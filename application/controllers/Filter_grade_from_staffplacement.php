<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filter_grade_from_staffplacement extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $userLevel = userLevel();
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
        if($this->input->post('branch')){
            $branch=$this->input->post('branch');
            echo $this->main_model->fetch_grade_from_staffplacement($branch,$max_year,$user,$mybranch); 
        }  
	}   
}