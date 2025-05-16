<nav class="navbar navbar-expand-lg main-navbar sticky">
  <div class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
						collapse-btn"> 
            <i data-feather="menu"></i>
          </a>
      </li>
      <li>
       <a href="<?php echo base_url(); ?>dashboard/?dashboard-page/" class="nav-link nav-link-lg" >
          <i data-feather="monitor"></i>
        </a>
      </li>
      <li>
       <a href="<?php echo base_url(); ?>home/?admin-home-page/" class="nav-link nav-link-lg" >
          <i data-feather="home"></i>
        </a>
      </li>
      <li>
        <form class="form-inline mr-auto pull-right">
          <div class="search-element">
             <?php foreach($sessionuser as $sessionusers){
              if($sessionusers->status=='Active'){
            if($_SESSION['usertype']===trim('superAdmin')){ 
                $query_name = $this->db->query("select * from school");
                $row_name = $query_name->row();
                $school_name=$row_name->name;
                $schoolID=$row_name->id;
                $queryCheck=$this->db->query("select * from subscription_detail where user_id ='$schoolID'");
                if($queryCheck->num_rows()>0){
                  date_default_timezone_set("Africa/Addis_Ababa");
                  $dtz = new DateTimeZone('UTC');
                  $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
                  $date = gmdate("Y-m-d h:i A", $dt->format('U'));
                  $rowData=$queryCheck->row();
                  $endDate=$rowData->sub_until;
                  $date = strtotime($endDate);
                  $remaining = $date - strtotime(date('Y-m-d h:i A'));
                  $days_remaining = floor($remaining / 86400);
                  $hours_remaining = floor(($remaining % 86400) / 3600);
                  if($days_remaining <='30'){
                    if($days_remaining <='0' && $hours_remaining<='0'){
                      header("Location:".base_url()."login/");
                    }else{
                      echo "<small><span class='text-danger'><h5>
                      <i data-feather='alert-triangle'></i> 
                      $days_remaining Days and $hours_remaining Hours left <a href='".base_url()."subscription/'>Pay now</a></h5></span></small>";
                    }
                  }else{
                    echo '<small class="badge badge-info">Software Level: Premium</small>';
                  }
                }else{
                  echo '<small class="badge badge-info">Software Level: Premium</small>';
                }
              }
            }else{
              redirect('loginpage/');
            }
          }?>           
          </div>
        </form>
      </li>
    </ul>
  </div>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="seen nav-link nav-link-lg message-togglee">
              <i data-feather="mail"></i>
              <span class="badge headerBadge1 count-new-inbox"></span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
              <div class="dropdown-header">
                Messages
              </div>
              <div class="dropdown-list-content dropdown-list-message inbox-show">
              </div>
              <div class="dropdown-footer text-center">
                <a href="<?php echo base_url();?>inbox/">View All <i class="fas fa-chevron-right"></i></a>
              </div>
            </div>
          </li>
         
    <li class="dropdown dropdown-list-toggle">
      <a href="#" data-toggle="dropdown" class="seen_noti nav-link nav-link-lg message-togglee">
        <i data-feather="bell"></i>
        <span class="badge headerBadge1 count-new-notification"></span>
      </a>
      <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
          <div class="dropdown-header">
            Notifications
            <div class="float-right"> </div>
          </div>
          <div class="table-responsive" style="height:40vh;">
            <div class="dropdown-list-icons notification-show" >
              <a href="#" class="dropdown-item dropdown-item-unread">

              </a> 
            </div>
          </div>

      </div>
    </li>
     <?php foreach($sessionuser as $sessionusers){?>
          <li class="dropdown"><a href="#" data-toggle="dropdown"
              class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
              <?php if($sessionusers->profile == ''){ ?>
              <img alt="image" src="<?php echo base_url(); ?>/profile/defaultProfile.png" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span>
            <?php } else{ ?>
              <img alt="image" src="<?php echo base_url(); ?>/profile/<?php echo $sessionusers->profile;?>" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span>
            <?php } ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
              <div class="dropdown-title">Hello <?php echo $_SESSION['username']; ?>
              </div>
              <a href="<?php echo base_url(); ?>myprofile/" class="dropdown-item has-icon"> <i class="far fa-user"></i> Profile
              </a> 
              <div class="dropdown-divider"></div>
              <a href="<?php echo base_url() ?>mysessions/" class="dropdown-item has-icon text-alert"> <i class="fas fa-sign-in-alt"></i>
                My Session
              </a>
              <div class="dropdown-divider"></div>
              <a href="<?php echo base_url(); ?>logout/" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                Logout
              </a>
            </div>
          </li>
        <?php }?>
        </ul>
      </nav>
      <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
          aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                 <?php echo form_open_multipart('home/');?>
                  <label>Create Post</label>
                  <div class="form-group col-lg-12">
                      <div class="form-group">
                        <input class="form-control" name="title" required="required" type="text" placeholder="Title here">
                      </div>
                      <div class="form-group">
                        <textarea class="form-control" name="posthere" placeholder="What is in your mind?"></textarea>
                      </div>
                     <div class="custom-file form-group">
                        <input type="file" class="custom-file-input" id="postphoto" name="postphoto">
                        <label class="custom-file-label" for="customFile">Post photo</label>
                      </div>
                  </div>
                  <button type="submit"  name="post" class="btn btn-primary m-t-15 waves-effect">Post</button>
                </form>
              </div>
            </div>
          </div>
        </div> -->

         <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
          aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <form action="<?php echo base_url()?>search/" method="POST">
                <div class="form-group">
                    <div class="search-element">
                      <input class="form-control" name="search" required="required" type="search" placeholder="Search posts" aria-label="Search">
                      <button class="btn btn-primary m-t-15 waves-effect" type="submit">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
               </div>
              </form>
              </div>
            </div>
          </div>
        </div>
