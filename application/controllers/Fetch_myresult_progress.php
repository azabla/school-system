<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_myresult_progress extends CI_Controller {
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
	public function index()
	{
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
	}
    function view_sample_progress(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $this->db->where('username',$user);
        $this->db->where('academicyear',$max_year);
        $query_gradesec=$this->db->get('users');

        if($query_gradesec->num_rows()>0){
            $row_gradesec = $query_gradesec->row();
            $grade=$row_gradesec->grade;
            $gradesec=$row_gradesec->gradesec;
            $id=$row_gradesec->id;
            $branch1=$row_gradesec->branch;
            $fName=$row_gradesec->fname;
            $mName=$row_gradesec->mname;
            $lName=$row_gradesec->lname;

            $this->db->select('max(term) as quarter');
            $this->db->where('termgrade',$grade);
            $this->db->where('Academic_Year',$max_year);
            $query2=$this->db->get('quarter');
            $row2 = $query2->row();
            $max_quarter=$row2->quarter;               
            $record= $this->gs_model->fetch_myseason_progress_sample($branch1,$gradesec,$max_quarter,$grade,$max_year,$id,$fName,$mName,$lName); 
            echo json_encode($record);
        }else{
            echo '<div class="alert alert-light alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No data found.
            </div></div>';
        }
    }
}