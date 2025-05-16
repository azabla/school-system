<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffidcard extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='IDCard' and allowed='StaffIDCard' order by id ASC ");  
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='1'){
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
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
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
        $this->load->view('home-page/'.$page,$data);
	} 
    function fetch_staff_idcard(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;

        $userType=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];

        if($this->input->post('branch')){
            $branch=$this->input->post('branch');
            $max_year=$this->input->post('reportaca');
            $queryGyear = $this->db->query("select gyear from academicyear where year_name='$max_year' ");
            $rowGyear = $queryGyear->row();
            $gyear=$rowGyear->gyear;
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_staff_idcard($max_year,$branch,$gyear); 
            }else{
                echo $this->main_model->fetch_staff_idcard($max_year,$mybranch,$gyear); 
            }
        }
    }
    function searchStudentsToTransportService(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->main_model->searchStudentsToTransportService_staffs($searchItem,$max_year);
        }
    }
    function fetchCustomIDCard(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryGYear = $this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowG = $queryGYear->row();
        $gyear=$rowG->gyear;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('stuIdArray')){
            $stuIdArray=$this->input->post('stuIdArray');
            for($i=0;$i<count($stuIdArray);$i++){
                $checkStudent[]=$stuIdArray[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchStaffsCustomIdCard($max_year,$checkStudent,$gyear);
            }else{
                echo $this->main_model->fetchStaffsCustomIdCard($max_year,$checkStudent,$gyear);
            }
        } 
    }
    public function fetchBackIdCard(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryGYear = $this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowG = $queryGYear->row();
        $gyear=$rowG->gyear;
        if($this->input->post('pageNumber')){
            $pageNumber=$this->input->post('pageNumber');
            $branchIDBack=$this->input->post('branchIDBack');
            echo $this->main_model->fetchBackStaffsIdCard($pageNumber,$max_year,$gyear,$branchIDBack);
        }
    }
}