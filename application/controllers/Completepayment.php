<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Completepayment extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        if($this->session->userdata('username') == '' || 
         $this->session->userdata('usertype')!= 'superAdmin'){
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
        $query_name = $this->db->query("select * from school");
        $row_name = $query_name->row();
        $school_name=$row_name->name;
        $schoolID=$row_name->id;
        date_default_timezone_set("Africa/Addis_Ababa");
        $dtz = new DateTimeZone('UTC');
        $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
        $date = gmdate("Y-m-d h:i A", $dt->format('U'));
        if(isset($_GET['tx']) && isset($_GET['amp;price']) && isset($_GET['amp;duration'])) {
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
            $dataInsert=array();
            if(strcmp($data['status'], "success") == 0) {
                $tx_ref = $_GET['tx'];
                $user_id = $schoolID;
                $pay_amount = $_GET['amp;price'];
                $duration = $_GET['amp;duration'];
                $currentDate = date('Y-m-d');
                $nextDuration=$duration * 30;
                $dataInsert=array(
                    'transaction_ref'=>$tx_ref,
                    'amount'=>$pay_amount,
                    'subscriber_id'=>$user_id,
                    'subscriber_name'=>$school_name,
                    'transaction_date'=>$date
                );
                $queryInsert=$this->db->insert('subscription_transaction_detail',$dataInsert);
                if($queryInsert){
                    $selectSql =$this->db->query("SELECT sub_until FROM subscription_detail WHERE user_id='$user_id' " );
                    if ($selectSql->num_rows() > 0) {
                        $rowUntl=$selectSql->row();
                        $sub_untils=$rowUntl->sub_until;
                        $subUntil =date('Y-m-d', strtotime($sub_untils . ' +'.$nextDuration.' days'));
                        $this->db->where('user_id',$user_id);
                        $this->db->set('sub_date',$date);
                        $this->db->set('sub_until',$subUntil);
                        $queryUpdate=$this->db->update('subscription_detail');
                    }else{
                        $subUntil =date('Y-m-d', strtotime($date . ' +'.$nextDuration.' days'));
                        $dataUpdate=array();
                        $dataUpdate=array(
                            'sub_date'=>$date,
                            'sub_until'=>$subUntil,
                            'user_id'=>$user_id
                        );
                        $query=$this->db->insert('subscription_detail',$dataUpdate);
                    }
                }
                header("Location:".base_url()."subscription/");
            }
        }else{
            header("Location:".base_url()."subscription/");
        }
    }
}