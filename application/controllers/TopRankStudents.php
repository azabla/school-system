<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TopRankStudents extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        }  
    }
    public function index()
    {
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query_quarter = $this->db->query("select max(term) as mQuarter from quarter");
        $row_quarter = $query_quarter->row();
        $max_quarter=$row_quarter->mQuarter;

        if(isset($_POST['view'])){
            $show= $this->main_model->TopRankStudents($max_year,$max_quarter);
            $data['notification']=$show;
            echo json_encode($data);
        }
    }   
}