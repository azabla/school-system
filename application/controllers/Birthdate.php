<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Birthdate extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        }  
    }
    public function index()
    {
        $user=$this->session->userdata('username');
        if(isset($_POST['view'])){
            $show= $this->main_model->birthdate();
            $data['notification']=$show;
            echo json_encode($data);
        }
    }   
}