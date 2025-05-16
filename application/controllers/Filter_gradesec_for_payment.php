<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filter_gradesec_for_payment extends CI_Controller {
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
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            echo $this->main_model->fetch_gradesec_forpayment($gradesec,$max_year); 
        }  
	}   
}