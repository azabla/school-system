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
  <!-- General CSS Files -->
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
            <div class="row">
              <div class="form-group col-md-3 col-4">
                <label>Exam Name</label><br>
                <?php echo $passFeild[0]; ?>
              </div>
              <div class="form-group col-md-3 col-4">
                <label>Grade</label><br>
                <?php echo $passFeild[1]; ?>
              </div>
              <div class="form-group col-md-3 col-4">
                <label>Subject</label><br>
                <?php echo $passFeild[2]; ?>
              </div>
              
                <?php foreach($exam_header as $examName){ ?>
              <div class="form-group col-md-3 col-4">
                <label>Allowed Time</label><br>
                <?php echo $examName->examinute; ?>'
              </div>
              <div class="form-group col-md-3 col-4">
                <label>Academic Year</label><br>
                <?php echo $passFeild[3]; ?>
              </div>
              <div class="form-group col-md-3 col-4">
                <label>Started Time</label><br>
                <?php echo $examName->exam_started_time; ?>
              </div>
              <div class="form-group col-md-5 col-12">                       
                <?php
                $terminateStatus=$examName->exam_terminate_status;
                $see_result_status=$examName->see_result_status;
                if($terminateStatus=='1'){ ?>
                  <div class="pretty p-switch">
                    <input type="checkbox" name="" checked="checked" class="" id="" value="0" disabled>Terminate exam when time run out.
                    <div class="state p-success pull-right">
                        <label></label>
                    </div>
                </div>
                <?php }else{ ?>
                  <div class="pretty p-switch">
                    <input type="checkbox" name="" class="" id="" value="1" disabled>Terminate exam when time run out.
                    <div class="state p-success pull-right">
                        <label></label>
                    </div>
                  </div>
                  <?php } if($see_result_status=='1'){ ?>
                <div class="pretty p-switch">
                    <input type="checkbox" name="" class="" checked="checked" id="" value="0" disabled>Show result automatically
                    <div class="state p-success pull-right">
                        <label></label>
                    </div>
                </div>
                <?php }else{ ?>
                <div class="pretty p-switch">
                    <input type="checkbox" name="" class="" id="" value="1" disabled>Show result automatically
                    <div class="state p-success pull-right">
                        <label></label>
                    </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <?php $no=1;$dataOption=array('Multiple Choice','True/False','Short Answer');
            foreach($edit_exam as $exam_Name){
              $examGroup=$exam_Name->examGroup; 
              $questionName=$exam_Name->question;
              $question_type=$exam_Name->question_type;
              $eid=$exam_Name->eid;
              $subject= $passFeild[2];
              $grade=$passFeild[1]; 
              $examName=$passFeild[0];
              $academicyear= $passFeild[3]; ?>
              <div class="support-ticket media pb-1 mb-3 delelete_question<?php echo $examGroup ?>">
                <div class="media-body">
                  <div class="badge badge-pill badge-light mb-1 float-right"> 
                    <a class="edit_this_question_details" id="<?php echo $examGroup ?>" value="<?php echo $subject ?>"  title="<?php echo $examName ?>" name="<?php echo $grade ?>" type="submit" data-toggle="modal" data-target="#editQuestionPage"><span class="text-success"><i class="fas fa-pen-alt"></i></span></a>
                    <div class="bullet"></div>
                  <a class="delete_this_question_details" id="<?php echo $examGroup ?>" value="<?php echo $subject ?>" title="<?php echo $examName ?>" name="<?php echo $grade ?>" type="submit"><span class="text-danger"><i class="fas fa-trash-alt"></i></span></a></div>
                  <div class="badge badge-pill badge-light mb-1 float-right"><?php echo $exam_Name->question_weight;?>pts.</div>
                  <span class="font-weight-bold">Q.<?php echo $no ?> <?php echo html_entity_decode($questionName) ?> </span>
                <?php 
                $this->db->where(array('subject'=>$subject));
                $this->db->where(array('academicyear'=>$academicyear));
                $this->db->where(array('grade'=>$grade));
                $this->db->where(array('examname'=>$examName));
                $this->db->where(array('examGroup'=>$examGroup));
                $this->db->group_by('eid,examGroup');
                $queryFetch=$this->db->get('exam');
                foreach($queryFetch->result() as $row_choice) { 
                  $examGroupNow=$row_choice->examGroup;
                  $eidNow=$row_choice->eid;
                  $answer=$row_choice->answer;
                  $optionChoice=$row_choice->a;?>
                  <?php if($answer=='' && $optionChoice == ''){ ?>
                    <ul>
                      <li class="text-danger">No answer for this question</li>
                    </ul>
                  <?php } elseif($answer == '' && $optionChoice != ''){ ?>
                    <ul>
                      <li><?php echo htmlentities($optionChoice) ?></li>
                    </ul>
                  <?php } elseif($answer != '' && $optionChoice == ''){?>
                    <ul style="background-color: #DFF5E5;">
                      <li><?php echo htmlentities($answer) ?></li>
                    </ul>
                  <?php } else if(strcasecmp($answer, $optionChoice) === 0 && trim($answer) === trim($optionChoice) && strcmp($answer, $optionChoice) === 0){ ?>
                    <ul style="background-color: #DFF5E5;">
                      <li><?php echo htmlentities($optionChoice) ?></li>
                    </ul>
                    <?php } else{ ?>
                      <ul>
                        <li><?php echo htmlentities($optionChoice) ?></li>
                      </ul>
                <?php } } $no++; ?>
                 </div>
              </div>
              <?php } ?>
          </div>
        </section>
      </div>
       <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="editQuestionPage" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="borrow_msg_book" id="borrow_msg_book">Edit Exam </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="exam_editingPage"></div>
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
    $(document).on('click', '.edit_this_question_details', function() {
      var subject=$(this).attr("value");
      var examGroup=$(this).attr("id");
      var examName=$(this).attr("title");
      var grade=$(this).attr("name");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>examform/edit_this_exam_name/",
        data: ({
          subject: subject,
          examGroup:examGroup,
          examName:examName,
          grade:grade
        }),
        cache: false,
        beforeSend: function() {
          $('.exam_editingPage').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.exam_editingPage').html(html); 
          $('#editedname_Question').summernote({
              placeholder: 'Create you content here.',
              tabsize: 2,
              height: '15vh',
              toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
              ]
            }); 
          /*$("#editedname_Question").summernote({
              placeholder: "Type your question here",
              height: 100,
          });*/   
        }
      }); 
    });
    $(document).on('click', '.delete_this_question_details', function() {
      swal({
        title: 'Are you sure?',
        text: 'The file will be erased permanently.',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          var subject=$(this).attr("value");
          var examGroup=$(this).attr("id");
          var examName=$(this).attr("title");
          var grade=$(this).attr("name");
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>examform/deleteQuestionName/",
            data: ({
              subject: subject,
              examGroup:examGroup,
              examName:examName,
              grade:grade
            }),
            cache: false,
            beforeSend:function(){
              $('.delelete_question' + examGroup).html( 'Deleting...');
            },
            success: function(html){
              $('.delelete_question' + examGroup).fadeOut( 'slow');
              if(html=='1'){
                $('#copyExamPage'). modal('hide');
                iziToast.success({
                  title: 'Deleted successfully.',
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
    });
    $(document).on('click', '#saveeditedexam', function(e) {
      e.preventDefault();
      eid=[];editedquestion_answer=[];question_name=[];
      editedquestion_type=[];editedquestion_weight=[];
      ca_gs=[];ca_hiddenGroup=[];
      $("input[name='hidden_questionID']").each(function(i){
        eid[i]=$(this).val();
      });
      /*$("input[name='hidden_examGroup']").each(function(i){
        ca_hiddenGroup[i]=$(this).val();
      });*/
      /*$("textarea:input[name=editedname_Question]").each(function(i){
        question_name[i]=$.trim($(this).val());
      });*/
      /*$("select:input[name='editedquestion_type']").each(function(i){
        editedquestion_type[i]=$(this).val();
      });*/
      /*$("input[name='editedquestion_weight']").each(function(i){
        editedquestion_weight[i]=$(this).val();
      });*/
      $("input[name='editedca']").each(function(i){
        ca_gs[i]=$(this).val();
      });
      var best_correct_answer=$("input[name='best_correct_answer']:checked").val();
      var editedquestion_weight=$("#editedquestion_weight").val();
      var editedquestion_type=$("#editedquestion_type").val();
      var question_name=$("#editedname_Question").val();
      var ca_hiddenGroup=$("#hidden_examGroup").val();
      var examName=$("#editedExamName").val();
      var examGrade=$("#hiddenexam_grade_name").val();
      var examSubject=$("#hiddenexam_subject_name").val();
      var exam_started_time=$("#editedStartedTime").val();
      var minuteAllowed=$("#editedAllowedTime").val();
      var hiddenexam_name=$("#hiddenexam_name").val();
      if($('#editedterminate_exam_status').is(':checked')){
        var terminate_exam_status='1';
      }else{
        var terminate_exam_status='0';
      }
      if($('#editedsee_resut_automatically').is(':checked')){
        var see_resut_automatically='1';
      }else{
        var see_resut_automatically='0';
      }
      $.ajax({
        url:"<?php echo base_url() ?>examform/update_exam_name/",
        method:"POST",
        data:({
          examName:examName,
          examGrade:examGrade,
          examSubject:examSubject,
          question_name:question_name,
          editedquestion_type:editedquestion_type,
          minuteAllowed:minuteAllowed,
          editedquestion_weight:editedquestion_weight,
          terminate_exam_status:terminate_exam_status,
          exam_started_time:exam_started_time,
          editedquestion_answer:editedquestion_answer,
          see_resut_automatically:see_resut_automatically,
          hiddenexam_name:hiddenexam_name,
          eid:eid,
          best_correct_answer:best_correct_answer,
          ca_gs:ca_gs,
          ca_hiddenGroup:ca_hiddenGroup
        }),
        beforeSend:function(){
          $('#saveeditedexam').attr( 'disabled','disabled');
          $('#saveeditedexam').html( 'Saving changes...' );
        },
        success: function(data){
          $('#saveeditedexam').removeAttr( 'disabled');
          $('#saveeditedexam').html( 'Save Changes' );
          if(data=='1'){
            iziToast.success({
              title: 'Changes updated successfully.',
              message: '',
              position: 'topRight'
            });
            $('#editQuestionPage'). modal('hide');
            var redirectUrl = "<?php echo base_url('exam');?>";
            window.location.href = redirectUrl;
          }else{
            iziToast.error({
              title: 'Something wrong, please try again.',
              message: '',
              position: 'topRight'
            });
          }
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

</body>

</html>