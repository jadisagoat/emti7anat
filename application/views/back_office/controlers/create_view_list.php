<?php
/* Generator template: emits a module list view that uses includes/dxgrid.php (brief section 4.4). */
$content_name = $table_name;
$cond1["name"]=str_replace("_"," ",$content_name);
$id_content=$this->fct->getonecell("content_type","id_content",$cond1);
$gallery_status=$this->fct->getonecell("content_type","gallery",$cond1);

// column list + cell types from the attribute definitions (1 date, 4 image, 5 file, 6 yes/no, 7 foreign key)
$gen_columns = array();
$gen_types = array();
$gen_lookups = array();
$gen_labels = array();
foreach($attr as $v){
$fname = str_replace(" ","_",$v["name"]);
if($v["type"] == 7){
$cond001=array("id_content" => $v["foreign_key"]);
$content001 = $this->fct->getonecell("content_type","name",$cond001);
$ftable = str_replace(" ","_",$content001);
if($ftable == $table_name) $frn1 = "parent"; else $frn1 = $ftable;
$gen_columns[] = "id_".$frn1;
$gen_lookups["id_".$frn1] = array($ftable, "title", "id_".$ftable);
$gen_labels["id_".$frn1] = ucfirst($content001);
}
elseif($v["type"] == 6){ $gen_columns[] = $fname; $gen_types[$fname] = "status"; }
elseif($v["type"] == 4){ $gen_columns[] = $fname; $gen_types[$fname] = "image"; }
elseif($v["type"] == 5){ $gen_columns[] = $fname; $gen_types[$fname] = "file"; }
elseif($v["type"] == 1){ $gen_columns[] = $fname; $gen_types[$fname] = "date"; }
elseif($v["type"] == 3){ $gen_columns[] = $fname; $gen_types[$fname] = "html"; }
else { $gen_columns[] = $fname; }
}
$gen_extra = '';
if($gallery_status == 1){
$gen_extra = "'extra'=>array(array('label'=>bo_t('grid_manage_gallery'),'url'=>'control/manage_gallery/".$content_name."/{id}','icon'=>'bx-film')),";
}

$list_view='<?php
// 1. privilege block
$content_name="'.$content_name.'";
$id_content="'.$id_content.'";
$sego =$this->uri->segment(4);
$gallery_status="'.$gallery_status.'";
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
// 2. grid config
$grid=array(
\'module\'=>\''.$table_name.'\',\'key\'=>\'id_'.$table_name.'\',\'rows\'=>$info,\'title\'=>$title,\'order\'=>$sego,
\'columns\'=>'.var_export($gen_columns, true).',
\'types\'=>'.var_export($gen_types, true).',
\'lookups\'=>'.var_export($gen_lookups, true).',
\'labels\'=>'.var_export($gen_labels, true).',
\'files\'=>\'uploads/'.$table_name.'/\',
\'actions\'=>array(\'edit\'=>$write_priv,\'delete\'=>$delete_priv,'.$gen_extra.'),
\'add\'=>$write_priv,\'bulk\'=>$delete_priv,\'reorder\'=>$write_priv,\'export\'=>true,
);
// 3. render
$this->load->view(\'back_office/includes/dxgrid\',$grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
';
