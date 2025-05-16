<?php
defined('BASEPATH') OR exit('No direct script access allowed');

  class Approvestudentmark extends CI_Controller {
    public function __construct(){
      parent::__construct();
      $this->load->model('teacher_model');
      ob_start();
      $this->load->helper('cookie');
      $userLevel = userLevel();
      $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='approvemark' order by id ASC "); 
      if($this->session->userdata('username') == '' || $uaddMark->num_rows()< 1 || $userLevel!='2'){
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
	public function index($page='approvestudentmark')
	{
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    
    $data['fetch_term']=$this->teacher_model->fetch_term($max_year);
    $data['fetch_evaluation']=$this->teacher_model->fetch_evaluation_fornewexam($max_year);
    $data['sessionuser']=$this->teacher_model->fetch_session_user($user);
    $data['academicyear']=$this->teacher_model->academic_year_filter();
    $data['fetch_grade_fromsp_toadd_neweaxm']=$this->teacher_model->fetch_grade_from_staffplace($user,$max_year);
    $data['schools']=$this->teacher_model->fetch_school();
    $this->load->view('teacher/'.$page,$data);
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
  function Fecth_grademark_4teacher(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    if($this->input->post('gs_gradesec')){
      $gs_gradesec=$this->input->post('gs_gradesec');
      $gs_subject=$this->input->post('gs_subject');
      $gs_quarter=$this->input->post('gs_quarter');
      echo $this->teacher_model->approveMark($branch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
    }
  }
  function approveMarkName(){
    $user=$this->session->userdata('username');
    $query=$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $year=$this->input->post('year');
      $markname=$this->input->post('markname');
      $this->db->where('mgrade',$gradesec);
      $this->db->where('academicyear',$year);
      $this->db->where('subname',$subject);
      $this->db->where('markname',$markname);
      $this->db->where('quarter',$quarter);
      $this->db->where('mbranch',$branch_me);
      $this->db->set('approved','1');
      $this->db->update('mark'.$branch_me.$gradesec.$quarter.$year);
      echo $this->teacher_model->approveMark($branch_me,$gradesec,$subject,$quarter,$max_year);  
    }
  }
}