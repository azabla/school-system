<?php
defined('BASEPATH') OR exit('No direct script access allowed');

  class Editstudentmark extends CI_Controller {
    public function __construct(){
      parent::__construct();
      $this->load->model('teacher_model');
      ob_start();
      $this->load->helper('cookie');
      $userLevel = userLevel();
      $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='addstudentmark' order by id ASC ");
      if($this->session->userdata('username') == '' || $uaddMark->num_rows() <1 || $userLevel!='2'){
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
	public function index($page='editmark')
	{
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $approvedID=$row_branch->id;
    $status2=$row_branch->status2;
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' ");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    $querychk=$this->db->query("select * from dmarkstatus where academicyear='$max_year' and dname='$status2' and dquarter='$max_quarter' ");
    $data['markstatus']=$querychk;
    $querySummerCheck=$this->db->query("select * from startsummerclass where academicyear='$max_year' and classname='summerClass' ");
    $data['summerClassMark']=$querySummerCheck;
    
    $data['checkAutoLock']=$this->teacher_model->checkAutoMarkLock($max_year,$max_quarter);
    $data['fetch_term']=$this->teacher_model->fetch_term_4teacheer($max_year);
    $data['fetch_evaluation']=$this->teacher_model->fetch_evaluation_fornewexam($max_year);
    $data['sessionuser']=$this->teacher_model->fetch_session_user($user);
    $data['academicyear']=$this->teacher_model->academic_year_filter();
    $data['fetch_grade_fromsp_toadd_neweaxm']=$this->teacher_model->fetch_grade_from_staffplace($user,$max_year);
    $data['schools']=$this->teacher_model->fetch_school();
    $data['branch']=$this->teacher_model->fetch_branch($max_year);
    $this->load->view('teacher/'.$page,$data);
	}
  function Filtersubjectfromstaff(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('gradesec')){
      $gradesec=$this->input->post('gradesec');
       $queryChk = $this->db->select('*')
                ->where('staff', $user)
                ->where('academicyear',$max_year)
                ->get('directorplacement');
      if($_SESSION['usertype']===trim('Director') && $queryChk->num_rows()>0 ){
        echo $this->teacher_model->fetch_subject_from_subject($gradesec,$max_year);
      }else{
        echo $this->teacher_model->fetch_subject_from_staffplace($gradesec,$max_year,$user);
      }
    } 
  }
  function Fecth_grademark_4teacher(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('gs_gradesec')){
      $gs_gradesec=$this->input->post('gs_gradesec');
      $gs_subject=$this->input->post('gs_subject');
      $gs_quarter=$this->input->post('gs_quarter');
      $queryFetchRemote=$this->db->query("select * from staffremoteplacement where staff='$user' and academicyear='$max_year' ");
      if($queryFetchRemote->num_rows()>0){
         if($_SESSION['usertype']===trim('Director')){
          echo $this->teacher_model->fetch_grade_mark_4director($mybranch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
        }else{
          echo $this->teacher_model->fetch_grade_mark_4teacher($mybranch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
        }
      }else{
        if($_SESSION['usertype']===trim('Director')){
          echo $this->teacher_model->fetch_grade_mark_4director($mybranch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
        }else{
          echo $this->teacher_model->fetch_grade_mark_4teacher($mybranch,$gs_gradesec,$gs_subject,$gs_quarter,$max_year); 
        }
      }
    }
  }
  function editMarkName(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      $branch=$this->input->post('branch');
      $year=$this->input->post('year');
      $markname=$this->input->post('markname');
      if($_SESSION['usertype']===trim('Director')){
        $show=$this->teacher_model->editMarkName($branch,$gradesec,$subject,$quarter,$year,$markname);
      }
      $output='<div class="row">';
      $output.='<input type="hidden" class="markgradesec" value="'.$gradesec.'">
      <input type="hidden" class="marksubject" value="'.$subject.'">
      <input type="hidden" class="markquarter" value="'.$quarter.'">
      <input type="hidden" class="markbranch" value="'.$branch.'">
      <input type="hidden" class="markyear" value="'.$year.'">';
      $output.='<div class="col-md-6">';
      foreach ($show as $keyvalue) {
        $output.='<input type="hidden" class="hidenMarkName" value="'.$keyvalue->markname.'">';
        $output.='<input type="text" class="form-control updateMarkName" value="'.$keyvalue->markname.'">';
        $this->db->where('eid',$keyvalue->evaid);
        $queryfind=$this->db->get('evaluation');
        $queryGet=$queryfind->row();
        $eName=$queryGet->evname;
        $output.='<span class="badge badge-info">Evaluation: '.$eName.'</span>';
      }
      $output.='</div><div class="col-md-6">';
      $output.='<select class="form-control" name="evaluationn" id="changeEvaluation" > ';
      $eval=$this->teacher_model->fetch_evaluation4markName($quarter,$gradesec,$max_year);
      $output.='<option></option>';
      foreach ($eval as $row) {
        $output .='<option value="'.$row->eid.'">'.$row->evname.'</option>';
      }
      $output.='</select><a class="changeEvalInfo"></a></div></div>';
      echo $output;
    }
  }
  function changeEvaluation(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('evalu')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      $year=$this->input->post('year');
      $markname=$this->input->post('markname');
      $evalu=$this->input->post('evalu');
      $branch=$this->input->post('branch');
      if($_SESSION['usertype']===trim('Director')){
        $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$branch, 'subname' => $subject,'quarter'=>$quarter,'academicyear'=>$year,'markname'=>$markname);
        $this->db->where($arrayM); 
        $this->db->set('evaid',$evalu);
        $queryChange=$this->db->update('mark'.$branch.$gradesec.$quarter.$year);
        if($queryChange){
          echo '<span class="text-success">Changed <i class="fas fa-check-circle"></i></span>';
        }
      }
    }
  }
  function updateMarkName(){
    date_default_timezone_set('Africa/Addis_Ababa');
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      $year=$this->input->post('year');
      $marknameOld=$this->input->post('oldMarkName');
      $markname=$this->input->post('markname');
      $branch=$this->input->post('branch');
      if($_SESSION['usertype']===trim('Director')){
        $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$branch, 'subname' => $subject,'quarter'=>$quarter,'academicyear'=>$year,'markname'=>$markname);
        $this->db->where($arrayM); 
        $this->db->group_by('mgrade');  
        $queryM = $this->db->get('mark'.$branch.$gradesec.$quarter.$year);
        if($queryM->num_rows() < 1){ 
          $data1=array(
            'userinfo'=>$user,
            'useraction'=>'Mark name updated',
            'infograde'=>$gradesec,
            'subject'=>$subject,
            'quarter'=>$quarter,
            'academicyear'=>$year,
            'oldata'=>$marknameOld,
            'newdata'=>$markname,
            'updateduser'=>'-',
            'userbranch'=>$branch,
            'actiondate'=> date('Y-m-d H:i:s', time())
          );
          $queryInsert=$this->db->insert('useractions',$data1);
          if($queryInsert){
            $this->db->where('mgrade',$gradesec);
            $this->db->where('mbranch',$branch);
            $this->db->where('subname',$subject);
            $this->db->where('quarter',$quarter);
            $this->db->where('academicyear',$year);
            $this->db->where('markname',$marknameOld);
            $this->db->set('markname',$markname);
            $show=$this->db->update('mark'.$branch.$gradesec.$quarter.$year);
            if($show){
              echo $markname;
            }
          }
        }
      }
    }
  }
  function lockThisSubject(){
    $user=$this->session->userdata('username');
    $query=$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      $jo_year=$this->input->post('year');
      $branch=$this->input->post('branch');
      $this->db->where('mgrade',$gradesec);
      $this->db->where('mbranch',$branch);
      $this->db->where('subname',$subject);
      $this->db->where('quarter',$quarter);
      $this->db->where('academicyear',$jo_year);
      $this->db->set('lockmark','1');
      $query=$this->db->update('mark'.$branch.$gradesec.$quarter.$jo_year);
      /*echo $this->teacher_model->fetch_grade_mark_4director($branch_me,$gradesec,$subject,$quarter,$max_year);*/
      if($_SESSION['usertype']===trim('Director')){
        echo $this->teacher_model->fetch_grade_mark_4director($branch,$gradesec,$subject,$quarter,$max_year); 
      }else{
        echo $this->teacher_model->fetch_grade_mark_4teacher($branch,$gradesec,$subject,$quarter,$max_year); 
      }
    }
  }
  function unlockThisSubject(){
    $user=$this->session->userdata('username');
    $query=$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $mybranch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $jo_year=$this->input->post('year');
      $this->db->where('mgrade',$gradesec);
      $this->db->where('mbranch',$mybranch);
      $this->db->where('subname',$subject);
      $this->db->where('quarter',$quarter);
      $this->db->where('academicyear',$jo_year);
      $this->db->set('lockmark','0');
      $query=$this->db->update('mark'.$mybranch.$gradesec.$quarter.$jo_year);
      /*echo $this->teacher_model->fetch_grade_mark_4director($mybranch,$gradesec,$subject,$quarter,$max_year);*/
      if($_SESSION['usertype']===trim('Director')){
        echo $this->teacher_model->fetch_grade_mark_4director($mybranch,$gradesec,$subject,$quarter,$max_year); 
      }else{
        echo $this->teacher_model->fetch_grade_mark_4teacher($mybranch,$gradesec,$subject,$quarter,$max_year); 
      }
    }
  }
  function fetchMarkToEdit(){
    if($this->input->post('edtim')){
      $edtim=$this->input->post('edtim');
      $quarter=$this->input->post('quarter');
      $gradesec=$this->input->post('gradesec');
      $academicyear=$this->input->post('academicyear');
      $branch=$this->input->post('branch');
      echo $this->teacher_model->select_edited_mark($edtim,$quarter,$gradesec,$academicyear,$branch);
    }
  }
  function updateMarkNow(){
    $user=$this->session->userdata('username');
    if($this->input->post('outof')){
      $outof=$this->input->post('outof');
      $mid=$this->input->post('mid');
      $value=$this->input->post('value');
      $quarter=$this->input->post('quarter');
      $gradesec=$this->input->post('gradesec');
      $year=$this->input->post('year');
      $branch=$this->input->post('branch');
      $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$year' ");
      $row2 = $query2->row();
      $max_quarter=$row2->quarter;
      if($outof>=$value){
        echo $this->teacher_model->update_edited_mark($user,$outof,$mid,$value,$quarter,$gradesec,$year,$branch,$max_quarter);
      }else{
        echo'<span class="text-danger"> Please insert correct value.</span>';
      }
    }
  }
  function FetchUpdatedMark(){
    if($this->input->post('mid')){
      $mid=$this->input->post('mid');
      $quarter=$this->input->post('quarter');
      $gradesec=$this->input->post('gradesec');
      $year=$this->input->post('year');
      $branch=$this->input->post('branch');
      echo $this->teacher_model->FetchUpdatedMark($mid,$quarter,$gradesec,$year,$branch);
    }
  }
  function deleteMarkName(){
    date_default_timezone_set('Africa/Addis_Ababa');
    $queryTemp='';
    $user=$this->session->userdata('username');
    $query=$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
    $data1=array();
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $year=$this->input->post('year');
      $markname=$this->input->post('markname');
      $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$year' ");
      $row2 = $query2->row();
      $max_quarter=$row2->quarter;
      if($_SESSION['usertype']===trim('Director')){
        $data1=array(
          'userinfo'=>$user,
          'useraction'=>'Mark Deleted',
          'infograde'=>$gradesec,
          'subject'=>$subject,
          'quarter'=>$quarter,
          'academicyear'=>$year,
          'oldata'=>$markname,
          'newdata'=>'-',
          'updateduser'=>'-',
          'userbranch'=>$branch,
          'actiondate'=> date('Y-m-d H:i:s', time())
        );
        if($quarter!==$max_quarter){
          $queryAlert=$this->db->insert('useralertactions',$data1);
        }
        $queryInsert=$this->db->insert('useractions',$data1);
        if($queryInsert){
          $dataInsert=array();
          $queryFetch=$this->db->query("SELECT *  FROM `mark".$branch.$gradesec.$quarter.$year."` WHERE mgrade='$gradesec' and academicyear='$year' and subname='$subject' and quarter='$quarter' and mbranch='$branch' and markname='$markname' and lockmark='0' ");
          if($queryFetch->num_rows()>0){
            foreach($queryFetch->result() as $row){
              $dataInsert[]=array(
                  'stuid' =>$row->stuid,
                  'mgrade' =>$row->mgrade,
                  'subname' =>$row->subname,
                  'evaid' =>$row->evaid,
                  'quarter' =>$row->quarter,
                  'outof' =>$row->outof,
                  'value' =>$row->value,
                  'academicyear' =>$row->academicyear,
                  'markname' =>$row->markname,
                  'status' =>$row->status,
                  'lockmark' =>$row->lockmark,
                  'approved' =>$row->approved,
                  'approvedby' =>$row->approvedby,
                  'zeromarkinfo' =>$row->zeromarkinfo,
                  'mbranch' =>$row->mbranch
              );
            }
            $queryTemp=$this->db->insert_batch('trash_mark',$dataInsert);
          }
          if($queryTemp){
            $this->db->where('lockmark','0');
            $this->db->where('mgrade',$gradesec);
            $this->db->where('academicyear',$year);
            $this->db->where('subname',$subject);
            $this->db->where('markname',$markname);
            $this->db->where('quarter',$quarter);
            $this->db->where('mbranch',$branch);
            $this->db->delete('mark'.$branch.$gradesec.$quarter.$year);
          }
          echo $this->teacher_model->fetch_grade_mark_4director($branch,$gradesec,$subject,$quarter,$max_year); 
        } 
      }else{
        $data1=array(
          'userinfo'=>$user,
          'useraction'=>'Mark Deleted',
          'infograde'=>$gradesec,
          'subject'=>$subject,
          'quarter'=>$quarter,
          'academicyear'=>$year,
          'oldata'=>$markname,
          'newdata'=>'-',
          'updateduser'=>'-',
          'userbranch'=>$branch,
          'actiondate'=> date('Y-m-d H:i:s', time())
        );
        if($quarter!==$max_quarter){
          $queryAlert=$this->db->insert('useralertactions',$data1);
        }
        $queryInsert=$this->db->insert('useractions',$data1);
        if($queryInsert){
          $dataInsert=array();
          $queryFetch=$this->db->query("SELECT *  FROM `mark".$branch.$gradesec.$quarter.$year."` WHERE mgrade='$gradesec' and academicyear='$year' and subname='$subject' and quarter='$quarter' and mbranch='$branch' and markname='$markname' and lockmark='0' ");
          if($queryFetch->num_rows()>0){
            foreach($queryFetch->result() as $row){
              $dataInsert[]=array(
                  'stuid' =>$row->stuid,
                  'mgrade' =>$row->mgrade,
                  'subname' =>$row->subname,
                  'evaid' =>$row->evaid,
                  'quarter' =>$row->quarter,
                  'outof' =>$row->outof,
                  'value' =>$row->value,
                  'academicyear' =>$row->academicyear,
                  'markname' =>$row->markname,
                  'status' =>$row->status,
                  'lockmark' =>$row->lockmark,
                  'approved' =>$row->approved,
                  'approvedby' =>$row->approvedby,
                  'zeromarkinfo' =>$row->zeromarkinfo,
                  'mbranch' =>$row->mbranch
              );
            }
            $queryTemp=$this->db->insert_batch('trash_mark',$dataInsert);
          }
          if($queryTemp){
            $this->db->where('lockmark','0');
            $this->db->where('approved','0');
            $this->db->where('mgrade',$gradesec);
            $this->db->where('academicyear',$year);
            $this->db->where('subname',$subject);
            $this->db->where('markname',$markname);
            $this->db->where('quarter',$quarter);
            $this->db->where('mbranch',$branch);
            $this->db->delete('mark'.$branch.$gradesec.$quarter.$year);
          }
          echo $this->teacher_model->fetch_grade_mark_4teacher($branch,$gradesec,$subject,$quarter,$max_year); 
        } 
      }
    }
  }
  function fecthNgMarkToEdit(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $stuid=$this->input->post('stuid');
      $evaid=$this->input->post('evaid');
      $quarter=$this->input->post('quarter');
      $gradesec=$this->input->post('gradesec');
      $markname=$this->input->post('markname');
      $outof=$this->input->post('outof');
      $branch=$this->input->post('branch');
      echo $this->teacher_model->select_edited_ngmark($subject,$stuid,$quarter,$max_year,$gradesec,$markname,$outof,$evaid,$branch);
    }
  }
  function updateNgMarkNow(){
    $user=$this->session->userdata('username');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('my_eva')){
      $my_eva=$this->input->post('my_eva');
      $stuid=$this->input->post('stuid');
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $quarter=$this->input->post('quarter');
      $year=$this->input->post('year');
      $val=$this->input->post('val');
      $markname=$this->input->post('markname');
      $outof=$this->input->post('outof');
      $my_studentBranch=$this->input->post('my_studentBranch');
      $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$year' ");
      $row2 = $query2->row();
      $max_quarter=$row2->quarter;
      if($outof>=$val){
        $data=array(
          'stuid'=>$stuid,
          'subname'=>$subject,
          'evaid'=>$my_eva,
          'quarter'=>$quarter,
          'value'=>$val,
          'markname'=>$markname,
          'outof'=>$outof,
          'academicyear'=>$year,
          'mbranch'=>$my_studentBranch,
          'mgrade'=>$gradesec
        );
        echo $this->teacher_model->update_edited_ngmark($user,$data,$quarter,$gradesec,$year,$my_studentBranch,$subject,$val,$stuid,$markname,$max_quarter);
      }else{
        echo '<span class="text-danger">Please insert correct mark</span>';
      }
    }
  }
  function editOutOf(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $year=$this->input->post('year');
      $markname=$this->input->post('markname');
      $outof=$this->input->post('outof');
      $show=$this->teacher_model->editMarkName($branch,$gradesec,$subject,$quarter,$year,$markname);
      $output='<div class="row">';
      $output.='<input type="hidden" class="markgradesec" value="'.$gradesec.'">
      <input type="hidden" class="marksubject" value="'.$subject.'">
      <input type="hidden" class="markquarter" value="'.$quarter.'">
      <input type="hidden" class="markbranch" value="'.$branch.'">
      <input type="hidden" class="markyear" value="'.$year.'">
      <input type="hidden" class="outofmarkname" value="'.$markname.'">';
      $output.='<div class="col-md-12">';
      foreach ($show as $keyvalue) {
        $output.='<input type="hidden" class="form-control oldOutOf" value="'.$keyvalue->outof.'">';
        $output.='<input type="text" class="form-control updateOutOf" value="'.$keyvalue->outof.'">';
      }
      $output.='<a class="changeOutInfo"></a></div></div>';
      echo $output;
    }
  }
  function updateOutOf(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $year=$this->input->post('year');
      $oldOutOf=$this->input->post('oldOutOf');
      $updateOutOf=$this->input->post('updateOutOf');
      $markname=$this->input->post('markname');
      if($_SESSION['usertype']===trim('Director')){
        $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$branch, 'subname' => $subject,'quarter'=>$quarter,'academicyear'=>$year,'markname'=>$markname,'outof'=>$oldOutOf,'value >'=>$updateOutOf);
        $this->db->where($arrayM);  
        $getCheck=$this->db->get('mark'.$branch.$gradesec.$quarter.$year);
        if($getCheck->num_rows()>0){
          echo '<span class="text-danger">Please insert correct value</span>';
        }else{
          $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$branch, 'subname' => $subject,'quarter'=>$quarter,'academicyear'=>$year,'markname'=>$markname,'outof'=>$oldOutOf);
          $this->db->where($arrayM); 
          $this->db->set('outof',$updateOutOf);
          $show=$this->db->update('mark'.$branch.$gradesec.$quarter.$year);
          if($show){
            echo $updateOutOf;
          }
        }
      }
    }
  }
  function deleteThismark(){
    $queryTemp='';
    $accessbranch = sessionUseraccessbranch();
    $user=$this->session->userdata('username');
    date_default_timezone_set('Africa/Addis_Ababa');
    $query=$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
    $data1=array();
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $jo_year=$this->input->post('year');
      $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$jo_year' ");
      $row2 = $query2->row();
      $max_quarter=$row2->quarter;
      $data1=array(
        'userinfo'=>$user,
        'useraction'=>'Mark Deleted',
        'infograde'=>$gradesec,
        'subject'=>$subject,
        'quarter'=>$quarter,
        'academicyear'=>$jo_year,
        'oldata'=>'-',
        'newdata'=>'-',
        'updateduser'=>'-',
        'userbranch'=>$branch,
        'actiondate'=> date('Y-m-d H:i:s', time())
      );
      if($quarter!==$max_quarter){
        $queryAlert=$this->db->insert('useralertactions',$data1);
      }
      $queryInsert=$this->db->insert('useractions',$data1);
      if($queryInsert){
        $dataInsert=array();
        $queryFetch=$this->db->query("SELECT *  FROM `mark".$branch.$gradesec.$quarter.$jo_year."` WHERE mgrade='$gradesec' and academicyear='$jo_year' and subname='$subject' and quarter='$quarter' and mbranch='$branch' and lockmark='0' ");
        if($queryFetch->num_rows()>0){
          foreach($queryFetch->result() as $row){
            $dataInsert[]=array(
                'stuid' =>$row->stuid,
                'mgrade' =>$row->mgrade,
                'subname' =>$row->subname,
                'evaid' =>$row->evaid,
                'quarter' =>$row->quarter,
                'outof' =>$row->outof,
                'value' =>$row->value,
                'academicyear' =>$row->academicyear,
                'markname' =>$row->markname,
                'status' =>$row->status,
                'lockmark' =>$row->lockmark,
                'approved' =>$row->approved,
                'approvedby' =>$row->approvedby,
                'zeromarkinfo' =>$row->zeromarkinfo,
                'mbranch' =>$row->mbranch
            );
          }
          $queryTemp=$this->db->insert_batch('trash_mark',$dataInsert);
        }
        if($queryTemp){
          $this->db->where('lockmark','0');
          $this->db->where('mbranch',$branch);
          $this->db->where('mgrade',$gradesec);
          $this->db->where('subname',$subject);
          $this->db->where('quarter',$quarter);
          $this->db->where('academicyear',$jo_year);
          $query=$this->db->delete('mark'.$branch.$gradesec.$quarter.$jo_year);
        }
        echo $this->teacher_model->fetch_grade_mark_4director($branch,$gradesec,$subject,$quarter,$jo_year);
      }
    }
  }
  
}