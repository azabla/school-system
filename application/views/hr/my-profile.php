<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title> 
    <?php foreach($schools as $school) {
      echo $school->name;}
      ?>
    </title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon'
   href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

      <?php include('header.php'); ?>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
           <?php include('left_menu.php'); ?>
        </aside>
      </div>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
              <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="infoPasswordChanged"></div>
            <div class="row mt-sm-4">
              
              <div class="col-12 col-md-12 col-lg-8">
                <div class="row">
                <div class="card card-body">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                          aria-selected="true">About</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="profile-tab2" data-toggle="tab" href="#settings" role="tab"
                          aria-selected="false">Profile</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="password-tab2" data-toggle="tab" href="#password" role="tab"
                          aria-selected="false">Password</a>
                      </li>
                    </ul>
                    <?php foreach($sessionuser as $sessionusers){?>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2">
                        <p class="m-t-30">
                          <?php echo $sessionusers->biography; ?>
                        </p>
                      </div>
                      <?php
                      if(isset($_SESSION['error'])) { ?>
                        <span class="text-danger">
                          <?php echo $_SESSION['error']; ?>
                        </span>
                        <?php } ?>
                        <?php
                        if(isset($_SESSION['success'])) { ?>
                          <span class="text-success">
                            <?php echo $_SESSION['success']; ?>
                          </span>
                          <?php } ?>
                      
                      <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="profile-tab2">
                      <?php echo form_open_multipart('viewmystaffprofile/');?>
                          <div class="card-header">
                            <h4>Edit Profile</h4>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="form-group col-lg-6">
                                <label>First Name</label>
                                <input type="text" class="form-control fname" disabled="disabled"
                                value="<?php echo $sessionusers->fname ; ?>">
                                <div class="invalid-feedback">
                                  Please fill in the first name
                                </div>
                              </div>
                              <div class="form-group col-lg-6">
                                <label>Father Name</label>
                                <input type="text" class="form-control lname" disabled="disabled"
                                value="<?php  echo $sessionusers->mname  ; ?>">
                                <div class="invalid-feedback">
                                  Please fill in the last name
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-lg-6">
                                <label>G.Father Name</label>
                                <input type="text" class="form-control lname" disabled="disabled"
                                value="<?php  echo $sessionusers->lname  ; ?>">
                                <div class="invalid-feedback">
                                  Please fill in the last name
                                </div>
                              </div>
                              <div class="form-group col-lg-6">
                                <label>Profile Photo</label>
                                 <input type="file" class="form-control"
                                name="profilephoto">
                                <div class="invalid-feedback">
                                  Please fill in the profile photo
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-lg-6">
                                <label>Email</label>
                                <input type="email" class="form-control email" name="email"
                                 value="<?php  echo $sessionusers->email  ; ?>">
                                <div class="invalid-feedback">
                                  Please fill in the email
                                </div>
                              </div>
                              <div class="form-group col-lg-6">
                                <label>Phone</label>
                                <input type="tel" class="form-control mobile" name="mobile"
                                value="<?php  echo $sessionusers->mobile  ; ?>">
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-lg-12">
                                <label>Biography</label>
                                <textarea name="biography" 
                                  class="form-control summernote-simple bio">
                                  <?php  echo $sessionusers->biography ; ?>
                                </textarea>
                              </div>
                            </div>    
                          </div>
                          <div class="card-footer text-right">
                            <button class="btn btn-primary" name="changeprofile">Save Changes</button>
                          </div>
                        </form>
                      </div>
                    
                      <div class="tab-pane fade show" id="password" role="tabpanel" aria-labelledby="password-tab2">
                          <div class="card-header">
                            <h4>Change Password</h4>
                          </div>
                          <form method="POST" id="changePassword">
                          <div class="row">
                              <div class="form-group col-lg-6">
                                <label>Old Password</label>
                                <input type="password" 
                                class="form-control" required="required" 
                                id="oldPassword" name="password1">
                              </div>
                              <div class="form-group col-lg-6">
                                <label>New Password</label>
                                <input type="password" required="required" 
                                class="form-control" id="newPassword" name="password2">
                              </div>
                            </div>
                            <div class="card-footer text-right">
                            <button class="btn btn-primary" type="submit">
                             Save Changes
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php foreach($sessionuser as $sessionusers){?>
              <div class="col-12 col-md-12 col-lg-4">
                <div class="card author-box">
                  <div class="card-body">
                    <div class="author-box-center">
                      <img alt="image" src="<?php echo base_url(); ?>/profile/<?php echo $sessionusers->profile;?>" class="border-circle-4profile">
                      <div class="clearfix"></div>
                      <div class="author-box-name">
                        <a href="#">
                          <?php echo $sessionusers->fname;
                           echo '&nbsp';
                          echo $sessionusers->mname ; ?>
                        </a>
                      </div>
                    </div>
                    <div class="text-center">
                      <div class="author-box-description">
                        <p><?php $sessionusers->usertype ;?>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <h4>Personal Details</h4>
                  </div>
                  <div class="card-body">
                    <div class="py-4">
                      <p class="clearfix">
                        <span class="float-left">
                          Gender
                        </span>
                        <span class="float-right text-muted">
                         <?php  echo $sessionusers->gender ; ?>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Birthday
                        </span>
                        <span class="float-right text-muted">
                         <?php  echo $sessionusers->dob ; ?>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Phone
                        </span>
                        <span class="float-right text-muted">
                          <?php  echo $sessionusers->mobile ; ?>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Mail
                        </span>
                        <span class="float-right text-muted">
                           <?php  echo $sessionusers->email; ?>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Status
                        </span>
                        <span class="float-right text-muted">
                           <?php  echo $sessionusers->status ; ?>
                        </span>
                      </p>
                     <p class="clearfix">
                        <span class="float-left">
                          City
                        </span>
                        <span class="float-right text-muted">
                          <?php  echo $sessionusers->city; ?>
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            <?php }?>
            </div>
          </div>
       
        </section>
      </div>
     <footer class="main-footer">
        <div class="footer-left">
         Call:+251967829025 &nbsp;&nbsp;
          Copyright &copy <?php echo date('Y');?>
          <a href="https://www.grandstande.com" target="_blanck">GrandStand</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/summernote/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</body>
<script type="text/javascript">
  $(document).on('click', '#changecolor', function() {
    var bgcolor=$(this).attr("value");
    $.ajax({
      url: "<?php echo base_url(); ?>Change_bgcolor/",
      method: "POST",
      data: ({
        bgcolor: bgcolor
      }),
    });
    if (bgcolor == "1") {
      $("body").removeClass();
      $("body").addClass("light");
      $("body").addClass("light-sidebar");
      $("body").addClass("theme-white");
      $(".choose-theme li").removeClass("active");
      $(".choose-theme li[title|='white']").addClass("active");
      $(".selectgroup-input[value|='1']").prop("checked", true);
    } else {
      $("body").removeClass();
      $("body").addClass("dark");
      $("body").addClass("dark-sidebar");
      $("body").addClass("theme-black");
      $(".choose-theme li").removeClass("active");
      $(".choose-theme li[title|='black']").addClass("active");
      $(".selectgroup-input[value|='2']").prop("checked", true);
    }
  });
</script>
<script type="text/javascript" language="javascript"> 
  var bgcolor_now=document.getElementById("bgcolor_now").value;
  if (bgcolor_now == "1") {
    $("body").removeClass();
    $("body").addClass("light");
    $("body").addClass("light-sidebar");
    $("body").addClass("theme-white");
    $(".choose-theme li").removeClass("active");
    $(".choose-theme li[title|='white']").addClass("active");
    $(".selectgroup-input[value|='1']").prop("checked", true);
  }else {
    $("body").removeClass();
    $("body").addClass("dark");
    $("body").addClass("dark-sidebar");
    $("body").addClass("theme-black");
    $(".choose-theme li").removeClass("active");
    $(".choose-theme li[title|='black']").addClass("active");
    $(".selectgroup-input[value|='2']").prop("checked", true);
  } 
</script>
  <script type="text/javascript">
    $('#changePassword').on('submit', function(event) {
    event.preventDefault();
    var oldPassword=$('#oldPassword').val();
    var newPassword=$('#newPassword').val();
      if( $('#newPassword').val() =='' || $('#oldPassword').val() =='')
      {
        alert("Oooops, Please fill all fields.");
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>viewmystaffprofile/changePassword/",
        data: ({
          oldPassword: oldPassword,
          newPassword:newPassword
        }),
        cache: false,
        beforeSend: function() {
          $('.infoPasswordChanged').html( '<span class="text-info">Updating Password...</span>');
        },
        success: function(html){
          $('#changePassword')[0].reset();
          $('.infoPasswordChanged').html(html);
        }
      });
    }
  });
  </script>
