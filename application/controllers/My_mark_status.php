<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_mark_status extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('chat_model');
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
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

        $this->db->where('username',$user);
        $this->db->where('academicyear',$max_year);
        $query_gradesec=$this->db->get('users');

        $row_gradesec = $query_gradesec->row();
        $grade=$row_gradesec->grade;
        $gradesec=$row_gradesec->gradesec;
        $id=$row_gradesec->id;
        $branch1=$row_gradesec->branch;
        $this->db->where('academicyear',$max_year);
        $queryCheck = $this->db->get('enableapprovemark');
        if(isset($_POST['view'])){
            if($queryCheck->num_rows()>0){
                if($_POST['view']!=''){
                    $this->chat_model->update_myunseen_markApproved($user,$id,$branch1,$gradesec,$grade,$max_year);
                }
                $show=$this->chat_model->fetch_allmymarkstatusApproved($user,$id,$branch1,$gradesec,$grade,$max_year);
                $result['notification']=$show;

                $tot=$this->chat_model->fetch_allunseetseen_myattendanceApproved($user,$max_year);
                $tot2=$this->chat_model->fetch_allunseetseen_mymarkApproved($id,$branch1,$gradesec,$grade,$max_year);
                $totBook=$this->chat_model->fetch_unseen_comBook_notificationApproved($user,$grade,$max_year);
                $allInboxGroup=$tot2 + $tot + $totBook;
                $result['unseen_notification']=$allInboxGroup;
                echo json_encode($result);
            }else{
                if($_POST['view']!=''){
                    $this->chat_model->update_myunseen_mark($user,$id,$branch1,$gradesec,$grade,$max_year);
                }
                $show2GS=$this->chat_model->fetch_allmymarkstatus($user,$id,$branch1,$gradesec,$grade,$max_year);
                $groupNotification=$show2GS;
                $result['notification']=$groupNotification;

                $tot2=$this->chat_model->fetch_allunseetseen_mymark($id,$branch1,$gradesec,$grade,$max_year);
                $tot=$this->chat_model->fetch_allunseetseen_myattendance($user,$max_year);
                $totBook=$this->chat_model->fetch_unseen_comBook_notification($user,$grade,$max_year);
                $allInboxGroup=$tot2 + $tot + $totBook;
                $result['unseen_notification']=$allInboxGroup;
                echo json_encode($result); 
            }
        } 
	}    
}