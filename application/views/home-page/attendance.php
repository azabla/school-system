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
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'> 
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
                <button type="submit" name="addNewAttendanceType" id="addNewAttendanceType" class="btn btn-secondary pull-right" data-toggle="modal" data-target="#addAttendanceType" > <i class="fas fa-plus"></i> Attendance Type
                </button>
              </div>
              <div class="col-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#feedSubject" role="tab" aria-selected="true"> Feed Attendance</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#feedMergedSubject" role="tab" aria-selected="false">Default Report</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#customAttendanceReport" role="tab" aria-selected="false">Custom Report</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab5" data-toggle="tab" href="#sectionAttendanceReport" role="tab" aria-selected="false">Section Report</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab4" data-toggle="tab" href="#attendanceReportFormat" role="tab" aria-selected="false">Attendance Format</a>
                      </li>
                    </ul>
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
                              <button class="btn btn-primary btn-block" 
                              type="submit" name="viewmark">Load Student</button>
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
                        <div  id="printAttendanceReport" class="table-responsive">
                          <table class="display dataTable" id='empTableGS' style="width:100%;">
                            <thead>
                             <tr>                          
                               <th>Student Name</th>
                               <th>Student ID</th>
                               <th>Grade</th>
                               <th>Attendance Type </th>
                               <th>Attendance Date</th>
                              </tr>
                            </thead>
                          </table> 
                        </div>
                        <div class="col-12">
                          <div class="fetch_attendance"></div>
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
                    <div class="tab-pane fade show" id="sectionAttendanceReport" role="tabpanel" aria-labelledby="home-tab5">
                      <form method="POST" id="sectionForm">
                        <div class="row">
                          <div class="col-lg-2 col-6">
                            <div class="form-group">
                              <select class="form-control" required="required" name="sectionBranch" id="sectionBranch">
                                <option>--- Branch ---</option>
                                <?php foreach($branch as $branchs){ ?>
                                  <option value="<?php echo $branchs->name;?>">
                                  <?php echo $branchs->name;?>
                                  </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-2 col-6">
                            <select class="form-control sectionGradesec" required="required" name="sectionGradesec" id="sectionGradesec">
                               <option>--- Grade ---</option>
                               </select>
                          </div>
                          <div class="col-lg-2 col-6">
                            <label>From Date</label>
                            <div class="form-group">
                            <input type="date" name="customFromDateSection" class="form-control" id="customFromDateSection">
                            </div>
                           </div>
                           <div class="col-lg-2 col-6">
                            <label>To Date</label>
                            <div class="form-group">
                            <input type="date" name="customToDateSection" class="form-control" id="customToDateSection">
                            </div>
                           </div>
                          <div class="col-lg-3 col-12">
                            <label><button class="btn btn-default btn-sm pull-right" name="gethisreport" onclick="codespeedySection()">  <i class="fas fa-print"></i>  print   </button></label>
                            <button class="btn btn-primary btn-lg btn-block" 
                            type="submit" name="viewsectionAttendance">View</button>
                          </div>
                        </div>
                      </form>
                      <div class="sectionStudentList" id="sctionStudentList"> </div>
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
        </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="addAttendanceType" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Add school attendance type</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <form method="POST" id="saveNewAttendanceType" class="saveNewAttendanceType" name="saveNewAttendanceType">
            <div class="form-group">
              <div class="search-element">
                <div class="row">
                  <div class="form-group col-lg-12 col-12">
                    <label>Attendance type( <span class="text-time"> Example. Absent, Execused Absence,Permission, Late or Tardy ...</span>)</label>
                    <input id="attendance_Type" type="text" class="form-control" required="required" name="attendance_Type" placeholder="Attendance type name...">
                  </div>
                  <div class="form-group col-lg-12 col-12">
                    <button class="btn btn-primary pull-right" name="save_Attendance_Type" id="save_Attendance_Type"> Save Attendance Type
                    </button>
                  </div>
                </div>
                <hr>
                <div class="msg_Attendance_Type" id="msg_Attendance_Type"></div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add_minute_toLate" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Add Tardy/Late Minute</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">

          <form method="POST" id="saveMinuteAttendance" class="saveMinuteAttendance" name="saveMinuteAttendance">
            <div class="form-group">
              <div class="search-element">
                <div class="row">
                  <div class="form-group col-lg-12 col-12">
                    <label> Late or Tardy minute ...<span class="text-danger">(Optional)</span></label>
                    <input class="form-control attendanceMinute" id="attendanceMinute" name="attendanceMinute" type="number" placeholder="Tardy/Late Minute ..." required="required">
                  </div>
                  <div class="form-group col-lg-12 col-12">
                    <button type="button" class="form-group btn btn-warning pull-right" data-dismiss="modal">Ignore Minute </button>
                    &nbsp;&nbsp;&nbsp;
                    <button class="form-group btn btn-info pull-right" name="save_Attendance_Type" id="save_Attendance_Type"> Submit Minute
                    </button> 
                  </div>
                </div>
                <hr>
                <div class="minute_Attendance_page" id="minute_Attendance_page"></div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
</body>
<script type="text/javascript">

  $(document).on('click', '#remove_this_attendanceType', function(e) {
    e.preventDefault();
    var userid=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Attendance/removeBook_this_attendance_type/",
      data: ({
        userid: userid
      }),
      beforeSend: function() {
        $('.remove_this_attendanceType' + userid).html( '<span class="text-info">Removing</span>');
        $('#remove_this_attendanceType').attr( 'disabled','disabled');
        
      },
      success: function(html){
        if(html=='1'){
          iziToast.success({
            title: 'Removed successfully',
            message: '',
            position: 'topRight'
          });
          load_attendance_typedata();
        }else{
          iziToast.error({
            title: 'Please try later',
            message: '',
            position: 'topRight'
          });
        }
        $('.remove_this_attendanceType' + userid).html( 'Remove');
        $('#remove_this_attendanceType').removeAttr( 'disabled');
      }
    });
  });
  $(document).on('submit', '#saveNewAttendanceType', function(e) {
    e.preventDefault();
    if ($('#attendance_Type').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Attendance/save_new_attendance_Type/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#save_Attendance_Type').html( 'Saving');
          $('#save_Attendance_Type').attr( 'disabled','disabled');
        },
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Saved successfully',
              message: '',
              position: 'topRight'
            });
            $('#attendance_Type').val('');
            load_attendance_typedata();
          }else{
            iziToast.error({
              title: 'Attendance type found.',
              message: '',
              position: 'topRight'
            });
          }
          $('#save_Attendance_Type').html( 'Save Attendance Type');
          $('#save_Attendance_Type').removeAttr( 'disabled');
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '#addNewAttendanceType', function(e) {
    e.preventDefault();
    load_attendance_typedata();
  });
  function load_attendance_typedata()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>Attendance/load_attendance_typedata/",
      method:"POST",
      beforeSend: function() {
        $('.msg_Attendance_Type').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.msg_Attendance_Type').html(data);
      }
    })
  }
  $(document).ready(function(){
    $('#empTableGS').DataTable({
      'processing': true,
      'serverSide': true,
      "dataType": "json",
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>Attendance/fetch_attendance_report_smart/'
      },
      'columns': [
         { data: 'fname' },
        { data: 'username' },
        { data: 'gradesec' },
         { data: 'absentype' },
        { data: 'absentdate' },
      ]
    });
  }); 
  $(document).on('click', "input[name='agreedToAttendance_activity']", function() {
      var agreed_date = $(this).attr("value");
      var user_id = $(this).attr("id");
      var gradesec = $(this).attr("title");
      var branch = $(this).attr("data-branch");
      if($(this).is(':checked')){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Attendance/sign_agreement/",
          data: ({
            agreed_date:agreed_date,
            user_id:user_id,
            gradesec:gradesec,
            branch:branch
          }),
          cache: false,
          success: function(html) {
            $(".agreedToAttendance_activity"+agreed_date).prop('disabled', true);
            iziToast.success({
              title: 'Signed successfully',
              message: '',
              position: 'topRight'
            });
          }
        });
      }
    });
  $("#downloadStuData").click(function(e) {
  let file = new Blob([$('.studentFormatList').html()], {type:"application/vnd.ms-excel"});
  let url = URL.createObjectURL(file);
  let a = $("<a />", {
    href: url,
    download: "Attendance-format.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
  });
  /*$(document).ready(function () {
    $("#downloadStuData").click(function(){
      TableToExcel.convert(document.getElementById("studentFormatList"), {
        name: "Attendance-format.xlsx",
        sheet: {
        name: "Sheet1"
        }
      });
    });
  });*/
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
          $('.customStudentList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".customStudentList").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $('#sectionForm').on('submit', function(event) {
    event.preventDefault();
    var attBranches=$('#sectionBranch').val();
    var grade=$('#sectionGradesec').val();
    var customFromDateSection=$('#customFromDateSection').val();
    var customToDateSection=$('#customToDateSection').val();
    if($('#sectionGradesec').val()!='--- Grade ---'){
      $.ajax({
        url: "<?php echo base_url(); ?>Attendance/fetchSectionStudentsAttendance/",
        method: "POST",
        data: ({
          attBranches: attBranches,
          grade:grade,
          customFromDateSection:customFromDateSection,
          customToDateSection:customToDateSection
        }),
        beforeSend: function() {
          $('.sectionStudentList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".sectionStudentList").html(data);
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
  function codespeedySection(){
    var print_div = document.getElementById("sctionStudentList");
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
            $('#empTableGS').DataTable().ajax.reload();

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
  $(document).ready(function() {  
    $("#sectionBranch").bind("change", function() {
      var branchit=$('#sectionBranch').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: ({
            branchit: branchit
        }),
        beforeSend: function() {
          $('.sectionGradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".sectionGradesec").html(data);
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
          $('.studentList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
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
  $(document).on('click', '.attendanceType', function() {
    var stuid=$(this).attr("value");
    var id=$(this).attr("id");
    var attendanceType=$(this).attr("title");
    var attendanceDate=$('#attendanceDate').val();
    $.ajax({
      url: "<?php echo base_url(); ?>Attendance/saveAttendance/",
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
          $('#empTableGS').DataTable().ajax.reload();
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
          $('#empTableGS').DataTable().ajax.reload();
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
          $('.studentFormatList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
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

</html>