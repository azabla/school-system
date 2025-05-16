<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Viewmyansweredworksheet extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='homeworkworksheet' order by id ASC ");
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='viewmyansweredworksheet')
	{
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php'))
        {
            show_404();
        }
        $this->load->model('main_model');
        $user=trim($this->session->userdata('username'));

        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $query_placement = $this->db->query("select * from staffplacement where academicyear ='$max_year' and staff='$user'");
        $row_placement = $query_placement->row();
        $place_grade=trim($row_placement->grade);
        $place_subject=trim($row_placement->subject);

        if(isset($_GET['lessonid']))
        {
          $id=$_GET['lessonid'];
          $this->load->model('main_model');
          $this->main_model->delete_lesson($id);
        }
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['fetch_gradesec']=$this->main_model->fetch_session_gradesec($user,$max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['answeredworksheet']=$this->main_model->fetch_myclass_answered_worksheet($user,$max_year);
		$this->load->view('teacher/'.$page,$data);
	}
    public function download($id){
        if(!empty($id)){
            $this->load->helper('download');
            $file = 'answeredworksheet/'.$id;
            force_download($file, NULL); 
            redirect('Viewmyansweredworksheet/','refresh');
        }
    }
}