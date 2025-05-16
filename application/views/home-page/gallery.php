<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <form id="mydocument" method="POST">
                      <div class="row">
                        <div class="form-group col-lg-4 col-6">
                          <input id="text" type="text" class="form-control" name="title" placeholder="Picture title here...">
                        </div>
                        <div class="form-group col-lg-4 col-6">
                          <input id="gsGallery" required="required" type="file" class="form-control" name="picture">
                        </div>
                        <div class="form-group col-lg-4 col-12">
                          <button type="submit" name="postgallery" 
                          class="btn btn-primary btn-block">Post</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="mydocuments"></div>
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
</body>
<script type="text/javascript">
    $(document).ready(function(){
        load_data();
        function load_data()
        {
          $.ajax({
            url:"<?php echo base_url(); ?>photogallery/fetchgallery/",
            method:"POST",
            beforeSend: function() {
              $('.mydocuments').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
            },
            success:function(data){
              $('.mydocuments').html(data);
            }
          })
        }
        $('#mydocument').on('submit', function(e) {
          e.preventDefault();
          if($('#gsGallery').val() =='')
          {
            swal({
              title: 'Oooops, Please select your file.',
              text: '',
              icon: 'warning',
              buttons: true,
              dangerMode: true,
            })
            
          }else{
            $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>photogallery/postgallery/",
            data:new FormData(this),
            processData:false,
            contentType:false,
            cache: false,
            async:false,
            success: function(html){
              $('#mydocument')[0].reset();
              load_data();
            }
          });
        }
      });
    });
  </script>
<script>
  $(document).ready(function() {
     $(document).on('click', '.deletegallery', function() {
      var gid = $(this).attr("value");
      swal({
          title: 'Are you sure you want to delete this Picture ?',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
      .then((willDelete) => {
        if (willDelete) {
      
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>photogallery/deltephotogallery/",
          data: ({
            gid: gid
          }),
          cache: false,
          success: function(html) {
            $(".delete_mem" + gid).fadeOut('slow');
          }
        });
      }else {
        return false;
      }
      });
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