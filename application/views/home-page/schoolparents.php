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
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-lg-12 col-12">
                <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                  <i data-feather="printer"></i>
                </button>
              </div>
            </div>
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <div class="row">
                  <div class="col-lg-2 col-6">
                    <div class="form-group">
                      <select class="form-control" required="required"  name="reportaca" id="reportacaIDParents">
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
                      <select class="form-control" required="required" name="branchIDParents" id="branchIDParents">
                        <option> --- Branch --- </option>
                        <?php foreach($branch as $branchs){ ?>
                        <option value="<?php echo $branchs->name;?>">
                          <?php echo $branchs->name;?>
                        </option>
                        <?php }?>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-6 table-responsive" style="height:15vh;">
                    <div class="form-group" id="gradesecIDParents">
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <button class="btn btn-primary btn-block" id="view_school_parents" type="submit" name="gethisroster"> View
                    </button>
                  </div>
                </div>
                <div class="school_parents_page" id="helloStuIDCard"></div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="view_school_parents_child" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="copy_exam_page" id="copy_exam_page">Guardian`s Child </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="view_this_school_parents_child"></div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $(document).on('click', '.view_school_parents_child', function() {
        var username=$(this).attr("value");
        var year=$(this).attr("id");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Schoolparents/fetch_this_parent_child/",
          data: ({
            username:username,
            year:year
          }),
          cache: false,
          beforeSend: function() {
            $('.view_this_school_parents_child').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(html){
            $('.view_this_school_parents_child').html(html);     
          }
        }); 
      });
      $(document).on('click', '#view_school_parents', function() {
        parentGrade=[];
        $("input[name='fect_schoolParents_grade']:checked").each(function(i){
          parentGrade[i]=$(this).val();
        });
        var branch=$("#branchIDParents").val();
        var reportacaID=$("#reportacaIDParents").val();
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>Schoolparents/fetchschoolparents/",
           data: ({
            branch:branch,
            parentGrade: parentGrade,
            reportacaID:reportacaID
          }),
          beforeSend: function() {
            $('.school_parents_page').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
              );
          },
          success: function(data) {
            $(".school_parents_page").html(data);
          }
        });
      });  
      $("#branchIDParents").bind("change", function() {
        var branchit=$('#branchIDParents').val();
        var grands_academicyear=$('#reportacaIDParents').val();
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>Schoolparents/Filter_grade_from_branch/",
          data: ({
              branchit: branchit,
              grands_academicyear:grands_academicyear
          }),
          beforeSend: function() {
            $('#gradesecIDParents').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $("#gradesecIDParents").html(data);
          }
        });
      });
    });
    function codespeedy(){
      var print_div = document.getElementById("helloStuIDCard");
      var print_area = window.open();
      print_area.document.write(print_div.innerHTML);
      print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
      print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
      print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
      print_area.document.close();
      print_area.focus();
      print_area.print();
    }
    $(document).ready(function(){
      $.ajax({
        url:"<?php echo base_url(); ?>Schoolparents/fetchschoolparents/",
        method:"POST",
        beforeSend: function() {
          $('.school_parents_page').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.school_parents_page').html(data);
        }
      })
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
</body>

</html>