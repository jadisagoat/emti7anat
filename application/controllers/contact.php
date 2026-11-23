<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contactus extends CI_Controller {
 
public function index()
{		
$this->form_validation->set_rules("first_name", "first name", "trim|required");
$this->form_validation->set_rules("last_name", "last name", "trim|required");
$this->form_validation->set_rules("company", "company", "trim|required");
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("phone", "phone", "trim|required");
$this->form_validation->set_rules("email", "email", "trim|required");
$this->form_validation->set_rules("comments", "comments", "trim|required");
$this->form_validation->set_rules("attach_file", "attach file", "trim");
if ($this->form_validation->run() == FALSE){
 
 echo "Error ";
}
else{
$_data["first_name"]=$this->input->post("first_name");
$_data["last_name"]=$this->input->post("last_name");
$_data["company"]=$this->input->post("company");
$_data["title"]=$this->input->post("title");
$_data["phone"]=$this->input->post("phone");
$_data["email"]=$this->input->post("email");
$_data["comments"]=$this->input->post("comments");
$_data["created_date"]=date("Y-m-d h:i:s");
$this->db->insert('contactform',$_data); 	
$this->session->set_userdata("success_message","Information was inserted successfully");
echo "IThaunks for your contact";
}
}
 
}

