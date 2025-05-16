<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffs extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userpStaffDe=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffDE' order by id ASC "); 
        if($this->session->userdata('username') == '' || $userpStaffDe->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='staffs')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch =$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['schools']=$this->main_model->fetch_school();
        $data['usergroup']=$this->main_model->fetchUserGroupRegistration();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $this->load->view('home-page/'.$page,$data);
	}
    function feedApprovalStatus(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staffUser')){
            $staffUser=$this->input->post('staffUser');
            $this->db->where('id',$staffUser);
            $this->db->set('finalapproval','1');
            $this->db->update('users');
        }
    } 
    function deleteApprovalStatus(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staffUser')){
            $staffUser=$this->input->post('staffUser');
            $this->db->where('id',$staffUser);
            $this->db->set('finalapproval','0');
            $this->db->update('users');
        }
    } 
    function fetchStaffs(){
        $user=$this->session->userdata('username');
        $query_branch =$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$row_branch->usertype;
        $myDivision=$row_branch->status2;
        $accessbranch = sessionUseraccessbranch();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            echo $this->main_model->fetch_staffs();
        }else{
            echo $this->main_model->fetch_mystaffsAdmin($branch,$usertype);
        }
    }
    function editStaff(){
        $user=$this->session->userdata('username');
        $queryUser =$this->db->query("select * from users where username='$user'"); 
        $rowUser = $queryUser->row();
        $usertype=$rowUser->usertype;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('staff_id')){
            $staffId=$this->input->post('staff_id');
            echo $this->main_model->fetch_staff_toedit($staffId,$max_year,$usertype);
        }
    }
    function updateStaff(){
        $id=$this->input->post('editedStaff');
        $username=$this->input->post('username');
        $unique_id=$this->input->post('username');
        $fname=$this->input->post('fname');
        $mname=$this->input->post('mname');
        $lname=$this->input->post('lname');
        $gender=$this->input->post('gender');
        $mobile=$this->input->post('mobile');
        $age=$this->input->post('staffAge');
        $staff_marital_Status=$this->input->post('staff_marital_Status');
        $staffQualification=$this->input->post('staffQualification');
        $gsallary=$this->input->post('gsallary');
        $transport_allowance=$this->input->post('transport_allowance');
        $quality_allowance=$this->input->post('quality_allowance');
        $position_allowance=$this->input->post('position_allowance');
        $home_allowance=$this->input->post('home_allowance');
        $gross_sallary=$this->input->post('gross_sallary');
        $taxable_income=$this->input->post('taxable_income');
        $income_tax=$this->input->post('income_tax');
        $pension_7=$this->input->post('pension_7');
        $pension_11=$this->input->post('pension_11');
        $other=$this->input->post('other');
        $netsallary=$this->input->post('netsallary');
        $email=$this->input->post('email');
        $branch=$this->input->post('branch');
        $usertype=$this->input->post('schoolusertype');
        $userDivision=$this->input->post('userDivision');
        $annual_leave_count=$this->input->post('annual_leave_count');
        $dateregister=$this->input->post('dateregister');
        $userNationality=$this->input->post('userNationality');
        if($_SESSION['usertype']===trim('superAdmin')){
            $data=array(
                'fname'=>$fname,
                'mname'=>$mname,
                'lname'=>$lname,
                'age'=>$age,
                'marital_status'=>$staff_marital_Status,
                'gsallary'=>$gsallary,
                'staff_qualification'=>$staffQualification,
                'allowance'=>$transport_allowance,
                'quality_allowance'=>$quality_allowance,
                'position_allowance'=>$position_allowance,
                'home_allowance'=>$home_allowance,
                'gross_sallary'=>$gross_sallary,
                'taxable_income'=>$taxable_income,
                'income_tax'=>$income_tax,
                'pension_7'=>$pension_7,
                'pension_11'=>$pension_11,
                'other'=>$other,
                'netsallary'=>$netsallary,
                'gender'=>$gender,
                'mobile'=>$mobile,
                'email'=>$email,
                'branch'=>$branch,
                'dateregister'=>$dateregister,
                'usertype'=>$usertype,
                'leave_days'=>$annual_leave_count,
                'status2'=>$userDivision,
                'nationality'=>$userNationality
            );
        }else{
           $data=array(
                'fname'=>$fname,
                'mname'=>$mname,
                'lname'=>$lname,
                'age'=>$age,
                'marital_status'=>$staff_marital_Status,
                'gsallary'=>$gsallary,
                'staff_qualification'=>$staffQualification,
                'allowance'=>$transport_allowance,
                'quality_allowance'=>$quality_allowance,
                'position_allowance'=>$position_allowance,
                'home_allowance'=>$home_allowance,
                'gross_sallary'=>$gross_sallary,
                'taxable_income'=>$taxable_income,
                'income_tax'=>$income_tax,
                'pension_7'=>$pension_7,
                'pension_11'=>$pension_11,
                'other'=>$other,
                'netsallary'=>$netsallary,
                'gender'=>$gender,
                'mobile'=>$mobile,
                'email'=>$email,
                'dateregister'=>$dateregister,
                'leave_days'=>$annual_leave_count,
                'status2'=>$userDivision,
                'nationality'=>$userNationality
            ); 
        }
        echo $this->main_model->update_staff_detail($id,$username,$data);     
    }
    function deleteStaff(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data1=array();
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('UTC');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $datetried = gmdate("Y-m-d h:i A", $dt->format('U'));
        $user=$this->session->userdata('username');
        if($this->input->post('staff_id')){
            $staff_id=$this->input->post('staff_id');
            $querySelect=$this->db->query("select * from users where id='$staff_id' ");
            $rowName=$querySelect->row();
            $fname=$rowName->fname;
            $mname=$rowName->mname;
            $lname=$rowName->lname;
            $data1=array(
                'userinfo'=>$user,
                'useraction'=>'Staff deleted',
                'infograde'=>'',
                'subject'=>'',
                'quarter'=>'',
                'academicyear'=>$max_year,
                'oldata'=>'',
                'newdata'=>'',
                'updateduser'=>''.$fname.' '.$mname.' '.$lname,
                'userbranch'=>'',
                'actiondate'=> $datetried
            );
            $queryInsert=$this->db->insert('useractions',$data1);
            if($queryInsert){
                $query= $this->main_model->deleteStaffs($staff_id);
            } 
        }
    }
    function resetStaffPassword(){
        if($this->input->post('editedId')){
            $editedId=$this->input->post('editedId');
            /*$checkStudent[]=$editedId[$i];*/
            $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 6; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $temp_pass= implode($pass); //turn the array into a string
            $passHash=hash('sha256',$temp_pass);
            $data=array(
                'password'=>$passHash,
                'password2'=>$passHash
            );
            echo $this->main_model->reset_staff_password($editedId,$data,$temp_pass);
            
        }
    }
    function inactiveStaff(){
        $user=$this->session->userdata('username');
        if($this->input->post('staff_id')){
            $staff_id=$this->input->post('staff_id');
            $this->main_model->inactive_staffs($staff_id,$user);
        }
    }
    function activeStaff(){
        if($this->input->post('staff_id')){
            $staff_id=$this->input->post('staff_id');
            $this->load->model('main_model');
            for($i=0;$i<count($staff_id);$i++){
                $checkStudent[]=$staff_id[$i];
            }
            $this->main_model->active_staffs($checkStudent);
        }
    }
    function downloadStuData(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryStuddent=$this->db->query("select username,usertype,fname,mname,lname,gender,mobile,mother_name,dob,age,email,city,sub_city,woreda,kebele,dateregister,status2,branch,academicyear,status from users where usertype!='Student' and status='Active' and isapproved='1' order by fname,mname,lname ASC ");
        $filename ='Staff-Data.csv';  
        header('Content-Type: testx/csv;charset=utf-8');
        header('Content-Disposition: attachment;filename="'.$filename.'"'); 
        $output=fopen('php://output', 'w');
        fputcsv($output,array('Username','Usertype','First Name','Middle Name','Last Name','Gender','Mobile','Mother Name','Date of birth','Age','Email','City','Sub city','Woreda','Kebele','Registration Date','User Division','Branch','Academic Year','Status'));
        foreach ($queryStuddent->result_array() as $row) {
            fputcsv($output,$row);
        } 
        fclose($output);
    }
    function saveNewStaff(){
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
        if($this->input->post('fnameRegistration')){
          $fathermobile=$this->input->post('fathermobileRegistration');
          $fname=$this->input->post('fnameRegistration');
          $lname=$this->input->post('lnameRegistration');
          $gfname=$this->input->post('gfnameRegistration');
          $gender=$this->input->post('genderRegistration');
          $usertype=$this->input->post('usertypeRegistration');
          $mobile=$this->input->post('mobileRegistration');
          $email=$this->input->post('emailRegistration');
          $dob=$this->input->post('dobRegistration');
          $city=$this->input->post('cityRegistration');
          $subcity=$this->input->post('subcityRegistration');
          $woreda=$this->input->post('woredaRegistration');
          $password=$this->input->post('passwordRegistration');
          $password2=$this->input->post('password2Registration');
          $branch=$this->input->post('branchRegistration');
          $stuid=$this->input->post('stuidRegistration');
          $academicyear=$this->input->post('academicyearRegistration');
          $username=$this->input->post('stuidRegistration');
          if($this->upload->do_upload('profileRegistration')){
            $dataa = $this->upload->data('file_name');
                $data=array(
                    'father_mobile'=>$fathermobile,
                    'username'=>$stuid,
                    'usertype'=>$usertype,
                    'fname'=>$fname,
                    'mname'=>$lname,
                    'lname'=>$gfname,
                    'mobile'=>$mobile,
                    'email'=>$email,
                    'dob'=>$dob,
                    'gender'=>$gender,
                    'password'=>hash('sha256', $password),
                    'password2'=>hash('sha256', $password2),
                    'city'=>$city,
                    'profile'=>$dataa,
                    'sub_city'=>$subcity,
                    'woreda'=>$woreda,
                    'isapproved'=>'1',
                    'dateregister'=>date('M-d-Y'),
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
                    'mobile'=>$mobile,
                    'email'=>$email,
                    'dob'=>$dob,
                    'gender'=>$gender,
                    'password'=>hash('sha256', $password),
                    'password2'=>hash('sha256', $password2),
                    'city'=>$city,
                    'sub_city'=>$subcity,
                    'woreda'=>$woreda,
                    'isapproved'=>'1',
                    'dateregister'=>date('M-d-Y'),
                    'branch'=>$branch,
                    'unique_id'=>$stuid,
                    'academicyear'=>$academicyear,
                    'status'=>'Active'
                );
            }
            $query=$this->main_model->register_new_student($data,$username,$stuid);
            if($query){
                echo '<span class="text-success">Saved successfully</span>';
            }else{
                echo '<span class="text-danger">Satff username already exists</span>';
            }
        }
    }
    function resetUserPassword(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('UTC');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $datetried = gmdate("Y-m-d h:i A", $dt->format('U'));
        $queryStuddent=$this->db->query("select username,password from users where usertype!='Student' and isapproved='1' and username!='$user' group by username order by fname,mname,lname ASC ");
        if($queryStuddent->num_rows()>0){
            foreach($queryStuddent->result_array() as $stuID){
                $username=$stuID['username'];
                $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
                $pass = array(); 
                $alphaLength = strlen($alphabet) - 1; 
                for ($i = 0; $i < 6; $i++) {
                    $n = rand(0, $alphaLength);
                    $pass[] = $alphabet[$n];
                }
                $temp_pass= implode($pass);
                $passHash=hash('sha256',$temp_pass);
                $this->db->where('username',$username);
                $this->db->set('password',$passHash);
                $this->db->set('password2',$passHash);
                $query=$this->db->update('users');
                if($query){
                    $data[]=array(
                        'username'=>$username,
                        'password'=>$temp_pass
                    );
                    
                }
            }
            $data1=array(
                'userinfo'=>$user,
                'useraction'=>'Generate Password',
                'infograde'=>'',
                'subject'=>'',
                'quarter'=>'',
                'academicyear'=>$max_year,
                'oldata'=>'',
                'newdata'=>'',
                'updateduser'=>'All staffs',
                'userbranch'=>'',
                'actiondate'=> $datetried
            );
            $queryInsert=$this->db->insert('useractions',$data1);
            $filename ='Staff-Password.csv';  
            header('Content-Type: testx/csv;charset=utf-8');
            header('Content-Disposition: attachment;filename="'.$filename.'"'); 
            $output=fopen('php://output', 'w');
            fputcsv($output,array('Username','Password'));
            foreach ($data as $row) {
                fputcsv($output,$row);
            } 
            fclose($output);
        }
    }
    function fetch_dropout_staffs(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetch_dropout_staffs();
    }
    function fetch_user(){  
        $user=$this->session->userdata('username');
        $query_branch =$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$row_branch->usertype;
        $myDivision=$row_branch->status2;
        $accessbranch = sessionUseraccessbranch();
        $postData = $this->input->post();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data = $this->main_model->getEmployees($usertype,$postData);
            echo json_encode($data);
        }else{
            $data = $this->main_model->getEmployees_filter($usertype,$branch,$postData);
            echo json_encode($data);
        }
    } 
}