<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller {
  public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $importFile=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='ImportExport' and allowed='importFile' order by id ASC ");
        if($this->session->userdata('username') == '' || $importFile->num_rows()< 1 || $userLevel!='1'){
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
  public function index($page='import')
  {
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $today=date('y-m-d');
    if(!empty($_FILES['importwhat']['tmp_name'])){
      if(isset($_POST['importmater'])){
        $imsubject=$this->input->post('imsubject');
        if($imsubject===trim('student')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            if(!empty($filesop[32]) && !empty($filesop[29]) && !empty($filesop[0]) && !empty($filesop[1])){
              $usertype = 'Student';
              $username = trim($filesop[0]);
              $stu_id=trim($filesop[0]);
              $name = trim($filesop[1]);
              $fathername = trim($filesop[2]);
              $gfathername = trim($filesop[3]);
              $llname = trim($filesop[4]);
              $gender = trim(ucfirst(strtolower($filesop[5])));
              $grade=preg_replace("/[\s-]+/", "", $filesop[6]);
              $section = trim(ucfirst($filesop[7]));
              $specialNeeds=trim($filesop[8]);
              $previousSchool=trim($filesop[9]);
              $fathermobile = trim($filesop[10]);
              $fdob = trim($filesop[11]);
              $fAge = trim($filesop[12]);
              $fwork = trim($filesop[13]);
              $workplace = trim($filesop[14]);
              $martialStatus =trim(ucfirst(strtolower($filesop[15])));
              $Nationality = trim(ucfirst(strtolower($filesop[16])));
              $mobile = trim($filesop[17]);
              $mothername = trim($filesop[18]);
              $old_dob = trim($filesop[19]);
              if($old_dob!=''){
                $old_dob2 = str_replace('/', '-', $old_dob);
                $dob=date("Y-m-d", strtotime($old_dob2) );
              }else{
                $dob='';
              }
              $age = trim($filesop[20]);
              $email = trim($filesop[21]);
              $password = trim($filesop[22]);
              $city = trim($filesop[23]);
              $subcity = trim($filesop[24]);
              $woreda = trim($filesop[25]);
              $kebele = trim($filesop[26]);
              $homeplace = trim($filesop[27]);
              $registrationdate = trim($filesop[28]);
              $stringBranch = trim(ucfirst(strtolower($filesop[29])));
              $branch = str_replace(' ', '', $stringBranch);
              $transportService = trim($filesop[30]);
              $asp = trim(ucfirst(strtolower($filesop[31])));
              $academicyear = trim($filesop[32]);
              if($section!=''){
                $grasection = $grade.$section;
              }else{
                $grasection = '';
              }
              $confpassword = trim($filesop[22]);
              $isapprove = '1';
              $status = 'Active';
              if($c<>0){
                $this->db->where('username = ',$username);
                $this->db->or_where('unique_id = ',$stu_id);
                $queryCheck=$this->db->get('users');
                if($queryCheck->num_rows() > 0){
                  $dataNotInserted[]=array(
                    'username'=>$username,
                    'usertype'=>$usertype,
                    'unique_id'=>$stu_id,
                    'fname'=>$name,
                    'mname'=>$fathername,
                    'lname'=>$gfathername,
                    'mobile'=>$mobile
                  );
                }else{
                  $dataInserted[]=array(
                    'username'=>$username,
                    'usertype'=>$usertype,
                    'unique_id'=>$stu_id,
                    'fname'=>$name,
                    'mname'=>$fathername,
                    'lname'=>$gfathername,
                    'last_oflast_name'=>$llname,
                    'previous_school'=>$previousSchool,
                    'mobile'=>$mobile,
                    'father_mobile'=>$fathermobile,
                    'father_dob'=>$fdob,
                    'father_age'=>$fAge,
                    'work'=>$fwork,
                    'father_workplace'=>$workplace,
                    'nationality'=>$Nationality,
                    'marital_status'=>$martialStatus,
                    'email'=>$email,
                    'grade'=>$grade,
                    'section'=>$section,
                    'gradesec'=>$grasection,
                    'dob'=>$dob,
                    'age'=>$age,
                    'gender'=>$gender,
                    'password'=>hash('sha256', $password),
                    'password2'=>hash('sha256', $password),
                    'mother_name'=>$mothername,
                    'city'=>$city,
                    'sub_city'=>$subcity,
                    'woreda'=>$woreda,
                    'kebele'=>$kebele,
                    'home_place'=>$homeplace,
                    'isapproved'=>$isapprove,
                    'dateregister'=>$registrationdate,
                    'branch'=>$branch,
                    'transportservice'=>$transportService,
                    'asp'=>$asp,
                    'academicyear'=>$academicyear,
                    'special_needs'=>$specialNeeds,
                    'status'=>$status
                  ); 
                }    
              }
              $c = $c + 1;
            }else{
              break;
              $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-exclamation-circle"> </i> Please check either your CSV excel format or fields that are mandatory.
                </div>
              </div>.');
            }
          }
          if(!empty($dataInserted)){
            $queryInserted=$this->db->insert_batch('users',$dataInserted);
            if(!empty($dataNotInserted) && $queryInserted){
              $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                 <p>'.implode(', ', array_map(function ($entry) {
                  return ($entry[key($entry)]);
                  }, $dataNotInserted)).'</p>
                  <i class="fas fa-exclamation-circle"> </i>The Above students are Not imported.Please try again with different student ID.
                </div>
              </div>');
            }else if(empty($dataNotInserted) && $queryInserted){
              $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-check-circle"> </i>All students are imported Successfully.
                </div>
              </div>');
            }else{
              $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-exclamation-circle"> </i> Please try again with different student ID.
                </div>
              </div>.');
            }
          }else{
            $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                  </button>
                <i class="fas fa-exclamation-circle"> </i> All Student is Not Imported. Please try again either with different student ID or adjust your excel format.
              </div>
            </div>.');
          }

          /*if($query){
            $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
              <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                  </button>
                <i class="fas fa-exclamation-circle"> </i> Student data imported Successfully.
              </div>
            </div>');
          }else{
            $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                  </button>
                <i class="fas fa-exclamation-circle"> </i> Please try again with different student ID.
              </div>
            </div>.');
          }*/
        }
        /*---------------------------------------------------*/
        if($imsubject===trim('remoteStudent')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            if(!empty($filesop[23]) && !empty($filesop[20]) && !empty($filesop[0]) && !empty($filesop[1])){
              $usertype = 'Student';
              $username = trim($filesop[0]);
              $stu_id=trim($filesop[0]);
              $name = trim($filesop[1]);
              $fathername = trim($filesop[2]);
              $gfathername = trim($filesop[3]);
              $gender = trim(ucfirst(strtolower($filesop[4])));
              $grade=preg_replace("/[\s-]+/", "", $filesop[5]);
              $section = trim($filesop[6]);
              $fathermobile = trim($filesop[7]);
              $mobile = trim($filesop[8]);
              $mothername = trim($filesop[9]);
              $old_dob = trim($filesop[10]);
              if($old_dob!=''){
                $old_dob2 = str_replace('/', '-', $old_dob);
                $dob=date("Y-m-d", strtotime($old_dob2) );
              }else{
                $dob='';
              }
              $age = trim($filesop[11]);
              $email = trim($filesop[12]);
              $password = trim($filesop[13]);
              $city = trim($filesop[14]);
              $subcity = trim($filesop[15]);
              $woreda = trim($filesop[16]);
              $kebele = trim($filesop[17]);
              $homeplace = trim($filesop[18]);
              $registrationdate = trim($filesop[19]);
              $branch = trim(ucfirst(strtolower($filesop[20])));
              $transportService = trim($filesop[21]);
              $asp = trim(ucfirst(strtolower($filesop[22])));
              $academicyear = trim($filesop[23]);
              if($section!=''){
                $grasection = $grade.$section;
              }else{
                $grasection = '';
              }
              $confpassword = trim($filesop[13]);
              $isapprove = '1';
              $status = 'Active';
              if($c<>0){
                $this->db->where('username = ',$username);
                $this->db->or_where('unique_id = ',$stu_id);
                $queryCheck=$this->db->get('users_remote');
                if($queryCheck->num_rows() > 0){
                  $dataNotInserted[]=array(
                    'username'=>$username,
                    'usertype'=>$usertype,
                    'unique_id'=>$stu_id,
                    'fname'=>$name,
                    'mname'=>$fathername,
                    'lname'=>$gfathername,
                    'mobile'=>$mobile
                  );
                }else{
                  $dataInserted[]=array(
                    'username'=>$username,
                    'usertype'=>$usertype,
                    'unique_id'=>$stu_id,
                    'fname'=>$name,
                    'mname'=>$fathername,
                    'lname'=>$gfathername,
                    'mobile'=>$mobile,
                    'father_mobile'=>$fathermobile,
                    'email'=>$email,
                    'grade'=>$grade,
                    'section'=>$section,
                    'gradesec'=>$grasection,
                    'dob'=>$dob,
                    'age'=>$age,
                    'gender'=>$gender,
                    'password'=>hash('sha256', $password),
                    'password2'=>hash('sha256', $password),
                    'mother_name'=>$mothername,
                    'city'=>$city,
                    'sub_city'=>$subcity,
                    'woreda'=>$woreda,
                    'kebele'=>$kebele,
                    'home_place'=>$homeplace,
                    'isapproved'=>$isapprove,
                    'dateregister'=>$registrationdate,
                    'branch'=>$branch,
                    'transportservice'=>$transportService,
                    'asp'=>$asp,
                    'academicyear'=>$academicyear,
                    'status'=>$status
                  ); 
                }    
              }
              $c = $c + 1;
            }else{
              break;
              $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-exclamation-circle"> </i> Please check either your CSV excel format or fields that are mandatory.
                </div>
              </div>.');
            }
          }
          if(!empty($dataInserted)){
            $queryInserted=$this->db->insert_batch('users_remote',$dataInserted);
            if(!empty($dataNotInserted) && $queryInserted){
              $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                 <p>'.implode(', ', array_map(function ($entry) {
                  return ($entry[key($entry)]);
                  }, $dataNotInserted)).'</p>
                  <i class="fas fa-exclamation-circle"> </i>The Above students are Not imported.Please try again with different student ID.
                </div>
              </div>');
            }else if(empty($dataNotInserted) && $queryInserted){
              $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-check-circle"> </i>All students are imported Successfully.
                </div>
              </div>');
            }else{
              $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-exclamation-circle"> </i> Please try again with different student ID.
                </div>
              </div>.');
            }
          }else{
            $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                  </button>
                <i class="fas fa-exclamation-circle"> </i> All Student is Not Imported. Please try again either with different student ID or adjust your excel format.
              </div>
            </div>.');
          }
        }
        if($imsubject===trim('attendance')){
          $config['upload_path'] = './mydocument/';
          $config['allowed_types'] ='csv|docx|pdf';
          $config['encrpt_name']=TRUE;
          $this->load->library('upload', $config);
          if($this->upload->do_upload('importwhat')){
            $file = $_FILES['importwhat']['tmp_name'];
            $handle = fopen($file, "r");
            $c = 0;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
              $data[]=array(
                'stuid' =>$filesop[1],
                'absentdate' =>$filesop[2],
                'absentype'=>$filesop[3],
                'academicyear' => $max_year,
                'attend_by' =>$user,
                'totabs' =>$filesop[4],
                'quarterab' =>$filesop[5]
              );
              $c = $c + 1;
            }
            $query=$this->db->insert_batch('attendance',$data);
            if($query){
               $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
              <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                  </button>
                 <i class="fas fa-check-circle"> </i> Attendance Imported Successfully.
              </div></div>');
            }else{
                $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                  </button>
                 <i class="fas fa-check-circle"> </i> ooops Please try again.
              </div></div>');
            }
          }else{
            $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                  </button>
                 <i class="fas fa-check-circle"> </i>ooops Please adjust your fiel as CSV.
              </div></div>');
          }
        }
        if($imsubject===trim('subject')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
          {
            if($c<>0){ 
            $subject= $filesop[0];
            $grade=$filesop[1];
              $this->db->where('Subj_name',$subject);
              $this->db->where('Grade',$grade);
              $this->db->where('Academic_Year',$max_year);
              $queryCheck=$this->db->get('subject');
              if($queryCheck->num_rows() < 1){
                $data[]=array(
                  'Subj_name' =>$filesop[0],
                  'Grade' =>$filesop[1],
                  'letter'=>$filesop[2],
                  'Academic_Year' => $filesop[3],
                  'onreportcard' =>$filesop[4],
                  'Merged_percent'=>'100',
                  'date_created'=>date('M-d-Y')
                );
              }
            }
            $c = $c + 1;
          }
          if(!empty($data)){
            $query=$this->db->insert_batch('subject',$data);
            if($query){
              $this->session->set_flashdata('success','Subject Imported Successfully');
              redirect('import','refresh');
            }else{
              $this->session->set_flashdata('error','Please try again. Either subject exists or Network problem.');
               redirect('import','refresh');
            }
          }else{
            $this->session->set_flashdata('error','File empty.');
            redirect('import','refresh');
          }
        }
        if($imsubject===trim('evaluation')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
          {
            $data=array(
              'evname' =>$filesop[0],
              'quarter' =>$filesop[1],
              'grade'=>$filesop[2],
              'percent' => $filesop[3],
              'academicyear' =>$filesop[4],
              'date_created'=>date('M-d-Y')
            );
            if($c<>0){  
              $query=$this->main_model->dump_import_evaluation($data);
            }
            $c = $c + 1;
          }
          if($query){
            $this->session->set_flashdata('success','Evaluation Imported Successfully');
            redirect('import','refresh');
          }else{
            $this->session->set_flashdata('error','Please try again. Either Evaluation exists or Network problem.');
             redirect('import','refresh');
          }
        }
        if($imsubject===trim('updateTransportService')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
          {
            if(!empty($filesop[1])){
              $transportService=$filesop[1];
              $username=$filesop[0];
              if($c<>0){
              $data[]=array(
                'username'=>$username,
                'transportservice'=>$transportService
              );  
                /*$this->db->where('username',$filesop[0]);
                $this->db->where('academicyear',$max_year);
                $this->db->set('transportservice',$filesop[1]);*/
                
              }
              $c = $c + 1;
            }
          }
          $this->db->where('academicyear',$max_year);
          $query=$this->db->update_batch('users',$data,'username');
          if($query){
            $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-check-circle"> </i> Transport service updated successfully.
                </div>
              </div>');
            redirect('import','refresh');
          }else{
            $this->session->set_flashdata('error','Please try again.');
             redirect('import','refresh');
          }
        }
        if($imsubject===trim('updateStudentBranch')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
          {
            if(!empty($filesop[1])){
              $branch=trim(ucfirst(strtolower($filesop[1])));
              $username=$filesop[0];
              if($c<>0){
                $data[]=array(
                  'username'=>$username,
                  'branch'=>$branch
                );                  
              }
              $c = $c + 1;
            }
          }
          $this->db->where('academicyear',$max_year);
          $query=$this->db->update_batch('users',$data,'username');
          if($query){
            $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-check-circle"> </i> Branch updated successfully.
                </div>
              </div>');
            redirect('import','refresh');
          }else{
            $this->session->set_flashdata('error','Please try again.');
            redirect('import','refresh');
          }
        }
        if($imsubject===trim('updateStudentGender')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
          {
            if(!empty($filesop[1])){
              $gender=trim(ucfirst(strtolower($filesop[1])));
              $username=$filesop[0];
              if($c<>0){
                $data[]=array(
                  'username'=>$username,
                  'gender'=>$gender
                );                  
              }
              $c = $c + 1;
            }
          }
          $this->db->where('academicyear',$max_year);
          $query=$this->db->update_batch('users',$data,'username');
          if($query){
            $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-check-circle"> </i> Student gender updated successfully.
                </div>
              </div>');
            redirect('import','refresh');
          }else{
            $this->session->set_flashdata('error','Please try again.');
            redirect('import','refresh');
          }
        }
        if($imsubject===trim('updateStudentAge')){
          $file = $_FILES['importwhat']['tmp_name'];
          $fileCheck = pathinfo($_FILES['importwhat']['name']);
          if($fileCheck['extension'] == 'csv' || $fileCheck['extension'] == 'CSV'){
            $handle = fopen($file, "r");
            $c = 0;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
              if(!empty($filesop[1])){
                $age=trim(ucfirst(strtolower($filesop[1])));
                $username=$filesop[0];
                if($c<>0){
                  $data[]=array(
                    'username'=>$username,
                    'age'=>$age
                  );                  
                }
                $c = $c + 1;
              }
            }
            $this->db->where('academicyear',$max_year);
            $query=$this->db->update_batch('users',$data,'username');
            if($query){
              $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                    <i class="fas fa-check-circle"> </i> Student age updated successfully.
                  </div>
                </div>');
              redirect('import','refresh');
            }else{
              $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                    <i class="fas fa-check-circle"> </i> No changes found.
                  </div>
                </div>');
              redirect('import','refresh');
            }
          }else{
            $this->session->set_flashdata('success','<div class="alert alert-warning alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                    <i class="fas fa-check-circle"> </i> Uploaded file should be CSV.
                  </div>
                </div>');
              redirect('import','refresh');
          }
        }
        if($imsubject===trim('updateStudentDOB')){
          $file = $_FILES['importwhat']['tmp_name'];
          $fileCheck = pathinfo($_FILES['importwhat']['name']);
          if($fileCheck['extension'] == 'csv' || $fileCheck['extension'] == 'CSV'){
            $handle = fopen($file, "r");
            $c = 0;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
              if(!empty($filesop[1])){
                $old_dob=trim(ucfirst(strtolower($filesop[1])));
                $old_dob2 = str_replace('/', '-', $old_dob);
                $dob=date("Y-m-d", strtotime($old_dob2) );
                $username=$filesop[0];
                if($c<>0){
                  $data[]=array(
                    'username'=>$username,
                    'dob'=>$dob
                  );                  
                }
                $c = $c + 1;
              }
            }
            $this->db->where('academicyear',$max_year);
            $queryDOB=$this->db->update_batch('users',$data,'username');
            if($queryDOB){
              $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                    <i class="fas fa-check-circle"> </i> Student DOB updated successfully.
                  </div>
                </div>');
            }else{
              $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                    <i class="fas fa-check-circle"> </i> No changes found.
                  </div>
                </div>');
            }
          }else{
            $this->session->set_flashdata('success','<div class="alert alert-warning alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                    <i class="fas fa-check-circle"> </i> Uploaded file should be CSV.
                  </div>
                </div>');
              redirect('import','refresh');
          }
        }
        if($imsubject===trim('updateStaffsDivision')){
          $file = $_FILES['importwhat']['tmp_name'];
          $fileCheck = pathinfo($_FILES['importwhat']['name']);
          if($fileCheck['extension'] == 'csv' || $fileCheck['extension'] == 'CSV'){
            $handle = fopen($file, "r");
            $c = 0;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
              if(!empty($filesop[1])){
                $status2=trim(ucfirst(strtolower($filesop[1])));
                $username=$filesop[0];
                if($c<>0){
                  $data[]=array(
                    'username'=>$username,
                    'status2'=>$status2
                  );                  
                }
                $c = $c + 1;
              }
            }
            $this->db->where('academicyear',$max_year);
            $queryDOB=$this->db->update_batch('users',$data,'username');
            if($queryDOB){
              $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                    <i class="fas fa-check-circle"> </i> Staffs division updated successfully.
                  </div>
                </div>');
            }else{
              $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                    <i class="fas fa-check-circle"> </i> No changes found.
                  </div>
                </div>');
            }
          }else{
            $this->session->set_flashdata('success','<div class="alert alert-warning alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                    <i class="fas fa-check-circle"> </i> Uploaded file should be CSV.
                  </div>
                </div>');
              redirect('import','refresh');
          }
        }
        if($imsubject===trim('updateStudentInformation')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;     
          $dataNotInserted=array();     
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            $dataInserted=array();
            if(!empty($filesop[0]) && !empty($filesop[32])){
              $username = trim($filesop[0]);
              $stu_id=trim($filesop[0]);
              $name = trim($filesop[1]);
              $fathername = trim($filesop[2]);
              $gfathername = trim($filesop[3]);
              $llname = trim($filesop[4]);
              $gender = trim(ucfirst(strtolower($filesop[5])));
              $grade=preg_replace("/[\s-]+/", "", $filesop[6]);
              $section = trim($filesop[7]);
              $specialNeeds=trim($filesop[8]);
              $previousSchool=trim($filesop[9]);
              $fathermobile = trim($filesop[10]);
              $fdob = trim($filesop[11]);
              $fAge = trim($filesop[12]);
              $fwork = trim($filesop[13]);
              $workplace = trim($filesop[14]);
              $martialStatus = trim($filesop[15]);
              $Nationality = trim($filesop[16]);
              $mobile = trim($filesop[17]);
              $mothername = trim($filesop[18]);
              $old_dob = trim($filesop[19]);
              if($old_dob!=''){
                $old_dob2 = str_replace('/', '-', $old_dob);
                $dob=date("Y-m-d", strtotime($old_dob2) );
              }else{
                $dob='';
              }
              $age = trim($filesop[20]);
              $email = trim($filesop[21]);
              $password = trim($filesop[22]);
              $city = trim($filesop[23]);
              $subcity = trim($filesop[24]);
              $woreda = trim($filesop[25]);
              $kebele = trim($filesop[26]);
              $homeplace = trim($filesop[27]);
              $registrationdate = trim($filesop[28]);
              $branch = trim(ucfirst(strtolower($filesop[29])));
              $transportService = trim($filesop[30]);
              $asp = trim(ucfirst(strtolower($filesop[31])));
              $academicyear = trim($filesop[32]);
              if($section!=''){
                $grasection = $grade.$section;
              }else{
                $grasection = '';
              }
              $confpassword = trim($filesop[22]);
              if($c<>0){
              $this->db->where('username',$username);
              $this->db->where('academicyear',$max_year);
              $queryCheck=$this->db->get('users');
              if($queryCheck->num_rows() < 1){
                $dataNotInserted[]=array(
                  'username'=>$username
                );
              }else{
                  $dataInserted[]=array(
                    'username'=>$username,
                    'unique_id'=>$stu_id,
                    'fname'=>$name,
                    'mname'=>$fathername,
                    'lname'=>$gfathername,
                    'last_oflast_name'=>$llname,
                    'mobile'=>$mobile,
                    'father_mobile'=>$fathermobile,
                    'father_dob'=>$fdob,
                    'father_age'=>$fAge,
                    'work'=>$fwork,
                    'father_workplace'=>$workplace,
                    'nationality'=>$Nationality,
                    'marital_status'=>$martialStatus,
                    'email'=>$email,
                    'grade'=>$grade,
                    'section'=>$section,
                    'gradesec'=>$grasection,
                    'dob'=>$dob,
                    'age'=>$age,
                    'gender'=>$gender,
                    'password'=>hash('sha256', $password),
                    'password2'=>hash('sha256', $password),
                    'mother_name'=>$mothername,
                    'city'=>$city,
                    'sub_city'=>$subcity,
                    'woreda'=>$woreda,
                    'kebele'=>$kebele,
                    'home_place'=>$homeplace,
                    'dateregister'=>$registrationdate,
                    'branch'=>$branch,
                    'transportservice'=>$transportService,
                    'asp'=>$asp,
                    'special_needs'=>$specialNeeds,
                    'previous_school'=>$previousSchool,
                    'academicyear'=>$academicyear,
                  ); 
                  $this->db->where(array('academicyear' => $max_year, 'username' => $username));
                  $queryInserted=$this->db->update_batch('users',$dataInserted,'username'); 
                }     
              }
              $c = $c + 1;
            }else{
              break;
              $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-exclamation-circle"> </i> Please check either your CSV excel format or mandatory fields.
                </div>
              </div>.');
            }
          }
          if(!empty($dataNotInserted)){
            $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
               <p>'.implode(', ', array_map(function ($entry) {
                return ($entry[key($entry)]);
                }, $dataNotInserted)).'</p>
                <i class="fas fa-exclamation-circle"> </i>The above students are not updated.Please try again with different student ID.
              </div>
            </div>');
          }else if(!empty($queryInserted)){
            $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
              <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                  </button>
                <i class="fas fa-exclamation-circle"> </i> Updated successfully.
              </div>
            </div>.');
          }else{
            $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-exclamation-circle"> </i> Please check either your CSV excel format or mandatory fields.
                </div>
              </div>.');
          }          
        }
        if($imsubject===trim('updateStudentMobile')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
          {
            if(!empty($filesop[0])){
              $motherMobile=$filesop[1];
              $fatherMobile=$filesop[2];
              $username=$filesop[0];
              if($c<>0){
                $data[]=array(
                  'username'=>$username,
                  'mobile'=>$motherMobile,
                  'father_mobile'=>$fatherMobile
                );                 
              }
              $c = $c + 1;
            }
          }
          $this->db->where('academicyear',$max_year);
          $query=$this->db->update_batch('users',$data,'username');
          if($query){
            $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-check-circle"> </i> Student mobile updated successfully.
                </div>
              </div>');
            redirect('import','refresh');
          }else{
            $this->session->set_flashdata('error','Please try again.');
             redirect('import','refresh');
          }
        }
        if($imsubject===trim('updateStudentEmail')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
          {
            if(!empty($filesop[0])){
              $stEmail=$filesop[1];
              $secondEmail=$filesop[2];
              $username=$filesop[0];
              if($c<>0){
                $data[]=array(
                  'username'=>$username,
                  'email'=>$stEmail,
                  'optional_email'=>$secondEmail
                );                 
              }
              $c = $c + 1;
            }
          }
          $this->db->where('academicyear',$max_year);
          $query=$this->db->update_batch('users',$data,'username');
          if($query){
            $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-check-circle"> </i> Student email updated successfully.
                </div>
              </div>');
            redirect('import','refresh');
          }else{
            $this->session->set_flashdata('error','Please try again.');
             redirect('import','refresh');
          }
        }
        if($imsubject===trim('updateStaffPayroll')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
          {
            if(!empty($filesop[13])){
              $StaffUsername=$filesop[0];
              $QualityAllowance=$filesop[1];
              $TransportAllowance=$filesop[2];
              $PositionAllowance=$filesop[3];
              $HomeAllowance=$filesop[4];
              $BasicSalary=$filesop[5];
              $GrossSallary=$filesop[6];
              $TaxableIncome=$filesop[7];
              $Incometax=$filesop[8];
              $Pension7=$filesop[9];
              $Pension11=$filesop[10];
              $Other=$filesop[11];
              $NetPayment=$filesop[12];
              $datemployeed=$filesop[13];
              if($c<>0){
                $data[]=array(
                  'username'=>$StaffUsername,
                  'quality_allowance'=>$QualityAllowance,
                  'allowance'=>$TransportAllowance,
                  'position_allowance'=>$PositionAllowance,
                  'home_allowance'=>$HomeAllowance,
                  'gsallary'=>$BasicSalary,
                  'gross_sallary'=>$GrossSallary,
                  'taxable_income'=>$TaxableIncome,
                  'income_tax'=>$Incometax,
                  'pension_7'=>$Pension7,
                  'pension_11'=>$Pension11,
                  'other'=>$Other,
                  'netsallary'=>$NetPayment,
                  'datemployeed'=>$datemployeed
                );                 
              }
              $c = $c + 1;
            }
          }
          $query=$this->db->update_batch('users',$data,'username');
          if($query){
            $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-check-circle"> </i> Staff Payroll updated successfully.
                </div>
              </div>');
            redirect('import','refresh');
          }else{
            $this->session->set_flashdata('error','Please try again.');
             redirect('import','refresh');
          }
        }
        if($imsubject===trim('staffs')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            if(!empty($filesop[0]) && !empty($filesop[19])){
              $username = trim($filesop[0]);
              $usertype = trim(ucfirst(strtolower($filesop[1])));
              $name = trim($filesop[2]);
              $fathername = trim($filesop[3]);
              $gfathername = trim($filesop[4]);
              $gender = trim($filesop[5]);
              $mobile = trim($filesop[6]);
              /*$dob = trim($filesop[7]);*/
              $old_dob = trim($filesop[7]);
              $old_dob2 = str_replace('/', '-', $old_dob);
              $dob=date("Y-m-d", strtotime($old_dob2) );
              $age = trim($filesop[8]);
              $martialStatus = trim($filesop[9]);
              $email = trim($filesop[10]);
              $password = trim($filesop[11]);
              $city = trim($filesop[12]);
              $subcity = trim($filesop[13]);
              $woreda = trim($filesop[14]);
              $kebele = trim($filesop[15]);
              $registrationdate = trim($filesop[16]);
              $branch = trim($filesop[17]);
              $status2 = trim($filesop[18]);
              $academicyear = trim($filesop[19]);
              if($c<>0){       
                $query=$this->main_model->import_staffs($username);
                $data[]=array(
                    'username'=>$username,
                    'usertype'=>$usertype,
                    'fname'=>$name,
                    'mname'=>$fathername,
                    'lname'=>$gfathername,
                    'mobile'=>$mobile,
                    'email'=>$email,
                    'dob'=>$dob,
                    'age'=>$age,
                    'marital_status'=>$martialStatus,
                    'gender'=>$gender,
                    'password'=>hash('sha256', $password),
                    'password2'=>hash('sha256', $password),
                    'city'=>$city,
                    'sub_city'=>$subcity,
                    'woreda'=>$woreda,
                    'kebele'=>$kebele,
                    'isapproved'=>'1',
                    'dateregister'=>$registrationdate,
                    'branch'=>$branch,
                    'academicyear'=>$academicyear,
                    'unique_id'=>$username,
                    'status'=>'Active',
                    'status2'=>$status2
                  );
              }
              $c = $c + 1; 
            }  
          }
          if($query){
            if(!empty($data)){
              $this->db->insert_batch('users',$data);
              $this->session->set_flashdata('success','
                <div class="alert alert-success alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                  <i class="fas fa-check-circle"> </i> User data Imported Successfully.
              </div></div>');
            }else{
               $this->session->set_flashdata('error','
              <div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Please try again.
            </div></div>');
            }
          }else{
            $this->session->set_flashdata('error','
              <div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Please try again username exists.
            </div></div>');
          }
        }
        if($imsubject===trim('importBookRegistration')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          $dataArray=array();
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            if(!empty($filesop[0])){
              $book_id = trim($filesop[0]);
              $book_name = trim($filesop[1]);
              $book_grade = trim($filesop[2]);
              $book_price = trim($filesop[3]);
              $book_quantity = trim($filesop[4]);
              $book_branch= trim($filesop[5]);
              $query_check=$this->main_model->check_book_stock($book_name,$book_id,$book_grade);
              if($query_check->num_rows()<1){
                if($c<>0){ 
                  $dataArray[]=array(
                      'book_name'=>$book_name,
                      'book_id'=>$book_id,
                      'book_price'=>$book_price,
                      'book_quantity'=>$book_quantity,
                      'book_grade'=>$book_grade,
                      'book_branch'=>$book_branch,
                      'date_created'=>date('M-d-Y'),
                      'created_by'=>$user
                  );      
                }
                $c = $c + 1; 
              }
            }  
          }
          if(!empty($dataArray)){
            $this->db->insert_batch('book_stock',$dataArray);
            $this->session->set_flashdata('success','
              <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Data imported successfully.
            </div></div>');
          }else{
             $this->session->set_flashdata('error','
            <div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                  </button>
              <i class="fas fa-check-circle"> </i> Please try again.
          </div></div>');
          }
        }
        if($imsubject===trim('importInventoryRegistration')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          $dataArray=array();
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            if(!empty($filesop[0])){
              $item_id = trim($filesop[0]);
              $item_name = trim($filesop[1]);
              $item_category = trim($filesop[2]);
              $item_type = trim($filesop[3]);
              $service_type = trim($filesop[4]);
              $stock = trim($filesop[5]);
              $unit_price = trim($filesop[6]);
              $total_price = trim($filesop[7]);
              $expiry_date = trim($filesop[8]);
              $item_branch = trim($filesop[9]);
              $query_check=$this->main_model->check_item($item_name,$item_id,$item_branch);
              if($query_check->num_rows()<1){
                if($c<>0){ 
                  $dataArray[]=array(
                      'item_name'=>$item_name,
                      'item_id'=>$item_id,
                      'item_category'=>$item_category,
                      'item_type_color'=>$item_type,
                      'item_service'=>$service_type,
                      'item_price'=>$unit_price,
                      'item_quantity'=>$stock,
                      'item_expiry'=>$expiry_date,
                      'item_branch'=>$item_branch,
                      'date_created'=>date('M-d-Y'),
                      'created_by'=>$user
                  );      
                }
                $c = $c + 1; 
              }
            }  
          }
          if(!empty($dataArray)){
            $this->db->insert_batch('stock_item',$dataArray);
            $this->session->set_flashdata('success','
              <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Data imported successfully.
            </div></div>');
          }else{
             $this->session->set_flashdata('error','
            <div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                  </button>
              <i class="fas fa-check-circle"> </i> Please try again.
          </div></div>');
          }
        }
        if($imsubject===trim('importZippedPhoto')){
          if($_FILES['importwhat']['name'] != '')  
          {
            $config['upload_path'] = './profile/'; 
            $config['allowed_types'] = 'zip'; 
            $config['max_size'] = '5120'; // max_size in kb (5 MB) 
            $config['file_name'] = $_FILES['importwhat']['name'];
            $this->load->library('upload',$config); 
            if($this->upload->do_upload('importwhat')){
              $file_name = $_FILES['importwhat']['name'];  
              $array = explode(".", $file_name);  
              $name = $array[0];  
              $ext = $array[1];  
              if($ext === 'zip')  
              {  
                $path = './profile/';  
                $location = $path . $file_name;  
                if(move_uploaded_file($_FILES['importwhat']['tmp_name'], $location))  
                {  
                  $zip = new ZipArchive;  
                  if($zip->open($location)){  
                    $zip->extractTo($path);  
                    $zip->close();  
                  }  
                  $files = scandir($path . $name);   
                  foreach($files as $file){  
                    $basenameAndExtension = explode(".", $file);
                    $file_ext = end($basenameAndExtension);
                    $filenameSansExt=str_replace('.'.$file_ext,"",$file);  
                    $allowed_ext = array('jpg', 'png','JPG','JPEG','jpeg','PNG');  
                    if(in_array($file_ext, $allowed_ext)){  
                      $new_name = $file.'.' . $file_ext;   
                      copy($path.$name.'/'.$file, $path . $file);  
                      $query=$this->db->query("select username,profile from users where academicyear='$max_year' and status='Active' and isapproved='1' and usertype='Student' group by id order by fname,mname,lname ASC");
                      if($query->num_rows()>0){
                        foreach($query->result() as $row){
                          $profile=$row->profile;
                          $username=$row->username;
                          $profileNew=str_replace('/',"", $username);
                          $updateProfile=$profileNew.'.'.$file_ext;
                          if($profileNew==$filenameSansExt){
                            $this->db->where('username',$username);
                            $this->db->set('profile',$updateProfile);
                            $this->db->update('users');
                          }
                        }
                      }
                      unlink($path.$name.'/'.$file);  
                    }       
                  }  
                  unlink($location);  
                  rmdir($path . $name);
                  $this->session->set_flashdata('success','<div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                      <i class="fas fa-check-circle"> </i> Upload & Extract successfully.
                    </div>
                  </div>');
                }else{
                  $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                      <i class="fas fa-check-circle"> </i> Please try again later.
                    </div>
                  </div>');
                }  
              }else{
                $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                  <i class="fas fa-check-circle"> </i> Please select zip file.
                </div>
              </div>');
              }
            }  
          }
        }
      }
    }else{
      $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
      <div class="alert-body">
          <button class="close"  data-dismiss="alert">
              <span>&times;</span>
          </button>
        <i class="fas fa-check-circle"> </i> Please select a file.
      </div>
    </div>');
    }
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('home-page/'.$page,$data);
  }
  function upload_data(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
      $config['upload_path'] = './profile/'; 
      $config['allowed_types'] = 'zip'; 
      $config['max_size'] = '50000000'; // max_size in kb (5 MB) 
      $file = $_FILES['file'];
      $this->load->library('upload',$config); 
      $file_name = $file['name'];  
      $array = explode(".", $file_name);  
      $name = $array[0];  
      $ext = $array[1];  
      if($ext === 'zip')  
      {  
        $path = './profile/';  
        $location = $path . $file_name;  
        if(move_uploaded_file($file['tmp_name'], $location))  
        {  
          $zip = new ZipArchive;  
          if($zip->open($location)){  
            $zip->extractTo($path);  
            $zip->close();  
          }  
          $files = scandir($path . $name);   
          foreach($files as $file){  
            $basenameAndExtension = explode(".", $file);
            $file_ext = end($basenameAndExtension);
            $filenameSansExt=str_replace('.'.$file_ext,"",$file);  
            $allowed_ext = array('jpg', 'png','JPG','JPEG','jpeg','PNG');  
            if(in_array($file_ext, $allowed_ext)){  
              $new_name = $file.'.' . $file_ext;   
              copy($path.$name.'/'.$file, $path . $file);  
              $query=$this->db->query("select username,profile from users where academicyear='$max_year' and status='Active' and isapproved='1' and usertype='Student' group by id order by fname,mname,lname ASC");
              if($query->num_rows()>0){
                foreach($query->result() as $row){
                  $profile=$row->profile;
                  $username=trim($row->username);
                  $profileNew=str_replace('/',"", $username);
                  $updateProfile=$profileNew.'.'.$file_ext;
                  if(strtolower($profileNew) == strtolower($filenameSansExt)) {
                    $this->db->where('username',$username);
                    $this->db->where('academicyear',$max_year);
                    $this->db->set('profile',$file);
                    $this->db->update('users');
                  }
                }
              }
              unlink($path.$name.'/'.$file);  
            }       
          }  
          unlink($location);  
          rmdir($path . $name);
          echo '<div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
              <i class="fas fa-check-circle"> </i> Uploaded & extracted successfully.
            </div>
          </div>';
        }else{
          echo '<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
              <i class="fas fa-check-circle"> </i> Please try again later.
            </div>
          </div>';
        }  
      }else{
        echo '<div class="alert alert-warning alert-dismissible show fade">
        <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
          <i class="fas fa-check-circle"> </i> Please select zip file.
        </div>
      </div>';
      }
    }
  }

}