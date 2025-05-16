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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
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
            <div class="card-body StudentViewTextInfo">
              <ul class="nav nav-tabs" id="myTab2" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#showBasicSkill" role="tab" aria-selected="true">Feed BS(Option1) </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="home-tab2" data-toggle="tab" href="#feedBasicSkill" role="tab" aria-selected="true">Feed BS(Option2) </a>
                </li>
                <?php if($enable_sub_category){ ?>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab6" data-toggle="tab" href="#feedBasicSkill2" role="tab" aria-selected="true">Feed BS(Option3) </a>
                  </li>
                <?php } ?>
                <li class="nav-item">
                  <a class="nav-link" id="home-tab3" data-toggle="tab" href="#notFilledStudent" role="tab" aria-selected="false">Incomplete Student</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="home-tab5" data-toggle="tab" href="#overallComment" role="tab" aria-selected="false">Overall Comment</a>
                </li>
              </ul>
              <div class="tab-content tab-bordered" id="myTab3Content">
                <div class="tab-pane fade show active" id="showBasicSkill" role="tabpanel" aria-labelledby="home-tab1">
                  <div class="row">
                    <div class="col-lg-12 col-12">
                      <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                      <span class="text-black">
                        <i data-feather="printer"></i>
                      </span>
                      </button>
                    </div>
                  </div>
                  <form method="POST" id="fetchBs">
                    <div class="row">
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <select class="form-control bsgradesec" required="required"
                           name="bsgradesec" id="bsgradesec">
                            <option>--- Grade ---</option>
                             <?php if($num_rows==1){
                            foreach($gradesec as $gradesecs){ ?>
                              <option value="<?php echo $gradesecs->gradesec;?>">
                              <?php echo $gradesecs->gradesec;?>
                              </option>
                            <?php } } else{
                              foreach($gradesecs_gs as $gradesecss){ ?>
                              <option value="<?php echo $gradesecss->roomgrade;?>">
                              <?php echo $gradesecss->roomgrade;?>
                              </option> 
                            <?php } }?>

                            <!--  -->
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" 
                          name="bsquarter" id="bsquarter">
                            <option>--- Select Quarter ---</option>
                            <?php foreach($fetch_term as $fetch_terms){ ?>
                              <option value="<?php echo $fetch_terms->term;?>">
                              <?php echo $fetch_terms->term;?>
                              </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-12">
                        <button class="btn btn-primary btn-block btn-lg" 
                          type="submit" name="viewmark">View
                        </button>
                      </div>
                    </div>
                  </form> 
                  <div class="listbs" id="listbs"></div>
                </div>
                <div class="tab-pane fade show" id="feedBasicSkill" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyFeed()">
                        <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="fetchBsFeed">
                      <div class="row">
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecFeed" required="required"
                             name="bsgradesecFeed" id="bsgradesecFeed">
                             <option>Select Grade</option>
                              <?php if($num_rows==1){
                            foreach($gradesec as $gradesecs){ ?>
                              <option value="<?php echo $gradesecs->gradesec;?>">
                              <?php echo $gradesecs->gradesec;?>
                              </option>
                            <?php } } else{
                              foreach($gradesecs_gs as $gradesecss){ ?>
                              <option value="<?php echo $gradesecss->roomgrade;?>">
                              <?php echo $gradesecss->roomgrade;?>
                              </option> 
                            <?php } }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" 
                            name="bsquarterFeed" id="bsquarterFeed">
                              <option>Select Quarter </option>
                             <?php foreach($fetch_term as $fetch_terms){ ?>
                              <option value="<?php echo $fetch_terms->term;?>">
                              <?php echo $fetch_terms->term;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-12">
                          <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="viewmark">Start
                          </button>
                        </div>
                      </div>
                    </form> 
                    <div class="listbsFeed" id="listbsFeed"></div>
                  </div>
                <?php if($enable_sub_category){ ?>
                  <div class="tab-pane fade show" id="feedBasicSkill2" role="tabpanel" aria-labelledby="home-tab6">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyFeed2()">
                        <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="fetchBsFeed2">
                      <div class="row">
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecFeed2" required="required"
                             name="bsgradesecFeed2" id="bsgradesecFeed2">
                              <option>Select Grade</option>
                              <?php if($num_rows==1){
                              foreach($gradesec as $gradesecs){ ?>
                                <option value="<?php echo $gradesecs->gradesec;?>">
                                <?php echo $gradesecs->gradesec;?>
                                </option>
                              <?php } } else{
                                foreach($gradesecs_gs as $gradesecss){ ?>
                                <option value="<?php echo $gradesecss->roomgrade;?>">
                                <?php echo $gradesecss->roomgrade;?>
                                </option> 
                              <?php } }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" 
                            name="bsquarterFeed2" id="bsquarterFeed2">
                              <option> </option>
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
                            type="submit" name="viewmark2">Start
                          </button>
                        </div>
                      </div>
                    </form> 
                    <div class="listbsFeed2" id="listbsFeed2"></div>
                  </div>
                  <?php } ?>
                  <div class="tab-pane fade show" id="notFilledStudent" role="tabpanel" aria-labelledby="home-tab2">
                    <span class="text-time text-danger">NB: If you do not find anything after selecting all the required fields on this tab, it means that all the basic skill fields are filled.</span>
                    <form method="POST" id="fetchNonFilledBs">
                      <div class="row">
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecNonFilled" required="required"
                             name="bsgradesecNonFilled" id="bsgradesecNonFilled">
                              <option>--- Grade ---</option>
                              <?php if($num_rows==1){
                            foreach($gradesec as $gradesecs){ ?>
                              <option value="<?php echo $gradesecs->gradesec;?>">
                              <?php echo $gradesecs->gradesec;?>
                              </option>
                            <?php } } else{
                              foreach($gradesecs_gs as $gradesecss){ ?>
                              <option value="<?php echo $gradesecss->roomgrade;?>">
                              <?php echo $gradesecss->roomgrade;?>
                              </option> 
                            <?php } }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" 
                            name="bsquarterNonFilled" id="bsquarterNonFilled">
                              <option> </option>
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
                            type="submit" name="showNonFilledStudent">Show All
                          </button>
                        </div>
                      </div>
                    </form>
                    <div class="listNonFilledBs" id="listNonFilledBs"></div>
                  </div>
                <div class="tab-pane fade show" id="overallComment" role="tabpanel" aria-labelledby="home-tab5">
                    <form method="POST" id="feedOverallComment">
                      <div class="row">
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecOverallComment" required="required"
                             name="bsgradesecOverallComment" id="bsgradesecOverallComment">
                               <option>Select Grade</option>
                                <?php if($num_rows==1){
                            foreach($gradesec as $gradesecs){ ?>
                              <option value="<?php echo $gradesecs->gradesec;?>">
                              <?php echo $gradesecs->gradesec;?>
                              </option>
                            <?php } } else{
                              foreach($gradesecs_gs as $gradesecss){ ?>
                              <option value="<?php echo $gradesecss->roomgrade;?>">
                              <?php echo $gradesecss->roomgrade;?>
                              </option> 
                            <?php } }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" 
                            name="bsquarterOverallComment" id="bsquarterOverallComment">
                              <option> </option>
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
                            type="submit" name="showOverallComment">Show All
                          </button>
                        </div>
                      </div>
                    </form>
                    <div class="listOverallComment" id="listOverallComment"></div>
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
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script> 
  $(document).ready(function() {  
    $("#bsgradesecNonFilled").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#bsgradesecNonFilled").val(),
         beforeSend: function() {
          $('#bsquarterNonFilled').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#bsquarterNonFilled").html(data);
        }
      });
    });
  });
  $('#fetchNonFilledBs').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('.bsgradesecNonFilled').val();
    var quarter=$('#bsquarterNonFilled').val();
    if ($('.bsgradesecNonFilled').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Viewbs/fecthNonFilledStudentBs/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          quarter:quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listNonFilledBs').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listNonFilledBs").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $(document).ready(function() {  
    $("#bsgradesecFeed2").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#bsgradesecFeed2").val(),
         beforeSend: function() {
          $('#bsquarterFeed2').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#bsquarterFeed2").html(data);
        }
      });
    });
  });
  $('#fetchBsFeed2').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('.bsgradesecFeed2').val();
    var quarter=$('#bsquarterFeed2').val();
    if ($('.bsgradesecFeed2').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Viewbs/fecthStudentBsFeed2/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          quarter:quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listbsFeed2').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listbsFeed2").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $(document).ready(function () { 
    $(document).on('keyup', 'textarea:input[name=teacher_ocomment_gs]', function() {
      var stuid=$(this).attr('title');
      var max_length = $("#totalWordsFORComments").val(); 
      var words = 0;
        if ((this.value.match(/\S+/g)) != null) {
          words = this.value.match(/\S+/g).length;
        }
        if (words > max_length) {
          var trimmed = $(this).val().split(/\s+/, max_length).join(" ");
          $(this).val(trimmed + " ");
        }
        else {
          $('#totalWordsLeft' + stuid).text(words + " Words left");
          $('#totalWordsLeft' + stuid).text(max_length-words + " Words left");
        }

      /*let str = $.trim($(this).val()).split(" ");
      var len = max_length - str.length; 
      if(len==0){
        str.pop(); 
        $('.count_words' + stuid).attr("disabled","disabled");
      }*/
      $('#totalWordsLeft' + stuid).text(len + " Words left"); 
    }); 
  }); 
