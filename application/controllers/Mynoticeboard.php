<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mynoticeboard extends CI_Controller {
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
  public function index($page='notice-board')
  {
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;
    $today=date('y-m-d');

    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['posts']=$this->main_model->fetch_post();
    $data['usergroup']=$this->main_model->fetchUserGroupRegistration();
    $this->load->view('teacher/'.$page,$data);
     
  }
}