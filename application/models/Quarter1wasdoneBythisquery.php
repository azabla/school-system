<?php
function reportcardByQuarter($max_year,$gradesec,$branch,$max_quarter){
    $output ='';
    $arrayRp = array('gradesec' => $gradesec, 'usertype' =>'Student', 'branch' => $branch,'isapproved'=>'1','status'=>'Active','academicyear'=>$max_year);
    $this->db->where($arrayRp);
    $this->db->group_by("id");  
    $this->db->order_by("fname,mname,lname","ASC"); 
    $this->db->distinct();
    $queryStudent = $this->db->get('users');
    $stuAll=$queryStudent->num_rows();
    $querySchool = $this->db->get('school')->row();
    $school_name=$querySchool->name;
    $dateYear=date('Y');
    foreach ($queryStudent->result() as $fetchStudent)
    {
      $grade=$fetchStudent->grade;
      $stuid=$fetchStudent->id;
      $username1=$fetchStudent->username;
      $gradesec=$fetchStudent->gradesec;
      $output.= '<div class="row" id="ENS">
      <div class="col-lg-7">';
      $output.='<div class="row">';
      $output.='<div class="col-lg-3"><b id="ENS">Grade: '.$fetchStudent->grade.'</b></div>';
      $output.='<div class="col-lg-3"><b id="ENS">Section: '.$fetchStudent->section.'</b></div> ';
      $output.='</div>';
      $output.='<div class="table-responsive">
      <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
      $output.='<tr><th colspan="15" class="text-center">
      <h6 id="ENScool"><B id="ENS">'.$school_name.' '.$dateYear.'/22 G.C '.$max_year.' E.C Student Report Card</B></h6>
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
      $arrayRp = array('grade' => $gradesec, 'rpbranch' => $branch,'onreportcard'=>'1','academicyear'=>$max_year,'stuid'=>$stuid);
      $this->db->where($arrayRp);
      $this->db->group_by("subject");  
      $this->db->order_by("subjorder","ASC"); 
      $this->db->distinct();
      $querySubject = $this->db->get('reportcard'.$gradesec.$max_year);
      if($querySubject->num_rows()>0){
        foreach ($querySubject->result() as $fetchSubject) 
        {
          $subject=$fetchSubject->subject;
          $letter=$fetchSubject->letter;
          $output.='<tr><td style="white-space: nowrap"><B>'.$fetchSubject->subject.'</B></td>';/*Subject List*/
          $result1=$fetchSubject->total;
          if($fetchSubject->total=='' || $fetchSubject->total<=0)
          {
            $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
          }else
          {
            if($letter!='A')
            {
              $output .= '<td class="text-center">'.number_format((float)$result1,2,'.','').'</td>';
            }
            else{
              /*$queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");*/
              $arrayLetterRange = array('grade' => $grade,'academicYear'=>$max_year,'minValue <= '=>$result1 ,'maxiValue >= '=>$result1);
              $this->db->where($arrayLetterRange);
              $this->db->distinct();
              $queryRange = $this->db->get('letterange');
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
          $queryQ1SubRank1=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and subject='$subject' and quarter='Quarter1' group by stuid) sm)) as stuRank1 from reportcard".$gradesec.$max_year." where grade='$gradesec' and stuid='$stuid' and rpbranch='$branch' and quarter='Quarter1' and subject='$subject' group by subject ");
          if($result1=='' || $result1<=0){
            $output.='<td class="text-center">-</td>';
          }else{
            foreach ($queryQ1SubRank1->result() as $q1SubRank1)
            {
              $Q1SubjRank1=$q1SubRank1->stuRank1;
              $output.='<td class="text-center"><b>'.$Q1SubjRank1.'</b></td>';
            }
          }
          /*fetch quarter 2 result starts*/
          $output.='<td class="text-center">-</td>';
          $output.='<td class="text-center">-</td>';
          /*1st Semester calculation starts*/
          $output.='<td class="text-center">-</td>';
          $output.='<td class="text-center">-</td>';
          /*fetch quarter 3 result starts*/
          $output.='<td class="text-center">-</td>';
          $output.='<td class="text-center">-</td>';
          /*fetch quarter 4 result starts*/
          $output.='<td class="text-center">-</td>';
          $output.='<td class="text-center">-</td>';
          /*2nd Semester calculation starts*/
          $output.='<td class="text-center">-</td>';
          $output.='<td class="text-center">-</td>';
          /*Yearly Average calculation starts*/
          $output.='<td class="text-center">-</td>';
          $output.='<td class="text-center">-</td>';
          $output.='</tr>';
        }
      } 
      /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
      $output.='<tr><td><b>Total</b></td>';
      $quarterArray1=array('Quarter1');
      $quartrTotalArray = array('quarter' =>'Quarter1','onreportcard'=>'1','letter'=>'#','stuid'=>$stuid);
      $this->db->where($quartrTotalArray);
      $this->db->select_sum('total','total');
      $quartrTotal = $this->db->get('reportcard'.$gradesec.$max_year);
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
      $output.='<td class="text-center" colspan="2">-</td>';
      $output.='<td class="text-center" colspan="2">-</td>';
      /*Quarter3 & Quarter4 Horizontal Total calculation starts*/
      $output.='<td class="text-center" colspan="2">-</td>';
      $output.='<td class="text-center" colspan="2">-</td>';
      /*Semester2 Horizontal Total calculation starts*/
      $output.='<td class="text-center" colspan="2">-</td>';

      /*Yearly Average Horizontal Total calculation starts*/
      $output.='<td class="text-center" colspan="2">-</td>';
      $output.='</tr>';
          /*Horizontal Average calculation starts*/
      $output.='<tr><td><b>Average</b></td>';
          /*1st and snd quarter calculation starts*/
      $quarterArray1=array('Quarter1');
      $arrayRpAverage = array('grade' => $gradesec, 'rpbranch' => $branch,'onreportcard'=>'1','academicyear'=>$max_year,'letter'=>'#');
      $this->db->where($arrayRpAverage);
      $this->db->group_by("subject");  
      $this->db->order_by("subjorder","ASC"); 
      $this->db->distinct();
      $countSubject = $this->db->get('reportcard'.$gradesec.$max_year);
      $subALl=$countSubject->num_rows();
      if($subALl>0){
        $quartrTotalArray = array('quarter' =>'Quarter1','onreportcard'=>'1','letter'=>'#','stuid'=>$stuid);
        $this->db->where($quartrTotalArray);
        $this->db->select_sum('total','total');
        $quartrTotal = $this->db->get('reportcard'.$gradesec.$max_year);

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
      /*1st Semester average starts*/
      $output.='<td class="text-center" colspan="2">-</td>';
      /*3rd and 4th quarter horizontal average calculation starts*/
      $output.='<td class="text-center" colspan="2">-</td>';
      $output.='<td class="text-center" colspan="2">-</td>';
      /*Semester2 Horizontal Average calculation starts*/
      $output.='<td class="text-center" colspan="2">-</td>';
      $output.='<td class="text-center" colspan="2">-</td>';
      /*Yearly Average Horizontal Average calculation starts*/
     $output.='<td class="text-center" colspan="2">-</td>';
      $output.='</tr>';
      /*Number of student calculation starts*/
      $output.='<tr><td><b style="white-space: nowrap">No. Of Student</b></td>';
      if($querySubject->num_rows()>0){
        $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
      }else{
        $output.='<td class="text-center" colspan="2">-</td>';
      }
      $output.='<td class="text-center" colspan="2">-</td>';
      $output.='<td class="text-center" colspan="2">-</td>';
      $output.='<td class="text-center" colspan="2">-</td>';
      $output.='<td class="text-center" colspan="2">-</td>';
      $output.='<td class="text-center" colspan="2">-</td>';
      $output.='<td class="text-center" colspan="2">-</td>';
          /*if($queryReportCardQ2->num_rows()>0){
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center" colspan="2">-</td>';
            $output.='<td class="text-center" colspan="2">-</td>';
          }
          if($queryReportCardQ3->num_rows()>0){
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }
          if($queryReportCardQ4->num_rows()>0){
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
            $output.='<td class="text-center" colspan="2">'.$stuAll.'</td>';
          }else{
            $output.='<td class="text-center" colspan="2">-</td>';
            $output.='<td class="text-center" colspan="2">-</td>';
            $output.='<td class="text-center" colspan="2">-</td>';
          }*/
          $output.='</tr>';
          /*student conduct calculation starts*/
          $output.='<tr><td><b>Conduct</b></td>';
          /*1st and 2nd quarter conduct calculation starts*/
          $quarterValue='Quarter1';
          /*foreach ($quarterArray1 as $quarterValue) {*/
          $BSArray = array('quarter' =>'Quarter1','academicyear'=>$max_year,'bsname'=>'Conduct','stuid'=>$stuid,'');
          $this->db->where($BSArray);
          $eachQuarterBasicskill = $this->db->get('basicskillvalue'.$gradesec.$max_year);
          if($eachQuarterBasicskill->num_rows()>0){
            $valueBS=$eachQuarterBasicskill->row();
            $getValue=$valueBS->value;
            $output.='<td class="text-center" colspan="2">'.$getValue.'</td>';
          }else{
            $output.='<td class="text-center" colspan="2">-</td>';
          }
         /* }*/
          /*1st Semester conduct*/
          $output.='<td class="text-center" colspan="2">-</td>';
          /*3rd and 4th quarter conduct*/
          $output.='<td class="text-center" colspan="2">-</td>';
          /*2nd Semester conduct*/
          $output.='<td class="text-center" colspan="2">-</td>';
          $output.='<td class="text-center" colspan="2">-</td>';
          /*yearly conduct*/
          $output.='<td class="text-center" colspan="2">-</td>';
          $output.='<td class="text-center" colspan="2">-</td>';
          $output.='</tr>';
          /*Absetn days calculation starts*/
          $output.='<tr><td><b style="white-space: nowrap">Absence Days</b></td>';
          /*1st and 2nd quarter absence days*/
          $qvalue='Quarter1';
         /* foreach ($quarterArray1 as $qvalue) {*/
            $query_total_absent=$this->db->query("select totabs from attendance where stuid='$username1' and quarterab='$qvalue' and academicyear='$max_year' ");
            if($query_total_absent->num_rows()>0){
              foreach ($query_total_absent->result() as $absent){
                if($absent->totabs>0)
                {
                  $output .= '<td class="text-center" colspan="2"><B>'.$absent->totabs.'</B></td>';
                }
                else{
                  $output .= '<td class="text-center" colspan="2"><B>-</B></td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
         /* }*/
          /*1st semester absent days*/
          $output.='<td class="text-center" colspan="2">-</td>';
          $output.='<td class="text-center" colspan="2">-</td>';
          $output.='<td class="text-center" colspan="2">-</td>';
          $output.='<td class="text-center" colspan="2">-</td>';
          $output.='<td class="text-center" colspan="2">-</td>';
          $output.='<td class="text-center" colspan="2">-</td>';
          /*quarter 3 and 4th quarter absent days*/
          

          /*2nd semester absent days*/
          
          /* Yearly absent days*/
          
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
            $output .='<tr><th colspan="5" class="text-center">'.$dateYear.'/2022 G.C '.$max_year.' E.C Basic Skills and Behaviour Progress Report</th></tr>';
            $output .='<tr><th>Evaluation Area</th>';
            $quarterArrayQ=array('Quarter1','Quarter2','Quarter3','Quarter4');
            foreach ($quarterArrayQ as $qvalue) 
            {
              $output .='<th class="text-center">'.$qvalue.'</th>';
            }
            /*$quarterArrayQ2=array('Quarter1');*/
            foreach ($queryCategory->result() as $bscatvalue) {
              $bscategory=$bscatvalue->bscategory;
              $query_basicskill=$this->db->query(" Select * from basicskill where grade='$grade' and academicyear='$max_year' and bsname!='Conduct' and bscategory='$bscategory' order by bsname ASC ");       
              $output .='<tr><th colspan="5" id="BGS" class="text-center">'.$bscategory.'</th>';
              foreach ($query_basicskill->result() as $bsvalue) {
                $bsname=$bsvalue->bsname;
                $output .='<tr><td>'.$bsvalue->bsname.'</td>';
                  $query_bsvalue=$this->db->query(" Select * from basicskillvalue".$gradesec.$max_year." where stuid='$stuid' and academicyear='$max_year' and bsname='$bsname' and quarter='Quarter1' ");
                  if($query_bsvalue->num_rows()>0) {
                    foreach ($query_bsvalue ->result() as $bsresult) {
                      $output .='<td class="text-center">'.$bsresult->value.'</td>';
                    }
                  }else {
                    $output .='<td class="text-center">-</td>';
                  }
                $output .='<td class="text-center">-</td>';
                $output .='<td class="text-center">-</td>';
                $output .='<td class="text-center">-</td>';
                $output .='</tr>';
              }
              
            }
            $output .='</table></div><br>';/*basic skill table closed*/
          }
          $queryEvaKey=$this->db->query("select * from bstype where academicyear='$max_year' and btgrade='$grade' ");
          if($queryEvaKey->num_rows()>0){
            $output.= '<div class="row"><div class="col-lg-6">
            <div id="ENS" class="table-responsive">
            <table width="100%" id="ENS" class="tabler table-borderedr table-md" cellspacing="5" cellpadding="5">';
            $output .='<th class="text-center" colspan="2">Evaluation Key</th>';
            foreach ($queryEvaKey->result() as $keyVValue) {
              $output .='<tr><td class="text-center">'.$keyVValue->bstype.'</td>';
              $output .='<td class="text-center">'.$keyVValue->bsdesc.'</td></tr>';
            }
            $output .='</table></div></div>';/*Evaluation key table closed*/
            $output .='<div class="col-lg-6"><small class="time"> Parents should help and give advice for their child in the areas where it is required.<br><i>
            Let Us work together for our  children!</i></small></div></div>';
          }else{
            $output .='No Evaluation Key found';
          }
          $output .='</div><br>';/*basic skill column closed*/
          $output.='</div>';/*class row closed*/
          $output.='<div class="dropdown-divider2"></div><h6 class="text-center"><strong>You can always do better than this!</strong></h6>';
        }
           
    return $output;
  }