<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assesmentrank extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        $this->load->helper('security');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $this->db->where('usergroup',$_SESSION['usertype']);
        $this->db->where('tableName','StudentMark');
        $this->db->where('allowed','viewstudentmark');
        $uaddMark=$this->db->get('usergrouppermission'); 
        if($this->session->userdata('username') == '' ||  $uaddMark->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='assesmentrank')
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
        $today=date('y-m-d');
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year();
        $data['academicyearFilter']=$this->main_model->academic_year_filter();
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    public function filterGradefromBranch()
    {
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->fetch_grade_from_branch_4statistics($branch,$max_year); 
        }
    }
    function filterSubjectFromSubject(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec',TRUE);
            $max_year=$this->input->post('academicyear',TRUE);
            $gradesec=xss_clean($gradesec);
            $max_year=xss_clean($max_year);
            echo $this->main_model->fetch_grade_from_branch_gs($gradesec,$max_year); 
        } 
    }
    function filter_quarter_fromyear(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        if($this->input->post('academicyear')){
            $max_year=$this->input->post('academicyear',TRUE);
            $max_year=xss_clean($max_year);
            echo $this->main_model->filter_quarter_fromyear($max_year); 
        }
    }
    function filterGradesecfromBranch(){
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear',TRUE);
            $academicyear=xss_clean($academicyear);
            echo $this->main_model->filterGradesecfromBranch($academicyear); 
        }
    }
    function fetch_gradesec_frombranch_markresult(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit',TRUE);
            $max_year=$this->input->post('academicyear',TRUE);
            $branch=xss_clean($branch);
            $max_year=xss_clean($max_year);
            echo $this->main_model->fetch_grade_from_branch($branch,$max_year); 
        }
    }
    function filterSubjectFromSubject_Comment(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec',TRUE);
            $gradesec=xss_clean($gradesec);
            echo $this->main_model->fetch_grade_from_branch_comment($gradesec,$max_year); 
        } 
    }
    function fetch_analysis(){
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
        if($this->input->post('branch')){
          $gradesec=$this->input->post('gradesec',TRUE);
          $branch=$this->input->post('branch',TRUE);
          $quarter=$this->input->post('quarter',TRUE);
          $evaluation=$this->input->post('evaluation',TRUE);
          $gradesec=xss_clean($gradesec);
          $branch=xss_clean($branch);
          $quarter=xss_clean($quarter);
          $evaluation=xss_clean($evaluation);
          if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->fetch_assesmentrank($branch,$gradesec,$quarter,$evaluation,$max_year); 
          }else{
            echo $this->main_model->fetch_assesmentrank($mybranch,$gradesec,$quarter,$evaluation,$max_year); 
          }
        }
    }
    function filter_evaluation4analysis(){
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
                echo $this->main_model->filter_evaluation4analysis_rank($branch,$gradesec,$max_year,$quarter); 
            }else{
                echo $this->main_model->filter_evaluation4analysis_rank($mybranch,$gradesec,$max_year,$quarter); 
            }
        }
    }
    function filter_assesment4analysis(){
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
                echo $this->main_model->filter_assesment4analysisByGrade($branch,$gradesec,$max_year,$quarter); 
            }else{
                echo $this->main_model->filter_assesment4analysisByGrade($mybranch,$gradesec,$max_year,$quarter); 
            }
        }
    }
    function fetch_assesment_analysis(){
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
        if($this->input->post('branch')){
          $gradesec=$this->input->post('gradesec',TRUE);
          $branch=$this->input->post('branch',TRUE);
          $quarter=$this->input->post('quarter',TRUE);
          $evaluation=$this->input->post('evaluation',TRUE);
          $gradesec=xss_clean($gradesec);
          $branch=xss_clean($branch);
          $quarter=xss_clean($quarter);
          $evaluation=xss_clean($evaluation);
          if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->fetch_assesment_analysis_rankbyGrade($branch,$gradesec,$quarter,$evaluation,$max_year); 
          }else{
            echo $this->main_model->fetch_assesment_analysis_rankbyGrade($mybranch,$gradesec,$quarter,$evaluation,$max_year); 
          }
        }
    }
}