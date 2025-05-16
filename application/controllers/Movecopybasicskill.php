<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Movecopybasicskill extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        $this->load->library('excel');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='copyStudentBSDATA' order by id ASC ");
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
	public function index($page='movecopybasicskill')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
        {
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year'");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['bsname']=$this->main_model->fetch_bsname($max_year,$max_quarter);
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function fetchThisGradeStudent(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesecs')){
            $gradesec=$this->input->post('gradesecs');
            $academicyear=$this->input->post('academicyear');
            $branch=$this->input->post('branch');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchThisGradeStudentMoveCopyBS($gradesec,$academicyear,$branch);
            }else{
                echo $this->main_model->fetchThisGradeStudentMoveCopyBS($gradesec,$academicyear,$mybranch);
            }
        }
    } 
    function fetchThisGradeStudentMove(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesecs')){
            $gradesec=$this->input->post('gradesecs');
            $academicyear=$this->input->post('academicyear');
            $branch=$this->input->post('branch');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchThisGradeStudentMoveCopyBSGS($gradesec,$academicyear,$branch);
            }else{
                echo $this->main_model->fetchThisGradeStudentMoveCopyBSGS($gradesec,$academicyear,$mybranch);
            }
        }
    }
    function copybasicskill(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $gradesec=trim($this->input->post('gradesec'));
            $studentList=$this->input->post('studentList');
            $academicyear=$this->input->post('academicyear');
            $branch=$this->input->post('branch');
            $toquarter=$this->input->post('toquarter');
            $fromquarter=$this->input->post('fromquarter');
            for($i=0;$i<count($studentList);$i++){
                $checkStudent[]=$studentList[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->copybasicskill($branch,$gradesec,$fromquarter,$toquarter,$checkStudent,$academicyear,$user);
            }else{
                echo $this->main_model->copybasicskill($mybranch,$gradesec,$fromquarter,$toquarter,$checkStudent,$academicyear,$user);
            }
        }
    }
    function movebasicskill(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('gradesec')){
            $gradesec=trim($this->input->post('gradesec'));
            $studentList=$this->input->post('studentList');
            $academicyear=$this->input->post('academicyear');
            $branch=$this->input->post('branch');
            $toquarter=$this->input->post('toquarter');
            $fromquarter=$this->input->post('fromquarter');
            for($i=0;$i<count($studentList);$i++){
                $checkStudent[]=$studentList[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->movebasicskill($branch,$gradesec,$fromquarter,$toquarter,$checkStudent,$academicyear,$user);
            }else{
                echo $this->main_model->movebasicskill($mybranch,$gradesec,$fromquarter,$toquarter,$checkStudent,$academicyear,$user);
            }
        }
    }
}