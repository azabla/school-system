<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schoolassesment extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Evaluation' and allowed='Mgmtassesment'  order by id ASC "); 
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
	public function index($page='schoolassesment')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
        {
          show_404();
        }
        
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $accessbranch = sessionUseraccessbranch();
        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year'");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $query_branch = $this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $data['fetchEval4Assesment']=$this->main_model->fetchEval4Assesment($max_year,$max_quarter);

        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['grade']=$this->main_model->fetch_grade($max_year);
        }else{
            $data['grade']=$this->main_model->fetch_grade_admin($max_year,$branch);
        }
        $data['posts']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['branch']=$this->main_model->fetch_branch($max_year);
        }else{
            $data['branch']=$this->main_model->fetch_my_branch($max_year,$branch);
        }
        $data['assesment_status']=$this->main_model->fetch_filter_assesment_status($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function fetchEval4AssesmentFilter(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetchEval4AssesmentFilter($max_year);
    }
    function fetch_School_Assesment(){
        $postData = $this->input->post();
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data= $this->main_model->fetch_School_Assesment($max_year,$postData);
            echo json_encode($data);
        }else{
            $this->db->where('academicyear',$max_year);
            $this->db->where('assesment_status','1');
            $query = $this->db->get('filter_assesment_by_branch_subject');
            if($query->num_rows()>0){
                $data= $this->main_model->fetchSchoolAssesment_admin($max_year,$branch,$postData);
                echo json_encode($data);
            }else{
                $data= $this->main_model->fetchSchoolAssesment_admin_NOBranch($max_year,$branch,$postData);
                echo json_encode($data);
            }
        }
    }
    function updateAssesmentMandatory(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('sasname')){
          $sasname=$this->input->post('sasname');
          $value=$this->input->post('value');
          $sasgrade=$this->input->post('sasgrade');
          $sasID=$this->input->post('sasID');
          $this->db->where('sasgrade',$sasgrade);
          $this->db->where('sasname',$sasname);
          $this->db->where('sasid',$sasID);
          $this->db->set('ismandatory',$value);
          $query=$this->db->update('schoolassesment');
        }
    }
    function updateAssesmentEnddate(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('sasname')){
            $sasname=$this->input->post('sasname');
            $value=$this->input->post('value');
            $sasgrade=$this->input->post('sasgrade');
            $sasID=$this->input->post('sasID');
            $this->db->where('sasgrade',$sasgrade);
            $this->db->where('sasname',$sasname);
            $this->db->where('sasid',$sasID);
            $this->db->set('dateend',$value);
            $query=$this->db->update('schoolassesment');
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        }
    }
    function updateAssesmentOrder(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('sasname')){
          $sasname=$this->input->post('sasname');
          $value=$this->input->post('value');
          $sasgrade=$this->input->post('sasgrade');
          $this->db->where('sasgrade',$sasgrade);
          $this->db->where('sasname',$sasname);
          $this->db->set('assorder',$value);
          $query=$this->db->update('schoolassesment');
        }
    }
    function updateAssesmentPercentage(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('sasname')){
          $sasname=$this->input->post('sasname');
          $value=$this->input->post('value');
          $sasgrade=$this->input->post('sasgrade');
          $sasID=$this->input->post('sasID');
          $this->db->where('sasgrade',$sasgrade);
          $this->db->where('sasname',$sasname);
          $this->db->where('sasid',$sasID);
          $this->db->set('saspercent',$value);
          $query=$this->db->update('schoolassesment');
        }
    }
    function saveSchoolAssesment(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('assesmentEval')){
            $assesmentEval=$this->input->post('assesmentEval');
            $assesmentEndDate=$this->input->post('assesmentEndDate');
            $assesmentName=$this->input->post('assesmentName');
            $assesmentGrade=$this->input->post('assesmentGrade');
            $assorder=$this->input->post('assorder');
            $ismandatory=$this->input->post('ismandatory');
            $assesmentPercent=$this->input->post('assesmentPercent');
            $assesmentSubject=$this->input->post('assesmentSubject');
            $assesmentBranch=$this->input->post('assesmentBranch');
            for($i=0;$i<count($assesmentGrade);$i++){
                $assesmentGrades=$assesmentGrade[$i];
                for($j=0;$j<count($assesmentSubject);$j++){
                    $assesmentSubjects=$assesmentSubject[$j];
                    $query=$this->db->query("select * from schoolassesment where academicyear='$max_year' and sasname='$assesmentName' and sasgrade='$assesmentGrades' and assesment_subject='$assesmentSubjects' ");
                    if($query->num_rows()<1){
                       $data[]=array(
                           'sasgrade'=>$assesmentGrades,
                           'saseval'=>$assesmentEval,
                           'sasname'=>$assesmentName,
                           'saspercent'=>$assesmentPercent,
                           'ismandatory'=>$ismandatory,
                           'assorder'=>$assorder,
                           'dateend'=>$assesmentEndDate,
                           'academicyear'=>$max_year,
                           'createdby'=>$user,
                           'assesment_branch'=>$assesmentBranch,
                           'assesment_subject'=>$assesmentSubjects,
                           'datecreated'=>date('M-d-Y')
                        );
                    }
                }
            }
            if(!empty($data)){
                $this->db->insert_batch('schoolassesment',$data);
            } 
        }
    }
    function saveSchoolAssesment_noSubject(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('assesmentEval')){
            $assesmentEval=$this->input->post('assesmentEval');
            $assesmentEndDate=$this->input->post('assesmentEndDate');
            $assesmentName=$this->input->post('assesmentName');
            $assesmentGrade=$this->input->post('assesmentGrade');
            $assorder=$this->input->post('assorder');
            $ismandatory=$this->input->post('ismandatory');
            $assesmentPercent=$this->input->post('assesmentPercent');
            for($i=0;$i<count($assesmentGrade);$i++){
                $assesmentGrades=$assesmentGrade[$i];
                $query=$this->db->query("select * from schoolassesment where academicyear='$max_year' and sasname='$assesmentName' and sasgrade='$assesmentGrades' ");
                if($query->num_rows()<1){
                   $data[]=array(
                       'sasgrade'=>$assesmentGrades,
                       'saseval'=>$assesmentEval,
                       'sasname'=>$assesmentName,
                       'saspercent'=>$assesmentPercent,
                       'ismandatory'=>$ismandatory,
                       'assorder'=>$assorder,
                       'dateend'=>$assesmentEndDate,
                       'academicyear'=>$max_year,
                       'createdby'=>$user,
                       'assesment_branch'=>'',
                       'assesment_subject'=>'',
                       'datecreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($data)){
                $this->db->insert_batch('schoolassesment',$data);
            } 
        }
    }
    function deleteAssesment(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('sasname')){
            $sasname=$this->input->post('sasname');
            $this->db->where('saseval',$sasname);
            $this->db->where('academicyear',$max_year);
            $this->db->delete('schoolassesment');
        }
    }
    function deleteAssesmentName(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('sasname')){
            $sasname=$this->input->post('sasname');
            $sasgrade=$this->input->post('sasgrade');
            $sasID=$this->input->post('sasID');
            $this->db->where('sasname',$sasname);
            $this->db->where('sasgrade',$sasgrade);
            $this->db->where('sasid',$sasID);
            $this->db->where('academicyear',$max_year);
            $this->db->delete('schoolassesment');
        }
    }
    function filterassesmentby_branch_subject(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->filterassesmentby_branch_subject($max_year);
    }
    function onn_filter_assesment(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            $this->db->where('academicyear',$max_year);
            $query = $this->db->get('filter_assesment_by_branch_subject');
            if($query->num_rows()>0){
                $this->db->where('academicyear',$max_year);
                $this->db->set('assesment_status','1');
                $query=$this->db->update('filter_assesment_by_branch_subject'); 
                if($query){
                    echo '1';
                }else{
                    echo '0';
                }
            }else{
                $data=array(
                    'assesment_status'=>'1',
                    'academicyear'=>$max_year,
                    'lockby'=>$user,
                    'datelocked'=>date('M-d-Y')
                );
                $query=$this->db->insert('filter_assesment_by_branch_subject',$data); 
                if($query){
                    echo '3';
                }else{
                    echo '4';
                }
            }
        }
    }
    function off_filter_assesment(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            $this->db->where('academicyear',$max_year);
            $this->db->set('assesment_status','0');
            $query=$this->db->update('filter_assesment_by_branch_subject');
            if($query){
                echo '1';
            } else{
                echo '0';
            }
        }
    }
    function filter_Subject_4_assesment(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade2analysis')){
            $grade2analysis=$this->input->post('grade2analysis');
            for($i=0;$i<count($grade2analysis);$i++){
                $gradeGsanalysis[]=$grade2analysis[$i];
            }
            echo $this->main_model->filter_Subject_4_assesment($gradeGsanalysis,$max_year);   
        }
    }
}