<?php
class reportcard_nolawi extends CI_Model{
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
    $query_student=$this->db->query(" Select * from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
    $output ='';
    foreach ($query_student->result() as $row_student)
    {
      $stuid=$row_student->id;
      $grade=$row_student->grade;
      $grade_sec=$row_student->gradesec;
      $fname=$row_student->fname;
        $mname=$row_student->mname;
        $lname=$row_student->lname;
        $gender=$row_student->gender;
        $age=$row_student->age;
        $addresss=$row_student->city;
        $kebele=$row_student->kebele;
        $subcity=$row_student->sub_city;
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
        $output.='<div style="width:100%;height:100%;page-break-inside:avoid; page-break-after:always;display: block; "><div class="row">
          <div class="col-lg-8">
          <div class="text-center">
          <span class="time" style="font-family:Rockwell">
          <h2>'.$school_name.'</h2></span>
          <span class="time" style="font-family:Rockwell">
          <h5>Academic Year: '.$yearname.' E.C</h5></span>
          <span class="text-muted" style="font-family:Poor Richard">Address: '.$address.'<br>
          Website : '.$website.'</span>
          </div>
          <div class="row">
          <div class="col-lg-7">
          <h5><b> Name : '.$row_student->fname.' '.$row_student->mname.' '.$row_student->lname.'</b></h5>
          </div>
          <div class="col-lg-5">
          <h5><b>  Grade : '.$row_student->gradesec.'</b></h5>
          </div>
          </div>
          </div>
          <div class="col-lg-4">
          <div class="text-center">
          <span class="time" style="font-family:Viner Hand ITC">
          <h2>Student Report Card</h2>
          </span>
          <span class="text-muted" style="font-family:Poor Richard">Phone: '.$phone.'<br>Email: '.$email.'</span>
          </div>  
          </div>
      </div>';
      $output.= '<div class="row"><div class="col-lg-6">
        <div class="table-responsive">
        <table width="100%" height="120%"  class="table-bordered table-md" cellspacing="9" cellpadding="9">';
      $output.='<tr><th>Subject</th>';
      
      if($max_quarter===trim('Semester2')){
        $query_quarter=$this->db->query(" Select * from quarter where Academic_year='$max_year' group by term order by term ASC ");
      }else{
        $query_quarter=$this->db->query(" Select * from quarter where Academic_year='$max_year' and term='$max_quarter' group by term order by term ASC ");
      }
      foreach ($query_quarter->result() as $qvalue) {
            $output .='<th>'.$qvalue->term.'</th>';
        }
        if($max_quarter===trim('Semester2')){
          $output .='<th>Yearly Average</th>';
        }
        $output.='</tr>';

        $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and onreportcard='1' and letter='#' group by subject order by subjorder ASC ");
        foreach ($query_result->result() as $qvalue_result) 
        {
            $subject=$qvalue_result->subject;
            $output .='<tr><td>'.$qvalue_result->subject.'</td>';
            foreach ($query_quarter->result() as $qvalue) 
            {
                $quarter=$qvalue->term;
                $query_qua_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and subject='$subject' and quarter ='$quarter' and onreportcard='1' and letter='#' group by subject order by subject ASC ");
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
            }
            /*Each subject Yearly average starts (Vertically*/
            $subject=$qvalue_result->subject;
            $letter=$qvalue_result->letter;
            $query_sa1_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where subject='$subject' and onreportcard='1' and academicyear='$max_year' and stuid ='$stuid' and letter='#' and quarter='Semester1' or quarter='Semester2' and onreportcard='1' and subject='$subject' and letter='#'  and stuid ='$stuid' group by stuid, subject ");
            foreach ($query_sa1_sub->result() as $row_sa1)
            {
                $rest222=$row_sa1->total_sum/2;
            }
            $query_check_semster2_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where  academicyear='$max_year' and stuid ='$stuid' and quarter='Semester2' and subject='$subject' and onreportcard='1' and letter='#' group by stuid, subject ");
            if($max_quarter===trim('Semester2')){
                if($query_check_semster2_sub->num_rows()>0){
                    $yearly_ave=$rest222;
                    if($letter!='A'){
                        $output .= '<td><B>'.number_format((float)$yearly_ave,2,'.','').'</B></td>';
                    }else{
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $yearly_ave between minValue and maxiValue and academicYear='$max_year'");
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
                    $output.= "<td>-</td>";
                }
            }else{
          
            }
            $output .='</tr>'; 
        }
        /*Each Quarter total Starts(Horizontally)*/
          $check_rankAllowed=$this->db->query(" Select gr.grade,ur.grade from rank_allowed_grades as gr cross join users as ur where ur.gradesec='$gradesec' and gr.academicyear='$max_year' and ur.grade=gr.grade ");
        if($check_rankAllowed->num_rows()>0)
        {
            $output .='<tr><td><b>Total</b></td>';
            foreach ($query_quarter->result() as $qvalue) 
            {
                $quarter=$qvalue->term;
                $query_qua_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and quarter ='$quarter' and onreportcard='1' group by quarter order by subjorder ASC ");
                if($query_qua_total->num_rows()>0)
                {
                    foreach ($query_qua_total->result() as $qtvalue){
                        $output .= '<td><B>'.number_format((float)$qtvalue->quarter_total,2,'.','').'</B></td>';
                    }
                }else{
                    $output .='<td>-</td>';
                }
            }
            $query_total_gsq2=$this->db->query("select subject,sum(total) as total2 from reportcard".$gradesec.$max_year."  where letter!='A' and onreportcard='1' and academicyear='$max_year' and stuid ='$stuid' and quarter='Semester1' or quarter='Semester2' and letter!='A' and onreportcard='1' and academicyear='$max_year' and stuid ='$stuid' ");
            if(empty($subject)){
                $subject='';
            }
            /*Yearlly average total starts(Horizontally)*/
            $query_check_semster2_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where  
            academicyear='$max_year' and stuid ='$stuid' and quarter='Semester2' and subject='$subject' group by stuid , subject ");
            if($max_quarter===trim('Semester2')){
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
            $output .='<tr><td><b>Average</b></td>';
            foreach ($query_quarter->result() as $qvalue) 
            {
            $quarter=$qvalue->term;
            $query_qua_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where 
            grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and letter='#' and onreportcard='1' and quarter ='$quarter' group by quarter order by subjorder ASC ");
              /*count subject starts*/
              $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' and academicyear='$max_year' and quarter='$quarter' group by subject ");
            $total_subject=$count_subject->num_rows();
            if($query_qua_total->num_rows()>0)
            {
                foreach ($query_qua_total->result() as $qtvalue) {
                    $output .= '<td><B>'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</B></td>';
                }
            } else{
                $output .='<td>-</td>';
            }
        }
        if($max_quarter===trim('Semester2')){
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
        $output .='<tr><td><b>Rank</b></td>'; 
        foreach ($query_quarter->result() as $qvalue)
        {
            $quarter=$qvalue->term;
            $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid' and quarter='$quarter' group by quarter ");
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
        }
          /*Rank of Yearly Average starts
          (Horizontally)*/
            $query_rankya=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." 
              where stuid='$stuid'  and academicyear='$max_year' and grade='$gradesec' and letter='#' and onreportcard='1' group by grade ");
            if($max_quarter===trim('Semester2')){
                if($query_check_semster2_sub->num_rows()>0)
                {
                    foreach ($query_rankya ->result() as $row_rankya)
                    {
                        $rankNew =$row_rankya->stuRank;
                        $output .= '<td><b>'.$rankNew.'</b></td>';
                    }
                }else{
                    $output .= '<td>-</td>';
                }
            }else{
    
            }
        }
        $output .='</tr>';

          /*Each Quarter No of students starts(Vertically)*/
        $output .='<tr><td><b>No. of Student</b></td>';
        foreach ($query_quarter->result() as $qvalue) 
        {
            $quarter=$qvalue->term;
            $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid'  and quarter='$quarter' group by quarter ");
            if($query_total->num_rows()>0)
            {
                $total_student=$query_student->num_rows();
                $output .= '<td><B>'.$total_student.'</B></td>';
            } else{
                $output .= '<td>-</td>';
            }
        }

        /*No of student of Yearly Average starts
        (Horizontally)*/
        if($max_quarter===trim('Semester2')){
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
          $output .='<tr><td><b>Conduct</b></td>';
          foreach ($query_quarter->result() as $qvalue) {
          $quarter=$qvalue->term;
          $each_quarter_basicskill=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' and bsname='Conduct' group by stuid ");
          if($max_quarter===trim('Semester2')){
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
        }
        $output .= '<td>-</td>';
          $output .='</tr>';
          $output .='<tr><td><b>No. of Absence</b></td>';
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
        }
        $query_total_absent2=$this->db->query("select count(stuid) as att from attendance where  stuid='$stuid' and academicyear='$max_year' ");
        if($max_quarter===trim('Semester2')){
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

      $output.= '<div class="col-lg-6">';
      $output.="<h5 id='ENS'><u>HOME ROOM TEACHER'S COMMENTS AND RECOMMENDATIONS</u></h5>";
      $output.="<h5 id='ENS'>የአንደኛ ወሰነ ትምህርት ወቅት የክፍል መምህር አስተያየት<br>First Semester Home Room Teacher's Remark</h5>";
      $output.='____________________________________________________________________ ____________________________________________________________________ ____________________________________________________________________';

      $output.="<br>የክፍሉ መምህር ስም <br>Home Room Teacher's Name";
      $output.='__________________________ Signature.____<br>';

      $output.="የወላጅ ወይም የአሳዳጊ ፊርማ<br>Parent or Guardian Name";
      $output.='__________________________ Signature.____<br>';

      $output.="የት/ቤት ርዕሰ መምህር ስም <br>Director's Name";
      $output.='__________________________ Signature.____<br>';

      $output.="<br><br><h5 id='ENS'>የሁለተኛ ወሰነ ትምህርት ወቅት የክፍል መምህር አስተያየት<br> Second Semester Home Room Teacher's Remark</h5>";
      $output.='____________________________________________________________________ ____________________________________________________________________ ____________________________________________________________________';

      $output.="<br>የክፍሉ መምህር ስም <br>Home Room Teacher's Name";
      $output.='__________________________ Signature.____<br>';

      $output.="የወላጅ ወይም የአሳዳጊ ፊርማ<br> Parent or Guardian Name";
      $output.='__________________________ Signature.____<br>';
      $output .='</div></div><div>';
      $output.='<div style="width:100%;height:100%;page-break-inside:avoid; page-break-after:always;display: block; ">
      <div class="row" id="ENS">
        <div class="col-lg-5 col-6">
          <h2><u><b><p class="text-center"> የማርክ አሰጣጥ ደንብ</p></b></u></h2>
          ትምህርት ቤቶች በመዝገብ ዉስጥ የሚፅፏቸው የተማሪዎች የትምህርት ደረጃ ዉጤት በሚከተለዉ ዓይነት ይመደባል፡፡
          <div class="row">
            <div class="col-lg-6 col-6">
              <p class="text-center">90-100%</p>
              <p class="text-center">80-89%</p>
              <p class="text-center">70-79% </p>
              <p class="text-center">60-69% </p>
              <p class="text-center">50-59% </p>
            </div>
            <div class="col-lg-6 col-6">
              <p class="text-center">ያገኘ እጅግ በጣም ጥሩ </p>
              <p class="text-center">በጣም ጥሩ</p>
              <p class="text-center"> ጥሩ</p>
              <p class="text-center">በቂ</p>
              <p class="text-center"> መጠነኛ</p>
            </div>
          </div>
          <p id="ENS">ከመቶ(0%) ምንም ጊዜ ቢሆን ለተማሪ አይሰጥም፡፡ ዜሮ መስጠት ፈፅሞ አልተማረም ማለት ነዉ፡፡ ተማሪ ክፍል ዉስጥ ያልተገኘ እንደሆነ አልነበረም ተብሎ ይፃፍበታል፡፡</p>
          <h2><u><b><p class="text-center">METHOD OF MARKING</p></b></u></h2>
          <h3><u><b><p class="text-center"> PASSING MARK 50%</p></b></u></h3>
          Students Achievement in each class will be assigned the following values.
          <div class="row">
            <div class="col-lg-6 col-6">
              <p class="text-center">90-100%</p>
              <p class="text-center">80-89%</p>
              <p class="text-center">70-79% </p>
              <p class="text-center">60-69% </p>
              <p class="text-center">50-59% </p>
              <p class="text-center">Below 50% </p>
            </div>
            <div class="col-lg-6 col-6">
              <p class="text-center">Excellent</p>
              <p class="text-center">Very Good</p>
              <p class="text-center"> Good</p>
              <p class="text-center">Satisfactory</p>
              <p class="text-center"> Poor</p>
              <p class="text-center">Failure</p>
            </div>
          </div>
          <p id="ENS">A mark Zero(0) should never be given, since it would mean that absolutely no work has been done. If a student has been absent from class for the whole period covered and has not made up any of the work,he should be marked "Ab" for Absent.</p>
        </div>
        <div class="col-lg-1 col-1"></div>
        <div class="col-lg-6 col-5">';
          $query_name = $this->db->query("select * from school");
          $row_name = $query_name->row();
          $school_name=$row_name->name;
          $address=$row_name->address;
          $phone=$row_name->phone;
          $website=$row_name->website;
          $email=$row_name->email;
          $logo=$row_name->logo;
          $output.='<div class="row"> <div class="col-lg-12 col-12"><img src="'.base_url().'/logo/rclogonew.jpg" style="float:right;border:1px solid #fff; -webkit-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;border-radius:10px" >
          </div>
            <div class="col-lg-6 col-6">
              <p><i class="fas fa-phone-square"></i>
              0930-099543/44</p>
              <p>0930-100432</p>
              <p>011-6-52020 90</p>
              <p>011-4-34 78 04</p>
            </div>
            <div class="col-lg-6 col-6">
              <p><i class="fas fa-envelope-square"></i> 24322 code 1000</p>
              <p>አዲስ አበባ ኢትዮጵያ</p>
              <p>Addis Ababa Ethiopia</p>
            </div>
            <div class="col-lg-12 col-12">
              <h3 class="text-center"><b><u>የተማሪዎች ዉጤት መግለጫ</u></b></h3>
              <h3 class="text-center"><b><u>Student Report Card</u></b></h3>
            </div>
              <div class="col-lg-12 col-12">
                የተማሪዉ ስም
                <p>Name of Student <u>'.$fname.' '.$mname.' '.$lname.'</u></p>
              </div>
              <div class="col-lg-4 col-4">
                ዕድሜ <p>Age <u>'.$age.' </u></p> 
              </div> 
              <div class="col-lg-4 col-4">
                ፆታ <p>Sex <u>'.$gender.' </u></p>
              </div>
              <div class="col-lg-4 col-4">
                አድራሻ <p>Address <u>'.$addresss.'</u> </p>
              </div>
              <div class="col-lg-3 col-3">
                ክ/ከ <p>S/C <u>'.$subcity.' </u> </p>
              </div> 
              <div class="col-lg-4 col-4">
                ቀበሌ <p>Kebele <u>'.$kebele.' </u></p>
              </div>
              <div class="col-lg-5 col-5">
                የቤት ቁጥር <p>House No. _____________</p>
              </div>
              <div class="col-lg-4 col-4">
                ክፍል <p>Class<u> '.$grade.'</u> </p>
              </div> 
              <div class="col-lg-8 col-8">
                የትምህርት ዘመን <p>Academic Year <u>'.$max_year.' </u></p>
              </div>
              <div class="col-lg-4 col-4">
                ከ <p>From <u>'.$grade.'</u> </p>
              </div>
              <div class="col-lg-5 col-5">
                ክፍል ወደ <p>Class to ________________</p>
               </div>
               <div class="col-lg-3 col-3">
                  ተዛዉሯል(ለች)<p>Promoted</p>
               </div>
            </div> 
          </div>

        </div>
      </div>';
    }
    return $output;
  }
  function report_card2($max_year,$gradesec,$branch,$max_quarter){
    $query_student=$this->db->query(" Select * from users where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname,mname,lname ASC ");
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
            <div class="col-lg-12"> 
              <div class="text-center ENS"> 
                <h2><u>'.$school_name.'</u></h2> <h2><u>Supplementary Subjects Report Card</u></h2>
              </div>
            </div>
            <div class="col-lg-12">
              <span class="time">This is to certify <b> <u>'.$row_student->fname.' '.$row_student->mname.' '.$row_student->lname.'</u></b> who has attended the following supplementary subjects in our school.
              </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <h5><b>  Grade : '.$row_student->gradesec.'</b></h5>
            </div>
            <div class="col-lg-6"> 
                <h5>Academic Year: '.$yearname.' E.C</h5>
            </div>
        </div>';
      $output.= '
        <div class="table-responsive">
        <table width="100%"  class="table-bordered table-md" cellspacing="5" cellpadding="5">';
      $output.='<tr><th>Subject</th>';
      
      if($max_quarter===trim('Semester2')){
        $query_quarter=$this->db->query(" Select * from quarter where Academic_year='$max_year' group by term order by term ASC ");
      }else{
        $query_quarter=$this->db->query(" Select * from quarter where Academic_year='$max_year' and term='$max_quarter' group by term order by term ASC ");
      }
      foreach ($query_quarter->result() as $qvalue) {
            $output .='<th class="text-center">'.$qvalue->term.'</th>';
        }
        if($max_quarter===trim('Semester2')){
          $output .='<th>Yearly Average</th>';
        }
        $output.='</tr>';

        $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and onreportcard='1' and letter='A' group by subject order by subject ASC ");
        foreach ($query_result->result() as $qvalue_result) 
        {
            $subject=$qvalue_result->subject;
            $output .='<tr><td>'.$qvalue_result->subject.'</td>';
            foreach ($query_quarter->result() as $qvalue) 
            {
                $quarter=$qvalue->term;
                $query_qua_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and subject='$subject' and quarter ='$quarter' and onreportcard='1' and letter='A' group by subject order by subject ASC ");
                if($query_qua_result->num_rows()>0)
                {
                    foreach ($query_qua_result->result() as $quvalue)
                    {
                        $letter=$quvalue->letter;
                        $result=$quvalue->total;
                       /* if($letter!='A')
                        {*/
                            $output .='<td class="text-center">'.$result.'</td>';
                        /*}*/
                        /*else{
                            $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                            if($queryRange->num_rows()>0){
                                foreach ($queryRange->result() as $letterValue) {
                                    $letterVal=$letterValue->letterVal;
                                    $output.= "<td class='text-center'>".$letterVal."</td>";
                                }
                            }else{
                                $output.= "<td class='text-center'> -</td>";
                            }
                        }*/
                    }
                }else{
                    $output.= "<td class='text-center'> -</td>";
                }
            }
            /*Each subject Yearly average starts (Vertically*/
            $subject=$qvalue_result->subject;
            $letter=$qvalue_result->letter;
            $query_sa1_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where subject='$subject' and onreportcard='1' and academicyear='$max_year' and stuid ='$stuid' and letter='A' and quarter='Semester1' or quarter='Semester2' and onreportcard='1' and subject='$subject' and letter='A'  and stuid ='$stuid' group by stuid, subject ");
            foreach ($query_sa1_sub->result() as $row_sa1)
            {
                $rest222=$row_sa1->total_sum/2;
            }
            $query_check_semster2_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where  academicyear='$max_year' and stuid ='$stuid' and quarter='Semester2' and subject='$subject' and onreportcard='1' and letter='A' group by stuid, subject ");
            if($max_quarter===trim('Semester2')){
                if($query_check_semster2_sub->num_rows()>0){
                    $yearly_ave=$rest222;
                   /* if($letter!='A'){*/
                        $output .= '<td><B>'.number_format((float)$yearly_ave,2,'.','').'</B></td>';
                    /*}else{
                        $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $yearly_ave between minValue and maxiValue and academicYear='$max_year'");
                        if($queryRange->num_rows()>0){
                            foreach ($queryRange->result() as $letterValue) {
                                $letterVal=$letterValue->letterVal;
                                $output.= "<td class='text-center'>".$letterVal."</td>";
                            }
                        }else{
                            $output.= "<td class='text-center'> -</td>";
                        }
                    }*/
                }else{
                    $output.= "<td class='text-center'>-</td>";
                }
            }else{
          
            }
            $output .='</tr>'; 
        }
        /*Each Quarter total Starts(Horizontally)*/
          $check_rankAllowed=$this->db->query(" Select gr.grade,ur.grade from rank_allowed_grades as gr cross join users as ur where ur.gradesec='$gradesec' and gr.academicyear='$max_year' and ur.grade=gr.grade ");
        if($check_rankAllowed->num_rows()>0)
        {
            /*$output .='<tr><td><b>Total</b></td>';*/
            foreach ($query_quarter->result() as $qvalue) 
            {
                $quarter=$qvalue->term;
                $query_qua_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and letter='A' and quarter ='$quarter' and onreportcard='1' group by quarter order by subject ASC ");
                if($query_qua_total->num_rows()>0)
                {
                    foreach ($query_qua_total->result() as $qtvalue){
                        /*$output .= '<td class="text-center"><B>'.number_format((float)$qtvalue->quarter_total,2,'.','').'</B></td>';*/
                    }
                }else{
                   /* $output .='<td class="text-center">-</td>';*/
                }
            }
            $query_total_gsq2=$this->db->query("select subject,sum(total) as total2 from reportcard".$gradesec.$max_year."  where letter='A' and onreportcard='1' and academicyear='$max_year' and stuid ='$stuid' and quarter='Semester1' or quarter='Semester2' and letter='A' and onreportcard='1' and academicyear='$max_year' and stuid ='$stuid' ");
            if(empty($subject)){
                $subject='';
            }
            /*Yearlly average total starts(Horizontally)*/
            $query_check_semster2_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where  
            academicyear='$max_year' and stuid ='$stuid' and quarter='Semester2' and subject='$subject' group by stuid , subject ");
            if($max_quarter===trim('Semester2')){
                if($query_check_semster2_sub->num_rows()>0)
                {
                    foreach ($query_total_gsq2->result() as $row_a1)
                    {
                        $fave1=$row_a1->total2/2;
                    }
                    $fave_year=$fave1;
                    /*$output .= '<td class="text-center"><B>'.number_format((float)$fave_year,2,'.','').'</B></td>';*/
                }
                else{
                    /*$output .='<td class="text-center">-</td>';*/
                }
            }else{
                //
            }
            /*$output .='</tr>';*/
        //     /*Each Yearlly Average total ends(Horizontally)*/

        //     /*Each Yearlly Average starts(Horizontally)*/
            if($grade>='4'){
                $output .='<tr><td><b>Average</b></td>';
                foreach ($query_quarter->result() as $qvalue) 
                {
                $quarter=$qvalue->term;
                $query_qua_total=$this->db->query(" Select sum(total) as quarter_total from reportcard".$gradesec.$max_year." where 
                grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and letter='A' and onreportcard='1' and quarter ='$quarter' group by quarter order by subject ASC ");
                  /*count subject starts*/
                  $count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='A' and onreportcard='1' and academicyear='$max_year' and quarter='$quarter' group by subject ");
                $total_subject=$count_subject->num_rows();
                if($query_qua_total->num_rows()>0)
                {
                    foreach ($query_qua_total->result() as $qtvalue) {
                        $output .= '<td class="text-center"><B>'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</B></td>';
                    }
                } else{
                    $output .='<td>-</td>';
                }
            }
            if($max_quarter===trim('Semester2')){
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
            
            }
         }

          /*Each Quarter No of students starts(Vertically)*/
        $output .='<tr><td><b>Effort</b></td>';
        foreach ($query_quarter->result() as $qvalue) 
        {
            $quarter=$qvalue->term;
            $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid'  and quarter='$quarter' group by quarter ");
            if($query_total->num_rows()>0)
            {
                $output .= '<td class="text-center"><B>-</B></td>';
            } else{
                $output .= '<td class="text-center">-</td>';
            }
        }

        /*No of student of Yearly Average starts
        (Horizontally)*/
        if($max_quarter===trim('Semester2')){
          if($query_check_semster2_sub->num_rows()>0)
          {
            $output .= '<td><B>-</B></td>';
          }else{
            $output .= '<td>-</td>';
          }
        }else{

        }
        $output .='</tr>';
        $output .='</table></div>';
        $output .='<br>';
        $output.="TEACHER'S COMMENT";
        $output.=' ____________________________________________________________
        _____________________________________________________________<br>';
        $output.="<br><div class='row'><div class='col-6'>Signature. ________________</div>";
        $output.="<div class='col-6'>Director's Signature";
        $output.='__________________________</div></div><br>';
    }
    return $output;
  }
  function KgReportCard($max_year,$gradesec,$branch,$max_quarter){
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

        $queryac = $this->db->query("select max(year_name) as ay from academicyear");
        $rowac = $queryac->row();
        $yearname=$rowac->ay;
      $output.='<div class="row">
          <div class="col-lg-8">
          <div class="text-center">
          <span class="time" style="font-family:Rockwell">
          <h2>'.$school_name.'</h2></span>
          <span class="time" style="font-family:Rockwell">
          <h5>Academic Year: '.$yearname.' E.C</h5></span>
          <span class="text-muted" style="font-family:Poor Richard">Address: '.$address.'<br>
          Website : '.$website.'</span>
          </div>
          <div class="row">
          <div class="col-lg-8">
          <h5><b> Name : '.$row_student->fname.' '.$row_student->mname.' '.$row_student->lname.'</b></h5>
          </div>
          <div class="col-lg-4">
          <h5><b>  Grade : '.$row_student->gradesec.'</b></h5>
          </div>
          </div>
          </div>
          <div class="col-lg-4">
          <div class="text-center">
          <span class="time" style="font-family:Viner Hand ITC">
          <h2>Student Report Card</h2>
          </span>
          <span class="text-muted" style="font-family:Poor Richard">Phone: '.$phone.'<br>Email: '.$email.'</span>
          </div>  
          </div>
      </div>';
      $output.= '<div class="row"><div class="col-lg-8">
        <div class="table-responsive">
        <table width="100%"  class="table-bordered table-md" cellspacing="5" cellpadding="5">';
      $output.='<tr><th>Subject</th>';
      
      if($max_quarter===trim('Semester2')){
        $query_quarter=$this->db->query(" Select * from quarter where Academic_year='$max_year' group by term order by term ASC ");
      }else{
        $query_quarter=$this->db->query(" Select * from quarter where Academic_year='$max_year' and term='$max_quarter' group by term order by term ASC ");
      }
      
      $output.='<th>Teachers Name</th>';
      foreach ($query_quarter->result() as $qvalue) {
            $output .='<th>'.$qvalue->term.'</th>';
            $output .='<th>TR</th>';
        }
        if($max_quarter===trim('Semester2')){
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
              $output .='<td>-</td>';
          }
          /*Each subject Yearly average starts
          (Vertically*/
          $subject=$qvalue_result->subject;
          $letter=$qvalue_result->letter;
            $query_sa1_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where subject='$subject' 
            and academicyear='$max_year' and stuid ='$stuid' and quarter='Semester1' or quarter='Semester2' and subject='$subject'  and stuid ='$stuid' group by stuid, subject ");
        foreach ($query_sa1_sub->result() as $row_sa1)
        {
          $rest222=$row_sa1->total_sum/2;
        }
        $query_check_semster2_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where  academicyear='$max_year' 
        and stuid ='$stuid' and quarter='Semester2' and subject='$subject' group by stuid, subject ");
          if($max_quarter===trim('Semester2')){
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
                          $output.= "<td class='text-center'>".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center'> -</td>";
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
          and academicyear='$max_year' and stuid ='$stuid' and quarter='Semester1' or quarter='Semester2'  and onreportcard='1' and academicyear='$max_year' 
          and stuid ='$stuid' ");
          if(empty($subject)){
            $subject='';
          }
          /*Yearlly average total starts(Horizontally)*/
          $query_check_semster2_sub=$this->db->query("select Sum(total) as total_sum from reportcard".$gradesec.$max_year." where  
          academicyear='$max_year' and stuid ='$stuid' and quarter='Semester2' and subject='$subject' group by stuid , subject ");
          if($max_quarter===trim('Semester2')){
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
          if($max_quarter===trim('Semester2')){
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
              $output .='<td>-</td>';
          }
          /*Rank of Yearly Average starts
          (Horizontally)*/
          $query_rankya=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from
          reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and onreportcard='1' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." 
          where stuid='$stuid'  and academicyear='$max_year' and grade='$gradesec' and letter='#' and onreportcard='1' group by grade ");
          if($max_quarter===trim('Semester2')){
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
        if($max_quarter===trim('Semester2')){
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
          $each_quarter_basicskill=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' group by stuid ");
          if($max_quarter===trim('Semester2')){
            if($each_quarter_basicskill->num_rows()>0){
              foreach ($each_quarter_basicskill->result() as $keue) 
              {
                $output .= '<td><B>'.$keue->conduct.'</B></td>';
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
        if($max_quarter===trim('Semester2')){
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
      $output.= '<div class="col-lg-4">
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
      $query_result=$this->db->query(" Select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' group by subject order by subjorder ASC ");
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
        $each_quarter_basicskill=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' group by stuid ");
        if($each_quarter_basicskill->num_rows()>0)
        {
            foreach ($each_quarter_basicskill->result() as $keue) 
            {
              $output .= '<td><B>'.$keue->conduct.'</B></td>';//Each Quarter conduct
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
        where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and quarter='Semester2' ");
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
        $eachQuarterBasicskill=$this->db->query(" Select value from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and quarter='$quarter' and bsname='Conduct' group by stuid ");
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
          $output.='<div class ="row" id="ENS">
          <div class="col-lg-1"></div>
         <div class="col-lg-8 text-center">
          <h3><B>OFFICIAL SCHOOL TRANSCRIPT</B></h3>
         </div>
         <div class="col-lg-2"></div>
         </div>
         <div class ="row" id="ENS">
         <div class="col-lg-1"></div>
            <div class="col-lg-8">
              <h5><b>NAME: <u>'.$stuName->fname.' '.$stuName->mname.' '.$stuName->lname.'</u></b></h5>
              <h5><b>GENDER: <u>'.$stuName->gender.' </u></b></h5>
              <h5><b>GRADE: <u>'.$stuName->gradesec.' </u></b></h5>
              <h5><b>AGE: <u>'.$stuName->age.' </u></b></h5>
            </div>
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
                        /*Semester1*/
                        /*for selected odd grade*/
                        $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Semester1' and onreportcard='1' and letter='#' and subject='$subjName'");
                        foreach ($quartrSem1Total->result() as $totalValueSem1) {
                          $printValueSem1=$totalValueSem1->total;
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Semester2*/
                         
                        /*for selected odd grade*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Semester2' and onreportcard='1' and letter='#' and subject='$subjName' ");
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
                        $quartrSem1TotalEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem1TotalEven->result() as $totalValueSem1Even) {
                          $printValueSem1Even=$totalValueSem1Even->total;
                          if($printValueSem1Even >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1Even,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*for future even grade*/
                        $quartrSem2TotalFuEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' and subject='$subjName' ");
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
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Semester2' and onreportcard='1' and letter='#' ");
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
                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
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
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Semester2' and onreportcard='1' and letter='#' ");
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
                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
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
                      /*Semester1*/

                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';
                      $output.='<td class="text-center">-</td>';
                      $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' and subject='$subjName' ");

                      foreach ($quartrSem1Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Semester2*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' and subject='$subjName'");
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
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=$totalValueSem1->total;
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 2nd Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
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
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=$totalValueSem1->total;
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 2nd Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
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
                $queryGreYear=$this->db->query("select gyear from academicyear where year_name='$targetYear' ");
                $rowG = $queryGreYear->row();
                $gmax_year=$rowG->gyear;

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
                        /*Semester1*/

                        
                        /*for future even grade*/
                        $quartrSem1TotalEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem1TotalEven->result() as $totalValueSem1Even) {
                          $printValueSem1Even=$totalValueSem1Even->total;
                          if($printValueSem1Even >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1Even,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*for future even grade*/
                        $quartrSem2TotalFuEven=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' and subject='$subjName' ");
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
                        $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Semester1' and onreportcard='1' and letter='#' and subject='$subjName' ");
                        foreach ($quartrSem1Total->result() as $totalValueSem1) {
                          $printValueSem1=$totalValueSem1->total;
                          if($printValueSem1 >0){
                            $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                          }else{
                            $output.='<td class="text-center">-</td>';
                          }
                        }
                        /*Semester2*/
                         
                        /*for selected odd grade*/
                        $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Semester2' and onreportcard='1' and letter='#' and subject='$subjName' ");
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
                      

                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#'  ");
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
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Semester2' and onreportcard='1' and letter='#' ");
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
                      

                      $quartrSem1TotalCurGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem1TotalCurGrade->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      $quartrSem2TotalFuGrade=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
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
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                      foreach ($quartrSem2Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Grandtotal 4 2nd Semester*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$lastGrsec.$targetYear." where stuid='$lastID' and quarter='Semester2' and onreportcard='1' and letter='#' ");
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
                      /*Semester1*/

                      
                      $quartrSem1Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' and subject='$subjName' ");
                      foreach ($quartrSem1Total->result() as $totalValueSem1) {
                        $printValueSem1=$totalValueSem1->total;
                        if($printValueSem1 >0){
                          $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                        }else{
                          $output.='<td class="text-center">-</td>';
                        }
                      }
                      /*Semester2*/
                      $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' and subject='$subjName' ");

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
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=$totalValueSem1->total;
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 2nd Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
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
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester1' and onreportcard='1' and letter='#' ");
                    foreach ($quartrSem2Total->result() as $totalValueSem1) {
                      $printValueSem1=$totalValueSem1->total;
                      if($printValueSem1 >0){
                        $output .= '<td class="text-center"><b>'.number_format((float)$printValueSem1/$subALl,2,'.','').'</b></td>';
                      }else{
                        $output.='<td class="text-center">-</td>';
                      }
                    }
                    /*Grandtotal 4 2nd Semester*/
                    $quartrSem2Total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$academicyear." where stuid='$stuid' and quarter='Semester2' and onreportcard='1' and letter='#' ");
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
              $output.='
              </tbody>
            </table>
          </div>
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-7" id="ENS">
              <span class="ENS">
              <B> LAST GRADE ATTENDED IN WORDS:<u>'.$gradeName.'</u><br>
             REASON FOR ISSUE:<u>Completed Grade '.$gradeName.'</u> <br><br>_____________________.<br>
             REGISTRAR</B></span>
            </div>
            <div class="col-md-4" id="ENS"> 
              <span class="ENS"><B>
              DATE OF ISSUE.______________. <br> <br><br>
              ACADEMIC HEAD.______________.</B></span> 
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <div class="text-center">
           <span id="ENS">Note: THIS TRANSCRIPT IS INVALID IF ANY ALTERNATION OR ERASER COMMITS & UNLESS IT BEARS THE OFFICIAL SEAL OF THE SCHOOL.</span>
          </div>
          <br>';
        }
      }
    }
    return $output;
  }
  function fetchBacksidereportcard(){
    $output='';
    $output.='<div class="row" id="ENS">
        <div class="col-lg-6 col-6">
          <h2><u><b><p class="text-center"> የማርክ አሰጣጥ ደንብ</p></b></u></h2>
          ትምህርት ቤቶች በመዝገብ ዉስጥ የሚፅፏቸው የተማሪዎች የትምህርት ደረጃ ዉጤት በሚከተለዉ ዓይነት ይመደባል፡፡
          <div class="row">
            <div class="col-lg-6 col-6">
              <p class="text-center">90-100%</p>
              <p class="text-center">80-89%</p>
              <p class="text-center">70-79% </p>
              <p class="text-center">60-69% </p>
              <p class="text-center">50-59% </p>
            </div>
            <div class="col-lg-6 col-6">
              <p class="text-center">ያገኘ እጅግ በጣም ጥሩ </p>
              <p class="text-center">በጣም ጥሩ</p>
              <p class="text-center"> ጥሩ</p>
              <p class="text-center">በቂ</p>
              <p class="text-center"> መጠነኛ</p>
            </div>
          </div>
          <p id="ENS">ከመቶ(0%) ምንም ጊዜ ቢሆን ለተማሪ አይሰጥም፡፡ ዜሮ መስጠት ፈፅሞ አልተማረም ማለት ነዉ፡፡ ተማሪ ክፍል ዉስጥ ያልተገኘ እንደሆነ አልነበረም ተብሎ ይፃፍበታል፡፡</p>
          <h2><u><b><p class="text-center">METHOD OF MARKING</p></b></u></h2>
          <h3><u><b><p class="text-center"> PASSING MARK 50%</p></b></u></h3>
          Students Achievement in each class will be assigned the following values.
          <div class="row">
            <div class="col-lg-6 col-6">
              <p class="text-center">90-100%</p>
              <p class="text-center">80-89%</p>
              <p class="text-center">70-79% </p>
              <p class="text-center">60-69% </p>
              <p class="text-center">50-59% </p>
              <p class="text-center">Below 50% </p>
            </div>
            <div class="col-lg-6 col-6">
              <p class="text-center">Excellent</p>
              <p class="text-center">Very Good</p>
              <p class="text-center"> Good</p>
              <p class="text-center">Satisfactory</p>
              <p class="text-center"> Poor</p>
              <p class="text-center">Failure</p>
            </div>
          </div>
          <p id="ENS">A mark Zero(0) should never be given, since it would mean that absolutely no work has been done. If a student has been absent from class for the whole period covered and has not made up any of the work,he should be marked "Ab" for Absent.</p>
        </div>
        <div class="col-lg-1 col-1"></div>
        <div class="col-lg-5 col-5">';
          $query_name = $this->db->query("select * from school");
          $row_name = $query_name->row();
          $school_name=$row_name->name;
          $address=$row_name->address;
          $phone=$row_name->phone;
          $website=$row_name->website;
          $email=$row_name->email;
          $logo=$row_name->logo;
          $output.='<div class="row"> <div class="col-lg-12 col-12"><img src="'.base_url().'/logo/rclogonew.jpg" style="float:right;border:1px solid #fff; -webkit-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;border-radius:10px" >
          </div>
            <div class="col-lg-6 col-6">
              <p><i class="fas fa-phone-square"></i>
              0930-099543/44</p>
              <p>0930-100432</p>
              <p>011-6-52020 90</p>
              <p>011-4-34 78 04</p>
            </div>
            <div class="col-lg-6 col-6">
              <p><i class="fas fa-envelope-square"></i> 24322 code 1000</p>
              <p>አዲስ አበባ ኢትዮጵያ</p>
              <p>Addis Ababa Ethiopia</p>
            </div>
            <div class="col-lg-12 col-12">
              <h3 class="text-center"><b><u>የተማሪዎች ዉጤት መግለጫ</u></b></h3>
              <h3 class="text-center"><b><u>Student Report Card</u></b></h3>
            </div>
              <div class="col-lg-12 col-12">
                የተማሪዉ ስም
                <p>Name of Student__________________________________________________.</p>
              </div>
              <div class="col-lg-4 col-4">
                ዕድሜ <p>Age_________</p> 
              </div> 
              <div class="col-lg-4 col-4">
                ፆታ <p>Sex_________</p>
              </div>
              <div class="col-lg-4 col-4">
                አድራሻ <p>Address__________</p>
              </div>
              <div class="col-lg-3 col-3">
                ክ/ከ <p>S/C_____________</p>
              </div> 
              <div class="col-lg-4 col-4">
                ቀበሌ <p>Kebele__________</p>
              </div>
              <div class="col-lg-5 col-5">
                የቤት ቁጥር <p>House No. _____________</p>
              </div>
              <div class="col-lg-4 col-4">
                ክፍል <p>Class____________. </p>
              </div> 
              <div class="col-lg-8 col-8">
                የትምህርት ዘመን <p>Academic Year__________________________.</p>
              </div>
              <div class="col-lg-4 col-4">
                ከ <p>From__________________</p>
              </div>
              <div class="col-lg-5 col-5">
                ክፍል ወደ <p>Class to ________________</p>
               </div>
               <div class="col-lg-3 col-3">
                  ተዛዉሯል(ለች)<p>Promoted</p>
               </div>
            </div> 
          </div>

        </div>
      </div>';
    return $output;
  }
}