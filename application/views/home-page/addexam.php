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
          <?php 
          if($summerClassMark->num_rows()>0){ ?>
             <div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
              <button class="close"  data-dismiss="alert">
                <span>&times;</span>
              </button>
              <i class="fas fa-check-circle"> </i> Summer class has been started. Please contact your system Admin.
            </div>
            </div> 
          <?php } else { if($markstatus->num_rows()>0 || $checkAutoLock){?>
            <div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
              <button class="close"  data-dismiss="alert">
                <span>&times;</span>
              </button>
              <i class="fas fa-check-circle"> </i> Access denied.Please check either locked or outdated.
            </div>
            </div> 
          <?php } else{ ?>
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Add New Result</h4>
                    <small class="text-muted text-time text-danger">Note:If you/teachers encountered `No table` error when selecting assesment, it may be either <a href="<?php echo base_url() ?>adjusttable/">table not created</a>, <a href="<?php echo base_url() ?>schoolassesment/">assesment not created</a> or user branch not correct.</small>
                  </div>
                  <div class="StudentViewTextInfo">
                    <form method="POST" class="" id="comment_form">
                     <div class="row">
                      <div class="col-lg-2 col-6">
                         <div class="form-group">
                          <label>Academic Year</label>
                           <select class="form-control selectric" required="required" name="academicyear" 
                           id="academicyear">
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
                            <label>Branch</label>
                           <select class="form-control selectric" required="required" name="branch"
                           id="admin_branch">
                           <option> </option>
                            <?php foreach($branch as $branchs){ ?>
                              <option value="<?php echo $branchs->name;?>">
                              <?php echo $branchs->name;?>
                              </option>
                            <?php }?>
                           </select>
                          </div>
                         </div>
                         <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <label>Grade</label>
                           <select class="form-control selectric" required="required" name="gradesec" id="gradesec">
                           <option> </option>
                           
                           </select>
                          </div>
                         </div>
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <label>Subject</label>
                           <select class="form-control subject" required="required" name="subject" id="subject">
                            <option></option>
                           </select>
                          </div>
                         </div>
                         <div class="col-lg-3 col-6">
                         <div class="form-group">
                          <label>Season</label>
                           <select class="form-control" required="required" name="quarter" id="quarter">
                          <option></option>
                            
                           </select>
                          </div>
                         </div>
                        <div class="col-lg-3 col-6">
                         <div class="form-group">
                          <label>Evaluation</label>
                           <select class="form-control" required="required" name="evaluation" required="required" id="evaluation">
                            <option></option>
                           </select>
                          </div>
                         </div>
                        <div class="col-lg-3 col-6">
                         <div class="form-group">
                          <label>Assesment</label>
                          <select class="form-control" required="required" name="assesname" required="required" id="assesname">
                            <option></option>
                           </select>
                          </div>
                        </div>
                         <div class="col-lg-3 col-6">
                         <div class="form-group">
                          <label>Percent</label>
                           <input type="number" required="required" class="form-control" name="percentage" placeholder="Percent..." id="percentage">
                          </div>
                         </div>
                       <div class="col-lg-3 col-12">
                        <div class="form-group">
                          <label></label>
                          <button class="btn btn-primary btn-block" type="submit" name="startmark">Start</button>
                        </div>
                      </div>
                    </div>
                   </form>
                   <div class="dropdown-divider"></div>
                  <div class="markform"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } }?>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<!-- <script>
    function record(id) {
      var recognition = new webkitSpeechRecognition();
      recognition.lang = "en-GB";
      recognition.onresult = function(event) {
        document.getElementById(id).value = event.results[0][0].transcript;
      }
      recognition.start();
    }
  </script> -->
<script type="text/javascript">
 $(document).ready(function() {  
    $("#quarter").bind("change", function() {
      var gradesec=$("#gradesec").val();
      var quarter=$("#quarter").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_evaluation_quarterchange/",
        data: ({
          gradesec:gradesec,
          quarter:quarter
        }),
         beforeSend: function() {
          $('#evaluation').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#evaluation").html(data);
        }
      });
    });
 });
</script>
<!-- filter assesment name starts -->
<script type="text/javascript">
 $(document).ready(function() {  
    $("#assesname").bind("change", function() {
      var gradesec=$("#gradesec").val();
      var evaluation=$("#assesname").val();
      var quarter=$("#quarter").val();
      var subject=$("#subject").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>FilterPercentageOnAssesmentChange/",
        data: ({
          gradesec:gradesec,
          evaluation:evaluation,
          quarter:quarter,
          subject:subject
        }),
         beforeSend: function() {
          $('#percentage').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          if(data>0){
            document.getElementById("percentage").value=data;
            $( "#percentage" ).prop( "disabled", true );
          }else{
            document.getElementById("percentage").value='';
            $( "#percentage" ).prop( "disabled", false );
          }
        }
      });
    });
 });
