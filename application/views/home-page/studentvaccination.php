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
  <link rel='shortcut icon' type='image/x-icon'
   href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
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
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                    <span class="text-black">
                      <i data-feather="printer"></i>
                    </span>
                    </button>
                    <button type="submit" name="addnew" class="btn btn-outline-dark btn-sm pull-right" data-toggle="modal" data-target="#newVaccination" > <i class="fas fa-plus"></i> Add Vaccination
                    </button> 
                  </div>
                </div>
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="card">
                  <div class="StudentViewTextInfo">
                    <div class="row">
                      <div class="col-lg-3 col-6">
                         <div class="form-group">
                           <select class="form-control selectric" required="required" name="academicyear" id="grands_academicyear">
                            <option>--Year--</option>
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
                           <select class="form-control" required="required" name="branch" id="grands_branchit">
                           <option>--- Branch ---</option>
                           
                           </select>
                          </div>
                         </div>
                         <div class="col-lg-3 col-6">
                          <div class="form-group">
                           <select class="form-control grands_grade" name="grands_grade" id="grands_grade">
                           <option>--- Grade ---</option>
                          </select>
                          </div>
                         </div>
                       <div class="col-lg-3 col-6">
                        <button class="btn btn-primary btn-block" 
                        type="submit" id="fetchStudent" name="viewmark">View Student</button>
                      </div>
                    </div>
                    <div class="listStudentShow" id="student_view"></div>
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
  <div class="modal fade" id="newVaccination" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>New Vaccination Type</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <form method="POST" id="saveNewFormVaccination" class="saveNewFormVaccination" name="saveNewFormVaccination">
            <div class="form-group">
              <div class="search-element">
                <div class="row">
                  <div class="form-group col-lg-6 col-6">
                    <input id="vaccinationName" type="text" class="form-control" required="required" name="vaccinationName" placeholder="Vaccination name here...">
                  </div>
                  <div class="form-group col-lg-6 col-6">
                    <button class="btn btn-primary btn-block" name="save_vaccination" id="save_vaccination">
                      <i class="fas fa-save"></i> Save
                    </button>
                  </div>
                </div>
                <h4 class="msg" id="msg"></h4>
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
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  $(document).on('click', '#viewstudent_vaccination', function(event) {
    event.preventDefault();
    var username=$(this).attr('value');
    $.ajax({
      url: "<?php echo base_url(); ?>studentvaccination/previous_vaccination_report/",
      method: "POST",
      data: ({
        username: username
      }),
      beforeSend: function() {
        $('.listStudentShow').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(data) {
        $(".listStudentShow").html(data);
      }
    })
  });
  $(document).on('click', '#viewstudent_illnessReport', function(event) {
    event.preventDefault();
    var username=$(this).attr('value');
    $.ajax({
      url: "<?php echo base_url(); ?>studentvaccination/previous_incident_report/",
      method: "POST",
      data: ({
        username: username
      }),
      beforeSend: function() {
        $('.listStudentShow').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(data) {
        $(".listStudentShow").html(data);
      }
    })
  });
  $(document).on('submit', '#saveNewFormVaccination', function(e) {
    e.preventDefault();
    if ($('#vaccinationName').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>studentvaccination/save_new_vaccination/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#msg').html( '<span class="text-info">Saving...</span>');
        },
        success: function(html){
          $("#msg").html(html);
          $('#vaccinationName').val('');
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
</script>
 <script type="text/javascript">
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

<!-- Grade change script starts-->
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_academicyear").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>studentvaccination/filterGradesecfromBranch/",
        data: "academicyear=" + $("#grands_academicyear").val(),
        beforeSend: function() {
          $('#grands_branchit').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $("#grands_branchit").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_branchit").bind("change", function() {
      var branchit=$('#grands_branchit').val();
      var grands_academicyear=$('#grands_academicyear').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>studentvaccination/filterOnlyGradeFromBranch/",
        data: ({
            branchit: branchit,
            grands_academicyear:grands_academicyear
        }),
        beforeSend: function() {
          $('.grands_grade').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $(".grands_grade").html(data);
        }
      });
    });
  });
