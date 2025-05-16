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
                    <h5 class="header-title">Export Student Mark Format</h5>
                  </div>
                  <?php include('bgcolor.php'); ?>
                  <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                  <div class="card-body">
                    <form method="POST" action="<?php echo base_url(); ?>Exportmarkformat/export/">
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
                         <div class="col-lg-3 col-6">
                          <div class="form-group">
                           <select class="form-control selectric" required="required" name="branch" id="branch_mformat">
                           <option>--- Select Branch ---</option>
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
                           <select class="form-control" required="required" name="gradesec"  id="gradesec_mformat">
                           <option>--- Select Grade ---</option>
                            
                           </select>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                         <div class="form-group">
                           <select class="form-control selectric" required="required" name="quarter" 
                           id="quarter">
                           <option>--- Select Quarter ---</option>
                           </select>
                          </div>
                         </div>
                       <div class="col-lg-2 col-12">
                        <button class="btn btn-primary btn-block btn-lg" type="submit" name="gethisgrade">Prepare</button>
                      </div>
                    </div>
                  </form>
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
    $("#reportaca2").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>exportmarkformat/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportaca2").val(),
        beforeSend: function() {
          $('#branch_mformat').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#branch_mformat").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportaca2").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>exportmarkformat/filterQuarterfromAcademicYear/",
        data: "academicyear=" + $("#reportaca2").val(),
        beforeSend: function() {
          $('#quarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#quarter").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#gradesec_mformat").bind("change", function() {
      var gradesec= $("#gradesec_mformat").val();
      var academicyear= $("#reportaca2").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>exportmarkformat/filterTermfromAcademicYear/",
        data: ({
          gradesec: gradesec,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#quarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
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
    $("#branch_mformat").bind("change", function() {
      var branchit= $("#branch_mformat").val();
      var academicyear= $("#reportaca2").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>exportmarkformat/filterGradesecfromAcademicYear/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#gradesec_mformat').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradesec_mformat").html(data);
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