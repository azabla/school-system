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
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#addClassMark" role="tab" aria-selected="true"> Add Mark</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#editClassMark" role="tab" aria-selected="true"> Edit Mark</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#viewClassMark" role="tab" aria-selected="true">View Mark</a>
                  </li>
                  <?php if($_SESSION['usertype']===trim('Director')){ ?>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab4" data-toggle="tab" href="#lockClassMark" role="tab" aria-selected="true">Lock Mark</a>
                  </li>
                <?php } ?>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab5" data-toggle="tab" href="#feedAttendance" role="tab" aria-selected="true">Feed Attendance</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab6" data-toggle="tab" href="#attendanceReport" role="tab" aria-selected="true">Attendance Report</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="addClassMark" role="tabpanel" aria-labelledby="home-tab1">
                    <form method="POST" id="addSummerMark">
                      <div class="row">
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="addgradesec" id="addgradesec">
                              <option>--- Grade ---</option>
                              <?php foreach($fetch_grade_fromsp_toadd_neweaxm as $gradesecs){ ?>
                                <option value="<?php echo $gradesecs->grade;?>">
                                <?php echo $gradesecs->grade;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <select class="form-control addsubject" name="addsubject">
                              <option>--- Select Subject ---</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="addevaluation"  id="addevaluation">
                            <option>--- Select Evaluation ---</option> </select>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <input class="form-control"  type="text" name="addassesname" id="addassesname" placeholder="Assesment..." required>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <input class="form-control"  type="number" name="addpercentage" id="addpercentage" placeholder="Percentage" required>
                          </div>
                        </div>
                        <div class="col-lg-2 col-6">
                          <button class="btn btn-primary btn-block btn-lg" type="submit" name="viewmark">start</button>
                        </div>
                      </div>
                    </form> 
                    <div id="listAddSummermark" class="listAddSummermark"></div>
                  </div>
                  <div class="tab-pane fade show" id="editClassMark" role="tabpanel" aria-labelledby="home-tab2">
                    <form method="POST" id="editSummerMark">
                      <div class="row">
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="academicyear" id="academicyear">
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
                            <select class="form-control selectric" required="required" name="gradesec" id="gradesec">
                              <option>--- Select Grade ---</option>
                              <?php foreach($fetch_grade_fromsp_toadd_neweaxm as $gradesecs){ ?>
                                <option value="<?php echo $gradesecs->grade;?>">
                                <?php echo $gradesecs->grade;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control subject" name="subject">
                              <option>--- Select Subject ---</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2 col-6">
                          <button class="btn btn-primary btn-block btn-lg" type="submit" name="viewmark">View</button>
                        </div>
                      </div>
                    </form> 
                    <div id="listSummermark" class="listSummermark"></div>
                  </div>
                  <div class="tab-pane fade show" id="viewClassMark" role="tabpanel" aria-labelledby="home-tab3">
                    <form method="POST" id="viewSummerMark">
                      <div class="row">
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="viewAcademicyear" id="viewAcademicyear">
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
                            <select class="form-control selectric" required="required" name="viewGradesec" id="viewGradesec">
                              <option>--- Select Grade ---</option>
                              <?php foreach($fetch_grade_fromsp_toadd_neweaxm as $gradesecs){ ?>
                                <option value="<?php echo $gradesecs->grade;?>">
                                <?php echo $gradesecs->grade;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control viewSubject" name="viewSubject">
                              <option>--- Select Subject ---</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2 col-6">
                          <button class="btn btn-info btn-block btn-lg" type="submit" name="viewmark">View</button>
                        </div>
                      </div>
                    </form> 
                    <div id="viewSummermark" class="viewSummermark"></div>
                  </div>
                  <div class="tab-pane fade show" id="lockClassMark" role="tabpanel" aria-labelledby="home-tab4">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-primary" type="submit" id="lockThisSummerSubjectMark"> Lock Mark</button>
                        <button class="btn btn-primary" type="submit" id="unlockThisSummerSubjectMark"> Unock Mark</button>
                      </div>
                    </div>
                    <div class="fetchLockedMark"></div>
                  </div>
                  <div class="tab-pane fade show" id="feedAttendance" role="tabpanel" aria-labelledby="home-tab3">
                    <form method="POST" id="comment_formAttendance">
                      <div class="row">
                        <div class="col-lg-6 col-6">
                          <div class="form-group">
                            <select class="form-control selectric grands_gradesecAttendance" required="required" name="grands_gradesecAttendance" id="grands_gradesecAttendance">
                              <option>--- Select Grade ---</option>
                              <?php foreach($fetch_grade_fromsp_toadd_neweaxm as $gradesecs){ ?>
                                <option value="<?php echo $gradesecs->grade;?>">
                                <?php echo $gradesecs->grade;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-6 col-6">
                          <button class="btn btn-info btn-block btn-lg" type="submit" name="viewmark">Submit Attendnace</button>
                        </div>
                      </div>
                    </form> 
                    <div class="studentListSummer"> </div>
                  </div>
                  <div class="tab-pane fade show" id="attendanceReport" role="tabpanel" aria-labelledby="home-tab6">
                     <div class="row">
                        <div class="col-lg-6 col-6">
                        </div>
                        <div class="col-lg-6 col-6">
                          <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyAttendanceReport()">
                            <i data-feather="printer"></i>
                          </button>
                        </div>
                      </div>
                    <div class="fetch_attendance_summer" id="printAttendanceReport"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <form method="POST" id="comment_form_updateSummer">
        <div class="modal fade" id="editmarkSummerTeacher" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Mark Value</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="editmarkhere_gsSummer">
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="updatesubject" class="btn btn-primary savegrandsubject">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <form method="POST" id="comment_form_NGupdate">
        <div class="modal fade" id="editngmarkSummer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit NG Mark</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="editngmarkhere_gs">
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="updatesubject" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      </div>
      <footer class="main-footer">
        <div class="footer-left">
         Call:+251967829025 &nbsp;&nbsp;
          Copyright &copy<?php echo date('Y');?>
          <a href="https://www.grandstande.com" target="_blanck">Grandstand IT Solution Plc</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  function codespeedyAttendanceReport(){
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
  $('#comment_formAttendance').on('submit', function(event) {
    event.preventDefault();
    var attGradesec=$('.grands_gradesecAttendance').val();
    if ($('.grands_gradesecAttendance').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Mysummerclass/fetchStudents4Attendance/",
        method: "POST",
        data: ({
          attGradesec:attGradesec
        }),
        beforeSend: function() {
          $('.studentListSummer').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(data) {
          $(".studentListSummer").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Mysummerclass/fetchAttendanceReport/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_attendance_summer').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success:function(data){
          $('.fetch_attendance_summer').html(data);
        }
      })
    }
    $(document).on('change', '#attendanceDateSummer', function() {
      id=[];
      $("input[name='attendanceStuidSummer']:disabled").each(function(i){
        id[i]= $(this).removeAttr("disabled","disabled");
      });
    }); 
    $(document).on('change', '#attendanceStuidSummer', function() {
      $("#attendanceTypeSummer").removeAttr("disabled","disabled");
    }); 
    $(document).on('change', '#attendanceTypeSummer', function() {
      var abtype=$(this).val();
      if(abtype==='Late'){
        $("#attendanceMinuteSummer").removeAttr("disabled","disabled");
      }else{
        $("#attendanceMinuteSummer").attr("disabled","disabled");
      }
    });
    $(document).on('click', '#saveAttendanceSummer', function() {
      var attendanceDate =$('#attendanceDateSummer').val();
      var attendanceType=$('#attendanceTypeSummer').val();
      var attendanceMinute=$('#attendanceMinuteSummer').val();
      stuid=[];
      $("input[name='attendanceStuidSummer']:checked").each(function(i){
        stuid[i]=$(this).val();
      });
      if($('#attendanceDateSummer').val()!='' && $('#attendanceTypeSummer').val()!='' && stuid.length!=0 ){
        if($('#attendanceTypeSummer').val()==='Late'){
          if($('#attendanceMinuteSummer').val()!==''){
            $.ajax({
              method: "POST",
              url: "<?php echo base_url(); ?>Mysummerclass/saveAttendance/",
              data: ({
                stuid: stuid,
                attendanceDate:attendanceDate,
                attendanceType:attendanceType,
                attendanceMinute:attendanceMinute
              }),
              cache: false,
              success: function(html){
                load_data();
                iziToast.success({
                  title: 'Attendance',
                  message: 'Updated successfully',
                  position: 'bottomCenter'
                });
              }
            });
          }else{
            alert('Please insert minute to late attendance.');
          }
        }else{
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Mysummerclass/saveAttendance/",
            data: ({
              stuid: stuid,
              attendanceDate:attendanceDate,
              attendanceType:attendanceType,
              attendanceMinute:attendanceMinute
            }),
            cache: false,
            success: function(html){
              load_data();
              iziToast.success({
                title: 'Attendance',
                message: 'Updated successfully',
                position: 'bottomCenter'
              });
            }
          });
        }
      }else{
        alert('Please select necessary fields.')
      }
    }); 
  });
  $(document).on('click', '.deleteThisAttendaneSummer', function() {
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
          url: "<?php echo base_url(); ?>Mysummerclass/deleteAttendanceSummer/",
          data: ({
            attendanceId: attendanceId
          }),
          cache: false,
          success: function(html) {
            $(".deleteSummerAttendane" + attendanceId).fadeOut('slow');
          }
        });
      }else {
        return false;
      }
    });
  });
