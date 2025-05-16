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
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card">
            <div class="card-head7er">
              <ul class="nav nav-tabs" id="myTab2" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#markResult" role="tab" aria-selected="true"> Mark Result</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="home-tab2" data-toggle="tab" href="#markSheet" role="tab" aria-selected="false">Mark Sheet</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="home-tab3" data-toggle="tab" href="#commentResult" role="tab" aria-selected="false">Comment Result</a>
                </li>
              </ul>
              <div class="tab-content tab-bordered" id="myTab3Content">
                <div class="tab-pane fade show active" id="markResult" role="tabpanel" aria-labelledby="home-tab1">
                  <div class="row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6">
                      <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                      <span class="text-black">
                        <i data-feather="printer"></i>
                      </span>
                      </button>
                    </div>
                  </div>
                  <form method="GET" id="comment_form">
                    <div class="row">
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="academicyear"  id="grands_academicyear">
                            <option>--Year--</option>
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
                            <select class="form-control" required="required" name="branch"
                            id="grands_branchit">
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
                          <select class="form-control grands_gradesec" required="required" name="gradesec" id="grands_gradesec">
                            <option>--- Grade ---</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-6 col-6 table-responsive" style="height: 20vh;">
                        <div class="grands_subject"> </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="quarter" id="grands_quarter">
                            <option>--- Select Season ---</option>
                           <!--  <?php foreach($fetch_term as $fetch_terms){ ?>
                              <option value="<?php echo $fetch_terms->term;?>">
                              <?php echo $fetch_terms->term;?>
                              </option>
                            <?php }?> -->
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-6">
                        <button class="btn btn-primary btn-lg btn-block" 
                          type="submit" name="viewmark">View Result
                        </button>
                      </div>
                    </div>
                  </form> 
                  <div class="listmark" id="mark_view"></div>
                </div>
                <div class="tab-pane fade show" id="markSheet" role="tabpanel" aria-labelledby="home-tab2">
                  <div class="row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6">
                      <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedySheet()">
                      <span class="text-black">
                        <i data-feather="printer"></i>
                      </span>
                      </button>
                    </div>
                  </div>
                  <form method="GET" id="comment_formSheet">
                    <div class="row">
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="academicyearSheet"  id="grands_academicyearSheet">
                          <?php foreach($academicyearFilter as $academicyears){ ?>
                            <option value="<?php echo $academicyears->year_name;?>">
                              <?php echo $academicyears->year_name;?>
                            </option>
                          <?php }?>
                          </select>
                        </div>
                      </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control" required="required" name="branchSheet"
                            id="grands_branchitSheet">
                              <option>--- Branch ---</option>
                                <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                  <?php echo $branchs->name;?>
                                </option>
                                <?php }?>
                            </select>
                          </div>
                        </div>
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <select class="form-control grands_gradesecSheet" required="required" name="gradesecSheet" id="grands_gradesecSheet">
                            <option>--- Grade ---</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <select class="form-control grands_subjectSheet" name="subjectSheet">
                            <option>--- Select Subject ---</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="quarterSheet" 
                          id="grands_quarterSheet">
                            <option>--- Select Season ---</option>
                            <?php foreach($fetch_term as $fetch_terms){ ?>
                              <option value="<?php echo $fetch_terms->term;?>">
                              <?php echo $fetch_terms->term;?>
                              </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-6">
                        <input type="checkbox" name="includeComment" id="includeComment" value="1">Include Comment
                        <button class="btn btn-primary btn-lg btn-block" 
                          type="submit" name="viewmarkShhet">View Sheet
                        </button>
                      </div>
                    </div>
                  </form> 
                  <div class="listmarkSheet" id="mark_viewSheet"></div>
                </div>
                <div class="tab-pane fade show" id="commentResult" role="tabpanel" aria-labelledby="home-tab3">
                  <div class="row">
                    <div class="col-md-12 col-12">
                      <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyComment()">
                      <span class="text-black">
                        <i data-feather="printer"></i>
                      </span>
                      </button>
                    </div>
                  </div>
                  <form method="GET" id="comment_formComment">
                    <div class="row">
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="academicyearComment"  id="grands_academicyearComment">
                          <?php foreach($academicyearFilter as $academicyears){ ?>
                            <option value="<?php echo $academicyears->year_name;?>">
                              <?php echo $academicyears->year_name;?>
                            </option>
                          <?php }?>
                          </select>
                        </div>
                      </div>
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <select class="form-control" required="required" name="branchComment"
                            id="grands_branchitComment">
                              <option>--- Branch ---</option>
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
                          <select class="form-control grands_gradesecComment" required="required" name="gradesecComment" id="grands_gradesecComment">
                            <option>--- Grade ---</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <select class="form-control grands_subjectComment" required="required" name="subjectComment" id="grands_subjectComment">
                            <option>--- Subject ---</option>
                        </select>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="quarterComment" id="grands_quarterComment">
                            <option>--- Season ---</option>
                            <?php foreach($fetch_term as $fetch_terms){ ?>
                              <option value="<?php echo $fetch_terms->term;?>">
                              <?php echo $fetch_terms->term;?>
                              </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <button class="btn btn-primary btn-lg btn-block" 
                          type="submit" name="viewmark">View Result
                        </button>
                      </div>
                    </div>
                  </form> 
                  <div class="listmarkComment" id="mark_viewComment"></div>
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
    $("#grands_gradesecComment").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#grands_gradesecComment").val(),
         beforeSend: function() {
          $('#grands_quarterComment').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#grands_quarterComment").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#grands_academicyear").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>markresult/filterGradesecfromBranch/",
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
    $("#grands_academicyear").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>markresult/filter_quarter_fromyear/",
        data: "academicyear=" + $("#grands_academicyear").val(),
        beforeSend: function() {
          $('#grands_quarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $("#grands_quarter").html(data);
        }
      });
    });
  });
  $(document).on('click', '#submitTeacherComment', function() {
    var academicyear=$("#academicyearTcomment").val();
    var subject=$("#subjectTcomment").val();
    var quarter=$("#quarterTcomment").val();
    var markGradeSec=$("#markGradeSecTcomment").val();
    var markGradeSecBranch=$("#markGradeSecBranchTcomment").val();
    stuid=[];commentvalue=[];
    $("input[name='markGradeStuidTcomment']").each(function(i){
      stuid[i]=$(this).val();
    });
    $("textarea:input[name=teacher_comment_gs]").each(function(i){
      commentvalue[i]=$.trim($(this).val());
    });
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>markresult/save_teacher_comment/",
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
        $('.infoTeacherComment').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">' );
      },
      success: function(html){
        $('.infoTeacherComment').html(html);
      }
    });
  });
