<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kgreportcard extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Reportcard_sinziro');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        if($this->session->userdata('username') == '' || $userLevel!='1'){
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
    public function index($page='kgreportcard')
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

        $data['fetch_term']=$this->Reportcard_sinziro->fetch_term($max_year);
        $data['sessionuser']=$this->Reportcard_sinziro->fetch_session_user($user);
        $data['academicyear']=$this->Reportcard_sinziro->academic_year_filter();
        $data['gradesec']=$this->Reportcard_sinziro->fetch_gradesec($max_year);
        $data['branch']=$this->Reportcard_sinziro->fetch_branch($max_year);
        $data['schools']=$this->Reportcard_sinziro->fetch_school();
        $this->load->view('home-page/'.$page,$data);
    } 
    function adjustRcTable(){
        $this->load->dbforge();
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->Reportcard_sinziro->prepareRCTable($max_year);
    }
    function fetchstudentreportcard(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year' ");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$max_quarter' ");
        $qRow=$queryTerm->row();
        $date1 =$qRow->startdate;
        $date2 =$qRow->endate;
        $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
        $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
        $startDate1= $changeDate1->format('Y-m-d');
        $endDate1= $changeDate2->format('Y-m-d');
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
               $query=$this->Reportcard_sinziro->update_reportcardResult($reportaca,$gradesec,$branch,$max_quarter);
                if($query){
                    $data=$this->Reportcard_sinziro->KgReportCard($reportaca,$gradesec,$branch,$max_quarter,$startDate1,$endDate1);
                    echo json_encode($data);
                }
            }else{
                $query=$this->Reportcard_sinziro->update_reportcardResult($reportaca,$gradesec,$mybranch,$max_quarter);
                if($query){
                    $data=$this->Reportcard_sinziro->KgReportCard($reportaca,$gradesec,$mybranch,$max_quarter,$startDate1,$endDate1);
                    echo json_encode($data);
                }
            }
        }
    }
}