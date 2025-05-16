<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unseen_bookrequest_notification extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('chat_model');
        if($this->session->userdata('username') == ''){
            $this->session->set_flashdata("error","Please Login first");
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
        $queryLogo=$this->db->get('school');
        if($queryLogo->num_rows()>0){
            $rowLogo=$queryLogo->row();
            $logo=$rowLogo->logo;
        }
        $accessbranch = sessionUseraccessbranch();
        $branchName = sessionUserDetailNonStudent();
        $branch=$branchName['branch'];
        $YearName = sessionAcademicYear();
        $max_year=$YearName['year'];
        $user=$this->session->userdata('username');
        $queyCheck=$this->db->query("select book_id from book_borrow where status='0' ");
        if($queyCheck->num_rows()>0){
            $webNotificationPayload['title'] = 'Book borrow request found';
            if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
                $webNotificationPayload['body'] = $this->chat_model->fetch_requested_book_toapprove_all($user);
            }else{
                $queryHead=$this->db->query("select head_name from book_stock_head where head_name='$user' ");
                if($queryHead->num_rows()>0){
                    $webNotificationPayload['body'] = $this->chat_model->fetch_requested_book_toapprove($user);
                }
            }
            $webNotificationPayload['icon'] = base_url().'/logo/'.$logo;
            $webNotificationPayload['url'] = base_url().'borrowrequests/?library-management-system/';
            echo json_encode($webNotificationPayload);
        }  
	}    
}