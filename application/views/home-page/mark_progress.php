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
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#sectionMarkProgress" role="tab" aria-selected="true"> Mark progress</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#gradeMarkProgress" role="tab" aria-selected="false">By Grade(Cross Check)</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="sectionMarkProgress" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row">
                          <div class="col-md-6 col-6"> </div>
                          <div class="col-md-6 col-6">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                            <span class="text-black">
                            <i data-feather="printer"></i>
                            </span>
                           </button>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 col-12">
                            <div class="form-group">
                             <select class="form-control selectric"
                             required="required" name="branch" id="branch2progress">
                             <option>--- Select branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-12 table-responsive" style="height:15vh">
                            <div class="form-group" id="grade2progress"> </div>
                          </div>
                          <div class="col-md-3 col-12">
                            <button class="btn btn-primary btn-block btn-sm viewprogress">
                              View Progress
                            </button>
                          </div>
                        </div>
                        <div class="listprogressHere" id="helloHere"> </div>
                      </div>
                      <div class="tab-pane fade show" id="gradeMarkProgress" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row">
                          <div class="col-md-6 col-6"> </div>
                          <div class="col-md-6 col-6">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyCrossGrade()">
                            <span class="text-black">
                            <i data-feather="printer"></i>
                            </span>
                           </button>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 col-12">
                            <div class="form-group">
                             <select class="form-control selectric"
                             required="required" name="branch" id="branchCrossprogress">
                             <option>--- Select branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-12 table-responsive" style="height:15vh">
                            <div class="form-group" id="corssProgress"> </div>
                          </div>
                          <div class="col-md-3 col-12">
                            <button class="btn btn-primary btn-block btn-sm viewGradeProgress">
                              Show Progress
                            </button>
                          </div>
                        </div>
                        <div class="gradeprogressHere" id="gradeProgress"> </div>
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
<script>
  $('.gs-sms-manage-mark-page').addClass('active');
  $(document).on('click', '.viewGradeProgress', function() {
    grade=[];
    $("input[name='studentGradeCrossProgress']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    var branch=$('#branchCrossprogress').val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Markprogress/Gradecrossprogress/",
      data: ({
        branch:branch,
        grade: grade
      }),
      cache: false,
      beforeSend: function() {
        $('.gradeprogressHere').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.gradeprogressHere').html(html);
      }
    }); 
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branchCrossprogress").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Markprogress/fetchCrossGradeFromBranch/",
        data: "branchit=" + $("#branchCrossprogress").val(),
        beforeSend: function() {
          $('#corssProgress').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#corssProgress").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branch2progress").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Markprogress/fetchGradeSecFromBranch/",
        data: "branchit=" + $("#branch2progress").val(),
        beforeSend: function() {
          $('#grade2progress').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grade2progress").html(data);
        }
      });
    });
  });
</script>
<script>
  $(document).on('click', '.viewprogress', function() {
    grade=[];
    $("input[name='studentGradeSecJoss']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    var branch=$('#branch2progress').val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Markprogress/Check_markprogress/",
      data: ({
        branch:branch,
        grade: grade
      }),
      cache: false,
      beforeSend: function() {
        $('.listprogressHere').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.listprogressHere').html(html);
      }
    }); 
  });
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
  function codespeedyCrossGrade(){
    var print_div = document.getElementById("gradeProgress");
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