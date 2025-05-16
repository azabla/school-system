<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Savestudentresult extends CI_Controller {
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('Login/');
        }
    }
	public function index()
	{
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $resultvalue=$this->input->post('resultvalue');
            $academicyear=$this->input->post('academicyear');
            $subject=$this->input->post('subject');
            $evaluation=$this->input->post('evaluation');
            $quarter=$this->input->post('quarter');
            $assesname=$this->input->post('assesname');
            $percentage=$this->input->post('percentage');
            $markGradeSec=$this->input->post('markGradeSec');
            $markGradeSecBranch=$this->input->post('markGradeSecBranch');
            $queryChk=$this->main_model->save_thisgrade_exam($academicyear,$subject,$quarter,$assesname,$markGradeSec,$markGradeSecBranch);
            if($queryChk){
                $data=array();
                for ($i=0; $i < count($stuid); $i++) { 
                    $id=$stuid[$i];
                    $markvalue=$resultvalue[$i];
                    if($percentage>=$markvalue && $markvalue>=0 && $markvalue!=''){
                        $data[]=array(
                            'stuid'=>$id,
                            'academicyear'=>$academicyear,
                            'markname'=>$assesname,
                            'subname'=>$subject,
                            'evaid'=>$evaluation,
                            'quarter'=>$quarter,
                            'outof'=>$percentage,
                            'value'=>$markvalue,
                            'mgrade'=>$markGradeSec,
                            'mbranch'=>$markGradeSecBranch,
                            'approved'=>'0'
                       );
                    }
                }
                $query=$this->db->insert_batch('mark'.$markGradeSecBranch.$markGradeSec.$quarter.$academicyear,$data);
                if($query){
                    echo '<div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close"  data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        <i class="fas fa-exclamation-circle"> </i> Result saved successfully.
                    </div></div>';
                }else{
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close"  data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        <i class="fas fa-exclamation-circle"> </i> Please Try Again.
                    </div></div>';
                }
            }else{
                echo '<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i> Mark already exists.
                </div></div>';
            }
        }
	} 
}