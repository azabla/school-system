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
            <div class="row">
              <div class="col-12">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>"> 
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#evaluationRank" role="tab" aria-selected="true"> Evaluation Rank</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#assesmentRank" role="tab" aria-selected="false">Assesment Rank</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="evaluationRank" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row">
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                            <span class="text-black">
                            <i data-feather="printer"></i>
                            </span>
                           </button>
                           <button type="submit" id="dataExportExcelEvaluation" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel
                            </button>
                          </div>
                          <div class="col-md-3 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="quarter"  id="analysis_quarter">
                                <option>--- Select Quarter ---</option>
                                <?php foreach($fetch_term as $fetch_terms){ ?>
                                  <option value="<?php echo $fetch_terms->term;?>">
                                  <?php echo $fetch_terms->term;?>
                                  </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3 col-6">
                            <div class="form-group">
                              <select class="form-control"
                               required="required" name="grade2analysis" id="grade2analysis">
                               <option>--Select Grade--</option>
                               <?php  foreach($gradesec as $gradesecs){ ?>
                                  <option value="<?php echo $gradesecs->grade;?>">
                                   <?php echo $gradesecs->grade;?>
                                  </option>
                                <?php } ?>
                                
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3 col-6">
                            <select class="form-control evaluation_here" required="required" name="evaluation_here" id="evaluation_here">
                            </select>
                          </div>
                          <div class="col-md-3 col-6">
                            <button class="btn btn-primary btn-block viewanalysis"> View Analysis
                            </button>
                          </div>
                        </div>
                        <div class="listanalysis" id="helloResult"> </div>
                      </div>
                      <div class="tab-pane fade show" id="assesmentRank" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row">
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy2()">
                            <span class="text-black">
                            <i data-feather="printer"></i>
                            </span>
                           </button>
                           <button type="submit" id="dataExportExcelAssesment" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel
                            </button>
                          </div>
                          <div class="col-md-3 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="evaluation_quarter"  id="evaluation_quarter">
                                <option>--- Select Quarter ---</option>
                                <?php foreach($fetch_term as $fetch_terms){ ?>
                                  <option value="<?php echo $fetch_terms->term;?>">
                                  <?php echo $fetch_terms->term;?>
                                  </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3 col-6">
                            <div class="form-group">
                              <select class="form-control" required="required" name="grade2analysis_evaluation" id="grade2analysis_evaluation">
                               <option>--Select Grade--</option>
                               <?php  foreach($gradesec as $gradesecs){ ?>
                                  <option value="<?php echo $gradesecs->grade;?>">
                                   <?php echo $gradesecs->grade;?>
                                  </option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3 col-6">
                            <select class="form-control evaluationassesment_here" required="required" name="evaluationassesment_here" id="evaluationassesment_here">
                            </select>
                          </div>
                          <div class="col-md-3 col-6">
                            <button class="btn btn-primary btn-block view_assesmentanalysis"> View Analysis
                            </button>
                          </div>
                        </div>
                        <div class="list_assesmentanalysis" id="helloResult2"> </div>
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
<!-- filter grade from branch starts -->
<script type="text/javascript">
  $('.gs-sms-mark-management-teacher-page').addClass('active');
  $("#dataExportExcelEvaluation").click(function(e) {
  let file = new Blob([$('.listanalysis').html()], {type:"application/vnd.ms-excel"});
  let url = URL.createObjectURL(file);
  let a = $("<a />", {
    href: url,
    download: "Evaluation Rank.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
  });  
  $("#dataExportExcelAssesment").click(function(e) {
  let file = new Blob([$('.list_assesmentanalysis').html()], {type:"application/vnd.ms-excel"});
  let url = URL.createObjectURL(file);
  let a = $("<a />", {
    href: url,
    download: "Assesment Rank.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
  }); 
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#grade2analysis_evaluation").bind("change", function() {
      var grade2analysis=$("#grade2analysis_evaluation").val();
       var analysis_quarter=$("#evaluation_quarter").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mystudentassesmentrank/filter_assesment4analysis/",
        data:({
          grade2analysis:grade2analysis,
          analysis_quarter:analysis_quarter
        }),
        beforeSend: function() {
          $('.evaluationassesment_here').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
            );
        },
        success: function(data) {
          $(".evaluationassesment_here").html(data);
        }
      });
    });
  });
  $(document).ready(function() {
    $("#grade2analysis").bind("change", function() {
      var grade2analysis=$("#grade2analysis").val();
       var analysis_quarter=$("#analysis_quarter").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mystudentassesmentrank/filter_evaluation4analysis/",
        data:({
          grade2analysis:grade2analysis,
          analysis_quarter:analysis_quarter
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
  });
</script>
<!-- fetch analysis starts -->
<script type="text/javascript">
  $(document).on('click', '.view_assesmentanalysis', function() {
    var branch=$('#branch_valuation').val();
    var gradesec=$('#grade2analysis_evaluation').val();
    var evaluation=$('#evaluationassesment_here').val();
    var quarter=$('#evaluation_quarter').val();
    $.ajax({
      url: "<?php echo base_url(); ?>mystudentassesmentrank/fetch_assesment_analysis/",
      method: "POST",
      data: ({
        gradesec:gradesec,
        evaluation:evaluation,
        quarter:quarter
      }),
      beforeSend: function() {
        $('.list_assesmentanalysis').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(data) {
        $(".list_assesmentanalysis").html(data);
      }
    });
  })
  $(document).on('click', '.viewanalysis', function() {
    var gradesec=$('#grade2analysis').val();
    var evaluation=$('#evaluation_here').val();
    var quarter=$('#analysis_quarter').val();
    $.ajax({
      url: "<?php echo base_url(); ?>mystudentassesmentrank/fetch_analysis/",
      method: "POST",
      data: ({
        gradesec:gradesec,
        evaluation:evaluation,
        quarter:quarter
      }),
      beforeSend: function() {
        $('.listanalysis').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(data) {
        $(".listanalysis").html(data);
      }
    });
  })
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("helloResult");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  function codespeedy2(){
    var print_div = document.getElementById("helloResult2");
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
</html>