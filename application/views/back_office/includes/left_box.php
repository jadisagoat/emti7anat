<?php if (defined('BO_MENU_RENDERED')) return; define('BO_MENU_RENDERED', 1);
/* Vertical menu (Sneat). Included once by template.php; the per-view includes above return immediately. */
$cond = array('position' => 2);
$menu_left = $this->fct->getAll_cond('content_type', 'sort_order', $cond);
$menu_left_seg1 = $this->uri->segment(2);
$menu_left_seg2 = $this->uri->segment(3);
$user_id = $this->session->userdata('user_id');
$bo_level = (int) $this->session->userdata('level');
$cond = array('admin_id' => 2);
$title_site = $this->fct->getonerow('admin', $cond);
$bo_lang = bo_lang();

// drill-down modules highlight their parent entry
$bo_alias = array(
'material' => 'class', 'contributors' => 'class', 'unit' => 'class', 'test_simple' => 'class',
'section' => 'class', 'summary' => 'class', 'questions_from_exams' => 'class',
'practice_question' => 'practice_test', 'install' => 'control', 'manage_gallery' => 'gallery',
);
$bo_cur = isset($bo_alias[$menu_left_seg1]) ? $bo_alias[$menu_left_seg1] : $menu_left_seg1;

$bo_groups = array('site' => array(), 'exams' => array(), 'practice' => array(), 'system' => array());
foreach ($menu_left as $val) {
    if ($bo_level == 0) {
        $cond = array('id_user' => $user_id, 'id_content' => $val["id_content"]);
        $read_priv = $this->fct->getonecell('priviledge', 'read_p', $cond);
    } else { $read_priv = 1; }
    if ($read_priv != 1) continue;
    $used = $val["used_name"] ? $val["used_name"] : str_replace(" ", "_", $val["name"]);
    $e = bo_menu_entry($val["name"]);
    if (!$e) {
        $e = array('group' => 'site', 'icon' => 'bx-grid', 'kind' => 'submenu', 'en' => ucwords($val["name"]), 'ar' => ucwords($val["name"]));
    }
    $group = ($e['group'] && isset($bo_groups[$e['group']])) ? $e['group'] : 'site';
    $url = isset($e['url']) ? $e['url'] : $used;
    $url_add = isset($e['url_add']) ? $e['url_add'] : $used . '/add';
    $bo_groups[$group][] = array(
        'used' => $used, 'url' => $url, 'url_add' => $url_add, 'icon' => $e['icon'], 'kind' => $e['kind'],
        'label' => bo_module_label($val["name"]), 'list' => bo_module_label($val["name"], 'list'), 'new' => bo_module_label($val["name"], 'new'),
        'active' => ($bo_cur == $used),
    );
}
// System group (hard-coded pages)
$bo_groups['system'][] = array('used' => 'settings', 'url' => 'settings', 'icon' => 'bx-cog', 'kind' => 'direct', 'label' => bo_t('nav_settings'), 'active' => ($bo_cur == 'settings'));
if ($bo_level == 1) {
    $bo_groups['system'][] = array('used' => 'users', 'url' => 'users', 'url_add' => 'users/add', 'icon' => 'bx-user', 'kind' => 'submenu', 'label' => bo_t('nav_users'), 'list' => bo_module_label('users', 'list'), 'new' => bo_module_label('users', 'new'), 'active' => ($bo_cur == 'users'));
}
// $cond = array('rules' => 'RECYCLE BIN');
// $user_rule = $this->fct->getonecell('admin_rules', 'active', $cond);
// if ($bo_level == 1 || $bo_level == 2 || ($user_rule == 1 && $bo_level != 0)) {
//     $bo_groups['system'][] = array('used' => 'recycle', 'url' => 'recycle', 'icon' => 'bx-trash', 'kind' => 'direct', 'label' => bo_t('nav_recycle'), 'active' => ($bo_cur == 'recycle'));
// }
if ($bo_level == 1) {
    $bo_groups['system'][] = array('used' => 'control', 'url' => 'control', 'icon' => 'bx-wrench', 'kind' => 'direct', 'label' => bo_t('nav_content_types'), 'active' => ($bo_cur == 'control' && $menu_left_seg2 != 'admin_rules'));
    $bo_groups['system'][] = array('used' => 'admin_rules', 'url' => 'control/admin_rules', 'icon' => 'bx-shield', 'kind' => 'direct', 'label' => bo_t('nav_admin_rules'), 'active' => ($bo_cur == 'control' && $menu_left_seg2 == 'admin_rules'));
}
// pages reached from the dashboard that have no menu entry of their own highlight "Dashboard"
$bo_any_active = false;
foreach ($bo_groups as $items) { foreach ($items as $it) { if (!empty($it['active'])) $bo_any_active = true; } }
$bo_dash_active = ($menu_left_seg1 == 'home') || (!$bo_any_active && in_array($menu_left_seg1, array('messages', 'todo', 'newsletter', 'orderonline', 'users', 'control', 'modules')));
$bo_group_labels = array('site' => bo_t('group_site'), 'exams' => bo_t('group_exams'), 'practice' => bo_t('group_practice'), 'system' => bo_t('group_system'));
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
<div class="app-brand demo">
<a href="<?= base_url() ?>back_office/home/dashboard" class="app-brand-link">
<span class="app-brand-logo demo"><img src="<?= base_url() ?>uploads/website/45x45/<?= $title_site["image"] ?>" width="40" height="40" alt="" /></span>
<span class="app-brand-text demo menu-text fw-bold ms-2 bo-brand"><?= htmlspecialchars((string) $title_site["website_title"], ENT_QUOTES, 'UTF-8') ?></span>
</a>
<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none"><i class="bx bx-chevron-left bx-sm align-middle"></i></a>
</div>
<div class="menu-inner-shadow"></div>
<ul class="menu-inner py-1">
<li class="menu-item <?= $bo_dash_active ? 'active' : '' ?>">
<a href="<?= base_url() ?>back_office/home/dashboard" class="menu-link"><i class="menu-icon tf-icons bx bx-home-circle"></i><div><?= bo_t('nav_dashboard') ?></div></a>
</li>
<?php foreach ($bo_groups as $gkey => $items) { if (!count($items)) continue; ?>
<li class="menu-header small text-uppercase"><span class="menu-header-text"><?= $bo_group_labels[$gkey] ?></span></li>
<?php foreach ($items as $it) {
    if ($it['kind'] == 'submenu') { ?>
<li class="menu-item <?= $it['active'] ? 'active open' : '' ?>">
<a href="javascript:void(0);" class="menu-link menu-toggle"><i class="menu-icon tf-icons bx <?= $it['icon'] ?>"></i><div><?= $it['label'] ?></div></a>
<ul class="menu-sub">
<li class="menu-item <?= ($it['active'] && $menu_left_seg2 != 'add') ? 'active' : '' ?>"><a href="<?= base_url() ?>back_office/<?= $it['url'] ?>" class="menu-link"><div><?= $it['list'] ?></div></a></li>
<li class="menu-item <?= ($it['active'] && $menu_left_seg2 == 'add') ? 'active' : '' ?>"><a href="<?= base_url() ?>back_office/<?= $it['url_add'] ?>" class="menu-link"><div><?= $it['new'] ?></div></a></li>
</ul>
</li>
<?php } else { ?>
<li class="menu-item <?= $it['active'] ? 'active' : '' ?>">
<a href="<?= base_url() ?>back_office/<?= $it['url'] ?>" class="menu-link"><i class="menu-icon tf-icons bx <?= $it['icon'] ?>"></i><div><?= $it['label'] ?></div></a>
</li>
<?php } } } ?>
</ul>
</aside>
