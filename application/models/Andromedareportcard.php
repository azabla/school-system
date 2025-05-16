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
  function fetch_grade($max_year){
    $this->db->where('usertype','Student');
    $this->db->where('grade !=','0');
    $this->db->where('grade !=','');
    $this->db->where('isapproved =','1');
    $this->db->where('status =','Active');
    $this->db->where('academicyear',$max_year);
    $this->db->order_by('grade','ASC');
    $this->db->group_by('grade');
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
  function  fetch_grade_from_branch_update($branch,$academicyear){
    $this->db->where('users.branch',$branch);
    $this->db->where('users.academicyear',$academicyear);
    $this->db->where('users.usertype','Student');
    $this->db->where('users.grade!=','');
    $this->db->order_by('users.grade','ASC');
    $this->db->group_by('users.grade');
    $query=$this->db->get('users');
    $output='';
    $output .='<div class="row">';
    foreach ($query->result() as $row) { 
      $output .=' <div class="col-lg-2 col-3">
          <div class="pretty p-icon p-bigger">
          <input type="radio" name="updateSubjectGrade" value="'.$row->grade.'" class="updateSubjectGrade" id="updateSubjectGrade">
          <div class="state p-info">
            <i class="icon fa fa-check"></i>
            <label></label>'.$row->grade.'
          </div>
         </div>
      </div> ';
    }
    $output.='</div>';
    return $output;
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
    $this->db->order_by('term','DESC');
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
            'type'=>'VARCHAR',
            'constraint'=>255
          )
        );
        $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$max_year."' ");
        if ($queryCheck->num_rows()<1 ){
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('rid',TRUE);
          $query=$this->dbforge->create_table('reportcard'.$gradesec.$max_year,TRUE); 
          $sql = "CREATE INDEX stu_id ON reportcard".$gradesec.$max_year."(stuid,grade,subject,quarter,onreportcard,rpbranch)";
              $this->db->query($sql); 
        }else{
          $sql = "CREATE INDEX stu_id ON reportcard".$gradesec.$max_year."(stuid,grade,subject,quarter,onreportcard,rpbranch)";
          $this->db->query($sql); 
          $query='';
        }
      }
      if($query){
        $fields_gs=array(
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
        $queryCheck2 = $this->db->query("SHOW TABLES LIKE 'reportcard_mid_report".$max_year."' ");
        if ($queryCheck2->num_rows()<1 ){
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('rid',TRUE);
          $query=$this->dbforge->create_table('reportcard_mid_report'.$max_year,TRUE); 
          $sql = "CREATE INDEX stu_id ON reportcard_mid_report".$max_year."(stuid,grade,subject,quarter,onreportcard,rpbranch)";
          $this->db->query($sql); 
        }else{
          $sql = "CREATE INDEX stu_id ON reportcard_mid_report".$max_year."(stuid,grade,subject,quarter,onreportcard,rpbranch)";
          $this->db->query($sql); 
          $query='';
        }
        $output .='<i class="fas fa-check-circle"> </i>';
      }else{
        $output .='Ooops Please try again.';
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
  function fetchCustomSemesterCardStudent($reportaca,$gradesec,$branch){
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
      <td><button class="btn btn-info printThisStudentSemesterReport" name="'.$id.'" value="" id="'.$reportaca.'"><i class="fas fa-print"></i></button></td> </tr>';
      $no++;
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
      $evalname_query=$this->db->query("select us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$max_quarter' and us.gradesec='$gradesec' and us.branch='$branch' and us.academicyear='$max_year' group by ev.evname order by ev.eid ASC");
      $queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gradesec' and u.branch='$branch' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname ASC ");
      foreach ($queryStudent->result_array() as $row) { 
        $id=$row['id'];
        $grade=$row['grade'];
        $average=0;
        $querySubject=$this->db->query("select * from subject where Academic_Year='$max_year' and Grade='$grade' group by Subj_name order by suborder ASC ");
          if($querySubject->num_rows()>0){
            foreach($querySubject->result() as $subject_row){
              $average1=0;
              $gs_subject=$subject_row->Subj_name;
              $letter=$subject_row->letter;
              $queryCheck=$this->db->query("select * from subject_custom_percentage where academicyear='$max_year' and grade='$grade' and quarter='$max_quarter' and subject='$gs_subject' ");
              if($queryCheck->num_rows()>0){
                $rowPercent=$queryCheck->row();
                $mergedPercent=$rowPercent->percentage;
              }else{
                $mergedPercent=$subject_row->Merged_percent;
              }
              /*$mergedPercent=$subject_row->Merged_percent;*/
              $onReportCard=$subject_row->onreportcard;
              $subjorder=$subject_row->suborder;
              if($subject_row->Merged_name==''){
                $mergedSubject='';
              }else{
                $mergedSubject=$subject_row->Merged_name;
              }
              foreach ($evalname_query->result_array() as $mark_name)
              {
                $evName=$mark_name['evname'];
                $grade=$mark_name['grade'];
                $mname_gs=$mark_name['eid'];
                $queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$gs_subject' and customasses='$evName' and customquarter='$max_quarter' ");
                if($queryCheckPercentage->num_rows()>0){
                  $rowPercent=$queryCheckPercentage->row();
                  $percent=$rowPercent->custompercent;
                }else{
                  $percent= $mark_name['percent'];
                }
                $query_value = $this->db->query("select markname,sum(value) as total from mark".$branch.$gradesec.$max_quarter.$max_year." where  subname='$gs_subject' and quarter='$max_quarter' and evaid='$mname_gs' and mbranch='$branch' group by markname order by mid ASC");
                if($query_value->num_rows()>0){
                  $totalMark=0;$outofTot=0;
                  foreach ($query_value->result_array() as $value) {
                    $markNameStu=$value['markname'];
                    $queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from mark".$branch.$gradesec.$max_quarter.$max_year." where stuid='$id' and subname='$gs_subject' and quarter='$max_quarter' and evaid='$mname_gs' and mbranch='$branch' and markname='$markNameStu' group by markname order by mid ASC");
                    if($queryStuValue->num_rows()>0){
                      foreach ($queryStuValue->result_array() as $kevalue) {
                        $outofTot=$outofTot+$kevalue['outof'];
                        $totalMark=$totalMark+$kevalue['value'];
                      }
                    }
                    $queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$branch.$gradesec.$max_quarter.$max_year." where subname='$gs_subject' and quarter='$max_quarter' and evaid='$mname_gs' and mgrade='$gradesec' and mbranch='$branch' group by markname order by mid ASC");
                      $sumOutOf=0;
                    foreach ($queryMvalue->result_array() as $mark_name) {
                      $sumOutOf=$mark_name['outof'] + $sumOutOf;  
                    }
                  }
                  if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='')
                  {
                    $conver= ($totalMark *$percent )/$sumOutOf;
                    $average =$conver + $average;
                    $average1 =($average * $mergedPercent)/100;
                  }
                }
              }
              $average1=number_format((float)$average1,2,'.','');
              $data[]=array(
                'stuid'=>$id,
                'subject'=>$gs_subject,
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


      /*$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gradesec' and u.branch='$branch' and isapproved='1' and status='Active' and usertype='Student' group by u.id order by u.fname,u.mname ASC ");
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
      }*/
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
  function update_Group_reportcardResult($max_year,$grade,$branch,$max_quarter){
     $queryUser=$this->db->query("select gradesec from users where academicyear='$max_year' and grade='$grade' and branch='$branch' and usertype='Student' group by gradesec ");
      if($queryUser->num_rows()>0){
        $output='';
        foreach($queryUser->result() as $gradeRow){
          $gradesec=$gradeRow->gradesec;
          $data=array();
          $data1=array();
        $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$max_year."' ");
        if ($queryCheck->num_rows()>0 ){
          $queyDelete=$this->db->query("delete from reportcard".$gradesec.$max_year." where rpbranch ='$branch' and grade='$gradesec' and quarter='$max_quarter' ");
          $evalname_query=$this->db->query("select us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$max_quarter' and us.gradesec='$gradesec' and us.branch='$branch' and us.academicyear='$max_year' group by ev.evname order by ev.eid ASC");
          $queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gradesec' and u.branch='$branch' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname ASC ");
          foreach ($queryStudent->result_array() as $row) { 
            $id=$row['id'];
            $grade=$row['grade'];
            $average=0;
            $querySubject=$this->db->query("select * from subject where Academic_Year='$max_year' and Grade='$grade' group by Subj_name order by suborder ASC ");
              if($querySubject->num_rows()>0){
                foreach($querySubject->result() as $subject_row){
                  $average1=0;
                  $gs_subject=$subject_row->Subj_name;
                  $letter=$subject_row->letter;
                  $queryCheck=$this->db->query("select * from subject_custom_percentage where academicyear='$max_year' and grade='$grade' and quarter='$max_quarter' and subject='$gs_subject' ");
                  if($queryCheck->num_rows()>0){
                    $rowPercent=$queryCheck->row();
                    $mergedPercent=$rowPercent->percentage;
                  }else{
                    $mergedPercent=$subject_row->Merged_percent;
                  }
                  /*$mergedPercent=$subject_row->Merged_percent;*/
                  $onReportCard=$subject_row->onreportcard;
                  $subjorder=$subject_row->suborder;
                  if($subject_row->Merged_name==''){
                    $mergedSubject='';
                  }else{
                    $mergedSubject=$subject_row->Merged_name;
                  }
                  foreach ($evalname_query->result_array() as $mark_name)
                  {
                    $evName=$mark_name['evname'];
                    $grade=$mark_name['grade'];
                    $mname_gs=$mark_name['eid'];
                    $queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$gs_subject' and customasses='$evName' and customquarter='$max_quarter' ");
                    if($queryCheckPercentage->num_rows()>0){
                      $rowPercent=$queryCheckPercentage->row();
                      $percent=$rowPercent->custompercent;
                    }else{
                      $percent= $mark_name['percent'];
                    }
                    $query_value = $this->db->query("select markname,sum(value) as total from mark".$branch.$gradesec.$max_quarter.$max_year." where  subname='$gs_subject' and quarter='$max_quarter' and evaid='$mname_gs' and mbranch='$branch' group by markname order by mid ASC");
                    if($query_value->num_rows()>0){
                      $totalMark=0;$outofTot=0;
                      foreach ($query_value->result_array() as $value) {
                        $markNameStu=$value['markname'];
                        $queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from mark".$branch.$gradesec.$max_quarter.$max_year." where stuid='$id' and subname='$gs_subject' and quarter='$max_quarter' and evaid='$mname_gs' and mbranch='$branch' and markname='$markNameStu' group by markname order by mid ASC");
                        if($queryStuValue->num_rows()>0){
                          foreach ($queryStuValue->result_array() as $kevalue) {
                            $outofTot=$outofTot+$kevalue['outof'];
                            $totalMark=$totalMark+$kevalue['value'];
                          }
                        }
                        $queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$branch.$gradesec.$max_quarter.$max_year." where subname='$gs_subject' and quarter='$max_quarter' and evaid='$mname_gs' and mgrade='$gradesec' and mbranch='$branch' group by markname order by mid ASC");
                          $sumOutOf=0;
                        foreach ($queryMvalue->result_array() as $mark_name) {
                          $sumOutOf=$mark_name['outof'] + $sumOutOf;  
                        }
                      }
                      if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='')
                      {
                        $conver= ($totalMark *$percent )/$sumOutOf;
                        $average =$conver + $average;
                        $average1 =($average * $mergedPercent)/100;
                      }
                    }
                  }
                  $average1=number_format((float)$average1,2,'.','');
                  $data[]=array(
                    'stuid'=>$id,
                    'subject'=>$gs_subject,
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
          if(!empty($data)){
            $query_insert1=$this->db->insert_batch('reportcard'.$gradesec.$max_year,$data);
            if($query_insert1){
              $output.='<i class="fas fa-check-circle"> </i>'.$gradesec.' ';
            }
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
        }
      }
    }
    return $output;
  }      
  function report_card_semester($max_year,$gradesec,$branch,$max_quarter){
    $output ='';$resultSem1=0;
    $resultSem2=0;
    $queryCHK=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
    if($queryCHK->num_rows()>0){
      $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec,username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
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
          $username1=$fetchStudent->username;
          $output.='<div style="width:100%;height:92%;page-break-inside:avoid;">';
          $output.= '<div class="row" id="ENS">
          <div class="col-lg-6 col-md-12">';
          $output.='<div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
          $output.='
          <tr><th class="text-center">Subject</th>
          <th class="text-center">First Semester</th>
          <th class="text-center">Second Semester</th>
          <th class="text-center">Yearly Average</th></tr>';
          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' 
          and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr><td style="white-space: nowrap"><B>'.$fetchSubject->subject.'</B></td>';
              /*1st Semester calculation starts*/
              $sem1Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter1') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter2') as total ");
              if($sem1Ave->num_rows()>0){
                foreach ($sem1Ave->result() as $fetchSem1) {
                  $resultSem1=($fetchSem1->total)/2;
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
              }else{
                $output.='<td class="text-center">-</td>';
              }
              
              $sem2Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter3') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter4') as total ");
              if($sem2Ave->num_rows()>0){
                foreach ($sem2Ave->result() as $fetchSem2) {
                  $resultSem2=($fetchSem2->total)/2;
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
              }else{
                $output.='<td class="text-center">-</td>';
              }
              $YAve=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' ");
              if($YAve->num_rows()>0){
                foreach ($YAve->result() as $fetchYA) {
                  $resultYA=($fetchYA->total)/4;
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
              }else{
                $output.='<td class="text-center">-</td>';
              }
              $output.='</tr>';
            }
          } 
          /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
          $output.='<tr><td><b>Total</b></td>';
          /*Semester1 Horizontal Total calculation starts*/
          $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
            if($quartrSem1Total->num_rows()>0){
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
          /*Semester2 Horizontal Total calculation starts*/
          $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1'
          and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
            if($quartrSem2Total->num_rows()>0){
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
            if($quartrYATotal->num_rows()>0){
                if($resultSem2 ==0 || $resultSem1 == 0){
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
          /*Horizontal Average calculation starts*/
          $output.='<tr><td><b>Average</b></td>';
          /*1st and snd quarter calculation starts*/
          $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
          $subALl=$countSubject->num_rows();
          
          /*1st Semester average starts*/
          $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
            if($quartrSem1Total->num_rows()>0){
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
          /*Semester2 Horizontal Average calculation starts*/
         $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
            if($quartrSem2Total->num_rows()>0){
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
            if($quartrYATotal->num_rows()>0){
              foreach ($quartrYATotal->result() as $totalValueYA) {
                $printValueYA=(($totalValueYA->total)/4)/$subALl;
                if($printValueYA >0){
                  $output .= '<td class="text-center"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center">-</td>';
                }
              }  
            }else{
              $output.='<td class="text-center">-</td>';
            }
          $output.='</tr>';
          /*Number of student calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">No. Of Student</b></td>';
          $output.='<td class="text-center">'.$stuAll.'</td>';
          $output.='<td class="text-center">'.$stuAll.'</td>';
          $output.='<td class="text-center">'.$stuAll.'</td>';
          $output.='</tr>';
          $output.='<tr><td><b style="white-space: nowrap">Absence Days</b></td>';
          /*1st semester absent days*/
          
          $tot1stSem=0;
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
                   $output .= '<td class="text-center"><B>'.$tot1stSem.'</B></td>';
              }else{
                   $output .= '<td class="text-center"><B>-</B></td>';
              }
          
          /*2nd semester absent days*/
          $tot2stSem=0;
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
          $output.='</tr>';
          $output.='</table></div>';
          $output.='</div>';/*result table closed*/
          $output.='</div></div>';/*class row closed*/
        }
      } 
    }
    return $output;
  }
  function quarteroster($max_year,$gradesec,$branch,$quarter){
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
      <h5><b> Campus : '.$branch.' </b></h5>
      <h5><b> Academic Year : '.$max_year.'E.C</b></h5>
      <h5><b> Grade & Section: '.$gradeSec.' </b></h5>
      <h5><b> Season: Yearly Average </b></h5>
      <h6><b> Total NO. Of Student : '.$totalStudent.'</b></h6>
      </span> </b>
    </div>';
    $output .='<div class="table-responsive" id="employee_table">
     <table width="100%"  class="table table-borderedr" >
    <tr><th> No.</th>
    <th>Students Name</th> 
    <th>Grade</th> 
    <th>Gender</th> 
    <th>Season</th>';
    /*$query_result=$this->db->query(" Select * from subject where Grade='$grade_name' and Academic_Year='$max_year' and onreportcard='1' group by Subj_name order by suborder ");*/
     $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
    $totSubject=$query_result->num_rows();
    foreach ($query_result ->result() as $rvalue)
    {
      $output .=' <th>'.$rvalue->subject.'</th>';
    }
    $output .='<th> Total</th> ';
    $output .='<th> Average</th> ';
    $output .='<th> Rank</th> ';
    $output .='<th> Conduct</th> ';
    $output .='<th> Absence</th> ';
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
      <td>'.$row->gradesec.'</td>
      <td>'.$row->gender.'</td>';
        $output .=' <td><B>'.$quarter.'</B></td>';
        $totA=0;
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->subject;
          $query_quarter_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and quarter ='$quarter' and subject='$subject_mark' group by subject ");
          if($query_quarter_result->num_rows()>0){
            foreach ($query_quarter_result->result() as $quarter_result) 
            {
              $letter=$quarter_result->letter;
              $result=$quarter_result->total;
              if($result>0){
                  if($letter!='A')
                  {
                    $output .='<td class="text-center">'.number_format((float)$result,2,'.','').'</td>';
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
                }else{
                    $output.= "<td class='text-center'> -</td>";
                }
            }
          }else{
            $output.= "<td class='text-center'> -</td>";
            $result=0;
          }
          $totA=$result + $totA;
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
        /*$output .= '<td class="text-center"><B>'.number_format((float)$totA,2,'.','').'</B></td>';//Each quarter Total
        $output .= '<td class="text-center"><B>'.number_format((float)$totA/$totSubject,2,'.','').'</B></td>';//Each quarter Average*/
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and mergedname='' and quarter='$quarter' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and mergedname='' and grade='$gradesec' group by grade ");
        foreach ($query_rank->result() as $rvalue) {
          $output .= '<td class="text-center"><B>'.$rvalue->stuRank.'</B></td>';//Each quarter rank
        }
        //Each quarter conduct starts
        $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' and bsname='Conduct' group by stuid ");
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
        /*$query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where 
        attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='$quarter' and attt.academicyear='$max_year' and se.Academic_year='$max_year' ");
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
      
      $output.='</tr>';
      $stuNo++;
    }
    $output .=' </table> </div>';
    $output .='<div class="row">
    <div class="col-md-6">';
    $queryHoomRoom=$this->db->query("select u.fname,u.mname,mysign from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
              if($queryHoomRoom->num_rows()>0){
                $rowHommeRoom=$queryHoomRoom->row_array();
                $tfName=$rowHommeRoom['fname'];
                $tmName=$rowHommeRoom['mname'];
                $signSigns=$rowHommeRoom['mysign'];
              }else{
                $tfName='------';
                $tmName='------';
                $signSigns='_____';
              }
              $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join directorplacement as dp ON dp.staff=u.username where dp.grade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
               if($queryDirector->num_rows()>0){
                $rowSignD=$queryDirector->row();
                $signName=$rowSignD->fname;
                $signmame=$rowSignD->mname;
                $signlame=$rowSignD->lname;
                $signlame=$rowSignD->lname;
                $signSignsD=$rowSignD->mysign;
              }else{
                $signName='------';
                $signmame='------';
                $signSignsD='_____';
              }                          
              $output.="<p>HRT's Name:- <u><b>".$tfName." ".$tmName."</b></u> <br>Signature.<img alt='.' src='".base_url()."/".$signSigns."' style='height:40px;width:120px'></p></div>
              <div class='col-md-6'>";
            $output.="<p>Director's Name:-<u><b>".$signName." ".$signmame."</b></u><br>
                Signature  <img alt='.' src='".base_url()."/".$signSignsD."' class='' style='height:40px;width:120px'></p></div>
                </div>";
        return $output;
  
  }
  function quarterosterFairway($max_year,$gradesec,$branch,$quarter){
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
    $query_result=$this->db->query(" Select * from subject where Grade='$grade_name' and Academic_Year='$max_year' group by Subj_name order by suborder ");
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
    $output .='<td class="text-center rotateJossRoster"><div> Tardiness</div></td> ';
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
      <h5> Campus : '.$branch.' </h5>
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
        
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and mergedname='' and quarter='$quarter' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and mergedname='' and grade='$gradesec' group by grade ");
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
      $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='Quarter1' and onreportcard='1' or grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='Quarter2' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter1' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' or stuid='$stuid' and quarter= 'Quarter2' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' group by grade ");
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
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='$quarter' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' group by grade ");
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
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='Quarter3' and onreportcard='1' or grade='$gradesec' and rpbranch='$branch' and letter='#' and quarter='Quarter4' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter3' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' or stuid='$stuid' and quarter= 'Quarter4' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' group by grade ");
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
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' group by grade ");
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
      <h5> Campus : '.$branch.' </h5>
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
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and onreportcard='1' and mergedname='' and quarter='$quarter' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and onreportcard='1' and mergedname='' and grade='$gradesec' group by grade ");
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
      $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and quarter='Quarter1' and onreportcard='1' or grade='$gradesec' and rpbranch='$branch' and quarter='Quarter2' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter1' and rpbranch='$branch' and academicyear='$max_year' and grade='$gradesec' and onreportcard='1' or stuid='$stuid' and quarter= 'Quarter2' and rpbranch='$branch' and academicyear='$max_year' and grade='$gradesec' and onreportcard='1' group by grade ");
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
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and quarter='$quarter' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and grade='$gradesec' and onreportcard='1' group by grade ");
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
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and quarter='Quarter3' and onreportcard='1' or grade='$gradesec' and rpbranch='$branch' and quarter='Quarter4' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter3' and rpbranch='$branch' and academicyear='$max_year' and grade='$gradesec' and onreportcard='1' or stuid='$stuid' and quarter= 'Quarter4' and rpbranch='$branch' and academicyear='$max_year' and grade='$gradesec' and onreportcard='1' group by grade ");
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
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and rpbranch='$branch' and academicyear='$max_year' and grade='$gradesec' and onreportcard='1' group by grade ");
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
      <h5> Campus : '.$branch.' </h5>
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
  function rosterFairway($max_year,$gradesec,$branch,$page)
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
      <h5> Campus : '.$branch.'</h5>
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
        <td class="text-center rotateJossRoster"><div>Age</div></td> 
        <td class="text-center rotateJossRoster"><div>Sex</div></td> 
        <td class="text-center rotateJossRoster"><div>Term</div></td>';
        $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
        foreach ($query_result ->result() as $rvalue)
        {
          $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
        }
        $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade_name' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");       
        foreach ($query_basicskill->result() as $bsvalue) {
          $bsname=$bsvalue->bsname;
          $output .='<td class="rotateJossRoster text-center"><div>'.$bsvalue->bsname.'</div></td>';
        }
        $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Tardiness</div></td> ';
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
      <td rowspan="8">'.$row->age.'</td>
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
       

        //Each quarter Tardiness starts
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
      $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC "); 
      if($query_basicskill->num_rows()>0){      
        foreach ($query_basicskill->result() as $bsvalue) {
          $output .='<td class="text-center">-</td>';
        }   
      }
      //Letter subjects
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
      /*1st Semester Tardiness calculation starts*/
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center" >'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" >-</td>';
          }
        }else{
          $output .= '<td class="text-center">-</td>';
        }
         /*1st Semester Absence calculation starts*/
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
               $output .= '<td class="text-center" >'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" >-</td>';
          }
        }else{
          $output .= '<td class="text-center">-</td>';
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
        
        //Each quarter Tardiness
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
      $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC "); 
      if($query_basicskill->num_rows()>0){      
        foreach ($query_basicskill->result() as $bsvalue) {
          $output .='<td class="text-center">-</td>';
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
       /*2nd Semester Tardiness calculation starts*/
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center">-</td>';
          }
        }else{
          $output .= '<td class="text-center"></td>';
        }
     
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
               $output .= '<td class="text-center">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" >-</td>';
          }
        }else{
          $output .= '<td class="text-center">-</td>';
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
      $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC "); 
      if($query_basicskill->num_rows()>0){      
        foreach ($query_basicskill->result() as $bsvalue) {
          $output .='<td class="text-center">-</td>';
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
      /*yearly tardiness calculation starts*/
      $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and absentype='Late' ");
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
      /*yearly absence calculation starts*/
      $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
            <td class="text-center rotateJossRoster"><div>Age</div></td> 
            <td class="text-center rotateJossRoster"><div>Sex</div></td> 
            <td class="text-center rotateJossRoster"><div>Term</div></td>';
            $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
            foreach ($query_result ->result() as $rvalue)
            {
              $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
            }
            $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade_name' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");       
            foreach ($query_basicskill->result() as $bsvalue) {
              $bsname=$bsvalue->bsname;
              $output .='<td class="rotateJossRoster text-center"><div>'.$bsvalue->bsname.'</div></td>';
            }            $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Tardiness</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
            $output .='</tr>';
        }
      }else{
        $output .='</tr>';
      }
      $stuNo++;
    }
    /**/
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
  function rosterFairwaySemester($max_year,$gradesec,$branch,$page)
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
      <h5> Campus : '.$branch.'</h5>
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
        <td class="text-center rotateJossRoster"><div>Age</div></td> 
        <td class="text-center rotateJossRoster"><div>Sex</div></td> 
        <td class="text-center rotateJossRoster"><div>Term</div></td>';
        $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
        foreach ($query_result ->result() as $rvalue)
        {
          $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
        }
        $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade_name' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");       
        foreach ($query_basicskill->result() as $bsvalue) {
          $bsname=$bsvalue->bsname;
          $output .='<td class="rotateJossRoster text-center"><div>'.$bsvalue->bsname.'</div></td>';
        }
        $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Tardiness</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
        $output .='</tr>';
    $stuNo=1;
    $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
    $total_subject=$count_subject->num_rows();
    foreach ($query_student->result() as $row) 
    {
      $stuid=$row->id;
      $username1=$row->username;
      $grade_sec=$row->gradesec;
      $grade=$row->grade;
      $query_quarter=array('Quarter1','Quarter2');
      $output .='<tr><td rowspan="4">'.$stuNo.'.</td>
      <td rowspan="4">'.$row->fname.' '.$row->mname.' '.$row->lname.'</td> 
      <td rowspan="4">'.$row->age.'</td>
      <td rowspan="4">'.$row->gender.'</td>';
      $output .='<tr id="BGS"><td>1<sup>st</sup> Semester Average </td>';
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $queryCheckSem1=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter2'  ");
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
      $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC "); 
      if($query_basicskill->num_rows()>0){      
        foreach ($query_basicskill->result() as $bsvalue) {
          $output .='<td class="text-center">-</td>';
        }   
      }
      //Letter subjects
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
      /*1st Semester Tardiness calculation starts*/
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center" >'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" >-</td>';
          }
        }else{
          $output .= '<td class="text-center">-</td>';
        }
         /*1st Semester Absence calculation starts*/
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
               $output .= '<td class="text-center" >'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" >-</td>';
          }
        }else{
          $output .= '<td class="text-center">-</td>';
        }
      $output.='</tr>';

      /*2nd semester calculation starts*/
      $output .='<tr id="BGS"><td>2<sup>nd</sup> Semester Average </td>';
      
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $queryCheckSem2=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter4'  ");
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
      $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC "); 
      if($query_basicskill->num_rows()>0){      
        foreach ($query_basicskill->result() as $bsvalue) {
          $output .='<td class="text-center">-</td>';
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
       /*2nd Semester Tardiness calculation starts*/
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center">-</td>';
          }
        }else{
          $output .= '<td class="text-center"></td>';
        }
     
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
               $output .= '<td class="text-center">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" >-</td>';
          }
        }else{
          $output .= '<td class="text-center">-</td>';
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
      $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC "); 
      if($query_basicskill->num_rows()>0){      
        foreach ($query_basicskill->result() as $bsvalue) {
          $output .='<td class="text-center">-</td>';
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
      /*yearly tardiness calculation starts*/
      $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and absentype='Late' ");
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
      /*yearly absence calculation starts*/
      $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
        $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp 
                      ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' 
                      and isapproved='1' and status='Active' and mysign!='' ");
        if($queryDirector->num_rows()>0){
          $rowSignD=$queryDirector->row();
          $signName=$rowSignD->fname;
          $signmame=$rowSignD->mname;
          $signlame=$rowSignD->lname;
          $signlame=$rowSignD->lname;
          $signSigns=$rowSignD->mysign;
          $output.="<td colspan='4'>Home Room Teachers
          Signature  <img alt='Sig.' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date: ".date('M-d-Y')."</td>";
        }else{
          $output.="<td colspan='4'>Home Room Teachers Signature .______________________.<br>
          Date._____________________.</td>";
        }
        /*$output.='<td colspan="4">Home Room Teachers Signature .______________________.<br>
        Date._____________________.</td>';*/
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="8">Record Office Signature._____________________. <br>
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
            $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade_name' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");       
            foreach ($query_basicskill->result() as $bsvalue) {
              $bsname=$bsvalue->bsname;
              $output .='<td class="rotateJossRoster text-center"><div>'.$bsvalue->bsname.'</div></td>';
            }            $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Tardiness</div></td> ';
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
  function fetch_custom_assesment_report($mybranch,$gradesec,$max_year,$quarter)
  {
    $output='';
      $query=$this->db->query("select ev.eid,ev.evname from users as u right join evaluation as ev ON u.grade=ev.grade where u.academicyear='$max_year' and u.status='Active' and u.isapproved='1' and u.usertype='Student' and ev.academicyear='$max_year' and u.gradesec='$gradesec' and u.branch='$mybranch' and ev.quarter='$quarter' group by ev.evname , ev.grade order by eid ASC ");
      $output='<div class="row"> ';
      foreach ($query->result() as $evavalue) {
        $output.='<div class="col-lg-6 col-12">
                  <div class="pretty p-bigger">
                    <input type="checkbox" name="evaluationanalysis" value="'.$evavalue->eid.'" class="evaluationanalysis" id="customCheck1">
                    <div class="state p-success">
                      <i class="icon material-icons"></i>
                      <label></label>'.$evavalue->evname.'
                    </div>
                  </div> 
              </div>';
      }
      $output.='</div>';
    
    return $output;
  }
  function update_reportcard_custom_Result($max_year,$gradesec,$branch,$max_quarter,$assesGsanalysis){
    $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard_mid_report".$max_year."' ");
    if ($queryCheck->num_rows()>0 ){
      $queyDelete=$this->db->query("delete from reportcard_mid_report".$max_year." where rpbranch ='$branch' and grade='$gradesec' and quarter='$max_quarter' ");
      $queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gradesec' and u.branch='$branch' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname ASC ");
      
     
      foreach ($queryStudent->result_array() as $row) { 
        $id=$row['id'];
        $grade=$row['grade'];
        $average=0;
        
             
        $querySubject=$this->db->query("select * from subject where Academic_Year='$max_year' and Grade='$grade' group by Subj_name order by suborder ASC ");
        if($querySubject->num_rows()>0){
          foreach($querySubject->result() as $subject_row){
            $average1=0;
            $gs_subject=$subject_row->Subj_name;
            $letter=$subject_row->letter;
            $mergedPercent=$subject_row->Merged_percent;
            $onReportCard=$subject_row->onreportcard;
            $subjorder=$subject_row->suborder;
            if($subject_row->Merged_name==''){
              $mergedSubject='';
            }else{
              $mergedSubject=$subject_row->Merged_name;
            }
            foreach($assesGsanalysis as $assesGsanalysiss){
            $evalname_query=$this->db->query("select us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$max_quarter' and us.gradesec='$gradesec' and us.branch='$branch' and us.academicyear='$max_year' and ev.eid='$assesGsanalysiss' group by ev.evname order by ev.eid ASC");
            foreach ($evalname_query->result_array() as $mark_name)
            {
              $evName=$mark_name['evname'];
              $grade=$mark_name['grade'];
              $mname_gs=$mark_name['eid'];
              $queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$gs_subject' and customasses='$evName' and customquarter='$max_quarter' ");
              if($queryCheckPercentage->num_rows()>0){
                $rowPercent=$queryCheckPercentage->row();
                $percent=$rowPercent->custompercent;
              }else{
                $percent= $mark_name['percent'];
              }
              $query_value = $this->db->query("select markname,sum(value) as total from mark".$branch.$gradesec.$max_quarter.$max_year." where  subname='$gs_subject' and quarter='$max_quarter' and evaid='$mname_gs' and mbranch='$branch' group by markname order by mid ASC");
              if($query_value->num_rows()>0){
                $totalMark=0;$outofTot=0;
                foreach ($query_value->result_array() as $value) {
                  $markNameStu=$value['markname'];
                  $queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from mark".$branch.$gradesec.$max_quarter.$max_year." where stuid='$id' and subname='$gs_subject' and quarter='$max_quarter' and evaid='$mname_gs' and mbranch='$branch' and markname='$markNameStu' group by markname order by mid ASC");
                  if($queryStuValue->num_rows()>0){
                    foreach ($queryStuValue->result_array() as $kevalue) {
                      $outofTot=$outofTot+$kevalue['outof'];
                      $totalMark=$totalMark+$kevalue['value'];
                    }
                  }
                  $queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$branch.$gradesec.$max_quarter.$max_year." where subname='$gs_subject' and quarter='$max_quarter' and evaid='$mname_gs' and mgrade='$gradesec' and mbranch='$branch' group by markname order by mid ASC");
                    $sumOutOf=0;
                  foreach ($queryMvalue->result_array() as $mark_name) {
                    $sumOutOf=$mark_name['outof'] + $sumOutOf;  
                  }
                }
                if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='')
                {
                  $conver= ($totalMark *$percent )/$sumOutOf;
                  $average =$conver + $average;
                  $average1 =($average * $mergedPercent)/100;
                }
              }
            }
          }
            
            $average1=number_format((float)$average1,2,'.','');
            $data[]=array(
              'stuid'=>$id,
              'subject'=>$gs_subject,
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

      if($querySubject->num_rows()>0){
        $query_insert1=$this->db->insert_batch('reportcard_mid_report'.$max_year,$data);
      }
      $queryMergedSubject=$this->db->query("select us.grade, us.id, us.gradesec, su.Subj_name, su.Merged_percent, su.Merged_name, su.suborder, su.letter, su.onreportcard from subject as su cross join users as us where us.grade=su.Grade and us.gradesec='$gradesec' and us.status='Active' and us.isapproved='1' and us.academicyear='$max_year' and su.Academic_Year='$max_year' and us.branch='$branch' and Merged_name!='' ");
      if($queryMergedSubject->num_rows()>0){
        $sqlDelete=$this->db->query("select *, sum(total) as mergedTot from reportcard_mid_report".$max_year." where mergedname!='' and rpbranch='$branch' and grade='$gradesec' and quarter='$max_quarter' group by mergedname,stuid ");
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
          $query_insert1=$this->db->insert_batch('reportcard_mid_report'.$max_year,$data1);
        }
      }
      if($query_insert1){
        return true;
      }
    }
  }
  function custom_mid_report($max_year,$gradesec,$branch,$max_quarter,$assesGsanalysis,$includeStudentBasicSkill){
    $output ='';$resultSem1=0;
    $resultSem2=0;
    $queryCHK=$this->db->query("select * from reportcard_mid_report".$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
    if($max_quarter=='Quarter1'){
      $quarterName ='1<sup>st</sup> Quarter';
    }else if($max_quarter=='Quarter2'){
      $quarterName='2<sup>nd</sup> Quarter';
    }else if($max_quarter=='Quarter3'){
      $quarterName='3<sup>rd</sup> Quarter';
    }else if($max_quarter=='Quarter4'){
      $quarterName='4<sup>th</sup> Quarter';
    }else if($max_quarter=='Semester1'){
      $quarterName='1<sup>st</sup> Semester';
    }else if($max_quarter=='Semester2'){
      $quarterName='2<sup>nd</sup> Semester';
    }else if($max_quarter=='semester1'){
       $quarterName='1<sup>st</sup> Semester';
    }else if($max_quarter=='semester2'){
      $quarterName='2<sup>nd</sup> Semester';
    }else if($max_quarter=='Term1'){
      $quarterName='1<sup>st</sup> Term';
    }else if($max_quarter=='Term2'){
      $quarterName='2<sup>nd</sup> Term';
    }else if($max_quarter=='Term3'){
      $quarterName='3<sup>rd</sup> Term';
    }
    if($queryCHK->num_rows()>0){
      $queryStudent=$this->db->query("select fname,mname,lname,id,grade, section,gradesec,username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
      $stuAll=$queryStudent->num_rows();

      /*$queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, username from users where id='$id' ");
      $queryStudentNum=$this->db->query("select fname,mname,lname,id,grade, section,gradesec,username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC");
      $stuAll=$queryStudentNum->num_rows();*/
      if($queryStudent->num_rows()>0){
        $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $slogan=$row_name->slogan;
        $logo=$row_name->logo;
        $querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowGyear = $querySlogan->row();
        $gYearName=$rowGyear->gyear;
        $dateYear=date('Y');
        foreach ($queryStudent->result() as $fetchStudent)
        {
          $printValue=0;
          $grade=$fetchStudent->grade;
          $stuid=$fetchStudent->id;
          $username1=$fetchStudent->username;
          $output.= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; "> 
          <div class="support-ticket media pb-1 mb-3 card-header">
          <img src="'.base_url().'/logo/'.$logo.'" style="width:auto;height: 110px;" class="user-img mr-2" alt="">
          <div class="media-body ml-3">
            <span class="font-weight-bold"><h2 id="ENScool"><B id="ENS">'.$school_name.' </B></h2></span>
            <p class="my-1"> <h4 id="ENScool"><B id="ENS"><u>'.$gYearName.' G.C Mid Quarter Assessment Result </u></B></h4></p>
          </div>
        </div> ';
          $output.='<div class="row">';
          $output.="<div class='col-lg-6 col-6'><b id='ENS'>Student's Name: ".$fetchStudent->fname." ".$fetchStudent->mname." ".$fetchStudent->lname."</b></div>";
          $output.='<div class="col-lg-3 col-3"><b id="ENS">Grade: '.$fetchStudent->grade.'</b></div>';
          $output.='<div class="col-lg-3 col-3"><b id="ENS">Section: '.$fetchStudent->section.'</b></div></div> ';
          $output.='<div class="row" id="ENS">
          <div class="col-lg-6 col-md-6">';
          $output.='<div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-bordered table-md" cellspacing="9" cellpadding="9">';
          $output.='
          <tr><th>Subject</th>
          <td class="text-center"><b>Result (100%)</b></td><td class="text-center"><b>Rank</b></td></tr>';
          $querySubject=$this->db->query("select * from reportcard_mid_report".$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr><td style="white-space: nowrap"><B>'.$fetchSubject->subject.'</B></td>';
              $queryReportCardQ1=$this->db->query("select * from reportcard_mid_report".$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='$max_quarter' and 
              subject='$subject' and onreportcard='1' group by subject order by subjorder ");
              if($queryReportCardQ1->num_rows()>0){
                foreach ($queryReportCardQ1->result() as $fetchResult1) {
                  $result1=$fetchResult1->total;
                  if($fetchResult1->total =='' || $fetchResult1->total<=0){
                    $output.='<td class="text-center">-</td>';
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
                  $queryQ1SubRank1=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from 
                  reportcard_mid_report".$max_year." where grade='$gradesec' and rpbranch='$branch' and subject='$subject' and quarter='$max_quarter' group by stuid) sm)) as stuRank1 from 
                  reportcard_mid_report".$max_year." where grade='$gradesec' and stuid='$stuid' and rpbranch='$branch' and quarter='$max_quarter' and subject='$subject' group by subject ");
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
            }
            /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
            $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='totalname' and grade='$grade' and allowed='1' ");
            if($queryRankAllowed->num_rows()>0){
              $output.='<tr><td><b>Total</b></td>';
              $quartrTotal=$this->db->query("select sum(total) as total from reportcard_mid_report".$max_year." where stuid='$stuid' and quarter='$max_quarter' and onreportcard='1' and letter='#' ");
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
              $output.='</tr>';
            }
            /*Horizontal Average calculation starts*/
            $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='averagename' and grade='$grade' and allowed='1' ");
            if($queryRankAllowed->num_rows()>0){
              $output.='<tr><td><b>Average</b></td>';
              /*1st and snd quarter calculation starts*/
              $countSubject=$this->db->query("select * from reportcard_mid_report".$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
              $subALl=$countSubject->num_rows();
              if($subALl>0){
                $quartrTotal=$this->db->query("select sum(total) as total from reportcard_mid_report".$max_year." where stuid='$stuid' and quarter='$max_quarter' and onreportcard='1' and letter='#' ");
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
              $output.='</tr>';
            }
            $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='rankname' and grade='$grade' and allowed='1' ");
            if($queryRankAllowed->num_rows()>0){
              $output.='<tr><td><b>Rank</b></td>';
              $query_total=$this->db->query("select * from reportcard_mid_report".$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` 
              from reportcard_mid_report".$max_year." where grade='$gradesec' and letter='#' and quarter='$max_quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank
              from reportcard_mid_report".$max_year." where stuid='$stuid' and quarter= '$max_quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' and rpbranch='$branch' group by grade ");
              if($query_total->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                  $output .= '<td class="text-center" colspan="2"><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center" colspan="2">-</td>';
              }
              $output.='</tr>';
            }
            $output.='</table></div>';
            if($includeStudentBasicSkill=='1'){
              $output.="<h6 class='text-success'><b><u>Homeroom Teacher's Comments & Recommendations </u></b></h6>";
              $queryHoomRoom=$this->db->query("select u.fname,u.mname,mysign from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
              if($queryHoomRoom->num_rows()>0){
                $rowHommeRoom=$queryHoomRoom->row_array();
                $tfName=$rowHommeRoom['fname'];
                $tmName=$rowHommeRoom['mname'];
                $signSigns=$rowHommeRoom['mysign'];
              }else{
                $tfName='------';
                $tmName='------';
                $signSigns='_____';
              }
              $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join directorplacement as dp ON dp.staff=u.username where dp.grade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
               if($queryDirector->num_rows()>0){
                $rowSignD=$queryDirector->row();
                $signName=$rowSignD->fname;
                $signmame=$rowSignD->mname;
                $signlame=$rowSignD->lname;
                $signlame=$rowSignD->lname;
                $signSignsD=$rowSignD->mysign;
              }else{
                $signName='------';
                $signmame='------';
                $signSignsD='_____';
              }
              $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and '$printValue' between mingradevalue and maxgradevalue and academicYear='$max_year'");
              if($printValue >0 && $reportCardComments->num_rows()>0){
                foreach($reportCardComments->result() as $commentValue){
                  $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                }
              }else{
                $output.=' ______________________________________ ____________________________________________  ____________________________________________ <br>';
              }                           
              $output.="<p>HRT's Name:- <u><b>".$tfName." ".$tmName."</b></u> Signature.<img alt='.' src='".base_url()."/".$signSigns."' style='height:40px;width:120px'></p>
              <p>Director's Name:-<u><b>".$signName." ".$signmame."</b></u>
                Signature  <img alt='.' src='".base_url()."/".$signSignsD."' class='' style='height:40px;width:120px'></p>";
            }
            $output.='</div>';/*result table closed*/
            if($includeStudentBasicSkill=='1'){
              $output.= '<div class="col-lg-6 col-md-6">';
              $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
              if($queryCategory->num_rows()>0){
                $output.= '<div class="table-responsive">
                <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
                $dateYear=date('Y');
                $output .='<tr><th colspan="5" class="text-center">'.$gYearName.' G.C '.$max_year.' E.C Basic Skills and Behaviour Progress Report</th></tr>';
                $output .='<tr><th>Evaluation Area</th>';
                  $output .='<th class="text-center">'.$quarterName.'</th>';
                foreach ($queryCategory->result() as $bscatvalue) {
                  $bscategory=$bscatvalue->bscategory;
                  $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and bsname!='Conduct' and bscategory='$bscategory' order by bsname ASC ");       
                  $output .='<tr><th colspan="5" id="BGS" class="text-center">'.$bscategory.'</th>';
                  foreach ($query_basicskill->result() as $bsvalue) {
                    $bsname=$bsvalue->bsname;
                    $output .='<tr><td>'.$bsvalue->bsname.'</td>';
                    $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$max_quarter' ");
                    if($query_bsvalue->num_rows()>0) {
                      foreach ($query_bsvalue ->result() as $bsresult) {
                        $output .='<td class="text-center">'.$bsresult->value.'</td>';
                      }
                    }else {
                      $output .='<td class="text-center">-</td>';
                    }
                    $output .='</tr>';
                  }
                }
                $output .='</table></div><br>';/*basic skill table closed*/
                $queryEvaKey=$this->db->query("select * from bstype where academicyear='$max_year' and btgrade='$grade' ");
                $output.='<div class="row"><div class="col-lg-6 col-6">';
                if($queryEvaKey->num_rows()>0){
                  $output.= '<div id="ENS" class="table-responsive">
                  <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
                  $output .='<th class="text-center" colspan="2"><u>Grading System</u>:-</th>';
                  foreach ($queryEvaKey->result() as $keyVValue) {
                    $output .='<tr><td class="text-center">'.$keyVValue->bstype.'</td>';
                    $output .='<td class="text-center">'.$keyVValue->bsdesc.'</td></tr>';
                  }
                  $output .='</table></div>';
                }else{
                  $output .='No Evaluation Key found';
                }
                $output .='</div><div class="col-lg-6 col-6">';
                $queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade' order by leId ASC ");
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
                $output .='</div></div>';

                $output .='</div><br>';/*basic skill column closed*/
              }
              $output .='</div>';
              $output .='</div><br>';/*basic skill column closed*/
            }else{
              $output.="<div class='col-lg-6 col-6'><h6 class='text-success'><b><u>Homeroom Teacher's Comments & Recommendations </u></b></h6>";
              $queryHoomRoom=$this->db->query("select u.fname,u.mname,mysign from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
              if($queryHoomRoom->num_rows()>0){
                $rowHommeRoom=$queryHoomRoom->row_array();
                $tfName=$rowHommeRoom['fname'];
                $tmName=$rowHommeRoom['mname'];
                $signSigns=$rowHommeRoom['mysign'];
              }else{
                $tfName='------';
                $tmName='------';
                $signSigns='_____';
              }
              $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join directorplacement as dp ON dp.staff=u.username where dp.grade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
               if($queryDirector->num_rows()>0){
                $rowSignD=$queryDirector->row();
                $signName=$rowSignD->fname;
                $signmame=$rowSignD->mname;
                $signlame=$rowSignD->lname;
                $signlame=$rowSignD->lname;
                $signSignsD=$rowSignD->mysign;
              }else{
                $signName='------';
                $signmame='------';
                $signSignsD='_____';
              }
              $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and '$printValue' between mingradevalue and maxgradevalue and academicYear='$max_year'");
              if($printValue >0 && $reportCardComments->num_rows()>0){
                foreach($reportCardComments->result() as $commentValue){
                  $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                }
              }else{
                $output.=' ______________________________________ ____________________________________________  ____________________________________________ <br>';
              }                           
              $output.="<p>HRT's Name:- <u><b>".$tfName." ".$tmName."</b></u> Signature.<img alt='.' src='".base_url()."/".$signSigns."' style='height:40px;width:120px'></p>
              <p>Director's Name:-<u><b>".$signName." ".$signmame."</b></u>
                Signature  <img alt='.' src='".base_url()."/".$signSignsD."' class='' style='height:40px;width:120px'></p>";
             /*class row closed*/ 
                $queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade' order by leId ASC ");
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
                 $output.='</div>';
                $output .='</div>';
                $output .='</div><br>';/*basic skill column closed*/
            }
            $output.='<h6 class="text-center"><strong>'.$slogan.'</strong></h6>';
            $output.='<h7 class="pull-right">ይህ ካርድ ጊዜያዊ ዉጤት ማሳወቂያ ሲሆን እንደ ትምህርት ማስረጃ አያገለግልም::</h7><br>';
          }
          $output .='</div>';
        } 
      } 
    }
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
  function transcriptFairway($academicyear,$gradesec,$branch,$noGrade){
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
            $gradeSubject='11N';
            break;
          case '12S':
            $gradeSubject='11S';
            break;
          case '12NS':
            $gradeSubject='11NS';
            break;
          case '12SS':
            $gradeSubject='11SS';
            break;
          case '11NS':
            $gradeSubject='10';
            break;
          case '11SS':
            $gradeSubject='10';
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
    $phone=$row_name->phone;
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
        $queryKey=$this->db->query("select * from letterange where grade = '$currGrade' and academicYear='$academicyear' ");
        
        $output.='<div style="width:100%;height:92%;page-break-inside:avoid;">';
        $output.='<div class ="row" id="ENS">
        <div class="col-lg-12 col-12">
            <div class="media pb-1 mb-3 text-center card-header">
              <img src="'.base_url().'/logo/'.$schooLogo.'" style="width:110px;height: 110px;" class="user-img mr-2" alt="">
              <div class="media-body ml-3">
                <h2>'.$school_name.'</h2>
                <h3>KINDERGARTEN - COLLEGE PREPARATORY</h3>
                <i class="fas fa-phone"></i> '.$phone.'
                <small><i class="fas fa-globe"></i> '.$website.' | admin@fairwayschoolethiopia.com</small>';
              $output.='</div>
            </div>
          </div>
          <div class="col-lg-12 col-12 text-center">
            <h3><B>OFFICIAL SCHOOL TRANSCRIPT</B></h3>
          </div>
        </div>

        <div class ="row" id="ENS">
          <div class="col-lg-8 col-8">
            <div class="support-ticket media pb-1 mb-3">
              <img src="'.base_url().'/profile/'.$stuName->profile.'" style="width:90px;height: 110px;" class="user-img mr-2" alt="">
              <div class="media-body ml-3">
                <p class="my-1">NAME:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>'.$stuName->fname.' '.$stuName->mname.' '.$stuName->lname.'</u></p>
                <p class="my-1">GENDER: &nbsp;&nbsp;<u>'.$stuName->gender.' </u></p>
                <p class="my-1">Grade: &nbsp;&nbsp;&nbsp;&nbsp;<u>'.$stuName->gradesec.'</u></p>';
                 if($stuName->age>0){
                  $output.='<p class="my-1">Age: &nbsp;&nbsp;<u>'.$stuName->age.' </u></p>';
                }else{
                  $output.='<p class="my-1">Age: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_____</p>';
                }
              $output.='</div>
            </div>
          </div>
          <div class="col-lg-4 col-4">
          <div class="keyTextInfo">
          <div class="row">';
          foreach($queryKey->result() as $keyVal){
            $output.='<div class="col-lg-6 col-6">';
            $output.=''.$keyVal->minValue.'-'.$keyVal->maxiValue.'='.$keyVal->letterVal.'';
            $output.='</div>';
          }
          $output.='</div></div></div></div>';           
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
                case '11N':
                  $gradeName='ELEVEN';
                  break;
                case '11NS':
                  $gradeName='ELEVEN';
                  break;
                case '11SS':
                  $gradeName='ELEVEN';
                  break;
                case '11S':
                  $gradeName='ELEVEN';
                  break;
                case '12':
                  $gradeName='TWELVE';
                  break;
                case '12n':
                  $gradeName='TWELVE';
                  break;
                case '12s':
                  $gradeName='TWELVE';
                  break;
                case '12N':
                  $gradeName='TWELVE';
                  break;
                case '12S':
                  $gradeName='TWELVE';
                  break;
                case '12SS':
                  $gradeName='TWELVE';
                  break;
                case '12NS':
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
            $letter=$subjValue->letter;
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
                    $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and subject='$subjName' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and subject='$subjName'");
                    foreach ($quartrSem1Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
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
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and subject='$subjName' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and subject='$subjName'");
                    foreach ($quartrSem2Total->result() as $totalValueSem21) {
                      $printValueSem12=(($totalValueSem21->total)/2);
                      if($printValueSem12 >0){
                        if($letter!='A'){                          
                          if($printValueSem12=='100'){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem12,0,'.','').'</td>';
                          }else{
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem12,2,'.','').'</td>';
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem12 between minValue and maxiValue and academicYear='$targetYear'");
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
                    $quartrSem2Totalver=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and subject='$subjName'  ");
                    foreach ($quartrSem2Totalver->result() as $totalValueSem1Ave) {
                      $printValueSem1Ave=(($totalValueSem1Ave->total)/2)/2;
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
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
                      if($printValueSem1 >0){
                        if($printValueSem1=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }
                        
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
    
                    }
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem12=(($totalValueSem1->total)/2);
                      if($printValueSem12 >0){                        
                        if($printValueSem12=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem12,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem12,2,'.','').'</td>';
                        }
                        
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1Av) {
                      $printValueSemAv=(($totalValueSem1Av->total)/4);
                      if($printValueSemAv >0){                        
                        if($printValueSemAv=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSemAv,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$printValueSemAv,2,'.','').'</td>';
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
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSemAv=(($totalValueSem1->total)/2)/$subALast;
                      if($printValueSemAv >0){                        
                        if($printValueSemAv=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSemAv,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$printValueSemAv,2,'.','').'</td>';
                        }
                        
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSemAv2=(($totalValueSem1->total)/2)/$subALast;
                      if($printValueSemAv2 >0){ 
                        if($printValueSemAv2=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSemAv2,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$printValueSemAv2,2,'.','').'</td>';
                        }
                        
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1Av=(($totalValueSem1->total)/4)/$subALast;
                      if($printValueSem1Av >0){                        
                        if($printValueSem1Av=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1Av,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1Av,2,'.','').'</td>';
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
                $output.='
                DATE OF ISSUE.______________.
              </div>
            </div>
          </div>
        </div>';
        $output.='<div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-6 col-6" id="ENS">
            <span class="">_____________________<br>REGISTRAR</span>  
          </div>
          <div class="col-md-5 col-6" id="ENS"> 
            <span class="">_____________________<br>ADMINISTRATOR</span>  
          </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="text-center">
         <span id="ENS">Note: THIS TRANSCRIPT IS INVALID IF ANY ALTERNATION OR ERASER COMMITS & UNLESS IT BEARS THE OFFICIAL SEAL OF THE SCHOOL.</span>
        </div>
        <br>'; 
      }
    }
    return $output;
  }
  function transcriptCustomFairway($academicyear,$gradesec,$branch,$userName,$noGrade){
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
            $gradeSubject='11N';
            break;
          case '12S':
            $gradeSubject='11S';
            break;
          case '12NS':
            $gradeSubject='11NS';
            break;
          case '12SS':
            $gradeSubject='11SS';
            break;
          case '11NS':
            $gradeSubject='10';
            break;
          case '11SS':
            $gradeSubject='10';
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
    $phone=$row_name->phone;
    if($queryTr->num_rows()>0){
      $rowcheckGrade=$queryTr->row();
      $cGrade=$rowcheckGrade->grade;
      $queryAGreYear=$this->db->query("select gyear from academicyear where year_name='$academicyear' ");
      $rowAG = $queryAGreYear->row();
      $Agmax_year=$rowAG->gyear;
      foreach ($queryTr->result() as $stuName) {
        $uniqueId=$stuName->unique_id;
        $currGrade=$stuName->grade;
        $stuid=$stuName->id;
        $username=$stuName->username;
        $queryKey=$this->db->query("select * from letterange where grade = '$currGrade' and academicYear='$academicyear' ");
        
        $output.='<div style="width:100%;height:92%;page-break-inside:avoid;">';
        $output.='<div class ="row" id="ENS">
        <div class="col-lg-12 col-12">
            <div class="media pb-1 mb-3 text-center card-header">
              <img src="'.base_url().'/logo/'.$schooLogo.'" style="width:110px;height: 110px;" class="user-img mr-2" alt="">
              <div class="media-body ml-3">
                <h2>'.$school_name.'</h2>
                <h3>KINDERGARTEN - COLLEGE PREPARATORY</h3>
                <i class="fas fa-phone"></i> '.$phone.'
                <small><i class="fas fa-globe"></i> '.$website.' | admin@fairwayschoolethiopia.com</small>';
              $output.='</div>
            </div>
          </div>
          <div class="col-lg-12 col-12 text-center">
            <h3><B>OFFICIAL SCHOOL TRANSCRIPT</B></h3>
          </div>
        </div>

        <div class ="row" id="ENS">
          <div class="col-lg-8 col-8">
            <div class="support-ticket media pb-1 mb-3">
              <img src="'.base_url().'/profile/'.$stuName->profile.'" style="width:90px;height: 110px;" class="user-img mr-2" alt="">
              <div class="media-body ml-3">
                <p class="my-1">NAME:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>'.$stuName->fname.' '.$stuName->mname.' '.$stuName->lname.'</u></p>
                <p class="my-1">GENDER: &nbsp;&nbsp;<u>'.$stuName->gender.' </u></p>
                <p class="my-1">Grade: &nbsp;&nbsp;&nbsp;&nbsp;<u>'.$stuName->gradesec.'</u></p>';
                 if($stuName->age>0){
                  $output.='<p class="my-1">Age: &nbsp;&nbsp;<u>'.$stuName->age.' </u></p>';
                }else{
                  $output.='<p class="my-1">Age: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_____</p>';
                }
              $output.='</div>
            </div>
          </div>
          <div class="col-lg-4 col-4">
          <div class="keyTextInfo">
          <div class="row">';
          foreach($queryKey->result() as $keyVal){
            $output.='<div class="col-lg-6 col-6">';
            $output.=''.$keyVal->minValue.'-'.$keyVal->maxiValue.'='.$keyVal->letterVal.'';
            $output.='</div>';
          }
          $output.='</div></div></div></div>';           
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
                case '11N':
                  $gradeName='ELEVEN';
                  break;
                case '11NS':
                  $gradeName='ELEVEN';
                  break;
                case '11SS':
                  $gradeName='ELEVEN';
                  break;
                case '11S':
                  $gradeName='ELEVEN';
                  break;
                case '12':
                  $gradeName='TWELVE';
                  break;
                case '12n':
                  $gradeName='TWELVE';
                  break;
                case '12s':
                  $gradeName='TWELVE';
                  break;
                case '12N':
                  $gradeName='TWELVE';
                  break;
                case '12S':
                  $gradeName='TWELVE';
                  break;
                case '12SS':
                  $gradeName='TWELVE';
                  break;
                case '12NS':
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
            $letter=$subjValue->letter;
            $output.='<tr><td>'.$subjName.'</td>';
            $targetYear=($academicyear-$noGrade) + 1;
            for($i=1;$i<=$noGrade;$i++){
              $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
              if($queryLastGrade->num_rows()>0){
                foreach($queryLastGrade->result() as $stuRow){
                  $newGradesec=$stuRow->gradesec;
                  $stuid=$stuRow->id;
                  $currGrade=$stuRow->grade;
                  $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
                  if ($queryCheck->num_rows()>0 ){
                    $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and subject='$subjName' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and subject='$subjName'");
                    foreach ($quartrSem1Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
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
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and subject='$subjName' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and subject='$subjName'");
                    foreach ($quartrSem2Total->result() as $totalValueSem21) {
                      $printValueSem12=(($totalValueSem21->total)/2);
                      if($printValueSem12 >0){
                        if($letter!='A'){                          
                          if($printValueSem12=='100'){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem12,0,'.','').'</td>';
                          }else{
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem12,2,'.','').'</td>';
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem12 between minValue and maxiValue and academicYear='$targetYear'");
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
                    $quartrSem2Totalver=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and subject='$subjName'  ");
                    foreach ($quartrSem2Totalver->result() as $totalValueSem1Ave) {
                      $printValueSem1Ave=(($totalValueSem1Ave->total)/2)/2;
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
                  $stuid=$stuRow->id;
                  $currGrade=$stuRow->grade;
                  $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
                  if ($queryCheck->num_rows()>0 ){
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
                      if($printValueSem1 >0){     
                        if($printValueSem1=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
    
                    }
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem12=(($totalValueSem1->total)/2);
                      if($printValueSem12 >0){                        
                        if($printValueSem12=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem12,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem12,2,'.','').'</td>';
                        }
                        
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1Av) {
                      $printValueSemAv=(($totalValueSem1Av->total)/4);
                      if($printValueSemAv >0){                          
                        if($printValueSemAv=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSemAv,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$printValueSemAv,2,'.','').'</td>';
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
          $output.='<tr><td>AVERAGE</td>';
          $targetYear=($academicyear-$noGrade) + 1;   
          for($i=1;$i<=$noGrade;$i++){
            $queryLastGrade=$this->db->query("select username,profile,fname,mname,lname, gender, gradesec,age,unique_id,grade,id from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
            if($queryLastGrade->num_rows()>0){
                foreach($queryLastGrade->result() as $stuRow){
                  $newGradesec=$stuRow->gradesec;
                  $stuid=$stuRow->id;
                  $currGrade=$stuRow->grade;
                  $queryCountSub=$this->db->query("select * from transcript_list where academicyear='$targetYear' and letter='#' and onreportcard='1' and grade='$currGrade' group by subject order by suborder ASC");
                  if($queryCountSub->num_rows()>0){
                    $subALast=$queryCountSub->num_rows();
                  }else{
                    $subALast=1;
                  }
                  $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
                  if ($queryCheck->num_rows()>0 ){
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSemAv=(($totalValueSem1->total)/2)/$subALast;
                      if($printValueSemAv >0){                          
                        if($printValueSemAv=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSemAv,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$printValueSemAv,2,'.','').'</td>';
                        }
                        
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSemAv2=(($totalValueSem1->total)/2)/$subALast;
                      if($printValueSemAv2 >0){                         
                        if($printValueSemAv2=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSemAv2,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$printValueSemAv2,2,'.','').'</td>';
                        }
                        
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1Av=(($totalValueSem1->total)/4)/$subALast;
                      if($printValueSem1Av >0){                       
                        if($printValueSem1Av=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1Av,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1Av,2,'.','').'</td>';
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
                $output.='
                DATE OF ISSUE.______________.
              </div>
            </div>
          </div>
        </div>';
        $output.='<div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-6 col-6" id="ENS">
            <span class="">_____________________<br>REGISTRAR</span>  
          </div>
          <div class="col-md-5 col-6" id="ENS"> 
            <span class="">_____________________<br>ADMINISTRATOR</span>  
          </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="text-center">
         <span id="ENS">Note: THIS TRANSCRIPT IS INVALID IF ANY ALTERNATION OR ERASER COMMITS & UNLESS IT BEARS THE OFFICIAL SEAL OF THE SCHOOL.</span>
        </div>
        <br>'; 
      }
    }
    return $output;
  }
  function letterTranscriptFairway($academicyear,$gradesec,$branch,$noGrade){
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
            $gradeSubject='11N';
            break;
          case '12S':
            $gradeSubject='11S';
            break;
          case '12NS':
            $gradeSubject='11NS';
            break;
          case '12SS':
            $gradeSubject='11SS';
            break;
          case '11NS':
            $gradeSubject='10';
            break;
          case '11SS':
            $gradeSubject='10';
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
    $phone=$row_name->phone;
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
        $queryKey=$this->db->query("select * from letterange where grade = '$currGrade' and academicYear='$academicyear' ");
        
        $output.='<div style="width:100%;height:92%;page-break-inside:avoid;">';
        $output.='<div class ="row" id="ENS">
        <div class="col-lg-12 col-12">
            <div class="media pb-1 mb-3 text-center card-header">
              <img src="'.base_url().'/logo/'.$schooLogo.'" style="width:110px;height: 110px;" class="user-img mr-2" alt="">
              <div class="media-body ml-3">
                <h2>'.$school_name.'</h2>
                <h3>KINDERGARTEN - COLLEGE PREPARATORY</h3>
                <i class="fas fa-phone"></i> '.$phone.'
                <small><i class="fas fa-globe"></i> '.$website.' | admin@fairwayschoolethiopia.com</small>';
              $output.='</div>
            </div>
          </div>
          <div class="col-lg-12 col-12 text-center">
            <h3><B>OFFICIAL SCHOOL TRANSCRIPT</B></h3>
          </div>
        </div>

        <div class ="row" id="ENS">
          <div class="col-lg-8 col-8">
            <div class="support-ticket media pb-1 mb-3">
              <img src="'.base_url().'/profile/'.$stuName->profile.'" style="width:90px;height: 110px;" class="user-img mr-2" alt="">
              <div class="media-body ml-3">
                <p class="my-1">NAME:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>'.$stuName->fname.' '.$stuName->mname.' '.$stuName->lname.'</u></p>
                <p class="my-1">GENDER: &nbsp;&nbsp;<u>'.$stuName->gender.' </u></p>
                <p class="my-1">Grade: &nbsp;&nbsp;&nbsp;&nbsp;<u>'.$stuName->gradesec.'</u></p>';
                 if($stuName->age>0){
                  $output.='<p class="my-1">Age: &nbsp;&nbsp;<u>'.$stuName->age.' </u></p>';
                }else{
                  $output.='<p class="my-1">Age: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_____</p>';
                }
              $output.='</div>
            </div>
          </div>
          <div class="col-lg-4 col-4">
          <div class="keyTextInfo">
          <div class="row">';
          foreach($queryKey->result() as $keyVal){
            $output.='<div class="col-lg-6 col-6">';
            $output.=''.$keyVal->minValue.'-'.$keyVal->maxiValue.'='.$keyVal->letterVal.'';
            $output.='</div>';
          }
          $output.='</div></div></div></div>';           
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
                case '11N':
                  $gradeName='ELEVEN';
                  break;
                case '11NS':
                  $gradeName='ELEVEN';
                  break;
                case '11SS':
                  $gradeName='ELEVEN';
                  break;
                case '11S':
                  $gradeName='ELEVEN';
                  break;
                case '12':
                  $gradeName='TWELVE';
                  break;
                case '12n':
                  $gradeName='TWELVE';
                  break;
                case '12s':
                  $gradeName='TWELVE';
                  break;
                case '12N':
                  $gradeName='TWELVE';
                  break;
                case '12S':
                  $gradeName='TWELVE';
                  break;
                case '12SS':
                  $gradeName='TWELVE';
                  break;
                case '12NS':
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
            $letter=$subjValue->letter;
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
                    $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and subject='$subjName' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and subject='$subjName'");
                    foreach ($quartrSem1Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
                      $value1st=number_format((float)$printValueSem1,0,'.','');
                      if($printValueSem1 >0){
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $value1st between minValue and maxiValue and academicYear='$targetYear'");
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
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and subject='$subjName' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and subject='$subjName'");
                    foreach ($quartrSem2Total->result() as $totalValueSem21) {
                      $printValueSem12=(($totalValueSem21->total)/2);
                      $value2nd=number_format((float)$printValueSem12,0,'.','');
                      if($printValueSem12 >0){
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $value2nd between minValue and maxiValue and academicYear='$targetYear'");
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
                      $printValueSem1Ave=(($totalValueSem1Ave->total)/4);
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
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1=(($totalValueSem1->total)/2);
                  if($printValueSem1 >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem1 between minValue and maxiValue and academicYear='$targetYear'");
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
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem12=(($totalValueSem1->total)/2);
                  if($printValueSem12 >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem12 between minValue and maxiValue and academicYear='$targetYear'");
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
                foreach ($quartrSem2Total->result() as $totalValueSem1Av) {
                  $printValueSemAv=(($totalValueSem1Av->total)/4);
                  if($printValueSemAv >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSemAv between minValue and maxiValue and academicYear='$targetYear'");
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
          }
          $output.='<tr><td>AVERAGE</td>';
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
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSemAv=(($totalValueSem1->total)/2)/$subALast;
                  if($printValueSemAv >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSemAv between minValue and maxiValue and academicYear='$targetYear'");
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
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSemAv2=(($totalValueSem1->total)/2)/$subALast;
                  if($printValueSemAv2 >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSemAv2 between minValue and maxiValue and academicYear='$targetYear'");
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
                  $printValueSem1Av=(($totalValueSem1->total)/4)/$subALast;
                  if($printValueSem1Av >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem1Av between minValue and maxiValue and academicYear='$targetYear'");
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
                $output.='
                DATE OF ISSUE.______________.
              </div>
            </div>
          </div>
        </div>';
        $output.='<div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-6 col-6" id="ENS">
            <span class="">_____________________<br>REGISTRAR</span>  
          </div>
          <div class="col-md-5 col-6" id="ENS"> 
            <span class="">_____________________<br>ADMINISTRATOR</span>  
          </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="text-center">
         <span id="ENS">Note: THIS TRANSCRIPT IS INVALID IF ANY ALTERNATION OR ERASER COMMITS & UNLESS IT BEARS THE OFFICIAL SEAL OF THE SCHOOL.</span>
        </div>
        <br>'; 
      }
    }
    return $output;
  }
  function letterCustomTranscriptFairway($academicyear,$gradesec,$branch,$userName,$noGrade){
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
            $gradeSubject='11N';
            break;
          case '12S':
            $gradeSubject='11S';
            break;
          case '12NS':
            $gradeSubject='11NS';
            break;
          case '12SS':
            $gradeSubject='11SS';
            break;
          case '11NS':
            $gradeSubject='10';
            break;
          case '11SS':
            $gradeSubject='10';
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
    $phone=$row_name->phone;
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
        $queryKey=$this->db->query("select * from letterange where grade = '$currGrade' and academicYear='$academicyear' ");
        
        $output.='<div style="width:100%;height:92%;page-break-inside:avoid;">';
        $output.='<div class ="row" id="ENS">
        <div class="col-lg-12 col-12">
            <div class="media pb-1 mb-3 text-center card-header">
              <img src="'.base_url().'/logo/'.$schooLogo.'" style="width:110px;height: 110px;" class="user-img mr-2" alt="">
              <div class="media-body ml-3">
                <h2>'.$school_name.'</h2>
                <h3>KINDERGARTEN - COLLEGE PREPARATORY</h3>
                <i class="fas fa-phone"></i> '.$phone.'
                <small><i class="fas fa-globe"></i> '.$website.' | admin@fairwayschoolethiopia.com</small>';
              $output.='</div>
            </div>
          </div>
          <div class="col-lg-12 col-12 text-center">
            <h3><B>OFFICIAL SCHOOL TRANSCRIPT</B></h3>
          </div>
        </div>

        <div class ="row" id="ENS">
          <div class="col-lg-8 col-8">
            <div class="support-ticket media pb-1 mb-3">
              <img src="'.base_url().'/profile/'.$stuName->profile.'" style="width:90px;height: 110px;" class="user-img mr-2" alt="">
              <div class="media-body ml-3">
                <p class="my-1">NAME:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>'.$stuName->fname.' '.$stuName->mname.' '.$stuName->lname.'</u></p>
                <p class="my-1">GENDER: &nbsp;&nbsp;<u>'.$stuName->gender.' </u></p>
                <p class="my-1">Grade: &nbsp;&nbsp;&nbsp;&nbsp;<u>'.$stuName->gradesec.'</u></p>';
                 if($stuName->age>0){
                  $output.='<p class="my-1">Age: &nbsp;&nbsp;<u>'.$stuName->age.' </u></p>';
                }else{
                  $output.='<p class="my-1">Age: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_____</p>';
                }
              $output.='</div>
            </div>
          </div>
          <div class="col-lg-4 col-4">
          <div class="keyTextInfo">
          <div class="row">';
          foreach($queryKey->result() as $keyVal){
            $output.='<div class="col-lg-6 col-6">';
            $output.=''.$keyVal->minValue.'-'.$keyVal->maxiValue.'='.$keyVal->letterVal.'';
            $output.='</div>';
          }
          $output.='</div></div></div></div>';           
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
                case '11N':
                  $gradeName='ELEVEN';
                  break;
                case '11NS':
                  $gradeName='ELEVEN';
                  break;
                case '11SS':
                  $gradeName='ELEVEN';
                  break;
                case '11S':
                  $gradeName='ELEVEN';
                  break;
                case '12':
                  $gradeName='TWELVE';
                  break;
                case '12n':
                  $gradeName='TWELVE';
                  break;
                case '12s':
                  $gradeName='TWELVE';
                  break;
                case '12N':
                  $gradeName='TWELVE';
                  break;
                case '12S':
                  $gradeName='TWELVE';
                  break;
                case '12SS':
                  $gradeName='TWELVE';
                  break;
                case '12NS':
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
            $letter=$subjValue->letter;
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
                    $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and subject='$subjName' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and subject='$subjName'");
                    foreach ($quartrSem1Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
                      $value1st=number_format((float)$printValueSem1,0,'.','');
                      if($printValueSem1 >0){
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $value1st between minValue and maxiValue and academicYear='$targetYear'");
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
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and subject='$subjName' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and subject='$subjName'");
                    foreach ($quartrSem2Total->result() as $totalValueSem21) {
                      $printValueSem12=(($totalValueSem21->total)/2);
                      $value2nd=number_format((float)$printValueSem12,0,'.','');
                      if($printValueSem12 >0){
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $value2nd between minValue and maxiValue and academicYear='$targetYear'");
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
                        
                      $printValueSem1Ave=($totalValueSem1Ave->total)/4;
                      $valueAve=number_format((float)$printValueSem1Ave,0,'.','');
                      if($printValueSem1Ave >0){
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $valueAve between minValue and maxiValue and academicYear='$targetYear'");
                        if($queryRange->num_rows()>0){
                          foreach ($queryRange->result() as $letterValueAv) {
                            $letterValA=$letterValueAv->letterVal;
                            $output.= "<td class='text-center'>".$letterValA."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center'>-</td>";
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
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem1=(($totalValueSem1->total)/2);
                  if($printValueSem1 >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem1 between minValue and maxiValue and academicYear='$targetYear'");
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
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSem12=(($totalValueSem1->total)/2);
                  if($printValueSem12 >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem12 between minValue and maxiValue and academicYear='$targetYear'");
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
                foreach ($quartrSem2Total->result() as $totalValueSem1Av) {
                  $printValueSemAv=(($totalValueSem1Av->total)/4);
                  if($printValueSemAv >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSemAv between minValue and maxiValue and academicYear='$targetYear'");
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
          }
          $output.='<tr><td>AVERAGE</td>';
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
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSemAv=(($totalValueSem1->total)/2)/$subALast;
                  if($printValueSemAv >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSemAv between minValue and maxiValue and academicYear='$targetYear'");
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
                $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$newGradesec.$targetYear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                foreach ($quartrSem2Total->result() as $totalValueSem1) {
                  $printValueSemAv2=(($totalValueSem1->total)/2)/$subALast;
                  if($printValueSemAv2 >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSemAv2 between minValue and maxiValue and academicYear='$targetYear'");
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
                  $printValueSem1Av=(($totalValueSem1->total)/4)/$subALast;
                  if($printValueSem1Av >0){
                    $queryRange=$this->db->query("select letterVal from letterange where grade='$currGrade' and $printValueSem1Av between minValue and maxiValue and academicYear='$targetYear'");
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
                $output.='
                DATE OF ISSUE.______________.
              </div>
            </div>
          </div>
        </div>';
        $output.='<div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-6 col-6" id="ENS">
            <span class="">_____________________<br>REGISTRAR</span>  
          </div>
          <div class="col-md-5 col-6" id="ENS"> 
            <span class="">_____________________<br>ADMINISTRATOR</span>  
          </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="text-center">
         <span id="ENS">Note: THIS TRANSCRIPT IS INVALID IF ANY ALTERNATION OR ERASER COMMITS & UNLESS IT BEARS THE OFFICIAL SEAL OF THE SCHOOL.</span>
        </div>
        <br>'; 
      }
    }
    return $output;
  }   
  function transcript($academicyear,$gradesec,$branch,$noGrade)
  {
    $output='';
    $queryTr=$this->db->query("select username,profile,fname,mname,lname,gender,gradesec,age, unique_id, grade,id from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and status='Active' and isapproved='1' and academicyear='$academicyear' order by fname,mname,lname ASC ");
    if($queryTr->num_rows()>0){
      $rowcheckGrade=$queryTr->row();
      $cGrade=$rowcheckGrade->grade;
      if($cGrade=='KG1' || $cGrade=='KG2' || $cGrade=='KG3' || $cGrade=='Nursery' || $cGrade=='LKG' || $cGrade=='UKG'){
        $output .='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            <i class="fas fa-check-circle"> </i> Not supported grade.
        </div></div>';
      }else{
        $queryAGreYear=$this->db->query("select gyear from academicyear where year_name='$academicyear' ");
        $rowAG = $queryAGreYear->row();
        $Agmax_year=$rowAG->gyear;
        foreach ($queryTr->result() as $stuName) {
          $uniqueId=$stuName->unique_id;
          $currGrade=trim($stuName->grade);
          $currID=$stuName->id;
          $stuid=$stuName->id;
          $username=$stuName->username;
          switch ($currGrade) {
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
          $queryKey=$this->db->query("select * from letterange where grade = '$currGrade' and academicYear='$academicyear' ");
          $query_name = $this->db->query("select * from school");
          $row_name = $query_name->row();
          $school_name=$row_name->name;
          $schooLogo=$row_name->logo;
          $website=$row_name->website;
          $output.='<div style="width:100%;height:92%;page-break-inside:avoid;">';
          $output.='<div class ="row" id="ENS">
          <div class="col-lg-12 col-12">
              <div class="support-ticket media pb-1 mb-3 text-center card-header">
                <img src="'.base_url().'/logo/'.$schooLogo.'" style="width:90px;height: 110px;" class="user-img mr-2" alt="">
                <div class="media-body ml-3">
                  <p class="my-1"><h2>ZELEKE AND AMELEWORK ENTERPRISE P.L.C</h2></p>
                  <p class="my-1"><h3>'.$school_name.'</h3></p>
                  <p class="my-1"><h3>KINDERGARTEN - COLLEGE PREPARATORY</h3></p>
                  <p class="my-1"><i class="fas fa-fax"></i> 3059 | +251114342093 | +251118880251</p>
                  <p class="my-1"><small><i class="fas fa-globe"></i> '.$website.' | admin@ethionationalschool.com</small></p>';
                $output.='</div>
              </div>
            </div>
            <div class="col-lg-12 col-12 text-center">
              <h3><B>OFFICIAL SCHOOL TRANSCRIPT</B></h3>
            </div>
          </div>

          <div class ="row" id="ENS">
            <div class="col-lg-8 col-8">
              <div class="support-ticket media pb-1 mb-3">
                <img src="'.base_url().'/profile/'.$stuName->profile.'" style="width:90px;height: 110px;" class="user-img mr-2" alt="">
                <div class="media-body ml-3">
                  <p class="my-1">NAME:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>'.$stuName->fname.' '.$stuName->mname.' '.$stuName->lname.'</u></p>
                  <p class="my-1">GENDER: &nbsp;&nbsp;<u>'.$stuName->gender.' </u></p>
                  <p class="my-1">Grade: &nbsp;&nbsp;&nbsp;&nbsp;<u>'.$stuName->gradesec.'</u></p>';
                   if($stuName->age>0){
                    $output.='<p class="my-1">Age: &nbsp;&nbsp;<u>'.$stuName->age.' </u></p>';
                  }else{
                    $output.='<p class="my-1">Age: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_____</p>';
                  }
                $output.='</div>
              </div>
            </div>
            <div class="col-lg-4 col-4">
            <div class="keyTextInfo">
            <div class="row">';
             foreach($queryKey->result() as $keyVal){
              $output.='<div class="col-lg-6 col-6">';
              $output.=''.$keyVal->minValue.'-'.$keyVal->maxiValue.'='.$keyVal->letterVal.'';
              $output.='</div>';
            }
          $output.='</div></div></div></div>';           
          $output.=' <div class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">
              <tbody><tr> 
              <th class="text-center">ACADEMIC YEAR</th>';
              /*Even Grades calculation starts*/
              if($currGrade == trim('12n') || $currGrade == trim('12s') || $currGrade =='10' || $currGrade == trim('12') ){
                $targetYear=$academicyear - 1;
                $queryGreYear=$this->db->query("select gyear from academicyear where year_name='$targetYear' ");
                if($queryGreYear->num_rows()>0){
                  $rowG = $queryGreYear->row();
                  $gmax_year=$rowG->gyear;
                }else{
                  $gmax_year='-';
                }
                $output.='<th colspan="3" class="text-center">'.$gmax_year.' G.C | '.$targetYear.' E.C </th> 
                <th colspan="3" class="text-center">'.$Agmax_year.' G.C | '.$academicyear.' E.C </th></tr>
                <tr> 
                <th class="text-center">GRADE</th>';
                /*check if previous grade has found*/
                $queryLastGrade=$this->db->query("select * from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
                if($queryLastGrade->num_rows()>0){
                  $checkRow=$queryLastGrade->row();
                  $newGradesec=$checkRow->gradesec;
                  $queryCheckLast = $this->db->query("SHOW TABLES LIKE 'reportcard".$newGradesec.$targetYear."' ");
                  $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$academicyear."' ");
                  if ($queryCheck->num_rows()>0 && $queryCheckLast->num_rows()>0){
                    foreach ($queryLastGrade->result() as $LastGrade) {
                      $lastGr=$LastGrade->grade;
                      $lastGrsec=$LastGrade->gradesec;
                      $lastID=$LastGrade->id;
                      switch ($lastGr) {
                        case '5':
                          $laGradeName='FIVE';
                          break;
                        case '6':
                          $laGradeName='SIX';
                          break;
                        case '7':
                          $laGradeName='SEVEN';
                          break;
                        case '8':
                          $laGradeName='EIGHT';
                          break;
                        case '9':
                          $laGradeName='NINE';
                          break;
                        case '10':
                          $laGradeName='TEN';
                          break;
                        case '11':
                          $laGradeName='ELEVEN';
                          break;
                        case '11n':
                          $laGradeName='ELEVEN';
                          break;
                        case '11s':
                          $laGradeName='ELEVEN';
                          break;
                        case '12':
                          $laGradeName='TWELVE';
                          break;
                        case '12n':
                          $laGradeName='TWELVE';
                          break;
                        case '12s':
                          $laGradeName='TWELVE';
                          break;
                        default:
                          $laGradeName='-';
                          break;
                      }
                      $output.='<th colspan="3" class="text-center">'.$lastGrsec.'('.$laGradeName.')</th>
                      <th colspan="3" class="text-center">'.$gradesec.'('.$gradeName.')</th>
                      <tr><th rowspan="2" class="text-center">SUBJECT</th>
                      <th colspan="3" class="text-center">SEMESTER</th>
                      <th colspan="3" class="text-center">SEMESTER</th></tr>
                      <th class="text-center">I</th>
                      <th class="text-center">II</th>
                      <th class="text-center">AV</th>
                      <th class="text-center">I</th>
                      <th class="text-center">II</th>
                      <th class="text-center">AV</th></tr>';
                      /*last grade calcualtion starts  */
                      $querySubjecy=$this->db->query("select * from subject where Academic_Year='$academicyear' and Grade='$currGrade' and letter='#' and onreportcard='1' or Academic_Year='$targetYear' and Grade='$lastGr' and letter='#' and onreportcard='1' group by Subj_name order by suborder ");
                      if($querySubjecy->num_rows()>0){
                        $countSubjectLast=$this->db->query("select * from reportcard".$lastGrsec.$targetYear." where grade='$lastGrsec' and academicyear='$lastGrsec' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                        $subALast=$countSubjectLast->num_rows();
                        $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                        $subALl=$countSubject->num_rows();
                        foreach ($querySubjecy->result() as $subjValue) {
                          if($subjValue->Merged_name==''){
                            $subjName=$subjValue->Subj_name;
                          }else{
                            $subjName=$subjValue->Merged_name;
                          }
                          $output.='<tr><td>'.$subjName.'</td>';
                          /*Semester1*/
                          /*for selected odd grade*/
                          $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Quarter1' and onreportcard='1' and letter='#' and subject='$subjName' or stuid='$lastID' and quarter='Quarter2' and onreportcard='1' and letter='#' and subject='$subjName'");
                          foreach ($quartrSem1Total->result() as $totalValueSem1) {
                            $printValueSem1=(($totalValueSem1->total)/2);
                            if($printValueSem1 >0){
                              $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                            }else{
                              $output.='<td class="text-center">-</td>';
                            }
                          }
                          /*Semester2*/
                           
                          /*for selected odd grade*/
                          $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Quarter3' and onreportcard='1' and letter='#' and subject='$subjName' or stuid='$lastID' and quarter='Quarter4' and onreportcard='1' and letter='#' and subject='$subjName'");
                          foreach ($quartrSem2Total->result() as $totalValueSem1) {
                            $printValueSem1=(($totalValueSem1->total)/2);
                            if($printValueSem1 >0){
                              $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                            }else{
                              $output.='<td class="text-center">-</td>';
                            }
                          }

                          /*YearlyAverage*/
                          
                          $quartrSem2Totalver=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#' and subject='$subjName'  ");
                          foreach ($quartrSem2Totalver->result() as $totalValueSem1Ave) {
                            $printValueSem1Ave=(($totalValueSem1Ave->total)/2);
                            if($printValueSem1Ave >0){
                              $output .= '<td class="text-center">'.number_format((float)$printValueSem1Ave/2,2,'.','').'</td>';
                            }else{
                              $output.='<td class="text-center">-</td>';
                            }
                          }
                          /*for future even grade*/

                          $quartrSem1TotalEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' and subject='$subjName' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' and subject='$subjName'");
                          foreach ($quartrSem1TotalEven->result() as $totalValueSem1Even) {
                            $printValueSem1Even=(($totalValueSem1Even->total)/2);
                            if($printValueSem1Even >0){
                              $output .= '<td class="text-center">'.number_format((float)$printValueSem1Even,2,'.','').'</td>';
                            }else{
                              $output.='<td class="text-center">-</td>';
                            }
                          }
                          /*for future even grade*/
                          $quartrSem2TotalFuEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' and subject='$subjName' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' and subject='$subjName'");
                          foreach ($quartrSem2TotalFuEven->result() as $totalValueSem2FuEven) {
                            $totalValueSem2FuEven=(($totalValueSem2FuEven->total)/2);
                            if($totalValueSem2FuEven >0){
                              $output .= '<td class="text-center">'.number_format((float)$totalValueSem2FuEven,2,'.','').'</td>';
                            }else{
                              $output.='<td class="text-center">-</td>';
                            }
                          }
                          $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#' and subject='$subjName'  ");
                          foreach ($quartrSem2Total->result() as $totalValueSem1) {
                            $printValueSem1=(($totalValueSem1->total)/2);
                            if($printValueSem1 >0){
                              $output .= '<td class="text-center">'.number_format((float)$printValueSem1/2,2,'.','').'</td>';
                            }else{
                              $output.='<td class="text-center">-</td>';
                            }
                          }
                           
                        }
                        /*GrandTotal*/
                        $output.='<tr><td>GRAND TOTAL</td>';
                        
                        /*Grandtotal 4 1st Semester*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$lastID' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Grandtotal 4 2nd Semester*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$lastID' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Grandtotal 4 YearlyAverage Semester*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#'  ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/4);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                        foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                        foreach ($quartrSem2TotalFuGrade->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        $quartryearCurGradeTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                        foreach ($quartryearCurGradeTotal->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/4);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        $output.='<tr><td>AVERAGE</td>';

                        /*Grandtotal 4 1st Semester*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$lastID' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Grandtotal 4 2nd Semester*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$lastID' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Grandtotal 4 YearlyAverage Semester*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#'  ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/4);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                        foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                        foreach ($quartrSem2TotalFuGrade->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        $quartryearCurGradeTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                        foreach ($quartryearCurGradeTotal->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/4);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                      }
                    }
                  }
                }else{
                    $totalSubjects=0;
                    $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$academicyear."' ");
                    if($queryCheck->num_rows()>0){
                    $querySubjecy=$this->db->query("select * from reportcard".$gradesec.$academicyear." where academicyear='$academicyear' and grade='$gradesec' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    if($querySubjecy->num_rows()>0){
                      $totalSubjects=$querySubjecy->num_rows() + 3;
                    }
                    $output.='<th colspan="3" class="text-center"><b> - </b></th><th colspan="3" class="text-center"><b>'.$gradesec.'('.$gradeName.')</b></th>
                    <tr><th rowspan="2" class="text-center">SUBJECT</th>
                    <th colspan="3" class="text-center">SEMESTER</th>
                    <th colspan="3" class="text-center">SEMESTER</th></tr>
                    <th class="text-center" colspan="3" rowspan="'.$totalSubjects.'" style="background-color:#e3e3e3">
                     Data not found.<br> Please contact your <br> school administrator.
                    </th>
                    <th class="text-center">I</th>
                    <th class="text-center">II</th>
                    <th class="text-center">AV</th></tr>';                 
                    if($querySubjecy->num_rows()>0){
                      foreach ($querySubjecy->result() as $subjValue) {
                        $subjName=$subjValue->subject;
                        $output.='<tr><td>'.$subjValue->subject.'</td>';
                        /*Semester1*/
                        $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' and subject='$subjName' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' and subject='$subjName'");

                        foreach ($quartrSem1Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Semester2*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' and subject='$subjName' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' and subject='$subjName'");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*YearlyAverage*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2)/2;
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                      }
                      /*GrandTotal*/
                      $output.='<tr><td>GRAND TOTAL</td>';
                      $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                      $subALl=$countSubject->num_rows();
                      /*Grandtotal 4 1st Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/4);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }

                      /*GrandTotal*/
                      $output.='<tr><td>AVERAGE</td>';
                      $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                      $subALl=$countSubject->num_rows();
                      /*Grandtotal 4 1st Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/4);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                    }
                  }
                }
                /*........................................................................*/
              }else if($currGrade == trim('11n') || $currGrade == trim('11s') || $currGrade =='9' || $currGrade == '11'){
                /*Odd grade calculation starts*/
                $targetYear=$academicyear + 1;
                $queryGreYear=$this->db->query("select gyear from academicyear where year_name='$targetYear' ");
                if($queryGreYear->num_rows()>0){
                    $rowG = $queryGreYear->row();
                    $gmax_year=$rowG->gyear;
                }else{
                    $gmax_year='-';
                }                
                $queryAGreYear=$this->db->query("select gyear from academicyear where year_name='$academicyear' ");
                $rowAG = $queryAGreYear->row();
                $Agmax_year=$rowAG->gyear;
                $output.='<th colspan="3" class="text-center">'.$Agmax_year.' G.C | '.$academicyear.' E.C </th>
                <th colspan="3" class="text-center">'.$gmax_year.' G.C | '.$targetYear.' E.C</th> </tr>
                <tr> 
                <th class="text-center">GRADE</th>';
                $queryLastGrade=$this->db->query("select * from users where academicyear ='$targetYear' and unique_id='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
                if($queryLastGrade->num_rows()>0){
                  foreach ($queryLastGrade->result() as $LastGrade) {
                    $lastGr=$LastGrade->grade;
                    $lastGrsec=$LastGrade->gradesec;
                    $lastID=$LastGrade->id;
                    switch ($lastGr) {
                      case '5':
                        $laGradeName='FIVE';
                        break;
                      case '6':
                        $laGradeName='SIX';
                        break;
                      case '7':
                        $laGradeName='SEVEN';
                        break;
                      case '8':
                        $laGradeName='EIGHT';
                        break;
                      case '9':
                        $laGradeName='NINE';
                        break;
                      case '10':
                        $laGradeName='TEN';
                        break;
                      case '11':
                        $laGradeName='ELEVEN';
                        break;
                      case '11n':
                        $laGradeName='ELEVEN';
                        break;
                      case '11s':
                        $laGradeName='ELEVEN';
                        break;
                      case '12':
                        $laGradeName='TWELVE';
                        break;
                      case '12n':
                        $laGradeName='TWELVE';
                        break;
                      case '12s':
                        $laGradeName='TWELVE';
                        break;
                      default:
                        $laGradeName='-';
                        break;
                    }
                    $output.='<th colspan="3" class="text-center">'.$gradesec.'('.$gradeName.')</th>
                    <th colspan="3" class="text-center">'.$lastGrsec.'('.$laGradeName.')</th>
                    <tr><th rowspan="2" class="text-center">SUBJECT</th>
                    <th colspan="3" class="text-center">SEMESTER</th>
                    <th colspan="3" class="text-center">SEMESTER</th></tr>
                    <th class="text-center">I</th>
                    <th class="text-center">II</th>
                    <th class="text-center">AV</th>
                    <th class="text-center">I</th>
                    <th class="text-center">II</th>
                    <th class="text-center">AV</th></tr>';
                    $querySubjecy=$this->db->query("select * from subject where Academic_Year='$academicyear' and Grade='$currGrade' and letter='#' and onreportcard='1' or Academic_Year='$targetYear' and Grade='$lastGr' and letter='#' and onreportcard='1' group by Subj_name order by suborder ");
                    if($querySubjecy->num_rows()>0){
                      $countSubjectLast=$this->db->query("select * from reportcard".$lastGrsec.$targetYear." where grade='$lastGrsec' and academicyear='$targetYear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                      $subALast=$countSubjectLast->num_rows();
                      $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                      $subALl=$countSubject->num_rows();

                      foreach ($querySubjecy->result() as $subjValue) {
                        if($subjValue->Merged_name==''){
                          $subjName=$subjValue->Subj_name;
                        }else{
                          $subjName=$subjValue->Merged_name;
                        }
                        $output.='<tr><td>'.$subjName.'</td>';
                        /*Semester1*/
                        /*for future even grade*/
                        $quartrSem1TotalEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' and subject='$subjName' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' and subject='$subjName'");
                        foreach ($quartrSem1TotalEven->result() as $totalValueSem1Even) {
                          $printValueSem1Even=(($totalValueSem1Even->total)/2);
                          if($printValueSem1Even >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1Even,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*for future even grade*/
                        $quartrSem2TotalFuEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' and subject='$subjName' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' and subject='$subjName'");
                        foreach ($quartrSem2TotalFuEven->result() as $totalValueSem2FuEven) {
                          $totalValueSem2FuEven=(($totalValueSem2FuEven->total)/2);
                          if($totalValueSem2FuEven >0){
                            $output .= '<td class="text-center">'.number_format((float)$totalValueSem2FuEven,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#' and subject='$subjName'  ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1/2,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                         /*for selected odd grade*/
                        $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Quarter1' and onreportcard='1' and letter='#' and subject='$subjName' or stuid='$lastID' and quarter='Quarter2' and onreportcard='1' and letter='#' and subject='$subjName'");
                        foreach ($quartrSem1Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Semester2*/
                         
                        /*for selected odd grade*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Quarter3' and onreportcard='1' and letter='#' and subject='$subjName' or stuid='$lastID' and quarter='Quarter4' and onreportcard='1' and letter='#' and subject='$subjName'");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }

                        /*YearlyAverage*/
                        
                        $quartrSem2Totalver=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#' and subject='$subjName'  ");
                        foreach ($quartrSem2Totalver->result() as $totalValueSem1Ave) {
                          $printValueSem1Ave=(($totalValueSem1Ave->total)/2);
                          if($printValueSem1Ave >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1Ave/2,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                      }
                      /*GrandTotal*/
                      $output.='<tr><td>GRAND TOTAL</td>';
                     
                      /*Grandtotal 4 1st Semester*/
                      
                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2TotalFuGrade->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartryearCurGradeTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartryearCurGradeTotal->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/4);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$lastID' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$lastID' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/4);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }

                      $output.='<tr><td>AVERAGE</td>';
                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2TotalFuGrade->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartryearCurGradeTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartryearCurGradeTotal->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/4);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$lastID' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$lastID' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/4);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                    }
                  }
                }else{
                  /*calculating odd grades and next grade result is not exists*/
                  $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$academicyear."' ");
                  if($queryCheck->num_rows()>0){
                    $totalSubjects=0;
                    $querySubjecy=$this->db->query("select * from reportcard".$gradesec.$academicyear." where academicyear='$academicyear' and grade='$gradesec' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    if($querySubjecy->num_rows()>0){
                      $totalSubjects=$querySubjecy->num_rows() + 3;
                    }
                    $subALl=$querySubjecy->num_rows();
                    $output.='<th colspan="3" class="text-center">'.$gradesec.'('.$gradeName.')
                    </th><th colspan="3" class="text-center"><b> - </b></th>
                    <tr><th rowspan="2" class="text-center">SUBJECT</th>
                    <th colspan="3" class="text-center">SEMESTER</th>
                    <th colspan="3" class="text-center">SEMESTER</th></tr>
                    <th class="text-center">I</th>
                    <th class="text-center">II</th>
                    <th class="text-center">AV</th>
                    <th class="text-center" colspan="3" rowspan="'.$totalSubjects.'" style="background-color:#e3e3e3">
                     Data not found.<br> Please contact your <br> school administrator.
                    </th></tr>';
                    if($querySubjecy->num_rows()>0){
                      foreach ($querySubjecy->result() as $subjValue) {
                        $subjName=$subjValue->subject;
                        $output.='<tr><td>'.$subjValue->subject.'</td>';
                        /*Semester1*/
                        $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' and subject='$subjName' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' and subject='$subjName'");
                        foreach ($quartrSem1Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Semester2*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' and subject='$subjName' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' and subject='$subjName'");

                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*YearlyAverage*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=(($totalValueSem1->total)/2);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1/2,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                      }
                      /*GrandTotal*/
                      $output.='<tr><td>GRAND TOTAL</td>';
                      
                      $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and
                      rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                      $subALl=$countSubject->num_rows();
                      /*Grandtotal 4 1st Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/4);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $output.='<tr><td>AVERAGE</td>';
                      
                      /*Grandtotal 4 1st Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/4);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                    }
                  }
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
                  $output.='
                  DATE OF ISSUE.______________.
                </div>
              </div>
            </div>
          </div>';

          $output.='<div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-6 col-6" id="ENS">
              <span class="">_____________________<br>REGISTRAR</span>  
            </div>
            <div class="col-md-5 col-6" id="ENS"> 
              <span class="">_____________________<br>ADMINISTRATOR</span>  
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <div class="text-center">
           <span id="ENS">NOTE: THIS TRANSCRIPT IS INVALID IF ANY ALTERNATION OR ERASER COMMITS & UNLESS IT BEARS THE OFFICIAL SEAL OF THE SCHOOL.</span>
          </div>
          <br>';
        }
      }
    }
    return $output;
  }
  function reportcardFairway($max_year,$gradesec,$branch,$max_quarter,$includeBackPage){
    $output ='';
    $resultSem1=0;
    $resultSem2=0;
    $queryCHK=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
    if($queryCHK->num_rows()>0){
      $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade,gender,age,city,kebele, profile, section,gradesec,username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
      $stuAll=$queryStudent->num_rows();

     /* $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec,gender,age, kebele, city,profile, username from users where id='$id' ");
      
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
          $result1=0;$result3=0;
          $result2=0;$result4=0;$result2Afan=0;
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
          $kebele=$fetchStudent->kebele;
          $profile=$fetchStudent->profile;

          $output.= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; ">
          <div class="row" id="ENS">
          <div class="col-lg-6 col-md-6">';
          $output.='<div class="row">';
          $output.='<div class="col-lg-3"><b id="ENS">Grade: '.$fetchStudent->grade.'</b></div>';
          $output.='<div class="col-lg-3"><b id="ENS">Section: '.$fetchStudent->section.'</b></div> ';
          $output.='</div>';
          $output.='<div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
          $output.='<tr><th colspan="15" class="text-center">
          <h6 id="ENScool"><B id="ENS">'.$school_name.' '.$gYearName.' G.C ('.$max_year.' E.C) Student Report Card</B></h6>
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
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result1=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result1,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result1,2,'.','').'</td>';
                      }
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0 && $result1>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center' colspan='2'> -</td>";
                      }
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
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result2=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result2,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result2,2,'.','').'</td>';
                      }
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0 && $result2>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center' colspan='2'> -</td>";
                      }
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
                      if($subject=='Afan Oromo'){
                         $resultSem1=$fetchSem1->total;
                      }else{
                        $resultSem1=($fetchSem1->total)/2;   
                      }
                    
                    $firstSemCheck=$resultSem1;
                    if($fetchSem1->total=='' || $fetchSem1->total<=0){
                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                    }else{
                      if($letter!='A'){
                        if($resultSem1=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$resultSem1,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$resultSem1,2,'.','').'</td>';
                        }
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem1 between minValue and maxiValue and academicYear='$max_year'");
                        if($queryRange->num_rows()>0 && $resultSem1>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center'> -</td>";
                        }
                      }
                      else{
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem1 between minValue and maxiValue and academicYear='$max_year'");
                        if($queryRange->num_rows()>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center' colspan='2'> -</td>";
                        }
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
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($result3=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result3,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result3,2,'.','').'</td>';
                      }
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result3 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0 && $result3>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result3 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center' colspan='2'> -</td>";
                      }
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
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($result4=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result4,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result4,2,'.','').'</td>';
                      }
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result4 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0 && $result4>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result4 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center' colspan='2'> -</td>";
                      }
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
              if($result3<=0 || $result4<=0){
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }else{
                if($queryReportCardQ4->num_rows()>0){
                  $sem2Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter3') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter4') as total ");
                  foreach ($sem2Ave->result() as $fetchSem2) {
                    $resultSem2=($fetchSem2->total)/2;
                    if($fetchSem2->total=='' || $fetchSem2->total<=0){
                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';
                    }else{
                      if($letter!='A'){
                        if($resultSem2=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$resultSem2,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$resultSem2,2,'.','').'</td>';
                        }
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem2 between minValue and maxiValue and academicYear='$max_year'");
                        if($queryRange->num_rows()>0 && $resultSem2>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center'> -</td>";
                        }
                      }
                      else{
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem2 between minValue and maxiValue and academicYear='$max_year'");
                        if($queryRange->num_rows()>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center' colspan='2'> -</td>";
                        }
                      }
                    }
                    
                  }
                }else{
                  $output.='<td class="text-center">-</td>';
                  $output.='<td class="text-center">-</td>';
                }
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
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultYA between minValue and maxiValue and academicYear='$max_year'");
                          if($queryRange->num_rows()>0 && $resultYA>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center' >".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center'> -</td>";
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultYA between minValue and maxiValue and academicYear='$max_year'");
                          if($queryRange->num_rows()>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center' colspan='2'> -</td>";
                          }
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
          $quarterArrayQ=array('Quarter1','Quarter2');
            $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' group by bsname order by bsname ASC ");       
            foreach ($query_basicskill->result() as $bsvalue) {
              $bsname=$bsvalue->bsname;
              $output .='<tr><td><B>'.$bsvalue->bsname.'</B></td>';
              foreach ($quarterArrayQ as $qvalue) {
                $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                if($query_bsvalue->num_rows()>0) {
                  foreach ($query_bsvalue ->result() as $bsresult) {
                    $output .='<td class="text-center" colspan="2">'.$bsresult->value.'</td>';
                  }
                }else {
                  $output .='<td class="text-center" colspan="2">-</td>';
                }
              } 
              $quarterArrayQ3=array('Quarter3','Quarter4');
              $output.='<td class="text-center" colspan="2">-</td>';
              foreach ($quarterArrayQ3 as $qvalue) {
                $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                if($query_bsvalue->num_rows()>0) {
                  foreach ($query_bsvalue ->result() as $bsresult) {
                    $output .='<td class="text-center" colspan="2">'.$bsresult->value.'</td>';
                  }
                }else {
                  $output .='<td class="text-center" colspan="2">-</td>';
                }
              } 
              $output.='<td class="text-center" colspan="2">-</td>';
              $output.='<td class="text-center" colspan="2">-</td>';
              $output .='</tr>';
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
          /*if($result2<=0 || $result1<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
            $quartrSem1Total=$this->db->query("select subject, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' and subject!='Afan Oromo' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' and subject!='Afan Oromo' ");
             $queryReportCardQ222=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter2' and subject='Afan Oromo' order by subjorder ");
              if($queryReportCardQ222->num_rows()>0){
                foreach ($queryReportCardQ222->result() as $fetchResult2) {
                  $result2Afan=$fetchResult2->total;
                }
              }
            if($queryReportCardQ2->num_rows()>0){
              foreach ($quartrSem1Total->result() as $totalValueSem1) {
                $printValueSem1=(($totalValueSem1->total)/2) + $result2Afan;
                if($printValueSem1 >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          /*}*/

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
          /*if($result3<=0 || $result4<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
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
            /*}*/
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
          $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
          $subALl=$countSubject->num_rows();
          foreach ($quarterArray1 as $quarterValue) {
            if($subALl>0){
                if($quarterValue=='Quarter1'){
                    $subALl=$subALl-1;
                }else{
                     $subALl=$countSubject->num_rows();
                }
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
          $subALl=$countSubject->num_rows();
          /*1st Semester average starts*/
          /*if($result2<=0 || $result1<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' and subject!='Afan Oromo' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' and subject!='Afan Oromo' ");
            $queryReportCardQ222=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter2' and subject='Afan Oromo' order by subjorder ");
              if($queryReportCardQ222->num_rows()>0){
                foreach ($queryReportCardQ222->result() as $fetchResult2) {
                  $result2Afan=$fetchResult2->total;
                }
              }
              if($queryReportCardQ2->num_rows()>0){
                foreach ($quartrSem1Total->result() as $totalValueSem1) {
                  $printValueSem1=(($totalValueSem1->total)/2 + $result2Afan)/$subALl;
                  if($printValueSem1 >0){
                    $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center" colspan="2">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center" colspan="2">-</td>';
              }
           /* }*/
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
          /*if($result3<=0 || $result4<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
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
          /*}*/
            /*$output.='<td class="text-center" colspan="2">-</td>';*/
          /*Yearly Average Horizontal Average calculation starts*/
          if($queryReportCardQ4->num_rows()>0){
            $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
            if($quartrYATotal->num_rows()>0){
                /*if($resultSem2 ==0 || $resultSem1 == 0){
                  $output.='<td class="text-center" colspan="2">-</td>';
                }else{*/
                  foreach ($quartrYATotal->result() as $totalValueYA) {
                    $printValueYA=(($totalValueYA->total)/4)/$subALl;
                    if($printValueYA >0){
                      $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                    }else{
                      $output.='<td class="text-center" colspan="2">-</td>';
                    }
                  }
               /* }*/
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          $output.='</tr>';
          $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='rankname' and grade='$grade' and allowed='1' ");
          if($queryRankAllowed->num_rows()>0){
            $output.='<tr><td><b>Rank</b></td>';
            $quarterArray1=array('Quarter1','Quarter2');
            foreach ($quarterArray1 as $quarterValue) {
              $quarter=$quarterValue;
              $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid'
              and quarter='$quarter' group by quarter ");
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank 
              from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank
              from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' 
              and onreportcard='1' and rpbranch='$branch' group by grade ");
              if($query_total->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                    $output .= '<td class="text-center" colspan="2"><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center" colspan="2">-</td>';
              }
            }
            /*Semester1 Rank Total calculation starts*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
              if($queryReportCardQ2->num_rows()>0){
                $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter1' or grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter2' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter1' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' or stuid='$stuid' and quarter= 'Quarter2' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' group by grade ");
                foreach ($query_rank->result() as $rvalue) {
                    $output .= '<td class="text-center" colspan="2"><B>'.$rvalue->stuRank.'</B></td>';
                }
              }else{
                $output.='<td class="text-center" colspan="2">-</td>';
              }

            /*Quarter3 & Quarter4 Rank Total calculation starts*/
            $quarterArray2=array('Quarter3','Quarter4');
            foreach ($quarterArray2 as $quarterValuee) {
              $quarter=$quarterValuee;
              $query_total=$this->db->query("select * from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid' and quarter='$quarter' and rpbranch='$branch' ");

              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' and rpbranch='$branch' group by grade ");
              if($query_total->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                    $output .= '<td class="text-center" colspan="2"><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center" colspan="2">-</td>';
              }
            }

            /*Semester2 Horizontal Rank calculation starts*/
            $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1'
            and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
              if($queryReportCardQ4->num_rows()>0){
              
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter3' or grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter4' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter3' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' or stuid='$stuid' and quarter= 'Quarter4' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' group by grade ");
                foreach ($query_rank->result() as $rvalue) {
                    $output .= '<td class="text-center" colspan="2"><B>'.$rvalue->stuRank.'</B></td>';
                }
              }else{
                $output.='<td class="text-center" colspan="2">-</td>';
              }
            /*Yearly Rank Horizontal Rank calculation starts*/
            $query_rankya=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid'  and academicyear='$max_year' and grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by grade ");
            if($resultSem2 !==0 ){
              /*if($query_check_semster2_sub->num_rows()>0)
              {*/
                foreach ($query_rankya ->result() as $row_rankya)
                {
                  $rankNew =$row_rankya->stuRank;
                    $output .= '<td class="text-center" colspan="2"><B>'.$rankNew.'</B></td>';
                }
              /*}else{
                $output .= '<td class="text-center" colspan="2">-</td>';
              }*/
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
            $output.='</tr>';
          }
          /*student conduct calculation starts*/
          $output.='<tr><td><b>Tardiness</b></td>';
          /*1st and 2nd quarter conduct calculation starts*/
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
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
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

          /*1st Semester conduct*/
          /*3rd and 4th quarter conduct*/
          $tot1stSem=0;
          if($result2<=0 || $result1<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{
            if($queryReportCardQ2->num_rows()>0){
            foreach ($quarterArray1 as $qvalue) {
              $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
              $qRow=$queryTerm->row();
              $date1 =$qRow->startdate;
              $date2 =$qRow->endate;
              $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
              $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
              $startDate1= $changeDate1->format('Y-m-d');
              $endDate1= $changeDate2->format('Y-m-d');
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
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

          /*2nd Semester conduct*/
          $tot2stSem=0;
          if($result3<=0 || $result4<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{
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
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
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
          }

          /*yearly conduct*/
          if($queryReportCardQ4->num_rows()>0){
                if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center" colspan="2">-</td>';
               }else{
                $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and absentype='Late' ");
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
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and 
              attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
          /*if($result2<=0 || $result1<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
            if($queryReportCardQ2->num_rows()>0){
            foreach ($quarterArray1 as $qvalue) {
              $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
              $qRow=$queryTerm->row();
              $date1 =$qRow->startdate;
              $date2 =$qRow->endate;
              $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
              $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
              $startDate1= $changeDate1->format('Y-m-d');
              $endDate1= $changeDate2->format('Y-m-d');
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between 
              '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
          /*}*/
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between 
            '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
          /*if($result3<=0 || $result4<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
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
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between
              '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
          /*}*/
          /* Yearly absent days*/
          /*if($queryReportCardQ4->num_rows()>0){*/
               /* if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center" colspan="2">-</td>';
               }else{*/
                $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and
                absentype='Absent' ");
                if($queryTotalAbsent->num_rows() > 0){
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
               /*}*/
            
          /*}else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }*/
          $output.='</tr>';
          $output.="</table></div>
          <div class='row'>
            <div class ='col-md-6 col-6'>";
            $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join directorplacement as dp ON dp.staff=u.username where dp.grade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
             if($queryDirector->num_rows()>0){
              $rowSignD=$queryDirector->row();
              $signName=$rowSignD->fname;
              $signmame=$rowSignD->mname;
              $signlame=$rowSignD->lname;
              $signlame=$rowSignD->lname;
              $signSigns=$rowSignD->mysign;
              $output.="<p>Principal's Name<br><u>".$signName." ".$signmame."</u></p>
              <p>Signature  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'></p>";
            }else{
              $output.="<p>Principal's Name<br>______________________</p>
              <p>Signature____________</p>";
            }
            $output.="</div>
            <div class ='col-md-6 col-6'>";
            $querySign=$this->db->query("select fname,mname,lname ,mysign from users where usertype!='Student' and isapproved='1' and status='Active' and finalapproval='1' ");
            if($querySign->num_rows()>0){
              $rowSign=$querySign->row();
              $signName=$rowSign->fname;
              $signmame=$rowSign->mname;
              $signlame=$rowSign->lname;
              $signlame=$rowSign->lname;
              $signSign=$rowSign->mysign;
             $output.="<p>Managing Director's Name<br><u>".$signName." ".$signmame."</u></p>
              <p>Signature  <img alt='' src='".base_url()."/".$signSign."' class='' style='height:40px;width:120px'> </p>";
            }else{
              $output.="<p>Managing Director's Name<br>______________________</p>
              <p>Signature____________</p>";
            }
            $output.="</div>
            <div class='col-lg-12 text-center'>FINAL RESULT</div>
            <div class='col-lg-9'>
            <div class='col-lg-12'><i class='fas fa-check-square'></i> Promoted to Grade________</div>
            <div class='col-lg-12'><i class='fas fa-check-square'></i> Detained in Grade________</div>
            <div class='col-lg-12'><i class='fas fa-check-square'></i> Incomplete in Grade________</div>
            </div>
            <div class='col-lg-3'> SEAL </div>
          </div>
          </div>";/*result table closed*/

          $output.= '<div class="col-lg-6 col-md-6">';
          $output.='<div class="row">';
          $output.="<div class='col-lg-12'><b id='ENS'>Student's Name: ".ucfirst(strtolower($fetchStudent->fname))." ".ucfirst(strtolower($fetchStudent->mname))." ".ucfirst(strtolower($fetchStudent->lname))."</b></div>";

          $output.="<div class='col-lg-12 text-center'><u>ACADEMIC/BEHAVIORAL ASSESSMENT REPORT</u></div>";
          $output.="<div class='col-lg-12'><small> To Parents:This is a skill check list to inform you about your child's progress during the past quarter. Each skill is a goal that our school uses to promote intellectual, social, emotional and physical growth. This report would help you and the school to recognize how far your child has achieved satisfactory usage of different skills that are targeted in the academic year.</small> </div></div>";
          $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
          if($queryCategory->num_rows()>0){
            $output.= '<div class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $dateYear=date('Y');
            $output .='<tr><th colspan="5" class="text-center">'.$gYearName.' G.C ('.$max_year.' E.C) Basic Skills and Behaviour Progress Report</th></tr>';
            $output .='<tr><th>Evaluation Area</th>';
            $quarterArrayQ=array('Quarter1','Quarter2','Quarter3','Quarter4');
            foreach ($quarterArrayQ as $qvalue) 
            {
              $output .='<th class="text-center">'.$qvalue.'</th>';
            }
            foreach ($queryCategory->result() as $bscatvalue) {
              $bscategory=$bscatvalue->bscategory;
              $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='0' and bscategory='$bscategory' group by bsname order by bsname ASC ");       
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
          $output.='<div class="row"><div class="col-lg-12">';
          if($queryEvaKey->num_rows()>0){
            $output.= '<div id="ENS" class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $output .='<th class="text-center" colspan="2"><u>Grading System</u>:-</th>';
            foreach ($queryEvaKey->result() as $keyVValue) {
              $output .='<tr><td class="text-center">'.$keyVValue->bstype.'</td>';
              $output .='<td class="text-center">'.$keyVValue->bsdesc.'</td></tr>';
            }
            $output .='</table></div>';
          }else{
            $output .='No Evaluation Key found';
          }
          $output .='</div>';
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
                <div class="row">
                  <div class="col-lg-12 col-12">';
                  $output.='<p class="text-center"><img class="text-center" src="'.base_url().'/logo/'.$logo.'" style="height:150px;width:150px;border:1px solid #fff; -webkit-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;border-radius:10px" ></p>
                  </div>
                  <div class="col-lg-12 col-12">
                    <h2 class="text-center"><b>A VISION THAT WORKS FOR ALL OUR KIDS!</b></h2>
                    <p class="text-center"><i class="fas fa-phone-square"></i> +251911428176 / +251 11866 47 20 / 0118 111 472/53 <br> 
                      / +251 111 893 26 15/ +251 118 111 461/62 </p>
                      <p class="text-center">P.O.Box 3193</p>
                      <p class="text-center"><i class="fas fa-envelope"></i> yamsammar@gmail.com / '.$email.'</p>
                      <p class="text-center">Addis Ababa</p>
                      <p class="text-center"> Ethiopia</p>
                      <h2 class="text-center"><u><b>STUDENTS PROGRESS REPORT CARD</b></u></h2>
                    <div class="row">
                      <div class="col-lg-9 col-12">
                        <div class="row">
                          <div class="col-lg-12 col-12">
                            የተማሪዉ ስም
                            <p>Name of Student <u>'.ucfirst(strtolower($fname)).' '.ucfirst(strtolower($mname)).' '.ucfirst(strtolower($lname)).'</u></p>
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
                          <div class="col-lg-12 col-12">
                            የትምህርት ዘመን <p>Academic Year <u>'.$max_year.' </u></p>
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

                    <div class="col-lg-12 col-12">
                      <h6 class="text-center"><strong>EVERY STUDENT,EVERY CHANGE ,EVERY DAY AT FAIR WAY!</strong></h6>
                    </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-6">
                <h6 class="text-center"><strong><u>GRADING CRITERIA</u></strong></h6>
                <div class="StudentViewTextInfo">
                <div class="row">';
                  if($grade=='9' || $grade=='10' || $grade=='11S' || $grade=='11N' || $grade=='12S' || $grade=='12N'){
                      $output.='<div class="col-lg-4 col-6">
                        A+ = 100-91
                      </div>
                      <div class="col-lg-4 col-6">
                        A = 90-85
                      </div>
                      <div class="col-lg-4 col-6">
                        A- = 84-81
                      </div>
                       <div class="col-lg-4 col-6">
                        <b>Outstanding</b>
                      </div>
                       <div class="col-lg-4 col-6">
                        <b>Exceptional</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        <b>Excellent</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        B+ = 80-78
                      </div>
                      <div class="col-lg-4 col-6">
                        B = 77-74
                      </div>
                      <div class="col-lg-4 col-6">
                        B- = 73-70
                      </div>
                      <div class="col-lg-4 col-6">
                       <b>Very Good</b>
                      </div>
                       <div class="col-lg-4 col-6">
                        <b>Better</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        <b>Good</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        C+ = 69-65
                      </div>
                      <div class="col-lg-4 col-6">
                        C = 64-61
                      </div>
                      <div class="col-lg-4 col-6">
                        C- = 60-55
                      </div>
                     <div class="col-lg-4 col-6">
                        <b>Satisfactory</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        <b>Probation</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        <b>Needs Improvement</b>
                      </div>
                     <div class="col-lg-6 col-6">
                        D= 54-50
                      </div>
                      
                      <div class="col-lg-6 col-6">
                        F = <50
                      </div>
                      <div class="col-lg-6 col-6">
                        <b>Poor</b>
                      </div>
                      <div class="col-lg-6 col-6">
                        <b>Fail</b>
                      </div>';
                }else{
                    $output.='<div class="col-lg-4 col-6">
                        A+ = 100-95
                      </div>
                      <div class="col-lg-4 col-6">
                        B+ = 87-78
                      </div>
                      <div class="col-lg-4 col-6">
                        C+ = 74-70
                      </div>
                      <div class="col-lg-4 col-6">
                        A = 94-88
                      </div>
                      <div class="col-lg-4 col-6">
                        B = 77-75
                      </div>
                      <div class="col-lg-4 col-6">
                        C = 69-65
                      </div>
                      <div class="col-lg-4 col-6">
                        <b>Excellent</b>
                      </div>
                       <div class="col-lg-4 col-6">
                        <b>Very Good</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        <b>Satisfactory</b>
                      </div>
                      <div class="col-lg-6 col-6">
                        D= 64-55
                      </div>
                      <div class="col-lg-6 col-6">
                        F = <55
                      </div>
                      <div class="col-lg-6 col-6">
                        <b>Poor</b>
                      </div>
                      <div class="col-lg-6 col-6">
                        <b>Failing</b>
                      </div>';
                }
                $output.='</div>
                </div>
                  <h6 class="text-center"><b><u>Remarks</u></b></h6>
                  <div class="row">
                    <div class="col-lg-12 col-12 StudentViewTextInfo">';
                    $queryHoomRoom=$this->db->query("select u.fname,u.mname from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
                    if($queryHoomRoom->num_rows()>0){
                      $rowHommeRoom=$queryHoomRoom->row_array();
                      $tfName=strtoupper($rowHommeRoom['fname']);
                      $tmName=strtoupper($rowHommeRoom['mname']);
                    }else{
                      $tfName='------';
                      $tmName='------';
                    }
                    
                    $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    $subALl=$countSubject->num_rows();
                      if($subALl>0){
                        $output.='<b>1<sup>st</sup> Quarter Teachers Comment</b><br>';
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
                              $output.='__________________________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      }else{
                        $output.='No comment.';
                      }
                      $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp 
                      ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' 
                      and isapproved='1' and status='Active' ");
                     if($queryDirector->num_rows()>0){
                      $rowSignD=$queryDirector->row();
                      $signName=$rowSignD->fname;
                      $signmame=$rowSignD->mname;
                      $signlame=$rowSignD->lname;
                      $signlame=$rowSignD->lname;
                      $signSigns=$rowSignD->mysign;
                      $output.="<p>Teachers Name:<u><b>".$tfName." ".$tmName."</b></u>
                      Sig.  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date: December 7, 2023<br></p>";
                    }else{
                      $output.="<p>Teachers Name: <u><b>".$tfName." " .$tmName."</b></u> Sig._____ Date:_____<br>";
                    }
                    $output.='
                      Parents Comment ________________________________________________________________ <br>
                      Parents Name: <u><b>'.$mname.' '.$lname.' </b></u> Sig._____ Date:_____
                    </div>';

                    $output.='<div class="col-lg-12 col-12 StudentViewTextInfo">';
                    if($subALl>0){
                        $output.='<b>2<sup>nd</sup> Quarter Teachers Comment</b><br>';
                        $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
                        if($quartrTotal->num_rows()>0){
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue2=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue2 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                            if($printValue2 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='__________________________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      }else{
                        $output.='No comment.';
                      }
                      $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp 
                      ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' 
                      and isapproved='1' and status='Active' ");
                     if($queryDirector->num_rows()>0){
                      $rowSignD=$queryDirector->row();
                      $signName=$rowSignD->fname;
                      $signmame=$rowSignD->mname;
                      $signlame=$rowSignD->lname;
                      $signlame=$rowSignD->lname;
                      $signSigns=$rowSignD->mysign;
                      $output.="<p>Teachers Name:<u><b>".$tfName." ".$tmName."</b></u>
                      Sig.  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date:  February 23, 2023<br></p>";
                    }else{
                      $output.="<p>Teachers Name: <u><b>".$tfName." " .$tmName."</b></u> Sig._____ Date:_____<br>";
                    }
                    $output.='
                      Parents Comment ________________________________________________________________ <br>
                      Parents Name: <u><b>'.$mname.' '.$lname.' </b></u> Sig._____ Date:_____
                    </div>';

                    $output.='<div class="col-lg-12 col-12 StudentViewTextInfo">';
                    if($subALl>0){
                        $output.='<b>3<sup>rd</sup> Quarter Teachers Comment</b><br>';
                        $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' ");
                        if($quartrTotal->num_rows()>0){
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue3=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue3 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                            if($printValue3 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='__________________________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      }else{
                        $output.='No comment.';
                      }
                      $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp 
                      ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' 
                      and isapproved='1' and status='Active' ");
                     if($queryDirector->num_rows()>0){
                      $rowSignD=$queryDirector->row();
                      $signName=$rowSignD->fname;
                      $signmame=$rowSignD->mname;
                      $signlame=$rowSignD->lname;
                      $signlame=$rowSignD->lname;
                      $signSigns=$rowSignD->mysign;
                      $output.="<p>Teachers Name:<u><b>".$tfName." ".$tmName."</b></u>
                      Sig.  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date: May 1, 2023<br></p>";
                    }else{
                      $output.="<p>Teachers Name: <u><b>".$tfName." " .$tmName."</b></u> Sig._____ Date:_____<br>";
                    }
                    $output.='
                      Parents Comment ________________________________________________________________ <br>
                      Parents Name: <u><b>'.$mname.' '.$lname.' </b></u> Sig._____ Date:_____
                    </div>';

                    $output.='<div class="col-lg-12 col-12 StudentViewTextInfo">';
                      if($subALl>0){
                        $output.='<b>4<sup>th</sup> Quarter Teachers Comment</b><br>';
                        $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                        if($quartrTotal->num_rows()>0){
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue4=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue4 between mingradevalue and maxgradevalue and academicYear='$max_year'");
                            if($printValue4 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='__________________________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      }else{
                        $output.='No comment.';
                      }
                      $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp 
                      ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' 
                      and isapproved='1' and status='Active' ");
                     if($queryDirector->num_rows()>0){
                      $rowSignD=$queryDirector->row();
                      $signName=$rowSignD->fname;
                      $signmame=$rowSignD->mname;
                      $signlame=$rowSignD->lname;
                      $signlame=$rowSignD->lname;
                      $signSigns=$rowSignD->mysign;
                      $output.="<p>Teachers Name:<u><b>".$tfName." ".$tmName."</b></u>
                      Sig.  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date: July 9, 2023<br></p>";
                    }else{
                      $output.="<p>Teachers Name: <u><b>".$tfName." " .$tmName."</b></u> Sig._____ Date:_____<br>";
                    }
                    $output.='
                      Parents Comment ________________________________________________________________ <br>
                      Parents Name: <u><b>'.$mname.' '.$lname.' </b></u> Sig._____ Date:_____
                    </div>';

                    $output.='
                  </div>
                </div>
              </div>
            </div>';
            $printValue=0;
          }
        }
      } 
    }
    return $output;
  }
  function customReportCardFairway($max_year,$gradesec,$branch,$max_quarter,$id,$includeBackPage){
    $output ='';
    $resultSem1=0;
    $resultSem2=0;
    $queryCHK=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
    if($queryCHK->num_rows()>0){
      /*$queryStudent=$this->db->query(" Select fname,mname,lname,id,grade,gender,age,city,kebele, profile, section,gradesec,username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
      $stuAll=$queryStudent->num_rows();*/

      $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec,gender,age, kebele, city,profile, username from users where id='$id' ");
      
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
          $result1=0;$result3=0;
          $result2=0;$result4=0;$result2Afan=0;
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
          $kebele=$fetchStudent->kebele;
          $profile=$fetchStudent->profile;

          $output.= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; ">
          <div class="row" id="ENS">
          <div class="col-lg-6 col-md-6">';
          $output.='<div class="row">';
          $output.='<div class="col-lg-3"><b id="ENS">Grade: '.$fetchStudent->grade.'</b></div>';
          $output.='<div class="col-lg-3"><b id="ENS">Section: '.$fetchStudent->section.'</b></div> ';
          $output.='</div>';
          $output.='<div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
          $output.='<tr><th colspan="15" class="text-center">
          <h6 id="ENScool"><B id="ENS">'.$school_name.' '.$gYearName.' G.C ('.$max_year.' E.C) Student Report Card</B></h6>
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
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result1=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result1,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result1,2,'.','').'</td>';
                      }
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0 && $result1>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center' colspan='2'> -</td>";
                      }
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
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result2=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result2,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result2,2,'.','').'</td>';
                      }
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0 && $result2>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result2 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center' colspan='2'> -</td>";
                      }
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
                      if($subject=='Afan Oromo'){
                         $resultSem1=$fetchSem1->total;
                      }else{
                        $resultSem1=($fetchSem1->total)/2;   
                      }
                    
                    $firstSemCheck=$resultSem1;
                    if($fetchSem1->total=='' || $fetchSem1->total<=0){
                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                    }else{
                      if($letter!='A'){
                        if($resultSem1=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$resultSem1,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$resultSem1,2,'.','').'</td>';
                        }
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem1 between minValue and maxiValue and academicYear='$max_year'");
                        if($queryRange->num_rows()>0 && $resultSem1>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center'> -</td>";
                        }
                      }
                      else{
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem1 between minValue and maxiValue and academicYear='$max_year'");
                        if($queryRange->num_rows()>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center' colspan='2'> -</td>";
                        }
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
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($result3=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result3,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result3,2,'.','').'</td>';
                      }
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result3 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0 && $result3>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result3 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center' colspan='2'> -</td>";
                      }
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
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      if($result4=='100'){
                        $output .= '<td class="text-center">'.number_format((float)$result4,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center">'.number_format((float)$result4,2,'.','').'</td>';
                      }
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result4 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0 && $result4>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result4 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center' colspan='2'> -</td>";
                      }
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
              if($result3<=0 || $result4<=0){
                $output.='<td class="text-center">-</td>';
                $output.='<td class="text-center">-</td>';
              }else{
                if($queryReportCardQ4->num_rows()>0){
                  $sem2Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter3') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter4') as total ");
                  foreach ($sem2Ave->result() as $fetchSem2) {
                    $resultSem2=($fetchSem2->total)/2;
                    if($fetchSem2->total=='' || $fetchSem2->total<=0){
                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';
                    }else{
                      if($letter!='A'){
                        if($resultSem2=='100'){
                          $output .= '<td class="text-center">'.number_format((float)$resultSem2,0,'.','').'</td>';
                        }else{
                          $output .= '<td class="text-center">'.number_format((float)$resultSem2,2,'.','').'</td>';
                        }
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem2 between minValue and maxiValue and academicYear='$max_year'");
                        if($queryRange->num_rows()>0 && $resultSem2>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center'> -</td>";
                        }
                      }
                      else{
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultSem2 between minValue and maxiValue and academicYear='$max_year'");
                        if($queryRange->num_rows()>0){
                          foreach ($queryRange->result() as $letterValue) {
                            $letterVal=$letterValue->letterVal;
                            $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                          }
                        }else{
                          $output.= "<td class='text-center' colspan='2'> -</td>";
                        }
                      }
                    }
                    
                  }
                }else{
                  $output.='<td class="text-center">-</td>';
                  $output.='<td class="text-center">-</td>';
                }
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
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultYA between minValue and maxiValue and academicYear='$max_year'");
                          if($queryRange->num_rows()>0 && $resultYA>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center' >".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center'> -</td>";
                          }
                        }
                        else{
                          $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $resultYA between minValue and maxiValue and academicYear='$max_year'");
                          if($queryRange->num_rows()>0){
                            foreach ($queryRange->result() as $letterValue) {
                              $letterVal=$letterValue->letterVal;
                              $output.= "<td class='text-center' colspan='2'>".$letterVal."</td>";
                            }
                          }else{
                            $output.= "<td class='text-center' colspan='2'> -</td>";
                          }
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
          $quarterArrayQ=array('Quarter1','Quarter2');
            $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' group by bsname order by bsname ASC ");       
            foreach ($query_basicskill->result() as $bsvalue) {
              $bsname=$bsvalue->bsname;
              $output .='<tr><td><B>'.$bsvalue->bsname.'</B></td>';
              foreach ($quarterArrayQ as $qvalue) {
                $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                if($query_bsvalue->num_rows()>0) {
                  foreach ($query_bsvalue ->result() as $bsresult) {
                    $output .='<td class="text-center" colspan="2">'.$bsresult->value.'</td>';
                  }
                }else {
                  $output .='<td class="text-center" colspan="2">-</td>';
                }
              } 
              $quarterArrayQ3=array('Quarter3','Quarter4');
              $output.='<td class="text-center" colspan="2">-</td>';
              foreach ($quarterArrayQ3 as $qvalue) {
                $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                if($query_bsvalue->num_rows()>0) {
                  foreach ($query_bsvalue ->result() as $bsresult) {
                    $output .='<td class="text-center" colspan="2">'.$bsresult->value.'</td>';
                  }
                }else {
                  $output .='<td class="text-center" colspan="2">-</td>';
                }
              } 
              $output.='<td class="text-center" colspan="2">-</td>';
              $output.='<td class="text-center" colspan="2">-</td>';
              $output .='</tr>';
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
          /*if($result2<=0 || $result1<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
            $quartrSem1Total=$this->db->query("select subject, sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' and subject!='Afan Oromo' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' and subject!='Afan Oromo' ");
             $queryReportCardQ222=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter2' and subject='Afan Oromo' order by subjorder ");
              if($queryReportCardQ222->num_rows()>0){
                foreach ($queryReportCardQ222->result() as $fetchResult2) {
                  $result2Afan=$fetchResult2->total;
                }
              }
            if($queryReportCardQ2->num_rows()>0){
              foreach ($quartrSem1Total->result() as $totalValueSem1) {
                $printValueSem1=(($totalValueSem1->total)/2) + $result2Afan;
                if($printValueSem1 >0){
                  $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          /*}*/

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
          /*if($result3<=0 || $result4<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
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
            /*}*/
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
          $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
          $subALl=$countSubject->num_rows();
          foreach ($quarterArray1 as $quarterValue) {
            if($subALl>0){
                if($quarterValue=='Quarter1'){
                    $subALl=$subALl-1;
                }else{
                     $subALl=$countSubject->num_rows();
                }
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
          $subALl=$countSubject->num_rows();
          /*1st Semester average starts*/
          /*if($result2<=0 || $result1<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' and subject!='Afan Oromo' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' and subject!='Afan Oromo' ");
            $queryReportCardQ222=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter2' and subject='Afan Oromo' order by subjorder ");
              if($queryReportCardQ222->num_rows()>0){
                foreach ($queryReportCardQ222->result() as $fetchResult2) {
                  $result2Afan=$fetchResult2->total;
                }
              }
              if($queryReportCardQ2->num_rows()>0){
                foreach ($quartrSem1Total->result() as $totalValueSem1) {
                  $printValueSem1=(($totalValueSem1->total)/2 + $result2Afan)/$subALl;
                  if($printValueSem1 >0){
                    $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center" colspan="2">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center" colspan="2">-</td>';
              }
           /* }*/
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
          /*if($result3<=0 || $result4<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
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
          /*}*/
            /*$output.='<td class="text-center" colspan="2">-</td>';*/
          /*Yearly Average Horizontal Average calculation starts*/
          if($queryReportCardQ4->num_rows()>0){
            $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
            if($quartrYATotal->num_rows()>0){
                /*if($resultSem2 ==0 || $resultSem1 == 0){
                  $output.='<td class="text-center" colspan="2">-</td>';
                }else{*/
                  foreach ($quartrYATotal->result() as $totalValueYA) {
                    $printValueYA=(($totalValueYA->total)/4)/$subALl;
                    if($printValueYA >0){
                      $output .= '<td class="text-center" colspan="2"><b>'.number_format((float)$printValueYA,2,'.','').'</b></td>';
                    }else{
                      $output.='<td class="text-center" colspan="2">-</td>';
                    }
                  }
               /* }*/
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          $output.='</tr>';
          $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='rankname' and grade='$grade' and allowed='1' ");
          if($queryRankAllowed->num_rows()>0){
            $output.='<tr><td><b>Rank</b></td>';
            $quarterArray1=array('Quarter1','Quarter2');
            foreach ($quarterArray1 as $quarterValue) {
              $quarter=$quarterValue;
              $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid'
              and quarter='$quarter' group by quarter ");
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank 
              from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank
              from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' 
              and onreportcard='1' and rpbranch='$branch' group by grade ");
              if($query_total->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                    $output .= '<td class="text-center" colspan="2"><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center" colspan="2">-</td>';
              }
            }
            /*Semester1 Rank Total calculation starts*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
              if($queryReportCardQ2->num_rows()>0){
                $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter1' or grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter2' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter1' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' or stuid='$stuid' and quarter= 'Quarter2' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' group by grade ");
                foreach ($query_rank->result() as $rvalue) {
                    $output .= '<td class="text-center" colspan="2"><B>'.$rvalue->stuRank.'</B></td>';
                }
              }else{
                $output.='<td class="text-center" colspan="2">-</td>';
              }

            /*Quarter3 & Quarter4 Rank Total calculation starts*/
            $quarterArray2=array('Quarter3','Quarter4');
            foreach ($quarterArray2 as $quarterValuee) {
              $quarter=$quarterValuee;
              $query_total=$this->db->query("select * from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid' and quarter='$quarter' and rpbranch='$branch' ");

              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' and rpbranch='$branch' group by grade ");
              if($query_total->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                    $output .= '<td class="text-center" colspan="2"><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center" colspan="2">-</td>';
              }
            }

            /*Semester2 Horizontal Rank calculation starts*/
            $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1'
            and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
              if($queryReportCardQ4->num_rows()>0){
              
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter3' or grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter4' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter3' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' or stuid='$stuid' and quarter= 'Quarter4' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' group by grade ");
                foreach ($query_rank->result() as $rvalue) {
                    $output .= '<td class="text-center" colspan="2"><B>'.$rvalue->stuRank.'</B></td>';
                }
              }else{
                $output.='<td class="text-center" colspan="2">-</td>';
              }
            /*Yearly Rank Horizontal Rank calculation starts*/
            $query_rankya=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid'  and academicyear='$max_year' and grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by grade ");
            if($resultSem2 !==0 ){
              /*if($query_check_semster2_sub->num_rows()>0)
              {*/
                foreach ($query_rankya ->result() as $row_rankya)
                {
                  $rankNew =$row_rankya->stuRank;
                    $output .= '<td class="text-center" colspan="2"><B>'.$rankNew.'</B></td>';
                }
              /*}else{
                $output .= '<td class="text-center" colspan="2">-</td>';
              }*/
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
            $output.='</tr>';
          }
          /*student conduct calculation starts*/
          $output.='<tr><td><b>Tardiness</b></td>';
          /*1st and 2nd quarter conduct calculation starts*/
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
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
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

          /*1st Semester conduct*/
          /*3rd and 4th quarter conduct*/
          $tot1stSem=0;
          if($result2<=0 || $result1<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{
            if($queryReportCardQ2->num_rows()>0){
            foreach ($quarterArray1 as $qvalue) {
              $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
              $qRow=$queryTerm->row();
              $date1 =$qRow->startdate;
              $date2 =$qRow->endate;
              $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
              $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
              $startDate1= $changeDate1->format('Y-m-d');
              $endDate1= $changeDate2->format('Y-m-d');
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
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

          /*2nd Semester conduct*/
          $tot2stSem=0;
          if($result3<=0 || $result4<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{
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
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
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
          }

          /*yearly conduct*/
          if($queryReportCardQ4->num_rows()>0){
                if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center" colspan="2">-</td>';
               }else{
                $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and absentype='Late' ");
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
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and 
              attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
          /*if($result2<=0 || $result1<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
            if($queryReportCardQ2->num_rows()>0){
            foreach ($quarterArray1 as $qvalue) {
              $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
              $qRow=$queryTerm->row();
              $date1 =$qRow->startdate;
              $date2 =$qRow->endate;
              $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
              $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
              $startDate1= $changeDate1->format('Y-m-d');
              $endDate1= $changeDate2->format('Y-m-d');
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between 
              '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
          /*}*/
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between 
            '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
          /*if($result3<=0 || $result4<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
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
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between
              '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
          /*}*/
          /* Yearly absent days*/
          /*if($queryReportCardQ4->num_rows()>0){*/
               /* if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center" colspan="2">-</td>';
               }else{*/
                $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and
                absentype='Absent' ");
                if($queryTotalAbsent->num_rows() > 0){
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
               /*}*/
            
          /*}else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }*/
          $output.='</tr>';
          $output.="</table></div>
          <div class='row'>
            <div class ='col-md-6 col-6'>";
            $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join directorplacement as dp ON dp.staff=u.username where dp.grade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
             if($queryDirector->num_rows()>0){
              $rowSignD=$queryDirector->row();
              $signName=$rowSignD->fname;
              $signmame=$rowSignD->mname;
              $signlame=$rowSignD->lname;
              $signlame=$rowSignD->lname;
              $signSigns=$rowSignD->mysign;
              $output.="<p>Principal's Name<br><u>".$signName." ".$signmame."</u></p>
              <p>Signature  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'></p>";
            }else{
              $output.="<p>Principal's Name<br>______________________</p>
              <p>Signature____________</p>";
            }
            $output.="</div>
            <div class ='col-md-6 col-6'>";
            $querySign=$this->db->query("select fname,mname,lname ,mysign from users where usertype!='Student' and isapproved='1' and status='Active' and finalapproval='1' ");
            if($querySign->num_rows()>0){
              $rowSign=$querySign->row();
              $signName=$rowSign->fname;
              $signmame=$rowSign->mname;
              $signlame=$rowSign->lname;
              $signlame=$rowSign->lname;
              $signSign=$rowSign->mysign;
             $output.="<p>Managing Director's Name<br><u>".$signName." ".$signmame."</u></p>
              <p>Signature  <img alt='' src='".base_url()."/".$signSign."' class='' style='height:40px;width:120px'> </p>";
            }else{
              $output.="<p>Managing Director's Name<br>______________________</p>
              <p>Signature____________</p>";
            }
            $output.="</div>
            <div class='col-lg-12 text-center'>FINAL RESULT</div>
            <div class='col-lg-9'>
            <div class='col-lg-12'><i class='fas fa-check-square'></i> Promoted to Grade________</div>
            <div class='col-lg-12'><i class='fas fa-check-square'></i> Detained in Grade________</div>
            <div class='col-lg-12'><i class='fas fa-check-square'></i> Incomplete in Grade________</div>
            </div>
            <div class='col-lg-3'> SEAL </div>
          </div>
          </div>";/*result table closed*/

          $output.= '<div class="col-lg-6 col-md-6">';
          $output.='<div class="row">';
          $output.="<div class='col-lg-12'><b id='ENS'>Student's Name: ".ucfirst(strtolower($fetchStudent->fname))." ".ucfirst(strtolower($fetchStudent->mname))." ".ucfirst(strtolower($fetchStudent->lname))."</b></div>";

          $output.="<div class='col-lg-12 text-center'><u>ACADEMIC/BEHAVIORAL ASSESSMENT REPORT</u></div>";
          $output.="<div class='col-lg-12'><small> To Parents:This is a skill check list to inform you about your child's progress during the past quarter. Each skill is a goal that our school uses to promote intellectual, social, emotional and physical growth. This report would help you and the school to recognize how far your child has achieved satisfactory usage of different skills that are targeted in the academic year.</small> </div></div>";
          $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
          if($queryCategory->num_rows()>0){
            $output.= '<div class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $dateYear=date('Y');
            $output .='<tr><th colspan="5" class="text-center">'.$gYearName.' G.C ('.$max_year.' E.C) Basic Skills and Behaviour Progress Report</th></tr>';
            $output .='<tr><th>Evaluation Area</th>';
            $quarterArrayQ=array('Quarter1','Quarter2','Quarter3','Quarter4');
            foreach ($quarterArrayQ as $qvalue) 
            {
              $output .='<th class="text-center">'.$qvalue.'</th>';
            }
            foreach ($queryCategory->result() as $bscatvalue) {
              $bscategory=$bscatvalue->bscategory;
              $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='0' and bscategory='$bscategory' group by bsname order by bsname ASC ");       
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
          $output.='<div class="row"><div class="col-lg-12">';
          if($queryEvaKey->num_rows()>0){
            $output.= '<div id="ENS" class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $output .='<th class="text-center" colspan="2"><u>Grading System</u>:-</th>';
            foreach ($queryEvaKey->result() as $keyVValue) {
              $output .='<tr><td class="text-center">'.$keyVValue->bstype.'</td>';
              $output .='<td class="text-center">'.$keyVValue->bsdesc.'</td></tr>';
            }
            $output .='</table></div>';
          }else{
            $output .='No Evaluation Key found';
          }
          $output .='</div>';
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
                <div class="row">
                  <div class="col-lg-12 col-12">';
                  $output.='<p class="text-center"><img class="text-center" src="'.base_url().'/logo/'.$logo.'" style="height:150px;width:150px;border:1px solid #fff; -webkit-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;border-radius:10px" ></p>
                  </div>
                  <div class="col-lg-12 col-12">
                    <h2 class="text-center"><b>A VISION THAT WORKS FOR ALL OUR KIDS!</b></h2>
                    <p class="text-center"><i class="fas fa-phone-square"></i> +251911428176 / +251 11866 47 20 / 0118 111 472/53 <br> 
                      / +251 111 893 26 15/ +251 118 111 461/62 </p>
                      <p class="text-center">P.O.Box 3193</p>
                      <p class="text-center"><i class="fas fa-envelope"></i> yamsammar@gmail.com / '.$email.'</p>
                      <p class="text-center">Addis Ababa</p>
                      <p class="text-center"> Ethiopia</p>
                      <h2 class="text-center"><u><b>STUDENTS PROGRESS REPORT CARD</b></u></h2>
                    <div class="row">
                      <div class="col-lg-9 col-12">
                        <div class="row">
                          <div class="col-lg-12 col-12">
                            የተማሪዉ ስም
                            <p>Name of Student <u>'.ucfirst(strtolower($fname)).' '.ucfirst(strtolower($mname)).' '.ucfirst(strtolower($lname)).'</u></p>
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
                          <div class="col-lg-12 col-12">
                            የትምህርት ዘመን <p>Academic Year <u>'.$max_year.' </u></p>
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

                    <div class="col-lg-12 col-12">
                      <h6 class="text-center"><strong>EVERY STUDENT,EVERY CHANGE ,EVERY DAY AT FAIR WAY!</strong></h6>
                    </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-6">
                <h6 class="text-center"><strong><u>GRADING CRITERIA</u></strong></h6>
                <div class="StudentViewTextInfo">
                <div class="row">';
                  if($grade=='9' || $grade=='10' || $grade=='11S' || $grade=='11N' || $grade=='12S' || $grade=='12N'){
                      $output.='<div class="col-lg-4 col-6">
                        A+ = 100-91
                      </div>
                      <div class="col-lg-4 col-6">
                        A = 90-85
                      </div>
                      <div class="col-lg-4 col-6">
                        A- = 84-81
                      </div>
                       <div class="col-lg-4 col-6">
                        <b>Outstanding</b>
                      </div>
                       <div class="col-lg-4 col-6">
                        <b>Exceptional</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        <b>Excellent</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        B+ = 80-78
                      </div>
                      <div class="col-lg-4 col-6">
                        B = 77-74
                      </div>
                      <div class="col-lg-4 col-6">
                        B- = 73-70
                      </div>
                      <div class="col-lg-4 col-6">
                       <b>Very Good</b>
                      </div>
                       <div class="col-lg-4 col-6">
                        <b>Better</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        <b>Good</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        C+ = 69-65
                      </div>
                      <div class="col-lg-4 col-6">
                        C = 64-61
                      </div>
                      <div class="col-lg-4 col-6">
                        C- = 60-55
                      </div>
                     <div class="col-lg-4 col-6">
                        <b>Satisfactory</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        <b>Probation</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        <b>Needs Improvement</b>
                      </div>
                     <div class="col-lg-6 col-6">
                        D= 54-50
                      </div>
                      
                      <div class="col-lg-6 col-6">
                        F = <50
                      </div>
                      <div class="col-lg-6 col-6">
                        <b>Poor</b>
                      </div>
                      <div class="col-lg-6 col-6">
                        <b>Fail</b>
                      </div>';
                }else{
                    $output.='<div class="col-lg-4 col-6">
                        A+ = 100-95
                      </div>
                      <div class="col-lg-4 col-6">
                        B+ = 87-78
                      </div>
                      <div class="col-lg-4 col-6">
                        C+ = 74-70
                      </div>
                      <div class="col-lg-4 col-6">
                        A = 94-88
                      </div>
                      <div class="col-lg-4 col-6">
                        B = 77-75
                      </div>
                      <div class="col-lg-4 col-6">
                        C = 69-65
                      </div>
                      <div class="col-lg-4 col-6">
                        <b>Excellent</b>
                      </div>
                       <div class="col-lg-4 col-6">
                        <b>Very Good</b>
                      </div>
                      <div class="col-lg-4 col-6">
                        <b>Satisfactory</b>
                      </div>
                      <div class="col-lg-6 col-6">
                        D= 64-55
                      </div>
                      <div class="col-lg-6 col-6">
                        F = <55
                      </div>
                      <div class="col-lg-6 col-6">
                        <b>Poor</b>
                      </div>
                      <div class="col-lg-6 col-6">
                        <b>Failing</b>
                      </div>';
                }
                $output.='</div>
                </div>
                  <h6 class="text-center"><b><u>Remarks</u></b></h6>
                  <div class="row">
                    <div class="col-lg-12 col-12 StudentViewTextInfo">';
                    $queryHoomRoom=$this->db->query("select u.fname,u.mname from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
                    if($queryHoomRoom->num_rows()>0){
                      $rowHommeRoom=$queryHoomRoom->row_array();
                      $tfName=strtoupper($rowHommeRoom['fname']);
                      $tmName=strtoupper($rowHommeRoom['mname']);
                    }else{
                      $tfName='------';
                      $tmName='------';
                    }
                    
                    $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    $subALl=$countSubject->num_rows();
                      if($subALl>0){
                        $output.='<b>1<sup>st</sup> Quarter Teachers Comment</b><br>';
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
                              $output.='__________________________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      }else{
                        $output.='No comment.';
                      }
                      $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp 
                      ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' 
                      and isapproved='1' and status='Active' ");
                     if($queryDirector->num_rows()>0){
                      $rowSignD=$queryDirector->row();
                      $signName=$rowSignD->fname;
                      $signmame=$rowSignD->mname;
                      $signlame=$rowSignD->lname;
                      $signlame=$rowSignD->lname;
                      $signSigns=$rowSignD->mysign;
                      $output.="<p>Teachers Name:<u><b>".$tfName." ".$tmName."</b></u>
                      Sig.  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date: December 7, 2023<br></p>";
                    }else{
                      $output.="<p>Teachers Name: <u><b>".$tfName." " .$tmName."</b></u> Sig._____ Date:_____<br>";
                    }
                    $output.='
                      Parents Comment ________________________________________________________________ <br>
                      Parents Name: <u><b>'.$mname.' '.$lname.' </b></u> Sig._____ Date:_____
                    </div>';

                    $output.='<div class="col-lg-12 col-12 StudentViewTextInfo">';
                    if($subALl>0){
                        $output.='<b>2<sup>nd</sup> Quarter Teachers Comment</b><br>';
                        $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
                        if($quartrTotal->num_rows()>0){
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue2=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue2 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                            if($printValue2 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='__________________________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      }else{
                        $output.='No comment.';
                      }
                      $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp 
                      ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' 
                      and isapproved='1' and status='Active' ");
                     if($queryDirector->num_rows()>0){
                      $rowSignD=$queryDirector->row();
                      $signName=$rowSignD->fname;
                      $signmame=$rowSignD->mname;
                      $signlame=$rowSignD->lname;
                      $signlame=$rowSignD->lname;
                      $signSigns=$rowSignD->mysign;
                      $output.="<p>Teachers Name:<u><b>".$tfName." ".$tmName."</b></u>
                      Sig.  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date:  February 23, 2023<br></p>";
                    }else{
                      $output.="<p>Teachers Name: <u><b>".$tfName." " .$tmName."</b></u> Sig._____ Date:_____<br>";
                    }
                    $output.='
                      Parents Comment ________________________________________________________________ <br>
                      Parents Name: <u><b>'.$mname.' '.$lname.' </b></u> Sig._____ Date:_____
                    </div>';

                    $output.='<div class="col-lg-12 col-12 StudentViewTextInfo">';
                    if($subALl>0){
                        $output.='<b>3<sup>rd</sup> Quarter Teachers Comment</b><br>';
                        $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' ");
                        if($quartrTotal->num_rows()>0){
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue3=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue3 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                            if($printValue3 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='__________________________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      }else{
                        $output.='No comment.';
                      }
                      $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp 
                      ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' 
                      and isapproved='1' and status='Active' ");
                     if($queryDirector->num_rows()>0){
                      $rowSignD=$queryDirector->row();
                      $signName=$rowSignD->fname;
                      $signmame=$rowSignD->mname;
                      $signlame=$rowSignD->lname;
                      $signlame=$rowSignD->lname;
                      $signSigns=$rowSignD->mysign;
                      $output.="<p>Teachers Name:<u><b>".$tfName." ".$tmName."</b></u>
                      Sig.  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date: May 1, 2023<br></p>";
                    }else{
                      $output.="<p>Teachers Name: <u><b>".$tfName." " .$tmName."</b></u> Sig._____ Date:_____<br>";
                    }
                    $output.='
                      Parents Comment ________________________________________________________________ <br>
                      Parents Name: <u><b>'.$mname.' '.$lname.' </b></u> Sig._____ Date:_____
                    </div>';

                    $output.='<div class="col-lg-12 col-12 StudentViewTextInfo">';
                      if($subALl>0){
                        $output.='<b>4<sup>th</sup> Quarter Teachers Comment</b><br>';
                        $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
                        if($quartrTotal->num_rows()>0){
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue4=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue4 between mingradevalue and maxgradevalue and academicYear='$max_year'");
                            if($printValue4 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='__________________________________________________________ <br>';
                            }
                          }
                        }else{
                          $output.='No comment.';
                        }
                      }else{
                        $output.='No comment.';
                      }
                      $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp 
                      ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' 
                      and isapproved='1' and status='Active' ");
                     if($queryDirector->num_rows()>0){
                      $rowSignD=$queryDirector->row();
                      $signName=$rowSignD->fname;
                      $signmame=$rowSignD->mname;
                      $signlame=$rowSignD->lname;
                      $signlame=$rowSignD->lname;
                      $signSigns=$rowSignD->mysign;
                      $output.="<p>Teachers Name:<u><b>".$tfName." ".$tmName."</b></u>
                      Sig.  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date: July 9, 2023<br></p>";
                    }else{
                      $output.="<p>Teachers Name: <u><b>".$tfName." " .$tmName."</b></u> Sig._____ Date:_____<br>";
                    }
                    $output.='
                      Parents Comment ________________________________________________________________ <br>
                      Parents Name: <u><b>'.$mname.' '.$lname.' </b></u> Sig._____ Date:_____
                    </div>';

                    $output.='
                  </div>
                </div>
              </div>
            </div>';
            $printValue=0;
          }
        }
      } 
    }
    return $output;
  }   
  function filterGradesecfromBranch($academicyear){
    $this->db->where(array('academicyear'=>$academicyear));
    $this->db->order_by('name','ASC');
    $this->db->group_by('name');
    $query=$this->db->get('branch');
    $output ='';
    $output.='<option>--Select Branch--</option>';
    foreach ($query->result() as $row) { 
      $output .='<option value="'.$row->name.'">'.$row->name.'</option>';
    }
      return $output;
  }
  function reportcardHalfQuarter($max_year,$gradesec,$branch,$max_quarter,$includeStudentBasicSkill){
    $output ='';$resultSem1=0;
    $resultSem2=0;
    $queryCHK=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
    if($max_quarter=='Quarter1'){
      $quarterName ='1<sup>st</sup> Quarter';
    }else if($max_quarter=='Quarter2'){
      $quarterName='2<sup>nd</sup> Quarter';
    }else if($max_quarter=='Quarter3'){
      $quarterName='3<sup>rd</sup> Quarter';
    }else if($max_quarter=='Quarter4'){
      $quarterName='4<sup>th</sup> Quarter';
    }else if($max_quarter=='Semester1'){
      $quarterName='1<sup>st</sup> Semester';
    }else if($max_quarter=='Semester2'){
      $quarterName='2<sup>nd</sup> Semester';
    }else if($max_quarter=='semester1'){
       $quarterName='1<sup>st</sup> Semester';
    }else if($max_quarter=='semester2'){
      $quarterName='2<sup>nd</sup> Semester';
    }else if($max_quarter=='Term1'){
      $quarterName='1<sup>st</sup> Term';
    }else if($max_quarter=='Term2'){
      $quarterName='2<sup>nd</sup> Term';
    }else if($max_quarter=='Term3'){
      $quarterName='3<sup>rd</sup> Term';
    }
    if($queryCHK->num_rows()>0){
      $queryStudent=$this->db->query("select fname,mname,lname,id,grade, section,gradesec,username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
      $stuAll=$queryStudent->num_rows();

      /*$queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, username from users where id='$id' ");
      $queryStudentNum=$this->db->query("select fname,mname,lname,id,grade, section,gradesec,username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC");
      $stuAll=$queryStudentNum->num_rows();*/
      if($queryStudent->num_rows()>0){
        $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $slogan=$row_name->slogan;
        $logo=$row_name->logo;
        $querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowGyear = $querySlogan->row();
        $gYearName=$rowGyear->gyear;
        $dateYear=date('Y');
        foreach ($queryStudent->result() as $fetchStudent)
        {
          $printValue=0;
          $grade=$fetchStudent->grade;
          $stuid=$fetchStudent->id;
          $username1=$fetchStudent->username;
          $output.= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; "> 
          <div class="support-ticket media pb-1 mb-3 card-header">
          <img src="'.base_url().'/logo/'.$logo.'" style="width:auto;height: 110px;" class="user-img mr-2" alt="">
          <div class="media-body ml-3">
            <span class="font-weight-bold"><h2 id="ENScool"><B id="ENS">'.$school_name.' </B></h2></span>
            <p class="my-1"> <h4 id="ENScool"><B id="ENS"><u>'.$gYearName.' G.C '.$quarterName.' Student Progress Report </u></B></h4></p>
          </div>
        </div> ';
          $output.='<div class="row">';
          $output.="<div class='col-lg-6 col-6'><b id='ENS'>Student's Name: ".$fetchStudent->fname." ".$fetchStudent->mname." ".$fetchStudent->lname."</b></div>";
          $output.='<div class="col-lg-3 col-3"><b id="ENS">Grade: '.$fetchStudent->grade.'</b></div>';
          $output.='<div class="col-lg-3 col-3"><b id="ENS">Section: '.$fetchStudent->section.'</b></div></div> ';
          $output.='<div class="row" id="ENS">
          <div class="col-lg-6 col-md-6">';
          $output.='<div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-bordered table-md" cellspacing="9" cellpadding="9">';
          $output.='
          <tr><th>Subject</th>
          <td class="text-center"><b>Result (100%)</b></td><td class="text-center"><b>Rank</b></td></tr>';
          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr><td style="white-space: nowrap"><B>'.$fetchSubject->subject.'</B></td>';
              $queryReportCardQ1=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='$max_quarter' and 
              subject='$subject' and onreportcard='1' group by subject order by subjorder ");
              if($queryReportCardQ1->num_rows()>0){
                foreach ($queryReportCardQ1->result() as $fetchResult1) {
                  $result1=$fetchResult1->total;
                  if($fetchResult1->total =='' || $fetchResult1->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      /*$evalname_query=$this->db->query("select ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev where ev.grade='$grade' and ev.academicyear='$max_year' and ev.quarter='$max_quarter' group by ev.evname order by ev.eid ASC");
                      $totalInserted=0;
                      foreach ($evalname_query->result_array() as $evalua_name) {
                        $mname_gs=$evalua_name['eid'];
                        $percent=$evalua_name['percent'];
                        $evName=$evalua_name['evname'];
                        $queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$subject' and customasses='$evName' and customquarter='$max_quarter' ");
                        if($queryCheckPercentage->num_rows()>0){
                          $rowPercent=$queryCheckPercentage->row();
                          $wightPercent=$rowPercent->custompercent;
                        }else{
                          $wightPercent=$evalua_name['percent'];
                        }  
                        $query_value = $this->db->query("select markname,sum(value) as total from mark".$branch.$gradesec.$max_quarter.$max_year." where  subname='$subject' and quarter='$max_quarter' and evaid='$mname_gs' and mbranch='$branch' group by markname order by mid ASC"); 
                        if($query_value->num_rows()>0){
                          $totalInserted=$totalInserted+ $wightPercent;
                        }else{

                        }         
                      }*/
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
                  $queryQ1SubRank1=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from 
                  reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and subject='$subject' and quarter='$max_quarter' group by stuid) sm)) as stuRank1 from 
                  reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and rpbranch='$branch' and quarter='$max_quarter' and subject='$subject' group by subject ");
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
            }
            /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
            $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='totalname' and grade='$grade' and allowed='1' ");
            if($queryRankAllowed->num_rows()>0){
              $output.='<tr><td><b>Total</b></td>';
              $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$max_quarter' and onreportcard='1' and letter='#' ");
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
              $output.='</tr>';
            }
            /*Horizontal Average calculation starts*/
            $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='averagename' and grade='$grade' and allowed='1' ");
            if($queryRankAllowed->num_rows()>0){
              $output.='<tr><td><b>Average</b></td>';
              /*1st and snd quarter calculation starts*/
              $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
              $subALl=$countSubject->num_rows();
              if($subALl>0){
                $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$max_quarter' and onreportcard='1' and letter='#' ");
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
              $output.='</tr>';
            }
            $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='rankname' and grade='$grade' and allowed='1' ");
            if($queryRankAllowed->num_rows()>0){
              $output.='<tr><td><b>Rank</b></td>';
              $query_total=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` 
              from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$max_quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank
              from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$max_quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' and rpbranch='$branch' group by grade ");
              if($query_total->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                  $output .= '<td class="text-center" colspan="2"><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center" colspan="2">-</td>';
              }
              $output.='</tr>';
            }
            /*student conduct calculation starts*/
            $output.='<tr><td><b>Conduct</b></td>';
            /*1st and 2nd quarter conduct calculation starts*/
            $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$max_quarter' and bsname='Conduct' group by stuid ");
            if($eachQuarterBasicskill->num_rows()>0){
              $valueBS=$eachQuarterBasicskill->row();
              $getValue=$valueBS->value;
              $output.='<td class="text-center" colspan="2">'.$getValue.'</td>';
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
            $output.='</tr>';
            /*Absetn days calculation starts*/
            $output.='<tr><td><b style="white-space: nowrap">Absence Days</b></td>';
            /*1st and 2nd quarter absence days*/
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$max_quarter' ");
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
            $output.='</tr>';
            /*Number of student calculation starts*/
            $output.='<tr><td><b style="white-space: nowrap">No. of Students</b></td>';
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
            $output.='</tr>';
            $output.='</table></div>';
            if($includeStudentBasicSkill=='1'){
              $output.="<h6 class='text-success'><b><u>Homeroom Teacher's Comments & Recommendations </u></b></h6>";
              $queryHoomRoom=$this->db->query("select u.fname,u.mname,mysign from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
              if($queryHoomRoom->num_rows()>0){
                $rowHommeRoom=$queryHoomRoom->row_array();
                $tfName=$rowHommeRoom['fname'];
                $tmName=$rowHommeRoom['mname'];
                $signSigns=$rowHommeRoom['mysign'];
              }else{
                $tfName='------';
                $tmName='------';
                $signSigns='_____';
              }
              $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join directorplacement as dp ON dp.staff=u.username where dp.grade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
               if($queryDirector->num_rows()>0){
                $rowSignD=$queryDirector->row();
                $signName=$rowSignD->fname;
                $signmame=$rowSignD->mname;
                $signlame=$rowSignD->lname;
                $signlame=$rowSignD->lname;
                $signSignsD=$rowSignD->mysign;
              }else{
                $signName='------';
                $signmame='------';
                $signSignsD='_____';
              }
              $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and '$printValue' between mingradevalue and maxgradevalue and academicYear='$max_year'");
              if($printValue >0 && $reportCardComments->num_rows()>0){
                foreach($reportCardComments->result() as $commentValue){
                  $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                }
              }else{
                $output.=' ______________________________________ ____________________________________________  ____________________________________________ <br>';
              }                           
              $output.="<p>HRT's Name:- <u><b>".$tfName." ".$tmName."</b></u> Signature.<img alt='.' src='".base_url()."/".$signSigns."' style='height:40px;width:120px'></p>
              <p>Director's Name:-<u><b>".$signName." ".$signmame."</b></u>
                Signature  <img alt='.' src='".base_url()."/".$signSignsD."' class='' style='height:40px;width:120px'></p>";
            }
            $output.='</div>';/*result table closed*/
            if($includeStudentBasicSkill=='1'){
              $output.= '<div class="col-lg-6 col-md-6">';
              $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
              if($queryCategory->num_rows()>0){
                $output.= '<div class="table-responsive">
                <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
                $dateYear=date('Y');
                $output .='<tr><th colspan="5" class="text-center">'.$gYearName.' G.C '.$max_year.' E.C Basic Skills and Behaviour Progress Report</th></tr>';
                $output .='<tr><th>Evaluation Area</th>';
                  $output .='<th class="text-center">'.$quarterName.'</th>';
                foreach ($queryCategory->result() as $bscatvalue) {
                  $bscategory=$bscatvalue->bscategory;
                  $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and bsname!='Conduct' and bscategory='$bscategory' order by bsname ASC ");       
                  $output .='<tr><th colspan="5" id="BGS" class="text-center">'.$bscategory.'</th>';
                  foreach ($query_basicskill->result() as $bsvalue) {
                    $bsname=$bsvalue->bsname;
                    $output .='<tr><td>'.$bsvalue->bsname.'</td>';
                    $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$max_quarter' ");
                    if($query_bsvalue->num_rows()>0) {
                      foreach ($query_bsvalue ->result() as $bsresult) {
                        $output .='<td class="text-center">'.$bsresult->value.'</td>';
                      }
                    }else {
                      $output .='<td class="text-center">-</td>';
                    }
                    $output .='</tr>';
                  }
                }
                $output .='</table></div><br>';/*basic skill table closed*/
                $queryEvaKey=$this->db->query("select * from bstype where academicyear='$max_year' and btgrade='$grade' ");
                $output.='<div class="row"><div class="col-lg-6 col-6">';
                if($queryEvaKey->num_rows()>0){
                  $output.= '<div id="ENS" class="table-responsive">
                  <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
                  $output .='<th class="text-center" colspan="2"><u>Grading System</u>:-</th>';
                  foreach ($queryEvaKey->result() as $keyVValue) {
                    $output .='<tr><td class="text-center">'.$keyVValue->bstype.'</td>';
                    $output .='<td class="text-center">'.$keyVValue->bsdesc.'</td></tr>';
                  }
                  $output .='</table></div>';
                }else{
                  $output .='No Evaluation Key found';
                }
                $output .='</div><div class="col-lg-6 col-6">';
                $queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade' order by leId DESC ");
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
                $output .='</div></div>';

                $output .='</div><br>';/*basic skill column closed*/
              }
              $output .='</div>';
              $output .='</div><br>';/*basic skill column closed*/
            }else{
              $output.="<div class='col-lg-6 col-6'><h6 class='text-success'><b><u>Homeroom Teacher's Comments & Recommendations </u></b></h6>";
              $queryHoomRoom=$this->db->query("select u.fname,u.mname,mysign from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
              if($queryHoomRoom->num_rows()>0){
                $rowHommeRoom=$queryHoomRoom->row_array();
                $tfName=$rowHommeRoom['fname'];
                $tmName=$rowHommeRoom['mname'];
                $signSigns=$rowHommeRoom['mysign'];
              }else{
                $tfName='------';
                $tmName='------';
                $signSigns='_____';
              }
              $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join directorplacement as dp ON dp.staff=u.username where dp.grade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
               if($queryDirector->num_rows()>0){
                $rowSignD=$queryDirector->row();
                $signName=$rowSignD->fname;
                $signmame=$rowSignD->mname;
                $signlame=$rowSignD->lname;
                $signlame=$rowSignD->lname;
                $signSignsD=$rowSignD->mysign;
              }else{
                $signName='------';
                $signmame='------';
                $signSignsD='_____';
              }
              $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and '$printValue' between mingradevalue and maxgradevalue and academicYear='$max_year'");
              if($printValue >0 && $reportCardComments->num_rows()>0){
                foreach($reportCardComments->result() as $commentValue){
                  $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                }
              }else{
                $output.=' ______________________________________ ____________________________________________  ____________________________________________ <br>';
              }                           
              $output.="<p>HRT's Name:- <u><b>".$tfName." ".$tmName."</b></u> Signature.<img alt='.' src='".base_url()."/".$signSigns."' style='height:40px;width:120px'></p>
              <p>Director's Name:-<u><b>".$signName." ".$signmame."</b></u>
                Signature  <img alt='.' src='".base_url()."/".$signSignsD."' class='' style='height:40px;width:120px'></p>";
             /*class row closed*/ 
                $queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade' order by leId DESC ");
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
                 $output.='</div>';
                $output .='</div>';
                $output .='</div><br>';/*basic skill column closed*/
            }
            $output.='<h6 class="text-center"><strong>'.$slogan.'</strong></h6>';
            $output.='<h7 class="pull-right">ይህ ካርድ ጊዜያዊ ዉጤት ማሳወቂያ ሲሆን እንደ ትምህርት ማስረጃ አያገለግልም::</h7><br>';
          }
          $output .='</div>';
        } 
      } 
    }
    return $output;
  }
  function fetchCustomStudentHalfReport($reportaca,$gradesec,$branch,$rpQuarter){
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
      <td><button class="btn btn-info printThisStudentHalfReport" name="'.$id.'" value="'.$rpQuarter.'" id="'.$reportaca.'"><i class="fas fa-print"></i></button></td> </tr>';
      $no++;
    }
    return $output;
  }
  function reportcardCustomHalfQuarter($max_year,$gradesec,$branch,$max_quarter,$id,$includeStudentBasicSkill){
    $output ='';$resultSem1=0;
    $resultSem2=0;
    $queryCHK=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
    if($max_quarter=='Quarter1'){
      $quarterName ='1<sup>st</sup> Quarter';
    }else if($max_quarter=='Quarter2'){
      $quarterName='2<sup>nd</sup> Quarter';
    }else if($max_quarter=='Quarter3'){
      $quarterName='3<sup>rd</sup> Quarter';
    }else if($max_quarter=='Quarter4'){
      $quarterName='4<sup>th</sup> Quarter';
    }else if($max_quarter=='Semester1'){
      $quarterName='1<sup>st</sup> Semester';
    }else if($max_quarter=='Semester2'){
      $quarterName='2<sup>nd</sup> Semester';
    }else if($max_quarter=='semester1'){
       $quarterName='1<sup>st</sup> Semester';
    }else if($max_quarter=='semester2'){
      $quarterName='2<sup>nd</sup> Semester';
    }else if($max_quarter=='Term1'){
      $quarterName='1<sup>st</sup> Term';
    }else if($max_quarter=='Term2'){
      $quarterName='2<sup>nd</sup> Term';
    }else if($max_quarter=='Term3'){
      $quarterName='3<sup>rd</sup> Term';
    }
    if($queryCHK->num_rows()>0){
      /*$queryStudent=$this->db->query("select fname,mname,lname,id,grade, section,gradesec,username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
      $stuAll=$queryStudent->num_rows();*/

      $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, username from users where id='$id' ");
      $queryStudentNum=$this->db->query("select fname,mname,lname,id,grade, section,gradesec,username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC");
      $stuAll=$queryStudentNum->num_rows();
      if($queryStudent->num_rows()>0){
        $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $slogan=$row_name->slogan;
        $logo=$row_name->logo;
        $querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowGyear = $querySlogan->row();
        $gYearName=$rowGyear->gyear;
        $dateYear=date('Y');
        foreach ($queryStudent->result() as $fetchStudent)
        {
          $printValue=0;
          $grade=$fetchStudent->grade;
          $stuid=$fetchStudent->id;
          $username1=$fetchStudent->username;
          $output.= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; "> 
          <div class="support-ticket media pb-1 mb-3 card-header">
          <img src="'.base_url().'/logo/'.$logo.'" style="width:auto;height: 110px;" class="user-img mr-2" alt="">
          <div class="media-body ml-3">
            <span class="font-weight-bold"><h2 id="ENScool"><B id="ENS">'.$school_name.' </B></h2></span>
            <p class="my-1"> <h4 id="ENScool"><B id="ENS"><u>'.$gYearName.' G.C '.$quarterName.' Student Progress Report </u></B></h4></p>
          </div>
        </div> ';
          $output.='<div class="row">';
          $output.="<div class='col-lg-6 col-6'><b id='ENS'>Student's Name: ".$fetchStudent->fname." ".$fetchStudent->mname." ".$fetchStudent->lname."</b></div>";
          $output.='<div class="col-lg-3 col-3"><b id="ENS">Grade: '.$fetchStudent->grade.'</b></div>';
          $output.='<div class="col-lg-3 col-3"><b id="ENS">Section: '.$fetchStudent->section.'</b></div></div> ';
          $output.='<div class="row" id="ENS">
          <div class="col-lg-6 col-md-6">';
          $output.='<div class="table-responsive">
          <table width="100%" id="ENS" class="tabler table-bordered table-md" cellspacing="9" cellpadding="9">';
          $output.='
          <tr><th>Subject</th>
          <td class="text-center"><b>Result (100%)</b></td><td class="text-center"><b>Rank</b></td></tr>';
          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr><td style="white-space: nowrap"><B>'.$fetchSubject->subject.'</B></td>';
              $queryReportCardQ1=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='$max_quarter' and 
              subject='$subject' and onreportcard='1' group by subject order by subjorder ");
              if($queryReportCardQ1->num_rows()>0){
                foreach ($queryReportCardQ1->result() as $fetchResult1) {
                  $result1=$fetchResult1->total;
                  if($fetchResult1->total =='' || $fetchResult1->total<=0){
                    $output.='<td class="text-center">-</td>';
                  }else{
                    if($letter!='A'){
                      /*$evalname_query=$this->db->query("select ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev where ev.grade='$grade' and ev.academicyear='$max_year' and ev.quarter='$max_quarter' group by ev.evname order by ev.eid ASC");
                      $totalInserted=0;
                      foreach ($evalname_query->result_array() as $evalua_name) {
                        $mname_gs=$evalua_name['eid'];
                        $percent=$evalua_name['percent'];
                        $evName=$evalua_name['evname'];
                        $queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$subject' and customasses='$evName' and customquarter='$max_quarter' ");
                        if($queryCheckPercentage->num_rows()>0){
                          $rowPercent=$queryCheckPercentage->row();
                          $wightPercent=$rowPercent->custompercent;
                        }else{
                          $wightPercent=$evalua_name['percent'];
                        }  
                        $query_value = $this->db->query("select markname,sum(value) as total from mark".$branch.$gradesec.$max_quarter.$max_year." where  subname='$subject' and quarter='$max_quarter' and evaid='$mname_gs' and mbranch='$branch' group by markname order by mid ASC"); 
                        if($query_value->num_rows()>0){
                          $totalInserted=$totalInserted+ $wightPercent;
                        }else{

                        }         
                      }*/
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
                  $queryQ1SubRank1=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from 
                  reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and subject='$subject' and quarter='$max_quarter' group by stuid) sm)) as stuRank1 from 
                  reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and rpbranch='$branch' and quarter='$max_quarter' and subject='$subject' group by subject ");
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
            }
            /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
            $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='totalname' and grade='$grade' and allowed='1' ");
            if($queryRankAllowed->num_rows()>0){
              $output.='<tr><td><b>Total</b></td>';
              $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$max_quarter' and onreportcard='1' and letter='#' ");
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
              $output.='</tr>';
            }
            /*Horizontal Average calculation starts*/
            $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='averagename' and grade='$grade' and allowed='1' ");
            if($queryRankAllowed->num_rows()>0){
              $output.='<tr><td><b>Average</b></td>';
              /*1st and snd quarter calculation starts*/
              $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
              $subALl=$countSubject->num_rows();
              if($subALl>0){
                $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$max_quarter' and onreportcard='1' and letter='#' ");
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
              $output.='</tr>';
            }
            $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='rankname' and grade='$grade' and allowed='1' ");
            if($queryRankAllowed->num_rows()>0){
              $output.='<tr><td><b>Rank</b></td>';
              $query_total=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` 
              from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$max_quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank
              from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$max_quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' and rpbranch='$branch' group by grade ");
              if($query_total->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                  $output .= '<td class="text-center" colspan="2"><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center" colspan="2">-</td>';
              }
              $output.='</tr>';
            }
            /*student conduct calculation starts*/
            $output.='<tr><td><b>Conduct</b></td>';
            /*1st and 2nd quarter conduct calculation starts*/
            $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$max_quarter' and bsname='Conduct' group by stuid ");
            if($eachQuarterBasicskill->num_rows()>0){
              $valueBS=$eachQuarterBasicskill->row();
              $getValue=$valueBS->value;
              $output.='<td class="text-center" colspan="2">'.$getValue.'</td>';
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
            $output.='</tr>';
            /*Absetn days calculation starts*/
            $output.='<tr><td><b style="white-space: nowrap">Absence Days</b></td>';
            /*1st and 2nd quarter absence days*/
            $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$max_quarter' ");
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
            $output.='</tr>';
            /*Number of student calculation starts*/
            $output.='<tr><td><b style="white-space: nowrap">No. of Students</b></td>';
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
            $output.='</tr>';
            $output.='</table></div>';
            if($includeStudentBasicSkill=='1'){
              $output.="<h6 class='text-success'><b><u>Homeroom Teacher's Comments & Recommendations </u></b></h6>";
              $queryHoomRoom=$this->db->query("select u.fname,u.mname,mysign from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
              if($queryHoomRoom->num_rows()>0){
                $rowHommeRoom=$queryHoomRoom->row_array();
                $tfName=$rowHommeRoom['fname'];
                $tmName=$rowHommeRoom['mname'];
                $signSigns=$rowHommeRoom['mysign'];
              }else{
                $tfName='------';
                $tmName='------';
                $signSigns='_____';
              }
              $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join directorplacement as dp ON dp.staff=u.username where dp.grade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
               if($queryDirector->num_rows()>0){
                $rowSignD=$queryDirector->row();
                $signName=$rowSignD->fname;
                $signmame=$rowSignD->mname;
                $signlame=$rowSignD->lname;
                $signlame=$rowSignD->lname;
                $signSignsD=$rowSignD->mysign;
              }else{
                $signName='------';
                $signmame='------';
                $signSignsD='_____';
              }
              $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and '$printValue' between mingradevalue and maxgradevalue and academicYear='$max_year'");
              if($printValue >0 && $reportCardComments->num_rows()>0){
                foreach($reportCardComments->result() as $commentValue){
                  $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                }
              }else{
                $output.=' ______________________________________ ____________________________________________  ____________________________________________ <br>';
              }                           
              $output.="<p>HRT's Name:- <u><b>".$tfName." ".$tmName."</b></u> Signature.<img alt='.' src='".base_url()."/".$signSigns."' style='height:40px;width:120px'></p>
              <p>Director's Name:-<u><b>".$signName." ".$signmame."</b></u>
                Signature  <img alt='.' src='".base_url()."/".$signSignsD."' class='' style='height:40px;width:120px'></p>";
            }
            $output.='</div>';/*result table closed*/
            if($includeStudentBasicSkill=='1'){
              $output.= '<div class="col-lg-6 col-md-6">';
              $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
              if($queryCategory->num_rows()>0){
                $output.= '<div class="table-responsive">
                <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
                $dateYear=date('Y');
                $output .='<tr><th colspan="5" class="text-center">'.$gYearName.' G.C '.$max_year.' E.C Basic Skills and Behaviour Progress Report</th></tr>';
                $output .='<tr><th>Evaluation Area</th>';
                  $output .='<th class="text-center">'.$quarterName.'</th>';
                foreach ($queryCategory->result() as $bscatvalue) {
                  $bscategory=$bscatvalue->bscategory;
                  $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and bsname!='Conduct' and bscategory='$bscategory' order by bsname ASC ");       
                  $output .='<tr><th colspan="5" id="BGS" class="text-center">'.$bscategory.'</th>';
                  foreach ($query_basicskill->result() as $bsvalue) {
                    $bsname=$bsvalue->bsname;
                    $output .='<tr><td>'.$bsvalue->bsname.'</td>';
                    $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$max_quarter' ");
                    if($query_bsvalue->num_rows()>0) {
                      foreach ($query_bsvalue ->result() as $bsresult) {
                        $output .='<td class="text-center">'.$bsresult->value.'</td>';
                      }
                    }else {
                      $output .='<td class="text-center">-</td>';
                    }
                    $output .='</tr>';
                  }
                }
                $output .='</table></div><br>';/*basic skill table closed*/
                $queryEvaKey=$this->db->query("select * from bstype where academicyear='$max_year' and btgrade='$grade' ");
                $output.='<div class="row"><div class="col-lg-6 col-6">';
                if($queryEvaKey->num_rows()>0){
                  $output.= '<div id="ENS" class="table-responsive">
                  <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
                  $output .='<th class="text-center" colspan="2"><u>Grading System</u>:-</th>';
                  foreach ($queryEvaKey->result() as $keyVValue) {
                    $output .='<tr><td class="text-center">'.$keyVValue->bstype.'</td>';
                    $output .='<td class="text-center">'.$keyVValue->bsdesc.'</td></tr>';
                  }
                  $output .='</table></div>';
                }else{
                  $output .='No Evaluation Key found';
                }
                $output .='</div><div class="col-lg-6 col-6">';
                $queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade' order by leId DESC ");
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
                $output .='</div></div>';

                $output .='</div><br>';/*basic skill column closed*/
              }
              $output .='</div>';
              $output .='</div><br>';/*basic skill column closed*/
            }else{
              $output.="<div class='col-lg-6 col-6'><h6 class='text-success'><b><u>Homeroom Teacher's Comments & Recommendations </u></b></h6>";
              $queryHoomRoom=$this->db->query("select u.fname,u.mname,mysign from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
              if($queryHoomRoom->num_rows()>0){
                $rowHommeRoom=$queryHoomRoom->row_array();
                $tfName=$rowHommeRoom['fname'];
                $tmName=$rowHommeRoom['mname'];
                $signSigns=$rowHommeRoom['mysign'];
              }else{
                $tfName='------';
                $tmName='------';
                $signSigns='_____';
              }
              $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join directorplacement as dp ON dp.staff=u.username where dp.grade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
               if($queryDirector->num_rows()>0){
                $rowSignD=$queryDirector->row();
                $signName=$rowSignD->fname;
                $signmame=$rowSignD->mname;
                $signlame=$rowSignD->lname;
                $signlame=$rowSignD->lname;
                $signSignsD=$rowSignD->mysign;
              }else{
                $signName='------';
                $signmame='------';
                $signSignsD='_____';
              }
              $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and '$printValue' between mingradevalue and maxgradevalue and academicYear='$max_year'");
              if($printValue >0 && $reportCardComments->num_rows()>0){
                foreach($reportCardComments->result() as $commentValue){
                  $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                }
              }else{
                $output.=' ______________________________________ ____________________________________________  ____________________________________________ <br>';
              }                           
              $output.="<p>HRT's Name:- <u><b>".$tfName." ".$tmName."</b></u> Signature.<img alt='.' src='".base_url()."/".$signSigns."' style='height:40px;width:120px'></p>
              <p>Director's Name:-<u><b>".$signName." ".$signmame."</b></u>
                Signature  <img alt='.' src='".base_url()."/".$signSignsD."' class='' style='height:40px;width:120px'></p>";
             /*class row closed*/ 
                $queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade' order by leId DESC ");
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
                 $output.='</div>';
                $output .='</div>';
                $output .='</div><br>';/*basic skill column closed*/
            }
            $output.='<h6 class="text-center"><strong>'.$slogan.'</strong></h6>';
            $output.='<h7 class="pull-right">ይህ ካርድ ጊዜያዊ ዉጤት ማሳወቂያ ሲሆን እንደ ትምህርት ማስረጃ አያገለግልም::</h7><br>';
          }
          $output .='</div>';
        } 
      } 
    }
    return $output;
  }
  function rosterSemester($max_year,$gradesec,$branch,$page)
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
      <h5> Campus : '.$branch.'</h5>
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
        <td class="text-center rotateJossRoster"><div>Age</div></td> 
        <td class="text-center rotateJossRoster"><div>Sex</div></td> 
        <td class="text-center rotateJossRoster"><div>Term</div></td>';
        $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ");
        foreach ($query_result ->result() as $rvalue)
        {
          $output .=' <td class="rotateJossRoster text-center"><div>'.$rvalue->subject.'</div></td>';
        }
        $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade_name' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");       
        foreach ($query_basicskill->result() as $bsvalue) {
          $bsname=$bsvalue->bsname;
          $output .='<td class="rotateJossRoster text-center"><div>'.$bsvalue->bsname.'</div></td>';
        }
        $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Tardiness</div></td> ';
        $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
        $output .='</tr>';
    $stuNo=1;
    $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
    $total_subject=$count_subject->num_rows();
    foreach ($query_student->result() as $row) 
    {
      $stuid=$row->id;
      $username1=$row->username;
      $grade_sec=$row->gradesec;
      $grade=$row->grade;
      $query_quarter=array('Quarter1','Quarter2');
      $output .='<tr><td rowspan="4">'.$stuNo.'.</td>
      <td rowspan="4">'.$row->fname.' '.$row->mname.' '.$row->lname.'</td> 
      <td rowspan="4">'.$row->age.'</td>
      <td rowspan="4">'.$row->gender.'</td>';
      $output .='<tr id="BGS"><td>1<sup>st</sup> Semester Average </td>';
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $queryCheckSem1=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter2'  ");
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
      $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC "); 
      if($query_basicskill->num_rows()>0){      
        foreach ($query_basicskill->result() as $bsvalue) {
          $output .='<td class="text-center">-</td>';
        }   
      }
      //Letter subjects
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
      /*1st Semester Tardiness calculation starts*/
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center" >'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" >-</td>';
          }
        }else{
          $output .= '<td class="text-center">-</td>';
        }
         /*1st Semester Absence calculation starts*/
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
               $output .= '<td class="text-center" >'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" >-</td>';
          }
        }else{
          $output .= '<td class="text-center">-</td>';
        }
      $output.='</tr>';

      /*2nd semester calculation starts*/
      $output .='<tr id="BGS"><td>2<sup>nd</sup> Semester Average </td>';
      
      foreach ($query_result ->result() as $subname)
      {
        $subject_mark=$subname->subject;
        $queryCheckSem2=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject_mark' and quarter='Quarter4'  ");
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
      $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC "); 
      if($query_basicskill->num_rows()>0){      
        foreach ($query_basicskill->result() as $bsvalue) {
          $output .='<td class="text-center">-</td>';
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
       /*2nd Semester Tardiness calculation starts*/
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot1stSem=$tot1stSem + $absent->att;
                }
              }
            }
          }
          if($tot1stSem > 0){
               $output .= '<td class="text-center">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center">-</td>';
          }
        }else{
          $output .= '<td class="text-center"></td>';
        }
     
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
               $output .= '<td class="text-center">'.$tot1stSem.'</td>';
          }else{
               $output .= '<td class="text-center" >-</td>';
          }
        }else{
          $output .= '<td class="text-center">-</td>';
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
      $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC "); 
      if($query_basicskill->num_rows()>0){      
        foreach ($query_basicskill->result() as $bsvalue) {
          $output .='<td class="text-center">-</td>';
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
      /*yearly tardiness calculation starts*/
      $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and absentype='Late' ");
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
      /*yearly absence calculation starts*/
      $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
        $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp 
                      ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' 
                      and isapproved='1' and status='Active' and mysign!='' ");
        if($queryDirector->num_rows()>0){
          $rowSignD=$queryDirector->row();
          $signName=$rowSignD->fname;
          $signmame=$rowSignD->mname;
          $signlame=$rowSignD->lname;
          $signlame=$rowSignD->lname;
          $signSigns=$rowSignD->mysign;
          $output.="<td colspan='2'>H.R. Teacher's
          Signature  <img alt='Sig.' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> <br>Date: ".date('M-d-Y')."</td>
          <td colspan='2'>V.Director's Signature .______________________.<br>
        Date._____________________.</td>";
        }else{
          $output.="<td colspan='2'>H.R. Teacher's Signature .______________________.<br>
          Date._____________________.</td>
          <td colspan='2'>V.Director's Signature .______________________.<br>
        Date._____________________.</td>";
        }
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="8">Record Office Signature._____________________. <br>
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
            $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade_name' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");       
            foreach ($query_basicskill->result() as $bsvalue) {
              $bsname=$bsvalue->bsname;
              $output .='<td class="rotateJossRoster text-center"><div>'.$bsvalue->bsname.'</div></td>';
            }            $output .='<td class="text-center rotateJossRoster"><div> Total</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Average</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Rank</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Tardiness</div></td> ';
            $output .='<td class="text-center rotateJossRoster"><div> Absence</div></td> ';
            $output .='</tr>';
        }
      }else{
        $output .='</tr>';
      }
      $stuNo++;
    }
    if($numStudent%$page!==0){
        $output.='<tr><td colspan="2">H.R. Teachers Signature .______________________.<br>
        Date._____________________.</td>
        <td colspan="2">V.Directors Signature .______________________.<br>
        Date._____________________.</td>';
        $output.='<td colspan="'.$numSubject.'" class="text-center">Directors Signature._____________________. <br>
        Date.________________________.</td>';
        $output.='<td colspan="5">Record Office Signature._____________________. <br>
        Date.________________________. </td></tr>';
    }
    $output .=' </table> </div>';
    return $output;
  }
  function reportcardKg_primebridge($max_year,$gradesec,$branch,$max_quarter,$includeBackPage){
    $output ='';
    $resultSem1=0;
    $resultSem2=0;
    $queryCHK=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
    if($queryCHK->num_rows()>0){
      $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade,gender,age,city,kebele, profile, section,gradesec,username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
      $stuAll=$queryStudent->num_rows();
      /*$queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec,gender,age, kebele, city,profile, username from users where id='$id' ");
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
          $result1=0;$result3=0;
          $result2=0;$result4=0;
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
          if($age=='' || $age=='0'){
            $age='___';
          }else{
            $age=$age;
          }
          $addresss=$fetchStudent->city;
          $kebele=$fetchStudent->kebele;
          $profile=$fetchStudent->profile;

          $output.= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; ">
          <div class="row" id="ENS">
          <div class="col-lg-6 col-md-6">';
          $output.='<div class="row">';
          $output.='<div class="col-lg-3"><b id="ENS">Grade: '.$fetchStudent->grade.'</b></div>';
          $output.='<div class="col-lg-3"><b id="ENS">Section: '.$fetchStudent->section.'</b></div> ';
          $output.='</div>';
          $output.='<div class="table-responsive">
         <table width="100%" id="ENS" class="tabler table-bordered table-md" cellspacing="9" cellpadding="9">';
          $output.='<tr><th colspan="8" class="text-center">
          <h6 id="ENScool"><B id="ENS">'.$school_name.' '.$gYearName.' G.C ('.$max_year.' E.C) Student Report Card</B></h6>
          </th></tr>
          <tr><th rowspan="2" class="text-center">Subject</th>
          <th colspan="3" class="text-center">First Semester</th>
          <th colspan="3" class="text-center">Second Semester</th>
          <th rowspan="2" class="text-center">Yearly Average</th></tr>';
          $output.='<tr><td class="text-center">First Quarter</td>';
          $output.='<td class="text-center">Second Quarter</td>
          <td class="text-center"><b>First Semester</b></td>
          <td class="text-center">Third Quarter</td>
          <td class="text-center">Fourth Quarter</td>
          <td class="text-center"><b>Second Semester</b></td></tr>';
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
              if($result2<=0 || $result1<=0){
                $output.='<td class="text-center">-</td>';
              }else{
                if($queryReportCardQ2->num_rows()>0){
                  $sem1Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter1') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter2') as total ");
                  foreach ($sem1Ave->result() as $fetchSem1) {
                    $resultSem1=($fetchSem1->total)/2;
                    $firstSemCheck=$resultSem1;
                    if($fetchSem1->total=='' || $fetchSem1->total<=0){
                      $output.='<td class="text-center">-</td>';
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
              $queryReportCardQ4=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter4' and subject='$subject' order by subjorder ");
              if($queryReportCardQ4->num_rows()>0){
                foreach ($queryReportCardQ4->result() as $fetchResult4) {
                  $result4=$fetchResult4->total;
                  if($fetchResult4->total=='' || $fetchResult4->total<=0){
                    $output.='<td class="text-center">-</td>';
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
              if($result3<=0 || $result4<=0){
                $output.='<td class="text-center">-</td>';
              }else{
                if($queryReportCardQ4->num_rows()>0){
                  $sem2Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter3') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter4') as total ");
                  foreach ($sem2Ave->result() as $fetchSem2) {
                    $resultSem2=($fetchSem2->total)/2;
                    if($fetchSem2->total=='' || $fetchSem2->total<=0){
                      $output.='<td class="text-center">-</td>';
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
              }
              if($queryReportCardQ4->num_rows()>0){
                  $sem1Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter1') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter2') as total ");
                  $rowCheckSum1=$sem1Ave->row();
                  $resultSem1=($rowCheckSum1->total)/2;
                  $firstSemCheck=$resultSem1;
                  if($resultSem1=='' || $resultSem1<=0){
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
          $quarterArrayQ=array('Quarter1','Quarter2');
            $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='1' order by bsname ASC ");       
            foreach ($query_basicskill->result() as $bsvalue) {
              $bsname=$bsvalue->bsname;
              $output .='<tr><td><B>'.$bsvalue->bsname.'</B></td>';
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
              $quarterArrayQ3=array('Quarter3','Quarter4');
              $output.='<td class="text-center">-</td>';

              foreach ($quarterArrayQ3 as $qvalue) {
                $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$qvalue' ");
                if($query_bsvalue->num_rows()>0) {
                  foreach ($query_bsvalue ->result() as $bsresult) {
                    $output .='<td class="text-center">'.$bsresult->value.'</td>';
                  }
                }else {
                  $output .='<td class="text-center" >-</td>';
                }
              } 
              $output.='<td class="text-center">-</td>';
              $output.='<td class="text-center">-</td>';
              $output .='</tr>';
            }
          /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
          $queryTotalAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='totalname' and grade='$grade' and allowed='1' ");
          if($queryTotalAllowed->num_rows()>0){
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
          /*if($result2<=0 || $result1<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
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
          /*}*/

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
          /*if($result3<=0 || $result4<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
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
            /*}*/
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
         $queryAverageAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='averagename' and grade='$grade' and allowed='1' ");
          if($queryAverageAllowed->num_rows()>0){
          /*Horizontal Average calculation starts*/
          $output.='<tr><td><b>Average</b></td>';
          /*1st and snd quarter calculation starts*/
          $quarterArray1=array('Quarter1','Quarter2');
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
          /*1st Semester average starts*/
          /*if($result2<=0 || $result1<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
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
           /* }*/
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
          /*if($result3<=0 || $result4<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
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
          /*}*/
            /*$output.='<td class="text-center" colspan="2">-</td>';*/
          /*Yearly Average Horizontal Average calculation starts*/
         $quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and onreportcard='1' and letter='#' ");
            if($quartrYATotal->num_rows()>0){
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
          $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='rankname' and grade='$grade' and allowed='1' ");
          if($queryRankAllowed->num_rows()>0){
            $output.='<tr><td><b>Rank</b></td>';
            $quarterArray1=array('Quarter1','Quarter2');
            foreach ($quarterArray1 as $quarterValue) {
              $quarter=$quarterValue;
              $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid' and quarter='$quarter' group by quarter ");
              if($query_total->num_rows()>0){
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' and rpbranch='$branch' group by grade ");
              
                foreach ($query_rank->result() as $qtrank)
                {
                    $output .= '<td class="text-center"><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center">-</td>';
              }
            }
            /*Semester1 Rank Total calculation starts*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
              if($queryReportCardQ2->num_rows()>0){
                $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter1' or grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter2' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter1' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' or stuid='$stuid' and quarter= 'Quarter2' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' group by grade ");
                foreach ($query_rank->result() as $rvalue) {
                    $output .= '<td class="text-center"><B>'.$rvalue->stuRank.'</B></td>';
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }

            /*Quarter3 & Quarter4 Rank Total calculation starts*/
            $quarterArray2=array('Quarter3','Quarter4');
            foreach ($quarterArray2 as $quarterValuee) {
              $quarter=$quarterValuee;
              $query_total=$this->db->query("select * from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid' and quarter='$quarter' and rpbranch='$branch' ");

              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' and rpbranch='$branch' group by grade ");
              if($query_total->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                    $output .= '<td class="text-center"><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td class="text-center">-</td>';
              }
            }

            /*Semester2 Horizontal Rank calculation starts*/
            $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1'
            and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
              if($queryReportCardQ4->num_rows()>0){
              
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter3' or grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter4' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter3' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' or stuid='$stuid' and quarter= 'Quarter4' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' group by grade ");
                foreach ($query_rank->result() as $rvalue) {
                    $output .= '<td class="text-center"><B>'.$rvalue->stuRank.'</B></td>';
                  
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            /*Yearly Rank Horizontal Rank calculation starts*/

            $query_rankya=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(`rank` order by `rank` DESC)from (select sum(total) as `rank` from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid'  and academicyear='$max_year' and grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by grade ");
            if($resultSem2 !==0 ){
              /*if($query_check_semster2_sub->num_rows()>0)
              {*/
                foreach ($query_rankya ->result() as $row_rankya)
                {
                  $rankNew =$row_rankya->stuRank;
                    $output .= '<td class="text-center"><B>'.$rankNew.'</B></td>';
                  
                }
              /*}else{
                $output .= '<td class="text-center" colspan="2">-</td>';
              }*/
            }else{
              $output.='<td class="text-center">-</td>';
            }
            $output.='</tr>';
          }
          
          /*student conduct calculation starts*/
          $output.='<tr><td><b>Tardiness</b></td>';
          /*1st and 2nd quarter conduct calculation starts*/
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
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
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

          /*1st Semester conduct*/
          /*3rd and 4th quarter conduct*/
          $tot1stSem=0;
          if($result2<=0 || $result1<=0){
            $output.='<td class="text-center">-</td>';
          }else{
            if($queryReportCardQ2->num_rows()>0){
            foreach ($quarterArray1 as $qvalue) {
              $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
              $qRow=$queryTerm->row();
              $date1 =$qRow->startdate;
              $date2 =$qRow->endate;
              $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
              $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
              $startDate1= $changeDate1->format('Y-m-d');
              $endDate1= $changeDate2->format('Y-m-d');
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
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

          /*2nd Semester conduct*/
          $tot2stSem=0;
          if($result3<=0 || $result4<=0){
            $output.='<td class="text-center">-</td>';
          }else{
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
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Late' ");
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
          }

          /*yearly conduct*/
          if($queryReportCardQ4->num_rows()>0){
                if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center">-</td>';
               }else{
                $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and absentype='Late' ");
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
                  $output.='<td class="text-center" >-</td>';
                }
               }
            
          }else{
            $output.='<td class="text-center">-</td>';
          }

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
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and 
              attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
          /*if($result2<=0 || $result1<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
            if($queryReportCardQ2->num_rows()>0){
            foreach ($quarterArray1 as $qvalue) {
              $queryTerm=$this->db->query("select startdate,endate from quarter where Academic_year='$max_year' and term='$qvalue' ");
              $qRow=$queryTerm->row();
              $date1 =$qRow->startdate;
              $date2 =$qRow->endate;
              $changeDate1 = DateTime::createFromFormat('d/m/y',$date1);
              $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
              $startDate1= $changeDate1->format('Y-m-d');
              $endDate1= $changeDate2->format('Y-m-d');
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between 
              '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
          /*}*/
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between 
            '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
          /*if($result3<=0 || $result4<=0){
            $output.='<td class="text-center" colspan="2">-</td>';
          }else{*/
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
              $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between
              '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
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
          /*}*/
          /* Yearly absent days*/
          /*if($queryReportCardQ4->num_rows()>0){*/
               /* if($resultSem1=='' || $resultSem1<=0){
                    $output.='<td class="text-center" colspan="2">-</td>';
               }else{*/
                $queryTotalAbsent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and
                absentype='Absent' ");
                if($queryTotalAbsent->num_rows() > 0){
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
               /*}*/
            
          /*}else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }*/
          $output.='</tr>';
          $output.="</table></div>
          <div class='row'>
            <div class ='col-md-8 col-8'>";
            $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join directorplacement as dp ON dp.staff=u.username where dp.grade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
             if($queryDirector->num_rows()>0){
              $rowSignD=$queryDirector->row();
              $signName=$rowSignD->fname;
              $signmame=$rowSignD->mname;
              $signlame=$rowSignD->lname;
              $signlame=$rowSignD->lname;
              $signSigns=$rowSignD->mysign;
              $output.="<p>Director's Name:- <u>".$signName." ".$signmame."</u><br>
              Signature  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'></p>";
            }else{
              $output.="<p>Director's Name:-______________________<br>
              Signature____________</p>";
            }
            $output.="</div>
            <div class ='col-md-4 col-4'><div class='col-lg-3'> SEAL </div>";
            
            $output.="</div>            
          </div>
          </div>";/*result table closed*/

          $output.= '<div class="col-lg-6 col-md-6">';
          $output.='<div class="row">';
          $output.="<div class='col-lg-12'><b id='ENS'>Student's Name: ".ucfirst(strtolower($fetchStudent->fname))." ".ucfirst(strtolower($fetchStudent->mname))." ".ucfirst(strtolower($fetchStudent->lname))."</b></div>";

          $output.="<div class='col-lg-12 text-center'><u>ACADEMIC/BEHAVIORAL ASSESSMENT REPORT</u></div>";
          $output.="<div class='col-lg-12'><small> To Parents:This is a skill check list to inform you about your child's progress during the past quarter. Each skill is a goal that our school uses to promote intellectual, social, emotional and physical growth. This report would help you and the school to recognize how far your child has achieved satisfactory usage of different skills that are targeted in the academic year.</small> </div></div>";
          $queryCategory=$this->db->query("select * from bscategory where academicyear='$max_year' and bcgrade='$grade' group by bscategory order by bcorder ASC");
          if($queryCategory->num_rows()>0){
            $output.= '<div class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $dateYear=date('Y');
            $output .='<tr><th colspan="5" class="text-center">'.$gYearName.' G.C ('.$max_year.' E.C ) Basic Skills and Behaviour Progress Report</th></tr>';
            $output .='<tr><th>Evaluation Area</th>';
            $quarterArrayQ=array('Quarter1','Quarter2','Quarter3','Quarter4');
            foreach ($quarterArrayQ as $qvalue) 
            {
              $output .='<th class="text-center">'.$qvalue.'</th>';
            }
            foreach ($queryCategory->result() as $bscatvalue) {
              $bscategory=$bscatvalue->bscategory;
              $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and subjectrow='0' and bscategory='$bscategory' order by bsname ASC ");       
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
          $output.='<div class="row"><div class="col-lg-6 col-6">';
          if($queryEvaKey->num_rows()>0){
            $output.= '<div id="ENS" class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $output .='<th class="text-center" colspan="2"><u>Grading System</u>:-</th>';
            foreach ($queryEvaKey->result() as $keyVValue) {
              $output .='<tr><td class="text-center">'.$keyVValue->bstype.'</td>';
              $output .='<td class="text-center">'.$keyVValue->bsdesc.'</td></tr>';
            }
            $output .='</table></div>';
          }else{
            $output .='No Evaluation Key found';
          }
          $output .='</div><div class="col-lg-6 col-6">';
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
          $output .='</div></div>';

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
            $slogan=$row_name->slogan;
            $output.='<br><div style="width:100%;height:auto;page-break-inside:avoid; page-break-after:always;display: block; ">
            <div class="row" id="ENS">

              <div class="col-lg-6 col-6">';
                $output.='
                  <h6 class="text-center"><b><u>Homeroom Teachers Remarks</u></b></h6>
                  <div class="row">
                    <div class="col-lg-12 col-12 StudentViewTextInfo">';
                    $queryHoomRoom=$this->db->query("select u.fname,u.mname from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
                    if($queryHoomRoom->num_rows()>0){
                      $rowHommeRoom=$queryHoomRoom->row_array();
                      $tfName=strtoupper($rowHommeRoom['fname']);
                      $tmName=strtoupper($rowHommeRoom['mname']);
                    }else{
                      $tfName='------';
                      $tmName='------';
                    }
                    
                    $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' group by subject order by subjorder ASC");
                    $subALl=$countSubject->num_rows();
                      if($subALl>0){
                        $output.='<b>1<sup>st</sup> Quarter Teachers Comment</b><br>';
                        $quartrTotal=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' ");
                        if($quartrTotal->num_rows()>0){
                            $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' "); 
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue1=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue1 between mingradevalue and maxgradevalue and academicYear='$max_year'");
                            if($printValue1 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='__________________________________________________________ <br>';
                            }
                          }
                          $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
                         if($queryDirector->num_rows()>0){
                          $rowSignD=$queryDirector->row();
                          $signName=$rowSignD->fname;
                          $signmame=$rowSignD->mname;
                          $signlame=$rowSignD->lname;
                          $signlame=$rowSignD->lname;
                          $signSigns=$rowSignD->mysign;
                          $output.="<p>Teachers Name:<u><b>".$tfName." ".$tmName."</b></u>
                          Sig.  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date:____________<br></p>";
                        }else{
                          $output.="<p>Teachers Name: _________________________ Sig._____ Date:_____<br>";
                        }
                        }else{
                          $output.='__________________________________________________________';
                        }
                      }else{
                        $output.='__________________________________________________________';
                      }
                      
                    $output.='</div>';
                    $output.='<div class="col-lg-12 col-12 StudentViewTextInfo">';
                    if($subALl>0){
                        $output.='<b>2<sup>nd</sup> Quarter Teachers Comment</b><br>';
                        $quartrTotal=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' ");
                        if($quartrTotal->num_rows()>0){
                             $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter2' and onreportcard='1' ");
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue2=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue2 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                            if($printValue2 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='__________________________________________________________ <br>';
                            }
                          }
                          $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
                         if($queryDirector->num_rows()>0){
                          $rowSignD=$queryDirector->row();
                          $signName=$rowSignD->fname;
                          $signmame=$rowSignD->mname;
                          $signlame=$rowSignD->lname;
                          $signlame=$rowSignD->lname;
                          $signSigns=$rowSignD->mysign;
                          $output.="<p>Teachers Name:<u><b>".$tfName." ".$tmName."</b></u>
                          Sig.  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date:____________<br></p>";
                        }else{
                          $output.="<p>Teachers Name: _________________________ Sig._____ Date:_____<br>";
                        }
                        }else{
                          $output.='__________________________________________________________.';
                        }
                      }else{
                        $output.='__________________________________________________________';
                      }
                      
                    $output.='</div>';

                    $output.='<div class="col-lg-12 col-12 StudentViewTextInfo">';
                    if($subALl>0){
                        $output.='<b>3<sup>rd</sup> Quarter Teachers Comment</b><br>';
                         $quartrTotal=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' ");
                        if($quartrTotal->num_rows()>0){
                           $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' ");
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue3=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue3 between mingradevalue and maxgradevalue and academicYear='$max_year'");

                            if($printValue3 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='__________________________________________________________ <br>';
                            }
                          }
                          $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
                         if($queryDirector->num_rows()>0){
                          $rowSignD=$queryDirector->row();
                          $signName=$rowSignD->fname;
                          $signmame=$rowSignD->mname;
                          $signlame=$rowSignD->lname;
                          $signlame=$rowSignD->lname;
                          $signSigns=$rowSignD->mysign;
                          $output.="<p>Teachers Name:<u><b>".$tfName." ".$tmName."</b></u>
                          Sig.  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date:____________<br></p>";
                        }else{
                          $output.="<p>Teachers Name: _________________________ Sig._____ Date:_____<br>";
                        }
                        }else{
                          $output.='__________________________________________________________';
                        }
                      }else{
                        $output.='__________________________________________________________';
                      }
                      
                    $output.='</div>';
                    $output.='<div class="col-lg-12 col-12 StudentViewTextInfo">';
                      if($subALl>0){
                        $output.='<b>4<sup>th</sup> Quarter Teachers Comment</b><br>';
                        $quartrTotal=$this->db->query("select * from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter4' and onreportcard='1' ");
                        if($quartrTotal->num_rows()>0){
                            $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter4' and onreportcard='1' ");
                          foreach ($quartrTotal->result() as $totalValue) {
                            $printValue4=($totalValue->total)/$subALl;
                            $reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $printValue4 between mingradevalue and maxgradevalue and academicYear='$max_year'");
                            if($printValue4 >0 && $reportCardComments->num_rows()>0){
                              foreach($reportCardComments->result() as $commentValue){
                                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
                              }
                              
                            }else{
                              $output.='__________________________________________________________ <br>';
                            }
                          }
                          $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join hoomroomplacement as dp ON dp.teacher=u.username where dp.roomgrade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
                         if($queryDirector->num_rows()>0){
                          $rowSignD=$queryDirector->row();
                          $signName=$rowSignD->fname;
                          $signmame=$rowSignD->mname;
                          $signlame=$rowSignD->lname;
                          $signlame=$rowSignD->lname;
                          $signSigns=$rowSignD->mysign;
                          $output.="<p>Teachers Name:<u><b>".$tfName." ".$tmName."</b></u>
                          Sig.  <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'> Date:____________<br></p>";
                        }else{
                          $output.="<p>Teachers Name: _________________________ Sig._____ Date:_____<br>";
                        }
                        }else{
                          $output.='__________________________________________________________';
                        }
                      }else{
                        $output.='__________________________________________________________';
                      }
                      
                    $output.='</div><hr>';
                    $queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join directorplacement as dp ON dp.staff=u.username where dp.grade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' ");
                     if($queryDirector->num_rows()>0){
                      $rowSignD=$queryDirector->row();
                      $signName=$rowSignD->fname;
                      $signmame=$rowSignD->mname;
                      $signlame=$rowSignD->lname;
                      $signlame=$rowSignD->lname;
                      $signSigns=$rowSignD->mysign;
                      $output.="<div class='col-12 col-md-12'>Director's Name:- <u><b>".$signName." ".$signmame."</b></u> Signature  
                      <img alt='' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'></div>";
                    }else{
                      $output.="<div class='col-12 col-md-12'>Director's Name:- ______________________Signature____________</div>";
                    }
                    $querySign=$this->db->query("select fname,mname,lname ,mysign from users where usertype!='Student' and isapproved='1' and status='Active' and finalapproval='1' ");
                    if($querySign->num_rows()>0){
                      $rowSign=$querySign->row();
                      $signName=$rowSign->fname;
                      $signmame=$rowSign->mname;
                      $signlame=$rowSign->lname;
                      $signlame=$rowSign->lname;
                      $signSign=$rowSign->mysign;
                      $output.='<div class="col-12 col-md-12">General Managers Name : <u><b>'.$signName.' '.$signmame.'</b></u>
                      Sig.<img alt="" src="'.base_url().'/'.$signSign.'"  style="height:40px;width:75px"> 
                      <br>Date:- _____________ </div>';
                    }else{
                      $output.='<div class="col-12 col-md-12">General Managers Name : 
                      <b>________________</b> Sig._____ 
                      <br>Date:- _____________</div>';
                    }
                    $output.='
                  </div>
                </div>
              <div class="col-lg-6 col-6">
                <div class="row">
                  <div class="col-lg-12 col-12">';
                  $output.='<h2 class="text-center"><u><b>'.strtoupper($school_name).'</b></u></h2><p class="text-center"><img class="text-center" src="'.base_url().'/logo/'.$logo.'" style="height:150px;width:150px;border:1px solid #fff; -webkit-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;border-radius:10px" ></p>
                  </div>
                  <div class="col-lg-12 col-12">
                    <h2 class="text-center"><b>'.$slogan.'!</b></h2>
                    <p class="text-center"><i class="fas fa-phone-square"></i> '.$phone.' <br>
                    <i class="fas fa-envelope"></i> '.$email.' <br>
                      <i class="fas fa-globe"></i> '.$website.'<br>
                      Addis Ababa, Ethiopia</p>
                      <h2 class="text-center"><u><b>STUDENTS REPORT CARD</b></u></h2>
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <div class="row">
                          <div class="col-lg-12 col-12">
                            <p>Name of Student <u>'.ucfirst(strtolower($fname)).' '.ucfirst(strtolower($mname)).' '.ucfirst(strtolower($lname)).'</u></p>
                          </div>
                          <div class="col-lg-6 col-6">
                            <p>Age <u>'.$age.' </u></p> 
                          </div> 
                          <div class="col-lg-6 col-6">
                            <p>Sex <u>'.$gender.' </u></p>
                          </div>
                          <div class="col-lg-4 col-4">
                            <p>Grade <u>'.$grade.' </u></p>
                          </div>
                          <div class="col-lg-4 col-4">
                            <p>Section<u> '.$section.'</u> </p>
                          </div> 
                          <div class="col-lg-4 col-4">
                            <p>Academic Year <u>'.$max_year.' </u></p>
                          </div>
                        </div>
                      </div>';
                        /*if($profile == ''){
                          $output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
                        }else{
                          $output.='<img alt="Photo" src="'.base_url().'/profile/'.$profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
                        }*/
                      $output.='
                      <div class="col-lg-12 col-12">
                          <p>Promoted To Grade_____________</p>
                      </div>
                      <div class="col-lg-12 col-12">
                          <p>Detained To Grade_____________</p>
                      </div>
                      <div class="col-lg-12 col-12">
                          <p>Incomplete In Grade_____________</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              </div>
            </div>';
            $printValue=0;
          }
        }
      } 
    }
    return $output;
  }
  function custom_ReportCard_Semester($max_year,$gradesec,$branch,$id,$includeBackPage){
    $output ='';
    $resultSem1=0;
    $resultSem2=0;
    $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$max_year."' ");
    if ($queryCheck->num_rows()>0 ){
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
          $result1=0;$result2=0;
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
          $output.='<h4 class="text-info"><b>
          Student Name:- '.ucfirst(strtolower($fname)).' '.ucfirst(strtolower($mname)).' '.ucfirst(strtolower($lname)).'</b></h4>';
          $output.='<div class="table-responsive">
          <table width="100%" height="80%" id="ENS" class="tabler table-bordered table-md" cellspacing="9" cellpadding="9">';
          $output.='
          <tr><th class="text-center">የትምህርት ዓይነት <br> Subject</th>
          <th class="text-center"> 1<sup>ኛ</sup> ወሰነ ትምህርት <br> 1<sup>st</sup> Semester</th>
          <th class="text-center"> 2<sup>ኛ</sup> ወሰነ ትምህርት <br> 2<sup>nd</sup> Semester</th>
          <th class="text-center"> አማካኝ <br> Average</th> </tr>';
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
            <div class ='col-md-8 col-8'>
              <p>የት/ቤቱ ርዕሰ መምህር ስም _________________________. </p>
            </div>
            <div class ='col-md-4 col-4'>
              <p>ፊርማ _________.</p>
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
          $output.='<h4 class="text-center text-info"><b>
          የክፍል ኃላፊ መምህር አስተያየት <br> Remark From the Homeroom teacher </b></h4>
          <div class="row">
            <div class="col-lg-12 col-12">';
            $output.="<p><b> 1<sup>ኛ</sup> ወሰነ ትምህርት</b> ____________________________________________________________________";
            $output.="<br><b>1<sup>st</sup> Semester</b> _____________________________________________________________________</p>";
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
                    $output.='___________________________________________________________________________________ ___________________________________________________________________________________  ___________________________________________________________________________________ <br>';
                  }
                }
              }else{
                $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
              }
            }else{
              $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
            }
            $output.="
              <p>የክፍል ኃላፊ መምህር ፊርማ ______________________________________________________________ <br>
              Signature of Homeroom teacher</p>
              <p>የወላጅ ወይም የአሳዳጊ ፊርማ ______________________________________________________________ <br>
              Signature of Parent/Guardian<br>
            </div>";
            $output.='<div class="col-lg-12 col-12"><br><br>';
             $output.="<p><b> 2<sup>ኛ</sup> ወሰነ ትምህርት</b> ____________________________________________________________________";
            $output.="<br><b>2<sup>nd</sup> Semester</b> _____________________________________________________________________</p>";
            if($subALl>0){
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
                      $output.='___________________________________________________________________________________ ___________________________________________________________________________________  ___________________________________________________________________________________ <br>';
                    }
                  }
                }else{
                  $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
                }
              }else{
                $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
              }
              $output.="
              <p>የክፍል ኃላፊ መምህር ፊርማ ______________________________________________________________ <br>
              Signature of Homeroom teacher</p>
              <p>የወላጅ ወይም የአሳዳጊ ፊርማ ______________________________________________________________ <br>
              Signature of Parent/Guardian
            </div>
          </div>";
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
                $output.='
              </div>
              <div class="col-lg-6 col-6">
                <div class="row">
                  <div class="col-lg-12 col-12">';
                  $output.='<p class="text-center"><img class="text-center" src="'.base_url().'/logo/'.$logo.'" style="height:150px;width:150px;border:1px solid #fff; -webkit-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;border-radius:10px" ></p>
                  </div>
                  <div class="col-lg-12 col-12">
                    <p class="text-center"><i class="fas fa-phone-square"></i>: '.$phone.'<br><i class="fas fa-envelope"></i> '.$email.'<br>Website:- '.$website.'<br>Addis Ababa, Ethiopia</p>
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
    }else{
      $output .='<div class="alert alert-warning alert-dismissible show fade">
        <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
           <i class="fas fa-check-circle"> </i>No record found.
        </div></div>';
    }
    return $output;
  }
  function default_ReportCard_Semester($max_year,$gradesec,$branch,$includeBackPage){
    $output ='';
    $resultSem1=0;
    $resultSem2=0;
    $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$max_year."' ");
    if ($queryCheck->num_rows()>0 ){
      $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade,gender,age,city,kebele, profile, section,gradesec,username,woreda from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
      $stuAll=$queryStudent->num_rows();

      /*$queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, gender,age, kebele,woreda, city,profile, username from users where id='$id' ");
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
          $result1=0;$result2=0;
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
          $output.='<h4 class="text-info"><b>
          Student Name:- '.ucfirst(strtolower($fname)).' '.ucfirst(strtolower($mname)).' '.ucfirst(strtolower($lname)).'</b></h4>';
          $output.='<div class="table-responsive">
          <table width="100%" height="80%" id="ENS" class="tabler table-bordered table-md" cellspacing="9" cellpadding="9">';
          $output.='
          <tr><th class="text-center">የትምህርት ዓይነት <br> Subject</th>
          <th class="text-center"> 1<sup>ኛ</sup> ወሰነ ትምህርት <br> 1<sup>st</sup> Semester</th>
          <th class="text-center"> 2<sup>ኛ</sup> ወሰነ ትምህርት <br> 2<sup>nd</sup> Semester</th>
          <th class="text-center"> አማካኝ <br> Average</th> </tr>';
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
            <div class ='col-md-8 col-8'>
              <p>የት/ቤቱ ርዕሰ መምህር ስም _________________________. </p>
            </div>
            <div class ='col-md-4 col-4'>
              <p>ፊርማ _________.</p>
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
          $output.='<h4 class="text-center text-info"><b>
          የክፍል ኃላፊ መምህር አስተያየት <br> Remark From the Homeroom teacher </b></h4>
          <div class="row">
            <div class="col-lg-12 col-12">';
            $output.="<p><b> 1<sup>ኛ</sup> ወሰነ ትምህርት</b> ____________________________________________________________________";
            $output.="<br><b>1<sup>st</sup> Semester</b> _____________________________________________________________________</p>";
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
                    $output.='___________________________________________________________________________________ ___________________________________________________________________________________  ___________________________________________________________________________________ <br>';
                  }
                }
              }else{
                $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
              }
            }else{
              $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
            }
            $output.="
              <p>የክፍል ኃላፊ መምህር ፊርማ ______________________________________________________________ <br>
              Signature of Homeroom teacher</p>
              <p>የወላጅ ወይም የአሳዳጊ ፊርማ ______________________________________________________________ <br>
              Signature of Parent/Guardian<br>
            </div>";
            $output.='<div class="col-lg-12 col-12"><br><br>';
             $output.="<p><b> 2<sup>ኛ</sup> ወሰነ ትምህርት</b> ____________________________________________________________________";
            $output.="<br><b>2<sup>nd</sup> Semester</b> _____________________________________________________________________</p>";
            if($subALl>0){
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
                      $output.='___________________________________________________________________________________ ___________________________________________________________________________________  ___________________________________________________________________________________ <br>';
                    }
                  }
                }else{
                  $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
                }
              }else{
                $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
              }
              $output.="
              <p>የክፍል ኃላፊ መምህር ፊርማ ______________________________________________________________ <br>
              Signature of Homeroom teacher</p>
              <p>የወላጅ ወይም የአሳዳጊ ፊርማ ______________________________________________________________ <br>
              Signature of Parent/Guardian
            </div>
          </div>";
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
                $output.='
              </div>
              <div class="col-lg-6 col-6">
                <div class="row">
                  <div class="col-lg-12 col-12">';
                  $output.='<p class="text-center"><img class="text-center" src="'.base_url().'/logo/'.$logo.'" style="height:150px;width:150px;border:1px solid #fff; -webkit-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;border-radius:10px" ></p>
                  </div>
                  <div class="col-lg-12 col-12">
                    <p class="text-center"><i class="fas fa-phone-square"></i>: '.$phone.'<br><i class="fas fa-envelope"></i> '.$email.'<br>Website:- '.$website.'<br>Addis Ababa, Ethiopia</p>
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
    }else{
      $output .='<div class="alert alert-warning alert-dismissible show fade">
        <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
           <i class="fas fa-check-circle"> </i>No record found.
        </div></div>';
    }
    return $output;
  }
  function default_ReportCard_Quarter($max_year,$gradesec,$branch,$includeBackPage){
    $output ='';
    
    $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$max_year."' ");
    if($queryCheck->num_rows()>0 ){
      $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade,gender,age,city,kebele, profile, section,gradesec,username,woreda from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
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
          $resultSem1=0;
          $resultSem2=0; $result2=0; $result1=0;$result4=0;$result3=0;
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
          $output.= '<div class="row" id="ENS">
          <div class="col-lg-7 col-md-7">';
          $output.='<h4 class="text-info"><b>
          Student Name:- '.ucfirst(strtolower($fname)).' '.ucfirst(strtolower($mname)).' '.ucfirst(strtolower($lname)).'</b></h4>';
           $output.='<div class="table-responsive">
          <table width="100%" height="80%" id="ENS" class="tabler table-bordered table-md" cellspacing="9" cellpadding="9">';
          $output.='
          <tr><th class="text-center">የትምህርት ዓይነት <br> Subject</th>
          <th class="text-center"> 1<sup>ኛ</sup> ወሰነ ትምህርት <br> 1<sup>st</sup> Semester</th>
          <th class="text-center"> 2<sup>ኛ</sup> ወሰነ ትምህርት <br> 2<sup>nd</sup> Semester</th>
          <th class="text-center"> አማካኝ <br> Average</th> </tr>';

          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' 
          and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr><td style="white-space: nowrap"><B>'.$fetchSubject->subject.'</B></td>';/*Subject List*/
              $queryReportCardQ2=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter2' and subject='$subject' order by subjorder ");
          
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
              /*fetch quarter 4 result starts*/
              $queryReportCardQ4=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter4' and subject='$subject' order by subjorder ");
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
              /*Yearly Average calculation starts*/
              if($queryReportCardQ2->num_rows()>0){
                  $sem1Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter1') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter2') as total ");
                  $rowCheckSum1=$sem1Ave->row();
                  $resultSem1=($rowCheckSum1->total)/2;
                  $firstSemCheck=$resultSem1;
                  if($resultSem1=='' || $resultSem1<=0){
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
          $queryTotalAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='totalname' and grade='$grade' and allowed='1' ");
          if($queryTotalAllowed->num_rows()>0){
            $output.='<tr><td><b>Total</b></td>';          
            /*Semester1 Horizontal Total calculation starts*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
              if($result2>0){
                foreach ($quartrSem1Total->result() as $totalValueSem1) {
                  $printValueSem1=($totalValueSem1->total)/2;
                  if($printValueSem1 >0){
                    $output .= '<td class="text-center" ><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            /*Semester2 Horizontal Total calculation starts*/
            $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1'
            and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
              if($result4>0){
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
            if($result4>0){
                if($resultSem2 ==0 || $resultSem1 == 0){
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
          $queryAverageAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='averagename' and grade='$grade' and allowed='1' ");
          if($queryAverageAllowed->num_rows()>0){
            $output.='<tr><td><b>Average</b></td>';            
            /*1st Semester average starts*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
              if($result2>0){
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
            
            /*Semester2 Horizontal Average calculation starts*/
           $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
              if($result4>0){
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
              if($result4>0){
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
          $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='rankname' and grade='$grade' and allowed='1' ");
          if($queryRankAllowed->num_rows()>0){
            $output.='<tr><td><b>Rank</b></td>';
            /*Semester1 Rank Total calculation starts*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
              if($result2>0){
                $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter1' or grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter2' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter1' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' or stuid='$stuid' and quarter= 'Quarter2' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' group by grade ");
                foreach ($query_rank->result() as $rvalue) {
                  $output .= '<td class="text-center"><B>'.$rvalue->stuRank.'</B></td>';
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }

            /*Semester2 Horizontal Rank calculation starts*/
            $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1'
            and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
              if($result4>0){
              
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter3' or grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter4' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter3' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' or stuid='$stuid' and quarter= 'Quarter4' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' group by grade ");
                foreach ($query_rank->result() as $rvalue) {
                  $output .= '<td class="text-center" ><B>'.$rvalue->stuRank.'</B></td>';
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            /*Yearly Rank Horizontal Rank calculation starts*/

            $query_rankya=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid'  and academicyear='$max_year' and grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by grade ");
            if($resultSem2 !==0 ){
              /*if($query_check_semster2_sub->num_rows()>0)
              {*/
                foreach ($query_rankya ->result() as $row_rankya)
                {
                  $rankNew =$row_rankya->stuRank;
                  $output .= '<td class="text-center"><B>'.$rankNew.'</B></td>';
                }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            $output.='</tr>';
          }
          
          /*Number of student calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">No. Of Student</b></td>';
          $output.='<td class="text-center">'.$stuAll.'</td>';

          if($result4 >0){
            $output.='<td class="text-center">'.$stuAll.'</td>';
            $output.='<td class="text-center">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
            $output.='<td class="text-center">-</td>';
          }
          $output.='</tr>';
          /*student conduct calculation starts*/
          $output.='<tr><td><b>Conduct</b></td>';
          /*1st and 2nd quarter conduct calculation starts*/
          $quarterArray1=array('Semester1','Semester2');
          foreach ($quarterArray1 as $quarterValue) {
            $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarterValue' and bsname='Conduct' group by stuid ");
            if($eachQuarterBasicskill->num_rows()>0){
              $valueBS=$eachQuarterBasicskill->row();
              $getValue=$valueBS->value;
              $output.='<td class="text-center" >'.$getValue.'</td>';
            }else{
              $output.='<td class="text-center">-</td>';
            }
          }
          /*1st Semester conduct*/
          /*yearly conduct*/
          $output.='<td class="text-center">-</td>';
          $output.='</tr>';
          /*Absetn days calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">Absence Days</b></td>';
          /*1st and 2nd quarter absence days*/
          $quarterArray1=array('Quarter1','Quarter2');
          $tot1stSem=0;
          if($result2>0){
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
              $output .= '<td class="text-center"><B>'.$tot1stSem.'</B></td>';
            }else{
              $output .= '<td class="text-center"><B>-</B></td>';
            }
          }else{
            $output .= '<td class="text-center"><B>-</B></td>';
          }
          /*quarter 3 and 4th quarter absent days*/
          $quarterArray3=array('Quarter3','Quarter4');
          
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot2stSem=$tot2stSem + $absent->att;
                }
              }
            }
          }
          if($tot2stSem > 0){
            $output .= '<td class="text-center" ><B>'.$tot2stSem.'</B></td>';
          }else{
            $output .= '<td class="text-center" ><B>-</B></td>';
          }
          if($result4>0){
            if($resultSem1=='' || $resultSem1<=0){
              $output.='<td class="text-center" >-</td>';
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
          $output.="</table></div>
          <div class='row'>
            <div class ='col-md-8 col-8'>
              <p>የት/ቤቱ ርዕሰ መምህር ስም _________________________. </p>
            </div>
            <div class ='col-md-4 col-4'>
              <p>ፊርማ _________.</p>
            </div>
          </div>
          </div>";/*result table closed*/
          $output.= '<div class="col-lg-5 col-md-5">';
          $output.='<div class="row"><div class="col-lg-12">';
          $query_name = $this->db->query("select * from school");
          $row_name = $query_name->row();
          $school_name=$row_name->name;
          $address=$row_name->address;
          $phone=$row_name->phone;
          $website=$row_name->website;
          $email=$row_name->email;
          $logo=$row_name->logo;
          $output.='<h4 class="text-center text-info"><b>
          የክፍል ኃላፊ መምህር አስተያየት <br> Remark From the Homeroom teacher </b></h4>
          <div class="row">
            <div class="col-lg-12 col-12">';
            $output.="<p><b> 1<sup>ኛ</sup> ወሰነ ትምህርት</b> ____________________________________________________________________";
            $output.="<br><b>1<sup>st</sup> Semester</b> _____________________________________________________________________</p>";
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
                    $output.='___________________________________________________________________________________ ___________________________________________________________________________________  ___________________________________________________________________________________ <br>';
                  }
                }
              }else{
                $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
              }
            }else{
              $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
            }
            $output.="
              <p>የክፍል ኃላፊ መምህር ፊርማ ______________________________________________________________ <br>
              Signature of Homeroom teacher</p>
              <p>የወላጅ ወይም የአሳዳጊ ፊርማ ______________________________________________________________ <br>
              Signature of Parent/Guardian<br>
            </div>";
            $output.='<div class="col-lg-12 col-12"><br><br>';
             $output.="<p><b> 2<sup>ኛ</sup> ወሰነ ትምህርት</b> ____________________________________________________________________";
            $output.="<br><b>2<sup>nd</sup> Semester</b> _____________________________________________________________________</p>";
            if($subALl>0){
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
                      $output.='___________________________________________________________________________________ ___________________________________________________________________________________  ___________________________________________________________________________________ <br>';
                    }
                  }
                }else{
                  $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
                }
              }else{
                $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
              }
              $output.="
              <p>የክፍል ኃላፊ መምህር ፊርማ ______________________________________________________________ <br>
              Signature of Homeroom teacher</p>
              <p>የወላጅ ወይም የአሳዳጊ ፊርማ ______________________________________________________________ <br>
              Signature of Parent/Guardian
            </div>
          </div>";
          $printValue=0;
          $output .='</div>';
          $output .='</div><br>';/*basic skill column closed*/
          $output.='</div>';/*class row closed*/
        }
      } 
    }else{
      $output .='<div class="alert alert-warning alert-dismissible show fade">
      <div class="alert-body">
          <button class="close"  data-dismiss="alert">
              <span>&times;</span>
          </button>
         <i class="fas fa-check-circle"> </i>No table found.
      </div></div>';
    }
    return $output;
  } 
  function custom_ReportCard_Quarter($max_year,$gradesec,$branch,$id,$includeBackPage){
    $output ='';
    
    $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$max_year."' ");
    if($queryCheck->num_rows()>0 ){
      $queryStudent=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, gender,age, kebele,woreda, city,profile, username from users where id='$id' ");
      $queryStudentNum=$this->db->query(" Select fname,mname,lname,id,grade, section,gradesec, username from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' ");
      $stuAll=$queryStudentNum->num_rows();
      /*$queryStudent=$this->db->query(" Select fname,mname,lname,id,grade,gender,age,city,kebele, profile, section,gradesec,username,woreda from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
      $stuAll=$queryStudent->num_rows();*/
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
          $resultSem1=0;
          $resultSem2=0; $result2=0; $result1=0;$result4=0;$result3=0;
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
          $output.= '<div class="row" id="ENS">
          <div class="col-lg-7 col-md-7">';
          $output.='<h4 class="text-info"><b>
          Student Name:- '.ucfirst(strtolower($fname)).' '.ucfirst(strtolower($mname)).' '.ucfirst(strtolower($lname)).'</b></h4>';
           $output.='<div class="table-responsive">
          <table width="100%" height="80%" id="ENS" class="tabler table-bordered table-md" cellspacing="9" cellpadding="9">';
          $output.='
          <tr><th class="text-center">የትምህርት ዓይነት <br> Subject</th>
          <th class="text-center"> 1<sup>ኛ</sup> ወሰነ ትምህርት <br> 1<sup>st</sup> Semester</th>
          <th class="text-center"> 2<sup>ኛ</sup> ወሰነ ትምህርት <br> 2<sup>nd</sup> Semester</th>
          <th class="text-center"> አማካኝ <br> Average</th> </tr>';

          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' 
          and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr><td style="white-space: nowrap"><B>'.$fetchSubject->subject.'</B></td>';/*Subject List*/
              $queryReportCardQ2=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter2' and subject='$subject' order by subjorder ");
          
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
              /*fetch quarter 4 result starts*/
              $queryReportCardQ4=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='Quarter4' and subject='$subject' order by subjorder ");
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
              /*Yearly Average calculation starts*/
              if($queryReportCardQ2->num_rows()>0){
                  $sem1Ave=$this->db->query("select(select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter1') + (select sum(total) from reportcard".$gradesec.$max_year." where stuid='$stuid' and subject='$subject' and quarter='Quarter2') as total ");
                  $rowCheckSum1=$sem1Ave->row();
                  $resultSem1=($rowCheckSum1->total)/2;
                  $firstSemCheck=$resultSem1;
                  if($resultSem1=='' || $resultSem1<=0){
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
          $queryTotalAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='totalname' and grade='$grade' and allowed='1' ");
          if($queryTotalAllowed->num_rows()>0){
            $output.='<tr><td><b>Total</b></td>';          
            /*Semester1 Horizontal Total calculation starts*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
              if($result2>0){
                foreach ($quartrSem1Total->result() as $totalValueSem1) {
                  $printValueSem1=($totalValueSem1->total)/2;
                  if($printValueSem1 >0){
                    $output .= '<td class="text-center" ><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                  }else{
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            /*Semester2 Horizontal Total calculation starts*/
            $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1'
            and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
              if($result4>0){
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
            if($result4>0){
                if($resultSem2 ==0 || $resultSem1 == 0){
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
          $queryAverageAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='averagename' and grade='$grade' and allowed='1' ");
          if($queryAverageAllowed->num_rows()>0){
            $output.='<tr><td><b>Average</b></td>';            
            /*1st Semester average starts*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
              if($result2>0){
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
            
            /*Semester2 Horizontal Average calculation starts*/
           $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
              if($result4>0){
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
              if($result4>0){
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
          $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='rankname' and grade='$grade' and allowed='1' ");
          if($queryRankAllowed->num_rows()>0){
            $output.='<tr><td><b>Rank</b></td>';
            /*Semester1 Rank Total calculation starts*/
            $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and  quarter='Quarter1' and onreportcard='1' and letter='#' or stuid='$stuid' and quarter='Quarter2' and onreportcard='1' and letter='#' ");
              if($result2>0){
                $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter1' or grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter2' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter1' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' or stuid='$stuid' and quarter= 'Quarter2' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' group by grade ");
                foreach ($query_rank->result() as $rvalue) {
                  $output .= '<td class="text-center"><B>'.$rvalue->stuRank.'</B></td>';
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }

            /*Semester2 Horizontal Rank calculation starts*/
            $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='Quarter3' and onreportcard='1'
            and letter='#' or stuid='$stuid' and quarter='Quarter4' and onreportcard='1' and letter='#' ");
              if($result4>0){
              
              $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter3' or grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and quarter='Quarter4' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= 'Quarter3' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' or stuid='$stuid' and quarter= 'Quarter4' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and grade='$gradesec' group by grade ");
                foreach ($query_rank->result() as $rvalue) {
                  $output .= '<td class="text-center" ><B>'.$rvalue->stuRank.'</B></td>';
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
            /*Yearly Rank Horizontal Rank calculation starts*/

            $query_rankya=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid'  and academicyear='$max_year' and grade='$gradesec' and letter='#' and onreportcard='1' and rpbranch='$branch' group by grade ");
            if($resultSem2 !==0 ){
              /*if($query_check_semster2_sub->num_rows()>0)
              {*/
                foreach ($query_rankya ->result() as $row_rankya)
                {
                  $rankNew =$row_rankya->stuRank;
                  $output .= '<td class="text-center"><B>'.$rankNew.'</B></td>';
                }
            }else{
              $output.='<td class="text-center">-</td>';
            }
            $output.='</tr>';
          }
          
          /*Number of student calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">No. Of Student</b></td>';
          $output.='<td class="text-center">'.$stuAll.'</td>';

          if($result4 >0){
            $output.='<td class="text-center">'.$stuAll.'</td>';
            $output.='<td class="text-center">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center">-</td>';
            $output.='<td class="text-center">-</td>';
          }
          $output.='</tr>';
          /*student conduct calculation starts*/
          $output.='<tr><td><b>Conduct</b></td>';
          /*1st and 2nd quarter conduct calculation starts*/
          $quarterArray1=array('Semester1','Semester2');
          foreach ($quarterArray1 as $quarterValue) {
            $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarterValue' and bsname='Conduct' group by stuid ");
            if($eachQuarterBasicskill->num_rows()>0){
              $valueBS=$eachQuarterBasicskill->row();
              $getValue=$valueBS->value;
              $output.='<td class="text-center" >'.$getValue.'</td>';
            }else{
              $output.='<td class="text-center">-</td>';
            }
          }
          /*1st Semester conduct*/
          /*yearly conduct*/
          $output.='<td class="text-center">-</td>';
          $output.='</tr>';
          /*Absetn days calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">Absence Days</b></td>';
          /*1st and 2nd quarter absence days*/
          $quarterArray1=array('Quarter1','Quarter2');
          $tot1stSem=0;
          if($result2>0){
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
              $output .= '<td class="text-center"><B>'.$tot1stSem.'</B></td>';
            }else{
              $output .= '<td class="text-center"><B>-</B></td>';
            }
          }else{
            $output .= '<td class="text-center"><B>-</B></td>';
          }
          /*quarter 3 and 4th quarter absent days*/
          $quarterArray3=array('Quarter3','Quarter4');
          
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
            $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt where attt.stuid='$username1' and attt.absentdate between '$startDate1' and '$endDate1' and attt.academicyear='$max_year' and absentype='Absent' ");
              foreach ($query_total_absent->result() as $absent){
                if($absent->att>0)
                {
                    $tot2stSem=$tot2stSem + $absent->att;
                }
              }
            }
          }
          if($tot2stSem > 0){
            $output .= '<td class="text-center" ><B>'.$tot2stSem.'</B></td>';
          }else{
            $output .= '<td class="text-center" ><B>-</B></td>';
          }
          if($result4>0){
            if($resultSem1=='' || $resultSem1<=0){
              $output.='<td class="text-center" >-</td>';
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
          $output.="</table></div>
          <div class='row'>
            <div class ='col-md-8 col-8'>
              <p>የት/ቤቱ ርዕሰ መምህር ስም _________________________. </p>
            </div>
            <div class ='col-md-4 col-4'>
              <p>ፊርማ _________.</p>
            </div>
          </div>
          </div>";/*result table closed*/
          $output.= '<div class="col-lg-5 col-md-5">';
          $output.='<div class="row"><div class="col-lg-12">';
          $query_name = $this->db->query("select * from school");
          $row_name = $query_name->row();
          $school_name=$row_name->name;
          $address=$row_name->address;
          $phone=$row_name->phone;
          $website=$row_name->website;
          $email=$row_name->email;
          $logo=$row_name->logo;
          $output.='<h4 class="text-center text-info"><b>
          የክፍል ኃላፊ መምህር አስተያየት <br> Remark From the Homeroom teacher </b></h4>
          <div class="row">
            <div class="col-lg-12 col-12">';
            $output.="<p><b> 1<sup>ኛ</sup> ወሰነ ትምህርት</b> ____________________________________________________________________";
            $output.="<br><b>1<sup>st</sup> Semester</b> _____________________________________________________________________</p>";
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
                    $output.='___________________________________________________________________________________ ___________________________________________________________________________________  ___________________________________________________________________________________ <br>';
                  }
                }
              }else{
                $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
              }
            }else{
              $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
            }
            $output.="
              <p>የክፍል ኃላፊ መምህር ፊርማ ______________________________________________________________ <br>
              Signature of Homeroom teacher</p>
              <p>የወላጅ ወይም የአሳዳጊ ፊርማ ______________________________________________________________ <br>
              Signature of Parent/Guardian<br>
            </div>";
            $output.='<div class="col-lg-12 col-12"><br><br>';
             $output.="<p><b> 2<sup>ኛ</sup> ወሰነ ትምህርት</b> ____________________________________________________________________";
            $output.="<br><b>2<sup>nd</sup> Semester</b> _____________________________________________________________________</p>";
            if($subALl>0){
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
                      $output.='___________________________________________________________________________________ ___________________________________________________________________________________  ___________________________________________________________________________________ <br>';
                    }
                  }
                }else{
                  $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
                }
              }else{
                $output.='___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________ ___________________________________________________________________________________';
              }
              $output.="
              <p>የክፍል ኃላፊ መምህር ፊርማ ______________________________________________________________ <br>
              Signature of Homeroom teacher</p>
              <p>የወላጅ ወይም የአሳዳጊ ፊርማ ______________________________________________________________ <br>
              Signature of Parent/Guardian
            </div>
          </div>";
          $printValue=0;
          $output .='</div>';
          $output .='</div><br>';/*basic skill column closed*/
          $output.='</div>';/*class row closed*/
        }
      } 
    }else{
      $output .='<div class="alert alert-warning alert-dismissible show fade">
      <div class="alert-body">
          <button class="close"  data-dismiss="alert">
              <span>&times;</span>
          </button>
         <i class="fas fa-check-circle"> </i>No table found.
      </div></div>';
    }
    return $output;
  }  
}
