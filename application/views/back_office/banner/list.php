<?php
// 1. privilege block (content_type 24 = banner, as in the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("24");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: legacy columns Title, Link, Image (+ "delete image" link), add / edit / delete / bulk / drag sort.
//    The link is an external URL: precomputed as a trusted 'raw' cell (the grid 'link' type only builds back-office URLs).
$rows = is_array($info) ? $info : array();
foreach ($rows as $i => $r) {
    $u = trim((string) $r["link"]);
    $rows[$i]["link_html"] = ($u === '') ? '' : (preg_match('#^(https?:)?//#i', $u)
        ? '<a href="' . htmlspecialchars($u, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener" dir="ltr">' . htmlspecialchars($u, ENT_QUOTES, 'UTF-8') . '</a>'
        : '<span dir="ltr">' . htmlspecialchars($u, ENT_QUOTES, 'UTF-8') . '</span>');
}
$grid = array(
'module' => 'banner', 'key' => 'id_banner', 'rows' => $rows, 'title' => $title,
'order' => $this->uri->segment(4),
'columns' => array('title', 'link_html', 'image'),
'labels' => array('link_html' => bo_label('link'), 'image' => bo_label('image')),
'types' => array('link_html' => 'raw', 'image' => 'image'),
'files' => 'uploads/banner/', 'thumb' => '374x229',
'widths' => array('image' => 130),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => ($write_priv == 1) ? array(
    array('label' => bo_t('site_delete_image'), 'url' => 'banner/delete_image/image/{image}/{id}', 'icon' => 'bx-image-alt',
          'confirm' => bo_t('grid_confirm_delete_image'), 'if' => 'image')) : array()),
'add' => $write_priv, 'add_label' => bo_module_label('banner', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
