<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Back office: practice tests (one or more per lesson / section).
 * Same structure as the generated modules (unit.php): index, lists, add, edit,
 * delete, delete_all, sorted, submit — plus results().
 */
class Practice_test extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="practice_test";
// the generated modules only guard the template; guard every action here
if(!$this->session->userdata('user_id')) redirect(base_url().'back_office/home');
}

// Standalone section: lists every test (the lesson link is optional).
public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]=bo_t("pt_c_tests_list");
$data["content"]="back_office/practice_test/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

// Kept for a future drill-down from a lesson; today it just opens the full list.
public function lists($id_section="",$order=""){
$this->index($order);
}

public function add(){
$data["title"]=bo_t("pt_add_test");
$data["content"]="back_office/practice_test/add";
$this->load->view("back_office/template",$data);
}

public function edit($id){
$data["title"]=bo_t("pt_c_edit_test");
$data["content"]="back_office/practice_test/add";
$cond=array("id_practice_test"=>(int)$id);
$data["id"]=(int)$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
if(!$data["info"]) redirect(base_url()."back_office/practice_test");
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d H:i:s"));
$this->db->where("id_practice_test",(int)$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message",bo_t("pt_c_test_deleted"));
redirect(base_url()."back_office/practice_test");
}

public function delete_all(){
$cehcklist= $this->input->post("cehcklist");
$check_option= $this->input->post("check_option");
if($check_option == "delete_all" && is_array($cehcklist)){
foreach($cehcklist as $id){
if($id != ""){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d H:i:s"));
$this->db->where("id_practice_test",(int)$id);
$this->db->update($this->table,$_data);
}
}
$this->session->set_userdata("success_message",bo_t("pt_c_tests_deleted"));
}
redirect(base_url()."back_office/practice_test");
}

public function sorted(){
$rows=$this->input->get("table-1");
if(!is_array($rows)) return;
$i=0;
foreach($rows as $val){
if(empty($val)) continue;
$this->db->where("id_practice_test",(int)$val);
$this->db->update($this->table,array("sort_order"=>$i++));
}
}

public function submit(){
$data["title"]=bo_t("pt_c_add_edit_test");
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("section", "lesson", "trim|integer");
$this->form_validation->set_rules("description", "description", "trim");
$this->form_validation->set_rules("questions_per_test", "questions per test", "trim|required|integer");
$this->form_validation->set_rules("max_attempts", "attempts", "trim|required|integer|greater_than[0]|less_than[6]");
$this->form_validation->set_rules("label_first", "grade (1st attempt)", "trim|required|max_length[10]");
$this->form_validation->set_rules("label_second", "grade (2nd attempt)", "trim|required|max_length[10]");
$this->form_validation->set_rules("label_fail", "grade (failed)", "trim|required|max_length[10]");
$this->form_validation->set_rules("points_first", "points (1st attempt)", "trim|required|numeric");
$this->form_validation->set_rules("points_second", "points (2nd attempt)", "trim|required|numeric");
$this->form_validation->set_rules("points_fail", "points (failed)", "trim|required|numeric");
$this->form_validation->set_rules("pass_percent", "pass mark", "trim|required|numeric|greater_than[-1]|less_than[101]");
$this->form_validation->set_rules("time_limit", "time limit", "trim|required|integer|greater_than[-1]");
$this->form_validation->set_rules("max_tries", "tries per student", "trim|required|integer|greater_than[-1]");

// the lesson is optional (standalone tests); when given it must exist
$ok=($this->form_validation->run() != FALSE);
$section=array("id_section"=>0,"id_unit"=>0,"id_material"=>0,"id_classes"=>0);
if($ok && (int)$this->input->post("section")>0){
$section=$this->fct->getonerow("section",array("id_section"=>(int)$this->input->post("section")));
if(!$section){ $ok=FALSE; $this->session->set_userdata("error_message",bo_t("pt_c_section_missing")); }
}

if (!$ok){
if($this->input->post("id")!=""){
$cond=array("id_practice_test"=>(int)$this->input->post("id"));
$data["id"]=(int)$this->input->post("id");
$data["info"]=$this->fct->getonerecord($this->table,$cond); }
$data["content"]="back_office/practice_test/add";
$this->load->view("back_office/template",$data);
return;
}

$_data["title"]=$this->input->post("title");
$_data["description"]=$this->input->post("description");
// the hierarchy ids come from the lesson row itself (zeros for a standalone test)
$_data["id_section"]=(int)$section["id_section"];
$_data["id_unit"]=(int)$section["id_unit"];
$_data["id_material"]=(int)$section["id_material"];
$_data["id_classes"]=(int)$section["id_classes"];
$_data["status"]=$this->input->post("status")=="1" ? 1 : 0;
$_data["questions_per_test"]=max(0,(int)$this->input->post("questions_per_test"));
$_data["max_attempts"]=(int)$this->input->post("max_attempts");
$_data["grade_style"]=in_array($this->input->post("grade_style"),array("letter","number")) ? $this->input->post("grade_style") : "letter";
$_data["label_first"]=$this->input->post("label_first");
$_data["label_second"]=$this->input->post("label_second");
$_data["label_fail"]=$this->input->post("label_fail");
$_data["points_first"]=(float)$this->input->post("points_first");
$_data["points_second"]=(float)$this->input->post("points_second");
$_data["points_fail"]=(float)$this->input->post("points_fail");
$_data["reveal_mode"]=$this->input->post("reveal_mode")=="auto" ? "auto" : "button";
$_data["show_total"]=$this->input->post("show_total") ? 1 : 0;
$_data["pass_percent"]=(float)$this->input->post("pass_percent");
$_data["time_limit"]=max(0,(int)$this->input->post("time_limit"));
$_data["max_tries"]=max(0,(int)$this->input->post("max_tries"));
$_data["random_questions"]=$this->input->post("random_questions") ? 1 : 0;
$_data["display_mode"]=$this->input->post("display_mode")=="one" ? "one" : "all";

if($this->input->post("id")!=""){
$_data["updated_date"]=date("Y-m-d H:i:s");
$this->db->where("id_practice_test",(int)$this->input->post("id"));
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message",bo_t("pt_c_saved"));
} else {
$_data["created_date"]=date("Y-m-d H:i:s");
$this->db->insert($this->table,$_data);
$this->session->set_userdata("success_message",bo_t("pt_c_test_added"));
}
redirect(base_url()."back_office/practice_test");
}

// Results of every student for one test (newest first).
public function results($id=0){
$data["test"]=$this->fct->getonerecord($this->table,array("id_practice_test"=>(int)$id));
if(!$data["test"]) redirect(base_url()."back_office/practice_test");
$data["title"]=bo_t("pt_c_test_results").$data["test"]["title"];
$data["content"]="back_office/practice_test/results";
$this->db->select("r.*, m.name AS member_name, m.username AS member_username");
$this->db->from("practice_result r");
$this->db->join("members m","m.id_members = r.id_members","left");
$this->db->where("r.id_practice_test",(int)$id);
$this->db->where("r.deleted",0);
$this->db->order_by("r.created_date","desc");
$data["info"]=$this->db->get()->result_array();
$this->load->view("back_office/template",$data);
}

}
