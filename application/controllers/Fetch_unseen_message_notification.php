<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_unseen_message_notification extends CI_Controller {
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
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $user=$this->session->userdata('username');
        if(isset($_POST['view'])){
            if($_POST['view']!=''){
            $this->chat_model->update_unseen_message_notification($user);
            }
            if($userLevel==1){
                $show=$this->chat_model->fetch_allmessages1($user);
                $result['notification']=$show;
            }else if($userLevel==2){
                $show=$this->chat_model->fetch_allmessages2($user);
                $result['notification']=$show;
            }else{
                $show=$this->chat_model->fetch_allmessages3($user);
                $result['notification']=$show;
            }
            
            $tot=$this->chat_model->fetch_unseen_message_notification($user);
            $result['unseen_notification']=$tot;
            echo json_encode($result);
        }
	}    
}