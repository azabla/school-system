<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CheckRegistration extends CI_Controller {
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
	public function index()
	{
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $unique_id=$this->session->userdata('unique_id');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $queryStudentChk=$this->db->query("select id,username,grade from users where unique_id='".$unique_id."' and academicyear = '".$max_year."' ");
        if($queryStudentChk->num_rows()>0 ){
            echo '<p class="text-success" title="Registered"><i class="fas fa-check-circle"></i></p>';
        }else{
            echo '<p class="text-danger" title="Not registered yet."><i class="fas fa-times-circle"></i></p>';
        }
	} 
}