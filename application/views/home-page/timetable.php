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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
</head>

<body>
  <div class="loader"><div class="loaderIcon"></div></div>
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
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <div class="row"> 
                      <div class="col-md-12 col-12">
                        <h4>Timetable Generator</h4>
                     </div>
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#dayInfo" role="tab" aria-controls="dayInfo" aria-selected="true"> <h5 class="card-title">No.of Periods/Day</h5></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#noExam" role="tab" aria-controls="noExam" aria-selected="false"> <h5 class="card-title">No. of Days</h5></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab8" data-toggle="tab" href="#lessonPerWeek" role="tab" aria-controls="finish" aria-selected="false"> <h5 class="card-title">Lessons/Week</h5></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab6" data-toggle="tab" href="#finishTab" role="tab" aria-controls="finish" aria-selected="false"> <h5 class="card-title">Generate</h5></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab8" data-toggle="tab" href="#editTab" role="tab" aria-controls="finish" aria-selected="false"> <h5 class="card-title">Edit Timetable</h5></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab7" data-toggle="tab" href="#printTab" role="tab" aria-controls="finish" aria-selected="false"> <h5 class="card-title">View Timetable</h5></a>
                      </li>
                    </ul>
                    
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="dayInfo" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row">
                          <div class="col-lg-12 col-md-12 col-12">
                            <a href="#" class="addcreateperiod" value="" data-toggle="modal" data-target="#add_create_period"><span class="text-success">
                              <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add/Create Period</button>
                             </span>
                            </a>
                          </div>
                          <div class="col-lg-12 col-md-12 col-12">
                            <div class="periodNameHere"></div>
                          </div>
                        </div>
                      </div>                      
                      <div class="tab-pane fade show" id="noExam" role="tabpanel" aria-labelledby="home-tab3">
                        <div class="dropdown-divider"></div>
                        <fieldset>
                          <div class="fetch_here_timeTableDays"></div>
                        
                        </fieldset>
                      </div>
                      <div class="tab-pane fade show" id="lessonPerWeek" role="tabpanel" aria-labelledby="home-tab8">
                        <div class="dropdown-divider"></div>
                        <fieldset>
                           <div class="lessonPerWeekHere"></div>
                        </fieldset>
                      </div>
                      <div class="tab-pane fade show" id="finishTab" role="tabpanel" aria-labelledby="home-tab6">
                        <div class="dropdown-divider"></div>
                        <fieldset>
                          <div class="row">
                            <div class="col-lg-6 col-6">
                              <div class="form-group">
                                <select class="form-control" required="required" name="timetableBranch" id="timetableBranch">
                                  <option>--- Branch ---</option>
                                    <?php foreach($branch as $branchs){ ?>
                                    <option value="<?php echo $branchs->name;?>">
                                      <?php echo $branchs->name;?>
                                    </option>
                                    <?php }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6 col-6">
                              <button class="btn btn-primary btn-block" id="generate">Generate Timetable</button>
                            </div>
                            <div class="col-md-12 col-12">
                              <div class="timeTableHere"> </div>
                            </div>                            
                          </div>
                        </fieldset>
                      </div>
                      <div class="tab-pane fade show" id="editTab" role="tabpanel" aria-labelledby="home-tab8">
                        <div class="dropdown-divider"></div>
                        <fieldset>
                          <div class="row">
                            <div class="col-md-5 col-5">
                              <div class="form-group">
                                <select class="form-control" required="required" name="editTimetableBranch" id="editTimetableBranch">
                                  <option>--- Branch ---</option>
                                    <?php foreach($branch as $branchs){ ?>
                                    <option value="<?php echo $branchs->name;?>">
                                      <?php echo $branchs->name;?>
                                    </option>
                                    <?php }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-5 col-5">
                              <select class="form-control editTimeTable" required="required" name="editTimeTable" id="editTimeTable">
                                <option></option>
                                <option value="timeTable_eachClass">Timetable for each Class</option>
                              </select>
                            </div>
                          </div>
                          <div class="editTimetablePage" id="editTimetablePage"></div>
                        </fieldset>
                      </div>
                      <div class="tab-pane fade show" id="printTab" role="tabpanel" aria-labelledby="home-tab7">
                        <div class="dropdown-divider"></div>
                        <fieldset>
                          <div class="row">
                            <div class="col-md-5 col-5">
                              <div class="form-group">
                                <select class="form-control" required="required" name="viewTimetableBranch" id="viewTimetableBranch">
                                  <option>--- Branch ---</option>
                                    <?php foreach($branch as $branchs){ ?>
                                    <option value="<?php echo $branchs->name;?>">
                                      <?php echo $branchs->name;?>
                                    </option>
                                    <?php }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-5 col-5">
                              <select class="form-control printTimeTable" required="required" name="printTimeTable" id="printTimeTable">
                                <option></option>
                                <option value="timeTable_eachClass">Timetable for each Class</option>
                                <option value="timeTable_eachTeacher">Timetable for each Teacher</option>
                              </select>
                            </div>
                            <div class="col-md-2 col-2">
                              <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                              </button>
                            </div>
                          </div>
                          <div class="viewTimetablePage" id="viewTimetablePage"></div>
                        </fieldset>
                      </div>
                    </div>
                 
                    <div class="row">
                      <div class="col-md-12 col-12">
                        <div class="btn-toolbar pull-right">
                          <div class="btn-group">
                            <button class="btn btn-light prevtab" data-direction="previous" data-target="#myTab"><span class="fas fa-arrow-left" aria-hidden="true"></span> Previous</button>
                            <button class="btn btn-light nexttab" data-direction="next" data-target="#myTab">Next <span class="fas fa-arrow-right" aria-hidden="true"></span>
                            </button>
                          </div>
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
  <div class="modal fade" id="add_create_period" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Create Periods</h5>          
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
            <div class="card-body StudentViewTextInfo">
              <div class="modal-body">
                <small class="text-muted">Create only class periods not breaks,lunch time and other...</small>
                <fieldset>
                  <div class="row">
                    <div class="col-md-6 col-6">
                      <input type="number" class="form-control" id="numberperiods" placeholder="No of Periods...">
                    </div>
                    <div class="col-md-6 col-6">
                      <button class="btn btn-info btn-block" type="submit" id="createPeriodNames">Create Period</button>
                    </div>
                    <div class="col-md-12 col-12">
                      <div class="place_periodNames"></div>
                    </div>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="editDelete_timetable_period" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add Periods</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
            <div class="card-body StudentViewTextInfo">
              <div class="modal-body">
                <div class="edit_gs_Timetable"></div>
              </div>
            </div>
          </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/gs_custom.js"></script>
