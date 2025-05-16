<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function index()
	{
        $this->load->model('main_model');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_COOKIE['username']) && isset($_COOKIE['password'])){
            $user=$this->main_model->can_login($_COOKIE['username'],$_COOKIE['password']);
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
                    'biography'=>$user->biography,
                    'unique_id'=>$user->unique_id
                );
                $this->load->library('user_agent');
                $data1= $this->agent->browser();
                $data2 = $this->agent->version();
                $data3 = $this->agent->platform();
                $data4 = $this->input->ip_address();
                $query_branch = $this->db->query("select * from users where username='".$_COOKIE['username']."'");
                $row_branch = $query_branch->row();
                $logged_id=$row_branch->id;
                $date_now= date('y-m-d');
                $now = new DateTime();
                $now->setTimezone(new DateTimezone('Africa/Addis_Ababa'));
                $datetime= $now->format('Y-m-d H:i:s');
                $query_log=$this->main_model->Loged_users($logged_id,$date_now,$datetime,$data1,$data2,$data3,$data4);
                $this->session->set_userdata($session_data);
                redirect('home/');
            }
        }else{
            $data['currentYear']=$max_year;
            $data['blogs']=$this->main_model->fetch_blogs();
            $data['teachers']=$this->main_model->fetch_teachers();
            $data['fetch_gallery']=$this->main_model->fetch_gallery();
            $data['fetch_galleryToWebsite']=$this->main_model->fetch_galleryToWebsite_login();
            $data['fetch_single_gallery']=$this->main_model->fetch_single_gallery();
            $data['social_pages']=$this->main_model->fetch_social_pages();
            $data['schools']=$this->main_model->fetch_school();
            $this->load->view('login',$data);
        } 
	}
    public function download($id) {   
        if(!empty($id)){
            $this->load->helper('download');
            $fileInfo = 'EthioNationalSchool.apk';
            $file = 'apk/'.$fileInfo;
            force_download($file, NULL);
        }
    }  
}