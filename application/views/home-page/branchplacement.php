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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
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
                  <div class="card-body StudentViewTextInfo">
                    <h5 class="card-header">Branch Placement</h5>
                    <div class="row">
                      <div class="col-lg-6 col-6">
                        <div class="form-group">
                          <label for="Mobile">Academic Year </label>
                          <select class="form-control selectric" required="required" name="academicyear" id="academicyear">
                             <?php foreach ($academicyear as $kevalue) { ?>
                              <option><?php echo $kevalue->year_name; ?></option>
                              <?php  } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-6 col-6">
                        <div class="form-group">
                          <label for="Mobile">Select Grade</label>
                          <select class="form-control selectric"
                             required="required" name="grade2place" id="grade2place">
                             <option></option>
                            <?php foreach ($grade as $grades) { ?>
                             <option value="<?php echo $grades->grade;  ?>"><?php echo $grades->grade; ?></option>
                            <?php  } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="listBranchPlacement"> </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
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
  $(document).ready(function() {
    $("#grade2place").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Branchplacement/filter_grade4branchplacement/",
        data: "grade2place=" + $("#grade2place").val(),
        beforeSend: function() {
          $('.listBranchPlacement').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".listBranchPlacement").html(data);
        }
      });
    });
  });
</script>

<script type="text/javascript">
  $(document).on('click', '.placesiec', function() {
    var stu_id=$(this).attr("id");
    var section_id=$(this).attr("value");
    $.ajax({
      url: "<?php echo base_url(); ?>Branchplacement/updateBranch/",
      method: "POST",
      data: ({
        stu_id: stu_id,
        section_id: section_id
      }),
      beforeSend: function() {
        $('.saved').html( '<img src="<?php echo base_url() ?>loader/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      dataType:"json",
      success: function(data) {
        $('.saved' + stu_id + section_id).html(data.notification);
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