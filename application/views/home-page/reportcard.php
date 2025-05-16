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
                    <a class="nav-link active" id="home-tab0" data-toggle="tab" href="#adjustReportCard" role="tab" aria-selected="true">Adjust Report card</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab15" data-toggle="tab" href="#evaluationReport" role="tab" aria-selected="false">Assesment Report</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab11" data-toggle="tab" href="#groupHalfReport" role="tab" aria-selected="false">Group Half Report</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab22" data-toggle="tab" href="#customHalfReport" role="tab" aria-selected="false">Custom Half Report</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab1" data-toggle="tab" href="#defaultReportCard" role="tab" aria-selected="false">Group Annual Card</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#customReportCard" role="tab" aria-selected="false">Custom Annual Card</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="adjustReportCard" role="tabpanel" aria-labelledby="home-tab0">
                    <div class="row"> 
                      <div class="col-md-12 col-12">
                        <button class="btn btn-success" id="adjustRcTable">Adjust Report Card Table</button><a href="#" class="reportCardTableInfo"></a>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="evaluationReport" role="tabpanel" aria-labelledby="home-tab15">
                    <div class="row"> 
                      <div class="col-md-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyAssesment()">
                          <span class="text-black">
                            <i data-feather="printer"></i>
                          </span>
                        </button>
                      </div>
                    </div>
                    <form id="comment_form_assesment">
                     <div class="row">
                       <div class="col-lg-2 col-6">
                         <div class="form-group">
                          <select class="form-control selectric" required="required" name="reportacaassesment" id="reportacaassesment">
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
                           <select class="form-control selectric" required="required" name="branchassesment" id="grands_branchitassesment">
                           <option> --- Select Branch --- </option>
                           
                           </select>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="quarterassesment" id="rpQuarterassesment">
                              <option>--- Select Quarter ---</option>
                              <?php foreach($fetch_term as $fetch_terms){ ?>
                                <option value="<?php echo $fetch_terms->term;?>">
                                <?php echo $fetch_terms->term;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                         <div class="col-lg-4 col-6">
                          <div class="form-group">
                           <select class="form-control" required="required" name="gradesecassesment" id="grands_gradesecassesment">
                           <option> --- Grade --- </option>
                           </select>
                          </div>
                         </div>
                        <div class="col-lg-8 col-6 table-responsive evaluation_here" style="height: 20vh;">
                        </div>
                       <div class="col-lg-4 col-6">
                        <input type="checkbox" name="includeHeader" id="includeHeader" value="1">Include BS
                        <button class="btn btn-primary btn-block btn-lg" 
                        type="submit" name="gethisreportAssesment"> View Report
                        </button>
                      </div>
                    </div>
                   </form>
                    <div class="ReportlistAssesment" id="helloFetchAssesment"> </div>
                  </div>
                  <div class="tab-pane fade show" id="groupHalfReport" role="tabpanel" aria-labelledby="home-tab11">
                    <div class="row">
                      <div class="col-12">
                        <div class="row"> 
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyGroupHalf()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                        <form id="comment_form_group_half">
                         <div class="row">
                           <div class="col-lg-2 col-6">
                             <div class="form-group">
                              <select class="form-control selectric" required="required" name="reportacaGroupHalf" id="reportacaGroupHalf">
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
                               <select class="form-control selectric" required="required" name="branchGroupHalf" id="grands_branchitGroupHalf">
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
                               <select class="form-control" required="required" name="gradesecGroupHalf" id="grands_gradesecGroupHalf">
                               <option> --- Grade --- </option>
                               </select>
                              </div>
                             </div>
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="quarterGroupHalf" id="rpQuarterGroupHalf">
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
                            <input type="checkbox" name="includeStudentBasicSkillGroup" id="includeStudentBasicSkillGroup" value="1">Include BS
                            <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="gethisreportGroupHalf"> View
                            </button>
                          </div>
                        </div>
                       </form>
                        <div class="ReportlistGroupHalf" id="helloFetchGroupHalf"> </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="customHalfReport" role="tabpanel" aria-labelledby="home-tab22">
                    <div class="row">
                      <div class="col-12">
                        <div class="row"> 
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport2HalfCustom" onclick="codespeedyGroupCustomHalf()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                        <form id="comment_form2CustomHalf">
                         <div class="row">
                           <div class="col-lg-2 col-6">
                             <div class="form-group">
                              <select class="form-control selectric" required="required" name="reportaca2HalfCustom" id="reportaca2HalfCustom">
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
                               <select class="form-control selectric" required="required" name="branch2HalfCustom" id="grands_branchit2HalfCustom">
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
                               <select class="form-control" required="required" name="gradesec2HalfCustom" id="grands_gradesec2HalfCustom">
                               <option> --- Grade --- </option>
                               </select>
                              </div>
                             </div>
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="quarter2HalfCustom" id="rpQuarter2HalfCustom">
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
                            <input type="checkbox" name="includeStudentBasicSkillHalf" id="includeStudentBasicSkillHalf" value="1">Include BS
                            <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="gethisreport2HalfCustom"> View
                            </button>
                          </div>
                        </div>
                       </form>
                        <div class="Reportlist2HalfCustom" id="helloFetch2HalfCustom"> </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="defaultReportCard" role="tabpanel" aria-labelledby="home-tab1">
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
                       <div class="col-lg-2 col-6">
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
                           <select class="form-control selectric" required="required" name="branch" id="grands_branchit">
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
                    <div class="Reportlist" id="helloFetch"></div>
                  </div>
                  <div class="tab-pane fade show" id="customReportCard" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="row">
                      <div class="col-12">
                        <div class="row"> 
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport2" onclick="codespeedy2()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
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
                            <input type="checkbox" name="includeBackPage" id="includeBackPage" value="1">Include Back Page
                            <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="gethisreport2"> View
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
  function codespeedyAssesment(){
    var print_div = document.getElementById("helloFetchAssesment");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  $("#grands_gradesecassesment").bind("change", function() {
    var grade2analysis=$("#grands_gradesecassesment").val();
    var branch2analysis=$("#grands_branchitassesment").val();
    var analysis_quarter=$("#rpQuarterassesment").val();
    if ($('#rpQuarterassesment').val() != '--- Select Quarter ---') {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcard/filter_evaluation4analysis/",
        data:({
          branch2analysis:branch2analysis,
          grade2analysis:grade2analysis,
          analysis_quarter:analysis_quarter
        }),
        beforeSend: function() {
          $('.evaluation_here').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".evaluation_here").html(data);
        }
      });
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
      $("#grands_gradesecassesment").val('');
    }
  });
  $('#comment_form_assesment').on('submit', function(event) {
    event.preventDefault();
    assement_name=[];
    $("input[name='evaluationanalysis']:checked").each(function(i){
      assement_name[i]=$(this).val();
    });
    var gradesec=$('#grands_gradesecassesment').val();
    var branch=$('#grands_branchitassesment').val();
    var reportaca=$('#reportacaassesment').val();
    var rpQuarter=$('#rpQuarterassesment').val();
    if($('#includeHeader').is(':checked')){
      var includeHeader='1';
    }else{
      var includeHeader='0';
    }
    if ($('#rpQuarterassesment').val() != '--- Select Quarter ---' && $('#grands_branchitassesment').val() != '' ) {
      $.ajax({
        url: "<?php echo base_url(); ?>reportcard/fetch_assesment_report/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          branch:branch,
          reportaca:reportaca,
          rpQuarter:rpQuarter,
          assement_name:assement_name,
          includeHeader:includeHeader
        }),
        cache: false,
        dataType: 'json',
        beforeSend: function() {
          $('.ReportlistAssesment').html('Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success: function(data) {
          $(".ReportlistAssesment").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
  $(document).ready(function() {  
    $("#reportacaassesment").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcard/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportacaassesment").val(),
        beforeSend: function() {
          $('#grands_branchitassesment').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_branchitassesment").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#grands_branchitassesment").bind("change", function() {
      var branchit=$("#grands_branchitassesment").val();
      var academicyear=$("#reportacaassesment").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcard/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#grands_gradesecassesment').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_gradesecassesment").html(data);
        }
      });
    });
  });
  function codespeedyGroupCustomHalf(){
    var print_div = document.getElementById("helloFetch2HalfCustom");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  $('#comment_form2CustomHalf').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('#grands_gradesec2HalfCustom').val();
    var branch=$('#grands_branchit2HalfCustom').val();
    var reportaca=$('#reportaca2HalfCustom').val();
    var rpQuarter=$('#rpQuarter2HalfCustom').val();
    
      if ($('#grands_gradesec2HalfCustom').val() != '' && $('#grands_branchit2HalfCustom').val() != '' ) {
        $.ajax({
          url: "<?php echo base_url(); ?>reportcard/fetchCustomStudentHalfReport/",
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
            $('.Reportlist2HalfCustom').html('Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success: function(data) {
            $(".Reportlist2HalfCustom").html(data);
          }
        })
      }else {
        alert("All fields are required");
      }
  });
  $(document).on('click', '.printThisStudentHalfReport', function() {
    event.preventDefault();
    if($('#includeStudentBasicSkillHalf').is(':checked')){
      var includeStudentBasicSkillHalf='1';
    }else{
      var includeStudentBasicSkillHalf='0';
    }
    var id=$(this).attr("name");
    var quarter=$(this).attr("value");
    var reportaca=$(this).attr("id");
    $.ajax({
      url: "<?php echo base_url(); ?>reportcard/customHalfstudentreportcard/",
      method: "POST",
      data: ({
        id:id,
        quarter:quarter,
        reportaca:reportaca,
        includeBackPage:includeStudentBasicSkillHalf
      }),
      cache: false,
      dataType: 'json',
      beforeSend: function() {
        $('.Reportlist2HalfCustom').html('Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data) {
        $(".Reportlist2HalfCustom").html(data);
      }
    })
  });
  $(document).ready(function() {  
    $("#grands_branchit2HalfCustom").bind("change", function() {
      var branchit=$("#grands_branchit2HalfCustom").val();
      var academicyear=$("#reportaca2HalfCustom").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcard/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#grands_gradesec2HalfCustom').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_gradesec2HalfCustom").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportaca2HalfCustom").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcard/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportaca2HalfCustom").val(),
        beforeSend: function() {
          $('#grands_branchit2HalfCustom').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_branchit2HalfCustom").html(data);
        }
      });
    });
  });