</script> 
<script type="text/javascript">
  $(document).on('click', '#submitTeacheroComment', function() {
    var academicyear=$("#academicyearTocomment").val();
    var quarter=$("#quarterTocomment").val();
    var markGradeSec=$("#markGradeSecTocomment").val();
    var markGradeSecBranch=$("#markGradeSecBranchTocomment").val();
    stuid=[];commentvalue=[];
    $("input[name='markGradeStuidTocomment']").each(function(i){
      stuid[i]=$(this).val();
    });
    $("textarea:input[name=teacher_ocomment_gs]").each(function(i){
      commentvalue[i]=$.trim($(this).val());
    });
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Viewbs/save_teacher_comment/",
      data: ({
        stuid:stuid,
        commentvalue:commentvalue,
        academicyear: academicyear,
        quarter:quarter,
        markGradeSec:markGradeSec,
        markGradeSecBranch:markGradeSecBranch
      }),
      cache: false,
      beforeSend: function() {
        $('.infoTeacheroComment').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.infoTeacheroComment').html(html);
      }
    });
  });
  $('#feedOverallComment').on('submit', function(event) {
    event.preventDefault();
    var branches=$('#bsbranchOverallComment').val();
    var gradesec=$('#bsgradesecOverallComment').val();
    var quarter=$('#bsquarterOverallComment').val();
    if ($('#bsgradesecOverallComment').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Viewbs/fecthOverallComment/",
        method: "POST",
        data: ({
          branches: branches,
          gradesec:gradesec,
          quarter:quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listOverallComment').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listOverallComment").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $(document).ready(function() {  
    $("#bsgradesecOverallComment").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#bsgradesecOverallComment").val(),
         beforeSend: function() {
          $('#bsquarterOverallComment').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#bsquarterOverallComment").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#bsgradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#bsgradesec").val(),
         beforeSend: function() {
          $('#bsquarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#bsquarter").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#bsgradesecFeed").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#bsgradesecFeed").val(),
         beforeSend: function() {
          $('#bsquarterFeed').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#bsquarterFeed").html(data);
        }
      });
    });
  });
  $(document).on('click', '.insertbsTypeGS_feed', function() {
    var value=$(this).attr('value');
    var stuid=$(this).attr('id');
    var bsname=$(this).attr('title');
    var quarter=$('#bsQuarter_feed').val();
    var bsGradesec=$('#bsGradesec_feed').val();
    var branch=$('#bsBranch_feed').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Viewbs/updateStudentBs/",
        data: ({
          value:value,
          stuid:stuid,
          bsname:bsname,
          quarter:quarter,
          bsGradesec:bsGradesec,
          branch:branch
        }),
        success: function(data) {
          iziToast.success({
            title: data,
            message: '',
            position: 'topRight'
          });
        }
    });
  });
  $('#fetchBsFeed').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('.bsgradesecFeed').val();
    var quarter=$('#bsquarterFeed').val();
    if ($('.bsgradesecFeed').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Viewbs/fecthStudentBsFeed/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          quarter:quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listbsFeed').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listbsFeed").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $(document).on('change', '.insertbsTypeo', function() {
    var value=$(this).find('option:selected').attr('value');
    var stuid=$(this).find('option:selected').attr('name');
    var bsname=$(this).find('option:selected').attr('class');
    var quarter=$('#bsQuarter').val();
    var bsGradesec=$('#bsGradesec').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Viewbs/updateStudentBs/",
        data: ({
          value:value,
          stuid:stuid,
          bsname:bsname,
          quarter:quarter,
          bsGradesec:bsGradesec
        }),
        success: function(data) {
          iziToast.success({
            title: data,
            message: '',
            position: 'topRight'
          });
        }
    });
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("listbs");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  function codespeedyFeed(){
    var print_div = document.getElementById("listbsFeed");
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
<!-- Fecth mark script starts -->
<script type="text/javascript">
  $('#fetchBs').on('submit', function(event) {
    event.preventDefault();
    var branches=$('#bsbranch').val();
    var gradesec=$('.bsgradesec').val();
    var quarter=$('#bsquarter').val();
    if ($('.bsgradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Viewbs/fecthStudentBs/",
        method: "POST",
        data: ({
          branches: branches,
          gradesec:gradesec,
          quarter:quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listbs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listbs").html(data);
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