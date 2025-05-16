<?php
class chat_model extends CI_Model{
	function fetch_users_online($user){
		$this->db->order_by('fname,mname,lname','ASC');
    $this->db->group_by('users.unique_id');
		$this->db->where_not_in('unique_id',$user);
		$this->db->select('*');
    $this->db->from('user_online');
    $this->db->join('users', 
        'users.unique_id = user_online.session');
    $query = $this->db->get();
    $output ='';
    foreach ($query->result() as $row) {
      $senderId=$row->session;
      $queryNewMessage=$this->db->query("select count(receiver_id) as newCount from chat where receiver_id='$user' and sender_id='$senderId' and status='0' ");
      $rowQuery=$queryNewMessage->row();
      $fetchNum=$rowQuery->newCount;

      if($fetchNum > 0){
        $output .='<ul class="k chat-list list-unstyled m-b-0">
        <li class="clearfix">
        <div class="selectChatId" value="'.$row->unique_id.'">
        <a href="#" class="nav-link nav-link-lg">
        <img src="'.base_url().'/profile/'.$row->profile.'" alt="Image" class="border-circle">
        <div class="about">
        <div class="name">
        '.$row->fname.' '.$row->mname.'
        </div>
        <div class="text-success text-small font-600-bold">
        <i class="fas fa-circle"></i> Online';
        $output.='<span class="badge badge-danger">'.$fetchNum.'</span>';
        $output.='</div> </div> </a> </div> </li> </ul>';
      }else{
        $output .='<ul class="k chat-list list-unstyled m-b-0">
        <li class="clearfix">
        <div class="selectChatId" value="'.$row->unique_id.'">
        <a href="#" class="nav-link nav-link-lg">
        <img src="'.base_url().'/profile/'.$row->profile.'" alt="Image" class="border-circle">
        <div class="about">
        <div class="name">
        '.$row->fname.' '.$row->mname.'
        </div>
        <div class="text-success text-small font-600-bold">
          <i class="fas fa-circle"></i> Online
        </div> </div> </a> </div> </li> </ul>';
      }
    }
    return $output;
	}
  function unseenMessage($user){
    $output='';
    $this->db->order_by('fname,mname,lname','ASC');
    $this->db->where('chat.status','0');
    $this->db->where('chat.receiver_id',$user);
    $this->db->select('*');
    $this->db->from('chat');
    $this->db->join('users', 
        'users.unique_id = chat.sender_id');
    $queryUnseesn = $this->db->get();
    
    foreach ($queryUnseesn->result() as $row) {
      $senderId=$row->sender_id;
      $queryNewMessage=$this->db->query("select count(receiver_id) as newCount from chat where receiver_id='$user' and sender_id='$senderId' and status='0' ");
      $rowQuery=$queryNewMessage->row();
      $fetchNum=$rowQuery->newCount;
        $output .='<ul class="k chat-list list-unstyled m-b-0">
        <li class="clearfix">
        <div class="selectChatId" value="'.$row->unique_id.'">
        <a href="#" class="nav-link nav-link-lg">
        <img src="'.base_url().'/profile/'.$row->profile.'" alt="Image" class="border-circle">
        <div class="about">
        <div class="name">
        '.$row->fname.' '.$row->mname.'
        </div>
        <div class="text-success text-small font-600-bold">
        <i class="fas fa-circle"></i> Online';
        $output.='<span class="badge badge-danger">'.$fetchNum.'</span>';
        $output.='</div> </div> </a> </div> </li> </ul>';
      
    }
    return $output;
  }
	function insertchatmsg($outgoing_id,$msg,$session_id){
		$output='';
		$data=array(
			'sender_id'=>$session_id,
			'chat_msg'=>$msg,
			'receiver_id'=>$outgoing_id,
			'chat_date'=>date('M-d-Y'),
			'status'=>'0'
		);
		$this->db->insert('chat',$data);
		$this->db->where('unique_id',$outgoing_id);
    $query = $this->db->get('users');
    $output ='';
    foreach ($query->result() as $row) { 
      $output .='<div id="closeablecard" class="cardChat"><div class="chat">
        <div class="chat-header clearfix">
          <div class="pull-Left">
            <div class="chat-num-messages">
            <button data-dismiss="alert" data-target="#closeablecard" type="button" class="close text-danger" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
              <a class="receiver_id" id="'.$row->id.'" value=""> <img src="'.base_url().'/profile/'.$row->profile.'" alt="Image" class="border-circle">
              </a>
            </div>
             <h5 class="card-title">'.$row->fname.' '.$row->mname.'</h5>
          </div>
          <small class="text-success">Active Now</small>
        </div>
      </div>
      <div class="chat-box table-responsive"  style="height:auto;max-height:35vh">
      <div class="card-body">';
      $querymsg=$this->db->query("select chat.chat_msg,chat.status, users.profile, chat.sender_id, chat.receiver_id from chat cross join users ON users.username = chat.sender_id where sender_id='$session_id' and receiver_id='$outgoing_id' or sender_id='$outgoing_id' and receiver_id='$session_id' order by chatid ASC ");
			foreach ($querymsg->result() as $chatmsg) {
				$sender=$chatmsg->sender_id;
				$receiver=$chatmsg->receiver_id;
				if($sender===$session_id){
					$output.='<div class="chat incoming">
            <a href="#"> <img src="'.base_url().'/profile/'.$chatmsg->profile.'" alt="Image" class="border-circle"> </a>
            <div class="details">
             <p>'.$chatmsg->chat_msg.'</p>
            </div>
         </div>';
				}else{
          $output.='<div class="chat outgoing">
           <div class="details">
             <p class="p">'.$chatmsg->chat_msg.'</p>
           </div>
         </div>';
					
				}
			}
      $output.='</div>
      <div class="card-footer chat-form">
        <form action="#" class="typing-area">
        <input type="text" class="outgoing_id" name="incoming_id" value="'.$row->unique_id.'" hidden>
          <input type="text" name="input-field" class="form-control input-field" placeholder="Type a message">
          <button class="btn btn-info send">
            <i class="far fa-paper-plane"></i>
          </button>
        </form>
      </div>
    </div></div>';
    }
		return $output;
	}
	function fetchuser_tochat($session_id,$outgoing_id){
		$this->db->where('unique_id',$outgoing_id);
    $query = $this->db->get('users');
    $output ='';
    foreach ($query->result() as $row) { 
      $output .='<div id="closeablecard" class="cardChat">
      <div class="chat">
        <div class="chat-header clearfix">
        <div class="pull-Left">
          <div class="chat-num-messages">
          <button data-dismiss="alert" data-target="#closeablecard" type="button" class="close text-danger" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
           <a class="receiver_id" id="'.$row->id.'" value=""> <img src="'.base_url().'/profile/'.$row->profile.'" alt="Image" class="border-circle">
           </a>
          </div>
          <h5 class="card-title">'.$row->fname.' '.$row->mname.'</h5>
        </div>
        <small class="text-success">Active Now</small>
      </div>
      </div>
      <div class="chat-box table-responsive"  style="height:auto;max-height:35vh">
      <div class="card-body">';
      $querymsg=$this->db->query("select chat.chat_msg,chat.status, users.profile, chat.sender_id, chat.receiver_id from chat cross join users ON users.username = chat.sender_id where sender_id='$session_id' and receiver_id='$outgoing_id' or sender_id='$outgoing_id' and receiver_id='$session_id' order by chatid ASC ");
			foreach ($querymsg->result() as $chatmsg) {
				$sender=$chatmsg->sender_id;
				$receiver=$chatmsg->receiver_id;
				if($sender===$session_id){
					$output.='<div class="chat incoming">
          <a href="#"> <img src="'.base_url().'/profile/'.$chatmsg->profile.'" alt="Image" class="border-circle"> </a>
           <div class="details"> <p>'.$chatmsg->chat_msg.' ';
           if($chatmsg->status = '1'){
            $output.='<i class="fas fa-check-circle"></i>';
            }else{
              $output.='<i class="fas fa-exclamation-circle"></i>';
            }
            $output.='</p></div> </div>';
				}else{
          $output.='<div class="chat outgoing">
          <div class="details">
          <p class="p">'.$chatmsg->chat_msg.'</p></div> </div>';
				}
			}
      $output.='</div>
        <div class="card-footer chat-form">
          <form action="#" class="typing-area">
          <input type="text" class="outgoing_id" name="incoming_id" value="'.$row->unique_id.'" hidden>
            <input type="text" name="input-field" class="form-control input-field" placeholder="Type a message">
            <button class="btn btn-info send">
              <i class="far fa-paper-plane"></i>
            </button>
          </form>
        </div>
      </div></div>';
    }
    $this->db->where('sender_id',$outgoing_id);
    $this->db->where('receiver_id',$session_id);
    $this->db->where('status','0');
    $this->db->set('status','1');
    $this->db->update('chat');
    return $output;
	}
  function fetch_allnotification($max_year){
    $this->db->where('academicyear',$max_year);
    $this->db->order_by('id','DESC');
    $this->db->select('*');
    $this->db->from('users_registration_request');
    $this->db->where('isapproved','0');
    $query = $this->db->get();
    $output='';
    foreach ($query->result() as $row) {
      $output .='<a href="'.base_url().'newstaffs/" class="dropdown-item"> 
        <span class="dropdown-item-avatar text-white">';
        if($row->profile!=''){
          $output.='<img alt="image" src="'.base_url().'/profile/'.$row->profile.'" class="rounded-circle" style="height:50px;width:50px">';
        }else{
          $output.='<img alt="image" src="'.base_url().'/profile/defaultProfile.png" class="rounded-circle" style="height:50px;width:50px">';  
        }
        $output.='</span> 
        <span class="dropdown-item-desc"> 
          <span class="message-user">
            '.$row->fname.'&nbsp'.$row->mname.'   
          </span>
          <span class="time messege-text">
          '.$row->usertype.' Registration Request at '.$row->branch.' branch  
         </span>
          <span class="time">
            '.$row->dateregister.' 
          </span>
        </span>
      </a> ';
    }
    return $output;
  }
  function fetch_all_incident($max_year){
    $this->db->where('report_status','0');
    $this->db->where('users.academicyear',$max_year);
    $this->db->select('incident_report.id, incident_report.stuid, incident_report.incident_type, incident_report.incident_location, incident_report.report_by, incident_report.date_report, users.fname,users.mname, users.lname, users.gradesec,users.username,users.profile ');
    $this->db->from('incident_report');
    $this->db->join('users', 
        'users.username = incident_report.stuid');
    $this->db->order_by('incident_report.id', 'DESC');
    $query = $this->db->get('');
    $output='';
    foreach ($query->result() as $row) {
      $output .='<div id="seen_incident_report"><a href="'.base_url().'studentincident/" class="dropdown-item seen_incident_report" > 
        <span class="dropdown-item-avatar text-white">';
        if($row->profile!=''){
          $output.='<img alt="image" src="'.base_url().'/profile/'.$row->profile.'" class="rounded-circle" style="height:50px;width:50px">';
        }else{
          $output.='<img alt="image" src="'.base_url().'/profile/defaultProfile.png" class="rounded-circle" style="height:50px;width:auto">';  
        }
        $output.='</span> 
        <span class="dropdown-item-desc"> 
          <span class="message-user">
            '.$row->fname.'&nbsp'.$row->mname.'   
          </span>
          <span class="time messege-text">
          '.$row->incident_type.' Incident at '.$row->incident_location.'  
         </span>
          <span class="time">
            '.$row->date_report.' 
          </span>
        </span>
      </a></div> ';
    }
    return $output;
  }
  function fetch_student_request($max_year){
    $this->db->where('requeststatus','0');
    $this->db->where('studentrequest.academicyear',$max_year);
    $this->db->order_by('studentrequest.id','DESC');
    $this->db->group_by('studentrequest.id');
    $this->db->select('*');
    $this->db->from('studentrequest');
    $this->db->join('users', 
            'users.username = studentrequest.stuid');
    $query = $this->db->get();
    $output='';
    foreach ($query->result() as $row) {
      # code...
      if($row->from_date!=''){
        $output .='<a href="'.base_url().'staffrequest/?staff-request-page" class="dropdown-item"> 
              <span class="dropdown-item-avatar text-white">';
              if($row->profile!=''){
                $output.='<img alt="image" src="'.base_url().'/profile/'.$row->profile.'" class="rounded-circle">';
              }else{
                $output.='<img alt="image" src="'.base_url().'/profile/defaultProfile.png" class="rounded-circle">';  
              }
              $output.='</span> 
              <span class="dropdown-item-desc"> 
                <span class="message-user">
                  '.$row->fname.'&nbsp'.$row->mname.'   
                </span>
                <span class="time messege-text">
                '.$row->requestype.' Leaving <span class="tex-muted"> ('.$row->from_date.' - '.$row->to_date.')</span>
               </span>
                <span class="time">
                  '.$row->requestdate.' 
                </span>
              </span>
            </a> ';
        }else{
        $output .='<a href="'.base_url().'studentrequest/?student-request-page" class="dropdown-item"> 
              <span class="dropdown-item-avatar text-white">';
              if($row->profile!=''){
                $output.='<img alt="image" src="'.base_url().'/profile/'.$row->profile.'" class="rounded-circle">';
              }else{
                $output.='<img alt="image" src="'.base_url().'/profile/defaultProfile.png" class="rounded-circle">';  
              }
              $output.='</span> 
              <span class="dropdown-item-desc"> 
                <span class="message-user">
                  '.$row->fname.'&nbsp'.$row->mname.'   
                </span>
                <span class="time messege-text">
                '.$row->requestype.'  
               </span>
                <span class="time">
                  '.$row->requestdate.' 
                </span>
              </span>
            </a> ';
      }
    }
    return $output;
  }
  function fetch_unseen_notification($max_year){
    $this->db->where('isapproved','0');
    $this->db->where('academicyear',$max_year);
    $this->db->from('users_registration_request');
    return $this->db->count_all_results();
  }
  function count_unseen_incident($max_year){
    $this->db->where('report_status','0');
    $this->db->from('incident_report');
    return $this->db->count_all_results();
  }
  function count_supervision_Attendance($user,$max_year){
    $this->db->where('academicyear',$max_year);
    $this->db->where('status','0');
    $this->db->where('stuid',$user);
    $this->db->from('attendance_evaluation');
    return $this->db->count_all_results();
  }
  function update_my_supervision_Attendance($user){
    $this->db->where('status','0');
    $this->db->where('stuid',$user);
    $this->db->set('status','1');
    $this->db->update('attendance_evaluation');
  }
  function fetch_allmy_supervision_attendance_notification($user,$max_year){
    $this->db->where('stuid',$user);
    $this->db->where('academicyear',$max_year);
    $this->db->order_by('aid','DESC');
    $this->db->select('*');
    $this->db->from('attendance_evaluation');
    /*$this->db->where('status','0');*/
    $this->db->limit('2');
    $query = $this->db->get();
    $output='';
    foreach ($query->result() as $row) {
      $output .='<a href="'.base_url().'mynotification/?my-notification/" class="dropdown-item"> 
        <span class="dropdown-item-desc"> 
          Supervision Attendance<br>
          <span class="message-user">
            '.$row->attendance_period.'<i class="fas fa-chevron-right"></i> '.$row->absentype.'  
          </span>
          <span class="time messege-text">
          on date '.$row->absentdate.'
         </span>
          
        </span>
      </a> ';
    }
    return $output;
  }
  function count_unseen_request_notification($max_year){
    $this->db->where('requeststatus','0');
    $this->db->where('academicyear',$max_year);
    $this->db->from('studentrequest');
    return $this->db->count_all_results();
  }
  function fetch_unseen_resultAlteration($max_year){
    $this->db->where('status','0');
    $this->db->where('academicyear',$max_year);
    $this->db->from('useralertactions');
    $query=$this->db->count_all_results();
    $output='';
    if($query > 0){
      $output.='<small class="alert alert-light text-danger"><i class="fas fa-exclamation-triangle"> </i>This alert will inform you the manipulated results in previous seasons! <a href="'.base_url().'loggeduser/"> See details  
            </a></small> ';
    }
    return $output;
  }
  function update_unseen_message_notification($username){
    $this->db->where('receiver',$username);
    $this->db->where('status','0');
    $this->db->set('status','1');
    $this->db->update('message');
  }
  function update_unseen_incident_report(){
    $this->db->where('report_status','0');
    $this->db->set('report_status','1');
    $this->db->update('incident_report');
  }
  function fetch_allmessages1($user){
    $this->db->order_by('message.id','DESC');
    $this->db->select('*');
        $this->db->from('message');
        $this->db->join('users', 
            'users.username = message.sender');
        $this->db->where('receiver',$user);
        $query = $this->db->get();
        $output='';
        foreach ($query->result() as $row) {
          # code...
          $output .='<a href="'.base_url().'inbox/" class="dropdown-item"> 
                  <span class="dropdown-item-avatar text-white">';
                  if($row->profile!=''){
                    $output.='<img alt="image" src="'.base_url().'/profile/'.$row->profile.'" class="rounded-circle">';
                  }else{
                    $output.='<img alt="image" src="'.base_url().'/profile/defaultProfile.png" class="rounded-circle">';  
                  }
                   $output.='
                  </span> 
                  <span class="dropdown-item-desc"> 
                    <span class="message-user">
                      '.$row->fname.' '.$row->mname.'    
                    </span>
                    <span class="time messege-text">
                    '.$row->subject.'   
                   </span>
                    <span class="time">
                      '.$row->date_sent.' 
                    </span>
                  </span>
                </a> ';
        }
        return $output;
  }
  function fetch_allmessages2($user){
    $this->db->order_by('message.id','DESC');
    $this->db->select('*');
        $this->db->from('message');
        $this->db->join('users', 
            'users.username = message.sender');
        $this->db->where('receiver',$user);
        $query = $this->db->get();
        $output='';
        foreach ($query->result() as $row) {
          # code...
          $output .='<a href="'.base_url().'messageinbox/" class="dropdown-item"> 
                  <span class="dropdown-item-avatar text-white">';
                  if($row->profile!=''){
                    $output.='<img alt="image" src="'.base_url().'/profile/'.$row->profile.'" class="rounded-circle">';
                  }else{
                    $output.='<img alt="image" src="'.base_url().'/profile/defaultProfile.png" class="rounded-circle">';  
                  }
                   $output.='
                  </span> 
                  <span class="dropdown-item-desc"> 
                    <span class="message-user">
                      '.$row->fname.' '.$row->mname.'  
                    </span>
                    <span class="time messege-text">
                    '.$row->subject.'   
                   </span>
                    <span class="time">
                      '.$row->date_sent.' 
                    </span>
                  </span>
                </a> ';
        }
        return $output;
  }
  function fetch_allmessages3($user){
    $this->db->order_by('message.id','DESC');
    $this->db->select('*');
        $this->db->from('message');
        $this->db->join('users', 
            'users.username = message.sender');
        $this->db->where('receiver',$user);
        $query = $this->db->get();
        $output='';
        foreach ($query->result() as $row) {
          # code...
          $output .='<a href="'.base_url().'myinbox/" class="dropdown-item"> 
                  <span class="dropdown-item-avatar text-white">';
                  if($row->profile!=''){
                    $output.='<img alt="image" src="'.base_url().'/profile/'.$row->profile.'" class="rounded-circle">';
                  }else{
                    $output.='<img alt="image" src="'.base_url().'/profile/defaultProfile.png" class="rounded-circle">';  
                  }
                   $output.='
                  </span> 
                  <span class="dropdown-item-desc"> 
                    <span class="message-user">
                      '.$row->fname.' '.$row->mname.' 
                    </span>
                    <span class="time messege-text">
                    '.$row->subject.'   
                   </span>
                    <span class="time">
                      '.$row->date_sent.' 
                    </span>
                  </span>
                </a> ';
        }
        return $output;
  }
  function fetch_unseen_message_notification($username){
    $this->db->where('status','0');
    $this->db->where('receiver',$username);
    $this->db->from('message');
    return $this->db->count_all_results();
  }
  function fetch_allnewMark($user,$myBranch,$max_year){
    $arraySp = array('staff' =>$user,'academicyear'=>$max_year);
    $this->db->where($arraySp); 
    $queryPlacement = $this->db->get('directorplacement');
    $output='';
    if ($queryPlacement->num_rows()>0) {
      foreach ($queryPlacement->result() as $keyvalue) {
        $gradesec=$keyvalue->grade;
        $query_quarter = $this->db->query("select max(qu.term) as mQuarter from users as us cross join quarter as qu where Academic_year ='$max_year' and us.academicyear='$max_year' and us.gradesec='$gradesec' and us.grade=qu.termgrade group by us.grade ");
        $row_quarter = $query_quarter->row();
        $max_quarter=$row_quarter->mQuarter;
        $this->db->where('approved','0');
        $this->db->group_by('markname');
        $this->db->group_by('mgrade');
        $this->db->from('mark'.$myBranch.$gradesec.$max_quarter.$max_year);
        $query=$this->db->get();
        foreach ($query->result() as $row) {
          $output .='<a href="'.base_url().'approvestudentmark/?approve-mark-page" class="dropdown-item"> 
              <span class="dropdown-item-desc"> 
                <span class="message-user">
                  '.$row->markname.'('.$row->subname.') 
                </span>
                <span class="time messege-text">
                 '.$row->mgrade.'('.$row->mbranch.')  
               </span>
            </span>
          </a> ';
        }
      }
    }else{
      $output.='No placement found.';
    }
    return $output; 
  }
  function fetch_unseen_newMark($user,$myBranch,$max_year){
    $arraySp = array('staff' =>$user,'academicyear'=>$max_year);
      $this->db->where($arraySp); 
      $queryPlacement = $this->db->get('directorplacement');
      $output='';
      if ($queryPlacement->num_rows()>0) {
        $count=0;
        foreach ($queryPlacement->result() as $keyvalue) {
          $gradesec=$keyvalue->grade;
          $query_quarter = $this->db->query("select max(qu.term) as mQuarter from users as us cross join quarter as qu where Academic_year ='$max_year' and us.academicyear='$max_year' and us.gradesec='$gradesec' and us.grade=qu.termgrade group by us.grade ");
          $row_quarter = $query_quarter->row();
          $max_quarter=$row_quarter->mQuarter;
          $this->db->where('approved','0');
          $this->db->group_by('markname');
          $this->db->group_by('mgrade');
        $this->db->from('mark'.$myBranch.$gradesec.$max_quarter.$max_year);
        $total=$this->db->count_all_results();
        $count=$count + $total;
        }
        $output.=$count;
      }
      return $output; 
  }
  function update_myunseen_markApproved($user,$id,$branch1,$gradesec,$grade,$max_year){
    $this->db->where('stuid',$user);
    $this->db->where('status','0');
    $this->db->where('approved','1');
    $this->db->set('status','1');
    $queryAttendance=$this->db->update('attendance');

    $query_quarter1= "select max(term) as mQuarter from quarter where Academic_year =? and termgrade = ? ";
    $query_quarter=$this->db->query($query_quarter1,array($max_year,$grade));
    if($query_quarter->num_rows()>0){
      $row_quarter = $query_quarter->row();
      $max_quarter=$row_quarter->mQuarter;
      $this->db->where('stuid',$id);
      $this->db->where('status','0');
      $this->db->where('approved','1');
      $this->db->set('status','1');
      $queryMark=$this->db->update('mark'.$branch1.$gradesec.$max_quarter.$max_year);
      
        $this->db->where('stuid',$user);
        $this->db->where('approvecom','1');
        $this->db->where('status','0');
        $this->db->where('academicyear',$max_year);
        $this->db->where('quarter',$max_quarter);
        $this->db->set('status','1');
        $this->db->update('communicationbook');
      
    }
  }
  function update_myunseen_mark($user,$id,$branch1,$gradesec,$grade,$max_year){
    $this->db->where('stuid',$user);
    $this->db->where('status','0');
    $this->db->set('status','1');
    $queryAttendance=$this->db->update('attendance');
    $query_quarter = $this->db->query("select max(term) as mQuarter from quarter where Academic_year ='$max_year' and termgrade = '$grade' ");
    if($query_quarter->num_rows()>0){
      $row_quarter = $query_quarter->row();
      $max_quarter=$row_quarter->mQuarter;
      $this->db->where('stuid',$id);
      $this->db->where('status','0');
      $this->db->set('status','1');
      $query=$this->db->update('mark'.$branch1.$gradesec.$max_quarter.$max_year);

      $this->db->where('stuid',$user);
      $this->db->where('status','0');
      $this->db->where('academicyear',$max_year);
      $this->db->where('quarter',$max_quarter);
      $this->db->set('status','1');
      $this->db->update('communicationbook');
    }
    
  }
  function fetch_allmymarkstatusApproved($user,$id,$branch1,$gradesec,$grade,$max_year){
    $query_quarter1 = "select max(term) as mQuarter from quarter where Academic_year =? and termgrade = ? ";
    $query_quarter=$this->db->query($query_quarter1,array($max_year,$grade));
    $output='';
    if($query_quarter->num_rows()>0){
      $row_quarter = $query_quarter->row();
      $max_quarter=$row_quarter->mQuarter;
      
      $queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$branch1.$gradesec.$max_quarter.$max_year."' ");
      if ($queryCheck->num_rows()>0 ){
      $this->db->where('stuid',$id);
      $this->db->where('approved','1');
      $this->db->order_by('mid','DESC');
      $query = $this->db->get('mark'.$branch1.$gradesec.$max_quarter.$max_year);
        if($query->num_rows()>0){
          foreach ($query->result() as $row) {
            $output .='<a href="'.base_url().'myresult/" class="dropdown-item"> 
              <span class="dropdown-item-desc"> 
              <span class="message-user">
              '.$row->subname.': '.$row->value.'/' . $row->outof .' '.$row->markname .'
              </span>
              <span class="time messege-text">Your result on '.$row->quarter .' </span>
              </span>
              </a> ';
          }
        }
      }
        $this->db->order_by('id','DESC');
        $this->db->select('*');
        $this->db->from('communicationbook');
        $this->db->where('approvecom','1');
        $this->db->where('stuid',$user);
        $this->db->where('quarter',$max_quarter);
        $queryCom = $this->db->get();
        foreach ($queryCom->result() as $row) {
          $output .='<a href="'.base_url().'mycommunicationbook/" class="dropdown-item"> 
          <span class="dropdown-item-desc"> 
            <span class="message-user">
              Communication Text    
            </span>
            <span class="time messege-text">
            '.$row->comsubject.' '.$row->comgrade.' '.$row->datecreated.'   
           </span>
            <span class="time">
              '.substr($row->comnote,0,60).'
            </span>
          </span>
        </a> ';
      }
    }
    $this->db->where('attendance.stuid',$user);
    $this->db->where('attendance.academicyear',$max_year);
    $this->db->where('attendance.approved','1');
    $this->db->order_by('attendance.absentdate','DESC');
    $queryAttendance = $this->db->get('attendance');
    if($queryAttendance->num_rows()>0){
      foreach ($queryAttendance->result() as $row) {
        $output .='<a href="'.base_url().'myattendance/" class="dropdown-item">
          <span class="dropdown-item-desc"> 
            <span class="time messege-text">You were absent on date </span>
            <span class="message-user">Date: '.$row->absentdate.' '.$row->absentype.' </span>
            <span class="time">By: '.$row->attend_by .' </span>
          </span>
        </a> ';
      }
    }
    return $output;
  }
  function fetch_allunseetseen_mymarkApproved($id,$branch1,$gradesec,$grade,$max_year){
    $query_quarter1 = "select max(term) as mQuarter from quarter where Academic_year =? and termgrade = ?";
    $query_quarter=$this->db->query($query_quarter1,array($max_year,$grade));
    $output='';
    if($query_quarter->num_rows()>0){
      $row_quarter = $query_quarter->row();
      $max_quarter=$row_quarter->mQuarter;
      $queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$branch1.$gradesec.$max_quarter.$max_year."' ");
      if ($queryCheck->num_rows()>0 ){
        $this->db->where('stuid',$id);
        $this->db->where('status','0');
        $this->db->where('approved','1');
        $this->db->from('mark'.$branch1.$gradesec.$max_quarter.$max_year);
        return $this->db->count_all_results();
      }
    }
  }
  function fetch_allmymarkstatus($user,$id,$branch1,$gradesec,$grade,$max_year){
    $query_quarter1 = "select max(term) as mQuarter from quarter where Academic_year =? and termgrade = ? ";
    $query_quarter=$this->db->query($query_quarter1,array($max_year,$grade));
     $output='';
    if($query_quarter->num_rows()>0){
      $row_quarter = $query_quarter->row();
      $max_quarter=$row_quarter->mQuarter;
      $queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$branch1.$gradesec.$max_quarter.$max_year."' ");
      if ($queryCheck->num_rows()>0 ){
        $this->db->where('stuid',$id);
        $this->db->order_by('mid','DESC');
        $query = $this->db->get('mark'.$branch1.$gradesec.$max_quarter.$max_year);
          if($query->num_rows()>0){
            foreach ($query->result() as $row) {
              $output .='<a href="'.base_url().'myresult/" class="dropdown-item"> 
                <span class="dropdown-item-desc"> 
                <span class="message-user">
                '.$row->subname.': '.$row->value.'/' . $row->outof .' '.$row->markname .'
                </span>
                <span class="time messege-text">Your result on '.$row->quarter .' </span>
                </span>
                </a> ';
            }
          }
        }
        $this->db->order_by('id','DESC');
        $this->db->select('*');
        $this->db->from('communicationbook');
        /*$this->db->where('approvecom','1');*/
        $this->db->where('stuid',$user);
        $this->db->where('quarter',$max_quarter);
        $queryCom = $this->db->get();
        foreach ($queryCom->result() as $row) {
          $output .='<a href="'.base_url().'mycommunicationbook/" class="dropdown-item"> 
          <span class="dropdown-item-desc"> 
            <span class="message-user">
              Communication Text    
            </span>
            <span class="time messege-text">
            '.$row->comsubject.' '.$row->comgrade.' '.$row->datecreated.'   
           </span>
            <span class="time">
              '.substr($row->comnote,0,60).'
            </span>
          </span>
        </a> ';
      }
    }
    $this->db->where('attendance.stuid',$user);
    $this->db->where('attendance.academicyear',$max_year);
    /*$this->db->where('attendance.approved','1');*/
    $this->db->order_by('attendance.absentdate','DESC');
    $queryAttendance = $this->db->get('attendance');
    if($queryAttendance->num_rows()>0){
      foreach ($queryAttendance->result() as $row) {
        $output .='<a href="'.base_url().'myattendance/" class="dropdown-item">
          <span class="dropdown-item-desc"> 
            <span class="time messege-text">You were absent on date </span>
            <span class="message-user">Date: '.$row->absentdate.' '.$row->absentype.' </span>
            <span class="time">By: '.$row->attend_by .' </span>
          </span>
        </a> ';
      }
    }
    return $output;
  }
  function fetch_allunseetseen_mymark($id,$branch1,$gradesec,$grade,$max_year){
    $query_quarter1 = "select max(term) as mQuarter from quarter where Academic_year =? and termgrade=? ";
    $query_quarter=$this->db->query($query_quarter1,array($max_year,$grade));
    $row_quarter = $query_quarter->row();
    $max_quarter=$row_quarter->mQuarter;
    $queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$branch1.$gradesec.$max_quarter.$max_year."' ");
    if ($queryCheck->num_rows()>0 ){
      $this->db->where('stuid',$id);
      $this->db->where('status','0');
      $this->db->from('mark'.$branch1.$gradesec.$max_quarter.$max_year);
      return $this->db->count_all_results();
    }
  }
  function fetch_allunseetseen_myattendance($id,$max_year){
    $this->db->where('stuid',$id);
    $this->db->where('academicyear',$max_year);
    $this->db->where('status','0');
    $this->db->from('attendance');
    return $this->db->count_all_results();
  }
  function fetch_allunseetseen_myattendanceApproved($id,$max_year){
    $this->db->where('stuid',$id);
    $this->db->where('academicyear',$max_year);
    $this->db->where('status','0');
    $this->db->where('approved','1');
    $this->db->from('attendance');
    return $this->db->count_all_results();
  }
  function fetch_unseen_comBook_notificationApproved($user,$grade,$max_year){
    $query_quarter1 = "select max(term) as mQuarter from quarter where Academic_year =? and termgrade=? ";
    $query_quarter=$this->db->query($query_quarter1,array($max_year,$grade));
    $row_quarter = $query_quarter->row();
    $max_quarter=$row_quarter->mQuarter;

    $this->db->select('*');
    $this->db->from('communicationbook');
    $this->db->order_by('communicationbook.id','DESC');
    $this->db->where('communicationbook.academicyear',$max_year);
    $this->db->where('communicationbook.stuid',$user);
    $this->db->where('communicationbook.quarter',$max_quarter);
    $this->db->where('communicationbook.approvecom','1');
    $this->db->where('communicationbook.status','0');
    return $this->db->count_all_results();
  }
  function fetch_unseen_comBook_notification($user,$grade,$max_year){
    $query_quarter1 = "select max(term) as mQuarter from quarter where Academic_year =? and termgrade=? ";
    $query_quarter= $this->db->query($query_quarter1,array($max_year,$grade));
    $row_quarter = $query_quarter->row();
    $max_quarter=$row_quarter->mQuarter;

    $this->db->select('*');
    $this->db->from('communicationbook');
    $this->db->order_by('communicationbook.id','DESC');
    $this->db->where('communicationbook.academicyear',$max_year);
    $this->db->where('communicationbook.stuid',$user);
    $this->db->where('communicationbook.quarter',$max_quarter);
    /*$this->db->where('communicationbook.approvecom','1');*/
    $this->db->where('communicationbook.status','0');
    return $this->db->count_all_results();
  }
  function fetch_requested_item_toapprove_all($user){
    $query = $this->db->query(" select stock_requested.id, stock_requested.requested_item_id, stock_requested.requested_quantity, stock_requested.requested_date, stock_requested.request_response,stock_requested.requested_by, stock_requested.status, stock_category.category_owner,stock_category.category_name,stock_item.item_name,stock_item.item_id from stock_requested cross join stock_item cross join stock_category where stock_item.item_id=stock_requested.requested_item_id and stock_item.item_category=stock_category.category_name and stock_requested.status='0' group by stock_requested.id order by stock_requested.id DESC limit 1 ");
    $output='';
    foreach ($query->result() as $row) {
      $output.=' Request Item:'.$row->requested_item_id.' Quantity: '.$row->requested_quantity.' on date  '.$row->requested_date.' ';
    }
    return $output;
  }
  function fetch_requested_item_toapprove($user){
    $query = $this->db->query(" select stock_requested.id, stock_requested.requested_item_id, stock_requested.requested_quantity, stock_requested.requested_date, stock_requested.request_response,stock_requested.requested_by, stock_requested.status, stock_category.category_owner,stock_category.category_name,stock_item.item_name,stock_item.item_id from stock_requested cross join stock_item cross join stock_category where stock_item.item_id=stock_requested.requested_item_id and stock_item.item_category=stock_category.category_name and stock_category.category_owner='$user' and stock_requested.status='0' group by stock_requested.id order by stock_requested.id DESC limit 1 ");
    $output='';
    foreach ($query->result() as $row) {
      $output.=' Request Item:'.$row->requested_item_id.' Quantity: '.$row->requested_quantity.' date  '.$row->requested_date.' ';
    }
    return $output;
  }
  function fetch_requested_book_toapprove_all($user){
    $query = $this->db->query(" select book_borrow.id, book_borrow.submitted_by, book_borrow.date_returned, book_borrow.user_received,book_borrow.date_submitted, book_borrow.status, book_borrow.request_response, book_stock.book_name, book_stock.book_id, book_stock.book_grade,book_stock.date_created from book_borrow cross join book_stock where book_borrow.book_id=book_stock.id and book_borrow.status='0' group by book_borrow.id order by book_borrow.id DESC limit 1 ");
    $output='';
    foreach ($query->result() as $row) {
      $output.=' Request Book:'.$row->book_name.' Grade: '.$row->book_grade.' date  '.$row->date_submitted.' ';
    }
    return $output;
  }
  function fetch_requested_book_toapprove($user){
    $query = $this->db->query(" select book_borrow.id, book_borrow.submitted_by, book_borrow.date_returned, book_borrow.user_received,book_borrow.date_submitted, book_borrow.status, book_borrow.request_response, book_stock.book_name, book_stock.book_id, book_stock.book_grade,book_stock.date_created from book_borrow cross join book_stock where book_borrow.book_id=book_stock.id and book_borrow.status='0' group by book_borrow.id order by book_borrow.id DESC limit 1 ");
    $output='';
    foreach ($query->result() as $row) {
      $output.=' Request Book:'.$row->book_name.' Grade: '.$row->book_grade.' date  '.$row->date_submitted.' ';
    }
    return $output;
  }
  function fetch_session_user($user){
    $this->db->where('username',$user);
    $this->db->group_by('username');
    $query=$this->db->get('users');
    return $query->result();
  }
  function fetch_school(){
    $query=$this->db->get('school');
    return $query->result();
  }
  public function fetch_my_notification($user)
  {
    $output='';
    $queryAttendance=$this->db->query("select * from attendance_evaluation where stuid='$user' order by aid DESC ");
    if($queryAttendance->num_rows()>0){
      $this->db->where('status','0');
      $this->db->where('stuid',$user);
      $this->db->set('status','1');
      $this->db->update('attendance_evaluation');
      foreach($queryAttendance->result() as $value){
        $output.='<span class="font-weight-bold">Supervision Attendance</span>
          <div class="have-incident-report">
            <div class="inbox-center">
              <table class="table table-hover">
              <tbody>
                <tr class="unread">
                  <td class="max-texts">  '.$value->absentdate.' </td>
                  <td class="hidden-xs">'.$value->attendance_period.'</td>
                  <td class="max-texts"> '.$value->absentype.' </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>';
      }
    }else{
      $output.='<div class="alert alert-light alert-dismissible show fade">
          <div class="alert-body">
              <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
              </button>
          <i class="fas fa-check-circle"> </i> Ooops, No record found.
      </div></div>';
    }
    return $output;
  }
}