<?php
class Visitor extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="visitor";
}


public function index($order=""){
// the list is a DevExtreme grid in server mode: rows come from grid_data() page by page
// ($order = optional initial sort column, as the legacy back_office/visitor/index/<field> links)
$data["title"]="List visitor";
$data["content"]="back_office/visitor/list";
$data["order"]=$order;
$data["info"]=array();
$this->load->view("back_office/template",$data);
}

/** JSON for the visitor grid (skip/take/sort/filter/search from DevExtreme, see bo_grid_data()). */
public function grid_data(){
bo_grid_output(bo_grid_data($this->table, array(
'id_content' => 25,
'columns'    => array('title','ip','id_material','id_class','date1','created_date'),
'search'     => array('title','ip'),
'lookups'    => array('id_material'=>array('material','title','id_material'), 'id_class'=>array('classes','title','id_classes')),
'order'      => 'id_visitor desc',
)));
}

public function add(){
$data["title"]="Add visitor";
$data["content"]="back_office/visitor/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit visitor";
$data["content"]="back_office/visitor/add";
$cond=array("id_visitor"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_visitor",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/visitor");
}

public function delete_all(){
$cehcklist= $this->input->post("cehcklist");
$check_option= $this->input->post("check_option");
if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ""){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_visitor",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/visitor");	
}

public function sorted(){
$sort=array();
foreach($this->input->get("table-1") as $key => $val){
if(!empty($val))
$sort[]=$val;	
}
$i=0;
for($i=0; $i<count($sort); $i++){
$_data=array("sort_order"=>$i);
$this->db->where("id_visitor",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit visitor";
$this->form_validation->set_rules("title", "title", "trim");
$this->form_validation->set_rules("ip", "ip", "trim");
$this->form_validation->set_rules("matrial", "matrial", "trim|required");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_visitor"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("visitor",$cond); }
$data["content"]="back_office/visitor/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["ip"]=$this->input->post("ip");
$_data["id_material"]=$this->input->post("matrial"); 

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_visitor",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/visitor");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/visitor/".$image)){
unlink("./uploads/visitor/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'visitor')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/visitor/".$value."/".$image)){
unlink("./uploads/visitor/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_visitor",$id);
$this->db->update("visitor",$_data);
redirect(base_url()."back_office/visitor");	
}

}