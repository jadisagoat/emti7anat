<?
class Home extends CI_Controller{
public function __construct()
{
parent::__construct();
// Your own constructor code
//$this->headers='back_office/includes/header';
//$this->footer='back_office/includes/footer';

}



public function index(){
$this->load->view('back_office/login');
/*	$data=array(
	'pg_title'=>'Home Page',
	'title'=>'DashBoard',
	'header' => $this->headers,
	'content' =>'back_office/dashboard',
	'footer'=>$this->footer );
	$this->load->view('back_office/template',$data);*/
}

public function login_validate(){
// field names and messages in the admin language (bo_lang cookie; the login page has no session yet)
$this->form_validation->set_message('required', bo_t('login_err_required'));
$this->form_validation->set_message('min_length', bo_t('login_err_min'));
$this->form_validation->set_message('max_length', bo_t('login_err_max'));
$this->form_validation->set_rules('username', bo_t('login_username'), 'trim|required|min_length[5]|max_length[12]|xss_clean');
$this->form_validation->set_rules('pass', bo_t('login_password'), 'trim|required|min_length[5]|max_length[12]|xss_clean');
if ($this->form_validation->run() == FALSE){
 $this->load->view('back_office/login'); 
}else{
$username= $this->input->post('username');
$password=$this->input->post('pass');
$this->db->where('user_name',$username);
$this->db->where('password',$password);
$query=$this->db->get('admin'); 
$admin=$query->row_array();

if($query->num_rows() >0 ){
$this->session->set_userdata('user_id',$admin["admin_id"]);
$this->session->set_userdata('user_name',$admin["admin_name"]);
$this->session->set_userdata('level',$admin["level"]);
$this->session->set_userdata('login_date',date("d M Y - h:i:s A"));
redirect(base_url().'back_office/home/dashboard');	 	
} else{
// check in user table 
$this->db->where('username',$username);
$this->db->where('password',$password);
$this->db->where('deleted',0);
$query=$this->db->get('user');
$user=$query->row_array();
if($query->num_rows() >0 ){
$this->session->set_userdata('user_id',$user["id_user"]);
$this->session->set_userdata('user_name',$user["username"]);
$this->session->set_userdata('level',0);
$this->session->set_userdata('login_date',date("d M Y - h:i:s A"));
redirect(base_url().'back_office/home/dashboard');	
} 
else{
$this->session->set_userdata('login_error','check your username or password .');
$this->load->view('back_office/login'); 	
}
}

}
}
public function dashboard(){
$data["title"] = "CreativeLebanon Admin Section";
$data["content"]="back_office/dashboard";
$data["date_calendar"]=$this->fct->module(1);
$data["vis_links"]=$this->fct->module(2);
//$data["system_message"]=$this->fct->module(3);
$data["todo"]=$this->fct->module(4);
$data["todo_list"]=$this->fct->getto_do_list();
$data["todo_list_comp"]=$this->fct->getto_do_list_completed();
//$data["messages"]=$this->fct->getAll_limit('contactform','created_date','DESC',2);
$data["content_type"]=$this->fct->getAll('content_type','sort_order'); 

$data["messages"]=$this->fct->getAll_orderdate('contactform');
$data["new_message"]=$this->fct->get_unreaded_emails();
    
    $data["orderonline"]=$this->fct->getAll_orderdate('orderonline');
    $data["new_orderonline"]=$this->fct->get_unreaded_orderonline();
    
    

$data["newsletter_emails"]=$this->fct->getAll_orderdate('newsletter');
$data["users"]=$this->fct->getAll_orderdate('user');


$this->load->view('back_office/template', $data);	
}

public function complete_todo($id){
$cond=array('id_do'=>$id);
$do_it=$this->fct->getonerow('do_it',$cond);
echo '<a class="cur" onclick="completed('.$id.');">';
if($do_it["completed"] == 1){
$action = 0;
echo "<span class='label label-important'>Pending</span>";
}
else{
echo "<span class='label label-success'>Completed</span>";
$action = 1;
}
echo '</a>';
$_data["completed"] = $action ; 
$this->db->where('id_do',$id);
$this->db->update('do_it',$_data);
}

public function do_it_pop_up($id){
$cond=array('id_do'=>$id);
$do_it=$this->fct->getonerow('do_it',$cond);
?>
<div class="modal-header">
<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel"><?= $do_it["title"]; ?>&nbsp;&nbsp;<small><?= $do_it["Deadline"]; ?></small></h3>
</div>
<div class="modal-body" id="modal-body">
<p><?= $do_it["description"]; ?></p>
</div>
<div class="modal-footer">
<button class="btn" data-bs-dismiss="modal" aria-hidden="true">Close</button>
</div>
<?
//echo '<div style="height:auto;"><h2 style="font-size:160%; font-weight:bold; margin:10px 0;">'.$do_it["title"].'&nbsp;&nbsp;<span style="color:#AE9A62">Deadline:'.$do_it["Deadline"].'</span> </h2>
//<p>'.$do_it["description"].'</p></div>';	
}

public function message_popup($id){
$cond=array('id'=>$id);
$do_it=$this->fct->getonerow('contactform',$cond);
echo '<div style="height:auto;"><h2 style="font-size:160%; font-weight:bold; margin:10px 0;">'.$do_it["subject"].'&nbsp;&nbsp;<span style="color:#AE9A62">Date:'.$do_it["created_date"].'</span> </h2>
<p>'.$do_it["message"].'</p></div>';	
}

/** Switch the admin language (ar|en): cookie + session, then back to the referer. */
public function lang($code=""){
$code = ($code === "en") ? "en" : "ar";
setcookie("bo_lang", $code, time()+31536000, "/");
$this->session->set_userdata("bo_lang", $code);
$ref = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "";
if($ref !== "" && strpos($ref, base_url()) === 0 && strpos($ref, "/home/lang/") === false){
redirect($ref);
}
redirect(base_url()."back_office/home/dashboard");
}

public function logout(){
$this->session->unset_userdata('user_id');
$this->session->unset_userdata('user_name');
redirect(base_url().'back_office/home');	 
}
	
	
}