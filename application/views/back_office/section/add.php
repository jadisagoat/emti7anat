
<?php
$content_name="section";
$id_content="5";
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
<li><a href="<?=base_url();?>back_office/section">Section</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/section/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["title"] = "";
$info["class"] = "";
$info["material"] = "";
$info["unit"] = "";
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

<label  style="display:none" class="field-title">Class
</label>
<label  style="display:none">

<? $items = $this->fct->getAll("classes","sort_order");
if(isset($id)){$id_classes=$info["id_classes"];}
 else $id_classes=$this->session->userdata("id_classes");
 ?><select name="class"  class="input-xxlarge">
<option value="" > - select - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_classes"]; ?>" <?   if($id_classes == $valll["id_classes"]){ echo 'selected="selected"'; }   ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("class","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Subject
</label>
<label>

<? //$items = $this->fct->getAll("material","sort_order");?>

<? $items = $this->fct->getAll("material","sort_order");
if(isset($id)){$id_material=$info["id_material"];}
 else $id_material=$this->session->userdata("id_material");
 ?>
 
<select name="material"  class="input-xxlarge">
<option value="" > - select - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_material"]; ?>" <?    if($id_material == $valll["id_material"]){ echo 'selected="selected"'; }  ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("material","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Unit
</label>
<label>

<? //$items = $this->fct->getAll("unit","sort_order");?>
<? $items = $this->fct->getAll("unit","sort_order");
if(isset($id)){$id_unit=$info["id_unit"];}
 else $id_unit=$this->session->userdata("id_unit");
 ?>
<select name="unit"  class="input-xxlarge">
<option value="" > - select - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_unit"]; ?>" <?  if($id_unit  == $valll["id_unit"]){ echo 'selected="selected"'; }   ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("unit","<span class='text-error'>","</span>"); ?>
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