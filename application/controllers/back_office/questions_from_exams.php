<?php
class Questions_from_exams extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="questions_from_exams";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List supplementary problems";
$data["content"]="back_office/questions_from_exams/list";
$cond=array("id_section" => $this->session->userdata("id_section"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data);
}



public function lists($id_section="",$order = ""){
$this->load->library('session');

if($id_section){
$id_section = array('id_section'  => $id_section );
$this->session->set_userdata($id_section);
}

if($order == "")
$order ="sort_order";
$data["title"]="List supplementary problems";
$data["content"]="back_office/questions_from_exams/list";
$cond=array("id_section" => $this->session->userdata("id_section"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data); 
}


public function add(){
$data["title"]="Add supplementary problems";
$data["content"]="back_office/questions_from_exams/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit supplementary problems";
$data["content"]="back_office/questions_from_exams/add";
$cond=array("id_questions_from_exams"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_questions_from_exams",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/questions_from_exams");
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
$this->db->where("id_questions_from_exams",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/questions_from_exams");	
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
$this->db->where("id_questions_from_exams",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit questions from exams";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("language", "language", "trim|required");
//$this->form_validation->set_rules("class", "class", "trim|required");
$this->form_validation->set_rules("material", "material", "trim|required");
$this->form_validation->set_rules("unit", "unit", "trim|required");
$this->form_validation->set_rules("section", "section", "trim|required");
$this->form_validation->set_rules("question", "question", "trim");
$this->form_validation->set_rules("answer", "answer", "trim");
    $this->form_validation->set_rules("description", "description", "trim");

if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_questions_from_exams"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("questions_from_exams",$cond); }
$data["content"]="back_office/questions_from_exams/add";
$this->load->view("back_office/template",$data);
}
else
{
    $_data["description"]=$this->input->post("description");
$_data["title"]=$this->input->post("title");
$_data["id_language"]=$this->input->post("language"); 
$_data["id_classes"]=$this->input->post("class"); 
$_data["id_material"]=$this->input->post("material"); 
$_data["id_unit"]=$this->input->post("unit"); 
$_data["id_section"]=$this->input->post("section"); 

if(!empty($_FILES["question"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_questions_from_exams"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("questions_from_exams","question",$cond_file);
if(!empty($old_file))
unlink("./uploads/questions_from_exams/".$old_file);	
}
$file1= $this->fct->uploadImage("question","questions_from_exams");
$_data["question"]=$file1;	
}

if(!empty($_FILES["answer"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_questions_from_exams"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("questions_from_exams","answer",$cond_file);
if(!empty($old_file))
unlink("./uploads/questions_from_exams/".$old_file);	
}
$file1= $this->fct->uploadImage("answer","questions_from_exams");
$_data["answer"]=$file1;	
}

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_questions_from_exams",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/questions_from_exams");
}
	
}

public function delete_image($field,$image='',$id=0){
// the stored file name is authoritative: CI's URI filter turns ( ) into &#40; &#41; and does not URL-decode
// the segment, so $image (kept for the legacy URL shape) is ignored; only whitelisted file fields are cleared
$allowed=array('question','answer');
$id=(int)$id;
if(!in_array($field,$allowed,true) || $id<=0){ redirect(base_url()."back_office/questions_from_exams"); }
$image=basename((string)$this->fct->getonecell("questions_from_exams",$field,array("id_questions_from_exams"=>$id)));
if($image!=="" && is_file("./uploads/questions_from_exams/".$image)){
unlink("./uploads/questions_from_exams/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'questions from exams')
AND name = ".$this->db->escape($field);
$query=$this->db->query($q);
$res=$query->row_array();
if($image!=="" && !empty($res) && $res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(is_file("./uploads/questions_from_exams/".$value."/".$image)){
unlink("./uploads/questions_from_exams/".$value."/".$image);	 }
} }
$_data[$field]="";
$this->db->where("id_questions_from_exams",$id);
$this->db->update("questions_from_exams",$_data);
redirect(base_url()."back_office/questions_from_exams");
}

}
