<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mystaffidcard extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='IDCard' and allowed='StaffIDCard' order by id ASC ");
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='staffidcard')
	{
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['staffs']=$this->main_model->fetch_staffss($max_year);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('teacher/'.$page,$data);
	} 
    function fetch_staff_idcard(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $myDivision=$row_branch->status2;
        $query = $this->db->query("select max(year_name) as year,gyear from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $queryGyear = $this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowGyear = $queryGyear->row();
        $gyear=$rowGyear->gyear;

        $userType=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];

        /*if($this->input->post('branch')){
            $branch=$this->input->post('branch');*/
           /* if($accessbranch === '1'){
                echo $this->main_model->fetch_staff_idcard($max_year,$branch,$gyear); 
            }else{*/
        echo $this->main_model->fetch_staff_idcardDirector($max_year,$mybranch,$gyear,$myDivision); 
            /*}*/
        /*}*/
    }
}