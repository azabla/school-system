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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/datatables.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/dataTables.bootstrap4.min.css">
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
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                   <form method="POST" id="comment_form">
                    <div class="row">
                      <div class="col-lg-2">
                         <div class="form-group">
                           <select class="form-control selectric"
                           required="required"
                            name="academicyear" 
                           id="academicyear">
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                              <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                           </select>
                          </div>
                         </div>
                        <div class="col-lg-4">
                         <div class="form-group">
                           <select class="form-control selectric"
                           required="required" name="quarter" 
                           id="miquarter">
                          <option>--- Select Quarter ---</option>
                            <?php foreach($fetch_term as $fetch_terms){ ?>
                              <option value="<?php echo $fetch_terms->term;?>">
                              <?php echo $fetch_terms->term;?>
                              </option>
                            <?php }?>
                           </select>
                          </div>
                         </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                          <select class="form-control selectric"
                           required="required" name="subject" 
                            id="misubject">
                            <option>--- Select Subject ---</option>
                            <option>All</option>
                            <?php foreach($subject as $subjects){ ?>
                              <option value="<?php echo $subjects->Subj_name;?>">
                              <?php echo $subjects->Subj_name;?>
                              </option>
                            <?php }?>
                           </select>
                          </div>
                         </div>
                       <div class="col-lg-2">
                        <button class="btn btn-info btn-md" 
                        type="submit" name="viewmark">View</button>
                      </div>
                    </div>
                    </form>
                    <div class="listmark" id="mark_view"></div>
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
          Copyright &copy<?php echo date('Y');?>
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
  <script src="<?php echo base_url(); ?>assets/pages/datatables.js"></script>
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
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var gs_subject=$('#misubject').val();
    var gs_quarter=$('#miquarter').val();
    if ($('#misubject').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Myresult/fecth_mymarkresult/",
        method: "POST",
        data: ({
          gs_subject:gs_subject,
          gs_quarter:gs_quarter
        }),
        beforeSend: function() {
          $('.listmark').html( 'Loading Mark...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(data) {
          $(".listmark").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<script>
  $(document).ready(function() {
    function my_mark_status(view = '') {
      $.ajax({
        url: "<?php echo base_url() ?>my_mark_status/",
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
    my_mark_status();
    inbox_unseen_notification();
    $(document).on('click', '.seen_noti', function() {
      $('.count-new-notification').html('');
      my_mark_status('yes');
    });
    $(document).on('click', '.seen', function() {
      $('.count-new-inbox').html('');
      inbox_unseen_notification('yes');
    });
    setInterval(function() {
      my_mark_status();
      inbox_unseen_notification();
    }, 5000);
  });
</script>
</html>