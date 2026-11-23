
<script type="text/javascript" src="<?php  echo  base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function delete_row(id){
window.location="<?php  echo  base_url(); ?>back_office/typesession/delete/"+id;
return false;
}


$(function(){
$("#table-1").tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$("#result").load("<?php  echo  base_url(); ?>back_office/typesession/sorted?"+ser);
}
});
});
</script>
<?php 
$content_name="typesession";
$id_content="33";
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
<?php  $this->load->view("back_office/includes/left_box"); ?>
</div>

<div class="span10">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li class="active"><?php  echo  $title; ?></li>
<?php  if($write_priv == 1){ ?>
<li class="pull-right">
<a href="<?php  echo  base_url(); ?>back_office/typesession/add" id="topLink" title="Add typesession">Add typesession</a>
</li><?php  } ?>
</ul> 
</div>
<div class="hundred pull-left">   
<div id="result"></div>  
<form action="<?php  echo  base_url(); ?>back_office/typesession/delete_all" method="post" name="list_form" >    		
<table class="table table-striped" id="table-1">
<thead>
<?php  if($this->session->userdata("success_message")){ ?>
<tr><td colspan="3" align="center" style="text-align:center">
<div class="alert alert-success">
<?php  echo  $this->session->userdata("success_message"); ?>
</div>
</td>
<tr>
<?php  } ?>
<tr>
<td>&nbsp;</td><th>
<a href="<?php  echo  base_url(); ?>back_office/typesession/index/title" class="<?php  if($sego == "title") echo 'order_active'; else echo 'order_link'; ?>" >Title</a></th>
<th style="text-align:right;padding-right:80px;">Action</th></tr></thead><tfoot><tr>
<td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
<td colspan="3"><div class="pull-right">
<?php  if($delete_priv == 1){ ?>
<select class="form-select" name="check_option">
<option value="option1">Bulk Options</option>
<option value="delete_all">Delete All</option></select>
<a class="btn btn-primary btn_mrg" onclick="document.forms['list_form'].submit();" style="cursor:pointer;"><span>perform action</span></a></div> <?php  } ?></td>
</tr>
</tfoot>
<tbody>
<?php  
if(count($info) > 0){
$i=0;
foreach($info as $val){
$i++; 
?>
<tr id="<?php  echo $val["id_typesession"]; ?>">
<td class="col-chk"><input type="checkbox" name="cehcklist[]" value="<?php  echo  $val["id_typesession"] ; ?>" /></td><td class="" width=""><?php  echo $val["title"]; ?>
</td><td style="text-align:right"><?php  if($write_priv == 1){ ?>
<a href="<?php  echo  base_url();?>back_office/typesession/edit/<?php  echo  $val["id_typesession"]; ?>" class="table-edit-link" title="Edite" >
<i class="icon-edit" ></i> Edit</a> <span class="hidden"> | </span>
<?php  } ?>
<?php  if($delete_priv == 1){ ?>
<a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?php  echo $val["id_typesession"];?>'); }" class="table-delete-link cur" title="Delete" >
<i class="icon-remove-sign" ></i> Delete</a>
<?php  } ?>
</td>
</tr>
<?php  }  } else { ?>
<tr class='odd'><td colspan="3" style='text-align:center;'>No records available . </td></tr>
<?php   } ?>
</tbody>
</table>  	
</form>
</div>
</div>
</div>   
</div>
<?php  $this->session->unset_userdata("success_message"); ?> 
<?php  $this->session->unset_userdata("error_message"); ?> 