<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Placement extends CI_Controller {
    public function __construct(){
        parent::__construct();
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userpStaffTP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffPl' order by id ASC ");
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
	public function index($page='placement')
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
            $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        }else{
            $data['gradesec']=$this->main_model->fetch_mygradesec($max_year,$branch);
        }
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['staffs']=$this->main_model->fetchStaffsForPlacement();
        }else{
            $data['staffs']=$this->main_model->fetchMyStaffsForPlacement($branch);
        }
        $data['subjects']=$this->main_model->fetch_subject_toplace($max_year);
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	} 
    function post_placement(){
        $data=array();
        $dataPlacement=array();
        $dataSubject=array();
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('id')){
            $grade = $this->input->post('id');
            $staff=$this->input->post('staff');
            $subject=$this->input->post('subject');
            foreach ($grade as $grades) {
                foreach ($subject as $subjects) {
                    $checkSubject=$this->main_model->checkSubject($subjects,$grades,$max_year);
                    if($checkSubject){
                        $query=$this->main_model->add_placement($staff,$subjects,$grades,$max_year);
                        if($query){
                            $data[]=array(
                                'staff'=>$staff,
                                'grade'=>$grades,
                                'academicyear'=>$max_year,
                                'subject'=>$subjects,
                                'date_created'=>date('M-d-Y')
                            );
                        }else{
                            $dataPlacement[]=array(
                                'subject'=>$subjects,
                                'grade'=>$grades
                            );
                        }
                    }else{
                        $dataSubject[]=array(
                            'subject'=>$subjects,
                            'grade'=>$grades
                        ); 
                    }
                }
            }
            if(!empty($data)){
                $query1=$this->db->insert_batch('staffplacement',$data);
                if(!empty($dataSubject) && !empty($dataPlacement)){
                    $msgCheckSubject='<p>'.implode(', ', array_map(function ($entry) { return $entry['subject']. ' '. $entry['grade'];  
                        }, $dataSubject)).'</p>';
                    $result['not_Created']=$msgCheckSubject;
                    $msgCheckPlacement='<p>'.implode(', ', array_map(function ($entry) { return $entry['subject']. ' '. $entry['grade'];;
                    }, $dataPlacement)).'</p>';
                    $result['pfound']=$msgCheckPlacement;
                }else if(!empty($dataSubject)){
                    $msgCheckSubject='<p>'.implode(', ', array_map(function ($entry) { return $entry['subject']. ' '. $entry['grade'];  
                        }, $dataSubject)).'</p>';
                    $result['not_Created']=$msgCheckSubject;
                }elseif(!empty($dataPlacement)){
                    $msgCheckPlacement='<p>'.implode(', ', array_map(function ($entry) { return $entry['subject']. ' '. $entry['grade'];;
                    }, $dataPlacement)).'</p>';
                    $result['pfound']=$msgCheckPlacement;
                }else{
                    if($query1){
                        $successPlacement='success';
                        $result['msg']=$successPlacement;
                    }else{
                        $successPlacement='error';
                        $result['msg']=$successPlacement;
                    } 
                }
            }else if(!empty($dataSubject) && !empty($dataPlacement)){
                $msgCheckSubject='<p>'.implode(', ', array_map(function ($entry) { return $entry['subject']. ' '. $entry['grade'];  
                        }, $dataSubject)).'</p>';
                $result['not_Created']=$msgCheckSubject;
                $msgCheckPlacement='<p>'.implode(', ', array_map(function ($entry) { return $entry['subject']. ' '. $entry['grade'];;
                }, $dataPlacement)).'</p>';
                $result['pfound']=$msgCheckPlacement;
            }elseif(!empty($dataPlacement) && empty($dataSubject)){
                $msgCheckPlacement='<p>'.implode(', ', array_map(function ($entry) { return $entry['subject']. ' '. $entry['grade'];;
                    }, $dataPlacement)).'</p>';
                $result['pfound']=$msgCheckPlacement;
            }elseif(empty($dataPlacement) && !empty($dataSubject)){
                $msgCheckSubject='<p>'.implode(', ', array_map(function ($entry) { return $entry['subject']. ' '. $entry['grade'];  
                        }, $dataSubject)).'</p>';
                $result['not_Created']=$msgCheckSubject;
            }else{
                $msgCheckSubject='<p>ooops Please try later</p>';
                $result['not_Created']=$msgCheckSubject;
            }
            echo json_encode($result);
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
            echo $this->main_model->fetch_staff_placement($max_year);
        }else{
            echo $this->main_model->fetch_mystaff_placement($max_year,$branch);
        }
    }
    function Delete_staffplacement(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staffGrade')){
            $staffGrade=$this->input->post('staffGrade');
            $staffSubject=$this->input->post('staffSubject');
            $staffName=$this->input->post('staffName');
            $data1=array();
            date_default_timezone_set("Africa/Addis_Ababa");
            $dtz = new DateTimeZone('UTC');
            $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
            $datetried = gmdate("Y-m-d h:i A", $dt->format('U'));
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
                $this->main_model->delete_placement($staffGrade,$staffSubject,$staffName);
            }
        }
    }
    function Delete_staffAllplacement(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $user=$this->session->userdata('username');
        if($this->input->post('staffName')){
            $staffName=$this->input->post('staffName');
            $data1=array();
            date_default_timezone_set("Africa/Addis_Ababa");
            $dtz = new DateTimeZone('UTC');
            $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
            $datetried = gmdate("Y-m-d h:i A", $dt->format('U'));
            $querySelect=$this->db->query("select * from users where username='$staffName' ");
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
                $this->main_model->Delete_staffAllplacement($staffName,$max_year);
            }
        }
    }
    function searchTeacherPlacementStaffs(){
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
                echo $this->main_model->searchTeacherPlacementStaffs($searchItem,$max_year);
            }else{
                echo $this->main_model->searchTeacherPlacementStaffsAdmin($searchItem,$max_year,$branch);
            }
        }
    }
    function postRemotePlacement(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['grade'])){
            $grade = $this->input->post('grade');
            $branchRemote=$this->input->post('branchRemote');
            $staff=$this->input->post('staff');
            $subject=$this->input->post('subject');
            foreach ($grade as $grades) {
                foreach ($subject as $subjects) {
                    $checkSubject=$this->main_model->checkSubject_remotePlacement($subjects,$grades,$max_year);
                    if($checkSubject){
                        $query=$this->main_model->add_RemotePlacement($staff,$subjects,$grades,$max_year,$branchRemote);
                        if($query){
                            $data[]=array(
                                'staff'=>$staff,
                                'remotebranch'=>$branchRemote,
                                'grade'=>$grades,
                                'academicyear'=>$max_year,
                                'subject'=>$subjects,
                                'date_created'=>date('M-d-Y')
                            );
                        }
                    }
                }
            }
            $this->db->insert_batch('staffremoteplacement',$data);
        }
    }
    function fetch_remote_placement(){
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
            echo $this->main_model->fetch_staff_remote_placement($max_year);
        }else{
            echo $this->main_model->fetch_my_remotestaff_placement($max_year,$branch);
        }
    }
    function Delete_remote_staffplacement(){
        if($this->input->post('staffGrade')){
          $staffGrade=$this->input->post('staffGrade');
          $staffSubject=$this->input->post('staffSubject');
          $staffName=$this->input->post('staffName');
          $staff_Branch=$this->input->post('staff_branch');
          $this->main_model->delete_remote_placement($staffGrade,$staffSubject,$staffName,$staff_Branch);
        }
    }
    function Delete_remote_staffAllplacement(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staffName')){
          $staffName=$this->input->post('staffName');
          $this->main_model->Delete_remote_staffAllplacement($staffName,$max_year);
        }
    }
    function filterSubject4Evaluation(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade2analysis')){
            $grade2analysis=$this->input->post('grade2analysis');
            for($i=0;$i<count($grade2analysis);$i++){
                $gradeGsanalysis[]=$grade2analysis[$i];
            }
            echo $this->main_model->filterSubject4Evaluation($gradeGsanalysis,$max_year);   
        }
    }

}