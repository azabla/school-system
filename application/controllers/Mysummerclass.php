<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mysummerclass extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        $this->load->library('excel');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='summerclass' order by id ASC ");
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
    public function index($page='summerclass')
    {
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        if($_SESSION['usertype']===trim('superAdmin')){
            $data['staffs']=$this->main_model->fetchStaffsForPlacement();
        }else{
            $data['staffs']=$this->main_model->fetchMyStaffsForPlacement($branch);
        }

        $data['fetch_grade_fromsp_toadd_neweaxm']=$this->main_model->fetchSummerTeacherPlacement($user,$max_year);
        $data['fetch_division']=$this->main_model->fetch_schooldivision($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter_summer();
        $data['gradesec']=$this->main_model->fetchSummerGradesec($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['grade']=$this->main_model->fetchSummerGrade($max_year);
        $data['subjects']=$this->main_model->fetchSummerSubjectPlacement($max_year);
        $this->load->view('teacher/'.$page,$data);
    }
    function fecthSummerGradeMarkTeacher(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        if($this->input->post('gs_gradesec')){
            $gs_gradesec=$this->input->post('gs_gradesec');
            $gs_subject=$this->input->post('gs_subject');
           /* if($_SESSION['usertype']===trim('Director')){
                echo $this->main_model->fetchSummerGradeMark($branch,$gs_gradesec,$gs_subject,$max_year); 
            }else{*/
                echo $this->main_model->fetchSummerGradeMarkTeacher($branch,$gs_gradesec,$gs_subject,$max_year); 
            /*}*/
        }
    }
    function fetchAttendanceReport(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetch_mattendance_summerTeacher($max_year,$branch,$user);  
    }
    function deleteAttendanceSummer(){
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attendanceId')){
            $attendanceId=$this->input->post('attendanceId');
            $this->main_model->delete_Staffattendance_summer($attendanceId,$max_year);
        }
    }
    function fetchStudents4Attendance(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $userType=$this->session->userdata('usertype');
        if($this->input->post('attGradesec')){
            $attGradesec=$this->input->post('attGradesec');
            echo $this->main_model->fetch_gradesec_student_summer($attGradesec,$branch,$max_year); 
        }
    }
     function saveAttendance(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $attendanceDate=$this->input->post('attendanceDate');
            $attendanceMinute=$this->input->post('attendanceMinute');
            $attendanceType=$this->input->post('attendanceType');
            $timestamp=strtotime($attendanceDate);
            $newDateEnd=date('d/m/y',$timestamp);
            for($i=0;$i<count($stuid);$i++){
                $check=$stuid[$i];
                $query=$this->main_model->insert_absent_summer($check,$attendanceDate,$max_year,$user);
                if($query){
                    if($attendanceType=='Absent'){
                        $data[]=array(
                            'stuid'=>$check,
                            'absentdate'=>$attendanceDate,
                            'absentype'=>'Absent',
                            'academicyear'=>$max_year,
                            'attend_by'=>$user
                        );
                    }
                    else if($attendanceType=='Late'){
                        $data[]=array(
                            'stuid'=>$check,
                            'absentdate'=>$attendanceDate,
                            'absentype'=>'Late',
                            'latemin'=>$attendanceMinute,
                            'academicyear'=>$max_year,
                            'attend_by'=>$user
                        );
                    }else{
                        $data[]=array(
                            'stuid'=>$check,
                            'absentdate'=>$attendanceDate,
                            'absentype'=>'Permission',
                            'academicyear'=>$max_year,
                            'attend_by'=>$user
                        );
                    }  
                }
            } 
        }
        if(!empty($data)){
            $this->db->insert_batch('summerattendance',$data);
        }
        
    }
    function FilterSummerSubjectFromStaff(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $queryChk = $this->db->select('*')
                    ->where('staff', $user)
                    ->where('academicyear',$max_year)
                    ->get('directorplacement');
            if($_SESSION['usertype']===trim('Director') && $queryChk->num_rows()>0 ){
                echo $this->main_model->fetchSummerSubjectForDirector($gradesec,$max_year);
            }else{
                echo $this->main_model->fetchSummerSubjectForTeacher($gradesec,$max_year,$user);
            }
        } 
    }
    function deleteMarkName(){
        $user=$this->session->userdata('username');
        $query=$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        if($this->input->post('subject')){
            $subject=$this->input->post('subject');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $year=$this->input->post('year');
            $markname=$this->input->post('markname');
            if($_SESSION['usertype']===trim('Director'))
            {
                $this->db->where('lockmark','0');
                $this->db->where('mgrade',$gradesec);
                $this->db->where('academicyear',$year);
                $this->db->where('subname',$subject);
                $this->db->where('markname',$markname);
                $this->db->where('mbranch',$branch);
                $this->db->delete('summermark');
                echo $this->main_model->fetchSummerGradeMarkTeacher($branch,$gradesec,$subject,$year);
            }else{
                $this->db->where('lockmark','0');
                $this->db->where('mgrade',$gradesec);
                $this->db->where('academicyear',$year);
                $this->db->where('subname',$subject);
                $this->db->where('markname',$markname);
                $this->db->where('mbranch',$branch_me);
                $this->db->delete('summermark');
                echo $this->main_model->fetchSummerGradeMarkTeacher($branch_me,$gradesec,$subject,$year);
            } 
        }
    }
    function fetchMarkToEdit(){
        $user=$this->session->userdata('username');
        $query=$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $user=$this->session->userdata('username');
        $query=$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        if($this->input->post('edtim')){
            $edtim=$this->input->post('edtim');
            $gradesec=$this->input->post('gradesec');
            $academicyear=$this->input->post('academicyear');
            $branch=$this->input->post('branch');
            echo $this->main_model->selectSummerMarkToEdit($edtim,$gradesec,$academicyear,$branch);
        }
    }
    function FetchUpdatedMark(){
        $user=$this->session->userdata('username');
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        if($this->input->post('mid')){
            $mid=$this->input->post('mid');
            $gradesec=$this->input->post('gradesec');
            $year=$this->input->post('year');
            $branch=$this->input->post('branch');
            echo $this->main_model->FetchUpdatedMarkSummer($mid,$gradesec,$year,$branch);
        }
    }
    function updateMarkNow(){
        $user=$this->session->userdata('username');
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        if($this->input->post('outof')){
            $outof=$this->input->post('outof');
            $mid=$this->input->post('mid');
            $value=$this->input->post('value');
            $gradesec=$this->input->post('gradesec');
            $year=$this->input->post('year');
            $branch=$this->input->post('branch');
            if($outof>=$value){
                echo $this->main_model->updateEditedMarkSummer($outof,$mid,$value,$gradesec,$year,$branch);
            }else{
                echo'<span class="text-danger"> Please insert correct value.</span>';
            }
        }
    }
    function fecthSummerMarkresult(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        if($this->input->POST('gs_gradesec')){
            $gs_gradesec=$this->input->POST('gs_gradesec');
            $gs_subject=$this->input->POST('gs_subject');
            $show=$this->main_model->fetchSummerGradeMarkResult($branch,$gs_gradesec,$gs_subject,$max_year); 
            echo $show;
        }
    }
    function filterSummerEvaluation(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            echo $this->main_model->fetchSummerEvaluationOnQuarterchange($gradesec,$max_year); 
        }
    }
    function studentResultForm(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $subject=$this->input->post('subject');
            $evaluation=$this->input->post('evaluation');
            $assesname=$this->input->post('assesname');
            $percentage=$this->input->post('percentage');
            echo $this->main_model->fetchThisGradeSummertudentsFornewexam($gradesec,$subject,$evaluation,$assesname,$percentage,$branch,$max_year);
        }
    }
    function fecthNgMarkToEdit_summer(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('stuid')){
          $subject=$this->input->post('subject');
          $stuid=$this->input->post('stuid');
          $evaid=$this->input->post('evaid');
          $quarter=$this->input->post('quarter');
          $gradesec=$this->input->post('gradesec');
          $markname=$this->input->post('markname');
          $outof=$this->input->post('outof');
          $branch=$this->input->post('branch');
          echo $this->main_model->select_edited_ngmark_summer($subject,$stuid,$quarter,$max_year,$gradesec,$markname,$outof,$evaid,$branch);
        }
    }
    function updateNgMarkNow(){
    $user=$this->session->userdata('username');
    $query =$this->db->query("select max(year_name) as year from summer_academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('my_eva')){
      $my_eva=$this->input->post('my_eva');
      $stuid=$this->input->post('stuid');
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      $year=$this->input->post('year');
      $val=$this->input->post('val');
      $markname=$this->input->post('markname');
      $outof=$this->input->post('outof');
      $my_studentBranch=$this->input->post('my_studentBranch');
      if($outof>=$val){
        $data=array(
          'stuid'=>$stuid,
          'subname'=>$subject,
          'evaid'=>$my_eva,
          'quarter'=>$quarter,
          'value'=>$val,
          'markname'=>$markname,
          'outof'=>$outof,
          'academicyear'=>$year,
          'mbranch'=>$my_studentBranch,
          'mgrade'=>$gradesec
        );
        echo $this->main_model->update_edited_ngmark_summer($user,$data,$quarter,$gradesec,$year,$my_studentBranch,$subject,$val,$stuid,$markname);
      }else{
        echo '<span class="text-danger">Please insert correct mark</span>';
      }
    }
  }
    function addNewresult(){
        $user=$this->session->userdata('username');
        $query=$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        if($this->input->post('stuid')){
          $stuid=$this->input->post('stuid');
          $resultvalue=$this->input->post('resultvalue');
          $academicyear=$this->input->post('academicyear');
          $subject=$this->input->post('subject');
          $evaluation=$this->input->post('evaluation');
          $assesname=$this->input->post('assesname');
          $percentage=$this->input->post('percentage');
          $markGradeSec=$this->input->post('markGradeSec');
          $queryChk=$this->main_model->saveThisSummerGradeResult($academicyear,$subject,$assesname,$markGradeSec,$branch_me);
          if($queryChk){
            for ($i=0; $i < count($stuid); $i++) { 
              $id=$stuid[$i];
              $markvalue=$resultvalue[$i];
              if($percentage>=$markvalue && $markvalue>=0 && $markvalue!=''){
                $data[]=array(
                  'stuid'=>$id,
                  'academicyear'=>$academicyear,
                  'markname'=>$assesname,
                  'subname'=>$subject,
                  'evaid'=>$evaluation,
                  'outof'=>$percentage,
                  'value'=>$markvalue,
                  'mgrade'=>$markGradeSec,
                  'mbranch'=>$branch_me,
                  'approved'=>'0'
                );
              }
            }
            if(!empty($data)){
                $query=$this->db->insert_batch('summermark',$data);
                if($query){
                      echo '<div class="alert alert-success alert-dismissible show fade">
                          <div class="alert-body">
                              <button class="close"  data-dismiss="alert">
                                  <span>&times;</span>
                              </button>
                          <i class="fas fa-exclamation-circle"> </i> Result saved successfully.
                      </div></div>';
                }else{
                      echo '<div class="alert alert-warning alert-dismissible show fade">
                          <div class="alert-body">
                              <button class="close"  data-dismiss="alert">
                                  <span>&times;</span>
                              </button>
                          <i class="fas fa-exclamation-circle"> </i> Please Try Again.
                      </div></div>';
                }
            }
          }else{
            echo '<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            <i class="fas fa-exclamation-circle"> </i> Mark already exists.
            </div></div>';
          }
        }
    }
    function lockThisSummerMark(){
        $accessbranch = sessionUseraccessbranch();
        $user=$this->session->userdata('username');
        $query=$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        $this->db->where('academicyear',$max_year);
        $this->db->set('lockmark','1');
        $query=$this->db->update('summermark');
        if($query){
            echo '<div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> Mark Locked successfully.
            </div></div>';
        }else{
            echo 'Oops Please try again';
        }
        
    }
    function unlockThisSummerMark(){
        $accessbranch = sessionUseraccessbranch();
        $user=$this->session->userdata('username');
        $query=$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        $this->db->where('academicyear',$max_year);
        $this->db->set('lockmark','0');
        $query=$this->db->update('summermark');
            
        if($query){
            echo '<div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> Mark Unocked successfully.
            </div></div>';
        }else{
            echo 'Oops Please try again';
        }
    }
}