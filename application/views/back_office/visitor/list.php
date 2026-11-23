<?php
// 1. privilege block (content_type 25 = visitor)
list($read_priv, $write_priv, $delete_priv) = bo_priv("25");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: server mode (about 55,000 rows), rows come from visitor::grid_data() page by page.
//    Columns of the legacy list (title, ip, material) + class and visit date; the legacy view is list_legacy.php.
$grid = array(
'module' => 'visitor', 'key' => 'id_visitor', 'ajax' => true, 'title' => $title,
'order' => (isset($order) && $order !== '') ? $order : 'id_visitor desc',
'columns' => array('title', 'ip', 'id_material', 'id_class', 'date1'),
'types' => array('date1' => 'date'),
'labels' => array('id_material' => bo_label('material'), 'id_class' => bo_label('class')),
'lookups' => array('id_material' => array('material', 'title', 'id_material'), 'id_class' => array('classes', 'title', 'id_classes')),
'search' => array('title', 'ip', 'id_material', 'id_class'),
'widths' => array('ip' => 160, 'date1' => 120),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => sprintf(bo_t('grid_add_x'), bo_module_label('visitor', 'one')),
'bulk' => $delete_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
