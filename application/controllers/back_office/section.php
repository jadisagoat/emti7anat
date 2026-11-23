<?
class Section extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="section";
}


public function index($order=""){
if(!in_array($order, array("title","sort_order"), true))
$order ="sort_order";
$data["title"]="List Lessons";
$data["content"]="back_office/section/list";
$cond=array("id_unit" => $this->session->userdata("id_unit"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data);
}



public function lists($id_unit="",$order = ""){
$this->load->library('session');
 

if($id_unit){
$id_unit = array('id_unit'  => $id_unit );
$this->session->set_userdata($id_unit);
}
 
if(!in_array($order, array("title","sort_order"), true))
$order ="sort_order";
$data["title"]="List Lessons";
$data["content"]="back_office/section/list";
$cond=array("id_unit" => $this->session->userdata("id_unit"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data); 
}

public function add(){
$data["title"]="Add section";
$data["content"]="back_office/section/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit section";
$data["content"]="back_office/section/add";
$cond=array("id_section"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_section",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/section");
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
$this->db->where("id_section",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/section");	
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
$this->db->where("id_section",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit section";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("class", "class", "trim");
$this->form_validation->set_rules("material", "material", "trim");
$this->form_validation->set_rules("unit", "unit", "trim");
$this->form_validation->set_rules("summary", "summary", "trim");

if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_section"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("section",$cond); }
$data["content"]="back_office/section/add";
$this->load->view("back_office/template",$data);
}
else
{

 
$_data["title"]=$this->input->post("title");
$_data["id_classes"]=$this->input->post("class"); 
$_data["id_material"]=$this->input->post("material"); 
$_data["id_unit"]=$this->input->post("unit"); 

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_section",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/section");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/section/".$image)){
unlink("./uploads/section/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'section')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/section/".$value."/".$image)){
unlink("./uploads/section/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_section",$id);
$this->db->update("section",$_data);
redirect(base_url()."back_office/section");	
}

}