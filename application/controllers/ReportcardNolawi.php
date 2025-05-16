<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reportcard extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Reportcard_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
         $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='reportcard' order by id ASC ");
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='reportcard')
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

        $data['fetch_term']=$this->Reportcard_nolawi->fetch_term($max_year);
        $data['sessionuser']=$this->Reportcard_nolawi->fetch_session_user($user);
        $data['academicyear']=$this->Reportcard_nolawi->academic_year_filter();
        $data['gradesec']=$this->Reportcard_nolawi->fetch_gradesec($max_year);
        $data['branch']=$this->Reportcard_nolawi->fetch_branch($max_year);
        $data['schools']=$this->Reportcard_nolawi->fetch_school();
        $this->load->view('home-page/'.$page,$data);
	} 
    function adjustRcTable(){
        $this->load->dbforge();
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_quarter = $this->db->query("select max(term) as quarter from quarter");
        $row_quarter = $query_quarter->row();
        $max_quarter=$row_quarter->quarter;
        echo $this->Reportcard_nolawi->prepareRCTable($max_year);
    }
    function Fetch_studentreportcard(){
        /*$this->output->enable_profiler(TRUE);*/
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        if($this->input->post('gradesec')){
            $gradesec=$this->input->post('gradesec');
            $branch=$this->input->post('branch');
            $reportaca=$this->input->post('reportaca');
            $rpQuarter=$this->input->post('rpQuarter');
            if($_SESSION['usertype']===trim('superAdmin')){
                $query=$this->Reportcard_nolawi->update_reportcardResult($reportaca,$gradesec,$branch,$rpQuarter);
                if($query){
                    $data=$this->Reportcard_nolawi->report_card($reportaca,$gradesec,$branch,$rpQuarter);
                    echo json_encode($data);
                }
            }else{
                $query=$this->Reportcard_nolawi->update_reportcardResult($reportaca,$gradesec,$mybranch,$rpQuarter);
                if($query){
                    $data=$this->Reportcard_nolawi->report_card($reportaca,$gradesec,$mybranch,$rpQuarter);
                    echo json_encode($data);
                }
            }
        }
    }
}