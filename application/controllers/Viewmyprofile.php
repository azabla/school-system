<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewmyprofile extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('security');
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
	public function index($page='my-profile')
	{
        if(!file_exists(APPPATH.'views/student/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        if(isset($_POST['changepassword'])){
            $pass1=$this->input->post('password1');
            $pass2=$this->input->post('password2');
            $password=hash('sha256', $pass1);
            $password2=hash('sha256', $pass2);

            $this->load->model('main_model');
            $change_it=$this->main_model->change_password($user,$password);
            if($change_it){
                $this->db->where('username',$user);
                $this->db->set('password', $password2);
                $this->db->set('password2', $password2);
                $this->db->update('users');
                $this->session->set_flashdata("success",'Password Changed successfully!');
                redirect('viewmyprofile/','refresh');
            }
            else{
                 $this->session->set_flashdata("error",'Old Password is not correct!');
                 redirect('viewmyprofile/','refresh');
            }
        }
//Change user profile
        if(isset($_POST['changeprofile'])){
            $config['upload_path']    = './profile/';
            $config['allowed_types']  = 'gif|jpg|png|ico';
            $this->load->library('upload', $config);

            $email=$this->input->post('email');
            $mobile=$this->input->post('mobile');
            $bio=$this->input->post('biography');
            $this->load->model('main_model');
            $change_pro=$this->main_model->change_profile($user);
            if ($this->upload->do_upload('profilephoto')){
                $dataa =  $this->upload->data('file_name');
            if($change_pro){
                $this->db->where('username',$user);
                $this->db->set('email', $email);
                $this->db->set('mobile', $mobile);
                $this->db->set('biography', $bio);
                $this->db->set('profile', $dataa);
                $this->db->update('users');
                $this->session->set_flashdata("success",'Profile Changed successfully!');
                redirect('viewmyprofile/','refresh');
            }
            else{
                 $this->session->set_flashdata("error",'Something wrong please try again');
                 redirect('viewmyprofile/','refresh');
            }
        }
        else{
            if($change_pro){
                $this->db->where('username',$user);
                $this->db->set('email', $email);
                $this->db->set('mobile', $mobile);
                $this->db->set('biography', $bio);
                $this->db->update('users');
                $this->session->set_flashdata("success",'Profile Changed successfully!');
                redirect('viewmyprofile/','refresh');
            }
            else{
                 $this->session->set_flashdata("error",'Something wrong please try again');
                 redirect('viewmyprofile/','refresh');
            }

        }
        }
        $this->load->model('main_model');
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year();
        $data['schools']=$this->main_model->fetch_school();//school
        $data['posts']=$this->main_model->fetch_post();
        $this->load->view('student/'.$page,$data);
	} 
    function changePassword(){
        $user=$this->session->userdata('username');
        if($this->input->is_ajax_request()){
            if(isset($_POST['oldPassword'])){
                $pass1=$this->input->post('oldPassword');
                $pass2=$this->input->post('newPassword');
                $password=hash('sha256', $pass1);
                $password2=hash('sha256', $pass2);
                $hashed_password = password_hash($pass2, PASSWORD_BCRYPT);
                $change_it=$this->main_model->change_password($user,$pass1);
                if($change_it){
                    $this->db->where('username',$user);
                    $this->db->set('password', $hashed_password);
                    $this->db->set('password2', $hashed_password);
                    $this->db->update('users');
                    $data['response']= '1';
                    $data['token'] = $this->security->get_csrf_hash();
                    echo json_encode($data);
                }
                else{
                    $data['response']= '0';
                    $data['token'] = $this->security->get_csrf_hash();
                    echo json_encode($data);
                }  
            }
        }
    }

}