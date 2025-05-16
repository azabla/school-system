<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_new_student extends CI_Controller {
    public function __construct()
    {
      parent::__construct();
      if($this->session->userdata('username') == ''){
          $this->session->set_flashdata("error","Please Login first");
          redirect('login/');
      }  
    }
	public function index()
	{
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $config['upload_path']    = './profile/';
    $config['allowed_types']  = 'gif|jpg|png|ico';
    $this->load->library('upload', $config);
    if($this->input->post('fname')){
      $fathermobile=$this->input->post('fmobile');
      $fname=$this->input->post('fname');
      $lname=$this->input->post('lname');
      $gfname=$this->input->post('gfname');
      $llfname=$this->input->post('llfname');
      $studentAge=$this->input->post('studentAge');
      $yearJoined=$this->input->post('yearJoined');
      $specialNeeds=$this->input->post('specialNeeds');
      $previousSchool=$this->input->post('previousSchool');
      $maritalStatus=$this->input->post('maritalStatus');
      $fdob=$this->input->post('fdob');
      $fatherAge=$this->input->post('fatherAge');
      $workType=$this->input->post('workType');
      $workPlace=$this->input->post('workPlace');
      $nationality=$this->input->post('nationality');
      $motherfullname=$this->input->post('motherfullname');
      $gender=$this->input->post('gender');
      $usertype=$this->input->post('usertype');
      $mobile=$this->input->post('mmobile');
      $email=$this->input->post('email');
      $grade=$this->input->post('grade');
      $sec=$this->input->post('sec');
      $dob=$this->input->post('dob');
      $city=$this->input->post('city');
      $subcity=$this->input->post('subcity');
      $woreda=$this->input->post('woreda');
      $homeplace=$this->input->post('homePlace');
      $password=$this->input->post('password');
      $password2=$this->input->post('password2');
      $branch=$this->input->post('branch');
      $stuid=$this->input->post('stuid');
      $academicyear=$this->input->post('academicyear');
      $username=$this->input->post('stuid');
      if($this->upload->do_upload('profile')){
          $dataa = $this->upload->data('file_name');
          $data=array(
            'father_mobile'=>$fathermobile,
            'username'=>$stuid,
            'usertype'=>$usertype,
            'fname'=>$fname,
            'mname'=>$lname,
            'lname'=>$gfname,
            'last_oflast_name'=>$llfname,
            'age'=>$studentAge,
            'dateregister'=>$yearJoined,
            'special_needs'=>$specialNeeds,
            'previous_school'=>$previousSchool,
            'marital_status'=>$maritalStatus,
            'father_dob'=>$fdob,
            'father_age'=>$fatherAge,
            'work'=>$workType,
            'father_workplace'=>$workPlace,
            'nationality'=>$nationality,
            'mother_name'=>$motherfullname,
            'mobile'=>$mobile,
            'email'=>$email,
            'grade'=>$grade,
            'section'=>$sec,
            'gradesec'=>$grade.$sec,
            'dob'=>$dob,
            'gender'=>$gender,
            'password'=>hash('sha256', $password),
            'password2'=>hash('sha256', $password2),
            'city'=>$city,
            'profile'=>$dataa,
            'sub_city'=>$subcity,
            'woreda'=>$woreda,
            'home_place'=>$homeplace,
            'isapproved'=>'1',
            'branch'=>$branch,
            'unique_id'=>$stuid,
            'academicyear'=>$academicyear,
            'status'=>'Active'
          );
      }else{
        $data=array(
          'father_mobile'=>$fathermobile,
          'username'=>$stuid,
          'usertype'=>$usertype,
          'fname'=>$fname,
          'mname'=>$lname,
          'lname'=>$gfname,
          'last_oflast_name'=>$llfname,
          'age'=>$studentAge,
          'dateregister'=>$yearJoined,
          'special_needs'=>$specialNeeds,
          'previous_school'=>$previousSchool,
          'marital_status'=>$maritalStatus,
          'father_dob'=>$fdob,
          'father_age'=>$fatherAge,
          'work'=>$workType,
          'father_workplace'=>$workPlace,
          'nationality'=>$nationality,
          'mother_name'=>$motherfullname,
          'mobile'=>$mobile,
          'email'=>$email,
          'grade'=>$grade,
          'section'=>$sec,
          'gradesec'=>$grade.$sec,
          'dob'=>$dob,
          'gender'=>$gender,
          'password'=>hash('sha256', $password),
          'password2'=>hash('sha256', $password2),
          'city'=>$city,
          'sub_city'=>$subcity,
          'woreda'=>$woreda,
          'home_place'=>$homeplace,
          'isapproved'=>'1',
          'branch'=>$branch,
          'unique_id'=>$stuid,
          'academicyear'=>$academicyear,
          'status'=>'Active'
        );
      }
      $query=$this->main_model->register_new_student($data,$username,$stuid);
      if($query){
        echo '1';
      }else{
        echo '0';
      }
    }  
	}   
}