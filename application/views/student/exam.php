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
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.css" rel="stylesheet">
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
              <div class="col-12 col-md-12 col-lg-12">
                <h4> <span id="countdown" class="timer text-danger"> </span> </h4>
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <div id="fetch_mynew_exam"></div>
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
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/gs_custom.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.js"></script>
  <script type="text/javascript">
    $('.student-onlin-exam-board').addClass('active');
    $(document).ready(function() {
      load_exam_data();
      function load_exam_data()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>myexam/fetch_mynew_exam/",
          method:"POST",
          beforeSend: function() {
            $('#fetch_mynew_exam').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success:function(data){
            $('#fetch_mynew_exam').html(data);
          }
        })
      }
      $(document).on('click', '.startmyexam', function() {
        swal({
          title: 'Are you sure?',
          text: 'Once you start the exam time will start automatically and cannot revert back until you finished the exam',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            var subject=$(this).attr("value");
            var stuid=$(this).attr("id");
            var examName=$(this).attr("title");
            var grade=$(this).attr("name");
            var year=$("#liveExamYear").val();
            var time_in_minutes =  document.getElementById("liveExamDuration").value;
            var terminate_status =  document.getElementById("terminateLiveExamTime").value;
            $.ajax({
              method: "POST",
              url: "<?php echo base_url(); ?>myexam/start_live_exam/",
              data: ({
                subject: subject,
                stuid:stuid,
                examName:examName,
                grade:grade,
                year:year
              }),
              cache: false,
              beforeSend: function() {
                $('#fetch_mynew_exam').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
              },
              success:function(data){
                $('#fetch_mynew_exam').html(data);
                $("#my_short_anser").summernote({
                  placeholder: "Write your question here",
                  height: 100,
                });
              }
            }); 
            
            var current_time = Date.parse(new Date());
            var deadline = new Date(current_time + time_in_minutes*60*1000);
            function time_remaining(endtime){
              var t = Date.parse(endtime) - Date.parse(new Date());
              var seconds = Math.floor( (t/1000) % 60 );
              var minutes = Math.floor( (t/1000/60) % 60 );
              var hours = Math.floor( (t/(1000*60*60)) % 24 );
              var days = Math.floor( t/(1000*60*60*24) );
              return {'total':t, 'days':days, 'hours':hours, 'minutes':minutes, 'seconds':seconds};
            }
            function run_clock(id,endtime){
              var clock = document.getElementById(id);
              function update_clock(){
                var t = time_remaining(endtime);
                clock.innerHTML = t.minutes+' :minutes & '+ t.seconds + ': seconds left';
                if(t.total<=0){ 
                  clearInterval(timeinterval); 
                  if(terminate_status=='1'){
                    window.location.href = '<?php echo base_url(); ?>myexam/';
                  }                  
                  /*window.location.replace("<?php echo base_url(); ?>myexam/fetch_mynew_exam/");*/
                }
              }
              update_clock(); // run function once at first to avoid delay
              var timeinterval = setInterval(update_clock,1000);
            }
            run_clock('countdown',deadline);
          }
        });
      });
      $(document).on('click', '.select_exam_choice', function() {
        var subject=$("#subject_answer").val();
        var examName=$("#examName_answer").val();
        var eid=$(this).attr("name");
        var myanswer=$(this).attr("value");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>myexam/submit_mylive_exam_answer/",
          data: ({
            subject: subject,
            eid:eid,
            examName:examName,
            myanswer:myanswer
          }),
          cache: false,
          success:function(data){
            if(data=='1'){
              iziToast.success({
                title: 'Answer submitted successfully.',
                message: '',
                position: 'topRight'
              });
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
      $(document).on('click', '.save_short_answer', function() {
        var subject=$("#subject_answer").val();
        var examName=$("#examName_answer").val();
        var eid=$(this).attr("id");
        var myanswer=$('#my_short_anser').val();
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>myexam/submit_mylive_exam_answer/",
          data: ({
            subject: subject,
            eid:eid,
            examName:examName,
            myanswer:myanswer
          }),
          cache: false,
          beforeSend:function(){
            $('.save_short_answer').attr( 'disabled','disabled');
            $('.save_short_answer').html( 'Saving...' );
          },
          success:function(data){
            $('.save_short_answer').removeAttr( 'disabled');
            $('.save_short_answer').html( 'Save Changes' );
            if(data=='1'){
              iziToast.success({
                title: 'Answer submitted successfully.',
                message: '',
                position: 'topRight'
              });
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