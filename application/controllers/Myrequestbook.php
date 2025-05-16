<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myrequestbook extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('gs_model');
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
	public function index($page='myrequestbook')
	{
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $data['fetch_today_attendance']=$this->gs_model->my_total_absents($max_year,$user);
        $data['sessionuser']=$this->gs_model->fetch_session_user($user);
        $data['academicyear']=$this->gs_model->academic_year_filter();
        $data['schools']=$this->gs_model->fetch_school();
        $data['gradesec']=$this->gs_model->fetch_gradesec($max_year);
        $this->load->view('student/'.$page,$data);
	} 
    function fetchLeavingRequest(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        if($this->input->is_ajax_request()){
            echo $this->gs_model->fetchLeavingRequest($max_year,$user);
        }
    }
    function sendLeavingRequest(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $data=array();
        if($this->input->is_ajax_request()){
            if($this->input->post('requestType')){
                $requestType=$this->input->post('requestType',TRUE);
                $requestType=xss_clean($requestType);
                $this->db->where('academicyear',$max_year);
                $this->db->where('stuid',$user);
                $this->db->where('requestype',$requestType);
                $query=$this->db->get('studentrequest');
                if($query->num_rows()<1){
                    $data=array(
                        'stuid'=>$user,
                        'requestype'=>$requestType,
                        'requestdate'=>date('M-d-Y'),
                        'academicyear'=>$max_year
                    );
                    $queryCheck=$this->db->insert('studentrequest',$data);
                    echo $this->gs_model->fetchLeavingRequest($max_year,$user); 
                }else{
                    echo $this->gs_model->fetchLeavingRequest($max_year,$user); 
                }
                
            }
        }
    }
}