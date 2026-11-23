<script>
function do_it_popup(id){
$('#myModal .modal-content').load('<?= base_url(); ?>back_office/home/do_it_pop_up/'+id, function(){ $('#myModal').modal('show'); });
}
function message_popup(id){
$('#sample-modal').load('<?= base_url(); ?>back_office/home/message_popup/'+id);
}
</script>
<?php
$bo_level = (int) $this->session->userdata('level');
$bo_base = base_url();
// read rights of the logged-in user (level 0 users only see what the priviledge table allows; ids = content_type.id_content)
list($bo_can_members) = bo_priv(16);
list($bo_can_tests) = bo_priv(37);
list($bo_can_results) = bo_priv(39);
$bo_admin = ($bo_level == 1 || $bo_level == 2);
$bo_students = $bo_can_members ? count($this->fct->getAll('members', 'sort_order')) : 0;
$bo_tests = $bo_can_tests ? count($this->fct->getAll('practice_test', 'sort_order')) : 0;
$bo_results = ($bo_admin || $bo_can_results) ? count($this->fct->getAll('practice_result', 'id_practice_result')) : 0;
?>
<div class="span10-fluid">
<ul class="breadcrumb">
<li class="active"><?= bo_t('dash_title') ?></li>
</ul>
</div>

<?php if ($bo_level == 1 || $bo_level == 2) { ?>
<div class="row g-3 mb-3 bo-tiles">
<div class="col-md-3 col-sm-6">
<a class="card bo-tile h-100" href="<?= $bo_base ?>back_office/settings" title="<?= bo_t('dash_settings_hint') ?>">
<div class="card-body d-flex align-items-center">
<span class="avatar flex-shrink-0 me-3"><span class="avatar-initial rounded bg-label-primary"><i class="bx bx-cog bx-sm"></i></span></span>
<div><span class="d-block fw-semibold"><?= bo_t('dash_settings') ?></span><small class="text-muted"><?= bo_t('dash_settings_hint') ?></small></div>
</div>
</a>
</div>
<div class="col-md-3 col-sm-6">
<a class="card bo-tile h-100" href="<?= $bo_base ?>back_office/messages" title="<?= count($new_message) ?> <?= bo_t('dash_new') ?>">
<div class="card-body d-flex align-items-center">
<span class="avatar flex-shrink-0 me-3"><span class="avatar-initial rounded bg-label-info"><i class="bx bx-envelope bx-sm"></i></span></span>
<div><span class="d-block fw-semibold"><?= bo_t('dash_messages') ?></span><h4 class="mb-0 d-inline"><?= count($messages) ?></h4>
<?php if (count($new_message) > 0) { ?> <span class="badge bg-danger rounded-pill"><?= count($new_message) ?> <?= bo_t('dash_new') ?></span><?php } ?>
</div>
</div>
</a>
</div>
<div class="col-md-3 col-sm-6">
<a class="card bo-tile h-100" href="<?= $bo_base ?>back_office/orderonline" title="<?= count($new_orderonline) ?> <?= bo_t('dash_new') ?>">
<div class="card-body d-flex align-items-center">
<span class="avatar flex-shrink-0 me-3"><span class="avatar-initial rounded bg-label-warning"><i class="bx bx-cart bx-sm"></i></span></span>
<div><span class="d-block fw-semibold"><?= bo_t('dash_orders') ?></span><h4 class="mb-0 d-inline"><?= count($orderonline) ?></h4>
<?php if (count($new_orderonline) > 0) { ?> <span class="badge bg-danger rounded-pill"><?= count($new_orderonline) ?> <?= bo_t('dash_new') ?></span><?php } ?>
</div>
</div>
</a>
</div>
<div class="col-md-3 col-sm-6">
<a class="card bo-tile h-100" href="<?= $bo_base ?>back_office/practice_result">
<div class="card-body d-flex align-items-center">
<span class="avatar flex-shrink-0 me-3"><span class="avatar-initial rounded bg-label-success"><i class="bx bx-line-chart bx-sm"></i></span></span>
<div><span class="d-block fw-semibold"><?= bo_t('dash_reports') ?></span><h4 class="mb-0 d-inline"><?= $bo_results ?></h4></div>
</div>
</a>
</div>
</div>
<?php } ?>

<div class="row g-3">
<div class="col-lg-8">
<div class="card h-100">
<div class="card-header d-flex align-items-center justify-content-between">
<h5 class="card-title m-0 me-2"><i class="bx bx-list-check me-1"></i><?= bo_t('dash_contents') ?></h5>
</div>
<div class="card-body">
<ul class="list-group list-group-flush bo-contents">
<?php
$content_type = $this->fct->getAll('content_type', 'sort_order');
foreach ($content_type as $val) {
    if ($val["position"] != 0) {
        if ($bo_level == 0) {
            $cond = array('id_user' => $this->session->userdata('user_id'), 'id_content' => $val["id_content"]);
            $read_priv = $this->fct->getonecell('priviledge', 'read_p', $cond);
        } else { $read_priv = 1; }
        if ($read_priv == 1) {
            $used = $val["used_name"] ? $val["used_name"] : str_replace(" ", "_", $val["name"]);
            $e = bo_menu_entry($val["name"]);
            $icon = $e ? $e['icon'] : 'bx-grid';
            $url = ($e && isset($e['url'])) ? $e['url'] : $used;
            $item_count = $this->fct->getAll($used, 'sort_order');
?>
<li class="list-group-item d-flex align-items-center px-0">
<i class="bx <?= $icon ?> me-2 text-primary"></i>
<a href="<?= $bo_base ?>back_office/<?= $url ?>" class="flex-grow-1 text-body"><?= bo_module_label($val["name"]) ?></a>
<span class="badge bg-label-<?= count($item_count) ? 'primary' : 'secondary' ?> rounded-pill"><?= count($item_count) ? count($item_count) : bo_t('dash_none') ?></span>
</li>
<?php } } } ?>
</ul>
</div>
</div>
</div>
<?php if ($bo_can_members || $bo_can_tests || $bo_can_results) { ?>
<div class="col-lg-4">
<div class="card h-100">
<div class="card-header"><h5 class="card-title m-0"><i class="bx bx-link me-1"></i><?= bo_t('dash_shortcuts') ?></h5></div>
<div class="card-body">
<div class="d-grid gap-2 bo-shortcuts">
<?php if ($bo_can_members) { ?>
<a class="btn btn-outline-primary" href="<?= $bo_base ?>back_office/members"><i class="bx bx-group"></i><span><?= bo_t('dash_students') ?></span><span class="badge bg-label-primary"><?= $bo_students ?></span></a>
<?php } ?>
<?php if ($bo_can_tests) { ?>
<a class="btn btn-outline-primary" href="<?= $bo_base ?>back_office/practice_test"><i class="bx bx-edit"></i><span><?= bo_t('dash_tests') ?></span><span class="badge bg-label-primary"><?= $bo_tests ?></span></a>
<?php } ?>
<?php if ($bo_can_results) { ?>
<a class="btn btn-outline-primary" href="<?= $bo_base ?>back_office/practice_result"><i class="bx bx-line-chart"></i><span><?= bo_t('dash_reports') ?></span><span class="badge bg-label-primary"><?= $bo_results ?></span></a>
<?php } ?>
</div>
</div>
</div>
</div>
<?php } ?>
</div>
