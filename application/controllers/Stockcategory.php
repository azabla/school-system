<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stockcategory extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='inventoryManagement' and allowed='stockCategory' order by id ASC ");  
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
	public function index($page='stockcategory')
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
    function insert_category(){
        $dataArray=array();
        $user=$this->session->userdata('username');
        if($this->input->post('category_name')){
            $categoryName=$this->input->post('category_name');
            $categoryID=$this->input->post('category_id');
            $categoryOwner=$this->input->post('category_owner');
            $query_check=$this->main_model->check_category($categoryName,$categoryID);
            if($query_check->num_rows()<1){
                $dataArray=array(
                    'category_name'=>$categoryName,
                    'category_id'=>$categoryID,
                    'category_owner'=>$categoryOwner,
                    'date_created'=>date('M-d-Y'),
                    'created_by'=>$user
                );
                $queryInsert=$this->db->insert('stock_category',$dataArray);
                if($queryInsert){
                    echo 'Data saved successfully.';
                }else{
                    echo 'Ooops, please try again';
                }
            }else{
                echo 'Ooops, Category found';
            }
        }
    }
    function view_saved_category(){
        $postData = $this->input->post();
        $data = $this->main_model->view_saved_category($postData);
        echo json_encode($data);
    }
    function edit_category_name(){
        if($this->input->post('category_name')){
            $category_name=$this->input->post('category_name');
            echo $this->main_model->edit_this_category_name($category_name);
        }
    }
    function saveEditedCategory(){
        $user=$this->session->userdata('username');
        if($this->input->post('editedCategoryID')){
            $categoryID=$this->input->post('editedCategoryID');
            $category_name=$this->input->post('editedCategoryName');
            $categoryHead=$this->input->post('editedcategory_owner');
            $hiddenUpdatedCategory=$this->input->post('hiddenUpdatedCategoryName');
            $data=array(
              'category_name'=>$category_name,
              'category_id'=>$categoryID,
              'category_owner'=>$categoryHead
            );
            $this->db->where('category_name',$hiddenUpdatedCategory);
            $query=$this->db->update('stock_category',$data);
            if($query){
                echo 'Updated successfully';
            }else{
                echo 'Please try again';
            }
        } 
    }
    function delete_stock_category(){
        if($this->input->post('category_name')){
            $category_name=$this->input->post('category_name');
            $this->db->where('category_name',$category_name);
            $query=$this->db->delete('stock_category');
            if($query){
                echo '<span class="text-success">Deleted successfully</span>';
            }else{
                echo '<span class="text-danger">Please try later</span>';
            }
        }  
    }
}
