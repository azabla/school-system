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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
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
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#registrationRequest" role="tab" aria-selected="true">Registration Request</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#rejectedRegistration" role="tab" aria-selected="false">Rejected Registration</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="registrationRequest" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="new_staffs"></div>
                      </div>
                      <div class="tab-pane fade show" id="rejectedRegistration" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="table-responsive">
                          <table class="display dataTable" id='empTableGS' style="width:100%;">
                            <thead>
                              <tr>
                                <th>Full Name</th>   
                                <th>Gender</th>                        
                                <th>Grade</th>                         
                                <th>Branch</th>
                                <th>Mobile</th>
                                <th>Rejected By</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                          </table> 
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
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function(){
    $('#empTableGS').DataTable({
      'processing': true,
      'serverSide': true,
      "dataType": "json",
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>Newstaffs/fetch_rejected_registration/'
      },
      'columns': [
        { data: 'fname' },
        { data: 'gender' },
        { data: 'grade' },
        { data: 'branch' },
        { data: 'mobile' },
        { data: 'status2_by' },
        { data: 'Action' },
      ]
    });
  });
  $(document).on('click', '.reaccept_this_registration', function() {
    var stuid=$(this).attr("id");
    swal({
      title: 'Are you sure you want to Approve this request?',
      text: 'Request detail will be send to finance department',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) { 
        $.ajax({
          url: "<?php echo base_url(); ?>Newstaffs/re_accept_registration_request/",
          method: "POST",
          data: ({
            stuid: stuid
          }),
          cache: false,
          success: function(data) {
            if(data==='1'){
              iziToast.success({
                title: 'Request submitted to finance department.',
                message: '',
                position: 'topRight'
              });
            }else{
              iziToast.error({
                title: 'Something wrong, please try again.',
                message: '',
                position: 'topRight'
              });
            }
            $('#empTableGS').DataTable().ajax.reload();
          }
        });
      }
    });
  });
  $(document).on('click', '.redelete_this_registration', function() {
    var decline_stuid=$(this).attr("id");
    swal({
      title: 'Are you sure you want to Delete this Request?',
      text: 'Once You Delete you can not recover this information!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {    
        $.ajax({
          url: "<?php echo base_url(); ?>Newstaffs/delete_registration_request/",
          method: "POST",
          data: ({
            decline_stuid: decline_stuid
          }),
          cache: false,
          success: function(html) {
            $('#empTableGS').DataTable().ajax.reload();
            if(html=='1'){
              iziToast.success({
                title: 'Request deleted successfully.',
                message: '',
                position: 'topRight'
              });
            }else{
              iziToast.error({
                title: 'Something wrong, please try again.',
                message: '',
                position: 'topRight'
              });
            }
          }
        });
      }
    });
  });
  $(document).on('click', '#deleteAllStaffRegistration', function() {
    var stuid=$(this).attr("id");
    swal({
      title: 'Are you sure you want to Delete All Registration Request?',
      text: 'Once deleted you can not recover this information',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) { 
        $.ajax({
          url: "<?php echo base_url(); ?>Newstaffs/delete_all/",
          method: "POST",
          beforeSend: function() {
            $('.new_staffs').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
          },
          cache: false,
          success: function(data) {
            $(".new_staffs").fadeOut('slow');
            if(data==='1'){
              iziToast.success({
                title: 'All request deleted successfully.',
                message: '',
                position: 'topRight'
              });
            }else{
              iziToast.error({
                title: 'Something wrong, please try again.',
                message: '',
                position: 'topRight'
              });
            }
          }
        });
      }
    });
  });
  $(document).on('click', '#declineAllStaffRegistration', function() {
    var stuid=$(this).attr("id");
    swal({
      title: 'Are you sure you want to Reject All Registration Request?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) { 
        $.ajax({
          url: "<?php echo base_url(); ?>Newstaffs/decline_all/",
          method: "POST",
          beforeSend: function() {
            $('.new_staffs').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
          },
          cache: false,
          success: function(data) {
            $(".new_staffs").fadeOut('slow');
            if(data==='1'){
              iziToast.success({
                title: 'All request rejected successfully.',
                message: '',
                position: 'topRight'
              });
            }else{
              iziToast.error({
                title: 'Something wrong, please try again.',
                message: '',
                position: 'topRight'
              });
            }
          }
        });
      }
    });
  });
  $(document).on('click', '.accept', function() {
    var stuid=$(this).attr("id");
    swal({
      title: 'Are you sure you want to Approve this request?',
      text: 'Request detail will be send to finance department',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) { 
        $.ajax({
          url: "<?php echo base_url(); ?>Newstaffs/accept_registration_request/",
          method: "POST",
          data: ({
            stuid: stuid
          }),
          beforeSend: function() {
            $('.delete_mem').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
          },
          cache: false,
          success: function(data) {
            if(data==='1'){
              iziToast.success({
                title: 'Request submitted to finance department.',
                message: '',
                position: 'topRight'
              });
            }else{
              iziToast.error({
                title: 'Something wrong, please try again.',
                message: '',
                position: 'topRight'
              });
            }
            $(".delete_mem" + stuid).fadeOut('slow');
          }
        });
      }
    });
  });
  $(document).on('click', '.decline', function() {
    var decline_stuid=$(this).attr("id");
    swal({
      title: 'Are you sure you want to Reject this Request?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {    
        $.ajax({
          url: "<?php echo base_url(); ?>Newstaffs/reject_registration_request/",
          method: "POST",
          data: ({
            decline_stuid: decline_stuid
          }),
          beforeSend: function() {
            $('.delete_mem').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
          },
          cache: false,
          success: function(html) {
            $(".delete_mem" + decline_stuid).fadeOut('slow');
            if(html=='1'){
              iziToast.success({
                title: 'Request rejected successfully.',
                message: '',
                position: 'topRight'
              });
            }else{
              iziToast.error({
                title: 'Something wrong, please try again.',
                message: '',
                position: 'topRight'
              });
            }
          }
        });
      }
    });
  });
  $(document).on('click', '.delete_this_registrationRequest', function() {
    var decline_stuid=$(this).attr("id");
    swal({
      title: 'Are you sure you want to Delete this Request?',
      text: 'Once You Delete you can not recover this information!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {    
        $.ajax({
          url: "<?php echo base_url(); ?>Newstaffs/delete_registration_request/",
          method: "POST",
          data: ({
            decline_stuid: decline_stuid
          }),
          beforeSend: function() {
            $('.delete_mem').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
          },
          cache: false,
          success: function(html) {
            $(".delete_mem" + decline_stuid).fadeOut('slow');
            if(html=='1'){
              iziToast.success({
                title: 'Request deleted successfully.',
                message: '',
                position: 'topRight'
              });
            }else{
              iziToast.error({
                title: 'Something wrong, please try again.',
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
    function new_staff(view = '') { 
      $.ajax({
        url: "<?php echo base_url() ?>fetch_newstaffs/",
        method: "POST",
        data: ({
          view: view
        }),
        success: function(html) {
          $('.new_staffs').html(html);
        }
      });
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
    new_staff();
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
      new_staff();
      unseen_notification();
      inbox_unseen_notification();
    }, 5000);
  });
  </script>
</html>