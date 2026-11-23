<?
class Suggestions extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="suggestions";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List suggestions";
$data["content"]="back_office/suggestions/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add suggestions";
$data["content"]="back_office/suggestions/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit suggestions";
$data["content"]="back_office/suggestions/add";
$cond=array("id_suggestions"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_suggestions",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/suggestions");
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
$this->db->where("id_suggestions",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/suggestions");	
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
$this->db->where("id_suggestions",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit suggestions";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("classes", "classes", "trim|required");
$this->form_validation->set_rules("material", "material", "trim");
$this->form_validation->set_rules("unit", "unit", "trim");
$this->form_validation->set_rules("section", "section", "trim");
$this->form_validation->set_rules("language", "language", "trim");
$this->form_validation->set_rules("question", "question", "trim");
$this->form_validation->set_rules("answer", "answer", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_suggestions"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("suggestions",$cond); }
$data["content"]="back_office/suggestions/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["id_classes"]=$this->input->post("classes"); 
$_data["id_material"]=$this->input->post("material"); 
$_data["id_unit"]=$this->input->post("unit"); 
$_data["id_section"]=$this->input->post("section"); 
$_data["id_language"]=$this->input->post("language"); 

if(!empty($_FILES["question"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_suggestions"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("suggestions","question",$cond_file);
if(!empty($old_file))
unlink("./uploads/suggestions/".$old_file);	
}
$file1= $this->fct->uploadImage("question","suggestions");
$_data["question"]=$file1;	
}

if(!empty($_FILES["answer"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_suggestions"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("suggestions","answer",$cond_file);
if(!empty($old_file))
unlink("./uploads/suggestions/".$old_file);	
}
$file1= $this->fct->uploadImage("answer","suggestions");
$_data["answer"]=$file1;	
}

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_suggestions",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/suggestions");
}
	
}

public function delete_image($field,$image='',$id=0){
// the stored file name is authoritative: CI's URI filter turns ( ) into &#40; &#41; and does not URL-decode
// the segment, so $image (kept for the legacy URL shape) is ignored; only whitelisted file fields are cleared
$allowed=array('question','answer');
$id=(int)$id;
if(!in_array($field,$allowed,true) || $id<=0){ redirect(base_url()."back_office/suggestions"); }
$image=basename((string)$this->fct->getonecell("suggestions",$field,array("id_suggestions"=>$id)));
if($image!=="" && is_file("./uploads/suggestions/".$image)){
unlink("./uploads/suggestions/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'suggestions')
AND name = ".$this->db->escape($field);
$query=$this->db->query($q);
$res=$query->row_array();
if($image!=="" && !empty($res) && $res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(is_file("./uploads/suggestions/".$value."/".$image)){
unlink("./uploads/suggestions/".$value."/".$image);	 }
} }
$_data[$field]="";
$this->db->where("id_suggestions",$id);
$this->db->update("suggestions",$_data);
redirect(base_url()."back_office/suggestions");
}

}