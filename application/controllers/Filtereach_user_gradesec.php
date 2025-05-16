<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filtereach_user_gradesec extends CI_Controller {
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
        if(isset($_POST['gradesec'])){
            $gradesec=$this->input->post('gradesec');
            echo $this->main_model->fetch_this_grade_subjects($user,$gradesec,$max_year); 
        }  
	}  
}