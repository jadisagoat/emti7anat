<?
class Schedule extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="schedule";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List schedule";
$data["content"]="back_office/schedule/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add schedule";
$data["content"]="back_office/schedule/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit schedule";
$data["content"]="back_office/schedule/add";
$cond=array("id_schedule"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_schedule",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/schedule");
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
$this->db->where("id_schedule",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/schedule");	
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
$this->db->where("id_schedule",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit schedule";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("date", "date", "trim");
$this->form_validation->set_rules("years_between", "years between", "trim");
$this->form_validation->set_rules("class", "class", "trim");
$this->form_validation->set_rules("time", "time", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_schedule"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("schedule",$cond); }
$data["content"]="back_office/schedule/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");

$_data["date"]=$this->fct->date_in_formate($this->input->post("date"));
$_data["years_between"]=$this->input->post("years_between");
$_data["class"]=$this->input->post("class");
$_data["time"]=$this->input->post("time");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_schedule",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/schedule");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/schedule/".$image)){
unlink("./uploads/schedule/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'schedule')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/schedule/".$value."/".$image)){
unlink("./uploads/schedule/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_schedule",$id);
$this->db->update("schedule",$_data);
redirect(base_url()."back_office/schedule");	
}

}