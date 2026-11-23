<?php
// 1. privilege block (content_type 30 = books, as in the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("30");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: legacy columns Title, Photo, Pdf, Language (+ "delete image" / "delete file" links),
//    add / edit / delete / bulk / drag sort. Files live in uploads/books/ (no thumbnail folder).
$grid = array(
'module' => 'books', 'key' => 'id_books', 'rows' => $info, 'title' => $title,
'order' => $this->uri->segment(4),
'columns' => array('title', 'photo', 'pdf', 'id_language'),
'labels' => array('id_language' => bo_label('language')),
'types' => array('photo' => 'image', 'pdf' => 'file'),
'files' => 'uploads/books/', 'thumb' => '',
'lookups' => array('id_language' => array('language', 'title', 'id_language')),
'widths' => array('photo' => 90, 'pdf' => 130, 'id_language' => 130),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => ($write_priv == 1) ? array(
    array('label' => bo_t('site_delete_image'), 'url' => 'books/delete_image/photo/{photo}/{id}', 'icon' => 'bx-image-alt',
          'confirm' => bo_t('grid_confirm_delete_image'), 'if' => 'photo'),
    array('label' => bo_t('site_delete_pdf'), 'url' => 'books/delete_image/pdf/{pdf}/{id}', 'icon' => 'bx-file-blank',
          'confirm' => bo_t('grid_confirm_delete_file'), 'if' => 'pdf')) : array()),
'add' => $write_priv, 'add_label' => bo_module_label('books', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
