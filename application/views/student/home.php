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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/glightbox.min.css" >
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
  <style>
        @-webkit-keyframes placeHolderShimmer {
          0% {
            background-position: -468px 0;
          }
          100% {
            background-position: 468px 0;
          }
        }

        @keyframes placeHolderShimmer {
          0% {
            background-position: -468px 0;
          }
          100% {
            background-position: 468px 0;
          }
        }

        .content-placeholder {
          display: inline-block;
          -webkit-animation-duration: 1s;
          animation-duration: 1s;
          -webkit-animation-fill-mode: forwards;
          animation-fill-mode: forwards;
          -webkit-animation-iteration-count: infinite;
          animation-iteration-count: infinite;
          -webkit-animation-name: placeHolderShimmer;
          animation-name: placeHolderShimmer;
          -webkit-animation-timing-function: linear;
          animation-timing-function: linear;
          background: #f6f7f8;
          background: -webkit-gradient(linear, left top, right top, color-stop(8%, #eeeeee), color-stop(18%, #dddddd), color-stop(33%, #eeeeee));
          background: -webkit-linear-gradient(left, #eeeeee 8%, #dddddd 18%, #eeeeee 33%);
          background: linear-gradient(to right, #eeeeee 8%, #dddddd 18%, #eeeeee 33%);
          -webkit-background-size: 800px 104px;
          background-size: 800px 104px;
          height: inherit;
          position: relative;
        }
    </style>
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
          <div class="section-body" id="viewResultProgressPage">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-12 col-md-6 col-lg-6 order-md-2">
                <div class="row">
                  <div class="col-xl-12 col-lg-12 col-12">
                    <a class="nav-lidnk" href="<?php echo base_url(); ?>myresult/">
                    <button class="card card-body bg-info btn-block btn-sm" name="markResultStudentFilterSubject" id="markResultStudentFilterSubject" value="Regular"> MY SUBJECT RESULT  </button>  </a>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-6">
                    <button class="card card-body bg-primary btn-block btn-sm" name="markResultStudent" id="markResultStudentFilter" value="Regular">REGULAR RESULT
                    </button>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-6">
                    <button class="card card-body bg-secondary btn-block btn-sm" name="markResultStudent" id="markResultStudentFilter" value="Summer" disabled="disabled"> SUMMER RESULT
                    </button>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-6">
                    <a href="<?php echo base_url(); ?>myattendance/">
                      <button class="card card-body bg-info btn-block btn-sm" name="myattendancePage" id="myattendancePage" value="Summer"> ATTENDANCE
                      </button>
                    </a>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-6">
                    <a href="<?php echo base_url(); ?>mycommunicationbook/">
                      <button class="card card-body bg-success btn-block btn-sm" name="mycommunicationPage" id="mycommunicationPage" value="Summer"> COMMUNICATION BOOK
                      </button>
                    </a>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-12">
                    <div class="card">
                      <div class="resultViewPage" id="resultViewPage"></div>
                    </div>
                    <div class="incidentReportMy"> </div>
<!--                     <div class="card">
                      <div class="card-body">
                        <div class="header-title"><h4>Incident Report</h4>
                          
                        </div>
                      </div>
                    </div> -->
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-6 order-md-1">
                <div id="load_poll_data"></div>
                <div id="load_poll_data_message"></div> 
                
                <div class="chart-container">
                  <div class="bar-chart-container">
                    <canvas id="bar-chartProgressSample"></canvas>
                  </div>
                </div>
                <input type="hidden" class="txt_csrfname_gs_home" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>"><br>
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
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/glightbox.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/swiper-bundle.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
  <script src="<?php echo base_url(); ?>assets/chart/chart.js"></script>
  <script type="text/javascript">
    $(document).on('change', '#regular_filter_quarter', function() {
    var quarter=$(this).find('option:selected').attr('value');
    var yearName=$('#regular_academicyear').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>fetch_student_regular_result/thisyear_regular_mark_result/",
      data: ({
        yearName:yearName,
        quarter:quarter
      }),
      beforeSend: function() {
        $('.resultViewPage').html( 'Loading...<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
      },
      success: function(data) {
        $('.resultViewPage').html(data);
      }
    });
  });
    $(document).on('change', '#regular_academicyear', function() {
      var yearName=$(this).find('option:selected').attr('value');
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>fetch_student_regular_result/fetch_thisyear_quarter/",
        data: ({
          yearName:yearName
        }),
        beforeSend: function() {
          $('#regular_filter_quarter').html( 'Loading...<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="24" height="24" id="loa">'
            );
        },
        success: function(data) {
          $('#regular_filter_quarter').html(data);
        }
      });
    });
  $(document).on('click', '#markResultStudentFilter', function() {
    var resultType=$(this).attr("value");
    if(resultType == 'Regular'){
      $.ajax({
        url: "<?php echo base_url(); ?>fetch_student_regular_result/",
        method: "POST",
        data: ({
          resultType: resultType
        }),
        beforeSend: function() {
          $('.resultViewPage').html( 'Loading...<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="24" height="24" id="loa">'
            );
        },
        success: function(data) {
          $('.resultViewPage').html(data);
        }
      });
    }else{
      $.ajax({
        url: "<?php echo base_url(); ?>fetch_student_regular_result/summer_mark_result",
        method: "POST",
        data: ({
          resultType: resultType
        }),
        beforeSend: function() {
          $('.resultViewPage').html( 'Loading...<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="24" height="24" id="loa">'
            );
        },
        success: function(data) {
          $('.resultViewPage').html(data);
        }
      });
    }
  });
</script>
  <script>
    $(document).on('click', '#voteThisPoll', function() {
      var pid = $(this).attr("value");
      var poll_group=$(this).attr("name");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>post_polls/submit_poll/",
        data: ({
          pid: pid,
          poll_group:poll_group
        }),
        cache: false,
        success: function(html) {
          $("#load_poll_data").html(html);
        }
      });
    });
  $(document).ready(function(){
    var limitPoll = 3;
    var startPOll = 0;
    var action1 = 'inactive';
    function lazzy_loader_poll(limitPoll)
    {
      var output = '';
      for(var count=0; count<limitPoll; count++)
      {
        output += '<div class="post_data">';
        output += '<p><span class="content-placeholder" style="width:100%; height: 30px;">&nbsp;</span></p>';
        output += '<p><span class="content-placeholder" style="width:100%; height: 100px;">&nbsp;</span></p>';
        output += '</div>';
      }
      $('#load_poll_data_message').html(output);
    }
    lazzy_loader_poll(limitPoll);
    function load_poll_data(limitPoll, startPOll)
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Post_polls/fetch_poll_data",
        method:"POST",
        data:{limit:limitPoll, start:startPOll},
        cache: false,
        success:function(data)
        {
          if(data == '')
          {
            $('#load_poll_data_message').html('<small></small>');
            action1 = 'active';
          }
          else
          {
            $('#load_poll_data').append(data);
            $('#load_poll_data_message').html("");
            action1 = 'inactive';
          }
        }
      })
    }
    if(action1 == 'inactive')
    {
      action1 = 'active';
      load_poll_data(limitPoll, startPOll);
    }
    $(window).scroll(function(){
      if($(window).scrollTop() + $(window).height() > $("#load_poll_data").height() && action1 == 'inactive')
      {
        lazzy_loader_poll(limitPoll);
        action1 = 'active';
        startPOll = startPOll + limitPoll;
        setTimeout(function(){
          load_poll_data(limitPoll, startPOll);
        }, 1000);
      }
    });
  });
