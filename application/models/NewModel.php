<?php
class main_model extends CI_Model{
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
		$this->db->where('datet',$date_now);
		$this->db->select('*');
		$this->db->from('loggeduser');
		$this->db->join('users',
		'users.id=loggeduser.logged_user');
		$query = $this->db->get();
        return $query->result();
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
			$output .='<div class="alert alert-success alert-dismissible show fade">
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
			$output .='<div class="alert alert-success alert-dismissible show fade">
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
        	$output .='<a href="#" class="dropdown-item"> 
                  <span class="dropdown-item-avatar text-white">
                   <img alt="image" src="'.base_url().'/profile/'.$row->profile.'" class="rounded-circle">
                  </span> 
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
	function fetch_allmymarkstatus($id){
		$this->db->where('stuid',$id);
		$this->db->order_by('mid','DESC');
		$query = $this->db->get('mark');
    $output='';
    if($query->num_rows()>0){
    foreach ($query->result() as $row) {
    	$output .='<a href="#" class="dropdown-item">
    	  <span class="dropdown-item-avatar text-white">
          Mark Result
        </span> 
        <span class="dropdown-item-desc"> 
        <span class="message-user">
        '.$row->subname.': '.$row->value.'/' . $row->outof .' '.$row->markname .'
        </span>
        <span class="time messege-text">Your result on </span>
        <span class="time">
        '.$row->quarter .' 
        </span>
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
		//$this->db->where('status','0');
		$this->db->order_by('absentdate','DESC');
		$query = $this->db->get('attendance');
	    $output='';
	    if($query->num_rows()>0){
	    foreach ($query->result() as $row) {
	    	$output .='<a href="#" class="dropdown-item">
	    	  <span class="dropdown-item-avatar text-white">
	          Date
	        </span> 
	        <span class="dropdown-item-desc"> 
	        <span class="message-user">Date:
	        '.$row->absentdate.' '.$row->absentype.'
	        </span>
	        <span class="time messege-text">You were absent on date listed above </span>
	        <span class="time">By:
	        '.$row->attend_by .'
	        </span>
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
	function fetch_allunseetseen_mymark($id){
		$this->db->where('stuid',$id);
		$this->db->where('status','0');
		$this->db->from('mark');
		return $this->db->count_all_results();
	}
	function update_myunseen_mark($id){
		$this->db->where('stuid',$id);
		$this->db->where('status','0');
		$this->db->set('status','1');
		$query=$this->db->update('mark');
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
	function fetchStaffsForPlacement(){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetchMyStaffsForPlacement($branch){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetchThisBranchTeacher($hoomroombranch){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('branch'=>$hoomroombranch));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('usertype','Teacher');
		$query=$this->db->get('users');
		$output ='<option> </option>';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->username.'">
			'.$row->username.'-'.$row->fname.' '.$row->mname.'</option>';
		}
		return $output;
	}
	function fetch_staffs(){
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
       			$id=$staff->id;
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
                    <td>'. $staff->username.'
                        <div class="table-links">
                            <a href="#" id="delete_staff" class="text-danger" value="'.$id.'" ><i class="fa fa-trash"></i> </a>
                            <div class="bullet"></div>
                            <a href="#" id="edit_staff" class="text-success" value="'.$id.'" ><i class="fa fa-pen"></i></a>
                        </div>
                    </td>
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                    	<div class="table-links">
             				<a href="#" class="resetStaffPassword text-warning" id="'.$staff->id.'">Reset Password</a>
            			</div>
                    </td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td> 
                    <td> ';
                    if($staff->status==trim('Active')){ 
                      $output.='<button type="submit" name="inactive" value="'.$staff->id.'" class="btn btn-success inactive">'.$staff->status.'
                      </button>';
                    }else {
                       $output.='<button type="submit" name="active" value="'.$staff->id.'" class="btn btn-danger active">
                        '.$staff->status.'
                      </button>';
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
	function fetch_mystaffs($branch){
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
       			$id=$staff->id;
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
                    <td>'. $staff->username.'
                        <div class="table-links">
                            <a href="#" id="delete_staff" class="text-danger" value="'.$id.'" ><i class="fa fa-trash"></i> </a>
                            <div class="bullet"></div>
                            <a href="#" id="edit_staff" class="text-success" value="'.$id.'" ><i class="fa fa-pen"></i></a>
                        </div>
                    </td>
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                    	<div class="table-links">
             				<a href="#" class="resetStaffPassword text-warning" id="'.$staff->id.'">Reset Password</a>
            			</div>
                    </td>
                    <td>'.$staff->mobile.'</td>
                    <td>'.$staff->branch.'</td> 
                    <td> ';
                    if($staff->status==trim('Active')){ 
                      $output.='<button type="submit" name="inactive" value="'.$staff->id.'" class="btn btn-success inactive">'.$staff->status.'
                      </button>';
                    }else {
                       $output.='<button type="submit" name="active" value="'.$staff->id.'" class="btn btn-danger active">
                        '.$staff->status.'
                      </button>';
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
	function fetchStaffsToAttendance($branch,$today){
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('branch'=>$branch));
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
                <th>UserName/ID</th>
                <th>UserType</th>
                <th>Name</th>
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
                    <td>'. $staff->username.'<a href="#" class="atteInfo"><i class="fas fa-check-circle"></i></a>
                    </td>
	                <td>'.$staff->usertype.'</td>
	                <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
	                </td>
	                <td>'.$staff->mobile.'</td>
	                <td>'.$staff->branch.'</td>                       
	                </tr>';
	                $no++;
       			}else{
       			$output.='<tr class="delete_staff'.$id.'">
                    <td>'.$no.'.</td>
                    <td>'. $staff->username.'
                        <div class="table-links">
                            <a href="#" id="absentStaff" class="text-danger" value="'.$id.'" >Absent </a>
                            <div class="bullet"></div>
                            <a href="#" id="lateStaff" class="text-warning" value="'.$id.'" >Late</a>
                            <div class="bullet"></div>
                            <a href="#" id="permissionStaff" class="text-success" value="'.$id.'" >Permission</a>
                        </div>
                    </td>
                    <td>'.$staff->usertype.'</td>
                    <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                    </td>
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
		$this->db->order_by('fname','ASC');
		$this->db->where(array('status'=>'Inactive'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('usertype','Student');
		$query=$this->db->get('users');
		return $query->result();
	}
	function fetch_branchstudents($gs_branches,$gs_gradesec,$max_year){
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
             <a href="#" class="deletestudent text-danger" id="'.$value->id.'">Delete</a>
             <div class="bullet"></div>
             <a href="#" class="dropstudent text-info" id="'.$value->id.'">Drop</a>
             <div class="bullet"></div>
             <a href="#" class="editstudent text-success" id="'.$value->unique_id.'">Edit</a>
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
		$output .='
         <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tableExport" style="width:100%;">
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
		$output .='
         <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tableExport" style="width:100%;">
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
		$output .='
         <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tableExport" style="width:100%;">
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
		return $output;
	}
	function fetch_student_idcard($max_year,$gradesec,$branch){
		$this->db->order_by('fname','DESC');
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
              <div class="author-box-center">';
              $query_school=$this->db->get('school');
              foreach ($query_school->result() as $school){
                  	$output.='<p> 
                  	<img src="'.base_url().'/logo/'. $school->logo.' " style="border-radius:3em;height: 50px;width: 50px;">'.$school->name.'
                  	';
                  	$output.='<br>School Phone:- '.$school->phone.' <br>Email:- '.$school->email.'</p>';
                  }
                $output.='<hr><p>
                <img alt="image" src="'.base_url().'/profile/'.$staff->profile.'"  style="border-radius:3em;height: 50px;width: 50px;" class="rounded-circle author-box-picture"> <a href="#"> '.$staff->fname.' '.$staff->mname. '
                    </a>
                  </p>
              </div>
              <div class="text-center">
                <div class="author-box-description">
                Gender : '.$staff->gender .'<br>
                  ID : '.$staff->unique_id.' <br>
                  Grade : <a href="#">'.$staff->gradesec.'</a><br> 
                  Academic Year : '.$staff->academicyear .'E.C <br>' ;
                  $output.='Campus : '.$staff->branch.'<br>
                  Guardian Phone :<a href="#"> '.$staff->mobile.'</a><br>
                  Subcity : '.$staff->sub_city.'
                  Woreda : '.$staff->woreda.'<br>
                  School Signature____________
                </div>
              </div>
            </div>
          </div>
        </div>
      ';
		}
		$output.='</div>';
		return $output;
	}
	function fetch_staff_idcard($max_year,$branch){
		$this->db->order_by('fname','ASC');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('branch'=>$branch));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->not_like('usertype','Student');
		$query=$this->db->get('users');
		$output='<div class="row">';
		foreach ($query->result() as $staff) {
			$output.='
			  <div class="col-lg-4">
          <div class="card author-box">
            <div class="card-body">
              <div class="author-box-center">
                <img alt="image" src="'.base_url().'/profile/'.$staff->profile.'"  style="border-radius:3em;height: 50px;width: 50px;" class="rounded-circle author-box-picture">
                  <div class="clearfix"></div>
                  <div class="author-box-name"><p>
                    <a href="#"> '.$staff->fname.' '.$staff->mname. '
                    </a></p>
                    Mob: +251'.$staff->mobile.'<br>
                    ID: ________
                    Position:_____________
                    <small class="text-muted"> Signature:______</small>
                    <hr>
                  </div>
              </div>
                <div class="author-box-description">';
                  $query_school=$this->db->get('school');
                  foreach ($query_school->result() as $school){
                  	$output.='<b><img src="'.base_url().'/logo/'. $school->logo.'" class="border-circle">
                  	'.$school->name.'</b>
                  	<div class="author-box-center">
                  	 <b>Employees ID Card</b>
                  	 <p>Phone: '.$school->phone.'</p>
                  	 <p>Address: '.$school->address.'</p>
                  	 </div>';

                  }
                  $output.='<div class="author-box-center"><p>Campus : '.$staff->branch.'</p></div>
                  <hr>
                  <p class="pull-right">Aurthorized Name:____________</p>
                  <p class="text-muted pull-right"> Signature:____________</p>
                </div>
            </div>
             <small class="text-muted"> The holder of this ID card is our Organization employee. </small>
          </div>
        </div>
      ';
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
	function fetch_student_toedit($id,$max_year){
		$this->db->where(array('unique_id'=>$id));
		$this->db->where(array('academicyear'=>$max_year));
		$query=$this->db->get('users');
		$output='<div class="dropdown-divider"></div>
		<form method="POST" id="updateStuForm">
		<div class="card-body"><div class="row">';
		foreach ($query->result() as $stuValue) {
			$output.='<input type="hidden" name="stuStuid" value="'.$stuValue->unique_id.'">
			<input type="hidden" class="form-control" name="stUsername" value="'.$stuValue->username .'">

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
		        <input type="text" class="form-control" name="stuFname" value="'.$stuValue->fname.'">
		    </div>
            <div class="form-group col-lg-3">
                <label>Father Name</label>
                <input type="text" class="form-control" name="stuLname" value="'.$stuValue->mname.'">
            </div>
            <div class="form-group col-lg-3">
            	<label>G.Father Name</label>
            	<input type="text" class="form-control" name="stuGfname" value="'.$stuValue->lname.'">
            </div>
            <div class="form-group col-lg-3">
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
            <div class="form-group col-lg-3">
                <label>Mother Mobile</label>
                <input type="text" class="form-control" name="stuMobile" value="'.$stuValue->mobile.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Father Mobile</label>
                <input type="text" class="form-control" name="father_mobile" value="'.$stuValue->father_mobile.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Grade</label>
                <input type="text" class="form-control" name="stuGrade" value="'.$stuValue->grade.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Email</label>
                <input type="text" class="form-control" name="stuEmail" 
                value="'.$stuValue->email.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Date of Birth</label>
                <input type="date" class="form-control" name="stuDob" 
                value="'.$stuValue->dob.'">
            </div>
            <div class="form-group col-lg-3">
                <label>City</label>
                <input type="text" class="form-control" name="stuCity" 
                value="'.$stuValue->city.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Sub city</label>
                <input type="text" class="form-control" name= "stuSubcity" value="'.$stuValue->sub_city.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Woreda</label>
                <input type="text" class="form-control" name="stuWoreda" value="'.$stuValue->woreda.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Kebele</label>
                <input type="text" class="form-control" name="stuKebele" value="'.$stuValue->kebele.'">
            </div>
            <div class="form-group col-lg-3">
                <div class="form-group">
	                <label for="Profile">Profile Photo</label>
	                <input id="profile" type="file" class="form-control stuProfile" name="stuProfile">
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary" type="submit" name="savechanges"> Save Changes
                </button>
            </div>
            ';
		}
		$output.='</div></div></form>';
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
	function fetch_staff_toedit($id){
		$this->db->where(array('id'=>$id));
		$query=$this->db->get('users');
		$output='<div class="dropdown-divider"></div>
		<form method="POST" id="updateStaForm" class="formemp" name="formemp">
		<div class="card-body"><div class="row">';
		foreach ($query->result() as $staffValue) {
			$output.='<input type="hidden" name="editedStaff" value="'.$staffValue->id.'">
			<div class="card-header"> <h4>Edit Profile
                <img alt="Profile" src="'.base_url().'/profile/'.$staffValue->profile.'" style="width:70px" class="user-img-radious-style pull-right">
            </h4></div>';
            $output.='<div class="form-group col-lg-3">
                <label>UserName/ID</label>
                <input type="text" class="form-control username" name="username" value="'.$staffValue->username.'">
            </div>
            <div class="form-group col-lg-3">
                <label>First Name</label>
                <input type="text" class="form-control fname" name="fname" value="'.$staffValue->fname.'">               
            </div>
            <div class="form-group col-lg-3">
                <label>Father Name</label>
                <input type="text" class="form-control lname" name="mname" value="'.$staffValue->mname.'">
            </div>
            <div class="form-group col-lg-3">
                <label>G.Father Name</label>
                <input type="text" class="form-control lname" name="lname" value="'.$staffValue->lname.'">
            </div>
            <div class="form-group col-lg-3">
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
            <div class="form-group col-lg-3">
                <label>Mobile</label>
                <input type="text" class="form-control mobile" name="mobile" value="'.$staffValue->mobile.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Quality Allowance</label>
                <input type="number" class="form-control text" onkeyup="calculateTotal()" id="ql" name="quality_allowance" value="'.$staffValue->quality_allowance.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Transport Allowance</label>
                <input type="number" class="form-control text" onkeyup="calculateTotal()" id="tl" name="transport_allowance" value="'.$staffValue->allowance.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Position Allowance</label>
                <input type="number" class="form-control text" onkeyup="calculateTotal()" id="pl" name="position_allowance" value="'.$staffValue->position_allowance.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Home Allowance</label>
                <input type="number" class="form-control text" onkeyup="calculateTotal()" id="hl" name="home_allowance" value="'.$staffValue->home_allowance.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Basic Salary</label>
                <input type="number" class="form-control text" onkeyup="calculateTotal()" name="gsallary" id="gs" value="'.$staffValue->gsallary.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Gross Sallary</label>
                <input type="text" class="form-control text" id="gross_sallary" name="gross_sallary" value="'.$staffValue->gross_sallary.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Taxable Income</label>
                <input type="text" class="form-control text" id="ti"  name="taxable_income" value="'.$staffValue->taxable_income.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Income tax</label>
                <input type="text" class="form-control text" id="income_tax" name="income_tax" value="'.$staffValue->income_tax.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Pension 7%</label>
                <input type="text" class="form-control text" id="pension_7" name="pension_7" value="'.$staffValue->pension_7.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Pension 11%</label>
                <input type="text" class="form-control text" id="pension_11" name="pension_11" value="'.$staffValue->pension_11.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Other</label>
                <input type="text" class="form-control text" id="other" name="other" value="'.$staffValue->other.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Net Payment</label>
                <input type="text" class="form-control text" id="ns" name="netsallary" value="'.$staffValue->netsallary.'">
            </div>
            <div class="form-group col-lg-3">
                <label>Email</label>
                <input type="text" class="form-control email" name="email"
                value="'.$staffValue->email.'">
            </div>
            <div class="form-group col-lg-3">
                <label for="password2" class="d-block">Branch</label>
                <select class="form-control selectric" name="branch" id="branch">
                    <option>'.$staffValue->branch.'</option>';
					$this->db->order_by('name','ASC');
					$queryBranch=$this->db->get('branch');
                    foreach($queryBranch->result() as $branchs){
                    	$output.='<option>'.$branchs->name.'</option>';
                    } 
                $output.='</select>
            </div>
            <div class="card-footer pull-right">
                <button class="btn btn-primary" type="submit" name="savechanges"> Save Changes </button>
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
		$this->db->where('id',$id);
        $query=$this->db->delete('users');
	}
	function active_staffs($id){
		$this->db->where(array('id'=>$id));
		$this->db->set('status', 'Active');
		$this->db->update('users');
	}
	function inactive_staffs($id){
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
		$output='<form class="saveSubjectChanges" method="POST"><div class="card">
		<a href="#" class="backToSubject"> <i class="fas fa-backward"></i> </a>
		<div class="card-body"> <div class="row"> ';
		foreach ($query->result() as $keyvalue) {
			$sgra=$keyvalue->Subj_name;
			$output .='<div class="col-lg-6">
				<input type="hidden" id="oldSubjName" value="'.$keyvalue->Subj_name.'">
				<label for="Mobile">Subject Name </label>
				<input type="text" id="newSubjName" value="'.$keyvalue->Subj_name.'" class="form-control"/>
			</div> ';
			$output .='<div class="col-lg-6">
				<label for="Mobile">Percentage</label>
				<input type="text" id="oldSubjPercent" value="'.$keyvalue->Merged_percent.'" class="form-control"/>
			</div> ';
			$query2=$this->db->query("select * from subject where Academic_Year='$max_year' and Subj_name='$sgra'");
			foreach ($query2->result() as $kvalue) {
				$output .='<div class="col-lg-3" id="deletee'.$kvalue->Subj_name.''.$kvalue->Grade.'">
				<a class="gr'.$kvalue->Grade.'"></a>
				<p class="text-info"> <a href="#" value="'.$kvalue->Subj_name.'" name="'.$kvalue->Grade.'" class="dele">
					<span class="text-danger"><i class="fas fa-trash"></i> </span>
				</a> '.$kvalue->Grade.'</p>';
				if($kvalue->letter=='#'){
				  	$output .='<div class="pretty p-icon p-smooth">
					  	<input type="checkbox" name="#" class="changeme" 
					  	 id="'.$kvalue->Subj_name.'" checked="checked" value="'.$kvalue->Grade.'">'.$kvalue->letter.'
					  	  	<div class="state p-success">
		                		<i class="icon fa fa-check"></i>
		                		<label></label>
	              			</div>
	              		</div> ';
	          			$output .='<div class="pretty p-icon p-smooth">
	          			<input class="changeme" id="'.$kvalue->Subj_name.'" name="A" type="checkbox" value="'.$kvalue->Grade.'">A
	          	  		<div class="state p-success">
	                		<i class="icon fa fa-check"></i>
	                		<label></label>
	              		</div>
          			</div>';
          		}else{
          			$output .='<div class="pretty p-icon p-smooth">
	          			<input class="changeme" name="A" type="checkbox" id="'.$kvalue->Subj_name.'" checked="checked" value="'.$kvalue->Grade.'">'.$kvalue->letter.'
	          			<div class="state p-success">
	                		<i class="icon fa fa-check"></i>
	                		<label></label>
	              		</div>
          			</div>';
          			$output .='<div class="pretty p-icon p-smooth">
	          			<input class="changeme" id="'.$kvalue->Subj_name.'" name="#" type="checkbox" value="'.$kvalue->Grade.'">#
	          	  		<div class="state p-success">
	                		<i class="icon fa fa-check"></i>
	                		<label></label>
	              		</div>
          			</div>';
          		}
          		if($kvalue->onreportcard=='1'){
				  	$output .='<div class="pretty p-icon p-smooth">
					  	<input type="checkbox" name="0" class="changeOnRp" 
					  	 id="'.$kvalue->Subj_name.'" checked="checked" value="'.$kvalue->Grade.'">RC
					  	  	<div class="state p-success">
		                		<i class="icon fa fa-check"></i>
		                		<label></label>
	              			</div>
	              		</div> ';
          		}else{
          			$output .='<div class="pretty p-icon p-smooth">
					  	<input type="checkbox" name="1" class="changeOnRp" 
					  	 id="'.$kvalue->Subj_name.'" value="'.$kvalue->Grade.'">RC
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
	function update_subject($oldsubjname,$data,$max_year){
		$this->db->where('Subj_name',$oldsubjname);
		$this->db->where('Academic_Year',$max_year);
		$query=$this->db->update('subject',$data);
		if($query){
			return true;
		}else{
			return false;
		}
	}
	function add_term($term,$ac){
		$this->db->where(array('term'=>$term));
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
		$this->db->where('grade !=','0');
		$this->db->where('grade !=','');
		$this->db->where('academicyear',$max_year);
		$this->db->order_by('grade','ASC');
		$this->db->group_by('grade');
		$query=$this->db->get('users');
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
                            <a href="#" class="editSubject" value="'.$post->Subj_name.'">Edit
                            </a>
                            <div class="bullet"></div>
                            <a href="#" class="deletesubject text-danger" id="'.$post->Subj_name.'">Delete</a>
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
  	function fetch_merged_subject_grades($max_year){
		$query=$this->db->query("SELECT *  from subject where Academic_Year ='$max_year' and Merged_name!='' GROUP BY Merged_name,Grade ORDER BY Merged_name ASC");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive"> 
            <table class="table table-borderedr" style="width:100%;">
            <thead><tr> 
                <th>Merge Name</th>
                <th>Grade</th>
                <th>Merged Subject List</th>
                <th>Percentage</th> </tr>
            </thead>';
			foreach ($query->result() as $posts) {
				$id=$posts->Merged_name;
            	$gradee=$posts->Grade;
            	$count_span=$this->db->query(" select * from subject where Merged_name ='$id' and Grade ='$gradee'");
            	$al_joss=$count_span->num_rows();
            	$output.='<tr class="removeit'.$posts->Grade.''.$posts->Merged_name.'">
                <td rowspan ="'.$al_joss.'"><button class="btn btn-default removemerged" value="'.$posts->Grade.'" name="'.$posts->Merged_name.'" id="heresave'.$posts->Grade.'">'.$posts->Merged_name.'</button> </td> 
                <td rowspan ="'.$al_joss.'">'.$posts->Grade.'</td>';
                $query231=$this->db->query("select * from subject where Merged_name ='$id' and Grade ='$gradee'");
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
    		$output.='<div class="alert alert-info    alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    No Merged Subject Yet.
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
	function fetchQuarterOfYear($max_year){
		$this->db->where('Academic_year',$max_year);
		$this->db->order_by('term','ASC');
		$query=$this->db->get('quarter');
		$output='';
		if($query->num_rows()>0){
			$output.='<div class="table-responsive">
            <table class="table table-borderedr table-md">
                <tr>
                  <th>Name</th>
                  <th>Starting date</th>
                  <th>Ending date</th>
                  <th>Created At</th>
                  <th>Academic Year</th>
                  <th>Action</th>
            </tr>';
			foreach ($query->result() as $qValue) {
				$id=$qValue->id;
				$output.='                          
                <tr class="delete_mem'.$id.'">
                  <td>'.$qValue->term.'</td>
                  <td>'.$qValue->startdate.'</td>
                  <td>'.$qValue->endate.'</td>
                  <td>'.$qValue->date_created.'</td>
                  <td>'.$qValue->Academic_year.'</td>
                  <td><button type="submit" name="deleteterm" id="'.$qValue->id.'"  class="btn btn-danger deleteterm">Delete </button> </td>
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
	function fetch_term_4teacheer($max_year){
		$this->db->where('Academic_year',$max_year);
		$this->db->select_max('term');
		$this->db->order_by('term','ASC');
		$query=$this->db->get('quarter');
		return $query->result();
	}
	function delete_term($id){
		$this->db->where('id',$id);
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
	function fetch_eval_grade($max_year,$max_quarter){
		$query=$this->db->query("select *, GROUP_CONCAT(grade) as evalname from evaluation where academicyear='$max_year' and quarter='$max_quarter' group by percent,evname order by grade DESC ");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="table-responsive"> 
	            <table class="table table-stripped table-hover" style="width:100%;">
	            <thead><tr> 
	                <th>No.</th>
	                <th>Evaluation Name</th>
	                <th>Quarter</th>
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
			$output.='<div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                          <span>&times;</span>
                        </button><i class="fas fa-exclamation-triangle "></i> No evaluation yet.
                    </div>
                  </div>';
		}
		return $output;
	}
	function fetch_evaluation_status($max_year,$max_quarter){
		$query=$this->db->query("select grade, sum(percent) as total from evaluation where academicyear='$max_year' and quarter='$max_quarter' group by grade order by grade DESC ");$output='';
		$output='<div class="row">';
		foreach ($query->result() as $klue) {
			if($klue->total!=='100'){
    			$output.='<div class="col-md-3">
    			 <div class="alert alert-warning    alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                          <span>&times;</span>
                        </button><i class="fas fa-exclamation-triangle "></i> Missed evaluation for Grade '.$klue->grade.'.
                    </div>
                  </div>
                </div>';
    		}else{
    			//$output.='<p></p>';
    		}
		}$output.='</div>';
    	return $output;
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
			$output.='<input type="hidden" id="my_percent" value="'.$keyalue->percent.'">
				<input type="hidden" id="my_evname" value="'.$keyalue->evname.'">';
			$output .='<div class="col-md-4">
			    <div class="form-group">
			   <input type="text" disabled="disabled" class="form-control" value="'.$keyalue->quarter.'">
			  </div> </div>';
			$output .='<div class="col-md-4">
			    <div class="form-group">
			   <input type="text" class="form-control" id="new_evname" value="'.$keyalue->evname.'">
			  </div> </div>';
			$output .='<div class="col-md-4"><div class="form-group">
			   <input type="text" id="new_percent" class="form-control" value="'.$keyalue->percent.'">
			  </div></div>';
			$query_grade=$this->db->query("select * from evaluation where evname='$evname' and quarter='$quarter' and academicyear='$max_year' and percent='$id' ");
			foreach ($query_grade->result() as $keyue) {
				$output .='<div class="col-md-2"> 
				<div class="form-group">  <div class="pretty p-icon p-smooth">
				  	<input type="checkbox" name="'.$keyue->quarter.'" class="remove_eval" id="'.$keyue->evname.'" checked="checked" value="'.$keyue->grade.'">'.$keyue->grade.'
				  	  <div class="state p-success">
                	<i class="icon fa fa-check"></i>
                	<label></label>
              	</div></div></div></div>';
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
	function edit_thisgradevaluation($percent,$max_quarter,$evname,$max_year,$new_percent,$new_evname){
		$this->db->where(array('percent'=>$percent));
		$this->db->where(array('quarter'=>$max_quarter));
		$this->db->where(array('evname'=>$evname));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->set('evname',$new_evname);
		$this->db->set('percent',$new_percent);
		$query=$this->db->update('evaluation');
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
	function import_student($username,$usertype,$stu_id,$name,$fathername,$gfathername,$mobile,$fathermobile,$email,$grade,$section,$grasection,$dob,$age,$gender,$password,$confpassword,$mothername,$city,$subcity,$woreda,$kebele,$isapprove,$registrationdate,$branch,$academicyear,$status){
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
			'password'=>md5($password),
			'password2'=>md5($confpassword),
			'mother_name'=>$mothername,
			'city'=>$city,
			'sub_city'=>$subcity,
			'woreda'=>$woreda,
			'kebele'=>$kebele,
			'isapproved'=>$isapprove,
			'dateregister'=>$registrationdate,
			'branch'=>$branch,
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
	function import_staffs($username,$usertype,$name,$fathername,$gfathername,$mobile,$email,$profile,$dob,$age,$gender,$password,$confpassword,$city,$subcity,$woreda,$kebele,$isapprove,$registrationdate,$branch,$academicyear,$status){
		$this->db->where('username = ',$username);
		$data=array(
			'username'=>$username,
			'usertype'=>$usertype,
			'fname'=>$name,
			'mname'=>$fathername,
			'lname'=>$gfathername,
			'mobile'=>$mobile,
			'email'=>$email,
			'profile'=>$profile,
			'dob'=>$dob,
			'age'=>$age,
			'gender'=>$gender,
			'password'=>md5($password),
			'password2'=>md5($confpassword),
			'city'=>$city,
			'sub_city'=>$subcity,
			'woreda'=>$woreda,
			'kebele'=>$kebele,
			'isapproved'=>$isapprove,
			'dateregister'=>$registrationdate,
			'branch'=>$branch,
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
		$output='<div class="table-responsive"><table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Grade</th>
                    <th>Branch</th>
                    <th>Date Created</th>
                  </tr>
                </thead>
            <tbody>';$no=1;
			foreach ($query->result() as $staffplacements) {
				$output.='<tr class="delete_hrplacement'.$staffplacements->teacher.'">
	            <td>'. $no.'.</td>
	            <td>'.$staffplacements->fname.' '.$staffplacements->mname.'
	            <div class="table-links">
	              <a href="#" id="delete_hoomroomplacemet" class="text-danger" 
	              value="'.$staffplacements->teacher.'" >Delete
	              </a>
	            </div>
	            </td>
	            <td>'.$staffplacements->gradess.'</td>
	            <td>'.$staffplacements->branch.'</td>
	            <td>'.$staffplacements->date_created.'</td>
	          </tr>';
	            $no++; 
	        } 
         	$output.='</tbody> </table></div>';
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
		$output='<div class="table-responsive"><table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Grade</th>
                    <th>Branch</th>
                    <th>Date Created</th>
                  </tr>
                </thead>
            <tbody>';$no=1;
			foreach ($query->result() as $staffplacements) {
				$output.='<tr class="delete_hrplacement'.$staffplacements->teacher.'">
	            <td>'. $no.'.</td>
	            <td>'.$staffplacements->fname.' '.$staffplacements->mname.'
	            <div class="table-links">
	              <a href="#" id="delete_hoomroomplacemet" class="text-danger" 
	              value="'.$staffplacements->teacher.'" >Delete
	              </a>
	            </div>
	            </td>
	            <td>'.$staffplacements->gradess.'</td>
	            <td>'.$staffplacements->branch.'</td>
	            <td>'.$staffplacements->date_created.'</td>
	          </tr>';
	            $no++; 
	        } 
         	$output.='</tbody> </table></div>';
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
	function deleteHomeRoomplacement($staff_placement,$max_year){
		$this->db->where(array('teacher'=>$staff_placement));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->delete('hoomroomplacement');
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
	function fetch_staff_placement($max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(st.grade) as gradess, GROUP_CONCAT(st.subject) as subjects from staffplacement as st cross join users as us where st.staff=us.username and st.academicyear ='$max_year'  GROUP BY st.staff ORDER BY st.staff ASC");
		$output='<div class="table-responsive"><table class="table table-bordered table-hover" id="tableExport" style="width:100%;">
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
			$output.='<tr class="delete_staffplacement'.$staffplacements->staff.'">
            <td>'. $no.'.</td>
            <td>'.$staffplacements->fname.' '.$staffplacements->mname.'
            <div class="table-links">
              <a href="#" id="delete_staffplacemet" class="text-danger" 
              value="'.$staffplacements->staff.'" >Delete
              </a>
            </div>
            </td>
            <td>'.$staffplacements->subjects.'</td>
            <td>'.$staffplacements->gradess.'</td>
            <td>'.$staffplacements->date_created.'</td>
          </tr>';
            $no++; 
        } 
	    $output.='</tbody> </table></div>';
		return $output;
	}
	function fetch_mystaff_placement($max_year,$branch){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(st.grade) as gradess, GROUP_CONCAT(st.subject) as subjects from staffplacement as st cross join users as us where st.staff=us.username and st.academicyear ='$max_year' and us.branch='$branch' GROUP BY st.staff ORDER BY st.staff ASC");
		$output='<div class="table-responsive"><table class="table table-bordered table-hover" id="tableExport" style="width:100%;">
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
			$output.='<tr class="delete_staffplacement'.$staffplacements->staff.'">
            <td>'. $no.'.</td>
            <td>'.$staffplacements->fname.' '.$staffplacements->mname.'
            <div class="table-links">
              <a href="#" id="delete_staffplacemet" class="text-danger" 
              value="'.$staffplacements->staff.'" >Delete
              </a>
            </div>
            </td>
            <td>'.$staffplacements->subjects.'</td>
            <td>'.$staffplacements->gradess.'</td>
            <td>'.$staffplacements->date_created.'</td>
          </tr>';
            $no++; 
        } 
	    $output.='</tbody> </table></div>';
		return $output;
	}
	function delete_placement($id){
		$this->db->where(array('staff'=>$id));
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
                        <td>'.$new_staff->fname.' '.$new_staff->mname.'</td>
                        <td><img src="'.base_url().'/profile/'.$new_staff->profile.'" style="width:30px;height:30px;boreder-radius:3em;"></td>
                        <td>'.$new_staff->branch.'</td>
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
                        <td>'.$new_staff->fname.' '.$new_staff->mname.'</td>
                        <td><img src="'.base_url().'/profile/'.$new_staff->profile.'" style="width:30px;height:30px;boreder-radius:3em;"></td>
                        <td>'.$new_staff->branch.'</td>
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
	function fetcHrGradesec($max_year,$user){
		$this->db->group_by('roomgrade');
		$this->db->order_by('roomgrade','ASC');
		$this->db->where(array('teacher'=>$user));
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
	function filterGradesecForTeachers($gradesec,$max_year,$branch){
		$this->db->where('branch',$branch);
		$this->db->where('gradesec',$gradesec);
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->order_by('fname','ASC');
		$query = $this->db->get('users');
		$output ='';
		if($query->num_rows()>0){
	        $output .='
	        	<div class="row">
	                <div class="col-lg-4">
	                    <div class="form-group">
	                        <label for="Mobile">Select Date</label>
	                        <input class="form-control" name="teaStuAbsentDate" id="teaStuAbsentDate" required="required" type="date">
	                    </div>
	                </div>
	                <div class="col-lg-4">
	                    <div class="form-group">
	                        <label for="Mobile">Late(Minute) for Late students
	                          </label>
	                        <input class="form-control" id="teaStuAbsentMin" name="teaStuAbsentMin" type="number" placeholder="Late in Min">
	                    </div>
	                </div>
	            </div>
	            <div class="table-responsive">
	                <table class="table table-striped table-hover" style="width:100%;">
	                    <thead>
	                        <tr>
	                        	<th>No.</th>
	                           <th>Student Name</th>
	                           <th>
	                            <a class="" href="#" name="absent">Absent</a>
	                           </th>
	                            <th><a href="#" class="" name="late">Late</a></th>
	                            <th><a href="#" class="" name="permission"> Permission</a></th>
	                        </tr>
	                    </thead>
	                    <tbody>';
	                    	$no=1;
	        				foreach ($query->result() as $row) {
	        	              	$output .='<tr>
	        	              	<td>'.$no.'.</td>
	        	              	<td>'.$row->fname.' '.$row->mname.' <i class="fas fa-arrow-right"></i> '.$row->gradesec.'</td>
	                          	<td><input type="checkbox" id="absenStuId" value="'.$row->id.'"></td>
	                          	<td><input type="checkbox" id="lateStuId" value="'.$row->id.'"></td>
	                          	<td><input type="checkbox" id="perStuId" value="'.$row->id.'"></td>
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
	function fetch_gradesec_student($gradesec,$attBranches,$max_year){
		$this->db->where('gradesec',$gradesec);
		$this->db->where('branch',$attBranches);
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
          <td><input type="checkbox" name="absentid[ ]" value="'.$row->username.'"></td>
          <td><input type="checkbox" name="lateid[ ]" value="'.$row->username.'"></td>
          <td><input type="checkbox" name="permissionid[ ]" value="'.$row->username.'"></td>
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
        <table class="table table-bordered table-hover" id="tableExport" style="width:100%;">
        <thead>
        <tr>
          <th>Student Name</th>
          <th>Grade</th>
          <th>Branch</th>
          <th>Gender</th>';
         $query_num_grade = $this->db->query("select * from users where grade='$gradesec' and academicyear ='$max_year' and usertype='Student' group by section");
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
       <td>'.$row->fname.'&nbsp'.$row->mname.'</td>
       <td>'.$row->grade.'</td>
       <td>'.$row->branch.'</td>
       <td>'.$row->gender.'</td>';
        for($j=0;$j<$into; $j++) {
        	if($row->section == $i[$j]){
        		$output .='
        		<input type="hidden" class="grades" value="'.$row->grade.'">
           <td>
           <div class="pretty p-icon p-smooth">
           <input type="checkbox" class="placesiec" id="'.$row->id.'" checked="checked" value="'.$i[$j].'">  
              <div class="state p-success">
                <i class="icon fa fa-check"></i>
                <label></label>
              </div>
           </div>
           <a class="saved'.$row->id.''.$i[$j].'" ></a>
           </td>';
        	}else{
           $output .='
           <input type="hidden" class="grades" value="'.$row->grade.'">
           <td>
           <div class="pretty p-icon p-smooth">
           <input type="checkbox" class="placesiec" id="'.$row->id.'" value="'.$i[$j].'">  
              <div class="state p-success">
                <i class="icon fa fa-check"></i>
                <label></label>
              </div>
           </div>
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
			$queryMale = $this->db->query("SELECT * FROM users where usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' and gender='Male' or usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' and gender='M'  ORDER BY fname ASC"); 
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
      		$queryFemale = $this->db->query("SELECT * FROM users where usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' and gender='Female' or usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' and gender='F'  ORDER BY fname ASC"); 
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
      		$query_fetch = $this->db->query("SELECT * FROM users where usertype='Student' and grade='$gradesec' and academicyear='$max_year' and branch='$branch2place' ORDER BY fname ASC");
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
		$this->db->order_by('fname','ASC');
		$query = $this->db->get('users');
		$output ='';
       $output .=' <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tableExport" style="width:100%;">
        <thead>
        <tr>
          <th>Student Name</th>
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
       <td>'.$row->fname.'&nbsp'.$row->mname.'</td>
       <td>'.$row->gradesec.'</td>
       <td>'.$row->gender.'</td>';
         foreach ($query_num_branch->result() as $rowi) {
        	if($row->branch == $rowi->name){
        		$output .=' <td>
        		 <div class="pretty p-icon p-smooth">
        		<input type="checkbox" class="placesiec" id="'.$row->id.'" checked="checked" value="'.$rowi->name.'">
        		    <div class="state p-success">
                      <i class="icon fa fa-check"></i>
                      <label></label>
                    </div>
        		</div>
        	   <a class="saved'.$row->id.''.$rowi->name.'" ></a>
        		</td>';
        	}else{
           $output .=' <td>
           <div class="pretty p-icon p-smooth">
            <input type="checkbox" class="placesiec" id="'.$row->id.'" value="'.$rowi->name.'"> 
            	<div class="state p-success">
                    <i class="icon fa fa-check"></i>
                    <label></label>
                </div>
            </div>
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
	function update_student_branch($stu_id,$section_id){
		$query_ck=$this->db->query("select * from users where branch='$section_id' and id='$stu_id' ");
		if($query_ck->num_rows()>0){
			$this->db->set('branch','');
			$this->db->where(array('id'=>$stu_id));
			$query=$this->db->update('users');
		}else{
			$this->db->set('branch',$section_id);
			$this->db->where(array('id'=>$stu_id));
			$query=$this->db->update('users');
		}
		if($query){
			return true;
		}else{
			return false;
		}
	}
	function insert_absent($id,$date,$max_year,$user){
		$this->db->where(array('stuid'=>$id));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('absentdate'=>$date));
		$query=$this->db->get('attendance');
		$output='';
		if($query->num_rows() > 0){
			$output.='<i class="fas fa-check-circle"> </i>';
		}else{
			$data=array(
				'stuid'=>$id,
				'absentdate'=>$date,
				'absentype'=>'Absent',
				'academicyear'=>$max_year,
				'attend_by'=>$user
			);
			$this->db->insert('attendance',$data);
		}
		return $output;
	}
	function insert_late($id,$date,$max_year,$minute,$user){
		$this->db->where(array('stuid'=>$id));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('absentdate'=>$date));
		$query=$this->db->get('attendance');
		$output='';
		if($query->num_rows() > 0){
			$output.='<i class="fas fa-check-circle"> </i>';
		}else{
			$data=array(
				'stuid'=>$id,
				'absentdate'=>$date,
				'absentype'=>'Late',
				'latemin'=>$minute,
				'academicyear'=>$max_year,
				'attend_by'=>$user
			);
			$this->db->insert('attendance',$data);
		}
		return $output;
	}
	function insert_permission($id,$date,$max_year,$user){
		$this->db->where(array('stuid'=>$id));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->where(array('absentdate'=>$date));
		$query=$this->db->get('attendance');
		$output='';
		if($query->num_rows() > 0){
			$output.='<i class="fas fa-check-circle"> </i>';
		}else{
			$data=array(
				'stuid'=>$id,
				'absentdate'=>$date,
				'absentype'=>'Permission',
				'academicyear'=>$max_year,
				'attend_by'=>$user
			);
			$this->db->insert('attendance',$data);
		}
		return $output;
	}
	function fetch_attendance($max_year){
		$this->db->where('usertype','Student');
		$this->db->like('attendance.academicyear',$max_year);
		$this->db->order_by('attendance.absentdate','DESC');
		$this->db->select('*');
        $this->db->from('attendance');
        $this->db->join('users', 
            'users.username = attendance.stuid');
        $query = $this->db->get();
        return $query->result();
	}
	function fetch_mattendance($max_year,$branch){
		$this->db->where('users.branch',$branch);
		$this->db->where('usertype','Student');
		$this->db->like('attendance.academicyear',$max_year);
		$this->db->order_by('attendance.absentdate','DESC');
		$this->db->select('*');
        $this->db->from('attendance');
        $this->db->join('users', 
            'users.username = attendance.stuid');
        $query = $this->db->get();
        return $query->result();
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
			$output.='<div class="table-responsive">
        	<table class="table table-striped table-hover" style="width:100%;">
        	<thead>
        	<tr>
	        	<th>No.</th>
                <th>UserName/ID</th>
                <th>AbsentType</th>
                <th>Name</th>
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
                <td>'. $staff->username.'<a href="#" class="deleteAttendance text-danger" value="'.$id.'"><i class="fas fa-times-circle"></i></a>
                </td>
                <td><span class="text-danger">'.$staff->absentype.'</span></td>
                <td>'.$staff->fname.' '.$staff->mname.' '.$staff->lname.'
                </td>
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
	function fetch_mystaffattendance($max_year,$branch){
		$this->db->where('branch',$branch);
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
	function fetchMyStuAttendance($max_year,$branch,$HrGrade){
		$this->db->where('branch',$branch);
		$this->db->where('gradesec',$HrGrade);
		$this->db->where('usertype =','Student');
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
                <td>'. $staff->username.'<a href="#" class="deleteStuAttendance text-danger" value="'.$id.'"><i class="fas fa-times-circle"></i></a>
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
                <i class="fas fa-exclamation-triangle"> </i> No absent students found yet.
            </div></div>';
		}
		return $output;
	}
	function delete_attendance($id){
		$this->db->where(array('aid'=>$id));
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
		$this->db->where('attendance.academicyear',$max_year);
		$this->db->order_by('aid','DESC');
		$this->db->select('*');
		$this->db->from('attendance');
		$this->db->join('users','users.id=attendance.stuid');
		$query = $this->db->get();
		return $query->result();
	}
	function export_student_mark_formate($gradesec,$quarter,$max_year,$branch1){
		$this->db->where('evaluation.academicyear',$max_year);
		$this->db->where('evaluation.quarter',$quarter);
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch1);
		$this->db->where('users.academicyear',$max_year);
		$this->db->order_by('users.fname','ASC');
		$this->db->order_by('users.mname','ASC');
		$this->db->order_by('users.lname','ASC');
		$this->db->group_by('users.id');
		$this->db->select('*');
		$this->db->from('evaluation');
		$this->db->join('users', 
            'users.grade = evaluation.grade');
		$query = $this->db->get();
        return $query->result();
	}
	function export_mystudent_mark_formate($gradesec,$quarter,$max_year,$branch){
		$this->db->where('evaluation.academicyear',$max_year);
		$this->db->where('evaluation.quarter',$quarter);
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch);
		$this->db->where('users.academicyear',$max_year);
		$this->db->order_by('users.fname','ASC');
		$this->db->order_by('users.mname','ASC');
		$this->db->order_by('users.lname','ASC');
		$this->db->group_by('users.id');
		$this->db->select('*');
		$this->db->from('evaluation');
		$this->db->join('users', 
            'users.grade = evaluation.grade');
		$query = $this->db->get();
        return $query->result();
	}
	function export_this_grade_evname($gradesec,$quarter,$max_year,$branch1){
		$this->db->where('evaluation.academicyear',$max_year);
		$this->db->where('evaluation.quarter',$quarter);
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch1);
		$this->db->where('users.academicyear',$max_year);
		$this->db->order_by('evaluation.eid','ASC');
		$this->db->group_by('evaluation.evname');
		$this->db->select('*');
		$this->db->from('evaluation');
		$this->db->join('users', 
            'users.grade = evaluation.grade');
		$query = $this->db->get();
        return $query->result();
	}
	function export_mythis_grade_evname($gradesec,$quarter,$max_year,$branch){
		$this->db->where('evaluation.academicyear',$max_year);
		$this->db->where('evaluation.quarter',$quarter);
		$this->db->where('users.gradesec',$gradesec);
		$this->db->where('users.branch',$branch);
		$this->db->where('users.academicyear',$max_year);
		$this->db->order_by('evaluation.eid','ASC');
		$this->db->group_by('evaluation.evname');
		$this->db->select('*');
		$this->db->from('evaluation');
		$this->db->join('users', 
            'users.grade = evaluation.grade');
		$query = $this->db->get();
        return $query->result();
	}
	function get_allsubject($gradesec,$max_year){
		$query=$this->db->query("select count(su.Subj_Id) as all_sub,su.Subj_Id,su.Subj_name,su.Grade from subject as su cross join users as u where u.grade=su.Grade and u.gradesec='$gradesec' and Academic_Year='$max_year' group by su.Subj_Id order by su.Subj_name ASC");
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
			$output .='<input class="form-control correct_mark_gs" id="correct_value" type="text" value="'.$value->value.'">
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
			<input class="form-control correct_ngmark_gs" placeholder="Value..." id="" type="text"> </div></div>
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
	function update_edited_mark($outof,$mid,$value,$quarter,$gradesec,$year,$branch)
	{
		$this->db->where(array('mid'=>$mid));
		$this->db->set('value',$value);
		$query=$this->db->update('mark'.$branch.$gradesec.$quarter.$year);
		$output='';
		if($query){
			$output .='<span class="text-success"> Updated</span>';
		}else{
			$output .='<span class="text-danger"> ooops</span>';
		}
		return $output;
	}
	function update_edited_ngmark($data,$quarter,$gradesec,$year,$my_studentBranch){
		$query=$this->db->insert('mark'.$my_studentBranch.$gradesec.$quarter.$year,$data);
		$output='';
		if($query){
			$output .='<span class="text-success"> Updated</span>';
		}else{
			$output .='<span class="text-danger"> ooops</span>';
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
	function fetch_grade_mark($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year)
	{
		$output='';
		$query=$this->db->query("select id,username,fname,mname,lname from users where branch='$gs_branches' and academicyear='$max_year' and gradesec='$gs_gradesec' and status='Active' and usertype='Student' and isapproved='1' and branch='$gs_branches' order by fname,mname,lname ASC ");

		$markname_query=$this->db->query("select ma.evaid, ma.markname,ma.mid, ma.value, ma.outof,sum(outof) as total_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." as ma where ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$gs_quarter' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' group by ma.markname order by ma.mid ASC ");
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
			$output.='<button class="btn btn-default lock_selected"><span class="text-info"><i class="fas fa-lock"></i> Lock / Unlock '.$gs_subject.'</span></button>';
			$output.='<button class="btn btn-default lock_selected_grade"><span class="text-success"><i class="fas fa-lock"></i> Lock / Unlock Grade '.$gs_gradesec.'</span></button>';
			
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
        			$query_value = $this->db->query("select lockmark,value, outof,mid, markname from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where markname='$markname' and stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' ");
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
	function fetch_grade_mark_4teacher($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year){
		$output='';
		$query=$this->db->query("select id,username,fname,mname,lname from users where branch='$gs_branches' and academicyear='$max_year' and gradesec='$gs_gradesec' and status='Active' and usertype='Student' and isapproved='1' and branch='$gs_branches' order by fname,mname ASC ");

		$markname_query=$this->db->query("select ma.evaid, ma.markname, ma.mid,ma.value, ma.outof,sum(outof) as total_outof from mark".$gs_gradesec.$gs_quarter.$max_year." as ma where ma.academicyear='$max_year' and ma.subname='$gs_subject' and ma.quarter='$gs_quarter' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' group by ma.markname order by ma.mid ASC ");
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
        			$query_value = $this->db->query("select lockmark,value,outof,mid, markname from mark".$gs_gradesec.$gs_quarter.$max_year." where markname='$markname' and stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' ");
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
						$output.='<td>
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
	function fetch_my_markresult($gs_subject,$gs_quarter,$max_year,$id,$gradesec,$gs_branches)
	{
		$querySingleSubject=$this->db->query("select * from mark".$gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gradesec' and mbranch='$gs_branches' ");
			if($querySingleSubject->num_rows()>0)
			{
				$query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
				$output[]='<h4 class="text-center"><B>'.$school_name.'</B>('.$gs_branches.'<small class="time">(Academic Year: '.$max_year.')</small>)</h4>';
				$output[]='<div class="row"><div class="col-md-1"></div>
				<div class="col-md-3"> Grade :<B>'.
				$gradesec.'</B></div> <div class="col-md-3">Season :<B>'.
				$gs_quarter.'</B> </div> <div class="col-md-3">Subject : <B>'.
				$gs_subject.'</B></div><div class="col-md-1"></div></div>';	
				$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

				$output[]='<div class="table-responsive">
        		<table class="table table-bordered table-hover" style="width:100%;height:92%">
        		<thead>
        		<tr>
        		<th rowspan="3">No.</th>
            	<th rowspan="3">Student Name</th>
            	<th rowspan="3">Student ID</th>';
            	
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from mark".$gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
            		$colSpan=$queryMvalue->num_rows() +2;
            		$output[]='<th class="text-center" colspan="'.$colSpan.'"><b>'.$evalua_name['evname'].'</b></th>';
            	}
            	$output[]='<th>Total</th><th rowspan="3">Sig.</th><tr>';
            	foreach ($evalname_query->result_array() as $evalua_name) {
            		$mname_gs=$evalua_name['eid'];
            		$queryMvalue = $this->db->query("select markname from mark".$gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
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
            		$queryMvalue = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gradesec' and mbranch='$gs_branches' group by markname order by mid ASC");
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$output[]='<td class="text-center">'.$mark_name['outof'].'</td>';
	            	}
            		$sum_outof = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gradesec' and mbranch='$gs_branches' group by stuid order by mid ASC");
        		    $keyvalue=$sum_outof->row_array();
        		    $sum_outof= $keyvalue['sum_outof'];
        		    $output[]='<td style="text-align:center;"><B>'.$sum_outof.'</B></td>';
        		    $output[]='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
	            }
            	$output[]='</tr>';
            	$stuNO=1;
            	$queryStudent=$this->db->query("select u.id,u.fname,u.lname,u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' and id='$id' group by u.id order by u.fname,u.mname ASC ");
				foreach ($queryStudent->result_array() as $row) { 
            		$id=$row['id'];
            		$output[]='<tr><td>'.$stuNO.'.</td> <td>'.$row['fname'].' '.$row['mname'].' '.$row['lname']. '</td>';
            		$output[]='<td>'.$row['username'].' </td>';
            		$average=0;
            		foreach ($evalname_query->result_array() as $mark_name)
            		{
            			$percent= $mark_name['percent'];
            			$mname_gs=$mark_name['eid'];
	            		$query_value = $this->db->query("select markname,sum(value) as total from mark".$gradesec.$gs_quarter.$max_year." where  subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' group by markname order by mid ASC");
						if($query_value->num_rows()>0){
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total from mark".$gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
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
		            	$sum_outof = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gradesec' and mbranch='$gs_branches' group by stuid order by mid ASC");
        		    	$keyvalue=$sum_outof->row_array();
        		    	$sumu_otof= $keyvalue['sum_outof'];
		            	$query_value = $this->db->query("select sum(value) as total,sum(outof) as outof from mark".$gradesec.$gs_quarter.$max_year." where  stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' order by mid ASC");
						if($query_value->num_rows()>0){
							foreach ($query_value->result_array() as $value) {
								if($value['outof'] != 0)
                				{
                        			$conver= ($value['total'] *$percent )/$sumu_otof;
                        			$output[]='<td style="text-align:center;">'.$value['total'].'</td>';
                    			}
                				if($value['outof'] == 0)
                				{
                					$output[]='<td style="text-align:center;">-</td>';
                				}else{
                					$output[]='<td style="text-align:center;"><B>'.number_format((float)$conver,2,'.','').'</B></td>';
                					$average =$conver + $average;
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
				$output[]='<p class="text-center">'.$school_slogan.'!</p>';
			}else{
	    		$output[]='<div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                	<i class="fas fa-check-circle"> </i> Data not found.
            	</div></div>';
			}
		return implode("\r\n",$output);
	}
	function fetch_grade_markresult2($gs_branches,$gs_gradesec,$gs_subject,$gs_quarter,$max_year)
	{
		$output ='';
		if($gs_subject===trim('All'))
		{
			$this->db->where(array('users.academicyear'=>$max_year));
			$this->db->where(array('users.gradesec'=>$gs_gradesec));
			$this->db->where(array('users.branch'=>$gs_branches));
			$this->db->group_by('users.id');
			$this->db->order_by('users.fname');
			$this->db->order_by('users.mname');
			$this->db->order_by('users.lname');
			$query1=$this->db->get('users');
			if($query1->num_rows()>0)
			{
			    $query_name = $this->db->query("select * from school");
				$row_name = $query_name->row_array();
				$school_name=$row_name['name'];
				$school_slogan=$row_name['slogan'];
				$query_subj=$this->db->query("select ma.markname, ma.subname,ma.value,ma.outof,sum(outof) as total_outof from 
				mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."
				as ma cross join users as us where us.id=ma.stuid and ma.academicyear='$max_year'
				and ma.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ma.subname order by ma.subname ASC ");
				foreach ($query_subj->result() as $suvalue) 
				{
					$subject=$suvalue->subname;
					$output.='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp;	Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp;	Subject :<B><u>'.$subject.'</u></B></div></br></h6>';
				    
					$output .='<div  class="table-responsive">
        			<table class="tabler table-borderedr table-hover" style="width:100%;height:92%;page-break-inside:avoid;">
        			<thead>
        			<tr>
            		<th>Name</th><th>Student ID</th>';
            		$markname_query=$this->db->query("select ma.markname,ma.value,ma.outof,sum(outof) as total_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."
            		as ma cross join users as us where us.id=ma.stuid and ma.academicyear='$max_year' and ma.subname='$subject' and ma.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ma.markname order by ma.mid ASC");
            		$evalname_query=$this->db->query("select ev.evname, ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev cross join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");
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
        		    	as m cross join users as us where us.id=m.stuid and us.gradesec='$gs_gradesec' and subname='$subject' and quarter='$gs_quarter' and evaid='$eid' group by stuid order by mid");
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
					foreach ($query1->result() as $row) { 
            			$id=$row->id;
            			$output .='<tr> <td>'.$row->fname.' '.$row->mname.'</td><td>'.$row->username.'</td>';
            			foreach ($markname_query->result() as $mark_name)
            			{
            				$mname_gs=$mark_name->markname;
            				$query_value = $this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year."
            				where  stuid='$id' and subname='$subject' and quarter='$gs_quarter' and markname='$mname_gs' and academicyear='$max_year' order by mid ASC");
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
                			where  stuid='$id' and subname='$subject' and quarter='$gs_quarter' and evaid='$evaid' order by mid ASC");
                			foreach ($query_sum->result() as $sum_value)
                			{
                				if($sum_value->outof != 0)
                				{
                        			$conver= ($sum_value->total *$percent )/$sum_value->outof;
                    			}
                    			if($sum_value->total!=0){
                    				$output .='<td>'.$sum_value->total.'</td>';
                    			}else{
                    				$output .='<td>-</td>';
                    			}
                				if($sum_value->outof == 0)
                				{
                					$output .='<td>-</td>';
                				}else{
                					$output .='<td><B>'.number_format((float)$conver,2,'.','').'</B></td>';
                					$average =$conver + $average;
                				}
                			}
            			}
            			$output .='<td><B>'.number_format((float)$average,2,'.','').'</B></td>';
						$output .='</tr>';
					}
					$output .='</table></div>';
					$output.='<p class="text-center">'.$school_slogan.'!</p>';
				}
			}
			else{
				$output .='<span class="text-danger">Not found.</span>';
			}
		}
		else{
			$this->db->where(array('users.academicyear'=>$max_year));
			$this->db->where(array('users.gradesec'=>$gs_gradesec));
			$this->db->where(array('users.branch'=>$gs_branches));
			$this->db->group_by('users.id');
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
	    		$output .='<div class="alert alert-danger alert-dismissible show fade">
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
					$output.='<h6 class="text-center"><div class="pull-left"><B>'.$school_name.'</B> Academic Year: ' .$max_year.'<small class="time">( '.$gs_branches.')</small></div>';
				    $output.='<div class="pull-right">Grade :<B><u>'.$gs_gradesec.'</u></B>
				    &nbsp;&nbsp; Season :<B><u>'. $gs_quarter.'</u></B> 
				    &nbsp;&nbsp; Subject :<B><u>'.$subject.'</u></B></div></br></h6>';
					
					$evalname_query=$this->db->query("select us.id, ev.evname,ev.eid, ev.percent, sum(ev.percent) as summ_percent from evaluation as ev inner join users as us where us.grade=ev.grade and ev.academicyear='$max_year' and ev.quarter='$gs_quarter' and us.gradesec='$gs_gradesec' and us.branch='$gs_branches' group by ev.evname order by ev.eid ASC");

					$output.='<div class="table-responsive">
	        		<table class="tabler table-borderedr table-hover" style="width:100%;height:92%;page-break-inside:avoid;">
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
		            $output.='<td rowspan="2" class="text-center"><B>100</B></td>';
	            	$output.='</tr><tr>';
	            	foreach ($evalname_query->result_array() as $evalua_name) 
	            	{
	            		$mname_gs=$evalua_name['eid'];
	            		$percent=$evalua_name['percent'];

	            		$queryMvalue1 = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by markname order by mid ASC",FALSE);

		            	foreach ($queryMvalue1->result() as $mark_name)
		            	{
		            		$output.='<td class="text-center">'.$mark_name->outof.'</td>';
		            	}
	            		$sum_outof = $this->db->query("select outof,sum(outof) as sum_outoof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by stuid order by mid ASC",FALSE)
	            		->row();
        		    	$sum_outof= $sum_outof->sum_outoof;
	        		    $output.='<td style="text-align:center;"><B>'.$sum_outof.'</B></td>';
	        		    $output.='<td style="text-align:center;"><B>'.$evalua_name['percent'].'</B></td>';
		            }
	            	$output.='</tr>';
	            	$stuNO=1;
	            	$queryStudent=$this->db->query("select u.id,u.fname, u.lname, u.username, u.mname, u.grade, u.gradesec from users as u where u.academicyear='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$gs_branches' and isapproved='1' and status='Active' group by u.id order by u.fname,u.mname,u.lname ASC ");
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
								foreach ($query_value->result_array() as $value) 
								{
									$markNameStu=$value['markname'];
									$queryStuValue = $this->db->query("select value,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
									if($queryStuValue->num_rows()>0){
										foreach ($queryStuValue->result_array() as $kevalue) {
											$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
										}
									}else{
										$output.='<td class="text-danger" style="text-align:center;">NG</td>';
									}
			            		}
			            	}else{
			            		$output.='<td style="text-align:center;">-</td>';
			            	}
			            	/*query Total*/
			            	$sum_outof = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by stuid order by mid ASC",FALSE)
			            	->row();
	        		    	$sumu_otof= $sum_outof->sum_outof;
			            	$query_value = $this->db->query("select sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  stuid='$id' and subname='$subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' order by mid ASC");
							if($query_value->num_rows()>0){
								foreach ($query_value->result_array() as $value) 
								{
									if($value['outof'] != 0 || $value['outof'] > 0 ||$sumu_otof !='')
		            				{
		                    			$conver= ($value['total'] *$percent )/$sumu_otof;
		                    			$output.='<td style="text-align:center;">'.$value['total'].'</td>';
		                    			$output.='<td style="text-align:center;"><B>'.number_format((float)$conver,2,'.','').'</B></td>';
		            					$average =$conver + $average;
		                			}else{
		                				$output.='<td style="text-align:center;">-</td>';
		                			}
		            			}
			            	}else{
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
			$querySingleSubject=$this->db->query("select * from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where academicyear='$max_year' and subname='$gs_subject' and mgrade='$gs_gradesec' and mbranch='$gs_branches' ");
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
	            	foreach ($queryMvalue->result_array() as $mark_name) {
	            		$output.='<td class="text-center">'.$mark_name['outof'].'</td>';
	            	}
            		$sum_outof = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by stuid order by mid ASC");
        		   $sum_outof = $this->db->query("select outof,sum(outof) as sum_outoof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by stuid order by mid ASC",FALSE)
	            		->row();
        		    	$sum_outof= $sum_outof->sum_outoof;
	        		    $output.='<td style="text-align:center;"><B>'.$sum_outof.'</B></td>';
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
							foreach ($query_value->result_array() as $value) {
								$markNameStu=$value['markname'];
								$queryStuValue = $this->db->query("select value,sum(value) as total from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' and markname='$markNameStu' group by markname order by mid ASC");
								if($queryStuValue->num_rows()>0){
									foreach ($queryStuValue->result_array() as $kevalue) {
										$output.='<td style="text-align:center;">'.$kevalue['value'].'</td>';
									}
								}else{
									$output.='<td class="text-danger" style="text-align:center;">NG</td>';
								}
		            		}
		            	}else{
		            		$output.='<td style="text-align:center;">-</td>';
		            	}
		            	/*query Total*/
		            	$sum_outof = $this->db->query("select outof,sum(outof) as sum_outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mgrade='$gs_gradesec' and mbranch='$gs_branches' group by stuid order by mid ASC");
        		    	$keyvalue=$sum_outof->row_array();
        		    	$sumu_otof= $keyvalue['sum_outof'];
		            	$query_value = $this->db->query("select sum(value) as total,sum(outof) as outof from mark".$gs_branches.$gs_gradesec.$gs_quarter.$max_year." where  stuid='$id' and subname='$gs_subject' and quarter='$gs_quarter' and evaid='$mname_gs' and mbranch='$gs_branches' order by mid ASC");
						if($query_value->num_rows()>0){
							foreach ($query_value->result_array() as $value) {
								if($value['outof'] != 0 || $value['outof'] > 0 ||$sumu_otof !='')
	            				{
	                    			$conver= ($value['total'] *$percent )/$sumu_otof;
	                    			if($value['total']>0){
	                    				$output.='<td style="text-align:center;">'.$value['total'].'</td>';
	                    				$output.='<td style="text-align:center;"><B>'.number_format((float)$conver,2,'.','').'</B></td>';
	                    			}else{
	                    				$output.='<td class="text-danger" style="text-align:center;">NG</td>';
	                    				$output.='<td class="text-danger" style="text-align:center;">NG</td>';
	                    			}
	            					$average =$conver + $average;
	                			}else{
	                				$output.='<td style="text-align:center;">-</td>';
	                			}
	            			}
		            	}else{
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
		$output ='<option> All </option>';
			foreach ($query->result() as $row) { 
				$output .='<option value="'.$row->Subj_name.'">'.$row->Subj_name.'</option>';
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
		$query = $this->db->query(" Select * from users where academicyear='$max_year' and status='Active' and isapproved='1' and usertype ='Student' and gradesec='$gradesec' and branch='$branch' order by fname,mname ASC ");
		$output .='<div class="table-responsive">
        	<table class="table table-bordered table-hover" style="width:100%;">
        		<thead>
        		<tr>
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
		foreach ($query->result() as $fetch_student) {
			$output.='<input type="hidden" id="stuid" 
			name="stuid_result" value="'.$fetch_student->id.'"> ';
			$output.='<tr class="'.$fetch_student->id.'">
			<td><input type="text" onkeyup="chkMarkValue()" name="markvalue_result" id="resultvalue" class="form-control markvalue_result">
			 </td>';
			$output.='<td>'.$fetch_student->fname.' '.$fetch_student->mname.' '.$fetch_student->lname.'</td>';
			$output.='<td>'.$gradesec.'</td>';
			$output.='<td>'.$branch.'</td>';
			$output.='<td>'.$subject.'</td>';
			$output.='<td>'.$quarter.'</td>';
			$output.='<td>'.$assesname.'</td>';
			$output.='<td>'.$percentage.'</td></tr>';
		}
		$output .='</table></div>';
		$output .='<button type="submit" id="SaveResult" class="btn btn-success">Save Result </button>';
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
		$querystu=$this->db->get('mark'.$markGradeSec.$quarter.$academicyear);
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
	                $output.='<td><select class="form-control bssubOrderJo" required="required" name="kgsubOrder" id="bssubOrder">
	                 <option class="kgCatOrder" id="'.$bsnames->bscategory.'" value="'.$bsnames->bcorder.'">'.$bsnames->bcorder.'</option>';
 						for ($i=1; $i <=$allbS ; $i++) { 
 							$output.='<option class="bsJoss" id="'.$bsnames->bscategory.'" value="'.$i.'">'.$i.'</option>';
 						}
 						$output.='</select></td>';

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
		$query=$this->db->query("select *, GROUP_CONCAT(grade) as grade_bsname from basicskill where academicyear='$max_year' group by bsname order by bsname ASC ");
		$output='';
		if($query->num_rows()>0){
			$output='<div class="card">
	        <div class="card-header">
	            <h4>Basic Skills Names</h4>
	        </div>
			<div class="table-responsive">
	        <table class="table table-stripped table-hover" style="width:100%;">
	            <thead>
	                <tr>
	                    <th>No.</th>
	                    <th>Basic Skill Name</th>
	                    <th>Grade</th>
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
	                <a href="#" class="deletebaskill" value="'.$bsnames->bsname.'"> <span class="text-danger">Delete</span> 
	                </a> <div class="bullet"></div>
	                <a href="#" class="editbaskill" value="'.$bsnames->bsname.'"> <span class="text-info">Edit</span> 
	                </a>
	                </div> </td>
	                <td>'.$bsnames->grade_bsname.'</td>
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
	function import_bs($stuid,$quarter,$bsname,$max_year,$value,$data){
		$this->db->where('academicyear',$max_year);
		$this->db->where('stuid',$stuid);
		$this->db->where('quarter',$quarter);
		$this->db->where('bsname',$bsname);
		$query=$this->db->get('basicskillvalue');

		$queryBsType=$this->db->query("select * from bstype where bstype='$value' and  academicyear='$max_year' group by bstid ");
		if($queryBsType->num_rows() > 0){
			if($query->num_rows() > 0){
				$this->db->where('academicyear',$max_year);
				$this->db->where('stuid',$stuid);
				$this->db->where('quarter',$quarter);
				$this->db->where('bsname',$bsname);
				$this->db->set('value',$value);
				$queryy=$this->db->update('basicskillvalue');
			}else{
				$queryy=$this->db->insert('basicskillvalue',$data);
			}
			if($queryy){
				return true;
			}else{
				return false;
			} 
		}
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
		if($gs_subject==='All'){
			$queryStudent=$this->db->query("select lname,username, fname, mname,branch, ma.subname,ma.markname, ma.quarter,ma.value, ma.outof,ma.zeromarkinfo,gradesec,id from users as us cross join mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." as ma where us.academicyear='$max_year' and us.usertype='Student' and us.status='Active' and us.isapproved='1' and ma.value='0' and us.id=ma.stuid and us.branch='$gs_branches' and ma.quarter='$max_quarter' and us.gradesec='$gs_gradesec' ");
		}else{
			$queryStudent=$this->db->query("select fname,mname, username, lname, branch,ma.subname, ma.markname, ma.quarter,ma.value, ma.outof,ma.zeromarkinfo,gradesec,id from users as us cross join mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." as ma where us.academicyear='$max_year' and us.usertype='Student' and us.status='Active' and us.isapproved='1' and ma.value='0' and us.id=ma.stuid and us.branch='$gs_branches' and ma.quarter='$max_quarter' and us.gradesec='$gs_gradesec' and ma.subname='$gs_subject' ");
		}
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
		if($queryStudent->num_rows()>0){
			foreach ($queryStudent->result() as $gradeValue) {
				$gradesec=$gradeValue->gradesec;
				$stuid=$gradeValue->id;
				$output .='<tr><td>'.$no.'.</td>
				<td>'.$gradeValue->fname.' '.$gradeValue->mname.' '.$gradeValue->lname.'</td>';
				$output .='<td>'.$gradeValue->username.'</td>';
				$output .='<td>'.$gradeValue->subname.'</td>';
				$output .='<td>'.$gradeValue->markname.'</td>';
				if($gradeValue->zeromarkinfo > $gradeValue->outof){
					$output .='<td class="text-danger">OW('.$gradeValue->zeromarkinfo.')</td>';
				}else{
					$output .='<td class="text-warning">NG('.$gradeValue->zeromarkinfo.')</td>';
				}
				$output .='<td><span class="text-danger"><h4>'.$gradeValue->value.'</h4></span></td></tr>';	
				$no++;
			}
		}
		if($gs_subject==='All'){
			$markname_query=$this->db->query("select ma.evaid, ma.markname,ma.subname, ma.mid, ma.value, ma.outof,sum(outof) as total_outof from mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." as ma where ma.academicyear='$max_year' and  ma.quarter='$max_quarter' and ma.mgrade='$gs_gradesec' and ma.mbranch='$gs_branches' group by ma.markname,subname order by ma.mid ASC ");
			foreach ($markname_query->result() as $markValue) {
				$markname=$markValue->markname;
				$subject=$markValue->subname;
				$queryStudent2=$this->db->query("select lname,username, fname, mname, branch, ma.subname,ma.markname, ma.quarter,ma.value, ma.outof, ma.zeromarkinfo, gradesec,id from users as us cross join mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." as ma where us.academicyear='$max_year' and us.usertype='Student' and us.status='Active' and us.isapproved='1' and us.branch='$gs_branches' and us.gradesec='$gs_gradesec' and ma.markname='$markname' and  ma.subname='$subject' and ma.quarter='$max_quarter' and us.id not in(select stuid from mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." where markname='$markname' and  subname='$subject' and quarter='$max_quarter' group by markname )  group by id");
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
				$queryStudent2=$this->db->query("select lname,username, fname, mname, branch, ma.subname,ma.markname, ma.quarter,ma.value, ma.outof, ma.zeromarkinfo, gradesec,id from users as us cross join mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." as ma where us.academicyear='$max_year' and us.usertype='Student' and us.status='Active' and us.isapproved='1' and us.branch='$gs_branches' and us.gradesec='$gs_gradesec' and ma.markname='$markname' and  ma.subname='$gs_subject' and ma.quarter='$max_quarter' and us.id not in(select stuid from mark".$gs_branches.$gs_gradesec.$max_quarter.$max_year." where markname='$markname' and  subname='$gs_subject' and quarter='$max_quarter' group by markname )  group by id");
			
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
		$output ='<div class="table-responsive">
      	<table class="table table-bordered" id="tableExport" style="width:100%;">
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
    	return $output;
	}
	function fetch_gradephone($max_year,$grade,$branch)
	{
		$query_student=$this->db->query(" Select fname,mname,lname,gender,grade,gradesec, branch,username,father_mobile,mobile from users where grade='$grade' and status='Active' and academicyear='$max_year' and branch='$branch' and isapproved='1' order by fname,mname ASC ");
		$output ='
      	<div class="table-responsive">
      	<table class="table table-bordered" id="tableExport" style="width:100%;">
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
    	return $output;
	}
	function gener_report($gradesec,$branch,$max_year){
		$this->db->where('gradesec',$gradesec);
		$this->db->where('branch',$branch);
		$this->db->where('academicyear',$max_year);
		$this->db->order_by('fname','ASC');
		$query = $this->db->get('users');
		$output =' <div class="table-responsive">
        <table class="table table-bordered" id="tableExport" style="width:100%;"> 
         <tbody><tr><td><B>Name</B></td><td><B>Grade</B></td><td><B>Branch</B></td><td><B>Gender</B></td></tr>';
		foreach ($query->result() as $row) {
			$output .='<tr>';
    		$output .='<td>'.$row->fname.' '.$row->mname.' 
    		'. $row->lname.'</td>';
    		$output .='<td>'.$row->gradesec.'</td>';
    		$output .='<td>'.$row->branch.'</td>';
    		$output .='<td>'.$row->gender.'</td>';
      		$output .='</tr>'; 
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
		$this->db->order_by('fname','ASC');
		$query = $this->db->get('users');
		$output =' <div class="table-responsive">
        <table class="table table-bordered" id="tableExport" style="width:100%;"> 
         <tbody><tr><td><B>Name</B></td><td><B>Grade</B></td><td><B>Branch</B></td><td><B>Gender</B></td></tr>';
		foreach ($query->result() as $row) {
			$output .='<tr>';
    		$output .='<td>'.$row->fname.' '.$row->mname.' 
    		'. $row->lname.'</td>';
    		$output .='<td>'.$row->gradesec.'</td>';
    		$output .='<td>'.$row->branch.'</td>';
    		$output .='<td>'.$row->gender.'</td>';
      		$output .='</tr>'; 
		}
		$output .=' </tbody> </table> </div>';
		$query2 = $this->db->query("SELECT *, CONCAT('Grade') as Yearlevel, COUNT(*) AS studentcount, sum(case when Gender = 'Male' or Gender='M' then 1 else 0 end) AS malecount, sum(case when Gender = 'Female' or Gender='F' then 1 else 0 end) AS femalecount FROM users where grade='$gradesec' and academicyear='$max_year' and branch='$branch' GROUP BY academicyear ORDER BY fname ASC");
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
	function examschedule($max_year,$max_quarter){
		
	}
	function top_rank($max_year,$quarter,$gradesec,$branch,$top){
		$i=1; 
		$output='';
	    if(trim($gradesec)===trim('All')){
	    	$output='<div class="table-responsive"> 
	        <table class="table table-bordered table-hover" style="width:100%;">
	        <thead> <tr> <th>Student Name</th> <th>Grade</th>
	        <th>Quarter</th><th>Average</th> <th>Rank</th> </tr> </thead>';
	    	$query_student=$this->db->query("select * from users as us where us.status='Active' and us.usertype='Student' and us.academicyear='$max_year' and us.isapproved='1' and us.branch='$branch' group by gradesec ");
	    	$output='';
	    	if($query_student->num_rows()>0){
		    	foreach ($query_student->result() as $keResult) {
		    		$gradesec_each=$keResult->gradesec;
		    		$output.='<div class="table-responsive"> 
			        <table class="table table-bordered table-hover" style="width:100%;">
			        <thead> <tr> <th>Student Name</th> <th>Student ID</th> <th>Grade</th>
			        <th>Quarter</th><th>Average</th> <th>Rank</th> </tr> </thead>';
			        if(trim($top)===trim('All')){
				        $query_rank=$this->db->query("select s.username,s.profile,s.lname, s.fname,s.mname, s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec_each' and rc.rpbranch='$branch' and rc.quarter= '$quarter' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec_each' and rc.rpbranch='$branch' group by stuid order by stuRank ASC ");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec_each' and rc.rpbranch='$branch' and letter!='A' and onreportcard='1' and academicyear='$max_year' group by subject ");
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
						$query_rank=$this->db->query("select s.lname, s.username, s.profile,s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec_each' and rc.rpbranch='$branch' and rc.quarter= '$quarter' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.quarter= '$quarter' and rc.academicyear='$max_year' and rc.rpbranch='$branch' and s.branch='$branch' and rc.grade='$gradesec_each' group by stuid order by stuRank ASC limit $top");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec_each' and rpbranch='$branch' and letter!='A' and onreportcard='1' and academicyear='$max_year' group by subject ");
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
		        <table class="table table-bordered table-hover" style="width:100%;">
		        <thead> <tr> <th>Student Name</th> <th>Student ID</th> <th>Grade</th>
		        <th>Quarter</th><th>Average</th> <th>Rank</th> </tr> </thead>';
			    if(trim($top)===trim('All')){
					$query_rank=$this->db->query("select s.lname, s.profile, s.username, s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.rpbranch='$branch' and rc.quarter= '$quarter' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec' group by stuid order by stuRank ASC");
					$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and academicyear='$max_year' group by subject ");
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
					$query_rank=$this->db->query("select s.lname, s.username, s.profile,s.fname,s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.rpbranch='$branch' and rc.quarter= '$quarter' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and rc.grade='$gradesec' group by stuid order by stuRank ASC limit $top");
					$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and rpbranch='$branch' and letter!='A' and onreportcard='1' and academicyear='$max_year' group by subject ");
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
    	return $output;
	}
	function topGradeTopRank($max_year,$quarter,$gradesec,$branch,$top){
		$i=1; 
		$output='';
		$queryStudent=$this->db->query("select gradesec from users where academicyear='$max_year' and status='Active' and isapproved='1' and branch='$branch' and grade='$gradesec' group by gradesec ");
		if($queryStudent->num_rows()>0){
			$output='<div class="table-responsive"> 
	        <table class="table table-bordered table-hover" style="width:100%;">
	        <thead> <tr> <th>Student Name</th> <th>Grade</th>
	        <th>Quarter</th><th>Average</th> <th>Rank</th> </tr> </thead>';
	    
	    	$output='<div class="table-responsive"> 
	        <table class="table table-bordered table-hover" style="width:100%;">
	        <thead> <tr> <th>Student Name</th> <th>Student ID</th> <th>Grade</th>
	        <th>Quarter</th><th>Average</th> <th>Rank</th> </tr> </thead>';
		    if(trim($top)===trim('All')){
		    	foreach ($queryStudent->result() as $gValue) {
		    		$gradesecc=$gValue->gradesec;
					$query_rank=$this->db->query("select s.username, s.profile,s.lname, s.fname, s.mname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesecc.$max_year." as rc cross join users as s where s.id=rc.stuid and s.grade='$gradesec' and rc.rpbranch='$branch' and rc.quarter= '$quarter' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesecc.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and s.grade='$gradesec' group by stuid order by stuRank ASC");
					$count_subject=$this->db->query("select * from reportcard".$gradesecc.$max_year." as rc cross join users as us where us.grade='$gradesec' and rc.rpbranch='$branch' and rc.letter!='A' and us.gradesec=rc.grade and rc.onreportcard='1' and rc.academicyear='$max_year' group by subject ");
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
			}else{
				foreach ($queryStudent->result() as $gValue) {
		    		$gradesecc=$gValue->gradesec;
					$query_rank=$this->db->query("select s.username, s.profile, s.fname,s.fname,s.lname, s.mname, s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesecc.$max_year." as rc cross join users as s where s.id=rc.stuid and s.grade='$gradesec' and rc.rpbranch='$branch' and rc.quarter= '$quarter' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesecc.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.rpbranch='$branch' and rc.quarter= '$quarter' and rc.academicyear='$max_year' and s.branch='$branch' and s.grade='$gradesec' group by stuid order by stuRank ASC limit $top");
					$count_subject=$this->db->query("select * from reportcard".$gradesecc.$max_year." as rc cross join users as us where us.grade='$gradesec' and rc.rpbranch='$branch' and rc.letter!='A' and us.gradesec=rc.grade and rc.onreportcard='1' and rc.academicyear='$max_year' group by subject ");
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
	    	$query_student=$this->db->query("select * from users as us cross join rank_allowed_grades as rg where us.status='Active' and us.usertype='Student' and us.academicyear='$max_year' and us.isapproved='1' and us.gradesec='$gradesec' and rg.grade=us.grade group by gradesec ");
	    	$output='';
	    	if($query_student->num_rows()>0){
		    	foreach ($query_student->result() as $keResult) {
		    		//$gradesec_each=$keResult->gradesec;
		    		$output.='<div class="table-responsive"> 
			        <table class="table table-bordered table-hover" style="width:100%;">
			        <thead> <tr> <th>Student Name</th> <th>Student ID</th> <th>Branch</th> <th>Grade</th>
			        <th>Quarter</th><th>Average</th> <th>Rank</th> </tr> </thead>';
			        if(trim($top)===trim('All')){
				        $query_rank=$this->db->query("select s.branch,s.username, s.profile, s.fname,s.mname,s.lname, s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.quarter= '$quarter' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.quarter= '$quarter' and rc.academicyear='$max_year'  and rc.grade='$gradesec' group by stuid order by stuRank ASC ");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter!='A' and onreportcard='1' and academicyear='$max_year' group by subject ");
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
						$query_rank=$this->db->query("select s.username, s.branch, s.profile, s.fname,s.mname,s.lname,s.gradesec, sum(total) as Average,FIND_IN_SET(sum(total), (select GROUP_CONCAT(rank order by rank DESC )from (select sum(total) as rank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.grade='$gradesec' and rc.quarter= '$quarter' group by stuid order by rank ASC) sm)) as stuRank from reportcard".$gradesec.$max_year." as rc cross join users as s where s.id=rc.stuid and rc.quarter= '$quarter' and rc.academicyear='$max_year' and rc.grade='$gradesec' group by stuid order by stuRank ASC limit $top");
						$count_subject=$this->db->query("select * from reportcard".$gradesec.$max_year." where grade='$gradesec' and letter!='A' and onreportcard='1' and academicyear='$max_year' group by subject ");
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
	function mark_statistics($max_year,$branch,$grade,$subject,$quarter,$less_than,$greater_than)
	{
		$query=$this->db->query("select us.fname, us.mname,us.lname,us.profile, us.gradesec,us.username, rc.total,rc.subject,rc.quarter,rc.total from reportcard".$max_year." as rc cross join  users as us where us.id=rc.stuid and rc.quarter='$quarter' and rc.subject='$subject' and rc.academicyear='$max_year' and us.branch='$branch' and us.grade ='$grade' and rc.total <='$less_than' and rc.total >='$greater_than' group by rc.stuid order by rc.total DESC ");
		$output='';
		if($query->num_rows()>0){
			$query_name = $this->db->query("select * from school");
     		$row_name = $query_name->row();
      		$school_name=$row_name->name;
			$output.='<h4 class="text-center"><b><u>'.$school_name.' Mark statistics for Grade:'.$grade.' Season:'.$quarter.' Subject:'.$subject.' </u></b></h4>';
			$output .='<div class="table-responsive"> 
            <table class="table table-bordered table-hover" style="width:100%;">
            <thead> <tr> <th>No.</th>
            <th>Student Name</th> <th>Student ID</th> <th>Grade</th>
            <th>Average</th> </tr> </thead>';
            $no=1;
    		foreach ($query->result() as $toprank) {
				$output.='<tr><td>'.$no.'.</td>
			  	<td><img src="'.base_url().'/profile/'.$toprank->profile.'"style="width: 33px;height: 33px;border-radius: 3em;">'.' '.$toprank->fname.' '.$toprank->mname.' '.$toprank->lname.'
			  	</td>
			  	<td>'.$toprank->username.'</td>
			  	<td>'.$toprank->gradesec.'</td>
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
            <table class="table table-bordered table-md">
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
	function fetch_rank_policy($max_year){
		$query=$this->db->query("SELECT *, GROUP_CONCAT(grade) as gradess from rank_allowed_grades where academicyear ='$max_year' and allowed='1' GROUP BY allowed ORDER BY grade ASC");
		$output='<div class="table-responsive">
            <table class="table table-bordered table-md">
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
		$this->db->select_max('unique_id');
		$query=$this->db->get('users');
		$output='';
		foreach ($query->result() as $alue) {
			$output.='<small><span class="text-info">Last Student ID is '.$alue->unique_id.'.</span></small>';
		}
		return $output;
	}
	function check_grade_markprogress($branch,$gs_gradesec,$gs_quarter,$max_year)
	{
		$output='';
		$query=$this->db->query("select u.username,u.fname, u.mname,s.Grade ,s.Subj_name,u.grade,u.id from users as u cross JOIN subject as s where s.Grade=u.grade and u.academicyear='$max_year' and u.status='Active' and u.isapproved='1' and u.usertype='Student' and s.Academic_Year='$max_year' and u.gradesec='$gs_gradesec' and u.branch='$branch' group by s.Subj_name order by s.Subj_name ASC");
		if($query->num_rows()>0){
			$output .='<div class="table-responsive">
	        <table class="table table-striped">
	        <tr>
	          	<th>Subject Name</th>
	          	<th>Teacher</th>
	            <th>Grade</th> 
	            <th>Assesment Name</th>           
	            <th>Added mark(%)</th>
	            <th>Remaining mark(%)</th>
			</tr>';
			foreach ($query->result() as $progress_value) {
				$subject=$progress_value->Subj_name;
				$grade=$progress_value->grade;
				$staff=$progress_value->username;
				$stuid=$progress_value->id;
				$output.='<tr><td>'.$progress_value->Subj_name.'</td>';
				$queryPlcament=$this->db->query("select st.staff, us.fname, us.mname from users as us cross join  staffplacement as st where st.grade='$gs_gradesec' and st.academicyear='$max_year' and st.staff=us.username and st.subject='$subject' and us.usertype!='Student' and us.status='Active' and us.isapproved='1' and us.branch='$branch' ");
				if($queryPlcament->num_rows()>0){
					foreach ($queryPlcament->result() as $pvalue) {
						$output.='<td>'.$pvalue->fname.' '.$pvalue->mname. '</td>';
					}
				}else{
					$output.='<td><span class="text-danger">No Placement</span></td>';
				}
				$output.='<td>'.$gs_gradesec.'</td>';
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
		}else{
			$output .='<div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No subject found.
            </div></div>';
		}
		$output .='</table></div>';
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
	function filterSubject4Analysis($mybranch,$gradesec,$max_year,$quarter)
	{
		$query=$this->db->query("select su.Subj_name,su.Subj_Id from subject as su cross join users as u where u.grade=su.Grade and u.academicyear='$max_year' and u.status='Active' and u.isapproved='1' and u.usertype='Student' and su.Academic_Year='$max_year' and u.gradesec='$gradesec' and u.branch='$mybranch' group by Subj_name order by Subj_name ASC ");
		$output='<select class="form-control selectric" required="required" name="branch" id="subevaluationanalysis">';
		foreach ($query->result() as $evavalue) {
			$output.='<option value='.$evavalue->Subj_Id.'>'.$evavalue->Subj_name.'</option>';	
		}
		$output.='</select>';
		return $output;
	}
	function fetchanalysis($branch,$gradesec,$quarter,$evaluation,$max_year)
	{
		$output='';
		$querySubject=$this->db->query("select u.username,u.fname, u.mname,s.Grade ,s.Subj_name,u.grade,u.id from users as u cross JOIN subject as s where s.Grade=u.grade and u.academicyear='$max_year' and u.status='Active' and u.isapproved='1' and u.usertype='Student' and u.branch='$branch' and s.Academic_Year='$max_year' and u.gradesec='$gradesec' group by s.Subj_name order by s.Subj_name ASC");
		if($querySubject->num_rows()>0){
			$queryac = $this->db->query("select max(year_name) as ay from academicyear");
    		$rowac = $queryac->row();
    		$yearname=$rowac->ay;
    		$queryev = $this->db->query("select evname from evaluation where  eid='$evaluation' and academicyear='$max_year' ");
    		$rowev = $queryev->row();
    		$evname=$rowev->evname;
			$output.='<div class="text-center"><h4><u><b>'.$yearname.'E.C</b></u> Academic Year <u><b>'.$quarter.' '.$evname.'</b></u> Result Analysis for Grade <u><b>'.$gradesec.'</b></u></h4></div>';
			$output .=' <div class="table-responsive">
        	<table class="table table-bordered table-hover" id="tableExport" style="width:100%;">
	        <thead>
	        <tr>
          	<th>No.</th>
          	<th>Student Name</th>';
          	$no=1;
			foreach ($querySubject->result() as $subvalue) {
				$output.='<th>'.$subvalue->Subj_name.'</th>';
			}
			$output.='<th>Total</th><th>Rank</th></tr></thead>';
			$queryStudent=$this->db->query("select * from users where academicyear='$max_year' and status='Active' and isapproved='1' and usertype='Student' and branch='$branch' and gradesec='$gradesec' group by id  order by fname ASC");
			$finalresult=0;
			foreach ($queryStudent->result() as $studentvalue) {
				$grade=$studentvalue->grade;
				$stuid=$studentvalue->id;
				$querySubject=$this->db->query("select * from subject where Grade='$grade' and Academic_Year='$max_year' order by Subj_name ASC");
				$output.='<tr><td>'.$no.'</td>';
				$allSubTotal=0;
				$output.='<td>'.$studentvalue->fname.' '.$studentvalue->mname. '</td>';
				foreach ($querySubject->result() as $subvalue) {
					$subject=$subvalue->Subj_name;
					$queryMarkOutof = $this->db->query("select sum(value) as total,sum(outof) as outof from mark".$branch.$gradesec.$quarter.$max_year." where subname='$subject' and quarter='$quarter' and evaid='$evaluation' group by stuid order by mid ASC");
    				$rowck=$queryMarkOutof->row_array();
    				$totaloutof=$rowck['outof'];

					$queryEvaluation=$this->db->query("select * from evaluation where quarter='$quarter' and eid='$evaluation' and academicyear='$max_year' and grade='$grade' ");
					if($queryEvaluation->num_rows()>0){
						foreach ($queryEvaluation->result() as $evavalue) {
							$percent=$evavalue->percent;
							$queryMark = $this->db->query("select sum(value) as total,sum(outof) as outof from mark".$branch.$gradesec.$quarter.$max_year." where  stuid='$stuid' and subname='$subject' and quarter='$quarter' and evaid='$evaluation' order by mid ASC");
							if($queryMark->num_rows()>0){
								foreach ($queryMark->result() as $mvalue) {
									$totalmark=$mvalue->total;
									
									if($totalmark>0){
										$finalresult=($percent*$totalmark)/$totaloutof;
										$output.='<td>'.number_format((float)$finalresult,2,'.','').'</td>';
									}else{
									    $finalresult=0;
										$output.='<td>-</td>';
									}
								}
							}else{
								$output.='<td> <span class="text-danger">- </span></td>';
							}
						}
						$allSubTotal= $allSubTotal+ $finalresult;
					}
				}
				$output.='<td>'.number_format((float)$allSubTotal,2,'.','').'</td>';
				/*Rank calculation starts*/
				$queryRank=$this->db->query("select sum(value),FIND_IN_SET(sum(value), (select GROUP_CONCAT(rank order by rank DESC)from (select sum(value) as rank from mark".$branch.$gradesec.$quarter.$max_year." as m cross join users as ur where m.evaid='$evaluation' and ur.id=m.stuid and ur.gradesec='$gradesec' and m.quarter='$quarter' and ur.academicyear='$max_year' and ur.status='Active' and ur.isapproved='1' and ur.usertype='Student' and ur.branch='$branch' group by ur.gradesec , m.stuid) sm)) as stuRank from mark".$branch.$gradesec.$quarter.$max_year." as m cross join users as ur where ur.academicyear='$max_year' and ur.status='Active' and ur.isapproved='1' and ur.usertype='Student' and ur.branch='$branch' and ur.id=m.stuid and m.stuid='$stuid' and m.quarter= '$quarter' and m.academicyear='$max_year' and ur.gradesec='$gradesec' and evaid='$evaluation' group by ur.gradesec ");
				foreach ($queryRank->result() as $rankvalue) {
					$output.='<td>'.$rankvalue->stuRank.'</td>';
				}
				$output.='</tr>';
				$no++;
			}
			$output.='</table></div>';
			$output.='<div class="text-center">Home Room Teachers Name:_______________________</div>';
		}else{
			$output .='<div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No subject found.
            </div></div>';
		}
		return $output;
	}
	function fetchSubjectMarkAnalysis($branch,$gradesec,$quarter,$subject,$max_year)
	{
		$output='';
		$querySubject=$this->db->query("select * from subject where Subj_Id='$subject' and Academic_Year='$max_year' ");
		$rowSubj=$querySubject->row();
		$SubName=$rowSubj->Subj_name;
		$queryMark=$this->db->query("select sum(m.value) as total, m.value,us.fname,us.mname from users as us cross join mark".$branch.$gradesec.$quarter.$max_year." as m where us.gradesec='$gradesec' and m.quarter='$quarter' and us.branch='$branch' and us.academicyear='$max_year' and m.academicyear='$max_year' and m.subname='$SubName' and us.id=m.stuid and us.usertype='Student' group by m.stuid order by total DESC ");
		if($queryMark->num_rows()>0){
			$output.='<div class="text-center"><h4><u><b>'.$max_year.'E.C</b></u> Academic Year <u><b>'.$quarter.' '.$SubName.'</b></u> Result Analysis for Grade <u><b>'.$gradesec.'</b></u></h4></div>';
			$output .='<div class="table-responsive">
        	<table class="table table-bordered table-hover" id="tableExport" style="width:100%;">
	        <thead>
	        <tr>
          	<th>Student Name.</th>
          	<th>Total</th></tr> </thead>';
			foreach ($queryMark->result() as $markValue) {
				$mValue=$markValue->total;
				$output.='<tr><td>'.$markValue->fname.' '.$markValue->mname.'</td>';
				$output.='<td>'.$mValue.'</td></tr>';
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
	function check_detainedstudent($grade,$max_year){
		$query=$this->db->query("select pp.average as avpp, u.gradesec from users as u cross join promotion_policy as pp  where pp.grade=u.grade and u.grade='$grade' and u.academicyear='$max_year'  and pp.academicyear='$max_year' group by u.id ");
		$total_detained=0;
		/*foreach ($query->result() as $kalue) {
			$gradesec=$kalue->gradesec;

			$count_subject=$this->db->query("select * from reportcard where grade='$gradesec' and academicyear='$max_year' group by subject ");
				$total_subject=$count_subject->num_rows();
			$count_average=$this->db->query("select *,sum(total) as yave from reportcard where grade='$gradesec' and letter!='A' and onreportcard='1' and academicyear='$max_year' group by stuid ");
				$kaluT=$count_average->row();
				$kalueV=$kaluT->yave;
				$yearlyaverage=($kalueV)/$total_subject;
				$promotion_average=$kalue->avpp;
			if($yearlyaverage < $promotion_average){
				$total_detained= $total_detained + 1;
			}
		}*/
		return '<div class="alert alert-info alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-triangle"> </i><B> 0</B> Student(s) are Detained in grade <B>'.$grade.'</B>.
                <a href="#"> View</a>
            </div></div>';
	}
	function student_registration($branch,$grade,$max_year){
		$this->db->where('usertype =','Student');
		$this->db->where('grade',$grade);
		$this->db->where('branch',$branch);
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->order_by('fname','ASC');
		$query = $this->db->get('users');
		$output ='';
		$chkMark=$this->db->query('select * from mark where academicyear="$max_year" ');
		if($query->num_rows()>0){
	        $output .='<div class="row">
			    <div class="col-md-8"></div>
			    <div class="col-md-2 pull-right"><button class="btn btn-outline-success" id="promoteStudent"> Promote</button></div>
			    <div class="col-md-2 pull-right"><button class="btn btn-outline-danger" id="detainedStudent"> Detained</button></div>
		    </div>
	        <div class="table-responsive">
	        <table class="table table-bordered table-hover" id="tableExport" style="width:100%;">
	        <thead>
	        <tr>
	          	<th>No.</th>
	          	<th>Student Name</th>
	          	<th>Grade</th>
	          	<th>Branch</th>
	          	<th><p>Select All</p> <input type="checkbox" class="" id="selectall" onClick="selectAll()"> </th>
	        </tr>
	        </thead>
	        <tbody>';
	        $no=1;
	        $chkYear=$max_year + $no;
	        $queryMaxYear=$this->db->query("select max(academicyear) as maxYear from users where usertype='Student' ");
	        $queryYearRow=$queryMaxYear->row();
	        $maxYear=$queryYearRow->maxYear;

	        foreach ($query->result() as $row) {
		        $output .='<tr class="clearRegs'.$row->id.'"> 
		        <td>'.$no.'.</td>
		        <td>'.$row->fname.' '.$row->mname.' '.$row->lname.'</td>
		        <td>'.$row->grade.'</td>
		        <td>'.$row->branch.'</td>';
		        $queryStudentChk=$this->db->query("select * from users where unique_id='".$row->unique_id."' and academicyear = '".$chkYear."' ");

		        $queryMarkChk=$this->db->query("select * from mark where stuid='".$row->id."' and academicyear = '".$max_year."' ");

		        if($queryStudentChk->num_rows()>0){
		        	$output.='<td><span class="text-success" title="Registered"> <i class="fas fa-check-circle"> </i> </span> </td>';
		        }else{
		        	if($max_year===$maxYear && $queryMarkChk->num_rows()<1){
		        		$output.='<td> <button class="btn btn-default" id="clearRegistration" value="'.$row->id.'"> Clear</button> </td>
		        		<input type="hidden" id="clearAcademicYear" value="'.$row->academicyear.'" >';
		        	}else{
		        		$output.='<td> <input type="checkbox" class="" name="stuId[ ]" id="stuIdList" value="'.$row->id.'" ></td>';
		        	}
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
	function studentPromotionPromote($stuid,$max_year){
		$this->db->where('id',$stuid);
		$this->db->where('academicyear',$max_year);
		$query=$this->db->get('users');
		$output='';
		foreach ($query->result() as $studentList) {
			$grade=$studentList->grade;
			if($grade==='Nursery'){
				$NextGrade='LKG';
				$data=array(
					'username'=>$studentList->username,
					'usertype'=>$studentList->usertype,
					'fname'=>$studentList->fname,
					'mname'=>$studentList->mname,
					'lname'=>$studentList->lname,
					'mobile'=>$studentList->mobile,
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
			else if($grade==='LKG'){
				$NextGrade='UKG';
				$data=array(
					'username'=>$studentList->username,
					'usertype'=>$studentList->usertype,
					'fname'=>$studentList->fname,
					'mname'=>$studentList->mname,
					'lname'=>$studentList->lname,
					'mobile'=>$studentList->mobile,
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
			else if($grade==='UKG'){
				$NextGrade='1';
				$data=array(
					'username'=>$studentList->username,
					'usertype'=>$studentList->usertype,
					'fname'=>$studentList->fname,
					'mname'=>$studentList->mname,
					'lname'=>$studentList->lname,
					'mobile'=>$studentList->mobile,
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
			}else{
				$data=array(
					'username'=>$studentList->username,
					'usertype'=>$studentList->usertype,
					'fname'=>$studentList->fname,
					'mname'=>$studentList->mname,
					'lname'=>$studentList->lname,
					'mobile'=>$studentList->mobile,
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
				$output.='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i> Registered Successfully.
            </div></div>';
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
				$output.='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-exclamation-circle"> </i>Registered Successfully.
            </div></div>';
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
	function filtergrade_4branch($branchRegistration){
		$this->db->where('users.branch',$branchRegistration);
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
				$output.='<tr><td>'.$stuValue->fname.' '.$stuValue->mname.'</td>';
				$output.='<td>'.$gs_gradesec.'</td>';
				$output.='<td>'.$branch.'</td>';
				$output.='<td>'.$stuValue->username.'</td>';
				$output.='<td>'.$stuValue->password.'</td>';
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
		$output='<div class="row">';
		if($query->num_rows()>0){
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
			$output .='<div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                <i class="fas fa-check-circle"> </i> No saved document found.
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
				$output.='<div class="col-md-3 deletevacancy'.$mydoc->vid.'">
                    <div class="card">
                		<div class="card-statistic-4">
                  			<div class="align-items-center justify-content-between">
                        		<div class="card-content">
                          			<h2 class="mb-3 font-18">'.$mydoc->vposition.'                    
                              			<p class="mb-0">
                              			<small class="time">
                              				<i data-feather="watch"></i>'.$mydoc->datepost.'
                            			</small>
                              			<a href="#"> <button class="btn btn-default deletemyvacancy" type="submit" id="'.$mydoc->vid.'"><span class="text-danger">
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
            <table class="table table-bordered table-hover" style="width:100%;">
            <thead><tr> 
                <th>No.</th>
                <th>Parent Name</th>
                <th>Academic Year</th> </tr>
            </thead>';
        	$no=1;
			foreach ($query->result() as $fetchparents) {
				$output.='
				<tr>
	                <td>'.$no.'</td>
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
	function transferMark($max_quarter,$max_year){
		$output='<div class="row">';
		$queryBranch=$this->db->query("select name from branch where academicyear='$max_year' group by name ");
		if($queryBranch->num_rows()>0){
			foreach ($queryBranch->result() as $branchValue) {
				$branch=$branchValue->name;
				$queryStudent=$this->db->query("select gradesec from users where academicyear='$max_year' and usertype='Student' and status='Active' and isapproved='1' and branch='$branch' group by gradesec; "); 
				foreach ($queryStudent->result() as $gradesecValue) {
					$gradesec=$gradesecValue->gradesec;
					
					$queryTransfer=$this->db->query("insert into mark".$branch.$gradesec.$max_quarter.$max_year." select * from mark".$gradesec.$max_quarter.$max_year." where mbranch='$branch' and mgrade='$gradesec' "  );

					/*$this->dbforge->add_field($fields);
					$this->dbforge->add_key('mid',TRUE);
					$query=$this->dbforge->create_table('mark'.$branch.$gradesec.$max_quarter.$max_year,TRUE);*/		
				}
			}
			if($queryTransfer){
				$output .='<div class="col-lg-12"><div class="alert alert-success alert-dismissible show fade">
	            <div class="alert-body">
	                <button class="close" data-dismiss="alert">
	                    <span>&times;</span>
	                </button>
	            <i class="fas fa-check-circle"> </i> Data Transfered successfully.
	           </div></div></div>';
			}
		}else{
			$output .='<div class="col-lg-12"><div class="alert alert-warning alert-dismissible show fade">
	            <div class="alert-body">
	                <button class="close"  data-dismiss="alert">
	                    <span>&times;</span>
	                </button>
	            <i class="fas fa-check-circle"> </i> Please try again.
	           </div></div></div>';
		}
		$output.='</div>';
		return $output;
	}
	function prepareMarkTable($max_quarter,$max_year){
		$output='<div class="row">';
		$queryBranch=$this->db->query("select name from branch where academicyear='$max_year' group by name ");
		if($queryBranch->num_rows()>0){
			foreach ($queryBranch->result() as $branchValue) {
				$branch=$branchValue->name;
				$queryStudent=$this->db->query("select gradesec from users where academicyear='$max_year' and usertype='Student' and status='Active' and isapproved='1' and branch='$branch' group by gradesec; "); 
				foreach ($queryStudent->result() as $gradesecValue) {
					$gradesec=$gradesecValue->gradesec;
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
							'constraint'=>5
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
					$query=$this->dbforge->create_table('mark'.$branch.$gradesec.$max_quarter.$max_year,TRUE);		
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
	function searchStudents($searchItem){
		$this->db->order_by('fname','ASC');
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->like('username',$searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
		$this->db->or_like('fname', $searchItem);
		$this->db->where(array('usertype'=>'Student'));
		$this->db->where(array('status'=>'Active'));
		$this->db->where(array('isapproved'=>'1'));
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
	             <a href="#" class="deletestudent text-danger" id="'.$value->id.'">Delete</a>
	             <div class="bullet"></div>
	             <a href="#" class="dropstudent text-info" id="'.$value->id.'">Drop</a>
	             <div class="bullet"></div>
	             <a href="#" class="editstudent text-success" id="'.$value->unique_id.'">Edit</a>
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
		$output .='<div class="alert alert-info alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                        <span>&times;</span>
                    </button>
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
			$queryBS=$this->db->query("select bs.bsname from users as u inner join basicskill as bs where bs.grade=u.grade and u.academicyear='$max_year' and u.usertype='Student' and u.status='Active' and u.isapproved='1' and u.gradesec='$gradesec' and u.branch='$branches' and bs.academicyear='$max_year' group by bs.bsname order by bs.bsname ASC ");
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
			foreach ($queryfetchBS->result() as $stuList) {
				$stuid=$stuList->id;
				$grade=$stuList->grade;
				$output.='<input type="hidden" id="bsConductStuId" value="'.$stuid.'" >';
				$output.='<tr> <td>'.$no.'</td>';
				$output.='<td>'.$stuList->fname.' '.$stuList->mname.'</td>';
				$output.='<td>'.$stuList->username.'</td>';
				$output.='<td>'.$stuList->gradesec.'</td>';
				foreach ($queryBS->result() as $bsName) {
					$bsName=$bsName->bsname;
					if($bsName!='Conduct'){
						$queryBsValue=$this->db->query("select conduct,bsname,value,quarter, academicyear from basicskillvalue where academicyear='$max_year' and quarter='$quarter' and stuid='$stuid' and bsname='$bsName' group by bsname order by bsname ASC ");
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
						$queryBsValue=$this->db->query("select conduct,bsname,value,quarter, academicyear from basicskillvalue where academicyear='$max_year' and quarter='$quarter' and stuid='$stuid' and bsname='$bsName' group by bsname order by bsname ASC ");
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
	function updateStudentBs($stuid,$quarter,$bsname,$max_year,$value,$data){
		$this->db->where('academicyear',$max_year);
		$this->db->where('stuid',$stuid);
		$this->db->where('quarter',$quarter);
		$this->db->where('bsname',$bsname);
		$query=$this->db->get('basicskillvalue');
		$output='';
		if($query->num_rows() > 0){
			$this->db->where('academicyear',$max_year);
			$this->db->where('stuid',$stuid);
			$this->db->where('quarter',$quarter);
			$this->db->where('bsname',$bsname);
			$this->db->set('value',$value);
			$queryy=$this->db->update('basicskillvalue');
		}else{
			$queryy=$this->db->insert('basicskillvalue',$data);
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
}
?>