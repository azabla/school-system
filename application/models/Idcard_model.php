<?php
class idcard_model extends CI_Model{
	function fetch_session_user($user){
		$this->db->where('username',$user);
		$this->db->group_by('username');
		$query=$this->db->get('users');
		return $query->result();
	}
	function academic_year(){
		$this->db->order_by('year_name','DESC');
		$this->db->select('*');
		$query=$this->db->get('academicyear');
		return $query->result();
	}
	function academic_year_filter(){
		$this->db->select_max('year_name');
		$query=$this->db->get('academicyear');
		return $query->result();
	}
	function fetch_gradesec($max_year){
		$this->db->group_by('gradesec');
		$this->db->order_by('gradesec','ASC');
		$this->db->where('gradesec!=','');
		$this->db->where(array('isapproved'=>'1'));
		$this->db->where(array('academicyear'=>$max_year));
		$this->db->like('usertype','Student');
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
	function fetch_school(){
		$query=$this->db->get('school');
		return $query->result();
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
	function Filter_grade_from_branch_Back($branch,$max_year){
		$this->db->where('users.branch',$branch);
		$this->db->where('users.grade !=','');
		$this->db->where('users.usertype','Student');
		$this->db->where(array('users.academicyear'=>$max_year));
		$this->db->order_by('users.gradesec','ASC');
		$this->db->group_by('users.gradesec');
		$query=$this->db->get('users');
		$output='';
		foreach ($query->result() as $row) { 
			$output .='<option value="'.$row->gradesec.'"> '.$row->gradesec.' </option>';
		}
		return $output;
	}
	function fetchThisGradeStudentIdcard($grade,$academicyear){
		$output ='<input type="checkbox" class="" id="selectallStudentList" onClick="selectAllStudent()">Select All<div class="row"> ';
		foreach($grade as $grades){
			$this->db->where('grade',$grades);
			$this->db->where('status','Active');
			$this->db->where('isapproved','1');
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
			          		<h5><b>'.$name.'</b></h5>'.$gyear.' ACADEMIC YEAR <br> STUDENT ID CARD<br>
			          		'.strtoupper($branch).' BRANCH
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
		          		<h5><b>'.$name.'</b></h5>'.$gyear.' ACADEMIC YEAR <br> STUDENT ID CARD
		          		<br> '.strtoupper($branch).' BRANCH
		          	</div>
		        </div><div class="dropdown-divider"></div>';
				$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12">
						<div class="row">
			                <div class="col-md-12 col-12" style="white-space: nowrap">
			                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.'&nbsp;&nbsp;Age: '.$staff->age.'
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
		          		<h5><b>'.$name.'</b></h5>'.$gyear.' ACADEMIC YEAR <br> STUDENT ID CARD<br>
		          		'.strtoupper($staff->branch).' BRANCH
		          	</div>
		        </div><div class="dropdown-divider"></div>';
				$output.='<input type="hidden" class="qrGeneratorFname" value="'.$staff->fname.'">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12">
						<div class="row">
			                <div class="col-md-12 col-12" style="white-space: nowrap">
			                    Name: '.$staff->fname.' '.$staff->mname.' '.$staff->lname.' &nbsp;&nbsp;Age: '.$staff->age.'
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
	function fetchBackIdCard($pageNumber,$max_year,$gyear,$branch,$gradesec){
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
					<div class="col-lg-12 col-md-12 col-12">';
					$queryDirector=$this->db->query("select u.fname,u.mname,u.lname ,u.mysign from users as u cross join directorplacement as dp ON dp.staff=u.username where dp.grade='$gradesec' and dp.academicyear='$max_year' and usertype!='Student' and u.branch='$branch' and isapproved='1' and status='Active' and mysign!='' ");
		             if($queryDirector->num_rows()>0){
		              $rowSignD=$queryDirector->row();
		              $signName=$rowSignD->fname;
		              $signmame=$rowSignD->mname;
		              $signlame=$rowSignD->lname;
		              $signlame=$rowSignD->lname;
		              $signSigns=$rowSignD->mysign;
		              $output.="<p>Director’s Name:- <u>".$signName." ".$signmame."</u><br>
		              Signature  <img alt='Sig.' src='".base_url()."/".$signSigns."' style='height:50px;width:140px'></p>";
		            }else{
		              $output.="<p>Director’s Name<br>______________________</p>
		              <p>Signature____________</p>";
		            }
					$output.='
		            </div>
				</div>
            	<div class="dropdown-divider"></div>
		            <div class="row">
		            	<div class="col-md-12 col-12">
			            	<p><i class="fas fa-globe"></i> '.$website.' | <i class="fab fa-telegram-plane"></i> '.$telegram.' <i class="fas fa-phone"></i> '.$phone.'</p>
			            </div>
	            	</div>
            	</div>
            </div>';
		}
		$output.='</div>';
		return $output;
	}


	
}
?>