<script>
    $(document).ready(function() {  
    function unseen_notification(view = '') { 
            $.ajax({
                url: "<?php echo base_url() ?>fetch_unseen_notification/",
                method: "POST",
                data: ({
                    view: view
                }),
                dataType: "json",
                success: function(data) {
                    $('.notification-show').html(data.notification);
                    if (data.unseen_notification > 0) {
                        $('.count-new-notification').html(data.unseen_notification);
                    }
                }
            });
        }  
        function inbox_unseen_notification(view = '') { 
            $.ajax({
                url: "<?php echo base_url() ?>fetch_unseen_message_notification/",
                method: "POST",
                data: ({
                    view: view
                }),
                dataType: "json",
                success: function(data) {
                    $('.inbox-show').html(data.notification);
                    if (data.unseen_notification > 0) {
                        $('.count-new-inbox').html(data.unseen_notification);
                    }
                }
            });
        }
        unseen_notification();
        inbox_unseen_notification();
        $(document).on('click', '.seen_noti', function() {
            $('.count-new-notification').html('');
            inbox_unseen_notification('yes');
        });
        $(document).on('click', '.seen', function() {
            $('.count-new-inbox').html('');
            inbox_unseen_notification('yes');
        });
        setInterval(function() {
          unseen_notification();
          inbox_unseen_notification();
        }, 5000);

    });
    </script>
</html>