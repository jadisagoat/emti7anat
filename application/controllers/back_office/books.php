<?
class Books extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="books";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List books";
$data["content"]="back_office/books/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add books";
$data["content"]="back_office/books/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit books";
$data["content"]="back_office/books/add";
$cond=array("id_books"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_books",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/books");
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
$this->db->where("id_books",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/books");	
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
$this->db->where("id_books",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit books";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("photo", "photo", "trim");
$this->form_validation->set_rules("pdf", "pdf", "trim");
$this->form_validation->set_rules("laungage", "laungage", "trim|required");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_books"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("books",$cond); }
$data["content"]="back_office/books/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
if(!empty($_FILES["photo"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_books"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("books","photo",$cond_image);
if(!empty($old_image) && file_exists('./uploads/books/'.$old_image)){
unlink("./uploads/books/".$old_image);
 } }
$image1= $this->fct->uploadImage("photo","books");
$_data["photo"]=$image1;	
}

if(!empty($_FILES["pdf"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_books"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("books","pdf",$cond_file);
if(!empty($old_file))
unlink("./uploads/books/".$old_file);	
}
$file1= $this->fct->uploadImage("pdf","books");
$_data["pdf"]=$file1;	
}
$_data["id_language"]=$this->input->post("laungage"); 

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_books",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/books");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/books/".$image)){
unlink("./uploads/books/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'books')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/books/".$value."/".$image)){
unlink("./uploads/books/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_books",$id);
$this->db->update("books",$_data);
redirect(base_url()."back_office/books");	
}

}