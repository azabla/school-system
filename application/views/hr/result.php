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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/summernote-bs4.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/selectric.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <!-- Custom style CSS -->
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
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="boxs mail_listing">
                    <div class="inbox-body no-pad">
                      <?php $no =1;foreach($exam as $read_mores){ $ans1=$read_mores->answer; 
                        $ans2=$read_mores->ans;?>
                      <section class="mail-list">
                        <hr>
                        <div class="view-mail">
                        <p>
                          <small class="text-muted">Q<?php echo $no;?>.</small>
                          <?php echo $read_mores->question; ?>
                        </p>
                        </div>
                        <div class="row">
                        <div class="col-lg-3">
                        <p>A.<?php echo $read_mores->a; ?>
                        </p>
                       </div>
                       <div class="col-lg-3">
                        <p>B.<?php echo $read_mores->b; ?>
                        </p>
                       </div>
                       <div class="col-lg-3">
                        <p>C.<?php echo $read_mores->c; ?>
                        </p>
                       </div>
                       <div class="col-lg-3">
                        <p>D.<?php echo $read_mores->d; ?>
                        </p>
                       </div>
                       <div class="col-lg-12">
                       <div class="form-group">
                        <?php if(trim($ans2)==trim($ans1)){ ?>
                        <span class="text-success">
                        <i data-feather="check"></i>
                          <?php echo $read_mores->answer; ?>
                        </span>
                        <?php } else{?>
                          <span class="text-danger">
                            <p>Your answer was <?php echo $read_mores->ans; ?><i data-feather="x"></i> </p>
                           The correct answer is <b><?php echo $read_mores->answer; ?></b>
                        </span>
                        <?php }?>
                        </div>
                      </div>
                      </div>
                    </section>
                  <?php $no++; }?>
                  
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
  <!-- Custom JS File -->
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
  <script src="<?php echo base_url(); ?>assets/pages/jquery.selectric.min.js"></script>
</body>

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