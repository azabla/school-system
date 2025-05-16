<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exportmanualmarkformat extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $markformat=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='studentmarkformat' order by id ASC ");
        if($this->session->userdata('username') == '' ||  $markformat->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='exportmanualmarkformat')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function filterOnlyGradeFromBranch(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->fetchOnlyGradeFromBranch($branch,$max_year); 
        }
    }
    function fecthMarkresult(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->POST('gs_gradesec')){
            $gs_branches=$this->input->POST('gs_branches');
            $gs_gradesec=$this->input->POST('gs_gradesec');
            $gs_quarter=$this->input->POST('gs_quarter');
            if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
                $show=$this->main_model->fetchGradeManualMarkFormate($gs_branches,$gs_gradesec,$gs_quarter,$max_year); 
                echo $show;
                
            }else{
                
                $show=$this->main_model->fetchGradeManualMarkFormate($branch,$gs_gradesec,$gs_quarter,$max_year); 
                echo $show; 
            }
        }
    } 
}