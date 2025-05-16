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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
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
            <div class="card">
              <div class="card-body table-responsive StudentViewTextInfo">
                <table class="display dataTable" id='empTableGS' style="width:100%;">
                  <thead>
                    <tr>
                      <th>Exam Name</th>                           
                      <th>Subject & Grade</th> 
                      <th>Created By</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                </table> 
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="addExamResultPage" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="add_exam_page" id="add_exam_page">Add exam result to regular mark sheet</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="exam_addingPage"></div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/gs_custom.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() { 
    $(document).on('change', '#new_season_grade', function() {
      var gradesec=$("#hiddenadded_grade_name").val();
      var quarter=$("#new_season_grade").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>viewexam/get_evaluation_season_change/",
        data: ({
          gradesec:gradesec,
          quarter:quarter
        }),
         beforeSend: function() {
          $('#new_evaluation_grade').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#new_evaluation_grade").html(data);
        }
      });
    });
  });
  $(document).on('click', '.addToRegularMark', function(e) {
    e.preventDefault();
    var subject=$(this).attr("value");
    var academicyear=$(this).attr("id");
    var examName=$(this).attr("title");
    var grade=$(this).attr("name");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>viewexam/add_this_exam_result_to_regular/",
      data: ({
        subject: subject,
        academicyear:academicyear,
        examName:examName,
        grade:grade
      }),
      cache: false,
      beforeSend: function() {
        $('.exam_addingPage').html( 'Adding exam result...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.exam_addingPage').html(html);     
      }
    }); 
  });
  $(document).on('submit', '#save_exam_changes_add', function(e) {
    e.preventDefault();
    swal({
      title: 'Are you sure you want to add this exam result to regular mark sheet?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        var examName=$("#hiddenaddedexam_name").val();
        var examGrade=$("#hiddenadded_grade_name").val();
        var examSubject=$("#hiddenaddedexam_subject_name").val();
        var academicyear=$("#hiddenaddedacademicYear").val();
        var season=$("#new_season_grade").val();
        var evaluation=$("#new_evaluation_grade").val();
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>viewexam/save_thisadded_exam_name/",
          data: ({
            examName: examName,
            academicyear:academicyear,
            examSubject:examSubject,
            examGrade:examGrade,
            season:season,
            evaluation:evaluation
          }),
          cache: false,
          beforeSend: function() {
            $('.exam_addingPage').html( 'Adding result...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(html){
            if(html=='1'){
              $('#addExamResultPage'). modal('hide');
              iziToast.success({
                title: 'Exam result added successfully.',
                message: '',
                position: 'topRight'
              });
            }else if(html=='0'){
              iziToast.error({
                title: 'Oooops Please try again later.',
                message: '',
                position: 'topRight'
              });
            }else{
              $('.exam_addingPage').html(html);
            }       
          }
        }); 
      }
    });
  });
  $('#empTableGS').DataTable({
    'processing': true,
    'serverSide': true,
    "dataType": "json",
    'serverMethod': 'post',
    'ajax': {
      'url':'<?=base_url()?>viewexam/fetch_exams/'
    },
    'columns': [
      { data: 'examname' },
      { data: 'subject' },
      { data: 'datecreated' },
      { data: 'Action' },
    ]
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
</body>

</html>