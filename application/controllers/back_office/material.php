<?
class Material extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="material";
}


public function index($order=""){
if(!in_array($order, array("title","sort_order"), true))
$order ="sort_order";
$data["title"]="List Subjects";
$data["content"]="back_office/material/list";
$data["info"]=$this->fct->getAll($this->table,$order);
//$this->load->view("back_office/template",$data);
//}
//$cond=array("id_classes" => $this->session->userdata("id_classes"));
//$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);

$this->load->view("back_office/template",$data);
}



public function lists($id_classes="",$order = ""){
$this->load->library('session');

if($id_classes){
$id_classes = array('id_classes'  => $id_classes );
$this->session->set_userdata($id_classes);
}

if(!in_array($order, array("title","sort_order"), true))
$order ="sort_order";
$data["title"]="List Subjects";
$data["content"]="back_office/material/list";
$cond=array("id_classes" => $this->session->userdata("id_classes"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data); 
}



public function add(){
$data["title"]="Add subject";
$data["content"]="back_office/material/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit subject";
$data["content"]="back_office/material/add";
$cond=array("id_material"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_material",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/material");
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
$this->db->where("id_material",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/material");	
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
$this->db->where("id_material",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit subject";
$this->form_validation->set_rules("title", "title", "trim|required");
//$this->form_validation->set_rules("class", "class", "trim|required");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_material"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("material",$cond); }
$data["content"]="back_office/material/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_sim=trim((string)$this->input->post("simulations_class"));
$_sim_plain=trim(str_replace("\xc2\xa0"," ",html_entity_decode(strip_tags($_sim,"<img><iframe><video><embed><object>"),ENT_QUOTES,"UTF-8")));
$_data["simulations_class"]=($_sim_plain==="") ? "" : $_sim;
//$_data["id_classes"]=$this->input->post("class");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_material",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/material");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/material/".$image)){
unlink("./uploads/material/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'material')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/material/".$value."/".$image)){
unlink("./uploads/material/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_material",$id);
$this->db->update("material",$_data);
redirect(base_url()."back_office/material");	
}

}