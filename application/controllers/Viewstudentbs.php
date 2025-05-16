<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewstudentbs extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='editStudentBSDATA' order by id ASC ");
    if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='viewstudentbs')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
    {
        show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $data['fetch_term']=$this->main_model->fetch_term($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['grade']=$this->main_model->fetch_grade($max_year);
    $data['schools']=$this->main_model->fetch_school();
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['enable_sub_category']=$this->main_model->enable_bs_sub_categories_status($max_year);
    $this->load->view('home-page/'.$page,$data);
	}
  function fecthStudentBs(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $fetchData= $this->main_model->fecthStudentBs($branches,$gradesec,$quarter,$max_year);
        echo json_encode($fetchData);
      }else{
        $fetchData=$this->main_model->fecthStudentBs($mybranch,$gradesec,$quarter,$max_year);
        echo json_encode($fetchData);
      }

    }
  }
  function fecthNonFilledStudentBs(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $fetchData= $this->main_model->fecthNonFilledStudentBs($branches,$gradesec,$quarter,$max_year);
        echo json_encode($fetchData);
      }else{
        $fetchData=$this->main_model->fecthNonFilledStudentBs($mybranch,$gradesec,$quarter,$max_year);
        echo json_encode($fetchData);
      }

    }
  }
  function updateStudentBs(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('bsname')){
      $bsname=$this->input->post('bsname');
      $value=$this->input->post('value');
      $stuid=$this->input->post('stuid');
      $quarter=$this->input->post('quarter');
      $bsGradesec=$this->input->post('bsGradesec');
      $branches=$this->input->post('branch');
      $data=array(
        'stuid'=>$stuid,
        'bsname'=>$bsname,
        'value'=>$value,
        'quarter'=>$quarter,
        'academicyear'=>$max_year,
        'datecreated'=>date('M-d-Y'),
        'byuser'=>$user,
        'bsgrade'=>$bsGradesec,
        'bsbranch'=>$branches
      );
      echo $this->main_model->updateStudentBs($bsGradesec,$stuid,$quarter,$bsname,$max_year,$value,$data);
    }
  }
  function fetch_user(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $fetch_data = $this->main_model->fetch_bs($branches,$gradesec,$quarter,$max_year);
      }else{
        $fetch_data = $this->main_model->fetch_bs($mybranch,$gradesec,$quarter,$max_year);
      }
      $data = array();
      foreach($fetch_data as $row)  
      {  
        $sub_array = array();  
        $sub_array[] = '<img src="'.base_url().'profile/'.$row->profile.'" class="img-thumbnail" width="50" height="35" />';  
        $sub_array[] = $row->fname;  
        $sub_array[] = $row->lname;  
        $sub_array[] = '<button type="button" name="update" id="'.$row->id.'" class="btn btn-warning btn-xs">Update</button>';  
        $sub_array[] = '<button type="button" name="delete" id="'.$row->id.'" class="btn btn-danger btn-xs">Delete</button>';  
        $data[] = $sub_array;  
      }  
      $output = array(  
        "draw"  =>intval($_POST["draw"]),  
        "recordsTotal"=>$this->main_model->get_all_data(),  
        "recordsFiltered"=>$this->main_model->get_filtered_data(),  
        "data" =>     $data  
      );  
      echo json_encode($output);
    }        
  }
  function printReportPage(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $fetchData= $this->main_model->printReportPage($branches,$gradesec,$quarter,$max_year);
        echo json_encode($fetchData);
      }else{
        $fetchData=$this->main_model->printReportPage($mybranch,$gradesec,$quarter,$max_year);
        echo json_encode($fetchData);
      }
    }
  }
  function fecthStudentBsFeed(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $fetchData= $this->main_model->fecthStudentBsFeed($branches,$gradesec,$quarter,$max_year);
        echo json_encode($fetchData);
      }else{
        $fetchData=$this->main_model->fecthStudentBsFeed($mybranch,$gradesec,$quarter,$max_year);
        echo json_encode($fetchData);
      }

    }
  }
  function fecthStudentBsFeed2(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $fetchData= $this->main_model->fecthStudentBsFeed2($branches,$gradesec,$quarter,$max_year);
        echo json_encode($fetchData);
      }else{
        $fetchData=$this->main_model->fecthStudentBsFeed2($mybranch,$gradesec,$quarter,$max_year);
        echo json_encode($fetchData);
      }

    }
  }
  function fecthOverallComment(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->post('branches')){
      $branches=$this->input->post('branches');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $fetchData= $this->main_model->fecthOverallComment($branches,$gradesec,$quarter,$max_year);
        echo json_encode($fetchData);
      }else{
        $fetchData=$this->main_model->fecthOverallComment($mybranch,$gradesec,$quarter,$max_year);
        echo json_encode($fetchData);
      }

    }
  }
  function save_teacher_comment(){
        $user=$this->session->userdata('username');
        if($this->input->post('stuid')){
            $stuid=$this->input->post('stuid');
            $commentvalue=$this->input->post('commentvalue');
            $academicyear=$this->input->post('academicyear');
            $quarter=$this->input->post('quarter');
            $markGradeSec=$this->input->post('markGradeSec');
            $markGradeSecBranch=$this->input->post('markGradeSecBranch');
            for ($i=0; $i < count($stuid); $i++) { 
              $data=array();
                $id=$stuid[$i];
                $commentvalues=$commentvalue[$i];
                $queryChk=$this->main_model->save_this_grade_teacher_ocomment($id,$academicyear,$quarter);
                $data=array(
                    'stuid'=>$id,
                    'academicyear'=>$academicyear,
                    'quarter'=>$quarter,
                    'resultcomment'=>$commentvalues,
                    'datecreated'=>date('M-d-Y'),
                    'createdby'=>$user
                );
                if($queryChk){
                    $query=$this->db->insert('overallcomments',$data);
                }else{
                    $this->db->where('stuid',$id);
                    $this->db->where('academicyear',$academicyear);
                    $this->db->where('quarter',$quarter);
                    $this->db->set('resultcomment',$commentvalues);
                    $query=$this->db->update('overallcomments',$data);
                }
            }
            if($query){
                echo '<div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    <i class="fas fa-exclamation-circle"> </i> Comment saved successfully.
                </div></div>';
            }else{
                echo '<div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    <i class="fas fa-exclamation-circle"> </i> Please Try Again.
                </div></div>';
            }
        }
    }
  function total_comment_words(){
    echo $this->main_model->fetch_total_comment_words();
  }
  function saveTotalCommentWords(){
    $data=array();
    if($this->input->post('TotalWords')){
      $total_words=$this->input->post('TotalWords');
      $query=$this->db->query("select total_words from total_comment_words ");
      $data=array(
        'total_words'=>$total_words
      );
      if($query->num_rows()>0){
        $queryUpdate=$this->db->update('total_comment_words',$data);
      }else{
        $queryUpdate=$this->db->insert('total_comment_words',$data);
      }
    }
  }
}