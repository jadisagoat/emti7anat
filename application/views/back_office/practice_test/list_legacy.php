
<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function delete_row(id){
window.location="<?= base_url(); ?>back_office/practice_test/delete/"+id;
return false;
}
$(function(){
$("#table-1").tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$("#result").load("<?= base_url(); ?>back_office/practice_test/sorted?"+ser);
}
});
});
</script>
<?php
$content_name="practice test";
$id_content=$this->fct->getonecell("content_type","id_content",array("name"=>"practice test"));
$sego =$this->uri->segment(4);
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
<li class="active"><?= $title; ?> <small class="muted"><?= bo_t('pt_drag_hint'); ?></small></li>
<?php if($write_priv == 1){ ?>
<li class="pull-right">
<a href="<?= base_url(); ?>back_office/practice_test/add" id="topLink" title="<?= bo_t('pt_add_test'); ?>"><?= bo_t('pt_add_test'); ?></a>
</li><?php } ?>
</ul>
</div>
<div class="hundred pull-left">
<div id="result"></div>
<form action="<?= base_url(); ?>back_office/practice_test/delete_all" method="post" name="list_form" >
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
<a href="<?= base_url(); ?>back_office/practice_test/index/title" class="<?php if($sego == "title") echo 'order_active'; else echo 'order_link'; ?>" ><?= bo_t('pt_title'); ?></a></th>
<th><?= bo_t('pt_lesson'); ?></th>
<th><?= bo_t('pt_questions'); ?></th>
<th><?= bo_t('pt_settings'); ?></th>
<th><?= bo_t('pt_status'); ?></th>
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
$qcount=count($this->fct->getAll_cond("practice_question","sort_order",array("id_practice_test"=>$val["id_practice_test"])));
$need=(int)$val["questions_per_test"];
$lesson=$val["id_section"] ? $this->fct->getonecell("section","title",array("id_section"=>(int)$val["id_section"])) : "";
?>
<tr id="<?=$val["id_practice_test"]; ?>">
<td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["id_practice_test"] ; ?>" /></td>
<td><?php echo $val["title"]; ?></td>
<td><?= $lesson ? $lesson : "<small class='muted'>".bo_t('pt_standalone')."</small>"; ?></td>
<td>
<?php if($need>0){ ?>
<span class="<?= $qcount < $need ? 'text-error' : 'text-success'; ?>"><?= $qcount; ?> / <?= $need; ?></span>
<?php if($qcount < $need){ ?><small class="muted"><?= sprintf(bo_t('pt_missing'), $need-$qcount); ?></small><?php } elseif($qcount > $need){ ?><small class="muted"><?= sprintf(bo_t('pt_shows_of'), $need, $qcount, $val["random_questions"] ? bo_t('pt_random') : bo_t('pt_in_order')); ?></small><?php } ?>
<?php } else { ?><?= $qcount; ?> <small class="muted"><?= sprintf(bo_t('pt_shows_all'), $val["random_questions"] ? bo_t('pt_random_order') : ""); ?></small><?php } ?>
</td>
<td><small><?= $val["label_first"]; ?> = <?= (float)$val["points_first"]; ?> &nbsp;/&nbsp; <?= $val["label_second"]; ?> = <?= (float)$val["points_second"]; ?> &nbsp;/&nbsp; <?= $val["label_fail"]; ?> = <?= (float)$val["points_fail"]; ?><br>
<?= sprintf(bo_t('pt_attempts_per_q'), (int)$val["max_attempts"], $val["reveal_mode"]=="auto" ? bo_t('pt_reveal_auto') : bo_t('pt_reveal_button')); ?><br>
<?= sprintf(bo_t('pt_pass_time_tries'),
(float)$val["pass_percent"],
(int)$val["time_limit"] ? sprintf(bo_t('pt_minutes'), (int)$val["time_limit"]) : bo_t('pt_no_limit'),
(int)$val["max_tries"] ? sprintf(bo_t('pt_per_student'), (int)$val["max_tries"]) : bo_t('pt_no_limit'),
(isset($val["display_mode"]) && $val["display_mode"]=="one") ? bo_t('pt_one_at_a_time') : bo_t('pt_one_page')); ?></small></td>
<td><?php if($val["status"]==1){ ?><span class="label label-success"><?= bo_t('pt_published'); ?></span><?php } else { ?><span class="label"><?= bo_t('pt_draft'); ?></span><?php } ?></td>
<td class="pt-actions-cell"><?php if($write_priv == 1){ ?>
<a href="<?= base_url();?>back_office/practice_question/lists/<?= $val["id_practice_test"]; ?>" class="table-edit-link" title="<?= bo_t('pt_manage_questions'); ?>" >
<i class="icon-list" ></i> <?= bo_t('pt_questions'); ?></a> <span class="hidden"> | </span>
<a href="<?= base_url();?>back_office/practice_test/results/<?= $val["id_practice_test"]; ?>" class="table-edit-link" title="<?= bo_t('pt_student_results'); ?>" >
<i class="icon-signal" ></i> <?= bo_t('pt_results'); ?></a> <span class="hidden"> | </span>
<a href="<?= base_url();?>back_office/practice_test/edit/<?= $val["id_practice_test"]; ?>" class="table-edit-link" title="<?= bo_t('pt_edit'); ?>" >
<i class="icon-edit" ></i> <?= bo_t('pt_edit'); ?></a> <span class="hidden"> | </span>
<?php } ?>
<?php if($delete_priv == 1){ ?>
<a onclick="if(confirm('<?= addslashes(bo_t('pt_confirm_delete_test')); ?>')){ delete_row('<?=$val["id_practice_test"];?>'); }" class="table-delete-link cur" title="<?= bo_t('pt_delete'); ?>" >
<i class="icon-remove-sign" ></i> <?= bo_t('pt_delete'); ?></a>
<?php } ?>
</td>
</tr>
<?php }  } else { ?>
<tr class='odd'><td colspan="7" style='text-align:center;'><?= bo_t('pt_no_tests'); ?></td></tr>
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
