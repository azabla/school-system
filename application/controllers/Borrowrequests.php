<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Borrowrequests extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='libraryManagement' and allowed='borrowRequests' order by id ASC ");  
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
    public function index($page='borrowrequests')
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
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['item_list']=$this->main_model->fetch_item_list();
        $this->load->view('home-page/'.$page,$data);
    }
    public function fetch_requested_book_toapprove(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        $user=$this->session->userdata('username');
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->fetch_requested_book_toapprove_all($user);
        }else{
            $queryHead=$this->db->query("select head_name from book_stock_head where head_name='$user' ");
            if($queryHead->num_rows()>0){
                echo $this->main_model->fetch_requested_book_toapprove_all($user);
            }else{
                echo '<div class="alert alert-light alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Ooops, No request found.
            </div></div>';
            }
        }
    }
    public function approve_request_bookBorrow(){
        $user=$this->session->userdata('username');
        if($this->input->post('requestid')){
            $requestid=$this->input->post('requestid');
            $requestquantity=$this->input->post('requestquantity');
            $requestItem=$this->input->post('requestItem');
            $this->db->where('id',$requestid);
            $this->db->set('status','1');
            $this->db->set('request_response','Approved');
            $this->db->set('response_by',$user);
            $queryUpdate=$this->db->update('book_borrow');  
        }
    }
    public function decline_request_bookBorrow(){
        $user=$this->session->userdata('username');
        if($this->input->post('requestid')){
            $requestid=$this->input->post('requestid');
            $this->db->where('id',$requestid);
            $this->db->set('status','1');
            $this->db->set('request_response','Rejected');
            $this->db->set('response_by',$user);
            $this->db->update('book_borrow');
        }
    }
}
