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
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab0" data-toggle="tab" href="#adjustReportCard" role="tab" aria-selected="true">Adjust Report card</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab1" data-toggle="tab" href="#defaultReportCard" role="tab" aria-selected="false">Default Report card</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab2" data-toggle="tab" href="#customReportCard" role="tab" aria-selected="false">Custom Report card</a>
              </li>
            </ul>
            <div class="tab-content tab-bordered" id="myTab3Content">
              <div class="tab-pane fade show active" id="adjustReportCard" role="tabpanel" aria-labelledby="home-tab0">
                <div class="card">
                  <div class="card-header">
                    <div class="row"> 
                      <div class="col-md-6">
                        <button class="btn btn-outline-warning" id="adjustRcTable">Adjust Report Card Table</button><a href="#" class="reportCardTableInfo"></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade show" id="defaultReportCard" role="tabpanel" aria-labelledby="home-tab1">
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">
                        <div class="row"> 
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <form id="comment_form">
                         <div class="row">
                           <div class="col-lg-2 col-6">
                             <div class="form-group">
                              <select class="form-control selectric" required="required" name="reportaca" id="reportaca">
                                <option></option>
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
                                  <?php foreach($fetch_term as $fetch_terms){ ?>
                                    <option value="<?php echo $fetch_terms->term;?>">
                                    <?php echo $fetch_terms->term;?>
                                    </option>
                                  <?php }?>
                                </select>
                              </div>
                            </div>
                           <div class="col-lg-2 col-12">
                            <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="gethisreport"> View
                            </button>
                          </div>
                        </div>
                       </form>
                     </div>
                    </div>
                    <div class="row table-responsive"  style="height: 50vh;">
                      <div class="col-lg-12 Reportlist" id="helloFetch">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade show" id="customReportCard" role="tabpanel" aria-labelledby="home-tab2">
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">
                        <div class="row"> 
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport2" onclick="codespeedy2()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <form id="comment_form2">
                         <div class="row">
                           <div class="col-lg-2 col-6">
                             <div class="form-group">
                              <select class="form-control selectric" required="required" name="reportaca2" id="reportaca2">
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
                             <div class="col-lg-2 col-6">
                              <div class="form-group">
                               <select class="form-control"
                               required="required" name="gradesec2" id="grands_gradesec2">
                               <option> --- Grade --- </option>
                               </select>
                              </div>
                             </div>
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="quarter2" id="rpQuarter2">
                                  <option>--- Select Quarter ---</option>
                                  <?php foreach($fetch_term as $fetch_terms){ ?>
                                    <option value="<?php echo $fetch_terms->term;?>">
                                    <?php echo $fetch_terms->term;?>
                                    </option>
                                  <?php }?>
                                </select>
                              </div>
                            </div>
                           <div class="col-lg-2 col-12">
                            <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="gethisreport2"> View
                            </button>
                          </div>
                        </div>
                       </form>
                     </div>
                    </div>
                    <div class="row table-responsive"  style="height: 50vh;">
                      <div class="col-lg-12 Reportlist2" id="helloFetch2">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
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
</body>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#adjustRcTable").on("click", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcardiamond/adjustRcTable/",
        beforeSend: function() {
          $('.reportCardTableInfo').html('Adjusting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".reportCardTableInfo").html(data);
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
        url: "<?php echo base_url(); ?>reportcardiamond/filterGradefromBranch/",
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
    $("#grands_branchit2").bind("change", function() {
      var branchit=$("#grands_branchit2").val();
      var academicyear=$("#reportaca2").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcardiamond/filterGradefromBranch/",
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
  $(document).ready(function() {  
    $("#reportaca").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcardiamond/filterQuarterfromAcademicYear/",
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
  $(document).ready(function() {  
    $("#reportaca2").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcardiamond/filterQuarterfromAcademicYear/",
        data: "academicyear=" + $("#reportaca2").val(),
        beforeSend: function() {
          $('#rpQuarter2').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#rpQuarter2").html(data);
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
      if ($('#grands_gradesec').val() != '' && $('#grands_branchit').val() != '' ) {
        $.ajax({
          url: "<?php echo base_url(); ?>reportcardiamond/Fetch_studentreportcard/",
          method: "POST",
          data: ({
            gradesec:gradesec,
            branch:branch,
            reportaca:reportaca,
            rpQuarter:rpQuarter
          }),
          cache: false,
          dataType: 'json',
          beforeSend: function() {
            $('.Reportlist').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
          },
          success: function(data) {
            $(".Reportlist").html(data);
          }
        })
      }else {
        alert("All fields are required");
      }
  });
  $('#comment_form2').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('#grands_gradesec2').val();
    var branch=$('#grands_branchit2').val();
    var reportaca=$('#reportaca2').val();
    var rpQuarter=$('#rpQuarter2').val();
      if ($('#grands_gradesec2').val() != '' && $('#grands_branchit2').val() != '' ) {
        $.ajax({
          url: "<?php echo base_url(); ?>reportcardiamond/fetchStudentforCustom/",
          method: "POST",
          data: ({
            gradesec:gradesec,
            branch:branch,
            reportaca:reportaca,
            rpQuarter:rpQuarter
            }),
          async: false,
          cache: false,
          dataType: 'json',
          beforeSend: function() {
            $('.Reportlist2').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
          },
          success: function(data) {
            $(".Reportlist2").html(data);
          }
        })
      }else {
        alert("All fields are required");
      }
  });
  $(document).on('click', '.printThisStudentReport', function() {
    var id=$(this).attr("name");
    var quarter=$(this).attr("value");
    var reportaca=$(this).attr("id");
    $.ajax({
      url: "<?php echo base_url(); ?>reportcardiamond/fetchThisStudentReportcard/",
      method: "POST",
      data: ({
        id:id,
        quarter:quarter,
        reportaca:reportaca
      }),
      async: false,
      cache: false,
      dataType: 'json',
      beforeSend: function() {
        $('.Reportlist2').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
      },
      success: function(data) {
        $(".Reportlist2").html(data);
      }
    })
  });
</script>
<!-- Report card fetch End -->
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("helloFetch");
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
    var print_div = document.getElementById("helloFetch2");
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