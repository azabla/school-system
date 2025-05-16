<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approverequest extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='inventoryManagement' and allowed='approverequest' order by id ASC ");  
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
	public function index($page='approverequest')
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
    public function fetch_requested_item_toapprove(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        $user=$this->session->userdata('username');
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->fetch_requested_item_toapprove_all($user);
        }else{
            echo $this->main_model->fetch_requested_item_toapprove($user);
        }
    }
    public function approve_request_item(){
        $user=$this->session->userdata('username');
        if($this->input->post('requestid')){
            $requestid=$this->input->post('requestid');
            $requestquantity=$this->input->post('requestquantity');
            $requestItem=$this->input->post('requestItem');
            $queryUpdate=$this->main_model->update_itemrequest($requestItem,$requestquantity);
            if($queryUpdate){
                $this->db->where('id',$requestid);
                $this->db->set('status','1');
                $this->db->set('request_response','Approved');
                $this->db->set('response_by',$user);
                $queryUpdate=$this->db->update('stock_requested');
            }
        }
    }
    public function decline_request_item(){
        $user=$this->session->userdata('username');
        if($this->input->post('requestid')){
            $requestid=$this->input->post('requestid');
            $this->db->where('id',$requestid);
            $this->db->set('status','1');
            $this->db->set('request_response','Rejected');
            $this->db->set('response_by',$user);
            $this->db->update('stock_requested');
        }
    }
}
