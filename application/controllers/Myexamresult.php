<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myexamresult extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('security');
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
    public function index($page='examresult')
    {
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $this->load->helper('date');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year; 
        $this->db->select('branch,grade,id');
        $this->db->where('username',$user);
        $this->db->where('academicyear',$max_year);
        $query_branch=$this->db->get('users');
        if($query_branch->num_rows()>0){
            $row_branch = $query_branch->row();
            $branch=$row_branch->branch;
            $grade=$row_branch->grade;
            $sid=$row_branch->id;
            $data['exam']=$this->main_model->fetch_my_examresult($sid,$max_year);
        }else{
            $data['exam']='';
        }        
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['fetch_gradesec']=$this->main_model->fetch_session_gradesec($user,$max_year);
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('student/'.$page,$data);
    } 
}