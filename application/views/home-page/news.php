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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
</head>

<body>
  <div class="loader"> <div class="loaderIcon"></div></div>
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
              <div class="col-12 col-md-12 col-lg-12">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <button type="submit" name="postNewVacancy" id="postNewVacancy" class="btn btn-secondary pull-right" data-toggle="modal" data-target="#postNew_Vacancy" > <i class="fas fa-plus"></i> Post Blogs
                    </button>                    
                  </div>
                  <div class="card-body blogs"> </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="postNew_Vacancy" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Post Blogs</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <form id="postNews" method="POST">
            <div class="row">
              <div class="col-lg-6 col-6">
                <div class="form-group">
                  <label for="Name">Title</label>
                  <input class="form-control" name="title" id="title" required="required" type="text" placeholder="News title">
               </div>
              </div>
              <div class="col-lg-6 col-6">
                <div class="form-group">
                  <label for="logo">News Image</label>
                  <input type="file" name="newsimage" class="form-control" id="customFile">
                </div>
              </div>
              <div class="form-group col-lg-12 col-12">
                <label>News Description</label>
                <textarea name="description" id="description" class="form-control summernote-simple bio">
                </textarea>
              </div>
              <div class="col-lg-12 col-12">
                <button type="submit" value="upload"
                 name="postnews" class="btn btn-primary pull-right">Post News
               </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/summernote/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
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

  <script type="text/javascript">
    $(document).ready(function(){
      load_data();
      function load_data()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>blog/fetchnews/",
          method:"POST",
          beforeSend: function() {
            $('.blogs').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success:function(data){
            $('.blogs').html(data);
          }
        })
      }
      $(document).on('submit', '#postNews', function(e) {
        e.preventDefault();
        if($('#title').val() =='')
        {
          alert("Oooops, Please enter your title.");
        }else{
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>blog/postnews/",
            data:new FormData(this),
            processData:false,
            contentType:false,
            cache: false,
            async:false,
            success: function(html){
              if(html=='1'){
                iziToast.success({
                  title: 'Blog posted successfully',
                  message: '',
                  position: 'topRight'
                });
                load_data();
                $('#postNews')[0].reset();
                $('#description').val('');
                $('#postNew_Vacancy').modal('hide');
              }else{
                iziToast.error({
                  title: 'Oooops Please try again..',
                  message: '',
                  position: 'topRight'
                });
              }
            }
          });
        }
      });
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
     $(document).on('click', '.deletemynews', function() {
      var id = $(this).attr("id");
      swal({
        title: 'Are you sure you want to delete this news permanently ?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>blog/DeleteNews",
            data: ({
              id: id
            }),
            cache: false,
            success: function(html) {
              $(".deletenews" + id).fadeOut('slow');
            }
          });
        }
      });
    });
  });
</script>

</body>
</html>