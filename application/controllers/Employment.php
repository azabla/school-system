<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employment extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='websitemanagment' order by id ASC ");
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
	public function index($page='employment')
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
    function postvacancy(){
        $user=$this->session->userdata('username');
         $todayDate=date('Y-m-d');
        if($this->input->post('position')){
            $position=$this->input->post('position');
            $description=$this->input->post('description');
            $expireDate=$this->input->post('expireDate');
            $data=array(
                'vposition'=>$position,
                'post'=>$description,
                'postby'=>$user,
                'datepost'=>date('M-d-Y'),
                'expire'=>$expireDate
            );
            echo $this->main_model->insertvacancy($data,$todayDate,$expireDate);
        }     
    }
    function fetchvacancy(){
        $todayDate=date('y-m-d');
        echo $this->main_model->fetchvacancy($todayDate);
    } 
    function Deletevacancy(){
        if($this->input->post('id')){
          $id=$this->input->post('id');
          $this->main_model->deletevacancy($id);
        }
    }
    function loadApplicants(){
        echo $this->main_model->loadApplicants();
    }
    public function download($id) {   
        if(!empty($id)){
            $this->load->helper('download');
            $file = 'vacancyfile/'.$id;
            force_download($file, NULL);
        }
    }
    function viewmyvacancyDetail(){
        if($this->input->post('id')){
            $id=$this->input->post('id');
            echo $this->main_model->viewmyvacancyDetail($id);
        }
    }
}