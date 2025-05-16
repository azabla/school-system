<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lineupschedule extends CI_Controller {
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
	public function index($page='lineupschedule')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
        {
          show_404();
        }
        
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year'");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['gradegroups']=$this->main_model->fetchDivForGradeGroup($max_year);
        $data['posts']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['staffs']=$this->main_model->fetch_students($max_year);
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function postLineupscheduleTeacher(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query2=$this->db->query("select max(term) as quarter,endate from quarter where Academic_Year='$max_year' ");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $endate=$row2->endate;
        $ConverEndDate = DateTime::createFromFormat('d/m/y', $endate); 
        $newDate=date('d-m-Y');
        $todayDate = date("d-m-Y", strtotime($newDate)); 
        $dateFrom=strtotime($todayDate);
        $dateTo=strtotime($ConverEndDate->format('d-m-Y'));
        if(isset($_POST['scheduleDays'])){
            $this->db->where('academicyear',$max_year);
            $this->db->delete('lineupschedule');
            $dataArray=array();
            $scheduleDays = $this->input->post('scheduleDays');
            $includeDirectors=$this->input->post('includeDirectors');
            $scheduleBranch=$this->input->post('scheduleBranch');
            foreach ($scheduleDays as $scheduleDay) {
                $dataArray[]=$scheduleDay;
            }
            $timeDiff = abs($dateTo - $dateFrom);
            $numberDays = $timeDiff/86400; 
            $numberDayss = intval($numberDays); 
            $arrayStudent=array();
            /*$arrayDate=array();*/
            $groups = [];
            $queryTeacher=$this->db->query("select branch,status2,id,fname,mname,lname from users where usertype='Teacher' and status='Active' and isapproved='1' and branch='$scheduleBranch' group by id order by RAND() ");
            foreach($queryTeacher->result() as $makeSchedule){
                $arrayStudent[]=array(
                    'tid'=>$makeSchedule->id,
                    'tdiv'=>$makeSchedule->status2
                );
            }
            /*for($currentDate = $dateFrom;$currentDate <= $dateTo;$currentDate += (86400)) {   
                $arrayDate[]=array(
                    'dateName'=>date('d-m-Y', $currentDate),
                    'dayName'=>date('l',$currentDate)
                );
                $nameToday=date('l',$currentDate); 
                $date = date('d-m-Y', $currentDate);
            }*/
            shuffle($arrayStudent); 
            
            /*shuffle($arrayDate); 
            $keys=array_keys($arrayStudent);
            $keyDate=array_keys($arrayDate);
            $countTeacher=0;
            $countDate=0;*/
            /*for($currentDate = $dateFrom;$currentDate <= $dateTo;$currentDate += (86400)) {
                $dateName=date('d-m-Y', $currentDate);
                $dayName=date('l',$currentDate);*/
                foreach($queryTeacher->result() as $makeSchedule){
                    $student=$makeSchedule->id;
                    $tdiv=$makeSchedule->status2;
                    $branch=$makeSchedule->branch;
                    $queryTeacherBranch=$this->db->query("select branch,status2,id,fname,mname,lname from users where usertype='Teacher' and status='Active' and isapproved='1' and branch='$scheduleBranch' group by id");
                    $noTeacher=$queryTeacherBranch->num_rows();
                    $noOfDaysToTeac=floor($numberDayss / $noTeacher);
                    /*$td=$position['tid'];
                    */
                    /*foreach($arrayDate[$keyDate[$i]] as $positionDate=>$studentDate) { 
                        $nameToday=$studentDate; */
                        /*$date = date('d-m-Y', $currentDate);*/
                        for($currentDate = $dateFrom;$currentDate <= $dateTo;) {
                            $dateName=date('d-m-Y', $currentDate);
                            $dayName=date('l',$currentDate);
                            if(in_array($dayName,$dataArray)){
                                $queryChk=$this->db->query("select * from lineupschedule where academicyear='$max_year' and linedate='$dateName' and divname='$tdiv' and branch='$scheduleBranch' group by divname ");
                                if($queryChk->num_rows() < 1){
                                    $data[]=array(
                                        'tid'=>$student,
                                        'branch'=>$scheduleBranch,
                                        'daysname'=>$dayName,
                                        'linedate'=>$dateName,
                                        'divname'=>$tdiv,
                                        'academicyear'=>$max_year,
                                        'createdby'=>$user,
                                        'datecreated'=>date('M-d-Y H:i:s')
                                    );
                                }
                                
                            }
                            $currentDate += ($noTeacher * 86400);
                        }
                        $dateFrom= $dateFrom + 86400;
                   /* }*/
                }
           /* }*/
            $query2=$this->db->insert_batch('lineupschedule',$data);
        }
    }
    function postLineupscheduleAll(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query2=$this->db->query("select max(term) as quarter,endate from quarter where Academic_Year='$max_year' ");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $endate=$row2->endate;
        $ConverEndDate = DateTime::createFromFormat('d/m/y', $endate); 
        $newDate=date('d-m-Y');
        $todayDate = date("d-m-Y", strtotime($newDate)); 
        $dateFrom=strtotime($todayDate);
        $dateTo=strtotime($ConverEndDate->format('d-m-Y'));
        if(isset($_POST['scheduleDays'])){
            $this->db->where('academicyear',$max_year);
            $this->db->delete('lineupschedule');
            $dataArray=array();
            $scheduleDays = $this->input->post('scheduleDays');
            $includeDirectors=$this->input->post('includeDirectors');
            $scheduleBranch=$this->input->post('scheduleBranch');
            foreach ($scheduleDays as $scheduleDay) {
                $dataArray[]=$scheduleDay;
            }
            $timeDiff = abs($dateTo - $dateFrom);
            $numberDays = $timeDiff/86400; 
            $numberDayss = intval($numberDays); 
            $arrayStudent=array();
            /*$arrayDate=array();*/
            $groups = [];
            $queryTeacher=$this->db->query("select branch,status2,id,fname,mname,lname from users where usertype='Teacher' and status='Active' and isapproved='1' and branch='$scheduleBranch' OR usertype='Director' and status='Active' and isapproved='1' and branch='$scheduleBranch' group by id order by RAND() ");
            foreach($queryTeacher->result() as $makeSchedule){
                $arrayStudent[]=array(
                    'tid'=>$makeSchedule->id,
                    'tdiv'=>$makeSchedule->status2
                );
            }
            /*for($currentDate = $dateFrom;$currentDate <= $dateTo;$currentDate += (86400)) {   
                $arrayDate[]=array(
                    'dateName'=>date('d-m-Y', $currentDate),
                    'dayName'=>date('l',$currentDate)
                );
                $nameToday=date('l',$currentDate); 
                $date = date('d-m-Y', $currentDate);
            }*/
            shuffle($arrayStudent); 
            
            /*shuffle($arrayDate); 
            $keys=array_keys($arrayStudent);
            $keyDate=array_keys($arrayDate);
            $countTeacher=0;
            $countDate=0;*/
            /*for($currentDate = $dateFrom;$currentDate <= $dateTo;$currentDate += (86400)) {
                $dateName=date('d-m-Y', $currentDate);
                $dayName=date('l',$currentDate);*/
                foreach($queryTeacher->result() as $makeSchedule){
                    $student=$makeSchedule->id;
                    $tdiv=$makeSchedule->status2;
                    $branch=$makeSchedule->branch;
                    $queryTeacherBranch=$this->db->query("select branch,status2,id,fname,mname,lname from users where usertype='Teacher' and status='Active' and isapproved='1' and branch='$scheduleBranch' OR usertype='Director' and status='Active' and isapproved='1' and branch='$scheduleBranch' group by id");
                    $noTeacher=$queryTeacherBranch->num_rows();
                    $noOfDaysToTeac=floor($numberDayss / $noTeacher);
                    /*$td=$position['tid'];
                    */
                    /*foreach($arrayDate[$keyDate[$i]] as $positionDate=>$studentDate) { 
                        $nameToday=$studentDate; */
                        /*$date = date('d-m-Y', $currentDate);*/
                        for($currentDate = $dateFrom;$currentDate <= $dateTo;) {
                            $dateName=date('d-m-Y', $currentDate);
                            $dayName=date('l',$currentDate);
                            if(in_array($dayName,$dataArray)){
                                $queryChk=$this->db->query("select * from lineupschedule where academicyear='$max_year' and linedate='$dateName' and divname='$tdiv' and branch='$scheduleBranch' group by divname ");
                                if($queryChk->num_rows() < 1){
                                    $data[]=array(
                                        'tid'=>$student,
                                        'branch'=>$scheduleBranch,
                                        'daysname'=>$dayName,
                                        'linedate'=>$dateName,
                                        'divname'=>$tdiv,
                                        'academicyear'=>$max_year,
                                        'createdby'=>$user,
                                        'datecreated'=>date('M-d-Y H:i:s')
                                    );
                                }
                                
                            }
                            $currentDate += ($noTeacher * 86400);
                        }
                        $dateFrom= $dateFrom + 86400;
                   /* }*/
                }
           /* }*/
            $query2=$this->db->insert_batch('lineupschedule',$data);
        }
    }
    function fetchLineupschedule(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetchLineupschedule($max_year);        
    }
    function printScheduleItem(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['printType'])){
            $printType = $this->input->post('printType');
            $branchit = $this->input->post('branchit');
            if($printType=='printTeacher'){
                echo $this->main_model->printTeacherLineupSchedule($max_year,$branchit);
            }else{
                echo $this->main_model->printDateLineupSchedule($max_year,$branchit);
            }
        }
    }
}