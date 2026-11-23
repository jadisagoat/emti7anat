<?php
// 1. privilege block (content_type 14 = suggestions)
list($read_priv, $write_priv, $delete_priv) = bo_priv("14");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
require_once APPPATH . 'views/back_office/includes/content_cells.php';
$sego = bo_ct_order($this->uri->segment(4), array('title'));
// 2. all suggestions (no drill-down). Parents are resolved to "<fk>_name" ("Not available" for 0 / missing); the
//    legacy question cell ("show file" + delete-file link) is a raw column whose text is the file name.
//    Legacy list: list_legacy.php.
$rows = (isset($info) && is_array($info)) ? $info : array();
foreach ($rows as $i => $r) {
    $rows[$i]['question_cell'] = bo_ct_file_cell('suggestions', 'question', $r['question'], $r['id_suggestions'], $write_priv == 1);
}
$rows = bo_ct_lookups($rows, array('id_classes' => array('classes', 'title', 'id_classes'), 'id_material' => array('material', 'title', 'id_material'),
                                   'id_unit' => array('unit', 'title', 'id_unit'), 'id_section' => array('section', 'title', 'id_section'),
                                   'id_language' => array('language', 'title', 'id_language')));
$grid = array(
'module' => 'suggestions', 'key' => 'id_suggestions', 'rows' => $rows,
'title' => bo_t('ct_list_suggestions'),
'order' => $sego,
'columns' => array('title', 'id_classes_name', 'id_material_name', 'id_unit_name', 'id_section_name', 'id_language_name', 'question_cell'),
'types' => array('question_cell' => 'raw'),
'labels' => array('id_classes_name' => bo_label('class'), 'id_material_name' => bo_label('material'), 'id_unit_name' => bo_label('unit'),
                  'id_section_name' => bo_label('section'), 'id_language_name' => bo_label('language'), 'question_cell' => bo_label('question')),
'widths' => array('question_cell' => 160),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_t('ct_add_suggestions'),
// dragging rewrites sort_order in the visible order: only offered while the list is in sort_order
'bulk' => $delete_priv, 'reorder' => ($write_priv == 1 && ($sego === '' || $sego === 'sort_order')), 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
