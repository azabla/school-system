<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CheckQuarterEndDate extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        if($this->session->userdata('username') == ''){
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
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $unique_id=$this->session->userdata('unique_id');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryQuarter=$this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year' ");
        $data =array();
        if($queryQuarter->num_rows()>0){
            $rowTerm = $queryQuarter->row();
            $maxQuarter=$rowTerm->quarter;
            $queryTerm=$this->db->query("select endate from quarter where Academic_year='$max_year' and term='$maxQuarter' ");
            if($queryTerm->num_rows()>0){
                $rowDate = $queryTerm->row();
                $endate=$rowDate->endate;
                $changeDate2 = DateTime::createFromFormat('d/m/y',$endate);
                $endDate1= $changeDate2->format('Y-m-d');
                $date1 = strtotime($endDate1);
                $remaining = $date1 - time();
                $days_remaining = floor($remaining / 86400);
                $hours_remaining = floor(($remaining % 86400) / 3600);
                $data =array('data1' => $days_remaining,'data2' => $hours_remaining);
                echo json_encode($data);
                /*if($days_remaining <='30'){
                    if($days_remaining <='0'){
                        echo " $days_remaining Days and $hours_remaining Hours has Passed for this Quarter/Term/Semester";
                    }else{
                        echo "$days_remaining Days and $hours_remaining Hours left to end Quarter/Term/Semester";
                    }
                }else{
                    echo '';
                }*/
            }
        }
	} 
}