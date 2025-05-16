<?php
class reportcard_model extends CI_Model{
  function fetch_term($max_year){
    $this->db->where('Academic_year',$max_year);
    $this->db->group_by('term');
    $this->db->order_by('term','ASC');
    $query=$this->db->get('quarter');
    return $query->result();
  }
  function fetch_session_user($user){
    $this->db->where('username',$user);
    $this->db->group_by('username');
    $query=$this->db->get('users');
    return $query->result();
  }
  function fetch_grade_from_staffplace($user,$max_year){
    $queryChk = $this->db->select('*')
    ->where('staff', $user)
    ->where('academicyear',$max_year)
    ->get('directorplacement');
    if($queryChk->num_rows()>0){
      return $queryChk->result();
    }else{
      $this->db->where(array('staff'=>$user));
      $this->db->where(array('academicyear'=>$max_year));
      $this->db->group_by('grade');
      $query=$this->db->get('staffplacement');
      return $query->result();
    }
  }
  function fetch_session_gradesec($user,$max_year)
  {
    $this->db->where('staffplacement.academicyear',$max_year);
    $this->db->where('staffplacement.staff',$user);
    $this->db->group_by('staffplacement.grade');
    $this->db->order_by('staffplacement.grade','ASC');
    $query = $this->db->get('staffplacement');
    return $query->result();
  }
  function academic_year_filter(){
    $this->db->select_max('year_name');
    $query=$this->db->get('academicyear');
    return $query->result();
  }
  function academic_year(){
    $this->db->order_by('year_name','DESC');
    $this->db->select('year_name');
    $query=$this->db->get('academicyear');
    return $query->result();
  }
  function fetch_grade_from_branch($branch,$academicyear){
    $this->db->where('users.branch',$branch);
    $this->db->where('users.academicyear',$academicyear);
    $this->db->order_by('users.gradesec','ASC');
    $this->db->group_by('users.gradesec');
    $query=$this->db->get('users');
    $output ='';
    foreach ($query->result() as $row) { 
      $output .='<option value="'.$row->gradesec.'">'.$row->gradesec.'</option>';
    }
    return $output;
  }
  function fetchReasonIssue(){
    $query=$this->db->query("select * from leavingreason group by leavingreason order by leavingreason ASC");
    $output='';
    if($query->num_rows()>0){
      foreach($query->result() as $row){
        $output.='<div class="support-ticket media pb-1 mb-3">
            <div class="media-body ml-3">
              <button class="btn btn-outline-danger mb-1 float-right" type="submit" id="deleteLeavingIssue" value="'.$row->id.'">Delete <i class="fas fa-trash-alt"></i></button>
              <span class="font-weight-bold">'.$row->leavingreason.'</span>
              <small class="text-muted">Created on '.$row->datecreated.'</small>
            </div>
          </div>
        <div class="dropdown-divider"></div>
        ';
      }
    }else{
      $output.='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> No record found.
            </div></div>';
    }
    return $output;
  }
  function fetchStudentForEdit($branch,$academicyear,$gradesec){
    $queryfetch=$this->db->query("select fname,mname,lname,grade,username from users where academicyear='$academicyear' and usertype='Student' and status='Active' and isapproved='1' and branch='$branch' and gradesec='$gradesec' group by id order by fname,mname,lname ASC ");
    $output='';
    if($queryfetch->num_rows()>0){
      $query=$this->db->query("select * from leavingreason group by leavingreason order by leavingreason ASC");
      foreach($queryfetch->result() as $row){
        $username=$row->username;
        $output.='<div class="support-ticket media pb-1 mb-3">
          <div class="media-body ml-3">
            <span class="font-weight-bold">'.$row->fname.' '.$row->mname.' '.$row->lname.'</span>
            <div class="badge badge-pill badge-default mb-1 float-right">';
            $queryCheck=$this->db->query("select * from leavingreasoninfo where stuid='$username' ");
            $output.='<select class="form-control" name="setleavingreason" id="setleavingreason">';
            if($queryCheck->num_rows()>0){
              $rowReasonStudent=$queryCheck->row();
              $reasonName=$rowReasonStudent->reasoname;
              $output.='<option selected="selected" name="'.$reasonName.'" class="'.$row->username.'" value="">'.$reasonName.'</option>';
              foreach($query->result() as $rowLeaving){
                if($rowLeaving->leavingreason!=$reasonName){
                  $output.='<option class="'.$row->username.'" name="'.$rowLeaving->leavingreason.'" value="'.$rowLeaving->id.'">'.$rowLeaving->leavingreason.'</option>';
                }
              }
              $output.='<option class="'.$row->username.'" name="backToDefaultReason" value="backToDefaultReason">Completed Grade '.$row->grade.'</option>';
            }else{
              $output.='<option>Completed Grade '.$row->grade.'</option>';
              foreach($query->result() as $rowLeaving){
                $output.='<option class="'.$row->username.'" name="'.$rowLeaving->leavingreason.'" value="'.$rowLeaving->id.'">'.$rowLeaving->leavingreason.'</option>';
              }
            }
            $output.='</select>';
            $output.='</div>
            <p class="my-1">'.$gradesec.' <small class="text-muted">'.$branch.'</small> </p>
          </div>
        </div>
        <div class="dropdown-divider"></div>';
      }
    }else{
      $output.='<div class="alert alert-warning alert-dismissible show fade">
        <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
           <i class="fas fa-exclamation-circle"> </i> No record found.
      </div></div>';
    }
    return $output;
  }
  function updateStudentLeavingStatus($reasonIssue,$stuid,$max_year,$user){
    $queryCheck=$this->db->query("select * from leavingreasoninfo where stuid='$stuid' ");
    if($queryCheck->num_rows()>0){
      $this->db->where('stuid',$stuid);
      $this->db->set('reasoname',$reasonIssue);
      $query=$this->db->update('leavingreasoninfo');
    }else{
      $data=array(
        'stuid'=>$stuid,
        'reasoname'=>$reasonIssue,
        'academicyear'=>$max_year,
        'datecreated'=>date('M-d-Y'),
        'createdby'=>$user
      );
      $query=$this->db->insert('leavingreasoninfo',$data);
    }
  }
  function backToDefaultReason($reasonIssue,$stuid,$max_year,$user){
    $this->db->where('stuid',$stuid);
    $this->db->delete('leavingreasoninfo');
  }
  function fetch_quarter_from_academicYear($academicyear){
    $this->db->where(array('Academic_year'=>$academicyear));
    $this->db->order_by('term','ASC');
    $this->db->group_by('term');
    $query=$this->db->get('quarter');
    $output ='';
    foreach ($query->result() as $row) { 
      $output .='<option value="'.$row->term.'">'.$row->term.'</option>';
    }
      return $output;
  }
  function fetch_gradesec($max_year){
    $this->db->group_by('gradesec');
    $this->db->order_by('gradesec','ASC');
    $this->db->where(array('isapproved'=>'1'));
    $this->db->where(array('academicyear'=>$max_year));
    $this->db->like('usertype','Student');
    $query=$this->db->get('users');
    return $query->result();
  }
  function fetch_branch($max_year){
    $this->db->where('academicyear',$max_year);
    $this->db->order_by('name','ASC');
    $query=$this->db->get('branch');
    return $query->result();
  }
  function fetch_school(){
    $query=$this->db->get('school');
    return $query->result();
  }
  function prepareRCTable($max_year){
    $queryStudent=$this->db->query("select gradesec from users where academicyear='$max_year' and usertype='Student' and status='Active' and isapproved='1' group by gradesec; "); 
    $output='';
    if($queryStudent->num_rows()>0){
      foreach ($queryStudent->result() as $gradesecValue) {
        $gradesec=$gradesecValue->gradesec;
       /* $query=$this->db->query(" INSERT INTO basicskillvalue".$gradesec.$max_year." SELECT * FROM basicskillvalue where mgrade='$gradesec' ");*/
        /*UPDATE basicskillvalue, users 
        SET basicskillvalue.bsgrade = users.gradesec
        WHERE  basicskillvalue.stuid = users.id*/


        $fields=array(
          'rid'=>array(
            'type'=>'INT',
            'constraint'=>255,
            'auto_increment'=>TRUE
          ),
          'stuid'=>array(
            'type'=>'INT',
            'constraint'=>255
          ),
          'grade'=>array(
            'type'=>'VARCHAR',
            'constraint'=>10
          ),
          'subject'=>array(
            'type'=>'VARCHAR',
            'constraint'=>50
          ),
          'mergedname'=>array(
            'type'=>'VARCHAR',
            'constraint'=>30
          ),
          'quarter'=>array(
            'type'=>'VARCHAR',
            'constraint'=>15
          ),
          'total'=>array(
            'type'=>'double'
          ),
          'letter'=>array(
            'type'=>'VARCHAR',
            'constraint'=>2
          ),
          'onreportcard'=>array(
            'type'=>'INT',
            'constraint'=>2
          ),
          'subjorder'=>array(
            'type'=>'INT',
            'constraint'=>2
          ),
          'rpbranch'=>array(
            'type'=>'VARCHAR',
            'constraint'=>25
          ),
          'academicyear'=>array(
            'type'=>'INT',
            'constraint'=>5
          )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('rid',TRUE);
        $query=$this->dbforge->create_table('reportcard'.$gradesec.$max_year,TRUE); 
      }
      if($query){
        $output .='<i class="fas fa-check-circle"> </i>';
      }
    }else{
      $output .='<div class="alert alert-warning alert-dismissible show fade">
          <div class="alert-body">
          <i class="fas fa-check-circle"> </i> Please add student list to the system.
      </div></div>';
    }
    return $output;
  }
  function kGreportcardByQuarter($max_year,$gradesec,$branch,$max_quarter,$startDate1,$endDate1){
    $querySubject=$this->db->query("select us.grade from users as us where  us.gradesec='$gradesec' and us.academicyear='$max_year' and us.branch='$branch' ");
    $output='';
    $rowCHK=$querySubject->row();
    $rowGrade=$rowCHK->grade;
    if($rowGrade == 'KG1' || $rowGrade == 'KG2'||$rowGrade == 'KG3'){
      $queryStudent=$this->db->query("select us.grade,fname,mname,lname, us.id, us.gradesec,section,username from users as us where us.gradesec='$gradesec' and us.status='Active' and us.isapproved='1' and us.academicyear='$max_year' and us.branch='$branch' and grade!='' order by fname,mname,lname ");
      $output='';
      foreach ($queryStudent->result() as $fetchStudent)
      {
          $grade=$fetchStudent->grade;
          $stuid=$fetchStudent->id;
          $username1=$fetchStudent->username;
          $query_name = $this->db->query("select * from school");
          $row_name = $query_name->row();
          $school_name=$row_name->name;
          $querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
          $rowGyear = $querySlogan->row();
          $gYearName=$rowGyear->gyear;
          $dateYear=date('Y');
          $output.= '<div class="row">
          <div class="col-lg-7">';
          $output.='<div class="row">';
          $output.='<div class="col-lg-3"><h4 style="font-family:century_gothicbold;">Grade: '.$fetchStudent->grade.'</h4></div>';
          $output.='<div class="col-lg-3"><h4 style="font-family:century_gothicbold;">Section: '.$fetchStudent->section.'</h4></div></div> ';
          $output.='<div class="table-responsive">
          <table width="100%" class="tabler table-bordered" cellspacing="5" cellpadding="5">';
          $output.='<tr><th class="text-center">
          <h4 id="ENScool" style="font-family:century_gothicbold;">'.$school_name.'<br> '.$gYearName.' G.C '.$max_year.' E.C Quarterly Grade Report</h4></th>
          <th> <h4 style="font-family:century_gothicbold;">'.$max_quarter.'</h4></th>';
          $queryKgSubject=$this->db->query("select * from kgsubject where subgrade='$grade' and academicyear='$max_year' group by subname order by suborder ASC ");
          if($queryKgSubject->num_rows()>0){
            foreach ($queryKgSubject->result() as $subValue) {
              $subname=$subValue->subname;
              $subgrade=$subValue->subgrade;
              $output.='<tr><th id="BGS" colspan="2" class="text-center">
              <h3 style="font-family:century_gothicbold;">'.$subValue->subname.'</h3></th></tr>';
              $querySubObjective=$this->db->query("select * from kgsubjectobjective where subid='$subname' and ograde='$subgrade' and academicyear='$max_year' and 
              quarter='$max_quarter' and subobjective!='S.T.E.M. (Science Technology Engineering Mathematics)' order by oid ASC ");
              if($querySubObjective->num_rows()>0){
                foreach ($querySubObjective->result() as $objValue) {
                  $linksubject=$objValue->linksubject;
                  $output.='<tr>
                  <td><h4 style="font-family:century_gothicbold;">'.$objValue->subobjective.'</h4></td>';
                  $queryFetchResult=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and onreportcard='1' and subject='$linksubject' and academicyear='$max_year' and quarter='$max_quarter' ");
                  if($queryFetchResult->num_rows()>0){
                    foreach ($queryFetchResult->result() as $mValue) {
                      $letter=$mValue->letter;
                      $result1=$mValue->total;
                      if($letter!='A'){
                        $output .= '<td class="text-center"><h4 style="font-family:century_gothicbold;">'.number_format((float)$result1,2,'.','').'</h4></td>';
                      }
                      else{
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                        if($queryRange->num_rows()>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center'><h4 style='font-family:century_gothicbold;'>".$letterVal."</h4></td>";
                          }
                        }else{
                          $output.= "<td class='text-center'><h4 style='font-family:century_gothicbold;'> N</h4></td>";
                        }
                      }
                    }
                  }else{
                    $output.= "<td class='text-center' style='font-family:century_gothicbold;'>-</td>";
                  }
                  $output.='</tr>';
                }

              }
            }
            $querySubObjective=$this->db->query("select * from kgsubjectobjective where ograde='$subgrade' and academicyear='$max_year' and quarter='$max_quarter' and 
            subobjective='S.T.E.M. (Science Technology Engineering Mathematics)' ");
            foreach ($querySubObjective->result() as $subValue) {
              $bsname=$subValue->subobjective;
              $output.= "<td><h4 style='font-family:century_gothicbold;'>".$subValue->subobjective."</h4></td>";
              $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$max_quarter' ");
                if($query_bsvalue->num_rows()>0) {
                  foreach ($query_bsvalue ->result() as $bsresult) {
                    $output .='<td class="text-center"><h4 style="font-family:century_gothicbold;">'.$bsresult->value.'</h4></td>';
                  }
                }else {
                  $output .='<td class="text-center" style="font-family:century_gothicbold;">-</td>';
                }
            }
          }
          $output.='</table></div><hr>';
            if($max_quarter=='Quarter2'){
                $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");   
                if($query_basicskill->num_rows()>0){
                    $output.='<div class="table-responsive">
                    <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
                    foreach ($query_basicskill->result() as $bsvalue) {
                        $bsname=$bsvalue->bsname;
                        $output .='<tr><td><h4 style="font-family:century_gothicbold;">'.$bsvalue->bsname.'</h4></td>';
                      
                        $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' 
                        and bsname='$bsname' and quarter='Quarter2' ");
                        if($query_bsvalue->num_rows()>0) {
                          foreach ($query_bsvalue ->result() as $bsresult) {
                            $output .='<td class="text-center" colspan="2"><h4 style="font-family:century_gothicbold;">'.$bsresult->value.'</h4></td>';
                          }
                        }else {
                          $output .='<td class="text-center" colspan="2">-</td>';
                        }
                       
                      $output .='</tr>';
                    }
                    $output.='</table></div> ';
                }
            }
            if($max_quarter=='Quarter4'){
                $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");   
                if($query_basicskill->num_rows()>0){
                    $output.='<div class="table-responsive">
                    <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
                    foreach ($query_basicskill->result() as $bsvalue) {
                        $bsname=$bsvalue->bsname;
                        $output .='<tr><td><h4 style="font-family:century_gothicbold;">'.$bsvalue->bsname.'</h4></td>';
                      
                        $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' 
                        and bsname='$bsname' and quarter='Quarter4' ");
                        if($query_bsvalue->num_rows()>0) {
                          foreach ($query_bsvalue ->result() as $bsresult) {
                            $output .='<td class="text-center" colspan="2"><h4 style="font-family:century_gothicbold;">'.$bsresult->value.'</h4></td>';
                          }
                        }else {
                          $output .='<td class="text-center" colspan="2">-</td>';
                        }
                       
                      $output .='</tr>';
                    }
                    $output.='</table></div> ';
                }
            }
          $output.='</div>';
          $output.='<div class="col-lg-5">';/*character Developmnet table starts*/
          $output.='<div class="row">';
          $output.="<div class='col-lg-12'><h4 style='font-family:century_gothicbold;'>Student's Name: ".$fetchStudent->fname." ".$fetchStudent->mname." ".$fetchStudent->lname."</h4></div></div>";
          $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
          if($queryCategory->num_rows()>0){
            foreach ($queryCategory->result() as $bscatvalue) {
              $output.= '<div class="table-responsive">
              <table width="100%"  class="tabler table-bordered table-md" cellspacing="5" cellpadding="5">';
              $output .='<tr><th id="BGS" class="text-center">
              <h4 style="font-family:century_gothicbold;">'.$bscatvalue->bscategory.'</h4></th>';
              $output .='<th id="BGS" class="text-center"><h4 style="font-family:century_gothicbold;">'.$max_quarter.'</h4></th></tr>';
              $bscategory=$bscatvalue->bscategory;
              $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and bscategory='$bscategory' and bsname!='S.T.E.M. (Science Technology Engineering Mathematics)' order by bsname ASC ");
              foreach ($query_basicskill->result() as $bsvalue) {
                $bsname=$bsvalue->bsname;
                $output .='<tr><td><h4 style="font-family:century_gothicbold;">'.$bsvalue->bsname.'</h4></td>';
                $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$max_quarter' ");
                if($query_bsvalue->num_rows()>0) {
                  foreach ($query_bsvalue ->result() as $bsresult) {
                    $output .='<td class="text-center"><h4 style="font-family:century_gothicbold;">'.$bsresult->value.'</h4></td>';
                  }
                }else {
                  $output .='<td class="text-center" style="font-family:century_gothicbold;">-</td>';
                }
                $output .='</tr>';
              }
              $output .='</table></div><br>';
            }
          }else{
            $output .='No Basic skill category found';
          }
          $output .='<br>';/*basic skill table closed*/
          /*Attendance table starts*/
        $queryAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and 
        attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
        $output.='<div class="row"><div class="col-lg-8">';
          $output.='<div class="table-responsive">
            <table width="100%"  class="tabler table-bordered table-md">';
          $output .='<tr><th class="text-center" colspan="2" id="BGS"><h4 style="font-family:century_gothicbold;">ATTENDANCE SUMMARY</h4></th></tr>';
          $output .='<tr><td class="text-center"><h4 style="font-family:century_gothicbold;">Absence Days</h4></td>';
          if($queryAbsent->num_rows()>0){
            foreach ($queryAbsent->result() as $abValue) {
              if($abValue->att=='0'){
                $output .='<td class="text-center"><h4 style="font-family:century_gothicbold;">&nbsp;&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;&nbsp;</h4></td></tr>';
              }else{
                $output .='<td class="text-center"><h4 style="font-family:century_gothicbold;">&nbsp;&nbsp;'.$abValue->att.'&nbsp;&nbsp;</h4></td></tr>';
              } 
            }
          }else{
            $output .='<td class="text-center" style="font-family:century_gothicbold;">-</td></tr>';
          }
          $output.='</table></div></div></div><br>';
          $queryEvaKey=$this->db->query("select * from bstype where academicyear='$max_year' and btgrade='$grade' ");
          if($queryEvaKey->num_rows()>0){
            $output.= '<div class="col-lg-10">
            <div class="table-responsive">
            <table width="100%" class="tabler table-bordered table-md">';
            $output .='<th class="text-center" id="BGS" colspan="2"><h4 style="font-family:century_gothicbold;">GRADING CODE</h4></th>';
            foreach ($queryEvaKey->result() as $keyVValue) {
              $output .='<tr><td class="text-center"><h5 style="font-family:century_gothicbold;">&nbsp;&nbsp;'.$keyVValue->bstype.'&nbsp;&nbsp;</h5></td>';
              $output .='<td><h5 style="font-family:century_gothicbold;">'.$keyVValue->bsdesc.'</h5></td></tr>';
            }
            $output .='</table></div></div>';/*Evaluation key table closed*/
            $output .='<div class="col-lg-12"><small class="time"><h5 style="font-family:century_gothicbold;"> Parents should assist their child in the areas where it is required to meet the expected standards.</h5></small></div></div>';
          }else{
            $output .='No Evaluation Key found';
          }
          $output .='</div></br>';/*basic skill column closed*/
          
        }
      }else{
        $output .='<div class="alert alert-warning alert-dismissible show fade">
        <div class="alert-body">
          <button class="close"  data-dismiss="alert">
              <span>&times;</span>
          </button>
          <i class="fas fa-check-circle"> </i> This Grade is not supported here.
        </div></div>';
      }
    return $output;
  }
  function fetchCustomStudent($reportaca,$gradesec,$branch,$rpQuarter){
    $this->db->order_by('fname,mname,lname','ASC');
    $this->db->where(array('status'=>'Active'));
    $this->db->where(array('isapproved'=>'1'));
    $this->db->where(array('academicyear'=>$reportaca));
    $this->db->where(array('gradesec'=>$gradesec));
    $this->db->where(array('branch'=>$branch));
    $this->db->like('usertype','Student');
    $query=$this->db->get('users');
    $output ='';
    $output .='
         <div class="table-responsive">
        <table class="table table-striped table-hover" style="width:100%;">
        <thead>
        <tr>
        <th>No.</th>
            <th>Student ID</th>
            <th>Full Name</th>
            <th>Gr. & Sec</th>
            <th>Branch</th>
            <th>Print</th>
        </tr>
        </thead>
       <tbody>';
        $no=1;
    foreach ($query ->result() as $value) {
      $id=$value->id;
      $output .='<tr class="delete_mem'.$value->id.'">
      <td>'.$no.'.</td>
      <td>'.$value->unique_id.' </td>
      <td>'.$value->fname .' '.$value->mname.' '.$value->lname.' </td> 
      <td>'.$value->gradesec.'</td>
      <td>'.$value->branch.' </td> 
      <td><button class="btn btn-info printThisStudentReport" name="'.$id.'" value="'.$rpQuarter.'" id="'.$reportaca.'"><i class="fas fa-print"></i></button></td> </tr>';
      $no++;
    }
    return $output;
  }
  function customReportCard($max_year,$gradesec,$branch,$max_quarter,$id){
    $output ='';$resultSem1=0;
    $resultSem2=0;
    $queryCHK=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and stuid='$id' group by subject order by subjorder ASC");
    if($queryCHK->num_rows()>0){
        $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, username from users where id='$id' ");
        
        $queryStudentNum=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' ");
        $stuAll=$queryStudentNum->num_rows();
      if($queryStudent->num_rows()>0){
        $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowGyear = $querySlogan->row();
        $gYearName=$rowGyear->gyear;
        $dateYear=date('Y');
        foreach ($queryStudent->result() as $fetchStudent)
        {
          $grade=$fetchStudent->grade;
          $stuid=$fetchStudent->id;
          $username1=$fetchStudent->username;
          //$gradesec=$fetchStudent->gradesec;
          $output.= '<div class="row" id="ENS">
          <div class="col-lg-7 col-md-7">';
          $output.='<div class="row">';
          $output.='<div class="col-lg-3"><b id="ENS">Grade: '.$fetchStudent->grade.'</b></div>';
          $output.='<div class="col-lg-3"><b id="ENS">Section: '.$fetchStudent->section.'</b></div> ';
          $output.='</div>';
          $output.='<div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
          $output.='<tr><th colspan="15" class="text-center">
          <h6 id="ENScool"><B id="ENS">'.$school_name.' '.$gYearName.' G.C '.$max_year.' E.C Student Report Card</B></h6>
          </th></tr>
          <tr><th rowspan="2" class="text-center">Subject</th>
          <th colspan="6" class="text-center">First Semester</th>
          <th colspan="6" class="text-center">Second Semester</th>
          <th rowspan="2" colspan="2" class="text-center">Yearly Average</th></tr>';
          $output.='<tr><td colspan="2" class="text-center">First Quarter</td>';
          $output.='<td colspan="2" class="text-center">Second Quarter</td>
          <td colspan="2" class="text-center"><b>First Semester</b></td>
          <td colspan="2" class="text-center">Third Quarter</td>
          <td colspan="2" class="text-center">Fourth Quarter</td>
          <td colspan="2" class="text-center"><b>Second Semester</b></td></tr>';
          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' 
          and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr><td style="white-space: nowrap"><B>'.$fetchSubject->subject.'</B></td>';/*Subject List*/

              /*fetch quarter 1 result starts*/
              $queryReportCardQ1=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter1' and subject='$subject' order by subjorder ");
              if($queryReportCardQ1->num_rows()>0){
                foreach ($queryReportCardQ1->result() as $fetchResult1) {
                  $result1=$fetchResult1->total;
                  if($fetchResult1->total=='' || $fetchResult1->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result1=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result1,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result1,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                  $queryQ1SubRank1=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from 
                  reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and subject='$subject' and quarter='Quarter1' group by stuid) sm)) as stuRank1 from 
                  reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and rpbranch='$branch' and quarter='Quarter1' and subject='$subject' group by subject ");
                  if($result1=='' || $result1<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($queryQ1SubRank1->result() as $q1SubRank1)
                    {
                      $Q1SubjRank1=$q1SubRank1->stuRank1;
                      $output.='<td class="text-center"><b>'.$Q1SubjRank1.'</b></td>';
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }

               /*fetch quarter 2 result starts*/
              $queryReportCardQ2=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter2' and subject='$subject' order by subjorder ");
              if($queryReportCardQ2->num_rows()>0){
                foreach ($queryReportCardQ2->result() as $fetchResult2) {
                  $result2=$fetchResult2->total;
                  if($fetchResult2->total=='' || $fetchResult2->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result2=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result2,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result2,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                  $queryQ1SubRank2=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' 
                  and subject='$subject' and quarter='Quarter2' group by stuid) sm)) as stuRank2 from 
                  reportcard".$gradesec.$max_year." where stuid='$stuid' and rpbranch='$branch' and grade='$gradesec' and quarter='Quarter2' and subject='$subject' group by subject ");
                  if($result2=='' || $result2<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($queryQ1SubRank2->result() as $q1SubRank2)
                    {
                      $Q1SubjRank2=$q1SubRank2->stuRank2;
                      $output.='<td class="text-center"><b>'.$Q1SubjRank2.'</b></td>';
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }

              /*1st Semester calculation starts*/
              if($queryReportCardQ2->num_rows()>0){
                $sem1Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter1') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter2') as total ");
                foreach ($sem1Ave->result() as $fetchSem1) {
                  $resultSem1=($fetchSem1->total)/2;
                  $firstSemCheck=$resultSem1;
                  if($fetchSem1->total=='' || $fetchSem1->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($resultSem1=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$resultSem1,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$resultSem1,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                  $querySem1SubRank2=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from 
                  reportcard".$gradesec.$max_year." where rpbranch='$branch' and subject='$subject' and grade='$gradesec' and quarter='Quarter2' or 
                  rpbranch='$branch' and subject='$subject' and grade='$gradesec' and quarter='Quarter1' group by stuid) sm)) as stuRankSem1 from reportcard".$gradesec.$max_year." 
                  where stuid='$stuid' and rpbranch='$branch' and grade='$gradesec' and quarter='Quarter2' and subject='$subject' or grade='$gradesec' and stuid='$stuid' and  rpbranch='$branch' and quarter='Quarter1' and subject='$subject' group by subject ");
                  if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($querySem1SubRank2->result() as $Sem1SubRank2)
                    {
                      $Sem1SubjRank2=$Sem1SubRank2->stuRankSem1;
                      $output.='<td class="text-center"><b>'.$Sem1SubjRank2.'</b></td>';
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              /*fetch quarter 3 result starts*/
              $queryReportCardQ3=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter3' and subject='$subject' order by subjorder ");
              if($queryReportCardQ3->num_rows()>0){
                foreach ($queryReportCardQ3->result() as $fetchResult3) {
                  $result3=$fetchResult3->total;
                  if($fetchResult3->total=='' || $fetchResult3->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($result3=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result3,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result3,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result3 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                  $queryQ1SubRank3=$this->db->query("select sum(total),FIND_IN_SET(sum(total), 
                  (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' 
                  and rpbranch='$branch' and subject='$subject' and quarter='Quarter3' group by stuid) sm)) as stuRank3 from reportcard".$gradesec.$max_year." where grade='$gradesec' 
                  and stuid='$stuid' and rpbranch='$branch' and quarter='Quarter3' and subject='$subject' group by subject ");
                  if($result3=='' || $result3<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($queryQ1SubRank3->result() as $q1SubRank3)
                    {
                      $Q1SubjRank3=$q1SubRank3->stuRank3;
                      $output.='<td class="text-center"><b>'.$Q1SubjRank3.'</b></td>';
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              /*$output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';*/
              /*fetch quarter 4 result starts*/
              $queryReportCardQ4=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter4' and subject='$subject' order by subjorder ");
              if($queryReportCardQ4->num_rows()>0){
                foreach ($queryReportCardQ4->result() as $fetchResult4) {
                  $result4=$fetchResult4->total;
                  if($fetchResult4->total=='' || $fetchResult4->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($result4=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result4,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result4,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result4 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                  $queryQ1SubRank4=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from
                  (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and subject='$subject' and quarter='Quarter4' 
                  group by stuid) sm)) as stuRank4 from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and rpbranch='$branch' and quarter='Quarter4' and subject='$subject' group by subject ");
                  if($result4=='' || $result4<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($queryQ1SubRank4->result() as $q1SubRank4)
                    {
                      $Q1SubjRank4=$q1SubRank4->stuRank4;
                      $output.='<td class="text-center"><b>'.$Q1SubjRank4.'</b></td>';
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              /*$output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';*/
              /*2nd Semester calculation starts*/
              if($queryReportCardQ4->num_rows()>0){
                $sem2Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter3') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter4') as total ");
                foreach ($sem2Ave->result() as $fetchSem2) {
                  $resultSem2=($fetchSem2->total)/2;
                  if($fetchSem2->total=='' || $fetchSem2->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($resultSem2=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$resultSem2,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$resultSem2,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                  $querySem2SubRank2=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from 
                  (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and subject='$subject' 
                  and quarter='Quarter3' or grade='$gradesec' and rpbranch='$branch' and subject='$subject' and quarter='Quarter4' group by stuid) sm)) as stuRankSem2 from 
                  reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and rpbranch='$branch' and quarter='Quarter3' and subject='$subject' or stuid='$stuid' and rpbranch='$branch' and quarter='Quarter4' and grade='$gradesec' and subject='$subject' group by subject ");
                  if($resultSem2=='' || $resultSem2<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($querySem2SubRank2->result() as $Sem2SubRank2)
                    {
                      $Sem2SubjRank2=$Sem2SubRank2->stuRankSem2;
                      $output.='<td class="text-center"><b>'.$Sem2SubjRank2.'</b></td>';
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              // $output.='<td class="text-center">-</td>';
              /*Yearly Average calculation starts*/
              if($queryReportCardQ4->num_rows()>0){
                  $sem1Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter1') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter2') as total ");
                  $rowCheckSum1=$sem1Ave->row();
                  $resultSem1=($rowCheckSum1->total)/2;
                  $firstSemCheck=$resultSem1;
                  if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                }else{
                    $YAve=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' ");
                    foreach ($YAve->result() as $fetchYA) {
                      $resultYA=($fetchYA->total)/4;
                      if($fetchYA->total=='' || $fetchYA->total<=0){
                        $output.='<td class="text-center">-</td>';
                      }else{
                        if($letter!='A'){
                          if($resultYA=='100'){
                            $output .= '<td class="text-center">'.number_format((float)$resultYA,0,'.','').'</td>';
                          }else{
                            $output .= '<td class="text-center">'.number_format((float)$resultYA,2,'.','').'</td>';
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultYA between minValue and maxiValue and academicYear='$max_year'");
                          if($queryRange->num_rows()>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center'>".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center'> -</td>";
                          }
                        }
                      }
                      $queryYASubRank2=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from
                      (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and subject='$subject' 
                      group by stuid) sm)) as stuRankYA from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and rpbranch='$branch' and subject='$subject' group by subject ");
                      if($resultSem2=='' || $resultSem2<=0){
                        $output.='<td class="text-center">-</td>';
                      }else{
                        foreach ($queryYASubRank2->result() as $YASubRank2)
                        {
                          $YASubjRank2=$YASubRank2->stuRankYA;
                          $output.='<td class="text-center"><b>'.$YASubjRank2.'</b></td>';
                        }
                      }
                    }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              $output.='</tr>';
            }
          } 
          /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
          $output.='<tr><td><b>Total</b></td>';
          $quarterArray1=array('Quarter1','Quarter2');
          foreach ($quarterArray1 as $quarterValue) {
            $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
            if($quartrTotal->num_rows()>0){
              foreach ($quartrTotal->result() as $totalValue) {
                $printValue=$totalValue->total;
                if($printValue >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*Semester1 Horizontal Total calculation starts*/
          $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
            if($queryReportCardQ2->num_rows()>0){
              foreach ($quartrSem1Total->result() as $totalValueSem1) {
                $printValueSem1=($totalValueSem1->total)/2;
                if($printValueSem1 >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }

          /*Quarter3 & Quarter4 Horizontal Total calculation starts*/
          $quarterArray2=array('Quarter3','Quarter4');
          foreach ($quarterArray2 as $quarterValuee) {
            $quartrTotal2=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValuee' and onreportcard='1' and letter='#' ");
            if($quartrTotal2->num_rows()>0){
              foreach ($quartrTotal2->result() as $totalValue2) {
                $printValue2=$totalValue2->total;
                if($printValue2 >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValue2,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*Semester2 Horizontal Total calculation starts*/
          $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1'
          and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
            if($queryReportCardQ4->num_rows()>0){
              foreach ($quartrSem2Total->result() as $totalValueSem2) {
                $printValueSem2=($totalValueSem2->total)/2;
                if($printValueSem2 >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueSem2,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          /*Yearly Average Horizontal Total calculation starts*/
          $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
            if($queryReportCardQ4->num_rows()>0){
                if($resultSem2 ==0 || $resultSem1 == 0){
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                }else{
                  foreach ($quartrYATotal->result() as $totalValueYA) {
                    $printValueYA=($totalValueYA->total)/4;
                    if($printValueYA >0){
                      $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                    }else{
                      $output.='<td class="text-center" colspan="2">-</td>';
                    }
                  }
                    
                }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          $output.='</tr>';
          /*Horizontal Average calculation starts*/
          $output.='<tr><td><b>Average</b></td>';
          /*1st and snd quarter calculation starts*/
          $quarterArray1=array('Quarter1','Quarter2');
          $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' 
          and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
          $subALl=$countSubject->num_rows();
          foreach ($quarterArray1 as $quarterValue) {
            if($subALl>0){
              $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
              if($quartrTotal->num_rows()>0){
                foreach ($quartrTotal->result() as $totalValue) {
                  $printValue=($totalValue->total)/$subALl;
                  if($printValue >0){
                    $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center" colspan="2">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center" colspan="2">-</td>';
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*1st Semester average starts*/
          $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1'
          and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
            if($queryReportCardQ2->num_rows()>0){
              foreach ($quartrSem1Total->result() as $totalValueSem1) {
                $printValueSem1=(($totalValueSem1->total)/2)/$subALl;
                if($printValueSem1 >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          /*3rd and 4th quarter horizontal average calculation starts*/
          $quarterArray2=array('Quarter3','Quarter4');
          foreach ($quarterArray2 as $quarterValuee) {
            if($subALl>0){
              $quartrTotal2=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValuee' and onreportcard='1' and letter='#' ");
              if($quartrTotal2->num_rows()>0){
                foreach ($quartrTotal2->result() as $totalValue2) {
                  $printValue2=($totalValue2->total)/$subALl;
                  if($printValue2 >0){
                    $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValue2,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center" colspan="2">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center" colspan="2">-</td>';
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*Semester2 Horizontal Average calculation starts*/
         $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
            if($queryReportCardQ4->num_rows()>0){
              foreach ($quartrSem2Total->result() as $totalValueSem2) {
                $printValueSem2=(($totalValueSem2->total)/2)/$subALl;
                if($printValueSem2 >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueSem2,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
            /*$output.='<td class="text-center" colspan="2">-</td>';*/
          /*Yearly Average Horizontal Average calculation starts*/
         $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
            if($queryReportCardQ4->num_rows()>0){
                if($resultSem2 ==0 || $resultSem1 == 0){
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                }else{
                  foreach ($quartrYATotal->result() as $totalValueYA) {
                    $printValueYA=(($totalValueYA->total)/4)/$subALl;
                    if($printValueYA >0){
                      $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                    }else{
                      $output.='<td class="text-center" colspan="2">-</td>';
                    }
                  }
                }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          $output.='</tr>';
          /*Number of student calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">No. Of Student</b></td>';
          if($queryReportCardQ1->num_rows()>0){
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }
          if($resultSem1 <=0){
            $output.='<td class="text-center" colspan="2">-</td>';
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }
          if($queryReportCardQ3->num_rows()>0){
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }
          if($resultSem2 <=0 ){
            $output.='<td class="text-center" colspan="2">-</td>';
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }
          if($resultSem2 <=0 || $resultSem1 <=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }
          $output.='</tr>';
          /*student conduct calculation starts*/
          $output.='<tr><td><b>Conduct</b></td>';
          /*1st and 2nd quarter conduct calculation starts*/
          $quarterArray1=array('Quarter1','Quarter2');
          foreach ($quarterArray1 as $quarterValue) {
            $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarterValue' and bsname='Conduct' group by stuid ");
            if($eachQuarterBasicskill->num_rows()>0){
              $valueBS=$eachQuarterBasicskill->row();
              $getValue=$valueBS->value;
              $output.='<td class="text-center" colspan="2">'.$getValue.'</td>';
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*1st Semester conduct*/
          /*3rd and 4th quarter conduct*/
          $output.='<td class="text-center" colspan="2">-</td>';
          $quarterArray1=array('Quarter3','Quarter4');
          foreach ($quarterArray1 as $quarterValue) {
            $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarterValue' and bsname='Conduct' group by stuid ");
            if($eachQuarterBasicskill->num_rows()>0){
              $valueBS=$eachQuarterBasicskill->row();
              $getValue=$valueBS->value;
              $output.='<td class="text-center" colspan="2">'.$getValue.'</td>';
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*2nd Semester conduct*/
          $output.='<td class="text-center" colspan="2">-</td>';
          /*yearly conduct*/
          $output.='<td class="text-center" colspan="2">-</td>';
          $output.='</tr>';
          /*Absetn days calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">Absence Days</b></td>';
          /*1st and 2nd quarter absence days*/
          $quarterArray1=array('Quarter1','Quarter2');
          foreach ($quarterArray1 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center" colspan="2"><B>'.$absent->att.'</B></td>';
                }
                else{
                  $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }

          }
          /*1st semester absent days*/
          
          $tot1stSem=0;
          if($queryReportCardQ2->num_rows()>0){
              foreach ($quarterArray1 as $qvalue) {
                $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
                if($queryTerm->num_rows()>0){
                $qRow=$queryTerm->row();
                $date1 =$qRow->startdate;
                $date2 =$qRow->endate;
                $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
                $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
                $startDate1= $changeDate1->format('Y-m-d');
                $endDate1= $changeDate2->format('Y-m-d');
                $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
                and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
                  foreach ($query_total_absent->result() as $absent){
                    if($absent->att>0)
                    {
                        $tot1stSem=$tot1stSem + $absent->att;
                    }
                  }
                }
              }
              if($tot1stSem > 0){
                   $output .= '<td class="text-center" colspan="2"><B>'.$tot1stSem.'</B></td>';
              }else{
                   $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
              }
          }
          /*if($queryReportCardQ2->num_rows()>0){
            $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where
            attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='Quarter1' and absentype='Absent'
            and attt.academicyear='$max_year' and se.Academic_year='$max_year' or attt.stuid='$username1' and attt.absentdate between se.startdate and 
            se.endate and se.term='Quarter2' and attt.academicyear='$max_year' and se.Academic_year='$max_year' and absentype='Absent' ");
            if($queryTotalAbsent->num_rows()>0){
              foreach ($queryTotalAbsent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center" colspan="2"><B>'.$absent->att.'</B></td>';
                }
                else{
                  $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }*/
          
          /*quarter 3 and 4th quarter absent days*/
          $quarterArray3=array('Quarter3','Quarter4');
          foreach ($quarterArray3 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center" colspan="2"><B>'.$absent->att.'</B></td>';
                }
                else{
                  $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
             }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*2nd semester absent days*/
          $tot2stSem=0;
          foreach ($quarterArray3 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
             if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot2stSem=$tot2stSem + $absent->att;
                }
              }
            }

          }
          if($tot2stSem > 0){
               $output .= '<td class="text-center" colspan="2"><B>'.$tot2stSem.'</B></td>';
          }else{
               $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
          }
          
          /*if($queryReportCardQ4->num_rows()>0){
            $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='Quarter3' and attt.academicyear='$max_year' and se.Academic_year='$max_year' or attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='Quarter4' and attt.academicyear='$max_year' and se.Academic_year='$max_year' ");
            if($queryTotalAbsent->num_rows()>0){
              foreach ($queryTotalAbsent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center" colspan="2"><B>'.$absent->att.'</B></td>';
                }
                else{
                  $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }*/
          /* Yearly absent days*/
          if($queryReportCardQ4->num_rows()>0){
                if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center" colspan="2">-</td>';
               }else{
                $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' ");
                if($queryTotalAbsent->num_rows()>0){
                  foreach ($queryTotalAbsent->result() as $absent){
                    if($absent->att>0)
                    {
                      $output .= '<td class="text-center" colspan="2"><B>'.$absent->att.'</B></td>';
                    }
                    else{
                      $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
                    }
                  }
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
               }
            
            }else{
                $output.='<td class="text-center" colspan="2">-</td>';
            }
            $output.='</tr>';
            $output.='</table></div><hr>';
            if($max_quarter=='Quarter2'){
                $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");   
                if($query_basicskill->num_rows()>0){
                    $output.='<div class="table-responsive">
                    <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
                    foreach ($query_basicskill->result() as $bsvalue) {
                        $bsname=$bsvalue->bsname;
                        $output .='<tr><td><B>'.$bsvalue->bsname.'</B></td>';
                      
                        $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' 
                        and bsname='$bsname' and quarter='Quarter2' ");
                        if($query_bsvalue->num_rows()>0) {
                          foreach ($query_bsvalue ->result() as $bsresult) {
                            $output .='<td class="text-center" colspan="2">'.$bsresult->value.'</td>';
                          }
                        }else {
                          $output .='<td class="text-center" colspan="2">-</td>';
                        }
                       
                      $output .='</tr>';
                    }
                    $output.='</table></div> ';
                }
            }
            if($max_quarter=='Quarter4'){
                $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");   
                if($query_basicskill->num_rows()>0){
                    $output.='<div class="table-responsive">
                    <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
                    foreach ($query_basicskill->result() as $bsvalue) {
                        $bsname=$bsvalue->bsname;
                        $output .='<tr><td><B>'.$bsvalue->bsname.'</B></td>';
                      
                        $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' 
                        and bsname='$bsname' and quarter='Quarter4' ");
                        if($query_bsvalue->num_rows()>0) {
                          foreach ($query_bsvalue ->result() as $bsresult) {
                            $output .='<td class="text-center" colspan="2">'.$bsresult->value.'</td>';
                          }
                        }else {
                          $output .='<td class="text-center" colspan="2">-</td>';
                        }
                       
                      $output .='</tr>';
                    }
                    $output.='</table></div> ';
                }
            }
          
          
          $output.='</div>';/*result table closed*/
          $output.= '<div class="col-lg-5 col-md-5">';
          $output.='<div class="row">';
          $output.="<div class='col-lg-12'><b id='ENS'>Student's Name: ".$fetchStudent->fname." ".$fetchStudent->mname." ".$fetchStudent->lname."</b></div></div>";
          $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
          if($queryCategory->num_rows()>0){
            $output.= '<div class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $dateYear=date('Y');
            $output .='<tr><th colspan="5" class="text-center">'.$gYearName.' G.C '.$max_year.' E.C Basic Skills and Behaviour Progress Report</th></tr>';
            $output .='<tr><th>Evaluation Area</th>';
            $quarterArrayQ=array('Quarter1','Quarter2','Quarter3','Quarter4');
            foreach ($quarterArrayQ as $qvalue) 
            {
              $output .='<th class="text-center">'.$qvalue.'</th>';
            }
            foreach ($queryCategory->result() as $bscatvalue) {
              $bscategory=$bscatvalue->bscategory;
              $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and bsname!='Conduct' and bscategory='$bscategory' order by bsname ASC ");       
              $output .='<tr><th colspan="5" id="BGS" class="text-center">'.$bscategory.'</th>';
              foreach ($query_basicskill->result() as $bsvalue) {
                $bsname=$bsvalue->bsname;
                $output .='<tr><td>'.$bsvalue->bsname.'</td>';
                foreach ($quarterArrayQ as $qvalue) {
                  $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                  if($query_bsvalue->num_rows()>0) {
                    foreach ($query_bsvalue ->result() as $bsresult) {
                      $output .='<td class="text-center">'.$bsresult->value.'</td>';
                    }
                  }else {
                    $output .='<td class="text-center">-</td>';
                  }
                } 
                $output .='</tr>';
              }
              
            }
            $output .='</table></div><br>';/*basic skill table closed*/
          }

          $queryEvaKey=$this->db->query("select * from bstype where academicyear='$max_year' and btgrade='$grade' ");
          $output.='<div class="row"><div class="col-lg-6">';
          if($queryEvaKey->num_rows()>0){
            $output.= '<div id="ENS" class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $output .='<th class="text-center" colspan="2">Evaluation Key</th>';
            foreach ($queryEvaKey->result() as $keyVValue) {
              $output .='<tr><td class="text-center">'.$keyVValue->bstype.'</td>';
              $output .='<td class="text-center">'.$keyVValue->bsdesc.'</td></tr>';
            }
            $output .='</table></div>';
          }else{
            $output .='No Evaluation Key found';
          }
          $output .='</div>';
          $output.='<div class="col-lg-6">';
          $queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade'  ");
          if($queryRangeValue->num_rows()>0){
            $output.= '<div id="ENS" class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $output .='<tr><th class="text-center" colspan="3">Letter Grade Evaluation Key</th></tr>';
            foreach ($queryRangeValue->result() as $rangeValue) {
              $output .='<tr><td class="text-center">'.$rangeValue->minValue.' - '.$rangeValue->maxiValue.'</td>';
              $output .='<td class="text-center">'.$rangeValue->letterVal.'</td> </tr>';
            }
            $output.= '</table></div>';
          }
          $output .='</div><div class="col-lg-12"><small class="time"> Parents should help and give advice for their child in the areas where it is required.<br><i>Let Us work together for our  children!</i></small></div></div>';

          $output .='</div><br>';/*basic skill column closed*/
          $output.='</div>';/*class row closed*/
          $output.='<div class="dropdown-divider2"></div><h6 class="text-center"><strong>You can always do better than this!</strong></h6>';
        }
      } 
    }
    return $output;
  }
  function KgcustomReportCardSeattle($max_year,$gradesec,$branch,$max_quarter,$id){
    $output ='';$resultSem1=0;
    $resultSem2=0;
    $queryCHK=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and stuid='$id' group by subject order by subjorder ASC");
    if($queryCHK->num_rows()>0){
        $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, username from users where id='$id' ");
        
        $queryStudentNum=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' ");
        $stuAll=$queryStudentNum->num_rows();
      if($queryStudent->num_rows()>0){
        $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowGyear = $querySlogan->row();
        $gYearName=$rowGyear->gyear;
        $dateYear=date('Y');
        foreach ($queryStudent->result() as $fetchStudent)
        {
          $grade=$fetchStudent->grade;
          $stuid=$fetchStudent->id;
          $username1=$fetchStudent->username;
          //$gradesec=$fetchStudent->gradesec;
          $output.= '<div class="row" id="ENS">
          <div class="col-lg-7 col-md-7">';
          $output.='<div class="row">';
          $output.='<div class="col-lg-3"><b id="ENS">Grade: '.$fetchStudent->grade.'</b></div>';
          $output.='<div class="col-lg-3"><b id="ENS">Section: '.$fetchStudent->section.'</b></div> ';
          $output.='</div>';
          $output.='<div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
          $output.='<tr><th colspan="15" class="text-center">
          <h6 id="ENScool"><B id="ENS">'.$school_name.' '.$gYearName.' G.C '.$max_year.' E.C Student Report Card</B></h6>
          </th></tr>
          <tr><th rowspan="2" class="text-center">Subject</th>
          <th colspan="6" class="text-center">First Semester</th>
          <th colspan="6" class="text-center">Second Semester</th>
          <th rowspan="2" colspan="2" class="text-center">Yearly Average</th></tr>';
          $output.='<tr><td colspan="2" class="text-center">First Quarter</td>';
          $output.='<td colspan="2" class="text-center">Second Quarter</td>
          <td colspan="2" class="text-center"><b>First Semester</b></td>
          <td colspan="2" class="text-center">Third Quarter</td>
          <td colspan="2" class="text-center">Fourth Quarter</td>
          <td colspan="2" class="text-center"><b>Second Semester</b></td></tr>';
          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' 
          and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr><td style="white-space: nowrap"><B>'.$fetchSubject->subject.'</B></td>';/*Subject List*/

              /*fetch quarter 1 result starts*/
              $queryReportCardQ1=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter1' and subject='$subject' order by subjorder ");
              if($queryReportCardQ1->num_rows()>0){
                foreach ($queryReportCardQ1->result() as $fetchResult1) {
                  $result1=$fetchResult1->total;
                  if($fetchResult1->total=='' || $fetchResult1->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result1=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result1,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result1,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                  $queryQ1SubRank1=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from 
                  reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and subject='$subject' and quarter='Quarter1' group by stuid) sm)) as stuRank1 from 
                  reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and rpbranch='$branch' and quarter='Quarter1' and subject='$subject' group by subject ");
                  if($result1=='' || $result1<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($queryQ1SubRank1->result() as $q1SubRank1)
                    {
                      $Q1SubjRank1=$q1SubRank1->stuRank1;
                      $output.='<td class="text-center"><b>'.$Q1SubjRank1.'</b></td>';
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }

               /*fetch quarter 2 result starts*/
              $queryReportCardQ2=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter2' and subject='$subject' order by subjorder ");
              if($queryReportCardQ2->num_rows()>0){
                foreach ($queryReportCardQ2->result() as $fetchResult2) {
                  $result2=$fetchResult2->total;
                  if($fetchResult2->total=='' || $fetchResult2->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result2=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result2,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result2,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                  $queryQ1SubRank2=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' 
                  and subject='$subject' and quarter='Quarter2' group by stuid) sm)) as stuRank2 from 
                  reportcard".$gradesec.$max_year." where stuid='$stuid' and rpbranch='$branch' and grade='$gradesec' and quarter='Quarter2' and subject='$subject' group by subject ");
                  if($result2=='' || $result2<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($queryQ1SubRank2->result() as $q1SubRank2)
                    {
                      $Q1SubjRank2=$q1SubRank2->stuRank2;
                      $output.='<td class="text-center"><b>'.$Q1SubjRank2.'</b></td>';
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }

              /*1st Semester calculation starts*/
              if($queryReportCardQ2->num_rows()>0){
                $sem1Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter1') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter2') as total ");
                foreach ($sem1Ave->result() as $fetchSem1) {
                  $resultSem1=($fetchSem1->total)/2;
                  $firstSemCheck=$resultSem1;
                  if($fetchSem1->total=='' || $fetchSem1->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($resultSem1=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$resultSem1,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$resultSem1,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                  $querySem1SubRank2=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from 
                  reportcard".$gradesec.$max_year." where rpbranch='$branch' and subject='$subject' and grade='$gradesec' and quarter='Quarter2' or 
                  rpbranch='$branch' and subject='$subject' and grade='$gradesec' and quarter='Quarter1' group by stuid) sm)) as stuRankSem1 from reportcard".$gradesec.$max_year." 
                  where stuid='$stuid' and rpbranch='$branch' and grade='$gradesec' and quarter='Quarter2' and subject='$subject' or grade='$gradesec' and stuid='$stuid' and  rpbranch='$branch' and quarter='Quarter1' and subject='$subject' group by subject ");
                  if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($querySem1SubRank2->result() as $Sem1SubRank2)
                    {
                      $Sem1SubjRank2=$Sem1SubRank2->stuRankSem1;
                      $output.='<td class="text-center"><b>'.$Sem1SubjRank2.'</b></td>';
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              /*fetch quarter 3 result starts*/
              $queryReportCardQ3=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter3' and subject='$subject' order by subjorder ");
              if($queryReportCardQ3->num_rows()>0){
                foreach ($queryReportCardQ3->result() as $fetchResult3) {
                  $result3=$fetchResult3->total;
                  if($fetchResult3->total=='' || $fetchResult3->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($result3=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result3,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result3,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result3 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                  $queryQ1SubRank3=$this->db->query("select sum(total),FIND_IN_SET(sum(total), 
                  (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' 
                  and rpbranch='$branch' and subject='$subject' and quarter='Quarter3' group by stuid) sm)) as stuRank3 from reportcard".$gradesec.$max_year." where grade='$gradesec' 
                  and stuid='$stuid' and rpbranch='$branch' and quarter='Quarter3' and subject='$subject' group by subject ");
                  if($result3=='' || $result3<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($queryQ1SubRank3->result() as $q1SubRank3)
                    {
                      $Q1SubjRank3=$q1SubRank3->stuRank3;
                      $output.='<td class="text-center"><b>'.$Q1SubjRank3.'</b></td>';
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              /*$output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';*/
              /*fetch quarter 4 result starts*/
              $queryReportCardQ4=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter4' and subject='$subject' order by subjorder ");
              if($queryReportCardQ4->num_rows()>0){
                foreach ($queryReportCardQ4->result() as $fetchResult4) {
                  $result4=$fetchResult4->total;
                  if($fetchResult4->total=='' || $fetchResult4->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($result4=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result4,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result4,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result4 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                  $queryQ1SubRank4=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from
                  (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and subject='$subject' and quarter='Quarter4' 
                  group by stuid) sm)) as stuRank4 from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and rpbranch='$branch' and quarter='Quarter4' and subject='$subject' group by subject ");
                  if($result4=='' || $result4<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($queryQ1SubRank4->result() as $q1SubRank4)
                    {
                      $Q1SubjRank4=$q1SubRank4->stuRank4;
                      $output.='<td class="text-center"><b>'.$Q1SubjRank4.'</b></td>';
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              /*$output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';*/
              /*2nd Semester calculation starts*/
              if($queryReportCardQ4->num_rows()>0){
                $sem2Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter3') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter4') as total ");
                foreach ($sem2Ave->result() as $fetchSem2) {
                  $resultSem2=($fetchSem2->total)/2;
                  if($fetchSem2->total=='' || $fetchSem2->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($resultSem2=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$resultSem2,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$resultSem2,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                  $querySem2SubRank2=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from 
                  (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and subject='$subject' 
                  and quarter='Quarter3' or grade='$gradesec' and rpbranch='$branch' and subject='$subject' and quarter='Quarter4' group by stuid) sm)) as stuRankSem2 from 
                  reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and rpbranch='$branch' and quarter='Quarter3' and subject='$subject' or stuid='$stuid' and rpbranch='$branch' and quarter='Quarter4' and grade='$gradesec' and subject='$subject' group by subject ");
                  if($resultSem2=='' || $resultSem2<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($querySem2SubRank2->result() as $Sem2SubRank2)
                    {
                      $Sem2SubjRank2=$Sem2SubRank2->stuRankSem2;
                      $output.='<td class="text-center"><b>'.$Sem2SubjRank2.'</b></td>';
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              // $output.='<td class="text-center">-</td>';
              /*Yearly Average calculation starts*/
              if($queryReportCardQ4->num_rows()>0){
                  $sem1Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter1') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter2') as total ");
                  $rowCheckSum1=$sem1Ave->row();
                  $resultSem1=($rowCheckSum1->total)/2;
                  $firstSemCheck=$resultSem1;
                  if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                }else{
                    $YAve=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' ");
                    foreach ($YAve->result() as $fetchYA) {
                      $resultYA=($fetchYA->total)/4;
                      if($fetchYA->total=='' || $fetchYA->total<=0){
                        $output.='<td class="text-center">-</td>';
                      }else{
                        if($letter!='A'){
                          if($resultYA=='100'){
                            $output .= '<td class="text-center">'.number_format((float)$resultYA,0,'.','').'</td>';
                          }else{
                            $output .= '<td class="text-center">'.number_format((float)$resultYA,2,'.','').'</td>';
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultYA between minValue and maxiValue and academicYear='$max_year'");
                          if($queryRange->num_rows()>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center'>".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center'> -</td>";
                          }
                        }
                      }
                      $queryYASubRank2=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from
                      (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and subject='$subject' 
                      group by stuid) sm)) as stuRankYA from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and rpbranch='$branch' and subject='$subject' group by subject ");
                      if($resultSem2=='' || $resultSem2<=0){
                        $output.='<td class="text-center">-</td>';
                      }else{
                        foreach ($queryYASubRank2->result() as $YASubRank2)
                        {
                          $YASubjRank2=$YASubRank2->stuRankYA;
                          $output.='<td class="text-center"><b>'.$YASubjRank2.'</b></td>';
                        }
                      }
                    }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              $output.='</tr>';
            }
          } 
          /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
          $output.='<tr><td><b>Total</b></td>';
          $quarterArray1=array('Quarter1','Quarter2');
          foreach ($quarterArray1 as $quarterValue) {
            $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
            if($quartrTotal->num_rows()>0){
              foreach ($quartrTotal->result() as $totalValue) {
                $printValue=$totalValue->total;
                if($printValue >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*Semester1 Horizontal Total calculation starts*/
          $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
            if($queryReportCardQ2->num_rows()>0){
              foreach ($quartrSem1Total->result() as $totalValueSem1) {
                $printValueSem1=($totalValueSem1->total)/2;
                if($printValueSem1 >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }

          /*Quarter3 & Quarter4 Horizontal Total calculation starts*/
          $quarterArray2=array('Quarter3','Quarter4');
          foreach ($quarterArray2 as $quarterValuee) {
            $quartrTotal2=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValuee' and onreportcard='1' and letter='#' ");
            if($quartrTotal2->num_rows()>0){
              foreach ($quartrTotal2->result() as $totalValue2) {
                $printValue2=$totalValue2->total;
                if($printValue2 >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValue2,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*Semester2 Horizontal Total calculation starts*/
          $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1'
          and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
            if($queryReportCardQ4->num_rows()>0){
              foreach ($quartrSem2Total->result() as $totalValueSem2) {
                $printValueSem2=($totalValueSem2->total)/2;
                if($printValueSem2 >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueSem2,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          /*Yearly Average Horizontal Total calculation starts*/
          $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
            if($queryReportCardQ4->num_rows()>0){
                if($resultSem2 ==0 || $resultSem1 == 0){
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                }else{
                  foreach ($quartrYATotal->result() as $totalValueYA) {
                    $printValueYA=($totalValueYA->total)/4;
                    if($printValueYA >0){
                      $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                    }else{
                      $output.='<td class="text-center" colspan="2">-</td>';
                    }
                  }
                    
                }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          $output.='</tr>';
          /*Horizontal Average calculation starts*/
          $output.='<tr><td><b>Average</b></td>';
          /*1st and snd quarter calculation starts*/
          $quarterArray1=array('Quarter1','Quarter2');
          $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' 
          and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
          $subALl=$countSubject->num_rows();
          foreach ($quarterArray1 as $quarterValue) {
            if($subALl>0){
              $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
              if($quartrTotal->num_rows()>0){
                foreach ($quartrTotal->result() as $totalValue) {
                  $printValue=($totalValue->total)/$subALl;
                  if($printValue >0){
                    $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center" colspan="2">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center" colspan="2">-</td>';
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*1st Semester average starts*/
          $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1'
          and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
            if($queryReportCardQ2->num_rows()>0){
              foreach ($quartrSem1Total->result() as $totalValueSem1) {
                $printValueSem1=(($totalValueSem1->total)/2)/$subALl;
                if($printValueSem1 >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          /*3rd and 4th quarter horizontal average calculation starts*/
          $quarterArray2=array('Quarter3','Quarter4');
          foreach ($quarterArray2 as $quarterValuee) {
            if($subALl>0){
              $quartrTotal2=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValuee' and onreportcard='1' and letter='#' ");
              if($quartrTotal2->num_rows()>0){
                foreach ($quartrTotal2->result() as $totalValue2) {
                  $printValue2=($totalValue2->total)/$subALl;
                  if($printValue2 >0){
                    $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValue2,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center" colspan="2">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center" colspan="2">-</td>';
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*Semester2 Horizontal Average calculation starts*/
         $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
            if($queryReportCardQ4->num_rows()>0){
              foreach ($quartrSem2Total->result() as $totalValueSem2) {
                $printValueSem2=(($totalValueSem2->total)/2)/$subALl;
                if($printValueSem2 >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueSem2,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
            /*$output.='<td class="text-center" colspan="2">-</td>';*/
          /*Yearly Average Horizontal Average calculation starts*/
         $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
            if($queryReportCardQ4->num_rows()>0){
                if($resultSem2 ==0 || $resultSem1 == 0){
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                }else{
                  foreach ($quartrYATotal->result() as $totalValueYA) {
                    $printValueYA=(($totalValueYA->total)/4)/$subALl;
                    if($printValueYA >0){
                      $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                    }else{
                      $output.='<td class="text-center" colspan="2">-</td>';
                    }
                  }
                }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          $output.='</tr>';
          /*Number of student calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">No. Of Student</b></td>';
          if($queryReportCardQ1->num_rows()>0){
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }
          if($resultSem1 <=0){
            $output.='<td class="text-center" colspan="2">-</td>';
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }
          if($queryReportCardQ3->num_rows()>0){
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }
          if($resultSem2 <=0 ){
            $output.='<td class="text-center" colspan="2">-</td>';
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }
          if($resultSem2 <=0 || $resultSem1 <=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }
          $output.='</tr>';
          /*student conduct calculation starts*/
          $output.='<tr><td><b>Conduct</b></td>';
          /*1st and 2nd quarter conduct calculation starts*/
          $quarterArray1=array('Quarter1','Quarter2');
          foreach ($quarterArray1 as $quarterValue) {
            $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarterValue' and bsname='Conduct' group by stuid ");
            if($eachQuarterBasicskill->num_rows()>0){
              $valueBS=$eachQuarterBasicskill->row();
              $getValue=$valueBS->value;
              $output.='<td class="text-center" colspan="2">'.$getValue.'</td>';
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*1st Semester conduct*/
          /*3rd and 4th quarter conduct*/
          $output.='<td class="text-center" colspan="2">-</td>';
          $quarterArray1=array('Quarter3','Quarter4');
          foreach ($quarterArray1 as $quarterValue) {
            $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarterValue' and bsname='Conduct' group by stuid ");
            if($eachQuarterBasicskill->num_rows()>0){
              $valueBS=$eachQuarterBasicskill->row();
              $getValue=$valueBS->value;
              $output.='<td class="text-center" colspan="2">'.$getValue.'</td>';
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*2nd Semester conduct*/
          $output.='<td class="text-center" colspan="2">-</td>';
          /*yearly conduct*/
          $output.='<td class="text-center" colspan="2">-</td>';
          $output.='</tr>';
          /*Absetn days calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">Absence Days</b></td>';
          /*1st and 2nd quarter absence days*/
          $quarterArray1=array('Quarter1','Quarter2');
          foreach ($quarterArray1 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center" colspan="2"><B>'.$absent->att.'</B></td>';
                }
                else{
                  $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }

          }
          /*1st semester absent days*/
          
          $tot1stSem=0;
          if($queryReportCardQ2->num_rows()>0){
              foreach ($quarterArray1 as $qvalue) {
                $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
                if($queryTerm->num_rows()>0){
                $qRow=$queryTerm->row();
                $date1 =$qRow->startdate;
                $date2 =$qRow->endate;
                $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
                $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
                $startDate1= $changeDate1->format('Y-m-d');
                $endDate1= $changeDate2->format('Y-m-d');
                $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
                and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
                  foreach ($query_total_absent->result() as $absent){
                    if($absent->att>0)
                    {
                        $tot1stSem=$tot1stSem + $absent->att;
                    }
                  }
                }
              }
              if($tot1stSem > 0){
                   $output .= '<td class="text-center" colspan="2"><B>'.$tot1stSem.'</B></td>';
              }else{
                   $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
              }
          }
          /*if($queryReportCardQ2->num_rows()>0){
            $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where
            attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='Quarter1' and absentype='Absent'
            and attt.academicyear='$max_year' and se.Academic_year='$max_year' or attt.stuid='$username1' and attt.absentdate between se.startdate and 
            se.endate and se.term='Quarter2' and attt.academicyear='$max_year' and se.Academic_year='$max_year' and absentype='Absent' ");
            if($queryTotalAbsent->num_rows()>0){
              foreach ($queryTotalAbsent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center" colspan="2"><B>'.$absent->att.'</B></td>';
                }
                else{
                  $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }*/
          
          /*quarter 3 and 4th quarter absent days*/
          $quarterArray3=array('Quarter3','Quarter4');
          foreach ($quarterArray3 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center" colspan="2"><B>'.$absent->att.'</B></td>';
                }
                else{
                  $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
             }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }
          /*2nd semester absent days*/
          $tot2stSem=0;
          foreach ($quarterArray3 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
             if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot2stSem=$tot2stSem + $absent->att;
                }
              }
            }

          }
          if($tot2stSem > 0){
               $output .= '<td class="text-center" colspan="2"><B>'.$tot2stSem.'</B></td>';
          }else{
               $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
          }
          
          /*if($queryReportCardQ4->num_rows()>0){
            $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='Quarter3' and attt.academicyear='$max_year' and se.Academic_year='$max_year' or attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='Quarter4' and attt.academicyear='$max_year' and se.Academic_year='$max_year' ");
            if($queryTotalAbsent->num_rows()>0){
              foreach ($queryTotalAbsent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center" colspan="2"><B>'.$absent->att.'</B></td>';
                }
                else{
                  $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }*/
          /* Yearly absent days*/
          if($queryReportCardQ4->num_rows()>0){
                if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center" colspan="2">-</td>';
               }else{
                $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' ");
                if($queryTotalAbsent->num_rows()>0){
                  foreach ($queryTotalAbsent->result() as $absent){
                    if($absent->att>0)
                    {
                      $output .= '<td class="text-center" colspan="2"><B>'.$absent->att.'</B></td>';
                    }
                    else{
                      $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
                    }
                  }
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
               }
            
            }else{
                $output.='<td class="text-center" colspan="2">-</td>';
            }
            $output.='</tr>';
            $output.='</table></div><hr>';
            if($max_quarter=='Quarter2'){
                $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");   
                if($query_basicskill->num_rows()>0){
                    $output.='<div class="table-responsive">
                    <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
                    foreach ($query_basicskill->result() as $bsvalue) {
                        $bsname=$bsvalue->bsname;
                        $output .='<tr><td><B>'.$bsvalue->bsname.'</B></td>';
                      
                        $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' 
                        and bsname='$bsname' and quarter='Quarter2' ");
                        if($query_bsvalue->num_rows()>0) {
                          foreach ($query_bsvalue ->result() as $bsresult) {
                            $output .='<td class="text-center" colspan="2">'.$bsresult->value.'</td>';
                          }
                        }else {
                          $output .='<td class="text-center" colspan="2">-</td>';
                        }
                       
                      $output .='</tr>';
                    }
                    $output.='</table></div> ';
                }
            }
            if($max_quarter=='Quarter4'){
                $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");   
                if($query_basicskill->num_rows()>0){
                    $output.='<div class="table-responsive">
                    <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
                    foreach ($query_basicskill->result() as $bsvalue) {
                        $bsname=$bsvalue->bsname;
                        $output .='<tr><td><B>'.$bsvalue->bsname.'</B></td>';
                      
                        $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' 
                        and bsname='$bsname' and quarter='Quarter4' ");
                        if($query_bsvalue->num_rows()>0) {
                          foreach ($query_bsvalue ->result() as $bsresult) {
                            $output .='<td class="text-center" colspan="2">'.$bsresult->value.'</td>';
                          }
                        }else {
                          $output .='<td class="text-center" colspan="2">-</td>';
                        }
                       
                      $output .='</tr>';
                    }
                    $output.='</table></div> ';
                }
            }
          
          
          $output.='</div>';/*result table closed*/
          $output.= '<div class="col-lg-5 col-md-5">';
          $output.='<div class="row">';
          $output.="<div class='col-lg-12'><b id='ENS'>Student's Name: ".$fetchStudent->fname." ".$fetchStudent->mname." ".$fetchStudent->lname."</b></div></div>";
          $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
          if($queryCategory->num_rows()>0){
            $output.= '<div class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $dateYear=date('Y');
            $output .='<tr><th colspan="5" class="text-center">'.$gYearName.' G.C '.$max_year.' E.C Basic Skills and Behaviour Progress Report</th></tr>';
            $output .='<tr><th>Evaluation Area</th>';
            $quarterArrayQ=array('Quarter1','Quarter2','Quarter3','Quarter4');
            foreach ($quarterArrayQ as $qvalue) 
            {
              $output .='<th class="text-center">'.$qvalue.'</th>';
            }
            foreach ($queryCategory->result() as $bscatvalue) {
              $bscategory=$bscatvalue->bscategory;
              $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and bsname!='Conduct' and bscategory='$bscategory' order by bsname ASC ");       
              $output .='<tr><th colspan="5" id="BGS" class="text-center">'.$bscategory.'</th>';
              foreach ($query_basicskill->result() as $bsvalue) {
                $bsname=$bsvalue->bsname;
                $output .='<tr><td>'.$bsvalue->bsname.'</td>';
                foreach ($quarterArrayQ as $qvalue) {
                  $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                  if($query_bsvalue->num_rows()>0) {
                    foreach ($query_bsvalue ->result() as $bsresult) {
                      $output .='<td class="text-center">'.$bsresult->value.'</td>';
                    }
                  }else {
                    $output .='<td class="text-center">-</td>';
                  }
                } 
                $output .='</tr>';
              }
              
            }
            $output .='</table></div><br>';/*basic skill table closed*/
          }

          $queryEvaKey=$this->db->query("select * from bstype where academicyear='$max_year' and btgrade='$grade' ");
          $output.='<div class="row"><div class="col-lg-6">';
          if($queryEvaKey->num_rows()>0){
            $output.= '<div id="ENS" class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $output .='<th class="text-center" colspan="2">Evaluation Key</th>';
            foreach ($queryEvaKey->result() as $keyVValue) {
              $output .='<tr><td class="text-center">'.$keyVValue->bstype.'</td>';
              $output .='<td class="text-center">'.$keyVValue->bsdesc.'</td></tr>';
            }
            $output .='</table></div>';
          }else{
            $output .='No Evaluation Key found';
          }
          $output .='</div>';
          $output.='<div class="col-lg-6">';
          $queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade'  ");
          if($queryRangeValue->num_rows()>0){
            $output.= '<div id="ENS" class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $output .='<tr><th class="text-center" colspan="3">Letter Grade Evaluation Key</th></tr>';
            foreach ($queryRangeValue->result() as $rangeValue) {
              $output .='<tr><td class="text-center">'.$rangeValue->minValue.' - '.$rangeValue->maxiValue.'</td>';
              $output .='<td class="text-center">'.$rangeValue->letterVal.'</td> </tr>';
            }
            $output.= '</table></div>';
          }
          $output .='</div><div class="col-lg-12"><small class="time"> Parents should help and give advice for their child in the areas where it is required.<br><i>Let Us work together for our  children!</i></small></div></div>';

          $output .='</div><br>';/*basic skill column closed*/
          $output.='</div>';/*class row closed*/
          $output.='<div class="dropdown-divider2"></div><h6 class="text-center"><strong>You can always do better than this!</strong></h6>';
        }
      } 
    }
    return $output;
  }
  function Kgannualreportcard($max_year,$gradesec,$branch,$max_quarter,$startDate1,$endDate1){
    $output ='';
    $queryGreYear=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
    if($queryGreYear->num_rows()>0){
      $rowG = $queryGreYear->row();
      $gmax_year=$rowG->gyear;
    }else{
      $gmax_year='-';
    }
    $queryCHK=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
    if($queryCHK->num_rows()>0){
      $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec,username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
      $stuAll=$queryStudent->num_rows();
      if($queryStudent->num_rows()>0){
        $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $dateYear=date('Y');
        foreach ($queryStudent->result() as $fetchStudent)
        {
          $grade=$fetchStudent->grade;
          $stuid=$fetchStudent->id;
          $username1=$fetchStudent->username;
          //$gradesec=$fetchStudent->gradesec;
          $output.= '<div class="row" id="ENS">
          <div class="col-lg-7">';
          $output.='<div class="row">';
          $output.='<div class="col-lg-3"><b id="ENS">Grade: '.$fetchStudent->grade.'</b></div>';
          $output.='<div class="col-lg-3"><b id="ENS">Section: '.$fetchStudent->section.'</b></div> ';
          $output.='</div>';
          $output.='<div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
          $output.='<tr><th colspan="15" class="text-center">
          <h6 id="ENScool"><B id="ENS">'.$school_name.' '.$gmax_year.' G.C '.$max_year.' E.C Student Report Card</B></h6>
          </th></tr>
          <tr><th rowspan="2" class="text-center">Subject</th>
          <th colspan="3" class="text-center">First Semester</th>
          <th colspan="3" class="text-center">Second Semester</th>
          <th rowspan="2" colspan="1" class="text-center">Yearly Average</th></tr>';
          $output.='<tr><td colspan="1" class="text-center">First Quarter</td>';
          $output.='<td colspan="1" class="text-center">Second Quarter</td>
          <td colspan="1" class="text-center"><b>First Semester</b></td>
          <td colspan="1" class="text-center">Third Quarter</td>
          <td colspan="1" class="text-center">Fourth Quarter</td>
          <td colspan="1" class="text-center"><b>Second Semester</b></td></tr>';
          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' 
          and onreportcard='1' and quarter='$max_quarter' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr>';
              if($subject=='Maths in Amharic'){
                $subject='ሂሳብ';
              }else if($subject=='Amharic'){
                $subject='አማርኛ';
              }else{
                $subject=$subject;
              }
              $output.='<td style="white-space: nowrap"><B>'.$subject.'</B></td>';

              /*fetch quarter 1 result starts*/
              $queryReportCardQ1=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter1'
              and subject='$subject' order by subjorder ");
              if($queryReportCardQ1->num_rows()>0){
                foreach ($queryReportCardQ1->result() as $fetchResult1) {
                  $result1=$fetchResult1->total;
                  if($fetchResult1->total=='' || $fetchResult1->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      $output .= '<td class="text-center">'.number_format((float)$result1,2,'.','').'</td>';
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }

               /*fetch quarter 2 result starts*/
              $queryReportCardQ2=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and
              quarter='Quarter2' and subject='$subject' order by subjorder ");
              if($queryReportCardQ2->num_rows()>0){
                foreach ($queryReportCardQ2->result() as $fetchResult2) {
                  $result2=$fetchResult2->total;
                  if($fetchResult2->total=='' || $fetchResult2->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      $output .= '<td class="text-center">'.number_format((float)$result2,2,'.','').'</td>';
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }

              /*1st Semester calculation starts*/
              if($queryReportCardQ2->num_rows()>0){
                $sem1Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter1') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter2') as total ");
                foreach ($sem1Ave->result() as $fetchSem1) {
                  $resultSem1=($fetchSem1->total)/2;
                  if($fetchSem1->total=='' || $fetchSem1->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      $output .= '<td class="text-center">'.number_format((float)$resultSem1,2,'.','').'</td>';
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              /*fetch quarter 3 result starts*/
              $queryReportCardQ3=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter3' and subject='$subject' order by subjorder ");
              if($queryReportCardQ3->num_rows()>0){
                foreach ($queryReportCardQ3->result() as $fetchResult3) {
                  $result3=$fetchResult3->total;
                  if($fetchResult3->total=='' || $fetchResult3->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      $output .= '<td class="text-center">'.number_format((float)$result3,2,'.','').'</td>';
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result3 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              /*$output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';*/
              /*fetch quarter 4 result starts*/
              $queryReportCardQ4=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter4' and subject='$subject' order by subjorder ");
              if($queryReportCardQ4->num_rows()>0){
                foreach ($queryReportCardQ4->result() as $fetchResult4) {
                  $result4=$fetchResult4->total;
                  if($fetchResult4->total=='' || $fetchResult4->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      $output .= '<td class="text-center">'.number_format((float)$result4,2,'.','').'</td>';
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result4 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              /*$output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';*/
              /*2nd Semester calculation starts*/
              if($queryReportCardQ4->num_rows()>0){
                $sem2Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter3') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter4') as total ");
                foreach ($sem2Ave->result() as $fetchSem2) {
                  $resultSem2=($fetchSem2->total)/2;
                  if($fetchSem2->total=='' || $fetchSem2->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      $output .= '<td class="text-center">'.number_format((float)$resultSem2,2,'.','').'</td>';
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              // $output.='<td class="text-center">-</td>';
              /*Yearly Average calculation starts*/
              if($queryReportCardQ4->num_rows()>0){
                $YAve=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' ");
                foreach ($YAve->result() as $fetchYA) {
                  $resultYA=($fetchYA->total)/4;
                  if($fetchYA->total=='' || $fetchYA->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      $output .= '<td class="text-center">'.number_format((float)$resultYA,2,'.','').'</td>';
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultYA between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              $output.='</tr>';
            }
            $queryBSOnRC=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");
            if($queryBSOnRC->num_rows()>0){
              $arrayQuarter=array('Quarter1','Quarter2');
              $arrayQuarter2=array('Quarter3','Quarter4');
              foreach($queryBSOnRC->result() as $onrc){
                $bsname=$onrc->bsname;
                $output.='<td><B>'.$bsname.'</B></td>';

                foreach($arrayQuarter as $arrayQuarters){
                  $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$arrayQuarters' ");
                  if($query_bsvalue->num_rows()>0) {
                    foreach ($query_bsvalue ->result() as $bsresult) {
                      $output .='<td class="text-center">'.$bsresult->value.'</td>';
                    }
                  }else {
                    $output .='<td class="text-center">-</td>';
                  }
                }
                /*1st Semester Average*/

                $output.= "<td class='text-center'> -</td>";

                foreach($arrayQuarter2 as $arrayQuarters){
                  $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$arrayQuarters' ");
                  if($query_bsvalue->num_rows()>0) {
                    foreach ($query_bsvalue ->result() as $bsresult) {
                      $output .='<td class="text-center">'.$bsresult->value.'</td>';
                    }
                  }else {
                    $output .='<td class="text-center">-</td>';
                  }
                }
                $output.= "<td class='text-center'> -</td>";
                $output.= "<td class='text-center'> -</td>";

              }
            }
          } 
          /*Number of student calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">No. Of Student</b></td>';
          if($queryReportCardQ1->num_rows()>0){
            $output.='<td class="text-center">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
          if($queryReportCardQ2->num_rows()>0){
            $output.='<td class="text-center">'.$stuAll.'</td>';
            $output.='<td class="text-center">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
            $output.='<td class="text-center">-</td>';
          }
          if($queryReportCardQ3->num_rows()>0){
            $output.='<td class="text-center">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
          if($queryReportCardQ4->num_rows()>0){
            $output.='<td class="text-center">'.$stuAll.'</td>';
            $output.='<td class="text-center">'.$stuAll.'</td>';
            $output.='<td class="text-center">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
            $output.='<td class="text-center">-</td>';
            $output.='<td class="text-center">-</td>';
          }
          $output.='</tr>';
          
          $output.='</table></div></div>';/*result table closed*/
          $output.= '<div class="col-lg-5">';
          $output.='<div class="row">';
          $output.="<div class='col-lg-12'><b id='ENS'>Student's Name: ".$fetchStudent->fname." ".$fetchStudent->mname." ".$fetchStudent->lname."</b></div></div>";
          $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
          if($queryCategory->num_rows()>0){
            $output.= '<div class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $dateYear=date('Y');
            $output .='<tr><th colspan="5" class="text-center">'.$gmax_year.' G.C '.$max_year.' E.C Basic Skills and Behaviour Progress Report</th></tr>';
            $output .='<tr><th>Evaluation Area</th>';
            $quarterArrayQ=array('Quarter1','Quarter2','Quarter3','Quarter4');
            foreach ($quarterArrayQ as $qvalue) 
            {
              $output .='<th class="text-center">'.$qvalue.'</th>';
            }
            foreach ($queryCategory->result() as $bscatvalue) {
              $bscategory=$bscatvalue->bscategory;
              $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and bsname!='Conduct' and bscategory='$bscategory' order by bsname ASC ");       
              $output .='<tr><th colspan="5" id="BGS" class="text-center">'.$bscategory.'</th>';
              foreach ($query_basicskill->result() as $bsvalue) {
                $bsname=$bsvalue->bsname;
                $output .='<tr><td>'.$bsvalue->bsname.'</td>';
                foreach ($quarterArrayQ as $qvalue) {
                  $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                  if($query_bsvalue->num_rows()>0) {
                    foreach ($query_bsvalue ->result() as $bsresult) {
                      $output .='<td class="text-center">'.$bsresult->value.'</td>';
                    }
                  }else {
                    $output .='<td class="text-center">-</td>';
                  }
                } 
                $output .='</tr>';
              }
              
            }
            $output .='</table></div><br>';/*basic skill table closed*/
          }

          $queryEvaKey=$this->db->query("select * from bstype where academicyear='$max_year' and btgrade='$grade' ");
          $output.='<div class="row"><div class="col-lg-9">';
          if($queryEvaKey->num_rows()>0){
            $output.= '<div id="ENS" class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $output .='<th class="text-center" colspan="2">Evaluation Key</th>';
            foreach ($queryEvaKey->result() as $keyVValue) {
              $output .='<tr><td>'.$keyVValue->bstype.'</td>';
              $output .='<td>'.$keyVValue->bsdesc.'</td></tr>';
            }
            $output .='</table></div>';
          }else{
            $output .='No Evaluation Key found';
          }
          $output .='</div>';
          $output.='<div class="col-lg-3">';
          $queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade'  ");
          if($queryRangeValue->num_rows()>0){
            $output.= '<div id="ENS" class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $output .='<tr><th class="text-center" colspan="3">Letter Grade Evaluation Key</th></tr>';
            foreach ($queryRangeValue->result() as $rangeValue) {
              $output .='<tr><td class="text-center">'.$rangeValue->minValue.' - '.$rangeValue->maxiValue.'</td>';
              $output .='<td class="text-center">'.$rangeValue->letterVal.'</td> </tr>';
            }
            $output.= '</table></div>';
          }
          $output .='</div><div class="col-lg-12"><small class="time"> Parents should help and give advice for their child in the areas where it is required.<br><i>Let Us work together for our  children!</i></small></div></div>';

          $output .='</div><br>';/*basic skill column closed*/
          $output.='</div>';/*class row closed*/
          $output.='<div class="dropdown-divider2"></div><h6 class="text-center"><strong>You can always do better than this!</strong></h6>';
        }
      } 
    }
    return $output;
  }
  function update_reportcardResult($max_year,$gradesec,$branch,$max_quarter){
    $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$max_year."' ");
    if ($queryCheck->num_rows()>0 ){
      $queyDelete=$this->db->query("delete from reportcard".$gradesec.$max_year." where rpbranch ='$branch' and grade='$gradesec' and quarter='$max_quarter' ");
      $queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gradesec' and u.branch='$branch' and isapproved='1' and status='Active' and usertype='Student' group by u.id order by u.fname,u.mname ASC ");
      if($queryStudent->num_rows()>0){
        foreach($queryStudent->result() as $studentList){
          $stuid=$studentList->id;
          $grade=$studentList->grade;
          $querySubject=$this->db->query("select * from subject where Academic_Year='$max_year' and Grade='$grade' group by Subj_name order by suborder ASC ");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $calcMark) {
              $total=0;$average=0;$average1=0;
              $subject=$calcMark->Subj_name;
              $letter=$calcMark->letter;
              $mergedPercent=$calcMark->Merged_percent;
              $onReportCard=$calcMark->onreportcard;
              $subjorder=$calcMark->suborder;
              if($calcMark->Merged_name==''){
                $mergedSubject='';
              }else{
                $mergedSubject=$calcMark->Merged_name;
              }
              $evalname_query=$this->db->query("select ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev where ev.academicyear='$max_year' and ev.quarter='$max_quarter' and ev.grade='$grade' group by ev.evname order by ev.eid ASC");
              if($evalname_query->num_rows()>0){
                $totalMark=0;$outofTot=0;
                foreach($evalname_query->result_array() as $evaRow){
                  $conver=0;
                  $mname_gs=$evaRow['eid'];
                  $evName=$evaRow['evname'];
                  $mname_gs=$evaRow['eid'];
                  $queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$subject' and customasses='$evName' and customquarter='$max_quarter' ");
                  if($queryCheckPercentage->num_rows()>0){
                    $rowPercent=$queryCheckPercentage->row();
                    $percent=$rowPercent->custompercent;
                  }else{
                    $percent= $evaRow['percent'];
                  }
                  $queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from mark".$branch.$gradesec.$max_quarter.$max_year." where stuid='$stuid' and subname='$subject' and quarter='$max_quarter' and evaid='$mname_gs' and mbranch='$branch' group by markname order by mid ASC");
                  if($queryStuValue->num_rows()>0){
                    foreach ($queryStuValue->result_array() as $kevalue) {
                      $outofTot=$outofTot+$kevalue['outof'];
                      $totalMark=$totalMark+$kevalue['value'];
                    }
                  }
                  $queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$branch.$gradesec.$max_quarter.$max_year." where subname='$subject' and quarter='$max_quarter' and evaid='$mname_gs' and mgrade='$gradesec' and mbranch='$branch' group by markname order by mid ASC");
                  $sumOutOf=0;
                  foreach ($queryMvalue->result_array() as $mark_name) {
                    $sumOutOf=$mark_name['outof'] + $sumOutOf;  
                  }
                  if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='')
                  {
                    $conver= ($totalMark *$percent )/$sumOutOf;
                  }
                  $average =$conver + $average;
                  $average1 =($average * $mergedPercent)/100;
                }
              }
              $data[]=array(
                'stuid'=>$stuid,
                'subject'=>$subject,
                'mergedname'=>$mergedSubject,
                'quarter'=>$max_quarter,
                'total'=>number_format((float)$average1,2,'.',''),
                'letter'=>$letter,
                'grade'=>$gradesec,
                'onreportcard'=>$onReportCard,
                'rpbranch'=>$branch,
                'subjorder'=>$subjorder,
                'academicyear'=>$max_year
              ); 
              $average=0;
            }
          }
        }
      }
      if($querySubject->num_rows()>0){
        $query_insert1=$this->db->insert_batch('reportcard'.$gradesec.$max_year,$data);
      }
      $queryMergedSubject=$this->db->query("select us.grade, us.id, us.gradesec, su.Subj_name, su.Merged_percent, su.Merged_name, su.suborder, su.letter, su.onreportcard from subject as su cross join users as us where us.grade=su.Grade and us.gradesec='$gradesec' and us.status='Active' and us.isapproved='1' and us.academicyear='$max_year' and su.Academic_Year='$max_year' and us.branch='$branch' and Merged_name!='' ");
      if($queryMergedSubject->num_rows()>0){
        $sqlDelete=$this->db->query("select *, sum(total) as mergedTot from reportcard".$gradesec.$max_year." where mergedname!='' and rpbranch='$branch' and grade='$gradesec' and quarter='$max_quarter' group by mergedname,stuid ");
        if($sqlDelete->num_rows()>0){
          foreach ($sqlDelete->result() as $mergedValue) {
            $mergedTotal=$mergedValue->mergedTot;
            $stuid=$mergedValue->stuid;
            $subject=$mergedValue->subject;
            $mergedSubject=$mergedValue->mergedname;
            $letter=$mergedValue->letter;
            $subjorder=$mergedValue->subjorder;
            $data1[]=array(
              'stuid'=>$stuid,
              'subject'=>$mergedSubject,
              'quarter'=>$max_quarter,
              'total'=>number_format((float)$mergedTotal,2,'.',''),
              'letter'=>$letter,
              'grade'=>$gradesec,
              'onreportcard'=>'1',
              'rpbranch'=>$branch,
              'subjorder'=>$subjorder,
              'academicyear'=>$max_year
            );
          }
          $query_insert1=$this->db->insert_batch('reportcard'.$gradesec.$max_year,$data1);
        }
      }
      if($query_insert1){
        return true;
      }
    }
  }
  function KgreportcardByQuarterSeattle($max_year,$gradesec,$branch,$max_quarter,$includeBackPage){
    $output ='';$resultSem1=0;
    $resultSem2=0;
    $queryCHK=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
    if($queryCHK->num_rows()>0){
      $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade,section,gradesec,username ,gender,age, city,woreda,profile from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
      $stuAll=$queryStudent->num_rows();
      if($queryStudent->num_rows()>0){
        $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowGyear = $querySlogan->row();
        $gYearName=$rowGyear->gyear;
        $dateYear=date('Y');
        foreach ($queryStudent->result() as $fetchStudent)
        {
          $grade=$fetchStudent->grade;
          $stuid=$fetchStudent->id;
          $section=$fetchStudent->section;
          $username1=$fetchStudent->username;
          $grade_sec=$fetchStudent->gradesec;
          $fname=$fetchStudent->fname;
          $mname=$fetchStudent->mname;
          $lname=$fetchStudent->lname;
          $gender=$fetchStudent->gender;
          $age=$fetchStudent->age;
          $addresss=$fetchStudent->city;
          $woreda=$fetchStudent->woreda;
          $profile=$fetchStudent->profile;
          //$gradesec=$fetchStudent->gradesec;
          $output.= '<div class="row" id="ENS">
          <div class="col-lg-6 col-md-6">';
           $output.="<h4 class='text-center text-info'><b>“Never settle for less than your best!”</b></h4>";
          $output.='<div class="row">';
          $output.='<div class="col-lg-3"><b id="ENS">Grade: '.$fetchStudent->grade.'</b></div>';
          $output.='<div class="col-lg-3"><b id="ENS">Section: '.$fetchStudent->section.'</b></div> ';
          $output.='</div>';
          $output.='<div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
          $output.='<tr><th colspan="8" class="text-center">
          <h6 id="ENScool"><B id="ENS">'.$school_name.' '.$gYearName.' G.C '.$max_year.' E.C Student Report Card</B></h6>
          </th></tr>
          <tr><th rowspan="3" class="text-center">Subject</th>
          <th colspan="3" class="text-center">First Semester</th>

          <th colspan="3" class="text-center">Second Semester</th>
          <th rowspan="3" class="text-center">Yearly Average</th></tr>';
          $output.='<th colspan="2" class="text-center">Quarters</th>
          <th  class="text-center" rowspan="2"><b>1<sup>st</sup> Semester Average</b></th>

          <th colspan="2" class="text-center">Quarters</th><td class="text-center" rowspan="2"><b>2<sup>nd</sup> Semester Average</b></td>';
          $output.='<tr><td class="text-center">1<sup>st</sup> Quarter</td>';
          $output.='<td class="text-center">2<sup>nd</sup> Quarter</td>
          
          <td class="text-center">3<sup>rd</sup> Quarter</td>
          <td class="text-center">4<sup>th</sup> Quarter</td>
          </tr>';
          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' 
          and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr><td style="white-space: nowrap"><B>'.$fetchSubject->subject.'</B></td>';/*Subject List*/

              /*fetch quarter 1 result starts*/
              $queryReportCardQ1=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter1' and subject='$subject' order by subjorder ");
              if($queryReportCardQ1->num_rows()>0){
                foreach ($queryReportCardQ1->result() as $fetchResult1) {
                  $result1=$fetchResult1->total;
                  if($fetchResult1->total=='' || $fetchResult1->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result1=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result1,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result1,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }

               /*fetch quarter 2 result starts*/
              $queryReportCardQ2=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter2' and subject='$subject' order by subjorder ");
              if($queryReportCardQ2->num_rows()>0){
                foreach ($queryReportCardQ2->result() as $fetchResult2) {
                  $result2=$fetchResult2->total;
                  if($fetchResult2->total=='' || $fetchResult2->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result2=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result2,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result2,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }

              /*1st Semester calculation starts*/
              if($queryReportCardQ2->num_rows()>0){
                $sem1Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter1') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter2') as total ");
                foreach ($sem1Ave->result() as $fetchSem1) {
                  $resultSem1=($fetchSem1->total)/2;
                  $firstSemCheck=$resultSem1;
                  if($fetchSem1->total=='' || $fetchSem1->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($resultSem1=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$resultSem1,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$resultSem1,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              /*fetch quarter 3 result starts*/
              $queryReportCardQ3=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter3' and subject='$subject' order by subjorder ");
              if($queryReportCardQ3->num_rows()>0){
                foreach ($queryReportCardQ3->result() as $fetchResult3) {
                  $result3=$fetchResult3->total;
                  if($fetchResult3->total=='' || $fetchResult3->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($result3=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result3,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result3,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result3 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              /*$output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';*/
              /*fetch quarter 4 result starts*/
              $queryReportCardQ4=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter4' and subject='$subject' order by subjorder ");
              if($queryReportCardQ4->num_rows()>0){
                foreach ($queryReportCardQ4->result() as $fetchResult4) {
                  $result4=$fetchResult4->total;
                  if($fetchResult4->total=='' || $fetchResult4->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($result4=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result4,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result4,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result4 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              /*$output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';*/
              /*2nd Semester calculation starts*/
              if($queryReportCardQ4->num_rows()>0){
                $sem2Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter3') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter4') as total ");
                foreach ($sem2Ave->result() as $fetchSem2) {
                  $resultSem2=($fetchSem2->total)/2;
                  if($fetchSem2->total=='' || $fetchSem2->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($resultSem2=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$resultSem2,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$resultSem2,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              // $output.='<td class="text-center">-</td>';
              /*Yearly Average calculation starts*/
              if($queryReportCardQ4->num_rows()>0){
                  $sem1Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter1') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter2') as total ");
                  $rowCheckSum1=$sem1Ave->row();
                  $resultSem1=($rowCheckSum1->total)/2;
                  $firstSemCheck=$resultSem1;
                  if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                }else{
                    $YAve=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' ");
                    foreach ($YAve->result() as $fetchYA) {
                      $resultYA=($fetchYA->total)/4;
                      if($fetchYA->total=='' || $fetchYA->total<=0){
                        $output.='<td class="text-center">-</td>';
                      }else{
                        if($letter!='A'){
                          if($resultYA=='100'){
                            $output .= '<td class="text-center">'.number_format((float)$resultYA,0,'.','').'</td>';
                          }else{
                            $output .= '<td class="text-center">'.number_format((float)$resultYA,2,'.','').'</td>';
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultYA between minValue and maxiValue and academicYear='$max_year'");
                          if($queryRange->num_rows()>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center'>".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center'> -</td>";
                          }
                        }
                      }
                    }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              $output.='</tr>';
            }
          } 
          /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
          $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='totalname' and grade='$grade' and allowed='1' ");
          if($queryRankAllowed->num_rows()>0){
            $output.='<tr><td><b>Total</b></td>';
            $quarterArray1=array('Quarter1','Quarter2');
            foreach ($quarterArray1 as $quarterValue) {
              $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
              if($quartrTotal->num_rows()>0){
                foreach ($quartrTotal->result() as $totalValue) {
                  $printValue=$totalValue->total;
                  if($printValue >0){
                    $output .= '<td class="text-center"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            }
            /*Semester1 Horizontal Total calculation starts*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
              if($queryReportCardQ2->num_rows()>0){
                foreach ($quartrSem1Total->result() as $totalValueSem1) {
                  $printValueSem1=($totalValueSem1->total)/2;
                  if($printValueSem1 >0){
                    $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }

            /*Quarter3 & Quarter4 Horizontal Total calculation starts*/
            $quarterArray2=array('Quarter3','Quarter4');
            foreach ($quarterArray2 as $quarterValuee) {
              $quartrTotal2=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValuee' and onreportcard='1' and letter='#' ");
              if($quartrTotal2->num_rows()>0){
                foreach ($quartrTotal2->result() as $totalValue2) {
                  $printValue2=$totalValue2->total;
                  if($printValue2 >0){
                    $output .= '<td class="text-center"><b>'.number_format((float)$printValue2,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            }
            /*Semester2 Horizontal Total calculation starts*/
            $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1'
            and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
              if($queryReportCardQ4->num_rows()>0){
                foreach ($quartrSem2Total->result() as $totalValueSem2) {
                  $printValueSem2=($totalValueSem2->total)/2;
                  if($printValueSem2 >0){
                    $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem2,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            /*Yearly Average Horizontal Total calculation starts*/
            $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
              if($queryReportCardQ4->num_rows()>0){
                  if($resultSem2 ==0 || $resultSem1 == 0){
                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($quartrYATotal->result() as $totalValueYA) {
                      $printValueYA=($totalValueYA->total)/4;
                      if($printValueYA >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                      
                  }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            $output.='</tr>';
          }
          /*Horizontal Average calculation starts*/
          $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='averagename' and grade='$grade' and allowed='1' ");
          if($queryRankAllowed->num_rows()>0){
            $output.='<tr><td><b>Average</b></td>';
            /*1st and snd quarter calculation starts*/
            $quarterArray1=array('Quarter1','Quarter2');
            $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' 
            and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
            $subALl=$countSubject->num_rows();
            foreach ($quarterArray1 as $quarterValue) {
              if($subALl>0){
                $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
                if($quartrTotal->num_rows()>0){
                  foreach ($quartrTotal->result() as $totalValue) {
                    $printValue=($totalValue->total)/$subALl;
                    if($printValue >0){
                      $output .= '<td class="text-center"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                }else{
                  $output.='<td class="text-center">-</td>';
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            }
            /*1st Semester average starts*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1'
            and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
              if($queryReportCardQ2->num_rows()>0){
                foreach ($quartrSem1Total->result() as $totalValueSem1) {
                  $printValueSem1=(($totalValueSem1->total)/2)/$subALl;
                  if($printValueSem1 >0){
                    $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            /*3rd and 4th quarter horizontal average calculation starts*/
            $quarterArray2=array('Quarter3','Quarter4');
            foreach ($quarterArray2 as $quarterValuee) {
              if($subALl>0){
                $quartrTotal2=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValuee' and onreportcard='1' and letter='#' ");
                if($quartrTotal2->num_rows()>0){
                  foreach ($quartrTotal2->result() as $totalValue2) {
                    $printValue2=($totalValue2->total)/$subALl;
                    if($printValue2 >0){
                      $output .= '<td class="text-center"><b>'.number_format((float)$printValue2,2,'.','').'</b></td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                }else{
                  $output.='<td class="text-center">-</td>';
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            }
            /*Semester2 Horizontal Average calculation starts*/
           $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
              if($queryReportCardQ4->num_rows()>0){
                foreach ($quartrSem2Total->result() as $totalValueSem2) {
                  $printValueSem2=(($totalValueSem2->total)/2)/$subALl;
                  if($printValueSem2 >0){
                    $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem2,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              /*$output.='<td class="text-center" colspan="2">-</td>';*/
            /*Yearly Average Horizontal Average calculation starts*/
           $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
              if($queryReportCardQ4->num_rows()>0){
                  if($resultSem2 ==0 || $resultSem1 == 0){
                      $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($quartrYATotal->result() as $totalValueYA) {
                      $printValueYA=(($totalValueYA->total)/4)/$subALl;
                      if($printValueYA >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                  }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            $output.='</tr>';
          }
          /*Number of student calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">No. Of Student</b></td>';
          if($queryReportCardQ1->num_rows()>0){
            $output.='<td class="text-center">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
          if($resultSem1 <=0){
            $output.='<td class="text-center">'.$stuAll.'</td>';
            $output.='<td class="text-center">-</td>';
          }else{
            $output.='<td class="text-center">'.$stuAll.'</td>';
            $output.='<td class="text-center">'.$stuAll.'</td>';
          }
          if($queryReportCardQ3->num_rows()>0){
            $output.='<td class="text-center">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
          if($resultSem2 <=0 ){
            $output.='<td class="text-center">-</td>';
            $output.='<td class="text-center">-</td>';
          }else{
            $output.='<td class="text-center">'.$stuAll.'</td>';
            $output.='<td class="text-center">'.$stuAll.'</td>';
          }
          if($resultSem2 <=0 || $resultSem1 <=0){
            $output.='<td class="text-center">-</td>';
          }else{
            $output.='<td class="text-center">'.$stuAll.'</td>';
          }
          $output.='</tr>';
          /*student conduct calculation starts*/
          $output.='<tr>';
          /*1st and 2nd quarter conduct calculation starts*/
          $quarterArray1=array('Quarter1','Quarter2');
          $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' group by bsname order by bsname ASC ");   
          if($query_basicskill->num_rows()>0){  
            foreach ($query_basicskill->result() as $bsvalue) {
              $bsname=$bsvalue->bsname;
              $output .='<td><B>'.$bsvalue->bsname.'</B></td>';
              foreach ($quarterArray1 as $qvalue) {
                $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                if($query_bsvalue->num_rows()>0) {
                  foreach ($query_bsvalue ->result() as $bsresult) {
                    $output .='<td class="text-center">'.$bsresult->value.'</td>';
                  }
                }else {
                  $output .='<td class="text-center">-</td>';
                }
              }              
            }
          }else{
            $output .='<td class="text-center">-</td>';
            $output .='<td class="text-center">-</td>';    
          }
          $output.='<td class="text-center">-</td>';
          /*1st Semester conduct*/
          /*3rd and 4th quarter conduct*/
          $quarterArray12=array('Quarter3','Quarter4');
          $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' group by bsname order by bsname ASC ");   
          if($query_basicskill->num_rows()>0){   
            foreach ($query_basicskill->result() as $bsvalue) {
              $bsname=$bsvalue->bsname;
              foreach ($quarterArray12 as $qvalue) {
                $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                if($query_bsvalue->num_rows()>0) {
                  foreach ($query_bsvalue ->result() as $bsresult) {
                    $output .='<td class="text-center">'.$bsresult->value.'</td>';
                  }
                }else {
                  $output .='<td class="text-center">-</td>';
                }
              }              
            }
          }else{
            $output .='<td class="text-center">-</td>';  
            $output .='<td class="text-center">-</td>';
          }
          /*2nd Semester conduct*/
          $output.='<td class="text-center">-</td>';
          /*yearly conduct*/
          $output.='<td class="text-center">-</td>';
          $output.='</tr>';
          /*Absetn days calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">Absence Days</b></td>';
          /*1st and 2nd quarter absence days*/
          $quarterArray1=array('Quarter1','Quarter2');
          foreach ($quarterArray1 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center"><B>'.$absent->att.'</B></td>';
                }
                else{
                  $output .= '<td class="text-center"><B>-</B></td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            }else{
              $output.='<td class="text-center">-</td>';
            }

          }
          /*1st semester absent days*/
          
          $tot1stSem=0;
          if($queryReportCardQ2->num_rows()>0){
              foreach ($quarterArray1 as $qvalue) {
                $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
                if($queryTerm->num_rows()>0){
                $qRow=$queryTerm->row();
                $date1 =$qRow->startdate;
                $date2 =$qRow->endate;
                $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
                $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
                $startDate1= $changeDate1->format('Y-m-d');
                $endDate1= $changeDate2->format('Y-m-d');
                $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
                and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
                  foreach ($query_total_absent->result() as $absent){
                    if($absent->att>0)
                    {
                        $tot1stSem=$tot1stSem + $absent->att;
                    }
                  }
                }
              }
              if($tot1stSem > 0){
                   $output .= '<td class="text-center"><B>'.$tot1stSem.'</B></td>';
              }else{
                   $output .= '<td class="text-center"><B>-</B></td>';
              }
          }
          
          /*quarter 3 and 4th quarter absent days*/
          $quarterArray3=array('Quarter3','Quarter4');
          foreach ($quarterArray3 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center"><B>'.$absent->att.'</B></td>';
                }
                else{
                  $output .= '<td class="text-center"><B>-</B></td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
             }else{
              $output.='<td class="text-center">-</td>';
            }
          }
          /*2nd semester absent days*/
          $tot2stSem=0;
          foreach ($quarterArray3 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
             if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot2stSem=$tot2stSem + $absent->att;
                }
              }
            }

          }
          if($tot2stSem > 0){
               $output .= '<td class="text-center"><B>'.$tot2stSem.'</B></td>';
          }else{
               $output .= '<td class="text-center"><B>-</B></td>';
          }
        
          /* Yearly absent days*/
          if($queryReportCardQ4->num_rows()>0){
                if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center">-</td>';
               }else{
                $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' ");
                if($queryTotalAbsent->num_rows()>0){
                  foreach ($queryTotalAbsent->result() as $absent){
                    if($absent->att>0)
                    {
                      $output .= '<td class="text-center"><B>'.$absent->att.'</B></td>';
                    }
                    else{
                      $output .= '<td class="text-center"><B>-</B></td>';
                    }
                  }
                }else{
                  $output.='<td class="text-center">-</td>';
                }
               }
            
          }else{
            $output.='<td class="text-center">-</td>';
          }
          $output.='</tr>';
          $output.='</table></div>
           <div class="row">
            <div class="col-6 col-md-6">
              የርዕሰ መምህሩ ስም _________________<br>
              Principals Name 
            </div>
            <div class="col-6 col-md-6">
              ፊርማ _______________</br>
              Sign.__________________ 
            </div>
          </div>';
          $output.='</div>';/*result table closed*/
          $output.= '<div class="col-lg-6 col-md-6">';
          $output.='<div class="row"><div class="col-lg-12">';
          $query_name = $this->db->query("select * from school");
            $row_name = $query_name->row();
            $school_name=$row_name->name;
            $address=$row_name->address;
            $phone=$row_name->phone;
            $website=$row_name->website;
            $email=$row_name->email;
            $logo=$row_name->logo;
            $output.='<h5 class="text-center text-info"><b><u>Homeroom Teacher Comments & Recommendations</u></b></h5>
                  <div class="row">
                  <div class="col-lg-12"><b id="ENS">Students Name: '.ucfirst(strtolower($fetchStudent->fname)).' '.ucfirst(strtolower($fetchStudent->mname)).' '.ucfirst(strtolower($fetchStudent->lname)).'</b></div>
                    <div class="col-lg-12 col-12 reportcardView">';
                    $queryHoomRoom=$this->db->query("select u.fname,u.mname from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
                    if($queryHoomRoom->num_rows()>0){
                      $rowHommeRoom=$queryHoomRoom->row_array();
                      $tfName=$rowHommeRoom['fname'];
                      $tmName=$rowHommeRoom['mname'];
                    }else{
                      $tfName='------';
                      $tmName='------';
                    }
                    $quarterArray12=array('Quarter1','Quarter2');
                    $quarterArray34=array('Quarter3','Quarter4');
                      $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                      $subALl=$countSubject->num_rows();
                        if($subALl>0){
                          $output.="<p class='text-center'><b>1<sup>st</sup> Semester</b></p>";
                          
                          $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                          if($quartrTotal->num_rows()>0){
                            foreach ($quartrTotal->result() as $totalValue) {
                              $printValue1=($totalValue->total)/$subALl;

                              $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue1 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                              if($printValue1 >0 && $reportCardComments->num_rows()>0){
                                foreach($reportCardComments->result() as $commentValue){
                                  $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                                }
                                
                              }else{
                                $output.='1<sup>st</sup> Quarter______________________________________ _______________________  ____________________________________________ <br>';
                              }
                            }
                          }else{
                            $output.=' ';
                          }
                        
                          $output.="
                          <p>HRT's Name: <u><b>".$tfName." ".$tmName."</b></u> Sig.________________________ </p>
                           Parent's Name: <u><b>".$mname." " .$lname." </b></u> Sig.________________________ ";
                        $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
                          if($quartrTotal->num_rows()>0){
                            foreach ($quartrTotal->result() as $totalValue) {
                              $printValue1=($totalValue->total)/$subALl;

                              $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue1 between mingradevalue and maxgradevalue and academicYear='$max_year'");
                                $output.='<br>2<sup>nd</sup> Quarter ';
                              if($printValue1 >0 && $reportCardComments->num_rows()>0){
                                foreach($reportCardComments->result() as $commentValue){
                                  $output .= ' <u>'.$commentValue->commentvalue.'</u><br>';
                                }
                                
                              }else{
                                $output.='______________________________________ _______________________  ____________________________________________ <br>';
                              }
                            }
                          }else{
                            $output.=' ';
                          }
                        
                          $output.="
                          <p>HRT's Name: <u><b>".$tfName." ".$tmName."</b></u> Sig.________________________ </p>
                           Parent's Name: <u><b>".$mname." " .$lname." </b></u> Sig.________________________ ";
                      
                    }
                    $output.='</div><div class="col-lg-12 col-12 reportcardView">';
                    if($subALl>0){
                        $output.="<p class='text-center'><b>2<sup>nd</sup> Semester</b></p>";
                        
                        $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' ");
                        if($quartrTotal->num_rows()>0){
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue2=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue2 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                            if($printValue2 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='<br>3<sup>rd</sup> Quarter______________________________________ _______________________  ____________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      
                    $output.="
                      <p>HRT's Name: <u><b>".$tfName." ".$tmName."</b></u> Sig.________________________ </p>
                       Parent's Name: <u><b>".$mname." " .$lname." </b></u> Sig.________________________ 
                    ";
                    $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                        if($quartrTotal->num_rows()>0){
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue2=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue2 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                            if($printValue2 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='<br>4<sup>th</sup> Quarter______________________________________ _______________________  ____________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      
                    $output.="
                      <p>HRT's Name: <u><b>".$tfName." ".$tmName."</b></u> Sig.________________________ </p>
                       Parent's Name: <u><b>".$mname." " .$lname." </b></u> Sig.________________________ 
                    ";
                  
                  }
                    $output.='</div><div class="col-lg-12 col-12">
                    <p class="text-center"><u>የማርክ አሰጣጥ</u><br>
                    <u>Method of Marking</u></p>';
                    $queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade'  ");
                    if($queryRangeValue->num_rows()>0){
                      $output.= '<div id="ENS" class="table-responsive">
                      <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
                      $output .='<tr><th class="text-center" colspan="3">Letter Grade Evaluation Key</th></tr>';
                      foreach ($queryRangeValue->result() as $rangeValue) {
                        $output .='<tr><td class="text-center">'.$rangeValue->minValue.' - '.$rangeValue->maxiValue.'</td>';
                        $output .='<td class="text-center">'.$rangeValue->letterVal.'</td> </tr>';
                      }
                      $output.= '</table></div>';
                    }
                    $output.='</div>
                  </div>';
                $printValue=0;
              $output .='</div>';

          $output .='</div><br>';/*basic skill column closed*/
          $output.='</div><div class="dropdown-divider2"></div></div>';/*class row closed*/
          if($includeBackPage=='1')
          {
            $query_name = $this->db->query("select * from school");
            $row_name = $query_name->row();
            $school_name=$row_name->name;
            $address=$row_name->address;
            $phone=$row_name->phone;
            $website=$row_name->website;
            $email=$row_name->email;
            $logo=$row_name->logo;
            $output.='<div style="width:100%;height:auto;page-break-inside:avoid; page-break-after:always;display: block; ">
            <div class="row" id="ENS">
              <div class="col-lg-6 col-6">
              <h3 class="text-center text-info"><strong><u>Over all Activities </u></strong></h3>';
              
              $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
          if($queryCategory->num_rows()>0){
            $output.= '<div class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $dateYear=date('Y');
            $output .='<tr><th colspan="5" class="text-center">2021/22 G.C '.$max_year.' E.C Basic Skills and Behaviour Progress Report</th></tr>';
            $output .='<tr><th>Evaluation Area</th>';
            $quarterArrayQ=array('Quarter1','Quarter2','Quarter3','Quarter4');
            foreach ($quarterArrayQ as $qvalue) 
            {
              $output .='<th class="text-center">'.$qvalue.'</th>';
            }
            foreach ($queryCategory->result() as $bscatvalue) {
              $bscategory=$bscatvalue->bscategory;
              $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and bsname!='Conduct' and bscategory='$bscategory' order by bsname ASC ");       
              $output .='<tr><th colspan="5" id="BGS" class="text-center">'.$bscategory.'</th>';
              foreach ($query_basicskill->result() as $bsvalue) {
                $bsname=$bsvalue->bsname;
                $output .='<tr><td>'.$bsvalue->bsname.'</td>';
                foreach ($quarterArrayQ as $qvalue) {
                  $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                  if($query_bsvalue->num_rows()>0) {
                    foreach ($query_bsvalue ->result() as $bsresult) {
                      $output .='<td class="text-center">'.$bsresult->value.'</td>';
                    }
                  }else {
                    $output .='<td class="text-center">-</td>';
                  }
                } 
                $output .='</tr>';
              }
              
            }
            $output .='</table></div><br>';/*basic skill table closed*/
          }
              $queryEvaKey=$this->db->query("select * from bstype where academicyear='$max_year' and btgrade='$grade' ");
              if($queryEvaKey->num_rows()>0){
                $output.= '<div class="row">';
                $output .='<div class="col-lg-12 col-12 text-center" id="BGS"><h4 style="font-family:century_gothicbold;">GRADING CODE</h4></div>';
                foreach ($queryEvaKey->result() as $keyVValue) {
                  $output .='<div class="col-lg-6 col-12"><h5 style="font-family:century_gothicbold;">'.$keyVValue->bstype.'=> '.$keyVValue->bsdesc.'</h5></div>';
                }
                $output .='</div>';/*Evaluation key table closed*/
              }else{
                $output .='No Evaluation Key found';
              }
                $output.='<div class="StudentViewTextInfo">
                  <p class="text-center"><h3>Vision of Seattle Academy</h3></p>
                    <small><i class="fas fa-check-circle"></i> Creating a conducive environment where every member of the school community is accountable so as to provide quality education that makes every student best achiever and successful. </small>
                </div>
              </div>
              <div class="col-lg-6 col-6">
                <div class="row">
                  <div class="col-lg-12 col-12">';
                  $output.='<p class="text-center"><img class="text-center" src="'.base_url().'/logo/'.$logo.'" style="height:150px;width:150px;border:1px solid #fff; -webkit-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;border-radius:10px" ></p>
                  </div>
                  <div class="col-lg-12 col-12">
                    <p class="text-center"><i class="fas fa-phone-square"></i>: +251-11-888-35-04/+9-03-21-44444<br><i class="fas fa-envelope"></i> '.$email.'<br><i class="fas fa-envelope"></i> 28643   Code 1000<br>Website address- www.seattleacademyethiopia.com<br>Addis Ababa, Ethiopia</p>
                      <h4 class="text-center"><u><b>የተማሪ ውጤት መግለጫ<br>Student’s Report Card</b></u></h4>
                    <div class="row">
                      <div class="col-lg-9 col-12">
                        <div class="row">
                          <div class="col-lg-12 col-12">
                            የተማሪዉ ስም
                            <p>Student Name:-<b> <u>'.ucfirst(strtolower($fname)).' '.ucfirst(strtolower($mname)).' '.ucfirst(strtolower($lname)).'</u></b></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            ዕድሜ <p>Age <u>'.$age.' </u></p> 
                          </div> 
                          <div class="col-lg-6 col-6">
                            ፆታ <p>Sex <u>'.$gender.' </u></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            <p>Grade <u>'.$grade.' </u></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            <p>Section<u> '.$section.'</u> </p>
                          </div> 
                          <div class="col-lg-6 col-6">
                            ክ/ከተማ<p>Subcity <u>'.$addresss.' </u></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            ወረዳ<p>Woreda<u> '.$woreda.'</u> </p>
                          </div> 
                          <div class="col-lg-12 col-12">
                            የትምህርት ዘመን <p>Academic Year <u>'.$max_year.'E.C ('.$gYearName.'G.C) </u></p>
                          </div>
                          <div class="col-lg-12 col-12">
                            ወደ ______ ክፍል ተዛውሯል/ለች፡፡ <p>Promoted to Grade</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-12">';
                        if($profile == ''){
                          $output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
                        }else{
                          $output.='<img alt="Photo" src="'.base_url().'/profile/'.$profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
                        }
                      $output.='</div>
                    </div>
                  </div>
                </div>
              </div>

              
              </div>
            </div>';
          }
          $output.='<div class="dropdown-divider2"></div>
         <br>';
        }
      } 
    }
    return $output;
  } 


  function roster($max_year,$gradesec,$branch,$page)
  {
    $query_student=$this->db->query(" Select * from users where gradesec='$gradesec' and branch='$branch' and status='Active' and academicyear='$max_year' and isapproved='1' and usertype='Student' group by id order by fname,mname,lname ASC ");
    $query_name = $this->db->query("select * from school");
    $row_name = $query_name->row();//school info
    $school_name=$row_name->name;
    $address=$row_name->address;
    $phone=$row_name->phone;
    $website=$row_name->website;
    $email=$row_name->email;

    $queryac = $this->db->query("select max(year_name) as ay from academicyear");
    $rowac = $queryac->row();//academic year info
    $yearname=$rowac->ay;

    $row_gradesec = $query_student->row();//grade and sec info
    $grade_name=$row_gradesec->grade;
    $section_name=$row_gradesec->section;
    $gradeSec=$row_gradesec->gradesec;
    $totalStudent=$query_student->num_rows();

    $output=' <div class="text-center" id="ENS" height="100%" style="width:100%;height:100%;page-break-inside:avoid;display:flex; flex-direction:column; justify-content:center;min-height:100vh;">
      <h1>ROSTER SUMMARY</h1>
      <h3>School: '.$school_name.' <br></h3>
      <h5> Campus : '.$branch.'(Kaliti) </h5>
      <h5> Academic Year : '.$max_year.'E.C</h5>
      <h5>Grade & Section: '.$gradeSec.' </h5>
      <h5>Season: Yearly Average </h5>
      <h6>Total No. Of Student : '.$totalStudent.'</h6>
      </b>
    </div>';
    
    $output .='<div class="table-responsive" width="100%" height="100%">
        <table id="ENS" class="tabler table-borderedr" width="100%" cellspacing="5" cellpadding="5">
        
        <tr><td class="text-center rotateJossRoster"><div> No.</div></td>
        <td class="text-center rotateJossRoster"><div>Students Name</div></td> 
        <td class="text-center rotateJossRoster"><div>Gender</div></td> 
        <td class="text-center rotateJossRoster"><div>Season</div></td>';
        $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
        foreach ($query_result ->result() as $rvalue)
        {
          $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
        }
        $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Conduct</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
        $output .='</tr>';
    $stuNo=1;
    foreach ($query_student->result() as $row) 
    {
      $stuid=$row->id;
      $username1=$row->username;
      $grade_sec=$row->gradesec;
      $grade=$row->grade;
      $query_quarter=array('Quarter1','Quarter2');
      $output .='<tr><td rowspan="8">'.$stuNo.'.</td>
      <td rowspan="8">'.$row->fname.' '.$row->mname.' '.$row->lname.'</td> 
      <td rowspan="8">'.$row->gender.'</td>';
      foreach ($query_quarter as $qvalue) 
      {
        $quarter=$qvalue;
        $output .='<tr> <td>'.$qvalue.'</td>';
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->subject;
          $query_quarter_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and subject='$subject_mark' group by subject ");
          if($query_quarter_result->num_rows()>0){
            foreach ($query_quarter_result->result() as $quarter_result) 
            {
              $letter=$quarter_result->letter;
              $result=$quarter_result->total;
              if($letter!='A')
              {
                $output .='<td class="text-center">'.$result.'</td>';
              }
              else{
                $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                if($queryRange->num_rows()>0){
                  foreach ($queryRange->result() as $letterValue) {
                    $letterVal=$letterValue->letterVal;
                    $output.= "<td class='text-center'>".$letterVal."</td>";
                  }
                }else{
                  $output.= "<td class='text-center'> -</td>";
                }
              }
            }
          }else{
            $output.= "<td class='text-center'> -</td>";
          }
        }
        $query_quarter_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and quarter ='$quarter' and onreportcard='1' and mergedname='' group by quarter order by subject ASC ");
        $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
        $total_subject=$count_subject->num_rows();
        if($query_quarter_total->num_rows()>0){
          foreach ($query_quarter_total->result() as $qtvalue)
          {
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total,2,'.','').'</td>';//Each quarter Total
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</td>';//Each quarter Average
          }
        }else{
          $output .= '<td class="text-center">-</td>';//Each quarter total
          $output .= '<td class="text-center">-</td>';//Each quarter Average
        }
        
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and mergedname='' and quarter='$quarter' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and mergedname='' and grade='$gradesec' group by grade ");
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
        }
       

        //Each quarter conduct starts
        $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' and bsname='Conduct' group by stuid ");
          if($eachQuarterBasicskill->num_rows()>0){
            $valueBS=$eachQuarterBasicskill->row();
            $getValue=$valueBS->value;
            $output.='<td class="text-center">'.$getValue.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        //Each quarter Absence starts
        
        $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$quarter' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center">'.$absent->att.'</td>';
                }
                else{
                  $output .= '<td class="text-center">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            }else{
              $output.='<td class="text-center">-</td>';
            }
        $output .='</tr>';
      }
      /*1st semester calculation starts*/
      $output .='<tr id="BGS"><td>Semester1 Average </td>';
      $queryCheckSem1=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter2'  ");
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter1' or stuid='$stuid' and subject='$subject_mark' and quarter='Quarter2'  ");
        if($queryCheckSem1->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/2;
            if($letter!='A')
            {
              $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
            }
            else{
              $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
              if($queryRange->num_rows()>0){
                foreach ($queryRange->result() as $letterValue) {
                  $letterVal=$letterValue->letterVal;
                  $output.= "<td class='text-center'>".$letterVal."</td>";
                }
              }else{
                $output.= "<td class='text-center'> -</td>";
              }
            }
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' and mergedname='' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' and mergedname='' ");
      if($queryCheckSem1->num_rows()>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=($totalValueSem2->total)/2;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' and mergedname='' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' and mergedname='' ");
      if($queryCheckSem1->num_rows()>0 && $total_subject>0 ){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/2)/$total_subject;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      /*Rank calculation starts*/
      $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='Quarter1' and onreportcard='1' or grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='Quarter2' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter1' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' or stuid='$stuid' and quarter= 'Quarter2' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' group by grade ");
      if($queryCheckSem1->num_rows()>0){
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $output.='<td class="text-center">-</td>';/*conduct calculation starts*/
      /*1st Semester absence calculation starts*/
      $tot1stSem=0;
      if($queryCheckSem1->num_rows()>0){
      $quarterArray1=array('Quarter1','Quarter2');
          foreach ($quarterArray1 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center" colspan="2">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" colspan="2">-</td>';
          }
        }else{
          $output .= '<td class="text-center" colspan="2">-</td>';
        }
      $output.='</tr>';
      /*3rd and 4th calculation starts*/
      $queryQuarter=array('Quarter3','Quarter4');
      foreach ($queryQuarter as $qvalue) 
      {
        $quarter=$qvalue;
        $output .='<tr> <td>'.$qvalue.'</td>';
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->subject;
          $query_quarter_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and subject='$subject_mark' group by subject ");
          if($query_quarter_result->num_rows()>0){
            foreach ($query_quarter_result->result() as $quarter_result) 
            {
              $letter=$quarter_result->letter;
              $result=$quarter_result->total;
              if($letter!='A')
              {
                $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
              }
              else{
                $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                if($queryRange->num_rows()>0){
                  foreach ($queryRange->result() as $letterValue) {
                    $letterVal=$letterValue->letterVal;
                    $output.= "<td class='text-center'>".$letterVal."</td>";
                  }
                }else{
                  $output.= "<td class='text-center'> -</td>";
                }
              }
            }
          }else{
            $output.= "<td class='text-center'> -</td>";
          }
        }
        $query_quarter_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and quarter ='$quarter' and onreportcard='1' group by quarter order by subject ASC ");
        $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and academicyear='$max_year' group by subject ");
        $total_subject=$count_subject->num_rows();
        if($query_quarter_total->num_rows()>0){
          foreach ($query_quarter_total->result() as $qtvalue)
          {
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total,2,'.','').'</td>';//Each quarter Total
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</td>';//Each quarter Average
          }
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='$quarter' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' group by grade ");
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
        }
        }else{
          $output .= '<td class="text-center">-</td>';//Each quarter total
          $output .= '<td class="text-center">-</td>';//Each quarter Average
          $output .= '<td class="text-center">-</td>';//Each quarter rank
        }
        
        //Each quarter conduct starts
        $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' and bsname='Conduct' group by stuid ");
          if($eachQuarterBasicskill->num_rows()>0){
            $valueBS=$eachQuarterBasicskill->row();
            $getValue=$valueBS->value;
            $output.='<td class="text-center">'.$getValue.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        //Each quarter Absence
        $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$quarter' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center">'.$absent->att.'</td>';
                }
                else{
                  $output .= '<td class="text-center">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            
          /*$query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where attt.stuid='$username1'
          and attt.absentdate between se.startdate and se.endate and se.term='$quarter' and attt.academicyear='$max_year' and se.Academic_year='$max_year' ");
        if($query_total_absent->num_rows()>0){
          foreach ($query_total_absent->result() as $absent){
            if($absent->att>0)
            {
              $output .= '<td class="text-center"><B>'.$absent->att.'</B></td>';
            }
            else{
              $output .= '<td class="text-center"><B>-</B></td>';
            }
          }
        }else{
          $output.='<td class="text-center">-</td>';
        }*/
        $output .='</tr>';
      }

      /*2nd semester calculation starts*/
      $output .='<tr id="BGS"><td>Semester2 Average </td>';
      $queryCheckSem2=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter4'  ");
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter3' or stuid='$stuid' and subject='$subject_mark' and quarter='Quarter4'  ");
        if($queryCheckSem2->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/2;
            if($letter!='A')
            {
              $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
            }
            else{
              $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
              if($queryRange->num_rows()>0){
                foreach ($queryRange->result() as $letterValue) {
                  $letterVal=$letterValue->letterVal;
                  $output.= "<td class='text-center'>".$letterVal."</td>";
                }
              }else{
                $output.= "<td class='text-center'> -</td>";
              }
            }
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
      
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
      if($queryCheckSem2->num_rows()>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/2);
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
      if($queryCheckSem2->num_rows()>0 && $total_subject>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/2)/$total_subject;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='Quarter3' and onreportcard='1' or grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='Quarter4' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter3' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' or stuid='$stuid' and quarter= 'Quarter4' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' group by grade ");
        if($query_rank->num_rows()>0){
          foreach ($query_rank->result() as $rvalue) {
            $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
          }
        }else{
          $output.='<td class="text-center">-</td>';
        }   
      }else{
        $output.='<td class="text-center">-</td>';
        $output.='<td class="text-center">-</td>';
      }
      $output.='<td class="text-center">-</td>';/*conduct calculation starts*/
     
      /*2nd Semester absence calculation starts*/
      
      $tot1stSem=0;
      if($queryCheckSem2->num_rows()>0){
      $quarterArray1=array('Quarter3','Quarter4');
          foreach ($quarterArray1 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center" colspan="2">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" colspan="2">-</td>';
          }
        }else{
          $output .= '<td class="text-center" colspan="2">-</td>';
        }
     /* $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='Quarter3' and attt.academicyear='$max_year' and se.Academic_year='$max_year' or attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='Quarter4' and attt.academicyear='$max_year' and se.Academic_year='$max_year' ");
      if($queryTotalAbsent->num_rows()>0){
        foreach ($queryTotalAbsent->result() as $absent){
          if($absent->att>0)
          {
            $output .= '<td class="text-center"><B>'.$absent->att.'</B></td>';
          }
          else{
            $output .= '<td class="text-center"><B>-</B></td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }*/
      $output.='</tr>';

      /*Yearly Average calculation starts*/
      $output .='<tr id="BGS"><td>Yearly Average </td>';

      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' ");
        if($queryCheckSem2->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/4;
            if($letter!='A')
            {
              $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
            }
            else{
              $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
              if($queryRange->num_rows()>0){
                foreach ($queryRange->result() as $letterValue) {
                  $letterVal=$letterValue->letterVal;
                  $output.= "<td class='text-center'>".$letterVal."</td>";
                }
              }else{
                $output.= "<td class='text-center'> -</td>";
              }
            }
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
      if($queryCheckSem2->num_rows()>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/4);
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
      if($queryCheckSem2->num_rows()>0 && $total_subject>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/4)/$total_subject;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' group by grade ");
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
        }
      }else{
        $output.='<td class="text-center">-</td>';
        $output.='<td class="text-center">-</td>';
      }
      $output.='<td class="text-center">-</td>';/*conduct calculation starts*/
      /*yearly absence calculation starts*/
      $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' ");
      if($queryCheckSem2->num_rows()>0){
        foreach ($queryTotalAbsent->result() as $absent){
          if($absent->att>0)
          {
            $output .= '<td class="text-center">'.$absent->att.'</td>';
          }
          else{
            $output .= '<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $totSubject=$this->db->query("select subject from reportcard".$gradesec.$max_year." where  onreportcard='1' and grade='$gradesec' group by subject ");
      $numSubject=$totSubject->num_rows();
      
      $totStudent=$this->db->query("select id from users where  isapproved='1' and status='Active' and gradesec='$gradesec' and academicyear='$max_year' and branch='$branch' group by id ");
      $numStudent=$totStudent->num_rows();
      if($stuNo%$page === 0 && $stuNo>=$page){
        $output .='<tr style="page-break-after: always; page-break-inside: avoid;
        page-break-before: avoid;">';
        $output.='<td colspan="4">Home Room Teachers Signature .______________________.<br>
        Date._____________________.</td>';
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="5">Record Office Signature._____________________. <br>
        Date.________________________. </td></tr></table> </div>';
        if($stuNo<$numStudent){
            $output .='<div class="table-responsive" width="100%" height="100%">
            <table id="ENS" class="tabler table-borderedr" width="100%" cellspacing="5" cellpadding="5">
            
            <tr><td class="text-center rotateJossRoster"><div> No.</div></td>
            <td class="text-center rotateJossRoster"><div>Students Name</div></td> 
            <td class="text-center rotateJossRoster"><div>Gender</div></td> 
            <td class="text-center rotateJossRoster"><div>Season</div></td>';
            $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
            foreach ($query_result ->result() as $rvalue)
            {
              $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
            }
            $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Conduct</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
            $output .='</tr>';
        }
      }else{
        $output .='</tr>';
      }
      $stuNo++;
    }
    if($numStudent%$page!==0){
        $output.='<tr><td colspan="4">Home Room Teachers Signature .______________________.<br>
        Date._____________________.</td>';
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="5">Record Office Signature._____________________. <br>
        Date.________________________. </td></tr>';
    }
    $output .=' </table> </div>';
    return $output;
  }
  function rosterNumber($max_year,$gradesec,$branch,$page)
  {
    $query_student=$this->db->query(" Select * from users where gradesec='$gradesec' and branch='$branch' and status='Active' and academicyear='$max_year' and isapproved='1' and usertype='Student' group by id order by fname,mname,lname ASC ");
    $query_name = $this->db->query("select * from school");
    $row_name = $query_name->row();//school info
    $school_name=$row_name->name;
    $address=$row_name->address;
    $phone=$row_name->phone;
    $website=$row_name->website;
    $email=$row_name->email;

    $queryac = $this->db->query("select max(year_name) as ay from academicyear");
    $rowac = $queryac->row();//academic year info
    $yearname=$rowac->ay;

    $row_gradesec = $query_student->row();//grade and sec info
    $grade_name=$row_gradesec->grade;
    $section_name=$row_gradesec->section;
    $gradeSec=$row_gradesec->gradesec;
    $totalStudent=$query_student->num_rows();

    $output=' <div class="text-center" id="ENS" height="100%" style="width:100%;height:100%;page-break-inside:avoid;display:flex; flex-direction:column; justify-content:center;min-height:100vh;">
      <h1>ROSTER SUMMARY</h1>
      <h3>School: '.$school_name.' <br></h3>
      <h5> Campus : '.$branch.'(Kaliti) </h5>
      <h5> Academic Year : '.$max_year.'E.C</h5>
      <h5>Grade & Section: '.$gradeSec.' </h5>
      <h5>Season: Yearly Average </h5>
      <h6>Total No. Of Student : '.$totalStudent.'</h6>
      </b>
    </div>';
    
    $output .='<div class="table-responsive" width="100%" height="100%">
        <table id="ENS" class="tabler table-borderedr" width="100%" cellspacing="5" cellpadding="5">
        
        <tr><td class="text-center rotateJossRoster"><div> No.</div></td>
        <td class="text-center rotateJossRoster"><div>Students Name</div></td> 
        <td class="text-center rotateJossRoster"><div>Gender</div></td> 
        <td class="text-center rotateJossRoster"><div>Season</div></td>';
        $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
        foreach ($query_result ->result() as $rvalue)
        {
          $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
        }
        $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Conduct</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
        $output .='</tr>';
    $stuNo=1;
    foreach ($query_student->result() as $row) 
    {
      $stuid=$row->id;
      $username1=$row->username;
      $grade_sec=$row->gradesec;
      $grade=$row->grade;
      $query_quarter=array('Quarter1','Quarter2');
      $output .='<tr><td rowspan="8">'.$stuNo.'.</td>
      <td rowspan="8">'.$row->fname.' '.$row->mname.' '.$row->lname.'</td> 
      <td rowspan="8">'.$row->gender.'</td>';
      foreach ($query_quarter as $qvalue) 
      {
        $quarter=$qvalue;
        $output .='<tr> <td>'.$qvalue.'</td>';
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->subject;
          $query_quarter_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and subject='$subject_mark' group by subject ");
          if($query_quarter_result->num_rows()>0){
            foreach ($query_quarter_result->result() as $quarter_result) 
            {
              $letter=$quarter_result->letter;
              $result=$quarter_result->total;
              $output .='<td class="text-center">'.$result.'</td>';
            }
          }else{
            $output.= "<td class='text-center'> -</td>";
          }
        }
        $query_quarter_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and onreportcard='1' and mergedname='' group by quarter order by subject ASC ");
        $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
        $total_subject=$count_subject->num_rows();
        if($query_quarter_total->num_rows()>0){
          foreach ($query_quarter_total->result() as $qtvalue)
          {
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total,2,'.','').'</td>';//Each quarter Total
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</td>';//Each quarter Average
          }
        }else{
          $output .= '<td class="text-center">-</td>';//Each quarter total
          $output .= '<td class="text-center">-</td>';//Each quarter Average
        }
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and onreportcard='1' and mergedname='' and quarter='$quarter' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and onreportcard='1' and mergedname='' and grade='$gradesec' group by grade ");
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
        }
        //Each quarter conduct starts
        $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' and bsname='Conduct' group by stuid ");
          if($eachQuarterBasicskill->num_rows()>0){
            $valueBS=$eachQuarterBasicskill->row();
            $getValue=$valueBS->value;
            $output.='<td class="text-center">'.$getValue.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        //Each quarter Absence starts
        
        $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$quarter' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center">'.$absent->att.'</td>';
                }
                else{
                  $output .= '<td class="text-center">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            }else{
              $output.='<td class="text-center">-</td>';
            }
        $output .='</tr>';
      }
      /*1st semester calculation starts*/
      $output .='<tr id="BGS"><td>Semester1 Average </td>';
      $queryCheckSem1=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter2'  ");
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter1' or stuid='$stuid' and subject='$subject_mark' and quarter='Quarter2'  ");
        if($queryCheckSem1->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/2;
            $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and mergedname='' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and mergedname='' ");
      if($queryCheckSem1->num_rows()>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=($totalValueSem2->total)/2;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and mergedname='' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and mergedname='' ");
      if($queryCheckSem1->num_rows()>0 && $total_subject>0 ){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/2)/$total_subject;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      /*Rank calculation starts*/
      $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and quarter='Quarter1' and onreportcard='1' or grade='$gradesec' and rpbranch='$branch' and quarter='Quarter2' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter1' and rpbranch='$branch' and academicyear='$max_year' and grade='$gradesec' and onreportcard='1' or stuid='$stuid' and quarter= 'Quarter2' and rpbranch='$branch' and academicyear='$max_year' and grade='$gradesec' and onreportcard='1' group by grade ");
      if($queryCheckSem1->num_rows()>0){
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $output.='<td class="text-center">-</td>';/*conduct calculation starts*/
      /*1st Semester absence calculation starts*/
      $tot1stSem=0;
      if($queryCheckSem1->num_rows()>0){
      $quarterArray1=array('Quarter1','Quarter2');
          foreach ($quarterArray1 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center" colspan="2">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" colspan="2">-</td>';
          }
        }else{
          $output .= '<td class="text-center" colspan="2">-</td>';
        }
      $output.='</tr>';
      /*3rd and 4th calculation starts*/
      $queryQuarter=array('Quarter3','Quarter4');
      foreach ($queryQuarter as $qvalue) 
      {
        $quarter=$qvalue;
        $output .='<tr> <td>'.$qvalue.'</td>';
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->subject;
          $query_quarter_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and subject='$subject_mark' group by subject ");
          if($query_quarter_result->num_rows()>0){
            foreach ($query_quarter_result->result() as $quarter_result) 
            {
              $letter=$quarter_result->letter;
              $result=$quarter_result->total;
              $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
            }
          }else{
            $output.= "<td class='text-center'> -</td>";
          }
        }
        $query_quarter_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and onreportcard='1' group by quarter order by subject ASC ");
        $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and onreportcard='1' and academicyear='$max_year' group by subject ");
        $total_subject=$count_subject->num_rows();
        if($query_quarter_total->num_rows()>0){
          foreach ($query_quarter_total->result() as $qtvalue)
          {
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total,2,'.','').'</td>';//Each quarter Total
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</td>';//Each quarter Average
          }
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and quarter='$quarter' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and grade='$gradesec' and onreportcard='1' group by grade ");
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
        }
        }else{
          $output .= '<td class="text-center">-</td>';//Each quarter total
          $output .= '<td class="text-center">-</td>';//Each quarter Average
          $output .= '<td class="text-center">-</td>';//Each quarter rank
        }
        
        //Each quarter conduct starts
        $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' and bsname='Conduct' group by stuid ");
          if($eachQuarterBasicskill->num_rows()>0){
            $valueBS=$eachQuarterBasicskill->row();
            $getValue=$valueBS->value;
            $output.='<td class="text-center">'.$getValue.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        //Each quarter Absence
        $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$quarter' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center">'.$absent->att.'</td>';
                }
                else{
                  $output .= '<td class="text-center">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            }else{
              $output.='<td class="text-center">-</td>';
            }
        $output .='</tr>';
      }

      /*2nd semester calculation starts*/
      $output .='<tr id="BGS"><td>Semester2 Average </td>';
      $queryCheckSem2=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter4'  ");
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter3' or stuid='$stuid' and subject='$subject_mark' and quarter='Quarter4'  ");
        if($queryCheckSem2->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/2;
            $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
      
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' ");
      if($queryCheckSem2->num_rows()>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/2);
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' ");
      if($queryCheckSem2->num_rows()>0 && $total_subject>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/2)/$total_subject;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and quarter='Quarter3' and onreportcard='1' or grade='$gradesec' and rpbranch='$branch' and quarter='Quarter4' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter3' and rpbranch='$branch' and academicyear='$max_year' and grade='$gradesec' and onreportcard='1' or stuid='$stuid' and quarter= 'Quarter4' and rpbranch='$branch' and academicyear='$max_year' and grade='$gradesec' and onreportcard='1' group by grade ");
        if($query_rank->num_rows()>0){
          foreach ($query_rank->result() as $rvalue) {
            $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
          }
        }else{
          $output.='<td class="text-center">-</td>';
        }   
      }else{
        $output.='<td class="text-center">-</td>';
        $output.='<td class="text-center">-</td>';
      }
      $output.='<td class="text-center">-</td>';/*conduct calculation starts*/
     
      /*2nd Semester absence calculation starts*/
      
      $tot1stSem=0;
      if($queryCheckSem2->num_rows()>0){
      $quarterArray1=array('Quarter3','Quarter4');
          foreach ($quarterArray1 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center" colspan="2">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" colspan="2">-</td>';
          }
        }else{
          $output .= '<td class="text-center" colspan="2">-</td>';
        }
      $output.='</tr>';

      /*Yearly Average calculation starts*/
      $output .='<tr id="BGS"><td>Yearly Average </td>';

      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' ");
        if($queryCheckSem2->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/4;
            $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' ");
      if($queryCheckSem2->num_rows()>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/4);
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' ");
      if($queryCheckSem2->num_rows()>0 && $total_subject>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/4)/$total_subject;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and rpbranch='$branch' and academicyear='$max_year' and grade='$gradesec' and onreportcard='1' group by grade ");
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
        }
      }else{
        $output.='<td class="text-center">-</td>';
        $output.='<td class="text-center">-</td>';
      }
      $output.='<td class="text-center">-</td>';/*conduct calculation starts*/
      /*yearly absence calculation starts*/
      $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' ");
      if($queryCheckSem2->num_rows()>0){
        foreach ($queryTotalAbsent->result() as $absent){
          if($absent->att>0)
          {
            $output .= '<td class="text-center">'.$absent->att.'</td>';
          }
          else{
            $output .= '<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $totSubject=$this->db->query("select subject from reportcard".$gradesec.$max_year." where  onreportcard='1' and grade='$gradesec' group by subject ");
      $numSubject=$totSubject->num_rows();
      
      $totStudent=$this->db->query("select id from users where  isapproved='1' and status='Active' and gradesec='$gradesec' and academicyear='$max_year' and branch='$branch' group by id ");
      $numStudent=$totStudent->num_rows();
      if($stuNo%$page === 0 && $stuNo>=$page){
        $output .='<tr style="page-break-after: always; page-break-inside: avoid;
        page-break-before: avoid;">';
        $output.='<td colspan="4">Home Room Teachers Signature .______________________.<br>
        Date._____________________.</td>';
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="5">Record Office Signature._____________________. <br>
        Date.________________________. </td></tr></table> </div>';
        if($stuNo<$numStudent){
            $output .='<div class="table-responsive" width="100%" height="100%">
            <table id="ENS" class="tabler table-borderedr" width="100%" cellspacing="5" cellpadding="5">
            
            <tr><td class="text-center rotateJossRoster"><div> No.</div></td>
            <td class="text-center rotateJossRoster"><div>Students Name</div></td> 
            <td class="text-center rotateJossRoster"><div>Gender</div></td> 
            <td class="text-center rotateJossRoster"><div>Season</div></td>';
            $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
            foreach ($query_result ->result() as $rvalue)
            {
              $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
            }
            $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Conduct</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
            $output .='</tr>';
        }
      }else{
        $output .='</tr>';
      }
      $stuNo++;
    }
    if($numStudent%$page!==0){
        $output.='<tr><td colspan="4">Home Room Teachers Signature .______________________.<br>
        Date._____________________.</td>';
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="5">Record Office Signature._____________________. <br>
        Date.________________________. </td></tr>';
    }
    $output .=' </table> </div>';
    return $output;
  }
  function rosterLetter($max_year,$gradesec,$branch,$page)
  {
    $query_student=$this->db->query(" Select * from users where gradesec='$gradesec' and branch='$branch' and status='Active' and academicyear='$max_year' and isapproved='1' and usertype='Student' group by id order by fname,mname,lname ASC ");
    $query_name = $this->db->query("select * from school");
    $row_name = $query_name->row();//school info
    $school_name=$row_name->name;
    $address=$row_name->address;
    $phone=$row_name->phone;
    $website=$row_name->website;
    $email=$row_name->email;

    $queryac = $this->db->query("select max(year_name) as ay from academicyear");
    $rowac = $queryac->row();//academic year info
    $yearname=$rowac->ay;

    $row_gradesec = $query_student->row();//grade and sec info
    $grade_name=$row_gradesec->grade;
    $section_name=$row_gradesec->section;
    $gradeSec=$row_gradesec->gradesec;
    $totalStudent=$query_student->num_rows();

    $output=' <div class="text-center" id="ENS" height="100%" style="width:100%;height:100%;page-break-inside:avoid;display:flex; flex-direction:column; justify-content:center;min-height:100vh;">
      <h1>ROSTER SUMMARY</h1>
      <h3>School: '.$school_name.' <br></h3>
      <h5> Campus : '.$branch.'(Kaliti) </h5>
      <h5> Academic Year : '.$max_year.'E.C</h5>
      <h5>Grade & Section: '.$gradeSec.' </h5>
      <h5>Season: Yearly Average </h5>
      <h6>Total No. Of Student : '.$totalStudent.'</h6>
      </b>
    </div>';
    
    $output .='<div class="table-responsive" width="100%" height="100%">
        <table id="ENS" class="tabler table-borderedr" width="100%" cellspacing="5" cellpadding="5">
        
        <tr><td class="text-center rotateJossRoster"><div> No.</div></td>
        <td class="text-center rotateJossRoster"><div>Students Name</div></td> 
        <td class="text-center rotateJossRoster"><div>Gender</div></td> 
        <td class="text-center rotateJossRoster"><div>Season</div></td>';
        $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
        foreach ($query_result ->result() as $rvalue)
        {
          $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
        }
        $output .='<td class="text-center rotateJossRoster"><div> Conduct</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
        $output .='</tr>';
    $stuNo=1;
    foreach ($query_student->result() as $row) 
    {
      $stuid=$row->id;
      $username1=$row->username;
      $grade_sec=$row->gradesec;
      $grade=$row->grade;
      $query_quarter=array('Quarter1','Quarter2');
      $output .='<tr><td rowspan="8">'.$stuNo.'.</td>
      <td rowspan="8">'.$row->fname.' '.$row->mname.' '.$row->lname.'</td> 
      <td rowspan="8">'.$row->gender.'</td>';
      foreach ($query_quarter as $qvalue) 
      {
        $quarter=$qvalue;
        $output .='<tr> <td>'.$qvalue.'</td>';
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->subject;
          $query_quarter_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and subject='$subject_mark' group by subject ");
          if($query_quarter_result->num_rows()>0){
            foreach ($query_quarter_result->result() as $quarter_result) 
            {
              $letter=$quarter_result->letter;
              $result=$quarter_result->total;
                $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                if($queryRange->num_rows()>0){
                  foreach ($queryRange->result() as $letterValue) {
                    $letterVal=$letterValue->letterVal;
                    $output.= "<td class='text-center'>".$letterVal."</td>";
                  }
                }else{
                  $output.= "<td class='text-center'> -</td>";
                }
              
            }
          }else{
            $output.= "<td class='text-center'> -</td>";
          }
        }
        //Each quarter conduct starts
        $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' and bsname='Conduct' group by stuid ");
          if($eachQuarterBasicskill->num_rows()>0){
            $valueBS=$eachQuarterBasicskill->row();
            $getValue=$valueBS->value;
            $output.='<td class="text-center">'.$getValue.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        //Each quarter Absence starts
        
        $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$quarter' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center">'.$absent->att.'</td>';
                }
                else{
                  $output .= '<td class="text-center">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            }else{
              $output.='<td class="text-center">-</td>';
            }
        $output .='</tr>';
      }
      /*1st semester calculation starts*/
      $output .='<tr id="BGS"><td>1 <sup>st </sup> Semester Average </td>';
      $queryCheckSem1=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter2'  ");
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter1' or stuid='$stuid' and subject='$subject_mark' and quarter='Quarter2'  ");
        if($queryCheckSem1->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/2;
            
              $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
              if($queryRange->num_rows()>0){
                foreach ($queryRange->result() as $letterValue) {
                  $letterVal=$letterValue->letterVal;
                  $output.= "<td class='text-center'>".$letterVal."</td>";
                }
              }else{
                $output.= "<td class='text-center'> -</td>";
              }
            
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
      $output.='<td class="text-center">-</td>';/*conduct calculation starts*/
      /*1st Semester absence calculation starts*/
      $tot1stSem=0;
      if($queryCheckSem1->num_rows()>0){
      $quarterArray1=array('Quarter1','Quarter2');
          foreach ($quarterArray1 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center" colspan="2">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" colspan="2">-</td>';
          }
        }else{
          $output .= '<td class="text-center" colspan="2">-</td>';
        }
      $output.='</tr>';
      /*3rd and 4th calculation starts*/
      $queryQuarter=array('Quarter3','Quarter4');
      foreach ($queryQuarter as $qvalue) 
      {
        $quarter=$qvalue;
        $output .='<tr> <td>'.$qvalue.'</td>';
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->subject;
          $query_quarter_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and subject='$subject_mark' group by subject ");
          if($query_quarter_result->num_rows()>0){
            foreach ($query_quarter_result->result() as $quarter_result) 
            {
              $letter=$quarter_result->letter;
              $result=$quarter_result->total;
             
                $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                if($queryRange->num_rows()>0){
                  foreach ($queryRange->result() as $letterValue) {
                    $letterVal=$letterValue->letterVal;
                    $output.= "<td class='text-center'>".$letterVal."</td>";
                  }
                }else{
                  $output.= "<td class='text-center'> -</td>";
                }
            }
          }else{
            $output.= "<td class='text-center'> -</td>";
          }
        }
        
        //Each quarter conduct starts
        $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' and bsname='Conduct' group by stuid ");
          if($eachQuarterBasicskill->num_rows()>0){
            $valueBS=$eachQuarterBasicskill->row();
            $getValue=$valueBS->value;
            $output.='<td class="text-center">'.$getValue.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        //Each quarter Absence
        $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$quarter' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center">'.$absent->att.'</td>';
                }
                else{
                  $output .= '<td class="text-center">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            }else{
              $output.='<td class="text-center">-</td>';
            }
          
        $output .='</tr>';
      }

      /*2nd semester calculation starts*/
      $output .='<tr id="BGS"><td>2 <sup>nd</sup> Semester Average </td>';
      $queryCheckSem2=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter4'  ");
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter3' or stuid='$stuid' and subject='$subject_mark' and quarter='Quarter4'  ");
        if($queryCheckSem2->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/2;
              $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
              if($queryRange->num_rows()>0){
                foreach ($queryRange->result() as $letterValue) {
                  $letterVal=$letterValue->letterVal;
                  $output.= "<td class='text-center'>".$letterVal."</td>";
                }
              }else{
                $output.= "<td class='text-center'> -</td>";
              }
            
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
      $output.='<td class="text-center">-</td>';/*conduct calculation starts*/
     
      /*2nd Semester absence calculation starts*/
      
      $tot1stSem=0;
      if($queryCheckSem2->num_rows()>0){
      $quarterArray1=array('Quarter3','Quarter4');
          foreach ($quarterArray1 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center" colspan="2">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" colspan="2">-</td>';
          }
        }else{
          $output .= '<td class="text-center" colspan="2">-</td>';
        }
      $output.='</tr>';

      /*Yearly Average calculation starts*/
      $output .='<tr id="BGS"><td>Yearly Average </td>';

      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' ");
        if($queryCheckSem2->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/4;
              $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
              if($queryRange->num_rows()>0){
                foreach ($queryRange->result() as $letterValue) {
                  $letterVal=$letterValue->letterVal;
                  $output.= "<td class='text-center'>".$letterVal."</td>";
                }
              }else{
                $output.= "<td class='text-center'> -</td>";
              }
            
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
      $output.='<td class="text-center">-</td>';/*conduct calculation starts*/
      /*yearly absence calculation starts*/
      $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' ");
      if($queryCheckSem2->num_rows()>0){
        foreach ($queryTotalAbsent->result() as $absent){
          if($absent->att>0)
          {
            $output .= '<td class="text-center">'.$absent->att.'</td>';
          }
          else{
            $output .= '<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $totSubject=$this->db->query("select subject from reportcard".$gradesec.$max_year." where  onreportcard='1' and grade='$gradesec' group by subject ");
      $numSubject=$totSubject->num_rows();
      
      $totStudent=$this->db->query("select id from users where  isapproved='1' and status='Active' and gradesec='$gradesec' and academicyear='$max_year' and branch='$branch' group by id ");
      $numStudent=$totStudent->num_rows();
      if($stuNo%$page === 0 && $stuNo>=$page){
        $output .='<tr style="page-break-after: always; page-break-inside: avoid;
        page-break-before: avoid;">';
        $output.='<td colspan="4">Home Room Teachers Signature .______________________.<br>
        Date._____________________.</td>';
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="5">Record Office Signature._____________________. <br>
        Date.________________________. </td></tr></table> </div>';
        if($stuNo<$numStudent){
            $output .='<div class="table-responsive" width="100%" height="100%">
            <table id="ENS" class="tabler table-borderedr" width="100%" cellspacing="5" cellpadding="5">
            
            <tr><td class="text-center rotateJossRoster"><div> No.</div></td>
            <td class="text-center rotateJossRoster"><div>Students Name</div></td> 
            <td class="text-center rotateJossRoster"><div>Gender</div></td> 
            <td class="text-center rotateJossRoster"><div>Season</div></td>';
            $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
            foreach ($query_result ->result() as $rvalue)
            {
              $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
            }
            $output .='<td class="text-center rotateJossRoster"><div> Conduct</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
            $output .='</tr>';
        }
      }else{
        $output .='</tr>';
      }
      $stuNo++;
    }
    if($numStudent%$page!==0){
        $output.='<tr><td colspan="4">Home Room Teachers Signature .______________________.<br>
        Date._____________________.</td>';
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="5">Record Office Signature._____________________. <br>
        Date.________________________. </td></tr>';
    }
    $output .=' </table> </div>';
    return $output;
  }


  function fetchCustomTranscriptStudent($reportaca,$gradesec,$branch){
    $this->db->order_by('fname,mname,lname','ASC');
    $this->db->where(array('status'=>'Active'));
    $this->db->where(array('isapproved'=>'1'));
    $this->db->where(array('academicyear'=>$reportaca));
    $this->db->where(array('gradesec'=>$gradesec));
    $this->db->where(array('branch'=>$branch));
    $this->db->like('usertype','Student');
    $query=$this->db->get('users');
    $output ='';
    $output .='
         <div class="table-responsive">
        <table class="table table-striped table-hover" style="width:100%;">
        <thead>
        <tr>
        <th>No.</th>
            <th>Student ID</th>
            <th>Full Name</th>
            <th>Gr. & Sec</th>
            <th>Branch</th>
            <th>Print</th>
        </tr>
        </thead>
       <tbody>';
        $no=1;
    foreach ($query ->result() as $value) {
      $id=$value->id;
      $username=$value->username;
      $output .='<tr class="delete_mem'.$value->id.'">
      <td>'.$no.'.</td>
      <td>'.$value->unique_id.' </td>
      <td>'.$value->fname .' '.$value->mname.' '.$value->lname.' </td> 
      <td>'.$value->gradesec.'</td>
      <td>'.$value->branch.' </td> 
      <td><button class="btn btn-outline-info printThisStudentTranscript" name="'.$id.'" value="'.$username.'" id="'.$reportaca.'"><i class="fas fa-print"></i></button></td> </tr>';
      $no++;
    }
    return $output;
  }
  function transcriptCrosspoint($academicyear,$gradesec,$branch,$noGrade)
  {
    $output='';
    $targetYear=$academicyear;
    $queryUsers=$this->db->query("select grade,username,unique_id from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and status='Active' and isapproved='1' and academicyear='$academicyear' group by grade order by grade ASC ");
    if($queryUsers->num_rows()>0){
      $rowSubject=$queryUsers->row_array();
      $gradeSubject=$rowSubject['grade'];
    }else{
      $gradeSubject='';
    }
    $data=array();
    for($i=$noGrade;$i>=1;$i--){
      $querySubjects=$this->db->query("select * from subject where Academic_Year='$targetYear' and Grade='$gradeSubject' group by Subj_name order by suborder ");
      if($querySubjects->num_rows()>0){
        foreach($querySubjects->result() as $subName){
          if($subName->Merged_name==''){
            $subject=$subName->Subj_name;
            $onreportcard=$subName->onreportcard;
          }else{
            $subject=$subName->Merged_name;
            $onreportcard='1';
          }
          $letter=$subName->letter;
          $suborder=$subName->suborder;
          $data[]=array(
            'grade'=>$gradeSubject,
            'subject'=>$subject,
            'letter'=>$letter,
            'onreportcard'=>$onreportcard,
            'suborder'=>$suborder,
            'academicyear'=>$targetYear,
          );
        }
        $targetYear=$academicyear-1;
        switch ($gradeSubject) {
           case 'KG3':
            $gradeSubject='KG2';
            break;
          case 'KG2':
            $gradeSubject='KG1';
            break;
          case 'KG1':
            $gradeSubject='-';
            break;
          case '11n':
            $gradeSubject='10';
            break;
          case '11s':
            $gradeSubject='10';
            break;
          case '12n':
            $gradeSubject='11n';
            break;
          case '12s':
            $gradeSubject='11s';
            break;
            case '11N':
            $gradeSubject='10';
            break;
          case '11S':
            $gradeSubject='10';
            break;
          case '12N':
            $gradeSubject='11n';
            break;
          case '12S':
            $gradeSubject='11s';
            break;
          case '12NS':
            $gradeSubject='12NS';
            break;
          case '12SS':
            $gradeSubject='12SS';
            break;
          case '11NS':
            $gradeSubject='11NS';
            break;
          case '11SS':
            $gradeSubject='11SS';
            break;
          default:
            $gradeSubject=$gradeSubject-1;
            break;
        }
      }
    }
    if(!empty($data)){
      $queryInsert=$this->db->insert_batch('transcript_list',$data);
    }
    $queryTr=$this->db->query("select username,profile,fname,mname,lname,gender,gradesec,age, unique_id,grade,id from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and status='Active' and isapproved='1' and academicyear='$academicyear' group by id order by fname,mname,lname ASC ");
    $query_name = $this->db->query("select * from school");
    $row_name = $query_name->row();
    $school_name=$row_name->name;
    $schooLogo=$row_name->logo;
    $website=$row_name->website;
    if($queryTr->num_rows()>0){
      $rowcheckGrade=$queryTr->row();
      $cGrade=$rowcheckGrade->grade;
      $queryAGreYear=$this->db->query("select gyear from academicyear where year_name='$academicyear' ");
      $rowAG = $queryAGreYear->row();
      $Agmax_year=$rowAG->gyear;
      foreach ($queryTr->result() as $stuName) {
        $uniqueId=$stuName->unique_id;
        $currGrade=$stuName->grade;
        $currID=$stuName->id;
        $stuid=$stuName->id;
        $username=$stuName->username;
        $output.='<div style="width:100%;height:92%;page-break-inside:avoid;">';
        $output.='<div class ="row" id="ENS">
            <div class="col-lg-5 col-5 text-center">
              <h4>ሲያትል አካዳሚ <h4>
              <h4><b>ከአፀደ-ህፃናት - ኮሌጅ መሰናዶ</b></h4>
            </div>
            <div class="col-lg-2 col-2 text-center">
              <img class="text-center" src="'.base_url().'/logo/'.$schooLogo.'" style="height:110px;width:110px;border:1px solid #fff;" >
            </div>
            <div class="col-lg-5 col-5 text-center">
              <h4><b>'.$school_name.'</b></h4>
              <h4><b>KG – Preparatory</b></h4>
            </div>
             <div class="col-lg-12 text-center">
              <h5 class="card-header"><B>OFFICIAL SCHOOL TRANSCRIPT</B></h3>
            </div>
        </div>
        <div class ="row" id="ENS">
          <div class="col-lg-8 col-8">
            <div class="support-ticket media pb-1 mb-3">
              <img src="'.base_url().'/profile/'.$stuName->profile.'" style="width:70px;height: 80px;" class="user-img mr-2" alt="">
              <div class="media-body ml-3">
                <p class="my-1">NAME: <u>'.$stuName->fname.' '.$stuName->mname.' '.$stuName->lname.'</u></p>
                <p class="my-1">GENDER: <u>'.$stuName->gender.' </u></p>';
              $output.='</div>
            </div>
          </div>
          <div class="col-lg-4 col-4">
            <p class="my-1">Grade: <u>'.$stuName->gradesec.'</u></p>';
             if($stuName->age>0){
              $output.='<p class="my-1">Age: <u>'.$stuName->age.' </u></p>';
            }else{
              $output.='<p class="my-1">Age: _____</p>';
            }
          $output.='</div></div>';           
          $output.=' <div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">
          <tbody><tr> 
          <th class="text-center">ACADEMIC YEAR</th>';
          $targetYear=($academicyear-$noGrade) + 1;
          for($i=1;$i<=$noGrade;$i++){
            $queryGreYear=$this->db->query("select gyear from academicyear where year_name='$targetYear' ");
            if($queryGreYear->num_rows()>0){
              $rowG = $queryGreYear->row();
              $gmax_year=$rowG->gyear;
            }else{
              $gmax_year='-';
            }
            $output.='<th colspan="3" class="text-center">'.$gmax_year.' G.C | '.$targetYear.' E.C </th>';
            $targetYear=$targetYear+1;
          }
          $output.='<tr> 
          <th class="text-center">GRADE</th>';
          $targetYear=($academicyear-$noGrade) + 1;
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
              $checkRow=$queryLastGrade->row();
              $newGradesec=$checkRow->gradesec;
              $currGrade=$checkRow->grade;
              switch ($currGrade) {
                case '1':
                  $gradeName='ONE';
                  break;
                case '2':
                  $gradeName='TWO';
                  break;
                case '3':
                  $gradeName='THREE';
                  break;
                case '4':
                  $gradeName='FOUR';
                  break;
                case '5':
                  $gradeName='FIVE';
                  break;
                case '6':
                  $gradeName='SIX';
                  break;
                case '7':
                  $gradeName='SEVEN';
                  break;
                case '8':
                  $gradeName='EIGHT';
                  break;
                case '9':
                  $gradeName='NINE';
                  break;
                case '10':
                  $gradeName='TEN';
                  break;
                case '11':
                  $gradeName='ELEVEN';
                  break;
                case '11n':
                  $gradeName='ELEVEN';
                  break;
                case '11s':
                  $gradeName='ELEVEN';
                  break;
                case '12':
                  $gradeName='ELEVEN';
                  break;
                case '12n':
                  $gradeName='TWELVE';
                  break;
                case '12s':
                  $gradeName='TWELVE';
                  break;
                default:
                  $gradeName='-';
                  break;
              }
              $output.='<th colspan="3" class="text-center">'.$newGradesec.'('.$gradeName.')</th>';
            }else{
              $output.='<th colspan="3" class="text-center">-</th>'; 
            }
            $targetYear=$targetYear+1;
          }
          $output.='<tr><th rowspan="2" class="text-center">SUBJECT</th>';
          for($i=1;$i<=$noGrade;$i++){
            $output.='<th colspan="3" class="text-center">SEMESTER</th>';
          }
          $output.='</tr>';
          $targetYear=($academicyear-$noGrade) + 1;
          for($i=1;$i<=$noGrade;$i++){
            $output.='<th class="text-center">I</th>
            <th class="text-center">II</th>
            <th class="text-center">AV</th>';
            $targetYear=$targetYear+1;
          }
          $output.='</tr>';
        $querySubjecy=$this->db->query("select * from transcript_list where onreportcard='1' group by subject order by suborder ASC");
        if($querySubjecy->num_rows()>0){
          foreach ($querySubjecy->result() as $subjValue) {
            $subjName=$subjValue->subject;
            $output.='<tr><td>'.$subjName.'</td>';
            $targetYear=($academicyear-$noGrade) + 1;
            for($i=1;$i<=$noGrade;$i++){
              $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
              if($queryLastGrade->num_rows()>0){
                foreach($queryLastGrade->result() as $stuRow){
                  $newGradesec=$stuRow->gradesec;
                  $currGrade=$stuRow->grade;
                  $stuid=$stuRow->id;
                  $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
                  if ($queryCheck->num_rows()>0 ){
                    $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' and subject='$subjName'");
                    foreach ($quartrSem1Total->result() as $totalValueSem1) {
                      $printValueSem1=($totalValueSem1->total);
                      if($printValueSem1 >0){
                        if($letter!='A'){                          
                          if($printValueSem1=='100'){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,0,'.','').'</td>';
                          }else{
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem1 between minValue and maxiValue and academicYear='$targetYear'");
                          if($queryRange->num_rows()>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center'>".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center'> -</td>";
                          }
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' and subject='$subjName' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem2=($totalValueSem1->total);
                      if($printValueSem2 >0){
                        if($letter!='A'){                          
                          if($printValueSem2=='100'){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,0,'.','').'</td>';
                          }else{
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem2 between minValue and maxiValue and academicYear='$targetYear'");
                          if($queryRange->num_rows()>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center'>".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center'> -</td>";
                          }
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Totalver=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#' and subject='$subjName'  ");
                    foreach ($quartrSem2Totalver->result() as $totalValueSem1Ave) {
                      $printValueSem1Ave=($totalValueSem1Ave->total)/2;
                      
                      if($printValueSem1Ave >0){
                        if($letter!='A'){                          
                          if($printValueSem1Ave=='100'){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1Ave,0,'.','').'</td>';
                          }else{
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1Ave,2,'.','').'</td>';
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem1Ave between minValue and maxiValue and academicYear='$targetYear'");
                          if($queryRange->num_rows()>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center'>".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center'> -</td>";
                          }
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                  }else{
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              $targetYear=$targetYear+1;
            }
          }
          $targetYear=($academicyear-$noGrade) + 1;
          $output.='<tr><td>GRAND TOTAL</td>';
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
              foreach($queryLastGrade->result() as $stuRow){
                $newGradesec=$stuRow->gradesec;
                $currGrade=$stuRow->grade;
                $stuid=$stuRow->id;
                $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
                if ($queryCheck->num_rows()>0 ){
                  $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                  foreach ($quartrSem2Total->result() as $totalValueSem1) {
                    $printValueSem1=($totalValueSem1->total);
                    if($printValueSem1 >0){
                      $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                  $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
                  foreach ($quartrSem2Total->result() as $totalValueSem1) {
                    $printValueSem1=($totalValueSem1->total);
                    if($printValueSem1 >0){
                      $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                  $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                  foreach ($quartrSem2Total->result() as $totalValueSem1) {
                    $printValueSem1=(($totalValueSem1->total)/2);
                    if($printValueSem1 >0){
                      $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                }else{
                  $output.='<td class="text-center">-</td>';
                  $output.='<td class="text-center">-</td>';
                  $output.='<td class="text-center">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
            }
            $targetYear=$targetYear+1;
          }
          $output.='<tr><td>AVERAGE</td>';
          $targetYear=($academicyear-$noGrade) + 1;   
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
              foreach($queryLastGrade->result() as $stuRow){
                $newGradesec=$stuRow->gradesec;
                $currGrade=$stuRow->grade;
                $stuid=$stuRow->id;
                $queryCountSub=$this->db->query("select * from transcript_list where academicyear='$targetYear' and letter='#' and onreportcard='1' and grade='$currGrade' group by subject order by suborder ASC");
                if($queryCountSub->num_rows()>0){
                  $subALast=$queryCountSub->num_rows();
                }else{
                  $subALast=1;
                }
                $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
                if ($queryCheck->num_rows()>0 ){
                  $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                  foreach ($quartrSem2Total->result() as $totalValueSem1) {
                    $printValueSem1=($totalValueSem1->total);
                    if($printValueSem1 >0){
                      $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                  $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
                  foreach ($quartrSem2Total->result() as $totalValueSem1) {
                    $printValueSem1=($totalValueSem1->total);
                    if($printValueSem1 >0){
                      $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                  $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                  foreach ($quartrSem2Total->result() as $totalValueSem1) {
                    $printValueSem1=(($totalValueSem1->total)/2);
                    if($printValueSem1 >0){
                      $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
            }
            }else{
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
            }
            $targetYear=$targetYear+1;
          }
        }
        $output.='
        </tbody>
        </table>
        </div>';
        $output.='<div class ="row" id="ENS">
            <div class="col-lg-12 col-12">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <p class="my-1">LAST GRADE ATTENDED IN WORD:<u>'.$gradeName.'</u></p>';
                  $queryInfo=$this->db->query("select * from leavingreasoninfo where stuid='$username' and academicyear='$academicyear' ");
                  if($queryInfo->num_rows()>0){
                    $rowInfo=$queryInfo->row();
                    $reasonIssue=$rowInfo->reasoname;
                    $output.='<p class="my-1">REASON FOR ISSUE:<u>'.$reasonIssue.'</u></p>';
                  }else{
                    $output.='<p class="my-1">REASON FOR ISSUE:<u>Completed Grade '.$gradeName.'</u></p>';
                  }
                $output.='</div>
              </div>
            </div>
            <div class="col-lg-12 col-12">
              <p class="my-1">Note: THIS TRANSCRIPT IS INVALID IF ANY ALTERNATION OR ERASER COMMITS & UNLESS IT BEARS THE OFFICIAL SEAL OF THE SCHOOL.</p>
            </div>
            <div class="col-lg-6 col-6">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <span class="">Record officers Name :_____________________</span><br>
                  <span class="">Signature :_______________</span> </br>
                  <span class="">Date  :_______________</span>  
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-6">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <span class="">Directors Name and Signature :_____________________</span><br>
                  <span class="">Signature :_______________</span> </br>
                  <span class="">Date  :_______________</span>  
                </div>
              </div>
            </div>
          </div>
            <div style="background-color:#e3e3e3;">
              <div class ="row">
                <div class="col-md-4 col-4">
                    P.O.Box <br> ፖ.ሳ.ቁ  28643
                </div>
                <div class="col-md-4 col-4">
                    Tel: +251-118-8888-60/ +251-118-8888-61/ +251-118-8888-66
                </div>
                <div class="col-md-4 col-4">
                    Fax:<br>ፋክስ______
                </div>
              </div>
            </div>
          ';
      }
    }
    return $output;
  }
  function letterTranscriptCrosspoint($academicyear,$gradesec,$branch,$noGrade)
  {
    $output='';
    $targetYear=$academicyear;
    $queryUsers=$this->db->query("select grade,username,unique_id from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and status='Active' and isapproved='1' and academicyear='$academicyear' group by grade order by grade ASC ");
    if($queryUsers->num_rows()>0){
      $rowSubject=$queryUsers->row_array();
      $gradeSubject=$rowSubject['grade'];
    }else{
      $gradeSubject='';
    }
    $data=array();
    for($i=$noGrade;$i>=1;$i--){
      $querySubjects=$this->db->query("select * from subject where Academic_Year='$targetYear' and Grade='$gradeSubject' group by Subj_name order by suborder ");
      if($querySubjects->num_rows()>0){
        foreach($querySubjects->result() as $subName){
          if($subName->Merged_name==''){
            $subject=$subName->Subj_name;
            $onreportcard=$subName->onreportcard;
          }else{
            $subject=$subName->Merged_name;
            $onreportcard='1';
          }
          $letter=$subName->letter;
          $suborder=$subName->suborder;
          $data[]=array(
            'grade'=>$gradeSubject,
            'subject'=>$subject,
            'letter'=>$letter,
            'onreportcard'=>$onreportcard,
            'suborder'=>$suborder,
            'academicyear'=>$targetYear,
          );
        }
        $targetYear=$academicyear-1;
        switch ($gradeSubject) {
           case 'KG3':
            $gradeSubject='KG2';
            break;
          case 'KG2':
            $gradeSubject='KG1';
            break;
          case 'KG1':
            $gradeSubject='-';
            break;
          case '11n':
            $gradeSubject='10';
            break;
          case '11s':
            $gradeSubject='10';
            break;
          case '12n':
            $gradeSubject='11n';
            break;
          case '12s':
            $gradeSubject='11s';
            break;
            case '11N':
            $gradeSubject='10';
            break;
          case '11S':
            $gradeSubject='10';
            break;
          case '12N':
            $gradeSubject='11n';
            break;
          case '12S':
            $gradeSubject='11s';
            break;
          case '12NS':
            $gradeSubject='12NS';
            break;
          case '12SS':
            $gradeSubject='12SS';
            break;
          case '11NS':
            $gradeSubject='11NS';
            break;
          case '11SS':
            $gradeSubject='11SS';
            break;
          default:
            $gradeSubject=$gradeSubject-1;
            break;
        }
      }
    }
    if(!empty($data)){
      $queryInsert=$this->db->insert_batch('transcript_list',$data);
    }
    $queryTr=$this->db->query("select username,profile,fname,mname,lname,gender,gradesec,age, unique_id,grade,id from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and status='Active' and isapproved='1' and academicyear='$academicyear' group by id order by fname,mname,lname ASC ");
    $query_name = $this->db->query("select * from school");
    $row_name = $query_name->row();
    $school_name=$row_name->name;
    $schooLogo=$row_name->logo;
    $website=$row_name->website;
    if($queryTr->num_rows()>0){
      $rowcheckGrade=$queryTr->row();
      $cGrade=$rowcheckGrade->grade;
      $queryAGreYear=$this->db->query("select gyear from academicyear where year_name='$academicyear' ");
      $rowAG = $queryAGreYear->row();
      $Agmax_year=$rowAG->gyear;
      foreach ($queryTr->result() as $stuName) {
        $uniqueId=$stuName->unique_id;
        $currGrade=$stuName->grade;
        $currID=$stuName->id;
        $stuid=$stuName->id;
        $username=$stuName->username;
        $output.='<div style="width:100%;height:92%;page-break-inside:avoid;">';
        $output.='<div class ="row" id="ENS">
            <div class="col-lg-5 col-5 text-center">
              <h4>ሲያትል አካዳሚ <h4>
              <h4><b>ከአፀደ-ህፃናት - ኮሌጅ መሰናዶ</b></h4>
            </div>
            <div class="col-lg-2 col-2 text-center">
              <img class="text-center" src="'.base_url().'/logo/'.$schooLogo.'" style="height:110px;width:110px;border:1px solid #fff;" >
            </div>
            <div class="col-lg-5 col-5 text-center">
              <h4><b>'.$school_name.'</b></h4>
              <h4><b>KG – Preparatory</b></h4>
            </div>
             <div class="col-lg-12 text-center">
              <h5 class="card-header"><B>OFFICIAL SCHOOL TRANSCRIPT</B></h3>
            </div>
        </div>
        <div class ="row" id="ENS">
          <div class="col-lg-8 col-8">
            <div class="support-ticket media pb-1 mb-3">
              <img src="'.base_url().'/profile/'.$stuName->profile.'" style="width:70px;height: 80px;" class="user-img mr-2" alt="">
              <div class="media-body ml-3">
                <p class="my-1">NAME: <u>'.$stuName->fname.' '.$stuName->mname.' '.$stuName->lname.'</u></p>
                <p class="my-1">GENDER: <u>'.$stuName->gender.' </u></p>';
              $output.='</div>
            </div>
          </div>
          <div class="col-lg-4 col-4">
            <p class="my-1">Grade: <u>'.$stuName->gradesec.'</u></p>';
             if($stuName->age>0){
              $output.='<p class="my-1">Age: <u>'.$stuName->age.' </u></p>';
            }else{
              $output.='<p class="my-1">Age: _____</p>';
            }
          $output.='</div></div>';           
          $output.=' <div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">
          <tbody><tr> 
          <th class="text-center">ACADEMIC YEAR</th>';
          $targetYear=($academicyear-$noGrade) + 1;
          for($i=1;$i<=$noGrade;$i++){
            $queryGreYear=$this->db->query("select gyear from academicyear where year_name='$targetYear' ");
            if($queryGreYear->num_rows()>0){
              $rowG = $queryGreYear->row();
              $gmax_year=$rowG->gyear;
            }else{
              $gmax_year='-';
            }
            $output.='<th colspan="3" class="text-center">'.$gmax_year.' G.C | '.$targetYear.' E.C </th>';
            $targetYear=$targetYear+1;
          }
          $output.='<tr> 
          <th class="text-center">GRADE</th>';
          $targetYear=($academicyear-$noGrade) + 1;
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
              $checkRow=$queryLastGrade->row();
              $newGradesec=$checkRow->gradesec;
              $currGrade=$checkRow->grade;
              switch ($currGrade) {
                case '1':
                  $gradeName='ONE';
                  break;
                case '2':
                  $gradeName='TWO';
                  break;
                case '3':
                  $gradeName='THREE';
                  break;
                case '4':
                  $gradeName='FOUR';
                  break;
                case '5':
                  $gradeName='FIVE';
                  break;
                case '6':
                  $gradeName='SIX';
                  break;
                case '7':
                  $gradeName='SEVEN';
                  break;
                case '8':
                  $gradeName='EIGHT';
                  break;
                case '9':
                  $gradeName='NINE';
                  break;
                case '10':
                  $gradeName='TEN';
                  break;
                case '11':
                  $gradeName='ELEVEN';
                  break;
                case '11n':
                  $gradeName='ELEVEN';
                  break;
                case '11s':
                  $gradeName='ELEVEN';
                  break;
                case '12':
                  $gradeName='ELEVEN';
                  break;
                case '12n':
                  $gradeName='TWELVE';
                  break;
                case '12s':
                  $gradeName='TWELVE';
                  break;
                default:
                  $gradeName='-';
                  break;
              }
              $output.='<th colspan="3" class="text-center">'.$newGradesec.'('.$gradeName.')</th>';
            }else{
              $output.='<th colspan="3" class="text-center">-</th>'; 
            }
            $targetYear=$targetYear+1;
          }
          $output.='<tr><th rowspan="2" class="text-center">SUBJECT</th>';
          for($i=1;$i<=$noGrade;$i++){
            $output.='<th colspan="3" class="text-center">SEMESTER</th>';
          }
          $output.='</tr>';
          $targetYear=($academicyear-$noGrade) + 1;
          for($i=1;$i<=$noGrade;$i++){
            $output.='<th class="text-center">I</th>
            <th class="text-center">II</th>
            <th class="text-center">AV</th>';
            $targetYear=$targetYear+1;
          }
          $output.='</tr>';
        $querySubjecy=$this->db->query("select * from transcript_list where onreportcard='1' group by subject order by suborder ASC");
        if($querySubjecy->num_rows()>0){
          foreach ($querySubjecy->result() as $subjValue) {
            $subjName=$subjValue->subject;
            $output.='<tr><td>'.$subjName.'</td>';
            $targetYear=($academicyear-$noGrade) + 1;
            for($i=1;$i<=$noGrade;$i++){
              $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
              if($queryLastGrade->num_rows()>0){
                foreach($queryLastGrade->result() as $stuRow){
                  $newGradesec=$stuRow->gradesec;
                  $currGrade=$stuRow->grade;
                  $stuid=$stuRow->id;
                  $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
                  if ($queryCheck->num_rows()>0 ){
                    $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and subject='$subjName'");
                    foreach ($quartrSem1Total->result() as $totalValueSem1) {
                      $printValueSem1=($totalValueSem1->total);
                      $valFirst=number_format((float)$printValueSem1,0,'.','');
                      if($printValueSem1 >0){
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $valFirst between minValue and maxiValue and academicYear='$targetYear'");
                        if($queryRange->num_rows()>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center'> -</td>";
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and subject='$subjName' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem12=($totalValueSem1->total);
                      $valSecond=number_format((float)$printValueSem12,0,'.','');
                      if($printValueSem12 >0){
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $valSecond between minValue and maxiValue and academicYear='$targetYear'");
                        if($queryRange->num_rows()>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center'> -</td>";
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Totalver=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and subject='$subjName'  ");
                    foreach ($quartrSem2Totalver->result() as $totalValueSem1Ave) {
                      $printValueSem1Ave=($totalValueSem1Ave->total)/2;
                      $valueAve=number_format((float)$printValueSem1Ave,0,'.','');
                      if($printValueSem1Ave >0){
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $valueAve between minValue and maxiValue and academicYear='$targetYear'");
                        if($queryRange->num_rows()>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center'> -</td>";
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                  }else{
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              $targetYear=$targetYear+1;
            }
          }
          /*$targetYear=($academicyear-$noGrade) + 1;
          $output.='<tr><td>GRAND TOTAL</td>';
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
              $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
              if ($queryCheck->num_rows()>0 ){
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1A=($totalValueSem1->total);
                  if($printValueSem1A >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$lastGr' and $printValueSem1A between minValue and maxiValue and academicYear='$targetYear'");
                    if($queryRange->num_rows()>0){
                      foreach ($queryRange->result() as $letterValue) {
                        $letterVal=$letterValue->letterVal;
                        $output.= "<td class='text-center'>".$letterVal."</td>";
                      }
                    }else{
                      $output.= "<td class='text-center'> -</td>";
                    }
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1AV=($totalValueSem1->total);
                  if($printValueSem1AV >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$lastGr' and $printValueSem1AV between minValue and maxiValue and academicYear='$targetYear'");
                    if($queryRange->num_rows()>0){
                      foreach ($queryRange->result() as $letterValue) {
                        $letterVal=$letterValue->letterVal;
                        $output.= "<td class='text-center'>".$letterVal."</td>";
                      }
                    }else{
                      $output.= "<td class='text-center'> -</td>";
                    }
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1G=(($totalValueSem1->total)/2);
                  if($printValueSem1G >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$lastGr' and $printValueSem1G between minValue and maxiValue and academicYear='$targetYear'");
                    if($queryRange->num_rows()>0){
                      foreach ($queryRange->result() as $letterValue) {
                        $letterVal=$letterValue->letterVal;
                        $output.= "<td class='text-center'>".$letterVal."</td>";
                      }
                    }else{
                      $output.= "<td class='text-center'> -</td>";
                    }
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
            }else{
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
            }
            $targetYear=$targetYear+1;
          }*/
          /*$output.='<tr><td>AVERAGE</td>';
          $targetYear=($academicyear-$noGrade) + 1;   
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
              $gradeRow=$queryLastGrade->row();
              $currGrade=$gradeRow->grade;
              $queryCountSub=$this->db->query("select * from transcript_list where academicyear='$academicyear' and letter='#' and onreportcard='1' and grade='$currGrade' group by subject order by suborder ASC");
              if($queryCountSub->num_rows()>0){
                $subALast=$queryCountSub->num_rows();
              }else{
                $subALast=1;
              }
              $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
              if ($queryCheck->num_rows()>0 ){
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1=($totalValueSem1->total);
                  if($printValueSem1 >0){
                    $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1=($totalValueSem1->total);
                  if($printValueSem1 >0){
                    $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1=(($totalValueSem1->total)/2);
                  if($printValueSem1 >0){
                    $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
            }else{
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
            }
            $targetYear=$targetYear+1;
          }*/
        }
        $output.='
        </tbody>
        </table>
        </div>';
        $output.='<div class ="row" id="ENS">
            <div class="col-lg-12 col-12">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <p class="my-1">LAST GRADE ATTENDED IN WORD:<u>'.$gradeName.'</u></p>';
                  $queryInfo=$this->db->query("select * from leavingreasoninfo where stuid='$username' and academicyear='$academicyear' ");
                  if($queryInfo->num_rows()>0){
                    $rowInfo=$queryInfo->row();
                    $reasonIssue=$rowInfo->reasoname;
                    $output.='<p class="my-1">REASON FOR ISSUE:<u>'.$reasonIssue.'</u></p>';
                  }else{
                    $output.='<p class="my-1">REASON FOR ISSUE:<u>Completed Grade '.$gradeName.'</u></p>';
                  }
                $output.='</div>
              </div>
            </div>
            <div class="col-lg-12 col-12">
              <p class="my-1">Note: THIS TRANSCRIPT IS INVALID IF ANY ALTERNATION OR ERASER COMMITS & UNLESS IT BEARS THE OFFICIAL SEAL OF THE SCHOOL.</p>
            </div>
            <div class="col-lg-6 col-6">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <span class="">Record officers Name :_____________________</span><br>
                  <span class="">Signature :_______________</span> </br>
                  <span class="">Date  :_______________</span>  
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-6">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <span class="">Directors Name and Signature :_____________________</span><br>
                  <span class="">Signature :_______________</span> </br>
                  <span class="">Date  :_______________</span>  
                </div>
              </div>
            </div>
          </div>
            <div style="background-color:#e3e3e3;">
              <div class ="row">
                <div class="col-md-4 col-4">
                    P.O.Box <br> ፖ.ሳ.ቁ  28643
                </div>
                <div class="col-md-4 col-4">
                    Tel: +251-118-8888-60/ +251-118-8888-61/ +251-118-8888-66
                </div>
                <div class="col-md-4 col-4">
                    Fax:<br>ፋክስ______
                </div>
              </div>
            </div>
          ';
      }
    }
    return $output;
  }
  function transcriptCustomCrosspoint($academicyear,$gradesec,$branch,$userName,$noGrade)
  {
    $output='';
    $targetYear=$academicyear;
    $queryUsers=$this->db->query("select fname,mname,lname,gender,gradesec,age,username, unique_id, grade, id, profile from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and status='Active' and isapproved='1' and academicyear='$academicyear' and username='$userName' order by fname,mname,lname ASC ");
    if($queryUsers->num_rows()>0){
      $rowSubject=$queryUsers->row_array();
      $gradeSubject=$rowSubject['grade'];
    }else{
      $gradeSubject='';
    }
    $data=array();
    for($i=$noGrade;$i>=1;$i--){
      $querySubjects=$this->db->query("select * from subject where Academic_Year='$targetYear' and Grade='$gradeSubject' group by Subj_name order by suborder ");
      if($querySubjects->num_rows()>0){
        foreach($querySubjects->result() as $subName){
          if($subName->Merged_name==''){
            $subject=$subName->Subj_name;
            $onreportcard=$subName->onreportcard;
          }else{
            $subject=$subName->Merged_name;
            $onreportcard='1';
          }
          $letter=$subName->letter;
          $suborder=$subName->suborder;
          $data[]=array(
            'grade'=>$gradeSubject,
            'subject'=>$subject,
            'letter'=>$letter,
            'onreportcard'=>$onreportcard,
            'suborder'=>$suborder,
            'academicyear'=>$targetYear,
          );
        }
        $targetYear=$academicyear-1;
        switch ($gradeSubject) {
           case 'KG3':
            $gradeSubject='KG2';
            break;
          case 'KG2':
            $gradeSubject='KG1';
            break;
          case 'KG1':
            $gradeSubject='-';
            break;
          case '11n':
            $gradeSubject='10';
            break;
          case '11s':
            $gradeSubject='10';
            break;
          case '12n':
            $gradeSubject='11n';
            break;
          case '12s':
            $gradeSubject='11s';
            break;
            case '11N':
            $gradeSubject='10';
            break;
          case '11S':
            $gradeSubject='10';
            break;
          case '12N':
            $gradeSubject='11n';
            break;
          case '12S':
            $gradeSubject='11s';
            break;
          case '12NS':
            $gradeSubject='12NS';
            break;
          case '12SS':
            $gradeSubject='12SS';
            break;
          case '11NS':
            $gradeSubject='11NS';
            break;
          case '11SS':
            $gradeSubject='11SS';
            break;
          default:
            $gradeSubject=$gradeSubject-1;
            break;
        }
      }
    }
    if(!empty($data)){
      $queryInsert=$this->db->insert_batch('transcript_list',$data);
    }
    $queryTr=$this->db->query("select fname,mname,lname,gender,gradesec,age,username, unique_id, grade, id, profile from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and status='Active' and isapproved='1' and academicyear='$academicyear' and username='$userName' order by fname,mname,lname ASC ");
    $query_name = $this->db->query("select * from school");
    $row_name = $query_name->row();
    $school_name=$row_name->name;
    $schooLogo=$row_name->logo;
    $website=$row_name->website;
    if($queryTr->num_rows()>0){
      $rowcheckGrade=$queryTr->row();
      $cGrade=$rowcheckGrade->grade;
      $queryAGreYear=$this->db->query("select gyear from academicyear where year_name='$academicyear' ");
      $rowAG = $queryAGreYear->row();
      $Agmax_year=$rowAG->gyear;
      foreach ($queryTr->result() as $stuName) {
        $uniqueId=$stuName->unique_id;
        $currGrade=$stuName->grade;
        $currID=$stuName->id;
        $stuid=$stuName->id;
        $username=$stuName->username;
        $output.='<div style="width:100%;height:92%;page-break-inside:avoid;">';
        $output.='<div class ="row" id="ENS">
            <div class="col-lg-5 col-5 text-center">
              <h4>ሲያትል አካዳሚ <h4>
              <h4><b>ከአፀደ-ህፃናት - ኮሌጅ መሰናዶ</b></h4>
            </div>
            <div class="col-lg-2 col-2 text-center">
              <img class="text-center" src="'.base_url().'/logo/'.$schooLogo.'" style="height:110px;width:110px;border:1px solid #fff;" >
            </div>
            <div class="col-lg-5 col-5 text-center">
              <h4><b>'.$school_name.'</b></h4>
              <h4><b>KG – Preparatory</b></h4>
            </div>
             <div class="col-lg-12 text-center">
              <h5 class="card-header"><B>OFFICIAL SCHOOL TRANSCRIPT</B></h3>
            </div>
        </div>
        <div class ="row" id="ENS">
          <div class="col-lg-8 col-8">
            <div class="support-ticket media pb-1 mb-3">
              <img src="'.base_url().'/profile/'.$stuName->profile.'" style="width:70px;height: 80px;" class="user-img mr-2" alt="">
              <div class="media-body ml-3">
                <p class="my-1">NAME: <u>'.$stuName->fname.' '.$stuName->mname.' '.$stuName->lname.'</u></p>
                <p class="my-1">GENDER: <u>'.$stuName->gender.' </u></p>';
              $output.='</div>
            </div>
          </div>
          <div class="col-lg-4 col-4">
            <p class="my-1">Grade: <u>'.$stuName->gradesec.'</u></p>';
             if($stuName->age>0){
              $output.='<p class="my-1">Age: <u>'.$stuName->age.' </u></p>';
            }else{
              $output.='<p class="my-1">Age: _____</p>';
            }
          $output.='</div></div>';           
          $output.=' <div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">
          <tbody><tr> 
          <th class="text-center">ACADEMIC YEAR</th>';
          $targetYear=($academicyear-$noGrade) + 1;
          for($i=1;$i<=$noGrade;$i++){
            $queryGreYear=$this->db->query("select gyear from academicyear where year_name='$targetYear' ");
            if($queryGreYear->num_rows()>0){
              $rowG = $queryGreYear->row();
              $gmax_year=$rowG->gyear;
            }else{
              $gmax_year='-';
            }
            $output.='<th colspan="3" class="text-center">'.$gmax_year.' G.C | '.$targetYear.' E.C </th>';
            $targetYear=$targetYear+1;
          }
          $output.='<tr> 
          <th class="text-center">GRADE</th>';
          $targetYear=($academicyear-$noGrade) + 1;
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
              $checkRow=$queryLastGrade->row();
              $newGradesec=$checkRow->gradesec;
              $currGrade=$checkRow->grade;
              switch ($currGrade) {
                case '1':
                  $gradeName='ONE';
                  break;
                case '2':
                  $gradeName='TWO';
                  break;
                case '3':
                  $gradeName='THREE';
                  break;
                case '4':
                  $gradeName='FOUR';
                  break;
                case '5':
                  $gradeName='FIVE';
                  break;
                case '6':
                  $gradeName='SIX';
                  break;
                case '7':
                  $gradeName='SEVEN';
                  break;
                case '8':
                  $gradeName='EIGHT';
                  break;
                case '9':
                  $gradeName='NINE';
                  break;
                case '10':
                  $gradeName='TEN';
                  break;
                case '11':
                  $gradeName='ELEVEN';
                  break;
                case '11n':
                  $gradeName='ELEVEN';
                  break;
                case '11s':
                  $gradeName='ELEVEN';
                  break;
                case '12':
                  $gradeName='ELEVEN';
                  break;
                case '12n':
                  $gradeName='TWELVE';
                  break;
                case '12s':
                  $gradeName='TWELVE';
                  break;
                default:
                  $gradeName='-';
                  break;
              }
              $output.='<th colspan="3" class="text-center">'.$newGradesec.'('.$gradeName.')</th>';
            }else{
              $output.='<th colspan="3" class="text-center">-</th>'; 
            }
            $targetYear=$targetYear+1;
          }
          $output.='<tr><th rowspan="2" class="text-center">SUBJECT</th>';
          for($i=1;$i<=$noGrade;$i++){
            $output.='<th colspan="3" class="text-center">SEMESTER</th>';
          }
          $output.='</tr>';
          $targetYear=($academicyear-$noGrade) + 1;
          for($i=1;$i<=$noGrade;$i++){
            $output.='<th class="text-center">I</th>
            <th class="text-center">II</th>
            <th class="text-center">AV</th>';
            $targetYear=$targetYear+1;
          }
          $output.='</tr>';
        $querySubjecy=$this->db->query("select * from transcript_list where onreportcard='1' group by subject order by suborder ASC");
        if($querySubjecy->num_rows()>0){
          foreach ($querySubjecy->result() as $subjValue) {
            $subjName=$subjValue->subject;
            $output.='<tr><td>'.$subjName.'</td>';
            $targetYear=($academicyear-$noGrade) + 1;
            for($i=1;$i<=$noGrade;$i++){
              $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
              if($queryLastGrade->num_rows()>0){
                foreach($queryLastGrade->result() as $stuRow){
                  $newGradesec=$stuRow->gradesec;
                  $currGrade=$stuRow->grade;
                  $stuid=$stuRow->id;
                  $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
                  if ($queryCheck->num_rows()>0 ){
                    $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' and subject='$subjName'");
                    foreach ($quartrSem1Total->result() as $totalValueSem1) {
                      $printValueSem1=($totalValueSem1->total);
                      if($printValueSem1 >0){
                        if($letter!='A'){                          
                          if($printValueSem1=='100'){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,0,'.','').'</td>';
                          }else{
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem1 between minValue and maxiValue and academicYear='$targetYear'");
                          if($queryRange->num_rows()>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center'>".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center'> -</td>";
                          }
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' and subject='$subjName' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem2=($totalValueSem1->total);
                      if($printValueSem2 >0){
                        if($letter!='A'){                          
                          if($printValueSem2=='100'){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,0,'.','').'</td>';
                          }else{
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem2 between minValue and maxiValue and academicYear='$targetYear'");
                          if($queryRange->num_rows()>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center'>".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center'> -</td>";
                          }
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Totalver=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#' and subject='$subjName'  ");
                    foreach ($quartrSem2Totalver->result() as $totalValueSem1Ave) {
                      $printValueSem1Ave=($totalValueSem1Ave->total)/2;
                      
                      if($printValueSem1Ave >0){
                        if($letter!='A'){                          
                          if($printValueSem1Ave=='100'){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1Ave,0,'.','').'</td>';
                          }else{
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1Ave,2,'.','').'</td>';
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem1Ave between minValue and maxiValue and academicYear='$targetYear'");
                          if($queryRange->num_rows()>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center'>".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center'> -</td>";
                          }
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                  }else{
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              $targetYear=$targetYear+1;
            }
          }
          $targetYear=($academicyear-$noGrade) + 1;
          $output.='<tr><td>GRAND TOTAL</td>';
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
              foreach($queryLastGrade->result() as $stuRow){
                $newGradesec=$stuRow->gradesec;
                $currGrade=$stuRow->grade;
                $stuid=$stuRow->id;
                $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
                if ($queryCheck->num_rows()>0 ){
                  $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                  foreach ($quartrSem2Total->result() as $totalValueSem1) {
                    $printValueSem1=($totalValueSem1->total);
                    if($printValueSem1 >0){
                      $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                  $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
                  foreach ($quartrSem2Total->result() as $totalValueSem1) {
                    $printValueSem1=($totalValueSem1->total);
                    if($printValueSem1 >0){
                      $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                  $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                  foreach ($quartrSem2Total->result() as $totalValueSem1) {
                    $printValueSem1=(($totalValueSem1->total)/2);
                    if($printValueSem1 >0){
                      $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                }else{
                  $output.='<td class="text-center">-</td>';
                  $output.='<td class="text-center">-</td>';
                  $output.='<td class="text-center">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
            }
            $targetYear=$targetYear+1;
          }
          $output.='<tr><td>AVERAGE</td>';
          $targetYear=($academicyear-$noGrade) + 1;   
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
              foreach($queryLastGrade->result() as $stuRow){
                $newGradesec=$stuRow->gradesec;
                $currGrade=$stuRow->grade;
                $stuid=$stuRow->id;
                $queryCountSub=$this->db->query("select * from transcript_list where academicyear='$targetYear' and letter='#' and onreportcard='1' and grade='$currGrade' group by subject order by suborder ASC");
                if($queryCountSub->num_rows()>0){
                  $subALast=$queryCountSub->num_rows();
                }else{
                  $subALast=1;
                }
                $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
                if ($queryCheck->num_rows()>0 ){
                  $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                  foreach ($quartrSem2Total->result() as $totalValueSem1) {
                    $printValueSem1=($totalValueSem1->total);
                    if($printValueSem1 >0){
                      $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                  $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
                  foreach ($quartrSem2Total->result() as $totalValueSem1) {
                    $printValueSem1=($totalValueSem1->total);
                    if($printValueSem1 >0){
                      $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                  $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                  foreach ($quartrSem2Total->result() as $totalValueSem1) {
                    $printValueSem1=(($totalValueSem1->total)/2);
                    if($printValueSem1 >0){
                      $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
            }
            }else{
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
            }
            $targetYear=$targetYear+1;
          }
        }
        $output.='
        </tbody>
        </table>
        </div>';
        $output.='<div class ="row" id="ENS">
            <div class="col-lg-12 col-12">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <p class="my-1">LAST GRADE ATTENDED IN WORD:<u>'.$gradeName.'</u></p>';
                  $queryInfo=$this->db->query("select * from leavingreasoninfo where stuid='$username' and academicyear='$academicyear' ");
                  if($queryInfo->num_rows()>0){
                    $rowInfo=$queryInfo->row();
                    $reasonIssue=$rowInfo->reasoname;
                    $output.='<p class="my-1">REASON FOR ISSUE:<u>'.$reasonIssue.'</u></p>';
                  }else{
                    $output.='<p class="my-1">REASON FOR ISSUE:<u>Completed Grade '.$gradeName.'</u></p>';
                  }
                $output.='</div>
              </div>
            </div>
            <div class="col-lg-12 col-12">
              <p class="my-1">Note: THIS TRANSCRIPT IS INVALID IF ANY ALTERNATION OR ERASER COMMITS & UNLESS IT BEARS THE OFFICIAL SEAL OF THE SCHOOL.</p>
            </div>
            <div class="col-lg-6 col-6">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <span class="">Record officers Name :_____________________</span><br>
                  <span class="">Signature :_______________</span> </br>
                  <span class="">Date  :_______________</span>  
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-6">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <span class="">Directors Name and Signature :_____________________</span><br>
                  <span class="">Signature :_______________</span> </br>
                  <span class="">Date  :_______________</span>  
                </div>
              </div>
            </div>
          </div>
            <div style="background-color:#e3e3e3;">
              <div class ="row">
                <div class="col-md-4 col-4">
                    P.O.Box <br> ፖ.ሳ.ቁ  28643
                </div>
                <div class="col-md-4 col-4">
                    Tel: +251-118-8888-60/ +251-118-8888-61/ +251-118-8888-66
                </div>
                <div class="col-md-4 col-4">
                    Fax:<br>ፋክስ______
                </div>
              </div>
            </div>
          ';
      }
    }
    return $output;
  }
  function letterCustomTranscriptCrosspoint($academicyear,$gradesec,$branch,$userName,$noGrade)
  {
    $output='';
    $targetYear=$academicyear;
    $queryUsers=$this->db->query("select fname,mname,lname,gender,gradesec,age,username, unique_id, grade, id, profile from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and status='Active' and isapproved='1' and academicyear='$academicyear' and username='$userName' order by fname,mname,lname ASC ");
    if($queryUsers->num_rows()>0){
      $rowSubject=$queryUsers->row_array();
      $gradeSubject=$rowSubject['grade'];
    }else{
      $gradeSubject='';
    }
    $data=array();
    for($i=$noGrade;$i>=1;$i--){
      $querySubjects=$this->db->query("select * from subject where Academic_Year='$targetYear' and Grade='$gradeSubject' group by Subj_name order by suborder ");
      if($querySubjects->num_rows()>0){
        foreach($querySubjects->result() as $subName){
          if($subName->Merged_name==''){
            $subject=$subName->Subj_name;
            $onreportcard=$subName->onreportcard;
          }else{
            $subject=$subName->Merged_name;
            $onreportcard='1';
          }
          $letter=$subName->letter;
          $suborder=$subName->suborder;
          $data[]=array(
            'grade'=>$gradeSubject,
            'subject'=>$subject,
            'letter'=>$letter,
            'onreportcard'=>$onreportcard,
            'suborder'=>$suborder,
            'academicyear'=>$targetYear,
          );
        }
        $targetYear=$academicyear-1;
        switch ($gradeSubject) {
           case 'KG3':
            $gradeSubject='KG2';
            break;
          case 'KG2':
            $gradeSubject='KG1';
            break;
          case 'KG1':
            $gradeSubject='-';
            break;
          case '11n':
            $gradeSubject='10';
            break;
          case '11s':
            $gradeSubject='10';
            break;
          case '12n':
            $gradeSubject='11n';
            break;
          case '12s':
            $gradeSubject='11s';
            break;
            case '11N':
            $gradeSubject='10';
            break;
          case '11S':
            $gradeSubject='10';
            break;
          case '12N':
            $gradeSubject='11n';
            break;
          case '12S':
            $gradeSubject='11s';
            break;
          case '12NS':
            $gradeSubject='12NS';
            break;
          case '12SS':
            $gradeSubject='12SS';
            break;
          case '11NS':
            $gradeSubject='11NS';
            break;
          case '11SS':
            $gradeSubject='11SS';
            break;
          default:
            $gradeSubject=$gradeSubject-1;
            break;
        }
      }
    }
    if(!empty($data)){
      $queryInsert=$this->db->insert_batch('transcript_list',$data);
    }
    $queryTr=$this->db->query("select fname,mname,lname,gender,gradesec,age,username, unique_id, grade, id, profile from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and status='Active' and isapproved='1' and academicyear='$academicyear' and username='$userName' order by fname,mname,lname ASC ");
    $query_name = $this->db->query("select * from school");
    $row_name = $query_name->row();
    $school_name=$row_name->name;
    $schooLogo=$row_name->logo;
    $website=$row_name->website;
    if($queryTr->num_rows()>0){
      $rowcheckGrade=$queryTr->row();
      $cGrade=$rowcheckGrade->grade;
      $queryAGreYear=$this->db->query("select gyear from academicyear where year_name='$academicyear' ");
      $rowAG = $queryAGreYear->row();
      $Agmax_year=$rowAG->gyear;
      foreach ($queryTr->result() as $stuName) {
        $uniqueId=$stuName->unique_id;
        $currGrade=$stuName->grade;
        $currID=$stuName->id;
        $stuid=$stuName->id;
        $username=$stuName->username;
        $output.='<div style="width:100%;height:92%;page-break-inside:avoid;">';
        $output.='<div class ="row" id="ENS">
            <div class="col-lg-5 col-5 text-center">
              <h4>ሲያትል አካዳሚ <h4>
              <h4><b>ከአፀደ-ህፃናት - ኮሌጅ መሰናዶ</b></h4>
            </div>
            <div class="col-lg-2 col-2 text-center">
              <img class="text-center" src="'.base_url().'/logo/'.$schooLogo.'" style="height:110px;width:110px;border:1px solid #fff;" >
            </div>
            <div class="col-lg-5 col-5 text-center">
              <h4><b>'.$school_name.'</b></h4>
              <h4><b>KG – Preparatory</b></h4>
            </div>
             <div class="col-lg-12 text-center">
              <h5 class="card-header"><B>OFFICIAL SCHOOL TRANSCRIPT</B></h3>
            </div>
        </div>
        <div class ="row" id="ENS">
          <div class="col-lg-8 col-8">
            <div class="support-ticket media pb-1 mb-3">
              <img src="'.base_url().'/profile/'.$stuName->profile.'" style="width:70px;height: 80px;" class="user-img mr-2" alt="">
              <div class="media-body ml-3">
                <p class="my-1">NAME: <u>'.$stuName->fname.' '.$stuName->mname.' '.$stuName->lname.'</u></p>
                <p class="my-1">GENDER: <u>'.$stuName->gender.' </u></p>';
              $output.='</div>
            </div>
          </div>
          <div class="col-lg-4 col-4">
            <p class="my-1">Grade: <u>'.$stuName->gradesec.'</u></p>';
             if($stuName->age>0){
              $output.='<p class="my-1">Age: <u>'.$stuName->age.' </u></p>';
            }else{
              $output.='<p class="my-1">Age: _____</p>';
            }
          $output.='</div></div>';           
          $output.=' <div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">
          <tbody><tr> 
          <th class="text-center">ACADEMIC YEAR</th>';
          $targetYear=($academicyear-$noGrade) + 1;
          for($i=1;$i<=$noGrade;$i++){
            $queryGreYear=$this->db->query("select gyear from academicyear where year_name='$targetYear' ");
            if($queryGreYear->num_rows()>0){
              $rowG = $queryGreYear->row();
              $gmax_year=$rowG->gyear;
            }else{
              $gmax_year='-';
            }
            $output.='<th colspan="3" class="text-center">'.$gmax_year.' G.C | '.$targetYear.' E.C </th>';
            $targetYear=$targetYear+1;
          }
          $output.='<tr> 
          <th class="text-center">GRADE</th>';
          $targetYear=($academicyear-$noGrade) + 1;
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
              $checkRow=$queryLastGrade->row();
              $newGradesec=$checkRow->gradesec;
              $currGrade=$checkRow->grade;
              switch ($currGrade) {
                case '1':
                  $gradeName='ONE';
                  break;
                case '2':
                  $gradeName='TWO';
                  break;
                case '3':
                  $gradeName='THREE';
                  break;
                case '4':
                  $gradeName='FOUR';
                  break;
                case '5':
                  $gradeName='FIVE';
                  break;
                case '6':
                  $gradeName='SIX';
                  break;
                case '7':
                  $gradeName='SEVEN';
                  break;
                case '8':
                  $gradeName='EIGHT';
                  break;
                case '9':
                  $gradeName='NINE';
                  break;
                case '10':
                  $gradeName='TEN';
                  break;
                case '11':
                  $gradeName='ELEVEN';
                  break;
                case '11n':
                  $gradeName='ELEVEN';
                  break;
                case '11s':
                  $gradeName='ELEVEN';
                  break;
                case '12':
                  $gradeName='ELEVEN';
                  break;
                case '12n':
                  $gradeName='TWELVE';
                  break;
                case '12s':
                  $gradeName='TWELVE';
                  break;
                default:
                  $gradeName='-';
                  break;
              }
              $output.='<th colspan="3" class="text-center">'.$newGradesec.'('.$gradeName.')</th>';
            }else{
              $output.='<th colspan="3" class="text-center">-</th>'; 
            }
            $targetYear=$targetYear+1;
          }
          $output.='<tr><th rowspan="2" class="text-center">SUBJECT</th>';
          for($i=1;$i<=$noGrade;$i++){
            $output.='<th colspan="3" class="text-center">SEMESTER</th>';
          }
          $output.='</tr>';
          $targetYear=($academicyear-$noGrade) + 1;
          for($i=1;$i<=$noGrade;$i++){
            $output.='<th class="text-center">I</th>
            <th class="text-center">II</th>
            <th class="text-center">AV</th>';
            $targetYear=$targetYear+1;
          }
          $output.='</tr>';
        $querySubjecy=$this->db->query("select * from transcript_list where onreportcard='1' group by subject order by suborder ASC");
        if($querySubjecy->num_rows()>0){
          foreach ($querySubjecy->result() as $subjValue) {
            $subjName=$subjValue->subject;
            $output.='<tr><td>'.$subjName.'</td>';
            $targetYear=($academicyear-$noGrade) + 1;
            for($i=1;$i<=$noGrade;$i++){
              $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
              if($queryLastGrade->num_rows()>0){
                foreach($queryLastGrade->result() as $stuRow){
                  $newGradesec=$stuRow->gradesec;
                  $currGrade=$stuRow->grade;
                  $stuid=$stuRow->id;
                  $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
                  if ($queryCheck->num_rows()>0 ){
                    $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and subject='$subjName'");
                    foreach ($quartrSem1Total->result() as $totalValueSem1) {
                      $printValueSem1=($totalValueSem1->total);
                      $valFirst=number_format((float)$printValueSem1,0,'.','');
                      if($printValueSem1 >0){
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $valFirst between minValue and maxiValue and academicYear='$targetYear'");
                        if($queryRange->num_rows()>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center'> -</td>";
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and subject='$subjName' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem12=($totalValueSem1->total);
                      $valSecond=number_format((float)$printValueSem12,0,'.','');
                      if($printValueSem12 >0){
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $valSecond between minValue and maxiValue and academicYear='$targetYear'");
                        if($queryRange->num_rows()>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center'> -</td>";
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Totalver=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and subject='$subjName'  ");
                    foreach ($quartrSem2Totalver->result() as $totalValueSem1Ave) {
                      $printValueSem1Ave=($totalValueSem1Ave->total)/2;
                      $valAve=number_format((float)$printValueSem1Ave,0,'.','');
                      if($printValueSem1Ave >0){
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $valAve between minValue and maxiValue and academicYear='$targetYear'");
                        if($queryRange->num_rows()>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center'> -</td>";
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                  }else{
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
              $targetYear=$targetYear+1;
            }
          }
          /*$targetYear=($academicyear-$noGrade) + 1;
          $output.='<tr><td>GRAND TOTAL</td>';
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
              $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
              if ($queryCheck->num_rows()>0 ){
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1A=($totalValueSem1->total);
                  if($printValueSem1A >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$lastGr' and $printValueSem1A between minValue and maxiValue and academicYear='$targetYear'");
                    if($queryRange->num_rows()>0){
                      foreach ($queryRange->result() as $letterValue) {
                        $letterVal=$letterValue->letterVal;
                        $output.= "<td class='text-center'>".$letterVal."</td>";
                      }
                    }else{
                      $output.= "<td class='text-center'> -</td>";
                    }
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1AV=($totalValueSem1->total);
                  if($printValueSem1AV >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$lastGr' and $printValueSem1AV between minValue and maxiValue and academicYear='$targetYear'");
                    if($queryRange->num_rows()>0){
                      foreach ($queryRange->result() as $letterValue) {
                        $letterVal=$letterValue->letterVal;
                        $output.= "<td class='text-center'>".$letterVal."</td>";
                      }
                    }else{
                      $output.= "<td class='text-center'> -</td>";
                    }
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1G=(($totalValueSem1->total)/2);
                  if($printValueSem1G >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$lastGr' and $printValueSem1G between minValue and maxiValue and academicYear='$targetYear'");
                    if($queryRange->num_rows()>0){
                      foreach ($queryRange->result() as $letterValue) {
                        $letterVal=$letterValue->letterVal;
                        $output.= "<td class='text-center'>".$letterVal."</td>";
                      }
                    }else{
                      $output.= "<td class='text-center'> -</td>";
                    }
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
            }else{
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
            }
            $targetYear=$targetYear+1;
          }*/
          /*$output.='<tr><td>AVERAGE</td>';
          $targetYear=($academicyear-$noGrade) + 1;   
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
              $gradeRow=$queryLastGrade->row();
              $currGrade=$gradeRow->grade;
              $queryCountSub=$this->db->query("select * from transcript_list where academicyear='$academicyear' and letter='#' and onreportcard='1' and grade='$currGrade' group by subject order by suborder ASC");
              if($queryCountSub->num_rows()>0){
                $subALast=$queryCountSub->num_rows();
              }else{
                $subALast=1;
              }
              $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
              if ($queryCheck->num_rows()>0 ){
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1=($totalValueSem1->total);
                  if($printValueSem1 >0){
                    $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1=($totalValueSem1->total);
                  if($printValueSem1 >0){
                    $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1=(($totalValueSem1->total)/2);
                  if($printValueSem1 >0){
                    $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }
            }else{
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
            }
            $targetYear=$targetYear+1;
          }*/
        }
        $output.='
        </tbody>
        </table>
        </div>';
        $output.='<div class ="row" id="ENS">
            <div class="col-lg-12 col-12">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <p class="my-1">LAST GRADE ATTENDED IN WORD:<u>'.$gradeName.'</u></p>';
                  $queryInfo=$this->db->query("select * from leavingreasoninfo where stuid='$username' and academicyear='$academicyear' ");
                  if($queryInfo->num_rows()>0){
                    $rowInfo=$queryInfo->row();
                    $reasonIssue=$rowInfo->reasoname;
                    $output.='<p class="my-1">REASON FOR ISSUE:<u>'.$reasonIssue.'</u></p>';
                  }else{
                    $output.='<p class="my-1">REASON FOR ISSUE:<u>Completed Grade '.$gradeName.'</u></p>';
                  }
                $output.='</div>
              </div>
            </div>
            <div class="col-lg-12 col-12">
              <p class="my-1">Note: THIS TRANSCRIPT IS INVALID IF ANY ALTERNATION OR ERASER COMMITS & UNLESS IT BEARS THE OFFICIAL SEAL OF THE SCHOOL.</p>
            </div>
            <div class="col-lg-6 col-6">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <span class="">Record officers Name :_____________________</span><br>
                  <span class="">Signature :_______________</span> </br>
                  <span class="">Date  :_______________</span>  
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-6">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <span class="">Directors Name and Signature :_____________________</span><br>
                  <span class="">Signature :_______________</span> </br>
                  <span class="">Date  :_______________</span>  
                </div>
              </div>
            </div>
          </div>
            <div style="background-color:#e3e3e3;">
              <div class ="row">
                <div class="col-md-4 col-4">
                    P.O.Box <br> ፖ.ሳ.ቁ  28643
                </div>
                <div class="col-md-4 col-4">
                    Tel: +251-118-8888-60/ +251-118-8888-61/ +251-118-8888-66
                </div>
                <div class="col-md-4 col-4">
                    Fax:<br>ፋክስ______
                </div>
              </div>
            </div>
          ';
      }
    }
    return $output;
  }
  function reportcardCrosspoint($max_year,$gradesec,$branch,$max_quarter,$includeBackPage){
    $output ='';
    $resultSem1=0;
    $resultSem2=0;
    $queryCHK=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
    if($queryCHK->num_rows()>0){
      $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade,gender,age,city,kebele, profile, section,gradesec,username,woreda from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
      $stuAll=$queryStudent->num_rows();

      /*$queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec,gender,age, kebele,woreda, city,profile, username from users where id='$id' ");
      
      $queryStudentNum=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' ");
      $stuAll=$queryStudentNum->num_rows();*/
      if($queryStudent->num_rows()>0){
        $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowGyear = $querySlogan->row();
        $gYearName=$rowGyear->gyear;
        $dateYear=date('Y');
        foreach ($queryStudent->result() as $fetchStudent)
        {
          $grade=$fetchStudent->grade;
          $stuid=$fetchStudent->id;
          $section=$fetchStudent->section;
          $username1=$fetchStudent->username;
          $grade_sec=$fetchStudent->gradesec;
          $fname=$fetchStudent->fname;
          $mname=$fetchStudent->mname;
          $lname=$fetchStudent->lname;
          $gender=$fetchStudent->gender;
          $age=$fetchStudent->age;
          $addresss=$fetchStudent->city;
          $woreda=$fetchStudent->woreda;
          $profile=$fetchStudent->profile;

          $output.= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; ">
          <div class="row" id="ENS">
          <div class="col-lg-6 col-md-6">';
          $output.="<h4 class='text-center text-info'><b>“Never settle for less than your best!”</b></h4>";
          $output.='<p class="text-center">Student Name:-<b> '.ucfirst(strtolower($fname)).' '.ucfirst(strtolower($mname)).' '.ucfirst(strtolower($lname)).'</b></p>';
          $output.='<div class="table-responsive">
          <table width="100%" height="80%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
          $output.='
          <tr><td class="text-center rotateJossRoster"><h3> Subjects</h3></td>
          <td class="text-center rotateJossRoster"><div> 1st Semester</div></td>
          <td class="text-center rotateJossRoster"><div> 2nd Semester</div></td>
          <td class="text-center rotateJossRoster"><div> Yearly Average</div></td> </tr>';
          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr><td style="white-space: nowrap"><B>'.$fetchSubject->subject.'</B></td>';/*Subject List*/
              $queryReportCardQ1=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Semester1' and subject='$subject' order by subjorder ");
              if($queryReportCardQ1->num_rows()>0){
                foreach ($queryReportCardQ1->result() as $fetchResult1) {
                  $result1=$fetchResult1->total;
                  if($fetchResult1->total=='' || $fetchResult1->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result1=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result1,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result1,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }

               /*fetch quarter 2 result starts*/
              $queryReportCardQ2=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Semester2' and subject='$subject' order by subjorder ");
              if($queryReportCardQ2->num_rows()>0){
                foreach ($queryReportCardQ2->result() as $fetchResult2) {
                  $result2=$fetchResult2->total;
                  if($fetchResult2->total=='' || $fetchResult2->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result2=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result2,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result2,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              /*Yearly Average calculation starts*/
              if($queryReportCardQ2->num_rows()>0){ 
                $YAve=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' ");
                foreach ($YAve->result() as $fetchYA) {
                  $resultYA=($fetchYA->total)/2;
                  if($fetchYA->total=='' || $fetchYA->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($resultYA=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$resultYA,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$resultYA,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultYA between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.= "<td class='text-center'> -</td>";
              }
                
              $output.='</tr>';
            }
          }
          /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
          $queryTotalAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='totalname' and grade='$grade' and allowed='1' ");
          if($queryTotalAllowed->num_rows()>0){
            $output.='<tr><td><b>Total</b></td>';
            $quarterArray1=array('Semester1','Semester2');
            foreach ($quarterArray1 as $quarterValue) {
              $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
              if($quartrTotal->num_rows()>0){
                foreach ($quartrTotal->result() as $totalValue) {
                  $printValue=$totalValue->total;
                  if($printValue >0){
                    $output .= '<td class="text-center"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            }
            /*Yearly Average Horizontal Total calculation starts*/
            $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
              if($quartrYATotal->num_rows()>0){
                  if($result1 ==0 || $result2 == 0){
                      $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($quartrYATotal->result() as $totalValueYA) {
                      $printValueYA=($totalValueYA->total)/2;
                      if($printValueYA >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                      
                  }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            $output.='</tr>';
          }

          /*Horizontal Average calculation starts*/
          $queryAverageAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='averagename' and grade='$grade' and allowed='1' ");
          if($queryAverageAllowed->num_rows()>0){
            $output.='<tr><td><b>Average</b></td>';
            /*1st and snd quarter calculation starts*/
            $quarterArray1=array('Semester1','Semester2');
            $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
            $subALl=$countSubject->num_rows();
            foreach ($quarterArray1 as $quarterValue) {
              if($subALl>0){
                $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
                if($quartrTotal->num_rows()>0){
                  foreach ($quartrTotal->result() as $totalValue) {
                    $printValue=($totalValue->total)/$subALl;
                    if($printValue >0){
                      $output .= '<td class="text-center"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                }else{
                  $output.='<td class="text-center">-</td>';
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            }
              /*$output.='<td class="text-center" colspan="2">-</td>';*/
            /*Yearly Average Horizontal Average calculation starts*/
           $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
              if($quartrYATotal->num_rows()>0){
                 if($result1 ==0 || $result2 == 0){
                      $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($quartrYATotal->result() as $totalValueYA) {
                      $printValueYA=(($totalValueYA->total)/2)/$subALl;
                      if($printValueYA >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                  }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            $output.='</tr>';
          }
          $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='rankname' and grade='$grade' and allowed='1' ");
          if($queryRankAllowed->num_rows()>0){
            $output.='<tr><td><b>Rank</b></td>';
            $quarterArray1=array('Semester1','Semester2');
            foreach ($quarterArray1 as $quarterValue) {
              $quarter=$quarterValue;
              $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid' and quarter='$quarter' group by quarter ");
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' and rpbranch='$branch' group by grade ");
              if($query_total->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                  $output .= '<td class="text-center"><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center">-</td>';
              }
            }
            /*Yearly Rank Horizontal Rank calculation starts*/

            $query_rankya=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid'  and academicyear='$max_year' and grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by grade ");
            if($result2 !==0 ){
              if($queryReportCardQ2->num_rows()>0)
              {
                foreach ($query_rankya ->result() as $row_rankya)
                {
                  $rankNew =$row_rankya->stuRank;
                  $output .= '<td class="text-center"><B>'.$rankNew.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center">-</td>';
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            $output.='</tr>';
          }
          /*Conduct calculation Starts*/
          $quarterArrayQ1=array('Semester1','Semester2');
            $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' group by bsname order by bsname ASC ");   
            if($query_basicskill->num_rows()>0){  
              $output.='<tr>';  
              foreach ($query_basicskill->result() as $bsvalue) {
                $bsname=$bsvalue->bsname;
                $output .='<td><B>'.$bsvalue->bsname.'</B></td>';
                foreach ($quarterArrayQ1 as $qvalue) {
                  $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                  if($query_bsvalue->num_rows()>0) {
                    foreach ($query_bsvalue ->result() as $bsresult) {
                      $output .='<td class="text-center">'.$bsresult->value.'</td>';
                    }
                  }else {
                    $output .='<td class="text-center">-</td>';
                  }
                }              
              }
              $output .='<td class="text-center">-</td>';
            }
          /*Conduct calculation ends*/
          
          $output.='</tr>';
          $output.="</table></div>
          <div class='dropdown-divider'></div>
          <div class='row'>
            <div class ='col-md-8 col-12'>
              <p>የርዕሰ መምህሩ ስም _________________________.<br>
              Principal’s Name</p>
            </div>
            <div class ='col-md-4 col-12'>
              <p>ፊርማ _________.<br>
              Sign</p>
            </div>
          </div>
          </div>";/*result table closed*/

          $output.= '<div class="col-lg-6 col-md-6">';
          $output.='<div class="row"><div class="col-lg-12">';
          $query_name = $this->db->query("select * from school");
            $row_name = $query_name->row();
            $school_name=$row_name->name;
            $address=$row_name->address;
            $phone=$row_name->phone;
            $website=$row_name->website;
            $email=$row_name->email;
            $logo=$row_name->logo;
            $output.='<h5 class="text-center text-info"><b><u>Homeroom Teacher Comments & Recommendations</u></b></h5>
              <p class="text-center">Grade:-<b> '.$grade.' &nbsp;&nbsp; Section:- '.$section.'</b></p>
                  <div class="row">
                    <div class="col-lg-12 col-12 reportcardView">';
                    $queryHoomRoom=$this->db->query("select u.fname,u.mname from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
                    if($queryHoomRoom->num_rows()>0){
                      $rowHommeRoom=$queryHoomRoom->row_array();
                      $tfName=$rowHommeRoom['fname'];
                      $tmName=$rowHommeRoom['mname'];
                    }else{
                      $tfName='------';
                      $tmName='------';
                    }
                    
                    $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    $subALl=$countSubject->num_rows();
                      if($subALl>0){
                        $output.="<p class='text-center'><b>1<sup>st</sup> Semester</b></p>";
                        $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                        if($quartrTotal->num_rows()>0){
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue1=($totalValue->total)/$subALl;

                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue1 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                            if($printValue1 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='______________________________________ _______________________  ____________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      }else{
                        $output.='No comment.';
                      }
                    $output.="
                      <p>HRT's Name: <u><b>".$tfName." ".$tmName."</b></u> Sig.________________________ </p>
                       Parent's Name: <u><b>".$mname." " .$lname." </b></u> Sig.________________________
                    </div>";

                    $output.='<div class="col-lg-12 col-12 reportcardView">';
                    if($subALl>0){
                        $output.="<p class='text-center'><b>2<sup>nd</sup> Semester</b></p>";
                        $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
                        if($quartrTotal->num_rows()>0){
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue2=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue2 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                            if($printValue2 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='______________________________________ _______________________  ____________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      }else{
                        $output.='No comment.';
                      }
                    $output.="
                      <p>HRT's Name: <u><b>".$tfName." ".$tmName."</b></u> Sig.________________________ </p>
                       Parent's Name: <u><b>".$mname." " .$lname." </b></u> Sig.________________________ 
                    </div>";

                    $output.='<div class="col-lg-12 col-12">
                    <p class="text-center"><u>የማርክ አሰጣጥ</u><br>
                    <u>Method of Marking</u></p>
                    <p class="text-center">
                      86% - 100% = A(Excellent)<br>
                      71% - 85%  = B(Very Good) <br>
                      60% - 70%  =  C(Satisfactory)<br>
                      Below 60%  =  NI (Needs Improvement)
                      </p>';
                    $queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade'  ");
                    if($queryRangeValue->num_rows()>0){
                      $output.= '<div id="ENS" class="table-responsive">
                      <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
                      $output .='<tr><th class="text-center" colspan="3">Letter Grade Evaluation Key</th></tr>';
                      foreach ($queryRangeValue->result() as $rangeValue) {
                        $output .='<tr><td class="text-center">'.$rangeValue->minValue.' - '.$rangeValue->maxiValue.'</td>';
                        $output .='<td class="text-center">'.$rangeValue->letterVal.'</td> </tr>';
                      }
                      $output.= '</table></div>';
                    }
                    $output.='</div>
            </div>';
            $printValue=0;
          $output .='</div>';

          $output .='</div><br>';/*basic skill column closed*/
          $output.='</div><div class="dropdown-divider2"></div></div>';/*class row closed*/
          if($includeBackPage=='1')
          {
            $query_name = $this->db->query("select * from school");
            $row_name = $query_name->row();
            $school_name=$row_name->name;
            $address=$row_name->address;
            $phone=$row_name->phone;
            $website=$row_name->website;
            $email=$row_name->email;
            $logo=$row_name->logo;
            $output.='<div style="width:100%;height:auto;page-break-inside:avoid; page-break-after:always;display: block; ">
            <div class="row" id="ENS">
              <div class="col-lg-6 col-6">
              <h3 class="text-center text-info"><strong><u>General Conduct & work habit </u></strong></h3>';
              $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
            if($queryCategory->num_rows()>0){
              foreach ($queryCategory->result() as $bscatvalue) {
                $bscategory=$bscatvalue->bscategory;
                $output.= '<div class="table-responsive">
                <table width="100%" id="ENS" class="tabler table-bordered table-md" cellspacing="5" cellpadding="5">';
                $dateYear=date('Y');
                $no=1;
                $output .='<tr>
                <th rowspan="2">'.$bscategory.'</th><th colspan="3" class="text-center">Semester</th></tr><tr>';
                $quarterArrayQ=array('Semester1','Semester2');
                foreach ($quarterArrayQ as $qvalue) 
                {
                  if($qvalue=='Semester1'){
                    $output .='<th class="text-center">1<sup>st</sup> Semester</th>';
                  }else{
                    $output .='<th class="text-center">2<sup>nd</sup> Semester</th>';
                  }
                }
                $output .='<th class="text-center">Yearly Average</th>';
                  $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and bscategory='$bscategory' order by bsname ASC ");    
                  $output .='<tr>';
                  foreach ($query_basicskill->result() as $bsvalue) {
                    $bsname=$bsvalue->bsname;
                    $output .='<tr><td>'.$bsvalue->bsname.'</td>';
                    foreach ($quarterArrayQ as $qvalue) {
                      $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                      if($query_bsvalue->num_rows()>0) {
                        foreach ($query_bsvalue ->result() as $bsresult) {
                          $output .='<td class="text-center">'.$bsresult->value.'</td>';
                        }
                      }else {
                        $output .='<td class="text-center">-</td>';
                      }
                    } 
                    $output .='<td class="text-center">-</td>';
                    $output .='</tr>';
                  }
                  $output .='</table></div><br>';/*basic skill table closed*/
                }
              }
              $queryEvaKey=$this->db->query("select * from bstype where academicyear='$max_year' and btgrade='$grade' ");
              if($queryEvaKey->num_rows()>0){
                $output.= '<div class="row">';
                $output .='<div class="col-lg-12 col-12 text-center" id="BGS"><h4 style="font-family:century_gothicbold;">GRADING CODE</h4></div>';
                foreach ($queryEvaKey->result() as $keyVValue) {
                  $output .='<div class="col-lg-6 col-12"><h5 style="font-family:century_gothicbold;">'.$keyVValue->bstype.'=> '.$keyVValue->bsdesc.'</h5></div>';
                }
                $output .='</div>';/*Evaluation key table closed*/
              }else{
                $output .='No Evaluation Key found';
              }
                $output.='<div class="StudentViewTextInfo">
                  <p class="text-center"><h3>Vision of Seattle Academy</h3></p>
                    <small><i class="fas fa-check-circle"></i> Creating a conducive environment where every member of the school community is accountable so as to provide quality education that makes every student best achiever and successful. </small>
                </div>
              </div>
              <div class="col-lg-6 col-6">
                <div class="row">
                  <div class="col-lg-12 col-12">';
                  $output.='<p class="text-center"><img class="text-center" src="'.base_url().'/logo/'.$logo.'" style="height:150px;width:150px;border:1px solid #fff; -webkit-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;border-radius:10px" ></p>
                  </div>
                  <div class="col-lg-12 col-12">
                    <p class="text-center"><i class="fas fa-phone-square"></i>: +251-11-888-35-04/+9-03-21-44444<br><i class="fas fa-envelope"></i> '.$email.'<br><i class="fas fa-envelope"></i> 28643   Code 1000<br>Website address- www.seattleacademyethiopia.com<br>Addis Ababa, Ethiopia</p>
                      <h4 class="text-center"><u><b>የተማሪ ውጤት መግለጫ<br>Student’s Report Card</b></u></h4>
                    <div class="row">
                      <div class="col-lg-9 col-12">
                        <div class="row">
                          <div class="col-lg-12 col-12">
                            የተማሪዉ ስም
                            <p>Student Name:-<b> <u>'.ucfirst(strtolower($fname)).' '.ucfirst(strtolower($mname)).' '.ucfirst(strtolower($lname)).'</u></b></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            ዕድሜ <p>Age <u>'.$age.' </u></p> 
                          </div> 
                          <div class="col-lg-6 col-6">
                            ፆታ <p>Sex <u>'.$gender.' </u></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            <p>Grade <u>'.$grade.' </u></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            <p>Section<u> '.$section.'</u> </p>
                          </div> 
                          <div class="col-lg-6 col-6">
                            ክ/ከተማ<p>Subcity <u>'.$addresss.' </u></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            ወረዳ<p>Woreda<u> '.$woreda.'</u> </p>
                          </div> 
                          <div class="col-lg-12 col-12">
                            የትምህርት ዘመን <p>Academic Year <u>'.$max_year.'E.C ('.$gYearName.'G.C) </u></p>
                          </div>
                          <div class="col-lg-12 col-12">
                            ወደ ______ ክፍል ተዛውሯል/ለች፡፡ <p>Promoted to Grade</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-12">';
                        if($profile == ''){
                          $output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
                        }else{
                          $output.='<img alt="Photo" src="'.base_url().'/profile/'.$profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
                        }
                      $output.='</div>
                    </div>
                  </div>
                </div>
              </div>

              
              </div>
            </div>';
          }
        }
      } 
    }
    return $output;
  }
  function customReportCardCrosspoint($max_year,$gradesec,$branch,$rpQuarter,$id,$includeBackPage){
    $output ='';
    $resultSem1=0;
    $resultSem2=0;
    $queryCHK=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
    if($queryCHK->num_rows()>0){
      /*$queryStudent=$this->db->query(" Select fname,mname,lname,id,grade,gender,age,city,kebele, profile, section,gradesec,username,woreda from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
      $stuAll=$queryStudent->num_rows();*/

      $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, gender,age, kebele,woreda, city,profile, username from users where id='$id' ");
      $queryStudentNum=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' ");
      $stuAll=$queryStudentNum->num_rows();
      if($queryStudent->num_rows()>0){
        $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowGyear = $querySlogan->row();
        $gYearName=$rowGyear->gyear;
        $dateYear=date('Y');
        foreach ($queryStudent->result() as $fetchStudent)
        {
          $grade=$fetchStudent->grade;
          $stuid=$fetchStudent->id;
          $section=$fetchStudent->section;
          $username1=$fetchStudent->username;
          $grade_sec=$fetchStudent->gradesec;
          $fname=$fetchStudent->fname;
          $mname=$fetchStudent->mname;
          $lname=$fetchStudent->lname;
          $gender=$fetchStudent->gender;
          $age=$fetchStudent->age;
          $addresss=$fetchStudent->city;
          $woreda=$fetchStudent->woreda;
          $profile=$fetchStudent->profile;

          $output.= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; ">
          <div class="row" id="ENS">
          <div class="col-lg-6 col-md-6">';
          $output.="<h4 class='text-center text-info'><b>“Never settle for less than your best!”</b></h4>";
          $output.='<p class="text-center">Student Name:-<b> '.ucfirst(strtolower($fname)).' '.ucfirst(strtolower($mname)).' '.ucfirst(strtolower($lname)).'</b></p>';
          $output.='<div class="table-responsive">
          <table width="100%" height="80%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
          $output.='
          <tr><td class="text-center rotateJossRoster"><h3> Subjects</h3></td>
          <td class="text-center rotateJossRoster"><div> 1st Semester</div></td>
          <td class="text-center rotateJossRoster"><div> 2nd Semester</div></td>
          <td class="text-center rotateJossRoster"><div> Yearly Average</div></td> </tr>';
          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr><td style="white-space: nowrap"><B>'.$fetchSubject->subject.'</B></td>';/*Subject List*/
              $queryReportCardQ1=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Semester1' and subject='$subject' order by subjorder ");
              if($queryReportCardQ1->num_rows()>0){
                foreach ($queryReportCardQ1->result() as $fetchResult1) {
                  $result1=$fetchResult1->total;
                  if($fetchResult1->total=='' || $fetchResult1->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result1=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result1,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result1,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }

               /*fetch quarter 2 result starts*/
              $queryReportCardQ2=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Semester2' and subject='$subject' order by subjorder ");
              if($queryReportCardQ2->num_rows()>0){
                foreach ($queryReportCardQ2->result() as $fetchResult2) {
                  $result2=$fetchResult2->total;
                  if($fetchResult2->total=='' || $fetchResult2->total<=0){
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result2=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result2,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result2,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              /*Yearly Average calculation starts*/
              if($queryReportCardQ2->num_rows()>0){ 
                $YAve=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' ");
                foreach ($YAve->result() as $fetchYA) {
                  $resultYA=($fetchYA->total)/2;
                  if($fetchYA->total=='' || $fetchYA->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($resultYA=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$resultYA,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$resultYA,2,'.','').'</td>';
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultYA between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                  }
                }
              }else{
                $output.= "<td class='text-center'> -</td>";
              }
                
              $output.='</tr>';
            }
          }
          /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
          $queryTotalAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='totalname' and grade='$grade' and allowed='1' ");
          if($queryTotalAllowed->num_rows()>0){
            $output.='<tr><td><b>Total</b></td>';
            $quarterArray1=array('Semester1','Semester2');
            foreach ($quarterArray1 as $quarterValue) {
              $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
              if($quartrTotal->num_rows()>0){
                foreach ($quartrTotal->result() as $totalValue) {
                  $printValue=$totalValue->total;
                  if($printValue >0){
                    $output .= '<td class="text-center"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            }
            /*Yearly Average Horizontal Total calculation starts*/
            $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
              if($quartrYATotal->num_rows()>0){
                  if($result1 ==0 || $result2 == 0){
                      $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($quartrYATotal->result() as $totalValueYA) {
                      $printValueYA=($totalValueYA->total)/2;
                      if($printValueYA >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                      
                  }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            $output.='</tr>';
          }

          /*Horizontal Average calculation starts*/
          $queryAverageAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='averagename' and grade='$grade' and allowed='1' ");
          if($queryAverageAllowed->num_rows()>0){
            $output.='<tr><td><b>Average</b></td>';
            /*1st and snd quarter calculation starts*/
            $quarterArray1=array('Semester1','Semester2');
            $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
            $subALl=$countSubject->num_rows();
            foreach ($quarterArray1 as $quarterValue) {
              if($subALl>0){
                $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
                if($quartrTotal->num_rows()>0){
                  foreach ($quartrTotal->result() as $totalValue) {
                    $printValue=($totalValue->total)/$subALl;
                    if($printValue >0){
                      $output .= '<td class="text-center"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
                    }else{
                      $output.='<td class="text-center">-</td>';
                    }
                  }
                }else{
                  $output.='<td class="text-center">-</td>';
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            }
              /*$output.='<td class="text-center" colspan="2">-</td>';*/
            /*Yearly Average Horizontal Average calculation starts*/
           $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
              if($quartrYATotal->num_rows()>0){
                  if($result1 ==0 || $result2 == 0){
                      $output.='<td class="text-center">-</td>';
                  }else{
                    foreach ($quartrYATotal->result() as $totalValueYA) {
                      $printValueYA=(($totalValueYA->total)/2)/$subALl;
                      if($printValueYA >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                  }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            $output.='</tr>';
          }
          $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='rankname' and grade='$grade' and allowed='1' ");
          if($queryRankAllowed->num_rows()>0){
            $output.='<tr><td><b>Rank</b></td>';
            $quarterArray1=array('Semester1','Semester2');
            foreach ($quarterArray1 as $quarterValue) {
              $quarter=$quarterValue;
              $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid' and quarter='$quarter' group by quarter ");
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' and rpbranch='$branch' group by grade ");
              if($query_total->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                  $output .= '<td class="text-center"><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center">-</td>';
              }
            }
            /*Yearly Rank Horizontal Rank calculation starts*/

            $query_rankya=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid'  and academicyear='$max_year' and grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by grade ");
            if($result2 !==0 ){
              if($queryReportCardQ2->num_rows()>0)
              {
                foreach ($query_rankya ->result() as $row_rankya)
                {
                  $rankNew =$row_rankya->stuRank;
                  $output .= '<td class="text-center"><B>'.$rankNew.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center">-</td>';
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            $output.='</tr>';
          }
          /*Conduct calculation Starts*/
          $quarterArrayQ1=array('Semester1','Semester2');
            $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' group by bsname order by bsname ASC ");   
            if($query_basicskill->num_rows()>0){  
              $output.='<tr>';  
              foreach ($query_basicskill->result() as $bsvalue) {
                $bsname=$bsvalue->bsname;
                $output .='<td><B>'.$bsvalue->bsname.'</B></td>';
                foreach ($quarterArrayQ1 as $qvalue) {
                  $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                  if($query_bsvalue->num_rows()>0) {
                    foreach ($query_bsvalue ->result() as $bsresult) {
                      $output .='<td class="text-center">'.$bsresult->value.'</td>';
                    }
                  }else {
                    $output .='<td class="text-center">-</td>';
                  }
                }              
              }
              $output .='<td class="text-center">-</td>';
            }
          /*Conduct calculation ends*/
          
          $output.='</tr>';
          $output.="</table></div>
          <div class='dropdown-divider'></div>
          <div class='row'>
            <div class ='col-md-8 col-12'>
              <p>የርዕሰ መምህሩ ስም _________________________.<br>
              Principal’s Name</p>
            </div>
            <div class ='col-md-4 col-12'>
              <p>ፊርማ _________.<br>
              Sign</p>
            </div>
          </div>
          </div>";/*result table closed*/

          $output.= '<div class="col-lg-6 col-md-6">';
          $output.='<div class="row"><div class="col-lg-12">';
          $query_name = $this->db->query("select * from school");
            $row_name = $query_name->row();
            $school_name=$row_name->name;
            $address=$row_name->address;
            $phone=$row_name->phone;
            $website=$row_name->website;
            $email=$row_name->email;
            $logo=$row_name->logo;
            $output.='<h5 class="text-center text-info"><b><u>Homeroom Teacher Comments & Recommendations</u></b></h5>
              <p class="text-center">Grade:-<b> '.$grade.' &nbsp;&nbsp; Section:- '.$section.'</b></p>
                  <div class="row">
                    <div class="col-lg-12 col-12 reportcardView">';
                    $queryHoomRoom=$this->db->query("select u.fname,u.mname from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
                    if($queryHoomRoom->num_rows()>0){
                      $rowHommeRoom=$queryHoomRoom->row_array();
                      $tfName=$rowHommeRoom['fname'];
                      $tmName=$rowHommeRoom['mname'];
                    }else{
                      $tfName='------';
                      $tmName='------';
                    }
                    
                    $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    $subALl=$countSubject->num_rows();
                      if($subALl>0){
                        $output.="<p class='text-center'><b>1<sup>st</sup> Semester</b></p>";
                        $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                        if($quartrTotal->num_rows()>0){
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue1=($totalValue->total)/$subALl;

                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue1 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                            if($printValue1 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='______________________________________ _______________________  ____________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      }else{
                        $output.='No comment.';
                      }
                    $output.="
                      <p>HRT's Name: <u><b>".$tfName." ".$tmName."</b></u> Sig.________________________ </p>
                       Parent's Name: <u><b>".$mname." " .$lname." </b></u> Sig.________________________
                    </div>";

                    $output.='<div class="col-lg-12 col-12 reportcardView">';
                    if($subALl>0){
                        $output.="<p class='text-center'><b>2<sup>nd</sup> Semester</b></p>";
                        $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
                        if($quartrTotal->num_rows()>0){
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue2=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue2 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                            if($printValue2 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='______________________________________ _______________________  ____________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      }else{
                        $output.='No comment.';
                      }
                    $output.="
                      <p>HRT's Name: <u><b>".$tfName." ".$tmName."</b></u> Sig.________________________ </p>
                       Parent's Name: <u><b>".$mname." " .$lname." </b></u> Sig.________________________ 
                    </div>";

                    $output.='<div class="col-lg-12 col-12">
                    <p class="text-center"><u>የማርክ አሰጣጥ</u><br>
                    <u>Method of Marking</u></p>
                    <p class="text-center">
                      86% - 100% = A(Excellent)<br>
                      71% - 85%  = B(Very Good) <br>
                      60% - 70%  =  C(Satisfactory)<br>
                      Below 60%  =  NI (Needs Improvement)
                      </p>';
                    $queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade'  ");
                    if($queryRangeValue->num_rows()>0){
                      $output.= '<div id="ENS" class="table-responsive">
                      <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
                      $output .='<tr><th class="text-center" colspan="3">Letter Grade Evaluation Key</th></tr>';
                      foreach ($queryRangeValue->result() as $rangeValue) {
                        $output .='<tr><td class="text-center">'.$rangeValue->minValue.' - '.$rangeValue->maxiValue.'</td>';
                        $output .='<td class="text-center">'.$rangeValue->letterVal.'</td> </tr>';
                      }
                      $output.= '</table></div>';
                    }
                    $output.='</div>
            </div>';
            $printValue=0;
          $output .='</div>';

          $output .='</div><br>';/*basic skill column closed*/
          $output.='</div><div class="dropdown-divider2"></div></div>';/*class row closed*/
          if($includeBackPage=='1')
          {
            $query_name = $this->db->query("select * from school");
            $row_name = $query_name->row();
            $school_name=$row_name->name;
            $address=$row_name->address;
            $phone=$row_name->phone;
            $website=$row_name->website;
            $email=$row_name->email;
            $logo=$row_name->logo;
            $output.='<div style="width:100%;height:auto;page-break-inside:avoid; page-break-after:always;display: block; ">
            <div class="row" id="ENS">
              <div class="col-lg-6 col-6">
              <h3 class="text-center text-info"><strong><u>General Conduct & work habit </u></strong></h3>';
              $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
            if($queryCategory->num_rows()>0){
              foreach ($queryCategory->result() as $bscatvalue) {
                $bscategory=$bscatvalue->bscategory;
                $output.= '<div class="table-responsive">
                <table width="100%" id="ENS" class="tabler table-bordered table-md" cellspacing="5" cellpadding="5">';
                $dateYear=date('Y');
                $no=1;
                $output .='<tr>
                <th rowspan="2">'.$bscategory.'</th><th colspan="3" class="text-center">Semester</th></tr><tr>';
                $quarterArrayQ=array('Semester1','Semester2');
                foreach ($quarterArrayQ as $qvalue) 
                {
                  if($qvalue=='Semester1'){
                    $output .='<th class="text-center">1<sup>st</sup> Semester</th>';
                  }else{
                    $output .='<th class="text-center">2<sup>nd</sup> Semester</th>';
                  }
                }
                $output .='<th class="text-center">Yearly Average</th>';
                  $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and bscategory='$bscategory' order by bsname ASC ");    
                  $output .='<tr>';
                  foreach ($query_basicskill->result() as $bsvalue) {
                    $bsname=$bsvalue->bsname;
                    $output .='<tr><td>'.$bsvalue->bsname.'</td>';
                    foreach ($quarterArrayQ as $qvalue) {
                      $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                      if($query_bsvalue->num_rows()>0) {
                        foreach ($query_bsvalue ->result() as $bsresult) {
                          $output .='<td class="text-center">'.$bsresult->value.'</td>';
                        }
                      }else {
                        $output .='<td class="text-center">-</td>';
                      }
                    } 
                    $output .='<td class="text-center">-</td>';
                    $output .='</tr>';
                  }
                  $output .='</table></div><br>';/*basic skill table closed*/
                }
              }
              $queryEvaKey=$this->db->query("select * from bstype where academicyear='$max_year' and btgrade='$grade' ");
              if($queryEvaKey->num_rows()>0){
                $output.= '<div class="row">';
                $output .='<div class="col-lg-12 col-12 text-center" id="BGS"><h4 style="font-family:century_gothicbold;">GRADING CODE</h4></div>';
                foreach ($queryEvaKey->result() as $keyVValue) {
                  $output .='<div class="col-lg-6 col-12"><h5 style="font-family:century_gothicbold;">'.$keyVValue->bstype.'=> '.$keyVValue->bsdesc.'</h5></div>';
                }
                $output .='</div>';/*Evaluation key table closed*/
              }else{
                $output .='No Evaluation Key found';
              }
                $output.='<div class="StudentViewTextInfo">
                  <p class="text-center"><h3>Vision of Seattle Academy</h3></p>
                    <small><i class="fas fa-check-circle"></i> Creating a conducive environment where every member of the school community is accountable so as to provide quality education that makes every student best achiever and successful. </small>
                </div>
              </div>
              <div class="col-lg-6 col-6">
                <div class="row">
                  <div class="col-lg-12 col-12">';
                  $output.='<p class="text-center"><img class="text-center" src="'.base_url().'/logo/'.$logo.'" style="height:150px;width:150px;border:1px solid #fff; -webkit-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;border-radius:10px" ></p>
                  </div>
                  <div class="col-lg-12 col-12">
                    <p class="text-center"><i class="fas fa-phone-square"></i>: +251-11-888-35-04/+9-03-21-44444<br><i class="fas fa-envelope"></i> '.$email.'<br><i class="fas fa-envelope"></i> 28643   Code 1000<br>Website address- www.seattleacademyethiopia.com<br>Addis Ababa, Ethiopia</p>
                      <h4 class="text-center"><u><b>የተማሪ ውጤት መግለጫ<br>Student’s Report Card</b></u></h4>
                    <div class="row">
                      <div class="col-lg-9 col-12">
                        <div class="row">
                          <div class="col-lg-12 col-12">
                            የተማሪዉ ስም
                            <p>Student Name:-<b> <u>'.ucfirst(strtolower($fname)).' '.ucfirst(strtolower($mname)).' '.ucfirst(strtolower($lname)).'</u></b></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            ዕድሜ <p>Age <u>'.$age.' </u></p> 
                          </div> 
                          <div class="col-lg-6 col-6">
                            ፆታ <p>Sex <u>'.$gender.' </u></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            <p>Grade <u>'.$grade.' </u></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            <p>Section<u> '.$section.'</u> </p>
                          </div> 
                          <div class="col-lg-6 col-6">
                            ክ/ከተማ<p>Subcity <u>'.$addresss.' </u></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            ወረዳ<p>Woreda<u> '.$woreda.'</u> </p>
                          </div> 
                          <div class="col-lg-12 col-12">
                            የትምህርት ዘመን <p>Academic Year <u>'.$max_year.'E.C ('.$gYearName.'G.C) </u></p>
                          </div>
                          <div class="col-lg-12 col-12">
                            ወደ ______ ክፍል ተዛውሯል/ለች፡፡ <p>Promoted to Grade</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-12">';
                        if($profile == ''){
                          $output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
                        }else{
                          $output.='<img alt="Photo" src="'.base_url().'/profile/'.$profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
                        }
                      $output.='</div>
                    </div>
                  </div>
                </div>
              </div>

              
              </div>
            </div>';
          }
        }
      } 
    }
    return $output;
  }
  function rosterCrosspoint($max_year,$gradesec,$branch,$page)
  {
    $query_student=$this->db->query(" Select * from users where gradesec='$gradesec' and branch='$branch' and status='Active' and academicyear='$max_year' and isapproved='1' and usertype='Student' group by id order by fname,mname,lname ASC ");
    $query_name = $this->db->query("select * from school");
    $row_name = $query_name->row();//school info
    $school_name=$row_name->name;
    $address=$row_name->address;
    $phone=$row_name->phone;
    $website=$row_name->website;
    $email=$row_name->email;
    $logo=$row_name->logo;

    $queryac = $this->db->query("select max(year_name) as ay from academicyear");
    $rowac = $queryac->row();//academic year info
    $yearname=$rowac->ay;

    $row_gradesec = $query_student->row();//grade and sec info
    $grade_name=$row_gradesec->grade;
    $section_name=$row_gradesec->section;
    $gradeSec=$row_gradesec->gradesec;
    $totalStudent=$query_student->num_rows();

    $output=' <div class="text-center" id="ENS" height="100%" style="width:100%;height:100%;page-break-inside:avoid;display:flex; flex-direction:column; justify-content:center;min-height:100vh;">
    <p class="text-center"><img class="text-center" src="'.base_url().'/logo/'.$logo.'" style="height:150px;width:150px;border:1px solid #fff; -webkit-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;border-radius:10px" ></p>
      <h1><b>ROSTER SUMMARY</b></h1>
      <h3><b>School: '.$school_name.' <br></b></h3>
      <h5><b> Campus : '.$branch.'</b></h5>
      <h5><b> Academic Year : '.$max_year.'E.C</b></h5>
      <h5><b>Grade & Section: '.$gradeSec.'</b> </h5>
      <h5><b>Season: Yearly Average </b></h5>
      <h6><b>Total No. Of Student : '.$totalStudent.'</b></h6>
      </b>
    </div>';
    
    $output .='<div class="table-responsive" width="100%" height="100%">
        <table id="ENS" class="tabler table-borderedr" width="100%" cellspacing="5" cellpadding="5">
        
        <tr><td class="text-center rotateJossRoster"><div> No.</div></td>
        <td class="text-center rotateJossRoster"><div>Students Name</div></td> 
        <td class="text-center rotateJossRoster"><div>Age</div></td> 
        <td class="text-center rotateJossRoster"><div>Sex</div></td> 
        <td class="text-center rotateJossRoster"><div>Term</div></td>';
        $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
        foreach ($query_result ->result() as $rvalue)
        {
          $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
        }
        $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
        $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade_name' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");       
        foreach ($query_basicskill->result() as $bsvalue) {
          $bsname=$bsvalue->bsname;
          $output .='<td class="rotateJossRoster text-center"><div>'.$bsvalue->bsname.'</div></td>';
        }
        $output .='</tr>';
    $stuNo=1;
    foreach ($query_student->result() as $row) 
    {
      $stuid=$row->id;
      $username1=$row->username;
      $grade_sec=$row->gradesec;
      $grade=$row->grade;
      $query_quarter=array('Semester1','Semester2');
      $output .='<tr><td rowspan="4">'.$stuNo.'.</td>
      <td rowspan="4">'.$row->fname.' '.$row->mname.' '.$row->lname.'</td> 
      <td rowspan="4">'.$row->age.'</td>
      <td rowspan="4">'.$row->gender.'</td>';
      foreach ($query_quarter as $qvalue) 
      {
        $quarter=$qvalue;
        $output .='<tr> <td>'.$qvalue.'</td>';
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->subject;
          $query_quarter_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and subject='$subject_mark' group by subject ");
          if($query_quarter_result->num_rows()>0){
            foreach ($query_quarter_result->result() as $quarter_result) 
            {
              $letter=$quarter_result->letter;
              $result=$quarter_result->total;
              if($letter!='A')
              {
                $output .='<td class="text-center">'.$result.'</td>';
              }
              else{
                $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                if($queryRange->num_rows()>0){
                  foreach ($queryRange->result() as $letterValue) {
                    $letterVal=$letterValue->letterVal;
                    $output.= "<td class='text-center'>".$letterVal."</td>";
                  }
                }else{
                  $output.= "<td class='text-center'> -</td>";
                }
              }
            }
          }else{
            $output.= "<td class='text-center'> -</td>";
          }
        }
        
        $query_quarter_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and quarter ='$quarter' and onreportcard='1' and mergedname='' group by quarter order by subject ASC ");
        $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
        $total_subject=$count_subject->num_rows();
        if($query_quarter_total->num_rows()>0){
          foreach ($query_quarter_total->result() as $qtvalue)
          {
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total,2,'.','').'</td>';//Each quarter Total
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</td>';//Each quarter Average
          }
        
        
          $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and mergedname='' and quarter='$quarter' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and mergedname='' and grade='$gradesec' group by grade ");
          foreach ($query_rank->result() as $rvalue) {
            $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
          }
        }else{
          $output .= '<td class="text-center">-</td>';//Each quarter total
          $output .= '<td class="text-center">-</td>';//Each quarter Average
          $output .= '<td class="text-center">-</td>';//Each quarter Rank
        }
       
        //Each quarter Absence starts
        
        $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$quarter' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center">'.$absent->att.'</td>';
                }
                else{
                  $output .= '<td class="text-center">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            }else{
              $output.='<td class="text-center">-</td>';
            }
        $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");       
        foreach ($query_basicskill->result() as $bsvalue) {
          $bsname=$bsvalue->bsname;
            $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$quarter' ");
            if($query_bsvalue->num_rows()>0) {
              foreach ($query_bsvalue ->result() as $bsresult) {
                $output .='<td class="text-center">'.$bsresult->value.'</td>';
              }
            }else {
              $output .='<td class="text-center">-</td>';
            }
           
        }
        $output .='</tr>';
      }
      

      /*Yearly Average calculation starts*/
      $output .='<tr id="BGS"><td>Yearly Average </td>';
      
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' ");
        if($query_quarter_result->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/2;
            if($letter!='A')
            {
              $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
            }
            else{
              $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
              if($queryRange->num_rows()>0){
                foreach ($queryRange->result() as $letterValue) {
                  $letterVal=$letterValue->letterVal;
                  $output.= "<td class='text-center'>".$letterVal."</td>";
                }
              }else{
                $output.= "<td class='text-center'> -</td>";
              }
            }
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
                  
            
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
      if($quartrSem2Total->num_rows()>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/2);
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
      if($quartrSem2Total->num_rows()>0 && $total_subject>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/2)/$total_subject;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' group by grade ");
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
        }
      }else{
        $output.='<td class="text-center">-</td>';
        $output.='<td class="text-center">-</td>';
      }
      /*yearly absence calculation starts*/
      $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and absentype='Absent' ");
      if($queryTotalAbsent->num_rows()>0){
        foreach ($queryTotalAbsent->result() as $absent){
          if($absent->att>0)
          {
            $output .= '<td class="text-center">'.$absent->att.'</td>';
          }
          else{
            $output .= '<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");
      if($query_basicskill->num_rows()>0){
        $output .='<td class="text-center">-</td>';

      }
      $totSubject=$this->db->query("select subject from reportcard".$gradesec.$max_year." where  onreportcard='1' and grade='$gradesec' group by subject ");
      $numSubject=$totSubject->num_rows();
      
      $totStudent=$this->db->query("select id from users where  isapproved='1' and status='Active' and gradesec='$gradesec' and academicyear='$max_year' and branch='$branch' group by id ");
      $numStudent=$totStudent->num_rows();
      if($stuNo%$page === 0 && $stuNo>=$page){
        $output .='<tr style="page-break-after: always; page-break-inside: avoid;
        page-break-before: avoid;">';
        $output.='<td colspan="4">Home Room Teachers Signature .______________________.<br>
        Date._____________________.</td>';
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="5">Record Office Signature._____________________. <br>
        Date.________________________. </td></tr></table> </div>';
        if($stuNo<$numStudent){
            $output .='<div class="table-responsive" width="100%" height="100%">
            <table id="ENS" class="tabler table-borderedr" width="100%" cellspacing="5" cellpadding="5">
            
            <tr><td class="text-center rotateJossRoster"><div> No.</div></td>
            <td class="text-center rotateJossRoster"><div>Students Name</div></td> 
            <td class="text-center rotateJossRoster"><div>Age</div></td> 
            <td class="text-center rotateJossRoster"><div>Sex</div></td> 
            <td class="text-center rotateJossRoster"><div>Term</div></td>';
            $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
            foreach ($query_result ->result() as $rvalue)
            {
              $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
            }
                        $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
            $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade_name' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");       
            foreach ($query_basicskill->result() as $bsvalue) {
              $bsname=$bsvalue->bsname;
              $output .='<td class="rotateJossRoster text-center"><div>'.$bsvalue->bsname.'</div></td>';
            }
            $output .='</tr>';
        }
      }else{
        $output .='</tr>';
      }
      $stuNo++;
    }
    if($numStudent%$page!==0){
        $output.='<tr><td colspan="4">Home Room Teachers Signature .______________________.<br>
        Date._____________________.</td>';
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="5">Record Office Signature._____________________. <br>
        Date.________________________. </td></tr>';
    }
    $output .=' </table> </div>';
    return $output;
  }
  function quarterosterCrosspoint($max_year,$gradesec,$branch,$quarter){
    $query_student=$this->db->query(" Select * from users where gradesec='$gradesec' and branch='$branch' and status='Active' and academicyear='$max_year' and isapproved='1' and usertype='Student' group by id order by fname,mname,lname ASC ");
    $query_name = $this->db->query("select * from school");
    $row_name = $query_name->row();//school info
    $school_name=$row_name->name;
    $address=$row_name->address;
    $phone=$row_name->phone;
    $website=$row_name->website;
    $email=$row_name->email;

    $row_gradesec = $query_student->row();//grade and sec info
    $grade_name=$row_gradesec->grade;
    $section_name=$row_gradesec->section;
    $gradeSec=$row_gradesec->gradesec;
    $totalStudent=$query_student->num_rows();

    $output=' <div class="text-center">
      <h1>ROSTER SUMMARY</h1>
      <span class="time" style="font-family:Poor Richard"> 
      <h3><b>School: '.$school_name.'</b> <br></h3>
      <h5><b> Campus : '.$branch.'</b></h5>
      <h5><b> Academic Year : '.$max_year.'E.C</b></h5>
      <h5><b> Grade & Section: '.$gradeSec.' </b></h5>
      <h5><b> Season: Yearly Average </b></h5>
      <h6><b> Total NO. Of Student : '.$totalStudent.'</b></h6>
      </span> </b>
    </div>';
    $output .='<div class="table-responsive" id="employee_table" width="100%" height="100%">
        <table id="ENS" class="table table-borderedr" width="100%" cellspacing="5" cellpadding="5">
    <tr><th> No.</th>
    <th>Students Name</th> 
    <th>Gender</th> 
    <th>Season</th>';
    $query_result=$this->db->query(" Select * from subject where Grade='$grade_name' and Academic_Year='$max_year' and onreportcard='1' group by Subj_name order by suborder ");
    $totSubject=$query_result->num_rows();
    foreach ($query_result ->result() as $rvalue)
    {
      $output .=' <td class="text-center rotateJossRoster"><div>'.$rvalue->Subj_name.'</div></td>';
    }
    $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade_name' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");       
    foreach ($query_basicskill->result() as $bsvalue) {
      $bsname=$bsvalue->bsname;
      $output .='<td class="text-center rotateJossRoster"><div>'.$bsvalue->bsname.'</div></td>';
    }
    $output .='<td class="text-center rotateJossRoster"><div>Total</div></td> ';
    $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
    $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
    $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
    $output .='</tr>';
    $stuNo=1;
    foreach ($query_student->result() as $row) 
    {
      $stuid=$row->id;
      $username1=$row->username;
      $grade_sec=$row->gradesec;
      $grade=$row->grade;
      $output .='<tr><td>'.$stuNo.'.</td>
      <td><b>'.$row->fname.' '.$row->mname.'</b></td> 
      <td>'.$row->gender.'</td>';
        $output .=' <td><B>'.$quarter.'</B></td>';
        $totA=0;
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->Subj_name;
          $mergedPercent=$subname->Merged_percent;
          $query_quarter_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and subject='$subject_mark' group by subject ");
          if($query_quarter_result->num_rows()>0){
            foreach ($query_quarter_result->result() as $quarter_result) 
            {
              $letter=$quarter_result->letter;
              $result=($quarter_result->total * 100)/$mergedPercent;
              $output .='<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
            }
          }else{
            $output.= "<td class='text-center'> -</td>";
            $result=0;
          }
          $totA=$result+$totA;
        }
        $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");       
        foreach ($query_basicskill->result() as $bsvalue) {
          $bsname=$bsvalue->bsname;
            $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$quarter' ");
            if($query_bsvalue->num_rows()>0) {
              foreach ($query_bsvalue ->result() as $bsresult) {
                $output .='<td class="text-center">'.$bsresult->value.'</td>';
              }
            }else {
              $output .='<td class="text-center">-</td>';
            }
           
        }
        $output .= '<td class="text-center"><B>'.number_format((float)$totA,2,'.','').'</B></td>';//Each quarter Total
        $output .= '<td class="text-center"><B>'.number_format((float)$totA/$totSubject,2,'.','').'</B></td>';//Each quarter Average
        
        
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and mergedname='' and quarter='$quarter' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and mergedname='' and grade='$gradesec' group by grade ");
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center"><B>'.$rvalue->stuRank.'</B></td>';//Each quarter rank
        }
       

        //Each quarter tardiness starts
          $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$quarter' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center">'.$absent->att.'</td>';
                }
                else{
                  $output .= '<td class="text-center">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            }else{
              $output.='<td class="text-center">-</td>';
            }

        //Each quarter Absence starts
        $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='$quarter' and attt.academicyear='$max_year' and se.Academic_year='$max_year' and absentype='Absent' ");
        if($query_total_absent->num_rows()>0){
          foreach ($query_total_absent->result() as $absent){
            if($absent->att>0)
            {
              $output .= '<td class="text-center"><B>'.$absent->att.'</B></td>';
            }
            else{
              $output .= '<td class="text-center"><B>-</B></td>';
            }
          }
        }else{
          $output.='<td class="text-center">-</td>';
        }
        $output .='</tr>';
      
      $output.='</tr>';
      $stuNo++;
    }
    $output .=' </table> </div>';
    $output .='<div class="row">
    <div class="col-md-4"><span class="time" style="font-family:Poor Richard"><B>
    Home Room Teachers Signature .______________________.<br>
    Date._____________________.</B> </span></div>
    <div class="col-md-4"> <span class="time" style="font-family:Poor Richard"><B>
    Directors Signature._____________________. <br>
    Date.________________________.</B></span> </div>
    <div class="col-md-4"> <span class="time" style="font-family:Poor Richard"><B>
    Record Office Signature._____________________. <br>
    Date.________________________.</B></span> </div>
    </div>';
    return $output;
  }
  function KgrosterCrosspoint($max_year,$gradesec,$branch,$page)
  {
    $query_student=$this->db->query(" Select * from users where gradesec='$gradesec' and branch='$branch' and status='Active' and academicyear='$max_year' and isapproved='1' and usertype='Student' group by id order by fname,mname,lname ASC ");
    $query_name = $this->db->query("select * from school");
    $row_name = $query_name->row();//school info
    $school_name=$row_name->name;
    $address=$row_name->address;
    $phone=$row_name->phone;
    $website=$row_name->website;
    $email=$row_name->email;

    $queryac = $this->db->query("select max(year_name) as ay from academicyear");
    $rowac = $queryac->row();//academic year info
    $yearname=$rowac->ay;

    $row_gradesec = $query_student->row();//grade and sec info
    $grade_name=$row_gradesec->grade;
    $section_name=$row_gradesec->section;
    $gradeSec=$row_gradesec->gradesec;
    $totalStudent=$query_student->num_rows();

    $output=' <div class="text-center" id="ENS" height="100%" style="width:100%;height:100%;page-break-inside:avoid;display:flex; flex-direction:column; justify-content:center;min-height:100vh;">
      <h1>ROSTER SUMMARY</h1>
      <h3>School: '.$school_name.' <br></h3>
      <h5> Campus : '.$branch.'(Kaliti) </h5>
      <h5> Academic Year : '.$max_year.'E.C</h5>
      <h5>Grade & Section: '.$gradeSec.' </h5>
      <h5>Season: Yearly Average </h5>
      <h6>Total No. Of Student : '.$totalStudent.'</h6>
      </b>
    </div>';
    
    $output .='<div class="table-responsive" width="100%" height="100%">
        <table id="ENS" class="tabler table-borderedr" width="100%" cellspacing="5" cellpadding="5">
        
        <tr><td class="text-center rotateJossRoster"><div> No.</div></td>
        <td class="text-center rotateJossRoster"><div>Students Name</div></td> 
        <td class="text-center rotateJossRoster"><div>Gender</div></td> 
        <td class="text-center rotateJossRoster"><div>Season</div></td>';
        $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
        foreach ($query_result ->result() as $rvalue)
        {
          $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
        }
        $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Conduct</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
        $output .='</tr>';
    $stuNo=1;
    foreach ($query_student->result() as $row) 
    {
      $stuid=$row->id;
      $username1=$row->username;
      $grade_sec=$row->gradesec;
      $grade=$row->grade;
      $query_quarter=array('Quarter1','Quarter2');
      $output .='<tr><td rowspan="8">'.$stuNo.'.</td>
      <td rowspan="8">'.$row->fname.' '.$row->mname.' '.$row->lname.'</td> 
      <td rowspan="8">'.$row->gender.'</td>';
      foreach ($query_quarter as $qvalue) 
      {
        $quarter=$qvalue;
        $output .='<tr> <td>'.$qvalue.'</td>';
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->subject;
          $query_quarter_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and subject='$subject_mark' group by subject ");
          if($query_quarter_result->num_rows()>0){
            foreach ($query_quarter_result->result() as $quarter_result) 
            {
              $letter=$quarter_result->letter;
              $result=$quarter_result->total;
              if($letter!='A')
              {
                $output .='<td class="text-center">'.$result.'</td>';
              }
              else{
                $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                if($queryRange->num_rows()>0){
                  foreach ($queryRange->result() as $letterValue) {
                    $letterVal=$letterValue->letterVal;
                    $output.= "<td class='text-center'>".$letterVal."</td>";
                  }
                }else{
                  $output.= "<td class='text-center'> -</td>";
                }
              }
            }
          }else{
            $output.= "<td class='text-center'> -</td>";
          }
        }
        $query_quarter_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and quarter ='$quarter' and onreportcard='1' and mergedname='' group by quarter order by subject ASC ");
        $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
        $total_subject=$count_subject->num_rows();
        if($query_quarter_total->num_rows()>0){
          foreach ($query_quarter_total->result() as $qtvalue)
          {
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total,2,'.','').'</td>';//Each quarter Total
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</td>';//Each quarter Average
          }
        }else{
          $output .= '<td class="text-center">-</td>';//Each quarter total
          $output .= '<td class="text-center">-</td>';//Each quarter Average
        }
        
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and mergedname='' and quarter='$quarter' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and mergedname='' and grade='$gradesec' group by grade ");
        if($query_rank->num_rows()>0){
            foreach ($query_rank->result() as $rvalue) {
              $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
            }
        }else{
            $output .= '<td class="text-center">-</td>';
        }
       

        //Each quarter conduct starts
        $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' and bsname='Conduct' group by stuid ");
          if($eachQuarterBasicskill->num_rows()>0){
            $valueBS=$eachQuarterBasicskill->row();
            $getValue=$valueBS->value;
            $output.='<td class="text-center">'.$getValue.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        //Each quarter Absence starts
        
        $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$quarter' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center">'.$absent->att.'</td>';
                }
                else{
                  $output .= '<td class="text-center">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            }else{
              $output.='<td class="text-center">-</td>';
            }
        $output .='</tr>';
      }
      /*1st semester calculation starts*/
      $output .='<tr id="BGS"><td>Semester1 Average </td>';
      $queryCheckSem1=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter2'  ");
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter1' or stuid='$stuid' and subject='$subject_mark' and quarter='Quarter2'  ");
        if($queryCheckSem1->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/2;
            if($letter!='A')
            {
              $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
            }
            else{
              $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
              if($queryRange->num_rows()>0){
                foreach ($queryRange->result() as $letterValue) {
                  $letterVal=$letterValue->letterVal;
                  $output.= "<td class='text-center'>".$letterVal."</td>";
                }
              }else{
                $output.= "<td class='text-center'> -</td>";
              }
            }
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' and mergedname='' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' and mergedname='' ");
      if($queryCheckSem1->num_rows()>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=($totalValueSem2->total)/2;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' and mergedname='' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' and mergedname='' ");
      if($queryCheckSem1->num_rows()>0 && $total_subject>0 ){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/2)/$total_subject;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      /*Rank calculation starts*/
      $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='Quarter1' or grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='Quarter2' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter1' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' or stuid='$stuid' and quarter= 'Quarter2' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' group by grade ");
      if($queryCheckSem1->num_rows()>0){
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $output.='<td class="text-center">-</td>';/*conduct calculation starts*/
      /*1st Semester absence calculation starts*/
      $tot1stSem=0;
      if($queryCheckSem1->num_rows()>0){
      $quarterArray1=array('Quarter1','Quarter2');
          foreach ($quarterArray1 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center" colspan="2">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" colspan="2">-</td>';
          }
        }else{
          $output .= '<td class="text-center" colspan="2">-</td>';
        }
      $output.='</tr>';
      /*3rd and 4th calculation starts*/
      $queryQuarter=array('Quarter3','Quarter4');
      foreach ($queryQuarter as $qvalue) 
      {
        $quarter=$qvalue;
        $output .='<tr> <td>'.$qvalue.'</td>';
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->subject;
          $query_quarter_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and subject='$subject_mark' group by subject ");
          if($query_quarter_result->num_rows()>0){
            foreach ($query_quarter_result->result() as $quarter_result) 
            {
              $letter=$quarter_result->letter;
              $result=$quarter_result->total;
              if($letter!='A')
              {
                $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
              }
              else{
                $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                if($queryRange->num_rows()>0){
                  foreach ($queryRange->result() as $letterValue) {
                    $letterVal=$letterValue->letterVal;
                    $output.= "<td class='text-center'>".$letterVal."</td>";
                  }
                }else{
                  $output.= "<td class='text-center'> -</td>";
                }
              }
            }
          }else{
            $output.= "<td class='text-center'> -</td>";
          }
        }
        $query_quarter_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and quarter ='$quarter' and onreportcard='1' group by quarter order by subject ASC ");
        $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and academicyear='$max_year' group by subject ");
        $total_subject=$count_subject->num_rows();
        if($query_quarter_total->num_rows()>0){
          foreach ($query_quarter_total->result() as $qtvalue)
          {
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total,2,'.','').'</td>';//Each quarter Total
            $output .= '<td class="text-center">'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</td>';//Each quarter Average
          }
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='$quarter' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' group by grade ");
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
        }
        }else{
          $output .= '<td class="text-center">-</td>';//Each quarter total
          $output .= '<td class="text-center">-</td>';//Each quarter Average
          $output .= '<td class="text-center">-</td>';//Each quarter rank
        }
        
        //Each quarter conduct starts
        $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' and bsname='Conduct' group by stuid ");
          if($eachQuarterBasicskill->num_rows()>0){
            $valueBS=$eachQuarterBasicskill->row();
            $getValue=$valueBS->value;
            $output.='<td class="text-center">'.$getValue.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        //Each quarter Absence
        $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$quarter' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                  $output .= '<td class="text-center">'.$absent->att.'</td>';
                }
                else{
                  $output .= '<td class="text-center">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            
          /*$query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where attt.stuid='$username1'
          and attt.absentdate between se.startdate and se.endate and se.term='$quarter' and attt.academicyear='$max_year' and se.Academic_year='$max_year' ");
        if($query_total_absent->num_rows()>0){
          foreach ($query_total_absent->result() as $absent){
            if($absent->att>0)
            {
              $output .= '<td class="text-center"><B>'.$absent->att.'</B></td>';
            }
            else{
              $output .= '<td class="text-center"><B>-</B></td>';
            }
          }
        }else{
          $output.='<td class="text-center">-</td>';
        }*/
        $output .='</tr>';
      }

      /*2nd semester calculation starts*/
      $output .='<tr id="BGS"><td>Semester2 Average </td>';
      $queryCheckSem2=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter4'  ");
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter3' or stuid='$stuid' and subject='$subject_mark' and quarter='Quarter4'  ");
        if($queryCheckSem2->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/2;
            if($letter!='A')
            {
              $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
            }
            else{
              $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
              if($queryRange->num_rows()>0){
                foreach ($queryRange->result() as $letterValue) {
                  $letterVal=$letterValue->letterVal;
                  $output.= "<td class='text-center'>".$letterVal."</td>";
                }
              }else{
                $output.= "<td class='text-center'> -</td>";
              }
            }
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
      
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
      if($queryCheckSem2->num_rows()>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/2);
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
      if($queryCheckSem2->num_rows()>0 && $total_subject>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/2)/$total_subject;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='Quarter3' or grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='Quarter4' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter3' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' or stuid='$stuid' and quarter= 'Quarter4' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' group by grade ");
        if($query_rank->num_rows()>0){
          foreach ($query_rank->result() as $rvalue) {
            $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
          }
        }else{
          $output.='<td class="text-center">-</td>';
        }   
      }else{
        $output.='<td class="text-center">-</td>';
        $output.='<td class="text-center">-</td>';
      }
      $output.='<td class="text-center">-</td>';/*conduct calculation starts*/
     
      /*2nd Semester absence calculation starts*/
      
      $tot1stSem=0;
      if($queryCheckSem2->num_rows()>0){
      $quarterArray1=array('Quarter3','Quarter4');
          foreach ($quarterArray1 as $qvalue) {
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
            if($queryTerm->num_rows()>0){
            $qRow=$queryTerm->row();
            $date1 =$qRow->startdate;
            $date2 =$qRow->endate;
            $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $startDate1= $changeDate1->format('Y-m-d');
            $endDate1= $changeDate2->format('Y-m-d');
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' 
            and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center" colspan="2">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" colspan="2">-</td>';
          }
        }else{
          $output .= '<td class="text-center" colspan="2">-</td>';
        }
     /* $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='Quarter3' and attt.academicyear='$max_year' and se.Academic_year='$max_year' or attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='Quarter4' and attt.academicyear='$max_year' and se.Academic_year='$max_year' ");
      if($queryTotalAbsent->num_rows()>0){
        foreach ($queryTotalAbsent->result() as $absent){
          if($absent->att>0)
          {
            $output .= '<td class="text-center"><B>'.$absent->att.'</B></td>';
          }
          else{
            $output .= '<td class="text-center"><B>-</B></td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }*/
      $output.='</tr>';

      /*Yearly Average calculation starts*/
      $output .='<tr id="BGS"><td>Yearly Average </td>';

      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $query_quarter_result=$this->db->query("select *, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' ");
        if($queryCheckSem2->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
            $letter=$quarter_result->letter;
            $result=($quarter_result->total)/4;
            if($letter!='A')
            {
              $output .= '<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
            }
            else{
              $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
              if($queryRange->num_rows()>0){
                foreach ($queryRange->result() as $letterValue) {
                  $letterVal=$letterValue->letterVal;
                  $output.= "<td class='text-center'>".$letterVal."</td>";
                }
              }else{
                $output.= "<td class='text-center'> -</td>";
              }
            }
          }
        }else{
          $output.= "<td class='text-center'> -</td>";
        }
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
      if($queryCheckSem2->num_rows()>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/4);
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
      if($queryCheckSem2->num_rows()>0 && $total_subject>0){
        foreach ($quartrSem2Total->result() as $totalValueSem2) {
          $printValueSem2=(($totalValueSem2->total)/4)/$total_subject;
          if($printValueSem2 >0){
            $output .= '<td class="text-center">'.number_format((float)$printValueSem2,2,'.','').'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
          }
        }
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' group by grade ");
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center">'.$rvalue->stuRank.'</td>';//Each quarter rank
        }
      }else{
        $output.='<td class="text-center">-</td>';
        $output.='<td class="text-center">-</td>';
      }
      $output.='<td class="text-center">-</td>';/*conduct calculation starts*/
      /*yearly absence calculation starts*/
      $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' ");
      if($queryCheckSem2->num_rows()>0){
        foreach ($queryTotalAbsent->result() as $absent){
          if($absent->att>0)
          {
            $output .= '<td class="text-center">'.$absent->att.'</td>';
          }
          else{
            $output .= '<td class="text-center">-</td>';
          }
        }
      }else{
        $output.='<td class="text-center">-</td>';
      }
      $totSubject=$this->db->query("select subject from reportcard".$gradesec.$max_year." where  onreportcard='1' and grade='$gradesec' group by subject ");
      $numSubject=$totSubject->num_rows();
      
      $totStudent=$this->db->query("select id from users where  isapproved='1' and status='Active' and gradesec='$gradesec' and academicyear='$max_year' and branch='$branch' group by id ");
      $numStudent=$totStudent->num_rows();
      if($stuNo%$page === 0 && $stuNo>=$page){
        $output .='<tr style="page-break-after: always; page-break-inside: avoid;
        page-break-before: avoid;">';
        $output.='<td colspan="4">Home Room Teachers Signature .______________________.<br>
        Date._____________________.</td>';
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="5">Record Office Signature._____________________. <br>
        Date.________________________. </td></tr></table> </div>';
        if($stuNo<$numStudent){
            $output .='<div class="table-responsive" width="100%" height="100%">
            <table id="ENS" class="tabler table-borderedr" width="100%" cellspacing="5" cellpadding="5">
            
            <tr><td class="text-center rotateJossRoster"><div> No.</div></td>
            <td class="text-center rotateJossRoster"><div>Students Name</div></td> 
            <td class="text-center rotateJossRoster"><div>Gender</div></td> 
            <td class="text-center rotateJossRoster"><div>Season</div></td>';
            $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
            foreach ($query_result ->result() as $rvalue)
            {
              $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
            }
            $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Conduct</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
            $output .='</tr>';
        }
      }else{
        $output .='</tr>';
      }
      $stuNo++;
    }
    if($numStudent%$page!==0){
        $output.='<tr><td colspan="4">Home Room Teachers Signature .______________________.<br>
        Date._____________________.</td>';
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="5">Record Office Signature._____________________. <br>
        Date.________________________. </td></tr>';
    }
    $output .=' </table> </div>';
    return $output;
  }

}