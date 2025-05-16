<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rankreport extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='rankReport' order by id ASC ");  
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
    public function index($page='rankreport')
    {
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
          show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $query_quarter = $this->db->query("select max(term) as quarter from quarter where Academic_year ='$max_year' ");
        $row_quarter = $query_quarter->row();
        $max_quarter=$row_quarter->quarter;
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['fetch_terms']=$this->main_model->fetch_term($max_year);
        $data['fetch_termss']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('home-page/'.$page,$data);
    }
    function filterGradeFromBranch4Rank(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            echo $this->main_model->filterGradeFromBranch4Rankgs($branch,$max_year); 
        }
    }
    function fetchTopRank(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $gradesecs=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $quarter=$this->input->post('quarter');
            $top=$this->input->post('top');
            foreach($gradesecs as $gradesec){
                $queryGrade = $this->db->query("select grade from users where academicyear ='$max_year' and gradesec='$gradesec' group by grade ");
                $rowGrade=$queryGrade->row();
                $gradeName=$rowGrade->grade;
                $query_quarter = $this->db->query("select * from quarter where Academic_year ='$max_year' and termgrade='$gradeName' group by term ");
                $NoOfquarter = $query_quarter->num_rows();
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    if($gradeName =='KG1' || $gradeName =='KG2'|| $gradeName =='KG3' ||$gradeName =='Nursery' ||$gradeName =='LKG' ||$gradeName =='UKG'){
                        echo $this->main_model->top_rankKG($max_year,$quarter,$gradesec,$branch,$top,$NoOfquarter); 
                    }else{
                        echo $this->main_model->top_rank($max_year,$quarter,$gradesec,$branch,$top,$NoOfquarter); 
                    }
                }else{
                    if($gradeName =='KG1' || $gradeName=='KG2'|| $gradeName=='KG3' ||$gradeName=='Nursery' ||$gradeName=='LKG' ||$gradeName=='UKG'){
                        echo $this->main_model->top_rankKG($max_year,$quarter,$gradesec,$branch_me,$top,$NoOfquarter); 
                    }else{
                        echo $this->main_model->top_rank($max_year,$quarter,$gradesec,$branch_me,$top,$NoOfquarter); 
                    }
                }
            }
        } 
    }
    function fetchTopRankGrade(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $mybranch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];  
        $countQuarter=0;      
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $quarters=$this->input->post('quarter');
            $top=$this->input->post('top');
            for($i=0;$i<count($quarters);$i++){
                $countQuarter=$countQuarter + 1;
                $quarter[]=$quarters[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->topGradeTopRank($max_year,$quarter,$gradesec,$branch,$top,$countQuarter); 
            }else{
                echo $this->main_model->topGradeTopRank($max_year,$quarter,$gradesec,$mybranch,$top,$countQuarter); 
            }
        }
    } 
    function fetchTopBranchRank(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch_me=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $quarter=$this->input->post('quarter');
            $top=$this->input->post('top');
            echo $this->main_model->topDivTopRank($max_year,$quarter,$gradesec,$top); 
        }
    }
}