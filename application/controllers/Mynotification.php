<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mynotification extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('chat_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel(); 
    if($this->session->userdata('username') == ''  || $userLevel!='2'){
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
	public function index($page='my-notification')
	{
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $data['sessionuser']=$this->chat_model->fetch_session_user($user);
    $data['schools']=$this->chat_model->fetch_school();
    $this->load->view('teacher/'.$page,$data);
	} 
  public function Fetch_my_notification(){
    $user=$this->session->userdata('username');
    echo $this->chat_model->fetch_my_notification($user);
  }
}