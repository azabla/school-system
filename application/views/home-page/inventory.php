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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'> 
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
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button type="submit" id="add_new_item" name="add_new_item" class="card bg-primary btn-sm pull-right" data-toggle="modal" data-target="#newItem"> Add Item<i class="fas fa-plus"></i> </button>
                      </div>
                    </div>                  
                    <div class="table-responsive" id="view_saved_category_here">
                      <table class="display dataTable" id='empTableGS' style="width:100%;">
                        <thead>
                         <tr>
                          <th>Item ID</th>
                           <th>Item Name</th>                           
                           <th>Item Category</th>
                           <th>Item Type/Color</th>                           
                           <th>Service Type</th>
                           <th>Stock</th>
                           <th>Unit Price</th>
                           <th>Total Price</th>
                           <th>Expiry Date</th>
                           <th>Item Branch</th>
                          </tr>
                        </thead>
                      </table> 
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
  <div class="modal fade" id="newItem" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="msg_item" id="msg_item">New stock Item </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="item_registration_here"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="item_editingplace__here" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="edit_msg_item" id="edit_msg_item">Edit stock Item </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="item_editingplace__here"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- General JS Scripts -->
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
        'url':'<?=base_url()?>inventory/fetch_item_history/'
      },
      'columns': [
        { data: 'item_id' },
         { data: 'item_name' },
         { data: 'item_category' },
         { data: 'item_type_color' },
         { data: 'item_service' },
         { data: 'item_quantity' },
         { data: 'item_price' },
         { data: 'total_price' },
        { data: 'item_expiry' },
        { data: 'item_branch' },
      ]
    });
  }); 
  $(document).on('click', '#editThisItemName', function() {
    var stockid=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>inventory/edit_item_name/",
      data: ({
        stockid: stockid
      }),
      cache: false,
      beforeSend: function() {
        $('.item_editingplace__here').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.item_editingplace__here').html(html);
      }
    }); 
  });
  $(document).on('submit', '#submiteditedItemName', function(e) {
    e.preventDefault();
    if ($('#editeditem_id').val() != '' && $('#editeditem_name').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>inventory/saveEditedItem/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#edit_msg_item').html( '<span class="text-info">Updating...</span>');
        },
        success: function(html){
          iziToast.success({
            title: html,
            message: '',
            position: 'topRight'
          });
          $('#item_editingplace__here').modal('hide');
          $('#edit_msg_item').html( html);
          $('#empTableGS').DataTable().ajax.reload();
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '#deleteThisItemName', function() {
    swal({
      title: 'Are you sure you want to delete?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        var stockid=$(this).attr("value");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>inventory/delete_stock_item/",
          data: ({
            stockid: stockid
          }),
          cache: false,
          beforeSend: function() {
            $('.deleted_stock_item' + stockid).html( 'Deleting<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(html){
            $('.deleted_stock_item' + stockid).fadeOut('slow');
            $('#empTableGS').DataTable().ajax.reload();
          }
        }); 
      }
    });
  });
  $(document).on('click', '#add_new_item', function()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>inventory/fetch_form_toadd_new_item/",
      method:"POST",
      beforeSend: function() {
        $('.item_registration_here').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.item_registration_here').html(data);
      }
    })
  });
  $(document).on('submit', '#saveNew_categoryItem', function(e) {
    e.preventDefault();
    if ($('#item_category').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>inventory/insert_item/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#msg_item').html( '<span class="text-info">Saving...</span>');
          $('#savenewitem').attr('disabled','disabled');
        },
        success: function(html){
          $("#msg_item").html(html);
          $('#savenewitem').removeAttr('disabled');
          $("#saveNew_categoryItem")[0].reset();
          $('#empTableGS').DataTable().ajax.reload();
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
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