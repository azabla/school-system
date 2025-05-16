<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exportformat extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $exportFile=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='ImportExport' and allowed='exportFile' order by id ASC ");
    if($this->session->userdata('username') == '' || $exportFile->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='export')
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

    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year();
    $data['schools']=$this->main_model->fetch_school();
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $this->load->view('teacher/'.$page,$data);
	}
  function subject_formate(){
    if(isset($_POST['exportsubject'])){
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Subject Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Grade');
      $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Grading System');
      $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Academic Year');
      $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Display on R.card');
      $filename ='Subject-format.csv';  
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
    }
  }
  function attendance_formate(){
    if(isset($_POST['exportattendance'])){
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Student Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Student ID');
      $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Absent Date(D/M/Y)');
      $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Absent Type');
      $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Total Absent');
      $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Quarter');
      $filename ='Attendance-format.csv';  
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
    }
  } 
  function evaluation_formate(){
    if(isset($_POST['exportevaluation'])){
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Evaluation Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Quarter');
      $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Grade');
      $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Percentage');
      $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Academic Year');
      $filename ='Evaluation-format.csv';  
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
    }
  }
  function staff_formate() {
    if(isset($_POST['exportstaff'])){
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Staff Username');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Staff Usertype');
      $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'First Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Middle Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Last Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Gender');
      $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Mobile');
      $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Date of birth');
      $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Age');
      $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Email');
      $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Password');
      $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'City');
      $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Sub city');
      $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Woreda');
      $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Kebele');
      $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Registration Date');
      $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Branch');
      $objPHPExcel->getActiveSheet()->SetCellValue('R1', 'User Division');
      $objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Academic year');
      $filename ='Staff-format.csv';  
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
    }
  }
  function student_formate() {
    if(isset($_POST['exportstudent'])){
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      //$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'UserName');
      //$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'UserType');
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Student ID');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'First Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Middle Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Last Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Gender');
      $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Grade');
      $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Section');
      $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Father Mobile');
      $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Mother Mobile');
      $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Mother Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Date of birth');
      $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Age');
      $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Email');
      $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Password');
      $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'City');
      $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Sub city');
      $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Woreda');
      $objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Kebele');
      //$objPHPExcel->getActiveSheet()->SetCellValue('W1', 'Approved');
      $objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Registration Date');
      $objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Branch');
      $objPHPExcel->getActiveSheet()->SetCellValue('U1', 'Transport Service');
      $objPHPExcel->getActiveSheet()->SetCellValue('V1', 'Academic year');
      //$objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'Update');
      //$objPHPExcel->getActiveSheet()->SetCellValue('AB1', 'Status');
      //$objPHPExcel->getActiveSheet()->SetCellValue('AC1', 'Delete');
      $filename ='Student-format.csv';  
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
    }
  }
}