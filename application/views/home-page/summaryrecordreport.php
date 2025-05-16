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
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#studentRecordReport" role="tab" aria-selected="true">Student Record Report</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#studentAspReport" role="tab" aria-selected="false">ASP Student Report</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="studentRecordReport" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                        <span class="text-black"> <i data-feather="printer"></i> </span>
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="comment_form">
                      <div class="row">
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required"
                              name="academicyear" id="grands_academicyear">
                              <?php foreach($academicyear as $academicyears){ ?>
                                <option value="<?php echo $academicyears->year_name;?>">
                                <?php echo $academicyears->year_name;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control" required="required" name="branch"
                             id="grands_branchit">
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
                          <label>Select Grade:</label>
                          <div class="form-group table-responsive grands_gradesec" 
                            id="grands_gradesec" style="height: 15vh;"> 
                          </div>
                        </div>
                        <div class="col-lg-2 col-6">
                          <label>Select Age:</label>
                          <div class="form-group table-responsive gradeAge" 
                            id="gradeAge" style="height: 15vh;"> 
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <input type="checkbox" name="includeName" id="includeName"> Include Name
                          <button class="btn btn-primary btn-block" type="submit" name="viewmark">View</button>
                        </div>
                      </div>
                    </form>
                    <div class="SummaryRecord" id="SummaryRecord"> </div>
                  </div>
                  <div class="tab-pane fade show" id="studentAspReport" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyASP()">
                        <span class="text-black"> <i data-feather="printer"></i> </span>
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="comment_formASP">
                      <div class="row">
                        <div class="col-lg-2 col-6">
                           <div class="form-group">
                             <select class="form-control selectric" required="required" name="academicyearASP" id="grands_academicyearASP">
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
                             <select class="form-control" required="required" name="branchASP" id="grands_branchitASP">
                             <option>--- Branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                           </div>
                           <div class="col-lg-4 col-6 table-responsive grands_gradesecASP" style="height: 20vh;">
                           </div>
                         <div class="col-lg-3 col-6">
                          <button class="btn btn-primary btn-block" 
                          type="submit" name="viewmark">View</button>
                        </div>
                      </div>
                    </form>
                    <div class="studentListASP" id="studentListASP"> </div>
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
</body>
<script type="text/javascript">
  function fetch_asp_student_list_now(){
      var itemsall=document.getElementById('fetch_asp_student_list');
      if(itemsall.checked==true){
      var items=document.getElementsByName('fetch_asp_student_list');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('fetch_asp_student_list');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
  }
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_branchitASP").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summaryrecordreport/Filter_grade_from_branch/",
        data: "branchit=" + $("#grands_branchitASP").val(),
        beforeSend: function() {
          $('.grands_gradesecASP').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".grands_gradesecASP").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $('#comment_formASP').on('submit', function(event) {
    event.preventDefault();
    gradeSection=[];
    $("input[name='fetch_asp_student_list']:checked").each(function(i){
      gradeSection[i]=$(this).val();
    });
    var attBranches=$('#grands_branchitASP').val();
    if (gradeSection.length!=0) {
      $.ajax({
        url: "<?php echo base_url(); ?>Summaryrecordreport/fetchStudents4_asp_Attendance_Report/",
        method: "POST",
        data: ({
          attBranches: attBranches,
          gradeSection:gradeSection
        }),
        beforeSend: function() {
          $('.studentListASP').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".studentListASP").html(data);
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
  function selectAll_Grade_GS(){
      var itemsall=document.getElementById('selectAll_Grade_GS_Now');
      if(itemsall.checked==true){
      var items=document.getElementsByName('summaryGSGrade');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('summaryGSGrade');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
  }
</script>
<script type="text/javascript">
  $(document).on('click', "input[name='summaryGSGrade']", function() {
    summaryGSGrade=[];
    $("input[name='summaryGSGrade']:checked").each(function(i){
      summaryGSGrade[i]=$(this).val();
    });
    var grands_academicyear=$('#grands_academicyear').val();
    var gs_branches=$('#grands_branchit').val();
    if(summaryGSGrade.length!==0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summaryrecordreport/fetchThisGradeAge/",
        data: ({
          summaryGSGrade:summaryGSGrade,
          gs_branches: gs_branches,
          grands_academicyear:grands_academicyear
        }),
        beforeSend: function() {
          $('#gradeAge').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $("#gradeAge").html(data);
        }
      });
    }
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("SummaryRecord");
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
  function codespeedyASP(){
    var print_div = document.getElementById("studentListASP");
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
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    summaryGSAge=[];
    $("input[name='summaryGSAge']:checked").each(function(i){
      summaryGSAge[i]=$(this).val();
    });


    summaryGSGrade=[];
    $("input[name='summaryGSGrade']:checked").each(function(i){
      summaryGSGrade[i]=$(this).val();
    });
    var grands_academicyear=$('#grands_academicyear').val();
    var gs_branches=$('#grands_branchit').val();
    if ($('#grands_branchit').val() != '') {
      if($('#includeName').is(':checked')){
        if(summaryGSAge.length!==0){
          $.ajax({
            url: "<?php echo base_url(); ?>Summaryrecordreport/FecthThisDivStudentAgeWithName/",
            method: "POST",
            data: ({
              gs_branches: gs_branches,
              summaryGSGrade:summaryGSGrade,
              grands_academicyear:grands_academicyear,
              summaryGSAge:summaryGSAge
            }),
            beforeSend: function() {
              $('.SummaryRecord').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $(".SummaryRecord").html(data);
            }
          })
        }else{
          $.ajax({
            url: "<?php echo base_url(); ?>Summaryrecordreport/FecthThisDivStudent/",
            method: "POST",
            data: ({
              gs_branches: gs_branches,
              summaryGSGrade:summaryGSGrade,
              grands_academicyear:grands_academicyear
            }),
            beforeSend: function() {
              $('.SummaryRecord').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $(".SummaryRecord").html(data);
            }
          })
        }
      }else{
        if(summaryGSAge.length!==0){
          $.ajax({
            url: "<?php echo base_url(); ?>Summaryrecordreport/FecthThisDivStudentNoNameAge/",
            method: "POST",
            data: ({
              gs_branches: gs_branches,
              summaryGSGrade:summaryGSGrade,
              grands_academicyear:grands_academicyear,
              summaryGSAge:summaryGSAge
            }),
            beforeSend: function() {
              $('.SummaryRecord').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $(".SummaryRecord").html(data);
            }
          })
        }else{
          $.ajax({
            url: "<?php echo base_url(); ?>Summaryrecordreport/FecthThisDivStudentNOName/",
            method: "POST",
            data: ({
              gs_branches: gs_branches,
              summaryGSGrade:summaryGSGrade,
              grands_academicyear:grands_academicyear
            }),
            beforeSend: function() {
              $('.SummaryRecord').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $(".SummaryRecord").html(data);
            }
          })
        }
      }
    }else {
      alert("All fields are required");
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_branchit").bind("change", function() {
      var academicyear=$("#grands_academicyear").val();
      var branchit=$("#grands_branchit").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summaryrecordreport/filterGrade/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
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
      $("body").removeClass("dark");
      $("body").removeClass("dark-sidebar");
      $("body").removeClass("theme-black");
      $("body").addClass("light");
      $("body").addClass("light-sidebar");
      $("body").addClass("theme-white");
    } else {
      $("body").removeClass("light");
      $("body").removeClass("light-sidebar");
      $("body").removeClass("theme-white");
      $("body").addClass("dark");
      $("body").addClass("dark-sidebar");
      $("body").addClass("theme-black");
    }
  });
</script>
<script type="text/javascript" language="javascript"> 
  var bgcolor_now=document.getElementById("bgcolor_now").value;
  if (bgcolor_now == "1") {
    $("body").removeClass("dark");
    $("body").removeClass("dark-sidebar");
    $("body").removeClass("theme-black");
    $("body").addClass("light");
    $("body").addClass("light-sidebar");
    $("body").addClass("theme-white");
  }else {
    $("body").removeClass("light");
    $("body").removeClass("light-sidebar");
    $("body").removeClass("theme-white");
    $("body").addClass("dark");
    $("body").addClass("dark-sidebar");
    $("body").addClass("theme-black"); 
  } 
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