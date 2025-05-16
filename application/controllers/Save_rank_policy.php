<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Save_rank_policy extends CI_Controller {
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

    if($this->input->post('rank_grade')){
      $rank_grade=$this->input->post('rank_grade');
      foreach ($rank_grade as $kepolicy_grade) {
        $query=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and grade='$kepolicy_grade' ");
        if($query->num_rows()>0){
          $this->main_model->update_rank_policy($kepolicy_grade,$max_year); 
        }
        else { 
          $data=array(
           'allowed'=>'1',
           'grade'=>$kepolicy_grade,
           'academicyear'=>$max_year,
           'datecreated'=>date('M-d-Y')
          );
          $this->main_model->insert_rank_policy($data,$max_year); 
        }
      }
    }  
	}   
}