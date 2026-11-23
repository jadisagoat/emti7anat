<?php
// 1. privilege block (content_type 8 = language); the legacy view used id 15 (= official exams), corrected to match bo_guard
list($read_priv, $write_priv, $delete_priv) = bo_priv("8");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: inline mode (small lookup table), same columns as the legacy list (list_legacy.php).
$one = bo_module_label('language', 'one');
if (!bo_rtl()) $one = ucwords($one);
$grid = array(
'module' => 'language', 'key' => 'id_language', 'rows' => $info, 'title' => $title,
'order' => $this->uri->segment(4),
'columns' => array('title'),
'types' => array(),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => sprintf(bo_t('grid_add_x'), $one),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
