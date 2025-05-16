<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_newstaffs extends CI_Controller 
{
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentPr' order by id ASC ");  
        if($this->session->userdata('username') == '' || $uperStuDE->num_rows() < 1 || $userLevel!='1'){
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
        $query_branch = $this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        if($usertype==trim('superAdmin') ){
            if(isset($_POST['view'])){
                echo $this->main_model->fetch_new_staffs($max_year);
            }else{
                redirect('home/');
            }
        }else {
            if(isset($_POST['view'])){
                echo $this->main_model->fetch_branchnew_staffs($max_year,$branch);
            }else{
                redirect('home/');
            }
        }
	}    
}