</script>
<script>
  $(document).on('click', '#lockThisSummerSubjectMark', function() {
      event.preventDefault();
      /*var branch=$('#lockSummerMarkBranch').val();
      var gradesec=$('#lockSummerMarkGradesec').val();
      var subject=$('#lockSummerMarkSubject').val();*/
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Mysummerclass/lockThisSummerMark/",
        beforeSend: function() {
          $('.fetchLockedMark').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".fetchLockedMark").html(data);
        }
      });
    });
  $(document).on('click', '#unlockThisSummerSubjectMark', function() {
      event.preventDefault();
      /*var branch=$('#lockSummerMarkBranch').val();
      var gradesec=$('#lockSummerMarkGradesec').val();
      var subject=$('#lockSummerMarkSubject').val();*/
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Mysummerclass/unlockThisSummerMark/",
        beforeSend: function() {
          $('.fetchLockedMark').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".fetchLockedMark").html(data);
        }
      });
    });
  $('#addSummerMark').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$("#addgradesec").val();
    var subject=$(".addsubject").val();
    var evaluation=$("#addevaluation").val();
    var assesname=$("#addassesname").val();
    var percentage=$("#addpercentage").val();
    if($("#subject").val()!=''){
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Mysummerclass/studentResultForm/",
      data: ({
        gradesec:gradesec,
        subject:subject,
        evaluation:evaluation,
        assesname:assesname,
        percentage:percentage
      }),
      cache: false,
      beforeSend: function() {
        $('.listAddSummermark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="54" height="54" id="loa">' );
      },
      success: function(html){
        $('.listAddSummermark').html(html);
      }
    });
    }else{
    alert('Please select all fields!');
  }
  });
  $(document).on('click', '.edit_NGmark_gsSummerTeacher', function() {
    var stuid=$(this).attr("title");
    var evaid=$(this).attr("value");
    var subject=$(".jo_subjectSummerTeacher").val();
    var quarter=$(".jo_quarter").val();
    var markname=$(this).attr("name");
    var outof=$(this).attr("id");
    var gradesec=$(".jo_gradesecSummerTeacher").val();
    var academicyear=$(".jo_yearSummerTeacher").val();
    var branch=$('.jo_branchSummerTeacher').val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Mysummerclass/fecthNgMarkToEdit_summer/",
      data: ({
        stuid: stuid,
        subject:subject,
        quarter:quarter,
        gradesec:gradesec,
        academicyear:academicyear,
        markname:markname,
        outof:outof,
        evaid:evaid,
        branch:branch
      }),
      cache: false,
      beforeSend: function() {
        $('#editngmarkhere_gs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('#editngmarkhere_gs').html(html);
      }
    });
  });
  $('#comment_form_NGupdate').on('submit', function(event) {
    event.preventDefault();
    var my_eva=$(".my_evaSummer").val();
    var stuid=$(".my_studentSummer").val();
    var subject=$(".my_subjectSummer").val();
    var quarter=$(".my_quarterSummer").val();
    var year=$(".my_yearSummer").val();
    var gradesec=$(".my_gradeSecSummer").val();
    var val =$(".correct_ngmark_gsSummer").val();
    var markname =$(".my_markNameHSummer").val();
    var outof=$(".my_outOfSummer").val();
    var my_studentBranch=$(".my_BranchSummer").val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Mysummerclass/updateNgMarkNow/",
      data: ({
        my_eva: my_eva,
        stuid:stuid,
        subject:subject,
        quarter:quarter,
        year:year,
        val:val,
        gradesec:gradesec,
        markname:markname,
        outof:outof,
        my_studentBranch:my_studentBranch
      }),
      cache: false,
      beforeSend: function() {
        $('.info-ngmarkSummer').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.info-ngmarkSummer').html(html);
        $('.JoMarkSummer'+stuid+markname).html(val);
      }
    });
  });
