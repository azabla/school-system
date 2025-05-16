<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_grade_from_branch extends CI_Controller {
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
        if($this->input->post('branch_statistics')){
            $branch=$this->input->post('branch_statistics');
            echo $this->main_model->fetch_grade_from_branch_4statistics($branch,$max_year); 
        }  
	}   
}