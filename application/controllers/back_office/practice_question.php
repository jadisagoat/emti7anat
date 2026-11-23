<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Back office: questions of a practice test.
 * A question has a text (CKEditor) and/or an image, four options, one correct
 * option, a hint (shown after the first wrong attempt) and an optional explanation.
 */
class Practice_question extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="practice_question";
if(!$this->session->userdata('user_id')) redirect(base_url().'back_office/home');
}

public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]=bo_t("pt_c_questions_list");
$data["content"]="back_office/practice_question/list";
$cond=array("id_practice_test" => (int)$this->session->userdata("id_practice_test"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$data["test"]=$this->fct->getonerecord("practice_test",array("id_practice_test"=>(int)$this->session->userdata("id_practice_test")));
$this->load->view("back_office/template",$data);
}

// Drill-down entry from practice_test/list.php.
public function lists($id_practice_test="",$order=""){
if($id_practice_test){
$test=$this->fct->getonerow("practice_test",array("id_practice_test"=>(int)$id_practice_test));
if($test){
$this->session->set_userdata(array('id_practice_test'=>$test['id_practice_test'],'id_section'=>$test['id_section']));
}
}
$this->index($order);
}

public function add(){
$data["title"]=bo_t("pt_add_question");
$data["content"]="back_office/practice_question/add";
$this->load->view("back_office/template",$data);
}

public function edit($id){
$data["title"]=bo_t("pt_c_edit_question");
$data["content"]="back_office/practice_question/add";
$cond=array("id_practice_question"=>(int)$id);
$data["id"]=(int)$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
if(!$data["info"]) redirect(base_url()."back_office/practice_question");
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d H:i:s"));
$this->db->where("id_practice_question",(int)$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message",bo_t("pt_c_question_deleted"));
redirect(base_url()."back_office/practice_question");
}

public function delete_all(){
$cehcklist= $this->input->post("cehcklist");
$check_option= $this->input->post("check_option");
if($check_option == "delete_all" && is_array($cehcklist)){
foreach($cehcklist as $id){
if($id != ""){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d H:i:s"));
$this->db->where("id_practice_question",(int)$id);
$this->db->update($this->table,$_data);
}
}
$this->session->set_userdata("success_message",bo_t("pt_c_questions_deleted"));
}
redirect(base_url()."back_office/practice_question");
}

public function sorted(){
$rows=$this->input->get("table-1");
if(!is_array($rows)) return;
$i=0;
foreach($rows as $val){
if(empty($val)) continue;
$this->db->where("id_practice_question",(int)$val);
$this->db->update($this->table,array("sort_order"=>$i++));
}
}

public function submit(){
$data["title"]=bo_t("pt_c_add_edit_question");
$this->form_validation->set_rules("title", "title", "trim");
$this->form_validation->set_rules("question_text", "question text", "trim");
$this->form_validation->set_rules("option_1", "option A", "trim|required");
$this->form_validation->set_rules("option_2", "option B", "trim|required");
$this->form_validation->set_rules("option_3", "option C", "trim|required");
$this->form_validation->set_rules("option_4", "option D", "trim|required");
$this->form_validation->set_rules("correct_option", "correct option", "trim|required|integer|greater_than[0]|less_than[5]");
$this->form_validation->set_rules("hint", "hint", "trim");
$this->form_validation->set_rules("explanation", "explanation", "trim");

$id=(int)$this->input->post("id");
$ok=($this->form_validation->run() != FALSE);
$test=FALSE;
$old=FALSE;
$text=(string)$this->input->post("question_text");
$has_text=trim(strip_tags($text,'<img>'))!=='';

if($ok){
$test=$this->fct->getonerecord("practice_test",array("id_practice_test"=>(int)$this->input->post("id_practice_test")));
if(!$test){ $ok=FALSE; $this->session->set_userdata("error_message",bo_t("pt_c_test_missing")); }
}
if($ok && $id){
$old=$this->fct->getonerecord($this->table,array("id_practice_question"=>$id));
if(!$old){ $ok=FALSE; $this->session->set_userdata("error_message",bo_t("pt_c_question_missing")); }
}
if($ok){
$has_new_image=!empty($_FILES["image"]["name"]);
$has_old_image=($old && !empty($old["image"]));
if(!$has_text && !$has_new_image && !$has_old_image){
$ok=FALSE;
$this->session->set_userdata("error_message",bo_t("pt_c_question_text_or_image"));
}
}

if(!$ok){
if($id){
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,array("id_practice_question"=>$id)); }
$data["content"]="back_office/practice_question/add";
$this->load->view("back_office/template",$data);
return;
}

$_data["id_practice_test"]=(int)$test["id_practice_test"];
$_data["id_section"]=(int)$test["id_section"];
$_data["question_text"]=$text;
$_data["option_1"]=$this->input->post("option_1");
$_data["option_2"]=$this->input->post("option_2");
$_data["option_3"]=$this->input->post("option_3");
$_data["option_4"]=$this->input->post("option_4");
$_data["correct_option"]=(int)$this->input->post("correct_option");
$_data["hint"]=$this->input->post("hint");
$_data["explanation"]=$this->input->post("explanation");
$_data["status"]=$this->input->post("status")=="0" ? 0 : 1;

// admin label; the recycle bin lists items by title
$title=trim((string)$this->input->post("title"));
if($title===""){
$plain=trim(preg_replace('/\s+/',' ',strip_tags($text)));
if($plain!==""){ $title=function_exists('mb_substr') ? mb_substr($plain,0,80,'UTF-8') : substr($plain,0,80); }
else $title="Question (image)";
}
$_data["title"]=$title;

if(!empty($_FILES["image"]["name"])) {
$image=$this->fct->uploadFile("image","practice_question");
if($image===""){
$this->session->set_userdata("error_message",bo_t("pt_c_upload_failed").$this->fct->upload_err);
if($id){ $data["id"]=$id; $data["info"]=$old; }
$data["content"]="back_office/practice_question/add";
$this->load->view("back_office/template",$data);
return;
}
if($old && !empty($old["image"]) && is_file("./uploads/practice_question/".$old["image"])){
unlink("./uploads/practice_question/".$old["image"]);
}
$_data["image"]=$image;
}

if($id){
$_data["updated_date"]=date("Y-m-d H:i:s");
$this->db->where("id_practice_question",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message",bo_t("pt_c_saved"));
} else {
$_data["created_date"]=date("Y-m-d H:i:s");
$this->db->insert($this->table,$_data);
$this->session->set_userdata("success_message",bo_t("pt_c_question_added"));
}
redirect(base_url()."back_office/practice_question");
}

// Remove the question image only (the row keeps its text / options).
public function delete_image($field,$image,$id){
$image=basename($image);
if($image!=="" && is_file("./uploads/practice_question/".$image)){
unlink("./uploads/practice_question/".$image); }
$this->db->where("id_practice_question",(int)$id);
$this->db->update($this->table,array("image"=>""));
redirect(base_url()."back_office/practice_question/edit/".(int)$id);
}

}