</script>
<script>
  function chkMarkValue(){
    var stuid=$("#stuidSummer").val();
    var chkPercent=parseInt($("#Sumpercentage").val());
    var markResult=$("#resultvalueSummer").val();
    $("input[name='markvalue_resultSummer']").each(function(i){
      resultvalue=parseInt($(this).val());
      if(resultvalue > chkPercent){
        alert('Incorrect Mark result. Please try Again.');
      }
    });
  }
  $(document).on('click', '#SaveResultSummer', function() {
    var stuid=$("#stuidSummer").val();
    var resultvalue=$("#resultvalueSummer").val();
    var academicyear=$("#Sumacademicyear").val();
    var subject=$("#Sumsubject").val();
    var evaluation=$("#Sumevaluation").val();
    var assesname=$("#Sumassesname").val();
    var percentage=$("#Sumpercentage").val();
    var markGradeSec=$("#SummarkGradeSec").val();
    stuid=[];resultvalue=[];
      $("input[name='stuid_resultSummer']").each(function(i){
        stuid[i]=$(this).val();
      });
      $("input[name='markvalue_resultSummer']").each(function(i){
        resultvalue[i]=$(this).val();
      });
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Mysummerclass/addNewresult/",
      data: ({
        stuid:stuid,
        resultvalue:resultvalue,
        academicyear: academicyear,
        subject:subject,
        evaluation:evaluation,
        assesname:assesname,
        percentage:percentage,
        markGradeSec:markGradeSec
      }),
      cache: false,
      beforeSend: function() {
        $('.listAddSummermark').html( '<h3><span class="text-success">Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa"></span></h3>' );
      },
      success: function(html){
        $('.listAddSummermark').html(html);
      }
    });
  });
