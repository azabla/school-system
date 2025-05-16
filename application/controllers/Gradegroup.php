<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gradegroup extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $gradeGroup=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='gradeGroup' order by id ASC ");
        if($this->session->userdata('username') == '' || $gradeGroup->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='gradegroup')
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
        $data['gradegroups']=$this->main_model->fetchDivForGradeGroup($max_year);
        $data['posts']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['staffs']=$this->main_model->fetch_students($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function postGradeGroup(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['id'])){
            $id = $this->input->post('id');
            $evname=$this->input->post('evname');
            foreach ($id as $grade) {
                $query=$this->db->query("select * from gradedivision where divgrade='$grade' ");
                if($query->num_rows()<1){
                    $data=array(
                        'divname'=>$evname,
                        'divgrade'=>$grade,
                        'academicyear'=>$max_year,
                        'createdby'=>$user,
                        'datecreated'=>date('M-d-Y')
                    );
                    $query2=$this->db->insert('gradedivision',$data);
                    if($query2){
                        echo 'Saved';
                    }else{
                        echo 'Please try again';
                    }
                }
            }
        }
    }
    function fetchGradeGroup(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetchGradeGroup($max_year);
    }
    function deleteGradeGroup(){
        $user=$this->session->userdata('username');
        if(isset($_POST['divname'])){
            $divname=$this->input->post('divname');
            $query=$this->main_model->deleteGradeGroup($divname);
        }
    }
}