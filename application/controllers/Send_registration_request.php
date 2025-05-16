<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_registration_request extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
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
        $userName=$this->session->userdata('username');
        $unique_id=$this->session->userdata('unique_id');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $currentYear=$max_year-1;
        $today=date('y-m-d');
        $dataArray=array();
        $this->db->where('username',$userName);
        $this->db->where('academicyear',$currentYear);
        $queryStudentChk=$this->db->get('users');
        if($queryStudentChk->num_rows()>0 ){
            $data['token'] = $this->security->get_csrf_hash();
            foreach($queryStudentChk->result() as $row){
                $grade=$row->grade;
                $this->db->where('pre_grade',$grade);
                $queryGradeLevel=$this->db->get('grade_level');
                if($queryGradeLevel->num_rows()>0){
                    $rowGrade = $queryGradeLevel->row();
                    $nextGrade=$rowGrade->next_grade;
                }else{
                    $nextGrade='';
                }
                $dataArray=array(
                    'username'=>$row->username,
                    'usertype'=>$row->usertype,
                    'fname'=>$row->fname,
                    'mname'=>$row->mname,
                    'lname'=>$row->lname,
                    'last_oflast_name'=>$row->last_oflast_name,
                    'mobile'=>$row->mobile,
                    'father_mobile'=>$row->father_mobile,
                    'email'=>$row->email,
                    'profile'=>$row->profile,
                    'grade'=>$nextGrade,
                    'section'=>'',
                    'gradesec'=>'',
                    'dob'=>$row->dob,
                    'age'=>$row->age,
                    'gender'=>$row->gender,
                    'password'=>$row->password,
                    'password2'=>$row->password2,
                    'mother_name'=>$row->mother_name,
                    'father_name'=>$row->father_name,
                    'father_dob'=>$row->father_dob,
                    'father_age'=>$row->father_age,
                    'work'=>$row->work,
                    'father_workplace'=>$row->father_workplace,
                    'nationality'=>$row->nationality,
                    'marital_status'=>$row->marital_status,
                    'city'=>$row->city,
                    'sub_city'=>$row->sub_city,
                    'woreda'=>$row->woreda,
                    'kebele'=>$row->kebele,
                    'home_place'=>$row->home_place,
                    'isapproved'=>'0',
                    'dateregister'=>$row->dateregister,
                    'branch'=>$row->branch,
                    'transportservice'=>$row->transportservice,
                    'asp'=>$row->asp,
                    'academicyear'=>$max_year,
                    'biography'=>$row->biography,
                    'dream'=>$row->dream,
                    'status'=>'Inactive',
                    'status2'=>'0',
                    'special_needs'=>$row->special_needs,
                    'datemployeed'=>$row->datemployeed,
                    'unique_id'=>$row->unique_id
                );
            }
            $queryInsert=$this->db->insert('users_registration_request',$dataArray);
            if($queryInsert){
                $data['response']= '1';
                $data['token'] = $this->security->get_csrf_hash();
                echo json_encode($data); 
            }else{
                $data['token'] = $this->security->get_csrf_hash();
                $data['response']= '0';
                echo json_encode($data); 
            }
        }else{
            $data['token'] = $this->security->get_csrf_hash();
            $data['response']=  'Something wrong please try again or contact school ICT center';
            echo json_encode($data); 
        }
	} 
}