</script>
<script type="text/javascript">
 $(document).ready(function() {  
    $("#addgradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Mysummerclass/filterSummerEvaluation/",
        data: "gradesec=" + $("#addgradesec").val(),
         beforeSend: function() {
          $('#addevaluation').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#addevaluation").html(data);
        }
      });
    });
 });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#addgradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Mysummerclass/FilterSummerSubjectFromStaff/",
        data: "gradesec=" + $("#addgradesec").val(),
        beforeSend: function() {
            $('.addsubject').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".addsubject").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $('#viewSummerMark').on('submit', function(event) {
    event.preventDefault();
    var gs_gradesec=$('#viewGradesec').val();
    var gs_subject=$('.viewSubject').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Mysummerclass/fecthSummerMarkresult/",
        method: "POST",
        data: ({
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject
        }),
        beforeSend: function() {
          $('.viewSummermark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="64" height="64" id="loa">' );
        },
        success: function(data) {
          $(".viewSummermark").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#viewGradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Mysummerclass/FilterSummerSubjectFromStaff/",
        data: "gradesec=" + $("#viewGradesec").val(),
        beforeSend: function() {
            $('.viewSubject').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".viewSubject").html(data);
        }
      });
    });
  });
</script>
<script>
  $('#comment_form_updateSummer').on('submit', function(event) {
    event.preventDefault();
    load_mark();
    var outof=$(".outofSummer").val();
    var mid=$(".midSummer").val();
    var value=$(".correct_mark_gsSummer").val();
    var gradesec=$(".gSecSummer").val();
    var year=$(".aYearSummer").val();
    var branch=$(".gsBranchSummer").val();
    function load_mark(){
      $.ajax({
        method:"POST",
        url:"<?php echo base_url() ?>Mysummerclass/FetchUpdatedMark/",
        data: ({
          mid: mid,
          gradesec:gradesec,
          year:year,
          branch:branch
        }),
        cache: false,
        beforeSend: function() {
          $('.jossMarkSummer'+mid).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
          );
        },
        success:function(html){
          $('.jossMarkSummer' + mid).html(html);
          //$('.fade').fadeOut('slow');
        }
      });
    }
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Mysummerclass/updateMarkNow/",
        data: ({
          mid: mid,
          outof:outof,
          value:value,
          gradesec:gradesec,
          year:year,
          branch:branch
        }),
        cache: false,
        beforeSend: function() {
          $('.info-markSummer').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.info-markSummer').html(html);
          load_mark();
        }
      });
  });
