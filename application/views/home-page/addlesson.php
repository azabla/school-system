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
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
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
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="boxs mail_listing">
                    <div class="inbox-center table-responsive">
                      <div class="card-header">
                        <h4>Add Lesson or Worksheet</h4>
                       </div>   
                    </div>
                    <?php include('bgcolor.php'); ?>
                    <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                    <?php echo form_open_multipart('Addlesson/');?>
                    <div class="composeForm">
                    <div class="row">
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <label for="Mobile">Select Grade</label>
                          <div class="form-line">
                            <select class="form-control selectric"required="required" name="gradesec" id="gradesec">
                              <option></option>
                              <?php foreach($fetch_gradesec as $fetch_gradesecs) { ?>
                               <option value="<?php echo $fetch_gradesecs->grade;?>">
                               <?php echo $fetch_gradesecs->grade;?>
                               </option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <label for="Mobile">Select Subject</label>
                          <div class="form-line">
                            <select required="required" class="form-control" name="subject" id="subject">
                            </select>
                            </div>
                          </div>
                        </div>
                      <div class="col-lg-6 col-12">
                        <div class="form-group">
                          <label for="Mobile">Lesson Title</label>
                          <div class="form-line">
                            <input type="text" 
                            id="title" name= "title" required="required" class="form-control" placeholder="Lesson or worksheet title here...">
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-12 col-12">
                    <label for="Mobile">Lesson Description</label>
                    <textarea class="form-cont4rol" rows="4" cols="50" wrap="physical" name="note" id="note" placeholder="Add Comment" style="width:100%; height:100px;"> </textarea>
                    <p>You can post Lesson or Worksheet either in the above field or import file below.</p>
                  </div>
                  <div class="col-lg-6 col-12">
                    <div class="custom-file form-group">
                      <input type="file" class="custom-file-input" id="customFile" name="pdfdoc">
                      <label class="custom-file-label" for="customFile">Choose file(Doc/Pdf/mp4)</label>
                    </div>
                  </div>
                  <div class="col-lg-6 col-12">
                    <div class="m-l-25 m-b-20 pull-right">
                      <button type="submit" name="addlessonnow" class="btn btn-info btn-border-radius waves-effect">Submit 
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
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js">
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
    $(document).ready(function() {
        $("#gradesec").change(function() {
          var gradesec=$("#gradesec").val();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url(); ?>Filter_grade_subject/",
                data: {gradesec:gradesec} ,
                success: function(data) {
                    $("#subject").html(data);
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