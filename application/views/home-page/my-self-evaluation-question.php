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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
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
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <div class="myself-evaluation-question-list"></div>
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
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  $('.gs-sms-self-request-report-page').addClass('active');
  function submit_my_self_evaluation_question_ansewer() {
    question_answer=[];
    $("textarea:input[name=myevaluation_question_name]").each(function(i){
      question_answer[i]=$.trim($(this).val());
    });
    if (question_answer.length < 1) {
      $('#submit_myself_evaluation_question').attr( 'disabled','disabled');
    }else {
      $('#submit_myself_evaluation_question').removeAttr( 'disabled','disabled');
    }
  }
  load_data();
  function load_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>myselfevaluationpage/fetch_my_newself_evaluation_questions/",
      method:"POST",
      beforeSend: function() {
        $('.myself-evaluation-question-list').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.myself-evaluation-question-list').html(data);
      }
    })
  }
  $(document).on('click', '.backToTitlePage', function()
  {
    load_data();
  }); 
   $(document).on('click', '#answer_this_self_evaluation_question', function(e) {
    e.preventDefault();
    var question_title=$(this).attr('value');
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>myselfevaluationpage/fetch_myself_evaluation_questions/",
      data: {
        question_title:question_title
      },
      beforeSend: function() {
        $('.myself-evaluation-question-list').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data){
        $('.myself-evaluation-question-list').html( data);
      }
    });
  });
  $(document).on('submit', '.submit_my_self_evaluation_question', function(e) {
    e.preventDefault();
    swal({
      title: 'Are you sure?',
      text: 'Once submitted you can not edit unless it is empty! ',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        question_answer=[];questionID=[];
        $("textarea:input[name=myevaluation_question_name]").each(function(i){
          question_answer[i]=$.trim($(this).val());
        });
        $("input[name='myevaluation_question_id']").each(function(i){
          questionID[i]=$(this).val();
        });
        if (question_answer.length!=0 ) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>myselfevaluationpage/submit_selfquestion_answer/",
            data: {
              question_answer:question_answer,
              questionID: questionID
            },
            beforeSend: function() {
              $('#submit_myself_evaluation_question').attr('disabled','disabled');
              $('#submit_myself_evaluation_question').html( 'Submiting...' );
            },
            success: function(data){
              if(data=='1'){
                iziToast.success({
                  title: 'Answer submitted successfully.',
                  message: '',
                  position: 'topRight'
                });
              }else{
                iziToast.error({
                  title: 'Ooops please answer all questions.',
                  message: '',
                  position: 'topRight'
                });
              }
              load_data();
              $('#submit_myself_evaluation_question').html( 'Submit Answer' );
            }
          });
        }else{
          swal('Please answer at least one question!', {
            icon: 'error',
          });
        }
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