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
  <link rel='shortcut icon' type='image/x-icon'
   href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
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
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <div class="row">
                  <div class="col-md-12 col-12">
                    <h5 class="header-title">You can copy Student's mark instantly</h5>
                  </div>
                </div>
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#withInEvaluation" role="tab" aria-selected="true">WithIn Assesment</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab4" data-toggle="tab" href="#withInSubject" role="tab" aria-selected="false">WithIn Subject</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#withInQuarter" role="tab" aria-selected="false">WithIn Quarter</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#withInCustom" role="tab" aria-selected="false">Custom Student</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="withInEvaluation" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="alert alert-light alert-dismissible show fade">
                      <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                          <span>&times;</span>
                        </button>
                        Note:This page will copy filled mark from one evaluation assesment to other non-filled evaluation assesment and will convert to 100%.
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="academicyear"  id="academicyearCopyEvaluation">
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
                          <select class="form-control" required="required" name="branch"
                          id="branchCopyEvaluation">
                            <option>--- Branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                              <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                              </option>
                              <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-7 col-6 table-responsive" style="height:20vh;">
                        <div class="form-group" id="gradesecCopyEvaluation"></div>
                      </div>
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <select class="form-control subjectCopyEvaluation" id="subjectCopyEvaluation" name="subject">
                            <option>--- Select Subject ---</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-6">
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
                      <div class="col-lg-4 col-6">
                        <button class="btn btn-primary btn-block" type="submit" id="copyThisSubjectAssesment">
                          Copy Mark
                        </button>
                      </div>
                      <div class="col-lg-12 col-12">
                        <div id="copyMarkInfo"></div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="withInSubject" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="alert alert-light alert-dismissible show fade">
                      <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                          <span>&times;</span>
                        </button>
                       Note:This page will copy filled mark from one subject to other subject.
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="withinsubjectacademicyear"  id="withinsubjectacademicyear">
                          <?php foreach($academicyear as $academicyears){ ?>
                            <option value="<?php echo $academicyears->year_name;?>">
                              <?php echo $academicyears->year_name;?>
                            </option>
                          <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control" required="required" name="withinsubjectbranch"
                          id="withinsubjectbranch">
                            <option>--- Branch ---</option>
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
                          <select class="form-control withinsubjectQuarter" required="required" name="withinsubjectQuarter" id="withinsubjectQuarter">
                            <option>--- Quarter ---</option>
                            <?php foreach($fetch_term as $fetch_terms){ ?>
                              <option value="<?php echo $fetch_terms->term;?>">
                              <?php echo $fetch_terms->term;?>
                              </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-6 col-6 table-responsive" style="height:20vh;">
                        <div class="form-group" id="withinsubjectgradsec"></div>
                      </div>
                      
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control withinsubjectfromsubject" id="withinsubjectfromsubject" name="subject" required>
                            <option>--- From Subject ---</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control withinsubjecttosubject" id="withinsubjecttosubject" name="subject" required>
                            <option>--- To Subject ---</option>
                          </select>
                        </div>
                      </div>
                      
                      <div class="col-lg-3 col-12">
                        <button class="btn btn-primary btn-block" type="submit" id="copyThisSubjectWithInSubject">
                          Copy Mark
                        </button>
                      </div>
                      <div class="col-lg-12 col-12">
                        <div id="copyWithInMarkInfo"></div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="withInQuarter" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="alert alert-light alert-dismissible show fade">
                      <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                          <span>&times;</span>
                        </button>
                       Note:This page will copy filled mark from one quarter assesment to other non-filled quarter evaluation assesment of the same subject and will convert to 100%.
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="academicyear"  id="academicyearCopyQuarter">
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
                          <select class="form-control" required="required" name="branch"
                          id="branchCopyQuarter">
                            <option>--- Branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                              <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                              </option>
                              <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-7 col-6 table-responsive" style="height:20vh;">
                        <div class="form-group" id="gradesecCopyQuarter"></div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control subjectCopyQuarter" id="subjectCopyQuarter" name="subject">
                            <option>--- Select Subject ---</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="quarter" 
                          id="fromgrandsQuarterQuarter">
                            <option>--- From Quarter ---</option>
                            <?php foreach($fetch_term as $fetch_terms){ ?>
                              <option value="<?php echo $fetch_terms->term;?>">
                              <?php echo $fetch_terms->term;?>
                              </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="quarter" 
                          id="tograndsQuarterQuarter">
                            <option>--- To Quarter ---</option>
                            <?php foreach($fetch_term as $fetch_terms){ ?>
                              <option value="<?php echo $fetch_terms->term;?>">
                              <?php echo $fetch_terms->term;?>
                              </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <button class="btn btn-primary btn-block" type="submit" id="copyThisSubjectQuarter">
                          Copy Mark
                        </button>
                      </div>
                      <div class="col-lg-12 col-12">
                        <div id="copyQuarterMarkInfo"></div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="withInCustom" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="alert alert-light alert-dismissible show fade">
                      <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                          <span>&times;</span>
                        </button>
                        Note:This page will copy filled mark from one quarter assesment to other non-filled quarter evaluation assesment of the same subject and will convert to 100%.
                      </div>
                    </div>
                    <div class="row"> 
                      <div class="col-lg-6 col-12">
                        <div class="card-header">
                          <input type="text" class="form-control typeahead" id="searchStudentForCopyMark" name="searchStudentForCopyMark" placeholder="Search Student Id,Name">
                          <div class="table-responsive" style="height:15vh;">
                            <div class="searchPlaceHere"></div> 
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-12">
                        <textarea class="form-control" id="selectStudentForTransportPlace" name="selectStudentForTransportPlace" col="12">  </textarea>
                        <button class="btn btn-default RemoveAll" id="removeAll" type="submit"><i class="fas fa-angle-double-left"></i></button>
                      </div> 
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="quarter" 
                          id="fromgrandsQuarterStudent">
                            <option>--- From Quarter ---</option>
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
                          <select class="form-control selectric" required="required" name="quarter" 
                          id="tograndsQuarterStudent">
                            <option>--- To Quarter ---</option>
                            <?php foreach($fetch_term as $fetch_terms){ ?>
                              <option value="<?php echo $fetch_terms->term;?>">
                              <?php echo $fetch_terms->term;?>
                              </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-6">
                        <div class="card-header">
                          <button type ="submit" class="btn btn-primary btn-block" id="saveNewStudentMark" name="saveNewStudentMark" >Copy Mark</button>
                        </div>
                      </div>
                      <div class="col-lg-12 col-12">
                        <div id="copyStudentMarkInfo"></div>
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
    $(document).ready(function() { 
      $('#searchStudentForCopyMark').on("keyup",function() {
        $searchItem=$('#searchStudentForCopyMark').val();
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>Copymovemark/searchStudentsToCopyMark/",
          data: "searchItem=" + $("#searchStudentForCopyMark").val(),
          beforeSend: function() {
            $('.searchPlaceHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
          },
          success: function(data) {
            $(".searchPlaceHere").html(data);
          }
        });
      });
    });
    $(document).on('click', '.saveThisStudentCopyMarks', function() {
      event.preventDefault();
      var oldText=$('#selectStudentForTransportPlace').val();
      var stuID=$(this).attr("value");
      var newText=oldText+stuID+"\n";
      $("#selectStudentForTransportPlace").val(newText);  
    });
    $(document).on('click', '#removeAll', function() {
      event.preventDefault();
      $("#selectStudentForTransportPlace").val('');   
    });
  </script>
  <script type="text/javascript">
  $(document).on('click', '#saveNewStudentMark', function() {
    swal({
      title: 'Are you sure you want to move selected student quarter mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        var newServicePlace=$('#selectStudentForTransportPlace').val();
        var stuIdArray=newServicePlace.split(/(\s+)/);
        var fromQuarter=$('#fromgrandsQuarterStudent').val();
        var toQuarter=$('#tograndsQuarterStudent').val();
        if ($('#selectStudentForTransportPlace').val()!='') {
          $.ajax({
            url: "<?php echo base_url(); ?>copymovemark/copyAssesmentStudentMark/",
            method: "POST",
            data: ({
              stuIdArray: stuIdArray,
              fromQuarter:fromQuarter,
              toQuarter:toQuarter
            }),
            beforeSend: function() {
              $('#copyStudentMarkInfo').html( 'Coping...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $("#copyStudentMarkInfo").html(data);
            }
          })
        }else {
          swal({
            title: 'All fields are required!',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
        }
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#copyThisSubjectQuarter', function() {
    swal({
      title: 'Are you sure you want to move selected grade quarter mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        gs_gradesec=[];
        $("input[name='selectAllMoveCopyMarkQuarterly[ ]']:checked").each(function(i){
          gs_gradesec[i]=$(this).val();
        });
        var gs_branches=$('#branchCopyQuarter').val();
        var gs_subject=$('#subjectCopyQuarter').val();
        var fromQuarter=$('#fromgrandsQuarterQuarter').val();
        var toQuarter=$('#tograndsQuarterQuarter').val();
        var grands_academicyear=$('#academicyearCopyQuarter').val();
        if (gs_gradesec.length!=0 && $('#subjectCopyQuarter').val()!='') {
          $.ajax({
            url: "<?php echo base_url(); ?>copymovemark/copyAssesmentQuarterMark/",
            method: "POST",
            data: ({
              gs_branches: gs_branches,
              gs_gradesec:gs_gradesec,
              gs_subject:gs_subject,
              fromQuarter:fromQuarter,
              toQuarter:toQuarter,
              grands_academicyear:grands_academicyear
            }),
            beforeSend: function() {
              $('#copyQuarterMarkInfo').html( 'Copying...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $("#copyQuarterMarkInfo").html(data);
            }
          })
        }else {
          swal({
            title: 'All fields are required!',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
        }
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '.selectAllMoveCopyMarkQuarterly', function() {
    grade=[];
    $("input[name='selectAllMoveCopyMarkQuarterly[ ]']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    var academicyear=$("#academicyearCopyQuarter").val();
    if(grade.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>copymovemark/fetchThisGradeSubjectQuarterly/",
         data: ({
          grade: grade,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#subjectCopyQuarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $("#subjectCopyQuarter").html(data);
        }
      });
    }
  });
</script>

<!-- Grade change script starts-->
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branchCopyQuarter").bind("change", function() {
      var branch=$("#branchCopyQuarter").val();
      var academicyear=$("#academicyearCopyQuarter").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>copymovemark/copyMarkStudentListQuarter/",
        data: ({
          academicyear:academicyear,
          branch:branch
        }),
        beforeSend: function() {
          $('#gradesecCopyQuarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradesecCopyQuarter").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#withinsubjectbranch").bind("change", function() {
      var branch=$("#withinsubjectbranch").val();
      var academicyear=$("#withinsubjectacademicyear").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>copymovemark/fetchGradeWithInSubject/",
        data: ({
          academicyear:academicyear,
          branch:branch
        }),
        beforeSend: function() {
          $('#withinsubjectgradsec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#withinsubjectgradsec").html(data);
        }
      });
    });
  });
  $(document).on('click', '.selectAllMoveCopyMarkSubjectly', function() {
    grade=[];
    $("input[name='selectAllMoveCopyMarkSubjectly[ ]']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    var academicyear=$("#withinsubjectacademicyear").val();
    if(grade.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>copymovemark/fetchThisGradeSubjectSubjectly/",
         data: ({
          grade: grade,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#withinsubjecttosubject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $("#withinsubjecttosubject").html(data);
        }
      });
    }
  });
  $(document).on('click', '.selectAllMoveCopyMarkSubjectly', function() {
    grade=[];
    $("input[name='selectAllMoveCopyMarkSubjectly[ ]']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    var academicyear=$("#withinsubjectacademicyear").val();
    var toQuarter=$('#withinsubjectQuarter').val();
    var gs_branches=$('#withinsubjectbranch').val();
    if(grade.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>copymovemark/fetchThisGradeSubjectFromark/",
         data: ({
          grade: grade,
          academicyear:academicyear,
          toQuarter:toQuarter,
          gs_branches:gs_branches
        }),
        beforeSend: function() {
          $('#withinsubjectfromsubject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $("#withinsubjectfromsubject").html(data);
        }
      });
    }
  });
  $(document).on('click', '#copyThisSubjectWithInSubject', function() {
    swal({
      title: 'Are you sure you want to move selected grade quarter mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        gs_gradesec=[];
        $("input[name='selectAllMoveCopyMarkSubjectly[ ]']:checked").each(function(i){
          gs_gradesec[i]=$(this).val();
        });
        var gs_branches=$('#withinsubjectbranch').val();
        var fromSubject=$('#withinsubjectfromsubject').val();
        var toSubject=$('#withinsubjecttosubject').val();
        var toQuarter=$('#withinsubjectQuarter').val();
        var grands_academicyear=$('#withinsubjectacademicyear').val();
        if (gs_gradesec.length!=0 && $('#withinsubjectQuarter').val()!='') {
          $.ajax({
            url: "<?php echo base_url(); ?>copymovemark/copyAssesmentSubjectMark/",
            method: "POST",
            data: ({
              gs_branches: gs_branches,
              gs_gradesec:gs_gradesec,
              fromSubject:fromSubject,
              toSubject:toSubject,
              toQuarter:toQuarter,
              grands_academicyear:grands_academicyear
            }),
            beforeSend: function() {
              $('#copyWithInMarkInfo').html( 'Copying...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $("#copyWithInMarkInfo").html(data);
            }
          })
        }else {
          swal({
            title: 'All fields are required!',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
        }
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branchCopyEvaluation").bind("change", function() {
      var branch=$("#branchCopyEvaluation").val();
      var academicyear=$("#academicyearCopyEvaluation").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>copymovemark/copyMarkStudentList/",
        data: ({
          academicyear:academicyear,
          branch:branch
        }),
        beforeSend: function() {
          $('#gradesecCopyEvaluation').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradesecCopyEvaluation").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '.selectAllStudentMoveCopyMark', function() {
    grade=[];
    $("input[name='selectAllStudentMoveCopyMark[ ]']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    var academicyear=$("#academicyearCopyEvaluation").val();
    if(grade.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>copymovemark/fetchThisGradeSubject/",
         data: ({
          grade: grade,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#subjectCopyEvaluation').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $("#subjectCopyEvaluation").html(data);
        }
      });
    }
  });
</script>
<!-- Grade change script ends -->
<!-- Fecth mark script starts -->
<script type="text/javascript">
  $(document).on('click', '#copyThisSubjectAssesment', function() {
    swal({
      title: 'Are you sure you want to move selected grade mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        gs_gradesec=[];
        $("input[name='selectAllStudentMoveCopyMark[ ]']:checked").each(function(i){
          gs_gradesec[i]=$(this).val();
        });
        var gs_branches=$('#branchCopyEvaluation').val();
        var gs_subject=$('#subjectCopyEvaluation').val();
        var gs_quarter=$('#grands_quarter').val();
        var grands_academicyear=$('#academicyearCopyEvaluation').val();
        if (gs_gradesec.length!=0 && $('#subjectCopyEvaluation').val()!='') {
          $.ajax({
            url: "<?php echo base_url(); ?>copymovemark/copyAssesmentMark/",
            method: "POST",
            data: ({
              gs_branches: gs_branches,
              gs_gradesec:gs_gradesec,
              gs_subject:gs_subject,
              gs_quarter:gs_quarter,
              grands_academicyear:grands_academicyear
            }),
            beforeSend: function() {
              $('#copyMarkInfo').html( 'Copying...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(data) {
              $("#copyMarkInfo").html(data);
            }
          })
        }else {
          swal({
            title: 'All fields are required!',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
        }
      }
    });
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