</script>
 <script>
  $('.student-home-page-report').addClass('active');
  $(document).ready(function() { //done
    $.ajax({
      url: "<?php echo base_url(); ?>fetch_myresult_progress/view_sample_progress/",
      method: "POST",
      dataType:"JSON",
      beforeSend: function() {
        $('#bar-chartStaff').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(data) {
        getData(data.dataSubject);
      }
    });
    function getData(data){
      var subject = [];
      var total = [];
      var color = [];
      for(var count = 0; count < data.length; count++)
      {
        subject.push(data[count].subject);
        total.push(data[count].total);
        color.push(data[count].color);
      }
      const randomColor1 = Math.floor(Math.random()*16777215).toString(16);
      const randomColor2 = Math.floor(Math.random()*16777215).toString(16);
      const randomColor3 = Math.floor(Math.random()*16777215).toString(16);
      var chart_data = {
        labels:subject,
        datasets:[
          {
            label:'Total',
            backgroundColor:'#' + randomColor2,
            color:'#fff',
            data:total
          }
        ]
      };
      var options = {
        responsive:true,
        scales:{
          yAxes:[{
            ticks:{
              min:0
            }
          }]
        }
      };
      var group_chart1 = $('#bar-chartProgressSample');
      var graph1 = new Chart(group_chart1, {
        type:"bar",
        data:chart_data
      });
    }
  })
  $(document).ready(function(){
    var csrfName = $('.txt_csrfname_gs_home').attr('name'); 
    var csrfHash = $('.txt_csrfname_gs_home').val(); // CSRF hash
    fetch_feeds_incident_report();
    function fetch_feeds_incident_report()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Myincidentreport/",
        method:"POST",
        data: ({
          [csrfName]:csrfHash
        }),
        dataType:'json',
        beforeSend: function() {
          $('.incidentReportMy').html( 'Loading report...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="10" height="10" id="loa">');
        },
        success:function(data){
          $('.incidentReportMy').html(data.response);
          $('.txt_csrfname_gs_home').val(data.token);
        }
      })
    }
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
      $("body").removeClass("dark");
      $("body").removeClass("dark-sidebar");
      $("body").removeClass("theme-black");
      $("body").addClass("light");
      $("body").addClass("light-sidebar");
      $("body").addClass("theme-white");
    } else {
      $("body").removeClass("light");
      $("body").removeClass("light-sidebar");
      $("body").removeClass("theme-white");
      $("body").addClass("dark");
      $("body").addClass("dark-sidebar");
      $("body").addClass("theme-black");
    }
  });
</script>
<script type="text/javascript" language="javascript"> 
  var bgcolor_now=document.getElementById("bgcolor_now").value;
  if (bgcolor_now == "1") {
    $("body").removeClass("dark");
    $("body").removeClass("dark-sidebar");
    $("body").removeClass("theme-black");
    $("body").addClass("light");
    $("body").addClass("light-sidebar");
    $("body").addClass("theme-white");
  }else {
    $("body").removeClass("light");
    $("body").removeClass("light-sidebar");
    $("body").removeClass("theme-white");
    $("body").addClass("dark");
    $("body").addClass("dark-sidebar");
    $("body").addClass("theme-black"); 
  } 
</script>  
<script>
  $(document).ready(function() {
    function my_mark_status(view = '') {
      $.ajax({
        url: "<?php echo base_url() ?>my_mark_status/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType: "json",
        success: function(data) {
          $('.notification-show-mark').html(data.notification);
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
    my_mark_status();
    inbox_unseen_notification();
    $(document).on('click', '.seen_noti', function() {
      $('.count-new-notification').html('');
      my_mark_status('yes');
    });
    $(document).on('click', '.seen', function() {
      $('.count-new-inbox').html('');
      inbox_unseen_notification('yes');
    });
    setInterval(function() {
      my_mark_status();
      inbox_unseen_notification();
    }, 5000);
  });
</script>
</body>

</html>