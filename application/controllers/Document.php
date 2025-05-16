<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    if($this->session->userdata('username') == '' || $userLevel!='2'){
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
	public function index($page='documents')
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
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('teacher/'.$page,$data);
	}
  function postdocuments(){
    $user=$this->session->userdata('username');
    $config['upload_path'] = './mydocument/';
    $config['allowed_types'] ='csv|xlsx|docx|pdf';
    $config['encrpt_name']=TRUE;
    $this->load->library('upload', $config);
    if($this->upload->do_upload('mydoc')){
      $filename= $this->upload->data('file_name');
      $data=array(
        'filename'=>$filename,
        'fileuser'=>$user,
        'datecreated'=>date('M-d-Y')
      );
      $this->main_model->insertdocument($data);
    }    
  }
  function fetchdocuments(){
    $user=$this->session->userdata('username');
    echo $this->main_model->fetchdocuments($user);
  } 
  function Deletedocuments(){
    if($this->input->post('id')){
      $id=$this->input->post('id');
      $this->main_model->deletedocuments($id);
    }
  }
  function fetch_my_tasks_report(){
    $user=$this->session->userdata('username');
    $userType=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $queryReportTo=$this->db->query("select user_hierarchy,uname from usegroup where uname='$userType' ");
    if($queryReportTo->num_rows()>0){
      $rowID=$queryReportTo->row();
      $user_hierarchys=$rowID->user_hierarchy;
    }else{
      $user_hierarchys='10000';
    }
    $queryCheckGroup=$this->db->query("select department_name from staff_group where staff_list='$user' or staff_head='$user' ");
    if($queryCheckGroup->num_rows()>0){
      $rowD = $queryCheckGroup->row();
      $department=$rowD->department_name;
    }else{
      $department='Unknown';
    }
    echo $this->main_model->fetch_my_tasks_report($max_year,$user_hierarchys,$user,$department);
  }
  function new_submit_report_page(){
    $user=$this->session->userdata('username');
    $userType=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->main_model->new_submit_report_page($max_year,$userType,$user);
  }
  function submit_this_newReport(){
    $user=$this->session->userdata('username');
    $userType=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $data=array();
    if($this->input->post('my_Report_Name')){
      $config['upload_path'] = './report_file/';
      $config['allowed_types'] ='docx|pdf|xlsx|xls|csv';
      $this->load->library('upload', $config);
      $this->upload->do_upload('importReportFile');
      $reportFile= $this->upload->data('file_name');
      $my_Report_Name=trim($this->input->post('my_Report_Name'));
      $my_Report_schedule=$this->input->post('my_Report_schedule');
      $my_Report_detail=trim($this->input->post('my_Report_detail'));
      $Reportdate=date('M-d-Y');
      $this->db->group_by('taskName');
      $this->db->where('staff_name',$user);
      $this->db->where('taskName',$my_Report_Name);
      $query = $this->db->get('stafftasks_report');
      if($query->num_rows()>0){
        echo '3';
      }else{
        $queryReportTo=$this->db->query("select user_hierarchy,uname from usegroup where uname='$userType' ");
        if($queryReportTo->num_rows()>0){
          $rowID=$queryReportTo->row();
          $user_hierarchys=$rowID->user_hierarchy;
        }else{
          echo '5';
        }
        $queryCheckGroup=$this->db->query("select department_name from staff_group where staff_list='$user'  ");
        if($queryCheckGroup->num_rows()>0){
          $rowD = $queryCheckGroup->row();
          $department=$rowD->department_name;
        }else{
          $department='';
        }
        $data=array(
          'taskName'=>$my_Report_Name,
          'task_response'=>$my_Report_detail,
          'file_report'=>$reportFile,
          'report_schedule'=>$my_Report_schedule,
          'staff_name'=>$user,
          'report_to'=>$user_hierarchys,
          'to_department'=>$department,
          'date_submitted'=>$Reportdate,
          'academicyear'=>$max_year
        );
        $query=$this->db->insert('stafftasks_report',$data);
        if($query){
          echo '1';
        }else{
          echo '2';
        }
      }
    } 
  }
  function fetch_this_report_toedt(){
    $user=$this->session->userdata('username');
    $userType=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('rid')){
      $rid=$this->input->post('rid');
      echo $this->main_model->fetch_this_report_toedt($rid,$max_year,$userType,$user);
    }
  }
  function submit_updated_this_newReport(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $data=array();
    if($this->input->post('my_Report_Name_update')){
      $my_Report_Name=trim($this->input->post('my_Report_Name_update'));
      $my_Report_schedule=$this->input->post('my_Report_schedule_update');
      $my_Report_detail=trim($this->input->post('my_Report_detail_update'));
      $rid=$this->input->post('id_my_updated_report');
      $oldTaskName=$this->input->post('id_my_updated_reportTitle');
      $oldTaskSchedule=$this->input->post('id_my_updated_reportSchedule');
      $oldTaskDetail=$this->input->post('id_my_updated_reportDetail');
      $oldTaskReportedFile=$this->input->post('id_my_updated_reportedFile');
      $Reportdate=date('M-d-Y');
      $config['upload_path'] = './report_file/';
      $config['allowed_types'] ='docx|pdf|xlsx|xls|csv';
      $this->load->library('upload', $config);
      $this->upload->do_upload('importReportFile_update');
      $reportFile= $this->upload->data('file_name');
      $this->db->where('id',$rid);
      $query = $this->db->get('stafftasks_report');
      if($query->num_rows()>0){
        $this->db->group_by('taskName');
        $this->db->where('taskName',$my_Report_Name);
        $this->db->where('id!=',$rid);
        $queryCheck = $this->db->get('stafftasks_report');
        if($queryCheck->num_rows()>0){
          echo '4';
        }else{
          if($oldTaskName==$my_Report_Name && $oldTaskSchedule==$my_Report_schedule && $oldTaskDetail==$my_Report_detail && $oldTaskReportedFile==$reportFile){
            echo '5';
          }else{
            if($reportFile==''){
              $querySelect=$this->db->query("select file_report from stafftasks_report where id='$rid' ");
              $rowQ=$querySelect->row();
              $fileReport=$rowQ->file_report;
              $reportFile=$fileReport;
            }else{
              $reportFile=$reportFile;
            }
            $data=array(
              'taskName'=>$my_Report_Name,
              'file_report'=>$reportFile,
              'task_response'=>$my_Report_detail,
              'report_schedule'=>$my_Report_schedule,
              'edited_status'=>'1'
            );
            $this->db->where('id',$rid);
            $query=$this->db->update('stafftasks_report',$data);
            if($query){
              echo '1';
            }else{
              echo '2';
            }
          }
        }
      }else{
        echo '3';
      }
    } 
  }
  function fetch_my_tasks(){
    $user=$this->session->userdata('username');
    $userType=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->main_model->fetch_my_tasks($max_year,$userType,$user);
  }
  function remove_this_report_file(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $data=array();
    if($this->input->post('removedId')){
      $removedId=$this->input->post('removedId');
      $this->db->where('id=',$removedId);
      $this->db->set('file_report','');
      $query=$this->db->update('stafftasks_report',$data);
      if($query){
        echo '1';
      }else{
        echo '2';
      }
    }
  }
}