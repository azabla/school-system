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
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.css" rel="stylesheet">
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
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-12">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#addNewExam" role="tab" aria-selected="true">New Exam</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#viewExam" role="tab" aria-selected="false">Edit Exam</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="addNewExam" role="tabpanel" aria-labelledby="home-tab1">
                    <form method="POST" id="exam_setting_field">
                      <div class="row">
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <label for="Mobile">Select Grade</label>
                            <div class="form-line">
                              <select class="form-control selectric" required="required" name="gradesec" id="examGradesec">
                                <option></option>
                                <?php foreach($fetch_gradesec as $fetch_gradesecs) { ?>
                                <option value="<?php echo $fetch_gradesecs->grade;?>">
                                <?php echo $fetch_gradesecs->grade;?>
                                </option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <label for="Mobile">Select Subject</label>
                            <select class="form-control" required="required" name="subject" id="examSubject">
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <label for="Mobile">Exam Name</label>
                            <input type ="text" placeholder="Exam Name Here..." class="form-control" required="required" name="examname" id="examName">
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <label for="Mobile">No of questions?</label>
                            <div class="form-line">
                              <input type ="number" placeholder="No of question..." class="form-control" required="required" name="number" id="numberQuestion">
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <label for="Mobile">Allwed exam time(min)</label>
                            <div class="form-line">
                              <input type ="number" placeholder="Exam duration..." class="form-control" required="required" name="minute" id="minuteAllowed">
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6 form-group">
                          <label for="Mobile">Exam Started Time</label>
                          <input type="datetime-local" id="exam_started_time" class="form-control">                      
                        </div>
                        <div class="col-lg-10 col-12 form-group">                     
                          <div class="pretty p-switch">
                            <input type="checkbox" name="terminate_exam_status" class="terminate_exam_status" id="terminate_exam_status" value="0" >Terminate exam when time run out.
                            <div class="state p-success pull-right">
                                <label></label>
                            </div>
                          </div>
                          <div class="pretty p-switch">
                            <input type="checkbox" name="see_resut_automatically" class="see_resut_automatically" id="see_resut_automatically" value="0" >Show result automatically to student.
                            <div class="state p-success pull-right">
                                <label></label>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <button type="submit" id="start_exam" name="start" class="btn btn-success btn-block" data-toggle="modal">Start Typing
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane fade show" id="viewExam" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="card">
                      <div class="card-body table-responsive StudentViewTextInfo">
                        <table class="display dataTable" id='empTableGS' style="width:100%;">
                          <thead>
                            <tr>
                              <th>Exam Name</th>                           
                              <th>Subject & Grade</th>                         
                              <th>Live Time</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                        </table> 
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
  <div class="modal fade" id="add_new_exam" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="addSubject_new_exam">Add new exam </h4> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <h3 class="badge badge-danger pull-right" id="count_no_question"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-header">
          <div class="modal-bodyd" id="fetch_exam_form"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="copyExamPage" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="copy_exam_page" id="copy_exam_page">Copy Exam </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="exam_copyingPage"></div>
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
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.js"></script>
  <script type="text/javascript">
    var question_number=1;
  $(document).on('click', "#start_exam", function() {
    var examName=$("#examName").val();
    var examGrade=$("#examGradesec").val();
    var examSubject=$("#examSubject").val();
    var numberQuestion=$("#numberQuestion").val();
    var exam_started_time=$("#exam_started_time").val();
    var minuteAllowed=$("#minuteAllowed").val();
    if($('#terminate_exam_status').is(':checked')){
      var terminate_exam_status='1';
    }else{
      var terminate_exam_status='0';
    }
    if($('#see_resut_automatically').is(':checked')){
      var see_resut_automatically='1';
    }else{
      var see_resut_automatically='0';
    }
    if($("#examName").val()!='' && $("#examGradesec").val()!='' && $("#examSubject").val()!='' && $("#numberQuestion").val()!='' && $("#minuteAllowed").val()!='' && $("#exam_started_time").val()!=''){
      $("#addSubject_new_exam").html(examSubject + ' Exam for Grade ' + examGrade);
      $("#count_no_question").html(question_number + ' / ' + numberQuestion);
      $.ajax({
        url:"<?php echo base_url() ?>exam/check_examValidation/",
        method:"POST",
        data:({
          examName:examName,
          examGrade:examGrade,
          examSubject:examSubject,
          numberQuestion:numberQuestion,
          minuteAllowed:minuteAllowed,
          terminate_exam_status:terminate_exam_status,
          exam_started_time:exam_started_time,
          see_resut_automatically:see_resut_automatically
        }),
        cache: false,
        beforeSend:function(){
          $('#fetch_exam_form').html( 'Checking...' );
          $('#start_exam').attr( 'disabled','disabled');
        },
        success: function(data){
          $('#start_exam').removeAttr( 'disabled');
          if(data=='0'){
            $("#fetch_exam_form").html('<span class="text-danger">Ooops exam found. Please try with other exam name.</span>');
          }else{
            $("#fetch_exam_form").html(data);
            $('#name_Question').summernote({
              placeholder: 'Create you content here.',
              tabsize: 2,
              height: '15vh'
            }); 
          }  
        }
      });
      $('#add_new_exam').modal('show');
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('submit', '.save_submit_exam', function(e) {
    e.preventDefault();
    var radioValue = $("input[name='select_correct_answer']:checked").val();
    if ($("input:radio[name='select_correct_answer']:checked").length != 0) {
      var examName=$("#exam_name").val();
      var answer=$("input[name='select_correct_answer[]']:checked").val();
      var examGrade=$("#exam_grade_name").val();
      var examSubject=$("#exam_subject_name").val();
      var name_Question=$("#name_Question").val();
      var question_type=$("#question_type").val();
      var question_weight=$("#question_weight").val();
      var minuteAllowed=$("#minute_Allowed").val();
      var terminate_status=$("#terminate_status").val();
      var startedTime_status=$("#startedTime_status").val();
      var can_see_resut_automatically=$("#can_see_resut_automatically").val();
      var numberQuestion=$("#noof_Question").val();
      $.ajax({
        url:"<?php echo base_url() ?>exam/save_insert_exam/",
        method:"POST",
        data:$('.save_submit_exam').serialize(),  
        beforeSend:function(){
          $('#save_exam').attr( 'disabled','disabled');
          $('#save_exam').html( 'Saving...' );
        },
        success: function(data){
          $('#save_exam').removeAttr( 'disabled');
          $('#save_exam').html( 'Save Exam & Add next' );
          $(".save_submit_exam")[0].reset();
          $('#name_Question').summernote('reset');
          if(data=='1'){
            question_number= question_number +1;
            if(question_number > numberQuestion ){
              $('#add_new_exam'). modal('hide');
              $("#exam_setting_field")[0].reset();
              question_number=1;
              document.getElementById('count_no_question').innerHTML='';
            }else{
              document.getElementById('count_no_question').innerHTML=question_number + ' / ' + numberQuestion;
            }
            iziToast.success({
              title: 'Exam saved successfully.',
              message: '',
              position: 'topRight'
            });
            $('#empTableGS').DataTable().ajax.reload();
          }else{
            iziToast.error({
              title: 'Something wrong, please try again.',
              message: '',
              position: 'topRight'
            });
          }
        }
      });
    }else{
      iziToast.error({
        title: 'Please select the correct answer.',
        message: '',
        position: 'topRight'
      });
    }
  });
  $(document).ready(function(){
    var i=1; var j=1;
    $(document).on('click', '#add_q_option', function() { 
      i++;  
      $('#dynamic_field_question').append('<div class="row" id="row'+i+'"><div class="col-md-10 col-10 form-group"><ul class="list-unstyled list-unstyled-border"> <li class="media"> <div class="custom-control"> <input type="radio" class="select_correct_answer" id="select_correct_answer" name="select_correct_answer" value="'+j+'"> </div><input type="text" id="name_question_choice" name="name_question_choice[]" placeholder="Enter answer" class="form-control name_question_choice" required /></li> </ul></div><div class="col-md-2 col-2"><button type="button" name="remove" id="'+i+'" class="btn btn-danger q_btn_remove">X</button></div></div>');  
      j++;
    });  
    $(document).on('click', '.q_btn_remove', function(){  
      var button_id = $(this).attr("id");   
      $('#row'+button_id+'').remove();  
    }); 
    $('#empTableGS').DataTable({
      'processing': true,
      'serverSide': true,
      "dataType": "json",
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>exam/fetch_exams/'
      },
      'columns': [
        { data: 'examname' },
        { data: 'subject' },
        { data: 'exam_started_time' },
        { data: 'Action' },
      ]
    });
  });
  $(document).on('click', '.copyThisExamName', function(e) {
    e.preventDefault();
    var subject=$(this).attr("value");
    var academicyear=$(this).attr("id");
    var examName=$(this).attr("title");
    var grade=$(this).attr("name");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>exam/copy_this_exam_name/",
      data: ({
        subject: subject,
        academicyear:academicyear,
        examName:examName,
        grade:grade
      }),
      cache: false,
      beforeSend: function() {
        $('.exam_copyingPage').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.exam_copyingPage').html(html);     
      }
    }); 
  });
  $(document).on('click', '#savecopiedexam', function(e) {
    e.preventDefault();
    var examName=$("#hiddencopiedexam_name").val();
    var newCopiedGrade=$("#new_copied_grade").val();
    var examGrade=$("#hiddencopied_grade_name").val();
    var examSubject=$("#hiddencopiedexam_subject_name").val();
    var newCopiedSubject=$("#new_copied_subject").val();
    var academicyear=$("#hiddencopiedacademicYear").val();
    if($("#new_copied_subject").val()!='' && $("#new_copied_grade").val()!=''){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>exam/save_thiscopy_exam_name/",
        data: ({
          examName: examName,
          academicyear:academicyear,
          examSubject:examSubject,
          examGrade:examGrade,
          newCopiedGrade:newCopiedGrade,
          newCopiedSubject:newCopiedSubject
        }),
        cache: false,
        beforeSend: function() {
          $('.exam_copyingPage').html( 'Copying...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          if(html=='1'){
            $('#copyExamPage'). modal('hide');
            $('#empTableGS').DataTable().ajax.reload();
            iziToast.success({
              title: 'Exam copied successfully.',
              message: '',
              position: 'topRight'
            });
          }else{
            iziToast.error({
              title: 'Oooops Please try again later.',
              message: '',
              position: 'topRight'
            });
          }       
        }
      }); 
    }
  });
  $(document).on('click', '.deleteThisExamName', function(e) {
    e.preventDefault();
    swal({
      title: 'Are you sure?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        var subject=$(this).attr("value");
        var academicyear=$(this).attr("id");
        var examName=$(this).attr("title");
        var grade=$(this).attr("name");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>exam/deleteExamName/",
          data: ({
            subject: subject,
            academicyear:academicyear,
            examName:examName,
            grade:grade
          }),
          cache: false,
          success: function(html){
            $('#empTableGS').DataTable().ajax.reload();
          }
        }); 
      }
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
        $("#examGradesec").change(function() {
          var gradesec=$("#examGradesec").val();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url(); ?>Filter_grade_subject/",
                data: {gradesec:gradesec} ,
                success: function(data) {
                    $("#examSubject").html(data);
                }
            });
        });
        $(document).on('change', '#new_copied_grade', function() {
          var gradesec=$("#new_copied_grade").val();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url(); ?>Filter_grade_subject/",
                data: {gradesec:gradesec} ,
                success: function(data) {
                    $("#new_copied_subject").html(data);
                }
            });
        });
    });
  </script>
</body>

</html>