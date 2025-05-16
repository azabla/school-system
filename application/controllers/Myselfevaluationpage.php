<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myselfevaluationpage extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $this->load->helper('security');
        $userLevel = userLevel(); 
        if($this->session->userdata('username') == '' || $userLevel!='1'){
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
	public function index($page='my-self-evaluation-question')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['user_group']=$this->main_model->fetch_user_group_list();
        $this->load->view('home-page/'.$page,$data);
	}
    public function submit_selfquestion_answer()
    {   
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('etc/UTC');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $date = gmdate("Y-m-d h:i A", $dt->format('U'));
        $user=$this->session->userdata('username');
        $dataArray=array();
        if($this->input->is_ajax_request()){
            if($this->input->post('question_answer')){
                $question_answer=$this->input->post('question_answer',TRUE);
                $questionID=$this->input->post('questionID',TRUE);
                $question_answer = xss_clean($question_answer);
                $questionID = xss_clean($questionID);
                for($i=0;$i<count($question_answer);$i++){
                    $question_answers=$question_answer[$i];
                    $questionIDs=$questionID[$i];
                    $queryCheck=$this->main_model->check_evaluation_question_answer($questionIDs,$user);
                    if($queryCheck && !empty($question_answers)){
                        $dataArray[]=array(
                            'staff_username'=>$user,
                            'answer_name'=>$question_answers,
                            'question_id'=>$questionIDs,
                            'date_answer'=>$date
                        );
                    }else{
                       echo '0'; 
                    }
                }
                $query=$this->db->insert_batch('self_evaluation_questions_answer',$dataArray);
                if($query){
                    echo '1';
                }else{
                    echo '0';
                }
            }
        }
    }
    function fetch_my_newself_evaluation_questions(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        echo $this->main_model->fetch_my_newself_evaluation_questions($userType);
    }
    function fetch_myself_evaluation_questions(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        if($this->input->is_ajax_request()){
            if($this->input->post('question_title')){
                $question_title=$this->input->post('question_title',TRUE);
                $question_title = xss_clean($question_title);
                echo $this->main_model->fetch_myself_evaluation_questions($user,$userType,$question_title);
            }
        }
    }
}
