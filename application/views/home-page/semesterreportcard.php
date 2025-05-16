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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css"> <!-- New -->
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
              <div class="col-lg-12 col-12">
                <span class="text-danger"><i class="fas fa-exclamation-circle"></i> This table can't auto update marks so that it should be accessed after report card generated. </span>
                <a href="#" class="addSemesterCalculationMethod" value="" data-toggle="modal" data-target="#addSemesterCalculationMethod"><span class="text-success">
                  <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Create Calculation Method</button>
                 </span>
                </a>
              </div>
              <div class="col-lg-12 col-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab0" data-toggle="tab" href="#customSemesterCard" role="tab" aria-selected="true">Custom Semester Card</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab1" data-toggle="tab" href="#defaultSemesterCard" role="tab" aria-selected="false">Default Semester Card</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#semesterroster" role="tab" aria-selected="false">Semester Roster</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="customSemesterCard" role="tabpanel" aria-labelledby="home-tab0">
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
                           <div class="col-lg-3 col-6">
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
                               </select>
                              </div>
                            </div>
                             <div class="col-lg-3 col-6">
                              <div class="form-group">
                               <select class="form-control"
                               required="required" name="gradesec2" id="grands_gradesec2">
                               <option> --- Grade --- </option>
                               </select>
                              </div>
                             </div>
                           <div class="col-lg-3 col-6">
                            <input type="checkbox" name="includeBackPage" id="includeBackPage" value="1">Include Back Page
                            <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="gethisreport2"> View
                            </button>
                            </div>
                          </div>
                        </form>
                        <div class="Reportlist2" id="helloFetch2"> </div>
                      </div>
                      <div class="tab-pane fade show" id="defaultSemesterCard" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row"> 
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedySemester()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                        <form id="comment_formSemester">
                         <div class="row">
                           <div class="col-lg-3 col-6">
                             <div class="form-group">
                              <select class="form-control selectric" required="required" name="reportacaSemester" id="reportacaSemester">
                                <option>---Academic Year---</option>
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
                               <select class="form-control selectric" required="required" name="branchSemester" id="grands_branchitSemester">
                               <option> --- Select Branch --- </option>
                                <?php foreach($branch as $branchs){ ?>
                                  <option value="<?php echo $branchs->name;?>">
                                  <?php echo $branchs->name;?>
                                  </option>
                                <?php }?>
                               </select>
                              </div>
                            </div>
                             <div class="col-lg-3 col-6">
                              <div class="form-group">
                               <select class="form-control"
                               required="required" name="gradesecSemester" id="grands_gradesecSemester">
                               <option> --- Grade --- </option>
                               </select>
                              </div>
                             </div>
                           <div class="col-lg-3 col-6">
                            <input type="checkbox" name="includeBackPageDefault" id="includeBackPageDefault" value="1">Include Back Page
                            <button class="btn btn-primary btn-block btn-lg" type="submit" name="gethisreportSemester"> View
                            </button>
                          </div>
                        </div>
                       </form>
                       <div class="ReportlistSemester" id="helloFetchSemester"> </div>
                      </div>
                      <div class="tab-pane fade show" id="semesterroster" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row"> 
                          <div class="col-md-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedySemesterRoster()">
                              <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                        <form id="comment_formSemesterRoster">
                         <div class="row">
                           <div class="col-lg-2 col-6">
                             <div class="form-group">
                              <select class="form-control selectric" required="required" name="reportacaSemesterRoster" id="reportacaSemesterRoster">
                                <option>---Academic Year---</option>
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
                               <select class="form-control selectric" required="required" name="branchSemesterRoster" id="grands_branchitSemesterRoster">
                               <option> --- Select Branch --- </option>
                                <?php foreach($branch as $branchs){ ?>
                                  <option value="<?php echo $branchs->name;?>">
                                  <?php echo $branchs->name;?>
                                  </option>
                                <?php }?>
                               </select>
                              </div>
                            </div>
                             <div class="col-lg-3 col-6">
                              <div class="form-group">
                               <select class="form-control"
                               required="required" name="gradesecSemesterRoster" id="grands_gradesecSemesterRoster">
                               <option> --- Grade --- </option>
                               </select>
                              </div>
                             </div>
                             <div class="col-lg-2 col-6">
                              <div class="form-group">
                                <select class="form-control" required="required" name="pageBreak" id="pageBreak">
                                  <option> --- No.Page --- </option>
                                  <?php for($i=1;$i<6;$i++){?>
                                    <option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
                                  <?php } ?>
                                 
                                </select>
                              </div>
                            </div>
                           <div class="col-lg-2 col-12">
                            <button class="btn btn-primary btn-block btn-lg" type="submit" name="gethisreportSemesterRoster"> View Roster
                            </button>
                          </div>
                        </div>
                       </form>
                       <div class="ReportlistSemesterRoster" id="helloFetchSemesterRoster"> </div>
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
  <div class="modal fade" id="addSemesterCalculationMethod" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle"></h5> &nbsp;
          <label class="text-danger">Separate Term2 evaluation lists to Term1 and Term3 to calculate semester card for each grade. Assume Term1 is for semester1 and Term3 is for semester2</label>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="card-body StudentViewTextInfo">
            <div class="modal-body">
              <form id="saveCustomSemesterCalculation" method="POST">
                <div class="row">
                  <div class="col-lg-4 col-12 table-responsive">
                    <div class="form-group">
                      <div class="row">
                        <?php foreach($grade as $grades){ ?>
                          <div class="col-lg-3 col-6">
                          <div class="pretty p-bigger">
                           <input id="semestereva_grade_custom" type="radio" class="semestereva_grade_custom" name="semestereva_grade_custom" value="<?php echo $grades->grade; ?>">
                           <div class="state p-primary">
                              <i class="icon material-icons"></i>
                              <label></label><?php echo $grades->grade; ?>
                           </div>
                           </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-12 table-responsive semesterevaluation_here_custom" style="height:15vh"> 
                  </div>
                  <div class="col-lg-6 col-12">

                    <div class="form-group">
                      <select class="form-control" id="selectSemester" name="selectSemester" required>
                        <option></option>
                        <option>Term1</option>
                        <option>Term3</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6 col-12  pull-right">
                    <div class="form-group">
                      <button type="submit" name="postCustomSemester" class="btn btn-info btn-block">Save method </button>
                    </div>
                  </div>
                </div>
              </form>
              <div class="loadSavedCalculation" id="loadSavedCalculation"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script> <!-- New -->
</body>
  <script type="text/javascript">
    function loadSavedCalculation() /*New*/
    {
      $.ajax({
        url:"<?php echo base_url(); ?>semesterreportcard/loadSavedCalculation/",
        method:"POST",
        beforeSend: function() {
          $('.loadSavedCalculation').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.loadSavedCalculation').html(data);
        }
      })
    }
    $(document).on('click', '.addSemesterCalculationMethod', function(e) { /*New*/
      e.preventDefault();
      loadSavedCalculation();
    });
    $(document).on('click', '#remove_loadSavedCalculation', function(e) { /*New*/
      e.preventDefault();
      var userid=$(this).attr("value");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>semesterreportcard/remove_loadSavedCalculation/",
        data: ({
          userid: userid
        }),
        beforeSend: function() {
          $('.remove_loadSavedCalculation' + userid).html( '<span class="text-info">Removing...</span>');
          $('#remove_loadSavedCalculation').attr( 'disabled','disabled');
        },
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Removed successfully',
              message: '',
              position: 'topRight'
            });
            loadSavedCalculation();
          }else{
            iziToast.error({
              title: 'Please try later',
              message: '',
              position: 'topRight'
            });
          }
          $('.remove_loadSavedCalculation' + userid).html( 'Remove');
          $('#remove_loadSavedCalculation').removeAttr( 'disabled');
        }
      });
    });
    $('#saveCustomSemesterCalculation').on('submit', function(event) { /*New*/
      event.preventDefault();
      var semester=$('#selectSemester').val();
      grade=[];subject=[];evalname=[];
      $("input[name='semestereva_grade_custom']:checked").each(function(i){
        grade[i]=$(this).val();
      });
      $("input[name='assesment4CustomSemestercard']:checked").each(function(i){
        evalname[i]=$(this).val();
      });
      if( grade.length == 0 || $('.eva_percent_custom').val() =='' || evalname.length == 0)
      {
        swal('Oooops, Please select necessary fields!', {
          icon: 'warning',
        });
      }else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>semesterreportcard/postCustomEvaluation/",
          data: ({
            grade: grade,
            evalname:evalname,
            semester:semester
          }),
          cache: false,
          success: function(html){
            if(html==1){
              iziToast.success({
                title: 'Setting saved successfully.',
                message: '',
                position: 'topRight'
              });
              
              $('#saveCustomSemesterCalculation')[0].reset();
            }else{
              iziToast.error({
                title: 'Oooops unable to save setting.',
                message: '',
                position: 'topRight'
              });
            }
            loadSavedCalculation();
          }
        });
      }
    });
    $(document).on('click', '.semestereva_grade_custom', function() { /*New*/
      grade2analysis=[];
      $("input[name='semestereva_grade_custom']:checked").each(function(i){
        grade2analysis[i]=$(this).val();
      });
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>semesterreportcard/filterAssesmentCustomEvaluation/",
        data:({
          grade2analysis:grade2analysis
        }),
        beforeSend: function() {
          $('.semesterevaluation_here_custom').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".semesterevaluation_here_custom").html(data);
        }
      });
    });
    $('#comment_formSemesterRoster').on('submit', function(event) {
      event.preventDefault();
      var gradesec=$('#grands_gradesecSemesterRoster').val();
      var branch=$('#grands_branchitSemesterRoster').val();
      var reportaca=$('#reportacaSemesterRoster').val();
      var pageBreak=$('#pageBreak').val();
      if ($('#grands_gradesecSemesterRoster').val() != '--- Grade ---' && $('#grands_branchitSemesterRoster').val() != '--- Select Branch ---' ) {
        $.ajax({
          url: "<?php echo base_url(); ?>semesterreportcard/Fetch_student_semester_roster/",
          method: "POST",
          data: ({
            gradesec:gradesec,
            branch:branch,
            reportaca:reportaca,
            pageBreak:pageBreak
          }),
          cache: false,
          beforeSend: function() {
            $('.ReportlistSemesterRoster').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success: function(data) {
            $(".ReportlistSemesterRoster").html(data);
          }
        })
      }else {
        swal('Oooops, Please select necessary fields!', {
          icon: 'warning',
        });
      }
    });
    $('#comment_formSemester').on('submit', function(event) {
      event.preventDefault();
      var gradesec=$('#grands_gradesecSemester').val();
      var branch=$('#grands_branchitSemester').val();
      var reportaca=$('#reportacaSemester').val();
      if($('#includeBackPageDefault').is(':checked')){
        var includeBackPage='1';
      }else{
        var includeBackPage='0';
      }
      if ($('#grands_gradesecSemester').val() != '--- Grade ---' && $('#grands_branchitSemester').val() != '--- Select Branch ---' ) {
        $.ajax({
          url: "<?php echo base_url(); ?>semesterreportcard/Fetch_studentreportcard/",
          method: "POST",
          data: ({
            gradesec:gradesec,
            branch:branch,
            reportaca:reportaca,
            includeBackPage:includeBackPage
          }),
          cache: false,
          beforeSend: function() {
            $('.ReportlistSemester').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success: function(data) {
            $(".ReportlistSemester").html(data);
          }
        })
      }else {
        swal('Oooops, Please select necessary fields!', {
          icon: 'warning',
        });
      }
    });
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
    function codespeedySemesterRoster(){
      var print_div = document.getElementById("helloFetchSemesterRoster");
      var print_area = window.open();
      print_area.document.write(print_div.innerHTML);
      print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
      print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
      print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
      print_area.document.close();
      print_area.focus();
      print_area.print();
    }
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
    $(document).on('click', '.printThisStudentSemesterReport', function() {
      if($('#includeBackPage').is(':checked')){
        var includeBackPage='1';
      }else{
        var includeBackPage='0';
      }
      var id=$(this).attr("name");
      var reportaca=$(this).attr("id");
      $.ajax({
        url: "<?php echo base_url(); ?>semesterreportcard/fetchThisStudentReportcard/",
        method: "POST",
        data: ({
          id:id,
          reportaca:reportaca,
          includeBackPage:includeBackPage
        }),
        cache: false,
        beforeSend: function() {
          $('.Reportlist2').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
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
      if ($('#grands_gradesec2').val() != '--- Grade ---' && $('#grands_branchit2').val() != '--- Select Branch ---' ) {
        $.ajax({
          url: "<?php echo base_url(); ?>semesterreportcard/fetchStudentforCustom/",
          method: "POST",
          data: ({
            gradesec:gradesec,
            branch:branch,
            reportaca:reportaca
          }),
          cache: false,
          beforeSend: function() {
            $('.Reportlist2').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success: function(data) {
            $(".Reportlist2").html(data);
          }
        })
      }else {
        swal('Oooops, Please select necessary fields!', {
          icon: 'warning',
        });
      }
    });
    $(document).ready(function() {  
      $("#reportacaSemester").bind("change", function() {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>semesterreportcard/filterGradesecfromBranch/",
          data: "academicyear=" + $("#reportacaSemester").val(),
          beforeSend: function() {
            $('#grands_branchitSemester').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $("#grands_branchitSemester").html(data);
          }
        });
      });
    });
    $(document).ready(function() {  
      $("#reportacaSemesterRoster").bind("change", function() {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>semesterreportcard/filterGradesecfromBranch/",
          data: "academicyear=" + $("#reportacaSemesterRoster").val(),
          beforeSend: function() {
            $('#grands_branchitSemesterRoster').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $("#grands_branchitSemesterRoster").html(data);
          }
        });
      });
    });
    $(document).ready(function() {  
      $("#grands_branchitSemester").bind("change", function() {
        var branchit=$("#grands_branchitSemester").val();
        var academicyear=$("#reportacaSemester").val();
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>semesterreportcard/filterGradefromBranch/",
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
      $("#grands_branchitSemesterRoster").bind("change", function() {
        var branchit=$("#grands_branchitSemesterRoster").val();
        var academicyear=$("#reportacaSemesterRoster").val();
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>semesterreportcard/filterGradefromBranch/",
          data: ({
            branchit: branchit,
            academicyear:academicyear
          }),
          beforeSend: function() {
            $('#grands_gradesecSemesterRoster').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $("#grands_gradesecSemesterRoster").html(data);
          }
        });
      });
    });
    $(document).ready(function() {  
      $("#reportaca2").bind("change", function() {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>semesterreportcard/filterGradesecfromBranch/",
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
      $("#grands_branchit2").bind("change", function() {
        var branchit=$("#grands_branchit2").val();
        var academicyear=$("#reportaca2").val();
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>semesterreportcard/filterGradefromBranch/",
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
  </script>
</html>