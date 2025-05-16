<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homeroomplacement extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userpStaffHrP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='hoomeroomPl' order by id ASC ");
        if($this->session->userdata('username') == '' || $userpStaffHrP->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='homeroomplacement')
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
        $accessbranch = sessionUseraccessbranch();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        }else{
            $data['gradesec']=$this->main_model->fetch_mygradesec($max_year,$branch);
        }
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function postHomeroomPlacement(){
        if(isset($_POST['id'])){
            $id = $this->input->post('id');
            $academicyear=$this->input->post('academicyear');
            $teacher=$this->input->post('teacher');
            $branch=$this->input->post('branch');
            foreach ($id as $checkbox) {
                $query=$this->main_model->addHomeRoomPlacement($teacher,$branch,$checkbox,$academicyear);
                if($query){
                    $data=array(
                        'teacher'=>$teacher,
                        'roomgrade'=>$checkbox,
                        'academicyear'=>$academicyear,
                        'branch'=>$branch,
                        'date_created'=>date('M-d-Y')
                    );
                    $this->db->insert('hoomroomplacement',$data);
                }
            }
        }
    }
    function fetchHomeroomPlacement(){
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
            echo $this->main_model->fetchHomeRoomplacement($max_year);
        }else{
            echo $this->main_model->fetchMyHomeRoomplacement($max_year,$branch);
        }
    }
    function deleteHomeroomPlacement(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data1=array();
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('UTC');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $datetried = gmdate("Y-m-d h:i A", $dt->format('U'));
        $user=$this->session->userdata('username');
        if($this->input->post('staff_placement')){
            $staff_placement=$this->input->post('staff_placement');
            $querySelect=$this->db->query("select fname,mname,lname from users where username='$staff_placement' ");
            $rowName=$querySelect->row();
            $fname=$rowName->fname;
            $mname=$rowName->mname;
            $lname=$rowName->lname;
            $data1=array(
                'userinfo'=>$user,
                'useraction'=>'H.room placement deleted',
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
            if($queryInsert){
                echo $this->main_model->deleteHomeRoomplacement($staff_placement,$max_year);
            }
        }
    }
    function searchHroomStaffs(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $searchItem=$this->input->post('searchItem');
        $userType=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('searchItem')){
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->searchHroomStaffs($searchItem,$max_year);
            }else{
                echo $this->main_model->searchHroomStaffsAdmin($searchItem,$max_year,$branch);
            }
        }
    } 
}
