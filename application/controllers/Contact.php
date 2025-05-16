<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {
	public function index()
	{

		if(isset($_POST['login'])){
            $this->form_validation->set_rules('username','Username','required');
            $this->form_validation->set_rules('password','Password','required');
            $this->load->helper('date');
    	
    	
    		if($this->form_validation->run()){
    			$username=$this->input->post('username');
    			$password1=$this->input->post('password');
                $password=md5($password1);
 
                $this->load->model('main_model');
                $user=$this->main_model->can_login($username,$password);
                if($user){
                   $session_data=array(
                    'id'=>$user->id,
                    'usertype'=>$user->usertype,
                    'username'=>$user->username,
                    'fname'=>$user->fname,
                    'mname'=>$user->mname,
                    'lname'=>$user->lname,
                    'mobile'=>$user->mobile,
                    'grade'=>$user->grade,
                    'gender'=>$user->gender,
                    'city'=>$user->city,
                    'email'=>$user->email,
                    'profile'=>$user->profile,
                    'status'=>$user->status,
                    'dob'=>$user->dob,
                    'biography'=>$user->biography
                   ); 
                   $this->session->set_userdata($session_data);
                   $query_branch = $this->db->query("select * from users where username='$username'");
                   $row_branch = $query_branch->row();
                   $logged_id=$row_branch->id;
                   $date_now= date('y-m-d');
                   $now = new DateTime();
                   $now->setTimezone(new DateTimezone('Africa/Addis_Ababa'));
                   $datetime= $now->format('Y-m-d H:i:s');
                   $query_log=$this->main_model->Loged_users($logged_id,$date_now,$datetime);

                   redirect('home/',"refresh");
                }
                else{
                 $this->session->set_flashdata("error",'Either Invalid Username and Password Or Not Approved!');
                 redirect("contact/");
                }
    		}
    	}
        $this->load->model('main_model');
        $data['blogs']=$this->main_model->fetch_blogs();
        $data['social_pages']=$this->main_model->fetch_social_pages();
        $data['schools']=$this->main_model->fetch_school();
		    $this->load->view('contact',$data);
	} 
}