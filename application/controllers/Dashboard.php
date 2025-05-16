<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    if($this->session->userdata('username') == ''){
      $this->session->set_flashdata("error","Please Login first");
      redirect('Login/');
    }    
  }
	public function index($page='dashboard')
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
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $logged_id=$row_branch->id;
    $date_now= date('y-m-d');
    
    $now = new DateTime();
    $now->setTimezone(new DateTimezone('Africa/Addis_Ababa'));
    $datetime= $now->format('Y-m-d H:i:s');
    $userLevel = userLevel();
    if($userLevel=='3' && trim($_SESSION['usertype'])!==''){
      if(isset($_POST['post'])){
        $this->upload->do_upload('postphoto');
        $postphoto= $this->upload->data('file_name');
        $post=$this->input->post('posthere');
        $title=$this->input->post('title');
        //$postby=$this->session->userdata('username');
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
          redirect('dashboard','refresh');
        }
        else{
          redirect('dashboard','refresh');
        }
      }
      if(isset($_GET['post_id'])){
        $id=$_GET['post_id'];
        $this->main_model->delete_post($id);
      }
      $data['sessionuser']=$this->main_model->fetch_session_user($user);
      $data['academicyear']=$this->main_model->academic_year_filter();
      $data['schools']=$this->main_model->fetch_school();
      $data['posts']=$this->main_model->fetch_post();
      $this->load->view('student/'.$page,$data);
    }
    else if($userLevel=='2' && trim($_SESSION['usertype'])!==''){
      if(isset($_POST['post'])){
        $this->upload->do_upload('postphoto');
        $postphoto= $this->upload->data('file_name');
        $post=$this->input->post('posthere');
        $title=$this->input->post('title');
        // $postby=$this->session->userdata('username');
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
          redirect('dashboard','refresh');
        }
        else{
          redirect('dashboard','refresh');
        }
      }
      if(isset($_GET['post_id'])){
        $id=$_GET['post_id'];
        $this->main_model->delete_post($id);
      }
      $data['sessionuser']=$this->main_model->fetch_session_user($user);
      $data['academicyear']=$this->main_model->academic_year_filter();
      $data['schools']=$this->main_model->fetch_school();
      $data['posts']=$this->main_model->fetch_post();
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
      if(isset($_POST['post'])){
        $this->upload->do_upload('postphoto');
        $postphoto= $this->upload->data('file_name');
        $post=$this->input->post('posthere');
        $title=$this->input->post('title');
        //$postby=$this->session->userdata('username');
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
          redirect('dashboard','refresh');
        }
        else{
          redirect('dashboard','refresh');
        }
      }
      $data['sessionuser']=$this->main_model->fetch_session_user($user);
      $data['academicyear']=$this->main_model->academic_year_filter();
      $data['schools']=$this->main_model->fetch_school();
      $data['posts']=$this->main_model->fetch_post();
		  $this->load->view('home-page/'.$page,$data);
	  } 
  }
  function fetchGradeReportGraph(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
     $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $accessbranch = sessionUseraccessbranch(); 
    if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
      $record= $this->main_model->fetchSubjectMarkAnalysisGraphSup($max_year);
    }else{
      $record= $this->main_model->fetchSubjectMarkAnalysisGraphAdmin($mybranch,$max_year);
    }
    $data2 =array();
    foreach($record as $row) {
      $grade=$row['grade'];
      $queryTotal=$this->db->query("select *,count(id) as total from users where academicyear='$max_year' and usertype='Student' and isapproved='1' and status='Active' and grade='$grade' group by grade  ");
      if($queryTotal->num_rows()>0){
        $rowGrade=$queryTotal->row_array();
        $rowTotal=$rowGrade['total'];
      }
      $data2[] = array(
        'language'    =>  $row["grade"],
        'total'     =>  $rowGrade['total'],
        'color'     =>  '#' . rand(100000, 999999) . ''
      );
    }
    $variable = array('data2' => $data2);
    echo json_encode($variable);
    
  }
  function fetchGradeGenderReportGraph(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
     $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $accessbranch = sessionUseraccessbranch(); 
    if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
      $record= $this->main_model->fetchSubjectMarkAnalysisGraphSup($max_year);
    }else{
      $record= $this->main_model->fetchSubjectMarkAnalysisGraphAdmin($mybranch,$max_year);
    }
    $data2 =array();
    foreach($record as $row) {
      $grade=$row['grade'];
      $queryTotal=$this->db->query("SELECT *, CONCAT('grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where grade='$grade' and academicyear='$max_year' and usertype='Student' and isapproved='1' and status='Active' GROUP BY grade ORDER BY fname,mname,lname ASC");
      if($queryTotal->num_rows()>0){
        $rowGrade=$queryTotal->row_array();
        $rowTotal=$rowGrade['studentcount'];
      }
      $data2[] = array(
        'grade' => $row["grade"],
        'total' => $rowGrade['studentcount'],
        'male'  => $rowGrade['malecount'],
        'female' => $rowGrade['femalecount'],
        'color' =>  '#' . rand(100000, 999999) . ''
      );
    }
    $variable = array('data2' => $data2);
    echo json_encode($variable);
    
  }
  function fetchStaffGenderReportGraph(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
     $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $accessbranch = sessionUseraccessbranch(); 
    if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
      $record= $this->main_model->fetchStaffMarkAnalysisGraphSup($max_year);
    }else{
      $record= $this->main_model->fetchStaffMarkAnalysisGraphSupAdmin($mybranch,$max_year);
    }
    $data2 =array();
    foreach($record as $row) {
      $data2[] = array(
        'usertype' => $row["usertype"],
        'total' => $row['studentcount'],
        'male'  => $row['malecount'],
        'female' => $row['femalecount'],
        'color' =>  '#' . rand(100000, 999999) . ''
      );
    }
    $variable = array('data2' => $data2);
    echo json_encode($variable);
    
  }
}