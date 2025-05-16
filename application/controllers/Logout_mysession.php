<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout_mysession extends CI_Controller {
  public function __construct(){
    parent::__construct();
    if($this->session->userdata('username') == ''){
      $this->session->set_flashdata("error","Please Login first");
      redirect('Login');
    }     
  }
  public function index($page='mysessions')
  {
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;

    if(isset($_POST['ipaddress'])){
      $ipaddress=$this->input->post('ipaddress');
      $meuser=$this->input->post('meuser');
      $browser=$this->input->post('browser');
      $this->db->where('logged_user',$meuser);
      $this->db->where('ipaddress',$ipaddress);
      $this->db->where('browser',$browser);
      $query=$this->db->delete('loggeduser');
      $this->session->unset_userdata('username');
      $this->session->sess_destroy();
      redirect('login/',"refresh");
      if($query){
        echo '<span class="text-info">Logged out</span>';
      }else{
        echo 'oops';
      }
    }
  } 
}