</body>
  <script type="text/javascript">
    $(document).on('submit', '#submitEdittedTimetablePeriod', function(event) {
      event.preventDefault();
      var grade=$('#timetableGrade').val();
      var period=$('#timetablePeriod').val();
      var branch=$('#editedtimetableBranch').val();
      var dayName=$('#timetableDayName').val();
      var staff=$('#selectEditetdPeriodStaff').val();
      var subject=$('#selectEditetdPeriodSubject').val();
      $.ajax({
        url:"<?php echo base_url(); ?>Timetable/savechanges_this_timetable_period/",
        method:"POST",
        data:({
          grade: grade,
          period:period,
          branch:branch,
          dayName:dayName,
          staff:staff,
          subject:subject
        }),
        beforeSend: function() {
          $('.edit_gs_Timetable').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success:function(data){
          if(data=='1'){
            iziToast.success({
              title: 'Timetable updated successfully.',
              message: '',
              position: 'topRight'
            });
            var printType=$('#editTimeTable').val();
            var branch=$('#editTimetableBranch').val();
            $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>Timetable/edit_for_eachclass/",
              data: ({
                printType:printType,
                branch:branch
              }),
              beforeSend: function() {
                $('.editTimetablePage').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
              },
              success: function(data) {
                $(".editTimetablePage").html(data);
              }
            });
          }else{
            iziToast.error({
              title: 'Oooops Please try again.<br> Maybe timetable period occupied',
              message: '',
              position: 'topRight'
            });
          }
          $('#editDelete_timetable_period').modal('hide');
        }
      });
    });
    $(document).on('click', '.editDelete_timetable_period', function(event) {
      event.preventDefault();
      var grade=$(this).attr('day-grade');
      var period=$(this).attr('value');
      var branch=$(this).attr('id');
      var dayName=$(this).attr('name');
      $.ajax({
        url:"<?php echo base_url(); ?>Timetable/addedit_this_timetable_period/",
        method:"POST",
        data:({
          grade: grade,
          period:period,
          branch:branch,
          dayName:dayName
        }),
        beforeSend: function() {
          $('.edit_gs_Timetable').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success:function(data){
          $(".edit_gs_Timetable").html(data);
        }
      });
    });
    $(document).on('change', '#selectEditetdPeriodStaff', function(event) {
      event.preventDefault();
      var staff=$('#selectEditetdPeriodStaff').val();
      var grade=$('#timetableGrade').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Timetable/fetch_subject_for_staffs/",
        data: ({
          staff:staff,
          grade:grade
        }),
        beforeSend: function() {
          $('#selectEditetdPeriodSubject').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $("#selectEditetdPeriodSubject").html(data);
        }
      });
    });
    $(document).on('click', '.delete_thisPeriod', function(event) {
      event.preventDefault();
      var grade=$(this).attr('name');
      var period=$(this).attr('value');
      var subject=$(this).attr('id');
      var branch=$(this).attr('data-branch');
      var dayName=$(this).attr('data-day');
      swal({
        title: 'Are you sure you want to delete this period?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            url:"<?php echo base_url(); ?>Timetable/delete_this_timetable_period/",
            method:"POST",
            data:({
              grade: grade,
              period:period,
              subject:subject,
              branch:branch,
              dayName:dayName
            }),
            success:function(data){
              if(data=='1'){
                iziToast.success({
                  title: 'Timetable updated successfully.',
                  message: '',
                  position: 'topRight'
                });
                var printType=$('#editTimeTable').val();
                var branch=$('#editTimetableBranch').val();
                $.ajax({
                  type: "POST",
                  url: "<?php echo base_url(); ?>Timetable/edit_for_eachclass/",
                  data: ({
                    printType:printType,
                    branch:branch
                  }),
                  beforeSend: function() {
                    $('.editTimetablePage').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
                  },
                  success: function(data) {
                    $(".editTimetablePage").html(data);
                  }
                });
              }else{
                iziToast.error({
                  title: 'Oooops Please try again.',
                  message: '',
                  position: 'topRight'
                });
              }
            }
          });
        }
      });
    });
    $("#editTimeTable").bind("change", function(event) {
      event.preventDefault();
      var printType=$('#editTimeTable').val();
      var branch=$('#editTimetableBranch').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Timetable/edit_for_eachclass/",
        data: ({
          printType:printType,
          branch:branch
        }),
        beforeSend: function() {
          $('.editTimetablePage').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".editTimetablePage").html(data);
        }
      });
    });
    $("#editTimetableBranch").bind("change", function(event) {
      event.preventDefault();
      var printType=$('#editTimeTable').val();
      var branch=$('#editTimetableBranch').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Timetable/edit_for_eachclass/",
        data: ({
          printType:printType,
          branch:branch
        }),
        beforeSend: function() {
          $('.editTimetablePage').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".editTimetablePage").html(data);
        }
      });
    });
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Timetable/fetch_lessons_per_week/",
        method:"POST",
        beforeSend: function() {
          $('.lessonPerWeekHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.lessonPerWeekHere').html(data);
        }
      })
    }
  $(document).on('click', "input[name='subject_timetable_status']", function() {
    var grade = $(this).attr("value");
    var subject = $(this).attr("id");
    var academicYear = $(this).attr("class");
    if($(this).is(':checked')){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Timetable/on_subject_status/",
          data: ({
            grade:grade,
            subject:subject,
            academicYear:academicYear
          }),
          cache: false,
          success: function(html) {
            iziToast.success({
              title: 'Subject status saved successfully',
              message: '',
              position: 'topRight'
            });
            load_data();
          } 
        });
    }else{
      var grade = $(this).attr("value");
      var subject = $(this).attr("id");
      var academicYear = $(this).attr("class");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Timetable/off_subject_status/",
        data: ({
          grade:grade,
          subject:subject,
          academicYear:academicYear
        }),
        cache: false,
        success: function(html) {
          iziToast.success({
            title: 'Subject status saved successfully',
            message: '',
            position: 'topRight'
          });
          load_data();
        }
      });
    }
  });
  function codespeedy(){
    var print_div = document.getElementById("viewTimetablePage");
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
  $(document).ready(function() {
    loadTimeTable();
    function loadTimeTable()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>timetable/loadTimeTable/",
        method:"POST",
        beforeSend: function() {
          $('.periodNameHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.periodNameHere').html(data);
        }
      })
    }  
    $("#printTimeTable").bind("change", function(event) {
      event.preventDefault();
      var printType=$('#printTimeTable').val();
      var branch=$('#viewTimetableBranch').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Timetable/timetable_for_eachclass/",
        data: ({
          printType:printType,
          branch:branch
        }),
        beforeSend: function() {
          $('.viewTimetablePage').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".viewTimetablePage").html(data);
        }
      });
    });
    $("#viewTimetableBranch").bind("change", function(event) {
      event.preventDefault();
      var printType=$('#printTimeTable').val();
      var branch=$('#viewTimetableBranch').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Timetable/timetable_for_eachclass/",
        data: ({
          printType:printType,
          branch:branch
        }),
        beforeSend: function() {
          $('.viewTimetablePage').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".viewTimetablePage").html(data);
        }
      });
    });
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Timetable/fetch_lessons_per_week/",
        method:"POST",
        beforeSend: function() {
          $('.lessonPerWeekHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.lessonPerWeekHere').html(data);
        }
      })
    }
    $(document).on('change', '.selectLessonWeek', function() {
      var value=$(this).find('option:selected').attr('value');
      var academicyear=$(this).find('option:selected').attr('id');
      var subject=$(this).find('option:selected').attr('name');
      var grade=$(this).find('option:selected').attr('class');
      var staff=$(this).find('option:selected').attr('title');
      $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>Timetable/update_lessons_per_week/",
          data: ({
            value:value,
            academicyear:academicyear,
            subject:subject,
            grade:grade
          }),
          success: function(data) {
            if(data=='1'){
              iziToast.success({
                title: 'Changes updated successfully.',
                message: '',
                position: 'topRight'
              });
            }else{
              iziToast.error({
                title: 'Ooops, changes can`t be updated. Please review your settings.<br> Maybe subject credit hour exceeds the total credit hour.',
                message: '',
                position: 'topRight'
              });
            }
            load_data();
          }
      });
    });
    /*function update_Teachercredit_onUpdate_lessons(staff)
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Timetable/update_teachercredit_onUpdate_lessons/",
        method:"POST",
        data: ({
          staff:staff
        }),
        beforeSend: function() {
          $('#count_total_lessons' + staff).html( 'updating...');
        },
        success:function(data){
          $('#count_total_lessons'+ staff).html(data);
        }
      })
    }*/
    $(document).on('submit', '#save_gs_periods', function(event) {
      event.preventDefault();
      periodID=[];startTime=[];endTime=[];
      $("input[name='period_ID']").each(function(i){
        periodID[i]=$(this).val();
      });
      $("input[name='start_Time']").each(function(i){
        if($(this).val()==''){
          swal('Incorrect time result. Please try Again. ', {
            icon: 'error',
          });
        }else{
          startTime[i]=$(this).val();
        } 
      });
      $("input[name='end_Time']").each(function(i){
        if($(this).val()==''){
          swal('Incorrect time result. Please try Again. ', {
            icon: 'error',
          });
        }else{
          endTime[i]=$(this).val();
        }
      });
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Timetable/save_period_names/",
        data: ({
          periodID:periodID,
          startTime:startTime,
          endTime: endTime
        }),
        cache: false,
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Period saved successfully.',
              message: '',
              position: 'topRight'
            });
             $('#save_gs_periods')[0].reset();
            $('#add_create_period').modal('hide');
            loadTimeTable();
          }else{
            iziToast.error({
              title: 'Period not saved. Please try again later.',
              message: '',
              position: 'topRight'
            });
          }
        }
      });
    });
    $(document).on('click', '#updatePeriodNames', function(event) {
      event.preventDefault();
      periodID=[];startTime=[];endTime=[];
      $("input[name='period_gs_ID']").each(function(i){
        periodID[i]=$(this).val();
      });
      $("input[name='start_gs_Time']").each(function(i){
        if($(this).val()==''){
          swal('Incorrect time result. Please try Again. ', {
            icon: 'error',
          });
        }else{
          startTime[i]=$(this).val();
        } 
      });
      $("input[name='end_gs_Time']").each(function(i){
        if($(this).val()==''){
          swal('Incorrect time result. Please try Again. ', {
            icon: 'error',
          });
        }else{
          endTime[i]=$(this).val();
        }
      });
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Timetable/update_period_names/",
        data: ({
          periodID:periodID,
          startTime:startTime,
          endTime: endTime
        }),
        cache: false,
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Changes saved successfully.',
              message: '',
              position: 'topRight'
            });
          }else{
            iziToast.error({
              title: 'No changes found.',
              message: '',
              position: 'topRight'
            });
          }
        }
      });
    });
    $(document).on('click', '#deletePeriodNames', function(event) {
      event.preventDefault();
      var periods=$(this).attr('value');
      swal({
        title: 'Are you sure you want to delete this period name?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            url:"<?php echo base_url(); ?>Timetable/delete_period_names/",
            method:"POST",
            data:({
              periods: periods
            }),
            success:function(data){
              loadTimeTable();
            }
          })
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#createPeriodNames', function(event) {
    event.preventDefault();  
    var periods=$('#numberperiods').val();
    if($('#numberperiods').val() =='')
    {
      swal('Oooops, Please write something on the provided field!', {
        icon: 'warning',
      });
    }else{
      $.ajax({
        url:"<?php echo base_url(); ?>Timetable/creating_period_names/",
        method:"POST",
        data:({
          periods: periods
        }),
        beforeSend: function() {
          $('.place_periodNames').html( 'Creating...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.place_periodNames').html(data);
        }
      })
    }
  });
