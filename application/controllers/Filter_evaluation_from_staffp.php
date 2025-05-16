<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filter_evaluation_from_staffp extends CI_Controller {
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
        $query2 = $this->db->query("select max(term) as quarter from quarter");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            echo $this->main_model->fetch_evaluation_from_staffplace($gradesec,$max_year,$max_quarter); 
        }  
	}   
}