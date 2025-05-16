<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Studentrankreport extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userPerStaAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='rankReport' order by id ASC ");
        if($this->session->userdata('username') == '' || $userPerStaAtt->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='studentrankreport')
	{
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
          show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        $query_quarter = $this->db->query("select max(quarter) as quarter from mark");
        $row_quarter = $query_quarter->row();
        $max_quarter=$row_quarter->quarter;
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        if($_SESSION['usertype']===trim('Director')){
          $data['gradesec']=$this->main_model->fetch_grade_from_staffplace4Director($user,$max_year);
        }else{
          $data['gradesecTeacher']=$this->main_model->fetch_session_gradesec($user,$max_year);
        }
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('teacher/'.$page,$data);
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
        $query_quarter = $this->db->query("select * from quarter where Academic_year ='$max_year' ");
        $NoOfquarter = $query_quarter->num_rows();

        if($this->input->post('gradesec')){
            $gradesecs=$this->input->post('gradesec');
            $quarters=$this->input->post('quarter');
            $top=$this->input->post('top');
            $countQuarter=0;
            for($i=0;$i<count($quarters);$i++){
                $countQuarter=$countQuarter + 1;
                $quarter[]=$quarters[$i];
            }
            for($i=0;$i<count($gradesecs);$i++){
                $gradesec[]=$gradesecs[$i];
            }
            /*$queryGrade = $this->db->query("select grade from users where academicyear ='$max_year' and gradesec='$gradesec' ");
            $rowGrade=$queryGrade->row();
            $gradeName=$rowGrade->grade;
            if($gradeName =='KG1' || $gradeName =='KG2'|| $gradeName =='KG3' ||$gradeName =='Nursery' ||$gradeName =='LKG' ||$gradeName =='UKG'){
                echo $this->main_model->top_rankKG($max_year,$quarter,$gradesec,$branch_me,$top,$countQuarter); 
            }else{*/
            echo $this->main_model->top_rank_gradebysection($max_year,$quarter,$gradesec,$branch_me,$top,$countQuarter);
            /*}*/
        } 
    } 
}