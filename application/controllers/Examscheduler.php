<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Examscheduler extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='taskspage' order by id ASC ");
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='examschedule')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
           show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $query_quarter = $this->db->query("select max(quarter) as quarter from mark");
        $row_quarter = $query_quarter->row();
        $max_quarter=$row_quarter->quarter;
        if(isset($_POST['startscheduler'])){
           $data['scheduler']=$this->main_model->examschedule($max_year,$max_quarter);
        }
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['subject']=$this->main_model->fetchSubjectExam($max_year);
        $this->load->view('home-page/'.$page,$data);    
	}
    function doExamSchedule(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('dayInfo')){
            $dayInfo=$this->input->post('dayInfo');
            $subjectGrade=$this->input->post('subjectGrade');
            $noExams=$this->input->post('noExams');
            $doLunch=$this->input->post('doLunch');
            $doBreak=$this->input->post('doBreak');
            $periodInfo=0;
            for($i=0;$i<count($dayInfo);$i++){
                $selectedDay=$dayInfo[$i];
                for($j=0;$j<count($subjectGrade);$j++){
                    $selectedSub=$subjectGrade[$j];
                    $queryGrade=$this->main_model->fetchSubjectOfThiGrade($selectedSub,$max_year);
                    foreach($queryGrade as $queryGrades){
                        $gradesec=$queryGrades->gradesec;
                        $grade=$queryGrades->grade;
                        $queryTeacher=$this->main_model->checkExaminerteacher($gradesec,$max_year);
                        foreach($queryTeacher as $queryTeachers){
                            $teacher=$queryTeachers->fname.' '.$queryTeachers->mname;
                            $queryCheck=$this->main_model->checkExamSchedule($selectedSub,$gradesec,$max_year);
                            if($queryCheck){
                                $countSubject=$this->main_model->checkSubjectPerDay($selectedDay,$noExams,$gradesec,$max_year);
                                if($countSubject){
                                    $periodInfo=$j + 1;
                                    $queryTeacherFound=$this->main_model->checTeacherSchedule($selectedDay,$teacher,$periodInfo,$max_year);
                                    if($queryTeacherFound->num_rows()>0){
                                        $data=array(
                                            'dayinfo'=>$selectedDay,
                                            'gradeinfo'=>$grade,
                                            'gradesecinfo'=>$gradesec,
                                            'subinfo'=>$selectedSub,
                                            'teacherinfo'=>'-',
                                            'nofexam'=>$noExams,
                                            'includelunch'=>$doLunch,
                                            'includebreak '=>$doBreak,
                                            'periodinfo'=>$periodInfo,
                                            'academicyear'=>$max_year,
                                            'datecreated'=>date('M-d-Y'),
                                            'createdby'=>$user
                                        );
                                    }else{
                                        $data=array(
                                            'dayinfo'=>$selectedDay,
                                            'gradeinfo'=>$grade,
                                            'gradesecinfo'=>$gradesec,
                                            'subinfo'=>$selectedSub,
                                            'teacherinfo'=>$teacher,
                                            'nofexam'=>$noExams,
                                            'includelunch'=>$doLunch,
                                            'includebreak '=>$doBreak,
                                            'periodinfo'=>$periodInfo,
                                            'academicyear'=>$max_year,
                                            'datecreated'=>date('M-d-Y'),
                                            'createdby'=>$user
                                        );
                                    }
                                    $query=$this->db->insert('examschedule',$data);
                                }
                            } 
                        }
                    }
                }
            }
            if($query){
                $query=$this->db->query("select * from examschedule where academicyear='$max_year' group by periodinfo order by periodinfo  ASC");
                foreach($query->result() as $fetchQuery){
                    $periodInfo=$fetchQuery->periodinfo;
                    $periodInfo2=$fetchQuery->periodinfo;
                    $nofexam=$fetchQuery->nofexam;
                    if($periodInfo > $nofexam){
                        $checkNumber=fmod($periodInfo, $nofexam);
                       if($checkNumber == 0){
                            $this->db->where('academicyear',$max_year);
                            $this->db->where('periodinfo',$periodInfo2);
                            $this->db->set('periodinfo',$nofexam);
                            $this->db->update('examschedule');
                        }else{
                            $this->db->where('academicyear',$max_year);
                            $this->db->where('periodinfo',$periodInfo2);
                            $this->db->set('periodinfo',$checkNumber);
                            $this->db->update('examschedule');
                        }
                    } 
                }
                echo '<div class="alert alert-info alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                   <i class="fas fa-exclamation-circle"> </i> Generated succesfully.
                </div></div>';
            }
        }
    }  
}