<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delete_specific_gradevaluation extends CI_Controller {
  public function __construct(){
    parent::__construct();
    if($this->session->userdata('username') == ''){
      $this->session->set_flashdata("error","Please Login first");
      redirect('Login');
    }     
  }
  public function index($page='evaluation')
  {
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $data1=array();
    date_default_timezone_set("Africa/Addis_Ababa");
    $dtz = new DateTimeZone('UTC');
    $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
    $datetried = gmdate("Y-m-d h:i A", $dt->format('U'));
    if(isset($_POST['grade'])){
      $grade=$this->input->post('grade');
      $evname=$this->input->post('evname');
      $quarter=$this->input->post('quarter');
      $querySelect=$this->db->query("select * from users where username='$user' ");
      $rowName=$querySelect->row();
      $fname=$rowName->fname;
      $mname=$rowName->mname;
      $lname=$rowName->lname;
      $data1=array(
          'userinfo'=>$user,
          'useraction'=>'Evaluation deleted',
          'infograde'=>$grade,
          'subject'=>'-',
          'quarter'=>$quarter,
          'academicyear'=>$max_year,
          'oldata'=>'-',
          'newdata'=>'-',
          'markname'=>$evname,
          'userbranch'=>'-',
          'actiondate'=> $datetried
      );
      $queryInsert=$this->db->insert('useractions',$data1);
      if($queryInsert){
        $query=$this->main_model->delete_thisgradevaluation($grade,$quarter,$evname,$max_year);
        if($query){
          echo '<span class="text-success">Deleted</span>';
        }else{
          echo'Oooops, Try again';
        }
      }
    }
  } 
}