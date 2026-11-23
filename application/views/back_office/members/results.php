
<?php
$id_content=$this->fct->getonecell("content_type","id_content",array("name"=>"members"));
if($this->session->userdata("level") == 0){
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
$read_priv=$this->fct->getonecell("priviledge","read_p",$cond);
} else { $read_priv = 1; }
if($read_priv != 1){ redirect(base_url()."back_office/home/dashboard"); }
?>
<link rel="stylesheet" href="<?= base_url(); ?>css/practice_admin.css" />
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10 bo-practice <?= bo_dir_class(); ?>">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?=base_url();?>back_office/members"><?= bo_t('pt_students'); ?></a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?> <small class="muted"><?= $member["username"]; ?></small></li>
<li class="pull-right"><a href="<?= base_url(); ?>back_office/practice_result?member=<?= (int)$member["id_members"]; ?>"><?= bo_t('pt_full_report'); ?></a></li>
</ul>
</div>
<div class="hundred pull-left">
<table class="table table-striped">
<thead><tr><th><?= bo_t('pt_date'); ?></th><th><?= bo_t('pt_lesson'); ?></th><th><?= bo_t('pt_test'); ?></th><th><?= bo_t('pt_grade'); ?></th><th><?= bo_t('pt_percent'); ?></th><th><?= bo_t('pt_result'); ?></th><th><?= bo_t('pt_first_try'); ?></th><th><?= bo_t('pt_second_try'); ?></th><th><?= bo_t('pt_wrong'); ?></th></tr></thead>
<tbody>
<?php if(count($info) > 0){ foreach($info as $val){ ?>
<tr>
<td><?= $val["created_date"]; ?></td>
<td><?= $val["section_title"]; ?></td>
<td><?= $val["test_title"] ? $val["test_title"] : "<small>".bo_t('pt_deleted_test')."</small>"; ?></td>
<td><?= (float)$val["score"]; ?> / <?= (float)$val["max_score"]; ?></td>
<td><?= (float)$val["percent"]; ?>%</td>
<td><?php if((int)$val["status"]!==1){ ?><span class="label label-warning"><?= bo_t('pt_unfinished'); ?></span><?php } elseif($val["passed"]){ ?><span class="label label-success"><?= bo_t('pt_passed'); ?></span><?php } else { ?><span class="label label-important"><?= bo_t('pt_failed'); ?></span><?php } ?></td>
<td><?= (int)$val["first_try"]; ?></td>
<td><?= (int)$val["second_try"]; ?></td>
<td><?= (int)$val["failed"]; ?></td>
</tr>
<?php } } else { ?>
<tr class='odd'><td colspan="9" style='text-align:center;'><?= bo_t('pt_no_results'); ?></td></tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
</div>
