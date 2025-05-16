<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requestpurchase extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $this->load->helper('security');
        $userLevel = userLevel();
        $this->db->where('usergroup',$_SESSION['usertype']);
        $this->db->where('tableName','inventoryManagement');
        $this->db->where('allowed','purchaseRequest');
        $uperStuDE=$this->db->get('usergrouppermission');  
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
	public function index($page='request-purchase')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $user=$this->session->userdata('username');
        $this->db->select('branch');
        $this->db->where('username',$user);
        $query_branch=$this->db->get('users');
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;

        $this->db->select('max(year_name) as year');
        $query=$this->db->get('academicyear');
        $row = $query->row();
        $max_year=$row->year;
        $data['academicyear']=$this->main_model->academic_year();
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $data['item_list']=$this->main_model->fetch_item_list();
        $this->load->view('home-page/'.$page,$data);
	}
    public function submit_request()
    {   
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('etc/UTC');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $date = gmdate("Y-m-d h:i A", $dt->format('U'));
        $user=$this->session->userdata('username');
        $dataArray=array();
        if($this->input->is_ajax_request()){
            if($this->input->post('purchase_request_description')){
                $purchase_request_description=$this->input->post('purchase_request_description',TRUE);
                $purchaseUnit=$this->input->post('purchaseUnit',TRUE);
                $purchaseQuantity=$this->input->post('purchaseQuantity',TRUE);
                $purchaseUnitPrice=$this->input->post('purchaseUnitPrice',TRUE);
                $purchase_request_remark=$this->input->post('purchase_request_remark',TRUE);
                $PurchaseType=$this->input->post('PurchaseType',TRUE);
                $freightType=$this->input->post('freightType',TRUE);
                $purchase_request_description = xss_clean($purchase_request_description);
                $purchaseUnit = xss_clean($purchaseUnit);
                $purchaseQuantity = xss_clean($purchaseQuantity);
                $purchaseUnitPrice = xss_clean($purchaseUnitPrice);
                $purchase_request_remark = xss_clean($purchase_request_remark);
                $PurchaseType = xss_clean($PurchaseType);
                $freightType = xss_clean($freightType);
                $requested_amount=$purchaseUnitPrice * $purchaseQuantity;
                $requested_amount= number_format((float)$requested_amount,2,'.','');
                $queryCheck=$this->main_model->check_requested_purchase_item($purchase_request_description,$purchaseQuantity,$user);
                if($queryCheck){
                    $dataArray=array(
                        'requested_item_desc'=>$purchase_request_description,
                        'requested_unit'=>$purchaseUnit,
                        'requested_quantity'=>$purchaseQuantity,
                        'requested_unit_price'=>$purchaseUnitPrice,
                        'requested_amount'=>$requested_amount,
                        'remark'=>$purchase_request_remark,
                        'purchaseType'=>$PurchaseType,
                        'freightType'=>$freightType,
                        'requested_date'=>$date,
                        'requested_by'=>$user
                    );
                    $query=$this->db->insert('stock_purchase_request',$dataArray);
                    if($query){
                        echo '1';
                    }else{
                        echo '0';
                    }
                }else{
                    echo '2';
                }
            }
        }
    }
    function fetch_myrequested_purchase(){
        $user=$this->session->userdata('username');
        echo $this->main_model->fetch_myrequested_purchase($user);
    }
    function delete_request_item(){
        if($this->input->post('requestid')){
            $requestid=$this->input->post('requestid',TRUE);
            $requestid = xss_clean($requestid);
            $this->db->where('id',$requestid);
            $this->db->where('status','0');
            $this->db->delete('stock_purchase_request');
        }
    }
}
