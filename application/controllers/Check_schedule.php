<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Check_schedule extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('schedule_model');
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        }
    }
	public function index()
	{
        
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        if($usertype==trim('Admin') || $usertype==trim('superAdmin') ){
            if(isset($_POST['view'])){
                $show=$this->schedule_model->check_today_schedule();
                $result['notification']=$show;

            /*    $totRequest=$this->chat_model->count_unseen_request_notification();
                $tot=$this->chat_model->fetch_unseen_notification();
                $allCountNotification=$tot + $totRequest;
                $result['unseen_notification']=$allCountNotification;*/
                echo json_encode($result);
            }
        }
	}    
}