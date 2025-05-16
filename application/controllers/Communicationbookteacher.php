<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Communicationbookteacher extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('teacher_model');
    $this->load->helper('security');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $this->db->where('usergroup',$_SESSION['usertype']);
    $this->db->where('tableName','CommunicationBook');
    $this->db->where('allowed','sendcommunicationbook');
    $usergroupPermission=$this->db->get('usergrouppermission'); 
    if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='2'){
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
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $this->db->select('branch');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $branch_teacher=$row_branch->branch;

    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;
    $today=date('y-m-d');
    $data['fetch_term']=$this->teacher_model->fetch_term_4teacheer($max_year);
    $data['sessionuser']=$this->teacher_model->fetch_session_user($user);
    $data['academicyear']=$this->teacher_model->academic_year_filter();
    $data['schools']=$this->teacher_model->fetch_school();
    $this->load->view('teacher/'.$page,$data);
	}
  function load_grade_to_commbook(){
    $user=$this->session->userdata('username');
    $this->db->select('branch');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;
    echo $this->teacher_model->load_grade_to_commbook($user,$mybranch,$max_year);
  }
  function fetch_subject_of_thisGrade(){
    $user=$this->session->userdata('username');
    $this->db->select('branch');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('academicyear')){
      $academicyear=$this->input->post('academicyear',TRUE);
      $gradesec=$this->input->post('grade',TRUE);
      $branch=$this->input->post('branch',TRUE);

      $academicyear=xss_clean($academicyear);
      $gradesec=xss_clean($gradesec);
      $branch=xss_clean($branch);
      echo $this->teacher_model->fetch_subject_of_thisGrade($user,$academicyear,$gradesec,$branch);
    }
  }
  function fetch_comBookhistory_of_thisGrade(){
    $user=$this->session->userdata('username');
    $this->db->select('branch');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject',TRUE);
      $gradesec=$this->input->post('grade',TRUE);
      $branch=$this->input->post('branch',TRUE);
      $year=$this->input->post('year',TRUE);

      $subject=xss_clean($subject);
      $gradesec=xss_clean($gradesec);
      $branch=xss_clean($branch);
      $year=xss_clean($year);
      $this->db->select('max(term) as quarter');
      $this->db->where('Academic_Year',$year);
      $query2=$this->db->get('quarter');
      $row2 = $query2->row();
      $max_quarter=$row2->quarter;
      $this->db->select('*');
      $this->db->where('academicyear',$year);
      $queryApproval=$this->db->get('enableapprovecommubook');

      if($queryApproval->num_rows()>0){
        echo $this->teacher_model->fetchCommunicationBookTeacherApprove($user,$subject,$gradesec,$mybranch,$year,$max_quarter); 
      }else{
        echo $this->teacher_model->fetchCommunicationBookTeacher($user,$subject,$gradesec,$mybranch,$year,$max_quarter); 
      }
    }
  }
  function fetch_communication_book_form(){
    $user=$this->session->userdata('username');

    $this->db->select('branch');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('academicyear')){
      $academicyear=$this->input->post('academicyear',TRUE);
      $subject=$this->input->post('subject',TRUE);
      $gradesec=$this->input->post('grade',TRUE);
      $branch=$this->input->post('branch',TRUE);

      $academicyear=xss_clean($academicyear);
      $subject=xss_clean($subject);
      $gradesec=xss_clean($gradesec);
      $branch=xss_clean($branch);
      echo $this->teacher_model->fetch_communication_book_form($academicyear,$subject,$gradesec,$branch); 
    }
  }
  function delete_commuication_bookText(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('comID')){
      $comID=$this->input->post('comID',TRUE);
      $comID=xss_clean($comID);
      $this->db->where('id',$comID);
      $queryDelete=$this->db->delete('communicationbook');
      if($queryDelete){
        echo '1';
      }else{
        echo '0';
      }
    }
  }
  function fetch_commbook_form_toedit(){
    $user=$this->session->userdata('username');

    $this->db->select('branch');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('comID')){
      $comID=$this->input->post('comID',TRUE);
      $comID=xss_clean($comID);
      echo $this->teacher_model->fetch_commbook_form_toedit($comID); 
    }
  }
  function updateCommunicationBook(){
    $user=$this->session->userdata('username');
    if(isset($_POST['comID'])){
      if(!empty($this->input->post('comID'))){
        $comID=$this->input->post('comID',TRUE);
        $comNote=$this->input->post('comNote',TRUE);
        $comID=xss_clean($comID);
        $comNote=xss_clean($comNote);
        $this->db->where('id',$comID);
        $this->db->set('comnote',$comNote);
        $query=$this->db->update('communicationbook');
        if($query){
          echo '1';
        }else{
          echo '0';
        }
      }
    }
  }

  function saveCommunicationBook(){
    $user=$this->session->userdata('username');

    $this->db->select('branch');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $branch_teacher=$row_branch->branch;

    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;

    $this->db->select('max(term) as quarter');
    $this->db->where('Academic_Year',$max_year);
    $query2=$this->db->get('quarter');
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;

    if(isset($_POST['comGradesec'])){
      if(!empty($this->input->post('comGradesec'))){
        $comGradesec=$this->input->post('comGradesec',TRUE);
        $comSubject=$this->input->post('comSubject',TRUE);
        $comNote=$this->input->post('comNote',TRUE);
        $stuName=$this->input->post('stuName',TRUE);

        $comGradesec=xss_clean($comGradesec);
        $comSubject=xss_clean($comSubject);
        $comNote=xss_clean($comNote);
        $stuName=xss_clean($stuName);
        for($i=0;$i<count($stuName);$i++){
          $check=$stuName[$i];
          $data[]=array(
            'comgrade'=>$comGradesec,
            'combranch'=>$branch_teacher,
            'stuid'=>$check,
            'comsubject'=>$comSubject,
            'comnote'=>$comNote,
            'datecreated'=>date('M-d-Y'),
            'quarter'=>$max_quarter,
            'academicyear'=>$max_year,
            'byteacher'=>$user
          );
        }
        $query=$this->db->insert_batch('communicationbook',$data);
        if($query){
          echo '1';
        }else{
          echo '0';
        }
      }
    }
  }
  function replyComBook(){
    $user=$this->session->userdata('username');
    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;

    $this->db->select('profile');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $profile=$row_branch->profile;
    if($this->input->post('comID')){
      $comID=$this->input->post('comID',TRUE);
      $replyText=$this->input->post('replyText',TRUE);
      $comID=xss_clean($comID);
      $replyText=xss_clean($replyText);
      $datereplay=date("Y-m-d H:i:s");
      $data=array(
        'replyid'=>$comID,
        'replyby'=>$user,
        'replytext'=>$replyText,
        'datereplay'=>$datereplay
      );
      $queryInsert=$this->db->insert('combookreplaystudent',$data);
      if($queryInsert){
       echo '<div class="message-gs-full sender-message-gs">
          <div class="support-ticket media">
            <div class="media-body">
              <p class="p">'.$replyText.'
                <small class="time text-muted"> '.$datereplay.'</small>
              </p>
            </div>
          </div>
        </div> ';
      }
    }
  }
}