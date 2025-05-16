<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Annualrequest extends CI_Controller {
  	public function __construct(){
	    parent::__construct();
	    $this->load->model('main_model');
	    ob_start();
	    $this->load->helper('cookie');
	    $userLevel = userLevel();
	    /*$usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='communicationbook' order by id ASC "); */
	    if($this->session->userdata('username') == '' /*|| $usergroupPermission->num_rows()<1*/ || $userLevel!='2'){
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
	public function index($page='annualrequest')
	{
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select branch,mobile,id,leave_days from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_teacher=$row_branch->branch;
    $mobile=$row_branch->mobile;
    $id=$row_branch->id;
    $leaveDays=$row_branch->leave_days;

    $queryAttendance=$this->db->query("select count(absentdate) as totalAttendance from attendance where stuid='$id' and academicyear='$max_year' and absentype='Absent' or stuid='$id' and academicyear='$max_year' and absentype='Unexcused Absence' ");
		if($queryAttendance->num_rows()>0){
			$count_attendance=$queryAttendance->row();
			$countAttendance=$count_attendance->totalAttendance;
		}else{
			$countAttendance=0;
		}
		$remainingDays=$leaveDays - $countAttendance;

    
    $today=date('y-m-d');
    $data['leavingDays']=$remainingDays;
    $data['totalAttendance']=$countAttendance;
    $data['mobile']=$mobile;
    $data['fetch_term']=$this->main_model->fetch_term_4teacheer($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['leaveReason']=$this->main_model->fetch_leaving_reasons();
    $this->load->view('teacher/'.$page,$data);
	}
 
  function submit_request(){
	    $user=$this->session->userdata('username');
	    $query_branch = $this->db->query("select branch from users where username='$user'");
	    $row_branch = $query_branch->row();
	    $branch_teacher=$row_branch->branch;
	    $data=array();
	    $query =$this->db->query("select max(year_name) as year from academicyear");
	    $row = $query->row();
	    $max_year=$row->year;
	    if(isset($_POST['LeaveType'])){
	      	if(!empty($this->input->post('LeaveType'))){
		        $LeaveType=$this->input->post('LeaveType');
		        $fromDate=$this->input->post('fromDate');
		        $toDate=$this->input->post('toDate');
		        $returnDate=$this->input->post('returnDate');
		        $emergencyMobile=$this->input->post('emergencyMobile');
		        $timestampFrom=strtotime($fromDate);
          	$newFromDate=date('d/m/Y',$timestampFrom);
          	$timestampTo=strtotime($toDate);
          	$newToDate=date('d/m/Y',$timestampTo);
          	$timestampReturn=strtotime($returnDate);
          	$newReturnDate=date('d/m/Y',$timestampReturn);
		        $checkQuery=$this->db->query("select stuid from studentrequest where from_date='$newFromDate' and to_date='$newToDate' and stuid='$user' ");
		        $newReturnDate1 = date("Y-m-d", strtotime($newReturnDate));
        		$newToDate1 = date("Y-m-d", strtotime($newToDate));
        		$newFromDate1 = date("Y-m-d", strtotime($newFromDate));
		        if($checkQuery->num_rows() < 1){
			        $data[]=array(
			            'stuid'=>$user,
			            'requestype'=>$LeaveType,
			            'from_date'=>$newFromDate,
			            'to_date'=>$newToDate,
			            'return_date'=>$newReturnDate,
			            'emergency_mobile'=>$emergencyMobile,
			            'requestdate'=>date('M-d-Y'),
			            'academicyear'=>$max_year
			        );
			        $query=$this->db->insert_batch('studentrequest',$data);
			        if($query){
			          echo '1';
			        }else{
			          echo '0';
			        }
			    }else{
			    	echo '2';
			    }
	     	}
	    }
  	}
	function fetch_myrequested_form(){
	    $query =$this->db->query("select max(year_name) as year from academicyear");
	    $row = $query->row();
	    $max_year=$row->year;
	    $user=$this->session->userdata('username');
	    $query_branch = $this->db->query("select branch from users where username='$user' ");
	    $row_branch = $query_branch->row();
	    $branch_teacher=$row_branch->branch;
	    echo $this->main_model->fetch_myrequested_form($user,$max_year); 
	}
	function delete_request(){
		if(isset($_POST['id'])){
	      	if(!empty($this->input->post('id'))){
		        $id=$this->input->post('id');
		        $this->db->where('id',$id);
		        $query=$this->db->delete('studentrequest');
		        if($query){
		          echo 'Request deleted successfully.';
		        }else{
		          echo '<span class="text-danger">Please try again.</span>';
		        }
	     	}
	    }
	}
}