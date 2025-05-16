<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Borrowbooks extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='libraryManagement' and allowed='borrowBooks' order by id ASC ");  
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
	public function index($page='borrowbooks')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
       
        if(isset($_POST['drop_id'])){
            $id=$this->input->post('drop_id');
            $this->main_model->inactive_student($id);
        }
        $accessbranch = sessionUseraccessbranch();
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['staffs']=$this->main_model->fetchStaffsForPlacement();
        }else{
            $data['staffs']=$this->main_model->fetchMyStaffsForPlacement($branch);
        }
        $this->load->view('home-page/'.$page,$data);
	}
    function fetch_book_borrow(){
        $user=$this->session->userdata('username');
        $postData = $this->input->post();
        $data = $this->main_model->fetch_book_borrow($user,$postData);
        echo json_encode($data);
    }
    function borrow_book_name(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('stockid')){
            $stockid=$this->input->post('stockid');
            echo $this->main_model->borrow_book_name($stockid,$max_year);
        }
    }
    function borrowThis_bookName(){
        $user=$this->session->userdata('username');
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('etc/GMT-10');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $date = gmdate("Y-m-d h:i A", $dt->format('U'));
        if($this->input->post('hiddenBorrowedBookName')){
            $hiddenBorrowedBookName=$this->input->post('hiddenBorrowedBookName');
            $dateReturnBook=$this->input->post('dateReturnBook');
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
    function removerequest_borrow_book_name(){
        if($this->input->post('stockid')){
            $stockid=$this->input->post('stockid');
            $this->db->where('book_id',$stockid);
            $this->db->where('status','0');
            $query=$this->db->delete('book_borrow');
            if($query){
                echo 'Canceled successfully';
            }else{
                echo 'Please try again';
            }
        }
    }
    
}
