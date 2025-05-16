<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Processubscriptionpayment extends CI_Controller {
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
        $email=$row_name->email;
        if(isset($_POST['process_subscriptionsubmit']) && isset($_POST['paid_price_subscription_gs']))
        {
            $amountPaid=$this->input->post('paid_price_subscription_gs');
            $date_duaration=$this->input->post('date_duration');
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $randomText = 'gstx_';
            $length = 100;
            for ($i = 0; $i < $length; $i++) {
                $randomText .= $characters[rand(0, strlen($characters) - 1)];
            }
            $queryParams = array(
                'tx' => $randomText,
                'price' => $_POST['paid_price_subscription_gs'],
                'duration' => $date_duaration
            );
            $queryString = http_build_query($queryParams);
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
             "amount":"'.$amountPaid.'",
              "currency": "ETB",
            "email": "'.$email.'",
            "first_name": "'.$school_name.'",
            "last_name": "",
            "phone_number": "",
              "tx_ref": "'.$randomText.'",
              "return_url": "'.base_url().'completepayment?'.$queryString.'",
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
        }else {
            redirect('subscription/');
        }
    }
}