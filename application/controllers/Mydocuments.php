<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mydocuments extends CI_Controller {
  public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        if($this->session->userdata('username') == '' || $userLevel!='3'){
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
	public function index($page='documents')
	{
    if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('student/'.$page,$data);
	}
  function postdocuments(){
    $user=$this->session->userdata('username');
    $config['upload_path'] = './mydocument/';
    $config['allowed_types'] ='csv|xlsx|docx|pdf';
    $config['encrpt_name']=TRUE;
    $this->load->library('upload', $config);
    if($this->upload->do_upload('mydoc')){
      $filename= $this->upload->data('file_name');
      $data=array(
        'filename'=>$filename,
        'fileuser'=>$user,
        'datecreated'=>date('M-d-Y')
      );
      $this->main_model->insertdocument($data);
    }    
  }
  function fetchdocuments(){
    $user=$this->session->userdata('username');
    echo $this->main_model->fetchdocuments($user);
  } 
  function Deletedocuments(){
    if($this->input->post('id')){
      $id=$this->input->post('id');
      $this->main_model->deletedocuments($id);
    }
  }
}