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
                <h5 class="header-title">Performance Status Page
                </h5>
              </div>
              <ul class="nav nav-tabs" id="myTab2" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#onoffWeek" role="tab" aria-selected="true">On/Off Week</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="home-tab2" data-toggle="tab" href="#performanceProgress" role="tab" aria-selected="false">Performance Progress</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="home-tab3" data-toggle="tab" href="#teachersStatus" role="tab" aria-selected="false">Teachers Status</a>
                </li>
              </ul>
            </div>
            <div class="tab-content tab-bordered" id="myTab3Content">
              <div class="tab-pane fade show active StudentViewTextInfo" id="onoffWeek" role="tabpanel" aria-labelledby="home-tab1">
                <div class="card">
                  <div class="fetch_performance_onoff" id="fetch_performance_onoff"></div>
                </div>                
              </div>
              <div class="tab-pane fade show" id="performanceProgress" role="tabpanel" aria-labelledby="home-tab2">
                <!-- <div class="row">
                  <div class="col-md-2 col-lg-2 col-6 form-group">
                    <select class="form-control selectperGroup" id="selectperGroup" name="selectperGroup">
                      <option>---Select Group---</option>
                      <?php foreach($perGroup as $perGroups){ ?>
                      <option value="<?php echo $perGroups->tid; ?>"><?php echo $perGroups->pername; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-md-4 col-lg-4 col-6 form-group">
                    <input type="text" name="customActivitiesName" id="customActivitiesName" class="form-control" placeholder="Custom performance activities here...">
                  </div>
                  <div class="col-md-2 col-lg-2 col-6 form-group">
                    <input type="number" name="customActivitiesPercent" id="customActivitiesPercent" class="form-control" placeholder="Weight...">
                  </div>
                  <div class="col-md-2 col-lg-2 col-6 form-group">
                     <select class="form-control activity_for" id="activity_for" name="activity_for">
                      <option>---Activity For---</option>
                      <?php foreach($usertype->result() as $usertypes){ ?>
                      <option value="<?php echo $usertypes->uname; ?>"><?php echo $usertypes->uname; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-md-2 col-lg-2 col-12 form-group">
                    <button class="btn btn-primary btn-block saveActivitiesText" id="saveActivitiesText">Save Activity</button>
                  </div>
                </div> -->
                <div class="fetchCustomActivity">Staff Progress</div>
              </div>
              <div class="tab-pane fade show" id="teachersStatus" role="tabpanel" aria-labelledby="home-tab3">
                <div class="row">
                  <div class="col-lg-3 col-6">
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
                  <div class="col-lg-3 col-6">
                    <div class="form-group">
                      <select class="form-control selectric" required="required" name="activityBranch" id="activityBranch">
                        <option> --- Select Branch --- </option>
                        <?php foreach($branch as $branchs){ ?>
                        <option value="<?php echo $branchs->name;?>">
                          <?php echo $branchs->name;?>
                        </option>
                        <?php }?>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="form-group">
                      <select class="form-control selectric" required="required" name="activity_week" id="activity_week">
                        <option> --- Select Week --- </option>
                        <?php foreach($week as $weeks){ ?>
                        <option value="<?php echo $weeks->week_name;?>">
                          <?php echo $weeks->week_name;?>
                        </option>
                        <?php }?>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <button class="btn btn-primary btn-block" type="submit" id="fetchStaffToEditActivity" name="fetchStaffToEditActivity"> View Staff</button>
                  </div>
                </div>
                <div class="fetchTeacherActivity"></div>
              </div>
            </div>
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
    $(document).ready(function(){
      $('#fetchStaffToEditActivity').on('click', function(event) {
        event.preventDefault();
        var activityYear=$('#activityYear').val();
        var activityBranch=$('#activityBranch').val();
        var activity_week=$('#activity_week').val();
        if($('#activityBranch').val() =='' || $('#activity_week').val()=='--- Select Week ---')
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
          url: "<?php echo base_url(); ?>performancestatus/fetch_signed_nonsigned_staffs/",
          data: ({
            activityYear: activityYear,
            activityBranch:activityBranch,
            activity_week:activity_week
          }),
          beforeSend: function() {
            $('.fetchTeacherActivity').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
          },
          cache: false,
          success: function(html){
            $('.fetchTeacherActivity').html(html);
          }
        });
      }
    });
  });
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>performancestatus/fetch_performance_onoff/",
        method:"POST",
        beforeSend: function() {
          $('#fetch_performance_onoff').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success:function(data){
          $('#fetch_performance_onoff').html(data);
        }
      })
    }
    $(document).on('click', "input[name='onoff_their_result']", function() {
      var lockmark=$(this).attr("value");
      var per_week=$(this).attr("id");
      var user_division=$(this).attr("title");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>teachersperformance/saveLockMarkAuto/",
          method:"POST",
          data:({
            lockmark:lockmark,
            per_week:per_week,
            user_division:user_division
          }),
          success: function(){
            iziToast.success({
              title: 'Status enabled successfully.',
              message: '',
              position: 'topRight'
            });
          }
        });
      }else{
      var lockmark=$(this).attr("value");
      var per_week=$(this).attr("id");
        $.ajax({
          url:"<?php echo base_url() ?>teachersperformance/deleteLockMarkAuto/",
          method:"POST",
          data:({
             lockmark:lockmark,
             per_week:per_week,
             user_division:user_division
          }),
          success: function(){
            iziToast.success({
              title: 'Status disabled successfully.',
              message: '',
              position: 'topRight'
            });
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