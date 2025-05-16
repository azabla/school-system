<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myidcard extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='IDCard' and allowed='StudentIDCard' order by id ASC ");
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
	public function index($page='idcard')
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
        $data['teachers']=$this->main_model->fetchDirectorTeacher($branch);
        if($_SESSION['usertype']===trim('Director')){
          $data['gradesec']=$this->main_model->fetchGradeForSummaryDirector($user,$max_year);
        }else{
          $data['gradesec']=$this->main_model->fetchGradeForSummaryTeacher($user,$max_year);
        }
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('teacher/'.$page,$data);
	}
    function fetchThisGradeStudentIdcard(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            for($i=0;$i<count($grade);$i++){
                $check[]=$grade[$i];
            }
            echo $this->main_model->fetchThisGradeStudentIdcardDirector($check,$max_year,$mybranch); 
        }
    }
    function filterServicePlace(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');

        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetch_servicePlace_branch($mybranch,$max_year);     
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

        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('servicePlace')){
            $studentList=$this->input->post('studentList');
            $gradesec=$this->input->post('gradesec');
            $placeID=$this->input->post('servicePlace');
            for($i=0;$i<count($placeID);$i++){
                $check[]=$placeID[$i];
            }
            for($i=0;$i<count($studentList);$i++){
                $checkStudent[]=$studentList[$i];
            }
            echo $this->main_model->fetch_student_idcard($max_year,$checkStudent,$gradesec,$check,$mybranch,$gyear); 
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

        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('studentList')){
            $gradesec=$this->input->post('gradesec');
            $studentList=$this->input->post('studentList');
            for($i=0;$i<count($studentList);$i++){
                $checkStudent[]=$studentList[$i];
            }
            echo $this->main_model->fetchStudentIdCard($max_year,$checkStudent,$gradesec,$mybranch,$gyear);
        }
    }
    function searchStudentsToTransportService(){
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

        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->main_model->searchStudentsToTransportServiceBranch($searchItem,$max_year,$mybranch); 
        }
    }
    function fetchCustomIDCard(){
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryGYear = $this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowG = $queryGYear->row();
        $gyear=$rowG->gyear;
        if($this->input->post('stuIdArray')){
            $stuIdArray=$this->input->post('stuIdArray');
            for($i=0;$i<count($stuIdArray);$i++){
                $checkStudent[]=$stuIdArray[$i];
            }
            echo $this->main_model->fetchStudentCustomIdCard($max_year,$checkStudent,$gyear);
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
            echo $this->main_model->fetchBackIdCard($pageNumber,$max_year,$gyear);
        }
    } 
}