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
                <div class="row">
                  <div class="col-lg-12 col-12">
                    <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyProgress()">
                    <span class="text-black">
                      <i data-feather="printer"></i>
                    </span>
                    </button>
                    <button type="submit" id="dataExportExcelS" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel
                    </button>
                  </div>
                </div>
                <div class="card">
                  <div class="table-responsive card-body StudentViewTextInfo" id="reportContent_GS">                 
                    <table class="display dataTable" id='empTable_Report' style="width:100%;">
                      <thead>
                       <tr>
                        <th>Book Name</th>         
                         <th>Book Grade</th>
                         <th>Date Requested</th>
                         <th>Requested By</th>
                         <th>Request Response</th>
                         <th>Response By</th>
                         <th>Return Status</th>
                         <th>Returned Date</th>
                         <th>Received By</th>
                        </tr>
                      </thead>
                    </table> 
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
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
</body>
<script>

  // enable this if you want to make only one call and not repeated calls automatically
  // pushNotify();

  // following makes an AJAX call to PHP to get notification every 10 secs
  setInterval(function(){pushNotify();}, 10000);

      function pushNotify() {
        if (!("Notification" in window)) {
              
          }
          if (Notification.permission !== "granted")
              Notification.requestPermission();
          else {
              $.ajax({
              url: "<?php echo base_url() ?>Unseen_bookrequest_notification/",
              type: "POST",
              success: function(data, textStatus, jqXHR) {
                  // if PHP call returns data process it and show notification
                  // if nothing returns then it means no notification available for now
                if ($.trim(data)){
                      var data = jQuery.parseJSON(data);
                      console.log(data);
                      notification = createNotification( data.title,  data.icon,  data.body, data.url);

                      // closes the web browser notification automatically after 5 secs
                      setTimeout(function() {
                        notification.close();
                      }, 5000);
                  }
              },
              error: function(jqXHR, textStatus, errorThrown) {}
              });
          }
      };

      function createNotification(title, icon, body, url) {
          var notification = new Notification(title, {
              icon: icon,
              body: body,
          });
          // url that needs to be opened on clicking the notification
          // finally everything boils down to click and visits right
          notification.onclick = function() {
              window.open(url);
          };
          return notification;
      }

  </script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#empTable_Report').DataTable({
      'processing': true,
      'serverSide': true,
      "dataType": "json",
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>borrowbooksreport/fetch_borrow_report/'
      },
      'columns': [
        { data: 'book_name' },
        { data: 'book_grade' },
        { data: 'date_submitted' },
        { data: 'submitted_by' },
        { data: 'request_response' },
        { data: 'response_by' },
        { data: 'return_status' },
        { data: 'date_returned' },
        { data: 'Received_by' },
      ]
    });
  }); 
  $(document).on('click', '#returnBack_this_mybookRequest', function() {
    swal({
      title: 'Are you sure this book is returned back?',
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
          url: "<?php echo base_url(); ?>borrowbooksreport/received_Borrowed_book/",
          data: ({
            requestid: requestid
          }),
          cache: false,
          success: function(html){
            $('#empTable_Report').DataTable().ajax.reload();
          }
        }); 
      }
    });
  });
  function codespeedyProgress(){
    var print_div = document.getElementById("reportContent_GS");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
   $("#dataExportExcelS").click(function(e) {
  let file = new Blob([$('#reportContent_GS').html()], {type:"application/vnd.ms-excel"});
  let url = URL.createObjectURL(file);
  let a = $("<a />", {
    href: url,
    download: "Library report.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
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