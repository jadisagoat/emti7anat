<?php
// 1. privilege block (content_type 33 = typesession)
list($read_priv, $write_priv, $delete_priv) = bo_priv("33");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: inline mode (small lookup table), same columns as the legacy list (list_legacy.php).
$one = bo_module_label('typesession', 'one');
if (!bo_rtl()) $one = ucwords($one);
$grid = array(
'module' => 'typesession', 'key' => 'id_typesession', 'rows' => $info, 'title' => $title,
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
