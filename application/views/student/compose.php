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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
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
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="boxs mail_listing">
                    <div class="inbox-center table-responsive">
                      <div class="card-header">
                        <h4>Compose new message</h4>
                       </div>   
                    </div>
                    <div class="row">
                    <div class="col-lg-12">
                      <form class="StudentViewTextInfo" id="formCompose">
                        <div class="row">
                         <div class="col-lg-4 col-12">
                          <div class="form-group">
                            <label for="Mobile">Select Usertype</label>
                            <div class="form-line">
                              <select class="form-control selectric"required="required" name="usertype" id="usertype">
                                <option></option>
                                <option value="Director"> Director</option>
                                <option value="Admin"> Admin</option>
                                <option value="superAdmin"> superAdmin</option>
                              </select>
                            </div>
                           </div>
                          </div>
                        <div class="col-lg-8 col-12">
                          <div class="form-line composeGradeViewHeight" id="grade"> </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <input type="text" id="message_title" name= "subject" required="required" 
                           class="form-control" placeholder="Write message title here...">
                      </div>
                       <textarea class="student_message_area" rows="4" cols="50" wrap="physical" name="student_message_area" id="student_message_area" style="width:100%; height:100px;" required="required" placeholder="Write your message here..."></textarea>

                        <div class="m-l-25 m-b-20 pull-right">
                          <button type="submit" id="sending_message" name="composemsg" class="btn btn-success">Send Message
                          </button>
                        </div>
                        </form>
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
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script type="text/javascript">
    $(document).on('click', '#sending_message', function(event) {
      event.preventDefault();
      username=[];commentvalue=[];
      $("input[name='username_message[ ]']:checked").each(function(i){
        username[i]=$(this).val();
      });   
      var commentvalue = jQuery.trim($("#student_message_area").val());
      var message_title=$("#message_title").val();
      var usertype=$("#usertype").val();
      if(commentvalue.length  ==0 || username.length==0 || $("#message_title").val()==''){
        swal('Oooops, Please write something on the provided field!', {
          icon: 'warning',
        });
      }else{
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>newcompose/composeMessage/",
          data: ({
            message_to: username,
            message_title:message_title,
            message_content:commentvalue,
            usertype:usertype
          }),
          success: function(data) {
            $("#formCompose")[0].reset();
            $("#student_message_area").val('');
            iziToast.success({
              title: data,
              message: '',
              position: 'topRight'
            });
          }
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
<script type="text/javascript">
    function selectAll(){
      var itemsall=document.getElementById('selectall');
      if(itemsall.checked==true){
      var items=document.getElementsByName('username_message[ ]');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('username_message[ ]');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
    }
</script>
 <script type="text/javascript">
    $(document).ready(function() {
        $("#usertype").change(function() {
          var usertype=$("#usertype").val();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url(); ?>Usertype/",
                data: {usertype:usertype} ,
                beforeSend: function() {
                    $('#grade').html(
                        '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="">'
                    );
                },
                success: function(data) {
                  if(usertype !=='Student'){
                    $("#grade").html(data);
                    $("#user").attr("disabled","disabled");
                  }else{
                    $("#user").html(data);
                    $("#user").removeAttr("disabled","disabled");
                  }
                }
            });
        });
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
        $("#user").change(function() {
          var grade=$("#user").val();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url(); ?>Usertype/",
                data: {grade:grade} ,
                beforeSend: function() {
                    $('#grade').html(
                        '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="">'
                    );
                },
                success: function(data) {
                    $("#grade").html(data);
                }
            });
        });
    });
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
</body>

</html>