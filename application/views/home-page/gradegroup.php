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
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="header-title">Group Name Page</h5>
                    <form id="save_evaluation" method="POST">
                      <div class="row">
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <label for="evname">Group Name</label>
                            <select class="form-control eva_name" name="evname">
                              <option></option>
                              <?php foreach($gradegroups as $gradeGroupss){ ?>
                                <option><?php echo $gradeGroupss->dname; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                         </div>
                        <div class="col-lg-7 col-6 table-responsive" style="height: 15vh;">
                          <div class="form-group">
                            <label for="Mobile">Select grade</label><br>
                            <div class="row">
                              <?php foreach($grade as $grades){ ?>
                                <div class="col-lg-3 col-6">
                                <div class="pretty p-icon p-jelly p-round p-bigger">
                                 <input id="eva_grade" type="checkbox" name="grade" value="<?php echo $grades->grade; ?>">
                                 <div class="state p-info">
                                    <i class="icon material-icons"></i>
                                    <label></label>
                                 </div>
                                 </div>
                                  <?php echo $grades->grade; ?>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12  pull-right">
                          <div class="form-group">
                            <button type="submit" name="postevaluation" class="btn btn-primary btn-block">Save Group </button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="table-responsive" style="height: 45vh;">
                  <div id="evaluationData" > </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <footer class="main-footer">
        <div class="footer-left">
         Call:+251967829025 &nbsp;&nbsp;
          Copyright &copy <?php echo date('Y');?>
          <a href="https://www.grandstande.com" target="_blanck">Grandstand IT Solutions Plc</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>gradegroup/fetchGradeGroup/",
        method:"POST",
        beforeSend: function() {
          $('#evaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success:function(data){
          $('#evaluationData').html(data);
        }
      })
    }
    $('#save_evaluation').on('submit', function(event) {
      event.preventDefault();
      var grade=$('#eva_grade').val();
      var evname=$('.eva_name').val();
      id=[];
      $("input[name='grade']:checked").each(function(i){
        id[i]=$(this).val();
      });
      if( id.length == 0 || $('.eva_name').val() =='')
      {
        swal('Oooops, Please select necessary fields!', {
          icon: 'warning',
        });
      }
      else{
        swal('Group saved successfully!', {
          icon: 'success',
        });
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>gradegroup/postGradeGroup/",
          data: ({
            id: id,
            evname:evname
          }),
          cache: false,
          success: function(html){
            $('#save_evaluation')[0].reset();
            load_data();
          }
        });
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
<script>
  $(document).ready(function() {
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>gradegroup/fetchGradeGroup/",
        method:"POST",
        beforeSend: function() {
          $('#evaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success:function(data){
          $('#evaluationData').html(data);
        }
      })
    }
    $(document).on('click', '.deletGroupGrade', function() {
      var divname = $(this).attr("value");
      swal({
        title: 'Are you sure?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          swal('Grade Group deleted successfully!', {
            icon: 'success',
          });
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>gradegroup/deleteGradeGroup/",
            data: ({
              divname :divname
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
    $(document).on('click', '.seen_noti', function() {
      $('.count-new-notification').html('');
      inbox_unseen_notification('yes');
    });
    $(document).on('click', '.seen', function() {
      $('.count-new-inbox').html('');
      inbox_unseen_notification('yes');
    });
    setInterval(function() {
      unseen_notification();
      inbox_unseen_notification();
    }, 5000);
  });
</script>
</body>

</html>