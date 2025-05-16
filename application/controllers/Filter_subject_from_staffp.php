<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filter_subject_from_staffp extends CI_Controller {
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
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $selectedBranch=$this->input->post('branch');
            echo $this->main_model->fetch_subject_from_staffplace($selectedBranch,$gradesec,$max_year,$user,$mybranch); 
        }  
	}   
}