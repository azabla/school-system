<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $exportFile=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='ImportExport' and allowed='exportFile' order by id ASC ");
    if($this->session->userdata('username') == '' || $exportFile->num_rows()<1 || $userLevel!='1'){
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

    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year();
    $data['schools']=$this->main_model->fetch_school();
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $this->load->view('home-page/'.$page,$data);
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
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
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
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
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
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
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
      $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Marital Status');
      $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Email');
      $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Password');
      $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'City');
      $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Sub city');
      $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Woreda');
      $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Kebele');
      $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Registration Date');
      $objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Branch');
      $objPHPExcel->getActiveSheet()->SetCellValue('S1', 'User Division');
      $objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Academic year');
      $filename ='Staff-format.csv';  
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
      $objWriter->save('php://output');
    }
  }
  function student_formate() {
    if(isset($_POST['exportstudent'])){
      $filename ='Regular-Student-Registration-Format.csv';  
      header('Content-Type: testx/csv;charset=utf-8');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      $output=fopen('php://output', 'w');
      fputcsv($output,array('Student ID','First Name','Middle Name','Last Name','Last of Last Name','Gender','Grade','Section','Special Needs','Previous School','Father Mobile','Father Date of Birth','Father Age','Work','Work Place','Marital Status','Nationality','Mother Mobile','Mother Name','Date of birth','Age','Email','Password','City','Sub city','Woreda','Kebele','home_place','Registration/Joined Date','Branch','Transport Service','After School Program','Academic year'));
      fclose($output);
      /*  
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Student ID');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'First Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Middle Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Last Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Last of Last Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Gender(Male/Female)');
      $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Grade');
      $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Section');
      $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Father Mobile');
      $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Mother Mobile');
      $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Mother Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Date of birth(yyyy-mm-dd)');
      $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Age');
      $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Email');
      $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Password');
      $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'City');
      $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Sub city');
      $objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Woreda');
      $objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Kebele');
      $objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Home Place');
      $objPHPExcel->getActiveSheet()->SetCellValue('U1', 'Registration Date');
      $objPHPExcel->getActiveSheet()->SetCellValue('V1', 'Branch');
      $objPHPExcel->getActiveSheet()->SetCellValue('W1', 'Transport Service');
      $objPHPExcel->getActiveSheet()->SetCellValue('X1', 'After School Program');
      $objPHPExcel->getActiveSheet()->SetCellValue('Y1', 'Academic year');

      $objPHPExcel->getActiveSheet()->SetCellValue('Z1', 'Transport Service');
      $objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'After School Program');

      $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'GS/2015/0001');
      $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'John');
      $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Mike');
      $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Pawlos');
      $objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Male');
      $objPHPExcel->getActiveSheet()->SetCellValue('F2', '1');
      $objPHPExcel->getActiveSheet()->SetCellValue('G2', 'A');
      $objPHPExcel->getActiveSheet()->SetCellValue('H2', '09*****');
      $objPHPExcel->getActiveSheet()->SetCellValue('I2', '09***');
      $objPHPExcel->getActiveSheet()->SetCellValue('J2', 'Aster');
      $objPHPExcel->getActiveSheet()->SetCellValue('K2', 'yyyy-mm-dd');
      $objPHPExcel->getActiveSheet()->SetCellValue('L2', '6');
      $objPHPExcel->getActiveSheet()->SetCellValue('M2', 'info@gmail.com');
      $objPHPExcel->getActiveSheet()->SetCellValue('N2', '12345');
      $objPHPExcel->getActiveSheet()->SetCellValue('O2', 'Addis Ababa');
      $objPHPExcel->getActiveSheet()->SetCellValue('P2', 'Bole');
      $objPHPExcel->getActiveSheet()->SetCellValue('Q2', '02');
      $objPHPExcel->getActiveSheet()->SetCellValue('R2', '05');
      $objPHPExcel->getActiveSheet()->SetCellValue('S2', 'Bole Dembel');
      $objPHPExcel->getActiveSheet()->SetCellValue('T2', '2023-02-21');
      $objPHPExcel->getActiveSheet()->SetCellValue('U2', 'Main');
      $objPHPExcel->getActiveSheet()->SetCellValue('V2', 'Transport Service');
      $objPHPExcel->getActiveSheet()->SetCellValue('W2', 'Yes');
      $objPHPExcel->getActiveSheet()->SetCellValue('X2', '2015');
      $objPHPExcel->getActiveSheet()->SetCellValue('Y2', 'Academic year');
      $objPHPExcel->getActiveSheet()->SetCellValue('Z2', 'Transport Service');
      $objPHPExcel->getActiveSheet()->SetCellValue('AA2', 'After School Program');
      $filename ='Regular-Student-Registration-Format.csv';  
      header('Content-Type: application/csv');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
      $objWriter->save('php://output');*/
    }
  }
  function exportRemotestudent() {
    if(isset($_POST['exportRemotestudent'])){
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Student ID');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'First Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Middle Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Last Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Gender(Male/Female)');
      $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Grade');
      $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Section');
      $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Father Mobile');
      $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Mother Mobile');
      $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Mother Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Date of birth(yyyy-mm-dd)');
      $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Age');
      $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Email');
      $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Password');
      $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'City');
      $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Sub city');
      $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Woreda');
      $objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Kebele');
      $objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Home Place');
      $objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Registration Date');
      $objPHPExcel->getActiveSheet()->SetCellValue('U1', 'Branch');
      $objPHPExcel->getActiveSheet()->SetCellValue('V1', 'Transport Service');
      $objPHPExcel->getActiveSheet()->SetCellValue('W1', 'After School Program Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('X1', 'Academic year');

      $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'GS/2015/0001');
      $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'John');
      $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Mike');
      $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Pawlos');
      $objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Male');
      $objPHPExcel->getActiveSheet()->SetCellValue('F2', '1');
      $objPHPExcel->getActiveSheet()->SetCellValue('G2', 'A');
      $objPHPExcel->getActiveSheet()->SetCellValue('H2', '09*****');
      $objPHPExcel->getActiveSheet()->SetCellValue('I2', '09***');
      $objPHPExcel->getActiveSheet()->SetCellValue('J2', 'Aster');
      $objPHPExcel->getActiveSheet()->SetCellValue('K2', 'yyyy-mm-dd');
      $objPHPExcel->getActiveSheet()->SetCellValue('L2', '6');
      $objPHPExcel->getActiveSheet()->SetCellValue('M2', 'info@gmail.com');
      $objPHPExcel->getActiveSheet()->SetCellValue('N2', '12345');
      $objPHPExcel->getActiveSheet()->SetCellValue('O2', 'Addis Ababa');
      $objPHPExcel->getActiveSheet()->SetCellValue('P2', 'Bole');
      $objPHPExcel->getActiveSheet()->SetCellValue('Q2', '02');
      $objPHPExcel->getActiveSheet()->SetCellValue('R2', '05');
      $objPHPExcel->getActiveSheet()->SetCellValue('S2', 'Bole Dembel');
      $objPHPExcel->getActiveSheet()->SetCellValue('T2', '2023-02-21');
      $objPHPExcel->getActiveSheet()->SetCellValue('U2', 'Main');
      $objPHPExcel->getActiveSheet()->SetCellValue('V2', 'Transport Service');
      $objPHPExcel->getActiveSheet()->SetCellValue('W2', 'Sport');
      $objPHPExcel->getActiveSheet()->SetCellValue('X2', '2016');
      $filename ='Non-regular student registration format.csv';  
      header('Content-Type: application/csv');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
      $objWriter->save('php://output');
    }
  }
  function transportService() {
    if(isset($_POST['exportransportservice'])){
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Student ID/Username');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Transport Service Place');
      $filename ='Transport-service.csv';  
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
      $objWriter->save('php://output');
    }
  }
  function mobileupdate() {
    if(isset($_POST['mobileupdateformat'])){
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Student ID/Username');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Mother Mobile');
      $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Father Mobile');
      $filename ='Student Mobile template.csv';  
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
      $objWriter->save('php://output');
    }
  }
  function emailupdate() {
    if(isset($_POST['emailupdateformat'])){
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Student ID/Username');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Email');
      $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Optional email');
      $filename ='Student Email template.csv';  
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
      $objWriter->save('php://output');
    }
  }
  function staffpayroll() {
    if(isset($_POST['staffpayrollupdateformat'])){
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Staff Username/Mobile');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Quality Allowance');
      $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Transport Allowance');
      $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Position Allowance');
      $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Home Allowance');
      $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Basic Salary');
      $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Gross Sallary');
      $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Taxable Income');
      $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Income tax');
      $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Pension 7%');
      $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Pension 11%');
      $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Other');
      $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Net Payment');
      $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Date Employeed');
      $filename ='Staff Payroll Format.csv';  
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
      $objWriter->save('php://output');
    }
  }
  function exportBookRegistration() {
    if(isset($_POST['exportBookRegistration'])){
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Book ID');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Book Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Book Grade');
      $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Book Price');
      $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Stock');
      $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Branch');
      $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Book0001');
      $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'History');
      $objPHPExcel->getActiveSheet()->SetCellValue('C2', '10');
      $objPHPExcel->getActiveSheet()->SetCellValue('D2', '500');
      $objPHPExcel->getActiveSheet()->SetCellValue('E2', '50');
      $objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Branch Name');
      $filename ='Library Book Registration Form.csv';  
      header('Content-Type: application/csv');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
      $objWriter->save('php://output');
    }
  }
  function exportInventoryRegistration() {
    if(isset($_POST['exportInventoryRegistration'])){
      $this->load->library('excel');
      $objPHPExcel = new Excel();
      $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Item ID');
      $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Item Name');
      $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Item Category');
      $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Item Type/color');
      $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Service Type');
      $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Stock');
      $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Unit Price');
      $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Total Price');
      $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Expiray Date');
      $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Item Branch');
      $filename ='Inventory Registration Form.csv';  
      header('Content-Type: application/csv');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
      $objWriter->save('php://output');
    }
  }
}