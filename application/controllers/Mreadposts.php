<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mreadposts extends CI_Controller {
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        }
        
    }
	public function index($page='read-more')
	{
         if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        if(isset($_POST['readmore'])){
            $this->load->model('main_model');
            $id=$this->input->post('readmore');
            $data['sessionuser']=$this->main_model->fetch_session_user($user);
            $data['read_more']=$this->main_model->read_more($id);
            $data['academicyear']=$this->main_model->academic_year_filter();
            $data['schools']=$this->main_model->fetch_school();
		    $this->load->view('teacher/'.$page,$data);
        }
        else{
            redirect('teacher/','refresh');
        }
	} 

}