<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basicskillteacher extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='addRemoveBS' order by id ASC "); 

        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='2'){
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
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php'))
        {
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['id'])){
            $bsname1=trim(str_replace("'", "`",$this->input->post('bsname')));
            $bsname=trim(str_replace('"', '``',$bsname1));
            $grade=$this->input->post('id');
            $linkcategory=$this->input->post('linkcategory');
            $name_quarter=$this->input->post('name_quarter');
            foreach ($grade as $grades) {
                $data=array(
                    'bsname'=>$bsname,
                    'grade'=>$grades,
                    'bscategory'=>$linkcategory,
                    'season'=>$name_quarter,
                    'academicyear'=>$max_year,
                    'datecreated'=>date('M-d-Y'),
                    'byuser'=>$user
                );
                $query=$this->main_model->insert_bs_name($bsname,$grades,$max_year,$data,$name_quarter);
            }
        }
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
        $this->load->view('teacher/'.$page,$data);
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
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_year='$max_year' ");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $queryMin = $this->db->query("select min(term) as quarter from quarter where Academic_year='$max_year' ");
        $rowMin = $queryMin->row();
        $min_quarter=$rowMin->quarter;
        $queryChk = $this->db->select('*')
        ->where('staff', $user)
        ->where('academicyear',$max_year)
        ->get('directorplacement');
        if($queryChk->num_rows()>0){
            echo $this->main_model->fetchBsCategory_director($max_year,$max_quarter,$min_quarter,$user);
        }else{
            echo $this->main_model->fetchBsCategory_teacher($max_year,$max_quarter,$min_quarter,$user);
        }
    }
    function updateCatLeftRow(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
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
        $query_branch = $this->db->query("select * from users where username='$user'");
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
        $queryChk = $this->db->select('*')
        ->where('staff', $user)
        ->where('academicyear',$max_year)
        ->get('directorplacement');
        if($queryChk->num_rows()>0){
            echo $this->main_model->fetch_bsname_director($max_year,$max_quarter,$user);
        }else{
            echo $this->main_model->fetch_bsname_teacher($max_year,$max_quarter,$user);
        }
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
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bs')){
            $bs=$this->input->post('bs');
            $quarter=$this->input->post('quarter');
            $category=$this->input->post('category');
            $queryChk = $this->db->select('*')
            ->where('staff', $user)
            ->where('academicyear',$max_year)
            ->get('directorplacement');
            if($queryChk->num_rows()>0){
                echo $this->main_model->fethchBsToEditDirector($bs,$max_year,$quarter,$category,$user);
            }else{
                 echo $this->main_model->fethchBsToEditTeacher($bs,$max_year,$quarter,$category,$user);
            }
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

        $query2 = $this->db->query("select max(term) as quarter from quarter");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
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
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('category')){
            $category=$this->input->post('category');
            $quarter=$this->input->post('quarter');
            echo $this->main_model->fethchBsCategoryToEditDirector($category,$quarter,$max_year,$user);
        }
    }
    function editBsCategoryTeacher(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('category')){
            $category=$this->input->post('category');
            $quarter=$this->input->post('quarter');
            echo $this->main_model->fethchBsCategoryToEditTeacher($category,$quarter,$max_year,$user);
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
                    $query=$this->db->insert_batch('bscategory',$data);
                    $queryBS = $this->db->query("select * from basicskill where academicyear='$max_year' and season='$max_quarter' ");
                    foreach($queryBS->result() as $queryBSVale){
                        $grades=$queryBSVale->grade;
                        $bsname=$queryBSVale->bsname;
                        $queryCheck=$this->main_model->insert_bs_name_check($bsname,$grades,$max_year,$currentQuarter);
                        if($queryCheck){
                            $dataBS[]=array(
                                'bsname'=>$queryBSVale->bsname,
                                'bscategory'=>$queryBSVale->bscategory,
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