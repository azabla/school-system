<?php
class main_model extends CI_Model{
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
	function reset_student_password($id,$data,$temp_pass){
		$this->db->where('unique_id',$id);
		$query=$this->db->update('users',$data);
		$output='';
		if($query){
			$output .='<div class="alert alert-info alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-check-circle"> </i> Your new password is '.$temp_pass.'.
            </div></div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-check-circle"> </i>ooops Please try again.
            </div></div>';
		}
		return $output;
	}
	function reset_staff_password($id,$data,$temp_pass){
		$this->db->where('id',$id);
		$query=$this->db->update('users',$data);
		$output='';
		if($query){
			$output .='<div class="alert alert-info alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-check-circle"> </i> Your new password is '.$temp_pass.'.
            </div></div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-check-circle"> </i>ooops Please try again.
            </div></div>';
		}
		return $output;
	}
	function fetch_single_details()
	{
		$this->db->not_like('usertype','Student');
		$data = $this->db->get('users');
		$output = '<p><div class="col-lg-6">';
		foreach($data->result() as $row)
		{
			$output .= '
			<img src="'.base_url().'/profile/'.$row->profile.'" />
			'.$row->fname.' '.$row->mname.'';
		}
		$output .= '</div></p>';
		return $output;
	}
	function fetch_session_user($user){
		$this->db->where('username',$user);
		$this->db->group_by('username');
		$query=$this->db->get('users');
		return $query->result();
	}
	function post_branch($data){
		$query=$this->db->insert('branch',$data);
		if($query){
			return true;
		}else{
			return false;
		}
	}
	function fetch_branch($max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->order_by('name','ASC');
		$query=$this->db->get('branch');
		return $query->result();
	}
	function delete_branch($id){
		$this->db->where('bid',$id);
		$this->db->delete('branch');
	}
	function fetch_social_pages(){
		$query=$this->db->get('links');
		return $query->result();
	}
	function change_password($user,$password){
	    $this->db->where('username',$user);
	    $this->db->where('password',$password);
	    $query=$this->db->get('users');
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	function update_password($user,$password2){
	  	$this->db->where('username',$user);
    	$this->db->set('password', $password2);
    	$this->db->set('password2', $password2);
    	$query=$this->db->update('users');
		if($query){
			return true;
		}
		else{
			return false;
		}
	}
	function change_profile($user){
	    $this->db->where('username',$user);
	    $query=$this->db->get('users');
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
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
	function academicYear4Registration(){
		$this->db->order_by('year_name','DESC');
		$this->db->select('year_name');
		$this->db->limit('2');
		$query=$this->db->get('academicyear');
		return $query->result();
	}
	function fetch_sent($user){
		$this->db->order_by('message.id','DESC');
		$this->db->select('*');
        $this->db->from('message');
        $this->db->join('users', 
            'users.username = message.sender');
        $this->db->where('sender',$user);
        $query = $this->db->get();
        return $query->result();
	}
	function fetch_inbox($user,$usertype){
		$this->db->order_by('message.id','DESC');
		$this->db->select('*');
        $this->db->from('message');
        $this->db->join('users', 
            'users.username = message.sender');
        $this->db->where('receiver',$user);
        $query = $this->db->get();
        return $query->result();
	}
	function fetch_allmessages($user){
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
        	$output .='<a href="#" class="dropdown-item"> 
                  <span class="dropdown-item-avatar text-white">
                   <img alt="image" src="'.base_url().'/profile/'.$row->profile.'" class="rounded-circle">
                  </span> 
                  <span class="dropdown-item-desc"> 
                    <span class="message-user">
                      '.$row->sender.'    
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
	function fetch_allnotification(){
		$this->db->order_by('id','DESC');
		$this->db->select('*');
        $this->db->from('users');
        $this->db->where('isapproved','0');
        $query = $this->db->get();
        $output='';
        foreach ($query->result() as $row) {
        	# code...
        	$output .='<a href="'.base_url().'newstaffs/" class="dropdown-item"> 
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
                    '.$row->usertype.' ('.$row->branch.')  
                   </span>
                    <span class="time">
                      '.$row->dateregister.' 
                    </span>
                  </span>
                </a> ';
        }
        return $output;
	}
	function fetch_allmymarkstatusApproved($id,$branch1,$gradesec,$max_quarter,$max_year){
		$this->db->where('stuid',$id);
		/*$this->db->where('status','0');*/
		$this->db->where('approved','1');
		$this->db->order_by('mid','DESC');
		$query = $this->db->get('mark'.$branch1.$gradesec.$max_quarter.$max_year);
	    $output='';
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
	    }else{
	    	$output .='<a href="#" class="dropdown-item"> 
	        <span class="dropdown-item-desc">
	        <span class="time messege-text">No new mark result report. </span>
	        </span>
	        </a> ';
	    }
	    return $output;
	}
	function fetch_allmymarkstatus($id,$branch1,$gradesec,$max_quarter,$max_year){
		$this->db->where('stuid',$id);
		/*$this->db->where('status','0');*/
		$this->db->order_by('mid','DESC');
		$query = $this->db->get('mark'.$branch1.$gradesec.$max_quarter.$max_year);
	    $output='';
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
	    }else{
	    	$output .='<a href="#" class="dropdown-item"> 
	        <span class="dropdown-item-desc">
	        <span class="time messege-text">No New mark result report. </span>
	        </span>
	        </a> ';
	    }
	    return $output;
	}
	function fetch_allmypaymentstatus($id){
		$this->db->where('stuid',$id);
		$this->db->order_by('date_created','DESC');
		$query = $this->db->get('payment');
    $output='';
    if($query->num_rows()>0){
    foreach ($query->result() as $row) {
    	$output .='<a href="#" class="dropdown-item">
    	  <span class="dropdown-item-avatar text-white">
          Payment
        </span> 
        <span class="dropdown-item-desc"> 
        <span class="message-user">Payment:
        '.$row->paymentype.' '.$row->paid.'Br.
        </span>
        <span class="time messege-text">You were paid the above listed detail</span>
        <span class="time">By:
        '.$row->byuser .'
        </span>
        </span>
        </a> ';
      }
    }else{
    	$output .='<a href="#" class="dropdown-item"> 
        <span class="dropdown-item-desc">
        <span class="time messege-text">No New payment report. </span>
        </span>
        </a> ';
    }
    return $output;
	}
	function fetch_allmyattendance($id){
		$this->db->where('stuid',$id);
		$this->db->where('approved','1');
		/*$this->db->where('status','0');*/
		$this->db->order_by('absentdate','DESC');
		$query = $this->db->get('attendance');
	    $output='';
	    if($query->num_rows()>0){
	    	foreach ($query->result() as $row) {
		    	$output .='<a href="'.base_url().'myattendance/" class="dropdown-item">
		        	<span class="dropdown-item-desc"> 
		        	<span class="time messege-text">You were absent on date </span>
			        	<span class="message-user">Date: '.$row->absentdate.' '.$row->absentype.' </span>
			        	
			        	<span class="time">By: '.$row->attend_by .' </span>
		        	</span>
		        </a> ';
		    }
	    }else{
	    	$output .='<a href="#" class="dropdown-item"> 
	        <span class="dropdown-item-desc">
	        <span class="time messege-text">No New absence report. </span>
	        </span>
	        </a> ';
	    }
	    return $output;
	}
	function fetch_allunseetseen_myattendance($id){
		$this->db->where('stuid',$id);
		$this->db->where('status','0');
		$this->db->where('approved','1');
		$this->db->from('attendance');
		return $this->db->count_all_results();
	}
	function update_myunseen_attendance($id){
		$this->db->where('stuid',$id);
		$this->db->where('status','0');
		$this->db->set('status','1');
		$query=$this->db->update('attendance');
		return $query->results();
	}
	function fetch_allunseetseen_mypayment($id){
		$this->db->where('stuid',$id);
		$this->db->where('status','0');
		$this->db->from('payment');
		return $this->db->count_all_results();
	}
	function update_myunseen_payment($id){
		$this->db->where('stuid',$id);
		$this->db->where('status','0');
		$this->db->set('status','1');
		$query=$this->db->update('payment');
		return $query->results();
	}
	function fetch_allunseetseen_mymarkApproved($id,$branch1,$gradesec,$max_quarter,$max_year){
		$this->db->where('stuid',$id);
		$this->db->where('status','0');
		$this->db->where('approved','1');
		$this->db->from('mark'.$branch1.$gradesec.$max_quarter.$max_year);
		return $this->db->count_all_results();
	}
	function fetch_allunseetseen_mymark($id,$branch1,$gradesec,$max_quarter,$max_year){
		$this->db->where('stuid',$id);
		$this->db->where('status','0');
		$this->db->from('mark'.$branch1.$gradesec.$max_quarter.$max_year);
		return $this->db->count_all_results();
	}
	function update_myunseen_mark($id,$branch1,$gradesec,$max_quarter,$max_year){
		$this->db->where('stuid',$id);
		$this->db->where('status','0');
		$this->db->set('status','1');
		$query=$this->db->update('mark'.$branch1.$gradesec.$max_quarter.$max_year);
		return $query->results();
	}
	function fetch_school(){
		$query=$this->db->get('school');
		return $query->result();
	}
	function post_data($data){
		$this->db->insert('post',$data);
	}
	function post_like($id,$uid,$data){
		$this->db->where('bid =',$uid);
		$this->db->where('pid =',$id);
		$query=$this->db->get('post_like');
		if($query->num_rows()>0){
			$this->db->where('bid =',$uid);
		  $this->db->where('pid =',$id);
		  $this->db->delete('post_like');
		}else{
			$this->db->insert('post_like',$data);
		  return $id=$this->db->insert_id();
		}
	}
	function fetch_post(){
		$this->db->order_by('post.pid','DESC');
		$this->db->select('*');
    	$this->db->from('post');
    	$this->db->join('users', 
            'users.username = post.postby');
    	$query = $this->db->get();
    	return $query->result();
	}
	function fetch_showmore_post($id){
		$this->db->where('pid',$id);
		$this->db->order_by('post.pid','DESC');
    	$query = $this->db->get('post');
    	$output='';
    	foreach ($query->result() as $post_result) {
    		$output.=$post_result->post;
    	}
    	return $output;
	}
	function fetch_elibrary(){
		$this->db->order_by('library.lid','DESC');
		$this->db->select('*');
    $this->db->from('library');
    $this->db->join('users', 
            'users.username = library.pby');
    $query = $this->db->get();
    return $query->result();
	}
	function fetch_myelibrary($grade){
		$this->db->where('library.grade',$grade);
		$this->db->order_by('library.lid','DESC');
		$this->db->select('*');
    $this->db->from('library');
    $this->db->join('users', 
            'users.username = library.pby');
    $query = $this->db->get();
    return $query->result();
	}
	function delete_post($id){
		$this->db->where('pid',$id);
		$this->db->delete('post');
	}
	function read_more($id){
		$this->db->where(array('post.pid'=>$id));
		$this->db->select('*');
        $this->db->from('post');
        $this->db->join('users', 
            'users.username = post.postby');
        $query = $this->db->get();
        return $query->result();
	}
	function search($key){
		$this->db->order_by('post.pid','DESC');
		$this->db->like('title',$key);
		$this->db->or_like('post',$key);
		$this->db->select('*');
        $this->db->from('post');
        $this->db->join('users', 
            'users.username = post.postby');
        $query = $this->db->get();
        return $query->result();
	}
	function fetchDirectorForPlacement(){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('usertype','Director');
		$this->db->order_by('fname,mname','ASC');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetchMyDirectorForPlacement($branch){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('branch'=>$branch));
		$this->db->like('usertype','Director');
		$this->db->order_by('fname,mname','ASC');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetchStaffsForPlacement(){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$this->db->order_by('fname,mname','ASC');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetchMyStaffsForPlacement($branch){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$this->db->order_by('fname,mname','ASC');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetchDirectorTeacher($hoomroombranch){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('branch'=>$hoomroombranch));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('usertype','Teacher');
		$this->db->or_like('usertype','Director');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetchThisBranchTeacher($hoomroombranch){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('branch'=>$hoomroombranch));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('usertype','Teacher');
		$this->db->or_like('usertype','Admin');
		$this->db->or_like('usertype','Director');
		$query=$this->db->get('users');
		$output ='<option> </option>';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->username.'">
			'.$row->username.'-'.$row->fname.' '.$row->mname.'</option>';
		}
		return $output;
	}
	function searchStaffs($searchItem){
		$this->db->order_by('fname','ASC');
		$this->db->where('usertype!=','Student');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('username',$searchItem);
		$this->db->or_where('usertype!=','Student');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('fname', $searchItem);
		$this->db->or_where('usertype!=','Student');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('email', $searchItem);

		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>Name</th>
                <th>UserType</th>
                <th>Mobile</th>
                <th>Branch</th>
                <th><div class="dropdown">
                		All <input type="checkbox" id="selectallstaffs" onClick="selectAllStaffs()"> Action
                        <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a> 
                        <div class="dropdown-menu">
                        	<a href="#" class="dropdown-item has-icon text-danger deleteCustomStaffs" name="" value="" id="deleteCustomStaffs"><i class="fas fa-trash-alt"></i> Delete</a>
                        	<div class="dropdown-divider"></div>
                        	<a href="#" class="dropdown-item has-icon text-success ActiveCustomStaffs" name="" value="" id="ActiveCustomStaffs"><i class="fas fa-check-circle"></i> Active</a>
                        	<div class="dropdown-divider"></div>
                        	<a href="#" class="dropdown-item has-icon text-warning InactiveCustomStaffs" name="" value="" id="InactiveCustomStaffs"><i class="fas fa-times-circle"></i> Inactive</a>
                        	<div class="dropdown-divider"></div>
                        	<a href="#" class="dropdown-item has-icon text-info resetPasswordCustomStaffs" name="" value="" id="resetPasswordCustomStaffs"><i class="fas fa-key"></i> Reset Password</a>
                      	</div>
                    </div>
                </th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$id=$staff->id;
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
                    <td><a href="#" id="edit_staff" class="text-success" value="'.$id.'" ><i class="fas fa-pen-alt"></i></a> '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'('.$staff->username.') <span class="resetStaffPasswordGrand'.$staff->id.' text-warning" ></span>
                    </td>
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td> 
                    <td class="text-center"> <input type="checkbox" class="activeInactiveStaffsList" id="activeInactiveStaffsList" name="activeInactiveStaffsList[ ]" value="'.$staff->id.'"> ';
                    if($staff->status==trim('Active')){ 
                      $output.='<span class="badge badge-light">Active</span>';
                    }else {
                    	$output.='<span class="badge badge-warning">Not Active </span>';
                    }
                    $output.='</td>                        
                </tr>';
                $no++; 
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function searchStaffsAdmin($searchItem,$branch){
		$this->db->order_by('fname','ASC');
		$this->db->where('usertype!=','Student');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('branch'=>$branch));
		$this->db->like('username',$searchItem);
		$this->db->or_where('usertype!=','Student');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('branch'=>$branch));
		$this->db->like('fname', $searchItem);
		$this->db->or_where('usertype!=','Student');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('branch'=>$branch));
		$this->db->like('email', $searchItem);

		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>Name</th>
                <th>UserType</th>
                <th>Mobile</th>
                <th>Branch</th>
                <th><div class="dropdown">
                		All <input type="checkbox" id="selectallstaffs" onClick="selectAllStaffs()"> Action
                        <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a> 
                        <div class="dropdown-menu">
                        	<a href="#" class="dropdown-item has-icon text-danger deleteCustomStaffs" name="" value="" id="deleteCustomStaffs"><i class="fas fa-trash-alt"></i> Delete</a>
                        	<div class="dropdown-divider"></div>
                        	<a href="#" class="dropdown-item has-icon text-success ActiveCustomStaffs" name="" value="" id="ActiveCustomStaffs"><i class="fas fa-check-circle"></i> Active</a>
                        	<div class="dropdown-divider"></div>
                        	<a href="#" class="dropdown-item has-icon text-warning InactiveCustomStaffs" name="" value="" id="InactiveCustomStaffs"><i class="fas fa-times-circle"></i> Inactive</a>
                        	<div class="dropdown-divider"></div>
                        	<a href="#" class="dropdown-item has-icon text-info resetPasswordCustomStaffs" name="" value="" id="resetPasswordCustomStaffs"><i class="fas fa-key"></i> Reset Password</a>
                      	</div>
                    </div>
                </th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$id=$staff->id;
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
                    <td><a href="#" id="edit_staff" class="text-success" value="'.$id.'" ><i class="fas fa-pen-alt"></i></a> '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'('.$staff->username.') <span class="resetStaffPasswordGrand'.$staff->id.' text-warning" ></span>
                    </td>
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td> 
                    <td class="text-center"> <input type="checkbox" class="activeInactiveStaffsList" id="activeInactiveStaffsList" name="activeInactiveStaffsList[ ]" value="'.$staff->id.'"> ';
                    if($staff->status==trim('Active')){ 
                      $output.='<span class="badge badge-light">Active</span>';
                    }else {
                    	$output.='<span class="badge badge-warning">Not Active </span>';
                    }
                    $output.='</td>                        
                </tr>';
                $no++; 
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetch_staffs(){
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$this->db->order_by('fname,mname,lname','ASC');
		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>Name</th>
                <th>UserType</th>
                <th>Mobile</th>
                <th>Branch</th>
                <th><div class="dropdown">
                		All <input type="checkbox" id="selectallstaffs" onClick="selectAllStaffs()"> Action
                        <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a> 
                        <div class="dropdown-menu">
                        	<a href="#" class="dropdown-item has-icon text-danger deleteCustomStaffs" name="" value="" id="deleteCustomStaffs"><i class="fas fa-trash-alt"></i> Delete</a>
                        	<div class="dropdown-divider"></div>
                        	<a href="#" class="dropdown-item has-icon text-success ActiveCustomStaffs" name="" value="" id="ActiveCustomStaffs"><i class="fas fa-check-circle"></i> Active</a>
                        	<div class="dropdown-divider"></div>
                        	<a href="#" class="dropdown-item has-icon text-warning InactiveCustomStaffs" name="" value="" id="InactiveCustomStaffs"><i class="fas fa-times-circle"></i> Inactive</a>
                        	<div class="dropdown-divider"></div>
                        	<a href="#" class="dropdown-item has-icon text-info resetPasswordCustomStaffs" name="" value="" id="resetPasswordCustomStaffs"><i class="fas fa-key"></i> Reset Password</a>
                      	</div>
                    </div>
                </th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$id=$staff->id;
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
                    <td><a href="#" id="edit_staff" class="text-success" value="'.$id.'" ><i class="fas fa-pen-alt"></i></a> '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'('.$staff->username.') <span class="resetStaffPasswordGrand'.$staff->id.' text-warning" ></span>
                    </td>
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td> 
                    <td class="text-center"> <input type="checkbox" class="activeInactiveStaffsList" id="activeInactiveStaffsList" name="activeInactiveStaffsList[ ]" value="'.$staff->id.'"> ';
                    if($staff->status==trim('Active')){ 
                      $output.='<span class="badge badge-light">Active</span>';
                    }else {
                    	$output.='<span class="badge badge-warning">Not Active </span>';
                    }
                    $output.='</td>                        
                </tr>';
                $no++; 
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetchFinancemystaffs($branch,$myDivision,$usertype){
		$this->db->where(array('status2'=>$myDivision));
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>UserName</th>
                <th>UserType</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Branch</th>
                <th>Status</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='$usertype' and allowed='Staff' order by id ASC ");
       			$id=$staff->id;
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
                    <td>'. $staff->username.'';
                    if($usergroupPermission->num_rows()>0){
                        $output.='<div class="table-links">
                            <a href="#" id="delete_staff" class="text-danger" value="'.$id.'" ><i class="fa fa-trash"></i> </a>
                            <div class="bullet"></div>
                            <a href="#" id="edit_staff" class="text-success" value="'.$id.'" ><i class="fa fa-pen"></i></a>
                        </div>';
                    }
                    $output.='</td>
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'';
                    if($usergroupPermission->num_rows()>0){
                    	$output.='<div class="table-links">
             				<a href="#" class="resetStaffPassword text-warning" id="'.$staff->id.'">Reset Password</a>
            			</div>';
            		}
                    $output.='</td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td> 
                    <td> ';
                    if($usergroupPermission->num_rows()>0){
	                    if($staff->status==trim('Active')){ 
	                      $output.='<button type="submit" name="inactive" value="'.$staff->id.'" class="btn btn-success inactive">'.$staff->status.'
	                      </button>';
	                    }else {
	                       $output.='<button type="submit" name="active" value="'.$staff->id.'" class="btn btn-danger active">
	                        '.$staff->status.'
	                      </button>';
	                    }
	                }
                    $output.='</td>                        
                </tr>';
                $no++; 
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetchstaffPhoneListAll($max_year){
		$this->db->where('isapproved','1');
		$this->db->where('status','Active');
		$this->db->not_like('usertype','Student');
		$this->db->order_by('fname,mname,lname','ASC');
		$query=$this->db->get('users');
		$output='';
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
		if($query->num_rows()>0){
			$output.='<p class="text-center"><b><u>'.$school_name.' Staffs Phone List in '.$max_year.' Academic Year</u></b></p>
			<div class="table-responsive">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>Staff Name</th>
                <th>UserType</th>
                <th>Mobile</th>
                <th>Branch</th>
                <th>Status</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$output.='<tr>
                    <td>'.$no.'.</td>
                    <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td>
                    <td><span class="badge badge-info">'.$staff->status.'</span></td>';
                    
                    $output.='                       
                </tr>';
                $no++; 
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetchstaffPhoneList($branch,$myDivision,$max_year){
		$this->db->where('branch',$branch);
		$this->db->where('status2',$myDivision);
		$this->db->where('isapproved','1');
		$this->db->where('status','Active');
		$this->db->not_like('usertype','Student');
		$this->db->order_by('fname,mname,lname','ASC');
		$query=$this->db->get('users');
		$output='';
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
		if($query->num_rows()>0){
			$output.='<p class="text-center"><b><u>'.$school_name.' Staffs Phone List in '.$max_year.' Academic Year</u></b></p>
			<div class="">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>Staff Name</th>
                <th>UserType</th>
                <th>Mobile</th>
                <th>Branch</th>
                <th>Status</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$output.='<tr>
                    <td>'.$no.'.</td>
                    <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td>
                    <td><span class="badge badge-info">'.$staff->status.'</span></td>';
                    
                    $output.='                       
                </tr>';
                $no++; 
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetch_mystaffsAdmin($branch,$usertype){
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>Name</th>
                <th>UserType</th>
                <th>Mobile</th>
                <th>Branch</th>
                <th><div class="dropdown">
                		All <input type="checkbox" id="selectallstaffs" onClick="selectAllStaffs()"> Action
                        <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a> 
                        <div class="dropdown-menu">
                        	<a href="#" class="dropdown-item has-icon text-danger deleteCustomStaffs" name="" value="" id="deleteCustomStaffs"><i class="fas fa-trash-alt"></i> Delete</a>
                        	<div class="dropdown-divider"></div>
                        	<a href="#" class="dropdown-item has-icon text-success ActiveCustomStaffs" name="" value="" id="ActiveCustomStaffs"><i class="fas fa-check-circle"></i> Active</a>
                        	<div class="dropdown-divider"></div>
                        	<a href="#" class="dropdown-item has-icon text-warning InactiveCustomStaffs" name="" value="" id="InactiveCustomStaffs"><i class="fas fa-times-circle"></i> Inactive</a>
                        	<div class="dropdown-divider"></div>
                        	<a href="#" class="dropdown-item has-icon text-info resetPasswordCustomStaffs" name="" value="" id="resetPasswordCustomStaffs"><i class="fas fa-key"></i> Reset Password</a>
                      	</div>
                    </div>
                </th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$id=$staff->id;
       			$usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='$usertype' and tableName='Staff' and allowed='staffDE' order by id ASC ");
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>';
                    if($usergroupPermission->num_rows()>0){
	                    $output.='<td><a href="#" id="edit_staff" class="text-success" value="'.$id.'" ><i class="fas fa-pen-alt"></i></a> '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'('.$staff->username.') <span class="resetStaffPasswordGrand'.$staff->id.' text-warning" ></span>
	                    </td>';
	                }else{
	                	$output.='<td><a href="#" id="edit_staff" class="text-success" value="'.$id.'" ><i class="fas fa-pen-alt"></i></a> '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'('.$staff->username.') <span class="resetStaffPasswordGrand'.$staff->id.' text-warning" ></span>
	                    </td>';
	                }
                    $output.='<td>'.$staff->usertype.'</td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td>';
                    if($usergroupPermission->num_rows()>0){
	                    $output.='<td class="text-center"> <input type="checkbox" class="activeInactiveStaffsList" id="activeInactiveStaffsList" name="activeInactiveStaffsList[ ]" value="'.$staff->id.'"> ';
	                    if($staff->status==trim('Active')){ 
	                      $output.='<span class="badge badge-light">Active</span>';
	                    }else {
	                    	$output.='<span class="badge badge-warning">Not Active </span>';
	                    }
	                }else{
	                	$output.='<td class="text-center"> <input type="checkbox" class="activeInactiveStaffsList" id="activeInactiveStaffsList" name="activeInactiveStaffsList[ ]" value="'.$staff->id.'"> ';
	                    if($staff->status==trim('Active')){ 
	                      $output.='<span class="badge badge-light">Active</span>';
	                    }else {
	                    	$output.='<span class="badge badge-warning">Not Active </span>';
	                    }
	                }
                    $output.='</td>                        
                </tr>';
                $no++; 
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetch_mystaffs($branch,$myDivision,$usertype){
		$this->db->where(array('status2'=>$myDivision));
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>UserName</th>
                <th>UserType</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Branch</th>
                <th>Status</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='$usertype' and tableName='Staff' and allowed='staffDE' order by id ASC ");
       			$id=$staff->id;
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
                    <td>'. $staff->username.'';
                    if($usergroupPermission->num_rows()>0){
                        $output.='<div class="table-links">
                            <a href="#" id="delete_staff" class="text-danger" value="'.$id.'" ><i class="fa fa-trash"></i> </a>
                            <div class="bullet"></div>
                            <a href="#" id="edit_staff" class="text-success" value="'.$id.'" ><i class="fa fa-pen"></i></a>
                        </div>';
                    }
                    $output.='</td>
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'';
                    if($usergroupPermission->num_rows()>0){
                    	$output.='<div class="table-links">
             				<a href="#" class="resetStaffPassword text-warning" id="'.$staff->id.'">Reset Password</a>
            			</div>';
            		}
                    $output.='</td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td> 
                     ';
                    if($usergroupPermission->num_rows()>0){
                    	$output.='<td> ';
	                    if($staff->status==trim('Active')){ 
	                      $output.='<button type="submit" name="inactive" value="'.$staff->id.'" class="btn btn-success inactive">'.$staff->status.'
	                      </button>';
	                    }else {
	                       $output.='<button type="submit" name="active" value="'.$staff->id.'" class="btn btn-danger active">
	                        '.$staff->status.'
	                      </button>';
	                    }
	                    $output.='</td> ';
	                }else{
	                	$output.='<td> <div class="card card-header"> ';
	                    if($staff->status==trim('Active')){ 
	                      $output.='<button type="submit" disabled="disabled" value="'.$staff->id.'" class="btn btn-success ">'.$staff->status.'
	                      </button>';
	                    }else {
	                       $output.='<button type="submit" disabled="disabled" value="'.$staff->id.'" class="btn btn-danger">
	                        '.$staff->status.'
	                      </button>';
	                    }
	                    $output.='</div></td> ';
	                }
                    $output.='                       
                </tr>';
                $no++; 
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetchStaffsForExperience(){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No</th>
                <th>Full Name</th>
                <th>Mobile</th>
                <th>Gender</th>
                <th>Registration Date</th>
                <th>Branch</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$id=$staff->id;
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
                    <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                        <div class="table-links">
                            <a href="#" id="preparexp" class="text-info preparexp" value="'.$id.'" >view experience </a>
                        </div>
                    </td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->gender.'</td>
                    <td>'.$staff->dateregister.'</td>
                    <td>'.$staff->branch.'</td> ';
                    $output.='                        
                </tr>';
                $no++; 
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetchMyStaffsForExperience($branch){
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No</th>
                <th>Full Name</th>
                <th>Mobile</th>
                <th>Gender</th>
                <th>Registration Date</th>
                <th>Branch</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$id=$staff->id;
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
                    <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                        <div class="table-links">
                            <a href="#" id="preparexp" class="text-info preparexp" value="'.$id.'" >view experience </a>
                        </div>
                    </td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->gender.'</td>
                    <td>'.$staff->dateregister.'</td>
                    <td>'.$staff->branch.'</td> ';
                    $output.='                        
                </tr>';
                $no++; 
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetchStaffsToAttendanceSuper($branch,$today){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="row"> <div class="col-lg-4 col-6">
			    <div class="form-group">
					<input type="date" required="required" class="form-control" id="todayDate"/> 
				</div>
		    </div>
		    <div class="col-lg-4 col-6">
		    	<input type="number" class="form-control" placeholder="Late in Minute" id="lateMinute"/>
		    </div>
			</div>';
			$output.='<div class="">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>Name</th>
                <th>UserType</th>
                <th>Mobile</th>
                <th>Branch</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$id=$staff->id;
       			$queryChk=$this->db->query("select * from attendance where stuid='$id' and absentdate='$today' ");
       			if($queryChk->num_rows()>0){
       				$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
	                <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
	                <a href="#" class="atteInfo"><i class="fas fa-check-circle"></i></a>
	                </td>
	                <td>'.$staff->usertype.'</td>
	                <td>'.$staff->mobile.'</td>
	                <td>'.$staff->branch.'</td>                       
	                </tr>';
	                $no++;
       			}else{
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>                    
                    <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                    <div class="table-links">
                            <a href="#" id="absentStaff" class="text-danger" value="'.$id.'" >Absent </a>
                            <div class="bullet"></div>
                            <a href="#" id="lateStaff" class="text-warning" value="'.$id.'" >Late</a>
                            <div class="bullet"></div>
                            <a href="#" id="permissionStaff" class="text-success" value="'.$id.'" >Permission</a>
                        </div>
                    </td>
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td>                       
                	</tr>';
                	$no++; 
            	}
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetchStaffsToAttendanceAccessBranch($today,$mydivision){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="row"> <div class="col-lg-4">
			    <div class="form-group">
					<input type="date" required="required" class="form-control" id="todayDate"/> 
				</div>
		    </div>
		    <div class="col-lg-4">
		    	<input type="number" class="form-control" placeholder="Late in Minute" id="lateMinute"/>
		    </div>
			</div>';
			$output.='<div class="table-responsive">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>Name</th>
                <th>UserType</th>
                <th>Mobile</th>
                <th>Branch</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$id=$staff->id;
       			$queryChk=$this->db->query("select * from attendance where stuid='$id' and absentdate='$today' ");
       			if($queryChk->num_rows()>0){
       				$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
	                
	                <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
	                <a href="#" class="atteInfo"><i class="fas fa-check-circle"></i></a>
	                </td>
	                <td>'.$staff->usertype.'</td>
	                <td>'.$staff->mobile.'</td>
	                <td>'.$staff->branch.'</td>                       
	                </tr>';
	                $no++;
       			}else{
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
                    
                    <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                    <div class="table-links">
                            <a href="#" id="absentStaff" class="text-danger" value="'.$id.'" >Absent </a>
                            <div class="bullet"></div>
                            <a href="#" id="lateStaff" class="text-warning" value="'.$id.'" >Late</a>
                            <div class="bullet"></div>
                            <a href="#" id="permissionStaff" class="text-success" value="'.$id.'" >Permission</a>
                        </div>
                    </td>
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td>                       
                	</tr>';
                	$no++; 
            	}
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetchStaffsToAttendance($branch,$today,$mydivision){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('status2'=>$mydivision));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="row"> 
				<div class="col-lg-4 col-6">
				    <div class="form-group">
						<input type="date" required="required" class="form-control" id="todayDate"/> 
					</div>
			    </div>
			    <div class="col-lg-4 col-6">
			    	<input type="number" class="form-control" placeholder="Late in Minute" id="lateMinute"/>
			    </div>
			</div>';
			$output.='<div class="">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>Name</th>
                <th>UserType</th>
                <th>Mobile</th>
                <th>Branch</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$id=$staff->id;
       			$queryChk=$this->db->query("select * from attendance where stuid='$id' and absentdate='$today' ");
       			if($queryChk->num_rows()>0){
       				$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
	                
	                <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
	                <a href="#" class="atteInfo"><i class="fas fa-check-circle"></i></a>
	                </td>
	                <td>'.$staff->usertype.'</td>
	                <td>'.$staff->mobile.'</td>
	                <td>'.$staff->branch.'</td>                       
	                </tr>';
	                $no++;
       			}else{
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
                    
                    <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                    <div class="table-links">
                            <a href="#" id="absentStaff" class="text-danger" value="'.$id.'" >Absent </a>
                            <div class="bullet"></div>
                            <a href="#" id="lateStaff" class="text-warning" value="'.$id.'" >Late</a>
                            <div class="bullet"></div>
                            <a href="#" id="permissionStaff" class="text-success" value="'.$id.'" >Permission</a>
                        </div>
                    </td>
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td>                       
                	</tr>';
                	$no++; 
            	}
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetch_teachers(){
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('status'=>'Active'));
		$this->db->like('usertype','Teacher');
		$this->db->order_by('id','random()');
		$this->db->limit('4');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetch_blogs(){
		$this->db->select('*');
		$this->db->from('blogs');
		$this->db->join('users',
		'users.username=blogs.postby');
		$query = $this->db->get();
        return $query->result();
	}
	function fetch_ViewBlogs($title){
		$this->db->select('*');
		$this->db->from('blogs');
		$this->db->join('users',
		'users.username=blogs.postby');
		$this->db->where('ntitle',$title);
		$query = $this->db->get();
        return $query->result();
	}
	function applyjobsnow($title){
		$this->db->where('vid',$title);
		$query = $this->db->get('vacancy');
        return $query->result();
	}
	function fetch_vacancy(){
		$this->db->select('*');
		$this->db->from('vacancy');
		$this->db->join('users',
		'users.username=vacancy.postby');
		$query = $this->db->get();
        return $query->result();
	}
	function fetch_all_teachers(){
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('status'=>'Active'));
		$this->db->like('usertype','Teacher');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetch_students($max_year){
		$this->db->order_by('fname','DESC');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->like('usertype','Student');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetch_inactivestudents($max_year){
		$this->db->order_by('fname,mname,lname','ASC');
		$this->db->where(array('status'=>'Inactive'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('usertype','Student');
		$query=$this->db->get('users');
		return $query;
	}
	function fetch_branchstudents($gs_branches,$onlyGrade,$gs_gradesec,$max_year){
		if($onlyGrade==''){
		  $query=$this->db->query("select * from users where status='Active' and isapproved='1' and usertype='Student' and academicyear='$max_year' and branch='$gs_branches'
		and gradesec='$gs_gradesec' order by fname,mname,lname ASC ");  
		}else{
		    $query=$this->db->query("select * from users where status='Active' and isapproved='1' and usertype='Student' and academicyear='$max_year' and branch='$gs_branches'
		and grade='$onlyGrade' order by fname,mname,lname ASC ");
		}
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
            <th>Gender</th>
            <th>Branch</th>
            <th>Mark & Attendance</th>
        </tr>
        </thead>
       <tbody>';
        $no=1;
		foreach ($query ->result() as $value) {
			$id=$value->id;
			$output .='<tr class="delete_mem'.$value->id.'">
			<td>'.$no.'.</td>
			<td>'.$value->unique_id.'
            <div class="table-links">
             <a href="#" class="deletestudent text-danger" id="'.$value->id.'"><i class="fas fa-trash-alt"></i></a>
             <div class="bullet"></div>
             <a href="#" class="dropstudent text-warning" id="'.$value->id.'"><i class="fas fa-user-times"></i></a>
             <div class="bullet"></div>
             <a href="#" class="editstudent text-success" id="'.$value->unique_id.'" value="'.$max_year.'"><i class="fas fa-user-edit"></i></a>
             <div class="bullet"></div>
             <a href="#" class="viewStudentPrint" value="" data-toggle="modal" data-target="#printStudentViewModal" id="'.$value->unique_id.'"> <span class="text-info"><i class="fas fa-eye"></i></span></a>
            </div>
            </td>
            <td>'.$value->fname .' '.$value->mname.' '.$value->lname.'
            <div class="table-links">
             	<a href="#" class="resetPassword text-warning" id="'.$value->unique_id.'">Reset Password</a>
            </div>
            </td> ';
            if($gs_gradesec==''){
            	$output.='<td>'.$value->grade.'</td>';
            }else{
            	$output.='<td>'.$value->gradesec.'</td>';
            }
            $output.='
            <td>'.$value->gender.'</td>
            <td>'.$value->branch.' </td>   
            <td class="text-center"><a href="#" target="_blanck"><button class="btn btn-default" id="viewStuAttendance" name="'.$max_year.'" value="'.$value->username.'"><span class="text-info"> View</span></button></a> </td>   
            </tr>';
            $no++;
		}
		return $output;
	}
	function fecthThiStudentAttendance($stuID,$max_year,$gs_quarter){
		$output='';
		$queryStudent=$this->db->query("select branch,gradesec,id,username,fname,lname,mname from users where academicyear='$max_year' and username='$stuID' ");
		$rowStudent=$queryStudent->row();
		$id=$rowStudent->id;
		$username=$rowStudent->username;
		$fname=$rowStudent->fname;
		$lname=$rowStudent->lname;
		$mname=$rowStudent->mname;
		$gs_branches=$rowStudent->branch;
		$gs_gradesec=$rowStudent->gradesec;
		$queryFetchMark=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and quarter='$gs_quarter' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and stuid='$id' group by subname order by subname ASC ");
			if($queryFetchMark->num_rows()>0){
				$output.='<button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyAttendancePrint()"> <i class="fas fa-print"></i> </button>
        		<div id="prinThiStudentAttendance">';
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
					
					$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

					$output.='<div class="table-responsive">
	        		<table class="tabler table-borderedr table-hover" style="width:100%;height:auto;page-break-inside:avoid;">
	        		<tr>
	        		<th rowspan="3">No.</th>
	            	<th rowspan="3">Student Name</th>
	            	<th rowspan="3" class="text-center">Student ID</th>';
        			foreach ($evalname_query->result_array() as $evalua_name) 
        			{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and stuid='$id' group by markname order by mid ASC");
	            		$colSpan=$queryMvalue->num_rows() +2;
	            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
	            	}
	            	$output.='<th class="text-center"><B>Total</B></th><th rowspan="3" class="text-center">Sig.</th><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and stuid='$id' group by markname order by mid ASC");
		            	foreach ($queryMvalue->result() as $mark_name)
		            	{
		            		$output.='<td class="text-center">'.$mark_name->markname.'</td>';
		            	}
		            	$output.='<td class="text-center"><b>Tot</b></td>';
		            	$output.='<td class="text-center"><b>Conv</b></td>';
		            }
		            $output.='<td rowspan="2" class="text-center"> <B>100</B> </td>';
	            	$output.='</tr><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$percent=$evalua_name['percent'];
	            		$queryMvalue1 = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and stuid='$id' group by markname order by mid ASC",FALSE);
		            	$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and stuid='$id' group by markname order by mid ASC");
            			$sumOutOf=0;
	            		foreach ($queryMvalue->result_array() as $mark_name) {
	            			$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            			$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            		}
	        			$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        			$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
		            }
	            	$output.='</tr>';
	            	$stuNO=1;
	            	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' and id='$id' group by u.id order by u.fname,u.mname,u.lname ASC ");
				foreach ($queryStudent->result_array() as $row) { 
            		$id=$row['id'];
            		$output.='<tr><td>'.$stuNO.'.</td> <td>'.$row['fname'].' '.$row['mname'].' '.$row['lname']. '</td>';
            		$output.='<td class="text-center">'.$row['username'].' </td>';
            		$average=0;
            		foreach ($evalname_query->result_array() as $mark_name)
            		{
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
	            		$query_value = $this->db->query("select markname,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and stuid='$id' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' and stuid='$id' group by markname order by mid ASC");
								
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and stuid='$id' group by markname order by mid ASC");
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
                	<i class="fas fa-check-circle"> </i> Mark data not found.
            	</div></div>';
			}
		$this->db->where('attendance.stuid',$stuID);
		$this->db->where('attendance.academicyear',$max_year);
		$this->db->where('users.academicyear',$max_year);
		$this->db->order_by('attendance.absentdate','DESC');
		$this->db->select('*');
        $this->db->from('attendance');
        $this->db->join('users', 
            'users.username = attendance.stuid');
        $query = $this->db->get();
      
        if($query->num_rows()>0){
        	$totAb=$query->num_rows();
        	$output.='<div class="row"><div class="col-lg-4 col-md-4 col-12"></div>
        	<div class="col-lg-8 col-md-8 col-12">
        	<div class="table-responsive">
                <table class="tabler table-borderedr text-center" style="width:auto;">
                <thead>
                    <tr>
                    <th>Student Name</th>
                    <th>Student ID</th>
                    <th>Grade</th>
                    <th>Absent Type</th>
                    <th>Absent Date</th>
                    </tr>
                </thead>
            <tbody>';
            $no=1;
            $rowSpan=$totAb + $no;
            $output.='<tr>
            <td rowspan="'.$rowSpan.'"> '.$fname.' '.$mname.' '.$lname.'  </td>
            <td rowspan="'.$rowSpan.'">'.$username.'</td>
            <td rowspan="'.$rowSpan.'">'.$gs_gradesec.'</td>';
	        foreach ($query->result() as $fetch_today_attendances) {
	        	$output.='<tr>
                <td>'.$fetch_today_attendances->absentype.' </td>
                <td>'.$fetch_today_attendances->absentdate.'</td>
                </tr>';$no++;
	        }
	        $num=$no-1;
	        $output.='</tbody> </table> </div> </div> </div>';
	        $output.='<div class="text-center"><span class ="badge badge-success">Total Absent Days '.$num.'.</span></div></div>';
	    }else{
	    	$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No absent days found.
            </div></div>';
	    }
        return $output;
	}
	function fetchFinanceBranchStudents($gs_branches,$gs_gradesec,$max_year){
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
		$output .='
         <div class="table-responsive">
        <table class="table table-striped table-hover" style="width:100%;">
        <thead>
        <tr>
        <th>No.</th>
            <th>ID</th>
            <th>Name</th>
            <th>Grade</th>
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
		return $output;
	}
	function fetch_thisgrade_branchstudents($gs_branches,$gs_gradesec,$max_year){
		$this->db->order_by('fname','ASC');
		$this->db->order_by('mname','ASC');
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('grade'=>$gs_gradesec));
		$this->db->where(array('branch'=>$gs_branches));
		$this->db->like('usertype','Student');
		$query=$this->db->get('users');
		$output ='';
		if($query->num_rows()>0){
			$output .='
	         <div class="table-responsive">
	        <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
	        <thead>
	        <tr>
	        	<th>No.</th>
	        	<th>Student Name</th>
	            <th>Student ID</th>
	            <th>Gender</th>
	            <th>Grade</th>
	            <th>Section</th>
	            <th>Branch</th>
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
	            <td>'.$value->grade.'</td>
	            <td>'.$value->gradesec.'</td>
	            <td>'.$value->branch.' </td> </tr>';
	            $no++;
			}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                   <i class="fas fa-exclamation-circle"> </i> Record not found.
            </div></div>';
		}
		return $output;
	}
	function fetch_thisSummaryRecord($gs_branches,$gs_gradesec,$max_year){
		$this->db->order_by('fname,mname,lname','ASC');
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where('grade',$gs_gradesec);
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
		$query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where grade='$gs_gradesec' and academicyear='$max_year' and branch='$gs_branches' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
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
	function fetch_thisTransportService($gs_branches,$checkedGrade,$checkedPlace,$max_year){
		$output ='';
		$query_school=$this->db->get('school');
					$schoolRow=$query_school->row();
					$website=$schoolRow->website;
					$logo=$schoolRow->logo;
					$name=$schoolRow->name;
					$phone=$schoolRow->phone;
		$output .='<p><h2 class="text-center"><u><b>'.$name. ' Transport Service Statistics in '.$max_year.' Academic Year</b></u></h2></p>
        <div class="table-responsive">
        <table class="tabler table-borderedr table-hover" style="width:100%;">
        <thead>
        <tr>
        	<th>No.</th>
        	<th>Name</th>
            <th>Student ID</th>
            <th>Branch</th>
            <th>Transport</th>
            <th>Gender</th> 
            <th>Section</th>
        </tr>
        </thead>
       <tbody>';
       $no=1;
       	foreach($checkedGrade as $checkedGrades){
       		foreach($checkedPlace as $checkedPlaces){
		       	$this->db->order_by('fname,mname,lname','ASC');
				$this->db->where(array('status'=>'Active'));
				$this->db->where(array('isapproved'=>'1'));
				$this->db->where(array('academicyear'=>$max_year));
				$this->db->where('grade',$checkedGrades);
				$this->db->where(array('branch'=>$gs_branches));
				$this->db->where(array('transportservice'=>$checkedPlaces));
				$this->db->like('usertype','Student');
				$query=$this->db->get('users');
				foreach ($query ->result() as $value) {
					$id=$value->id;
					$output .='<tr class="delete_mem'.$value->id.'">
					<td>'.$no.'. </td>
					<td>'.$value->fname .' '.$value->mname.' '.$value->lname.' </td> 
					<td>'.$value->username.' </td>
					<td>'.$value->branch.' </td>
					<td>'.$value->transportservice.' </td>
					<td>'.$value->gender.'</td>
		            <td>'.$value->gradesec.'</td> 
		            </tr>';
		            $no++;
				}
			}
		}
		$output.='</tbody></table></div><div class="dropdown-divider"></div>';
		return $output;
	}
	function fetch_thisTransportServiceSection($gs_branches,$checkedGrade,$checkedPlace,$max_year){
		$output ='';
		$query_school=$this->db->get('school');
					$schoolRow=$query_school->row();
					$website=$schoolRow->website;
					$logo=$schoolRow->logo;
					$name=$schoolRow->name;
					$phone=$schoolRow->phone;
		$output .='<p><h2 class="text-center"><u><b>'.$name. ' Transport Service Statistics in '.$max_year.' Academic Year</b></u></h2></p>
        <div class="table-responsive">
        <table class="tabler table-borderedr table-hover" style="width:100%;">
        <thead>
        <tr>
        	<th>No.</th>
        	<th>Name</th>
            <th>Student ID</th>
            <th>Branch</th>
            <th>Transport</th>
            <th>Gender</th> 
            <th>Section</th>
        </tr>
        </thead>
       <tbody>';
       $no=1;
       	foreach($checkedGrade as $checkedGrades){
       		foreach($checkedPlace as $checkedPlaces){
		       	$this->db->order_by('fname,mname,lname','ASC');
				$this->db->where(array('status'=>'Active'));
				$this->db->where(array('isapproved'=>'1'));
				$this->db->where(array('academicyear'=>$max_year));
				$this->db->where('gradesec',$checkedGrades);
				$this->db->where(array('branch'=>$gs_branches));
				$this->db->where(array('transportservice'=>$checkedPlaces));
				$this->db->like('usertype','Student');
				$query=$this->db->get('users');
				foreach ($query ->result() as $value) {
					$id=$value->id;
					$output .='<tr class="delete_mem'.$value->id.'">
					<td>'.$no.'. </td>
					<td>'.$value->fname .' '.$value->mname.' '.$value->lname.' </td> 
					<td>'.$value->username.' </td>
					<td>'.$value->branch.' </td>
					<td>'.$value->transportservice.' </td>
					<td>'.$value->gender.'</td>
		            <td>'.$value->gradesec.'</td> 
		            </tr>';
		            $no++;
				}
			}
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
		$output='<p><h2 class="text-center"><b><u>'.$name.' Grade Statistics for '.$max_year.' Academic Year <small>('.$gs_branches.' Branch)</small></u></b></h2></p>
		<div class="table-responsive">
		<table class="tabler table-bordered table-hover" style="width:100%;">';
		$no=1;$grandTotal=0;$grandMale=0;$grandFemale=0;
		foreach($gs_gradesec as $gs_gradesecs){
			$querySection=$this->db->query("select gradesec from users where academicyear='$max_year' and branch='$gs_branches' and grade='$gs_gradesecs' and gradesec!='' group by gradesec order by gradesec ");
			if($querySection->num_rows()>0){
				$totalSection=($querySection->num_rows() * 3) + 5;
				$output.='<tr><td rowspan='.$totalSection.'>'.$no.'</td><td rowspan='.$totalSection.'> Grade: '.$gs_gradesecs.'</td>';
			    foreach($querySection->result() as $sectionNum){
			    	$gradesec=$sectionNum->gradesec;
					$output.='<tr><td rowspan="3">'.$sectionNum->gradesec.'</td>';
					$gradeSecTotal = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where gradesec='$gradesec' and academicyear='$max_year' and branch='$gs_branches' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
					foreach ($gradeSecTotal->result() as $value) {
						$output.='<tr><td>Male</td><td>'.$value->malecount.'</td><td rowspan="2" class="text-center"> <span class="badge badge-primary">'.$value->studentcount.'</span></td> </tr>';
						$output.='<tr><td>Female</td><td>'.$value->femalecount.'</td></tr>';
					}
					$output.='</tr>';
				}
				$gradeTotal = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where grade='$gs_gradesecs' and academicyear='$max_year' and branch='$gs_branches' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
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
				$querySection=$this->db->query("select gradesec from users where academicyear='$max_year' and branch='$gs_branches' and grade='$gs_gradesecs' and gradesec!='' and age='$summaryGSAges' group by gradesec order by gradesec ");
				if($querySection->num_rows()>0){
					$totalSection=($querySection->num_rows() * 3) + 5;
					$output.='<tr><td rowspan='.$totalSection.'>'.$no.'.</td><td rowspan='.$totalSection.'> Grade: '.$gs_gradesecs.' Age: '.$summaryGSAges.'</td>';
				    foreach($querySection->result() as $sectionNum){
				    	$gradesec=$sectionNum->gradesec;
						$output.='<tr><td rowspan="3">'.$sectionNum->gradesec.'</td>';
						$gradeSecTotal = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where gradesec='$gradesec' and academicyear='$max_year' and branch='$gs_branches' and age='$summaryGSAges' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
						foreach ($gradeSecTotal->result() as $value) {
							$output.='<tr><td>Male</td><td>'.$value->malecount.'</td><td rowspan="2" class="text-center"> <span class="badge badge-primary">'.$value->studentcount.'</span></td> </tr>';
							$output.='<tr><td>Female</td><td>'.$value->femalecount.'</td></tr>';
						}
						$output.='</tr>';
					}
					$gradeTotal = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where grade='$gs_gradesecs' and academicyear='$max_year' and branch='$gs_branches' and age='$summaryGSAges' GROUP BY academicyear ORDER BY fname,mname,lname ASC");
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
	function fetch_thisdiv_branchstudents($gs_branches,$gs_gradesec,$max_year){
		if($gs_gradesec==='KG'){
			$array=array('KG1','KG2','KG3','Nursery','LKG','UKG');
		}else if($gs_gradesec==='div1'){
			$array=array('1','2','3','4');
		}else if($gs_gradesec==='div5'){
			$array=array('5','6','7','8');
		}else if($gs_gradesec==='div9'){
			$array=array('9','10','11','12','12n','11n','12s','11s');
		}
		$this->db->order_by('fname','ASC');
		$this->db->order_by('mname','ASC');
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where_in('grade',$array);
		$this->db->where(array('branch'=>$gs_branches));
		$this->db->like('usertype','Student');
		$query=$this->db->get('users');
		$output ='';
		if($query->num_rows()>0){
			$output .='
	         <div class="table-responsive">
	        <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
	        <thead>
	        <tr>
	        	<th>No.</th>
	        	<th>Name</th>
	            <th>Student ID</th>
	            <th>Gender</th>            
	            <th>Grade</th>
	            <th>Section</th>
	            <th>Branch</th>
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
	            <td>'.$value->grade.'</td>
	            <td>'.$value->gradesec.'</td>
	            <td>'.$value->branch.' </td> </tr>';
	            $no++;
			}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div>';
		}
		return $output;
	}
	function fetch_grade_branchstudents($gs_branches,$gs_gradesec,$max_year){
		$this->db->order_by('fname','ASC');
		$this->db->order_by('mname','ASC');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('gradesec'=>$gs_gradesec));
		$this->db->where(array('branch'=>$gs_branches));
		$this->db->like('usertype','Student');
		$query=$this->db->get('users');
		$output ='';
		if($query->num_rows()>0){
			$output .='
	         <div class="table-responsive">
	        <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
	        <thead>
	        <tr>
	        	<th>No.</th>
	        	<th>Student Name</th>
	            <th>Student ID</th>
	            <th>Gender</th>
	            <th>Grade</th>
	            <th>Section</th>
	            <th>Branch</th>
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
	            <td>'.$value->grade.'</td>
	            <td>'.$value->gradesec.'</td>
	            <td>'.$value->branch.' </td> </tr>';
	            $no++;
			}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div>';
		}
		return $output;
	}
	function fetch_student_idcard($max_year,$checkStudent,$gradesec,$placeIDs,$branch,$gyear){
		$output='<div class="row">';
		foreach($placeIDs as $placeID){
			foreach($checkStudent as $checkStudents){
				$this->db->order_by('fname,mname,lname','ASC');
				$this->db->where(array('transportservice'=>$placeID));
				$this->db->where(array('id'=>$checkStudents));
				$this->db->where(array('status'=>'Active'));
				$this->db->where(array('branch'=>$branch));
				$this->db->where(array('isapproved'=>'1'));
				$this->db->where(array('academicyear'=>$max_year));
				$this->db->like('usertype','Student');
				$query=$this->db->get('users');
				
				$queryLinks=$this->db->get('links');
				$linksRow=$queryLinks->row();
				$telegram=$linksRow->telegram;

				foreach ($query->result() as $staff) {
					$query_school=$this->db->get('school');
					$schoolRow=$query_school->row();
					$website=$schoolRow->website;
					$logo=$schoolRow->logo;
					$name=$schoolRow->name;
					$phone=$schoolRow->phone;
					$output.='<script>var qrcode = new QRCode(
					      "qr-code'.$staff->id.'",
					      {
					        text: "Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.', ID: '.$staff->username.',Transport Service '.$placeID.' ,Website: '.$website.'",
					        width:80,
					        height:60,
					        colorDark : "#000000",
					        colorLight : "#FFFFFF",
					        correctLevel : QRCode.CorrectLevel.M
					      }
					    );</script>';
					$output.='<div class="col-md-4 col-12"><div class="StudentViewTextInfo" style="background:url(../img/bg.jpg);background-size:cover; background-position:center; background-repeat:no-repeat;">';
		            $output.='<div class="row">
						<div class="col-lg-3 col-md-3 col-3">
			          		<img src="'.base_url().'/logo/'. $logo.'" alt="logo" style="height: 80px;width: 80px;"> 
			          	</div>
			          	<div class="col-lgs-9 col-md-9 col-9">
			          		<h5><b>'.$name.'</b></h5>'.$gyear.' Academic Year <br> STUDENT ID CARD
			          	</div>
			        </div><div class="dropdown-divider"></div>';
					$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="row">
				                <div class="col-md-12 col-12" style="white-space: nowrap">
				                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
				                </div>
				                <div class="col-md-6 col-6">
				                    <small>ID: '.$staff->username.'</small>
				                </div>
				                <div class="col-md-6 col-6">
				                    <small>GRADE: '.$staff->gradesec.'</small>
				                </div>
				          
			                </div>
			            </div>';
						$output.='<div class="col-lg-3 col-md-3 col-3">
				                	<div class="dropdown-divider"></div>
				                	<p id="qr-code'.$staff->id.'"></p>
						        </div>
				                <div class="col-lg-6 col-md-6 col-6">
				                <div class="dropdown-divider"></div><small>PARENT PHONE:';
				                if($staff->mobile!='' || $staff->mobile!='0'){
				                    $output.=' '.$staff->mobile.' || ';
				                }else{
				                    $output.=' - ';
				                }
				                if($staff->father_mobile!='' || $staff->father_mobile!='0'){
				                    $output.=' '.$staff->father_mobile.' ';
				                }else{
				                    $output.=' - ';
				                }
							    $output.='<br>
							      	<h4 style="white-space: nowrap"><u><b>'.$placeID.'</b></u></h4> </small>
				                </div>


						<div class="col-lg-3 col-md-3 col-3 pull-left">';
			                if($staff->profile == ''){
								$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
							}else{
								$output.='<img alt="Photo" src="'.base_url().'/profile/'.$staff->profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
							}
		                $output.='</div> </div>
		            </div></div>';
		        }
			}
		}
		$output.='</div>';
		return $output;
	}
	function fetch_student_idcard_seattle($max_year,$checkStudent,$gradesec,$placeIDs,$branch,$gyear){
		$output='<div class="row">';
		foreach($placeIDs as $placeID){
			foreach($checkStudent as $checkStudents){
				$this->db->order_by('fname,mname,lname','ASC');
				$this->db->where(array('transportservice'=>$placeID));
				$this->db->where(array('id'=>$checkStudents));
				$this->db->where(array('status'=>'Active'));
				$this->db->where(array('branch'=>$branch));
				$this->db->where(array('isapproved'=>'1'));
				$this->db->where(array('academicyear'=>$max_year));
				$this->db->like('usertype','Student');
				$query=$this->db->get('users');
				
				$queryLinks=$this->db->get('links');
				$linksRow=$queryLinks->row();
				$telegram=$linksRow->telegram;

				foreach ($query->result() as $staff) {
					$query_school=$this->db->get('school');
					$schoolRow=$query_school->row();
					$website=$schoolRow->website;
					$logo=$schoolRow->logo;
					$name=$schoolRow->name;
					$phone=$schoolRow->phone;
					$output.='<script>var qrcode = new QRCode(
					      "qr-code'.$staff->id.'",
					      {
					        text: "Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.', ID: '.$staff->username.',Transport Service '.$placeID.' ,Website: '.$website.'",
					        width:80,
					        height:60,
					        colorDark : "#000000",
					        colorLight : "#FFFFFF",
					        correctLevel : QRCode.CorrectLevel.M
					      }
					    );</script>';
					$output.='<div class="col-md-4 col-12"><div class="StudentViewTextInfo" style="background:url(../img/bg.jpg);background-size:cover; background-position:center; background-repeat:no-repeat;">';
		            $output.='<div class="row">
						<div class="col-lg-3 col-md-3 col-3">
			          		<img src="'.base_url().'/logo/'. $logo.'" alt="logo" style="height: 80px;width: 80px;"> 
			          	</div>
			          	<div class="col-lgs-9 col-md-9 col-9">
			          		<h5><b>'.$name.'</b></h5>'.$gyear.' Academic Year <br> STUDENT ID CARD<br>
			          		'.$phone.'
			          	</div>
			        </div><div class="dropdown-divider"></div>';
					$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="row">
				                <div class="col-md-12 col-12" style="white-space: nowrap">
				                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
				                </div>
				                <div class="col-md-6 col-6">
				                    <small>ID: '.$staff->username.'</small>
				                </div>
				                <div class="col-md-6 col-6">
				                    <small>GRADE: '.$staff->gradesec.'</small>
				                </div>
				          
			                </div>
			            </div>';
						$output.='<div class="col-lg-3 col-md-3 col-3">
				                	<div class="dropdown-divider"></div>
				                	<p id="qr-code'.$staff->id.'"></p>
						        </div>
				                <div class="col-lg-6 col-md-6 col-6">
				                <div class="dropdown-divider"></div><small>PARENT PHONE:';
				                if($staff->mobile!='' || $staff->mobile!='0'){
				                    $output.=' '.$staff->mobile.' || ';
				                }else{
				                    $output.=' - ';
				                }
				                if($staff->father_mobile!='' || $staff->father_mobile!='0'){
				                    $output.=' '.$staff->father_mobile.' ';
				                }else{
				                    $output.=' - ';
				                }
							    $output.='<br>
							      	<h4 style="white-space: nowrap"><u><b>'.$placeID.'</b></u></h4> </small>
				                </div>


						<div class="col-lg-3 col-md-3 col-3 pull-left">';
			                if($staff->profile == ''){
								$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
							}else{
								$output.='<img alt="Photo" src="'.base_url().'/profile/'.$staff->profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
							}
		                $output.='</div> 
		                <div class="col-md-12 col-12">
			            	<p><i class="fas fa-website"></i> '.$website.' | <i class="fab fa-telegram-plane"></i> '.$telegram.'</p>
			            </div></div>
		            </div></div>';
		        }
			}
		}
		$output.='</div>';
		return $output;
	}
	function fetch_student_idcard_eeaa($max_year,$checkStudent,$gradesec,$placeIDs,$branch,$gyear){
		$output='<div class="row">';
		foreach($placeIDs as $placeID){
			foreach($checkStudent as $checkStudents){
				$this->db->order_by('fname,mname,lname','ASC');
				$this->db->where(array('transportservice'=>$placeID));
				$this->db->where(array('id'=>$checkStudents));
				$this->db->where(array('status'=>'Active'));
				$this->db->where(array('branch'=>$branch));
				$this->db->where(array('isapproved'=>'1'));
				$this->db->where(array('academicyear'=>$max_year));
				$this->db->like('usertype','Student');
				$query=$this->db->get('users');
				
				$queryLinks=$this->db->get('links');
				$linksRow=$queryLinks->row();
				$telegram=$linksRow->telegram;

				foreach ($query->result() as $staff) {
					$query_school=$this->db->get('school');
					$schoolRow=$query_school->row();
					$website=$schoolRow->website;
					$logo=$schoolRow->logo;
					$name=$schoolRow->name;
					$phone=$schoolRow->phone;
					$output.='<script>var qrcode = new QRCode(
					      "qr-code'.$staff->id.'",
					      {
					        text: "Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.', ID: '.$staff->username.',Transport Service '.$placeID.' ,Website: '.$website.'",
					        width:80,
					        height:60,
					        colorDark : "#000000",
					        colorLight : "#FFFFFF",
					        correctLevel : QRCode.CorrectLevel.M
					      }
					    );</script>';
					$output.='<div class="col-md-4 col-12"><div class="StudentViewTextInfo" style="background:url(../img/bg.jpg);background-size:cover; background-position:center; background-repeat:no-repeat;">';
		            $output.='<div class="row">
						<div class="col-lg-3 col-md-3 col-3">
			          		<img src="'.base_url().'/logo/'. $logo.'" alt="logo" style="height: 80px;width: 80px;"> 
			          	</div>
			          	<div class="col-lgs-9 col-md-9 col-9">
			          		<h5><b>'.$name.'</b></h5>'.$gyear.' Academic Year <br> STUDENT ID CARD
			          	</div>
			        </div><div class="dropdown-divider"></div>';
					$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="row">
				                <div class="col-md-12 col-12" style="white-space: nowrap">
				                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
				                </div>
				                <div class="col-md-6 col-6">
				                    <small>ID: '.$staff->username.'</small>
				                </div>
				                <div class="col-md-6 col-6">
				                    <small>GRADE: '.$staff->gradesec.'</small>
				                </div>
				          
			                </div>
			            </div>';
						$output.='<div class="col-lg-3 col-md-3 col-3">
				                	<div class="dropdown-divider"></div>
				                	<p id="qr-code'.$staff->id.'"></p>
						        </div>
				                <div class="col-lg-6 col-md-6 col-6">
				                <div class="dropdown-divider"></div><small>PARENT PHONE:';
				                if($staff->mobile!='' || $staff->mobile!='0'){
				                    $output.=' '.$staff->mobile.' || ';
				                }else{
				                    $output.=' - ';
				                }
				                if($staff->father_mobile!='' || $staff->father_mobile!='0'){
				                    $output.=' '.$staff->father_mobile.' ';
				                }else{
				                    $output.=' - ';
				                }
							    $output.='<br>
							      	<h4 style="white-space: nowrap"><u><b>'.$placeID.'</b></u></h4> </small>
				                </div>


						<div class="col-lg-3 col-md-3 col-3 pull-left">';
			                if($staff->profile == ''){
								$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
							}else{
								$output.='<img alt="Photo" src="'.base_url().'/profile/'.$staff->profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
							}
		                $output.='</div> 
		                <div class="col-md-12 col-12">
			            	<p><i class="fas fa-website"></i> '.$website.' | <i class="fab fa-telegram-plane"></i> '.$telegram.' | <i class="fas fa-phone"></i> '.$phone.' </p>
			            </div></div>
		            </div></div>';
		        }
			}
		}
		$output.='</div>';
		return $output;
	}
	function fetchStudentIdCardSeattle($max_year,$checkStudent,$gradesec,$branch,$gyear){
		$output='<div class="row">';
		foreach($checkStudent as $checkStudents){
			$this->db->order_by('fname,mname,lname','ASC');
			$this->db->where(array('id'=>$checkStudents));
			$this->db->where(array('status'=>'Active'));
			$this->db->where(array('branch'=>$branch));
			$this->db->where(array('isapproved'=>'1'));
			$this->db->where(array('academicyear'=>$max_year));
			$this->db->like('usertype','Student');
			$query=$this->db->get('users');
			
			$queryLinks=$this->db->get('links');
			$linksRow=$queryLinks->row();
			$telegram=$linksRow->telegram;

			foreach ($query->result() as $staff) {
				$query_school=$this->db->get('school');
				$schoolRow=$query_school->row();
				$website=$schoolRow->website;
				$logo=$schoolRow->logo;
				$name=$schoolRow->name;
				$phone=$schoolRow->phone;
				$output.='<script>var qrcode = new QRCode(
				      "qr-code'.$staff->id.'",
				      {
				        text: "Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.', ID: '.$staff->username.', Website: '.$website.'",
				        width:80,
				        height:60,
				        colorDark : "#000000",
				        colorLight : "#FFFFFF",
				        correctLevel : QRCode.CorrectLevel.M
				      }
				    );</script>';
				$output.='<div class="col-md-4 col-12"><div class="StudentViewTextInfo" style="background:url(../img/bg.jpg);background-size:cover;background-position:center; background-repeat:no-repeat;">';
	            $output.='<div class="row">
					<div class="col-lg-3 col-md-3 col-3">
		          		<img src="'.base_url().'/logo/'. $logo.'" alt="logo" style="height: 80px;width: 80px;"> 
		          	</div>
		          	<div class="col-lgs-9 col-md-9 col-9">
		          		<h5><b>'.$name.'</b></h5>'.$gyear.' Academic Year <br> STUDENT ID CARD<br>
		          		'.$phone.'
		          	</div>
		        </div><div class="dropdown-divider"></div>';
				$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12">
						<div class="row">
			                <div class="col-md-12 col-12" style="white-space: nowrap">
			                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
			                </div>
			                <div class="col-md-6 col-6">
			                    <small>ID: '.$staff->username.'</small>
			                </div>
			                <div class="col-md-6 col-6">
			                    <small>GRADE: '.$staff->gradesec.'</small>
			                </div>
			            
		                </div>
		            </div>';
					$output.='<div class="col-md-3 col-3">
			                	<div class="dropdown-divider"></div>
			                	<p id="qr-code'.$staff->id.'"></p>
					        </div>
			                <div class="col-lg-6 col-md-6 col-6">
			                <div class="dropdown-divider"></div>
						      	<small>PARENT PHONE: '.$staff->mobile.' || '.$staff->father_mobile.' </small>
			                </div>
					<div class="col-lg-3 col-md-3 col-3 pull-left">';
		                if($staff->profile == ''){
							$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
						}else{
							$output.='<img alt="Photo" src="'.base_url().'/profile/'.$staff->profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
						}
	                $output.='</div> 
	                <div class="col-md-12 col-12">
			            	<p><i class="fas fa-website"></i> '.$website.' | <i class="fab fa-telegram-plane"></i> '.$telegram.'</p>
			            </div>
			            </div>
	            </div></div>';
			}
		}
		$output.='</div>';
		return $output;
	}
	function fetchStudentIdCardEeaa($max_year,$checkStudent,$gradesec,$branch,$gyear){
		$output='<div class="row">';
		foreach($checkStudent as $checkStudents){
			$this->db->order_by('fname,mname,lname','ASC');
			$this->db->where(array('id'=>$checkStudents));
			$this->db->where(array('status'=>'Active'));
			$this->db->where(array('branch'=>$branch));
			$this->db->where(array('isapproved'=>'1'));
			$this->db->where(array('academicyear'=>$max_year));
			$this->db->like('usertype','Student');
			$query=$this->db->get('users');
			
			$queryLinks=$this->db->get('links');
			$linksRow=$queryLinks->row();
			$telegram=$linksRow->telegram;

			foreach ($query->result() as $staff) {
				$query_school=$this->db->get('school');
				$schoolRow=$query_school->row();
				$website=$schoolRow->website;
				$logo=$schoolRow->logo;
				$name=$schoolRow->name;
				$phone=$schoolRow->phone;
				$output.='<script>var qrcode = new QRCode(
				      "qr-code'.$staff->id.'",
				      {
				        text: "Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.', ID: '.$staff->username.', Website: '.$website.'",
				        width:80,
				        height:60,
				        colorDark : "#000000",
				        colorLight : "#FFFFFF",
				        correctLevel : QRCode.CorrectLevel.M
				      }
				    );</script>';
				$output.='<div class="col-md-4 col-12"><div class="StudentViewTextInfo" style="background:url(../img/bg.jpg);background-size:cover;background-position:center; background-repeat:no-repeat;">';
	            $output.='<div class="row">
					<div class="col-lg-3 col-md-3 col-3">
		          		<img src="'.base_url().'/logo/'. $logo.'" alt="logo" style="height: 80px;width: 80px;"> 
		          	</div>
		          	<div class="col-lgs-9 col-md-9 col-9">
		          		<h5><b>'.$name.'</b></h5>'.$gyear.' Academic Year <br> STUDENT ID CARD
		          	</div>
		        </div><div class="dropdown-divider"></div>';
				$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12">
						<div class="row">
			                <div class="col-md-12 col-12" style="white-space: nowrap">
			                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
			                </div>
			                <div class="col-md-6 col-6">
			                    <small>ID: '.$staff->username.'</small>
			                </div>
			                <div class="col-md-6 col-6">
			                    <small>GRADE: '.$staff->gradesec.'</small>
			                </div>
			            
		                </div>
		            </div>';
					$output.='<div class="col-md-3 col-3">
			                	<div class="dropdown-divider"></div>
			                	<p id="qr-code'.$staff->id.'"></p>
					        </div>
			                <div class="col-lg-6 col-md-6 col-6">
			                <div class="dropdown-divider"></div>
						      	<small>PARENT PHONE: '.$staff->mobile.' || '.$staff->father_mobile.' </small>
			                </div>
					<div class="col-lg-3 col-md-3 col-3 pull-left">';
		                if($staff->profile == ''){
							$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
						}else{
							$output.='<img alt="Photo" src="'.base_url().'/profile/'.$staff->profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
						}
	                $output.='</div> 
	                <div class="col-md-12 col-12">
			            	<p><i class="fas fa-website"></i> '.$website.' | <i class="fab fa-telegram-plane"></i> '.$telegram.' | <i class="fas fa-phone"></i> '.$phone.'</p>
			            </div>
			            </div>
	            </div></div>';
			}
		}
		$output.='</div>';
		return $output;
	}
	function fetchStudentIdCard($max_year,$checkStudent,$gradesec,$branch,$gyear){
		$output='<div class="row">';
		foreach($checkStudent as $checkStudents){
			$this->db->order_by('fname,mname,lname','ASC');
			$this->db->where(array('id'=>$checkStudents));
			$this->db->where(array('status'=>'Active'));
			$this->db->where(array('branch'=>$branch));
			$this->db->where(array('isapproved'=>'1'));
			$this->db->where(array('academicyear'=>$max_year));
			$this->db->like('usertype','Student');
			$query=$this->db->get('users');
			
			$queryLinks=$this->db->get('links');
			$linksRow=$queryLinks->row();
			$telegram=$linksRow->telegram;

			foreach ($query->result() as $staff) {
				$query_school=$this->db->get('school');
				$schoolRow=$query_school->row();
				$website=$schoolRow->website;
				$logo=$schoolRow->logo;
				$name=$schoolRow->name;
				$phone=$schoolRow->phone;
				$output.='<script>var qrcode = new QRCode(
				      "qr-code'.$staff->id.'",
				      {
				        text: "Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.', ID: '.$staff->username.', Website: '.$website.'",
				        width:80,
				        height:60,
				        colorDark : "#000000",
				        colorLight : "#FFFFFF",
				        correctLevel : QRCode.CorrectLevel.M
				      }
				    );</script>';
				$output.='<div class="col-md-4 col-12"><div class="StudentViewTextInfo" style="background:url(../img/bg.jpg);background-size:cover;background-position:center; background-repeat:no-repeat;">';
	            $output.='<div class="row">
					<div class="col-lg-3 col-md-3 col-3">
		          		<img src="'.base_url().'/logo/'. $logo.'" alt="logo" style="height: 80px;width: 80px;"> 
		          	</div>
		          	<div class="col-lg-9 col-md-9 col-9">
		          		<h5><b>'.$name.'</b></h5>'.$gyear.' Academic Year <br> STUDENT ID CARD
		          	</div>
		        </div><div class="dropdown-divider"></div>';
				$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12">
						<div class="row">
			                <div class="col-md-12 col-12" style="white-space: nowrap">
			                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
			                </div>
			                <div class="col-md-6 col-6">
			                    <small>ID: '.$staff->username.'</small>
			                </div>
			                <div class="col-md-6 col-6">
			                    <small>GRADE: '.$staff->gradesec.'</small>
			                </div>
			            
		                </div>
		            </div>';
					$output.='<div class="col-md-3 col-3">
			                	<div class="dropdown-divider"></div>
			                	<p id="qr-code'.$staff->id.'"></p>
					        </div>
			                <div class="col-lg-6 col-md-6 col-6">
			                <div class="dropdown-divider"></div>
						      	<small>PARENT PHONE: '.$staff->mobile.' || '.$staff->father_mobile.' </small>
			                </div>
					<div class="col-lg-3 col-md-3 col-3 pull-left">';
		                if($staff->profile == ''){
							$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
						}else{
							$output.='<img alt="Photo" src="'.base_url().'/profile/'.$staff->profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
						}
	                $output.='</div> </div>
	            </div></div>';
			}
		}
		$output.='</div>';
		return $output;
	}
	function fetchStudentCustomIdCardEeaa($max_year,$checkStudent,$gyear){
		$output='<div class="row">';
		foreach($checkStudent as $checkStudents){
			$this->db->order_by('fname,mname,lname','ASC');
			$this->db->where(array('username'=>$checkStudents));
			$this->db->where(array('status'=>'Active'));
			$this->db->where(array('isapproved'=>'1'));
			$this->db->where(array('academicyear'=>$max_year));
			$this->db->like('usertype','Student');
			$query=$this->db->get('users');

			$queryLinks=$this->db->get('links');
			$linksRow=$queryLinks->row();
			$telegram=$linksRow->telegram;

			foreach ($query->result() as $staff) {
				$query_school=$this->db->get('school');
				$schoolRow=$query_school->row();
				$website=$schoolRow->website;
				$logo=$schoolRow->logo;
				$name=$schoolRow->name;
				$phone=$schoolRow->phone;
				$output.='<script>var qrcode = new QRCode(
				      "customqr-codee'.$staff->id.'",
				      {
				        text: "Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.', ID: '.$staff->username.',Transport Service '.$staff->transportservice.' ,Website: '.$website.'",
				        width:80,
				        height:60,
				        colorDark : "#000000",
				        colorLight : "#FFFFFF",
				        correctLevel : QRCode.CorrectLevel.M
				      }
				    );</script>';
				$output.='<div class="col-md-4 col-12"><div class="StudentViewTextInfo" style="background:url(../img/bg.jpg);background-size:cover;background-position:center; background-repeat:no-repeat;">';
	            $output.='<div class="row">
					<div class="col-lg-3 col-md-3 col-3">
		          		<img src="'.base_url().'/logo/'. $logo.'" alt="logo" style="height: 80px;width: 80px;"> 
		          	</div>
		          	<div class="col-lgs-9 col-md-9 col-9">
		          		<h5><b>'.$name.'</b></h5>'.$gyear.' Academic Year <br> STUDENT ID CARD
		          	</div>
		        </div><div class="dropdown-divider"></div>';
				$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12">
						<div class="row">
			                <div class="col-md-12 col-12" style="white-space: nowrap">
			                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
			                </div>
			                <div class="col-md-6 col-6">
			                    <small>ID: '.$staff->username.'</small>
			                </div>
			                <div class="col-md-6 col-6">
			                    <small>GRADE: '.$staff->gradesec.'</small>
			                </div>
			                

		                </div>
		            </div>';
					$output.='<div class="col-lg-3 col-md-3 col-3">
			                	<div class="dropdown-divider"></div>
			                	<p id="customqr-codee'.$staff->id.'"></p>
					        </div>
			                <div class="col-lg-6 col-md-6 col-6">
			                <div class="dropdown-divider"></div><small>PARENT PHONE:';
			                if($staff->mobile!='' || $staff->mobile!='0'){
			                    $output.=' '.$staff->mobile.' || ';
			                }else{
			                    $output.=' - ';
			                }
			                if($staff->father_mobile!='' || $staff->father_mobile!='0'){
			                    $output.=' '.$staff->father_mobile.' ';
			                }else{
			                    $output.=' - ';
			                }
						    $output.='<br>
						      	<h4 style="white-space: nowrap"><u><b>'.$staff->transportservice.'</b></u></h4> </small>
			                </div>

					<div class="col-lg-3 col-md-3 col-3 pull-left">';
		                if($staff->profile == ''){
							$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
						}else{
							$output.='<img alt="Photo" src="'.base_url().'/profile/'.$staff->profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
						}
	                $output.='</div>
	                <div class="col-md-12 col-12">
			            	<p><i class="fas fa-website"></i> '.$website.' | <i class="fab fa-telegram-plane"></i> '.$telegram.' | <i class="fas fa-phone"></i> '.$phone.'</p>
			            </div>
	                </div>
	            
	            </div></div>';
	        }
		}
		$output.='</div>';
		return $output;
	}
	function fetchStudentCustomIdCardSeattle($max_year,$checkStudent,$gyear){
		$output='<div class="row">';
		foreach($checkStudent as $checkStudents){
			$this->db->order_by('fname,mname,lname','ASC');
			$this->db->where(array('username'=>$checkStudents));
			$this->db->where(array('status'=>'Active'));
			$this->db->where(array('isapproved'=>'1'));
			$this->db->where(array('academicyear'=>$max_year));
			$this->db->like('usertype','Student');
			$query=$this->db->get('users');

			$queryLinks=$this->db->get('links');
			$linksRow=$queryLinks->row();
			$telegram=$linksRow->telegram;

			foreach ($query->result() as $staff) {
				$query_school=$this->db->get('school');
				$schoolRow=$query_school->row();
				$website=$schoolRow->website;
				$logo=$schoolRow->logo;
				$name=$schoolRow->name;
				$phone=$schoolRow->phone;
				$output.='<script>var qrcode = new QRCode(
				      "customqr-codee'.$staff->id.'",
				      {
				        text: "Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.', ID: '.$staff->username.',Transport Service '.$staff->transportservice.' ,Website: '.$website.'",
				        width:80,
				        height:60,
				        colorDark : "#000000",
				        colorLight : "#FFFFFF",
				        correctLevel : QRCode.CorrectLevel.M
				      }
				    );</script>';
				$output.='<div class="col-md-4 col-12"><div class="StudentViewTextInfo" style="background:url(../img/bg.jpg);background-size:cover;background-position:center; background-repeat:no-repeat;">';
	            $output.='<div class="row">
					<div class="col-lg-3 col-md-3 col-3">
		          		<img src="'.base_url().'/logo/'. $logo.'" alt="logo" style="height: 80px;width: 80px;"> 
		          	</div>
		          	<div class="col-lgs-9 col-md-9 col-9">
		          		<h5><b>'.$name.'</b></h5>'.$gyear.' Academic Year <br> STUDENT ID CARD<br>
		          		'.$phone.'
		          	</div>
		        </div><div class="dropdown-divider"></div>';
				$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12">
						<div class="row">
			                <div class="col-md-12 col-12" style="white-space: nowrap">
			                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
			                </div>
			                <div class="col-md-6 col-6">
			                    <small>ID: '.$staff->username.'</small>
			                </div>
			                <div class="col-md-6 col-6">
			                    <small>GRADE: '.$staff->gradesec.'</small>
			                </div>
			                

		                </div>
		            </div>';
					$output.='<div class="col-lg-3 col-md-3 col-3">
			                	<div class="dropdown-divider"></div>
			                	<p id="customqr-codee'.$staff->id.'"></p>
					        </div>
			                <div class="col-lg-6 col-md-6 col-6">
			                <div class="dropdown-divider"></div><small>PARENT PHONE:';
			                if($staff->mobile!='' || $staff->mobile!='0'){
			                    $output.=' '.$staff->mobile.' || ';
			                }else{
			                    $output.=' - ';
			                }
			                if($staff->father_mobile!='' || $staff->father_mobile!='0'){
			                    $output.=' '.$staff->father_mobile.' ';
			                }else{
			                    $output.=' - ';
			                }
						    $output.='<br>
						      	<h4 style="white-space: nowrap"><u><b>'.$staff->transportservice.'</b></u></h4> </small>
			                </div>

					<div class="col-lg-3 col-md-3 col-3 pull-left">';
		                if($staff->profile == ''){
							$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
						}else{
							$output.='<img alt="Photo" src="'.base_url().'/profile/'.$staff->profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
						}
	                $output.='</div>
	                <div class="col-md-12 col-12">
			            	<p><i class="fas fa-website"></i> '.$website.' | <i class="fab fa-telegram-plane"></i> '.$telegram.'</p>
			            </div>
	                </div>
	            
	            </div></div>';
	        }
		}
		$output.='</div>';
		return $output;
	}
	function fetchStudentCustomIdCard($max_year,$checkStudent,$gyear){
		$output='<div class="row">';
		foreach($checkStudent as $checkStudents){
			$this->db->order_by('fname,mname,lname','ASC');
			$this->db->where(array('username'=>$checkStudents));
			$this->db->where(array('status'=>'Active'));
			$this->db->where(array('isapproved'=>'1'));
			$this->db->where(array('academicyear'=>$max_year));
			$this->db->like('usertype','Student');
			$query=$this->db->get('users');

			$queryLinks=$this->db->get('links');
			$linksRow=$queryLinks->row();
			$telegram=$linksRow->telegram;

			foreach ($query->result() as $staff) {
				$query_school=$this->db->get('school');
				$schoolRow=$query_school->row();
				$website=$schoolRow->website;
				$logo=$schoolRow->logo;
				$name=$schoolRow->name;
				$phone=$schoolRow->phone;
				$output.='<script>var qrcode = new QRCode(
				      "customqr-codee'.$staff->id.'",
				      {
				        text: "Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.', ID: '.$staff->username.',Transport Service '.$staff->transportservice.' ,Website: '.$website.'",
				        width:80,
				        height:60,
				        colorDark : "#000000",
				        colorLight : "#FFFFFF",
				        correctLevel : QRCode.CorrectLevel.M
				      }
				    );</script>';
				$output.='<div class="col-md-4 col-12"><div class="StudentViewTextInfo" style="background:url(../img/bg.jpg);background-size:cover;background-position:center; background-repeat:no-repeat;">';
	            $output.='<div class="row">
					<div class="col-lg-3 col-md-3 col-3">
		          		<img src="'.base_url().'/logo/'. $logo.'" alt="logo" style="height: 80px;width: 80px;"> 
		          	</div>
		          	<div class="col-lgs-9 col-md-9 col-9">
		          		<h5><b>'.$name.'</b></h5>'.$gyear.' Academic Year <br> STUDENT ID CARD
		          	</div>
		        </div><div class="dropdown-divider"></div>';
				$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12">
						<div class="row">
			                <div class="col-md-12 col-12" style="white-space: nowrap">
			                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
			                </div>
			                <div class="col-md-6 col-6">
			                    <small>ID: '.$staff->username.'</small>
			                </div>
			                <div class="col-md-6 col-6">
			                    <small>GRADE: '.$staff->gradesec.'</small>
			                </div>
			                

		                </div>
		            </div>';
					$output.='<div class="col-lg-3 col-md-3 col-3">
			                	<div class="dropdown-divider"></div>
			                	<p id="customqr-codee'.$staff->id.'"></p>
					        </div>
			                <div class="col-lg-6 col-md-6 col-6">
			                <div class="dropdown-divider"></div><small>PARENT PHONE:';
			                if($staff->mobile!='' || $staff->mobile!='0'){
			                    $output.=' '.$staff->mobile.' || ';
			                }else{
			                    $output.=' - ';
			                }
			                if($staff->father_mobile!='' || $staff->father_mobile!='0'){
			                    $output.=' '.$staff->father_mobile.' ';
			                }else{
			                    $output.=' - ';
			                }
						    $output.='<br>
						      	<h4 style="white-space: nowrap"><u><b>'.$staff->transportservice.'</b></u></h4> </small>
			                </div>

					<div class="col-lg-3 col-md-3 col-3">';
		                if($staff->profile == ''){
							$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 100px;width: 90px;">';
						}else{
							$output.='<img alt="Photo" src="'.base_url().'/profile/'.$staff->profile.'" style="border-radius:5px;height: 100px;width: 90px;">';
						}
	                $output.='</div> </div>
	            
	            </div></div>';
	        }
		}
		$output.='</div>';
		return $output;
	}
	function fetchBackIdCard($pageNumber,$max_year,$gyear){
		$output='<div class="row">';
		$queryLinks=$this->db->get('links');
		$linksRow=$queryLinks->row();
		$telegram=$linksRow->telegram;

		$query_school=$this->db->get('school');
		$schoolRow=$query_school->row();
		$website=$schoolRow->website;
		$logo=$schoolRow->logo;
		$name=$schoolRow->name;
		$phone=$schoolRow->phone;
		for($i=1;$i<=$pageNumber;$i++){			
			$output.='<div class="col-md-4 col-12">
				<div class="StudentViewTextInfo">';
            		$output.='<div class="row">
						<div class="col-lg-3 col-md-3 col-3">
	          				<img src="'.base_url().'/logo/'. $logo.'" alt="logo" style="height: 80px;width: 80px;"> 
	          			</div>
	          			<div class="col-lgs-9 col-md-9 col-9">
	          			<h4><b>'.$name.'</b></h4>'.$max_year.'E.C ('.$gyear.') ACADEMIC YEAR <br> STUDENT ID CARD
	          		</div>
	        	</div>
	        	<div class="dropdown-divider"></div>
				<div class="row">
					<div class="col-lg-9 col-md-9 col-9">
						<div class="row">
			                <div class="col-md-12 col-12" style="white-space: nowrap">
			                <i class="fas fa-phone"></i> '.$phone.' </div>
		                </div>
		            </div>
				</div>
            	<div class="dropdown-divider"></div>
		            <div class="row">
		            	<div class="col-md-12 col-12">
			            	<p><i class="fas fa-website"></i> '.$website.' | <i class="fab fa-telegram-plane"></i> '.$telegram.'</p>
			            </div>
	            	</div>
            	</div>
            </div>';
		}
		$output.='</div>';
		return $output;
	}
	function fetch_staff_idcard($max_year,$branch,$gyear){
		$this->db->order_by('fname,mname,lname','ASC');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		$output='<div class="row">';
		
			
		$queryLinks=$this->db->get('links');
		$linksRow=$queryLinks->row();
		$telegram=$linksRow->telegram;

		foreach ($query->result() as $staff) {
			$query_school=$this->db->get('school');
			$schoolRow=$query_school->row();
			$website=$schoolRow->website;
			$logo=$schoolRow->logo;
			$name=$schoolRow->name;
			$phone=$schoolRow->phone;
			$output.='<script>var qrcode = new QRCode(
			      "qr-codeStaff'.$staff->id.'",
			      {
			        text: "Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.', Position: '.$staff->usertype.', Website: '.$website.'",
			        width:80,
			        height:70,
			        colorDark : "#000000",
			        colorLight : "#FFFFFF",
			        correctLevel : QRCode.CorrectLevel.M
			      }
			    );</script>';
			$output.='<div class="col-md-4 col-6"><div class="StudentViewTextInfo" style="background:url(../img/bg.jpg);background-size:cover;background-position:center; background-repeat:no-repeat;">';
            $output.='<div class="row">
				<div class="col-lg-3 col-md-3 col-3">
	          		<img src="'.base_url().'/logo/'. $logo.'" alt="logo" style="height: 80px;width: 80px;"> 
	          	</div>
	          	<div class="col-lg-9 col-md-9 col-9">
	          		<h5><b>'.$name.'</b></h5>'.$gyear.' Academic Year <br> EMPLOYEE ID CARD <br>
	          		'.$phone.'
	          	</div>
	        </div><div class="dropdown-divider"></div>';
			$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-12">
					<div class="row">
		                <div class="col-md-12 col-12" style="white-space: nowrap">
		                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
		                </div>
		                <div class="col-md-4 col-4">
		                <small>ID CARD:'.substr($name,0,3).'/'.substr($staff->mobile,-4).'</small>
		                </div>
		                <div class="col-md-4 col-4">
		                    <small>Phone: '.$staff->mobile.'</small>
		                </div>
		                <div class="col-md-4 col-4">
		                    <small>Position: '.$staff->usertype.'</small>
		                </div>
		            
	                </div>
	            </div>';
				$output.='
				<div class="col-md-4 col-4">
				<div class="dropdown-divider"></div>';
	                if($staff->profile == ''){
						$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 80px;width: 90px;">';
					}else{
						$output.='<img alt="Photo" src="'.base_url().'/profile/'.$staff->profile.'" style="border-radius:5px;height: 80px;width: 90px;">';
					}
                $output.='</div>
                <div class="col-md-4 col-4">
		            <div class="dropdown-divider"></div>
		               <p id="qr-codeStaff'.$staff->id.'"></p>
				    </div>
				<div class="col-md-4 col-4 pull-right">
				    <div class="dropdown-divider"></div>School Stamp.
				</div>
                <div class="col-md-12 col-12">
                <small class="text-muted"><p class="StudentViewTextInfo"> The holder of this ID card is our Organization employee.</p><p><i class="fas fa-website"></i> '.$website.' | <i class="fab fa-telegram-plane"></i> '.$telegram.'</p> </small>
                </div> </div>
            </div></div>';
		}
		$output.='</div>';
		return $output;

	}
	function fetch_staff_idcardDirector($max_year,$branch,$gyear,$myDivision){
		$this->db->order_by('fname,mname,lname','ASC');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('status2'=>$myDivision));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		$output='<div class="row">';
		
			
		$queryLinks=$this->db->get('links');
		$linksRow=$queryLinks->row();
		$telegram=$linksRow->telegram;

		foreach ($query->result() as $staff) {
			$query_school=$this->db->get('school');
			$schoolRow=$query_school->row();
			$website=$schoolRow->website;
			$logo=$schoolRow->logo;
			$name=$schoolRow->name;
			$phone=$schoolRow->phone;
			$output.='<script>var qrcode = new QRCode(
			      "qr-codeStaff'.$staff->id.'",
			      {
			        text: "Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.', Position: '.$staff->usertype.', Website: '.$website.'",
			        width:80,
			        height:70,
			        colorDark : "#000000",
			        colorLight : "#FFFFFF",
			        correctLevel : QRCode.CorrectLevel.M
			      }
			    );</script>';
			$output.='<div class="col-md-4 col-6"><div class="StudentViewTextInfo" style="background:url(../img/bg.jpg);background-size:cover;background-position:center; background-repeat:no-repeat;">';
            $output.='<div class="row">
				<div class="col-lg-3 col-md-3 col-3">
	          		<img src="'.base_url().'/logo/'. $logo.'" alt="logo" style="height: 80px;width: 80px;"> 
	          	</div>
	          	<div class="col-lg-9 col-md-9 col-9">
	          		<h5><b>'.$name.'</b></h5>'.$gyear.' Academic Year <br> EMPLOYEE ID CARD <br>
	          		'.$phone.'
	          	</div>
	        </div><div class="dropdown-divider"></div>';
			$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-12">
					<div class="row">
		                <div class="col-md-12 col-12" style="white-space: nowrap">
		                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
		                </div>
		                <div class="col-md-4 col-4">
		                <small>ID CARD:'.substr($name,0,3).'/'.substr($staff->mobile,-4).'</small>
		                </div>
		                <div class="col-md-4 col-4">
		                    <small>Phone: '.$staff->mobile.'</small>
		                </div>
		                <div class="col-md-4 col-4">
		                    <small>Position: '.$staff->usertype.'</small>
		                </div>
		            
	                </div>
	            </div>';
				$output.='
				<div class="col-md-4 col-4">
				<div class="dropdown-divider"></div>';
	                if($staff->profile == ''){
						$output.='<img alt="Photo" src="'.base_url().'/profile/defaultProfile.png" style="border-radius:5px;height: 80px;width: 90px;">';
					}else{
						$output.='<img alt="Photo" src="'.base_url().'/profile/'.$staff->profile.'" style="border-radius:5px;height: 80px;width: 90px;">';
					}
                $output.='</div>
                <div class="col-md-4 col-4">
		            <div class="dropdown-divider"></div>
		               <p id="qr-codeStaff'.$staff->id.'"></p>
				    </div>
				<div class="col-md-4 col-4 pull-right">
				    <div class="dropdown-divider"></div>School Stamp.
				</div>
                <div class="col-md-12 col-12">
                <small class="text-muted"><p class="StudentViewTextInfo"> The holder of this ID card is our Organization employee.</p><p><i class="fas fa-website"></i> '.$website.' | <i class="fab fa-telegram-plane"></i> '.$telegram.'</p> </small>
                </div> </div>
            </div></div>';
		}
		$output.='</div>';
		return $output;

	}
	function delete_student($id){
		$this->db->where(array('id'=>$id));
		$this->db->delete('users');
	}
	function fetch_staffss($max_year){
		$this->db->order_by('fname','DESC');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		return $query->result();
	}
	function viewStudentPrint($editedId,$max_year){
		$this->db->where('username',$editedId);
		$this->db->group_by('username');
		$query = $this->db->get('users');
		$output='';

		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$email=$row_name['email'];
		$address=$row_name['address'];
		$phone=$row_name['phone'];
		foreach($query->result() as $lessonP){
			$output.='<div class="section-title">
			<u>Student Information</u></div>
			<div class="row">
				<div class="col-md-3 col-3">
				<img alt="image" src="'.base_url().'/profile/'.$lessonP->profile.'" class="border-circle-4profile">
				</div>
				<div class="col-md-9 col-9">
	            	<div class="row StudentViewTextInfo">
	                  <div class="col-md-3 col-6 b-r">
	                    <strong>Full Name</strong>
	                    <br>
	                    <p class="text-muted">'.$lessonP->fname.' '.$lessonP->mname.' '.$lessonP->lname.'</p>
	                  </div>
	                  <div class="col-md-3 col-6 b-r">
	                    <strong>Gender</strong>
	                    <br>
	                    <p class="text-muted">'.$lessonP->gender.'</p>
	                  </div>
	                  <div class="col-md-3 col-6 b-r">
	                    <strong>Birth Date</strong>
	                    <br>
	                    <p class="text-muted">'.$lessonP->dob.'</p>
	                  </div>
	                  <div class="col-md-3 col-6 b-r">
	                    <strong>Grade</strong>
	                    <br>
	                    <p class="text-muted">'.$lessonP->grade.'</p>
	                  </div>
	                  <div class="col-md-3 col-6 b-r">
	                    <strong>Section</strong>
	                    <br>
	                    <p class="text-muted">'.$lessonP->section.'</p>
	                  </div>
	                  <div class="col-md-3 col-6 b-r">
	                    <strong>Email</strong>
	                    <br>
	                    <p class="text-muted">'.$lessonP->email.'</p>
	                  </div>
	                  <div class="col-md-3 col-6">
	                    <strong>Mother Name</strong>
	                    <br>
	                    <p class="text-muted">'.$lessonP->mother_name.'</p>
	                  </div>
	                  <div class="col-md-3 col-6">
	                    <strong>Mother Mobile</strong>
	                    <br>
	                    <p class="text-muted">'.$lessonP->mobile.'</p>
	                  </div>
	                  <div class="col-md-3 col-6">
	                    <strong>Father Mobile</strong>
	                    <br>
	                    <p class="text-muted">'.$lessonP->father_mobile.'</p>
	                  </div>
	                </div>
                </div>
            </div>
            <p class="m-t-30"><u>Student Status</u>: '.$lessonP->status.' <i class="fas fa-check-circle"> </i> </p>
            <p></p>
            <div class="section-title"><u>Adress</u></div>
            <ul>
            	<li>City: '.$lessonP->city.'</li>
            	<li>Sub city: '.$lessonP->sub_city.'</li>
              	<li>Woreda: '.$lessonP->woreda.'</li>
              	<li>Kebele: '.$lessonP->woreda.'</li>
            </ul>
            <div class="section-title"><u>School Information</u></div>
            <ul>
              <li>Name: '.$school_name.'</li>
              <li>Phone: '.$phone.'</li>
              <li>Email: '.$email.'</li>
              <li>Address: '.$address.'</li>
            </ul> ';
		}
		return $output;
	}
	function fetch_student_toedit($id,$max_year){
		$this->db->where(array('unique_id'=>$id));
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->get('users');
		$output='<div class="dropdown-divider"></div>
		<form method="POST" id="updateStuForm" class="StudentViewTextInfo">
		<div class="row">';
		foreach ($query->result() as $stuValue) {
			$output.='<input type="hidden" name="stuAcademicYear" value="'.$max_year.'">
			<input type="hidden" name="stuStuid" value="'.$stuValue->unique_id.'">
			<input type="hidden" class="form-control" name="stUsername" value="'.$stuValue->username .'">

			<div class="card-header text-center"> <h4>Edit Profile
                <img alt="Profile" src="'.base_url().'/profile/'.$stuValue->profile.'" style="width: 70px" class="user-img-radious-style">
            </h4></div>';
			$output.='
			<div class="form-group col-lg-3 col-6">
	            <label>UserName/ID</label>
	            <input type="text" disabled="disabled" class="form-control" value="'.$stuValue->username .'">
            </div>
		    <div class="form-group col-lg-3 col-6">
		        <label>First Name</label>
		        <input type="text" class="form-control" name="stuFname" value="'.$stuValue->fname.'">
		    </div>
            <div class="form-group col-lg-3 col-6">
                <label>Father Name</label>
                <input type="text" class="form-control" name="stuLname" value="'.$stuValue->mname.'">
            </div>
            <div class="form-group col-lg-3 col-6">
            	<label>G.Father Name</label>
            	<input type="text" class="form-control" name="stuGfname" value="'.$stuValue->lname.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Gender</label>
                <select class="form-control stuGender" required="required" name="stuGender" id="gender">
                    <option>'.$stuValue->gender.'</option>';
                    if($stuValue->gender ===trim('Female') or $stuValue->gender ===trim('female') or $stuValue->gender ===trim('F')){
                    	$output.='<option> Male </option>';
                    }else{
                    	$output.='<option> Female </option>';
                    }
                    $output.='
                </select>
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Mother Mobile</label>
                <input type="text" class="form-control" name="stuMobile" value="'.$stuValue->mobile.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Father Mobile</label>
                <input type="text" class="form-control" name="father_mobile" value="'.$stuValue->father_mobile.'">
            </div>
            <div class="form-group col-lg-2 col-6">
                <label>Grade</label>
                <input type="text" class="form-control" name="stuGrade" value="'.$stuValue->grade.'">
            </div>
            <div class="form-group col-lg-1 col-6">
                <label>Section</label>
                <input type="text" class="form-control" name="stuSection" value="'.$stuValue->section.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Email</label>
                <input type="text" class="form-control" name="stuEmail" 
                value="'.$stuValue->email.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Date of Birth</label>
                <input type="date" class="form-control" name="stuDob" 
                value="'.date('Y-m-d', strtotime($stuValue->dob)).'">
            </div>
            <div class="form-group col-lg-2 col-6">
                <label>Age</label>
                <input type="text" class="form-control" name="stuAge" 
                value="'.$stuValue->age.'">
            </div>
            <div class="form-group col-lg-2 col-6">
                <label>City</label>
                <input type="text" class="form-control" name="stuCity" 
                value="'.$stuValue->city.'">
            </div>
            <div class="form-group col-lg-2 col-6">
                <label>Sub city</label>
                <input type="text" class="form-control" name= "stuSubcity" value="'.$stuValue->sub_city.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Woreda</label>
                <input type="text" class="form-control" name="stuWoreda" value="'.$stuValue->woreda.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Kebele</label>
                <input type="text" class="form-control" name="stuKebele" value="'.$stuValue->kebele.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <div class="form-group">
	                <label for="Profile">Profile Photo</label>
	                <input id="profile" type="file" class="form-control stuProfile" name="stuProfile">
                </div>
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Transport Service</label>
                <select class="form-control transportService" name="transportService" id="transportService">
                    <option>'.$stuValue->transportservice.' </option>';
                $queryFetchService=$this->db->query("select transportservice from users where academicyear ='$max_year' and transportservice!='' group by transportservice order by transportservice ASC ");
                foreach($queryFetchService->result() as $servicePlace){
                	$output.='<option value'.$servicePlace->transportservice.'>'.$servicePlace->transportservice.'</option>';
                }
            $output.='</select></div>
            <div class="form-group col-lg-12 col-12">
                <button class="btn btn-info btn-block" type="submit" name="savechanges"> Save Changes
                </button>
            </div>
            ';
		}
		$output.='</div></form>';
		return $output;
	}
	function update_student_detail($id,$username,$data,$max_year){
		$queryChk=$this->db->query("select * from users where unique_id='$id' and username='$username' ");
		$output='';
		if($queryChk->num_rows()<1){
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i>Username exists, please try other username.
            </div></div>';
		}else{
			$this->db->where(array('unique_id'=>$id));
			$query=$this->db->update('users',$data);
			if($query){
				$output.='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Profile updated successfully.
            	</div></div>';
			}else{
				$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> ooops, please try again.
            	</div></div>';
			}
		}
		return $output;
	}
	function fetch_staff_toedit($id,$max_year,$usertype){
		$this->db->where(array('id'=>$id));
		$query=$this->db->get('users');
		$output='<div class="dropdown-divider"></div>
		<form method="POST" id="updateStaForm" class="formemp" name="formemp">
		<div class="StudentViewTextInfo"><div class="row">';
		foreach ($query->result() as $staffValue) {
			$output.='<input type="hidden" name="editedStaff" value="'.$staffValue->id.'">
			<div class="card-header"> <h4>Edit Profile
                <img alt="Profile" src="'.base_url().'/profile/'.$staffValue->profile.'" style="width:70px" class="user-img-radious-style pull-right">
            </h4></div>';
            $output.='<input type="hidden" class="username" name="username" value="'.$staffValue->username.'"> ';
            $output.='<div class="form-group col-lg-3 col-6">
                <label>UserName/ID</label>
                <input type="text" class="form-control" disabled="disabled" value="'.$staffValue->username.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>First Name</label>
                <input type="text" class="form-control fname" name="fname" value="'.$staffValue->fname.'">               
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Father Name</label>
                <input type="text" class="form-control lname" name="mname" value="'.$staffValue->mname.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>G.Father Name</label>
                <input type="text" class="form-control lname" name="lname" value="'.$staffValue->lname.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Gender</label>
                <select class="form-control selectric" required="required" name="gender" id="gender">
                    <option>'.$staffValue->gender.'</option>';
                    if($staffValue->gender ===trim('Female') or $staffValue->gender ===trim('female') or $staffValue->gender ===trim('F')){
                        $output.='<option>Male </option>';
                    }else{
                        $output.='<option>Female </option>';
                    }
                $output.='</select>
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Mobile</label>
                <input type="text" class="form-control mobile" name="mobile" value="'.$staffValue->mobile.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Quality Allowance</label>
                <input type="number" class="form-control text" onkeyup="calculateTotal()" id="ql" name="quality_allowance" value="'.$staffValue->quality_allowance.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Transport Allowance</label>
                <input type="number" class="form-control text" onkeyup="calculateTotal()" id="tl" name="transport_allowance" value="'.$staffValue->allowance.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Position Allowance</label>
                <input type="number" class="form-control text" onkeyup="calculateTotal()" id="pl" name="position_allowance" value="'.$staffValue->position_allowance.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Home Allowance</label>
                <input type="number" class="form-control text" onkeyup="calculateTotal()" id="hl" name="home_allowance" value="'.$staffValue->home_allowance.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Basic Salary</label>
                <input type="number" class="form-control text" onkeyup="calculateTotal()" name="gsallary" id="gs" value="'.$staffValue->gsallary.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Gross Sallary</label>
                <input type="text" class="form-control text" id="gross_sallary" name="gross_sallary" value="'.$staffValue->gross_sallary.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Taxable Income</label>
                <input type="text" class="form-control text" id="ti"  name="taxable_income" value="'.$staffValue->taxable_income.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Income tax</label>
                <input type="text" class="form-control text" id="income_tax" name="income_tax" value="'.$staffValue->income_tax.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Pension 7%</label>
                <input type="text" class="form-control text" id="pension_7" name="pension_7" value="'.$staffValue->pension_7.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Pension 11%</label>
                <input type="text" class="form-control text" id="pension_11" name="pension_11" value="'.$staffValue->pension_11.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Other</label>
                <input type="text" class="form-control text" id="other" name="other" value="'.$staffValue->other.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Net Payment</label>
                <input type="text" class="form-control text" id="ns" name="netsallary" value="'.$staffValue->netsallary.'">
            </div>
            <div class="form-group col-lg-3 col-6">
                <label>Email</label>
                <input type="text" class="form-control email" name="email"
                value="'.$staffValue->email.'">
            </div>';
            if($usertype == 'superAdmin'){
	            $output.='<div class="form-group col-lg-3 col-6">
	                <label for="password2" class="d-block">Branch</label>
	                <select class="form-control selectric" name="branch" id="branch">
	                    <option>'.$staffValue->branch.'</option>';
						$this->db->order_by('name','ASC');
						$this->db->group_by('name');
						$this->db->where('academicyear',$max_year);
						$queryBranch=$this->db->get('branch');
	                    foreach($queryBranch->result() as $branchs){
	                    	if($staffValue->branch!==$branchs->name){
		                    	$output.='<option>'.$branchs->name.'</option>';
		                    }
	                    } 
	                $output.='</select>
	            </div>';
	        }else{
	        	$output.='<div class="form-group col-lg-3 col-6">
	                <label for="password2" class="d-block">Branch</label>
	                <select class="form-control selectric" name="branch" disabled="disabled" id="branch">
	                    <option>'.$staffValue->branch.'</option>';
						$this->db->order_by('name','ASC');
						$this->db->group_by('name');
						$this->db->where('academicyear',$max_year);
						$queryBranch=$this->db->get('branch');
	                    foreach($queryBranch->result() as $branchs){
	                    	if($staffValue->branch!==$branchs->name){
		                    	$output.='<option>'.$branchs->name.'</option>';
		                    }
	                    } 
	                $output.='</select>
	            </div>';
	        }
            if($usertype == 'superAdmin'){
	            $output.='<div class="form-group col-lg-3 col-6">
	                <label for="password2" class="d-block">User Type</label>
	                <select class="form-control selectric" name="schoolusertype" id="schoolusertype">
	                    <option>'.$staffValue->usertype.'</option>';
						$this->db->order_by('usertype','ASC');
						$this->db->group_by('usertype');
						$queryUsertype=$this->db->get('users');
	                    foreach($queryUsertype->result() as $usertype){
	                    	if($staffValue->usertype!==$usertype->usertype){
		                    	$output.='<option>'.$usertype->usertype.'</option>';
		                    }
	                    } 
	                $output.='</select>
	            </div>';
	        }
            $output.='
            <div class="form-group col-lg-3 col-6">
                <label for="password2" class="d-block">User Division</label>
                <select class="form-control selectric" name="userDivision" id="userDivision">
                    <option>'.$staffValue->status2.'</option>';
					$this->db->order_by('dname','ASC');
					$this->db->group_by('dname');
					$this->db->where('academicyear',$max_year);
					$queryDivision=$this->db->get('schooldivision');
                    foreach($queryDivision->result() as $userDivision){
                    	if($staffValue->status2!==$userDivision->dname){
	                    	$output.='<option>'.$userDivision->dname.'</option>';
	                    }
                    } 
                $output.='</select>
            </div>';
	        
            $output.='<div class="col-lg-3 col-12 card-footer pull-right">
            <label for="password2" class="d-block"></label>
                <button class="btn btn-primary btn-block" type="submit" name="savechanges"> Save Changes </button>
            </div>
            ';
		}
		$output.='</div></div></form>';
		return $output;
	}
	function update_staff_detail($id,$username,$data){
		$queryChk=$this->db->query("select * from users where id!='$id' and username='$username' ");
		$output='';
		if($queryChk->num_rows()>0){
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i>Username exists, please try other username.
            </div></div>';
		}else{
			$this->db->where(array('id'=>$id));
			$query=$this->db->update('users',$data);
			if($query){
				$output.='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Profile updated successfully.
            	</div></div>';
			}else{
				$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> ooops, please try again.
            	</div></div>';
			}
		}
		return $output;
	}
	function deleteStaffs($id){
		foreach($id as $ids){
			$this->db->where('id',$ids);
        	$query=$this->db->delete('users');	
		}
	}
	function active_staffs($id){
		foreach($id as $ids){
			$this->db->where(array('id'=>$ids));
			$this->db->set('status', 'Active');
			$this->db->update('users');
		}
	}
	function inactive_staffs($id){
		foreach($id as $ids){
			$this->db->where(array('id'=>$ids));
			$this->db->set('status', 'Inactive');
			$this->db->update('users');
		}
	}
	function active_student($id){
		$this->db->where(array('id'=>$id));
		$this->db->set('status', 'Active');
		$this->db->update('users');
	}
	function inactive_student($id){
		$this->db->where(array('id'=>$id));
		$this->db->set('status', 'Inactive');
		$this->db->update('users');
	}
	function add_subject($subject,$grade,$max_year,$data){
		$this->db->where(array('Academic_Year'=>$max_year));
		$this->db->where(array('Subj_name'=>$subject));
		$this->db->where(array('Grade'=>$grade));
		$query=$this->db->get('subject');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			$this->db->insert('subject',$data);
		}
	}
	function add_KG_subject($subject,$grade,$max_year,$data){
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('subname'=>$subject));
		$this->db->where(array('subgrade'=>$grade));
		$query=$this->db->get('kgsubject');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			$this->db->insert('kgsubject',$data);
		}
	}
	function add_KG_subject_objective($subject,$subjectName,$grade,$max_year,$max_quarter,$data){
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('subid'=>$subjectName));
		$this->db->where(array('subobjective'=>$subject));
		$this->db->where(array('ograde'=>$grade));
		$this->db->where(array('quarter'=>$max_quarter));
		$query=$this->db->get('kgsubjectobjective');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			$this->db->insert('kgsubjectobjective',$data);
		}
	}
	function fetch_subject_toplace($max_year){
		$this->db->where('Academic_Year',$max_year);
		$this->db->order_by('Subj_name','ASC');
		$this->db->group_by('Subj_name');
		$query=$this->db->get('subject');
		return $query->result();
	}
	function delete_KG_subject($id,$max_year){
		$this->db->where('subname',$id);
		$this->db->where('academicyear',$max_year);
		$this->db->delete('kgsubject');
	}
	function delete_KG_subject_objective($id,$max_year){
		$this->db->where('oid',$id);
		$this->db->where('academicyear',$max_year);
		$this->db->delete('kgsubjectobjective');
	}
	function delete_subject($id,$max_year){
		$this->db->where('Subj_name',$id);
		$this->db->where('Academic_Year',$max_year);
		$this->db->delete('subject');
	}
	function edit_KG_subject_Objective($edtisub,$max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->where('oid',$edtisub);
		$this->db->group_by('subid');
		$query=$this->db->get('kgsubjectobjective');
		$output='<form class="saveSubjectChanges" method="POST">
		<div class="card">
		<a href="#" class="backToSubject"> <i class="fas fa-backward"></i> </a>
		<div class="card-body"> <div class="row"> ';
		foreach ($query->result() as $keyvalue) {
			$sgra=$keyvalue->subid;
			$output .='<div class="col-lg-12">
				<input type="hidden" id="oldSubjName" value="'.$keyvalue->oid.'">
				<label for="Mobile">Subject Name </label>
				<input type="text" id="newSubjName" value="'.$keyvalue->subobjective.'" class="form-control"/>
			</div> ';
			/*$query2=$this->db->query("select * from kgsubjectobjective where academicyear='$max_year' and subid='$sgra'");*/
			/*foreach ($query2->result() as $kvalue) {
				$output .='<div class="col-lg-3" id="deletee'.$kvalue->subid.''.$kvalue->ograde.'">
				<a class="gr'.$kvalue->ograde.'"></a>
				<p class="text-info"> <a href="#" value="'.$kvalue->subid.'" name="'.$kvalue->ograde.'" class="dele">
					<span class="text-danger"><i class="fas fa-trash"></i> </span>
				</a> '.$kvalue->ograde.'</p>';
        		$output .='</div> ';
			}*/
		}
		$output .='</div><div class="row">
		<div class="col-lg-4"></div><div class="col-lg-4"></div>
		<div class="col-lg-4 pull-right"><button class="btn btn-outline-success text-success btn-sm form-control" type="submit">Save Changes</button></div>
		</div></div></div></form>';
		return $output ;
	}
	function edit_KG_subject($edtisub,$max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->where('subname',$edtisub);
		$this->db->group_by('subname');
		$query=$this->db->get('kgsubject');
		$output='<form class="saveSubjectChanges" method="POST"><div class="card">
		<a href="#" class="backToSubject"> <i class="fas fa-backward"></i> </a>
		<div class="card-body"> <div class="row"> ';
		foreach ($query->result() as $keyvalue) {
			$sgra=$keyvalue->subname;
			$output .='<div class="col-lg-6">
				<input type="hidden" id="oldSubjName" value="'.$keyvalue->subname.'">
				<label for="Mobile">Subject Name </label>
				<input type="text" id="newSubjName" value="'.$keyvalue->subname.'" class="form-control"/>
			</div> ';
			$output .='<div class="col-lg-6">
				<label for="Mobile">Percentage</label>
				<input type="text" id="oldSubjPercent" value="'.$keyvalue->percentage.'" class="form-control"/>
			</div> ';
			$query2=$this->db->query("select * from kgsubject where academicyear='$max_year' and subname='$sgra'");
			foreach ($query2->result() as $kvalue) {
				$output .='<div class="col-lg-3" id="deletee'.$kvalue->subname.''.$kvalue->subgrade.'">
				<a class="gr'.$kvalue->subgrade.'"></a>
				<p class="text-info"> <a href="#" value="'.$kvalue->subname.'" name="'.$kvalue->subgrade.'" class="dele">
					<span class="text-danger"><i class="fas fa-trash"></i> </span>
				</a> '.$kvalue->subgrade.'</p>';
				if($kvalue->letter=='#'){
				  	$output .='<div class="pretty p-icon p-smooth">
					  	<input type="checkbox" name="#" class="changeme" 
					  	 id="'.$kvalue->subname.'" checked="checked" value="'.$kvalue->subgrade.'">'.$kvalue->letter.'
					  	  	<div class="state p-success">
		                		<i class="icon fa fa-check"></i>
		                		<label></label>
	              			</div>
	              		</div> ';
	          			$output .='<div class="pretty p-icon p-smooth">
	          			<input class="changeme" id="'.$kvalue->subname.'" name="A" type="checkbox" value="'.$kvalue->subgrade.'">A
	          	  		<div class="state p-success">
	                		<i class="icon fa fa-check"></i>
	                		<label></label>
	              		</div>
          			</div>';
          		}else{
          			$output .='<div class="pretty p-icon p-smooth">
	          			<input class="changeme" name="A" type="checkbox" id="'.$kvalue->subname.'" checked="checked" value="'.$kvalue->subgrade.'">'.$kvalue->letter.'
	          			<div class="state p-success">
	                		<i class="icon fa fa-check"></i>
	                		<label></label>
	              		</div>
          			</div>';
          			$output .='<div class="pretty p-icon p-smooth">
	          			<input class="changeme" id="'.$kvalue->subname.'" name="#" type="checkbox" value="'.$kvalue->subgrade.'">#
	          	  		<div class="state p-success">
	                		<i class="icon fa fa-check"></i>
	                		<label></label>
	              		</div>
          			</div>';
          		}
          		if($kvalue->onreportcard=='1'){
				  	$output .='<div class="pretty p-icon p-smooth">
					  	<input type="checkbox" name="0" class="changeOnRp" 
					  	 id="'.$kvalue->subname.'" checked="checked" value="'.$kvalue->subgrade.'">RC
					  	  	<div class="state p-success">
		                		<i class="icon fa fa-check"></i>
		                		<label></label>
	              			</div>
	              		</div> ';
          		}else{
          			$output .='<div class="pretty p-icon p-smooth">
					  	<input type="checkbox" name="1" class="changeOnRp" 
					  	 id="'.$kvalue->subname.'" value="'.$kvalue->subgrade.'">RC
					  	  	<div class="state p-success">
		                		<i class="icon fa fa-check"></i>
		                		<label></label>
	              			</div>
	              		</div> ';
          		}
        		$output .='</div> ';
			}
		}
		$output .='</div><div class="row">
		<div class="col-lg-4"></div><div class="col-lg-4"></div>
		<div class="col-lg-4 pull-right"><button class="btn btn-outline-success text-success btn-sm form-control" type="submit">Save Changes</button></div>
		</div></div></div></form>';
		return $output ;
	}
	function edit_subject($edtisub,$max_year){
		$this->db->where('Academic_Year',$max_year);
		$this->db->where('Subj_name',$edtisub);
		$this->db->group_by('Subj_name');
		$query=$this->db->get('subject');
		$output='<div class="">
		<a href="#" class="backToSubject"> <i class="fas fa-backward"></i> </a>
		<div class="StudentViewTextInfo"> <div class="row"> ';
		foreach ($query->result() as $keyvalue) {
			$sgra=$keyvalue->Subj_name;
			$sgrade=$keyvalue->Grade;
			$output .='<div class="col-lg-12 col-12 form-group">
				<input type="hidden" id="oldSubjName" value="'.$keyvalue->Subj_name.'">
				<label for="Mobile">Subject Name </label>
				<input type="text" id="newSubjName" value="'.$keyvalue->Subj_name.'" class="form-control"/>
			</div> ';
			$query2=$this->db->query("select * from subject where Academic_Year='$max_year' and Subj_name='$sgra' ");
			foreach ($query2->result() as $kvalue) {
				$output .='<div class="col-lg-3 col-6 StudentViewTextInfo" id="deletee'.$kvalue->Subj_name.''.$kvalue->Grade.'">
				<p> 
				<button value="'.$kvalue->Subj_name.'" name="'.$kvalue->Grade.'" class="btn btn-outline-danger btn-block deleleSubjectGS"><small>Delete Grade
				 '.$kvalue->Grade.' <a class="gr'.$kvalue->Grade.'"></a></small>  </button><br>';
				if($kvalue->letter == '#'){
				  	$output .='<input type="radio" name="'.$kvalue->Grade.'" class="changeme" id="'.$kvalue->Subj_name.'" checked="checked" value="#">'.$kvalue->letter.' ';
	          			$output .='<input class="changeme" id="'.$kvalue->Subj_name.'" name="'.$kvalue->Grade.'" type="radio" value="A">A';
          		}else{
          			$output .='<input class="changeme" name="'.$kvalue->Grade.'" type="radio" id="'.$kvalue->Subj_name.'" checked="checked" value="A">'.$kvalue->letter.' ';
          			$output .='<input class="changeme" id="'.$kvalue->Subj_name.'" name="'.$kvalue->Grade.'" type="radio" value="#">#';
          		}
          		if($kvalue->onreportcard == '1'){
				  	$output .=' <input type="checkbox" name="changeOnRpGS" class="0" 
					  	 id="'.$kvalue->Subj_name.'" checked="checked" value="'.$kvalue->Grade.'">RC ';
          		}else{
          			$output .=' <input type="checkbox" name="changeOnRpGS" class="1" 
					  	 id="'.$kvalue->Subj_name.'" value="'.$kvalue->Grade.'">RC ';
          		}
          		$output.='<select name="percentageGrade" style="width: 100px" class="form-control custom-select" id="percentageGrade">';
          		for($i=100;$i>=1;$i--) { 
          			if($i==$kvalue->Merged_percent ){
          				$output.=' <option selected="selected" class="percentageGrade" name="'.$kvalue->Subj_name.'" id="'.$kvalue->Grade.'" value="'.$i.'">
                    '.$i.'</option>';
          			}else{
          				$output.=' <option class="percentageGrade" name="'.$kvalue->Subj_name.'" id="'.$kvalue->Grade.'" value="'.$i.'"> '.$i.'</option>';
          			}
                }
                $output.='</select> ';
        		$output .='</p></div> <hr>';
			}
		}
		$output .='</div>
		<button class="btn btn-primary btn-block form-control saveSubjectChangesGS type="submit">Save Changes</button>
		</div></div>';
		return $output ;
	}
	function update_KG_subject_objective($oldsubjname,$data,$max_year){
		$this->db->where('oid',$oldsubjname);
		$this->db->where('academicyear',$max_year);
		$query=$this->db->update('kgsubjectobjective',$data);
		if($query){
			return true;
		}else{
			return false;
		}
	}
	function update_KG_subject($oldsubjname,$data,$max_year,$newsubjName){
		$this->db->where('subname',$oldsubjname);
		$this->db->where('academicyear',$max_year);
		$query=$this->db->update('kgsubject',$data);
		if($query){
			$this->db->where('subid',$oldsubjname);
		    $this->db->where('academicyear',$max_year);
		    $this->db->set('subid',$newsubjName);
		    $query=$this->db->update('kgsubjectobjective');
			return true;
		}else{
			return false;
		}
	}
	function update_subject($oldsubjname,$data,$max_year,$newsubjName){
		$this->db->where('Subj_name',$oldsubjname);
		$this->db->where('Academic_Year',$max_year);
		$query=$this->db->update('subject',$data);
		if($query){
			$queryQuarter=$this->db->query("select term from quarter where Academic_year ='$max_year' ");
			if($queryQuarter->num_rows()>0){
				foreach($queryQuarter->result() as $quarterName){
					$quarter=$quarterName->term;
					$queryGrade=$this->db->query("select Grade from subject where Subj_name='$newsubjName' and Academic_Year='$max_year' ");
					if($queryGrade->num_rows()>0){
						foreach($queryGrade->result() as $gradeName){
							$grade=$gradeName->Grade;
							$queryBranch=$this->db->query("select name from branch where academicyear='$max_year'  group by name");
							if($queryBranch->num_rows()>0){
								foreach($queryBranch->result() as $branchName){
									$branch=$branchName->name;
									$queryGradesec=$this->db->query("select gradesec from users where academicyear='$max_year' and grade='$grade' and usertype='Student' and branch='$branch' group by gradesec ");
									if($queryGradesec->num_rows()>0){
										foreach($queryGradesec->result() as $GradesecName){
											$gradesec=$GradesecName->gradesec;
											$queryCheck = $this->db->query("SHOW TABLES LIKE 'mark".$branch.$gradesec.$quarter.$max_year."' ");
											if ($queryCheck->num_rows()>0)
											{
												$this->db->where('subname',$oldsubjname);
												$this->db->where('mgrade',$gradesec);
												/*$this->db->where('quarter',$quarter);*/
												$this->db->where('academicyear',$max_year);
												$this->db->set('subname',$newsubjName);
												$queryUpdate=$this->db->update('mark'.$branch.$gradesec.$quarter.$max_year);
											}
										}
									}
								}
							}
						}
					}
				}
			}
			return true;
		}else{
			return false;
		}
	}
	function feedschoolcurriclum($term,$data){
		$this->db->where(array('crname'=>$term));
		$query=$this->db->get('schoolcurriclum');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			$this->db->insert('schoolcurriclum',$data);
		}
	}
	function fetchschoolcurriclum($max_year){
		$output='';
			$output.='<div class="StudentViewTextInfo">
			<h5 class="card-header"> Your School Annual Curriclum by ';
			$arrayterm=array('Quarter','Term','Semester');
			foreach($arrayterm as $termName){
				$queryCheck=$this->db->query("select * from schoolcurriclum where crname='$termName' ");
				if($queryCheck->num_rows()>0){
					$output.='<div class="pretty p-switch">
	                    <input type="checkbox" checked="checked" name="schoolAnnualCurriclum" value="'.$termName.'" />
	                    <div class="state">
	                        <label>'.$termName.'</label>
	                    </div>
	                </div>';
				}else{
					$output.='<div class="pretty p-switch">
	                  <input type="checkbox" name="schoolAnnualCurriclum" value="'.$termName.'" />
	                  <div class="state">
	                    <label>'.$termName.'</label>
	                  </div>
	                </div>';
	            }
			}
			$output.='</h5></div>';
		return $output;
	}
	function loadSchoolCurriclum4Use($max_year){
		$queryCheck=$this->db->query("select * from schoolcurriclum group by crname order by crname ASC ");
		if($queryCheck->num_rows()>0){
		$output ='<option> </option>';
			foreach ($queryCheck->result() as $row) { 
				$output .='<option value="'.$row->crname.'">'.$row->crname.'</option>';
			}
			return $output;
		}
	}
	function add_term($term,$ac,$grade){
		$this->db->where(array('term'=>$term));
		$this->db->where(array('termgrade'=>$grade));
		$this->db->where(array('Academic_year'=>$ac));
		$query=$this->db->get('quarter');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}
	function add_year($academicyear){
		$this->db->where(array('year_name'=>$academicyear));
		$query=$this->db->get('academicyear');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}
	function fetch_year(){
		$this->db->order_by('year_name','DESC');
		$query=$this->db->get('academicyear');
		return $query->result();
	}
	function delete_year($id){
		$this->db->where('id',$id);
		$this->db->delete('academicyear');
	}
	function fetch_grade($max_year){
		$this->db->where('usertype','Student');
		$this->db->where('grade !=','0');
		$this->db->where('grade !=','');
		$this->db->where('academicyear',$max_year);
		$this->db->order_by('grade','ASC');
		$this->db->group_by('grade');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetch_grade4NonsuperAdmin($max_year,$branch){
		$this->db->where('usertype','Student');
		$this->db->where('grade !=','0');
		$this->db->where('grade !=','');
		$this->db->where('academicyear',$max_year);
		$this->db->where('branch',$branch);
		$this->db->order_by('grade','ASC');
		$this->db->group_by('grade');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetchDivForGradeGroup($max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->order_by('dname','ASC');
		$this->db->group_by('dname');
		$query=$this->db->get('schooldivision');
		return $query->result();
	}
	function fetchKgGrade($max_year){
		$this->db->where('grade !=','0');
		$this->db->where('grade !=','');
		$this->db->where('grade =','KG1');
		$this->db->where('academicyear',$max_year);
		$this->db->or_where('grade =','KG2');
		$this->db->where('grade !=','0');
		$this->db->where('grade !=','');
		$this->db->where('academicyear',$max_year);
		$this->db->or_where('grade =','KG3');
		$this->db->where('grade !=','0');
		$this->db->where('grade !=','');
		$this->db->where('academicyear',$max_year);
		$this->db->order_by('grade','ASC');
		$this->db->group_by('grade');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetchAllSubject4Forged($max_year){
		$query=$this->db->query("select * from subject where Academic_Year='$max_year' and onreportcard='0' group by Subj_name order by Subj_name ASC");
		return $query->result();
	}
	function fetchOnlyKgSubjects($max_year){
		$query=$this->db->query("select * from subject where Academic_Year='$max_year'  and Grade='KG1' or Academic_Year='$max_year' and  Grade='KG2' or Academic_Year='$max_year' and Grade='KG3' group by Subj_name order by Subj_name ASC");
		return $query->result();
	}
	function fetch_all_subject($max_year){
		$query=$this->db->query("select * from subject where Academic_Year='$max_year' group by Subj_name order by Subj_name ASC");
		return $query->result();
	}
	function fetch_KG_subject($max_year){
		$query=$this->db->query("select * from kgsubject where academicyear='$max_year' group by subname order by subname ASC");
		return $query->result();
	}
	function fetch_subject_grades($max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(Grade) as gradess from subject where Academic_Year ='$max_year'  GROUP BY Subj_name ORDER BY Subj_name ASC");
    	return $query->result();
  	}
  	function fetchAllKGSubjetsObjectives($max_year,$max_quarter){
  		$querySubject=$this->db->query("SELECT * from kgsubjectobjective where academicyear ='$max_year' and quarter='$max_quarter' GROUP BY oid ORDER BY oid DESC");
  		$output='';
  		if($querySubject->num_rows()>0){
  			$output.='<div class="card" id="subjecttshere">
            <div class="card-body">
              <div class="row">
                <div class="table-responsive">
                  <table class="table table-stripped table-hover" style="width:100%;">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Subject Name</th>
                        <th>Grade</th>
                        <th>Objective</th>
                        <th>Linked Subject</th>
                        <th>Date Created</th>
                      </tr>
                    </thead>
                    <tbody>';
                    $no=1; 
                    foreach($querySubject->result() as $post){
                      $id=$post->oid;
                      $output.='<tr class="delete_mem'.$id.'">
                        <td>'.$no.'.</td>
                        <td>'.$post->subid.'</td>
                        <td>'.$post->ograde.'</td><td>'.$post->subobjective.'</td><td>'.$post->linksubject.'</td>'; 
                        $output.='
                        <td>'.$post->datecreated.'
                          <div class="table-links">
                            <a href="#" class="editSubject" value="'.$post->oid.'">Edit
                            </a>
                            <div class="bullet"></div>
                            <a href="#" class="deletesubject text-danger" id="'.$post->oid.'">Delete</a>
                          </div>
                        </td>
                       </tr>';
                       $no++; 
                    }
                    $output.='</tbody>
                  </table>
                </div>
              </div>
            </div>
           </div>';
	  	}else{
	  		$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No subject objective found.
            </div></div>';
	  	}
	  	return $output;
  	}
  	function fetchAllKGSubjets($max_year){
  		$querySubject=$this->db->query("SELECT *, GROUP_CONCAT(subgrade) as gradess from kgsubject where academicyear ='$max_year'  GROUP BY subname ORDER BY subname ASC");
  		$output='';
  		if($querySubject->num_rows()>0){
  			$allSubject=$querySubject->num_rows();
  			$output.='<div class="card" id="subjecttshere">
            <div class="card-body">
              <div class="row">
                <div class="table-responsive">
                  <table class="table table-stripped table-hover" style="width:100%;">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Subject Name</th>
                        <th>Grade</th>
                        <th>Subject Order</th>
                        <th>Date Created</th>
                      </tr>
                    </thead>
                    <tbody>';
                    $no=1; 
                    foreach($querySubject->result() as $post){
                      $id=$post->subname;
                      $output.='<tr class="delete_mem'.$id.'">
                        <td>'.$no.'.</td>
                        <td>'.$post->subname.'</td>';
                        $output.='<td>'.$post->gradess.'</td>'; 
 						$output.='<td><select class="form-control kgsubOrderJ" required="required" name="kgsubOrder" id="kgsubOrderJ"><option class="kgsubOrder" id="'.$post->subname.'" value="'.$post->suborder.'">'.$post->suborder.'</option>';
 						for ($i=1; $i <=$allSubject ; $i++) { 
 							$output.='<option class="kgsubOrder" id="'.$post->subname.'" value="'.$i.'">'.$i.'</option>';
 						}
 						$output.='</select></td>';
                        $output.='
                        <td>'.$post->datecreated.'
                          <div class="table-links">
                            <a href="#" class="editSubject" value="'.$post->subname.'">Edit
                            </a>
                            <div class="bullet"></div>
                            <a href="#" class="deletesubject text-danger" id="'.$post->subname.'">Delete</a>
                          </div>
                        </td>
                       </tr>';
                       $no++; 
                    }
                    $output.='</tbody>
                  </table>
                </div>
              </div>
            </div>
           </div>';
	  	}else{
	  		$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No subject found.
            </div></div>';
	  	}
	  	return $output;
  	}
  	function fetchAllSubjets($max_year){
  		$querySubject=$this->db->query("SELECT *, GROUP_CONCAT(Grade) as gradess from subject where Academic_Year ='$max_year'  GROUP BY Subj_name ORDER BY Subj_name ASC");
  		$output='';
  		if($querySubject->num_rows()>0){
  			$output.='
                <div class="table-responsive">
                  <table class="table table-striped table-hover" style="width:100%;">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Subject Name</th>
                        <th>Grade</th>
                        <th>Date Created</th>
                      </tr>
                    </thead>
                    <tbody>';
                    $no=1; 
                    foreach($querySubject->result() as $post){
                      $id=$post->Subj_name;
                      $output.='<tr class="delete_mem'.$id.'">
                        <td>'.$no.'.</td>
                        <td>'.$post->Subj_name.'
                        <div class="table-links">
                            <a href="#" class="editSubject" value="'.$post->Subj_name.'">Edit
                            </a>
                            <div class="bullet"></div>
                            <a href="#" class="deletesubject text-danger" id="'.$post->Subj_name.'">Delete</a>
                          </div>
                        </td>
                        <td>'.$post->gradess.'</td>'; 
                        $output.='
                        <td>'.$post->date_created.' </td>
                       </tr>';
                       $no++; 
                    }
                    $output.='</tbody>
                  </table>
           </div>';
	  	}else{
	  		$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No subject found.
            </div></div>';
	  	}
	  	return $output;
  	}
  	function fetchAchiever(){
  		$query=$this->db->query("SELECT *, GROUP_CONCAT(achievergrade) as gradess from achievername GROUP BY minvalue,maxivalue ORDER BY id ASC");

  		$output='';
  		if($query->num_rows()>0){
  			$output.='
                <div class="table-responsive">
                  <table class="table table-striped table-hover" style="width:100%;">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Grade</th>
                        <th>Achiever Name</th>
                        <th>Min. Value</th>
                        <th>Max. Value</th>
                        <th>Remark</th>
                        <th>Date Created</th>
                      </tr>
                    </thead>
                    <tbody>';
                    $no=1; 
                    foreach($query->result() as $post){
                      $id=$post->achievername;
                      $output.='<tr class="delete_mem'.$id.'">
                        <td>'.$no.'.</td>
                        <td>'.$post->gradess.'.</td>
                        <td>'.$post->achievername.'
                        <div class="table-links">
                            <a href="#" class="deleteachievername text-danger" value="'.$post->minvalue.'" name="'.$post->maxivalue.'" id="'.$post->achievername.'">Delete</a>
                          </div>
                        </td>
                        <td>'.$post->minvalue.'</td> <td>'.$post->maxivalue.'</td> <td>'.$post->remarkname.'</td>'; 
                        $output.='
                        <td>'.$post->datecreated.' </td>
                       </tr>';
                       $no++; 
                    }
                    $output.='</tbody>
                  </table>
           </div>';
	  	}else{
	  		$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div>';
	  	}
	  	return $output;
  	}
  	function fetch_merged_subject_grades($max_year){
		$query=$this->db->query("SELECT *  from subject where Academic_Year ='$max_year' and Merged_name!='' GROUP BY Merged_name,Grade ORDER BY Merged_name ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive"> 
            <table class="tabler table-borderedr" style="width:100%;">
            <thead><tr> 
                <th>Merge Name</th>
                <th>Grade</th>
                <th>Merged Subject List</th>
                <th>Percentage</th> </tr>
            </thead>';
			foreach ($query->result() as $posts) {
				$id=$posts->Merged_name;
            	$gradee=$posts->Grade;
            	$count_span=$this->db->query(" select * from subject where Merged_name ='$id' and Grade ='$gradee' and Academic_Year ='$max_year' ");
            	$al_joss=$count_span->num_rows();
            	$output.='<tr class="removeit'.$posts->Grade.''.$posts->Merged_name.'">
                <td rowspan ="'.$al_joss.'"><button class="btn btn-primary removemerged" value="'.$posts->Grade.'" name="'.$posts->Merged_name.'" id="heresave'.$posts->Grade.'">'.$posts->Merged_name.'</button> </td> 
                <td rowspan ="'.$al_joss.'">'.$posts->Grade.'</td>';
                $query231=$this->db->query("select * from subject where Merged_name ='$id' and Grade ='$gradee' and Academic_Year ='$max_year'");
                $no=1;
                foreach ($query231->result() as $keyvalue)
                { 
                    $output.='<td>'.$keyvalue->Subj_name.'</td>  
                    <td>'.$keyvalue->Merged_percent.'</td>
                      </tr>';
                } 
            	$no++; 
			}
			$output.='</table></div>';
		}else{
    		$output.='<div class="alert alert-warning    alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    No merged subject found.
                </div>';
		}
		return $output;
  	}
	function fetch_month(){
		$query=$this->db->get('month');
		return $query->result();
	}
	function fetch_term($max_year){
		$this->db->where('Academic_year',$max_year);
		$this->db->order_by('term','ASC');
		$query=$this->db->get('quarter');
		return $query->result();
	}
	function fetch_schooldivision($max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->order_by('dname','ASC');
		$query=$this->db->get('schooldivision');
		return $query->result();
	}
	function fetchQuarterOfYear($max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(termgrade) as gradess from quarter where Academic_year ='$max_year'  GROUP BY term ORDER BY term ASC");

		/*$this->db->where('Academic_year',$max_year);
		$this->db->order_by('term','ASC');
		$query=$this->db->get('quarter');*/
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
            <table class="table table-borderedr">
                <tr>
                  <th>Name</th>
                  <th>Grade</th>
                  <th>Starting date</th>
                  <th>Ending date</th>
                  <th>Created At</th>
                  <th>Academic Year</th>
                  <th>Action</th>
            </tr>';
			foreach ($query->result() as $qValue) {
				$id=$qValue->id;
				$output.='                          
                <tr class="delete_mem'.$qValue->term.'">
                  <td>'.$qValue->term.'</td>
                  <td>'.$qValue->gradess.'</td>
                  <td>'.$qValue->startdate.'</td>
                  <td>'.$qValue->endate.'</td>
                  <td>'.$qValue->date_created.'</td>
                  <td>'.$qValue->Academic_year.'</td>
                  <td><button type="submit" name="editerm" id="'.$qValue->term.'"  class="btn btn-success editerm"><i class="fas fa-pen-alt"></i> </button>

                  <button type="submit" name="deleteterm" id="'.$qValue->term.'"  class="btn btn-danger deleteterm"><i class="fas fa-trash-alt"></i> </button> </td>
                </tr>';
			}
			$output.='</table> </div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
                No quarter found.
            </div>';
		}
		return $output;
	}
	function getQuarterToEdit($termid){
		$this->db->where('term',$termid);
		$this->db->group_by('term');
        $query=$this->db->get('quarter');
        return $query->result();
	}
	function updateQuarter($data,$termID){
		$this->db->where('term',$termID);
		$query=$this->db->update('quarter',$data);
		if($query){
			return true;
		}else{
			return false;
		}
	}
	function fetch_term_4teacheer($max_year){
		$this->db->where('Academic_year',$max_year);
		$this->db->select_max('term');
		$this->db->order_by('term','ASC');
		$query=$this->db->get('quarter');
		return $query->result();
	}
	function delete_term($id){
		$this->db->where('term',$id);
		$this->db->delete('quarter');
	}
	function fetch_usertype(){
		$this->db->order_by('usertype','ASC');
		$this->db->group_by('usertype');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetch_usertype_users($usertype){
		$this->db->order_by('fname','ASC');
		$this->db->where('usertype',$usertype);
		$query=$this->db->get('users');
		$output ='<input type="checkbox" id="selectall" onClick="selectAll()"> 
		Select All</br>';
			foreach ($query->result() as $row) { 
				$output .='<input type="checkbox" name="username[ ]" id="username"
				value="'.$row->username.'">
				'.$row->fname.' '.$row->mname.'<br>';
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
	function fetchThisGradeStudents($gradeselect){
		$this->db->order_by('fname','ASC');
		$this->db->where('grade',$gradeselect);
		$query=$this->db->get('users');
		$output ='<input type="checkbox" id="selectall" onClick="selectAll()"> 
		Select All</br>';
			foreach ($query->result() as $row) { 
				$output .='<input type="checkbox" id="username" name="username[ ]" value="'.$row->username.'"> '.$row->fname.' '.$row->mname.'<br>';
			}
			return $output;
	}
	function fetch_assigned_grade($max_year,$user){
		$this->db->where(array('staff'=>$user));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->group_by('grade');
		$query=$this->db->get('staffplacement');
		$output ='<option> </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->grade.'">'.$row->grade.'</option>';
			}
			return $output;
	}
	function fetch_usertype_users_grade($usertype){
		$this->db->order_by('gradesec','ASC');
		$this->db->where('usertype',$usertype);
		$this->db->group_by('gradesec');
		$query=$this->db->get('users');
		$output ='<option> </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->gradesec.'">'.$row->gradesec.'</option>';
			}
			return $output;
	}
	function fetch_this_grade_students($gradeselect){
		$this->db->order_by('fname','ASC');
		$this->db->where('gradesec',$gradeselect);
		$query=$this->db->get('users');
		$output ='<input type="checkbox" id="selectall" onClick="selectAll()"> 
		Select All</br>';
			foreach ($query->result() as $row) { 
				$output .='<input type="checkbox" id="username" name="username[ ]" value="'.$row->username.'"> '.$row->fname.' '.$row->mname.'<br>';
			}
			return $output;
	}
	function compose_message($usertype,$touser,$user,$grade,$subject,$message,$datetoday){
		$data=array(
			'sender'=>$user,
			'group_staffs'=>$usertype,
			'receiver'=>$grade,
			'grade'=>$touser,
			'subject'=>$subject,
			'message'=>$message,
			'date_sent'=>$datetoday
		);
		$this->db->insert('message',$data);
	}
	function add_evaluation($grade,$evname,$ac,$max_quarter){
		$this->db->where(array('grade'=>$grade));
		$this->db->where(array('evname'=>$evname));
		$this->db->where(array('academicyear'=>$ac));
		$this->db->where(array('quarter'=>$max_quarter));
		$query=$this->db->get('evaluation');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}
	function summerStudent($username,$year){
		$this->db->where('academicyear',$year);
		$this->db->where('username',$username);
        $query = $this->db->get('summerstudent');
        if($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}
	function filterGradeFromBranch($branch,$max_year){
		$this->db->where('summerstudent.branch',$branch);
		$this->db->where(array('summerstudent.academicyear'=>$max_year));
		$this->db->order_by('summerstudent.gradesec','ASC');
		$this->db->group_by('summerstudent.gradesec');
		$query=$this->db->get('summerstudent');
		$output ='<option></option>';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->gradesec.'">'.$row->gradesec.'</option>';
		}
	    return $output;
	}
	function exportSummerStudentMarkFormat($gradesec,$max_year,$branch1){
		$this->db->where('summerevaluation.academicyear',$max_year);
		$this->db->where('summerstudent.gradesec',$gradesec);
		$this->db->where('summerstudent.branch',$branch1);
		$this->db->where('summerstudent.academicyear',$max_year);
		$this->db->order_by('summerstudent.fname','ASC');
		$this->db->order_by('summerstudent.mname','ASC');
		$this->db->order_by('summerstudent.lname','ASC');
		$this->db->group_by('summerstudent.id');
		$this->db->select('*');
		$this->db->from('summerevaluation');
		$this->db->join('summerstudent', 
            'summerstudent.grade = summerevaluation.grade');
		$query = $this->db->get();
        return $query->result();
	}
	function exportThisGradeSummerEvname($gradesec,$max_year,$branch1){
		$this->db->where('summerevaluation.academicyear',$max_year);
		$this->db->where('summerstudent.gradesec',$gradesec);
		$this->db->where('summerstudent.branch',$branch1);
		$this->db->where('summerstudent.academicyear',$max_year);
		$this->db->order_by('summerevaluation.eid','ASC');
		$this->db->group_by('summerevaluation.evname');
		$this->db->select('*');
		$this->db->from('summerevaluation');
		$this->db->join('summerstudent', 
            'summerstudent.grade = summerevaluation.grade');
		$query = $this->db->get();
        return $query->result();
	}
	function getAllSummerSubject($gradesec,$max_year){
		$query=$this->db->query("select count(su.Subj_Id) as all_sub,su.Subj_Id,su.Subj_name,su.Grade from summersubject as su cross join summerstudent as u where u.grade=su.Grade and u.gradesec='$gradesec' and Academic_Year='$max_year' group by su.Subj_Id order by su.Subj_name ASC");
		return $query->result();
	}
	function fecthThisStudent($gs_branches,$gs_gradesec,$max_year){
		$this->db->order_by('fname','ASC');
		$this->db->order_by('mname','ASC');
		$this->db->order_by('lname','ASC');
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('gradesec'=>$gs_gradesec));
		$this->db->where(array('branch'=>$gs_branches));
		$this->db->like('usertype','Student');
		$query=$this->db->get('summerstudent');
		$output ='';
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
			<td>'.$value->username.'
            <div class="table-links">
             <a href="#" class="deleteSummerStudent text-danger" id="'.$value->id.'">Delete</a>
             <div class="bullet"></div>
             <a href="#" class="editSummerStudent text-success" id="'.$value->unique_id.'">Edit</a>
            </div>
            </td>
            <td>'.$value->fname .' '.$value->mname.' '.$value->lname.' </td> 
            <td>'.$value->gradesec.'</td>
            <td>'.$value->gender.'</td>
            <td>'.$value->branch.' </td>  
            </tr>';
            $no++;
		}
		return $output;
	}
	function deleteSummerStudent($id){
		$this->db->where(array('id'=>$id));
		$this->db->delete('summerstudent');
	}
	function fetchSummerStudentToEdit($id,$max_year){
		$this->db->where(array('unique_id'=>$id));
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->get('summerstudent');
		$output='<div class="dropdown-divider"></div>
		<form method="POST" id="updateSummerStuForm">
		<div class="card-body"><div class="row">';
		foreach ($query->result() as $stuValue) {
			$output.='<input type="hidden" name="stuStuidSummer" value="'.$stuValue->unique_id.'">
			<input type="hidden" class="form-control" name="stUsernameSummer" value="'.$stuValue->username .'">

			<div class="card-header"> <h4>Edit Profile
                <img alt="Profile" src="'.base_url().'/profile/'.$stuValue->profile.'" style="width: 70px" class="user-img-radious-style pull-right">
            </h4></div>';
			$output.='
			<div class="form-group col-lg-3">
	            <label>UserName/ID</label>
	            <input type="text" disabled="disabled" class="form-control" value="'.$stuValue->username .'">
            </div>
		    <div class="form-group col-lg-3">
		        <label>First Name</label>
		        <input type="text" class="form-control" name="stuFnameSummer" value="'.$stuValue->fname.'">
		    </div>
            <div class="form-group col-lg-3">
                <label>Father Name</label>
                <input type="text" class="form-control" name="stuLnameSummer" value="'.$stuValue->mname.'">
            </div>
            <div class="form-group col-lg-3">
            	<label>G.Father Name</label>
            	<input type="text" class="form-control" name="stuGfnameSummer" value="'.$stuValue->lname.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Gender</label>
                <select class="form-control stuGender" required="required" name="stuGenderSummer" id="gender">
                    <option>'.$stuValue->gender.'</option>';
                    if($stuValue->gender ===trim('Female') or $stuValue->gender ===trim('female') or $stuValue->gender ===trim('F')){
                    	$output.='<option> Male </option>';
                    }else{
                    	$output.='<option> Female </option>';
                    }
                    $output.='
                </select>
            </div>
            <div class="form-group col-lg-3">
                <label>Mother Mobile</label>
                <input type="text" class="form-control" name="stuMobileSummer" value="'.$stuValue->mobile.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Father Mobile</label>
                <input type="text" class="form-control" name="father_mobileSummer" value="'.$stuValue->father_mobile.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Grade</label>
                <input type="text" class="form-control" name="stuGradeSummer" value="'.$stuValue->grade.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Grade</label>
                <input type="text" class="form-control" name="stuSectionSummer" value="'.$stuValue->section.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Email</label>
                <input type="text" class="form-control" name="stuEmailSummer" 
                value="'.$stuValue->email.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Date of Birth</label>
                <input type="date" class="form-control" name="stuDobSummer" 
                value="'.$stuValue->dob.'">
            </div>
            <div class="form-group col-lg-3">
                <label>City</label>
                <input type="text" class="form-control" name="stuCitySummer" 
                value="'.$stuValue->city.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Sub city</label>
                <input type="text" class="form-control" name= "stuSubcitySummer" value="'.$stuValue->sub_city.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Woreda</label>
                <input type="text" class="form-control" name="stuWoredaSummer" value="'.$stuValue->woreda.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Kebele</label>
                <input type="text" class="form-control" name="stuKebeleSummer" value="'.$stuValue->kebele.'">
            </div>
            <div class="form-group col-lg-3">
                <div class="form-group">
	                <label for="Profile">Profile Photo</label>
	                <input id="profile" type="file" class="form-control stuProfile" name="stuProfileSummer">
                </div>
            </div>
            <div class="form-group col-lg-3 text-right">
                <button class="btn btn-success" type="submit" name="savechangesSummer"> Save Changes
                </button>
            </div>
            ';
		}
		$output.='</div></div></form>';
		return $output;
	}
	function fetchSummerGrade($max_year){
		$this->db->where('grade !=','0');
		$this->db->where('grade !=','');
		$this->db->where('academicyear',$max_year);
		$this->db->order_by('grade','ASC');
		$this->db->group_by('grade');
		$query=$this->db->get('summerstudent');
		return $query->result();
	}
	function fetchSummerSubjets($max_year){
  		$querySubject=$this->db->query("SELECT *, GROUP_CONCAT(Grade) as gradess from summersubject where Academic_Year ='$max_year'  GROUP BY Subj_name ORDER BY Subj_name ASC");
  		$output='';
  		if($querySubject->num_rows()>0){
  			$output.='<div class="card" id="summersubjecttshere">
            <div class="card-body">
              <div class="row">
                <div class="table-responsive">
                  <table class="table table-stripped table-hover" style="width:100%;">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Subject Name</th>
                        <th>Grade</th>
                        <th>Date Created</th>
                      </tr>
                    </thead>
                    <tbody>';
                    $no=1; 
                    foreach($querySubject->result() as $post){
                      $id=$post->Subj_name;
                      $output.='<tr class="delete_mem'.$id.'">
                        <td>'.$no.'.</td>
                        <td>'.$post->Subj_name.'</td>
                        <td>'.$post->gradess.'</td>'; 
                        $output.='
                        <td>'.$post->date_created.'
                          <div class="table-links">
                            <a href="#" class="deleteSummersubject text-danger" id="'.$post->Subj_name.'">Delete</a>
                          </div>
                        </td>
                       </tr>';
                       $no++; 
                    }
                    $output.='</tbody>
                  </table>
                </div>
              </div>
            </div>
           </div>';
	  	}else{
	  		$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No subject found.
            </div></div>';
	  	}
	  	return $output;
  	}
  	function deleteSummerSubject($id,$max_year){
		$this->db->where('Subj_name',$id);
		$this->db->where('Academic_Year',$max_year);
		$this->db->delete('summersubject');
	}
  	function addSummerSubject($subject,$grade,$max_year,$data){
		$this->db->where(array('Academic_Year'=>$max_year));
		$this->db->where(array('Subj_name'=>$subject));
		$this->db->where(array('Grade'=>$grade));
		$query=$this->db->get('summersubject');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			$this->db->insert('summersubject',$data);
		}
	}
	function fetchSummerEvaluations($max_year){
		$query=$this->db->query("select *, GROUP_CONCAT(grade) as evalname from summerevaluation where academicyear='$max_year' group by percent,evname order by grade DESC ");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive"> 
	            <table class="table table-striped table-hover" style="width:100%;">
	            <thead><tr> 
	                <th>No.</th>
	                <th>Evaluation Name</th>
	                <th>Grade</th>
	                <th>Percentage</th>
	                <th>Academic Year</th>
	                <th>Date Created</th> </tr>
	            </thead>';
	        $no=1;
			foreach ($query->result() as $fetch_evaluations) {
				$output.='
				<tr class="delete_mem'.$fetch_evaluations->percent.''.$fetch_evaluations->evname.'">
	                <td>'.$no.'</td>
	                <td>'.$fetch_evaluations->evname.'
	                    <div class="table-links">
	                        <a href="#" name="'.$fetch_evaluations->evname.'"  class="deleteSummerEvaluation text-danger" id="'.$fetch_evaluations->percent.'">Delete</a>
	                    </div>
	                </td>
	                <td>'.$fetch_evaluations->evalname.'</td>
	                <td>'.$fetch_evaluations->percent.'</td>
	                <td>'.$fetch_evaluations->academicyear.'</td>
	                <td>'.$fetch_evaluations->date_created.'</td>
	            </tr>';
			    $no++;
			}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                      <span>&times;</span>
                    </button><i class="fas fa-exclamation-triangle "></i> No summer evaluation found.
                </div>
              </div>';
		}
		return $output;
	}
	function fetchSummerSubjectPlacement($max_year){
		$this->db->where('Academic_Year',$max_year);
		$this->db->order_by('Subj_name','ASC');
		$this->db->group_by('Subj_name');
		$query=$this->db->get('summersubject');
		return $query->result();
	}
	function fetchSummerGradesec($max_year){
		$this->db->group_by('gradesec');
		$this->db->order_by('gradesec','ASC');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->like('usertype','Student');
		$query=$this->db->get('summerstudent');
		return $query->result();
	}
	function fetchSummerStaffPlacement($max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(st.grade) as gradess, GROUP_CONCAT(st.subject) as subjects from summerstaffplacement as st cross join users as us where st.staff=us.username and st.academicyear ='$max_year'  GROUP BY st.staff ORDER BY us.fname,us.mname,us.lname ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive"><table class="table table-striped table-hover"  style="width:100%;">
	                <thead>
	                  <tr>
	                    <th>No.</th>
	                    <th>Name</th>
	                    <th>Subject</th>
	                    <th>Grade</th>
	                    <th>Date Created</th>
	                  </tr>
	                </thead>
	            <tbody>';$no=1;
			foreach ($query->result() as $staffplacements) {
				$output.='<tr class="deleteSummerStaffplacement'.$staffplacements->staff.'">
	            <td>'. $no.'.</td>
	            <td>'.$staffplacements->fname.' '.$staffplacements->mname.'
	            <div class="table-links">
	              <a href="#" id="deleteSummerStaffplacement" class="text-danger" 
	              value="'.$staffplacements->staff.'" >Delete
	              </a>
	            </div>
	            </td>
	            <td style="word-break:break-all;">'.$staffplacements->subjects.'</td>
	            <td style="word-break:break-all;">'.$staffplacements->gradess.'</td>
	            <td>'.$staffplacements->date_created.'</td>
	          </tr>';
	            $no++; 
	        } 

	    	$output.='</tbody> </table></div>';
	    }else{
	    	$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No placement found.
            	</div></div>';
	    }
		return $output;
	}
	function checkSummerMarkImport($markname,$subname,$max_year,$gradesec,$mybranch){
		$this->db->where(array('markname'=>$markname));
		$this->db->where(array('subname'=>$subname));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('mgrade'=>$gradesec));
		$this->db->where(array('mbranch'=>$mybranch));
		$query=$this->db->get('summermark');
		if($query->num_rows()>0){
			return false;
		}else{
			return true;
		}
	}
	function filterSubjectFromSummer($gradesec,$max_year){
		$this->db->where('summerstudent.gradesec',$gradesec);
		$this->db->where(array('summersubject.Academic_Year'=>$max_year));
		$this->db->order_by('summersubject.Subj_name','ASC');
		$this->db->group_by('summersubject.Subj_name');
		$this->db->select('*');
		$this->db->from('summersubject');
		$this->db->join('summerstudent',
		'summerstudent.grade = summersubject.Grade');
		$query=$this->db->get();
		$output ='<option></option><option value="All"> All </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->Subj_name.'">'.$row->Subj_name.'</option>';
			}
			return $output;
	}
	function fetchSummerGradeMarkResult($gs_branches,$gs_gradesec,$gs_subject,$max_year)
	{
		$output='';
		if($gs_subject===trim('All'))
		{
			$queryFetchMark=$this->db->query("select * from summermark where academicyear='$max_year'  and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by subname order by subname ASC ");
			if($queryFetchMark->num_rows()>0){
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
				
        		foreach ($queryFetchMark->result_array() as $mark_name) {
        			$subject=$mark_name['subname'];
					$output.='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp; Subject :<B><u>'.$subject.'</u></B></div></br></h6>';
					
					$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from summerevaluation as ev inner join summerstudent as us where us.grade=ev.grade and ev.academicyear='$max_year' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

					$output.='<div class="table-responsive">
	        		<table class="tabler table-borderedr table-hover" style="width:100%;height:92%;page-break-inside:avoid;">
	        		<tr>
	        		<th rowspan="3">No.</th>
	            	<th rowspan="3">Student Name</th>
	            	<th rowspan="3" class="text-center">Student ID</th>';
        			foreach ($evalname_query->result_array() as $evalua_name) 
        			{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from summermark where subname='$subject' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
	            		$colSpan=$queryMvalue->num_rows() +2;
	            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
	            	}
	            	$output.='<th class="text-center"><B>Total</B></th><th rowspan="3" class="text-center">Sig.</th><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from summermark where subname='$subject' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
		            	foreach ($queryMvalue->result() as $mark_name)
		            	{
		            		$output.='<td class="text-center">'.$mark_name->markname.'</td>';
		            	}
		            	$output.='<td class="text-center"><b>Tot</b></td>';
		            	$output.='<td class="text-center"><b>Conv</b></td>';
		            }
		            $output.='<td rowspan="2" class="text-center"> <B>100</B> </td>';
	            	$output.='</tr><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$percent=$evalua_name['percent'];
	            		$queryMvalue1 = $this->db->query("select outof,sum(outof) as sum_outof from summermark where subname='$subject' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC",FALSE);
		            	$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from summermark where subname='$subject' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
            			$sumOutOf=0;
	            		foreach ($queryMvalue->result_array() as $mark_name) {
	            			$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            			$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            		}
	        			$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        			$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
		            }
	            	$output.='</tr>';
	            	$stuNO=1;
	            	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from summerstudent as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname,u.lname ASC ");
				foreach ($queryStudent->result_array() as $row) { 
            		$id=$row['id'];
            		$output.='<tr><td>'.$stuNO.'.</td> <td>'.$row['fname'].' '.$row['mname'].' '.$row['lname']. '</td>';
            		$output.='<td class="text-center">'.$row['username'].' </td>';
            		$average=0;
            		foreach ($evalname_query->result_array() as $mark_name)
            		{
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
	            		$query_value = $this->db->query("select markname,sum(value) as total from summermark where  subname='$subject' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from summermark where stuid='$id' and subname='$subject' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
								
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from summermark where subname='$subject' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
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
                	<i class="fas fa-check-circle"> </i> Data Not Found.
            	</div></div>';
			}
		}
		else{
			$querySingleSubject=$this->db->query("select * from summermark where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' ");
			if($querySingleSubject->num_rows()>0)
			{
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];

				$output.='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';

				$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from summerevaluation as ev inner join summerstudent as us where us.grade=ev.grade and ev.academicyear='$max_year' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

				$output.='<div class="table-responsive">
        		<table class="tabler table-borderedr table-hover" style="width:100%;height:85%">
        		<thead>
        		<tr>
        		<th rowspan="3">No.</th>
            	<th rowspan="3">Student Name</th>
            	<th rowspan="3" class="text-center">Student ID</th>';
            	
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from summermark where subname='$gs_subject' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
            		$colSpan=$queryMvalue->num_rows() +2;
            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
            	}
            	$output.='<th class="text-center">Total</th><th rowspan="3" class="text-center">Sig.</th><tr>';
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from summermark where subname='$gs_subject' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
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
            		$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from summermark where subname='$gs_subject' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
            		$sumOutOf=0;
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            		$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            	}
	        		$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        		$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
	            }
            	$output.='</tr>';
            	$stuNO=1;
            	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from summerstudent as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname ASC ");
				foreach ($queryStudent->result_array() as $row) { 
            		$id=$row['id'];
            		$output.='<tr><td>'.$stuNO.'.</td> <td>'.$row['fname'].' '.$row['mname'].' '.$row['lname']. '</td>';
            		$output.='<td class="text-center">'.$row['username'].' </td>';
            		$average=0;
            		foreach ($evalname_query->result_array() as $mark_name)
            		{
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
	            		$query_value = $this->db->query("select markname,sum(value) as total from summermark where  subname='$gs_subject' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from summermark where stuid='$id' and subname='$gs_subject' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
								
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from summermark where subname='$gs_subject' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
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
	function selectSummerMarkToEdit($edtimar,$gradesec,$academicyear,$branch){
		$this->db->where(array('mid'=>$edtimar));
		$query=$this->db->get('summermark');
		$output='';
		foreach ($query->result() as $value) {
			$output .='<input type="hidden" class="aYearSummer" value="'.$academicyear.'">';
			$output .='<input type="hidden" class="gsBranchSummer" value="'.$branch.'">';
			$output .='<input type="hidden" class="gSecSummer" value="'.$gradesec.'">';
			$output .='<input type="hidden" class="outofSummer" value="'.$value->outof.'">';
			$output .='<input type="hidden" class="midSummer" value="'.$value->mid.'">';
			$output .='<div class="row">
			<div class="col-lg-4"><h5 class="card-title">'.$value->markname.'</h5></div><div class="col-lg-8"><input class="form-control correct_mark_gsSummer" id="correct_valueSummer" type="text" value="'.$value->value.'"></div></div>
			<a class="info-markSummer"></a>';
		}
		return $output;
	}
	function FetchUpdatedMarkSummer($mid,$gradesec,$year,$branch){
		$this->db->where('mid',$mid);
		$query=$this->db->get('summermark');
		$output='';
		foreach ($query->result() as $keyvalue) {
			$output.=''.$keyvalue->value.'';
		}
		return $output;
	}
	function updateEditedMarkSummer($outof,$mid,$value,$gradesec,$year,$branch)
	{
		$this->db->where(array('mid'=>$mid));
		$this->db->set('value',$value);
		$query=$this->db->update('summermark');
		$output='';
		if($query){
			$output .='<span class="text-success"> Updated</span>';
		}else{
			$output .='<span class="text-danger"> ooops</span>';
		}
		return $output;
	}
	function fetchSummerEvaluationOnQuarterchange($gradesec,$max_year){
		$this->db->where('summerstudent.gradesec',$gradesec);
		$this->db->where(array('summerevaluation.academicyear'=>$max_year));
		$this->db->where(array('summerstudent.academicyear'=>$max_year));
		$this->db->order_by('summerevaluation.evname','ASC');
		$this->db->group_by('summerevaluation.evname');
		$this->db->select('*');
		$this->db->from('summerevaluation');
		$this->db->join('summerstudent',
		'summerstudent.grade = summerevaluation.grade');
		$query=$this->db->get();
		$output ='<option> </option>';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->eid.'">'.$row->evname.'</option>';
		}
		return $output;
	}
	function fetchThisGradeSummertudentsFornewexam($gradesec,$subject,$evaluation,$assesname,$percentage,$branch,$max_year){
		$output='';
		$query = $this->db->query(" Select * from summerstudent where academicyear='$max_year' and status='Active' and isapproved='1' and usertype ='Student' and gradesec='$gradesec' and branch='$branch' order by fname,mname ASC ");
		$output .='<div class="table-responsive">
        	<table class="table table-bordered table-hover" style="width:100%;">
        		<thead>
        		<tr>
        		    <th>Result</th>
            		<th>Name</th>
            		<th>Grade</th>
            		<th>Branch</th>
            		<th>Subject</th>
            		<th>Assesment Name</th>
            		<th>Percentage</th>
        		</tr>
        	</thead>';
        $output.='<input type="hidden" id="Sumacademicyear" value="'.$max_year.'"> ';
        $output.='<input type="hidden" id="Sumsubject" value="'.$subject.'"> ';
        $output.='<input type="hidden" id="Sumevaluation" value="'.$evaluation.'"> ';
        $output.='<input type="hidden" id="Sumpercentage" value="'.$percentage.'"> ';
        $output.='<input type="hidden" id="Sumassesname" value="'.$assesname.'"> ';
        $output.='<input type="hidden" id="SummarkGradeSec" value="'.$gradesec.'"> ';
        $output.='<input type="hidden" id="SummarkGradeSecBranch" value="'.$branch.'"> ';
		foreach ($query->result() as $fetch_student) {
			$output.='<input type="hidden" id="stuidSummer" 
			name="stuid_resultSummer" value="'.$fetch_student->id.'"> ';
			$output.='<tr class="'.$fetch_student->id.'">
			<td><input type="text" onkeyup="chkMarkValue()" name="markvalue_resultSummer" id="resultvalueSummer" class="form-control markvalue_resultSummer">
			 </td>';
			$output.='<td>'.$fetch_student->fname.' '.$fetch_student->mname.' '.$fetch_student->lname.'</td>';
			$output.='<td>'.$gradesec.'</td>';
			$output.='<td>'.$branch.'</td>';
			$output.='<td>'.$subject.'</td>';
			$output.='<td>'.$assesname.'</td>';
			$output.='<td>'.$percentage.'</td></tr>';
		}
		$output .='</table></div>';
		$output .='<button type="submit" id="SaveResultSummer" class="btn btn-success">Save Result </button>';
		return $output;
	}
	function saveThisSummerGradeResult($academicyear,$subject,$assesname,$markGradeSec,$markGradeSecBranch){
		$this->db->where(array('mgrade'=>$markGradeSec));
		$this->db->where(array('subname'=>$subject));
		$this->db->where(array('markname'=>$assesname));
		$this->db->where(array('academicyear'=>$academicyear));
		$this->db->where(array('mbranch'=>$markGradeSecBranch));
		$querystu=$this->db->get('summermark');
		$output='';
		if($querystu->num_rows()>0){
			return false;
		}else{
			return true;
		}
	}
	function fetchSummerGradeMark($gs_branches,$gs_gradesec,$gs_subject,$max_year)
	{
		$output='';
		$query=$this->db->query("select id,username,fname,mname,lname from summerstudent where branch='$gs_branches' and academicyear='$max_year' and gradesec='$gs_gradesec' and status='Active' and usertype='Student' and isapproved='1' and branch='$gs_branches' order by fname,mname,lname ASC ");

		$markname_query=$this->db->query("select ma.lockmark, ma.evaid, ma.markname,ma.mid, ma.value, ma.outof,sum(outof) as total_outof from summermark as ma where ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' group by ma.markname order by ma.mid ASC ");
		if($markname_query->num_rows()>0)
		{   
			$output.='<h6><small>Grade :</small>'.
			$gs_gradesec.' <small> Subject :</small> '.
			$gs_subject.'</h6>';
			$output.='<button class="btn btn-default delete_selected_gradeSummer pull-right">
			<span class="text-danger"><i class="fas fa-trash-alt"></i> Delete Grade '.$gs_gradesec.'</span> </button>';
			$output.='<button class="btn btn-default delete_selectedSummer pull-right">
			<span class="text-warning"><i class="fas fa-trash"></i> Delete '.$gs_subject.'</span> </button>';
			
			$output.='<div class="table-responsive">
    		<table class="table table-borderedr table-hover" style="width:100%;">
    		<tr> <th rowspan="2" class="text-center">Student Name</th> 
        	<th rowspan="2" class="text-center">Student ID</th>';
        	foreach ($markname_query->result_array() as $mark_name) {
        		$output.='<th class="coreMarkName'.$mark_name['markname'].'">' .$mark_name['markname'].'';
        		if($mark_name['lockmark']=='0'){
	        		$output.='<div class="table-links"> 
						<a href="#" value="'.$mark_name['markname'].'" 
						class="gs_delete_marknameSummer"> 
						<span class="text-danger"><i class="fas fa-trash"></i> </span> </a>
				    </div> </th>';
				}
        	}
        	$output.=' </tr><tr>';
			foreach ($markname_query->result_array() as $mark_name) 
			{
        		$output.='<td class="coreOutOFSummer'.$mark_name['outof'].$mark_name['markname'].'">'.$mark_name['outof'].'</td> ';
        	}
        	$output.='</tr>';
        	$output.='<input type="hidden" class="jo_gradesecSummer" value="'.$gs_gradesec.'">
			<input type="hidden" class="jo_subjectSummer" value="'.$gs_subject.'">
			<input type="hidden" class="jo_branchSummer" value="'.$gs_branches.'">
			<input type="hidden" class="jo_yearSummer" value="'.$max_year.'">';
			foreach ($query->result_array() as $row) 
			{ 
        		$id=$row['id'];
        		$output.='<tr> <td> '.$row['fname'].' '.$row['mname'].' '.$row['lname'].' </td>
        		<td>'.$row['username'].'</td>';
        		foreach ($markname_query->result_array() as $mark_name)
        		{
        			$Evaid=$mark_name['evaid'];
        			$outOFF=$mark_name['outof'];
        			$markname=$mark_name['markname'];
        			$lockmark1=$mark_name['lockmark'];
        			$query_value = $this->db->query("select lockmark,value, outof,mid, markname from summermark where markname='$markname' and stuid='$id' and subname='$gs_subject' ");
        			if($query_value->num_rows()>0)
        			{
        				$mark_value=$query_value->row_array();
    					$outof=$mark_value['outof'];
    					$value=$mark_value['value'];
    					$lockmark=$mark_value['lockmark'];
						if($outof >= $value){
							$output.='<td class="jossMarkSummer'.$mark_value['mid'].'">'.$mark_value['value'].'<small class="text-muted">('.$mark_value['markname'].')</small>';
							if($lockmark==='0'){
								$output.='<div class="table-links"> <a href="#" value="'.$mark_value['mid'].'" class="edit_mark_gsSummer" data-toggle="modal"
								data-target="#editmarkSummer">
								<span class="text-success">
								<i class="far fa-edit"> </i></span></a>
								   </div>';
                         	}else{
                         		$output.='<div  class="table-links"> 
                         			<span class="text-warning"><i class="fas fa-lock"> </i> </span>
								   </div>';
                         	}
							$output.='</td>';
						}else{
							$output.='<td><span class="text-danger"> NG
								<div class="table-links"> 
								<a href="#" value="'.$mark_value['mid'].'" class="edit_mark_gsSummer" data-toggle="modal" 
								data-target="#editmarkSummer"><span class="text-info"> Edit 
								</span></a>
							</div>
							</span></td>';
						}
        			}else{
        				if($lockmark1=='0'){
							$output.='<td class="JoMarkSummer'.$id.$markname.'">
							<input type="hidden" value="" class="my_IDSummer">
							<span class="text-danger"> NG</span>
							<div class="table-links"> 
								<a href="#" title="'.$id.'" id="'.$outOFF.'" name="'.$markname.'" value="'.$Evaid.'" class="edit_NGmark_gsSummer" data-toggle="modal" 
								data-target="#editngmarkSummer"><span class="text-info"> 
								<i class="fas fa-plus"></i> 
								</span></a>
							</div>
							</td>';
						}else{
							$output.='<td class="JoMarkSummer'.$id.'">
							<input type="hidden" value="" class="my_IDSummer">
							<span class="text-danger"> NG</span>
							<div class="table-links"> 
								<span class="text-warning"><i class="fas fa-lock"> </i> </span>
							</div>
							</td>';
						}
					}
        		}
			}
			$output.='</tr></table></div>';
		}else{
    		$output.='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> Data not found.
            </div></div>';
		}
		return $output;
	}
	function fetchSummerGradeMarkTeacher($gs_branches,$gs_gradesec,$gs_subject,$max_year)
	{
		$output='';
		$query=$this->db->query("select id,username,fname,mname,lname from summerstudent where branch='$gs_branches' and academicyear='$max_year' and gradesec='$gs_gradesec' and status='Active' and usertype='Student' and isapproved='1' and branch='$gs_branches' order by fname,mname,lname ASC ");

		$markname_query=$this->db->query("select ma.lockmark, ma.evaid, ma.markname,ma.mid, ma.value, ma.outof,sum(outof) as total_outof from summermark as ma where ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' group by ma.markname order by ma.mid ASC ");
		if($markname_query->num_rows()>0)
		{   
			$output.='<h6><small>Grade :</small>'.
			$gs_gradesec.' <small> Subject :</small> '.
			$gs_subject.'</h6>';
			
			$output.='<div class="table-responsive">
    		<table class="table table-borderedr table-hover" style="width:100%;">
    		<tr> <th rowspan="2" class="text-center">Student Name</th> 
        	<th rowspan="2" class="text-center">Student ID</th>';
        	foreach ($markname_query->result_array() as $mark_name) {
        		$output.='<th class="coreMarkName'.$mark_name['markname'].'">' .$mark_name['markname'].'';
        		if($mark_name['lockmark']=='0'){
	        		$output.='<div class="table-links"> 
						<a href="#" value="'.$mark_name['markname'].'" 
						class="gs_delete_marknameSummerTeacher"> 
						<span class="text-danger"><i class="fas fa-trash"></i> </span> </a>
				    </div> </th>';
				}
        	}
        	$output.=' </tr><tr>';
			foreach ($markname_query->result_array() as $mark_name) 
			{
        		$output.='<td class="coreOutOFSummer'.$mark_name['outof'].$mark_name['markname'].'">'.$mark_name['outof'].'</td> ';
        	}
        	$output.='</tr>';
        	$output.='<input type="hidden" class="jo_gradesecSummerTeacher" value="'.$gs_gradesec.'">
			<input type="hidden" class="jo_subjectSummerTeacher" value="'.$gs_subject.'">
			<input type="hidden" class="jo_branchSummerTeacher" value="'.$gs_branches.'">
			<input type="hidden" class="jo_yearSummerTeacher" value="'.$max_year.'">';
			foreach ($query->result_array() as $row) 
			{ 
        		$id=$row['id'];
        		$output.='<tr> <td> '.$row['fname'].' '.$row['mname'].' '.$row['lname'].' </td>
        		<td>'.$row['username'].'</td>';
        		foreach ($markname_query->result_array() as $mark_name)
        		{
        			$Evaid=$mark_name['evaid'];
        			$outOFF=$mark_name['outof'];
        			$markname=$mark_name['markname'];
        			$lockmark1=$mark_name['lockmark'];
        			$query_value = $this->db->query("select lockmark,value, outof,mid, markname from summermark where markname='$markname' and stuid='$id' and subname='$gs_subject' ");
        			if($query_value->num_rows()>0)
        			{
        				$mark_value=$query_value->row_array();
    					$outof=$mark_value['outof'];
    					$value=$mark_value['value'];
    					$lockmark=$mark_value['lockmark'];
						if($outof >= $value){
							$output.='<td class="jossMarkSummer'.$mark_value['mid'].'">'.$mark_value['value'].'<small class="text-muted">('.$mark_value['markname'].')</small>';
							if($lockmark==='0'){
								$output.='<div class="table-links"> <a href="#" value="'.$mark_value['mid'].'" class="edit_mark_gsSummerteacher" data-toggle="modal"
								data-target="#editmarkSummer">
								<span class="text-success">
								<i class="far fa-edit"> </i></span></a>
								   </div>';
                         	}else{
                         		$output.='<div  class="table-links"> 
                         			<span class="text-warning"><i class="fas fa-lock"> </i> </span>
								   </div>';
                         	}
							$output.='</td>';
						}else{
							$output.='<td><span class="text-danger"> NG
								<div class="table-links"> 
								<a href="#" value="'.$mark_value['mid'].'" class="edit_mark_gsSummer" data-toggle="modal" 
								data-target="#editmarkSummer"><span class="text-info"> Edit 
								</span></a>
							</div>
							</span></td>';
						}
        			}else{
        				if($lockmark1=='0'){
							$output.='<td class="JoMarkSummer'.$id.$markname.'">
							<input type="hidden" value="" class="my_IDSummer">
							<span class="text-danger"> NG</span>
							<div class="table-links"> 
								<a href="#" title="'.$id.'" id="'.$outOFF.'" name="'.$markname.'" value="'.$Evaid.'" class="edit_NGmark_gsSummer" data-toggle="modal" 
								data-target="#editngmarkSummer"><span class="text-info"> 
								<i class="fas fa-plus"></i> 
								</span></a>
							</div>
							</td>';
						}else{
							$output.='<td class="JoMarkSummer'.$id.'">
							<input type="hidden" value="" class="my_IDSummer">
							<span class="text-danger"> NG</span>
							<div class="table-links"> 
								<span class="text-warning"><i class="fas fa-lock"> </i> </span>
							</div>
							</td>';
						}
					}
        		}
			}
			$output.='</tr></table></div>';
		}else{
    		$output.='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> Data not found.
            </div></div>';
		}
		return $output;
	}
	function checkSummerSubject($subject,$grade,$max_year){
		$this->db->where(array('Subj_name'=>$subject));
		$this->db->where(array('gradesec'=>$grade));
		$this->db->where(array('Academic_Year'=>$max_year));
		$this->db->where('usertype','Student');
		$this->db->select('*');
        $this->db->from('summersubject');
        $this->db->join('summerstudent', 
            'summerstudent.grade = summersubject.Grade');
        $query = $this->db->get();
		if($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	function addSummerPlacement($staff,$subject,$checkbox,$academicyear){
		$this->db->where(array('staff'=>$staff));
		$this->db->where(array('grade'=>$checkbox));
		$this->db->where(array('academicyear'=>$academicyear));
		$this->db->where(array('subject'=>$subject));
		$query=$this->db->get('summerstaffplacement');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}
	function deleteSummerPlacement($id){
		$this->db->where(array('staff'=>$id));
		$this->db->delete('summerstaffplacement');
	}
	function addSummerEvaluation($grade,$evname,$ac){
		$this->db->where(array('grade'=>$grade));
		$this->db->where(array('evname'=>$evname));
		$this->db->where(array('academicyear'=>$ac));
		$query=$this->db->get('summerevaluation');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}
	function deleteSummerEvaluation($id,$evname,$max_year){
		$this->db->where(array('percent'=>$id));
		$this->db->where(array('evname'=>$evname));
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->delete('summerevaluation');
		if($query){
			return true;
		}
		else{
			return false;
		}
	}
	function updateSummerStudentDetail($id,$username,$data,$max_year){
		$queryChk=$this->db->query("select * from summerstudent where unique_id='$id' and username='$username' ");
		$output='';
		if($queryChk->num_rows()<1){
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i>Username exists, please try other username.
            </div></div>';
		}else{
			$this->db->where(array('unique_id'=>$id));
			$query=$this->db->update('summerstudent',$data);
			if($query){
				$output.='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Profile updated successfully.
            	</div></div>';
			}else{
				$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> ooops, please try again.
            	</div></div>';
			}
		}
		return $output;
	}
	function fetchSummerClassStatus($max_year){
		$this->db->order_by('classname','ASC');
		$this->db->where('academicyear',$max_year);
        $query = $this->db->get('startsummerclass');
		$output='';
		if($query->num_rows()>0){
			foreach ($query->result() as $fetch_evaluations) {
				$output.='<h5 class="card-header">Summer class already started. 
				<div class="pretty p-switch p-fill">
	              <input type="checkbox" name="startSummerClass" class="startSummerClass" checked="checked" id="'.$fetch_evaluations->scid.'" value="'.$fetch_evaluations->classname.'" >
	              <div class="state p-success">
	                <label></label>
	              </div>
	            </div> </h5>';
			}
		}else{
			$output.='<h5 class="card-header">Click here if summer class starts. 
			 <div class="pretty p-switch p-fill">
                <input type="checkbox" name="startSummerClass" class="startSummerClass"  id="" value="" >
              	<div class="state p-success">
                	<label></label>
              	</div>
		    </div></h5> ';
		}
		return $output;
	}
	function checkAutoMarkLock($max_year,$max_quarter){
		$querychkAutoLock=$this->db->query("select * from lockmarkauto where academicyear='$max_year' and autolockstatus='1' ");
        if($querychkAutoLock->num_rows()>0){
        	$query2 = $this->db->query("select endate from quarter where Academic_Year='$max_year' and term='$max_quarter' ");
        	if($query2->num_rows()>0){
        		$row2 = $query2->row();
	        	$date2=$row2->endate;
	            $changeDate2 = DateTime::createFromFormat('d/m/y',$date2);
	            $endDate1= $changeDate2->format('Y-m-d');
	            $today2=Date('Y-m-d');
	            if($endDate1<=$today2){
	                return true;
	            }else{
	                return false;
	            }
        	}else{
        		return true;
        	}
        }else{
        	return false;
        }
	}
	function lockMarkAutomatically($max_year){
		$this->db->where('academicyear',$max_year);
		$query = $this->db->get('lockmarkauto');
		$output='Lock Mark Automatically on Semester/Quarter Date';
		if($query->num_rows()>0){
			$output.='<div class="pretty p-switch p-fill">
                <input type="checkbox" name="lockmarkautoOn" class="lockmarkautoOn" checked="checked" id="0" value="0" >
                <div class="state p-success">
                    <label></label>
                </div>
		    </div>';
		}else{
			$output.='<div class="pretty p-switch p-fill">
              	<input type="checkbox" name="lockmarkautoOn" class="lockmarkautoOn" id="1" value="1" >
              	<div class="state p-success">
                	<label></label>
              	</div>
		    </div>';
		}
		return $output;
	}
	function enableMarkAuto($max_year){
		$this->db->where('academicyear',$max_year);
		$query = $this->db->get('enableapprovemark');
		$output='Directors Must approve mark to be public for Parents/Students.';
		if($query->num_rows()>0){
			$output.='<div class="pretty p-switch p-fill">
                <input type="checkbox" name="enableapprovemark" class="enableapprovemark" checked="checked" id="0" value="0" >
                <div class="state p-success">
                    <label></label>
                </div>
		    </div>';
		}else{
			$output.='<div class="pretty p-switch p-fill">
              	<input type="checkbox" name="enableapprovemark" class="enableapprovemark" id="1" value="1" >
              	<div class="state p-success">
                	<label></label>
              	</div>
		    </div>';
		}
		return $output;
	}
	function fetchDivisionStatus($max_year,$max_quarter){
		$this->db->order_by('sd.dname','ASC');
		$this->db->where('sd.academicyear',$max_year);
		$this->db->where('q.Academic_Year',$max_year);
		$this->db->where('q.term',$max_quarter);
		$this->db->select('*');
        $this->db->from('schooldivision sd');
        $this->db->join('quarter q', 
            'q.Academic_Year = sd.academicyear');
        $query = $this->db->get();
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive"> 
	            <table class="table table-stripped table-hover" style="width:100%;">
	            <thead><tr> 
	                <th>No.</th>
	                <th>Division Name</th>
	                <th>Academic Year</th>
	                <th>Quarter</th>
	                <th>Status</th> </tr>
	            </thead>';
	        $no=1;
			foreach ($query->result() as $fetch_evaluations) {
				$output.='
				<tr class="">
	                <td>'.$no.'.</td>
	                <td>'.$fetch_evaluations->dname.'</td>
	                <td>'.$fetch_evaluations->academicyear.'</td>
	                <td>'.$fetch_evaluations->term.'</td>';
	                $queryChk=$this->db->query("select * from dmarkstatus where academicyear='$max_year' and dname='$fetch_evaluations->dname' and dquarter='$fetch_evaluations->term' ");
	                if($queryChk->num_rows()>0){
						$output.=' <td>
						<div class="pretty p-switch p-fill">
		                  <input type="checkbox" name="addmarkstatus" class="addmarkstatus" checked="checked" id="'.$fetch_evaluations->term.'" value="'.$fetch_evaluations->dname.'" >
		                  <div class="state p-success">
		                    <label></label>
		                  </div>
		                </div></td>';
	                }else{
	                	$output.=' <td><div class="pretty p-switch p-fill">
		                  <input type="checkbox" name="addmarkstatus" class="addmarkstatus"  id="'.$fetch_evaluations->term.'" value="'.$fetch_evaluations->dname.'" >
		                  <div class="state p-success">
		                    <label></label>
		                  </div>
		                </div></td>';
	                }
	           $output.='</tr>';
			    $no++;
			}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                          <span>&times;</span>
                        </button><i class="fas fa-exclamation-triangle "></i> No record yet.
                    </div>
                  </div>';
		}
		return $output;
	}
	function fetch_eval_grade($max_year,$max_quarter,$min_quarter){
		$query=$this->db->query("select *, GROUP_CONCAT(grade) as evalname from evaluation where academicyear='$max_year' and quarter='$max_quarter' group by percent,evname order by grade DESC ");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive"> 
	            <table class="table table-stripped table-hover" style="width:100%;">
	            <thead><tr> 
	                <th>No.</th>
	                <th>Evaluation Name</th>
	                <th>Quarter/Term/Semester</th>
	                <th>Grade</th>
	                <th>Percentage</th>
	                <th>Academic Year</th>
	                <th>Date Created</th> </tr>
	            </thead>';
	        $no=1;
			foreach ($query->result() as $fetch_evaluations) {
				$output.='
				<tr class="delete_mem'.$fetch_evaluations->percent.''.$fetch_evaluations->evname.'">
	                <td>'.$no.'</td>
	                <td>'.$fetch_evaluations->evname.'
	                    <div class="table-links">
	                        <a href="#" name="'.$fetch_evaluations->evname.'" value="'.$fetch_evaluations->quarter.'" class="deletevaluation text-danger" id="'.$fetch_evaluations->percent.'">Delete</a>
	                    	<div class="bullet"></div>
	                        <a href="#" data-toggle="modal" data-target="#save_evaluations" name="'.$fetch_evaluations->evname.'" value="'.$fetch_evaluations->quarter.'" class="editevaluation text-success" id="'.$fetch_evaluations->percent.'">Edit</a>
	                    </div>
	                </td>
	                <td>'.$fetch_evaluations->quarter.'</td>
	                <td>'.$fetch_evaluations->evalname.'</td>
	                <td>'.$fetch_evaluations->percent.'</td>
	                <td>'.$fetch_evaluations->academicyear.'</td>
	                <td>'.$fetch_evaluations->date_created.'</td>
	            </tr>';
			    $no++;
			}
		}else{
			$queryMin=$this->db->query("select *, GROUP_CONCAT(grade) as evalname from evaluation where academicyear='$max_year' and quarter='$min_quarter' group by percent,evname order by grade DESC ");
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                      <span>&times;</span>
                    </button><i class="fas fa-exclamation-triangle "></i> No evaluation for this term/Quarter '.$min_quarter.' yet.
                </div>
              </div>';
            if($queryMin->num_rows()>0){
                $output.='<span class="text-dark">You can move previous evaluation from previous quarter/term here </span><button type="submit" id="movevaluation" class="btn btn-dark text-center"> Move Evaluation</button>';
            }
		}
		return $output;
	}
	function fetchGradeGroup($max_year){
		$query=$this->db->query("select *, GROUP_CONCAT(divgrade) as groupdivname from gradedivision group by divname order by divname ASC ");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive"> 
	            <table class="table table-stripped table-hover" style="width:100%;">
	            <thead><tr> 
	                <th>No.</th>
	                <th>Group Name</th>
	                <th>Grade</th>
	                <th>Academic Year</th>
	                <th>Date Created</th> </tr>
	            </thead>';
	        $no=1;
			foreach ($query->result() as $fetch_evaluations) {
				$output.='
				<tr class="delete_mem'.$fetch_evaluations->divname.'">
	                <td>'.$no.'.</td>
	                <td>'.$fetch_evaluations->divname.'
	                    <div class="table-links">
	                        <a href="#" name="'.$fetch_evaluations->divname.'" value="'.$fetch_evaluations->divname.'" class="deletGroupGrade text-danger" id="'.$fetch_evaluations->divname.'">Delete</a>
	                    </div>
	                </td>
	                <td>'.$fetch_evaluations->groupdivname.'</td>
	                <td>'.$fetch_evaluations->academicyear.'</td>
	                <td>'.$fetch_evaluations->datecreated.'</td>
	            </tr>';
			    $no++;
			}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                      <span>&times;</span>
                    </button><i class="fas fa-exclamation-triangle "></i> No record found.
                </div>
              </div>';
		}
		return $output;
	}
	function fetch_evaluation_status($max_year,$max_quarter){
		$items = array();
		$query=$this->db->query("select grade, sum(percent) as total from evaluation where academicyear='$max_year' and quarter='$max_quarter' group by grade order by grade DESC ");
		foreach ($query->result() as $klue) {
			if($klue->total!=='100'){
				$items[] = $klue->grade;    			
    		}
		}
    	return $items;
	}
	function edit_evaluation($id,$quarter,$evname,$max_year){
		$this->db->where(array('percent'=>$id));
		$this->db->where(array('quarter'=>$quarter));
		$this->db->where(array('evname'=>$evname));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->group_by('evname');
		$this->db->group_by('percent');
		$query=$this->db->get('evaluation');
		$output='<div class="row">';
		foreach ($query->result() as $keyalue) {
			/*$output.='<input type="hidden" id="my_percent" value="'.$keyalue->percent.'">
				<input type="hidden" id="my_evname" value="'.$keyalue->evname.'">';*/
			$output .='<div class="col-md-6 col-6">
			    <div class="form-group">
			   <input type="text" disabled="disabled" class="form-control" value="'.$keyalue->quarter.'">
			  </div> </div>';
			$output .='<div class="col-md-6 col-6">
			    <div class="form-group">
			   <input type="text" class="form-control" id="new_evname" value="'.$keyalue->evname.'">
			  </div> </div>';

			/*$output .='<div class="col-md-4">
					<div class="form-group">
				   		<input type="text" id="new_percent" class="form-control" value="'.$keyalue->percent.'">
				  	</div>
			   </div>';*/
			$query_grade=$this->db->query("select * from evaluation where evname='$evname' and quarter='$quarter' and academicyear='$max_year' and percent='$id' ");
			foreach ($query_grade->result() as $keyue) {

				$output.='<input type="hidden" id="my_quarterEval" value="'.$keyue->quarter.'">
				<input type="hidden" id="my_ac" value="'.$keyue->academicyear.'">';

				$output .='<div class="col-md-3 col-6 StudentViewTextInfo" id="deleteEva'.$keyue->grade.''.$keyue->evname.'"> 
					<a href="#" value="'.$keyue->grade.'" id="'.$keyue->evname.'" name="'.$keyue->quarter.'" class="btn btn-outline-danger btn-block remove_evalGS"><small><i class="fas fa-trash-alt"></i> Gr '.$keyue->grade.' </small>  </a><br>';
              			$output.='<select name="percentageGradeEvaluation" style="width: 100px" class="form-control custom-select" id="percentageGradeEvaluation">';
	          		for($i=100;$i>=1;$i--) { 
	          			if($i==$keyue->percent ){
	          				$output.=' <option selected="selected" class="percentageGradeEvaluation" name="'.$keyue->evname.'" id="'.$keyue->grade.'" value="'.$i.'">
	                    '.$i.'</option>';
	          			}else{
	          				$output.=' <option class="percentageGradeEvaluation" name="'.$keyue->evname.'" id="'.$keyue->grade.'" value="'.$i.'">
	                    '.$i.'</option>';
	          			}
	                }
	                $output.='</select>
              	</div>';
			}
		}
		$output.='</div>';
		return $output;
	}
	function delete_thisgradevaluation($grade,$quarter,$evname,$max_year){
		$this->db->where(array('grade'=>$grade));
		$this->db->where(array('quarter'=>$quarter));
		$this->db->where(array('evname'=>$evname));
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->delete('evaluation');
		if($query){
			return true;
		}
		else{
			return false;
		}
	}
	function edit_thisgradevaluation($max_quarter,$evname,$max_year,$new_evname){
		/*$this->db->where(array('percent'=>$percent));*/
		$this->db->where(array('quarter'=>$max_quarter));
		$this->db->where(array('evname'=>$evname));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->set('evname',$new_evname);
		$query=$this->db->update('evaluation');
		if($query){
			return true;
		}
		else{
			return false;
		}
	}
	function deleteGradeGroup($divname){
		$this->db->where(array('divname'=>$divname));
		$query=$this->db->delete('gradedivision');
		if($query){
			return true;
		}
		else{
			return false;
		}
	}
	function delete_evaluation($id,$quarter,$evname,$max_year){
		$this->db->where(array('percent'=>$id));
		$this->db->where(array('quarter'=>$quarter));
		$this->db->where(array('evname'=>$evname));
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->delete('evaluation');
		if($query){
			return true;
		}
		else{
			return false;
		}
	} 
	function fetch_bgcolor($id){
		$this->db->where('sid',$id);
		$query=$this->db->get('bgcolor');
		$output='';
		foreach ($query->result() as $value) {
			$output .=''.$value->bgcolor.'';
		}
		return $output;
	}
	function fetch_unseen_message_notification($username){
		$this->db->where('status','0');
		$this->db->where('receiver',$username);
		$this->db->from('message');
		return $this->db->count_all_results();
	}
	function update_unseen_message_notification($username){
		$this->db->where('receiver',$username);
		$this->db->where('status','0');
		$this->db->set('status','1');
		$this->db->update('message');
	}
	function fetchAllMyMessages($username){
		$this->db->where('receiver',$username);
		$this->db->where('status','0');
		$this->db->group_by('id');
		$query=$this->db->get('message');
		return $query;
	}
	function fetchAllMyNewUserNotification(){
	    $this->db->order_by('id','DESC');
		$this->db->select('*');
        $this->db->from('users');
        $this->db->where('isapproved','0');
        $query = $this->db->get();
		return $query;
	}
	function fetch_unseen_notification(){
		$this->db->where('isapproved','0');
		$this->db->from('users');
		return $this->db->count_all_results();
	}
	function fetch_post_likes($id){
		$this->db->where('pid',$id);
		$this->db->from('post_like');
		return $this->db->count_all_results();
	}
	function fetch_galleryToWebsite(){
		$this->db->order_by('gid','DESC');
		$query=$this->db->get('gallery');
		return $query->result();
	}
	function fetch_gallery(){
		$this->db->order_by('gid','random()');
		$this->db->limit('4');
		$query=$this->db->get('gallery');
		return $query->result();
	}
	function all_fetch_gallery(){
		$this->db->order_by('gid','DESC');
		$query=$this->db->get('gallery');
		$output='<div class="row"> ';
		if($query->num_rows()>0){
			foreach ($query->result() as $fetch_gallerys) {
				$output.='<div class="col-lg-3 delete_mem'.$fetch_gallerys->gid.'">
                    <div class="hover_to_deletegallery"> 
                        <a href="#" 
                          data-sub-html="'.$fetch_gallerys->gtitle.'">
                          <img class="img-responsive" 
                          src="'.base_url().'/gallery/'. $fetch_gallerys->gname.'" alt="" style="height:240px;width:100%">
                        </a>
                        <div class="table-links">
                        <button type="submit" name="deletegallery" class="btn btn-default deletegallery" value="'.$fetch_gallerys->gid.'">
                          <span class="text-danger">
                          <i class="fa fa-trash"></i></span>
                        </button>
                       </div>
                    </div>
                </div> <div class="dropdown-divider"></div>';
			}
		}else{
			$output .='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No saved gallery found..
            </div></div>';
		}
		$output.='</div>';
		return $output;
	}
	function fetch_single_gallery(){
		$this->db->order_by('gid','random()');
		$this->db->limit('1');
		$query=$this->db->get('gallery');
		return $query->result();
	}
	function delete_gallery($id){
		$this->db->where('gid',$id);
		$this->db->delete('gallery');
	}
	function import_student($username,$usertype,$stu_id,$name,$fathername,$gfathername,$mobile,$fathermobile,$email,$grade,$section,$grasection,$dob,$age,$gender,$password,$confpassword,$mothername,$city,$subcity,$woreda,$kebele,$isapprove,$registrationdate,$branch,$transportService,$academicyear,$status){
		$this->db->where('username = ',$username);
		$this->db->or_where('unique_id = ',$stu_id);
		$data=array(
			'username'=>$username,
			'usertype'=>$usertype,
			'unique_id'=>$stu_id,
			'fname'=>$name,
			'mname'=>$fathername,
			'lname'=>$gfathername,
			'mobile'=>$mobile,
			'father_mobile'=>$fathermobile,
			'email'=>$email,
			'grade'=>$grade,
			'section'=>$section,
			'gradesec'=>$grasection,
			'dob'=>$dob,
			'age'=>$age,
			'gender'=>$gender,
			'password'=>hash('sha256', $password),
			'password2'=>hash('sha256', $password),
			'mother_name'=>$mothername,
			'city'=>$city,
			'sub_city'=>$subcity,
			'woreda'=>$woreda,
			'kebele'=>$kebele,
			'isapproved'=>$isapprove,
			'dateregister'=>$registrationdate,
			'branch'=>$branch,
			'transportservice'=>$transportService,
			'academicyear'=>$academicyear,
			'status'=>$status
		);
		$query=$this->db->get('users');
		if($query->num_rows() > 0){
			return false;
		}else{
			$this->db->insert('users',$data);
			return true;
		}
	}
	function import_staffs($username){
		$this->db->where('username = ',$username);
		$query=$this->db->get('users');
		if($query->num_rows() > 0){
			return false;
		}else{
			return true;
		}
	}
	function addHomeRoomPlacement($teacher,$branch,$checkbox,$academicyear){
		$this->db->where(array('teacher'=>$teacher));
		$this->db->where(array('roomgrade'=>$checkbox));
		$this->db->where(array('academicyear'=>$academicyear));
		$this->db->where(array('branch'=>$branch));
		$query=$this->db->get('hoomroomplacement');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}
	function fetchHomeRoomplacement($max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(st.roomgrade) as gradess from hoomroomplacement as st cross join users as us where st.teacher=us.username and st.academicyear ='$max_year'  GROUP BY st.teacher ORDER BY st.teacher ASC");
		$output='';
		if($query->num_rows()>0){
		$output='<div class="row">';
			foreach ($query->result() as $staffplacements) {
				$output.='<div class ="col-lg-3 col-6">
				<ul class="list-unstyled list-unstyled-border list-unstyled-noborder delete_hrplacement'.$staffplacements->teacher.'">
                      <li class="media">';
                      	if($staffplacements->profile!=''){
                            $output.='<td><img class="mr-3 rounded-circle" src="'.base_url().'profile/'.$staffplacements->profile.'" width="70"></td>';
                        }else{
                            $output.='<td><img class="mr-3 rounded-circle" src="'.base_url().'profile/defaultProfile.png" width="70"></td>';
                        }
                        $output.='<div class="media-body">
                          <div class="">
                            <div class="text-info">'.$staffplacements->branch.'</div>
                          </div>
                          <div class="media-title mb-1">'.$staffplacements->fname.' '.$staffplacements->mname.'</div>
                          <div class="text-time">'.$staffplacements->date_created.'</div>
                          <div class="media-description text-muted">Grade: '.$staffplacements->gradess.' <a href="#" id="delete_hoomroomplacemet" class="text-danger" value="'.$staffplacements->teacher.'" ><i class="fas fa-trash-alt"></i> </a></div>
                        </div>
                      </li>
                    </ul>
                </div>';
	        } 
         	$output.='</div>';
	    }else{
	    	$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No hoom room teacher placement found.
            </div></div>';
	    }
		return $output;
	}
	function fetchMyHomeRoomplacement($max_year,$branch){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(st.roomgrade) as gradess from hoomroomplacement as st cross join users as us where st.teacher=us.username and st.academicyear ='$max_year' and st.branch='$branch'  GROUP BY st.teacher ORDER BY st.teacher ASC");
		$output='';
		if($query->num_rows()>0){
         	$output='<div class="row">';
			foreach ($query->result() as $staffplacements) {
				$output.='<div class ="col-lg-3 col-6">
				<ul class="list-unstyled list-unstyled-border list-unstyled-noborder delete_hrplacement'.$staffplacements->teacher.'">
                      <li class="media">';
                      	if($staffplacements->profile!=''){
                            $output.='<td><img class="mr-3 rounded-circle" src="'.base_url().'profile/'.$staffplacements->profile.'" width="70"></td>';
                        }else{
                            $output.='<td><img class="mr-3 rounded-circle" src="'.base_url().'profile/defaultProfile.png" width="70"></td>';
                        }
                        $output.='<div class="media-body">
                          <div class="">
                            <div class="text-info">'.$staffplacements->branch.'</div>
                          </div>
                          <div class="media-title mb-1">'.$staffplacements->fname.' '.$staffplacements->mname.'</div>
                          <div class="text-time">'.$staffplacements->date_created.'</div>
                          <div class="media-description text-muted">Grade: '.$staffplacements->gradess.' <a href="#" id="delete_hoomroomplacemet" class="text-danger" value="'.$staffplacements->teacher.'" ><i class="fas fa-trash-alt"></i> </a></div>
                        </div>
                      </li>
                    </ul>
                </div>';
	        } 
         	$output.='</div>';
	    }else{
	    	$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No hoom room teacher placement found.
            </div></div>';
	    }
		return $output;
	}
	function searchHroomStaffs($searchItem,$max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(st.roomgrade) as gradess from hoomroomplacement as st cross join users as us where st.teacher=us.username and st.academicyear ='$max_year' and username LIKE '%$searchItem%' ||  st.teacher=us.username and st.academicyear ='$max_year' and fname LIKE '%$searchItem%' ||  st.teacher=us.username and st.academicyear ='$max_year' and mname LIKE '%$searchItem%' GROUP BY st.teacher ORDER BY st.teacher ASC");

		$output='';
		if($query->num_rows()>0){
			$output='<div class="row">';
			foreach ($query->result() as $staffplacements) {
				$output.='<div class ="col-lg-3 col-6">
				<ul class="list-unstyled list-unstyled-border list-unstyled-noborder delete_hrplacement'.$staffplacements->teacher.'">
                      <li class="media">';
                      	if($staffplacements->profile!=''){
                            $output.='<td><img class="mr-3 rounded-circle" src="'.base_url().'profile/'.$staffplacements->profile.'" width="70"></td>';
                        }else{
                            $output.='<td><img class="mr-3 rounded-circle" src="'.base_url().'profile/defaultProfile.png" width="70"></td>';
                        }
                        $output.='<div class="media-body">
                          <div class="">
                            <div class="text-info">'.$staffplacements->branch.'</div>
                          </div>
                          <div class="media-title mb-1">'.$staffplacements->fname.' '.$staffplacements->mname.'</div>
                          <div class="text-time">'.$staffplacements->date_created.'</div>
                          <div class="media-description text-muted">Grade: '.$staffplacements->gradess.' <a href="#" id="delete_hoomroomplacemet" class="text-danger" value="'.$staffplacements->teacher.'" ><i class="fas fa-trash-alt"></i> </a></div>
                        </div>
                      </li>
                    </ul>
                </div>';
	        } 
         	$output.='</div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function searchHroomStaffsAdmin($searchItem,$max_year,$branch){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(st.roomgrade) as gradess from hoomroomplacement as st cross join users as us where st.teacher=us.username and st.academicyear ='$max_year' and us.branch='$branch' and username LIKE '%$searchItem%' ||  st.teacher=us.username and st.academicyear ='$max_year' and us.branch='$branch' and fname LIKE '%$searchItem%' ||  st.teacher=us.username and st.academicyear ='$max_year' and us.branch='$branch' and mname LIKE '%$searchItem%' GROUP BY st.teacher ORDER BY st.teacher ASC");

		$output='';
		if($query->num_rows()>0){
			$output='<div class="row">';
			foreach ($query->result() as $staffplacements) {
				$output.='<div class ="col-lg-3 col-6">
				<ul class="list-unstyled list-unstyled-border list-unstyled-noborder delete_hrplacement'.$staffplacements->teacher.'">
                      <li class="media">';
                      	if($staffplacements->profile!=''){
                            $output.='<td><img class="mr-3 rounded-circle" src="'.base_url().'profile/'.$staffplacements->profile.'" width="70"></td>';
                        }else{
                            $output.='<td><img class="mr-3 rounded-circle" src="'.base_url().'profile/defaultProfile.png" width="70"></td>';
                        }
                        $output.='<div class="media-body">
                          <div class="">
                            <div class="text-info">'.$staffplacements->branch.'</div>
                          </div>
                          <div class="media-title mb-1">'.$staffplacements->fname.' '.$staffplacements->mname.'</div>
                          <div class="text-time">'.$staffplacements->date_created.'</div>
                          <div class="media-description text-muted">Grade: '.$staffplacements->gradess.' <a href="#" id="delete_hoomroomplacemet" class="text-danger" value="'.$staffplacements->teacher.'" ><i class="fas fa-trash-alt"></i> </a></div>
                        </div>
                      </li>
                    </ul>
                </div>';
	        } 
         	$output.='</div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function deleteHomeRoomplacement($staff_placement,$max_year){
		$this->db->where(array('teacher'=>$staff_placement));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->delete('hoomroomplacement');
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
	function fetchGradeSection($checkbox,$academicyear,$mybranch){
		$this->db->select('gradesec');
		$this->db->where('grade',$checkbox);
		$this->db->where('academicyear',$academicyear);
		$this->db->where('branch',$mybranch);
		$this->db->group_by('gradesec');
		$queryFetch=$this->db->get('users');
		return $queryFetch->result();
	}
	function add_Directorplacement($staff,$checkbox,$academicyear){
		$this->db->where(array('staff'=>$staff));
		$this->db->where(array('grade'=>$checkbox));
		$this->db->where(array('academicyear'=>$academicyear));
		$query=$this->db->get('directorplacement');
		if($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}
	function fetch_director_placement($max_year){
		$query=$this->db->query("SELECT * from directorplacement as st cross join users as us where st.staff=us.username and st.academicyear ='$max_year'  GROUP BY st.staff ORDER BY st.staff ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="row">';
			foreach ($query->result() as $staffplacements) {
				$staffs=$staffplacements->staff;
				$output.='<div class="col-lg-6 col-12">
					<div class="activities">
						<div class="activity delete_directorplacementRow'.$staffplacements->staff.'">
		                    <div class="activity-icon text-white">';
		                    if($staffplacements->profile!=''){
		                        $output.='<img class="mr-3 rounded-circle" src="'.base_url().'profile/'.$staffplacements->profile.'" width="70">';
		                    }else{
		                        $output.='<img class="mr-3 rounded-circle" src="'.base_url().'profile/defaultProfile.png" width="70">';
		                    }
                    		$output.='</div>
                    		<div class="activity-detail">
		                    	<div class="mb-2">
		                      		<a class="text-job" href="#">'.$staffplacements->fname.' '.$staffplacements->mname.'</a>
		                       		<span class="bullet"></span>
		                        	<span class="text-job text-primary">'.$staffplacements->date_created.'</span>
		                        	<div class="float-right dropdown-menu-right pullDown">
		                          		<a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
		                          		<div class="dropdown-menu">
			                            	<div class="dropdown-title">Action</div>
			                            	<a href="#" id="delete_directorplacement" class="dropdown-item has-icon text-danger" value="'.$staffplacements->staff.'">
			                            	<i class="fas fa-trash-alt"></i> Delete</a>
			                          	</div>
		                        	</div>
                      			</div>';
		                      	$queryGrade=$this->db->query("select * from directorplacement where academicyear='$max_year' and staff='$staffs' ");
								if($queryGrade->num_rows()>0){
									foreach($queryGrade->result() as $grades){
										$gradess=$grades->grade;
										$output.='<span class="badge badge-pill">'.$gradess.'</span>';
									}
								}
                    		$output.='</div>
                  		</div>
              		</div>
              	</div>';
	        } 
		    $output.='</div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
            	<div class="alert-body">
                	<button class="close"  data-dismiss="alert">
                    	<span>&times;</span>
                	</button>
            		<i class="fas fa-exclamation-circle"> </i> No director placement found.
            	</div>
            </div>';
		}
		return $output;
	}
	function fetch_staff_placement($max_year){
		$query=$this->db->query("SELECT st.staff,st.date_created, st.subject,st.grade,us.profile,us.fname,us.mname,us.lname from staffplacement as st cross join users as us where st.staff=us.username and st.academicyear ='$max_year' GROUP by st.staff  ORDER BY us.fname,us.mname,us.lname ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="row">';
			foreach ($query->result() as $staffplacements) {
				$staffs=$staffplacements->staff;
				$queryCount=$this->db->query("SELECT * from staffplacement where staff='$staffs' and academicyear ='$max_year' ");
				$countRow=$queryCount->num_rows() + 1;

				$output.='<div class="col-lg-6 col-12">
					<div class="activities">
						<div class="activity delete_directorplacementRow'.$staffplacements->staff.'">
		                    <div class="activity-icon text-white">';
		                    if($staffplacements->profile!=''){
		                        $output.='<img class="mr-3 rounded-circle" src="'.base_url().'profile/'.$staffplacements->profile.'" width="70">';
		                    }else{
		                        $output.='<img class="mr-3 rounded-circle" src="'.base_url().'profile/defaultProfile.png" width="70">';
		                    }
                    		$output.='</div>
                    		<div class="activity-detail">
		                    	<div class="mb-2">
		                      		<a class="text-job" href="#">'.$staffplacements->fname.' '.$staffplacements->mname.'</a>
		                       		<span class="bullet"></span>
		                        	<span class="text-job text-primary">'.$staffplacements->date_created.'</span>
		                        	<div class="float-right dropdown-menu-right pullDown">
		                          		<a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
		                          		<div class="dropdown-menu">
			                            	<div class="dropdown-title">Action</div>
			                            	<a href="#" id="delete_staffAllplacemet" class="dropdown-item has-icon text-danger" name="" value="'.$staffplacements->staff.'" ><i class="fas fa-trash-alt"></i>Delete </a>
			                          	</div>
		                        	</div>
                      			</div>';
		                      	$queryGrade=$this->db->query("select * from staffplacement where academicyear='$max_year' and staff='$staffs' ");
								if($queryGrade->num_rows()>0){
									foreach($queryGrade->result() as $grades){
										$gradess=$grades->grade;
										$output.='<span class="badge badge-light StudentViewTextInfo">'.$gradess.' ('.$grades->subject.')
										<a href="#" id="delete_staffplacemet" class="'.$grades->staff.'" name="'.$grades->grade.'" value="'.$grades->subject.'" ><span class="text-danger"><i class="fas fa-trash-alt"></i></span> </a></span>';
									}
								}
                    		$output.='</div>
                  		</div>
              		</div>
              	</div>';	
	        }
	        $output.='</div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
            	<div class="alert-body">
                	<button class="close"  data-dismiss="alert">
                    	<span>&times;</span>
                	</button>
            		<i class="fas fa-exclamation-circle"> </i> No record found.
            	</div>
            </div>';
		}
	    
		return $output;
	}
	function searchTeacherPlacementStaffs($searchItem,$max_year){
		$query=$this->db->query("SELECT st.staff,st.date_created, st.subject,st.grade,us.profile,us.fname,us.mname,us.lname from staffplacement as st cross join users as us ON st.staff=us.username where st.academicyear ='$max_year' and username LIKE '%$searchItem%' || st.academicyear ='$max_year' and fname LIKE '%$searchItem%' || st.academicyear ='$max_year' and mname LIKE '%$searchItem%' GROUP by st.staff  ORDER BY us.fname,us.mname,us.lname ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="row">';
			foreach ($query->result() as $staffplacements) {

				$staffs=$staffplacements->staff;
				$queryCount=$this->db->query("SELECT * from staffplacement where staff='$staffs' and academicyear ='$max_year' ");
				$countRow=$queryCount->num_rows() + 1;

				$output.='<div class="col-lg-6 col-12">
					<div class="activities">
						<div class="activity delete_directorplacementRow'.$staffplacements->staff.'">
		                    <div class="activity-icon text-white">';
		                    if($staffplacements->profile!=''){
		                        $output.='<img class="mr-3 rounded-circle" src="'.base_url().'profile/'.$staffplacements->profile.'" width="70">';
		                    }else{
		                        $output.='<img class="mr-3 rounded-circle" src="'.base_url().'profile/defaultProfile.png" width="70">';
		                    }
                    		$output.='</div>
                    		<div class="activity-detail">
		                    	<div class="mb-2">
		                      		<a class="text-job" href="#">'.$staffplacements->fname.' '.$staffplacements->mname.'</a>
		                       		<span class="bullet"></span>
		                        	<span class="text-job text-primary">'.$staffplacements->date_created.'</span>
		                        	<div class="float-right dropdown-menu-right pullDown">
		                          		<a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
		                          		<div class="dropdown-menu">
			                            	<div class="dropdown-title">Action</div>
			                            	<a href="#" id="delete_staffAllplacemet" class="dropdown-item has-icon text-danger" name="" value="'.$staffplacements->staff.'" ><i class="fas fa-trash-alt"></i>Delete </a>
			                          	</div>
		                        	</div>
                      			</div>';
		                      	$queryGrade=$this->db->query("select * from staffplacement where academicyear='$max_year' and staff='$staffs' ");
								if($queryGrade->num_rows()>0){
									foreach($queryGrade->result() as $grades){
										$gradess=$grades->grade;
										$output.='<span class="badge badge-light StudentViewTextInfo">'.$gradess.' ('.$grades->subject.')
										<a href="#" id="delete_staffplacemet" class="'.$grades->staff.'" name="'.$grades->grade.'" value="'.$grades->subject.'" ><span class="text-danger"><i class="fas fa-trash-alt"></i></span> </a></span>';
									}
								}
                    		$output.='</div>
                  		</div>
              		</div>
              	</div>';
	        } 
         	$output.='</div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function searchTeacherPlacementStaffsAdmin($searchItem,$max_year,$branch){
		$query=$this->db->query("SELECT st.staff,st.date_created, st.subject,st.grade,us.profile,us.fname,us.mname,us.lname from staffplacement as st cross join users as us ON st.staff=us.username where st.academicyear ='$max_year' and us.branch='$branch' and username LIKE '%$searchItem%' || st.academicyear ='$max_year' and us.branch='$branch' and fname LIKE '%$searchItem%' || st.academicyear ='$max_year' and us.branch='$branch' and mname LIKE '%$searchItem%' GROUP by st.staff  ORDER BY us.fname,us.mname,us.lname ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="row">';
			foreach ($query->result() as $staffplacements) {

				$staffs=$staffplacements->staff;
				$queryCount=$this->db->query("SELECT * from staffplacement where staff='$staffs' and academicyear ='$max_year' ");
				$countRow=$queryCount->num_rows() + 1;

				$output.='<div class="col-lg-6 col-12">
					<div class="activities">
						<div class="activity delete_directorplacementRow'.$staffplacements->staff.'">
		                    <div class="activity-icon text-white">';
		                    if($staffplacements->profile!=''){
		                        $output.='<img class="mr-3 rounded-circle" src="'.base_url().'profile/'.$staffplacements->profile.'" width="70">';
		                    }else{
		                        $output.='<img class="mr-3 rounded-circle" src="'.base_url().'profile/defaultProfile.png" width="70">';
		                    }
                    		$output.='</div>
                    		<div class="activity-detail">
		                    	<div class="mb-2">
		                      		<a class="text-job" href="#">'.$staffplacements->fname.' '.$staffplacements->mname.'</a>
		                       		<span class="bullet"></span>
		                        	<span class="text-job text-primary">'.$staffplacements->date_created.'</span>
		                        	<div class="float-right dropdown-menu-right pullDown">
		                          		<a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
		                          		<div class="dropdown-menu">
			                            	<div class="dropdown-title">Action</div>
			                            	<a href="#" id="delete_staffAllplacemet" class="dropdown-item has-icon text-danger" name="" value="'.$staffplacements->staff.'" ><i class="fas fa-trash-alt"></i>Delete </a>
			                          	</div>
		                        	</div>
                      			</div>';
		                      	$queryGrade=$this->db->query("select * from staffplacement where academicyear='$max_year' and staff='$staffs' ");
								if($queryGrade->num_rows()>0){
									foreach($queryGrade->result() as $grades){
										$gradess=$grades->grade;
										$output.='<span class="badge badge-light StudentViewTextInfo">'.$gradess.' ('.$grades->subject.')
										<a href="#" id="delete_staffplacemet" class="'.$grades->staff.'" name="'.$grades->grade.'" value="'.$grades->subject.'" ><span class="text-danger"><i class="fas fa-trash-alt"></i></span> </a></span>';
									}
								}
                    		$output.='</div>
                  		</div>
              		</div>
              	</div>';
	        } 
         	$output.='</div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetch_mystaff_placement($max_year,$branch){
		$query=$this->db->query("SELECT st.staff,st.date_created, st.subject,st.grade,us.profile,us.fname,us.mname,us.lname from staffplacement as st cross join users as us where st.staff=us.username and st.academicyear ='$max_year' and us.branch='$branch' GROUP by st.staff  ORDER BY us.fname,us.mname,us.lname ASC");
		$output='';
		if($query->num_rows()>0){

			$output='<div class="row">';
			foreach ($query->result() as $staffplacements) {
				$staffs=$staffplacements->staff;
				$queryCount=$this->db->query("SELECT * from staffplacement where staff='$staffs' and academicyear ='$max_year' ");
				$countRow=$queryCount->num_rows() + 1;

				$output.='<div class="col-lg-6 col-12">
					<div class="activities">
						<div class="activity delete_directorplacementRow'.$staffplacements->staff.'">
		                    <div class="activity-icon text-white">';
		                    if($staffplacements->profile!=''){
		                        $output.='<img class="mr-3 rounded-circle" src="'.base_url().'profile/'.$staffplacements->profile.'" width="70">';
		                    }else{
		                        $output.='<img class="mr-3 rounded-circle" src="'.base_url().'profile/defaultProfile.png" width="70">';
		                    }
                    		$output.='</div>
                    		<div class="activity-detail">
		                    	<div class="mb-2">
		                      		<a class="text-job" href="#">'.$staffplacements->fname.' '.$staffplacements->mname.'</a>
		                       		<span class="bullet"></span>
		                        	<span class="text-job text-primary">'.$staffplacements->date_created.'</span>
		                        	<div class="float-right dropdown-menu-right pullDown">
		                          		<a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
		                          		<div class="dropdown-menu">
			                            	<div class="dropdown-title">Action</div>
			                            	<a href="#" id="delete_staffAllplacemet" class="dropdown-item has-icon text-danger" name="" value="'.$staffplacements->staff.'" ><i class="fas fa-trash-alt"></i>Delete </a>
			                          	</div>
		                        	</div>
                      			</div>';
		                      	$queryGrade=$this->db->query("select * from staffplacement where academicyear='$max_year' and staff='$staffs' ");
								if($queryGrade->num_rows()>0){
									foreach($queryGrade->result() as $grades){
										$gradess=$grades->grade;
										$output.='<span class="badge badge-light StudentViewTextInfo">'.$gradess.' ('.$grades->subject.')
										<a href="#" id="delete_staffplacemet" class="'.$grades->staff.'" name="'.$grades->grade.'" value="'.$grades->subject.'" ><span class="text-danger"><i class="fas fa-trash-alt"></i></span> </a></span>';
									}
								}
                    		$output.='</div>
                  		</div>
              		</div>
              	</div>';	
	        }
	        $output.='</div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
            	<div class="alert-body">
                	<button class="close"  data-dismiss="alert">
                    	<span>&times;</span>
                	</button>
            		<i class="fas fa-exclamation-circle"> </i> No record found.
            	</div>
            </div>';
		}
	    
		return $output;
	}
	function delete_Directorplacement($id){
		$this->db->where(array('staff'=>$id));
		$this->db->delete('directorplacement');
	}
	function delete_placement($staffGrade,$staffSubject,$staffName){
		$this->db->where(array('grade'=>$staffGrade));
		$this->db->where(array('subject'=>$staffSubject));
		$this->db->where(array('staff'=>$staffName));
		$this->db->delete('staffplacement');
	}
	function Delete_staffAllplacement($staffName,$max_year){
		$this->db->where(array('staff'=>$staffName));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->delete('staffplacement');
	}
	function fetch_new_staffs($max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->where('isapproved','0');
		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output .='<div class="table-responsive">
            <table class="table table-border table-hover" id="tableExport" style="width:100%;">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>UserType</th>
                    <th>Name</th>
                    <th>Profile</th>
                    <th>Branch</th>
                    <th>Gender</th>
                    <th>Mobile</th>
                    <th>Grade</th>
                    <th>Registration Date</th>
                  </tr>
                </thead>
                <tbody> ';
            	$no=1;
				foreach ($query->result() as $new_staff) {
					$id=$new_staff->id;
                    $output .='<tr class="delete_mem'.$new_staff->id.'">
                        <td>'.$no.'.</td>
                        <td>'.$new_staff->usertype.'
                            <div class="table-links"> 
                            <a href="#" class="accept text-success" id="'.$new_staff->id .'">Accept</a>
                            <div class="bullet"></div> 
                            <a href="#" class="decline text-danger" id="'.$new_staff->id.'"> Decline</a>
                            </div>
                        </td>
                        <td>'.$new_staff->fname.' '.$new_staff->mname.'</td>';
                        if($new_staff->profile!=''){
                            $output.='<td><img src="'.base_url().'profile/'.$new_staff->profile.'" style="width:30px;height:30px;boreder-radius:3em;"></td>';
                        }else{
                            $output.='<td><img src="'.base_url().'profile/defaultProfile.png" style="width:30px;height:30px;boreder-radius:3em;"></td>';
                        }
                        $output.='<td>'.$new_staff->branch.'</td>
                        <td>'.$new_staff->gender.'</td>
                        <td>'.$new_staff->mobile.'</td>
                        <td>'.$new_staff->grade.'</td>
                        <td>'.$new_staff->dateregister.' </td>                          
                    </tr> ';
			    $no++;
			}
			$output .='</tbody> </table> </div>';

		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
            	<div class="alert-body">
                	<button class="close"  data-dismiss="alert">
                    	<span>&times;</span>
                	</button>
            		<i class="fas fa-exclamation-circle"> </i> No new staffs found.
            	</div>
            </div>';
		}
		return $output;
	}
	function fetch_branchnew_staffs($max_year,$branch){
		$this->db->where('branch',$branch);
		$this->db->where('academicyear',$max_year);
		$this->db->where('isapproved','0');
		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output .='<div class="table-responsive">
            <table class="table table-border table-hover" id="tableExport" style="width:100%;">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>UserType</th>
                    <th>Name</th>
                    <th>Profile</th>
                    <th>Branch</th>
                    <th>Gender</th>
                    <th>Mobile</th>
                    <th>Grade</th>
                    <th>Registration Date</th>
                  </tr>
                </thead>
                <tbody> ';
            	$no=1;
				foreach ($query->result() as $new_staff) {
					$id=$new_staff->id;
                    $output .='<tr class="delete_mem'.$new_staff->id.'">
                        <td>'.$no.'.</td>
                        <td>'.$new_staff->usertype.'
                            <div class="table-links"> 
                            <a href="#" class="accept text-success" id="'.$new_staff->id .'">Accept</a>
                            <div class="bullet"></div> 
                            <a href="#" class="decline text-danger" id="'.$new_staff->id.'"> Decline</a>
                            </div>
                        </td>
                        <td>'.$new_staff->fname.' '.$new_staff->mname.'</td>';
                        if($new_staff->profile!==''){
                            $output.='<td><img src="'.base_url().'/profile/'.$new_staff->profile.'" style="width:30px;height:30px;boreder-radius:3em;"></td>';
                        }else{
                            $output.='<td><img src="'.base_url().'profile/defaultProfile.png" style="width:30px;height:30px;boreder-radius:3em;"></td>';
                        }
                        $output.='<td>'.$new_staff->branch.'</td>
                        <td>'.$new_staff->gender.'</td>
                        <td>'.$new_staff->mobile.'</td>
                        <td>'.$new_staff->grade.'</td>
                        <td>'.$new_staff->dateregister.' </td>                          
                    </tr> ';
			    $no++;
			}
			$output .='</tbody> </table> </div>';

		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
            	<div class="alert-body">
                	<button class="close"  data-dismiss="alert">
                    	<span>&times;</span>
                	</button>
            		<i class="fas fa-exclamation-circle"> </i> No new staffs found.
            	</div>
            </div>';
		}
		return $output;
	}
	function accept_staffs($id){
		$this->db->where(array('id'=>$id));
		$this->db->set('isapproved','1');
		$this->db->update('users');
	}
	function decline_staffs($id){
		$this->db->where(array('id'=>$id));
		$this->db->set('isapproved','1');
		$this->db->delete('users');
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
	function fetch_grades($max_year){
		$this->db->group_by('grade');
		$this->db->order_by('fname','ASC');
		$this->db->where('grade!=','');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->like('usertype','Student');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetch_mygradesec($max_year,$branch){
		$this->db->group_by('gradesec');
		$this->db->order_by('gradesec','ASC');
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->like('usertype','Student');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetch_mygradesec2($user,$max_year,$branch){
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

	function fetcHrGradesec($max_year,$user,$branch){
		$this->db->group_by('roomgrade');
		$this->db->order_by('roomgrade','ASC');
		$this->db->where(array('teacher'=>$user));
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->get('hoomroomplacement');
		return $query->result();
	}
	function my_subject($max_year,$grade){
		$this->db->order_by('Subj_name','ASC');
		$this->db->where(array('Academic_Year'=>$max_year));
		$this->db->where(array('Grade'=>$grade));
		$query=$this->db->get('subject');
		return $query->result();
	}
	function fetch_mygradesec_student($gradesec,$max_year,$branch){
		$this->db->where('branch',$branch);
		$this->db->where('gradesec',$gradesec);
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->order_by('fname','ASC');
		$query = $this->db->get('users');
		$output ='';
        $output .='<form method="POST" 
        	action="'.base_url().'attendance/">
        	       <div class="row">
                      <div class="col-lg-4">
                        <div class="form-group">
                          <label for="Mobile">Date</label>
                            <input class="form-control datepicker" 
                            name="datet" id="id" required="required" type="date" placeholder="Absent Date">
                         </div>
                       </div>
                     <div class="col-lg-4">
                         <div class="form-group">
                          <label for="Mobile">Late(Minute) for Late students
                          </label>
                            <input class="form-control" id="late" name="minute" type="number" placeholder="Late in Min">
                          </div>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                        <thead>
                          <tr>
                           <th>
                            <button class="btn btn-danger" type="submit" name="absent">Absent</button>
                           </th>
                            <th><button class="btn btn-warning" type="submit" name="late">Late</button></th>
                            <th><button class="btn btn-info" type="submit" name="permission">Permission</button></th>
                            <th>Student Name</th>
                            <th>Grade</th>
                            <th>Gender</th>
                          </tr>
                        </thead>
                        <tbody>';
        foreach ($query->result() as $row) {
        	            $output .='<tr>
                          <td><input type="checkbox" name="absentid[ ]" value="'.$row->id.'"></td>
                          <td><input type="checkbox" name="lateid[ ]" value="'.$row->id.'"></td>
                          <td><input type="checkbox" name="permissionid[ ]" value="'.$row->id.'"></td>
                          <td>'.$row->fname.'&nbsp'.$row->mname.'</td>
                          <td>'.$row->gradesec.'</td>
                          <td>'.$row->gender.'</td>
                          </tr>';
        }
        '</tbody>
        </form>
        </table>
        </div>';
        return $output;
	}
	function filterGradesecForTeachers($gradesec,$max_year,$branch,$newDateEnd){
		$query=$this->db->query("select fname,mname,lname,username from users where branch='$branch' and gradesec='$gradesec' and academicyear='$max_year' and username not in(select stuid from attendance where academicyear='$max_year' and absentdate='$newDateEnd' ) order by fname,mname,lname ASC ");
		/*$this->db->where('branch',$branch);
		$this->db->where('gradesec',$gradesec);
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->order_by('fname,mname,lname','ASC');
		$query = $this->db->get('users');*/
		$output ='';
		if($query->num_rows()>0){
	        $output .='
	            <div class="">
	                <table class="table table-borderedr table-hover" style="width:100%;">
	                    <thead>
	                        <tr>
	                        	<th>No.</th>
	                           <th>Student Name</th>
	                           <th>
	                            <a class="" href="#" name="absent">Absent</a>
	                           </th>
	                            <th><a href="#" class="" name="late">Late</a> </th>
	                            <th><a href="#" class="" name="permission"> Permission</a></th>
	                        </tr>
	                    </thead>
	                    <tbody>';
	                    	$no=1;
	        				foreach ($query->result() as $row) {
	        	              	$output .='<tr>
	        	              	<td>'.$no.'.</td>
	        	              	<td>'.$row->fname.' '.$row->mname.' '.$row->lname.' </td>
	                          	<td><input type="checkbox" name="absenStuIdSave" id="absenStuId" value="'.$row->username.'"></td>
	                          	<td><input type="checkbox" name="lateStuIdSave" id="lateStuId" value="'.$row->username.'"></td>
	                          	<td><input type="checkbox" name="permissionStuIdSave" id="perStuId" value="'.$row->username.'"></td>
	                          	</tr>';
	                          	$no++;
	        				}
	        			'</tbody>
	        		</table>
	        	</div>';
    	}else{
    		$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No student found.
            </div></div>';
    	}
        return $output;
	}
	function feedAbsentAttendance($attendanceId,$absentDate,$abseType,$max_year,$user){
		$this->db->where('stuid',$attendanceId);
		$this->db->where(array('absentdate'=>$absentDate));
		$this->db->where(array('academicyear'=>$max_year));
		$query = $this->db->get('attendance');
		$output='';
		if($query->num_rows()>0){
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i>
            </div></div>';
		}else{
			$data=array(
				'stuid'=>$attendanceId,
				'absentdate'=>$absentDate,
				'absentype'=>$abseType,
				'academicyear'=>$max_year,
				'attend_by'=>$user
			);
			$this->db->insert('attendance',$data);
		}
		return $output;
	}
	function feedLateAttendance($attendanceId,$absentDate,$abseType,$max_year,$user,$teaStuAbsentMin){
		$this->db->where('stuid',$attendanceId);
		$this->db->where(array('absentdate'=>$absentDate));
		$this->db->where(array('academicyear'=>$max_year));
		$query = $this->db->get('attendance');
		$output='';
		if($query->num_rows()>0){
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i>
            </div></div>';
		}else{
			$data=array(
				'stuid'=>$attendanceId,
				'absentdate'=>$absentDate,
				'absentype'=>$abseType,
				'academicyear'=>$max_year,
				'attend_by'=>$user,
				'latemin'=>$teaStuAbsentMin
			);
			$this->db->insert('attendance',$data);
		}
		return $output;
	}
	function feedPermissionAttendance($attendanceId,$absentDate,$abseType,$max_year,$user){
		$this->db->where('stuid',$attendanceId);
		$this->db->where(array('absentdate'=>$absentDate));
		$this->db->where(array('academicyear'=>$max_year));
		$query = $this->db->get('attendance');
		$output='';
		if($query->num_rows()>0){
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i>
            </div></div>';
		}else{
			$data=array(
				'stuid'=>$attendanceId,
				'absentdate'=>$absentDate,
				'absentype'=>$abseType,
				'academicyear'=>$max_year,
				'attend_by'=>$user
			);
			$this->db->insert('attendance',$data);
		}
		return $output;
	}
	function fetchStudentsAttendanceFormat($gradesec,$attBranches,$max_year){
		$this->db->where('gradesec',$gradesec);
		$this->db->where('branch',$attBranches);
		$this->db->where('isapproved','1');
		$this->db->where('status','Active');
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->order_by('fname ,mname,lname','ASC');
		$query = $this->db->get('users');
		$output ='';
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
        $output .='<p class="text-center"><u><b>'.$school_name.' </b>('.$attBranches.' Branch)</u></p>
        <div class ="row">
        	<div class ="col-lg-4 col-12"><p class="text-center"><u><b>Students Attendance in '.$max_year.' Academic Year </b></u></p> </div>
        	<div class ="col-lg-4 col-6"><p class="text-center"><b>Grade:<u>'.$gradesec.'</u></b></p></div>
        	<div class ="col-lg-4 col-6"><p class="text-center"><b> Month </b>_________</p></div>
        </div>';
        $output .='<div class="row">
            <div class="col-lg-3"> </div>
           	<div class="col-lg-3"> </div>
         	<div class="col-lg-3"> </div>
            <div class="col-lg-3"> </div>
        </div>
        <div class="table-responsive">
            <table class="tabler table-borderedr table-hover" cellspacing="9" cellpadding="9" style="width:100%;">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Student Name</th>
                    <th>Gender</th>
                    <th>Age</th>';
                    for ($i=1; $i <=26 ; $i++) { 
                    	$output.='<th></th>';
                    }
                $output.='</tr>
            </thead>
        <tbody>';
        $no=1;
        foreach ($query->result() as $row) {
	        $output .='<tr>
	        <td>'.$no.'. </td>
	        <td>'.$row->fname.' '.$row->mname.' '.$row->lname.'</td>
	        <td>'.$row->gender.'</td> 
	        <td>'.$row->age.'</td>';
	        for ($i=1; $i <=26 ; $i++) { 
                $output.='<td> </td>';
            }
	        $output.='</tr>';
	        $no++;
        }
        $output.='<tr><td colspan="4" class="text-center">Key</td><td colspan="8"><span class="text-success"> U= Present</span></td><td colspan="9"><span class="text-danger"> A=Absent</span></td><td colspan="9"><span class="text-warning"> P=Permission</span></td></tr>';
        $output.='</tbody> </table> </div>';
        return $output;
	}
	function fetch_gradesec_student($gradesec,$attBranches,$max_year){
		$this->db->where('gradesec',$gradesec);
		$this->db->where('branch',$attBranches);
		$this->db->where('isapproved','1');
		$this->db->where('status','Active');
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->order_by('fname ,mname,lname','ASC');
		$query = $this->db->get('users');
		$output ='';
        $output .='<div class="row">
            <div class="col-lg-3">
                <label for="Mobile">Date</label>
                <input class="form-control datepicker" name="datet" id="attendanceDate" required="required" type="date" 
                    placeholder="Absent Date">
            </div>
           	<div class="col-lg-3">
              <label for="Mobile">Attendance type
              </label>
                <select class="form-control selectric" disabled="disabled" 
                required="required" name="attendanceType" id="attendanceType">
                 <option></option>
                <option id="Absent">Absent</option>
                <option id="Late">Late</option>
                <option id="Permission">Permission</option>
                </select>
            </div>
         	<div class="col-lg-3">
              <label for="Mobile">Late(Minute) for Late students
              </label>
                <input class="form-control" disabled="disabled" id="attendanceMinute" name="attendanceMinute" type="number" placeholder="Late in Min">
            </div>
            <div class="col-lg-3">
              <label for="Mobile">Click submit after selection done
              </label>
                <button class="btn btn-success pull-right" id="saveAttendance" name="saveAttendance" type="submit"> Submit </button>
            </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="table-responsive">
            <table class="table table-striped table-hover" style="width:100%;">
            <thead>
                <tr>
                    <th>Select Student</th>
                    <th>Student Name</th>
                    <th>Student ID</th>
                    <th>Grade</th>
                    <th>Gender</th>
                </tr>
            </thead>
        <tbody>';
        $no=1;
        foreach ($query->result() as $row) {
	        $output .='<tr>
	        <td>'.$no.'. <input disabled="disabled" type="checkbox" name="attendanceStuid" id="attendanceStuid" value="'.$row->username.'"></td>
	        <td>'.$row->fname.' '.$row->mname.' '.$row->lname.'</td>
	        <td>'.$row->username.'</td>
	        <td>'.$row->gradesec.'</td>
	        <td>'.$row->gender.'</td> </tr>';
	        $no++;
        }
        $output.='</tbody> </table> </div>';
        return $output;
	}
	function fetchCustomStudentsAttendance($check,$attBranches,$customToDate,$customFromDate,$max_year){
		
		$output ='';
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
        $output .='<p class="text-center"><u><b>'.$school_name.'</b></u></p>
        <div class ="row">
        	<div class ="col-lg-6 col-12"><p class="text-center"><u><b>Students Attendance in '.$max_year.' Academic Year </b></u></p> </div>
        	<div class ="col-lg-6 col-12"><p class="text-center"><u><b> Date '.$customFromDate.' To '.$customToDate.'</b></u></p></div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover" style="width:100%;">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Student Name</th>
                    <th>Student ID</th>
                    <th>Grade</th>
                    <th>Gender</th>
                    <th>Absent Type</th>
                    <th>Absent Date</th>
                </tr>
            </thead>
        <tbody>';
        $no=1;
        foreach($check as $gradesec){
	        $queryAbsent=$this->db->query("select absentdate, aid,stuid, fname,mname,lname, gradesec, username, gender,absentype from attendance as abs cross join users as us   where us.grade='$gradesec' and us.branch='$attBranches' and us.isapproved='1' and us.status='Active' and us.academicyear='$max_year' and abs.stuid=us.username and abs.absentype='Absent' and abs.absentdate  between '$customFromDate' and '$customToDate' group by absentdate,stuid order by us.fname,us.mname,us.lname ASC ");
	        if($queryAbsent->num_rows()>0){
	        	foreach($queryAbsent->result() as $row){
			        $output .='<tr <tr class="deleteAttendane'.$row->aid.'">
			        <td>'.$no.'. </td>
			        <td>'.$row->fname.' '.$row->mname.' '.$row->lname.'</td>
			        <td>'.$row->username.'</td>
			        <td>'.$row->gradesec.'</td>
			        <td>'.$row->gender.'</td>
			        <td>'.$row->absentype.'
			         <div class="table-links">
                        <a href="#" class="deleteThisAttendane text-danger" id="'.$row->aid.'">Delete</a>
                    </div>
			        </td>
			        <td><span class="badge badge-danger">'.$row->absentdate.'</span></td> </tr>';
			        $no++;
		        }
	        }
	        
	    }
        $output.='</tbody> </table> </div>';
        return $output;
	}
	function fetch_grade_4placement($gradesec,$into,$max_year,$branch){
		$this->db->where('usertype =','Student');
		$this->db->where('grade',$gradesec);
		$this->db->where('branch',$branch);
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->order_by('fname','ASC');
		$query = $this->db->get('users');
		$output ='';
    $output .='
      <div class="table-responsive">
        <table class="table table-striped table-hover" style="width:100%;">
        <thead>
        <tr>
          <th>Student Name</th>
          <th>ID</th>
          <th>Grade</th>
          <th>Branch</th>
          <th>Gender</th>';
         $query_num_grade = $this->db->query("select fname,mname,lname,grade,branch,gender,id,username from users where grade='$gradesec' and academicyear ='$max_year' and usertype='Student' group by section order by fname,mname,lname ASC");
         $num_branch= $query_num_grade->num_rows();
         $gsnum=$query_num_grade->result() ;
         $i=range('A','Z');
         for($j=0;$j<$into; $j++) {
           $output .='<th> '.$i[$j].'</th>';
          }
         $output .='</tr>
        </thead>
       <tbody>';
       foreach ($query->result() as $row) {
       $output .='<tr> 
       <td>'.$row->fname.' '.$row->mname.' '.$row->lname.'</td>
       <td>'.$row->username.'</td>
       <td>'.$row->grade.'</td>
       <td>'.$row->branch.'</td>
       <td>'.$row->gender.'</td>';
        for($j=0;$j<$into; $j++) {
        	if($row->section == $i[$j]){
        		$output .='
        		<input type="hidden" class="grades" value="'.$row->grade.'">
           		<td> <input type="radio" name="manualPlacementRadio'.$row->id.'" class="placesiec" id="'.$row->id.'" checked="checked" value="'.$i[$j].'">
           			<a class="saved'.$row->id.''.$i[$j].'" ></a>
           		</td>';
        	}else{
           $output .='
           <input type="hidden" class="grades" value="'.$row->grade.'">
           <td><input type="radio" name="manualPlacementRadio'.$row->id.'" class="placesiec" id="'.$row->id.'" value="'.$i[$j].'">
           <a class="saved'.$row->id.''.$i[$j].'" ></a>
           </td>';
          }
        }
       }
      $output .='</tr></tbody>
     </table>
    </div>';
   return $output;
	}
	function check_placement_found($branch2place,$grade2place,$max_year){
		$this->db->where('usertype =','Student');
		$this->db->where('grade',$grade2place);
		$this->db->where('branch',$branch2place);
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where('section!=','');
		$query = $this->db->get('users');
		$output ='';
		if($query->num_rows()>0){
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i> Placement Found for Grade '.$grade2place.'.
            </div></div>';
		}else{
			$output .='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No placement Found for Grade '.$grade2place.'.
            </div></div>';
		}
		return $output;
	}
	function fetch_grade_4autoplacement($branch2place,$gradesec,$into,$max_year)
	{
		$query = $this->db->query("SELECT * FROM users where usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' ORDER BY fname ASC"); 
		$studentcount=$query->num_rows();
		$output ='';
		if($studentcount>=$into){
			$queryMale = $this->db->query("SELECT * FROM users where usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' and gender='Male' or usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' and gender='M' or usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' and gender='male'  ORDER BY mname,lname,fname ASC"); 
      		$output .='
      		<div class="table-responsive">
        	<table class="table table-bordered table-hover" id="tableExport" style="width:100%;">
        	<thead> <tr>
        	<th>No.</th>
          	<th>Student Name</th>
          	<th>Grade</th>
          	<th>Branch</th>';
         	$i=range('A','Z');
          	for($j=0;$j<$into; $j++){
          		$output .='<th>'.$i[$j].'</th>';
         	}
         	$output .='</tr></thead>
       		<tbody>';
      		$start=1;
      		$end=1;
      		$j=0;
      		foreach ($queryMale->result() as $kevalue) {
      			$id=$kevalue->id;
       			$this->db->where('usertype =','Student');
			    $this->db->where('grade',$gradesec);
			    $this->db->where('gender=','Male');
			    $this->db->where('branch',$branch2place);
			    $this->db->where('id',$id);
				$this->db->where('academicyear',$max_year);
				$this->db->or_where('gender=','M');
				$this->db->where('usertype =','Student');
			    $this->db->where('grade',$gradesec);
			    $this->db->where('branch',$branch2place);
			    $this->db->where('id',$id);
				$this->db->where('academicyear',$max_year);
				$this->db->set('section',$i[$j]);
				$this->db->set('gradesec',$gradesec.$i[$j]);
				$this->db->update('users');
				if($j<$into-1){
					$j++;
				}else{
					$j=0;
				}
      		}
      		$queryFemale = $this->db->query("SELECT * FROM users where usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' and gender='Female' or usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' and gender='F' or usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' and gender='female' ORDER BY mname,lname,fname ASC"); 
      		$start=1;
      		$end=1;
      		$j=0;
      		foreach ($queryFemale->result() as $kevalue) {
      			$id=$kevalue->id;
       			$this->db->where('usertype =','Student');
			    $this->db->where('grade',$gradesec);
			    $this->db->where('gender=','Female');
			    $this->db->where('branch',$branch2place);
			    $this->db->where('id',$id);
				$this->db->where('academicyear',$max_year);
				$this->db->or_where('gender=','F');
				$this->db->where('usertype =','Student');
			    $this->db->where('grade',$gradesec);
			    $this->db->where('branch',$branch2place);
			    $this->db->where('id',$id);
				$this->db->where('academicyear',$max_year);
				$this->db->set('section',$i[$j]);
				$this->db->set('gradesec',$gradesec.$i[$j]);
				$this->db->update('users');
				if($j<$into-1){
					$j++;
				}else{
					$j=0;
				}
      		}
      		$query_fetch = $this->db->query("SELECT * FROM users where usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' ORDER BY fname,mname,lname ASC");
      		$no=1;
      		foreach ($query_fetch->result() as $row) {
      			$id=$row->id;
        		$output .='<tr> <td>'.$no.'.</td>
        		<td>'.$row->fname.' '.$row->mname.'</td>
        		<td>'.$row->grade.'</td>
        		<td>'.$row->branch.'</td>';
        		for($j=0;$j<$into; $j++) {
        			if($row->section == $i[$j]){
        				$output .=' <td>
            			<div class="pretty p-icon p-smooth">
              				<input type="checkbox" checked="checked">
              				<div class="state p-success">
	                			<i class="icon fa fa-check"></i>
	                			<label></label>
              				</div>
            			</div> </td>';
        			}else{
        				$output .=' <td>
            			<div class="pretty p-icon p-smooth">
              				<input type="checkbox">
              				<div class="state p-success">
	                			<i class="icon fa fa-check"></i>
	                			<label></label>
              				</div>
            			</div> </td>';
        			}
        		}
        		$no++;
      		}
      		$output .='</tr></tbody>
       		</table>
      		</div>';
    	}else{
   			$output .='<div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> Number of Students do not support this Number of Section.
            </div></div>';
   		}
   		return $output;
	}
	function update_student_section($stu_id,$section_id,$grade){
		$query_chk=$this->db->query("Select * from users where section='$section_id' and id='$stu_id' ");
		if($query_chk->num_rows()>0){
			$this->db->set('section','');
			$this->db->set('gradesec','');
			$this->db->where(array('id'=>$stu_id));
			$query=$this->db->update('users');
		}else{
			$this->db->set('section',$section_id);
			$this->db->set('gradesec',$grade.''.$section_id);
			$this->db->where(array('id'=>$stu_id));
			$query=$this->db->update('users');
		}
		if($query){
			return true;
		}else{
			return false;
		}
	}
	function fetch_grade_4branch($gradesec,$max_year){
		$this->db->where('usertype =','Student');
		$this->db->where('grade',$gradesec);
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->order_by('fname,mname,lname','ASC');
		$query = $this->db->get('users');
		$output ='';
       	$output .=' <div class="table-responsive">
        <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
        <thead>
        <tr>
          <th>Student Name</th>
          <th>Student ID</th>
          <th>Grade</th>
          <th>Gender</th>';
        $query_num_branch = $this->db->query("select * from branch group by name");
        $num_branch= $query_num_branch->num_rows();
        $gsnum=$query_num_branch->result() ;
        foreach ($query_num_branch->result() as $rowi) {
           $output .='<th> '.$rowi->name.' </th>';
        }
        $output .='</tr>
        </thead>
        <tbody>';
       	foreach ($query->result() as $row) {
	       $output .='<tr> 
	       <td>'.$row->fname.'&nbsp'.$row->mname.' '.$row->lname.'</td>
	       <td>'.$row->username.'</td>
	       <td>'.$row->gradesec.'</td>
	       <td>'.$row->gender.'</td>';
	        foreach ($query_num_branch->result() as $rowi) {
	        	if($row->branch == $rowi->name){
	        		$output .=' <td>
	        		<input type="radio" name="branchRadio'.$row->id.'" class="placesiec" id="'.$row->id.'" checked="checked" value="'.$rowi->name.'">
	        	   <a class="saved'.$row->id.''.$rowi->name.'" ></a>
	        		</td>';
	        	}else{
		           $output .=' <td>
		            <input type="radio" name="branchRadio'.$row->id.'" class="placesiec" id="'.$row->id.'" value="'.$rowi->name.'"> 
		            <a class="saved'.$row->id.''.$rowi->name.'"></a> 
		           </td>';
		        }
	        }
       	}
        $output .='</tr></tbody>
        </table>
        </div>';
       	return $output;
	}
	function update_student_branch($stu_id,$branchName){
		$query_ck=$this->db->query("select * from users where branch='$branchName' and id='$stu_id' ");
		if($query_ck->num_rows()>0){
			$this->db->set('branch','');
			$this->db->where(array('id'=>$stu_id));
			$query=$this->db->update('users');
		}else{
			$this->db->set('branch',$branchName);
			$this->db->where(array('id'=>$stu_id));
			$query=$this->db->update('users');
		}
		if($query){
			return true;
		}else{
			return false;
		}
	}
	function transferThismark($stu_id,$branchName,$max_year,$previousGradesec,$previousBranch,$quarter){
		$this->db->where('stuid',$stu_id);
		$this->db->group_by('markname');
        $getMark = $this->db->get('mark'.$previousBranch.$previousGradesec.$quarter.$max_year);
        if($getMark->num_rows()>0){
        	return $getMark->result();
		}else{
			return false;
		}
	}
	function insert_absent($id,$date,$max_year,$user){
		$this->db->where(array('stuid'=>$id));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('absentdate'=>$date));
		$query=$this->db->get('attendance');
		if($query->num_rows() > 0){
			return false;
		}else{
			return true;
		}
	}
	function searchAttendance($searchItem,$max_year){
		$this->db->where('attendance.academicyear',$max_year);
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('usertype','Student');
		$this->db->like('users.fname',$searchItem);
		$this->db->or_like('users.mname',$searchItem);
		$this->db->or_like('users.unique_id',$searchItem);
		$this->db->or_like('users.gradesec',$searchItem);
		$this->db->or_like('attendance.absentdate',$searchItem);
		$this->db->order_by('attendance.absentdate','DESC');
		$this->db->select('*');
        $this->db->from('attendance');
        $this->db->join('users', 
            'users.username = attendance.stuid');
        $query = $this->db->get();
        $output='';
        if($query->num_rows()>0){
        	$output.='<div class="table-responsive">
                <table class="table table-striped table-hover" style="width:100%;">
                <thead>
                    <tr>
                    <th>No.</th>
                    <th>Student Name</th>
                    <th>Student ID</th>
                    <th>Grade</th>
                      <th>Absent Type</th>
                      <th>Absent Date</th>
                    </tr>
                </thead>
            <tbody>';
            $no=1;
	        foreach ($query->result() as $fetch_today_attendances) {
	        	$output.='<tr class="deleteAttendane'.$fetch_today_attendances->aid.'">
	        	<td>'.$no.'.</td>
	        	<td> '.$fetch_today_attendances->fname.'
                    '.$fetch_today_attendances->mname.' '.$fetch_today_attendances->lname.'  </td>
                <td>'.$fetch_today_attendances->username.'</td>
                <td>'.$fetch_today_attendances->gradesec.'</td>
                <td>'.$fetch_today_attendances->absentype.' 
                <div class="table-links">
                        <a href="#" class="deleteThisAttendane text-danger" id="'.$fetch_today_attendances->aid.'">Delete</a>
                    </div> 
                </td>
                <td>'.$fetch_today_attendances->absentdate.'</td>
                </tr>';$no++;
	        }
	        $output.='</tbody> </table> </div>';
	    }else{
	    	$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No absent students found.
            </div></div>';
	    }
        return $output;
	}
	function searchAttendanceDirector($searchItem,$myBranch,$max_year){
		$this->db->where('attendance.academicyear',$max_year);
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('users.branch',$myBranch);
		$this->db->where('usertype','Student');
		$this->db->like('users.fname',$searchItem);
		$this->db->or_like('users.mname',$searchItem);
		$this->db->where('attendance.academicyear',$max_year);
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('users.branch',$myBranch);
		$this->db->where('usertype','Student');
		$this->db->or_like('users.unique_id',$searchItem);
		$this->db->where('attendance.academicyear',$max_year);
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('users.branch',$myBranch);
		$this->db->where('usertype','Student');
		$this->db->or_like('users.gradesec',$searchItem);
		$this->db->where('attendance.academicyear',$max_year);
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('users.branch',$myBranch);
		$this->db->where('usertype','Student');
		$this->db->or_like('attendance.absentdate',$searchItem);
		$this->db->where('attendance.academicyear',$max_year);
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('users.branch',$myBranch);
		$this->db->where('usertype','Student');
		$this->db->order_by('attendance.absentdate','DESC');
		$this->db->select('*');
        $this->db->from('attendance');
        $this->db->join('users', 
            'users.username = attendance.stuid');
        $query = $this->db->get();
        $output='';
        if($query->num_rows()>0){
        	$output.='<div class="table-responsive">
                <table class="table table-striped table-hover" style="width:100%;">
                <thead>
                    <tr>
                    <th>No.</th>
                    <th>Student Name</th>
                    <th>Student ID</th>
                    <th>Grade</th>
                      <th>Absent Type</th>
                      <th>Absent Date</th>
                    </tr>
                </thead>
            <tbody>';
            $no=1;
	        foreach ($query->result() as $fetch_today_attendances) {
	        	$output.='<tr class="deleteAttendane'.$fetch_today_attendances->aid.'">
	        	<td>'.$no.'.</td>
	        	<td> '.$fetch_today_attendances->fname.'
                    '.$fetch_today_attendances->mname.' '.$fetch_today_attendances->lname.'  </td>
                <td>'.$fetch_today_attendances->username.'</td>
                <td>'.$fetch_today_attendances->gradesec.'</td>
                <td>'.$fetch_today_attendances->absentype.' 
                <div class="table-links">
                        <a href="#" class="deleteThisAttendane text-danger" id="'.$fetch_today_attendances->aid.'">Delete</a>
                    </div> 
                </td>
                <td>'.$fetch_today_attendances->absentdate.'</td>
                </tr>';$no++;
	        }
	        $output.='</tbody> </table> </div>';
	    }else{
	    	$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No absent students found.
            </div></div>';
	    }
        return $output;
	}
	function fetch_attendance($max_year){
		$this->db->where('usertype','Student');
		$this->db->like('attendance.academicyear',$max_year);
		$this->db->like('users.academicyear',$max_year);
		$this->db->order_by('attendance.absentdate','DESC');
		$this->db->select('*');
        $this->db->from('attendance');
        $this->db->join('users', 
            'users.username = attendance.stuid');
        $query = $this->db->get();
        $output='';
        if($query->num_rows()>0){
        	$output.='<div class="table-responsive">
                <table class="table table-striped table-hover" style="width:100%;">
                <thead>
                    <tr>
                    <th>No.</th>
                    <th>Student Name</th>
                    <th>Student ID</th>
                    <th>Grade</th>
                    <th>Absent Type</th>
                    <th>Absent Date</th>
                    </tr>
                </thead>
            <tbody>';
            $no=1;
	        foreach ($query->result() as $fetch_today_attendances) {
	        	$output.='<tr class="deleteAttendane'.$fetch_today_attendances->aid.'">
	        	<td>'.$no.'.</td>
	        	<td> '.$fetch_today_attendances->fname.'
                    '.$fetch_today_attendances->mname.' '.$fetch_today_attendances->lname.'  </td>
                <td>'.$fetch_today_attendances->username.'</td>
                <td>'.$fetch_today_attendances->gradesec.'</td>';
                if($fetch_today_attendances->approved=='1'){
                	$output.='<td>'. $fetch_today_attendances->absentype.' </td>';
                }else{
                	$output.='<td>'. $fetch_today_attendances->absentype.'<a href="#" class="deleteThisAttendane text-danger" id="'.$fetch_today_attendances->aid.'"><i class="fas fa-trash-alt"></i></a> </td>';
                } 
                $output.='<td>'.$fetch_today_attendances->absentdate.'</td>
                </tr>';$no++;
	        }
	        $output.='</tbody> </table> </div>';
	    }else{
	    	$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No absent students found.
            </div></div>';
	    }
        return $output;
	}
	function fetch_mattendance($max_year,$branch){
		$this->db->where('users.branch',$branch);
		$this->db->where('usertype','Student');
		$this->db->like('attendance.academicyear',$max_year);
		$this->db->like('users.academicyear',$max_year);
		$this->db->order_by('attendance.absentdate','DESC');
		$this->db->select('*');
        $this->db->from('attendance');
        $this->db->join('users', 
            'users.username = attendance.stuid');
        $query = $this->db->get();
        $output='';
        if($query->num_rows()>0){
        	$output.='<div class="table-responsive">
                <table class="table table-striped table-hover" style="width:100%;">
                <thead>
                    <tr>
                    <th>No.</th>
                    <th>Student Name</th>
                     <th>Student ID</th>
                      <th>Grade</th>
                      <th>Absent Type</th>
                      <th>Absent Date</th>
                    </tr>
                </thead>
            <tbody>';
            $no=1;
	        foreach ($query->result() as $fetch_today_attendances) {
	        	$output.='<tr class="deleteAttendane'.$fetch_today_attendances->aid.'">
	        	<td>'.$no.'.</td>
	        	<td>'.$fetch_today_attendances->fname.'
                    '.$fetch_today_attendances->mname.' '.$fetch_today_attendances->lname.'  </td>
                <td>'.$fetch_today_attendances->username.'</td>
                <td>'.$fetch_today_attendances->gradesec.'</td>';
                if($fetch_today_attendances->approved=='1'){
                	$output.='<td>'. $fetch_today_attendances->absentype.' </td>';
                }else{
                	$output.='<td>'. $fetch_today_attendances->absentype.'<a href="#" class="deleteThisAttendane text-danger" id="'.$fetch_today_attendances->aid.'"><i class="fas fa-trash-alt"></i></a> </td>';
                }
                $output.='<td>'.$fetch_today_attendances->absentdate.'</td>
                </tr>';$no++;
	        }
	        $output.='</tbody> </table> </div>';
	    }else{
	    	$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No absent students found.
            </div></div>';
	    }
        return $output;
	}
	function fetch_staffattendance($max_year){
		$this->db->where('usertype !=','Student');
		$this->db->like('attendance.academicyear',$max_year);
		$this->db->order_by('attendance.absentdate','DESC');
		$this->db->select('*');
        $this->db->from('attendance');
        $this->db->join('users', 
            'users.id = attendance.stuid');
        $query = $this->db->get();
        $output='';
		if($query->num_rows()>0){
			$output.='<div class="">
        	<table class="table table-striped table-hover" >
        	<thead>
        	<tr>
	        	<th>No.</th>
                
                <th>Name</th>
                <th>AbsentType</th>
                <th>Absent Date</th>
                <th>Branch</th>
                <th>Total Absents</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$queryCunt=$this->db->query("select count(stuid) as totalAbsents from attendance where stuid ='$staff->stuid' and academicyear='$max_year' ");
       			$row=$queryCunt->row();
       			$cunt=$row->totalAbsents;
       			$id=$staff->aid;
   				$output.='<tr class="delete_staff'.$id.'">
                <td>'.$no.'.</td>
                
                <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                <a href="#" class="deleteAttendance text-danger" id="'.$id.'" value="'.$id.'"><i class="fas fa-trash-alt"></i></a>
                </td>
                <td><span class="text-danger">'.$staff->absentype.'</span></td>
                <td>'.$staff->absentdate.'</td>
                <td>'.$staff->branch.'</td>   
                <td><span class ="badge badge-info">'.$cunt.'</span></td>                      
                </tr>';
                $no++;	
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No absent staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetch_mystaffattendanceAccessBranch($max_year,$mydivision){
		$this->db->where('usertype !=','Student');
		$this->db->where('status2',$mydivision);
		$this->db->like('attendance.academicyear',$max_year);
		$this->db->order_by('attendance.absentdate','DESC');
		$this->db->select('*');
        $this->db->from('attendance');
        $this->db->join('users', 
            'users.id = attendance.stuid');
        $query = $this->db->get();
        $output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>UserName/ID</th>
                <th>AbsentType</th>
                <th>Name</th>
                <th>Absent Date</th>
                <th>Total Absents</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$id=$staff->aid;
       			$queryCunt=$this->db->query("select count(stuid) as totalAbsents from attendance where stuid ='$staff->stuid' and academicyear='$max_year' ");
       			$row=$queryCunt->row();
       			$cunt=$row->totalAbsents;
       			$id=$staff->aid;
   				$output.='<tr class="delete_staff'.$id.'">
                <td>'.$no.'.</td>
                <td>'. $staff->username.'<a href="#" class="deleteAttendance text-danger" value="'.$id.'"><i class="fas fa-times-circle"></i></a>
                </td>
                <td><span class="text-danger">'.$staff->absentype.'</span></td>
                <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                </td>
                <td>'.$staff->absentdate.'</td>
                 <td><span class ="badge badge-info">'.$cunt.'</span></td>                      
                </tr>';
                $no++;	
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No absent staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetch_mystaffattendance($max_year,$branch,$mydivision){
		$this->db->where('branch',$branch);
		$this->db->where('usertype !=','Student');
		$this->db->where('status2',$mydivision);
		$this->db->like('attendance.academicyear',$max_year);
		$this->db->order_by('attendance.absentdate','DESC');
		$this->db->select('*');
        $this->db->from('attendance');
        $this->db->join('users', 
            'users.id = attendance.stuid');
        $query = $this->db->get();
        $output='';
		if($query->num_rows()>0){
			$output.='<div class="">
        	<table class="table table-striped table-hover">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>Name</th>
                <th>AbsentType</th>
                <th>Absent Date</th>
                <th>Total Absents</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$id=$staff->aid;
       			$queryCunt=$this->db->query("select count(stuid) as totalAbsents from attendance where stuid ='$staff->stuid' and academicyear='$max_year' ");
       			$row=$queryCunt->row();
       			$cunt=$row->totalAbsents;
       			$id=$staff->aid;
   				$output.='<tr class="delete_staff'.$id.'">
                <td>'.$no.'.</td>                
                <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                <a href="#" class="deleteAttendance text-danger" value="'.$id.'"><i class="fas fa-trash-alt"></i></a>
                </td>
                <td><span class="text-danger">'.$staff->absentype.'</span></td>
                <td>'.$staff->absentdate.'</td>
                 <td><span class ="badge badge-info">'.$cunt.'</span></td>                      
                </tr>';
                $no++;	
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No absent staffs found yet.
            </div></div>';
		}
		return $output;
	}
	function fetchMyStuAttendanceDirector($max_year,$branch,$user){
		$output='';
		$query=$this->db->query("select grade from directorplacement where staff='$user' and academicyear='$max_year' group by grade ");
		if($query->num_rows() >0){
			foreach ($query->result() as $gValue) {
				$gradesec=$gValue->grade;
				$this->db->where('branch',$branch);
				$this->db->where('usertype =','Student');
				$this->db->where('gradesec',$gradesec);
				$this->db->like('attendance.academicyear',$max_year);
				$this->db->order_by('attendance.absentdate','DESC');
				$this->db->group_by('attendance.stuid');
				$this->db->group_by('attendance.absentdate');
				$this->db->select('*');
        		$this->db->from('attendance');
        		$this->db->join('users', 
            		'users.username = attendance.stuid');
        		$query = $this->db->get();
				if($query->num_rows()>0){
					$output.='<div class="table-responsive">
		        	<table class="table table-striped table-hover" style="width:100%;">
		        	<thead>
		        	<tr>
			        	<th>No.</th>
		                <th>Student ID</th>
		                <th>Student Name</th>
		                <th>Grade</th>
		                <th>AbsentType</th>
		                <th>Absent Date</th>
		                <th>Total Absents</th>
		        	</tr>
		        	</thead>
		       		<tbody>';
		       		$no=1;
		       		foreach ($query->result() as $staff) {
		       			$id=$staff->stuid;
		       			$queryCunt=$this->db->query("select count(stuid) as totalAbsents from attendance where stuid ='$staff->stuid' and academicyear='$max_year' ");
		       			$row=$queryCunt->row();
		       			$cunt=$row->totalAbsents;
		       			$id=$staff->stuid;
		   				$output.='<tr class="delete_staff'.$id.'">
		                <td>'.$no.'.</td>';
		                if($staff->approved=='1'){
		                	$output.='<td>'. $staff->username.' </td>';
		                }else{
		                	$output.='<td>'. $staff->username.'<a href="#" class="deleteStuAttendance text-danger" id="'.$staff->absentdate.'" value="'.$id.'"><i class="fas fa-trash-alt"></i></a> </td>';
		                }
		                
		                $output.='<td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
		                </td>
		                <td>'.$staff->gradesec.'
		                </td>
		                <td><span class="text-danger">'.$staff->absentype.'</span></td>
		                
		                <td>'.$staff->absentdate.'</td>
		                 <td><span class ="badge badge-info">'.$cunt.'</span></td>                      
		                </tr>';
		                $no++;	
		       		}
				}/*else{
					$output .='<div class="alert alert-warning alert-dismissible show fade">
		                <div class="alert-body">
		                    <button class="close"  data-dismiss="alert">
		                        <span>&times;</span>
		                    </button>
		                <i class="fas fa-exclamation-triangle"> </i> No absent students found yet.
		            </div></div>';
				}*/
			}
		}
		return $output;
	}
	function fetchMyStuAttendance($max_year,$branch,$HrGrade){
		$this->db->where('users.branch',$branch);
		$this->db->where('hoomroomplacement.teacher',$HrGrade);
		$this->db->where('usertype =','Student');
		$this->db->like('attendance.academicyear',$max_year);
		$this->db->order_by('attendance.absentdate','DESC');
		$this->db->select('*');
        $this->db->from('attendance');
        $this->db->join('users', 
            'users.username = attendance.stuid');
        $this->db->join('hoomroomplacement', 
            'users.gradesec = hoomroomplacement.roomgrade');
        $query = $this->db->get();
        $output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>AbsentType</th>
                <th>Absent Date</th>
                <th>Total Absents</th>
        	</tr>
        	</thead>
       		<tbody>';
       		$no=1;
       		foreach ($query->result() as $staff) {
       			$id=$staff->stuid;
       			$queryCunt=$this->db->query("select count(stuid) as totalAbsents from attendance where stuid ='$staff->stuid' and academicyear='$max_year' ");
       			$row=$queryCunt->row();
       			$cunt=$row->totalAbsents;
       			$id=$staff->stuid;
   				$output.='<tr class="delete_staff'.$id.'">
                <td>'.$no.'.</td>';
                if($staff->approved=='1'){
                	$output.='<td>'. $staff->username.' </td>';
                }else{
                	$output.='<td>'. $staff->username.'<a href="#" class="deleteStuAttendance text-danger" id="'.$staff->absentdate.'" value="'.$id.'"><i class="fas fa-trash-alt"></i></a> </td>';
                }      
		        $output.='
                <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                </td>
                <td><span class="text-danger">'.$staff->absentype.'</span></td>
                
                <td>'.$staff->absentdate.'</td>
                 <td><span class ="badge badge-info">'.$cunt.'</span></td>                      
                </tr>';
                $no++;	
       		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i> No absent students found yet.
            </div></div>';
		}
		return $output;
	}
	function delete_attendance($id,$absentDate){
		$this->db->where(array('stuid'=>$id));
		$this->db->where(array('absentdate'=>$absentDate));
		$this->db->delete('attendance');
	}
	function delete_Staffattendance($id,$max_year){
		$this->db->where(array('aid'=>$id));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->delete('attendance');
	}
	function student_total_absents($max_year){
		$query = $this->db->query("select us.fname, us.profile, us.mname,us.gradesec, count(stuid) as rank from attendance as at cross join users as us where us.username=at.stuid and at.academicyear='$max_year' and absentype='Absent' and us.usertype='Student' group by stuid
			HAVING count(*)=(select count(stuid) from attendance where academicyear='$max_year' and  absentype='Absent' group by stuid DESC limit 1) limit 1");
		/*select rank from(select  count(stuid) as rank from attendance  group by stuid) attendance order by rank DESC limit 8*/
		return $query->result();
	}
	function student_mtotal_absents($max_year,$branch){
		$query = $this->db->query(" select us.fname, us.profile, us.mname,us.gradesec, count(stuid) as rank,FIND_IN_SET(count(stuid),(select GROUP_CONCAT(stuid order by stuid DESC )from attendance where academicyear= '$max_year' and branch='$branch')) as attendance from attendance as at cross join users as us where us.username=at.stuid and at.academicyear='$max_year' and branch='$branch' and absentype='Absent' and us.usertype='Student' group by stuid ");
		return $query->result();
	}
	function my_total_absents($max_year,$user){
		$this->db->where('users.username',$user);
		$this->db->where('attendance.approved','1');
		$this->db->where('attendance.academicyear',$max_year);
		$this->db->order_by('aid','DESC');
		$this->db->select('*');
		$this->db->from('attendance');
		$this->db->join('users','users.username=attendance.stuid');
		$query = $this->db->get();
		return $query;
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
			$output.='<p class="StudentViewTextInfo"><small>Total Absent Days</small><span class="badge badge-info"> '.$totalAbsent.'</span></p> ';
			foreach($query->result() as $absentDate){
				$output.='<p>'.$absentDate->absentdate.'=><span class="text-danger">'.$absentDate->absentype.'</span><p>';
			}
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
	function export_student_mark_formate($gradesec,$quarter,$max_year,$branch1){
		$this->db->where('evaluation.academicyear',$max_year);
		$this->db->where('evaluation.quarter',$quarter);
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch1);
		$this->db->where('users.status','Active');
		$this->db->where('users.isapproved','1');
		$this->db->where('users.academicyear',$max_year);
		$this->db->order_by('users.fname','ASC');
		$this->db->order_by('users.mname','ASC');
		$this->db->order_by('users.lname','ASC');
		$this->db->group_by('users.id');
		$this->db->select('*');
		$this->db->from('evaluation');
		$this->db->join('users', 
            'users.grade = evaluation.grade');
		$this->db->join('schoolassesment', 
            'schoolassesment.saseval = evaluation.evname');
		$query = $this->db->get();
        return $query->result();
	}
	function export_mystudent_mark_formate($gradesec,$quarter,$max_year,$branch){
		$this->db->where('evaluation.academicyear',$max_year);
		$this->db->where('evaluation.quarter',$quarter);
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch);
		$this->db->where('users.status','Active');
		$this->db->where('users.isapproved','1');
		$this->db->where('users.academicyear',$max_year);
		$this->db->order_by('users.fname','ASC');
		$this->db->order_by('users.mname','ASC');
		$this->db->order_by('users.lname','ASC');
		$this->db->group_by('users.id');
		$this->db->select('*');
		$this->db->from('evaluation');
		$this->db->join('users', 
            'users.grade = evaluation.grade');
		$this->db->join('schoolassesment', 
            'schoolassesment.saseval = evaluation.evname');
		$query = $this->db->get();
        return $query->result();
	}
	function export_this_grade_evname($gradesec,$quarter,$max_year,$branch1){
		$this->db->where('evaluation.academicyear',$max_year);
		$this->db->where('evaluation.quarter',$quarter);
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch1);
		$this->db->where('users.status','Active');
		$this->db->where('users.isapproved','1');
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('schoolassesment.academicyear',$max_year);
		$this->db->order_by('evaluation.eid','ASC');
		$this->db->group_by('schoolassesment.sasname');
		$this->db->select('*');
		$this->db->from('evaluation');
		$this->db->join('users', 
            'users.grade = evaluation.grade');
		$this->db->join('schoolassesment', 
            'schoolassesment.saseval = evaluation.evname');
		
		$query = $this->db->get();
        return $query->result();
	}
	function export_mythis_grade_evname($gradesec,$quarter,$max_year,$branch){
		$this->db->where('evaluation.academicyear',$max_year);
		$this->db->where('evaluation.quarter',$quarter);
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch);
		$this->db->where('users.status','Active');
		$this->db->where('users.isapproved','1');
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('schoolassesment.academicyear',$max_year);
		$this->db->order_by('evaluation.eid','ASC');
		$this->db->group_by('schoolassesment.sasname');
		$this->db->select('*');
		$this->db->from('evaluation');
		$this->db->join('users', 
            'users.grade = evaluation.grade');
		$this->db->join('schoolassesment', 
            'schoolassesment.saseval = evaluation.evname');
		$query = $this->db->get();
        return $query->result();
	}
	function get_allsubject($gradesec,$max_year){
		$query=$this->db->query("select count(su.Subj_Id) as all_sub,su.Subj_Id,su.Subj_name,su.Grade from subject as su cross join users as u where u.grade=su.Grade and u.gradesec='$gradesec' and Academic_Year='$max_year' and academicyear='$max_year' group by su.Subj_Id order by su.Subj_name ASC");
		return $query->result();
	}
	function check_import_markm2($markname,$subname,$quarter,$max_year,$gradesec,$mybranch){
		$this->db->where(array('markname'=>$markname));
		$this->db->where(array('subname'=>$subname));
		$this->db->where(array('quarter'=>$quarter));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('mgrade'=>$gradesec));
		$this->db->where(array('mbranch'=>$mybranch));
		$query=$this->db->get('mark'.$mybranch.$gradesec.$quarter.$max_year);
		if($query->num_rows()>0){
			return false;
		}else{
			return true;
		}
	}
	function import_mark($data,$gradesec,$max_quarter,$max_year){
		$query=$this->db->insert('mark'.$mybranch.$max_quarter.$max_year,$data);
		if($query){
			return true;
		}else{
			return false;
		}
	}
	function dump_import_subject($data){
		$query=$this->db->insert('subject',$data);
		if($query){
			return true;
		}else{
			return false;
		}
	}
	function dump_import_evaluation($data){
		$query=$this->db->insert('evaluation',$data);
		if($query){
			return true;
		}else{
			return false;
		}
	}
	function select_edited_mark($edtimar,$quarter,$gradesec,$academicyear,$branch){
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
			<input type="text" class="form-control my_markNameH" value="'.$markname.'">
			</div></div> 
			<div class="col-md-6"> <div class="form-group"> 
			<input class="form-control correct_ngmark_gs" placeholder="Value..." required="required" id="" type="text"> </div></div>
			 </div>';
			
		}$output.='<a class="info-ngmark"></a>';
		return $output;
	}
	function select_edited_outof($markanme,$quarter,$subject,$gradesec,$max_year){
		$this->db->where(array('markname'=>$markanme));
		$this->db->where(array('quarter'=>$quarter));
		$this->db->where(array('subname'=>$subject));
		$this->db->where(array('gradesec'=>$gradesec));
		$this->db->group_by('markname');
		$this->db->select('*');
		$this->db->from('mark'.$gradesec.$quarter.$max_year);
		$this->db->join('users', 
            'users.id = mark'.$gradesec.$quarter.$max_year.'.stuid');
		$query=$this->db->get();
		$output='';
		foreach ($query->result() as $value) {
			$output .='<input type="hidden" class="markname" value="'.$value->markname.'">';
			$output .='<input type="hidden" class="gr" value="'.$gradesec.'">';
			$output .='<input type="hidden" class="qu" value="'.$quarter.'">';
			$output .='<input type="hidden" class="su" value="'.$subject.'">';
			$output .='<div class="row"> <div class="col-lg-6"> <div class="form-group">
			<input class="form-control correct_markname_gs" id="correct_name" type="text" value="'.$value->markname.'">
			</div></div>';
			$output .='<div class="col-lg-6"> <input class="form-control correct_markoutof_gs" id="correct_outof" type="text" value="'.$value->outof.'">
			</div></div>
			<a class="info-mark"></a>';
		}
		return $output;
	}
	function update_edited_mark($user,$outof,$mid,$value,$quarter,$gradesec,$year,$branch)
	{
		$output='';
		$selectUpdatedMark=$this->db->query("select * from mark".$branch.$gradesec.$quarter.$year." where mid='$mid' ");
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
			'userbranch'=>$branch
		);
		$queryInsert=$this->db->insert('useractions',$data);
		if($queryInsert){
			$this->db->where(array('mid'=>$mid));
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
	function update_edited_ngmark($user,$data,$quarter,$gradesec,$year,$my_studentBranch,$value,$subject,$stuid,$markname){
		$output='';
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
			'userbranch'=>$my_studentBranch
		);
		$queryInsert=$this->db->insert('useractions',$data1);

		if($queryInsert){
			$queryCheck=$this->db->query("select * from mark".$my_studentBranch.$gradesec.$quarter.$year." where stuid='$stuid' and subname='$subject' and quarter='$quarter' and markname='$markname' and academicyear='$year' ");
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
	function update_edited_outof($markanme,$grade,$quarter,$subject,$correct_name,$correct_markoutof_gs,$max_year){
		$query_filetrstudent=$this->db->query("select * from users where gradesec='$grade' and academicyear='$max_year' and usertype='Student' ");
		foreach ($query_filetrstudent->result() as $kvalue) 
		{
			$id=$kvalue->id;
			$this->db->where(array('markname'=>$markanme));
			$this->db->where(array('stuid'=>$id));
			$this->db->where(array('quarter'=>$quarter));
			$this->db->where(array('subname'=>$subject));
			//$this->db->where(array('gradesec'=>$grade));
			$this->db->set('markname',$correct_name);
			$this->db->set('outof',$correct_markoutof_gs);
			$query=$this->db->update('mark');
		}
		$output='';
		if($query){
			$output .='<span class="text-success"> Updated</span>';
		}else{
			$output .='<span class="text-danger"> ooops</span>';
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
	function fetch_mybranch_gradesec($branch,$max_year)
	{
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('users.branch',$branch);
		$this->db->where('users.grade !=','0');
		$this->db->group_by('users.grade');
		$this->db->order_by('users.grade','ASC');
		$query = $this->db->get('users');
		return $query->result();
	}
	function fetch_myschool_gradesec($max_year)
	{
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('users.grade !=','0');
		$this->db->where('users.grade !=','');
		$this->db->group_by('users.grade');
		$this->db->order_by('users.grade','ASC');
		$query = $this->db->get('users');
		return $query->result();
	}
	function fetch_my_subject($grade,$max_year){
		$this->db->where('subject.Academic_Year',$max_year);
		$this->db->where('subject.grade',$grade);
		$this->db->group_by('subject.Subj_name');
		$this->db->order_by('subject.Subj_name','ASC');
		$query = $this->db->get('subject');
		return $query->result();
	}
	function fetch_this_grade_subjects($user,$gradesec,$max_year){
		$this->db->where('staffplacement.academicyear',$max_year);
		$this->db->where('staffplacement.staff',$user);
		$this->db->where('staffplacement.grade',$gradesec);
		$query=$this->db->get('staffplacement');
		$output ='<option> </option>';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->subject.'">
			'.$row->subject.'</option>';
		}
		return $output;
	}
	function fetch_eachthis_grade_subjects($gradesec,$max_year){
		$this->db->where('subject.Academic_Year',$max_year);
		$this->db->where('subject.Grade',$gradesec);
		$this->db->order_by('subject.Subj_name','ASC');
		$query=$this->db->get('subject');
		$output ='<option> </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->Subj_name.'">
				'.$row->Subj_name.'</option>';
			}
			return $output;
	}
	function insert_lesson($user,$subject,$gradesec,$title,$note,$max_year){
		$data=array(
			'teacher'=>$user,
			'subject'=>$subject,
			'grade'=>$gradesec,
			'title'=>$title,
			'note'=>$note,
			'lesson_date'=>date('M-d-Y'),
			'academicyear'=>$max_year
		);
		$query=$this->db->insert('lesson',$data);
		return $query;
	}
	function checkLessonPlan($lessonGrade,$lesson_subject,$max_year){
		$this->db->where('grade',$lessonGrade);
		$this->db->where('subject',$lesson_subject);
		$this->db->where('academicyear',$max_year);
		$query=$this->db->get('lessonplan');
		if($query->num_rows()>0){
			return false;
		}else{
			return true;
		}
		
	}
	function viewLessonPlan($max_year){
		$this->db->where('lessonplan.academicyear',$max_year);
		$this->db->order_by('lessonplan.id','DESC');
		$this->db->select('lessonplan.id, lessonplan.grade, lessonplan.subject, lessonplan.dateposted, lessonplan.postby,lessonplan.lesson_objective,users.profile,users.fname,users.mname, lessonplan.teacher_guide,lessonplan.student_guide,lessonplan.material_needed,lessonplan.academicyear');
		$this->db->from('lessonplan');
		$this->db->join('users', 
            'users.username = lessonplan.postby');
		$query = $this->db->get();
        return $query->result();
	}
	function viewLessonPlanTeacher($max_year,$user){
		$this->db->where('lessonplan.academicyear',$max_year);
		$this->db->where('lessonplan.postby',$user);
		$this->db->order_by('lessonplan.id','DESC');
		$this->db->select('lessonplan.id, lessonplan.grade, lessonplan.subject, lessonplan.dateposted, lessonplan.postby,lessonplan.lesson_objective,users.profile,users.fname,users.mname, lessonplan.teacher_guide,lessonplan.student_guide,lessonplan.material_needed,lessonplan.academicyear');
		$this->db->from('lessonplan');
		$this->db->join('users', 
            'users.username = lessonplan.postby');
		$query = $this->db->get();
        return $query->result();
	}
	function viewLessonPlanDirector($max_year,$user){
		$query = $this->db->query(" select lessonplan.id, lessonplan.grade, lessonplan.subject, lessonplan.dateposted, lessonplan.postby, lessonplan.lesson_objective, users.profile,users.fname, users.mname, lessonplan.teacher_guide,lessonplan.student_guide,lessonplan.material_needed,lessonplan.academicyear from lessonplan cross join users cross join directorplacement where users.usertype!='Student' and lessonplan.academicyear='$max_year' and users.username=lessonplan.postby and lessonplan.postby='$user' 
			OR  directorplacement.staff='$user' and directorplacement.academicyear='$max_year' and directorplacement.grade=users.gradesec and lessonplan.grade=users.grade group by lessonplan.subject order by lessonplan.id DESC  ");
        return $query->result();
	}
	function deleteLessonId($lessonID){
		$this->db->where('lessonplan.id',$lessonID);
		$query = $this->db->delete('lessonplan');
	}
	function editLessonId($lessonID){
		$this->db->where('lessonplan.id',$lessonID);
		$query = $this->db->get('lessonplan');
		$output='';
		foreach($query->result() as $lessonP){
			$output.='<input type="hidden" id="lessonPlanId" value="'.$lessonP->id.'">
			<div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="Mobile">Lesson Objective</label>
                  <textarea class="form-control summernote-simple" id="lesson_objective_update" name="lesson_objective" required="required">
                  '.$lessonP->lesson_objective.'
                   </textarea>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="Mobile">Teachers Guide</label>
                  <textarea class="form-control summernote-simple" id="teachers_guide_update" name="teachers_guide" required="required"> '.$lessonP->teacher_guide.'</textarea>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="Mobile">Students Guide</label>
                  <textarea class="form-control summernote-simple" id="students_guide_update" name="students_guide" required="required"> '.$lessonP->student_guide.'</textarea>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="Mobile">Materials Needed</label>
                  <textarea class="form-control summernote-simple" id="materials_needed_update" name="materials_needed" required="required"> '.$lessonP->material_needed.'</textarea>
                </div>
              </div>
            </div>';
		}
		return $output;
	}
	function viewLessonId($lessonID,$max_year){
		$this->db->where('lessonplan.id',$lessonID);
		$query = $this->db->get('lessonplan');
		$output='';

		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
		foreach($query->result() as $lessonP){
			$output.='<div id="printLessonPlanGs">
			<h3 class="text-center"><p><u><b>'.$school_name.' Lesson Plan for '.$max_year.' Academic Year</b></u></p></h3>
			<p><h5 class="text-center"><u>Subject: <b>'.$lessonP->subject.'</b> & Grade: <b>'.$lessonP->grade.'</b></u></h5></p>';
			$output.='<div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label for="Mobile">Lesson Objective</label>
                  '.$lessonP->lesson_objective.'
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label for="Mobile">Teachers Guide</label> '.$lessonP->teacher_guide.'
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label for="Mobile">Students Guide</label> '.$lessonP->student_guide.'
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label for="Mobile">Materials Needed</label>'.$lessonP->material_needed.'
                </div>
              </div>
            </div>
            </div>';
		}
		return $output;
	}
	function insert_elibrary($user,$subject,$gradesec,$notepdf,$max_year){
		$data=array(
			'pby'=>$user,
			'grade'=>$gradesec,
			'subjects'=>$subject,
			'ebook'=>$notepdf,
			'edate'=>date('M-d-Y'),
			'academicyear'=>$max_year
		);
		$query=$this->db->insert('library',$data);
		return $query;
	}
	function insert_lesson2($user,$subject,$gradesec,$title,$note,$max_year){
		$data=array(
			'teacher'=>$user,
			'subject'=>$subject,
			'grade'=>$gradesec,
			'title'=>$title,
			'pdfdoc'=>$note,
			'lesson_date'=>date('M-d-Y'),
			'academicyear'=>$max_year
		);
		$query=$this->db->insert('lesson',$data);
		return $query;
	}
	function answer_worksheet($id,$gradesec,$subject,$title,$notepdf,$max_year){
		$data=array(
			'sid'=>$id,
			'grade'=>$gradesec,
			'subject'=>$subject,
			'title'=>$title,
			'answeredfile'=>$notepdf,
			'answered_date'=>date('M-d-Y'),
			'academicyear'=>$max_year
		);
		$query=$this->db->insert('answerworksheet',$data);
		return $query;
	}
	function fetch_answer_worksheet($max_year,$id){
		$this->db->where('answerworksheet.sid',$id);
		$this->db->where('answerworksheet.academicyear',$max_year);
		$this->db->select('*');
		$this->db->from('answerworksheet');
		$this->db->join('users', 
            'users.id = answerworksheet.sid');
		$query = $this->db->get();
		return $query->result();
	}
	function fetch_lesson($max_year){
		$query=$this->db->query("
			Select us.fname,us.mname,us.profile,le.grade,le.title,le.pdfdoc,le.note,le.subject,le.lid,le.lesson_date,le.teacher from lesson as le cross join users as us where le.academicyear='$max_year' and le.teacher=us.username order by le.lid DESC
			");
        return $query->result();
	}
	function fetch_milesson($max_year,$user){
		$query=$this->db->query("
			Select us.fname,us.mname,us.profile,le.grade,le.title,le.pdfdoc,le.note,le.subject,le.lid,le.lesson_date,le.teacher from lesson as le cross join users as us where le.academicyear='$max_year'and le.teacher='$user' and le.teacher=us.username order by le.lid DESC
			");
        return $query->result();
	}
	function my_lesson($max_year,$gradesec,$grade){
		$this->db->where('lesson.academicyear',$max_year);
		$this->db->where('lesson.grade',$grade);
		$this->db->or_where('lesson.grade',$gradesec);
		$this->db->order_by('lesson.lid','DESC');
		$this->db->select('*');
		$this->db->from('lesson');
		$this->db->join('users', 
            'users.username = lesson.teacher');
		$query = $this->db->get();
        return $query->result();
	}
	function mystudent_lesson($max_year,$user){
		$this->db->where('lesson.academicyear',$max_year);
		$this->db->where('lesson.teacher',$user);
		$this->db->order_by('lesson.lid','DESC');
		$this->db->select('*');
		$this->db->from('lesson');
		$this->db->join('users', 
            'users.username = lesson.teacher');
		$query = $this->db->get();
        return $query->result();
	}
	function delete_lesson($id){
		$this->db->where('lid',$id);
		$this->db->delete('lesson');
	}
	function delete_elibrary($id){
		$this->db->where('lid',$id);
		$this->db->delete('library');
	}
	function delete_sent_worksheet($awid){
		$this->db->where('awid',$awid);
		$this->db->delete('answerworksheet');
	}
	function fetch_answered_worksheet($max_year){
		$this->db->where(array('answerworksheet.academicyear'=>$max_year));
		$this->db->select('*');
		$this->db->from('answerworksheet');
		$this->db->join('users', 
            'users.id = answerworksheet.sid');
		$query = $this->db->get();
        return $query->result();
	}
	function fetch_answered_worksheet_4this_admin($branch,$max_year){
		$this->db->where(array('answerworksheet.academicyear'=>$max_year));
		$this->db->where(array('users.branch'=>$branch));
		$this->db->select('*');
		$this->db->from('answerworksheet');
		$this->db->join('users', 
            'users.id = answerworksheet.sid');
		$query = $this->db->get();
        return $query->result();
	}
	function fetch_myclass_answered_worksheet($user,$max_year){
		$query = $this->db->query("
			select aw.subject,aw.awid,aw.answered_date,aw.title,aw.answeredfile, us.fname,us.profile,us.mname,us.gradesec from users as us cross join answerworksheet as aw cross join staffplacement as st where st.grade=aw.grade and st.subject=aw.subject and us.id=aw.sid  and st.staff='$user' group by aw.awid
			");
        return $query->result();
	}
	function read_lesson($id){
		$this->db->where(array('lesson.lid'=>$id));
		$this->db->select('*');
        $this->db->from('lesson');
        $this->db->join('users', 
            'users.username = lesson.teacher');
        $query = $this->db->get();
        return $query->result();
	}
	function my_answer($sid,$subject,$examname,$question,$mianswer,$datetime,$max_year){
		$data=array(
			'sid'=>$sid,
			'subject'=>$subject,
			'examname'=>$examname,
			'ques'=>$question,
			'ans'=>$mianswer,
			'datesubmitted'=>$datetime,
			'academicyear'=>$max_year
		);
		$query=$this->db->insert('examanswer',$data);
		if($query){
			return true;
		}
		else{
			return;
		}
	}
	function fetch_my_examresult($sid,$max_year){
		$this->db->where(array('examanswer.sid'=>$sid));
		$this->db->where(array('examanswer.academicyear'=>$max_year));
		$this->db->group_by('examanswer.examname');
		$this->db->group_by('examanswer.subject');
		$this->db->order_by('examanswer.id','DESC');
		$this->db->select('*');
        $this->db->from('examanswer');
        $this->db->join('users', 
            'users.id = examanswer.sid');
        $query = $this->db->get();
        return $query->result();
	}
	function fetch_this_subjectresult($sid,$subject,$examname,$max_year){
		$this->db->where(array('examanswer.sid'=>$sid));
		$this->db->where(array('examanswer.subject'=>$subject));
		$this->db->where(array('examanswer.examname'=>$examname));
		$this->db->where(array('examanswer.academicyear'=>$max_year));
		$this->db->select('*');
        $this->db->from('examanswer');
        $this->db->join('exam', 
            'exam.eid = examanswer.ques');
        $query = $this->db->get();
        return $query->result();
	}
	function read_ansered_worksheet($id){
		$this->db->where(array('answerworksheet.awid'=>$id));
		$this->db->select('*');
        $this->db->from('answerworksheet');
        $this->db->join('users', 
            'users.id = answerworksheet.sid');
        $query = $this->db->get();
        return $query->result();
	}
	function add_payment_category($acy,$pcname,$grade,$month){
		$this->db->where(array('academicyear'=>$acy));
		$this->db->where(array('name'=>$pcname));
		$this->db->where(array('grade'=>$grade));
		$this->db->where(array('month'=>$month));
		$query=$this->db->get('paymentype');
		if($query->num_rows() > 0){
			return false;
		}else{
			return true;
		}
	}
	function delete_payment_category($id){
		$this->db->where(array('id'=>$id));
		$this->db->delete('paymentype');
	}
	function fetch_gradesec_forpayment($gradesec,$max_year){
		$this->db->where('gradesec',$gradesec);
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->order_by('fname','ASC');
		$query = $this->db->get('users');
		$output ='';
        $output .='<div class="table-responsive">
        <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
            <thead>
              <tr>
               <th>
                <button class="btn btn-success" type="submit" name="paid">Pay</button>
               </th>
                <th>Student Name</th>
                <th>Grade</th>
                <th>Branch</th>
                <th>Gender</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($query->result() as $row) {
            $output .='<tr>
              <td><input type="checkbox" name="paidid[ ]" value="'.$row->id.'"></td>
              <td>'.$row->fname.'&nbsp'.$row->mname.'</td>
              <td>'.$row->gradesec.'</td>
              <td>'.$row->branch.'</td>
              <td>'.$row->gender.'</td>
              </tr>';
              }
              '</tbody>
        </table>
        </div>';
        return $output;
	}
	function insert_payment($check,$month,$ptype,$gradesec,$acy){
		$this->db->where(array('stuid'=>$check));
		$this->db->where(array('academicyear'=>$acy));
		$this->db->where(array('paymentype'=>$ptype));
		$this->db->where(array('gradesecc'=>$gradesec));
		$this->db->where(array('month'=>$month));
		$query=$this->db->get('payment');
		if($query->num_rows() > 0){
			return false;
		}else{
			return true;
		}
	}
	function fetch_payment(){
		$this->db->order_by('payment.pid','DESC');
		$this->db->select('*');
        $this->db->from('payment');
        $this->db->join('users', 
            'users.id = payment.stuid');
        $query = $this->db->get();
        return $query->result();
	}
	function delete_payment($id){
		$this->db->where(array('pid'=>$id));
		$this->db->delete('payment');
	}
	function fetch_payment_report($max_year){
		$query = $this->db->query("select us.gradesec, us.fname,
			us.mname,us.profile,pt.name,pt.month,pt.academicyear from users as us cross join paymentype pt where us.usertype ='Student' and us.grade=pt.grade and us.id not in(select stuid from payment as p where academicyear='$max_year' and p.paymentype = pt.name and p.month=pt.month) ;
			");
		return $query->result();
	}
	function fetch_mypayment_report($max_year,$id){
		$query = $this->db->query("select us.gradesec, us.fname,
			us.mname,us.profile,pt.name,pt.month,pt.academicyear from users as us cross join paymentype pt where us.id='$id' and us.usertype ='Student' and us.grade=pt.grade and us.id not in(select stuid from payment as p where stuid='$id' and academicyear='$max_year' and p.paymentype = pt.name and p.month=pt.month) ;
			");
		return $query->result();
	}
	function fetch_grade_from_staffplace($user,$max_year){
		$this->db->where(array('staff'=>$user));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->group_by('grade');
		$query=$this->db->get('staffplacement');
		return $query->result();
	}
	function fetch_grade_from_staffplace4Director($user,$max_year){
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
	function fetchEval4Assesment($max_year,$quarter){
		$this->db->where(array('evaluation.quarter'=>$quarter));
		//$this->db->where('users.gradesec',$gradesec);
		$this->db->where(array('evaluation.academicyear'=>$max_year));
		//$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('evaluation.evname','ASC');
		$this->db->group_by('evaluation.evname');
		$this->db->select('*');
		$this->db->from('evaluation');
		$query=$this->db->get();
		/*$output ='<option> </option>';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->eid.'">'.$row->evname.'</option>';
		}*/
		return $query->result();
	}
	function FilterAssesmentQuarterChange($evaluation,$gradesec,$max_year,$branch,$quarter,$subject){
		$this->db->where(array('schoolassesment.saseval'=>$evaluation));
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where(array('schoolassesment.academicyear'=>$max_year));
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('schoolassesment.sasname','ASC');
		$this->db->group_by('schoolassesment.sasname');
		$this->db->select('*');
		$this->db->from('schoolassesment');
		$this->db->join('users',
		'users.grade = schoolassesment.sasgrade');
		$query=$this->db->get();
		$output ='<option> </option>';
		$dateToday=date('Y-m-d');
		foreach ($query->result() as $row) { 
			$queryCheckMark=$this->db->query("select markname from mark".$branch.$gradesec.$quarter.$max_year." where academicyear='$max_year' and markname='".$row->sasname."' and mbranch='$branch' and mgrade='$gradesec' and quarter='$quarter' and subname='$subject' ");
			if($queryCheckMark->num_rows()<1){
				$queryCheckDate=$this->db->query("select * from lockmarkauto where academicyear='$max_year' and autolockstatus='1' ");
				if($queryCheckDate->num_rows() > 0){
					$queryQuarterEndDate=$this->db->query("select * from schoolassesment where academicyear='$max_year' and sasname='".$row->sasname."' ");
					$endDateRow=$queryQuarterEndDate->row_array();
                	$endDateName=$endDateRow['dateend'];
                	if($endDateName>=$dateToday){
                		$output .='<option value="'.$row->sasname.'">'.$row->sasname.'</option>';
                	}
				}else{
					$output .='<option value="'.$row->sasname.'">'.$row->sasname.'</option>';
				}
			}
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
	function fetch_evaluation_from_staffplace($gradesec,$max_year,$max_quarter){
		$this->db->where(array('evaluation.quarter'=>$max_quarter));
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
	function fetch_grade_from_branch($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.gradesec','ASC');
		$this->db->group_by('users.gradesec');
		$query=$this->db->get('users');
		$output ='';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->gradesec.'">'.$row->gradesec.'</option>';
		}
	    return $output;
	}
	function fetch_term_from_grade($grade,$max_year){
		$this->db->select('quarter.term');
		$this->db->from('users');
		$this->db->join('quarter',
		'quarter.termgrade=users.grade');
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('users.gradesec',$grade);
		$this->db->where('quarter.Academic_year',$max_year);
		$this->db->group_by('quarter.term');
		$query=$this->db->get();
		$output ='';
		if($query->num_rows()>0){
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->term.'">'.$row->term.'</option>';
			}
		}else{
			$output .='<option>No Term/Quarter</option>';
		}
	    return $output;
	}
	function fetch_term_from_gradegs($grade,$max_year){
		$this->db->where('quarter.termgrade',$grade);
		$this->db->where('quarter.Academic_year',$max_year);
		$this->db->group_by('quarter.term');
		$query=$this->db->get('quarter');
		$output ='';
		if($query->num_rows()>0){
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->term.'">'.$row->term.'</option>';
			}
		}else{
			$output .='<option>No Term/Quarter</option>';
		}
	    return $output;
	}
	function filterGradeFromBranchGS($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where('users.usertype','Student');
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.gradesec','ASC');
		$this->db->group_by('users.gradesec');
		$query=$this->db->get('users');
		$output ='';
		$output.='<div class="row">';
		foreach ($query->result() as $row) {
			$output.='<div class="col-lg-4 col-6">
                <div class="pretty p-icon p-jelly p-round p-bigger">
                  	<input type="checkbox" name="grade2analysisGrandstande" value="'.$row->gradesec.'" class="grade2analysisGrandstande" id="customCheck1">
                  	<div class="state p-info">
                    	<i class="icon material-icons"></i>
                    	<label></label>
                  	</div>
                </div>'.$row->gradesec.'
            </div>'; 
		}
		$output.='</div>';
	    return $output;
	}
	function filterGradeFromCustomBranchGS($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where('users.usertype','Student');
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.gradesec','ASC');
		$this->db->group_by('users.gradesec');
		$query=$this->db->get('users');
		$output ='';
		$output.='<div class="row">';
		foreach ($query->result() as $row) {
			$output.='<div class="col-lg-4 col-6">
                <div class="pretty p-icon p-jelly p-round p-bigger">
                  	<input type="checkbox" name="gradeCustomAnalysisGrandstande" value="'.$row->gradesec.'" class="gradeCustomAnalysisGrandstande" id="customCheck1">
                  	<div class="state p-info">
                    	<i class="icon material-icons"></i>
                    	<label></label>
                  	</div>
                </div>'.$row->gradesec.'
            </div>'; 
		}
		$output.='</div>';
	    return $output;
	}
	function filter_evaluation4CustomAnalysisGrand($mybranch,$gradesecs,$max_year,$quarter)
	{
		$output='';
		foreach($gradesecs as $gradesec){
			$query=$this->db->query("select ev.sasid,ev.sasname from users as u right join schoolassesment as ev ON u.grade=ev.sasgrade where u.academicyear='$max_year' and u.status='Active' and u.isapproved='1' and u.usertype='Student' and ev.academicyear='$max_year' and u.gradesec='$gradesec' and u.branch='$mybranch' group by ev.sasname , ev.sasgrade order by sasid ASC ");
			$output='<div class="row"> ';
			foreach ($query->result() as $evavalue) {
				$output.='<div class="col-lg-12 col-12">
	                <div class="pretty p-icon p-jelly p-round p-bigger">
	                  <input type="checkbox" name="customevaluationanalysis" value="'.$evavalue->sasname.'" class="customevaluationanalysis" id="customCheck1">
	                  <div class="state p-success">
	                    <i class="icon material-icons"></i>
	                    <label></label>
	                  </div>
	                </div> '.$evavalue->sasname.'
	            </div>';
			}
			$output.='</div>';
		}
		return $output;
	}
	function filterSubject4CustomAnalysisGrand($mybranch,$gradesecs,$max_year,$quarter)
	{
		$output='';
		foreach($gradesecs as $gradesec){
			$query=$this->db->query("select ev.Subj_Id,ev.Subj_name from users as u right join subject as ev ON u.grade=ev.Grade where u.academicyear='$max_year' and u.status='Active' and u.isapproved='1' and u.usertype='Student' and ev.Academic_Year='$max_year' and u.gradesec='$gradesec' and u.branch='$mybranch' group by ev.Subj_name , ev.Grade order by Subj_name ASC ");
			$output='<div class="row"> ';
			foreach ($query->result() as $evavalue) {
				$output.='<div class="col-lg-12 col-6">
	                <div class="pretty p-icon p-jelly p-round p-bigger">
	                  <input type="checkbox" name="customSubjectAnalysis" value="'.$evavalue->Subj_name.'" class="customSubjectAnalysis" id="customCheck1">
	                  <div class="state p-success">
	                    <i class="icon material-icons"></i>
	                    <label></label>
	                  </div>
	                </div> '.$evavalue->Subj_name.'
	            </div>';
			}
			$output.='</div>';
		}
		return $output;
	}
	function fetch_grade_from_branchAll($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.gradesec','ASC');
		$this->db->group_by('users.gradesec');
		$query=$this->db->get('users');
		$output ='<div class="row">';
		foreach ($query->result() as $row) {
			$output .='<div class="col-lg-4 col-6">
			<input type="checkbox" class="studentGradeSecJoss" name="studentGradeSecJoss" value="'.$row->gradesec.'"/>'.$row->gradesec.'</div>';
		}
		$output .='</div>';

		/*foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->gradesec.'">'.$row->gradesec.'</option>';
		}*/
	    return $output;
	}
	function fetchGradeFromBranchTransport($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where('users.grade !=','');
		$this->db->where('users.usertype','Student');
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.grade','ASC');
		$this->db->group_by('users.grade');
		$query=$this->db->get('users');
		$output ='<div class="row">';
		foreach ($query->result() as $row) {
			$output .='<div class="col-lg-4 col-6">
			<input type="checkbox" class="studentServiceGrade" name="studentServiceGrade" value="'.$row->grade.'"/>'.$row->grade.'
			<input type ="hidden" name="studentListPLace" id="studentListPLace" value="'.$max_year.'"/>
			</div>';
		}
		$output .='</div>';
	    return $output;
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
	function fetchThisGradeStudentIdcard($grade,$academicyear){
		$output ='<input type="checkbox" class="" id="selectallStudentList" onClick="selectAllStudent()">Select All<div class="row"> ';
		foreach($grade as $grades){
			$this->db->where('grade',$grades);
			$this->db->where('academicyear',$academicyear);
			$this->db->order_by('users.fname,users.mname,users.lname','ASC');
			$this->db->group_by('users.id');
			$query=$this->db->get('users');
			
			foreach ($query->result() as $row) {
				$output .='<div class="col-lg-12 col-12">
				<input type="checkbox" class="studentListTransportService" name="studentListTransportService[ ]" value="'.$row->id.'"/>'.$row->fname.' '.$row->mname.' '.$row->lname.'
				</div>';
			}
		}
		$output .='</div>';
		return $output;
	}
	function fetchThisGradeStudentMoveCopyMarkQuarterly($academicyear,$branch){
		$output ='<div class="row"> ';
		$this->db->where('usertype','Student');
		$this->db->where('branch',$branch);
		$this->db->where('academicyear',$academicyear);
		$this->db->order_by('users.gradesec','ASC');
		$this->db->group_by('users.gradesec');
		$query=$this->db->get('users');
		foreach ($query->result() as $row) {
			$output .='<div class="col-lg-3 col-6">
			<input type="checkbox" class="selectAllMoveCopyMarkQuarterly" name="selectAllMoveCopyMarkQuarterly[ ]" value="'.$row->grade.'"/>'.$row->gradesec.'
			</div>';
		}
		
		$output .='</div>';
		return $output;
	}
	function fetchThisGradeSubjectQuarterly($academicyear,$grades){
		foreach($grades as $grade){
			$this->db->where('subject.Grade',$grade);
			$this->db->where(array('subject.Academic_Year'=>$academicyear));
			$this->db->order_by('subject.Subj_name','ASC');
			$this->db->group_by('subject.Subj_name');
			$query=$this->db->get('subject');
			$output ='<option>  </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->Subj_name.'">'.$row->Subj_name.'</option>';
			}
			return $output;
		}
	}
	function fetchThisGradeStudentMoveCopyMark($academicyear,$branch){
		$output ='<div class="row"> ';
		$this->db->where('usertype','Student');
		$this->db->where('branch',$branch);
		$this->db->where('academicyear',$academicyear);
		$this->db->order_by('users.gradesec','ASC');
		$this->db->group_by('users.gradesec');
		$query=$this->db->get('users');
		foreach ($query->result() as $row) {
			$output .='<div class="col-lg-3 col-6">
			<input type="checkbox" class="selectAllStudentMoveCopyMark" name="selectAllStudentMoveCopyMark[ ]" value="'.$row->grade.'"/>'.$row->gradesec.'
			</div>';
		}
		
		$output .='</div>';
		return $output;
	}
	function fetchThisGradeSubjectMoveCopyMark($academicyear,$grades){
		foreach($grades as $grade){
			$this->db->where('subject.Grade',$grade);
			$this->db->where(array('subject.Academic_Year'=>$academicyear));
			$this->db->order_by('subject.Subj_name','ASC');
			$this->db->group_by('subject.Subj_name');
			$query=$this->db->get('subject');
			$output ='<option>  </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->Subj_name.'">'.$row->Subj_name.'</option>';
			}
			return $output;
		}
	}
	function copyAssesmentQuarterlyMark($gs_branches,$check,$subject,$fromQuarter,$toQuarter,$max_year){
		$output='';
		foreach($check as $grade){
			$queryUser=$this->db->query("select gradesec from users where academicyear='$max_year' and grade='$grade' and usertype='Student' and branch='$gs_branches' group by gradesec ");
			if($queryUser->num_rows()>0){
				$gradeRow=$queryUser->row_array();
				$gradesec=$gradeRow['gradesec'];
				$queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$gs_branches.$gradesec.$fromQuarter.$max_year."' ");
	            if ($queryCheckMark->num_rows()>0){
	            	$queryEvaluation=$this->db->query("select * from evaluation where quarter='$fromQuarter' and academicyear='$max_year' and grade='$grade' group by eid ");
        			if($queryEvaluation->num_rows()>0){
        				foreach($queryEvaluation->result() as $assesName){
        					$eid=$assesName->eid;
        					$evalName=$assesName->evname;
		            		$queryMark=$this->db->query("select * from mark".$gs_branches.$gradesec.$fromQuarter.$max_year." where academicyear='$max_year' and subname='$subject' and mgrade='$gradesec' and quarter='$fromQuarter' and mbranch='$gs_branches' and evaid='$eid' ");
		            		if($queryMark->num_rows()>0){
		            			foreach($queryMark->result() as $markName){
			            			$stuid=$markName->stuid;
			            			$outof=$markName->outof;
			            			$value=$markName->value;
			            			$status=$markName->status;
			            			$lockmark=$markName->lockmark;
			            			$markname=$markName->markname;
			            			$approved=$markName->approved;
			            			$approvedby=$markName->approvedby;
			            			$zeromarkinfo=$markName->zeromarkinfo;
	            					$queryAssesment=$this->db->query("select * from evaluation where quarter='$toQuarter' and academicyear='$max_year' and grade='$grade' and evname='$evalName' group by eid ");
	            					if($queryAssesment->num_rows()>0){
	            						$asseRow=$queryAssesment->row_array();
	            						$eid=$asseRow['eid'];
	            						$queryCheckMark=$this->db->query("select * from mark".$gs_branches.$gradesec.$toQuarter.$max_year." where academicyear='$max_year' and subname='$subject' and mgrade='$gradesec' and quarter='$toQuarter' and mbranch='$gs_branches' and markname='$markname' ");
	            						if($queryCheckMark->num_rows()<1){
		            						$data[]=array(
		            							'stuid'=>$stuid,
		            							'mgrade'=>$gradesec,
		            							'subname'=>$subject,
		            							'evaid'=>$eid,
		            							'quarter'=>$toQuarter,
		            							'outof'=>$outof,
		            							'value'=>$value,
		            							'academicyear'=>$max_year,
		            							'markname'=>$markname,
		            							'status'=>$status,
		            							'lockmark'=>$lockmark,
		            							'approved'=>$approved,
		            							'approvedby'=>$approvedby,
		            							'zeromarkinfo'=>$zeromarkinfo,
		            							'mbranch'=>$gs_branches
		            						);
		            					}
	            					}
	            				}
	            			}
	            		}
	            	}
	        	}else{
	        		$output .='<div class="alert alert-warning alert-dismissible show fade">
			                <div class="alert-body">
			                    <button class="close"  data-dismiss="alert">
			                        <span>&times;</span>
			                    </button>
			                <i class="fas fa-check-circle"> </i> No table found.
			        	</div></div>';
	        	}
	        } 
		}
		if(!empty($data)){
			$query=$this->db->insert_batch('mark'.$gs_branches.$gradesec.$toQuarter.$max_year,$data);
			if($query){
				$output .='<div class="alert alert-success alert-dismissible show fade">
		                <div class="alert-body">
		                    <button class="close"  data-dismiss="alert">
		                        <span>&times;</span>
		                    </button>
		                <i class="fas fa-check-circle"> </i>Data copied successfully.
			    </div></div>';
	        }else{
	        	$output .='<div class="alert alert-warning alert-dismissible show fade">
	                <div class="alert-body">
	                    <button class="close"  data-dismiss="alert">
	                        <span>&times;</span>
	                    </button>
	                <i class="fas fa-check-circle"> </i> ooops Please try again.
	        	</div></div>';
	        }
		}
		return $output;		
	}
	function copyAssesmentMark($gs_branches,$check,$subject,$gs_quarter,$max_year){
		$output='';
		foreach($check as $grade){
			$queryUser=$this->db->query("select gradesec from users where academicyear='$max_year' and grade='$grade' and usertype='Student' and branch='$gs_branches' group by gradesec ");
			if($queryUser->num_rows()>0){
				$gradeRow=$queryUser->row_array();
				$gradesec=$gradeRow['gradesec'];
				$queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$gs_branches.$gradesec.$gs_quarter.$max_year."' ");
	            if ($queryCheckMark->num_rows()>0){
	            	$queryMark=$this->db->query("select * from mark".$gs_branches.$gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and subname='$subject' and mgrade='$gradesec' and quarter='$gs_quarter' and mbranch='$gs_branches' ");
	            	if($queryMark->num_rows()>0){
	            		foreach($queryMark->result() as $markName){
	            			$stuid=$markName->stuid;
	            			$eid=$markName->evaid;
	            			$outof=$markName->outof;
	            			$value=$markName->value;
	            			$status=$markName->status;
	            			$lockmark=$markName->lockmark;
	            			$approved=$markName->approved;
	            			$approvedby=$markName->approvedby;
	            			$zeromarkinfo=$markName->zeromarkinfo;
	            			$queryEvaluation=$this->db->query("select * from evaluation where quarter='$gs_quarter' and academicyear='$max_year' and grade='$grade' and eid!='$eid' group by eid ");
	            			if($queryEvaluation->num_rows()>0){
	            				foreach($queryEvaluation->result() as $assesName){
	            					$evalName=$assesName->evname;
	            					$eid=$assesName->eid;
	            					$queryAssesment=$this->db->query("select sasname from schoolassesment where sasgrade='$grade' and saseval='$evalName' ");
	            					if($queryAssesment->num_rows()>0){
	            						$asseRow=$queryAssesment->row_array();
	            						$sasname=$asseRow['sasname'];
	            						$data[]=array(
	            							'stuid'=>$stuid,
	            							'mgrade'=>$gradesec,
	            							'subname'=>$subject,
	            							'evaid'=>$eid,
	            							'quarter'=>$gs_quarter,
	            							'outof'=>$outof,
	            							'value'=>$value,
	            							'academicyear'=>$max_year,
	            							'markname'=>$sasname,
	            							'status'=>$status,
	            							'lockmark'=>$lockmark,
	            							'approved'=>$approved,
	            							'approvedby'=>$approvedby,
	            							'zeromarkinfo'=>$zeromarkinfo,
	            							'mbranch'=>$gs_branches
	            						);
	            					}
	            					
	            				}
	            			}
	            		}
	            	}
	        	}else{
	        		$output .='<div class="alert alert-warning alert-dismissible show fade">
			                <div class="alert-body">
			                    <button class="close"  data-dismiss="alert">
			                        <span>&times;</span>
			                    </button>
			                <i class="fas fa-check-circle"> </i> No table found.
			        	</div></div>';
	        	}
	        } 
		}
		if(!empty($data)){
			$query=$this->db->insert_batch('mark'.$gs_branches.$gradesec.$gs_quarter.$max_year,$data);
			if($query){
				$output .='<div class="alert alert-success alert-dismissible show fade">
		                <div class="alert-body">
		                    <button class="close"  data-dismiss="alert">
		                        <span>&times;</span>
		                    </button>
		                <i class="fas fa-check-circle"> </i>Data copied successfully.
			    </div></div>';
	        }else{
	        	$output .='<div class="alert alert-warning alert-dismissible show fade">
	                <div class="alert-body">
	                    <button class="close"  data-dismiss="alert">
	                        <span>&times;</span>
	                    </button>
	                <i class="fas fa-check-circle"> </i> ooops Please try again.
	        	</div></div>';
	        }
		}
		return $output;		
	}
	function fetchThisGradeStudentMoveCopyBS($gradesec,$academicyear,$branch){
		$output ='<input type="checkbox" class="" id="selectAllStudentMoveCopyBSGS" onClick="selectAllStudentMoveCopyBS()">Select All<div class="row"> ';
		$this->db->where('gradesec',$gradesec);
		$this->db->where('branch',$branch);
		$this->db->where('academicyear',$academicyear);
		$this->db->order_by('users.fname,users.mname,users.lname','ASC');
		$this->db->group_by('users.id');
		$query=$this->db->get('users');
		
		foreach ($query->result() as $row) {
			$output .='<div class="col-lg-12 col-12">
			<input type="checkbox" class="selectAllStudentMoveCopyBS" name="selectAllStudentMoveCopyBS[ ]" value="'.$row->id.'"/>'.$row->fname.' '.$row->mname.' '.$row->lname.'
			</div>';
		}
		
		$output .='</div>';
		return $output;
	}
	function fetchThisGradeStudentMoveCopyBSGS($gradesec,$academicyear,$branch){
		$output ='<input type="checkbox" class="" id="selectAllStudentMoveCopyBSGSJ" onClick="selectAllStudentMoveCopyBSkill()">Select All<div class="row"> ';
		$this->db->where('gradesec',$gradesec);
		$this->db->where('branch',$branch);
		$this->db->where('academicyear',$academicyear);
		$this->db->order_by('users.fname,users.mname,users.lname','ASC');
		$this->db->group_by('users.id');
		$query=$this->db->get('users');
		
		foreach ($query->result() as $row) {
			$output .='<div class="col-lg-12 col-12">
			<input type="checkbox" class="selectAllStudentMoveCopyBSkill" name="selectAllStudentMoveCopyBSkill[ ]" value="'.$row->id.'"/>'.$row->fname.' '.$row->mname.' '.$row->lname.'
			</div>';
		}
		
		$output .='</div>';
		return $output;
	}
	function copybasicskill($branch,$gradesec,$fromquarter,$toquarter,$checkStudent,$academicyear,$byuser){
		$output='';
		$queryCheckB=$this->db->query("SHOW TABLES LIKE 'basicskillvalue".$gradesec.$academicyear."' ");
        if ($queryCheckB->num_rows()>0){
			foreach($checkStudent as $checkStudents){
				$queryBasicSkill=$this->db->query("select * from basicskillvalue".$gradesec.$academicyear." where academicyear='$academicyear' and quarter='$fromquarter' and stuid='$checkStudents' and bsbranch='$branch' ");
				if($queryBasicSkill->num_rows()>0){
					foreach($queryBasicSkill->result() as $bsValue){
						$bsName=$bsValue->bsname;
						$queryCheck=$this->db->query("select * from basicskillvalue".$gradesec.$academicyear." where academicyear='$academicyear' and quarter='$toquarter' and stuid='$checkStudents' and bsbranch='$branch' and bsname='$bsName' ");
						if($queryCheck->num_rows()<1){
							$data[]=array(
								'stuid'=>$checkStudents,
								'bsname'=>$bsValue->bsname,
								'value'=>$bsValue->value,
								'quarter'=>$toquarter,
								'academicyear'=>$academicyear,
								'datecreated'=>date('M-d-Y'),
								'byuser'=>$byuser,
								'bsgrade'=>$gradesec,
								'bsbranch'=>$branch
							);
						}
					}
				}
			}
			if(!empty($data)){
				$query=$this->db->insert_batch('basicskillvalue'.$gradesec.$academicyear,$data);
				if($query){
					$output .='<div class="alert alert-success alert-dismissible show fade">
		                <div class="alert-body">
		                    <button class="close"  data-dismiss="alert">
		                        <span>&times;</span>
		                    </button>
		                <i class="fas fa-check-circle"> </i> Basic Skill data copied successfully.
		        	</div></div>';
				}else{
					$output .='<div class="alert alert-warning alert-dismissible show fade">
		                <div class="alert-body">
		                    <button class="close"  data-dismiss="alert">
		                        <span>&times;</span>
		                    </button>
		                <i class="fas fa-check-circle"> </i> Ooops Please try again.
		        	</div></div>';
				}
			}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
		                <div class="alert-body">
		                    <button class="close"  data-dismiss="alert">
		                        <span>&times;</span>
		                    </button>
		                <i class="fas fa-check-circle"> </i> No table found.
		        	</div></div>';
		}
		return $output;
	}
	function movebasicskill($branch,$gradesec,$fromquarter,$toquarter,$checkStudent,$academicyear,$byuser){
		$output='';
		$queryCheckB=$this->db->query("SHOW TABLES LIKE 'basicskillvalue".$gradesec.$academicyear."' ");
        if ($queryCheckB->num_rows()>0){
			foreach($checkStudent as $checkStudents){
				$queryBasicSkill=$this->db->query("select * from basicskillvalue".$gradesec.$academicyear." where academicyear='$academicyear' and quarter='$fromquarter' and stuid='$checkStudents' and bsbranch='$branch' ");
				if($queryBasicSkill->num_rows()>0){
					foreach($queryBasicSkill->result() as $bsValue){
						$bsName=$bsValue->bsname;
						$queryCheck=$this->db->query("select * from basicskillvalue".$gradesec.$academicyear." where academicyear='$academicyear' and quarter='$toquarter' and stuid='$checkStudents' and bsbranch='$branch' and bsname='$bsName' ");
						if($queryCheck->num_rows()<1){
							$data=array(
								'stuid'=>$checkStudents,
								'bsname'=>$bsValue->bsname,
								'value'=>$bsValue->value,
								'quarter'=>$toquarter,
								'academicyear'=>$academicyear,
								'datecreated'=>date('M-d-Y'),
								'byuser'=>$byuser,
								'bsgrade'=>$gradesec,
								'bsbranch'=>$branch
							);
							$this->db->where('academicyear',$academicyear);
							$this->db->where('stuid',$checkStudents);
							$this->db->where('bsname',$bsName);
							$this->db->where('quarter',$fromquarter);
							$this->db->where('bsbranch',$branch);
							$query=$this->db->update('basicskillvalue'.$gradesec.$academicyear,$data);
						}
					}
				}
			}
			if(!empty($data)){
				if($query){
					$output .='<div class="alert alert-success alert-dismissible show fade">
		                <div class="alert-body">
		                    <button class="close"  data-dismiss="alert">
		                        <span>&times;</span>
		                    </button>
		                <i class="fas fa-check-circle"> </i> Basic Skill data moved successfully.
		        	</div></div>';
				}else{
					$output .='<div class="alert alert-warning alert-dismissible show fade">
		                <div class="alert-body">
		                    <button class="close"  data-dismiss="alert">
		                        <span>&times;</span>
		                    </button>
		                <i class="fas fa-check-circle"> </i> Ooops Please try again.
		        	</div></div>';
				}
			}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
		                <div class="alert-body">
		                    <button class="close"  data-dismiss="alert">
		                        <span>&times;</span>
		                    </button>
		                <i class="fas fa-check-circle"> </i> No table found.
		        	</div></div>';
		}
		return $output;
	}
	function fetchThisBranchGrade($branch,$academicyear){
		$output ='<div class="row"> ';
		foreach($branch as $branchs){
			$this->db->where('branch',$branchs);
			$this->db->where('usertype','Student');
			$this->db->where('academicyear',$academicyear);
			$this->db->order_by('users.grade','ASC');
			$this->db->group_by('users.grade');
			$query=$this->db->get('users');
			
			foreach ($query->result() as $row) {
				$output .='<div class="col-lg-4 col-6">
				<input type="checkbox" class="gradeListForLockMarkList" name="gradecListForLockMarkList[ ]" value="'.$row->grade.'"/>'.$row->grade.' 
				<small id="lockGradeInfo'.$row->grade.'"></small>
				</div>';
			}
		}
		$output .='</div>';
		return $output;
	}
	function lockThisBranchMark($branch,$max_year){
		foreach($branch as $branchs){
			$queryTerm=$this->db->query("select term from quarter where Academic_year='$max_year' group by term ");
            if($queryTerm->num_rows()>0){
            	$queryGradeSec=$this->db->query("select gradesec from users where academicyear='$max_year' and branch='$branchs' ");
        		if($queryGradeSec->num_rows()>0){
            		foreach($queryGradeSec->result() as $gradesecs){
            			$gradesec=$gradesecs->gradesec;
	                    foreach($queryTerm->result() as $termName){
	                        $max_quarter=$termName->term;
	                        $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branchs.$gradesec.$max_quarter.$max_year."' ");
	                        if ($queryCheckMark->num_rows()>0)
	                        {
	                            $this->db->where('lockmark','0');
	                            $this->db->set('lockmark','1');
	                            $queryUpdate=$this->db->update('mark'.$branchs.$gradesec.$max_quarter.$max_year);
	                        }
	                    }
	                }
                }
            }		
		}
	}
	function unlockThisBranchMark($branch,$max_year){
		foreach($branch as $branchs){
			$queryTerm=$this->db->query("select term from quarter where Academic_year='$max_year' group by term ");
            if($queryTerm->num_rows()>0){
            	$queryGradeSec=$this->db->query("select gradesec from users where academicyear='$max_year' and branch='$branchs' ");
        		if($queryGradeSec->num_rows()>0){
            		foreach($queryGradeSec->result() as $gradesecs){
            			$gradesec=$gradesecs->gradesec;
	                    foreach($queryTerm->result() as $termName){
	                        $max_quarter=$termName->term;
	                        $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branchs.$gradesec.$max_quarter.$max_year."' ");
	                        if ($queryCheckMark->num_rows()>0)
	                        {
	                            $this->db->where('lockmark','1');
	                            $this->db->set('lockmark','0');
	                            $queryUpdate=$this->db->update('mark'.$branchs.$gradesec.$max_quarter.$max_year);
	                        }
	                    }
	                }
                }
            }		
		}
	}
	function lockThisGradeMark($branch,$checkGrade,$max_year){
		foreach($branch as $branchs){
			foreach($checkGrade as $checkGrades){
				$queryTerm=$this->db->query("select term from quarter where Academic_year='$max_year' group by term ");
                if($queryTerm->num_rows()>0){
                	$queryGradeSec=$this->db->query("select gradesec from users where academicyear='$max_year' and grade='$checkGrades' ");
            		if($queryGradeSec->num_rows()>0){
	            		foreach($queryGradeSec->result() as $gradesecs){
	            			$gradesec=$gradesecs->gradesec;
		                    foreach($queryTerm->result() as $termName){
		                        $max_quarter=$termName->term;
		                        $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branchs.$gradesec.$max_quarter.$max_year."' ");
		                        if ($queryCheckMark->num_rows()>0)
		                        {
		                            $this->db->where('lockmark','0');
		                            $this->db->set('lockmark','1');
		                            $queryUpdate=$this->db->update('mark'.$branchs.$gradesec.$max_quarter.$max_year);
		                        }
		                    }
		                }
	                }
                }	
			}
		}
	}
	function unlockThisGradeMark($branch,$checkGrade,$max_year){
		foreach($branch as $branchs){
			foreach($checkGrade as $checkGrades){
				$queryTerm=$this->db->query("select term from quarter where Academic_year='$max_year' group by term ");
                if($queryTerm->num_rows()>0){
                	$queryGradeSec=$this->db->query("select gradesec from users where academicyear='$max_year' and grade='$checkGrades' ");
            		if($queryGradeSec->num_rows()>0){
	            		foreach($queryGradeSec->result() as $gradesecs){
	            			$gradesec=$gradesecs->gradesec;
		                    foreach($queryTerm->result() as $termName){
		                        $max_quarter=$termName->term;
		                        $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branchs.$gradesec.$max_quarter.$max_year."' ");
		                        if ($queryCheckMark->num_rows()>0)
		                        {
		                            $this->db->where('lockmark','1');
		                            $this->db->set('lockmark','0');
		                            $queryUpdate=$this->db->update('mark'.$branchs.$gradesec.$max_quarter.$max_year);
		                        }
		                    }
		                }
	                }
                }	
			}
		}
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
	function lockThisSectionMark($branch,$checkGradesec,$max_year){
		foreach($branch as $branchs){
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
	}
	function UnlockThisSectionMark($branch,$checkGradesec,$max_year){
		foreach($branch as $branchs){
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
	}
	function fetchThisGradeStudentIdcardDirector($grade,$academicyear,$mybranch){
		$output ='<input type="checkbox" class="" id="selectallStudentList" onClick="selectAllStudent()">Select All<div class="row"> ';
		foreach($grade as $grades){
			$this->db->where('grade',$grades);
			$this->db->where('branch',$mybranch);
			$this->db->where('academicyear',$academicyear);
			$this->db->order_by('users.fname,users.mname,users.lname','ASC');
			$this->db->group_by('users.id');
			$query=$this->db->get('users');
			
			foreach ($query->result() as $row) {
				$output .='<div class="col-lg-12 col-12">
				<input type="checkbox" class="studentListTransportService" name="studentListTransportService[ ]" value="'.$row->id.'"/>'.$row->fname.' '.$row->mname.' '.$row->lname.'
				</div>';
			}
		}
		$output .='</div>';
		return $output;
	}
	function fetch_servicePlace_branch($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where('users.transportservice !=','');
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.transportservice','ASC');
		$this->db->group_by('users.transportservice');
		$query=$this->db->get('users');
		$output ='<div class="row">';
		$output ='<input type="checkbox" class="" id="selectallServicePlaceList" onClick="selectAllPlaceList()">Select All<div class="row"> ';
		foreach ($query->result() as $row) { 
			$output .='<div class="col-lg-6 col-12">
			<input type="checkbox" class="studentServicePlace" name="studentServicePlace[ ]" value="'.$row->transportservice.'"/>'.$row->transportservice.' </div>';
		}
		$output .='</div>';
	    return $output;
	}
	function fetch_servicePlace_branchReport($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where('users.transportservice !=','');
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.transportservice','ASC');
		$this->db->group_by('users.transportservice');
		$query=$this->db->get('users');
		$output ='<div class="row">';
		$output ='<input type="checkbox" class="" id="selectallServicePlaceListReport" onClick="selectAllPlaceListRecord()">Select All<div class="row"> ';
		foreach ($query->result() as $row) { 
			$output .='<div class="col-lg-6 col-12">
			<input type="checkbox" class="studentServicePlaceReport" name="studentServicePlaceReport[ ]" value="'.$row->transportservice.'"/>'.$row->transportservice.' </div>';
		}
		$output .='</div>';
	    return $output;
	}
	function fetch_servicePlace_branchReportSection($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where('users.transportservice !=','');
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.transportservice','ASC');
		$this->db->group_by('users.transportservice');
		$query=$this->db->get('users');
		$output ='<div class="row">';
		$output ='<input type="checkbox" class="" id="selectallServicePlaceListSectionReport" onClick="selectAllPlaceListRecordSection()">Select All<div class="row"> ';
		foreach ($query->result() as $row) { 
			$output .='<div class="col-lg-6 col-12">
			<input type="checkbox" class="studentServicePlaceSectionReport" name="studentServicePlaceSectionReport[ ]" value="'.$row->transportservice.'"/>'.$row->transportservice.' </div>';
		}
		$output .='</div>';
	    return $output;
	}
	function fetchQuarterFromAcademicYear($max_year){
		$this->db->where(array('Academic_year'=>$max_year));
		$this->db->order_by('term','ASC');
		$this->db->group_by('term');
		$query=$this->db->get('quarter');
		$output ='';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->term.'">'.$row->term.'</option>';
		}
	    return $output;
	}
	function fetchOnlyGradeFromBranch($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.grade','ASC');
		$this->db->group_by('users.grade');
		$query=$this->db->get('users');
		$output ='<option></option>';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->grade.'">'.$row->grade.'</option>';
		}
	    return $output;
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
	function fetch_grade_from_branch4rank($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.gradesec','ASC');
		$this->db->group_by('users.gradesec');
		$query=$this->db->get('users');
		$output ='<option>All</option>';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->gradesec.'">'.$row->gradesec.'</option>';
		}
	    return $output;
	}
	function filterGradeFromBranch4Rank($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.gradesec','ASC');
		$this->db->group_by('users.gradesec');
		$query=$this->db->get('users');
		$output ='<option> </option>';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->gradesec.'">'.$row->gradesec.'</option>';
		}
	    return $output;
	}
	function filterGradeFromBranch4Rankgs($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where('users.usertype','Student');
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.gradesec','ASC');
		$this->db->group_by('users.gradesec');
		$query=$this->db->get('users');
		$output ='';
		$output.='<div class="row">';
		foreach ($query->result() as $row) {
			$output.='<div class="col-lg-4 col-6">
                <div class="pretty p-icon p-jelly p-round p-bigger">
                  	<input type="checkbox" name="sectionRankGrandstande" value="'.$row->gradesec.'" class="sectionRankGrandstande" id="customCheck1">'.$row->gradesec.'
                  	<div class="state p-info">
                    	<i class="icon material-icons"></i>
                    	<label></label>
                  	</div>
                </div>
            </div>'; 
		}
		$output.='</div>';
	    return $output;
	}
	function fetch_grade_from_branch_4statistics($branch,$max_year)
	{
		$this->db->where('users.branch',$branch);
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.grade','ASC');
		$this->db->group_by('users.grade');
		$query=$this->db->get('users');
		$output ='';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->grade.'">'.$row->grade.'</option>';
		}
	    return $output;
	}
	function fetchGradeFromBranch($branch,$max_year)
	{
		$this->db->where('users.usertype','Student');
		$this->db->where('users.grade!=','');
		$this->db->where('users.status','Active');
		$this->db->where('users.branch',$branch);
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.grade','ASC');
		$this->db->group_by('users.grade');
		$query=$this->db->get('users');
		$output ='';
		foreach ($query->result() as $row) { 
			$output .='<input id="grade_statistics" type="checkbox" value="'.$row->grade.'">'.$row->grade.'&nbsp;&nbsp;';
		}
	    return $output;
	}
	function fetchSubjectFromBranch4Statistics($grade,$max_year)
	{
		$this->db->where('subject.Grade',$grade);
		$this->db->where(array('subject.Academic_Year'=>$max_year));
		$this->db->order_by('subject.Subj_name','ASC');
		$this->db->group_by('subject.Subj_name');
		$query=$this->db->get('subject');
		$output ='';
		foreach ($query->result() as $row) { 
			$output .='<input type="checkbox" value="'.$row->Subj_name.'">'.$row->Subj_name.'&nbsp;&nbsp;';
		}
	    return $output;
	}
	function fetch_subject_from_branch_4statistics($grade,$max_year)
	{
		$this->db->where('subject.Grade',$grade);
		$this->db->where(array('subject.Academic_Year'=>$max_year));
		$this->db->order_by('subject.Subj_name','ASC');
		$this->db->group_by('subject.Subj_name');
		$query=$this->db->get('subject');
		$output ='';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->Subj_name.'">'.$row->Subj_name.'</option>';
		}
	    return $output;
	}
	function fetch_transcript_grade($branch,$max_year)
	{
		$this->db->where('users.branch',$branch);
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.gradesec','ASC');
		$this->db->group_by('users.gradesec');
		$query=$this->db->get('users');
		$output ='';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->gradesec.'">'.$row->gradesec.'</option>';
		}
	    return $output;
	}
	function fetch_thisgrade_from_branch($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.grade','ASC');
		$this->db->group_by('users.grade');
		$query=$this->db->get('users');
		$output ='';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->grade.'">'.$row->grade.'</option>';
		}
	    return $output;
	}
	function fetch_thisgrade_from_branchNow($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->where('users.usertype','Student');
		$this->db->order_by('users.grade','ASC');
		$this->db->group_by('users.grade');
		$query=$this->db->get('users');
		$output ='<div class="row">';
		foreach ($query->result() as $row) {
			$output.='<div class="col-lg-4 col-6">
                <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input type="checkbox" class="summaryGSGrade" name="summaryGSGrade" value="'.$row->grade.'" id="customCheck1 summaryGSGrade"> '.$row->grade.'
                    <div class="state p-info">
                      <i class="icon material-icons"></i>
                      <label></label>
                    </div>
                </div>
            </div>'; 
		}
		$output.='</div>';
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
	                <div class="pretty p-icon p-jelly p-round p-bigger">
	                    <input type="checkbox" class="summaryGSAge" name="summaryGSAge" value="'.$row->age.'" id="customCheck1 summaryGSAge"> '.$row->age.'
	                    <div class="state p-info">
	                      <i class="icon material-icons"></i>
	                      <label></label>
	                    </div>
	                </div>
	            </div>'; 
			}
			$output.='</div>';
		}
	    return $output;
	}
	function fetch_thissection_from_branchNow($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->where('users.usertype','Student');
		$this->db->order_by('users.gradesec','ASC');
		$this->db->group_by('users.gradesec');
		$query=$this->db->get('users');
		$output ='<div class="row">';
		foreach ($query->result() as $row) {
			$output.='<div class="col-lg-6">
                <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input type="checkbox" name="summaryGSGradeSec" value="'.$row->gradesec.'" id="customCheck1 summaryGSGradeSec"> '.$row->gradesec.'
                    <div class="state p-info">
                      <i class="icon material-icons"></i>
                      <label></label>
                    </div>
                </div>
            </div>'; 
		}
		$output.='</div>';
	    return $output;
	}
	function FilterBranchGrade($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.grade','ASC');
		$this->db->group_by('users.grade');
		$query=$this->db->get('users');
		$output ='';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->grade.'">'.$row->grade.'</option>';
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
	function fetch_grade_mark($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year)
	{
		$output='';
		$query=$this->db->query("select id,username,fname,mname,lname from users where branch='$gs_branches' and academicyear='$max_year' and gradesec='$gs_gradesec' and status='Active' and usertype='Student' and isapproved='1' and branch='$gs_branches' order by fname,mname,lname ASC ");

		$markname_query=$this->db->query("select ma.lockmark, ma.evaid, ma.markname,ma.mid, ma.value, ma.outof,sum(outof) as total_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." as ma where ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$gs_quarter' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' group by ma.markname order by ma.mid ASC ");
		if($markname_query->num_rows()>0)
		{   
			$output.='<h6><small>Grade :</small>'.
			$gs_gradesec.' <small>Quarter :</small>'.
			$gs_quarter.'<small> Subject :</small> '.
			$gs_subject.'</h6>';
			$output.='<button class="btn btn-default delete_selected_grade pull-right">
			<span class="text-danger"><i class="fas fa-trash-alt"></i> Delete Grade '.$gs_gradesec.'</span> </button>';
			$output.='<button class="btn btn-default delete_selected pull-right">
			<span class="text-warning"><i class="fas fa-trash"></i> Delete '.$gs_subject.'</span> </button>';
			$uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='lockstudentmark' order by id ASC ");
			if($uperStuDE->num_rows() > 0 ){
				$output.='<button class="btn btn-default lock_selected"><span class="text-info"><i class="fas fa-lock"></i> Lock / Unlock '.$gs_subject.'</span></button>';
				$output.='<button class="btn btn-default lock_selected_grade"><span class="text-success"><i class="fas fa-lock"></i> Lock / Unlock Grade '.$gs_gradesec.'</span></button>';
			}
			
			$output.='<div class="table-responsive">
    		<table class="table table-borderedr table-hover" style="width:100%;">
    		<tr> <th rowspan="2" class="text-center">Student Name</th> 
        	<th rowspan="2" class="text-center">Student ID</th>';
        	foreach ($markname_query->result_array() as $mark_name) {
        		$output.='<th class="coreMarkName'.$mark_name['markname'].'">' .$mark_name['markname'].'';
        		if($mark_name['lockmark']=='0'){
	        		$output.='<div class="table-links"> 
						<a href="#" value="'.$mark_name['markname'].'" 
						class="gs_delete_markname"> 
						<span class="text-danger"><i class="fas fa-trash"></i> </span> </a>
						<a href="#" value="'.$mark_name['markname'].'" 
						class="gs_edit_markname" data-toggle="modal" 
									data-target="#editmarkName"> 
						<span class="text-success">
						<small> <i class="fas fa-pen"></i> </small> </span>
						</a>
				    </div> </th>';
				}
        	}
        	$output.=' </tr><tr>';
			foreach ($markname_query->result_array() as $mark_name) 
			{
        		$output.='<td class="coreOutOF'.$mark_name['outof'].$mark_name['markname'].'">'.$mark_name['outof'].' ';
        		if($mark_name['lockmark']=='0'){
	        		$output.=' <div class="table-links">
						<a href="#" id="'.$mark_name['outof'].'" value="'.$mark_name['markname'].'" 
						class="gs_edit_outof" data-toggle="modal" 
									data-target="#editOutOf"> 
						<span class="text-success">
						<small> <i class="fas fa-pen"></i> </small> </span>
						</a>
				    </div></td>';
				}
        	}
        	$output.='</tr>';
        	$output.='<input type="hidden" class="jo_gradesec" value="'.$gs_gradesec.'">
			<input type="hidden" class="jo_subject" value="'.$gs_subject.'">
			<input type="hidden" class="jo_quarter" value="'.$gs_quarter.'">
			<input type="hidden" class="jo_branch" value="'.$gs_branches.'">
			<input type="hidden" class="jo_year" value="'.$max_year.'">';
			foreach ($query->result_array() as $row) 
			{ 
        		$id=$row['id'];
        		$output.='<tr> <td> '.$row['fname'].' '.$row['mname'].' '.$row['lname'].'
				<div class="table-links"> 
					<a href="#" value="'.$id.'" class="lock_me">
						<span class="text-info">
						Lock /Unlock</span>
					</a>
			    </div> </td><td>'.$row['username'].'</td>';
        		foreach ($markname_query->result_array() as $mark_name)
        		{
        			$Evaid=$mark_name['evaid'];
        			$outOFF=$mark_name['outof'];
        			$markname=$mark_name['markname'];
        			$lockmark1=$mark_name['lockmark'];
        			$query_value = $this->db->query("select lockmark,value, outof,mid, markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where markname='$markname' and stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' ");
        			if($query_value->num_rows()>0)
        			{
        				$mark_value=$query_value->row_array();
    					$outof=$mark_value['outof'];
    					$value=$mark_value['value'];
    					$lockmark=$mark_value['lockmark'];
						if($outof >= $value){
							$output.='<td class="jossMark'.$mark_value['mid'].'">'.$mark_value['value'].'<small class="text-muted">('.$mark_value['markname'].')</small>';
							if($lockmark==='0'){
								$output.='<div class="table-links"> <a href="#" value="'.$mark_value['mid'].'" class="edit_mark_gs" data-toggle="modal"
								data-target="#editmark">
								<span class="text-success">
								<i class="far fa-edit"> </i></span></a>
								   </div>';
                         	}else{
                         		$output.='<div  class="table-links"> 
                         			<span class="text-warning"><i class="fas fa-lock"> </i> </span>
								   </div>';
                         	}
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
        				if($lockmark1=='0'){
							$output.='<td class="JoMark'.$id.$markname.'">
							<input type="hidden" value="" class="my_ID">
							<span class="text-danger"> NG</span>
							<div class="table-links"> 
								<a href="#" title="'.$id.'" id="'.$outOFF.'" name="'.$markname.'" value="'.$Evaid.'" class="edit_NGmark_gs" data-toggle="modal" 
								data-target="#editngmark"><span class="text-info"> 
								<i class="fas fa-plus"></i> 
								</span></a>
							</div>
							</td>';
						}else{
							$output.='<td class="JoMark'.$id.'">
							<input type="hidden" value="" class="my_ID">
							<span class="text-danger"> NG</span>
							<div class="table-links"> 
								<span class="text-warning"><i class="fas fa-lock"> </i> </span>
							</div>
							</td>';
						}
					}
        		}
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
	/*function fetch_grade_mark_4teacher($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year){
		$output='';
		$query=$this->db->query("select id,username,fname,mname,lname from users where branch='$gs_branches' and academicyear='$max_year' and gradesec='$gs_gradesec' and status='Active' and usertype='Student' and isapproved='1' and branch='$gs_branches' order by fname,mname ASC ");

		$markname_query=$this->db->query("select ma.evaid, ma.markname, ma.mid,ma.value, ma.outof,sum(outof) as total_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." as ma where ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$gs_quarter' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' group by ma.markname order by ma.mid ASC ");
		if($markname_query->num_rows()>0)
		{   
			$output.='<h6><small>Grade :</small>'.
			$gs_gradesec.' <small>Quarter :</small>'.
			$gs_quarter.'<small> Subject :</small> '.
			$gs_subject.'</h6>';
			$output.='<button class="btn btn-default delete_selected_grade pull-right">';
			$output.='<button class="btn btn-default delete_selected pull-right">
			<span class="text-warning"><i class="fas fa-trash"></i> Delete '.$gs_subject.'</span> </button>';
			
			$output.='<div class="table-responsive">
    		<table class="table table-borderedr table-hover" style="width:100%;">
    		<tr> <th rowspan="2" class="text-center">Student Name</th> 
        	<th rowspan="2" class="text-center">Student ID</th>';
        	foreach ($markname_query->result_array() as $mark_name) {
        		$output.='<th>' .$mark_name['markname'].'
        		<div class="table-links"> 
					<a href="#" value="'.$mark_name['markname'].'" 
					class="gs_delete_markname"> 
					<span class="text-danger">
					<small> <i class="fas fa-trash"></i> </small> </span>
					</a>
			    </div> </th>';
        	}
        	$output.=' </tr><tr>';
			foreach ($markname_query->result_array() as $mark_name) 
			{
        		$output.='<td><small>'.$mark_name['outof'].'</small></td>';
        	}
        	$output.='</tr>';
        	$output.='<input type="hidden" class="jo_gradesec" value="'.$gs_gradesec.'">
			<input type="hidden" class="jo_subject" value="'.$gs_subject.'">
			<input type="hidden" class="jo_quarter" value="'.$gs_quarter.'">
			<input type="hidden" class="jo_branch" value="'.$gs_branches.'">
			<input type="hidden" class="jo_year" value="'.$max_year.'">';
			foreach ($query->result_array() as $row) 
			{ 
        		$id=$row['id'];
        		$output.='<tr> <td> '.$row['fname'].' '.$row['mname'].' '.$row['lname'].'
				<div class="table-links"> 
					<a href="#" value="'.$id.'" class="lock_me">
						<span class="text-info">
						Lock /Unlock</span>
					</a>
			    </div> </td><td>'.$row['username'].'</td>';
        		foreach ($markname_query->result_array() as $mark_name)
        		{
        			$Evaid=$mark_name['evaid'];
        			$outOFF=$mark_name['outof'];
        			$markname=$mark_name['markname'];
        			$query_value = $this->db->query("select lockmark,value,outof,mid, markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where markname='$markname' and stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' ");
        			if($query_value->num_rows()>0)
        			{
        				$mark_value=$query_value->row_array();
    					$outof=$mark_value['outof'];
    					$value=$mark_value['value'];
    					$lockmark=$mark_value['lockmark'];
						if($outof >= $value){
							$output.='<td class="jossMark'.$mark_value['mid'].'">'.$mark_value['value'].'';
							if($lockmark==='0'){
								$output.='<div class="table-links"> <a href="#" value="'.$mark_value['mid'].'" class="edit_mark_gs" data-toggle="modal"
								data-target="#editmark">
								<span class="text-success">
								<i class="far fa-edit"> </i></span></a>
								   </div>';
                         	}else{
                         		$output.='<div  class="table-links"> 
                         			<span class="text-warning"><i class="fas fa-lock"> </i> </span>
								   </div>';
                         	}
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
						$output.='<td class="JoMark'.$id.$markname.'">
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
        		}
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
	}*/
	function FetchUpdatedMark($mid,$quarter,$gradesec,$year,$branch){
		$this->db->where('mid',$mid);
		$query=$this->db->get('mark'.$branch.$gradesec.$quarter.$year);
		$output='';
		foreach ($query->result() as $keyvalue) {
			$output.=''.$keyvalue->value.'';
		}
		return $output;
	}
	function fetch_my_markresult($gs_subject,$gs_quarter,$max_year,$id,$gs_gradesec,$gs_branches)
	{
		$output='';
		$this->db->where(array('users.academicyear'=>$max_year));
			$this->db->where(array('users.gradesec'=>$gs_gradesec));
			$this->db->where(array('users.branch'=>$gs_branches));
			$this->db->where(array('users.id'=>$id));
			$this->db->order_by('users.fname,users.mname,users.lname');
			$query=$this->db->get('users');
			$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
					$output.='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';
			if($query->num_rows()>0)
			{
				$markname_query=$this->db->query("select ma.markname,ma.value,ma.outof,sum(outof) as total_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."
				as ma cross join users as us where us.id=ma.stuid and ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ma.markname order by ma.mid ASC ");

				$evalname_query=$this->db->query("select ev.evname,ev.eid,ev.percent,sum(ev.percent) as summ_percent from evaluation as ev cross join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");
				$output .='<div class="table-responsive">
        		<table class="tabler table-borderedr table-hover" style="width:100%;height:92%;page-break-inside:avoid;">
        		<thead>
        		<tr>
            	<th>Name</th><th>Student ID</th>';
            	foreach ($markname_query->result() as $mark_name) {
            		$output .='<th>'.$mark_name->markname.'</th>';
            	}
            	foreach ($evalname_query->result() as $evalua_name) {
            		$output .='<th>Tot '.$evalua_name->evname.'</th>';
            		$output .='<th><B>Conv '.$evalua_name->evname.'</B></th>';
            	}
            	$output .='<th><B>Total</B></th>
        		</tr>
        		</thead>';
        		$output .='<tr><td></td> <td></td>';
				foreach ($markname_query->result() as $mark_name) 
				{
            		$output .='<td><small>'.$mark_name->outof.'</small></td>';
            	}
            	$average=0;
            	foreach ($evalname_query->result() as $evalua_name) 
            	{
            		$average=$evalua_name->summ_percent;
            		$eid = $evalua_name->eid;
        		    $query_outof_sum = $this->db->query("select m.value,sum(outof) as sum_outof, m.outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."
        		    as m cross join users as us where us.id=m.stuid and us.gradesec='$gs_gradesec' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$eid' group by stuid order by mid");
        		    $sum_outof=0;
        		    foreach ($query_outof_sum->result() as $keyvalue) 
        		    {
        		    	$sum_outof= $keyvalue->sum_outof;
        		    }
        		    $output .='<td>'.$sum_outof.'</td>';
        		    $output .='<td><B>'.$evalua_name->percent.'</B></td>';
            	}
            	$output .='<td><B>100</B></td>';
            	$output .='</tr>';
				foreach ($query->result() as $row) { 
            		$id=$row->id;
            		$output .='<tr>
						<td>'.$row->fname.' '.$row->mname.'</td><td>'.$row->username.'</td>';
            		foreach ($markname_query->result() as $mark_name)
            		{
            			$mname_gs=$mark_name->markname;
	            		$query_value = $this->db->query("select value from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."
	            		where  stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and markname='$mname_gs' order by mid ASC");
						if($query_value->num_rows()>0){
							foreach ($query_value->result() as $mark_value)
							{
		            			$output .='<td>'.$mark_value->value.'</td>';
		            		}
		            	}else{
		            		$output .='<td><span class="text-danger">NG</span></td>';
		            	}
	            	}
            		$average=0;
            		foreach ($evalname_query->result() as $evalua_name) 
            		{
            			$evaid= $evalua_name->eid;
                		$percent= $evalua_name->percent;
                		$query_sum = $this->db->query("select sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."
                		where  stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$evaid' order by mid ASC");
                		foreach ($query_sum->result() as $sum_value)
                		{
                				if($sum_value->outof != 0){
                        			$conver= ($sum_value->total *$percent )/$sum_value->outof;
                    			}
                    		if($sum_value->total!=0){
                    			$output .='<td>'.$sum_value->total.'</td>';
                    		}else{
                    			$output .='<td>-</td>';
                    		}
                		
                			if($sum_value->outof == 0){
                				$output .='<td>-</td>';
                			}else{
                				$output .='<td><B>'.number_format((float)$conver,2,'.','').'</B></td>';
                				$average =$conver + $average;
                			}
                		}
            		}$output .='<td><B>'.number_format((float)$average,2,'.','').'</B></td>';
					$output .='</tr>';
				}
				$output .='</table></div>';
				$output.='<p class="text-center">'.$school_slogan.'!</p>';
			}else{
	    		$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                	<i class="fas fa-check-circle"> </i> Data Not Found.
            	</div></div>';
			}
		return $output;
	}
	function fetch_my_markresultApproved($gs_subject,$gs_quarter,$max_year,$id,$gs_gradesec,$gs_branches)
	{
		$output='';
		$this->db->where(array('users.academicyear'=>$max_year));
			$this->db->where(array('users.gradesec'=>$gs_gradesec));
			$this->db->where(array('users.branch'=>$gs_branches));
			$this->db->where(array('users.id'=>$id));
			$this->db->order_by('users.fname');
			$this->db->order_by('users.mname');
			$this->db->order_by('users.lname');
			$query=$this->db->get('users');
			$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
					$output.='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';
			if($query->num_rows()>0)
			{
				$markname_query=$this->db->query("select ma.markname,ma.value,ma.outof,sum(outof) as total_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."
				as ma cross join users as us where us.id=ma.stuid and ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' and approved='1' group by ma.markname order by ma.mid ASC ");

				$evalname_query=$this->db->query("select ev.evname,ev.eid,ev.percent,sum(ev.percent) as summ_percent from evaluation as ev cross join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");
				$output .='<div class="table-responsive">
        		<table class="tabler table-borderedr table-hover" style="width:100%;height:92%;page-break-inside:avoid;">
        		<thead>
        		<tr>
            	<th>Name</th><th>Student ID</th>';
            	foreach ($markname_query->result() as $mark_name) {
            		$output .='<th>'.$mark_name->markname.'</th>';
            	}
            	foreach ($evalname_query->result() as $evalua_name) {
            		$output .='<th>Tot '.$evalua_name->evname.'</th>';
            		$output .='<th><B>Conv '.$evalua_name->evname.'</B></th>';
            	}
            	$output .='<th><B>Total</B></th>
        		</tr>
        		</thead>';
        		$output .='<tr><td></td> <td></td>';
				foreach ($markname_query->result() as $mark_name) 
				{
            		$output .='<td><small>'.$mark_name->outof.'</small></td>';
            	}
            	$average=0;
            	foreach ($evalname_query->result() as $evalua_name) 
            	{
            		$average=$evalua_name->summ_percent;
            		$eid = $evalua_name->eid;
        		    $query_outof_sum = $this->db->query("select m.value,sum(outof) as sum_outof, m.outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."
        		    as m cross join users as us where us.id=m.stuid and us.gradesec='$gs_gradesec' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$eid' and approved='1' group by stuid order by mid");
        		    $sum_outof=0;
        		    foreach ($query_outof_sum->result() as $keyvalue) 
        		    {
        		    	$sum_outof= $keyvalue->sum_outof;
        		    }
        		    $output .='<td>'.$sum_outof.'</td>';
        		    $output .='<td><B>'.$evalua_name->percent.'</B></td>';
            	}
            	$output .='<td><B>100</B></td>';
            	$output .='</tr>';
				foreach ($query->result() as $row) { 
            		$id=$row->id;
            		$output .='<tr>
						<td>'.$row->fname.' '.$row->mname.'</td><td>'.$row->username.'</td>';
            		foreach ($markname_query->result() as $mark_name)
            		{
            			$mname_gs=$mark_name->markname;
	            		$query_value = $this->db->query("select value from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."
	            		where  stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and markname='$mname_gs' and approved='1' order by mid ASC");
						if($query_value->num_rows()>0){
							foreach ($query_value->result() as $mark_value)
							{
		            			$output .='<td>'.$mark_value->value.'</td>';
		            		}
		            	}else{
		            		$output .='<td><span class="text-danger">NG</span></td>';
		            	}
	            	}
            		$average=0;
            		foreach ($evalname_query->result() as $evalua_name) 
            		{
            			$evaid= $evalua_name->eid;
                		$percent= $evalua_name->percent;
                		$query_sum = $this->db->query("select sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."
                		where  stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$evaid' and approved='1' order by mid ASC");
                		foreach ($query_sum->result() as $sum_value)
                		{
                				if($sum_value->outof != 0){
                        			$conver= ($sum_value->total *$percent )/$sum_value->outof;
                    			}
                    		if($sum_value->total!=0){
                    			$output .='<td>'.$sum_value->total.'</td>';
                    		}else{
                    			$output .='<td>-</td>';
                    		}
                		
                			if($sum_value->outof == 0){
                				$output .='<td>-</td>';
                			}else{
                				$output .='<td><B>'.number_format((float)$conver,2,'.','').'</B></td>';
                				$average =$conver + $average;
                			}
                		}
            		}$output .='<td><B>'.number_format((float)$average,2,'.','').'</B></td>';
					$output .='</tr>';
				}
				$output .='</table></div>';
				$output.='<p class="text-center">'.$school_slogan.'!</p>';
			}else{
	    		$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                	<i class="fas fa-check-circle"> </i> Data Not Found.
            	</div></div>';
			}
		return $output;
	}
	function fetchDashboardMarkResultApproved($grade,$gs_quarter,$max_year,$id,$gs_gradesec,$gs_branches)
	{
		$output='';
		$querySubject=$this->db->query("select * from subject where Academic_Year='$max_year' and Grade='$grade' group by Subj_name order by suborder ");
		if($querySubject->num_rows()>0){
			$output.='<span class="StudentViewTextInfo">Season :'.$gs_quarter.'</span>';
			foreach($querySubject->result() as $stuSubject){
				$gs_subject=$stuSubject->Subj_name;
				$markname_query=$this->db->query("select ma.markname,ma.value,ma.outof,sum(outof) as total_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." as ma cross join users as us where us.id=ma.stuid and ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' and approved='1' group by ma.markname order by ma.mid ASC ");
				foreach ($markname_query->result() as $mark_name)
        		{
        			$mname_gs=$mark_name->markname;
            		$query_value = $this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and markname='$mname_gs' and approved='1' order by mid ASC");
					if($query_value->num_rows()>0){
						foreach ($query_value->result() as $mark_value)
						{
	            			$output .='<p>'.$mark_value->subname.'=>'.$mark_value->markname.'=><span class="badge badge-info">'.$mark_value->value.'/'.$mark_value->outof.'</span></p>';
	            		}
	            	}else{
	            		$output .='<p class="text-danger">NG</p>';
	            	}
            	}
			}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                	<i class="fas fa-check-circle"> </i> Subject not found.
            	</div></div>';
		}
		return $output;
	}
	function fetchDashboardMarkResult($grade,$gs_quarter,$max_year,$id,$gs_gradesec,$gs_branches)
	{
		$output='';
		$querySubject=$this->db->query("select * from subject where Academic_Year='$max_year' and Grade='$grade' group by Subj_name order by suborder ");
		if($querySubject->num_rows()>0){
			$output.='<span class="StudentViewTextInfo">Season :'.$gs_quarter.'</span>';
			foreach($querySubject->result() as $stuSubject){
				$gs_subject=$stuSubject->Subj_name;
				$markname_query=$this->db->query("select ma.markname,ma.value,ma.outof,sum(outof) as total_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." as ma cross join users as us where us.id=ma.stuid and ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ma.markname order by ma.mid ASC ");
				foreach ($markname_query->result() as $mark_name)
        		{
        			$mname_gs=$mark_name->markname;
            		$query_value = $this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and markname='$mname_gs' order by mid ASC");
					if($query_value->num_rows()>0){
						foreach ($query_value->result() as $mark_value)
						{
	            			$output .='<p>'.$mark_value->subname.'=>'.$mark_value->markname.'=><span class="badge badge-info">'.$mark_value->value.'/'.$mark_value->outof.'</span></p>';
	            		}
	            	}else{
	            		$output .='<p class="text-danger">NG</p>';
	            	}
            	}
			}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                	<i class="fas fa-check-circle"> </i> Subject not found.
            	</div></div>';
		}
		return $output;
	}
	function fetch_grade_markresultAdmin($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year)
	{
		$output='';
		if($gs_subject===trim('All'))
		{
			$queryFetchMark=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and quarter='$gs_quarter' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by subname order by subname ASC ");
			if($queryFetchMark->num_rows()>0){
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
				
        		foreach ($queryFetchMark->result_array() as $mark_name) {
        			$subject=$mark_name['subname'];
					$output.='<div style="height:92%;page-break-inside:avoid;"><h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp; Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp; Subject :<B><u>'.$subject.'</u></B></div></br></h6>';
					
					$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

					$output.='<div class="table-responsive">
	        		<table class="tabler table-borderedr table-hover" style="width:100%;">
	        		<tr>
	        		<th rowspan="3">No.</th>
	            	<th rowspan="3">Student Name</th>
	            	<th rowspan="3" class="text-center">Student ID</th>';
        			foreach ($evalname_query->result_array() as $evalua_name) 
        			{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
	            		$colSpan=$queryMvalue->num_rows() +2;
	            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
	            	}
	            	$output.='<th class="text-center"><B>Total</B></th><th rowspan="3" class="text-center">Sig.</th><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
		            	foreach ($queryMvalue->result() as $mark_name)
		            	{
		            		$output.='<td class="text-center">'.$mark_name->markname.'</td>';
		            	}
		            	$output.='<td class="text-center"><b>Tot</b></td>';
		            	$output.='<td class="text-center"><b>Conv</b></td>';
		            }
		            $output.='<td rowspan="2" class="text-center"> <B>100</B> </td>';
	            	$output.='</tr><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$percent=$evalua_name['percent'];
	            		$queryMvalue1 = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC",FALSE);
		            	$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
            			$sumOutOf=0;
	            		foreach ($queryMvalue->result_array() as $mark_name) {
	            			$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            			$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            		}
	        			$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        			$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
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
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
	            		$query_value = $this->db->query("select markname,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
								
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
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
        		$output.='</div>';
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
		else{
			$querySingleSubject=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' ");
			if($querySingleSubject->num_rows()>0)
			{
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];

				$output.='<div style="height:92%;page-break-inside:avoid;"><h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';

				$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

				$output.='<div class="table-responsive">
        		<table class="tabler table-borderedr table-hover" style="width:100%;height:85%">
        		<thead>
        		<tr>
        		<th rowspan="3">No.</th>
            	<th rowspan="3">Student Name</th>
            	<th rowspan="3" class="text-center">Student ID</th>';
            	
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
            		$colSpan=$queryMvalue->num_rows() +2;
            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
            	}
            	$output.='<th class="text-center">Total</th><th rowspan="3" class="text-center">Sig.</th><tr>';
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
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
            		$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
            		$sumOutOf=0;
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            		$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            	}
	        		$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        		$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
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
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
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
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
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
				$output.='<p class="text-center">'.$school_slogan.'!</p></div>';
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
	function fetch_grade_markresultAdminApproved($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year)
	{
		$output='';
		if($gs_subject===trim('All'))
		{
			$queryFetchMark=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and quarter='$gs_quarter' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by subname order by subname ASC ");
			if($queryFetchMark->num_rows()>0){
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
				
        		foreach ($queryFetchMark->result_array() as $mark_name) {
        			$subject=$mark_name['subname'];
					$output.='<div style="height:92%;page-break-inside:avoid;"><h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp; Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp; Subject :<B><u>'.$subject.'</u></B></div></br></h6>';
					
					$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

					$output.='<div class="table-responsive">
	        		<table class="tabler table-borderedr table-hover" style="width:100%;">
	        		<tr>
	        		<th rowspan="3">No.</th>
	            	<th rowspan="3">Student Name</th>
	            	<th rowspan="3" class="text-center">Student ID</th>';
        			foreach ($evalname_query->result_array() as $evalua_name) 
        			{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
	            		$colSpan=$queryMvalue->num_rows() +2;
	            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
	            	}
	            	$output.='<th class="text-center"><B>Total</B></th><th rowspan="3" class="text-center">Sig.</th><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
		            	foreach ($queryMvalue->result() as $mark_name)
		            	{
		            		$output.='<td class="text-center">'.$mark_name->markname.'</td>';
		            	}
		            	$output.='<td class="text-center"><b>Tot</b></td>';
		            	$output.='<td class="text-center"><b>Conv</b></td>';
		            }
		            $output.='<td rowspan="2" class="text-center"> <B>100</B> </td>';
	            	$output.='</tr><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$percent=$evalua_name['percent'];
	            		$queryMvalue1 = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC",FALSE);
		            	$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
            			$sumOutOf=0;
	            		foreach ($queryMvalue->result_array() as $mark_name) {
	            			$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            			$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            		}
	        			$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        			$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
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
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
	            		$query_value = $this->db->query("select markname,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' and approved='1'group by markname order by mid ASC");
								
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
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
        		$output.='</div>';
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
		else{
			$querySingleSubject=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' ");
			if($querySingleSubject->num_rows()>0)
			{
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];

				$output.='<div style="height:92%;page-break-inside:avoid;"><h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';

				$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

				$output.='<div class="table-responsive">
        		<table class="tabler table-borderedr table-hover" style="width:100%;height:85%">
        		<thead>
        		<tr>
        		<th rowspan="3">No.</th>
            	<th rowspan="3">Student Name</th>
            	<th rowspan="3" class="text-center">Student ID</th>';
            	
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
            		$colSpan=$queryMvalue->num_rows() +2;
            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
            	}
            	$output.='<th class="text-center">Total</th><th rowspan="3" class="text-center">Sig.</th><tr>';
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
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
            		$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
            		$sumOutOf=0;
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            		$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            	}
	        		$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        		$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
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
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
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
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
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
				$output.='<p class="text-center">'.$school_slogan.'!</p></div>';
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
	function fetch_grade_markresult($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year)
	{
		$output='';
		if($gs_subject===trim('All'))
		{
			$queryFetchMark=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and quarter='$gs_quarter' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by subname order by subname ASC ");
			if($queryFetchMark->num_rows()>0){
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
				
        		foreach ($queryFetchMark->result_array() as $mark_name) {
        			$subject=$mark_name['subname'];
					$output.='<div style="height:92%;page-break-inside:avoid;"><h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp; Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp; Subject :<B><u>'.$subject.'</u></B></div></br></h6>';
					
					$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

					$output.='<div class="table-responsive">
	        		<table class="tabler table-borderedr table-hover" style="width:100%;">
	        		<tr>
	        		<th rowspan="3">No.</th>
	            	<th rowspan="3">Student Name</th>
	            	<th rowspan="3" class="text-center">Student ID</th>';
        			foreach ($evalname_query->result_array() as $evalua_name) 
        			{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
	            		$colSpan=$queryMvalue->num_rows() +2;
	            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
	            	}
	            	$output.='<th class="text-center"><B>Total</B></th><th rowspan="3" class="text-center">Sig.</th><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
		            	foreach ($queryMvalue->result() as $mark_name)
		            	{
		            		$output.='<td class="text-center">'.$mark_name->markname.'</td>';
		            	}
		            	$output.='<td class="text-center"><b>Tot</b></td>';
		            	$output.='<td class="text-center"><b>Conv</b></td>';
		            }
		            $output.='<td rowspan="2" class="text-center"> <B>100</B> </td>';
	            	$output.='</tr><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$percent=$evalua_name['percent'];
	            		$queryMvalue1 = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC",FALSE);
		            	$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
            			$sumOutOf=0;
	            		foreach ($queryMvalue->result_array() as $mark_name) {
	            			$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            			$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            		}
	        			$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        			$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
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
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
	            		$query_value = $this->db->query("select markname,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
								
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
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
        		$output.='</div>';
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
		else{
			$querySingleSubject=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' ");
			if($querySingleSubject->num_rows()>0)
			{
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];

				$output.='<div style="height:92%;page-break-inside:avoid;"><h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';

				$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

				$output.='<div class="table-responsive">
        		<table class="tabler table-borderedr table-hover" style="width:100%;height:85%">
        		<thead>
        		<tr>
        		<th rowspan="3">No.</th>
            	<th rowspan="3">Student Name</th>
            	<th rowspan="3" class="text-center">Student ID</th>';
            	
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
            		$colSpan=$queryMvalue->num_rows() +2;
            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
            	}
            	$output.='<th class="text-center">Total</th><th rowspan="3" class="text-center">Sig.</th><tr>';
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
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
            		$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
            		$sumOutOf=0;
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            		$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            	}
	        		$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        		$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
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
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
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
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
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
				$output.='<p class="text-center">'.$school_slogan.'!</p></div>';
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
	function fetch_grade_markresultApproved($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year)
	{
		$output='';
		if($gs_subject===trim('All'))
		{
			$queryFetchMark=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and quarter='$gs_quarter' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by subname order by subname ASC ");
			if($queryFetchMark->num_rows()>0){
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
				
        		foreach ($queryFetchMark->result_array() as $mark_name) {
        			$subject=$mark_name['subname'];
					$output.='<div style="height:92%;page-break-inside:avoid;"><h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp; Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp; Subject :<B><u>'.$subject.'</u></B></div></br></h6>';
					
					$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

					$output.='<div class="table-responsive">
	        		<table class="tabler table-borderedr table-hover" style="width:100%;">
	        		<tr>
	        		<th rowspan="3">No.</th>
	            	<th rowspan="3">Student Name</th>
	            	<th rowspan="3" class="text-center">Student ID</th>';
        			foreach ($evalname_query->result_array() as $evalua_name) 
        			{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
	            		$colSpan=$queryMvalue->num_rows() +2;
	            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
	            	}
	            	$output.='<th class="text-center"><B>Total</B></th><th rowspan="3" class="text-center">Sig.</th><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
		            	foreach ($queryMvalue->result() as $mark_name)
		            	{
		            		$output.='<td class="text-center">'.$mark_name->markname.'</td>';
		            	}
		            	$output.='<td class="text-center"><b>Tot</b></td>';
		            	$output.='<td class="text-center"><b>Conv</b></td>';
		            }
		            $output.='<td rowspan="2" class="text-center"> <B>100</B> </td>';
	            	$output.='</tr><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$percent=$evalua_name['percent'];
	            		$queryMvalue1 = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC",FALSE);
		            	$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
            			$sumOutOf=0;
	            		foreach ($queryMvalue->result_array() as $mark_name) {
	            			$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            			$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            		}
	        			$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        			$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
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
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
	            		$query_value = $this->db->query("select markname,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							$totalMark=0;$outofTot=0;
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' and markname='$markNameStu' group by markname order by mid ASC");
								
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$outofTot=$outofTot+$kevalue['outof'];
										$totalMark=$totalMark+$kevalue['value'];
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
								$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
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
        		$output.='</div>';
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
		else{
			$querySingleSubject=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' ");
			if($querySingleSubject->num_rows()>0)
			{
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];

				$output.='<div style="height:92%;page-break-inside:avoid;"><h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';

				$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

				$output.='<div class="table-responsive">
        		<table class="tabler table-borderedr table-hover" style="width:100%;height:85%">
        		<thead>
        		<tr>
        		<th rowspan="3">No.</th>
            	<th rowspan="3">Student Name</th>
            	<th rowspan="3" class="text-center">Student ID</th>';
            	
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
            		$colSpan=$queryMvalue->num_rows() +2;
            		$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
            	}
            	$output.='<th class="text-center">Total</th><th rowspan="3" class="text-center">Sig.</th><tr>';
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
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
            		$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' and approved='1' group by markname order by mid ASC");
            		$sumOutOf=0;
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$sumOutOf=$mark_name['outof'] + $sumOutOf;
	            		$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            	}
	        		$output.='<td style="text-align:center;"><B>'.$sumOutOf.'</B></td>';
	        		$output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
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
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
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
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
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
				$output.='<p class="text-center">'.$school_slogan.'!</p></div>';
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
	function fetch_grade_teachermarkresult($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year)
	{
		$output=array();
		$querySingleSubject=$this->db->query("select * from mark".$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' ");
		if($querySingleSubject->num_rows()>0)
		{
			$query_name = $this->db->query("select * from school");
			$row_name = $query_name->row_array();
			$school_name=$row_name['name'];
			$school_slogan=$row_name['slogan'];
			$output[]='<h4 class="text-center"><B>'.$school_name.'</B>('.$gs_branches.'<small class="time">(Academic Year: '.$max_year.')</small>)</h4>';
			$output[]='<div class="row"><div class="col-md-1"></div>
			<div class="col-md-3"> Grade :<B>'.
			$gs_gradesec.'</B></div> <div class="col-md-3">Season :<B>'.
			$gs_quarter.'</B> </div> <div class="col-md-3">Subject : <B>'.
			$gs_subject.'</B></div><div class="col-md-1"></div></div>';	
			$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

			$output[]='<div class="table-responsive">
    		<table class="table table-bordered table-hover" style="width:100%;height:92%">
    		<thead>
    		<tr>
    		<th rowspan="3">No.</th>
        	<th rowspan="3">Student Name</th>
        	<th rowspan="3">Student ID</th>';
        	
        	foreach ($evalname_query->result_array() as $evalua_name) {
        		$mname_gs=$evalua_name['eid'];
        		$queryMvalue = $this->db->query("select markname from mark".$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
        		$colSpan=$queryMvalue->num_rows() +2;
        		$output[]='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
        	}
        	$output[]='<th>Total</th><th rowspan="3">Sig.</th><tr>';
        	foreach ($evalname_query->result_array() as $evalua_name) {
        		$mname_gs=$evalua_name['eid'];
        		$queryMvalue = $this->db->query("select markname from mark".$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
            	foreach ($queryMvalue->result_array() as $mark_name) {
            		$output[]='<td class="text-center">'.$mark_name['markname'].'</td>';
            	}
            	$output[]='<td class="text-center"><b>Tot</b></td>';
            	$output[]='<td class="text-center"><b>Conv</b></td>';
            }
            $output[]='<td rowspan="2" class="text-center"><B>100</B></td>';
        	$output[]='</tr><tr>';
        	foreach ($evalname_query->result_array() as $evalua_name) {
        		$mname_gs=$evalua_name['eid'];
        		$percent=$evalua_name['percent'];
        		$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
            	foreach ($queryMvalue->result_array() as $mark_name) {
            		$output[]='<td class="text-center">'.$mark_name['outof'].'</td>';
            	}
        		$sum_outof = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by stuid order by mid ASC");
    		    $keyvalue=$sum_outof->row_array();
    		    $sum_outof= $keyvalue['sum_outof'];
    		    $output[]='<td style="text-align:center;"><B>'.$sum_outof.'</B></td>';
    		    $output[]='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
            }
        	$output[]='</tr>';
        	$stuNO=1;
        	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname ASC ");
			foreach ($queryStudent->result_array() as $row) { 
        		$id=$row['id'];
        		$output[]='<tr><td>'.$stuNO.'.</td> <td>'.$row['fname'].' '.$row['mname'].' '.$row['lname']. '</td>';
        		$output[]='<td>'.$row['username'].' </td>';
        		$average=0;
        		foreach ($evalname_query->result_array() as $mark_name)
        		{
        			$percent= $mark_name['percent'];
        			$mname_gs=$mark_name['eid'];
            		$query_value = $this->db->query("select markname,sum(value) as total from mark".$gs_gradesec.$gs_quarter.$max_year." where  subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
					if($query_value->num_rows()>0){
						foreach ($query_value->result_array() as $value) {
							$markNameStu=$value['markname'];
							$queryStuValue = $this->db->query("select value,sum(value) as total from mark".$gs_gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
							if($queryStuValue->num_rows()>0){
								foreach ($queryStuValue->result_array() as $kevalue) {
									$output[]='<td style="text-align:center;">'.$kevalue['value'].'</td>';
								}
							}else{
								$output[]='<td style="text-align:center;">-</td>';
							}
	            		}
	            	}else{
	            		$output[]='<td style="text-align:center;">-</td>';
	            	}
	            	/*query Total*/
	            	$sum_outof = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by stuid order by mid ASC");
    		    	$keyvalue=$sum_outof->row_array();
    		    	$sumu_otof= $keyvalue['sum_outof'];
	            	$query_value = $this->db->query("select sum(value) as total,sum(outof) as outof from mark".$gs_gradesec.$gs_quarter.$max_year." where  stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' order by mid ASC");
					if($query_value->num_rows()>0){
						foreach ($query_value->result_array() as $value) {
							if($value['outof'] != 0 || $value['outof'] > 0 ||$sumu_otof !='')
            				{
                    			$conver= ($value['total'] *$percent )/$sumu_otof;
                    			$output[]='<td style="text-align:center;">'.$value['total'].'</td>';
                    			$output[]='<td style="text-align:center;"><B>'.number_format((float)$conver,2,'.','').'</B></td>';
            					$average =$conver + $average;
                			}else{
                				$output[]='<td style="text-align:center;">-</td>';
                			}
            			}
	            	}else{
	            		$output[]='<td style="text-align:center;">-</td>';
	            	}
            	}
            	$output[]='<td style="text-align:center;"><B>'.number_format((float)$average,2,'.','').'</B></td>';
        		$average=0;
        		$output[]='<td style="text-align:center;"></td>';
				$stuNO++;
			}
			$output[]='</table></div>';
			$output[]='<p class="text-center">'.$school_slogan.'!</p><br>';
		}else{
    		$output[]='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            	<i class="fas fa-check-circle"> </i> Data Not Found.
        	</div></div>';
		}
		
		return implode("\r\n",$output);
	}
	function fetchRowmarkresult($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year)
	{
		$output=array();
		if($gs_subject===trim('All'))
		{
			$queryFetchMark=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and quarter='$gs_quarter' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by subname order by subname ASC ");
			if($queryFetchMark->num_rows()>0){
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
				
        		foreach ($queryFetchMark->result_array() as $mark_name) {
        			$subject=$mark_name['subname'];
					$output[]='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output[]='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp;	Subject :<B><u>'.$subject.'</u></B></div></br></h6>';
					
					$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

					$output[]='<div class="table-responsive">
	        		<table class="tabler table-borderedr table-hover" style="width:100%;height:85%">
	        		<thead>
	        		<tr>
	        		<th rowspan="3">No.</th>
	            	<th rowspan="3">Student Name</th>
	            	<th rowspan="3" class="text-center">Student ID</th>';
        			foreach ($evalname_query->result_array() as $evalua_name) {
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
	            		$colSpan=$queryMvalue->num_rows() ;
	            		$output[]='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
	            	}
	            	$output[]='<th rowspan="3" class="text-center"> Sig.</th><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) {
	            		$mname_gs=$evalua_name['eid'];
	            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
		            	foreach ($queryMvalue->result_array() as $mark_name) {
		            		$output[]='<td class="text-center">'.$mark_name['markname'].'</td>';
		            	}
		            }
	            	$output[]='</tr><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) {
	            		$mname_gs=$evalua_name['eid'];
	            		$percent=$evalua_name['percent'];
	            		$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
		            	foreach ($queryMvalue->result_array() as $mark_name)
		            	{
		            		$output[]='<td class="text-center">'.$mark_name['outof'].'</td>';
		            	}
		            }
	            	$output[]='</tr>';
	            	$stuNO=1;
	            	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname,u.lname ASC ");
					foreach ($queryStudent->result_array() as $row) { 
	            		$id=$row['id'];
	            		$output[]='<tr><td>'.$stuNO.'.</td> <td>'.$row['fname'].' '.$row['mname'].' '.$row['lname']. '</td>';
	            		$output[]='<td class="text-center">'.$row['username'].' </td>';
	            		$average=0;
	            		foreach ($evalname_query->result_array() as $mark_name)
	            		{
	            			$percent= $mark_name['percent'];
	            			$mname_gs=$mark_name['eid'];
		            		$query_value = $this->db->query("select markname,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
							if($query_value->num_rows()>0){
								foreach ($query_value->result_array() as $value) {
									$markNameStu=$value['markname'];
									$queryStuValue = $this->db->query("select value,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
									if($queryStuValue->num_rows()>0){
										foreach ($queryStuValue->result_array() as $kevalue) {
											$output[]='<td style="text-align:center;">'.$kevalue['value'].'</td>';
										}
									}else{
										$output[]='<td class="text-danger" style="text-align:center;">NG</td>';
									}
			            		}
			            	}else{
			            		$output[]='<td style="text-align:center;">-</td>';
			            	}
		            	}
	            		$output[]='<td style="text-align:center;"></td>';
						$stuNO++;
					}
					$output[]='</table></div>';
					$output[]='<p class="text-center">'.$school_slogan.'!</p>';
        		}
			}else{
				$output[]='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                	<i class="fas fa-check-circle"> </i> Data Not Found.
            	</div></div>';
			}
		}
		else{
			$querySingleSubject=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' ");
			if($querySingleSubject->num_rows()>0)
			{
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];

				$output[]='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output[]='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';

				$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

				$output[]='<div class="table-responsive">
        		<table class="tabler table-borderedr table-hover" style="width:100%;height:85%">
        		<thead>
        		<tr>
        		<th rowspan="3">No.</th>
            	<th rowspan="3">Student Name</th>
            	<th rowspan="3" class="text-center">Student ID</th>';
            	
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
            		$colSpan=$queryMvalue->num_rows() ;
            		$output[]='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
            	}
            	$output[]='<th rowspan="3" class="text-center">Sig.</th><tr>';
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$output[]='<td class="text-center">'.$mark_name['markname'].'</td>';
	            	}
	            }
            	$output[]='</tr><tr>';
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$percent=$evalua_name['percent'];
            		$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$output[]='<td class="text-center">'.$mark_name['outof'].'</td>';
	            	}
	            }
            	$output[]='</tr>';
            	$stuNO=1;
            	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname ASC ");
				foreach ($queryStudent->result_array() as $row) { 
            		$id=$row['id'];
            		$output[]='<tr><td>'.$stuNO.'.</td> <td>'.$row['fname'].' '.$row['mname'].' '.$row['lname']. '</td>';
            		$output[]='<td class="text-center">'.$row['username'].' </td>';
            		$average=0;
            		foreach ($evalname_query->result_array() as $mark_name)
            		{
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
	            		$query_value = $this->db->query("select markname,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$output[]='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output[]='<td class="text-danger" style="text-align:center;"> NG</td>';
								}
		            		}
		            	}else{
		            		$output[]='<td style="text-align:center;">-</td>';
		            	}
	            	}
	            	$output[]='<td style="text-align:center;"></td>';
					$stuNO++;
				}
				$output[]='</table></div>';
				$output[]='<p class="text-center">'.$school_slogan.'!</p>';
			}else{
	    		$output[]='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                	<i class="fas fa-check-circle"> </i> Data Not Found.
            	</div></div>';
			}
		}
		return implode("\r\n",$output);
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
		$output ='<option value="All"> All </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->Subj_name.'">'.$row->Subj_name.'</option>';
			}
			return $output;
	}
	function fetch_subject_from_subjectAdmin($gradesec,$max_year){
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where(array('subject.Academic_Year'=>$max_year));
		$this->db->order_by('subject.Subj_name','ASC');
		$this->db->group_by('subject.Subj_name');
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->join('users',
		'users.grade = subject.Grade');
		$query=$this->db->get();
		$output ='<option value="All"> All </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->Subj_name.'">'.$row->Subj_name.'</option>';
			}
			return $output;
	}
	function fetch_subject_from_subjectNonAdmin($gradesec,$max_year,$branch){
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch);
		$this->db->where(array('subject.Academic_Year'=>$max_year));
		$this->db->order_by('subject.Subj_name','ASC');
		$this->db->group_by('subject.Subj_name');
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->join('users',
		'users.grade = subject.Grade');
		$query=$this->db->get();
		$output ='<option value="All"> All </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->Subj_name.'">'.$row->Subj_name.'</option>';
			}
			return $output;
	}
	function fetch_subject_from_gradeFilter($gradesec,$max_year){
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where(array('subject.Academic_Year'=>$max_year));
		$this->db->order_by('subject.Subj_name','ASC');
		$this->db->group_by('subject.Subj_name');
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->join('users',
		'users.grade = subject.Grade');
		$query=$this->db->get();
		$output ='<div class="row">';
		foreach ($query->result() as $row) { 
			$output.='<div class="col-md-6 col-6">
			<div class="form-group">
            <div class="pretty p-icon p-jelly p-round p-bigger">
              <input type="checkbox" name="subject_statistics" value="'.$row->Subj_name.'" id="customCheck1 subject_statistics">
              <div class="state p-info">
                <i class="icon material-icons"></i>
                <label></label>
              </div>
            </div>'.$row->Subj_name.'
          </div></div>';
			
		}
		$output.='</div>';
		return $output;
	}
	function fetch_subject_from_gradeFilterDirector($gradesecs,$max_year){
		foreach($gradesecs as $gradesec){
			$this->db->where('users.gradesec',$gradesec);
			$this->db->where(array('subject.Academic_Year'=>$max_year));
			$this->db->order_by('subject.Subj_name','ASC');
			$this->db->group_by('subject.Subj_name');
			$this->db->select('*');
			$this->db->from('subject');
			$this->db->join('users',
			'users.grade = subject.Grade');
			$query=$this->db->get();
			$output ='<div class="row">';
			foreach ($query->result() as $row) { 
				$output.='<div class="col-md-6 col-6">
				<div class="form-group">
	            <div class="pretty p-icon p-jelly p-round p-bigger">
	              <input type="checkbox" name="subject_statisticsDirector" value="'.$row->Subj_name.'" id="customCheck1 subject_statisticsDirector">
	              <div class="state p-info">
	                <i class="icon material-icons"></i>
	                <label></label>
	              </div>
	            </div>'.$row->Subj_name.'
	          </div></div>';
				
			}
			$output.='</div>';
		}
		return $output;
	}
	function fetch_subject_from_subject4MardEdit($gradesec,$max_year){
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where(array('subject.Academic_Year'=>$max_year));
		$this->db->order_by('subject.Subj_name','ASC');
		$this->db->group_by('subject.Subj_name');
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->join('users',
		'users.grade = subject.Grade');
		$query=$this->db->get();
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->Subj_name.'">'.$row->Subj_name.'</option>';
			}
			return $output;
	}
	function fetch_mymark_result($academicyear,$gradesec,$subject,$quarter,$id){
		$this->db->where(array('mark.academicyear'=>$academicyear));
		$this->db->where(array('mark.subname'=>$subject));
		$this->db->where(array('mark.quarter'=>$quarter));
		$this->db->where(array('mark.stuid'=>$id));
		$this->db->where(array('users.gradesec'=>$gradesec));
		$this->db->group_by('mark.stuid');
		$this->db->order_by('mark.mid');
		$this->db->select('*');
		$this->db->from('mark');
		$this->db->join('users',
		'users.id = mark.stuid');
		$query=$this->db->get();
		return $query->result();
	}
	function fetch_average($max_year,$id,$max_quarter){
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('quarter'=>$max_quarter));
		$this->db->where(array('stuid'=>$id));
		$this->db->group_by('quarter');
		$this->db->group_by('subject');
		$query=$this->db->get('reportcard');
		return $query->result();
	}
	function fetch_evaluation_fornewexam($max_year){
		$this->db->order_by('eid','ASC');
		$this->db->group_by('evname');
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->get('evaluation');
		return $query->result();
	}
	function fetch_thisgrade_students_fornewexam($academicyear,$gradesec,$subject,$evaluation,$quarter,$assesname,$percentage,$branch,$max_year){
		$output='';
		$query = $this->db->query(" Select * from users where academicyear='$max_year' and status='Active' and isapproved='1' and usertype ='Student' and gradesec='$gradesec' and branch='$branch' order by fname,mname,lname ASC ");
		$output .='<div class="table-responsive">
        	<table class="table table-bordered table-hover" style="width:100%;">
        		<thead>
        		<tr>
        			<th>No.</th>
        		    <th>Result</th>
            		<th>Name</th>
            		<th>Grade</th>
            		<th>Branch</th>
            		<th>Subject</th>
           	 		<th>Quarter</th>
            		<th>Assesment Name</th>
            		<th>Percentage</th>
        		</tr>
        	</thead>';
        $output.='<input type="hidden" id="academicyear" value="'.$academicyear.'"> ';
        $output.='<input type="hidden" id="subject" value="'.$subject.'"> ';
        $output.='<input type="hidden" id="evaluation" value="'.$evaluation.'"> ';
        $output.='<input type="hidden" id="quarter" value="'.$quarter.'"> ';
        $output.='<input type="hidden" id="percentage" value="'.$percentage.'"> ';
        $output.='<input type="hidden" id="assesname" value="'.$assesname.'"> ';
        $output.='<input type="hidden" id="markGradeSec" value="'.$gradesec.'"> ';
        $output.='<input type="hidden" id="markGradeSecBranch" value="'.$branch.'"> ';
        $no=1;
		foreach ($query->result() as $fetch_student) {
			$output.='<input type="hidden" id="stuid" 
			name="stuid_result" value="'.$fetch_student->id.'"> ';
			$output.='<tr class="'.$fetch_student->id.'">
			<td>'.$no.'.</td>
			<td><input type="text" onkeyup="chkMarkValue()" name="markvalue_result" id="resultvalue" class="form-control markvalue_result">
			 </td>';
			$output.='<td>'.$fetch_student->fname.' '.$fetch_student->mname.' '.$fetch_student->lname.'</td>';
			$output.='<td>'.$gradesec.'</td>';
			$output.='<td>'.$branch.'</td>';
			$output.='<td>'.$subject.'</td>';
			$output.='<td>'.$quarter.'</td>';
			$output.='<td>'.$assesname.'</td>';
			$output.='<td>'.$percentage.'</td></tr>';
			$no++;
		}
		$output .='</table></div>';
		$output .='<button type="submit" id="SaveResult" class="btn btn-primary btn-block">Submit Result </button>';
		return $output;
	}
	function fetch_thisgrade_mystudents_fornewexam($gradesec,$max_year,$branch){
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('gradesec'=>$gradesec));
		$this->db->where(array('branch'=>$branch));
		$this->db->order_by('fname','ASC');
		$query=$this->db->get('users');
		return $query->result();
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
	function insert_bs_name($bsname,$grades,$max_year,$data){
		$this->db->where(array('bsname'=>$bsname));
		$this->db->where(array('grade'=>$grades));
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->get('basicskill');
		if($query->num_rows()>0){
			return false;
		}else{
			$this->db->insert('basicskill',$data);
		}
	}
	function fetchBsCategory($max_year){
		$query=$this->db->query("select *, GROUP_CONCAT(bcgrade) as grade_bsname from bscategory where academicyear='$max_year' group by bscategory order by bscategory ASC ");
		$output='';
		if($query->num_rows()>0){
			$allbS=$query->num_rows();
			$output='<div class="card">
	        <div class="card-header">
	            <h4>Basic Skills Names</h4>
	        </div>
			<div class="table-responsive">
	        <table class="table table-stripped table-hover" style="width:100%;">
	            <thead>
	                <tr>
	                    <th>No.</th>
	                    <th>Basic Skill Category</th>
	                    <th>Grade</th>
	                    <th>Order</th>
	                    <th>Left Row</th>
	                    <th>Academic Year</th>
	                    <th>Date Created</th>
	                </tr>
	            </thead>';
	        $no=1;
			foreach ($query->result() as $bsnames) {
				$output.='<tr class="deleteCAT'.$bsnames->bscategory.'">
	                <td>'.$no.'.</td>
	                <td>'.$bsnames->bscategory.'
	                <div class="table-links"> <a href="#" 
	                class="deleteCAT" value="'.$bsnames->bscategory.'">
	                 <span class="text-danger">Delete</span> 
	                 </a> </div> </td>
	                <td>'.$bsnames->grade_bsname.'</td>';
	                $output.='<td><select class="form-control bssubOrderJo" required="required" name="kgsubOrder" id="bssubOrder">';
 						for ($i=1; $i <=$allbS ; $i++) { 
 							if($i==$bsnames->bcorder){
 								$output.='<option selected="selected" class="bsJoss" id="'.$bsnames->bscategory.'" value="'.$i.'">'.$i.'</option>';
 							}else{
 								$output.='<option class="bsJoss" id="'.$bsnames->bscategory.'" value="'.$i.'">'.$i.'</option>';
 							}
 						}
 						$output.='</select></td>';
 						if($bsnames->bcsubjectrow=='1'){
 							$output.='<td><div class="pretty p-switch p-fill">
			                  <input type="checkbox" name="putbsCatLeftRow" class="putbsCatLeftRow" checked="checked" id="'.$bsnames->bscategory.'" value="'.$bsnames->bscategory.'" >
			                  <div class="state p-success">
			                    <label></label>
			                  </div>
			                </div></td>';
 						}else{
 							$output.='<td><div class="pretty p-switch p-fill">
			                  <input type="checkbox" name="putbsCatLeftRow" class="putbsCatLeftRow" id="'.$bsnames->bscategory.'" value="'.$bsnames->bscategory.'" >
			                  <div class="state p-success">
			                    <label></label>
			                  </div>
			                </div></td>';
 						}
 					

	                $output.='<td>'.$bsnames->academicyear.'</td>
	                <td>'.$bsnames->datecreated.'</td>
	            </tr>';
	            $no++;
			}
			$output.='</table></div></div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No basic skill category found.
        	</div></div>';
		}
		return $output;
	}
	function fetch_bscategory($max_year){
		$query=$this->db->query("select * from bscategory where academicyear='$max_year' group by bscategory order by bscategory ASC ");
		return $query->result();
	}
	function fetch_bsname($max_year){
		$query=$this->db->query("select * from basicskill where academicyear='$max_year' group by id order by bsname ASC ");
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
	        <table class="tabler table-borderedr table-hover" style="width:100%;">
	            <thead>
	                <tr>
	                    <th>No.</th>
	                    <th>Basic Skill Name</th>
	                    <th>Grade</th>
	                    <th>Put Subject Row</th>
	                    <th>Academic Year</th>
	                    <th>Date Created</th>
	                </tr>
	            </thead>';
	        $no=1;
			foreach ($query->result() as $bsnames) {
				$output.='<tr class="delete_bs'.$bsnames->bsname.'">
	                <td>'.$no.'.</td>
	                <td>'.$bsnames->bsname.'
	                <div class="table-links"> 
	                <a href="#" class="deletebaskill" value="'.$bsnames->bsname.'"> 
	                <span class="text-danger">Delete</span> 
	                </a> <div class="bullet"></div>
	                <a href="#" class="editbaskill" value="'.$bsnames->bsname.'"> 
	                <span class="text-info">Edit</span> 
	                </a>
	                </div> </td>
	                <td>'.$bsnames->grade.'</td>';
	                if($bsnames->subjectrow==0){
	                	$output.=' <td>
						<div class="pretty p-switch p-fill">
		                  <input type="checkbox" name="addOnSubRowGs" class="'.$bsnames->bsname.'" id="'.$bsnames->grade.'" value="1" >
		                  <div class="state p-success">
		                    <label></label>
		                  </div>
		                </div></td>';
	                }else{
	                	$output.=' <td>
						<div class="pretty p-switch p-fill">
		                  <input type="checkbox" name="addOnSubRowGs" class="'.$bsnames->bsname.'" checked="checked" id="'.$bsnames->grade.'" value="0" >
		                  <div class="state p-success">
		                    <label></label>
		                  </div>
		                </div></td>';
	                }
	                $output.='<td>'.$bsnames->academicyear.'</td>
	                <td>'.$bsnames->datecreated.'</td>
	            </tr>';
	            $no++;
			}
			$output.='</table></div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No basic skill found.
        	</div></div>';
		}
		return $output;
	}
	function delete_bsname($id,$max_year){
		$this->db->where(array('bsname'=>$id));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->delete('basicskill');
	}
	function export_student_bs_formate($gradesec,$max_year,$branch1){
		$this->db->where('basicskill.academicyear',$max_year);
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch1);
		$this->db->where('users.status','Active');
		$this->db->where('users.isapproved','1');
		$this->db->where('users.academicyear',$max_year);
		$this->db->order_by('basicskill.bsname','ASC');
		$this->db->order_by('users.fname','ASC');
		$this->db->order_by('users.mname','ASC');
		$this->db->group_by('users.id');
		$this->db->select('*');
		$this->db->from('basicskill');
		$this->db->join('users', 
            'users.grade = basicskill.grade');
		$query = $this->db->get();
        return $query->result();
	}
	function export_mystudent_bs_formate($gradesec,$max_year,$branch){
		$this->db->where('basicskill.academicyear',$max_year);
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch);
		$this->db->where('users.status','Active');
		$this->db->where('users.isapproved','1');
		$this->db->where('users.academicyear',$max_year);
		$this->db->order_by('basicskill.bsname','ASC');
		$this->db->order_by('users.fname','ASC');
		$this->db->order_by('users.mname','ASC');
		$this->db->group_by('users.id');
		$this->db->select('*');
		$this->db->from('basicskill');
		$this->db->join('users', 
            'users.grade = basicskill.grade');
		$query = $this->db->get();
        return $query->result();
	}
	function export_this_grade_bsname($gradesec,$max_year,$branch1){
		$this->db->where('basicskill.academicyear',$max_year);
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch1);
		$this->db->where('users.status','Active');
		$this->db->where('users.isapproved','1');
		$this->db->where('users.academicyear',$max_year);
		$this->db->order_by('basicskill.bsname','ASC');
		$this->db->group_by('basicskill.id');
		$this->db->select('*');
		$this->db->from('basicskill');
		$this->db->join('users', 
            'users.grade = basicskill.grade');
		$query = $this->db->get();
        return $query->result();
	}
	function export_mythis_grade_bsname($gradesec,$max_year,$branch){
		$this->db->where('basicskill.academicyear',$max_year);
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch);
		$this->db->where('users.status','Active');
		$this->db->where('users.isapproved','1');
		$this->db->where('users.academicyear',$max_year);
		$this->db->order_by('basicskill.bsname','ASC');
		$this->db->group_by('basicskill.id');
		$this->db->select('*');
		$this->db->from('basicskill');
		$this->db->join('users', 
            'users.grade = basicskill.grade');
		$query = $this->db->get();
        return $query->result();
	}
	function import_bs($gradesec,$quarter,$max_year,$branch){
		$this->db->where('academicyear',$max_year);
		$this->db->where('bsgrade',$gradesec);
		$this->db->where('quarter',$quarter);
		$this->db->where('bsbranch',$branch);
		$query=$this->db->get('basicskillvalue'.$gradesec.$max_year);

		/*$queryBsType=$this->db->query("select * from bstype where bstype='$value' and  academicyear='$max_year' group by bstid ");
		if($queryBsType->num_rows() > 0){*/
			if($query->num_rows() > 0){
				
				$this->db->where('academicyear',$max_year);
				$this->db->where('bsgrade',$gradesec);
				$this->db->where('quarter',$quarter);
				$this->db->where('bsbranch',$branch);
				$queryy=$this->db->delete('basicskillvalue'.$gradesec.$max_year);
				return TRUE;
			}else{
				return TRUE;
				/*$queryy=$this->db->insert('basicskillvalue',$data);*/
			}
			/*if($queryy){
				return true;
			}else{
				return false;
			} */
		/*}*/
	}
	function insert_exam($user,$subject,$gradesec,$examname,$answern,$numbern,$minute,$ca,$cb,$cc,$cd,$max_year){
		$data=array(
			'teacher'=>$user,
			'subject'=>$subject,
			'grade'=>$gradesec,
			'examname'=>$examname,
			'answer'=>$answern,
			'question'=>$numbern,
			'a'=>$ca,
			'b'=>$cb,
			'c'=>$cc,
			'd'=>$cd,
			'datecreated'=>date('M-d-Y'),
			'examinute'=>$minute,
			'academicyear'=>$max_year
		);
		$query=$this->db->insert('exam',$data);
		return $query;
	}
	function fetch_exam($max_year){
		$query = $this->db->query("
			select us.profile, us.fname,us.mname ,ex.eid,ex.examname,ex.datecreated,ex.teacher,ex.subject,ex.grade from users as us cross join exam as ex where ex.academicyear='$max_year' and us.academicyear='$max_year' and  us.username=ex.teacher group by ex.examname,ex.subject order by ex.eid DESC 
		");
        return $query->result();
	}
	function myschool_fetch_exam($branch,$max_year){
		$query = $this->db->query("
			select us.profile, us.fname,us.mname ,ex.eid,ex.examname,ex.datecreated,ex.teacher,ex.subject,ex.grade from users as us 
			cross join exam as ex where us.branch=
			'$branch' and ex.academicyear=
			'$max_year' and us.academicyear=
			'$max_year' and  us.username=ex.teacher 
			group by ex.examname,ex.subject order by ex.eid 
			DESC 
		");
        return $query->result();
	}
	function this_exam_name($examname,$max_year,$grade,$subject){
		$this->db->where(array('examname'=>$examname));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('grade'=>$grade));
		$this->db->where(array('subject'=>$subject));
		$query = $this->db->get('exam');
        if($query->num_rows() > 0){
        	return false;
        }
        else{
        	return true;
        }
	}
	function fetch_my_exam($sid,$grade,$max_year){
		$query=$this->db->query("
			Select ex.academicyear,us.profile,us.fname,us.mname,ex.examname,ex.subject,ex.grade,ex.examname,ex.question,ex.a,ex.b,ex.c,ex.d,ex.answer,ex.examinute,ex.datecreated,ex.teacher from users as us cross join exam as ex where ex.grade='$grade' and ex.academicyear='$max_year' and us.academicyear='$max_year' and us.username=ex.teacher and ex.subject not in( select subject from examanswer as exa where exa.examname = ex.examname and exa.sid ='$sid') group by ex.examname ,ex.subject order by ex.eid DESC
		");
        return $query->result();
	}
	function read_exam($id,$subject,$max_year){
		$this->db->where(array('exam.examname'=>$id));
		$this->db->where(array('exam.subject'=>$subject));
		$this->db->where(array('exam.academicyear'=>$max_year));
		$this->db->select('*');
        $this->db->from('exam');
        $this->db->join('users', 
            'users.username = exam.teacher');
        $query = $this->db->get();
        return $query->result();
	}
	function insert_usertrial($sid,$id,$subject,$max_year,$datetried){
		$this->db->where('triedexam',$id);
		$this->db->where('stuid',$sid);
		$this->db->where('triedsubject',$subject);
		$this->db->where('academicyear',$max_year);
		$query = $this->db->get('examtried');
        if($query->num_rows()>0){
        	return false;
        }else{
        	return true;
		}
	}
	function delete_exam($id,$subject,$grade){
		$this->db->where('examname',$id);
		$this->db->where('subject',$subject);
		$this->db->where('grade',$grade);
		$this->db->delete('exam');
	}
	function view_students_examresult($examname,$subject,$max_year){
		$query=$this->db->query("us.id,us.fname, us.mname, exa.ans,ex.academicyear,ex.answer,exa.ans,exa.subject,exa.examname,ex.eid,ex.question,exa.ques,exa.sid,exa.datesubmitted from exam as ex cross join examanswer as exa cross join users as us where ex.examname='$examname' and ex.subject='$subject' and ex.academicyear='$max_year' and exa.academicyear='$max_year' and ex.eid=exa.ques and us.id=exa.sid group by us.id
		");
        return $query->result();
	}
	function outof_error($gs_branches,$gs_gradesec,$gs_subject,$max_quarter,$max_year){
		
		$output='';
		$query_name = $this->db->query("select * from school");
        	$row_name = $query_name->row();
        	$school_name=$row_name->name;
			$output .='<h4> '.$school_name.'('.$gs_branches.') Grade: '.$gs_gradesec.' Quarter :'.$max_quarter.'</h4>';
			$output .='<div class="table-responsive">
        		<table class="table table-striped table-hover" id="tableExport" style="width:100%;">
        		<thead>
        		<tr><th>No.</th>
            		<th>Student Name</th>
            		<th>Student ID</th>
            		<th>Subject</th>
            		<th>Assesment Name</th>
            		<th>Status</th>
            		<th>Value</th>
        		</tr>
		    </thead>';
		    $no=1;
		if($gs_subject==='All'){
			$markname_query=$this->db->query("select ma.evaid, ma.markname,ma.subname, ma.mid, ma.value, ma.outof,sum(outof) as total_outof from mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." as ma where ma.academicyear='$max_year' and  ma.quarter='$max_quarter' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' group by ma.markname,subname order by ma.mid ASC ");
			foreach ($markname_query->result() as $markValue) {
				$markname=$markValue->markname;
				$subject=$markValue->subname;
				$queryStudent2=$this->db->query("select lname,username, fname, mname, branch, ma.subname,ma.markname, ma.quarter,ma.value, ma.outof, ma.zeromarkinfo, gradesec,id from users as us cross join mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." as ma where us.academicyear='$max_year' and us.usertype='Student' and us.status='Active' and us.isapproved='1' and us.branch='$gs_branches' and us.gradesec='$gs_gradesec' and ma.markname='$markname' and  ma.subname='$subject' and ma.quarter='$max_quarter' and us.id not in(select stuid from mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." where markname='$markname' and  subname='$subject' and quarter='$max_quarter' group by markname ) or us.academicyear='$max_year' and us.usertype='Student' and us.status='Active' and us.isapproved='1' and us.branch='$gs_branches' and us.gradesec='$gs_gradesec' and ma.markname='$markname' and  ma.subname='$subject' and ma.quarter='$max_quarter' and ma.value='0' and us.id=ma.stuid group by id");
				foreach ($queryStudent2->result() as $gradeValue) {
					$gradesec=$gradeValue->gradesec;
					$stuid=$gradeValue->id;
					$output .='<tr><td>'.$no.'.</td>
					<td>'.$gradeValue->fname.' '.$gradeValue->mname.' '.$gradeValue->lname.'</td>';
					$output .='<td>'.$gradeValue->username.'</td>';
					$output .='<td>'.$gradeValue->subname.'</td>';
					$output .='<td>'.$gradeValue->markname.'</td>';
					$output .='<td class="text-warning">NG(-)</td>';
					$output .='<td><span class="text-danger"><h4>-</h4></span></td></tr>';	
					$no++;
				}
			}
		}else{
			$markname_query=$this->db->query("select ma.evaid, ma.markname, ma.mid, ma.value, ma.outof,sum(outof) as total_outof from mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." as ma where ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$max_quarter' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' group by ma.markname order by ma.mid ASC ");
			foreach ($markname_query->result() as $markValue) {
				$markname=$markValue->markname;
				$queryStudent2=$this->db->query("select lname,username, fname, mname, branch, ma.subname,ma.markname, ma.quarter,ma.value, ma.outof, ma.zeromarkinfo, gradesec,id from users as us cross join mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." as ma where us.academicyear='$max_year' and us.usertype='Student' and us.status='Active' and us.isapproved='1' and us.branch='$gs_branches' and us.gradesec='$gs_gradesec' and ma.markname='$markname' and  ma.subname='$gs_subject' and ma.quarter='$max_quarter' and us.id not in(select stuid from mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." where markname='$markname' and  subname='$gs_subject' and quarter='$max_quarter' group by markname ) or us.academicyear='$max_year' and us.usertype='Student' and us.status='Active' and us.isapproved='1' and us.branch='$gs_branches' and us.gradesec='$gs_gradesec' and ma.markname='$markname' and  ma.subname='$gs_subject' and ma.quarter='$max_quarter' and ma.value='0' and us.id=ma.stuid  group by id");
			
				foreach ($queryStudent2->result() as $gradeValue) {
					$gradesec=$gradeValue->gradesec;
					$stuid=$gradeValue->id;
					$output .='<tr><td>'.$no.'.</td>
					<td>'.$gradeValue->fname.' '.$gradeValue->mname.' '.$gradeValue->lname.'</td>';
					$output .='<td>'.$gradeValue->username.'</td>';
					$output .='<td>'.$gradeValue->subname.'</td>';
					$output .='<td>'.$gradeValue->markname.'</td>';
					$output .='<td class="text-warning">NG(-)</td>';
					$output .='<td><span class="text-danger"><h4>-</h4></span></td></tr>';	
					$no++;
				}
			}
		}
		$output .='<table></div>';
		return $output;
	}
	function fetch_phone($max_year,$gradesec,$branch)
	{
		$query_student=$this->db->query(" Select * from users where gradesec='$gradesec' and status='Active' and academicyear='$max_year' and branch='$branch' and isapproved='1' order by fname,mname ASC ");
		$output='';
		if($query_student->num_rows()>0){
			$query_name = $this->db->query("select * from school");
	        $row_name = $query_name->row();//school info
	        $school_name=$row_name->name;
	        $address=$row_name->address;
	        $phone=$row_name->phone;
	        $website=$row_name->website;
	        $email=$row_name->email;
	        $logo=$row_name->logo;
			$output .='<p class="text-center"><b><u>'.$school_name.' Phone List for Grade '.$gradesec.'</u></b></p>';
			$output .='<div class="table-responsive">
	      	<table class="table table-striped" id="tableExport" style="width:100%;">
	      	<tbody><tr>
	      	<td><B>No.</B></td>
	      	<td><B>Student Name</B></td>
	      	<td><B>Student ID</B></td>
	      	<td><B>Gender</B></td>
	      	<td><B>Grade</B></td>
	      	<td><B>Section</B></td>
	      	<td><B>Mother Mobile</B></td>
	      	<td><B>Father Mobile</B></td>
	      	<td><B>Branch</B></td></tr>';
	      	$no=1;
	    	foreach ($query_student->result() as $row) {
				$output .='<tr><td>'.$no.'.</td>';
	    		$output .='<td>'.$row->fname.' '.$row->mname.' '. $row->lname.'</td>';
	    		$output .='<td>'.$row->username.'</td>';
	    		$output .='<td>'.$row->gender.'</td>';
	    		$output .='<td>'.$row->grade.'</td>';
	    		$output .='<td>'.$row->gradesec.'</td>';
	    		$output .='<td>'.$row->mobile.'</td>';
	    		$output .='<td>'.$row->father_mobile.'</td>';
	    		$output .='<td>'.$row->branch.'</td>';
	      		$output .='</tr>'; 
	      		$no++;
	    	}
	    	$output .=' </tbody> </table> </div>';
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
	function fetch_gradephone($max_year,$grade,$branch)
	{
		$query_student=$this->db->query(" Select fname,mname,lname,gender,grade,gradesec, branch,username,father_mobile,mobile from users where grade='$grade' and status='Active' and academicyear='$max_year' and branch='$branch' and isapproved='1' order by fname,mname ASC ");
		$output ='';
		if($query_student->num_rows()>0){
			$query_name = $this->db->query("select * from school");
	        $row_name = $query_name->row();//school info
	        $school_name=$row_name->name;
	        $address=$row_name->address;
	        $phone=$row_name->phone;
	        $website=$row_name->website;
	        $email=$row_name->email;
	        $logo=$row_name->logo;
			$output ='<p class="text-center"><b><u>'.$school_name.' Phone List for Grade '.$grade.'</u></b></p>';
			$output.='
	      	<div class="table-responsive">
	      	<table class="table table-striped" id="tableExport" style="width:100%;">
	      	<tbody>
	      	<tr><td><B>No.</B></td>
	      	<td><B>Student Name</B></td>
	      	<td><B>Student ID</B></td>
	      	<td><B>Gender</B></td>
	      	<td><B>Grade</B></td>
	      	<td><B>Section</B></td>
	      	<td><B>Mother Mobile</B></td>
	      	<td><B>Father Mobile</B></td>
	      	<td><B>Branch</B></td></tr>';
	      	$no=1;
	    	foreach ($query_student->result() as $row) {
				$output .='<tr><td>'.$no.'.</td>';
	    		$output .='<td>'.$row->fname.' '.$row->mname.' '. $row->lname.'</td>';
	    		$output .='<td>'.$row->username.'</td>';
	    		$output .='<td>'.$row->gender.'</td>';
	    		$output .='<td>'.$row->grade.'</td>';
	    		$output .='<td>'.$row->gradesec.'</td>';
	    		$output .='<td>'.$row->mobile.'</td>';
	    		$output .='<td>'.$row->father_mobile.'</td>';
	    		$output .='<td>'.$row->branch.'</td>';
	      		$output .='</tr>'; 
	      		$no++;
	    	}
	    	$output .=' </tbody> </table> </div>';
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
	function gener_report($gradesec,$branch,$max_year){
		$this->db->where('gradesec',$gradesec);
		$this->db->where('branch',$branch);
		$this->db->where('academicyear',$max_year);
		$this->db->order_by('fname','ASC');
		$query = $this->db->get('users');
		$output =' <div class="table-responsive">
        <table class="table table-striped" id="tableExport" style="width:100%;"> 
         <tbody><tr><td><B>No.</B></td><td><B>Student Name</B></td><td><B>Student ID</B></td><td><B>Grade</B></td><td><B>Branch</B></td><td><B>Gender</B></td></tr>';
        $no=1;
		foreach ($query->result() as $row) {
			$output .='<tr>';
			$output .='<td>'.$no.'.</td>';
    		$output .='<td>'.$row->fname.' '.$row->mname.' 
    		'. $row->lname.'</td>';
    		$output .='<td>'.$row->username.'</td>';
    		$output .='<td>'.$row->gradesec.'</td>';
    		$output .='<td>'.$row->branch.'</td>';
    		$output .='<td>'.$row->gender.'</td>';
      		$output .='</tr>'; 
      		$no++;
		}
		$output .=' </tbody> </table> </div>';
		$query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' then 1 else 0 end) AS femalecount FROM users where gradesec='$gradesec' and academicyear='$max_year' and branch='$branch' GROUP BY academicyear ORDER BY fname ASC");
		$output .='<div class="row"><div class="col-lg-4"> </div> <div class="col-lg-4"></div>
		<div class="col-lg-4">';
		foreach ($query2->result() as $value) {
			$output .='<p><B>Male: '.$value->malecount.'</B></p>';
    		$output .='<p><B> Female: '.$value->femalecount.'</B></p>';
    		$output .='<p><B>Total: '.$value->studentcount.' </B></p>'; 
		}
		$output .='</div></div>';
	    return $output;
	}
	function gener_report_bygrade($gradesec,$branch,$max_year){
		$this->db->where('grade',$gradesec);
		$this->db->where('branch',$branch);
		$this->db->where('academicyear',$max_year);
		$this->db->order_by('fname,mname,lname','ASC');
		$query = $this->db->get('users');
		$no=1;
		$query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();//school info
        $school_name=$row_name->name;
        $address=$row_name->address;
        $phone=$row_name->phone;
        $website=$row_name->website;
        $email=$row_name->email;
        $logo=$row_name->logo;
		$output ='<p class="text-center"><b><u>'.$school_name.' gender report for Grade '.$gradesec.'</u></b></p> <div class="table-responsive">
        <table class="table table-striped" style="width:100%;"> 
         <tbody><tr><th><B>No.</B></th><th><B>Name</B></th><th><B>Student ID</B></th><th><B>Grade</B></th><th><B>Branch</B></th><th><B>Gender</B></th></tr>';
		foreach ($query->result() as $row) {
			$output .='<tr>';
			$output .='<td>'.$no.'.</td>';
    		$output .='<td>'.$row->fname.' '.$row->mname.' 
    		'. $row->lname.'</td>';
    		$output .='<td>'.$row->username.'</td>';
    		$output .='<td>'.$row->gradesec.'</td>';
    		$output .='<td>'.$row->branch.'</td>';
    		$output .='<td>'.$row->gender.'</td>';
      		$output .='</tr>'; 
      		$no++;
		}
		$output .=' </tbody> </table> </div>';
		$query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where grade='$gradesec' and academicyear='$max_year' and branch='$branch' GROUP BY academicyear ORDER BY fname ASC");
		$output .='<div class="row"><div class="col-lg-4"> </div> <div class="col-lg-4"></div>
		<div class="col-lg-4">';
		foreach ($query2->result() as $value) {
			$output .='<p><B>Male: '.$value->malecount.'</B></p>';
    		$output .='<p><B> Female: '.$value->femalecount.'</B></p>';
    		$output .='<p><B>Total: '.$value->studentcount.' </B></p>'; 
		}
		$output .='</div></div>';
	    return $output;
	}
	function fetch_gradepayment_category($max_year,$gradesec){
		$query_student=$this->db->query(" Select * from paymentype where grade='$gradesec' and academicyear='$max_year' group by name,grade, month order by id DESC
			");
		$output ='
      <div class="table-responsive">
      <table class="table table-striped table-bordered" id="tableExport" style="width:100%;">
      <tbody><tr><th>Category Name</th><th>Grade</th> <th>Month</th><th>Amount</th><th>Academic Year</th><th>Date Created</th><th>Delete</th></tr>';
    foreach ($query_student->result() as $row) {
			$output .='<tr class="delete_mem'.$row->id.'">';
    	$output .='<td>'.$row->name.'</td>';
    	$output .='<td>'.$row->grade.'</td>';
    	$output .='<td>'.$row->month.'</td>';
    	$output .='<td>'.$row->amount.'</td>';
    	$output .='<td>'.$row->academicyear.'</td>';
    	$output .='<td>'.$row->date_created.'</td>';
    	$output .='<td><button class="btn btn-danger deleteecategory" id='.$row->id.'>Delete</button></td>';
      $output .='</tr>'; 
    }
    $output .=' </tbody> </table> </div>';
    return $output;
	}
	function fetch_payment_category($max_year){
		$this->db->order_by('id','DESC');
		$this->db->group_by('name');
		$query=$this->db->get('paymentype');
		return $query->result();
	}
	function get_staff_payroll($datefrom,$dateto,$payrollbranch,$max_year){
		$query_staffs=$this->db->query(" Select * from users where usertype!='Student' and status='Active' and isapproved='1' and branch='$payrollbranch' and academicyear='$max_year' order by fname ASC ");
		$query_name = $this->db->query("select * from school");
    		$row_name = $query_name->row();
    		$school_name=$row_name->name;
		$output='<h6>'.$school_name.' Staffs Payroll Sheet for Month of___________.</h6><div class="table-responsive">
            <table class="table table-bordered table-hover" style="width:100%;">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Branch</th>
                    <th>Working Days</th>
                    <th>Basic Sallary</th>
                    <th>Quality Allowance</th>
                    <th>Transport Allowance</th>
                    <th>Position Allowance</th>
                    <th>Home Allowance</th>
                    <th>Gross Sallary</th>
                    <th>Taxable Income</th>
                    <th>Income Tax</th>
                    <th>Pension 7%</th>
                    <th>Pension 11%</th>
                    <th>Other</th>
                    <th>Total Deduction</th>
                    <th>Date From</th>
                    <th>Date To</th>
                    <th>Net Payment</th>
                    <th>Sign</th>
                  </tr>
                </thead>';
        $no=1; $total_deduc=0;$net_pay=0;
		foreach ($query_staffs->result() as $getpayrolls) {
			$gsallary= $getpayrolls->gsallary;
            $nsallary= $getpayrolls->netsallary;
            $allowance= $getpayrolls->allowance;
            $total_deduction=$getpayrolls->income_tax + $getpayrolls->pension_7 + $getpayrolls->other;
            $stuid=$getpayrolls->id;
            $query=$this->db->query("select count(absentdate) as total_absent,absentdate,stuid,absentype from attendance  where stuid='$stuid' and absentype='Absent' and absentdate  between '$datefrom' and '$dateto' ");
            foreach ($query ->result() as $keyvalue) {
                $total_absence=$keyvalue->total_absent;
                if($total_absence>0){
                	$ns=$nsallary/30;
                    $monsallary=$total_absence*$ns;
                    $p=$nsallary-$monsallary;
                }else{
                    $p=$nsallary;
                }
                $workingdays=30-$total_absence;
                $output.=' <tr>
                    <td>'.$no.'</td>
                    <td>'.$getpayrolls->fname.' '.$getpayrolls->mname.' '.$getpayrolls->lname.' </td>
                    <td>'.$getpayrolls->branch.'</td>
                    <td>'.$workingdays.'</td>
                    <td>'.$gsallary.'</td>
                    <td>'.$getpayrolls->quality_allowance.'</td>
                    <td>'.$getpayrolls->allowance.'</td>
                    <td>'.$getpayrolls->position_allowance.'</td>
                    <td>'.$getpayrolls->home_allowance.'</td>
                    <td>'.$getpayrolls->gross_sallary.'</td>
                    <td>'.$getpayrolls->taxable_income.'</td>
                    <td>'.ceil($getpayrolls->income_tax).'</td>
                    <td>'.$getpayrolls->pension_7.'</td>
                    <td>'.$getpayrolls->pension_11.'</td>
                    <td>'.$getpayrolls->other.'</td>
                    <td>'.ceil($total_deduction).'</td>
                    <td>'.$this->input->post('datefrom').'</td>
                    <td>'.$this->input->post('dateto').'</td>
                    <td><B>'.floor($p).'</B></td>
                    <td>______</td>
                </tr>';
            } $no++; 
            $total_deduc=$total_deduc+$total_deduction;
            $net_pay=$net_pay + $p; 
        } 
        $output.=' <tr> <td></td>
                    <td><B>Total</B></td>
                    <td>-</td><td>-</td>';
        $query_sum_basicsallary=$this->db->query(" Select *,sum(gsallary) as basic_sallary,sum(quality_allowance) as quality_allowance,sum(allowance) as allowance,sum(position_allowance) as position_allowance,sum(home_allowance) as home_allowance,sum(gross_sallary) as gross_sallary,sum(taxable_income) as taxable_income,sum(income_tax) as income_tax ,sum(pension_7) as pension_7 ,sum(pension_11) as pension_11 ,sum(other) as other from users where usertype!='Student' and status='Active' and isapproved='1' and branch='$payrollbranch' and academicyear='$max_year' order by fname ASC ");
        $row_basicsallary = $query_sum_basicsallary->row();
    	$basicsallary_name=$row_basicsallary->basic_sallary;
    	$quality_allowance=$row_basicsallary->quality_allowance;
    	$allowance=$row_basicsallary->allowance;
    	$position_allowance=$row_basicsallary->position_allowance;
    	$home_allowance=$row_basicsallary->home_allowance;
    	$gross_sallary=$row_basicsallary->gross_sallary;
    	$taxable_income=$row_basicsallary->taxable_income;
    	$income_tax=$row_basicsallary->income_tax;
    	$pension_7=$row_basicsallary->pension_7;
    	$pension_11=$row_basicsallary->pension_11;
    	$other=$row_basicsallary->other;
        $output.='<td>'.$basicsallary_name.'</td>';
        $output.='<td>'.$quality_allowance.'</td>';
        $output.='<td>'.$allowance.'</td>';
        $output.='<td>'.$position_allowance.'</td>';
        $output.='<td>'.$home_allowance.'</td>';
        $output.='<td>'.$gross_sallary.'</td>';
        $output.='<td>'.$taxable_income.'</td>';
        $output.='<td>'.ceil($income_tax).'</td>';
        $output.='<td>'.$pension_7.'</td>';
        $output.='<td>'.$pension_11.'</td>';
        $output.='<td>'.$other.'</td>';
        $output.='<td>'.ceil($total_deduc).'</td>';
        $output.='<td>-</td>';
        $output.='<td>-</td>';
        $output.='<td><B>'.floor($net_pay).'</B></td>';

		$output .='</tr></table></div>';
		$output .='<div class="row">
		<div class="col-lg-4">
		<h6><u>Processed By</u><br>
		Name: ______________________.<br>
		Sign: _______________________.<br>
		Date: _______________________.</h6>
		</div>
		<div class="col-lg-4">
		<h6><u>Checked By</u><br>
		Name: ______________________.<br>
		Sign: _______________________.<br>
		Date: _______________________.
		</h6>
		</div>
		<div class="col-lg-4">
		<h6><u>Approved By</u><br>
		Name: ______________________.<br>
		Sign: _______________________.<br>
		Date: _______________________.
		</h6>
		</div>
		</div>';
		return $output;
	}
	function fetchSubjectExam($max_year){
		$this->db->where(array('Academic_Year'=>$max_year));
		$this->db->group_by('Subj_name');
		$this->db->order_by('Subj_name','ASC');
		$query=$this->db->get('subject');
		return $query->result();
	}
	function fetchSubjectOfThiGrade($selectedSub,$max_year){
		$this->db->where(array('subject.Subj_name'=>$selectedSub));
		$this->db->where(array('subject.Academic_Year'=>$max_year));
		$this->db->where(array('users.usertype'=>'Student'));
		$this->db->group_by('users.gradesec');
		$this->db->from('subject');
		$this->db->join('users', 
	            'users.grade = subject.Grade');
		$query=$this->db->get();
		return $query->result();
	}
	function checkExamSchedule($selectedSub,$grade,$max_year){
		$this->db->where(array('subinfo'=>$selectedSub));
		$this->db->where(array('gradesecinfo'=>$grade));
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->get('examschedule');
		if($query->num_rows()>0){
			return false;
		}else{
			return true;
		}
	}
	function checkSubjectPerDay($selectedDay,$noExams,$grade,$max_year){
		$this->db->where(array('dayinfo'=>$selectedDay));
		$this->db->where(array('gradesecinfo'=>$grade));
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->get('examschedule');
		$totalSubjectPerDay=$query->num_rows();
		if($totalSubjectPerDay<$noExams){
			return true;
		}else{
			return false;
		}
	}
	function checkExaminerteacher($grade,$max_year){
		$query=$this->db->query("select us.fname,us.lname,us.mname,st.staff,st.grade,us.gradesec from staffplacement as st cross join users as us where st.staff=us.username and st.academicyear ='$max_year' and us.academicyear='$max_year' GROUP BY st.staff ORDER BY RAND()");
		return $query->result();
	}
	function checTeacherSchedule($selectedDay,$teacher,$periodInfo,$max_year){
		$this->db->where(array('dayinfo'=>$selectedDay));
		$this->db->where(array('teacherinfo'=>$teacher));
		$this->db->where(array('periodinfo'=>$periodInfo));
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->get('examschedule');
		return $query;
	}
	function viewTeacherExamSchedule($max_year){
		$query=$this->db->query("select * from examschedule where academicyear='$max_year' group by subinfo order by periodinfo  ASC");
		$output='';
		$dayinfoOrder=array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
		if($query->num_rows()>0){
			$output.='<h5 class="header-title text-center"><u>Exam Schedule Summary</u> </h5>';
			$output.='<div class="table-responsive">
            <table class="table table-bordered table-hover" style="width:100%;">
                <thead><tr><th></th>';
                $nofexams=$query->row();
                $querynofexams=$nofexams->nofexam;
                for($i=1;$i<=$querynofexams;$i++){
                	$output.='<th>'.$i.'</th>';
                }
            $output.='</tr></thead>';
            $no=1;
            foreach($dayinfoOrder as $dayinfoOrders){
            	$queryFetchTeacherCh=$this->db->query("select * from examschedule where academicyear='$max_year' and dayinfo ='$dayinfoOrders' order by teacherinfo ASC ");
			    if($queryFetchTeacherCh->num_rows()>0){
            	$output.='<tr><th>'.$dayinfoOrders.'</th>';
            	for($i=1;$i<=$querynofexams;$i++){
			        $queryFetchTeacher=$this->db->query("select * from examschedule where academicyear='$max_year' and dayinfo ='$dayinfoOrders' and periodinfo='$i' order by teacherinfo ASC ");
			            $output.='<td>';
						foreach($queryFetchTeacher->result() as $fetchTeacher){
							$periodOrder=$fetchTeacher->nofexam;
							$periodInfo=$fetchTeacher->periodinfo;
							$output.='';
							$output.='<h5 class="text-bold">'.$fetchTeacher->teacherinfo.'</span> ';
							$output.='<span class="text-info">'.$fetchTeacher->subinfo.'</span>';
							$output.=' <span class="badge badge-success">'.$fetchTeacher->gradesecinfo.'</span>';
							$output.='';
						}
						$output.='</td>';
					}
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
                   <i class="fas fa-exclamation-circle"> </i> Record not found.
            </div></div>';
		}
		return $output;
	}
	function top_rank($max_year,$quarter,$gradesec,$branch,$top,$NoOfquarter){
		$i=1; 
		$output='';
		$query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
	    if(trim($gradesec)===trim('All')){
	    	$query_student=$this->db->query("select * from users as us where us.status='Active' and us.usertype='Student' and us.academicyear='$max_year' and us.isapproved='1' and us.branch='$branch' group by gradesec ");
	    	$output='';
	    	if($query_student->num_rows()>0){
		    	foreach ($query_student->result() as $keResult) {
		    		$gradesec_each=$keResult->gradesec;
		    		$output='<div class="table-responsive"> 
			        <table class="table table-bordered table-hover" style="width:100%;">
			        <thead><tr><th class="text-center" colspan="6">'.$school_name.' rank report in '.$max_year.' Academic Year</th></tr> <tr> <td>Student Name</td> <td>Student ID</td> <td>Grade</td>
			        <td>Quarter</td><td>Average</td> <td>Rank</td> </tr> </thead>';
			        if(trim($top)===trim('All')){
				        $query_rank=$this->db->query("select s.username,s.profile,s.lname, s.fname,s.mname, s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec_each' and rc.rpbranch='$branch' and rc.quarter= '$quarter' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec_each' and rc.rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC ");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec_each' and rc.rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname. '
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)$rankValue->Average/$total_subject,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
					}else{
						$query_rank=$this->db->query("select s.lname, s.username, s.profile,s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec_each' and rc.rpbranch='$branch' and rc.quarter= '$quarter' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.quarter= '$quarter' and rc.academicyear='$max_year' and rc.rpbranch='$branch' and s.branch='$branch' and rc.grade='$gradesec_each' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC limit $top");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec_each' and rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.''.$rankValue->lname. '
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)$rankValue->Average/$total_subject,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
					}
			        $output.='</table></div>';
		    	}
	    	}
	    }else{
	    	$output='<div class="table-responsive"> 
	        <table class="table table-striped table-hover" style="width:100%;">
	        <thead><tr><th class="text-center" colspan="6">'.$school_name.' rank report in '.$max_year.' Academic Year</th></tr> <tr> <td>Student Name</td> <td>Student ID</td> <td>Grade</td>
	        <td>Quarter</td><td>Average</td> <td>Rank</td> </tr> </thead>';
		    if(trim($top)===trim('All')){
		    	if($quarter==trim('All')){
		    		$query_rank=$this->db->query("select s.lname, s.profile, s.username, s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname. '
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)($rankValue->Average/$total_subject)/$NoOfquarter,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
		    	}else{


					$query_rank=$this->db->query("select s.lname, s.profile, s.username, s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.rpbranch='$branch' and rc.quarter= '$quarter' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC");
					$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
					$total_subject=$count_subject->num_rows();
					foreach ($query_rank->result() as $rankValue) {
						$output.='<tr>
						  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname. '
						  </td>
						  <td>'.$rankValue->username.'</td>
						  <td>'.$rankValue->gradesec.'</td>
						  <td>'.$quarter.'</td>
						  <td><B>'.number_format((float)$rankValue->Average/$total_subject,2,'.','').'</B></td>
						  <td>'.$rankValue->stuRank.'</td>
			      		</tr>';	
				    }
				}
			}else{
				if($quarter==trim('All')){
					if(trim($top)===trim('All')){
						$query_rank=$this->db->query("select s.lname, s.profile, s.username, s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname. '
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)($rankValue->Average/$total_subject)/$NoOfquarter,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
					}else{
						$query_rank=$this->db->query("select s.lname, s.username, s.profile,s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC limit $top");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' and letter!='A' and onreportcard='1' and mergedname='' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$gsRank=($rankValue->Average/$total_subject)/$NoOfquarter;
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname. '
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)$gsRank,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
					}

				}else{
					$query_rank=$this->db->query("select s.lname, s.username, s.profile,s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.rpbranch='$branch' and rc.quarter= '$quarter' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC limit $top");
					$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and mergedname='' and academicyear='$max_year' and letter!='A' and onreportcard='1' and mergedname='' group by subject ");
					$total_subject=$count_subject->num_rows();
					foreach ($query_rank->result() as $rankValue) {
						$output.='<tr>
						  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname. '
						  </td>
						  <td>'.$rankValue->username.'</td>
						  <td>'.$rankValue->gradesec.'</td>
						  <td>'.$quarter.'</td>
						  <td><B>'.number_format((float)$rankValue->Average/$total_subject,2,'.','').'</B></td>
						  <td>'.$rankValue->stuRank.'</td>
			      		</tr>';	
				    }
				}
			}
		}
		return $output;
	}
	function top_rankKG($max_year,$quarter,$gradesec,$branch,$top,$NoOfquarter){
		$i=1; 
		$output='';
		$query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
	    if(trim($gradesec)===trim('All')){
	    	$query_student=$this->db->query("select * from users as us where us.status='Active' and us.usertype='Student' and us.academicyear='$max_year' and us.isapproved='1' and us.branch='$branch' group by gradesec ");
	    	$output='';
	    	if($query_student->num_rows()>0){
		    	foreach ($query_student->result() as $keResult) {
		    		$gradesec_each=$keResult->gradesec;
		    		$output='<div class="table-responsive"> 
			        <table class="table table-bordered table-hover" style="width:100%;">
			        <thead><tr><th class="text-center" colspan="6">'.$school_name.' rank report in '.$max_year.' Academic Year</th></tr> <tr> <td>Student Name</td> <td>Student ID</td> <td>Grade</td>
			        <td>Quarter</td><td>Average</td> <td>Rank</td> </tr> </thead>';
			        if(trim($top)===trim('All')){
				        $query_rank=$this->db->query("select s.username,s.profile,s.lname, s.fname,s.mname, s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec_each' and rc.rpbranch='$branch' and rc.quarter= '$quarter' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec_each' and rc.rpbranch='$branch' and  onreportcard='1' and mergedname='' group by stuid order by stuRank ASC ");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec_each' and rc.rpbranch='$branch' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname. '
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)$rankValue->Average/$total_subject,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
					}else{
						$query_rank=$this->db->query("select s.lname, s.username, s.profile,s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec_each' and rc.rpbranch='$branch' and rc.quarter= '$quarter' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.quarter= '$quarter' and rc.academicyear='$max_year' and rc.rpbranch='$branch' and s.branch='$branch' and rc.grade='$gradesec_each' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC limit $top");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec_each' and rpbranch='$branch' and onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.''.$rankValue->lname. '
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)$rankValue->Average/$total_subject,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
					}
			        $output.='</table></div>';
		    	}
	    	}
	    }else{
	    	$output='<div class="table-responsive"> 
	        <table class="table table-striped table-hover" style="width:100%;">
	        <thead><tr><th class="text-center" colspan="6">'.$school_name.' rank report in '.$max_year.' Academic Year</th></tr> <tr> <td>Student Name</td> <td>Student ID</td> <td>Grade</td>
	        <td>Quarter</td><td>Average</td> <td>Rank</td> </tr> </thead>';
		    if(trim($top)===trim('All')){
		    	if($quarter==trim('All')){
		    		$query_rank=$this->db->query("select s.lname, s.profile, s.username, s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.rpbranch='$branch' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and  onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname. '
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)($rankValue->Average/$total_subject)/$NoOfquarter,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
		    	}else{


					$query_rank=$this->db->query("select s.lname, s.profile, s.username, s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.rpbranch='$branch' and rc.quarter= '$quarter' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC");
					$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and  onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
					$total_subject=$count_subject->num_rows();
					foreach ($query_rank->result() as $rankValue) {
						$output.='<tr>
						  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname. '
						  </td>
						  <td>'.$rankValue->username.'</td>
						  <td>'.$rankValue->gradesec.'</td>
						  <td>'.$quarter.'</td>
						  <td><B>'.number_format((float)$rankValue->Average/$total_subject,2,'.','').'</B></td>
						  <td>'.$rankValue->stuRank.'</td>
			      		</tr>';	
				    }
				}
			}else{
				if($quarter==trim('All')){
					if(trim($top)===trim('All')){
						$query_rank=$this->db->query("select s.lname, s.profile, s.username, s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.rpbranch='$branch' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and  onreportcard='1' and mergedname='' and academicyear='$max_year' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname. '
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)($rankValue->Average/$total_subject)/$NoOfquarter,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
					}else{
						$query_rank=$this->db->query("select s.lname, s.username, s.profile,s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.rpbranch='$branch'  and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC limit $top");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and  onreportcard='1' and academicyear='$max_year'  and mergedname='' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$gsRank=($rankValue->Average/$total_subject)/$NoOfquarter;
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname. '
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)$gsRank,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
					}

				}else{
					$query_rank=$this->db->query("select s.lname, s.username, s.profile,s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.rpbranch='$branch' and rc.quarter= '$quarter' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC limit $top");
					$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and  onreportcard='1'  and academicyear='$max_year'  and mergedname='' group by subject ");
					$total_subject=$count_subject->num_rows();
					foreach ($query_rank->result() as $rankValue) {
						$output.='<tr>
						  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname. '
						  </td>
						  <td>'.$rankValue->username.'</td>
						  <td>'.$rankValue->gradesec.'</td>
						  <td>'.$quarter.'</td>
						  <td><B>'.number_format((float)$rankValue->Average/$total_subject,2,'.','').'</B></td>
						  <td>'.$rankValue->stuRank.'</td>
			      		</tr>';	
				    }
				}
			}
		}
		return $output;
	}
	function topGradeTopRank($max_year,$quarter,$gradesec,$branch,$top){
		$i=1; 
		$output='';
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$queryStudent=$this->db->query("select gradesec from users where academicyear='$max_year' and status='Active' and isapproved='1' and branch='$branch' and grade='$gradesec' group by gradesec ");
		if($queryStudent->num_rows()>0){
			$output.='<h3 class ="text-center"><u>'.$school_name.' Rank report for Grade '.$gradesec.' in '.$max_year.' Academic Year</u></h3>';
	    
	    	$output.='<div class="table-responsive"> 
	        <table class="table table-bordered table-hover" style="width:100%;">
	        <thead> <tr> <th>Student Name</th> <th>Student ID</th> <th>Grade</th>
	        <th>Quarter</th><th>Average</th> <th>Rank</th> </tr> </thead>';
		    if(trim($top)===trim('All')){
		    	foreach ($queryStudent->result() as $gValue) {
		    		$gradesecc=$gValue->gradesec;
					$queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesecc.$max_year."' ");
					if ($queryCheck->num_rows()>0)
					{
						$query_rank=$this->db->query("select s.username, s.profile,s.lname, s.fname, s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesecc.$max_year." as rc cross join users as s where s.id=rc.stuid and s.grade='$gradesec' and rc.rpbranch='$branch' and rc.quarter= '$quarter' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesecc.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and s.grade='$gradesec' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC");
						$count_subject=$this->db->query("select * from reportcard".$gradesecc.$max_year." as rc cross join users as us where us.grade='$gradesec' and rc.rpbranch='$branch' and rc.letter!='A' and us.gradesec=rc.grade and rc.onreportcard='1' and mergedname='' and rc.academicyear='$max_year' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname.'
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)$rankValue->Average/$total_subject,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
					}
				}
			}else{
				foreach ($queryStudent->result() as $gValue) {
		    		$gradesecc=$gValue->gradesec;
					$queryCheck = $this->db->query("SHOW TABLES LIKE 'reportcard".$gradesecc.$max_year."' ");
					if ($queryCheck->num_rows()>0)
					{
						$query_rank=$this->db->query("select s.username, s.profile, s.fname,s.fname,s.lname, s.mname, s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesecc.$max_year." as rc cross join users as s where s.id=rc.stuid and s.grade='$gradesec' and rc.rpbranch='$branch' and rc.quarter= '$quarter' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesecc.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and s.grade='$gradesec' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC limit $top");
						$count_subject=$this->db->query("select * from reportcard".$gradesecc.$max_year." as rc cross join users as us where us.grade='$gradesec' and rc.rpbranch='$branch' and rc.letter!='A' and us.gradesec=rc.grade and rc.onreportcard='1' and mergedname='' and rc.academicyear='$max_year' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname.'
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)$rankValue->Average/$total_subject,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
					}
				}
			}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div>';
		}
    	return $output;
	}
	function topDivTopRank($max_year,$quarter,$gradesec,$top){
		$i=1; 
		$output='';
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
	    	$query_student=$this->db->query("select * from users as us where us.status='Active' and us.usertype='Student' and us.academicyear='$max_year' and us.isapproved='1' and us.gradesec='$gradesec' group by gradesec ");
	    	if($query_student->num_rows()>0){
	    		$output.='<h3 class ="text-center"><u>'.$school_name.' Rank report for Grade '.$gradesec.' in '.$max_year.' Academic Year</u></h3>';
		    	foreach ($query_student->result() as $keResult) {
		    		//$gradesec_each=$keResult->gradesec;
		    		$output.='<div class="table-responsive"> 
			        <table class="table table-bordered table-hover" style="width:100%;">
			        <thead> <tr> <th>Student Name</th> <th>Student ID</th> <th>Branch</th> <th>Grade</th>
			        <th>Quarter</th><th>Average</th> <th>Rank</th> </tr> </thead>';
			        if(trim($top)===trim('All')){
				        $query_rank=$this->db->query("select s.branch,s.username, s.profile, s.fname,s.mname,s.lname, s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.quarter= '$quarter' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.quarter= '$quarter' and rc.academicyear='$max_year'  and rc.grade='$gradesec' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC ");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter!='A' and onreportcard='1' and academicyear='$max_year' and mergedname='' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname.'
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->branch.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)$rankValue->Average/$total_subject,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
					}else{
						$query_rank=$this->db->query("select s.username, s.branch, s.profile, s.fname,s.mname,s.lname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.quarter= '$quarter' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.quarter= '$quarter' and rc.academicyear='$max_year' and rc.grade='$gradesec' and letter!='A' and onreportcard='1' and mergedname='' group by stuid order by stuRank ASC limit $top");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter!='A' and onreportcard='1' and academicyear='$max_year' and mergedname='' group by subject ");
						$total_subject=$count_subject->num_rows();
						foreach ($query_rank->result() as $rankValue) {
							$output.='<tr>
							  <td><img src="'.base_url().'/profile/'.$rankValue->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$rankValue->fname.' '.$rankValue->mname.' '.$rankValue->lname.'
							  </td>
							  <td>'.$rankValue->username.'</td>
							  <td>'.$rankValue->branch.'</td>
							  <td>'.$rankValue->gradesec.'</td>
							  <td>'.$quarter.'</td>
							  <td><B>'.number_format((float)$rankValue->Average/$total_subject,2,'.','').'</B></td>
							  <td>'.$rankValue->stuRank.'</td>
				      		</tr>';	
					    }
					}
			        $output.='</table></div>';
		    	}
	    	}
	    
    	return $output;
	}
	function markStatisticsDirector($max_year,$gs_branches,$gs_gradesec,$subjects,$gs_quarters,$less_than,$greater_than)
	{
		$output='';
			$query_name = $this->db->query("select * from school");
			$row_name = $query_name->row_array();
			$school_name=$row_name['name'];
			$school_slogan=$row_name['slogan'];
			$output.='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
			    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
			    &nbsp;&nbsp;	Season : '.$gs_quarters.'';
		    $output.='<B><u>'. $gs_quarters.' </u>,</B> ';
		    	
			$output.='&nbsp;&nbsp;	Subject :<B><u>'.$subjects.'</u></B></div></br></h6>';
			$output.='<div class="table-responsive">
			<table class="tabler table-borderedr table-hover" style="width:100%;">
			<tr>
			<th>No.</th>
	    	<th>Student Name</th>
	    	<th>Student ID</th>
	    	<th>Gender</th>
	    	<th class="text-center">Total</th></tr>';
	    	$stuNO=1;
	    	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec,u.gender from users as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname ASC ");
	    	$m=0;$f=0;
			foreach ($queryStudent->result() as $row) { 
	    		$id=$row->id;
	    		$average=0;
	    		/*foreach($gs_quarter as $gs_quarters){*/
					$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarters' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");
		    		foreach ($evalname_query->result_array() as $mark_name)
		    		{
		    			$outofTot=0;$totalMark=0;
		    			$percent= $mark_name['percent'];
		    			$mname_gs=$mark_name['eid'];
		        		$query_value = $this->db->query("select value,markname,sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarters.$max_year." where quarter='$gs_quarters' and evaid='$mname_gs' and stuid='$id' and mbranch='$gs_branches' and subname='$subjects' group by markname order by mid ASC");
						$sumOutOf=0;
						foreach ($query_value->result_array() as $value) {
							$markNameStu=$value['markname'];
							$outofTot=$outofTot+$value['outof'];
							$totalMark=$totalMark+$value['value'];
				            $sumOutOf=$value['outof'] + $sumOutOf;	
		        		}
		        		if($sumOutOf>0){
		        			$conver= ($totalMark *$percent )/$sumOutOf;
		    				$average =($conver + $average);
		        		}
		        	}
		        /*}*/
	        	if($average <=$less_than and $average >=$greater_than){
	        		if($row->gender=='Male' || $row->gender=='M' || $row->gender=='male' ){
	        			$m=$m+1;
	        		}else{
	        			$f=$f+1;
	        		}
	        		$output.='<tr><td>'.$stuNO.'.</td> <td>'.$row->fname.' '.$row->mname.' '.$row->lname. '</td>';
	    			$output.='<td>'.$row->username.' </td>';
	    			$output.='<td>'.$row->gender.' </td>';
	        		$output.='<td style="text-align:center;"><B>'.number_format((float)$average,2,'.','').'</B></td>';
	        	}
	    		$average=0;
				$stuNO++;
			}
			$totalGender=$f+$m;
			$output.='</table></div>';
			$output.='<h5 class="card-title text-center">Male: '.$m.' </h5> <h5 class="card-title text-center">Female: '.$f.'</h5><h5 class="card-title text-center">Total: <b>'.$totalGender.'</b></h5>';
			$output.='<p class="text-center">'.$school_slogan.'!</p>';
    	return $output;
    	/*
		$output='';
		$query=$this->db->query("select us.fname, us.mname,us.lname, us.profile, us.gradesec,us.username, rc.total, rc.subject, rc.quarter,rc.total from reportcard".$grade.$max_year." as rc cross join  users as us where us.id=rc.stuid and rc.quarter='$quarter' and rc.academicyear='$max_year' and us.branch='$branch' and us.gradesec ='$grade' and rc.total <='$less_than' and rc.total >='$greater_than' order by rc.total DESC ");
		if($query->num_rows()>0){
			$query_name = $this->db->query("select * from school");
     		$row_name = $query_name->row();
      		$school_name=$row_name->name;
			$output .='<div class="table-responsive"> 
            <table class="table table-bordered table-hover" style="width:100%;">
            <thead><tr> <th class="text-center" colspan="6">'.$school_name.' Mark statistics for Grade:'.$grade.' Season:'.$quarter.'  </th></tr>
             <tr> <td>No.</td><td>Student Name</td> 
            <td>Student ID</td><td>Grade</td><td>Subject</td> <td>Average</td> 
            </tr> </thead>';
            $no=1;
    		foreach ($query->result() as $toprank) {
				$output.='<tr><td>'.$no.'.</td>
			  	<td><img src="'.base_url().'/profile/'.$toprank->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$toprank->fname.' '.$toprank->mname.' '.$toprank->lname.'
			  	</td>
			  	<td>'.$toprank->username.'</td>
			  	<td>'.$toprank->gradesec.'</td>
			  	<td>'.$toprank->subject.'</td>
			  	<td>'.$toprank->total.'</td>
      			</tr>';
      			$no++;
    		}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No data found at this range.
            </div></div>';
		}
    	return $output;*/
	}
	function mark_statistics($max_year,$gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$less_than,$greater_than,$countQuarter){
		$output='';
			$query_name = $this->db->query("select * from school");
			$row_name = $query_name->row_array();
			$school_name=$row_name['name'];
			$school_slogan=$row_name['slogan'];
			$output.='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
			    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
			    &nbsp;&nbsp;	Season :';
		    foreach($gs_quarter as $gs_quarters){
		    	if($countQuarter >1){
		    		$output.='<B><u>'. $gs_quarters.' </u>,</B> ';
		    	}else{
		    		$output.='<B><u>'. $gs_quarters.'</u></B> ';
		    	}
			}
			$output.='&nbsp;&nbsp;	Subject :<B><u>'.$gs_subject.'</u></B></div></br></h6>';
			$output.='<div class="table-responsive">
			<table class="tabler table-borderedr table-hover" style="width:100%;">
			<thead>
			<tr>
			<th>No.</th>
	    	<th>Student Name</th>
	    	<th>Student ID</th>
	    	<th>Gender</th>
	    	<th class="text-center">Total</th></tr>';
	    	$stuNO=1;
	    	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec,u.gender from users as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname ASC ");
	    	$m=0;$f=0;
			foreach ($queryStudent->result() as $row) { 
	    		$id=$row->id;
	    		$average=0;
	    		foreach($gs_quarter as $gs_quarters){
					$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarters' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");
		    		foreach ($evalname_query->result_array() as $mark_name)
		    		{
		    			$outofTot=0;$totalMark=0;
		    			$percent= $mark_name['percent'];
		    			$mname_gs=$mark_name['eid'];
		        		$query_value = $this->db->query("select value,markname,sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarters.$max_year." where  subname='$gs_subject' and quarter='$gs_quarters' and evaid='$mname_gs' and stuid='$id' and mbranch='$gs_branches' group by markname order by mid ASC");
						$sumOutOf=0;
						foreach ($query_value->result_array() as $value) {
							$markNameStu=$value['markname'];
							$outofTot=$outofTot+$value['outof'];
							$totalMark=$totalMark+$value['value'];
				            $sumOutOf=$value['outof'] + $sumOutOf;	
		        		}
		        		if($sumOutOf>0){
		        			$conver= ($totalMark *$percent )/$sumOutOf;
		    				$average =($conver + $average)/$countQuarter;
		        		}
		        	}
		        }
	        	if($average <=$less_than and $average >=$greater_than){
	        		if($row->gender=='Male' || $row->gender=='M' || $row->gender=='male' || $row->gender=='MALE'){
	        			$m=$m+1;
	        		}else{
	        			$f=$f+1;
	        		}
	        		$output.='<tr><td>'.$stuNO.'.</td> <td>'.$row->fname.' '.$row->mname.' '.$row->lname. '</td>';
	    			$output.='<td>'.$row->username.' </td>';
	    			$output.='<td>'.$row->gender.' </td>';
	        		$output.='<td style="text-align:center;"><B>'.number_format((float)$average,2,'.','').'</B></td>';
	        	}
	    		$average=0;
				$stuNO++;
			}
			$totalGender=$f+$m;
			$output.='</table></div>';
			$output.='<h5 class="card-title text-center">Male: '.$m.' </h5> <h5 class="card-title text-center">Female: '.$f.'</h5><h5 class="card-title text-center">Total: <b>'.$totalGender.'</b></h5>';
			$output.='<p class="text-center">'.$school_slogan.'!</p>';
    	return $output;
	}
	function view_student_detail($id){
		$this->db->where(array('id'=>$id));
		$query=$this->db->get('users');
		return $query->result();
	}
	function Fetch_experience($emp_id){
		$this->db->where(array('id'=>$emp_id));
		$query=$this->db->get('users');
		$output='';
		foreach ($query ->result() as $kevalue) {
			$query_name = $this->db->query("select * from school");
    		$row_name = $query_name->row();
    		$school_name=$row_name->name;
    		$school_logo=$row_name->logo;
			$output.='<div class="row">
			<div class="col-lg-8">
			<p><h4 class="pull-right"><B>'.$school_name.' ';
			$output.='<img alt="image" src="'.base_url().'
			/logo/'.$school_logo.'"style="height:35px; width:35px; border-radius:3em;" class="header-logo"/></B></h4></p></div>
			<div class="col-lg-4"></div></div>';
			$output.='<div class="row"> <div class="col-lg-6">
			<p><B><U><h6 class="pull-right">To Whome It May Concern</h6></U></B>';
			$output.='</div>';

			$output.='<div class="col-lg-6">
			<p class="m-t-30 pull-right">Date: '.date('M/d/Y').'<br>';
			$output.='Number: '.date('M/d/Y').'</p>';
			$output.='</div></div>';

			$output.='<ul class="list-unstyled user-progress list-unstyled-border list-unstyled-noborder">
                <li class="media"> <div class="media-body"> 
                <div class="media-title"> ';
			$output.='<p class="m-t-10">Mr/Miss <b>'.$kevalue->fname.' '.$kevalue->mname.' </b> has been employed as <b>'.$kevalue->usertype.'</b> in <b>'.$school_name.'</b> since <b>'.$kevalue->dateregister.'</b> till '.date('M-d-Y').'. ';
			$output.=' He/She was employeed with Gross Sallary of <b>'.$kevalue->gross_sallary.'</b> Br. which is taxed to the Authorized government office.</p>';
			$output.='<p> He/She was active and self-motivated individual,who is able to perform well under pressure & who can bring to the table solid capabilities. We hope he/she will help you in any aspect of your organizations duty.</p>';
			$output.='</div> </div> </li></u>';

			$output.='<p class="m-t-30 pull-right">Greetings.<br>';
			
		}
		return $output;
	}
	function insert_promotion_policy($data,$max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->insert('promotion_policy',$data);
	}
	function update_promotion_policy($data,$kepolicy_grade,$max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->where('grade',$kepolicy_grade);
		$this->db->update('promotion_policy',$data);
	}
	function insert_rank_policy($data,$max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->insert('rank_allowed_grades',$data);
	}
	function update_rank_policy($kepolicy_grade,$max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->where('grade',$kepolicy_grade);
		$this->db->delete('rank_allowed_grades');
	}

	function fetch_promotion_policy($max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(grade) as gradess from promotion_policy where academicyear ='$max_year'  GROUP BY average ORDER BY grade ASC");
		$output='<div class="table-responsive">
            <table class="table table-striped table-md">
                <tr>
                    <th>No.</th>
                    <th>Percentage</th>
                    <th>Grade</th>
                    <th>Academic Year</th>
                </tr>';
        $no=1;
		foreach ($query->result() as $kvalue) {
			$output.='<tr><td>'.$no.'.</td>
			<td>'.$kvalue->average.'</td>
			<td>'.$kvalue->gradess.'</td>
			<td>'.$kvalue->academicyear.' </tr>';
			$no++;
		}
		$output.='</table></div>';
		return $output;
	}
	function fetchSchoolDivision($max_year){
		$query=$this->db->query("SELECT * from schooldivision where academicyear ='$max_year'  GROUP BY dname ORDER BY dname ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive">
	            <table class="table table-striped table-md">
	                <tr>
	                    <th>No.</th>
	                    <th>Division Name</th>
	                    <th>Academic Year</th>
	                    <th>Date Created</th>
	                </tr>';
	        $no=1;
			foreach ($query->result() as $kvalue) {
				$output.='<tr><td>'.$no.'.</td>
				<td>'.$kvalue->dname.'
				<div class="table-links">
	             <a href="#" class="deleteDivision text-danger" id="'.$kvalue->did.'">Delete</a>
	            </div> </td>
				<td>'.$kvalue->academicyear.'</td><td>'.$kvalue->datecreated.'.</td> </tr>';
				$no++;
			}
			$output.='</table></div>';
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
	function fetchSchoolAssesment($max_year){
		$query=$this->db->query("SELECT * from schoolassesment where academicyear ='$max_year' GROUP BY saseval ORDER BY saseval ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive">
	            <table class="tabler table-borderedr" width="100%">
	                <tr>
	                    <th>No.</th>
	                    <th>Evaluation Name</th>
	                    <th>Grade</th>
	                    <th>Assesment Name</th>
	                    <th>End Date</th>
	                    <th>Date Created</th>
	                    <th>Academic Year</th>
	                </tr>';
	        $no=1;
			foreach ($query->result() as $kvalue) {
				$saseval=$kvalue->saseval;
				$querySasName=$this->db->query("SELECT * from schoolassesment where academicyear ='$max_year' and saseval='$saseval' ORDER BY sasname ASC");
				$numRows=$querySasName->num_rows() + 1;
				$output.='<tr><td rowspan='.$numRows.'>'.$no.'.</td>';
				$output.='<td rowspan='.$numRows.'>'.$kvalue->saseval.' <a href="#" class="deleteAssesment text-danger" id="'.$kvalue->saseval.'"><i class="fas fa-trash-alt"> </i></a> </td>';
				foreach($querySasName->result() as $queryNames){
					$output.='<tr><td>'.$queryNames->sasgrade.'  
						<a href="#" class="deleteAssesmentSasName text-danger" id="'.$queryNames->sasname.'" value="'.$queryNames->sasgrade.'">
						<i class="fas fa-trash-alt"> </i></a> </td> 
					<td>'.$queryNames->sasname.'</td> 
					<td>'.$queryNames->dateend.'</td> 
					<td>'.$queryNames->datecreated.'</td>
					<td>'.$queryNames->academicyear.'.</td></tr>';
				}
				$output.='</tr>';
				$no++;
			}
			$output.='</table></div>';
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
	function fetchrcComments($max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(grade) as gradess from reportcardcomments where academicyear ='$max_year' GROUP BY commentvalue ORDER BY mingradevalue ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive">
	            <table class="table table-striped table-md">
	                <tr>
	                    <th>No.</th>
	                    <th>Grade</th>
	                    <th>Value</th>
	                    <th>Comments</th>
	                    <th>Date Created</th>
	                    <th>Academic Year</th>
	                </tr>';
	        $no=1;
			foreach ($query->result() as $kvalue) {
				$output.='<tr><td>'.$no.'.</td>
				<td>'.$kvalue->gradess.'
				<div class="table-links">
	             <a href="#" class="deleteCommentValue text-danger" name="'.$kvalue->maxgradevalue.'"  value="'.$kvalue->mingradevalue.'" id="'.$kvalue->commentvalue.'">Delete</a>
	            </div> </td>
				<td>>='.$kvalue->mingradevalue.' && < '.$kvalue->maxgradevalue.'</td><td>'.$kvalue->commentvalue.'</td> 
				<td>'.$kvalue->datecreated.'</td><td>'.$kvalue->academicyear.'.</td></tr>';
				$no++;
			}
			$output.='</table></div>';
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
	function fetch_rank_policy($max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(grade) as gradess from rank_allowed_grades where academicyear ='$max_year' and allowed='1' GROUP BY allowed ORDER BY grade ASC");
		$output='<div class="table-responsive">
            <table class="table table-striped table-md">
                <tr>
                    <th>No.</th>
                    <th>Grade</th>
                    <th>Academic Year</th>
                </tr>';
        $no=1;
		foreach ($query->result() as $kvalue) {
			$output.='<tr><td>'.$no.'.</td>
			<td>'.$kvalue->gradess.'</td>
			<td>'.$kvalue->academicyear.' </tr>';
			$no++;
		}
		$output.='</table></div>';
		return $output;
	}
	function fetch_letter_policy($max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(grade) as gradess from letterange where academicYear ='$max_year' GROUP BY minValue,maxiValue,letterVal ORDER BY grade ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive">
	            <table class="table table-striped">
	                <tr>
	                    <th>No.</th>
	                    <th>Grade</th>
	                    <th>Range</th>
	                    <th>Value</th>
	                    <th>Academic Year</th>
	                </tr>';
	        $no=1;
			foreach ($query->result() as $kvalue) {
				$output.='<tr class="drange'.$kvalue->leId.'"><td>'.$no.'.</td>
				<td>'.$kvalue->gradess.'
				<div class="table-links">
	             <a href="#" class="deleteLetterPolicy text-danger" id="'.$kvalue->leId.'">Delete</a>
	            </div>

				</td>
				<td>'.$kvalue->minValue.' - '.$kvalue->maxiValue.'</td>
				<td>'.$kvalue->letterVal.'</td>
				<td>'.$kvalue->academicYear.' </tr>';
				$no++;
			}
			$output.='</table></div>';
		}
		else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No letter range found.
            </div></div>';
		}
		return $output;
	}
	function lastID($max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->where('usertype','Student');
		$this->db->select_max('unique_id');
		$query=$this->db->get('users');
		$output='';
		foreach ($query->result() as $alue) {
			$output.='<small><span class="text-dark">Last Student ID is '.$alue->unique_id.'.</span></small>';
		}
		return $output;
	}
	function fetch_grade_from_staffplaceDir($user,$max_year){
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
	function check_grade_markprogress($branch,$gs_gradesecs,$gs_quarter,$max_year)
	{
		$output='';
		foreach($gs_gradesecs as $gs_gradesec){
			$this->db->where('us.academicyear',$max_year);
			$this->db->where('us.branch',$branch);
			$this->db->where('us.gradesec',$gs_gradesec);
			$this->db->where('s.Academic_Year',$max_year);
			$this->db->group_by('s.Subj_name');
			$this->db->order_by('s.suborder','ASC');
			$this->db->select('us.usertype,us.username,us.fname,us.mname,s.Grade, s.Subj_name, us.grade, us.id');
			$this->db->from('users us');
			$this->db->join('subject s', 
	            'us.grade=s.Grade and us.usertype="Student" ','left');
			$query=$this->db->get();
			if($query->num_rows()>0){
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
				$output.='<div style="width:100%;height:auto;page-break-inside:avoid;"><h4 class="text-center"><u>'.$school_name.' mark analysis for Grade: '.$gs_gradesec.' in '.$max_year.'Academic Year</u></h4>';
				$output .='<div class="table-responsive">
		        <table class="table table-borderedr">
		        <tr>
		          	<th>Subject Name</th>
		            <th>Assesment Name</th>           
		            <th>Added mark(%)</th>
		            <th>Remaining mark(%)</th>
				</tr>';
				ob_start();
				foreach ($query->result() as $progress_value) {
					$subject=$progress_value->Subj_name;
					$grade=$progress_value->grade;
					$staff=$progress_value->username;
					$stuid=$progress_value->id;
					$output.='<tr><td>'.$progress_value->Subj_name.'</td>';
					$queryEvaluation=$this->db->query("select * from evaluation where quarter='$gs_quarter' and academicyear='$max_year' and grade='$grade' ");
					if($queryEvaluation->num_rows()>0){
						$sumPercent=0;
						foreach ($queryEvaluation->result() as $evalue)
						{
							$eid=$evalue->eid;
							$percent=$evalue->percent;
							$queryMark=$this->db->query("select * from mark".$branch.$gs_gradesec.$gs_quarter.$max_year." as m cross join users as ur where ur.id=m.stuid and m.evaid='$eid' and m.subname='$subject' and m.quarter='$gs_quarter' and m.academicyear='$max_year' and ur.academicyear='$max_year' and ur.usertype='Student' and ur.status='Active' and ur.isapproved='1' and ur.gradesec='$gs_gradesec' and ur.branch='$branch' and m.mbranch='$branch' ");
							if($queryMark->num_rows()>0){
								$sumPercent=$percent + $sumPercent;
							}
						}
						$queryAssesment=$this->db->query("select * from mark".$branch.$gs_gradesec.$gs_quarter.$max_year." as m cross join users as ur where ur.id=m.stuid  and m.subname='$subject' and m.quarter='$gs_quarter' and m.academicyear='$max_year' and ur.academicyear='$max_year' and ur.usertype='Student' and m.mbranch='$branch' and ur.status='Active' and ur.isapproved='1' and ur.gradesec='$gs_gradesec' and ur.branch='$branch' group by m.markname order by mid ASC");
						$output .='<td>';
						foreach ($queryAssesment->result() as $assesValue) {
							$assesmentName=$assesValue->markname;
							$output .='<div class="badge badge-info"> '.$assesmentName. ',</div>';
						}
						$remainingMark=100-$sumPercent;
						$output .='</td>';
						if($sumPercent<='20'){
							$output .='<td><div class="badge badge-danger">'.$sumPercent.' </div> </td>';
						}else if($sumPercent <='40'){
							$output .='<td><div class="badge badge-warning"> '.$sumPercent.' </div> </td>';
						}else if($sumPercent<='50'){
							$output .='<td><div class="badge badge-info"> '.$sumPercent.'</div> </td>';
						}else if($sumPercent<'100'){
							$output .='<td><div class="badge badge-primary"> '.$sumPercent.'</div> </td>';
						}else if($sumPercent=='100'){
							$output .='<td><div class="badge badge-success">'.$sumPercent.' </div> </td>';
						}
						if($remainingMark<='20'){
							$output .='<td><div class="badge badge-success">'.$remainingMark.' </div> </td>';
						}else if($remainingMark <='40'){
							$output .='<td><div class="badge badge-primary"> '.$remainingMark.' </div> </td>';
						}else if($remainingMark<='50'){
							$output .='<td><div class="badge badge-info"> '.$remainingMark.'</div> </td>';
						}else if($remainingMark<'100'){
							$output .='<td><div class="badge badge-warning"> '.$remainingMark.'</div> </td>';
						}else if($remainingMark=='100'){
							$output .='<td><div class="badge badge-danger">'.$remainingMark.' </div> </td>';
						}
					}else{
						$output.='<td><span class="text-danger">No Evaluation found.</span></td>';
					}
					$output.='</tr>';
				}
				$output .= ob_get_clean();
			}else{
				$output .='<div class="alert alert-warning alert-dismissible show fade">
	                <div class="alert-body">
	                    <button class="close"  data-dismiss="alert">
	                        <span>&times;</span>
	                    </button>
	                <i class="fas fa-check-circle"> </i> No record found.
	            </div></div>';
			}
			$output .='</table></div></div>';
		}

		return $output;
	}
	function filter_evaluation4analysis($mybranch,$gradesec,$max_year,$quarter)
	{
		$query=$this->db->query("select ev.eid,ev.evname from evaluation as ev cross join users as u where u.grade=ev.grade and u.academicyear='$max_year' and u.status='Active' and u.isapproved='1' and u.usertype='Student' and ev.academicyear='$max_year' and u.gradesec='$gradesec' and u.branch='$mybranch' and ev.quarter='$quarter' group by evname order by eid ASC ");
		$output='<select class="form-control selectric" required="required" name="branch" id="evaluationanalysis">';
		foreach ($query->result() as $evavalue) {
			$output.='<option value='.$evavalue->eid.'>'.$evavalue->evname.'</option>';	
		}
		$output.='</select>';
		return $output;
	}
	function filter_evaluation4analysisGrand($mybranch,$gradesecs,$max_year,$quarter)
	{
		$output='';
		foreach($gradesecs as $gradesec){
			$query=$this->db->query("select ev.eid,ev.evname from users as u right join evaluation as ev ON u.grade=ev.grade where u.academicyear='$max_year' and u.status='Active' and u.isapproved='1' and u.usertype='Student' and ev.academicyear='$max_year' and u.gradesec='$gradesec' and u.branch='$mybranch' and ev.quarter='$quarter' group by ev.evname , ev.grade order by eid ASC ");
			$output='<div class="row"> ';
			foreach ($query->result() as $evavalue) {
				$output.='<div class="col-lg-12 col-12">
	                <div class="pretty p-icon p-jelly p-round p-bigger">
	                  <input type="checkbox" name="evaluationanalysis" value="'.$evavalue->eid.'" class="evaluationanalysis" id="customCheck1">
	                  <div class="state p-success">
	                    <i class="icon material-icons"></i>
	                    <label></label>
	                  </div>
	                </div> '.$evavalue->evname.'
	            </div>';
			}
			$output.='</div>';
		}
		return $output;
	}
	function filterSubject4Analysis($mybranch,$gradesec,$max_year,$quarter)
	{
		$query=$this->db->query("select su.Subj_name,su.Subj_Id from subject as su cross join users as u where u.grade=su.Grade and u.academicyear='$max_year' and u.status='Active' and u.isapproved='1' and u.usertype='Student' and su.Academic_Year='$max_year' and u.gradesec='$gradesec' and u.branch='$mybranch' group by Subj_name order by Subj_name ASC ");
		$output='<select class="form-control selectric" required="required" name="branch" id="subevaluationanalysis">';
		foreach ($query->result() as $evavalue) {
			$output.='<option value='.$evavalue->Subj_name.'>'.$evavalue->Subj_name.'</option>';	
		}
		$output.='</select>';
		return $output;
	}
	function filterSubject4TeAnalysis($mybranch,$gradesec,$max_year,$user)
	{
		$this->db->where('grade',$gradesec);
	    $this->db->where('staff',$user);
	    $this->db->where(array('academicyear'=>$max_year));
	    $this->db->order_by('grade','ASC');
	    $this->db->group_by('subject');
	    $query=$this->db->get('staffplacement');
	    $output='<select class="form-control selectric" required="required" name="branch" id="subevaluationanalysis">';
		foreach ($query->result() as $evavalue) {
			$output.='<option value='.$evavalue->subject.'>'.$evavalue->subject.'</option>';	
		}
		$output.='</select>';
		return $output;

		/*$query=$this->db->query("select su.Subj_name,su.Subj_Id from subject as su cross join users as u where u.grade=su.Grade and u.academicyear='$max_year' and u.status='Active' and u.isapproved='1' and u.usertype='Student' and su.Academic_Year='$max_year' and u.gradesec='$gradesec' and u.branch='$mybranch' group by Subj_name order by Subj_name ASC ");
		$output='<select class="form-control selectric" required="required" name="branch" id="subevaluationanalysis">';
		foreach ($query->result() as $evavalue) {
			$output.='<option value='.$evavalue->Subj_Id.'>'.$evavalue->Subj_name.'</option>';	
		}
		$output.='</select>';
		return $output;*/
	}
	function fetchCustomAnalysis($branch,$gradesecs,$quarter,$evaluations,$subjectGsanalysis,$max_year)
	{
		$output='';
		$query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;

        $queryac = $this->db->query("select max(year_name) as ay from academicyear");
		$rowac = $queryac->row();
		$yearname=$rowac->ay;    		
		foreach($subjectGsanalysis as $subject){
			foreach($gradesecs as $gradesec){
				$queryHTeacher=$this->db->query("SELECT * from staffplacement as st cross join users as us where st.staff=us.username and st.academicyear ='$max_year' and st.grade='$gradesec' and us.branch='$branch' and subject='$subject'  GROUP BY st.staff ORDER BY st.staff ASC");

				$querySubject=$this->db->query("select u.username,u.fname,u.lname, u.mname,u.grade ,u.id, u.gender from users as u where u.academicyear='$max_year' and u.status='Active' and u.isapproved='1' and u.usertype='Student' and u.branch='$branch' and u.gradesec='$gradesec' group by u.id order by u.fname,u.mname,u.lname ASC");
				if($querySubject->num_rows()>0){
					
		    		$output.='<div class="text-center"><h4><u><b> '.$school_name.' '.$yearname.'E.C  Academic Year</b></u> <u><b> <br></h4><h6>Subject: '.$subject.' Quarter: '.$quarter.' Result Analysis for Grade <u><b>'.$gradesec.'</b></u></h6></div>';
					if($queryHTeacher->num_rows()>0){
						$tName=$queryHTeacher->row_array();
						$fName=$tName['fname'];
						$mName=$tName['mname'];
						$output.='<h6 class="text-center">Teachers Name:'.$fName.' '.$mName.'</h6>';
					}else{
						$output.='<h6 class="text-center">Teachers Name:______________</h6>';
					}
					$output .=' <div class="table-responsive" width="100%" height="100%">
        			<table id="ENS" class="tabler table-borderedr" width="100%" cellspacing="5" cellpadding="5">
			        <tr>
			        <th>No.</span></th>
			        <th>Student Name</th><th>Gender</th>';
		          	$no=1;
		          	foreach($evaluations as $evaluation){
		          		$output.='<th class="text-center">'.$evaluation.'</th>';
		          	}
					$output.='<th class="text-center">Remark</th></tr>';
					$queryStudent=$this->db->query("select * from users where academicyear='$max_year' and status='Active' and isapproved='1' and usertype='Student' and branch='$branch' and gradesec='$gradesec' group by id  order by fname ASC");
					foreach ($queryStudent->result() as $studentvalue) {
						$grade=$studentvalue->grade;
						$stuid=$studentvalue->id;
						$output.='<tr><td>'.$no.'</td>';
						$allSubTotal=0;
						$output.='<td>'.$studentvalue->fname.' '.$studentvalue->mname. ' '.$studentvalue->lname. '</td>';
						$output.='<td>'.$studentvalue->gender.'</td>';
						foreach($evaluations as $evaluation){
							$queryMarkOutof = $this->db->query("select sum(value) as total,sum(outof) as outof,value from mark".$branch.$gradesec.$quarter.$max_year." where subname='$subject' and quarter='$quarter' and markname='$evaluation' and stuid='$stuid' group by stuid order by total ASC");
							if($queryMarkOutof->num_rows()>0){
								foreach ($queryMarkOutof->result() as $keyvalue) {
									$result1=$keyvalue->value;
			    					$output.='<td class="text-center">'.number_format((float)$keyvalue->value,2,'.','').' ';
			    					$querRemark=$this->db->query("select achievername from achievername where $result1 between minvalue and maxivalue and achievergrade='$grade'");
			    					if($querRemark->num_rows()>0){
			    						foreach($querRemark->result() as $achName){
			    							$output.='<span class="text-center badge badge-info">'.$achName->achievername.'</span>';
			    						}
			    					}else{
			    						$output.='<span class="text-center text-warning">Not Set</span>';
			    					}
			    					$output.='</td>';
								}
							}else{
								$output.='<td class="text-center">-</td>';
							}
						}
						$output.='<td class="text-center">-</td>';
						$output.='</tr>';
						$no++;
					}
					$output.='</table></div>';
				}
				$querRemark=$this->db->query("SELECT *, GROUP_CONCAT(achievergrade) as gradess from achievername GROUP BY minvalue,maxivalue ORDER BY id ASC");
				if($querRemark->num_rows()>0){
					$output .='<br><div class="row"><div class="col-lg-3 col-2"></div>
					<div class="col-lg-6 col-8">
					<div class="table-responsive">
        			<table class="tabler table-bordered" cellspacing="5" cellpadding="5">
			        <tr>
			        <th class="text-center">Grade</th><th class="text-center">Achiever Name</th><th class="text-center">Range</th> </tr>';
			        
			        $output.='<tr>';
					
						foreach($querRemark->result() as $achName){
							$output.='<th class="text-center">'.$achName->gradess.'</th>';
							$output.='<th class="text-center">'.$achName->achievername.'</th>';
							$output.='<th class="text-center">'.$achName->minvalue.'-'.$achName->maxivalue.'</th>';
						}
					
					$output.='</tr>';
					$output.='</table></div></div><div class="col-lg-3 col-2"></div></div>';
				}
			}
		}
		return $output;
	}
	function fetchanalysis($branch,$gradesecs,$quarter,$evaluations,$max_year)
	{
		$output='';
		foreach($gradesecs as $gradesec){
			$querySubject=$this->db->query("select u.username,u.fname,u.lname, u.mname,s.Grade ,s.Subj_name, u.grade,u.id from users as u cross JOIN subject as s where s.Grade=u.grade and u.academicyear='$max_year' and u.status='Active' and u.isapproved='1' and u.usertype='Student' and u.branch='$branch' and s.Academic_Year='$max_year' and u.gradesec='$gradesec' group by s.Subj_name order by s.Subj_name ASC");
			if($querySubject->num_rows()>0){
				foreach($evaluations as $evaluation){
					$queryac = $this->db->query("select max(year_name) as ay from academicyear");
		    		$rowac = $queryac->row();
		    		$yearname=$rowac->ay;
		    		$queryev = $this->db->query("select evname from evaluation where eid='$evaluation' and academicyear='$max_year' ");
		    		$rowev = $queryev->row_array();
		    		$evname=$rowev['evname'];

		    		$query_name = $this->db->query("select * from school");
			        $row_name = $query_name->row();
			        $school_name=$row_name->name;

					$output.='<div class="text-center"><h4><u><b> '.$school_name.' '.$yearname.'E.C  Academic Year</b></u> <u><b> <br></h4><h6>'.$quarter.' '.$evname.' Result Analysis for Grade <u><b>'.$gradesec.'</b></u></h6></div>';
					$output .=' <div class="table-responsive" width="100%" height="100%">
        			<table id="ENS" class="tabler table-borderedr table-header-rotated" width="100%" cellspacing="5" cellpadding="5">
			        <tr>
			        <th class="rotate"><div><span>No.</span></div></th>
			        <th class="rotate"><div><span>Student Name</span></div></th>';
		          	$no=1;
					foreach ($querySubject->result() as $subvalue) {
						$output.='<th class="rotate"><div><span>'.$subvalue->Subj_name.'</span></div></th>';
					}
					$output.='<th class="rotate"><div><span>Total</span></div></th>
					<th class="rotate"><div><span>Status</span></div></th></tr>';
					$queryStudent=$this->db->query("select * from users where academicyear='$max_year' and status='Active' and isapproved='1' and usertype='Student' and branch='$branch' and gradesec='$gradesec' group by id  order by fname ASC");
					foreach ($queryStudent->result() as $studentvalue) {
						$grade=$studentvalue->grade;
						$stuid=$studentvalue->id;
						/*$querySubject=$this->db->query("select * from subject where Grade='$grade' and Academic_Year='$max_year' group by Subj_name order by Subj_name ASC");*/
						$output.='<tr><td>'.$no.'</td>';
						$allSubTotal=0;
						$output.='<td>'.$studentvalue->fname.' '.$studentvalue->mname. ' '.$studentvalue->lname. '</td>';
						if($querySubject->num_rows()>0){
							foreach ($querySubject->result() as $subvalue) {
								$subject=$subvalue->Subj_name;
								$queryMarkOutof = $this->db->query("select sum(value) as total,sum(outof) as outof from mark".$branch.$gradesec.$quarter.$max_year." where subname='$subject' and quarter='$quarter' and evaid='$evaluation' group by stuid order by mid ASC");
								if($queryMarkOutof->num_rows()>0){
									foreach ($queryMarkOutof->result() as $keyvalue) {
				    					$totaloutof=$keyvalue->outof;
									}
									$queryEvaluation=$this->db->query("select * from evaluation where quarter='$quarter' and eid='$evaluation' and academicyear='$max_year' and grade='$grade' ");
									if($queryEvaluation->num_rows()>0){
										foreach ($queryEvaluation->result() as $evavalue) {
											$percent=$evavalue->percent;
											$queryMark = $this->db->query("select sum(value) as total,sum(outof) as outof from mark".$branch.$gradesec.$quarter.$max_year." where stuid='$stuid' and subname='$subject' and quarter='$quarter' and evaid='$evaluation' group by stuid order by mid ASC");
											if($queryMark->num_rows()>0){
												foreach ($queryMark->result() as $mvalue) {
													$totalmark=$mvalue->total;
													if($totalmark>0){
														$finalresult=($percent*$totalmark)/$totaloutof;
														$output.='<td class="text-center">'.number_format((float)$finalresult,2,'.','').'</td>';
													}else{
													    $finalresult=0;
														$output.='<td class="text-center">-</td>';
													}
												}
											}else{
												$output.='<td class="text-center">-</td>';
											}
										}
										$allSubTotal= $allSubTotal+ $finalresult;
									}else{
										$output.='<td class="text-center">-</td>';
									}
								}else{
									$output.='<td class="text-center">-</td>';
								}
							}
						}else{
							$output.='<td class="text-center"> -</td>';
						}
						$output.='<td class="text-center">'.number_format((float)$allSubTotal,2,'.','').'</td>';
						/*Rank calculation starts*/
						/*$queryRank=$this->db->query("select sum(value),FIND_IN_SET(sum(value), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(value) as rank from mark".$branch.$gradesec.$quarter.$max_year." where mgrade='$gradesec' and mbranch='$branch' and quarter='$quarter' and evaid='$evaluation' group by stuid) sm)) as stuRank from mark".$branch.$gradesec.$quarter.$max_year." where stuid='$stuid' and quarter= '$quarter' and mbranch='$branch' and academicyear='$max_year' and mgrade='$gradesec' and evaid='$evaluation' group by mgrade ");
						if($queryRank->num_rows()>0){
							foreach ($queryRank->result() as $rankvalue) {
								$output.='<td>'.$rankvalue->stuRank.'</td>';
							}
						}else{
							$output.='<td>-</td>';
						}*/
						$output.='<td class="text-center">-</td>';
						$output.='</tr>';
						$no++;
					}
					$output.='</table></div>';
					$output.='<div class="text-center">Home Room Teachers Name:_______________________</div>';
				}
			}
		}
		return $output;
	}
	function fetchSubjectMarkAnalysisGraph($branch,$gradesec,$quarter,$SubName,$max_year){
		$queryMark=$this->db->query("select sum(m.value) as total, m.value,us.fname,us.mname,us.lname,us.username from users as us cross join mark".$branch.$gradesec.$quarter.$max_year." as m where us.gradesec='$gradesec' and m.quarter='$quarter' and us.branch='$branch' and us.academicyear='$max_year' and m.academicyear='$max_year' and m.subname='$SubName' and us.id=m.stuid and us.usertype='Student' group by m.stuid order by total DESC limit 10 ");
		return $queryMark->result_array();
	}
	function fetchSubjectMarkAnalysisGraphSup($max_year){
		$queryMark=$this->db->query("select * from users where academicyear='$max_year' and usertype='Student' and isapproved='1' and status='Active' group by grade ");
		return $queryMark->result_array();
	}
	function fetchSubjectMarkAnalysisGraphAdmin($mybranch,$max_year){
		$queryMark=$this->db->query("select * from users where academicyear='$max_year' and usertype='Student' and isapproved='1' and status='Active' and branch='$mybranch' group by grade ");
		return $queryMark->result_array();
	}
	function fetchStaffMarkAnalysisGraphSup($max_year){
		$queryTotal=$this->db->query("SELECT *, CONCAT('usertype') as Yearlevel,COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where academicyear='$max_year' and usertype!='Student' and isapproved='1' and status='Active' GROUP BY usertype ORDER BY fname,mname,lname ASC");
		return $queryTotal->result_array();
	}
	function fetchStaffMarkAnalysisGraphSupAdmin($mybranch,$max_year){
		$queryTotal=$this->db->query("SELECT *, CONCAT('usertype') as Yearlevel,COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' or Gender='male' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' or Gender='female' then 1 else 0 end) AS femalecount FROM users where academicyear='$max_year' and branch='$mybranch' and usertype!='Student' and isapproved='1' and status='Active' GROUP BY usertype ORDER BY fname,mname,lname ASC");
		return $queryTotal->result_array();
	}
	/*function fetchTeachersPerformance($quarter,$max_year){
		$queryUser=$this->db->query("select username,branch from users where academicyear='$max_year' and status='Active' and isapproved='1' and usertype='Teacher' group by id order by fname,mname,lname ASC ");
		if($queryUser->num_rows()>0){
			foreach($queryUser->result() as $userGrade){
				$userName=$userGrade->username;
				$branch=$userGrade->branch;
				$arraySp = array('academicyear'=>$max_year,'staff'=>$userName);
		    	$this->db->where($arraySp);
		    	$this->db->group_by('subject');  
		    	$queryPlacement = $this->db->get('staffplacement');
		    	if($queryPlacement->num_rows()>0){
			    	foreach($queryPlacement->result() as $stPlacement){
			    		$gradePlace=$stPlacement->grade;
			    		$subject=$stPlacement->subject;
			    		$queryMark=$this->db->query("select sum(m.value) as total, m.value,us.fname, us.mname,us.lname,us.username from users as us cross join mark".$branch.$gradePlace.$quarter.$max_year." as m where us.gradesec='$gradePlace' and m.quarter='$quarter' and us.branch='$branch' and us.academicyear='$max_year' and m.academicyear='$max_year' and us.id=m.stuid and us.usertype='Student' group by m.stuid order by total DESC limit 10 ");
			    	}
			    }
			}
		}
		return $queryUser->result_array();
	}*/
	function fetchSubjectMarkAnalysis($branch,$gradesec,$quarter,$SubName,$max_year)
	{
		$output='';
		/*$querySubject=$this->db->query("select * from subject where Subj_Id='$subject' and Academic_Year='$max_year' ");
		$rowSubj=$querySubject->row();
		$SubName=$rowSubj->Subj_name;*/
		$queryMark=$this->db->query("select sum(m.value) as total, m.value,us.fname,us.mname,us.lname,us.username from users as us cross join mark".$branch.$gradesec.$quarter.$max_year." as m where us.gradesec='$gradesec' and m.quarter='$quarter' and us.branch='$branch' and us.academicyear='$max_year' and m.academicyear='$max_year' and m.subname='$SubName' and us.id=m.stuid and us.usertype='Student' group by m.stuid order by total DESC ");
		if($queryMark->num_rows()>0){
			$output.='<div class="text-center"><h4><u><b>'.$max_year.'E.C</b></u> Academic Year <u><b>'.$quarter.' '.$SubName.'</b></u> Result Analysis for Grade <u><b>'.$gradesec.'</b></u></h4></div>';
			$output .='<div class="table-responsive">
        	<table class="table table-borderedr table-hover" style="width:100%;">
	        <thead>
	        <tr>
	        <th>No.</th>
          	<th>Student Name.</th>
          	<th>Student ID.</th>
          	<th>Total</th></tr> </thead>';
          	$no=1;
			foreach ($queryMark->result() as $markValue) {
				$mValue=$markValue->total;
				$output.='<tr> <td>'.$no.'.</td>
				<td>'.$markValue->fname.' '.$markValue->mname.' '.$markValue->lname.'</td>';
				$output.='<td>'.$markValue->username.'</td>';
				$output.='<td>'.$mValue.'</td></tr>';
				$no++;
			}
			$output.='</table></div>';
			$output.='<div class="text-center">Home Room Teachers Name:_______________________</div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No mark result found.
            </div></div>';
		}
		return $output;
	}
	function filter_quarter($max_year){
		$this->db->where('Academic_year',$max_year);
		$this->db->select_max('term');
		$this->db->order_by('term','ASC');
		$query=$this->db->get('quarter');
		$output='<select class="form-control" required="required" name="branch" id="quarter4eva"><option></option>';
		foreach ($query->result() as $evavalue) {
			$output.='<option value='.$evavalue->term.'>'.$evavalue->term.'</option>';	
		}
		$output.='</select>';
		return $output;
	}
	function fetch_academicyear_branch($academicyear){
		$this->db->where('academicyear',$academicyear);
		$this->db->order_by('name','ASC');
		$query=$this->db->get('branch');
		$output='<option></option>';
		foreach ($query->result() as $evavalue) {
			$output.='<option value='.$evavalue->name.'>'.$evavalue->name.'</option>';	
		}
		return $output;
	}
	/*function check_detainedstudent($grade,$max_year){
		$query=$this->db->query("select u.id, pp.average as avpp, u.gradesec from users as u cross join promotion_policy as pp  where pp.grade=u.grade and u.grade='$grade' and u.academicyear='$max_year'  and pp.academicyear='$max_year' group by u.id ");
		$total_detained=0;
		foreach ($query->result() as $kalue) {
			$gradesec=$kalue->gradesec;
			$stuid=$kalue->id;
			$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and onreportcard='1' and letter='#' and academicyear='$max_year' group by subject ");
				$total_subject=$count_subject->num_rows();
			$countAverage=$this->db->query("select sum(total) as yave from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter!='A' and onreportcard='1' and stuid='$stuid' and academicyear='$max_year' group by stuid ");
			$promotion_average=$kalue->avpp;
			foreach ($countAverage->result() as $averageValue) {
				$kalueV=$averageValue->yave;
				$yearlyaverage=$kalueV/$total_subject;
				if($yearlyaverage < $promotion_average){
					$total_detained= $total_detained + 1;
				}
			}	
		}
		return '<div class="alert alert-info alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i><B> '.$total_detained.'</B> Student(s) are Detained in grade <B>'.$grade.'</B>.
                <a href="#"> View</a>
            </div></div>';
	}*/
	function student_registration($branch,$grade,$max_year){
		$this->db->where('usertype','Student');
		$this->db->where('status','Active');
		$this->db->where('isapproved','1');
		$this->db->where('grade',$grade);
		$this->db->where('branch',$branch);
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->order_by('fname,mname,lname','ASC');
		$query = $this->db->get('users');
		$output ='';
		if($query->num_rows()>0){
	        $output .=' <div class="table-responsive">
	        <table class="table table-striped table-hover" style="width:100%;">
	        <thead>
	        <tr>
	          	<th>No.</th>
	          	<th>Student Name</th>
	          	<th>Student ID</th>
	          	<th>Grade</th>
	          	<th>Branch</th>';
	          	if($grade == '12n' || $grade == '12s'){
	          		$output .='<th class="text-center">
		          	<a href="#" class="text-warning" id="detainedStudent"><i class="fas fa-times-circle"></i> Detained</a>
		          	<div class="dropdown-divider"></div>
		          	<input type="checkbox" class="" id="selectall" onClick="selectAll()"> </th>';
	          	}
	          	else if($grade == '10'){
	          		$output .='<th class="text-center"> 
		          	<a href="#" id="promoteStudentN"><i class="fas fa-check-circle"></i> Promote 11n</a>
		          	<a href="#" id="promoteStudentS"><i class="fas fa-check-circle"></i> Promote 11s</a>
		          	<a href="#" class="text-warning" id="detainedStudent"><i class="fas fa-times-circle"></i> Detained</a>
		          	<div class="dropdown-divider"></div>
		          	<input type="checkbox" class="" id="selectall" onClick="selectAll()"> </th>';
	          	}else{
		          	$output .='<th class="text-center"> 
		          	<button class="btn btn-info btn-sm" id="promoteStudent"><i class="fas fa-check-circle"></i> Promote</button>
		          	<button class="btn btn-warning btn-sm" id="detainedStudent"><i class="fas fa-times-circle"></i> Detained</button>
		          	<br>
		          	Select All <input type="checkbox" class="" id="selectall" onClick="selectAll()"> </th>';
	          	}
	        	$output .='</tr>
	        	</thead>
	        <tbody>';
	        $no=1;
	        $currentYear=$max_year;
	        $nextYear=$max_year + 1;
	        $queryMaxYear=$this->db->query("select year_name from academicyear where year_name='$nextYear' ");
		    foreach ($query->result() as $row) 
		    {
		        $output .='<tr class="clearRegs'.$row->id.'"> 
			    <td>'.$no.'.</td>
			    <td>'.$row->fname.' '.$row->mname.' '.$row->lname.'</td>
			    <td>'.$row->username.'</td>
			    <td>'.$row->grade.'</td>
			    <td>'.$row->branch.'</td>';
			    $queryStudentChk=$this->db->query("select id,username,grade from users where unique_id='".$row->unique_id."' and academicyear = '".$nextYear."' ");
			    if($queryStudentChk->num_rows()>0 ){
			    	$stuid=$queryStudentChk->row();
			    	$deletedId=$stuid->id;
			    	$nextGrade=$stuid->grade;
			    	if($nextGrade == $grade){
			    		$output.='<td class="text-center">
				        <div class="pretty p-icon p-jelly p-round p-bigger">
		                    <input type="checkbox" name="unregister" checked="checked" class="'.$nextYear.'" value="'.$deletedId.'" id="customCheck1">
		                    <div class="state p-info">
		                      <i class="icon material-icons"></i>
		                      <label></label>
		                    </div>
		                </div><span class="time text-danger">Detained</span>
				        <small id="checkstatus'.$deletedId.'" class="time text-success"> </small>
			        </td>';
			    	}else{
			    		$output.='<td class="text-center">
				        <div class="pretty p-icon p-jelly p-round p-bigger">
		                    <input type="checkbox" name="unregister" checked="checked" class="'.$nextYear.'" value="'.$deletedId.'" id="customCheck1">
		                    <div class="state p-info"> 
		                      <i class="icon material-icons"></i>
		                      <label></label>
		                    </div>
		                </div><span class="time text-success">Promoted</span>
				        <small id="checkstatus'.$deletedId.'" class="time text-success"> </small>
			        </td>';
			    	}
			    }else if($queryMaxYear->num_rows()> 0){
			        $output.='<td class="text-center"> 
			        <div class="pretty p-icon p-jelly p-round p-bigger">
	                    <input type="checkbox" name="stuId[ ]" class="stuidList'.$row->id.'" value="'.$row->id.'" id="customCheck1 stuIdList">
	                    <div class="state p-info">
	                      <i class="icon material-icons"></i>
	                      <label></label>
	                    </div>
	                </div><span class="time text-info">No Regist.</span>
			        <small id="promotestatus'.$row->id.'" class="time text-success"></small>
			        </td>';
			    }else{
			        $output.='<td> No new year </td>';
			    }
			    $output.='</tr>';
			    $no++;
		    }
	        $output .='</tbody> </table> </div>';
    	}else{
    		$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i> No Student found.
            </div></div>';
    	}
        return $output;
	}
	function studentPromotionPromoteENS($stuid,$max_year){
		$this->db->where('id',$stuid);
		$this->db->where('academicyear',$max_year);
		$query=$this->db->get('users');
		$output='';
    	foreach ($query->result() as $studentList) {
    	    $username=$studentList->username;
    	    $nextYear=$max_year + 1;
    	    $quryCheck=$this->db->query("select username from users where username='$username' and academicyear='$nextYear' ");
		    if($quryCheck->num_rows()<1){
    			$grade=$studentList->grade;
    			if($grade == '11n'){
    				$NextGrade='12n';
    				$data=array(
    					'username'=>$studentList->username,
    					'usertype'=>$studentList->usertype,
    					'fname'=>$studentList->fname,
    					'mname'=>$studentList->mname,
    					'lname'=>$studentList->lname,
    					'mobile'=>$studentList->mobile,
    					'father_mobile'=>$studentList->father_mobile,
    					'email'=>$studentList->email,
    					'profile'=>$studentList->profile,
    					'grade'=>$NextGrade,
    					'section'=>'',
    					'gradesec'=>'',
    					'dob'=>$studentList->dob,
    					'age'=>$studentList->age,
    					'gender'=>$studentList->gender,
    					'password'=>$studentList->password,
    					'password2'=>$studentList->password2,
    					'mother_name'=>$studentList->mother_name,
    					'city'=>$studentList->city,
    					'sub_city'=>$studentList->sub_city,
    					'woreda'=>$studentList->woreda,
    					'kebele'=>$studentList->kebele,
    					'isapproved'=>$studentList->isapproved,
    					'dateregister'=>$studentList->dateregister,
    					'branch'=>$studentList->branch,
    					'academicyear'=>$max_year + 1,
    					'biography'=>$studentList->biography,
    					'status'=>$studentList->status,
    					'unique_id'=>$studentList->unique_id
    				);
    			}
    			else if($grade =='11s'){
    				$NextGrade='12s';
    				$data=array(
    					'username'=>$studentList->username,
    					'usertype'=>$studentList->usertype,
    					'fname'=>$studentList->fname,
    					'mname'=>$studentList->mname,
    					'lname'=>$studentList->lname,
    					'mobile'=>$studentList->mobile,
    					'father_mobile'=>$studentList->father_mobile,
    					'email'=>$studentList->email,
    					'profile'=>$studentList->profile,
    					'grade'=>$NextGrade,
    					'section'=>'',
    					'gradesec'=>'',
    					'dob'=>$studentList->dob,
    					'age'=>$studentList->age,
    					'gender'=>$studentList->gender,
    					'password'=>$studentList->password,
    					'password2'=>$studentList->password2,
    					'mother_name'=>$studentList->mother_name,
    					'city'=>$studentList->city,
    					'sub_city'=>$studentList->sub_city,
    					'woreda'=>$studentList->woreda,
    					'kebele'=>$studentList->kebele,
    					'isapproved'=>$studentList->isapproved,
    					'dateregister'=>$studentList->dateregister,
    					'branch'=>$studentList->branch,
    					'academicyear'=>$max_year + 1,
    					'biography'=>$studentList->biography,
    					'status'=>$studentList->status,
    					'unique_id'=>$studentList->unique_id
    				);
    			}
    			else if($grade =='KG1'){
    				$NextGrade='KG2';
    				$data=array(
    					'username'=>$studentList->username,
    					'usertype'=>$studentList->usertype,
    					'fname'=>$studentList->fname,
    					'mname'=>$studentList->mname,
    					'lname'=>$studentList->lname,
    					'mobile'=>$studentList->mobile,
    					'father_mobile'=>$studentList->father_mobile,
    					'email'=>$studentList->email,
    					'profile'=>$studentList->profile,
    					'grade'=>$NextGrade,
    					'section'=>'',
    					'gradesec'=>'',
    					'dob'=>$studentList->dob,
    					'age'=>$studentList->age,
    					'gender'=>$studentList->gender,
    					'password'=>$studentList->password,
    					'password2'=>$studentList->password2,
    					'mother_name'=>$studentList->mother_name,
    					'city'=>$studentList->city,
    					'sub_city'=>$studentList->sub_city,
    					'woreda'=>$studentList->woreda,
    					'kebele'=>$studentList->kebele,
    					'isapproved'=>$studentList->isapproved,
    					'dateregister'=>$studentList->dateregister,
    					'branch'=>$studentList->branch,
    					'academicyear'=>$max_year + 1,
    					'biography'=>$studentList->biography,
    					'status'=>$studentList->status,
    					'unique_id'=>$studentList->unique_id
    				);
    			}
    			else if($grade=='KG2'){
    				$NextGrade='KG3';
    				$data=array(
    					'username'=>$studentList->username,
    					'usertype'=>$studentList->usertype,
    					'fname'=>$studentList->fname,
    					'mname'=>$studentList->mname,
    					'lname'=>$studentList->lname,
    					'mobile'=>$studentList->mobile,
    					'father_mobile'=>$studentList->father_mobile,
    					'email'=>$studentList->email,
    					'profile'=>$studentList->profile,
    					'grade'=>$NextGrade,
    					'section'=>'',
    					'gradesec'=>'',
    					'dob'=>$studentList->dob,
    					'age'=>$studentList->age,
    					'gender'=>$studentList->gender,
    					'password'=>$studentList->password,
    					'password2'=>$studentList->password2,
    					'mother_name'=>$studentList->mother_name,
    					'city'=>$studentList->city,
    					'sub_city'=>$studentList->sub_city,
    					'woreda'=>$studentList->woreda,
    					'kebele'=>$studentList->kebele,
    					'isapproved'=>$studentList->isapproved,
    					'dateregister'=>$studentList->dateregister,
    					'branch'=>$studentList->branch,
    					'academicyear'=>$max_year + 1,
    					'biography'=>$studentList->biography,
    					'status'=>$studentList->status,
    					'unique_id'=>$studentList->unique_id
    				);
    			}
    			else if($grade =='KG3'){
    				$NextGrade='1';
    				$data=array(
    					'username'=>$studentList->username,
    					'usertype'=>$studentList->usertype,
    					'fname'=>$studentList->fname,
    					'mname'=>$studentList->mname,
    					'lname'=>$studentList->lname,
    					'mobile'=>$studentList->mobile,
    					'father_mobile'=>$studentList->father_mobile,
    					'email'=>$studentList->email,
    					'profile'=>$studentList->profile,
    					'grade'=>$NextGrade,
    					'section'=>'',
    					'gradesec'=>'',
    					'dob'=>$studentList->dob,
    					'age'=>$studentList->age,
    					'gender'=>$studentList->gender,
    					'password'=>$studentList->password,
    					'password2'=>$studentList->password2,
    					'mother_name'=>$studentList->mother_name,
    					'city'=>$studentList->city,
    					'sub_city'=>$studentList->sub_city,
    					'woreda'=>$studentList->woreda,
    					'kebele'=>$studentList->kebele,
    					'isapproved'=>$studentList->isapproved,
    					'dateregister'=>$studentList->dateregister,
    					'branch'=>$studentList->branch,
    					'academicyear'=>$max_year + 1,
    					'biography'=>$studentList->biography,
    					'status'=>$studentList->status,
    					'unique_id'=>$studentList->unique_id
    				);
    			}else if($grade=='Nursery'){
    				$NextGrade='LKG';
    				$data=array(
    					'username'=>$studentList->username,
    					'usertype'=>$studentList->usertype,
    					'fname'=>$studentList->fname,
    					'mname'=>$studentList->mname,
    					'lname'=>$studentList->lname,
    					'mobile'=>$studentList->mobile,
    					'father_mobile'=>$studentList->father_mobile,
    					'email'=>$studentList->email,
    					'profile'=>$studentList->profile,
    					'grade'=>$NextGrade,
    					'section'=>'',
    					'gradesec'=>'',
    					'dob'=>$studentList->dob,
    					'age'=>$studentList->age,
    					'gender'=>$studentList->gender,
    					'password'=>$studentList->password,
    					'password2'=>$studentList->password2,
    					'mother_name'=>$studentList->mother_name,
    					'city'=>$studentList->city,
    					'sub_city'=>$studentList->sub_city,
    					'woreda'=>$studentList->woreda,
    					'kebele'=>$studentList->kebele,
    					'isapproved'=>$studentList->isapproved,
    					'dateregister'=>$studentList->dateregister,
    					'branch'=>$studentList->branch,
    					'academicyear'=>$max_year + 1,
    					'biography'=>$studentList->biography,
    					'status'=>$studentList->status,
    					'unique_id'=>$studentList->unique_id
    				);
    			}
    			else if($grade =='LKG'){
    				$NextGrade='UKG';
    				$data=array(
    					'username'=>$studentList->username,
    					'usertype'=>$studentList->usertype,
    					'fname'=>$studentList->fname,
    					'mname'=>$studentList->mname,
    					'lname'=>$studentList->lname,
    					'mobile'=>$studentList->mobile,
    					'father_mobile'=>$studentList->father_mobile,
    					'email'=>$studentList->email,
    					'profile'=>$studentList->profile,
    					'grade'=>$NextGrade,
    					'section'=>'',
    					'gradesec'=>'',
    					'dob'=>$studentList->dob,
    					'age'=>$studentList->age,
    					'gender'=>$studentList->gender,
    					'password'=>$studentList->password,
    					'password2'=>$studentList->password2,
    					'mother_name'=>$studentList->mother_name,
    					'city'=>$studentList->city,
    					'sub_city'=>$studentList->sub_city,
    					'woreda'=>$studentList->woreda,
    					'kebele'=>$studentList->kebele,
    					'isapproved'=>$studentList->isapproved,
    					'dateregister'=>$studentList->dateregister,
    					'branch'=>$studentList->branch,
    					'academicyear'=>$max_year + 1,
    					'biography'=>$studentList->biography,
    					'status'=>$studentList->status,
    					'unique_id'=>$studentList->unique_id
    				);
    			}
    			else if($grade =='UKG'){
    				$NextGrade='1';
    				$data=array(
    					'username'=>$studentList->username,
    					'usertype'=>$studentList->usertype,
    					'fname'=>$studentList->fname,
    					'mname'=>$studentList->mname,
    					'lname'=>$studentList->lname,
    					'mobile'=>$studentList->mobile,
    					'father_mobile'=>$studentList->father_mobile,
    					'email'=>$studentList->email,
    					'profile'=>$studentList->profile,
    					'grade'=>$NextGrade,
    					'section'=>'',
    					'gradesec'=>'',
    					'dob'=>$studentList->dob,
    					'age'=>$studentList->age,
    					'gender'=>$studentList->gender,
    					'password'=>$studentList->password,
    					'password2'=>$studentList->password2,
    					'mother_name'=>$studentList->mother_name,
    					'city'=>$studentList->city,
    					'sub_city'=>$studentList->sub_city,
    					'woreda'=>$studentList->woreda,
    					'kebele'=>$studentList->kebele,
    					'isapproved'=>$studentList->isapproved,
    					'dateregister'=>$studentList->dateregister,
    					'branch'=>$studentList->branch,
    					'academicyear'=>$max_year + 1,
    					'biography'=>$studentList->biography,
    					'status'=>$studentList->status,
    					'unique_id'=>$studentList->unique_id
    				);
    			} else{
    				$data=array(
    					'username'=>$studentList->username,
    					'usertype'=>$studentList->usertype,
    					'fname'=>$studentList->fname,
    					'mname'=>$studentList->mname,
    					'lname'=>$studentList->lname,
    					'mobile'=>$studentList->mobile,
    					'father_mobile'=>$studentList->father_mobile,
    					'email'=>$studentList->email,
    					'profile'=>$studentList->profile,
    					'grade'=>$studentList->grade + 1,
    					'section'=>'',
    					'gradesec'=>'',
    					'dob'=>$studentList->dob,
    					'age'=>$studentList->age,
    					'gender'=>$studentList->gender,
    					'password'=>$studentList->password,
    					'password2'=>$studentList->password2,
    					'mother_name'=>$studentList->mother_name,
    					'city'=>$studentList->city,
    					'sub_city'=>$studentList->sub_city,
    					'woreda'=>$studentList->woreda,
    					'kebele'=>$studentList->kebele,
    					'isapproved'=>$studentList->isapproved,
    					'dateregister'=>$studentList->dateregister,
    					'branch'=>$studentList->branch,
    					'academicyear'=>$max_year + 1,
    					'biography'=>$studentList->biography,
    					'status'=>$studentList->status,
    					'unique_id'=>$studentList->unique_id
    				);
    			}
    			$queryInsert=$this->db->insert('users',$data);
    			if($queryInsert){
    				$output.='<i class="fas fa-exclamation-circle"> </i> ';
    			}else{
    				$output.='<div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    <i class="fas fa-exclamation-circle"> </i> Not registered, PLease try Again.
                </div></div>';
    			}
    		}
		}
		return $output;
	}
	function studentPromotionPromoteENSN($stuid,$max_year){
		$this->db->where('id',$stuid);
		$this->db->where('academicyear',$max_year);
		$query=$this->db->get('users');
		$output='';
		foreach ($query->result() as $studentList) {
		    $username=$studentList->username;
    	    $nextYear=$max_year + 1;
    	    $quryCheck=$this->db->query("select username from users where username='$username' and academicyear='$nextYear' ");
		    if($quryCheck->num_rows()<1){
    			$grade=$studentList->grade;
    			if($grade =='10'){
    				$NextGrade='11n';
    				$data=array(
    					'username'=>$studentList->username,
    					'usertype'=>$studentList->usertype,
    					'fname'=>$studentList->fname,
    					'mname'=>$studentList->mname,
    					'lname'=>$studentList->lname,
    					'mobile'=>$studentList->mobile,
    					'father_mobile'=>$studentList->father_mobile,
    					'email'=>$studentList->email,
    					'profile'=>$studentList->profile,
    					'grade'=>$NextGrade,
    					'section'=>'',
    					'gradesec'=>'',
    					'dob'=>$studentList->dob,
    					'age'=>$studentList->age,
    					'gender'=>$studentList->gender,
    					'password'=>$studentList->password,
    					'password2'=>$studentList->password2,
    					'mother_name'=>$studentList->mother_name,
    					'city'=>$studentList->city,
    					'sub_city'=>$studentList->sub_city,
    					'woreda'=>$studentList->woreda,
    					'kebele'=>$studentList->kebele,
    					'isapproved'=>$studentList->isapproved,
    					'dateregister'=>$studentList->dateregister,
    					'branch'=>$studentList->branch,
    					'academicyear'=>$max_year + 1,
    					'biography'=>$studentList->biography,
    					'status'=>$studentList->status,
    					'unique_id'=>$studentList->unique_id
    				);
    			}
    			$queryInsert=$this->db->insert('users',$data);
    			if($queryInsert){
    				$output.='<i class="fas fa-exclamation-circle"> </i> ';
    			}else{
    				$output.='<div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    <i class="fas fa-exclamation-circle"> </i> Not registered, PLease try Again.
                </div></div>';
    			}
		    }
		}
		return $output;
	}
	function studentPromotionPromoteENSS($stuid,$max_year){
		$this->db->where('id',$stuid);
		$this->db->where('academicyear',$max_year);
		$query=$this->db->get('users');
		$output='';
		foreach ($query->result() as $studentList) {
		    $username=$studentList->username;
    	    $nextYear=$max_year + 1;
    	    $quryCheck=$this->db->query("select username from users where username='$username' and academicyear='$nextYear' ");
		    if($quryCheck->num_rows()<1){
    			$grade=$studentList->grade;
    			if($grade=='10'){
    				$NextGrade='11s';
    				$data=array(
    					'username'=>$studentList->username,
    					'usertype'=>$studentList->usertype,
    					'fname'=>$studentList->fname,
    					'mname'=>$studentList->mname,
    					'lname'=>$studentList->lname,
    					'mobile'=>$studentList->mobile,
    					'father_mobile'=>$studentList->father_mobile,
    					'email'=>$studentList->email,
    					'profile'=>$studentList->profile,
    					'grade'=>$NextGrade,
    					'section'=>'',
    					'gradesec'=>'',
    					'dob'=>$studentList->dob,
    					'age'=>$studentList->age,
    					'gender'=>$studentList->gender,
    					'password'=>$studentList->password,
    					'password2'=>$studentList->password2,
    					'mother_name'=>$studentList->mother_name,
    					'city'=>$studentList->city,
    					'sub_city'=>$studentList->sub_city,
    					'woreda'=>$studentList->woreda,
    					'kebele'=>$studentList->kebele,
    					'isapproved'=>$studentList->isapproved,
    					'dateregister'=>$studentList->dateregister,
    					'branch'=>$studentList->branch,
    					'academicyear'=>$max_year + 1,
    					'biography'=>$studentList->biography,
    					'status'=>$studentList->status,
    					'unique_id'=>$studentList->unique_id
    				);
    			}
    			$queryInsert=$this->db->insert('users',$data);
    			if($queryInsert){
    				$output.='<i class="fas fa-exclamation-circle"> </i> ';
    			}else{
    				$output.='<div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close"  data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    <i class="fas fa-exclamation-circle"> </i> Not registered, PLease try Again.
                </div></div>';
    			}
		    }
		}
		return $output;
	}
	function studentPromotionDetained($stuid,$max_year){
		$this->db->where('id',$stuid);
		$this->db->where('academicyear',$max_year);
		$query=$this->db->get('users');
		$output='';
		foreach ($query->result() as $studentList) {
			$data=array(
				'username'=>$studentList->username,
				'usertype'=>$studentList->usertype,
				'fname'=>$studentList->fname,
				'mname'=>$studentList->mname,
				'lname'=>$studentList->lname,
				'mobile'=>$studentList->mobile,
				'father_mobile'=>$studentList->father_mobile,
				'email'=>$studentList->email,
				'profile'=>$studentList->profile,
				'grade'=>$studentList->grade,
				'section'=>'',
				'gradesec'=>'',
				'dob'=>$studentList->dob,
				'age'=>$studentList->age,
				'gender'=>$studentList->gender,
				'password'=>$studentList->password,
				'password2'=>$studentList->password2,
				'mother_name'=>$studentList->mother_name,
				'city'=>$studentList->city,
				'sub_city'=>$studentList->sub_city,
				'woreda'=>$studentList->woreda,
				'kebele'=>$studentList->kebele,
				'isapproved'=>$studentList->isapproved,
				'dateregister'=>$studentList->dateregister,
				'branch'=>$studentList->branch,
				'academicyear'=>$max_year + 1,
				'biography'=>$studentList->biography,
				'status'=>$studentList->status,
				'unique_id'=>$studentList->unique_id
			);
			$queryInsert=$this->db->insert('users',$data);
			if($queryInsert){
				$output.='<i class="fas fa-exclamation-circle"> </i>';
			}else{
				$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i> Not registered, Please try Again.
            </div></div>';
			}
		}
		return $output;
	}
	function clearRegistration($stuid,$academicyear){
		$this->db->where('id',$stuid);
		$this->db->where('academicyear',$academicyear);
		$query=$this->db->delete('users');
	}
	function startAutoPromotion($fromAcademicYear,$toAcademicYear){
		$output ='';
		$this->db->where('users.academicyear',$fromAcademicYear);
		$this->db->where('users.usertype','Student');
		$this->db->group_by('users.grade');
		$this->db->order_by('users.grade','ASC');
		$query=$this->db->get('users');
		if($query->num_rows()>0){
			$output.='<div class="row">';
			foreach($query->result() as $fetchGrade){
				$stuGradeCheck=$fetchGrade->grade;
				$queryPromotion=$this->db->query("select * from promotion_policy where grade='$stuGradeCheck' and academicyear='$fromAcademicYear' ");
				if($queryPromotion->num_rows()>0){
					foreach($queryPromotion->result() as $promotionAverage ){
						$average=$promotionAverage->average;
						$queryStudent=$this->db->query("select * from users where academicyear='$fromAcademicYear' and status='Active' and isapproved='1' and usertype='Student' and grade='$stuGradeCheck' group by id order by fname ASC");
						if($queryStudent->num_rows()>0){
							foreach($queryStudent->result() as $promotionStudent ){
								$stuId=$promotionStudent->id;
								$gradesec=$promotionStudent->gradesec;
								$uniqueId=$promotionStudent->unique_id;
								$stuGrade=$promotionStudent->grade;
								/*count subject*/
								$checkStudent=$this->db->query("select * from users where academicyear='$toAcademicYear' and unique_id='$uniqueId' group by unique_id order by fname ASC");
								if($checkStudent->num_rows() <1){
									$countSubject=$this->db->query("select * from reportcard".$gradesec.$fromAcademicYear." where grade='$gradesec' and academicyear='$fromAcademicYear' and onreportcard='1' and letter='#' group by subject order by subjorder ASC"); 
									$subALl=$countSubject->num_rows();
									/*count quarter*/
									$countQuarter=$this->db->query("select * from quarter where Academic_year='$fromAcademicYear' "); 
									$allQuarter=$countQuarter->num_rows();

									$quartrYATotal=$this->db->query("select sum(total) as total from reportcard".$gradesec.$fromAcademicYear." where stuid='$stuId' and onreportcard='1' and letter='#' ");
						            foreach ($quartrYATotal->result() as $totalValueYA) {
						                if($totalValueYA->total > 0 ){
						                	$printValueYA=(($totalValueYA->total)/$allQuarter)/$subALl;
						                	
						                	if($printValueYA >= $average){
						                		if($stuGrade!='12n' && $stuGrade!='12s' && $stuGrade!='10'){
					                				if($stuGrade=='KG1'){
					                					$nextGradeIs='KG2';
					                				}else if($stuGrade=='KG2'){
					                					$nextGradeIs='KG3';
					                				}else if($stuGrade=='KG3'){
					                					$nextGradeIs='1';
					                				}else if($stuGrade=='Nursery'){
					                					$nextGradeIs='LKG';
					                				}else if($stuGrade=='LKG'){
					                					$nextGradeIs='UKG';
					                				}else if($stuGrade=='UKG'){
					                					$nextGradeIs='1';
					                				}else if($stuGrade=='11n'){
					                					$nextGradeIs='12n';
					                				}else if($stuGrade=='11s'){
					                					$nextGradeIs='12s';
					                				}else{
						                				$nextGradeIs=$stuGrade + 1;
						                			}
						                			$data[]=array(
														'username'=>$promotionStudent->username,
														'usertype'=>$promotionStudent->usertype,
														'fname'=>$promotionStudent->fname,
														'mname'=>$promotionStudent->mname,
														'lname'=>$promotionStudent->lname,
														'mobile'=>$promotionStudent->mobile,
														'father_mobile'=>$studentList->father_mobile,
														'email'=>$promotionStudent->email,
														'profile'=>$promotionStudent->profile,
														'grade'=>$nextGradeIs,
														'section'=>'',
														'gradesec'=>'',
														'dob'=>$promotionStudent->dob,
														'age'=>$promotionStudent->age,
														'gender'=>$promotionStudent->gender,
														'password'=>$promotionStudent->password,
														'password2'=>$promotionStudent->password2,
														'mother_name'=>$promotionStudent->mother_name,
														'city'=>$promotionStudent->city,
														'sub_city'=>$promotionStudent->sub_city,
														'woreda'=>$promotionStudent->woreda,
														'kebele'=>$promotionStudent->kebele,
														'isapproved'=>$promotionStudent->isapproved,
														'dateregister'=>$promotionStudent->dateregister,
														'branch'=>$promotionStudent->branch,
														'academicyear'=>$toAcademicYear,
														'biography'=>$promotionStudent->biography,
														'status'=>$promotionStudent->status,
														'unique_id'=>$promotionStudent->unique_id
													);
						                		}
						                	}
						                }
						            }
						        }
							}
							if(!empty($data)){
					            $queryPromote=$this->db->insert_batch('users',$data);
					            if($queryPromote){
					            	$output.='<div class="alert alert-warning alert-dismissible show fade">
						                <div class="alert-body">
						                    <button class="close"  data-dismiss="alert">
						                        <span>&times;</span>
						                    </button>
						                <i class="fas fa-exclamation-circle"> </i> Registered '.$stuGrade.'.
						            </div></div>';
					            }
							}
						}else{
							$output.='<div class="alert alert-warning alert-dismissible show fade">
				                <div class="alert-body">
				                    <button class="close"  data-dismiss="alert">
				                        <span>&times;</span>
				                    </button>
				                <i class="fas fa-exclamation-circle"> </i> Ooops No student found.
				            </div></div>';
						}
					}
				}else{
					$output.='<div class="col-md-6"><div class="alert alert-warning alert-dismissible show fade">
		                <div class="alert-body">
		                    <button class="close"  data-dismiss="alert">
		                        <span>&times;</span>
		                    </button>
		                <i class="fas fa-exclamation-circle"> </i> Please enter promotion average for grade '.$stuGrade.'.
		            </div></div></div>';
				}
			}
			$output.='</div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i> Ooops something wrong.Please try again.
            </div></div>';
		}
		return $output;
	}
	function filtergrade_4branch($academicyear,$branchRegistration){
		$this->db->where('users.branch',$branchRegistration);
		$this->db->where('users.academicyear',$academicyear);
		$this->db->group_by('users.grade');
		$this->db->order_by('users.grade','ASC');
		$query=$this->db->get('users');
		$output ='';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->grade.'">'.$row->grade.'</option>';
		}
		return $output;
	}
	function fetch_username_password($branch,$gs_gradesec,$max_year)
	{
		$output='';
		$query=$this->db->query("select * from users where academicyear='$max_year' and status='Active' and isapproved='1' and usertype='Student' and gradesec='$gs_gradesec' and branch='$branch' group by id order by fname ASC");
		if($query->num_rows()>0){
			$output .='<div class="table-responsive">
	        <table class="table table-bordered">
	        <tr>
	          	<th>Student Name</th>
	            <th>Grade</th>            
	            <th>Branch</th>
	            <th>Username</th>
	            <th>Password</th>
			</tr>';
			foreach ($query->result() as $stuValue) {
				$output.='<tr><td>'.$stuValue->fname.' '.$stuValue->mname.' '.$stuValue->lname.'</td>';
				$output.='<td>'.$gs_gradesec.'</td>';
				$output.='<td>'.$branch.'</td>';
				$output.='<td>'.$stuValue->username.'</td>';
				$output.='<td>'.$stuValue->username.'12345</td>';
				$output.='</tr>';
			}
		}else{
			$output .='<div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No student found.
            </div></div>';
		}
		$output .='</table></div>';
		return $output;
	}
	function TopRankStudents($max_year,$quarter){
		$query=$this->db->query("select * from users where status='Active' and usertype='Student' and academicyear='$max_year' and isapproved='1' group by grade ");
        $output ='';
        foreach ($query->result() as $row) { 
        	$id=$row->id;
        	$grade=$row->grade;
        	$gradesec=$row->gradesec;
        	$queryMark=$this->db->query("select s.branch, s.profile, s.fname, s.mname, s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$max_year." as rc cross join users as s where s.grade='$grade' and s.id=rc.stuid and  rc.quarter= '$quarter' and letter='#' and onreportcard='1' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$max_year." as rc cross join users as s where s.id=rc.stuid and s.grade='$grade' and  rc.quarter= '$quarter' and rc.academicyear='$max_year' and letter='#' and onreportcard='1' group by stuid order by stuRank ASC limit 3");
        	$output.='<div class="card-header"> <small class="text-muted">Grade: '.$row->grade.'</small></div>';
        	foreach ($queryMark->result() as $markValue) {
        		$output .='<ul class="k chat-list list-unstyled m-b-0">
		        	<li class="clearfix">
			        	<a href="#" class="nav-link nav-link-lg">
				        	<img src="'.base_url().'/profile/'.$markValue->profile.'" class="border-circle">
				        	<span class="badge badge-danger"> '.$markValue->stuRank.'</span>
				        	<div class="about">
					        	<div class="name">'.$markValue->fname.' '.$markValue->mname.' <small class="text-muted">'.$markValue->gradesec.'</small>
					        	</div>
					        	<small class="text-muted">('.$markValue->branch.')</small>
					        	<div class="text-success text-small font-600-bold">
					        		<i class="fas fa-check-circle"></i>
					        		'.number_format((float)$markValue->Average,2,'.','').' Total
					        	</div>
				        	</div>
			        	</a>
		        	</li>
	        	</ul>';
        	}
        }
        return $output;
	}
	function TopRankStudentsSection($max_year,$quarter){
		$query=$this->db->query("select * from users where status='Active' and usertype='Student' and academicyear='$max_year' and isapproved='1' group by gradesec ");
        $output ='';
        foreach ($query->result() as $row) { 
        	$id=$row->id;
        	$grade=$row->grade;
        	$gradesec=$row->gradesec;
        	$queryMark=$this->db->query("select s.branch, s.profile,s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.quarter= '$quarter' and letter='#' and onreportcard='1' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$max_year." as rc cross join users as s where s.id=rc.stuid and rc.quarter= '$quarter' and rc.academicyear='$max_year' and rc.grade='$gradesec' and letter='#' and onreportcard='1' group by stuid order by stuRank ASC limit 3 ");
        	$output.='<div class="card-header"> <small class="text-muted">Grade: '.$row->gradesec.'</small></div>';
        	foreach ($queryMark->result() as $markValue) {
        		$output .='<ul class="k chat-list list-unstyled m-b-0">
		        	<li class="clearfix">
			        	<a href="#" class="nav-link nav-link-lg">
				        	<span class="badge badge-info"> '.$markValue->stuRank.'</span>
				        	<div class="about">
					        	<div class="name">'.$markValue->fname.' '.$markValue->mname.'
					        	</div>
					        	<small class="text-muted">('.$markValue->branch.')</small>
					        	<div class="text-warning text-small font-600-bold">
					        		<i class="fas fa-check-circle"></i> '.
					        		number_format((float)$markValue->Average,2,'.','').' Total
					        	</div>
				        	</div>
			        	</a>
		        	</li>
	        	</ul>';
        	}
        }
        return $output;
	}
	function birthdate(){
		$query=$this->db->query("select * from users where status='Active' and isapproved='1' ");
        $output ='';
        foreach ($query->result() as $birthValue) {
        	$birthdate=$birthValue->dob;
        	$parts=explode('-', $birthdate);
        	$year=$parts[0];
        	$month=$parts[1];
        	$date=$parts[2];
        	$monthNow=date('m');
        	$dateNow=date('d');
        	if($dateNow==$date and $monthNow==$month){
        		$output .='<ul class="k chat-list list-unstyled m-b-0">
		        	<li class="clearfix">
			        	<a href="#" class="nav-link nav-link-lg">
				        	<small class="text-muted"> '.$birthValue->gradesec.' '.$birthValue->usertype.'</small>
				        	<div class="about">
					        	<div class="name">'.$birthValue->fname.' '.$birthValue->mname.' 
					        	</div> 
					        	<div class="text-info text-small font-600-bold">
					        		<i class="fas fa-birthday-cake"></i> Happy Brth Day
					        	</div>
				        	</div>
			        	</a>
		        	</li>
	        	</ul>';
        	}
        }
        return $output;
	}
	function timeTable($max_year,$dayValue,$periods){
		$query=$this->db->query("select * from staffplacement where academicyear='$max_year' ");
		$output ='';
		if($query->num_rows()>0){
			foreach ($query->result() as $placeValue) {
				$grade=$placeValue->grade;
				$staff=$placeValue->staff;
				$subject=$placeValue->subject;
				for ($i=1; $i <=$periods; $i++) { 
					$checkTimeTable=$this->db->query("select * from timetable where lessonday='$dayValue' and grade='$grade' and teacher='$staff' and period='$i' ");
					if($checkTimeTable->num_rows()<1){
						$data=array(
							'grade'=>$grade,
							'teacher'=>$staff,
							'subject'=>$subject,
							'lessonday'=>$dayValue,
							'period'=>$i
						);
						$this->db->insert('timetable',$data);
					}
				}
			}
			$queryTimeTable=$this->db->query("select * from timetable group by period order by period ASC");
			if($queryTimeTable->num_rows()>0){
				$output .='<div class="table-responsive">
		        <table class="table table-bordered">
		        <tr><th></th> ';
				foreach ($queryTimeTable->result() as $viewValue) {
					$output.='<th>'.$viewValue->period.'</th>';
				}
				$output .='</tr>';
				$queryTimeTable=$this->db->query("select * from timetable group by lessonday order by period ASC");
				foreach ($queryTimeTable->result() as $viewValue) {
					$output.='<tr><td>'.$viewValue->lessonday.'</td>';
					$output.='<td>'.$viewValue->subject.'</td></tr>';
				}
				$output .='</table></div>';
			}else{
				$output .='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No timeTable found.
            </div></div>';
			}
		}else{
			$output .='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No placement found.
            </div></div>';
		}
		return $output;
	}
	function insertdocument($data){
		$this->db->insert('mydocument',$data);
	}
	function insertnews($data){
		$this->db->insert('blogs',$data);
	}
	function insertvacancy($data){
		$this->db->insert('vacancy',$data);
	}
	function fetchdocuments($user){
		$this->db->where('fileuser',$user);
		$this->db->order_by('id','DESC');
		$query=$this->db->get('mydocument');
		$output='';
		if($query->num_rows()>0){
			$output='<div class="row">';
			foreach ($query->result() as $mydoc) {
				$output.='<div class="col-lg-3 deletedocument'.$mydoc->id.'">
                    <div class="card">
                		<div class="card-statistic-4">
                  			<div class="align-items-center justify-content-between">
                        		<div class="card-content">
                          			<h2 class="mb-3 font-18">'.$mydoc->filename.'
                            			<a href="'.base_url().'mydocument/'.$mydoc->filename.'" class="dwn" download> 
                              			<i class="fas fa-download"></i></a>
                              		</h2>
                           			<p class="mb-0">
                            			<small class="pull-right">
                              				<i data-feather="watch"></i>'.$mydoc->datecreated.'
                            			</small>
                          			</p>
                      			</div> ';
                        		if($mydoc->fileuser ==$_SESSION['username']){ 
                        			$output.='<a href="#"> <button class="btn btn-default deletemydocument" type="submit" id="'.$mydoc->id.'"><span class="text-danger">
                          			<i class="fas fa-trash"></i></span>
                        			</button> </a>';
                       			} 
                        		$output.='
                      		</div>
                  		</div>
                	</div>
              	</div>';
			}
			$output.='</div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No saved document found.
            </div></div>';
		}
		return $output;
	}
	function loadApplicants(){
		$this->db->order_by('id','DESC');
		$query=$this->db->get('jobapplicants');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="row">';
			foreach ($query->result() as $mydoc) {
				$output.='<div class="col-md-4 col-12 '.$mydoc->id.'">
                    <div class="card">
                		<div class="card-statistic-4">
                  			<div class="align-items-center justify-content-between">
                        		<div class="card-content">
                          			<p class="mb-0"> Position:<b> '.$mydoc->applyposition.'</b></p>
                          			<p class="mb-0"> Applicant Name: <b>'.$mydoc->applyfullname.'</b> </p>
                          			<p class="mb-0"> Applicant Mobile: <b>'.$mydoc->applymobile.'</b> </p>
                          			<p class="mb-0"> Applicant Qualification: <b>'.$mydoc->applyqualification.'</b></p>
                          			<p class="mb-0">Applicant Experience: <b>'.$mydoc->applyexperience.'</b></p>                   
                              		<p class="mb-0"> <small class="time"> Date Applied: '.$mydoc->dateapplied.' </small>'; 
                              		$fileName=$mydoc->applycv;
							        $output.='<a href="'.base_url().'employment/download/'.$fileName.'" class="get-started-btn">
							        <button class="btn btn-default" id=""><span class="text-info"><i class="fas fa-download"></i></span></button></a>
							        </p>
                      			</div> '; 
                        		$output.='
                      		</div>
                  		</div>
                	</div>
              	</div>';
			}
			$output.='</div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No Applicants found.
            </div></div>';
		}
		return $output;
	}
	function fetchvacancy($todayDate){
		$this->db->where('expire >=',$todayDate);
		$this->db->order_by('vid','DESC');
		$query=$this->db->get('vacancy');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="row">';
			foreach ($query->result() as $mydoc) {
				$output.='<div class="col-md-3 col-6 deletevacancy'.$mydoc->vid.'">
                    <div class="card">
                		<div class="card-statistic-4">
                  			<div class="align-items-center justify-content-between">
                        		<div class="card-content">
                          			<h2 class="mb-3 font-18">'.$mydoc->vposition.' 
                          			<a href="#" data-toggle="modal" data-target="#viewPostedVacancyDetail"> <button class="btn btn-default viewmyvacancy" type="submit" id="'.$mydoc->vid.'"><span class="text-info">
			                      			<i class="fas fa-eye"></i></span>
			                    			</button> 
			                    		</a>                   
                              			<p class="mb-0">
                              			<small class="time">
                              				<i data-feather="watch"></i>'.$mydoc->datepost.'
                            			</small>
                              			<button class="btn btn-default deletemyvacancy" type="submit" id="'.$mydoc->vid.'"><span class="text-danger">
			                      			<i class="fas fa-trash"></i></span>
			                    			</button> 
			                    		</p>
                              		</h2>
                      			</div> '; 
                        		$output.='
                      		</div>
                  		</div>
                	</div>
              	</div>';
			}
			$output.='</div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No vacancy found.
            </div></div>';
		}
		return $output;
	}
	function viewmyvacancyDetail($id){
		$this->db->where('vid ',$id);
		$this->db->order_by('vid','DESC');
		$query=$this->db->get('vacancy');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="row">';
			foreach ($query->result() as $mydoc) {
				$output.='<div class="col-md-12 col-12 deletevacancy'.$mydoc->vid.'">
                    <div class="card">
                		<div class="card-statistic-4">
                  			<div class="align-items-center justify-content-between">
                        		<div class="card-content">
                          			<p class="mb-0"><h2 class="mb-3 font-18">'.$mydoc->vposition.' 
                          			<small class="time"> <i data-feather="watch"></i>'.$mydoc->datepost.' </small> </h2>
                          			</p>
                          			<p class="mb-0"><h2 class="mb-3 font-18">'.$mydoc->post.'</h2>
                          			</p>
                          			<p class="mb-0"><h2 class="mb-3 font-18"><small> Date Expire: '.$mydoc->expire.' Posted By: '.$mydoc->postby.'</small></h2>
                          			</p>
                      			</div> '; 
                        		$output.='
                      		</div>
                  		</div>
                	</div>
              	</div>';
			}
			$output.='</div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No vacancy found.
            </div></div>';
		}
		return $output;
	}
	function fetchnews(){
		$this->db->order_by('nid','DESC');
		$query=$this->db->get('blogs');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="row">';
			foreach ($query->result() as $mydoc) {
				$output.='<div class="col-md-3 deletenews'.$mydoc->nid.'">
                    <div class="card">
                		<div class="card-statistic-4">
                  			<div class="align-items-center justify-content-between">
                        		<div class="card-content">
                          			<h2 class="mb-3 font-18">'.$mydoc->ntitle.'                    
                              			<p class="mb-0">
                              			<small class="time">
                              				<i data-feather="watch"></i>'.$mydoc->datepost.'
                            			</small>
                              			<a href="#"> <button class="btn btn-default deletemynews" type="submit" id="'.$mydoc->nid.'"><span class="text-danger">
			                      			<i class="fas fa-trash"></i></span>
			                    			</button> 
			                    		</a></p>
                              		</h2>
                      			</div> '; 
                        		$output.='
                      		</div>
                  		</div>
                	</div>
              	</div>';
			}
			$output.='</div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No News found.
            </div></div>';
		}
		return $output;
	}
	function deletedocuments($id){
		$this->db->where('id',$id);
		$query=$this->db->delete('mydocument');
	}
	function deletenews($id){
		$this->db->where('nid',$id);
		$query=$this->db->delete('blogs');
	}
	function deletevacancy($id){
		$this->db->where('vid',$id);
		$query=$this->db->delete('vacancy');
	}
	function fetchparents($max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->group_by('mname');
		$this->db->group_by('lname');
		$query=$this->db->get('users');
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive"> 
            <table class="table table-striped table-hover" style="width:100%;">
            <thead><tr> 
                <th>No.</th>
                <th>Parent Name</th>
                <th>Academic Year</th> </tr>
            </thead>';
        	$no=1;
			foreach ($query->result() as $fetchparents) {
				$output.='
				<tr>
	                <td>'.$no.'.</td>
	                <td>'.$fetchparents->mname.' '.$fetchparents->lname.'</td>
	                <td>'.$fetchparents->academicyear.'</td>
	            </tr>';
			    $no++;
			}
		}else{
			$output .='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No school parent found.
            </div></div>';
		}
		return $output;
	}
	function loadSimilarId(){
		$query=$this->db->query("SELECT *, unique_id,count(unique_id),GROUP_CONCAT(gradesec) as gradesecc from users group by unique_id ,academicyear Having count(unique_id)>1 ");
		$output='';
    	if($query->num_rows()>0){
    		$output='<div class="table-responsive"> 
            <table class="table table-striped table-hover" style="width:100%;">
            <thead><tr> 
                <th>No.</th>
                <th>ID</th>
                <th>Grade</th> </tr>
            </thead>';
            $no=1;
    		foreach ($query->result() as $idValue) {
    			$output.='
				<tr>
	                <td>'.$no.'.</td>
	                <td>'.$idValue->unique_id.'</td>
	                <td>'.$idValue->gradesecc.'</td>
	            </tr>';
			    $no++;
    		}
    	}else{
    		$output .='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No similar ID found.
            </div></div>';
    	}
    	return $output;
	}
	function saveGroup($usergroup,$user){
		$queryUg=$this->db->query("select uname from usegroup where uname='$usergroup' group by uname ");
		if($queryUg->num_rows()<1){
			if($usergroup==trim('superadmin')){
	            $usergroup='superAdmin';
				$data=array(
					'uname'=>$usergroup,
					'datecreated'=>date('M-d-Y'),
					'createdby'=>$user
				);
			}else{
				$data=array(
					'uname'=>ucfirst(strtolower($usergroup)),
					'datecreated'=>date('M-d-Y'),
					'createdby'=>$user
				);
			}
			$this->db->insert('usegroup',$data);
		}
	}
	function saveBgImage($setasbg,$user){
		$queryUg=$this->db->query("select bgname from bgimage where bguser='$user' ");
		if($queryUg->num_rows()>0){
			$data=array(
				'bgname'=>$setasbg,
				'bguser'=>$user
			);
			$this->db->update('bgimage',$data);
		}else{
			$data=array(
				'bgname'=>$setasbg,
				'bguser'=>$user
			);
			$this->db->insert('bgimage',$data);
		}
	}
	function fetch_usergroup(){
		$query=$this->db->query("SELECT * from usegroup group by uname order by uname ASC ");
		return $query;
	}
	function fetchUserGroupRegistration(){
		$query=$this->db->query("SELECT * from usegroup where uname!='superAdmin' group by uname order by uname ASC ");
		return $query;
	}
	function fetchUserGroup(){
		$query=$this->db->query("SELECT * from usegroup group by uname order by uname ASC ");
		$output='';
    	if($query->num_rows()>0){
    		$output='<div class=""> 
            <table class="table table-striped table-hover" style="width:100%;">
            <thead><tr> 
                <th>No.</th>
                <th>User Group</th>
                <th>User Level</th>
                <th>Access Other Branch</th>
                <th>Date Created</th> </tr>
            </thead>';
            $no=1;
    		foreach ($query->result() as $idValue) {
    			$userLevel=$idValue->userlevel;
    			$output.='
				<tr>
	                <td>'.$no.'.</td>
	                <td>'.$idValue->uname.'
	                <div class="table-links">
		             <a href="#" class="deleteUserGroup text-danger" id="'.$idValue->ugid.'">Delete</a>
		            </div>
	                </td>
	                <td>
	                <select class="form-control groupUserLevel" required="required" name="groupUserLevel" id="groupUserLevel">
                    <option>'.$userLevel.'</option>';
                    for($i=1;$i<=3;$i++){
                    	if($i!=$userLevel){
                    		$output.='<option value="'.$i.'" class="'.$idValue->uname.'"> '.$i.' </option>';
                    	}
                    }
                    $output.='
                </select></td>';
	                $userName=$idValue->uname;
					$this->db->where('uname',$userName);
					$this->db->where('accessbranch','1');
					$query=$this->db->get('usegroup');
					if($userLevel =='1'){
						if($query->num_rows()>0){
							$output.='<td class="text-center"><div class="pretty p-switch p-fill">
		                      <input type="checkbox" name="accessOtherBranch" class="accessOtherBranch" checked="checked" id="'.$idValue->uname.'" value="'.$idValue->uname.'" title="Homepage">
		                      <div class="state p-success">
		                        <label></label>
		                      </div>
		                    </div></td>';
						}else{
							$output.='<td class="text-center"><div class="pretty p-switch p-fill">
							<input type="checkbox" name="accessOtherBranch" class="accessOtherBranch" id="'.$idValue->uname.'" value="'.$idValue->uname.'" title="Homepage">
		                    <div class="state p-success">
		                        <label></label>
		                      </div>
		                    </div></td>';
						}
					}else{
						$output.='<td class="text-center">-</td>';
					}
	                $output.='<td>'.$idValue->datecreated.'</td>
	            </tr>';
			    $no++;
    		}
    	}else{
    		$output .='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
            </div></div>';
    	}
    	return $output;
	}
	function deleteGroup($ugid){
		$this->db->where('ugid',$ugid);
		$this->db->delete('usegroup');
	}
	function grantUserPermission(){
		$output='';
		$usergroup=$this->db->query("SELECT * from usegroup group by uname order by uname ASC ");
		$output.='<div class="table-responsive">
            <table width="100%" class="tabler table-borderedr" cellspacing="5" cellpadding="5">
                <thead>
                    <tr>
                        <th>Table Name</th><th>Action</th>';
                        foreach($usergroup->result() as $usergroups){ 
	                    	$userName=$usergroups->uname;
	                        $output.='<th class="text-center">'.$userName.'</th>';
	                    }
                    $output.='</tr></thead>
                <tr>
                    <td>Home Page</td> <td>Post Photo/Text</td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','postInfo');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($query->num_rows()>0){
							$output.='<td class="text-center"><div class="pretty p-switch p-fill">
		                      <input type="checkbox" name="chatPermission" class="postInfo" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Homepage">
		                      <div class="state p-success">
		                        <label></label>
		                      </div>
		                    </div></td>';
						}else{
							$output.='<td class="text-center"><div class="pretty p-switch p-fill">
							<input type="checkbox" name="chatPermission" class="postInfo" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Homepage">
		                    <div class="state p-success">
		                        <label></label>
		                      </div>
		                    </div></td>';
						}
	                } 
	                $output.='</tr>
                <tr>
                    <td rowspan="7">Manage Student</td><td>Edit/Delete Student</td> ';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','StudentDE');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="StudentDE" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student" >
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="StudentDE" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student" >
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='</tr><tr><td>Student Promotion</td>';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','StudentPr');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="StudentPr" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student" >
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="StudentPr" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student" >
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	                $output.='</tr><tr><td>Student Placement</td>';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','StudentPl');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="StudentPl" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student" >
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="StudentPl" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student" >
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	                $output.='</tr><tr><td>Branch Placement</td>';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','Studentbp');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="Studentbp" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student" >
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="Studentbp" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student" >
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	                $output.='</tr><tr><td>View Student</td>';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','StudentVE');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="StudentVE" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="StudentVE" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }

	            	$output.='</tr><tr><td>Grade Group</td>';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','gradeGroup');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="gradeGroup" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="gradeGroup" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	                $output.='</tr><tr><td>Drop Student</td>';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','StudentDrop');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="StudentDrop" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="StudentDrop" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Student">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	            	$output.='</tr>
	            	<tr>
                    <td rowspan="5">Manage Staff</td><td>Edit/Delete Staff</td> ';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','staffDE');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="staffDE" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Staff">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="staffDE" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Staff">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='</tr><tr><td>Teacher Placement</td> ';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','staffPl');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="staffPl" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Staff">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="staffPl" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Staff">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
                $output.='</tr><tr><td>Director Placement</td>';
                	foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','directorPl');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="directorPl" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Staff">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="directorPl" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Staff">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
                $output.='</tr><tr><td>Homeroom Placement</td>';
                	foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','hoomeroomPl');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="hoomeroomPl" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Staff">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="hoomeroomPl" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Staff">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	                $output.='</tr><tr><td>Staffs Phone</td>';
                	foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','staffPhone');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="staffPhone" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Staff">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="staffPhone" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Staff">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
                	$output.='</tr>
                    <tr><td>Add/Delete Subject</td><td></td> ';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','Subject');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="Subject" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Subject">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="Subject" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Subject">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <tr>
                    <td rowspan="2">Manage ID Card</td> <td>Staff ID</td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','StaffIDCard');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="StaffIDCard" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="IDCard">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="StaffIDCard" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="IDCard">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <td>Student ID</td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','StudentIDCard');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="StudentIDCard" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="IDCard">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="StudentIDCard" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="IDCard">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
                <tr>
                    <td>Add/Delete Evaluation</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','Evaluation');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="Evaluation" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Evaluation">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="Evaluation" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Evaluation">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <tr>
                    <td rowspan="2">Manage Attendance</td> <td>Student Attendance</td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','studentAttendance');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="studentAttendance" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Attendance" >
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="studentAttendance" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Attendance">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	            $output.='</tr><tr><td>Staff Attendance</td>';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','staffAttendance');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="staffAttendance" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Attendance" >
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="staffAttendance" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Attendance">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	            $output.='</tr>
	            <tr>
                    <td>Manage Communication Book</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','communicationbook');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="communicationbook" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="CommunicationBook">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="communicationbook" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="CommunicationBook">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <tr>
                    <td rowspan="2">Manage Lesson Plan</td> <td>Add Lesson Plan</td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','addlessonplan');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="addlessonplan" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="LessonPlan">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="addlessonplan" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="LessonPlan">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='</tr><tr><td>View Lesson Plan</td>';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','viewlessonplan');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="viewlessonplan" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="LessonPlan">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="viewlessonplan" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="LessonPlan">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	            $output.='</tr>
	            <tr>
                    <td>Manage Homework/Worksheet</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','homeworkworksheet');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="homeworkworksheet" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="HowworkSheet">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="homeworkworksheet" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="HowworkSheet">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <tr>
                    <td>Manage Exam</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','studentexam');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="studentexam" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Exam">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="studentexam" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Exam">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <tr>
                    <td rowspan="6">Manage Mark</td> <td>Add/Delete Mark</td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','addstudentmark');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="addstudentmark" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="StudentMark">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="addstudentmark" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="StudentMark">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='</tr><tr><td>Prepare Mark Format</td>';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','studentmarkformat');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="studentmarkformat" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="StudentMark">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="studentmarkformat" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="StudentMark">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	                $output.='</tr><tr><td>Approve Mark</td>';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','approvemark');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="approvemark" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="StudentMark">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="approvemark" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="StudentMark">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	                $output.='</tr><tr><td>View Mark/Analysis</td>';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','viewstudentmark');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($query->num_rows()>0){
							$output.='<td class="text-center"><div class="pretty p-switch p-fill">
		                      <input type="checkbox" name="chatPermission" class="viewstudentmark" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="StudentMark">
		                      <div class="state p-success">
		                        <label></label>
		                      </div>
		                    </div></td>';
						}else{
							$output.='<td class="text-center"><div class="pretty p-switch p-fill">
							<input type="checkbox" name="chatPermission" class="viewstudentmark" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="StudentMark">
		                    <div class="state p-success">
		                        <label></label>
		                      </div>
		                    </div></td>';
						}
	                }
	                $output.='</tr> <tr><td> Active/Inactive Division</td>';
                	foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','activeInactiveDiv');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="activeInactiveDiv" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Staff">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="activeInactiveDiv" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="Staff">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	                $output.='</tr> <tr><td> Lock/Unlock Mark</td>';
                	foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','lockstudentmark');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="lockstudentmark" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="StudentMark">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="lockstudentmark" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="StudentMark">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	            $output.='</tr>
	            <tr><td rowspan="5">Manage Student Card</td>
                    <td>Report Card</td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','reportcard');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="reportcard" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="studentCard">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="reportcard" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="studentCard">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 

	                $output.='</tr><tr><td>Roster</td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','roster');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="roster" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="studentCard">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="roster" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="studentCard">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	                $output.='</tr><tr><td>Transcript</td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','transcript');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="transcript" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="studentCard">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="transcript" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="studentCard">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	                $output.='</tr><tr><td>Rank Report</td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','rankReport');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="rankReport" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="studentCard">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="rankReport" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="studentCard">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	                $output.='</tr><tr><td>Statistics</td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','Statistics');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="Statistics" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="studentCard">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="Statistics" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="studentCard">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	                $output.='
	            </tr>
	            <tr>
                    <td>Manage Student Basic Skills</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','studentbasicskill');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="studentbasicskill" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="BasikSkill">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="studentbasicskill" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="BasikSkill">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <tr>
                    <td rowspan="2">Import & Export</td> <td>Export Format</td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','exportFile');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="exportFile" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="ImportExport">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="exportFile" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="ImportExport">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='</tr><tr><td>Import File</td>';
	                foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','importFile');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="importFile" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="ImportExport">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="importFile" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="ImportExport">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                }
	            $output.='</tr>
	            <tr>
                    <td>Fee Management</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','feemanagment');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="feemanagment" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="FeeManagment">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="feemanagment" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="FeeManagment">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <tr>
                    <td>Library Management</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','elibrary');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="elibrary" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="libraryManagment">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="elibrary" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="libraryManagment">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
                <tr>
                    <td>Send Chat/message</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','Chat');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="Chat" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="chatMessage">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="Chat" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="chatMessage">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <tr>
                    <td>School Files</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','schoolfiles');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="schoolfiles" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="schoolFiles">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="schoolfiles" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="schoolFiles">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <tr>
                    <td>Website Managment</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','websitemanagment');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="websitemanagment" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="websiteManagment">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="websitemanagment" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="websiteManagment">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <tr>
                    <td>Managment Tasks</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','taskspage');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel=='1'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="taskspage" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="tasksManagement">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="taskspage" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="tasksManagement">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <tr>
                    <td>Managment Summer Class</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','summerclass');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="summerclass" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="summerClassManagement">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="summerclass" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="summerClassManagement">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>
	            <tr>
                    <td>Teacher Performance</td> <td></td>';
                    foreach($usergroup->result() as $usergroups){ 
                    	$userName=$usergroups->uname;
                    	$userLevel=$usergroups->userlevel;
						$this->db->where('usergroup',$userName);
						$this->db->where('allowed','teacherperformance');
						$this->db->order_by('usergroup','ASC');
						$query=$this->db->get('usergrouppermission');
						if($userLevel!='3'){
							if($query->num_rows()>0){
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
			                      <input type="checkbox" name="chatPermission" class="teacherperformance" checked="checked" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="teacherperformance">
			                      <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}else{
								$output.='<td class="text-center"><div class="pretty p-switch p-fill">
								<input type="checkbox" name="chatPermission" class="teacherperformance" id="'.$usergroups->uname.'" value="'.$usergroups->uname.'" title="teacherperformance">
			                    <div class="state p-success">
			                        <label></label>
			                      </div>
			                    </div></td>';
							}
						}else{
							$output.='<td class="text-center">-</td>';
						}
	                } 
	                $output.='
	            </tr>	            
	        </table>
	    </div>';        
        return $output;
	}
	function prepareMarkTable($max_quarter,$max_year){
		$output='<div class="row">';
		$queryBranch=$this->db->query("select name from branch where academicyear='$max_year' group by name ");
		if($queryBranch->num_rows()>0){
			foreach ($queryBranch->result() as $branchValue) {
				$branch=$branchValue->name;
				$queryStudent=$this->db->query("select us.gradesec,qu.term from users as us cross join quarter as qu ON qu.termgrade=us.grade where us.academicyear='$max_year' and usertype='Student' and status='Active' and isapproved='1' and branch='$branch' and qu.Academic_year='$max_year' group by gradesec,term; ");
				if($queryStudent->num_rows()>0){ 
					foreach ($queryStudent->result() as $gradesecValue) {
						$gradesec=$gradesecValue->gradesec;
						$term=$gradesecValue->term;
						$fields=array(
							'mid'=>array(
								'type'=>'INT',
								'constraint'=>255,
								'auto_increment'=>TRUE
							),
							'stuid'=>array(
								'type'=>'INT',
								'constraint'=>255
							),
							'mgrade'=>array(
								'type'=>'varchar',
								'constraint'=>25
							),
							'subname'=>array(
								'type'=>'VARCHAR',
								'constraint'=>50
							),
							'evaid'=>array(
								'type'=>'INT',
								'constraint'=>255
							),
							'quarter'=>array(
								'type'=>'VARCHAR',
								'constraint'=>15
							),
							'outof'=>array(
								'type'=>'INT',
								'constraint'=>255
							),
							'value'=>array(
								'type'=>'double'
							),
							'academicyear'=>array(
								'type'=>'INT',
								'constraint'=>5
							),
							'markname'=>array(
								'type'=>'VARCHAR',
								'constraint'=>25
							),
							'status'=>array(
								'type'=>'INT',
								'constraint'=>2
							),
							'lockmark'=>array(
								'type'=>'INT',
								'constraint'=>2
							),
							'approved'=>array(
								'type'=>'INT',
								'constraint'=>1
							),
							'approvedby'=>array(
								'type'=>'INT',
								'constraint'=>25
							),
							'zeromarkinfo'=>array(
								'type'=>'INT',
								'constraint'=>5
							),
							'mbranch'=>array(
								'type'=>'varchar',
								'constraint'=>25
							)
						);
						$this->dbforge->add_field($fields);
						$this->dbforge->add_key('mid',TRUE);
						$query=$this->dbforge->create_table('mark'.$branch.$gradesec.$term.$max_year,TRUE);
						$sql = "CREATE INDEX IF NOT EXISTS stuid  ON mark".$branch.$gradesec.$term.$max_year."(stuid,mgrade,subname,evaid,markname,mbranch)";
						$this->db->query($sql);		
					}
				}
			}
			if($query){
				$output .='<div class="col-lg-12"><div class="alert alert-success alert-dismissible show fade">
	            <div class="alert-body">
	                <button class="close"  data-dismiss="alert">
	                    <span>&times;</span>
	                </button>
	            <i class="fas fa-check-circle"> </i> Table created successfully.
	           </div></div></div>';
			}
		}else{
			$output .='<div class="col-lg-12"><div class="alert alert-warning alert-dismissible show fade">
	            <div class="alert-body">
	                <button class="close"  data-dismiss="alert">
	                    <span>&times;</span>
	                </button>
	            <i class="fas fa-check-circle"> </i> Please adjust your branch.
	           </div></div></div>';
		}
		$output.='</div>';
		return $output;
	}
	function prepareBSTable($max_quarter,$max_year){
		$output='<div class="row">';
		$queryStudent=$this->db->query("select gradesec from users where academicyear='$max_year' 
			and usertype='Student' and status='Active' and isapproved='1' group by gradesec; "); 
		foreach ($queryStudent->result() as $gradesecValue) {
			$gradesec=$gradesecValue->gradesec;
			$fields=array(
				'id'=>array(
					'type'=>'INT',
					'constraint'=>255,
					'auto_increment'=>TRUE
				),
				'stuid'=>array(
					'type'=>'INT',
					'constraint'=>255
				),
				'grade'=>array(
					'type'=>'varchar',
					'constraint'=>25
				),
				'conduct'=>array(
					'type'=>'VARCHAR',
					'constraint'=>255
				),
				'bsname'=>array(
					'type'=>'VARCHAR',
					'constraint'=>255
				),
				'value'=>array(
					'type'=>'VARCHAR',
					'constraint'=>255
				),
				'quarter'=>array(
					'type'=>'VARCHAR',
					'constraint'=>255
				),
				'academicyear'=>array(
					'type'=>'INT',
					'constraint'=>5
				),
				'datecreated'=>array(
					'type'=>'VARCHAR',
					'constraint'=>255
				),
				'byuser'=>array(
					'type'=>'varchar',
					'constraint'=>255
				),
				'bsgrade'=>array(
					'type'=>'VARCHAR',
					'constraint'=>255
				),
				'bsbranch'=>array(
					'type'=>'VARCHAR',
					'constraint'=>255
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id',TRUE);
			$query=$this->dbforge->create_table('basicskillvalue'.$gradesec.$max_year,TRUE);		
		}
			
		if($query){
			$output .='<div class="col-lg-12"><div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            <i class="fas fa-check-circle"> </i> Table created successfully.
           </div></div></div>';
		}
		
		$output.='</div>';
		return $output;
	}
	function searchFinanceStudents($searchItem,$branch,$max_year){
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
				<td>'.$value->unique_id.' 
				<div class="table-links">
	             <a href="#" class="deletestudent text-danger" id="'.$value->id.'"><i class="fas fa-trash-alt"></i></a>
	             <div class="bullet"></div>
	             <a href="#" class="dropstudent text-warning" id="'.$value->id.'"><i class="fas fa-user-times"></i></a>
	             <div class="bullet"></div>
	             <a href="#" class="editstudent text-success" id="'.$value->unique_id.'" value="'.$max_year.'"><i class="fas fa-user-edit"></i></a>
	             <div class="bullet"></div>
	             <a href="#" class="viewStudentPrint" value="" data-toggle="modal" data-target="#printStudentViewModal" id="'.$value->unique_id.'"> <span class="text-info"><i class="fas fa-eye"></i></span></a>
	            </div>
				</td>
	            <td>'.$value->fname .' '.$value->mname.' '.$value->lname.' 
	             <div class="table-links">
	             	<a href="#" class="resetPassword text-warning" id="'.$value->unique_id.'">Reset Password</a>
	            </div>
	            </td> 
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
	function searchAdminStudents($searchItem,$branch,$max_year){
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
	            <th>Gr. & Sec</th>
	            <th>Gender</th>
	            <th>Branch</th>
	            <th>Mark & Attendance</th>
	        </tr>
	        </thead>
	        <tbody>';
	        $no=1;
			foreach ($query ->result() as $value) {
				$id=$value->id;
				$output .='<tr class="delete_mem'.$value->id.'">
				<td>'.$no.'.</td>
				<td>'.$value->unique_id.' 
				<div class="table-links">
	             <a href="#" class="deletestudent text-danger" id="'.$value->id.'"><i class="fas fa-trash-alt"></i></a>
	             <div class="bullet"></div>
	             <a href="#" class="dropstudent text-warning" id="'.$value->id.'"><i class="fas fa-user-times"></i></a>
	             <div class="bullet"></div>
	             <a href="#" class="editstudent text-success" id="'.$value->unique_id.'" value="'.$max_year.'"><i class="fas fa-user-edit"></i></a>
	             <div class="bullet"></div>
	             <a href="#" class="viewStudentPrint" value="" data-toggle="modal" data-target="#printStudentViewModal" id="'.$value->unique_id.'"> <span class="text-info"><i class="fas fa-eye"></i></span></a>
	            </div>
				</td>
	            <td>'.$value->fname .' '.$value->mname.' '.$value->lname.' 
	             <div class="table-links">
	             	<a href="#" class="resetPassword text-warning" id="'.$value->unique_id.'">Reset Password</a>
	            </div>
	            </td> 
	            <td>'.$value->gradesec.'</td>
	            <td>'.$value->gender.'</td>
	            <td>'.$value->branch.' </td>  
	            <td class="text-center"><a href="#" target="_blanck"><button class="btn btn-default" id="viewStuAttendance" name="'.$max_year.'" value="'.$value->username.'"><span class="text-info"> View</span></button></a></td>
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
	function searchStudents($searchItem,$max_year){
		$this->db->order_by('fname','ASC');
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->like('username',$searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->or_like('fname', $searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->or_like('grade', $searchItem);
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
	            <th>Mark & Attendance</th>
	        </tr>
	        </thead>
	        <tbody>';
	        $no=1;
			foreach ($query ->result() as $value) {

			$id=$value->id;
			$output .='<tr class="delete_mem'.$value->id.'">
			<td>'.$no.'.</td>
			<td>'.$value->unique_id.'
            <div class="table-links">
             <a href="#" class="deletestudent text-danger" id="'.$value->id.'"><i class="fas fa-trash-alt"></i></a>
             <div class="bullet"></div>
             <a href="#" class="dropstudent text-warning" id="'.$value->id.'"><i class="fas fa-user-times"></i></a>
             <div class="bullet"></div>
             <a href="#" class="editstudent text-success" id="'.$value->unique_id.'" value="'.$max_year.'"><i class="fas fa-user-edit"></i></a>
             <div class="bullet"></div>
             <a href="#" class="viewStudentPrint" value="" data-toggle="modal" data-target="#printStudentViewModal" id="'.$value->unique_id.'"> <span class="text-info"><i class="fas fa-eye"></i></span></a>
            </div>
            </td>
            <td>'.$value->fname .' '.$value->mname.' '.$value->lname.'
            <div class="table-links">
             	<a href="#" class="resetPassword text-warning" id="'.$value->unique_id.'">Reset Password</a>
            </div>
            </td> ';
           
            $output.='<td>'.$value->gradesec.'</td>';
            
            $output.='
            <td>'.$value->gender.'</td>
            <td>'.$value->branch.' </td>     
            <td class="text-center"><a href="#" target="_blanck"><button class="btn btn-default" id="viewStuAttendance" name="'.$max_year.'" value="'.$value->username.'"><span class="text-info"> View</span></button></a> </td> 
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
	function searchStudentsToLockMark($searchItem,$max_year){
		$this->db->order_by('fname','ASC');
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->like('username',$searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->or_like('fname', $searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->or_like('grade', $searchItem);
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
            <td><button class="btn btn-info lockThisStudentMark" id="lockThisStudentMark'.$value->id.'" name="'.$max_year.'" value="'.$value->id.'"> Lock</button>
            <button class="btn btn-warning unlockThisStudentMark" id="unlockThisStudentMark'.$value->id.'" name="'.$max_year.'" value="'.$value->id.'"> UnLock</button>
            </td> 
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
            <td><button class="btn btn-info lockThisStudentMark" id="lockThisStudentMark'.$value->id.'" name="'.$max_year.'" value="'.$value->id.'"> Lock</button>
            <button class="btn btn-warning unlockThisStudentMark" id="unlockThisStudentMark'.$value->id.'" name="'.$max_year.'" value="'.$value->id.'"> UnLock</button>
            </td> 
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
	function fetchNoGrades($max_year){
		$queryStudent=$this->db->query("select grade from users where academicyear='$max_year' and usertype='Student' group by grade ");
		$output='';
		if($queryStudent->num_rows()>0){
			$output.='<div class="row">';
			foreach ($queryStudent->result() as $gradeValue) {
				$output.='<ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link selectThisGrade" value="'.$gradeValue->grade.'" href="#" role="tab" aria-selected="true">'.$gradeValue->grade.'
                    </a>
                  </li>
                </ul>';
			}
			$output.='</div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No grade found.
            </div></div>';
		}
		return $output;
	}
	function fetchNoSections($grade,$max_year){
		$queryStudent=$this->db->query("select section from users where academicyear='$max_year' and usertype='Student' and grade='$grade' group by section ");
		$output='';
		if($queryStudent->num_rows()>0){
			$output.='<div class="row">';
			foreach ($queryStudent->result() as $gradeValue) {
				$output.='<ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link" id="'.$gradeValue->section.'" href="#" role="tab"
                      aria-selected="true">'.$gradeValue->section.'</a>
                  </li>
                </ul>';
			}
			$output.='</div>';
			$totalSec= $queryStudent->num_rows();
			$output.='<div class="alert alert-info alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> There are '.$totalSec.' Sections.
            </div></div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No grade found.
            </div></div>';
		}
		return $output;
	}
	function nOfStudents($max_year){
		$queryStu=$this->db->query("select id from users where academicyear='$max_year' and usertype='Student' and status='Active' and isapproved='1' group by id ");
		$output='';
		$totalStudent=$queryStu->num_rows();
		$output .='<div class="badge badge-light alert-dismissible show fade">
                <div class="alert-body">
                <i class="fas fa-check-circle"> </i> No of Student '.$totalStudent.'.
        </div></div>';
		return $output;
	}
	function fetchContype($max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(congrade) as gradess from conductype where academicyear ='$max_year' group by coname,condesc order by cid ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="card">
	        <div class="card-header">
	            <h4>Conduct Types</h4>
	        </div>
			<div class="table-responsive">
	        <table class="table table-stripped table-hover" style="width:100%;">
	            <thead>
	                <tr>
	                    <th>No.</th>
	                    <th>Grade</th>
	                    <th>Conduct Value</th>
	                    <th>Conduct Description</th>
	                    <th>Academic Year</th>
	                    <th>Date Created</th>
	                </tr>
	            </thead>';
	        $no=1;
			foreach ($query->result() as $bsnames) {
				$output.='<tr class="deleteCon'.$bsnames->coname.''.$bsnames->condesc.'">
	                <td>'.$no.'.</td>
	                <td>'.$bsnames->gradess.'</td>
	                <td>'.$bsnames->coname.'
	                <div class="table-links"> <a href="#" 
	                class="deletecontype" id="'.$bsnames->condesc.'" value="'.$bsnames->coname.'">
	                 <span class="text-danger"><i class="fas fa-trash"></i></span> 
	                 </a>
	                 </div> </td>
	                <td>'.$bsnames->condesc.'</td>
	                <td>'.$bsnames->academicyear.'</td>
	                <td>'.$bsnames->datecreated.'</td>
	            </tr>';
	            $no++;
			}
			$output.='</table></div></div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No conduct value found.
        	</div></div>';
		}
		return $output;
	}
	function deleteBsCategory($id,$max_year){
		$this->db->where(array('bscategory'=>$id));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->delete('bscategory');
	}
	function deleteConType($id,$delte_desc,$max_year){
		$this->db->where(array('coname'=>$id));
		$this->db->where(array('condesc'=>$delte_desc));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->delete('conductype');
	}
	function fetch_bstype($max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(btgrade) as gradess from bstype where academicyear ='$max_year' group by bstype,bsdesc order by bstid ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="card">
	        <div class="card-header">
	            <h4>Basic Skill Types</h4>
	        </div>
			<div class="table-responsive">
	        <table class="table table-stripped table-hover" style="width:100%;">
	            <thead>
	                <tr>
	                    <th>No.</th>
	                    <th>Grade</th>
	                    <th>Basic Skill Type</th>
	                    <th>Basic Skill Description</th>
	                    <th>Academic Year</th>
	                    <th>Date Created</th>
	                </tr>
	            </thead>';
	        $no=1;
			foreach ($query->result() as $bsnames) {
				$output.='<tr class="delete_bs'.$bsnames->bstype.'">
	                <td>'.$no.'.</td><td>'.$bsnames->gradess.'</td>
	                <td>'.$bsnames->bstype.'
	                <div class="table-links"> <a href="#" 
	                class="deletebaskilltype" value="'.$bsnames->bstype.'">
	                 <span class="text-danger"><i class="fas fa-trash"></i></span> 
	                 </a><div class="bullet"></div>
	                 <a href="#" class="editbaskilltype" value="'.$bsnames->bstid.'">
	                 <span class="text-info"><i class="fas fa-pen"></i></span> 
	                 </a> </div> </td>
	                <td>'.$bsnames->bsdesc.'</td>
	                <td>'.$bsnames->academicyear.'</td>
	                <td>'.$bsnames->datecreated.'</td>
	            </tr>';
	            $no++;
			}
			$output.='</table></div></div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No basic skill type found.
        	</div></div>';
		}
		return $output;
	}
	function fethchBsToEdit($bstid,$max_year){
		$query=$this->db->query("select * from basicskill where academicyear='$max_year' and bsname='$bstid' group by bsname order by bsname ASC ");
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="row"> ';
			foreach ($query->result() as $bsnames) {
				$output.='<div class="col-lg-5"> 
				<input type ="hidden" name="" id="bsnameInfo" value="'.$bsnames->bsname.'" >';
				$output.='<input type="text" class="form-control bsInfo" name="bstid" value="'.$bsnames->bsname.'"> </div><div class="col-lg-5">';
				$output.='<button class="btn btn-primary" id="saveBsInfo" value="'.$bstid.'">Save Changes</button> ';
			}
			$output.='</div> ';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No basic skill type found.
        	</div></div>';
		}
		return $output;
	}
	function fethchBsTypeToEdit($bstid,$max_year){
		$query=$this->db->query("select * from bstype where academicyear='$max_year' and bstid='$bstid' group by bstid order by bstype ASC ");
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="row"> ';
			foreach ($query->result() as $bsnames) {
				$output.='<div class="col-lg-5"> ';
				$output.='<input type="text" class="form-control bstypeInfo" name="bstid" value="'.$bsnames->bstype.'"> </div><div class="col-lg-5">';
				$output.='<input type="text" class="form-control bstdescInfo" name="bstdesc" value="'.$bsnames->bsdesc.'"></div> <div class="col-lg-2">';
				$output.='<button class="btn btn-primary" id="saveBsType" value="'.$bstid.'">Save Changes</button> ';
			}
			$output.='</div> ';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No basic skill type found.
        	</div></div>';
		}
		return $output;
	}
	function deleteBsType($id,$max_year){
		$this->db->where(array('bstype'=>$id));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->delete('bstype');
	}
	function fecthStudentBs($branches,$gradesec,$quarter,$max_year){
		$queryfetchBS=$this->db->query("select u.id,u.fname,u.mname, u.lname, u.username, u.gradesec,u.grade from users as u where u.academicyear='$max_year' and u.usertype='Student' and u.status='Active' and u.isapproved='1' and u.gradesec='$gradesec' and u.branch='$branches' group by u.id  order by u.fname,u.mname,u.lname ASC ");
		$output='';
		if($queryfetchBS->num_rows()>0){
			$queryBS=$this->db->query("select bs.bsname,bs.subjectrow from users as u inner join basicskill as bs where bs.grade=u.grade and u.academicyear='$max_year' and u.usertype='Student' and u.status='Active' and u.isapproved='1' and u.gradesec='$gradesec' and u.branch='$branches' and bs.academicyear='$max_year' group by bs.bsname order by bs.bsname ASC ");
			$output.='<div class="card">
	        <div class="card-header">
	            <h4>Student Basic Skills Values</h4>
	        </div>
			<div class="table-responsive">
	        <table class="tabler table-borderedr table-hover" style="width:100%;">
	            <thead>
	                <tr>
	                    <th>No.</th>
	                    <th>Student Name</th>
	                    <th>Student ID</th>
	                    <th>Grade</th>';
	                    foreach ($queryBS->result() as $bsName) {
	                    	$output.='<th>'.$bsName->bsname.'</th>';
	                    }
	                $output.='</tr>
	            </thead>';
	        $no=1;
	        $output.='<input type="hidden" id="bsQuarter" value="'.$quarter.'" >';
	        $output.='<input type="hidden" id="bsGradesec" value="'.$gradesec.'" >';
	        $output.='<input type="hidden" id="bsBranch" value="'.$branches.'" >';
			foreach ($queryfetchBS->result() as $stuList) {
				$stuid=$stuList->id;
				$grade=$stuList->grade;
				$output.='<input type="hidden" id="bsConductStuId" value="'.$stuid.'" >';
				$output.='<tr> <td>'.$no.'</td>';
				$output.='<td>'.$stuList->fname.' '.$stuList->mname.'</td>';
				$output.='<td>'.$stuList->username.'</td>';
				$output.='<td>'.$stuList->gradesec.'</td>';
				foreach ($queryBS->result() as $bsName1) {
					$bsName=$bsName1->bsname;
					$subjectRow=$bsName1->subjectrow;
					if($subjectRow!='1'){
						$queryBsValue=$this->db->query("select conduct,bsname,value,quarter, academicyear from basicskillvalue".$gradesec.$max_year." 
						where academicyear='$max_year' and quarter='$quarter' and stuid='$stuid' and bsname='$bsName' group by bsname order by bsname ASC ");
						if($queryBsValue->num_rows()>0){
							foreach ($queryBsValue->result() as $bsValue) {
								$output.='<td><select class="form-control insertbsTypeo" name="insertbsType" >';
								$insertedValue=$bsValue->value;
								if($insertedValue==''){
									$queryBsType=$this->db->query("select bstype from bstype where academicyear='$max_year' and btgrade='$grade' ");
									$output.='<option>'.$bsValue->value.'</option>';
									foreach ($queryBsType->result() as $bsType)
									{
										$output.='<option id="insertbsTypeGS" class="'.$bsName.'" name="'.$stuList->id.'" value="'.$bsType->bstype.'">'.$bsType->bstype.'</option>';
									}
								}else{
									$output.='<option>'.$bsValue->value.'</option>';
									$queryBsType2=$this->db->query("select bstype from bstype where academicyear='$max_year' and btgrade='$grade' and bstype!='$insertedValue' ");
									foreach ($queryBsType2->result() as $bsType)
									{
										$output.='<option id="insertbsTypeGS" class="'.$bsName.'" name="'.$stuList->id.'" value="'.$bsType->bstype.'">'.$bsType->bstype.'</option>';
									}	
								}
								$output.='</select></td>';
							}
						}else{
							$output.='<td><select class="form-control insertbsTypeo" name="insertbsType">';
							$queryBsType=$this->db->query("select bstype from bstype where academicyear='$max_year' and btgrade='$grade' ");
							$output.='<option></option>';
							foreach ($queryBsType->result() as $bsType) {
								$output.='<option id="insertbsTypeGS" class="'.$bsName.'" name="'.$stuList->id.'" value="'.$bsType->bstype.'">'.$bsType->bstype.'</option>';
							}
							$output.='</select></td>';
						}
					}else{
						$queryBsValue=$this->db->query("select conduct,bsname,value,quarter, academicyear from basicskillvalue".$gradesec.$max_year." where academicyear='$max_year' and quarter='$quarter' and stuid='$stuid' and bsname='$bsName' group by bsname order by bsname ASC ");
						if($queryBsValue->num_rows()>0){
							foreach ($queryBsValue->result() as $bsValue) {
								$output.='<td><select class="form-control insertbsTypeo" name="insertbsType" >';
								$insertedValue=$bsValue->value;
								if($insertedValue==''){
									$queryBsType=$this->db->query("select coname from conductype where academicyear='$max_year' and congrade='$grade' ");
									$output.='<option>'.$bsValue->value.'</option>';
									foreach ($queryBsType->result() as $bsType) {
										$output.='<option id="insertbsTypeGS" class="'.$bsName.'" name="'.$stuList->id.'" value="'.$bsType->coname.'">'.$bsType->coname.'</option>';
									}
								}else{
									$output.='<option>'.$bsValue->value.'</option>';
									$queryBsType2=$this->db->query("select coname from conductype where academicyear='$max_year' and congrade='$grade' and coname!='$insertedValue' ");
									foreach ($queryBsType2->result() as $bsType) {
										$output.='<option id="insertbsTypeGS" class="'.$bsName.'" name="'.$stuList->id.'" value="'.$bsType->coname.'">'.$bsType->coname.'</option>';
									}	
								}
								$output.='</select></td>';
							}
						}else{
							$output.='<td><select class="form-control insertbsTypeo" name="insertbsType">';
							$queryBsType=$this->db->query("select coname from conductype where academicyear='$max_year' and congrade='$grade' ");
							$output.='<option></option>';
							foreach ($queryBsType->result() as $bsType) {
								$output.='<option id="insertbsTypeGS" class="'.$bsName.'" name="'.$stuList->id.'" value="'.$bsType->coname.'">'.$bsType->coname.'</option>';
							}
							$output.='</select></td>';
						}
					}

	        	}
	        	$output.='</tr>';
	        	$no++;
			}
			$output.='</table></div></div>';
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
	function updateStudentBs($bsGradesec,$stuid,$quarter,$bsname,$max_year,$value,$data){
		$this->db->where('academicyear',$max_year);
		$this->db->where('stuid',$stuid);
		$this->db->where('quarter',$quarter);
		$this->db->where('bsname',$bsname);
		$query=$this->db->get('basicskillvalue'.$bsGradesec.$max_year);
		$output='';
		if($query->num_rows() > 0){
			$this->db->where('academicyear',$max_year);
			$this->db->where('stuid',$stuid);
			$this->db->where('quarter',$quarter);
			$this->db->where('bsname',$bsname);
			$this->db->set('value',$value);
			$queryy=$this->db->update('basicskillvalue'.$bsGradesec.$max_year);
		}else{
			$queryy=$this->db->insert('basicskillvalue'.$bsGradesec.$max_year,$data);
		}
		if($queryy){
			$output.='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Saved Successfully.
        	</div></div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> ooops, Please try again.
        	</div></div>';
		} 
		return $output;
	}
	function fecthStudentResultComments($branches,$gradesec,$quarter,$max_year){
		$queryfetchBS=$this->db->query("select u.id,u.fname,u.mname, u.lname, u.username, u.gradesec,u.grade from users as u where u.academicyear='$max_year' and u.usertype='Student' and u.status='Active' and u.isapproved='1' and u.gradesec='$gradesec' and u.branch='$branches' group by u.id  order by u.fname,u.mname,u.lname ASC ");
		$output='';
		if($queryfetchBS->num_rows()>0){
			$output.='<div class="card">
	        <div class="card-header">
	            <h4>Student Result Values</h4>
	        </div>
			<div class="table-responsive">
	        <table class="tabler table-borderedr table-hover" style="width:100%;">
	            <thead>
	                <tr>
	                    <th>No.</th>
	                    <th>Student Name</th>
	                    <th>Student ID</th>
	                    <th>Grade</th>';
	                $output.='</tr>
	            </thead>';
	        $no=1;
	        $output.='<input type="hidden" id="resultCommentQuarter" value="'.$quarter.'" >';
	        $output.='<input type="hidden" id="resultCommentGradesec" value="'.$gradesec.'" >';
	        $output.='<input type="hidden" id="resultCommentBranch" value="'.$branches.'" >';
			foreach ($queryfetchBS->result() as $stuList) {
				$stuid=$stuList->id;
				$grade=$stuList->grade;
				$queryBS=$this->db->query("select bs.resultcomment from manualreportcardcomments as bs where bs.stuid='$stuid' and bs.academicyear='$max_year' and bs.quarter='$quarter' group by bs.resultcomment order by bs.resultcomment ASC ");
				$output.='<input type="hidden" id="bsResultCommentStuId" value="'.$stuid.'" >';
				$output.='<tr> <td>'.$no.'</td>';
				$output.='<td>'.$stuList->fname.' '.$stuList->mname.'</td>';
				$output.='<td>'.$stuList->username.'</td>';
				$output.='<td>'.$stuList->gradesec.'</td>';
				$output.='<td><select class="form-control insertResultCommentTypeo" name="insertResultCommentType" >';
				foreach ($queryBS->result() as $bsName1) {
					$bsName=$bsName1->resultcomment;
					$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="'.$bsName.'">'.$bsName.'</option>';
				}
				$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="Excellent Keep up the good work">Excellent Keep up the good work</option>';
				$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="He/She is the good model for the class">He/She is the good model for the class</option>';
				$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="Great Achievement! Congratulations">Great Achievement! Congratulations</option>';
				$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="Keep it up Congratulations">Keep it up Congratulations</option>';
				$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="He/She is an asset to the class">He/She is an asset to the class</option>';

				$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="He/She has a potential for better result">He/She has a potential for better result</option>';
				$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="He/She can do more than this">He/She can do more than this</option>';
				$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="Please encourage him/her for better performance">Please encourage him/her for better performance</option>';
				$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="Please give extra help at home">Please give extra help at home</option>';
				$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="Please give more attention at home">Please give more attention at home</option>';
				$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="Help from home is needed">Help from home is needed</option>';
				$output.='<option id="insertCommentTypeGS" class="" name="'.$stuid.'" value="He/she needs a constant focus from family">He/she needs a constant focus from family</option>';
				$output.='</select></td>';
	        	$output.='</tr>';
	        	$no++;
			}
			$output.='</table></div></div>';
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
	function updatestudentresultcomment($bsGradesec,$stuid,$quarter,$max_year,$value,$data){
		$this->db->where('academicyear',$max_year);
		$this->db->where('stuid',$stuid);
		$this->db->where('quarter',$quarter);
		$query=$this->db->get('manualreportcardcomments');
		$output='';
		if($query->num_rows() > 0){
			$this->db->where('academicyear',$max_year);
			$this->db->where('stuid',$stuid);
			$this->db->where('quarter',$quarter);
			$this->db->set('resultcomment',$value);
			$queryy=$this->db->update('manualreportcardcomments');
		}else{
			$queryy=$this->db->insert('manualreportcardcomments',$data);
		}
		if($queryy){
			$output.='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> Saved Successfully.
        	</div></div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> ooops, Please try again.
        	</div></div>';
		} 
		return $output;
	}
	function fetchNoGradesForOrderSubject($max_year){
		$queryStudent=$this->db->query("select grade from users where academicyear='$max_year' and usertype='Student' and grade!='' group by grade ");
		$output='';
		if($queryStudent->num_rows()>0){
			$no=1;
			$output.='<div class="row">';
			foreach ($queryStudent->result() as $gradeValue) {
				$grade=$gradeValue->grade;
				$output.='<div class="col-12 col-md-3 col-lg-3">
                    <div id="accordion">
                      <div class="accordion">
                        <div class="accordion-header" role="button" data-toggle="collapse" data-target="#no'.$no.'"
                          aria-expanded="true">
                          <h4>Grade '.$gradeValue->grade.'</h4>
                        </div>
                        <div class="accordion-body collapse" id="no'.$no.'" data-parent="#accordion">';
                          $querySubject=$this->db->query("select suborder,Grade, Subj_name,Subj_Id from subject where Academic_Year='$max_year' and Grade='$grade' order by suborder ASC");
                          if($querySubject->num_rows()>0){
                          	$subNo=1;
                          	$output.='<table class="table table-borderedr table-hover">
				                <tr>
				                    <th>No.</th>
				                    <th>Subject Name</th>
				                    <th>Subject Order</th>
				                </tr><tbody class="row_position"> ';
				            $countSubject=$querySubject->num_rows();
                          	foreach ($querySubject->result() as $SubjeValue) {
                          		$output.='<tr id="'.$SubjeValue->Subj_Id.'">
                          		<td>'.$subNo.'.</td>';
                          		$output.='<td>'.$SubjeValue->Subj_name.'</td>';
                          		$output.='<td><select class="form-control selectSubOrder"> ';
                          		$currOrder=intval($SubjeValue->suborder);
                          		if($currOrder='' || $currOrder >$countSubject){
                          			$output.='<option></option>';
                          			for($i=1;$i<=$countSubject;$i++){
                          				$output.='<option id="selectSubOrder" class="'.$SubjeValue->Subj_name.'" value="'.$i.'" name="'.$SubjeValue->Grade.'">'.$i.'</option>';
                          			}
                          		}else{
                          			$output.='<option id="selectSubOrder" class="'.$SubjeValue->Subj_name.'" value="'.$currOrder.'" name="'.$SubjeValue->Grade.'">'.$SubjeValue->suborder.'</option>';
                          			for($i=1;$i<=$countSubject;$i++){
                          				if($i===$currOrder){
                          					$output.='<option></option>';
                          				}else{
                          					$output.='<option id="selectSubOrder" class="'.$SubjeValue->Subj_name.'" value="'.$i.'" name="'.$SubjeValue->Grade.'">'.$i.'</option>';
                          				}
	                          		}
                          		}
                          		$output.='</select></td>';
                          		$subNo++;
                          	}
                          	$output.='</tbody></table>';
                          }else{
                          	$output .='<div class="alert alert-warning alert-dismissible show fade">
				                <div class="alert-body">
				                    <button class="close"  data-dismiss="alert">
				                        <span>&times;</span>
				                    </button>
				                <i class="fas fa-check-circle"> </i> No subject found.
				            </div></div>';
                          }
                        $output.='</div>
                      </div>
                    </div>
                  </div>';
                $no++;
              }
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No grade found.
            </div></div>';
		}
		return $output;
	}
	function fetchSummerTeacherPlacement($user,$max_year){
        $this->db->where(array('staff'=>$user));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->group_by('grade');
		$query=$this->db->get('summerstaffplacement');
		return $query->result();
        
	}
	function fetchSummerSubjectForDirector($gradesec,$max_year){
		$this->db->where('summerstudent.gradesec',$gradesec);
		$this->db->where(array('summersubject.Academic_Year'=>$max_year));
		$this->db->order_by('summersubject.Subj_name','ASC');
		$this->db->group_by('summersubject.Subj_name');
		$this->db->select('*');
		$this->db->from('summersubject');
		$this->db->join('summerstudent',
		'summerstudent.grade = summersubject.Grade');
		$query=$this->db->get();
		$output ='';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->Subj_name.'">'.$row->Subj_name.'</option>';
		}
		return $output;
	}
	function fetchSummerSubjectForTeacher($gradesec,$max_year,$user){
		$this->db->where('grade',$gradesec);
		$this->db->where('staff',$user);
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->order_by('grade','ASC');
		$this->db->group_by('subject');
		$query=$this->db->get('summerstaffplacement');
		$output ='<option> </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->subject.'">'.$row->subject.'</option>';
			}
			return $output;
	}
	function update_reportcardResult($max_year,$gradesec,$branch){
	    $this->db->where('us.gradesec',$gradesec);
	    $this->db->where('us.academicyear',$max_year);
	    $this->db->where('us.status','Active');
	    $this->db->where('us.isapproved','1');
	    $this->db->where('us.branch',$branch);
	    $this->db->where('su.Academic_Year',$max_year);
	    $this->db->group_by('us.id,su.Subj_name');
	    $this->db->select('us.grade, us.id, us.gradesec, su.Subj_name, su.Merged_percent, su.Merged_name, su.suborder, su.letter, su.onreportcard');
	    $this->db->from('summerstudent as us');
	    $this->db->join('summersubject as su', 
	            'us.grade = su.Grade');
	    $querySubject = $this->db->get();
	    if($querySubject->num_rows()>0){
	      $total=0;$average=0;$average1=0;
	      $queyDelete=$this->db->query("delete from summerreportcard where rpbranch ='$branch' and grade='$gradesec' ");
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
	          $this->db->where('ev.grade',$grade);
	          $this->db->where('ma.academicyear',$max_year);
	          $this->db->where('ma.stuid',$stuid);
	          $this->db->where('ma.subname',$subject);
	          $this->db->group_by('ev.eid,ma.stuid,ma.subname');
	          $this->db->select('ma.stuid, ma.subname, ma.quarter,sum(ma.value) as total,sum(ma.outof) as outof,ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent');
	          $this->db->from('summerevaluation as ev');
	          $this->db->join('summermark as ma', 
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
	        $query_insert1=$this->db->insert_batch('summerreportcard',$data);
	      }
	        $queryMergedSubject=$this->db->query("select us.grade, us.id, us.gradesec, su.Subj_name, su.Merged_percent, su.Merged_name, su.suborder, su.letter, su.onreportcard from summersubject as su cross join summerstudent as us where us.grade=su.Grade and us.gradesec='$gradesec' and us.status='Active' and us.isapproved='1' and us.academicyear='$max_year' and su.Academic_Year='$max_year' and us.branch='$branch' and Merged_name!='' ");
	        if($queryMergedSubject->num_rows()>0){
	        $sqlDelete=$this->db->query("select *, sum(total) as mergedTot from summerreportcard where mergedname!='' and rpbranch='$branch' and grade='$gradesec' group by mergedname,stuid ");
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
	          $query_insert1=$this->db->insert_batch('summerreportcard',$data1);
	        }
	      }
	    if($query_insert1){
	      return true;
	    }
	}
	function reportcardByQuarter($max_year,$gradesec,$branch){
	   $query_student=$this->db->query(" Select * from summerstudent where gradesec='$gradesec' and usertype='Student' and branch='$branch' and isapproved='1' and status='Active' and academicyear='$max_year' order by fname ASC ");
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
	          <div class="col-lg-6">
	          <h5><b> Name : '.$row_student->fname.' '.$row_student->mname.' '.$row_student->lname.'</b></h5>
	          </div>
	          <div class="col-lg-6">
	          <h5><b>  Grade : '.$row_student->gradesec.'</b></h5>
	          </div>
	          </div>
	          </div>
	          <div class="col-lg-4">
	          <div class="text-center">
	          <span class="time" style="font-family:Viner Hand ITC">
	          <h2>Summer Report Card</h2>
	          </span>
	          <span class="text-muted" style="font-family:Poor Richard">Phone: '.$phone.'<br>Email: '.$email.'</span>
	          </div>  
	          </div>
	        </div>';
	      	$output.= '<div class="row"><div class="col-lg-6">
	        <div class="table-responsive">
	        <table width="100%"  class="table-bordered table-md" cellspacing="5" cellpadding="5">';
	      	$output.='<tr><th>Subject</th>';
	      	$output .='<th>Summer Class</th>';
	      	$output.='</tr>';
	        $query_result=$this->db->query(" Select * from summerreportcard where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and onreportcard='1' group by subject order by subject ASC ");
	        foreach ($query_result->result() as $qvalue_result) 
	        {
	          	$subject=$qvalue_result->subject;
	            $output .='<tr><td>'.$qvalue_result->subject.'</td>';
	          	
              	$query_qua_result=$this->db->query(" Select * from summerreportcard where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and subject='$subject' and onreportcard='1' group by subject order by subject ASC ");
              	if($query_qua_result->num_rows()>0)
              	{
            		foreach ($query_qua_result->result() as $quvalue)
            		{
	                  	$letter=$quvalue->letter;
	                  	$result=$quvalue->total;
	                  	if($letter!='A') {
	                    	$output .='<td class="text-center">'.$result.'</td>';
	                  	} else{
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
	        	$output .='</tr>'; 
	        }
	        /*Each Quarter total Starts(Horizontally)*/
	        
            $output .='<tr><td><b>Total</b></td>';
          	$query_qua_total=$this->db->query(" Select sum(total) as quarter_total from summerreportcard where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and onreportcard='1' group by quarter order by subject ASC ");
          	if($query_qua_total->num_rows()>0) {
            	foreach ($query_qua_total->result() as $qtvalue){
              		$output .= '<td class="text-center"><B>'.number_format((float)$qtvalue->quarter_total,2,'.','').'</B></td>';
            	}
          	} else{
            	$output .='<td class="text-center">-</td>';
          	}		
            $output .='</tr>';
            $output .='<tr><td><b>Average</b></td>';
        	$query_qua_total=$this->db->query(" Select sum(total) as quarter_total from summerreportcard where grade='$gradesec' and stuid='$stuid' and academicyear='$max_year' and letter!='A' and onreportcard='1' group by quarter order by subject ASC ");
          	/*count subject starts*/
          	$count_subject=$this->db->query("select * from summerreportcard where grade='$gradesec' and letter!='A' and onreportcard='1' and academicyear='$max_year'  group by subject ");
      		$total_subject=$count_subject->num_rows();
      		if($query_qua_total->num_rows()>0) {
            	foreach ($query_qua_total->result() as $qtvalue) {
              		$output .= '<td class="text-center"><B>'.number_format((float)$qtvalue->quarter_total/$total_subject,2,'.','').'</B></td>';
            	}
          	}else{
            	$output .='<td class="text-center">-</td>';
          	}
          	
            $output .='</tr>';

          	/*Each Quarter No of students starts(Vertically)*/
          	$output .='<tr><td><b>No. of Student</b></td>';
        	$query_total=$this->db->query("select sum(total) as total from summerreportcard where academicyear='$max_year' and stuid ='$stuid' group by quarter ");
        	if($query_total->num_rows()>0) {
      			$total_student=$query_student->num_rows();
      			$output .= '<td class="text-center"><B>'.$total_student.'</B></td>';
    		} else{
      			$output .= '<td class="text-center">-</td>';
    		}
	        $output .='</tr>';
	        $output .='<tr><td><b>Conduct</b></td>';
	        $output .= '<td class="text-center">-</td>';
	        $output .='</tr>';
	        $output .='<tr><td><b>No. of Absence</b></td><td></td>';
	        $output .='</tr>';
	      	$output .='</table></div></div>';

	      	$output.= '<div class="col-lg-6">';
	      	$output.="<h5 id='ENS'><u>HOMEROOM TEACHER'S COMMENTS AND RECOMMENDATIONS</u></h5>";
	      	$output.="<h5 id='ENS'>የትምህርት ክፍል መምህር አስተያየት<br>Home Room Teacher's Remark</h5>";
	      	$output.='____________________________________________________________________ ____________________________________________________________________ ____________________________________________________________________';

	      $output.="<br>የክፍሉ መምህር ስም <br>Home Room Teacher's Name";
	      $output.='__________________________ Signature.____<br>';

	      $output.="የወላጅ ወይም የአሳዳጊ ፊርማ<br>Parent or Guardian Name";
	      $output.='__________________________ Signature.____<br>';
	    }
	    return $output;
	}
	function fetchMyCommBook($username,$max_year){
		$this->db->select('users.fname,users.mname,users.lname,users.username,communicationbook.id,communicationbook.quarter,communicationbook.comsubject,communicationbook.comnote,communicationbook.status,communicationbook.stuid,communicationbook.comgrade');
		$this->db->from('communicationbook');
		$this->db->join('users',
		'users.username=communicationbook.byteacher');
		$this->db->order_by('communicationbook.id','DESC');
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('communicationbook.approvecom','1');
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.stuid',$username);

		$query=$this->db->get();
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="row"> ';
			foreach($query->result() as $bookSent){
				$stuid=$bookSent->stuid;
				$id=$bookSent->id;
				$statusCheck=$bookSent->status;
				$output.='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
	                <div class="card">
	                	<div class="card-statistic-4 card-header">
	                  		<div class="align-items-center justify-content-between">
                    			<div class="card-content"> ';
                        			$output.='<h5 class="font-25 text-center"> <a href="#" class="btn btn-outline-primary"><i data-feather="home"></i>Teacher: '.$bookSent->fname.' '.$bookSent->mname.'  Grade: '.$bookSent->comgrade.' Subject: '.$bookSent->comsubject.'  </a></h5>
								</div>';
                      			$output.=' <p> <h4 class="vew-mail-header">'.$bookSent->comnote.'</h4></p>';
                      			$queryReply=$this->db->query("select * from combookreplaystudent where replyid='$id' ");
                      			if($queryReply->num_rows()>0){
                      				foreach($queryReply->result() as $replyAnswer){
                      					$output.='<div class="activities">';
                      					if($replyAnswer->seenstatus=='1'){
		                      				$output.='<div class="activity">
							                    <div class="activity-icon bg-primary text-white">
							                      You
							                    </div>
							                    <div class="activity-detail">
							                      <p><span class="text-job"> '.$replyAnswer->replytext.' </span></p>
							                      <small class="text-muted"><i class="fas fa-clock"></i> '.$replyAnswer->datereplay.' <span class="text-success"> <i class="fa fa-check-double"></i> seen</span> </small>
							                    </div>
							                </div> ';
							            }else{
						            		$output.='<div class="activity">
							                    <div class="activity-icon bg-primary text-white">
							                      You
							                    </div>
							                    <div class="activity-detail">
							                      <p><span class="text-job"> '.$replyAnswer->replytext.' </span></p>
							                      <small class="text-muted"><i class="fas fa-clock"></i> '.$replyAnswer->datereplay.'  </small>
							                    </div>
							                </div>';
							            }
							            $output.='</div> ';
	                      			}
                      			}
                      			$output.='<div id="replyedTextHere'.$id.'"> </div>
	                  		</div>
	                  		<a class="btn btn-default pull-right" data-toggle="collapse" href="#collapseExample'.$id.'" role="button" aria-expanded="false" aria-controls="collapseExample"> <i class="fas fa-reply"></i>Reply</a>
	                	</div>
	                	<div class="collapse" id="collapseExample'.$id.'">
                      		<div class="card-header">
                      			<div class="chat-box">
							        <div class="card-footer chat-form">
						            	<input type="text" name="replayComText" class="form-control replayComText" id="replayComText'.$id.'" placeholder="Type a reply for '.$bookSent->fname.' '.$bookSent->mname.'..." >
						            	<button class="btn btn-info sendMyReply" value="'.$id.'"> <i class="far fa-paper-plane"></i> </button>
							        </div>
							    </div>
                      		</div>
                    	</div>
	              	</div>
	            </div> ';	
			}
			$output.='</div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No new record found.
            </div></div>';
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
	function fetch_unseen_comBook_notification($user,$max_year,$max_quarter){
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
	function fetchcomBooknotification($user){
		$this->db->order_by('id','DESC');
		$this->db->select('*');
        $this->db->from('communicationbook');
        /*$this->db->where('status','0');*/
        $this->db->where('approvecom','1');
        $this->db->where('stuid',$user);
        $query = $this->db->get();
        $output='';
        foreach ($query->result() as $row) {
        	# code...
        	$output .='<a href="#" class="dropdown-item"> 
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
        return $output;
	}
	function fetchCommunicationBookAdmin($user,$branch_teacher,$max_year,$max_quarter){
		$this->db->select('users.fname,users.mname,users.lname,users.username,communicationbook.id,communicationbook.quarter,communicationbook.comsubject,communicationbook.comnote,communicationbook.status,communicationbook.stuid,communicationbook.comgrade');
		$this->db->from('communicationbook');
		$this->db->join('users',
		'users.username=communicationbook.stuid');
		$this->db->order_by('communicationbook.id','DESC');
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('users.branch',$branch_teacher);
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.quarter',$max_quarter);
		$this->db->where('communicationbook.approvecom','1');
		$query=$this->db->get();
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
	        <table class="table table-striped table-hover" style="width:100%;">
	            <thead>
	                <tr>
	                    <th>No.</th>
	                    <th>Student Name</th>
	                    <th>Grade</th>
	                    <th>Subject</th>
	                    <th>Comment Book</th> 
	                </tr>
	            </thead>';
	        $no=1;
			foreach($query->result() as $bookSent){
				$stuid=$bookSent->stuid;
				$id=$bookSent->id;
				$statusCheck=$bookSent->status;
				$output.='<tr> <td>'.$no.'.</td>';
				if($statusCheck == '1'){
					$output.='<td>'.$bookSent->fname.' '.$bookSent->mname.' <span class="text-dark time"><i class="fas fa-check-double"></i></span></td>';
				}else{
					$output.='<td>'.$bookSent->fname.' '.$bookSent->mname.'</td>';
				}
				$output.='<td>'.$bookSent->comgrade.' </td>';
				$output.='<td>'.$bookSent->comsubject.' </td>';
				if($bookSent->comcommented==1){
					$output.='<td id="ENS">'.substr($bookSent->comnote,0,60).' <i class="fas fa-check-circle text-dark"> Edited</i> 
					<button class="btn btn-default viewComBook" name="viewComBook" type="submit" id="'.$id.'"> <a href="#" class="" value="" data-toggle="modal" data-target="#viewComBookNow"> <span class="text-warning">See More</span></a> </button></td>';
					$output.='</tr>';
				}else{
					$output.='<td id="ENS">'.substr($bookSent->comnote,0,60).' 
					<button class="btn btn-default viewComBook" name="viewComBook" type="submit" id="'.$id.'"> <a href="#" class="" value="" data-toggle="modal" data-target="#viewComBookNow"> <span class="text-warning">See More</span></a> </button></td>';
					$output.='</tr>';
				}
				$no++;
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
	function fetchCommunicationBooksuAdmin($user,$max_year,$max_quarter){
		$this->db->select('users.fname,users.mname,users.lname,users.username,communicationbook.id,communicationbook.quarter,communicationbook.comsubject,communicationbook.comnote,communicationbook.status,communicationbook.stuid,communicationbook.comgrade, communicationbook.comcommented, communicationbook.datecreated,communicationbook.byteacher');
		$this->db->from('communicationbook');
		$this->db->join('users',
		'users.username=communicationbook.stuid');
		$this->db->order_by('communicationbook.id','DESC');
		$this->db->where('users.academicyear',$max_year);
		$this->db->where('communicationbook.academicyear',$max_year);
		$this->db->where('communicationbook.quarter',$max_quarter);
		$this->db->where('communicationbook.approvecom','1');
		$query=$this->db->get();
		$output='';
		if($query->num_rows()>0){
			foreach($query->result() as $bookSent){
				$stuid=$bookSent->stuid;
				$id=$bookSent->id;
				$statusCheck=$bookSent->status;
				$output.='<div class="activities">
          		<div class="activity">
		            <div class="activity-icon bg-primary text-white">
		              '.$bookSent->byteacher.'
		            </div>
            		<div class="activity-detail">
            			<div class="mb-2"> ';
              				if($statusCheck == '1'){
								$output.='<a class="text-job" href="#">'.$bookSent->fname.' '.$bookSent->mname.' <i class="fas fa-check-circle text-dark"> Seen</i></a>';
							}else{
								$output.='<a class="text-job" href="#">'.$bookSent->fname.' '.$bookSent->mname.'</a>';
							}
              				$output.='('.$bookSent->comgrade.') Subject: '.$bookSent->comsubject.' <span class="text-muted">'.$bookSent->datecreated.'</span> </div>';
              				$queryHasReplay=$this->db->query("select * from combookreplaystudent where replyid='$id' ");
              				if($queryHasReplay->num_rows()>0){
	              				if($bookSent->comcommented==1){
									$output.=''.substr($bookSent->comnote,0,100).'... <i class="fas fa-check-circle text-dark"> Edited</i>
									<button class="btn btn-default viewComBook" name="viewComBook" type="submit" id="'.$id.'"> <a href="#" class="" value="" data-toggle="modal" data-target="#viewComBookNow"> <span class="text-warning">Read More</span></a> </button>';
									foreach($queryHasReplay->result() as $replyAnswer){
										$output.='<div class="activity">
			                    			<div class="activity-icon bg-primary text-white"> '.$bookSent->fname.' 
			                    			</div>
			                    			<div class="activity-detail">
			                      				<p><span class="text-job"> '.$replyAnswer->replytext.' </span></p>
			                      				<small class="text-muted"><i class="fas fa-clock"></i> '.$replyAnswer->datereplay.'  </small>
			                    			</div>
		                				</div>';
									}
								}else{
									$output.=''.substr($bookSent->comnote,0,100).'... 
									<button class="btn btn-default viewComBook" name="viewComBook" type="submit" id="'.$id.'"> <a href="#" class="" value="" data-toggle="modal" data-target="#viewComBookNow"> <span class="text-warning">Read More</span></a> </button>';
									foreach($queryHasReplay->result() as $replyAnswer){
										$output.='<div class="activity">
						                    <div class="activity-icon bg-primary text-white">
						                      '.$bookSent->fname.'
						                    </div>
						                    <div class="activity-detail">
						                      <p><span class="text-job"> '.$replyAnswer->replytext.' </span></p>
						                      <small class="text-muted"><i class="fas fa-clock"></i> '.$replyAnswer->datereplay.'  </small>
						                    </div>
					                	</div>';
									}
								}
							}else{
								if($bookSent->comcommented==1){
									$output.=''.substr($bookSent->comnote,0,100).'... <i class="fas fa-check-circle text-dark"> Edited</i>
									<button class="btn btn-default viewComBook" name="viewComBook" type="submit" id="'.$id.'"> <a href="#" class="" value="" data-toggle="modal" data-target="#viewComBookNow"> <span class="text-warning">Read More</span></a> </button>';
								}else{
									$output.=''.substr($bookSent->comnote,0,100).'... 
									<button class="btn btn-default viewComBook" name="viewComBook" type="submit" id="'.$id.'"> <a href="#" class="" value="" data-toggle="modal" data-target="#viewComBookNow"> <span class="text-warning">Read More</span></a> </button>';
								}
							}
              			$output.='</div>
            		</div>
          		</div> </div> ';
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
	function fetchUserAction($max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->order_by('id','DESC');
		$query=$this->db->get('useractions');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
	        <table class="table table-striped table-hover" style="width:100%;">
	            <thead>
	                <tr>
	                    <th>No.</th>
	                    <th>Action</th> 
	                    <th>Branch</th> 
	                    <th>Grade</th>
	                    <th>Subject</th>
	                    <th>Quarter</th>
	                    <th>Target User</th> 
	                    <th>Old Data</th>
	                    <th>New Data</th>
	                    <th>Mark Name</th>
	                    <th>Action By</th>
	                    <th>Academic Year</th>
	                </tr>
	            </thead>';
	        $no=1;
			foreach($query->result() as $fetchActions ){
				$targetUser=$fetchActions->updateduser;
				$queryUser=$this->db->query("select fname,mname,lname from users where id='$targetUser' ");
				$output.='<tr><td>'.$no.' </td>';
				if($targetUser!='-' && $queryUser->num_rows()>0){
					
					$targetRow=$queryUser->row_array();
					$fName=$targetRow['fname'];
					$mName=$targetRow['mname'];
					$lName=$targetRow['lname'];
					$output.='<td>'.$fetchActions->useraction.' </td>';
					$output.='<td>'.$fetchActions->userbranch.' </td>';
					$output.='<td>'.$fetchActions->infograde.' </td>';
					$output.='<td>'.$fetchActions->subject.' </td>';				
					$output.='<td>'.$fetchActions->quarter.' </td>';
					$output.='<td>'.$fName.' '.$mName.' '.$lName.' </td>';
					$output.='<td>'.$fetchActions->oldata.' </td>';
					$output.='<td>'.$fetchActions->newdata.' </td>';
					$output.='<td>'.$fetchActions->markname.' </td>';
					$output.='<td>'.$fetchActions->userinfo.' </td>';
					$output.='<td>'.$fetchActions->academicyear.' </td>';
				}else{
					$output.='<td>'.$fetchActions->useraction.' </td>';
					$output.='<td>'.$fetchActions->userbranch.' </td>';
					$output.='<td>'.$fetchActions->infograde.' </td>';
					$output.='<td>'.$fetchActions->subject.' </td>';				
					$output.='<td>'.$fetchActions->quarter.' </td>';
					$output.='<td>'.$fetchActions->updateduser.' </td>';
					$output.='<td>'.$fetchActions->oldata.' </td>';
					$output.='<td>'.$fetchActions->newdata.' </td>';
					$output.='<td>'.$fetchActions->markname.' </td>';
					$output.='<td>'.$fetchActions->userinfo.' </td>';
					$output.='<td>'.$fetchActions->academicyear.' </td>';
				}
				$output.='</tr>';
				$no++;
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
	function searchStudentsToTransportServiceNotAccess($searchItem,$branch,$max_year){
		$this->db->order_by('fname','ASC');
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('branch'=>$branch));
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
		$this->db->or_like('grade', $searchItem);
		$query=$this->db->get('users');
		$output ='';
		if($query->num_rows()>0){
	        $no=1;
			foreach ($query ->result() as $value) {
				$id=$value->id;
				$output .=' '.$no.'.<button class="btn btn-default saveThisStudentToGroupEdit" id="saveThisStudentToGroupEdit" value="'.$value->username.'">'.$value->username.' ('.$value->fname.' '.$value->mname.') </button><br>';
            	$no++;
			}
		}
		return $output;
	}
	function searchStudentsToTransportService($searchItem,$max_year){
		$this->db->order_by('fname','ASC');
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->like('username',$searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->or_like('fname', $searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->or_like('grade', $searchItem);
		$query=$this->db->get('users');
		$output ='';
		if($query->num_rows()>0){
	        $no=1;
			foreach ($query ->result() as $value) {
				$id=$value->id;
				$output .=' '.$no.'.<button class="btn btn-default saveThisStudentToGroupEdit" id="saveThisStudentToGroupEdit" value="'.$value->username.'">'.$value->username.' ('.$value->fname.' '.$value->mname.') </button><br>';
            	$no++;
			}
		}
		return $output;
	}
	function searchStudentsToTransportServiceBranch($searchItem,$max_year,$mybranch){
		$this->db->order_by('fname','ASC');
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('branch'=>$mybranch));
		$this->db->like('username',$searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('branch'=>$mybranch));
		$this->db->or_like('fname', $searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('branch'=>$mybranch));
		$this->db->or_like('grade', $searchItem);
		$query=$this->db->get('users');
		$output ='';
		if($query->num_rows()>0){
	        $no=1;
			foreach ($query ->result() as $value) {
				$id=$value->id;
				$output .=' '.$no.'.<button class="btn btn-default saveThisStudentToGroupEdit" id="saveThisStudentToGroupEdit" value="'.$value->username.'">'.$value->username.' ('.$value->fname.' '.$value->mname.') </button><br>';
            	$no++;
			}
		}
		return $output;
	}
	function fetchCustomText(){
		$query=$this->db->query("select fname,mname,ct.id,ct.datecreated,ct.comtext from customcomtext as ct cross join users as us where us.username=ct.createdby order by id DESC ");
		$output='';
		if($query->num_rows()>0){
			foreach ($query->result() as $fetchText) {
				$output.='<div class="activities">
                  <div class="activity deleteCustomText'.$fetchText->id.'">
                    <div class="activity-icon bg-primary text-white">
                      '.$fetchText->fname.' '.$fetchText->mname.'
                    </div>
                    <div class="activity-detail">
                      <div class="mb-2">
                        <span class="text-job text-primary">'.$fetchText->datecreated.'</span>
                        <div class="float-right dropdown">
                          <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
                          <div class="dropdown-menu">
                            
                            <a href="#" class="dropdown-item has-icon text-danger deleteCustomText" name="'.$fetchText->id.'" value="'.$fetchText->id.'" id="'.$fetchText->id.'"><i class="fas fa-trash-alt"></i> Delete</a>
                          </div>
                        </div>
                      </div>
                      <p>'.$fetchText->comtext.'</p>
                    </div>
                  </div>
                </div>';
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
	function fetchCustomGroup($max_year,$max_quarter){
		$query=$this->db->query("select fname,mname,ct.quarter,ct.perweight,ct.tid,ct.datecreated,ct.pername from teacherperfogroup as ct cross join users as us where us.username=ct.createdby and ct.academicyear='$max_year' and ct.quarter='$max_quarter' order by ct.tid DESC ");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive"> 
	            <table class="table table-stripped table-hover" style="width:100%;">
	            <thead><tr> 
	                <th>No.</th>
	                <th>Performance Group</th>
	                <th>Group Weight</th>
	                <th>Quarter</th>
	                <th>Date Created</th> <th>Created By</th></tr>
	            </thead>';
	        $no=1;
			foreach ($query->result() as $fetchText) {
				$output.='
				<tr class="deletePerformanceGroup'.$fetchText->tid.'">
	                <td>'.$no.'.</td>
	                <td>'.$fetchText->pername.'
	                    <div class="table-links">
	                        <a href="#" name="'.$fetchText->tid.'" value="'.$fetchText->tid.'" class="deletePerformanceGroup text-danger" id="'.$fetchText->tid.'">Delete</a>
	                    </div>
	                </td>
	                <td>'.$fetchText->perweight.'</td>
	                <td>'.$fetchText->quarter.'</td>
	                <td>'.$fetchText->datecreated.'</td>
	                <td>'.$fetchText->fname.' '.$fetchText->mname.'</td>
	            </tr>';
			    $no++;
			}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No group record found.
            </div></div>';
		}
		return $output;
	}
	function fetchPerformanceGroup($max_year){
		$this->db->where('academicyear',$max_year);
		$this->db->order_by('tid','ASC');
		$this->db->group_by('tid');
		$query=$this->db->get('teacherperfogroup');
		return $query->result();
	}
	function fetchCustomActivity($max_year,$max_quarter){
		$query=$this->db->query("select fname,mname,ct.pergroup,ct.quarter,ct.aid,ct.datecreated,ct.acname , tg.pername from teacherperactivity as ct cross join users as us  cross join teacherperfogroup as tg where tg.tid=ct.pergroup and us.username=ct.createdby and ct.academicyear='$max_year' and ct.quarter='$max_quarter' order by ct.aid DESC ");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive"> 
	            <table class="table table-stripped table-hover" style="width:100%;">
	            <thead><tr> 
	                <th>No.</th>
	                <th>Performance Activity</th>
	                <th>Group Name</th>
	                <th>Quarter</th>
	                <th>Date Created</th> <th>Created By</th></tr>
	            </thead>';
	        $no=1;
			foreach ($query->result() as $fetchText) {
				$output.='
				<tr class="deletePerformanceActivity'.$fetchText->aid.'">
	                <td>'.$no.'.</td>
	                <td>'.$fetchText->acname.'
	                    <div class="table-links">
	                        <a href="#" name="'.$fetchText->aid.'" value="'.$fetchText->aid.'" class="deletePerformanceActivity text-danger" id="'.$fetchText->aid.'">Delete</a>
	                    </div>
	                </td>
	                <td>'.$fetchText->pername.'</td>
	                <td>'.$fetchText->quarter.'</td>
	                <td>'.$fetchText->datecreated.'</td>
	                <td>'.$fetchText->fname.' '.$fetchText->mname.'</td>
	            </tr>';
			    $no++;
			}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No activity record found.
            </div></div>';
		}
		return $output;
	}
	function fetchStaffToPerformActivity($max_year,$branches,$quarter){
		$queryfetchBS=$this->db->query("select u.id,u.fname,u.mname, u.lname, u.username, u.gradesec,u.grade from users as u where u.usertype='Teacher' and u.status='Active' and u.isapproved='1' and u.branch='$branches' group by u.id  order by u.fname,u.mname,u.lname ASC ");
		$output='';
		if($queryfetchBS->num_rows()>0){
			$queryBS=$this->db->query("select acname,aid from teacherperactivity where academicyear='$max_year' and quarter='$quarter' group by acname order by acname ASC ");
			$output.='<div class="card">
	        <div class="card-header">
	            <h4>Teacher Performance Activity</h4>
	        </div>
			<div class="table-responsive">
	        <table class="tabler table-borderedr table-hover" style="width:100%;">
	            <thead>
	                <tr>
	                    <th>No.</th>
	                    <th>Teacher Name</th> ';
	                    foreach ($queryBS->result() as $bsName) {
	                    	$output.='<th>'.$bsName->acname.'</th>';
	                    }
	                $output.='</tr>
	            </thead>';
	        $no=1;
	        $output.='<input type="hidden" id="activityQuarter" value="'.$quarter.'" >';
			foreach ($queryfetchBS->result() as $stuList) {
				$teid=$stuList->id;
				$grade=$stuList->grade;
				$output.='<input type="hidden" id="activityStuId" value="'.$teid.'" >';
				$output.='<tr> <td>'.$no.'</td>';
				$output.='<td>'.$stuList->fname.' '.$stuList->mname.' '.$stuList->lname.'</td>';
				foreach ($queryBS->result() as $bsName) {
					$aid=$bsName->aid;
					$queryCheck=$this->db->query("select acid,pervalue from teacherperfvalue where academicyear='$max_year' and quarter='$quarter' and acid='$aid' and teid='$teid' ");
					$output.='<td><select class="form-control insertTeacherPerformance">';
					if($queryCheck->num_rows()>0){
						$rowValue=$queryCheck->row_array();
						$teaValue=$rowValue['pervalue'];
						$output.='<option value="'.$teaValue.'" id="'.$teid.'" class="'.$aid.'">'.$teaValue.'</option>';
						for($i=0;$i<=4;$i++){
							$output.='<option value="'.$i.'" id="'.$teid.'" class="'.$aid.'">'.$i.'</option>';
						}
					}else{
						for($i=0;$i<=4;$i++){
							$output.='<option value="'.$i.'" id="'.$teid.'" class="'.$aid.'">'.$i.'</option>';
						}
					}
					$output.='</select></td>';
	        	}
	        	$output.='</tr>';
	        	$no++;
			}
			$output.='</table></div></div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
        	</div></div>';
		}
		return $output;
	}
	function fetchStaffToPerformActivityDirector($max_year,$branches,$quarter,$user){
		$queryfetchBS=$this->db->query("select u.id,u.fname,u.mname, u.lname, u.username, u.gradesec,u.grade from users as u cross join directorplacement as dp cross join staffplacement as st where st.academicyear='$max_year' and st.grade=dp.grade and dp.academicyear='$max_year' and dp.staff='$user' and u.usertype='Teacher' and u.status='Active' and u.isapproved='1' and u.branch='$branches' group by u.id  order by u.fname,u.mname,u.lname ASC ");
		$output='';
		if($queryfetchBS->num_rows()>0){
			$queryBS=$this->db->query("select acname,aid from teacherperactivity where academicyear='$max_year' and quarter='$quarter' group by acname order by acname ASC ");
			$output.='<div class="card">
	        <div class="card-header">
	            <h4>Teacher Performance Activity</h4>
	        </div>
			<div class="table-responsive">
	        <table class="tabler table-borderedr table-hover" style="width:100%;">
	            <thead>
	                <tr>
	                    <th>No.</th>
	                    <th>Teacher Name</th> ';
	                    foreach ($queryBS->result() as $bsName) {
	                    	$output.='<th>'.$bsName->acname.'</th>';
	                    }
	                $output.='</tr>
	            </thead>';
	        $no=1;
	        $output.='<input type="hidden" id="activityQuarter" value="'.$quarter.'" >';
			foreach ($queryfetchBS->result() as $stuList) {
				$teid=$stuList->id;
				$grade=$stuList->grade;
				$output.='<input type="hidden" id="activityStuId" value="'.$teid.'" >';
				$output.='<tr> <td>'.$no.'</td>';
				$output.='<td>'.$stuList->fname.' '.$stuList->mname.' '.$stuList->lname.'</td>';
				foreach ($queryBS->result() as $bsName) {
					$aid=$bsName->aid;
					$queryCheck=$this->db->query("select acid,pervalue from teacherperfvalue where academicyear='$max_year' and quarter='$quarter' and acid='$aid' and teid='$teid' ");
					$output.='<td><select class="form-control insertTeacherPerformance">';
					if($queryCheck->num_rows()>0){
						$rowValue=$queryCheck->row_array();
						$teaValue=$rowValue['pervalue'];
						$output.='<option value="'.$teaValue.'" id="'.$teid.'" class="'.$aid.'">'.$teaValue.'</option>';
						for($i=0;$i<=4;$i++){
							$output.='<option value="'.$i.'" id="'.$teid.'" class="'.$aid.'">'.$i.'</option>';
						}
					}else{
						for($i=0;$i<=4;$i++){
							$output.='<option value="'.$i.'" id="'.$teid.'" class="'.$aid.'">'.$i.'</option>';
						}
					}
					$output.='</select></td>';
	        	}
	        	$output.='</tr>';
	        	$no++;
			}
			$output.='</table></div></div>';
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
        	</div></div>';
		}
		return $output;
	}
	function fetchStaffPerformResult($max_year,$branches,$max_quarter){
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
		$output='';
		$queryfetchTeacher=$this->db->query("select u.id,u.fname,u.mname, u.lname, u.username, u.gradesec,u.grade from users as u where u.usertype='Teacher' and u.status='Active' and u.isapproved='1' and u.branch='$branches' group by u.id  order by u.fname,u.mname,u.lname ASC ");
		if($queryfetchTeacher->num_rows()>0){
			$grandTotal=0;
			foreach($queryfetchTeacher->result() as $teachResult){
				$output.='<div class="row" style="width:100%;height:auto;page-break-inside:avoid;">';
				$staff=$teachResult->username;
				$teid=$teachResult->id;
				$output.='<div class="col-lg-12 col-12"><h5 class="header-title text-center">'.$school_name.'</h5>
				<div class="dropdown-divider"></div> ';
				$output.='<h6 class="header-title text-center">'.$school_slogan.'<br>
				<u>Teacher’s performance Evaluation (Summative)</u>
				</h6></div>';
				$output.='<div class="col-lg-8 col-6">Teacher’s name: '.$teachResult->fname.' '.$teachResult->mname.' '.$teachResult->lname.'</div>';
				$output.='<div class="col-lg-4 col-6">Date: '.date('M-d-Y').'</div>';
				$queryTeaches=$this->db->query("select staff,subject,grade from staffplacement where academicyear='$max_year' and staff='$staff' ");
				if($queryTeaches->num_rows()>0){
					$output.='<div class="col-lg-6 col-6">Grade he/she teaches: ';
					foreach($queryTeaches->result() as $heTeaches){
						$output.=''.$heTeaches->grade.' , ';
					}
					$output.='</div>';
					$output.='<div class="col-lg-6 col-6">Subject he/she teaches:';
					foreach($queryTeaches->result() as $heTeaches){
						$output.=''.$heTeaches->subject.' , ';
					}
					$output.='</div>';
				}
				$output.='<div class="col-lg-8 col-6">Quarter: '.$max_quarter.' </div>';
				$output.='<div class="col-lg-4 col-6">Semester: _________</div>';
				$output.='<div class="col-lg-12 col-12 table-responsive">
	        	<table class="tabler table-borderedr table-hover" style="width:100%;">
                <tr>
                    <th>No.</th>
                    <th class="text-center">Activities</th> 
                    <th class="text-center">Points</th> 
                </tr>';
                $queryGroup=$this->db->query("select ct.quarter,ct.perweight,ct.tid,ct.datecreated, ct.pername from teacherperfogroup as ct where ct.academicyear='$max_year' and ct.quarter='$max_quarter' order by ct.tid ASC ");
                if($queryGroup->num_rows()>0){
                	$noG=1;
                	$grandWeight=0;
                	foreach($queryGroup->result() as $groupName){
                		$aid=$groupName->tid;
                		$queryPerValue=$this->db->query("select aid,acname from teacherperactivity where academicyear='$max_year' and quarter='$max_quarter' and pergroup='$aid' ");
                		$totalActivity=$queryPerValue->num_rows() + 2;
                		$output.='<tr><td rowspan="'.$totalActivity.'">'.$noG.'.</td><td><b>'.$groupName->pername.'</b></td><td class="text-center">'.$groupName->perweight.'</td>';
                		
                		$sumTotal=0;              		
                		if($queryPerValue->num_rows()>0){
                			$noV=1;
                			foreach($queryPerValue->result() as $activityName){
                				$aid=$activityName->aid;
                				$output.='<tr><td>'.$noV.'. '.$activityName->acname.'</td>';
                				$queryTeaValue=$this->db->query("select acid,pervalue from teacherperfvalue where academicyear='$max_year' and quarter='$max_quarter' and acid='$aid' and teid='$teid' ");
                				if($queryTeaValue->num_rows()>0){
                					foreach($queryTeaValue->result() as $teacValue){
                						$sumTotal=$sumTotal + $teacValue->pervalue;
                						$output.='<td class="text-center">'.$teacValue->pervalue.'</td>';
                					}
                				}else{
                					$output.='<td class="text-center">-</td>';
                				}
                				$output.='</tr>';
                				$noV++;
                			}
                		}
                		$grandTotal=$grandTotal + $sumTotal;           		
                		$output.='</tr>';
                		$output.='<td class="pull-right">Total</td>'; 
                		if($sumTotal > $groupName->perweight){
                			$output.='<td class="text-center"><span class="text-danger">OW</span></td>';
                		}else{
                			$output.='<td class="text-center"><b>'.$sumTotal.'/'.$groupName->perweight.' </b></td>';
                		}
                		$grandWeight= $grandWeight + $groupName->perweight;
                		$noG++;
                	}
                }
	        	$output.='</table></div>';
	        	if($grandTotal>0){
	        		$output.='<div class="col-lg-6 col-6">TOTAL OUT OF <u>'.$grandTotal.'/'.$grandWeight.'</u></div>';
	        	}else{
	        		$output.='<div class="col-lg-6 col-6">TOTAL OUT OF ____</div>';
	        	}
	        	if($grandTotal>0){
	        		$convertedPercent=(100*$grandTotal)/$grandWeight;
	        		$output.='<div class="col-lg-6 col-6">CONVERTED TOTAL OUT OF 100% <u>'.number_format((float)$convertedPercent,2,'.','').'</u></div>';
	        	}else{
	        		$output.='<div class="col-lg-6 col-6">CONVERTED TOTAL OUT OF 100% ___</div>';
	        	}
	        	
	        	
	        	$output.='<div class="col-lg-6 col-12">Strength observed: _________________________________________________</div>';
	        	$output.='<div class="col-lg-6 col-12">Points to ponder: _________________________________________________</div>';
	        	$output.='<div class="col-lg-6 col-12">Teacher’s comment: _________________________________________________</div>';
	        	$output.='<div class="col-lg-6 col-12">Name and signature of the Principal: ____________________________________</div>';
	        	$output.='<div class="col-lg-6 col-12">Name and signature of the Teacher: ____________________________________</div>';
	        	$output.='<div class="col-lg-8 col-12">Name and signature of the dep’t Head /vice Director/Unit Leader: _______________</div>';
	        	$output.='<div class="col-lg-2 col-6">Date: <u>'.date('M-d-Y').'</u></div>';
	        	$output.='<div class="col-lg-2 col-6 text-center">SCHOOL SEAL</div>';
	        	$output.='<div class="col-lg-12 col-12 text-center StudentViewTextInfo"><u>Key</u> &nbsp;&nbsp; 0.Failure &nbsp; &nbsp;&nbsp; 1.Needs Improvement &nbsp;&nbsp;&nbsp; 2.Good &nbsp;&nbsp;&nbsp; 3.V .good &nbsp;&nbsp;&nbsp; 4.Excellent</div>';
	        	$output.='<div class="col-lg-12 col-12"><p>Note: This evaluation and observation check list can be amended by School management any time in any condition for mutual benefit.</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>Distribution .1.Orginal Copy for the principal</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>2. First Copy for the teacher</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>3. Second Copy for School Dean</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>4. Third copy for General Manager</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>5. Fourth copy for teacher’s file</p></div>';
	        	$output.='</div>';
			}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
        	</div></div>';
		}
		return $output;
	}
	function fetchStaffPerformResultDirector($max_year,$branches,$max_quarter,$user){
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
		$output='';
		$queryfetchTeacher=$this->db->query("select u.id,u.fname,u.mname, u.lname, u.username, u.gradesec,u.grade from users as u cross join directorplacement as dp cross join staffplacement as st where st.academicyear='$max_year' and st.grade=dp.grade and dp.academicyear='$max_year' and dp.staff='$user' and u.usertype='Teacher' and u.status='Active' and u.isapproved='1' and u.branch='$branches' group by u.id  order by u.fname,u.mname,u.lname ASC ");
		if($queryfetchTeacher->num_rows()>0){
			
			foreach($queryfetchTeacher->result() as $teachResult){
				$output.='<div class="row" style="width:100%;height:auto;page-break-inside:avoid;">';
				$staff=$teachResult->username;
				$teid=$teachResult->id;
				$output.='<div class="col-lg-12 col-12"><h5 class="header-title text-center">'.$school_name.'</h5>
				<div class="dropdown-divider"></div> ';
				$output.='<h6 class="header-title text-center">'.$school_slogan.'<br>
				<u>Teacher’s performance Evaluation (Summative)</u>
				</h6></div>';
				$output.='<div class="col-lg-8 col-6">Teacher’s name: '.$teachResult->fname.' '.$teachResult->mname.' '.$teachResult->lname.'</div>';
				$output.='<div class="col-lg-4 col-6">Date: '.date('M-d-Y').'</div>';
				$queryTeaches=$this->db->query("select staff,subject,grade from staffplacement where academicyear='$max_year' and staff='$staff' ");
				if($queryTeaches->num_rows()>0){
					$output.='<div class="col-lg-6 col-6">Grade he/she teaches: ';
					foreach($queryTeaches->result() as $heTeaches){
						$output.=''.$heTeaches->grade.' , ';
					}
					$output.='</div>';
					$output.='<div class="col-lg-6 col-6">Subject he/she teaches:';
					foreach($queryTeaches->result() as $heTeaches){
						$output.=''.$heTeaches->subject.' , ';
					}
					$output.='</div>';
				}
				$output.='<div class="col-lg-8 col-6">Quarter: '.$max_quarter.' </div>';
				$output.='<div class="col-lg-4 col-6">Semester: _________</div>';
				$output.='<div class="col-lg-12 col-12 table-responsive">
	        	<table class="tabler table-borderedr table-hover" style="width:100%;">
                <tr>
                    <th>No.</th>
                    <th class="text-center">Activities</th> 
                    <th class="text-center">Points</th> 
                </tr>';
                $queryGroup=$this->db->query("select ct.quarter,ct.perweight,ct.tid,ct.datecreated, ct.pername from teacherperfogroup as ct where ct.academicyear='$max_year' and ct.quarter='$max_quarter' order by ct.tid ASC ");
                if($queryGroup->num_rows()>0){
                	$noG=1;
                	$grandTotal=0;$grandWeight=0;
                	foreach($queryGroup->result() as $groupName){
                		$aid=$groupName->tid;
                		$queryPerValue=$this->db->query("select aid,acname from teacherperactivity where academicyear='$max_year' and quarter='$max_quarter' and pergroup='$aid' ");
                		$totalActivity=$queryPerValue->num_rows() + 2;
                		$output.='<tr><td rowspan="'.$totalActivity.'">'.$noG.'.</td><td><b>'.$groupName->pername.'</b></td><td class="text-center">'.$groupName->perweight.'</td>';
                		
                		$sumTotal=0;              		
                		if($queryPerValue->num_rows()>0){
                			$noV=1;
                			foreach($queryPerValue->result() as $activityName){
                				$aid=$activityName->aid;
                				$output.='<tr><td>'.$noV.'. '.$activityName->acname.'</td>';
                				$queryTeaValue=$this->db->query("select acid,pervalue from teacherperfvalue where academicyear='$max_year' and quarter='$max_quarter' and acid='$aid' and teid='$teid' ");
                				if($queryTeaValue->num_rows()>0){
                					foreach($queryTeaValue->result() as $teacValue){
                						$sumTotal=$sumTotal + $teacValue->pervalue;
                						$output.='<td class="text-center">'.$teacValue->pervalue.'</td>';
                					}
                				}else{
                					$output.='<td class="text-center">-</td>';
                				}
                				$output.='</tr>';
                				$noV++;
                			}
                		}
                		$grandTotal=$grandTotal + $sumTotal;           		
                		$output.='</tr>';
                		$output.='<td class="pull-right">Total</td>'; 
                		if($sumTotal > $groupName->perweight){
                			$output.='<td class="text-center"><span class="text-danger">OW</span></td>';
                		}else{
                			$output.='<td class="text-center"><b>'.$sumTotal.'/'.$groupName->perweight.' </b></td>';
                		}
                		$grandWeight= $grandWeight + $groupName->perweight;
                		$noG++;
                	}
                }
	        	$output.='</table></div>';
	        	$output.='<div class="col-lg-6 col-6">TOTAL OUT OF <u>'.$grandTotal.'/'.$grandWeight.'</u></div>';
	        	$convertedPercent=(100*$grandTotal)/$grandWeight;
	        	$output.='<div class="col-lg-6 col-6">CONVERTED TOTAL OUT OF 100% <u>'.number_format((float)$convertedPercent,2,'.','').'</u></div>';
	        	$output.='<div class="col-lg-6 col-12">Strength observed: _________________________________________________</div>';
	        	$output.='<div class="col-lg-6 col-12">Points to ponder: _________________________________________________</div>';
	        	$output.='<div class="col-lg-6 col-12">Teacher’s comment: _________________________________________________</div>';
	        	$output.='<div class="col-lg-6 col-12">Name and signature of the Principal: ____________________________________</div>';
	        	$output.='<div class="col-lg-6 col-12">Name and signature of the Teacher: ____________________________________</div>';
	        	$output.='<div class="col-lg-8 col-12">Name and signature of the dep’t Head /vice Director/Unit Leader: _______________</div>';
	        	$output.='<div class="col-lg-2 col-6">Date: <u>'.date('M-d-Y').'</u></div>';
	        	$output.='<div class="col-lg-2 col-6 text-center">SCHOOL SEAL</div>';
	        	$output.='<div class="col-lg-12 col-12 text-center StudentViewTextInfo"><u>Key</u> &nbsp;&nbsp; 0.Failure &nbsp; &nbsp;&nbsp; 1.Needs Improvement &nbsp;&nbsp;&nbsp; 2.Good &nbsp;&nbsp;&nbsp; 3.V .good &nbsp;&nbsp;&nbsp; 4.Excellent</div>';
	        	$output.='<div class="col-lg-12 col-12"><p>Note: This evaluation and observation check list can be amended by School management any time in any condition for mutual benefit.</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>Distribution .1.Orginal Copy for the principal</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>2. First Copy for the teacher</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>3. Second Copy for School Dean</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>4. Third copy for General Manager</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>5. Fourth copy for teacher’s file</p></div>';
	        	$output.='</div>';
			}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
        	</div></div>';
		}
		return $output;
	}
	function fetchStaffPerformResultTeacher($max_year,$teid,$max_quarter){
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
		$output='';
		$grandTotal=0;$grandWeight=0;
		$queryfetchTeacher=$this->db->query("select u.id,u.fname,u.mname, u.lname, u.username, u.gradesec,u.grade from users as u where u.id='$teid' and u.usertype='Teacher' and u.status='Active' and u.isapproved='1' group by u.id  order by u.fname,u.mname,u.lname ASC ");
		if($queryfetchTeacher->num_rows()>0){
			
			foreach($queryfetchTeacher->result() as $teachResult){
				$output.='<div class="row" style="width:100%;height:auto;page-break-inside:avoid;">';
				$staff=$teachResult->username;
				$teid=$teachResult->id;
				$output.='<div class="col-lg-12 col-12"><h5 class="header-title text-center">'.$school_name.'</h5>
				<div class="dropdown-divider"></div> ';
				$output.='<h6 class="header-title text-center">'.$school_slogan.'<br>
				<u>Teacher’s performance Evaluation (Summative)</u>
				</h6></div>';
				$output.='<div class="col-lg-8 col-6">Teacher’s name: '.$teachResult->fname.' '.$teachResult->mname.' '.$teachResult->lname.'</div>';
				$output.='<div class="col-lg-4 col-6">Date: '.date('M-d-Y').'</div>';
				$queryTeaches=$this->db->query("select staff,subject,grade from staffplacement where academicyear='$max_year' and staff='$staff' ");
				if($queryTeaches->num_rows()>0){
					$output.='<div class="col-lg-6 col-6">Grade he/she teaches: ';
					foreach($queryTeaches->result() as $heTeaches){
						$output.=''.$heTeaches->grade.' , ';
					}
					$output.='</div>';
					$output.='<div class="col-lg-6 col-6">Subject he/she teaches:';
					foreach($queryTeaches->result() as $heTeaches){
						$output.=''.$heTeaches->subject.' , ';
					}
					$output.='</div>';
				}
				$output.='<div class="col-lg-8 col-6">Quarter: '.$max_quarter.' </div>';
				$output.='<div class="col-lg-4 col-6">Semester: _________</div>';
				$output.='<div class="col-lg-12 col-12 table-responsive">
	        	<table class="tabler table-borderedr table-hover" style="width:100%;">
                <tr>
                    <th>No.</th>
                    <th class="text-center">Activities</th> 
                    <th class="text-center">Points</th> 
                </tr>';
                $queryGroup=$this->db->query("select ct.quarter,ct.perweight,ct.tid,ct.datecreated, ct.pername from teacherperfogroup as ct where ct.academicyear='$max_year' and ct.quarter='$max_quarter' order by ct.tid ASC ");
                if($queryGroup->num_rows()>0){
                	$noG=1;
                	$grandTotal=0;$grandWeight=0;
                	foreach($queryGroup->result() as $groupName){
                		$aid=$groupName->tid;
                		$queryPerValue=$this->db->query("select aid,acname from teacherperactivity where academicyear='$max_year' and quarter='$max_quarter' and pergroup='$aid' ");
                		$totalActivity=$queryPerValue->num_rows() + 2;
                		$output.='<tr><td rowspan="'.$totalActivity.'">'.$noG.'.</td><td><b>'.$groupName->pername.'</b></td><td class="text-center">'.$groupName->perweight.'</td>';
                		
                		$sumTotal=0;              		
                		if($queryPerValue->num_rows()>0){
                			$noV=1;
                			foreach($queryPerValue->result() as $activityName){
                				$aid=$activityName->aid;
                				$output.='<tr><td>'.$noV.'. '.$activityName->acname.'</td>';
                				$queryTeaValue=$this->db->query("select acid,pervalue from teacherperfvalue where academicyear='$max_year' and quarter='$max_quarter' and acid='$aid' and teid='$teid' ");
                				if($queryTeaValue->num_rows()>0){
                					foreach($queryTeaValue->result() as $teacValue){
                						$sumTotal=$sumTotal + $teacValue->pervalue;
                						$output.='<td class="text-center">'.$teacValue->pervalue.'</td>';
                					}
                				}else{
                					$output.='<td class="text-center">-</td>';
                				}
                				$output.='</tr>';
                				$noV++;
                			}
                		}
                		$grandTotal=$grandTotal + $sumTotal;           		
                		$output.='</tr>';
                		$output.='<td class="pull-right">Total</td>'; 
                		if($sumTotal > $groupName->perweight){
                			$output.='<td class="text-center"><span class="text-danger">OW</span></td>';
                		}else{
                			$output.='<td class="text-center"><b>'.$sumTotal.'/'.$groupName->perweight.' </b></td>';
                		}
                		$grandWeight= $grandWeight + $groupName->perweight;
                		$noG++;
                	}
                }
	        	$output.='</table></div>';
	        	$output.='<div class="col-lg-6 col-6">TOTAL OUT OF <u>'.$grandTotal.'/'.$grandWeight.'</u></div>';
	        	if($grandTotal >0 && $grandWeight>0){
	        		$convertedPercent=(100*$grandTotal)/$grandWeight;
	        	}else{
	        		$convertedPercent='';
	        	}
	        	
	        	$output.='<div class="col-lg-6 col-6">CONVERTED TOTAL OUT OF 100% <u>'.number_format((float)$convertedPercent,2,'.','').'</u></div>';
	        	$output.='<div class="col-lg-6 col-12">Strength observed: _________________________________________________</div>';
	        	$output.='<div class="col-lg-6 col-12">Points to ponder: _________________________________________________</div>';
	        	$output.='<div class="col-lg-6 col-12">Teacher’s comment: _________________________________________________</div>';
	        	$output.='<div class="col-lg-6 col-12">Name and signature of the Principal: ____________________________________</div>';
	        	$output.='<div class="col-lg-6 col-12">Name and signature of the Teacher: ____________________________________</div>';
	        	$output.='<div class="col-lg-8 col-12">Name and signature of the dep’t Head /vice Director/Unit Leader: _______________</div>';
	        	$output.='<div class="col-lg-2 col-6">Date: <u>'.date('M-d-Y').'</u></div>';
	        	$output.='<div class="col-lg-2 col-6 text-center">SCHOOL SEAL</div>';
	        	$output.='<div class="col-lg-12 col-12 text-center StudentViewTextInfo"><u>Key</u> &nbsp;&nbsp; 0.Failure &nbsp; &nbsp;&nbsp; 1.Needs Improvement &nbsp;&nbsp;&nbsp; 2.Good &nbsp;&nbsp;&nbsp; 3.V .good &nbsp;&nbsp;&nbsp; 4.Excellent</div>';
	        	$output.='<div class="col-lg-12 col-12"><p>Note: This evaluation and observation check list can be amended by School management any time in any condition for mutual benefit.</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>Distribution .1.Orginal Copy for the principal</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>2. First Copy for the teacher</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>3. Second Copy for School Dean</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>4. Third copy for General Manager</p></div>';
	        	$output.='<div class="col-lg-4 col-6"><p>5. Fourth copy for teacher’s file</p></div>';
	        	$output.='</div>';
			}
		}else{
			$output .='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No record found.
        	</div></div>';
		}
		return $output;
	}
	function fetchLineupschedule($max_year){
		$query=$this->db->query("select li.tid,li.daysname,li.linedate,li.divname,li.datecreated, us.fname,us.mname,us.lname from lineupschedule as li cross join users as us where us.id=li.tid and li.academicyear='$max_year' group by li.id order by linedate ASC ");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive"> 
	            <table class="table table-stripped table-hover" style="width:100%;">
	            <thead><tr> 
	                <th>No.</th>
	                <th>Teacher Name</th>
	                <th>Day Assigned</th>
	                <th>Date Assigned</th>
	                <th>Division Name</th>
	                <th>Date Created</th> </tr>
	            </thead>';
	        $no=1;
			foreach ($query->result() as $fetch_evaluations) {
				$output.='
				<tr>
	                <td>'.$no.'.</td>
	                <td>'.$fetch_evaluations->fname.' '.$fetch_evaluations->mname.' '.$fetch_evaluations->lname.'</td>
	                <td>'.$fetch_evaluations->daysname.'</td>
	                <td>'.$fetch_evaluations->linedate.'</td>
	                <td>'.$fetch_evaluations->divname.'</td>
	                <td>'.$fetch_evaluations->datecreated.'</td>
	            </tr>';
			    $no++;
			}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                      <span>&times;</span>
                    </button><i class="fas fa-exclamation-triangle "></i> No record found.
                </div>
              </div>';
		}
		return $output;
	}
	function printTeacherLineupSchedule($max_year,$branchit){
		$query=$this->db->query("select us.fname,mname,lname, ls.tid, ls.daysname, ls.linedate,ls.divname from users as us cross join lineupschedule as ls where ls.tid=us.id and ls.academicyear='$max_year' and us.status='Active' and us.branch='$branchit' group by us.id order by fname,mname,lname");
		$output='';
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
		if($query->num_rows()>0){
			foreach($query->result() as $fetchTeacher){
				$output.='<h3 class="text-center"><u>'.$school_name.' LineUp Schedule Program in '.$max_year.' Academic Year</u></h3>';
				$id=$fetchTeacher->tid;
				$fname=$fetchTeacher->fname;
				$mname=$fetchTeacher->mname;
				$queryFetchTeacher=$this->db->query("select * from lineupschedule as ls where ls.tid='$id'");
				$output.='<h3 class="text-center"> Teacher '.$fname.' '.$mname.'</h3>';
				$output.='<div class="table-responsive"> 
	            <table class="tabler table-bordered table-hover" style="width:100%;">
	            <tr> 
	                <th class="text-center">Day/Date Assigned</th>
	                <th class="text-center">Division Name</th></tr>';
				foreach($queryFetchTeacher->result() as $teacherFile){
					$output.='<tr><td class="text-center"><h4>'.$teacherFile->daysname.'</h4>('.$teacherFile->linedate.')</td>
					<td class="text-center"><h4>'.$teacherFile->divname.'</h4></td></tr>';

				}
				$output.='</table></div>';
				$output.='<div class="row"><div class="col-lg-6 col-12">Schedule Generated: '.$teacherFile->datecreated.'</div><div class="col-lg-6 col-12 pull-right">MySchool SMS(Grandstande Inc.)</div> </div>';
			}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                      <span>&times;</span>
                    </button><i class="fas fa-exclamation-triangle "></i> No record found.
                </div>
              </div>';
		}
		return $output;
	}
	function printDateLineupSchedule($max_year,$branchit){
		$query=$this->db->query("select us.fname,mname,lname, ls.tid,ls.datecreated, ls.daysname, ls.linedate,ls.divname from users as us cross join lineupschedule as ls where ls.tid=us.id and ls.academicyear='$max_year' and us.status='Active' and us.branch='$branchit' group by ls.linedate order by linedate ASC");
		$output='';
		$query_name = $this->db->query("select * from school");
		$row_name = $query_name->row_array();
		$school_name=$row_name['name'];
		$school_slogan=$row_name['slogan'];
		if($query->num_rows()>0){
			$output.='<h3 class="text-center"><u>'.$school_name.' LineUp Schedule Program in '.$max_year.' Academic Year</u></h3>';
			$output.='<div class="table-responsive"> 
	            <table class="tabler table-bordered table-hover" style="width:100%;">
	            <tr> <th>No.</th>
                <th class="text-center">Day/Date Assigned</th>
                <th class="text-center">Teacher Name</th>
                <th class="text-center">Division Name</th></tr>';
	        $no=1;
			foreach($query->result() as $fetchTeacher){
				
				$id=$fetchTeacher->tid;
				$fname=$fetchTeacher->fname;
				$mname=$fetchTeacher->mname;
				$output.='<tr><td>'.$no.'.</td><td class="text-center"><h4>'.$fetchTeacher->daysname.'</h4>('.$fetchTeacher->linedate.')</td>
				<td class="text-center"><h4>'.$fname.' '.$mname.'</h4></td>
				<td class="text-center"><h4>'.$fetchTeacher->divname.'</h4></td></tr>';
				$no++;
			}
			$output.='</table></div>';
			$output.='<div class="row"><div class="col-lg-6 col-12">Schedule Generated: '.$fetchTeacher->datecreated.'</div><div class="col-lg-6 col-12 pull-right">MySchool SMS(Grandstande Inc.)</div> </div>';
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                      <span>&times;</span>
                    </button><i class="fas fa-exclamation-triangle "></i> No record found.
                </div>
              </div>';
		}
		return $output;
	}
	function save_token()
    {
    	$token = $this->input->post('token');
    	$queryCheck=$this->db->query("select * from notification_tokens_tbl where token='$token' ");
    	if($queryCheck->num_rows()<1){
    		 if(isset($token) && !empty($token))
    		 {
    		 //Insert mode
    			$data = array(
    						'token'       => $token,
    						'delete_status'=>'N',
    						'created_at' => date('y-m-d H:i:s'),
    						);
    			$this->db->insert('notification_tokens_tbl', $data);
    			$insert_id= $this->db->insert_id();
    			
    			if ($insert_id != '') {
    				$data = array(
                    'status' => true,
                    'msg' => 'Record # '.$insert_id.' saved successfully. Thank you!'
                    );
    			} else {
    				$data = array(
                    'status' => false,
                    'msg' => 'Record not save.'
                	);
    			}
    		} else {
    				$data = array(
                    'status' => false,
                    'msg' => 'Token not set.'
                	);
    		}
    		echo json_encode($data);
    	}
			
    }
    function fetchGradeManualMarkFormate($gs_branches,$gs_gradesec,$gs_quarter,$max_year)
	{
		$output='';
		$subjectName=$this->db->query("select * from subject where Grade='$gs_gradesec' and Academic_Year='$max_year' group by Subj_Id order by Subj_name ASC");
		
		if($subjectName->num_rows()>0){
			
            $queryGrade=$this->db->query("select gradesec from users as u where u.academicyear='$max_year' and u.grade='$gs_gradesec' and u.branch='$gs_branches' group by u.gradesec order by gradesec ASC ");
            foreach($queryGrade->result() as $grades){
            	$gradesec=$grades->gradesec;
            	$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
			
    			foreach ($subjectName->result_array() as $subName) {
	    			$subject=$subName['Subj_name'];
					$output.='<div style="page-break-inside:avoid;width:100%;"><h3 class="text-center"><u>'.$school_name.'<small class="time">(Branch: '.$gs_branches.')</small></u></h3>
					<h4 class="text-center"> Academic Year: <B><u>' .$max_year.'</u></B><br>
					Grade :<B><u>'.$gradesec.'</u></B>
					Season :<B><u>'. $gs_quarter.'</u></B>
					Subject :<B><u>'.$subject.'</u></B>
					</h4>';

					$queryEvaluation=$this->db->query("select * from evaluation where academicyear='$max_year' and quarter='$gs_quarter' and grade='$gs_gradesec' group by evname order by eid ASC");
					$output.='<div class="table-responsive">
	        		<table class="tabler table-borderedr table-hover" style="width:100%;">
	        		<tr>
	        		<th rowspan="2">No.</th>
	            	<th rowspan="2">Student Name</th>
	            	<th rowspan="2" class="text-center">Student ID</th>';
	    			foreach ($queryEvaluation->result_array() as $evalua_name) 
	    			{
	            		$mname_gs=$evalua_name['evname'];
		            	$queryMvalue = $this->db->query("select * from schoolassesment where saseval='$mname_gs' and sasgrade='$gs_gradesec' and academicyear='$max_year' group by sasname order by sasid ASC");
		            	$colSpan=$queryMvalue->num_rows();
		            	$output.='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
	            	}
	            	$output.='<th class="text-center" rowspan="2"><B>Total</B></th><th rowspan="2" class="text-center">Sig.</th><tr>';
	            	foreach ($queryEvaluation->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['evname'];
	            		$queryMvalue = $this->db->query("select * from schoolassesment where saseval='$mname_gs' and sasgrade='$gs_gradesec' and academicyear='$max_year' group by sasname order by sasid ASC");
		            	foreach ($queryMvalue->result() as $mark_name)
		            	{
		            		$output.='<td class="text-center">'.$mark_name->sasname.'</td>';
		            	}
		            }
            		$output.='</tr>';
            		$stuNO=1;
	            	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname,u.lname ASC ");
					foreach ($queryStudent->result_array() as $row) { 
		        		$id=$row['id'];
		        		$output.='<tr><td>'.$stuNO.'.</td> <td>'.$row['fname'].' '.$row['mname'].' '.$row['lname']. '</td>';
		        		$output.='<td class="text-center">'.$row['username'].' </td>';
		        		$average=0;
		        		foreach ($queryEvaluation->result_array() as $mark_name)
		        		{
		        			$mname_gs=$mark_name['evname'];
		        			$queryMvalue = $this->db->query("select * from schoolassesment where saseval='$mname_gs' and sasgrade='$gs_gradesec' and academicyear='$max_year' group by sasname order by sasid ASC");
			            	foreach ($queryMvalue->result() as $mark_name1)
			            	{
			            		$output.='<td class="text-center"></td>';
			            	}
		            	}
		            	$output.='<td style="text-align:center;"></td>';
		            	$output.='<td style="text-align:center;"><B>-</B></td>';
						$stuNO++;
					}
					$output.='</table></div>';
					$output.='<p class="text-center">'.$school_slogan.'!</p></div>';
	    		}
	    	}
		}else{
			$output.='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            	<i class="fas fa-check-circle"> </i> No recorded subject found.
        	</div></div>';
		}
		return $output;
	}
	function approveMark($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year){
		$output='';
		$query=$this->db->query("select id,username,fname,mname,lname from users where branch='$gs_branches' and academicyear='$max_year' and gradesec='$gs_gradesec' and status='Active' and usertype='Student' and isapproved='1' and branch='$gs_branches' order by fname,mname ASC ");
		$markname_query=$this->db->query("select ma.evaid, ma.markname, ma.mid,ma.value,ma.approved, ma.outof,sum(outof) as total_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." as ma where ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$gs_quarter' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' and approved='0' group by ma.markname order by ma.mid ASC ");
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
					<a href="#" value="'.$mark_name['markname'].'" class="gs_Approve_markname"> 
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
        			$query_value = $this->db->query("select lockmark,value,outof,mid, markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where markname='$markname' and stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' ");
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
	function fetch_allnewMark($myBranch,$max_quarter,$max_year){
		$queryGrade=$this->db->query("select gradesec from users as u where u.academicyear='$max_year' and  u.branch='$myBranch' group by u.gradesec order by gradesec ASC ");

		/*$arraySp = array('branch' =>$myBranch,'academicyear'=>$max_year);
    	$this->db->where($arraySp);
    	$this->db->group_by('gradesec');  
    	$queryPlacement = $this->db->get('users');*/
    	$output='';
    	if ($queryGrade->num_rows()>0) {
    		foreach ($queryGrade->result() as $keyvalue) {
    			$gradesecs=$keyvalue->gradesec;
    			$this->db->where('approved','0');
    			$this->db->group_by('markname');
    			$this->db->group_by('mgrade');
				$this->db->from('mark'.$myBranch.$gradesecs.$max_quarter.$max_year);
				$query=$this->db->get();
				foreach ($query->result() as $row) {
					$output .='<a href="#" class="dropdown-item"> 
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
    	}
    	return $output;	
	}
	function fetch_unseen_newMark($myBranch,$max_quarter,$max_year){
		$queryGrade=$this->db->query("select gradesec from users as u where u.academicyear='$max_year' and  u.branch='$myBranch' group by u.gradesec order by gradesec ASC ");
		/*
		$arraySp = array('branch' =>$myBranch,'academicyear'=>$max_year);
    	$this->db->where($arraySp); 
    	$this->db->group_by('gradesec');  
    	$queryPlacement = $this->db->get('users');*/
    	$output='';
    	if ($queryGrade->num_rows()>0) {
    		$count=0;
    		foreach ($queryGrade->result() as $keyvalue) {
    			$gradesecs=$keyvalue->gradesec;
    			$this->db->where('approved','0');
    			$this->db->group_by('markname');
    			$this->db->group_by('mgrade');
				$this->db->from('mark'.$myBranch.$gradesecs.$max_quarter.$max_year);
				$total=$this->db->count_all_results();
				$count=$count + $total;
    		}
    		$output.=$count;
    	}
    	return $output;	
	}
	function fetch_allnewAttendance($myBranch,$max_year){
		$queryGrade=$this->db->query("select u.fname,u.mname,att.absentdate,att.absentype from users as u cross join attendance as att where att.academicyear='$max_year' and u.username=att.stuid and u.academicyear='$max_year' and  u.branch='$myBranch' and att.approved='0' order by gradesec ASC ");
    	$output='';
    	if ($queryGrade->num_rows()>0) {
    		foreach ($queryGrade->result() as $row) {
				$output .='<a href="'.base_url().'approveattendance/" class="dropdown-item"> 
                  <span class="dropdown-item-desc"> 
                    <span class="message-user">
                      '.$row->fname.' '.$row->mname.') 
                    </span>
                    <span class="time messege-text">
                     '.$row->absentdate.'('.$row->absentype.')  
                   </span>
                  </span>
                	</a> ';
    		}
    	}
    	return $output;	
	}
	function fetch_unseen_newAttendance($myBranch,$max_year){
		$queryGrade=$this->db->query("select count(stuid) as TotStuid from users as u cross join attendance as att where att.academicyear='$max_year' and u.username=att.stuid and u.academicyear='$max_year' and  u.branch='$myBranch' and att.approved='0' order by gradesec ASC ");
		$total=$this->db->count_all_results();
    	$output='';
    	if ($queryGrade->num_rows()>0) {
    		$count=0;
    		foreach ($queryGrade->result() as $keyvalue) {
    			$gradesecs=$keyvalue->TotStuid;
				$count=$count + $gradesecs;
	    		$output.=$count;
	    	}
    	}
    	return $output;	
	}
	function fetch_allnewAttendanceDirector($myBranch,$max_year,$user){

		$queryGrade=$this->db->query("select u.fname,u.mname,att.absentdate,att.absentype from users as u cross join attendance as att cross join directorplacement as drr where att.academicyear='$max_year' and u.username=att.stuid and u.academicyear='$max_year' and  u.branch='$myBranch' and att.approved='0' and u.gradesec=drr.grade and drr.staff='$user' and drr.academicyear='$max_year' order by gradesec ASC ");
    	$output='';
    	if ($queryGrade->num_rows()>0) {
    		foreach ($queryGrade->result() as $row) {
				$output .='<a href="'.base_url().'approvemyattendance/" class="dropdown-item"> 
                  <span class="dropdown-item-desc"> 
                    <span class="message-user">
                      '.$row->fname.' '.$row->mname.') 
                    </span>
                    <span class="time messege-text">
                     '.$row->absentdate.'('.$row->absentype.')  
                   </span>
                  </span>
                	</a> ';
    		}
    	}
    	return $output;	
	}
	function fetch_unseen_newAttendanceDirector($myBranch,$max_year,$user){
		
		$output='';
		$count=0;
		$queryGrade=$this->db->query("select count(stuid) as TotStuid from users as u cross join attendance as att cross join directorplacement as drr where att.academicyear='$max_year' and u.username=att.stuid and u.academicyear='$max_year' and  u.branch='$myBranch' and att.approved='0' and u.gradesec=drr.grade and drr.staff='$user' and drr.academicyear='$max_year' order by gradesec ASC ");
    	if ($queryGrade->num_rows()>0) {
    		foreach ($queryGrade->result() as $keyvalue) {
    			$gradesecs=$keyvalue->TotStuid;
				$count=$count + $gradesecs;
	    		$output.=$count;
	    	}
    	}
    	return $output;	
	}
	function fetchAttendanceForApproval($branch,$max_year){
		$queryGrade=$this->db->query("select att.stuid ,u.fname,u.mname,att.absentdate, att.absentype, att.attend_by from users as u cross join attendance as att where att.academicyear='$max_year' and u.username=att.stuid and u.academicyear='$max_year' and  u.branch='$branch' and att.approved='0' order by gradesec ASC ");
		$output='';
    	if ($queryGrade->num_rows()>0) {
    		$no=1;
    		$output.='<div class="table-responsive"> 
	            <table class="table table-stripped table-hover" style="width:100%;">
	            <thead><tr> 
	                <th>No.</th>
	                <th>Action</th>
	                <th>Student Name</th>
	                <th>Date Absent</th>
	                <th>Absent Type</th>
	                <th>Recored By</th></tr>
	            </thead>';
    		foreach ($queryGrade->result() as $keyvalue) {
    			$output.='<tr id="approvedThisAttendance'.$keyvalue->stuid.'"><td>'.$no.'.</td><td> 
    			<button type="submit" class="btn btn-primary approveAttendance" value="'.$keyvalue->stuid.'" id="'.$keyvalue->absentdate.'">Approve </button> </td><td>'.$keyvalue->fname.' '.$keyvalue->mname.'</td>';
    			$output.='<td>'.$keyvalue->absentdate.'</td><td> '.$keyvalue->absentype.'</td>';
    			$output.='<td>'.$keyvalue->attend_by.'</td></tr>';
    			$no++;
	    	}
	    	$output.='</table></div>';
    	}else{
    		$output.='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> No data found.
            </div></div>';
    	}
    	return $output;	
	}
	function fetchAttendanceForApprovalDirector($branch,$max_year,$user){
		$queryGrade=$this->db->query("select att.stuid ,u.fname,u.mname,att.absentdate, att.absentype, att.attend_by from users as u cross join attendance as att cross join directorplacement as drr where att.academicyear='$max_year' and u.username=att.stuid and u.academicyear='$max_year' and  u.branch='$branch' and att.approved='0' and u.gradesec=drr.grade and drr.staff='$user' and drr.academicyear='$max_year' order by gradesec ASC ");
		$output='';
    	if ($queryGrade->num_rows()>0) {
    		$no=1;
    		$output.='<div class="table-responsive"> 
	            <table class="table table-stripped table-hover" style="width:100%;">
	            <thead><tr> 
	                <th>No.</th>
	                <th>Action</th>
	                <th>Student Name</th>
	                <th>Date Absent</th>
	                <th>Absent Type</th>
	                <th>Recored By</th></tr>
	            </thead>';
    		foreach ($queryGrade->result() as $keyvalue) {
    			$output.='<tr id="approvedThisAttendance'.$keyvalue->stuid.'"><td>'.$no.'.</td><td> 
    			<button type="submit" class="btn btn-primary approveAttendanceDirector" value="'.$keyvalue->stuid.'" id="'.$keyvalue->absentdate.'" >Approve </button> </td><td>'.$keyvalue->fname.' '.$keyvalue->mname.'</td>';
    			$output.='<td>'.$keyvalue->absentdate.'</td><td> '.$keyvalue->absentype.'</td>';
    			$output.='<td>'.$keyvalue->attend_by.'</td></tr>';
    			$no++;
	    	}
	    	$output.='</table></div>';
    	}else{
    		$output.='<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
               <i class="fas fa-exclamation-circle"> </i> No data found.
            </div></div>';
    	}
    	return $output;	
	}


}
?>