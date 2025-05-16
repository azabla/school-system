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
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
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
              <div class="col-lg-12 col-md-12 col-12">
              <?php if($_SESSION['usertype']===trim('superAdmin')){ ?>
                <button type="submit" name="addNewLeavingReason" id="addNewLeavingReason" class="btn btn-secondary pull-right" data-toggle="modal" data-target="#addLeavingReason" > <i class="fas fa-plus"></i> Add Leaving Reason
                </button>
              <?php } ?>
                 <button type="submit" name="add_NewLeavingRequest" id="add_NewLeavingRequest" class="btn btn-info pull-right" data-toggle="modal" data-target="#addNewLeavingRequest" > <i class="fas fa-plus"></i> Add New Request
                </button>
              </div>
            </div>
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-12">
                    <div class="fetch_requested_form"> </div>
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
  <div class="modal fade" id="addLeavingReason" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Add leaving reason</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <form method="POST" id="saveNewLeavingReason" class="saveNewLeavingReason" name="saveNewLeavingReason">
            <div class="form-group">
              <div class="search-element">
                <div class="row">
                  <div class="form-group col-lg-12 col-12">
                    <label>Leaving reason name</label>
                    <input id="leavingReasonName" type="text" class="form-control" required="required" name="leavingReasonName" placeholder="Leaving reason name...">
                  </div>
                  <div class="form-group col-lg-12 col-12">
                    <button class="btn btn-primary pull-right" name="save_Leaving_Reason" id="save_Leaving_Reason"> Save Reason
                    </button>
                  </div>
                </div>
                <hr>
                <div class="msg_Leaving_Reason" id="msg_Leaving_Reason"></div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="addNewLeavingRequest" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Add leaving request</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="alert alert-light">
            Total Absent is <?php echo $totalAttendance ?>
            & Remaining leave days is <?php echo $leavingDays ?>
          </div>
          <?php if($leavingDays <=0){ ?>
          <div class="alert alert-light">
            Your don`t have remaining days to annual leave.
          </div>
          <?php } else{ ?>
          <form method="POST" id="submit_leaving_form">
            <input type="hidden" name="totalRemainig" id="totalRemainig" value="<?php echo $leavingDays ?>">
            <div class="row">
              <div class="col-md-4 col-lg-4 col-6">
                <div class="form-group">
                  <label>Reason for leave</label>
                  <select class="form-control" id="leaveType" required>
                    <option></option>
                    <?php foreach($leaveReason as $leaveReasons){ ?>
                      <option><?php echo $leaveReasons->reason_name ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-6">
                <div class="form-group">
                  <label>From Date</label>
                  <input type="date" name="fromDate" class="form-control" id="fromDate" required>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-6">
                <div class="form-group">
                  <label>To Date</label>
                  <input type="date" name="toDate" class="form-control" id="toDate" required>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-6">
                <div class="form-group">
                  <label>The day you return to work</label>
                  <input type="date" name="returnDate" class="form-control" id="returnDate" required> 
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-12">
                <div class="form-group">
                  <label>Emergency Mobile</label>
                  <input type="text" name="emergencyMobile" class="form-control" id="emergencyMobile" value="<?php echo $mobile ?>" required>
                </div>
              </div>
            </div>
            <div class="text-muted form-text">Notice:- Payment free license is requested by application when the applicant does not have an annual leaving license and faces majeure problem.
            </div>
            <div class="form-group mb-0 col-12">                                
              <input type="checkbox" name="agreedToAnnual_leavingRequest" class="" id="" title="" value="" required>
              <label>I agree and sign that i have requested this annual leaving form.</label>
            </div>
            <button class="btn btn-primary pull-right submitRequest" id="submitRequest">Submit Request</button>
          </form>
        <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/summernote/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script type="text/javascript">
    $(document).on('submit', '#saveNewLeavingReason', function(e) {
      e.preventDefault();
      if ($('#leavingReasonName').val() != '') {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Myannualrequest/save_new_leaving_reason/",
          data:new FormData(this),
          processData:false,
          contentType:false,
          cache: false,
          async:false,
          beforeSend: function() {
            $('#save_Leaving_Reason').html( 'Saving...');
            $('#save_Leaving_Reason').attr( 'disabled','disabled');
          },
          success: function(html){
            if(html=='1'){
              iziToast.success({
                title: 'Saved successfully',
                message: '',
                position: 'topRight'
              });
              $('#leavingReasonName').val('');
              load_Reason_data();
            }else{
              iziToast.error({
                title: 'Reason found.',
                message: '',
                position: 'topRight'
              });
            }
            $('#save_Leaving_Reason').html('Save Reason');
            $('#save_Leaving_Reason').removeAttr( 'disabled');
          }
        });
      }else{
        swal('Please fill all fields!', {
          icon: 'error',
        });
      }
    });
    $(document).on('click', '#addNewLeavingReason', function(e) {
      e.preventDefault();
      load_Reason_data();
    });
    function load_Reason_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Myannualrequest/fetch_leaving_reason/",
        method:"POST",
        beforeSend: function() {
          $('.msg_Leaving_Reason').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.msg_Leaving_Reason').html(data);
        }
      })
    }
    $(document).on('click', '#remove_leaving_reason', function(e) {
      e.preventDefault();
      var userid=$(this).attr("value");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Myannualrequest/removeBook_this_leaving_reason/",
        data: ({
          userid: userid
        }),
        beforeSend: function() {
          $('.remove_leaving_reason' + userid).html( '<span class="text-info">Removing...</span>');
          $('#remove_leaving_reason').attr( 'disabled','disabled');
          
        },
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Removed successfully',
              message: '',
              position: 'topRight'
            });
            load_Reason_data();
          }else{
            iziToast.error({
              title: 'Please try later',
              message: '',
              position: 'topRight'
            });
          }
          $('.remove_leaving_reason' + userid).html( 'Remove');
          $('#remove_leaving_reason').removeAttr( 'disabled');
        }
      });
    });
    fetch_requested_form();
    function fetch_requested_form()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Myannualrequest/fetch_myrequested_form/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_requested_form').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.fetch_requested_form').html(data);
        }
      })
    } 
    $('#submit_leaving_form').on('submit', function(event) {
      event.preventDefault();
      var LeaveType=$("#leaveType").val();
      var fromDate=$("#fromDate").val();
      var toDate=$("#toDate").val();
      var returnDate=$("#returnDate").val();
      var emergencyMobile=$("#emergencyMobile").val(); 
      var total_remainig=$("#totalRemainig").val();     
      if($("#leaveType").val()!='' && $("#fromDate").val()!=$("#toDate").val() && $("#returnDate").val() > $("#toDate").val() && $("#returnDate").val() > $("#fromDate").val()){
        var startDay = new Date(fromDate);
        var endDay = new Date(toDate);
        var millisBetween = startDay.getTime() - endDay.getTime();
        var days = millisBetween / (1000 * 3600 * 24);
        var totalRemaning=Math.round(Math.abs(days));
        if(totalRemaning > total_remainig){
          swal('You don`t have enough days for the selected range. Please adjust your leave days. ', {
            icon: 'error',
          });
        }else{
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Myannualrequest/submit_request/",
            data: ({
              LeaveType: LeaveType,
              fromDate:fromDate,
              toDate:toDate,
              returnDate:returnDate,
              emergencyMobile:emergencyMobile
            }),
            cache: false,
            beforeSend: function() {
              $('#submitRequest').html( 'Submitting...' );
              $('#submitRequest').attr('disabled','disabled');
            },
            success: function(html){
              if(html=='1'){
                iziToast.success({
                  title: 'Request sent successfully.',
                  message: '',
                  position: 'topRight'
                });
                $('#submitRequest').html( 'Submit Request' );
                $('#submitRequest').removeAttr('disabled');
                $("#submit_leaving_form")[0].reset();
                fetch_requested_form();
                $('#addNewLeavingRequest').modal('hide');
              }else if(html=='2'){
                iziToast.error({
                  title: 'Either request found or incorrect date.',
                  message: '',
                  position: 'topRight'
                });
                $('#submitRequest').html( 'Submit Request' );
                $('#submitRequest').removeAttr('disabled');
              }else{
                iziToast.error({
                  title: 'Oooops Please try again.',
                  message: '',
                  position: 'topRight'
                });
                $('#submitRequest').html( 'Submit Request' );
                $('#submitRequest').removeAttr('disabled');
              }
            }
          });
        }
      }else{
        swal('Please enter correct value! ', {
          icon: 'error',
        });
      }
    });
    $(document).on('click', '#cancelRequest', function(event) { 
      event.preventDefault();
      var id=$(this).attr("value");    
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Myannualrequest/delete_request/",
        data: ({
          id: id
        }),
        cache: false,
        beforeSend: function() {
          $('.cancelRequest' +id).html( 'Deleting...' );
          $('#cancelRequest').attr('disabled','disabled');
        },
        success: function(html){
          iziToast.success({
            title: html,
            message: '',
            position: 'topRight'
          });
          $('.cancelRequest' +id).html( 'Cancel Request');
          $('#cancelRequest').removeAttr('disabled');
          fetch_requested_form();
        }
      });
    });
  </script>
  <script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("printReturnedComBookNow");
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
    $(document).on('click', '.seenBook', function() {
        $('.count-new-comBook').html('');
        unseenComBook('yes');
    });
    $(document).on('click', '.seenReplyComBook', function() {
        $('.count-new-replayComBook').html('');
        unseenreplyComBbok('yes');
    });
    setInterval(function() {
      unseen_notification();
      inbox_unseen_notification();
    }, 5000);
    });
  </script>
</body>

</html>