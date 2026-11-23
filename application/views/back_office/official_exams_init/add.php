
<?php   
$content_name="official_exams_init";
$id_content="36";
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
<?php    $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10" >
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?php    echo base_url();?>back_office/official_exams_init">official exams</a> <span class="divider">/</span></li>
<li class="active"><?php    echo  $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?php    echo  base_url(); ?>back_office/official_exams_init/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?php    if(isset($id)){?>
<input type="hidden" name="id" value="<?php    echo  $id; ?>" /> 
<?php    } else {
$info["title"] = "";
$info["class"] = "";
$info["years"] = "";
$info["pdf_1st"] = "";
$info["pdf_2st"] = "";
}
?>
<?php    if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?php    echo  $this->session->userdata("success_message"); ?>
</div>
<?php    } ?>
<?php    if($this->session->userdata("error_message")){ ?>
<div class="alert alert-error">
<?php    echo  $this->session->userdata("error_message"); ?>
</div>
<?php    } ?>
</p>
<fieldset>
<legend></legend>

<label class="field-title">Title&nbsp;<em>*</em>:
</label>
<label>
<input type="text" class="input-xxlarge" name="title" value="<?php    echo  set_value("title",$info["title"]); ?>" />
<?php    echo  form_error("title","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Class
</label>
<label>

<?php    $items = $this->fct->getAll("classes","sort_order");?>
<select name="class"  class="input-xxlarge">
<option value="" > - select classes - </option>
<?php    foreach($items as $valll){ ?>
<option value="<?php    echo  $valll["id_classes"]; ?>" <?php    if(isset($id)){  if($info["id_classes"] == $valll["id_classes"]){ echo 'selected="selected"'; } } ?> ><?php    echo  $valll["title"]; ?></option>
<?php    } ?>
</select>
<?php    echo  form_error("class","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Years&nbsp;<em>*</em>:
</label>
<label>

<?php    $items = $this->fct->getAll("years","sort_order");?>
<select name="years"  class="input-xxlarge">
<option value="" > - select years - </option>
<?php    foreach($items as $valll){ ?>
<option value="<?php    echo  $valll["id_years"]; ?>" <?php    if(isset($id)){  if($info["id_years"] == $valll["id_years"]){ echo 'selected="selected"'; } } ?> ><?php    echo  $valll["title"]; ?></option>
<?php    } ?>
</select>
<?php    echo  form_error("years","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Pdf 1st
</label>
<label>
<input type="file"  class="input-xxlarge"  name="pdf_1st" />
<?php    echo  form_error("pdf_1st","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">PDF - 2nd
</label>
<label>
<input type="file"  class="input-xxlarge"  name="pdf_2st" />
<?php    echo  form_error("pdf_2st","<span class='text-error'>","</span>"); ?>
</label>
</ol>
</fieldset>
<p class="align-right">
<input type="image" src="<?php    echo  base_url(); ?>images/bt-send-form.png" value="Save Changes" class="btn btn-primary" />
</p>
<span class="clearFix">&nbsp;</span>
</form>
</div>
</div>     
</div>
<?php    $this->session->unset_userdata("success_message"); ?> 
<?php    $this->session->unset_userdata("error_message"); ?> 
