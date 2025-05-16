<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users_online extends CI_Controller {
    public function __construct(){
        parent::__construct();
      if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        }  
    }
    public function index()
    {
        $this->load->model('chat_model');
        $user=$this->session->userdata('unique_id');
        if(isset($_POST['view'])){
            $time = time();
            $time_check = $time - 100;
            $msg="";
            $this->db->where('session',$user);
            $query=$this->db->get('user_online');
            if($query->num_rows()==0){
                $data=array(
                    'session'=>$user,
                    'time'=>$time
                );
                $this->db->insert('user_online',$data);
            }else{
                $this->db->where('session',$user);
                $this->db->set('time',$time);
                $this->db->update('user_online');                
            }
            $this->db->where('time <',$time_check);
            $this->db->delete('user_online');

            $show= $this->chat_model->fetch_users_online($user);
            //$data['notification']=$this->chat_model->unseenMessage($user);
            $data['notification']=$show;
            echo json_encode($data);
        }
    }  
}