</script>
<script type="text/javascript">
 $(document).ready(function() {  
    $("#evaluation").bind("change", function() {
      var gradesec=$("#gradesec").val();
      var evaluation=$("#evaluation").val();
      var branch=$("#admin_branch").val();
      var quarter=$("#quarter").val();
      var subject=$("#subject").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>FilterAssesmentQuarterChange/",
        data: ({
          gradesec:gradesec,
          evaluation:evaluation,
          branch:branch,
          quarter:quarter,
          subject:subject
        }),
         beforeSend: function() {
          $('#assesname').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#assesname").html(data);
        }
      });
    });
 });
</script>
<script>
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var academicyear=$("#academicyear").val();
    var branch=$("#admin_branch").val();
    var gradesec=$("#gradesec").val();
    var subject=$("#subject").val();
    var evaluation=$("#evaluation").val();
    var quarter=$("#quarter").val();
    var assesname=$("#assesname").val();
    var percentage=$("#percentage").val();
    if($("#subject").val()!='' && $("#percentage").val() > 0){
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>My_studentresultform/",
      data: ({
        academicyear: academicyear,
        branch:branch,
        gradesec:gradesec,
        subject:subject,
        evaluation:evaluation,
        quarter:quarter,
        assesname:assesname,
        percentage:percentage
      }),
      cache: false,
      beforeSend: function() {
        $('.markform').html( '<h4>Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa"></h4>' );
        $('.markform').css('green','red');
      },
      success: function(html){
        $('.markform').html(html);
      }
    });
  }else{
    swal('Please enter correct value! ', {
      icon: 'error',
    });
  }
  });
</script>

<!-- save mark result starts -->
<script>
  function chkMarkValue(){
    var stuid=$("#stuid").val();
    var chkPercent=parseInt($("#percentage").val());
    var markResult=$("#resultvalue").val();
    $("input[name='markvalue_result']").each(function(i){
      resultvalue=parseInt($(this).val());
      if(resultvalue > chkPercent){
        swal('Incorrect Mark result. Please try Again. ', {
          icon: 'error',
        });
      }
    });
  }
  $(document).on('click', '#SaveResult', function() {
    /*event.preventDefault();*/
    var stuid=$("#stuid").val();
    var academicyear=$("#academicyear").val();
    var subject=$("#subject").val();
    var evaluation=$("#evaluation").val();
    var quarter=$("#quarter").val();
    var assesname=$("#assesname").val();
    var percentage=$("#percentage").val();
    var markGradeSec=$("#markGradeSec").val();
    var markGradeSecBranch=$("#markGradeSecBranch").val();
    stuid=[];resultvalue=[];
    $("input[name='stuid_result']").each(function(i){
      stuid[i]=$(this).val();
    });
    $("input[name='markvalue_result']").each(function(i){
      resultvalue[i]=$(this).val();
    });
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Savestudentresult/",
      data: ({
        stuid:stuid,
        resultvalue:resultvalue,
        academicyear: academicyear,
        subject:subject,
        evaluation:evaluation,
        quarter:quarter,
        assesname:assesname,
        percentage:percentage,
        markGradeSec:markGradeSec,
        markGradeSecBranch:markGradeSecBranch
      }),
      cache: false,
      beforeSend: function() {
        $('.markform').html( '<h3><span class="text-success">Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa"></span></h3>' );
        $("#SaveResult").attr("disabled","disabled");
      },
      success: function(html){
        $('.markform').html(html);
        $('#comment_form')[0].reset(); 
        $('#SaveResult').removeAttr('disabled','disabled'); 
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
    $("#gradesec").bind("change", function() {
      var gradesec=$("#gradesec").val();
      var branch=$("#admin_branch").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_subject_from_staffp/",
        data: ({
          gradesec:gradesec,
          branch:branch
        }),
         beforeSend: function() {
          $('.subject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $(".subject").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#admin_branch").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_staffplacement/",
        data: "branch=" + $("#admin_branch").val(),
         beforeSend: function() {
          $('#gradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#gradesec").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#subject").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter_admin/",
        data: "gradesec=" + $("#gradesec").val(),
         beforeSend: function() {
          $('#quarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#quarter").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#gradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#gradesec").val(),
         beforeSend: function() {
          $('#quarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#quarter").html(data);
        }
      });
    });
  });
</script>

</html>