
<?php
$content_name="schedule";
$id_content="7";
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
<li><a href="<?=base_url();?>back_office/schedule">schedule</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/schedule/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["title"] = "";
$info["date"] = "";
$info["years_between"] = "";
$info["class"] = "";
$info["time"] = "";
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
<label class="field-title">Date
</label>
<label>

    <div class="input-append date" data-date="12-02-2012" data-date-format="mm/dd/yyyy">
    <input class="span2" size="16" name="date" type="text" value="<?= set_value("date",$this->fct->date_out_formate($info["date"])); ?>">
    <span class="add-on"><i class="icon-th"></i></span>
    </div>
<?= form_error("date","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Years between
</label>
<label>
<input type="text" class="input-xxlarge" name="years_between" value="<?= set_value("years_between",$info["years_between"]); ?>" />
<?= form_error("years_between","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Class
</label>
<label>
<input type="text" class="input-xxlarge" name="class" value="<?= set_value("class",$info["class"]); ?>" />
<?= form_error("class","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Time
</label>
<label>
<input type="text" class="input-xxlarge" name="time" value="<?= set_value("time",$info["time"]); ?>" />
<?= form_error("time","<span class='text-error'>","</span>"); ?>
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