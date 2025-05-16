<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Markformat extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='studentmarkformat' order by id ASC ");
    if($this->session->userdata('username') == '' || $uaddMark->num_rows() < 1 || $userLevel!='2'){
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
  public function index($page='mark-formate')
  {
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $today=date('y-m-d');

    $data['fetch_grade']=$this->main_model->fetch_grade_from_staffplace4Director($user,$max_year);
    $data['fetch_term']=$this->main_model->fetch_term($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('teacher/'.$page,$data);
  }
  function export(){
    if(isset($_POST['gethisgrade'])){
      $this->load->model('main_model');
      $this->load->library('excel');
      $obj = new Excel();
      $user=$this->session->userdata('username');
      $query_branch = $this->db->query("select * from users where username='$user'");
      $row_branch = $query_branch->row();
      $branch=$row_branch->branch;
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      $today=date('y-m-d');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      $branch1=$this->input->post('branch');
      
      $listInfo = $this->main_model->export_mystudent_mark_formate($gradesec,$quarter,$max_year,$branch);
      $evnameinfo = $this->main_model->export_mythis_grade_evname($gradesec,$quarter,$max_year,$branch);
      $count_sub=$this->main_model->get_allsubject($gradesec,$max_year);
      foreach ($count_sub as $count_subs) {
        $allsub=$count_subs->all_sub;
        $objWorkSheet = $obj->createSheet($allsub);
        $objWorkSheet->SetCellValue('A3','Id');
        $objWorkSheet->SetCellValue('B3','StuID');
        $objWorkSheet->SetCellValue('C3','Full Name');
        $column = 3;
        foreach($evnameinfo  as $field)
        {
          $objWorkSheet->setCellValueByColumnAndRow($column, 1, $field->evname);
          $objWorkSheet->setCellValueByColumnAndRow($column, 2, $field->eid);
          $objWorkSheet->setCellValueByColumnAndRow($column, 3, $field->percent);
          $column++;
        }
        $rowCount = 4;
        foreach ($listInfo as $list) {
          $objWorkSheet->SetCellValue('A' . $rowCount, $list->id);
          $objWorkSheet->SetCellValue('B' . $rowCount, $list->username);
          $objWorkSheet->SetCellValue('C' . $rowCount, strtoupper($list->fname.' '.$list->mname.' '.$list->lname));
          $rowCount++;   
        }
        $objWorkSheet->SetCellValue('C1', $branch);
        $objWorkSheet->SetCellValue('B2', $quarter);
        $objWorkSheet->SetCellValue('C2', $count_subs->Subj_name);
        $objWorkSheet->SetCellValue('B1', $gradesec);
        $invalidCharacters = $objWorkSheet->getInvalidCharacters();
        $title = str_replace($invalidCharacters, '', $count_subs->Subj_name);
        $objWorkSheet->setTitle($title);
      }
      
      $filename =$gradesec.'.xls';  
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'"'); 
      header('Cache-Control: max-age=0'); 
      $objWriter = PHPExcel_IOFactory::createWriter($obj, 'Excel5');
      $objWriter->save('php://output');
    }
  } 
}