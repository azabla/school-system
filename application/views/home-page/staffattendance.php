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
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#feedStaffAttendance" role="tab" aria-selected="true"> Manage Attendance</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#fetchAttendanceReport" role="tab" aria-selected="false">Attendance Report</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#fetchAttendanceCustomReport" role="tab" aria-selected="false">Custom Report</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="feedStaffAttendance" role="tabpanel" aria-labelledby="home-tab1">
                    <form method="POST" id="comment_form">
                      <div class="row">
                        <div class="col-lg-7 col-6">
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
                        <div class="col-lg-5 col-6">
                          <button class="btn btn-primary btn-block btn-lg" type="submit" name="viewmark">View</button>
                        </div>
                      </div>
                    </form>
                    <div class="fetchStaffHere"></div>
                  </div>
                  <div class="tab-pane fade show" id="fetchAttendanceReport" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="fetchStaffsAttendanceHere">
                    <table class="display dataTable" id='empTable' style="width:100%;">
                        <thead>
                         <tr>
                           <th>Full Name</th>
                           <th>Branch</th>
                           <th>Attendance Type</th>
                           <th>Attendance Date</th>                           
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="fetchAttendanceCustomReport" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-outline-info btn-lg pull-right" name="gethisreport" onclick="codespeedyCustom()">  print   </button>
                        <button type="submit" id="dataExportExcelS" name="dataExport" value="Export to excel" class="btn btn-outline-primary btn-lg pull-right">Export To Excel
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="customForm">
                      <div class="row">
                        <div class="col-lg-4 col-6">
                          <label>From Date</label>
                          <div class="form-group">
                          <input type="date" name="customFromDate" class="form-control" id="customFromDate">
                          </div>
                         </div>
                         <div class="col-lg-4 col-6">
                          <label>To Date</label>
                          <div class="form-group">
                          <input type="date" name="customToDate" class="form-control" id="customToDate">
                          </div>
                         </div>
                         <div class="col-lg-4 col-6">
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
                           <input type="checkbox" name="includeDate" id="includeDate" value="1">Include Date
                          <button class="btn btn-primary btn-lg btn-block" 
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
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
</body>
<script type="text/javascript">
  $('.gs-sms-manage-attendance-page').addClass('active');
  $(document).ready(function(){
    $('#empTable').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>staffattendance/fetchStaffsAttendance/'
      },
      'columns': [
         { data: 'fname' },
         { data: 'branch' },
         { data: 'absentype' },
         { data: 'absentdate' },
      ]
    });
  });
  $("#dataExportExcelS").click(function(e) {
  let file = new Blob([$('.customStudentList').html()], {type:"application/vnd.ms-excel"});
  let url = URL.createObjectURL(file);
  let a = $("<a />", {
    href: url,
    download: "Result Statistics.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
  });
  $('#customForm').on('submit', function(event) {
    event.preventDefault();
    grade=[];
    var attBranches=$('#customBranch').val();
    var customFromDate=$('#customFromDate').val();
    var customToDate=$('#customToDate').val();
    if($('#includeDate').is(':checked')){
      var includeDate='1';
    }else{
      var includeDate='0';
    }
    if($('#customBranch').val()!=''){
      $.ajax({
        url: "<?php echo base_url(); ?>staffattendance/fetchCustomStaffsAttendance/",
        method: "POST",
        data: ({
          attBranches: attBranches,
          customToDate:customToDate,
          customFromDate:customFromDate,
          includeDate:includeDate
        }),
        beforeSend: function() {
          $('.customStudentList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".customStudentList").html(data);
        }
      })
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
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var staffBranche=$('#grands_branchit').val();
    $.ajax({
      url: "<?php echo base_url(); ?>staffattendance/fetchStaffsToAttendance/",
        method: "POST",
        data: ({
          staffBranche: staffBranche
        }),
        beforeSend: function() {
          $('.fetchStaffHere').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".fetchStaffHere").html(data);
        }
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $(document).on('click', '#absentStaff', function() {
      var staffId = $(this).attr("value");
      var dateAbsent=$('#todayDate').val();
      if ($('#todayDate').val()!=='') {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>staffattendance/absentAttendance/",
          data: ({
            staffId: staffId,
            dateAbsent:dateAbsent
          }),
          cache: false,
          success: function(html) {
            $(".atteInfo").html(html);
            $('#empTable').DataTable().ajax.reload();
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
          url: "<?php echo base_url(); ?>staffattendance/lateAttendance/",
          data: ({
            staffId: staffId,
            dateAbsent:dateAbsent,
            lateMin:lateMin
          }),
          cache: false,
          success: function(html) {
            $(".atteInfo").html(html);
            $('#empTable').DataTable().ajax.reload();
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
          url: "<?php echo base_url(); ?>staffattendance/permissionAttendance/",
          data: ({
            staffId: staffId,
            dateAbsent:dateAbsent
          }),
          cache: false,
          success: function(html) {
            $(".atteInfo").html(html);
            $('#empTable').DataTable().ajax.reload();
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
          url: "<?php echo base_url(); ?>staffattendance/deleteAttendance/",
          data: ({
            staffId: staffId
          }),
          cache: false,
          success: function(html) {
            $('#empTable').DataTable().ajax.reload();
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
</html>