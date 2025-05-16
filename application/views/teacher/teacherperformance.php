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
                <h5 class="header-title">Teacher's Performance Page</h5>
              </div>
            </div>  
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
              <!-- <li class="nav-item">
                <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#CustomPerformanceGroup" role="tab" aria-selected="true"> Performance Group</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab2" data-toggle="tab" href="#CustomPerformanceActivities" role="tab" aria-selected="false">Performance Activities</a>
              </li> -->
              <li class="nav-item">
                <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#EditTeacherPerformance" role="tab" aria-selected="false">Teacher's Performance</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab4" data-toggle="tab" href="#ViewTeacherPerformance" role="tab" aria-selected="false">Performance Report</a>
              </li>
            </ul>
            <div class="tab-content tab-bordered" id="myTab3Content">
              <!-- <div class="tab-pane fade show active" id="CustomPerformanceGroup" role="tabpanel" aria-labelledby="home-tab1">
                <div class="row">
                  <div class="col-md-5 col-lg-5 col-6 form-group">
                    <input type="text" name="customGroupName" id="customGroupName" class="form-control" placeholder="Custom performance group...">
                  </div>
                  <div class="col-md-4 col-lg-4 col-6 form-group">
                    <input type="number" name="customGroupWeight" id="customGroupWeight" class="form-control" placeholder="Group Weight...">
                  </div>
                  <div class="col-md-3 col-lg-3 col-12 form-group">
                    <button class="btn btn-primary btn-block saveGroupText" id="saveGroupText">Save group</button>
                  </div>
                </div>
                <div class="fetchCustomGroup"></div>
              </div> -->
              <!-- <div class="tab-pane fade show" id="CustomPerformanceActivities" role="tabpanel" aria-labelledby="home-tab2">
                <div class="row">
                  <div class="col-md-5 col-lg-5 col-6 form-group">
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
                  <div class="col-md-3 col-lg-3 col-12 form-group">
                    <button class="btn btn-primary btn-block saveActivitiesText" id="saveActivitiesText">Save Activity</button>
                  </div>
                </div>
                <div class="fetchCustomActivity"></div>
              </div> -->
              <div class="tab-pane fade show active" id="EditTeacherPerformance" role="tabpanel" aria-labelledby="home-tab3">
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
                      <select class="form-control selectric" required="required" name="activity_staffs" id="activity_staffs">
                        <option> --- Select Staffs --- </option>
                        <?php foreach($my_staffs as $staff){ ?>
                        <option value="<?php echo $staff->id;?>">
                          <?php echo $staff->fname;?> <?php echo $staff->mname;?>
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
              <div class="tab-pane fade show" id="ViewTeacherPerformance" role="tabpanel" aria-labelledby="home-tab4">
                <div class="row">
                  <div class="col-lg-6 col-6">
                  </div>
                  <div class="col-lg-6 col-6">
                    <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                      <i data-feather="printer"></i>
                    </button>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-6">
                    <div class="form-group">
                      <select class="form-control selectric" required="required"  name="activityViewYear" id="activityViewYear">
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
                      <select class="form-control selectric" required="required" name="activityViewStaffs" id="activityViewStaffs">
                        <option> --- Select Staffs --- </option>
                        <?php foreach($my_staffs as $staff){ ?>
                        <option value="<?php echo $staff->id;?>">
                          <?php echo $staff->fname;?> <?php echo $staff->mname;?>
                        </option>
                        <?php }?>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="form-group">
                      <select class="form-control selectric" required="required" name="activityViewWeek" id="activityViewWeek">
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
                    <button class="btn btn-primary btn-block" type="submit" id="fetchStaffToViewActivity" name="fetchStaffToViewActivity"> View Result</button>
                  </div>
                </div>
                <div class="viewTeacherActivity" id="viewTeacherActivity" ></div>
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
  <script type="text/javascript">
    $(document).ready(function(){
      $('#fetchStaffToViewActivity').on('click', function(event) {
        event.preventDefault();
        var activityYear=$('#activityViewYear').val();
        var activityViewWeek=$('#activityViewWeek').val();
        var activityViewStaffs=$('#activityViewStaffs').val();
        if($('#activityBranch').val() =='')
        {
          alert("Oooops, Please select necessary fields.");
        }else{
          $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>teacherperformance/fetchStaffPerformResult/",
          data: ({
            activityYear: activityYear,
            activityViewWeek:activityViewWeek,
            activityViewStaffs:activityViewStaffs
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
  <script type="text/javascript">
    $(document).ready(function(){
      $('#fetchStaffToEditActivity').on('click', function(event) {
        event.preventDefault();
        var activityYear=$('#activityYear').val();
        var activity_week=$('#activity_week').val();
        var activity_staffs=$('#activity_staffs').val();
        if($('#activity_staffs').val() =='--- Select Staffs ---')
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
          url: "<?php echo base_url(); ?>teacherperformance/fetchStaffToPerformActivity/",
          data: ({
            activityYear: activityYear,
            activity_week:activity_week,
            activity_staffs:activity_staffs
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
  </script>
  <script type="text/javascript">
    $(document).on('change', '.insertTeacherPerformance', function() {
      var value=$(this).find('option:selected').attr('value');
      var teid=$(this).find('option:selected').attr('id');
      var acname=$(this).find('option:selected').attr('class');
      var GroupName=$(this).find('option:selected').attr('name');
      var activity_week=$(this).find('option:selected').attr('title');
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>teacherperformance/updateTeacherPerformance/",
        data: ({
          value:value,
          teid:teid,
          acname:acname,
          activity_week:activity_week,
          GroupName:GroupName
        }),
        success: function(data) {
          $('#TotalGivenResults'+GroupName + teid).html(data);
          iziToast.success({
            title: 'Teacher Performance Updated',
            message: '',
            position: 'topRight'
          });
        }
      });
    });
  </script>
  <script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("viewTeacherActivity");
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