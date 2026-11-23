
<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function delete_row(id){
window.location="<?= base_url(); ?>back_office/members/delete/"+id;
return false;
}
$(function(){
$("#table-1").tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$("#result").load("<?= base_url(); ?>back_office/members/sorted?"+ser);
}
});
});
</script>
<?php
$content_name="members";
$id_content=$this->fct->getonecell("content_type","id_content",array("name"=>"members"));
$sego =$this->uri->segment(4);
$gallery_status="0";
if($this->session->userdata("level") == 0){
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
$read_priv=$this->fct->getonecell("priviledge","read_p",$cond);
$write_priv=$this->fct->getonecell("priviledge","write_p",$cond);
$delete_priv=$this->fct->getonecell("priviledge","delete_p",$cond);
} else {
$read_priv = 1;
$write_priv=1;
$delete_priv = 1;
}
if($read_priv != 1){ redirect(base_url()."back_office/home/dashboard"); }
$today=date("Y-m-d");
?>
<link rel="stylesheet" href="<?= base_url(); ?>css/practice_admin.css" />
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view("back_office/includes/left_box"); ?>
</div>

<div class="span10 bo-practice <?= bo_dir_class(); ?>">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li class="active"><?= $title; ?> <small class="muted"><?= bo_t('pt_members_subtitle'); ?></small></li>
<?php if($write_priv == 1){ ?>
<li class="pull-right">
<a href="<?= base_url(); ?>back_office/members/add" id="topLink" title="<?= bo_t('pt_add_student'); ?>"><?= bo_t('pt_add_student'); ?></a>
</li><?php } ?>
</ul>
</div>
<div class="hundred pull-left">
<div id="result"></div>
<form action="<?= base_url(); ?>back_office/members/delete_all" method="post" name="list_form" >
<table class="table table-striped" id="table-1">
<thead>
<?php if($this->session->userdata("success_message")){ ?>
<tr><td colspan="7" align="center" style="text-align:center">
<div class="alert alert-success">
<?= $this->session->userdata("success_message"); ?>
</div>
</td>
<tr>
<?php } ?>
<tr>
<td>&nbsp;</td><th>
<a href="<?= base_url(); ?>back_office/members/index/name" class="<?php if($sego == "name") echo 'order_active'; else echo 'order_link'; ?>" ><?= bo_t('pt_name'); ?></a></th>
<th>
<a href="<?= base_url(); ?>back_office/members/index/username" class="<?php if($sego == "username") echo 'order_active'; else echo 'order_link'; ?>" ><?= bo_t('pt_username'); ?></a></th>
<th><a href="<?= base_url(); ?>back_office/members/index/access_end" class="<?php if($sego == "access_end") echo 'order_active'; else echo 'order_link'; ?>" ><?= bo_t('pt_access'); ?></a></th>
<th><?= bo_t('pt_status'); ?></th>
<th><?= bo_t('pt_last_login'); ?></th>
<th class="pt-actions-cell"><?= bo_t('pt_actions'); ?></th></tr></thead><tfoot><tr>
<td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
<td colspan="7"><div class="pull-right">
<?php if($delete_priv == 1){ ?>
<select class="form-select" name="check_option">
<option value="option1"><?= bo_t('pt_bulk'); ?></option>
<option value="delete_all"><?= bo_t('pt_delete_selected'); ?></option></select>
<a class="btn btn-primary btn_mrg" onclick="document.forms['list_form'].submit();" style="cursor:pointer;"><span><?= bo_t('pt_perform'); ?></span></a></div> <?php } ?></td>
</tr>
</tfoot>
<tbody>
<?php
if(count($info) > 0){
foreach($info as $val){
$expired=(!empty($val["access_end"]) && $val["access_end"] < $today);
$not_yet=(!empty($val["access_start"]) && $val["access_start"] > $today);
?>
<tr id="<?=$val["id_members"]; ?>">
<td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["id_members"] ; ?>" /></td>
<td><?php echo $val["name"]; ?><?php if(!empty($val["notes"])){ ?><br><small class="muted"><?= htmlspecialchars($val["notes"]); ?></small><?php } ?></td>
<td><?php echo $val["username"]; ?></td>
<td>
<?php if(empty($val["access_start"]) && empty($val["access_end"])){ ?><small class="muted"><?= bo_t('pt_unlimited_access'); ?></small>
<?php } else { ?>
<span dir="<?= bo_rtl() ? 'rtl' : 'ltr'; ?>"><bdi><?= $this->fct->date_out_formate($val["access_start"]); ?></bdi> <?= bo_rtl() ? '&larr;' : '&rarr;'; ?> <bdi><?= $this->fct->date_out_formate($val["access_end"]); ?></bdi></span>
<?php if($expired){ ?><br><span class="label label-important"><?= bo_t('pt_expired'); ?></span><?php } elseif($not_yet){ ?><br><span class="label label-warning"><?= bo_t('pt_not_started'); ?></span><?php } ?>
<?php } ?>
</td>
<td><?php if($val["status"]==1){ ?><span class="label label-success"><?= bo_t('pt_active'); ?></span><?php } else { ?><span class="label"><?= bo_t('pt_inactive'); ?></span><?php } ?></td>
<td><small><?= !empty($val["last_login"]) ? $val["last_login"] : "-"; ?></small></td>
<td class="pt-actions-cell">
<a href="<?= base_url();?>back_office/members/results/<?= $val["id_members"]; ?>" class="table-edit-link" title="<?= bo_t('pt_test_results'); ?>" >
<i class="icon-signal" ></i> <?= bo_t('pt_results'); ?></a> <span class="hidden"> | </span>
<?php if($write_priv == 1){ ?>
<a onclick="if(confirm('<?= addslashes(sprintf(bo_t('pt_renew_confirm'), htmlspecialchars($val["name"]), PT_ACCESS_YEARS)); ?>')){ window.location='<?= base_url();?>back_office/members/renew/<?= $val["id_members"]; ?>'; }" class="table-edit-link cur" title="<?= sprintf(bo_t('pt_renew_title'), PT_ACCESS_YEARS); ?>" >
<i class="icon-refresh" ></i> <?= bo_t('pt_renew'); ?></a> <span class="hidden"> | </span>
<a href="<?= base_url();?>back_office/members/edit/<?= $val["id_members"]; ?>" class="table-edit-link" title="<?= bo_t('pt_edit'); ?>" >
<i class="icon-edit" ></i> <?= bo_t('pt_edit'); ?></a> <span class="hidden"> | </span>
<?php } ?>
<?php if($delete_priv == 1){ ?>
<a onclick="if(confirm('<?= addslashes(bo_t('pt_confirm_delete_student')); ?>')){ delete_row('<?=$val["id_members"];?>'); }" class="table-delete-link cur" title="<?= bo_t('pt_delete'); ?>" >
<i class="icon-remove-sign" ></i> <?= bo_t('pt_delete'); ?></a>
<?php } ?>
</td>
</tr>
<?php }  } else { ?>
<tr class='odd'><td colspan="7" style='text-align:center;'><?= bo_t('pt_no_students'); ?></td></tr>
<?php  } ?>
</tbody>
</table>
</form>
</div>
</div>
</div>
</div>
<?php $this->session->unset_userdata("success_message"); ?>
<?php $this->session->unset_userdata("error_message"); ?>
