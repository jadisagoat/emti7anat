
<?php
$content_name="test_simple";
$id_content="23";
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
<li><a href="<?=base_url();?>back_office/test_simple">test simple</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/test_simple/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["title"] = "";
$info["class"] = "";
$info["subject"] = "";
$info["simple_exam"] = "";
$info["solution"] = "";
$info["id_language"] = "";

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

<label class="field-title">Title&nbsp;<em>*</em>:
</label>
<label>
<input type="text" class="input-xxlarge" name="title" value="<?= set_value("title",$info["title"]); ?>" />
<?= form_error("title","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Class&nbsp;<em>*</em>:
</label>
<label>

<? $items = $this->fct->getAll("classes","sort_order");
if(isset($id)){$id_classes=$info["id_class"];}
 else $id_classes=$this->session->userdata("id_classes");
  ?> 
 
<select name="class"  class="input-xxlarge">
<option value="" > - select - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_classes"]; ?>" <?   if($id_classes == $valll["id_classes"]){ echo 'selected="selected"'; }   ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("class","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title">Subject&nbsp;<em>*</em>:
</label>
<label>

<? $items = $this->fct->getAll("material","sort_order");
if(isset($id)){$id_material=$info["id_material"];}
 else $id_material=$this->session->userdata("id_material");
 ?>
<select name="subject"  class="input-xxlarge">
<option value="" > - select - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_material"]; ?>" <? if($id_material == $valll["id_material"]){ echo 'selected="selected"';   } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("subject","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title">Language&nbsp;<em>*</em>:
</label>
<label>

<? $items = $this->fct->getAll("language","sort_order");?>
<select name="language"  class="input-xxlarge">
<option value="" > - select language - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_language"]; ?>" <? if(isset($id)){  if($info["id_language"] == $valll["id_language"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("language","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title">Simple exam
</label>
<label>
<input type="file"  class="input-xxlarge"  name="simple_exam" />
<?= form_error("simple_exam","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Solution
</label>
<label>
<input type="file"  class="input-xxlarge"  name="solution" />
<?= form_error("solution","<span class='text-error'>","</span>"); ?>
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