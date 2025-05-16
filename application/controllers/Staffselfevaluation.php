<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffselfevaluation extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $this->load->helper('security');
        $userLevel = userLevel();
        $this->db->where('usergroup',$_SESSION['usertype']);
        $this->db->where('tableName','Staff');
        $this->db->where('allowed','staffIncident');
        $uperStuDE=$this->db->get('usergrouppermission');  
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
	public function index($page='staff-self-evaluation-form')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['user_group']=$this->main_model->fetch_user_group_list();
        $this->load->view('home-page/'.$page,$data);
	}
    public function submit_request()
    {   
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('etc/UTC');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $date = gmdate("Y-m-d h:i A", $dt->format('U'));
        $user=$this->session->userdata('username');
        $dataArray=array();
        if($this->input->is_ajax_request()){
            if($this->input->post('self_question')){
                $evaluation_question_name=$this->input->post('self_question',TRUE);
                $self_evaluation_question_for=$this->input->post('department_name',TRUE);
                $question_title=$this->input->post('question_title',TRUE);
                $evaluation_question_name = xss_clean($evaluation_question_name);
                $self_evaluation_question_for = xss_clean($self_evaluation_question_for);
                $question_title = xss_clean($question_title);
                for($i=0;$i<count($self_evaluation_question_for);$i++){
                    $self_evaluation_question_fors=$self_evaluation_question_for[$i];
                    $queryCheck=$this->main_model->check_evaluation_question($evaluation_question_name,$self_evaluation_question_fors);
                    if($queryCheck){
                        $dataArray[]=array(
                            'question_title'=>$question_title,
                            'question_name'=>$evaluation_question_name,
                            'question_to'=>$self_evaluation_question_fors,
                            'date_posted'=>$date,
                            'posted_by'=>$user
                        );
                        
                    }
                }
                $query=$this->db->insert_batch('self_evaluation_questions',$dataArray);
                if($query){
                    echo '1';
                }else{
                    echo '0';
                }
            }
        }
    }
    function fetch_self_evaluation_questions_title(){
        $user=$this->session->userdata('username');
        echo $this->main_model->fetch_self_evaluation_questions_title($user);
    }
    function fetch_self_evaluation_questions(){
        $user=$this->session->userdata('username');
        if($this->input->is_ajax_request()){
            if($this->input->post('question_title')){
                $question_title=$this->input->post('question_title',TRUE);
                $question_title = xss_clean($question_title);
                echo $this->main_model->fetch_self_evaluation_questions($user,$question_title);
            }
        }
    }
    function delete_self_evaluation_question(){
        if($this->input->post('requestid')){
            $requestid=$this->input->post('requestid',TRUE);
            $requestid = xss_clean($requestid);
            $this->db->where('id',$requestid);
            $queyDelete=$this->db->delete('self_evaluation_questions');
            if($queyDelete){
                $this->db->where('question_id',$requestid);
                $queyDelete=$this->db->delete('self_evaluation_questions_answer');
            }
        }
    }
    function fetch_self_evaluation_report(){
        $postData = $this->input->post();
        $data = $this->main_model->fetch_self_evaluation_report($postData);
        echo json_encode($data);
    }
    function view_detail_this_staff_self_evaluation_answer(){
        if($this->input->post('requestid')){
            $question_title=$this->input->post('requestid',TRUE);
            $question_title = xss_clean($question_title);
            $staffName=$this->input->post('staffName',TRUE);
            $staffName = xss_clean($staffName);
            echo $this->main_model->view_detail_this_staff_self_evaluation_answer($question_title,$staffName);
        }
    }
}
