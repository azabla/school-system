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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel='shortcut icon' type='image/x-icon'
   href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
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
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#request-item" role="tab" aria-selected="true"> Request Item</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#request-history" role="tab" aria-selected="false">Request History</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="request-item" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="dropdown-divider"></div>
                        <div class="row">
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                              <select class="form-control" required="required" name="item_option" id="item_option">
                                <option>Select Item</option>
                                <?php foreach($item_list as $item_lists){ ?>
                                  <option value="<?php echo $item_lists->item_id; ?>"><?php echo $item_lists->item_name; ?> (<small><?php echo $item_lists->item_id; ?></small>)-<span class="text-danger"><?php echo $item_lists->item_quantity; ?></span></option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                              <input type="text" name="itemQuantity" id="itemQuantity" class="form-control" placeholder="Type quantity..." required>
                              <span class="validate_num_rows"></span>
                            </div>
                          </div>
                          <div class="col-lg-4 col-12">
                            <div class="form-group">
                             <button class="btn btn-primary btn-block" type="submit" id="submitItemQuantity">Submit Request</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade show" id="request-history" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="dropdown-divider"></div>
                        <div class="myrequesteditem"></div>
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
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function() { 
      $('#itemQuantity').on("keyup",function() {
        var item_option=$('#item_option').val();
        var itemQuantity=parseInt($('#itemQuantity').val());
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>Myitemrequest/validate_item_quantity/",
          data: ({
            item_option: item_option,
            itemQuantity:itemQuantity
          }),
          beforeSend: function() {
            $('.validate_num_rows').html( 'Checking...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
          },
          success: function(data) {
            if(data >= itemQuantity){
              $(".validate_num_rows").html('<span class="text-success"><i class="fas fa-check"> </i></span>');
              $('#submitItemQuantity').removeAttr('disabled');
            }else{
              $(".validate_num_rows").html('<span class="text-danger"><i class="fas fa-times"> </i></span>');
              $('#submitItemQuantity').attr('disabled','disabled');
            }
          }
        });
      });
    });
  $(document).ready(function() {  
    $("#item_option").bind("change", function() {
      $('#itemQuantity').val('');
      var item_option=$('#item_option').val();
      var itemQuantity=parseInt($('#itemQuantity').val());
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Myitemrequest/validate_item_quantity/",
        data: ({
          item_option: item_option,
          itemQuantity:itemQuantity
        }),
        beforeSend: function() {
          $('.validate_num_rows').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $(".validate_num_rows").html(data);
        }
      });
    });
  });
  load_data();
  function load_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>Myitemrequest/fetch_myrequested_item/",
      method:"POST",
      beforeSend: function() {
        $('.myrequesteditem').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.myrequesteditem').html(data);
      }
    })
  }
  $(document).on('click', '#submitItemQuantity', function() {
    var item_option=$('#item_option').val();
    var itemQuantity=parseInt($('#itemQuantity').val());
    if($('#item_option').val()!='Select Item' && $('#itemQuantity').val()!=''){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Myitemrequest/submit_request/",
        data: ({
          item_option: item_option,
          itemQuantity:itemQuantity
        }),
        cache: false,
        beforeSend: function() {
          $('#submitItemQuantity').attr('disabled','disabled');
          $('#submitItemQuantity').html( 'Submiting...' );
        },
        success: function(data){
          iziToast.success({
            title: data,
            message: '',
            position: 'topRight'
          });
          $('#submitItemQuantity').removeAttr('disabled');
          $('#submitItemQuantity').html( 'Submit Request' );
          $('#item_option').val('');
          $('#itemQuantity').val('');
          $('.validate_num_rows').html( '' );
          load_data();
        }
      });
    } 
  });
  $(document).on('click', '#delete_this_myrequest', function() {
    swal({
      title: 'Are you sure?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        var requestid=$(this).attr("value");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Myitemrequest/delete_request_item/",
          data: ({
            requestid: requestid
          }),
          cache: false,
          beforeSend: function() {
            $('#deletingStock' + requestid).html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(html){
            $('#deletingStock' + requestid).fadeOut('slow');
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