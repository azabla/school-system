<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Markresult extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='viewstudentmark' order by id ASC ");
        if($this->session->userdata('username') == '' ||  $uaddMark->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='mark-result')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
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
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year();
        $data['academicyearFilter']=$this->main_model->academic_year_filter();
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function filterSubjectFromSubject(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $max_year=$this->input->post('academicyear');
            echo $this->main_model->fetch_grade_from_branch_gs($gradesec,$max_year); 
        } 
    }
    function filter_quarter_fromyear(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        if($this->input->post('academicyear')){
            $max_year=$this->input->post('academicyear');
            echo $this->main_model->filter_quarter_fromyear($max_year); 
        }
    }
    function filterGradesecfromBranch(){
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->filterGradesecfromBranch($academicyear); 
        }
    }
    function fetch_gradesec_frombranch_markresult(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $max_year=$this->input->post('academicyear');
            echo $this->main_model->fetch_grade_from_branch($branch,$max_year); 
        }
    }
    function filterSubjectFromSubject_Comment(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            echo $this->main_model->fetch_grade_from_branch_comment($gradesec,$max_year); 
        } 
    }
    function fecthMarkresult(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user' ");
        $row_branch = $query_branch->row_array();
        $branch=$row_branch['branch'];
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];

        if($this->input->GET('gs_gradesec')){
            $gs_branches=$this->input->GET('gs_branches');
            $gs_gradesec=$this->input->GET('gs_gradesec');
            $gs_subjects=$this->input->GET('gs_subject');
            $gs_quarter=$this->input->GET('gs_quarter');
            $academicyear=$this->input->GET('academicyear');
            for($i=0;$i<count($gs_subjects);$i++){
                $gs_subject[]=$gs_subjects[$i];
            }
            $this->db->where('academicyear',$academicyear);
            $queryCheck = $this->db->get('enableapprovemark');
            if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
                if($queryCheck->num_rows()>0){
                   $show=$this->main_model->fetch_grade_markresultApproved($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$academicyear); 
                    echo $show; 
                }else{
                    $show=$this->main_model->fetch_grade_markresult($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$academicyear); 
                    echo $show;
                } 
            }else{
                if($queryCheck->num_rows()>0){
                    $show=$this->main_model->fetch_grade_markresultAdminApproved($branch,$gs_gradesec,$gs_subject,$gs_quarter,$academicyear); 
                    echo $show;
                }else{
                    $show=$this->main_model->fetch_grade_markresultAdmin($branch,$gs_gradesec,$gs_subject,$gs_quarter,$academicyear); 
                    echo $show;
                }
            }
        }
    }
    function fecth_mark_result_comment(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user' ");
        $row_branch = $query_branch->row_array();
        $branch=$row_branch['branch'];
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];

        if($this->input->GET('gs_gradesec')){
            $gs_branches=$this->input->GET('gs_branches');
            $gs_gradesec=$this->input->GET('gs_gradesec');
            $gs_subject=$this->input->GET('gs_subject');
            $gs_quarter=$this->input->GET('gs_quarter');
            $this->db->where('academicyear',$max_year);
            $queryCheck = $this->db->get('enableapprovemark');
            if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
                if($queryCheck->num_rows()>0){
                   $show=$this->main_model->fetch_grade_markresult_comment($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
                    echo $show; 
                }else{
                    $show=$this->main_model->fetch_grade_markresult_comment($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
                    echo $show;
                } 
            }else{
                if($queryCheck->num_rows()>0){
                    $show=$this->main_model->fetch_grade_markresult_comment($branch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
                    echo $show;
                }else{
                    $show=$this->main_model->fetch_grade_markresult_comment($branch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
                    echo $show;
                }
            }
        }
    }
    function save_teacher_comment(){
        $user=$this->session->userdata('username');
        
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $commentvalue=$this->input->post('commentvalue');
            $academicyear=$this->input->post('academicyear');
            $subject=$this->input->post('subject');
            $quarter=$this->input->post('quarter');
            $markGradeSec=$this->input->post('markGradeSec');
            $markGradeSecBranch=$this->input->post('markGradeSecBranch');
            for ($i=0; $i < count($stuid); $i++) { 
                $data=array();
                $id=$stuid[$i];
                $commentvalues=$commentvalue[$i];
                $queryChk=$this->main_model->save_this_grade_teacher_comment($id,$academicyear,$subject,$quarter);
                $data=array(
                    'stuid'=>$id,
                    'academicyear'=>$academicyear,
                    'subject'=>$subject,
                    'quarter'=>$quarter,
                    'resultcomment'=>$commentvalues,
                    'datecreated'=>date('M-d-Y'),
                    'createdby'=>$user
                );
                if($queryChk){
                    $query=$this->db->insert('manualreportcardcomments',$data);
                }else{
                    $this->db->where('stuid',$id);
                    $this->db->where('academicyear',$academicyear);
                    $this->db->where('subject',$subject);
                    $this->db->where('quarter',$quarter);
                    $this->db->set('resultcomment',$commentvalues);
                    $query=$this->db->update('manualreportcardcomments',$data);
                }
            }
            if($query){
                echo '<div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    <i class="fas fa-exclamation-circle"> </i> Comment saved successfully.
                </div></div>';
            }else{
                echo '<div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    <i class="fas fa-exclamation-circle"> </i> Please Try Again.
                </div></div>';
            }
        }
    }
    function fecthMarksheet(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user' ");
        $row_branch = $query_branch->row_array();
        $branch=$row_branch['branch'];
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        if($this->input->GET('gs_gradesec')){
            $gs_branches=$this->input->GET('gs_branches');
            $gs_gradesec=$this->input->GET('gs_gradesec');
            $gs_subject=$this->input->GET('gs_subject');
            $gs_quarter=$this->input->GET('gs_quarter');
            $includeComment=$this->input->GET('includeComment');
            $this->db->where('academicyear',$max_year);
            $queryCheck = $this->db->get('enableapprovemark');
            if(trim($_SESSION['usertype'])===trim('superAdmin') || $accessbranch === '1'){
                if($queryCheck->num_rows()>0){
                   $show=$this->main_model->fetch_grade_marksheetApproved($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
                    echo $show; 
                }else{
                    $show=$this->main_model->fetch_grade_marksheet($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year,$includeComment); 
                    echo $show;
                } 
            }else{
                if($queryCheck->num_rows()>0){
                    $show=$this->main_model->fetch_grade_marksheetApproved($branch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
                    echo $show;
                }else{
                    $show=$this->main_model->fetch_grade_marksheet($branch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year,$includeComment); 
                    echo $show;
                }
            }
        }
    } 
}