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
            <div class="card">
              <ul class="nav nav-tabs" id="myTab2" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#feedStaffAttendance" role="tab" aria-selected="true"> Manage Attendance</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="home-tab2" data-toggle="tab" href="#fetchAttendanceReport" role="tab" aria-selected="false">Attendance Report</a>
                </li>
              </ul>
              <div class="tab-content tab-bordered" id="myTab3Content">
                <div class="tab-pane fade show active" id="feedStaffAttendance" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="fetchStaffHH"></div>
                </div>
                <div class="tab-pane fade show" id="fetchAttendanceReport" role="tabpanel" aria-labelledby="home-tab2">
                  <div class="fetchStaffsAttendanceHere"></div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function() {
    loadStaffs();
    function loadStaffs(){
      $.ajax({
        url:"<?php echo base_url(); ?>mystaffattendance/fetchStaffsToAttendance/",
        method:"POST",
        beforeSend: function() {
          $('.fetchStaffHH').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success:function(data){
          $('.fetchStaffHH').html(data);
        }
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    loadStaffsAttendance();
    function loadStaffsAttendance(){
      $.ajax({
        url:"<?php echo base_url(); ?>mystaffattendance/fetchStaffsAttendance/",
        method:"POST",
        beforeSend: function() {
          $('.fetchStaffsAttendanceHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success:function(data){
          $('.fetchStaffsAttendanceHere').html(data);
        }
      });
    }
    $(document).on('click', '#absentStaff', function() {
      var staffId = $(this).attr("value");
      var dateAbsent=$('#todayDate').val();
      if ($('#todayDate').val()!=='') {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>mystaffattendance/absentAttendance/",
          data: ({
            staffId: staffId,
            dateAbsent:dateAbsent
          }),
          cache: false,
          success: function(html) {
            $(".atteInfo").html(html);
            loadStaffsAttendance();
            swal({
              title: 'Attendance submitted successfully.',
              text: '',
              icon: 'success',
              buttons: true,
              dangerMode: true,
            })
          }
        });
      }else {
        swal({
          title: 'Please select date to feed attendance.',
          text: '',
          icon: 'error',
          buttons: true,
          dangerMode: true,
        })
      }
    });
    $(document).on('click', '#lateStaff', function() {
      var staffId = $(this).attr("value");
      var dateAbsent=$('#todayDate').val();
      var lateMin=$('#lateMinute').val();
      if ($('#todayDate').val()!=='' && $('#lateMinute').val()!=='') {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>mystaffattendance/lateAttendance/",
          data: ({
            staffId: staffId,
            dateAbsent:dateAbsent,
            lateMin:lateMin
          }),
          cache: false,
          success: function(html) {
            $(".atteInfo").html(html);
            loadStaffsAttendance();
            swal({
              title: 'Attendance submitted successfully.',
              text: '',
              icon: 'success',
              buttons: true,
              dangerMode: true,
            })
          }
        });
      }else {
        swal({
          title: 'Please select all necessary fields to feed attendance.',
          text: '',
          icon: 'error',
          buttons: true,
          dangerMode: true,
        })
      }
    });
    $(document).on('click', '#permissionStaff', function() {
      var staffId = $(this).attr("value");
      var dateAbsent=$('#todayDate').val();
      if ($('#todayDate').val()!=='') {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>mystaffattendance/permissionAttendance/",
          data: ({
            staffId: staffId,
            dateAbsent:dateAbsent
          }),
          cache: false,
          success: function(html) {
            $(".atteInfo").html(html);
            loadStaffsAttendance();
            swal({
              title: 'Attendance submitted successfully.',
              text: '',
              icon: 'success',
              buttons: true,
              dangerMode: true,
            })
          }
        });
      }else {
        swal({
          title: 'Please select date to feed attendance.',
          text: '',
          icon: 'error',
          buttons: true,
          dangerMode: true,
        })
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '.deleteAttendance', function() {
    var staffId = $(this).attr("value");
    swal({
      title: 'Are you sure to delete this attendance?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>mystaffattendance/deleteAttendance/",
          data: ({
            staffId: staffId
          }),
          cache: false,
          success: function(html) {
            $(".delete_staff" + staffId).fadeOut('slow');
          }
        });
      }
    });
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