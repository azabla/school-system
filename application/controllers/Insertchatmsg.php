<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insertchatmsg extends CI_Controller {
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        } 
    }
	public function index()
	{
        $this->load->model('chat_model');
        $unique_id=$this->session->userdata('unique_id');
        if($this->input->post('msg')){
            $outgoing_id=$this->input->post('outgoing_id');
            $msg=$this->input->post('msg');
            echo $this->chat_model->insertchatmsg($outgoing_id,$msg,$unique_id);   
        }   
	}   
}