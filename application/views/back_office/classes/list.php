<?php
// 1. privilege block
$content_name="classes";
$id_content="2";
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
// 2. rows: the legacy list showed the number of visitors of each class in red next to the title
$bo_rows = (isset($info) && is_array($info)) ? $info : array();
$bo_counts = array();
foreach ($this->db->query("SELECT id_class AS k, COUNT(*) AS n FROM visitor WHERE deleted = 0 GROUP BY id_class")->result_array() as $bo_c) {
    $bo_counts[(string) $bo_c['k']] = (int) $bo_c['n'];
}
foreach ($bo_rows as $bo_i => $bo_r) {
    $bo_k = (string) $bo_r['id_classes'];
    $bo_rows[$bo_i]['visitors'] = isset($bo_counts[$bo_k]) ? $bo_counts[$bo_k] : 0;
}
if (!in_array($sego, array('title', 'sort_order'), true)) $sego = '';
// 3. grid config
$grid=array(
'module'=>'classes','key'=>'id_classes','rows'=>$bo_rows,'title'=>$title,'order'=>$sego,
'columns'=>array('title','visitors'),
'labels'=>array('title'=>bo_label('title'),'visitors'=>bo_t('chain_visitors')),
'types'=>array('visitors'=>'number'),
'align'=>array('visitors'=>'center'),
'widths'=>array('visitors'=>140),
'search'=>array('title'),
'actions'=>array('edit'=>$write_priv,'delete'=>$delete_priv),
'add'=>$write_priv,'add_label'=>bo_t('chain_add_class'),
'bulk'=>$delete_priv,'reorder'=>$write_priv,'export'=>true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid',$grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
