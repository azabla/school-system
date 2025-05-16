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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
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
              <div class="col-lg-12">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <h4>Export excel registration format</h4>
                    <div class="row">
                      <div class="col-md-3 col-6">
                        <div class="form-group">
                        <form action="<?php echo base_url()?>Export/staff_formate" enctype="multipart/form-data" method="POST">
                        <button class="card card-body bg-info btn-block" name="exportstaff"><i class="fas fa-users"></i> Export Staff</button>
                        </form>
                        </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group">
                        <form action="<?php echo base_url()?>Export/student_formate" enctype="multipart/form-data" method="POST">
                        <button class="card card-body bg-warning btn-block" name="exportstudent"><i class="fas fa-users"></i> Export Regular Student</button>
                        </form>
                        </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group">
                        <form action="<?php echo base_url()?>Export/subject_formate" enctype="multipart/form-data" method="POST">
                        <button class="card card-body bg-primary btn-block" name="exportsubject"><i class="fas fa-book-open"></i> Export Subject</button>
                        </form>
                      </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group">
                        <form action="<?php echo base_url()?>Export/evaluation_formate" enctype="multipart/form-data" method="POST">
                        <button class="card card-body bg-success btn-block" name="exportevaluation"><i class="fas fa-check-circle"></i> Export Evaluation</button>
                        </form>
                      </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group">
                          <form action="<?php echo base_url()?>Export/attendance_formate" enctype="multipart/form-data" method="POST">
                          <button class="card card-body bg-secondary btn-block" name="exportattendance"><i class="fas fa-check"></i> Export Attendance</button>
                          </form>
                        </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group">
                          <form action="<?php echo base_url()?>Export/transportService" enctype="multipart/form-data" method="POST">
                          <button class="card card-body bg-danger btn-block" name="exportransportservice"><i class="fas fa-check"></i> Export Transport Service</button>
                          </form>
                        </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group">
                          <form action="<?php echo base_url()?>Export/mobileupdate" enctype="multipart/form-data" method="POST">
                          <button class="card card-body bg-success btn-block" name="mobileupdateformat"><i class="fas fa-check"></i> Export Mobile Update</button>
                          </form>
                        </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group">
                          <form action="<?php echo base_url()?>Export/emailupdate" enctype="multipart/form-data" method="POST">
                          <button class="card card-body bg-secondary btn-block" name="emailupdateformat"><i class="fas fa-check"></i> Export Email Update</button>
                          </form>
                        </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group">
                          <form action="<?php echo base_url()?>Export/staffpayroll" enctype="multipart/form-data" method="POST">
                          <button class="card card-body bg-light btn-block" name="staffpayrollupdateformat"><i class="fas fa-check"></i> Export Staff Payroll</button>
                          </form>
                        </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group">
                        <form action="<?php echo base_url()?>Export/exportRemotestudent" enctype="multipart/form-data" method="POST">
                        <button class="card card-body bg-primary btn-block" name="exportRemotestudent"><i class="fas fa-users"></i> Export Non-regular Student</button>
                        </form>
                        </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group">
                        <form action="<?php echo base_url()?>Export/exportBookRegistration" enctype="multipart/form-data" method="POST">
                        <button class="card card-body bg-info btn-block" name="exportBookRegistration"><i class="fas fa-book-open"></i>Book Registration Form</button>
                        </form>
                        </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group">
                        <form action="<?php echo base_url()?>Export/exportInventoryRegistration" enctype="multipart/form-data" method="POST">
                        <button class="card card-body bg-warning btn-block" name="exportInventoryRegistration"><i class="fas fa-book-open"></i>Inventory Registration Form</button>
                        </form>
                        </div>
                      </div>
                    </div>
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
    $('.deletestudent').click(function() {
      var post_id = $(this).attr("id");
      if (confirm("Are you sure you want to delete this student permantly ?")) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>student/",
          data: ({
            post_id: post_id
          }),
          cache: false,
          success: function(html) {
            $(".delete_mem" + post_id).fadeOut('slow');
          }
        });
      }else {
        return false;
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#savenewstudent', function() {
    var username=$("#username").val();
    var fname=$("#fname").val();
    var lname=$("#lname").val();
    var gfname=$("#gfname").val();
    var gender=$("#gender").val();
    var profile=$("#profile").val();
    var usertype=$("#usertype").val();
    var mobile=$("#mobile").val();
    var email=$("#email").val();
    var grade=$("#grade").val();
    var branch=$("#branch").val();
    var sec=$("#sec").val();
    var dob=$("#dob").val();
    var city=$("#city").val();
    var subcity=$("#subcity").val();
    var woreda=$("#woreda").val();
    var password=$("#password").val();
    var password2=$("#password2").val();
    var academicyear=$("#academicyear").val();
    if ($('#fname').val() != '' && $('#username').val() != '' && $('#lname').val() != '' && $('#gfname').val() != '' && $('#grade').val() != '' && $('#sec').val() != ''  && $('#password').val() != ''  && $('#password2').val() != '') {
      if($('#password').val()==$('#password2').val()){
      $.ajax({
        url: "<?php echo base_url(); ?>register_new_student/",
        method: "POST",
        data: ({
          username: username, fname: fname, 
          lname: lname, gfname: gfname,gender: gender,
          usertype: usertype,branch: branch,
          profile: profile, mobile: mobile,
          email: email,grade: grade, sec: sec,dob: dob,
          city: city, subcity: subcity,woreda: woreda,
          password: password, password2: password2,
          academicyear: academicyear,
        }),
        cache: false,
        beforeSend: function() {
          $('#msg').html( '<img src="<?php echo base_url() ?>loader/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(html) {
          $('#msg').html(html);
        }
      });
    }else{
      alert('Password does not match');
    }
    }else{
      alert('Please fill all fields');
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