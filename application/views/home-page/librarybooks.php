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
                        <button type="submit" id="dataExportExcelBooks" name="dataExportExcelBooks" value="Export to excel" class="btn btn-secondary btn-sm pull-right">Export To Excel <i class="fas fa-download"></i>
                        </button>
                        <button type="submit" id="add_new_book" name="add_new_book" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#newBook"> Add Book <i class="fas fa-plus"></i> </button>
                        <button type="submit" id="libray-head" name="libray-head" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#libray_head"> Library Head <i class="fas fa-user-plus"></i> </button>
                        <button type="submit" id="gs-custom-grade" name="gs-custom-grade" class="btn btn-warning btn-sm pull-right" data-toggle="modal" data-target="#add_custom_grade"> Add custom Grade <i class="fas fa-user-plus"></i> </button>
                        
                      </div>
                    </div>   
                    <div class="dropdown-divider"></div>               
                    <div class="table-responsive" id="view_saved_category_here">
                      <table class="display dataTable" id='empTableGS' style="width:100%;">
                        <thead>
                         <tr>
                          <th>Book ID</th>
                           <th>Book Name</th>                           
                           <th>Book Grade</th>
                           <th>Book Price</th>
                           <th>Stock</th>
                           <th>Branch</th>
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
  <div class="modal fade" id="add_custom_grade" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="library_head_book" id="library_head_book">Add custom Grade</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="library_head_registration_here"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="libray_head" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="library_head_book" id="library_head_book">Library Head</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="library_head_registration_here"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="newBook" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="msg_book" id="msg_book">New Book</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="book_registration_here"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="book_editingplace__here" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="edit_msg_book" id="edit_msg_book">Edit Book </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="book_editingplace__here"></div>
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
  $("#dataExportExcelBooks").click(function(e) {
  let file = new Blob([$('#view_saved_category_here').html()], {type:"application/vnd.ms-excel"});
  let url = URL.createObjectURL(file);
  let a = $("<a />", {
    href: url,
    download: "Library Books.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
  });
  function load_custom_grade_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>librarybooks/fetch_custom_grade_gs/",
      method:"POST",
      beforeSend: function() {
        $('.fetch_gs_custom_grade').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.fetch_gs_custom_grade').html(data);
      }
    })
  }
  $(document).on('click', '#remove_gs_custom_grade', function(e) {
    e.preventDefault();
    var userid=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>librarybooks/removeBook_this_custom_grade/",
      data: ({
        userid: userid
      }),
      beforeSend: function() {
        $('.remove_gs_custom_grade' + userid).html( '<span class="text-info">Removing...</span>');
        $('#remove_gs_custom_grade').attr( 'disabled','disabled');
        
      },
      success: function(html){
        iziToast.success({
          title: html,
          message: '',
          position: 'topRight'
        });
        $('.remove_gs_custom_grade' + userid).html( 'Remove');
        $('#remove_gs_custom_grade').removeAttr( 'disabled');
        load_custom_grade_data();
      }
    });
  });
  $(document).on('submit', '#submiteditedCustomGrade', function(e) {
    e.preventDefault();
    if ($('#custom_grade_name').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>librarybooks/savegs_custom_grade/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#saveCustom_Grade').html( '<span class="text-info">Saving...</span>');
          $('#saveCustom_Grade').attr( 'disabled','disabled');
          
        },
        success: function(html){
          iziToast.success({
            title: html,
            message: '',
            position: 'topRight'
          });
          $('#custom_grade_name').val('');
          $('#saveCustom_Grade').html( 'Add Head');
          $('#saveCustom_Grade').removeAttr( 'disabled');
          load_custom_grade_data();
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '#gs-custom-grade', function()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>librarybooks/add_custom_grade/",
      method:"POST",
      beforeSend: function() {
        $('.library_head_registration_here').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.library_head_registration_here').html(data);
      }
    })
  });
  $(document).on('click', '#libray-head', function()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>librarybooks/fetch_form_toadd_library_head/",
      method:"POST",
      beforeSend: function() {
        $('.library_head_registration_here').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.library_head_registration_here').html(data);
      }
    })
  });
  function load_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>librarybooks/fetch_book_head/",
      method:"POST",
      beforeSend: function() {
        $('.fetch_book_stock_head').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.fetch_book_stock_head').html(data);
      }
    })
  }
  $(document).on('submit', '#submiteditedbook_stock_head', function(e) {
    e.preventDefault();
    var stockHead=$('#book_stock_head').val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>librarybooks/savebook_stock_head/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#saveBook_Stock_Head').html( '<span class="text-info">Saving...</span>');
          $('#saveBook_Stock_Head').attr( 'disabled','disabled');
          
        },
        success: function(html){
          iziToast.success({
            title: html,
            message: '',
            position: 'topRight'
          });
          $('#saveBook_Stock_Head').html( 'Add Head');
          $('#saveBook_Stock_Head').removeAttr( 'disabled');
          load_data();
        }
      });
    
  });
  $(document).on('click', '#removeBook_Stock_Head', function(e) {
    e.preventDefault();
    var userid=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>librarybooks/removeBook_Stock_Head/",
      data: ({
        userid: userid
      }),
      beforeSend: function() {
        $('.removeBook_Stock_Head' + userid).html( '<span class="text-info">Removing...</span>');
        $('#removeBook_Stock_Head').attr( 'disabled','disabled');
        
      },
      success: function(html){
        iziToast.success({
          title: html,
          message: '',
          position: 'topRight'
        });
        $('.removeBook_Stock_Head' + userid).html( 'Remove');
        $('#removeBook_Stock_Head').removeAttr( 'disabled');
        load_data();
      }
    });
  });
  $(document).ready(function(){
    $('#empTableGS').DataTable({
      'processing': true,
      'serverSide': true,
      "dataType": "json",
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>librarybooks/fetch_book_history/'
      },
      'columns': [
        { data: 'book_id' },
         { data: 'book_name' },
         { data: 'book_grade' },
         { data: 'book_price' },
         { data: 'book_quantity' },
         { data: 'book_branch' },
      ]
    });
  }); 
  $(document).on('click', '#editThisBookName', function() {
    var stockid=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>librarybooks/edit_book_name/",
      data: ({
        stockid: stockid
      }),
      cache: false,
      beforeSend: function() {
        $('.book_editingplace__here').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.book_editingplace__here').html(html);
      }
    }); 
  });
  $(document).on('submit', '#submiteditedBookName', function(e) {
    e.preventDefault();
    if ($('#editedbook_id').val() != '' && $('#editedbook_name').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>librarybooks/saveEditedItem/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#saveEditedBook').html( '<span class="text-info">Updating...</span>');
        },
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Changes saved successfully',
              message: '',
              position: 'topRight'
            });
            $('#empTableGS').DataTable().ajax.reload();
            $('#book_editingplace__here').modal('hide');
          }else{
            iziToast.error({
              title: 'Please try again',
              message: '',
              position: 'topRight'
            });
          }
          $('#saveEditedBook').html('Save Changes');
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '#deleteThisBookName', function() {
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
          url: "<?php echo base_url(); ?>librarybooks/delete_stock_book/",
          data: ({
            stockid: stockid
          }),
          cache: false,
          beforeSend: function() {
            $('.deleted_stock_item' + stockid).html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(html){
            $('.deleted_stock_item' + stockid).fadeOut('slow');
            $('#empTableGS').DataTable().ajax.reload();
          }
        }); 
      }
    });
  });
  $(document).on('click', '#add_new_book', function()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>librarybooks/fetch_form_toadd_new_book/",
      method:"POST",
      beforeSend: function() {
        $('.book_registration_here').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.book_registration_here').html(data);
      }
    })
  });
  $(document).on('submit', '#saveNew_book', function(e) {
    e.preventDefault();
    if ($('#item_category').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>librarybooks/insert_item/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#savenewbook').attr('disabled','disabled');
        },
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Data saved successfully.',
              message: '',
              position: 'topRight'
            });
            $('#empTableGS').DataTable().ajax.reload();
            $("#saveNew_book")[0].reset();
          }else if(html=='2'){
            iziToast.error({
              title: 'Ooops, Book found',
              message: '',
              position: 'topRight'
            });
          }else{
            iziToast.error({
              title: 'Ooops, please try again.',
              message: '',
              position: 'topRight'
            });
          }
          $('#savenewbook').removeAttr('disabled'); 
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