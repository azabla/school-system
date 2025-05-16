<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Replaycomment extends CI_Controller {
    public function __construct(){
      parent::__construct();
      $this->load->model('main_model');
      if($this->session->userdata('username') == ''){
        $this->session->set_flashdata("error","Please Login first");
        redirect('login/');
      }   
    }
  public function index()
  {
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $logged_id=$row_branch->id;
    $fName=$row_branch->fname;
    $mName=$row_branch->mname;
    $profile=$row_branch->profile;
    date_default_timezone_set("Africa/Addis_Ababa");
    if($this->input->post('comID')){
      $comID=$this->input->post('comID');
      $replyText=strip_tags($this->input->post('replyText'));
      $datereplay=date("Y-m-d h:i:s");
      $data=array(
        'uid'=>$user,
        'pid'=>$comID,
        'pcomment'=>$replyText,
        'comdate'=>$datereplay
      );
      $queryInsert=$this->db->insert('post_comment',$data);
      if($queryInsert){
        echo '<div class="chat-box">
          <div class="chat outgoing">
          <div class="details">
            <p class="p">'.$replyText.' <small class="time text-muted"> <i class="fas fa-clock"></i> '.$datereplay.' </small><br>
              <small class="time text-muted pull-right"> '.$fName.' '.$mName.' </small>
            </p>
          </div>';
          if($profile!=''){ 
            echo '<img alt="Pic" src="'.base_url().'/profile/'.$profile.'" class="border-circle">';
          }else{
            echo' <img alt="Pic" src="'.base_url().'/profile/defaultProfile.png" class="border-circle">';
          } 
        echo '</div>
      </div>';
      }
    }
  } 
  function fetch_feeds_comments(){
    if($this->input->post('last_video_id')){
      $id=$this->input->post('last_video_id');
      $category_id=$this->input->post('category_id');
      echo $this->main_model->fetch_this_post_comments($id,$category_id);
    }
  }   
}