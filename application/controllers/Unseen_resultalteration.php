<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unseen_resultalteration extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('chat_model');
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
            redirect('login/');
        }
    }
	public function index()
	{
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($usertype==trim('Admin') || $usertype==trim('superAdmin') ){
            if(isset($_POST['view'])){
                $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='reportcard' order by id ASC ");
                if($usergroupPermission->num_rows()>0){
                    $showRequest=$this->chat_model->fetch_unseen_resultAlteration($max_year);
                }else{
                    $showRequest='';
                }
                $groupNotification=$showRequest;
                $result['notification']=$groupNotification;
                echo json_encode($result);
            }
        }
	}    
}