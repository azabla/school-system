<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Check_update_age extends CI_Controller {
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
        $gYearName=date('Y');
        $queryUpdate='';
        if(isset($_POST['view'])){
            $this->db->where('academicyear',$max_year);
            $queryCheck = $this->db->get('agecalculation');
            if($queryCheck->num_rows()>0){
                $rowAge=$queryCheck->row();
                $value=$rowAge->age_method;
                $queryCalculate=$this->db->query("select dob,age,username from users where academicyear='$max_year' and dob!='' and usertype='Student' or academicyear='$max_year' and dob!='0000-00-00' and usertype='Student'  or academicyear='$max_year' and dob!='0000/00/00' and usertype='Student' group by id ");
                if($queryCalculate->num_rows()>0){
                    foreach($queryCalculate->result() as $stuRow){
                        $username=$stuRow->username;
                        $birthday =$stuRow->dob;
                        $orderdate = explode('-', $birthday);
                        $year  = $orderdate[0];
                        if(is_numeric($year) && $year>0){
                            if($value=='Ethiopian'){
                                $age=$max_year - $year;
                            }else{
                                $age=$gYearName - $year;
                            }
                            $this->db->where('dob!=','0000-00-00');
                            $this->db->where('username',$username);
                            $this->db->where('academicyear',$max_year);
                            $this->db->set('age',$age);
                            $queryUpdate=$this->db->update('users');
                        }
                    }
                }
                if($queryUpdate){
                    echo '1';
                }else{
                    echo '0';
                } 
            }
        }else{
            redirect('home/');
        }
	}    
}