<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        $this->load->library('secure');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentDE' order by id ASC ");  
        if($this->session->userdata('username') == '' || $uperStuDE->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='student')
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
       
        if(isset($_POST['drop_id'])){
            $id=$this->input->post('drop_id');
            $this->main_model->inactive_student($id);
        }
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['gradeGroups']=$this->main_model->studentRegistrationGrade($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function delete_student(){
        $user=$this->session->userdata('username');
        $data1=array();
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['post_id'])){
            $id=$this->input->post('post_id');
            $fetchStudent=$this->db->query("select fname,mname,lname,branch,academicyear, grade from users where id='$id' and academicyear='$max_year' ");
            foreach($fetchStudent->result() as $row){
                $grade=$row->grade;
                $year=$row->academicyear;
                $fName=$row->fname;
                $mName=$row->mname;
                $branch=$row->branch;
                $data1[]=array(
                    'userinfo'=>$user,
                    'useraction'=>'Student Deleted',
                    'infograde'=>$grade,
                    'subject'=>'',
                    'quarter'=>'',
                    'academicyear'=>$year,
                    'oldata'=>'',
                    'newdata'=>'',
                    'updateduser'=>$fName.' ' .$mName,
                    'userbranch'=>$branch,
                    'actiondate'=> date('Y-m-d H:i:s', time())
                );
                $queryInsert=$this->db->insert_batch('useractions',$data1);
                if($queryInsert){
                    $query=$this->main_model->delete_student($id);
                }
            }           
        }
    }
    function filterGradesecfromBranch(){
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->filterGradesecfromBranch($academicyear); 
        }
    }
    function Filter_grade_from_branch(){
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $grands_academicyear=$this->input->post('grands_academicyear');
            echo $this->main_model->fetch_grade_from_branch($branch,$grands_academicyear); 
        }
    }
    function filterGradeForGroup(){
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->fetch_grade_from_branch($branch,$max_year); 
        }
    }
    function filterOnlyGradeFromBranch(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $grands_academicyear=$this->input->post('grands_academicyear');
            echo $this->main_model->fetchOnlyGradeFromBranch_group($branch,$grands_academicyear); 
        }
    }
    function filterOnlyGradeFromBranchForGroup(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->fetchOnlyGradeFromBranch_group($branch,$max_year); 
        }
    }
    function filter_gradesec_ongrade_change(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grades')){
            $grades=$this->input->post('grades');
            echo $this->main_model->filter_gradesec_ongrade_change($grades,$max_year); 
        }
    }
    function downloadStuData(){
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        $accessbranch = sessionUseraccessbranch();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $queryStuddent=$this->db->query("select username,fname,mname,lname ,last_oflast_name ,gender,grade,section,special_needs,previous_school,father_mobile,father_dob,father_age,work,father_workplace,marital_status,nationality,mobile,mother_name,dob,age,email,optional_email,password,city,sub_city,woreda,kebele,home_place,dateregister,branch,transportservice,asp,academicyear from users where usertype='Student' and status='Active' and isapproved='1' and academicyear='$max_year' order by fname,mname,lname ASC ");
        }else{
            $branchName = sessionUserDetailNonStudent();
            $branch=$branchName['branch'];
            $queryStuddent=$this->db->query("select username,fname,mname,lname,last_oflast_name ,gender,grade, section,special_needs,previous_school,father_mobile,father_dob,father_age,work,father_workplace,marital_status,nationality,mobile,mother_name,dob,age,email,optional_email,password,city,sub_city,woreda,kebele,home_place,dateregister,branch,transportservice,asp,academicyear from users where usertype='Student' and status='Active' and isapproved='1' and academicyear='$max_year' and branch='$branch' order by fname,mname,lname ASC ");
        }
        $filename ='Student-Data.csv';  
        header('Content-Type: testx/csv;charset=utf-8');
        header('Content-Disposition: attachment;filename="'.$filename.'"'); 
        $output=fopen('php://output', 'w');
        fputcsv($output,array('Student ID','First Name','Middle Name','Last Name','Last of Last Name','Gender','Grade','Section','Special Needs','Previous School','Father Mobile','Father Date of Birth','Father Age','Work','Work Place','Marital Status','Nationality','Mother Mobile','Mother Name','Date of birth','Age','Email','Optional email','Password','City','Sub city','Woreda','Kebele','home_place','Registration/Joined Date','Branch','Transport Service','After School Program','Academic year'));
        foreach ($queryStuddent->result_array() as $row) {
            fputcsv($output,$row);
        } 
        fclose($output);
    }
    function searchStudent(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->searchStudents($searchItem,$max_year);
            }else{
                echo $this->main_model->searchAdminStudents($searchItem,$branch,$max_year);
            }
        }
    }
    function Fecth_thistudent(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        if($this->input->post('gs_branches')){
            $gs_branches=$this->input->post('gs_branches');
            $gs_gradesec=$this->input->post('gs_gradesec');
            $onlyGrade=$this->input->post('onlyGrade');
            $grands_academicyear=$this->input->post('grands_academicyear');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_branchstudents($gs_branches,$onlyGrade,$gs_gradesec,$grands_academicyear);
            }else{
                echo $this->main_model->fetch_branchstudents($branch,$onlyGrade,$gs_gradesec,$grands_academicyear);
            }
        } 
    }
    function editstudent(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('editedId')){
            $editedId=$this->input->post('editedId');
            $newAcademicYear=$this->input->post('newAcademicYear');
            echo $this->main_model->fetch_student_toedit($editedId,$newAcademicYear);
        }
    }
    function leavingRequest(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('editedId')){
            $editedId=$this->input->post('editedId');
            $newAcademicYear=$this->input->post('newAcademicYear');
            echo $this->main_model->fetch_student_toLeavingRequest($editedId,$newAcademicYear);
        }
    }
    function viewStudentPrint(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('editedId')){
            $editedId=$this->input->post('editedId');
            echo $this->main_model->viewStudentPrint($editedId,$max_year);
        }
    }
    function resetPassword(){
        if($this->input->post('editedId')){
            $editedId=$this->input->post('editedId');
            $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 6; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $temp_pass= implode($pass); //turn the array into a string
            $passHash=hash('sha256', $temp_pass);
            $data=array(
                'password'=>$passHash,
                'password2'=>$passHash
            );
            echo $this->main_model->reset_student_password($editedId,$data,$temp_pass);
        }
    }
    function updateStudents(){

        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $config['upload_path']    = './profile/';
        $config['allowed_types']  = 'gif|jpg|png|ico';
        $this->load->library('upload', $config);
        $stuAcademicYear=$this->input->post('stuAcademicYear');
        $stuid=$this->input->post('stuStuid');
        $username=$this->input->post('stUsername');
        $fname=$this->input->post('stuFname');
        $mname=$this->input->post('stuLname');
        $lname=$this->input->post('stuGfname');
        $gender=$this->input->post('stuGender');
        $mobile=$this->input->post('stuMobile');
        $fmobile=$this->input->post('father_mobile');
        $grade=$this->input->post('stuGrade');
        $section=$this->input->post('stuSection');
        $email=$this->input->post('stuEmail');
        $optional_email=$this->input->post('stuEmail2');
        $dob=$this->input->post('stuDob');
        $stuAge=$this->input->post('stuAge');
        $city=$this->input->post('stuCity');
        $subcity=$this->input->post('stuSubcity');
        $woreda=$this->input->post('stuWoreda');
        $kebele=$this->input->post('stuKebele');
        $homeplace=$this->input->post('stuHomePlace');
        $branchName=$this->input->post('studentbranchName');
        $transportService=$this->input->post('transportService');
        $asp=$this->input->post('asp');
        $stuMotherName=$this->input->post('stuMotherName');

        $llfname=$this->input->post('stuLLfname');
        $yearJoined=$this->input->post('yearStudentJoined');
        $specialNeeds=$this->input->post('student_special_needs');
        $previousSchool=$this->input->post('previous_schoolName');
        $maritalStatus=$this->input->post('marital_Status');
        $fdob=$this->input->post('fatherDateoB');
        $fatherAge=$this->input->post('father_Age');
        $workType=$this->input->post('work_type');
        $workPlace=$this->input->post('work_place');
        $nationality=$this->input->post('country_nationality');
        //fayda number
        $faydanum=$this->input->post('faydanum');
        $fayda_number = $this->secure->encrypt($faydanum);
        if($this->upload->do_upload('stuProfile')){
            $dataa = $this->upload->data('file_name');
            $data=array(
                'fname'=>$fname,
                'mname'=>$mname,
                'lname'=>$lname,
                'gender'=>$gender,
                'mobile'=>$mobile,
                'mother_name'=>$stuMotherName,
                'father_mobile'=>$fmobile,
                'email'=>$email,
                'optional_email'=>$optional_email,
                'last_oflast_name'=>$llfname,
                'dateregister'=>$yearJoined,
                'profile'=>$dataa
            );
            $this->db->where(array('unique_id'=>$stuid));
            $this->db->where('academicyear',$stuAcademicYear);
            $this->db->set('father_dob',$fdob);
            $this->db->set('father_age',$fatherAge);
            $this->db->set('dob',$dob);
            $this->db->set('age',$stuAge);
            $this->db->set('special_needs',$specialNeeds);
            $this->db->set('previous_school',$previousSchool);
            $this->db->set('marital_status',$maritalStatus);
            $this->db->set('work',$workType);
            $this->db->set('father_workplace',$workPlace);
            $this->db->set('nationality',$nationality);
            $this->db->set('asp',$asp);
            $this->db->set('branch',$branchName);
            $this->db->set('city',$city);
            $this->db->set('sub_city',$subcity);
            $this->db->set('woreda',$woreda);
            $this->db->set('kebele',$kebele);
            $this->db->set('transportservice',$transportService);
            $this->db->set('home_place',$homeplace);
            $this->db->set('grade',$grade);
            $this->db->set('section',$section);
            $this->db->set('gradesec',$grade.$section);
            $queryUpdate=$this->db->update('users');
            if($queryUpdate){
                echo $this->main_model->update_student_detail($stuid,$username,$data,$max_year);
            }
        }else{
           $data=array(
                'fname'=>$fname,
                'mname'=>$mname,
                'lname'=>$lname,
                'gender'=>$gender,
                'mobile'=>$mobile,
                'mother_name'=>$stuMotherName,
                'father_mobile'=>$fmobile,
                'email'=>$email,
                'optional_email'=>$optional_email,
                'last_oflast_name'=>$llfname,
                'dateregister'=>$yearJoined
            );
            $this->db->where(array('unique_id'=>$stuid));
            $this->db->where('academicyear',$stuAcademicYear);
            $this->db->set('father_dob',$fdob);
            $this->db->set('father_age',$fatherAge);
            $this->db->set('dob',$dob);
            $this->db->set('age',$stuAge);
            $this->db->set('special_needs',$specialNeeds);
            $this->db->set('previous_school',$previousSchool);
            $this->db->set('marital_status',$maritalStatus);
            $this->db->set('work',$workType);
            $this->db->set('father_workplace',$workPlace);
            $this->db->set('nationality',$nationality);
            $this->db->set('branch',$branchName);
            $this->db->set('asp',$asp);
            $this->db->set('city',$city);
            $this->db->set('sub_city',$subcity);
            $this->db->set('woreda',$woreda);
            $this->db->set('kebele',$kebele);
            $this->db->set('transportservice',$transportService);
            $this->db->set('home_place',$homeplace);
            $this->db->set('grade',$grade);
            $this->db->set('section',$section);
            $this->db->set('gradesec',$grade.$section);
            //fayda number
            $this->db->set('fayda_number',$fayda_number);

            $queryUpdate=$this->db->update('users');
            if($queryUpdate){
                echo $this->main_model->update_student_detail($stuid,$username,$data,$max_year);
            }
        }
    }
    function fecthThiStudentAttendance(){
        $quarterName = sessionQuarterDetail();
        $max_quarter=$quarterName['quarter'];
        if($this->input->post('stuID')){
            $stuID=$this->input->post('stuID');
            $yearattende=$this->input->post('yearattende');
            echo $this->main_model->fecthThiStudentAttendance($stuID,$yearattende,$max_quarter);
        }
    }
    function searchStudentsToTransportService(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->searchStudentsToTransportService($searchItem,$max_year);
            }else{
                echo $this->main_model->searchStudentsToTransportServiceNotAccess($searchItem,$branch,$max_year);
            }
            
        }
    }
    function saveNewTransportPlace(){
        $user=$this->session->userdata('username');
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('stuIdArray')){
            $stuIdArray=$this->input->post('stuIdArray');
            $takeAction=trim($this->input->post('takeAction'));
            if($takeAction=='dropGroup'){
                foreach($stuIdArray as $stuIdArrays){
                    $this->db->where('username',$stuIdArrays);
                    $this->db->where('academicyear',$max_year);
                    $this->db->set('status','Inactive');
                    $queryUpdate=$this->db->update('users');
                }
                if($queryUpdate){
                    echo '<div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Group Droped successfully.
                    </div></div>';
                }else{
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Please try again.
                    </div></div>'; 
                }
            } 
            else if($takeAction=='deleteGroup'){
                $queryUpdate='';
                foreach($stuIdArray as $stuIdArrays){
                    $data1=array();
                    $fetchStudent=$this->db->query("select fname,mname,lname,branch,academicyear, grade from users where username='$stuIdArrays' and academicyear='$max_year' ");
                    foreach($fetchStudent->result() as $row){
                        $grade=$row->grade;
                        $year=$row->academicyear;
                        $fName=$row->fname;
                        $mName=$row->mname;
                        $branch=$row->branch;
                        $data1=array(
                            'userinfo'=>$user,
                            'useraction'=>'Student Deleted',
                            'infograde'=>$grade,
                            'subject'=>'',
                            'quarter'=>'',
                            'academicyear'=>$year,
                            'oldata'=>'',
                            'newdata'=>'',
                            'updateduser'=>$fName.' ' .$mName,
                            'userbranch'=>$branch,
                            'actiondate'=> date('Y-m-d H:i:s', time())
                        );
                        $queryInsert=$this->db->insert('useractions',$data1);
                        if($queryInsert){
                            $this->db->where('username',$stuIdArrays);
                            $this->db->where('academicyear',$max_year);
                            $queryUpdate=$this->db->delete('users');
                        }
                    }
                }
                if($queryUpdate){
                    echo '<div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Group Deleted successfully.
                    </div></div>';
                }else{
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Please try again.
                    </div></div>'; 
                }
            }
            else if($takeAction=='adjustTransPlace'){
                $newServiceTransPlace=trim($this->input->post('newServiceTransPlace'));
                if($newServiceTransPlace!==''){
                    foreach($stuIdArray as $stuIdArrays){
                        $this->db->where('username',$stuIdArrays);
                        $this->db->where('academicyear',$max_year);
                        $this->db->set('transportservice',$newServiceTransPlace);
                        $queryUpdate=$this->db->update('users');
                    }
                    if($queryUpdate){
                        echo '<div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Transport Service updated successfully.
                        </div></div>';
                    }else{
                        echo '<div class="alert alert-warning alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Please try again.
                        </div></div>'; 
                    }
                }else{
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Please Enter new Transport Place.
                    </div></div>';
                } 
            }else if($takeAction=='ASPON'){
                foreach($stuIdArray as $stuIdArrays){
                    $dataAdd=array();
                    $this->db->where('username',$stuIdArrays);
                    $this->db->where('academicyear',$max_year);
                    $this->db->set('asp','Yes');
                    $updateASP=$this->db->update('users');

                    $this->db->where('username',$stuIdArrays);
                    $this->db->where('academicyear',$max_year);
                    $queryFetchASP=$this->db->get('users');
                    foreach($queryFetchASP->result() as $row){
                        $userName=$row->username;
                        $queyCheck=$this->db->query("select * from users_remote where academicyear='$max_year' and username='$userName' ");
                        if($queyCheck->num_rows() < 1){
                            $dataAdd[]=array(
                                'username'=>$row->username,
                                'usertype'=>$row->usertype,
                                'fname'=>$row->fname,
                                'mname'=>$row->mname,
                                'lname'=>$row->lname,
                                'mobile'=>$row->mobile,
                                'father_mobile'=>$row->father_mobile,
                                'email'=>$row->email,
                                'profile'=>$row->profile,
                                'grade'=>$row->grade,
                                'section'=>$row->section,
                                'gradesec'=>$row->gradesec,
                                'dob'=>$row->dob,
                                'age'=>$row->age,
                                'gender'=>$row->gender,
                                'password'=>$row->password,
                                'password2'=>$row->password2,
                                'mother_name'=>$row->mother_name,
                                'father_name'=>$row->father_name,
                                'city'=>$row->city,
                                'sub_city'=>$row->sub_city,
                                'woreda'=>$row->woreda,
                                'kebele'=>$row->kebele,
                                'home_place'=>$row->home_place,
                                'isapproved'=>$row->isapproved,
                                'dateregister'=>$row->dateregister,
                                'branch'=>$row->branch,
                                'transportservice'=>$row->transportservice,
                                'asp'=>'Yes',
                                'academicyear'=>$row->academicyear,
                                'biography'=>$row->biography,
                                'dream'=>$row->dream,
                                'status'=>$row->status,
                                'status2'=>$row->status2,
                                'datemployeed'=>$row->datemployeed,
                                'unique_id'=>$row->unique_id,
                                'mysign'=>$row->mysign,
                                'finalapproval'=>$row->finalapproval
                            );
                        }
                    }
                    if(!empty($dataAdd)){
                        $queryADDONASP=$this->db->insert_batch('users_remote',$dataAdd);
                    }else{
                        $queryADDONASP='<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Please try again.
                    </div></div>';
                    }
                }
                if($queryADDONASP){
                    echo '<div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Added on ASP successfully.
                    </div></div>';
                }else{
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Please try again.
                    </div></div>'; 
                } 
            }else if($takeAction=='ASPOFF'){
                foreach($stuIdArray as $stuIdArrays){
                    $this->db->where('username',$stuIdArrays);
                    $this->db->where('academicyear',$max_year);
                    $this->db->set('asp','No');
                    $updateASP=$this->db->update('users');

                    $this->db->where('username',$stuIdArrays);
                    $this->db->where('academicyear',$max_year);
                    $queryUpdate=$this->db->delete('users_remote');
                }
                if($queryUpdate){
                    echo '<div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Removed from ASP successfully.
                    </div></div>';
                }else{
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Please try again.
                    </div></div>'; 
                } 
            }
            else if($takeAction=='sectionGroup'){
                $newSection=trim($this->input->post('newSection'));
                if($newSection!==''){
                    $stuIdArray=$this->input->post('stuIdArray');
                    for($i=0;$i<count($stuIdArray);$i++){
                        $checkStudent[]=$stuIdArray[$i];
                    }
                    echo $this->main_model->editStudentSectionwithMark($checkStudent,$newSection,$max_year);
                }else{
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Please Enter Section.
                    </div></div>';
                } 
            }
            else if($takeAction=='gradeGroup'){
                $newGrade=trim($this->input->post('newGrade'));
                if($newGrade!==''){
                    foreach($stuIdArray as $stuIdArrays){
                        $queryFetchSection=$this->db->query("select section from users where academicyear='$max_year' and username='$stuIdArrays' ");
                        if($queryFetchSection->num_rows()>0){
                            $queryRow=$queryFetchSection->row_array();
                            $section=$queryRow['section'];

                            $this->db->where('username',$stuIdArrays);
                            $this->db->where('academicyear',$max_year);
                            $this->db->set('grade',$newGrade);
                            $this->db->set('gradesec',$newGrade.$section);
                            $queryUpdate=$this->db->update('users');
                        }
                    }
                    if($queryUpdate){
                        echo '<div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Group Grade updated successfully.
                        </div></div>';
                    }else{
                        echo '<div class="alert alert-warning alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Please try again.
                        </div></div>'; 
                    }
                }else{
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Please Enter Grade.
                    </div></div>';
                } 
            }
            else if($takeAction=='branchGroup'){
                $branchGroup=trim($this->input->post('branchGroup'));
                if($branchGroup!==''){
                    $stuIdArray=$this->input->post('stuIdArray');
                    for($i=0;$i<count($stuIdArray);$i++){
                        $checkStudent[]=$stuIdArray[$i];
                    }
                    echo $this->main_model->editStudentBranchwithMark($checkStudent,$branchGroup,$max_year);
                }else{
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Please Enter Branch.
                    </div></div>';
                } 
            }
            else if($takeAction=='branchGroupNoMark'){
                $branchGroupNoMark=trim($this->input->post('branchGroupNoMark'));
                if($branchGroupNoMark!==''){
                    foreach($stuIdArray as $stuIdArrays){
                        $queryFetchSection=$this->db->query("select section from users where academicyear='$max_year' and username='$stuIdArrays' ");
                        if($queryFetchSection->num_rows()>0){
                            $queryRow=$queryFetchSection->row_array();
                            $section=$queryRow['section'];

                            $this->db->where('username',$stuIdArrays);
                            $this->db->where('academicyear',$max_year);
                            $this->db->set('branch',$branchGroupNoMark);
                            $queryUpdate=$this->db->update('users');
                        }
                    }
                    if($queryUpdate){
                        echo '<div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Group branch updated successfully.
                        </div></div>';
                    }else{
                        echo '<div class="alert alert-warning alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Please try again.
                        </div></div>'; 
                    }
                }else{
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Please Enter Branch.
                    </div></div>';
                } 
            }
        }
    }
    function changeDefaultGroupEdit(){
        $user=$this->session->userdata('username');
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        if($this->input->post('groupBranch')){
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $groupBranch=$this->input->post('groupBranch');
            }else{
                $groupBranch=$branch;
            }
            $groupSections=$this->input->post('groupSection');
            $actionType=$this->input->post('actionType');
            foreach($groupSections as $groupSection){
                if($actionType=='deleteBranchGroup'){
                    $data1=array();
                    $data1=array(
                        'userinfo'=>$user,
                        'useraction'=>'Student Deleted',
                        'infograde'=>$groupSection,
                        'subject'=>'',
                        'quarter'=>'',
                        'academicyear'=>$max_year,
                        'oldata'=>'',
                        'newdata'=>'',
                        'updateduser'=>'',
                        'userbranch'=>$groupBranch,
                        'actiondate'=> date('Y-m-d H:i:s', time())
                    );
                    $queryInsert=$this->db->insert('useractions',$data1);
                    if($queryInsert){
                        $this->db->where('branch',$groupBranch);
                        $this->db->where('gradesec',$groupSection);
                        $this->db->where('academicyear',$max_year);
                        $queryUpdate=$this->db->delete('users');
                    }
                    if($queryUpdate){
                        echo '<div class="alert alert-light alert-dismissible show fade">
                                <div class="alert-body">
                                <i class="fas fa-check-circle"> </i> Group '.$groupSection.' Deleted successfully.
                            </div></div>';
                    }else{
                        echo '<div class="alert alert-warning alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Please try again.
                        </div></div>';
                    }
                }elseif($actionType=='dropBranchGroup'){
                    $this->db->where('branch',$groupBranch);
                    $this->db->where('gradesec',$groupSection);
                    $this->db->where('academicyear',$max_year);
                    $this->db->set('status','Inactive');
                    $queryUpdate=$this->db->update('users');
                    if($queryUpdate){
                        echo '<div class="alert alert-light alert-dismissible show fade">
                                <div class="alert-body">
                                <i class="fas fa-check-circle"> </i> Group '.$groupSection.' archived successfully.
                            </div></div>';
                    }else{
                        echo '<div class="alert alert-warning alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Please try again.
                        </div></div>';
                    }
                }elseif($actionType=='undropBranchGroup'){
                    $this->db->where('branch',$groupBranch);
                    $this->db->where('gradesec',$groupSection);
                    $this->db->where('academicyear',$max_year);
                    $this->db->set('status','Active');
                    $queryUpdate=$this->db->update('users');
                    if($queryUpdate){
                        echo '<div class="alert alert-light alert-dismissible show fade">
                                <div class="alert-body">
                                <i class="fas fa-check-circle"> </i> Group '.$groupSection.' unarchived successfully.
                            </div></div>';
                    }else{
                        echo '<div class="alert alert-warning alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Please try again.
                        </div></div>';
                    }
                }elseif($actionType=='aspBranchGroupON'){
                    /*$this->db->where('branch',$groupBranch);
                    $this->db->where('gradesec',$groupSection);
                    $this->db->where('academicyear',$max_year);
                    $this->db->set('asp','Yes');
                    $queryUpdate=$this->db->update('users');*/
                    $this->db->where('branch',$groupBranch);
                    $this->db->where('gradesec',$groupSection);
                    $this->db->where('academicyear',$max_year);
                    $this->db->set('asp','Yes');
                    $updateASP=$this->db->update('users');

                    $dataAdd=array();
                    $this->db->where('branch',$groupBranch);
                    $this->db->where('gradesec',$groupSection);
                    $this->db->where('academicyear',$max_year);
                    $queryFetchASP=$this->db->get('users');
                    foreach($queryFetchASP->result() as $row){
                        $userName=$row->username;
                        $queyCheck=$this->db->query("select * from users_remote where academicyear='$max_year' and username='$userName' ");
                        if($queyCheck->num_rows() < 1){
                            $dataAdd[]=array(
                                'username'=>$row->username,
                                'usertype'=>$row->usertype,
                                'fname'=>$row->fname,
                                'mname'=>$row->mname,
                                'lname'=>$row->lname,
                                'mobile'=>$row->mobile,
                                'father_mobile'=>$row->father_mobile,
                                'email'=>$row->email,
                                'profile'=>$row->profile,
                                'grade'=>$row->grade,
                                'section'=>$row->section,
                                'gradesec'=>$row->gradesec,
                                'dob'=>$row->dob,
                                'age'=>$row->age,
                                'gender'=>$row->gender,
                                'password'=>$row->password,
                                'password2'=>$row->password2,
                                'mother_name'=>$row->mother_name,
                                'father_name'=>$row->father_name,
                                'city'=>$row->city,
                                'sub_city'=>$row->sub_city,
                                'woreda'=>$row->woreda,
                                'kebele'=>$row->kebele,
                                'home_place'=>$row->home_place,
                                'isapproved'=>$row->isapproved,
                                'dateregister'=>$row->dateregister,
                                'branch'=>$row->branch,
                                'transportservice'=>$row->transportservice,
                                'asp'=>$row->asp,
                                'academicyear'=>$row->academicyear,
                                'biography'=>$row->biography,
                                'dream'=>$row->dream,
                                'status'=>$row->status,
                                'status2'=>$row->status2,
                                'datemployeed'=>$row->datemployeed,
                                'unique_id'=>$row->unique_id,
                                'mysign'=>$row->mysign,
                                'finalapproval'=>$row->finalapproval
                            );
                        }
                    }
                    if(!empty($dataAdd)){
                        $queryADDONASP=$this->db->insert_batch('users_remote',$dataAdd);
                    }else{
                        $queryADDONASP='<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> Please try again.
                    </div></div>';
                    }
                    if($queryADDONASP){
                        echo '<div class="alert alert-light alert-dismissible show fade">
                                <div class="alert-body">
                                <i class="fas fa-check-circle"> </i> Group '.$groupSection.' Registered on ASP successfully.
                            </div></div>';
                    }else{
                        echo '<div class="alert alert-warning alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Please try again.
                        </div></div>';
                    }
                }elseif($actionType=='aspBranchGroupOFF'){
                    $this->db->where('branch',$groupBranch);
                    $this->db->where('gradesec',$groupSection);
                    $this->db->where('academicyear',$max_year);
                    $this->db->set('asp','No');
                    $updateASP=$this->db->update('users');

                    $this->db->where('branch',$groupBranch);
                    $this->db->where('gradesec',$groupSection);
                    $this->db->where('academicyear',$max_year);
                    $queryUpdate=$this->db->delete('users_remote');
                    if($queryUpdate){
                        echo '<div class="alert alert-light alert-dismissible show fade">
                                <div class="alert-body">
                                <i class="fas fa-check-circle"> </i> Group '.$groupSection.' Removed from ASP successfully.
                            </div></div>';
                    }else{
                        echo '<div class="alert alert-warning alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Please try again.
                        </div></div>';
                    }
                }
            }
        }
    }
    function changeDefaultGroupEditBranch(){
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        if($this->input->post('groupBranch')){
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $groupBranch=$this->input->post('groupBranch');
            }else{
                $groupBranch=$branch;
            }
            $groupSections=$this->input->post('groupSection');
            $newBranchName=$this->input->post('newBranchName');
            $actionType=$this->input->post('actionType');
            foreach($groupSections as $groupSection){
                if($actionType=='changeBranchGroup' && $groupBranch!=$newBranchName){
                    $this->db->where('branch',$groupBranch);
                    $this->db->where('gradesec',$groupSection);
                    $this->db->where('academicyear',$max_year);
                    $this->db->set('branch',$newBranchName);
                    $queryUpdate=$this->db->update('users');
                    if($queryUpdate){
                        echo '<div class="alert alert-light alert-dismissible show fade">
                                <div class="alert-body">
                                <i class="fas fa-check-circle"> </i> Group '.$groupSection.' changed successfully.
                            </div></div>';
                    }else{
                        echo '<div class="alert alert-warning alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Please try again.
                        </div></div>';
                    }
                }else{
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                            <div class="alert-body">
                            <i class="fas fa-check-circle"> </i> Please select different branch.
                        </div></div>';
                }
            }
        }
    }
    function fetch_brachto_defaultChange(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('groupBranch')){
            $oldBranch=$this->input->post('groupBranch');
            echo $this->main_model->fetch_brachto_defaultChange($oldBranch,$max_year);
        }
    }
    function deleteStudentData(){
        $data1=array();
        $user=$this->session->userdata('username');
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        $data1=array(
            'userinfo'=>$user,
            'useraction'=>'Student Deleted',
            'infograde'=>'All',
            'subject'=>'',
            'quarter'=>'',
            'academicyear'=>$max_year,
            'oldata'=>'',
            'newdata'=>'',
            'updateduser'=>'All',
            'userbranch'=>'All',
            'actiondate'=> date('Y-m-d H:i:s', time())
        );
        $queryInsert=$this->db->insert('useractions',$data1);
        if($queryInsert){
            $this->db->where('usertype','Student');
            $this->db->where('academicyear',$max_year);
            $query=$this->db->delete('users');
            if($query){
                echo ' <i class="fas fa-check-circle"> </i> Student data cleared successfully.';
            }else{
                echo ' <i class="fas fa-times-circle"> </i> Please try again.';
            }
        }
    }
    function fetch_dropout_students(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetch_dropout_students();
    }
}