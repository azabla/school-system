<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usertypeteacherdirector extends CI_Controller {
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
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        if(isset($_POST['usertype'])){
            $usertype=$this->input->post('usertype');
            if($usertype ===trim('Student')){
                echo $this->main_model->fetch_assigned_grade($max_year,$user); 
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