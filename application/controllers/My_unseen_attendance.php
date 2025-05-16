<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_unseen_attendance extends CI_Controller {
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
        $query_gradesec = $this->db->query("select * from users where username='$user'");
        $row_gradesec = $query_gradesec->row();
        $grade=$row_gradesec->grade;
        $gradesec=$row_gradesec->gradesec;
        $id=$row_gradesec->id;
        $branch1=$row_gradesec->branch;
        if(isset($_POST['view'])){
            if($_POST['view']!=''){
                $this->chat_model->update_myunseen_attendance($user);
            }
            $show=$this->chat_model->fetch_allmyattendance($user);
            $result['notification']=$show;
            $tot=$this->chat_model->fetch_allunseetseen_myattendance($user);
            $result['unseen_notification']=$tot;
            echo json_encode($result);
        } 
	}    
}