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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
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
                    <button class="card card-body bg-primary btn-block btn-sm" name="markResultStudent" id="markResultStudentFilter" value="Regular" disabled="disabled">REGULAR RESULT
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
                    <div class="card">
                      <div class="card-body">
                        <div class="header-title"><h4>Incident Report</h4>
                          <div class="incidentReportMy">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-6 order-md-1">
                <div id="load_data"></div>
                <div id="load_data_message"></div>
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
  <script>
  $(document).ready(function() {
    $(document).on('click','.like',function(e){
      e.preventDefault();
      var id = this.id;
      var split_id = id.split("_");
      var text = split_id[0];
      var like_id = split_id[1];
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>fetch_post_likes/",
        data: ({
          like_id: like_id
        }),
        cache: false,
        dataType: 'json',
        success: function(data) {
          var likes = data['countlikes'];
          var typeLikes = data['likesTypes'];
          $(".count-likes_" + like_id).text(likes); 
          if(typeLikes == 1){
            $("#like_"+like_id).css("color","red");
          }if(typeLikes == 0){
            $("#like_"+like_id).css("color","black");
          }
        }
      });
    });
  });
  </script> 
  <script>
  $(document).ready(function(){
    $('.student-notice-board').addClass('active');
    var limit = 3;
    var start = 0;
    var action = 'inactive';
    function lazzy_loader(limit)
    {
      var output = '';
      for(var count=0; count<limit; count++)
      {
        output += '<div class="post_data">';
        output += '<p><span class="content-placeholder" style="width:100%; height: 30px;">&nbsp;</span></p>';
        output += '<p><span class="content-placeholder" style="width:100%; height: 100px;">&nbsp;</span></p>';
        output += '</div>';
      }
      $('#load_data_message').html(output);
    }
    lazzy_loader(limit);
    function load_data(limit, start)
    {
      $.ajax({
        url:"<?php echo base_url(); ?>home/fetch_feeds",
        method:"POST",
        data:{limit:limit, start:start},
        cache: false,
        success:function(data)
        {
          $('#load_data').append(data);
          if(data == '')
          {
            $('#load_data_message').html('<small>No more post found</small>');
            action = 'active';
          }
          else
          {
            $('#load_data_message').html("Please wait...");
            action = 'inactive';
          }
        }
      })
    }
    if(action == 'inactive')
    {
      action = 'active';
      load_data(limit, start);
    }
    $(window).scroll(function(){
      if($(window).scrollTop() + $(window).height() > $("#load_data").height() && action == 'inactive')
      {
        lazzy_loader(limit);
        action = 'active';
        start = start + limit;
        setTimeout(function(){
          load_data(limit, start);
        }, 1000);
      }
    });
  });
</script>
 <script>
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
          $('.incidentReportMy').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">');
        },
        success:function(data){
          $('.incidentReportMy').html(data.response);
          $('.txt_csrfname_gs_home').val(data.token);
        }
      })
    }
  });
</script>
</body>

</html>