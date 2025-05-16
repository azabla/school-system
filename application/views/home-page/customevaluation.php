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
  <link rel='shortcut icon' type='image/x-icon'
   href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
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
              <div class="card-header">
                <h5>Custom Evaluation Page</h5>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <a href="#" id="evaluation_status"></a>
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#customEvaluation" role="tab" aria-selected="true">New Evaluation</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#fetchcustomEvaluation" role="tab" aria-selected="false">View Custom Evaluation</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="customEvaluation" role="tabpanel" aria-labelledby="home-tab1">
                        <form id="saveCustomEvaluation" method="POST">
                          <div class="row">
                            <div class="col-lg-4 col-8 table-responsive datapageheight">
                              <div class="form-group">
                                <div class="row">
                                  <?php foreach($grade as $grades){ ?>
                                    <div class="col-lg-4 col-6">
                                    <div class="pretty p-icon p-jelly p-round p-bigger">
                                     <input id="eva_grade" type="checkbox" class="grade" name="grade" value="<?php echo $grades->grade; ?>">
                                     <div class="state p-info">
                                        <i class="icon material-icons"></i>
                                        <label></label>
                                     </div>
                                     </div>
                                      <?php echo $grades->grade; ?>
                                      <div class="dropdown-divider2"></div>
                                    </div>
                                  <?php } ?>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-2 col-4 evaluation_here"> </div>
                            <div class="col-md-6 col-12 defaultSubjectHere"> </div>
                            <div class="col-lg-6 col-6">
                              <div class="form-group">
                                <input class="form-control eva_percent" name="customPercent" type="number" placeholder="Value (In Number)...">
                              </div>
                            </div>
                            <div class="col-lg-6 col-6  pull-right">
                              <div class="form-group">
                                <button type="submit" name="postCustomEvaluation" class="btn btn-outline-primary btn-block">Save Evaluation </button>
                              </div>
                                <a href="#" class="save_info"></a>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="tab-pane fade show" id="fetchcustomEvaluation" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="card table-responsive datapageheight">
                          <div id="evaluationData"> </div>
                        </div>
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
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script type="text/javascript">
    $(document).on('click', '.grade', function() {
      grade2analysis=[];
      $("input[name='grade']:checked").each(function(i){
        grade2analysis[i]=$(this).val();
      });
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>customevaluation/filterSubject4CustomEvaluation/",
        data:({
          grade2analysis:grade2analysis
        }),
        beforeSend: function() {
          $('.defaultSubjectHere').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".defaultSubjectHere").html(data);
        }
      });
    });
    $(document).on('click', '.grade', function() {
      grade2analysis=[];
      $("input[name='grade']:checked").each(function(i){
        grade2analysis[i]=$(this).val();
      });
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>customevaluation/filterAssesmentCustomEvaluation/",
        data:({
          grade2analysis:grade2analysis
        }),
        beforeSend: function() {
          $('.evaluation_here').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".evaluation_here").html(data);
        }
      });
    });
  </script>
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>customevaluation/fetchCustomEvaluations/",
        method:"POST",
        beforeSend: function() {
          $('#evaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success:function(data){
          $('#evaluationData').html(data);
        }
      })
    }
    $('#saveCustomEvaluation').on('submit', function(event) {
      event.preventDefault();
      var grade=$('#eva_grade').val();
      /*var evalname=$('.assesment4CustomEvaluation').val();*/
      var percent=$('.eva_percent').val();
      id=[];subject=[];evalname=[];
      $("input[name='grade']:checked").each(function(i){
        id[i]=$(this).val();
      });
      $("input[name='subject4CustomEvaluation']:checked").each(function(i){
        subject[i]=$(this).val();
      });
      $("input[name='assesment4CustomEvaluation']:checked").each(function(i){
        evalname[i]=$(this).val();
      });
      if( id.length == 0 || subject.length == 0 || $('.eva_percent').val() =='' || evalname.length == 0)
      {
        swal('Oooops, Please select necessary fields!', {
          icon: 'warning',
        });
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>customevaluation/postCustomEvaluation/",
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
      url:"<?php echo base_url(); ?>customevaluation/movingEvaluations/",
      method:"POST",
      beforeSend: function() {
        $('#evaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
      },
      success:function(data){
        $('#evaluationData').html(data);
        load_data();
      }
    })
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
<script>
  $(document).ready(function() {
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>customevaluation/fetchCustomEvaluations/",
        method:"POST",
        beforeSend: function() {
          $('#evaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success:function(data){
          $('#evaluationData').html(data);
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
        swal('Assesment evaluation deleted successfully!', {
          icon: 'success',
        });
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>customevaluation/deleteCustomEvaluation/",
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
  });
</script>
<script>
  $(document).ready(function() { 
    function load_evaluation_status()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>fetch_evaluation_status",
        method:"POST",
        success:function(data){
          $('#evaluation_status').html(data);
        }
      })
    }
    function unseen_notification(view = '') { 
      $.ajax({
        url: "<?php echo base_url() ?>fetch_unseen_notification/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType: "json",
        success: function(data) {
          $('.notification-show').html(data.notification);
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
    unseen_notification();
    inbox_unseen_notification();
    load_evaluation_status();
    $(document).on('click', '.seen_noti', function() {
      $('.count-new-notification').html('');
      inbox_unseen_notification('yes');
    });
    $(document).on('click', '.seen', function() {
      $('.count-new-inbox').html('');
      inbox_unseen_notification('yes');
    });
    setInterval(function() {
      load_evaluation_status();
      unseen_notification();
      inbox_unseen_notification();
    }, 5000);
  });
</script>
</body>

</html>