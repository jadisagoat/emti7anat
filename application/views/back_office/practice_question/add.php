
<?php
$content_name="practice question";
$id_content=$this->fct->getonecell("content_type","id_content",array("name"=>"practice question"));
if($this->session->userdata("level") == 0){
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
$write_priv=$this->fct->getonecell("priviledge","write_p",$cond);
} else {
$write_priv=1;
}
if($write_priv != 1){ redirect(base_url()."back_office/home/dashboard"); }
$id_practice_test=isset($id) ? (int)$info["id_practice_test"] : (int)$this->session->userdata("id_practice_test");
$test=$this->fct->getonerecord("practice_test",array("id_practice_test"=>$id_practice_test));
$letters=array(1=>"A",2=>"B",3=>"C",4=>"D");
?>
<link rel="stylesheet" href="<?= base_url(); ?>css/practice_admin.css" />
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10 bo-practice <?= bo_dir_class(); ?>" >
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?=base_url();?>back_office/practice_test"><?= bo_t('pt_tests'); ?></a> <span class="divider">/</span></li>
<li><a href="<?=base_url();?>back_office/practice_question"><?= bo_t('pt_questions'); ?></a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?> <?php if($test){ ?><span style="color:#F00">(<?= $test["title"]; ?>)</span><?php } ?></li>
</ul>
</div>
<div class="hundred pull-left">
<?php if(!$test){ ?>
<div class="alert alert-error"><?= bo_t('pt_open_test_first'); ?> <a href="<?=base_url();?>back_office/practice_test"><?= bo_t('pt_tests'); ?></a> <?= bo_t('pt_arrow'); ?> <?= bo_t('pt_questions'); ?> <?= bo_t('pt_arrow'); ?> <?= bo_t('pt_add_question'); ?>.</div>
<?php } else { ?>
<form action="<?= base_url(); ?>back_office/practice_question/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<input type="hidden" name="id_practice_test" value="<?= $id_practice_test; ?>" />
<p class="alert alert-info"><?= bo_t('pt_q_intro'); ?> <em>*</em>
<?php
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" />
<?php
} else {
$info=array("title"=>"","question_text"=>"","image"=>"","option_1"=>"","option_2"=>"","option_3"=>"","option_4"=>"","correct_option"=>1,"hint"=>"","explanation"=>"","status"=>1);
}
?>
<?php if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?= $this->session->userdata("success_message"); ?>
</div>
<?php } ?>
<?php if($this->session->userdata("error_message")){ ?>
<div class="alert alert-error">
<?= $this->session->userdata("error_message"); ?>
</div>
<?php } ?>
</p>
<fieldset>
<legend><?= bo_t('pt_legend_question'); ?></legend>

<label class="field-title"><?= bo_t('pt_q_label'); ?></label>
<label>
<input type="text" class="input-xxlarge" name="title" value="<?= set_value("title",$info["title"]); ?>" placeholder="<?= bo_t('pt_q_label_ph'); ?>" />
<?= form_error("title","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title"><?= bo_t('pt_q_text'); ?></label>
<label>
<textarea class="ckeditor" id="question_text" rows="8" cols="100" name="question_text"><?= set_value("question_text",$info["question_text"]); ?></textarea>
<?= form_error("question_text","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title"><?= bo_t('pt_q_image'); ?></label>
<label>
<input type="file" class="input-xxlarge" name="image" accept="image/*" />
<span>
<?php if(!empty($info["image"]) && is_file("./uploads/practice_question/".$info["image"])){ ?>
<br><img src="<?= base_url(); ?>uploads/practice_question/<?= $info["image"]; ?>" style="max-height:160px;max-width:100%;border:1px solid #ddd;margin:8px 0" />
<br><a href="<?= base_url(); ?>uploads/practice_question/<?= $info["image"]; ?>" class="blue gallery" title="<?= bo_t('pt_q_image_title'); ?>"><?= bo_t('pt_show_image'); ?></a>
&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('<?= addslashes(bo_t('pt_remove_image_confirm')); ?>')){ window.location='<?= base_url(); ?>back_office/practice_question/delete_image/image/<?= $info["image"]; ?>/<?= $id; ?>' }"><img src="<?= base_url(); ?>images/delete.png" /> <?= bo_t('pt_remove_image'); ?></a>
<?php } else { echo "<span class='blue'>".bo_t('pt_no_image')."</span>"; } ?>
</span>
</label>
</fieldset>

<fieldset>
<legend><?= bo_t('pt_legend_options'); ?>&nbsp;<em>*</em></legend>
<?php $correct=(int)set_value("correct_option",$info["correct_option"]); ?>
<table class="table table-condensed" style="width:auto">
<tbody>
<?php for($k=1;$k<=4;$k++){ ?>
<tr>
<td style="vertical-align:middle"><label class="radio inline" style="margin:0"><input type="radio" name="correct_option" value="<?= $k; ?>" <?php if($correct==$k) echo 'checked="checked"'; ?> /> <b><?= $letters[$k]; ?></b></label></td>
<td><input type="text" class="input-xxlarge" name="option_<?= $k; ?>" value="<?= set_value("option_".$k,$info["option_".$k]); ?>" placeholder="<?= sprintf(bo_t('pt_option_ph'), $letters[$k]); ?>" />
<?= form_error("option_".$k,"<span class='text-error'>","</span>"); ?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?= form_error("correct_option","<span class='text-error'>","</span>"); ?>
</fieldset>

<fieldset>
<legend><?= bo_t('pt_legend_help'); ?></legend>

<label class="field-title"><?= bo_t('pt_hint_label'); ?></label>
<label>
<textarea class="ckeditor" id="hint" rows="5" cols="100" name="hint"><?= set_value("hint",$info["hint"]); ?></textarea>
<span class="help-block"><?= sprintf(bo_t('pt_hint_help'), PT_DEFAULT_HINT_TEXT); ?></span>
<?= form_error("hint","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title"><?= bo_t('pt_explanation_label'); ?></label>
<label>
<textarea class="ckeditor" id="explanation" rows="5" cols="100" name="explanation"><?= set_value("explanation",$info["explanation"]); ?></textarea>
<?= form_error("explanation","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title"><?= bo_t('pt_status'); ?>:</label>
<label>
<?php $status=set_value("status",$info["status"]); ?>
<select name="status" class="input-xlarge">
<option value="1" <?php if($status=="1") echo 'selected="selected"'; ?>><?= bo_t('pt_published'); ?></option>
<option value="0" <?php if($status=="0") echo 'selected="selected"'; ?>><?= bo_t('pt_hidden'); ?></option>
</select>
</label>
</fieldset>
<p class="align-right">
<button type="submit" class="btn btn-primary"><?= bo_t('pt_save'); ?></button>
</p>
<span class="clearFix">&nbsp;</span>
</form>
<?php } ?>
</div>
</div>
</div>
<?php $this->session->unset_userdata("success_message"); ?>
<?php $this->session->unset_userdata("error_message"); ?>
