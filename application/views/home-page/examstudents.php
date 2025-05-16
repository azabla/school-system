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
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-12 col-md-12">
                <div class="alert alert-light alert-dismissible show fade">
                  <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                    Note:You can arrange students based on their result and assign new exam class. The placement is based on total scored raw marks in all subjects
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#feedMergedSubject" role="tab" aria-selected="true">Assign Student</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="feedMergedSubject" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                        <div class="card">
                          <div class="card-body StudentViewTextInfo">
                            <div id="plist" class="people-list">
                              <div class="m-b-20">
                                <form id="comment_formGrade">
                                  <div class="row">
                                    <div class="col-lg-7 col-6">
                                      <div class="form-group">
                                       <select class="form-control selectric" required="required" name="branch_statisticsGrade" id="branchitGrade">
                                       <option>---Branch --- </option>
                                        <?php foreach($branch as $branchs){ ?>
                                          <option value="<?php echo $branchs->name;?>">
                                          <?php echo $branchs->name;?>
                                          </option>
                                        <?php }?>
                                       </select>
                                      </div>
                                    </div>
                                    <div class="col-lg-5 col-6">
                                      <div class="form-group">
                                       <select class="form-control selectric" required="required" name="branch_statisticsTerm" id="branchitTerm">
                                       <option>---Term/Quarter --- </option>
                                        <?php foreach($fetch_term as $fetch_terms){ ?>
                                          <option value="<?php echo $fetch_terms->term;?>">
                                          <?php echo $fetch_terms->term;?>
                                          </option>
                                        <?php }?>
                                       </select>
                                      </div>
                                    </div>
                                    <div class="col-lg-12 col-6">
                                      <div class="form-group">
                                        <select class="form-control" required="required" name="grade_statisticsGrade" id="grade_statisticsGrade">
                                          <option>-Grade- </option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-lg-12 col-12">
                                      <div class="form-group">
                                        <div class="evaluation_here"></div>
                                      </div>
                                    </div>
                                    <div class="col-lg-4 col-5">
                                      <div class="form-group">
                                       <select class="form-control" required="required" name="className" id="className">
                                        <option>Exam Class</option>
                                        <?php
                                         for($j=0;$j<26; $j++) { ?>
                                           <option value="<?php echo chr(65+$j) ?>"><?php echo chr(65+$j);?></option>
                                         <?php } ?>
                                         <option value="AA">AA</option>
                                         <option value="AB">AB</option>
                                         <option value="AC">AC</option>
                                         <option value="AD">AD</option>
                                         <option value="AE">AE</option>
                                         <option value="AF">AF</option>
                                         <option value="AG">AG</option>
                                         <option value="AH">AH</option>
                                         <option value="AI">AI</option>
                                         <option value="AJ">AJ</option>
                                         <option value="AK">AK</option>
                                         <option value="AL">AL</option>
                                         <option value="AM">AM</option>
                                         <option value="AN">AN</option>
                                         <option value="AO">AO</option>
                                         <option value="AP">AP</option>
                                         <option value="AQ">AQ</option>
                                         <option value="AR">AR</option>
                                         <option value="AS">AS</option>
                                         <option value="AT">AT</option>
                                         <option value="AU">AU</option>
                                       </select>
                                      </div>
                                    </div>
                                    <div class="col-lg-6 col-5">
                                      <div class="form-group">
                                       <select class="form-control" required="required" name="noOfStudent" id="noOfStudent">
                                        <option>No. of Student</option>
                                        <?php for($i=1;$i<=100;$i++){ ?>
                                          <option value="<?php echo $i ?>"><?php echo $i;?></option>
                                        <?php } ?>
                                       </select>
                                      </div>
                                    </div>
                                    <div class="col-lg-2 col-2">
                                      <button class="btn btn-default text-info" id="saveCriteria"><i class="fas fa-plus-circle"></i></button>
                                    </div>
                                    
                                    <div class="col-lg-12 col-12">
                                      <div class="listHereGs"></div>
                                    </div>
                                    <div class="col-lg-12 col-12">
                                      <input type="checkbox" name="includeName" id="includeName"> Include Result
                                      <button class="btn btn-primary btn-block" type="submit" name="getrank"> View </button>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <div class="chat">
                          <div class="chat-about">
                            <div class="row"> 
                              <div class="col-md-6 col-6"> </div>
                              <div class="col-md-6 col-6">
                                <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyGrade()">
                                  <span class="text-black">
                                  <i data-feather="printer"></i>
                                  </span>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="listReportGrade" id="helloReportGrade">  </div>
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
</body>
<script type="text/javascript">
  load_data();
  function load_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>Examstudents/fetchRange/",
      method:"POST",
      beforeSend: function() {
        $('.listHereGs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="20" height="20" id="loa">');
      },
      success:function(data){
        $('.listHereGs').html(data);
      }
    })
  }
  $(document).on('click', '#saveCriteria', function() {
    event.preventDefault();
    var noOfStudent=$('#noOfStudent').val();
    var className=$('#className').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Examstudents/saveRange/",
      data: ({
        noOfStudent:noOfStudent,
        className:className
      }),
      success: function(data) {
        $(".listHereGs").html(data);
      }
    });
  });
  $(document).on('click', '.btnRemove', function() {
    event.preventDefault();
    var id=$(this).attr("id");
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Examstudents/removeRange/",
      data: ({
        id:id
      }),
      success: function(data) {
        $(".listHereGs").html(data);
      }
    });
  });
  $("#grade_statisticsGrade").bind("change", function() {
    var grade2analysis=$("#grade_statisticsGrade").val();
    var quarter=$("#branchitTerm").val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Examstudents/filterAssesmentCustomEvaluation/",
      data:({
        grade2analysis:grade2analysis,
        quarter:quarter
      }),
      beforeSend: function() {
        $('.evaluation_here').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      success: function(data) {
        $(".evaluation_here").html(data);
      }
    });
  });
  $("#branchitTerm").bind("change", function() {
    $("#grade_statisticsGrade").val('');
  });
</script>
<script type="text/javascript">
  $('#comment_formGrade').on('submit', function(event) {
    event.preventDefault();
    gradeEvaluation=[];
    $("input[name='assesment4StudentsExam']:checked").each(function(i){
      gradeEvaluation[i]=$(this).val();
    });
    var term=$("#branchitTerm").val();
    var grade_statistics=$("#grade_statisticsGrade").val();
    var branch_statistics=$("#branchitGrade").val();
    if($('#includeName').is(':checked')){
      var nameChecked=1;
    }else{
      var nameChecked=0;
    }
    if(gradeEvaluation.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Examstudents/shuffleStudent/",
        data: ({
          grade_statistics:grade_statistics,
          branch_statistics:branch_statistics,
          gradeEvaluation:gradeEvaluation,
          nameChecked:nameChecked,
          term:term
        }),
        beforeSend: function() {
          $('.listReportGrade').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">'
            );
        },
        success: function(data) {
          $(".listReportGrade").html(data);
        }
      });
    }else{
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branchitGrade").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Examstudents/filterOnlyGradeFromBranch/",
        data: "branchit=" + $("#branchitGrade").val(),
        beforeSend: function() {
          $('#grade_statisticsGrade').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grade_statisticsGrade").html(data);
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
  function codespeedyGrade(){
    var print_div = document.getElementById("helloReportGrade");
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