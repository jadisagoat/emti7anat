<?php
// 1. privilege block (content_type 9 = questions from exams, "supplementary problems")
list($read_priv, $write_priv, $delete_priv) = bo_priv("9");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
require_once APPPATH . 'views/back_office/includes/content_cells.php';
// legacy order link: <module>/index/<field> (segment 4) or <module>/lists/<id_section>/<field> (segment 5)
$sego = bo_ct_order(($this->uri->segment(3) == 'lists') ? $this->uri->segment(5) : $this->uri->segment(4), array('title'));
// 2. rows of the current lesson (lists($id_section) keeps id_section in the session). Parents are resolved to
//    "<fk>_name" ("Not available" for 0 / missing); legacy file cells ("show file" + delete-file link) are raw
//    columns whose text is the file name. Legacy list: list_legacy.php.
$rows = (isset($info) && is_array($info)) ? $info : array();
foreach ($rows as $i => $r) {
    foreach (array('question', 'answer') as $f) {
        $rows[$i][$f . '_cell'] = bo_ct_file_cell('questions_from_exams', $f, $r[$f], $r['id_questions_from_exams'], $write_priv == 1);
    }
}
$rows = bo_ct_lookups($rows, array('id_language' => array('language', 'title', 'id_language'), 'id_material' => array('material', 'title', 'id_material'),
                                   'id_unit' => array('unit', 'title', 'id_unit'), 'id_section' => array('section', 'title', 'id_section')));
$grid = array(
'module' => 'questions_from_exams', 'key' => 'id_questions_from_exams', 'rows' => $rows,
'title' => bo_t('ct_list_questions_from_exams'),
'subtitle' => bo_ct_chain_html(bo_ct_chain_section($this->session->userdata('id_section'))),
'order' => $sego,
'columns' => array('title', 'id_language_name', 'id_material_name', 'id_unit_name', 'id_section_name', 'question_cell', 'answer_cell'),
'types' => array('question_cell' => 'raw', 'answer_cell' => 'raw'),
'labels' => array('id_language_name' => bo_label('language'), 'id_material_name' => bo_label('material'), 'id_unit_name' => bo_label('unit'),
                  'id_section_name' => bo_label('section'), 'question_cell' => bo_label('question'), 'answer_cell' => bo_label('answer')),
// tuned so the actions column stays visible at 1400px (no adaptive "..." column)
'widths' => array('id_language_name' => 75, 'id_material_name' => 80, 'id_unit_name' => 80, 'id_section_name' => 80, 'question_cell' => 130, 'answer_cell' => 130),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_t('ct_add_questions_from_exams'),
// dragging rewrites sort_order in the visible order: only offered while the list is in sort_order
'bulk' => $delete_priv, 'reorder' => ($write_priv == 1 && ($sego === '' || $sego === 'sort_order')), 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
