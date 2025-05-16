<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{
    public function index()
    {      
        $this->load->helper('security');
        $this->load->model('main_model');
    	if(isset($_POST['register'])){
        $this->load->library('form_validation');
    	$this->form_validation->set_rules('username','User name','required');
    	$this->form_validation->set_rules('frist_name','First name','required');
    	$this->form_validation->set_rules('last_name','Last name','required');
    	$this->form_validation->set_rules('email','Email','required');
        $this->form_validation->set_rules('mobile','Mobile','required');
    	$this->form_validation->set_rules('password','Password','required|min_length[8]');
    	$this->form_validation->set_rules('password-confirm','password-confirm','required|min_length[8]|matches[password]');
    		if($this->form_validation->run()==TRUE){
                $config['upload_path']    = './profile/';
                $config['allowed_types']  = 'gif|jpg|png|ico';
                $this->load->library('upload', $config);
                $username=xss_clean($this->input->post('username'));
                $usertype=xss_clean($this->input->post('usertype'));
                $frist_name=xss_clean($this->input->post('frist_name'));
                $last_name=xss_clean($this->input->post('last_name'));
                $gf_name=xss_clean($this->input->post('gf_name'));
                $gender=xss_clean($this->input->post('gender'));
                $mobile=xss_clean($this->input->post('mobile'));
                $email=xss_clean($this->input->post('email'));
                $grade=xss_clean($this->input->post('grade'));
                $dob=xss_clean($this->input->post('dob'));
                $moname=xss_clean($this->input->post('moname'));
                $city=xss_clean($this->input->post('city'));
                $subcity=xss_clean($this->input->post('subcity'));
                $woreda=xss_clean($this->input->post('woreda'));
                $password=xss_clean($this->input->post('password'));
                $passwordc=xss_clean($this->input->post('password-confirm'));
                $academicyear=xss_clean($this->input->post('academicyear'));
                $branch=xss_clean($this->input->post('branch'));
                $user=xss_clean($this->main_model->can_register($username));
                if($gender==''){
                    $gender='Male';
                }else{
                    $gender=$gender;
                }
                if (xss_clean($this->upload->do_upload('profile'))){
                    $dataa =  $this->upload->data('file_name');
                    if($user){
                        $data=array(
                            'username'=>$username,
                            'usertype'=>$usertype,
    				        'fname'=>$frist_name,
                            'mname'=>$last_name,
    				        'lname'=>$gf_name,
                            'gender'=>$gender,
                            'mobile'=>$mobile,
    				        'email'=>$email,
                            'profile'=>$dataa,
                            'grade'=>$grade,
                            'dob'=>$dob,
                            'mother_name'=>$moname,
                            'city'=>$city,
                            'sub_city'=>$subcity,
                            'woreda'=>$woreda,
    				        'password'=>hash('sha256', $password),
                            'password2'=>hash('sha256', $password),
                            'branch'=>$branch,
                            'academicyear'=>$academicyear,
    				        'dateregister'=>date('Y-m-d')
                        );
                        $query=$this->main_model->register_user($data);
                        if($query){
                            $this->session->set_flashdata("success",'Your account has been registered.You can login now!');
                            redirect('Register/','refresh');
                        }
                    }
                    else{
                        $this->session->set_flashdata("error",'This Username is already exists. Please try again.');
                        redirect('Register/','refresh');
                    }
                }else{
                    if($user){
                        $data=array(
                            'username'=>$username,
                            'usertype'=>$usertype,
                            'fname'=>$frist_name,
                            'mname'=>$last_name,
                            'lname'=>$gf_name,
                            'gender'=>$gender,
                            'mobile'=>$mobile,
                            'email'=>$email,
                            'grade'=>$grade,
                            'dob'=>$dob,
                            'mother_name'=>$moname,
                            'city'=>$city,
                            'sub_city'=>$subcity,
                            'woreda'=>$woreda,
                            'password'=>hash('sha256', $password),
                            'password2'=>hash('sha256', $password),
                            'branch'=>$branch,
                            'academicyear'=>$academicyear,
                            'dateregister'=>date('Y-m-d')
                        );
                        $query=$this->main_model->register_user($data);
                        if($query){
                            $this->session->set_flashdata("success",'Your account has been registered.You can login now!');
                            redirect('Register/','refresh');
                        }
                    }
                    else{
                        $this->session->set_flashdata("error",'This Username is already exists. Please try again.');
                        redirect('Register/','refresh');
                    }
                }
            }
        }
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['currentYear']=$max_year;
        $data['blogs']=$this->main_model->fetch_blogs();
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['usergroup']=$this->main_model->fetchUserGroupRegistration();
        $data['gradeGroup']=$this->main_model->studentRegistrationGrade($max_year);
        $this->load->view('register',$data);
    }
}