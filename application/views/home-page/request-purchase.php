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
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <button type="submit" id="add_new_purchase_request" name="add_new_purchase_request" class="card bg-primary btn-md pull-right" data-toggle="modal" data-target="#new-purchase-request"> New Purchase Request<i class="fas fa-plus"></i> </button>
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#purchase-request-history" role="tab" aria-selected="false">Purchase request history</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="purchase-request-history" role="tabpanel" aria-labelledby="home-tab2">
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
  <div class="modal fade" id="new-purchase-request" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="msg_item" id="msg_item">Purchase request form</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <form class="submit_new_purchase_request" method="POST">
            <div class="row">
              <div class="col-lg-12 col-12">
                <div class="form-group">
                  <input type="text" name="purchase_request_description" id="purchase_request_description" class="form-control" placeholder="Type purchase description..." required>
                </div>
              </div>
              <div class="col-lg-4 col-6">
                <div class="form-group text-danger">
                  <input type="text" name="purchaseUnit" id="purchaseUnit" class="form-control" placeholder="Type Unit" required>
                </div>
              </div>
              <div class="col-lg-4 col-6">
                <div class="form-group text-danger">
                  <input type="number" name="purchaseQuantity" id="purchaseQuantity" class="form-control" placeholder="Type quantity..." required>
                </div>
              </div>
              <div class="col-lg-4 col-6">
                <div class="form-group text-danger">
                  <input type="number" name="purchaseUnitPrice" id="purchaseUnitPrice" class="form-control" step="any" placeholder="Type unit price..." required>
                </div>
                <div class="calculate_amount"></div>
              </div>
              <div class="col-lg-12 col-12">
                <div class="form-group">
                  <textarea rows="4" cols="50" wrap="physical" name="purchase_request_remark" id="purchase_request_remark" placeholder="Justification/Remark" style="width:100%; height:100px;" required></textarea>
                </div>
              </div>
              <div class="col-lg-12 col-12">
                <div class="form-group">
                  <div class="pretty p-icon p-smooth">
                    <input type="radio" name="PurchaseType" class="PurchaseType" id="PurchaseType" value="International Purchase" >
                    <div class="state p-success">
                    <i class="icon fa fa-check"></i>
                        <label></label>International Purchase
                    </div>
                  </div>
                  <div class="pretty p-icon p-smooth">
                    <input type="radio" name="PurchaseType" class="PurchaseType" id="PurchaseType" value="Local Purchase" >
                    <div class="state p-success">
                    <i class="icon fa fa-check"></i>
                        <label></label>Local Purchase
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12 col-12">
                <div class="form-group text-center">
                  <div class="pretty p-icon p-smooth">
                    <input type="radio" name="freightType" class="freightType" id="freightType" value="Air Freight" >
                    <div class="state p-success">
                    <i class="icon fa fa-check"></i>
                        <label></label>Air Freight
                    </div>
                  </div>
                  <div class="pretty p-icon p-smooth">
                    <input type="radio" name="freightType" class="freightType" id="freightType" value="Sea Freight" >
                    <div class="state p-success">
                    <i class="icon fa fa-check"></i>
                        <label></label>Sea Freight
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12 col-12">
                <div class="form-group">
                 <button class="btn btn-primary pull-right" type="submit" id="submitPurchaseRequest">Submit Request</button>
                </div>
              </div>
            </div>
          </form>

          <div class="item_registration_here"></div>
        </div>
      </div>
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
    $('#purchaseUnitPrice').on("keyup",function() {
      var unitPrice=$('#purchaseUnitPrice').val();
      var itemQuantity=$('#purchaseQuantity').val();
      var Amount= unitPrice * itemQuantity;
      Amount=parseFloat(Amount.toFixed(2));
      $.ajax({
        beforeSend: function() {
          $('.calculate_amount').html( 'Calculating<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function() {
          $(".calculate_amount").html('<span class="text-success"><i class="fas fa-check"> Amount:' + Amount + '</i></span>');
        }
      });
    });
  });
  $(document).ready(function() { 
    $('#purchaseQuantity').on("keyup",function() {
      var unitPrice=$('#purchaseUnitPrice').val();
      var itemQuantity=$('#purchaseQuantity').val();
      var Amount=unitPrice * itemQuantity;
      Amount=parseFloat(Amount.toFixed(2));
      $.ajax({
        beforeSend: function() {
          $('.calculate_amount').html( 'Calculating<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function() {
          $(".calculate_amount").html('<span class="text-success"><i class="fas fa-check"> Amount:' + Amount + '</i></span>');
        }
      });
    });
  });
  load_data();
  function load_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>requestpurchase/fetch_myrequested_purchase/",
      method:"POST",
      beforeSend: function() {
        $('.myrequesteditem').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.myrequesteditem').html(data);
      }
    })
  }
  $(document).on('submit', '.submit_new_purchase_request', function(e) {
    e.preventDefault();
    if ($("#PurchaseType:checked").val() && $("#freightType:checked").val()) {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>requestpurchase/submit_request/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        beforeSend: function() {
          $('#submitPurchaseRequest').attr('disabled','disabled');
          $('#submitPurchaseRequest').html( 'Submiting...' );
        },
        success: function(data){
          if(data=='1'){
            iziToast.success({
              title: 'Request submitted successfully.',
              message: '',
              position: 'topRight'
            });
            $(".submit_new_purchase_request")[0].reset();
            load_data();
          }else if(data=='2'){
            iziToast.error({
              title: 'Request found.',
              message: '',
              position: 'topRight'
            });
          }else{
            iziToast.error({
              title: 'Ooops please try later.',
              message: '',
              position: 'topRight'
            });
          }
          $('#submitPurchaseRequest').removeAttr('disabled');
          $('#submitPurchaseRequest').html( 'Submit Request' );
        }
      });
    }else{
      swal('Please select Purchase and Freight!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '#delete_this_purchase_request', function() {
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
          url: "<?php echo base_url(); ?>requestpurchase/delete_request_item/",
          data: ({
            requestid: requestid
          }),
          cache: false,
          beforeSend: function() {
            $('#deletingPurchaseRequest' + requestid).html( 'Deleting<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(html){
            $('#deletingPurchaseRequest' + requestid).fadeOut('slow');
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

</html>