<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Back office: reports of the practice tests.
 * Rows are written by the public side (home/practice_result) when a student finishes a test;
 * this module filters, summarises, exports (CSV) and deletes them.
 */
class Practice_result extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="practice_result";
if(!$this->session->userdata('user_id')) redirect(base_url().'back_office/home');
}

// Filters come from the query string (the form uses GET so the report can be bookmarked / exported).
private function _filters(){
$f=array(
"test"=>(int)$this->input->get("test"),
"member"=>(int)$this->input->get("member"),
"from"=>trim((string)$this->input->get("from")),
"to"=>trim((string)$this->input->get("to")),
"status"=>(string)$this->input->get("status"));
// admin dates are mm/dd/yyyy (datepicker)
$f["from_sql"]=preg_match('#^\d{1,2}/\d{1,2}/\d{4}$#',$f["from"]) ? $this->fct->date_in_formate($f["from"]) : "";
$f["to_sql"]=preg_match('#^\d{1,2}/\d{1,2}/\d{4}$#',$f["to"]) ? $this->fct->date_in_formate($f["to"]) : "";
if(!in_array($f["status"],array("passed","failed","unfinished"))) $f["status"]="";
return $f;
}

private function _rows($f){
$this->db->select("r.*, m.name AS member_name, m.username AS member_username, t.title AS test_title, s.title AS section_title");
$this->db->from("practice_result r");
$this->db->join("members m","m.id_members = r.id_members","left");
$this->db->join("practice_test t","t.id_practice_test = r.id_practice_test","left");
$this->db->join("section s","s.id_section = r.id_section","left");
$this->db->where("r.deleted",0);
if($f["test"]) $this->db->where("r.id_practice_test",$f["test"]);
if($f["member"]) $this->db->where("r.id_members",$f["member"]);
if($f["from_sql"]!=="") $this->db->where("r.created_date >=",$f["from_sql"]." 00:00:00");
if($f["to_sql"]!=="") $this->db->where("r.created_date <=",$f["to_sql"]." 23:59:59");
// status 1 = finished, 0 = started but not finished
if($f["status"]==="passed"){ $this->db->where("r.status",1); $this->db->where("r.passed",1); }
if($f["status"]==="failed"){ $this->db->where("r.status",1); $this->db->where("r.passed",0); }
if($f["status"]==="unfinished") $this->db->where("r.status",0);
$this->db->order_by("r.created_date","desc");
return $this->db->get()->result_array();
}

public function index(){
$f=$this->_filters();
$data["f"]=$f;
$data["rows"]=$this->_rows($f);
$data["tests"]=$this->fct->getAll("practice_test","sort_order");
$data["members"]=$this->fct->getAll("members","name");
$data["title"]=bo_t("pt_c_reports_title");
$data["content"]="back_office/practice_result/list";
$this->load->view("back_office/template",$data);
}

// CSV (UTF-8 with BOM so Excel opens it correctly), same filters as the page.
public function export(){
$f=$this->_filters();
$rows=$this->_rows($f);
$name="practice-results-".date("Y-m-d").".csv";
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=\"".$name."\"");
header("Pragma: no-cache");
$out=fopen("php://output","w");
fwrite($out,"\xEF\xBB\xBF");
fputcsv($out,array(bo_t("pt_date"),bo_t("pt_student"),bo_t("pt_username"),bo_t("pt_test"),bo_t("pt_lesson"),bo_t("pt_csv_score"),bo_t("pt_csv_max"),bo_t("pt_percent"),bo_t("pt_csv_pass_mark"),bo_t("pt_result"),bo_t("pt_csv_duration"),bo_t("pt_timed_out"),bo_t("pt_csv_first"),bo_t("pt_csv_second"),bo_t("pt_wrong"),bo_t("pt_csv_qcount"),bo_t("pt_csv_answers")));
foreach($rows as $r){
$fin=((int)$r["status"]===1);
$d=(int)$r["duration_sec"];
// per question: "1: B) Tripoli > A) Beirut | correct A) Beirut | grade B"
$det=json_decode((string)$r["details"],TRUE); $answers=array();
if(is_array($det)){ foreach($det as $q){
$chosen=(isset($q["chosen_text"]) && is_array($q["chosen_text"]) && count($q["chosen_text"])) ? implode(" > ",$q["chosen_text"]) : "-";
$answers[]=(int)$q["n"].": ".$chosen.(!empty($q["correct_text"]) ? " | correct ".$q["correct_text"] : "")." | ".$q["grade"];
} }
fputcsv($out,array(
$r["created_date"],
$r["member_name"]!==NULL ? $r["member_name"] : "(deleted student)",
(string)$r["member_username"],
$r["test_title"]!==NULL ? $r["test_title"] : "(deleted test)",
(string)$r["section_title"],
(float)$r["score"],(float)$r["max_score"],(float)$r["percent"],(float)$r["pass_percent"],
$fin ? ($r["passed"] ? bo_t("pt_passed") : bo_t("pt_failed")) : bo_t("pt_unfinished"),
$fin ? sprintf('%02d:%02d',floor($d/60),$d%60) : "",
$r["timed_out"] ? bo_t("pt_yes") : "",
(int)$r["first_try"],(int)$r["second_try"],(int)$r["failed"],(int)$r["total_questions"],
implode(" || ",$answers)));
}
fclose($out);
exit;
}

public function delete($id=0){
$_data=array("deleted"=>1,"deleted_date"=>date("Y-m-d H:i:s"));
$this->db->where("id_practice_result",(int)$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message",bo_t("pt_c_result_deleted"));
redirect(base_url()."back_office/practice_result".($this->input->server("QUERY_STRING") ? "?".$this->input->server("QUERY_STRING") : ""));
}

}
