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
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#markResult" role="tab" aria-selected="true">Mark Result</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#commentResult" role="tab" aria-selected="false">Comment Result</a>
                      </li>
                    </ul>
                  
                  <div class="tab-content tab-bordered" id="myTab3Content">
                    <div class="tab-pane fade show active" id="markResult" role="tabpanel" aria-labelledby="home-tab1">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-12 col-md-12">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                              <span class="text-black"> <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                        <form id="comment_form">
                          <div class="row">
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="gradesec" id="gradesec">
                                <option>--- Grade ---</option>
                                <?php  foreach($gradesec as $gradesecs){ ?>
                                  <option value="<?php echo $gradesecs->grade;?>">
                                   <?php echo $gradesecs->grade;?>
                                  </option>
                                <?php } ?>
                               </select>
                              </div>
                            </div>
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control subject" name="subject" id="subject" required> 
                                  <option>--- Select Subject ---</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="quarter"  id="quarter">
                                <option>--- Select Quarter ---</option>
                                <?php foreach($fetch_term as $fetch_terms){ ?>
                                  <option value="<?php echo $fetch_terms->term;?>">
                                  <?php echo $fetch_terms->term;?>
                                  </option>
                                <?php }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-3 col-6">
                            <button class="btn btn-info btn-block" type="submit" name="viewmark">View Result</button>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="card-body listmark" id="listmark"> </div>
                    </div>
                    <div class="tab-pane fade show" id="commentResult" role="tabpanel" aria-labelledby="home-tab3">
                      <div class="row">
                        <div class="col-12 col-md-12">
                          <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                            <span class="text-black"> <i data-feather="printer"></i>
                            </span>
                          </button>
                        </div>
                      </div>
                      <form id="comment_form_comment">
                        <div class="row">
                          <div class="col-lg-3 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="gradesec_comment" id="gradesec_comment">
                              <option>--- Grade ---</option>
                              <?php foreach($gradesec as $gradesecs){ ?>
                                <option value="<?php echo $gradesecs->grade;?>">
                                 <?php echo $gradesecs->grade;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                          </div>
                          <div class="col-lg-3 col-6">
                            <div class="form-group">
                              <select class="form-control subject_comment" name="subject_comment" id="subject_comment" required>
                                <option>--- Select Subject ---</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-3 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="quarter_comment"  id="quarter_comment">
                              <option>--- Select Quarter ---</option>
                              <?php foreach($fetch_term as $fetch_terms){ ?>
                                <option value="<?php echo $fetch_terms->term;?>">
                                <?php echo $fetch_terms->term;?>
                                </option>
                              <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-3 col-6">
                          <button class="btn btn-info btn-block" type="submit" name="viewmark_comment">View Result</button>
                          </div>
                        </div>
                      </form>

                      <div class="card-body listmark_comment" id="listmark_comment"> </div>
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
    $("#gradesec_comment").bind("change", function() {
      var gradesec=$("#gradesec_comment").val();
      var branch=$("#admin_branch_comment").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Studentresult/Filtersubjectfromstaff/",
        data: ({
          gradesec:gradesec,
          branch:branch
        }),
         beforeSend: function() {
          $('.subject_comment').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $(".subject_comment").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#gradesec_comment").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#gradesec_comment").val(),
         beforeSend: function() {
          $('#quarter_comment').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#quarter_comment").html(data);
        }
      });
    });
  });
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
<script type="text/javascript">
  $(document).ready(function() {  
    $("#gradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Studentresult/fetchSubjectforMarkView/",
        data: "gradesec=" + $("#gradesec").val(),
         beforeSend: function() {
          $('.subject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".subject").html(data);
        }
      });
    });
  });
</script> 
<!-- Fecth mark script starts -->
<script type="text/javascript">
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var gs_gradesec=$('#gradesec').val();
    var gs_subject=$('#subject').val();
    var gs_quarter=$('#quarter').val();
    if ($('#gradesec').val() != '--- Grade ---' && $('#quarter').val() != '--- Select Quarter ---') {
      $.ajax({
        url: "<?php echo base_url(); ?>Studentresult/Fecth_teacher_markresult/",
        method: "POST",
        data: ({
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject,
          gs_quarter:gs_quarter
        }),
        beforeSend: function() {
          $('.listmark').html( 'Loading Mark...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="30" height="30" id="loa">' );
        },
        success: function(data) {
          $(".listmark").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
   $('#comment_form_comment').on('submit', function(event) {
    event.preventDefault();
    var gs_gradesec=$('#gradesec_comment').val();
    var gs_subject=$('#subject_comment').val();
    var gs_quarter=$('#quarter_comment').val();
    if ($('#gradesec_comment').val() != '--- Grade ---' && $('#quarter_comment').val() != '--- Select Quarter ---') {
      $.ajax({
        url: "<?php echo base_url(); ?>Studentresult/fecth_mark_result_comment/",
        method: "GET",
        data: ({
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject,
          gs_quarter:gs_quarter
        }),
        beforeSend: function() {
          $('.listmark_comment').html( 'Loading Mark...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listmark_comment").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#submitTeacherCommentTeacher', function() {
    var academicyear=$("#academicyearTcomment_teacher").val();
    var subject=$("#subjectTcomment_teacher").val();
    var quarter=$("#quarterTcomment_teacher").val();
    var markGradeSec=$("#markGradeSecTcomment_teacher").val();
    var markGradeSecBranch=$("#markGradeSecBranchTcomment_teacher").val();
    stuid=[];commentvalue=[];
    $("input[name='markGradeStuidTcomment_teacher']").each(function(i){
      stuid[i]=$(this).val();
    });
    $("textarea:input[name=teacher_comment_gs_comment]").each(function(i){
      commentvalue[i]=$.trim($(this).val());
    });
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Studentresult/save_teacher_comment/",
      data: ({
        stuid:stuid,
        commentvalue:commentvalue,
        academicyear: academicyear,
        subject:subject,
        quarter:quarter,
        markGradeSec:markGradeSec,
        markGradeSecBranch:markGradeSecBranch
      }),
      cache: false,
      beforeSend: function() {
        $('.infoTeacherComment_comment').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">' );
      },
      success: function(html){
        $('.infoTeacherComment_comment').html(html);
      }
    });
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("listmark");
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