</script>
<!-- Grade change script ends -->
<script type="text/javascript">
  $(document).on('click', '#fetchStudent', function() {
    event.preventDefault();
    var gs_branches=$('#grands_branchit').val();
    var gs_gradesec=$('.grands_gradesec').val();
    var onlyGrade=$('.grands_grade').val();
    var grands_academicyear=$('#grands_academicyear').val();
    if ($('.grands_gradesec').val() != '' || $('.grands_grade').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>studentvaccination/fecth_this_tudent/",
        method: "POST",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          onlyGrade:onlyGrade,
          grands_academicyear:grands_academicyear
        }),
        beforeSend: function() {
          $('.listStudentShow').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $(".listStudentShow").html(data);
        }
      })
    }else {
      swal('All fields are required.', {
        icon: 'error',
      });
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
  $(document).on('click', '.editstudent_illnessReport', function() {
    var editedId = $(this).attr("value");
    var newAcademicYear=$(this).attr("name");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>studentvaccination/editstudent_illnessReport/",
      data: ({
        editedId: editedId,
        newAcademicYear:newAcademicYear
      }),
      cache: false,
      beforeSend: function() {
        $('.listStudentShow').html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="24" height="24" id="loa">');
      },
      success: function(html) {
        $(".listStudentShow").html(html);
      }
    });
  });
  $(document).on('click', '#updateStuForm_Illness', function(e) {
    e.preventDefault();
    var Illness_Cause=$('.Illness_Cause').val();
    var date_of_checkup=$('.date_of_checkup').val();
    var action_Taken=$('.action_Taken').val();
    var stuAcademicYear_illness=$('.stuAcademicYear_illness').val();
    var stuStuid_illness=$('.stuStuid_illness').val();
    var stUsername_illness=$('.stUsername_illness').val();
    if($('.action_Taken').val()=='' || $('.Illness_Cause').val()=='' || $('.date_of_checkup').val()==''){
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }else{
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>studentvaccination/updateStudents_Illness/",
        data: ({
          stuAcademicYear_illness:stuAcademicYear_illness,
          stuStuid_illness:stuStuid_illness,
          stUsername_illness:stUsername_illness,
          Illness_Cause: Illness_Cause,
          date_of_checkup:date_of_checkup,
          action_Taken:action_Taken
        }),
        success: function(html){
          iziToast.show({
            title: html,
            message: '',
            position: 'topRight'
          });
        }
      });
    }
  });
  $(document).on('click', '.editstudent_vaccination', function() {
    var editedId = $(this).attr("value");
    var newAcademicYear=$(this).attr("name");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>studentvaccination/editstudent_vaccination/",
      data: ({
        editedId: editedId,
        newAcademicYear:newAcademicYear
      }),
      cache: false,
      beforeSend: function() {
        $('.listStudentShow').html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="24" height="24" id="loa">');
      },
      success: function(html) {
        $(".listStudentShow").html(html);
      }
    });
  });
</script>
<script type="text/javascript">
  function codespeedyStudentView(){
    var print_div = document.getElementById("StudentViewPrintHere");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  function codespeedyStudentLeaving(){
    var print_div = document.getElementById("PrintStudentRequestPaper");
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
  $(document).on('submit', '#updateStuForm_Vaccination', function(e) {
    e.preventDefault();
    vaccination_Name=[];
    $("input[name='setAsVaccination_Info']:checked").each(function(i){
      vaccination_Name[i]=$(this).val();
    });
    var Hospital_Adress=$('.Hospital_Adress').val();
    var Hospital_Contact_Person=$('.Hospital_Contact_Person').val();
    var Permit_Exceptional_Case = $("input[name='Permit_Exceptional_Case']:checked").val();
   /* var Permit_Exceptional_Case=$('.Permit_Exceptional_Case').val();*/
    var Hospital_Phone=$('.Hospital_Phone').val();
    var Hospital_Name=$('.Hospital_Name').val();
    var Hospital_Email=$('.Hospital_Email').val();
    var Needs_Disabilities=$('.Needs_Disabilities').val();
    var Allergies_Medications_Conditions=$('.Allergies_Medications_Conditions').val();
    var stuAcademicYear_vaccination=$('.stuAcademicYear_vaccination').val();
    var stuStuid_vaccination=$('.stuStuid_vaccination').val();
    var stUsername_Vaccination=$('.stUsername_Vaccination').val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>studentvaccination/updateStudents_Vaccination/",
      data: ({
        stuAcademicYear_vaccination:stuAcademicYear_vaccination,
        stuStuid_vaccination:stuStuid_vaccination,
        stUsername_Vaccination:stUsername_Vaccination,
        vaccination_Name: vaccination_Name,
        Hospital_Adress:Hospital_Adress,
        Hospital_Contact_Person:Hospital_Contact_Person,
        Permit_Exceptional_Case: Permit_Exceptional_Case,
        Hospital_Phone:Hospital_Phone,
        Hospital_Name:Hospital_Name,
        Hospital_Email:Hospital_Email,
        Needs_Disabilities:Needs_Disabilities,
        Allergies_Medications_Conditions:Allergies_Medications_Conditions
      }),
      success: function(html){
        iziToast.show({
          title: html,
          message: '',
          position: 'topRight'
        });
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