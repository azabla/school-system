<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStupro=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentPr' order by id ASC "); 
        if($this->session->userdata('username') == '' || $uperStupro->num_rows() < 1 || $userLevel!='1'){
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
    public function index($page='registration')
    {
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academicYear4Registration();
        $data['academicyearlist']=$this->main_model->academic_year();
        $data['fetch_grade_fromsp_toadd_neweaxm']=$this->main_model->fetch_grade_from_staffplace($user,$max_year);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
    } 
    function fecth_student_toregister(){ //done
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['register_id'])){
            $id=$this->input->post('register_id');
            $yearDropped=$this->input->post('yearDrooped');
            $username=$this->input->post('username');
            echo $this->main_model->fecth_student_toregister_lastYear($id,$yearDropped,$max_year,$username);
        }
    }
    public function register_student()
    {
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if(isset($_POST['register_id'])){
            $id=$this->input->post('register_id');
            $yearDropped=$this->input->post('yearDrooped');
            $grade=$this->input->post('grade');
            $branch=$this->input->post('branch');
            $registerOnYear=$this->input->post('registerOnYear');
            if($yearDropped==$registerOnYear){
                echo $this->main_model->active_student($id);
            }else{
                $queryFetch=$this->db->query("select * from users where id='$id' and academicyear='$yearDropped' ");
                if($queryFetch->num_rows()>0){
                    foreach($queryFetch->result() as $row){
                        $userName=$row->username;
                        $data=array(
                            'username'=>$row->username,
                            'usertype'=>$row->usertype,
                            'fname'=>$row->fname,
                            'mname'=>$row->mname,
                            'lname' =>$row->lname,
                            'last_oflast_name'=>$row->last_oflast_name,
                            'previous_school' =>$row->previous_school,
                            'mobile' =>$row->mobile,
                            'father_mobile'=> $row->father_mobile,
                            'email' =>$row->email,
                            'profile'=>$row->profile ,
                            'grade'=>$grade ,
                            'section' =>'',
                            'gradesec'=>'',
                            'dob'=>$row->dob ,
                            'age'=>$row->age ,
                            'gender' =>$row->gender,
                            'password'=>$row->password ,
                            'password2' =>$row->password2,
                            'mother_name' =>$row->mother_name,
                            'father_name' =>$row->father_name,
                            'father_dob' =>$row->father_dob,
                            'father_age' =>$row->father_age,
                            'work' =>$row->work,
                            'father_workplace'=>$row->father_workplace,
                            'nationality'=>$row->nationality ,
                            'marital_status'=>$row->marital_status ,
                            'city' =>$row->city,
                            'sub_city'=>$row->sub_city,
                            'woreda'=>$row->woreda ,
                            'kebele'=>$row->kebele,
                            'home_place'=>$row->home_place,
                            'isapproved'=>$row->isapproved ,
                            'dateregister'=>$row->dateregister,
                            'branch'=>$branch,
                            'transportservice'=>$row->transportservice,
                            'asp'=>$row->asp,
                            'academicyear'=>$registerOnYear,
                            'biography'=>$row->biography,
                            'dream' =>$row->dream,
                            'status' =>'Active',
                            'status2' =>$row->status2,
                            'special_needs' =>$row->special_needs,
                            'datemployeed'=>$row->datemployeed ,
                            'gsallary' =>$row->gsallary,
                            'allowance'=>$row->allowance ,
                            'quality_allowance' =>$row->quality_allowance,
                            'position_allowance'=>$row->position_allowance,
                            'home_allowance' =>$row->home_allowance,
                            'gross_sallary' =>$row->gross_sallary,
                            'taxable_income' =>$row->taxable_income,
                            'income_tax' =>$row->income_tax,
                            'pension_7' =>$row->pension_7,
                            'pension_11' =>$row->pension_11,
                            'other' =>$row->other,
                            'netsallary' =>$row->netsallary,
                            'unique_id' =>$row->unique_id,
                            'leave_days' =>$row->leave_days,
                            'mysign'=>$row->mysign ,
                            'finalapproval'=>$row->finalapproval
                        );
                        $queryInsert=$this->db->insert('users',$data);
                        if($queryInsert){
                            echo '1';
                        }else{
                            echo '0';
                        }
                    }
                }
            }            
        }
    }
    function fetch_grade_non_registration(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('academicyear')){
            $grade=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $year=$this->input->post('academicyear');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_grade_non_registration($branch,$grade,$year); 
            }else{
                echo $this->main_model->fetch_grade_non_registration($mybranch,$grade,$year); 
            }
        }
    }
    function fetch_grade_4registration(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        /*preg_match("/(\\d+)([a-zA-Z]+)/", "0982asdlkjJOASS", $matches);
        print("Integer component: " . $matches[1] . "\n");
        print("Letter component: " . $matches[2] . "\n");*/
        if($this->input->post('gradesec_rg')){
            $grade=$this->input->post('gradesec_rg');
            $branch=$this->input->post('branch');
            $year=$this->input->post('academicyear');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->student_registration($branch,$grade,$year); 
            }else{
                echo $this->main_model->student_registration($mybranch,$grade,$year); 
            }
        }
    }
    function Fetch_academicyear_branch_non_registration(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->Fetch_academicyear_branch_non_registration($academicyear); 
        } 
    }
    function Fetch_academicyear_branch(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->fetch_academicyear_branch($academicyear); 
        } 
    }
    function filtergrade_4_non_registeration(){
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        if(isset($_POST['branchRegistration'])){
            $academicyear=$this->input->post('academicyear');
            $branchRegistration=$this->input->post('branchRegistration');
            echo $this->main_model->filtergrade_4_non_registeration($academicyear,$branchRegistration); 
        }
    }
    function filtergrade_4registeration(){
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        if(isset($_POST['branchRegistration'])){
            $academicyear=$this->input->post('academicyear');
            $branchRegistration=$this->input->post('branchRegistration');
            echo $this->main_model->filtergrade_4branch($academicyear,$branchRegistration); 
        }
    }
    function studentPromotionPromoted(){
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $academicyear=$this->input->post('academicyear');
            $nextGrade=$this->input->post('nextGrade');
            foreach($stuid as $stuidNow){
                echo $this->main_model->studentPromotionPromote($stuidNow,$academicyear,$nextGrade); 
            }
        } 
    }
    function studentPromotionDetained(){
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $academicyear=$this->input->post('academicyear');
            foreach($stuid as $stuidNow){
                echo $this->main_model->studentPromotionDetained($stuidNow,$academicyear); 
            }
        } 
    }
    function clearRegistration(){
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->clearRegistration($stuid,$academicyear); 
        }
    }
    function startAutoPromotion(){
        if($this->input->post('toAcademicYear')){
            $toAcademicYear=$this->input->post('toAcademicYear');
            $fromAcademicYear=$this->input->post('fromAcademicYear');
            if($fromAcademicYear >= $toAcademicYear){
                echo '<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i> Please select correct academic year.
            </div></div>';
            }else{
              echo $this->main_model->startAutoPromotion($fromAcademicYear,$toAcademicYear); 
              /*echo 'Waiting...';*/
            }
        }
    }
    function fetchFailedStudents(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('academicyear')){
            $grade=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $year=$this->input->post('academicyear');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchFailedStudents($branch,$grade,$year); 
            }else{
                echo $this->main_model->fetchFailedStudents($mybranch,$grade,$year); 
            }
        }
    }
}