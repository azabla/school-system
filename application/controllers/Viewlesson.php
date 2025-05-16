<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewlesson extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='homeworkworksheet' order by id ASC ");
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='viewlesson')
	{
         if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['deletelesson'])){
          $id=$this->input->post('deletelesson');
          $this->load->model('main_model');
          $this->main_model->delete_lesson($id);
        }
        $accessbranch = sessionUseraccessbranch();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['fetch_gradesec']=$this->main_model->fetch_session_gradesec($user,$max_year);
        $data['schools']=$this->main_model->fetch_school();
        if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
            $data['lesson']=$this->main_model->fetch_lesson($max_year,$user);
        }else{
            $data['lesson']=$this->main_model->fetch_milesson($max_year,$user);
        }
		$this->load->view('home-page/'.$page,$data);
	}
    public function download($id){
        if(!empty($id)){
            $this->load->helper('download');
             $file = 'lessonworksheet/'.$id;
            force_download($file, NULL); 
            redirect('Viewlesson/','refresh');
        }
    }
}