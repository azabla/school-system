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
            <div class="fetch_unseen_result_alteration"></div>
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab0" data-toggle="tab" href="#adjustReportCard" role="tab" aria-selected="true">Adjust Report card</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab1" data-toggle="tab" href="#defaultReportCard" role="tab" aria-selected="false">Default Semester R.Card</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab2" data-toggle="tab" href="#customReportCard" role="tab" aria-selected="false">Custom Semester R.Card</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab3" data-toggle="tab" href="#defaultSemesterReportCard" role="tab" aria-selected="false">Default Quarter R.Card</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab4" data-toggle="tab" href="#customSemesterReportCard" role="tab" aria-selected="false">Custom Quarter R.Card</a>
              </li>
            </ul>
            <div class="tab-content tab-bordered" id="myTab3Content">
              <div class="tab-pane fade show active" id="adjustReportCard" role="tabpanel" aria-labelledby="home-tab0">
                <div class="card">
                  <div class="card-header">
                    <div class="row"> 
                      <div class="col-md-6">
                        <button class="btn btn-success" id="adjustRcTable">Adjust Report Card Table</button><a href="#" class="reportCardTableInfo"></a>
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
                      <div class="card-header">
                        <form id="comment_form">
                         <div class="row">
                           <div class="col-lg-2 col-6">
                             <div class="form-group">
                              <select class="form-control selectric" required="required" name="reportaca" id="reportaca">
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
                            <input type="checkbox" name="includeBackPageDefault" id="includeBackPageDefault" value="1">Include Back Page
                            <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="gethisreport"> View
                            </button>
                          </div>
                        </div>
                       </form>
                     </div>
                    </div>
                    <div class="row table-responsive">
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
                      <div class="card-header">
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
                            <input type="checkbox" name="includeBackPage" id="includeBackPage" value="1">Include Back Page
                            <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="gethisreport2"> View
                            </button>
                          </div>
                        </div>
                       </form>
                     </div>
                    </div>
                    <div class="row table-responsive">
                      <div class="col-lg-12 Reportlist2" id="helloFetch2">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade show" id="defaultSemesterReportCard" role="tabpanel" aria-labelledby="home-tab3">
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">
                        <div class="row"> 
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="helloFetchQ1" onclick="helloFetchQ1()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                      </div>
                      <div class="card-header">
                        <form id="comment_formQ1">
                         <div class="row">
                           <div class="col-lg-2 col-6">
                             <div class="form-group">
                              <select class="form-control selectric" required="required" name="reportacaQ1" id="reportacaQ1">
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
                               <select class="form-control selectric" required="required" name="branchQ1" id="branchQ1">
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
                               required="required" name="gradesecQ1" id="gradesecQ1">
                               <option> --- Grade --- </option>
                               </select>
                              </div>
                             </div>
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="quarterQ1" id="quarterQ1">
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
                            <input type="checkbox" name="includeBackPageQ1" id="includeBackPageQ1" value="1">Include Back Page
                            <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="gethisreportQ1"> View Card
                            </button>
                          </div>
                        </div>
                       </form>
                     </div>
                    </div>
                    <div class="row table-responsive">
                      <div class="col-lg-12 ReportlistQ1" id="helloFetchQ1">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade show" id="customSemesterReportCard" role="tabpanel" aria-labelledby="home-tab4">
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">
                        <div class="row"> 
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="helloFetchQ2" onclick="helloFetchQ2()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                      </div>
                      <div class="card-header">
                        <form id="comment_formQ2">
                         <div class="row">
                           <div class="col-lg-2 col-6">
                             <div class="form-group">
                              <select class="form-control selectric" required="required" name="reportacaQ2" id="reportacaQ2">
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
                               <select class="form-control selectric" required="required" name="branchQ2" id="branchQ2">
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
                               required="required" name="gradesecQ2" id="gradesecQ2">
                               <option> --- Grade --- </option>
                               </select>
                              </div>
                             </div>
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="quarterQ2" id="quarterQ2">
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
                            <input type="checkbox" name="includeBackPageQ2" id="includeBackPageQ2" value="1">Include Back Page
                            <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="gethisreportQ2"> View Card
                            </button>
                          </div>
                        </div>
                       </form>
                     </div>
                    </div>
                    <div class="row table-responsive">
                      <div class="col-lg-12 ReportlistQ2" id="helloFetchQ2">
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
<script type="text/javascript">
    function helloFetchQ1(){
        var print_div = document.getElementById("helloFetchQ1");
        var print_area = window.open();
        print_area.document.write(print_div.innerHTML);
        print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
        print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
        print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
        print_area.document.close();
        print_area.focus();
        print_area.print();
    }
    function helloFetchQ2(){
        var print_div = document.getElementById("helloFetchQ2");
        var print_area = window.open();
        print_area.document.write(print_div.innerHTML);
        print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
        print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
        print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
        print_area.document.close();
        print_area.focus();
        print_area.print();
    }
    $('#comment_formQ2').on('submit', function(event) {
        event.preventDefault();
        var gradesec=$('#gradesecQ2').val();
        var branch=$('#branchQ2').val();
        var reportaca=$('#reportacaQ2').val();
        var rpQuarter=$('#quarterQ2').val();
        
          if ($('#gradesecQ2').val() != '' && $('#branchQ2').val() != '' ) {
            $.ajax({
              url: "<?php echo base_url(); ?>reportcard/fetchStudentforCustom_quarter/",
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
                $('.ReportlistQ2').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
              },
              success: function(data) {
                $(".ReportlistQ2").html(data);
              }
            })
          }else {
            alert("All fields are required");
          }
    });
