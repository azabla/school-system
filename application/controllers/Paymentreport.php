<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Paymentreport extends CI_Controller {
    private $per_page=0;
    public function __construct(){
        parent::__construct();
        /*$this->load->library("pagination");*/
        $this->load->model('main_model');
        $this->load->helper('url');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='feemanagment' order by id ASC "); 
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows() < 1 || $userLevel!='1'){
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
	public function index()
	{
        if(!file_exists(APPPATH.'views/home-page/paymentreport.php')){
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        /*$this->pageConfig();
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["links"] = $this->pagination->create_links();*/
        /*$data['payment_report']=$this->main_model->fetch_payment_report($this->per_page,$page,$max_year);*/
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('home-page/paymentreport',$data);
	} 
    /*public function pageConfig(){     
        $config = array();
        $config["base_url"] = base_url() . "paymentreport/index";
        $config["total_rows"] = $this->main_model->getCount_payment_report();
        $config["per_page"] = 10;
         $config["uri_segment"] = 3;
         $config['full_tag_open'] = "<ul class='pagination'>";
         $config['full_tag_close'] = '</ul>';
         $config['num_tag_open'] = '<li>';
         $config['num_tag_close'] = '</li>';
         $config['cur_tag_open'] = '<li class="active"><a href="#">';
         $config['cur_tag_close'] = '</a></li>';
         $config['prev_tag_open'] = '<li>';
         $config['prev_tag_close'] = '</li>';
         $config['first_tag_open'] = '<li>';
         $config['first_tag_close'] = '</li>';
         $config['last_tag_open'] = '<li>';
         $config['last_tag_close'] = '</li>';
         $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
         $config['prev_tag_open'] = '<li>';
         $config['prev_tag_close'] = '</li>';
         $config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
         $config['next_tag_open'] = '<li>';
         $config['next_tag_close'] = '</li>';
         $this->per_page=$config["per_page"]; 
         $this->pagination->initialize($config);        
    }*/
    function Filter_grade_from_branch(){
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $grands_academicyear=$this->input->post('grands_academicyear');
            echo $this->main_model->fetch_grade_from_branch($branch,$grands_academicyear); 
        }
    }
    function filterGradesecfromBranch(){
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->filterGradesecfromBranch($academicyear); 
        }
    }
    function fecth_thistudent_report(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        if($this->input->post('gs_branches')){
            $gs_branches=$this->input->post('gs_branches');
            $gs_gradesec=$this->input->post('gs_gradesec');
            $grands_academicyear=$this->input->post('grands_academicyear');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_payment_report($gs_branches,$gs_gradesec,$grands_academicyear);
            }else{
                echo $this->main_model->fetch_payment_report($branch,$gs_gradesec,$grands_academicyear);
            }
        } 
    }
}