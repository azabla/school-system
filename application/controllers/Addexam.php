<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Addexam extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='addstudentmark' order by id ASC ");
        if($this->session->userdata('username') == '' || $uaddMark->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='addexam')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $status2=$row_branch->status2;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' ");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;

        $querySummerCheck=$this->db->query("select * from startsummerclass where academicyear='$max_year' and classname='summerClass' ");
        $data['summerClassMark']=$querySummerCheck;

        $querychk=$this->db->query("select * from dmarkstatus where academicyear='$max_year' and dname='$status2' and dquarter='$max_quarter' ");
        $data['markstatus']=$querychk;
        $accessbranch = sessionUseraccessbranch();
        
        $data['checkAutoLock']=$this->main_model->checkAutoMarkLock($max_year,$max_quarter);
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['fetch_evaluation']=$this->main_model->fetch_evaluation_fornewexam($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['fetch_grade_fromsp_toadd_neweaxm']=$this->main_model->fetch_grade_from_staffplace($user,$max_year);
        $data['schools']=$this->main_model->fetch_school();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['branch']=$this->main_model->fetch_branch($max_year);
        }else{
            $this->db->where('academicyear',$max_year);
            $this->db->where('name',$branch);
            $this->db->group_by('name');
            $this->db->order_by('name','ASC');
            $query=$this->db->get('branch');
            $data['branch']= $query->result();
        }
        $this->load->view('home-page/'.$page,$data);
	} 
}