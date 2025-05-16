<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Readmyexam extends CI_Controller {
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        }    
    }
	public function index($page='readexam')
	{
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $this->load->helper('date');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_gradesec = $this->db->query("select * from users where username='$user'");
        $row_gradesec = $query_gradesec->row();
        $grade=$row_gradesec->grade;
        $gradesec=$row_gradesec->gradesec;
        $sid=$row_gradesec->id;
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('etc/GMT-10');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $datetried = gmdate("Y-m-d h:i A", $dt->format('U'));
        if(isset($_POST['readmore'])){
            $id=$this->input->post('readmore');
            $subject=$this->input->post('subject');
            $query=$this->main_model->insert_usertrial($sid,$id,$subject,$max_year,$datetried);
            if($query){
                $data=array(
                'stuid'=>$sid,
                'triedsubject'=>$subject,
                'triedexam'=>$id,
                'academicyear'=>$max_year,
                'datetried'=>$datetried
            );
            $this->db->insert('examtried',$data);
            }
            else{
                redirect('myexam','refresh');
            }
            $data['sessionuser']=$this->main_model->fetch_session_user($user);
            /*$data['readlesson']=$this->main_model->read_exam($id,$subject,$grade,$max_year);*/
            $data['academicyear']=$this->main_model->academic_year_filter();
            $data['schools']=$this->main_model->fetch_school();
		    $this->load->view('student/'.$page,$data);
        }
        else{
            redirect('myexam','refresh');
        }
	} 
}