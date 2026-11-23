
<?php
$content_name="contributors";
$id_content="11";
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
<li><a href="<?=base_url();?>back_office/contributors">contributors</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/contributors/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["title"] = "";
$info["classes"] = "";
$info["material"] = "";
$info["resume"] = "";
$info["cv"] = "";
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
<label class="field-title">Classes&nbsp;<em>*</em>:
</label>
<label>

<? $items = $this->fct->getAll("classes","sort_order");
if(isset($id)){$id_classes=$info["id_classes"];}
 else $id_classes=$this->session->userdata("id_classes");
 ?>

<select name="classes"  class="input-xxlarge">
<option value="" > - select - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_classes"]; ?>" <?  if($id_classes == $valll["id_classes"]){ echo 'selected="selected"'; }  ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("classes","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Subject&nbsp;<em>*</em>:
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
<option value="<?= $valll["id_material"]; ?>" <?   if($id_material == $valll["id_material"]){ echo 'selected="selected"'; }  ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("material","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Image
</label>
<label>
<input type="file"  class="input-xxlarge"  name="image" />
<span >
<?
if($info["image"] != ""){ ?>	
<a href="<?= base_url(); ?>uploads/contributors/<?= $info["image"]; ?>" class="blue gallery" title="<?= $info["title"]; ?>" >
show image
</a>
&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/contributors/delete_image/image/<?= $info["image"]; ?>/<?= $info["id_contributors"] ?> ' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<span class='blue'>No Image Available</span>"; } ?>
</span>
<?= form_error("image","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Resume
</label>
<label>
<input type="file"  class="input-xxlarge"  name="resume" />
<?= form_error("resume","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Cv
</label>
<label>

<textarea class="ckeditor" id="cv" rows="15" cols="100" name="cv" >
<?= set_value("cv",$info["cv"]); ?>
</textarea>

<?= form_error("cv","<span class='text-error'>","</span>"); ?>
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