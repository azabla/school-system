<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php include('bgcolor.php'); ?>
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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
 <link rel='shortcut icon' type='image/x-icon'
   href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <style type="text/css">
    body {
      background-image: url('<?php echo base_url(); ?>/wallpapers/<?php echo $bgid;?>');
    }
  </style>
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
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <div class="row">
                      <div class="col-md-12">
                        <h5 class="card-title">Themes</h5>
                      </div>
                    </div>
                  </div>
                  <div class="card-body table-responsive" style="height:60vh;">
                    <form method="POST" action="<?php echo base_url(); ?>themes/saveThemes/">
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="hover_to_deletegallery"> 
                          <a href="#" data-sub-html="">
                            <img class="img-responsive" 
                            src="<?php echo base_url(); ?>/wallpapers/1.jpg" alt="" style="height:240px;width:100%">
                          </a>
                          <div class="table-links">
                          <button type="submit" name="setasbg" class="btn btn-primary" value="1.jpg">Set as background 
                          </button>
                         </div>
                      </div>
                    </div> 
                    <div class="dropdown-divider"></div>

                    <div class="col-lg-3">
                      <div class="hover_to_deletegallery"> 
                          <a href="#" data-sub-html="">
                            <img class="img-responsive" 
                            src="<?php echo base_url(); ?>/wallpapers/2.jpg" alt="" style="height:240px;width:100%">
                          </a>
                          <div class="table-links">
                          <button type="submit" name="setasbg" class="btn btn-primary" value="2.jpg">Set as background 
                          </button>
                         </div>
                      </div>
                    </div> 
                    <div class="dropdown-divider"></div>

                    <div class="col-lg-3">
                      <div class="hover_to_deletegallery"> 
                          <a href="#" data-sub-html="">
                            <img class="img-responsive" 
                            src="<?php echo base_url(); ?>/wallpapers/3.jpg" alt="" style="height:240px;width:100%">
                          </a>
                          <div class="table-links">
                          <button type="submit" name="setasbg" class="btn btn-primary" value="3.jpg">Set as background 
                          </button>
                         </div>
                      </div>
                    </div> 
                    <div class="dropdown-divider"></div>

                    <div class="col-lg-3">
                      <div class="hover_to_deletegallery"> 
                          <a href="#" data-sub-html="">
                            <img class="img-responsive" 
                            src="<?php echo base_url(); ?>/wallpapers/4.jpg" alt="" style="height:240px;width:100%">
                          </a>
                          <div class="table-links">
                          <button type="submit" name="setasbg" class="btn btn-primary" value="4.jpg">Set as background 
                          </button>
                         </div>
                      </div>
                    </div> 
                    <div class="dropdown-divider"></div>
                  </div>
                </form>
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
          <a href="https://www.grandstande.com" target="_blanck">Grandstand IT Solution Plc</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
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
  function codespeedy(){
    var print_div = document.getElementById("helloTranscript");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
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