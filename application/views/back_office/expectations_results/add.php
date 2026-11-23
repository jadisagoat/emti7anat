
<?php
$content_name="expectations_results";
$id_content="28";
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
<li><a href="<?=base_url();?>back_office/expectations_results">expectations results</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/expectations_results/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["expectations_class"] = "";
$info["results"] = "";
$info["rate"] = "";
$info["total"] = "";
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
<label class="field-title">Results
</label>
<label>
<input type="text" class="input-xxlarge" name="results" value="<?= set_value("results",$info["results"]); ?>" />
<?= form_error("results","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Rate
</label>
<label>
<input type="text" class="input-xxlarge" name="rate" value="<?= set_value("rate",$info["rate"]); ?>" />
<?= form_error("rate","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Total
</label>
<label>
<input type="text" class="input-xxlarge" name="total" value="<?= set_value("total",$info["total"]); ?>" />
<?= form_error("total","<span class='text-error'>","</span>"); ?>
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