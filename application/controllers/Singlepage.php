<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Singlepage extends CI_Controller {
	public function index()
	{
        $this->load->model('main_model');
        if ( isset($_GET['blog']) ) {
            $user = $_GET['blog'];
            $data['blogs']=$this->main_model->fetch_blogs();
            $data['viewblogs']=$this->main_model->fetch_ViewBlogs($user);
            $data['teachers']=$this->main_model->fetch_teachers();
            $data['fetch_gallery']=$this->main_model->fetch_gallery();
            $data['fetch_galleryToWebsite']=$this->main_model->fetch_galleryToWebsite();
            $data['fetch_single_gallery']=$this->main_model->fetch_single_gallery();
            $data['social_pages']=$this->main_model->fetch_social_pages();
            $data['schools']=$this->main_model->fetch_school();
            $this->load->view('singlepage',$data);  
        }else{
            redirect("login/");
        }
	}  
}