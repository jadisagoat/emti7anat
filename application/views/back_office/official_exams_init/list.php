<?php
// 1. privilege block (content_type 36 = official exams init)
list($read_priv, $write_priv, $delete_priv) = bo_priv("36");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: inline mode (about 170 rows), same columns as the legacy list (list_legacy.php):
//    title, class (classes.title), year (years.title) and the two session PDFs.
//    Each PDF cell is precomputed as a raw cell: a link to the file whose text is the file name (so search,
//    header filter and Excel export carry the name) plus, with write privilege, the "delete this file" link
//    (official_exams_init/delete_image/<field>/<file>/<id>). The cell HTML is kept short (root-relative URLs,
//    icons drawn by CSS, the file-name tooltip and the delete confirm set by one delegated handler below) because it
//    is repeated in every row of the inline payload.
//    delete_image() receives the name as a raw URI segment: CI 2.0.1 never urldecodes segments and _filter_uri
//    turns "(" ")" into "&#40;" "&#41;", so for a name that is not URL-safe the controller would clear the DB
//    field but miss the file on disk. Such names get a hint (replace the file from Edit) instead of the link.
$root = parse_url(base_url(), PHP_URL_PATH);
if (!is_string($root) || $root === '') $root = '/';
$files_url = $root . 'uploads/official_exams_init/';
$del_url = $root . 'back_office/official_exams_init/delete_image/';
$no_file = '<span class="text-muted">' . htmlspecialchars(bo_t('grid_no_file'), ENT_QUOTES, 'UTF-8') . '</span>';
$del_title = htmlspecialchars(bo_t('pl_delete_file'), ENT_QUOTES, 'UTF-8');
$hint_title = htmlspecialchars(bo_t('pl_file_replace_hint'), ENT_QUOTES, 'UTF-8');
$rows = is_array($info) ? $info : array();
foreach ($rows as $i => $r) {
    foreach (array('pdf_1st', 'pdf_2st') as $f) {
        $file = isset($r[$f]) ? trim((string) $r[$f]) : '';
        if ($file === '') {
            $html = $no_file;
        } else {
            $enc = rawurlencode($file);
            $name = htmlspecialchars($file, ENT_QUOTES, 'UTF-8');
            $html = '<a href="' . htmlspecialchars($files_url . $enc, ENT_QUOTES, 'UTF-8') . '" target="_blank" class="bo-oe-file">' . $name . '</a>';
            if ($write_priv == 1) {
                if ($enc === $file) {
                    $html .= '<a href="' . htmlspecialchars($del_url . $f . '/' . $enc . '/' . (int) $r['id_official_exams_init'], ENT_QUOTES, 'UTF-8')
                           . '" class="bo-del-file" title="' . $del_title . '"></a>';
                } else {
                    $html .= '<span class="bo-oe-hint" title="' . $hint_title . '"></span>';
                }
            }
        }
        $rows[$i][$f . '_html'] = $html;
    }
}
$one = bo_module_label('official_exams_init', 'one');
if (!bo_rtl()) $one = ucwords($one);
$grid = array(
'module' => 'official_exams_init', 'key' => 'id_official_exams_init', 'rows' => $rows, 'title' => $title,
'order' => $this->uri->segment(4),
'columns' => array('title', 'id_classes', 'id_years', 'pdf_1st_html', 'pdf_2st_html'),
'types' => array('pdf_1st_html' => 'raw', 'pdf_2st_html' => 'raw'),
'labels' => array('id_classes' => bo_t('pl_class'), 'id_years' => bo_t('pl_year'),
                  'pdf_1st_html' => bo_t('pl_pdf_1st'), 'pdf_2st_html' => bo_t('pl_pdf_2nd')),
'lookups' => array('id_classes' => array('classes', 'title', 'id_classes'), 'id_years' => array('years', 'title', 'id_years')),
'search' => array('title', 'id_classes', 'id_years', 'pdf_1st_html', 'pdf_2st_html'),
'widths' => array('id_years' => 150, 'pdf_1st_html' => 220, 'pdf_2st_html' => 220),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => sprintf(bo_t('grid_add_x'), $one),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
?>
<style>
/* boxicons glyphs: bx-file \ead5, bx-trash \ec50, bx-info-circle \eb21 (new_theme/assets/vendor/fonts/boxicons.css) */
.bo-oe-file { display: inline-block; max-width: calc(100% - 1.75rem); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle; direction: ltr; }
.bo-oe-file::before, .bo-del-file::before, .bo-oe-hint::before { font-family: "boxicons"; font-weight: normal; line-height: 1; vertical-align: -0.125em; }
.bo-oe-file::before { content: "\ead5"; margin-right: .25rem; }
.bo-del-file, .bo-oe-hint { display: inline-block; vertical-align: middle; margin: 0 .4rem; }
.bo-del-file { color: var(--bs-danger, #ff3e1d); }
.bo-del-file::before { content: "\ec50"; }
.bo-oe-hint { color: var(--bs-secondary, #8592a3); cursor: help; }
.bo-oe-hint::before { content: "\eb21"; }
</style>
<script>
(function () {
  var msg = <?= json_encode(bo_t('grid_confirm_delete_file'), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
  document.addEventListener('click', function (e) {
    var a = e.target && e.target.closest ? e.target.closest('a.bo-del-file') : null;
    if (a && !window.confirm(msg)) { e.preventDefault(); e.stopPropagation(); }
  }, true);
  // full file name as tooltip (the cell may truncate it)
  function tip(e) {
    var a = e.target && e.target.closest ? e.target.closest('a.bo-oe-file') : null;
    if (a && !a.title) a.title = a.textContent;
  }
  document.addEventListener('mouseover', tip, true);
  document.addEventListener('focusin', tip, true);
})();
</script>
<?php
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
