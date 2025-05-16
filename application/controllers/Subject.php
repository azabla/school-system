<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subject extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Subject' and allowed='gradeSubject' order by id ASC "); 
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
	public function index($page='subject')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['allYear']=$this->main_model->academic_year();
    $data['schools']=$this->main_model->fetch_school();
    $data['grade']=$this->main_model->fetch_grade($max_year);
    $data['subjects']=$this->main_model->fetch_all_subject($max_year);
    $data['subj4merged']=$this->main_model->fetchAllSubject4Forged($max_year);
    $data['grades_subject']=$this->main_model->fetch_subject_grades($max_year);
    $this->load->view('home-page/'.$page,$data);
	}
  function fetchSubject4CustomPercentage(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['grade2analysis'])){
      $grade2analysis=$this->input->post('grade2analysis');
      for($i=0;$i<count($grade2analysis);$i++){
        $check=$grade2analysis[$i];
        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' and termgrade='$check' ");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
      }
      echo $this->main_model->fetchSubject4CustomPercentage($check,$max_year,$max_quarter);
    }
  }
  function updateSubjectPercentage(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('subject')){
      $value=$this->input->post('value');
      $subject=$this->input->post('subject');
      $grade=$this->input->post('grade');
      $selectCheck=$this->db->query("select * from quarter where Academic_Year='$max_year' and termgrade='$grade' ");
      if($selectCheck->num_rows()>0){
        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' and termgrade='$grade' ");
        $data=array();
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $queryCheck=$this->db->query("select * from subject_custom_percentage where academicyear='$max_year' and grade='$grade' and subject='$subject' and quarter='$max_quarter' ");
        $data=array(
          'subject'=>$subject,
          'grade'=>$grade,
          'percentage'=>$value,
          'quarter'=>$max_quarter,
          'academicyear'=>$max_year,
          'datecreated'=>date('M-d-Y')
        );
        if($queryCheck->num_rows()>0){
          $this->db->where('academicyear',$max_year);
          $this->db->where('quarter',$max_quarter);
          $this->db->where('subject',$subject);
          $this->db->where('grade',$grade);
          $this->db->set('percentage',$value);
          $query=$this->db->update('subject_custom_percentage');
        }else{
          $query1=$this->db->insert('subject_custom_percentage',$data);
        }
        /*if($query || $query1){
          echo 'Grade '.$grade.' '.$subject.' percentage has been customized successfully.';
        }else{
          echo 'Ooops Please try again.';
        }*/
        echo $this->main_model->fetchSubject4CustomPercentage($grade,$max_year,$max_quarter);
      }else{
        echo '<div class="alert alert-warning alert-dismissible show fade">
        <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
        <i class="fas fa-check-circle"> </i> Grade '.$grade.' Quarter is not defined.';
      }
    }
  }
  function deleteSubject4CustomPercentage(){
    if($this->input->post('quarter')){
      $academicYear=$this->input->post('academicYear');
      $subject=$this->input->post('subject');
      $grade=$this->input->post('grade');
      $quarter=$this->input->post('quarter');
      $this->db->where('academicyear',$academicYear);
      $this->db->where('quarter',$quarter);
      $this->db->where('subject',$subject);
      $this->db->where('grade',$grade);
      $query=$this->db->delete('subject_custom_percentage');
      echo $this->main_model->fetchSubject4CustomPercentage($grade,$academicYear,$quarter);
    }
  }
  function fetchSubject4SubSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['grade2analysis'])){
      $grade2analysis=$this->input->post('grade2analysis');
      for($i=0;$i<count($grade2analysis);$i++){
        $check=$grade2analysis[$i];
      }
      echo $this->main_model->fetchSubject4SubSubject($check,$max_year);
    }
  }
  function saveSubSubjectName(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $subSubject=trim($this->input->post('subSubjectName'));
      $grade=$this->input->post('grade');
      for($i=0;$i<count($grade);$i++){
        $checkGrade=$grade[$i];
        for($j=0;$j<count($subject);$j++){
          $check=$subject[$j];
          $check2=trim($check);
          $my_string = explode(" ", $check2);
          $last_word = end($my_string);
          echo $this->main_model->saveSubSubjectName($subSubject,$check,$checkGrade,$last_word,$max_year);
        }
      }
    }
  }
  function fetchSubSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->main_model->fetchSubSubject($max_year);
  }
  function removeSubSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('removesub')){
      $removesub=$this->input->post('removesub');
      $this->db->where('id',$removesub);
      $this->db->where('academicyear',$max_year);
      $this->db->delete('subjectlist');       
    }
  }
   function updategroupOrder(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('sasname')){
          $sasname=$this->input->post('sasname');
          $value=$this->input->post('value');
          $sasgrade=$this->input->post('sasgrade');
          $this->db->where('grade',$sasgrade);
          $this->db->where('listname',$sasname);
          $this->db->set('listorder',$value);
          $query=$this->db->update('subjectlist');
        }
    }
  function saveNewSubject(){
    $data=array();
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['subjectName'])){
      if(!empty($this->input->post('subjectName'))){
        $subjectName=trim($this->input->post('subjectName'));
        $subjectLetter=$this->input->post('subjectLetter');
        $subjectGrade=$this->input->post('subjectGrade');
        $academicYear=$this->input->post('academicYear');
        $onReportCard=1;
        $date_created=date('M-d-Y');
        for($i=0;$i<count($subjectGrade);$i++){
          $check=$subjectGrade[$i];
          $letteri=$subjectLetter[$i];
          if($letteri==''){
            $letteri='#';
          }else{
            $letteri=$letteri;
          }
          $data=array(
              'Subj_name'=>$subjectName,
              'Merged_percent'=>'100',
              'Grade'=>$check,
              'letter'=>$letteri,
              'date_created'=>$date_created,
              'Academic_Year'=>$academicYear,
              'onreportcard'=>$onReportCard
            );
          echo $query=$this->main_model->add_subject($subjectName,$check,$academicYear,$data);

        }
      }
    }
  } 
  function on_subject_status(){
    $user=$this->session->userdata('username');
    if($this->input->post('grade')){
      $grade=$this->input->post('grade');
      $subject=$this->input->post('subject');
      $academicYear=$this->input->post('academicYear');
      $this->db->where('Academic_Year',$academicYear);
      $this->db->where('Grade',$grade);
      $this->db->where('Subj_name',$subject);
      $this->db->set('student_view','1');
      $this->db->update('subject');
    }
  }
  function off_subject_status(){
    $user=$this->session->userdata('username');
    if($this->input->post('grade')){
      $grade=$this->input->post('grade');
      $subject=$this->input->post('subject');
      $academicYear=$this->input->post('academicYear');
      $this->db->where('Academic_Year',$academicYear);
      $this->db->where('Grade',$grade);
      $this->db->where('Subj_name',$subject);
      $this->db->set('student_view','0');
      $this->db->update('subject');
    }
  }
  function fetchSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('academicYear')){
      $academicYear=$this->input->post('academicYear');
      echo $this->main_model->fetchAllSubjets($academicYear);
    }
  }
  function fetchYearSubject(){
    if($this->input->post('academicYear')){
      $max_year=$this->input->post('academicYear');
      echo $this->main_model->fetchAllSubjets($max_year);
    }
  }
  function fetchSubjectToEdit(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['edtisub'])){
      $edtisub=$this->input->post('edtisub');
      $year=$this->input->post('year');
      echo $this->main_model->edit_subject($edtisub,$year);
    }
  }
  function updateSubjectName(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['oldsubjName'])){
      $date_created=date('M-d-Y');
      $oldsubjName=$this->input->post('oldsubjName');
      $newsubjName=$this->input->post('newsubjName');
      $academicYear=$this->input->post('academicYear');
      $data=array(
        'Subj_name'=>$newsubjName
      );
      $query=$this->main_model->update_subject($oldsubjName,$data,$academicYear,$newsubjName);
      if($query){
        $queryCheck=$this->db->query("select * from evaluationcustom where academicyear='$academicYear' and customsubject='$oldsubjName' ");
        if($queryCheck->num_rows()>0){
          $this->db->where('customsubject',$oldsubjName);
          $this->db->where('academicyear',$academicYear);
          $this->db->set('customsubject',$newsubjName);
          $querySubject=$this->db->update('evaluationcustom');
        }else{
          $querySubject='';
        }
        $queryPlacementCheck=$this->db->query("select * from staffplacement where academicyear='$academicYear' and subject='$oldsubjName' ");
        if($queryPlacementCheck->num_rows()>0){
          $this->db->where('subject',$oldsubjName);
          $this->db->where('academicyear',$academicYear);
          $this->db->set('subject',$newsubjName);
          $queryPlacement=$this->db->update('staffplacement');
        }
        $queryReportcardComments=$this->db->query("select * from manualreportcardcomments where academicyear='$academicYear' and subject='$oldsubjName' ");
        if($queryReportcardComments->num_rows()>0){
          $this->db->where('subject',$oldsubjName);
          $this->db->where('academicyear',$academicYear);
          $this->db->set('subject',$newsubjName);
          $queryPlacement=$this->db->update('manualreportcardcomments');
        }
      }
    }
  }
  function updateEachSubjectPercentage(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('mname')){
      $date_created=date('M-d-Y');
      $mname=$this->input->post('mname');
      $grade=$this->input->post('grade');
      $valuee=$this->input->post('valuee');
      $academicYear=$this->input->post('academicYear');
      $this->db->where('Academic_Year',$academicYear);
      $this->db->where('Subj_name',$mname);
      $this->db->where('Grade',$grade);
      $this->db->set('Merged_percent',$valuee);
      $query=$this->db->update('subject');
      if($query){
        echo '1';
      }else{
        echo '0';
      }
    }
  }
  function updateSubjectForLetter(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['gradejoss'])){
      $gradejoss=$this->input->post('gradejoss');
      $letterjoss=$this->input->post('letterjoss');
      $subjjoss=$this->input->post('subjjoss');
      $academicYear=$this->input->post('academicYear');
      $this->db->where('Grade',$gradejoss);
      $this->db->where('Subj_name',$subjjoss);
      $this->db->where('Academic_Year',$academicYear);
      $this->db->set('letter',$letterjoss);
      $query=$this->db->update('subject');
      if($query){
        $queryUsers=$this->db->query("select gradesec from users where academicyear='$academicYear' and grade='$gradejoss' and usertype='Student' group by gradesec ");
        if($queryUsers->num_rows()>0){
          foreach($queryUsers->result() as $gradeSec ){
            $gradesec=$gradeSec->gradesec;
            $queryCheckReportCard = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$academicYear."' ");
            if ($queryCheckReportCard->num_rows()>0)
            {
              $this->db->where('academicyear',$academicYear);
              $this->db->where('subject',$subjjoss);
              $this->db->where('grade',$gradesec);
              $this->db->set('letter',$letterjoss);
              $queryUpdate=$this->db->update('reportcard'.$gradesec.$academicYear);
            }
          }
        }
        echo '<span class="text-info">Saved</span>';
      }else{
        echo 'oops';
      }
    }
  }
  function subjectDelete(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $queryTemp='';
    $dataInsert=array();
    $user=$this->session->userdata('username');
    date_default_timezone_set('Africa/Addis_Ababa');
    if(isset($_POST['post_id'])){
      $subjname=$this->input->post('post_id');
      $academicYear=$this->input->post('academicYear');
      $this->db->where('Subj_name',$subjname);
      $this->db->where('Academic_Year',$academicYear);
      $this->db->group_by('Grade');
      $queryFetchMark=$this->db->get('subject');
      if($queryFetchMark->num_rows()>0){
        foreach($queryFetchMark->result() as $gradenames){
          $gradename=$gradenames->Grade;
          $queryBranch=$this->db->query("select name from branch where academicyear='$academicYear' group by name ");
          if($queryBranch->num_rows()>0){
            foreach($queryBranch->result() as $branchName){
              $branch=$branchName->name;
              $queryUsers=$this->db->query("select gradesec from users where academicyear='$academicYear' and usertype='Student' and grade='$gradename' and branch='$branch' group by gradesec ");
              if($queryUsers->num_rows()>0){
                foreach($queryUsers->result() as $gradesecName){
                  $gradesec=$gradesecName->gradesec;
                  $queryTerm=$this->db->query("select term from quarter where Academic_year='$academicYear' group by term ");
                  if($queryTerm->num_rows()>0){
                    foreach($queryTerm->result() as $termName){
                      $max_quarter=$termName->term;
                      $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branch.$gradesec.$max_quarter.$academicYear."' ");
                      if ($queryCheckMark->num_rows()>0)
                      {
                        $queryFetch=$this->db->query("SELECT *  FROM `mark".$branch.$gradesec.$max_quarter.$academicYear."` WHERE mgrade='$gradesec' and academicyear='$academicYear' and subname='$subjname' and quarter='$max_quarter' and mbranch='$branch' and lockmark='0' ");
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
                          $this->db->where('mgrade',$gradesec);
                          $this->db->where('subname',$subjname);
                          $this->db->where('academicyear',$academicYear);
                          $queryDeleteMark=$this->db->delete('mark'.$branch.$gradesec.$max_quarter.$academicYear);
                          if($queryDeleteMark){
                            $data1=array(
                              'userinfo'=>$user,
                              'useraction'=>'Subject Deleted',
                              'infograde'=>$gradesec,
                              'subject'=>$subjname,
                              'quarter'=>$max_quarter,
                              'academicyear'=>$academicYear,
                              'oldata'=>'-',
                              'newdata'=>'-',
                              'updateduser'=>'-',
                              'userbranch'=>$branch,
                              'actiondate'=> date('Y-m-d H:i:s', time())
                            );
                            $queryInsert=$this->db->insert('useractions',$data1);
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
        if(!empty($dataInsert)){
          $queryTemp=$this->db->insert_batch('trash_mark',$dataInsert);
        }
      }
      $queryDeleteSubject=$this->main_model->delete_subject($subjname,$academicYear);
    }
  }
  function onreportcard(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['onreportcard'])){
      $onreportcard=$this->input->post('onreportcard');
      $subject=$this->input->post('subject');
      $grade=$this->input->post('grade');
      $academicYear=$this->input->post('academicYear');
      $this->db->where('Subj_name',$subject);
      $this->db->where('Grade',$grade);
      $this->db->where('Academic_Year',$academicYear);
      $this->db->set('onreportcard',$onreportcard);
      $query=$this->db->update('subject');
      if($query){
        $queryUsers=$this->db->query("select gradesec from users where academicyear='$academicYear' and grade='$grade' and usertype='Student' group by gradesec ");
        if($queryUsers->num_rows()>0){
          foreach($queryUsers->result() as $gradeSec ){
            $gradesec=$gradeSec->gradesec;
            $queryCheckReportCard = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$academicYear."' ");
            if ($queryCheckReportCard->num_rows()>0)
            {
              $this->db->where('academicyear',$academicYear);
              $this->db->where('subject',$subject);
              $this->db->where('grade',$gradesec);
              $this->db->set('onreportcard',$onreportcard);
              $queryUpdate=$this->db->update('reportcard'.$gradesec.$academicYear);
            }
          }
        }
        echo '<span class="text-info">Saved </span>';
      }else{
        echo 'oops';
      }
    }
  }
  function deleteOneSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $queryTemp='';
    $dataInsert=array();
    $user=$this->session->userdata('username');
    date_default_timezone_set('Africa/Addis_Ababa');
    if(isset($_POST['gradename'])){
      $gradename=$this->input->post('gradename');
      $subjname=$this->input->post('subjname');
      $academicYear=$this->input->post('academicYear');
      $this->db->where('Grade',$gradename);
      $this->db->where('Subj_name',$subjname);
      $this->db->where('Academic_Year',$academicYear);
      $query=$this->db->delete('subject');
      if($query){
        $queryBranch=$this->db->query("select name from branch where academicyear='$academicYear' group by name ");
        if($queryBranch->num_rows()>0){
          foreach($queryBranch->result() as $branchName){
            $branch=$branchName->name;
            $queryUsers=$this->db->query("select gradesec from users where academicyear='$academicYear' and usertype='Student' and grade='$gradename' and branch='$branch' group by gradesec ");
            if($queryUsers->num_rows()>0){
              foreach($queryUsers->result() as $gradesecName){
                $gradesec=$gradesecName->gradesec;
                $queryTerm=$this->db->query("select term from quarter where Academic_year='$academicYear' group by term ");
                if($queryTerm->num_rows()>0){
                  foreach($queryTerm->result() as $termName){
                    $max_quarter=$termName->term;
                    $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branch.$gradesec.$max_quarter.$academicYear."' ");
                    if ($queryCheckMark->num_rows()>0)
                    {
                      $queryFetch=$this->db->query("SELECT *  FROM `mark".$branch.$gradesec.$max_quarter.$academicYear."` WHERE mgrade='$gradesec' and academicyear='$academicYear' and subname='$subjname' and quarter='$max_quarter' and mbranch='$branch' and lockmark='0' ");
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
                        $this->db->where('mgrade',$gradesec);
                        $this->db->where('subname',$subjname);
                        $this->db->where('academicyear',$academicYear);
                        $queryDeleteMark=$this->db->delete('mark'.$branch.$gradesec.$max_quarter.$academicYear);
                        if($queryDeleteMark){
                          $data1=array(
                            'userinfo'=>$user,
                            'useraction'=>'Subject Deleted',
                            'infograde'=>$gradesec,
                            'subject'=>$subjname,
                            'quarter'=>$max_quarter,
                            'academicyear'=>$academicYear,
                            'oldata'=>'-',
                            'newdata'=>'-',
                            'updateduser'=>'-',
                            'userbranch'=>$branch,
                            'actiondate'=> date('Y-m-d H:i:s', time())
                          );
                          $queryInsert=$this->db->insert('useractions',$data1);
                        }
                      }
                    }
                  }
                }
              }
            }
          }
          if(!empty($dataInsert)){
            $queryTemp=$this->db->insert_batch('trash_mark',$dataInsert);
          }
        }
      }else{
        echo 'oops';
      }
    }
  }
  function removeMergedSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['removesub'])){
      $removesub=$this->input->post('removesub');
      $removesub2=$this->input->post('removesub2');
      $this->db->where('Grade',$removesub);
      $this->db->where('Merged_name',$removesub2);
      $this->db->where('Academic_Year',$max_year);
      $this->db->set('Merged_name','');
      $this->db->set('Merged_percent','100');
      $this->db->set('onreportcard','1');
      $query=$this->db->update('subject');
      if($query){
        echo '<span class="text-success">Removed</span>';
      }else{
        echo 'oops, please try again';
      }
    }
  }
  function FetchMergedSubject(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->main_model->fetch_merged_subject_grades($max_year);
  }
  function UpdateMergedSubjectvalue(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if(isset($_POST['grade'])){
      $grade=$this->input->post('grade');
      $valuee=$this->input->post('valuee');
      $subjdd=$this->input->post('subjdd');
      $mname=trim($this->input->post('mname'));
      $this->db->where('Subj_name',$subjdd);
      $this->db->where('Grade',$grade);
      $this->db->where('Academic_year',$max_year);
      $this->db->set('Merged_name',$mname);
      $this->db->set('Merged_percent',$valuee);
      $query=$this->db->update('subject');
      if($query){
        echo '<span class="text-success">Saved successfully</span>';
      }else{
        echo 'oops, please try again';
      }
    }
  }
  function enable_subject_branch(){
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $data=array();
    if($this->input->post('academicyear')){
      $academicyear=$this->input->post('academicyear');
      $this->db->where('academicyear',$academicyear);
      $query = $this->db->get('subject_branch_enable');
      if($query->num_rows()>0){
        $this->db->where('academicyear',$academicyear);
        $this->db->set('enable_status','1');
        $query=$this->db->update('subject_branch_enable'); 
        if($query){
          echo '1';
        }else{
          echo '0';
        }
      }else{
        $data=array(
          'enable_status'=>'1',
          'academicyear'=>$academicyear,
          'userby'=>$user,
          'dateenabled'=>date('M-d-Y')
        );
        $query=$this->db->insert('subject_branch_enable',$data); 
        if($query){
            echo '3';
        }else{
            echo '4';
        }
      }
    }
  }
  function disable_subject_branch(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('academicyear')){
      $academicyear=$this->input->post('academicyear');
      $this->db->where('academicyear',$academicyear);
      $this->db->set('enable_status','0');
      $query=$this->db->update('subject_branch_enable');
      if($query){
        echo '1';
      } else{
        echo '0';
      }
    }
  }
  function update_subject_branch(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('subject')){
      $date_created=date('M-d-Y');
      $subject=$this->input->post('subject');
      $grade=$this->input->post('grade');
      $branch=$this->input->post('branch');
      $academicYear=$this->input->post('academicYear');
      $this->db->where('Academic_Year',$academicYear);
      $this->db->where('Subj_name',$subject);
      $this->db->where('Grade',$grade);
      $this->db->set('subject_branch',$branch);
      $query=$this->db->update('subject');
      if($query){
        echo '1';
      }else{
        echo '0';
      }
    }
  }
  function update_subject_specific(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('subject')){
      $date_created=date('M-d-Y');
      $subject=$this->input->post('subject');
      $grade=$this->input->post('grade');
      $specific=$this->input->post('specific');
      $academicYear=$this->input->post('academicYear');
      $this->db->where('Academic_Year',$academicYear);
      $this->db->where('Subj_name',$subject);
      $this->db->where('Grade',$grade);
      $this->db->set('student_specific',$specific);
      $query=$this->db->update('subject');
      if($query){
        echo '1';
      }else{
        echo '0';
      }
    }
  }
  function filterSubjectSpecificSTudent(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('grade2analysis')){
      $grade2analysis=$this->input->post('grade2analysis');
      /*for($i=0;$i<count($grade2analysis);$i++){
          $gradeGsanalysis[]=$grade2analysis[$i];
      }*/
      echo $this->main_model->filterSubjectSpecificSTudent($grade2analysis,$max_year);   
    }
  }
  function filterStudentSpecificSTudent(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('grade2analysis')){
      $grade2analysis=$this->input->post('grade2analysis');
      /*for($i=0;$i<count($grade2analysis);$i++){
          $gradeGsanalysis[]=$grade2analysis[$i];
      }*/
      echo $this->main_model->filterStudentSpecificSTudent($grade2analysis,$max_year);   
    }
  }
  function saveSubjectSpecificStudents(){
    $data=array();
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if ($this->input->post('subject')) {
      $subjectNames = $this->input->post('subject'); // Array of subjects
      $studentNames = $this->input->post('student'); // Array of students
      $subjectGrade = $this->input->post('subjectGrade'); // Single grade value (assuming it applies to all)
      foreach ($subjectNames as $subjectName) {
        foreach ($studentNames as $studentName) {
          $data=array(
              'subject_name'=>$subjectName,
              'student_id'=>$studentName,
              'student_grade'=>$subjectGrade,
              'academicyear'=>$max_year,
              'date_created'=>date('M-d-Y')
            );
          echo $query=$this->main_model->addSubjectSpecificStudents($subjectName,$subjectGrade,$max_year,$studentName,$data);

        }
      }
      
    }
  } 
  function fetchSubjectSpecificStudents(){
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->main_model->fetchSubjectSpecificStudents($max_year);
  }
  function removeSUbjectSpecificStudent(){
    if($this->input->post('userName')){
      $userName=$this->input->post('userName');
      $subject=$this->input->post('subject');
      $year=$this->input->post('year');
      $this->db->where('student_id',$userName);
      $this->db->where('subject_name',$subject);
      $this->db->where('academicyear',$year);
      $this->db->delete('subject_specific_students');       
    }
  }
}