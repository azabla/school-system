<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mystudent extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('teacher_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $this->db->where('usergroup',$_SESSION['usertype']);
        $this->db->where('tableName','Student');
        $this->db->where('allowed','StudentVE');
        $this->db->order_by('id','ASC');
        $uperStuView=$this->db->get('usergrouppermission');
        if($this->session->userdata('username') == '' || $uperStuView->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='mystudent')
	{
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_teacher=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $data['fetch_term']=$this->teacher_model->fetch_term_4teacheer($max_year);
        $data['sessionuser']=$this->teacher_model->fetch_session_user($user);
        $data['academicyear']=$this->teacher_model->academic_year_filter();
        /*if($_SESSION['usertype']===trim('Director')){*/
          $data['gradesec']=$this->teacher_model->fetch_grade_from_staffplace($user,$max_year);
        /*}else{
          $data['gradesecTeacher']=$this->teacher_model->fetch_session_gradesec($user,$max_year);
        }*/
        $data['schools']=$this->teacher_model->fetch_school();
        $this->load->view('teacher/'.$page,$data);
	}
    function downloadStuData(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryStuddent=$this->db->query("select username,fname,mname,lname,gender,
            grade,section,father_mobile,mobile,mother_name,dob,age,email,password,city,sub_city,woreda,kebele,dateregister,branch,transportservice,asp,academicyear from users where usertype='Student' and status='Active' and isapproved='1' and academicyear='$max_year' and branch='$branch' order by fname,mname,lname ASC ");
        
        $filename ='Student-Data.csv';  
        header('Content-Type: testx/csv;charset=utf-8');
        header('Content-Disposition: attachment;filename="'.$filename.'"'); 
        $output=fopen('php://output', 'w');
        fputcsv($output,array('Student ID','First Name','Middle Name','Last Name','Gender','Grade','Section','Father Mobile','Mother Mobile','Mother Name','Date of birth','Age','Email','Password','City','Sub city','Woreda','Kebele','Registration Date','Branch','Transport Service','After School Program','Academic Year'));
        foreach ($queryStuddent->result_array() as $row) {
            fputcsv($output,$row);
        } 
        fclose($output);
    }
    function searchStudent(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->teacher_model->searchFinanceStudents($searchItem,$branch,$max_year);
        }
    }
    function Fecth_thistudent(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gs_gradesec')){
            $gs_gradesec=$this->input->post('gs_gradesec');    
            echo $this->teacher_model->fetchFinanceBranchStudents($branch,$gs_gradesec,$max_year,$user);
        } 
    }
    function reportIncident_student(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $tname=$row_branch->fname;
        $mname=$row_branch->mname;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('username')){
            $username=$this->input->post('username');    
            echo $this->teacher_model->reportIncident_student($username,$max_year,$tname,$mname,$user);
        }
    }
    function previous_incident_report(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $tname=$row_branch->fname;
        $mname=$row_branch->mname;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('username')){
            $username=$this->input->post('username');    
            echo $this->teacher_model->previous_incident_report($username,$max_year,$tname,$mname,$user);
        }
    }
    function save_incident(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $previous_conse='';
        $data1=array();
        $data=array();
        $maxID='1';
        if($this->input->post('incident_teacher')){
            $teacher=$this->input->post('incident_teacher');  
            $student=$this->input->post('incident_student');
            $date_import=$this->input->post('incident_date');
            $incident_location=$this->input->post('incident_location');
            $incident_category=$this->input->post('incidentTypeCategoryChoose');
            $incident_type=$this->input->post('incident_type');  
            $incident_description=trim($this->input->post('incident_description'));
            $is_offense=$this->input->post('is_offense');
            $previous_conse=$this->input->post('previous_conse');  
            $admin_action=$this->input->post('admin_action');
            $date_suspension_outschool=$this->input->post('date_suspension_outschool');
            $reentry_date_outschool=$this->input->post('reentry_date_outschool');
            $date_suspensionIn=$this->input->post('date_suspension_inschool');
            $reentry_dateIn=$this->input->post('reentry_date_inschool');
            $reportDate = date('d/m/Y', strtotime($date_import));
            if(!empty($date_suspensionIn)){
                $new_out_suspensionDate = date('d/m/Y', strtotime($date_suspension_outschool));
                $new_outreentry_date = date('d/m/Y', strtotime($reentry_date_outschool));
                $new_in_suspensionDate = date('d/m/Y', strtotime($date_suspensionIn));
                $new_reentry_indate = date('d/m/Y', strtotime($reentry_dateIn));
            }else{
                $new_out_suspensionDate='';
                $new_outreentry_date='';
                $new_in_suspensionDate='';
                $new_reentry_indate='';
            }
            $data =array(
                'stuid'=>$student,
                'incident_type'=>$incident_category,
                'incident_location'=>$incident_location,
                'incidet_desc'=>$incident_description,
                'is_offense'=>$is_offense,
                'previous_conse'=>$previous_conse,
                'admin_action'=>$admin_action,
                'date_in_suspension'=>$new_in_suspensionDate,
                'in_reentry_date'=>$new_reentry_indate,
                'date_out_suspension'=>$new_out_suspensionDate,
                'out_reentry_date'=>$new_outreentry_date,
                'report_by'=>$teacher,
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
                        'stuid'=>$student,
                        'incident_type'=>$incident_types,
                        'incident_id'=>$maxID,
                        'academicyear'=>$max_year,
                        'inserted_by'=>$teacher,
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
    function fetch_this_incidentform_type(){
        if($this->input->post('incidentCategory')){
            $incidentCategory=$this->input->post('incidentCategory');
            echo $this->teacher_model->fetch_this_incidentform_type($incidentCategory);
        }
    }
    function fetch_this_incidentform_type_level(){
        if($this->input->post('incidentCategory')){
            $incidentCategory=$this->input->post('incidentCategory');
            echo $this->teacher_model->fetch_this_incidentform_type_level($incidentCategory);
        }
    }
}
