<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mysessions extends CI_Controller {
  public function __construct(){
    parent::__construct();
    if($this->session->userdata('username') == ''){
      $this->session->set_flashdata("error","Please Login first");
      redirect('Login/');
    }    
  }
	public function index($page='mysessions')
  {
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $logged_id=$row_branch->id;
    $date_now= date('y-m-d');
    $data['loggeduser']=$this->main_model->my_sessions($logged_id);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['posts']=$this->main_model->fetch_post();
    $this->load->view('home-page/'.$page,$data); 
  }
}