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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
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
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <button type="submit" name="postNewVacancy" id="postNewVacancy" class="btn btn-secondary pull-right" data-toggle="modal" data-target="#postNew_Vacancy" > <i class="fas fa-plus"></i> Post Vacancy
                    </button>
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#viewPostedVacancy" role="tab" aria-selected="false">Posted Vacancy</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#viewApplicants" role="tab" aria-selected="false">View Applicants</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="viewPostedVacancy" role="tabpanel" aria-labelledby="home-tab3">
                        <div class="blogs"> </div>
                      </div>
                      <div class="tab-pane fade show" id="viewApplicants" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="viewApplicants"> </div>
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
  <div class="modal fade" id="viewPostedVacancyDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            Vacancy Detail
            <h5 class="modal-title" id="">
            <button class="btn btn-default" onclick="codespeedy()" name="" type="submit" id="">
                <span class="text-warning">Print <i class="fas fa-print"></i></span>
            </button></h5>
          </div>
          <div class="modal-body vacancyDetailHere" id="printviewVacancyDetail">
            
          </div>
          <div class="modal-footer bg-whitesmoke br">
            <a id="saveskygrade"></a>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
  </div>
  <div class="modal fade" id="postNew_Vacancy" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Post Vacancy</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <form id="postVacancy" method="POST">
            <div class="row">
              <div class="col-lg-6 col-6">
                <div class="form-group">
                  <label for="Name">Position</label>
                  <input class="form-control" name="position" id="position" required="required" type="text" placeholder="Vacancy Position" >
                </div>
              </div>
              <div class="col-lg-6 col-6">
                <div class="form-group">
                  <label for="logo">Expire Date</label>
                  <div class="custom-file">
                    <input type="date" id="expireDate" name="expireDate" class="form-control" required>
                  </div>
                </div>
              </div>
              <div class="form-group col-lg-12 col-12">
                <label>Vacancy Description</label>
                <textarea name="description" id="descriptionVacancy" class="form-control summernote-simple bio" required> </textarea>
              </div>
              <div class="col-lg-12 col-12">
                <button type="submit" value="upload" name="postnews" class="btn btn-primary pull-right">Post Vacancy
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
    function codespeedy(){
      var print_div = document.getElementById("printviewVacancyDetail");
      var print_area = window.open();
      print_area.document.write(print_div.innerHTML);
      print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
      print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
      print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
      print_area.document.close();
      print_area.focus();
      print_area.print();
    }
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

  <script type="text/javascript">
    $(document).ready(function(){
      load_data();
      loadApplicants();
      function loadApplicants()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>employment/loadApplicants/",
          method:"POST",
          beforeSend: function() {
            $('.viewApplicants').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success:function(data){
            $('.viewApplicants').html(data);
          }
        })
      }
      function load_data()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>employment/fetchvacancy/",
          method:"POST",
          beforeSend: function() {
            $('.blogs').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success:function(data){
            $('.blogs').html(data);
          }
        })
      }
      $(document).on('submit', '#postVacancy', function(e) {
          e.preventDefault();
          if($('#position').val() =='' || $('#descriptionVacancy').val()=='')
          {
            alert("Oooops, Please enter all necessary fields.");
          }else{
            var position=$('#position').val();
            var expireDate=$('#expireDate').val();
            var description=$('#descriptionVacancy').val();
            $.ajax({
              method: "POST",
              url: "<?php echo base_url(); ?>employment/postvacancy/",
              data:({
                position:position,
                expireDate:expireDate,
                description:description
              }),
              cache: false,
              success: function(html){
                if(html=='1'){
                  iziToast.success({
                    title: 'Vacancy posted successfully',
                    message: '',
                    position: 'topRight'
                  });
                  load_data();
                  $('#postVacancy')[0].reset();
                  $('#descriptionVacancy').val('');
                  $('#postNew_Vacancy').modal('hide');
                }else{
                  iziToast.error({
                    title: 'Oooops Please try again.May be expire date is wrong.',
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
     $(document).on('click', '.deletemyvacancy', function() {
      var id = $(this).attr("id");
      swal({
          title: 'Are you sure you want to delete this Vacancy ?',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
      .then((willDelete) => {
        if (willDelete) {
      
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>employment/Deletevacancy",
          data: ({
            id: id
          }),
          cache: false,
          success: function(html) {
            $(".deletevacancy" + id).fadeOut('slow');
          }
        });
      }
      });
    });
  });
  $(document).on('click', '.viewmyvacancy', function() {
    var id = $(this).attr("id");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>employment/viewmyvacancyDetail/",
        data: ({
          id: id
        }),
        cache: false,
        success: function(html) {
          $(".vacancyDetailHere").html(html);
        }
      });
  });
</script>


</body>
</html>