<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pollresult extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='createPolls' and tableName='HomepagePost' order by id ASC ");
        if($this->session->userdata('username') == '' || $uaddMark->num_rows()<1 || $userLevel!='1'){
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
    public function index($page='pollresult')
    {
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
        {
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['fetch_year']=$this->main_model->fetch_year();
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['posts']=$this->main_model->fetch_term($max_year);
        $this->load->view('home-page/'.$page,$data);
    }
    public function fetch_poll_data(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        echo $this->main_model->fetch_poll_posts_summary($user,$this->input->post('limit'), $this->input->post('start'),$usertype);
    }

}