<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filtergrade_4branch extends CI_Controller {
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
        if(isset($_POST['branchRegistration'])){
            $branchRegistration=$this->input->post('branchRegistration');
            echo $this->main_model->filtergrade_4branch($branchRegistration,$max_year); 
        }  
	}  
}