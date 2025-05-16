<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Borrowbooksreport extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='libraryManagement' and allowed='borrowReport' order by id ASC ");  
        if($this->session->userdata('username') == '' || $uperStuDE->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='borrowbooksreport')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
    
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function fetch_borrow_report(){
        $postData = $this->input->post();
        $data = $this->main_model->fetch_book_borrowed_for_report($postData);
        echo json_encode($data);
    }
    public function received_Borrowed_book(){
        $user=$this->session->userdata('username');
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('etc/GMT-10');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $date = gmdate("Y-m-d h:i A", $dt->format('U'));
        if($this->input->post('requestid')){
            $requestid=$this->input->post('requestid');
            $this->db->where('id',$requestid);
            $this->db->set('return_status','1');
            $this->db->set('returned_date',$date);
            $this->db->set('user_received',$user);
            $queryUpdate=$this->db->update('book_borrow');  
        }
    }
}
