<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myexam extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('security');
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
    public function index($page='exam')
    {
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
            show_404();
        }
        $this->load->helper('date');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['fetch_gradesec']=$this->main_model->fetch_session_gradesec($user,$max_year);
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('student/'.$page,$data);
    } 
    function fetch_mynew_exam(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->is_ajax_request()){
            $user=$this->session->userdata('username');
            $this->db->select('branch,grade,id');
            $this->db->where('username',$user);
            $this->db->where('academicyear',$max_year);
            $query_branch=$this->db->get('users');
            if($query_branch->num_rows()>0){
                $row_branch = $query_branch->row();
                $branch=$row_branch->branch;
                $grade=$row_branch->grade;
                $sid=$row_branch->id;
                echo $this->main_model->fetch_my_exam($sid,$grade,$max_year);
            }else{
                echo '<div class="alert alert-light">No data found</div>';
            }
        }
    }
    function start_live_exam(){
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('UTC');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $datetried = gmdate("Y-m-d h:i A", $dt->format('U'));
        $data=array();
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $user=$this->session->userdata('username');
        if($this->input->is_ajax_request()){
            $this->db->select('branch,grade,id');
            $this->db->where('username',$user);
            $this->db->where('academicyear',$max_year);
            $query_branch=$this->db->get('users');
            if($query_branch->num_rows()>0){
                $row_branch = $query_branch->row();
                $branch=$row_branch->branch;
                $grade=$row_branch->grade;
                $sid=$row_branch->id;
                if($this->input->post('subject')){
                    $subject=$this->input->post('subject',TRUE);
                    $stuid=$this->input->post('stuid',TRUE);
                    $examName=$this->input->post('examName',TRUE);
                    $grade=$this->input->post('grade',TRUE);
                    $year=$this->input->post('year',TRUE);

                    $subject=xss_clean($subject);
                    $stuid=xss_clean($stuid);
                    $examName=xss_clean($examName);
                    $grade=xss_clean($grade);
                    $year=xss_clean($year);
                    if($sid==$stuid){
                        $query=$this->main_model->insert_usertrial($stuid,$examName,$subject,$year,$datetried);
                        if($query){
                            $data=array(
                                'stuid'=>$stuid,
                                'triedsubject'=>$subject,
                                'triedexam'=>$examName,
                                'academicyear'=>$year,
                                'datetried'=>$datetried
                            );
                        }
                        $this->db->insert('examtried',$data);
                        echo $this->main_model->read_exam($examName,$subject,$grade,$year);
                    }else{
                        redirect('myexam','refresh');
                    }
                }else{
                    redirect('myexam','refresh');
                }
            }
        }
    }
    function submit_mylive_exam_answer(){
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('UTC');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $datetime = gmdate("Y-m-d h:i A", $dt->format('U'));
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->is_ajax_request()){
            $this->db->select('branch,grade,id');
            $this->db->where('username',$user);
            $this->db->where('academicyear',$max_year);
            $query_branch=$this->db->get('users');
            if($query_branch->num_rows()>0){
                $row_branch = $query_branch->row();
                $branch=$row_branch->branch;
                $grade=$row_branch->grade;
                $sid=$row_branch->id;
                if($this->input->post('subject')){
                    $myanswer=$_POST['myanswer'];
                    $eid=$this->input->post('eid',TRUE);
                    $subject=$this->input->post('subject',TRUE);
                    $examname=$this->input->post('examName',TRUE);

                    $myanswer=xss_clean($myanswer);
                    $eid=xss_clean($eid);
                    $subject=xss_clean($subject);
                    $examname=xss_clean($examname);
                    echo $this->main_model->my_answer($sid,$subject,$examname,$eid,$myanswer,$datetime,$max_year);
                }
            }
        }
    }
}