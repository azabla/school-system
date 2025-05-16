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
            <div class="row">
              <div class="col-12 col-md-12">
                <div class="card">
                  <div class="card-header">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab1" data-toggle="tab" href="#createAchiever" role="tab" aria-selected="true"> Create Achiever Range</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#EvaluationAnalysis" role="tab" aria-selected="true">Evaluation Analysis</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#AssesmentAnalysis" role="tab" aria-selected="false">Assesment Analysis</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab4" data-toggle="tab" href="#reportStatistics" role="tab" aria-selected="false">Report Statistics</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show" id="createAchiever" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                        <div class="card">
                          <div class="card-body StudentViewTextInfo">
                            <div class="row">
                              <div class="col-lg-6 col-6 form-group">
                                <input type="text" name="rangedescription" id="rangedescription" class="form-control" placeholder="Achiever name here..." required>
                              </div>
                              <div class="col-lg-6 col-6 form-group">
                                <input type="number" name="lowrange" id="lowrange" class="form-control" placeholder="Min value..." required>
                              </div>
                              <div class="col-lg-6 col-6 form-group">
                                <input type="number" name="highrange" id="highrange" class="form-control" placeholder="Max value..." required>
                              </div>
                              <div class="col-lg-6 col-6 form-group">
                                <input type="textt" name="achieverremark" id="achieverremark" class="form-control" placeholder="Remark..." required>
                              </div>
                              <div class="col-lg-12 col-12 table-responsive" style="height: 20vh;">
                                <div class="form-group">
                                  <label for="Mobile">Select grade</label><br>
                                  <div class="row">
                                    <?php foreach($gradeSec as $gradess){ ?>
                                      <div class="col-lg-4 col-4">
                                        <div class="pretty p-bigger">
                                          <input id="eva_grade" type="checkbox" name="eva_grade" value="<?php echo $gradess->grade; ?>">
                                          <div class="state p-info">
                                            <i class="icon material-icons"></i>
                                            <label></label><?php echo $gradess->grade; ?>
                                          </div>
                                        </div>
                                      </div>
                                    <?php } ?>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-12 col-12 form-group">
                                <button class="btn btn-primary btn-block" id="saveAchiever" type="submit">Save Achiever</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <div class="fetchAchiever"></div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show active" id="EvaluationAnalysis" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                          <span class="text-black">
                          <i data-feather="printer"></i>
                          </span>
                        </button>
                        <button type="submit" onclick="exportTableToExcel('helloUsers', 'Subject Analysis')" id="dataExport" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel
                        </button>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-body StudentViewTextInfo">
                        <div class="row">
                          <div class="col-md-2 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="quarter" id="analysis_quarter">
                                <option>--- Quarter ---</option>
                                <?php foreach($fetch_term as $fetch_terms){ ?>
                                  <option value="<?php echo $fetch_terms->term;?>">
                                  <?php echo $fetch_terms->term;?>
                                  </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3 col-6 table-responsive" style="height: 20vh;">
                            <div class="form-group">
                              <div class="row">
                              <?php foreach($gradeSec as $grades){ ?>
                                <div class="col-lg-6 col-6">
                                  <div class="pretty p-icon p-jelly p-bigger">
                                    <input type="checkbox" name="gradeCustomAnalysisGrandstandeD" value="<?php echo $grades->grade;?>" class="gradeCustomAnalysisGrandstandeD" id="customCheck1">
                                    <div class="state p-success">
                                      <i class="icon material-icons"></i>
                                      <label></label><?php echo $grades->grade;?>
                                    </div>
                                  </div> 
                                </div>
                                <?php }?>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-3 col-6 table-responsive Evaluation_here" style="height: 20vh;">
                          </div>
                          <div class="col-md-4 col-12 table-responsive defaultSubjectHere" style="height: 20vh;">
                          </div>
                          <div class="col-md-6 col-6">
                            <select class="form-control" required="required" name="achiverName"  id="achiverName">
                              <option>Select Range</option>
                              <?php foreach($achievername as $achievernames){ ?>
                                <option value="<?php echo $achievernames->achievername;?>">
                                <?php echo $achievernames->achievername;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                          <div class="col-md-6 col-6">
                             <input type="checkbox" name="inAllSelectedSubject" id="inAllSelectedSubject" value="1"> In All Selected Subject
                            <button class="btn btn-primary btn-block btn-lg viewanalysis"> View Analysis 
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="listanalysisHere" id="helloHere"> </div>
                  </div>
                  <div class="tab-pane fade show" id="AssesmentAnalysis" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="card">
                      <div class="card-body StudentViewTextInfo">
                        <div class="row">
                          <div class="col-md-2 col-5">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="quarter"  id="customquarter">
                                <option>--- Quarter ---</option>
                                <?php foreach($fetch_term as $fetch_terms){ ?>
                                  <option value="<?php echo $fetch_terms->term;?>">
                                  <?php echo $fetch_terms->term;?>
                                  </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                         
                          <div class="col-md-4 col-7 table-responsive" style="height: 15vh;">
                            <div class="row">
                              <?php foreach($gradeSec as $grades){ ?>
                              <div class="col-lg-4 col-6">
                                <div class="pretty p-icon p-jelly p-bigger">
                                  <input type="checkbox" name="gradeAnalysisGrandstandeD" value="<?php echo $grades->grade;?>" class="gradeAnalysisGrandstandeD" id="customCheck1">
                                  <div class="state p-success">
                                    <i class="icon material-icons"></i>
                                    <label></label><?php echo $grades->grade;?>
                                  </div>
                                </div> 
                              </div>
                              <?php }?>
                            </div>
                          </div>
                          <div class="col-md-6 col-6">
                             <select class="form-control selectric customEvaluation_here" required="required" name="customEvaluation_here"  id="customEvaluation_here">
                             </select>
                          </div>
                          <div class="col-md-6 col-6 table-responsive customSubjectHere" style="height: 15vh;">
                          </div>
                          <div class="col-md-3 col-6">
                            <div class="form-group">
                              <select class="form-control" required="required" name="assesment_outof_range" id="assesment_outof_range">
                                <option>-Range- </option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3 col-6">
                            <button class="btn btn-primary btn-block btn-lg viewCustomAnalysis"> View Analysis 
                            </button>
                          </div>                          
                        </div>
                      </div>
                    </div>
                    <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedycustom()">
                    <span class="text-black">
                    <i data-feather="printer"></i>
                    </span>
                   </button>
                  <div class="customAlysisHere" id="helloCustomHere"> </div>
                  </div>
                  <div class="tab-pane fade show" id="reportStatistics" role="tabpanel" aria-labelledby="home-tab4">
                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="card">
                          <div class="card-body StudentViewTextInfo">
                            <div id="plist" class="people-list">
                              <div class="m-b-20">
                                <form id="comment_formGrade">
                                  <div class="row">
                                    <!-- <div class="col-lg-6 col-6">
                                      <div class="form-group">
                                       <select class="form-control selectric" required="required" name="Year_statisticsGrade" id="Year_statisticsGrade">
                                       <option>---Year --- </option>
                                        <?php foreach($academicyear as $academicyears){ ?>
                                          <option value="<?php echo $academicyears->year_name;?>">
                                          <?php echo $academicyears->year_name;?>
                                          </option>
                                        <?php }?>
                                       </select>
                                      </div>
                                    </div> -->
                                    <div class="col-lg-12 col-12">
                                      <div class="form-group">
                                        <select class="form-control" required="required" name="grade_statisticsGrade" id="grade_statisticsGrade">
                                          <option>-Grade- </option>
                                          <?php foreach($gradeSec as $grades){ ?>
                                          <option value="<?php echo $grades->grade?>">
                                          <?php echo $grades->grade;?>
                                          </option>
                                        <?php }?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-lg-12 col-12 table-responsive" id="subject_statisticsHereGrade" style="height:15vh">
                                    </div>
                                    
                                    <div class="col-lg-12 col-12">
                                      <hr>
                                      <div class="form-group">
                                        <div class="row">
                                          <?php foreach($fetch_termGrade as $fetch_terms){ ?>
                                          <div class="col-md-6 col-12">
                                            <div class="form-group">
                                              <div class="pretty p-icon p-jelly p-bigger">
                                                <input type="checkbox" name="quarter_statisticsGrade[ ]" value="<?php echo $fetch_terms->term; ?>" id="customCheck1 quarter_statisticsGrade">
                                                <div class="state p-info">
                                                  <i class="icon material-icons"></i>
                                                  <label></label><?php echo $fetch_terms->term; ?>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <?php }?>
                                          <!--  -->
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-5 col-5">
                                      <div class="form-group">
                                       <select class="form-control" required="required" name="less_thanGrade" id="less_thanGrade">
                                        <?php $a=.99; for($i=100;$i>=1;$i--){
                                          if($i==100){ ?>
                                            <option value="<?php echo $i ?>"><small><= </small> <?php echo $i;?></option>
                                          <?php } else { ?>
                                            <option value="<?php echo $i + $a ?>"><small><= </small> <?php echo $i + $a;?></option>
                                          <?php } } ?>
                                       </select>
                                      </div>
                                    </div>
                                    <div class="col-lg-5 col-5">
                                      <div class="form-group">
                                       <select class="form-control" required="required" name="greater_thanGrade" id="greater_thanGrade">
                                        <?php for($i=1;$i<=100;$i++){ ?>
                                          <option value="<?php echo $i ?>"><small>>= </small> <?php echo $i;?></option>
                                        <?php } ?>
                                       </select>
                                      </div>
                                    </div>
                                    <div class="col-lg-2 col-2">
                                      <button class="btn btn-default text-info" id="saveCriteria"><i class="fas fa-plus-circle"></i></button>
                                    </div>
                                    <div class="col-lg-12 col-12">
                                      <div class="listHereGs"></div>
                                    </div>
                                    <div class="col-lg-12 col-12">
                                      <input type="checkbox" name="includeName" id="includeName"> Include Name
                                      <button class="btn btn-primary btn-block" type="submit" name="getrank"> View </button>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                        <div class="chat">
                          <div class="chat-about">
                            <div class="row"> 
                              <div class="col-md-6 col-6"> </div>
                              <div class="col-md-6 col-6">
                                <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyGrade()">
                                  <span class="text-black">
                                  <i data-feather="printer"></i>
                                  </span>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="listReportGrade" id="helloReportGrade">  </div>
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
  $(document).on('click', '.customSubjectAnalysis', function() {
    subjectName=[];
    $("input[name='customSubjectAnalysis']:checked").each(function(i){
      subjectName[i]=$(this).val();
    });
    grade2analysis=[];
    $("input[name='gradeAnalysisGrandstandeD']:checked").each(function(i){
      grade2analysis[i]=$(this).val();
    });
    var customEvaluation_here=$("#customEvaluation_here").val();
    var analysis_quarter=$("#customquarter").val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Resultstatistics/fetch_assesment_outof/",
      data:({
        subjectName:subjectName,
        customEvaluation_here:customEvaluation_here,
        grade2analysis:grade2analysis,
        analysis_quarter:analysis_quarter
      }),
      beforeSend: function() {
        $('#assesment_outof_range').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      success: function(data) {
        $("#assesment_outof_range").html(data);
      }
    });
  });
  load_data();
  function load_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>Resultstatistics/fetchRange/",
      method:"POST",
      beforeSend: function() {
        $('.listHereGs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="20" height="20" id="loa">');
      },
      success:function(data){
        $('.listHereGs').html(data);
      }
    })
  }
  $(document).on('click', '#saveCriteria', function() {
    event.preventDefault();
    var lessThan=$('#less_thanGrade').val();
    var greaterThan=$('#greater_thanGrade').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Resultstatistics/saveRange/",
      data: ({
        lessThan:lessThan,
        greaterThan:greaterThan
      }),
      success: function(data) {
        $(".listHereGs").html(data);
      }
    });
  });
  $(document).on('click', '.btnRemove', function() {
    event.preventDefault();
    var id=$(this).attr("id");
    var minValue=$(this).attr("value");
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Resultstatistics/removeRange/",
      data: ({
        lessThan:id,
        greaterThan:minValue
      }),
      success: function(data) {
        $(".listHereGs").html(data);
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '.viewCustomAnalysis', function() {
    grade2analysis=[];
    $("input[name='gradeAnalysisGrandstandeD']:checked").each(function(i){
      grade2analysis[i]=$(this).val();
    });
    subjectanalysis=[];
    $("input[name='defaultSubjectAnalysis_new']:checked").each(function(i){
      subjectanalysis[i]=$(this).val();
    });
    var quarter=$('#customquarter').val();
    var outof_range=$('#assesment_outof_range').val();
    var customEvaluation_here=$('#customEvaluation_here').val();
    if(grade2analysis.length!=0 && subjectanalysis.length!=0){
      $.ajax({
        url: "<?php echo base_url(); ?>Resultstatistics/fetchCustomAnalysis/",
        method: "POST",
        data: ({
          gradesec:grade2analysis,
          evaluation:customEvaluation_here,
          subject:subjectanalysis,
          quarter:quarter,
          outof_range:outof_range
        }),
        beforeSend: function() {
          $('.customAlysisHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".customAlysisHere").html(data);
        }
      });
    }else{
      swal('Please select all necessary fields!', {
        icon: 'error',
      });
    }
  })
</script>
<script type="text/javascript">
  $(document).ready(function() {  
     $(document).on('click', '.gradeAnalysisGrandstandeD', function() {
      grade2analysis=[];
      $("input[name='gradeAnalysisGrandstandeD']:checked").each(function(i){
        grade2analysis[i]=$(this).val();
      });
      var analysis_quarter=$("#customquarter").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Resultstatistics/filterEvaluationCustomAnalysis/",
        data:({
          grade2analysis:grade2analysis,
          analysis_quarter:analysis_quarter
        }),
        beforeSend: function() {
          $('.customEvaluation_here').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".customEvaluation_here").html(data);
        }
      });
    });
  });
  $(document).on('click', '.gradeAnalysisGrandstandeD', function() {
    grade2analysis=[];
    $("input[name='gradeAnalysisGrandstandeD']:checked").each(function(i){
      grade2analysis[i]=$(this).val();
    });
    var analysis_quarter=$("#customquarter").val();
    var customEvaluation_here=$("#customEvaluation_here").val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Resultstatistics/filterSubjectCustomAnalysis/",
      data:({
        grade2analysis:grade2analysis,
        analysis_quarter:analysis_quarter,
        customEvaluation_here:customEvaluation_here
      }),
      beforeSend: function() {
        $('.customSubjectHere').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      success: function(data) {
        $(".customSubjectHere").html(data);
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '.viewanalysis', function() {
    grade2analysis=[];
    $("input[name='gradeCustomAnalysisGrandstandeD']:checked").each(function(i){
      grade2analysis[i]=$(this).val();
    });

    evaluationanalysis=[];
    $("input[name='evaluationanalysis']:checked").each(function(i){
      evaluationanalysis[i]=$(this).val();
    });
    subjectanalysis=[];
    $("input[name='defaultSubjectAnalysis']:checked").each(function(i){
      subjectanalysis[i]=$(this).val();
    });
    var quarter=$('#analysis_quarter').val();
    var achiverName=$('#achiverName').val();
    var achiverName=$('#achiverName').val();
    if($('#inAllSelectedSubject').is(':checked')){
      var inAllSelectedSubject='1';
    }else{
      var inAllSelectedSubject='0';
    }
    if(evaluationanalysis.length!=0 && grade2analysis.length!=0 && subjectanalysis.length!=0){
      $.ajax({
        url: "<?php echo base_url(); ?>Resultstatistics/fetch_analysis/",
        method: "POST",
        data: ({
          gradesec:grade2analysis,
          evaluation:evaluationanalysis,
          quarter:quarter,
          subjectanalysis:subjectanalysis,
          achiverName:achiverName,
          inAllSelectedSubject:inAllSelectedSubject
        }),
        beforeSend: function() {
          $('.listanalysisHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listanalysisHere").html(data);
        }
      });
    }else{
      swal('Please select all necessary fields!', {
        icon: 'error',
      });
    }
  })
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $(".gradeCustomAnalysisGrandstandeD").bind("change", function() {
      grade2analysis=[];
      $("input[name='gradeCustomAnalysisGrandstandeD']:checked").each(function(i){
        grade2analysis[i]=$(this).val();
      });
      var analysis_quarter=$("#analysis_quarter").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Resultstatistics/filter_evaluation4analysis/",
        data:({
          grade2analysis:grade2analysis,
          analysis_quarter:analysis_quarter
        }),
        beforeSend: function() {
          $('.Evaluation_here').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".Evaluation_here").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $(".gradeCustomAnalysisGrandstandeD").bind("change", function() {
      grade2analysis=[];
      $("input[name='gradeCustomAnalysisGrandstandeD']:checked").each(function(i){
        grade2analysis[i]=$(this).val();
      });
      var analysis_quarter=$("#analysis_quarter").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Resultstatistics/filterSubjectDefaultAnalysis/",
        data:({
          grade2analysis:grade2analysis,
          analysis_quarter:analysis_quarter
        }),
        beforeSend: function() {
          $('.defaultSubjectHere').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".defaultSubjectHere").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    loadSchoolAssesment();
    function loadSchoolAssesment() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>Resultstatistics/fetchAchiever/',
        cache: false,
        beforeSend: function() {
          $('.fetchAchiever').html( 'Loading Achiever...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(html){
         $('.fetchAchiever').html(html);
        }
      })
    }
    $("#saveAchiever").on("click",function(event){
      var achieverName=$("#rangedescription").val();
      var minVal=$("#lowrange").val();
      var maxVal=$("#highrange").val();
      var achieverRemark=$("#achieverremark").val();

      eva_grade=[];
      $("input[name='eva_grade']:checked").each(function(i){
        eva_grade[i]=$(this).val();
      });
      if($("#rangedescription").val()!=='' || $("#lowrange").val()!=='' || $("#highrange").val()!=='' || eva_grade.length!=0){
        $.ajax({
          url:"<?php echo base_url() ?>Resultstatistics/saveAchiever/",
          method:"POST",
          data:({
            achieverName:achieverName,
            minVal:minVal,
            maxVal:maxVal,
            eva_grade:eva_grade,
            achieverRemark:achieverRemark
          }),
          beforeSend:function(){
            $("#saveAchiever").attr("disabled","disabled");
          },
          success: function(){
            loadSchoolAssesment();
            $("#rangedescription").val('');
            $("#lowrange").val('');
            $("#highrange").val('');
            $("#achieverremark").val('');
            $("input[name='eva_grade']").each(function(i){
              $(this).prop('checked',false);
            });
            $("#saveAchiever").removeAttr("disabled");
          }
        });
      }else{
        swal('Please insert all necessary fields.', {
          icon: 'error',
        });
      }
    });
    $(document).on('click', '.deleteachievername', function() {
      var textId = $(this).attr("id");
      var textValue = $(this).attr("value");
      var textName = $(this).attr("name");
       swal({
          title: 'Are you sure you want to delete this text ?',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Resultstatistics/deleteAchiever/",
            data: ({
              textId: textId,
              textValue:textValue,
              textName:textName
            }),
            cache: false,
            success: function(html) {
              loadSchoolAssesment();
            }
          });
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $('#comment_formGrade').on('submit', function(event) {
    event.preventDefault();
    subStatistics=[];quarterStatistics=[];
    $("input[name='subject_statisticsGrade']:checked").each(function(i){
      subStatistics[i]=$(this).val();
    });
    $("input[name='quarter_statisticsGrade[ ]']:checked").each(function(i){
      quarterStatistics[i]=$(this).val();
    });
    /*var quarterStatistics=$("#quarter_statisticsGrade").val();*/
    var grade_statistics=$("#grade_statisticsGrade").val();
    var branch_statistics=$("#branchitGrade").val();
    var Year_statisticsGrade=$("#Year_statisticsGrade").val();
    if($('#includeName').is(':checked')){
      var nameChecked=1;
    }else{
      var nameChecked=0;
    }
    if(quarterStatistics.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Resultstatistics/thisGradeMarkStatistics/",
        data: ({
          quarterStatistics:quarterStatistics,
          grade_statistics:grade_statistics,
          branch_statistics:branch_statistics,
          subStatistics:subStatistics,
          nameChecked:nameChecked
        }),
        beforeSend: function() {
          $('.listReportGrade').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".listReportGrade").html(data);
        }
      });
    }else{
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grade_statisticsGrade").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Resultstatistics/fetch_subject_from_gradeSecFilter/",
        data: "gradesec=" + $("#grade_statisticsGrade").val(),
        beforeSend: function() {
          $('#subject_statisticshereGrade').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#subject_statisticsHereGrade").html(data);
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
<script>  
 function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById('helloHere');
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}  
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
  function codespeedyGrade(){
    var print_div = document.getElementById("helloReportGrade");
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
  function codespeedycustom(){
    var print_div = document.getElementById("helloCustomHere");
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