</script>
<!--  -->
<script type="text/javascript">
  function codespeedyGroupHalf(){
    var print_div = document.getElementById("helloFetchGroupHalf");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  $('#comment_form_group_half').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('#grands_gradesecGroupHalf').val();
    var branch=$('#grands_branchitGroupHalf').val();
    var reportaca=$('#reportacaGroupHalf').val();
    var rpQuarter=$('#rpQuarterGroupHalf').val();
    if($('#includeStudentBasicSkillGroup').is(':checked')){
      var includeStudentBasicSkill='1';
    }else{
      var includeStudentBasicSkill='0';
    }
    if ($('#grands_gradesecGroupHalf').val() != '' && $('#grands_branchitGroupHalf').val() != '' ) {
      $.ajax({
        url: "<?php echo base_url(); ?>reportcard/grouphalfstudentreportcard/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          branch:branch,
          reportaca:reportaca,
          rpQuarter:rpQuarter,
          includeStudentBasicSkill:includeStudentBasicSkill
        }),
        cache: false,
        dataType: 'json',
        beforeSend: function() {
          $('.ReportlistGroupHalf').html('Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success: function(data) {
          $(".ReportlistGroupHalf").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $(document).ready(function() {  
    $("#grands_branchitGroupHalf").bind("change", function() {
      var branchit=$("#grands_branchitGroupHalf").val();
      var academicyear=$("#reportacaGroupHalf").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcard/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#grands_gradesecGroupHalf').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_gradesecGroupHalf").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportacaGroupHalf").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcard/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportacaGroupHalf").val(),
        beforeSend: function() {
          $('#grands_branchitGroupHalf').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_branchitGroupHalf").html(data);
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
  $('#comment_formSemester').on('submit', function(event) {
    event.preventDefault();
    var gradesecSemester=$('#grands_gradesecSemester').val();
    var branchSemester=$('#grands_branchitSemester').val();
    var reportacaSemester=$('#reportacaSemester').val();
    if ($('#grands_gradesecSemester').val() != '' && $('#grands_branchitSemester').val() != '' ) {
      $.ajax({
        url: "<?php echo base_url(); ?>reportcard/Fetch_studentreportcard_semester/",
        method: "POST",
        data: ({
          gradesec:gradesecSemester,
          branch:branchSemester,
          reportaca:reportacaSemester
          }),
          cache: false,
         dataType: 'json',
        beforeSend: function() {
          $('.ReportlistSemester').html('Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success: function(data) {
          $(".ReportlistSemester").html(data);
        }
      })
    }else {
      alert("All fields are required");
      }
  });
  $(document).ready(function() {  
    $("#grands_branchitSemester").bind("change", function() {
      var branchit=$("#grands_branchitSemester").val();
      var academicyear=$("#reportacaSemester").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportcard/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#grands_gradesecSemester').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_gradesecSemester").html(data);
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
          $('.reportCardTableInfo').html('Adjusting<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
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
            $('.Reportlist').html('Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
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
        $('.Reportlist2').html('Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
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