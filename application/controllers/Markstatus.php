<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Markstatus extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userpStaffAI=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='activeInactiveDiv' order by id ASC ");
        if($this->session->userdata('username') == '' || $userpStaffAI->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='markstatus')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
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
        $data['fetch_division']=$this->main_model->fetch_schooldivision($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function fetchDivisionStatus(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year' group by termgroup");
        foreach($query2->result() as $row){
             $max_quarter=$row->quarter;
            echo $this->main_model->fetchDivisionStatus($max_year,$max_quarter);
        }
        
    }
    function feedMarkStatus(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('dname')){
            $dname=$this->input->post('dname');
            $term=$this->input->post('term');
            $data=array(
                'dquarter'=>$term,
                'dname'=>$dname,
                'status'=>'1',
                'academicyear'=>$max_year
            );
            $this->db->insert('dmarkstatus',$data);
        }
    } 
    function deleteMarkStatus(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('dname')){
            $dname=$this->input->post('dname');
            $term=$this->input->post('term');
            $this->db->where('academicyear',$max_year);
            $this->db->where('dname',$dname);
            $this->db->where('dquarter',$term);
            $this->db->delete('dmarkstatus');
        }
    }
}