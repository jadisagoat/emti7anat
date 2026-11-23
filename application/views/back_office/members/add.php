
<?php
$content_name="members";
$id_content=$this->fct->getonecell("content_type","id_content",array("name"=>"members"));
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
<li><a href="<?=base_url();?>back_office/members"><?= bo_t('pt_students'); ?></a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul>
</div>
<div class="hundred pull-left">
<form action="<?= base_url(); ?>back_office/members/submit" method="post" class="middle-forms" enctype="multipart/form-data" autocomplete="off">
<p class="alert alert-info"><?= bo_t('pt_m_intro'); ?> <em>*</em>
<?php
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" />
<?php
} else {
$info["name"] = "";
$info["username"] = "";
$info["email"] = "";
$info["tel"] = "";
$info["code"] = "";
$info["id_gender"] = "";
$info["date_of_birth"] = "";
$info["image"] = "";
$info["address"] = "";
$info["notes"] = "";
$info["status"] = 1;
$info["access_start"] = date("Y-m-d");
$info["access_end"] = date("Y-m-d",strtotime("+".PT_ACCESS_YEARS." year"));
$info["last_login"] = "";
$info["id_members"] = 0;
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
<legend><?= bo_t('pt_legend_login'); ?></legend>

<label class="field-title"><?= bo_t('pt_student_name'); ?>&nbsp;<em>*</em>:</label>
<label>
<input type="text" class="input-xxlarge" name="name" value="<?= set_value("name",$info["name"]); ?>" />
<?= form_error("name","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title"><?= bo_t('pt_username'); ?>&nbsp;<em>*</em>:</label>
<label>
<input type="text" class="input-xxlarge" name="username" value="<?= set_value("username",$info["username"]); ?>" autocomplete="off" />
<?= form_error("username","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title"><?= bo_t('pt_password'); ?><?php if(!isset($id)){ ?>&nbsp;<em>*</em><?php } ?>:</label>
<label>
<input type="text" class="input-xxlarge" name="password" value="" autocomplete="new-password" placeholder="<?= isset($id) ? bo_t('pt_password_keep_ph') : bo_t('pt_password_min_ph'); ?>" />
<span class="help-block"><?= bo_t('pt_password_help'); ?></span>
<?= form_error("password","<span class='text-error'>","</span>"); ?>
</label>
</fieldset>

<fieldset>
<legend><?= bo_t('pt_access'); ?></legend>

<label class="field-title"><?= bo_t('pt_status'); ?>:</label>
<label>
<?php $status=set_value("status",$info["status"]); ?>
<select name="status" class="input-xlarge">
<option value="1" <?php if($status=="1") echo 'selected="selected"'; ?>><?= bo_t('pt_active_can_login'); ?></option>
<option value="0" <?php if($status=="0") echo 'selected="selected"'; ?>><?= bo_t('pt_inactive'); ?></option>
</select>
</label>

<label class="field-title"><?= bo_t('pt_access_start'); ?></label>
<label>
    <div class="input-append date" data-date-format="mm/dd/yyyy">
    <input class="span2" size="16" name="access_start" type="text" value="<?= set_value("access_start",$this->fct->date_out_formate($info["access_start"])); ?>">
    <span class="add-on"><i class="icon-th"></i></span>
    </div>
<?= form_error("access_start","<span class='text-error'>","</span>"); ?>
</label>

<label class="field-title"><?= bo_t('pt_access_end'); ?></label>
<label>
    <div class="input-append date" data-date-format="mm/dd/yyyy">
    <input class="span2" size="16" name="access_end" type="text" value="<?= set_value("access_end",$this->fct->date_out_formate($info["access_end"])); ?>">
    <span class="add-on"><i class="icon-th"></i></span>
    </div>
<span class="help-block"><?= sprintf(bo_t('pt_access_help'), PT_ACCESS_YEARS); ?></span>
<?= form_error("access_end","<span class='text-error'>","</span>"); ?>
</label>
<?php if(isset($id) && !empty($info["last_login"])){ ?>
<label class="field-title"><?= bo_t('pt_last_login'); ?>:</label>
<label><?= $info["last_login"]; ?></label>
<?php } ?>

<label class="field-title"><?= bo_t('pt_notes_label'); ?></label>
<label>
<input type="text" class="input-xxlarge" name="notes" value="<?= set_value("notes",$info["notes"]); ?>" placeholder="<?= bo_t('pt_notes_ph'); ?>" />
<?= form_error("notes","<span class='text-error'>","</span>"); ?>
</label>
</fieldset>

<fieldset>
<legend><?= bo_t('pt_legend_extra'); ?></legend>

<label class="field-title"><?= bo_t('pt_email'); ?></label>
<label>
<input type="text" class="input-xxlarge" name="email" value="<?= set_value("email",$info["email"]); ?>" />
<?= form_error("email","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title"><?= bo_t('pt_phone'); ?></label>
<label>
<input type="text" class="input-xxlarge" name="tel" value="<?= set_value("tel",$info["tel"]); ?>" />
<?= form_error("tel","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title"><?= bo_t('pt_code'); ?></label>
<label>
<input type="text" class="input-xxlarge" name="code" value="<?= set_value("code",$info["code"]); ?>" />
<?= form_error("code","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title"><?= bo_t('pt_gender'); ?></label>
<label>
<?php $items = $this->fct->getAll("gender","sort_order"); $id_gender=set_value("gender",$info["id_gender"]); ?>
<select name="gender"  class="input-xxlarge">
<option value="" ><?= bo_t('pt_choose'); ?></option>
<?php foreach($items as $valll){ ?>
<option value="<?= $valll["id_gender"]; ?>" <?php if($id_gender == $valll["id_gender"]){ echo 'selected="selected"'; } ?> ><?= $valll["title"]; ?></option>
<?php } ?>
</select>
<?= form_error("gender","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title"><?= bo_t('pt_dob'); ?></label>
<label>
    <div class="input-append date" data-date-format="mm/dd/yyyy">
    <input class="span2" size="16" name="date_of_birth" type="text" value="<?= set_value("date_of_birth",$this->fct->date_out_formate($info["date_of_birth"])); ?>">
    <span class="add-on"><i class="icon-th"></i></span>
    </div>
<?= form_error("date_of_birth","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title"><?= bo_t('pt_image'); ?></label>
<label>
<input type="file"  class="input-xxlarge"  name="image" />
<span >
<?php
if($info["image"] != ""){ ?>
<a href="<?= base_url(); ?>uploads/members/<?= $info["image"]; ?>" class="blue gallery" title="<?= htmlspecialchars($info["name"]); ?>" >
<?= bo_t('pt_show_image'); ?>
</a>
&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('<?= addslashes(bo_t('pt_delete_image_confirm')); ?>')){ window.location=' <?= base_url(); ?>back_office/members/delete_image/image/<?= $info["image"]; ?>/<?= $info["id_members"] ?> ' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<?php } else { echo "<span class='blue'>".bo_t('pt_no_image')."</span>"; } ?>
</span>
<?= form_error("image","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title"><?= bo_t('pt_address'); ?></label>
<label>
<input type="text" class="input-xxlarge" name="address" value="<?= set_value("address",$info["address"]); ?>" />
<?= form_error("address","<span class='text-error'>","</span>"); ?>
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
