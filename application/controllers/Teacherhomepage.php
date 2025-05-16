<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Teacherhomepage extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    $this->load->library('excel');
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
	public function index($page='home')
  {
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $this->load->helper('date');
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query_branch = $this->db->query("select id from users where username='$user'");
    $row_branch = $query_branch->row();
    $logged_id=$row_branch->id;
    $date_now= date('y-m-d');
    
    $now = new DateTime();
    $now->setTimezone(new DateTimezone('Africa/Addis_Ababa'));
    $datetime= $now->format('Y-m-d H:i:s');
    $userLevel = userLevel();
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($userLevel=='2' && trim($_SESSION['usertype'])!==''){
      if(isset($_GET['post_id'])){
        $id=$_GET['post_id'];
        $this->main_model->delete_post($id);
      }
      if(isset($_GET['postComid'])){
        $id=$_GET['postComid'];
        $this->main_model->delete_post_comment($id);
      }
      $data['sessionuser']=$this->main_model->fetch_session_user($user);
      $data['academicyear']=$this->main_model->academic_year_filter();
      $data['schools']=$this->main_model->fetch_school();
      $data['posts']=$this->main_model->fetch_post();
      $data['usergroup']=$this->main_model->fetchUserGroupRegistration();
      $this->load->view('teacher/'.$page,$data);
    }else if (trim($_SESSION['usertype'])==='' || trim($_SESSION['usertype'])==''){
      $this->session->set_flashdata("error",'Your user type is not set. Please contact your system Admin!');
      redirect('loginpage/','refresh');
    }else{
      redirect('loginpage/','refresh');
    } 
  }
  function postFeed(){
    $user=$this->session->userdata('username');
    /*if(isset($_POST['post_text'])){*/
    $config['upload_path'] = './public_post/';
    $config['allowed_types'] ='png|jpg|jpeg';
    $config['max_size'] = '300';
    $this->load->library('upload', $config);
    $post_text=$this->input->post('post_text');
    $find = array('`((?:https?|ftp)://\S+[[:alnum:]]/?)`si', '`((?<!//)(www\.\S+[[:alnum:]]/?))`si');
    $replace = array('<a href="$1" target="_blank">$1</a>', '<a href="http://$1" target="_blank">$1</a>');
    $string= preg_replace($find,$replace,$post_text);
    $post=$this->input->post('posthere');
    $title=strip_tags($this->input->post('postTitle'));
    $audience=$this->input->post('postAudience');
    $date_post=date('M-d-Y');
    if ($this->upload->do_upload('postPicture')){
      $dataa =  $this->upload->data('file_name');
      $data=array(
        'title'=>$title,
        'photo'=>$dataa,
        'postby'=>$user,
        'user'=>$audience,
        'date_post'=>$date_post
      );
    }else{
      $data=array(
        'title'=>$title,
        'post'=>$string,
        'postby'=>$user,
        'user'=>$audience,
        'date_post'=>$date_post
      );
    }
    echo $this->main_model->post_data($data);
  }
  function fetch_feeds(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    echo $this->main_model->fetch_post_users($usertype,$user,$this->input->post('limit'), $this->input->post('start'));
  }
  function fetch_postdata_to_edit(){
    if($this->input->get('post_id')){
      $post_id=$this->input->get('post_id');
      echo $this->main_model->fetch_postdata_to_edit($post_id);
    }
  }
  function update_postdata_to_edit(){
    $usertype=$this->session->userdata('usertype');
    if($this->input->post('post_title')){
      $post_title=strip_tags($this->input->post('post_title'));
      $updatepostAudience=$this->input->post('updatepostAudience');
      $updatepost_text=strip_tags($this->input->post('updatepost_text'));
      $updated_pid=$this->input->post('updated_pid');
      $find = array('`((?:https?|ftp)://\S+[[:alnum:]]/?)`si', '`((?<!//)(www\.\S+[[:alnum:]]/?))`si');
      $replace = array('<a href="$1" target="_blank">$1</a>', '<a href="http://$1" target="_blank">$1</a>');
      $string= preg_replace($find,$replace,$updatepost_text);
      $this->db->where('pid',$updated_pid);
      $this->db->set('title',$post_title);
      $this->db->set('user',$updatepostAudience);
      $this->db->set('post',$string);
      $this->db->set('updated_status','1');
      $query=$this->db->update('post');
      if($query){
        echo $this->main_model->fetch_edited_posts($updated_pid);
      }
    }
  }
}