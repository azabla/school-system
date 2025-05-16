<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Library extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='elibrary' order by id ASC ");   
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
	public function index($page='library')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $config['upload_path'] = './elibrary/';
    $config['allowed_types'] ='docx|pdf|mp4';
    $this->load->library('upload', $config);

    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $uid=$row_branch->id;

    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['addelibrary'])){
      $subject=$this->input->post('subjectt');
      $gradesec=$this->input->post('gradesec');
      $this->upload->do_upload('pdfdoc');
      $notepdf= $this->upload->data('file_name');
      if($notepdf!==''){
        $query=$this->main_model->insert_elibrary($user,$subject,$gradesec,$notepdf,$max_year);
        if($query){
          $this->session->set_flashdata('success','Posted successfully.');
          redirect('library/');
        }else{
          $this->session->set_flashdata('error','Something wrong please try again.');
          redirect('library/');
        }
      }else{
        $this->session->set_flashdata('error','Please select E-book.');
         redirect('library/');
      }
    }
    if(isset($_POST['post_id'])){
      $id=$this->input->post('post_id');
      $this->main_model->delete_elibrary($id);
    }
    $data['fetch_gradesec']=$this->main_model->fetch_myschool_gradesec($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['usertype']=$this->main_model->fetch_usertype();
    $data['library']=$this->main_model->fetch_elibrary();
    $data['schools']=$this->main_model->fetch_school();
    $this->load->view('home-page/'.$page,$data);
	} 
}