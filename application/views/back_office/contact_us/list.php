<?php
// 1. privilege block (content_type 22, the id the legacy view list_legacy.php used)
list($read_priv, $write_priv, $delete_priv) = bo_priv("22");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: single-record page reached from a direct menu item. As in the legacy view: Edit only
//    (no add link, no delete, no bulk form, no drag sort). Legacy column: Title.
//    Title = the $BO_MENU key, which bo_page_title() turns into the menu label ("List <module>" reads badly for a one-record page;
//    a translated label must not be passed: bo_page_title() would send it back through the alias lookup).
$grid = array(
'module' => 'contact_us', 'key' => 'id_contact_us', 'rows' => $info, 'title' => 'contact us',
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
