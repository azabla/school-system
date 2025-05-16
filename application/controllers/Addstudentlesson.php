<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Addstudentlesson extends CI_Controller {
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
	public function index($page='addlesson')
	{
         if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $config['upload_path']    = './lessonworksheet/';
        $config['allowed_types']  = 'docx|pdf';
        $this->load->library('upload', $config);
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['addlessonnow'])){
          $subject=$this->input->post('subject');
          $gradesec=$this->input->post('gradesec');
          $title=$this->input->post('title');
          $note=$this->input->post('note');
          $this->upload->do_upload('pdfdoc');
          $notepdf= $this->upload->data('file_name');
          if($notepdf ==''){
            $query=$this->main_model->insert_lesson($user,$subject,$gradesec,$title,$note,$max_year);
          }else{
            $query=$this->main_model->insert_lesson2($user,$subject,$gradesec,$title,$notepdf,$max_year);
          }
          if($query){
            $this->session->set_flashdata('success','Lesson Inserted successfully.');
             redirect('Addstudentlesson/');
          }else{
            $this->session->set_flashdata('error','Something wrong please try again.');
            redirect('Addstudentlesson/');
          }
        }
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['fetch_gradesec']=$this->main_model->fetch_session_gradesec($user,$max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['posts']=$this->main_model->fetch_post();
		    $this->load->view('teacher/'.$page,$data);
	} 

}