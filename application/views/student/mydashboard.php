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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel='shortcut icon' type='image/x-icon'
   href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
</head>

<body>
  <div class="loader"><div class="loaderIcon"></div></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <?php include('myheaderdashboard.php'); ?>
      <div class="main-contentDashboard">
        <section class="section">
          <?php include('bgcolor.php'); ?>
          <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
          <div class="card">
            <div class="card-header"> 
              <h5 class="font-25" id="ENS" > <a href="<?php echo base_url(); ?>home/?student-home-page/" class="btn btn-outline-primary"><i data-feather="home"></i>Back To Home </a></h5>
            </div>
          </div>
          <div class="row">
            <div class="col-xl-6 col-lg-6 col-12">
              <div class="card">
                <div class="card-header">
                  <select class="form-control" name="selectQuarter" id="selectQuarter">
                    <option> Select Term/Quarter</option>
                    <?php foreach($fetch_term as $fetch_terms) { ?>
                      <option value="<?php echo $fetch_terms->term ?>"><?php echo $fetch_terms->term ?></option>
                    <?php }?>
                  </select>
              </div>
                <span id="myMarkResult"></span>
              </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-12">
              <div class="card">
                <div class="card-header">
                  <h4>My Attendance</h4>
                </div>
                <span id="myAttendanceData"></span>
              </div>
            </div>                    
          </div>
          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
              <div class="text-center" id="ENS" style="margin-top: 150px;">
                <?php include('footer.php'); ?>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/chart/chart.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#selectQuarter").bind("change", function() {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>mydashboard/filter_grade4branchplacement/",
          data: "selectQuarter=" + $("#selectQuarter").val(),
          beforeSend: function() {
            $('#myMarkResult').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="54" height="54" id="loa">'
              );
          },
          success: function(data) {
            $("#myMarkResult").html(data);
          }
        });
      });
    });
  </script>
  <script type="text/javascript">
  $(document).ready(function(){
    loadAttendanceData();
    loadMarkData();
    function loadAttendanceData()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>mydashboard/fetchMyAttendance/",
        method:"POST",
        beforeSend: function() {
          $('#myAttendanceData').html( 'Loading Attendance...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success:function(data){
          $('#myAttendanceData').html(data);
        }
      })
    }
    function loadMarkData()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>mydashboard/fetchMyMark/",
        method:"POST",
        beforeSend: function() {
          $('#myMarkResult').html( 'Loading Mark...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success:function(data){
          $('#myMarkResult').html(data);
        }
      })
    }
  });
</script>
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
      $("body").removeClass("dark");
      $("body").removeClass("dark-sidebar");
      $("body").removeClass("theme-black");
      $("body").addClass("light");
      $("body").addClass("light-sidebar");
      $("body").addClass("theme-white");
    } else {
      $("body").removeClass("light");
      $("body").removeClass("light-sidebar");
      $("body").removeClass("theme-white");
      $("body").addClass("dark");
      $("body").addClass("dark-sidebar");
      $("body").addClass("theme-black");
    }
  });
</script>
<script type="text/javascript" language="javascript"> 
  var bgcolor_now=document.getElementById("bgcolor_now").value;
  if (bgcolor_now == "1") {
    $("body").removeClass("dark");
    $("body").removeClass("dark-sidebar");
    $("body").removeClass("theme-black");
    $("body").addClass("light");
    $("body").addClass("light-sidebar");
    $("body").addClass("theme-white");
  }else {
    $("body").removeClass("light");
    $("body").removeClass("light-sidebar");
    $("body").removeClass("theme-white");
    $("body").addClass("dark");
    $("body").addClass("dark-sidebar");
    $("body").addClass("theme-black"); 
  } 
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
         $('.notification-show-mark').html(data.notification);
          if (data.unseen_notification > 0) {
            $('.count-new-notification').html(data.unseen_notification);
          }
        }
      });
    } 
    function myseen_attendance(view = '') {
      $.ajax({
        url: "<?php echo base_url() ?>my_unseen_attendance/",
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
    myseen_attendance();
    inbox_unseen_notification();
    $(document).on('click', '.seen_noti', function() {
      $('.count-new-notification').html('');
      myseen_attendance('yes');
      my_mark_status('yes');
    });
    $(document).on('click', '.seen', function() {
      $('.count-new-inbox').html('');
      inbox_unseen_notification('yes');
    });
    setInterval(function() {
      my_mark_status();
      myseen_attendance();
      inbox_unseen_notification();
    }, 5000);
  });
</script>
</body>

</html>