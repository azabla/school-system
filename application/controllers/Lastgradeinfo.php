<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lastgradeinfo extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuView=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentVE' order by id ASC ");
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
    public function index($page='lastgradeinfo')
    {
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['posts']=$this->main_model->fetch_post();
        $this->load->view('home-page/'.$page,$data);
    }
    function Filter_grade_from_branch(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->fetch_grade_from_branch_history($branch,$max_year); 
        }
    }
    function fetch_history(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $branch=$this->input->post('branch');
            if(trim($_SESSION['usertype'])===trim('superAdmin')){
                echo $this->main_model->fetch_history($branch,$grade,$max_year); 
            }else{
                echo $this->main_model->fetch_history($mybranch,$grade,$max_year); 
            }
        }
    } 
}