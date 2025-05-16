<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myresult extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('gs_model');
        $this->load->helper('cookie');
        $this->load->helper('security');
        ob_start();
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
	public function index($page='mark-result')
	{
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $data['fetch_term']=$this->gs_model->fetch_term($max_year);
        $data['sessionuser']=$this->gs_model->fetch_session_user($user);
        $data['academicyear']=$this->gs_model->academic_year_filter();
        $data['schools']=$this->gs_model->fetch_school();
        $this->load->view('student/'.$page,$data);
	} 
    function fetchMySubject(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->is_ajax_request()){
            $this->db->where('username',$user);
            $this->db->where('academicyear',$max_year);
            $this->db->select('grade');
            $query_gradesec = $this->db->get('users');
            if($query_gradesec->num_rows()>0){
                $row_gradesec = $query_gradesec->row();
                $grade=$row_gradesec->grade;
                $data['token'] = $this->security->get_csrf_hash();
                if($grade!=''){
                    $data['subject']= $this->gs_model->loadMySubject($max_year,$grade);
                    echo json_encode($data);
                }else{
                    $data['subject']= '<div class="alert alert-light alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> No data to fetch.Please contact your school admin!
                    </div></div>';
                    echo json_encode($data);
                }
            }else{
                $data['subject']= '<div class="alert alert-light alert-dismissible show fade">
                    <div class="alert-body">
                    <i class="fas fa-check-circle"> </i> Please wait the school is under registration for new academic year .
                </div></div>';
                echo json_encode($data);
            }
        }
    }
    function fetchThisSubjectResult(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $this->db->where('username',$user);
        $this->db->where('academicyear',$max_year);
        $this->db->select('grade,id,gradesec,branch');
        $query_gradesec = $this->db->get('users');
        
        $row_gradesec = $query_gradesec->row();
        $grade=$row_gradesec->grade;
        $gradesec=$row_gradesec->gradesec;
        $id=$row_gradesec->id;
        $branch1=$row_gradesec->branch;
        if($this->input->is_ajax_request()){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('subid','subid','required');
            if($this->form_validation->run()==FALSE){
                $data['result']= 'Please try later'; 
                $data['token'] = $this->security->get_csrf_hash();
                echo json_encode($data);
                return;
            }
            $postData=$this->input->post(['subid'],TRUE);
            $postData =xss_clean($postData);

            $this->db->where('academicyear',$max_year);
            $queryCheck = $this->db->get('enableapprovemark');
            if($queryCheck->num_rows()>0){
                $data['result']= $this->gs_model->fetch_my_markresultApproved($branch1,$gradesec,$postData,$grade,$max_year,$id); 
                $data['token'] = $this->security->get_csrf_hash();
                echo json_encode($data);
            }else{
                $data['result']= $this->gs_model->fetch_my_markresult($branch1,$gradesec,$postData,$grade,$max_year,$id); 
                $data['token'] = $this->security->get_csrf_hash();
                echo json_encode($data);
            }
        }
    }
    function fetchThisSubjectDeatilResult(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_gradesec1 ="select grade,id,gradesec,branch from users where username=? and academicyear=? ";
        $query_gradesec=$this->db->query($query_gradesec1,array($user,$max_year));
        $row_gradesec = $query_gradesec->row();
        $grade=$row_gradesec->grade;
        $gradesec=$row_gradesec->gradesec;
        $id=$row_gradesec->id;
        $branch1=$row_gradesec->branch;
        if($this->input->is_ajax_request()){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('subid','subid','required');
            $this->form_validation->set_rules('quarter','quarter','required');
            if($this->form_validation->run()==FALSE){
                $data['result']= 'Please try later'; 
                $data['token'] = $this->security->get_csrf_hash();
                echo json_encode($data);
                return;
            }
            $postID=$this->input->post('subid',TRUE);
            $postQUarter=$this->input->post('quarter',TRUE);        
            $postID = xss_clean($postID);
            $postQUarter =xss_clean($postQUarter);
            $this->db->where('academicyear',$max_year);
            $queryCheck = $this->db->get('enableapprovemark');
            if($queryCheck->num_rows()>0){
                $data['result']=  $this->gs_model->fetch_mydeatil_markresultApproved($branch1,$gradesec,$postID,$postQUarter,$grade,$max_year,$id); 
                $data['token'] = $this->security->get_csrf_hash();
                echo json_encode($data);
            }else{
                $data['result']=  $this->gs_model->fetch_mydeatil_markresult($branch1,$gradesec,$postID,$postQUarter,$grade,$max_year,$id); 
                $data['token'] = $this->security->get_csrf_hash();
                echo json_encode($data);
            }
        }
    }
}