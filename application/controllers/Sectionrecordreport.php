<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sectionrecordreport extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        $this->load->helper('security');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $this->db->where('usergroup',$_SESSION['usertype']);
        $this->db->where('tableName','Student');
        $this->db->where('allowed','StudentVE');
        $uperStuView=$this->db->get('usergrouppermission'); 
        if($this->session->userdata('username') == '' || $uperStuView->num_rows()<1 || $userLevel!='1'){
            $this->session->set_flashdata("error","Please Login first");
            $this->load->driver('cache');
            delete_cookie('username');
            unset($_SESSION);
            session_destroy();
            $this->cache->clean();
            ob_clean();
            redirect('login/');
        } 
    }
	public function index($page='sectionrecordreport')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year();
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function FecthThiSectionStudent(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('gs_gradesec')){
            $gs_branches=$this->input->post('gs_branches',TRUE);
            $gs_gradesec=$this->input->post('gs_gradesec',TRUE);
            $grands_academicyear=$this->input->post('grands_academicyear',TRUE);

            $gs_branches=xss_clean($gs_branches);
            $gs_gradesec=xss_clean($gs_gradesec);
            $grands_academicyear=xss_clean($grands_academicyear);
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_grade_branchstudents($gs_branches,$gs_gradesec,$grands_academicyear);
            }else{
                echo $this->main_model->fetch_grade_branchstudents($branch,$gs_gradesec,$grands_academicyear);
            }
        }
    }
}