</script>
<script type="text/javascript">
  function bootstrapTabControl(){
  var i, items = $('.nav-link'), pane = $('.tab-pane');
  $('.nexttab').on('click', function(){
    for(i = 0; i < items.length; i++){
        if($(items[i]).hasClass('active') == true){
            break;
        }
    }
    if(i < items.length - 1){
        // for tab
        $(items[i]).removeClass('active');
        $(items[i+1]).tab('show');
        // for pane
        $(pane[i]).removeClass('show active');
        $(pane[i+1]).tab('show active');
    }
  });
  // Prev
  $('.prevtab').on('click', function(){
    for(i = 0; i < items.length; i++){
        if($(items[i]).hasClass('active') == true){
            break;
        }
    }
    if(i != 0){
        // for tab
        $(items[i]).removeClass('active');
        $(items[i-1]).tab('show');
        // for pane
        $(pane[i]).removeClass('show active');
        $(pane[i-1]).tab('show active');
    }
  });
}
bootstrapTabControl();

</script>
<script type="text/javascript">
  load_days_data();
  function load_days_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>Timetable/fetch_timetable_days/",
      method:"POST",
      beforeSend: function() {
        $('.fetch_here_timeTableDays').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
      },
      success:function(data){
        $('.fetch_here_timeTableDays').html(data);
      }
    })
  }
  $(document).on('click', '.timetableDaySelection', function() { 
    var timeTabeDays=$(this).attr("value");
    $.ajax({
      url:"<?php echo base_url(); ?>Timetable/save_timeTable_Days/",
      method:"POST",
      data:({
        timeTabeDays: timeTabeDays
      }),
      success:function(data){
        iziToast.success({
          title: 'Changes updated successfully.',
          message: '',
          position: 'topRight'
        });
      }
    })
    
  });
  $(document).on('click', '#generate', function(event) {
    event.preventDefault();  
    id=[];
    var branch=$("#timetableBranch").val();
    $("input[name='days']:checked").each(function(i){
      id[i]=$(this).val();
    });
    if( id.length == 0)
    {
      swal('Oooops, Please select all necessary fields!', {
        icon: 'warning',
      });
    }else{
      $.ajax({
        url:"<?php echo base_url(); ?>Timetable/generatetimeTable",
        method:"POST",
        data:({
          dayss: id,
          branch:branch
        }),
        beforeSend: function() {
          $('#generate').attr("disabled","disabled");
          $('.timeTableHere').html( 'Generating...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#generate').removeAttr("disabled");
          $('.timeTableHere').html(data);
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