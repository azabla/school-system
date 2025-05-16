<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_studentresultform extends CI_Controller {
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
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];

        if($this->input->post('gradesec')){
            $academicyear=$this->input->post('academicyear');
            $branches=$this->input->post('branch');
            $gradesec=$this->input->post('gradesec');
            $subject=$this->input->post('subject');
            $evaluation=$this->input->post('evaluation');
            $quarter=$this->input->post('quarter');
            $assesname=$this->input->post('assesname');
            $percentage=$this->input->post('percentage');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_thisgrade_students_fornewexam($academicyear,$gradesec,$subject,$evaluation,$quarter,$assesname,$percentage,$branches,$max_year);
            }else{
                echo $this->main_model->fetch_thisgrade_students_fornewexam($academicyear,$gradesec,$subject,$evaluation,$quarter,$assesname,$percentage,$branch,$max_year);
            }
        }
	} 
}