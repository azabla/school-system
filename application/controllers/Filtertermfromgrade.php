<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filtertermfromgrade extends CI_Controller {
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
        if($this->input->post('gradeit')){
            $grade=$this->input->post('gradeit');
            echo $this->main_model->fetch_term_from_grade($grade,$max_year); 
        }  
	}   
}