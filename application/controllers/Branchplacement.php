<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branchplacement extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuPl=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='Studentbp' order by id ASC "); 
        if($this->session->userdata('username') == '' || $uperStuPl->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='branchplacement')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
          show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['grades_subject']=$this->main_model->fetch_subject_grades($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function filter_grade4branchplacement(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade2place')){
            $grade2place=$this->input->post('grade2place');
            echo $this->main_model->fetch_grade_4branch($grade2place,$max_year); 
        }
    }
    function updateBranch(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('section_id')){
            $stu_id=$this->input->post('stu_id');
            $branchName=$this->input->post('section_id');
            $query=$this->main_model->update_student_branch($stu_id,$branchName);
            if($query){
                if($query){
                    $data['notification']='<span class="text-success"><small>Updated.</small></span>';
                }
            }else{
                $data['notification']='<span class="text-danger">oops.Please try again.</span>';
            }
            echo json_encode($data);
        } 
    } 
}