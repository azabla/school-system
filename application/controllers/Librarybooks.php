<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Librarybooks extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='libraryManagement' and allowed='libraryBooks' order by id ASC ");  
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
	public function index($page='librarybooks')
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
        $accessbranch = sessionUseraccessbranch();
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
            $data['staffs']=$this->main_model->fetchStaffsForPlacement();
        }else{
            $data['staffs']=$this->main_model->fetchMyStaffsForPlacement($branch);
        }
        $this->load->view('home-page/'.$page,$data);
	}
    function fetch_form_toadd_new_book(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetch_form_toadd_new_book($max_year);
    }
    function fetch_form_toadd_library_head(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->fetch_form_toadd_library_head($max_year);
    }
    function insert_item(){
        $dataArray=array();
        $user=$this->session->userdata('username');
        if($this->input->post('book_id')){
            $book_id=$this->input->post('book_id');
            $book_name=$this->input->post('book_name');
            $book_price=$this->input->post('book_price');
            $book_quantity=$this->input->post('book_quantity');
            $book_grade=$this->input->post('book_grade');
            $book_branch=$this->input->post('book_branch');
            $query_check=$this->main_model->check_book_stock($book_name,$book_id,$book_grade);
            if($query_check->num_rows()<1){
                $dataArray=array(
                    'book_name'=>$book_name,
                    'book_id'=>$book_id,
                    'book_price'=>$book_price,
                    'book_quantity'=>$book_quantity,
                    'book_grade'=>$book_grade,
                    'book_branch'=>$book_branch,
                    'date_created'=>date('M-d-Y'),
                    'created_by'=>$user
                );
                $queryInsert=$this->db->insert('book_stock',$dataArray);
                if($queryInsert){
                    echo '1';
                }else{
                    echo '0';
                }
            }else{
                echo '2';
            }
        }
    }
    function savebook_stock_head(){
        $user=$this->session->userdata('username');
        if($this->input->post('book_stock_head')){
            $book_stock_head=$this->input->post('book_stock_head');
            $query_check=$this->main_model->check_book_stock_head($book_stock_head);
            if($query_check->num_rows()<1){
                $dataArray=array(
                    'head_name'=>$book_stock_head,
                    'date_created'=>date('M-d-Y'),
                    'created_by'=>$user
                );
                $queryInsert=$this->db->insert('book_stock_head',$dataArray);
                if($queryInsert){
                    echo '<span class="text-success">Data saved successfully.</span>';
                }else{
                    echo '<span class="text-danger">Ooops, please try again.</span>';
                }
            }else{
                echo '<span class="text-danger">Ooops, Record found</span>';
            }
        }
    }
    function removeBook_Stock_Head(){
        $user=$this->session->userdata('username');
        if($this->input->post('userid')){
            $userid=$this->input->post('userid');
            $this->db->where('head_name',$userid);
            $query=$this->db->delete('book_stock_head');
            if($query){
                echo '<span class="text-success">Removed successfully</span>';
            }else{
                echo '<span class="text-danger">Please try later</span>';
            }
        }
    }
    function fetch_book_history(){
        $postData = $this->input->post();
        $data = $this->main_model->fetch_book_history($postData);
        echo json_encode($data);
    }
    function fetch_book_head(){
        echo $this->main_model->fetch_book_head();
    }
    function edit_book_name(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        if($this->input->post('stockid')){
            $stockid=$this->input->post('stockid');
            echo $this->main_model->edit_book_name($stockid,$max_year);
        }
    }
    function saveEditedItem(){
        $user=$this->session->userdata('username');
        if($this->input->post('editedbook_id')){
            $hiddenUpdatedBookName=$this->input->post('hiddenUpdatedBookName');
            $editedbook_id=$this->input->post('editedbook_id');
            $editedbook_grade=$this->input->post('editedbook_grade');
            $editedbook_name=$this->input->post('editedbook_name');
            $editedbook_price=$this->input->post('editedbook_price');
            $editedbook_quantity=$this->input->post('editedbook_quantity');
            $editedbook_branch=$this->input->post('edited_book_branch');
            $data=array(
                'book_name'=>$editedbook_name,
                'book_id'=>$editedbook_id,
                'book_price'=>$editedbook_price,
                'book_quantity'=>$editedbook_quantity,
                'book_grade'=>$editedbook_grade,
                'book_branch'=>$editedbook_branch
            );
            $this->db->where('id',$hiddenUpdatedBookName);
            $query=$this->db->update('book_stock',$data);
            if($query){
                echo '1';
            }else{
                echo '0';
            }
        } 
    }
    function delete_stock_book(){
        if($this->input->post('stockid')){
            $stockid=$this->input->post('stockid');
            $this->db->where('id',$stockid);
            $query=$this->db->delete('book_stock');
            if($query){
                echo '<span class="text-success">Deleted successfully</span>';
            }else{
                echo '<span class="text-danger">Please try later</span>';
            }
        }  
    }
    function add_custom_grade(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        echo $this->main_model->add_custom_grade($max_year);
    }
    function savegs_custom_grade(){
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $user=$this->session->userdata('username');
        if($this->input->post('custom_grade_name')){
            $custom_grade_name=$this->input->post('custom_grade_name');
            $query_check=$this->main_model->check_custom_grade_found($custom_grade_name);
            if($query_check->num_rows()<1){
                $dataArray=array(
                    'name'=>$custom_grade_name,
                    'date_created'=>date('M-d-Y'),
                    'academicyear'=>$max_year
                );
                $queryInsert=$this->db->insert('grade',$dataArray);
                if($queryInsert){
                    echo '<span class="text-success">Data saved successfully.</span>';
                }else{
                    echo '<span class="text-danger">Ooops, please try again.</span>';
                }
            }else{
                echo '<span class="text-danger">Ooops, Record found</span>';
            }
        }
    }
    function fetch_custom_grade_gs(){
        echo $this->main_model->fetch_custom_grade_gs();
    }
    function removeBook_this_custom_grade(){
        $user=$this->session->userdata('username');
        if($this->input->post('userid')){
            $userid=$this->input->post('userid');
            $this->db->where('name',$userid);
            $query=$this->db->delete('grade');
            if($query){
                echo '<span class="text-success">Removed successfully</span>';
            }else{
                echo '<span class="text-danger">Please try later</span>';
            }
        }
    }
    
}
