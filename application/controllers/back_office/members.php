<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Back office: student profiles (table `members`).
 * 2026-10-21: access period (access_start / access_end), active status, hashed
 * passwords, "renew" (+1 year) and per-student practice results.
 */
class Members extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="members";
if(!$this->session->userdata('user_id')) redirect(base_url().'back_office/home');
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]=bo_t("pt_c_students_list");
$data["content"]="back_office/members/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]=bo_t("pt_add_student");
$data["content"]="back_office/members/add";
$this->load->view("back_office/template",$data);
}

public function edit($id){
$data["title"]=bo_t("pt_c_edit_student");
$data["content"]="back_office/members/add";
$cond=array("id_members"=>(int)$id);
$data["id"]=(int)$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
if(!$data["info"]) redirect(base_url()."back_office/members");
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d H:i:s"));
$this->db->where("id_members",(int)$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message",bo_t("pt_c_student_deleted"));
redirect(base_url()."back_office/members");
}

public function delete_all(){
$cehcklist= $this->input->post("cehcklist");
$check_option= $this->input->post("check_option");
if($check_option == "delete_all" && is_array($cehcklist)){
foreach($cehcklist as $id){
if($id != ""){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d H:i:s"));
$this->db->where("id_members",(int)$id);
$this->db->update($this->table,$_data);
}
}
$this->session->set_userdata("success_message",bo_t("pt_c_students_deleted"));
}
redirect(base_url()."back_office/members");
}

public function sorted(){
$rows=$this->input->get("table-1");
if(!is_array($rows)) return;
$i=0;
foreach($rows as $val){
if(empty($val)) continue;
$this->db->where("id_members",(int)$val);
$this->db->update($this->table,array("sort_order"=>$i++));
}
}

// Extends the access period by PT_ACCESS_YEARS (from today, or from the current end if still valid).
public function renew($id){
$m=$this->fct->getonerecord($this->table,array("id_members"=>(int)$id));
if($m){
$today=date("Y-m-d");
$base=(!empty($m["access_end"]) && $m["access_end"]>$today) ? $m["access_end"] : $today;
$_data=array(
"access_end"=>date("Y-m-d",strtotime($base." +".PT_ACCESS_YEARS." year")),
"status"=>1,
"updated_date"=>date("Y-m-d H:i:s"));
if(empty($m["access_start"])) $_data["access_start"]=$today;
$this->db->where("id_members",(int)$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message",$m["name"].sprintf(bo_t("pt_c_renewed"),$this->fct->date_out_formate($_data["access_end"])));
}
redirect(base_url()."back_office/members");
}

// Practice results of one student (newest first).
public function results($id=0){
$data["member"]=$this->fct->getonerecord($this->table,array("id_members"=>(int)$id));
if(!$data["member"]) redirect(base_url()."back_office/members");
$data["title"]=bo_t("pt_c_student_results").$data["member"]["name"];
$data["content"]="back_office/members/results";
$this->db->select("r.*, t.title AS test_title, s.title AS section_title");
$this->db->from("practice_result r");
$this->db->join("practice_test t","t.id_practice_test = r.id_practice_test","left");
$this->db->join("section s","s.id_section = r.id_section","left");
$this->db->where("r.id_members",(int)$id);
$this->db->where("r.deleted",0);
$this->db->order_by("r.created_date","desc");
$data["info"]=$this->db->get()->result_array();
$this->load->view("back_office/template",$data);
}

public function submit(){
$data["title"]=bo_t("pt_c_add_edit_student");
$id=(int)$this->input->post("id");
$this->form_validation->set_rules("name", "name", "trim|required");
$this->form_validation->set_rules("username", "username", "trim|required");
// the password is only required for a new profile; leave it empty to keep the current one
$this->form_validation->set_rules("password", "password", $id ? "trim" : "trim|required|min_length[4]");
$this->form_validation->set_rules("email", "email", "trim");
$this->form_validation->set_rules("tel", "tel", "trim");
$this->form_validation->set_rules("code", "code", "trim");
$this->form_validation->set_rules("gender", "gender", "trim");
$this->form_validation->set_rules("date_of_birth", "date of birth", "trim");
$this->form_validation->set_rules("access_start", "access start", "trim");
$this->form_validation->set_rules("access_end", "access end", "trim");
$this->form_validation->set_rules("image", "image", "trim");
$this->form_validation->set_rules("address", "address", "trim");
$this->form_validation->set_rules("notes", "notes", "trim");

$ok=($this->form_validation->run() != FALSE);
if($ok){
// usernames must be unique among active profiles
$this->db->where("username",$this->input->post("username"));
$this->db->where("deleted",0);
if($id) $this->db->where("id_members !=",$id);
if($this->db->count_all_results($this->table) > 0){
$ok=FALSE;
$this->session->set_userdata("error_message",bo_t("pt_c_username_taken"));
}
}
if(!$ok){
if($id){
$cond=array("id_members"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord("members",$cond); }
$data["content"]="back_office/members/add";
$this->load->view("back_office/template",$data);
return;
}

$_data["name"]=$this->input->post("name");
$_data["title"]=$_data["name"]; // `title` is NOT NULL without a default (strict MySQL) and is what the recycle bin lists
$_data["username"]=$this->input->post("username");
if($this->input->post("password")!==""){
$_data["password"]=password_hash($this->input->post("password"),PASSWORD_DEFAULT);
}
$_data["email"]=$this->input->post("email");
$_data["tel"]=$this->input->post("tel");
$_data["code"]=$this->input->post("code");
$_data["id_gender"]=(int)$this->input->post("gender");
$_data["status"]=$this->input->post("status")=="1" ? 1 : 0;
$_data["notes"]=$this->input->post("notes");
$_data["date_of_birth"]=$this->input->post("date_of_birth")!="" ? $this->fct->date_in_formate($this->input->post("date_of_birth")) : NULL;
$_data["access_start"]=$this->input->post("access_start")!="" ? $this->fct->date_in_formate($this->input->post("access_start")) : NULL;
$_data["access_end"]=$this->input->post("access_end")!="" ? $this->fct->date_in_formate($this->input->post("access_end")) : NULL;

if(!empty($_FILES["image"]["name"])) {
if($id){
$cond_image=array("id_members"=>$id);
$old_image=$this->fct->getonecell("members","image",$cond_image);
if(!empty($old_image) && file_exists('./uploads/members/'.$old_image)){
unlink("./uploads/members/".$old_image);
$sumb_val1=explode(",","100x100");
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/members/".$value."/".$old_image)){
unlink("./uploads/members/".$value."/".$old_image);	 }
}
 } }
$image1= $this->fct->uploadImage("image","members");
if($image1!==""){ $this->fct->createthumb($image1,"members","100x100"); $_data["image"]=$image1; }
}
$_data["address"]=$this->input->post("address");

	if($id){
	$_data["updated_date"]=date("Y-m-d H:i:s");
	$this->db->where("id_members",$id);
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message",bo_t("pt_c_student_saved"));
	} else {
	$_data["created_date"]=date("Y-m-d H:i:s");
	$this->db->insert($this->table,$_data);
	$this->session->set_userdata("success_message",bo_t("pt_c_student_added"));
	}
	redirect(base_url()."back_office/members");
}

public function delete_image($field,$image,$id){
$image=basename($image);
if(file_exists("./uploads/members/".$image)){
unlink("./uploads/members/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'members')
AND name = '".$this->db->escape_str($field)."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res && $res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/members/".$value."/".$image)){
unlink("./uploads/members/".$value."/".$image);	 }
} }
$this->db->where("id_members",(int)$id);
$this->db->update("members",array("image"=>""));
redirect(base_url()."back_office/members");
}

}
