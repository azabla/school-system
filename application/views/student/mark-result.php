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
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <div id="mySubjectList"> </div>
                <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>"><br>   
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
</body>
<script type="text/javascript">
  $('.student-subject-board').addClass('active');
    $(document).ready(function(){
      $(document).on('click', '#viewThisSubjectResult', function() { 
        var csrfName = $('.txt_csrfname').attr('name'); // Value specified in $config['csrf_token_name']
        var csrfHash = $('.txt_csrfname').val(); // CSRF hash
        var subid = $(this).attr("value");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Myresult/fetchThisSubjectResult/",
          data: ({
            subid: subid,
            [csrfName]:csrfHash
          }),
          dataType: 'json',
          beforeSend: function() {
            $('#mySubjectList').html( 'Loading result<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success:function(response){
            $('.txt_csrfname').val(response.token);
            $('#mySubjectList').html(response.result);
          }
        });
      });
    });
  $(document).on('click', '#backToSubjectPage', function()
  {
    var csrfName = $('.txt_csrfname').attr('name'); // Value specified in $config['csrf_token_name']
    var csrfHash = $('.txt_csrfname').val(); // CSRF hash
    $.ajax({
      url:"<?php echo base_url(); ?>Myresult/fetchMySubject/",
      method:"POST",
      dataType:'json',
      data: ({
        [csrfName]:csrfHash
      }),
      beforeSend: function() {
        $('#mySubjectList').html( 'Loading subject<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(response){
        $('#mySubjectList').html(response.subject);
        $('.txt_csrfname').val(response.token);
      }
    })
  }); 
  $(document).on('click', '#viewMyDetailResult', function() { 
    var csrfName = $('.txt_csrfname').attr('name'); // Value specified in $config['csrf_token_name']
    var csrfHash = $('.txt_csrfname').val(); // CSRF hash
    var subid = $(this).attr("name");
    var quarter = $(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Myresult/fetchThisSubjectDeatilResult/",
      data: ({
        subid: subid,
        quarter:quarter,
        [csrfName]:csrfHash
      }),
      cache: false,
      dataType:'json',
      beforeSend: function() {
        $('#mySubjectList').html( 'Loading result<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(response){
        $('#mySubjectList').html(response.result);
        $('.txt_csrfname').val(response.token);
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    var csrfName = $('.txt_csrfname').attr('name'); // Value specified in $config['csrf_token_name']
    var csrfHash = $('.txt_csrfname').val(); // CSRF hash
    loadSubjectData();
    function loadSubjectData()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Myresult/fetchMySubject/",
        method:"POST",
        dataType:'json',
        data: ({
          [csrfName]:csrfHash
        }),
        beforeSend: function() {
          $('#mySubjectList').html( 'Loading subject<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#mySubjectList').html(data.subject);
          $('.txt_csrfname').val(data.token);
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