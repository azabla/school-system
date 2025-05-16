<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_session_library {
    public function abc() {
      echo "Welcome to GeeksforGeeks";
   }
    
    function sessionUser($user){
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row_array();
        $branch=$row_branch['branch']; 
    }
    function sessionBranch($userType){
        
        $this->settings = $this->main_model->get_session_branch($userType);
        return $this->settings;
    }
    function sessionYear(){
        $this->settings = $this->main_model->get_max_year();
        return $this->settings;
    }
}
