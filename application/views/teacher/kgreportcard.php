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
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#quarterReportCard" role="tab" aria-selected="true">Quarter Report Card</a>
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
                           <div class="col-lg-3 col-6">
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
                               <select class="form-control"
                               required="required" name="gradesec" id="grands_gradesec">
                               <option> --- Grade --- </option>
                                 <?php if($_SESSION['usertype']===trim('Director')){
                                  foreach($gradesec as $gradesecs){ ?>
                                    <option value="<?php echo $gradesecs->grade;?>">
                                     <?php echo $gradesecs->grade;?>
                                    </option>
                                  <?php } }else{ 
                                  foreach($gradesecTeacher as $gradesecs){ ?>
                                    <option value="<?php echo $gradesecs->grade;?>">
                                     <?php echo $gradesecs->grade;?>
                                    </option>
                                  <?php } }?>
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
                           <div class="col-lg-3 col-6">
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
                            <div class="col-lg-4 col-6">
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
                                <select class="form-control"
                                 required="required" name="gradesecAnnual" id="grands_gradesecAnnual">
                                 <option> --- Grade --- </option>
                                 <?php if($_SESSION['usertype']===trim('Director')){
                                  foreach($gradesec as $gradesecs){ ?>
                                    <option value="<?php echo $gradesecs->grade;?>">
                                     <?php echo $gradesecs->grade;?>
                                    </option>
                                  <?php } }else{ 
                                  foreach($gradesecTeacher as $gradesecs){ ?>
                                    <option value="<?php echo $gradesecs->grade;?>">
                                     <?php echo $gradesecs->grade;?>
                                    </option>
                                  <?php } }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-4 col-12">
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
                            <div class="col-lg-4 col-6">
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
                                <select class="form-control" required="required" name="gradesec2" id="grands_gradesec2">
                                 <option> --- Grade --- </option>
                                 <?php if($_SESSION['usertype']===trim('Director')){
                                  foreach($gradesec as $gradesecs){ ?>
                                    <option value="<?php echo $gradesecs->grade;?>">
                                     <?php echo $gradesecs->grade;?>
                                    </option>
                                  <?php } }else{ 
                                  foreach($gradesecTeacher as $gradesecs){ ?>
                                    <option value="<?php echo $gradesecs->grade;?>">
                                     <?php echo $gradesecs->grade;?>
                                    </option>
                                  <?php } }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-4 col-12">
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
  $('#comment_formAnnual2').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('#grands_gradesec2').val();
    var reportaca=$('#reportaca2').val();
    if ($('#grands_gradesec2').val() != '' ) {
      $.ajax({
        url: "<?php echo base_url(); ?>Studentkgreportcard/fetchKgStudentAnnualReportCard2/",
        method: "POST",
        data: ({
          gradesec:gradesec,
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
  $('#comment_formAnnual').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('#grands_gradesecAnnual').val();
    var reportaca=$('#reportacaAnnual').val();
    if ($('#grands_gradesecAnnual').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Studentkgreportcard/fetchKgStudentAnnualReportCard/",
        method: "POST",
        data: ({
          gradesec:gradesec,
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
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('#grands_gradesec').val();
    var reportaca=$('#reportaca').val();
    var rpQuarter=$('#rpQuarter').val();
    if($('#includeBackPageDefault').is(':checked')){
      var includeBackPageDefault='1';
    }else{
      var includeBackPageDefault='0';
    }
      if ($('#grands_gradesec').val() != '') {
        $.ajax({
          url: "<?php echo base_url(); ?>Studentkgreportcard/fetchstudentreportcard/",
          method: "POST",
          data: ({
            gradesec:gradesec,
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
<script type="text/javascript">
  $(document).ready(function() {  
    $("#reportaca").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Studentkgreportcard/filterQuarterfromAcademicYear/",
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
</html>