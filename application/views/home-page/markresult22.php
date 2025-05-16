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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css">
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
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card">
            <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                        <h4><a href="<?php echo base_url() ?>Markresult/"><small class="text-muted">Mark result (Method 1)</small> </a></h4>
                  </div>
                  <div class="col-lg-6">
                    <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                    <span class="text-black">
                      <i data-feather="printer"></i>
                    </span>
                    </button>
                  </div>
                </div>
             <form method="GET" id="comment_form">
                <div class="row">
                  <div class="col-lg-2">
                    <div class="form-group">
                      <select class="form-control selectric" required="required" name="academicyear"  id="grands_academicyear">
                      <?php foreach($academicyear as $academicyears){ ?>
                        <option value="<?php echo $academicyears->year_name;?>">
                          <?php echo $academicyears->year_name;?>
                        </option>
                      <?php }?>
                      </select>
                    </div>
                  </div>
                    <div class="col-lg-4">
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
                  <div class="col-lg-4">
                    <div class="form-group">
                      <select class="form-control grands_gradesec" required="required" name="gradesec" id="grands_gradesec">
                        <option>--- Grade ---</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <select class="form-control grands_subject" name="subject">
                        <option>--- Select Subject ---</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <select class="form-control selectric" required="required" name="quarter" 
                      id="grands_quarter">
                        <option>--- Select Quarter ---</option>
                        <?php foreach($fetch_term as $fetch_terms){ ?>
                          <option value="<?php echo $fetch_terms->term;?>">
                          <?php echo $fetch_terms->term;?>
                          </option>
                        <?php }?>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <button class="btn btn-light btn-lg" 
                      type="submit" name="viewmark">View
                    </button>
                  </div>
                </div>
              </form> 
              <div class="listmark" id="mark_view"></div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <footer class="main-footer">
        <div class="footer-left">
         Call:+251967829025 &nbsp;&nbsp;
          Copyright &copy<?php echo date('Y');?>
          <a href="https://www.grandstande.com" target="_blanck">GrandStand</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</body>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("mark_view");
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
<!-- Subject change script starts -->
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_gradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_subject_from_subject/",
        data: "gradesec=" + $("#grands_gradesec").val(),
        beforeSend: function() {
          $('.grands_subject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".grands_subject").html(data);
        }
      });
    });
  });
</script>
<!-- Subject change script ends -->
<!-- Fecth mark script starts -->
<script type="text/javascript">
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var gs_branches=$('#grands_branchit').val();
    var gs_gradesec=$('.grands_gradesec').val();
    var gs_subject=$('.grands_subject').val();
    var gs_quarter=$('#grands_quarter').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Markresult2/fecthMarkresult/",
        method: "GET",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject,
          gs_quarter:gs_quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listmark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">' );
        },
        success: function(data) {
          $(".listmark").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<!-- Fetch mark ends -->
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
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_subject_from_subject/",
        data: "gradesec=" + $("#gradesec").val(),
        beforeSend: function() {
          $('.subject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".subject").html(data);
        }
      });
    });
  });
</script> 
</html>