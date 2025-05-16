<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetchtochat extends CI_Controller {
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
        if($this->input->post('id')){
            $id=$this->input->post('id');
            echo $this->chat_model->fetchuser_tochat($unique_id,$id);   
        }   
	}   
}