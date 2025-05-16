<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customevaluation extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='Evaluation' order by id ASC "); 
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='customevaluation')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
        {
          show_404();
        }
        
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year'");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['posts']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['staffs']=$this->main_model->fetch_students($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    
    function movingEvaluations(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryCheck = $this->db->query("select * from quarter where Academic_year='$max_year' group by termgroup ");
        if($queryCheck->num_rows()>0){
            foreach($queryCheck->result() as $maxQuarter){
                $termgroup=$maxQuarter->termgroup;
                $queryCurrent = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' and termgroup='$termgroup' ");
                $rowCurrent = $queryCurrent->row();
                $currentQuarter=$rowCurrent->quarter;
                   
                $query2 = $this->db->query("select max(customquarter) as quarter from evaluationcustom where academicyear='$max_year' and quartergroup='$termgroup' ");
                $row2 = $query2->row();
                $max_quarter=$row2->quarter;
                if($max_quarter!=$currentQuarter){
                    $queryEva = $this->db->query("select * from evaluationcustom where academicyear='$max_year' and customquarter='$max_quarter' and quartergroup='$termgroup' ");
                    foreach($queryEva->result() as $evaValue){
                        $data[]=array(
                            'customquarter'=>$currentQuarter,
                            'customgrade'=>$evaValue->customgrade,
                            'customsubject'=>$evaValue->customsubject,
                            'customasses'=>$evaValue->customasses,
                            'custompercent'=>$evaValue->custompercent,
                            'quartergroup'=>$termgroup,
                            'academicyear'=>$evaValue->academicyear
                        );
                    }
                    $query=$this->db->insert_batch('evaluationcustom',$data);
                }
            }
        }
    }
}