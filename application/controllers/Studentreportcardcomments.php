<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Studentreportcardcomments extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userPerStaAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='reportcard' order by id ASC ");
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
    public function index($page='studentreportcardcomments')
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
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['fetch_today_attendance']=$this->main_model->fetch_mattendance($max_year,$branch);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['gradesecs']=$this->main_model->fetcHrGradesec($max_year,$user,$branch);
        $data['gradesec']=$this->main_model->fetch_mygradesec2($user,$max_year,$branch);
        $this->load->view('teacher/'.$page,$data);
    } 
    function fecthStudentBs(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradesec')){
          $gradesec=$this->input->post('gradesec');
          $quarter=$this->input->post('quarter');
          $fetchData=$this->main_model->fecthStudentResultComments($mybranch,$gradesec,$quarter,$max_year);
            echo json_encode($fetchData);
        }
    }
    function updatestudentresultcomment(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('stuid')){
          $value=$this->input->post('value');
          $stuid=$this->input->post('stuid');
          $quarter=$this->input->post('quarter');
          $bsGradesec=$this->input->post('bsGradesec');
          $data=array(
            'stuid'=>$stuid,
            'resultcomment'=>$value,
            'quarter'=>$quarter,
            'academicyear'=>$max_year,
            'datecreated'=>date('M-d-Y'),
            'createdby'=>$user
          );
          echo $this->main_model->updatestudentresultcomment($bsGradesec,$stuid,$quarter,$max_year,$value,$data);
        }
    }   
        
}