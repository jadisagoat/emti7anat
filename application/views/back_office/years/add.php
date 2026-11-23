
<?php 
$content_name="years";
$id_content="34";
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
<?php  $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10" >
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?php  echo base_url();?>back_office/years">years</a> <span class="divider">/</span></li>
<li class="active"><?php  echo  $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?php  echo  base_url(); ?>back_office/years/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?php  if(isset($id)){?>
<input type="hidden" name="id" value="<?php  echo  $id; ?>" /> 
<?php  } else {
$info["title"] = "";
}
?>
<?php  if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?php  echo  $this->session->userdata("success_message"); ?>
</div>
<?php  } ?>
<?php  if($this->session->userdata("error_message")){ ?>
<div class="alert alert-error">
<?php  echo  $this->session->userdata("error_message"); ?>
</div>
<?php  } ?>
</p>
<fieldset>
<legend></legend>

<label class="field-title">Title&nbsp;<em>*</em>:
</label>
<label>
<input type="text" class="input-xxlarge" name="title" value="<?php  echo  set_value("title",$info["title"]); ?>" />
<?php  echo  form_error("title","<span class='text-error'>","</span>"); ?>
</label>
</ol>
</fieldset>
<p class="align-right">
<input type="image" src="<?php  echo  base_url(); ?>images/bt-send-form.png" value="Save Changes" class="btn btn-primary" />
</p>
<span class="clearFix">&nbsp;</span>
</form>
</div>
</div>     
</div>
<?php  $this->session->unset_userdata("success_message"); ?> 
<?php  $this->session->unset_userdata("error_message"); ?> 