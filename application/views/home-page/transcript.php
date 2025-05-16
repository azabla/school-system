<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <?php include('bgcolor.php'); ?>
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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
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
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <div class="row">
                      <div class="col-md-12 col-12">
                        <h5 class="card-title">Student Transcript</h5>
                      </div>
                    </div>
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#ReasonForIssue" role="tab" aria-selected="true">Reason for Issue</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#editIssueReason" role="tab" aria-selected="true"> Edit Reason</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#defaultTranscript" role="tab" aria-selected="true"> Default Transcript</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab4" data-toggle="tab" href="#customTranscript" role="tab" aria-selected="false">Custom Transcript</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="ReasonForIssue" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="alert alert-light alert-dismissible show fade">
                          <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                              <span>&times;</span>
                            </button>
                            Note: Create issue reason other than "Completed Grade" which is default for all students.
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-6 col-12">
                            <div class="form-group">
                              <input type="text" name="reasonForIssue" id="reasonForIssue" class="form-control" placeholder="Type Reason..." required>
                            </div>
                          </div>
                          <div class="col-lg-6 col-12">
                            <div class="form-group">
                              <button class="btn btn-outline-primary btn-block" type="submit" name="saveReason" id="saveReason"> Save Reason </button>
                            </div>
                          </div>
                        </div>
                        <div class="viewReasonIssue"></div>
                      </div>
                      <div class="tab-pane fade show" id="editIssueReason" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row">
                          <div class="col-lg-3 col-6">
                            <div class="form-group">
                             <select class="form-control selectric"
                             required="required" name="academicyearsEditReason" id="academicyearsEditReason">
                             <option>--- Select Year ---</option>
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
                             <select class="form-control selectric"
                             required="required" name="branchEditReason" id="branchEditReason">
                             <option>--- Select branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                          </div>
                          <div class="col-md-3 col-6">
                            <div class="form-group">
                              <select class="form-control"
                               required="required" name="gradeEditReason" id="gradeEditReason">
                               <option>--Select Grade--</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-2 col-6">
                            <button class="btn btn-primary btn-block viewStudent">View Student
                            </button>
                          </div>
                        </div>
                        <div class="editIssueReasonGS" id="editIssueReasonGS"> </div>
                      </div>
                      <div class="tab-pane fade show" id="defaultTranscript" role="tabpanel" aria-labelledby="home-tab3">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                          <span class="text-black"> <i data-feather="printer"></i> </span>
                        </button>
                        <div class="row">
                          <div class="col-lg-2 col-6">
                            <div class="form-group">
                             <select class="form-control selectric"
                             required="required" name="branch" id="tsAcademicyears">
                             <option>--- Year ---</option>
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
                             <select class="form-control selectric"
                             required="required" name="branch" id="branch2progress">
                             <option>--- Select branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                          </div>
                          <div class="col-md-2 col-6">
                            <div class="form-group">
                              <select class="form-control"
                               required="required" name="grade2progress" id="grade2progress">
                               <option>--Select Grade--</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-2 col-6">
                            <div class="form-group">
                              <select class="form-control" required="required" name="noGrade" id="noGrade">
                               <option>--No of Grade--</option>
                               <?php for($i=1;$i<=5;$i++){?>
                                <option><?php echo $i; ?></option>
                               <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3 col-12">
                            <input type="checkbox" name="includeLetterTranscript" id="includeLetterTranscript" value="1"> Letter Transcript
                            <button class="btn btn-primary btn-block viewtranscript">View Transcript
                            </button>
                          </div>
                        </div>
                        <div class="lisTranscript" id="helloTranscript"> </div>
                      </div>
                      <div class="tab-pane fade show" id="customTranscript" role="tabpanel" aria-labelledby="home-tab4">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyCustom()">
                          <span class="text-black"> <i data-feather="printer"></i> </span>
                        </button>
                        <div class="row">
                          <div class="col-lg-2 col-6">
                            <div class="form-group">
                             <select class="form-control selectric"
                             required="required" name="academicYearCustom" id="academicYearCustom">
                             <option>--- Year ---</option>
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
                             <select class="form-control selectric"
                             required="required" name="branchCustom" id="branchCustom">
                             <option>--- Branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                          </div>
                          <div class="col-md-3 col-6">
                            <div class="form-group">
                              <select class="form-control"
                               required="required" name="gradeCustom" id="gradeCustom">
                               <option>--Select Grade--</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-2 col-6">
                            <div class="form-group">
                              <select class="form-control" required="required" name="noGradeCustom" id="noGradeCustom">
                               <option>--No of Grade--</option>
                               <?php for($i=1;$i<=5;$i++){?>
                                <option><?php echo $i; ?></option>
                               <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-2 col-12">
                            <input type="checkbox" name="includeLetterCustomTranscript" id="includeLetterCustomTranscript" value="1"> Letter Transcript
                            <button class="btn btn-primary btn-block viewCustomtranscript" id="viewCustomtranscript">View Student
                            </button>
                          </div>
                        </div>
                        <div class="lisTCustomTranscript" id="lisTCustomTranscript"> </div>
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
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#tsAcademicyears").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>transcript/filterGradesecfromBranch/",
        data: "academicyear=" + $("#tsAcademicyears").val(),
        beforeSend: function() {
          $('#branch2progress').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#branch2progress").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#academicYearCustom").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>transcript/filterGradesecfromBranch/",
        data: "academicyear=" + $("#academicYearCustom").val(),
        beforeSend: function() {
          $('#branchCustom').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#branchCustom").html(data);
        }
      });
    });
  });
  $(document).on('click', '#viewCustomtranscript', function() {
    var gradesec=$('#gradeCustom').val();
    var branch=$('#branchCustom').val();
    var academicyear=$('#academicYearCustom').val();
    if ($('#gradeCustom').val() != '' && $('#branchCustom').val() != '' && $('#noGradeCustom').val()!='--No of Grade--' ) {
      $.ajax({
        url: "<?php echo base_url(); ?>transcript/fetchStudentforCustom/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          branch:branch,
          academicyear:academicyear
        }),
        dataType: 'json',
        beforeSend: function() {
          $('.lisTCustomTranscript').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success: function(data) {
          $(".lisTCustomTranscript").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
   $(document).on('click', '.printThisStudentTranscript', function() {
    if($('#includeLetterCustomTranscript').is(':checked')){
      var includeBackPage='1';
    }else{
      var includeBackPage='0';
    }
    var id=$(this).attr("name");
    var username=$(this).attr("value");
    var reportaca=$(this).attr("id");
    var noGradeCustom=$('#noGradeCustom').val();
    $.ajax({
      url: "<?php echo base_url(); ?>transcript/fetchThisStudentTranscript/",
      method: "POST",
      data: ({
        id:id,
        username:username,
        reportaca:reportaca,
        includeBackPage:includeBackPage,
        noGrade:noGradeCustom
      }),
      beforeSend: function() {
        $('.lisTCustomTranscript').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data) {
        $(".lisTCustomTranscript").html(data);
      }
    })
  });
  $(document).ready(function() {  
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>transcript/fetchReasonIssue/",
        method:"POST",
        beforeSend: function() {
          $('.viewReasonIssue').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.viewReasonIssue').html(data);
        }
      })
    }
    $('#saveReason').on('click', function(event) {
      event.preventDefault();
      var reasonIssue=$('#reasonForIssue').val();
      if ($('#reasonForIssue').val() != '') {
        $.ajax({
          url: "<?php echo base_url(); ?>transcript/saveReasonIssue/",
          method: "POST",
          data: ({
            reasonIssue: reasonIssue
          }),
          beforeSend: function() {
            $('#reasonForIssue').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            load_data();
            $('#reasonForIssue').val('')
          }
        })
      }
    });
    $(document).on('click', '#deleteLeavingIssue', function() {
      var reasonIssueID=$(this).attr("value");
     swal({
        title: 'Are you sure?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          
          $.ajax({
            url: "<?php echo base_url(); ?>transcript/deleteReasonIssue/",
            method: "POST",
            data: ({
              reasonIssueID: reasonIssueID
            }),
            beforeSend: function() {
              $('#reasonForIssue').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              load_data();
              $('#reasonForIssue').val('')
            }
          })
        }
      });
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
  function codespeedy(){
    var print_div = document.getElementById("helloTranscript");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  function codespeedyCustom(){
    var print_div = document.getElementById("lisTCustomTranscript");
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
    $("#branch2progress").bind("change", function() {
      var branchit=$("#branch2progress").val();
      var academicyear=$("#tsAcademicyears").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>transcript/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#grade2progress').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grade2progress").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branchEditReason").bind("change", function() {
      var branchit=$("#branchEditReason").val();
      var academicyear=$("#academicyearsEditReason").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>transcript/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#gradeEditReason').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradeEditReason").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branchCustom").bind("change", function() {
      var branchit=$("#branchCustom").val();
      var academicyear=$("#academicYearCustom").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>transcript/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#gradeCustom').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradeCustom").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $('.viewtranscript').on('click', function(event) {
    event.preventDefault();
    var academicyear=$('#tsAcademicyears').val();
    var branch=$('#branch2progress').val();
    var gradesec=$('#grade2progress').val();
    var noGrade=$('#noGrade').val();
    if($('#includeLetterTranscript').is(':checked')){
      var includeLetterTranscript='1';
    }else{
      var includeLetterTranscript='0';
    }
    if ($('#gradesec_tr').val() != '' && $('#noGrade').val()!='--No of Grade--' ) {
      $.ajax({
        url: "<?php echo base_url(); ?>transcript/fetchtranscript/",
        method: "POST",
        data: ({
          academicyear: academicyear,
          branch:branch,
          gradesec:gradesec,
          noGrade:noGrade,
          includeLetterTranscript:includeLetterTranscript
        }),
        beforeSend: function() {
          $('.lisTranscript').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".lisTranscript").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).on('change', '#setleavingreason', function() {
    var id=$(this).find('option:selected').attr('value');
    var reasonIssue=$(this).find('option:selected').attr('name');
    var stuid=$(this).find('option:selected').attr('class');
    var academicyear=$(this).find('option:selected').attr('title');
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>transcript/updateStudentLeavingStatus/",
      data: ({
        id:id,
        reasonIssue:reasonIssue,
        stuid:stuid,
        academicyear:academicyear
      }),
      success: function(data) {
        iziToast.success({
          title: 'Reason Issue updated successfully.',
          message: '',
          position: 'topRight'
        });
      }
    });
  });
  $('.viewStudent').on('click', function(event) {
    event.preventDefault();
    var academicyear=$('#academicyearsEditReason').val();
    var branch=$('#branchEditReason').val();
    var gradesec=$('#gradeEditReason').val();
    if ($('#gradesec_tr').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>transcript/fetchStudentForEdit/",
        method: "POST",
        data: ({
          academicyear: academicyear,
          branch:branch,
          gradesec:gradesec
        }),
        beforeSend: function() {
          $('.editIssueReasonGS').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".editIssueReasonGS").html(data);
        }
      })
    }else {
      alert("All fields are required");
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