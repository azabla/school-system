<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FilterAssesmentQuarterChange extends CI_Controller {
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
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query_branch =$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $usertype=$row_branch->usertype;
        $myDivision=$row_branch->status2;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('evaluation')){
            $gradesec=$this->input->post('gradesec');
            $evaluation=$this->input->post('evaluation');
            $branch=$this->input->post('branch');
            $quarter=$this->input->post('quarter');
            $subject=$this->input->post('subject');
            $queyEval=$this->db->query("select evname from evaluation where eid ='$evaluation' ");
            $evaRow=$queyEval->row();
            $evaName=$evaRow->evname;
            $this->db->where('academicyear',$max_year);
            $this->db->where('assesment_status','1');
            $query = $this->db->get('filter_assesment_by_branch_subject');
            if($query->num_rows()>0){
                echo $this->main_model->FilterAssesmentQuarterChange_filteringby_branch($evaName,$gradesec,$max_year,$branch,$quarter,$subject); 
            }else{
                echo $this->main_model->FilterAssesmentQuarterChange($evaName,$gradesec,$max_year,$branch,$quarter,$subject); 
            }
        }  
	}   
}