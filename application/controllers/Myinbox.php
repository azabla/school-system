<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myinbox extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        $this->load->library("pagination");
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
	public function index()
	{
        if(!file_exists(APPPATH.'views/student/inbox.php')){
            show_404();
        }
         $this->pageConfig();
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["links"] = $this->pagination->create_links();
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['allmessages']=$this->main_model->fetch_inbox($this->per_page,$page,$user,$usertype);
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('student/inbox',$data);
	}
    public function pageConfig(){ 
        $usertype=$this->session->userdata('usertype');
        $user=$this->session->userdata('username');    
        $config = array();
        $config["base_url"] = base_url() . "inbox/index";
        $config["total_rows"] = $this->main_model->fetch_count_inbox($user,$usertype);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $this->per_page=$config["per_page"]; 
        $this->pagination->initialize($config);        
    }  

}