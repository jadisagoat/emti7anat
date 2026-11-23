
<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function delete_row(id){
window.location="<?= base_url(); ?>back_office/practice_question/delete/"+id;
return false;
}
$(function(){
$("#table-1").tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$("#result").load("<?= base_url(); ?>back_office/practice_question/sorted?"+ser);
}
});
});
</script>
<?php
$content_name="practice question";
$id_content=$this->fct->getonecell("content_type","id_content",array("name"=>"practice question"));
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
$letters=array(1=>"A",2=>"B",3=>"C",4=>"D");
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
<li class="active">
<a href="<?=base_url();?>back_office/practice_test"><?= bo_t('pt_tests'); ?></a> / <?= $title; ?>&nbsp;&nbsp;&nbsp;<span style="color:#F00">(<?= $test ? $test["title"] : bo_t('pt_no_test_chosen'); ?>)</span>
<?php if($test){ ?>&nbsp;&nbsp;<small class="muted"><?= bo_t('pt_drag_questions'); ?></small><?php } ?>
</li>
<?php if($write_priv == 1 && $test){ ?>
<li class="pull-right">
<a href="<?= base_url(); ?>back_office/practice_question/add" id="topLink" title="<?= bo_t('pt_add_question'); ?>"><?= bo_t('pt_add_question'); ?></a>
</li><?php } ?>
</ul>
</div>
<div class="hundred pull-left">
<div id="result"></div>
<?php if(!$test){ ?>
<div class="alert alert-info"><?= bo_t('pt_open_test_first'); ?> <a href="<?=base_url();?>back_office/practice_test"><?= bo_t('pt_tests'); ?></a> <?= bo_t('pt_arrow'); ?> <b><?= bo_t('pt_questions'); ?></b>.</div>
<?php } elseif($test["questions_per_test"]>0 && count($info) < $test["questions_per_test"]){ ?>
<div class="alert"><?= sprintf(bo_t('pt_too_few'), (int)$test["questions_per_test"], count($info), (int)$test["questions_per_test"]-count($info)); ?></div>
<?php } ?>
<form action="<?= base_url(); ?>back_office/practice_question/delete_all" method="post" name="list_form" >
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
<td>&nbsp;</td><th>#</th><th><?= bo_t('pt_question'); ?></th><th><?= bo_t('pt_correct'); ?></th><th><?= bo_t('pt_hint'); ?></th><th><?= bo_t('pt_status'); ?></th>
<th class="pt-actions-cell"><?= bo_t('pt_actions'); ?></th></tr></thead><tfoot><tr>
<td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
<td colspan="6"><div class="pull-right">
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
$i=0;
foreach($info as $val){
$i++;
$plain=trim(preg_replace('/\s+/',' ',strip_tags($val["question_text"])));
if(function_exists('mb_strlen') && mb_strlen($plain,'UTF-8')>90) $plain=mb_substr($plain,0,90,'UTF-8')."…";
elseif(strlen($plain)>90) $plain=substr($plain,0,90)."…";
?>
<tr id="<?=$val["id_practice_question"]; ?>">
<td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["id_practice_question"] ; ?>" /></td>
<td><?= $i; ?></td>
<td>
<?php if(!empty($val["image"]) && is_file("./uploads/practice_question/".$val["image"])){ ?>
<a href="<?= base_url(); ?>uploads/practice_question/<?= $val["image"]; ?>" class="gallery" title="<?= htmlspecialchars($val["title"]); ?>"><img src="<?= base_url(); ?>uploads/practice_question/<?= $val["image"]; ?>" style="height:40px;margin:0 8px;vertical-align:middle;border:1px solid #ddd" /></a>
<?php } ?>
<?= $plain !== "" ? htmlspecialchars($plain) : "<small class='muted'>".bo_t('pt_image_question')."</small>"; ?>
</td>
<td><span class="label label-info"><?= $letters[(int)$val["correct_option"]]; ?></span></td>
<td><?= trim(strip_tags($val["hint"]))!=="" ? bo_t('pt_yes') : "<small class='muted'>".bo_t('pt_default')."</small>"; ?></td>
<td><?php if($val["status"]==1){ ?><span class="label label-success"><?= bo_t('pt_published'); ?></span><?php } else { ?><span class="label"><?= bo_t('pt_hidden'); ?></span><?php } ?></td>
<td class="pt-actions-cell"><?php if($write_priv == 1){ ?>
<a href="<?= base_url();?>back_office/practice_question/edit/<?= $val["id_practice_question"]; ?>" class="table-edit-link" title="<?= bo_t('pt_edit'); ?>" >
<i class="icon-edit" ></i> <?= bo_t('pt_edit'); ?></a> <span class="hidden"> | </span>
<?php } ?>
<?php if($delete_priv == 1){ ?>
<a onclick="if(confirm('<?= addslashes(bo_t('pt_confirm_delete_question')); ?>')){ delete_row('<?=$val["id_practice_question"];?>'); }" class="table-delete-link cur" title="<?= bo_t('pt_delete'); ?>" >
<i class="icon-remove-sign" ></i> <?= bo_t('pt_delete'); ?></a>
<?php } ?>
</td>
</tr>
<?php }  } else { ?>
<tr class='odd'><td colspan="7" style='text-align:center;'><?= bo_t('pt_no_questions'); ?></td></tr>
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
