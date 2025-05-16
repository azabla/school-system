<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Miprofile extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        if($this->session->userdata('username') == '' || $userLevel!='2'){
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
    public function index($page='myprofile')
    {
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year();
        $data['schools']=$this->main_model->fetch_school();//school
        $data['posts']=$this->main_model->fetch_post();
        $this->load->view('teacher/'.$page,$data);
    } 
    function updateMyProfile(){
        $user=$this->session->userdata('username');
        if(isset($_POST['profileFname'])){
            $config['upload_path']    = './profile/';
            $config['allowed_types']  = 'gif|jpg|png|ico';
            $this->load->library('upload', $config);

            $fName=$this->input->post('profileFname');
            $mName=$this->input->post('profileMname');
            $lName=$this->input->post('profileLname');

            $email=$this->input->post('profileEmail');
            $mobile=$this->input->post('profileMobile');
            $bio=$this->input->post('profileBio');


            if ($this->upload->do_upload('profilePhoto')){
                $dataa =  $this->upload->data('file_name');
                $dataProfile=array(
                    'fname'=>$fName,
                    'mname'=>$mName,
                    'lname'=>$lName,
                    'mobile'=>$mobile,
                    'email'=>$email,
                    'profile'=>$dataa,
                    'biography'=>$bio
                ); 
            }
            else{
                $dataProfile=array(
                    'fname'=>$fName,
                    'mname'=>$mName,
                    'lname'=>$lName,
                    'mobile'=>$mobile,
                    'email'=>$email,
                    'biography'=>$bio
                );
            }
            $this->db->where('username',$user);
            $queryUpdate=$this->db->update('users',$dataProfile);
            if($queryUpdate){
                echo'Profile updated successfully.';
            }else{
                echo 'Ooops Please try again.';
            }
        }
    }
    function changePassword(){
        $user=$this->session->userdata('username');
        if(isset($_POST['oldPassword'])){
            $pass1=$this->security->xss_clean($this->input->post('oldPassword',TRUE));
            $pass2=$this->security->xss_clean($this->input->post('newPassword',TRUE));
            $hashed_password = password_hash($pass2, PASSWORD_BCRYPT);
            $password=hash('sha256', $pass1);
            $password2=hash('sha256', $pass2);

            $change_it=$this->main_model->change_password($user,$pass1);
            if($change_it){
                $this->db->where('username',$user);
                $this->db->set('password', $hashed_password);
                $this->db->set('password2', $hashed_password);
                $this->db->update('users');
                echo '1';
            }
            else{
                echo '0';
            }
        }
    }
    function updateSignature(){
        $user=$this->session->userdata('username');
        define('UPLOAD_DIR', 'usersignature/');  
        $img = $_POST['dataUrl'];  
        $img = str_replace('data:image/png;base64,', '', $img);  
        $img = str_replace(' ', '+', $img);  
        $data = base64_decode($img);  
        $file = UPLOAD_DIR . uniqid() . '_'.$user.'.png';  
        $success = file_put_contents($file, $data);  
        if($success){
            $queryDelete=$this->db->query("select mysign from users where username='$user' ");
            $rowSign=$queryDelete->row();
            $oldFile=$rowSign->mysign;
            $filePath=FCPATH.$oldFile;
            if($oldFile!='' && file_exists($filePath)){
                if(unlink($filePath)){
                    $this->db->where('username',$user);
                    $this->db->set('mysign',$file);
                    $query=$this->db->update('users');
                    if($query){
                        echo '<img alt="image" src="'.base_url().'/'.$file.'" class="rounded-circle">';
                    }else{
                        echo 'Unable to save the file.';  
                    }
                }
            }else{
                $this->db->where('username',$user);
                $this->db->set('mysign',$file);
                $query=$this->db->update('users');
                if($query){
                    echo '<img alt="image" src="'.base_url().'/'.$file.'" class="rounded-circle">';
                }else{
                    echo 'Unable to save the file.';  
                }
            }
        }else{
            echo 'Unable to save the file.';  
        } 
    }
    function two_factor_authentication(){
        $user=$this->session->userdata('username');
        echo $this->main_model->two_factor_authentication($user);
    }
    function enable_two_factor_authentication(){
        $user=$this->session->userdata('username');
        if($this->input->post('twofactor')){
            $queryEmail=$this->db->query("select email from users where username='$user' and email!='' ");
            if($queryEmail->num_rows()>0){
                $twofactor=$this->input->post('twofactor');
                $querySelect=$this->db->query("select * from two_factor_authentication where user_id='$user' ");
                if($querySelect->num_rows()>0){
                    $this->db->where('user_id',$user);
                    $this->db->set('status','1');
                    $this->db->update('two_factor_authentication'); 
                }else{
                    $data=array(
                        'user_id'=>$user,
                        'status'=>'1'
                    );
                    $this->db->insert('two_factor_authentication',$data); 
                }
            }else{
                echo '0';
            }
        }
    }
    function disable_two_factor_authentication(){
        $user=$this->session->userdata('username');
        $this->db->where('user_id',$user);
        $this->db->set('status','0');
        $this->db->update('two_factor_authentication'); 
    }
}