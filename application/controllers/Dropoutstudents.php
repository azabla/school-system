<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dropoutstudents extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuView=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentDrop' order by id ASC ");
        if($this->session->userdata('username') == '' || $uperStuView->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='dropoutstudent')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data['maxYear']=$max_year;
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year();
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('home-page/'.$page,$data);
	}
    function fetch_inactivestudents(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $postData = $this->input->post();
        $data= $this->main_model->fetch_inactivestudents($max_year,$postData);
        echo json_encode($data);
    } 
    function searchStudentsToTransportService(){
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->searchStudentsToForUnarchive($searchItem,$max_year);
            }else{
                echo $this->main_model->searchStudentsToTransportServiceNotAccess($searchItem,$branch,$max_year);
            }
        }
    }
    function saveNewTransportPlace(){
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        if($this->input->post('stuIdArray')){
            $stuIdArray=$this->input->post('stuIdArray');
            $takeAction=trim($this->input->post('takeAction'));
            if($takeAction=='UndropGroup'){
                foreach($stuIdArray as $stuIdArrays){
                    $this->db->where('username',$stuIdArrays);
                    $this->db->set('status','Active');
                    $queryUpdate=$this->db->update('users');
                }
                if($queryUpdate){
                    echo '1';
                }else{
                    echo '0'; 
                }
            } 
        }
    }
    function fecth_student_toregister(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if(isset($_POST['register_id'])){
            $id=$this->input->post('register_id');
            $yearDropped=$this->input->post('yearDrooped');
            $username=$this->input->post('username');
            echo $this->main_model->fecth_student_toregister($id,$yearDropped,$max_year,$username);
        }
    }
    public function register_student()
    {
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $data=array();
        if(isset($_POST['register_id'])){
            $id=$this->input->post('register_id');
            $yearDropped=$this->input->post('yearDrooped');
            $grade=$this->input->post('grade');
            $branch=$this->input->post('branch');
            $registerOnYear=$this->input->post('registerOnYear');
            if($yearDropped==$registerOnYear){
                echo $this->main_model->active_student($id);
            }else{
                $queryFetch=$this->db->query("select * from users where id='$id' and academicyear='$yearDropped' ");
                if($queryFetch->num_rows()>0){
                    foreach($queryFetch->result() as $row){
                        $userName=$row->username;
                        $data=array(
                            'username'=>$row->username,
                            'usertype'=>$row->usertype,
                            'fname'=>$row->fname,
                            'mname'=>$row->mname,
                            'lname' =>$row->lname,
                            'last_oflast_name'=>$row->last_oflast_name,
                            'previous_school' =>$row->previous_school,
                            'mobile' =>$row->mobile,
                            'father_mobile'=> $row->father_mobile,
                            'email' =>$row->email,
                            'profile'=>$row->profile ,
                            'grade'=>$grade ,
                            'section' =>'',
                            'gradesec'=>'',
                            'dob'=>$row->dob ,
                            'age'=>$row->age ,
                            'gender' =>$row->gender,
                            'password'=>$row->password ,
                            'password2' =>$row->password2,
                            'mother_name' =>$row->mother_name,
                            'father_name' =>$row->father_name,
                            'father_dob' =>$row->father_dob,
                            'father_age' =>$row->father_age,
                            'work' =>$row->work,
                            'father_workplace'=>$row->father_workplace,
                            'nationality'=>$row->nationality ,
                            'marital_status'=>$row->marital_status ,
                            'city' =>$row->city,
                            'sub_city'=>$row->sub_city,
                            'woreda'=>$row->woreda ,
                            'kebele'=>$row->kebele,
                            'home_place'=>$row->home_place,
                            'isapproved'=>$row->isapproved ,
                            'dateregister'=>$row->dateregister,
                            'branch'=>$branch,
                            'transportservice'=>$row->transportservice,
                            'asp'=>$row->asp,
                            'academicyear'=>$registerOnYear,
                            'biography'=>$row->biography,
                            'dream' =>$row->dream,
                            'status' =>'Active',
                            'status2' =>$row->status2,
                            'special_needs' =>$row->special_needs,
                            'datemployeed'=>$row->datemployeed ,
                            'gsallary' =>$row->gsallary,
                            'allowance'=>$row->allowance ,
                            'quality_allowance' =>$row->quality_allowance,
                            'position_allowance'=>$row->position_allowance,
                            'home_allowance' =>$row->home_allowance,
                            'gross_sallary' =>$row->gross_sallary,
                            'taxable_income' =>$row->taxable_income,
                            'income_tax' =>$row->income_tax,
                            'pension_7' =>$row->pension_7,
                            'pension_11' =>$row->pension_11,
                            'other' =>$row->other,
                            'netsallary' =>$row->netsallary,
                            'unique_id' =>$row->unique_id,
                            'leave_days' =>$row->leave_days,
                            'mysign'=>$row->mysign ,
                            'finalapproval'=>$row->finalapproval
                        );
                        $queryInsert=$this->db->insert('users',$data);
                        if($queryInsert){
                            echo '1';
                        }else{
                            echo '0';
                        }
                    }
                }
            }            
        }
    }
}