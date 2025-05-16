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
  <!-- Template CSS -->
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
              <?php foreach($readlesson as $read_mores){ $pdf=$read_mores->note;
                if($pdf!=''){?>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="boxs mail_listing">
                    <div class="inbox-body no-pad">
                      <section class="mail-list">
                        <div class="mail-sender">
                          <div class="mail-heading">
                            <h4 class="vew-mail-header">
                              <b><?php echo $read_mores->title; ?></b>
                            </h4>
                          </div>
                          <hr>
                          <div class="media">
                            <a href="#" class="table-img m-r-15">
                              <img alt="image" src="<?php echo base_url(); ?>/profile/<?php echo $read_mores->profile;?>" class="rounded-circle" width="35"
                                data-toggle="tooltip" title="">
                            </a>
                            <div class="media-body">
                            <span class="date pull-right">
                              <?php echo $read_mores->lesson_date; ?>
                              </span>
                              <h5 class="text-primary"></h5>
                              <small class="text-muted">From: <?php echo $read_mores->fname; echo'&nbsp';
                              echo $read_mores->mname;?></small>
                            </div>
                          </div>
                        </div>
                        <div class="view-mail p-t-20">
                          <p>
                          <?php echo $read_mores->note; ?>
                          </p>
                        </div>
                      </section>
                    </div>
                  </div>
                </div>
              </div>
             <?php } else{ ?>
              <a href="<?php echo base_url().'Readlesson/download/'.$read_mores->pdfdoc; ?>" class="dwn">Download</a>
              <?php } }?>
            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
     <script src="<?php echo base_url(); ?>assets/pages/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
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