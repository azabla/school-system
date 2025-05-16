<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluation extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Evaluation' and allowed='Mgmtevaluation'  order by id ASC "); 
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows() < 1 || $userLevel!='1'){
            $this->session->set_flashdata("error","Please Login first");
            $this->load->driver('cache');
            delete_cookie('username');
            unset($_SESSION);
            session_destroy();
            $this->cache->clean();
            ob_clean();
            redirect('login/');
        }    
    }
	public function index($page='evaluation')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
        {
          show_404();
        }
        
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year'");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['posts']=$this->main_model->fetch_term($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['staffs']=$this->main_model->fetch_students($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function filterSubject4CustomEvaluation(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade2analysis')){
            $grade2analysis=$this->input->post('grade2analysis');
            for($i=0;$i<count($grade2analysis);$i++){
                $gradeGsanalysis[]=$grade2analysis[$i];
            }
            echo $this->main_model->filterSubject4CustomEvaluation($gradeGsanalysis,$max_year);   
        }
    }
    function fetchCustomEvaluations(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_branch = $this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $accessbranch = sessionUseraccessbranch();
        $queryCheck = $this->db->query("select * from quarter where Academic_year='$max_year' group by termgroup ");
        if($queryCheck->num_rows()>0){
            foreach($queryCheck->result() as $maxQuarter){
                $termgroup=$maxQuarter->termgroup;
                $query2 = $this->db->query("select max(term) as quarter,min(term) as minQuarter from quarter where Academic_year='$max_year' and termgroup='$termgroup' ");
                $row2 = $query2->row();
                $max_quarter=$row2->quarter;
                $min_quarter=$row2->minQuarter;
                $postData = $this->input->post();
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    $data= $this->main_model->fetchCustomEvaluation($max_year,$max_quarter,$min_quarter,$postData);
                    echo json_encode($data);
                }else{
                    $data=  $this->main_model->fetchCustomEvaluation_admin($max_year,$max_quarter,$min_quarter,$postData);
                    echo json_encode($data);
                }
            }
        }    
    }
    function deleteCustomEvaluation(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['post_id'])){
            $id=$this->input->post('post_id');
            $quarter=$this->input->post('quarter');
            $evname=$this->input->post('evname');
            $query=$this->main_model->deleteCustomEvaluation($id,$quarter,$evname,$max_year);
        }
    }
    function postCustomEvaluation(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['id'])){
            $id = $this->input->post('id');
            $evnames=$this->input->post('evalname');
            $percent=trim($this->input->post('percent'));
            $evsubject=$this->input->post('subject');
            foreach ($evnames as $evname) {
                foreach ($id as $grade) {
                    $query2 = $this->db->query("select max(term) as quarter,termgroup from quarter where Academic_year='$max_year' and termgrade ='$grade' group by termgroup ");
                    $row2 = $query2->row();
                    $max_quarter=$row2->quarter;
                    $termgroup=$row2->termgroup;
                    foreach ($evsubject as $evsubjects) {
                        $query=$this->main_model->addCustomEvaluation($grade,$evsubjects,$evname,$max_year,$max_quarter);
                        if($max_quarter!=''){
                            if($query){
                                $data=array(
                                    'customgrade'=>$grade,
                                    'customsubject'=>$evsubjects,
                                    'customasses'=>$evname,
                                    'academicyear'=>$max_year,
                                    'custompercent'=>$percent,
                                    'customquarter'=>$max_quarter,
                                    'quartergroup'=>$termgroup
                                );
                                $query2=$this->db->insert('evaluationcustom',$data);
                                if($query2){
                                    echo 'Saved';
                                }else{
                                    echo 'Please try again';
                                }
                            }
                        }
                    }
                }
            }
            
        }
    }
    function movingCustomEvaluations(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryCheck = $this->db->query("select * from quarter where Academic_year='$max_year' group by termgroup ");
        if($queryCheck->num_rows()>0){
            $data=array();
            foreach($queryCheck->result() as $maxQuarter){
                $termgroup=$maxQuarter->termgroup;
                $queryCurrent = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' and termgroup='$termgroup' ");
                $rowCurrent = $queryCurrent->row();
                $currentQuarter=$rowCurrent->quarter;
                   
                $query2 = $this->db->query("select max(customquarter) as quarter from evaluationcustom where academicyear='$max_year' and quartergroup='$termgroup' ");
                $row2 = $query2->row();
                $max_quarter=$row2->quarter;
                if($max_quarter!=$currentQuarter){
                    $queryEva = $this->db->query("select * from evaluationcustom where academicyear='$max_year' and customquarter='$max_quarter' and quartergroup='$termgroup' ");
                    foreach($queryEva->result() as $evaValue){
                        $data[]=array(
                            'customquarter'=>$currentQuarter,
                            'customgrade'=>$evaValue->customgrade,
                            'customsubject'=>$evaValue->customsubject,
                            'customasses'=>$evaValue->customasses,
                            'custompercent'=>$evaValue->custompercent,
                            'quartergroup'=>$termgroup,
                            'academicyear'=>$evaValue->academicyear
                        );
                    }
                    $query=$this->db->insert_batch('evaluationcustom',$data);
                }
            }
        }
    }
    function movingGroupEvaluations(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryCheck = $this->db->query("select * from quarter where Academic_year='$max_year' group by termgroup ");
        if($queryCheck->num_rows()>0){
            $data=array();
            foreach($queryCheck->result() as $maxQuarter){
                $termgroup=$maxQuarter->termgroup;
                $queryCurrent = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' and termgroup='$termgroup' ");
                $rowCurrent = $queryCurrent->row();
                $currentQuarter=$rowCurrent->quarter;
                   
                $query2 = $this->db->query("select max(groupquarter) as quarter from evaluationgroup where academicyear='$max_year' and quartergroup='$termgroup' ");
                $row2 = $query2->row();
                $max_quarter=$row2->quarter;
                if($max_quarter!=$currentQuarter){
                    $queryEva = $this->db->query("select * from evaluationgroup where academicyear='$max_year' and groupquarter='$max_quarter' and quartergroup='$termgroup' ");
                    foreach($queryEva->result() as $evaValue){
                        $data[]=array(
                            'groupquarter'=>$currentQuarter,
                            'groupgrade'=>$evaValue->groupgrade,
                            'groupasses'=>$evaValue->groupasses,
                            'groupname'=>$evaValue->groupname,
                            'quartergroup'=>$termgroup,
                            'academicyear'=>$evaValue->academicyear
                        );
                    }
                    $query=$this->db->insert_batch('evaluationgroup',$data);
                }
            }
        }
    }
    function filterAssesmentCustomEvaluation(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade2analysis')){
            $grade2analysis=$this->input->post('grade2analysis');
            for($i=0;$i<count($grade2analysis);$i++){
                $gradeGsanalysis[]=$grade2analysis[$i];
            }
            echo $this->main_model->filterAssesmentCustomEvaluation($gradeGsanalysis,$max_year);   
        }
    }
    function filterAssesmentGroupEvaluation(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade2analysis')){
            $grade2analysis=$this->input->post('grade2analysis');
            for($i=0;$i<count($grade2analysis);$i++){
                $gradeGsanalysis[]=$grade2analysis[$i];
            }
            echo $this->main_model->filterAssesmentGroupEvaluation($gradeGsanalysis,$max_year);   
        }
    }
    function postGroupEvaluation(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('id')){
            $stuIdArray=$this->input->post('stuIdArray');
            $id = $this->input->post('id');
            $groupName=$this->input->post('groupName');
            $data=array();
            $dataNotInserted=array();
            foreach ($id as $grade) {
                foreach ($stuIdArray as $assesname) {
                    $asses_Name=trim($assesname);
                    $query2 = $this->db->query("select max(term) as quarter,termgroup from quarter where Academic_year='$max_year' and termgrade ='$grade' group by termgroup ");
                    $row2 = $query2->row_array();
                    $max_quarter=$row2['quarter'];
                    $termgroup=$row2['termgroup'];
                    if($max_quarter!==''){
                        $query=$this->main_model->addgroupEvaluation($grade,$asses_Name,$max_year,$max_quarter);
                        if($query){
                            if(!empty($asses_Name) && !empty($max_quarter)) {
                                $data[]=array(
                                    'groupgrade'=>$grade,
                                    'groupname'=>$groupName,
                                    'groupasses'=>$asses_Name,
                                    'groupquarter'=>$max_quarter,
                                    'quartergroup'=>$termgroup,
                                    'academicyear'=>$max_year
                                );
                            }
                        }else{
                            $dataNotInserted[]=array(
                                'groupgrade'=>$grade,
                                'groupname'=>$groupName,
                                'groupasses'=>$asses_Name
                            ); 
                        }
                    } 
                }
            }
            $queryInserted=$this->db->insert_batch('evaluationgroup',$data);
            if($queryInserted){
                echo '<span class="text-success">Saved</span>';
            }else{
                echo '<span class="text-danger">Please try again</span>';
            }
            if(!empty($dataNotInserted)){
               echo '<span class="text-success">
                   <p>'.implode(', ', array_map(function ($entry) {
                    return ($entry[key($entry)]);
                    }, $dataNotInserted)).'</p>
                    <i class="fas fa-exclamation-circle"> </i>The Above evaluation lists are already grouped.
                  </span'; 
            }
        }
    }
    function fetchGroupEvaluations(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryCheck = $this->db->query("select * from quarter where Academic_year='$max_year' group by termgroup ");
        if($queryCheck->num_rows()>0){
            foreach($queryCheck->result() as $maxQuarter){
                $termgroup=$maxQuarter->termgroup;
                $query2 = $this->db->query("select max(term) as quarter,min(term) as minQuarter from quarter where Academic_year='$max_year' and termgroup='$termgroup' ");
                $row2 = $query2->row();
                $max_quarter=$row2->quarter;
                $min_quarter=$row2->minQuarter;
                echo $this->main_model->fetchGroupEvaluations($max_year,$max_quarter,$min_quarter);

            }
        }    
    }
    function deleteGroupEvaluation(){
        $user=$this->session->userdata('username');
        if(isset($_POST['year'])){
            $year=$this->input->post('year');
            $quarter=$this->input->post('quarter');
            $group_name=$this->input->post('group_name');
            $query=$this->main_model->deleteGroupEvaluation($quarter,$group_name,$year);
        }
    }
    function postEvaluation(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if(isset($_POST['id'])){
            $id = $this->input->post('id');
            $evname=trim($this->input->post('evname'));
            $percent=trim($this->input->post('percent'));
            foreach ($id as $grade) {
                $query2 = $this->db->query("select max(term) as quarter,termgroup from quarter where Academic_year='$max_year' and termgrade ='$grade' group by termgroup ");
                $row2 = $query2->row();
                $max_quarter=$row2->quarter;
                $termgroup=$row2->termgroup;
                $query=$this->main_model->add_evaluation($grade,$evname,$max_year,$max_quarter);
                if($max_quarter!=''){
                    if($query){
                        $data=array(
                            'grade'=>$grade,
                            'evname'=>$evname,
                            'quarter'=>$max_quarter,
                            'academicyear'=>$max_year,
                            'percent'=>$percent,
                            'evgroup'=>$termgroup,
                            'date_created'=>date('M-d-Y')
                        );
                        $query2=$this->db->insert('evaluation',$data);
                        if($query2){
                            echo 'Saved';
                        }else{
                            echo 'Please try again';
                        }
                    }
                }
            }
        }
    }
    function fetchEvaluations(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $accessbranch = sessionUseraccessbranch();
        $query_branch = $this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $queryCheck = $this->db->query("select * from quarter where Academic_year='$max_year' group by termgroup ");
        if($queryCheck->num_rows()>0){
            foreach($queryCheck->result() as $maxQuarter){
                $termgroup=$maxQuarter->termgroup;
                $query2 = $this->db->query("select max(term) as quarter,min(term) as minQuarter from quarter where Academic_year='$max_year' and termgroup='$termgroup' ");
                $row2 = $query2->row();
                $max_quarter=$row2->quarter;
                $min_quarter=$row2->minQuarter;
                if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                    echo $this->main_model->fetch_eval_grade($max_year,$max_quarter,$min_quarter);
                }else{
                    echo $this->main_model->fetch_eval_grade_admin($max_year,$max_quarter,$min_quarter,$branch);
                }

            }
        }    
    }
    function deleteEvaluation(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data1=array();
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('UTC');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $datetried = gmdate("Y-m-d h:i A", $dt->format('U'));
        if(isset($_POST['post_id'])){
            $id=$this->input->post('post_id');
            $quarter=$this->input->post('quarter');
            $evname=$this->input->post('evname');
            $querySelect=$this->db->query("select * from users where username='$user' ");
            $rowName=$querySelect->row();
            $fname=$rowName->fname;
            $mname=$rowName->mname;
            $lname=$rowName->lname;
            $data1=array(
                'userinfo'=>$user,
                'useraction'=>'Evaluation deleted',
                'infograde'=>'-',
                'subject'=>'-',
                'quarter'=>$quarter,
                'academicyear'=>$max_year,
                'oldata'=>'-',
                'newdata'=>'-',
                'markname'=>$evname,
                'userbranch'=>'-',
                'actiondate'=> $datetried
            );
            $queryInsert=$this->db->insert('useractions',$data1);
            if($queryInsert){
                $query=$this->main_model->delete_evaluation($id,$quarter,$evname,$max_year);
            }
        }
    }
    function fetchEvaluationsToEdit(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['post_id'])){
          $id=$this->input->post('post_id');
          $quarter=$this->input->post('quarter');
          $evname=$this->input->post('evname');
          echo $this->main_model->edit_evaluation($id,$quarter,$evname,$max_year);
        }
    }
    function Edit_thisgradevaluation(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        
        if(isset($_POST['evname'])){
            $evname=$this->input->post('evname');
            $new_evname=$this->input->post('new_evname');
            $query=$this->main_model->edit_thisgradevaluation($evname,$max_year,$new_evname);
            if($query){
                echo '<span class="text-success">Saved</span>';
            }else{
                echo'Oooops, Try again';
            }
        }
    }
    function updateEachEvaluationPercentage(){
        if($this->input->post('evname')){
            $date_created=date('M-d-Y');
            $evname=$this->input->post('evname');
            $grade=$this->input->post('grade');
            $valuee=$this->input->post('value');
            $academicyear=$this->input->post('academicyear');
            $quarter=$this->input->post('quarter');
            $this->db->where('academicyear',$academicyear);
            $this->db->where('evname',$evname);
            $this->db->where('grade',$grade);
            $this->db->where('quarter',$quarter);
            $this->db->set('percent',$valuee);
            $query=$this->db->update('evaluation');
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        }
    }
    function movingEvaluations(){

        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryCheck = $this->db->query("select * from quarter where Academic_year='$max_year' group by termgroup ");
        $data=array();
        if($queryCheck->num_rows()>0){
            foreach($queryCheck->result() as $maxQuarter){
                $termgroup=$maxQuarter->termgroup;
                $queryCurrent = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' and termgroup='$termgroup' ");
                $rowCurrent = $queryCurrent->row();
                $currentQuarter=$rowCurrent->quarter;
                   
                $query2 = $this->db->query("select max(quarter) as quarter from evaluation where academicyear='$max_year' and evgroup='$termgroup' ");
                $row2 = $query2->row();
                $max_quarter=$row2->quarter;
                if($max_quarter!=$currentQuarter){
                    $queryEva = $this->db->query("select * from evaluation where academicyear='$max_year' and quarter='$max_quarter' and evgroup='$termgroup' ");
                    foreach($queryEva->result() as $evaValue){
                        $data[]=array(
                            'evname'=>$evaValue->evname,
                            'quarter'=>$currentQuarter,
                            'grade'=>$evaValue->grade,
                            'percent'=>$evaValue->percent,
                            'evgroup'=>$termgroup,
                            'academicyear'=>$evaValue->academicyear,
                            'date_created'=>date('M-d-Y')
                        );
                    }
                    $query=$this->db->insert_batch('evaluation',$data);
                }
            }
        }

        /*$user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryCheck = $this->db->query("select * from quarter where Academic_year='$max_year' group by termgroup ");
        if($queryCheck->num_rows()>0){
            foreach($queryCheck->result() as $maxQuarter){
                $termgroup=$maxQuarter->termgroup;
                $queryCurrent = $this->db->query("select max(term) as maxQuarter,min(term) as minQuarter from quarter where Academic_year='$max_year' and termgroup='$termgroup' ");
                $rowCurrent = $queryCurrent->row();
                $currentQuarter=$rowCurrent->maxQuarter;
                $minQuarter=$rowCurrent->minQuarter;

                $query2 = $this->db->query("select quarter from evaluation where academicyear='$max_year' and quarter='$minQuarter' and evgroup='$termgroup' ");
                $row2 = $query2->row();
                $max_quarter=$row2->quarter;
                if($minQuarter!=$currentQuarter){   
                    $queryEva = $this->db->query("select * from evaluation where academicyear='$max_year' and quarter='$max_quarter' and evgroup='$termgroup' ");
                    foreach($queryEva->result() as $evaValue){
                        $data[]=array(
                            'evname'=>$evaValue->evname,
                            'quarter'=>$currentQuarter,
                            'grade'=>$evaValue->grade,
                            'percent'=>$evaValue->percent,
                            'academicyear'=>$evaValue->academicyear,
                            'date_created'=>date('M-d-Y')
                        );
                    }
                    $query=$this->db->insert_batch('evaluation',$data);
                }
            }
        }*/
    }
    function enable_teachers_change_evaluation(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->enable_teachers_change_evaluation($max_year);
    }
    function onn_teacher_enable_evaluation_change(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            $this->db->where('academicyear',$max_year);
            $query = $this->db->get('enable_teachers_change_evaluation');
            if($query->num_rows()>0){
                $this->db->where('academicyear',$max_year);
                $this->db->set('enable_status','1');
                $query=$this->db->update('enable_teachers_change_evaluation'); 
                if($query){
                    echo '1';
                }else{
                    echo '0';
                }
            }else{
                $data=array(
                    'enable_status'=>'1',
                    'academicyear'=>$max_year,
                    'actionby'=>$user,
                    'dateaction'=>date('M-d-Y')
                );
                $query=$this->db->insert('enable_teachers_change_evaluation',$data); 
                if($query){
                    echo '3';
                }else{
                    echo '4';
                }
            }
        }
    }
    function off_teacher_enable_evaluation_change(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            $this->db->where('academicyear',$max_year);
            $this->db->set('enable_status','0');
            $query=$this->db->update('enable_teachers_change_evaluation');
            if($query){
                echo '1';
            } else{
                echo '0';
            }
        }
    }
}