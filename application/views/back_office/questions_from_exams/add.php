
<?php
$content_name="questions_from_exams";
$id_content="9";
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
<li><a href="<?=base_url();?>back_office/questions_from_exams">supplementary problems</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/questions_from_exams/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["title"] = "";
$info["language"] = "";
$info["class"] = "";
$info["material"] = "";
$info["unit"] = "";
$info["section"] = "";
$info["question"] = "";
$info["answer"] = "";
    $info["description"] = "";
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
<label  style="display: none" class="field-title">Class&nbsp;<em>*</em>:
</label>
<label  style="display: none">

<? $items = $this->fct->getAll("classes","sort_order");
if(isset($id)){$id_classes=$info["id_classes"];}
 else $id_classes=$this->session->userdata("id_classes");
  ?> 
<select name="class"  class="input-xxlarge">
<option value="" > - select classes - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_classes"]; ?>" <?   if($id_classes == $valll["id_classes"]){ echo 'selected="selected"'; }   ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("class","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Material&nbsp;<em>*</em>:
</label>
<label>

<? $items = $this->fct->getAll("material","sort_order");
if(isset($id)){$id_material=$info["id_material"];}
 else $id_material=$this->session->userdata("id_material");
 //echo $id_material; ?>
 
 <select name="material"  class="input-xxlarge">
<option value="" > - select - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_material"]; ?>" <?   if($id_material == $valll["id_material"]){ echo 'selected="selected"'; }  ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("material","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Unit&nbsp;<em>*</em>:
</label>
<label>

<? $items = $this->fct->getAll("unit","sort_order");
if(isset($id)){$id_unit=$info["id_unit"];}
 else $id_unit=$this->session->userdata("id_unit");
 ?> 
<select name="unit"  class="input-xxlarge">
<option value="" > - select unit - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_unit"]; ?>" <?   if($id_unit  == $valll["id_unit"]){ echo 'selected="selected"'; }  ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("unit","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Section&nbsp;<em>*</em>:
</label>
<label>

<? $items = $this->fct->getAll("section","sort_order");
if(isset($id)){$id_section=$info["id_section"];}
 else $id_section=$this->session->userdata("id_section");
 ?> 
 <select name="section"  class="input-xxlarge">
<option value="" > - select section - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_section"]; ?>" <?   if($id_section  == $valll["id_section"]){ echo 'selected="selected"'; }   ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("section","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Question
</label>
<label>
<input type="file"  class="input-xxlarge"  name="question" />
<?= form_error("question","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Answer
</label>
<label>
<input type="file"  class="input-xxlarge"  name="answer" />
<?= form_error("answer","<span class='text-error'>","</span>"); ?>
</label>

    <label class="field-title">Description
    </label>
    <label>

<textarea class="ckeditor" id="description" rows="15" cols="100" name="description" >
<?= set_value("description",$info["description"]); ?>
</textarea>

        <?= form_error("description","<span class='text-error'>","</span>"); ?>
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
