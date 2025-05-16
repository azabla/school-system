<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Themes extends CI_Controller {
   public function __construct(){
      parent::__construct();
      $this->load->model('main_model');
      ob_start();
      $this->load->helper('cookie');
      if($this->session->userdata('username') == ''){
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
	public function index($page='themes')
	{
      if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
         show_404();
      }
      $user=$this->session->userdata('username');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      $today=date('y-m-d');
      $query_quarter = $this->db->query("select max(quarter) as quarter from mark");
      $row_quarter = $query_quarter->row();
      $max_quarter=$row_quarter->quarter;
        
      $data['fetch_term']=$this->main_model->fetch_term($max_year);
      $data['sessionuser']=$this->main_model->fetch_session_user($user);
      $data['academicyear']=$this->main_model->academic_year();
      $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
      $data['branch']=$this->main_model->fetch_branch($max_year);
      $data['schools']=$this->main_model->fetch_school();
      $this->load->view('home-page/'.$page,$data);
	} 
   function saveThemes(){
      $user=$this->session->userdata('username');
      if($this->input->post('setasbg')){
         $setasbg=$this->input->post('setasbg');
         echo $this->main_model->saveBgImage($setasbg,$user); 
      }
      redirect('themes/');
   }
}