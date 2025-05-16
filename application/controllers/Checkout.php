<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {
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
        $user=$this->session->userdata('username');
        $usertype=$this->session->userdata('usertype');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query_gradesec = $this->db->query("select username,mobile,father_mobile, grade,fname, mname from users where username='$user' and academicyear='$max_year' ");
        if($query_gradesec->num_rows()>0){
            $row_gradesec = $query_gradesec->row();
            $grade=$row_gradesec->grade;
            $username=$row_gradesec->username;
            $fname=$row_gradesec->fname;
            $mname=$row_gradesec->mname;
            $father_mobile=$row_gradesec->father_mobile;
            $mobile=$row_gradesec->mobile; 
            if(isset($_POST['submit_gs_payment'])){
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                $randomText = 'gs_tech_';
                $length = 45;
                for ($i = 0; $i < $length; $i++) {
                    $randomText .= $characters[rand(0, strlen($characters) - 1)];
                }
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.chapa.co/v1/transaction/initialize',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>'{
                 "amount":"300",
                  "currency": "ETB",
                  "email": "info@grandstande.com",
                  "first_name": "'.$fname.'",
                  "last_name": "'.$mname.'",
                  "phone_number": "",
                  "tx_ref": "'.$randomText.'",
                  "return_url": "'.base_url().'Checkout_payment?tx='. $randomText .'",
                  "customization[title]": "Payment for my favourite merchant",
                  "customization[description]": "I love online payments."
                  }',
                  CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer CHASECK-uculRvXzHhJNDkbcWBKQ13nzmZ4Jv0B7',
                    'Content-Type: application/json'
                  ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $data = json_decode($response, true);
                header("Location: " . $data['data']['checkout_url']);
            }
        }else{
           redirect('Home/');
        } 
	}
}