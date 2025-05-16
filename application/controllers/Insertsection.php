<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insertsection extends CI_Controller {
  public function __construct(){
    parent::__construct();
    if($this->session->userdata('username') == ''){
      $this->session->set_flashdata("error","Please Login first");
      redirect('login/');
    }    
  }
	public function index()
	{
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;

    if($this->input->post('section_id')){
      /*if($_SESSION['usertype']===trim('superAdmin')){*/
        $stu_id=$this->input->post('stu_id');
        $section_id=$this->input->post('section_id');
        $grade=$this->input->post('grade');
        $query=$this->main_model->update_student_section($stu_id,$section_id,$grade);
        if($query){
         $data['notification']='<span class="text-success"><i class="fas fa-check-circle"><i></span>';
        }else{
          $data['notification']='<span class="text-danger">oops.Please try again.</span>';
        }
      /*}else{
        $data['notification']='<span class="text-danger">oops.Please try again.</span>';
      }*/
      echo json_encode($data);
    }  
	}   
}