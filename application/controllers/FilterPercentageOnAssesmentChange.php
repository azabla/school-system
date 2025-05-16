<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FilterPercentageOnAssesmentChange extends CI_Controller {
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

        $user=$this->session->userdata('username');
        $query_branch =$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$row_branch->usertype;
        $myDivision=$row_branch->status2;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('evaluation')){
            $gradesec=$this->input->post('gradesec');
            $evaluation=$this->input->post('evaluation');
            $quarter=$this->input->post('quarter');
            $subject=$this->input->post('subject');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->FilterPercentageAssesmentChange($evaluation,$gradesec,$max_year,$mybranch,$quarter,$subject); 
            }else{
                echo $this->main_model->FilterPercentageAssesmentChange($evaluation,$gradesec,$max_year,$mybranch,$quarter,$subject); 
            }
        }  
	}   
}