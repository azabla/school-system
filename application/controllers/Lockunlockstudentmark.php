<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lockunlockstudentmark extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' order by id ASC ");  
        if($this->session->userdata('username') == '' || $uperStuDE->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='lockunlockstudentmark')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function lockThisAssesmentMark(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('assesgrade')){
            $assesgrade=$this->input->post('assesgrade');
            $assesname=$this->input->post('assesname');
            $quarter=$this->input->post('quarter');
            $subject=$this->input->post('subject');
            echo $this->main_model->lockThisAssesmentMark($assesgrade,$quarter,$subject,$assesname,$max_year);
        }
    }
    function unlockThisAssesmentMark(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('assesgrade')){
            $assesgrade=$this->input->post('assesgrade');
            $assesname=$this->input->post('assesname');
            $quarter=$this->input->post('quarter');
            $subject=$this->input->post('subject');
            echo $this->main_model->unlockThisAssesmentMark($assesgrade,$quarter,$subject,$assesname,$max_year);
        }
    }
    function lockThisBranchMark(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('branchName')){
            $branchName=$this->input->post('branchName');
            for($i=0;$i<count($branchName);$i++){
                $check[]=$branchName[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->lockThisBranchMark($check,$max_year);
            }else{
                echo $this->main_model->lockThisBranchMark($branch,$max_year); 
            }
        }
    }
    function unlockThisBranchMark(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('branchName')){
            $branchName=$this->input->post('branchName');
            for($i=0;$i<count($branchName);$i++){
                $check[]=$branchName[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->unlockThisBranchMark($check,$max_year);
            }else{
                echo $this->main_model->unlockThisBranchMark($branch,$max_year); 
            }
        }
    }
    function fetchThisBranchGrade(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('branchName')){
            $branchName=$this->input->post('branchName');
            for($i=0;$i<count($branchName);$i++){
                $check[]=$branchName[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchThisBranchGrade($check,$max_year);
            }else{
                echo $this->main_model->fetchThisBranchGrade($branch,$max_year); 
            }
        }
    }
    function fetchThisBranchGrade4Assesment(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('branchName')){
            $branchName=$this->input->post('branchName');
            for($i=0;$i<count($branchName);$i++){
                $check[]=$branchName[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchThisBranchGrade4Assesment($check,$max_year);
            }else{
                echo $this->main_model->fetchThisBranchGrade4Assesment($branch,$max_year); 
            }
        }
    }
    function fetchGradeQuarter(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('evaluationName')){
            $grade=$this->input->post('evaluationName');
            for($i=0;$i<count($grade);$i++){
                $check[]=$grade[$i];
            }
            echo $this->main_model->fetchTermFromGrade4Assesmet($check,$max_year); 
        }
    }
    function fetchThisGradeEvaluation(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('quarter')){
            $gradeName=$this->input->post('gradeName');
            $quarter=$this->input->post('quarter');
            for($i=0;$i<count($gradeName);$i++){
                $check[]=$gradeName[$i];
            }
            echo $this->main_model->fetchThisGradeEvaluation($check,$quarter,$max_year);
        }
    }
    function fetchAssesmentStatusToLockUnlock(){
       $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('branchName')){
            $branchName=$this->input->post('branchName');
            $evaluationName=$this->input->post('evaluationName');
            $gradeName=$this->input->post('gradeName');
            $quarter=$this->input->post('quarter');
            for($i=0;$i<count($branchName);$i++){
                $check[]=$branchName[$i];
            }
            for($j=0;$j<count($evaluationName);$j++){
                $evaluation[]=$evaluationName[$j];
            }
            for($j=0;$j<count($gradeName);$j++){
                $checkGrade[]=$gradeName[$j];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchAssesmentStatusToLockUnlock($check,$evaluation,$checkGrade,$quarter,$max_year);
            }else{
                echo $this->main_model->fetchAssesmentStatusToLockUnlock($branch,$evaluation,$checkGrade,$quarter,$max_year); 
            }
        } 
    }
    function lockThisGradeMark(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('branchName')){
            $branchName=$this->input->post('branchName');
            $grade=$this->input->post('grade');
            for($i=0;$i<count($branchName);$i++){
                $check[]=$branchName[$i];
            }
            for($j=0;$j<count($grade);$j++){
                $checkGrade[]=$grade[$j];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->lockThisGradeMark($check,$checkGrade,$max_year);
            }else{
                echo $this->main_model->lockThisGradeMark($branch,$checkGrade,$max_year); 
            }
        }
    }
    function UnlockThisGradeMark(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('branchName')){
            $branchName=$this->input->post('branchName');
            $grade=$this->input->post('grade');
            for($i=0;$i<count($branchName);$i++){
                $check[]=$branchName[$i];
            }
            for($j=0;$j<count($grade);$j++){
                $checkGrade[]=$grade[$j];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->unlockThisGradeMark($check,$checkGrade,$max_year);
            }else{
                echo $this->main_model->unlockThisGradeMark($branch,$checkGrade,$max_year); 
            }
        }
    }
    function fetchThisBranchSection(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('branchName')){
            $branchName=$this->input->post('branchName');
            for($i=0;$i<count($branchName);$i++){
                $check[]=$branchName[$i];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetchThisBranchSection($check,$max_year);
            }else{
                echo $this->main_model->fetchThisBranchSection($branch,$max_year); 
            }
        }
    }
    function lockThisSectionMark(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('branchName')){
            $branchName=$this->input->post('branchName');
            $gradesec=$this->input->post('gradesec');
            for($i=0;$i<count($branchName);$i++){
                $check[]=$branchName[$i];
            }
            for($j=0;$j<count($gradesec);$j++){
                $checkGradesec[]=$gradesec[$j];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->lockThisSectionMark($check,$checkGradesec,$max_year);
            }else{
                echo $this->main_model->lockThisSectionMark($branch,$checkGradesec,$max_year); 
            }
        }
    }
    function UnlockThisSectionMark(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('branchName')){
            $branchName=$this->input->post('branchName');
            $gradesec=$this->input->post('gradesec');
            for($i=0;$i<count($branchName);$i++){
                $check[]=$branchName[$i];
            }
            for($j=0;$j<count($gradesec);$j++){
                $checkGradesec[]=$gradesec[$j];
            }
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->UnlockThisSectionMark($check,$checkGradesec,$max_year);
            }else{
                echo $this->main_model->UnlockThisSectionMark($branch,$checkGradesec,$max_year); 
            }
        }
    }
    function searchStudent(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->searchStudentsToLockMark($searchItem,$max_year);
            }else{
                echo $this->main_model->searchAdminStudentsToLockMark($searchItem,$branch,$max_year);
            }
        }
    }
    function lockThisStudentMark(){
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('stuID')){
            $stuID=$this->input->post('stuID');
            $queryUsers=$this->db->query("select gradesec,branch from users where academicyear='$max_year' and id='$stuID' ");
            if($queryUsers->num_rows()>0){
                foreach($queryUsers->result() as $gradesecName){
                    $gradesec=$gradesecName->gradesec;
                    $branch=$gradesecName->branch;
          
                    $queryTerm=$this->db->query("select term from quarter where Academic_year='$max_year' group by term ");
                    if($queryTerm->num_rows()>0){
                        foreach($queryTerm->result() as $termName){
                            $max_quarter=$termName->term;
                            $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branch.$gradesec.$max_quarter.$max_year."' ");
                            if ($queryCheckMark->num_rows()>0)
                            {
                                $this->db->where('stuid',$stuID);
                                $this->db->set('lockmark','1');
                                $queryUpdate=$this->db->update('mark'.$branch.$gradesec.$max_quarter.$max_year);
                            }
                        }
                    }
                }
            }
        }
    }
    function unlockThisStudentMark(){
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('stuID')){
            $stuID=$this->input->post('stuID');
            $queryUsers=$this->db->query("select gradesec,branch from users where academicyear='$max_year' and id='$stuID' ");
            if($queryUsers->num_rows()>0){
                foreach($queryUsers->result() as $gradesecName){
                    $gradesec=$gradesecName->gradesec;
                    $branch=$gradesecName->branch;
          
                    $queryTerm=$this->db->query("select term from quarter where Academic_year='$max_year' group by term ");
                    if($queryTerm->num_rows()>0){
                        foreach($queryTerm->result() as $termName){
                            $max_quarter=$termName->term;
                            $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branch.$gradesec.$max_quarter.$max_year."' ");
                            if ($queryCheckMark->num_rows()>0)
                            {
                                $this->db->where('stuid',$stuID);
                                $this->db->set('lockmark','0');
                                $queryUpdate=$this->db->update('mark'.$branch.$gradesec.$max_quarter.$max_year);
                            }
                        }
                    }
                }
            }
        }
    }
    function lockAllMark(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        $query='';
        $queryBranch=$this->db->query("select name from branch where academicyear='$max_year' group by name ");
        if($queryBranch->num_rows()>0){
            foreach ($queryBranch->result() as $branchValue) {
                $branch=$branchValue->name;
                $queryStudent=$this->db->query("select gradesec,grade from users where academicyear='$max_year' and usertype='Student' and status='Active' and isapproved='1' and branch='$branch' group by gradesec; "); 
                foreach ($queryStudent->result() as $gradesecValue) {
                    $gradesec=$gradesecValue->gradesec;
                    $grade=$gradesecValue->grade;
                    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year' and termgrade='$grade' ");
                    $row2 = $query2->row();
                    $max_quarter=$row2->quarter;
                    $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branch.$gradesec.$max_quarter.$max_year."' ");
                    if ($queryCheckMark->num_rows()>0)
                    {
                        $this->db->where('lockmark','0');
                        $this->db->where('academicyear',$max_year);
                        $this->db->set('lockmark','1');
                        $query=$this->db->update('mark'.$branch.$gradesec.$max_quarter.$max_year);
                    }
                }
            }
        }
        if($query){
          echo '<i class="fas fa-check-circle"> </i> Mark Locked successfully';
        }else{
          echo '<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            <i class="fas fa-exclamation-circle"> </i> Please try again..
          </div></div>';
        }
    }
    function unlockAllMark(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        $query='';
        $queryBranch=$this->db->query("select name from branch where academicyear='$max_year' group by name ");
        if($queryBranch->num_rows()>0){
            foreach ($queryBranch->result() as $branchValue) {
                $branch=$branchValue->name;
                $queryStudent=$this->db->query("select gradesec,grade from users where academicyear='$max_year' and usertype='Student' and status='Active' and isapproved='1' and branch='$branch' group by gradesec; "); 
                foreach ($queryStudent->result() as $gradesecValue) {
                    $gradesec=$gradesecValue->gradesec;
                    $grade=$gradesecValue->grade;
                    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year' and termgrade='$grade' ");
                    $row2 = $query2->row();
                    $max_quarter=$row2->quarter;
                    $queryCheckMark = $this->db->query("SHOW TABLES LIKE 'mark".$branch.$gradesec.$max_quarter.$max_year."' ");
                    if ($queryCheckMark->num_rows()>0)
                    {
                        $this->db->where('lockmark','1');
                        $this->db->where('academicyear',$max_year);
                        $this->db->set('lockmark','0');
                        $query=$this->db->update('mark'.$branch.$gradesec.$max_quarter.$max_year);
                    }
                }
            }
        }
        if($query){
          echo '<i class="fas fa-check-circle"> </i> Mark unlocked successfully';
        }else{
          echo '<div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                </button>
            <i class="fas fa-exclamation-circle"> </i> Please try again..
          </div></div>';
        }
    }
}
