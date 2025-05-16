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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/summernote-bs4.css"> 
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
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover" id="save-stage" style="width:100%;">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>User Type</th>
                            <th>Browser</th>
                            <th>IP Address</th>
                            <th>Platform</th>
                            <th>Logged At</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no =1;
                          foreach($loggeduser as $post){ ?>
                          <tr class="logouut<?php echo $post->logged_user; ?>">
                          <td><?php  echo $no;?>.</td>
                          <td><?php echo $post->fname; echo ' ';echo $post->mname;?></td>
                          <td><?php echo $post->usertype; ?></td>
                          <td><?php echo $post->browser . ' - ' . $post->bversion; ?></td>
                          <td><?php echo $post->platform; ?></td>
                          <td><?php echo $post->ipaddress;?></td>
                          <td><?php echo $post->dateime; ?></td>
                          <td>
                            <button class="btn btn-default logit" name="<?php echo $post->browser;?>" id="<?php echo $post->ipaddress;?>" value="<?php echo $post->logged_user;?>">
                              <span class="text-danger"><i class="fas fa-sign-out-alt"></i></span>
                            </button>                   
                          </td>
                          </tr>
                           <?php $no++; } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
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
  <script src="<?php echo base_url(); ?>assets/pages/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</body>
<script type="text/javascript">
  $(document).on('click', '.logit', function() {
    var ipaddress=$(this).attr("id");
    var meuser=$(this).attr("value");
    var browser=$(this).attr("name");
    $.ajax({
      url: "<?php echo base_url(); ?>Logout_mysession/",
      method: "POST",
      data: ({
        ipaddress: ipaddress,
        meuser: meuser,
        browser:browser
      }),
      dataType:"json",
      success: function(data) {
       $(".logouut" + meuser).fadeOut('slow');
      }
    });
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