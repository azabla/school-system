<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newstaffsfinanceapproval extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentApproval' order by id ASC ");  
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
	public function index($page='new-staffs-finance-approvel')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('home-page/'.$page,$data);
	} 
  public function approve_registration_request(){
    $user=$this->session->userdata('username');
    if($this->input->post('stuid')){
      $id=$this->input->post('stuid');
      $dataArray=array();
      $queryStudentChk=$this->db->query("select * from users_registration_request where id='".$id."' ");
      if($queryStudentChk->num_rows()>0 ){
        foreach($queryStudentChk->result() as $row){
          $grade=$row->grade;
          $username=$row->username;
          $academicyear=$row->academicyear;
          $age=$row->age;
          if($age>0){
            $age=$age + 1;
          }else{
            $age=$age;
          }
          $queryCheck=$this->db->query("select * from users where username='$username' ");
          if($queryCheck->num_rows()>0){
            echo 'Student ID already exists. Please try with different ID.';
          }else{
            $dataArray=array(
              'username'=>$row->username,
              'usertype'=>$row->usertype,
              'fname'=>$row->fname,
              'mname'=>$row->mname,
              'lname'=>$row->lname,
              'last_oflast_name'=>$row->last_oflast_name,
              'mobile'=>$row->mobile,
              'father_mobile'=>$row->father_mobile,
              'email'=>$row->email,
              'profile'=>$row->profile,
              'grade'=>$grade,
              'section'=>'',
              'gradesec'=>'',
              'dob'=>$row->dob,
              'age'=>$age,
              'gender'=>$row->gender,
              'password'=>$row->password,
              'password2'=>$row->password2,
              'mother_name'=>$row->mother_name,
              'father_name'=>$row->father_name,
              'father_dob'=>$row->father_dob,
              'father_age'=>$row->father_age,
              'work'=>$row->work,
              'father_workplace'=>$row->father_workplace,
              'nationality'=>$row->nationality,
              'marital_status'=>$row->marital_status,
              'city'=>$row->city,
              'sub_city'=>$row->sub_city,
              'woreda'=>$row->woreda,
              'kebele'=>$row->kebele,
              'home_place'=>$row->home_place,
              'isapproved'=>'1',
              'dateregister'=>date('M-d-Y'),
              'branch'=>$row->branch,
              'transportservice'=>$row->transportservice,
              'asp'=>$row->asp,
              'academicyear'=>$row->academicyear,
              'biography'=>$row->biography,
              'dream'=>$row->dream,
              'status'=>'Active',
              'status2'=>'',
              'special_needs'=>$row->special_needs,
              'datemployeed'=>$row->datemployeed,
              'unique_id'=>$row->username
            );
          }
        }
        if(!empty($dataArray)){
          $queryInsert=$this->db->insert('users',$dataArray);
          if($queryInsert){
            $this->db->where('id',$id);
            $this->db->set('status','Active');
            $this->db->set('status_by',$user);
            $query=$this->db->update('users_registration_request');
            echo '1';
          }else{
            echo '0';
          }
        }
      }else{
          echo 'Something wrong please try again or contact school ICT center';
      }
    }
  }
  public function reject_registration_request(){
    $user=$this->session->userdata('username');
    if($this->input->post('decline_stuid')){
      $id=$this->input->post('decline_stuid');
      echo $this->main_model->decline_staffs($id,$user);
    }
  }

}