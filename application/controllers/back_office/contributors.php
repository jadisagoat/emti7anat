<?
class Contributors extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="contributors";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List contributors";
$data["content"]="back_office/contributors/list";
//$data["info"]=$this->fct->getAll($this->table,$order);
//$this->load->view("back_office/template",$data);
//}
$cond=array("id_material" => $this->session->userdata("id_material"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data);
}



public function lists($id_material="",$order = ""){
$this->load->library('session');

if($id_material){
$id_material = array('id_material'  => $id_material );
$this->session->set_userdata($id_material);
}

if($order == "")
$order ="sort_order";
$data["title"]="List contributors";
$data["content"]="back_office/contributors/list";
$cond=array("id_material" => $this->session->userdata("id_material"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data); 
}

public function add(){
$data["title"]="Add contributors";
$data["content"]="back_office/contributors/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit contributors";
$data["content"]="back_office/contributors/add";
$cond=array("id_contributors"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_contributors",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/contributors");
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
$this->db->where("id_contributors",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/contributors");	
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
$this->db->where("id_contributors",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit contributors";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("classes", "classes", "trim|required");
$this->form_validation->set_rules("material", "material", "trim|required");
$this->form_validation->set_rules("language", "language", "trim|required");
$this->form_validation->set_rules("image", "image", "trim");
$this->form_validation->set_rules("resume", "resume", "trim");
$this->form_validation->set_rules("cv", "cv", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_contributors"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("contributors",$cond); }
$data["content"]="back_office/contributors/add";
$this->load->view("back_office/template",$data);
}
else
{

if(!empty($_FILES["image"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_contributors"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("contributors","image",$cond_image);
if(!empty($old_image) && file_exists('./uploads/contributors/'.$old_image)){
unlink("./uploads/contributors/".$old_image);
$sumb_val1=explode(",","100x100");
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/contributors/".$value."/".$old_image)){
unlink("./uploads/contributors/".$value."/".$old_image);	 }							
} 
 } }
$image1= $this->fct->uploadImage("image","contributors");
$this->fct->createthumb1($image1,"contributors","100x100");$_data["image"]=$image1;	
}

$_data["title"]=$this->input->post("title");
$_data["id_classes"]=$this->input->post("classes"); 
$_data["id_material"]=$this->input->post("material"); 
$_data["id_language"]=$this->input->post("language"); 

if(!empty($_FILES["resume"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_contributors"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("contributors","resume",$cond_file);
if(!empty($old_file))
unlink("./uploads/contributors/".$old_file);	
}
$file1= $this->fct->uploadImage("resume","contributors");
$_data["resume"]=$file1;	
}
$_data["cv"]=$this->input->post("cv");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_contributors",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/contributors");
}
	
}

public function delete_image($field,$image='',$id=0){
// the stored file name is authoritative: CI's URI filter turns ( ) into &#40; &#41; and does not URL-decode
// the segment, so $image (kept for the legacy URL shape) is ignored; only whitelisted file fields are cleared
$allowed=array('image','resume');
$id=(int)$id;
if(!in_array($field,$allowed,true) || $id<=0){ redirect(base_url()."back_office/contributors"); }
$image=basename((string)$this->fct->getonecell("contributors",$field,array("id_contributors"=>$id)));
if($image!=="" && is_file("./uploads/contributors/".$image)){
unlink("./uploads/contributors/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'contributors')
AND name = ".$this->db->escape($field);
$query=$this->db->query($q);
$res=$query->row_array();
if($image!=="" && !empty($res) && $res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(is_file("./uploads/contributors/".$value."/".$image)){
unlink("./uploads/contributors/".$value."/".$image);	 }
} }
$_data[$field]="";
$this->db->where("id_contributors",$id);
$this->db->update("contributors",$_data);
redirect(base_url()."back_office/contributors");
}

}