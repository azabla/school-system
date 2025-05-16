<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Noticeboard extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('gs_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    if($this->session->userdata('username') == '' || $userLevel!='3'){
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
  public function index($page='notice-board')
  {
    if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $today=date('y-m-d');

    $this->db->where(array('username'=>$user));
    $this->db->where(array('academicyear'=>$max_year));
    $query_gradesec=$this->db->get('users');

    $row_gradesec = $query_gradesec->row_array();
    $grade=$row_gradesec['grade'];
    $gradesec=$row_gradesec['gradesec'];
    $id=$row_gradesec['id'];
    $branch1=$row_gradesec['branch'];

    $this->db->select('max(term) as quarter');
    $this->db->where(array('Academic_year'=>$max_year));
    $query_quarter=$this->db->get('quarter');
    $row_quarter = $query_quarter->row();
    $max_quarter=$row_quarter->quarter;

    $data['fetch_term']=$this->gs_model->fetch_term_student($max_year,$grade);
    $data['sessionuser']=$this->gs_model->fetch_session_user($user);
    $data['academicyear']=$this->gs_model->academic_year_filter();
    $data['subject']=$this->gs_model->my_subject($max_year,$grade);
    $data['schools']=$this->gs_model->fetch_school();
    $this->load->view('student/'.$page,$data);
     
  }
}