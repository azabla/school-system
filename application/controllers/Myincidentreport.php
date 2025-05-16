<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Myincidentreport extends CI_Controller {
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
        $usertype=$this->session->userdata('usertype');
        $this->db->where('username',$user);
        $query_gradesec=$this->db->get('users');
        $row_gradesec = $query_gradesec->row();
        $grade=$row_gradesec->grade;
        $gradesec=$row_gradesec->gradesec;
        $id=$row_gradesec->id;
        $branch1=$row_gradesec->branch;
        $data['token'] = $this->security->get_csrf_hash();
        $data['response']=$this->main_model->myprevious_incident_report($user);
        echo json_encode($data); 
    }    
}