<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Payment extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='feemanagment' order by id ASC "); 
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows() < 1 || $userLevel!='1'){
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
	public function index($page='payment')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        /*if(isset($_POST['paid'])){
            if(!empty($_POST['paidid'])){
            $paidid=$this->input->post('paidid');
            $month=$this->input->post('month');
            $ptype=$this->input->post('ptype');
            $receipt=$this->input->post('receipt');
            $gradesec=$this->input->post('gradesec');
            $acy=$this->input->post('acy');
                
            }
        }*/
        if(isset($_POST['deletepayment'])){
            $id=$this->input->post('deletepayment');
            $this->main_model->delete_payment($id);
        }
        $data['grade']=$this->main_model->fetch_grade($max_year);
        $data['month']=$this->main_model->fetch_month();
        $data['payment']=$this->main_model->fetch_payment($max_year);
        $data['payment_category']=$this->main_model->fetch_payment_category($max_year);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('home-page/'.$page,$data);
	} 
    function save_payment(){
        $user=$this->session->userdata('username');
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $queryGYear = $this->db->query("select gyear from academicyear where year_name='$max_year' ");
        $rowG = $queryGYear->row();
        $gyear=$rowG->gyear;
        $accessbranch = sessionUseraccessbranch();
        if($this->input->post('academicyear')){
            $acy=$this->input->post('academicyear');
            $gradesec=$this->input->post('gradesec');
            $receipt=$this->input->post('receipt');
            $ptype=$this->input->post('paymentType');
            $month=$this->input->post('month');
            $paidid=$this->input->post('id');
            for($i=0;$i<count($paidid);$i++){
                $check=$paidid[$i];
                $query=$this->main_model->insert_payment($check,$month,$ptype,$gradesec,$acy);
                if($query){
                    $data=array(
                        'stuid'=>$check,
                        'month'=>$month,
                        'gradesecc'=>$gradesec,
                        'paymentype'=>$ptype,
                        'academicyear'=>$acy,
                        'payment_receipt'=>$receipt,
                        'paid'=>1,
                        'method'=>'Manual',
                        'date_created'=>date('M-d-Y'),
                        'byuser'=>$user
                    );
                    $inserted=$this->db->insert('payment',$data);
                    
                }
            }
            if($inserted){
                echo 'Payment Saved successfully.';
            }else{
                echo 'Please try Again. Something goes wrong.';
            }
        }
    }
}