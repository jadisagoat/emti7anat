<?
class Test_simple extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="test_simple";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List test simple";
$data["content"]="back_office/test_simple/list";
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
$data["title"]="List test simple";
$data["content"]="back_office/test_simple/list";
$cond=array("id_material" => $this->session->userdata("id_material"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data); 
}


public function add(){
$data["title"]="Add test simple";
$data["content"]="back_office/test_simple/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit test simple";
$data["content"]="back_office/test_simple/add";
$cond=array("id_test_simple"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_test_simple",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/test_simple");
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
$this->db->where("id_test_simple",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/test_simple");	
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
$this->db->where("id_test_simple",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit test simple";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("class", "class", "trim|required");
$this->form_validation->set_rules("subject", "subject", "trim|required");
$this->form_validation->set_rules("simple_exam", "simple exam", "trim");
$this->form_validation->set_rules("language", "language", "trim|required");

$this->form_validation->set_rules("solution", "solution", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_test_simple"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("test_simple",$cond); }
$data["content"]="back_office/test_simple/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["id_class"]=$this->input->post("class"); 
$_data["id_material"]=$this->input->post("subject"); 
$_data["id_language"]=$this->input->post("language"); 

if(!empty($_FILES["simple_exam"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_test_simple"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("test_simple","simple_exam",$cond_file);
if(!empty($old_file))
unlink("./uploads/test_simple/".$old_file);	
}
$file1= $this->fct->uploadImage("simple_exam","test_simple");
$_data["simple_exam"]=$file1;	
}

if(!empty($_FILES["solution"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_test_simple"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("test_simple","solution",$cond_file);
if(!empty($old_file))
unlink("./uploads/test_simple/".$old_file);	
}
$file1= $this->fct->uploadImage("solution","test_simple");
$_data["solution"]=$file1;	
}

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_test_simple",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/test_simple");
}
	
}

public function delete_image($field,$image='',$id=0){
// the stored file name is authoritative: CI's URI filter turns ( ) into &#40; &#41; and does not URL-decode
// the segment, so $image (kept for the legacy URL shape) is ignored; only whitelisted file fields are cleared
$allowed=array('simple_exam','solution');
$id=(int)$id;
if(!in_array($field,$allowed,true) || $id<=0){ redirect(base_url()."back_office/test_simple"); }
$image=basename((string)$this->fct->getonecell("test_simple",$field,array("id_test_simple"=>$id)));
if($image!=="" && is_file("./uploads/test_simple/".$image)){
unlink("./uploads/test_simple/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'test simple')
AND name = ".$this->db->escape($field);
$query=$this->db->query($q);
$res=$query->row_array();
if($image!=="" && !empty($res) && $res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(is_file("./uploads/test_simple/".$value."/".$image)){
unlink("./uploads/test_simple/".$value."/".$image);	 }
} }
$_data[$field]="";
$this->db->where("id_test_simple",$id);
$this->db->update("test_simple",$_data);
redirect(base_url()."back_office/test_simple");
}

}