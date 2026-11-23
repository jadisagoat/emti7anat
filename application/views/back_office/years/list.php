<?php
// 1. privilege block (content_type 34 = years)
list($read_priv, $write_priv, $delete_priv) = bo_priv("34");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: inline mode (small lookup table), same columns as the legacy list (list_legacy.php).
//    Own list title and add caption (pl_years_list / pl_years_add, bo_dict_plain.php): $BO_MENU gives "years" (exam years)
//    and "year" (academic years) the same Arabic labels, so the two lists could not be told apart.
$grid = array(
'module' => 'years', 'key' => 'id_years', 'rows' => $info, 'title' => bo_t('pl_years_list'),
'order' => $this->uri->segment(4),
'columns' => array('title'),
'types' => array(),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_t('pl_years_add'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
