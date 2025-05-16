<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_my_personal_unseen_notification extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('chat_model');
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        }
    }
	public function index()
	{
        
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['view'])){
            if($_POST['view']!=''){
                $this->chat_model->update_my_supervision_Attendance($user);
            }
            $show=$this->chat_model->fetch_allmy_supervision_attendance_notification($user,$max_year);
            $groupNotification=$show;
            $result['notification']=$groupNotification;
            $countsupervision_Attendance=$this->chat_model->count_supervision_Attendance($user,$max_year);
            $allCountNotification=$countsupervision_Attendance;
            $result['unseen_notification']=$allCountNotification;
            echo json_encode($result);
        }
	}    
}