$(document).ready(function() {  
        $("#reportacaQ2").bind("change", function() {
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>reportcard/filterQuarterfromAcademicYear/",
            data: "academicyear=" + $("#reportacaQ2").val(),
            beforeSend: function() {
              $('#quarterQ2').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $("#quarterQ2").html(data);
            }
          });
        });
    });
    $(document).ready(function() {  
        $("#reportacaQ2").bind("change", function() {
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>reportcard/filterGradesecfromBranch/",
            data: "academicyear=" + $("#reportacaQ2").val(),
            beforeSend: function() {
              $('#branchQ2').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $("#branchQ2").html(data);
            }
          });
        });
    });
    $(document).ready(function() {  
        $("#branchQ2").bind("change", function() {
          var branchit=$("#branchQ2").val();
          var academicyear=$("#reportacaQ2").val();
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>reportcard/filterGradefromBranch/",
            data: ({
              branchit: branchit,
              academicyear:academicyear
            }),
            beforeSend: function() {
              $('#gradesecQ2').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $("#gradesecQ2").html(data);
            }
          });
        });
    });
    $('#comment_formQ1').on('submit', function(event) {
        event.preventDefault();
        var gradesec=$('#gradesecQ1').val();
        var branch=$('#branchQ1').val();
        var reportaca=$('#reportacaQ1').val();
        var rpQuarter=$('#quarterQ1').val();
        
          if ($('#gradesecQ1').val() != '' && $('#branchQ1').val() != '' ) {
            $.ajax({
              url: "<?php echo base_url(); ?>reportcard/Fetch_studentreportcard_quarter/",
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
                $('.ReportlistQ1').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
              },
              success: function(data) {
                $(".ReportlistQ1").html(data);
              }
            })
          }else {
            alert("All fields are required");
          }
    });
    $(document).on('click', '.printThisStudentReport_quarter', function() {
        if($('#includeBackPageQ2').is(':checked')){
          var includeBackPage='1';
        }else{
          var includeBackPage='0';
        }
        var id=$(this).attr("name");
        var quarter=$(this).attr("value");
        var reportaca=$(this).attr("id");
        $.ajax({
          url: "<?php echo base_url(); ?>reportcard/fetchThisStudentReportcard_quarter/",
          method: "POST",
          data: ({
            id:id,
            quarter:quarter,
            reportaca:reportaca,
            includeBackPage:includeBackPage
          }),
          cache: false,
          dataType: 'json',
          beforeSend: function() {
            $('.ReportlistQ2').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success: function(data) {
            $(".ReportlistQ2").html(data);
          }
        })
    });
    $(document).ready(function() {  
        $("#reportacaQ1").bind("change", function() {
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>reportcard/filterQuarterfromAcademicYear/",
            data: "academicyear=" + $("#reportacaQ1").val(),
            beforeSend: function() {
              $('#quarterQ1').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $("#quarterQ1").html(data);
            }
          });
        });
    });
    $(document).ready(function() {  
        $("#reportacaQ1").bind("change", function() {
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>reportcard/filterGradesecfromBranch/",
            data: "academicyear=" + $("#reportacaQ1").val(),
            beforeSend: function() {
              $('#branchQ1').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $("#branchQ1").html(data);
            }
          });
        });
    });
    $(document).ready(function() {  
        $("#branchQ1").bind("change", function() {
          var branchit=$("#branchQ1").val();
          var academicyear=$("#reportacaQ1").val();
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>reportcard/filterGradefromBranch/",
            data: ({
              branchit: branchit,
              academicyear:academicyear
            }),
            beforeSend: function() {
              $('#gradesecQ1').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $("#gradesecQ1").html(data);
            }
          });
        });
    });
