<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Resultstatistics extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userPerStaAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='Statistics' order by id ASC "); 
        if($this->session->userdata('username') == '' || $userPerStaAtt->num_rows()<1 || $userLevel!='2'){
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
    public function index($page='resultstatistics')
    {
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
          show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $query_quarter = $this->db->query("select max(quarter) as quarter from mark");
        $row_quarter = $query_quarter->row();
        $max_quarter=$row_quarter->quarter;
        /*if($_SESSION['usertype']===trim('Director')){
          $data['gradesec']=$this->main_model->fetch_mygradesec2($user,$max_year,$branch);
        }else{
          $data['gradesecs']=$this->main_model->fetcHrGradesec($max_year,$user,$branch);
        }*/
        if($_SESSION['usertype']===trim('Director')){
          $data['gradesec']=$this->main_model->fetchGradeForSummaryDirector($user,$max_year);
        }else{
          $data['gradesecTeacher']=$this->main_model->fetchGradeForSummaryTeacher($user,$max_year);
        }
        if($_SESSION['usertype']===trim('Director')){
            $data['gradeSec']=$this->main_model->fetch_mygradeDirector($user,$max_year,$branch);
        }else{
            $data['gradeSec']=$this->main_model->fetch_GradeFor_SummaryTeacher($user,$max_year);
        }
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['fetch_termGrade']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['grade']=$this->main_model->fetch_mygradeDirector($user,$max_year,$branch);
        $data['gradeee']=$this->main_model->fetch_mygrade_Director($user,$max_year,$branch);
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['achievername']=$this->main_model->fetch_Achiever();
        $this->load->view('teacher/'.$page,$data);
    }
    function fetch_subject_from_gradeSecFilter(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradesec')){
          $grade=$this->input->post('gradesec');
          echo $this->main_model->fetch_subject_from_gradeSecFilter($grade,$max_year); 
        } 
    }
    function fetch_assesment_outof(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('subjectName')){
            $subjectName=$this->input->post('subjectName');
            $quarter=$this->input->post('analysis_quarter');
            $grade2analysis=$this->input->post('grade2analysis');
            $analysis_subject=$this->input->post('customEvaluation_here');
            for($i=0;$i<count($grade2analysis);$i++){
                $grade2_analysis[]=$grade2analysis[$i];
            }
            for($i=0;$i<count($subjectName);$i++){
                $subjectNames[]=$subjectName[$i];
            }
            echo $this->main_model->fetch_assesment_range_outof($mybranch,$subjectNames,$grade2_analysis,$max_year,$quarter,$analysis_subject); 
        }
    }
    function saveRange(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('lessThan')){
      $less_than=$this->input->post('lessThan');
      $greater_than=$this->input->post('greaterThan');
      $data=array(
        'minvaluerange'=>$greater_than,
        'maxvaluerange'=>$less_than,
        'academicyear'=>$max_year
      );
      $query=$this->db->insert('reportstatistics',$data);
      if($query){
        $queryFetch=$this->db->query("select * from reportstatistics ");
        if($queryFetch->num_rows()>0){
          foreach($queryFetch->result() as $staName){
            echo '<p id="row" class="dynamic-added">Between '.$staName->minvaluerange.' & '.$staName->maxvaluerange.'<button class="btn btn-default btnRemove" id="'.$staName->maxvaluerange.'"  value="'.$staName->minvaluerange.'"><i class="fas fa-times-circle"></i></button></p>';      
          }
        }
      }
    }
  }
    function removeRange(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('lessThan')){
        $less_than=$this->input->post('lessThan');
        $greater_than=$this->input->post('greaterThan');
        $this->db->where('minvaluerange',$greater_than);
        $this->db->where('maxvaluerange',$less_than);
        $query=$this->db->delete('reportstatistics');
        if($query){
            $queryFetch=$this->db->query("select * from reportstatistics ");
            if($queryFetch->num_rows()>0){
                foreach($queryFetch->result() as $staName){
                    echo '<p id="row" class="dynamic-added">Between '.$staName->minvaluerange.' & '.$staName->maxvaluerange.'<button class="btn btn-default btnRemove" id="'.$staName->maxvaluerange.'"  value="'.$staName->minvaluerange.'"><i class="fas fa-times-circle"></i></button></p>';      
                }
            }
        }
        }
    }
    function fetchRange(){
        $queryFetch=$this->db->query("select * from reportstatistics ");
        if($queryFetch->num_rows()>0){
          foreach($queryFetch->result() as $staName){
            echo '<p id="row" class="dynamic-added">Between '.$staName->minvaluerange.' & '.$staName->maxvaluerange.'<button class="btn btn-default btnRemove" id="'.$staName->maxvaluerange.'"  value="'.$staName->minvaluerange.'"><i class="fas fa-times-circle"></i></button></p>';      
          }
        }
    }
    function fetch_subject_from_gradeFilter(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradesec')){
          $grade=$this->input->post('gradesec');
          for($i=0;$i<count($grade);$i++){
              $check[]=$grade[$i];
          }
          echo $this->main_model->fetch_subject_from_gradeFilter($check,$max_year); 
        } 
    }
    function fetchAchiever(){
        echo $this->main_model->fetchAchiever();
    }
    function saveAchiever(){
        $user=$this->session->userdata('username');
        if($this->input->post('achieverName')){
          $achieverName=$this->input->post('achieverName');
          $minVal=$this->input->post('minVal');
          $maxVal=$this->input->post('maxVal');
          $achieverRemark=$this->input->post('achieverRemark');
          $eva_grade=$this->input->post('eva_grade');
          foreach($eva_grade as $grdaes){
            $data=array(
              'achievergrade'=>$grdaes,
              'achievername'=>$achieverName,
              'minvalue'=>$minVal,
              'maxivalue'=>$maxVal,
              'remarkname'=>$achieverRemark,
              'datecreated'=>date('M-d-Y'),
              'createdby'=>$user
            );
            $query=$this->db->insert('achievername',$data);
          }
        }
    }
    function deleteAchiever(){
        if($this->input->post('textId')){
          $id=$this->input->post('textId');
          $textValue=$this->input->post('textValue');
          $textName=$this->input->post('textName');
          $this->db->where('achievername',$id);
          $this->db->where('minvalue',$textValue);
          $this->db->where('maxivalue',$textName);
          $this->db->delete('achievername');
        }
    }
    function filterEvaluationCustomAnalysis(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('grade2analysis')){
            $grade2analysis=$this->input->post('grade2analysis');
            $quarter=$this->input->post('analysis_quarter');
            for($i=0;$i<count($grade2analysis);$i++){
                $gradeGsanalysis[]=$grade2analysis[$i];
            }
            echo $this->main_model->filter_evaluation4CustomAnalysisGrand_option($mybranch,$gradeGsanalysis,$max_year,$quarter); 
        }
    }
    function fetch_analysis(){
        $accessbranch = sessionUseraccessbranch();
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $quarter=$this->input->post('quarter');
            $evaluation=$this->input->post('evaluation');
            $subject=$this->input->post('subjectanalysis');
            $achiverName=$this->input->post('achiverName');
            $inAllSelectedSubject=$this->input->post('inAllSelectedSubject');
            for($i=0;$i<count($gradesec);$i++){
                $gradeGsanalysis[]=$gradesec[$i];
            }
            for($i=0;$i<count($evaluation);$i++){
                $evaluationGsanalysis[]=$evaluation[$i];
            }
            $countSubject=0;
            for($i=0;$i<count($subject);$i++){
                $subjectGsanalysis[]=$subject[$i];
                $countSubject= $countSubject + 1;
            }
            if($inAllSelectedSubject==0){
                if($achiverName!='Select Range'){
                    echo $this->main_model->fetchanalysis_filter($mybranch,$gradeGsanalysis,$quarter,$evaluationGsanalysis,$max_year,$subjectGsanalysis,$countSubject,$achiverName); 
                }else{
                    echo $this->main_model->fetchanalysis($mybranch,$gradeGsanalysis,$quarter,$evaluationGsanalysis,$max_year,$subjectGsanalysis,$countSubject); 
                }
            }else{
                if($achiverName!='Select Range'){
                    echo $this->main_model->fetchanalysis_filter_All($mybranch,$gradeGsanalysis,$quarter,$evaluationGsanalysis,$max_year,$subjectGsanalysis,$countSubject,$achiverName); 
                }else{
                    echo $this->main_model->fetchanalysis($mybranch,$gradeGsanalysis,$quarter,$evaluationGsanalysis,$max_year,$subjectGsanalysis,$countSubject); 
                }
            }
        }
    }
    function filter_evaluation4analysis(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('grade2analysis')){
          $grade2analysis=$this->input->post('grade2analysis');
          $quarter=$this->input->post('analysis_quarter');
          for($i=0;$i<count($grade2analysis);$i++){
            $gradeGsanalysis[]=$grade2analysis[$i];
          }
          
            echo $this->main_model->filter_evaluation4analysisGrand($mybranch,$gradeGsanalysis,$max_year,$quarter); 
          
        }
    }
    function filterSubjectDefaultAnalysis(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('grade2analysis')){
          $grade2analysis=$this->input->post('grade2analysis');
          $quarter=$this->input->post('analysis_quarter');
          for($i=0;$i<count($grade2analysis);$i++){
            $gradeGsanalysis[]=$grade2analysis[$i];
          }
            echo $this->main_model->filterSubject4DefaultAnalysisGrand($mybranch,$gradeGsanalysis,$max_year,$quarter); 
          
        }
    }
    function fetchCustomAnalysis(){
        $accessbranch = sessionUseraccessbranch();
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        if($this->input->post('gradesec')){
          $gradesec=$this->input->post('gradesec');
          $quarter=$this->input->post('quarter');
          $evaluation=$this->input->post('evaluation');
          $subject=$this->input->post('subject');
          $outof_range=$this->input->post('outof_range');
          $countSubject=0;$countAssesment=0;
          for($i=0;$i<count($gradesec);$i++){
            $gradeGsanalysis[]=$gradesec[$i];
          }
          for($i=0;$i<count($subject);$i++){
            $subjectGsanalysis[]=$subject[$i];
            $countSubject=$countSubject + 1;
          }
            echo $this->main_model->fetchCustomAnalysis($mybranch,$gradeGsanalysis,$quarter,$evaluation,$subjectGsanalysis,$max_year,$outof_range,$countSubject,$countAssesment); 
          
        }
    }
    function filterSubjectCustomAnalysis(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('grade2analysis')){
            $grade2analysis=$this->input->post('grade2analysis');
            $quarter=$this->input->post('analysis_quarter');
            $assesname=$this->input->post('customEvaluation_here');
            for($i=0;$i<count($grade2analysis);$i++){
                $gradeGsanalysis[]=$grade2analysis[$i];
            }
            echo $this->main_model->filterSubject4DefaultAnalysisGrand_New($mybranch,$gradeGsanalysis,$max_year,$quarter,$assesname); 
        }
    }
    function thisGradeMarkStatistics(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $countQuarter=0;$countSubject=0;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('grade_statistics')){
            $quarters=$this->input->post('quarterStatistics');
            $grade=$this->input->post('grade_statistics');
            $branch=$this->input->post('branch_statistics');
            $subject=$this->input->post('subStatistics');
            /*$Year_statisticsGrade=$this->input->post('Year_statisticsGrade');*/
            $nameChecked=$this->input->post('nameChecked');
            $this->db->where('grade',$grade);
            $this->db->where('academicyear',$max_year);
            $this->db->delete('reportvaluestatistics');
            for($i=0;$i<count($quarters);$i++){
                $countQuarter=$countQuarter + 1;
                $quarter[]=$quarters[$i];
            }
            if(!empty($subject)){
                for($i=0;$i<count($subject);$i++){
                    $countSubject=$countSubject + 1;
                    $subjects[]=$subject[$i];
                }
                if($nameChecked==1){
                    if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                        echo $this->main_model->gradeMarkStatisticswithName($max_year,$branch,$grade,$subjects,$quarter,$countQuarter,$countSubject);
                    }else{
                        echo $this->main_model->gradeMarkStatisticswithName($max_year,$mybranch,$grade,$subjects,$quarter,$countQuarter,$countSubject);
                    }
                }else{
                    if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                        echo $this->main_model->gradeMarkStatistics($max_year,$branch,$grade,$subjects,$quarter,$countQuarter,$countSubject);
                    }else{
                        echo $this->main_model->gradeMarkStatistics($max_year,$mybranch,$grade,$subjects,$quarter,$countQuarter,$countSubject);
                    }
                }
          }else{
                if($nameChecked==1){
                    if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                        echo $this->main_model->averageMarkStatisticsWithName($max_year,$branch,$grade,$quarter,$countQuarter,$countSubject);
                    }else{
                        echo $this->main_model->averageMarkStatisticsWithName($max_year,$mybranch,$grade,$quarter,$countQuarter,$countSubject);
                    }
                }else{
                    if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                        echo $this->main_model->averageMarkStatistics($max_year,$branch,$grade,$quarter,$countQuarter,$countSubject);
                    }else{
                        echo $this->main_model->averageMarkStatistics($max_year,$mybranch,$grade,$quarter,$countQuarter,$countSubject);
                    }
                }
            }
        } 
    } 
}