<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_student_regular_result extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('gs_model');
        ob_start();
        $this->load->helper('security');
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
        if($this->input->post('resultType')){
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
                $lName=$row_gradesec->lname;

                $this->db->select('min(term) as quarter');
                $this->db->where('termgrade',$grade);
                $this->db->where('Academic_Year',$max_year);
                $query2=$this->db->get('quarter');
                $row2 = $query2->row();
                $max_quarter=$row2->quarter;
                $this->db->where('academicyear',$max_year);
                $this->db->where('quarter',$max_quarter);
                $queryCheck = $this->db->get('studentcanseecard');
                if($queryCheck->num_rows()>0){
                    echo $this->gs_model->fetchDashboardMarkResultENS($branch1,$gradesec,$max_quarter,$grade,$max_year,$id,$fName,$mName,$lName); 
                }else{
                    echo '<div class="alert alert-light alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close"  data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        <i class="fas fa-exclamation-circle"> </i> Not ready yet. Teachers are working on it.
                    </div></div>';
                }
            }else{
                echo '<div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    <i class="fas fa-check-circle"> </i> No data found.
                </div></div>';
            }
        }else{
            redirect('Home/');
        }
	} 
    function thisyear_regular_mark_result(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        if($this->input->post('yearName')){
            $max_year=$this->input->post('yearName');
            $max_quarter=$this->input->post('quarter');
            $query_gradesec = $this->db->query("select * from users where username='$user' and academicyear='$max_year' ");
            if($query_gradesec->num_rows()>0){
                $row_gradesec = $query_gradesec->row();
                $grade=$row_gradesec->grade;
                $gradesec=$row_gradesec->gradesec;
                $id=$row_gradesec->id;
                $branch1=$row_gradesec->branch;
                $fName=$row_gradesec->fname;
                $mName=$row_gradesec->mname;
                $lName=$row_gradesec->lname;
                $this->db->where('academicyear',$max_year);
                $this->db->where('quarter',$max_quarter);
                $queryCheck = $this->db->get('studentcanseecard');
                if($queryCheck->num_rows()>0){
                    echo $this->gs_model->fetchDashboardMarkResultENS($branch1,$gradesec,$max_quarter,$grade,$max_year,$id,$fName,$mName,$lName);  
                }else{
                    echo '<div class="alert alert-light alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close"  data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        <i class="fas fa-exclamation-circle"> </i> Not ready yet. Teachers are working on it.
                    </div></div>';
                }
            }else{
                echo '<div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    <i class="fas fa-check-circle"> </i> No data found.
                </div></div>';
            }
        }else{
            redirect('Home/');
        }
    }
    function summer_mark_result(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');        
        $query = $this->db->query("select max(year_name) as year from summer_academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryGYear = $this->db->query("select gyear from summer_academicyear where year_name='$max_year'");
        if($queryGYear->num_rows()>0){
            $rowG = $queryGYear->row();
            $gyear=$rowG->gyear;
        }else{
            $gyear='';
        }
        
        if($this->input->post('resultType')){
            $query_gradesec = $this->db->query("select * from summerstudent where username='$user' and academicyear='$max_year' ");
            if($query_gradesec->num_rows()>0){
                $row_gradesec = $query_gradesec->row();
                $grade=$row_gradesec->grade;
                $gradesec=$row_gradesec->gradesec;
                $id=$row_gradesec->id;
                $mybranch=$row_gradesec->branch;
                $fName=$row_gradesec->fname;
                $mName=$row_gradesec->mname;
                $lName=$row_gradesec->lname;
                $username=$row_gradesec->username;
                echo $this->gs_model->fetchSummerMark($id,$grade,$username,$max_year,$gradesec,$mybranch,$gyear);
            }else{
                echo '<div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    <i class="fas fa-check-circle"> </i> No data found.
                </div></div>';
            }
        }else{
            redirect('Home/');
        }
    }
    function thisyear_summer_mark_result(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');                
        if($this->input->post('yearName')){
            $yearName=$this->input->post('yearName');
            $query_gradesec = $this->db->query("select * from summerstudent where username='$user' and academicyear='$yearName' ");
            $queryGYear = $this->db->query("select gyear from summer_academicyear where year_name='$yearName'");
            $rowG = $queryGYear->row();
            $gyear=$rowG->gyear;
            if($query_gradesec->num_rows()>0){
                $row_gradesec = $query_gradesec->row();
                $grade=$row_gradesec->grade;
                $gradesec=$row_gradesec->gradesec;
                $id=$row_gradesec->id;
                $mybranch=$row_gradesec->branch;
                $fName=$row_gradesec->fname;
                $mName=$row_gradesec->mname;
                $lName=$row_gradesec->lname;
                $username=$row_gradesec->username;
                echo $this->gs_model->fetchSummerMark($id,$grade,$username,$yearName,$gradesec,$mybranch,$gyear);
            }else{
                echo '<div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    <i class="fas fa-check-circle"> </i> No data found.
                </div></div>';
            }
        }else{
            redirect('Home/');
        }
    }
    function fetch_thisyear_quarter(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        if($this->input->post('yearName')){
            $selectedYear=$this->input->post('yearName');
            $query_gradesec = $this->db->query("select grade,gradesec from users where username='$user' and academicyear='$selectedYear' ");
            if($query_gradesec->num_rows()>0){
                $row_gradesec = $query_gradesec->row();
                $grade=$row_gradesec->grade;
                $gradesec=$row_gradesec->gradesec;
                echo $this->gs_model->filter_quarterAddMark($grade,$selectedYear);
            }else{
                echo "No Quarter/Term";
            }
        }
    }
}