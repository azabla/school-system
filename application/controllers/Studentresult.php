<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Studentresult extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('teacher_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='viewstudentmark' order by id ASC ");  
        if($this->session->userdata('username') == '' || $uaddMark->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='mark-result')
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
    
    $data['fetch_term']=$this->teacher_model->fetch_term($max_year);
    $data['sessionuser']=$this->teacher_model->fetch_session_user($user);
    $data['academicyear']=$this->teacher_model->academic_year_filter();
    /*if($_SESSION['usertype']===trim('Director')){*/
      $data['gradesec']=$this->teacher_model->fetch_grade_from_staffplace($user,$max_year);
    /*}else{
      $data['gradesecTeacher']=$this->teacher_model->fetch_session_gradesec($user,$max_year);
    }*/
    $data['schools']=$this->teacher_model->fetch_school();
    $data['branch']=$this->teacher_model->fetch_branch($max_year);
    $this->load->view('teacher/'.$page,$data);
	}
  function fecth_mark_result_comment(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select branch from users where username='$user' ");
    $row_branch = $query_branch->row_array();
    $mybranch=$row_branch['branch'];
    if($this->input->GET('gs_gradesec')){
      $gs_gradesec=$this->input->GET('gs_gradesec');
      $gs_subject=$this->input->GET('gs_subject');
      $gs_quarter=$this->input->GET('gs_quarter');
      $this->db->where('academicyear',$max_year);
      $queryCheck = $this->db->get('enableapprovemark');
      if($queryCheck->num_rows()>0){
        $show=$this->teacher_model->fetch_grade_markresult_comment($mybranch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
        echo $show;
      }else{
        $show=$this->teacher_model->fetch_grade_markresult_comment($mybranch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
        echo $show;
      }
    }
  }
  function save_teacher_comment(){
    $user=$this->session->userdata('username');
    if($this->input->post('stuid')){
      $stuid=$this->input->post('stuid');
      $commentvalue=$this->input->post('commentvalue');
      $academicyear=$this->input->post('academicyear');
      $subject=$this->input->post('subject');
      $quarter=$this->input->post('quarter');
      $markGradeSec=$this->input->post('markGradeSec');
      $markGradeSecBranch=$this->input->post('markGradeSecBranch');
      for ($i=0; $i < count($stuid); $i++) { 
        $data=array();
        $id=$stuid[$i];
        $commentvalues=$commentvalue[$i];
        $queryChk=$this->teacher_model->save_this_grade_teacher_comment($id,$academicyear,$subject,$quarter);
        $data=array(
          'stuid'=>$id,
          'academicyear'=>$academicyear,
          'subject'=>$subject,
          'quarter'=>$quarter,
          'resultcomment'=>$commentvalues,
          'datecreated'=>date('M-d-Y'),
          'createdby'=>$user
        );
        if($queryChk){
          $query=$this->db->insert('manualreportcardcomments',$data);
        }else{
          $this->db->where('stuid',$id);
          $this->db->where('academicyear',$academicyear);
          $this->db->where('subject',$subject);
          $this->db->where('quarter',$quarter);
          $this->db->set('resultcomment',$commentvalues);
          $query=$this->db->update('manualreportcardcomments',$data);
        }
      }
      if($query){
        echo '<div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            <i class="fas fa-exclamation-circle"> </i> Comment saved successfully.
        </div></div>';
      }else{
        echo '<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            <i class="fas fa-exclamation-circle"> </i> Please Try Again.
        </div></div>';
      }
    }
  }
  function fetchSubjectforMarkView(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('gradesec')){
      $gradesec=$this->input->post('gradesec');
      $queryChk = $this->db->select('*')
      ->where('staff', $user)
      ->where('academicyear',$max_year)
      ->get('directorplacement');
      if($queryChk->num_rows()>0){
        echo $this->teacher_model->fetch_subject_from_subjectmark($gradesec,$max_year); 
      }else{
        echo $this->teacher_model->fetch_subject_from_staffplace($gradesec,$max_year,$user);
      }
    }
  }
  function fecth_teacher_markresult(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('gs_gradesec')){
      $gs_gradesec=$this->input->post('gs_gradesec');
      $gs_subject=$this->input->post('gs_subject');
      $gs_quarter=$this->input->post('gs_quarter');
      $this->db->where('academicyear',$max_year);
      $queryCheck = $this->db->get('enableapprovemark');
      if($queryCheck->num_rows()>0){
        echo $this->teacher_model->fetch_grade_teachermarkresultApproved($mybranch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
      }else{
        echo $this->teacher_model->fetch_grade_teachermarkresult($mybranch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
      }
    }
  }
  function Filtersubjectfromstaff(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('gradesec')){
      $gradesec=$this->input->post('gradesec');
       $queryChk = $this->db->select('*')
                ->where('staff', $user)
                ->where('academicyear',$max_year)
                ->get('directorplacement');
      if($_SESSION['usertype']===trim('Director') && $queryChk->num_rows()>0 ){
        echo $this->teacher_model->fetch_subject_from_subject($gradesec,$max_year);
      }else{
        echo $this->teacher_model->fetch_subject_from_staffplace($gradesec,$max_year,$user);
      }
    } 
  }
}