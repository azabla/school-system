<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Replayworksheet extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        if($this->session->userdata('username') == '' || $userLevel!='3'){
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
	public function index($page='replayworksheet')
	{
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
          show_404();
        }
        $this->load->model('main_model');
        $config['upload_path']='./answeredworksheet/';
        $config['allowed_types']  = 'docx|pdf';
        $this->load->library('upload', $config);
        $user=$this->session->userdata('username');

        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $query_gradesec = $this->db->query("select * from users where username='$user'");
        $row_gradesec = $query_gradesec->row();
        $id=$row_gradesec->id;
        $grade=$row_gradesec->grade;
        $gradesec=$row_gradesec->gradesec;

        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        if(isset($_POST['answerworksheet'])){
          $subject=$this->input->post('subject');
          $title=$this->input->post('title');
          $this->upload->do_upload('pdfdoc');
          $notepdf= $this->upload->data('file_name');
          if($notepdf !=''){
            $query=$this->main_model->answer_worksheet($id,$gradesec,$subject,$title,$notepdf,$max_year);
          }
          if($query){
            $this->session->set_flashdata('success','Worksheet Submitted successfully.');
            redirect('Replayworksheet/');
          }else{
            $this->session->set_flashdata('error','Something wrong please try again.');
            redirect('Replayworksheet/');
          }
        }
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['fetch_gradesec']=$this->main_model->fetch_my_subject($grade,$max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['posts']=$this->main_model->fetch_post();
		    $this->load->view('student/'.$page,$data);
	} 
}