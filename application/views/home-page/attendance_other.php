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
                  <div class="card-header">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#feedSubject" role="tab" aria-selected="true"> Feed Attendance</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#feedMergedSubject" role="tab" aria-selected="false">Attendance Report</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#customAttendanceReport" role="tab" aria-selected="false">Custom Report</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab4" data-toggle="tab" href="#attendanceReportFormat" role="tab" aria-selected="false">Attendance Format</a>
                      </li>
                    </ul>
                  </div>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="feedSubject" role="tabpanel" aria-labelledby="home-tab1">
                        <form method="POST" id="comment_form">
                          <div class="row">
                           
                               <div class="col-lg-3 col-6">
                                <div class="form-group">
                                 <select class="form-control" required="required" name="branch" id="grands_branchit">
                                 <option>--- Branch ---</option>
                                  <?php foreach($branch as $branchs){ ?>
                                    <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                    </option>
                                  <?php }?>
                                 </select>
                                </div>
                               </div>
                               <div class="col-lg-3 col-6">
                                <div class="form-group">
                                 <select class="form-control grands_gradesec" required="required" name="gradesec" id="grands_gradesec">
                                 <option>--- Grade ---</option>
                                 </select>
                                </div>
                               </div>
                               <div class="col-lg-3 col-6">                                
                                <input class="form-control datepicker" name="attendanceDate" id="attendanceDate" required="required" type="date" placeholder="Absent Date">
                            </div>
                             <div class="col-lg-3 col-6">
                              <button class="card card-body bg-info btn-block" 
                              type="submit" name="viewmark">Load</button>
                            </div>
                          </div>
                        </form>
                        <div class="studentList"> </div>
                      </div>
                      <div class="tab-pane fade show" id="feedMergedSubject" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="card-header ">
                          <div class="row">
                            <div class ="col-lg-6 col-7"> 
                              <input type="text" class="form-control" id="searchAttendanceReport" name="searchAttendance" placeholder="Search attendance report here..." > 
                            </div>
                            <div class ="col-lg-6 col-5">
                              <button class="btn btn-primary btn-sm pull-right" name="gethisreport" onclick="codespeedy()">  <i class="fas fa-print"></i>  print   
                              </button>
                            </div>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="fetch_attendance" id="printAttendanceReport"></div>
                        </div>
                      </div>
                      <div class="tab-pane fade show" id="customAttendanceReport" role="tabpanel" aria-labelledby="home-tab3">
                        <form method="POST" id="customForm">
                          <div class="row">
                            <div class="col-lg-2 col-6">
                              <label>From Date</label>
                              <div class="form-group">
                              <input type="date" name="customFromDate" class="form-control" id="customFromDate">
                              </div>
                             </div>
                             <div class="col-lg-2 col-6">
                              <label>To Date</label>
                              <div class="form-group">
                              <input type="date" name="customToDate" class="form-control" id="customToDate">
                              </div>
                             </div>
                             <div class="col-lg-3 col-6">
                              <label>Branch</label>
                              <div class="form-group">
                                <select class="form-control" required="required" name="customBranch" id="customBranch">
                                <option>--- Branch ---</option>
                                <?php foreach($branch as $branchs){ ?>
                                  <option value="<?php echo $branchs->name;?>">
                                  <?php echo $branchs->name;?>
                                  </option>
                                <?php }?>
                                </select>
                              </div>
                              </div>
                             <div class="col-lg-3 col-6">
                              <label>Grade</label>
                              <div class="form-group">
                                <div class="customGradesec table-responsive" style="height:15vh"></div>
                              </div>
                             </div>
                             
                           <div class="col-lg-2 col-12">
                            <label><button class="btn btn-default btn-sm pull-right" name="gethisreport" onclick="codespeedyCustom()">  <i class="fas fa-print"></i>  print   </button></label>
                            <button class="btn btn-primary btn-lg btn-block" 
                            type="submit" name="viewCustomAttendance">View</button>
                          </div>
                        </div>
                      </form>
                      <div class="customStudentList" id="customStudentList"> </div>
                    </div>
                    <div class="tab-pane fade show" id="attendanceReportFormat" role="tabpanel" aria-labelledby="home-tab4">
                      <form method="POST" id="attendanceFormat">
                        <div class="row">
                          <div class="col-lg-2 col-6">
                             <div class="form-group">
                               <select class="form-control selectric" required="required" name="academicyearattendanceFormat" id="academicyearattendanceFormat">
                                <?php foreach($academicyear as $academicyears){ ?>
                                  <option value="<?php echo $academicyears->year_name;?>">
                                  <?php echo $academicyears->year_name;?>
                                  </option>
                                <?php }?>
                               </select>
                              </div>
                             </div>
                             <div class="col-lg-3 col-6">
                              <div class="form-group">
                               <select class="form-control" required="required" name="branchacademicyearattendanceFormat" id="branchacademicyearattendanceFormat">
                               <option>--- Branch ---</option>
                                <?php foreach($branch as $branchs){ ?>
                                  <option value="<?php echo $branchs->name;?>">
                                  <?php echo $branchs->name;?>
                                  </option>
                                <?php }?>
                               </select>
                              </div>
                             </div>
                             <div class="col-lg-3 col-6">
                              <div class="form-group">
                               <select class="form-control gradesecattendanceFormat" required="required" name="gradesecattendanceFormat" id="gradesecattendanceFormat">
                               <option>--- Grade ---</option>
                               </select>
                              </div>
                             </div>
                           <div class="col-lg-2 col-4">
                            <button class="btn btn-primary btn-lg btn-block" 
                            type="submit" name="viewmark">View</button>
                          </div>
                          <div class="col-lg-2 col-2">
                            <!-- <form method="POST" action="<?php echo base_url(); ?>attendance/downloadStuData/"> -->
                              <button type="submit" id="downloadStuData" name="downloadStuData" class="btn btn-success btn-sm pull-right"> Excel Data <i data-feather="download"></i>
                              </button>
                            <!-- </form> -->
                            <button class="btn btn-default btn-sm pull-right" name="gethisreport" onclick="codespeedyFormat()">  <i class="fas fa-print"></i>  print   </button>
                           <!--  <button id="getPDF">save div as pdf</button> -->
                          </div>
                        </div>
                      </form>
                      <div class="studentFormatList card-body" id="studentFormatList"> </div>
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
  <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js" integrity="sha512-w3u9q/DeneCSwUDjhiMNibTRh/1i/gScBVp2imNVAMCt6cUHIw6xzhzcPFIaL3Q1EbI2l+nu17q2aLJJLo4ZYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> 
