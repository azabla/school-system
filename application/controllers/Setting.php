<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        if($this->session->userdata('username') == '' || 
         $this->session->userdata('usertype')!= 'superAdmin'){
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
    public function index($page='setting')
    {
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
        {
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' ");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        if(isset($_POST['postyear']))
        {
            $academicyear=$this->input->post('academicyear');
            $gacademicyear=$this->input->post('gacademicyear');
            $date_created=date('M-d-Y');
            $this->load->model('main_model');
            $query=$this->main_model->add_year($academicyear);
            if($query){
                $data=array(
                'year_name'=>$academicyear,
                'gyear'=>$gacademicyear,
                'date_created'=>$date_created
            );
                $this->db->insert('academicyear',$data);
                redirect('setting/','refresh');
            }
        }
        if(isset($_POST['postbranch'])){
            $branch=preg_replace("/[\s-]+/", "", trim(ucfirst(strtolower($this->input->post('branch')))));
            $bac=$this->input->post('bac');
            $data=array(
                'name'=>$branch,
                'academicyear'=>$bac,
                'datecreated'=>date('M-d-Y')
            );
            $this->main_model->post_branch($data);
        }
        if(isset($_POST['deletebranch'])){
            $id=$this->input->post('deletebranch');
            $this->main_model->delete_branch($id);
        }
        if(isset($_POST['term']))
        {
            $term=preg_replace("/[\s-]+/", "", $this->input->post('term'));
            $ac=$this->input->post('ac');
            $startdate=$this->input->post('startdate');
            $endate=$this->input->post('endate');
            $termGroup=$this->input->post('termGroup');
            $date_created=date('M-d-Y');
            $timestamp=strtotime($startdate);
            $newDateStart=date('d/m/y',$timestamp);
            $timestamp2=strtotime($endate);
            $newDateEnd=date('d/m/y',$timestamp2);
            $grade=$this->input->post('quarter_grade[]');
            for($i=0;$i<count($grade);$i++){
                $check=$grade[$i];
                $query=$this->main_model->add_term($term,$ac,$check);
                if($query)
                {
                    $data=array(
                        'term'=>$term,
                        'termgrade'=>$check,
                        'Academic_year'=>$ac,
                        'startdate'=>$newDateStart,
                        'endate'=>$newDateEnd,
                        'termgroup'=>$termGroup,
                        'onoff'=>'1',
                        'date_created'=>$date_created
                    );
                    $queryInsert=$this->db->insert('quarter',$data);
                    if($queryInsert){
                        $this->db->where('Academic_year',$ac);
                        $this->db->where('termgroup',$termGroup);
                        $this->db->where('term!=',$term);
                        $this->db->set('onoff',0);
                        $this->db->update('quarter');
                    }
               }
               else
               {
                $this->session->set_flashdata("error","Term/Quarter already registered");
                 redirect('setting/','refresh');
                }
            }
        }
        if(isset($_GET['term_id']))
        {
            $id=$_GET['term_id'];
            $this->load->model('main_model');
            $this->main_model->delete_term($id,$max_year);
            redirect('setting/','refresh');
        }
        if(isset($_POST['postsocial']))
        {
            $facebooklink=$this->input->post('facebooklink');
            $twitterlink=$this->input->post('twitterlink');
            $telegramlink=$this->input->post('telegramlink');
            $instagramlink=$this->input->post('instagramlink');
            $date_created=date('M-d-Y');
            $data=array(
                'facebook'=>$facebooklink,
                'twitter'=>$twitterlink,
                'telegram'=>$telegramlink,
                'instagram'=>$instagramlink,
                'date_created'=>$date_created
            );
            $this->db->update('links',$data); 
            redirect('setting/','refresh');
        }
        if (isset($_GET['view'])) 
        {
            $status='0';
            if ($_GET['view'] != '') 
            {
                $query_status=$this->main_model->update_newmessages($status,$user);
                $data_status=array(
                    'status'=>'1'
                );
                if($query_status)
                {
                    $this->db->update('message',$data_status); 
                }   
            }
        }
        $data['fetchEval4Assesment']=$this->main_model->fetchEval4Assesment($max_year,$max_quarter);
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['gradee']=$this->main_model->fetch_grade($max_year);
        $data['gradeee']=$this->main_model->fetch_grade($max_year);
        $data['gradeeee']=$this->main_model->fetch_grade($max_year);
        $data['gradeeeee']=$this->main_model->fetch_grade($max_year);
        $data['social_pages']=$this->main_model->fetch_social_pages();
        $data['fetch_year']=$this->main_model->fetch_year();
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['posts']=$this->main_model->fetch_term($max_year);
        $this->load->view('home-page/'.$page,$data);
    } 
    public function schoolsetting()
    {
        $config['upload_path']    = './logo/';
        $config['allowed_types']  = 'gif|jpg|png|ico';
        $this->load->library('upload', $config);
        $sname=$this->input->post('sname');
        $name_2=$this->input->post('s2name');
        $slogan=$this->input->post('slogan');
        $mobile=$this->input->post('mobile');
        $email=$this->input->post('email');
        $address=$this->input->post('address');
        $about=$this->input->post('about');
        $website=$this->input->post('schoolwebsite');
        $date_created=date('M-d-Y');
        if ($this->upload->do_upload('logo'))
        {
            $dataa = $this->upload->data('file_name');
            $data=array(
                'name'=>$sname,
                'name_2'=>$name_2,
                'email'=>$email,
                'phone'=>$mobile,
                'address'=>$address,
                'slogan'=>$slogan,
                'about'=>$about,
                'website'=>$website,
                'date_created'=>$date_created,
                'logo'=>$dataa
            );
        }else{
            $data=array(
                'name'=>$sname,
                'name_2'=>$name_2,
                'email'=>$email,
                'phone'=>$mobile,
                'address'=>$address,
                'slogan'=>$slogan,
                'about'=>$about,
                'website'=>$website,
                'date_created'=>$date_created
            );
        }
        $this->db->update('school',$data); 
        redirect('setting/','refresh');
    }
    function deleteyear(){
        if($this->input->post('yid')){
            $id=$this->input->post('yid');
            $this->main_model->delete_year($id);
        }
    }
    function fetchEval4AssesmentFilter(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetchEval4AssesmentFilter($max_year);
    }
    function load_grade_level(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->load_grade_level($max_year);
    }
    function save_grade_level(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('pre_grade')){
            $pre_grade=$this->input->post('pre_grade');
            $next_grade=$this->input->post('next_grade');
            foreach($next_grade as $next_grades){
                $queryNext=$this->db->query("select * from grade_level where pre_grade='$pre_grade' and next_grade ='$next_grades' ");
                if($queryNext->num_rows()<1){
                    $data[ ]=array(
                        'pre_grade'=>$pre_grade,
                        'next_grade'=>$next_grades
                    );
                }
            }
            $query=$this->db->insert_batch('grade_level',$data);
        }
    }
    function remove_grade_level(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('preGrade')){
            $preGrade=$this->input->post('preGrade');
            $nextGrade=$this->input->post('nextGrade');
            $this->db->where('pre_grade',$preGrade);
            $this->db->where('next_grade',$nextGrade);
            $query=$this->db->delete('grade_level');
        }
    }
    function fetchQuarter(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetchQuarterOfYear($max_year);
    }
    function onQuarter(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('term')){
            $term=$this->input->post('term');
            $this->db->where('Academic_year',$max_year);
            $this->db->where('term',$term);
            $this->db->set('onoff','1');
            $this->db->update('quarter');
        }
    }
    function offQuarter(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('term')){
            $term=$this->input->post('term');
            $this->db->where('Academic_year',$max_year);
            $this->db->where('term',$term);
            $this->db->set('onoff','0');
            $this->db->update('quarter');
        }
    }
    function feedschoolcurriclum(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('dname')){
            $term=$this->input->post('dname');
            $data=array(
                'crname'=>$term,
                'academicyear'=>$max_year,
                'createdby'=>$user,
                'datecreated'=>date('M-d-Y')
            );
            $this->main_model->feedschoolcurriclum($term,$data);
        }
    }
    function fetchschoolcurriclum(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetchschoolcurriclum($max_year); 
    }
    function loadSchoolCurriclum4Use(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->loadSchoolCurriclum4Use($max_year); 
    }
    function deleteschoolcurriclum(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('dname')){
            $term=$this->input->post('dname');
            $this->db->where('crname',$term);
            $this->db->delete('schoolcurriclum');
        }
    }
    function fetchTermToEdit(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $output='<div class="row">';
        if($this->input->post('term_id')){
            $termid=$this->input->post('term_id');
            $output.='<input type="hidden" class="termID" value="'.$termid.'" > ';
            $query=$this->main_model->getQuarterToEdit($termid,$max_year);
            foreach ($query as $value) {
                $startDate = $value->startdate;
                $endDate = $value->endate;
                $start_date = DateTime::createFromFormat('d/m/y',$startDate);
                $date_end = DateTime::createFromFormat('d/m/y',$endDate);
                $endDate= $date_end->format('Y-m-d');
                $startDate=$start_date->format('Y-m-d');

                $output.='<div class="col-md-4 col-12 form-group"> <lable>Season</lable>';
                $output.='<input class="form-control termName" type="text" value="'.$value->term.'" >';
                $output.='</div> ';
                $output.='<div class="col-md-4 col-12 form-group"><lable>Start Date</lable> ';
                $output.='<input class="form-control termStartDate" type="date" value="'.$startDate.'" >';
                $output.='</div> ';
                $output.='<div class="col-md-4 col-12 form-group"> <lable>End Date</lable>';
                $output.='<input class="form-control termEndDate" type="date" value="'.$endDate.'" >';
                $output.='</div> ';
                $output.='<div class="col-md-12 col-12 form-group"> ';
                $output.='<button class="btn btn-info pull-right saveEditedQuarter" type="submit">Save Changes</button>';
                $output.='</div> ';
            }
        }
        $output.='</div>';
        echo $output;
    }
    function updateQuarter(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('termName')){
            $termName=$this->input->post('termName');
            $termStartDate=$this->input->post('termStartDate');
            $termEndDate=$this->input->post('termEndDate');
            $termID=$this->input->post('termID');
            $timestamp=strtotime($termStartDate);
            $newDateStart=date('d/m/y',$timestamp);
            $timestamp2=strtotime($termEndDate);
            $newDateStart2=date('d/m/y',$timestamp2);
            $data=array(
                'term'=>$termName,
                'startdate'=>$newDateStart,
                'endate'=>$newDateStart2
            );
            $query=$this->main_model->updateQuarter($data,$termID,$max_year);
            if($query){
                echo 'saved';
            }
        }
    }
    function saveLetterPolicy(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if($this->input->post('letter_grade')){
            $letter_grade=$this->input->post('letter_grade');
            $startRange=$this->input->post('startRange');
            $endRange=$this->input->post('endRange');
            $valtext=$this->input->post('valtext');

            foreach ($letter_grade as $kepolicy_grade) {
                $query=$this->db->query("select * from letterange where grade='$kepolicy_grade' and minValue='$startRange' and maxiValue='$endRange' and academicyear='$max_year' ");
                if($query->num_rows()<1){
                    $data[]=array(
                       'grade'=>$kepolicy_grade,
                       'minValue'=>$startRange,
                       'maxiValue'=>$endRange,
                       'letterVal'=>$valtext,
                       'academicYear'=>$max_year,
                       'dateCreated'=>date('M-d-Y')
                    );
                }
            }
            $this->db->insert_batch('letterange',$data); 
        }
    }
    function fetchLetterPolicy(){
        $maxIDQuery = $this->db->query("select max(id) as maxID from academicyear");
        $maxIDRow = $maxIDQuery->row();
        $max_ID=$maxIDRow->maxID;
        $maxYearQuery = $this->db->query("select year_name from academicyear where id='$max_ID' ");
        $maxyearRow = $maxYearQuery->row();
        $max_year=$maxyearRow->year_name;

        $queryLast_ID=$this->db->query("select * from academicyear where id < '$max_ID' ORDER BY id DESC LIMIT 1  ");
        if($queryLast_ID->num_rows()>0){
            $maxLstIaD = $queryLast_ID->row();
            $LastID=$maxLstIaD->id;
        }else{
            $LastID=$max_ID;
        }
        
        $minYearQuery = $this->db->query("select * from academicyear where id='$LastID' ");
        $lastyearRow = $minYearQuery->row();
        $minYear=$lastyearRow->year_name;

        echo $this->main_model->fetch_letter_policy($max_year,$minYear); 
    }
    function deleteLetterPolicy(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('r_id')){
            $r_id=$this->input->post('r_id');
            $minValue=$this->input->post('lminvalue');
            $maxValue=$this->input->post('lmaxvalue');
            $this->db->where('maxiValue',$maxValue);
            $this->db->where('minValue',$minValue);
            $this->db->where('letterVal',$r_id);
            $this->db->delete('letterange');
        }
    }
    function saveSchoolDivision(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        if($this->input->post('divisionName')){
            $divisionName=$this->input->post('divisionName');
            $query=$this->db->query("select * from schooldivision where academicyear='$max_year' and dname='$divisionName' ");
            if($query->num_rows()<1){
               $data=array(
               'dname'=>$divisionName,
               'academicyear'=>$max_year,
               'datecreated'=>date('M-d-Y')
              );
              $this->db->insert('schooldivision',$data); 
            }
        }
    }
    function fetchDivision(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetchSchoolDivision($max_year); 
    }
    function deleteDivision(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('r_id')){
            $r_id=$this->input->post('r_id');
            $this->db->where('did',$r_id);
            $this->db->delete('schooldivision');
        }
    }
    function fetchAssesment(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetchSchoolAssesment($max_year); 
    }
    function updateAssesmentMandatory(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('sasname')){
          $sasname=$this->input->post('sasname');
          $value=$this->input->post('value');
          $sasgrade=$this->input->post('sasgrade');
          $this->db->where('sasgrade',$sasgrade);
          $this->db->where('sasname',$sasname);
          $this->db->set('ismandatory',$value);
          $query=$this->db->update('schoolassesment');
        }
    }
    function updateAssesmentOrder(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('sasname')){
          $sasname=$this->input->post('sasname');
          $value=$this->input->post('value');
          $sasgrade=$this->input->post('sasgrade');
          $this->db->where('sasgrade',$sasgrade);
          $this->db->where('sasname',$sasname);
          $this->db->set('assorder',$value);
          $query=$this->db->update('schoolassesment');
        }
    }
    function updateAssesmentPercentage(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('sasname')){
          $sasname=$this->input->post('sasname');
          $value=$this->input->post('value');
          $sasgrade=$this->input->post('sasgrade');
          $this->db->where('sasgrade',$sasgrade);
          $this->db->where('sasname',$sasname);
          $this->db->set('saspercent',$value);
          $query=$this->db->update('schoolassesment');
        }
    }
    function saveSchoolAssesment(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('assesmentEval')){
            $assesmentEval=$this->input->post('assesmentEval');
            $assesmentEndDate=$this->input->post('assesmentEndDate');
            $assesmentName=$this->input->post('assesmentName');
            $assesmentGrade=$this->input->post('assesmentGrade');
            $assorder=$this->input->post('assorder');
            $ismandatory=$this->input->post('ismandatory');
            $assesmentPercent=$this->input->post('assesmentPercent');
            foreach($assesmentGrade as $assesmentGrades){
                $query=$this->db->query("select * from schoolassesment where academicyear='$max_year' and sasname='$assesmentName' and sasgrade='$assesmentGrades' ");
                if($query->num_rows()<1){
                   $data[]=array(
                   'sasgrade'=>$assesmentGrades,
                   'saseval'=>$assesmentEval,
                   'sasname'=>$assesmentName,
                   'saspercent'=>$assesmentPercent,
                   'ismandatory'=>$ismandatory,
                   'assorder'=>$assorder,
                   'dateend'=>$assesmentEndDate,
                   'academicyear'=>$max_year,
                   'createdby'=>$user,
                   'datecreated'=>date('M-d-Y')
                  );
                  
                }
            }
            if(!empty($data)){
                $this->db->insert_batch('schoolassesment',$data);
            } 
        }
    }
    function deleteAssesment(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('sasname')){
            $sasname=$this->input->post('sasname');
            $this->db->where('saseval',$sasname);
            $this->db->where('academicyear',$max_year);
            $this->db->delete('schoolassesment');
        }
    }
    function deleteAssesmentName(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('sasname')){
            $sasname=$this->input->post('sasname');
            $sasgrade=$this->input->post('sasgrade');
            $this->db->where('sasname',$sasname);
            $this->db->where('sasgrade',$sasgrade);
            $this->db->where('academicyear',$max_year);
            $this->db->delete('schoolassesment');
        }
    }
    function fetchrcComment(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $maxIDQuery = $this->db->query("select max(id) as maxID from academicyear");
        $maxIDRow = $maxIDQuery->row();
        $max_ID=$maxIDRow->maxID;
        $maxYearQuery = $this->db->query("select year_name from academicyear where id='$max_ID' ");
        $maxyearRow = $maxYearQuery->row();
        $max_year=$maxyearRow->year_name;

        $queryLast_ID=$this->db->query("select * from academicyear where id < '$max_ID' ORDER BY id DESC LIMIT 1  ");
        if($queryLast_ID->num_rows()>0){
            $maxLstIaD = $queryLast_ID->row();
            $LastID=$maxLstIaD->id;
        }else{
            $LastID=$max_ID;
        }
        $minYearQuery = $this->db->query("select * from academicyear where id='$LastID' ");
        $lastyearRow = $minYearQuery->row();
        $minYear=$lastyearRow->year_name;
        echo $this->main_model->fetchrcComments($max_year,$minYear); 
    }
    function saveReportcardComment(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('minValue')){
            $minValue=$this->input->post('minValue');
            $maxValue=$this->input->post('maxValue');
            $reportcardComment=$this->input->post('reportcardComment');
            $commentGrade=$this->input->post('commentGrade');
            foreach($commentGrade as $commentGrades){
                $query=$this->db->query("select * from reportcardcomments where academicyear='$max_year' and grade='$commentGrades' and commentvalue='$reportcardComment' and mingradevalue='$minValue' and maxgradevalue='$maxValue' ");
                if($query->num_rows()<1){
                    $data[]=array(
                       'grade'=>$commentGrades,
                       'mingradevalue'=>$minValue,
                       'maxgradevalue'=>$maxValue,
                       'commentvalue'=>$reportcardComment,
                       'academicyear'=>$max_year,
                       'createdby'=>$user,
                       'datecreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($data)){
                $this->db->insert_batch('reportcardcomments',$data);
            } 
        }
    }
    function deleteRCcomment(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('maxgradevalue')){
            $commentValue=$this->input->post('commentValue');
            $maxgradevalue=$this->input->post('maxgradevalue');
            $mingradevalue=$this->input->post('mingradevalue');
            $this->db->where('commentvalue',$commentValue);
            $this->db->where('mingradevalue',$mingradevalue);
            $this->db->where('maxgradevalue',$maxgradevalue);
            $this->db->delete('reportcardcomments');
        }
    }
    function checkNewAcademicYear(){
        /*$maxYearQuery = $this->db->query("select max(year_name) as year from academicyear");
        $maxyearRow = $maxYearQuery->row();
        $maxYear=$maxyearRow->year;
        if(is_numeric($maxYear)){
            $minYear=$maxYear - 1;
        }else{
            $minYear='';
        }
        $minYearQuery = $this->db->query("select * from academicyear where year_name='$minYear' ");
        $queryMaxYearQuarter = $this->db->query("select * from quarter where Academic_Year='$maxYear' ");
        if($minYearQuery->num_rows() > 0 && $queryMaxYearQuarter->num_rows() < 1){
            echo '<button class="btn btn-success" id="prepareEveryThing">Move Setting</button>
                <span class="time SettingText"> This will adjust new academic year setting from previous academic year.</span>';
        }*/
        $maxIDQuery = $this->db->query("select max(id) as maxID from academicyear");
        if($maxIDQuery->num_rows()>0){
            $maxIDRow = $maxIDQuery->row();
            $max_ID=$maxIDRow->maxID;

            $maxYearQuery = $this->db->query("select year_name from academicyear where id='$max_ID' ");
            $maxyearRow = $maxYearQuery->row();
            $maxYear=$maxyearRow->year_name;

            $queryLast_ID=$this->db->query("select * from academicyear where id < '$max_ID' ORDER BY id DESC LIMIT 1  ");
            if($queryLast_ID->num_rows()>0){
                $maxLastID = $queryLast_ID->row();
                $LastID=$maxLastID->id;
                $minYearQuery = $this->db->query("select * from academicyear where id='$LastID' ");
                $lastyearRow = $minYearQuery->row();
                $LastYear=$lastyearRow->year_name;
                $queryMaxYearQuarter = $this->db->query("select * from quarter where Academic_Year='$maxYear' ");
                if($minYearQuery->num_rows() > 0 && $queryMaxYearQuarter->num_rows() < 1){
                    echo '<button class="btn btn-success" id="prepareEveryThing">Move Setting</button>
                    <span class="time SettingText"> This will adjust new academic year setting from previous academic year.</span>';
                }
            }
        }
    }
    function saveTotalPolicy(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $rowname=$this->input->post('rowname');
            $data=array(
                'allowed'=>'1',
                'grade'=>$grade,
                'rowname'=>$rowname,
                'academicyear'=>$max_year,
                'datecreated'=>date('M-d-Y')
            );
            $this->main_model->insert_rank_policy($data,$max_year); 
        }
    }
    function deleteTotalPolicy(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $rowname=$this->input->post('rowname');
            $this->db->where('grade',$grade);
            $this->db->where('rowname',$rowname);
            $this->db->delete('rank_allowed_grades'); 
        }
    }
    function saveAveragePolicy(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $rowname=$this->input->post('rowname');
            $data=array(
                'allowed'=>'1',
                'grade'=>$grade,
                'rowname'=>$rowname,
                'academicyear'=>$max_year,
                'datecreated'=>date('M-d-Y')
            );
            $this->main_model->insert_rank_policy($data,$max_year); 
        }
    }
    function deleteAveragePolicy(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $rowname=$this->input->post('rowname');
            $this->db->where('grade',$grade);
            $this->db->where('rowname',$rowname);
            $this->db->delete('rank_allowed_grades'); 
        }
    }
    function saveRankPolicy(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $rowname=$this->input->post('rowname');
            $data=array(
                'allowed'=>'1',
                'grade'=>$grade,
                'rowname'=>$rowname,
                'academicyear'=>$max_year,
                'datecreated'=>date('M-d-Y')
            );
            $this->main_model->insert_rank_policy($data,$max_year); 
        }
    }
    function deleteRankPolicy(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade')){
            $grade=$this->input->post('grade');
            $rowname=$this->input->post('rowname');
            $this->db->where('grade',$grade);
            $this->db->where('rowname',$rowname);
            $this->db->delete('rank_allowed_grades'); 
        }
    }
    function movingSubjectObjective(){
        $user=$this->session->userdata('username');
        $maxYearQuery = $this->db->query("select max(year_name) as year from academicyear");
        $maxyearRow = $maxYearQuery->row();
        $maxYear=$maxyearRow->year;

        $minYear=$maxYear - 1;
        $queryCurrentQuarter = $this->db->query("select max(term) as quarter from quarter where Academic_year='$maxYear' ");
        foreach($queryCurrentQuarter->result() as $querterName){
            $querter=$querterName->quarter;
            $queryMinYearKgSubjectObj = $this->db->query("select * from kgsubjectobjective where academicyear='$minYear' and quarter='$querter' ");
            if($queryMinYearKgSubjectObj->num_rows() > 0){            
                foreach($queryMinYearKgSubjectObj->result() as $SubjectOb){
                    if($querter==$SubjectOb->quarter){
                        $subid=$SubjectOb->subid;
                        $ograde=$SubjectOb->ograde;
                        $subobjective=$SubjectOb->subobjective;
                        $linksubject=$SubjectOb->linksubject;
                        $queryMaxYearKgSubjectObj = $this->db->query("select * from kgsubjectobjective where academicyear='$maxYear' and subid='$subid' and ograde='$ograde' and quarter='$querter' and linksubject='$linksubject' ");
                        if($queryMaxYearKgSubjectObj->num_rows() < 1){
                            $kgObjData[]=array(
                                'subid'=>$SubjectOb->subid,
                                'ograde'=>$SubjectOb->ograde,
                                'subobjective'=>$SubjectOb->subobjective,
                                'academicyear'=>$maxYear,
                                'quarter'=>$querter,
                                'linksubject'=>$SubjectOb->linksubject,
                                'datecreated'=>date('M-d-Y')
                            );
                        }
                    }
                }
                if(!empty($kgObjData)){
                    $queryKgObj=$this->db->insert_batch('kgsubjectobjective',$kgObjData);
                    if($queryKgObj){
                        echo 'School KG Subject Objective setting Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                    }
                }
            }
        }
    }
    function moveSetting(){
        $user=$this->session->userdata('username');

        $maxIDQuery = $this->db->query("select max(id) as maxID from academicyear");
        $maxIDRow = $maxIDQuery->row();
        $max_ID=$maxIDRow->maxID;
        $maxYearQuery = $this->db->query("select year_name from academicyear where id='$max_ID' ");
        $maxyearRow = $maxYearQuery->row();
        $maxYear=$maxyearRow->year_name;

        $queryLast_ID=$this->db->query("select * from academicyear where id < '$max_ID' ORDER BY id DESC LIMIT 1  ");
        $maxLstIaD = $queryLast_ID->row();
        $LastID=$maxLstIaD->id;
        $minYearQuery = $this->db->query("select * from academicyear where id='$LastID' ");
        $lastyearRow = $minYearQuery->row();
        $minYear=$lastyearRow->year_name;

                
            
        

        $queryMinYearBranch = $this->db->query("select * from branch where academicyear='$minYear' ");
        if($queryMinYearBranch->num_rows() > 0){            
            foreach($queryMinYearBranch->result() as $branchName){
                $branch=$branchName->name;
                $queryMaxYearBranch = $this->db->query("select * from branch where academicyear='$maxYear' and name='$branch' ");
                if($queryMaxYearBranch->num_rows() < 1){
                    $data[]=array(
                        'name'=>$branchName->name,
                        'academicyear'=>$maxYear,
                        'datecreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($data)){
                $queryBranch=$this->db->insert_batch('branch',$data);
                if($queryBranch){
                    echo 'Branch setting Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
        /*Promotion Policy movement*/
        $queryMinYearPromotion = $this->db->query("select * from promotion_policy where academicyear='$minYear' ");
        if($queryMinYearPromotion->num_rows() > 0){            
            foreach($queryMinYearPromotion->result() as $promotionName){
                $average=$promotionName->average;
                $grade=$promotionName->grade;
                $queryMaxYearPromotion = $this->db->query("select * from promotion_policy where academicyear='$maxYear' and grade='$grade' ");
                if($queryMaxYearPromotion->num_rows() < 1){
                    $promotionData[]=array(
                        'average'=>$promotionName->average,
                        'grade'=>$promotionName->grade,
                        'academicyear'=>$maxYear,
                        'datecreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($promotionData)){
                $queryPromotion=$this->db->insert_batch('promotion_policy',$promotionData);
                if($queryPromotion){
                    echo 'Promotion Policy setting Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
        /*Rank policy Movement*/

        $queryMinYearRankPolicy = $this->db->query("select * from rank_allowed_grades where academicyear='$minYear' ");
        if($queryMinYearRankPolicy->num_rows() > 0){            
            foreach($queryMinYearRankPolicy->result() as $rankPolicyName){
                $grade=$rankPolicyName->grade;
                $queryMaxYearRankPolicy = $this->db->query("select * from rank_allowed_grades where academicyear='$maxYear' and grade='$grade' ");
                if($queryMaxYearRankPolicy->num_rows() < 1){
                    $rankPolicyData[]=array(
                        'allowed'=>$rankPolicyName->allowed,
                        'grade'=>$rankPolicyName->grade,
                        'academicyear'=>$maxYear,
                        'datecreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($rankPolicyData)){
                $queryRankPolicy=$this->db->insert_batch('rank_allowed_grades',$rankPolicyData);
                if($queryRankPolicy){
                    echo 'Rank Policy setting Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
        /*Letter Grade Policy Movement*/
        $queryMinYearLetterPolicy = $this->db->query("select * from letterange where academicYear='$minYear' ");
        if($queryMinYearLetterPolicy->num_rows() > 0){            
            foreach($queryMinYearLetterPolicy->result() as $LetterPolicyName){
                $grade=$LetterPolicyName->grade;
                $minValue=$LetterPolicyName->minValue;
                $maxiValue=$LetterPolicyName->maxiValue;
                $queryMaxYearLetterPolicy = $this->db->query("select * from letterange where academicYear='$maxYear' and grade='$grade' and minValue='$minValue' and maxiValue='$maxiValue' ");
                if($queryMaxYearLetterPolicy->num_rows() < 1){
                    $letterPolicyData[]=array(
                        'grade'=>$LetterPolicyName->grade,
                        'minValue'=>$LetterPolicyName->minValue,
                        'maxiValue'=>$LetterPolicyName->maxiValue,
                        'letterVal'=>$LetterPolicyName->letterVal,
                        'academicYear'=>$maxYear,
                        'dateCreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($letterPolicyData)){
                $queryLetterPolicy=$this->db->insert_batch('letterange',$letterPolicyData);
                if($queryLetterPolicy){
                    echo 'Letter Grade Policy setting Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
        /*School Division Movement*/
        $queryMinYearDivision = $this->db->query("select * from schooldivision where academicyear='$minYear' ");
        if($queryMinYearDivision->num_rows() > 0){            
            foreach($queryMinYearDivision->result() as $divisionName){
                $dname=$divisionName->dname;
                $queryMaxYeardivision = $this->db->query("select * from schooldivision where academicyear='$maxYear' and dname='$dname' ");
                if($queryMaxYeardivision->num_rows() < 1){
                    $divisionData[]=array(
                        'dname'=>$divisionName->dname,
                        'academicyear'=>$maxYear,
                        'datecreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($divisionData)){
                $queryDivision=$this->db->insert_batch('schooldivision',$divisionData);
                if($queryDivision){
                    echo 'Division setting Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
        /*School Assesment Movement*/
        $queryMinYearAssesment = $this->db->query("select * from schoolassesment where academicyear='$minYear' ");
        if($queryMinYearAssesment->num_rows() > 0){            
            foreach($queryMinYearAssesment->result() as $assesName){
                $sasgrade=$assesName->sasgrade;
                $saseval=$assesName->saseval;
                $sasname=$assesName->sasname;
                $queryMaxYearAssesment = $this->db->query("select * from schoolassesment where academicyear='$maxYear' and sasgrade='$sasgrade' and saseval='$saseval' and sasname='$sasname' ");
                if($queryMaxYearAssesment->num_rows() < 1){
                    $assementData[]=array(
                        'sasgrade'=>$assesName->sasgrade,
                        'saseval'=>$assesName->saseval,
                        'sasname'=>$assesName->sasname,
                        'academicyear'=>$maxYear,
                        'createdby'=>$assesName->createdby,
                        'datecreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($assementData)){
                $queryAssesment=$this->db->insert_batch('schoolassesment',$assementData);
                if($queryAssesment){
                    echo 'School Assesment setting Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
        /*Quarter movement*/
        $queryMinYearQuarter = $this->db->query("select termgroup,min(term) as quarterr from quarter where Academic_year='$minYear' ");
        if($queryMinYearQuarter->num_rows() > 0){
            foreach($queryMinYearQuarter->result() as $quarterName){
                $quarter=$quarterName->quarterr;
                $termgroup=$quarterName->termgroup;
                $queryMaxYearQuarter = $this->db->query("select * from quarter where Academic_year='$maxYear' and term='$quarter' ");
                if($queryMaxYearQuarter->num_rows() < 1){
                    $dataQuarter[]=array(
                        'term'=>$quarter,
                        'startdate'=>date('d/m/y'),
                        'endate'=>date('d/m/y'),
                        'termgroup'=>$termgroup,
                        'Academic_year'=>$maxYear,
                        'date_created'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($dataQuarter)){
                $queryQuarter=$this->db->insert_batch('quarter',$dataQuarter);
                if($queryQuarter){
                    echo 'Quater setting Moved<span class="text-danger"> Please set quarter StartDate && EndDate</span> <i class="fas fa-check-circle"> </i> ';
                }
            }
        }
        /*---Evaluation---*/
        $queryMinYearMinEva = $this->db->query("select min(quarter) as minTerm from evaluation where academicyear='$minYear' ");
        $rowMinTerm=$queryMinYearMinEva->row();
        $minTerm=$rowMinTerm->minTerm;
        $queryMinYearEvaluation = $this->db->query("select * from evaluation where academicyear='$minYear' and quarter='$minTerm' ");
        if($queryMinYearEvaluation->num_rows() > 0){
            foreach($queryMinYearEvaluation->result() as $evaName){
                $quarterEva=$evaName->quarter;
                $queryMaxYearEvaluation = $this->db->query("select * from evaluation where academicyear='$maxYear' and quarter='$quarterEva' ");
                if($queryMaxYearEvaluation->num_rows() < 1){
                    $dataEvaluation[]=array(
                        'evname'=>$evaName->evname,
                        'quarter'=>$evaName->quarter,
                        'grade'=>$evaName->grade,
                        'percent'=>$evaName->percent,
                        'evgroup'=>$evaName->evgroup,
                        'academicyear'=>$maxYear,
                        'date_created'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($dataEvaluation)){
                $queryEva=$this->db->insert_batch('evaluation',$dataEvaluation);
                if($queryEva){
                    echo 'Evaluation setting Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
        /*Subject setting Movement*/ 
        $queryMinYearSubject = $this->db->query("select * from subject where Academic_Year='$minYear' ");
        if($queryMinYearSubject->num_rows() > 0){            
            foreach($queryMinYearSubject->result() as $subjectName){
                $subGrade=$subjectName->Grade;
                $subName=$subjectName->Subj_name;
                $queryMaxYearSubject = $this->db->query("select * from subject where Academic_Year='$maxYear' and Grade='$subGrade' and Subj_name='$subName' ");
                if($queryMaxYearSubject->num_rows() < 1){
                    $subjectData[]=array(
                        'Subj_name'=>$subjectName->Subj_name,
                        'Subj_Av'=>$subjectName->Subj_Av,
                        'Grade'=>$subjectName->Grade,
                        'letter'=>$subjectName->letter,
                        'Merged_name'=>$subjectName->Merged_name,
                        'Merged_percent'=>$subjectName->Merged_percent,
                        'Academic_Year'=>$maxYear,
                        'onreportcard'=>$subjectName->onreportcard,
                        'suborder'=>$subjectName->suborder,
                        'status'=>$subjectName->status,
                        'school'=>$subjectName->school,
                        'date_created'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($subjectData)){
                $querySubject=$this->db->insert_batch('subject',$subjectData);
                if($querySubject){
                    echo 'School subject setting Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
        /*KG Subject Movement*/
        $queryMinYearKgSubject = $this->db->query("select * from kgsubject where academicyear='$minYear' ");
        if($queryMinYearKgSubject->num_rows() > 0){            
            foreach($queryMinYearKgSubject->result() as $kgSubjectName){
                $subgrade=$kgSubjectName->subgrade;
                $subname=$kgSubjectName->subname;
                $queryMaxYearKgSubject = $this->db->query("select * from kgsubject where academicyear='$maxYear' and subgrade='$subgrade' and subname='$subname' ");
                if($queryMaxYearKgSubject->num_rows() < 1){
                    $kgSubjectData[]=array(
                        'subname'=>$kgSubjectName->subname,
                        'subgrade'=>$kgSubjectName->subgrade,
                        'letter'=>$kgSubjectName->letter,
                        'percentage'=>$kgSubjectName->percentage,
                        'academicyear'=>$maxYear,
                        'onreportcard'=>$kgSubjectName->onreportcard,
                        'suborder'=>$kgSubjectName->suborder,
                        'quarter'=>$kgSubjectName->quarter,
                        'datecreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($kgSubjectData)){
                $queryKgSubject=$this->db->insert_batch('kgsubject',$kgSubjectData);
                if($queryKgSubject){
                    echo 'School KG subject setting Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }        
                                               
    }
    function lockMarkAutomatically(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->lockMarkAutomatically($max_year);
    }
    function onoff_registration_page(){
        echo $this->main_model->on_off_registration_page();
    }
    function studentsCanSeeQuarterResult(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->studentsCanSeeQuarterResult($max_year);
    }
    function check_markresult_schedule(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->check_markresult_schedule($max_year);
    }
    function schedule_task(){
        $user=$this->session->userdata('username');
        $dataToday=date('Y-m-d');
        if($this->input->post('scheduleType')){
            $scheduleType=$this->input->post('scheduleType');
            $scheduleDate=$this->input->post('scheduleDate');
            $queryCheck=$this->db->query("select * from schedule_gs where scheduled_task='$scheduleType' and $scheduleDate > '$dataToday' ");
            if($queryCheck->num_rows()>0){
                echo '<div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                          <span>&times;</span>
                        </button><i class="fas fa-exclamation-triangle "></i> Please romove the same task from the scheduled list.
                    </div>
                </div>';
            }else{
                $queryCheckdate=$this->db->query("select * from schedule_gs where scheduled_for='$scheduleDate' ");
                if($queryCheckdate->num_rows()>0){
                    echo '<div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                              <span>&times;</span>
                            </button><i class="fas fa-times-circle"></i> Ooops, this date has been occupied.
                        </div>
                    </div>';
                }else{
                    $data=array(
                        'scheduled_task'=>$scheduleType,
                        'scheduled_for'=>$scheduleDate,
                        'scheduled_by'=>$user,
                        'scheduled_at'=>date('Y-m-d'),
                        'scheduled_status'=>'0'
                    );
                    $query=$this->db->insert('schedule_gs',$data);
                    if($query){
                        echo '<div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                  <span>&times;</span>
                                </button><i class="fas fa-exclamation-triangle "></i> Schedule saved successfully.
                            </div>
                        </div>';
                    }else{
                        echo '<div class="alert alert-warning alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                  <span>&times;</span>
                                </button><i class="fas fa-exclamation-triangle "></i> Ooops Please try again.
                            </div>
                        </div>';
                    }
                }
            }
        }
    }
    function delete_schedule_task(){
        if($this->input->post('scheduleID')){
            $scheduleID=$this->input->post('scheduleID');
            $this->db->where('id',$scheduleID);
            $query=$this->db->delete('schedule_gs');
        }
    }
    function ageCalculationMethod(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        date_default_timezone_set('Africa/Addis_Ababa');
        echo $this->main_model->ageCalculationMethod($max_year);
    }
    function updateAgeMethod(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $gYearName=date('Y');
        if($this->input->post('valueAge')){
            $this->db->where('academicyear',$max_year);
            $queryCheck = $this->db->get('agecalculation');
            if($queryCheck->num_rows()>0){
                $value=$this->input->post('valueAge');
                $this->db->where('academicyear',$max_year);
                $this->db->set('age_method',$value);
                $query=$this->db->update('agecalculation');
                if($query){
                    $queryCalculate=$this->db->query("select dob,age,username from users where academicyear='$max_year' and dob!='' and usertype='Student' or academicyear='$max_year' and dob!='0000-00-00' and usertype='Student'  or academicyear='$max_year' and dob!='0000/00/00' and usertype='Student' group by id ");
                    if($queryCalculate->num_rows()>0){
                        foreach($queryCalculate->result() as $stuRow){
                            $username=$stuRow->username;
                            $birthday =$stuRow->dob;
                            $orderdate = explode('-', $birthday);
                            $year  = $orderdate[0];
                            if(is_numeric($year) && $year>0){
                                if($value=='Ethiopian'){
                                    $age=$max_year - $year;
                                }else{
                                    $age=$gYearName - $year;
                                }
                                $this->db->where('dob!=','0000-00-00');
                                $this->db->where('username',$username);
                                $this->db->where('academicyear',$max_year);
                                $this->db->set('age',$age);
                                $queryUpdate=$this->db->update('users');
                            }
                        }
                    }
                }
            }else{
                $value=$this->input->post('valueAge');
                $data=array(
                    'age_method'=>$value,
                    'academicyear'=>$max_year,
                    'created_by'=>$user,
                    'date_created'=>date('M-d-Y')
                );
                $query=$this->db->insert('agecalculation',$data); 
                if($query){
                    $queryCalculate=$this->db->query("select dob,age,username from users where academicyear='$max_year' and dob!='' and usertype='Student' or academicyear='$max_year' and dob!='0000-00-00' and usertype='Student' ");
                    if($queryCalculate->num_rows()>0){
                        foreach($queryCalculate->result() as $stuRow){
                            $username=$stuRow->username;
                            $birthday =$stuRow->dob;
                            $date = DateTime::createFromFormat("Y-m-d", $birthday);
                            $year= $date->format("Y");
                            if($value=='Ethiopian'){
                                $age=$max_year - $year;
                            }else{
                                $age=$gYearName - $year;
                            }
                            $this->db->where('dob!=','0000-00-00');
                            $this->db->where('username',$username);
                            $this->db->where('academicyear',$max_year);
                            $this->db->set('age',$age);
                            $queryUpdate=$this->db->update('users');
                        }
                    }
                }
            }
        }
    }
    function save_registration_page_status(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('lockmark')){
            $lockmark=$this->input->post('lockmark');
            $querySelect=$this->db->query("select * from studentregistrationstatus ");
            if($querySelect->num_rows()>0){
                $this->db->set('registration_status','1');
                $this->db->update('studentregistrationstatus'); 
            }else{
                $data=array(
                    'registration_status'=>$lockmark,
                    'academicyear'=>$max_year,
                    'statusby'=>$user,
                    'datemodified'=>date('M-d-Y')
                );
                $this->db->insert('studentregistrationstatus',$data); 
            }
        }
    }
    function delete_registration_page_status(){
        $this->db->set('registration_status','0');
        $this->db->update('studentregistrationstatus'); 
    }
    function saveLockMarkAuto(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        if($this->input->post('lockmark')){
            $lockmark=$this->input->post('lockmark');
            $data=array(
                'autolockstatus'=>$lockmark,
                'academicyear'=>$max_year,
                'lockby'=>$user,
                'datelocked'=>date('M-d-Y')
            );
            $this->db->insert('lockmarkauto',$data); 
        }
    }
    function save_can_see_report_card(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('quarter')){
            $quarter=$this->input->post('quarter');
            $data=array(
                'quarter_status'=>'1',
                'quarter'=>$quarter,
                'academicyear'=>$max_year,
                'lockby'=>$user,
                'datelocked'=>date('M-d-Y')
            );
            $this->db->insert('studentcanseecard',$data); 
        }
    }
    function delete_can_see_report_card(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $max_year=$row->year;
         if($this->input->post('quarter')){
            $quarter=$this->input->post('quarter');
            $this->db->where('quarter',$quarter);
            $this->db->where('academicyear',$max_year);
            $this->db->delete('studentcanseecard'); 
        }
    }
    function enableHomeRoomAccess(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->enableHomeRoomAccess($max_year);
    }
    function deleteLockMarkAuto(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        /*if($this->input->post('lockmark')){
            $lockmark=$this->input->post('lockmark');*/
            $this->db->where('academicyear',$max_year);
            $this->db->delete('lockmarkauto'); 
        //}
    }
    function enableMarkAuto(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->enableMarkAuto($max_year);
    }
    function enableCommunicationBookAuto(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->enableCommunicationBookAuto($max_year);
    }
    function saveEnableCoomBookApprove(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('markon')){
            $markon=$this->input->post('markon');
            $data=array(
                'markapprove'=>$markon,
                'academicyear'=>$max_year,
                'enabledby'=>$user,
                'dateenabled'=>date('M-d-Y')
            );
            $this->db->insert('enableapprovecommubook',$data); 
        }
    }
    function deleteEnableCoomBookApprove(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $this->db->where('academicyear',$max_year);
        $this->db->delete('enableapprovecommubook'); 
    }
    function saveEnableMarkApprove(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        if($this->input->post('markon')){
            $markon=$this->input->post('markon');
            $data=array(
                'markapprove'=>$markon,
                'academicyear'=>$max_year,
                'enabledby'=>$user,
                'dateenabled'=>date('M-d-Y')
            );
            $this->db->insert('enableapprovemark',$data); 
        }
    }
    function deleteEnableMarkApprove(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $this->db->where('academicyear',$max_year);
        $this->db->delete('enableapprovemark'); 
    }
    function saveEnableHomeRoomAcces(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        if($this->input->post('markon')){
            $markon=$this->input->post('markon');
            $data=array(
                'accessmark'=>$markon,
                'academicyear'=>$max_year,
                'createdby'=>$user,
                'datecreated'=>date('M-d-Y')
            );
            $this->db->insert('hoomroomaccess',$data); 
        }
    }
    function deleteHomeRomAccess(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $this->db->where('academicyear',$max_year);
        $this->db->delete('hoomroomaccess'); 
    }
    function save_new_week(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('week_Name')){
          $week_Name=trim($this->input->post('week_Name'));
          $start_date=trim($this->input->post('start_date'));
          $end_date=trim($this->input->post('end_date'));
          $new_start_date = date('d/m/Y', strtotime($start_date));
          $new_end_date = date('d/m/Y', strtotime($end_date));
          $data=array(
              'week_name'=>$week_Name,
              'start_date'=>$new_start_date,
              'end_date'=>$new_end_date,
              'academicyear'=>$max_year,
              'createdby'=>$user,
              'created_at'=>date('M-d-Y')
          );
          $query=$this->main_model->register_new_school_week($data,$week_Name,$max_year);
          if($query){
              echo '<span class="text-success">Saved Successfully</span>';
          }else{
              echo '<span class="text-danger">Week Exists.</span>';
          }
        }
    }
    function delete_school_week(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['textId'])){
          $id=$this->input->post('textId');
          $this->db->where('week_name',$id);
          $this->db->where('academicyear',$max_year);
          $query=$this->db->delete('school_week');
        }
    }
    function load_school_week(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->load_school_week($max_year);
    }
    function save_new_non_working_dates(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('non_working_dats')){
          $non_working_dats=trim($this->input->post('non_working_dats'));
          $non_working_reason=trim($this->input->post('non_working_reason'));
          $new_end_date = date('d/m/Y', strtotime($non_working_dats));
          $data=array(
              'non_working_date'=>$new_end_date,
              'reason_date'=>$non_working_reason,
              'academicyear'=>$max_year,
              'createdby'=>$user,
              'created_at'=>date('M-d-Y')
          );
          $query=$this->main_model->register_new_school_non_working_dates($data,$new_end_date,$max_year);
          if($query){
              echo '<span class="text-success">Saved Successfully</span>';
          }else{
              echo '<span class="text-danger">Date Exists.</span>';
          }
        }
    }
    function load_school_non_working_dates(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->load_school_non_working_dates($max_year);
    }
    function delete_school_non_working_dates(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['textId'])){
          $id=$this->input->post('textId');
          $this->db->where('non_working_date',$id);
          $this->db->where('academicyear',$max_year);
          $query=$this->db->delete('school_non_working_days');
        }
    }
    function movingLastyearLetterRange(){
        $user=$this->session->userdata('username');
        $maxIDQuery = $this->db->query("select max(id) as maxID from academicyear");
        $maxIDRow = $maxIDQuery->row();
        $max_ID=$maxIDRow->maxID;
        $maxYearQuery = $this->db->query("select year_name from academicyear where id='$max_ID' ");
        $maxyearRow = $maxYearQuery->row();
        $maxYear=$maxyearRow->year_name;

        $queryLast_ID=$this->db->query("select * from academicyear where id < '$max_ID' ORDER BY id DESC LIMIT 1  ");
        $maxLstIaD = $queryLast_ID->row();
        $LastID=$maxLstIaD->id;
        $minYearQuery = $this->db->query("select * from academicyear where id='$LastID' ");
        $lastyearRow = $minYearQuery->row();
        $minYear=$lastyearRow->year_name;
        $data1=array();
        $queryLastYear = $this->db->query("select * from letterange where academicYear='$minYear' group by leId ");
        if($queryLastYear->num_rows() > 0){            
            foreach($queryLastYear->result() as $branchName){
                $grade=$branchName->grade;
                $minValue=$branchName->minValue;
                $maxiValue=$branchName->maxiValue;
                $queryMaxYearBranch = $this->db->query("select * from letterange where academicYear='$maxYear' and grade='$grade' and minValue='$minValue' and maxiValue='$maxiValue' ");
                if($queryMaxYearBranch->num_rows() < 1){
                    $data1[]=array(
                        'grade'=>$branchName->grade,
                        'minValue'=>$branchName->minValue,
                        'maxiValue'=>$branchName->maxiValue,
                        'letterVal'=>$branchName->letterVal,
                        'academicYear'=>$maxYear,
                        'dateCreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($data1)){
                $queryRange=$this->db->insert_batch('letterange',$data1);
                if($queryRange){
                    echo 'Letter Range MOved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
    }
    function movingLastyearComments(){
        $user=$this->session->userdata('username');
        $maxIDQuery = $this->db->query("select max(id) as maxID from academicyear");
        $maxIDRow = $maxIDQuery->row();
        $max_ID=$maxIDRow->maxID;
        $maxYearQuery = $this->db->query("select year_name from academicyear where id='$max_ID' ");
        $maxyearRow = $maxYearQuery->row();
        $maxYear=$maxyearRow->year_name;

        $queryLast_ID=$this->db->query("select * from academicyear where id < '$max_ID' ORDER BY id DESC LIMIT 1  ");
        $maxLstIaD = $queryLast_ID->row();
        $LastID=$maxLstIaD->id;
        $minYearQuery = $this->db->query("select * from academicyear where id='$LastID' ");
        $lastyearRow = $minYearQuery->row();
        $minYear=$lastyearRow->year_name;
        $data1=array();
        $queryLastYear = $this->db->query("select * from reportcardcomments where academicyear='$minYear' group by id ");
        if($queryLastYear->num_rows() > 0){            
            foreach($queryLastYear->result() as $branchName){
                $grade=$branchName->grade;
                $mingradevalue=$branchName->mingradevalue;
                $maxgradevalue=$branchName->maxgradevalue;
                $queryMaxYearBranch = $this->db->query("select * from reportcardcomments where academicyear='$maxYear' and grade='$grade' and mingradevalue='$mingradevalue' and maxgradevalue='$maxgradevalue' ");
                if($queryMaxYearBranch->num_rows() < 1){
                    $data1[]=array(
                        'grade'=>$branchName->grade,
                        'mingradevalue'=>$branchName->mingradevalue,
                        'maxgradevalue'=>$branchName->maxgradevalue,
                        'commentvalue'=>$branchName->commentvalue,
                        'createdby'=>$user,
                        'academicyear'=>$maxYear,
                        'datecreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($data1)){
                $queryRange=$this->db->insert_batch('reportcardcomments',$data1);
                if($queryRange){
                    echo 'Letter Range MOved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
    }
    public function fetchPromotionPolicy()
    {
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetch_promotion_policy($max_year); 
    } 
    public function savePromotionPolicy()
    {
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('policy_grade')){
            $policy_average=$this->input->post('policy_average');
            $policy_grade=$this->input->post('policy_grade');
            $failedSubjects=$this->input->post('failedSubjects');
            foreach ($policy_grade as $kepolicy_grade) {
                $query=$this->db->query("select * from promotion_policy where academicyear='$max_year' and grade='$kepolicy_grade' and average='$policy_average' ");
                if($query->num_rows()>0){
                    $data=array(
                        'average'=>$policy_average,
                        'grade'=>$kepolicy_grade,
                        'total_failed_subject'=>$failedSubjects,
                        'academicyear'=>$max_year,
                        'datecreated'=>date('M-d-Y')
                    );
                    $this->main_model->update_promotion_policy($data,$kepolicy_grade,$max_year); 
                }
                else { 
                    $data=array(
                        'average'=>$policy_average,
                        'grade'=>$kepolicy_grade,
                        'total_failed_subject'=>$failedSubjects,
                        'academicyear'=>$max_year,
                        'datecreated'=>date('M-d-Y')
                    );
                    $this->main_model->insert_promotion_policy($data,$max_year); 
                }
            }
        }
    } 
    public function deletePromotionPolicy(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['textId'])){
            $id=$this->input->post('textId');
            $this->db->where('id',$id);
            $query=$this->db->delete('promotion_policy');
        }
    }

}