<?php
/**
 * Shared helpers of the "content" batch lists (summary, questions_from_exams, official_exams, suggestions,
 * test_simple, contributors). Loaded with require_once from those list.php views, before the dxgrid config.
 *
 *  bo_ct_file_cell($module, $field, $file, $id, $can_delete, $kind)
 *      Trusted HTML for a dxgrid 'raw' column that reproduces the legacy cell plus its trash link to
 *      <module>/delete_image/<field>/<file>/<id> (with the legacy confirm). The cell's TEXT is the file name
 *      (shown truncated for files, visually hidden for images), so the inline grid's search, sort, header
 *      filter and xlsx export work on the file name. Empty value => "No file" / "No image".
 *      The whole cell is one non-wrapping line (the trash icon never drops below the link).
 *  bo_ct_lookups($rows, $lookups)   resolves fk => array(table, display, pk) into "<fk>_name" with
 *      "Not available" for 0 / missing parents (dxgrid's own lookups leave those cells blank).
 *  bo_ct_chain_section($id_section)   chain Subjects > [Class] > Subject > Unit > Lesson for the subtitle.
 *  bo_ct_chain_material($id_material) chain Subjects > [Class] > Subject for the subtitle.
 *      Both follow the chain batch (section/list.php): the root is "Subjects" (material/index), the class crumb
 *      only shows when the session class owns the subject, and deleted parents are skipped.
 *  bo_ct_chain_html($chain)  the chain as trusted subtitle HTML, every crumb isolated in <bdi>, joined with
 *      U+203A (bidi-mirrored, so the RTL breadcrumb draws it pointing left), exactly like the chain batch.
 *  bo_ct_order($sego, $allowed)      the legacy order segment, kept only when it names an allowed field.
 */
if (!function_exists('bo_ct_file_cell')) {
    function bo_ct_file_cell($module, $field, $file, $id, $can_delete, $kind = 'file')
    {
        $h = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
        $file = trim((string) $file);
        if ($file === '') {
            return '<small class="text-muted">' . $h(bo_t($kind === 'image' ? 'grid_no_image' : 'grid_no_file')) . '</small>';
        }
        $url = base_url() . 'uploads/' . $module . '/' . rawurlencode($file);
        if ($kind === 'image') {
            $out = '<a href="' . $h($url) . '" class="gallery" title="' . $h($file) . '"><img class="bo-thumb" alt="" src="' . $h($url) . '" /></a>'
                 . '<span class="visually-hidden">' . $h($file) . '</span>';
        } else {
            $out = '<a href="' . $h($url) . '" class="blue text-truncate" style="min-width:0" target="_blank" dir="ltr" title="'
                 . $h(bo_t('grid_show_file') . ': ' . $file) . '"><i class="bx bx-file"></i> ' . $h($file) . '</a>';
        }
        if ($can_delete) {
            $del = base_url() . 'back_office/' . $module . '/delete_image/' . $field . '/' . rawurlencode($file) . '/' . (int) $id;
            $msg = bo_t($kind === 'image' ? 'grid_confirm_delete_image' : 'grid_confirm_delete_file');
            $out .= '<a href="' . $h($del) . '" class="text-danger ms-2 flex-shrink-0" title="' . $h(bo_t($kind === 'image' ? 'ct_delete_image' : 'ct_delete_file'))
                  . '" aria-label="' . $h(bo_t($kind === 'image' ? 'ct_delete_image' : 'ct_delete_file'))
                  . '" onclick="return confirm(' . $h(json_encode($msg, JSON_UNESCAPED_UNICODE)) . ');"><i class="bx bx-trash"></i></a>';
        }
        return '<span class="bo-ct-file d-inline-flex align-items-center text-nowrap mw-100">' . $out . '</span>';
    }
}

