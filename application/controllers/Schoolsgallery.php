<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schoolsgallery extends CI_Controller {
    public function index()
    {
        $this->load->model('main_model');
        $data['blogs']=$this->main_model->fetch_blogs();
        $data['teachers']=$this->main_model->fetch_teachers();
        $data['fetch_gallery']=$this->main_model->fetch_gallery();
        $data['fetch_galleryToWebsite']=$this->main_model->fetch_galleryToWebsite();
        $data['all_fetch_gallery']=$this->main_model->all_fetch_gallery();
        $data['fetch_single_gallery']=$this->main_model->fetch_single_gallery();
        $data['social_pages']=$this->main_model->fetch_social_pages();
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('schoolgallery',$data);
    } 
}