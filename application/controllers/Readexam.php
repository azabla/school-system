<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Readexam extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='addstudentmark' order by id ASC ");
        if($this->session->userdata('username') == '' || $uaddMark->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='readexam')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['readmore'])){
            $id=$this->input->post('readmore');
            $subject=$this->input->post('subject');
            $grade=$this->input->post('grade');
            $data['sessionuser']=$this->main_model->fetch_session_user($user);
            $data['academicyear']=$this->main_model->academic_year_filter();
            $data['schools']=$this->main_model->fetch_school();
		    $this->load->view('home-page/'.$page,$data);
        }
        else{
            redirect('viewexam/','refresh');
        }
	} 
}