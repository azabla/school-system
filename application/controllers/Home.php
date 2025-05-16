<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    $this->load->helper('security');
    if($this->session->userdata('username') == ''){
      $this->session->set_flashdata("error","Please Login first");
      redirect('Login/');
    }    
  }
	public function index($page='home')
  {
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $config['upload_path'] = './public_post/';
    $config['allowed_types'] ='png|jpg|jpeg';
    $this->load->library('upload', $config);

    $this->load->helper('date');
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');

    $this->db->select('id');
    $this->db->where(array('username'=>$user));
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $logged_id=$row_branch->id;
    $date_now= date('y-m-d');
    
    $now = new DateTime();
    $now->setTimezone(new DateTimezone('Africa/Addis_Ababa'));
    $datetime= $now->format('Y-m-d H:i:s');
    $userLevel = userLevel();

    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;
    if($userLevel=='3' && trim($_SESSION['usertype'])!==''){
      $dataArray=array();

      $this->db->select('username ,fname,mname,grade');
      $this->db->where(array('username'=>$user));
      $this->db->where(array('academicyear'=>$max_year));
      $query_gradesec=$this->db->get('users');
      if($query_gradesec->num_rows()>0){
        $row_gradesec = $query_gradesec->row();
        $fName=$row_gradesec->fname;
        $mName=$row_gradesec->mname;
        $username=$row_gradesec->username;
        $grade=$row_gradesec->grade;
        $dataArray=array(
          'username'=>$user,
          'first_name'=>$fName,
          'last_name'=>$mName
        );
      }else{
        $dataArray=array(
          'username'=>'',
          'first_name'=>'',
          'last_name'=>''
        );
      }
      if(isset($_POST['post'])){
        $this->upload->do_upload('postphoto');
        $postphoto= $this->upload->data('file_name');
        $post=$this->input->post('posthere');
        $title=$this->input->post('title');
        $date_post=date('M-d-Y');
        if($postphoto!=='' || $post!==''){
          if($postphoto==''){
            $data=array(
              'title'=>$title,
              'post'=>$post,
              'postby'=>$user,
              'date_post'=>$date_post
            );
          }else{
            $data=array(
              'title'=>$title,
              'photo'=>$postphoto,
              'postby'=>$user,
              'date_post'=>$date_post
            );
          }
          $id=$this->main_model->post_data($data);
          redirect('home','refresh');
        }
        else{
          redirect('home','refresh');
        }
      }
      if(isset($_GET['post_id'])){
        $id=$_GET['post_id'];
        $this->main_model->delete_post($id);
      }
      if(isset($_GET['postComid'])){
        $id=$_GET['postComid'];
        $this->main_model->delete_post_comment($id);
      }
      $data['currentYear']=$max_year;
      $data['userName']=$user;
      $data['check_payment']=$this->main_model->check_payment($user,$max_year);
      $data['my_History']=$dataArray;
      $data['sessionuser']=$this->main_model->fetch_session_user($user);
      $data['academicyear']=$this->main_model->academic_year_filter();
      $data['schools']=$this->main_model->fetch_school();
      $data['posts']=$this->main_model->fetch_post();
      $data['usergroup']=$this->main_model->fetchUserGroupRegistration();
      $this->load->view('student/'.$page,$data);
    }
    else if($userLevel=='2' && trim($_SESSION['usertype'])!==''){
      if(isset($_POST['post'])){
        $this->upload->do_upload('postphoto');
        $postphoto= $this->upload->data('file_name');
        $post=$this->input->post('posthere');
        $title=$this->input->post('title');
        $date_post=date('M-d-Y');
        if($postphoto!=='' || $post!==''){
          if($postphoto==''){
            $data=array(
              'title'=>$title,
              'post'=>$post,
              'postby'=>$user,
              'date_post'=>$date_post
            );
          }else{
            $data=array(
              'title'=>$title,
              'photo'=>$postphoto,
              'postby'=>$user,
              'date_post'=>$date_post
            );
          }
          $id=$this->main_model->post_data($data);
          redirect('home','refresh');
        }
        else{
          redirect('home','refresh');
        }
      }
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
    }
    else if (trim($_SESSION['usertype'])==='') {
      $this->session->set_flashdata("error",'Your user type is not set. Please contact your system Admin!');
      redirect('loginpage/','refresh');
    }
    else {
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
		  $this->load->view('home-page/'.$page,$data);
	  } 
  }
  function save_token()
  {
      $this->main_model->save_token();
  }
  function checkNewUserFound(){
    $user=$this->session->userdata('username');
    $tot=$this->main_model->fetchAllMyNewUserNotification();
    if($tot->num_rows()>0){
      foreach($tot->result() as $myMess){
        $title =$myMess->fname.' '.$myMess->lname;
        $body =$myMess->usertype;
    
        $query1 ="SELECT distinct token FROM `notification_tokens_tbl` where delete_status=?";
        $query=$this->db->query($query1,array('N'));
        $query_res = $query->result();
        foreach ($query_res as $query_res_data) {
          $registrationIds[] =$query_res_data->token;
        }
        
        $url ="https://fcm.googleapis.com/fcm/send";
        //"to" for single user
        //"registration_ids" for multiple users
        //$title = "$user";
        //$body = "New Message has been found.";
        $BaseUrl=base_url();
        $schools=$this->main_model->fetch_school();
        foreach($schools as $school){
            $icon = ''.$BaseUrl.'logo/'.$school->logo.'';
        }
        $click_action = ''.$BaseUrl.'inbox/';
        $fields=array(
            "registration_ids"=>$registrationIds,
            "notification"=>array(
            "body"=>$body,
            "title"=>$title,
            "icon"=>$icon,
            "click_action"=>$click_action
            )
        );
        //print_r($fields);
        //exit;
        
        $headers=array(
        'Authorization: key=AAAAv9-EmBs:APA91bFLeVp7y35RHreE5Vwxk5mq-cyckoe062CebxPlfdJgYd1Eh_KqU4uRuHCTLOk-NV8gpO7-GBxorqTAgpwN9WexbZR1sZBOvBdLyr2V46OG7-M_NLOuCvWSFW2H1AWlehRpBAGu',
        'Content-Type:application/json'
        );
    
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
        $result=curl_exec($ch);
        print_r($result);
        curl_close($ch);
      }
    }
  }
  function activateUrlStrings($str){
    $find = array('`((?:https?|ftp)://\S+[[:alnum:]]/?)`si', '`((?<!//)(www\.\S+[[:alnum:]]/?))`si');
    $replace = array('<a href="$1" target="_blank">$1</a>', '<a href="http://$1" target="_blank">$1</a>');
    return preg_replace($find,$replace,$str);
  }
  function postFeed(){
    $user=$this->session->userdata('username');
    /*if(isset($_POST['post_text'])){*/
    $config['upload_path'] = './public_post/';
    $config['allowed_types'] ='png|jpg|jpeg';
    $config['max_size'] = '300';
    $this->load->library('upload', $config);
    $post_text=$this->input->post('post_text',TRUE);
    $post_text=xss_clean($post_text);
    $find = array('`((?:https?|ftp)://\S+[[:alnum:]]/?)`si', '`((?<!//)(www\.\S+[[:alnum:]]/?))`si');
    $replace = array('<a href="$1" target="_blank">$1</a>', '<a href="http://$1" target="_blank">$1</a>');
    $string= preg_replace($find,$replace,$post_text);
    $post=$this->input->post('posthere',TRUE);
    /*$post=xss_clean($post);*/
    $title=$this->input->post('postTitle',TRUE);
    $title=xss_clean($title);
    $audience=$this->input->post('postAudience',TRUE);
    $audience=xss_clean($audience);
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
    if($usertype==trim('superAdmin') ){
      echo $this->main_model->fetch_posts($this->input->post('limit'), $this->input->post('start'));
    }else{
      echo $this->main_model->fetch_post_users($usertype,$user,$this->input->post('limit'), $this->input->post('start'));
    }
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
      $post_title=strip_tags($this->input->post('post_title',TRUE));
      $updatepostAudience=$this->input->post('updatepostAudience',TRUE);
      $updatepost_text=strip_tags($this->input->post('updatepost_text',TRUE));
      $updated_pid=$this->input->post('updated_pid',TRUE);

      $post_title=xss_clean($post_title);
      $updatepostAudience=xss_clean($updatepostAudience);
      $updatepost_text=xss_clean($updatepost_text);
      $updated_pid=xss_clean($updated_pid);

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