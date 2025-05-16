<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newexamform extends CI_Controller {
    public function __construct(){
        parent::__construct();
      if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('Login/');
        }
        
    }
	public function index($page='New-exam-form')
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
        $accessbranch = sessionUseraccessbranch();
        if(isset($_POST['startmark'])){
            $academicyear=$this->input->post('academicyear');
            $gradesec=$this->input->post('gradesec');
            $subject=$this->input->post('subject');
            $evaluation=$this->input->post('evaluation');
            $branch1=$this->input->post('branch');
            $quarter=$this->input->post('quarter');
            $assesname=$this->input->post('assesname');
            $percentage=$this->input->post('percentage');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $data['fetch_thisgrade_students_fornewexam']=$this->main_model->fetch_thisgrade_students_fornewexam($gradesec,$max_year,$branch1);
                $data['exam_details']=array($academicyear,$gradesec,$subject,$evaluation,$quarter,$assesname,$percentage,$branch1);
            }else{
                $data['fetch_thisgrade_students_fornewexam']=$this->main_model->fetch_thisgrade_mystudents_fornewexam($gradesec,$max_year,$branch);
                $data['exam_details']=array($academicyear,$gradesec,$subject,$evaluation,$quarter,$assesname,$percentage,$branch);
            }
        }else{
            redirect('Addexam/','refresh');
        }
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['fetch_evaluation']=$this->main_model->fetch_evaluation_fornewexam($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['fetch_grade_fromsp_toadd_neweaxm']=$this->main_model->fetch_grade_from_staffplace($user);
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('home-page/'.$page,$data);
	} 
}