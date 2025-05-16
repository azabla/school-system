<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class FetchNewMark4Approval extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('chat_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $this->load->helper('cookie');
        if($this->session->userdata('username') == '' || $userLevel!='2'){
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
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $myBranch=$row_branch->branch;
        $usertype=$row_branch->usertype;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $this->db->where('academicyear',$max_year);
        $queryCheck = $this->db->get('enableapprovemark');
        $this->load->helper('cookie');
        $userLevel = userLevel();
        if($queryCheck->num_rows()>0){            
            if(isset($_POST['view'])){
                $show=$this->chat_model->fetch_allnewMark($user,$myBranch,$max_year);
                $result['notification']=$show;
                $tot=$this->chat_model->fetch_unseen_newMark($user,$myBranch,$max_year);
                $result['unseen_notification']=$tot;
                echo json_encode($result);
            } 
        }
    }    
}