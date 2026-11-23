
<?php
$content_name="practice test";
$id_content=$this->fct->getonecell("content_type","id_content",array("name"=>"practice test"));
if($this->session->userdata("level") == 0){
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
$write_priv=$this->fct->getonecell("priviledge","write_p",$cond);
} else {
$write_priv=1;
}
if($write_priv != 1){ redirect(base_url()."back_office/home/dashboard"); }
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
<li class="active"><?= $title; ?></li>
</ul>
</div>
<div class="hundred pull-left">
<form action="<?= base_url(); ?>back_office/practice_test/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info"><?= bo_t('pt_form_intro'); ?> <em>*</em>
<?php
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" />
<?php
} else {
// defaults for a new test (also the column defaults in the database)
$info=array("title"=>"","description"=>"","id_section"=>0,"status"=>1,
"questions_per_test"=>PT_DEFAULT_QUESTIONS,"max_attempts"=>PT_DEFAULT_MAX_ATTEMPTS,"grade_style"=>PT_DEFAULT_GRADE_STYLE,
"label_first"=>PT_DEFAULT_LABEL_FIRST,"label_second"=>PT_DEFAULT_LABEL_SECOND,"label_fail"=>PT_DEFAULT_LABEL_FAIL,
"points_first"=>PT_DEFAULT_POINTS_FIRST,"points_second"=>PT_DEFAULT_POINTS_SECOND,"points_fail"=>PT_DEFAULT_POINTS_FAIL,
"reveal_mode"=>PT_DEFAULT_REVEAL,"show_total"=>1,"pass_percent"=>PT_DEFAULT_PASS_PERCENT,
"time_limit"=>PT_DEFAULT_TIME_LIMIT,"max_tries"=>PT_DEFAULT_MAX_TRIES,"random_questions"=>1,"display_mode"=>PT_DEFAULT_DISPLAY);
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
<legend><?= bo_t('pt_legend_test'); ?></legend>

<label class="field-title"><?= bo_t('pt_test_title'); ?>&nbsp;<em>*</em>:</label>
<label>
<input type="text" class="input-xxlarge" name="title" value="<?= set_value("title",$info["title"]); ?>" />
<?= form_error("title","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title"><?= bo_t('pt_lesson_optional'); ?></label>
<label>
<?php
// optional link to a lesson: "Unit / Lesson" labels; leave empty for a standalone test
$items=$this->fct->getAll("section","id_unit, sort_order");
$id_section=(int)set_value("section",$info["id_section"]);
?>
<select name="section" class="input-xxlarge">
<option value="0" ><?= bo_t('pt_no_lesson_opt'); ?></option>
<?php foreach($items as $valll){ $unit=$valll["id_unit"] ? $this->fct->getonecell("unit","title",array("id_unit"=>(int)$valll["id_unit"])) : ""; ?>
<option value="<?= $valll["id_section"]; ?>" <?php if($id_section == $valll["id_section"]){ echo 'selected="selected"'; } ?> ><?= $unit ? $unit." / " : ""; ?><?= $valll["title"]; ?></option>
<?php } ?>
</select>
<span class="help-block"><?= bo_t('pt_lesson_help'); ?></span>
<?= form_error("section","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title"><?= bo_t('pt_status'); ?>:</label>
<label>
<?php $status=set_value("status",$info["status"]); ?>
<select name="status" class="input-xxlarge">
<option value="1" <?php if($status=="1") echo 'selected="selected"'; ?>><?= bo_t('pt_published_visible'); ?></option>
<option value="0" <?php if($status=="0") echo 'selected="selected"'; ?>><?= bo_t('pt_draft_hidden'); ?></option>
</select>
</label>

<label class="field-title"><?= bo_t('pt_intro_optional'); ?></label>
<label>
<textarea class="ckeditor" id="description" rows="6" cols="100" name="description"><?= set_value("description",$info["description"]); ?></textarea>
<?= form_error("description","<span class='text-error'>","</span>"); ?>
</label>
</fieldset>

<fieldset>
<legend><?= bo_t('pt_legend_questions_time'); ?></legend>

<label class="field-title"><?= bo_t('pt_questions_per_test'); ?>&nbsp;<em>*</em>:</label>
<label>
<input type="text" class="input-small" name="questions_per_test" value="<?= set_value("questions_per_test",$info["questions_per_test"]); ?>" />
<span class="help-inline"><?= bo_t('pt_questions_per_test_help'); ?></span>
<?= form_error("questions_per_test","<span class='text-error'>","</span>"); ?>
</label>

<label class="checkbox">
<input type="checkbox" name="random_questions" value="1" <?php if(set_value("random_questions",$info["random_questions"])) echo 'checked="checked"'; ?> /> <?= bo_t('pt_random_questions_label'); ?>
</label>

<label class="field-title"><?= bo_t('pt_display_mode'); ?></label>
<label>
<?php $dm=set_value("display_mode",$info["display_mode"]); ?>
<select name="display_mode" class="input-xxlarge">
<option value="all" <?php if($dm!="one") echo 'selected="selected"'; ?>><?= bo_t('pt_display_all'); ?></option>
<option value="one" <?php if($dm=="one") echo 'selected="selected"'; ?>><?= bo_t('pt_display_one'); ?></option>
</select>
</label>

<label class="field-title"><?= bo_t('pt_time_limit'); ?>&nbsp;<em>*</em>:</label>
<label>
<input type="text" class="input-small" name="time_limit" value="<?= set_value("time_limit",(int)$info["time_limit"]); ?>" />
<span class="help-inline"><?= bo_t('pt_time_limit_help'); ?></span>
<?= form_error("time_limit","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title"><?= bo_t('pt_max_tries'); ?>&nbsp;<em>*</em>:</label>
<label>
<input type="text" class="input-small" name="max_tries" value="<?= set_value("max_tries",(int)$info["max_tries"]); ?>" />
<span class="help-inline"><?= bo_t('pt_max_tries_help'); ?></span>
<?= form_error("max_tries","<span class='text-error'>","</span>"); ?>
</label>
</fieldset>

<fieldset>
<legend><?= bo_t('pt_legend_grading'); ?></legend>

<label class="field-title"><?= bo_t('pt_max_attempts'); ?>&nbsp;<em>*</em>:</label>
<label>
<input type="text" class="input-small" name="max_attempts" value="<?= set_value("max_attempts",$info["max_attempts"]); ?>" />
<span class="help-inline"><?= bo_t('pt_max_attempts_help'); ?></span>
<?= form_error("max_attempts","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title"><?= bo_t('pt_grade_style'); ?></label>
<label>
<?php $gs=set_value("grade_style",$info["grade_style"]); ?>
<select name="grade_style" class="input-xlarge">
<option value="letter" <?php if($gs=="letter") echo 'selected="selected"'; ?>><?= bo_t('pt_grade_letters'); ?></option>
<option value="number" <?php if($gs=="number") echo 'selected="selected"'; ?>><?= bo_t('pt_grade_points'); ?></option>
</select>
</label>

<table class="table table-condensed" style="width:auto">
<thead><tr><th></th><th><?= bo_t('pt_grade'); ?></th><th><?= bo_t('pt_points'); ?></th></tr></thead>
<tbody>
<tr><td><?= bo_t('pt_correct_first'); ?></td>
<td><input type="text" class="input-mini" name="label_first" value="<?= set_value("label_first",$info["label_first"]); ?>" /> <?= form_error("label_first","<span class='text-error'>","</span>"); ?></td>
<td><input type="text" class="input-mini" name="points_first" value="<?= set_value("points_first",(float)$info["points_first"]); ?>" /> <?= form_error("points_first","<span class='text-error'>","</span>"); ?></td></tr>
<tr><td><?= bo_t('pt_correct_second'); ?></td>
<td><input type="text" class="input-mini" name="label_second" value="<?= set_value("label_second",$info["label_second"]); ?>" /> <?= form_error("label_second","<span class='text-error'>","</span>"); ?></td>
<td><input type="text" class="input-mini" name="points_second" value="<?= set_value("points_second",(float)$info["points_second"]); ?>" /> <?= form_error("points_second","<span class='text-error'>","</span>"); ?></td></tr>
<tr><td><?= bo_t('pt_wrong_all'); ?></td>
<td><input type="text" class="input-mini" name="label_fail" value="<?= set_value("label_fail",$info["label_fail"]); ?>" /> <?= form_error("label_fail","<span class='text-error'>","</span>"); ?></td>
<td><input type="text" class="input-mini" name="points_fail" value="<?= set_value("points_fail",(float)$info["points_fail"]); ?>" /> <?= form_error("points_fail","<span class='text-error'>","</span>"); ?></td></tr>
</tbody>
</table>

<label class="field-title"><?= bo_t('pt_reveal_label'); ?></label>
<label>
<?php $rm=set_value("reveal_mode",$info["reveal_mode"]); ?>
<select name="reveal_mode" class="input-xxlarge">
<option value="button" <?php if($rm=="button") echo 'selected="selected"'; ?>><?= bo_t('pt_reveal_button_opt'); ?></option>
<option value="auto" <?php if($rm=="auto") echo 'selected="selected"'; ?>><?= bo_t('pt_reveal_auto'); ?></option>
</select>
</label>

<label class="checkbox">
<input type="checkbox" name="show_total" value="1" <?php if(set_value("show_total",$info["show_total"])) echo 'checked="checked"'; ?> /> <?= bo_t('pt_show_total_label'); ?>
</label>

<label class="field-title"><?= bo_t('pt_pass_percent'); ?>&nbsp;<em>*</em>:</label>
<label>
<input type="text" class="input-small" name="pass_percent" value="<?= set_value("pass_percent",(float)$info["pass_percent"]); ?>" />
<span class="help-inline"><?= sprintf(bo_t('pt_pass_percent_help'), PT_DEFAULT_PASS_PERCENT); ?></span>
<?= form_error("pass_percent","<span class='text-error'>","</span>"); ?>
</label>
</fieldset>
<p class="align-right">
<button type="submit" class="btn btn-primary"><?= bo_t('pt_save'); ?></button>
</p>
<span class="clearFix">&nbsp;</span>
</form>
</div>
</div>
</div>
<?php $this->session->unset_userdata("success_message"); ?>
<?php $this->session->unset_userdata("error_message"); ?>
