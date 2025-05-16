<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Addmark extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    $this->load->library('excel');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='addstudentmark' order by id ASC ");
    if($this->session->userdata('username') == '' || $uaddMark->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='add-mark')
	{
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
     show_404();
    }
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $approvedID=$row_branch->id;
    $status2=$row_branch->status2;
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' ");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    $today=date('y-m-d');
    /*if(isset($_POST['insertmark']))
    {
      if(!empty($_FILES['addmark']["tmp_name"]))
      {
        $path = $_FILES["addmark"]["tmp_name"];
        $object = PHPExcel_IOFactory::load($path);
        foreach($object->getWorksheetIterator() as $worksheet)
        {
          $highestRow = $worksheet->getHighestRow();
          $highestColumn = $worksheet->getHighestColumn();
          $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
          $subname=trim($worksheet->getCellByColumnAndRow(2,2)->getValue());
          $quarter =trim($worksheet->getCellByColumnAndRow(1,2)->getValue());
          $gradesec = trim($worksheet->getCellByColumnAndRow(1,1)->getValue());
          $mybranch = trim($worksheet->getCellByColumnAndRow(2,1)->getValue());
          if($mybranch!==''){
            if($quarter!==''){
              if($gradesec!==''){
                for($col=3;$col <= $highestColumnIndex;$col++)
                {
                  $evaid = $worksheet->getCellByColumnAndRow($col,2)->getValue();
                  $outof = $worksheet->getCellByColumnAndRow($col,3)->getValue();
                  $markname = $worksheet->getCellByColumnAndRow($col,1)->getValue();
                  $query_check=$this->main_model->check_import_markm2($markname,$subname,$quarter,$max_year,$gradesec,$mybranch);
                  if($query_check && $outof!='' && $markname!=''){
                    for($row=4; $row <= $highestRow; $row++)
                    {
                      $stuid = $worksheet->getCellByColumnAndRow(0,$row)->getValue();
                      $zeromarkinfo= $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                      if($worksheet->getCellByColumnAndRow($col,$row)!='')
                      {
                        $value1=$worksheet->getCellByColumnAndRow($col,$row)->getValue();
                        $value2=$worksheet->getCellByColumnAndRow($col,3)->getValue();
                        if($value1 > $value2 )
                        {
                          $value=0;
                        }
                        else
                        {
                          $value = $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                        }
                        $data[] = array(
                          'stuid'  => $stuid,
                          'subname'=>$subname,
                          'mgrade'=>$gradesec,
                          'evaid'=>$evaid,
                          'quarter'=>$quarter,
                          'value'=>$value,
                          'outof'=>$outof,
                          'academicyear'=>$max_year,
                          'markname'=>$markname,
                          'zeromarkinfo'=>$zeromarkinfo,
                          'approved'=>'1',
                          'approvedby'=>$approvedID,
                          'mbranch'=>$mybranch
                        );
                      }
                    }
                  }
                }
              }else{
                $this->session->set_flashdata('success','
              <div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                  <i class="fas fa-check-circle"> </i>No Grade found!.
                </div>
              </div> ');
              }
            }else{
              $this->session->set_flashdata('success','
              <div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                  <i class="fas fa-check-circle"> </i>No Quarter found!.
                </div>
              </div> ');
            }
          }else{
            $this->session->set_flashdata('success','
              <div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                  <i class="fas fa-check-circle"> </i>Either no branch found or mark exists!.
                </div>
              </div> ');
          }
        }
        if(!empty($data)){
          $queryCheckM=$this->db->query("SHOW TABLES LIKE 'mark".$mybranch.$gradesec.$quarter.$max_year."' ");
          if ($queryCheckM->num_rows()>0)
          {
            $query=$this->db->insert_batch('mark'.$mybranch.$gradesec.$quarter.$max_year,$data);
            if($query) {
              $this->session->set_flashdata('success','
              <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                  <i class="fas fa-check-circle"> </i> Data inserted successfully.
                </div>
              </div> ');
            }else{
              $this->session->set_flashdata('error','
              <div class="alert alert-wa alert-dismissible show fade">
                <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                  <i class="fas fa-check-circle"> </i> Please try Again.
                </div>
              </div> ');
            }
          }
        }else{
          $this->session->set_flashdata('error','
            <div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i> Please try again, file already exists.
              </div>
            </div> ');
        }
      }
      else{
        $this->session->set_flashdata('error','
        <div class="alert alert-warning alert-dismissible show fade">
          <div class="alert-body">
            <button class="close"  data-dismiss="alert">
              <span>&times;</span>
            </button>
            <i class="fas fa-check-circle"> </i> Please select a file to import.
          </div>
        </div> ');
      }
    }*/
    
    $querySummerCheck=$this->db->query("select * from startsummerclass where academicyear='$max_year' and classname='summerClass' ");
    $data['summerClassMark']=$querySummerCheck;

    $querychk=$this->db->query("select * from dmarkstatus where academicyear='$max_year' and dname='$status2' and dquarter='$max_quarter' ");
    $data['markstatus']=$querychk;
    $data['checkAutoLock']=$this->main_model->checkAutoMarkLock($max_year,$max_quarter);
    $data['fetch_maxTerm']=$this->main_model->fetch_term_4teacheer($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year();
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['schools']=$this->main_model->fetch_school();
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['fetch_term']=$this->main_model->fetch_term($max_year);
    $this->load->view('home-page/'.$page,$data);
	}
  function Filter_grade_from_branch(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    if($this->input->post('branch')){
        $branch=$this->input->post('branch');
        $academicyear=$this->input->post('academicyear');
        echo $this->main_model->fetch_grade_from_branch($branch,$academicyear); 
    }
  }
  function Fetch_subject_from_subject4MardEdit(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    if($this->input->post('gradesec')){
      $academicyear=$this->input->post('academicyear');
      $gradesec=$this->input->post('gradesec');
      echo $this->main_model->fetch_subject_from_subject4MardEdit($gradesec,$academicyear); 
    }
  }
  function fetchQuarterFromAcademicYear(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    if($this->input->post('academicyear')){
      $academicyear=$this->input->post('academicyear');
      echo $this->main_model->fetchQuarterFromAcademicYear($academicyear); 
    }
  }
  function fetchGradeMark(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->get('gs_gradesec')){
      $gs_branches=$this->input->get('gs_branches');
      $gs_gradesec=$this->input->get('gs_gradesec');
      $gs_subject=$this->input->get('gs_subject');
      $gs_quarter=$this->input->get('gs_quarter');
      $academicyear=$this->input->get('academicyear');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $show=$this->main_model->fetch_grade_mark($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$academicyear);
        echo json_encode($show);
      }else{
        $show=$this->main_model->fetch_grade_mark($branch,$gs_gradesec,$gs_subject,$gs_quarter,$academicyear);
        echo json_encode($show);
      } 
    }
  }
  function fetchCustomSubjectMark(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $accessbranch = sessionUseraccessbranch();
    if($this->input->get('gs_gradesec')){
      $gs_branches=$this->input->get('gs_branches');
      $gs_gradesec=$this->input->get('gs_gradesec');
      $gs_quarter=$this->input->get('gs_quarter');
      $academicyear=$this->input->get('academicyear');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $show=$this->main_model->fetch_custom_grade_mark($gs_branches,$gs_gradesec,$gs_quarter,$academicyear);
        echo json_encode($show);
      }else{
        $show=$this->main_model->fetch_custom_grade_mark($branch,$gs_gradesec,$gs_quarter,$academicyear);
        echo json_encode($show);
      } 
    }
  }
  function editMarkName(){
    $accessbranch = sessionUseraccessbranch();
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
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $show=$this->main_model->editMarkName($branch,$gradesec,$subject,$quarter,$year,$markname);
      }else{
        $show=$this->main_model->editMarkName($mybranch,$gradesec,$subject,$quarter,$year,$markname);
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
      $eval=$this->main_model->fetch_evaluation4markName($quarter,$gradesec,$year);
      $output.='<option></option>';
      foreach ($eval as $row) {
        $output .='<option value="'.$row->eid.'">'.$row->evname.'</option>';
      }
      $output.='</select><a class="changeEvalInfo"></a></div></div>';
      echo $output;
    }
  }
  function editOutOf(){
    $accessbranch = sessionUseraccessbranch();
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
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $show=$this->main_model->editMarkName($branch,$gradesec,$subject,$quarter,$year,$markname);
      }else{
        $show=$this->main_model->editMarkName($mybranch,$gradesec,$subject,$quarter,$year,$markname);
      }
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
    $accessbranch = sessionUseraccessbranch();
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    date_default_timezone_set('Africa/Addis_Ababa');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $data1=array();
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $year=$this->input->post('year');
      $oldOutOf=$this->input->post('oldOutOf');
      $updateOutOf=$this->input->post('updateOutOf');
      $markname=$this->input->post('markname');
      $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$year' ");
      $row2 = $query2->row();
      $max_quarter=$row2->quarter;
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$branch, 'subname' => $subject,'quarter'=>$quarter,'academicyear'=>$year,'markname'=>$markname,'outof'=>$oldOutOf,'value >'=>$updateOutOf);
        $this->db->where($arrayM);  
        $getCheck=$this->db->get('mark'.$branch.$gradesec.$quarter.$year);
        if($getCheck->num_rows()>0){
          echo '<span class="text-danger">Please insert correct value</span>';
        }else{
          $data1=array(
            'userinfo'=>$user,
            'useraction'=>'Mark Percentage updated',
            'infograde'=>$gradesec,
            'subject'=>$subject,
            'quarter'=>$quarter,
            'academicyear'=>$year,
            'oldata'=>$oldOutOf,
            'newdata'=>$updateOutOf,
            'updateduser'=>'-',
            'userbranch'=>$branch,
            'actiondate'=> date('Y-m-d H:i:s', time())
          );
          $queryInsert=$this->db->insert('useractions',$data1);
          if($queryInsert){
            $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$branch, 'subname' => $subject,'quarter'=>$quarter,'academicyear'=>$year,'markname'=>$markname,'outof'=>$oldOutOf);
            $this->db->where($arrayM); 
            $this->db->set('outof',$updateOutOf);
            $show=$this->db->update('mark'.$branch.$gradesec.$quarter.$year);
            if($show){
              echo $updateOutOf;
            }
          }
          if($quarter!==$max_quarter){
            $queryInsert=$this->db->insert('useralertactions',$data1);
          }
        }
      }
    }
  }
  function changeEvaluation(){
    $accessbranch = sessionUseraccessbranch();
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
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $year=$this->input->post('year');
      $markname=$this->input->post('markname');
      $evalu=$this->input->post('evalu');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$branch, 'subname' => $subject,'quarter'=>$quarter,'academicyear'=>$year,'markname'=>$markname);
        $this->db->where($arrayM); 
        $this->db->set('evaid',$evalu);
        $queryChange=$this->db->update('mark'.$branch.$gradesec.$quarter.$year);
        if($queryChange){
          echo '<span class="text-success">Changed <i class="fas fa-check-circle"></i></span>';
        }
      }else{
        $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$mybranch, 'subname' => $subject,'quarter'=>$quarter,'academicyear'=>$year,'markname'=>$markname);
        $this->db->where($arrayM); 
        $this->db->set('evaid',$evalu);
        $queryChange=$this->db->update('mark'.$mybranch.$gradesec.$quarter.$year);
        if($queryChange){
           echo '<span class="text-success">Changed <i class="fas fa-check-circle"></i></span>';
        }
      }
    }
  }
  function updateMarkName(){
    $accessbranch = sessionUseraccessbranch();
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    date_default_timezone_set('Africa/Addis_Ababa');
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;
    $data1=array();
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $year=$this->input->post('year');
      $marknameOld=$this->input->post('oldMarkName');
      $markname=$this->input->post('markname');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
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
      }else{
        $arrayM = array('mgrade' => $gradesec, 'mbranch' =>$mybranch, 'subname' => $subject,'quarter'=>$quarter,'academicyear'=>$year,'markname'=>$markname);
        $this->db->where($arrayM); 
        $this->db->group_by('mgrade');  
        $queryM = $this->db->get('mark'.$mybranch.$gradesec.$quarter.$year);
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
            $this->db->where('mbranch',$mybranch);
            $this->db->where('subname',$subject);
            $this->db->where('quarter',$quarter);
            $this->db->where('academicyear',$year);
            $this->db->where('markname',$marknameOld);
            $this->db->set('markname',$markname);
            $show=$this->db->update('mark'.$mybranch.$gradesec.$quarter.$year);
            if($show){
              echo $markname;
            }
          }
        }
      }
    }
  }
  function lockThisStudentMark(){
    $accessbranch = sessionUseraccessbranch();
    $user=$this->session->userdata('username');
    $query=$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $stuid=$this->input->post('stuid');
      $quarter=$this->input->post('quarter');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $jo_year=$this->input->post('year');
      $queryMark=$this->db->query("select * from mark".$branch.$gradesec.$quarter.$jo_year." where stuid='$stuid' and academicyear='$jo_year' and quarter='$quarter' and subname='$subject' ");
      $lockALl=$queryMark->row();
      $lockmark=$lockALl->lockmark;
      if($lockmark==='0'){
        $this->db->where('subname',$subject);
        $this->db->where('stuid',$stuid);
        $this->db->where('quarter',$quarter);
        $this->db->where('academicyear',$jo_year);
        $this->db->set('lockmark','1');
        $query=$this->db->update('mark'.$branch.$gradesec.$quarter.$jo_year);
      }else{
        $this->db->where('subname',$subject);
        $this->db->where('stuid',$stuid);
        $this->db->where('quarter',$quarter);
        $this->db->where('academicyear',$jo_year);
        $this->db->set('lockmark','0');
        $query=$this->db->update('mark'.$branch.$gradesec.$quarter.$jo_year);
      }
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->fetch_grade_mark($branch,$gradesec,$subject,$quarter,$jo_year);
      }else{
        echo $this->main_model->fetch_grade_mark($branch_me,$gradesec,$subject,$quarter,$jo_year);
      }
    }
  }
  function lockThisMark(){
    $accessbranch = sessionUseraccessbranch();
    $user=$this->session->userdata('username');
    $query=$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $mybranch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $jo_year=$this->input->post('year');
      $queryMark=$this->db->query("select * from mark".$mybranch.$gradesec.$quarter.$jo_year." where mbranch='$mybranch' and academicyear='$jo_year' and mgrade='$gradesec' and subname='$subject' ");
      $lockALl=$queryMark->row();
      $lockmark=$lockALl->lockmark;
      if($lockmark==='0'){
        $this->db->where('mgrade',$gradesec);
        $this->db->where('mbranch',$mybranch);
        $this->db->where('subname',$subject);
        $this->db->where('quarter',$quarter);
        $this->db->where('academicyear',$jo_year);
        $this->db->set('lockmark','1');
        $query=$this->db->update('mark'.$mybranch.$gradesec.$quarter.$jo_year);
      }else{
        $this->db->where('mgrade',$gradesec);
        $this->db->where('mbranch',$mybranch);
        $this->db->where('subname',$subject);
        $this->db->where('quarter',$quarter);
        $this->db->where('academicyear',$jo_year);
        $this->db->set('lockmark','0');
        $query=$this->db->update('mark'.$mybranch.$gradesec.$quarter.$jo_year);
      }
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->fetch_grade_mark($mybranch,$gradesec,$subject,$quarter,$jo_year);
      }else{
        echo $this->main_model->fetch_grade_mark($branch_me,$gradesec,$subject,$quarter,$jo_year);
      }
    }
  }
  function lockThisSubject(){
    $accessbranch = sessionUseraccessbranch();
    $user=$this->session->userdata('username');
    $query=$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $jo_year=$this->input->post('year');
      $queryMark=$this->db->query("select * from mark".$branch.$gradesec.$quarter.$jo_year." where mbranch='$branch' and academicyear='$jo_year' and mgrade='$gradesec' and quarter='$quarter' ");
      $lockALl=$queryMark->row();
      $lockmark=$lockALl->lockmark;
      if($lockmark==='0'){
        $this->db->where('mgrade',$gradesec);
        $this->db->where('mbranch',$branch);
        $this->db->where('quarter',$quarter);
        $this->db->where('academicyear',$jo_year);
        $this->db->set('lockmark','1');
        $query=$this->db->update('mark'.$branch.$gradesec.$quarter.$jo_year);
      }else{
        $this->db->where('mgrade',$gradesec);
        $this->db->where('mbranch',$branch);
        $this->db->where('quarter',$quarter);
        $this->db->where('academicyear',$jo_year);
        $this->db->set('lockmark','0');
        $query=$this->db->update('mark'.$branch.$gradesec.$quarter.$jo_year);
      }
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->fetch_grade_mark($branch,$gradesec,$subject,$quarter,$jo_year);
      }else{
        echo $this->main_model->fetch_grade_mark($branch_me,$gradesec,$subject,$quarter,$jo_year);
      }
    }
  }
  function deleteMarkName(){
    $accessbranch = sessionUseraccessbranch();
    $user=$this->session->userdata('username');
    date_default_timezone_set('Africa/Addis_Ababa');
    $query=$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
    $dataInsert=array();
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
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1')
      {
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
          }
          echo $this->main_model->fetch_grade_mark($branch,$gradesec,$subject,$quarter,$year);
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
          'userbranch'=>$branch_me,
          'actiondate'=> date('Y-m-d H:i:s', time())
        );
        if($quarter!==$max_quarter){
          $queryAlert=$this->db->insert('useralertactions',$data1);
        }
        $queryInsert=$this->db->insert('useractions',$data1);
        if($queryInsert){
          $queryFetch=$this->db->query("SELECT *  FROM `mark".$branch_me.$gradesec.$quarter.$year."` WHERE mgrade='$gradesec' and academicyear='$year' and subname='$subject' and quarter='$quarter' and mbranch='$branch_me' and markname='$markname' and lockmark='0' ");
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
            $this->db->where('mbranch',$branch_me);
            $this->db->delete('mark'.$branch_me.$gradesec.$quarter.$year);
          }
          echo $this->main_model->fetch_grade_mark($branch_me,$gradesec,$subject,$quarter,$year);
        }
      } 
    }
  } 
  function deleteThismark(){
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
    $dataInsert=array();
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $jo_year=$this->input->post('year');
      $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$jo_year' ");
      $row2 = $query2->row();
      $max_quarter=$row2->quarter;
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
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
            $dataInsert=array();
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
          echo $this->main_model->fetch_grade_mark($branch,$gradesec,$subject,$quarter,$jo_year);
        }
      }else{
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
          'userbranch'=>$branch_me,
          'actiondate'=> date('Y-m-d H:i:s', time())
        );
        if($quarter!==$max_quarter){
          $queryAlert=$this->db->insert('useralertactions',$data1);
        }
        $queryInsert=$this->db->insert('useractions',$data1);
        if($queryInsert){
          $dataInsert=array();
          $queryFetch=$this->db->query("SELECT *  FROM `mark".$branch_me.$gradesec.$quarter.$jo_year."` WHERE mgrade='$gradesec' and academicyear='$jo_year' and subname='$subject' and quarter='$quarter' and mbranch='$branch_me' and lockmark='0' ");
          if($queryFetch->num_rows()>0){
            $dataInsert=array();
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
            $this->db->where('mbranch',$branch_me);
            $this->db->where('mgrade',$gradesec);
            $this->db->where('subname',$subject);
            $this->db->where('quarter',$quarter);
            $this->db->where('academicyear',$jo_year);
            $query=$this->db->delete('mark'.$branch_me.$gradesec.$quarter.$jo_year);
          }
          echo $this->main_model->fetch_grade_mark($branch_me,$gradesec,$subject,$quarter,$jo_year);
        }
      }
    }
  }
  function deleteThisCustomSubjectMark(){
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
    $dataInsert=array();
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $jo_year=$this->input->post('year');
      $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$jo_year' ");
      $row2 = $query2->row();
      $max_quarter=$row2->quarter;
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
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
            $dataInsert=array();
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
            $this->db->where('mbranch',$branch);
            $this->db->where('mgrade',$gradesec);
            $this->db->where('subname',$subject);
            $this->db->where('quarter',$quarter);
            $this->db->where('academicyear',$jo_year);
            $query=$this->db->delete('mark'.$branch.$gradesec.$quarter.$jo_year);
          }
          echo $this->main_model->fetch_custom_grade_mark($branch,$gradesec,$quarter,$jo_year);
        }
      }else{
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
          'userbranch'=>$branch_me,
          'actiondate'=> date('Y-m-d H:i:s', time())
        );
        if($quarter!==$max_quarter){
          $queryAlert=$this->db->insert('useralertactions',$data1);
        }
        $queryInsert=$this->db->insert('useractions',$data1);
        if($queryInsert){

          $dataInsert=array();
          $queryFetch=$this->db->query("SELECT *  FROM `mark".$branch_me.$gradesec.$quarter.$jo_year."` WHERE mgrade='$gradesec' and academicyear='$jo_year' and subname='$subject' and quarter='$quarter' and mbranch='$branch_me' and lockmark='0' ");
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
            $this->db->where('mbranch',$branch_me);
            $this->db->where('mgrade',$gradesec);
            $this->db->where('subname',$subject);
            $this->db->where('quarter',$quarter);
            $this->db->where('academicyear',$jo_year);
            $query=$this->db->delete('mark'.$branch_me.$gradesec.$quarter.$jo_year);
          }
          echo $this->main_model->fetch_custom_grade_mark($branch_me,$gradesec,$quarter,$jo_year);
        }
      }
    }
  }
  function deleteThisGradeMark(){
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
    $dataInsert=array();
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $branch=$this->input->post('branch');
      $quarter=$this->input->post('quarter');
      $jo_year=$this->input->post('year');
      $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$jo_year' ");
      $row2 = $query2->row();
      $max_quarter=$row2->quarter;
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
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
            $this->db->where('quarter',$quarter);
            $this->db->where('academicyear',$jo_year);
            $query=$this->db->delete('mark'.$branch.$gradesec.$quarter.$jo_year);
          }
        }
      }else{
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
          'userbranch'=>$branch_me,
          'actiondate'=> date('Y-m-d H:i:s', time())
        );
        if($quarter!==$max_quarter){
          $queryAlert=$this->db->insert('useralertactions',$data1);
        }
        $queryInsert=$this->db->insert('useractions',$data1);
        if($queryInsert){
          $dataInsert=array();
          $queryFetch=$this->db->query("SELECT *  FROM `mark".$branch_me.$gradesec.$quarter.$jo_year."` WHERE mgrade='$gradesec' and academicyear='$jo_year' and subname='$subject' and quarter='$quarter' and mbranch='$branch_me' and lockmark='0' ");
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
            $this->db->where('quarter',$quarter);
            $this->db->where('academicyear',$jo_year);
            $query=$this->db->delete('mark'.$branch_me.$gradesec.$quarter.$jo_year);
          }
        }
      }
      exit;
    }
  }
  function fetchMarkToEdit(){
    $accessbranch = sessionUseraccessbranch();
    $user=$this->session->userdata('username');
    $query=$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
    if($this->input->post('edtim')){
      $edtim=$this->input->post('edtim');
      $quarter=$this->input->post('quarter');
      $gradesec=$this->input->post('gradesec');
      $academicyear=$this->input->post('academicyear');
      $branch=$this->input->post('branch');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->select_edited_mark($edtim,$quarter,$gradesec,$academicyear,$branch);
      }else{
        echo $this->main_model->select_edited_mark($edtim,$quarter,$gradesec,$academicyear,$branch_me);
      }
    }
  }
  function fecthNgMarkToEdit(){
    $accessbranch = sessionUseraccessbranch();
    $user=$this->session->userdata('username');
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
    if($this->input->post('subject')){
      $subject=$this->input->post('subject');
      $stuid=$this->input->post('stuid');
      $evaid=$this->input->post('evaid');
      $quarter=$this->input->post('quarter');
      $gradesec=$this->input->post('gradesec');
      $markname=$this->input->post('markname');
      $outof=$this->input->post('outof');
      $branch=$this->input->post('branch');
      $academicyear=$this->input->post('academicyear');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->select_edited_ngmark($subject,$stuid,$quarter,$academicyear,$gradesec,$markname,$outof,$evaid,$branch);
      }else{
        echo $this->main_model->select_edited_ngmark($subject,$stuid,$quarter,$academicyear,$gradesec,$markname,$outof,$evaid,$branch_me);
      }
    }
  }
  function updateNgMarkNow(){
    $accessbranch = sessionUseraccessbranch();
    $user=$this->session->userdata('username');
    $query_branch=$this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
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
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          echo $this->main_model->update_edited_ngmark($user,$data,$quarter,$gradesec,$year,$my_studentBranch,$val,$subject,$stuid,$markname,$max_quarter);
        }else{
          echo $this->main_model->update_edited_ngmark($user,$data,$quarter,$gradesec,$year,$branch_me,$val,$subject,$stuid,$markname,$max_quarter);
        }
      }else{
        echo '<span class="text-danger">Please insert correct mark</span>';
      }
    }
  }
  function updateMarkNow(){
    $accessbranch = sessionUseraccessbranch();
    $user=$this->session->userdata('username');
    $query_branch=$this->db->query("select branch from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
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
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        if($outof>=$value){
          echo $this->main_model->update_edited_mark($user,$outof,$mid,$value,$quarter,$gradesec,$year,$branch,$max_quarter);
        }else{
          echo'<span class="text-danger"> Please insert correct value.</span>';
        }
      }else{
        if($outof>=$value){
          echo $this->main_model->update_edited_mark($user,$outof,$mid,$value,$quarter,$gradesec,$year,$branch_me,$max_quarter);
        }else{
          echo'<span class="text-danger"> Please insert correct value.</span>';
        }
      }
    }
  }
  function FetchUpdatedMark(){
    $accessbranch = sessionUseraccessbranch();
    $user=$this->session->userdata('username');
    $query_branch=$this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch_me=$row_branch->branch;
    if($this->input->post('mid')){
      $mid=$this->input->post('mid');
      $quarter=$this->input->post('quarter');
      $gradesec=$this->input->post('gradesec');
      $year=$this->input->post('year');
      $branch=$this->input->post('branch');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
        echo $this->main_model->FetchUpdatedMark($mid,$quarter,$gradesec,$year,$branch);
      }else{
        echo $this->main_model->FetchUpdatedMark($mid,$quarter,$gradesec,$year,$branch_me);
      }
    }
  }
  function fetchOutOffToEdit(){
    $user=$this->session->userdata('username');
    if($this->input->post('markanme')){
      $markanme=$this->input->post('markanme');
      $quarter=$this->input->post('quarter');
      $subject=$this->input->post('subject');
      $gradesec=$this->input->post('gradesec');
      $year=$this->input->post('year');
      echo $this->main_model->select_edited_outof($markanme,$quarter,$subject,$gradesec,$year);
    }
  }
  function importCustomStudentMark(){
    $query=$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $user=$this->session->userdata('username');
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' ");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    $query_branch = $this->db->query("select branch,id,status2 from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $approvedID=$row_branch->id;
    $status2=$row_branch->status2;
    if(isset($_FILES["addcustommark"]["name"]))
    {
      $path = $_FILES["addcustommark"]["tmp_name"];
      $object = PHPExcel_IOFactory::load($path);
      foreach($object->getWorksheetIterator() as $worksheet)
      {
        $data=array();
        $data1=array();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $subname=trim($worksheet->getCellByColumnAndRow(2,2)->getValue());
        $quarter =trim($worksheet->getCellByColumnAndRow(1,2)->getValue());
        $gradesec = trim($worksheet->getCellByColumnAndRow(1,1)->getValue());
        $mybranch = trim($worksheet->getCellByColumnAndRow(2,1)->getValue());
        if($mybranch!==''){
          if($quarter!==''){
            if($gradesec!==''){
              for($col=3;$col <= $highestColumnIndex;$col++)
              {
                $evaid = $worksheet->getCellByColumnAndRow($col,2)->getValue();
                $outof = $worksheet->getCellByColumnAndRow($col,3)->getValue();
                $markname = $worksheet->getCellByColumnAndRow($col,1)->getValue();
                if($outof!='' && $markname!=''){
                  for($row=4; $row <= $highestRow; $row++)
                  {
                    $stuid = $worksheet->getCellByColumnAndRow(0,$row)->getValue();
                    $query_check=$this->main_model->check_import_custom($markname,$subname,$quarter,$max_year,$gradesec,$mybranch,$stuid);
                    if($query_check){
                      $zeromarkinfo= $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                      if($worksheet->getCellByColumnAndRow($col,$row)!='')
                      {
                        $value1=$worksheet->getCellByColumnAndRow($col,$row)->getValue();
                        $value2=$worksheet->getCellByColumnAndRow($col,3)->getValue();
                        if($value1 > $value2 )
                        {
                          $value=0;
                        }
                        else
                        {
                          $value = $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                        }
                        $data[] = array(
                          'stuid'  => $stuid,
                          'subname'=>$subname,
                          'mgrade'=>$gradesec,
                          'evaid'=>$evaid,
                          'quarter'=>$quarter,
                          'value'=>$value,
                          'outof'=>$outof,
                          'academicyear'=>$max_year,
                          'markname'=>$markname,
                          'zeromarkinfo'=>$zeromarkinfo,
                          'approved'=>'1',
                          'approvedby'=>$approvedID,
                          'mbranch'=>$mybranch
                        );
                        $data1=array(
                          'userinfo'=>$user,
                          'useraction'=>'Excel Mark Inserted',
                          'infograde'=>$gradesec,
                          'subject'=>$subname,
                          'quarter'=>$quarter,
                          'academicyear'=>$max_year,
                          'oldata'=>'-',
                          'newdata'=>'-',
                          'updateduser'=>'-',
                          'userbranch'=>$mybranch,
                          'actiondate'=> date('Y-m-d H:i:s', time())
                        );
                      }
                    }
                  }
                }
              }
            }else{
              echo '
            <div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i>No Grade found!.
              </div>
            </div> ';
            }
          }else{
            echo '
            <div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i>No Quarter found!.
              </div>
            </div> ';
          }
        }else{
          echo '
            <div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i>Either no branch found or mark exists!.
              </div>
            </div> ';
        }
      }
      if(!empty($data)){
        $queryCheckM=$this->db->query("SHOW TABLES LIKE 'mark".$mybranch.$gradesec.$quarter.$max_year."' ");
        if ($queryCheckM->num_rows()>0)
        {
          $query=$this->db->insert_batch('mark'.$mybranch.$gradesec.$quarter.$max_year,$data);
          if($query) {
            $queryInsert=$this->db->insert('useractions',$data1);
            if($quarter!==$max_quarter){
              $queryAlert=$this->db->insert('useralertactions',$data1);
            }
            echo '
            <div class="alert alert-success alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i> Data inserted successfully.
              </div>
            </div> ';
          }else{
           echo '
            <div class="alert alert-wa alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i> Please try Again.
              </div>
            </div> ';
          }
        }
      }else{
        echo '
          <div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
              <button class="close"  data-dismiss="alert">
                <span>&times;</span>
              </button>
              <i class="fas fa-check-circle"> </i> Please try again, file already exists.
            </div>
          </div> ';
      }
      
    }
  }
  function importDefaultStudentMark(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select branch,id,status2 from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $approvedID=$row_branch->id;
    $status2=$row_branch->status2;
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' ");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    $today=date('y-m-d');
    echo '<div class="row">';
    if($_FILES['addmark']['name'] != ''){
    /*if(isset($_FILES["addmark"]["name"]))
    {*/
      $fileName = $_FILES['addmark']['name'];
      
      $path = $_FILES["addmark"]["tmp_name"];
      $object = PHPExcel_IOFactory::load($path);
      $info = pathinfo($fileName);
      $allow_file = array("xls");
      if(in_array($info['extension'],$allow_file)){
        foreach($object->getWorksheetIterator() as $worksheet)
        {
          $data=array();
          $data1=array();
          $highestRow = $worksheet->getHighestRow();
          $highestColumn = $worksheet->getHighestColumn();
          $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
          $subname=trim($worksheet->getCellByColumnAndRow(2,2)->getValue() ?? '');
          $quarter=trim($worksheet->getCellByColumnAndRow(1,2)->getValue() ?? '');
          $gradesec=trim($worksheet->getCellByColumnAndRow(1,1)->getValue() ?? '');
          $mybranch=trim($worksheet->getCellByColumnAndRow(2,1)->getValue() ?? '');
          for($col=3;$col <= $highestColumnIndex;$col++)
          {
            $evaid = $worksheet->getCellByColumnAndRow($col,2)->getValue();
            $outof = $worksheet->getCellByColumnAndRow($col,3)->getValue();
            $markname = $worksheet->getCellByColumnAndRow($col,1)->getValue();
            $query_check=$this->main_model->check_import_markm2($markname,$subname,$quarter,$max_year,$gradesec,$mybranch);
            if($query_check && $outof!='' && $markname!=''){
              for($row=4; $row <= $highestRow; $row++)
              {
                $stuid = $worksheet->getCellByColumnAndRow(0,$row)->getValue();
                $zeromarkinfo= $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                if($worksheet->getCellByColumnAndRow($col,$row)!='')
                {
                  $value1=$worksheet->getCellByColumnAndRow($col,$row)->getValue();
                  $value2=$worksheet->getCellByColumnAndRow($col,3)->getValue();
                  if($value1 > $value2 )
                  {
                    $value=0;
                  }
                  else
                  {
                    $value = $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                  }
                  $data[] = array(
                    'stuid'  => $stuid,
                    'subname'=>$subname,
                    'mgrade'=>$gradesec,
                    'evaid'=>$evaid,
                    'quarter'=>$quarter,
                    'value'=>$value,
                    'outof'=>$outof,
                    'academicyear'=>$max_year,
                    'markname'=>$markname,
                    'zeromarkinfo'=>$zeromarkinfo,
                    'approved'=>'1',
                    'approvedby'=>$approvedID,
                    'mbranch'=>$mybranch
                  );
                  $data1=array(
                    'userinfo'=>$user,
                    'useraction'=>'Excel Mark Inserted',
                    'infograde'=>$gradesec,
                    'subject'=>$subname,
                    'quarter'=>$quarter,
                    'academicyear'=>$max_year,
                    'oldata'=>'-',
                    'newdata'=>'-',
                    'updateduser'=>'-',
                    'userbranch'=>$mybranch,
                    'actiondate'=> date('Y-m-d H:i:s', time())
                  );
                }
              }
            }
          }
          if(!empty($data)){
            $queryCheckM=$this->db->query("SHOW TABLES LIKE 'mark".$mybranch.$gradesec.$quarter.$max_year."' ");
            if ($queryCheckM->num_rows()>0)
            {
              $query=$this->db->insert_batch('mark'.$mybranch.$gradesec.$quarter.$max_year,$data);
              if($query) {
                $queryInsert=$this->db->insert('useractions',$data1);
                if($quarter!==$max_quarter){
                  $queryAlert=$this->db->insert('useralertactions',$data1);
                }
                echo '<div class="col-md-6 col-6">
                <div class="alert alert-success alert-dismissible show fade">
                  <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                    <i class="fas fa-check-circle"> </i> Data inserted successfully for subject '.$subname.'.
                  </div>
                </div></div>';
              }else{
                echo '
                <div class="alert alert-wa alert-dismissible show fade">
                  <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                    <i class="fas fa-times"> </i> Please try Again.
                  </div>
                </div> ';
              }
            }
          }else{
           echo' <div class="col-md-6 col-6">
              <div class="alert alert-light alert-dismissible show fade">
                <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                  <i class="fas fa-times"> </i> Please try again,Either file exists or something wrong with your excel subject '.$subname.'.
                </div>
              </div></div>';
          }
        }
      }else{
         echo'
            <div class="alert alert-light alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i> Please select only xls file.
              </div>
            </div> ';
      }
    }
    echo '</div>';
  }
  function importDefaultStudentMarkTranscript(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select branch,id,status2 from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $approvedID=$row_branch->id;
    $status2=$row_branch->status2;
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $today=date('y-m-d');
    echo '<div class="row">';
    if($_FILES['addmark']['name'] != ''){
      $fileName = $_FILES['addmark']['name'];
      
      $path = $_FILES["addmark"]["tmp_name"];
      $object = PHPExcel_IOFactory::load($path);
      $info = pathinfo($fileName);
      $allow_file = array("xls");
      if(in_array($info['extension'],$allow_file)){
        foreach($object->getWorksheetIterator() as $worksheet)
        {
          $data=array();
          $highestRow = $worksheet->getHighestRow();
          $highestColumn = $worksheet->getHighestColumn();
          $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
          
          $quarter=trim($worksheet->getCellByColumnAndRow(1,2)->getValue());
          $YearName=trim($worksheet->getCellByColumnAndRow(0,1)->getValue());
          $gradesec=trim($worksheet->getCellByColumnAndRow(1,1)->getValue());
          $mybranch=trim($worksheet->getCellByColumnAndRow(2,1)->getValue());
          for($col=3;$col <= $highestColumnIndex;$col++)
          {
            $subname=trim($worksheet->getCellByColumnAndRow($col,1)->getValue());
            $evaid = $worksheet->getCellByColumnAndRow($col,2)->getValue();
            $outof = $worksheet->getCellByColumnAndRow($col,3)->getValue();
            $markname = $worksheet->getCellByColumnAndRow($col,1)->getValue();
              for($row=3; $row <= $highestRow; $row++)
              {
                $stuid = $worksheet->getCellByColumnAndRow(0,$row)->getValue();
                $zeromarkinfo= $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                if($worksheet->getCellByColumnAndRow($col,$row)!='')
                {
                  $value1=$worksheet->getCellByColumnAndRow($col,$row)->getValue();
                  $value2=$worksheet->getCellByColumnAndRow($col,3)->getValue();
                  $value = $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                  
                  $data[] = array(
                    'stuid'  => $stuid,
                    'subject'=>$subname,
                    'grade'=>$gradesec,
                    'mergedname'=>'',
                    'quarter'=>$quarter,
                    'total'=>$value,
                    'academicyear'=>$YearName,
                    'letter'=>'#',
                    'onreportcard'=>'1',
                    'subjorder'=>'',
                    'rpbranch'=>$mybranch
                  );
                }
              }
          }
          if(!empty($data)){
              $query=$this->db->insert_batch('reportcard'.$gradesec.$YearName,$data);
              if($query) {
                echo '<div class="col-md-6 col-6">
                <div class="alert alert-success alert-dismissible show fade">
                  <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                    <i class="fas fa-check-circle"> </i> Data inserted successfully for subject '.$subname.'.
                  </div>
                </div></div>';
              }else{
                echo '
                <div class="alert alert-wa alert-dismissible show fade">
                  <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                    <i class="fas fa-times"> </i> Please try Again.
                  </div>
                </div> ';
              }
          }else{
           echo' <div class="col-md-6 col-6">
              <div class="alert alert-light alert-dismissible show fade">
                <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                  <i class="fas fa-times"> </i> Please try again,Either file exists or something wrong with your excel subject '.$subname.'.
                </div>
              </div></div>';
          }
        }
      }else{
         echo'
            <div class="alert alert-light alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i> Please select only xls file.
              </div>
            </div> ';
      }
    }
    echo '</div>';
  }
}