
<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function delete_row(id){
window.location="<?= base_url(); ?>back_office/test_simple/delete/"+id;
return false;
}


$(function(){
$("#table-1").tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$("#result").load("<?= base_url(); ?>back_office/test_simple/sorted?"+ser);
}
});
});
</script>
<?php
$content_name="test_simple";
$id_content="23";
$sego =$this->uri->segment(4);
$gallery_status="0";
if($this->session->userdata("level") == 0){
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
$read_priv=$this->fct->getonecell("priviledge","read_p",$cond);
$write_priv=$this->fct->getonecell("priviledge","write_p",$cond);
$delete_priv=$this->fct->getonecell("priviledge","delete_p",$cond);
} else { 
$read_priv = 1; 
$write_priv=1;
$delete_priv = 1;
}
if($read_priv != 1){ redirect(base_url()."back_office/home/dashboard"); }	
?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view("back_office/includes/left_box"); ?>
</div>

<div class="span10">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li class="active">
<a href="<?=base_url();?>back_office/classes">Class </a> / <a href="<?=base_url();?>back_office/material">Subject</a> / <?= $title; ?>&nbsp;&nbsp;&nbsp;<span style="color:#F00">(<? 
$cond=array("id_classes" => $this->session->userdata("id_classes"));
echo $this->fct->getonecell("classes","title",$cond); echo " / ";
$cond=array("id_material" => $this->session->userdata("id_material"));
echo $this->fct->getonecell("material","title",$cond);      
?>
)</span></li>

<? if($write_priv == 1){ ?>
<li class="pull-right">
<a href="<?= base_url(); ?>back_office/test_simple/add" id="topLink" title="Add test simple">Add test simple</a>
</li><? } ?>
</ul> 
</div>
<div class="hundred pull-left">   
<div id="result"></div>  
<form action="<?= base_url(); ?>back_office/test_simple/delete_all" method="post" name="list_form" >    		
<table class="table table-striped" id="table-1">
<thead>
<? if($this->session->userdata("success_message")){ ?>
<tr><td colspan="7" align="center" style="text-align:center">
<div class="alert alert-success">
<?= $this->session->userdata("success_message"); ?>
</div>
</td>
<tr>
<? } ?>
<tr>
<td>&nbsp;</td><th>
<a href="<?= base_url(); ?>back_office/test_simple/index/title" class="<? if($sego == "title") echo 'order_active'; else echo 'order_link'; ?>" >Title</a></th>
<th>Language</th><th>Class</th>
<th>Subject</th>
<th>Simple exam</th>
<th>Solution</th>
<th style="text-align:right;padding-right:80px;">Action</th></tr></thead><tfoot><tr>
<td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
<td colspan="8"><div class="pull-right">
<? if($delete_priv == 1){ ?>
<select class="form-select" name="check_option">
<option value="option1">Bulk Options</option>
<option value="delete_all">Delete All</option></select>
<a class="btn btn-primary btn_mrg" onclick="document.forms['list_form'].submit();" style="cursor:pointer;"><span>perform action</span></a></div> <? } ?></td>
</tr>
</tfoot>
<tbody>
<? 
if(count($info) > 0){
$i=0;
foreach($info as $val){
$i++; 
?>
<tr id="<?=$val["id_test_simple"]; ?>">
<td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?= $val["id_test_simple"] ; ?>" /></td><td class="" width=""><? echo $val["title"]; ?>
</td><td class="" width=""><? 
if($val["id_language"] != 0){
$cond=array("id_language" => $val["id_language"]);
echo $this->fct->getonecell("language","title",$cond);
 } 
else { echo "<small>Not available</small>"; }  
 ?>
</td><td class="" width=""><? if($val["id_class"] != 0){
$cond=array("id_classes" => $val["id_class"]);
echo $this->fct->getonecell("classes","title",$cond); } else { echo "<small>Not available</small>"; }   ?>
</td><td class="" width=""><? if($val["id_material"] != 0){
$cond=array("id_material" => $val["id_material"]);
echo $this->fct->getonecell("material","title",$cond); } else { echo "<small>Not available</small>"; }   ?>
</td><td class="" width=""> <? if( $val["simple_exam"] != ""){ ?>	
<a href="<?= base_url(); ?>uploads/test_simple/<?=$val["simple_exam"]; ?>" class="blue" title="<?=$val["title"];?>" >
show file</a>&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('Are you sure you want to delete this file ?')){ window.location=' <?= base_url(); ?>back_office/test_simple/delete_image/simple_exam/<?=$val["simple_exam"];?>/<?=$val["id_test_simple"];?>' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<small class='blue'>No File Available</small>"; } ?>
</td><td class="" width=""> <? if( $val["solution"] != ""){ ?>	
<a href="<?= base_url(); ?>uploads/test_simple/<?=$val["solution"]; ?>" class="blue" title="<?=$val["title"];?>" >
show file</a>&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('Are you sure you want to delete this file ?')){ window.location=' <?= base_url(); ?>back_office/test_simple/delete_image/solution/<?=$val["solution"];?>/<?=$val["id_test_simple"];?>' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<small class='blue'>No File Available</small>"; } ?>
</td><td style="text-align:right"><? if($write_priv == 1){ ?>
<a href="<?= base_url();?>back_office/test_simple/edit/<?= $val["id_test_simple"]; ?>" class="table-edit-link" title="Edite" >
<i class="icon-edit" ></i> Edit</a> <span class="hidden"> | </span>
<? } ?>
<? if($delete_priv == 1){ ?>
<a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?=$val["id_test_simple"];?>'); }" class="table-delete-link cur" title="Delete" >
<i class="icon-remove-sign" ></i> Delete</a>
<? } ?>
</td>
</tr>
<? }  } else { ?>
<tr class='odd'><td colspan="8" style='text-align:center;'>No records available . </td></tr>
<?  } ?>
</tbody>
</table>  	
</form>
</div>
</div>
</div>   
</div>
<? $this->session->unset_userdata("success_message"); ?> 
<? $this->session->unset_userdata("error_message"); ?> 