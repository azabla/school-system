<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Idcard extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('idcard_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='IDCard' and allowed='StudentIDCard' order by id ASC ");
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
	public function index($page='idcard')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
          show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['sessionuser']=$this->idcard_model->fetch_session_user($user);
        $data['academicyear']=$this->idcard_model->academic_year();
        $data['gradesec']=$this->idcard_model->fetch_gradesec($max_year);
        $data['branch']=$this->idcard_model->fetch_branch($max_year);
        $data['schools']=$this->idcard_model->fetch_school();
        $this->load->view('home-page/'.$page,$data);
	}
    function Filter_grade_from_branch(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $grands_academicyear=$this->input->post('grands_academicyear');
            echo $this->idcard_model->fetchGradeFromBranchTransport($branch,$grands_academicyear); 
        }
    }
    function Filter_grade_from_branch_Back(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->idcard_model->Filter_grade_from_branch_Back($branch,$max_year); 
        }
    }
    function fetchThisGradeStudentIdcard(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $academicyear=$this->input->post('academicyear');
            for($i=0;$i<count($grade);$i++){
                $check[]=$grade[$i];
            }
            echo $this->idcard_model->fetchThisGradeStudentIdcard($check,$academicyear); 
        }
    }
    function filterServicePlace(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $grands_academicyear=$this->input->post('grands_academicyear');
            echo $this->idcard_model->fetch_servicePlace_branch($branch,$grands_academicyear); 
        }
    }
    function fetchStudentIdcard(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryGYear = $this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowG = $queryGYear->row();
        $gyear=$rowG->gyear;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('servicePlace')){
            $studentList=$this->input->post('studentList');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $placeID=$this->input->post('servicePlace');
            $reportaca=$this->input->post('reportacaID');
            for($i=0;$i<count($placeID);$i++){
                $check[]=$placeID[$i];
            }
            for($i=0;$i<count($studentList);$i++){
                $checkStudent[]=$studentList[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->idcard_model->fetch_student_idcard($reportaca,$checkStudent,$gradesec,$check,$branch,$gyear); 
            }else{
                echo $this->idcard_model->fetch_student_idcard($reportaca,$checkStudent,$gradesec,$check,$mybranch,$gyear); 
            } 
        }
    }
    function fetchStudentIdcardWithoutPlace(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryGYear = $this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowG = $queryGYear->row();
        $gyear=$rowG->gyear;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('studentList')){
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportacaID');
            $studentList=$this->input->post('studentList');
            for($i=0;$i<count($studentList);$i++){
                $checkStudent[]=$studentList[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->idcard_model->fetchStudentIdCard($reportaca,$checkStudent,$gradesec,$branch,$gyear);
            }else{
                echo $this->idcard_model->fetchStudentIdCard($reportaca,$checkStudent,$gradesec,$mybranch,$gyear);
            }
        }
    }
    function searchStudentsToTransportService(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->idcard_model->searchStudentsToTransportService($searchItem,$max_year);
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
                echo $this->idcard_model->fetchStudentCustomIdCard($max_year,$checkStudent,$gyear);
            }else{
                echo $this->idcard_model->fetchStudentCustomIdCard($max_year,$checkStudent,$gyear);
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
            $gradesecIDBack=$this->input->post('gradesecIDBack');
            echo $this->idcard_model->fetchBackIdCard($pageNumber,$max_year,$gyear,$branchIDBack,$gradesecIDBack);
        }
    } 
}