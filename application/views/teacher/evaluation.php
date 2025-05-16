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
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-12">
                <a href="#" id="evaluation_status"></a>
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <a href="#" class="AddNewCustomEvaluation" value="" data-toggle="modal" data-target="#newCustomEvaluation">
                      <button class="btn btn-info pull-right" disabled="disabled"><i data-feather="plus-circle"> </i> Add Custom Evaluation</button>
                    </a>
                    <div class="card table-responsive">
                      <div id="customEvaluationData"> </div>
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
  
  <!--  -->
  <div class="modal fade" id="newCustomEvaluation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add New Custom Evaluation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="dropdown-divider"></div>
        <div class="card">
          <div class="card-body StudentViewTextInfo">
            
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/gs_all.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>studentevaluationweight/fetchCustomEvaluations/",
        method:"POST",
        beforeSend: function() {
          $('#customEvaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#customEvaluationData').html(data);
        }
      })
    }
     $(document).on('click', '.deleteCustomvaluation', function() {
      var post_id = $(this).attr("id");
      var quarter = $(this).attr("value");
      var evname = $(this).attr("name");
      swal({
        title: 'Are you sure?',
        text: 'Once deleted you can not recover this evaluation mark data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>studentevaluationweight/deleteCustomEvaluation/",
          data: ({
            post_id: post_id,
            quarter:quarter,
            evname :evname
          }),
          cache: false,
          success: function(html) {
            load_data();
          }
        });
      }
      });
    });
    $('#saveCustomEvaluation').on('submit', function(event) {
      event.preventDefault();
      var grade=$('#eva_grade_custom').val();
      var percent=$('.eva_percent_custom').val();
      id=[];subject=[];evalname=[];
      $("input[name='grade_custom']:checked").each(function(i){
        id[i]=$(this).val();
      });
      $("input[name='subject4CustomEvaluation']:checked").each(function(i){
        subject[i]=$(this).val();
      });
      $("input[name='assesment4CustomEvaluation']:checked").each(function(i){
        evalname[i]=$(this).val();
      });
      if( id.length == 0 || subject.length == 0 || $('.eva_percent_custom').val() =='' || evalname.length == 0)
      {
        swal('Oooops, Please select necessary fields!', {
          icon: 'warning',
        });
      }else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>studentevaluationweight/postCustomEvaluation/",
          data: ({
            id: id,
            evalname:evalname,
            percent:percent,
            subject:subject
          }),
          cache: false,
          success: function(html){
            $('#saveCustomEvaluation')[0].reset();
            load_data();
          }
        });
      }
    });
    $(document).on('click', '#moveCustomEvaluation', function() {
      $.ajax({
        url:"<?php echo base_url(); ?>studentevaluationweight/movingCustomEvaluations/",
        method:"POST",
        beforeSend: function() {
          $('#customEvaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#customEvaluationData').html(data);
          load_data();
        }
      })
    });
  });
    $(document).on('click', '.grade_custom', function() {
      grade2analysis=[];
      $("input[name='grade_custom']:checked").each(function(i){
        grade2analysis[i]=$(this).val();
      });
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>studentevaluationweight/filterSubject4CustomEvaluation/",
        data:({
          grade2analysis:grade2analysis
        }),
        beforeSend: function() {
          $('.defaultSubjectHere_custom').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".defaultSubjectHere_custom").html(data);
        }
      });
    });
    $(document).on('click', '.grade_custom', function() {
      grade2analysis=[];
      $("input[name='grade_custom']:checked").each(function(i){
        grade2analysis[i]=$(this).val();
      });
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>studentevaluationweight/filterAssesmentCustomEvaluation/",
        data:({
          grade2analysis:grade2analysis
        }),
        beforeSend: function() {
          $('.evaluation_here_custom').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".evaluation_here_custom").html(data);
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
</body>

</html>