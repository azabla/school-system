<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myitemrequest extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='inventoryManagement' and allowed='requestItem' order by id ASC ");  
        if($this->session->userdata('username') == '' || $uperStuDE->num_rows() < 1 || $userLevel!='2'){
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
	public function index($page='requestitem')
	{
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
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
        $data['item_list']=$this->main_model->fetch_item_list();
        $this->load->view('teacher/'.$page,$data);
	}
    function validate_item_quantity(){
        if($this->input->post('item_option')){
            $item_option=$this->input->post('item_option');
            $itemQuantity=$this->input->post('itemQuantity');
            echo $this->main_model->validate_item_quantity($item_option,$itemQuantity);
        }
    }
    public function submit_request()
    {   
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('etc/GMT-10');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $date = gmdate("Y-m-d h:i A", $dt->format('U'));
        $user=$this->session->userdata('username');
        $dataArray=array();
        if($this->input->post('item_option')){
            $item_option=$this->input->post('item_option');
            $itemQuantity=$this->input->post('itemQuantity');
            $queryCheck=$this->main_model->check_requested_item($item_option,$itemQuantity,$user);
            if($queryCheck){
                $dataArray=array(
                    'requested_item_id'=>$item_option,
                    'requested_quantity'=>$itemQuantity,
                    'requested_date'=>$date,
                    'requested_by'=>$user
                );
                $query=$this->db->insert('stock_requested',$dataArray);
                if($query){
                    echo 'Request sent successfully.';
                }else{
                    echo 'Ooops Please try again.';
                }
            }else{
                echo '<span class="text-danger">Ooops ,You have already request this item.</span>';
            }
        }
    }
    function fetch_myrequested_item(){
        $user=$this->session->userdata('username');
        echo $this->main_model->fetch_myrequested_item($user);
    }
    function delete_request_item(){
        if($this->input->post('requestid')){
            $requestid=$this->input->post('requestid');
            $this->db->where('id',$requestid);
            $this->db->delete('stock_requested');
        }
    }
}
