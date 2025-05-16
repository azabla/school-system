<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Borrowedlibrarybooks extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('security');
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE1="SELECT * from usergrouppermission where usergroup=? and tableName=? and allowed=? order by id ASC ";  
        $uperStuDE=$this->db->query($uperStuDE1,array($_SESSION['usertype'],'libraryManagement','borrowBooks'));
        if($this->session->userdata('username') == '' || $uperStuDE->num_rows() < 1 || $userLevel!='3'){
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
    public function index($page='borrowbooks')
    {
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
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
        $this->load->view('student/'.$page,$data);
    }
    function fetch_book_borrow(){
        $user=$this->session->userdata('username');
        if($this->input->is_ajax_request()){
            $postData = $this->input->post();
            $data = $this->main_model->fetch_book_borrow($user,$postData);
            echo json_encode($data);
        }
    }
    function borrow_book_name(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->is_ajax_request()){
            if($this->input->post('stockid')){
                $stockid=$this->input->post('stockid',TRUE);
                $stockid=xss_clean($stockid);
                echo $this->main_model->borrow_book_name($stockid,$max_year);
            }
        }
    }
    function borrowThis_bookName(){
        $user=$this->session->userdata('username');
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('etc/GMT-10');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $date = gmdate("Y-m-d h:i A", $dt->format('U'));
        if($this->input->is_ajax_request()){
            if($this->input->post('hiddenBorrowedBookName')){
                $hiddenBorrowedBookName=$this->input->post('hiddenBorrowedBookName',TRUE);
                $dateReturnBook=$this->input->post('dateReturnBook',TRUE);
                $dateReturnBook=xss_clean($dateReturnBook);
                $hiddenBorrowedBookName=xss_clean($hiddenBorrowedBookName);
                $data=array(
                  'book_id'=>$hiddenBorrowedBookName,
                  'date_returned'=>$dateReturnBook,
                  'date_submitted'=>$date,
                  'submitted_by'=>$user
                );
                $query=$this->db->insert('book_borrow',$data);
                if($query){
                    echo 'Submitted successfully';
                }else{
                    echo 'Please try again';
                }
            } 
        }
    }
    function removerequest_borrow_book_name(){
        if($this->input->is_ajax_request()){
            if($this->input->post('stockid')){
                $stockid=$this->input->post('stockid',TRUE);
                $stockid=xss_clean($stockid);
                $this->db->where('book_id',$stockid);
                $this->db->where('status','1');
                $query=$this->db->delete('book_borrow');
                if($query){
                    echo 'Canceled successfully';
                }else{
                    echo 'Please try again';
                }
            }
        }
    }
    
}
