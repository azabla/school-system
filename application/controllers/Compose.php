<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compose extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('security');
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $this->db->where('usergroup',$_SESSION['usertype']);
        $this->db->where('allowed','Chat');
        $usergroupPermission=$this->db->get('usergrouppermission');  
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows() < 1 || $userLevel!='1'){
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
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['usertype']=$this->main_model->fetch_usertype();
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('home-page/'.$page,$data);
    }
    function fetchUsertype(){
        $user=$this->session->userdata('username');
        if(isset($_POST['usertype'])){
            $usertype=$this->input->post('usertype',TRUE);
            $usertype=xss_clean($usertype);
            if($usertype ===trim('Student')){
                echo $this->main_model->fetchGradesofUserStudent($usertype); 
            }else{
                echo $this->main_model->fetch_usertype_users($usertype); 
            } 
        }
        if(isset($_POST['grade'])){
            $grade=$this->input->post('grade');
            echo $this->main_model->fetchThisGradeStudents($grade); 
        }
    } 
    function save_token()
    {
        $this->main_model->save_token();
    }
    function checkNewUserFound(){
        $user=$this->session->userdata('username');
        $tot=$this->main_model->fetchAllMyNewUserNotification();
        if($tot->num_rows()>0){
            foreach($tot->result() as $myMess){
                $title =$myMess->fname.' '.$myMess->lname;
                $body =$myMess->usertype;
                
                $query1 = $this->db->query("SELECT distinct token FROM `notification_tokens_tbl` where delete_status=? ");
                $query=$this->db->query($query1,array('N'));
                $query_res = $query->result();
                foreach ($query_res as $query_res_data) {
                    $registrationIds[] =$query_res_data->token;
                }
                
                $url ="https://fcm.googleapis.com/fcm/send";
                //"to" for single user
                //"registration_ids" for multiple users
                //$title = "$user";
                //$body = "New Message has been found.";
                $BaseUrl=base_url();
                $schools=$this->main_model->fetch_school();
                foreach($schools as $school){
                    $icon = ''.$BaseUrl.'logo/'.$school->logo.'';
                }
                $click_action = ''.$BaseUrl.'inbox/';
                    $fields=array(
                        "registration_ids"=>$registrationIds,
                        "notification"=>array(
                        "body"=>$body,
                        "title"=>$title,
                        "icon"=>$icon,
                        "click_action"=>$click_action
                        )
                    );
                    //print_r($fields);
                    //exit;
                    
                    $headers=array(
                    'Authorization: key=AAAAv9-EmBs:APA91bFLeVp7y35RHreE5Vwxk5mq-cyckoe062CebxPlfdJgYd1Eh_KqU4uRuHCTLOk-NV8gpO7-GBxorqTAgpwN9WexbZR1sZBOvBdLyr2V46OG7-M_NLOuCvWSFW2H1AWlehRpBAGu',
                    'Content-Type:application/json'
                    );
                
                    $ch=curl_init();
                    curl_setopt($ch,CURLOPT_URL,$url);
                    curl_setopt($ch,CURLOPT_POST,true);
                    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
                    $result=curl_exec($ch);
                    print_r($result);
                    curl_close($ch);
            }
        }
    }
    function sendNotification(){
        $user=$this->session->userdata('username');
        $tot=$this->main_model->fetchAllMyMessages($user);
        if($tot->num_rows()>0){
            foreach($tot->result() as $myMess){
                $title =$myMess->subject;
                $body =$myMess->message;
                
                $query1 = $this->db->query("SELECT distinct token FROM `notification_tokens_tbl` where delete_status=? ");
                $query=$this->db->query($query1,array('N'));
                $query_res = $query->result();
    
                foreach ($query_res as $query_res_data) {
                    $registrationIds[] =$query_res_data->token;
                }
                
                $url ="https://fcm.googleapis.com/fcm/send";
                //"to" for single user
                //"registration_ids" for multiple users
                //$title = "$user";
                //$body = "New Message has been found.";
                $BaseUrl=base_url();
                $schools=$this->main_model->fetch_school();
                foreach($schools as $school){
                    $icon = ''.$BaseUrl.'logo/'.$school->logo.'';
                }
                $click_action = ''.$BaseUrl.'inbox/';
                    $fields=array(
                        "registration_ids"=>$registrationIds,
                        "notification"=>array(
                        "body"=>$body,
                        "title"=>$title,
                        "icon"=>$icon,
                        "click_action"=>$click_action
                        )
                    );
                    
                    $headers=array(
                    'Authorization: key=AAAAv9-EmBs:APA91bFLeVp7y35RHreE5Vwxk5mq-cyckoe062CebxPlfdJgYd1Eh_KqU4uRuHCTLOk-NV8gpO7-GBxorqTAgpwN9WexbZR1sZBOvBdLyr2V46OG7-M_NLOuCvWSFW2H1AWlehRpBAGu',
                    'Content-Type:application/json'
                    );
                
                    $ch=curl_init();
                    curl_setopt($ch,CURLOPT_URL,$url);
                    curl_setopt($ch,CURLOPT_POST,true);
                    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
                    $result=curl_exec($ch);
                    print_r($result);
                    curl_close($ch);
            }
        }
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