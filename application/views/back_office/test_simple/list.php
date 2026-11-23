<?php
// 1. privilege block (content_type 23 = test simple)
list($read_priv, $write_priv, $delete_priv) = bo_priv("23");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
require_once APPPATH . 'views/back_office/includes/content_cells.php';
$sego = bo_ct_order(($this->uri->segment(3) == 'lists') ? $this->uri->segment(5) : $this->uri->segment(4), array('title'));
// 2. rows of the current subject (test_simple::lists($id_material) keeps id_material in the session).
//    The class shown is id_class (what the add form saves; id_classes stays empty), as in the legacy list.
//    Parents are resolved to "<fk>_name" ("Not available" for 0 / missing); legacy file cells ("show file" +
//    delete-file link) are raw columns whose text is the file name. Legacy list: list_legacy.php.
$rows = (isset($info) && is_array($info)) ? $info : array();
foreach ($rows as $i => $r) {
    foreach (array('simple_exam', 'solution') as $f) {
        $rows[$i][$f . '_cell'] = bo_ct_file_cell('test_simple', $f, $r[$f], $r['id_test_simple'], $write_priv == 1);
    }
}
$rows = bo_ct_lookups($rows, array('id_language' => array('language', 'title', 'id_language'), 'id_class' => array('classes', 'title', 'id_classes'),
                                   'id_material' => array('material', 'title', 'id_material')));
$grid = array(
'module' => 'test_simple', 'key' => 'id_test_simple', 'rows' => $rows,
'title' => bo_t('ct_list_test_simple'),
'subtitle' => bo_ct_chain_html(bo_ct_chain_material($this->session->userdata('id_material'))),
'order' => $sego,
'columns' => array('title', 'id_language_name', 'id_class_name', 'id_material_name', 'simple_exam_cell', 'solution_cell'),
'types' => array('simple_exam_cell' => 'raw', 'solution_cell' => 'raw'),
'labels' => array('id_language_name' => bo_label('language'), 'id_class_name' => bo_label('class'), 'id_material_name' => bo_label('material'),
                  'simple_exam_cell' => bo_t('ct_simple_exam'), 'solution_cell' => bo_label('solution')),
'widths' => array('simple_exam_cell' => 170, 'solution_cell' => 170),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_t('ct_add_test_simple'),
// dragging rewrites sort_order in the visible order: only offered while the list is in sort_order
'bulk' => $delete_priv, 'reorder' => ($write_priv == 1 && ($sego === '' || $sego === 'sort_order')), 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