if (!function_exists('bo_ct_lookups')) {
    function bo_ct_lookups($rows, $lookups)
    {
        $CI =& get_instance();
        $na = bo_t('grid_not_available');
        foreach ($lookups as $fk => $lk) {
            list($table, $display, $pk) = $lk;
            $cache = array();
            foreach ($rows as $i => $r) {
                $v = isset($r[$fk]) ? (int) $r[$fk] : 0;
                if (!array_key_exists($v, $cache)) {
                    $t = $v > 0 ? trim((string) $CI->fct->getonecell($table, $display, array($pk => $v))) : '';
                    $cache[$v] = ($t === '') ? $na : $t;
                }
                $rows[$i][$fk . '_name'] = $cache[$v];
            }
        }
        return $rows;
    }
}

if (!function_exists('bo_ct_order')) {
    function bo_ct_order($sego, $allowed)
    {
        $sego = (string) $sego;
        return in_array($sego, array_merge(array('sort_order'), $allowed), true) ? $sego : '';
    }
}

if (!function_exists('bo_ct_chain_material')) {
    /** Subjects > [Class] > Subject. */
    function bo_ct_chain_material($id_material, $link_material = false)
    {
        $CI =& get_instance();
        $chain = array(array('label' => bo_t('ct_subjects'), 'url' => 'material/index'));
        $id_material = (int) $id_material;
        $material = $id_material > 0 ? $CI->fct->getonerow('material', array('id_material' => $id_material, 'deleted' => 0)) : array();
        if (empty($material)) return $chain;
        // class crumb: only when the class of the drill-down (session) owns this subject and is not deleted
        $id_classes = (int) $CI->session->userdata('id_classes');
        if ($id_classes > 0 && (int) $material['id_classes'] === $id_classes) {
            $class = $CI->fct->getonerow('classes', array('id_classes' => $id_classes, 'deleted' => 0));
            if (!empty($class) && trim((string) $class['title']) !== '') $chain[] = array('label' => $class['title'], 'url' => 'material/lists/' . $id_classes);
        }
        $chain[] = array('label' => $material['title'], 'url' => $link_material ? 'unit/lists/' . $id_material : '');
        return $chain;
    }
}

if (!function_exists('bo_ct_chain_section')) {
    /** Subjects > [Class] > Subject > Unit > Lesson, from the section row up (falls back to the session ids). */
    function bo_ct_chain_section($id_section)
    {
        $CI =& get_instance();
        $id_section = (int) $id_section;
        $section = $id_section > 0 ? $CI->fct->getonerow('section', array('id_section' => $id_section, 'deleted' => 0)) : array();
        $id_unit = !empty($section['id_unit']) ? (int) $section['id_unit'] : (int) $CI->session->userdata('id_unit');
        $unit = $id_unit > 0 ? $CI->fct->getonerow('unit', array('id_unit' => $id_unit, 'deleted' => 0)) : array();
        $id_material = !empty($unit['id_material']) ? (int) $unit['id_material']
                     : (!empty($section['id_material']) ? (int) $section['id_material'] : (int) $CI->session->userdata('id_material'));
        $chain = bo_ct_chain_material($id_material, true);
        if (!empty($unit) && trim((string) $unit['title']) !== '') $chain[] = array('label' => $unit['title'], 'url' => 'section/lists/' . $id_unit);
        if (!empty($section) && trim((string) $section['title']) !== '') $chain[] = array('label' => $section['title']);
        return $chain;
    }
}

if (!function_exists('bo_ct_chain_html')) {
    function bo_ct_chain_html($chain)
    {
        if (!is_array($chain) || !count($chain)) return '';
        $parts = array();
        foreach ($chain as $c) {
            $label = htmlspecialchars((string) (isset($c['label']) ? $c['label'] : ''), ENT_QUOTES, 'UTF-8');
            $parts[] = '<bdi>' . (!empty($c['url'])
                ? '<a href="' . htmlspecialchars(base_url() . 'back_office/' . ltrim($c['url'], '/'), ENT_QUOTES, 'UTF-8') . '">' . $label . '</a>'
                : $label) . '</bdi>';
        }
        // always U+203A: it is Bidi_Mirrored, so the RTL breadcrumb draws it pointing left (same as section/list.php)
        return implode(' &rsaquo; ', $parts);
    }
}
