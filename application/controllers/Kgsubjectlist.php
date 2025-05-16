<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kgsubjectlist extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupKGSUbjectL=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Subject' and allowed='KgSubjectList' order by id ASC "); 
        if($this->session->userdata('username') == '' || $usergroupKGSUbjectL->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='kgsubjectlist')
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
        $data['max_year']=$max_year;
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['bscategory']=$this->main_model->fetch_bscategory($max_year);
        $data['scategory']=$this->main_model->fetch_kg_s_category($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['fetch_term']=$this->main_model->fetch_term($max_year);
        $data['fetch_season']=$this->main_model->fetch_kg_subject_season($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function fetch_grade_list_Names_grands(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade_list'))
        {
            $header_list=$this->input->post('grade_list');
            echo $this->main_model->fetch_grade_list_Names_grands($header_list,$max_year);
        }
    }
    function fetch_category_list_Names_grands(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade_list'))
        {
            $header_list=$this->input->post('grade_list');
            echo $this->main_model->fetch_category_list_Names_grands($header_list,$max_year);
        }
    }
    function fetch_grade_list_Names(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('grade_list'))
        {
            $header_list=$this->input->post('grade_list');
            echo $this->main_model->fetch_grade_list_Names($header_list,$max_year);
        }
    }
    function save_new_season_name(){
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('header_name')){
            $header_name=trim($this->input->post('header_name'));
            $grade=$this->input->post('grade');
            foreach ($grade as $grades) {
                $data=array(
                    'sub_name'=>$header_name,
                    'header_grade'=>$grades,
                    'academicyear'=>$max_year,
                    'createdby'=>$user,
                    'date_created'=>date('M-d-Y')
                );
                $query=$this->main_model->save_new_season_name($data,$header_name,$max_year,$grades);
            }
            if($query){
                echo '<span class="text-success">Saved Successfully</span>';
            }else{
                echo '<span class="text-danger">Name Exists.</span>';
            }
        }
    }
    function save_subject_list(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['id'])){
            $bsname=$this->input->post('bsname');
            $grade=$this->input->post('id');
            $linkcategory=$this->input->post('linkcategory');
            $category_term_sub=$this->input->post('category_term_sub');
            $weekName=$this->input->post('weekName');
            foreach ($grade as $grades) {
                $data=array(
                    'sname'=>$bsname,
                    'sgrade'=>$grades,
                    'scategory'=>$linkcategory,
                    'sterm'=>$category_term_sub,
                    'week'=>$weekName,
                    'academicyear'=>$max_year,
                    'datecreated'=>date('M-d-Y'),
                    'byuser'=>$user
                );
                $query=$this->main_model->insert_s_name($category_term_sub,$bsname,$grades,$max_year,$weekName,$data);
            }
        }
    }
    function delete_s_list_name(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['delte_id']))
        {
            $id=$this->input->post('delte_id');
            $grade=$this->input->post('delte_grade');
            $this->main_model->delete_kg_subject_list_name($id,$max_year,$grade);
        }
    }
    function insertBsCategory(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bsnamecate')){
            $grade=$this->input->post('grade');
            $bsnamecate=$this->input->post('bsnamecate');
            $category_term=$this->input->post('category_term');
            foreach ($grade as $grades) {
                $queryCheck=$this->db->query("select * from kg_subject_list_category where academicyear='$max_year' and cate_grade='$grades' and cate_term='$category_term' and category_name='$bsnamecate' ");
                if($queryCheck->num_rows()<1){
                    $data[]=array(
                        'category_name'=>$bsnamecate,
                        'cate_grade'=>$grades,
                        'cate_term'=>$category_term,
                        'academicyear'=>$max_year,
                        'datecreated'=>date('M-d-Y'),
                        'byuser '=>$user
                    );
                }
            }
            $query=$this->db->insert_batch('kg_subject_list_category',$data);
        }
    }
    function fetchBsCategory(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query2 = $this->db->query("select max(term) as quarter from quarter");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        echo $this->main_model->fetch_kg_subject_list_Category($max_year,$max_quarter);
    }
    function load_subject_main_category(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query2 = $this->db->query("select max(term) as quarter from quarter");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        echo $this->main_model->load_subject_main_category($max_year,$max_quarter);
    }
    function delete_subject_Header(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['delte_id']))
        {
            $id=$this->input->post('delte_id');
            $this->main_model->delete_subject_Header($id,$max_year);
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
          $this->db->where('category_name',$subject);
          $this->db->set('suborder',$suborder);
          $this->db->update('kg_subject_list_category');
        }
    }
    function fetch_kg_subject_list_name(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $this->db->where('academicyear',$max_year);
        $this->db->where('enable_status','1');
        $query=$this->db->get('kg_chibt_week_category');
        if($query->num_rows()>0){
            echo $this->main_model->fetch_kg_subject_list_name_week($max_year);
        }else{
            echo $this->main_model->fetch_kg_subject_list_name($max_year);
        }
        
    }
    function putOnSubjectRow(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bsGrade')){
            $bsGrade=$this->input->post('bsGrade');
            $bsName=$this->input->post('bsName');
            $bsValue=$this->input->post('bsValue');
            $this->db->where('academicyear',$max_year);
            $this->db->where('grade',$bsGrade);
            $this->db->where('bsname',$bsName);
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
            $bsValue=$this->input->post('bsValue');
            $this->db->where('academicyear',$max_year);
            $this->db->where('grade',$bsGrade);
            $this->db->where('bsname',$bsName);
            $this->db->set('subjectrow','0');
            $query=$this->db->update('basicskill');
        }
    }
    function editlist_name(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bs')){
            $bs=$this->input->post('bs');
            echo $this->main_model->editlist_name($bs,$max_year);
        }
    }
    function updateBs(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $this->db->where('academicyear',$max_year);
        $this->db->where('enable_status','1');
        $query=$this->db->get('kg_chibt_week_category');
        if($query->num_rows()>0){
            if($this->input->post('bsInfo')){
                $bsInfo=$this->input->post('bsInfo');
                $bsnameInfo=$this->input->post('bsnameInfo');
                $data=array(
                    'sname'=>$bsInfo
                );
                $this->db->where('week!=','');
                $this->db->where('sname',$bsnameInfo);
                $this->db->update('kg_subject_list_name',$data);
            }
        }else{
            if($this->input->post('bsInfo')){
                $bsInfo=$this->input->post('bsInfo');
                $bsnameInfo=$this->input->post('bsnameInfo');
                $data=array(
                    'sname'=>$bsInfo
                );
                $this->db->where('sname',$bsnameInfo);
                $this->db->where('week=','');
                $this->db->update('kg_subject_list_name',$data);
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
            $this->main_model->delete_subject_Category_list($id,$max_year);
        }
    }
    function subject_value_list(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query2 = $this->db->query("select max(term) as quarter from quarter");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        echo $this->main_model->subject_value_list($max_year);
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
            $bstype=trim($this->input->post('bstype'));
            $bstypedecs=$this->input->post('bstypedecs');
            $subject_percentage=$this->input->post('subject_percentage');
            foreach ($grade as $grades) {
                $data[]=array(
                    'value_name'=>$bstype,
                    'value_percent'=>$subject_percentage,
                    'value_desc'=>$bstypedecs,
                    'value_grade'=>$grades,
                    'datecreated'=>date('M-d-Y'),
                    'academicyear'=>$max_year,
                    'createdby'=>$user
                );
            }
            $query=$this->db->insert_batch('kg_subject_value',$data);
        }
    }
    function editBsType(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bstype')){
            $bstype=$this->input->post('bstype');
            echo $this->main_model->fethchtoedit_subject_list_value($bstype,$max_year);
        }
    }
    function updateBsType(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bstypeId')){
            $bstypeId=$this->input->post('bstypeId');
            $bstypeName=trim($this->input->post('bstypeName'));
            $bstypeDesc=$this->input->post('bstypeDesc');
            $bstypePercent=$this->input->post('bstypePercent');
            $data=array(
                'value_name'=>$bstypeName,
                'value_desc'=>$bstypeDesc,
                'value_percent'=>$bstypePercent
            );
            $this->db->where('value_name',$bstypeId);
            $query=$this->db->update('kg_subject_value',$data);
            if($query){
                $this->db->where('academicyear',$max_year);
                $this->db->where('value',$bstypeId);
                $this->db->set('value',$bstypeName);
                $query=$this->db->update('kg_subject_student_result');
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
            $this->main_model->deletesubject_list_value($id,$max_year);
        }
    }
    function enable_week_categories(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->enable_week_categories($max_year);
    }
    function enable_week_category(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            $this->db->where('academicyear',$max_year);
            $query = $this->db->get('kg_chibt_week_category');
            if($query->num_rows()>0){
                $this->db->where('academicyear',$max_year);
                $this->db->set('enable_status','1');
                $query=$this->db->update('kg_chibt_week_category'); 
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
                $query=$this->db->insert('kg_chibt_week_category',$data); 
                if($query){
                    echo '3';
                }else{
                    echo '4';
                }
            }
        }
    }
    function disable_week_category(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            $this->db->where('academicyear',$max_year);
            $this->db->set('enable_status','0');
            $query=$this->db->update('kg_chibt_week_category');
            if($query){
                echo '1';
            } else{
                echo '0';
            }
        }
    }
    function filterGradesecfromBranch(){
        if($this->input->post('academicyear')){
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->filterGradesecfromBranch($academicyear); 
        }
    }
    function filterGradefromBranch(){
        if($this->input->post('branchit')){
            $branch=$this->input->post('branchit');
            $academicyear=$this->input->post('academicyear');
            echo $this->main_model->fetch_grade_from_branch($branch,$academicyear); 
        }
    }
    function fetch_sheet(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('bsnamecate')){
            $grade=$this->input->post('grade');
            $chibt=$this->input->post('bsnamecate');
            $branch=$this->input->post('branch');
            $year=$this->input->post('year');
            echo $this->main_model->fetch_kg_mark_sheet($grade,$chibt,$branch,$year);
        }
    }
}