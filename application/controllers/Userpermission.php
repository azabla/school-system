<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Userpermission extends CI_Controller {
   public function __construct(){
      parent::__construct();
      $this->load->model('main_model');
      ob_start();
      $this->load->helper('cookie');
      if($this->session->userdata('username') == '' || 
         $this->session->userdata('usertype')!= 'superAdmin'){
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
	public function index($page='userpermission')
	{
      if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
         show_404();
      }
      $user=$this->session->userdata('username');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      $today=date('y-m-d');
        
      $data['fetch_term']=$this->main_model->fetch_term($max_year);
      $data['sessionuser']=$this->main_model->fetch_session_user($user);
      $data['academicyear']=$this->main_model->academic_year();
      $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
      $data['branch']=$this->main_model->fetch_branch($max_year);
      $data['schools']=$this->main_model->fetch_school();
      $data['usergroup']=$this->main_model->fetch_usergroup();
      $this->load->view('home-page/'.$page,$data);
	}
   function grantUserPermission(){
      $user=$this->session->userdata('username');
      echo $this->main_model->grantUserPermission();  
   }
   function saveGroupPermission(){
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      if($this->input->post('usergroup')){
         $usergroup=$this->input->post('usergroup');
         $tableName=$this->input->post('tableName');
         $allowed=$this->input->post('allowed');
         $data=array(
            'usergroup'=>$usergroup,
            'tableName'=>$tableName,
            'allowed'=>$allowed,
            'academicyear'=>$max_year,
            'datecreated'=>date('M-d-Y')
         );
         $this->db->insert('usergrouppermission',$data);
      }
   }
   function deleteGroupPermission(){
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      if($this->input->post('usergroup')){
         $usergroup=$this->input->post('usergroup');
         $tableName=$this->input->post('tableName');
         $allowed=$this->input->post('allowed');
         $this->db->where('usergroup',$usergroup);
         $this->db->where('tableName',$tableName);
         $this->db->where('allowed',$allowed);
         $this->db->delete('usergrouppermission');
      }
   }
}