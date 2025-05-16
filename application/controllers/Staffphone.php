<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffphone extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userpStaffDe=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffPhone' order by id ASC "); 
        if($this->session->userdata('username') == '' || $userpStaffDe->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='staffphone')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch =$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('home-page/'.$page,$data);
	} 
    function fetchstaffPhoneList(){
        $user=$this->session->userdata('username');
        $query_branch =$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$row_branch->usertype;
        $myDivision=$row_branch->status2;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $accessbranch = sessionUseraccessbranch();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->fetchstaffPhoneListAll($max_year);
        }else{
           echo $this->main_model->fetchstaffPhoneList($branch,$myDivision,$max_year); 
        }
    }
}