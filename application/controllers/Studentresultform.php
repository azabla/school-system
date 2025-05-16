<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Studentresultform extends CI_Controller {
    public function __construct(){
        parent::__construct();
      if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('Login/');
        }
        
    }
	public function index()
	{
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradesec')){
            $academicyear=$this->input->post('academicyear');
            $gradesec=$this->input->post('gradesec');
            $subject=$this->input->post('subject');
            $evaluation=$this->input->post('evaluation');
            $quarter=$this->input->post('quarter');
            $assesname=$this->input->post('assesname');
            $percentage=$this->input->post('percentage');
            echo $this->main_model->fetch_thisgrade_students_fornewexam($academicyear,$gradesec,$subject,$evaluation,$quarter,$assesname,$percentage,$branch,$max_year);
        }
	} 
}