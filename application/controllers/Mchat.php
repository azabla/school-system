<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mchat extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='Chat' order by id ASC "); 
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='2'){
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
    public function index($page='chat')
    {
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='Chat' order by id ASC "); 
        if($usergroupPermission->num_rows()<1){ 
            redirect('home/');
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['usertype']=$this->main_model->fetch_usertype();
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('teacher/'.$page,$data);
    } 

}