<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_unseen_notification extends CI_Controller {
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
        if($usertype==trim('Admin') || $usertype==trim('superAdmin') ){
            if(isset($_POST['view'])){
                if($_POST['view']!=''){
                    /*$this->chat_model->update_unseen_notification();*/
                    $this->chat_model->update_unseen_incident_report();
                }
                $uperStuRequest=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentRequest' order by id ASC ");
                if($uperStuRequest->num_rows()>0){
                    $showRequest=$this->chat_model->fetch_student_request($max_year);
                }else{
                    $showRequest='';
                }
                $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentIncident' order by id ASC "); 
                if($uperStuDE->num_rows()>0){
                    $showIncident=$this->chat_model->fetch_all_incident($max_year);
                }else{
                    $showIncident='';
                }
                $show=$this->chat_model->fetch_allnotification($max_year);
                $groupNotification=$show.$showRequest.$showIncident;
                $result['notification']=$groupNotification;
                $uperStuRequest=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentRequest' order by id ASC ");
                if($uperStuRequest->num_rows()>0){
                    $totRequest=$this->chat_model->count_unseen_request_notification($max_year);
                }else{
                   $totRequest=0; 
                }
                $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentIncident' order by id ASC "); 
                if($uperStuDE->num_rows()>0){
                    $totIncident=$this->chat_model->count_unseen_incident($max_year);
                }else{
                    $totIncident=0;
                }
                $tot=$this->chat_model->fetch_unseen_notification($max_year);
                $allCountNotification=$tot + $totRequest + $totIncident;
                $result['unseen_notification']=$allCountNotification;
                echo json_encode($result);
            }
        }
	}    
}