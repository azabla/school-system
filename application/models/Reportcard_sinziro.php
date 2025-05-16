<?php
class reportcard_sinziro extends CI_Model{
  function fetch_term($max_year){
    $this->db->where('Academic_year',$max_year);
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
  function fetch_grade_from_branch($branch){
    $this->db->where('users.branch',$branch);
    $this->db->order_by('users.gradesec','ASC');
    $this->db->group_by('users.gradesec');
    $query=$this->db->get('users');
    $output ='';
    foreach ($query->result() as $row) { 
      $output .='<option value="'.$row->gradesec.'">'.$row->gradesec.'</option>';
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
  function fetch_student_idcard($max_year,$gradesec,$branch){
    $this->db->order_by('fname','ASC');
    $this->db->where(array('gradesec'=>$gradesec));
    $this->db->where(array('branch'=>$branch));
    $this->db->where(array('isapproved'=>'1'));
    $this->db->where(array('academicyear'=>$max_year));
    $this->db->like('usertype','Student');
    $query=$this->db->get('users');
    $output='<div class="row">';
    foreach ($query->result() as $staff) {
      $output.='
     <div class="col-lg-4">
          <div class="card author-box">
            <div class="card-body">
              <div class="text-center">';
                $query_school=$this->db->get('school');
                foreach ($query_school->result() as $school){
                  $output.='<h3><p style="font-family:Segoe UI Black;"> 
                  <img src="'.base_url().'/logo/'. $school->logo.' " style="height: 90px;width: 90px;border-radius: 10px">'.$school->name.'';
                  $output.='<br><i class="fas fa-phone"></i> '.$school->phone.' <br><i class="fas fa-envelope"></i> '.$school->email.'
                  <br>'.$school->address.'</p></h3>';
                }
                $output.='<div class="dropdown-divider"></div><h3><p>
                <img alt="image" src="'.base_url().'/profile/'.$staff->profile.'"  style="height: 140px;width: 140px;border-radius: 10px">
                <a href="#" style="font-family:Segoe UI Black;"> '.$staff->fname.' '.$staff->mname. ' </a>
                  </p></h3>
              </div>
              <div class="text-center">
                <div class="author-box-description"><h4>
                  <a href="#" style="font-family:Segoe UI Black;">Grade : '.$staff->gradesec.'</a> 
                  <span class="time" style="font-family:Poor Richard">Academic Year : '.$staff->academicyear .'E.C</span> <br>' ;
                  $output.='Guardian Phone :<a href="#" style="font-family:Segoe UI Black;" > '.$staff->mobile.'</a><br>
                  <span class="time" style="font-family:Poor Richard">School Signature____________.</span></h4>
                </div>
              </div>
              <div class="dropdown-divider"></div>
              <span class="time" style="font-family:Poor Richard">
              <u>Dear Parents</u><br>
              <i class="fas fa-check-circle"> </i> This Identification card is used until June 30 2014 E.C. <br>
              <i class="fas fa-check-circle"> </i> You are Expected to bring the student identification card when you come to take the student home.<br>
              <i class="fas fa-check-circle"> </i> If you lost the Identification card let us know immediatley.
              </span>
            </div>
          </div>
        </div>
      ';
    }
    $output.='</div>';
    return $output;
  }
   function prepareRCTable($max_year){
    $queryStudent=$this->db->query("select gradesec from users where academicyear='$max_year' and usertype='Student' and status='Active' and isapproved='1' group by gradesec; "); 
    $output='';
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
    return $output;
  }
    function update_reportcardResult($max_year,$gradesec,$branch,$max_quarter){
    $this->db->where('us.gradesec',$gradesec);
    $this->db->where('us.academicyear',$max_year);
    $this->db->where('us.status','Active');
    $this->db->where('us.isapproved','1');
    $this->db->where('us.branch',$branch);
    $this->db->where('su.Academic_Year',$max_year);
    $this->db->group_by('us.id,su.Subj_name');
    $this->db->select('us.grade, us.id, us.gradesec, su.Subj_name, su.Merged_percent, su.Merged_name, su.suborder, su.letter, su.onreportcard');
    $this->db->from('users as us');
    $this->db->join('subject as su', 
            'us.grade = su.Grade');
    $querySubject = $this->db->get();
    if($querySubject->num_rows()>0){
      $total=0;$average=0;$average1=0;
      $queyDelete=$this->db->query("delete from reportcard".$gradesec.$max_year." where rpbranch ='$branch' and grade='$gradesec' and quarter='$max_quarter' ");
        foreach ($querySubject->result() as $calcMark) {
          $stuid=$calcMark->id;
          $grade=$calcMark->grade;
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
          $this->db->where('ev.academicyear',$max_year);
          $this->db->where('ev.quarter',$max_quarter);
          $this->db->where('ev.grade',$grade);
          $this->db->where('ma.academicyear',$max_year);
          $this->db->where('ma.stuid',$stuid);
          $this->db->where('ma.subname',$subject);
          $this->db->where('ma.quarter',$max_quarter);
          $this->db->group_by('ev.eid,ma.stuid,ma.subname');
          $this->db->select('ma.stuid, ma.subname, ma.quarter,sum(ma.value) as total,sum(ma.outof) as outof,ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent');
          $this->db->from('evaluation as ev');
          $this->db->join('mark'.$branch.$gradesec.$max_quarter.$max_year.' as ma', 
                  'ev.eid = ma.evaid');
          $evalname_query = $this->db->get();
          $average1=0;$average=0;
          foreach ($evalname_query->result() as $eValue) {
            $sumu_otof=$eValue->outof;
            $evaid=$eValue->eid;
            $percent=$eValue->percent;
            if($eValue->outof != 0){
              $conver= ($eValue->total *$percent )/$sumu_otof;
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

  function report_card($max_year,$gradesec,$branch,$max_quarter){
    $query_student=$this->db->query(" Select * from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' 
    and academicyear='$max_year' order by fname ASC ");
    $output ='';
    foreach ($query_student->result() as $row_student)
    {
      $stuid=$row_student->id;
      $grade=$row_student->grade;
      $grade_sec=$row_student->gradesec;
      $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $address=$row_name->address;
        $phone=$row_name->phone;
        $website=$row_name->website;
        $email=$row_name->email;
        $yearname=$max_year;
        $output.='<div class="row">
          <div class="col-lg-8">
          <div class="text-center">
          <span class="time text-danger" style="font-family:Rockwell">
          <h2>'.$school_name.'</h2></span>
          <span class="time text-info" style="font-family:Rockwell">
          <h5>Academic Year: '.$max_year.' E.C</h5></span>
          <span class="text-info" style="font-family:Poor Richard">Address: '.$address.'<br>
          Website : '.$website.'</span>
          </div>
          <div class="row">
          <div class="col-lg-7 text-success">
          <h5><b> Name : '.$row_student->fname.' '.$row_student->mname.' '.$row_student->lname.'</b></h5>
          </div>
          <div class="col-lg-5 text-success">
          <h5><b>  Grade : '.$row_student->gradesec.'</b></h5>
          </div>
          </div>
          </div>
          <div class="col-lg-4">
          <div class="text-center">
          <span class="time text-danger" style="font-family:Viner Hand ITC">
          <h2>Student Report Card</h2>
          </span>
          <span class="text-info" style="font-family:Poor Richard">Phone: '.$phone.'<br>Email: '.$email.'</span>
          </div>  
          </div>
      </div>';
      $output.= '<div class="row"><div class="col-lg-6">
        <div class="table-responsive">
        <table width="100%"  class="tabler table-bordered table-md" cellspacing="5" cellpadding="5">';
      $output.='<tr><th>Subject</th>';
      
      if($max_quarter===trim('Sem2')){
        $query_quarter=$this->db->query(" Select * from quarter where Academic_year='$max_year' group by term order by term ASC ");
      }else{
        $query_quarter=$this->db->query(" Select * from quarter where Academic_year='$max_year' and term='$max_quarter' group by term order by term ASC ");
      }
      
      $output.='<th>Teachers Name</th>';
      foreach ($query_quarter->result() as $qvalue) {
            $output .='<th>'.$qvalue->term.'</th>';
            $output .='<th class="text-center">TR</th>';
        }
        if($max_quarter===trim('Sem2')){
          $output .='<th>Yearly Average</th>';
        }
        $output.='</tr>';

        $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' 
        and onreportcard='1' group by subject order by subject ASC ");
        foreach ($query_result->result() as $qvalue_result) 
        {
          $subject=$qvalue_result->subject;
            $output .='<tr><td>'.$qvalue_result->subject.'</td>';
            /*Teachers Name Query Starts*/
            $queryTeacher=$this->db->query("select * from staffplacement as staffp cross join users as user where user.username=staffp.staff and staffp.grade='$gradesec' 
            and staffp.academicyear='$max_year' and staffp.subject='$subject' ");
            if($queryTeacher->num_rows()>0){
              $queryRow=$queryTeacher->row();
              $stafFirName=$queryRow->fname;$staffMidName=$queryRow->mname;
              $output.='<td>'.$stafFirName.' '.$staffMidName.'</td>';
            }else{
              $output.='<td>-</td>';
            }
            /*Teachers Name Query End*/
          foreach ($query_quarter->result() as $qvalue) 
          {
            $quarter=$qvalue->term;
              $query_qua_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' 
              and stuid='$stuid' and academicyear='$max_year' and subject='$subject' and quarter ='$quarter' and onreportcard='1' group by subject order by subject ASC ");
              if($query_qua_result->num_rows()>0)
              {
            foreach ($query_qua_result->result() as $quvalue)
            {
                  $letter=$quvalue->letter;
                  $result=$quvalue->total;
                  if($letter!='A')
                  {
                    $output .='<td>'.$result.'</td>';
                  }
                  else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                  }
                }
              }else{
                $output.= "<td> -</td>";
              }
              if($result >= 90 && $result<=100 ){
                $output .='<td>OP</td>';
              }else if($result >= 80 && $result<=89 ){
                $output .='<td>WH</td>';
              }else if($result >= 70 && $result<=79 ){
                $output .='<td>AP</td>';
              }else if($result >= 60 && $result<=69 ){
                $output .='<td>HK</td>';
              }else if($result >= 50 && $result<=59 ){
                $output .='<td>MI</td>';
              }else{
                $output .='<td>NI</td>';
              }
              
          }
          /*Each subject Yearly average starts
          (Vertically*/
          $subject=$qvalue_result->subject;
          $letter=$qvalue_result->letter;
            $query_sa1_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where subject='$subject' and onreportcard='1'
            and academicyear='$max_year' and stuid ='$stuid' and quarter='Sem1' or quarter='Sem2' and onreportcard='1' and subject='$subject'  and stuid ='$stuid' group by stuid, subject ");
        foreach ($query_sa1_sub->result() as $row_sa1)
        {
          $rest222=$row_sa1->total_sum/2;
        }
        $query_check_semster2_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where  academicyear='$max_year' 
        and stuid ='$stuid' and quarter='Sem2' and subject='$subject' and onreportcard='1' group by stuid, subject ");
          if($max_quarter===trim('Sem2')){
            if($query_check_semster2_sub->num_rows()>0){

            $yearly_ave=$rest222;
            if($letter!='A'){
              $output .= '<td><B>'.number_format((float)$yearly_ave,2,'.','').'</B></td>';
            }
            else{
                $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $yearly_ave between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td> -</td>";
                      }
            }
          }else{
            $output.= "<td>-</td>";
          }
        }else{
          
        }
          $output .='</tr>'; 
        }
          /*Each Quarter total Starts(Horizontally)*/
          $check_rankAllowed=$this->db->query(" Select gr.grade,ur.grade from rank_allowed_grades as gr cross join users as ur where ur.gradesec='$gradesec' and 
          gr.academicyear='$max_year' and ur.grade=gr.grade ");
          if($check_rankAllowed->num_rows()>0)
          {
            $output .='<tr><td><b>Total</b></td><td></td>';
            foreach ($query_quarter->result() as $qvalue) 
            {
            $quarter=$qvalue->term;
              $query_qua_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' 
              and stuid='$stuid' and academicyear='$max_year' and letter!='A' and quarter ='$quarter' and onreportcard='1' group by quarter order by subject ASC ");
              if($query_qua_total->num_rows()>0)
              {
                foreach ($query_qua_total->result() as $qtvalue){
                  $output .= '<td><B>'.number_format((float)$qtvalue->quarter_total,2,'.','').'</B></td>';
                }
              } else{
                $output .='<td>-</td>';
              }
              $output .='<td>-</td>';
          }
          $query_total_gsq2=$this->db->query("select subject,sum(total) as total2 from reportcard".$gradesec.$max_year."  where letter!='A' and onreportcard='1' and academicyear='$max_year' and stuid ='$stuid' and quarter='Sem1' or quarter='Sem2' and letter!='A' and onreportcard='1' and academicyear='$max_year' and stuid ='$stuid' ");
          if(empty($subject)){
            $subject='';
          }
          /*Yearlly average total starts(Horizontally)*/
          $query_check_semster2_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where  
          academicyear='$max_year' and stuid ='$stuid' and quarter='Sem2' and subject='$subject' group by stuid , subject ");
          if($max_quarter===trim('Sem2')){
          if($query_check_semster2_sub->num_rows()>0)
          {
            foreach ($query_total_gsq2->result() as $row_a1)
            {
              $fave1=$row_a1->total2/2;
            }
            $fave_year=$fave1;
              $output .= '<td><B>'.number_format((float)$fave_year,2,'.','').'</B></td>';
          }
          else{
            $output .='<td>-</td>';
          }
        }else{
          //
        }
            $output .='</tr>';
            /*Each Yearlly Average total ends(Horizontally)*/

            /*Each Yearlly Average starts(Horizontally)*/
            $output .='<tr><td><b>Average</b></td><td></td>';
            foreach ($query_quarter->result() as $qvalue) 
            {
            $quarter=$qvalue->term;
            $query_qua_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where 
            grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and onreportcard='1' and quarter ='$quarter' group by quarter order by subject ASC ");
              /*count subject starts*/
              $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter!='A' 
              and onreportcard='1' and academicyear='$max_year' and quarter='$quarter' group by subject ");
          $total_subject=$count_subject->num_rows();
          if($query_qua_total->num_rows()>0)
          {
                foreach ($query_qua_total->result() as $qtvalue) {
                  $output .= '<td><B>'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</B></td>';
                }
              } else{
                $output .='<td>-</td>';
              }
              $output .='<td>-</td>';
          }
          if($max_quarter===trim('Sem2')){
            if($query_check_semster2_sub->num_rows()>0)
            {
              if($fave_year>0){
                $output .= '<td><B>'.number_format((float)$fave_year/$total_subject,2,'.','').'</B></td>';
            }else{
              $output .='<td><B>0.00</B></td>';
            }
          }else{
            $output .='<td>-</td>';
          }
        }else{

        }
            $output .='</tr>';
            /*Each Yearlly Average Ends(Horizontally)*/

            /*Each Quarter Rank starts(Horizontally)*/
              $output .='<tr><td><b>Rank</b></td><td></td>'; 
            foreach ($query_quarter->result() as $qvalue)
            {
            $quarter=$qvalue->term;
            $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and 
            stuid ='$stuid' and quarter='$quarter' group by quarter ");
            $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank
            from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$quarter' and onreportcard='1' group by stuid) sm)) as stuRank from 
            reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' group by grade ");
            if($query_total->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                  $output .= '<td><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td>-</td>';
              }
              $output .='<td>-</td>';
          }
          /*Rank of Yearly Average starts
          (Horizontally)*/
          $query_rankya=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from
          reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." 
          where stuid='$stuid'  and academicyear='$max_year' and grade='$gradesec' and letter='#' and onreportcard='1' group by grade ");
          if($max_quarter===trim('Sem2')){
          if($query_check_semster2_sub->num_rows()>0)
          {
            foreach ($query_rankya ->result() as $row_rankya)
            {
              $rankNew =$row_rankya->stuRank;
              $output .= '<td><b>'.$rankNew.'</b></td>';
            }
          } else{
            $output .= '<td>-</td>';
          }
        }else{

        }
          }
          $output .='</tr>';

          /*Each Quarter No of students starts(Vertically)*/
          $output .='<tr><td><b>No. of Student</b></td><td></td>';
          foreach ($query_quarter->result() as $qvalue) 
          {
          $quarter=$qvalue->term;
            $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid' 
            and quarter='$quarter' group by quarter ");
            if($query_total->num_rows()>0)
            {
          $total_student=$query_student->num_rows();
          $output .= '<td><B>'.$total_student.'</B></td>';
        } else{
          $output .= '<td>-</td>';
        }
        $output .='<td>-</td>';
      }

        /*No of student of Yearly Average starts
        (Horizontally)*/
        if($max_quarter===trim('Sem2')){
          if($query_check_semster2_sub->num_rows()>0)
          {
            $output .= '<td><B>'.$total_student.'</B></td>';
          }else{
            $output .= '<td>-</td>';
          }
        }else{

        }
        /*No of student of Yearly Average ends
        (Horizontally)*/
          $output .='</tr>';
          $output .='<tr><td><b>Conduct</b></td><td></td>';
          foreach ($query_quarter->result() as $qvalue) {
          $quarter=$qvalue->term;
          $each_quarter_basicskill=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' 
          and academicyear='$max_year' and quarter='$quarter' and bsname='Conduct' group by stuid ");
          /*if($max_quarter===trim('Sem2')){*/
            if($each_quarter_basicskill->num_rows()>0){
              foreach ($each_quarter_basicskill->result() as $keue) 
              {
                $output .= '<td><B>'.$keue->value.'</B></td>';
              }
            }else{
              $output .= '<td>-</td>';
            }
          /*}else{

          }*/
          $output .='<td>-</td>';
        }
        if($max_quarter===trim('Sem2')){$output .= '<td>-</td>';}
          $output .='</tr>';
          $output .='<tr><td><b>No. of Absence</b></td><td></td>';
          foreach ($query_quarter->result() as $qvalue) {
          $quarter=$qvalue->term;
          $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where attt.stuid='$stuid' and attt.absentdate between se.startdate and se.endate and se.term='$quarter' and attt.academicyear='$max_year' ");
          foreach ($query_total_absent->result() as $absent)
          {
          if($absent->att>0)
          {
            $output .= '<td><B>'.$absent->att.'</B></td>';
              }
              else{
              $output .= '<td><B>-</B></td>';
            }
        }
        $output .='<td>-</td>';
        }
        $query_total_absent2=$this->db->query("select count(stuid) as att from attendance where  stuid='$stuid' and academicyear='$max_year' ");
        if($max_quarter===trim('Sem2')){
          if($query_check_semster2_sub->num_rows()>0)
          {
          foreach ($query_total_absent2 ->result() as $row_absent)
          {
            if($row_absent->att>0){
              $output .= '<td><B>'.$row_absent->att.'</B></td>';
            }else{
              $output .= '<td><B>-</B></td>';
            }
          }
        }else{
          $output .= '<td>-</td>';
        }
      }else{

      }
          $output .='</tr>';
      $output .='</table></div></div>';
      
        /*Basic Skill starts here*/
      $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' order by bsname ASC ");
      $output.= '<div class="col-lg-1"></div><div class="col-lg-5">
          <div class="table-responsive">
          <table width="100%"  class="table-bordered table-md" cellspacing="5" cellpadding="5">';
       $output .='<th>Teachers Remark(TR)</th>';
       $output .='<th>Code</th>';
       $output .='<tr><td>Actively Participates</td><td>AP</td></tr>';
       $output .='<tr><td>Cheating on Test</td><td>CH</td></tr>';
       $output .='<tr><td>Complete work on time</td><td>CW</td></tr>';
       $output .='<tr><td>Cutting class</td><td>CT</td></tr>';
       $output .='<tr><td>Damage school Property</td><td>DP</td></tr>';
       $output .='<tr><td>Disturbs in class</td><td>DC</td></tr>';
       $output .='<tr><td>Has acquired good knowledge of the subject
       </td><td>HK</td></tr>';
       $output .='<tr><td>Incomplete tests/assignments</td><td>IT</td></tr>';
       $output .='<tr><td>Irregular work habits</td><td>IW</td></tr>';
       $output .='<tr><td>Made improvments this quarter</td><td>MI</td></tr>';
       $output .='<tr><td>Need improvment in His/Homework</td><td>NI</td></tr>';
       $output .='<tr><td>Often late to school</td><td>OL</td></tr>';
       $output .='<tr><td>Outstanding Progress</td><td>OP</td></tr>';
       $output .='<tr><td>Very good work habits</td><td>WH</td></tr>';
       $output .='<tr><td>Well behaved student</td><td>WB</td></tr>';
      /*foreach ($query_quarter->result() as $qvalue) 
      {
            $output .='<th>'.$qvalue->Code'</th>';
        }*/
    /*  foreach ($query_basicskill->result() as $bsvalue) {
          $bsname=$bsvalue->bsname;
        $output .='<tr><td>'.$bsvalue->bsname.'</td>';
        foreach ($query_quarter->result() as $qvalue) {
            $quarterbs=$qvalue->term;
          $query_bsvalue=$this->db->query(" Select * from basicskillvalue where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$quarterbs' ");
            $output .='<td>AP</td>';
            $output .='<td>CH</td>';
            $output .='<td>CW</td>';
            $output .='<td>CT</td>';
            $output .='<td>DP</td>';
            $output .='<td>DC</td>';
            $output .='<td>HK</td>';
            $output .='<td>IT</td>';
            $output .='<td>IW</td>';
            $output .='<td>MI</td>';
            $output .='<td>NI</td>';
            $output .='<td>OL</td>';
            $output .='<td>OP</td>';
            $output .='<td>WH</td>';
            $output .='<td>WB</td>';
        /*  if($query_bsvalue->num_rows()>0) {
            foreach ($query_bsvalue ->result() as $bsresult) {
              $output .='<td>'.$bsresult->value.'</td>';
              $output .='<td>'.$bsresult->value.'</td>';
              $output .='<td>'.$bsresult->value.'</td>';
              $output .='<td>'.$bsresult->value.'</td>';
              $output .='<td>'.$bsresult->value.'</td>';
              $output .='<td>'.$bsresult->value.'</td>';
            }
          }else {
            $output .='<td>-</td>';
          }*/
      //  } 
        //$output .='</tr>';
      //}
      /*$query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' order by bsname ASC ");
      $output.= '<div class="col-lg-4">
          <div class="table-responsive">
          <table width="100%"  class="table-bordered table-md" cellspacing="5" cellpadding="5">';
       $output .='<th>Basic Skill</th>';
      foreach ($query_quarter->result() as $qvalue) 
      {
            $output .='<th>'.$qvalue->term.'</th>';
        }
      foreach ($query_basicskill->result() as $bsvalue) {
          $bsname=$bsvalue->bsname;
        $output .='<tr><td>'.$bsvalue->bsname.'</td>';
        foreach ($query_quarter->result() as $qvalue) {
            $quarterbs=$qvalue->term;
          $query_bsvalue=$this->db->query(" Select * from basicskillvalue where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$quarterbs' ");
          if($query_bsvalue->num_rows()>0) {
            foreach ($query_bsvalue ->result() as $bsresult) {
              $output .='<td>'.$bsresult->value.'</td>';
            }
          }else {
            $output .='<td>-</td>';
          }
        } 
        $output .='</tr>';
      }*/
      /*$queryac = $this->db->query("select max(year_name) as ay from academicyear");
        $rowac = $queryac->row();
        $yearname=$rowac->ay;*/
      $output .='</table></div></div></div><br>';
      $output.='<div class="row"><div class="col-lg-8">
      <span class="time" style="font-family:Poor Richard">Name & Sig. of H.R. Teacher. ________________________.<br>Promoted To Grade_____________.  Detained In Grade_____________.</span>
      </div><div class="col-lg-4"> 
      <span class="time pull-right" style="font-family:Curlz MT">The school seal.</span><br>
      <span class="time" style="font-family:Poor Richard">Principals Sig. _______________.<br>
      </span>
      </div></div><br>';
    }
    return $output;
  }
  
  function KgReportCard($max_year,$gradesec,$branch,$max_quarter,$startDate1,$endDate1){
    $query_student=$this->db->query(" Select * from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' 
    and academicyear='$max_year' order by fname,mname,lname ASC ");
    $output ='';
    foreach ($query_student->result() as $row_student)
    {
      $stuid=$row_student->id;
      $grade=$row_student->grade;
      $grade_sec=$row_student->gradesec;
      $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $address=$row_name->address;
        $phone=$row_name->phone;
        $website=$row_name->website;
        $email=$row_name->email;

        $queryac = $this->db->query("select max(year_name) as ay from academicyear");
        $rowac = $queryac->row();
        $yearname=$rowac->ay;
        $output.='<div class="row">
            <div class="col-lg-8">
                <div class="text-center">
                  <span class="time text-danger" style="font-family:Rockwell">
                  <h2>'.$school_name.'</h2></span>
                  <span class="time text-info" style="font-family:Rockwell">
                  <h5>Academic Year: '.$yearname.' E.C</h5></span>
                  <span class="text-info" style="font-family:Poor Richard">Address: '.$address.'<br>
                  Website : '.$website.'</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center">
                <span class="time text-danger" style="font-family:Viner Hand ITC">
                <h2>Student Report Card</h2> </span>
                <span class="text-info" style="font-family:Poor Richard">Phone: '.$phone.'<br>Email: '.$email.'</span>
                </div>  
            </div>
        </div>';
      $output.= '<div class="row"><div class="col-lg-6"><h5 class="text-success"><b> Name : '.$row_student->fname.' '.$row_student->mname.' '.$row_student->lname.'</b></h5>
        <div class="table-responsive">
        <table width="100%"  class="tabler table-bordered table-md" cellspacing="5" cellpadding="5">';
      $output.='<tr><th>Subject</th>';
      
      if($max_quarter===trim('Sem2')){
        $query_quarter=$this->db->query(" Select * from quarter where Academic_year='$max_year' group by term order by term ASC ");
      }else{
        $query_quarter=$this->db->query(" Select * from quarter where Academic_year='$max_year' and term='$max_quarter' group by term order by term ASC ");
      }
      
      $output.='<th>Teachers Name</th>';
      foreach ($query_quarter->result() as $qvalue) {
            $output .='<th>'.$qvalue->term.'</th>';
            $output .='<th>TR</th>';
        }
        if($max_quarter===trim('Sem2')){
          $output .='<th>Yearly Average</th>';
        }
        $output.='</tr>';

        $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' 
        and onreportcard='1' group by subject order by subject ASC ");
        foreach ($query_result->result() as $qvalue_result) 
        {
          $subject=$qvalue_result->subject;
            $output .='<tr><td>'.$qvalue_result->subject.'</td>';
            /*Teachers Name Query Starts*/
            $queryTeacher=$this->db->query("select * from staffplacement as staffp cross join users as user where user.username=staffp.staff and staffp.grade='$gradesec' 
            and staffp.academicyear='$max_year' and staffp.subject='$subject' ");
            if($queryTeacher->num_rows()>0){
              $queryRow=$queryTeacher->row();
              $stafFirName=$queryRow->fname;$staffMidName=$queryRow->mname;
              $output.='<td>'.$stafFirName.' '.$staffMidName.'</td>';
            }else{
              $output.='<td>-</td>';
            }
            /*Teachers Name Query End*/
          foreach ($query_quarter->result() as $qvalue) 
          {
            $quarter=$qvalue->term;
              $query_qua_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' 
              and stuid='$stuid' and academicyear='$max_year' and subject='$subject' and quarter ='$quarter' and onreportcard='1' group by subject order by subject ASC ");
              if($query_qua_result->num_rows()>0)
              {
            foreach ($query_qua_result->result() as $quvalue)
            {
                  $letter=$quvalue->letter;
                  $result=$quvalue->total;
                  if($letter!='A')
                  {
                    $output .='<td>'.$result.'</td>';
                  }
                  else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                  }
                }
              }else{
                $output.= "<td> -</td>";
              }
              if($result >= 90 && $result<=100 ){
                $output .='<td>OP</td>';
              }else if($result >= 80 && $result<=89 ){
                $output .='<td>WH</td>';
              }else if($result >= 70 && $result<=79 ){
                $output .='<td>AP</td>';
              }else if($result >= 60 && $result<=69 ){
                $output .='<td>HK</td>';
              }else if($result >= 50 && $result<=59 ){
                $output .='<td>MI</td>';
              }else{
                $output .='<td>NI</td>';
              }
          }
          /*Each subject Yearly average starts
          (Vertically*/
          $subject=$qvalue_result->subject;
          $letter=$qvalue_result->letter;
            $query_sa1_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where subject='$subject' 
            and academicyear='$max_year' and stuid ='$stuid' and quarter='Sem1' or quarter='Sem2' and subject='$subject'  and stuid ='$stuid' group by stuid, subject ");
        foreach ($query_sa1_sub->result() as $row_sa1)
        {
          $rest222=$row_sa1->total_sum/2;
        }
        $query_check_semster2_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where  academicyear='$max_year' 
        and stuid ='$stuid' and quarter='Sem2' and subject='$subject' group by stuid, subject ");
          if($max_quarter===trim('Sem2')){
            if($query_check_semster2_sub->num_rows()>0){

            $yearly_ave=$rest222;
            if($letter!='A'){
              $output .= '<td><B>'.number_format((float)$yearly_ave,2,'.','').'</B></td>';
            }
            else{
                $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $yearly_ave between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td> -</td>";
                      }
            }
          }else{
            $output.= "<td>-</td>";
          }
        }else{
          
        }
          $output .='</tr>'; 
        }
          /*Each Quarter total Starts(Horizontally)*/
          $check_rankAllowed=$this->db->query(" Select gr.grade,ur.grade from rank_allowed_grades as gr cross join users as ur where ur.gradesec='$gradesec' and 
          gr.academicyear='$max_year' and ur.grade=gr.grade ");
          if($check_rankAllowed->num_rows()>0)
          {
            $output .='<tr><td><b>Total</b></td><td></td>';
            foreach ($query_quarter->result() as $qvalue) 
            {
            $quarter=$qvalue->term;
              $query_qua_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' 
              and stuid='$stuid' and academicyear='$max_year'  and quarter ='$quarter' and onreportcard='1' group by quarter order by subject ASC ");
              if($query_qua_total->num_rows()>0)
              {
                foreach ($query_qua_total->result() as $qtvalue){
                  $output .= '<td><B>'.number_format((float)$qtvalue->quarter_total,2,'.','').'</B></td>';
                }
              } else{
                $output .='<td>-</td>';
              }
              $output .='<td>-</td>';
          }
          $query_total_gsq2=$this->db->query("select subject,sum(total) as total2 from reportcard".$gradesec.$max_year."  where onreportcard='1' 
          and academicyear='$max_year' and stuid ='$stuid' and quarter='Sem1' or quarter='Sem2'  and onreportcard='1' and academicyear='$max_year' 
          and stuid ='$stuid' ");
          if(empty($subject)){
            $subject='';
          }
          /*Yearlly average total starts(Horizontally)*/
          $query_check_semster2_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where  
          academicyear='$max_year' and stuid ='$stuid' and quarter='Sem2' and subject='$subject' group by stuid , subject ");
          if($max_quarter===trim('Sem2')){
          if($query_check_semster2_sub->num_rows()>0)
          {
            foreach ($query_total_gsq2->result() as $row_a1)
            {
              $fave1=$row_a1->total2/2;
            }
            $fave_year=$fave1;
              $output .= '<td><B>'.number_format((float)$fave_year,2,'.','').'</B></td>';
          }
          else{
            $output .='<td>-</td>';
          }
        }else{
          //
        }
            $output .='</tr>';
            /*Each Yearlly Average total ends(Horizontally)*/

            /*Each Yearlly Average starts(Horizontally)*/
            $output .='<tr><td><b>Average</b></td><td></td>';
            foreach ($query_quarter->result() as $qvalue) 
            {
            $quarter=$qvalue->term;
            $query_qua_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where 
            grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and onreportcard='1' and quarter ='$quarter' group by quarter order by subject ASC ");
              /*count subject starts*/
              $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' 
              and onreportcard='1' and academicyear='$max_year' and quarter='$quarter' group by subject ");
          $total_subject=$count_subject->num_rows();
          if($query_qua_total->num_rows()>0)
          {
                foreach ($query_qua_total->result() as $qtvalue) {
                  $output .= '<td><B>'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</B></td>';
                }
              } else{
                $output .='<td>-</td>';
              }
              $output .='<td>-</td>';
          }
          if($max_quarter===trim('Sem2')){
            if($query_check_semster2_sub->num_rows()>0)
            {
              if($fave_year>0){
                $output .= '<td><B>'.number_format((float)$fave_year/$total_subject,2,'.','').'</B></td>';
            }else{
              $output .='<td><B>0.00</B></td>';
            }
          }else{
            $output .='<td>-</td>';
          }
        }else{

        }
            $output .='</tr>';
            /*Each Yearlly Average Ends(Horizontally)*/

            /*Each Quarter Rank starts(Horizontally)*/
              $output .='<tr><td><b>Rank</b></td><td></td>'; 
            foreach ($query_quarter->result() as $qvalue)
            {
            $quarter=$qvalue->term;
            $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and 
            stuid ='$stuid' and quarter='$quarter' group by quarter ");
            $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank
            from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$quarter' and onreportcard='1' group by stuid) sm)) as stuRank from 
            reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' group by grade ");
            if($query_total->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                  $output .= '<td><B>'.$qtrank->stuRank.'</B></td>';
                }
              }else{
                $output .= '<td>-</td>';
              }
              $output .='<td>-</td>';
          }
          /*Rank of Yearly Average starts
          (Horizontally)*/
          $query_rankya=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from
          reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." 
          where stuid='$stuid'  and academicyear='$max_year' and grade='$gradesec' and letter='#' and onreportcard='1' group by grade ");
          if($max_quarter===trim('Sem2')){
              if($query_check_semster2_sub->num_rows()>0)
              {
                foreach ($query_rankya ->result() as $row_rankya)
                {
                  $rankNew =$row_rankya->stuRank;
                  $output .= '<td><b>'.$rankNew.'</b></td>';
                }
              } else{
                $output .= '<td>-</td>';
              }
            }else{
                $output .= '<td>-</td>';
            }
          }
          $output .='</tr>';

          /*Each Quarter No of students starts(Vertically)*/
          $output .='<tr><td><b>No. of Student</b></td><td></td>';
          foreach ($query_quarter->result() as $qvalue) 
          {
          $quarter=$qvalue->term;
            $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid' 
            and quarter='$quarter' group by quarter ");
            if($query_total->num_rows()>0)
            {
          $total_student=$query_student->num_rows();
          $output .= '<td><B>'.$total_student.'</B></td>';
        } else{
          $output .= '<td>-</td>';
        }
        $output .='<td>-</td>';
      }

        /*No of student of Yearly Average starts
        (Horizontally)*/
        if($max_quarter===trim('Sem2')){
          if($query_check_semster2_sub->num_rows()>0)
          {
            $output .= '<td><B>'.$total_student.'</B></td>';
          }else{
            $output .= '<td>-</td>';
          }
        }else{

        }
        /*No of student of Yearly Average ends
        (Horizontally)*/
          $output .='</tr>';
          $output .='<tr><td><b>Conduct</b></td><td></td>';
          foreach ($query_quarter->result() as $qvalue) {
          $quarter=$qvalue->term;
          $each_quarter_basicskill=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and
          quarter='$quarter' and bsname='Conduct' group by stuid ");
          if($max_quarter===trim('Sem2')){
            if($each_quarter_basicskill->num_rows()>0){
              foreach ($each_quarter_basicskill->result() as $keue) 
              {
                $output .= '<td><B>'.$keue->value.'</B></td>';
              }
            }else{
              $output .= '<td>-</td>';
            }
          }else{

          }
          $output .='<td>-</td>';
        }
        $output .= '<td>-</td>';
          $output .='</tr>';
          $output .='<tr><td><b>No. of Absence</b></td><td></td>';
          foreach ($query_quarter->result() as $qvalue) {
          $quarter=$qvalue->term;
          $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where attt.stuid='$stuid' and attt.absentdate between se.startdate and se.endate and se.term='$quarter' and attt.academicyear='$max_year' ");
          foreach ($query_total_absent->result() as $absent)
          {
          if($absent->att>0)
          {
            $output .= '<td><B>'.$absent->att.'</B></td>';
              }
              else{
              $output .= '<td><B>-</B></td>';
            }
        }
        $output .='<td>-</td>';
        }
        $query_total_absent2=$this->db->query("select count(stuid) as att from attendance where  stuid='$stuid' and academicyear='$max_year' ");
        if($max_quarter===trim('Sem2')){
          if($query_check_semster2_sub->num_rows()>0)
          {
          foreach ($query_total_absent2 ->result() as $row_absent)
          {
            if($row_absent->att>0){
              $output .= '<td><B>'.$row_absent->att.'</B></td>';
            }else{
              $output .= '<td><B>-</B></td>';
            }
          }
        }else{
          $output .= '<td>-</td>';
        }
      }else{

      }
          $output .='</tr>';
      $output .='</table></div></div>';
      
        /*Basic Skill starts here*/
      $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' order by bsname ASC ");
      $output.= '<div class="col-lg-1"></div><div class="col-lg-5">
      <h5 class="text-success"><b>  Grade : '.$row_student->gradesec.'</b></h5>
          <div class="table-responsive">
          <table width="100%"  class="table-bordered table-md" cellspacing="5" cellpadding="5">';
       $output .='<th>Teachers Remark(TR)</th>';
       $output .='<th>Code</th>';
       $output .='<tr><td>Actively Participates</td><td>AP</td></tr>';
       $output .='<tr><td>Cheating on Test</td><td>CH</td></tr>';
       $output .='<tr><td>Complete work on time</td><td>CW</td></tr>';
       $output .='<tr><td>Cutting class</td><td>CT</td></tr>';
       $output .='<tr><td>Damage school Property</td><td>DP</td></tr>';
       $output .='<tr><td>Disturbs in class</td><td>DC</td></tr>';
       $output .='<tr><td>Has acquired good knowledge of the subject
       </td><td>HK</td></tr>';
       $output .='<tr><td>Incomplete tests/assignments</td><td>IT</td></tr>';
       $output .='<tr><td>Irregular work habits</td><td>IW</td></tr>';
       $output .='<tr><td>Made improvments this quarter</td><td>MI</td></tr>';
       $output .='<tr><td>Need improvment in His/Homework</td><td>NI</td></tr>';
       $output .='<tr><td>Often late to school</td><td>OL</td></tr>';
       $output .='<tr><td>Outstanding Progress</td><td>OP</td></tr>';
       $output .='<tr><td>Very good work habits</td><td>WH</td></tr>';
       $output .='<tr><td>Well behaved student</td><td>WB</td></tr>';
      /*foreach ($query_quarter->result() as $qvalue) 
      {
            $output .='<th>'.$qvalue->Code'</th>';
        }*/
    /*  foreach ($query_basicskill->result() as $bsvalue) {
          $bsname=$bsvalue->bsname;
        $output .='<tr><td>'.$bsvalue->bsname.'</td>';
        foreach ($query_quarter->result() as $qvalue) {
            $quarterbs=$qvalue->term;
          $query_bsvalue=$this->db->query(" Select * from basicskillvalue where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$quarterbs' ");
            $output .='<td>AP</td>';
            $output .='<td>CH</td>';
            $output .='<td>CW</td>';
            $output .='<td>CT</td>';
            $output .='<td>DP</td>';
            $output .='<td>DC</td>';
            $output .='<td>HK</td>';
            $output .='<td>IT</td>';
            $output .='<td>IW</td>';
            $output .='<td>MI</td>';
            $output .='<td>NI</td>';
            $output .='<td>OL</td>';
            $output .='<td>OP</td>';
            $output .='<td>WH</td>';
            $output .='<td>WB</td>';
        /*  if($query_bsvalue->num_rows()>0) {
            foreach ($query_bsvalue ->result() as $bsresult) {
              $output .='<td>'.$bsresult->value.'</td>';
              $output .='<td>'.$bsresult->value.'</td>';
              $output .='<td>'.$bsresult->value.'</td>';
              $output .='<td>'.$bsresult->value.'</td>';
              $output .='<td>'.$bsresult->value.'</td>';
              $output .='<td>'.$bsresult->value.'</td>';
            }
          }else {
            $output .='<td>-</td>';
          }*/
      //  } 
        //$output .='</tr>';
      //}
      /*$query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' order by bsname ASC ");
      $output.= '<div class="col-lg-4">
          <div class="table-responsive">
          <table width="100%"  class="table-bordered table-md" cellspacing="5" cellpadding="5">';
       $output .='<th>Basic Skill</th>';
      foreach ($query_quarter->result() as $qvalue) 
      {
            $output .='<th>'.$qvalue->term.'</th>';
        }
      foreach ($query_basicskill->result() as $bsvalue) {
          $bsname=$bsvalue->bsname;
        $output .='<tr><td>'.$bsvalue->bsname.'</td>';
        foreach ($query_quarter->result() as $qvalue) {
            $quarterbs=$qvalue->term;
          $query_bsvalue=$this->db->query(" Select * from basicskillvalue where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='$quarterbs' ");
          if($query_bsvalue->num_rows()>0) {
            foreach ($query_bsvalue ->result() as $bsresult) {
              $output .='<td>'.$bsresult->value.'</td>';
            }
          }else {
            $output .='<td>-</td>';
          }
        } 
        $output .='</tr>';
      }*/
      /*$queryac = $this->db->query("select max(year_name) as ay from academicyear");
        $rowac = $queryac->row();
        $yearname=$rowac->ay;*/
      $output .='</table></div></div></div><br>';
      $output.='<div class="row"><div class="col-lg-8">
      <span class="time" style="font-family:Poor Richard">Name & Sig. of H.R. Teacher. ________________________.<br>Promoted To Grade_____________.  Detained In Grade_____________.</span>
      </div><div class="col-lg-4"> 
      <span class="time pull-right" style="font-family:Curlz MT">The school seal.</span><br>
      <span class="time" style="font-family:Poor Richard">Principals Sig. _______________.<br>
      </span>
      </div></div><br>';
    }
    return $output;
  }
  function roster($max_year,$gradesec,$branch)
  {
    $query_student=$this->db->query(" Select * from users where gradesec='$gradesec' and branch='$branch' and status='Active' and academicyear='$max_year' group by id order by fname ASC ");
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

    $output=' <div class="text-center">
       <span class="time" style="font-family:Poor Richard"> <h2><b>'.$school_name.' Students Roster</b> <br></h2><h3><b>
      Academic Year : '.$yearname.' E.C</b></h3>
      <h5> <b> Grade : <u>'.$grade_name.'</u> Section: <u>'.$section_name.'</u></b></h5>
      <h6> <b> Home Room Teachers Name :__________________.</b></h6>
      </span> </b>
    </div>';
    $output .='<div class="table-responsive">
            <table class="table table-bordered table-hover" id="rotate_gs" style="width:100%;">
            <tbody><tr> <th> No.</th>
            <th>Students Name</th> <th>Sex</th> <th>Age</th>
            <th>Semester</th>';
      $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' group by subject order by subject ASC ");
      foreach ($query_result ->result() as $rvalue)
      {
          $output .=' <th>'.$rvalue->subject.'</th>';
      }
      $output .='<th> Total</th> ';
      $output .='<th> Average</th> ';
      $output .='<th> Rank</th> ';
      $output .='<th> No of Student</th> ';
      $output .='<th> Conduct</th> ';
      $output .='<th> Absence Date</th> ';
      $output .='<th> Promoted</th> ';
      $output .='<th> Detained</th> ';
      $output .='<th> Remark</th> ';
      $output .='</tr>';
      $stu_no=1;
      foreach ($query_student->result() as $row) 
      {
        $stuid=$row->id;
        $grade=$row->grade;
        $grade_sec=$row->gradesec;
        $query_quarter=$this->db->query(" Select * from quarter where Academic_year='$max_year' group by term order by term ASC ");
        $output .='<tr><td rowspan="4">'.$stu_no.'.</td>
            <td rowspan="4"><b>'.$row->fname.' '.$row->mname.' '.$row->lname.'</b></td> 
            <td rowspan="4">'.$row->gender.'</td>
            <td rowspan="4">-</td>';//$row->age
          foreach ($query_quarter->result() as $qvalue) 
          {
          $quarter=$qvalue->term;
          $output .='<tr> <td><B>'.$qvalue->term.'</B></td>';
          foreach ($query_result ->result() as $subname)
          {
            $subject_mark=$subname->subject;
              $query_quarter_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' 
              and quarter ='$quarter' and subject='$subject_mark' group by subject ");
              if($query_quarter_result->num_rows()>0){
                foreach ($query_quarter_result->result() as $quarter_result) 
                {
                  $letter=$quarter_result->letter;
                  $result=$quarter_result->total;
                  if($letter!='A')
                  {
                    $output .='<td>'.$result.'</td>';
                  }
                else{
                  $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
                }
                }
              }else{
                $output.= "<td> -</td>";
              }
            }
            $query_quarter_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' 
            and academicyear='$max_year'  and quarter ='$quarter' and onreportcard='1' and letter='#' group by quarter order by subject ASC ");
            $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and onreportcard='1' and academicyear='$max_year' 
            and quarter='$quarter' and letter='#' group by subject ");
            $total_subject=$count_subject->num_rows();
            if($query_quarter_total->num_rows()>0){
              foreach ($query_quarter_total->result() as $qtvalue)
              {
                $output .= '<td><B>'.number_format((float)$qtvalue->quarter_total,2,'.','').'</B></td>';//Each quarter Total
                $output .= '<td><B>'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</B></td>';//Each quarter Average
              }
            }else{
              $output .= '<td><B>-</B></td>';//Each quarter total
              $output .= '<td><B>-</B></td>';//Each quarter Average
            }
            $check_rankAllowed=$this->db->query(" Select gr.grade,ur.grade from rank_allowed_grades as gr cross join users as ur where ur.gradesec='$gradesec' and 
            gr.academicyear='$max_year' and ur.grade=gr.grade ");
            $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank 
            from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' and quarter='$quarter' group by stuid) sm)) as stuRank from 
            reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' and onreportcard='1' group by grade ");
            if($check_rankAllowed->num_rows()>0){
              if($query_rank->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                  $output .= '<td><B>'.$qtrank->stuRank.'</B></td>';//Each Quarter Rank
                }
              }else{
                $output .= '<td><B>-</B></td>';//Each Quarter Rank
              }
            }else{
              if($query_rank->num_rows()>0){
                foreach ($query_rank->result() as $qtrank)
                {
                  $output .= '<td><B>-</B></td>';//Each Quarter Rank
                }
              }else{
                $output .= '<td><B>-</B></td>';//Each Quarter Rank
              }
            }
            $total_student=$query_student->num_rows();
        $output .= '<td><B>'.$total_student.'</B></td>';//Each quarter Number of student
        $each_quarter_basicskill=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' 
        and quarter='$quarter' and bsname='Conduct' group by stuid ");
        if($each_quarter_basicskill->num_rows()>0)
        {
            foreach ($each_quarter_basicskill->result() as $keue) 
            {
              $output .= '<td><B>'.$keue->value.'</B></td>';//Each Quarter conduct
            }
          }else{
            $output .= '<td><B>-</B></td>';//Each Quarter conduct
          }
          $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where attt.stuid='$stuid' and attt.absentdate between se.startdate and se.endate and se.term='$quarter' and attt.academicyear='$max_year' ");
          foreach ($query_total_absent->result() as $absent)
          {
          if($absent->att>0)
          {
            $output .= '<td><B>'.$absent->att.'</B></td>';//Each quarter Absent date
              }
              else{
              $output .= '<td><B>-</B></td>';//Each quarter Absent date
            }
        }
        $output .= '<td><B>-</B></td>';//Promoted
        $output .= '<td><B>-</B></td>';//Detained
        $output .= '<td><B>-</B></td>';//Remark
            $output .='</tr>';
          }
          $output .='<td><B>Yearly Average</B></td>';
          $yearly_ave_total=0;
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->subject;
            $query_quarter_result=$this->db->query(" Select *,sum(total) as yearly_average from reportcard".$gradesec.$max_year." where grade='$gradesec' 
            and stuid='$stuid' and academicyear='$max_year' and subject='$subject_mark' and onreportcard='1' group by subject ");
            $num_term=2;
          if($query_quarter_result->num_rows()>0){
            foreach ($query_quarter_result->result() as $quarter_result) 
            {
              $letter=$quarter_result->letter;
              $result=$quarter_result->yearly_average/ $num_term;
              if($letter!='A')
              {
                $output .='<td>'.$result.'</td>';
              }
              else{
                  $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
                      }
              } 
            }
          }else{
            $output.= "<td> -</td>";
          }
        }
          $query_quarter_result=$this->db->query(" Select *,sum(total) as yearly_average from reportcard".$gradesec.$max_year."
          where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and onreportcard='1' and letter='#' ");
        $num_term=2;
        $query_check_sem2=$this->db->query("Select * from reportcard".$gradesec.$max_year."
        where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and quarter='Sem2' ");
        if($query_check_sem2->num_rows()>0){
          foreach ($query_quarter_result->result() as $quarter_result) 
          {
              $yearly_ave_total=$quarter_result->yearly_average /2;
              $output .= '<td><B>'.number_format((float)$yearly_ave_total,2,'.','').'</B></td>';//Each total Average
              $output .= '<td><B>'.number_format((float)$yearly_ave_total / $total_subject,2,'.','').'</B></td>';//Each  Average yearly Average
          }
        }else{
          $output .= '<td><B>-</B></td>';//Each total Average
            $output .= '<td><B>-</B></td>';//Each  Average yearly Average
        }
          $query_rankya=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from 
          reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' 
          and academicyear='$max_year' and grade='$gradesec' and letter='#' and onreportcard='1' group by grade ");
        if($check_rankAllowed->num_rows()>0){
          if($query_rank->num_rows()>0){
            foreach ($query_rankya ->result() as $row_rankya)
            {
              $rankNew =$row_rankya->stuRank;
              $output .= '<td><b>'.$row_rankya->stuRank.'</b></td>';//Yearly Average Rank
            }
          }else{
            $output .= '<td><B>-</B></td>';//Each Quarter Rank
          }
        }else{
          if($query_rank->num_rows()>0){
            foreach ($query_rankya ->result() as $row_rankya)
            {
              $rankNew =$row_rankya->stuRank;
              $output .= '<td><b>-</b></td>';//Yearly Average Rank
            }
          }else{
            $output .= '<td><B>-</B></td>';//Each Quarter Rank
          }
        }
      $output .= '<td><B>'.$total_student.'</B></td>';
      //Yearly Average Number of student
      $output .= '<td>-</td>';
      //Yearly Average conduct
      $query_total_absent2=$this->db->query("select count(stuid) as att from attendance where  stuid='$stuid' and academicyear='$max_year' ");
      foreach ($query_total_absent2 ->result() as $row_absent)
      {
        if($row_absent->att>0){
          $output .= '<td><B>'.$row_absent->att.'</B></td>';//Yearly Absent Date
        }else{
          $output .= '<td><B>-</B></td>';//Yearly Absent Date
        }
      }
      $output .= '<td><B>-</B></td>'; //Promoted
      $output .= '<td><B>-</B></td>'; //Detained
      $output .= '<td><B>-</B></td>'; //Remark
          $output .='</tr>';
          $stu_no++;
      }
      $output .=' </tbody> </table> </div>';
      $output .='<div class="row">
      <div class="col-md-1"></div>
      <div class="col-md-5"><span class="time" style="font-family:Poor Richard"><B>
      Home Room Teachers Signature .______________________.<br>
      Date._____________________.</B> </span></div>
      <div class="col-md-5"> <span class="time" style="font-family:Poor Richard"><B>
      Directors Signature._____________________. <br>
      Date.________________________.</B></span> </div>
      <div class="col-md-1"></div>
      </div>';
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
      <h5><b> Campus : '.$branch.'(Kaliti) </b></h5>
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
    <th>Gender</th> 
    <th>Season</th>';
    $query_result=$this->db->query(" Select * from subject where Grade='$grade_name' and Academic_Year='$max_year' group by Subj_name order by suborder ");
    foreach ($query_result ->result() as $rvalue)
    {
      $output .=' <th>'.$rvalue->Subj_name.'</th>';
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
      <td>'.$row->gender.'</td>';
        $output .=' <td><B>'.$quarter.'</B></td>';
        foreach ($query_result ->result() as $subname)
        {
          $subject_mark=$subname->Subj_name;
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
        $query_quarter_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and quarter ='$quarter' and onreportcard='1' and mergedname='' group by quarter order by subject ASC ");
        $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
        $total_subject=$count_subject->num_rows();
        if($query_quarter_total->num_rows()>0){
          foreach ($query_quarter_total->result() as $qtvalue)
          {
            $output .= '<td class="text-center"><B>'.number_format((float)$qtvalue->quarter_total,2,'.','').'</B></td>';//Each quarter Total
            $output .= '<td class="text-center"><B>'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</B></td>';//Each quarter Average
          }
        }else{
          $output .= '<td class="text-center"><B>-</B></td>';//Each quarter total
          $output .= '<td class="text-center"><B>-</B></td>';//Each quarter Average
        }
        
        $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter='#' and onreportcard='1' and mergedname='' and quarter='$quarter' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and rpbranch='$branch' and academicyear='$max_year' and letter='#' and onreportcard='1' and mergedname='' and grade='$gradesec' group by grade ");
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
        $query_total_absent=$this->db->query("select count(stuid) as att from attendance as attt cross join quarter as se where attt.stuid='$username1' and attt.absentdate between se.startdate and se.endate and se.term='$quarter' and attt.academicyear='$max_year' and se.Academic_year='$max_year' ");
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
  function transcriptCheck($academicyear,$gradesec,$branch)
  {
    $output='';
    $queryTr=$this->db->query("select fname,mname,lname,gender,gradesec,age,username, unique_id, grade, id, profile from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and status='Active' and isapproved='1' and academicyear='$academicyear' order by fname,mname,lname ASC ");
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
            case '11NS':
              $gradeName='ELEVEN';
              break;
            case '11SS':
              $gradeName='ELEVEN';
              break;
            case '12':
              $gradeName='ELEVEN';
              break;
            case '12NS':
              $gradeName='TWELVE';
              break;
            case '12SS':
              $gradeName='TWELVE';
              break;
            default:
              $gradeName='-';
              break;
          }
          $output.='<div style="width:100%;height:92%;page-break-inside:avoid;">';
          $query_name = $this->db->query("select * from school");
          $row_name = $query_name->row();
          $school_name=$row_name->name;
          $schooLogo=$row_name->logo;
          $output.='<div class ="row" id="ENS">
            <div class="col-lg-5 col-5 text-center">
              <h4>ሲያትል አካዳሚ <h4>
              <h4><b>ከአፀደ-ህፃናት - ኮሌጅ መሰናዶ</b></h4>
            </div>
            <div class="col-lg-2 col-2 text-center">
              <img class="text-center" src="'.base_url().'/logo/'.$schooLogo.'" style="height:80px;width:80px;border:1px solid #fff;" >
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
                  <p class="my-1">GENDER: <u>'.$stuName->gender.' </u></p>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-4">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <p class="my-1">Grade: <u>'.$stuName->gradesec.'</u></p> ';
                  if($stuName->age>0){
                    $output.='<p class="my-1">Age: <u>'.$stuName->age.' </u></p>';
                  }else{
                    $output.='<p class="my-1">Age: _____</p>';
                  }
                  $output.='
                </div>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table width="100%" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">
              <tbody><tr> 
              <th class="text-center">ACADEMIC YEAR</th>';
              /*Even Grades calculation starts*/
              if($currGrade == trim('12NS') || $currGrade == trim('12SS') || $currGrade =='10' || $currGrade == trim('12') ){
                $targetYear=$academicyear - 1;
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
                $output.='<th colspan="3" class="text-center">'.$gmax_year .' G.C | ' .$targetYear.' E.C </th> 
                <th colspan="3" class="text-center">'.$Agmax_year.' G.C | '.$academicyear.' E.C </th></tr>
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
                      case '11NS':
                        $laGradeName='ELEVEN';
                        break;
                      case '11SS':
                        $laGradeName='ELEVEN';
                        break;
                      case '12':
                        $laGradeName='TWELVE';
                        break;
                      case '12NS':
                        $laGradeName='TWELVE';
                        break;
                      case '12SS':
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
                    $querySubjecy=$this->db->query("select * from subject where Academic_Year='$academicyear' and Grade='$currGrade' and letter='#' and onreportcard='1' or Academic_Year='$targetYear' and Grade='$lastGr' and letter='#' and onreportcard='1' group by Subj_name order by suborder ");

                    if($querySubjecy->num_rows()>0){
                      $countSubjectLast=$this->db->query("select * from reportcard".$lastGrsec.$targetYear." where grade='$lastGrsec' and academicyear='$targetYear' and
                      rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
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
                        /*Sem1*/
                        /*for selected odd grade*/
                        $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem1' and onreportcard='1' and letter='#' and subject='$subjName'");
                        foreach ($quartrSem1Total->result() as $totalValueSem1) {
                          $printValueSem1=$totalValueSem1->total;
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Sem2*/
                         
                        /*for selected odd grade*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem2' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=($totalValueSem1->total);
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
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1Ave,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*for future even grade*/
                        $quartrSem1TotalEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem1TotalEven->result() as $totalValueSem1Even) {
                          $printValueSem1Even=($totalValueSem1Even->total);
                          if($printValueSem1Even >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1Even,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*for future even grade*/
                        $quartrSem2TotalFuEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem2TotalFuEven->result() as $totalValueSem2FuEven) {
                          $totalValueSem2FuEven=($totalValueSem2FuEven->total);
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
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                         
                      }
                      /*GrandTotal*/
                      $output.='<tr><td>GRAND TOTAL</td>';
                      
                      /*Grandtotal 4 1st Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem1' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2TotalFuGrade->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartryearCurGradeTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartryearCurGradeTotal->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $output.='<tr><td>AVERAGE</td>';
                      /*Grandtotal 4 1st Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2TotalFuGrade->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartryearCurGradeTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartryearCurGradeTotal->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                    }
                  }
                }else{ 
                  /*Calculating mark current academic year with even grade and previous grade not exists*/
                  $querySubjecy=$this->db->query("select * from reportcard".$gradesec.$academicyear." where academicyear='$academicyear' and grade='$gradesec' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                  if($querySubjecy->num_rows()>0){
                    $totalSubjects=$querySubjecy->num_rows() + 3;
                  }else{
                     $totalSubjects=0;  
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
                      /*Sem1*/
                      

                      /*$output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';*/
                      $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' and subject='$subjName'");

                      foreach ($quartrSem1Total->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Sem2*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' and subject='$subjName' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
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
                            $output.='<td class="text-center">-</td>';
                          /*$output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';*/
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                    }
                    /*GrandTotal*/
                    $output.='<tr><td>GRAND TOTAL</td>';

                    /*$output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';*/
                    $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    $subALl=$countSubject->num_rows();
                    /*Grandtotal 4 1st Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=($totalValueSem1->total);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 2nd Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=($totalValueSem1->total);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 YearlyAverage Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
                      if($printValueSem1 >0){
                          $output.='<td class="text-center">-</td>';
                        /*$output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';*/
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }

                    /*GrandTotal*/
                    $output.='<tr><td>AVERAGE</td>';

                    /*$output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';*/
                    $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    $subALl=$countSubject->num_rows();
                    /*Grandtotal 4 1st Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=($totalValueSem1->total);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 2nd Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=($totalValueSem1->total);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 YearlyAverage Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>-</b></td>';
                        /*$output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';*/
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                  }
                }
                /*........................................................................*/
              }else if($currGrade == trim('11NS') || $currGrade == trim('11SS') || $currGrade =='9' || $currGrade == '11'){
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

                $output.='<th colspan="3" class="text-center"><b>'.$Agmax_year.' G.C | '.$academicyear.' E.C </b></th>
                <th colspan="3" class="text-center"><b>'.$gmax_year.' G.C | '.$targetYear.' E.C </b></th> </tr>
                <tr> 
                <th class="text-center"><b>GRADE</b></th>';
                $queryLastGrade=$this->db->query("select * from users where academicyear ='$targetYear' and username='$uniqueId' and status='Active' and isapproved='1' and usertype='Student' group by id ");
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
                      case '11NS':
                        $laGradeName='ELEVEN';
                        break;
                      case '11SS':
                        $laGradeName='ELEVEN';
                        break;
                      case '12':
                        $laGradeName='TWELVE';
                        break;
                      case '12NS':
                        $laGradeName='TWELVE';
                        break;
                      case '12SS':
                        $laGradeName='TWELVE';
                        break;
                      default:
                        $laGradeName='-';
                        break;
                    }
                    $output.='<th colspan="3" class="text-center">'.$gradesec.'('.$gradeName.')</th>
                    <th colspan="3" class="text-center"><b>'.$lastGrsec.'('.$laGradeName.')</b></th>
                    <tr><th rowspan="2" class="text-center">SUBJECT</th>
                    <th colspan="3" class="text-center">SEMESTER</th>
                    <th colspan="3" class="text-center">SEMESTER</th></tr>
                    <th class="text-center">I</th>
                    <th class="text-center">II</th>
                    <th class="text-center">AV</th>
                    <th class="text-center">I</th>
                    <th class="text-center">II</th>
                    <th class="text-center">AV</th></tr>';
                    $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$academicyear."' ");
                   
                     $querySubjecy=$this->db->query("select * from subject where Academic_Year='$academicyear' and Grade='$currGrade' and letter='#' and onreportcard='1' or Academic_Year='$targetYear' and Grade='$lastGr' and letter='#' and onreportcard='1' group by Subj_name order by suborder ");
                     if ($queryCheck->num_rows()>0)
                    {
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
                        /*Sem1*/
                      
                        $quartrSem1TotalEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem1TotalEven->result() as $totalValueSem1Even) {
                          $printValueSem1Even=($totalValueSem1Even->total);
                          if($printValueSem1Even >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1Even,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        $quartrSem2TotalFuEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem2TotalFuEven->result() as $totalValueSem2FuEven) {
                          $totalValueSem2FuEven=($totalValueSem2FuEven->total);
                          if($totalValueSem2FuEven >0){
                            $output .= '<td class="text-center">'.number_format((float)$totalValueSem2FuEven,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#' and subject='$subjName'  ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=($totalValueSem1->total);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1/2,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                         /*for selected odd grade*/
                        $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem1' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem1Total->result() as $totalValueSem1) {
                          $printValueSem1=($totalValueSem1->total);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Sem2*/
                         
                        /*for selected odd grade*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem2' and onreportcard='1' and letter='#' and subject='$subjName'");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=($totalValueSem1->total);
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }

                        /*YearlyAverage*/
                        
                        $quartrSem2Totalver=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#' and subject='$subjName'  ");
                        foreach ($quartrSem2Totalver->result() as $totalValueSem1Ave) {
                          $printValueSem1Ave=($totalValueSem1Ave->total);
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
                      
                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2TotalFuGrade->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartryearCurGradeTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartryearCurGradeTotal->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }

                      $output.='<tr><td>AVERAGE</td>';                   

                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2TotalFuGrade->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartryearCurGradeTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartryearCurGradeTotal->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALast,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
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
                      /*Sem1*/
                      $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' and subject='$subjName'");
                      foreach ($quartrSem1Total->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Sem2*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' 
                      and quarter='Sem2' and onreportcard='1' and letter='#' and subject='$subjName' ");

                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=($totalValueSem1->total);
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
                          $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*$output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';*/
                    }
                    /*GrandTotal*/
                    $output.='<tr><td>GRAND TOTAL</td>';
                    
                    $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and
                    rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    $subALl=$countSubject->num_rows();
                    /*Grandtotal 4 1st Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' 
                    and quarter='Sem1' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=($totalValueSem1->total);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 2nd Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and 
                    quarter='Sem2' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=($totalValueSem1->total);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 YearlyAverage Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and 
                    onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center">'.number_format((float)$printValueSem1,2,'.','').'</td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*$output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';*/
                    $output.='<tr><td>AVERAGE</td>';
                    
                    /*Grandtotal 4 1st Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' 
                    and quarter='Sem1' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=($totalValueSem1->total);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 2nd Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=($totalValueSem1->total);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 YearlyAverage Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center">'.number_format((float)$printValueSem1/$subALl,2,'.','').'</td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*$output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';*/
                  }
                }
              }
              $output.='
              </tbody>
            </table>
          </div>

          <div class ="row" id="ENS">
            <div class="col-lg-12 col-12">
              <div class="support-ticket media pb-1 mb-3">
                <div class="media-body ml-3">
                  <p class="my-1">LAST GRADE ATTENDED IN WORD:<u>'.$gradeName.'</u></p>';
                 /* $queryInfo=$this->db->query("select * from leavingreasoninfo where stuid='$username' and academicyear='$academicyear' ");
                  if($queryInfo->num_rows()>0){
                    $rowInfo=$queryInfo->row();
                    $reasonIssue=$rowInfo->reasoname;
                    $output.='<p class="my-1">REASON FOR ISSUE:<u>'.$reasonIssue.'</u></p>';
                  }else{
                    $output.='<p class="my-1">REASON FOR ISSUE:<u>Completed Grade '.$gradeName.'</u></p>';
                  }*/
                  $output.='
                </div>
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
    }
    return $output;
  }
  function transcript($academicyear,$gradesec,$branch)
  {
    $output='';
    $queryTr=$this->db->query("select fname,mname,lname,gender,gradesec,age, unique_id,grade,id from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and status='Active' and isapproved='1' and academicyear='$academicyear' order by fname,mname,lname ASC ");
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
        foreach ($queryTr->result() as $stuName) {
          $uniqueId=$stuName->unique_id;
          $currGrade=trim($stuName->grade);
          $currID=$stuName->id;
          $stuid=$stuName->id;
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
          $row_name = $query_name->row();//school info
          $school_name=$row_name->name;
          $address=$row_name->address;
          $phone=$row_name->phone;
          $website=$row_name->website;
          $email=$row_name->email;
          $logo=$row_name->logo;
          $output.='<div style="width:100%;height:auto;page-break-inside:avoid;">

          <div class ="row" id="ENS">
          
          <div class="col-lg-1"></div>
          <div class="col-lg-10">
            <img src="'.base_url().'/logo/'.$logo.'" style="float:left" >
                    
                      <div class="text-center"><h1><B>'.$school_name.'</B></h1></div>
                      <div class="text-center"> <h2><B>'.$address.'</B></h2></div>
                      <div class="text-center"><h3><B>Phone: '.$phone.'</B></h3></div>
                      <div class="text-center"><h4><B>Email: '.$email.'</B></h4></div>
                    
                 
            </div>
            <div class="col-lg-1"></div>
            </div>
          
          <div class ="row" id="ENS">
          <div class="col-lg-1"></div>
         <div class="col-lg-8 text-center">
          <h3><B><u>STUDENTS TRANSCRIPT</u></B></h3>
         </div>
         <div class="col-lg-2"></div>
         </div>
         <div class ="row" id="ENS">
         <div class="col-lg-1"></div>
            <div class="col-lg-8">
            <div class ="row">
                <div class="col-lg-7">
                  <h5><b>NAME: <u>'.$stuName->fname.' '.$stuName->mname.' '.$stuName->lname.'</u></b></h5>
                  <h5><b>GENDER: <u>'.$stuName->gender.' </u></b></h5>
                </div>
                <div class="col-lg-5">
              <h5><b>GRADE: <u>'.$stuName->gradesec.' </u></b></h5>';
              if($stuName->age=='' || $stuName->age=='0'){
                  $output.='  <h5><b>AGE: ______</b></h5>';
              }else{
                 $output.='  <h5><b>AGE: <u>'.$stuName->age.' </u></b></h5>'; 
              }
            $output.='<h5><b>Issuance Date: '.date('M,d,Y').'</b></h5></div></div></div>
            <div class="col-lg-3"><div class ="row">';
            foreach($queryKey->result() as $keyVal){
              $output.='<div class="col-lg-6">';
              $output.=''.$keyVal->minValue.'-'.$keyVal->maxiValue.'='.$keyVal->letterVal.'';
              $output.='</div>';
            }
            $output.='</div></div>
          </div>
          <div class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">
              <tbody><tr> 
              <th class="text-center"><b>ACADEMIC YEAR</b></th>';
              /*Even Grades calculation starts*/
              if($currGrade == trim('12n') || $currGrade == trim('12s') || $currGrade%2=='0'){
                $targetYear=$academicyear - 1;
                $queryGreYear=$this->db->query("select gyear from academicyear where year_name='$targetYear' ");
                $rowG = $queryGreYear->row();
                $gmax_year=$rowG->gyear;

                $queryAGreYear=$this->db->query("select gyear from academicyear where year_name='$academicyear' ");
                $rowAG = $queryAGreYear->row();
                $Agmax_year=$rowAG->gyear;
                $output.='<th colspan="3" class="text-center"><b>'.$gmax_year .'|' .$targetYear.' E.C </b></th> 
                <th colspan="3" class="text-center"><b>'.$Agmax_year.' | '.$academicyear.' E.C </b></th></tr>
                <tr> 
                <th class="text-center"><b>GRADE</b></th>';
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
                    $output.='<th colspan="3" class="text-center"><b>'.$lastGrsec.'('.$laGradeName.')</b></th><th colspan="3" class="text-center"><b>'.$gradesec.'('.$gradeName.')</b></th>
                    <tr><th rowspan="2" class="text-center"><b>SUBJECT</b></th>
                    <th colspan="3" class="text-center"><b>SEMESTER</b></th>
                    <th colspan="3" class="text-center"><b>SEMESTER</b></th></tr>
                    <th class="text-center"><b>I</b></th>
                    <th class="text-center"><b>II</b></th>
                    <th class="text-center"><b>AV</b></th>
                    <th class="text-center"><b>I</b></th>
                    <th class="text-center"><b>II</b></th>
                    <th class="text-center"><b>AV</b></th></tr>';
                    $querySubjecy=$this->db->query("select * from reportcard".$gradesec.$academicyear." where academicyear='$academicyear' and grade='$gradesec' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    if($querySubjecy->num_rows()>0){
                      foreach ($querySubjecy->result() as $subjValue) {
                        $subjName=$subjValue->subject;
                        $output.='<tr><td><b>'.$subjValue->subject.'</b></td>';
                        /*Sem1*/
                        /*for selected odd grade*/
                        $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem1' and onreportcard='1' and letter='#' and subject='$subjName'");
                        foreach ($quartrSem1Total->result() as $totalValueSem1) {
                          $printValueSem1=$totalValueSem1->total;
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Sem2*/
                         
                        /*for selected odd grade*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem2' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=$totalValueSem1->total;
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }

                        /*YearlyAverage*/
                        
                        $quartrSem2Totalver=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#' and subject='$subjName'  ");
                        foreach ($quartrSem2Totalver->result() as $totalValueSem1Ave) {
                          $printValueSem1Ave=$totalValueSem1Ave->total;
                          if($printValueSem1Ave >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1Ave/2,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*for future even grade*/
                        $quartrSem1TotalEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem1TotalEven->result() as $totalValueSem1Even) {
                          $printValueSem1Even=$totalValueSem1Even->total;
                          if($printValueSem1Even >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1Even,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*for future even grade*/
                        $quartrSem2TotalFuEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem2TotalFuEven->result() as $totalValueSem2FuEven) {
                          $totalValueSem2FuEven=$totalValueSem2FuEven->total;
                          if($totalValueSem2FuEven >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$totalValueSem2FuEven,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#' and subject='$subjName'  ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=$totalValueSem1->total;
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/2,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                         
                      }
                      /*GrandTotal*/
                      $output.='<tr><td><b>GRAND TOTAL</b></td>';
                      $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                      $subALl=$countSubject->num_rows();
                      /*Grandtotal 4 1st Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2TotalFuGrade->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartryearCurGradeTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartryearCurGradeTotal->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $output.='<tr><td><b>AVERAGE</b></td>';
                      $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                      $subALl=$countSubject->num_rows();
                      /*Grandtotal 4 1st Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2TotalFuGrade->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartryearCurGradeTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartryearCurGradeTotal->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                    }
                  }
                }else{
                  $output.='<th colspan="3" class="text-center"><b> - </b></th><th colspan="3" class="text-center"><b>'.$gradesec.'('.$gradeName.')</b></th>
                  <tr><th rowspan="2" class="text-center"><b>SUBJECT</b></th>
                  <th colspan="3" class="text-center"><b>SEMESTER</b></th>
                  <th colspan="3" class="text-center"><b>SEMESTER</b></th></tr>
                  <th class="text-center"><b>I</b></th>
                  <th class="text-center"><b>II</b></th>
                  <th class="text-center"><b>AV</b></th>
                  <th class="text-center"><b>I</b></th>
                  <th class="text-center"><b>II</b></th>
                  <th class="text-center"><b>AV</b></th></tr>';
                  
                  $querySubjecy=$this->db->query("select * from reportcard".$gradesec.$academicyear." where academicyear='$academicyear' and grade='$gradesec' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                  if($querySubjecy->num_rows()>0){
                    foreach ($querySubjecy->result() as $subjValue) {
                      $subjName=$subjValue->subject;
                      $output.='<tr><td><b>'.$subjValue->subject.'</b></td>';
                      /*Sem1*/

                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';
                      $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' and subject='$subjName' ");

                      foreach ($quartrSem1Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Sem2*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' and subject='$subjName'");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*YearlyAverage*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#' and subject='$subjName' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/2,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                    }
                    /*GrandTotal*/
                    $output.='<tr><td><b>GRAND TOTAL</b></td>';

                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    $subALl=$countSubject->num_rows();
                    /*Grandtotal 4 1st Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=$totalValueSem1->total;
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 2nd Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=$totalValueSem1->total;
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 YearlyAverage Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }

                    /*GrandTotal*/
                    $output.='<tr><td><b>AVERAGE</b></td>';

                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    $subALl=$countSubject->num_rows();
                    /*Grandtotal 4 1st Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=$totalValueSem1->total;
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 2nd Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=$totalValueSem1->total;
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 YearlyAverage Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                  }
                }
                /*........................................................................*/
              }else if($currGrade == trim('11n') || $currGrade == trim('11s') || $currGrade%2 !='0'){
                /*Odd grade calculation starts*/
                $targetYear=$academicyear + 1;
                /*$queryGreYear=$this->db->query("select gyear from academicyear where year_name='$targetYear' ");
                $rowG = $queryGreYear->row();
                $gmax_year=$rowG->gyear;*/
                $gmax_year=$academicyear + 1;
                $queryAGreYear=$this->db->query("select gyear from academicyear where year_name='$academicyear' ");
                $rowAG = $queryAGreYear->row();
                $Agmax_year=$rowAG->gyear;

                $output.='<th colspan="3" class="text-center"><b>'.$Agmax_year.' | '.$academicyear.' E.C </b></th><th colspan="3" class="text-center"><b>'.$gmax_year.' | '.$targetYear.' E.C </b></th> </tr>
                <tr> 
                <th class="text-center"><b>GRADE</b></th>';
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
                    $output.='<th colspan="3" class="text-center"><b>'.$gradesec.'('.$gradeName.')</b></th><th colspan="3" class="text-center"><b>'.$lastGrsec.'('.$laGradeName.')</b></th>
                    <tr><th rowspan="2" class="text-center"><b>SUBJECT</b></th>
                    <th colspan="3" class="text-center"><b>SEMESTER</b></th>
                    <th colspan="3" class="text-center"><b>SEMESTER</b></th></tr>
                    <th class="text-center"><b>I</b></th>
                    <th class="text-center"><b>II</b></th>
                    <th class="text-center"><b>AV</b></th>
                    <th class="text-center"><b>I</b></th>
                    <th class="text-center"><b>II</b></th>
                    <th class="text-center"><b>AV</b></th></tr>';
                    $querySubjecy=$this->db->query("select * from reportcard".$lastGrsec.$targetYear." where academicyear='$targetYear' and grade='$lastGrsec' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    if($querySubjecy->num_rows()>0){
                      foreach ($querySubjecy->result() as $subjValue) {
                        $subjName=$subjValue->subject;
                        $output.='<tr><td><b>'.$subjValue->subject.'</b></td>';
                        /*Sem1*/

                        
                        /*for future even grade*/
                        $quartrSem1TotalEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem1TotalEven->result() as $totalValueSem1Even) {
                          $printValueSem1Even=$totalValueSem1Even->total;
                          if($printValueSem1Even >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1Even,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*for future even grade*/
                        $quartrSem2TotalFuEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem2TotalFuEven->result() as $totalValueSem2FuEven) {
                          $totalValueSem2FuEven=$totalValueSem2FuEven->total;
                          if($totalValueSem2FuEven >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$totalValueSem2FuEven,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#' and subject='$subjName'  ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=$totalValueSem1->total;
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/2,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                         /*for selected odd grade*/
                        $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem1' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem1Total->result() as $totalValueSem1) {
                          $printValueSem1=$totalValueSem1->total;
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Sem2*/
                         
                        /*for selected odd grade*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem2' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem2Total->result() as $totalValueSem1) {
                          $printValueSem1=$totalValueSem1->total;
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }

                        /*YearlyAverage*/
                        
                        $quartrSem2Totalver=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#' and subject='$subjName'  ");
                        foreach ($quartrSem2Totalver->result() as $totalValueSem1Ave) {
                          $printValueSem1Ave=$totalValueSem1Ave->total;
                          if($printValueSem1Ave >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1Ave/2,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                      }
                      /*GrandTotal*/
                      $output.='<tr><td><b>GRAND TOTAL</b></td>';
                      $countSubject=$this->db->query("select * from reportcard".$lastGrsec.$targetYear." where grade='$lastGrsec' and academicyear='$targetYear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                      $subALl=$countSubject->num_rows();
                      /*Grandtotal 4 1st Semester*/
                      

                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2TotalFuGrade->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartryearCurGradeTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartryearCurGradeTotal->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }

                      $output.='<tr><td><b>AVERAGE</b></td>';
                      $countSubject=$this->db->query("select * from reportcard".$lastGrsec.$targetYear." where grade='$lastGrsec' and academicyear='$targetYear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                      $subALl=$countSubject->num_rows();
                      /*Grandtotal 4 1st Semester*/
                      

                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2TotalFuGrade->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartryearCurGradeTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                      foreach ($quartryearCurGradeTotal->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 YearlyAverage Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and onreportcard='1' and letter='#'  ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=(($totalValueSem1->total)/2);
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                    }
                  }
                }else{
                  $output.='<th colspan="3" class="text-center"><b>'.$gradesec.'('.$gradeName.')</b></th><th colspan="3" class="text-center"><b> - </b></th>
                  <tr><th rowspan="2" class="text-center"><b>SUBJECT</b></th>
                  <th colspan="3" class="text-center"><b>SEMESTER</b></th>
                  <th colspan="3" class="text-center"><b>SEMESTER</b></th></tr>
                  <th class="text-center"><b>I</b></th>
                  <th class="text-center"><b>II</b></th>
                  <th class="text-center"><b>AV</b></th>
                  <th class="text-center"><b>I</b></th>
                  <th class="text-center"><b>II</b></th>
                  <th class="text-center"><b>AV</b></th></tr>';

                  $querySubjecy=$this->db->query("select * from reportcard".$gradesec.$academicyear." where academicyear='$academicyear' and grade='$gradesec' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                  if($querySubjecy->num_rows()>0){
                    foreach ($querySubjecy->result() as $subjValue) {
                      $subjName=$subjValue->subject;
                      $output.='<tr><td><b>'.$subjValue->subject.'</b></td>';
                      /*Sem1*/

                      
                      $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' and subject='$subjName' ");
                      foreach ($quartrSem1Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Sem2*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' and subject='$subjName' ");

                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*YearlyAverage*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#' and subject='$subjName' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/2,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';
                    }
                    /*GrandTotal*/
                    $output.='<tr><td><b>GRAND TOTAL</b></td>';
                    
                    $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    $subALl=$countSubject->num_rows();
                    /*Grandtotal 4 1st Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=$totalValueSem1->total;
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 2nd Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=$totalValueSem1->total;
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 YearlyAverage Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<tr><td><b>AVERAGE</b></td>';
                    
                    $countSubject=$this->db->query("select * from reportcard".$gradesec.$academicyear." where grade='$gradesec' and academicyear='$academicyear' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
                    $subALl=$countSubject->num_rows();
                    /*Grandtotal 4 1st Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=$totalValueSem1->total;
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 2nd Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Sem2' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=$totalValueSem1->total;
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 YearlyAverage Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and onreportcard='1' and letter='#'  ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=(($totalValueSem1->total)/2);
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';
                  }
                }
              }
              $output.="
              </tbody>
            </table>
          </div>
          <div class='row'>
            <div class='col-md-12' id='ENS'>
              <span class='ENS text-center'>
              <B> Comment:_________________________________________________.<br></B></span>
            </div><br>
            <div class='col-md-12' id='ENS'> 
              <span class='ENS text-center'><B>
              Record Officer's Name and Signature:_______________________. <br>
              Principal's Name and Signature: ____________________________.</B></span> 
            </div>
          </div>
          </div><br>";
        }
      }
    }
    return $output;
  }
}