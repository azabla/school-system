<?php
class teacher_model extends CI_Model{
	function fetch_term_4teacheer($max_year){
		$this->db->where('Academic_year',$max_year);
		$this->db->select_max('term');
		/*$this->db->group_by('term');*/
		$this->db->order_by('term','ASC');
		$query=$this->db->get('quarter');
		return $query->result();
	}
	function fetch_evaluation_fornewexam($max_year){
		$this->db->order_by('eid','ASC');
		$this->db->group_by('evname');
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->get('evaluation');
		return $query->result();
	}
	function fetch_session_user($user){
		$this->db->where('username',$user);
		$this->db->group_by('username');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetch_branch($max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->group_by('name');
		$this->db->order_by('name','ASC');
		$query=$this->db->get('branch');
		return $query->result();
	}
	function academic_year_filter(){
		$this->db->select_max('year_name');
		$query=$this->db->get('academicyear');
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
			$this->db->order_by('grade','ASC');
			$query=$this->db->get('staffplacement');
			return $query->result();
    }
	}
	function fetchGradeForSummaryDirector($user,$max_year){
		$this->db->select('users.grade');
		$this->db->from('users');
		$this->db->join('directorplacement',
		'directorplacement.grade=users.gradesec');
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('directorplacement.academicyear',$max_year);
		$this->db->where('directorplacement.staff',$user);
		$this->db->group_by('users.grade');
		$query=$this->db->get();
		return $query->result();
	}
	function fetchGradeForSummaryTeacher($user,$max_year){
		$this->db->select('users.grade');
		$this->db->from('users');
		$this->db->join('staffplacement',
		'staffplacement.grade=users.gradesec');
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('staffplacement.academicyear',$max_year);
		$this->db->where('staffplacement.staff',$user);
		$this->db->group_by('users.grade');
		$query=$this->db->get();
		return $query->result();
	}
	function fetchCommunicationBookTeacherApprove($user,$subject,$gradesec,$branch_teacher,$max_year,$max_quarter){
		$this->db->select('users.profile,users.fname,users.mname,users.lname,users.username,communicationbook.id,communicationbook.quarter,communicationbook.comsubject,communicationbook.comnote,communicationbook.status,communicationbook.stuid,communicationbook.comgrade,communicationbook.comcommented, communicationbook.datecreated,communicationbook.byteacher, communicationbook.approvecom');
		$this->db->from('communicationbook');
		$this->db->join('users',
		'users.username=communicationbook.byteacher');
		$this->db->join('directorplacement',
		'directorplacement.grade=communicationbook.comgrade');
		$this->db->order_by('communicationbook.id','DESC');
		$this->db->group_by('communicationbook.id');
		$this->db->where('users.branch',$branch_teacher);
		$this->db->where('communicationbook.academicyear',$max_year);
		/*$this->db->where('communicationbook.quarter',$max_quarter);*/
		$this->db->where('communicationbook.byteacher',$user);
		$this->db->where('communicationbook.comsubject',$subject);
		$this->db->where('communicationbook.comgrade',$gradesec);
		$this->db->where('communicationbook.combranch',$branch_teacher);
		$this->db->where('communicationbook.approvecom','1');
		$this->db->or_where('users.branch',$branch_teacher);
		$this->db->where('communicationbook.academicyear',$max_year);
		/*$this->db->where('communicationbook.quarter',$max_quarter);*/
		$this->db->where('communicationbook.stuid',$user);
		$this->db->where('communicationbook.comsubject',$subject);
		$this->db->where('communicationbook.comgrade',$gradesec);
		$this->db->where('communicationbook.combranch',$branch_teacher);
		$this->db->where('communicationbook.approvecom','1');
		
		$this->db->or_where('directorplacement.staff',$user);
		$this->db->where('users.branch',$branch_teacher);
		$this->db->where('communicationbook.academicyear',$max_year);
		/*$this->db->where('communicationbook.quarter',$max_quarter);*/
		$this->db->where('communicationbook.comsubject',$subject);
		$this->db->where('communicationbook.comgrade',$gradesec);
		$this->db->where('communicationbook.combranch',$branch_teacher);
		$this->db->where('communicationbook.approvecom','1');
		$query=$this->db->get();
		$output='';
		$output.='<div class="row"> 
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
            	<button class="btn btn-default btn-lg backTo_MainPage"><h3> <i class="fas fa-chevron-left"  style="font-size: 30px;" ></i> Back </h3></button>
            	</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
				<a href="#" class="AddNewCommunicationBook" value="'.$subject.'" id="'.$gradesec.'" data-year="'.$max_year.'" data-branch="'.$branch_teacher.'" data-toggle="modal" data-target="#AddNewCommunicationBook">
              		<button class="btn btn-primary pull-right"><i class="fas fa-plus-circle"></i> Communication Book</button>
            	</a>
            	</div>
            	
			</div>';
		if($query->num_rows()>0){
			$this->db->where('communicationbook.comgrade',$gradesec);
			$this->db->where('communicationbook.academicyear',$max_year);
			$this->db->where('communicationbook.comsubject',$subject);
			$this->db->where('communicationbook.stuid',$user);
			$this->db->set('status','1');
			$queryUpdate=$this->db->update('communicationbook');
			foreach($query->result() as $bookSent){
				$stuid=$bookSent->stuid;
				$byteacher=$bookSent->byteacher;
				$id=$bookSent->id;
				$statusCheck=$bookSent->status;
				$output.='<div class="chat-container-gss">
			        <div class="message-container-gs">';
				$output .='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12" id="deleteThisComBook'.$id.'">';
		      	if($statusCheck=='1' && $byteacher==$user){
		          	$output.='<div class="message-gs-full sender-message-gs">
		          		<div class="support-ticket media">';
		           			$output.='<div class="media-body">
		             			<p class="p">'.$bookSent->comnote.'<span class="text-success"> <i class="fa fa-check-double"></i> seen</span>
		             				<small class="time text-muted"> '.$bookSent->datecreated.' </small>
		             			</p>
		             		</div>
		           		</div>
		         	</div>';	
		        }else{
		        	if($byteacher==$user){
		        		$output.='<div class="message-gs-full sender-message-gs">
			        		<div class="support-ticket media">';
			           			$output.='<div class="media-body">
			             			<p class="p">'.$bookSent->comnote.' ';
						             	if($bookSent->status=='0' && $bookSent->byteacher==$user){
						             		$output.='<a href="#" class="text-success editThisComBook" value="'.$bookSent->id.'" data-toggle="modal" data-target="#editNewCommunicationBook"><i class="fas fa-edit"></i></a>
						             		<a href="#" class="text-danger deleteThisComBook" value="'.$bookSent->id.'"><i class="fas fa-trash-alt"></i></a>';
						             	}
			             				$output.=' <small class="time text-muted">   <small class="time text-muted"> '.$bookSent->datecreated.' </small></small>
			             			</p>
			           			</div>
			           		</div>
			         	</div>';
		        	}else{
			        	$output.='<div class="message-gs-full receiver-message-gs">
			        		<div class="support-ticket media">';
					        	if($bookSent->profile == ''){
			                      	$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" class="user-img mr-2">';
			                    }else{
			                      	$output.='<img alt="Photo" src="'.base_url().'/profile/'.$bookSent->profile.'" class="user-img mr-2">';
			                    }
			           			$output.='<div class="media-body">'.$bookSent->fname.' '.$bookSent->mname.'
			             			<p class="p">'.$bookSent->comnote.' ';
						             	if($bookSent->status=='0' && $bookSent->byteacher==$user){
						             		$output.='<a href="#" class="text-success editThisComBook" value="'.$bookSent->id.'" data-toggle="modal" data-target="#editNewCommunicationBook"><i class="fas fa-edit"></i></a>
						             		<a href="#" class="text-danger deleteThisComBook" value="'.$bookSent->id.'"><i class="fas fa-trash-alt"></i></a>';
						             	}
			             				$output.=' <small class="time text-muted">   <small class="time text-muted"> '.$bookSent->datecreated.' </small></small>
			             			</p>
			           			</div>
			           		</div>
			         	</div>';
			        }
		        }
		      	$output.='</div></div></div>';	
			}
			
		}else{
			$output.='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12"><div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div></div>';
		}
		$output.='</div>';
		return $output;
	}
	function fetchCommunicationBookTeacher($user,$subject,$gradesec,$branch_teacher,$max_year,$max_quarter){
		$this->db->select('users.profile,users.fname,users.mname,users.lname,users.username,communicationbook.id,communicationbook.quarter,communicationbook.comsubject,communicationbook.comnote,communicationbook.status,communicationbook.stuid,communicationbook.comgrade,communicationbook.comcommented, communicationbook.datecreated,communicationbook.byteacher');
		$this->db->from('communicationbook');
		$this->db->join('users',
		'users.username=communicationbook.byteacher');
		$this->db->join('directorplacement',
		'directorplacement.grade=communicationbook.comgrade');
		$this->db->order_by('communicationbook.id','DESC');
		$this->db->group_by('communicationbook.id');
		$this->db->where('users.branch',$branch_teacher);
		$this->db->where('communicationbook.academicyear',$max_year);
		/*$this->db->where('communicationbook.quarter',$max_quarter);*/
		$this->db->where('communicationbook.byteacher',$user);
		$this->db->where('communicationbook.comsubject',$subject);
		$this->db->where('communicationbook.comgrade',$gradesec);
		$this->db->where('communicationbook.combranch',$branch_teacher);
		/*$this->db->where('communicationbook.approvecom','1');*/
		$this->db->or_where('users.branch',$branch_teacher);
		$this->db->where('communicationbook.academicyear',$max_year);
		/*$this->db->where('communicationbook.quarter',$max_quarter);*/
		$this->db->where('communicationbook.stuid',$user);
		$this->db->where('communicationbook.comsubject',$subject);
		$this->db->where('communicationbook.comgrade',$gradesec);
		$this->db->where('communicationbook.combranch',$branch_teacher);
		/*$this->db->where('communicationbook.approvecom','1');*/
		$this->db->or_where('directorplacement.staff',$user);
		$this->db->where('users.branch',$branch_teacher);
		$this->db->where('communicationbook.academicyear',$max_year);
		/*$this->db->where('communicationbook.quarter',$max_quarter);*/
		$this->db->where('communicationbook.comsubject',$subject);
		$this->db->where('communicationbook.comgrade',$gradesec);
		$this->db->where('communicationbook.combranch',$branch_teacher);
		/*$this->db->where('communicationbook.approvecom','1');*/
		$query=$this->db->get();
		$output='';
		$output.='<div class="row"> 
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
            	<button class="btn btn-default btn-lg backTo_MainPage"><h3> <i class="fas fa-chevron-left"  style="font-size: 30px;" ></i> Back </h3></button>
            	</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
				<a href="#" class="AddNewCommunicationBook" value="'.$subject.'" id="'.$gradesec.'" data-year="'.$max_year.'" data-branch="'.$branch_teacher.'" data-toggle="modal" data-target="#AddNewCommunicationBook">
              		<button class="btn btn-primary pull-right"><i class="fas fa-plus-circle"></i> Communication Book</button>
            	</a>
            	</div>
            	
			</div>';
		if($query->num_rows()>0){
			$this->db->where('communicationbook.comgrade',$gradesec);
			$this->db->where('communicationbook.academicyear',$max_year);
			$this->db->where('communicationbook.comsubject',$subject);
			$this->db->where('communicationbook.stuid',$user);
			$this->db->set('status','1');
			$queryUpdate=$this->db->update('communicationbook');
			foreach($query->result() as $bookSent){
				$stuid=$bookSent->stuid;
				$byteacher=$bookSent->byteacher;
				$id=$bookSent->id;
				$statusCheck=$bookSent->status;
				$output.='<div class="chat-container-gss">
			        <div class="message-container-gs">';
				$output .='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12" id="deleteThisComBook'.$id.'">';
		      	if($statusCheck=='1' && $byteacher==$user){
		          	$output.='<div class="message-gs-full sender-message-gs">
		          		<div class="support-ticket media">';
		           			$output.='<div class="media-body">
		             			<p class="p">'.$bookSent->comnote.'<span class="text-success"> <i class="fa fa-check-double"></i> seen</span>
		             				<small class="time text-muted"> '.$bookSent->datecreated.' </small>
		             			</p>
		             		</div>
		           		</div>
		         	</div>';	
		        }else{
		        	if($byteacher==$user){
		        		$output.='<div class="message-gs-full sender-message-gs">
			        		<div class="support-ticket media">';
			           			$output.='<div class="media-body">
			             			<p class="p">'.$bookSent->comnote.' ';
						             	if($bookSent->status=='0' && $bookSent->byteacher==$user){
						             		$output.='<a href="#" class="text-success editThisComBook" value="'.$bookSent->id.'" data-toggle="modal" data-target="#editNewCommunicationBook"><i class="fas fa-edit"></i></a>
						             		<a href="#" class="text-danger deleteThisComBook" value="'.$bookSent->id.'"><i class="fas fa-trash-alt"></i></a>';
						             	}
			             				$output.=' <small class="time text-muted">   <small class="time text-muted"> '.$bookSent->datecreated.' </small></small>
			             			</p>
			           			</div>
			           		</div>
			         	</div>';
		        	}else{
			        	$output.='<div class="message-gs-full receiver-message-gs">
			        		<div class="support-ticket media">';
					        	if($bookSent->profile == ''){
			                      	$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" class="user-img mr-2">';
			                    }else{
			                      	$output.='<img alt="Photo" src="'.base_url().'/profile/'.$bookSent->profile.'" class="user-img mr-2">';
			                    }
			           			$output.='<div class="media-body">'.$bookSent->fname.' '.$bookSent->mname.'
			             			<p class="p">'.$bookSent->comnote.' ';
						             	if($bookSent->status=='0' && $bookSent->byteacher==$user){
						             		$output.='<a href="#" class="text-success editThisComBook" value="'.$bookSent->id.'" data-toggle="modal" data-target="#editNewCommunicationBook"><i class="fas fa-edit"></i></a>
						             		<a href="#" class="text-danger deleteThisComBook" value="'.$bookSent->id.'"><i class="fas fa-trash-alt"></i></a>';
						             	}
			             				$output.=' <small class="time text-muted">   <small class="time text-muted"> '.$bookSent->datecreated.' </small></small>
			             			</p>
			           			</div>
			           		</div>
			         	</div>';
			        }
		        }
		      	$output.='</div></div></div>';	
			}
			
		}else{
			$output.='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12"><div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div></div>';
		}
		$output.='</div>';
		return $output;
	}
	public function fetch_commbook_form_toedit($id){
		$output='';
		$this->db->where(array('id'=>$id));
		$query=$this->db->get('communicationbook');
		$output.='<form id="updateCommunicationForm">
		<div class="card">
		<div class="card-body StudentViewTextInfo">
		<div class="row">';
		foreach ($query->result() as $row) { 
			$gradesec=$row->comgrade;
			$subject=$row->comsubject;
			$branch=$row->combranch;
			$academicyear=$row->academicyear;
			$comNote=$row->comnote;
			$output.=' <h4 class="card-header">'.$gradesec.' <i class="fas fa-chevron-right"></i> '.$subject.'</h4>
			<input type="hidden" id="updatedcomGradesec" name="updatedcomGradesec" value="'.$gradesec.'">
			<input type="hidden" id="updatedcomSubject" name="updatedcomSubject" value="'.$subject.'">
			<input type="hidden" id="updatedcomBranch" name="updatedcomBranch" value="'.$branch.'">
			<input type="hidden" id="updatedcomID" name="updatedcomID" value="'.$id.'">
			<input type="hidden" id="updatedcomAcademicYear" name="updatedcomAcademicYear" value="'.$academicyear.'">'; 
			$output.='<div class="form-group col-lg-12 col-12">
				<textarea name="updatedcomNote" id="updatedcomNote" class="form-control updatedcomNote">'.$comNote.' </textarea>';
        	$output.='</div>
	        <div class="col-lg-12 col-12">
	          	<button class="btn btn-primary btn-md pull-right" type="submit" name="updateCommBook">Update Communication Book</button>
	        </div>';
    	}
		$output.='</div></div></div></form>';
		return $output;
	}
	function fetchStudents4_asp_Attendance_Report($gradesecs,$attBranches,$max_year){
		$output ='';
		$query_school=$this->db->get('school');
		$schoolRow=$query_school->row();
		$website=$schoolRow->website;
		$logo=$schoolRow->logo;
		$name=$schoolRow->name;
		$output='<p><h2 class="text-center"><b><u>'.$name.' Grade Statistics for '.$max_year.' Academic Year <small>('.$attBranches.')</small></u></b></h2></p>';
		foreach($gradesecs as $gradesec){
			$this->db->where('grade',$gradesec);
			$this->db->where('branch',$attBranches);
			$this->db->where('isapproved','1');
			$this->db->where('status','Active');
			$this->db->where('asp','Yes');
			$this->db->where(array('academicyear'=>$max_year));
			$this->db->order_by('fname ,mname,lname','ASC');
			$query = $this->db->get('users');
	        $output .='
	        <div class="table-responsive">
	            <table class="table table-striped table-hover" style="width:100%;">
	            <thead>
	                <tr>
	                <th>No.</th>
	                    <th>Student Name</th>
	                    <th>Student ID</th>
	                    <th>Grade</th>
	                    <th>Gender</th>
	                    <th>ASP Status</th>
	                </tr>
	            </thead>
	        <tbody>';
	        $no=1;
	        foreach ($query->result() as $row) {
		        $output .='<tr><td>'.$no.'.</td>
		        <td>'.$row->fname.' '.$row->mname.' '.$row->lname.' </td>
		        <td>'.$row->username.'</td>
		        <td>'.$row->gradesec.'</td>
		        <td>'.$row->gender.'</td><td><span class="badge badge-light">Active</span></td> </tr>';
		        $no++;
	        }
	        $output.='</tbody> </table> </div>';
	    }
        return $output;
	}
	function viewComBookId($lessonID,$max_year){
		$this->db->where('communicationbook.id',$lessonID);
		$this->db->where('communicationbook.academicyear',$max_year);
		$query = $this->db->get('communicationbook');
		$output='';

		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
		foreach($query->result() as $lessonP){
			$output.='<div id="printLessonPlanGs">
			<h3 class="text-center"><p><u><b>'.$school_name.' Communication Book for '.$max_year.' Academic Year</b></u></p></h3>
			<p><h5 class="text-center"><u>Subject: <b>'.$lessonP->comsubject.'</b> & Grade: <b>'.$lessonP->comgrade.'</b></u></h5></p>';
			$output.='<div class="row">
              <div class="col-lg-12">
                <div class="form-group" id="ENS">
                  <label for="Mobile">Comments</label>
                  '.$lessonP->comnote.'
                </div>
              </div>
            </div>
            </div>';
		}
		return $output;
	}
	function fetch_unseen_comBook_notification($user,$branch_teacher,$max_year,$max_quarter){
		$this->db->select('*');
		$this->db->from('communicationbook');
		$this->db->join('directorplacement',
		'directorplacement.grade=communicationbook.comgrade');
		
		$this->db->order_by('communicationbook.id','DESC');
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.quarter',$max_quarter);

		$this->db->where('communicationbook.approvecom','0');
		$this->db->where('communicationbook.combranch',$branch_teacher);
		$this->db->where('directorplacement.academicyear',$max_year);
		$this->db->where('directorplacement.staff',$user);
		
		return $this->db->count_all_results();
	}
	function unseenreplyComBbok($user,$branch_teacher,$max_year,$max_quarter){
		$this->db->select('*');
		$this->db->from('communicationbook');
		$this->db->join('combookreplaystudent',
		'combookreplaystudent.replyid=communicationbook.id');
		
		$this->db->order_by('communicationbook.id','DESC');
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.quarter',$max_quarter);

		$this->db->where('combookreplaystudent.seenstatus','0');
		$this->db->where('communicationbook.byteacher',$user);
		
		return $this->db->count_all_results();
	}
	function fetchreturnedComBook($user,$branch_teacher,$max_year,$max_quarter){
		$this->db->select('*');
		$this->db->from('communicationbook');
		
		$this->db->order_by('communicationbook.id','DESC');
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.quarter',$max_quarter);
		$this->db->where('communicationbook.comcommented','1');
		$this->db->where('communicationbook.approvecom','0');
		$this->db->where('communicationbook.combranch',$branch_teacher);
		$this->db->where('communicationbook.byteacher',$user);
		
		return $this->db->count_all_results();
	}
	function checkAutoMarkLock($max_year,$max_quarter){
		$querychkAutoLock=$this->db->query("select * from lockmarkauto where academicyear='$max_year' and autolockstatus='1' ");
        if($querychkAutoLock->num_rows()>0){
        	$query2 = $this->db->query("select endate from quarter where Academic_Year='$max_year' and term='$max_quarter' ");
        	$row2 = $query2->row();
        	$date2=$row2->endate;
            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
            $endDate1= $changeDate2->format('Y-m-d');

            $today2=Date('Y-m-d');
            if($endDate1>=$today2){
                return true;
            }else{
                return false;
            }
        }else{
        	return false;
        }
	}
	function commentedTeacher($lessonID,$max_year){
		$this->db->where('id',$lessonID);
		$this->db->where('academicyear',$max_year);
		$query=$this->db->get('communicationbook');
		$output='';
		foreach($query->result() as $teacherName){
			$output.='<button class="btn btn-default commentedTeacherName" value="'.$lessonID.'">Comment for '.$teacherName->byteacher.'</button>';
		}
		return $output;
	}
	function fetchReturnedCommunicationBook($user,$max_year){
		$this->db->select('users.fname,users.mname,users.lname,users.username,communicationbook.id,communicationbook.quarter,communicationbook.byteacher,communicationbook.comsubject,communicationbook.comnote,communicationbook.status,communicationbook.stuid,communicationbook.comgrade,communicationbook.comcommented, commbookcomment.comcomment, users.profile,communicationbook.datecreated,commbookcomment.commentby');
		$this->db->from('communicationbook');
		$this->db->join('commbookcomment',
		'commbookcomment.comid=communicationbook.id');
		$this->db->join('users',
		'users.username=commbookcomment.commentby');

		$this->db->where('byteacher',$user);
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('comcommented','1');
		$this->db->where('approvecom','0');
		$this->db->group_by('communicationbook.id');
		$this->db->order_by('communicationbook.id','DESC');
		$query=$this->db->get();
		$no=1;
		$output='';
		if($query->num_rows()>0){
			$output='<div class="row">';
			foreach($query->result() as $returnedBook){
				$output.=' <div class="col-12 col-lg-12 col-md-12">
                    <article class="article article-style-c">
                      <div class="article-details"><h5>Grade: '.$returnedBook->comgrade.' Subject: '.$returnedBook->comsubject.'</h5>
                        <div class="card article-category"> 
                            <div class="card-header"> <h4> '.$returnedBook->comnote.'                               
                                <button class="btn btn-outline-success editReturnedComBookNow" name="editReturnedComBook" type="submit" id="'.$returnedBook->id.'">
                                  <a href="#" id="'.$returnedBook->id.'" value="'.$returnedBook->id.'" data-toggle="modal" data-target="#editReturnedComBook"> <i class="fas fa-pen"></i> Edit</a>
                                </button> 
                                 <button class="btn btn-outline-warning viewReturnedComBook" name="viewReturnedComBook" type="submit" id="'.$returnedBook->id.'">
                                  <a href="#" class="" value="'.$returnedBook->id.'" data-toggle="modal" data-target="#viewReturnedComBook"><i class="fas fa-eye"></i> View</a>
                                </button></h4>
                            </div>
                        </div>
                        <div class="row">
                        <div class="activities">
		                  <div class="activity">
		                    <div class="activity-icon bg-primary text-white">
		                      '.$returnedBook->commentby.'
		                    </div>
		                    <div class="activity-detail">
		                      <p>'.$returnedBook->comcomment.' "<a href="#">'.$returnedBook->fname.' '.$returnedBook->mname.'</a>".</p>
		                    </div>
		                  </div>
		                </div>
                        <div class="article-user">
                          <div class="article-user-details">
                            <small class="text-muted pull-right"><i class="fas fa-clock"></i> '.$returnedBook->datecreated.'</small>
                          </div>
                        </div>
                      </div>
                    </article>
                  </div> ';
				$no++;
			}
			$output.='</div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No returned communication book found.
            </div></div>';
		}
		return $output;
	}
	function editReturnedCommmbookDetail($comId,$max_year){
		$this->db->where('id',$comId);
		$query=$this->db->get('communicationbook');
		$output='';
		foreach($query->result() as $editQuery){
			$output.='<textarea name="editReturnedCommBookSave" class="form-control summernote-simple editReturnedCommBookSave">'.$editQuery->comnote.' </textarea>';
		}
		$output.='<button type="submit" id="'.$comId.'" value="'.$comId.'" class="btn btn-primary saveReturnedComBook">Submit Changes</button>';
		return $output;
	}
	function viewReturnedComBook($user,$comId,$max_year){
		$this->db->select('users.fname,users.mname,users.lname,users.username,communicationbook.id,communicationbook.quarter,communicationbook.byteacher,communicationbook.comsubject,communicationbook.comnote,communicationbook.status,communicationbook.stuid,communicationbook.comgrade,communicationbook.comcommented,commbookcomment.comcomment,users.profile,communicationbook.datecreated');
		$this->db->from('communicationbook');
		$this->db->join('commbookcomment',
		'commbookcomment.comid=communicationbook.id');
		$this->db->join('users',
		'users.username=commbookcomment.commentby');

		$this->db->where('byteacher',$user);
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.id',$comId);
		$this->db->group_by('communicationbook.id');
		$this->db->order_by('communicationbook.id','DESC');
		$query=$this->db->get();
		$no=1;
		$output='';
		if($query->num_rows()>0){
			$output='<div class="row">';
			foreach($query->result() as $returnedBook){
				$output.='<div class="col-12 col-lg-12 col-md-12">
                    <article class="article article-style-c">
                      <div class="article-details">
                        <div class="article-category">  '.$returnedBook->comnote.'
                            <small class="text-muted">
                              
                            </small>
                        </div>
                        <div class="row">
                        <div class="col-1 col-lg-1 col-md-1"></div>
	                        <div class="col-11 col-lg-11 col-md-11">
		                        <div class="StudentViewTextInfo"> <a href="#">'.$returnedBook->fname.' '.$returnedBook->mname.'</a>  '.$returnedBook->comcomment.'
		                        </div>
	                        </div>
                        </div>
                        <div class="article-user">
                          <div class="article-user-details">
                            Grade: '.$returnedBook->comgrade.' Subject: '.$returnedBook->comsubject.'
                            <small class="text-muted pull-right"> '.$returnedBook->datecreated.'</small>
                          </div>
                        </div>
                      </div>
                    </article>
                  </div> ';
				$no++;
			}
			$output.='</div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No returned communication book found.
            </div></div>';
		}
		return $output;
	}
	function fetch_usertype_users($usertype){
		$this->db->order_by('fname,mname,lname','ASC');
		$this->db->where('usertype',$usertype);
		$query=$this->db->get('users');
		$output ='<input type="checkbox" id="selectall" onClick="selectAllCom()"> 
		Select All</br>';
			foreach ($query->result() as $row) { 
				$output .='<input type="checkbox" name="username[ ]" id="stuNameComBook[ ]"
				value="'.$row->username.'"> '.$row->fname.' '.$row->mname.'<br>';
			}
			return $output;
	}
	function fetchGradesofUserStudent($usertype){
		$this->db->order_by('gradesec','ASC');
		$this->db->where('usertype',$usertype);
		$this->db->where('grade!=','');
		$this->db->group_by('grade');
		$query=$this->db->get('users');
		$output ='<option> </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->grade.'">'.$row->grade.'</option>';
			}
			return $output;
	}
	function fetch_school(){
		$query=$this->db->get('school');
		return $query->result();
	}
	function fetch_mygradesec($user,$max_year,$branch){
		$this->db->order_by('gradesec','ASC');
		$this->db->group_by('gradesec');
		$this->db->where(array('staff'=>$user));
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('directorplacement.academicyear'=>$max_year));
		$this->db->where('usertype','Student');
		$this->db->select('*');
        $this->db->from('directorplacement');
        $this->db->join('users', 
            'users.gradesec = directorplacement.grade');
        $query = $this->db->get();
        return $query->result();
	}
	function add_placement($staff,$subject,$checkbox,$academicyear){
		$this->db->where(array('staff'=>$staff));
		$this->db->where(array('grade'=>$checkbox));
		$this->db->where(array('academicyear'=>$academicyear));
		$this->db->where(array('subject'=>$subject));
		$query=$this->db->get('staffplacement');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}
	function FilterAssesmentQuarterChange($evaluation,$gradesec,$max_year,$branch,$quarter,$subject){
		$output='';
		$this->db->where(array('schoolassesment.saseval'=>$evaluation));
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where(array('schoolassesment.academicyear'=>$max_year));
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('schoolassesment.assorder','ASC');
		$this->db->group_by('schoolassesment.sasname');
		$this->db->select('*');
		$this->db->from('schoolassesment');
		$this->db->join('users',
		'users.grade = schoolassesment.sasgrade');
		$query=$this->db->get();
		$output .='<option> </option>';
		$queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$branch.$gradesec.$quarter.$max_year."' ");
      	if ($queryCheck->num_rows()>0 ){
			foreach ($query->result() as $row) {
				$ismandatory=$row->ismandatory;
				$this->db->where('academicyear',$max_year);
				$this->db->where('subname',$subject);
				$this->db->where('quarter',$quarter);
				$this->db->where('markname',$row->sasname);
				$this->db->where('mbranch',$branch);
				$this->db->where('mgrade',$gradesec);
				$queryCheckMark=$this->db->get('mark'.$branch.$gradesec.$quarter.$max_year);

				if($queryCheckMark->num_rows()<1){
					$this->db->where('academicyear',$max_year);
					$this->db->where('autolockstatus','1');
					$queryCheckDate=$this->db->get('lockmarkauto');
					if($queryCheckDate->num_rows() > 0){
						$this->db->where('academicyear',$max_year);
						$this->db->where('sasname',$row->sasname);
						$queryQuarterEndDate=$this->db->get('schoolassesment');
						$endDateRow=$queryQuarterEndDate->row_array();
			        	$endDateName=$endDateRow['dateend'];
			        	if($endDateName>=$dateToday){
			        		if($ismandatory==1){
			        			$output .='<option value="'.$row->sasname.'" class="text-danger">'.$row->sasname.'(Mandatory)</option>';
			        		}else{
			        			$output .='<option value="'.$row->sasname.'">'.$row->sasname.'</option>';
			        		}
			        	}
					}else{
						if($ismandatory==1){
	      					$output .='<option value="'.$row->sasname.'" class="text-danger">'.$row->sasname.'(Mandatory)</option>';
			      		}else{
			      			$output .='<option value="'.$row->sasname.'">'.$row->sasname.'</option>';
			      		}
					}
				} 
			}
		}else{
			$output .='<option>No Table</option>';
		}
		return $output;
	}
	function FilterAssesmentQuarterChange_filter_assement($evaluation,$gradesec,$max_year,$branch,$quarter,$subject){
		$output='';
		$this->db->where(array('schoolassesment.saseval'=>$evaluation));
		$this->db->where(array('schoolassesment.assesment_branch'=>$branch));
		$this->db->where(array('schoolassesment.assesment_subject'=>$subject));
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where(array('schoolassesment.academicyear'=>$max_year));
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('schoolassesment.assorder','ASC');
		$this->db->group_by('schoolassesment.sasname');
		$this->db->select('*');
		$this->db->from('schoolassesment');
		$this->db->join('users',
		'users.grade = schoolassesment.sasgrade');
		$query=$this->db->get();
		$output .='<option> </option>';
		$queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$branch.$gradesec.$quarter.$max_year."' ");
      	if ($queryCheck->num_rows()>0 ){
			foreach ($query->result() as $row) {
				$ismandatory=$row->ismandatory;
				$this->db->where('academicyear',$max_year);
				$this->db->where('subname',$subject);
				$this->db->where('quarter',$quarter);
				$this->db->where('markname',$row->sasname);
				$this->db->where('mbranch',$branch);
				$this->db->where('mgrade',$gradesec);
				$queryCheckMark=$this->db->get('mark'.$branch.$gradesec.$quarter.$max_year);
				if($queryCheckMark->num_rows()<1){
					$this->db->where('academicyear',$max_year);
					$this->db->where('autolockstatus','1');
					$queryCheckDate=$this->db->get('lockmarkauto');
					if($queryCheckDate->num_rows() > 0){
						$this->db->where('academicyear',$max_year);
						$this->db->where('sasname',$row->sasname);
						$queryQuarterEndDate=$this->db->get('schoolassesment');
						$endDateRow=$queryQuarterEndDate->row_array();
	        			$endDateName=$endDateRow['dateend'];
			        	if($endDateName>=$dateToday){
			        		if($ismandatory==1){
			        			$output .='<option value="'.$row->sasname.'" class="text-danger">'.$row->sasname.'(Mandatory)</option>';
			        		}else{
			        			$output .='<option value="'.$row->sasname.'">'.$row->sasname.'</option>';
			        		}
			        	}
					}else{
						if($ismandatory==1){
	      					$output .='<option value="'.$row->sasname.'" class="text-danger">'.$row->sasname.'(Mandatory)</option>';
			      		}else{
			      			$output .='<option value="'.$row->sasname.'">'.$row->sasname.'</option>';
			      		}
					}
				} 
			}
		}else{
			$output .='<option>No Table</option>';
		}
		return $output;
	}
	function checkSubject($subject,$grade,$max_year){
		$this->db->where(array('Subj_name'=>$subject));
		$this->db->where(array('gradesec'=>$grade));
		$this->db->where(array('Academic_Year'=>$max_year));
		$this->db->where('usertype','Student');
		$this->db->select('*');
        $this->db->from('subject');
        $this->db->join('users', 
            'users.grade = subject.Grade');
        $query = $this->db->get();
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	function fetchMyStaffsForPlacement($branch){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('usertype','Teacher');
		$this->db->or_like('usertype','Director');
		$this->db->order_by('fname,mname,lname','ASC');
		$query=$this->db->get('users');
		return $query->result();
	}
	function delete_placement($id,$max_year){
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('staff'=>$id));
		$this->db->delete('staffplacement');
	}
	function fetch_subject_toplace($user,$max_year,$branch){
		$this->db->where('Academic_Year',$max_year);
		$this->db->where(array('staff'=>$user));
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->where(array('directorplacement.academicyear'=>$max_year));
		$this->db->order_by('Subj_name','ASC');
		$this->db->group_by('Subj_name');
		$this->db->select('*');
        $this->db->from('subject');
        $this->db->join('users', 
            'users.grade = subject.Grade');
        $this->db->join('directorplacement', 
            'directorplacement.grade = users.gradesec');
        $query = $this->db->get();
        return $query->result();
	}
	function fetch_mystaff_placement($max_year,$branch){
		$query=$this->db->query("SELECT us.fname,us.mname,st.staff,st.date_created, GROUP_CONCAT(st.grade) as gradess, GROUP_CONCAT(st.subject) as subjects from staffplacement as st inner join users as us inner join directorplacement as dp where dp.grade=st.grade and st.staff=us.username and st.academicyear ='$max_year' and us.branch='$branch' GROUP BY st.staff ORDER BY st.staff ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="">
			<table class="table table-borderedr table-hover">
        <thead>
          <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Subject</th>
            <th>Grade</th>
          </tr>
        </thead>
      <tbody>';
      $no=1;
	    foreach ($query->result() as $staffplacements) {
				$staff=$staffplacements->staff;
				$queryCount=$this->db->query("SELECT * from staffplacement where staff='$staff' and academicyear ='$max_year' ");
				$countRow=$queryCount->num_rows() + 1;
				$output.='<tr>
            <td rowspan="'.$countRow.'">'. $no.'.</td>
            <td rowspan="'.$countRow.'">'.$staffplacements->fname.' '.$staffplacements->mname.'
            <a href="#" id="delete_staffAllplacemet" class="" name="" value="'.$staffplacements->staff.'" ><button class="btn btn-default"><span class="text-danger"> Delete All</span></button></a>
            </td>';
            foreach ($queryCount->result() as $queryCounts) {
            	$output.='<tr class="delete_staffplacement'.$queryCounts->subject.''.$queryCounts->grade.'"><td> <a href="#" id="delete_staffplacemet" class="'.$queryCounts->staff.'" name="'.$queryCounts->grade.'" value="'.$queryCounts->subject.'" ><button class="btn btn-default"><span class="text-danger">Delete</span></button> </a>'.$queryCounts->subject.' </td>
	            <td>'.$queryCounts->grade.' (<small class="time">'.$staffplacements->date_created.'</small>) </td></tr>';
            }
            $output.='</tr>';
            $no++; 
	        }
		  $output.='</tbody> </table></div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> No staff placement found.
            </div></div>';
		}
		return $output;
	}
	function delete_Customplacement($staffGrade,$staffSubject,$staffName){
		$this->db->where(array('grade'=>$staffGrade));
		$this->db->where(array('subject'=>$staffSubject));
		$this->db->where(array('staff'=>$staffName));
		$this->db->delete('staffplacement');
	}
	function approveMark($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year){
		$output='';
		$query=$this->db->query("select id,username,fname,mname,lname from users where branch='$gs_branches' and academicyear='$max_year' and gradesec='$gs_gradesec' and status='Active' and usertype='Student' and isapproved='1' and branch='$gs_branches' order by fname,mname ASC ");
		$markname_query=$this->db->query("select ma.evaid, ma.markname, ma.mid,ma.value,ma.approved, ma.outof,sum(outof) as total_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` as ma where ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$gs_quarter' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' and approved='0' group by ma.markname order by ma.mid ASC ");
		if($markname_query->num_rows()>0)
		{   
			$output.='<h6><small>Grade :</small>'.
			$gs_gradesec.' <small>Quarter :</small>'.
			$gs_quarter.'<small> Subject :</small> '.
			$gs_subject.'</h6>';			
			$output.='<div class="table-responsive">
    		<table class="table table-borderedr table-hover" style="width:100%;">
    		<tr><th rowspan="2" class="text-center">No.</th>  <th rowspan="2" class="text-center">Student Name</th> 
        	<th rowspan="2" class="text-center">Student ID</th>';
        	foreach ($markname_query->result_array() as $mark_name) {
        		if($mark_name['approved']=='0'){
        		$output.='<th class="text-center">' .$mark_name['markname'].'
					<a href="#" value="'.$mark_name['markname'].'" class="gs_delete_markname"> 
					<button class=" btn btn-info"> <i class="fas fa-check-circle"></i> Approve </button>
					</a> </th>';
				}else{
					$output.='<th class="text-center">' .$mark_name['markname'].'</th>';
				}
        	}
        	$output.=' </tr><tr>';
			foreach ($markname_query->result_array() as $mark_name) 
			{
        		$output.='<td class="text-center"><small>'.$mark_name['outof'].'</small></td>';
        	}
        	$output.='</tr>';
        	$output.='<input type="hidden" class="jo_gradesec" value="'.$gs_gradesec.'">
			<input type="hidden" class="jo_subject" value="'.$gs_subject.'">
			<input type="hidden" class="jo_quarter" value="'.$gs_quarter.'">
			<input type="hidden" class="jo_branch" value="'.$gs_branches.'">
			<input type="hidden" class="jo_year" value="'.$max_year.'">';
			$no=1;
			foreach ($query->result_array() as $row) 
			{ 
        		$id=$row['id'];

        		$output.='<tr><td>'.$no.'</td> <td> '.$row['fname'].' '.$row['mname'].' '.$row['lname'].'</td>
        		<td>'.$row['username'].'</td>';
        		foreach ($markname_query->result_array() as $mark_name)
        		{
        			$Evaid=$mark_name['evaid'];
        			$outOFF=$mark_name['outof'];
        			$markname=$mark_name['markname'];
        			$query_value = $this->db->query("select lockmark,value,outof,mid, markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where markname='$markname' and stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' ");
        			if($query_value->num_rows()>0)
        			{
        				$mark_value=$query_value->row_array();
    					$outof=$mark_value['outof'];
    					$value=$mark_value['value'];
    					$lockmark=$mark_value['lockmark'];
						if($outof >= $value){
							$output.='<td class="text-center jossMark'.$mark_value['mid'].'">'.$mark_value['value'].'';
							$output.='</td>';
						}else{
							$output.='<td><span class="text-danger"> NG
								<div class="table-links"> 
								<a href="#" value="'.$mark_value['mid'].'" class="edit_mark_gs" data-toggle="modal" 
								data-target="#editmark"><span class="text-info"> Edit 
								</span></a>
							</div>
							</span></td>';
						}
        				
        			}else{
						$output.='<td class="JoMark'.$id.'">
						<input type="hidden" value="" class="my_ID">
						<span class="text-danger"> NG</span>
						<div class="table-links"> 
							<a href="#" title="'.$id.'" id="'.$outOFF.'" name="'.$markname.'" value="'.$Evaid.'" class="edit_NGmark_gs" data-toggle="modal" 
							data-target="#editngmark"><span class="text-info"> 
							<i class="fas fa-plus"></i> 
							</span></a>
						</div>
						</td>';
					}
        		}$no++;
			}
			$output.='</tr></table></div>';
		}else{
    		$output.='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> No new result found.
            </div></div>';
		}
		return $output;
	}
	function editMarkName($branch,$gradesec,$subject,$quarter,$year,$markname){
       	$arrayMark = array('mgrade' => $gradesec, 'mbranch' => $branch,'subname'=>$subject,'academicyear'=>$year,'quarter'=>$quarter, 'markname'=>$markname,'lockmark'=>'0');
      	$this->db->where($arrayMark); 
      	$this->db->group_by('markname');
      	$this->db->distinct();
      	$query = $this->db->get('mark'.$branch.$gradesec.$quarter.$year);
      	return $query->result();
	}
	function fetch_evaluation4markName($quarter,$gradesec,$max_year){
		$this->db->where(array('evaluation.quarter'=>$quarter));
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where(array('evaluation.academicyear'=>$max_year));
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->group_by('evaluation.evname');
		$this->db->select('*');
		$this->db->from('evaluation');
		$this->db->join('users',
		'users.grade = evaluation.grade');
		$query=$this->db->get();
		return $query->result();
	}
	function fetch_grade_mark_4director($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year){
		$output='';
		$query=$this->db->query("select id,username,fname,mname,lname from users where branch='$gs_branches' and academicyear='$max_year' and gradesec='$gs_gradesec' and status='Active' and usertype='Student' and isapproved='1' and branch='$gs_branches' order by fname,mname ASC ");
		$markname_query=$this->db->query("select ma.lockmark,ma.evaid, ma.markname, ma.mid,ma.value,ma.approved, ma.outof,sum(outof) as total_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` as ma where ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$gs_quarter' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' group by ma.markname order by ma.mid ASC ");
		if($markname_query->num_rows()>0)
		{   
			$output.='<h6><small>Grade :</small>'.
			$gs_gradesec.' <small>Quarter :</small>'.
			$gs_quarter.'<small> Subject :</small> '.
			$gs_subject.'</h6>';
			$uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='addstudentmark' order by id ASC ");
			if($uaddMark->num_rows() >0){
				$output.='<button class="btn btn-outline-danger pull-right delete_selected"><i class="fas fa-trash"></i> Delete '.$gs_subject.'</button>';	
			}
			$uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='unlockstudentmark' order by id ASC "); 
      if($uperStuDE->num_rows() >0){
				$output.='<button class="btn btn-outline-warning pull-right unlock_selected"><i class="fas fa-unlock"></i> Unlock '.$gs_subject.'</button>';	
			}
			$uperStuD=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='lockstudentmark' order by id ASC ");  
      if($uperStuD->num_rows() >0){
				$output.='<button class="btn btn-outline-success pull-right lock_selected"><i class="fas fa-lock"></i> Lock '.$gs_subject.'</button>';
			}	
			$output.='<div class="table-responsive">
    		<table class="table table-bordered" style="width:100%;">
    		<tr><th rowspan="2" class="text-center">No.</th>  
    		<th rowspan="2" class="text-center">Student Name</th> 
        	<th rowspan="2" class="text-center">Student ID</th>';
        	foreach ($markname_query->result_array() as $mark_name) {
        		if($mark_name['lockmark']=='0'){
        			$output.='<th class="text-center coreMarkName'.$mark_name['markname'].'">' .$mark_name['markname'].'
							<a href="#" value="'.$mark_name['markname'].'" 
							class="gs_delete_markname"> 
							<span class="text-danger"> 
							<small> <i class="fas fa-trash"></i> </small> </span>
							</a>
					     </th>';
						}else{
							$output.='<th class="text-center text-warning">' .$mark_name['markname'].' <i class="fas fa-lock"> </i> </th>';
						}
        	}
        	$output.=' </tr><tr>';
			foreach ($markname_query->result_array() as $mark_name) 
			{
        		//$output.='<td class="text-center"><small>'.$mark_name['outof'].'</small></td>';
        		if($mark_name['lockmark']=='0'){
	        		$output.='<td class="text-center coreOutOF'.$mark_name['outof'].$mark_name['markname'].'">'.$mark_name['outof'].'
	        		
						<a href="#" id="'.$mark_name['outof'].'" value="'.$mark_name['markname'].'" 
						class="gs_edit_outof" data-toggle="modal" 
									data-target="#editOutOf"> 
						<span class="text-success">
						<small> <i class="far fa-edit"></i> </small> </span>
						</a>
				    </td>';
				}else{
					$output.='<th class="text-center text-warning">' .$mark_name['outof'].' <i class="fas fa-lock"> </i></th>';
				}
        	}
        	$output.='</tr>';
        	$output.='<input type="hidden" class="jo_gradesec" value="'.$gs_gradesec.'">
			<input type="hidden" class="jo_subject" value="'.$gs_subject.'">
			<input type="hidden" class="jo_quarter" value="'.$gs_quarter.'">
			<input type="hidden" class="jo_branch" value="'.$gs_branches.'">
			<input type="hidden" class="jo_year" value="'.$max_year.'">';
			$no=1;
			foreach ($query->result_array() as $row) 
			{ 
        		$id=$row['id'];

        		$output.='<tr><td>'.$no.'</td> <td> '.$row['fname'].' '.$row['mname'].' '.$row['lname'].'</td>
        		<td>'.$row['username'].'</td>';
        		foreach ($markname_query->result_array() as $mark_name)
        		{
        			$Evaid=$mark_name['evaid'];
        			$outOFF=$mark_name['outof'];
        			$markname=$mark_name['markname'];
        			$lockmark1=$mark_name['lockmark'];
        			$query_value = $this->db->query("select lockmark,value,outof,mid, markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where markname='$markname' and stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' ");
        			if($query_value->num_rows()>0)
        			{
        				$mark_value=$query_value->row_array();
    					$outof=$mark_value['outof'];
    					$value=$mark_value['value'];
    					$lockmark=$mark_value['lockmark'];
						if($outof >= $value){
							$output.='<td class="text-center jossMark'.$mark_value['mid'].'">'.$mark_value['value'].'('.$markname.')';
							if($lockmark==='0'){
								$output.=' <a href="#" value="'.$mark_value['mid'].'" class="edit_mark_gs" data-toggle="modal"
								data-target="#editmark">
								<span class="text-success">
								<i class="far fa-edit"> </i></span></a>';
                         	}else{
                         		$output.='
                         			<span class="text-warning"><i class="fas fa-lock"> </i> </span>';
                         	}
							$output.='</td>';
						}else{
							$output.='<td><span class="text-danger"> NG ('.$markname.')
								<a href="#" value="'.$mark_value['mid'].'" class="edit_mark_gs" data-toggle="modal" 
								data-target="#editmark"><span class="text-info"> Edit 
								</span></a>
							</span></td>';
						}
        				
        			}else{
        				//if($lockmark1=='0'){
							$output.='<td class="text-center JoMark'.$id.$markname.'">
							<input type="hidden" value="" class="my_ID">
							<span class="text-danger"> NG ('.$markname.')</span>
								<a href="#" title="'.$id.'" id="'.$outOFF.'" name="'.$markname.'" value="'.$Evaid.'" class="edit_NGmark_gs" data-toggle="modal" 
								data-target="#editngmark"><span class="text-info"> 
								<i class="fas fa-plus"></i> 
								</span></a>
							</td>';
						/*}else{
							$output.='<td class="text-center JoMark'.$id.'">
							<input type="hidden" value="" class="my_ID">
							<span class="text-danger"> NG ('.$markname.')</span>
								<span class="text-warning"><i class="fas fa-lock"> </i> </span>
							</td>';
						}*/
					}
        		}$no++;
			}
			$output.='</tr></table></div>';
		}else{
    		$output.='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> Data not Found.
            </div></div>';
		}
		return $output;
	}
	function fetch_grade_mark_4teacher($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year){
		$output='';
		$query=$this->db->query("select id,username,fname,mname,lname from users where branch='$gs_branches' and academicyear='$max_year' and gradesec='$gs_gradesec' and status='Active' and usertype='Student' and isapproved='1' and branch='$gs_branches' order by fname,mname ASC ");
		$markname_query=$this->db->query("select ma.lockmark, ma.evaid, ma.markname, ma.mid,ma.value,ma.approved, ma.outof,sum(outof) as total_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` as ma where ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$gs_quarter' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' group by ma.markname order by ma.mid ASC ");
		if($markname_query->num_rows()>0)
		{   
			$output.='<h6><small>Grade :</small>'.
			$gs_gradesec.' <small>Quarter :</small>'.
			$gs_quarter.'<small> Subject :</small> '.
			$gs_subject.'</h6>';			
			$output.='<div class="table-responsive">
    		<table class="table table-bordered" style="width:100%;">
    		<tr><th rowspan="2" class="text-center">No.</th>  <th rowspan="2" class="text-center">Student Name</th> 
      	<th rowspan="2" class="text-center">Student ID</th>';
      	foreach ($markname_query->result_array() as $mark_name) {
      		if($mark_name['approved']=='0'){
	        	$output.='<th class="text-center">' .$mark_name['markname'].'
						<a href="#" value="'.$mark_name['markname'].'" 
						class="gs_delete_markname"> 
						<span class="text-danger">
						<small> <i class="fas fa-trash-alt"></i> </small> </span>
						</a> </th>';
					}else{
						$output.='<th class="text-center">' .$mark_name['markname'].' </th>';
					}
      	}
      	$output.=' </tr><tr>';
			foreach ($markname_query->result_array() as $mark_name) 
			{
        		$output.='<td class="text-center"><small>'.$mark_name['outof'].'</small></td>';
        	}
        	$output.='</tr>';
        	$output.='<input type="hidden" class="jo_gradesec" value="'.$gs_gradesec.'">
			<input type="hidden" class="jo_subject" value="'.$gs_subject.'">
			<input type="hidden" class="jo_quarter" value="'.$gs_quarter.'">
			<input type="hidden" class="jo_branch" value="'.$gs_branches.'">
			<input type="hidden" class="jo_year" value="'.$max_year.'">';
			$no=1;
			foreach ($query->result_array() as $row) 
			{ 
        		$id=$row['id'];

        		$output.='<tr><td>'.$no.'</td> <td> '.$row['fname'].' '.$row['mname'].' '.$row['lname'].'</td>
        		<td>'.$row['username'].'</td>';
        		foreach ($markname_query->result_array() as $mark_name)
        		{
        			$Evaid=$mark_name['evaid'];
        			$outOFF=$mark_name['outof'];
        			$markname=$mark_name['markname'];
        			$lockmark1=$mark_name['lockmark'];
        			$query_value = $this->db->query("select lockmark,value,outof,mid, markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where markname='$markname' and stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' ");
        			if($query_value->num_rows()>0)
        			{
        				$mark_value=$query_value->row_array();
    					$outof=$mark_value['outof'];
    					$value=$mark_value['value'];
    					$lockmark=$mark_value['lockmark'];
						if($outof >= $value){
							$output.='<td class="text-center jossMark'.$mark_value['mid'].'">'.$mark_value['value'].'('.$markname.')';
							if($lockmark==='0'){
								$output.=' <a href="#" value="'.$mark_value['mid'].'" class="edit_mark_gs" data-toggle="modal"
								data-target="#editmark">
								<span class="text-success">
								<i class="far fa-edit"> </i></span></a>';
                         	}else{
                         		$output.='
                         			<span class="text-warning"><i class="fas fa-lock"> </i> </span>';
                         	}
							$output.='</td>';
						}else{
							$output.='<td><span class="text-danger"> NG ('.$markname.')
								<a href="#" value="'.$mark_value['mid'].'" class="edit_mark_gs" data-toggle="modal" 
								data-target="#editmark"><span class="text-info"> Edit 
								</span></a>
							</span></td>';
						}
        				
        			}else{
        				//if($lockmark1=='0'){
							$output.='<td class="text-center JoMark'.$id.$markname.'">
							<input type="hidden" value="" class="my_ID">
							<span class="text-danger"> NG ('.$markname.')</span>
								<a href="#" title="'.$id.'" id="'.$outOFF.'" name="'.$markname.'" value="'.$Evaid.'" class="edit_NGmark_gs" data-toggle="modal" 
								data-target="#editngmark"><span class="text-info"> 
								<i class="fas fa-plus"></i> 
								</span></a>
							</td>';
						/*}else{
							$output.='<td class="text-center JoMark'.$id.'">
							<input type="hidden" value="" class="my_ID">
							<span class="text-danger"> NG ('.$markname.')</span>
								<span class="text-warning"><i class="fas fa-lock"> </i> </span>
							</td>';
						}*/
						/*$output.='<td class="JoMark'.$id.'">
						<input type="hidden" value="" class="my_ID">
						<span class="text-danger"> NG</span>
						<div class="table-links"> 
							<a href="#" title="'.$id.'" id="'.$outOFF.'" name="'.$markname.'" value="'.$Evaid.'" class="edit_NGmark_gs" data-toggle="modal" 
							data-target="#editngmark"><span class="text-info"> 
							<i class="fas fa-plus"></i> 
							</span></a>
						</div>
						</td>';*/
					}
        		}$no++;
			}
			$output.='</tr></table></div>';
		}else{
    		$output.='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> Data not Found.
            </div></div>';
		}
		return $output;
	}
	function select_edited_mark($edtimar,$quarter,$gradesec,$academicyear,$branch)
	{
		$this->db->where(array('mid'=>$edtimar));
		$query=$this->db->get('mark'.$branch.$gradesec.$quarter.$academicyear);
		$output='';
		foreach ($query->result() as $value) {
			$output .='<input type="hidden" class="mQuarter" value="'.$quarter.'">';
			$output .='<input type="hidden" class="aYear" value="'.$academicyear.'">';
			$output .='<input type="hidden" class="gsBranch" value="'.$branch.'">';
			$output .='<input type="hidden" class="gSec" value="'.$gradesec.'">';
			$output .='<input type="hidden" class="outof" value="'.$value->outof.'">';
			$output .='<input type="hidden" class="mid" value="'.$value->mid.'">';
			$output .='<div class="row">
			<div class="col-lg-4"><h5 class="card-title">'.$value->markname.'</h5></div><div class="col-lg-8"><input class="form-control correct_mark_gs" id="correct_value" type="text" value="'.$value->value.'"></div></div>
			<a class="info-mark"></a>';
		}
		return $output;
	}
	function update_edited_mark($user,$outof,$mid,$value,$quarter,$gradesec,$year,$branch,$max_quarter)
	{
		$output='';
		date_default_timezone_set('Africa/Addis_Ababa');
		$selectUpdatedMark=$this->db->query("select * from `mark".$branch.$gradesec.$quarter.$year."` where mid='$mid' ");
		$querRow=$selectUpdatedMark->row();
		$subject=$querRow->subname;
		$oldate=$querRow->value;
		$updateduser=$querRow->stuid;
		$markname=$querRow->markname;
		$data=array(
			'userinfo'=>$user,
			'useraction'=>'Mark updated',
			'infograde'=>$gradesec,
			'subject'=>$subject,
			'quarter'=>$quarter,
			'academicyear'=>$year,
			'oldata'=>$oldate,
			'newdata'=>$value,
			'markname'=>$markname,
			'updateduser'=>$updateduser,
			'userbranch'=>$branch,
			'actiondate'=> date('Y-m-d H:i:s', time())
		);
		if($quarter!==$max_quarter){
          $queryAlert=$this->db->insert('useralertactions',$data);
        }
		$queryInsert=$this->db->insert('useractions',$data);
		if($queryInsert){
			$this->db->where(array('stuid'=>$updateduser));
			$this->db->where(array('subname'=>$subject));
			$this->db->where(array('markname'=>$markname));
			$this->db->where(array('mbranch'=>$branch));
			$this->db->where(array('mgrade'=>$gradesec));
			$this->db->where(array('quarter'=>$quarter));
			/*$this->db->where(array('mid'=>$mid));*/
			$this->db->set('value',$value);
			$query=$this->db->update('mark'.$branch.$gradesec.$quarter.$year);
			if($query){
				$output .='<span class="text-success"> Updated</span>';
			}else{
				$output .='<span class="text-danger"> ooops</span>';
			}
		}
		return $output;
	}
	function FetchUpdatedMark($mid,$quarter,$gradesec,$year,$branch){
		$this->db->where('mid',$mid);
		$query=$this->db->get('mark'.$branch.$gradesec.$quarter.$year);
		$output='';
		foreach ($query->result() as $keyvalue) {
			$output.=''.$keyvalue->value.'';
		}
		return $output;
	}
	function select_edited_ngmark($subject,$stuid,$quarter,$max_year,$gradesec,$markname,$outof,$evaid,$branch){
		$query_student=$this->db->query("select * from users where id='$stuid' and academicyear='$max_year' and status='Active' and isapproved='1' and usertype='Student' ");
		$output='';
		$output.='<input type="hidden" class="my_subject" value="'.$subject.'"> ';
		$output.='<input type="hidden" class="my_quarter" value="'.$quarter.'"> ';
		$output.='<input type="hidden" class="my_year" value="'.$max_year.'"> ';
		$output.='<input type="hidden" class="my_gradeSec" value="'.$gradesec.'"> ';
		$output.='<input type="hidden" class="my_Branch" value="'.$branch.'"> ';
		$output.='<input type="hidden" class="my_student" value="'.$stuid.'"> ';
		$output.='<input type="hidden" class="my_outOf" value="'.$outof.'"> ';
		$output.='<input type="hidden" class="my_eva" value="'.$evaid.'"> ';
		foreach ($query_student->result() as $kUsers) {
			$grade=$kUsers->grade;
			$output.='<input type="hidden" class="my_studentBranch" value="'.$kUsers->branch.'"> ';
			$output.='<div class="row"> <div class="col-md-6">
			<div class="form-group">
			<input type="text" class="form-control my_markNameH" value="'.$markname.'" disabled
			="disabled">
			</div></div> 
			<div class="col-md-6"> <div class="form-group"> 
			<input class="form-control correct_ngmark_gs" required="required" placeholder="Value..." id="" type="text"> </div></div>
			 </div>';
			
		}$output.='<a class="info-ngmark"></a>';
		return $output;
	}
	function update_edited_ngmark($user,$data,$quarter,$gradesec,$year,$my_studentBranch,$subject,$value,$stuid,$markname,$max_quarter){
		$output='';
		date_default_timezone_set('Africa/Addis_Ababa');
		$data1=array(
			'userinfo'=>$user,
			'useraction'=>'Mark updated',
			'infograde'=>$gradesec,
			'subject'=>$subject,
			'quarter'=>$quarter,
			'academicyear'=>$year,
			'oldata'=>'-',
			'newdata'=>$value,
			'markname'=>$markname,
			'updateduser'=>$stuid,
			'userbranch'=>$my_studentBranch,
			'actiondate'=> date('Y-m-d H:i:s', time())
		);
		if($quarter!==$max_quarter){
          $queryAlert=$this->db->insert('useralertactions',$data1);
        }
		$queryInsert=$this->db->insert('useractions',$data1);
		if($queryInsert){
			$queryCheck=$this->db->query("select * from `mark".$my_studentBranch.$gradesec.$quarter.$year."` where stuid='$stuid' and subname='$subject' and quarter='$quarter' and markname='$markname' and academicyear='$year' ");
			if($queryCheck->num_rows()>0){
				$this->db->where('academicyear',$year);
				$this->db->where('stuid',$stuid);
				$this->db->where('subname',$subject);
				$this->db->where('quarter',$quarter);
				$this->db->where('markname',$markname);
				$this->db->set('value',$value);
				$query=$this->db->update('mark'.$my_studentBranch.$gradesec.$quarter.$year,$data);
			}else{
				$query=$this->db->insert('mark'.$my_studentBranch.$gradesec.$quarter.$year,$data);
			}
			
			if($query){
				$output .='<span class="text-success"> Updated</span>';
			}else{
				$output .='<span class="text-danger"> ooops</span>';
			}
		}
		return $output;
	}
	function save_thisgrade_exam($academicyear,$subject,$quarter,$assesname,$markGradeSec,$markGradeSecBranch){
		$this->db->where(array('mgrade'=>$markGradeSec));
		$this->db->where(array('subname'=>$subject));
		$this->db->where(array('quarter'=>$quarter));
		$this->db->where(array('markname'=>$assesname));
		$this->db->where(array('academicyear'=>$academicyear));
		$this->db->where(array('mbranch'=>$markGradeSecBranch));
		$querystu=$this->db->get('mark'.$markGradeSecBranch.$markGradeSec.$quarter.$academicyear);
		$output='';
		if($querystu->num_rows()>0){
			return false;
		}else{
			return true;
		}
	}
	function fetch_subject_from_staffplace($gradesec,$max_year,$user){
		$this->db->where('grade',$gradesec);
		$this->db->where('staff',$user);
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->order_by('grade','ASC');
		$this->db->group_by('subject');
		$query=$this->db->get('staffplacement');
		$output ='<option> </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->subject.'">'.$row->subject.'</option>';
			}
			return $output;
	}
	function fetch_subject_from_subjectmark($gradesec,$max_year){
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where(array('subject.Academic_Year'=>$max_year));
		$this->db->order_by('subject.Subj_name','ASC');
		$this->db->group_by('subject.Subj_name');
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->join('users',
		'users.grade = subject.Grade');
		$query=$this->db->get();
		$output ='<option value="All">All</option>';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->Subj_name.'">'.$row->Subj_name.'</option>';
		}
		return $output;
	}
	function fetch_subject_from_subject($gradesec,$max_year){
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where(array('subject.Academic_Year'=>$max_year));
		$this->db->order_by('subject.Subj_name','ASC');
		$this->db->group_by('subject.Subj_name');
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->join('users',
		'users.grade = subject.Grade');
		$query=$this->db->get();
		$output ='';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->Subj_name.'">'.$row->Subj_name.'</option>';
		}
		return $output;
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
	function fetch_term($max_year){
		$this->db->where('Academic_year',$max_year);
		$this->db->group_by('term');
		$this->db->order_by('term','ASC');
		$query=$this->db->get('quarter');
		return $query->result();
	}
	function fetch_grade_teachermarkresult($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year)
	{
		$output='';
		if($gs_subject===trim('All')){
			$this->db->where('ma.academicyear',$max_year);
			$this->db->where('ma.quarter',$gs_quarter);
			$this->db->where('ma.mgrade',$gs_gradesec);
			$this->db->where('ma.mbranch',$gs_branches);
			$this->db->group_by('ma.subname');
			$this->db->order_by('ma.subname','ASC');
	        $queryFetchMark = $this->db->get('mark'.$gs_branches.$gs_gradesec.$gs_quarter.$max_year.' as ma');
			if($queryFetchMark->num_rows()>0){
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
				
        		foreach ($queryFetchMark->result_array() as $mark_name) {
        			$subject=$mark_name['subname'];
					$output.='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp; Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp; Subject :<B><u>'.$subject.'</u></B></div></br></h6>';
					
					$evalname_query=$this->db->query("select us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

					$output.='<div class="table-responsive">
	        		<table class="tabler table-borderedr table-hover" style="width:100%;height:92%;page-break-inside:avoid;">
	        		<tr>
	        		<th rowspan="3">No.</th>
	            	<th rowspan="3">Student Name</th>
	            	<th rowspan="3" class="text-center">Student ID</th>';
        			foreach ($evalname_query->result_array() as $evalua_name) 
        			{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
	            		$colSpan=$queryMvalue->num_rows() +2;
	            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
	            	}
	            	$output.='<th class="text-center"><B>Total</B></th><th rowspan="3" class="text-center">Sig.</th><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
		            	foreach ($queryMvalue->result() as $mark_name)
		            	{
		            		$output.='<td class="text-center">'.$mark_name->markname.'</td>';
		            	}
		            	$output.='<td class="text-center"><b>Tot</b></td>';
		            	$output.='<td class="text-center"><b>Conv</b></td>';
		            }
		            $output.='<td rowspan="2" class="text-center"> <B>100</B> </td>';
	            	$output.='</tr><tr>';
	            	$sumOutOf=0;
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		
	            		$mname_gs=$evalua_name['eid'];
	            		$percent=$evalua_name['percent'];
	            		$grade=$evalua_name['grade'];
	            		$evName=$evalua_name['evname'];
		            	$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
            			$sumOutOf=0;
	            		foreach ($queryMvalue->result_array() as $mark_name) {
	            			$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            			$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            		}
	        			$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        			$queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$subject' and customasses='$evName' and customquarter='$gs_quarter' ");
		            	if($queryCheckPercentage->num_rows()>0){
		            		$rowPercent=$queryCheckPercentage->row();
		            		$customPercent=$rowPercent->custompercent;
		            		$output.='<td style="text-align:center;"><B>'.$customPercent.'</B></td>';
		            	}else{
		            		$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
		            	}
		            }
	            	$output.='</tr>';
	            	$stuNO=1;
	            	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname,u.lname ASC ");
				foreach ($queryStudent->result_array() as $row) { 
            		$id=$row['id'];
            		$output.='<tr><td>'.$stuNO.'.</td> <td>'.$row['fname'].' '.$row['mname'].' '.$row['lname']. '</td>';
            		$output.='<td class="text-center">'.$row['username'].' </td>';
            		$average=0;
            		foreach ($evalname_query->result_array() as $mark_name)
            		{
            			
            			$evName=$mark_name['evname'];
            			$grade=$mark_name['grade'];
            			$mname_gs=$mark_name['eid'];
            			$queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$subject' and customasses='$evName' and customquarter='$gs_quarter' ");
		            	if($queryCheckPercentage->num_rows()>0){
		            		$rowPercent=$queryCheckPercentage->row();
		            		$percent=$rowPercent->custompercent;
		            	}else{
		            		$percent= $mark_name['percent'];
		            	}

	            		$query_value = $this->db->query("select markname,sum(value) as total from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where  subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where stuid='$id' and subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
								
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
				            	$sumOutOf=0;
					            foreach ($queryMvalue->result_array() as $mark_name) {
					            	$sumOutOf=$mark_name['outof'] + $sumOutOf;	
					            }
		            		}
		            		if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='')
	            			{
	                    		$conver= ($totalMark *$percent )/$sumOutOf;
	                    		if($totalMark>0){
	                    			$output.='<td style="text-align:center;">'.$totalMark.'</td>';
	                    			$output.='<td style="text-align:center;"><B>'.number_format((float)$conver,2,'.','').'</B></td>';
	                    		}else{
	                    			$output.='<td class="text-danger" style="text-align:center;">NG</td>';
	                    			$output.='<td class="text-danger" style="text-align:center;">NG</td>';
	                    		}
	            					$average =$conver + $average;
	                		}else{
	                			$output.='<td style="text-align:center;">-</td>';
	                			$output.='<td style="text-align:center;">-</td>';
	                		}
		            	}else{
		            		$output.='<td style="text-align:center;">-</td>';
		            		$output.='<td style="text-align:center;">-</td>';
		            	}
	            	}
	            	$output.='<td style="text-align:center;"><B>'.number_format((float)$average,2,'.','').'</B></td>';
            		$average=0;
            		$output.='<td style="text-align:center;"></td>';
					$stuNO++;
				}

					$output.='</table></div>';
					$output.='<p class="text-center">'.$school_slogan.'!</p>';
        		}
			}else{
				$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                	<i class="fas fa-check-circle"> </i> Record not found.
            	</div></div>';
			}
		}else{
			$querySingleSubject=$this->db->query("select * from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' ");
			if($querySingleSubject->num_rows()>0)
			{
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];

				$output.='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';

				$evalname_query=$this->db->query("select us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

				$output.='<div class="table-responsive">
	    		<table class="tabler table-borderedr table-hover" style="width:100%;height:85%">
	    		<thead>
	    		<tr>
	    		<th rowspan="3">No.</th>
	        	<th rowspan="3">Student Name</th>
	        	<th rowspan="3" class="text-center">Student ID</th>';
	        	foreach ($evalname_query->result_array() as $evalua_name) {
	        		$mname_gs=$evalua_name['eid'];
	        		$queryMvalue = $this->db->query("select markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
	        		$colSpan=$queryMvalue->num_rows() +2;
	        		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
	        	}
	        	$output.='<th class="text-center">Total</th><th rowspan="3" class="text-center">Sig.</th><tr>';
	        	foreach ($evalname_query->result_array() as $evalua_name) {
	        		$mname_gs=$evalua_name['eid'];
	        		$queryMvalue = $this->db->query("select markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$output.='<td class="text-center">'.$mark_name['markname'].'</td>';
	            	}
	            	$output.='<td class="text-center"><b>Tot</b></td>';
	            	$output.='<td class="text-center"><b>Conv</b></td>';
	            }
	            $output.='<td rowspan="2" class="text-center"><B>100</B></td>';
	        	$output.='</tr><tr>';
	        	foreach ($evalname_query->result_array() as $evalua_name) {
	        			$mname_gs=$evalua_name['eid'];
            		$percent=$evalua_name['percent'];
            		$evName=$evalua_name['evname'];
            		$grade=$evalua_name['grade'];

	        		$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
	        		$sumOutOf=0;
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            		$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            	}
	        		$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        		$queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$gs_subject' and customasses='$evName' and customquarter='$gs_quarter' ");
	            	if($queryCheckPercentage->num_rows()>0){
	            		$rowPercent=$queryCheckPercentage->row();
	            		$customPercent=$rowPercent->custompercent;
	            		$output.='<td style="text-align:center;"><B>'.$customPercent.'</B></td>';
	            	}else{
	            		$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
	            	}
	            }
	        	$output.='</tr>';
	        	$stuNO=1;
	        	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname ASC ");
				foreach ($queryStudent->result_array() as $row) { 
	        		$id=$row['id'];
	        		$output.='<tr><td>'.$stuNO.'.</td> <td>'.$row['fname'].' '.$row['mname'].' '.$row['lname']. '</td>';
	        		$output.='<td class="text-center">'.$row['username'].' </td>';
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

	            		$query_value = $this->db->query("select markname,sum(value) as total from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where  subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
								
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
				            		$sumOutOf=0;
					            foreach ($queryMvalue->result_array() as $mark_name) {
					            	$sumOutOf=$mark_name['outof'] + $sumOutOf;	
					            }
		            		}
		            		if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='')
	            			{
	                    		$conver= ($totalMark *$percent )/$sumOutOf;
	                    		if($totalMark>0){
	                    			$output.='<td style="text-align:center;">'.$totalMark.'</td>';
	                    			$output.='<td style="text-align:center;"><B>'.number_format((float)$conver,2,'.','').'</B></td>';
	                    		}else{
	                    			$output.='<td class="text-danger" style="text-align:center;">NG</td>';
	                    			$output.='<td class="text-danger" style="text-align:center;">NG</td>';
	                    		}
	            					$average =$conver + $average;
	                		}else{
	                			$output.='<td style="text-align:center;">-</td>';
	                			$output.='<td style="text-align:center;">-</td>';
	                		}
		            	}else{
		            		$output.='<td style="text-align:center;">-</td>';
		            		$output.='<td style="text-align:center;">-</td>';
		            	}
		            	
	            	}
	            	$output.='<td style="text-align:center;"><B>'.number_format((float)$average,2,'.','').'</B></td>';
	        		$average=0;
	        		$output.='<td style="text-align:center;"></td>';
					$stuNO++;
				}
				$output.='</table></div>';
				$output.='<p class="text-center">'.$school_slogan.'!</p>';
			}else{
	    		$output.='<div class="alert alert-warning alert-dismissible show fade">
	            <div class="alert-body">
	                <button class="close"  data-dismiss="alert">
	                    <span>&times;</span>
	                </button>
	            	<i class="fas fa-check-circle"> </i> Data not found.
	        	</div></div>';
			}
		}
		return $output;
	}
	function fetch_grade_teachermarkresultApproved($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year)
	{
		$output='';
		if($gs_subject===trim('All')){
			$this->db->where('ma.academicyear',$max_year);
			$this->db->where('ma.quarter',$gs_quarter);
			$this->db->where('ma.mgrade',$gs_gradesec);
			$this->db->where('ma.mbranch',$gs_branches);
			$this->db->group_by('ma.subname');
			$this->db->order_by('ma.subname','ASC');
	        $queryFetchMark = $this->db->get('mark'.$gs_branches.$gs_gradesec.$gs_quarter.$max_year.' as ma');
			if($queryFetchMark->num_rows()>0){
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
				
        		foreach ($queryFetchMark->result_array() as $mark_name) {
        			$subject=$mark_name['subname'];
					$output.='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp; Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp; Subject :<B><u>'.$subject.'</u></B></div></br></h6>';
					
					$evalname_query=$this->db->query("select us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

					$output.='<div class="table-responsive">
	        		<table class="tabler table-borderedr table-hover" style="width:100%;height:92%;page-break-inside:avoid;">
	        		<tr>
	        		<th rowspan="3">No.</th>
	            	<th rowspan="3">Student Name</th>
	            	<th rowspan="3" class="text-center">Student ID</th>';
        			foreach ($evalname_query->result_array() as $evalua_name) 
        			{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
	            		$colSpan=$queryMvalue->num_rows() +2;
	            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
	            	}
	            	$output.='<th class="text-center"><B>Total</B></th><th rowspan="3" class="text-center">Sig.</th><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
		            	foreach ($queryMvalue->result() as $mark_name)
		            	{
		            		$output.='<td class="text-center">'.$mark_name->markname.'</td>';
		            	}
		            	$output.='<td class="text-center"><b>Tot</b></td>';
		            	$output.='<td class="text-center"><b>Conv</b></td>';
		            }
		            $output.='<td rowspan="2" class="text-center"> <B>100</B> </td>';
	            	$output.='</tr><tr>';
	            	$sumOutOf=0;
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$percent=$evalua_name['percent'];
	            		$evName=$evalua_name['evname'];
            			$grade=$evalua_name['grade'];

		            	$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
            			$sumOutOf=0;
	            		foreach ($queryMvalue->result_array() as $mark_name) {
	            			$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            			$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            		}
	        			$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        			$queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$subject' and customasses='$evName' and customquarter='$gs_quarter' ");
		            	if($queryCheckPercentage->num_rows()>0){
		            		$rowPercent=$queryCheckPercentage->row();
		            		$customPercent=$rowPercent->custompercent;
		            		$output.='<td style="text-align:center;"><B>'.$customPercent.'</B></td>';
		            	}else{
		            		$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
		            	}
		            }
	            	$output.='</tr>';
	            	$stuNO=1;
	            	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname,u.lname ASC ");
				foreach ($queryStudent->result_array() as $row) { 
            		$id=$row['id'];
            		$output.='<tr><td>'.$stuNO.'.</td> <td>'.$row['fname'].' '.$row['mname'].' '.$row['lname']. '</td>';
            		$output.='<td class="text-center">'.$row['username'].' </td>';
            		$average=0;
            		foreach ($evalname_query->result_array() as $mark_name)
            		{
            			
            			$evName=$mark_name['evname'];
            			$grade=$mark_name['grade'];
            			$mname_gs=$mark_name['eid'];
            			$queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$subject' and customasses='$evName' and customquarter='$gs_quarter' ");
		            	if($queryCheckPercentage->num_rows()>0){
		            		$rowPercent=$queryCheckPercentage->row();
		            		$percent=$rowPercent->custompercent;
		            	}else{
		            		$percent= $mark_name['percent'];
		            	}

	            		$query_value = $this->db->query("select markname,sum(value) as total from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where  subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where stuid='$id' and subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' and approved='1' group by markname order by mid ASC");
								
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
				            	$sumOutOf=0;
					            foreach ($queryMvalue->result_array() as $mark_name) {
					            	$sumOutOf=$mark_name['outof'] + $sumOutOf;	
					            }
		            		}
		            		if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='')
	            			{
	                    		$conver= ($totalMark *$percent )/$sumOutOf;
	                    		if($totalMark>0){
	                    			$output.='<td style="text-align:center;">'.$totalMark.'</td>';
	                    			$output.='<td style="text-align:center;"><B>'.number_format((float)$conver,2,'.','').'</B></td>';
	                    		}else{
	                    			$output.='<td class="text-danger" style="text-align:center;">NG</td>';
	                    			$output.='<td class="text-danger" style="text-align:center;">NG</td>';
	                    		}
	            					$average =$conver + $average;
	                		}else{
	                			$output.='<td style="text-align:center;">-</td>';
	                			$output.='<td style="text-align:center;">-</td>';
	                		}
		            	}else{
		            		$output.='<td style="text-align:center;">-</td>';
		            		$output.='<td style="text-align:center;">-</td>';
		            	}
	            	}
	            	$output.='<td style="text-align:center;"><B>'.number_format((float)$average,2,'.','').'</B></td>';
            		$average=0;
            		$output.='<td style="text-align:center;"></td>';
					$stuNO++;
				}

					$output.='</table></div>';
					$output.='<p class="text-center">'.$school_slogan.'!</p>';
        		}
			}else{
				$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                	<i class="fas fa-check-circle"> </i> Record not found.
            	</div></div>';
			}
		}else{
			$querySingleSubject=$this->db->query("select * from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' ");
			if($querySingleSubject->num_rows()>0)
			{
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];

				$output.='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';

				$evalname_query=$this->db->query("select us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

				$output.='<div class="table-responsive">
	    		<table class="tabler table-borderedr table-hover" style="width:100%;height:85%">
	    		<thead>
	    		<tr>
	    		<th rowspan="3">No.</th>
	        	<th rowspan="3">Student Name</th>
	        	<th rowspan="3" class="text-center">Student ID</th>';
	        	foreach ($evalname_query->result_array() as $evalua_name) {
	        		$mname_gs=$evalua_name['eid'];
	        		$queryMvalue = $this->db->query("select markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
	        		$colSpan=$queryMvalue->num_rows() +2;
	        		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
	        	}
	        	$output.='<th class="text-center">Total</th><th rowspan="3" class="text-center">Sig.</th><tr>';
	        	foreach ($evalname_query->result_array() as $evalua_name) {
	        		$mname_gs=$evalua_name['eid'];
	        		$queryMvalue = $this->db->query("select markname from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$output.='<td class="text-center">'.$mark_name['markname'].'</td>';
	            	}
	            	$output.='<td class="text-center"><b>Tot</b></td>';
	            	$output.='<td class="text-center"><b>Conv</b></td>';
	            }
	            $output.='<td rowspan="2" class="text-center"><B>100</B></td>';
	        	$output.='</tr><tr>';
	        	foreach ($evalname_query->result_array() as $evalua_name) {
	        		
	        		$mname_gs=$evalua_name['eid'];
            	$evName=$evalua_name['evname'];
        			$grade=$evalua_name['grade'];

	        		$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
	        		$sumOutOf=0;
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            		$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            	}
	        		$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';

	        		$queryCheckPercentage=$this->db->query("select * from evaluationcustom where academicyear ='$max_year' and customgrade='$grade' and customsubject='$gs_subject' and customasses='$evName' and customquarter='$gs_quarter' ");
	            	if($queryCheckPercentage->num_rows()>0){
	            		$rowPercent=$queryCheckPercentage->row();
	            		$customPercent=$rowPercent->custompercent;
	            		$output.='<td style="text-align:center;"><B>'.$customPercent.'</B></td>';
	            	}else{
	            		$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
	            	}
	            }
	        	$output.='</tr>';
	        	$stuNO=1;
	        	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname ASC ");
				foreach ($queryStudent->result_array() as $row) { 
	        		$id=$row['id'];
	        		$output.='<tr><td>'.$stuNO.'.</td> <td>'.$row['fname'].' '.$row['mname'].' '.$row['lname']. '</td>';
	        		$output.='<td class="text-center">'.$row['username'].' </td>';
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
								$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' and approved='1' group by markname order by mid ASC");
								
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
				            		$sumOutOf=0;
					            foreach ($queryMvalue->result_array() as $mark_name) {
					            	$sumOutOf=$mark_name['outof'] + $sumOutOf;	
					            }
		            		}
		            		if($sumOutOf != 0 || $sumOutOf > 0 ||$sumOutOf !='')
	            			{
	                    		$conver= ($totalMark *$percent )/$sumOutOf;
	                    		if($totalMark>0){
	                    			$output.='<td style="text-align:center;">'.$totalMark.'</td>';
	                    			$output.='<td style="text-align:center;"><B>'.number_format((float)$conver,2,'.','').'</B></td>';
	                    		}else{
	                    			$output.='<td class="text-danger" style="text-align:center;">NG</td>';
	                    			$output.='<td class="text-danger" style="text-align:center;">NG</td>';
	                    		}
	            					$average =$conver + $average;
	                		}else{
	                			$output.='<td style="text-align:center;">-</td>';
	                			$output.='<td style="text-align:center;">-</td>';
	                		}
		            	}else{
		            		$output.='<td style="text-align:center;">-</td>';
		            		$output.='<td style="text-align:center;">-</td>';
		            	}
		            	
	            	}
	            	$output.='<td style="text-align:center;"><B>'.number_format((float)$average,2,'.','').'</B></td>';
	        		$average=0;
	        		$output.='<td style="text-align:center;"></td>';
					$stuNO++;
				}
				$output.='</table></div>';
				$output.='<p class="text-center">'.$school_slogan.'!</p>';
			}else{
	    		$output.='<div class="alert alert-warning alert-dismissible show fade">
	            <div class="alert-body">
	                <button class="close"  data-dismiss="alert">
	                    <span>&times;</span>
	                </button>
	            	<i class="fas fa-check-circle"> </i> Data Not Found.
	        	</div></div>';
			}
		}
		return $output;
	}
	function fetchCustomText(){
		$query=$this->db->query("select profile,fname,mname,ct.id,ct.datecreated,ct.comtext from customcomtext as ct cross join users as us where us.username=ct.createdby order by id DESC ");
		$output='';
		if($query->num_rows()>0){
			foreach ($query->result() as $fetchText) {
				$output.='<div class="col-md-12 col-lg-12 col-xl-12 deleteCustomText'.$fetchText->id.'">
            <div class="support-ticket">';
              	if($fetchText->profile!=''){ 
                	$output.='<img alt="image" src="'.base_url().'/profile/'.$fetchText->profile .'" class="user-img mr-2">';
                } else { 
                 	$output.='<img alt="image" src="'.base_url().'/profile/defaultProfile.png" class="user-img mr-2">';
              	} 
              	$output.='<span class="font-weight-bold">'.$fetchText->fname.' ' .$fetchText->mname.'</span> 
              	<a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
              	<small class="text-muted pull-right">'.$fetchText->datecreated.'</small>
                  <div class="dropdown-menu">
                      <a href="#" class="dropdown-item has-icon text-danger deleteCustomText" name="'.$fetchText->id.'" value="'.$fetchText->id.'" id="'.$fetchText->id.'"><i class="fas fa-trash-alt"></i> Remove
                      </a>
                  </div>
              	<div class="media-body">
                	<div class="moreData">'.$fetchText->comtext.' </div>
              	</div>
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
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div>';
		}
		return $output;
	}
	function fetchCustomTextTosend(){
		$query=$this->db->query("select * from customcomtext order by id DESC ");
		$output='';
		if($query->num_rows()>0){
			foreach ($query->result() as $fetchText) {
				$output.='<div class="activities">
          <div class="activity">
          	<div class="activity-icon bg-white text-dark">
              <i class="fas fa-envelope-square"></i>
            </div>
            <div class="activity-detail">
            <button class="btn btn-default useThisText" id="saveThisStudentToGroupEdit" value="'.$fetchText->comtext.'">
              <p>'.$fetchText->comtext.'</p></button>
            </div>
          </div>
        </div>';
			}
		}
		return $output;
	}
	function searchFinanceStudents($searchItem,$branch){
		$this->db->order_by('fname,mname,lname','ASC');
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('branch',$branch);
		$this->db->like('username',$searchItem);
		$this->db->or_where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('branch',$branch);
		$this->db->like('fname', $searchItem);
		$this->db->or_where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('branch',$branch);
		$this->db->like('grade', $searchItem);
		$query=$this->db->get('users');
		$output ='';
		if($query->num_rows()>0){
			$output .='
	         <div class="table-responsive">
	        <table class="table table-striped table-hover" style="width:100%;">
	        <thead>
	        <tr>
	        <th>No.</th>
	            <th>ID</th>
	            <th>Name</th>
	            <th>Gr. & Sec</th>
	            <th>Gender</th>
	            <th>Branch</th>
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
	            <td>'.$value->gender.'</td>
	            <td>'.$value->branch.' </td>  
	            </tr>';
	            $no++;
			}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No student found.
            </div></div>';
		}
		return $output;
	}
	function fetchFinanceBranchStudents($gs_branches,$gs_gradesec,$max_year,$user){
		$this->db->order_by('fname','ASC');
		$this->db->order_by('mname','ASC');
		$this->db->order_by('lname','ASC');
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('gradesec'=>$gs_gradesec));
		$this->db->where(array('branch'=>$gs_branches));
		$this->db->like('usertype','Student');
		$query=$this->db->get('users');
		$output ='';
		foreach ($query ->result() as $value) {
			$id=$value->id;
			$userName=$value->username;
			$queryIncident=$this->db->query("select * from incident_report where stuid='$userName' and academicyear='$max_year' and report_by='$user' ");
			$output.='<div class="chat-container-gss">
				        <div class="message-container-gs">';
			if($queryIncident->num_rows()>0){
				$output.='<div class="have-incident-report">
					<div class="support-ticket media">';
			        if($value->profile!=''){
			            $output.='<img class="user-img" src="'.base_url().'profile/'.$value->profile.'" width="40">';
			        }else{
			            $output.='<img class="user-img" src="'.base_url().'profile/defaultProfile.png" width="40">';
			        }
		        	$output.='<div class="media-body">
		        	<span class="font-weight-bold">'.$value->fname .' '.$value->mname.' '.$value->lname.'</span>
		          		
		          			<a href="#" id="IncidentReportPage" class="badge badge-pill badge-success pull-right" data-toggle="modal" data-target="#IncidentReport"  value="'.$value->username .'"> Report incident </a> 
		            		<a href="#" id="PreviousReportPage" class="badge badge-pill badge-warning pull-right" data-toggle="modal" data-target="#PreviousReport"  value="'.$value->username .'"> Previous Report </a>
		          		
		          		</div>
		        	</div>
		    	</div> ';
		    }else{
		    	$output.='<div class="no-have-incident-report">
					<div class="support-ticket media">';
			        if($value->profile!=''){
			            $output.='<img class="user-img" src="'.base_url().'profile/'.$value->profile.'" width="40">';
			        }else{
			            $output.='<img class="user-img" src="'.base_url().'profile/defaultProfile.png" width="40">';
			        }
		        	$output.='<div class="media-body">
		        		<span class="font-weight-bold">'.$value->fname .' '.$value->mname.' '.$value->lname.'</span>
		          		
		          			<a href="#" id="IncidentReportPage" class="badge badge-pill badge-success pull-right" data-toggle="modal" data-target="#IncidentReport"  value="'.$value->username .'"> Report incident </a> 
		            		<a href="#" id="PreviousReportPage" class="badge badge-pill badge-warning pull-right" data-toggle="modal" data-target="#PreviousReport"  value="'.$value->username .'"> Previous Report </a>
		          		</div>
		          		
		        	</div>
		    	</div> ';
		    }
		    $output.='</div></div><div class="dropdown-divider"></div>';
		}
		return $output;
	}
	function fetch_this_incidentform_type($incidentTypeCategoryChoose){
		$output='';
		$incidentName=$this->db->query("SELECT * FROM incident_type where incident_category='$incidentTypeCategoryChoose' group by incident_name 
			ORDER BY incident_name ASC  ");
		if($incidentName->num_rows()>0){
			$output.='<div class="row">';
          	foreach($incidentName->result() as $incidentNames){
          		$output.='<div class="col-lg-12 col-md-12 col-12"> 
          			<div class="pretty p-fill">
		                <input type="checkbox" name="setAsIncident_Info" class="setAsIncident_Info" value="'.$incidentNames->incident_name.'">
		                <div class="state p-success">
		                    <label></label>'.$incidentNames->incident_name.'
		                </div>
				    </div>
				</div>';
          	}
          	$output.='</div>';
        }else{
        	$output.='<span class="text-danger">No incident type found.</span>';
        }
        return $output;
	}
	function fetch_this_incidentform_type_level($incidentTypeCategoryChoose){
		$output='';
		$date=date("Y-m-d");
		$queryMaxId=$this->db->query("SELECT max(id) as Max_ID FROM incident_category");
		if($queryMaxId->num_rows()>0){
			$maxIDRow=$queryMaxId->row();
			$maxID=$maxIDRow->Max_ID;
		}else{
			$maxID='0';
		}
		$incidentName=$this->db->query("SELECT id FROM incident_category where category_name='$incidentTypeCategoryChoose' group by category_name ");
		if($incidentName->num_rows()>0){
			$thisIdRow=$incidentName->row();
			$thisID=$thisIdRow->id;
			if($thisID==$maxID){
				$output.='<div class="row">';
					$output.='<div class="form-group col-md-3 col-lg-3 col-6">
					<label>Action taken:</label>
                    <select class="form-control" name="admin_action" id="admin_action" required="required">
	                    <option> </option>
	                    <option> Conference W/student</option>
	                    <option> Parents called in </option>
	                    <option> In-school suspension</option>
	                    <option> Out-of-school suspension</option>
	                    <option> Sent to the G.C</option>
	                    <option> Other</option>
	                  </select>
	                </div>
	                <div class="form-group col-lg-2 col-md-3 col-6">
                    	<label>In-school suspension:</label>
                    	<input type="date" class="form-control" name="date_suspension_inschool" id="date_suspension_inschool" value="'.$date.'">
                  	</div>
                  	<div class="form-group col-lg-2 col-md-3 col-6">
                    	<label>class re-entry date:</label>
                    	<input type="date" class="form-control" name="reentry_date_inschool" id="reentry_date_inschool" required>
                  	</div>
                  	<div class="form-group col-lg-3 col-md-3 col-6">
                    	<label>Out-of-school suspension:</label>
                    	<input type="date" class="form-control" name="date_suspension_outschool" id="date_suspension_outschool" value="'.$date.'">
                  	</div>
                  	<div class="form-group col-lg-2 col-md-3 col-6">
                    	<label>Re-entry date:</label>
                    	<input type="date" class="form-control" name="reentry_date_outschool" id="reentry_date_outschool" required>
                  	</div>
	            </div>';
			}
        }
        return $output;
	}
	function reportIncident_student($username,$max_year,$tname,$mname,$user){
		$this->db->where('username',$username);
		$this->db->where('academicyear',$max_year);
		$query=$this->db->get('users');
		$output='';
		$date=date("Y-m-d");
		$queryFetch=$this->db->query("select * from incident_category group by category_name order by id ASC");
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$output.='<form type="role" class="save_new_incident_teacher_side" method="POST">
					<input type="hidden" id="incident_teacher" name="incident_teacher" value="'.$user.'">
					<input type="hidden" id="incident_student" name="incident_student" value="'.$username.'">
					<input type="hidden" id="incidented_Name" name="incidented_Name" value="'.$row->fname.' '.$row->mname.' '.$row->lname.'">
					<input type="hidden" id="incidented_grade" name="incidented_grade" value="'.$row->gradesec.'">
					<span class="text-time">Student Name: '.$row->fname.' '.$row->mname.' '.$row->lname.' <i class="fas fa-chevron-right"></i> Grade: '.$row->gradesec.' <i class="fas fa-chevron-right"></i> Teacher Name: '.$tname.' '.$mname.' <i class="fas fa-chevron-right"></i> Date : 
					<input id="incident_date" type="date" class="form-contrdol" required="required" name="incident_date" value="'.$date.'"></span>
                	<div class="row">
                		<div class="form-group col-lg-3 col-md-3 col-6">
                    		<label>Choose Incident Type:</label>
                    		<select class="form-control" required="required" name="incidentTypeCategoryChoose" id="incidentTypeCategoryChoose">
                    			<option></option>';
								foreach($queryFetch->result() as $row){
									$output.='<option>'.$row->category_name.'</option>';
								}
							$output.='</select>
                  		</div>
	                  	<div class="form-group col-lg-6 col-md-6 col-6 table-responsive" style="height:15h">
	                  		<label>Check all that apply:</label>
	                  		<div class="page_for_incident_type"></div>
	                  	</div>
	                  	<div class="form-group col-lg-3 col-md-3 col-12">
		                  	<label>Location of Incident:</label>
		                  	<select class="form-control" name="incident_location" id="incident_location" required="required">
		                    	<option> </option>
		                    	<option> Outside</option>
		                    	<option> Dining Hall</option>
		                    	<option> Hallway</option>
		                    	<option> Restroom</option>
		                    	<option> Classroom</option>
		                    	<option> Bus/ Field trip</option>
		                    	<option> Arrival/ Dismissal</option>
		                    	<option> Other</option>
		                  	</select>
		                </div>
	                  	<div class="form-group col-lg-12 col-md-12 col-12">
	                    	<label>Describe Incident:</label>
	                    	<textarea class="form-co4ntrol incident_description" rows="4" cols="50" wrap="physical" name="incident_description" id="incident_description" placeholder="Describe Incident..." style="width:100%; height:100px;" required></textarea>
	                    	
	                  	</div>
	                  	<div class="form-group col-lg-2 col-md-3 col-12">
		                  	<label>Is this the first offense?</label>';
		                  	$queryCheck=$this->db->query("SELECT * FROM incident_report where stuid='$username' and report_by='$user' ORDER BY id DESC limit 1");
		                  	if($queryCheck->num_rows()>0){
			                  	$output.='<select class="form-control" name="is_offense" id="is_offense" required="required">
				                    <option> Yes</option>
				                    <option selected="selected"> No</option>
				                </select>';
		                  	}else{
		                  		$output.='<select class="form-control" name="is_offense" id="is_offense" required="required">
		                    	<option> No</option>
		                    	<option selected="selected"> Yes</option>
		                  		</select>';
		                  	}
		                  	$output.=' </div>';
			                if($queryCheck->num_rows()>0){
			                	$rowCon=$queryCheck->row();
			                    $con_prev=$rowCon->previous_conse;
				                $incidentID=$rowCon->id;
			                  	$incidentName=$this->db->query("SELECT * FROM incident_student_type where stuid='$username' and incident_id ='$incidentID' ");
			                  	if($incidentName->num_rows()>0){
			                  		$output.='<div class="form-group col-lg-4 col-md-8 col-12">
		                    		<label>Previous incident:</label><br>';
				                  	foreach($incidentName->result() as $incidentNames){
				                  		$output.='<div class="pretty p-fill">
						                	<input type="checkbox" name="" class="" value="'.$incidentNames->incident_type.'" checked="checked">
							                <div class="state p-info">
							                    <i class="fas fa-check"></i>'.$incidentNames->incident_type.'
							                </div>
										</div>';
				                  	}
				                  	$output.='</div>';
				                }
				            }
	                    	$output.='<div class="form-group col-lg-6 col-md-8 col-12">
	                    		<label>Previous consequences:</label>';
	                    		$queryCons=$this->db->query("select * from incident_consequence group by consequence_name order by id ASC");
								if($queryCons->num_rows()>0){
									$output.='<select class="form-control" required="required" name="previous_conse" id="previous_conse">
									<option></option>';
									foreach($queryCons->result() as $rowCon){
										$coName=$rowCon->consequence_name;
					                    if($queryCheck->num_rows()>0){
					                    	$rowCon=$queryCheck->row();
					                    	$con_prev=$rowCon->previous_conse;
					                    	if($coName==$con_prev){
					                    		$output.='<option selected="selected">'.$coName.'</option>';
					                    	}else{
					                    		$output.='<option>'.$coName.'</option>';
					                    	}
					                    }else{
					                    	$output.='<option>'.$coName.'</option>';
					                    }
					               	}
					               	$output.='</select>';
								}else{
									$output.='<span class="text-danger"><br>Please set consequence lists</span>';
								}
	                    	$output.='</div>';
                  		$output.='<div class="form-group col-lg-12 col-md-12 col-12">
                  			<div class="page_for_incident_type_level"></div>
                  		</div>
                  		<div class="form-group col-lg-12 col-12">
                    		<button class="btn btn-primary pull-right" id="save_incident">  Submit Incident
                    		</button>
                  		</div>
                	</div>
            	</form>';
			}
		}
		return $output;
	}
	function previous_incident_report($username,$max_year,$tname,$mname,$user){
		$this->db->where('username',$username);
		$query=$this->db->get('users');
		$queryRow=$query->row();
		$fName=$queryRow->fname;
		$mName=$queryRow->mname;
		$lName=$queryRow->lname;
		$profile=$queryRow->profile;
		$output='';
		$queryIncidet=$this->db->query("SELECT ir.id,us.fname,us.lname,us.mname,us.profile,ir.incident_type, ir.incident_location,ir.admin_action,ir.is_offense,ir.previous_conse,ir.date_in_suspension, ir.incidet_desc,ir.date_out_suspension,ir.out_reentry_date,ir.date_report,ir.in_reentry_date FROM incident_report as ir cross join users as us where stuid='$username' and ir.report_by='$user' and us.username=ir.report_by group by ir.id ORDER BY ir.id DESC  ");
		if($queryIncidet->num_rows()>0){
			foreach($queryIncidet->result() as $row){
				$output.='<div class="support-ticket media">';
                    if($profile!=''){
		            	$output.='<img class="user-img" src="'.base_url().'profile/'.$profile.'" width="30">';
			        }else{
			            $output.='<img class="user-img" src="'.base_url().'profile/defaultProfile.png" width="30">';
			        }
                	$output.='<div class="media-body ml-3">
                  	<span class="font-weight-bold">'.$fName.' '.$mName.' '.$lName.'</span>&nbsp;';
                  	if($row->incident_type=='White Incident Form'){
                  		$output.='<div class="badge badge-pill badge-light"> '.$row->incident_type.'</div><br>';
                  	}else if($row->incident_type=='Yellow Incident Form'){
                  		$output.='<div class="badge badge-pill badge-info"> '.$row->incident_type.'</div><br>';
                  	}else if($row->incident_type=='Orange Incident Form'){
                  		$output.='<div class="badge badge-pill badge-warning"> '.$row->incident_type.'</div><br>';
                  	}else{
                  		$output.='<div class="badge badge-pill badge-danger"> '.$row->incident_type.'</div><br>';
                  	}
                  	$incidentID=$row->id;
                  	$incidentName=$this->db->query("SELECT * FROM incident_student_type where stuid='$username' and incident_id ='$incidentID' ");
                  	if($incidentName->num_rows()>0){
	                  	foreach($incidentName->result() as $incidentNames){
	                  		$output.='<div class="pretty p-fill">
			                	<input type="checkbox" name="" class="" value="'.$incidentNames->incident_type.'" checked="checked">
				                <div class="state p-info">
				                    <i class="fas fa-check"></i>'.$incidentNames->incident_type.'
				                </div>
							</div>';
	                  	}
	                }
                  	$output.='<p>Incident Location:- '.$row->incident_location.'</p>';
                  	if($row->admin_action!=''){
                  		$output.='<p>Action taken:- '.$row->admin_action.'</p>';
                  	}
                  	$output.='<p>
                  	Is it the first offense? <u>'.$row->is_offense.'</u> </p>';
                  	if($row->is_offense=='Yes'){
                  		$output.='';
                  	}else{
                  		if($row->previous_conse!=''){
                  			$output.='<p>If not, what was the previous consequence? <u>'.$row->previous_conse.'</u></p>';
                  		}else{
                  			$output.='<p>If not, what was the previous consequence? ______________________________________________________________________________________________ ___________________________________________________________________ ________________________________________________________________</p>';
                  		}
                  	}
                    
                    $output.='<p>'.$row->incidet_desc.' </p>';
                    if($row->date_in_suspension!=''){
                    	$output.='<p><b> <u>In-school suspension :</u></b> <br>Date : <u> '.$row->date_in_suspension .'</u> &nbsp;&nbsp;&nbsp; class re-entry date: <u>'.$row->in_reentry_date.'</u> &nbsp</p>';
                    }
                    if($row->date_out_suspension!=''){
                    	$output.='<p><b> <u>Out-of-school suspension :</u></b> <br>Date : <u> '.$row->date_out_suspension .'</u> &nbsp;&nbsp;&nbsp; Re-entry date: <u>'.$row->out_reentry_date.'</u> &nbsp</p>';
                    }
                    $output.='<small class="text-muted">Report by <span class="font-weight-bold font-13">'.$row->fname.' '.$row->mname.'</span>&nbsp;
                        <span> '.$row->date_report.'</span></small>
                    </div>
                  </div>
                <hr>';
			}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Ooops, No record found.
        	</div></div>';
		}
		return $output;
	}
	function fetch_thisSummaryRecord($gs_branches,$gs_gradesec,$max_year){
		$this->db->order_by('fname,mname,lname','ASC');
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where('gradesec',$gs_gradesec);
		$this->db->where(array('branch'=>$gs_branches));
		$this->db->like('usertype','Student');
		$query=$this->db->get('users');
		$output ='';
		$output .='
         <div class="table-responsive">
        <table class="tabler table-borderedr table-hover" style="width:100%;">
        <thead>
        <tr>
        	<th>No.</th>
        	<th>Name</th>
            <th>Student ID</th>
            <th>Gender</th> 
            <th>Age</th>            
            <th>Grade</th>
            <th>Section</th>
        </tr>
        </thead>
       <tbody>';
       $no=1;
		foreach ($query ->result() as $value) {
			$id=$value->id;
			$output .='<tr class="delete_mem'.$value->id.'">
			<td>'.$no.'. </td>
			<td>'.$value->fname .' '.$value->mname.' '.$value->lname.' </td> 
			<td>'.$value->unique_id.' </td>
			<td>'.$value->gender.'</td>
			<td>'.$value->age.'</td>
            <td>'.$value->grade.'</td>
            <td>'.$value->gradesec.'</td> 
            </tr>';
            $no++;
		}
		$query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where gradesec='$gs_gradesec' and academicyear='$max_year' and branch='$gs_branches' and status='Active' and isapproved='1' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
		foreach ($query2->result() as $value) {
			$output.='<tr><td colspan="3" rowspan="3" class="text-center"><b>Summary<small>(Branch: '.$gs_branches.')</small></b></td>
			<td colspan="2" rowspan="1" class="text-center"><b>Male:</b></td>
			<td colspan="2" class="text-center"><b>'.$value->malecount.'</b></td></tr>
			<tr>
			<td colspan="2" rowspan="1" class="text-center"><b>Female:</b></td>
			<td colspan="2" class="text-center"><b>'.$value->femalecount.'</b></td></tr>
			<tr>
			<td colspan="2" rowspan="1" class="text-center"><b>Total:</b></td>
			<td colspan="2" class="text-center"><b>'.$value->studentcount.'</b></td>
			</tr>';
		}
		$output.='</tbody></table></div><div class="dropdown-divider"></div>';
		return $output;
	}
	function fetch_thisSummaryRecordNoName($gs_branches,$gs_gradesec,$max_year){
		$output='';
		$query_school=$this->db->get('school');
		$schoolRow=$query_school->row();
		$website=$schoolRow->website;
		$logo=$schoolRow->logo;
		$name=$schoolRow->name;
		$output='<p><h4 class="text-center"><b><u>'.$name.' Grade Statistics for '.$max_year.' Academic Year <small>('.$gs_branches.' Branch)</small></u></b></h4></p>
		<div class="table-responsive">
		<table class="tabler table-bordered table-hover" style="width:100%;">';
		$no=1;$grandTotal=0;$grandMale=0;$grandFemale=0;
		foreach($gs_gradesec as $gs_gradesecs){
			$querySection=$this->db->query("select gradesec from users where academicyear='$max_year' and branch='$gs_branches' and gradesec='$gs_gradesecs' and gradesec!='' group by gradesec order by gradesec ");
			if($querySection->num_rows()>0){
				$totalSection=($querySection->num_rows() * 3) + 5;
				$output.='<tr><td rowspan='.$totalSection.'>'.$no.'</td><td rowspan='.$totalSection.'> Grade: '.$gs_gradesecs.'</td>';
			    foreach($querySection->result() as $sectionNum){
			    	$gradesec=$sectionNum->gradesec;
					$output.='<tr><td rowspan="3">'.$sectionNum->gradesec.'</td>';
					$gradeSecTotal = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' or Gender='MALE' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' or Gender='FEMALE' then 1 else 0 end) AS femalecount FROM users where gradesec='$gradesec' and academicyear='$max_year' and branch='$gs_branches' and status='Active' and isapproved='1' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
					foreach ($gradeSecTotal->result() as $value) {
						$output.='<tr><td>Male</td><td>'.$value->malecount.'</td><td rowspan="2" class="text-center"> <span class="badge badge-primary">'.$value->studentcount.'</span></td> </tr>';
						$output.='<tr><td>Female</td><td>'.$value->femalecount.'</td></tr>';
					}
					$output.='</tr>';
				}
				$gradeTotal = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' or Gender='MALE' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' or Gender='FEMALE' then 1 else 0 end) AS femalecount FROM users where gradesec='$gs_gradesecs' and academicyear='$max_year' and branch='$gs_branches' and status='Active' and isapproved='1' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
				foreach ($gradeTotal->result() as $value) {
					$grandTotal=$grandTotal + $value->studentcount;
					$grandMale=$grandMale + $value->malecount;
					$grandFemale=$grandFemale + $value->femalecount;
					$output.='</tr><tr><td colspan="1" rowspan="3" style="background-color:#e3e3e3"><i>Grade '.$gs_gradesecs.' Total:</i></td>
					<tr><td style="background-color:#e3e3e3">Male</td><td style="background-color:#e3e3e3"><span class="badge badge-info">'.$value->malecount.'</span></td><td rowspan="2" class="text-center" style="background-color:#e3e3e3"><b><i><span class="badge badge-info">'.$value->studentcount.'</span></i></b></td>
					<tr><td style="background-color:#e3e3e3">Female</td><td style="background-color:#e3e3e3"><span class="badge badge-info">'.$value->femalecount.'</span></td></tr>
					<tr>';
				}
			}
			$no++;
		}
		$output.='<tr style="background-color:#e3e3e3"><td colspan="3" rowspan="3"><i>Grand Total For Selected Grade:</i></td>
			<tr><td style="background-color:#e3e3e3">Male</td><td style="background-color:#e3e3e3"><span class="badge badge-primary">'.$grandMale.'</span></td><td rowspan="2" class="text-center" style="background-color:#e3e3e3"><b><i><span class="badge badge-success">'.$grandTotal.'</span></i></b></td>
					<tr><td style="background-color:#e3e3e3">Female</td><td style="background-color:#e3e3e3"><span class="badge badge-primary">'.$grandFemale.'</span></td></tr>

		</tr>';
		$output.='</table></div>';
		return $output;
	}
	function fetch_thisgradeAge($branch,$summaryGSGrades,$max_year){
		$output ='';
		foreach($summaryGSGrades as $summaryGSGradess){
			$this->db->where('users.branch',$branch);
			$this->db->where('users.grade',$summaryGSGradess);
			$this->db->where(array('users.academicyear'=>$max_year));
			$this->db->where('users.usertype','Student');
			$this->db->where('users.age!=','0');
			$this->db->order_by('users.age','ASC');
			$this->db->group_by('users.age');
			$query=$this->db->get('users');
			$output.='<div class="row">';
			foreach ($query->result() as $row) {
				$output.='<div class="col-lg-6 col-6">
	                <div class="pretty p-bigger">
	                    <input type="checkbox" class="summaryGSAge" name="summaryGSAge" value="'.$row->age.'" id="customCheck1 summaryGSAge"> 
	                    <div class="state p-info">
	                      <i class="icon material-icons"></i>
	                      <label></label>'.$row->age.'
	                    </div>
	                </div>
	            </div>'; 
			}
			$output.='</div>';
		}
	    return $output;
	}
	function fetch_thisSummaryRecordNoNameAge($gs_branches,$gs_gradesec,$max_year,$summaryGSAges){
		$output='';
		$query_school=$this->db->get('school');
		$schoolRow=$query_school->row();
		$website=$schoolRow->website;
		$logo=$schoolRow->logo;
		$name=$schoolRow->name;
		$output='<p><h2 class="text-center"><b><u>'.$name.' Grade Statistics for '.$max_year.' Academic Year <small>('.$gs_branches.' Branch)</small></u></b></h2></p>
		<div class="table-responsive">
		<table class="tabler table-bordered table-hover" style="width:100%;">';
		$no=1;$grandTotal=0;$grandMale=0;$grandFemale=0;
		foreach($summaryGSAges as $summaryGSAges){

			foreach($gs_gradesec as $gs_gradesecs){
				$querySection=$this->db->query("select gradesec from users where academicyear='$max_year' and branch='$gs_branches' and gradesec='$gs_gradesecs' and age='$summaryGSAges' group by gradesec order by gradesec ");
				if($querySection->num_rows()>0){
					$totalSection=($querySection->num_rows() * 3) + 5;
					$output.='<tr><td rowspan='.$totalSection.'>'.$no.'.</td><td rowspan='.$totalSection.'> Grade: '.$gs_gradesecs.' Age: '.$summaryGSAges.'</td>';
				    foreach($querySection->result() as $sectionNum){
				    	$gradesec=$sectionNum->gradesec;
						$output.='<tr><td rowspan="3">'.$sectionNum->gradesec.'</td>';
						$gradeSecTotal = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where gradesec='$gradesec' and academicyear='$max_year' and branch='$gs_branches' and age='$summaryGSAges' and status='Active' and isapproved='1' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
						foreach ($gradeSecTotal->result() as $value) {
							$output.='<tr><td>Male</td><td>'.$value->malecount.'</td><td rowspan="2" class="text-center"> <span class="badge badge-primary">'.$value->studentcount.'</span></td> </tr>';
							$output.='<tr><td>Female</td><td>'.$value->femalecount.'</td></tr>';
						}
						$output.='</tr>';
					}
					$gradeTotal = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where gradesec='$gs_gradesecs' and academicyear='$max_year' and branch='$gs_branches' and age='$summaryGSAges' and status='Active' and isapproved='1' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
					foreach ($gradeTotal->result() as $value) {
						$grandTotal=$grandTotal + $value->studentcount;
						$grandMale=$grandMale + $value->malecount;
						$grandFemale=$grandFemale + $value->femalecount;
						$output.='</tr><tr><td colspan="1" rowspan="3" style="background-color:#e3e3e3"><i>Grade '.$gs_gradesecs.' Total:</i></td>
						<tr><td style="background-color:#e3e3e3">Male</td><td style="background-color:#e3e3e3"><span class="badge badge-info">'.$value->malecount.'</span></td><td rowspan="2" class="text-center" style="background-color:#e3e3e3"><b><i><span class="badge badge-info">'.$value->studentcount.'</span></i></b></td>
						<tr><td style="background-color:#e3e3e3">Female</td><td style="background-color:#e3e3e3"><span class="badge badge-info">'.$value->femalecount.'</span></td></tr>
						<tr>';
					}
				}
			}
			$no++;
		}
		$output.='<tr style="background-color:#e3e3e3"><td colspan="3" rowspan="3"><i>Grand Total For Selected Grade:</i></td>
			<tr><td style="background-color:#e3e3e3">Male</td><td style="background-color:#e3e3e3"><span class="badge badge-primary">'.$grandMale.'</span></td><td rowspan="2" class="text-center" style="background-color:#e3e3e3"><b><i><span class="badge badge-success">'.$grandTotal.'</span></i></b></td>
					<tr><td style="background-color:#e3e3e3">Female</td><td style="background-color:#e3e3e3"><span class="badge badge-primary">'.$grandFemale.'</span></td></tr>

		</tr>';
		$output.='</table></div>';
		return $output;
	}
	function searchAdminStudentsToLockMark($searchItem,$branch,$max_year){
		$this->db->order_by('fname,mname,lname','ASC');
		$this->db->like('username',$searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('branch'=>$branch));
		

		$this->db->or_like('fname', $searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('branch'=>$branch));

		$this->db->or_like('fname', $searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('branch'=>$branch));

		$this->db->or_like('grade', $searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('branch'=>$branch));
		$query=$this->db->get('users');
		$output ='';
		if($query->num_rows()>0){
			$output .='
	         <div class="table-responsive">
	        <table class="table table-striped table-hover" style="width:100%;">
	        <thead>
	        <tr>
	        <th>No.</th>
	            <th>ID</th>
	            <th>Name</th>
	            <th>Gr & Sec</th>
	            <th>Gender</th>
	            <th>Branch</th>
	            <th>Lock</th>
	        </tr>
	        </thead>
	        <tbody>';
	        $no=1;
			foreach ($query ->result() as $value) {

			$id=$value->id;
			$output .='<tr class="lockStudent'.$value->id.'">
			<td>'.$no.'.</td>
			<td>'.$value->unique_id.' </td>
            <td>'.$value->fname .' '.$value->mname.' '.$value->lname.' </td> ';
            $output.='<td>'.$value->gradesec.'</td>';
            $output.='
            <td>'.$value->gender.'</td>
            <td>'.$value->branch.' </td>     
            <td>';
            $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='lockstudentmark' order by id ASC "); 
            if($uperStuDE->num_rows() >0){
	            $output.='<button class="btn btn-info lockThisStudentMark" id="lockThisStudentMark'.$value->id.'" name="'.$max_year.'" value="'.$value->id.'"> Lock</button>';
		        }
	          $uperStuDEu=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='unlockstudentmark' order by id ASC ");  
	          if($uperStuDEu->num_rows() >0){
		          $output.='<button class="btn btn-warning unlockThisStudentMark" id="unlockThisStudentMark'.$value->id.'" name="'.$max_year.'" value="'.$value->id.'"> UnLock</button>';
		        }else{
		        	$output.='<span class="badge badge-light">No Permission to unlock</span>';
		        }
            $output.=' </td> 
            </tr>';
            $no++;
			}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No student found.
            </div></div>';
		}
		return $output;
	}
	function fetchThisBranchSection($branch,$academicyear){
		$output ='<div class="row"> ';
		foreach($branch as $branchs){
			$this->db->where('branch',$branchs);
			$this->db->where('usertype','Student');
			$this->db->where('academicyear',$academicyear);
			$this->db->order_by('users.gradesec','ASC');
			$this->db->group_by('users.gradesec');
			$query=$this->db->get('users');
			
			foreach ($query->result() as $row) {
				$output .='<div class="col-lg-4 col-6">
				<input type="checkbox" class="gradesescListForLockMarkList" name="gradesescListForLockMarkList[ ]" value="'.$row->gradesec.'"/>'.$row->gradesec.' 
				<small id="locksectionInfo'.$row->gradesec.'"></small>
				</div>';
			}
		}
		$output .='</div>';
		return $output;
	}
	function lockThisSectionMark($branchs,$checkGradesec,$max_year){
		foreach($checkGradesec as $checkGradesecs){
			$queryTerm=$this->db->query("select term from quarter where Academic_year='$max_year' group by term ");
      if($queryTerm->num_rows()>0){
        foreach($queryTerm->result() as $termName){
          $max_quarter=$termName->term;
          $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branchs.$checkGradesecs.$max_quarter.$max_year."' ");
          if ($queryCheckMark->num_rows()>0)
          {
              $this->db->where('lockmark','0');
              $this->db->set('lockmark','1');
              $queryUpdate=$this->db->update('mark'.$branchs.$checkGradesecs.$max_quarter.$max_year);
          }
        }
      }	
		}
	}
	function UnlockThisSectionMark($branchs,$checkGradesec,$max_year){
		foreach($checkGradesec as $checkGradesecs){
			$queryTerm=$this->db->query("select term from quarter where Academic_year='$max_year' group by term ");
      if($queryTerm->num_rows()>0){
        foreach($queryTerm->result() as $termName){
	        $max_quarter=$termName->term;
	        $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branchs.$checkGradesecs.$max_quarter.$max_year."' ");
	        if ($queryCheckMark->num_rows()>0)
	        {
            $this->db->where('lockmark','1');
            $this->db->set('lockmark','0');
            $queryUpdate=$this->db->update('mark'.$branchs.$checkGradesecs.$max_quarter.$max_year);
	        }
        }
      }	
		}
	}
	function fetch_grade_markresult_comment($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year)
	{
		$output='';
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
		$queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."' ");
		if ($queryCheck->num_rows()>0)
		{
			/*$querySingleSubject=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' ");
			if($querySingleSubject->num_rows()>0)
			{*/

				$output.='<div style="page-break-inside:avoid;page-break-after: always;">
				<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';

				$evalname_query=$this->db->query("select us.id,ev.grade, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' and us.academicyear='$max_year' group by ev.evname order by ev.eid ASC");
            	$stuNO=1;
            	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade,u.gradesec,u.profile from users as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname,lname ASC ");
            	$output.='<input type="hidden" id="academicyearTcomment_teacher" value="'.$max_year.'"> ';
		        $output.='<input type="hidden" id="subjectTcomment_teacher" value="'.$gs_subject.'"> ';
		        $output.='<input type="hidden" id="quarterTcomment_teacher" value="'.$gs_quarter.'"> ';
		        $output.='<input type="hidden" id="markGradeSecTcomment_teacher" value="'.$gs_gradesec.'"> ';
		        $output.='<input type="hidden" id="markGradeSecBranchTcomment_teacher" value="'.$gs_branches.'"> ';
				foreach ($queryStudent->result_array() as $row) { 
            		$id=$row['id'];
            		$average=0;
            		$output.='<input type="hidden" id="markGradeStuidTcomment_teacher" name="markGradeStuidTcomment_teacher" value="'.$id.'"> ';
            		$output.='<div class="col-md-12 col-lg-12 col-xl-12">
	                <div class="support-ticket">';
                  	/*if($row['profile']!=''){ 
                    	$output.='<img alt="image" src="'.base_url().'/profile/'.$row['profile'] .'" class="user-img mr-2">';
                    } else { 
                     	$output.='<img alt="image" src="'.base_url().'/profile/defaultProfile.png" class="user-img mr-2">';
                  	}*/ 
                  	$output.='<span class="font-weight-bold">'.$row['fname'].' ' .$row['mname'].' '.$row['lname']. '</span> ';
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
	            		$query_value = $this->db->query("select markname,sum(value) as total from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where  subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
								
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
									}
								}
								$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from `mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."` where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
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
	            	$output.='<span class="badge badge-light pull-right">Total<small>(100%)</small> - '.number_format((float)$average,2,'.','').'</span>';
            		$average=0;
            		$queryComment=$this->db->query("select * from manualreportcardcomments where academicyear='$max_year' and quarter='$gs_quarter' and subject='$gs_subject' and stuid='$id' ");
            		if($queryComment->num_rows()>0){
            			$commentRow=$queryComment->row();
            			$commentValue=$commentRow->resultcomment;            			
            			$output.='<textarea class="form-controrl" name="teacher_comment_gs_comment" id="teacher_comment_gs_comment" placeholder="Add Comment" style="width:100%; height:100px;"> '.$commentValue.'</textarea>';          			
            		}else{
            			$output.='<textarea class="form-controrl" name="teacher_comment_gs_comment" id="teacher_comment_gs_comment" placeholder="Add Comment" style="width:100%; height:100px;"> </textarea>';
            		}
            		$output.='</div>
	                </div>
	                <div class="dropdown-divider"></div>';
				}
				$output.='<button class="btn btn-info btn-block" type="submit" name="submitTeacherCommentTeacher" id="submitTeacherCommentTeacher">Save Changes</button> </div>
				<p class="infoTeacherComment_comment"></p>';
			/*}else{
	    		$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                	<i class="fas fa-check-circle"> </i> Data not found for '.$gs_subject.'.
            	</div></div>';
			}*/
			
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            	<i class="fas fa-check-circle"> </i> Table not found.
        	</div></div>';
		}
		return $output;
	}
	function save_this_grade_teacher_comment($id,$academicyear,$subject,$quarter){
		$this->db->where(array('stuid'=>$id));
		$this->db->where(array('subject'=>$subject));
		$this->db->where(array('quarter'=>$quarter));
		$this->db->where(array('academicyear'=>$academicyear));
		$querystu=$this->db->get('manualreportcardcomments');
		if($querystu->num_rows()>0){
			return false;
		}else{
			return true;
		}
	}
	public function load_subject_to_feed($user,$mybranch,$max_year)
	{
		
    	$this->db->where('academicyear',$max_year);
    	$this->db->where('staff',$user);
    	$this->db->group_by('grade , subject');
    	$this->db->order_by('subject','ASC');
    	$queryPlacement=$this->db->get('staffplacement');

		$this->db->where('academicyear',$max_year);
    	$this->db->where('staff',$user);
    	$queryFetchRemote=$this->db->get('staffremoteplacement');
		$output='';
		if($queryPlacement->num_rows()>0 || $queryFetchRemote->num_rows()>0){
			$output.='<div class="row">';
			foreach($queryPlacement->result() as $rowPlacement){
				$subject=$rowPlacement->subject;
				$grade=$rowPlacement->grade;
				$output.='<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12" id="view_ThisSubjectResult_gs">
					<a href="javascript:void(0)" class="view_ThisSubjectResult_gs startFeedingResult" id="'.$max_year.'" value="'.$grade.'" name="'.$mybranch.'" title="'.$subject.'">
					<div class="card-body StudentViewTextInfo">
		                <div class="badge badge-pill badge-default pull-right" ><i class="fas fa-chevron-right"></i>
		                </div>
		                <span class="font-weight-bold font-24">'.$rowPlacement->subject.' ('.$rowPlacement->grade.') </span><br>
		                <small class="text-muted">Click here to add '.$rowPlacement->subject.' result</small>
		                </div>
			        </a>
		        </div>';
			}
			foreach($queryFetchRemote->result() as $rowRPlacement){
				$subject=$rowRPlacement->subject;
				$grade=$rowRPlacement->grade;
				$branch=$rowRPlacement->remotebranch;
				$output.='<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12" id="view_ThisSubjectResult_gs">
					<a href="javascript:void(0)" class="view_ThisSubjectResult_gs startFeedingResult" id="'.$max_year.'" value="'.$grade.'" name="'.$branch.'" title="'.$subject.'">
					<div class="StudentViewTextInfo">
		                <div class="badge badge-pill badge-info float-right" ><i class="fas fa-chevron-right"></i>
		                </div>
		                <span class="font-weight-bold font-24">'.$rowRPlacement->subject.' ('.$rowRPlacement->grade.' '.$branch.') </span><br>
		                <small class="text-muted">Click here to add '.$rowPlacement->subject.' result</small>
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
            	<i class="fas fa-check-circle"> </i> No class placement found.
        	</div></div>';
		}
		return $output;
	}
	public function load_grade_to_commbook($user,$mybranch,$max_year)
	{
      	$this->db->where(array('staff'=>$user));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->group_by('grade');
		$this->db->order_by('grade','ASC');
		$queryPlacement=$this->db->get('staffplacement');

		$this->db->where(array('staff'=>$user));
		$this->db->where(array('academicyear'=>$max_year));
		$queryFetchRemote=$this->db->get('staffremoteplacement');
		$output='';
		if($queryPlacement->num_rows()>0 || $queryFetchRemote->num_rows()>0){
			$output.='<div class="row">';
			foreach($queryPlacement->result() as $rowPlacement){
				$subject=$rowPlacement->subject;
				$grade=$rowPlacement->grade;
				$output.='<div class="col-xl-3 col-lg-3 col-6">
	              	<a href="#" class="btn btn-info btn-block btn-lg form-group startfetchingCommBook_subject" id="'.$max_year.'" value="'.$grade.'" name="'.$mybranch.'"> '.$rowPlacement->grade.'
	              	</a>
	            </div>';
			}
			foreach($queryFetchRemote->result() as $rowRPlacement){
				$subject=$rowRPlacement->subject;
				$grade=$rowRPlacement->grade;
				$branch=$rowRPlacement->remotebranch;
				$output.='<div class="col-xl-3 col-lg-3 col-6">
					<a href="#" class="btn btn-primary btn-block btn-lg startfetchingCommBook_subject_remote" id="'.$max_year.'" value="'.$grade.'" name="'.$mybranch.'"> '.$rowRPlacement->grade.'
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
            	<i class="fas fa-check-circle"> </i> No class placement found.
        	</div></div>';
		}
		return $output;
	}
	public function fetch_subject_of_thisGrade($user,$max_year,$gradesec,$branch)
	{
      	$this->db->where(array('staff'=>$user));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('grade'=>$gradesec));
		$this->db->group_by('subject');
		$this->db->order_by('subject','ASC');
		$queryPlacement=$this->db->get('staffplacement');
		$output='';
		if($queryPlacement->num_rows()>0 ){
			$output.='<div class="card">
			<div class="card-body"><div class="row">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
            	<button class="btn btn-default btn-lg backTo_MainPage"><h3> <i class="fas fa-chevron-left"  style="font-size: 30px;" ></i> Go Back</h3></button>
            	</div>';
			foreach($queryPlacement->result() as $rowPlacement){
				$subject=$rowPlacement->subject;
				$grade=$rowPlacement->grade;
            	$output.='<div class="col-md-6 col-12" id="view_ThisSubjectResult_gs">
					<a href="javascript:void(0)" class="view_ThisSubjectResult_gs starttypingCommBook_student" id="'.$subject.'" value="'.$grade.'" name="'.$branch.'" data-year="'.$max_year.'">
						<div class="card-body StudentViewTextInfo">
			            <div class="support-ticket media">
			              <div class="media-body">
			                <div class="badge badge-pill badge-default float-right" ><i class="fas fa-chevron-right"></i>
			                </div>
			                <span class="font-weight-bold font-24">'.$rowPlacement->subject.'</span><br>
			                <small class="text-muted">Click here to add '.$rowPlacement->subject.' communication book</small>
			              </div>
			            </div>
			          </div>
			        </a>
		        </div>';
			}
			$output.='</div></div></div>';
		}else{
			$output.='<div class="alert alert-light alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            	<i class="fas fa-check-circle"> </i> No class placement found.
        	</div></div>';
		}
		return $output;
	}
	public function fetch_communication_book_form($academicyear,$subject,$gradesec,$branch){
		$output='';
		$this->db->order_by('fname,mname,lname','ASC');
		$this->db->group_by('id');
		$this->db->where('gradesec',$gradesec);
		$this->db->where('branch',$branch);
		$this->db->where('status','Active');
		$this->db->where('isapproved','1');
		$this->db->where(array('academicyear'=>$academicyear));
		$query=$this->db->get('users');
		$output.='<form id="saveCommunicationForm">
		<div class="card">
		<h4 class="card-header">'.$gradesec.' <i class="fas fa-chevron-right"></i> '.$subject.'</h4>
		<div class="card-body StudentViewTextInfo">
		<div class="row">
		<input type="hidden" id="comGradesec" name="comGradesec" value="'.$gradesec.'">
		<input type="hidden" id="comSubject" name="comSubject" value="'.$subject.'">
		<input type="hidden" id="comBranch" name="comBranch" value="'.$branch.'">
		<input type="hidden" id="comAcademicYear" name="comAcademicYear" value="'.$academicyear.'">
		<div class="form-group col-lg-6 col-12 table-responsive" style="height:20vh">';
		$output .='<input type="checkbox" id="selectall" onClick="selectAllCom()"> 
		Select All</br>';
		foreach ($query->result() as $row) { 
			$output .='<input type="checkbox" id="username" name="stuNameComBook" value="'.$row->username.'"> '.$row->fname.' '.$row->mname.' '.$row->lname.'<br>';
		}
		$output.='</div>
		<div class="form-group col-lg-6 col-12">
          	<label>Select custom text to send from the following list</label>';
          	$this->db->order_by('id','DESC');
          	$query=$this->db->get('customcomtext');
		if($query->num_rows()>0){
			foreach ($query->result() as $fetchText) {
				$output.='<div class="activities">
		          <div class="activity">
		          	<div class="activity-icon bg-white text-dark">
		              <i class="fas fa-envelope-square"></i>
		            </div>
		            <div class="activity-detail">
		            <button class="btn btn-default useThisText" id="saveThisStudentToGroupEdit" value="'.$fetchText->comtext.'">
		              <p>'.$fetchText->comtext.'</p></button>
		            </div>
		          </div>
		        </div>';
			}
		}
        $output.='<textarea name="comNote" id="comNote" class="form-control comNote"> </textarea>
        </div>
        <div class="col-lg-12 col-12">
          <button class="btn btn-primary btn-md pull-right" type="submit" name="viewmark">Submit Communication Book</button>
        </div>';

		$output.='</div></div></div></form>';
		return $output;
	}
	public function fetch_thisFilter_Form($max_year,$gradesec,$subject,$branch){
		$output='';
		$this->db->where('qu.onoff','1');
		$this->db->where('qu.Academic_year',$max_year);
		$this->db->where('us.academicyear',$max_year);
		$this->db->where('us.usertype','Student');
		$this->db->where('us.status','Active');
		$this->db->where('us.isapproved','1');
		$this->db->where('us.gradesec',$gradesec);
		$this->db->where('us.isapproved','1');
		$this->db->group_by('us.grade,qu.term');
		$this->db->order_by('qu.term','DESC');
		$this->db->select('us.grade,qu.term');
        $this->db->from('quarter qu');
        $this->db->join('users us', 
            'us.grade = qu.termgrade');
        $query = $this->db->get();

		if($query->num_rows()>0){
			$output.='<form method="POST" id="fetch_last_form_ToFeed">
			<input type="hidden" id="feededSubject" value="'.$subject.'" />
			<input type="hidden" id="feededGrade" value="'.$gradesec.'" />
			<input type="hidden" id="feededBranch" value="'.$branch.'" />
			<input type="hidden" id="feededYear" value="'.$max_year.'" />';
			$output.='<button class="btn btn-default backToMainPage"> <h4>
			<i class="fas fa-chevron-left"  style="font-size: 30px;" ></i> Go Back</h4></button>
			<h4 class="card-header">'.$gradesec.' <i class="fas fa-chevron-right"></i> '.$subject.'</h4>';
			$output.='<div class="row">
				<div class="col-lg-3 col-6">';
					$output.='<select class="form-control" required="required" name="selectSeasonToFeed" id="selectSeasonToFeed">
					<option>--Select Season--</option>';
					foreach ($query->result() as $evavalue) {
						$output.='<option value='.$evavalue->term.'>'.$evavalue->term.'</option>';	
					}
					$output.='</select>';
				$output.='</div>';
				$output.='<div class="col-lg-3 col-6">
                    <div class="form-group">
                        <select class="form-control" required="required" name="selectEvaluationToFeed" id="selectEvaluationToFeed">
                          <option>--- Select Evaluation ---</option> 
                        </select>
                    </div>
                </div>';
                $output.='<div class="col-lg-3 col-6">
                    <div class="form-group">
                        <select class="form-control" required="required" name="selectAssesmentToFeed" id="selectAssesmentToFeed">
                          <option>--- Select Assesment ---</option> 
                        </select>
                    </div>
                </div>';
              	$output.='<div class="col-lg-3 col-6">
                    <div class="form-group">
                    	<input type="number" class="form-control" name="selectPercentageToFeed" id="selectPercentageToFeed" required="required" placeholder="Percentage/Weight...">
                    </div>
                </div>';
               	$output.='<div class="col-lg-12 col-12">
                    <div class="form-group">
                    	<button class="btn btn-primary pull-right btn-md" type="submit" name="startFeedingMark">Start Feeding Result</button>
                    </div>
                </div>';
			$output.='</div></form>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i> No season found. Please contact school system admin..
            </div></div>';
		}
		return $output;
	}
	function fetch_evaluation_on_quarterchange($quarter,$gradesec,$max_year){
		$this->db->where(array('evaluation.quarter'=>$quarter));
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where(array('evaluation.academicyear'=>$max_year));
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('evaluation.evname','ASC');
		$this->db->group_by('evaluation.evname');
		$this->db->select('*');
		$this->db->from('evaluation');
		$this->db->join('users',
		'users.grade = evaluation.grade');
		$query=$this->db->get();
		$output ='<option> </option>';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->eid.'">'.$row->evname.'</option>';
		}
		return $output;
	}
	function FilterPercentageAssesmentChange($evaluation,$gradesec,$max_year,$branch,$quarter,$subject){
		$this->db->where(array('schoolassesment.sasname'=>$evaluation));
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where(array('schoolassesment.academicyear'=>$max_year));
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('schoolassesment.assorder','ASC');
		$this->db->group_by('schoolassesment.sasname');
		$this->db->select('schoolassesment.saspercent');
		$this->db->from('schoolassesment');
		$this->db->join('users',
		'users.grade = schoolassesment.sasgrade');
		$query=$this->db->get();
		$output ='';
		$dateToday=date('Y-m-d');
		foreach ($query->result() as $row) { 
			$saspercent=$row->saspercent;
			$output.=$saspercent;
		}
		return $output;
	}
	function fetch_thisgrade_students_fornewexam($academicyear,$gradesec,$subject,$evaluation,$quarter,$assesname,$percentage,$branch){
		$output='';
		$this->db->where('academicyear',$academicyear);
		$this->db->where('status','Active');
		$this->db->where('isapproved','1');
		$this->db->where('usertype','Student');
		$this->db->where('gradesec',$gradesec);
		$this->db->where('branch',$branch);
		$this->db->order_by('fname,mname,lname','ASC');
		$query=$this->db->get('users');
		if($query->num_rows()>0){
			$output .='<button class="btn btn-default backToMainPage"> <h4>
			<i class="fas fa-chevron-left"  style="font-size: 30px;" ></i> Go Back</h4></button>
			<form method="POST" id="submit_student_result">
			<div class="table-responsive">
	        	<table class="table table-bordered table-hover" style="width:100%;">
	        		<thead>
	        		<tr>
	        			<th>No.</th>
	        		    <th>Result</th>
	            		<th>Name</th>
	            		<th>Grade</th>
	            		<th>Subject</th>
	           	 		<th>Season</th>
	            		<th>Assesment Name</th>
	            		<th>Percentage</th>
	        		</tr>
	        	</thead>';
	        $output.='<input type="hidden" id="academicyearResult" value="'.$academicyear.'"> ';
	        $output.='<input type="hidden" id="subjectResult" value="'.$subject.'"> ';
	        $output.='<input type="hidden" id="evaluationResult" value="'.$evaluation.'"> ';
	        $output.='<input type="hidden" id="quarterResult" value="'.$quarter.'"> ';
	        $output.='<input type="hidden" id="percentageResult" value="'.$percentage.'"> ';
	        $output.='<input type="hidden" id="assesnameResult" value="'.$assesname.'"> ';
	        $output.='<input type="hidden" id="markGradeSecResult" value="'.$gradesec.'"> ';
	        $output.='<input type="hidden" id="markGradeSecBranchResult" value="'.$branch.'"> ';
	        $no=1;
			foreach ($query->result() as $fetch_student) {
				$output.='<input type="hidden" id="stuidResult" 
				name="stuid_result" value="'.$fetch_student->id.'"> ';
				$output.='<tr class="'.$fetch_student->id.'">
				<td>'.$no.'.</td>
				<td><input type="text" onkeyup="chkMarkValue()" name="markvalue_result" id="resultvalue" class="form-control markvalue_result">
				 </td>';
				$output.='<td>'.$fetch_student->fname.' '.$fetch_student->mname.' '.$fetch_student->lname.'</td>';
				$output.='<td>'.$gradesec.'</td>';
				$output.='<td>'.$subject.'</td>';
				$output.='<td>'.$quarter.'</td>';
				$output.='<td>'.$assesname.'</td>';
				$output.='<td>'.$percentage.'</td> </tr>';
				$no++;
			}
			$output .='</table></div>';
			$output .='<button type="submit" id="SaveResult" class="btn btn-success pull-right">Submit Result </button></form>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i> No student found. Please contact school system admin..
            </div></div>';
		}
		return $output;
	}
	public function load_grade_to_appprovecommbook($user,$mybranch,$max_year)
	{
      	$this->db->where(array('staff'=>$user));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->group_by('grade');
		$this->db->order_by('grade','ASC');
		$queryPlacement=$this->db->get('directorplacement');
		$output='';
		if($queryPlacement->num_rows()>0){
			$output.='<div class="row">';
			foreach($queryPlacement->result() as $rowPlacement){
				$subject=$rowPlacement->subject;
				$grade=$rowPlacement->grade;
				$output.='<div class="col-xl-3 col-lg-3 col-6">
	              	<a href="#" class="btn btn-info btn-block btn-lg form-group startfetchingapproveCommBook_subject" id="'.$max_year.'" value="'.$grade.'" name="'.$mybranch.'"> '.$rowPlacement->grade.'
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
            	<i class="fas fa-check-circle"> </i> No class placement found.
        	</div></div>';
		}
		return $output;
	}
	public function fetch_subject_of_thisGrade_toapprove($user,$max_year,$gradesec,$branch)
	{
		$this->db->where(array('gradesec'=>$gradesec));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('Academic_Year'=>$max_year));
		$this->db->where('usertype','Student');
		$this->db->where('users.status','Active');
		$this->db->where('isapproved','1');
		$this->db->group_by('Subj_name,users.grade');
		$this->db->order_by('Subj_name','ASC');
		$this->db->select('Subj_name,users.grade,users.gradesec');
        $this->db->from('subject');
        $this->db->join('users', 
            'users.grade = subject.Grade');
        $queryPlacement = $this->db->get();
		$output='';
		if($queryPlacement->num_rows()>0 ){
			$output.='<div class="row">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
            	<button class="btn btn-default btn-lg backTo_MainPageApprove"> <i class="fas fa-chevron-left"  style="font-size: 30px;" ></i></button>
            	</div>';
			foreach($queryPlacement->result() as $rowPlacement){
				$subject=$rowPlacement->Subj_name;
				$grade=$rowPlacement->gradesec;
				$output.=' <div class="col-xl-3 col-lg-3 col-6">
              		<a href="#" class="text-white text-center startapprovingCommBook_student" id="'.$subject.'" value="'.$grade.'" name="'.$branch.'" data-year="'.$max_year.'">
              		<div class="card l-bg-green">
	                <div class="card-statistic-1">
	                  <div class="card-icon card-icon-large"><i class="fa fa-book-open"></i></div>
	                  <div class="card-content">
	                    <h4 class="card-title"> '.$subject.'</h4>
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
            	<i class="fas fa-check-circle"> </i> No class placement found.
        	</div></div>';
		}
		return $output;
	}
	function fetch_comBookhistory_of_thisGrade_toapprove($user,$subject,$gradesec,$branch_teacher,$max_year,$max_quarter){
		$this->db->select('users.profile,users.fname,users.mname,users.lname,users.username,communicationbook.id,communicationbook.quarter,communicationbook.comsubject,communicationbook.comnote,communicationbook.status,communicationbook.stuid,communicationbook.comgrade,communicationbook.comcommented, communicationbook.datecreated, communicationbook.byteacher, communicationbook.approvecom');
		$this->db->from('communicationbook');
		$this->db->join('directorplacement',
		'directorplacement.grade=communicationbook.comgrade');
		$this->db->join('users',
		'users.username=communicationbook.byteacher');
		$this->db->order_by('communicationbook.id','ASC');
		$this->db->where('communicationbook.comsubject',$subject);
		$this->db->where('communicationbook.comgrade',$gradesec);
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('users.branch',$branch_teacher);
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.quarter',$max_quarter);
		/*$this->db->where('communicationbook.approvecom','0');*/
		$this->db->where('communicationbook.combranch',$branch_teacher);
		$this->db->where('directorplacement.academicyear',$max_year);
		$this->db->where('directorplacement.staff',$user);
		$query=$this->db->get();
		$output='';
		$output.='<div class="row"> 
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
            	<button class="btn btn-default btn-lg backTo_MainPageApprove"> <i class="fas fa-chevron-left"  style="font-size: 30px;" ></i></button>
            	</div>
			</div>';
		if($query->num_rows()>0){
			foreach($query->result() as $bookSent){
				$stuid=$bookSent->stuid;
				$byteacher=$bookSent->byteacher;
				$id=$bookSent->id;
				$statusCheck=$bookSent->status;
				$output .='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12" >
		      	<div class="chat-box">';
		      	if($statusCheck=='1'){
		          $output.='<div class="chat incoming">';
		          	if($bookSent->profile == ''){
                      	$output.='<a href="#"><img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" class="border-circle"></a>';
                    }else{
                      	$output.='<a href="#"><img alt="Photo" src="'.base_url().'/profile/'.$bookSent->profile.'" class="border-circle"></a>';
                    }
		           $output.='
		           <div class="details">
		             <p class="p">'.$bookSent->comnote.'<span class="text-success"> <i class="fa fa-check-double"></i> seen</span><br>
		             <small class="time text-muted"> '.$bookSent->fname.' '.$bookSent->mname.' '.$bookSent->datecreated.' </small>
		             </p>

		           </div>
		         </div>';	
		        }else{
		        	$output.='<div class="chat incoming" id="approveThisComBook'.$id.'">';
		        	if($bookSent->profile == ''){
                      	$output.='<a href="#"><img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" class="border-circle"></a>';
                    }else{
                      	$output.='<a href="#"><img alt="Photo" src="'.base_url().'/profile/'.$bookSent->profile.'" class="border-circle"></a>';
                    }
		           	$output.='<div class="details">
		             	<p class="p">'.$bookSent->comnote.' ';
		             	if($bookSent->approvecom=='0'){
		             		$output.='<a href="#" class="text-info approvComBook" value="'.$bookSent->id.'"><i class="fas fa-check"></i> Approve</a>
		             		<a href="#" class="text-danger rejectThisComBook" value="'.$bookSent->id.'"><i class="fas fa-times-circle"></i> Reject</a>';
		             	}else{
		             		$output.='<a href="#" class="text-success" value="'.$bookSent->id.'"><i class="fas fa-check-circle"></i> Approved</a>';
		             	}
		             	$output.='<br>
		             	<small class="time text-muted"> '.$bookSent->fname.' '.$bookSent->mname.'  <small class="time text-muted"> '.$bookSent->datecreated.' </small></small>
		             	</p>
		           	</div>
		         </div>';
		        }				
			    $queryReply=$this->db->query("select cr.approvereplay,cr.id,cr.replyby, cr.seenstatus, cr.replytext, cr.datereplay, us.profile,us.fname,us.mname from combookreplaystudent as cr cross join users as us where replyid='$id' and us.username=cr.replyby group by cr.id order by cr.id ASC ");
			      if($queryReply->num_rows()>0){
			      	foreach($queryReply->result() as $replyAnswer){
				      	$output.='<div class="chat incoming" id="approverejectReplay'.$replyAnswer->id.'">';
			      		if($replyAnswer->profile == ''){
	                      	$output.='<a href="#"><img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" class="border-circle"></a>';
	                    }else{
	                      	$output.='<a href="#"><img alt="Photo" src="'.base_url().'/profile/'.$replyAnswer->profile.'" class="border-circle"></a>';
	                    }
	                    if($replyAnswer->seenstatus=='1'){
	                    	$output.='<div class="details">
					             <p class="p">'.$replyAnswer->replytext.'
					             <small class="time text-muted"> ';
					             $output.='<span class="text-success"><i class="fa fa-check-double"></i> seen</span>';
					            $output.='<br> '.$replyAnswer->fname.' '.$replyAnswer->mname.' '.$replyAnswer->datereplay.'</small>
					             </p>
					           </div>';
	                    }else{
			                if($replyAnswer->approvereplay=='0'){
					           $output.='<div class="details">
					             <p class="p">'.$replyAnswer->replytext.'
					             <small class="time text-muted"> ';
					             $output.='<a href="#" class="text-info approvReplayComBook" value="'.$replyAnswer->id.'"><i class="fas fa-check"></i> Approve</a>
			             		<a href="#" class="text-danger rejectThisReplayComBook" value="'.$replyAnswer->id.'"><i class="fas fa-times-circle"></i> Reject</a>';
					            $output.='<br> '.$replyAnswer->fname.' '.$replyAnswer->mname.' '.$replyAnswer->datereplay.'</small>
					             </p>
					           </div>';
					       	}else{
					       		$output.='<div class="details">
					             <p class="p">'.$replyAnswer->replytext.'
					             <small class="time text-muted"> ';
					             $output.='<a href="#" class="text-success" value="'.$replyAnswer->id.'"><i class="fas fa-check-circle"></i> Approved</a>';
					            $output.='<br> '.$replyAnswer->fname.' '.$replyAnswer->mname.' '.$replyAnswer->datereplay.'</small>
					             </p>
					           </div>';
					       	}  
					    }	
				        $output.='</div>';
			     	}
			    }
		      	$output.='</div>';
				$output.='</div>';	
			}
			
		}else{
			$output.='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12"><div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div></div>';
		}
		$output.='</div>';
		return $output;
	}
	function fetch_comBookhistory_of_thisGrade_nottoapprove($user,$subject,$gradesec,$branch_teacher,$max_year,$max_quarter){
		$this->db->select('users.profile,users.fname,users.mname,users.lname,users.username,communicationbook.id,communicationbook.quarter,communicationbook.comsubject,communicationbook.comnote,communicationbook.status,communicationbook.stuid,communicationbook.comgrade,communicationbook.comcommented, communicationbook.datecreated, communicationbook.byteacher, communicationbook.approvecom');
		$this->db->from('communicationbook');
		$this->db->join('directorplacement',
		'directorplacement.grade=communicationbook.comgrade');
		$this->db->join('users',
		'users.username=communicationbook.byteacher');
		$this->db->order_by('communicationbook.id','ASC');
		$this->db->where('communicationbook.comsubject',$subject);
		$this->db->where('communicationbook.comgrade',$gradesec);
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('users.branch',$branch_teacher);
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.quarter',$max_quarter);
		/*$this->db->where('communicationbook.approvecom','0');*/
		$this->db->where('communicationbook.combranch',$branch_teacher);
		$this->db->where('directorplacement.academicyear',$max_year);
		$this->db->where('directorplacement.staff',$user);
		$query=$this->db->get();
		$output='';
		$output.='<div class="row"> 
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
            	<button class="btn btn-default btn-lg backTo_MainPageApprove"> <i class="fas fa-chevron-left"  style="font-size: 30px;" ></i></button>
            	</div>
			</div>';
		if($query->num_rows()>0){
			foreach($query->result() as $bookSent){
				$stuid=$bookSent->stuid;
				$byteacher=$bookSent->byteacher;
				$id=$bookSent->id;
				$statusCheck=$bookSent->status;
				$output .='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12" >
		      	<div class="chat-box">';
		      	if($statusCheck=='1'){
		          $output.='<div class="chat incoming">';
		          	if($bookSent->profile == ''){
                      	$output.='<a href="#"><img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" class="border-circle"></a>';
                    }else{
                      	$output.='<a href="#"><img alt="Photo" src="'.base_url().'/profile/'.$bookSent->profile.'" class="border-circle"></a>';
                    }
		           $output.='
		           <div class="details">
		             <p class="p">'.$bookSent->comnote.'<span class="text-success"> <i class="fa fa-check-double"></i> seen</span><br>
		             <small class="time text-muted"> '.$bookSent->fname.' '.$bookSent->mname.' '.$bookSent->datecreated.' </small>
		             </p>

		           </div>
		         </div>';	
		        }else{
		        	$output.='<div class="chat incoming" id="approveThisComBook'.$id.'">';
		        	if($bookSent->profile == ''){
                      	$output.='<a href="#"><img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" class="border-circle"></a>';
                    }else{
                      	$output.='<a href="#"><img alt="Photo" src="'.base_url().'/profile/'.$bookSent->profile.'" class="border-circle"></a>';
                    }
		           	$output.='<div class="details">
		             	<p class="p">'.$bookSent->comnote.'<br>
		             	<small class="time text-muted"> '.$bookSent->fname.' '.$bookSent->mname.'  <small class="time text-muted"> '.$bookSent->datecreated.' </small></small>
		             	</p>
		           	</div>
		         </div>';
		        }				
			    $queryReply=$this->db->query("select cr.approvereplay,cr.id,cr.replyby, cr.seenstatus, cr.replytext, cr.datereplay, us.profile,us.fname,us.mname from combookreplaystudent as cr cross join users as us where replyid='$id' and us.username=cr.replyby group by cr.id order by cr.id ASC ");
			      if($queryReply->num_rows()>0){
			      	foreach($queryReply->result() as $replyAnswer){
				      	$output.='<div class="chat incoming" id="approverejectReplay'.$replyAnswer->id.'">';
			      		if($replyAnswer->profile == ''){
	                      	$output.='<a href="#"><img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" class="border-circle"></a>';
	                    }else{
	                      	$output.='<a href="#"><img alt="Photo" src="'.base_url().'/profile/'.$replyAnswer->profile.'" class="border-circle"></a>';
	                    }
	                    if($replyAnswer->seenstatus=='1'){
	                    	$output.='<div class="details">
					             <p class="p">'.$replyAnswer->replytext.'
					             <small class="time text-muted"> ';
					             $output.='<span class="text-success"><i class="fa fa-check-double"></i> seen</span>';
					            $output.='<br> '.$replyAnswer->fname.' '.$replyAnswer->mname.' '.$replyAnswer->datereplay.'</small>
					             </p>
					           </div>';
	                    }else{
				       		$output.='<div class="details">
				             <p class="p">'.$replyAnswer->replytext.'
				             <small class="time text-muted"> ';
				            $output.='<br> '.$replyAnswer->fname.' '.$replyAnswer->mname.' '.$replyAnswer->datereplay.'</small>
				             </p>
				           </div>';
					       	
					    }	
				        $output.='</div>';
			     	}
			    }
		      	$output.='</div>';
				$output.='</div>';	
			}
			
		}else{
			$output.='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12"><div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div></div>';
		}
		$output.='</div>';
		return $output;
	}
	function class_change_evaluation_my_subject($user,$mybranch,$max_year,$max_quarter){
		$output='';/*
		$queryPlacement=$this->db->query("SELECT  from staffplacement as st cross join users as us where st.grade=us.gradesec and st.academicyear ='$max_year' and st.staff='$user' and us.academicyear='$max_year' GROUP BY st.grade , st.subject ORDER BY st.grade ASC");*/

		$this->db->where('st.staff',$user);
		$this->db->where('st.academicyear',$max_year);
		$this->db->where('us.academicyear',$max_year);
		$this->db->group_by('st.grade , st.subject');
		$this->db->order_by('st.grade','ASC');
		$this->db->select('us.gradesec,us.grade,st.subject');
        $this->db->from('staffplacement st');
        $this->db->join('users us', 
            'us.gradesec = st.grade');
        $queryPlacement = $this->db->get();
		if($queryPlacement->num_rows()>0){
			$output.='<span class="text-warning">Note:-Make sure sum of each subject percentage is 100% for each grade. </span>
			<div class="table-responsive" >
          	<table class="table table-striped" width="100%">
            <tr>
              <th>Grade</th>
              <th>Subject</th>
              <th>Percentage</th>
              <th>Sum</th>
            </tr>';
			foreach($queryPlacement->result() as $row){
				$sumTotal=0;
				$grades=$row->grade;
				$subjects=$row->subject;
				$gradesec=$row->gradesec;
				$output.='<tr><td>'.$gradesec.'</td>';
				$output.='<td>'.$subjects.'</td>';
				$this->db->where('academicyear',$max_year);
				$this->db->where('grade',$grades);
				$this->db->where('quarter',$max_quarter);
				$this->db->group_by('evname');
				$queryEvaluation=$this->db->get('evaluation');
				/*$queryEvaluation=$this->db->query("select * from evaluation where academicyear='$max_year' and grade='$grades' and quarter='$max_quarter' group by evname ");*/
				if($queryEvaluation->num_rows()>0){
					$output.='<td>';
					foreach($queryEvaluation->result() as $rowEva){
						$assesname=$rowEva->evname;
						$this->db->where('academicyear',$max_year);
						$this->db->where('customasses',$assesname);
						$this->db->where('customquarter',$max_quarter);
						$this->db->where('customsubject',$subjects);
						$this->db->where('customgrade',$grades);
						$queryCustomEvaluation=$this->db->get('evaluationcustom');

						/*$queryCustomEvaluation=	$this->db->query("select * from evaluationcustom where academicyear='$max_year' and customquarter='$max_quarter' and customasses='$assesname' and customsubject='$subjects' and customgrade='$grades' ");	*/
						if($queryCustomEvaluation->num_rows()>0){
							$rowPercent=$queryCustomEvaluation->row();
							$fPercent=$rowPercent->custompercent;
							$output.='<a href="#" data-toggle="modal" data-target="#change_my_evaluation" class="btn btn-default edit_this_subject_percentage" id="'.$assesname.'" data-subject-name="'.$subjects.'" data-season-name="'.$max_quarter.'" data-grade-name="'.$grades.'" data-percent-value="'.$fPercent.'" value="'.$max_year.'"> <span class="badge badge-info">'.$rowEva->evname.'=>'.$fPercent.' <div class="bullet"></div> <i class="fas fa-pen-alt"></i></button></span></a>';
							$sumTotal=$sumTotal + $fPercent;
						}else{
							$output.='<a href="#" data-toggle="modal" data-target="#change_my_evaluation" class="btn btn-default edit_this_subject_percentage" id="'.$assesname.'" data-subject-name="'.$subjects.'" data-season-name="'.$max_quarter.'" data-grade-name="'.$grades.'" data-percent-value="'.$rowEva->percent.'" value="'.$max_year.'"> <span class="badge badge-info">'.$rowEva->evname.'=>'.$rowEva->percent.' <div class="bullet"></div> <i class="fas fa-pen-alt"></i></button></span></a>';
							$sumTotal=$sumTotal + $rowEva->percent;
						}				
					}
					$output.='</td>';
				}
				if($sumTotal=='100'){
					$output.='<td class="text-success">'.$sumTotal.'</td>';
				}else{
					$output.='<td class="text-danger">'.$sumTotal.'</td>';
				}
				$output.='</tr>';
			}
			$output.='</table></div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No placement found.
            </div></div>';
		}
		return $output;
	}
	function edit_this_subject_percentage($user,$grade,$subject,$season,$year,$customasses,$percentValue){
		$output='';
		$output .='<input type="hidden" class="my_changed_grade" value="'.$grade.'">';
		$output .='<input type="hidden" class="my_changed_subject" value="'.$subject.'">';
		$output .='<input type="hidden" class="my_changed_season" value="'.$season.'">';
		$output .='<input type="hidden" class="my_changed_year" value="'.$year.'">';
		$output .='<input type="hidden" class="my_changed_assesment" value="'.$customasses.'">';
		$output .='<input type="hidden" class="my_changed_percentage" value="'.$percentValue.'">';
		$output .='<div class="card"><div class="card-body"><div class="row">
		<div class="col-lg-12 col-12"><span class="text-muted"> '.$year.' <i class="fas fa-angle-double-right"></i> '.$season.' <i class="fas fa-angle-double-right"></i> '.$grade.' <i class="fas fa-angle-double-right"></i> '.$subject.' <i class="fas fa-angle-double-right"></i> '.$customasses.'</span></div>
			<div class="col-lg-8 col-12">
				<input class="form-control changed_evaluation_percent" id="changed_evaluation_percent" type="number" value="'.$percentValue.'">
			</div>
			<div class="col-lg-4 col-12">
				<button class="btn btn-primary pull-right" id="submit_eva_setting">Save Changes</button>
			</div>
			</div></div></div>';
		return $output;
	}

}