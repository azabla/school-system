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
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#quarterReportCard" role="tab" aria-selected="true"> Quarter Report Card</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#annualReportCard1" role="tab" aria-selected="false">Annual Report Card(Method 1)</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#annualReportCard2" role="tab" aria-selected="false">Annual Report Card(Method 2)</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="quarterReportCard" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row"> 
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                        <form id="comment_form">
                         <div class="row">
                           <div class="col-lg-2 col-6">
                             <div class="form-group">
                              <select class="form-control selectric" required="required" name="reportaca" id="reportaca">
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
                               <select class="form-control selectric" required="required" name="branch" id="grands_branchit">
                               <option> --- Select Branch --- </option>
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
                               <select class="form-control"
                               required="required" name="gradesec" id="grands_gradesec">
                               <option> --- Grade --- </option>
                               </select>
                              </div>
                             </div>
                             <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="quarter" id="rpQuarter">
                                  <option>--- Select Quarter ---</option>
                                  
                                </select>
                              </div>
                            </div>
                           <div class="col-lg-2 col-12">
                             <input type="checkbox" name="includeBackPageDefault" id="includeBackPageDefault" value="1">Include Back Page
                            <button class="btn btn-primary btn-block" 
                            type="submit" name="gethisreport"> View
                            </button>
                          </div>
                        </div>
                       </form>
                        <div class="listHere" id="helloHere"></div>
                      </div>
                      <div class="tab-pane fade show" id="annualReportCard1" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row"> 
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyAnnual()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                        <form id="comment_formAnnual">
                          <div class="row">
                            <div class="col-lg-2 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="reportacaAnnual" id="reportacaAnnual">
                                  <option>--Year--</option>
                                  <?php foreach($academicyear as $academicyears){ ?>
                                    <option value="<?php echo $academicyears->year_name;?>">
                                    <?php echo $academicyears->year_name;?>
                                    </option>
                                  <?php }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-4 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="branchAnnual" id="grands_branchitAnnual">
                                 <option> --- Select Branch --- </option>
                                  <?php foreach($branch as $branchs){ ?>
                                    <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                    </option>
                                  <?php }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-4 col-6">
                              <div class="form-group">
                                <select class="form-control"
                                 required="required" name="gradesecAnnual" id="grands_gradesecAnnual">
                                 <option> --- Select Grade --- </option>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-2 col-6">
                              <button class="btn btn-primary btn-block" type="submit" name="gethisreport"> View
                              </button>
                            </div>
                          </div>
                        </form>
                        <div id="kgAnnualReportCard"> </div>
                      </div>
                      <div class="tab-pane fade show" id="annualReportCard2" role="tabpanel" aria-labelledby="home-tab3">
                        <div class="row"> 
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy2()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                        <form id="comment_formAnnual2">
                          <div class="row">
                            <div class="col-lg-2 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="reportaca2" id="reportaca2">
                                  <option>--Year--</option>
                                  <?php foreach($academicyear as $academicyears){ ?>
                                    <option value="<?php echo $academicyears->year_name;?>">
                                    <?php echo $academicyears->year_name;?>
                                    </option>
                                  <?php }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-4 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="branch2" id="grands_branchit2">
                                  <option> --- Select Branch --- </option>
                                  <?php foreach($branch as $branchs){ ?>
                                    <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                    </option>
                                  <?php }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-4 col-6">
                              <div class="form-group">
                                <select class="form-control" required="required" name="gradesec2" id="grands_gradesec2">
                                 <option> --- Select Grade --- </option>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-2 col-6">
                              <button class="btn btn-primary btn-block" type="submit" name="gethisreport"> View </button>
                            </div>
                          </div>
                        </form>
                        <div id="kgAnnualReportCard2"> </div>
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
</body>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#reportaca").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Kgreportcard/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportaca").val(),
        beforeSend: function() {
          $('#grands_branchit').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_branchit").html(data);
        }
      });
    });
  });
  $('#comment_formAnnual2').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('#grands_gradesec2').val();
    var branch=$('#grands_branchit2').val();
    var reportaca=$('#reportaca2').val();
      if ($('#grands_gradesec2').val() != '' && $('#grands_branchit2').val() != '' ) {
        $.ajax({
          url: "<?php echo base_url(); ?>Kgreportcard/fetchKgStudentAnnualReportCard2/",
          method: "POST",
          data: ({
            gradesec:gradesec,
            branch:branch,
            reportaca:reportaca
          }),
          async: false,
          cache: false,
          dataType: 'json',
          beforeSend: function() {
            $('#kgAnnualReportCard2').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
          },
          success: function(data) {
            $("#kgAnnualReportCard2").html(data);
          }
        })
      }else {
        alert("All fields are required");
      }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_branchit2").bind("change", function() {
      var branchit=$("#grands_branchit2").val();
      var academicyear=$("#reportaca2").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Kgreportcard/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#grands_gradesec2').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_gradesec2").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $('#comment_formAnnual').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('#grands_gradesecAnnual').val();
    var branch=$('#grands_branchitAnnual').val();
    var reportaca=$('#reportacaAnnual').val();
      if ($('#grands_gradesecAnnual').val() != '' && $('#grands_branchitAnnual').val() != '' ) {
        $.ajax({
          url: "<?php echo base_url(); ?>Kgreportcard/fetchKgStudentAnnualReportCard/",
          method: "POST",
          data: ({
            gradesec:gradesec,
            branch:branch,
            reportaca:reportaca
          }),
          async: false,
          cache: false,
          dataType: 'json',
          beforeSend: function() {
            $('#kgAnnualReportCard').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
          },
          success: function(data) {
            $("#kgAnnualReportCard").html(data);
          }
        })
      }else {
        alert("All fields are required");
      }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_branchitAnnual").bind("change", function() {
      var branchit=$("#grands_branchitAnnual").val();
      var academicyear=$("#reportacaAnnual").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Kgreportcard/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#grands_gradesecAnnual').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_gradesecAnnual").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_branchit").bind("change", function() {
      var branchit=$("#grands_branchit").val();
      var academicyear=$("#reportaca").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Kgreportcard/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#grands_gradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_gradesec").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportaca").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Kgreportcard/filterQuarterfromAcademicYear/",
        data: "academicyear=" + $("#reportaca").val(),
        beforeSend: function() {
          $('#rpQuarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#rpQuarter").html(data);
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
<!-- Report card fetch Start-->
<script type="text/javascript">
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('#grands_gradesec').val();
    var branch=$('#grands_branchit').val();
    var reportaca=$('#reportaca').val();
    var rpQuarter=$('#rpQuarter').val();
    if($('#includeBackPageDefault').is(':checked')){
      var includeBackPageDefault='1';
    }else{
      var includeBackPageDefault='0';
    }
      if ($('#grands_gradesec').val() != '' && $('#grands_branchit').val() != '' ) {
        $.ajax({
          url: "<?php echo base_url(); ?>Kgreportcard/fetchstudentreportcard/",
          method: "POST",
          data: ({
            gradesec:gradesec,
            branch:branch,
            reportaca:reportaca,
            rpQuarter:rpQuarter,
            includeBackPageDefault:includeBackPageDefault
          }),
           async: false,
            cache: false,
            
           dataType: 'json',
          beforeSend: function() {
            $('.listHere').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
          },
          success: function(data) {
            $(".listHere").html(data);
          }
        })
      }else {
        alert("All fields are required");
      }
  });
</script>
<!-- Report card fetch End -->
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("helloHere");
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
  function codespeedyAnnual(){
    var print_div = document.getElementById("kgAnnualReportCard");
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
  function codespeedy2(){
    var print_div = document.getElementById("kgAnnualReportCard2");
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