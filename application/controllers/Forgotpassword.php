<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Forgotpassword extends CI_Controller {
	public function index()
	{
        $this->load->model('main_model');
        if(isset($_POST['resetpassword'])){
			$email=$this->input->post('email');
			$que=$this->db->query("select password,email from users where email='$email'");
			if($que->num_rows() > 0){
			    $countEmail=$que->num_rows();
			    if($countEmail == 1){
    				$row=$que->row();
    				$user_email=$row->email;
    				$config=array(
    					'protocol'=>'smtp',
    					'smtp_host'=>'mail.grandstande.com',
    					'smtp_port'=>465,
    					'smtp_user'=>'admin@grandstande.com',
    					'smtp_pass'=>'tN$W%~T-9)a)',
    					'mailtype'=>'html',
    					'smtp_crypto'=> 'ssl',
    					'newline' => "\r\n",
                        'crlf' => "\r\n",
    					'charset'=>'iso-8859-1',
    					'wordwrap'=>TRUE
    				);
    				if((!strcmp($email, $user_email))){
    					$pass=$row->password;
    					$to = $user_email;
    					$temp_pass = uniqid();
    					$uo=hash('sha256', $temp_pass);
    					$this->load->library('email',$config);
    					$this->email->from('admin@grandstande.com', "GS Technologies");
                		$this->email->to($this->input->post('email'));
                		$this->email->subject("Reset your Password");
                		$message = "<p>$temp_pass is your password for temporary use,you can change after login</p>";
                        $this->email->message($message);
                        if($this->email->send()){
                            $this->db->where('email',$email);
                            $this->db->set('password',$uo);
                            $this->db->set('password2',$uo);
                            $this->db->update('users');
                        	$this->session->set_flashdata('success','Check your email for instructions, Thank you.');
                        	redirect('loginpage/','refresh');
                        }
                        else{
                        	$this->session->set_flashdata('error','Email was not sent, Please try again.');
                           redirect('forgotpassword/','refresh');
                        }
    				}
    				else { 
    				$this->session->set_flashdata('error','Invalid email address.');
    				redirect('forgotpassword/','refresh');
    				}
			    }else{
			        $this->session->set_flashdata('error','Ii is not your email address.');
    				redirect('forgotpassword/','refresh');
			    }
			}
			else {
				$this->session->set_flashdata('error','Invalid email address.');
				redirect('forgotpassword/','refresh');
			}	
    	}
    	$data['blogs']=$this->main_model->fetch_blogs();
        $data['social_pages']=$this->main_model->fetch_social_pages();
        $data['schools']=$this->main_model->fetch_school();
		$this->load->view('forgotpassword',$data);
	} 
}