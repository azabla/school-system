<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Examresult extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $this->load->helper('security');
        $userLevel = userLevel();
        $uaddMark1="SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='addstudentmark' order by id ASC ";
        $uaddMark=$this->db->query($uaddMark1,array($_SESSION['usertype'],'StudentMark','addstudentmark'));
        if($this->session->userdata('username') == '' || $uaddMark->num_rows()<1 || $userLevel!='1'){
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
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
        {
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['viewresult'])){
            $examname=$this->input->post('viewresult',TRUE);
            $subject=$this->input->post('view_exam_subject',TRUE);
            $grade=$this->input->post('view_exam_grade',TRUE);

            $examname=xss_clean($examname);
            $subject=xss_clean($subject);
            $grade=xss_clean($grade);

            $data['examresult']=$this->main_model->view_students_examresult($examname,$subject,$grade,$max_year);
            $data['sessionuser']=$this->main_model->fetch_session_user($user);
            $data['academicyear']=$this->main_model->academic_year_filter();
            $data['fetch_gradesec']=$this->main_model->fetch_session_gradesec($user,$max_year);
            $data['schools']=$this->main_model->fetch_school();
            $data['posts']=$this->main_model->fetch_post();
            $this->load->view('home-page/'.$page,$data);
        }
        else{
            redirect('viewexam/','refresh');
        }	    
	}
    public function not_completedReport(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $postData = $this->input->post();
        $data = $this->main_model->examnot_completedReport($max_year,$postData);
        echo json_encode($data);
    } 
    public function retryExam(){
        if($this->input->post('requestid')){
            $id=$this->input->post('requestid');
            $this->db->where('id',$id);
            $this->db->delete('examtried');
        }
    }
    public function fetch_student_detail_result(){
        if($this->input->post('subject')){
            $subject=$this->input->post('subject',TRUE);
            $examName=$this->input->post('examName',TRUE);
            $academicyear=$this->input->post('academicyear',TRUE);
            $stuid=$this->input->post('stuid',TRUE);
            $examName=xss_clean($examName);
            $subject=xss_clean($subject);
            $academicyear=xss_clean($academicyear);
            $stuid=xss_clean($stuid);
            echo $this->main_model->fetch_student_detail_result($subject,$examName,$academicyear,$stuid);
        }
    }
}