</script>
<script type="text/javascript">
  function selectAllSubject(){
      var itemsall=document.getElementById('selectAllSubjectGS');
      if(itemsall.checked==true){
      var items=document.getElementsByName('grade_mark_resultGS');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('grade_mark_resultGS');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
  }
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("mark_view");
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
  function codespeedyComment(){
    var print_div = document.getElementById("mark_viewComment");
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
  function codespeedySheet(){
    var print_div = document.getElementById("mark_viewSheet");
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
<!-- Grade change script starts-->
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_branchit").bind("change", function() {
      var branchit=$("#grands_branchit").val();
      var academicyear=$("#grands_academicyear").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>markresult/fetch_gradesec_frombranch_markresult/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
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
  $(document).ready(function() {  
    $("#grands_branchitSheet").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#grands_branchitSheet").val(),
        beforeSend: function() {
          $('.grands_gradesecSheet').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".grands_gradesecSheet").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#grands_branchitComment").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#grands_branchitComment").val(),
        beforeSend: function() {
          $('.grands_gradesecComment').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".grands_gradesecComment").html(data);
        }
      });
    });
  });
</script>
<!-- Grade change script ends -->
<!-- Subject change script starts -->
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_gradesec").bind("change", function() {
      var gradesec=$("#grands_gradesec").val();
      var academicyear=$("#grands_academicyear").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Markresult/filterSubjectFromSubject/",
        data: ({
          gradesec: gradesec,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('.grands_subject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".grands_subject").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#grands_gradesecSheet").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_subject_from_subject/",
        data: "gradesec=" + $("#grands_gradesecSheet").val(),
        beforeSend: function() {
          $('.grands_subjectSheet').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".grands_subjectSheet").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#grands_gradesecComment").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Markresult/filterSubjectFromSubject_Comment/",
        data: "gradesec=" + $("#grands_gradesecComment").val(),
        beforeSend: function() {
          $('.grands_subjectComment').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".grands_subjectComment").html(data);
        }
      });
    });
  });
</script>
<!-- Subject change script ends -->
<!-- Fecth mark script starts -->
<script type="text/javascript">
  $('#comment_formComment').on('submit', function(event) {
    event.preventDefault();
    var gs_subject=$('#grands_subjectComment').val();
    var gs_branches=$('#grands_branchitComment').val();
    var gs_gradesec=$('.grands_gradesecComment').val();
    var gs_quarter=$('#grands_quarterComment').val();
    if ($('#grands_subjectComment').val()!='') {
      $.ajax({
        url: "<?php echo base_url(); ?>Markresult/fecth_mark_result_comment/",
        method: "GET",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject,
          gs_quarter:gs_quarter
        }),
        beforeSend: function() {
          $('.listmarkComment').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">' );
        },
        success: function(data) {
          $(".listmarkComment").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    gs_subject=[];
    $("input[name='grade_mark_resultGS']:checked").each(function(i){
      gs_subject[i]=$(this).val();
    });
    var gs_branches=$('#grands_branchit').val();
    var gs_gradesec=$('.grands_gradesec').val();
    var gs_quarter=$('#grands_quarter').val();
    var academicyear=$('#grands_academicyear').val();
    if (gs_subject.length!=0) {
      $.ajax({
        url: "<?php echo base_url(); ?>Markresult/fecthMarkresult/",
        method: "GET",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject,
          gs_quarter:gs_quarter,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('.listmark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">' );
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
  $('#comment_formSheet').on('submit', function(event) {
    event.preventDefault();
    var gs_branches=$('#grands_branchitSheet').val();
    var gs_gradesec=$('.grands_gradesecSheet').val();
    var gs_subject=$('.grands_subjectSheet').val();
    var gs_quarter=$('#grands_quarterSheet').val();
    if($('#includeComment').is(':checked')){
      var includeComment='1';
    }else{
      var includeComment='0';
    }
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Markresult/fecthMarksheet/",
        method: "GET",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject,
          gs_quarter:gs_quarter,
          includeComment:includeComment
        }),
        beforeSend: function() {
          $('.listmarkSheet').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listmarkSheet").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
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
</html>