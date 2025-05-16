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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
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
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#newGroup" role="tab" aria-selected="true"> New Group</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#viewGroup" role="tab" aria-selected="false">View Group</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="newGroup" role="tabpanel" aria-labelledby="home-tab1">
                      <form id="save_placement" method="POST"> 
                          <div class="row">
                            <div class="col-lg-2 col-6">
                              <label for="Mobile">Academic Year</label>
                              <select class="form-control selectric" required="required" name="academicyear" 
                                id="placaeacademicyear">
                              <?php foreach($academicyear as $academicyears){ ?>
                                <option><?php echo $academicyears->year_name ?></option>
                              <?php } ?>
                              </select>
                            </div>
                           <div class="col-lg-3 col-6">
                              <label for="Staff"> Select Group Head </label>
                             <select class="form-control selectric" required="required" name="staff" id="staffplacement">
                             <option></option>
                              <?php foreach($staffs as $staff) { ?>
                              <option value="<?php echo $staff->username;?>"><?php echo $staff->fname.' '.$staff->mname;echo '('; echo $staff->username;echo ')';
                              ?></option>
                            <?php }?>
                             </select>
                          </div>
                          <div class="col-lg-7 col-6 table-responsive" style="height: 50vh;">
                            <label for="staff_list">Select Staffs </label><br>
                            <div class="row">
                              <?php foreach($staffs as $staff) { ?>
                                <div class="col-lg-6 col-12">
                                <div class="pretty p-icon p-bigger">
                                    <input type="checkbox" name="staff_list" value="<?php echo $staff->username;?>" class="staff_list" id="customCheck1">
                                    <div class="state p-success">
                                      <i class="icon material-icons"></i>
                                      <label></label><?php  echo $staff->fname.' '.$staff->mname.' '.$staff->lname;;?>
                                    </div>
                                </div>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="col-lg-6 col-6"></div>
                          <div class="col-lg-6 col-12 pull-right">
                            <button type="submit" name="postplacement" class="card card-body bg-primary btn-block">Save Group </button>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="tab-pane fade show" id="viewGroup" role="tabpanel" aria-labelledby="home-tab2">
                      <div class="fetch_placement"></div>
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
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>staffgrouping/fetch_placement/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_placement').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">');
        },
        success:function(data){
          $('.fetch_placement').html(data);
        }
      })
    }
    $('#save_placement').on('submit', function(event) {
      event.preventDefault();
      var academicyear=$('#placaeacademicyear').val();
      var staff=$('#staffplacement').val();
      staff_list=[];
      $("input[name='staff_list']:checked").each(function(i){
        staff_list[i]=$(this).val();
      });
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>staffgrouping/post_placement/",
        data: ({
          academicyear:academicyear,
          staff:staff,
          staff_list:staff_list
        }),
        cache: false,
        success: function(html){
          $('#save_placement')[0].reset();
          load_data();
        }
      });
    
  });
  $(document).on('click', '#delete_groupPlacement', function()
  {
    var staff_list=$(this).attr("value");
    var staff_head=$(this).attr("name");
    swal({
      title: 'Are you susre you want to delete this Staff Group?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>staffgrouping/Delete_staff_group/",
          data: ({
            staff_list:staff_list,
            staff_head:staff_head
          }),
          cache: false,
          beforeSend: function() {
            $('.delete_this_staff_list').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
            );
          },
          success: function(html){
           $('.delete_this_staff_list' + staff_list+staff_head).fadeOut('slow');
          }
        });
      }
    });
  }); 
  $(document).on('click', '#delete_allStaffGroup', function()
  {
    var staffName=$(this).attr("value");
    swal({
      title: 'Are you susre you want to delete this Staff Group?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>staffgrouping/Delete_staffAll_group/",
          data: ({
            staffName:staffName
          }),
          cache: false,
          beforeSend: function() {
            $('.delete_staffplacement').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
            );
          },
          success: function(html){
           load_data();
          }
        });
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
</html>