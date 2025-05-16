<?php
class commbook_model extends CI_Model{
	function fetch_school(){
		$query=$this->db->get('school');
		return $query->result();
	}
	function fetchcomMySubject($max_year,$grade){
		$this->db->order_by('Subj_name','ASC');
		$this->db->where(array('Academic_Year'=>$max_year));
		$this->db->where(array('Grade'=>$grade));
		$this->db->where(array('student_view'=>'1'));
		$query=$this->db->get('subject');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="row">';
			foreach($query->result() as $subjname){
				$output.='<div class="col-md-6 col-12" id="view_ThisSubjectResult_gs">
					<a href="javascript:void(0)" class="view_ThisSubjectResult_gs" id="viewThisSubjectComBook" value="'.$subjname->Subj_name.'">
						<div class="card-body StudentViewTextInfo">
			            <div class="support-ticket media">
			              <div class="media-body">
			                <div class="badge badge-pill badge-primary float-right" ><i class="fas fa-chevron-right"></i>
			                </div>
			                <span class="font-weight-bold font-24">'.$subjname->Subj_name.'</span>
			                <br>
			                <small class="text-muted">Click here to see '.$subjname->Subj_name.' conversation</small>
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
	function fetchMyCommBookApproved($username,$subject,$max_year){
		$this->db->select('users.profile,users.fname,users.mname,users.lname,users.username,communicationbook.id,communicationbook.quarter,communicationbook.comsubject,communicationbook.comnote,communicationbook.status,communicationbook.stuid,communicationbook.comgrade,communicationbook.byteacher,communicationbook.datecreated');
		$this->db->from('communicationbook');
		$this->db->join('users',
		'users.username=communicationbook.byteacher');
		$this->db->order_by('communicationbook.id','DESC');
		$this->db->group_by('communicationbook.id');
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.stuid',$username);
		$this->db->where('communicationbook.comsubject',$subject);
		$this->db->where('communicationbook.approvecom','1');
		$this->db->or_where('communicationbook.byteacher',$username);
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.comsubject',$subject);
		$this->db->where('communicationbook.approvecom','1');
		$query=$this->db->get();

		$output='';
		$output.='<button class="btn btn-default StudentViewTextInfo backTo_myMainPage font-weight-bold font-22" id="backTo_myMainPage" ><i class="fas fa-chevron-left" style="font-size:30px"></i> Go Back</button><div class="dropdown-divider"></div> ';
		if($query->num_rows()>0){
			$this->db->where('communicationbook.academicyear',$max_year);
			$this->db->where('communicationbook.comsubject',$subject);
			$this->db->where('communicationbook.stuid',$username);
			$this->db->set('status','1');
			$queryUpdate=$this->db->update('communicationbook');
			$output.='';
			foreach($query->result() as $bookSent){
				$stuid=$bookSent->stuid;
				$id=$bookSent->id;
				$byteacher=$bookSent->byteacher;
				$statusCheck=$bookSent->status;
				if($statusCheck=='1' && $byteacher==$username){
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
		        	if($byteacher==$username){
		        		$output.='<div class="message-gs-full sender-message-gs">
			        		<div class="support-ticket media">';
			           			$output.='<div class="media-body">
			             			<p class="p">'.$bookSent->comnote.' ';
						             	if($bookSent->status=='0' && $bookSent->byteacher==$username){
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
						             	if($bookSent->status=='0' && $bookSent->byteacher==$username){
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
		    }
		    if($stuid==$username){
				$output.='<div id="replyedTextHere'.$id.'"> </div>
      			<div class="chat-box">
			        <div class="card-footer chat-form">
		            	<input type="text" name="replayComText" class="form-control replayComText" id="replayComText'.$id.'" placeholder="Type a reply here for '.$bookSent->fname.' '.$bookSent->mname. '('.$bookSent->comsubject.')..." >
		            	<button class="btn btn-info sendMyReply" value="'.$id.'" data-subject="'.$bookSent->comsubject.'"> <i class="far fa-paper-plane"></i> </button>
			        </div>
			    </div>';
			}
		}else{
			$output.='<div class="alert alert-light alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div>';
		}
		return $output;

	}
	function fetchMyCommBook($username,$subject,$max_year){
		$this->db->select('users.branch,users.profile,users.fname,users.mname,users.lname,users.username,communicationbook.id,communicationbook.quarter,communicationbook.comsubject,communicationbook.comnote,communicationbook.status,communicationbook.stuid,communicationbook.comgrade,communicationbook.byteacher,communicationbook.datecreated');
		$this->db->from('communicationbook');
		$this->db->join('users',
		'users.username=communicationbook.byteacher');
		$this->db->order_by('communicationbook.id','DESC');
		$this->db->group_by('communicationbook.id');
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.stuid',$username);
		$this->db->where('communicationbook.comsubject',$subject);
		$this->db->or_where('communicationbook.byteacher',$username);
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.comsubject',$subject);
		$query=$this->db->get();
		$output='';
		$output.='<button class="btn btn-default StudentViewTextInfo backTo_myMainPage font-weight-bold font-22" id="backTo_myMainPage" ><i class="fas fa-chevron-left" style="font-size:30px"></i> Go Back</button><div class="dropdown-divider"></div> ';
		if($query->num_rows()>0){
			$this->db->where('communicationbook.academicyear',$max_year);
			$this->db->where('communicationbook.comsubject',$subject);
			$this->db->where('communicationbook.stuid',$username);
			$this->db->set('status','1');
			$queryUpdate=$this->db->update('communicationbook');
			$output.='';
			foreach($query->result() as $bookSent){
				$stuid=$bookSent->stuid;
				$id=$bookSent->id;
				$byteacher=$bookSent->byteacher;
				$statusCheck=$bookSent->status;
				if($statusCheck=='1' && $byteacher==$username){
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
		        	if($byteacher==$username){
		        		$output.='<div class="message-gs-full sender-message-gs">
			        		<div class="support-ticket media">';
			           			$output.='<div class="media-body">
			             			<p class="p">'.$bookSent->comnote.' ';
						             	if($bookSent->status=='0' && $bookSent->byteacher==$username){
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
						             	if($bookSent->status=='0' && $bookSent->byteacher==$username){
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
		    }
		    if($stuid==$username){
				$output.='<div id="replyedTextHere'.$id.'"> </div>
      			<div class="chat-box">
			        <div class="card-footer chat-form">
		            	<input type="text" name="replayComText" class="form-control replayComText" id="replayComText'.$id.'" placeholder="Type a reply here for '.$bookSent->fname.' '.$bookSent->mname. '('.$bookSent->comsubject.')..." >
		            	<button class="btn btn-info sendMyReply" value="'.$id.'" data-subject="'.$bookSent->comsubject.'"> <i class="far fa-paper-plane"></i> </button>
			        </div>
			    </div>';
			}
		}else{
			$output.='<div class="alert alert-light alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div>';
		}
		return $output;
	}
	function myNew_CommunicationBook($branch,$gradesec,$username,$max_year){
		$output='';
		$this->db->where('users.branch',$branch);
		$this->db->where('hoomroomplacement.branch',$branch);
		$this->db->where('hoomroomplacement.roomgrade',$gradesec);
		$this->db->like('hoomroomplacement.academicyear',$max_year);
		$this->db->select('hoomroomplacement.teacher,users.fname,users.mname,users.lname');
        $this->db->from('hoomroomplacement');
        $this->db->join('users', 
            'users.username = hoomroomplacement.teacher');
        $query = $this->db->get();
		if($query->num_rows()>0){
			$output.='<div class="row" id="sendindNewComBookStatus"> <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
			<option></option>';
			foreach($query->result() as $bookSent){
				$teacher=$bookSent->teacher;
				$output.='<div class="pretty p-icon p-smooth">
	                <input type="checkbox" name="sendindNewComBookStatus" class="sendindNewComBookStatus" id="" value="'.$teacher.'" >
	                <div class="state p-success">
	                <i class="icon fa fa-check"></i>
	                    <label></label>'.$bookSent->fname.' '.$bookSent->mname.'
	                </div>
			    </div>';
			}
			$output.='</div>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
			<div class="card">
				<div class="card-body StudentViewTextInfo">
		      	<div class="chat-box"> ';
				$output.='
	          			<div class="chat-box">
					        <div class="card-footer chat-form">
				            	<input type="text" name="sendMyNewComBookReplyText" class="form-control sendMyNewComBookReplyText" id="sendMyNewComBookReplyText" placeholder="Type a text here ..." >
				            	<button class="btn btn-info sendMyNewComBookReply"> <i class="far fa-paper-plane"></i> </button>
					        </div>
					    </div>
	            	</div>
	            	</div> 
	            </div>
			 </div>
			</div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No homeroom teacher found.
            </div></div>';
		}
		return $output;
	}
	function fetch_session_user($user){
		$this->db->where('username',$user);
		$this->db->group_by('username');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetch_staff_from_placement($subject,$gradesec,$branch,$max_year){
		$this->db->where('st.subject',$subject);
        $this->db->where('st.grade',$gradesec);
        $this->db->where('st.academicyear',$max_year);
        $this->db->where('us.branch',$branch);
        $this->db->where('us.status','Active');
        $this->db->where('us.isapproved','1');
        $this->db->group_by('st.staff');
        $this->db->select('st.staff,st.date_created,st.lessons_week, st.subject,st.grade,us.profile,us.fname,us.mname,us.lname,st.status');
        $this->db->from('staffplacement st');
        $this->db->join('users us', 
            'us.username = st.staff');
        $queryPlacement = $this->db->get('');
        if($queryPlacement->num_rows()>0){
        	return $queryPlacement->result();
        }else{
        	return false;
        }
	}

}