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
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
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
                    <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#studentRecordReport" role="tab" aria-selected="true">Staff Record Report</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab5" data-toggle="tab" href="#staffQualificationReport" role="tab" aria-selected="true">Staff Qualification Report</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="studentRecordReport" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                        <span class="text-black"> <i data-feather="printer"></i> </span>
                        </button>
                        <button type="submit" id="dataExportExcelS" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="comment_form">
                      <div class="row">
                        <div class="col-lg-4 col-6">
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
                        <div class="col-lg-4 col-6">
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
                        <div class="col-lg-4 col-12">
                          <input type="checkbox" name="includeName" id="includeName"> Include Name
                          <button class="btn btn-primary btn-block" type="submit" name="viewmark">View</button>
                        </div>
                      </div>
                    </form>
                    <div class="SummaryRecord" id="SummaryRecord"> </div>
                  </div>
                  <div class="tab-pane fade show" id="staffQualificationReport" role="tabpanel" aria-labelledby="home-tab5">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyQualification()">
                        <span class="text-black"> <i data-feather="printer"></i> </span>
                        </button>
                        <button type="submit" id="dataExportExcelSQualification" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="comment_form_qualification">
                      <div class="row">
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required"
                              name="grands_academicyear_qualification" id="grands_academicyear_qualification">
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
                            <select class="form-control" required="required" name="branch_qualification" id="branch_qualification">
                              <option>--- Branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-12">
                          <input type="checkbox" name="includeNameQualification" id="includeNameQualification"> Include Name
                          <button class="btn btn-primary btn-block" type="submit" name="viewmark_qualification">View Report</button>
                        </div>
                      </div>
                    </form>
                    <div class="SummaryRecord_qualification" id="SummaryRecord_qualification"> 
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
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  $("#dataExportExcelSQualification").click(function(e) {
  let file = new Blob([$('.SummaryRecord_qualification').html()], {type:"application/vnd.ms-excel"});
  let url = URL.createObjectURL(file);
  let a = $("<a />", {
    href: url,
    download: "Staff Qualification Report.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
  });
  function codespeedy(){
    var print_div = document.getElementById("SummaryRecord_qualification");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  $("#dataExportExcelS").click(function(e) {
  let file = new Blob([$('.SummaryRecord').html()], {type:"application/vnd.ms-excel"});
  let url = URL.createObjectURL(file);
  let a = $("<a />", {
    href: url,
    download: "Staff Report.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
  });
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
  function codespeedyQualification(){
    var print_div = document.getElementById("SummaryRecord_qualification");
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
  $('#comment_form_qualification').on('submit', function(event) {
    event.preventDefault();
    var grands_academicyear=$('#grands_academicyear_qualification').val();
    var gs_branches=$('#branch_qualification').val();
    if($('#includeNameQualification').is(':checked')){
      var includeHeader='1';
    }else{
      var includeHeader='0';
    }
    if ($('#branch_qualification').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>staffreport/FecthThisDivStaffQualificationReport/",
        method: "POST",
        data: ({
          gs_branches: gs_branches,
          grands_academicyear:grands_academicyear,
          includeHeader:includeHeader
        }),
        beforeSend: function() {
          $('.SummaryRecord_qualification').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".SummaryRecord_qualification").html(data);
        }
      })
    }else {
      swal('Please select all necessary fields', {
          icon: 'error',
      });
    }
  });
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var grands_academicyear=$('#grands_academicyear').val();
    var gs_branches=$('#grands_branchit').val();
    if ($('#grands_branchit').val() != '') {
      if($('#includeName').is(':checked')){
        $.ajax({
          url: "<?php echo base_url(); ?>staffreport/FecthThisDivStudentAgeWithName/",
          method: "POST",
          data: ({
            gs_branches: gs_branches,
            grands_academicyear:grands_academicyear
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
          url: "<?php echo base_url(); ?>staffreport/FecthThisDivStudentNOName/",
          method: "POST",
          data: ({
            gs_branches: gs_branches,
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
    }else {
      swal('Please select all necessary fields', {
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