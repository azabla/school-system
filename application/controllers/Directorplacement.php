<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Directorplacement extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userpStaffDP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='directorPl' order by id ASC ");
        if($this->session->userdata('username') == '' || $userpStaffDP->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='directorplacement')
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

        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['gradesec']=$this->main_model->fetch_grade($max_year);
        }else{
            $data['gradesec']=$this->main_model->fetch_grade4NonsuperAdmin($max_year,$branch);
        }
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['staffs']=$this->main_model->fetchDirectorForPlacement();
        }else{
            $data['staffs']=$this->main_model->fetchMyDirectorForPlacement($branch);
        }
        $data['subjects']=$this->main_model->fetch_subject_toplace($max_year);
        $this->load->view('home-page/'.$page,$data);
	} 
    function post_placement(){
        if(isset($_POST['id'])){
            $id = $this->input->post('id');
            $academicyear=$this->input->post('academicyear');
            $staff=$this->input->post('staff');
            $staffBranch=$this->db->query("select branch from users where username='$staff'");
            $rowBranch = $staffBranch->row();
            $branchs=$rowBranch->branch;
            foreach ($id as $checkbox) {
                $queryFetchSec=$this->main_model->fetchGradeSection($checkbox,$academicyear,$branchs);
                foreach ($queryFetchSec as $sec) {
                    $section=$sec->gradesec;
                    $query=$this->main_model->add_Directorplacement($staff,$section,$academicyear);
                    if($query){
                        $data[]=array(
                            'staff'=>$staff,
                            'grade'=>$section,
                            'academicyear'=>$academicyear,
                            'date_created'=>date('M-d-Y')
                        );
                    }
                }
            }
            $this->db->insert_batch('directorplacement',$data);
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
        echo $this->main_model->fetch_director_placement($max_year);
    }
    function deletePlacement(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data1=array();
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('UTC');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $datetried = gmdate("Y-m-d h:i A", $dt->format('U'));
        if($this->input->post('staff_placement')){
            $staff_placement=$this->input->post('staff_placement');
            $querySelect=$this->db->query("select * from users where username='$staff_placement' ");
            $rowName=$querySelect->row();
            $fname=$rowName->fname;
            $mname=$rowName->mname;
            $lname=$rowName->lname;
            $data1=array(
                'userinfo'=>$user,
                'useraction'=>'Director placement deleted',
                'infograde'=>'',
                'subject'=>'',
                'quarter'=>'',
                'academicyear'=>$max_year,
                'oldata'=>'',
                'newdata'=>'',
                'updateduser'=>''.$fname.' '.$mname.' '.$lname,
                'userbranch'=>'',
                'actiondate'=> $datetried
            );
            $queryInsert=$this->db->insert('useractions',$data1);
            $this->main_model->delete_Directorplacement($staff_placement,$max_year);
        }
    }
}