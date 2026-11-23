<?
class Expectations extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="expectations";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
// only a real column of the table may be used as ORDER BY (index/subject%20ar used to give a DB error page)
if(!in_array($order, $this->db->list_fields($this->table), true))
$order ="sort_order";
$data["title"]="List expectations";
$data["content"]="back_office/expectations/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add expectations";
$data["content"]="back_office/expectations/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit expectations";
$data["content"]="back_office/expectations/add";
$cond=array("id_expectations"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_expectations",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/expectations");
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
$this->db->where("id_expectations",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/expectations");	
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
$this->db->where("id_expectations",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit expectations";
$this->form_validation->set_rules("expectations_class", "expectations class", "trim|required");
$this->form_validation->set_rules("subject", "subject", "trim");
$this->form_validation->set_rules("subject_ar", "subject ar", "trim");
$this->form_validation->set_rules("maximum_grade", "maximum grade", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_expectations"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("expectations",$cond); }
$data["content"]="back_office/expectations/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["id_expectations_class"]=$this->input->post("expectations_class"); 
$_data["subject"]=$this->input->post("subject");
$_data["subject_ar"]=$this->input->post("subject_ar");
$_data["maximum_grade"]=$this->input->post("maximum_grade");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_expectations",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/expectations");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/expectations/".$image)){
unlink("./uploads/expectations/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'expectations')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/expectations/".$value."/".$image)){
unlink("./uploads/expectations/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_expectations",$id);
$this->db->update("expectations",$_data);
redirect(base_url()."back_office/expectations");	
}

}