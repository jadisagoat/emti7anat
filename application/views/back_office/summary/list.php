<?php
// 1. privilege block (content_type 6 = summary)
list($read_priv, $write_priv, $delete_priv) = bo_priv("6");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
require_once APPPATH . 'views/back_office/includes/content_cells.php';
// legacy order link: summary/index/<field> (segment 4) or summary/lists/<id_section>/<field> (segment 5)
$sego = bo_ct_order(($this->uri->segment(3) == 'lists') ? $this->uri->segment(5) : $this->uri->segment(4), array('title'));
// 2. rows of the current lesson (summary::lists($id_section) keeps id_section in the session). Parents are resolved
//    to "<fk>_name" ("Not available" for 0 / missing); the legacy file cell ("show file" + delete-file link) is a raw
//    column whose text is the file name. Legacy list: list_legacy.php.
$rows = (isset($info) && is_array($info)) ? $info : array();
foreach ($rows as $i => $r) {
    $rows[$i]['file_pdf_cell'] = bo_ct_file_cell('summary', 'file_pdf', $r['file_pdf'], $r['id_summary'], $write_priv == 1);
}
$rows = bo_ct_lookups($rows, array('id_material' => array('material', 'title', 'id_material'), 'id_unit' => array('unit', 'title', 'id_unit'),
                                   'id_section' => array('section', 'title', 'id_section'), 'id_language' => array('language', 'title', 'id_language')));
$grid = array(
'module' => 'summary', 'key' => 'id_summary', 'rows' => $rows,
'title' => bo_t('ct_list_summary'),
'subtitle' => bo_ct_chain_html(bo_ct_chain_section($this->session->userdata('id_section'))),
'order' => $sego,
'columns' => array('title', 'id_material_name', 'id_unit_name', 'id_section_name', 'id_language_name', 'file_pdf_cell'),
'types' => array('file_pdf_cell' => 'raw'),
'labels' => array('id_material_name' => bo_label('material'), 'id_unit_name' => bo_label('unit'), 'id_section_name' => bo_label('section'),
                  'id_language_name' => bo_label('language'), 'file_pdf_cell' => bo_t('ct_file_pdf')),
'widths' => array('file_pdf_cell' => 170),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_t('ct_add_summary'),
// dragging rewrites sort_order in the visible order: only offered while the list is in sort_order
'bulk' => $delete_priv, 'reorder' => ($write_priv == 1 && ($sego === '' || $sego === 'sort_order')), 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
