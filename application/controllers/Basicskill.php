<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basicskill extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='addRemoveBS' order by id ASC "); 

        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='basicskill')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php'))
        {
            show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['delte_id']))
        {
            $id=$this->input->post('delte_id');
            $quarter=$this->input->post('quarter');
            $this->main_model->delete_bsname($id,$max_year,$quarter);
        }
        $data['fetch_term']=$this->main_model->fetch_term_desc($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['bscategory']=$this->main_model->fetch_bscategory($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['enable_sub_category']=$this->main_model->enable_bs_sub_categories_status($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    public function record_bs_Names()
    {
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if(isset($_POST['id'])){
            $bsname1=trim(str_replace("'", "`",$this->input->post('bsname')));
            $bsname=trim(str_replace('"', '``',$bsname1));
            $grade=$this->input->post('id');
            $linkcategory=$this->input->post('linkcategory');
            $bssubname=$this->input->post('bssubname');
            $name_quarter=$this->input->post('name_quarter');
            foreach ($grade as $grades) {
                $query=$this->main_model->insert_bs_name($bsname,$grades,$max_year,$name_quarter);
                if($query){
                    $data[]=array(
                        'bsname'=>$bsname,
                        'grade'=>$grades,
                        'bscategory'=>$linkcategory,
                        'sub_category'=>$bssubname,
                        'season'=>$name_quarter,
                        'academicyear'=>$max_year,
                        'datecreated'=>date('M-d-Y'),
                        'byuser'=>$user
                    );
                }
            }
            if(!empty($data)){
                $query=$this->db->insert_batch('basicskill',$data);
                if($query){
                    echo '1';
                }else{
                    echo '0';
                }
            }
        }
    }
    function searchBS_Names(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year'");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;

        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->main_model->search_BS_Names($searchItem,$max_year,$max_quarter);
        }
    }
    function insertBsCategory(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bsnamecate')){
            $grade=$this->input->post('grade');
            $bsnamecate=trim($this->input->post('bsnamecate'));
            $bsname_quarter=$this->input->post('bsname_quarter');
            foreach ($grade as $grades) {
                $queryCheck=$this->db->query("select * from bscategory where academicyear='$max_year' and bscategory='$bsnamecate' and season='$bsname_quarter' and bcgrade='$grades' ");
                if($queryCheck->num_rows()<1 ){
                    $data[]=array(
                        'bscategory'=>$bsnamecate,
                        'season'=>$bsname_quarter,
                        'bcgrade'=>$grades,
                        'academicyear'=>$max_year,
                        'datecreated'=>date('M-d-Y'),
                        'byuser '=>$user
                    );
                }
            }
            $query=$this->db->insert_batch('bscategory',$data);
        }
    }
    function fetchBsCategory(){
        $user=$this->session->userdata('username');
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
            $LastID='';
        }
        $minYearQuery = $this->db->query("select * from academicyear where id='$LastID' ");
        if($minYearQuery->num_rows()>0){
            $lastyearRow = $minYearQuery->row();
            $minYear=$lastyearRow->year_name;
        }else{
            $minYear='';
        }
        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year' ");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $queryMin = $this->db->query("select min(term) as quarter from quarter where Academic_year='$max_year' ");
        $rowMin = $queryMin->row();
        $min_quarter=$rowMin->quarter;
        echo $this->main_model->fetchBsCategory($max_year,$max_quarter,$min_quarter,$minYear);
    }
    function updateCatLeftRow(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('catName')){
          $catName=$this->input->post('catName');
          $catStatus=$this->input->post('catStatus');
          $this->db->where('academicyear',$max_year);
          $this->db->where('bscategory',$catName);
          $this->db->set('bcsubjectrow',$catStatus);
          $this->db->update('bscategory');
        }
    }
    function updateCatRightRow(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('catName')){
          $catName=$this->input->post('catName');
          $catStatus=$this->input->post('catStatus');
          $this->db->where('academicyear',$max_year);
          $this->db->where('bscategory',$catName);
          $this->db->set('bcsubjectrow',$catStatus);
          $this->db->update('bscategory');
        }
    }
    function updateBSOrderOrder(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('suborder')){
          $suborder=$this->input->post('suborder');
          $bsName=$this->input->post('bsName');
          $quarter=$this->input->post('quarter');
          $this->db->where('academicyear',$max_year);
          $this->db->where('bsname',$bsName);
          $this->db->where('season',$quarter);
          $this->db->set('bsorder',$suborder);
          $this->db->update('basicskill');
        }
    }
    function updateSpecificBSOrderOrder(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('suborder')){
          $suborder=$this->input->post('suborder');
          $bsName=$this->input->post('bsName');
          $quarter=$this->input->post('quarter');
          $grade=$this->input->post('grade');
          $this->db->where('academicyear',$max_year);
          $this->db->where('bsname',$bsName);
          $this->db->where('season',$quarter);
          $this->db->where('grade',$grade);
          $this->db->set('bsorder',$suborder);
          $this->db->update('basicskill');
        }
    }
    function updateCatOrder(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('suborder')){
          $suborder=$this->input->post('suborder');
          $subject=$this->input->post('subject');
          $this->db->where('academicyear',$max_year);
          $this->db->where('bscategory',$subject);
          $this->db->set('bcorder',$suborder);
          $this->db->update('bscategory');
        }
    }
    function fetchBasicSkills(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year'");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        echo $this->main_model->fetch_bsname($max_year,$max_quarter);
    }
    function putOnSubjectRow(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bsGrade')){
            $bsGrade=$this->input->post('bsGrade');
            $bsName=$this->input->post('bsName');
            $season=$this->input->post('season');
            $this->db->where('academicyear',$max_year);
            $this->db->where('grade',$bsGrade);
            $this->db->where('bsname',$bsName);
            $this->db->where('season',$season);
            $this->db->set('subjectrow','1');
            $query=$this->db->update('basicskill');
        }
    }
    function deleteputOnSubjectRow(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bsGrade')){
            $bsGrade=$this->input->post('bsGrade');
            $bsName=$this->input->post('bsName');
            $season=$this->input->post('season');
            $this->db->where('academicyear',$max_year);
            $this->db->where('grade',$bsGrade);
            $this->db->where('bsname',$bsName);
            $this->db->where('season',$season);
            $this->db->set('subjectrow','0');
            $query=$this->db->update('basicskill');
        }
    }
    function editbaskill(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bs')){
            $bs=$this->input->post('bs');
            $quarter=$this->input->post('quarter');
            $category=$this->input->post('category');
            echo $this->main_model->fethchBsToEdit($bs,$max_year,$quarter,$category);
        }
    }
    function updateBsNameCategory(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('category')){
            $category=trim($this->input->post('category'));
            $bsname1=trim(str_replace("'", "`",$this->input->post('bsname')));
            $bsname=trim(str_replace('"', '``',$bsname1));
            $grade=$this->input->post('grade');
            $season=$this->input->post('season');
            $this->db->where('bsname',$bsname);
            $this->db->where('academicyear',$max_year);
            $this->db->where('season',$season);
            $this->db->where('grade',$grade);
            $this->db->set('bscategory',$category);
            $queryUpdate=$this->db->update('basicskill');            
        }
    }
    function updateBs(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bsInfo')){
            $bsname1=trim(str_replace("'", "`",$this->input->post('bsInfo')));
            $bsInfo=trim(str_replace('"', '``',$bsname1));
            $bsnameInfo=$this->input->post('bsnameInfo');
            $bsquarterInfo=$this->input->post('bsquarterInfo');
            $grade=$this->input->post('grade');
            $data=array(
                'bsname'=>$bsInfo
            );
            foreach ($grade as $grades) {
                $this->db->where('bsname',$bsnameInfo);
                $this->db->where('academicyear',$max_year);
                $this->db->where('season',$bsquarterInfo);
                $this->db->where('grade',$grades);
                $queryUpdate=$this->db->update('basicskill',$data);
                if($queryUpdate){
                    echo $this->main_model->update_bs_names_values($bsnameInfo,$bsInfo,$max_year,$bsquarterInfo,$grades);
                }
            }
        }
    }
    function deleteBsCategory(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['delte_id']))
        {
            $id=$this->input->post('delte_id');
            $quarter=$this->input->post('quarter');
            $this->main_model->deleteBsCategory($id,$max_year,$quarter);
        }
    }
    function deleteSpecificBsCategory(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['grade']))
        {
            $grade=$this->input->post('grade');
            $quarter=$this->input->post('quarter');
            $category=$this->input->post('category');
            $this->db->where(array('bscategory'=>$category));
            $this->db->where(array('academicyear'=>$max_year));
            $this->db->where(array('season'=>$quarter));
            $this->db->where(array('bcgrade'=>$grade));
            $this->db->delete('bscategory');
        }
    }
    function deleteSpecificBsName(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['grade']))
        {
            $grade=$this->input->post('grade');
            $quarter=$this->input->post('quarter');
            $category=$this->input->post('category');
            $this->db->where(array('bsname'=>$category));
            $this->db->where(array('academicyear'=>$max_year));
            $this->db->where(array('season'=>$quarter));
            $this->db->where(array('grade'=>$grade));
            $this->db->delete('basicskill');
        }
    }
    function fetchConductType(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetchContype($max_year);
    }
    function insertConType(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('cotype')){
            $grade=$this->input->post('grade');
            $cotype=$this->input->post('cotype');
            $contypedecs=$this->input->post('contypedecs'); 
            foreach ($grade as $grades) {
                $data[]=array(
                    'coname'=>$cotype,
                    'congrade'=>$grades,
                    'condesc'=>$contypedecs,
                    'datecreated'=>date('M-d-Y'),
                    'academicyear'=>$max_year
                );
            }
            $query=$this->db->insert_batch('conductype',$data);
        }
    }
    function deleteConType(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['delte_id']))
        {
            $id=$this->input->post('delte_id');
            $delte_desc=$this->input->post('delte_desc');
            $this->main_model->deleteConType($id,$delte_desc,$max_year);
        }
    }
    function fetchBasicSkillsType(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetch_bstype($max_year);
    } 
    function insertBsType(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query2 = $this->db->query("select max(term) as quarter from quarter");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        if($this->input->post('bstype')){
            $grade=$this->input->post('grade');
            $bstype=$this->input->post('bstype');
            $bstypedecs=$this->input->post('bstypedecs');
            foreach ($grade as $grades) {
                $data[]=array(
                    'bstype'=>$bstype,
                    'bsdesc'=>$bstypedecs,
                    'btgrade'=>$grades,
                    'datecreated'=>date('M-d-Y'),
                    'academicyear'=>$max_year
                );
            }
            $query=$this->db->insert_batch('bstype',$data);
        }
    }
    function editBsType(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bstype')){
            $bstype=$this->input->post('bstype');
            echo $this->main_model->fethchBsTypeToEdit($bstype,$max_year);
        }
    }
    function editBsCategory(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('category')){
            $category=$this->input->post('category');
            $quarter=$this->input->post('quarter');
            echo $this->main_model->fethchBsCategoryToEdit($category,$quarter,$max_year);
        }
    }
    function updateBsCategory(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('categoryOld')){
            $categoryOld=$this->input->post('categoryOld');
            $categoryNew=$this->input->post('categoryNew');
            $quarterNew=$this->input->post('quarterNew');
            $quarterOld=$this->input->post('quarterOld');
            $grade=$this->input->post('grade');
            $data=array(
                'bscategory'=>$categoryNew,
                'season'=>$quarterNew
            );
            foreach ($grade as $grades) {
                $this->db->where('bscategory',$categoryOld);
                $this->db->where('bcgrade',$grades);
                $this->db->where('season',$quarterOld);
                $this->db->where('academicyear',$max_year);
                $query=$this->db->update('bscategory',$data);
                if($query){
                    $this->db->where('bscategory',$categoryOld);
                    $this->db->where('grade',$grades);
                    $this->db->where('season',$quarterOld);
                    $this->db->where('academicyear',$max_year);
                    $this->db->set('bscategory',$categoryNew);
                    $query=$this->db->update('basicskill',$data);
                }
            }
        }
    }
    function updateBsType(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bstypeId')){
            $bstypeId=$this->input->post('bstypeId');
            $bstypeName=$this->input->post('bstypeName');
            $bstypeDesc=$this->input->post('bstypeDesc');
            $oldbstypeName=$this->input->post('oldbstypeName');
            $oldbstypeDesc=$this->input->post('oldbstypeDesc');
            $data=array(
                'bstype'=>$bstypeName,
                'bsdesc'=>$bstypeDesc
            );
            $this->db->where('bstype',$oldbstypeName);
            $this->db->where('bsdesc',$oldbstypeDesc);
            $this->db->where('academicyear',$max_year);
            $query=$this->db->update('bstype',$data);
            if($query){
                echo $this->main_model->update_bs_names_values_key($bstypeName,$oldbstypeName,$max_year);
            }
        }
    }
    function deleteBsType(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['delte_id']))
        {
            $id=$this->input->post('delte_id');
            $this->main_model->deleteBsType($id,$max_year);
        }
    }
    function movingBs(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryCheck = $this->db->query("select * from quarter where Academic_year='$max_year' group by termgroup ");
        $data=array();
        $dataBS=array();
        $dataSubBS=array();
        if($queryCheck->num_rows()>0){
            foreach($queryCheck->result() as $maxQuarter){
                $termgroup=$maxQuarter->termgroup;
                $queryCurrent = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' and termgroup='$termgroup' ");
                $rowCurrent = $queryCurrent->row();
                $currentQuarter=$rowCurrent->quarter;
                   
                $query2 = $this->db->query("select max(season) as quarter from bscategory where academicyear='$max_year' ");
                $row2 = $query2->row();
                $max_quarter=$row2->quarter;
                if($max_quarter!=$currentQuarter){
                    $queryEva = $this->db->query("select * from bscategory where academicyear='$max_year' and season='$max_quarter' ");
                    if($queryEva->num_rows()>0){
                        foreach($queryEva->result() as $evaValue){
                            $data[]=array(
                                'bscategory'=>$evaValue->bscategory,
                                'season'=>$currentQuarter,
                                'bcgrade'=>$evaValue->bcgrade,
                                'byuser'=>$user,
                                'bcorder'=>$evaValue->bcorder,
                                'academicyear'=>$evaValue->academicyear,
                                'bcsubjectrow'=>$evaValue->bcsubjectrow,
                                'datecreated'=>date('M-d-Y')
                            );
                        }
                        if(!empty($data)){
                            $query=$this->db->insert_batch('bscategory',$data);
                        }
                    }
                    $queryEva = $this->db->query("select * from bs_sub_category where academicyear='$max_year' and season='$max_quarter' ");
                    if($queryEva->num_rows()>0){
                        foreach($queryEva->result() as $evaValue){
                            $dataSubBS[]=array(
                                'bscategory'=>$evaValue->bscategory,
                                'bssubcategory'=>$evaValue->bssubcategory,
                                'season'=>$currentQuarter,
                                'bcgrade'=>$evaValue->bcgrade,
                                'byuser'=>$user,
                                'bcorder'=>$evaValue->bcorder,
                                'academicyear'=>$evaValue->academicyear,
                                'bcsubjectrow'=>$evaValue->bcsubjectrow,
                                'datecreated'=>date('M-d-Y')
                            );
                        }
                        if(!empty($dataSubBS)){
                            $query=$this->db->insert_batch('bs_sub_category',$dataSubBS);
                        }
                    }
                    
                    $queryBS = $this->db->query("select * from basicskill where academicyear='$max_year' and season='$max_quarter' ");
                    if($queryBS->num_rows()>0){
                        foreach($queryBS->result() as $queryBSVale){
                            $grades=$queryBSVale->grade;
                            $bsname=$queryBSVale->bsname;
                            $queryCheck=$this->main_model->insert_bs_name_check($bsname,$grades,$max_year,$currentQuarter);
                            if($queryCheck){
                                $dataBS[]=array(
                                    'bsname'=>$queryBSVale->bsname,
                                    'bscategory'=>$queryBSVale->bscategory,
                                    'sub_category'=>$queryBSVale->sub_category,
                                    'season'=>$currentQuarter,
                                    'grade'=>$queryBSVale->grade,
                                    'byuser'=>$user,
                                    'academicyear'=>$queryBSVale->academicyear,
                                    'subjectrow'=>$queryBSVale->subjectrow,
                                    'datecreated'=>date('M-d-Y')
                                );
                            }
                        }
                        if(!empty($dataBS)){
                            $queryBSInsert=$this->db->insert_batch('basicskill',$dataBS);
                        }
                    }
                }
            }
        }
    }
    function fetch_bs_categoreis(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('gradeName')){
            $gradeName=$this->input->post('gradeName');
            $quarter=$this->input->post('quarter');
            foreach ($gradeName as $gradeNames) {
                echo $this->main_model->fetch_bs_categoreis($gradeNames,$quarter,$max_year);
            }
        }
    }
    function save_sub_category(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if($this->input->post('gradeName')){
            $gradeName=$this->input->post('gradeName');
            $quarter=$this->input->post('quarter');
            $category=$this->input->post('category');
            $subcategory=$this->input->post('subCategory');
            foreach ($gradeName as $gradeNames) {
                $check= $this->main_model->check_bs_sub_categoreis($gradeNames,$quarter,$max_year,$subcategory,$category);
                if($check){
                    $data[]=array(
                        'bscategory'=>$category,
                        'bssubcategory'=>$subcategory,
                        'season'=>$quarter,
                        'bcgrade'=>$gradeNames,
                        'academicyear'=>$max_year,
                        'datecreated'=>date('M-d-Y'),
                        'byuser'=>$user,
                        'bcorder'=>'-',
                        'bcsubjectrow'=>'-'
                    );
                }
            }
            if(!empty($data)){
                $queryInsert=$this->db->insert_batch('bs_sub_category',$data);
                if($queryInsert){
                    echo '1';
                }else{
                    echo '0';
                }
            }else{
                echo '3';
            }
        }
    }
    function load_sub_category_data(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year; 
        $queryCurrent = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' ");
        $rowCurrent = $queryCurrent->row();
        $max_quarter=$rowCurrent->quarter;
        echo $this->main_model->load_sub_category_data($max_year,$max_quarter);
    }
    function remove_bs_sub_category_type(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('userid')){
            $userid=$this->input->post('userid');
            $this->db->where('bsid',$userid);
            $query=$this->db->delete('bs_sub_category');
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        } 
    }
    function enable_bs_sub_categories(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->enable_bs_sub_categories($max_year);
    }
    function enable_sub_category(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            $this->db->where('academicyear',$max_year);
            $query = $this->db->get('bs_sub_category_enable');
            if($query->num_rows()>0){
                $this->db->where('academicyear',$max_year);
                $this->db->set('enable_status','1');
                $query=$this->db->update('bs_sub_category_enable'); 
                if($query){
                    echo '1';
                }else{
                    echo '0';
                }
            }else{
                $data=array(
                    'enable_status'=>'1',
                    'academicyear'=>$max_year,
                    'lockby'=>$user,
                    'datelocked'=>date('M-d-Y')
                );
                $query=$this->db->insert('bs_sub_category_enable',$data); 
                if($query){
                    echo '3';
                }else{
                    echo '4';
                }
            }
        }
    }
    function disable_sub_category(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            $this->db->where('academicyear',$max_year);
            $this->db->set('enable_status','0');
            $query=$this->db->update('bs_sub_category_enable');
            if($query){
                echo '1';
            } else{
                echo '0';
            }
        }
    }
    function fetch_bs_categoreis_forGrade(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('category')){
            $category=$this->input->post('category');
            $quarter=$this->input->post('quarter');
            echo $this->main_model->fetch_bs_categoreis_forGrade($category,$quarter,$max_year);
        }
    }
    function fetch_bs_sub_categoreis_forGrade(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('category')){
            $category=$this->input->post('category');
            $quarter=$this->input->post('quarter');
            echo $this->main_model->fetch_bs_sub_categoreis_forGrade($category,$quarter,$max_year);
        }
    }
    function movingBasicSkill(){
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

        /*$maxYearQuery = $this->db->query("select max(year_name) as year from academicyear");
        $maxyearRow = $maxYearQuery->row();
        $maxYear=$maxyearRow->year;
        $minYear=$maxYear - 1;*/
        $data5=array();
        $query2 = $this->db->query("select max(term) as quarter,min(term) as minQuarter from quarter where Academic_year='$maxYear' ");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $season=$row2->minQuarter;
        $queryMinYearBranch = $this->db->query("select * from conductype where academicyear='$minYear' group by congrade,coname  ");
        if($queryMinYearBranch->num_rows() > 0){            
            foreach($queryMinYearBranch->result() as $branchName){
                $queryMaxYearBranch = $this->db->query("select * from conductype where academicyear='$maxYear' ");
                if($queryMaxYearBranch->num_rows() < 1){
                    $data1[]=array(
                        'congrade'=>$branchName->congrade,
                        'coname'=>$branchName->coname,
                        'condesc'=>$branchName->condesc,
                        'academicyear'=>$maxYear,
                        'datecreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($data1)){
                $queryBranch=$this->db->insert_batch('conductype',$data1);
                if($queryBranch){
                    echo 'Conduct Type Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
        $queryMinYearBranch = $this->db->query("select * from bstype where academicyear='$minYear' group by btgrade, bstype");
        if($queryMinYearBranch->num_rows() > 0){            
            foreach($queryMinYearBranch->result() as $branchName){
                $queryMaxYearBranch = $this->db->query("select * from bstype where academicyear='$maxYear'");
                if($queryMaxYearBranch->num_rows() < 1){
                    $data2[]=array(
                        'btgrade'=>$branchName->btgrade,
                        'bstype'=>$branchName->bstype,
                        'bsdesc'=>$branchName->bsdesc,
                        'academicyear'=>$maxYear,
                        'datecreated'=>date('M-d-Y')
                    );
                }
            }
            if(!empty($data2)){
                $queryBranch=$this->db->insert_batch('bstype',$data2);
                if($queryBranch){
                    echo 'Basik Skill Type Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }

        $queryMinYearBranch = $this->db->query("select * from bscategory where academicyear='$minYear' and season='$season' group by bscategory,bcgrade ");
        if($queryMinYearBranch->num_rows() > 0){            
            foreach($queryMinYearBranch->result() as $branchName){
                $queryMaxYearBranch = $this->db->query("select * from bscategory where academicyear='$maxYear'");
                if($queryMaxYearBranch->num_rows() < 1){
                    $data3[]=array(
                        'bscategory'=>$branchName->bscategory,
                        'bcgrade'=>$branchName->bcgrade,
                        'season'=>$max_quarter,
                        'academicyear'=>$maxYear,
                        'datecreated'=>date('M-d-Y'),
                        'byuser'=>$user,
                        'bcorder'=>$branchName->bcorder,
                        'bcsubjectrow'=>$branchName->bcsubjectrow
                    );
                }
            }
            if(!empty($data3)){
                $queryBranch=$this->db->insert_batch('bscategory',$data3);
                if($queryBranch){
                    echo 'Basic Category Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
        $queryMinYearBranch = $this->db->query("select * from basicskill where academicyear='$minYear' and season='$season' group by bsname,grade ");
        if($queryMinYearBranch->num_rows() > 0){            
            foreach($queryMinYearBranch->result() as $branchName){
                $queryMaxYearBranch = $this->db->query("select * from basicskill where academicyear='$maxYear'");
                if($queryMaxYearBranch->num_rows() < 1){
                    $data4[]=array(
                        'bsname'=>$branchName->bsname,
                        'grade'=>$branchName->grade,
                        'season'=>$max_quarter,
                        'bscategory'=>$branchName->bscategory,
                        'sub_category'=>$branchName->sub_category,
                        'subjectrow'=>$branchName->subjectrow,
                        'bsorder'=>$branchName->bsorder,
                        'academicyear'=>$maxYear,
                        'datecreated'=>date('M-d-Y'),
                        'byuser'=>$user
                    );
                }
            }
            if(!empty($data4)){
                $queryBranch=$this->db->insert_batch('basicskill',$data4);
                if($queryBranch){
                    echo 'Basic Skill Name Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
        $queryMinYearBranch = $this->db->query("select * from bs_sub_category where academicyear='$minYear' group by bscategory,bcgrade ");
        if($queryMinYearBranch->num_rows() > 0){            
            foreach($queryMinYearBranch->result() as $branchName){
                $queryMaxYearBranch = $this->db->query("select * from bs_sub_category where academicyear='$maxYear'");
                if($queryMaxYearBranch->num_rows() < 1){
                    $data5[]=array(
                        'bscategory'=>$branchName->bscategory,
                        'bssubcategory'=>$branchName->bssubcategory,
                        'season'=>$max_quarter,
                        'bcgrade'=>$branchName->bcgrade,
                        'bcsubjectrow'=>$branchName->bcsubjectrow,
                        'bcorder'=>$branchName->bcorder,
                        'academicyear'=>$maxYear,
                        'datecreated'=>date('M-d-Y'),
                        'byuser'=>$user
                    );
                }
            }
            if(!empty($data5)){
                $queryBranch=$this->db->insert_batch('bs_sub_category',$data5);
                if($queryBranch){
                    echo 'Basic Skill Sub-category Moved<span> <i class="fas fa-check-circle"> </i> </span>';
                }
            }
        }
    }
}