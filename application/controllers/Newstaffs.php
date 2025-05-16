<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newstaffs extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentPr' order by id ASC ");  
    if($this->session->userdata('username') == '' || $uperStuDE->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='new-staffs')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('home-page/'.$page,$data);
	} 
  public function accept_registration_request(){
    $user=$this->session->userdata('username');
    if($this->input->post('stuid')){
      $id=$this->input->post('stuid');
      echo $this->main_model->accept_staffs($id,$user);
    }
  }
  public function reject_registration_request(){
    $user=$this->session->userdata('username');
    if($this->input->post('decline_stuid')){
      $id=$this->input->post('decline_stuid');
      echo $this->main_model->decline_staffs($id,$user);
    }
  }
  public function delete_registration_request(){
    $user=$this->session->userdata('username');
    if($this->input->post('decline_stuid')){
      $id=$this->input->post('decline_stuid');
      $this->db->where('id',$id);
      $query=$this->db->delete('users_registration_request');
      if($query){
        echo '1';
      }else{
        echo '0';
      }
    }
  }
  public function decline_all()
  {
    $this->db->where('isapproved','0');
    $this->db->set('status2','2');
    $query=$this->db->update('users_registration_request');
    if($query){
      echo '1';
    }else{
      echo '0';
    }
  }
  public function delete_all()
  {
    $this->db->where('isapproved','0');
    $query=$this->db->delete('users_registration_request');
    if($query){
      echo '1';
    }else{
      echo '0';
    }
  }
  public function fetch_rejected_registration(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $postData = $this->input->post();
    $data= $this->main_model->fetch_rejected_registration($max_year,$postData);
    echo json_encode($data);
  }
  public function re_accept_registration_request(){
    $user=$this->session->userdata('username');
    if($this->input->post('stuid')){
      $id=$this->input->post('stuid');
      $this->db->where(array('id'=>$id));
      $this->db->set('isapproved','1');
      $this->db->set('status2','0');
      $this->db->set('isapproved_by',$user);
      $query=$this->db->update('users_registration_request');
      if($query){
        echo '1';
      }else{
        echo '0';
      }
    }
  }

}