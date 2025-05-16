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
                <div class="card">
                  <div class="card-header">
                    <h4>Payment Report </h4>
                  </div>
                <div class="StudentViewTextInfo">
                    <div class="row">
                        <div class="col-lg-12 col-12">
                             <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                              </button>
                        </div>
                      <div class="col-lg-3 col-6">
                         <div class="form-group">
                           <select class="form-control selectric" required="required" name="academicyear" id="grands_academicyear">
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
                           <select class="form-control" required="required" name="branch" id="grands_branchit">
                           <option>--- Branch ---</option>
                           
                           </select>
                          </div>
                         </div>
                         <div class="col-lg-3 col-6">
                          <div class="form-group">
                           <select class="form-control grands_gradesec" name="gradesec" id="grands_gradesec">
                           <option>--- Section ---</option>
                           </select>
                          </div>
                         </div>
                       <div class="col-lg-3 col-6">
                        <button class="btn btn-primary btn-block" 
                        type="submit" id="fetchStudent" name="viewmark">View Report</button>
                      </div>
                    </div>
                    <div class="listStudentShow" id="student_view"></div>
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
  <script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("student_view");
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
    $(document).on('click', '#fetchStudent', function() {
        event.preventDefault();
        var gs_branches=$('#grands_branchit').val();
        var gs_gradesec=$('.grands_gradesec').val();
        var grands_academicyear=$('#grands_academicyear').val();
        if ($('.grands_gradesec').val() != '') {
          $.ajax({
            url: "<?php echo base_url(); ?>paymentreport/fecth_thistudent_report/",
            method: "POST",
            data: ({
              gs_branches: gs_branches,
              gs_gradesec:gs_gradesec,
              grands_academicyear:grands_academicyear
            }),
            beforeSend: function() {
              $('.listStudentShow').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
            },
            success: function(data) {
              $(".listStudentShow").html(data);
            }
          })
        }else {
          swal('All fields are required.', {
            icon: 'error',
          });
        }
    });
    $(document).ready(function() {  
        $("#grands_academicyear").bind("change", function() {
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>paymentreport/filterGradesecfromBranch/",
            data: "academicyear=" + $("#grands_academicyear").val(),
            beforeSend: function() {
              $('#grands_branchit').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $("#grands_branchit").html(data);
            }
          });
        });
    });
    $(document).ready(function() {  
        $("#grands_branchit").bind("change", function() {
          var branchit=$('#grands_branchit').val();
          var grands_academicyear=$('#grands_academicyear').val();
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>paymentreport/Filter_grade_from_branch/",
            data: ({
                branchit: branchit,
                grands_academicyear:grands_academicyear
            }),
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
<script type="text/javascript">
     $(document).ready(function() {  
        $("#gradesec").bind("change", function() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>Filter_gradesec_for_payment/",
                data: "gradesec=" + $("#gradesec").val(),
                 beforeSend: function() {
                    $('.list').html(
                        '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
                    );
                },
                success: function(data) {
                    $(".list").html(data);
                }
            });
        });
    });
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
</body>

</html>