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
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="fetch_unseen_result_alteration"></div>
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#sendCustomEmail" role="tab" aria-selected="false">Custom Email result</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#sendGroupEmail" role="tab" aria-selected="false">Group Email result</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="sendCustomEmail" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="row">
                      <div class="col-12">
                        <form id="comment_form2">
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
                               <select class="form-control selectric" required="required" name="branch2" id="grands_branchit2">
                               <option> --- Select Branch --- </option>
                                <!-- <?php foreach($branch as $branchs){ ?>
                                  <option value="<?php echo $branchs->name;?>">
                                  <?php echo $branchs->name;?>
                                  </option>
                                <?php }?> -->
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
                            type="submit" name="gethisreport2"> View Student
                            </button>
                          </div>
                        </div>
                       </form>
                        <div class="row table-responsive">
                          <div class="col-lg-12 Reportlist2" id="helloFetch2">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="sendGroupEmail" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="row">
                      <div class="col-12">
                        <form id="comment_formGroup">
                         <div class="row">
                           <div class="col-lg-2 col-6">
                             <div class="form-group">
                              <select class="form-control selectric" required="required" name="reportacaGroup" id="reportacaGroup">
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
                               <select class="form-control selectric" required="required" name="branchGroup" id="branchGroup">
                               <option> --- Select Branch --- </option>
                                <!-- <?php foreach($branch as $branchs){ ?>
                                  <option value="<?php echo $branchs->name;?>">
                                  <?php echo $branchs->name;?>
                                  </option>
                                <?php }?> -->
                               </select>
                              </div>
                            </div>
                             <div class="col-lg-2 col-6">
                              <div class="form-group">
                               <select class="form-control"
                               required="required" name="gradesecGroup" id="gradesecGroup">
                               <option> --- Grade --- </option>
                               </select>
                              </div>
                             </div>
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="quarterGroup" id="quarterGroup">
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
                            type="submit" name="gethisreport2"> Send Result
                            </button>
                          </div>
                        </div>
                       </form>
                        <div class="row table-responsive">
                          <div class="col-lg-12 ReportlistGroup" id="ReportlistGroup">
                          </div>

                        </div>
                        
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
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script>
  $('#comment_formGroup').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('#gradesecGroup').val();
    var branch=$('#branchGroup').val();
    var reportaca=$('#reportacaGroup').val();
    var rpQuarter=$('#quarterGroup').val();
    
    if ($('#gradesecGroup').val() != '' && $('#branchGroup').val() != '' ) {
      $.ajax({
        url: "<?php echo base_url(); ?>Sendemailresult/fetchThisGradeReportcard/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          branch:branch,
          reportaca:reportaca,
          rpQuarter:rpQuarter
        }),
        cache: false,
        beforeSend: function() {
          $('.ReportlistGroup').html('Sending<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success: function(data) {
          $(".ReportlistGroup").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $(document).on('click', '.sendThisStudentResult', function() {
    var id=$(this).attr("name");
    var quarter=$(this).attr("value");
    var reportaca=$(this).attr("id");
    $.ajax({
      url: "<?php echo base_url(); ?>Sendemailresult/fetchThisStudentReportcard/",
      method: "POST",
      data: ({
        id:id,
        quarter:quarter,
        reportaca:reportaca
      }),
      cache: false,
      dataType: 'json',
      beforeSend: function() {
        $('.Reportlist2').html('Sending<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data) {
        $(".Reportlist2").html(data);
      }
    })
  });
  $('#comment_form2').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('#grands_gradesec2').val();
    var branch=$('#grands_branchit2').val();
    var reportaca=$('#reportaca2').val();
    var rpQuarter=$('#rpQuarter2').val();
    
    if ($('#grands_gradesec2').val() != '' && $('#grands_branchit2').val() != '' ) {
      $.ajax({
        url: "<?php echo base_url(); ?>Sendemailresult/fetchStudentforCustom/",
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
          $('.Reportlist2').html('Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success: function(data) {
          $(".Reportlist2").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $(document).ready(function() {  
    $("#reportaca2").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Sendemailresult/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportaca2").val(),
        beforeSend: function() {
          $('#grands_branchit2').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_branchit2").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportacaGroup").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Sendemailresult/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportacaGroup").val(),
        beforeSend: function() {
          $('#branchGroup').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#branchGroup").html(data);
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
        url: "<?php echo base_url(); ?>Sendemailresult/filterGradefromBranch/",
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
    $("#branchGroup").bind("change", function() {
      var branchit=$("#branchGroup").val();
      var academicyear=$("#reportacaGroup").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Sendemailresult/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#gradesecGroup').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradesecGroup").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportaca2").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Sendemailresult/filterQuarterfromAcademicYear/",
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
  $(document).ready(function() {  
    $("#reportacaGroup").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Sendemailresult/filterQuarterfromAcademicYear/",
        data: "academicyear=" + $("#reportacaGroup").val(),
        beforeSend: function() {
          $('#quarterGroup').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#quarterGroup").html(data);
        }
      });
    });
  });

</script>


</html>