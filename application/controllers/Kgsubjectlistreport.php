<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kgsubjectlistreport extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupKGReport=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Subject' and allowed='KgSubjectReport' order by id ASC "); 
        if($this->session->userdata('username') == '' || $usergroupKGReport->num_rows()<1 || $userLevel!='1'){
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
    public function index($page='kgsubjectlistreport')
    {
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
        {
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['max_year']=$max_year;
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year();
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
    }
    function load_kg_subject_header(){
      $user=$this->session->userdata('username');
      $usertype=$this->session->userdata('usertype');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      if($this->input->post('branches')){
        $branch=$this->input->post('branches');
        $gradesec=$this->input->post('gradesec');
        echo $this->main_model->load_kg_subject_header($branch,$gradesec,$max_year); 
      }
    }
    function load_kg_subject_week(){
      $user=$this->session->userdata('username');
      $usertype=$this->session->userdata('usertype');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      if($this->input->post('branches')){
        $branch=$this->input->post('branches');
        $gradesec=$this->input->post('gradesec');
        echo $this->main_model->load_kg_subject_week($branch,$gradesec,$max_year); 
      }
    }
    function load_kg_subject_header_grade(){
      $user=$this->session->userdata('username');
      $usertype=$this->session->userdata('usertype');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      if($this->input->post('branches')){
        $branch=$this->input->post('branches');
        $gradesec=$this->input->post('gradesec');
        echo $this->main_model->load_kg_subject_header_grade($branch,$gradesec,$max_year); 
      }
    }
    function load_kg_category_header(){
      $user=$this->session->userdata('username');
      $usertype=$this->session->userdata('usertype');
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      if($this->input->post('gradesec')){
        $quarter=$this->input->post('quarter');
        $gradesec=$this->input->post('gradesec');
        echo $this->main_model->load_kg_category_header($quarter,$gradesec,$max_year); 
      }
    }
    function load_kg_subject_value(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branches')){
            $branch=$this->input->post('branches');
            $gradesec=$this->input->post('gradesec');
            echo $this->main_model->load_kg_subject_value($branch,$gradesec,$max_year); 
        }
    }
    function fecthStudentResult(){
      $user=$this->session->userdata('username');
      $query_branch = $this->db->query("select * from users where username='$user'");
      $row_branch = $query_branch->row();
      $mybranch=$row_branch->branch;
      $query = $this->db->query("select max(year_name) as year from academicyear");
      $row = $query->row();
      $max_year=$row->year;
      $accessbranch = sessionUseraccessbranch();
      if($this->input->post('branches')){
        $branches=$this->input->post('branches');
        $gradesec=$this->input->post('gradesec');
        $quarter=$this->input->post('quarter');
        $period_status=$this->input->post('period_status');
        $academicyear=$this->input->post('academicyear');

        $this->db->where('academicyear',$academicyear);
        $this->db->where('enable_status','1');
        $queryCheck = $this->db->get('kg_chibt_week_category');
        if($queryCheck->num_rows()>0){
          if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $fetchData= $this->main_model->fecthStudentResultWeek($branches,$gradesec,$quarter,$period_status,$academicyear);
            echo json_encode($fetchData);
          }else{
            $fetchData=$this->main_model->fecthStudentResultWeek($mybranch,$gradesec,$quarter,$period_status,$academicyear);
            echo json_encode($fetchData);
          }
        }else{
          if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $fetchData= $this->main_model->fecthStudentResult($branches,$gradesec,$quarter,$period_status,$academicyear);
            echo json_encode($fetchData);
          }else{
            $fetchData=$this->main_model->fecthStudentResult($mybranch,$gradesec,$quarter,$period_status,$academicyear);
            echo json_encode($fetchData);
          }
        }
      }
    }
  function fetch_result_report(){


    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_quarter = $this->db->query("select max(term) as quarter from quarter where Academic_year ='$max_year' ");
    $row_quarter = $query_quarter->row();
    $max_quarter=$row_quarter->quarter;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      $yearName=$this->input->post('year');

      $this->db->where('academicyear',$yearName);
      $this->db->where('enable_status','1');
      $queryCheck = $this->db->get('kg_chibt_week_category');
      if($queryCheck->num_rows()>0){
        $weekName=$this->input->post('week');
        if(empty($weekName) || $weekName==''){
          echo json_encode('Please select week to proceed');
        }else{
          for($i=0;$i<count($weekName);$i++){
            $week[]=$weekName[$i];
          }
          if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $fetchData= $this->main_model->fetch_kg_result_reportWeek($branches,$gradesec,$quarter,$week,$yearName,$max_quarter);
            echo json_encode($fetchData);
          }else{
            $fetchData=$this->main_model->fetch_kg_result_reportWeek($mybranch,$gradesec,$quarter,$week,$yearName,$max_quarter);
            echo json_encode($fetchData);
          }
        }
      }else{
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          $fetchData= $this->main_model->fetch_kg_result_report($branches,$gradesec,$quarter,$yearName);
          echo json_encode($fetchData);
        }else{
          $fetchData=$this->main_model->fetch_kg_result_report($mybranch,$gradesec,$quarter,$yearName);
          echo json_encode($fetchData);
        }
      }

    }
  }
  function fecthNonFilledStudentBs(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      $period_status=$this->input->post('period_status');
      $academicyear=$this->input->post('academicyear');

      $this->db->where('academicyear',$academicyear);
      $this->db->where('enable_status','1');
      $queryCheck = $this->db->get('kg_chibt_week_category');
      if($queryCheck->num_rows()>0){
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          $fetchData= $this->main_model->fecthNonFilledStudent_kf_result_GSWeek($branches,$gradesec,$quarter,$period_status,$academicyear);
          echo json_encode($fetchData);
        }else{
          $fetchData=$this->main_model->fecthNonFilledStudent_kf_result_GSWeek($mybranch,$gradesec,$quarter,$period_status,$academicyear);
          echo json_encode($fetchData);
        }
      }else{
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          $fetchData= $this->main_model->fecthNonFilledStudent_kf_result_GS($branches,$gradesec,$quarter,$period_status,$academicyear);
          echo json_encode($fetchData);
        }else{
          $fetchData=$this->main_model->fecthNonFilledStudent_kf_result_GS($mybranch,$gradesec,$quarter,$period_status,$academicyear);
          echo json_encode($fetchData);
        }
      }

    }
  }
  function updateStudentBs(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('bsname')){
      $bsname=$this->input->post('bsname');
      $value=$this->input->post('value');
      $stuid=$this->input->post('stuid');
      $quarter=$this->input->post('quarter');
      $bsGradesec=$this->input->post('bsGradesec');
      $branches=$this->input->post('branch');
      $bsPeriod=$this->input->post('bsPeriod');
      $data=array(
        'stuid'=>$stuid,
        'criteria_name'=>$bsname,
        'value'=>$value,
        'quarter'=>$quarter,
        'result_period'=>$bsPeriod,
        'academicyear'=>$max_year,
        'datecreated'=>date('M-d-Y'),
        'byuser'=>$user,
        'bsgrade'=>$bsGradesec,
        'bsbranch'=>$branches
      );
      echo $this->main_model->updateStudent_kg_result_value($bsGradesec,$stuid,$quarter,$bsPeriod,$bsname,$max_year,$value,$data);
    }
  }
  function fetch_user(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $fetch_data = $this->main_model->fetch_bs($branches,$gradesec,$quarter,$max_year);
      }else{
        $fetch_data = $this->main_model->fetch_bs($mybranch,$gradesec,$quarter,$max_year);
      }
      $data = array();
      foreach($fetch_data as $row)  
      {  
        $sub_array = array();  
        $sub_array[] = '<img src="'.base_url().'profile/'.$row->profile.'" class="img-thumbnail" width="50" height="35" />';  
        $sub_array[] = $row->fname;  
        $sub_array[] = $row->lname;  
        $sub_array[] = '<button type="button" name="update" id="'.$row->id.'" class="btn btn-warning btn-xs">Update</button>';  
        $sub_array[] = '<button type="button" name="delete" id="'.$row->id.'" class="btn btn-danger btn-xs">Delete</button>';  
        $data[] = $sub_array;  
      }  
      $output = array(  
        "draw"  =>intval($_POST["draw"]),  
        "recordsTotal"=>$this->main_model->get_all_data(),  
        "recordsFiltered"=>$this->main_model->get_filtered_data(),  
        "data" =>     $data  
      );  
      echo json_encode($output);
    }        
  }
  function fetchGradeSecFromBranch(){
    $YearName = sessionAcademicYear();
    $max_year=$YearName['year'];
    if($this->input->post('branchit')){
      $branch=$this->input->post('branchit');
      echo $this->main_model->fetch_grade_from_branchAll_dataProgress($branch,$max_year); 
    }
  }
  function fecthDataProgress(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');
      $period_status=$this->input->post('period_status');
      $academicyear=$this->input->post('academicyear');
       for($i=0;$i<count($gradesec);$i++){
        $check[]=$gradesec[$i];
      }

      $this->db->where('academicyear',$academicyear);
      $this->db->where('enable_status','1');
      $queryCheck = $this->db->get('kg_chibt_week_category');
      if($queryCheck->num_rows()>0){
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          $fetchData= $this->main_model->fecthDataProgressWeek($branches,$check,$period_status,$academicyear);
          echo json_encode($fetchData);
        }else{
          $fetchData=$this->main_model->fecthDataProgressWeek($mybranch,$check,$period_status,$academicyear);
          echo json_encode($fetchData);
        }
      }else{
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          $fetchData= $this->main_model->fecthDataProgress($branches,$check,$period_status,$academicyear);
          echo json_encode($fetchData);
        }else{
          $fetchData=$this->main_model->fecthDataProgress($mybranch,$check,$period_status,$academicyear);
          echo json_encode($fetchData);
        }
      }

    }
  }
  function filterGradefromBranch(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
     $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('branchit')){
      $branch=$this->input->post('branchit');
      echo $this->main_model->fetch_grade_from_branch_4statistics($branch,$max_year); 
    }
  }
  function fecthReportStatistics(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $countValues=0;
      $branches=$this->input->post('branches');
      $value_name=$this->input->post('value_name');
      $gradesec=$this->input->post('gradesec');
      $period_statusRS=$this->input->post('period_statusRS');
      $bsquarterRS=$this->input->post('bsquarterRS');
      $header_name=$this->input->post('header_name');
      $academicyear=$this->input->post('academicyear');
      for($i=0;$i<count($value_name);$i++){
        $check_valueNames[]=$value_name[$i];
        $countValues=$countValues + 1;
      }
      for($i=0;$i<count($header_name);$i++){
        $check_headerNames[]=$header_name[$i];
      }

      $this->db->where('academicyear',$academicyear);
      $this->db->where('enable_status','1');
      $queryCheck = $this->db->get('kg_chibt_week_category');
      if($queryCheck->num_rows()>0){
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          $fetchData= $this->main_model->fecthReportStatisticsWeek($branches,$check_valueNames,$check_headerNames,$gradesec,$bsquarterRS,$period_statusRS,$academicyear,$countValues);
          echo json_encode($fetchData);
        }else{
          $fetchData= $this->main_model->fecthReportStatisticsWeek($mybranch,$check_valueNames,$check_headerNames,$gradesec,$bsquarterRS,$period_statusRS,$academicyear,$countValues);
          echo json_encode($fetchData);
        }
      }else{
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          $fetchData= $this->main_model->fecthReportStatistics($branches,$check_valueNames,$check_headerNames,$gradesec,$bsquarterRS,$period_statusRS,$academicyear,$countValues);
          echo json_encode($fetchData);
        }else{
          $fetchData= $this->main_model->fecthReportStatistics($mybranch,$check_valueNames,$check_headerNames,$gradesec,$bsquarterRS,$period_statusRS,$academicyear,$countValues);
          echo json_encode($fetchData);
        }
      }
    }
  }
  function fecthRosterSummary(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year'");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    if($this->input->post('branches')){
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');;
      $bsquarterRS=$this->input->post('bsquarterRS');
      $academicyear=$this->input->post('academicyear');

      $this->db->where('academicyear',$academicyear);
      $this->db->where('enable_status','1');
      $queryCheck = $this->db->get('kg_chibt_week_category');
      if($queryCheck->num_rows()>0){
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          $fetchData= $this->main_model->fecthRosterSummaryWeek($branches,$gradesec,$bsquarterRS,$academicyear,$max_quarter);
          echo json_encode($fetchData);
        }else{
          $fetchData= $this->main_model->fecthRosterSummaryWeek($mybranch,$gradesec,$bsquarterRS,$academicyear,$max_quarter);
          echo json_encode($fetchData);
        }
      }else{
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          $fetchData= $this->main_model->fecthRosterSummary($branches,$gradesec,$bsquarterRS,$academicyear,$max_quarter);
          echo json_encode($fetchData);
        }else{
          $fetchData= $this->main_model->fecthRosterSummary($mybranch,$gradesec,$bsquarterRS,$academicyear,$max_quarter);
          echo json_encode($fetchData);
        }
      }
    }
  }
   function fecthReportStatistics1(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $countValues=0;
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');
      $period_statusRS=$this->input->post('period_statusRS');
      $bsquarterRS=$this->input->post('bsquarterRS');
      $academicyear=$this->input->post('academicyear');

      $this->db->where('academicyear',$academicyear);
      $this->db->where('enable_status','1');
      $queryCheck = $this->db->get('kg_chibt_week_category');
      if($queryCheck->num_rows()>0){

        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          $queryCalculate=$this->main_model->calculate_resultReportStatistics1Week($branches,$gradesec,$bsquarterRS,$period_statusRS,$academicyear);
          if($queryCalculate){
            $fetchData= $this->main_model->fecthReportStatistics1Week($branches,$gradesec,$bsquarterRS,$period_statusRS,$academicyear);
            echo json_encode($fetchData);
          }else{
            $fetchData= '<span class="text-danger">Ooops Please try again</span>';
            echo json_encode($fetchData);
          }
        }else{
          $queryCalculate=$this->main_model->calculate_resultReportStatistics1Week($mybranch,$gradesec,$bsquarterRS,$period_statusRS,$academicyear);
          if($queryCalculate){
            $fetchData= $this->main_model->fecthReportStatistics1Week($mybranch,$gradesec,$bsquarterRS,$period_statusRS,$academicyear);
            echo json_encode($fetchData);
          }else{
            $fetchData= '<span class="text-danger">Ooops Please try again</span>';
            echo json_encode($fetchData);
          }
        }
      }else{
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          $queryCalculate=$this->main_model->calculate_resultReportStatistics1($branches,$gradesec,$bsquarterRS,$period_statusRS,$academicyear);
          if($queryCalculate){
            $fetchData= $this->main_model->fecthReportStatistics1($branches,$gradesec,$bsquarterRS,$period_statusRS,$academicyear);
            echo json_encode($fetchData);
          }else{
            $fetchData= '<span class="text-danger">Ooops Please try again</span>';
            echo json_encode($fetchData);
          }
        }else{
          $queryCalculate=$this->main_model->calculate_resultReportStatistics1($mybranch,$gradesec,$bsquarterRS,$period_statusRS,$academicyear);
          if($queryCalculate){
            $fetchData= $this->main_model->fecthReportStatistics1($mybranch,$gradesec,$bsquarterRS,$period_statusRS,$academicyear);
            echo json_encode($fetchData);
          }else{
            $fetchData= '<span class="text-danger">Ooops Please try again</span>';
            echo json_encode($fetchData);
          }
        }
      }
    }
  }
}