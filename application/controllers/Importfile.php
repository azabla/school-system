<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Importfile extends CI_Controller {
  public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $importFile=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='ImportExport' and allowed='importFile' order by id ASC ");
        if($this->session->userdata('username') == '' || $importFile->num_rows()<1 || $userLevel!='2'){
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
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
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
            $usertype = 'Student';
            $username = $filesop[0];
            $stu_id=$filesop[0];
            $name = $filesop[1];
            $fathername = $filesop[2];
            $gfathername = $filesop[3];
            $gender = $filesop[4];
            $grade = $filesop[5];
            $section = $filesop[6];
            $fathermobile = $filesop[7];
            $mobile = $filesop[8];
            $mothername = $filesop[9];
            $dob = $filesop[10];
            $age = $filesop[11];
            $email = $filesop[12];
            $password = $filesop[13];
            $city = $filesop[14];
            $subcity = $filesop[15];
            $woreda = $filesop[16];
            $kebele = $filesop[17];
            $registrationdate = $filesop[18];
            $branch = $filesop[19];
            $academicyear = $filesop[20];
            if($section!=''){
              $grasection = $grade.$section;
            }else{
              $grasection = '';
            }
            $confpassword = $filesop[13];
            $isapprove = '1';
            $status = 'Active';
            if($c<>0){       
              $query=$this->main_model->import_student($username,$usertype,$stu_id,$name,$fathername,$gfathername,$mobile,$fathermobile,$email,$grade,$section,$grasection,$dob,$age,$gender,$password,$confpassword,$mothername,$city,$subcity,$woreda,$kebele,$isapprove,$registrationdate,$branch,$academicyear,$status);
            }
            $c = $c + 1;
          }
          if($query){
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
                <i class="fas fa-exclamation-circle"> </i> Please try again with different student Id.
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
            $data=array(
              'Subj_name' =>$filesop[0],
              'Grade' =>$filesop[1],
              'letter'=>$filesop[2],
              'Academic_Year' => $filesop[3],
              'onreportcard' =>$filesop[4],
              'Merged_percent'=>'100',
              'date_created'=>date('M-d-Y')
            );
            if($c<>0){  
              $query=$this->main_model->dump_import_subject($data);
            }
            $c = $c + 1;
          }
          if($query){
            $this->session->set_flashdata('success','Subject Imported Successfully');
            redirect('import','refresh');
          }else{
            $this->session->set_flashdata('error','Please try again. Either subject exists or Network problem.');
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
        if($imsubject===trim('staffs')){
          $file = $_FILES['importwhat']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            $username = $filesop[0];
            $usertype = ucfirst($filesop[1]);
            $name = $filesop[2];
            $fathername = $filesop[3];
            $gfathername = $filesop[4];
            $gender = $filesop[5];
            $mobile = $filesop[6];
            $dob = $filesop[7];
            $age = $filesop[8];
            $email = $filesop[9];
            $password = $filesop[10];
            $city = $filesop[11];
            $subcity = $filesop[12];
            $woreda = $filesop[13];
            $kebele = $filesop[14];
            $registrationdate = $filesop[15];
            $branch = $filesop[16];
            $academicyear = $filesop[17];
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
                  'status'=>'Active'
                );
            }
            $c = $c + 1;   
          }
          if($query){
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
                <i class="fas fa-check-circle"> </i> Please try again username exists.
            </div></div>');
          }
        }
      }
    }else{
      $this->session->set_flashdata('error','Please select a file.');
    }
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('teacher/'.$page,$data);
  } 
}