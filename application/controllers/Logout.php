<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class logout extends CI_Controller {
	function __construct(){
		parent::__construct();
		ob_start();
		$this->load->helper('cookie');
	}
	public function index()
	{
		$this->load->driver('cache');
		delete_cookie('username');
		$this->session->unset_userdata('username');
		setcookie('username',"",time()-3600,"/");
        setcookie('password',"",time()-3600,"/");
    	unset($_SESSION);
    	session_destroy();
    	$this->cache->clean();
    	ob_clean();
    	redirect('login/');
	} 
}