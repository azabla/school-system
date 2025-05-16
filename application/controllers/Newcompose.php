<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newcompose extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('security');
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
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
	public function index($page='compose')
	{
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
            show_404();
        }
        $this->db->where('usergroup',$_SESSION['usertype']);
        $this->db->where('allowed','Chat');
        $usergroupPermission=$this->db->get('usergrouppermission');  
        if($usergroupPermission->num_rows()<1){ 
            redirect('home/');
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['usertype']=$this->main_model->fetch_usertype();
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('student/'.$page,$data);
	} 
    function composeMessage(){
        $user=$this->session->userdata('username');
        if(isset($_POST['message_to'])){
            $message_to=$this->input->post('message_to',TRUE);
            $message_title=$this->input->post('message_title',TRUE);
            $message_content=$this->input->post('message_content',TRUE);
            $usertype=$this->input->post('usertype',TRUE);

            $message_to=xss_clean($message_to);
            $message_title=xss_clean($message_title);
            $message_content=xss_clean($message_content);
            $usertype=xss_clean($usertype);
            $datetoday=date('M-d-Y');
            for($i=0;$i<count($message_to);$i++){
                $check=$message_to[$i];
                $data=array(
                    'sender'=>$user,
                    'group_staffs'=>$usertype,
                    'receiver'=>$check,
                    'grade'=>'',
                    'subject'=>$message_title,
                    'message'=>$message_content,
                    'date_sent'=>$datetoday
                );
                $query=$this->db->insert('message',$data);
            }
            if($query){
                echo ' Message sent successfully.';
            }else{
                echo 'Ooops Please try again.';
            }
        } 
    }
}