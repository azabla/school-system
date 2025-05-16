<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffdailyactivity extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $userpStaffDe=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffActivity' order by id ASC "); 
        if($this->session->userdata('username') == '' || $userpStaffDe->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='staffdailyactivity')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $query_branch =$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['schools']=$this->main_model->fetch_school();
        $data['usertype']=$this->main_model->fetch_usertype_dailytasks();
        $data['taskslist']=$this->main_model->fetch_each_dailytasks();
        $this->load->view('home-page/'.$page,$data);
	} 
    function fetchTasks(){
        $user=$this->session->userdata('username');
        $query_branch =$this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $usertype=$row_branch->usertype;
        $myDivision=$row_branch->status2;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $accessbranch = sessionUseraccessbranch();
        echo $this->main_model->fetchstaffsTasks();
    }
    function saveNewTasks(){
        date_default_timezone_set('Africa/Addis_Ababa');
        $datePosted=date('Y-m-d h:i:s a', time());
        $user=$this->session->userdata('username');
        if($this->input->post('taskName')){
            $taskName=$this->input->post('taskName');
            $whoseTask=$this->input->post('whoseTask');
            $dueDate=$this->input->post('dueDate');
            $taskType=$this->input->post('taskType');
            $queryCheck=$this->db->query("select * from stafftasks where task_name='$taskName' and task_for='$whoseTask' ");
            if($queryCheck->num_rows()>0){
                echo '<span class="text-danger">Ooops,Task exists</span>';
            }else{
                $data=array(
                  'task_name'=>$taskName,
                  'task_for'=>$whoseTask,
                  'task_type'=>$taskType,
                  'created_by'=>$user,
                  'due_date'=>$dueDate,
                  'date_created'=>date('M,d,Y h:i:s A')
                );
                $query=$this->db->insert('stafftasks',$data);
                if($query){
                    echo '<span class="text-success">Saved successfully</span>';
                }else{
                    echo '<span class="text-danger">Student ID already exists</span>';
                }
            }
        }
    }
    function saveEditedTasks(){
        $user=$this->session->userdata('username');
        if($this->input->post('editedtaskName')){
            $taskName=$this->input->post('editedtaskName');
            $whoseTask=$this->input->post('editedwhoseTask');
            $editTaskType=$this->input->post('editTaskType');
            $dueDate=$this->input->post('editeddueDate');
            $hiddenUpdatedTask=$this->input->post('hiddenUpdatedTask');
            $data=array(
              'task_name'=>$taskName,
              'task_for'=>$whoseTask,
              'task_type'=>$editTaskType,
              'created_by'=>$user,
              'due_date'=>$dueDate
            );
            $this->db->where('id',$hiddenUpdatedTask);
            $query=$this->db->update('stafftasks',$data);
            if($query){
                echo '<span class="text-success">Updated successfully</span>';
            }else{
                echo '<span class="text-danger">Please try again</span>';
            }
        } 
    }
    function deleteTaskName(){
        if($this->input->post('taskName')){
            $taskId=$this->input->post('taskName');
            $this->db->where('id',$taskId);
            $query=$this->db->delete('stafftasks');
            if($query){
                echo '<span class="text-success">Deleted successfully</span>';
            }else{
                echo '<span class="text-danger">Please try later</span>';
            }
        }  
    }
    function editThisTaskName(){
        if($this->input->post('taskName')){
            $taskId=$this->input->post('taskName');
            echo $this->main_model->edit_this_task($taskId);
        }
    }
    function searchTasks(){
        if($this->input->post('searchItem')){
            $searchItem=$this->input->post('searchItem');
            echo $this->main_model->searchTasks($searchItem);
        }
    }
    function fetch_usersTask_progress(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $usertype=$this->session->userdata('usertype');
        $queryAccessBranch=$this->db->query("select accessbranch from usegroup where uname='$usertype' ");
        $rowaccessbranch = $queryAccessBranch->row_array();
        $accessbranch=$rowaccessbranch['accessbranch'];
        $user=$this->session->userdata('username');
        $query_branch =$this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        if($this->input->post('taskName')){
            $taskName=$this->input->post('taskName');
            $id=$this->input->post('id');
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                echo $this->main_model->fetch_usersTask_progress($max_year,$taskName);
            }else{
                echo $this->main_model->fetch_usersTask_progress_branch($max_year,$taskName,$branch);
            }
        } 
    }
    function fetch_my_userstasks_report(){
        $user=$this->session->userdata('username');
        $userType=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $accessbranch = sessionUseraccessbranch();
        $query_branch =$this->db->query("select branch from users where username='$user'");
        $row_branch = $query_branch->row();
        $mybranch=$row_branch->branch;
        $queryReportTo=$this->db->query("select user_hierarchy,uname from usegroup where uname='$userType' ");
        if($queryReportTo->num_rows()>0){
          $rowID=$queryReportTo->row();
          $user_hierarchys=$rowID->user_hierarchy;
        }else{
           $user_hierarchys='10000'; 
        }
        if($_SESSION['usertype']===trim('superAdmin')){
            $this->db->where('report_to',$user_hierarchys);
            $this->db->set('seen_status','1');
            $this->db->update('stafftasks_report');
            echo $this->main_model->fetch_all_userstasks_report($max_year,$userType,$user);
        }else if($accessbranch === '1'){
            $this->db->where('report_to',$user_hierarchys);
            $this->db->set('seen_status','1');
            $this->db->update('stafftasks_report');
            echo $this->main_model->fetch_my_userstasks_report($max_year,$user_hierarchys,$user);
        }else{
            echo $this->main_model->fetch_mybranch_userstasks_report($max_year,$user_hierarchys,$user,$mybranch);
        }
    }
    function deleteSingleidentReport(){
        if($this->input->post('reportID')){
            $reportID=$this->input->post('reportID');    
            $this->db->where('id',$reportID);
            $query= $this->db->delete('stafftasks_report');
        }
    }
}