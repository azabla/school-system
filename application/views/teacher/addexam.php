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
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
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
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
           <?php 
          if($summerClassMark->num_rows()>0){ ?>
             <div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
              <button class="close"  data-dismiss="alert">
                <span>&times;</span>
              </button>
              <i class="fas fa-check-circle"> </i> Summer class has been started. Please contact your system Admin.
            </div>
            </div> 
          <?php } else { if($markstatus->num_rows()>0 || $checkAutoLock){?>
            <div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
              <button class="close"  data-dismiss="alert">
                <span>&times;</span>
              </button>
              <i class="fas fa-check-circle"> </i> Access denied.
            </div>
            </div> 
          <?php } else{ ?>
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <div class="row">
                      <?php 
                      $query = $this->db->query("select max(year_name) as year from academicyear");
                      $row = $query->row();
                      $max_year=$row->year;

                      $this->db->where('academicyear',$max_year);
                      $this->db->where('enable_status','1');
                      $query=$this->db->get('enable_teachers_change_evaluation'); 
                      if($query->num_rows()>0){ ?>
                      <!-- <div class="col-lg-6 col-md-6 col-12">
                        <h4>Add New Result</h4>
                      </div> -->
                      <div class="col-lg-12 col-md-12 col-12">
                        <a href="#" class="pull-right" id="class_change_evaluation_my_subject">Change my subject evaluation <i class="fas fa-chevron-right"></i></a>
                      </div>
                    <?php } else{ ?>
                      <div class="col-lg-12 col-md-12 col-12">
                        <h4 class="header-title">Add New Result</h4>
                      </div>
                    <?php } ?>
                    </div>
                    <div class="fillResultList">
                    </div>
                    <div class="markform"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } }?>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="change_my_evaluation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Change evaluation setting </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="dropdown-divider"></div>
        <div class="class_change_evaluation_my_subject"></div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function() {
    $(document).on('click', '#submit_eva_setting', function() {
      var grade=$(".my_changed_grade").val();
      var subject=$(".my_changed_subject").val();
      var season=$(".my_changed_season").val();
      var year=$(".my_changed_year").val();
      var customasses=$(".my_changed_assesment").val();
      var percentValue=$(".changed_evaluation_percent").val();
      var oldPercentValue=$(".my_changed_percentage").val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Addstudentresult/update_this_subject_percentage/",
        data: ({
          grade:grade,
          subject:subject,
          season: season,
          year:year,
          customasses:customasses,
          percentValue:percentValue,
          oldPercentValue:oldPercentValue
        }),
        cache: false,
        beforeSend: function() {
          $('#submit_eva_setting').html('Updating....');
        },
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Changes updated successfully.',
              message: '',
              position: 'topRight'
            });
            $('#change_my_evaluation').modal('hide'); 
            $.ajax({
              method: "POST",
              url: "<?php echo base_url(); ?>Addstudentresult/class_change_evaluation_my_subject/",
              cache: false,
              beforeSend: function() {
                $('.fillResultList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
              },
              success: function(html){
                $('.fillResultList').html(html);
              }
            });
          }else if(html=='2'){
            iziToast.error({
              title: 'Oooops, Unable to update changes.',
              message: '',
              position: 'topRight'
            });
          }else if(html=='3'){
            iziToast.success({
              title: 'Changes saved successfully.',
              message: '',
              position: 'topRight'
            });
            $('#change_my_evaluation').modal('hide');
            $.ajax({
              method: "POST",
              url: "<?php echo base_url(); ?>Addstudentresult/class_change_evaluation_my_subject/",
              cache: false,
              beforeSend: function() {
                $('.fillResultList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
              },
              success: function(html){
                $('.fillResultList').html(html);
              }
            });
          }else if(html=='6'){
            iziToast.error({
              title: 'No changes found.',
              message: '',
              position: 'topRight'
            });
          }else{
            iziToast.error({
              title: 'Ooops Please try again later.',
              message: '',
              position: 'topRight'
            });
          }
          $('#submit_eva_setting').html('Save Changes');
        }
      });
    });
    $(document).on('click', '.edit_this_subject_percentage', function() {
      var grade=$(this).attr('data-grade-name');
      var subject=$(this).attr('data-subject-name');
      var season=$(this).attr('data-season-name');
      var year=$(this).attr('value');
      var customasses=$(this).attr('id');
      var percentValue=$(this).attr('data-percent-value');
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Addstudentresult/edit_this_subject_percentage/",
        data: ({
          grade:grade,
          subject:subject,
          season: season,
          year:year,
          customasses:customasses,
          percentValue:percentValue
        }),
        cache: false,
        beforeSend: function() {
          $('.class_change_evaluation_my_subject').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.class_change_evaluation_my_subject').html(html);
        }
      });
    });
    $(document).on('click', '#class_change_evaluation_my_subject', function() {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Addstudentresult/class_change_evaluation_my_subject/",
        cache: false,
        beforeSend: function() {
          $('.fillResultList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.fillResultList').html(html);
        }
      });
    });
    load_subject_to_feed();
    function load_subject_to_feed()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Addstudentresult/load_subject_to_feed/",
        method:"POST",
        beforeSend: function() {
          $('.fillResultList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.fillResultList').html(data);
        }
      })
    }
    $(document).on('click', '.backToMainPage', function()
    {
      load_subject_to_feed();
    });
  }); 
  $(document).on('click', '.startFeedingResult', function() {
    var academicyear=$(this).attr('id');
    var grade=$(this).attr('value');
    var branch=$(this).attr('name');
    var subject=$(this).attr('title');
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Addstudentresult/fetch_form/",
      data: ({
        academicyear:academicyear,
        grade:grade,
        branch: branch,
        subject:subject
      }),
      cache: false,
      beforeSend: function() {
        $('.fillResultList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.fillResultList').html(html);
      }
    });
  });
  $(document).on('change', '#selectSeasonToFeed', function() {
    var year=$("#feededYear").val();
    var gradesec=$("#feededGrade").val();
    var quarter=$("#selectSeasonToFeed").val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Addstudentresult/Filter_evaluation_quarterchange/",
      data: ({
        gradesec:gradesec,
        quarter:quarter,
        year:year
      }),
       beforeSend: function() {
        $('#selectEvaluationToFeed').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(data) {
        $("#selectEvaluationToFeed").html(data);
      }
    });
  });
  $(document).on('change', '#selectEvaluationToFeed', function() {
    var gradesec=$("#feededGrade").val();
    var evaluation=$("#selectEvaluationToFeed").val();
    var quarter=$("#selectSeasonToFeed").val();
    var subject=$("#feededSubject").val();
    var branch=$("#feededBranch").val();
    var year=$("#feededYear").val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Addstudentresult/FilterAssesmentQuarterChange/",
      data: ({
        gradesec:gradesec,
        evaluation:evaluation,
        quarter:quarter,
        subject:subject,
        branch:branch,
        year:year
      }),
       beforeSend: function() {
        $('#selectAssesmentToFeed').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(data) {
        $("#selectAssesmentToFeed").html(data);
      }
    });
  });
  $(document).on('change', '#selectAssesmentToFeed', function() {
    var gradesec=$("#feededGrade").val();
    var evaluation=$("#selectAssesmentToFeed").val();
    var quarter=$("#selectSeasonToFeed").val();
    var subject=$("#feededSubject").val();
    var branch=$("#feededBranch").val();
    var year=$("#feededYear").val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Addstudentresult/FilterPercentageOnAssesmentChange/",
      data: ({
        gradesec:gradesec,
        evaluation:evaluation,
        quarter:quarter,
        subject:subject,
        branch:branch,
        year:year
      }),
      beforeSend: function() {
        $('#selectPercentageToFeed').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(data) {
        if(data>0){
          document.getElementById("selectPercentageToFeed").value=data;
          $( "#selectPercentageToFeed" ).prop( "disabled", true );
        }else{
          document.getElementById("selectPercentageToFeed").value='';
          $( "#selectPercentageToFeed" ).prop( "disabled", false );
        }
      }
    });
  });
  $(document).on('submit', '#fetch_last_form_ToFeed', function(event) {
    event.preventDefault();
    var academicyear=$("#feededYear").val();
    var gradesec=$("#feededGrade").val();
    var subject=$("#feededSubject").val();
    var evaluation=$("#selectEvaluationToFeed").val();
    var quarter=$("#selectSeasonToFeed").val();
    var assesname=$("#selectAssesmentToFeed").val();
    var percentage=$("#selectPercentageToFeed").val();
    var branch=$("#feededBranch").val();
    if($("#selectSeasonToFeed").val()!=='--Select Season--'){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Addstudentresult/studentResultForm/",
        data: ({
          academicyear: academicyear,
          gradesec:gradesec,
          subject:subject,
          evaluation:evaluation,
          quarter:quarter,
          assesname:assesname,
          percentage:percentage,
          branch:branch
        }),
        cache: false,
        beforeSend: function() {
          $('.fillResultList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.fillResultList').html(html);
        }
      });
    }else{
      swal('Please select correct season. ', {
        icon: 'error',
      });
    }
  });
  /*    */
