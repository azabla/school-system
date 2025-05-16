<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Summerclass extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        $this->load->library('excel');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='summerclass' order by id ASC ");
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
    public function index($page='summerclass')
    {
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
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
        $accessbranch = sessionUseraccessbranch();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['staffs']=$this->main_model->fetchStaffsForPlacement();
        }else{
            $data['staffs']=$this->main_model->fetchMyStaffsForPlacement($branch);
        }
        $data['fetch_division']=$this->main_model->fetch_schooldivision($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter_summer();
        $data['gradesec']=$this->main_model->fetchSummerGradesec($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['grade']=$this->main_model->fetchSummerGrade($max_year);
        $data['subjects']=$this->main_model->fetchSummerSubjectPlacement($max_year);
        $this->load->view('home-page/'.$page,$data);
    }
    function downloadStuData(){
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $accessbranch = sessionUseraccessbranch();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $queryStuddent=$this->db->query("select username,fname,mname,lname,gender,
            grade,section,father_mobile,mobile,mother_name,dob,age,email,password,city,sub_city,woreda,kebele,dateregister,branch,transportservice,academicyear from summerstudent where usertype='Student' and status='Active' and isapproved='1' and academicyear='$max_year' order by fname,mname,lname ASC ");
        }else{
            $branchName = sessionUserDetailNonStudent();
            $branch=$branchName['branch'];
            $queryStuddent=$this->db->query("select username,fname,mname,lname,gender,
            grade,section,father_mobile,mobile,mother_name,dob,age,email,password,city,sub_city,woreda,kebele,dateregister,branch,transportservice,academicyear from summerstudent where usertype='Student' and status='Active' and isapproved='1' and academicyear='$max_year' and branch='$branch' order by fname,mname,lname ASC ");
        }
        $filename ='Student-Data.csv';  
        header('Content-Type: testx/csv;charset=utf-8');
        header('Content-Disposition: attachment;filename="'.$filename.'"'); 
        $output=fopen('php://output', 'w');
        fputcsv($output,array('Student ID','First Name','Middle Name','Last Name','Gender','Grade','Section','Father Mobile','Mother Mobile','Mother Name','Date of birth','Age','Email','Password','City','Sub city','Woreda','Kebele','Registration Date','Branch','Transport Service','Academic year'));
        foreach ($queryStuddent->result_array() as $row) {
            fputcsv($output,$row);
        } 
        fclose($output);
    }
    function Filter_thisgrade_from_branch_Summer(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->fetch_thisgrade_from_branch_summer($branch,$max_year); 
        }
    }
    function insertsection(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;

        if($this->input->post('section_id')){
            $stu_id=$this->input->post('stu_id');
            $section_id=$this->input->post('section_id');
            $grade=$this->input->post('grade');
            $query=$this->main_model->update_summer_student_section($stu_id,$section_id,$grade);
            if($query){
             $data['notification']='<span class="text-success"><i class="fas fa-check-circle"><i></span>';
            }else{
              $data['notification']='<span class="text-danger">oops.Please try again.</span>';
            }
            echo json_encode($data);
        }
    }
    function filter_grade4placement(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];

        if($this->input->post('grade2placeManual')){
            $grade2place=$this->input->post('grade2placeManual');
            $into=$this->input->post('intoManual');
            $branch=$this->input->post('branchManual');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_grade_4placement_summer($grade2place,$into,$max_year,$branch); 
            }else{
                echo $this->main_model->fetch_grade_4placement_summer($grade2place,$into,$max_year,$mybranch);  
            }
        }
    }
    function fetchSummerClassStatus(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetchSummerClassStatus($max_year);
    }
    function startSummerClass(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array(
            'classname'=>'summerClass',
            'academicyear'=>$max_year,
            'datecreated'=>date('M-d-Y')
        );
        $this->db->insert('startsummerclass',$data);
        
    } 
    function deleteSummerClass(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $this->db->where('academicyear',$max_year);
        $this->db->delete('startsummerclass');
    }
    function importStudent(){
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(!empty($_FILES["importSummerClassStudent"]["name"]))  
        { 
            /*$config['upload_path'] = './mydocument/';
            $config['allowed_types'] ='csv|CSV';
            $config['encrpt_name']=TRUE;
            $this->load->library('upload', $config);*/
            /*if(isset($_POST['insertSummerStudent'])){*/
            /*if($this->upload->do_upload('importSummerClassStudent')){*/
                $file = $_FILES['importSummerClassStudent']['tmp_name'];
                $handle = fopen($file, "r");
                $c = 0;
                while(($filesop = fgetcsv($handle, 1000, ",")) !== false)  
                {  
                    $data=array(
                        'usertype' => 'Student',
                        'username' => $filesop[0],
                        'fname' => $filesop[1],
                        'mname' => $filesop[2],
                        'lname' => $filesop[3],
                        'gender' => $filesop[4],
                        'grade' => $filesop[5],
                        'section' => $filesop[6],
                        'father_mobile' => $filesop[7],
                        'mobile' => $filesop[8],
                        'mother_name' => $filesop[9],
                        'dob' => $filesop[10],
                        'age' => $filesop[11],
                        'email' => $filesop[12],
                        'password' => $filesop[13],
                        'city' => $filesop[14],
                        'sub_city' => $filesop[15],
                        'woreda' => $filesop[16],
                        'kebele' => $filesop[17],
                        'dateregister' => $filesop[19],
                        'branch' => $filesop[20],
                        'transportservice' =>$filesop[21],
                        'academicyear' => $filesop[23],
                        'gradesec' => $filesop[5].$filesop[6],
                        'password2' => $filesop[13],
                        'isapproved' => '1',
                        'status' => 'Active',
                        'unique_id'=>$filesop[0]
                    );
                    if($c<>0){
                        $checkStudent=$this->main_model->summerStudent($filesop[0],$filesop[23]);
                        if($checkStudent){
                            $quary=$this->db->insert('summerstudent',$data);
                        }
                    }
                    $c = $c + 1;
                }
                if($query){
                    echo '<div class="alert alert-success alert-dismissible show fade">
                      <div class="alert-body">
                          <button class="close"  data-dismiss="alert">
                              <span>&times;</span>
                          </button>
                        <i class="fas fa-exclamation-circle"> </i> Saved Successfully.
                      </div>
                    </div>';
                }else{
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                      <div class="alert-body">
                          <button class="close"  data-dismiss="alert">
                              <span>&times;</span>
                          </button>
                        <i class="fas fa-exclamation-circle"> </i> Something wrong.
                      </div>
                    </div>.';
                }
            /*}else{
                echo '<div class="alert alert-warning alert-dismissible show fade">
                      <div class="alert-body">
                          <button class="close"  data-dismiss="alert">
                              <span>&times;</span>
                          </button>
                        <i class="fas fa-exclamation-circle"> </i> File must be CSV.
                      </div>
                </div>.';
            } */      
        }
    }
    function deleteSummerStudentData(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $this->db->where('usertype','Student');
        $this->db->where('academicyear',$max_year);
        $query=$this->db->delete('summerstudent');
        if($query){
            echo '<div class="alert alert-success alert-dismissible show fade">
                      <div class="alert-body">
                          <button class="close"  data-dismiss="alert">
                              <span>&times;</span>
                          </button>
                        <i class="fas fa-exclamation-circle"> </i> Data deleted successfully.
                      </div>
                </div>.';
        }else{
            echo '<div class="alert alert-warning alert-dismissible show fade">
                      <div class="alert-body">
                          <button class="close"  data-dismiss="alert">
                              <span>&times;</span>
                          </button>
                        <i class="fas fa-exclamation-circle"> </i> Ooops Please try again.
                      </div>
                </div>.';
        }
    }
    function Filter_grade_from_branch(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $grands_academicyear=$this->input->post('grands_academicyear');
            echo $this->main_model->fetchGradeFromBranch_summerTransport($branch,$grands_academicyear); 
        }
    }
    function fetchThisGradeStudentIdcard(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $academicyear=$this->input->post('academicyear');
            for($i=0;$i<count($grade);$i++){
                $check[]=$grade[$i];
            }
            echo $this->main_model->fetchThisGradesummer_StudentIdcard($check,$academicyear); 
        }
    }
    function filtersummer_ServicePlace(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $grands_academicyear=$this->input->post('grands_academicyear');
            echo $this->main_model->fetch_summer_servicePlace_branch($branch,$grands_academicyear); 
        }
    }
    function fetchsummer_StudentIdcard(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryGYear = $this->db->query("select gyear from summer_academicyear where year_name='$max_year' ");
        $rowG = $queryGYear->row();
        $gyear=$rowG->gyear;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('servicePlace')){
            $studentList=$this->input->post('studentList');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $placeID=$this->input->post('servicePlace');
            $reportaca=$this->input->post('reportacaID');
            for($i=0;$i<count($placeID);$i++){
                $check[]=$placeID[$i];
            }
            for($i=0;$i<count($studentList);$i++){
                $checkStudent[]=$studentList[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_summer_student_idcard($reportaca,$checkStudent,$gradesec,$check,$branch,$gyear); 
            }else{
                echo $this->main_model->fetch_summer_student_idcard($reportaca,$checkStudent,$gradesec,$check,$mybranch,$gyear); 
            } 
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
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->post('attBranches')){
            $attGradesec=$this->input->post('attGradesec');
            $attBranches=$this->input->post('attBranches');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_gradesec_student_summer($attGradesec,$attBranches,$max_year); 
            }else{
                echo $this->main_model->fetch_gradesec_student_summer($attGradesec,$branch,$max_year); 
            }
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
    function deleteAttendanceSummer(){
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('attendanceId')){
            $attendanceId=$this->input->post('attendanceId');
            $this->main_model->delete_Staffattendance_summer($attendanceId,$max_year);
        }
    }
    function fetchAttendanceReport(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->fetch_attendance_summer($max_year);
        }else{
            echo $this->main_model->fetch_mattendance_summer($max_year,$branch);
        }
    }
    function searchAttendance(){
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->main_model->searchAttendance_summer($searchItem,$max_year);
        }
    }
    function fetchsummer_StudentIdcardWithoutPlace(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryGYear = $this->db->query("select gyear from summer_academicyear where year_name='$max_year' ");
        $rowG = $queryGYear->row();
        $gyear=$rowG->gyear;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('studentList')){
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportacaID');
            $studentList=$this->input->post('studentList');
            for($i=0;$i<count($studentList);$i++){
                $checkStudent[]=$studentList[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchSummerStudentIdCard($reportaca,$checkStudent,$gradesec,$branch,$gyear);
            }else{
                echo $this->main_model->fetchSummerStudentIdCard($reportaca,$checkStudent,$gradesec,$mybranch,$gyear);
            }
        }
    }
    function filterGradeFromBranch(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->filterGradeFromBranch($branch,$max_year); 
        }
    }
    function fecthThisStudent(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gs_gradesec')){
            $gs_branches=$this->input->post('gs_branches');
            $gs_gradesec=$this->input->post('gs_gradesec');
            $grands_academicyear=$this->input->post('grands_academicyear');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fecthThisStudent($gs_branches,$gs_gradesec,$grands_academicyear);
            }else{
                echo $this->main_model->fecthThisStudent($branch,$gs_gradesec,$grands_academicyear);
            }
        } 
    }
    function deleteSummerStudent(){
        if(isset($_POST['post_id'])){
            $id=$this->input->post('post_id');
            $this->main_model->deleteSummerStudent($id);
        }
    }
    function editSummerStudent(){
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('editedId')){
            $editedId=$this->input->post('editedId');
            echo $this->main_model->fetchSummerStudentToEdit($editedId,$max_year);
        }
    }
    function updateSummerStudents(){
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $config['upload_path']    = './profile/';
        $config['allowed_types']  = 'gif|jpg|png|ico';
        $this->load->library('upload', $config);
        $stuid=$this->input->post('stuStuidSummer');
        $username=$this->input->post('stUsernameSummer');
        $fname=$this->input->post('stuFnameSummer');
        $mname=$this->input->post('stuLnameSummer');
        $lname=$this->input->post('stuGfnameSummer');
        $gender=$this->input->post('stuGenderSummer');
        $mobile=$this->input->post('stuMobileSummer');
        $fmobile=$this->input->post('father_mobileSummer');
        $grade=$this->input->post('stuGradeSummer');
        $section=$this->input->post('stuSectionSummer');
        $email=$this->input->post('stuEmailSummer');
        $dob=$this->input->post('stuDobSummer');
        $city=$this->input->post('stuCitySummer');
        $subcity=$this->input->post('stuSubcitySummer');
        $woreda=$this->input->post('stuWoredaSummer');
        $kebele=$this->input->post('stuKebeleSummer');
        $branch=$this->input->post('summerBranch');
        if($this->upload->do_upload('stuProfileSummer')){
            $dataa = $this->upload->data('file_name');
            $data=array(
                'fname'=>$fname,
                'mname'=>$mname,
                'lname'=>$lname,
                'grade'=>$grade,
                'section'=>$section,
                'gradesec'=>$grade.$section,
                'gender'=>$gender,
                'mobile'=>$mobile,
                'father_mobile'=>$fmobile,
                'email'=>$email,
                'dob'=>$dob,
                'city'=>$city,
                'sub_city'=>$subcity,
                'woreda'=>$woreda,
                'kebele'=>$kebele,
                'branch'=>$branch,
                'profile'=>$dataa
            );
            echo $this->main_model->updateSummerStudentDetail($stuid,$username,$data,$max_year);
        }else{
           $data=array(
                'fname'=>$fname,
                'mname'=>$mname,
                'lname'=>$lname,
                'grade'=>$grade,
                'section'=>$section,
                'gradesec'=>$grade.$section,
                'gender'=>$gender,
                'mobile'=>$mobile,
                'father_mobile'=>$fmobile,
                'email'=>$email,
                'dob'=>$dob,
                'city'=>$city,
                'sub_city'=>$subcity,
                'woreda'=>$woreda,
                'kebele'=>$kebele,
                'branch'=>$branch
            );
            echo $this->main_model->updateSummerStudentDetail($stuid,$username,$data,$max_year);
        }
    }
    function fetchSummerSubject(){
        $query =$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetchSummerSubjets($max_year);
    }
    function saveNewSummerSubject(){
        $query =$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['subjectName'])){
          if(!empty($this->input->post('subjectName'))){
            $subjectName=$this->input->post('subjectName');
            $subjectLetter=$this->input->post('subjectLetter');
            $subjectGrade=$this->input->post('subjectGrade');
            $onReportCard=1;
            $date_created=date('M-d-Y');
            for($i=0;$i<count($subjectGrade);$i++){
              $check=$subjectGrade[$i];
              $letteri=$subjectLetter[$i];
              $data=array(
                  'Subj_name'=>$subjectName,
                  'Merged_percent'=>'100',
                  'Grade'=>$check,
                  'letter'=>$letteri,
                  'date_created'=>$date_created,
                  'Academic_Year'=>$max_year,
                  'onreportcard'=>$onReportCard
                );
              $this->main_model->addSummerSubject($subjectName,$check,$max_year,$data);
            }
          }
        }
    }
    function summerSubjectDelete(){
        $query =$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['post_id'])){
          $id=$this->input->post('post_id');
          $this->main_model->deleteSummerSubject($id,$max_year);
        }
    }
    function fetchSummerEvaluations(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetchSummerEvaluations($max_year);
    }
    function postSummerEvaluation(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;

        if(isset($_POST['id'])){
            $id = $this->input->post('id');
            $evname=$this->input->post('evname');
            $percent=$this->input->post('percent');
            foreach ($id as $grade) {
                $query=$this->main_model->addSummerEvaluation($grade,$evname,$max_year);
                if($query){
                    $data=array(
                        'grade'=>$grade,
                        'evname'=>$evname,
                        'academicyear'=>$max_year,
                        'percent'=>$percent,
                        'date_created'=>date('M-d-Y')
                    );
                    $query2=$this->db->insert('summerevaluation',$data);
                    if($query2){
                        echo 'Saved';
                    }else{
                        echo 'Please try again';
                    }
                }
            }
        }
    }
    function deleteSummerEvaluation(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['post_id'])){
            $id=$this->input->post('post_id');
            $evname=$this->input->post('evname');
            $query=$this->main_model->deleteSummerEvaluation($id,$evname,$max_year);
        }
    }
    function fetchSummerPlacement(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $accessbranch = sessionUseraccessbranch();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->fetchSummerStaffPlacement($max_year);
        }else{
            echo $this->main_model->fetch_mystaff_placement($max_year,$branch);
        }
    }
    function postSummerPlacement(){
        if(isset($_POST['id'])){
            $grade = $this->input->post('id');
            $academicyear=$this->input->post('academicyear');
            $staff=$this->input->post('staff');
            $subject=$this->input->post('subject');
            foreach ($grade as $grades) {
                foreach ($subject as $subjects) {
                    $checkSubject=$this->main_model->checkSummerSubject($subjects,$grades,$academicyear);
                    if($checkSubject){
                        $query=$this->main_model->addSummerPlacement($staff,$subjects,$grades,$academicyear);
                        if($query){
                            $data[]=array(
                                'staff'=>$staff,
                                'grade'=>$grades,
                                'academicyear'=>$academicyear,
                                'subject'=>$subjects,
                                'date_created'=>date('M-d-Y')
                            );
                        }
                    }
                }
            }
            $this->db->insert_batch('summerstaffplacement',$data);

        }
    }
    function deleteSummerStaffPlacement(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staff_placement')){
          $staff_placement=$this->input->post('staff_placement');
          $this->main_model->deleteSummerPlacement($staff_placement);
        }
    }
    function importSummerMark(){
        $query =$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $approvedID=$row_branch->id;
        if(!empty($_FILES['uploadSummerMark']["tmp_name"])) {
            $path = $_FILES["uploadSummerMark"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
            foreach($object->getWorksheetIterator() as $worksheet){
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                $subname=$worksheet->getCellByColumnAndRow(2,2)->getValue();
                $gradesec = $worksheet->getCellByColumnAndRow(1,1)->getValue();
                $mybranch = $worksheet->getCellByColumnAndRow(2,1)->getValue();
                if($gradesec!='' && $mybranch!=''){
                    for($col=3;$col <= $highestColumnIndex;$col++) {
                        $evaid = $worksheet->getCellByColumnAndRow($col,2)->getValue();
                        $outof = $worksheet->getCellByColumnAndRow($col,3)->getValue();
                        $markname = $worksheet->getCellByColumnAndRow($col,1)->getValue();
                        $query_check=$this->main_model->checkSummerMarkImport($markname,$subname,$max_year,$gradesec,$mybranch);
                        if($query_check && $outof!='' && $markname!=''){
                            for($row=4; $row <= $highestRow; $row++) {
                                $stuid = $worksheet->getCellByColumnAndRow(0,$row)->getValue();
                                $zeromarkinfo= $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                                if($worksheet->getCellByColumnAndRow($col,$row)!='') {
                                    $value1=$worksheet->getCellByColumnAndRow($col,$row)->getValue();
                                    $value2=$worksheet->getCellByColumnAndRow($col,3)->getValue();
                                    if($value1 > $value2 ) {
                                        $value=0;
                                    }else{
                                        $value = $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                                    }
                                    $data[] = array(
                                      'stuid'  => $stuid,
                                      'subname'=>$subname,
                                      'mgrade'=>$gradesec,
                                      'evaid'=>$evaid,
                                      'quarter'=>'',
                                      'value'=>$value,
                                      'outof'=>$outof,
                                      'academicyear'=>$max_year,
                                      'markname'=>$markname,
                                      'zeromarkinfo'=>$zeromarkinfo,
                                      'approved'=>'1',
                                      'approvedby'=>$approvedID,
                                      'mbranch'=>$mybranch
                                    );
                                }
                            }
                        }
                    }
                }else{
                $this->session->set_flashdata('success','
                  <div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                      </button>
                      <i class="fas fa-check-circle"> </i> Please adjust your excel format properly.
                    </div>
                  </div> ');
                }
            }
            if(!empty($data)){
                $query=$this->db->insert_batch('summermark',$data);
                if($query) {
                    echo ' <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                        </button>
                        <i class="fas fa-check-circle"> </i> Data inserted successfully.
                      </div>
                    </div> ';
                }else{
                    echo ' <div class="alert alert-wa alert-dismissible show fade">
                      <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                        </button>
                        <i class="fas fa-check-circle"> </i> Please try Again.
                      </div>
                    </div> ';
                }
            }else{
                echo ' <div class="alert alert-warning alert-dismissible show fade">
                  <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                    <i class="fas fa-check-circle"> </i> File already exists.
                  </div>
                </div> ';
            }
        }else{
            echo ' <div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i> Please select a file to import.
              </div>
            </div> ';
        }
    }
    function filterSubjectFromSummer(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            echo $this->main_model->filterSubjectFromSummer($gradesec,$max_year); 
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
        $accessbranch = sessionUseraccessbranch();
        if($this->input->POST('gs_gradesec')){
            $gs_branches=$this->input->POST('gs_branches');
            $gs_gradesec=$this->input->POST('gs_gradesec');
            $gs_subject=$this->input->POST('gs_subject');
            if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
                $show=$this->main_model->fetchSummerGradeMarkResult($gs_branches,$gs_gradesec,$gs_subject,$max_year); 
                echo $show;
            }else{
                $show=$this->main_model->fetchSummerGradeMarkResult($branch,$gs_gradesec,$gs_subject,$max_year); 
                echo $show;
            }
        }
    }
    function fetchSummerGradeMark(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query =$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->get('gs_gradesec')){
            $gs_branches=$this->input->get('gs_branches');
            $gs_gradesec=$this->input->get('gs_gradesec');
            $gs_subject=$this->input->get('gs_subject');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $show=$this->main_model->fetchSummerGradeMark($gs_branches,$gs_gradesec,$gs_subject,$max_year);
                echo json_encode($show);
            }else{
                $show=$this->main_model->fetchSummerGradeMark($branch,$gs_gradesec,$gs_subject,$max_year);
                echo json_encode($show);
            } 
        }
    }
    function editOutOf(){
        $accessbranch = sessionUseraccessbranch();
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query =$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        if($this->input->post('subject')){
          $subject=$this->input->post('subject');
          $gradesec=$this->input->post('gradesec');
          $branch=$this->input->post('branch');
          $year=$this->input->post('year');
          $markname=$this->input->post('markname');
          $outof=$this->input->post('outof');
          if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $show=$this->main_model->editMarkNameSummer($branch,$gradesec,$subject,$year,$markname);
          }else{
            $show=$this->main_model->editMarkNameSummer($mybranch,$gradesec,$subject,$year,$markname);
          }
          $output='<div class="row">';
          $output.='<input type="hidden" class="markgradesec" value="'.$gradesec.'">
          <input type="hidden" class="marksubject" value="'.$subject.'">
          <input type="hidden" class="markbranch" value="'.$branch.'">
          <input type="hidden" class="markyear" value="'.$year.'">
          <input type="hidden" class="outofmarkname" value="'.$markname.'">';
          $output.='<div class="col-md-12">';
          foreach ($show as $keyvalue) {
            $output.='<input type="hidden" class="form-control oldOutOf" value="'.$keyvalue->outof.'">';
            $output.='<input type="text" class="form-control updateOutOf" value="'.$keyvalue->outof.'">';
          }
          $output.='<a class="changeOutInfo"></a></div></div>';
          echo $output;
        }
    }
    function updateOutOf(){
        $accessbranch = sessionUseraccessbranch();
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        date_default_timezone_set('Africa/Addis_Ababa');
        $query =$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        if($this->input->post('subject')){
          $subject=$this->input->post('subject');
          $gradesec=$this->input->post('gradesec');
          $branch=$this->input->post('branch');
          $year=$this->input->post('year');
          $oldOutOf=$this->input->post('oldOutOf');
          $updateOutOf=$this->input->post('updateOutOf');
          $markname=$this->input->post('markname');
          if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$branch, 'subname' => $subject,'academicyear'=>$year,'markname'=>$markname,'outof'=>$oldOutOf,'value >'=>$updateOutOf);
            $this->db->where($arrayM);  
            $getCheck=$this->db->get('summermark');
            if($getCheck->num_rows()>0){
              echo '<span class="text-danger">Please insert correct value</span>';
            }else{
              $data1=array(
                'userinfo'=>$user,
                'useraction'=>'Mark Percentage updated',
                'infograde'=>$gradesec,
                'subject'=>$subject,
                'academicyear'=>$year,
                'oldata'=>$oldOutOf,
                'newdata'=>$updateOutOf,
                'updateduser'=>'-',
                'userbranch'=>$branch,
                'actiondate'=> date('Y-m-d H:i:s', time())
              );
              $queryInsert=$this->db->insert('useractions',$data1);
              if($queryInsert){
                $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$branch, 'subname' => $subject,'academicyear'=>$year,'markname'=>$markname,'outof'=>$oldOutOf);
                $this->db->where($arrayM); 
                $this->db->set('outof',$updateOutOf);
                $show=$this->db->update('summermark');
                if($show){
                  echo $updateOutOf;
                }
              }
            }
          }else{
            $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$mybranch, 'subname' => $subject,'academicyear'=>$year,'markname'=>$markname,'outof'=>$oldOutOf,'value >'=>$updateOutOf);
            $this->db->where($arrayM);  
            $getCheck=$this->db->get('summermark');
            if($getCheck->num_rows()>0){
              echo '<span class="text-danger">Please insert correct value</span>';
            }else{
              $data1=array(
                'userinfo'=>$user,
                'useraction'=>'Mark Percentage updated',
                'infograde'=>$gradesec,
                'subject'=>$subject,
                'academicyear'=>$year,
                'oldata'=>$oldOutOf,
                'newdata'=>$updateOutOf,
                'updateduser'=>'-',
                'userbranch'=>$mybranch,
                'actiondate'=> date('Y-m-d H:i:s', time())
              );
              $queryInsert=$this->db->insert('useractions',$data1);
              if($queryInsert){
                $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$mybranch, 'subname' => $subject,'academicyear'=>$year,'markname'=>$markname,'outof'=>$oldOutOf);
                $this->db->where($arrayM); 
                $this->db->set('outof',$updateOutOf);
                $show=$this->db->update('summermark');
                if($show){
                  echo $updateOutOf;
                }
              }
            }
          }
        }
    }
    function exportSummerMarkFormat(){
        $this->load->library('excel');
        $obj = new Excel();
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $accessbranch = sessionUseraccessbranch();
        if(isset($_POST['summerGradesecformat'])){
            $gradesec=$this->input->post('summerGradesecformat');
            $branch1=$this->input->post('SummerBranchFormat');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $listInfo = $this->main_model->exportSummerStudentMarkFormat($gradesec,$max_year,$branch1);
                $evnameinfo = $this->main_model->exportThisGradeSummerEvname($gradesec,$max_year,$branch1);
            }else{
                $listInfo = $this->main_model->exportSummerStudentMarkFormat($gradesec,$max_year,$branch);
                $evnameinfo = $this->main_model->exportThisGradeSummerEvname($gradesec,$max_year,$branch);
            }
            $count_sub=$this->main_model->getAllSummerSubject($gradesec,$max_year);
            foreach ($count_sub as $count_subs) {
                $allsub=$count_subs->all_sub;
                $objWorkSheet = $obj->createSheet($allsub);
                $objWorkSheet->SetCellValue('A3','Id');
                $objWorkSheet->SetCellValue('B3','StuID');
                $objWorkSheet->SetCellValue('C3','Full Name');
                $column = 3;
                foreach($evnameinfo  as $field) {
                    $objWorkSheet->setCellValueByColumnAndRow($column, 1, $field->evname);
                    $objWorkSheet->setCellValueByColumnAndRow($column, 2, $field->eid);
                    $objWorkSheet->setCellValueByColumnAndRow($column, 3, $field->percent);
                    $column++;
                }
                $rowCount = 4;
                foreach ($listInfo as $list) {
                    $objWorkSheet->SetCellValue('A' . $rowCount, $list->id);
                    $objWorkSheet->SetCellValue('B' . $rowCount, $list->username);
                    $objWorkSheet->SetCellValue('C' . $rowCount, strtoupper($list->fname.' '.$list->mname.' '.$list->lname));
                    $rowCount++;   
                }
                $objWorkSheet->SetCellValue('C1', $branch1);
                $objWorkSheet->SetCellValue('B2', '');
                $objWorkSheet->SetCellValue('C2', $count_subs->Subj_name);
                $objWorkSheet->SetCellValue('B1', $gradesec);
                $invalidCharacters = $objWorkSheet->getInvalidCharacters();
                $title = str_replace($invalidCharacters, '', $count_subs->Subj_name);
                $objWorkSheet->setTitle($title);
            }
            $filename =$gradesec.'.xls'; 
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"'); 
            header('Cache-Control: max-age=0'); 
            $objWriter = PHPExcel_IOFactory::createWriter($obj, 'Excel5');
            echo $objWriter->save('php://output');
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
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('subject')){
            $subject=$this->input->post('subject');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $quarter=$this->input->post('quarter');
            $year=$this->input->post('year');
            $markname=$this->input->post('markname');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1')
            {
                $this->db->where('lockmark','0');
                $this->db->where('mgrade',$gradesec);
                $this->db->where('academicyear',$year);
                $this->db->where('subname',$subject);
                $this->db->where('markname',$markname);
                $this->db->where('mbranch',$branch);
                $this->db->delete('summermark');
                echo $this->main_model->fetchSummerGradeMark($branch,$gradesec,$subject,$max_year);
            }else{
                $this->db->where('lockmark','0');
                $this->db->where('mgrade',$gradesec);
                $this->db->where('academicyear',$year);
                $this->db->where('subname',$subject);
                $this->db->where('markname',$markname);
                $this->db->where('mbranch',$branch_me);
                $this->db->delete('summermark');
                echo $this->main_model->fetchSummerGradeMark($branch_me,$gradesec,$subject,$max_year);
            } 
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
    function deleteThismark(){
        $user=$this->session->userdata('username');
        $query=$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('subject')){
            $subject=$this->input->post('subject');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $jo_year=$this->input->post('year');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $this->db->where('mbranch',$branch);
                $this->db->where('mgrade',$gradesec);
                $this->db->where('subname',$subject);
                $this->db->where('academicyear',$jo_year);
                $this->db->where('lockmark','0');
                $query=$this->db->delete('summermark');
                echo $this->main_model->fetchSummerGradeMark($branch,$gradesec,$subject,$max_year);
            }else{
                $this->db->where('mbranch',$branch_me);
                $this->db->where('mgrade',$gradesec);
                $this->db->where('subname',$subject);
                $this->db->where('academicyear',$jo_year);
                $this->db->where('lockmark','0');
                $query=$this->db->delete('summermark');
                echo $this->main_model->fetchSummerGradeMark($branch_me,$gradesec,$subject,$max_year);
            }
        }
    }
    function deleteThisGradeMark(){
        $user=$this->session->userdata('username');
        $query=$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;  
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('subject')){
            $subject=$this->input->post('subject');
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $jo_year=$this->input->post('year');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $this->db->where('lockmark','0');
                $this->db->where('mbranch',$branch);
                $this->db->where('mgrade',$gradesec);
                $this->db->where('academicyear',$jo_year);
                $query=$this->db->delete('summermark');
            }else{
                $this->db->where('lockmark','0');
                $this->db->where('mbranch',$branch_me);
                $this->db->where('mgrade',$gradesec);
                $this->db->where('academicyear',$jo_year);
                $query=$this->db->delete('summermark');
            }
            exit;
        }
    }
    function fetchMarkToEdit(){
        $user=$this->session->userdata('username');
        $query=$this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('edtim')){
            $edtim=$this->input->post('edtim');
            $gradesec=$this->input->post('gradesec');
            $academicyear=$this->input->post('academicyear');
            $branch=$this->input->post('branch');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->selectSummerMarkToEdit($edtim,$gradesec,$academicyear,$branch);
            }else{
                echo $this->main_model->selectSummerMarkToEdit($edtim,$gradesec,$academicyear,$branch_me);
            }
        }
    }
    function FetchUpdatedMark(){
        $user=$this->session->userdata('username');
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('mid')){
            $mid=$this->input->post('mid');
            $gradesec=$this->input->post('gradesec');
            $year=$this->input->post('year');
            $branch=$this->input->post('branch');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->FetchUpdatedMarkSummer($mid,$gradesec,$year,$branch);
            }else{
                echo $this->main_model->FetchUpdatedMarkSummer($mid,$gradesec,$year,$branch_me);
            }
        }
    }
    function updateMarkNow(){
        $user=$this->session->userdata('username');
        $query_branch=$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('outof')){
            $outof=$this->input->post('outof');
            $mid=$this->input->post('mid');
            $value=$this->input->post('value');
            $gradesec=$this->input->post('gradesec');
            $year=$this->input->post('year');
            $branch=$this->input->post('branch');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                if($outof>=$value){
                    echo $this->main_model->updateEditedMarkSummer($outof,$mid,$value,$gradesec,$year,$branch);
                }else{
                    echo'<span class="text-danger"> Please insert correct value.</span>';
                }
            }else{
                if($outof>=$value){
                  echo $this->main_model->updateEditedMarkSummer($outof,$mid,$value,$gradesec,$year,$branch_me);
                }else{
                  echo'<span class="text-danger"> Please insert correct value.</span>';
                }
            }
        }
    }
    function fetchSummerReportcard(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            $queryGYear = $this->db->query("select gyear from summer_academicyear where year_name='$reportaca' ");
            $rowG = $queryGYear->row();
            $gyear=$rowG->gyear;
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $query=$this->main_model->update_reportcardResult($reportaca,$gradesec,$branch);
                if($query){
                    $data=$this->main_model->reportcardByQuarter($reportaca,$gradesec,$branch,$gyear);
                    echo json_encode($data);
                }
            }else{
                $query=$this->main_model->update_reportcardResult($reportaca,$gradesec,$mybranch);
                if($query){
                    $data=$this->main_model->reportcardByQuarter($reportaca,$gradesec,$mybranch,$gyear);
                    echo json_encode($data);
                }
            }
        }
    }
    function searchSummerStudentsToTransportService(){
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->main_model->searchSummerStudentsToTransportService($searchItem,$max_year);
        }
    }
    function fetchCustomIDCard(){
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryGYear = $this->db->query("select gyear from summer_academicyear where year_name='$max_year' ");
        $rowG = $queryGYear->row();
        $gyear=$rowG->gyear;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('stuIdArray')){
            $stuIdArray=$this->input->post('stuIdArray');
            for($i=0;$i<count($stuIdArray);$i++){
                $checkStudent[]=$stuIdArray[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchSummer_StudentCustomIdCard($max_year,$checkStudent,$gyear);
            }else{
                echo $this->main_model->fetchSummer_StudentCustomIdCard($max_year,$checkStudent,$gyear);
            }
        } 
    }
    function save_new_summer_serviceplace(){
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryGYear = $this->db->query("select gyear from summer_academicyear where year_name='$max_year' ");
        $rowG = $queryGYear->row();
        $gyear=$rowG->gyear;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('stuIdArray')){
            $stuIdArray=$this->input->post('stuIdArray');
            $newServicePlaceName=$this->input->post('newServicePlaceName');
            for($i=0;$i<count($stuIdArray);$i++){
                $checkStudent[]=$stuIdArray[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->updateSummer_StudentservicePlace($max_year,$checkStudent,$gyear,$newServicePlaceName);
            }else{
                echo $this->main_model->updateSummer_StudentservicePlace($max_year,$checkStudent,$gyear,$newServicePlaceName);
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
    function checkPlacementFound(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $userType=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];

        if($this->input->post('grade2place')){
            $grade2place=$this->input->post('grade2place');
            $branch2place=$this->input->post('branch2place');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->check_summer_placement_found($branch2place,$grade2place,$max_year); 
            }else{
                echo $this->main_model->check_summer_placement_found($branch,$grade2place,$max_year);; 
            }
        }
    }
    function filterGrade4AutoPlacement(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade2place_auto')){
            $grade2place=trim($this->input->post('grade2place_auto'));
            $branch2place=trim($this->input->post('grands_branchit'));
            $into=trim($this->input->post('into'));
            echo $this->main_model->fetch_grade_summer_autoplacement($branch2place,$grade2place,$into,$max_year);
        }
    }
    function Filter_thisgrade_from_branch(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->fetch_thisgrade_from_branch_summer($branch,$max_year); 
        }
    }
    function fetch_summer_academicyear(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetch_summer_academicyear($max_year); 
    }
    function deleteSummerAcademicYear(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('yearName')){
          $yearName=$this->input->post('yearName');
          $this->main_model->deleteSummerAcademicYear($yearName);
        }
    }
    function postSummerAcademicYear(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('academicyearName')){
          $academicyearName=$this->input->post('academicyearName');
          $academicyearNameG=$this->input->post('academicyearNameG');
          $data=array(
            'year_name'=>$academicyearName,
            'gyear'=>$academicyearNameG,
            'date_created'=>date('M-d-Y')
          );
          $this->db->insert('summer_academicyear',$data);
        }
    }
    function searchStudent(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->searchSummerStudents($searchItem,$max_year);
            }/*else{
                echo $this->main_model->searchSummerStudents($searchItem,$max_year);
            }*/
        }
    }
    function fetchNullMark(){
        $accessbranch = sessionUseraccessbranch();
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        if($this->input->post('gs_gradesec')){
            $gs_branches=$this->input->post('gs_branches');
            $gs_gradesec=$this->input->post('gs_gradesec');
            $gs_subject=$this->input->post('gs_subject');
            if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->outof_error_Summer($gs_branches,$gs_gradesec,$gs_subject,$max_year); 
            }else{
                echo $this->main_model->outof_error_Summer($branch,$gs_gradesec,$gs_subject,$max_year); 
            }
        }
    }
}