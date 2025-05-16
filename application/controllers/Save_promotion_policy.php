<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Save_promotion_policy extends CI_Controller {
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

    if($this->input->post('policy_grade')){
      $policy_average=$this->input->post('policy_average');
      $policy_grade=$this->input->post('policy_grade');
      foreach ($policy_grade as $kepolicy_grade) {
        $query=$this->db->query("select * from promotion_policy where academicyear='$max_year' and grade='$kepolicy_grade' ");
        if($query->num_rows()>0){
           $data=array(
           'average'=>$policy_average,
           'grade'=>$kepolicy_grade,
           'academicyear'=>$max_year,
           'datecreated'=>date('M-d-Y')
          );
          $this->main_model->update_promotion_policy($data,$kepolicy_grade,$max_year); 
        }
        else { 
          $data=array(
           'average'=>$policy_average,
           'grade'=>$kepolicy_grade,
           'academicyear'=>$max_year,
           'datecreated'=>date('M-d-Y')
          );
          $this->main_model->insert_promotion_policy($data,$max_year); 
        }
      }
    }  
	}   
}