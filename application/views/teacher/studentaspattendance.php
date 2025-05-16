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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
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
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#feedaspAttendance" role="tab" aria-selected="true">Feed ASP Attendance</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#fetchASPAttendance" role="tab" aria-selected="false">ASP Attendance Report</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="feedaspAttendance" role="tabpanel" aria-labelledby="home-tab1">
                        <form method="POST" id="comment_form">
                          <div class="row">
                            <div class="col-lg-3 col-6">
                             <select class="form-control gradesec_list_gs_aspT" required="required" name="gradesec_list_gs_aspT" id="gradesec_list_gs_aspT">
                              <option>---Grade---</option>
                              <?php if($_SESSION['usertype']===trim('Director')){
                                foreach($gradesec as $gradesecs){ ?>
                                  <option value="<?php echo $gradesecs->grade;?>"><?php echo $gradesecs->grade;?></option>
                                <?php } }else{ 
                                  foreach($gradesecTeacher as $gradesecs){ ?>
                                     <option value="<?php echo $gradesecs->grade;?>"><?php echo $gradesecs->grade;?></option>
                                 <?php } } ?>
                            </select>
                            </div>
                            <div class="col-lg-3 col-6">
                             <select class="form-control asp_program" required="required" name="asp_program" id="asp_program">
                              <option>---Program---</option>
                         
                            </select>
                            </div> 
                            <div class="col-lg-3 col-6">
                              <input class="form-control datepicker" name="aspdatet" id="asp_attendanceDate" required="required" type="date" 
                                  placeholder="Absent Date">
                            </div> 
                             <div class="col-lg-3 col-6">
                              <button class="btn btn-primary btn-block" 
                              type="submit" name="viewmark">View Student</button>
                            </div>
                          </div>
                        </form>
                        <div class="studentList"> </div>
                      </div>
                      <div class="tab-pane fade show" id="fetchASPAttendance" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="card-header ">
                          <div class="row">
                            <div class ="col-lg-10 col-8"> 
                              <input type="text" class="form-control" id="searchAttendanceReport" name="searchAttendance" placeholder="Search attendance report here..." > 
                            </div>
                            <div class ="col-lg-2 col-4">
                              <button class="btn btn-primary btn-sm pull-right" name="gethisreport" onclick="codespeedy()">  <i class="fas fa-print"></i>  print   
                              </button>
                            </div>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="fetch_attendance" id="printAttendanceReport"></div>
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
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#gradesec_list_gs_aspT").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Studentaspattendance/Filter_asp_program_from_grade/",
        data: "branchit=" + $("#gradesec_list_gs_aspT").val(),
        beforeSend: function() {
          $('.asp_program').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".asp_program").html(data);
        }
      });
    });
  });
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var gradeSection=$('#gradesec_list_gs_aspT').val();
    var attendanceDate=$('#asp_attendanceDate').val();
    var attendanceProgram=$('#asp_program').val();
    if ($('#gradesec_list_gs_aspT').val()!='') {
      $.ajax({
        url: "<?php echo base_url(); ?>studentaspattendance/fetchStudents4_asp_Attendance/",
        method: "POST",
        data: ({
          gradeSection:gradeSection,
          attendanceDate:attendanceDate,
          attendanceProgram:attendanceProgram
        }),
        beforeSend: function() {
          $('.studentList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".studentList").html(data);
        }
      })
    }else {
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("printAttendanceReport");
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
        url:"<?php echo base_url(); ?>studentaspattendance/fetchasp_AttendanceReport/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_attendance').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success:function(data){
          $('.fetch_attendance').html(data);
        }
      })
    }else{
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>studentaspattendance/searchAttendance/",
        data: "searchItem=" + $("#searchAttendanceReport").val(),
        beforeSend: function() {
          $('.fetch_attendance').html( 'Searching...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(data) {
          $(".fetch_attendance").html(data);
        }
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>studentaspattendance/fetchasp_AttendanceReport/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_attendance').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.fetch_attendance').html(data);
        }
      })
    }
    $(document).on('click', '.aspattendanceStuid', function() {
      var attendanceDate =$('#asp_attendanceDate').val();
      var attendanceProgram=$('#asp_program').val();
      var attendanceMinute=$('#aspattendanceMinute').val();
      var stuid=$(this).attr("value");
      var attendanceType=$(this).attr("id");
      if($('#asp_attendanceDate').val()!='' && $('#asp_program').val()!=''){
        if($('#aspattendanceType').val()==='Late' || $('#aspattendanceType').val()==='Tardy'){
          if($('#aspattendanceMinute').val()!==''){
            $.ajax({
              method: "POST",
              url: "<?php echo base_url(); ?>studentaspattendance/saveAttendance/",
              data: ({
                stuid: stuid,
                attendanceDate:attendanceDate,
                attendanceType:attendanceType,
                attendanceMinute:attendanceMinute,
                attendanceProgram:attendanceProgram
              }),
              cache: false,
              success: function(html){
                load_data();
                iziToast.success({
                  title: 'Attendance',
                  message: 'Updated successfully',
                  position: 'topRight'
                });
              }
            });
          }else{
            swal('Please insert minute to late attendance.', {
              icon: 'error',
            });
          }
        }else{
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>studentaspattendance/saveAttendance/",
            data: ({
              stuid: stuid,
              attendanceDate:attendanceDate,
              attendanceType:attendanceType,
              attendanceMinute:attendanceMinute,
              attendanceProgram:attendanceProgram
            }),
            cache: false,
            success: function(html){
              load_data();
              iziToast.success({
                title: 'Attendance',
                message: 'Updated successfully',
                position: 'topRight'
              });
            }
          });
        }
      }else{
        swal('Please select necessary fields.', {
          icon: 'error',
        });
      }
    }); 
  });
</script>
<script type="text/javascript">
  $(document).on('click', '.deleteThisasp_Attendane', function() {
    var attendanceId = $(this).attr("id");
     swal({
        title: 'Are you sure you want to delete this Attendance ?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
    .then((willDelete) => {
      if (willDelete) {
        swal('Attendance deleted successfully!', {
          icon: 'success',
        });
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>studentaspattendance/deleteAttendance/",
          data: ({
            attendanceId: attendanceId
          }),
          cache: false,
          success: function(html) {
            $(".deleteaspAttendane" + attendanceId).fadeOut('slow');
          }
        });
      }else {
        return false;
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

</html>