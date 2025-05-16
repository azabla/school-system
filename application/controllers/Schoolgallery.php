<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schoolgallery extends CI_Controller {
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
  public function index($page='gallery')
  {
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        if(isset($_POST['postgallery'])){
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
        if(isset($_POST['deletegallery'])){
          $id=$this->input->post('deletegallery');
          $this->load->model('main_model');
          $this->main_model->delete_gallery($id);

        }
        $this->load->model('main_model');
        $data['fetch_gallery']=$this->main_model->fetch_gallery();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('teacher/'.$page,$data);
  } 

}