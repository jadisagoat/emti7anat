<?php    
class Official_exams_init extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="official_exams_init";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List official exams";
$data["content"]="back_office/official_exams_init/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add official exams";
$data["content"]="back_office/official_exams_init/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit official exams";
$data["content"]="back_office/official_exams_init/add";
$cond=array("id_official_exams_init"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_official_exams_init",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/official_exams_init");
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
$this->db->where("id_official_exams_init",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/official_exams_init");	
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
$this->db->where("id_official_exams_init",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit official exams";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("class", "class", "trim");
$this->form_validation->set_rules("years", "years", "trim|required");
$this->form_validation->set_rules("pdf_1st", "pdf 1st", "trim");
$this->form_validation->set_rules("pdf_2st", "pdf 2st", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_official_exams_init"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("official_exams_init",$cond); }
$data["content"]="back_office/official_exams_init/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["id_classes"]=$this->input->post("class"); 
$_data["id_years"]=$this->input->post("years"); 

if(!empty($_FILES["pdf_1st"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_official_exams_init"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("official_exams_init","pdf_1st",$cond_file);
if(!empty($old_file))
unlink("./uploads/official_exams_init/".$old_file);	
}
$file1= $this->fct->uploadImage("pdf_1st","official_exams_init");
$_data["pdf_1st"]=$file1;	
}

if(!empty($_FILES["pdf_2st"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_official_exams_init"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("official_exams_init","pdf_2st",$cond_file);
if(!empty($old_file))
unlink("./uploads/official_exams_init/".$old_file);	
}
$file1= $this->fct->uploadImage("pdf_2st","official_exams_init");
$_data["pdf_2st"]=$file1;	
}

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_official_exams_init",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/official_exams_init");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/official_exams_init/".$image)){
unlink("./uploads/official_exams_init/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'official exams init')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/official_exams_init/".$value."/".$image)){
unlink("./uploads/official_exams_init/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_official_exams_init",$id);
$this->db->update("official_exams_init",$_data);
redirect(base_url()."back_office/official_exams_init");	
}

}
