<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lockunlockmystudentmark extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('teacher_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' order by id ASC ");  
        if($this->session->userdata('username') == '' || $uperStuDE->num_rows() < 1 || $userLevel!='2'){
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
	public function index($page='lockunlockstudentmark')
	{
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_teacher=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $data['fetch_term']=$this->teacher_model->fetch_term_4teacheer($max_year);
        $data['sessionuser']=$this->teacher_model->fetch_session_user($user);
        $data['academicyear']=$this->teacher_model->academic_year_filter();
        if($_SESSION['usertype']===trim('Director')){
          $data['gradesec']=$this->teacher_model->fetch_grade_from_staffplace($user,$max_year);
        }else{
          $data['gradesecTeacher']=$this->teacher_model->fetch_session_gradesec($user,$max_year);
        }
        $data['schools']=$this->teacher_model->fetch_school();
        $this->load->view('teacher/'.$page,$data);
	}
    function lockThisSectionMark(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            for($j=0;$j<count($gradesec);$j++){
                $checkGradesec[]=$gradesec[$j];
            }
            echo $this->teacher_model->lockThisSectionMark($branch,$checkGradesec,$max_year); 
        }
    }
     function UnlockThisSectionMark(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            for($j=0;$j<count($gradesec);$j++){
                $checkGradesec[]=$gradesec[$j];
            }
            echo $this->teacher_model->UnlockThisSectionMark($branch,$checkGradesec,$max_year); 
        }
    }
    function searchStudent(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->teacher_model->searchAdminStudentsToLockMark($searchItem,$branch,$max_year);
        }
    }
    function lockThisStudentMark(){
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('stuID')){
            $stuID=$this->input->post('stuID');
            $queryUsers=$this->db->query("select gradesec,branch from users where academicyear='$max_year' and id='$stuID' ");
            if($queryUsers->num_rows()>0){
                foreach($queryUsers->result() as $gradesecName){
                    $gradesec=$gradesecName->gradesec;
                    $branch=$gradesecName->branch;
          
                    $queryTerm=$this->db->query("select term from quarter where Academic_year='$max_year' group by term ");
                    if($queryTerm->num_rows()>0){
                        foreach($queryTerm->result() as $termName){
                            $max_quarter=$termName->term;
                            $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branch.$gradesec.$max_quarter.$max_year."' ");
                            if ($queryCheckMark->num_rows()>0)
                            {
                                $this->db->where('stuid',$stuID);
                                $this->db->set('lockmark','1');
                                $queryUpdate=$this->db->update('mark'.$branch.$gradesec.$max_quarter.$max_year);
                            }
                        }
                    }
                }
            }
        }
    }
    function unlockThisStudentMark(){
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('stuID')){
            $stuID=$this->input->post('stuID');
            $queryUsers=$this->db->query("select gradesec,branch from users where academicyear='$max_year' and id='$stuID' ");
            if($queryUsers->num_rows()>0){
                foreach($queryUsers->result() as $gradesecName){
                    $gradesec=$gradesecName->gradesec;
                    $branch=$gradesecName->branch;
          
                    $queryTerm=$this->db->query("select term from quarter where Academic_year='$max_year' group by term ");
                    if($queryTerm->num_rows()>0){
                        foreach($queryTerm->result() as $termName){
                            $max_quarter=$termName->term;
                            $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branch.$gradesec.$max_quarter.$max_year."' ");
                            if ($queryCheckMark->num_rows()>0)
                            {
                                $this->db->where('stuid',$stuID);
                                $this->db->set('lockmark','0');
                                $queryUpdate=$this->db->update('mark'.$branch.$gradesec.$max_quarter.$max_year);
                            }
                        }
                    }
                }
            }
        }
    }
}
