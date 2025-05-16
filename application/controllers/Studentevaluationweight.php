<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Studentevaluationweight extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='Evaluation' order by id ASC "); 
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows() < 1 || $userLevel!='2'){
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
	public function index($page='evaluation')
	{
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php'))
        {
          show_404();
        }
        
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['posts']=$this->main_model->fetch_post();
        $data['gre']=$this->main_model->fetch_grade_from_staffplaceDir($user,$max_year);
        $this->load->view('teacher/'.$page,$data);
	}
    function filterSubject4CustomEvaluation(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade2analysis')){
            $grade2analysis=$this->input->post('grade2analysis');
            for($i=0;$i<count($grade2analysis);$i++){
                $gradeGsanalysis[]=$grade2analysis[$i];
            }
            echo $this->main_model->filterSubject4CustomEvaluation($gradeGsanalysis,$max_year);   
        }
    }
    function fetchCustomEvaluations(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryCheck = $this->db->query("select * from quarter where Academic_year='$max_year' group by termgroup ");
        if($queryCheck->num_rows()>0){
            foreach($queryCheck->result() as $maxQuarter){
                $termgroup=$maxQuarter->termgroup;
                $query2 = $this->db->query("select max(term) as quarter,min(term) as minQuarter from quarter where Academic_year='$max_year' and termgroup='$termgroup' ");
                $row2 = $query2->row();
                $max_quarter=$row2->quarter;
                $min_quarter=$row2->minQuarter;
                /*echo $this->main_model->fetchCustomEvaluation($max_year,$max_quarter,$min_quarter);*/
                echo 'Please wait it is under construction.';

            }
        }    
    }
    function deleteCustomEvaluation(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['post_id'])){
            $id=$this->input->post('post_id');
            $quarter=$this->input->post('quarter');
            $evname=$this->input->post('evname');
            $query=$this->main_model->deleteCustomEvaluation($id,$quarter,$evname,$max_year);
        }
    }
    function postCustomEvaluation(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['id'])){
            $id = $this->input->post('id');
            $evnames=$this->input->post('evalname');
            $percent=trim($this->input->post('percent'));
            $evsubject=$this->input->post('subject');
            foreach ($evnames as $evname) {
                foreach ($id as $grade) {
                    $query2 = $this->db->query("select max(term) as quarter,termgroup from quarter where Academic_year='$max_year' and termgrade ='$grade' group by termgroup ");
                    $row2 = $query2->row();
                    $max_quarter=$row2->quarter;
                    $termgroup=$row2->termgroup;
                    foreach ($evsubject as $evsubjects) {
                        $query=$this->main_model->addCustomEvaluation($grade,$evsubjects,$evname,$max_year,$max_quarter);
                        if($max_quarter!=''){
                            if($query){
                                $data=array(
                                    'customgrade'=>$grade,
                                    'customsubject'=>$evsubjects,
                                    'customasses'=>$evname,
                                    'academicyear'=>$max_year,
                                    'custompercent'=>$percent,
                                    'customquarter'=>$max_quarter,
                                    'quartergroup'=>$termgroup
                                );
                                $query2=$this->db->insert('evaluationcustom',$data);
                                if($query2){
                                    echo 'Saved';
                                }else{
                                    echo 'Please try again';
                                }
                            }
                        }
                    }
                }
            }
            
        }
    }
    function movingCustomEvaluations(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryCheck = $this->db->query("select * from quarter where Academic_year='$max_year' group by termgroup ");
        if($queryCheck->num_rows()>0){
            $data=array();
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