<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Studentrequest extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('gs_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $uperStuRequest=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentRequest' order by id ASC ");
    if($this->session->userdata('username') == '' || $userLevel!='1'  || $uperStuRequest->num_rows()<1){
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
	public function index($page='new-student-request')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $data['sessionuser']=$this->gs_model->fetch_session_user($user);
    $data['schools']=$this->gs_model->fetch_school();
    $this->load->view('home-page/'.$page,$data);
	}
  function fetch_new_student_request(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $uperStuRequest=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentRequest' order by id ASC ");
    if($uperStuRequest->num_rows()>0){
      echo $this->gs_model->fetch_new_student_request($max_year);
    }
  } 
  function fetch_approved_request(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $postData = $this->input->post();
    $data= $this->gs_model->fetch_approved_request($max_year,$postData);
    echo json_encode($data);
  }
  function fetch_approved_staffrequest_form(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('request_id')){
      $request_id=$this->input->post('request_id');
      $uperStuRequest=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentRequest' order by id ASC ");
      if($uperStuRequest->num_rows()>0){
        echo $this->gs_model->fetch_approved_staffrequested_form($max_year,$request_id);
      }
    }
  }
  function submit_response(){
    $user=$this->session->userdata('username');
    if($this->input->post('id_request')){
      $id_request=$this->input->post('id_request');
      $req_response=$this->input->post('req_response');
      $this->db->where('id',$id_request);
      $this->db->set('requestresponse',$req_response);
      $this->db->set('requeststatus','1');
      $this->db->set('responseby',$user);
      $querySave=$this->db->update('studentrequest');
      if($querySave){
        $queryData=$this->db->query("select stuid,from_date,to_date from studentrequest where id='$id_request' ");
        $rowQuery=$queryData->row();
        $userName=$rowQuery->stuid;
        $startDate=$rowQuery->from_date;
        $endDate=$rowQuery->to_date;

        $changeDate1 = DateTime::createFromFormat('d/m/Y',$startDate);
        $changeDate2 = DateTime::createFromFormat('d/m/Y',$endDate);
        $startDate1= $changeDate1->format('Y-m-d');
        $endDate1= $changeDate2->format('Y-m-d');
        $diff = strtotime($endDate1) - strtotime($startDate1);
        $no_of_days=abs(round($diff / 86400)) +1 ;

        $queryUserData=$this->db->query("select username,leave_days from users where username='$userName' ");
        if($queryUserData->num_rows()>0){
          $rowUser=$queryUserData->row();
          $LeaveDays=$rowUser->leave_days;
          if($LeaveDays >0){
            $finalRemaining=$LeaveDays - $no_of_days;
          }else{
            $finalRemaining=0;
          }
        }else{
          $finalRemaining=0;
          $LeaveDays=0;
        }
        $this->db->where('username',$userName);
        $this->db->set('leave_days',$finalRemaining);
        $queryUpdate=$this->db->update('users');
        echo 'Request updated successfully.';
      }else{
        echo 'Oooops, please try again.';
      }
    }
  }
  public function delete_all()
  {
    $this->db->where('requeststatus','0');
    $this->db->or_where('requeststatus','1');
    $query=$this->db->delete('studentrequest');
    if($query){
      echo '1';
    }else{
      echo '0';
    }
  }

}