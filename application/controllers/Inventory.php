<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='inventoryManagement' and allowed='stockItem' order by id ASC ");  
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
	public function index($page='inventory')
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
       
        if(isset($_POST['drop_id'])){
            $id=$this->input->post('drop_id');
            $this->main_model->inactive_student($id);
        }
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $accessbranch = sessionUseraccessbranch();
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['staffs']=$this->main_model->fetchStaffsForPlacement();
        }else{
            $data['staffs']=$this->main_model->fetchMyStaffsForPlacement($branch);
        }
        $this->load->view('home-page/'.$page,$data);
	}
    function fetch_form_toadd_new_item(){
        echo $this->main_model->fetch_form_toadd_new_item();
    }
    function insert_item(){
        $dataArray=array();
        $user=$this->session->userdata('username');
        if($this->input->post('item_category')){
            $item_category=$this->input->post('item_category');
            $item_id=$this->input->post('item_id');
            $item_name=$this->input->post('item_name');
            $item_price=$this->input->post('item_price');
            $item_quantity=$this->input->post('item_quantity');
            $item_expiry=$this->input->post('item_expiry');
            $item_branch=$this->input->post('item_branch');
            $item_type_color=$this->input->post('item_type_color');
            $item_service=$this->input->post('item_service');
            $query_check=$this->main_model->check_item($item_name,$item_id,$item_branch);
            if($query_check->num_rows()<1){
                $dataArray=array(
                    'item_name'=>$item_name,
                    'item_id'=>$item_id,
                    'item_category'=>$item_category,
                    'item_type_color'=>$item_type_color,
                    'item_service'=>$item_service,
                    'item_price'=>$item_price,
                    'item_quantity'=>$item_quantity,
                    'item_expiry'=>$item_expiry,
                    'item_branch'=>$item_branch,
                    'date_created'=>date('M-d-Y'),
                    'created_by'=>$user
                );
                $queryInsert=$this->db->insert('stock_item',$dataArray);
                if($queryInsert){
                    echo 'Data saved successfully.';
                }else{
                    echo 'Ooops, please try again';
                }
            }else{
                echo 'Ooops, Item found';
            }
        }
    }
    function edit_item_name(){
        if($this->input->post('stockid')){
            $stockid=$this->input->post('stockid');
            echo $this->main_model->edit_this_item_name($stockid);
        }
    }
    function saveEditedItem(){
        $user=$this->session->userdata('username');
        if($this->input->post('editeditem_category')){
            $hiddenUpdatedItemName=$this->input->post('hiddenUpdatedItemName');
            $editeditem_category=$this->input->post('editeditem_category');
            $editeditem_id=$this->input->post('editeditem_id');
            $editeditem_name=$this->input->post('editeditem_name');
            $editeditem_price=$this->input->post('editeditem_price');
            $editeditem_quantity=$this->input->post('editeditem_quantity');
            $editeditem_expiry=$this->input->post('editeditem_expiry');
            $editeditem_branch=$this->input->post('editeditem_branch');
            $editeditem_type_color=$this->input->post('editeditem_type_color');
            $editeditem_service=$this->input->post('editeditem_service');
            $data=array(
              'item_name'=>$editeditem_name,
              'item_id'=>$editeditem_id,
              'item_category'=>$editeditem_category,
              'item_type_color'=>$editeditem_type_color,
              'item_service'=>$editeditem_service,
              'item_price'=>$editeditem_price,
              'item_quantity'=>$editeditem_quantity,
              'item_expiry'=>$editeditem_expiry,
              'item_branch'=>$editeditem_branch
            );
            $this->db->where('id',$hiddenUpdatedItemName);
            $query=$this->db->update('stock_item',$data);
            if($query){
                echo 'Updated successfully';
            }else{
                echo 'Please try again';
            }
        } 
    }
    function delete_stock_item(){
        if($this->input->post('stockid')){
            $stockid=$this->input->post('stockid');
            $this->db->where('id',$stockid);
            $query=$this->db->delete('stock_item');
            if($query){
                echo '<span class="text-success">Deleted successfully</span>';
            }else{
                echo '<span class="text-danger">Please try later</span>';
            }
        }  
    }
    function fetch_item_history(){
        $postData = $this->input->post();
        $data = $this->main_model->fetch_item_history($postData);
        echo json_encode($data);
    }
}
