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
            <div class="subscriptionData" id="subscriptionData"></div>
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
<script type="text/javascript">
  load_group_data();
  function load_group_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>subscription/subscriptionData/",
      method:"POST",
      beforeSend: function() {
        $('#subscriptionData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('#subscriptionData').html(data);
      }
    })
  }
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