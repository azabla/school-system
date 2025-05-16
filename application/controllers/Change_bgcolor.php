<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Change_bgcolor extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->helper('security');
    if($this->session->userdata('username') == ''){
      $this->session->set_flashdata("error","Please Login first");
      redirect('Login');
    }     
  }
  public function index($page='staffs')
  {
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select id,username from users where username='$user'");
    $row_branch = $query_branch->row();
    $id=$row_branch->id;

    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->is_ajax_request()){
      if(isset($_POST['bgcolor'])){
        $bgcolor=$this->input->post('bgcolor',TRUE);
        $bgcolor=xss_clean($bgcolor);
        $this->db->where('sid',$id);
        $q_bgcolor=$this->db->get('bgcolor');
        $data['token'] = $this->security->get_csrf_hash();
        if($q_bgcolor->num_rows()>0){
          $this->db->where('sid',$id);
          $this->db->set('bgcolor',$bgcolor);
          $this->db->set('sid',$id);
          $query=$this->db->update('bgcolor');
        }else{
          $data=array(
            'sid'=>$id,
            'bgcolor'=>$bgcolor
          );
          $this->db->where('sid',$id);
          $this->db->insert('bgcolor',$data);
        }  
      }
    }
  } 
}