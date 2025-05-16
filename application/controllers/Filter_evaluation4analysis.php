<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filter_evaluation4analysis extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('security');
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
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('grade2analysis')){
            $gradesec=$this->input->post('grade2analysis',TRUE);
            $branch=$this->input->post('branch2analysis',TRUE);
            $quarter=$this->input->post('analysis_quarter',TRUE);

            $gradesec=xss_clean($gradesec);
            $branch=xss_clean($branch);
            $quarter=xss_clean($quarter);

            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->filter_evaluation4analysis($branch,$gradesec,$max_year,$quarter); 
            }else{
                echo $this->main_model->filter_evaluation4analysis($mybranch,$gradesec,$max_year,$quarter); 
            }
        }  
	}   
}