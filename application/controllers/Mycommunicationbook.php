<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mycommunicationbook extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('commbook_model');
    ob_start();
    $this->load->helper('cookie');
    $this->load->helper('security');
    $userLevel = userLevel();
    if($this->session->userdata('username') == '' || $userLevel!='3'){
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
	public function index($page='communicationbook')
	{
    if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $data['schools']=$this->commbook_model->fetch_school();
    $data['sessionuser']=$this->commbook_model->fetch_session_user($user);
    $this->load->view('student/'.$page,$data);
	}
  function fetchcomMySubject(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_gradesec1 = "select grade from users where username=? and academicyear=? ";
    $query_gradesec=$this->db->query($query_gradesec1,array($user,$max_year));
    if($this->input->is_ajax_request()){
      $data['token'] = $this->security->get_csrf_hash();
      if($query_gradesec->num_rows()>0){
        $row_gradesec = $query_gradesec->row();
        $grade=$row_gradesec->grade;
        if($grade!=''){
          $data['subject']= $this->commbook_model->fetchcomMySubject($max_year,$grade);
          echo json_encode($data);
        }else{
            $data['subject']= '<div class="alert alert-light alert-dismissible show fade">
                <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
                <i class="fas fa-exclamation-triangle"> </i> No data to fetch.Please contact your school admin!
            </div></div>';
          echo json_encode($data);
        }
      }else{
        $data['subject']= '<div class="alert alert-light alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            <i class="fas fa-exclamation-triangle"> </i> Please wait the school is under registration for new academic year .
        </div></div>';
        echo json_encode($data);
      }
    }
  }
  function fetchMyCommBook(){
    $username=$this->session->userdata('username');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $queryApproval="select * from enableapprovecommubook where academicyear=?";
    $queryApproval=$this->db->query($queryApproval,array($max_year));
    if($this->input->is_ajax_request()){
      $data['token'] = $this->security->get_csrf_hash();
      if($this->input->post('subject',TRUE)){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('subject','subject','required');
        if($this->form_validation->run()==FALSE){
          $data['result']= 'Please try later'; 
          $data['token'] = $this->security->get_csrf_hash();
          echo json_encode($data);
          return;
        }
        $subject=$this->input->post('subject',TRUE);
        $subject=xss_clean($subject);
        $data['token'] = $this->security->get_csrf_hash();
        if($queryApproval->num_rows()>0){
          if($_SESSION['usertype']===trim('Student')){
            $data['result']= $this->commbook_model->fetchMyCommBookApproved($username,$subject,$max_year);
          }else{
            $data['result']= 'Nothing to show';
          }
          echo json_encode($data);
        }else{
          if($_SESSION['usertype']===trim('Student')){
            $data['result']= $this->commbook_model->fetchMyCommBook($username,$subject,$max_year);
          }else{
            $data['result']= 'Nothing to show';
          }
          echo json_encode($data);
        }
      }else{
        $data['result']= 'Invalid data';
        echo json_encode($data);
      }
    }
  }
  function replyComBook(){
    date_default_timezone_set("Africa/Addis_Ababa");
    $dtz = new DateTimeZone('UTC');
    $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
    $datereplay = gmdate("Y-m-d h:i A", $dt->format('U'));
    $user=$this->session->userdata('username');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $data=array();
    $this->db->select('profile,gradesec,branch');
    $this->db->where('username',$user);
    $this->db->where('academicyear',$max_year);
    $query_branch = $this->db->get('users');
    $row_branch = $query_branch->row();
    $profile=$row_branch->profile;
    $gradesec=$row_branch->gradesec;
    $branch=$row_branch->branch;
    if($this->input->is_ajax_request()){
      if($this->input->post('comID',TRUE)){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('comID','comID','required');
        $this->form_validation->set_rules('replyText','replyText','required');
        if($this->form_validation->run()==FALSE){
          $data['result']= 'Please try later'; 
          $data['token'] = $this->security->get_csrf_hash();
          echo json_encode($data);
          return;
        }
        $comID=$this->input->post('comID',TRUE);
        $comID=xss_clean($comID);
        $replyText=$this->input->post('replyText',TRUE);
        $replyText=xss_clean($replyText);
        $subject=$this->input->post('subject',TRUE);
        $subject=xss_clean($subject);
        $queryPlacement=$this->commbook_model->fetch_staff_from_placement($subject,$gradesec,$branch,$max_year);
        if($queryPlacement){
          foreach($queryPlacement as $row){
            $staffName=$row->staff;
            $data[]=array(
              'comgrade'=>$gradesec,
              'combranch'=>$branch,
              'stuid'=>$staffName,
              'comsubject'=>$subject,
              'comnote'=>$replyText,
              'datecreated'=>$datereplay,
              'quarter'=>'',
              'academicyear'=>$max_year,
              'byteacher'=>$user
            );
          }
          $queryInsert=$this->db->insert_batch('communicationbook',$data);
          if($queryInsert){
            if($profile == ''){
              $data['response'] = '<div class="message-gs-full sender-message-gs">
                <div class="support-ticket media">
                  <div class="media-body">
                    <p class="p">'.$replyText.'
                      <small class="time text-muted"> '.$datereplay.'</small>
                    </p>
                  </div>
                </div>
              </div> ';
              $data['token'] = $this->security->get_csrf_hash();
            }else{
              $data['response'] = '<div class="message-gs-full sender-message-gs">
                  <div class="support-ticket media">
                  <div class="media-body">
                    <p class="p">'.$replyText.'
                      <small class="time text-muted"> '.$datereplay.'</small>
                    </p>
                  </div>
                </div>
              </div> ';
              $data['token'] = $this->security->get_csrf_hash();
            }
          }else{
            $data['response'] ='Please try later ';
            $data['token'] = $this->security->get_csrf_hash();
          }
        }else{
          $data['response'] ='No staff found';
          $data['token'] = $this->security->get_csrf_hash();
        }
        echo json_encode($data);
      }
    }
  }
  function createNew_CommunicationBook(){
    $user=$this->session->userdata('username');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;

    $this->db->select('gradesec,branch,username');
    $this->db->where('username',$user);
    $this->db->where('academicyear',$max_year);
    $query_branch = $this->db->get('users');
    $data['token'] = $this->security->get_csrf_hash();
    if($query_branch->num_rows()>0){
      $row_branch = $query_branch->row();
      $branch=$row_branch->branch;
      $username=$row_branch->username;
      $gradesec=$row_branch->gradesec;
      $data['response']= $this->commbook_model->myNew_CommunicationBook($branch,$gradesec,$username,$max_year);
    }else{
      $data['response']= '<div class="alert alert-light">No data found</div>';
    }
    echo json_encode($data);
  }
  function saveCommunicationBook(){
    $user=$this->session->userdata('username');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $this->db->select('gradesec,branch,username,profile');
    $this->db->where('username',$user);
    $this->db->where('academicyear',$max_year);
    $query_branch = $this->db->get('users');

    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $username=$row_branch->username;
    $gradesec=$row_branch->gradesec;
    $profile=$row_branch->profile;
    
    $this->db->select('select max(term) as quarter');
    $this->db->where('Academic_Year',$max_year);
    $query2 = $this->db->get('quarter');
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    if($this->input->is_ajax_request()){
      $data['token'] = $this->security->get_csrf_hash();
      if(isset($_POST['teacherID'])){
        if(!empty($this->input->post('teacherID',TRUE))){
          $teacherID=$this->input->post('teacherID',TRUE);
          $teacherID=xss_clean($teacherID);
          $text=$this->input->post('text',TRUE);
          $text=xss_clean($text);
          foreach($teacherID as $teacherIDs){
            $data[]=array(
              'comgrade'=>$gradesec,
              'combranch'=>$branch,
              'stuid'=>$teacherIDs,
              'comsubject'=>'',
              'comnote'=>$text,
              'datecreated'=>date('M-d-Y'),
              'quarter'=>$max_quarter,
              'academicyear'=>$max_year,
              'byteacher'=>$user
            );
          }
          $query=$this->db->insert_batch('communicationbook',$data);
          if($query){
            $data['response']= '<div class="chat-box"><div class="chat outgoing">
           <div class="details">
             <p class="p">'.$text.'
             <small class="time text-muted"> '.date('M-d-Y').'</small>
             </p>
           </div>
           <a href="#"> <img src="'.base_url().'/profile/'.$profile.'" alt="ME" class="border-circle"> </a>
         </div>
         </div> <br>';
          }else{
            $data['response']= '<div class="alert alert-warning alert-dismissible show fade">
                  <div class="alert-body">
                      <button class="close"  data-dismiss="alert">
                          <span>&times;</span>
                      </button>
                  <i class="fas fa-check-circle"> </i>Please try again.
              </div></div>';
          }
          echo json_encode($data);
        }
      }
    }
  }
}