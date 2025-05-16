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
              <div class="col-lg-12 col-12">
                <a href="#" class="pull-right" value="" data-toggle="modal" data-target="#addAttendanceType"><span class="text-success">
                <button class="btn btn-outline-info"><i data-feather="plus-circle"> </i>Add Attendance Type</button>
               </span>
               </a>
                <a href="#" class="pull-right" value="" data-toggle="modal" data-target="#addAttendancePrograme"><span class="text-success">
                <button class="btn btn-outline-primary"><i data-feather="plus-circle"> </i>Add Attendance Program</button>
               </span>
               </a>
              </div>
              <div class="col-12">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#addaspAttendance" role="tab" aria-selected="true">Add/Remove Student</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#feedaspAttendance" role="tab" aria-selected="true">Feed ASP Attendance</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#fetchASPAttendance" role="tab" aria-selected="false">ASP Attendance Report</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="addaspAttendance" role="tabpanel" aria-labelledby="home-tab1">
                        <form method="POST" id="addRemoveASPAttendance">
                          <div class="row">
                           <div class="col-lg-4 col-6">
                            <div class="form-group">
                             <select class="form-control" required="required" name="AddRemovegrands_branchit" id="AddRemovegrands_branchit">
                             <option>--- Branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                            </div>
                            <div class="col-lg-4 col-6">
                             <select class="form-control AddRemovegrands_gradesec" required="required" name="AddRemovegrands_gradesec" id="AddRemovegrands_gradesec">
                              <option>---Grade---</option>
                         
                            </select>
                            </div> 
                             <div class="col-lg-4 col-12">
                              <button class="btn btn-primary btn-block" type="submit" name="viewmarkAddRemove">View Student</button>
                            </div>
                          </div>
                        </form>
                        <div class="fetchstudentList"> </div>
                      </div>
                      <div class="tab-pane fade show" id="feedaspAttendance" role="tabpanel" aria-labelledby="home-tab2">
                        <form method="POST" id="comment_form">
                          <div class="row">
                           <div class="col-lg-2 col-6">
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
                            <div class="col-lg-2 col-6">
                             <select class="form-control grands_gradesec" required="required" name="grands_gradesec" id="grands_gradesec">
                              <option>---Grade---</option>
                         
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
                             <div class="col-lg-2 col-12">
                              <button class="btn btn-primary btn-block" 
                              type="submit" name="viewmark">View Student</button>
                            </div>
                          </div>
                        </form>
                        <div class="studentList"> </div>
                      </div>
                      <div class="tab-pane fade show" id="fetchASPAttendance" role="tabpanel" aria-labelledby="home-tab3">
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
  <div class="modal fade" id="addAttendanceType" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Manage Attendance Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="modal-body">
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab11" data-toggle="tab" href="#addNewType" role="tab" aria-selected="true"> Add Attendance Type</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab22" data-toggle="tab" href="#viewAttendanceType" role="tab" aria-selected="false">View Recorded</a>
              </li>
            </ul>
            <div class="tab-content tab-bordered" id="myTab3Content">
              <div class="tab-pane fade show active" id="addNewType" role="tabpanel" aria-labelledby="home-tab11">
                <form method="POST" class="saveAttendanceType">
                  <div class="row">
                    <div class="col-lg-6 col-6">
                      <label for="Staff"> Attendance Type </label>
                       <input type="text" name="attendanceType" class="form-control attendanceType" id="attendanceType" placeholder="Attendance type here..." required>
                    </div>
                    <div class="col-lg-6 col-6">
                      <label for="Staff"> Attendance Description </label>
                      <input type="text" name="attendanceDesc" class="form-control attendanceDesc" id="attendanceDesc" placeholder="Attendance description here...">
                    </div>
                    <div class="col-lg-12 col-12 table-responsive" style="height: 30vh;">
                      <label for="Grade"> Select grade to assign</label><br>
                      <div class="pretty p-bigger">
                          <input type="checkbox" class="" id="selectAllAttendanceType" onClick="selectAll_Attendance_Type()">
                          <div class="state p-warning">
                            <i class="icon material-icons"></i>
                            <label></label>Select All 
                          </div>
                      </div>
                      <div class="row">
                        <?php foreach($grade as $gradesecs){ ?>
                        <div class="col-lg-2 col-4">
                          <div class="pretty p-icon p-bigger">
                            <input type="checkbox" name="gradeAttendanceType" value="<?php echo $gradesecs->grade;?>" class="gradeAttendanceType" id="customCheck1">
                            <div class="state p-info">
                              <i class="icon material-icons"></i>
                              <label></label><?php echo $gradesecs->grade; ?>
                            </div>
                          </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-lg-12 col-12 pull-right">
                      <div class="dropdown-divider"></div>
                      <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                      <button type="submit" name="saveAttendanceType" id="saveAttendanceType" class="btn btn-primary pull-right"> Save Attendance Type </button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane fade show" id="viewAttendanceType" role="tabpanel" aria-labelledby="home-tab2">
                <div class="fetch_attendance_type"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="addAttendancePrograme" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitlee" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Manage Attendance Program</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="modal-body">
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab111" data-toggle="tab" href="#addNewProgram" role="tab" aria-selected="true"> Add Program</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab222" data-toggle="tab" href="#viewAttendanceProgram" role="tab" aria-selected="false">View program</a>
              </li>
            </ul>
            <div class="tab-content tab-bordered" id="myTab3Content">
              <div class="tab-pane fade show active" id="addNewProgram" role="tabpanel" aria-labelledby="home-tab111">
                <form method="POST" class="saveAttendanceProgram">
                  <div class="row">
                    <div class="col-lg-6 col-6">
                      <label for="Staff"> Program Name </label>
                       <input type="text" name="attendanceProgram" class="form-control attendanceProgram" id="attendanceProgram" placeholder="Attendance program here..." required>
                    </div>
                    <div class="col-lg-6 col-6">
                      <label for="Staff"> Program Description </label>
                      <input type="text" name="attendanceProgramDesc" class="form-control attendanceProgramDesc" id="attendanceProgramDesc" placeholder="Attendance program description here...">
                    </div>
                    <div class="col-lg-12 col-12 table-responsive" style="height: 30vh;">
                      <label for="Grade"> Select grade to assign</label><br>
                      <div class="pretty p-bigger">
                          <input type="checkbox" class="" id="selectAllAttendanceProgram" onClick="selectAll_Attendance_Program()">
                          <div class="state p-warning">
                            <i class="icon material-icons"></i>
                            <label></label>Select All 
                          </div>
                      </div>
                      <div class="row">
                        <?php foreach($grade as $gradesecs){ ?>
                        <div class="col-lg-2 col-4">
                          <div class="pretty p-icon p-bigger">
                            <input type="checkbox" name="gradeAttendanceProgram" value="<?php echo $gradesecs->grade;?>" class="gradeAttendanceProgram" id="customCheck1">
                            <div class="state p-info">
                              <i class="icon material-icons"></i>
                              <label></label><?php echo $gradesecs->grade; ?>
                            </div>
                          </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-lg-12 col-12 pull-right">
                      <div class="dropdown-divider"></div>
                      <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                      <button type="submit" name="saveAttendanceProgram" id="saveAttendanceProgram" class="btn btn-primary pull-right"> Save Attendance Program </button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane fade show" id="viewAttendanceProgram" role="tabpanel" aria-labelledby="home-tab222">
                <div class="fetch_attendance_Program"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  $(document).on('click', '.addremoveaspattendanceStuid', function() {
    var stuid=$(this).attr("value");
    var attendanceProgram=$(this).attr("id");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>aspattendance/update_studentASPAttendance/",
        data: ({
          stuid: stuid,
          attendanceProgram:attendanceProgram
        }),
        cache: false,
        success: function(html){
          iziToast.success({
            title: html,
            message: '',
            position: 'topRight'
          });
        }
      });
    });
  $('#addRemoveASPAttendance').on('submit', function(event) {
    event.preventDefault();
    var grade=$('#AddRemovegrands_gradesec').val();
    var branch=$('#AddRemovegrands_branchit').val();
    if($('#AddRemovegrands_branchit').val() !='--- Branch ---'){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Aspattendance/addremove_studentASP_Attendance/",
        data: ({
          grade: grade,
          branch:branch
        }),
        cache: false,
        beforeSend: function() {
          $('.fetchstudentList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $(".fetchstudentList").html(html);
        }
      });
    }else{
      swal('Please select all fields !', {
        icon: 'error',
      });
    }
  });
  $(document).ready(function() {  
    $("#AddRemovegrands_branchit").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Aspattendance/Filter_grade_from_branch/",
        data: "branchit=" + $("#AddRemovegrands_branchit").val(),
        beforeSend: function() {
          $('.AddRemovegrands_gradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".AddRemovegrands_gradesec").html(data);
        }
      });
    });
  });
  loadProgramdata();
  function loadProgramdata()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>Aspattendance/fetch_attendance_program/",
      method:"POST",
      beforeSend: function() {
        $('.fetch_attendance_Program').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.fetch_attendance_Program').html(data);
      }
    })
  }
  $(document).on('click', '.edit_attendance_program', function(){
    var programName=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Aspattendance/fetch_attendance_program_toedit/",
      data: ({
        programName: programName
      }),
      cache: false,
      beforeSend: function() {
        $('.fetch_attendance_Program').html( '<h3>Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa"></h3>'
          );
      },
      success: function(html){
        $('.fetch_attendance_Program').html(html);
      }
    });
  });
  $(document).on('click', '#saveThisProgramNameChanges', function() {
      grade=[];
      $("input[name='changeedit_ProgramName']:checked").each(function(i){
        grade[i]=$(this).val();
      });
      var programOld=$(this).attr('value');
      var programNew=$('#edit_ProgramName').val();
      if(grade.length!=0 && $('#edit_ProgramName').val()!=''){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Aspattendance/updateThisProgram/",
          data: ({
            programOld:programOld,
            programNew:programNew,
            grade:grade
          }),
          cache: false,
          beforeSend: function() {
            $('#fetch_attendance_Program').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success: function(data){
            loadProgramdata();
          }
        });
      }else{
         swal('Oooops, Please select at least one grade or fill necessary fields to save changes!', {
          icon: 'error',
        });
      }
    });
  $(document).on('click', '#deleteThisedit_ProgramName', function() {
    var grade=$(this).attr("value");
    var program=$(this).attr("name");
    swal({
      title: 'Are you sure you want to delete this grade program?',
      text: 'Every data will be erased permanently regarding this program for grade '+grade+'! ',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Aspattendance/deleteSpecificProgram/",
          data: ({
            grade: grade,
            program:program
          }),
          cache: false,
          success: function(html){
            loadProgramdata();
          }
        });
      }
    });
  });
  $('.saveAttendanceProgram').on('submit', function(event) {
    event.preventDefault();
    grade=[];
    $("input[name='gradeAttendanceProgram']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    var attendanceType=$('#attendanceProgram').val();
    var attendanceDesc=$('#attendanceProgramDesc').val();
    if($('#attendanceProgram').val() !='' && grade.length!=0){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Aspattendance/postProgramType/",
        data: ({
          attendanceType: attendanceType,
          attendanceDesc:attendanceDesc,
          grade:grade
        }),
        cache: false,
        success: function(html){
          $('.saveAttendanceProgram')[0].reset();
          loadProgramdata();
        }
      });
    }else{
      swal('Please select all fields !', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '#delete_attendance_program', function()
  {
    var attendanceType=$(this).attr("value");
    swal({
      title: 'Are you susre you want to delete this Attendance program?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Aspattendance/Delete_attendance_program/",
          data: ({
            attendanceType:attendanceType
          }),
          cache: false,
          beforeSend: function() {
            $('.delete_attendance_program'+ attendanceType).html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
            );
          },
          success: function(html){
           $('.delete_attendance_program' + attendanceType ).fadeOut('slow');
           loadProgramdata();
          }
        });
      }
    });
  });
  function selectAll_Attendance_Program(){
      var itemsall=document.getElementById('selectAllAttendanceProgram');
      if(itemsall.checked==true){
      var items=document.getElementsByName('gradeAttendanceProgram');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('gradeAttendanceProgram');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
  }
  function selectAll_Attendance_Type(){
      var itemsall=document.getElementById('selectAllAttendanceType');
      if(itemsall.checked==true){
      var items=document.getElementsByName('gradeAttendanceType');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('gradeAttendanceType');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
  }
</script>
<script type="text/javascript">
  loadRemotedata();
  function loadRemotedata()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>Aspattendance/fetch_attendance_type/",
      method:"POST",
      beforeSend: function() {
        $('.fetch_attendance_type').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.fetch_attendance_type').html(data);
      }
    })
  }
  $('.saveAttendanceType').on('submit', function(event) {
    event.preventDefault();
    grade=[];
    $("input[name='gradeAttendanceType']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    var attendanceType=$('#attendanceType').val();
    var attendanceDesc=$('#attendanceDesc').val();
    if($('#attendanceType').val() !='' && grade.length!=0){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Aspattendance/postAttendanceType/",
        data: ({
          attendanceType: attendanceType,
          attendanceDesc:attendanceDesc,
          grade:grade
        }),
        cache: false,
        success: function(html){
          $('.saveAttendanceType')[0].reset();
          loadRemotedata();
        }
      });
    }else{
      swal('Please select all fields !', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '#delete_attendance_type', function()
  {
    var attendanceType=$(this).attr("value");
    swal({
      title: 'Are you susre you want to delete this Attendance type?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Aspattendance/Delete_attendance_Type/",
          data: ({
            attendanceType:attendanceType
          }),
          cache: false,
          beforeSend: function() {
            $('.delete_attendance_type'+ attendanceType).html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
            );
          },
          success: function(html){
           $('.delete_attendance_type' + attendanceType ).fadeOut('slow');
           loadRemotedata();
          }
        });
      }
    });
  }); 
  $(document).ready(function() {  
    $("#grands_gradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Aspattendance/Filter_asp_program_from_grade/",
        data: "branchit=" + $("#grands_gradesec").val(),
        beforeSend: function() {
          $('.asp_program').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".asp_program").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#grands_branchit").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Aspattendance/Filter_grade_from_branch/",
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
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var gradeSection=$('#grands_gradesec').val();
    var attBranches=$('#grands_branchit').val();
    var attendanceDate=$('#asp_attendanceDate').val();
    var attendanceProgram=$('#asp_program').val();
    if ($('#grands_gradesec').val()!='') {
      $.ajax({
        url: "<?php echo base_url(); ?>Aspattendance/fetchStudents4_asp_Attendance/",
        method: "POST",
        data: ({
          attBranches: attBranches,
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
  function selectAll_gradesec_asp_now(){
      var itemsall=document.getElementById('selectAll_gradesec_asp');
      if(itemsall.checked==true){
      var items=document.getElementsByName('gradesec_list_gs_asp');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('gradesec_list_gs_asp');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
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
        url:"<?php echo base_url(); ?>aspattendance/fetchasp_AttendanceReport/",
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
        url: "<?php echo base_url(); ?>aspattendance/searchAttendance/",
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
        url:"<?php echo base_url(); ?>aspattendance/fetchasp_AttendanceReport/",
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
              url: "<?php echo base_url(); ?>aspattendance/saveAttendance/",
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
            url: "<?php echo base_url(); ?>aspattendance/saveAttendance/",
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
          url: "<?php echo base_url(); ?>aspattendance/deleteAttendance/",
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