</body>
<script type="text/javascript">
  $(document).ready(function () {
      $("#downloadStuData").click(function(){
        TableToExcel.convert(document.getElementById("studentFormatList"), {
            name: "Attendance-format.xlsx",
            sheet: {
            name: "Sheet1"
            }
          });
        });
  });
</script>
<script>
let htmlPDF = document.getElementById("studentFormatList");
let exportPDF = document.getElementById("getPDF");
exportPDF.onclick = (e) => html2pdf(htmlPDF);
</script>

<script type="text/javascript">
  $('#customForm').on('submit', function(event) {
    event.preventDefault();
    grade=[];
    $("input[name='studentServiceGrade']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    var attBranches=$('#customBranch').val();
    var customFromDate=$('#customFromDate').val();
    var customToDate=$('#customToDate').val();
    if(grade.length!=0){
      $.ajax({
        url: "<?php echo base_url(); ?>Attendance/fetchCustomStudentsAttendance/",
        method: "POST",
        data: ({
          attBranches: attBranches,
          grade:grade,
          customToDate:customToDate,
          customFromDate:customFromDate
        }),
        beforeSend: function() {
          $('.customStudentList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".customStudentList").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<script type="text/javascript">
  function codespeedyFormat(){
    var print_div = document.getElementById("studentFormatList");
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
  function codespeedyCustom(){
    var print_div = document.getElementById("customStudentList");
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
        url:"<?php echo base_url(); ?>attendance/fetchAttendanceReport/",
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
        url: "<?php echo base_url(); ?>attendance/searchAttendance/",
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
  $(document).on('click', '.deleteThisAttendane', function() {
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
          url: "<?php echo base_url(); ?>attendance/deleteAttendance/",
          data: ({
            attendanceId: attendanceId
          }),
          cache: false,
          success: function(html) {
            $(".deleteAttendane" + attendanceId).fadeOut('slow');
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
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_branchit").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#grands_branchit").val(),
        beforeSend: function() {
          $('.grands_gradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".grands_gradesec").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#customBranch").bind("change", function() {
      var branchit=$('#customBranch').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Attendance/Filter_grade_from_branch/",
        data: ({
            branchit: branchit
        }),
        beforeSend: function() {
          $('.customGradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".customGradesec").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branchacademicyearattendanceFormat").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#branchacademicyearattendanceFormat").val(),
        beforeSend: function() {
          $('.gradesecattendanceFormat').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".gradesecattendanceFormat").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var attBranches=$('#grands_branchit').val();
    var attGradesec=$('.grands_gradesec').val();
    var attendanceDate=$('#attendanceDate').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Attendance/fetchStudents4Attendance/",
        method: "POST",
        data: ({
          attBranches: attBranches,
          attGradesec:attGradesec,
          attendanceDate:attendanceDate
        }),
        beforeSend: function() {
          $('.studentList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".studentList").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<script type="text/javascript">
  load_data();
  function load_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>attendance/fetchAttendanceReport/",
      method:"POST",
      beforeSend: function() {
        $('.fetch_attendance').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
      },
      success:function(data){
        $('.fetch_attendance').html(data);
      }
    })
  }
  $(document).on('click', '.attendanceType', function() {
    var stuid=$(this).attr("value");
    var id=$(this).attr("id");
    var attendanceType=$(this).attr("title");
    var attendanceDate=$('#attendanceDate').val();
    if(attendanceType==='Late'){
      var attendanceMinute=document.getElementById('attendanceMinute_'+stuid).value;
      $.ajax({
        url: "<?php echo base_url(); ?>Attendance/saveAttendance/",
        method: "POST",
        data: ({
          stuid: stuid,
          attendanceDate:attendanceDate,
          attendanceType:attendanceType,
          attendanceMinute:attendanceMinute
        }),
        beforeSend: function() {
          $('.savedAttendance'+id+attendanceType).html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="15" height="15" id="loa">'
            );
        },
        dataType:"json",
        success: function(data) {
          load_data();
          $('.savedAttendance' +id+attendanceType).html('');
          iziToast.success({
            title: 'Attendance',
            message: 'Updated successfully',
            position: 'topRight'
          });
        }
      });
    }else{
      $.ajax({
        url: "<?php echo base_url(); ?>Attendance/saveAttendance/",
        method: "POST",
        data: ({
          stuid: stuid,
          attendanceDate:attendanceDate,
          attendanceType:attendanceType
        }),
        beforeSend: function() {
          $('.savedAttendance'+id+attendanceType).html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="15" height="15" id="loa">'
            );
        },
        dataType:"json",
        success: function(data) {
          load_data();
          $('.savedAttendance' +id+attendanceType).html('');
          iziToast.success({
            title: 'Attendance',
            message: 'Updated successfully',
            position: 'topRight'
          });
        }
      });
    }
  });
</script>
<script type="text/javascript">
  $('#attendanceFormat').on('submit', function(event) {
    event.preventDefault();
    var attBranches=$('#branchacademicyearattendanceFormat').val();
    var attGradesec=$('.gradesecattendanceFormat').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Attendance/fetchStudentsAttendanceFormat/",
        method: "POST",
        data: ({
          attBranches: attBranches,
          attGradesec:attGradesec
        }),
        beforeSend: function() {
          $('.studentFormatList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(data) {
          $(".studentFormatList").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<script>
  $(document).ready(function() {
    function fetchNewAttendance(view = '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Attendance/attendanceNotification/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType:"json",
        success: function(data) {
          $('.approve-mark-notification-show').html(data.notification);
          if (data.unseen_notification > 0) {
            $('.count-new-notification').html(data.unseen_notification);
          }
        }
      });
    }  
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
    fetchNewAttendance();
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
      fetchNewAttendance();
      unseen_notification();
      inbox_unseen_notification();
    }, 5000);

  });
</script>
</html>