</script>
<!-- save mark result starts -->
<script>
  function chkMarkValue(){
    var stuid=$("#stuidResult").val();
    var chkPercent=parseInt($("#percentageResult").val());
    var markResult=$("#resultvalue").val();
    $("input[name='markvalue_result']").each(function(i){
      resultvalue=parseInt($(this).val());
      if(resultvalue > chkPercent){
        swal({
          title: 'Oooops, Incorrect Mark result. Please try Again.',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
      }
    });
  }
  $(document).on('submit', '#submit_student_result', function() {
    var stuid=$("#stuidResult").val();
    var academicyear=$("#academicyearResult").val();
    var subject=$("#subjectResult").val();
    var evaluation=$("#evaluationResult").val();
    var quarter=$("#quarterResult").val();
    var assesname=$("#assesnameResult").val();
    var percentage=$("#percentageResult").val();
    var markGradeSec=$("#markGradeSecResult").val();
    var branch=$("#markGradeSecBranchResult").val();
    stuid=[];resultvalue=[];
      $("input[name='stuid_result']").each(function(i){
        stuid[i]=$(this).val();
      });
      $("input[name='markvalue_result']").each(function(i){
        resultvalue[i]=$(this).val();
      });
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Addstudentresult/addNewresult/",
      data: ({
        stuid:stuid,
        resultvalue:resultvalue,
        academicyear: academicyear,
        subject:subject,
        evaluation:evaluation,
        quarter:quarter,
        assesname:assesname,
        percentage:percentage,
        markGradeSec:markGradeSec,
        branch:branch
      }),
      cache: false,
      beforeSend: function() {
        $('.fillResultList').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        $("#SaveResult").attr("disabled","disabled");
      },
      success: function(html){
        $('.fillResultList').html(html);
        $('#fetch_last_form_ToFeed')[0].reset();  
        $('#SaveResult').removeAttr('disabled','disabled'); 
      }
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
</html>