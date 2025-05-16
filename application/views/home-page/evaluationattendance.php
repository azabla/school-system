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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
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
                <div class="row">
                  <div class="col-lg-12 col-12">
                    <button type="submit" id="add_supervision-staff" name="add_supervision-staff" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#add_staffs_to_supervision"> Add staffs to supervision <i class="fas fa-user-plus"></i> </button>
                  </div>
                </div>
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
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
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="feedSubject" role="tabpanel" aria-labelledby="home-tab1">
                        <form method="POST" id="comment_form">
                          <div class="row">
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control" required="required" name="branch" id="grands_branchit">
                                  <?php foreach($branch as $branchs){ ?>
                                    <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                    </option>
                                  <?php }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-3 col-6">                                
                              <input class="form-control datepicker" name="attendanceDate" id="attendanceDate" required="required" type="date" placeholder="Absent Date">
                            </div>
                            <div class="col-lg-3 col-6">
                              <select class="form-control" required="required" name="attendace_type" id="attendace_type">
                                <option>Morning</option>
                                <option>1<sup>st</sup> Period</option>
                                <option>Snack</option>
                                <option>3<sup>rd</sup> Period</option>
                                <option>Lunch</option>
                                <option>5<sup>th</sup> Period</option>
                                <option>Dismisal</option>
                              </select>                             
                            </div>
                            <div class="col-lg-3 col-6">
                              <button class="btn btn-info btn-block" 
                              type="submit" name="viewmark">Load Staffs</button>
                            </div>
                          </div>
                        </form>
                        <div class="studentList"> </div>
                      </div>
                      <div class="tab-pane fade show" id="feedMergedSubject" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row">
                          <div class ="col-lg-12 col-12">
                            <button class="btn btn-primary btn-sm pull-right" name="gethisreport" onclick="codespeedy()">  <i class="fas fa-print"></i>  print   
                            </button>
                          </div>
                        </div>
                        <div class="table-responsive" id="printAttendanceReport">
                          <table class="display dataTable" id='emp_attendanceTable' style="width:100%;">
                            <thead>
                             <tr>
                               <th>Staff Name</th>
                               <th>Attendance Type</th>
                               <th>Substituted By</th>
                               <th>Attendance Period</th>
                               <th>Attendance Date</th>
                               <th>Comment</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                      </div>
                      <div class="tab-pane fade show" id="customAttendanceReport" role="tabpanel" aria-labelledby="home-tab3">
                        <form method="POST" id="customForm">
                          <div class="row">
                            <div class="col-lg-2 col-6">
                              <label>Branch</label>
                              <div class="form-group">
                                <select class="form-control" required="required" name="customBranch" id="customBranch">
                                <?php foreach($branch as $branchs){ ?>
                                  <option value="<?php echo $branchs->name;?>">
                                  <?php echo $branchs->name;?>
                                  </option>
                                <?php }?>
                                </select>
                              </div>
                              </div>
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
                               <label>Attendance Period</label>
                              <select class="form-control" required="required" name="customattendace_type" id="customattendace_type">
                                <option>Morning</option>
                                <option>Snack</option>
                                <option>Lunch</option>
                                <option>Dismisal</option>
                              </select>                             
                            </div>                     
                           <div class="col-lg-3 col-12">
                            <button class="btn btn-primary btn-sm pull-right" name="gethisreport" onclick="codespeedyCustom()">  <i class="fas fa-print"></i>  print   </button>
                            <button class="btn btn-info btn-block" 
                            type="submit" name="viewCustomAttendance">View Report</button>
                          </div>
                        </div>
                      </form>
                      <div class="customStudentList" id="customStudentList"> </div>
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
  <div class="modal fade" id="add_staffs_to_supervision" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="library_head_book" id="library_head_book">Add staffs to supervision attendance</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="add_staffs_to_supervision"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add_comment_to_supervision" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="library_head_book" id="library_head_book">Add comment to supervision attendance</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="add_comment_to_supervision"></div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script> 
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function(){
    $('#emp_attendanceTable').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>staffevaluationattendance/fetchAttendanceReport/'
      },
      'columns': [
         { data: 'fname' },
         { data: 'absentype' },
         { data: 'substitute_by' },
         { data: 'attendance_period' },
         { data: 'absentdate' },
         { data: 'staff_comment' },
      ]
    });
  });
  $(document).on('submit', '#comment_staff_tosupervision', function(e) {
    e.preventDefault();
    if ($('#teacher_supervision_comment_gs').length != 0) {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>staffevaluationattendance/save_supervision_attendance_comment/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#savesupervision_attendance_comment').html( '<span class="text-info">Saving...</span>');
          $('#savesupervision_attendance_comment').attr( 'disabled','disabled');
          
        },
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Comment updated successfully',
              message: '',
              position: 'topRight'
            });
            $('#add_comment_to_supervision'). modal('hide');
          }else{
            iziToast.error({
              title: 'Ooops, please try again',
              message: '',
              position: 'topRight'
            });
          }
          $('#savesupervision_attendance_comment').html( 'Save Comment');
          $('#savesupervision_attendance_comment').removeAttr( 'disabled');
          $('#emp_attendanceTable').DataTable().ajax.reload();
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '.add_comment_tostaff_supervision', function()
  {
    var username=$(this).attr('value');
    var year=$(this).attr('id');
    var dateattendance=$(this).attr('name');
    var attendanceType=$(this).attr('data-type');
    $.ajax({
      url:"<?php echo base_url(); ?>staffevaluationattendance/add_comment_tostaff_supervision/",
      data: ({
        username: username,
        year:year,
        dateattendance:dateattendance,
        attendanceType:attendanceType
      }),
      method:"POST",
      beforeSend: function() {
        $('.add_comment_to_supervision').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.add_comment_to_supervision').html(data);
      }
    })
  });
  function load_supervision_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>staffevaluationattendance/fetch_supervision_staff_list/",
      method:"POST",
      beforeSend: function() {
        $('#fetch_supervision_staff_list').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('#fetch_supervision_staff_list').html(data);
      }
    })
  }
  $(document).on('click', '#removestaff_from_supervision_attendance', function(e) {
    e.preventDefault();
    var userid=$(this).attr("value");
    swal({
        title: 'Are you sure you want to remove this staff from supervision attendance ?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>staffevaluationattendance/removestaff_from_supervision_attendance/",
          data: ({
            userid: userid
          }),
          beforeSend: function() {
            $($.parseHTML('.removestaff_from_supervision_attendance' +userid)).html( '<span class="text-info">Removing...</span>');
            $('#removestaff_from_supervision_attendance').attr( 'disabled','disabled');
          },
          success: function(html){
            if(html=='1'){
              iziToast.success({
                title: 'Changes updated successfully',
                message: '',
                position: 'topRight'
              });
            }else{
              iziToast.error({
                title: 'Please try later',
                message: '',
                position: 'topRight'
              });
            }
            $($.parseHTML('.removestaff_from_supervision_attendance' +userid)).html( 'Remove');
            $('#removestaff_from_supervision_attendance').removeAttr( 'disabled');
            load_supervision_data();
          }
        });
      }
    });
  });
  $(document).on('submit', '#submitedited_staff_tosupervision', function(e) {
    e.preventDefault();
    if ($('#supervision_attendance_staff').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>staffevaluationattendance/save_supervision_attendance_staff/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#savesupervision_attendance_staff').html( '<span class="text-info">Saving...</span>');
          $('#savesupervision_attendance_staff').attr( 'disabled','disabled');
        },
        success: function(html){
          if(html==='1'){
            iziToast.success({
              title: 'Changes updated successfully',
              message: '',
              position: 'topRight'
            });
            load_supervision_data();
          }else if(html=='2'){
            iziToast.error({
              title: 'Oooops, user found.',
              message: '',
              position: 'topRight'
            });
          }else{
            iziToast.error({
              title: 'Ooops, please try again',
              message: '',
              position: 'topRight'
            });
          }
          $('#savesupervision_attendance_staff').html( 'Add Staff');
          $('#savesupervision_attendance_staff').removeAttr( 'disabled');
          
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '#add_supervision-staff', function()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>staffevaluationattendance/add_staffs_to_supervision/",
      method:"POST",
      beforeSend: function() {
        $('.add_staffs_to_supervision').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.add_staffs_to_supervision').html(data);
      }
    })
  });
  
  $('#customForm').on('submit', function(event) {
    event.preventDefault();
    var attBranches=$('#customBranch').val();
    var customFromDate=$('#customFromDate').val();
    var customToDate=$('#customToDate').val();
    var customattendace_type=$('#customattendace_type').val();
    if($('#customBranch').val()!='--- Branch ---'){
      $.ajax({
        url: "<?php echo base_url(); ?>Staffevaluationattendance/fetchCustomStudentsAttendance/",
        method: "POST",
        data: ({
          attBranches: attBranches,
          customToDate:customToDate,
          customFromDate:customFromDate,
          customattendace_type:customattendace_type
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
  $(document).on('click', '.deleteThisStaffEvaluationAttendane', function() {
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
          url: "<?php echo base_url(); ?>Staffevaluationattendance/deleteAttendance/",
          data: ({
            attendanceId: attendanceId
          }),
          cache: false,
          success: function(html) {
            $('#emp_attendanceTable').DataTable().ajax.reload();
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
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var attBranches=$('#grands_branchit').val();
    var attendanceDate=$('#attendanceDate').val();
    var attendace_type=$('#attendace_type').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Staffevaluationattendance/fetchStudents4Attendance/",
        method: "POST",
        data: ({
          attBranches: attBranches,
          attendanceDate:attendanceDate,
          attendace_type:attendace_type
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
  $(document).on('change', '.attendanceTypeStaff_Substitution', function() {
    var stuid=$(this).find('option:selected').attr('value');
    var attendanceType=$(this).find('option:selected').attr('title');
    var id=$(this).find('option:selected').attr('id');
    var substituteBy=$(this).find('option:selected').attr('name');
    var attendanceDate=$('#attendanceDate').val();
    var attendace_type=$('#attendace_type').val();
    $($.parseHTML('.attendanceTypeStaffCommon' +stuid)).removeAttr("checked","checked");
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Staffevaluationattendance/saveAttendance/",
        data: ({
          stuid: stuid,
          attendanceDate:attendanceDate,
          attendanceType:attendanceType,
          substituteBy:substituteBy,
          attendace_type:attendace_type
        }),
        beforeSend: function() {
          $('.savedAttendance'+id+attendanceType).html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="15" height="15" id="loa">'
            );
        },
        dataType:"json",
        success: function(data) {
          $('#emp_attendanceTable').DataTable().ajax.reload();
          iziToast.success({
            title: 'Attendance',
            message: 'Updated successfully',
            position: 'topRight'
          });
        }
    });
  });
  $(document).on('click', '.attendanceTypeStaff', function() {
    var stuid=$(this).attr("value");
    var id=$(this).attr("id");
    var attendanceType=$(this).attr("title");
    var attendanceDate=$('#attendanceDate').val();
    var attendace_type=$('#attendace_type').val();
    $($.parseHTML('.attendanceTypeStaff_Substitution' +stuid)).val('');
    if(attendanceType==='Tardy'){
      var attendanceMinute=document.getElementById('attendanceMinuteStaff_'+stuid).value;
      $.ajax({
        url: "<?php echo base_url(); ?>Staffevaluationattendance/saveAttendance/",
        method: "POST",
        data: ({
          stuid: stuid,
          attendanceDate:attendanceDate,
          attendanceType:attendanceType,
          attendanceMinute:attendanceMinute,
          attendace_type:attendace_type
        }),
        beforeSend: function() {
          $('.savedAttendance'+id+attendanceType).html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="15" height="15" id="loa">'
            );
        },
        dataType:"json",
        success: function(data) {
          $('#emp_attendanceTable').DataTable().ajax.reload();
          iziToast.success({
            title: 'Attendance',
            message: 'Updated successfully',
            position: 'topRight'
          });
        }
      });
    }else{
      $.ajax({
        url: "<?php echo base_url(); ?>Staffevaluationattendance/saveAttendance/",
        method: "POST",
        data: ({
          stuid: stuid,
          attendanceDate:attendanceDate,
          attendanceType:attendanceType,
          attendace_type:attendace_type
        }),
        beforeSend: function() {
          $('.savedAttendance'+id+attendanceType).html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="15" height="15" id="loa">'
            );
        },
        dataType:"json",
        success: function(data) {
          $('#emp_attendanceTable').DataTable().ajax.reload();
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
<script>
  $(document).ready(function() {
    function fetchNewAttendance(view = '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Staffevaluationattendance/attendanceNotification/",
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