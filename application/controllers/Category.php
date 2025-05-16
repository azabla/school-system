<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='feemanagment' order by id ASC "); 
    if($this->session->userdata('username') == '' || $usergroupPermission->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='category')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $today=date('y-m-d');

    if(isset($_POST['postpc'])){
      if(!empty($this->input->post('month'))){
        $pcname=$this->input->post('pcname');
        $grade=$this->input->post('grade');
        $month=$this->input->post('month');
        $amount=$this->input->post('amount');
        $acy=$this->input->post('acy');
        for ($i=0; $i <count($grade) ; $i++) { 
          $checkgrade=$grade[$i];
          for($j=0; $j <count($month) ; $j++){
            $checkmonth=$month[$j];
            $query=$this->main_model->add_payment_category($acy,
            $pcname,$checkgrade,$checkmonth);
            if($query){
              $data=array(
                'name'=>$pcname,
                'grade'=>$checkgrade,
                'month'=>$checkmonth,
                'amount'=>$amount,
                'academicyear'=>$acy,
                'date_created'=>date('M-d-Y')
              );
              $this->db->insert('paymentype',$data);
            }
          }
        }
      }
    }
    if(isset($_POST['postgrade'])){
      $id=$this->input->post('postgrade');
      $this->main_model->delete_payment_category($id);
    }
    $data['grade']=$this->main_model->fetch_grade($max_year);
    $data['month']=$this->main_model->fetch_month();
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('home-page/'.$page,$data);
	} 
}