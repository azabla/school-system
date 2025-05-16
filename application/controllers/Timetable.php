<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Timetable extends CI_Controller {
    public function __construct(){
        parent::__construct();
        ob_start();
        $this->load->helper('cookie');
        $this->load->model('main_model');
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
	public function index($page='timetable')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
           show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');

        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('home-page/'.$page,$data);    
	} 
    function loadTimeTable(){
        echo $this->main_model->loadTimeTable();
         
    }
    function creating_period_names(){
        if($this->input->post('periods')){
            $periods=$this->input->post('periods');
            echo $this->main_model->create_periods($periods);
        }  
    }
    function generatetimeTable(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $days_name=array();
        if($this->input->post('dayss')){
            $dayss=$this->input->post('dayss');
            $branch=$this->input->post('branch');
            for($i=0;$i<count($dayss);$i++){
                $days_name[]=$dayss[$i];
            }
            $this->db->where('t_branch',$branch);
            $this->db->delete('timetable');
            echo $this->main_model->timeTable($max_year,$days_name,$branch);
        }
    }
    function save_period_names(){
        if($this->input->post('periodID')){
            $periodID=$this->input->post('periodID');
            $startTime=$this->input->post('startTime');
            $endTime=$this->input->post('endTime');
            echo $this->main_model->insert_period_data($periodID,$startTime,$endTime);
        }
    }
    function update_period_names(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('periodID')){
            $periodID=$this->input->post('periodID');
            $startTime=$this->input->post('startTime');
            $endTime=$this->input->post('endTime');
            if(!empty($startTime) || !empty($endTime)){
                for ($i=0; $i < count($periodID); $i++) {  
                    $id=$periodID[$i];
                    $start_Time=$startTime[$i];
                    $end_Time=$endTime[$i];
                    $data[]=array(
                        'period_name'=>$id,
                        'start_time'=>$start_Time,
                        'end_time'=>$end_Time
                   );  
                }
                $query=$this->db->update_batch('period_names',$data,'period_name');
                if($query){
                    echo '1';
                }else{
                    echo '0';
                }
            }
        }
    }
    function delete_period_names(){
        if($this->input->post('periods')){
            $periods=$this->input->post('periods');
            echo $this->main_model->delete_period_names($periods);
        }
    }
    function fetch_lessons_per_week(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetch_lessons_per_week($max_year);
    }
    function update_teachercredit_onUpdate_lessons(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staff')){
            $staff=$this->input->post('staff');
            $countLessons=$this->db->query("SELECT SUM(lessons_week) AS lessons_weeks FROM staffplacement where staff='$staff' and academicyear ='$max_year' ");
            $count_Row=$countLessons->row();
            $total_Lessons=$count_Row->lessons_weeks;
            echo $total_Lessons;
        }
    }
    function update_lessons_per_week(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('value')){
          $academicyear=$this->input->post('academicyear');
          $value=$this->input->post('value');
          $subject=$this->input->post('subject');
          $grade=$this->input->post('grade');
          $data=array(
            'lessons_week'=>$value,
            'academicyear'=>$academicyear,
            'subject'=>$subject,
            'grade'=>$grade
          );
          echo $this->main_model->update_lessons_per_week($value,$academicyear,$subject,$grade,$data);
        }
    }
    function timetable_for_eachclass(){
        if($this->input->post('printType')){
            $printType=$this->input->post('printType');
            $branch=$this->input->post('branch');
            if($printType=='timeTable_eachClass'){
                echo $this->main_model->printType_eachClass($branch);
            }else if($printType=='timeTable_eachTeacher'){
                echo $this->main_model->printType_eachTeacher($branch);
            }
            else{
                echo 'No record found';
            }
        }
    }
    function edit_for_eachclass(){
        if($this->input->post('printType')){
            $printType=$this->input->post('printType');
            $branch=$this->input->post('branch');
            if($printType=='timeTable_eachClass'){
                echo $this->main_model->editType_eachClass($branch);
            }else if($printType=='timeTable_eachTeacher'){
                echo $this->main_model->printType_eachTeacher($branch);
            }
            else{
                echo 'No record found';
            }
        }
    }
    function save_timeTable_Days(){
        if($this->input->post('timeTabeDays')){
            $timeTabeDays=$this->input->post('timeTabeDays');
            $queryCheck=$this->db->query("select * from timetable_days where timetable_days='$timeTabeDays' ");
            if($queryCheck->num_rows()>0){
                $this->db->where('timetable_days',$timeTabeDays);
                $queryDelete=$this->db->delete('timetable_days');
                if($queryDelete){
                    echo 'Deleted';
                }else{
                    echo 'Oooops Please try again';
                }
            }else{
                $data=array(
                    'timetable_days'=>$timeTabeDays,
                    'date_created'=>date('M-d-Y')
                );
                $queryInsert=$this->db->insert('timetable_days',$data);
                if($queryInsert){
                    echo 'Saved';
                }else{
                    echo 'Oooops Please try again';
                }
            }
        }
    }
    function fetch_timetable_days(){
        echo $this->main_model->fetch_timetable_days();
    }
    function on_subject_status(){
        $user=$this->session->userdata('username');
        if($this->input->post('grade')){
          $grade=$this->input->post('grade');
          $subject=$this->input->post('subject');
          $academicYear=$this->input->post('academicYear');
          $this->db->where('academicyear',$academicYear);
          $this->db->where('grade',$grade);
          $this->db->where('subject',$subject);
          $this->db->set('status','1');
          $this->db->update('staffplacement');
        }
    }
    function off_subject_status(){
        $user=$this->session->userdata('username');
        if($this->input->post('grade')){
          $grade=$this->input->post('grade');
          $subject=$this->input->post('subject');
          $academicYear=$this->input->post('academicYear');
          $this->db->where('academicyear',$academicYear);
          $this->db->where('grade',$grade);
          $this->db->where('subject',$subject);
          $this->db->set('status','0');
          $this->db->update('staffplacement');
        }
    }
    function delete_this_timetable_period(){
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $period=$this->input->post('period');
            $subject=$this->input->post('subject');
            $branch=$this->input->post('branch');
            $dayName=$this->input->post('dayName');
            $this->db->where('grade',$grade);
            $this->db->where('subject',$subject);
            $this->db->where('t_branch',$branch);
            $this->db->where('period',$period);
            $this->db->where('lessonday',$dayName);
            $query=$this->db->delete('timetable');
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        }
    }
    function addedit_this_timetable_period(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $period=$this->input->post('period');
            $branch=$this->input->post('branch');
            $dayName=$this->input->post('dayName');
            echo $this->main_model->fetch_timetale_toedit($grade,$branch,$period,$dayName,$max_year);
        }
    }
    public function fetch_subject_for_staffs()
    {
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staff')){
            $staff=$this->input->post('staff');
            $grade=$this->input->post('grade');
            echo $this->main_model->fetch_subject_for_staffs($staff,$grade,$max_year);
        }
    }
    function savechanges_this_timetable_period(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $period=$this->input->post('period');
            $branch=$this->input->post('branch');
            $dayName=$this->input->post('dayName');
            $staff=$this->input->post('staff');
            $subject=$this->input->post('subject');
            $queryFetch=$this->db->query("select * from timetable where t_branch='$branch' and lessonday='$dayName' and period='$period' and teacher='$staff' ");
            if($queryFetch->num_rows()<1){
                $data=array(
                    'grade'=>$grade,
                    'teacher'=>$staff,
                    'subject'=>$subject,
                    'lessonday'=>$dayName,
                    'period'=>$period,
                    't_branch'=>$branch
                );
                $query= $this->db->insert('timetable',$data);
                if($query){
                    echo '1';
                }else{
                    echo '0';
                }
            }else{
                echo '0';
            }
        }
    }
}