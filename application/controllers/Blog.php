<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {
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
	public function index($page='news')
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
    function postnews(){
        $user=$this->session->userdata('username');
        $config['upload_path'] = './news/';
        $config['allowed_types'] ='JPG|jpg|png|gif|pdf';
        $config['encrpt_name']=TRUE;
        $this->load->library('upload', $config);
        $title=$this->input->post('title');
        $description=$this->input->post('description');
        if($this->upload->do_upload('newsimage')){
            $filename= $this->upload->data('file_name');
            $data=array(
                'ntitle'=>$title,
                'description'=>$description,
                'postby'=>$user,
                'datepost'=>date('M-d-Y'),
                'photo'=>$filename
            );
            echo $this->main_model->insertnews($data);
        }else{
            $data=array(
                'ntitle'=>$title,
                'description'=>$description,
                'postby'=>$user,
                'datepost'=>date('M-d-Y')
            );
            echo $this->main_model->insertnews($data);
        }    
    }
    function fetchnews(){
        echo $this->main_model->fetchnews();
    } 
    function DeleteNews(){
        if($this->input->post('id')){
          $id=$this->input->post('id');
          $this->main_model->deletenews($id);
        }
    }
}