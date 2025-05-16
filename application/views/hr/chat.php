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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/summernote-bs4.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css">
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
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <div class="card">
                  <div class="chatarea">
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="card">
                  <div class="body">
                    <div id="plist" class="people-list">
                      <div class="m-b-20">
                        <div id="chat-scroll" 
                        class="namees">
                          
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
       <footer class="main-footer">
        <div class="footer-left">
         Call:+251967829025 &nbsp;&nbsp;
          Copyright &copy<?php echo date('Y');?>
          <a href="https://www.grandstande.com" target="_blanck">GrandStand</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/pages/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</body>
<script type="text/javascript">
  $(document).on('click', '.selectChatId', function() {
    var id=$(this).attr("value");
    $.ajax({
      url: "<?php echo base_url(); ?>Fetchtochat/",
      method: "POST",
      data: ({
        id: id
      }),
      cache: false,
      beforeSend: function() {
        $('.chat-area').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.chatarea').html(html);
      }
    });
    function newChatMessages(view = '') {
      $.ajax({
        url: "<?php echo base_url(); ?>newChatMessages/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType:"json",
        success: function(data) {
          $('#fetchChatMessage' + id).html(data.notification);
        }
      });
    }
    setInterval(function() {
      newChatMessages();
    }, 5000);
  });
  $(document).on('click','.send',function(e){
    e.preventDefault();
    var msg=$(".input-field").val();
    var outgoing_id=$(".outgoing_id").val();
    if(msg==''){
      ('.send').addAttr('disable','disable');
    }else{
      $.ajax({
        url:'<?php echo base_url(); ?>insertchatmsg',
        method:'POST',
        data:({
          msg:msg,
          outgoing_id:outgoing_id
        }),
        cache:false,
        beforeSend:function(){
          $('.chat-content').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success:function(html){
           $('.chatarea').html(html);
           $('.input-field').val('');
        }
      })
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
      $("body").removeClass("dark");
      $("body").removeClass("dark-sidebar");
      $("body").removeClass("theme-black");
      $("body").addClass("light");
      $("body").addClass("light-sidebar");
      $("body").addClass("theme-white");
    } else {
      $("body").removeClass("light");
      $("body").removeClass("light-sidebar");
      $("body").removeClass("theme-white");
      $("body").addClass("dark");
      $("body").addClass("dark-sidebar");
      $("body").addClass("theme-black");
    }
  });
</script>
<script type="text/javascript" language="javascript"> 
  var bgcolor_now=document.getElementById("bgcolor_now").value;
  if (bgcolor_now == "1") {
    $("body").removeClass("dark");
    $("body").removeClass("dark-sidebar");
    $("body").removeClass("theme-black");
    $("body").addClass("light");
    $("body").addClass("light-sidebar");
    $("body").addClass("theme-white");
  }else {
    $("body").removeClass("light");
    $("body").removeClass("light-sidebar");
    $("body").removeClass("theme-white");
    $("body").addClass("dark");
    $("body").addClass("dark-sidebar");
    $("body").addClass("theme-black"); 
  } 
</script> 
<script>
  $(document).ready(function() {   
    function users_online(view = '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Users_online/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType:"json",
        success: function(data) {
          $('.namees').html(data.notification);
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
    unseen_notification();
    inbox_unseen_notification();
    users_online();
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
      users_online();
    }, 5000);
  });
</script>
</html>