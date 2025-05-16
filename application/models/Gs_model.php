<?php
class gs_model extends CI_Model{
	function insert($data)
	{
		$this->db->insert('sample_data', $data);
	}
	function can_register($username){
		$this->db->where('username',$username);
		$query=$this->db->get('users');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}
	function get_max_year(){
		$query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row_array();
        $max_year=$row['year'];
        return $max_year;
	}
	function get_session_branch(){
		$queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$userType' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        return $accessbranch;
	}
	function can_update_username($id,$username){
		$this->db->where('id!=',$id);
		$this->db->where('username',$username);
		$query=$this->db->get('users');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}
	function register_user($data){
		$query=$this->db->insert('users',$data);
		if($query){
			return true;
		}else{
			return false;
		}
	}
	function register_new_student($data,$username,$stuid){
		$this->db->where('username =',$username);
		$this->db->or_where('unique_id =',$stuid);
		$query=$this->db->get('users');
		if($query->num_rows() > 0){
			return false;
		}else{
			$this->db->insert('users',$data);
			return true;
		}
	}
	function can_login($username,$password){
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$this->db->where('isapproved','1');
		$this->db->where('status','Active');
		$query=$this->db->get('users');
		if($query->num_rows() > 0){
			return $query->row();
		}
		else{
			return false;
		}
	}
	function Loged_users($logged_id,$date_now,$datetime,$data1,$data2,$data3,$data4){
		$data=array(
			'logged_user'=>$logged_id,
			'datet'=>$date_now,
			'dateime'=>$datetime,
			'browser'=>$data1,
			'bversion'=>$data2,
			'ipaddress'=>$data3,
			'platform'=>$data4
		);
		$this->db->insert('loggeduser',$data);
	}
	function fetch_logged_user($date_now){
		$this->db->where('username!=','Joss');
		$this->db->where('datet',$date_now);
		$this->db->select('*');
		$this->db->from('loggeduser');
		$this->db->join('users',
		'users.id=loggeduser.logged_user');
		$query = $this->db->get();
        return $query;
	}
	function my_sessions($logged_id){
		$this->db->where('logged_user',$logged_id);
		$this->db->select('*');
		$this->db->from('loggeduser');
		$this->db->join('users',
		'users.id=loggeduser.logged_user');
		$query = $this->db->get();
        return $query->result();
	}
	function fetch_term($max_year){
		$this->db->where('Academic_year',$max_year);
		$this->db->group_by('term');
		$this->db->order_by('term','ASC');
		$query=$this->db->get('quarter');
		return $query->result();
	}
	function fetch_term_student($max_year,$grade){
		$this->db->where('Academic_year',$max_year);
		$this->db->where('termgrade',$grade);
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
	function academic_year_filter(){
		$this->db->select_max('year_name');
		$query=$this->db->get('academicyear');
		return $query->result();
	}
	function my_subject($max_year,$grade){
		$this->db->order_by('Subj_name','ASC');
		$this->db->where(array('Academic_Year'=>$max_year));
		$this->db->where(array('Grade'=>$grade));
		$query=$this->db->get('subject');
		return $query->result();
	}
	function fetch_school(){
		$query=$this->db->get('school');
		return $query->result();
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
	function my_total_absents($max_year,$user){
		/*$this->db->where('academicyear',$max_year);
    $queryCheck = $this->db->get('enableapprovemark');
    if($queryCheck->num_rows()>0){
    	$this->db->where('attendance.stuid',$user);
			$this->db->where('attendance.approved','1');
			$this->db->where('attendance.academicyear',$max_year);
			$this->db->order_by('aid','DESC');
			$query = $this->db->get('attendance');
    }else{*/
    	$this->db->where('attendance.stuid',$user);
		/*$this->db->where('attendance.approved','1');*/
		$this->db->where('attendance.academicyear',$max_year);
		$this->db->order_by('aid','DESC');
		$this->db->select('users.fname,users.mname,attendance.absentype, attendance.absentdate');
        $this->db->from('attendance');
        $this->db->join('users', 
            'users.username = attendance.attend_by');
        $query = $this->db->get();

    	/*$this->db->where('attendance.stuid',$user);
		$this->db->where('attendance.approved','1');
		$this->db->where('attendance.academicyear',$max_year);
		$this->db->order_by('aid','DESC');
		$query = $this->db->get('attendance');*/
    /*}*/
		
		return $query;
	}
	function loadMySubject($max_year,$grade){
		$this->db->order_by('Subj_name','ASC');
		$this->db->where('Academic_Year',$max_year);
		$this->db->where('Grade',$grade);
		$this->db->where('student_view','1');
		$query=$this->db->get('subject');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="row">';
			foreach($query->result() as $subjname){
				$output.='<div class="col-md-6 col-12" id="view_ThisSubjectResult_gs">
					<a href="javascript:void(0)" class="view_ThisSubjectResult_gs" id="viewThisSubjectResult" value="'.$subjname->Subj_name.'">
						<div class="card-body StudentViewTextInfo">
	            <div class="support-ticket media">
	              <div class="media-body">
	                <div class="badge badge-pill badge-primary float-right" ><i class="fas fa-chevron-right"></i>
	                </div>
	                <span class="font-weight-bold font-24">'.$subjname->Subj_name.'</span>
	                <br>
	                <small class="text-muted">Click here to see '.$subjname->Subj_name.' result</small>
	              </div>
	            </div>
	          </div>
	        </a>
        </div>';
			}
			$output.='</div>';
		}else{
			$output.='<div class="alert alert-light alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> No subject found.
            </div></div>';
		}
		return $output;
	}
	function fetch_my_markresult($gs_branches,$gs_gradesec,$gs_subject,$grade,$max_year,$id)
	{
		$output='';
		 if(isset($gs_subject['subid']) ){
		 	$gs_subject=$gs_subject['subid'];
		 	$query_quarter = "select term from quarter where Academic_year =? and termgrade=?";
			$query_quarter = $this->db->query($query_quarter,array($max_year,$grade));
			if($query_quarter->num_rows()>0){
				$output.='<button class="btn btn-default StudentViewTextInfo font-weight-bold font-22" id="backToSubjectPage" ><i class="fas fa-chevron-left" style="font-size:30px"></i> Go Back</button><div class="dropdown-divider"></div> ';
				foreach($query_quarter->result() as $termQuarter){
					$gs_quarter=$termQuarter->term;
					$output.=' <div class="view_ThisSubjectResult_gs">
				          	<h4 class="title-header">'.$gs_subject.' <i class="fa fa-chevron-right"></i> '.$gs_quarter.'</h4> ';
					$queryCheck1="SHOW TABLES LIKE 'mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."'";
					$queryCheck = $this->db->query($queryCheck1);
					if ($queryCheck->num_rows()>0)
					{
						$sql="select * from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where academicyear= ? and subname=? and mgrade= ? and mbranch= ? and stuid=? ";
						$querySingleSubject=$this->db->query($sql,array($max_year,$gs_subject,$gs_gradesec,$gs_branches,$id));
						if($querySingleSubject->num_rows()>0)
						{
							$query_name = $this->db->query("select * from school");
							$row_name = $query_name->row_array();
							$school_name=$row_name['name'];
							$school_slogan=$row_name['slogan'];
							$this->db->select('us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent');
							$this->db->from('evaluation ev');
							$this->db->join('users us',
							'us.grade=ev.grade');
							$this->db->where('ev.academicyear',$max_year);
							$this->db->where('ev.quarter',$gs_quarter);
							$this->db->where('us.gradesec',$gs_gradesec);
							$this->db->where('us.branch',$gs_branches);
							$this->db->where('us.academicyear',$max_year);
							$this->db->group_by('ev.evname');
							$this->db->order_by('ev.eid','ASC');
							$evalname_query = $this->db->get();
							
				        	$average=0;
				        	foreach ($evalname_query->result_array() as $mark_name){
				        		$evName=$mark_name['evname'];
		            			$grade=$mark_name['grade'];
		            			$mname_gs=$mark_name['eid'];
		            			$sql1="select * from evaluationcustom where academicyear =? and customgrade=? and customsubject=? and customasses=? and customquarter=? ";
				        			$queryCheckPercentage=$this->db->query($sql1,array($max_year,$grade,$gs_subject,$evName,$gs_quarter));
				            	if($queryCheckPercentage->num_rows()>0){
				            		$rowPercent=$queryCheckPercentage->row();
				            		$percent=$rowPercent->custompercent;
				            	}else{
				            		$percent= $mark_name['percent'];
				            	}
				        		$output.=' <div class="support-ticket media StudentViewTextInfo fetch_my_markresult font-weight-bold font-22">
			                  		<div class="media-body">
			                    		<span class="font-weight-bold">'.$mark_name['evname'].'( '.$percent.'%)</span> ';
			                    		$query_value= "select markname,sum(value) as total from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where  subname=? and quarter=? and evaid=? and mbranch=? group by markname order by mid ASC";	 
				            			$query_value = $this->db->query($query_value,array($gs_subject,$gs_quarter,$mname_gs,$gs_branches));
										if($query_value->num_rows()>0){
											$totalMark=0;$outofTot=0;
											foreach ($query_value->result_array() as $value) {
												$markNameStu=$value['markname'];
												$queryStuValue1 = "select value,sum(value) as total,sum(outof) as outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where stuid=? and subname=? and quarter=? and evaid=? and mbranch=? and markname=? group by markname order by mid ASC";
												$queryStuValue= $this->db->query($queryStuValue1,array($id,$gs_subject,$gs_quarter,$mname_gs,$gs_branches,$markNameStu));
												if($queryStuValue->num_rows()>0){
													foreach ($queryStuValue->result_array() as $kevalue) {
														$outofTot=$outofTot+$kevalue['outof'];
														$totalMark=$totalMark+$kevalue['value'];
													}
												}
												$queryMvalue1 ="select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname=? and quarter=? and evaid=? and mgrade=? and mbranch=? group by markname order by mid ASC";
												$queryMvalue=$this->db->query($queryMvalue1,array($gs_subject,$gs_quarter,$mname_gs,$gs_gradesec,$gs_branches));
								            	$sumOutOf=0;
									            foreach ($queryMvalue->result_array() as $mark_name) {
									            	$sumOutOf=$mark_name['outof'] + $sumOutOf;	
									            }
								            }
						            		if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='')
					            			{
			                    				$conver= ($totalMark *$percent )/$sumOutOf;
					                    		if($totalMark>0){
					                    			$output.='
					                    			<div class="badge badge-pill badge-primary mb-1 float-right">Result: '.number_format((float)$conver,2,'.','').'</div>';
					                    		}else{
					                    			$output.='<div class="badge badge-pill badge-primary mb-1 float-right">Result: 0.00</div>';
					                    		}
							            		$average =$conver + $average;
					                		}else{
					                			$output.='<div class="badge badge-pill badge-primary mb-1 float-right">Result: 0.00</div>';
					                		}
						            	}else{
						            		$output.='<div class="badge badge-pill badge-primary mb-1 float-right">Result: 0.00</div>';
						            	}
					       		$output.='</div></div>';
				          	}
				          	$output.='<div class="badge badge-pill badge-success float-right StudentViewTextInfo">  '.number_format((float)$average,2,'.','').'</div><br>';
				          	$this->db->select('rc.commentvalue');
							$this->db->from('reportcardcomments rc');
							$this->db->join('users us',
							'us.grade=rc.grade');
							$this->db->where('rc.academicyear',$max_year);
							$this->db->where('us.id',$id);
							$this->db->where('us.gradesec',$gs_gradesec);
							$this->db->where('us.academicyear',$max_year);
							$this->db->where('rc.mingradevalue <=',$average);
							$this->db->where('rc.maxgradevalue >=',$average);
							$reportCardComments = $this->db->get();
			                if($reportCardComments->num_rows()>0){
			                  foreach($reportCardComments->result() as $commentValue){
			                    $output .= $commentValue->commentvalue;
			                  }
			                }
					       	$average=0;
							$output.='<a href="javascript:void(0)" class="btn btn-default StudentViewTextInfo view_ThisSubjectResult_gs pull-right" id="viewMyDetailResult" value="'.$gs_quarter.'" name="'.$gs_subject.'">View Detail <i class="fas fa-chevron-right"></i> </a><br>';
						}else{
				    		$output.='<div class="alert alert-light alert-dismissible show fade">
				            <div class="alert-body">
				                <button class="close"  data-dismiss="alert">
				                    <span>&times;</span>
				                </button>
				            	<i class="fas fa-exclamation-triangle"> </i> '.$gs_subject.' data not found for season '.$gs_quarter.'.
				        	</div></div>';
						}
					}else{
						$output.='<div class="alert alert-light alert-dismissible show fade">
				            <div class="alert-body">
			                <button class="close"  data-dismiss="alert">
			                    <span>&times;</span>
			                </button>
			            	<i class="fas fa-exclamation-triangle"> </i> Subject data not found.Please contact your teacher
			        	</div></div>';
					}
					$output.='</div><hr>';
				}
			}else{
				$output.='<div class="alert alert-light alert-dismissible show fade">
		            <div class="alert-body">
	                <button class="close"  data-dismiss="alert">
	                    <span>&times;</span>
	                </button>
	            	<i class="fas fa-exclamation-triangle"> </i> Subject data not inserted.Please contact your teacher
	        	</div></div>';
			}
		}
		return $output;
	}
	function fetch_my_markresultApproved($gs_branches,$gs_gradesec,$gs_subject,$grade,$max_year,$id)
	{
		$output='';
		 if(isset($gs_subject['subid']) ){
		 	$gs_subject=$gs_subject['subid'];
		 	$query_quarter = "select term from quarter where Academic_year =? and termgrade=?";
			$query_quarter = $this->db->query($query_quarter,array($max_year,$grade));
			if($query_quarter->num_rows()>0){
				$output.='<button class="btn btn-default StudentViewTextInfo font-weight-bold font-22" id="backToSubjectPage" ><i class="fas fa-chevron-left" style="font-size:30px"></i> Go Back</button><div class="dropdown-divider"></div> ';
				foreach($query_quarter->result() as $termQuarter){
					$gs_quarter=$termQuarter->term;
					$output.='<div class="view_ThisSubjectResult_gs"> 
				          <h4 class="title-header">'.$gs_subject.' <i class="fa fa-chevron-right"></i> '.$gs_quarter.'</h4>  ';
					$queryCheck1="SHOW TABLES LIKE 'mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."'";
					$queryCheck = $this->db->query($queryCheck1);
					if ($queryCheck->num_rows()>0)
					{
						$sql="select * from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where academicyear= ? and subname=? and mgrade= ? and mbranch= ? and stuid=? and approved=? ";
						$querySingleSubject=$this->db->query($sql,array($max_year,$gs_subject,$gs_gradesec,$gs_branches,$id,'1'));
						if($querySingleSubject->num_rows()>0)
						{
							$query_name = $this->db->query("select * from school");
							$row_name = $query_name->row_array();
							$school_name=$row_name['name'];
							$school_slogan=$row_name['slogan'];
							$this->db->select('us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent');
							$this->db->from('evaluation ev');
							$this->db->join('users us',
							'us.grade=ev.grade');
							$this->db->where('ev.academicyear',$max_year);
							$this->db->where('ev.quarter',$gs_quarter);
							$this->db->where('us.gradesec',$gs_gradesec);
							$this->db->where('us.branch',$gs_branches);
							$this->db->where('us.academicyear',$max_year);
							$this->db->group_by('ev.evname');
							$this->db->order_by('ev.eid','ASC');
							$evalname_query = $this->db->get();
							
				        	$average=0;
				        	foreach ($evalname_query->result_array() as $mark_name){
				        			$evName=$mark_name['evname'];
		            			$grade=$mark_name['grade'];
		            			$mname_gs=$mark_name['eid'];
		            			$sql1="select * from evaluationcustom where academicyear =? and customgrade=? and customsubject=? and customasses=? and customquarter=? ";
				        			$queryCheckPercentage=$this->db->query($sql1,array($max_year,$grade,$gs_subject,$evName,$gs_quarter));
				            	if($queryCheckPercentage->num_rows()>0){
				            		$rowPercent=$queryCheckPercentage->row();
				            		$percent=$rowPercent->custompercent;
				            	}else{
				            		$percent= $mark_name['percent'];
				            	}
				        			$output.=' <div class="support-ticket media StudentViewTextInfo fetch_my_markresult font-weight-bold font-22">
			                  <div class="media-body">
			                    <span class="font-weight-bold">'.$mark_name['evname'].'(
			                      	 '.$percent.'%)</span> ';
			                    $query_value= "select markname,sum(value) as total from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where  subname=? and quarter=? and evaid=? and mbranch=? and approved=? group by markname order by mid ASC";	 
				            			$query_value = $this->db->query($query_value,array($gs_subject,$gs_quarter,$mname_gs,$gs_branches,'1'));
													if($query_value->num_rows()>0){
														$totalMark=0;$outofTot=0;
														foreach ($query_value->result_array() as $value) {
															$markNameStu=$value['markname'];
															$queryStuValue1 = "select value,sum(value) as total,sum(outof) as outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where stuid=? and subname=? and quarter=? and evaid=? and mbranch=? and markname=? and approved=? group by markname order by mid ASC";
															$queryStuValue= $this->db->query($queryStuValue1,array($id,$gs_subject,$gs_quarter,$mname_gs,$gs_branches,$markNameStu,'1'));
															if($queryStuValue->num_rows()>0){
																foreach ($queryStuValue->result_array() as $kevalue) {
																	$outofTot=$outofTot+$kevalue['outof'];
																	$totalMark=$totalMark+$kevalue['value'];
																}
															}
															$queryMvalue1 ="select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname=? and quarter=? and evaid=? and mgrade=? and mbranch=? and approved=? group by markname order by mid ASC";
															$queryMvalue=$this->db->query($queryMvalue1,array($gs_subject,$gs_quarter,$mname_gs,$gs_gradesec,$gs_branches,'1'));
								            		$sumOutOf=0;
									            foreach ($queryMvalue->result_array() as $mark_name) {
									            	$sumOutOf=$mark_name['outof'] + $sumOutOf;	
									            }
								            }
						            		if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='')
					            			{
			                    		$conver= ($totalMark *$percent )/$sumOutOf;
			                    		if($totalMark>0){
			                    			$output.='
			                    			<div class="badge badge-pill badge-primary mb-1 float-right">Result: '.number_format((float)$conver,2,'.','').'</div>';
			                    		}else{
			                    			$output.='<div class="badge badge-pill badge-primary mb-1 float-right">Result: 0.00</div>';
			                    		}
							            		$average =$conver + $average;
				                		}else{
				                			$output.='<div class="badge badge-pill badge-primary mb-1 float-right">Result: 0.00</div>';
				                		}
						            	}else{
						            		$output.='<div class="badge badge-pill badge-primary mb-1 float-right">Result: 0.00</div>';
						            	}
					       		$output.='</div></div>';
				          	}
				          	$output.='<div class="badge badge-pill badge-success mb-1 float-right">  '.number_format((float)$average,2,'.','').'</div><br>';
				         	$this->db->select('rc.commentvalue');
							$this->db->from('reportcardcomments rc');
							$this->db->join('users us',
							'us.grade=rc.grade');
							$this->db->where('rc.academicyear',$max_year);
							$this->db->where('us.id',$id);
							$this->db->where('us.gradesec',$gs_gradesec);
							$this->db->where('us.academicyear',$max_year);
							$this->db->where('rc.mingradevalue <=',$average);
							$this->db->where('rc.maxgradevalue >=',$average);
							$reportCardComments = $this->db->get();
			                if($reportCardComments->num_rows()>0){
			                  foreach($reportCardComments->result() as $commentValue){
			                    $output .= $commentValue->commentvalue;
			                  }
			                }
				        	$average=0;
							$output.=' <a href="javascript:void(0)" class="btn btn-default StudentViewTextInfo pull-right" id="viewMyDetailResult" value="'.$gs_quarter.'" name="'.$gs_subject.'">View Detail <i class="fas fa-chevron-right"></i> </a> <br>';
						}else{
				    		$output.='<div class="alert alert-light alert-dismissible show fade">
				            <div class="alert-body">
				                <button class="close"  data-dismiss="alert">
				                    <span>&times;</span>
				                </button>
				            	<i class="fas fa-exclamation-triangle"> </i> '.$gs_subject.' data not found for season '.$gs_quarter.'.
				        	</div></div>';
						}
					}else{
						$output.='<div class="alert alert-light alert-dismissible show fade">
				            <div class="alert-body">
			                <button class="close"  data-dismiss="alert">
			                    <span>&times;</span>
			                </button>
			            	<i class="fas fa-exclamation-triangle"> </i> Subject data not found.Please contact your teacher
			        	</div></div>';
					}
					$output.='</div><hr>';
				}
			}else{
				$output.='<div class="alert alert-light alert-dismissible show fade">
		            <div class="alert-body">
	                <button class="close"  data-dismiss="alert">
	                    <span>&times;</span>
	                </button>
	            	<i class="fas fa-exclamation-triangle"> </i> Subject data not inserted.Please contact your teacher
	        	</div></div>';
			}
		}
		return $output;
	}
	function fetch_mydeatil_markresult($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$grade,$max_year,$id){
		$output='';

			$queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."'");
			if ($queryCheck->num_rows()>0)
			{
				$this->db->select('*');
				$this->db->from('mark'.$gs_branches.$gs_gradesec.$gs_quarter.$max_year);
				$this->db->where('academicyear',$max_year);
				$this->db->where('subname',$gs_subject);
				$this->db->where('mgrade',$gs_gradesec);
				$this->db->where('mbranch',$gs_branches);
				$this->db->where('stuid',$id);
				$querySingleSubject = $this->db->get();
				if($querySingleSubject->num_rows()>0)
				{
					$query_name = $this->db->query("select * from school");
					$row_name = $query_name->row_array();
					$school_name=$row_name['name'];
					$school_slogan=$row_name['slogan'];
					$output.='<button class="btn btn-default StudentViewTextInfo font-weight-bold font-22" id="backToSubjectPage" ><i class="fas fa-chevron-left" style="font-size:30px"></i> Go Back</button><div class="dropdown-divider"></div> ';
					$output.='<div style="height:92%;page-break-inside:avoid;"><h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
			    $output.='<div class="pull-right">
			    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
			    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';

			    $this->db->select('us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent');
					$this->db->from('evaluation ev');
					$this->db->join('users us',
					'us.grade=ev.grade');
					$this->db->where('ev.academicyear',$max_year);
					$this->db->where('ev.quarter',$gs_quarter);
					$this->db->where('us.gradesec',$gs_gradesec);
					$this->db->where('us.branch',$gs_branches);
					$this->db->where('us.academicyear',$max_year);
					$this->db->group_by('ev.evname');
					$this->db->order_by('ev.eid','ASC');
					$evalname_query = $this->db->get();
					$output.='<div class="table-responsive">
	        <table class="tabler table-borderedr table-hover" style="width:100%;height:85%">
	        <thead>
	        <tr>';
        	foreach ($evalname_query->result_array() as $evalua_name) {
        		$mname_gs=$evalua_name['eid'];
        		$queryMvalue1 ="select markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname=? and quarter=? and evaid=? and mbranch=? group by markname order by mid ASC";
        		$queryMvalue=$this->db->query($queryMvalue1,array($gs_subject,$gs_quarter,$mname_gs,$gs_branches));
        		$colSpan=$queryMvalue->num_rows() +2;
        		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
        	}
        	$output.='<th class="text-center">Total</th>
        	<th rowspan="2" class="text-center">Sig.</th><tr>';
        	foreach ($evalname_query->result_array() as $evalua_name) {
        		$mname_gs=$evalua_name['eid'];
        		$percent=$evalua_name['percent'];
        		$evName=$evalua_name['evname'];
    				$grade=$evalua_name['grade'];
        		$sumOutOf=0;
        		$queryMvalue1 ="select markname,outof,sum(outof) as sum_outof  from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname=? and quarter=? and evaid=? and mbranch=? group by markname order by mid ASC";
        		$queryMvalue=$this->db->query($queryMvalue1,array($gs_subject,$gs_quarter,$mname_gs,$gs_branches));
          	foreach ($queryMvalue->result_array() as $mark_name) {
          		$sumOutOf=$mark_name['outof'] + $sumOutOf;
          		$output.='<td class="text-center">'.$mark_name['markname'].' ('.$mark_name['outof'].')</td>';
          	}
          	$output.='<td class="text-center"><b>Tot('.$sumOutOf.')</b></td>';
          	$queryCheckPercentage1="select * from evaluationcustom where academicyear =? and customgrade=? and customsubject=? and customasses=? and customquarter=? ";
						$queryCheckPercentage=$this->db->query($queryCheckPercentage1,array($max_year,$grade,$gs_subject,$evName,$gs_quarter));
          	if($queryCheckPercentage->num_rows()>0){
          		$rowPercent=$queryCheckPercentage->row();
          		$customPercent=$rowPercent->custompercent;
          		$output.='<td style="text-align:center;background-color:#e3e3e3"><B>From '.$customPercent.'</B></td>';
          	}else{
          		$output.='<td class="text-center" style="background-color:#e3e3e3"><b>From '.$evalua_name['percent'].'%</b></td>';
          	}
          }
          $output.='<td rowspan="1" class="text-center" style="background-color:#e3e3e3"><B>100</B></td>';
        	$output.='</tr>';
        	$stuNO=1;
	        $average=0;
	        foreach ($evalname_query->result_array() as $mark_name) {
      			$evName=$mark_name['evname'];
      			$grade=$mark_name['grade'];
      			$mname_gs=$mark_name['eid'];
      			$queryCheckPercentage1="select * from evaluationcustom where academicyear =? and customgrade=? and customsubject=? and customasses=? and customquarter=? ";
      			$queryCheckPercentage=$this->db->query($queryCheckPercentage1,array($max_year,$grade,$gs_subject,$evName,$gs_quarter));
          	if($queryCheckPercentage->num_rows()>0){
          		$rowPercent=$queryCheckPercentage->row();
          		$percent=$rowPercent->custompercent;
          	}else{
          		$percent= $mark_name['percent'];
          	}
	          $query_value1 ="select markname,sum(value) as total from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where  subname=? and quarter=? and evaid=? and mbranch=? group by markname order by mid ASC";
	          $query_value = $this->db->query($query_value1,array($gs_subject,$gs_quarter,$mname_gs,$gs_branches));
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue1 = "select value,sum(value) as total,sum(outof) as outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where stuid=? and subname=? and quarter=? and evaid=? and mbranch=? and markname=? group by markname order by mid ASC";
								$queryStuValue = $this->db->query($queryStuValue1,array($id,$gs_subject,$gs_quarter,$mname_gs,$gs_branches,$markNameStu));
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue1 ="select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname=? and quarter=? and evaid=? and mgrade=? and mbranch=? group by markname order by mid ASC";
								$queryMvalue = $this->db->query($queryMvalue1,array($gs_subject,$gs_quarter,$mname_gs,$gs_gradesec,$gs_branches));
				        $sumOutOf=0;
		            foreach ($queryMvalue->result_array() as $mark_name) {
		            	$sumOutOf=$mark_name['outof'] + $sumOutOf;	
		            }
            	}
		          if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='') {
            		$conver= ($totalMark *$percent )/$sumOutOf;
            		if($totalMark>0){
            			$output.='<td style="text-align:center;">'.$totalMark.'</td>';
            			$output.='<td style="text-align:center;background-color:#e3e3e3"><B>'.number_format((float)$conver,2,'.','').'</B></td>';
            		}else{
            			$output.='<td class="text-danger" style="text-align:center;">NG</td>';
            			$output.='<td class="text-danger" style="text-align:center; background-color:#e3e3e3">NG</td>';
            		}
    						$average =$conver + $average;
        			}else{
        				$output.='<td style="text-align:center;">-</td>';
        				$output.='<td style="text-align:center;background-color:#e3e3e3">-</td>';
        			}
          	}else{
          		$output.='<td style="text-align:center;">-</td>';
          		$output.='<td style="text-align:center;background-color:#e3e3e3">-</td>';
          	}
        	}
	        $output.='<td style="text-align:center;background-color:#e3e3e3""><B>'.number_format((float)$average,2,'.','').'</B></td>';
	        $average=0;
	        $output.='<td style="text-align:center;"></td>';
					$output.='</table></div>';
					$output.='</div>';
				}else{
		    	$output.='<div class="alert alert-light alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            	<i class="fas fa-exclamation-triangle"> </i> Data not found.
        	</div></div>';
				}
				
			}else{
				$output.='<div class="alert alert-light alert-dismissible show fade">
          <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
        	<i class="fas fa-exclamation-triangle"> </i> Subject data not inserted.Please contact your teacher
    	</div></div>';
			}
		return $output;
	}
	function fetch_mydeatil_markresultApproved($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$grade,$max_year,$id){
		$output='';
			$queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."'");
			if ($queryCheck->num_rows()>0)
			{
				$this->db->select('*');
				$this->db->from('mark'.$gs_branches.$gs_gradesec.$gs_quarter.$max_year);
				$this->db->where('academicyear',$max_year);
				$this->db->where('subname',$gs_subject);
				$this->db->where('mgrade',$gs_gradesec);
				$this->db->where('mbranch',$gs_branches);
				$this->db->where('stuid',$id);
				$this->db->where('approved','1');
				$querySingleSubject = $this->db->get();
				if($querySingleSubject->num_rows()>0)
				{
					$query_name = $this->db->query("select * from school");
					$row_name = $query_name->row_array();
					$school_name=$row_name['name'];
					$school_slogan=$row_name['slogan'];
					$output.='<button class="btn btn-default StudentViewTextInfo font-weight-bold font-22" id="backToSubjectPage" ><i class="fas fa-chevron-left" style="font-size:30px"></i> Go Back</button><div class="dropdown-divider"></div> ';
					$output.='<div style="height:92%;page-break-inside:avoid;"><h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
			    $output.='<div class="pull-right">
			    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
			    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';

			    $this->db->select('us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent');
					$this->db->from('evaluation ev');
					$this->db->join('users us',
					'us.grade=ev.grade');
					$this->db->where('ev.academicyear',$max_year);
					$this->db->where('ev.quarter',$gs_quarter);
					$this->db->where('us.gradesec',$gs_gradesec);
					$this->db->where('us.branch',$gs_branches);
					$this->db->where('us.academicyear',$max_year);
					$this->db->group_by('ev.evname');
					$this->db->order_by('ev.eid','ASC');
					$evalname_query = $this->db->get();
					$output.='<div class="table-responsive">
	        <table class="tabler table-borderedr table-hover" style="width:100%;height:85%">
	        <thead>
	        <tr>';
        	foreach ($evalname_query->result_array() as $evalua_name) {
        		$mname_gs=$evalua_name['eid'];
        		$queryMvalue1 ="select markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname=? and quarter=? and evaid=? and mbranch=? and approved=? group by markname order by mid ASC";
        		$queryMvalue=$this->db->query($queryMvalue1,array($gs_subject,$gs_quarter,$mname_gs,$gs_branches,'1'));
        		$colSpan=$queryMvalue->num_rows() +2;
        		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
        	}
        	$output.='<th class="text-center">Total</th>
        	<th rowspan="2" class="text-center">Sig.</th><tr>';
        	foreach ($evalname_query->result_array() as $evalua_name) {
        		$mname_gs=$evalua_name['eid'];
        		$percent=$evalua_name['percent'];
        		$evName=$evalua_name['evname'];
    				$grade=$evalua_name['grade'];
        		$sumOutOf=0;
        		$queryMvalue1 ="select markname,outof,sum(outof) as sum_outof  from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname=? and quarter=? and evaid=? and mbranch=? and approved=? group by markname order by mid ASC";
        		$queryMvalue=$this->db->query($queryMvalue1,array($gs_subject,$gs_quarter,$mname_gs,$gs_branches,'1'));
          	foreach ($queryMvalue->result_array() as $mark_name) {
          		$sumOutOf=$mark_name['outof'] + $sumOutOf;
          		$output.='<td class="text-center">'.$mark_name['markname'].' ('.$mark_name['outof'].')</td>';
          	}
          	$output.='<td class="text-center"><b>Tot('.$sumOutOf.')</b></td>';
          	$queryCheckPercentage1="select * from evaluationcustom where academicyear =? and customgrade=? and customsubject=? and customasses=? and customquarter=? ";
						$queryCheckPercentage=$this->db->query($queryCheckPercentage1,array($max_year,$grade,$gs_subject,$evName,$gs_quarter));
          	if($queryCheckPercentage->num_rows()>0){
          		$rowPercent=$queryCheckPercentage->row();
          		$customPercent=$rowPercent->custompercent;
          		$output.='<td style="text-align:center;background-color:#e3e3e3"><B>From '.$customPercent.'</B></td>';
          	}else{
          		$output.='<td class="text-center" style="background-color:#e3e3e3"><b>From '.$evalua_name['percent'].'%</b></td>';
          	}
          }
          $output.='<td rowspan="1" class="text-center" style="background-color:#e3e3e3"><B>100</B></td>';
        	$output.='</tr>';
        	$stuNO=1;
	        $average=0;
	        foreach ($evalname_query->result_array() as $mark_name) {
      			$evName=$mark_name['evname'];
      			$grade=$mark_name['grade'];
      			$mname_gs=$mark_name['eid'];
      			$queryCheckPercentage1="select * from evaluationcustom where academicyear =? and customgrade=? and customsubject=? and customasses=? and customquarter=? ";
      			$queryCheckPercentage=$this->db->query($queryCheckPercentage1,array($max_year,$grade,$gs_subject,$evName,$gs_quarter));
          	if($queryCheckPercentage->num_rows()>0){
          		$rowPercent=$queryCheckPercentage->row();
          		$percent=$rowPercent->custompercent;
          	}else{
          		$percent= $mark_name['percent'];
          	}
	          $query_value1 ="select markname,sum(value) as total from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where  subname=? and quarter=? and evaid=? and mbranch=? and approved=? group by markname order by mid ASC";
	          $query_value = $this->db->query($query_value1,array($gs_subject,$gs_quarter,$mname_gs,$gs_branches,'1'));
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue1 = "select value,sum(value) as total,sum(outof) as outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where stuid=? and subname=? and quarter=? and evaid=? and mbranch=? and markname=? and approved=? group by markname order by mid ASC";
								$queryStuValue = $this->db->query($queryStuValue1,array($id,$gs_subject,$gs_quarter,$mname_gs,$gs_branches,$markNameStu,'1'));
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue1 ="select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname=? and quarter=? and evaid=? and mgrade=? and mbranch=? and approved=? group by markname order by mid ASC";
								$queryMvalue = $this->db->query($queryMvalue1,array($gs_subject,$gs_quarter,$mname_gs,$gs_gradesec,$gs_branches,'1'));
				        $sumOutOf=0;
		            foreach ($queryMvalue->result_array() as $mark_name) {
		            	$sumOutOf=$mark_name['outof'] + $sumOutOf;	
		            }
            	}
		          if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='') {
            		$conver= ($totalMark *$percent )/$sumOutOf;
            		if($totalMark>0){
            			$output.='<td style="text-align:center;">'.$totalMark.'</td>';
            			$output.='<td style="text-align:center;background-color:#e3e3e3"><B>'.number_format((float)$conver,2,'.','').'</B></td>';
            		}else{
            			$output.='<td class="text-danger" style="text-align:center;">NG</td>';
            			$output.='<td class="text-danger" style="text-align:center; background-color:#e3e3e3">NG</td>';
            		}
    						$average =$conver + $average;
        			}else{
        				$output.='<td style="text-align:center;">-</td>';
        				$output.='<td style="text-align:center;background-color:#e3e3e3">-</td>';
        			}
          	}else{
          		$output.='<td style="text-align:center;">-</td>';
          		$output.='<td style="text-align:center;background-color:#e3e3e3">-</td>';
          	}
        	}
	        $output.='<td style="text-align:center;background-color:#e3e3e3""><B>'.number_format((float)$average,2,'.','').'</B></td>';
	        $average=0;
	        $output.='<td style="text-align:center;"></td>';
					$output.='</table></div>';
					$output.='</div>';
				}else{
		    	$output.='<div class="alert alert-light alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            	<i class="fas fa-exclamation-triangle"> </i> Data not found.
        	</div></div>';
				}
				
			}else{
				$output.='<div class="alert alert-light alert-dismissible show fade">
          <div class="alert-body">
            <button class="close"  data-dismiss="alert">
                <span>&times;</span>
            </button>
        	<i class="fas fa-exclamation-triangle"> </i> Subject data not inserted.Please contact your teacher
    	</div></div>';
			}
		return $output;
	}
	function fetchDashboarAttendance($max_year,$user){
		$this->db->where('users.username',$user);
		$this->db->where('attendance.approved','1');
		$this->db->where('attendance.academicyear',$max_year);
		$this->db->order_by('aid','DESC');
		$this->db->select('*');
		$this->db->from('attendance');
		$this->db->join('users','users.username=attendance.stuid');
		$query = $this->db->get();
		$output='';
		if($query->num_rows()>0){
			$totalAbsent=$query->num_rows();
			$output.='<div class="support-ticket media pb-1 mb-3">
            <div class="media-body ml-3">
                <div class="badge badge-pill badge-warning mb-1 float-right">'.$totalAbsent.'
            </div>
            <span class="font-weight-bold">Total Absent Days</span>
            <div class="dropdown-divider"></div>';
			foreach($query->result() as $absentDate){
				$output.='<p class="my-1">'.$absentDate->absentdate.'</p>
                      <small class="text-muted">Type <span class="font-weight-bold font-13">'.$absentDate->absentype.'</span> </small> <hr>';
			}
			$output.='</div> </div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> No absent date found.
            </div></div>';
		}
		return $output;
	}
	function fetchDashboardMarkResult($gs_branches,$gs_gradesec,$gs_quarter,$grade,$max_year,$id)
	{
		$output='';
		$querySubject = $this->db->query("select * from subject where Academic_Year ='$max_year' and Grade='$grade' order by suborder ");
		$output.='<div class="card-header">
		<h5>Mark Grade & Comment for each Subject in '.$gs_quarter.'</h5></div>';
		if($querySubject->num_rows()>0){
			foreach($querySubject->result() as $calcMark){
				$gs_subject=$calcMark->Subj_name;
				$output.='<div class="card-statistic-3">
				<div class="card-body"> <div class="support-ticket media pb-1 mb-3">
				<div class="media-body ml-3"><p><b>'.$gs_subject.'</b></p>';
				$queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."' ");
				if ($queryCheck->num_rows()>0)
				{
					$querySingleSubject=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and stuid='$id' ");
					if($querySingleSubject->num_rows()>0)
					{
						$query_name = $this->db->query("select * from school");
						$row_name = $query_name->row_array();
						$school_name=$row_name['name'];
						$school_slogan=$row_name['slogan'];
						$evalname_query=$this->db->query("select us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' and us.academicyear='$max_year' group by ev.evname order by ev.eid ASC");
			        		$average=0;
		        		foreach ($evalname_query->result_array() as $mark_name)
		        		{
		        			$evName=$mark_name['evname'];
            			$grade=$mark_name['grade'];
            			$mname_gs=$mark_name['eid'];
            			$queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$gs_subject' and customasses='$evName' and customquarter='$gs_quarter' ");
		            	if($queryCheckPercentage->num_rows()>0){
		            		$rowPercent=$queryCheckPercentage->row();
		            		$percent=$rowPercent->custompercent;
		            	}else{
		            		$percent= $mark_name['percent'];
		            	}

		            		$query_value = $this->db->query("select markname,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
							if($query_value->num_rows()>0){
								$totalMark=0;$outofTot=0;
								foreach ($query_value->result_array() as $value) {
									$markNameStu=$value['markname'];
									$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
									if($queryStuValue->num_rows()>0){
										foreach ($queryStuValue->result_array() as $kevalue) {
											$outofTot=$outofTot+$kevalue['outof'];
											$totalMark=$totalMark+$kevalue['value'];
										}
									}
									$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
					            		$sumOutOf=0;
						            foreach ($queryMvalue->result_array() as $mark_name) {
						            	$sumOutOf=$mark_name['outof'] + $sumOutOf;	
						            }
			            		}
			            		if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='')
		            			{
		                    		$conver= ($totalMark *$percent )/$sumOutOf;
		            				$average =$conver + $average;
		                		}
			            	}
		            	}
		            	$output.=' <div class="badge badge-pill badge-info mb-1 float-right">'.number_format((float)$average,2,'.','').' / 100 </div> ';

		            	$queryRange=$this->db->query("select letterVal from letterange as lr cross join users as us where lr.grade=us.grade and $average between minValue and maxiValue and lr.academicYear='$max_year' and us.id='$id' and us.gradesec='$gs_gradesec' and us.academicyear='$max_year' ");
		            	$output.='<div class="card-icon card-icon-large"><i class="fa fa-book-open"></i></div><p class="my-1">';
		                if($queryRange->num_rows()>0){
		                	foreach ($queryRange->result() as $letterValue) {
		                  		$letterVal=$letterValue->letterVal;
		                  		$output.= 'Grade :'.$letterVal.'';
		                	}
		                }else{
		                   $output.= 'No value to grade your result';
		                }
		                $reportCardComments=$this->db->query("select commentvalue from reportcardcomments as rc cross join users as us where rc.grade=us.grade and $average between mingradevalue and maxgradevalue and rc.academicYear='$max_year' and us.id='$id' and us.gradesec='$gs_gradesec' and us.academicyear='$max_year' ");

		                if($reportCardComments->num_rows()>0){
		                  foreach($reportCardComments->result() as $commentValue){
		                  	$output.='<small class="text-muted"><span class="font-weight-bold font-13"> "'.$commentValue->commentvalue.'"</span> </small>';
		                  }
		                  
		                }else{
		                  $output.='<small class="text-muted"><span class="font-weight-bold font-13"> No value to comment your result.</span></small>';
		                }
		                $output.='</p>';
		        		$average=0;
					}
				}
				$output.=' </div> </div> </div></div><hr>';
			}
		}else{
			$output.='<p class="my-1">Subject data not found.Please contact your teacher.</p>';
		}
		return $output;
	
	}
	function fetchDashboardMarkResultApproved($gs_branches,$gs_gradesec,$gs_quarter,$grade,$max_year,$id)
	{
		$output='';
		$querySubject = $this->db->query("select * from subject where Academic_Year ='$max_year' and Grade='$grade' order by suborder ");
		if($querySubject->num_rows()>0){
			foreach($querySubject->result() as $calcMark){
				$gs_subject=$calcMark->Subj_name;
				$output.='<div class="card-statistic-3"><div class="card-body"> <div class="support-ticket media pb-1 mb-3">
				<div class="media-body ml-3"><p><b>'.$gs_subject.'</b></p>';
				$queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."' ");
				if ($queryCheck->num_rows()>0)
				{
					$querySingleSubject=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and stuid='$id' and approved='1' ");
					if($querySingleSubject->num_rows()>0)
					{
						$query_name = $this->db->query("select * from school");
						$row_name = $query_name->row_array();
						$school_name=$row_name['name'];
						$school_slogan=$row_name['slogan'];
						$evalname_query=$this->db->query("select us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' and us.academicyear='$max_year' group by ev.evname order by ev.eid ASC");
			        		$average=0;
		        		foreach ($evalname_query->result_array() as $mark_name)
		        		{
		        			$evName=$mark_name['evname'];
            			$grade=$mark_name['grade'];
            			$mname_gs=$mark_name['eid'];
            			$queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$gs_subject' and customasses='$evName' and customquarter='$gs_quarter' ");
		            	if($queryCheckPercentage->num_rows()>0){
		            		$rowPercent=$queryCheckPercentage->row();
		            		$percent=$rowPercent->custompercent;
		            	}else{
		            		$percent= $mark_name['percent'];
		            	}

		            		$query_value = $this->db->query("select markname,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
							if($query_value->num_rows()>0){
								$totalMark=0;$outofTot=0;
								foreach ($query_value->result_array() as $value) {
									$markNameStu=$value['markname'];
									$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' and approved='1' group by markname order by mid ASC");
									if($queryStuValue->num_rows()>0){
										foreach ($queryStuValue->result_array() as $kevalue) {
											$outofTot=$outofTot+$kevalue['outof'];
											$totalMark=$totalMark+$kevalue['value'];
										}
									}
									$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
					            		$sumOutOf=0;
						            foreach ($queryMvalue->result_array() as $mark_name) {
						            	$sumOutOf=$mark_name['outof'] + $sumOutOf;	
						            }
			            		}
			            		if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='')
		            			{
		                    		$conver= ($totalMark *$percent )/$sumOutOf;
		            				$average =$conver + $average;
		                		}
			            	}
		            	}
		            	$output.=' <div class="badge badge-pill badge-info mb-1 float-right">'.number_format((float)$average,2,'.','').' / 100 </div> ';

		            	$queryRange=$this->db->query("select letterVal from letterange as lr cross join users as us where lr.grade=us.grade and $average between minValue and maxiValue and lr.academicYear='$max_year' and us.id='$id' and us.gradesec='$gs_gradesec' and us.academicyear='$max_year' ");
		            	$output.='<div class="card-icon card-icon-large"><i class="fa fa-book-open"></i></div><p class="my-1">';
		                if($queryRange->num_rows()>0){
		                	foreach ($queryRange->result() as $letterValue) {
		                  		$letterVal=$letterValue->letterVal;
		                  		$output.= 'Grade :'.$letterVal.'';
		                	}
		                }else{
		                   $output.= 'No value to grade your result';
		                }
		                $reportCardComments=$this->db->query("select commentvalue from reportcardcomments as rc cross join users as us where rc.grade=us.grade and $average between mingradevalue and maxgradevalue and rc.academicYear='$max_year' and us.id='$id' and us.gradesec='$gs_gradesec' and us.academicyear='$max_year' ");

		                if($reportCardComments->num_rows()>0){
		                  foreach($reportCardComments->result() as $commentValue){
		                  	$output.='<small class="text-muted"><span class="font-weight-bold font-13"> "'.$commentValue->commentvalue.'"</span> </small>';
		                  }
		                  
		                }else{
		                  $output.='<small class="text-muted"><span class="font-weight-bold font-13"> No value to comment your result.</span></small>';
		                }
		                $output.='</p>';
		        		$average=0;
					}
				}
				$output.=' </div> </div> </div></div><hr>';
			}
		}else{
			$output.='<p class="my-1">Subject data not found.Please contact your teacher.</p>';
		}
		return $output;
	
	}
	function fetchDashboardMarkResultMountolive($branch,$gradesec,$quarterValue,$grade,$max_year,$stuid,$fName,$mName,$lName)
	{
		$output='';
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
		$querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
    $rowGyear = $querySlogan->row();
    $gYearName=$rowGyear->gyear;
		$querySubject = $this->db->query("select * from subject where Academic_Year ='$max_year' and Grade='$grade' order by suborder ");
		$queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade'  ");
		$output.='<h5 class="text-center">'.$school_name.'</h5>
		<h5>'.$max_year.'E.C ('.$gYearName.'G.C) Academic Year '.$quarterValue.' Student Progress Report</h5>
		<div class="row">
			<div class="col-lg-8 col-8">
				Student Name: '.$fName.' '.$mName.' '.$lName.'
			</div>
			<div class="col-lg-4 col-4">
				Grade: '.$gradesec.'
			</div>
			<div class="col-lg-12 col-12">';
			if($queryRangeValue->num_rows()>0){
	      $output .='Grading';
	      foreach ($queryRangeValue->result() as $rangeValue) {
	        $output .=' '.$rangeValue->minValue.' - '.$rangeValue->maxiValue.' = ';
	        $output .=' '.$rangeValue->letterVal.' &nbsp;&nbsp;';
	      } 
	    }
			$output.='</div> </div> ';
			if($querySubject->num_rows()>0){
		    $resultSem1=0;
		    $resultSem2=0;
		    $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$max_year."' ");
				if ($queryCheck->num_rows()>0)
				{   
          $output.= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; ">
          <div class="row">
          <div class="col-lg-12 col-md-12">';
          $output.='<table width="100%" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
          $output.='<tr><th>Subject</th>
          <th class="text-center">Exam Result</th>
          <th class="text-center">Grade</th></tr>';
          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' 
          and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $querySubjectList=$this->db->query("select * from subjectlist where academicyear='$max_year' and grade='$grade' and subname='$subject' ");
	          	if($querySubjectList->num_rows()>0){
	          		$totList=$querySubjectList->num_rows() + 1;
	          	}else{
	          		$totList='1';
	          	}
              $output.='<tr><td class="font-weight-bold font-13"><b>'.$fetchSubject->subject.'</b></td>';              
              $queryReportCardQ1=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='$quarterValue' and subject='$subject' order by subjorder ");
              if($queryReportCardQ1->num_rows()>0){
                foreach ($queryReportCardQ1->result() as $fetchResult1) {
                  $result1=$fetchResult1->total;
                  if($fetchResult1->total=='' || $fetchResult1->total<=0){
                    $output.='<td class="text-center" rowspan="'.$totList.'">-</td>';
                    $output.='<td class="text-center" rowspan="'.$totList.'">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result1=='100'){
                        $output .= '<td class="text-center font-weight-bold font-13" rowspan="'.$totList.'">'.number_format((float)$result1,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center font-weight-bold font-13" rowspan="'.$totList.'">'.number_format((float)$result1,2,'.','').'</td>';
                      }
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0 && $result1>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center' rowspan=".$totList.">".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center' rowspan=".$totList."> -</td>";
                      }
                    }
                    else{
                      $queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result1 between minValue and maxiValue and academicYear='$max_year'");
                      if($queryRange->num_rows()>0){
                        foreach ($queryRange->result() as $letterValue) {
                          $letterVal=$letterValue->letterVal;
                          $output.= "<td class='text-center' rowspan=".$totList.">".$letterVal."</td>";
                        }
                      }else{
                        $output.= "<td class='text-center' rowspan=".$totList."> -</td>";
                      }
                    }
                  }
                  foreach($querySubjectList->result() as $subList){
		              	$output.='<tr><td>'.$subList->listname.'</td></tr>';
		              }
                }
              }else{
                $output.='<td class="text-center">-</td>';
              }
              $output.='</tr>';
            }
          }
          /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
          $output.='<tr><td class="font-weight-bold font-13"><b>Total</b></td>';
            $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
            if($quartrTotal->num_rows()>0){
              foreach ($quartrTotal->result() as $totalValue) {
                $printValue=$totalValue->total;
                if($printValue >0){
                  $output .= '<td class="text-center font-weight-bold font-13" colspan="2"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          
          $output.='</tr>';

          /*Horizontal Average calculation starts*/
          $output.='<tr><td class="font-weight-bold font-13"><b>Average</b></td>';
          /*1st and snd quarter calculation starts*/
          $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
          $subALl=$countSubject->num_rows();
            if($subALl>0){
              $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
              if($quartrTotal->num_rows()>0){
                foreach ($quartrTotal->result() as $totalValue) {
                  $printValue=($totalValue->total)/$subALl;
                  if($printValue >0){
                    $output .= '<td class="text-center font-weight-bold font-13" colspan="2"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
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
          $queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='rankname' and grade='$grade' and allowed='1' ");
          if($queryRankAllowed->num_rows()>0){
            $output.='<tr><td class="font-weight-bold font-13"><b>Rank</b></td>';
            $quarter=$quarterValue;
            $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid'
            and quarter='$quarter' group by quarter ");
            $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank 
            from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' 
            and onreportcard='1' and rpbranch='$branch' group by grade ");
            if($query_total->num_rows()>0){
              foreach ($query_rank->result() as $qtrank)
              {
                $output.= '<td class="text-center font-weight-bold font-13" colspan="2"><B>'.$qtrank->stuRank.'</B></td>';
              }
            }else{
              $output.= '<td class="text-center" colspan="2">-</td>';
            }
          $output.='</tr>';
        }
        $output.="</table></div></div></div>";/*result table closed*/
        $queryHoomRoom=$this->db->query("select u.fname,u.mname from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
        if($queryHoomRoom->num_rows()>0){
          $rowHommeRoom=$queryHoomRoom->row_array();
          $tfName=$rowHommeRoom['fname'];
          $tmName=$rowHommeRoom['mname'];
        }else{
          $tfName='__________';
          $tmName='__________';
        }
        $output.='<div class="row">
        <div class="col-lg-8 col-8">
        Teachers Name: '.$tfName. ' ' .$tmName. '</div>
        <div class="col-lg-4 col-4">Signature__________ </div>
        </div>';
    	}
		}else{
			$output.='<p class="my-1">Report is not ready to view.Please contact your school Admin</p>';
		}
		return $output;
	}
	function fetchDashboardMarkResultENS($branch,$gradesec,$quarterValue,$grade,$max_year,$stuid,$fName,$mName,$lName)
	{
		$output='';
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
		$querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
    $rowGyear = $querySlogan->row();
    $gYearName=$rowGyear->gyear;
		$querySubject = $this->db->query("select * from subject where Academic_Year ='$max_year' and Grade='$grade' order by suborder ");
		$queryRangeValue=$this->db->query("select * from letterange where academicyear='$max_year' and grade='$grade'  ");

		$queryac = $this->db->query("select * from academicyear order by year_name DESC");
		$output.='<div class="row"><div class="col-lg-6 col-md-6 col-6">

		<select class="form-control" name="regular_academicyear" id="regular_academicyear">
      <option> Select Year </option>';
      foreach($queryac->result() as $fetch_terms) { 
        $output.='<option value="'.$fetch_terms->year_name.'">'.$fetch_terms->year_name.'</option>';
      }
    $output.='</select></div>
    <div class="col-lg-6 col-md-6 col-6">
    <select class="form-control" name="regular_filter_quarter" id="regular_filter_quarter">
      <option> Select Quarter </option>';
      
    $output.='</select></div></div>';
		$output.='<b class="text-center"><u>'.$school_name.'</u></b><br>
		<b><u>'.$max_year.'E.C ('.$gYearName.'G.C) Academic Year '.$quarterValue.' Student Progress Report</u></b>
		<div class="row">
			<div class="col-lg-8 col-8">
				<h6>Student Name: '.$fName.' '.$mName.' '.$lName.'</h6>
			</div>
			<div class="col-lg-4 col-4">
				<h6>Grade: '.$gradesec.'</h6>
			</div>
			<div class="col-lg-12 col-12">';
			if($queryRangeValue->num_rows()>0){
	      $output .='Grading';
	      foreach ($queryRangeValue->result() as $rangeValue) {
	        $output .=' '.$rangeValue->minValue.' - '.$rangeValue->maxiValue.' = ';
	        $output .=' '.$rangeValue->letterVal.' &nbsp;&nbsp;';
	      } 
	    }
			$output.='</div> </div> ';
			if($querySubject->num_rows()>0){
		    $resultSem1=0;
		    $resultSem2=0;
		    $queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesec.$max_year."' ");
				if ($queryCheck->num_rows()>0)
				{   
          $output.= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; ">
          <div class="row">
          <div class="col-lg-12 col-md-12">';
          $output.='<table width="100%" class="tabler table-borderedr table-md" cellspacing="9" cellpadding="9">';
          $output.='<tr><th>Subject</th>
          <th class="text-center">Quarterly Average(Out of 100%)</th>
          <th class="text-center">Grade</th></tr>';
          $querySubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' 
          and onreportcard='1' group by subject order by subjorder ASC");
          if($querySubject->num_rows()>0){
            foreach ($querySubject->result() as $fetchSubject) {
              $subject=$fetchSubject->subject;
              $letter=$fetchSubject->letter;
              $output.='<tr><td class="font-weight-bold font-13"><b>'.$fetchSubject->subject.'</b></td>';              
              $queryReportCardQ1=$this->db->query("select * from reportcard".$gradesec.$max_year." where rpbranch='$branch' and stuid='$stuid' and quarter='$quarterValue' and subject='$subject' order by subjorder ");
              if($queryReportCardQ1->num_rows()>0){
                foreach ($queryReportCardQ1->result() as $fetchResult1) {
                  $result1=$fetchResult1->total;
                  if($fetchResult1->total=='' || $fetchResult1->total<=0){
                    $output.='<td class="text-center">-</td>';
                    $output.='<td class="text-center">-</td>';/*1st Quarter Result*/
                  }else{
                    if($letter!='A'){
                      if($result1=='100'){
                        $output .= '<td class="text-center font-weight-bold font-13">'.number_format((float)$result1,0,'.','').'</td>';
                      }else{
                        $output .= '<td class="text-center font-weight-bold font-13">'.number_format((float)$result1,2,'.','').'</td>';
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
          }
          /*Quarter1 & Quarter2 Horizontal Total calculation starts*/
          $output.='<tr><td class="font-weight-bold font-13"><b>Total</b></td>';
            $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
            if($quartrTotal->num_rows()>0){
              foreach ($quartrTotal->result() as $totalValue) {
                $printValue=$totalValue->total;
                if($printValue >0){
                  $output .= '<td class="text-center font-weight-bold font-13" colspan="2"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
                }else{
                  $output.='<td class="text-center" colspan="2">-</td>';
                }
              }
            }else{
              $output.='<td class="text-center" colspan="2">-</td>';
            }
          
          $output.='</tr>';

          /*Horizontal Average calculation starts*/
          $output.='<tr><td class="font-weight-bold font-13"><b>Average</b></td>';
          /*1st and snd quarter calculation starts*/
          $countSubject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and academicyear='$max_year' and rpbranch='$branch' and onreportcard='1' and letter='#' group by subject order by subjorder ASC");
          $subALl=$countSubject->num_rows();
            if($subALl>0){
              $quartrTotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter='$quarterValue' and onreportcard='1' and letter='#' ");
              if($quartrTotal->num_rows()>0){
                foreach ($quartrTotal->result() as $totalValue) {
                  $printValue=($totalValue->total)/$subALl;
                  if($printValue >0){
                    $output .= '<td class="text-center font-weight-bold font-13" colspan="2"><b>'.number_format((float)$printValue,2,'.','').'</b></td>';
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
          /*$queryRankAllowed=$this->db->query("select * from rank_allowed_grades where academicyear='$max_year' and rowname='rankname' and grade='$grade' and allowed='1' ");
          if($queryRankAllowed->num_rows()>0){
            $output.='<tr><td class="font-weight-bold font-13"><b>Rank</b></td>';
            $quarter=$quarterValue;
            $query_total=$this->db->query("select sum(total) as total from reportcard".$gradesec.$max_year." where academicyear='$max_year' and stuid ='$stuid'
            and quarter='$quarter' group by quarter ");
            $query_rank=$this->db->query("select sum(total),FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(total) as rank 
            from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter='#' and quarter='$quarter' and onreportcard='1' and rpbranch='$branch' group by stuid) sm)) as stuRank from reportcard".$gradesec.$max_year." where stuid='$stuid' and quarter= '$quarter' and academicyear='$max_year' and letter='#' and grade='$gradesec' 
            and onreportcard='1' and rpbranch='$branch' group by grade ");
            if($query_total->num_rows()>0){
              foreach ($query_rank->result() as $qtrank)
              {
                $output.= '<td class="text-center font-weight-bold font-13" colspan="2"><B>'.$qtrank->stuRank.'</B></td>';
              }
            }else{
              $output.= '<td class="text-center" colspan="2">-</td>';
            }
          $output.='</tr>';
        }*/
        $output.="</table></div></div></div>";/*result table closed*/
        $queryHoomRoom=$this->db->query("select u.fname,u.mname from users as u cross join hoomroomplacement as hm where hm.roomgrade='$gradesec' and hm.academicyear='$max_year' and hm.branch='$branch' and u.branch='$branch' and u.username=hm.teacher and u.status='Active' and u.isapproved='1' ");
        if($queryHoomRoom->num_rows()>0){
          $rowHommeRoom=$queryHoomRoom->row_array();
          $tfName=$rowHommeRoom['fname'];
          $tmName=$rowHommeRoom['mname'];
        }else{
          $tfName='__________';
          $tmName='__________';
        }
        $output.='<div class="row">
        <div class="col-lg-8 col-8">
        Teachers Name: '.$tfName. ' ' .$tmName. '</div>
        <div class="col-lg-4 col-4">Signature__________ </div>
        </div>';
    	}
		}else{
			$output.='<p class="my-1">Report is not ready to view.Please contact your school Admin</p>';
		}
		return $output;
	}
	function filter_quarterAddMark($grade,$max_year){
		$output='';
		$query=$this->db->query("select qu.term from quarter as qu where qu.termgrade='$grade' and qu.Academic_year='$max_year' group by qu.term order by qu.term DESC ");
		if($query->num_rows()>0){ 
			$output='<select class="form-control" required="required" name="quarter4eva" id="quarter4eva"><option></option>';
			foreach ($query->result() as $evavalue) {
				$output.='<option value='.$evavalue->term.'>'.$evavalue->term.'</option>';	
			}
			$output.='</select>';
		}else{
			$output.='<option>No Term/Quarter</option>';
		}
		return $output;
	}
	function fetchLeavingRequest($max_year,$user){
		$output='';
		$this->db->where('academicyear',$max_year);
		$this->db->where('stuid',$user);
		$this->db->where('requestype','Leaving Certificate Request');
		$query=$this->db->get('studentrequest');
		$output.='<div class="row"><div class="col-md-6 col-12" id="view_ThisSubjectResult_gs">';
		if($query->num_rows()>0){
			$rowDate=$query->row();
			$rowName=$rowDate->requestdate;
			$rowStatus=$rowDate->requeststatus;
			$rowResponse=$rowDate->requestresponse;
			$output.='<div class="card-body StudentViewTextInfo">
	            <div class="support-ticket">
	                <div class="badge badge-pill badge-light float-right" >';
	                if($rowStatus==0){
	                	$output.='<i class="fas fa-check"></i>';
	                }else{
	                	$output.='<i class="fas fa-check-double"></i> '.$rowResponse.' ';
	                }
	                $output.='</div>
	                <span class="font-weight-bold font-24">Leaving request sent succesfully</span><br>
	                <small class="text-muted">'.$rowName.'</small>
	              </div>
	          </div>
	          <p class="my-1" id="leavingRequestStatus"></p>';
		}else{
		    $output.='<a href="javascript:void(0)" class="view_ThisSubjectResult_gs" id="sendmyRequest" value="Leaving Certificate Request">
	            <div class="support-ticket media StudentViewTextInfo">
	              <div class="media-body">
	                <div class="badge badge-pill badge-primary float-right" ><i class="fas fa-chevron-right"></i>
	                </div>
	                <span class="font-weight-bold font-24">Send leaving request</span><br>
	                <small class="text-muted">Click here to send leaving request</small>
	              </div>
	            </div>
	          <p class="my-1" id="leavingRequestStatus"></p>
	        </a>';
		}
		$output.='</div>';
		$this->db->where('academicyear',$max_year);
		$this->db->where('stuid',$user);
		$this->db->where('requestype','Transcript Certificate Request');
		$queryTranscript=$this->db->get('studentrequest');
		$output.='<div class="col-md-6 col-12" id="view_ThisSubjectResult_gs">';
		if($queryTranscript->num_rows()>0){
			$rowDate=$queryTranscript->row();
			$rowName=$rowDate->requestdate;
			$rowStatus=$rowDate->requeststatus;
			$rowResponse=$rowDate->requestresponse;
			$output.='<div class="card-body StudentViewTextInfo">
	            <div class="support-ticket">
	                <div class="badge badge-pill badge-light float-right" >';
	                if($rowStatus==0){
	                	$output.='<i class="fas fa-check"></i>';
	                }else{
	                	$output.='<i class="fas fa-check-double"></i> '.$rowResponse.' ';
	                }
	                $output.='</div>
	                <span class="font-weight-bold font-24">Transcript request sent succesfully</span><br>
	                <small class="text-muted">'.$rowName.'</small>
	              </div>
	          </div>';
		}else{
	      	$output.='<a href="javascript:void(0)" class="view_ThisSubjectResult_gs " id="sendmyRequest" value="Transcript Certificate Request">
	            <div class="support-ticket media StudentViewTextInfo">
	              <div class="media-body">
	                <div class="badge badge-pill badge-primary float-right" ><i class="fas fa-chevron-right"></i>
	                </div>
	                <span class="font-weight-bold font-24">Send Transcript Request</span><br>
	                <small class="text-muted">Click here to send transcript request</small>
	              </div>
	            </div>
	          <p class="my-1" id="requestStatus"></p>
		    </a>';
		}
		$output.='</div></div>';
		return $output;
	}
	function fetch_new_student_request($max_year){
		$output='';
		$this->db->where('studentrequest.academicyear',$max_year);
		$this->db->not_like('studentrequest.requestresponse','approved');
    $this->db->order_by('studentrequest.id','DESC');
    $this->db->group_by('studentrequest.id');
    $this->db->select('users.profile,users.fname, users.mname,studentrequest.id, studentrequest.requestype, studentrequest.requestdate,studentrequest.requeststatus,studentrequest.requestresponse ,studentrequest.from_date,studentrequest.to_date ,studentrequest.return_date');
    $this->db->from('studentrequest');
    $this->db->join('users', 
            'users.username = studentrequest.stuid');
    $query = $this->db->get();
		if($query->num_rows()>0){
			$todayDate = date('d/m/Y');
			$output.='<div class="row"> 
				<div class="col-12 col-sm-12 col-lg-12 col-md-12">
				<button class="btn btn-danger pull-right" id="deleteAllStudentRequest">Delete All </button>
				</div>			
			</div> ';
			foreach($query->result() as $rowRequest){
				$returnDate=$rowRequest->return_date;
				$send_date = date("Y-m-d", strtotime($returnDate));
				$output.='
			        <div class="message-codntainer-gs">
			        <div class="message-gs-full have-incident-report">';
				$output.='<div class="support-ticket media">';
          if($rowRequest->profile!=''){
            $output.='<img alt="image" src="'.base_url().'/profile/'.$rowRequest->profile.'" class="user-img mr-2" style="height:40px;width:auto">';
          }else{
            $output.='<img alt="image" src="'.base_url().'/profile/defaultProfile.png" class="user-img mr-2" style="height:40px;width:auto">';  
          }
          $output.='
        	<div class="media-body">';
        	if($todayDate > $returnDate){
        		$output.='<div class="badge badge-pill badge-light pull-right">Expired '.$todayDate.' '.$returnDate.'</div>';
        	}else{
          	$output.='<div class="badge badge-pill badge-light pull-right"> Action
          		<div class="float-right dropdown-menu-right pullLeft">
            		<a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
            		<div class="dropdown-menu">
                	<div class="dropdown-title">Action</div>
                	<a href="#" id="respond_student_request" class="dropdown-item has-icon text-info" value="Pending" name="'.$rowRequest->id.'"> <i class="fas fas fa-clock"></i> Pending</a>
                	<div class="dropdown-divider"></div>
                	<a href="#" id="respond_student_request" class="dropdown-item has-icon text-success" value="approved" name="'.$rowRequest->id.'"><i class="fas fa-check-circle"></i> Approved</a>
                	<div class="dropdown-divider"></div>
                	<a href="#" id="respond_student_request" class="dropdown-item has-icon text-danger" value="Postphoned" name="'.$rowRequest->id.'"><i class="fas fa-times-circle"></i> Postphoned</a>
              	</div>
          		</div>
          	</div>';
         	}
          $output.='<span class="font-weight-bold">'.$rowRequest->fname.' '.$rowRequest->mname.'</span> <br>'.$rowRequest->requestype.' (<u>'.$rowRequest->from_date.'</u> - <u>'.$rowRequest->to_date.' </u>) <br>Returned Date <u>'.$rowRequest->return_date.'</u> <small class="text-muted">Created on '.$rowRequest->requestdate.'</small><span class="badge badge-pill badge-info"> '.$rowRequest->requestresponse.'</span>
          	
        	</div>
      	</div> </div></div>';
			}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
	      <div class="alert-body">
	          <button class="close" data-dismiss="alert">
	            <span>&times;</span>
	          </button><i class="fas fa-exclamation-triangle "></i> No new request found.
	      </div>
	    </div>';
		}
		return $output;
	}
	function fetch_approved_request($max_year,$postData=null){	
   	$response = array();

   	## Read value
   	$draw = $postData['draw'];
   	$start = $postData['start'];
   	$rowperpage = $postData['length']; // Rows display per page
   	$columnIndex = $postData['order'][0]['column']; // Column index
   	$columnName = $postData['columns'][$columnIndex]['data']; // Column name
   	$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
   	$searchValue = $postData['search']['value']; // Search value

   	## Search 
   	$searchQuery = "";
   	if($searchValue != ''){
   		$this->db->where('academicyear',$max_year);
   		$this->db->where('requestresponse','approved');
      $searchQuery = " (requestdate like '%".$searchValue."%' or requestype like '%".$searchValue."%' or return_date like '%".$searchValue."%' or requestresponse like '%".$searchValue."%' ) ";
   	}

   	## Total number of records without filtering
   	$this->db->where('academicyear',$max_year);
   	$this->db->where('requestresponse','approved');
   	$this->db->select('count(*) as allcount');
   	$records = $this->db->get('studentrequest')->result();
   	$totalRecords = $records[0]->allcount;

   	## Total number of record with filtering
   	$this->db->select('count(*) as allcount');
   	if($searchQuery != '')
      $this->db->where($searchQuery);
    $this->db->where('requestresponse','approved');
  	$this->db->where('academicyear',$max_year);
   	$records = $this->db->get('studentrequest')->result();
   	$totalRecordwithFilter = $records[0]->allcount;

   	## Fetch records
   	$this->db->select('*');
   	if($searchQuery != '')
    $this->db->where($searchQuery);
 		$this->db->order_by($columnName, $columnSortOrder);
 		$this->db->limit($rowperpage, $start);

 		$this->db->where('studentrequest.academicyear',$max_year);
		$this->db->where('studentrequest.requestresponse','approved');
  	$this->db->order_by('studentrequest.id','DESC');
  	$this->db->select('users.profile,users.fname, users.mname,studentrequest.id, studentrequest.requestype, studentrequest.requestdate,studentrequest.requeststatus,studentrequest.requestresponse ,studentrequest.from_date,studentrequest.to_date ,studentrequest.return_date,studentrequest.emergency_mobile');
  	$this->db->from('studentrequest');
  	$this->db->join('users', 
          'users.username = studentrequest.stuid');
  	$records = $this->db->get('')->result();
    $data = array();
    $no=1;
 		foreach($records as $staff){
        $action=''.$staff->requestresponse.' <div class="bullet"></div>
        <a href="javascript:void(0)" class="text-center printThisApprovedRequest" id="printThisApprovedRequest" value="'.$staff->id.'" data-toggle="modal" data-target="#print_approved_staff_request" type="submit">View Report</a>';
    	$data[] = array( 
       	"fname"=>$staff->fname.' '. $staff->mname .' ' .$staff->lname,
       	"requestype"=>$staff->requestype,
       	"requestdate"=>$staff->requestdate,
       	"requestresponse"=>$action,
      ); 
   	}
    ## Response
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );
    return $response; 
 	}
	/*function fetch_approved_staffrequested_form($max_year,$request_id){
		$output='';
		$this->db->where('studentrequest.academicyear',$max_year);
		$this->db->where('studentrequest.id',$request_id);
		$this->db->where('studentrequest.requestresponse','approved');
    $this->db->order_by('studentrequest.id','DESC');
    $this->db->select('users.profile,users.fname, users.mname,studentrequest.id, studentrequest.requestype, studentrequest.requestdate,studentrequest.requeststatus,studentrequest.requestresponse ,studentrequest.from_date,studentrequest.to_date ,studentrequest.return_date,studentrequest.emergency_mobile');
    $this->db->from('studentrequest');
    $this->db->join('users', 
            'users.username = studentrequest.stuid');
    $query = $this->db->get();
		if($query->num_rows()>0){
			$query_name = $this->db->query("select * from school");
      $row_name = $query_name->row();
      $school_name=$row_name->name;
      $slogan=$row_name->slogan;
      $logo=$row_name->logo;
      $querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
      $rowGyear = $querySlogan->row();
      $gYearName=$rowGyear->gyear;
      $dateYear=date('Y');
			$todayDate=date('d/m/Y');
			$output.='<div class="badge badge-pill mb-1 float-right"><button class="btn btn-default" name="gethisrequestreport" onclick="codespeedyRequestReport()"> 
            		<i class="fas fa-print"></i> </button></div>';
			foreach($query->result() as $rowRequest){
				$changeDate1 = DateTime::createFromFormat('d/m/Y',$rowRequest->from_date);
        $changeDate2 = DateTime::createFromFormat('d/m/Y',$rowRequest->to_date);
        $startDate1= $changeDate1->format('Y-m-d');
        $endDate1= $changeDate2->format('Y-m-d');
				$diff = strtotime($endDate1) - strtotime($startDate1); 
    		$daysleft= round($diff / (60 * 60 * 24)) + 1;
				$output.='<div id="codespeedyRequestReport">';
             $output.= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; "> 
	          <div class="support-ticket media pb-1 mb-3 card-header">
	          <img src="'.base_url().'/logo/'.$logo.'" style="width:auto;height: 110px;" class="user-img mr-2" alt="">
	          <div class="media-body ml-3">
	            <span class="font-weight-bold"><h2 id="ENScool"><B id="ENS">'.$school_name.' </B></h2></span>
	            <p class="my-1"> <h4 id="ENScool"><B id="ENS"><u> '.$max_year.'ዓ.ም('.$gYearName.' G.C) የሠራተኞች የፍቃድ መጠየቂያና መዉሰጃ ፎርም </u></B></h4></p>
	          </div>
	        </div> 
	        <p>1. የሰራተኛዉ ስም/Employee Name:- <u>'.$rowRequest->fname.' '.$rowRequest->mname.'</u> &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;ቀን/Date <u>'.date('M-d-Y').' </u>  <br></p>
	        <p>2. የስራ ክፍሉ /Job Department :- ________________ .  &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;የስራ መደብ/ Job Position:- ________________ <br></p>
	        <p>3. የፍቃድ ዓይነት /Request Type:- &nbsp;&nbsp; <u>'.$rowRequest->requestype.' </u>  <br></p>
	        <p>4. የፍቃድ ጠያቂዉ ፊርማ/ Applicant Signature:- ______________  &nbsp;&nbsp;&nbsp; &nbsp; የቅርብ ሃላፊ ስምና ፊርማ/Name and signature of immediate supervisor:- ______________ <br></p>
	        <p>5. የቅርብ ሃላፊ አስተያየት/ Immediate supervisor comment:- ____________________________________ ___________________________________________ ________________________________________________ 
	        <br></p>
	        <p>6. ሰራተኛዉ ያለዉ/ያላት የአመት ፍቃድ /The employee`s annual leave :- ከ/From/:- _______ እስከ/To/:- _______ &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;ድምር/Total/:- _____________.<br></p>
	        <p>7. የፍቃድ ጊዜ/Period:- ከ/From/ <u> '.$rowRequest->from_date.'</u> እስከ/To/ <u>'.$rowRequest->to_date.' </u> &nbsp;&nbsp;&nbsp; &nbsp; የቀናት ብዛት/total days/:- <u>'.$daysleft.' </u> &nbsp;&nbsp;&nbsp; &nbsp; ቀሪ የአመት እረፍት ብዛት/Remaining annual leave :- ________ <br></p>
	        <p>8. ወደ ስራ የሚመለሱበት ቀን/The day you return to work:- <u>'.$rowRequest->return_date.'</u><br></p>
	        <p>9. ፍቃድ ላይ እያሉ ለስራ ቢፈለጉ የሚገኙበት ስልክ ቁጥር /Phone number where we can be contacted if we want you for work while you are on leave:- <u> '.$rowRequest->emergency_mobile.'</u> <br></p>
	        <p>10. ፍቃዱን ያጸደቀዉ አስተዳደር ክፍል ሃላፊ ፊርማ/Signature of the head of the administrative department who approved the request :- ______________________________. <br></p>
	        <div class="text-muted form-text">ማሳሰቢያ ፡-ከክፍያ ነጻ ፍቃድ የሚጠየቅዉ በማመልከቻ ሆኖ አመልካች የአመት ፍቃድ የሌለዉ ሲሆንና ከአቅም በላይ የሆነ ችግር ሲያጋጥም ነዉ፡፡ <br>Notice:- Payment free license is requested by application when the applicant does not have an annual leaving license and faces majeure problem.
          </div>
        </div> ';
			}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
	      <div class="alert-body">
	          <button class="close" data-dismiss="alert">
	            <span>&times;</span>
	          </button><i class="fas fa-exclamation-triangle "></i> No new request found.
	      </div>
	    </div>';
		}
		return $output;
	}*/
	function fetch_approved_staffrequested_form($max_year,$request_id){
		$output='';
		$this->db->where('studentrequest.academicyear',$max_year);
		$this->db->where('studentrequest.id',$request_id);
		$this->db->where('studentrequest.requestresponse','approved');
    $this->db->order_by('studentrequest.id','DESC');
    $this->db->select('users.profile,users.fname, users.mname,studentrequest.id, studentrequest.requestype, studentrequest.requestdate,studentrequest.requeststatus,studentrequest.requestresponse ,studentrequest.from_date,studentrequest.to_date ,studentrequest.return_date,studentrequest.emergency_mobile');
    $this->db->from('studentrequest');
    $this->db->join('users', 
            'users.username = studentrequest.stuid');
    $query = $this->db->get();
		if($query->num_rows()>0){
			$query_name = $this->db->query("select * from school");
      $row_name = $query_name->row();
      $school_name=$row_name->name;
      $slogan=$row_name->slogan;
      $logo=$row_name->logo;
      $querySlogan=$this->db->query("select gyear from academicyear where year_name='$max_year' ");
      $rowGyear = $querySlogan->row();
      $gYearName=$rowGyear->gyear;
      $dateYear=date('Y');
			$todayDate=date('d/m/Y');
			$output.='<div class="badge badge-pill mb-1 float-right"><button class="btn btn-default" name="gethisrequestreport" onclick="codespeedyRequestReport()"> 
            		<i class="fas fa-print"></i> </button></div>';
			foreach($query->result() as $rowRequest){
				$changeDate1 = DateTime::createFromFormat('d/m/Y',$rowRequest->from_date);
        $changeDate2 = DateTime::createFromFormat('d/m/Y',$rowRequest->to_date);
        $startDate1= $changeDate1->format('Y-m-d');
        $endDate1= $changeDate2->format('Y-m-d');
				$diff = strtotime($endDate1) - strtotime($startDate1); 
    		$daysleft= round($diff / (60 * 60 * 24)) + 1;
				$output.='<div id="codespeedyRequestReport">';
             $output.= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; "> 
	          <div class="support-ticket media pb-1 mb-3 card-header">
	          <img src="'.base_url().'/logo/'.$logo.'" style="width:auto;height: 80px;" class="user-img mr-2" alt="">
	          <div class="media-body ml-3">
	            <span class="font-weight-bold"><h2 id="ENScool"><B id="ENS">'.$school_name.' </B></h2></span>
	            <p class="my-1"> <h4 id="ENScool"><B id="ENS"><u> Employee Leave Request Form </u></B></h4></p>
	          </div>
	        </div> 
	        <p class="font-weight-bold font-16"> Employee Name:- <u>'.$rowRequest->fname.' '.$rowRequest->mname.'</u> &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Date:- <u>'.date('M-d-Y').' </u>  <br></p>
	        <p class="font-weight-bold font-16"> Department :- ___________________________________________ .  &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Supervisor’s Name:- ___________________________________________ <br></p>
	        <p class="font-weight-bold font-18 text-center" style="background-color:#0c5e8c;width:100%;color:#fff">
	        REASON FOR LEAVE</p>';
	        $queryCk=$this->db->query('select * from staff_leaving_reason group by reason_name order by id ASC');
	        if($queryCk->num_rows()>0){
	        	$output.='<div class="row">';
	        	foreach($queryCk->result()  as $row){
	        		$reasonType=$row->reason_name;
	        		if($reasonType==$rowRequest->requestype){
	        			$output.='<div class="col-lg-6 col-md-6 col-6 form-group"> 
			               <i class="fas fa-check"></i> '.$reasonType.' 
									</div>';
	        		}else{
	        			$output.='<div class="col-lg-6 col-md-6 col-6 form-group"> 
			               '.$reasonType.' 
									</div>';
	        		}
	        	}
	        	$output.='</div>';
	        }
	        $output.='<p class="font-weight-bold font-18 text-center" style="background-color:#0c5e8c;width:100%;color:#fff"> 
			        LEAVE REQUESTED</p>
	        <p class="font-weight-bold font-16"> Period:- Day(s):&nbsp;&nbsp;&nbsp; &nbsp;  From: &nbsp;&nbsp; <u> '.$rowRequest->from_date.'</u> &nbsp;&nbsp;&nbsp; &nbsp; To: &nbsp;&nbsp;<u>'.$rowRequest->to_date.' </u> &nbsp;&nbsp;&nbsp; &nbsp; No of days:- <u>'.$daysleft.' </u> &nbsp;&nbsp;&nbsp; &nbsp; </p>
	        <p class="font-weight-bold font-16">Other ____________________________________ ____________________________________ ____________________________________ ____________________________________ ________________________________________________________________________</p> 
	        <p class="font-weight-bold font-16">Employee Signature:- ______________  &nbsp;&nbsp;&nbsp; &nbsp; Date :- ______________ <br></p>
	        <p class="font-weight-bold font-18 text-center " style="background-color:#0c5e8c;width:100%;color:#fff"> 
			        SUPERVISOR USE ONLY </p>
	        <p class="font-weight-bold font-16">Immediate supervisor comment:- ________________________________________________________________________ ___________________________________________ ____________________________________ ________________________________________________ 
	        </p>
	        <p class="font-weight-bold font-16">Approved By:- ___________________________________________  Supervisor signature  ____________________________________ Date : ________________________
	        </p>
        </div> ';
			}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
	      <div class="alert-body">
	          <button class="close" data-dismiss="alert">
	            <span>&times;</span>
	          </button><i class="fas fa-exclamation-triangle "></i> No new request found.
	      </div>
	    </div>';
		}
		return $output;
	}
	function fetchSummerMark($stuid,$grade,$username1,$max_year,$gradesec,$branch,$gyear){
		$output='';
		$queryCheck=$this->db->query(" Select * from summerreportcard where grade='$gradesec' and academicyear='$max_year' group by subject order by subject ASC ");
		if($queryCheck->num_rows()>0){
		$queryac = $this->db->query("select * from summer_academicyear");
		$output.='<select class="form-control" name="summer_academicyear" id="summer_academicyear">
      <option> Select Year </option>';
      foreach($queryac->result() as $fetch_terms) { 
        $output.='<option value="'.$fetch_terms->year_name.'">'.$fetch_terms->year_name.'</option>';
      }
    $output.='</select><div class="dropdown-divider"></div>
    <button class="btn btn-success btn-block" type="button" id="download-button" onclick="generateHTML2PDF()">Dowload PDF</button>';
	   $query_student=$this->db->query(" select * from summerstudent where id='$stuid'");
	    $queryac = $this->db->query("select max(year_name) as ay from academicyear");
        $rowac = $queryac->row();
        $yearname=$rowac->ay;

        $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $address=$row_name->address;
        $phone=$row_name->phone;
        $website=$row_name->website;
        $email=$row_name->email;
        $schooLogo=$row_name->logo;
        $averageSummer=0;
	    foreach ($query_student->result() as $row_student)
	    {
	    	$username1=$row_student->username;
	      	$stuid=$row_student->id;
	      	$grade=$row_student->grade;
	      	$grade_sec=$row_student->gradesec;
            $output.='<div id="printArea" style="width:100%;height:92%;page-break-inside:avoid;">';
		        $output.='<div>
		        <div class ="row">
		        	<div class="col-lg-12 col-12">
		        	<div class="support-ticket">
		            <div class="media">
		              <img src="'.base_url().'/logo/'.$schooLogo.'" style="width:100px;height: 100px;" class="user-img mr-2" alt="">
		              <div class="media-body">
		                <h4 class="font-weight-bold"><b>'.strtoupper($school_name).'</b></h4>
		               	<small> Phone:- '.$phone.' Website:- '.$website.'</small>';
		              	$output.='
		             	</div>
		            </div>
		            </div>
		        	</div>
	          	<div class="col-lg-12 col-12 text-center">
	            	<h6><B>SUMMER PROGRAM '.$gyear.' STUDENT REPORT CARD</B></h6>
	          	</div>
		    	</div>';
	      	$output.= '<div class="StudentViewTextInfo">
		      	<div class="row">
			      	<div class="col-lg-12 col-12">
			      	 <h5><B>Student Name: '.strtoupper($row_student->fname).' '.strtoupper($row_student->mname).' '.strtoupper($row_student->lname).'</B></h5>
			      	</div>
			      	<div class="col-lg-6 col-6">
			      		<h6><B> Grade: '.strtoupper($row_student->gradesec).' </B></h6>
			      	</div>
			      	<div class="col-lg-6 col-6">
			      		<h6><B>Academic Year: '.$max_year.'</B></h6>
			      	</div>
		      	</div>
	      	</div>
	      	</div>
	      	<div class="dropdown-divider"></div>
	      	<div class="row">
	      	<div class="col-lg-12">
	        <div class="table-responsive" id="ENS">
	        <table width="100%"  class="tabler table-borderedr" cellspacing="5" cellpadding="5">';
	      	$output.='<tr><th>Subject</th>';
	      	$output .='<th class="text-center" colspan="2">Summer Class</th>';
	      	$output.='</tr>';
	        $query_result=$this->db->query(" Select * from summerreportcard where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and onreportcard='1' group by subject order by subject ASC ");
	        foreach ($query_result->result() as $qvalue_result) 
	        {
	          	$subject=$qvalue_result->subject;
	            $output .='<tr><td><h6>'.$qvalue_result->subject.'</h6></td>';
	          	
              	$query_qua_result=$this->db->query(" Select * from summerreportcard where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and subject='$subject' and onreportcard='1' group by subject order by subject ASC ");
              	if($query_qua_result->num_rows()>0)
              	{
            		foreach ($query_qua_result->result() as $quvalue)
            		{
	                  	$letter=$quvalue->letter;
	                  	$result=$quvalue->total;
	                  	/*if($letter!='A') {*/
	                    	$output .='<td class="text-center"><h6>'.$result.'</h6></td>';
	                  	/*} else{*/
                      		$queryRange=$this->db->query("select letterVal from letterange where grade='$grade' and $result between minValue and maxiValue and academicYear='$max_year'");
                      		if($queryRange->num_rows()>0){
		                        foreach ($queryRange->result() as $letterValue) {
		                          	$letterVal=$letterValue->letterVal;
		                          	$output.= "<td class='text-center'><h6>".$letterVal."</h6></td>";
		                        }
                      		}else{
                        		$output.= "<td class='text-center'> -</td>";
                      		}
                  		/*}*/
                	}
              	}else{
                	$output.= "<td class='text-center'> -</td>";
              	}
	        	$output .='</tr>'; 
	        }	        
            $output .='<tr><td><b>Total</b></td>';
          	$query_qua_total=$this->db->query(" Select sum(total) as quarter_total from summerreportcard where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and onreportcard='1' group by quarter order by subject ASC ");
          	if($query_qua_total->num_rows()>0) {
            	foreach ($query_qua_total->result() as $qtvalue){
              		$output .= '<td class="text-center" colspan="2"><h6>'.number_format((float)$qtvalue->quarter_total,2,'.','').'</h6></td>';
            	}
          	} else{
            	$output .='<td class="text-center" colspan="2">-</td>';
          	}		
            $output .='</tr>';
            $output .='<tr><td><b>Average</b></td>';
        	$query_qua_total=$this->db->query(" Select sum(total) as quarter_total from summerreportcard where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and onreportcard='1' group by quarter order by subject ASC ");
          	/*count subject starts*/
          	$count_subject=$this->db->query("select * from summerreportcard where grade='$gradesec' and letter!='A' and onreportcard='1' and academicyear='$max_year' group by subject ");
      		$total_subject=$count_subject->num_rows();
      		if($query_qua_total->num_rows()>0) {
            	foreach ($query_qua_total->result() as $qtvalue) {
            		$averageSummer=number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','');
              		$output .= '<td class="text-center" colspan="2"><h6>'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</h6></td>';
            	}
          	}else{
            	$output .='<td class="text-center" colspan="2">-</td>';
          	}
          	
            $output .='</tr>';
	        $output .='<tr><td><b>No. of Absence</b></td>';
	        $queryTotalAbsent=$this->db->query("select count(stuid) as att from summerattendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and absentype='Absent' ");
		      if($queryTotalAbsent->num_rows()>0){
		        foreach ($queryTotalAbsent->result() as $absent){
		          if($absent->att>0)
		          {
		            $output .= '<td class="text-center" colspan="2"><h6>'.$absent->att.'</h6></td>';
		          }
		          else{
		            $output .= '<td class="text-center" colspan="2">-</td>';
		          }
		        }
		      }else{
		        $output.='<td class="text-center" colspan="2">-</td>';
		      }
	        $output .='</tr>';
	      	$output .='</table></div></div>';

	      	$output.= "<div class='col-lg-12 text-center'>
	      	<h6><u>GRADING SYSTEM</u></h6>";
	      	$queryKey=$this->db->query("select * from letterange where grade = '$grade' and academicYear='$max_year' ");
	      	$output.="<div class='row'>";
	        foreach($queryKey->result() as $keyVal){
	            $output.='<div class="col-lg-6 col-6 StudentViewTextInfo">';
	            $output.='<h6>'.$keyVal->minValue.'-'.$keyVal->maxiValue.'='.$keyVal->letterVal.'</h6>';
	            $output.='</div>';
	        }
	      	$output.="</div></div>";
	      	$output.= '<div class="col-lg-12 col-12"><div class="dropdown-divider"></div>';
	      	$output.="<h6><u>GENERAL COMMENTS AND RECOMMENDATIONS</u></h6>";
	      	/*$output.="<h5 id='ENS' class='text-center'>የክፍል ሃላፊ መምህር አስተያየት/Homeroom Teacher's Remark</h5>";*/
	      	$reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $averageSummer between mingradevalue and maxgradevalue and academicYear='$max_year'");
            if($averageSummer >0 && $reportCardComments->num_rows()>0){
              foreach($reportCardComments->result() as $commentValue){
                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
              }
              
            }else{
              $output.='____________________________________________________________________ _________________________ ____________________________________________________________________ _________________________ <br>';
            }
	      	$queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u where usertype='superAdmin' and u.branch='$branch' and isapproved='1' and finalapproval='1' and status='Active' and mysign!='' ");
             if($queryDirector->num_rows()>0){
              $rowSignD=$queryDirector->row();
              $signName=$rowSignD->fname;
              $signmame=$rowSignD->mname;
              $signlame=$rowSignD->lname;
              $signlame=$rowSignD->lname;
              $signSigns=$rowSignD->mysign;
              $output.="<p class='text-center'>School Head Name <b><u>".$signName." ".$signmame."</u></b> Signature  <img alt='Sig.' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'></p>";
            }else{
              $output.="<p class='text-center'>School Head Name______________________ Signature____________</p>";
            }
	      $output.='</div></div>';
	    }
	  }else
	  {
	   $output .='<div class="alert alert-light alert-dismissible show fade">
                <div class="alert-body">
                   
                <i class="fas fa-exclamation-triangle"> </i> Summer result not ready Please wait.
            </div></div>';
	  }
	   return $output;
	}
	function reportcardByQuarter($stuid,$grade,$username,$max_year,$gradesec,$branch,$gyear){
		$query_result=$this->db->query(" Select * from summersubject where Grade='$grade' and onreportcard='1' and Academic_Year='$max_year' group by Subj_name order by Subj_name ASC ");
		$output ='';
		if($query_result->num_rows()>0){
	   	$query_student=$this->db->query(" select * from summerstudent where academicyear='$max_year' and id='$stuid' ");
	   	if($query_student->num_rows()>0){ 
	    $row_student = $query_student->row();


	    $query_name = $this->db->query("select * from school");
	    $row_name = $query_name->row();
	    $school_name=$row_name->name;
	    $address=$row_name->address;
	    $phone=$row_name->phone;
	    $website=$row_name->website;
	    $email=$row_name->email;
	    $schooLogo=$row_name->logo;
	    $averageSummer=0;  
	    $output.='<div style="width:100%;height:92%;page-break-inside:avoid;">';
	    $output.='<div style="background:url(../img/bg.jpg);background-size:cover;background-position:center; background-repeat:no-repeat;">
	      <div class ="row" id="ENS">
	      <div class="col-lg-12 col-12">
	          <div class="media pb-1 mb-3 card-header">
	            <img src="'.base_url().'/logo/'.$schooLogo.'" style="width:130px;height: 130px;" class="user-img" alt="">
	            <div class="media-body">
	              <h1><u><b>'.strtoupper($school_name).'</b></u></h1>
	              <i class="fas fa-fax"></i> '.$phone.'
	              <small><i class="fas fa-globe"></i> '.$website.'</small>';
	            $output.='</div>
	          </div>
	      </div>
	    	<div class="col-lg-12 col-12 text-center">
	      	<h3><B>SUMMER PROGRAM '.$gyear.' STUDENT REPORT CARD</B></h3>
	    	</div> </div>';
	      	$output.= '<div class="card-header StudentViewTextInfo">
		      	<div class="row" style="background:url(../img/bg.jpg);background-size:cover;background-position:center; background-repeat:no-repeat;">
			      	<div class="col-lg-12 col-12">
			      	 <h3><b>Student Name: '.strtoupper($row_student->fname).' '.strtoupper($row_student->mname).' '.strtoupper($row_student->lname).'</b></h3>
			      	</div>
			      	<div class="col-lg-6 col-6">
			      		<h4><b> Grade: '.strtoupper($row_student->gradesec).' </b></h4>
			      	</div>
			      	<div class="col-lg-6 col-6">
			      		<h4><b>Academic Year: '.$max_year.'</b></h4>
			      	</div>
		      	</div>
	      	</div>
	      	</div>
	      	<div class="dropdown-divider"></div>
	      	<div class="row">
	      	<div class="col-lg-6">
	        <div class="table-responsive">
	        <table width="100%"  class="table-bordered table-md" cellspacing="5" cellpadding="5">';
	      	$output.='<tr><th>Subject</th>';
	      	$output .='<th class="text-center" colspan="2">Summer Class</th>';
	      	$output.='</tr>';
	        
	        foreach ($query_result->result() as $qvalue_result) 
	        {
	          	$subject=$qvalue_result->Subj_name;
	            $output .='<tr><td>'.$qvalue_result->Subj_name.'</td>';
	          	
              	$query_qua_result=$this->db->query(" Select * from summermark where mgrade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and subname='$subject' ");
              	if($query_qua_result->num_rows()>0)
              	{
	            		foreach ($query_qua_result->result() as $quvalue)
	            		{
	                	/*$letter=$quvalue->letter;*/
	                	$result=$quvalue->total;
	                  $output .='<td class="text-center">'.$result.'</td>';
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
	        	$output .='</tr>'; 
	        }	        
            $output .='<tr><td><b>Total</b></td>';
          	/*$query_qua_total=$this->db->query(" Select sum(total) as quarter_total from summermark where mgrade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and onreportcard='1' group by quarter order by subject ASC ");
          	if($query_qua_total->num_rows()>0) {
            	foreach ($query_qua_total->result() as $qtvalue){
              		$output .= '<td class="text-center" colspan="2"><B>'.number_format((float)$qtvalue->quarter_total,2,'.','').'</B></td>';
            	}
          	} else{
            	$output .='<td class="text-center" colspan="2">-</td>';
          	}	*/	
            $output .='</tr>';
            $output .='<tr><td><b>Average</b></td>';
        	/*$query_qua_total=$this->db->query(" Select sum(total) as quarter_total from summermark where mgrade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and onreportcard='1' group by quarter order by subject ASC ");
          
          	$count_subject=$this->db->query("select * from summermark where mgrade='$gradesec' and letter!='A' and onreportcard='1' and academicyear='$max_year' group by subject ");
      		$total_subject=$count_subject->num_rows();
      		if($query_qua_total->num_rows()>0) {
            	foreach ($query_qua_total->result() as $qtvalue) {
            		$averageSummer=number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','');
              		$output .= '<td class="text-center" colspan="2"><B>'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</B></td>';
            	}
          	}else{
            	$output .='<td class="text-center" colspan="2">-</td>';
          	}*/
          	
            $output .='</tr>';
	        $output .='<tr><td><b>No. of Absence</b></td>';
	        $queryTotalAbsent=$this->db->query("select count(stuid) as att from summerattendance as attt where attt.stuid='$username1' and attt.academicyear='$max_year' and absentype='Absent' ");
		      if($queryTotalAbsent->num_rows()>0){
		        foreach ($queryTotalAbsent->result() as $absent){
		          if($absent->att>0)
		          {
		            $output .= '<td class="text-center" colspan="2">'.$absent->att.'</td>';
		          }
		          else{
		            $output .= '<td class="text-center" colspan="2">-</td>';
		          }
		        }
		      }else{
		        $output.='<td class="text-center" colspan="2">-</td>';
		      }
	        $output .='</tr>';
	      	$output .='</table></div></div>';

	      	$output.= "<div class='col-lg-6'>
	      	<h5 id='ENS' class='text-center'><u>GRADING SYSTEM</u></h5>";
	      	$queryKey=$this->db->query("select * from letterange where grade = '$grade' and academicYear='$max_year' ");
	      	$output.="<div class='keyTextInfo' style='background:url(../img/bg.jpg);background-size:cover;background-position:center; background-repeat:no-repeat;'> <div class='row'>";
	        foreach($queryKey->result() as $keyVal){
	            $output.='<div class="col-lg-6 col-6 StudentViewTextInfo">';
	            $output.='<h4 class="title-header">'.$keyVal->minValue.'-'.$keyVal->maxiValue.'='.$keyVal->letterVal.'</h4>';
	            $output.='</div>';
	        }
	      	$output.="</div></div></div>";
	      	$output.= '<div class="col-lg-12 col-12"><div class="dropdown-divider"></div>';
	      	$output.="<h5 id='ENS'><u>GENERAL COMMENTS AND RECOMMENDATIONS</u></h5>";
	      	/*$output.="<h5 id='ENS' class='text-center'>የክፍል ሃላፊ መምህር አስተያየት/Homeroom Teacher's Remark</h5>";*/
	      	$reportCardComments=$this->db->query("select commentvalue from reportcardcomments where grade='$grade' and $averageSummer between mingradevalue and maxgradevalue and academicYear='$max_year'");
            if($averageSummer >0 && $reportCardComments->num_rows()>0){
              foreach($reportCardComments->result() as $commentValue){
                $output .= '<u>'.$commentValue->commentvalue.'</u><br>';
              }
              
            }else{
              $output.='____________________________________________________________________ _________________________ ____________________________________________________________________ _________________________ <br>';
            }
	      	$queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u where usertype='superAdmin' and u.branch='$branch' and isapproved='1' and finalapproval='1' and status='Active' and mysign!='' ");
             if($queryDirector->num_rows()>0){
              $rowSignD=$queryDirector->row();
              $signName=$rowSignD->fname;
              $signmame=$rowSignD->mname;
              $signlame=$rowSignD->lname;
              $signlame=$rowSignD->lname;
              $signSigns=$rowSignD->mysign;
              $output.="<p class='text-center'>School Head Name <u><b>".$signName." ".$signmame."</b></u> Signature  <img alt='Sig.' src='".base_url()."/".$signSigns."' class='' style='height:40px;width:120px'></p>";
            }else{
              $output.="<p class='text-center'>School Head Name______________________ Signature____________</p>";
            }
	      $output.='</div></div>';
	    }
	    }else{
	    	$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div>';
	    }
	    return $output;
	}
	function fetch_myseason_progress($gs_branches,$gs_gradesec,$gs_quarter,$grade,$max_year,$id,$fName,$mName,$lName)
	{
		$output='';
		$dataSubject=array();
		$dataEvaluation=array();
		$queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."' ");
		if ($queryCheck->num_rows()>0)
		{
			$query_name = $this->db->query("select * from school");
			$row_name = $query_name->row_array();
			$school_name=$row_name['name'];
			$school_slogan=$row_name['slogan'];

			$querySlogan1="select gyear from academicyear where year_name=? ";
			$querySlogan=$this->db->query($querySlogan1,array($max_year));
	    	$rowGyear = $querySlogan->row();
	    	$gYearName=$rowGyear->gyear;

			$this->db->where('Academic_Year',$max_year);
			$this->db->where('Grade',$grade);
			$this->db->order_by('suborder','ASC');
			$querySubject=$this->db->get('subject');

			$this->db->where('academicyear',$max_year);
			$this->db->where('grade',$grade);
			$queryRangeValue=$this->db->get('letterange');

			$this->db->order_by('year_name','DESC');
			$queryac=$this->db->get('academicyear');

			$output.='<div class="row">';
      		if($querySubject->num_rows()>0){
      			$rototal_subject=$querySubject->num_rows();
      			foreach($querySubject->result() as $subjectRow){
      				$output.='<div class="col-12 col-sm-6 col-md-6 col-lg-6 mt-lg-0 mt-sm-4">
    				<div class="card">
      					<div class="card-body StudentViewTextInfo">
      					<h4 class="title-header">Result Statistics</h4>';
      					$finalResult=0;
    					$gs_subject=$subjectRow->Subj_name;
						$this->db->select('us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent');
						$this->db->from('evaluation ev');
						$this->db->join('users us',
						'us.grade=ev.grade');
						$this->db->order_by('ev.eid','ASC');
						$this->db->group_by('ev.evname');
						$this->db->where('ev.academicyear',$max_year);
						$this->db->where('ev.quarter',$gs_quarter);
						$this->db->where('us.gradesec',$gs_gradesec);
						$this->db->where('us.branch',$gs_branches);
						$this->db->where('us.academicyear',$max_year);
						$evalname_query=$this->db->get();
						$output.='<h4>'.$gs_subject.'</h4><hr>';
      					$average=0;
      					foreach ($evalname_query->result_array() as $mark_name) {
			      			$evName=$mark_name['evname'];
			      			$grade=$mark_name['grade'];
			      			$mname_gs=$mark_name['eid'];
			      			$this->db->where('academicyear',$max_year);
							$this->db->where('customgrade',$grade);
							$this->db->where('customsubject',$gs_subject);
							$this->db->where('customasses',$evName);
							$this->db->where('customquarter',$gs_quarter);
							$queryCheckPercentage=$this->db->get('evaluationcustom');
				          	if($queryCheckPercentage->num_rows()>0){
				          		$rowPercent=$queryCheckPercentage->row();
				          		$percent=$rowPercent->custompercent;
				          	}else{
				          		$percent= $mark_name['percent'];
				          	}
      						$output.=' <div class="support-ticket">
                				<div class="media-body">
                  				<span class="font-weight-bold">'.$mark_name['evname'].'(<a href="javascript:void(0)"> '.$percent.'%</a>)</span> ';
                  			$this->db->select('markname,sum(value) as total');
                  			$this->db->where('mbranch',$gs_branches);
							$this->db->where('mgrade',$gs_gradesec);
							$this->db->where('subname',$gs_subject);
							$this->db->where('evaid',$mname_gs);
							$this->db->where('quarter',$gs_quarter);
							$this->db->order_by('mid','ASC');
							$this->db->group_by('markname');
							$query_value=$this->db->get('"mark"'.$gs_branches.$gs_gradesec.$gs_quarter.$max_year);
							if($query_value->num_rows()>0){
								$totalMark=0;$outofTot=0;
								foreach ($query_value->result_array() as $value) {
									$markNameStu=$value['markname'];
									$this->db->select('value,sum(value) as total,sum(outof) as outof');
		                  			$this->db->where('stuid',$id);
		                  			$this->db->where('mbranch',$gs_branches);
									$this->db->where('mgrade',$gs_gradesec);
									$this->db->where('subname',$gs_subject);
									$this->db->where('evaid',$mname_gs);
									$this->db->where('quarter',$gs_quarter);
									$this->db->where('markname',$markNameStu);
									$this->db->order_by('mid','ASC');
									$this->db->group_by('markname');
									$queryStuValue=$this->db->get('"mark"'.$gs_branches.$gs_gradesec.$gs_quarter.$max_year);
									if($queryStuValue->num_rows()>0){
										foreach ($queryStuValue->result_array() as $kevalue) {
											$outofTot=$outofTot+$kevalue['outof'];
											$totalMark=$totalMark+$kevalue['value'];
										}
									}
									$this->db->select('outof,sum(outof) as sum_outof');
		                  			$this->db->where('mbranch',$gs_branches);
									$this->db->where('mgrade',$gs_gradesec);
									$this->db->where('subname',$gs_subject);
									$this->db->where('evaid',$mname_gs);
									$this->db->where('quarter',$gs_quarter);
									$this->db->order_by('mid','ASC');
									$this->db->group_by('markname');
									$queryMvalue=$this->db->get('"mark"'.$gs_branches.$gs_gradesec.$gs_quarter.$max_year);
					            	$sumOutOf=0;
						            foreach ($queryMvalue->result_array() as $mark_name) {
						            	$sumOutOf=$mark_name['outof'] + $sumOutOf;	
						            }
          						}
            					if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='') {
            						$conver= ($totalMark *$percent )/$sumOutOf;
				            		if($totalMark>0){
				            			$output.='
				            			<div class="badge badge-pill badge-success mb-1">Result: '.number_format((float)$conver,2,'.','').'</div>';
				            		}else{
				            			$output.='<div class="badge badge-pill badge-warning mb-1">Result: 0.00</div>';
				            		}
      								$average =$conver + $average;
				          		}else{
				          			$output.='<div class="badge badge-pill badge-warning mb-1">Result: 0.00</div>';
				          		}
			          	}else{
			          		$output.='<div class="badge badge-pill badge-warning mb-1">Result: 0.00</div>';
			          	}
		        		$output.='</div></div><hr>';
	        		}
         			$output.='</div>
  				</div>
			</div>';
        }
      }
      $output.='</div>';
	  
	  usort($dataSubject, 'compareByPrice');
	  $output.='<div class="row">';
	  $output.='<div class="col-12 col-sm-6 col-md-6 col-lg-6 mt-lg-0 mt-sm-4">
    <div class="card"> <div class="card-body StudentViewTextInfo"><h4>Subject Rank Order</h4><hr>';
	  
		$output.='</div></div></div>';
		}else{
	  	$output.='<div class="alert alert-warning alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        <i class="fas fa-check-circle"> </i> No record found.
			</div>
			</div>';
	  }
		/*$output.='<div class="col-12 col-sm-6 col-md-6 col-lg-6 mt-lg-0 mt-sm-4">
    <div class="card"> <div class="card-body StudentViewTextInfo">Subject Rank Order<hr>';*/
		$output.='</div>';
		return $output;
	}
	function fetch_myseason_progress_sample($gs_branches,$gs_gradesec,$gs_quarter,$grade,$max_year,$id,$fName,$mName,$lName)
	{
		$output='';
		$dataSubject=array();
		$dataEvaluation=array();
		$queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."' ");
		if ($queryCheck->num_rows()>0)
		{
			$query_name = $this->db->query("select * from school");
			$row_name = $query_name->row_array();
			$school_name=$row_name['name'];
			$school_slogan=$row_name['slogan'];

			$querySlogan1="select gyear from academicyear where year_name=? ";
			$querySlogan=$this->db->query($querySlogan1,array($max_year));
	    	$rowGyear = $querySlogan->row();
	    	$gYearName=$rowGyear->gyear;

	    	$this->db->where('Academic_Year',$max_year);
			$this->db->where('Grade',$grade);
			$this->db->where('student_view','1');
			$this->db->order_by('suborder','ASC');
			$querySubject=$this->db->get('subject');

			$this->db->where('academicyear',$max_year);
			$this->db->where('grade',$grade);
			$queryRangeValue=$this->db->get('letterange');

			$this->db->order_by('year_name','DESC');
			$queryac=$this->db->get('academicyear');

      		if($querySubject->num_rows()>0){
      			$rototal_subject=$querySubject->num_rows();
      			foreach($querySubject->result_array() as $subjectRow){
      				$finalResult=0;
    				$gs_subject=$subjectRow['Subj_name'];

    				$this->db->select('us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent');
					$this->db->from('evaluation ev');
					$this->db->join('users us',
					'us.grade=ev.grade');
					$this->db->order_by('ev.eid','ASC');
					$this->db->group_by('ev.evname');
					$this->db->where('ev.academicyear',$max_year);
					$this->db->where('ev.quarter',$gs_quarter);
					$this->db->where('us.gradesec',$gs_gradesec);
					$this->db->where('us.branch',$gs_branches);
					$this->db->where('us.academicyear',$max_year);
					$evalname_query=$this->db->get();

      				$average=0;
      				foreach ($evalname_query->result_array() as $mark_name) {
		      			$evName=$mark_name['evname'];
		      			$grade=$mark_name['grade'];
		      			$mname_gs=$mark_name['eid'];
		      			$this->db->where('academicyear',$max_year);
						$this->db->where('customgrade',$grade);
						$this->db->where('customsubject',$gs_subject);
						$this->db->where('customasses',$evName);
						$this->db->where('customquarter',$gs_quarter);
						$queryCheckPercentage=$this->db->get('evaluationcustom');

			          	if($queryCheckPercentage->num_rows()>0){
			          		$rowPercent=$queryCheckPercentage->row();
			          		$percent=$rowPercent->custompercent;
			          	}else{
			          		$percent= $mark_name['percent'];
			          	}
			          	$this->db->select('markname,sum(value) as total');
              			$this->db->where('mbranch',$gs_branches);
						$this->db->where('mgrade',$gs_gradesec);
						$this->db->where('subname',$gs_subject);
						$this->db->where('evaid',$mname_gs);
						$this->db->where('quarter',$gs_quarter);
						$this->db->order_by('mid','ASC');
						$this->db->group_by('markname');
						$query_value=$this->db->get('mark'.$gs_branches.$gs_gradesec.$gs_quarter.$max_year);
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$this->db->select('value,sum(value) as total,sum(outof) as outof');
	                  			$this->db->where('stuid',$id);
	                  			$this->db->where('mbranch',$gs_branches);
								$this->db->where('mgrade',$gs_gradesec);
								$this->db->where('subname',$gs_subject);
								$this->db->where('evaid',$mname_gs);
								$this->db->where('quarter',$gs_quarter);
								$this->db->where('markname',$markNameStu);
								$this->db->order_by('mid','ASC');
								$this->db->group_by('markname');
								$queryStuValue=$this->db->get('mark'.$gs_branches.$gs_gradesec.$gs_quarter.$max_year);
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
									}
								}
								$this->db->select('outof,sum(outof) as sum_outof');
	                  			$this->db->where('mbranch',$gs_branches);
								$this->db->where('mgrade',$gs_gradesec);
								$this->db->where('subname',$gs_subject);
								$this->db->where('evaid',$mname_gs);
								$this->db->where('quarter',$gs_quarter);
								$this->db->order_by('mid','ASC');
								$this->db->group_by('markname');
								$queryMvalue=$this->db->get('mark'.$gs_branches.$gs_gradesec.$gs_quarter.$max_year);
	            				$sumOutOf=0;
					            foreach ($queryMvalue->result_array() as $mark_name) {
					            	$sumOutOf=$mark_name['outof'] + $sumOutOf;	
					            }
          					}
			            	if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='') {
			            		$conver= ($totalMark *$percent )/$sumOutOf;
			            		$lastConvesion=number_format((float)$conver,2,'.','');
			      					$average =$lastConvesion + $average;
			          		}
          				}
	        		}
			        $finalResult=number_format((float)$average,2,'.','');
							$dataSubject[] = array(
				        'subject' => $gs_subject,
				        'total' => $finalResult,
				        'color' =>  '#' . rand(100000, 999999) . ''
				      );
		        	$average=0;
        		}
      		}
	  	}
	  	return array('dataSubject' => $dataSubject);
	}
	function checkout_payment($gs_branches,$gs_gradesec,$gs_quarter,$grade,$max_year,$id,$fName,$mName,$lName){
		$output='';
		$dataSubject=array();
		$dataEvaluation=array();
		$queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."' ");
		if ($queryCheck->num_rows()>0)
		{
			$output.='<div class="row">';
			$output.='<div class="col-lg-12 text-right">
        <div class="invoice-detail-item">
          <div class="invoice-detail-name">'.$fName.' '.$mName.'</div>
          <div class="invoice-detail-value">Br.50</div>
        </div>
        <hr class="mt-2 mb-2">
        <div class="invoice-detail-item">
          <div class="invoice-detail-name">Total</div>
          <div class="invoice-detail-value invoice-detail-value-lg">Br.50</div>
        </div>
      </div> ';
      
      $output.='</div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
	        <div class="alert-body">
	            <button class="close"  data-dismiss="alert">
	                <span>&times;</span>
	            </button>
	        <i class="fas fa-check-circle"> </i> No record found.
				</div>
				</div>';
		}
		return $output;
	}
}
?>