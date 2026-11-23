<?php
// 1. privilege block (content_type 12 = year)
list($read_priv, $write_priv, $delete_priv) = bo_priv("12");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: inline mode (small lookup table), same columns as the legacy list (list_legacy.php).
//    Own list title and add caption (pl_year_list / pl_year_add, bo_dict_plain.php): $BO_MENU gives "year" (academic years)
//    and "years" (exam years) the same Arabic labels, so the two lists could not be told apart.
$grid = array(
'module' => 'year', 'key' => 'id_year', 'rows' => $info, 'title' => bo_t('pl_year_list'),
'order' => $this->uri->segment(4),
'columns' => array('title', 'status'),
'types' => array('status' => 'status'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_t('pl_year_add'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
