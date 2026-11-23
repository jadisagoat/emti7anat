<?php
// 1. privilege block
$content_name="news";
$id_content="21";
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
// 2. grid config (today's list shows only Title; the image column stays on the edit form)
$grid=array(
'module'=>'news','key'=>'id_news','rows'=>$info,'title'=>$title,'order'=>$sego,
'hide'=>array('description','date','images','status'),
'types'=>array('date'=>'date','images'=>'image','description'=>'html','status'=>'status'),
'files'=>'uploads/news/',
'actions'=>array('edit'=>$write_priv,'delete'=>$delete_priv),
'add'=>$write_priv,'bulk'=>$delete_priv,'reorder'=>$write_priv,'export'=>true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid',$grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
