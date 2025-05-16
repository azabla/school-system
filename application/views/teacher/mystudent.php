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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
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
              <div class="col-lg-12">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <!-- <div class="row">
                      <div class="col-lg-12 col-12">
                        <input type="text" name="searchStudent" id="searchStudent" class="form-control typeahead" placeholder="Search Student (Name, Id , Grade . . . ) ">
                      </div>
                    </div> -->
                    <form method="POST" id="comment_form">
                      <div class="row">
                        <div class="col-lg-6 col-6">
                          <div class="form-group">
                            <select class="form-control grands_gradesec" required="required" name="gradesec" id="grands_gradesec">
                            <option>--- Grade ---</option> 
                              <?php foreach($gradesec as $gradesecs) { ?>
                                <option value="<?php echo $gradesecs->grade;?>">
                                 <?php echo $gradesecs->grade;?>
                                </option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                       <div class="col-lg-6 col-6">
                        <button class="btn btn-primary bg-info btn-block" 
                        type="submit" name="viewmark">View Student</button>
                      </div>
                    </div>
                  </form>
                  <div class="myliststudent" id="student_view"></div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="IncidentReport" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Incident Report Page</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="saveNewIncident_place_here"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="PreviousReport" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Previous Report History</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="previous_incident_report"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<!-- Grade change script starts-->
<script type="text/javascript">
  $(document).ready(function() { 
    $(document).on('change', '#incidentTypeCategoryChoose', function() { 
      var incidentCategory=$("#incidentTypeCategoryChoose").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mystudent/fetch_this_incidentform_type/",
        data: ({
          incidentCategory:incidentCategory
        }),
        beforeSend: function() {
          $('.page_for_incident_type').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(data) {
          $(".page_for_incident_type").html(data);
        }
      });
    });
    $(document).on('change', '#incidentTypeCategoryChoose', function() { 
      var incidentCategory=$("#incidentTypeCategoryChoose").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mystudent/fetch_this_incidentform_type_level/",
        data: ({
          incidentCategory:incidentCategory
        }),
        beforeSend: function() {
          $('.page_for_incident_type_level').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(data) {
          $(".page_for_incident_type_level").html(data);
        }
      });
    });
  });
  $(document).on('submit', '.save_new_incident_teacher_side', function(event) {
    event.preventDefault();
    incident_type=[];
    $("input[name='setAsIncident_Info']:checked").each(function(i){
      incident_type[i]=$(this).val();
    });
    var incident_teacher=$('#incident_teacher').val();
    var incident_student=$('#incident_student').val();
    var incident_date=$('#incident_date').val();
    var incidentTypeCategoryChoose=$('#incidentTypeCategoryChoose').val();
    var admin_action=$('#admin_action').val();
    var date_suspension_inschool=$('#date_suspension_inschool').val();
    var reentry_date_inschool=$('#reentry_date_inschool').val();
    var date_suspension_outschool=$('#date_suspension_outschool').val();
    var reentry_date_outschool=$('#reentry_date_outschool').val();
    var incident_location=$('#incident_location').val();
    var incident_description=$('#incident_description').val();
    var is_offense=$('#is_offense').val();
    var previous_conse=$('#previous_conse').val();
     $('input').attr('required', true);
    if(incident_type.length!=0) {
      swal({
        title: 'Are you sure?',
        text: 'Once send,you can not edit or delete this report!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            url: "<?php echo base_url(); ?>mystudent/save_incident/",
            method: "POST",
            data: ({
              incident_teacher:incident_teacher,
              incident_student:incident_student,
              incident_date:incident_date,
              incidentTypeCategoryChoose:incidentTypeCategoryChoose,
              admin_action:admin_action,
              date_suspension_inschool:date_suspension_inschool,
              reentry_date_inschool:reentry_date_inschool,
              date_suspension_outschool:date_suspension_outschool,
              reentry_date_outschool:reentry_date_outschool,
              incident_description:incident_description,
              incident_location:incident_location,
              is_offense:is_offense,
              incident_type:incident_type,
              previous_conse:previous_conse
            }),
            beforeSend: function() {
              $('#save_incident').html( 'Saving...');
              $('#save_incident').attr( 'disabled','disabled');
            },
            success: function(data) {
              if(data=='1'){
                iziToast.success({
                  title: 'Incident recorded successfully',
                  message: '',
                  position: 'topRight'
                });
                $('.save_new_incident_teacher_side')[0].reset();
                $('#IncidentReport').modal('hide');
              }else{
                iziToast.error({
                  title: 'Oooops Please try again.',
                  message: '',
                  position: 'topRight'
                });
              }
              $('#save_incident').html( 'Submit Incident');
              $('#save_incident').removeAttr( 'disabled');
            }
          })
        }
      });
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
  $(document).on('click', '#IncidentReportPage', function(event) {
    event.preventDefault();
    var username=$(this).attr('value');
    if ($('.grands_gradesec').val() != '--- Grade ---') {
      $.ajax({
        url: "<?php echo base_url(); ?>mystudent/reportIncident_student/",
        method: "POST",
        data: ({
          username: username
        }),
        beforeSend: function() {
          $('.saveNewIncident_place_here').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".saveNewIncident_place_here").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
  $(document).on('click', '#PreviousReportPage', function(event) {
    event.preventDefault();
    var username=$(this).attr('value');
    $.ajax({
      url: "<?php echo base_url(); ?>mystudent/previous_incident_report/",
      method: "POST",
      data: ({
        username: username
      }),
      beforeSend: function() {
        $('.previous_incident_report').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(data) {
        $(".previous_incident_report").html(data);
      }
    })
  });
  function codespeedy(){
    var print_div = document.getElementById("student_view");
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
    $('#searchStudent').on("keyup",function() {
      $searchItem=$('#searchStudent').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mystudent/searchStudent/",
        data: "searchItem=" + $("#searchStudent").val(),
        beforeSend: function() {
          $('.myliststudent').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".myliststudent").html(data);
        }
      });
    });
  });
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
<!-- Grade change script ends -->
<script type="text/javascript">
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var gs_branches=$('#grands_branchit').val();
    var gs_gradesec=$('.grands_gradesec').val();
    var grands_academicyear=$('#grands_academicyear').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>mystudent/Fecth_thistudent/",
        method: "POST",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          grands_academicyear:grands_academicyear
        }),
        beforeSend: function() {
          $('.myliststudent').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".myliststudent").html(data);
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
<script type="text/javascript">
  $(document).on('click', '#downloadStuData', function() {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>mystudent/downloadStuData/",
        cache: false,
        beforeSend: function() {
          $('#downloadStuData').html( 'Downloading...');
        },
        success: function(html) {
          $("#downloadStuData").html('Download Finished.');
          window.open('<?php echo base_url(); ?>mystudent/downloadStuData/','_blanck');
        }
      });
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