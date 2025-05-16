<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Photogallery extends CI_Controller {
  public function __construct(){
    parent::__construct();
    ob_start();
    $this->load->helper('cookie');
    $this->load->model('main_model');
    $userLevel = userLevel();
    $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='websitemanagment' order by id ASC ");
    if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='1'){
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
  public function index($page='gallery')
  {
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('home-page/'.$page,$data);
  }
  function postgallery(){
    $user=$this->session->userdata('username');
    $config['upload_path']    = './gallery/';
    $config['allowed_types']  = 'gif|jpg|png|ico';
    $this->load->library('upload', $config);
    $title=$this->input->post('title');
    if ($this->upload->do_upload('picture')){
      $dataa =  $this->upload->data('file_name');
      $data=array(
        'gtitle'=>$title,
        'gname'=>$dataa,
        'gby'=>$user,
        'gdate'=>date('Y-m-d')
      );
      $this->db->insert('gallery',$data);
    }
  }
  function fetchgallery(){
    echo $this->main_model->all_fetch_gallery();
  }
  function deltephotogallery(){
    if(isset($_POST['gid'])){
      $id=$this->input->post('gid');
      $this->main_model->delete_gallery($id);
    }
  } 
}