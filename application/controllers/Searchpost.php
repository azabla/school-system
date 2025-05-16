<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Searchpost extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $userLevel = userLevel();
      if($this->session->userdata('username') == '' || $userLevel!='3'){
            $this->session->set_flashdata("error","Please Login first");
            redirect('Login');
        } 
    }
	public function index($page='search')
	{
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $key=$this->input->post('search');
        $this->load->model('main_model');
         $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();//school
        $data['posts']=$this->main_model->search($key);
        $this->load->view('student/'.$page,$data);
	}
}