<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffincidentreport extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffIncident' order by id ASC ");  
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
    public function index($page='staffincidentreport')
    {
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');

        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['gradeGroups']=$this->main_model->studentRegistrationGrade($max_year);
        $this->load->view('home-page/'.$page,$data);
    }
    function fetch_user_forreport(){  
        $user=$this->session->userdata('username');
        $queryBranch ="select * from users where username=?";
        $query_branch=$this->db->query($queryBranch,array($user));
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$row_branch->usertype;
        $myDivision=$row_branch->status2;
        $accessbranch = sessionUseraccessbranch();
        $postData = $this->input->post();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data = $this->main_model->getEmployees_incident_report($usertype,$postData);
            echo json_encode($data);
        }else{
            $data = $this->main_model->getEmployees_incident_report_branch($usertype,$branch,$postData);
            echo json_encode($data);
        }
    }
    function reportIncident_staff(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $queryBranch ="select branch,fname,mname from users where username=? ";
        $query_branch=$this->db->query($queryBranch,array($user));
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $tname=$row_branch->fname;
        $mname=$row_branch->mname;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('editedId',TRUE)){
            $editedId=$this->input->post('editedId',TRUE);
            $editedId = $this->security->xss_clean($editedId);
            echo $this->main_model->reportIncident_staffs($editedId,$max_year,$tname,$mname,$user);
        }
    }
    function fetch_this_incidentform_type(){
        if($this->input->post('incidentCategory')){
            $incidentCategory=$this->input->post('incidentCategory');
            echo $this->main_model->fetch_this_incidentform_type($incidentCategory);
        }
    }
    function save_staff_incident(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $previous_conse='';
        $data1=array();
        $data=array();
        $user=$this->session->userdata('username');
        $maxID='1';
        if($this->input->post('incident_staff')){
            $teacher=$this->input->post('incident_staff',TRUE);  
            $date_import=$this->input->post('incident_date',TRUE);
            $incident_location=$this->input->post('incident_location',TRUE);
            $incident_category=$this->input->post('incidentTypeCategoryChoose',TRUE);
            $incident_type=$this->input->post('incident_type',TRUE);  
            $incident_description=trim($this->input->post('incident_description',TRUE));
            $admin_action=$this->input->post('admin_action',TRUE);
            $reportDate = date('d/m/Y', strtotime($date_import));
            $data =array(
                'stuid'=>$teacher,
                'incident_type'=>$incident_category,
                'incident_location'=>$incident_location,
                'incidet_desc'=>$incident_description,
                'admin_action'=>$admin_action,
                'report_by'=>$user,
                'date_report'=>$reportDate,
                'academicyear'=>$max_year
            );
            $query= $this->db->insert('incident_report',$data);
            if($query){
                $queryMax=$this->db->query("select max(id) as max_ID from incident_report ");
                $queryRow=$queryMax->row();
                $maxID=$queryRow->max_ID;
                for($i=0;$i<count($incident_type);$i++) {
                    $incident_types=$incident_type[$i];            
                    $data1[]=array(
                        'stuid'=>$teacher,
                        'incident_type'=>$incident_types,
                        'incident_id'=>$maxID,
                        'academicyear'=>$max_year,
                        'inserted_by'=>$user,
                        'date_inserted'=>$reportDate
                    );
                }
                if(!empty($data1)){
                    $query=$this->db->insert_batch('incident_student_type',$data1);
                    if($query){
                        echo '1';
                    }else{
                        echo '2';
                    }
                }
            }else{
                echo '2';
            }
        }
    }
    function fetch_staff_incident_report(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $postData = $this->input->post();
        $data= $this->main_model->fetch_staff_incident_report($max_year,$postData);
        echo json_encode($data);
    }
    function deleteSingleidentReport(){
        if($this->input->post('reportID')){
            $reportID=$this->input->post('reportID');    
            $reportUsername=$this->input->post('reportUsername');
            $this->db->where('id',$reportID);
            $query= $this->db->delete('incident_report');
            if($query){
                $this->db->where('incident_id',$reportID);
                $query1= $this->db->delete('incident_student_type');
                
                $this->db->where('incident_id',$reportID);
                $query2= $this->db->delete('incident_decision');
            }
        }
    }
    function viewSinglestaffidentReport(){
        if($this->input->post('reportID')){
            $reportID=$this->input->post('reportID');    
            $reportUsername=$this->input->post('reportUsername');
            echo $this->main_model->viewSinglestaffidentReport($reportID,$reportUsername);
        }
    }
    
}
