<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Authenticationpage extends CI_Controller {
    public function index()
    {
        $this->load->helper('security');
        $this->load->model('main_model');
        if(isset($_POST['sendOTP']) && isset($_SESSION['login_email']) && !is_null($_SESSION['login_email'])){ 
            $email = $_SESSION["login_email"];    
            $username = $_SESSION["login_username"]; 
            $triedOTP= $this->input->post('my_otp',TRUE); 
            $triedOTP=xss_clean($triedOTP); 
            $this->db->where('user_name',$username);
            $this->db->where('email',$email);
            $queryValidation=$this->db->get('two_factor_validation');

            /*$queryValidation=$this->db->query("select * from two_factor_validation where user_name =".$this->db->escape($username)." and email=".$this->db->escape($email));*/
            if($queryValidation->num_rows()>0){
                $rowData=$queryValidation->row();
                $otp=$rowData->otp;
                $otpExpiry=$rowData->otp_expiration;
                if($otp==$triedOTP){
                    if(( (!is_null($otp) && !is_null($otpExpiry) && strtotime($otpExpiry) > time()) ) ){
                        $this->session->set_flashdata("error",'Logged Succesfully.',2);
                        $userSha=$this->main_model->can_login_byonly_email($username);
                        $session_data=array(
                            'id'=>$userSha->id,
                            'usertype'=>$userSha->usertype,
                            'username'=>$userSha->username,
                            'fname'=>$userSha->fname,
                            'mname'=>$userSha->mname,
                            'lname'=>$userSha->lname,
                            'mobile'=>$userSha->mobile,
                            'grade'=>$userSha->grade,
                            'gender'=>$userSha->gender,
                            'city'=>$userSha->city,
                            'email'=>$userSha->email,
                            'profile'=>$userSha->profile,
                            'status'=>$userSha->status,
                            'dob'=>$userSha->dob,
                            'biography'=>$userSha->biography,
                            'unique_id'=>$userSha->unique_id,
                            'status2'=>$userSha->status2
                        );
                        $this->session->set_userdata($session_data);
                        $this->load->library('user_agent');
                        $data1= $this->agent->browser();
                        $data2 = $this->agent->version();
                        $data3 = $this->agent->platform();
                        $data4 = $this->input->ip_address();
                        $this->session->set_userdata($session_data);
                        $this->db->where('username',$username);
                        $query_branch=$this->db->get('users');

                        $row_branch = $query_branch->row();
                        $logged_id=$row_branch->id;
                        $date_now= date('y-m-d');
                        date_default_timezone_set('Africa/Addis_Ababa');
                        $datetime= date('Y-m-d H:i:s', time());
                        $query_log=$this->main_model->Loged_users($logged_id,$date_now,$datetime,$data1,$data2,$data3,$data4);
                        redirect('home/');
                    }else{
                        $this->session->set_flashdata("error",'OTP Expired. Type your credentials to re-send OTP!');
                        redirect('loginpage','refresh');
                    }
                }else{
                    $this->session->set_flashdata("error",'Incorrect OTP.');
                    redirect('authenticationpage','refresh');
                }
            }else{
                $this->session->set_flashdata("error",'Incorrect credentials.Please try again');
                redirect('loginpage','refresh');
            }   
        }
        $data['blogs']=$this->main_model->fetch_blogs();
        $data['social_pages']=$this->main_model->fetch_social_pages();
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('authenticationpage',$data); 
    }
}