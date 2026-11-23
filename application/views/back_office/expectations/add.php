
<?php
$content_name="expectations";
$id_content="26";
if($this->session->userdata("level") == 0){
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
$write_priv=$this->fct->getonecell("priviledge","write_p",$cond);
} else { 
$write_priv=1;
}
if($write_priv != 1){ redirect(base_url()."back_office/home/dashboard"); }	
?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10" >
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?=base_url();?>back_office/expectations">expectations</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/expectations/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["expectations_class"] = "";
$info["subject"] = "";
$info["subject_ar"] = "";
$info["maximum_grade"] = "";
}
?>
<? if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?= $this->session->userdata("success_message"); ?>
</div>
<? } ?>
<? if($this->session->userdata("error_message")){ ?>
<div class="alert alert-error">
<?= $this->session->userdata("error_message"); ?>
</div>
<? } ?>
</p>
<fieldset>
<legend></legend>

<label class="field-title">Expectations class&nbsp;<em>*</em>:
</label>
<label>

<? $items = $this->fct->getAll("expectations_class","sort_order");?>
<select name="expectations_class"  class="input-xxlarge">
<option value="" > - select expectations class - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_expectations_class"]; ?>" <? if(isset($id)){  if($info["id_expectations_class"] == $valll["id_expectations_class"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("expectations_class","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Subject
</label>
<label>
<input type="text" class="input-xxlarge" name="subject" value="<?= set_value("subject",$info["subject"]); ?>" />
<?= form_error("subject","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Subject ar
</label>
<label>
<input type="text" class="input-xxlarge" name="subject_ar" value="<?= set_value("subject_ar",$info["subject_ar"]); ?>" />
<?= form_error("subject_ar","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Maximum grade
</label>
<label>
<input type="text" class="input-xxlarge" name="maximum_grade" value="<?= set_value("maximum_grade",$info["maximum_grade"]); ?>" />
<?= form_error("maximum_grade","<span class='text-error'>","</span>"); ?>
</label>
</ol>
</fieldset>
<p class="align-right">
<input type="image" src="<?= base_url(); ?>images/bt-send-form.png" value="Save Changes" class="btn btn-primary" />
</p>
<span class="clearFix">&nbsp;</span>
</form>
</div>
</div>     
</div>
<? $this->session->unset_userdata("success_message"); ?> 
<? $this->session->unset_userdata("error_message"); ?> 