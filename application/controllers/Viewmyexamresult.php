<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Viewmyexamresult extends CI_Controller {
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('Login/');
        }
    }
    public function index($page='result')
    {
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $this->load->helper('date');
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $grade=$row_branch->grade;
        $sid=$row_branch->id;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Addis_Ababa'));
        $datetime= $now->format('Y-m-d H:i:s');
        if(isset($_POST['viewresult'])){
            $subject=$this->input->post('subject');
            $examname=$this->input->post('viewresult');
            $data['exam']=$this->main_model->fetch_this_subjectresult($sid,$subject,$examname,$max_year);
            $data['sessionuser']=$this->main_model->fetch_session_user($user);
            $data['academicyear']=$this->main_model->academic_year_filter();
            $data['fetch_gradesec']=$this->main_model->fetch_session_gradesec($user,$max_year);
            $data['schools']=$this->main_model->fetch_school();
            $this->load->view('student/'.$page,$data);
        }
        else{
            redirect('myexamresult/','refresh');
        }
    } 
}