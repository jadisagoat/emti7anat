<?php
// 1. privilege block (content_type 29 = orderonline_us, the id bo_guard enforces; the legacy view used 22 = contact us by mistake)
list($read_priv, $write_priv, $delete_priv) = bo_priv("29");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: single-record page reached from a direct menu item. As in the legacy view: Edit only
//    (no add link, no delete, no bulk form, no drag sort). Legacy column: Title.
//    Title = the $BO_MENU key, which bo_page_title() turns into the menu label ("List <module>" reads badly for a one-record page;
//    a translated label must not be passed: bo_page_title() would send it back through the alias lookup).
$grid = array(
'module' => 'orderonline_us', 'key' => 'id_orderonline_us', 'rows' => $info, 'title' => 'orderonline_us',
'order' => $this->uri->segment(4),
'columns' => array('title'),
'actions' => array('edit' => $write_priv, 'delete' => 0),
'add' => false, 'bulk' => false, 'reorder' => false, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
