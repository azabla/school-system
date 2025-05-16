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
  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
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
                <h5 class="header-title">My Performance</h5>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-6">
                <div class="form-group">
                  <select class="form-control selectric" required="required"  name="activityYear" id="activityYear">
                    <?php foreach($academicyear as $academicyears){ ?>
                      <option value="<?php echo $academicyears->year_name;?>">
                      <?php echo $academicyears->year_name;?>
                      </option>
                    <?php }?>
                  </select>
                </div>
              </div>
              <div class="col-lg-4 col-6">
                <div class="form-group">
                  <select class="form-control selectric" required="required" name="activity_week" id="activity_week">
                    <option> --- Select Week --- </option>
                    <?php foreach($week as $weeks){ ?>
                    <option value="<?php echo $weeks->permission_week;?>">
                      <?php echo $weeks->permission_week;?>
                    </option>
                    <?php }?>
                  </select>
                </div>
              </div>
              <div class="col-lg-4 col-12">
                <button class="btn btn-primary btn-block" type="submit" id="fetchStaffToEditActivity" name="fetchStaffToEditActivity"> View Result</button>
              </div>
            </div>
            <div class="viewTeacherActivity" id="viewTeacherActivity"></div>
          </div>
        </section>
      </div>
       <?php include('footer.php'); ?>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script type="text/javascript">
    $(document).on('click', "input[name='agreedToResult']", function() {
      var agreed_week = $(this).attr("value");
      var teaid = $(this).attr("title");
      if($(this).is(':checked')){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>myperformance/sign_agreement/",
          data: ({
            agreed_week:agreed_week,
            teaid:teaid
          }),
          cache: false,
          success: function(html) {
            $(".agreedToResult"+agreed_week).prop('disabled', true);
            iziToast.success({
              title: 'Signed Successfully',
              message: '',
              position: 'topRight'
            });
          }
        });
      }
    });
    $(document).ready(function(){
      $('#fetchStaffToEditActivity').on('click', function(event) {
        event.preventDefault();
        var activityYear=$('#activityYear').val();
        var activity_week=$('#activity_week').val();
        if($('#activity_week').val() =='--- Select Week ---')
        {
          swal({
            title: 'Oooops, Please select necessary fields.',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
        }else{
          $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>myperformance/loadMyPerformance/",
          data: ({
            activityYear: activityYear,
            activity_week:activity_week
          }),
          beforeSend: function() {
            $('.viewTeacherActivity').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
          },
          cache: false,
          success: function(html){
            $('.viewTeacherActivity').html(html);
          }
        });
      }
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