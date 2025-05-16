<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mystaffplacement extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('teacher_model'); 
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userpStaffTP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffPl' order by id ASC "); 
        if($this->session->userdata('username') == '' || $userpStaffTP->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='placement')
	{
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['gradesec']=$this->teacher_model->fetch_mygradesec($user,$max_year,$branch);
        $data['sessionuser']=$this->teacher_model->fetch_session_user($user);
        $data['academicyear']=$this->teacher_model->academic_year_filter();
        $data['schools']=$this->teacher_model->fetch_school();
        $data['staffs']=$this->teacher_model->fetchMyStaffsForPlacement($branch);
        $data['subjects']=$this->teacher_model->fetch_subject_toplace($user,$max_year,$branch);
        $this->load->view('teacher/'.$page,$data);
	} 
    function post_placement(){
        if(isset($_POST['grade'])){
            $grade = $this->input->post('grade');
            $academicyear=$this->input->post('academicyear');
            $staff=$this->input->post('staff');
            $subject=$this->input->post('subject');
            foreach ($grade as $grades) {
                foreach ($subject as $subjects) {
                    $checkSubject=$this->teacher_model->checkSubject($subjects,$grades,$academicyear);
                    if($checkSubject){
                        $query=$this->teacher_model->add_placement($staff,$subjects,$grades,$academicyear);
                        if($query){
                            $data[]=array(
                                'staff'=>$staff,
                                'grade'=>$grades,
                                'academicyear'=>$academicyear,
                                'subject'=>$subjects,
                                'date_created'=>date('M-d-Y')
                            );
                        }
                    }
                }
            }
            $this->db->insert_batch('staffplacement',$data);
        }
    }
    function fetch_placement(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query=$this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryChk=$this->db->query("SELECT dp.staff from directorplacement as dp  where dp.staff='$user' and dp.academicyear ='$max_year' group by grade ");
        if($queryChk->num_rows()>0){
            echo $this->teacher_model->fetch_mystaff_placement($max_year,$branch);
        }else{
            echo '<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
                <i class="fas fa-exclamation-circle"> </i> You are not assigned to see teacher placement. Please contact your System Administrator.
            </div></div>';
        }
    }
    function deleteStaffplacement(){
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
                'useraction'=>'Teacher placement deleted',
                'infograde'=>'-',
                'subject'=>'-',
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
                $this->teacher_model->delete_placement($staff_placement,$max_year);
            }
        }
    }
    function Delete_staffCustomplacement(){
        $data1=array();
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('UTC');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $datetried = gmdate("Y-m-d h:i A", $dt->format('U'));
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staffGrade')){
            $staffGrade=$this->input->post('staffGrade');
            $staffSubject=$this->input->post('staffSubject');
            $staffName=$this->input->post('staffName');
            $querySelect=$this->db->query("select * from users where username='$staffName' ");
            $rowName=$querySelect->row();
            $fname=$rowName->fname;
            $mname=$rowName->mname;
            $lname=$rowName->lname;
            $data1=array(
                'userinfo'=>$user,
                'useraction'=>'Teacher placement deleted',
                'infograde'=>$staffGrade,
                'subject'=>$staffSubject,
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
                $this->teacher_model->delete_Customplacement($staffGrade,$staffSubject,$staffName);
            }
        }
    }

}