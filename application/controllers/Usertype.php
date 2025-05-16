<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usertype extends CI_Controller {
    public function __construct(){
        parent::__construct();
      if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        }
        
    }
	public function index()
	{
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        if(isset($_POST['usertype'])){
            $usertype=$this->input->post('usertype');
            if($usertype ===trim('Student')){
                echo $this->main_model->fetch_usertype_users_grade($usertype); 
            }else{
                echo $this->main_model->fetch_usertype_users($usertype); 
            } 
        }
        if(isset($_POST['grade'])){
            $grade=$this->input->post('grade');
            echo $this->main_model->fetch_this_grade_students($grade); 
        }  
	}  
}