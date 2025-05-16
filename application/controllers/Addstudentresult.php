<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Addstudentresult extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('teacher_model');
    ob_start();
    $this->load->helper('security');
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $uaddMark1="SELECT * from usergrouppermission where usergroup=? and tableName=? and allowed=? order by id ASC ";
    $uaddMark=$this->db->query($uaddMark1,array($_SESSION['usertype'],'StudentMark','addstudentmark')); 
    if($this->session->userdata('username') == '' || $uaddMark->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='addexam')
	{
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
      show_404();
    }
    $user=$this->session->userdata('username');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;

    $this->db->select('branch,id,status2');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $approvedID=$row_branch->id;
    $status2=$row_branch->status2;

    $this->db->select('max(term) as quarter');
    $this->db->where('Academic_Year',$max_year);
    $query2=$this->db->get('quarter');
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;

    $this->db->where('academicyear',$max_year);
    $this->db->where('dname',$status2);
    $this->db->where('dquarter',$max_quarter);
    $querychk=$this->db->get('dmarkstatus');
    $data['markstatus']=$querychk;

    $this->db->where('academicyear',$max_year);
    $this->db->where('classname','summerClass');
    $querySummerCheck=$this->db->get('startsummerclass');
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
  function FilterAssesmentQuarterChange(){
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('evaluation')){
      $gradesec=$this->input->post('gradesec',TRUE);
      $year=$this->input->post('year',TRUE);
      $evaluation=$this->input->post('evaluation',TRUE);
      $quarter=$this->input->post('quarter',TRUE);
      $subject=$this->input->post('subject',TRUE);
      $branch=$this->input->post('branch',TRUE);

      $gradesec=xss_clean($gradesec);
      $year=xss_clean($year);
      $evaluation=xss_clean($evaluation);
      $quarter=xss_clean($quarter);
      $subject=xss_clean($subject);
      $branch=xss_clean($branch);

      $this->db->select('evname');
      $this->db->where('eid',$evaluation);
      $queyEval=$this->db->get('evaluation');
      $evaRow=$queyEval->row();
      $evaName=$evaRow->evname;
      $this->db->where('academicyear',$max_year);
      $this->db->where('assesment_status','1');
      $query = $this->db->get('filter_assesment_by_branch_subject');
      if($query->num_rows()>0){
        echo $this->teacher_model->FilterAssesmentQuarterChange_filter_assement($evaName,$gradesec,$year,$branch,$quarter,$subject); 
      }else{
        echo $this->teacher_model->FilterAssesmentQuarterChange($evaName,$gradesec,$year,$branch,$quarter,$subject); 
      }
    } 
  }
  function studentResultForm(){
    $user=$this->session->userdata('username');
    $this->db->select('branch,id,status2');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;

    if($this->input->post('gradesec')){
      $academicyear=$this->input->post('academicyear',TRUE);
      $gradesec=$this->input->post('gradesec',TRUE);
      $subject=$this->input->post('subject',TRUE);
      $evaluation=$this->input->post('evaluation',TRUE);
      $quarter=$this->input->post('quarter',TRUE);
      $assesname=$this->input->post('assesname',TRUE);
      $percentage=$this->input->post('percentage',TRUE);
      $branch=$this->input->post('branch',TRUE);

      $academicyear=xss_clean($academicyear);
      $gradesec=xss_clean($gradesec);
      $subject=xss_clean($subject);
      $evaluation=xss_clean($evaluation);
      $quarter=xss_clean($quarter);
      $assesname=xss_clean($assesname);
      $percentage=xss_clean($percentage);
      $branch=xss_clean($branch);
      $this->db->where('academicyear',$academicyear);
      $this->db->where('staff',$user);
      $queryFetchRemote=$this->db->get('staffremoteplacement');
      if($queryFetchRemote->num_rows()>0){
        echo $this->teacher_model->fetch_thisgrade_students_fornewexam($academicyear,$gradesec,$subject,$evaluation,$quarter,$assesname,$percentage,$branch);
      }else{
        echo $this->teacher_model->fetch_thisgrade_students_fornewexam($academicyear,$gradesec,$subject,$evaluation,$quarter,$assesname,$percentage,$mybranch);
      }
    }
  }
  function addNewresult(){
    $user=$this->session->userdata('username');
    if($this->input->post('stuid')){
      $stuid=$this->input->post('stuid',TRUE);
      $resultvalue=$this->input->post('resultvalue',TRUE);
      $academicyear=$this->input->post('academicyear',TRUE);
      $subject=$this->input->post('subject',TRUE);
      $evaluation=$this->input->post('evaluation',TRUE);
      $quarter=$this->input->post('quarter',TRUE);
      $assesname=$this->input->post('assesname',TRUE);
      $percentage=$this->input->post('percentage',TRUE);
      $markGradeSec=$this->input->post('markGradeSec',TRUE);
      $branch=$this->input->post('branch',TRUE);

      $stuid=xss_clean($stuid);
      $resultvalue=xss_clean($resultvalue);
      $academicyear=xss_clean($academicyear);
      $subject=xss_clean($subject);
      $evaluation=xss_clean($evaluation);
      $quarter=xss_clean($quarter);
      $assesname=xss_clean($assesname);
      $percentage=xss_clean($percentage);
      $markGradeSec=xss_clean($markGradeSec);
      $branch=xss_clean($branch);
      $queryChk=$this->teacher_model->save_thisgrade_exam($academicyear,$subject,$quarter,$assesname,$markGradeSec,$branch);
        if($queryChk){
          $data=array();
          for ($i=0; $i < count($stuid); $i++) { 
            $id=$stuid[$i];
            $markvalue=$resultvalue[$i];
            if($percentage>=$markvalue && $markvalue>=0 && $markvalue!=''){
              $data[]=array(
                'stuid'=>$id,
                'academicyear'=>$academicyear,
                'markname'=>$assesname,
                'subname'=>$subject,
                'evaid'=>$evaluation,
                'quarter'=>$quarter,
                'outof'=>$percentage,
                'value'=>$markvalue,
                'mgrade'=>$markGradeSec,
                'mbranch'=>$branch,
                'approved'=>'0'
              );
            }
          }
          $query=$this->db->insert_batch('mark'.$branch.$markGradeSec.$quarter.$academicyear,$data);
          if($query){
            echo '<button class="btn btn-default btn-lg backToMainPage"> <i class="fas fa-chevron-left"  style="font-size: 30px;" ></i></button><div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i> Result submitted successfully.
            </div></div>';
          }else{
            echo '<button class="btn btn-default btn-lg backToMainPage"> <i class="fas fa-chevron-left"  style="font-size: 30px;" ></i></button><div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i> Please Try Again.
            </div></div>';
          }
        }else{
          echo '<button class="btn btn-default btn-lg backToMainPage"> <i class="fas fa-chevron-left"  style="font-size: 30px;" ></i></button><div class="alert alert-warning alert-dismissible show fade">
          <div class="alert-body">
              <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
              </button>
          <i class="fas fa-exclamation-circle"> </i> Mark already exists.
          </div></div>';
        }
      
    }
  }
  function load_subject_to_feed(){
    $user=$this->session->userdata('username');
    $this->db->select('branch');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;

    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;
    echo $this->teacher_model->load_subject_to_feed($user,$mybranch,$max_year);
  }
  function fetch_form(){
    if($this->input->post('academicyear')){
      $academicyear=$this->input->post('academicyear');
      $gradesec=$this->input->post('grade');
      $branch=$this->input->post('branch');
      $subject=$this->input->post('subject');
      echo $this->teacher_model->fetch_thisFilter_Form($academicyear,$gradesec,$subject,$branch);
    }
  }
  public function Filter_evaluation_quarterchange()
  {
    if($this->input->post('gradesec')){
      $gradesec=$this->input->post('gradesec',TRUE);
      $quarter=$this->input->post('quarter',TRUE);
      $year=$this->input->post('year',TRUE);
      $gradesec=xss_clean($gradesec);
      $quarter=xss_clean($quarter);
      $year=xss_clean($year);
      echo $this->teacher_model->fetch_evaluation_on_quarterchange($quarter,$gradesec,$year);
    }
  }
  public function FilterPercentageOnAssesmentChange(){
    if($this->input->post('evaluation')){
      $gradesec=$this->input->post('gradesec',TRUE);
      $evaluation=$this->input->post('evaluation',TRUE);
      $quarter=$this->input->post('quarter',TRUE);
      $subject=$this->input->post('subject',TRUE);
      $branch=$this->input->post('branch',TRUE);
      $year=$this->input->post('year',TRUE);

      $gradesec=xss_clean($gradesec);
      $evaluation=xss_clean($evaluation);
      $quarter=xss_clean($quarter);
      $subject=xss_clean($subject);
      $branch=xss_clean($branch);
      $year=xss_clean($year);
      echo $this->teacher_model->FilterPercentageAssesmentChange($evaluation,$gradesec,$year,$branch,$quarter,$subject); 
    }
  }
  public function class_change_evaluation_my_subject(){
    $user=$this->session->userdata('username');
    $this->db->select('branch');
    $this->db->where('username',$user);
    $query_branch=$this->db->get('users');
    $row_branch = $query_branch->row();
    $mybranch=$row_branch->branch;

    $this->db->select('max(year_name) as year');
    $query=$this->db->get('academicyear');
    $row = $query->row();
    $max_year=$row->year;

    $this->db->select('max(term) as quarter');
    $this->db->where('Academic_Year',$max_year);
    $query2=$this->db->get('quarter');
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    echo $this->teacher_model->class_change_evaluation_my_subject($user,$mybranch,$max_year,$max_quarter);
  }
  public function edit_this_subject_percentage(){
    $user=$this->session->userdata('username');
    if($this->input->post('grade')){
      $grade=$this->input->post('grade',TRUE);
      $subject=$this->input->post('subject',TRUE);
      $season=$this->input->post('season',TRUE);
      $year=$this->input->post('year',TRUE);
      $customasses=$this->input->post('customasses',TRUE);
      $percentValue=$this->input->post('percentValue',TRUE);

      $grade=xss_clean($grade);
      $subject=xss_clean($subject);
      $season=xss_clean($season);
      $year=xss_clean($year);
      $customasses=xss_clean($customasses);
      $percentValue=xss_clean($percentValue);

      echo $this->teacher_model->edit_this_subject_percentage($user,$grade,$subject,$season,$year,$customasses,$percentValue);
    }
  }
  public function update_this_subject_percentage(){
    $user=$this->session->userdata('username');
    $adata=array();
    if($this->input->post('grade')){
      $grade=$this->input->post('grade',TRUE);
      $subject=$this->input->post('subject',TRUE);
      $season=$this->input->post('season',TRUE);
      $year=$this->input->post('year',TRUE);
      $customasses=$this->input->post('customasses',TRUE);
      $percentValue=$this->input->post('percentValue',TRUE);
      $oldPercentValue=$this->input->post('oldPercentValue',TRUE);

      $grade=xss_clean($grade);
      $subject=xss_clean($subject);
      $season=xss_clean($season);
      $year=xss_clean($year);
      $customasses=xss_clean($customasses);
      $percentValue=xss_clean($percentValue);
      $oldPercentValue=xss_clean($oldPercentValue);

      $this->db->where('academicyear',$year);
      $this->db->where('quarter',$season);
      $this->db->where('evname',$customasses);
      $this->db->where('grade',$grade);
      $queryEvaluation=$this->db->get('evaluation');
      /*$queryEvaluation= $this->db->query("select * from evaluation where academicyear='$year' and quarter='$season' and evname='$customasses' and grade='$grade' "); */
      if($queryEvaluation->num_rows()>0){
        $evGroupR=$queryEvaluation->row();
        $evGroup=$evGroupR->evgroup;
        $percents=$evGroupR->percent;
        if($percents!==trim($percentValue)){
          if(trim($oldPercentValue)!==trim($percentValue)){
            $this->db->where('academicyear',$year);
            $this->db->where('customquarter',$season);
            $this->db->where('customasses',$customasses);
            $this->db->where('customsubject',$subject);
            $this->db->where('customgrade',$grade);
            $queryCustomEvaluation=$this->db->get('evaluationcustom');

            /*$queryCustomEvaluation= $this->db->query("select * from evaluationcustom where academicyear='$year' and customquarter='$season' and customasses='$customasses' and customsubject='$subject' and customgrade='$grade' "); */
            if($queryCustomEvaluation->num_rows()>0){
              $this->db->where('academicyear',$year);
              $this->db->where('customquarter',$season);
              $this->db->where('customasses',$customasses);
              $this->db->where('customsubject',$subject);
              $this->db->where('customgrade',$grade);
              $this->db->set('custompercent',$percentValue);
              $queryUpdate=$this->db->update('evaluationcustom');
              if($queryUpdate){
                echo '1';
              }else{
                echo '2';
              }
            }else{
              $adata=array(
                'academicyear'=>$year,
                'customquarter'=>$season,
                'customasses'=>$customasses,
                'customsubject'=>$subject,
                'customgrade'=>$grade,
                'custompercent'=>$percentValue,
                'quartergroup'=>$evGroup
              );
              $queryInsert=$this->db->insert('evaluationcustom',$adata);
              if($queryInsert){
                echo '3';
              }else{
                echo '4';
              }
            }
          }else{
            echo '6';
          }
        }else{
          $this->db->where('academicyear',$year);
          $this->db->where('customquarter',$season);
          $this->db->where('customasses',$customasses);
          $this->db->where('customsubject',$subject);
          $this->db->where('customgrade',$grade);
          $this->db->set('custompercent',$percentValue);
          $queryUpdate=$this->db->delete('evaluationcustom');
          if($queryUpdate){
            echo '1';
          }else{
            echo '2';
          }
        }
      }
    }
  }
}