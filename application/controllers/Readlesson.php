<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Readlesson extends CI_Controller {
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        } 
    }
	public function index($page='readlesson')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
        {
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        if(isset($_POST['readmore'])){
            $id=$this->input->post('readmore');
            $data['sessionuser']=$this->main_model->fetch_session_user($user);
            $data['readlesson']=$this->main_model->read_lesson($id);
            $data['academicyear']=$this->main_model->academic_year_filter();
            $data['schools']=$this->main_model->fetch_school();
		    $this->load->view('home-page/'.$page,$data);
        }
        else{
            redirect('viewlesson/','refresh');
        }
	}
}