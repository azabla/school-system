<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_payment_status extends CI_Controller {
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        }
    }
	public function index()
	{
        $this->load->model('main_model');
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
                $this->main_model->update_myunseen_payment($id);
            }
            $show=$this->main_model->fetch_allmypaymentstatus($id);
            $result['notification']=$show;
            
            $tot=$this->main_model->fetch_allunseetseen_mypayment($id);
            $result['unseen_notification']=$tot;
            echo json_encode($result);
        } 
	}    
}