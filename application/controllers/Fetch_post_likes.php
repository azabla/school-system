<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_post_likes extends CI_Controller {
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
        $user=$this->session->userdata('unique_id');
        $usertype=$this->session->userdata('usertype');

        $this->db->select('id');
        $this->db->where('unique_id',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $logged_id=$row_branch->id;
        $date_now= date('y-m-d');
        if($this->input->post('like_id')){
            $id=$this->input->post('like_id',TRUE);
            $id=xss_clean($id);
            $data=array(
                'pid'=>$id,
                'bid'=>$logged_id
            );
            $typeLike=$this->main_model->post_like($id,$logged_id,$data);
            $result['likesTypes']=$typeLike;
        }
        $tot=$this->main_model->fetch_post_likes($id);
        $result['countlikes']=$tot;
        echo json_encode($result);
	}    
}