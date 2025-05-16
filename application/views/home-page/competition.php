<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <?php include('bgcolor.php'); ?>
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
  <link rel='shortcut icon' type='image/x-icon'
   href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
   <style type="text/css">
    body {
      background-image: url('<?php echo base_url(); ?>/wallpapers/<?php echo $bgid;?>');
    }
  </style>
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
      <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="card">
              <div class="card-header">
                <div class="d-flex align-items-center pt-3">
                  <div id="prev"> <h5 class="card-title">Student Competition</h5> </div>
                  <div class="ml-auto mr-sm-5"> 
                    <button class="btn btn-success" id="addQuestions">Add Questions</button> 
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    
                    <!-- <div class="container my-1">
                      <div class="question ml-sm-5 pl-sm-5 pt-2">
                        <div class="py-2 h5"><b>Q. which option best describes your job role?</b></div>
                        <div class="ml-md-3 ml-sm-3 pl-md-5 pt-sm-0 pt-3" id="options"> 
                          <label class="options">
                          Small Business Owner or Employee <input type="radio" name="radio"> <span class="checkmark"></span>
                          </label> <label class="options">Nonprofit Owner or Employee <input type="radio" name="radio"> <span class="checkmark"></span> </label> <label class="options">Journalist or Activist <input type="radio" name="radio"> <span class="checkmark"></span> </label> <label class="options">Other <input type="radio" name="radio"> <span class="checkmark"></span> </label> 
                        </div>
                      </div>
                      <div class="d-flex align-items-center pt-3">
                        <div id="prev"> <button class="btn btn-primary">Previous</button> </div>
                        <div class="ml-auto mr-sm-5"> <button class="btn btn-success">Next</button> </div>
                      </div>
                    </div> -->
                  </div>
                </div>
                <div class="listQuestionType table-responsive" id="listQuestionType" style="height:40vh;">
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
          <a href="https://www.grandstande.com" target="_blanck">Grandstand IT Solution Plc</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
</body>
<script type="text/javascript">  
$(function () {  
    $("#addQuestions").bind("click", function () {  
        var div = $("<div />");  
        div.html(GenerateTextbox(""));  
        $("#listQuestionType").append(div);  
    });  
    $("#buttonGet").bind("click", function () {  
        var values = "";  
        $("input[name=CreateTextbox]").each(function () {  
            values += $(this).val() + "\n";  
        });  
        alert(values);  
    });  
    $("body").on("click", ".remove", function () {  
        $(this).closest("div").remove();  
    });  
});  
function GenerateTextbox(value) {  
    return '<input name = "CreateTextbox" type="text" class="form-control" value = "' + value + '" /> ' +  
            '<input type="button" value="Remove" class="remove" />'  
}  
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