$(document).ready(function() {  
    $("#reportaca").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcard/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportaca").val(),
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
    $("#reportaca2").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcard/filterGradesecfromBranch/",
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
    $("#adjustRcTable").on("click", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcard/adjustRcTable/",
        beforeSend: function() {
          $('.reportCardTableInfo').html('Adjusting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".reportCardTableInfo").html(data);
          /*swal('Report card table adjusted successfully!', {
            icon: 'success',
          });*/
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
        url: "<?php echo base_url(); ?>reportcard/filterGradefromBranch/",
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
        url: "<?php echo base_url(); ?>reportcard/filterGradefromBranch/",
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
        url: "<?php echo base_url(); ?>reportcard/filterQuarterfromAcademicYear/",
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
        url: "<?php echo base_url(); ?>reportcard/filterQuarterfromAcademicYear/",
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
    if($('#includeBackPageDefault').is(':checked')){
      var includeBackPageDefault='1';
    }else{
      var includeBackPageDefault='0';
    }
      if ($('#grands_gradesec').val() != '' && $('#grands_branchit').val() != '' ) {
        $.ajax({
          url: "<?php echo base_url(); ?>reportcard/Fetch_studentreportcard/",
          method: "POST",
          data: ({
            gradesec:gradesec,
            branch:branch,
            reportaca:reportaca,
            rpQuarter:rpQuarter,
            includeBackPageDefault:includeBackPageDefault
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
          url: "<?php echo base_url(); ?>reportcard/fetchStudentforCustom/",
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
    if($('#includeBackPage').is(':checked')){
      var includeBackPage='1';
    }else{
      var includeBackPage='0';
    }
    var id=$(this).attr("name");
    var quarter=$(this).attr("value");
    var reportaca=$(this).attr("id");
    $.ajax({
      url: "<?php echo base_url(); ?>reportcard/fetchThisStudentReportcard/",
      method: "POST",
      data: ({
        id:id,
        quarter:quarter,
        reportaca:reportaca,
        includeBackPage:includeBackPage
      }),
      cache: false,
      dataType: 'json',
      beforeSend: function() {
        $('.Reportlist2').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data) {
        $(".Reportlist2").html(data);
      }
    })
  });
</script>
<!-- Report card fetch End -->
<script type="text/javascript">
  function codespeedySemester(){
    var print_div = document.getElementById("helloFetchSemester");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
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
<script>
  $(document).ready(function() {  
    function unseen_resultalteration(view = '') { 
      $.ajax({
        url: "<?php echo base_url() ?>unseen_resultalteration/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType: "json",
        success: function(data) {
          $('.fetch_unseen_result_alteration').html(data.notification);
        }
      });
    }  
    unseen_resultalteration();
    setInterval(function() {
      unseen_resultalteration();
    }, 20000);
  });
</script>
</html>