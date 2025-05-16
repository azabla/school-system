<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout_payment extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('gs_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        if($this->session->userdata('username') == '' || $userLevel!='3'){
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
	public function index()
	{
        $userName=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_gradesec = $this->db->query("select username ,fname,mname,grade from users where username='$userName' and academicyear='$max_year' ");
        $row_gradesec = $query_gradesec->row();
        $grade=$row_gradesec->grade;
        if(isset($_GET['tx'])) {
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://api.chapa.co/v1/transaction/verify/'.$_GET['tx'],
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'GET',
			  CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer CHASECK-uculRvXzHhJNDkbcWBKQ13nzmZ4Jv0B7'
			  ),
			));
			$response = curl_exec($curl);
			curl_close($curl);
			$data = json_decode($response, true);
			if(strcmp($data['status'], "success") == 0) {
				$data=array();
				$tx_ref = $_GET['tx'];
				$username = $data['data']['first_name'];
				$data=array(
					'customer_name'=>$userName,
					'txn_id '=>$tx_ref,
					'paid_amount'=>'300',
					'payment_name'=>'gs-sms-feature',
					'payment_status'=>'success',
					'academicyear'=>$max_year,
					'season'=>''
				);
				$queryInsert=$this->db->insert('transactions',$data);
				header("Location:".base_url()."home/");
			}
		}
	}
}
?>