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
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'> 
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
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button type="submit" name="addnew" class="card bg-info btn-sm pull-right" data-toggle="modal" data-target="#newcategory" > Add Category<i class="fas fa-plus"></i>
                        </button> 
                      </div>
                    </div>                  
                    <div class="dropdown-divider"></div>
                    <div class="table-responsive">
                      <table class="display dataTable" id='categoryTableGS' style="width:100%;">
                        <thead>
                         <tr>
                          <th>Category ID</th>
                           <th>Category Name</th>                           
                           <th>Category Head</th>
                           <th>Date Created</th>
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
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="newcategory" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="msg" id="msg">New stock category </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <form method="POST" id="saveNewCategory" class="saveNewCategory" name="saveNewCategory">
            <div class="form-group">
              <div class="search-element">
                <div class="row">
                  <div class="form-group col-lg-4 col-6">
                    <input id="category_id" type="text" class="form-control" required="required" name="category_id" placeholder="Category ID...">
                    <span class="text-danger">
                      <?php echo form_error('last_name'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-4 col-6">
                    <input id="category_name" type="text" class="form-control" required="required" placeholder="Category name..." name="category_name"autofocus>
                    <span class="text-danger"> 
                      <?php echo form_error('frist_name'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-4 col-6">
                    <select class="form-control" id="category_owner" name="category_owner" required>
                      <option>---Category Head---</option>
                      <?php foreach($staffs as $staff) { ?>
                      <option value="<?php echo $staff->username;?>"><?php echo $staff->fname.' '.$staff->mname;echo '('; echo $staff->username;echo ')'; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group col-lg-12 col-6">
                    <button class="btn btn-primary pull-right" name="savenewcategory" id="savenewcategory">Save Category
                    </button>
                  </div>
                  
                  </div>
                </div>
              </div>
            </form>
          </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="category_editingplace__here" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="edit_msg_category" id="edit_msg_category">Edit stock Category </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="category_editingplace__here"></div>
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
    $('#categoryTableGS').DataTable({
      'processing': true,
      'serverSide': true,
      "dataType": "json",
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>Stockcategory/view_saved_category/'
      },
      'columns': [
        { data: 'category_id' },
         { data: 'category_name' },
         { data: 'owner_category' },
         { data: 'date_created' },
        { data: 'Action' },
      ]
    });
  }); 
  $(document).on('submit', '#saveNewCategory', function(e) {
    e.preventDefault();
    if ($('#category_name').val() != '' && $('#category_id').val() != '' ) {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Stockcategory/insert_category/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#msg').html( '<span class="text-info">Saving...</span>');
          $('#savenewcategory').attr('disabled','disabled');
        },
        success: function(html){
          $("#msg").html(html);
          $('#savenewcategory').removeAttr('disabled');
          $("#saveNewCategory")[0].reset();
          $('#categoryTableGS').DataTable().ajax.reload();
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '#deleteThisCategoryName', function() {
    swal({
      title: 'Are you sure?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        var category_name=$(this).attr("value");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Stockcategory/delete_stock_category/",
          data: ({
            category_name: category_name
          }),
          cache: false,
          beforeSend: function() {
            $('.deleted_stock_category' + category_name).html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(html){
            $('.deleted_stock_category' + category_name).fadeOut('slow');
            $('#categoryTableGS').DataTable().ajax.reload();
          }
        }); 
      }
    });
  });
  $(document).on('click', '#editThisCategoryName', function() {
    var category_name=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Stockcategory/edit_category_name/",
      data: ({
        category_name: category_name
      }),
      cache: false,
      beforeSend: function() {
        $('.category_editingplace__here').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.category_editingplace__here').html(html);
      }
    }); 
  });
  $(document).on('submit', '#submiteditedCategoryName', function(e) {
    e.preventDefault();
    if ($('#editedCategoryID').val() != '' && $('#editedCategoryName').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Stockcategory/saveEditedCategory/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#view_saved_category_here').html( '<span class="text-info">Updating...</span>');
        },
        success: function(html){
          iziToast.success({
            title: html,
            message: '',
            position: 'topRight'
          });
          $('#category_editingplace__here').modal('hide');
          $('#edit_msg_category').html( html);
          $('#categoryTableGS').DataTable().ajax.reload();
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