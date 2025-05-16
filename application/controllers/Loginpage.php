<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loginpage extends CI_Controller {
	public function index()
	{
        $this->load->helper('security');
        $this->load->model('main_model');
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
                    'unique_id'=>$user->unique_id,
                    'status2'=>$user->status2
                );
                $this->load->library('user_agent');
                $data1= $this->agent->browser();
                $data2 = $this->agent->version();
                $data3 = $this->agent->platform();
                $data4 = $this->input->ip_address();

                $this->db->where('username',$_COOKIE['username']);
                $query_branch=$this->db->get('users');
                $row_branch = $query_branch->row();
                $logged_id=$row_branch->id;
                $date_now= date('y-m-d');
                date_default_timezone_set('Africa/Addis_Ababa');
                $datetime= date('Y-m-d H:i:s', time());
                $query_log=$this->main_model->Loged_users($logged_id,$date_now,$datetime,$data1,$data2,$data3,$data4);
                $this->session->set_userdata($session_data);
                redirect('home/',"refresh");
            }
        }
        if(isset($_POST['login'])){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username','Username','required');
            $this->form_validation->set_rules('password','Password','required');
            $this->load->helper('date');
          	if($this->form_validation->run()==TRUE)
            {
        		$username=$this->input->post('username',true);
                $username=xss_clean($username);
        		$password1=$this->input->post('password');
                $passwordSha=hash('sha256', $password1);
                $userSha=$this->main_model->can_login($username,$password1);
                if($userSha){
                    $this->db->where('user_id',$username);
                    $this->db->where('status','1');
                    $querySelect=$this->db->get('two_factor_authentication');
                    if($querySelect->num_rows()>0){
                        $email=$userSha->email;
                        $this->db->where('user_name',$username);
                        $this->db->where('email',$email);
                        $queryValidation=$this->db->get('two_factor_validation');
                        if($queryValidation->num_rows()>0){
                            $rowData=$queryValidation->row();
                            $otp=$rowData->otp;
                            $otpExpiry=$rowData->otp_expiration;
                            if(((!is_null($otp) && !is_null($otpExpiry) && strtotime($otpExpiry) < time()) ) ){
                                $otp = sprintf("%'.06d",mt_rand(0,999999));
                                $expiration = date("Y-m-d H:i" ,strtotime(date('Y-m-d H:i')." +3 mins"));
                                $this->db->where('user_name',$username);
                                $this->db->where('email',$email);
                                $this->db->set('otp',$otp);
                                $this->db->set('otp_expiration',$expiration);
                                $queryUpdate=$this->db->update('two_factor_validation');
                                if($queryUpdate){
                                    $config=array(
                                        'protocol'=>'smtp',
                                        'smtp_host'=>'mail.grandstande.com',
                                        'smtp_port'=>465,
                                        'smtp_user'=>'contact@grandstande.com',
                                        'smtp_pass'=>'350Ae5Yj%$Uc',
                                        'mailtype'=>'html',
                                        'smtp_crypto'=> 'ssl',
                                        'newline' => "\r\n",
                                        'crlf' => "\r\n",
                                        'charset'=>'iso-8859-1',
                                        'wordwrap'=>TRUE
                                    );
                                    $this->load->library('email',$config);
                                    $this->email->from('contact@grandstande.com', "GS Technologies");
                                    $this->email->to($email);
                                    $this->email->subject("GS Technologies 2F Authentication");
                                    $message = '<html>
                                            <body>
                                                <h2>OTP Verification</h2>
                                                <div class="support-ticket media pb-1 mb-3">
                                                    <div class="media-body ml-3">
                                                      <span class="font-weight-bold">Here is your OTP (One-Time Password) to verify your Identity.<h3><b>'.$otp.'</b></h3></span>
                                                      <p class="my-1">Please verify it with in 3min before expired.</p>
                                                      
                                                    </div>
                                                    <p> Visit our website <a href="https://www.grandstande.com" target="_blanck">Myschool SMS (Grandstande Technologies.)</a>
                                                    Contact us on Telegram <a href="https://t.me/GrandstandeSupport" target="_blanck">GS Support</a> </p>
                                                    <small class="text-muted">'.date('Y-m-d').'</small>
                                                  </div>
                                            </body>
                                        </html>';
                                    $this->email->message($message);
                                    if($this->email->send()){
                                        $_SESSION['login_email'] = $email;
                                        $_SESSION['login_username'] = $username;
                                        $this->session->set_flashdata('success','OTP verification has been sent successfully, Please verify it with in 3min before expired.');
                                        redirect('authenticationpage','refresh');
                                    }
                                    else{
                                        $this->session->set_flashdata('error','Unable to send OTP, Please try again.');
                                        redirect('loginpage','refresh');
                                    }
                                }else{
                                    $this->session->set_flashdata("error",'Something wrong please try again.');
                                }
                            }else{
                                $_SESSION['login_email'] = $email;
                                $_SESSION['login_username'] = $username;
                                $this->session->set_flashdata('error','Please insert your OTP which is sent to your Email.');
                                redirect('authenticationpage','refresh');
                            }
                        }else{
                            $dataInsertOtp=array();
                            $otp = sprintf("%'.06d",mt_rand(0,999999));
                            $expiration = date("Y-m-d H:i" ,strtotime(date('Y-m-d H:i')." +3 mins"));
                            $dataInsertOtp=array(
                                'user_name'=>$username,
                                'email'=>$email,
                                'otp'=>$otp,
                                'otp_expiration'=>$expiration
                            );
                            $queryUpdate=$this->db->insert('two_factor_validation',$dataInsertOtp);
                            if($queryUpdate){
                                $config=array(
                                    'protocol'=>'smtp',
                                    'smtp_host'=>'mail.grandstande.com',
                                    'smtp_port'=>465,
                                    'smtp_user'=>'contact@grandstande.com',
                                    'smtp_pass'=>'350Ae5Yj%$Uc',
                                    'mailtype'=>'html',
                                    'smtp_crypto'=> 'ssl',
                                    'newline' => "\r\n",
                                    'crlf' => "\r\n",
                                    'charset'=>'iso-8859-1',
                                    'wordwrap'=>TRUE
                                );
                                $this->load->library('email',$config);
                                $this->email->from('contact@grandstande.com', "GS Technologies");
                                $this->email->to($email);
                                $this->email->subject("GS Technologies OTP Verification");
                                $message = "<html>
                                        <body>
                                            <h2>GS Technologies Two-Factor Authentication</h2>
                                            <p>Here is your OTP (One-Time PIN) to verify your Identity.</p>
                                            <h3><b>".$otp."</b></h3>
                                        </body>
                                    </html>";
                                $this->email->message($message);
                                if($this->email->send()){
                                    $_SESSION['login_email'] = $email;
                                    $_SESSION['login_username'] = $username;
                                    $this->session->set_flashdata('success','OTP verification has been sent successfully, Please verify it with in 3min before expired.');
                                    redirect('authenticationpage','refresh');
                                }
                                else{
                                    $this->session->set_flashdata('error','Email was not sent, Please try again.');
                                   redirect('loginpage','refresh');
                                }
                            }else{
                                $this->session->set_flashdata("error",'Something wrong please try again.');
                            }
                        }
                    }else{
                        if($this->input->post('remember')!=NULL){
                            setcookie('username',$username,time()+(10*365*24*60*60),"/");
                            setcookie('password',$passwordSha,time()+(10*365*24*60*60),"/");
                        }
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
                    }
                }else{
                    $this->session->set_flashdata("error",'Please enter valid details. ');                       
                }
          	}
        }
        $data['blogs']=$this->main_model->fetch_blogs();
        $data['social_pages']=$this->main_model->fetch_social_pages();
        $data['schools']=$this->main_model->fetch_school();
		$this->load->view('signin',$data);
	}
}