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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/date/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
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
              <div class="col-12">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                  <ul class="nav nav-tabs" id="myTab2" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#feedStuAttendance" role="tab" aria-selected="true">Feed Attendance</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab3" data-toggle="tab" href="#fetchAttendance" role="tab" aria-selected="false">View Attendance</a>
                    </li>
                  </ul>
                  <div class="dropdown-divider"></div>
                  <div class="tab-content tab-bordered" id="myTab3Content">
                    <div class="tab-pane fade show active" id="feedStuAttendance" role="tabpanel" aria-labelledby="home-tab2">
                      <div class="row">
                        <div class="col-md-4 col-6">
                          <div class="form-group">
                            <select class="form-control" required="required" name="gradesec"  id="gradesec">
                            <option>--- Select Grade ---</option>
                            <?php if($num_rows==1){
                            foreach($gradesec as $gradesecs){ ?>
                              <option value="<?php echo $gradesecs->gradesec;?>">
                              <?php echo $gradesecs->gradesec;?>
                              </option>
                            <?php } } else{
                              foreach($gradesecs_gs as $gradesecss){ ?>
                              <option value="<?php echo $gradesecss->roomgrade;?>">
                              <?php echo $gradesecss->roomgrade;?>
                              </option> 
                            <?php } }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4 col-6">
                          <div class="form-group">
                            <input class="form-control datepicker" name="attendanceDateTeacher" id="attendanceDateTeacher" required="required" type="date">
                          </div>
                        </div>
                        <div class="col-md-4 col-12">
                          <button class="btn btn-info btn-block" id="fetchStu"> View</button> 
                        </div>
                      </div>
                   
                    <div class="studentList"> </div>
                    </div>
                    <div class="tab-pane fade show" id="fetchAttendance" role="tabpanel" aria-labelledby="home-tab3">
                      <div class="row">
                        <div class ="col-lg-6 col-8"> 
                          <input type="text" class="form-control" id="searchAttendanceReport" name="searchAttendance" placeholder="Search attendance report here..." > 
                        </div>
                        <div class ="col-lg-6 col-4">
                          <button class="btn btn-primary pull-right" name="gethisreport" onclick="codespeedy()">  <i class="fas fa-print"></i>  print   
                          </button>
                        </div>
                      </div>
                      <div class="AttendanceReport" id="AttendanceReport"> </div>
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
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  $(document).on('click', "input[name='agreedToAttendance_activity_Teacher']", function() {
      var agreed_date = $(this).attr("value");
      var user_id = $(this).attr("id");
      var gradesec = $(this).attr("title");
      if($(this).is(':checked')){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>mystudentattendance/sign_agreement/",
          data: ({
            agreed_date:agreed_date,
            user_id:user_id,
            gradesec:gradesec
          }),
          cache: false,
          success: function(html) {
            $(".agreedToAttendance_activity_Teacher"+agreed_date).prop('disabled', true);
            iziToast.success({
              title: 'Signed Successfully',
              message: '',
              position: 'topRight'
            });
          }
        });
      }
    });
  function codespeedy(){
    var print_div = document.getElementById("AttendanceReport");
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
<script type="text/javascript">
  $(document).on('keyup', '#searchAttendanceReport', function() { 
    $searchItem=$('#searchAttendanceReport').val();
    if($('#searchAttendanceReport').val()==''){
      $.ajax({
        url:"<?php echo base_url(); ?>mystudentattendance/fetchStuAttendance/",
        method:"POST",
        beforeSend: function() {
          $('.AttendanceReport').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.AttendanceReport').html(data);
        }
      })
    }else{
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mystudentattendance/searchAttendance/",
        data: "searchItem=" + $("#searchAttendanceReport").val(),
        beforeSend: function() {
          $('.AttendanceReport').html( 'Searching<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".AttendanceReport").html(data);
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
        url:"<?php echo base_url(); ?>mystudentattendance/fetchStuAttendance/",
        method:"POST",
        beforeSend: function() {
          $('.AttendanceReport').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.AttendanceReport').html(data);
        }
      });
    }
    $(document).on('click', '.attendanceTypeTeacher', function() {
    var stuid=$(this).attr("value");
    var id=$(this).attr("id");
    var attendanceType=$(this).attr("title");
    var attendanceDate=$('#attendanceDateTeacher').val();
    $.ajax({
      url: "<?php echo base_url(); ?>mystudentattendance/saveAttendance/",
      method: "POST",
      data: ({
        stuid: stuid,
        attendanceDate:attendanceDate,
        attendanceType:attendanceType
      }),
      dataType:"json",
      success: function(data) {
        if(data.notification==1){
          iziToast.success({
            title: 'Attendance submitted successfully',
            message: '',
            position: 'topRight'
          });
          loadStaffsAttendance();
        }else if(data.notification==2){
          iziToast.error({
            title: 'Oooops Something wrong. Please try again',
            message: '',
            position: 'topRight'
          });
        }else if(data.notification==3){
          iziToast.success({
            title: 'Attendance updated successfully',
            message: '',
            position: 'topRight'
          });
          loadStaffsAttendance();
        }
        else if(data.notification==4){
          iziToast.error({
            title: 'Oooops Something wrong. Please try again',
            message: '',
            position: 'topRight'
          });
        }else{
          iziToast.error({
            title: 'Oooops Please try again',
            message: '',
            position: 'topRight'
          });
        }        
      }
    });
  });
    
    $(document).on('click', '.deleteStuAttendance', function() {
      var attendanceId = $(this).attr("value");
      var absentDate = $(this).attr("id");
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
            url: "<?php echo base_url(); ?>mystudentattendance/removeAttendance/",
            data: ({
              attendanceId: attendanceId,
              absentDate:absentDate
            }),
            cache: false,
            success: function(html) {
              loadStaffsAttendance();
            }
          });
        }
      });
    });
  });
</script>
<script type="text/javascript">

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
<script type="text/javascript">
  $(document).ready(function() {  
    $("#fetchStu").on("click", function() {
      var fetchDate=$('#attendanceDateTeacher').val();
      var gradesec=$("#gradesec").val();
      if($('#attendanceDateTeacher').val()!==''){
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>mystudentattendance/filterGradesecForTeachers/",
          data:({
            gradesec:gradesec,
            fetchDate:fetchDate
          }) ,
          beforeSend: function() {
            $('.studentList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success: function(data) {
            $(".studentList").html(data);
          }
        });
      }
      else{
        swal({
          title: 'Please select all necessary fields.',
          text: '',
          icon: 'error',
          buttons: true,
          dangerMode: true,
        })
      }
    });
  });
</script>
<script>
  $(document).ready(function() {
    function fetchNewAttendance(view = '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Mystudentattendance/attendanceNotification/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType:"json",
        success: function(data) {
          $('.mark-show').html(data.notification);
          if (data.unseen_notification > 0) {
            $('.count-new-mark').html(data.unseen_notification);
          }
        }
      });
    }  
    fetchNewAttendance();
    setInterval(function() {
      fetchNewAttendance();
    }, 5000);

  });
</script>
</html>