<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Post_polls extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        $this->load->helper('security');
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        }  
    }
    public function index()
    {
        $user=$this->session->userdata('username');
        $number = count($_POST["name_option"]);  
        if($number > 1) {
            $group_name=1;
            $this->db->select('max(group_name) as groupName');
            $this->db->order_by('id','DESC');
            $queryCheck=$this->db->get('poll_table');
            if($queryCheck->num_rows()>0){
                $row=$queryCheck->row();
                $grooupName=$row->groupName;
                $group_name=$grooupName + 1;
            } else{
                $group_name=$group_name;
            }
            $date_Today=date('Y-m-d');
            $poll_question=$this->input->post('name_question',TRUE);
            $poll_length=$this->input->post('poll_length',TRUE);
            $who_can_vote=$this->input->post('who_can_vote',TRUE);

            $poll_question=xss_clean($poll_question);
            $poll_length=xss_clean($poll_length);
            $who_can_vote=xss_clean($who_can_vote);
            if($poll_length >= $date_Today){
                for($i=0; $i<$number; $i++) { 
                    $optionName= $_POST["name_option"][$i];
                    if(trim($_POST["name_option"][$i] != '')){  
                        $data[]=array(
                            'group_name'=>$group_name,
                            'poll_question'=>$poll_question,
                            'date_expired'=>$poll_length,
                            'option_name'=>$optionName,
                            'who_vote'=>$who_can_vote,
                            'date_created'=>date('M-d-Y'),
                            'createdby'=>$user
                        );  
                    } 
                    
                }
                if(!empty($data)){
                    $queryInsert=$this->db->insert_batch('poll_table',$data);
                    if($queryInsert){
                        echo $this->main_model->fetch_thispoll_posts($user,$group_name);
                    }
                }
            }
        } 
    } 
    function fetch_poll_data(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        echo $this->main_model->fetch_poll_posts($user,$this->input->post('limit'), $this->input->post('start'),$usertype);
    }  
    function submit_poll(){
        $user=$this->session->userdata('username');
        if($this->input->post('pid')){
            $pid=$this->input->post('pid',TRUE);
            $poll_group=$this->input->post('poll_group',TRUE);
            $pid=xss_clean($pid);
            $poll_group=xss_clean($poll_group);
            $data=array(
                'p_group'=>$poll_group,
                'pid'=>$pid,
                'user_id'=>$user,
                'date_vote'=>date('M-d-Y')
            );
            $query=$this->db->insert('poll_table_result',$data);
            if($query){
                echo $this->main_model->fetch_thispoll_posts($user,$poll_group);
            }
        }
    } 
    function delete_poll_post(){
        if(isset($_GET['post_id'])){
            $id=$this->input->get('post_id',TRUE);
            $id=xss_clean($id);
            $this->db->where('group_name',$id);
            $query=$this->db->delete('poll_table');
            if($query){
                $this->db->where('p_group',$id);
                $query=$this->db->delete('poll_table_result');   
            }
        }
    }
}