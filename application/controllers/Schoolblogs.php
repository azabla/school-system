<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schoolblogs extends CI_Controller {
	public function index()
	{
        $this->load->model('main_model');
        $data['teachers']=$this->main_model->fetch_teachers();
        $data['blogs']=$this->main_model->fetch_blogs();
        $data['fetch_gallery']=$this->main_model->fetch_gallery();
        $data['social_pages']=$this->main_model->fetch_social_pages();
        $data['schools']=$this->main_model->fetch_school();//school
		$this->load->view('blogs',$data);
	} 
}