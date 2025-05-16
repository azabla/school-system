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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/datatables.min.css">
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/selectric.css">
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
              <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                <div class="card">
                  <div class="body">
                    <div id="mail-nav">
                      <ul class="" id="mail-folders">
                        <li class="active">
                          <a href="javascript:;" title="Inbox">Compose
                          </a>
                        </li>
                        <li>
                          <a href="<?php echo base_url(); ?>mystaffinbox/" title="Inbox">Inbox ()
                          </a>
                        </li>
                        <li>
                          <a href="<?php echo base_url(); ?>mystaffsent/" title="Sent">Sent</a>
                        </li>
                        <li>
                          <a href="javascript:;" title="Draft">Draft</a>
                        </li>
                        <li>
                          <a href="javascript:;" title="Important">Important</a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                <div class="card">
                  <div class="boxs mail_listing">
                    <div class="inbox-center table-responsive">
                      <div class="card-header">
                        <h4>Compose new message</h4>
                       </div>   
                    </div>
                    <div class="row">
                    <div class="col-lg-12">
                       <form class="composeForm" method="POST"
                       action="<?php echo base_url();?>Newstaffcompose/">
                        <div class="row">
                         <div class="col-lg-4">
                          <div class="form-group">
                            <label for="Mobile">Select Usertype</label>
                            <div class="form-line">
                              <select class="form-control selectric"required="required"
                              name="usertype" id="usertype">
                                <option></option>
                                <?php foreach($usertype as $usertypes) { ?>
                                 <option value="<?php echo $usertypes->usertype;?>">
                                 <?php echo $usertypes->usertype;?></option>
                                <?php } ?>
                              </select>
                            </div>
                           </div>
                          </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label for="Mobile">Select Grade(For Only Student)</label>
                            <div class="form-line">
                            <select class="form-control"
                            name="user" id="user">
                            </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            <div class="card">
                              <div class="body">
                                <div id="plist" class="people-list">
                                  <div class="m-b-20">
                                    <div id="chat-scroll">
                                      <ul class="chat-list list-unstyled m-b-0">
                                        <li class="clearfix">
                                          <div class="about">
                                            <div class="form-line composeGradeViewHeight" id="grade">
                                              
                                            </div>
                                          </div>
                                        </li>
                                      </ul>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                          <div class="form-group">
                            <div class="form-line">
                              <input type="text" 
                              id="subject" name= "subject" required="required" 
                               class="form-control" placeholder="Subject">
                            </div>
                          </div>
                           <textarea class="form-control summernote-simple bio" id="message" name="message"
                           required="required">
                           </textarea>
                        <div class="m-l-25 m-b-20">
                          <button type="submit" name="composemsg" class="btn btn-info btn-border-radius waves-effect">Send
                          </button>
                          <button type="button" id="discard" class="btn btn-danger btn-border-radius waves-effect">Discard
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
       <footer class="main-footer">
        <div class="footer-left">
         Call:+251967829025 &nbsp;&nbsp;
          Copyright &copy <?php echo date('Y');?>
          <a href="https://www.grandstande.com" target="_blanck">GrandStand</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/summernote/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
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
      var items=document.getElementsByName('username[ ]');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('username[ ]');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
    }
</script>
  <script type="text/javascript">
    $(document).ready(function() {
        $("#discard").on("click",function() {
          $("#subject").val(''); 
          $("#message").val('');
          $("#usertype").val('');
        });
    });
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