<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Usergroup extends CI_Controller {
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
	public function index($page='usergroup')
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
      $this->load->view('home-page/'.$page,$data);
	} 
   function saveGroup(){
      $user=$this->session->userdata('username');
      if($this->input->post('usergroup')){
         $usergroup=strtolower($this->input->post('usergroup'));
         echo $this->main_model->saveGroup($usergroup,$user); 
      }
   }
   function saveGroupLevel(){
      $user=$this->session->userdata('username');
      if($this->input->post('usergroup')){
         $usergroupLevel=$this->input->post('usergroupLevel');
         $usergroup=strtolower($this->input->post('usergroup'));
         $this->db->set('userlevel',$usergroupLevel);
         $this->db->where('uname',$usergroup);
         $query=$this->db->update('usegroup');
      }
   }
   function fetchUserGroup(){
      $user=$this->session->userdata('username');
      echo $this->main_model->fetchUserGroup();  
   }
   function deleteGroup(){
      if($this->input->post('ugid')){
         $ugid=$this->input->post('ugid');
         echo $this->main_model->deleteGroup($ugid);
      }

   }
   function feedAccessOtherBranch(){
      $user=$this->session->userdata('username');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      if($this->input->post('groupName')){
         $groupName=$this->input->post('groupName');
         $this->db->where('uname',$groupName);
         $this->db->set('accessbranch','1');
         $this->db->update('usegroup');
      }
   }
   function deleteAccessOtherBranch(){
      if($this->input->post('groupName')){
         $groupName=$this->input->post('groupName');
         $this->db->where('uname',$groupName);
         $this->db->set('accessbranch','0');
         $this->db->update('usegroup');
      }
   }
   function fetchUserGroup_heirarchy(){
      $user=$this->session->userdata('username');
      echo $this->main_model->fetchUserGroup_heirarchy();  
   }
   function saveGroupHeirarchy(){
      $user=$this->session->userdata('username');
      if($this->input->post('usergroup')){
         $usergroupLevel=$this->input->post('usergroupLevel');
         $usergroup=strtolower($this->input->post('usergroup'));
         $this->db->set('user_hierarchy',$usergroupLevel);
         $this->db->where('uname',$usergroup);
         $query=$this->db->update('usegroup');
      }
   }
   function showUserGroup_heirarchy(){
      $user=$this->session->userdata('username');
      echo $this->main_model->showUserGroup_heirarchy();  
   }
}