</script>
<script>
  $(document).on('click', '.edit_mark_gsSummerTeacher', function() {
      var edtim=$(this).attr("value");
      var gradesec=$('.jo_gradesecSummerTeacher').val();
      var academicyear=$('.jo_yearSummerTeacher').val();
      var branch=$('.jo_branchSummerTeacher').val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Mysummerclass/fetchMarkToEdit/",
        data: ({
          edtim: edtim,
          gradesec:gradesec,
          academicyear:academicyear,
          branch:branch
        }),
        cache: false,
        beforeSend: function() {
          $('#editmarkhere_gsSummer').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('#editmarkhere_gsSummer').html(html);
        }
    });
  });
</script>
<script>
  $(document).on('click', '.gs_delete_marknameSummerTeacher', function() {
    if(confirm('Are you sure you want to delete this mark?')){
      var subject=$(".jo_subjectSummerTeacher").val();
      var gradesec=$(".jo_gradesecSummerTeacher").val();
      var branch=$(".jo_branchSummerTeacher").val();
      var year=$(".jo_yearSummerTeacher").val();
      var markname=$(this).attr("value");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Mysummerclass/deleteMarkName/",
        data: ({
          subject: subject,
          gradesec:gradesec,
          branch:branch,
          year:year,
          markname: markname
        }),
        cache: false,
        beforeSend: function() {
          $('.listSummermark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.listSummermark').html(html);
        }
      }); 
    }else{
      return false;
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#gradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Mysummerclass/FilterSummerSubjectFromStaff/",
        data: "gradesec=" + $("#gradesec").val(),
        beforeSend: function() {
            $('.subject').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".subject").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $('#editSummerMark').on('submit', function(event) {
    event.preventDefault();
    var gs_gradesec=$('#gradesec').val();
    var gs_subject=$('.subject').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Mysummerclass/fecthSummerGradeMarkTeacher/",
        method: "POST",
        data: ({
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject
        }),
        beforeSend: function() {
          $('.listSummermark').html( '<h3>Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="84" height="84" id="loa"></h3>' );
        },
        success: function(data) {
          $(".listSummermark").html(data);
        }
      })
    }else {
      alert("All fields are required");
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