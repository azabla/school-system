<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mychat extends CI_Controller {
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
    public function index($page='chat')
    {
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
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
        $this->load->view('student/'.$page,$data);
    } 

}