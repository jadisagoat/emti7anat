<?php
// 1. privilege block (content_type 15 = official exams, "session problems")
list($read_priv, $write_priv, $delete_priv) = bo_priv("15");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
require_once APPPATH . 'views/back_office/includes/content_cells.php';
$sego = bo_ct_order(($this->uri->segment(3) == 'lists') ? $this->uri->segment(5) : $this->uri->segment(4), array());
// 2. the controller lists the rows of the lesson kept in the session (id_section); the legacy list shows
//    Class / Subject / Language / Question / Answer (the add form has no title field, so no title column).
//    Parents are resolved to "<fk>_name" ("Not available" for 0 / missing); legacy file cells ("show file" +
//    delete-file link) are raw columns whose text is the file name. Legacy list: list_legacy.php.
$rows = (isset($info) && is_array($info)) ? $info : array();
foreach ($rows as $i => $r) {
    foreach (array('question', 'answer') as $f) {
        $rows[$i][$f . '_cell'] = bo_ct_file_cell('official_exams', $f, $r[$f], $r['id_official_exams'], $write_priv == 1);
    }
}
$rows = bo_ct_lookups($rows, array('id_classes' => array('classes', 'title', 'id_classes'), 'id_material' => array('material', 'title', 'id_material'),
                                   'id_language' => array('language', 'title', 'id_language')));
$grid = array(
'module' => 'official_exams', 'key' => 'id_official_exams', 'rows' => $rows,
'title' => bo_t('ct_list_official_exams'),
'subtitle' => bo_ct_chain_html(bo_ct_chain_section($this->session->userdata('id_section'))),
'order' => $sego,
'columns' => array('id_classes_name', 'id_material_name', 'id_language_name', 'question_cell', 'answer_cell'),
'types' => array('question_cell' => 'raw', 'answer_cell' => 'raw'),
'labels' => array('id_classes_name' => bo_label('class'), 'id_material_name' => bo_label('material'), 'id_language_name' => bo_label('language'),
                  'question_cell' => bo_label('question'), 'answer_cell' => bo_label('answer')),
'widths' => array('question_cell' => 190, 'answer_cell' => 190),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_t('ct_add_official_exams'),
// dragging rewrites sort_order in the visible order: only offered while the list is in sort_order
'bulk' => $delete_priv, 'reorder' => ($write_priv == 1 && ($sego === '' || $sego === 'sort_order')), 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
