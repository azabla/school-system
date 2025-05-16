<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffdepartment extends CI_Controller {
    public function __construct(){
        parent::__construct();
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userpStaffTP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffGrouping' order by id ASC ");
        if($this->session->userdata('username') == '' || $userpStaffTP->num_rows()<1 || $userLevel!='1'){
            $this->session->set_flashdata("error","Please Login first");
            $this->load->driver('cache');
            delete_cookie('username');
            unset($_SESSION);
            session_destroy();
            $this->cache->clean();
            ob_clean();
            redirect('login/');
        }
        $this->load->model('main_model');        
    }
	public function index($page='staffdepartment')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $userType=$this->session->userdata('usertype');
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['staffs']=$this->main_model->fetchStaffsForPlacement();
        }else{
            $data['staffs']=$this->main_model->fetchMyStaffsForPlacement($branch);
        }
        $this->load->view('home-page/'.$page,$data);
	} 
    function post_placement(){
        $user=$this->session->userdata('username');
        if(isset($_POST['academicyear'])){
            $academicyear=$this->input->post('academicyear');
            $staff_head=$this->input->post('staff');
            $staff_list=$this->input->post('staff_list');
            foreach ($staff_list as $staff_lists) {
                $query=$this->main_model->add_staff_grouping($staff_head,$staff_lists,$academicyear);
                if($query){
                    $data[]=array(
                        'staff_head'=>$staff_head,
                        'academicyear'=>$academicyear,
                        'staff_list'=>$staff_lists,
                        'createdby'=>$user,
                        'date_created'=>date('M-d-Y')
                    );
                }
            }
            $this->db->insert_batch('staff_group',$data);
        }
    }
    function fetch_placement(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $userType=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->fetch_staff_grouping($max_year);
        }else{
            echo $this->main_model->fetch_staff_grouping_branch($max_year,$branch);
        }
    }
    function Delete_staff_group(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staff_list')){
          $staff_list=$this->input->post('staff_list');
          $staff_head=$this->input->post('staff_head');
          $this->main_model->Delete_staff_group($staff_list,$staff_head,$max_year);
        }
    }
    function Delete_staffAll_group(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staffName')){
          $staffName=$this->input->post('staffName');
          $this->main_model->Delete_staffAll_group($staffName,$max_year);
        }
    }

}