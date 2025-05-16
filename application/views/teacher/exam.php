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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/summernote-bs4.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/datatables.min.css">
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/selectric.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <!-- Custom style CSS -->
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
            <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-12">
                <div class="card">
                  <div class="boxs mail_listing">
                    <div class="inbox-center table-responsive">
                      <div class="card-header">
                        <h4>Add New Exam</h4>
                        <?php if(isset($_SESSION['success'])){ ?>
                      <sapn class="text-success">
                          <?php echo $_SESSION['success']; ?>
                      </span>
                      <?php  }
                      elseif(isset($_SESSION['error'])) { ?>
                      <sapn class="text-danger">
                          <?php echo $_SESSION['error']; ?>  
                      </span>
                     <?php } ?>
                       </div>   
                    </div>
                    <div class="row">
                    <div class="col-lg-12">
                       <form class="composeForm" method="POST"
                       action="<?php echo base_url();?>myexam/">
                        <div class="row">
                         <div class="col-lg-6">
                          <div class="form-group">
                            <label for="Mobile">Select Grade</label>
                            <div class="form-line">
                              <select class="form-control selectric"required="required"
                              name="gradesec" id="gradesec">
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
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="Mobile">Select Subject</label>
                            <div class="form-line">
                            <select class="form-control"
                            name="subject" id="subject">
                            </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group">
                            <label for="Mobile">Question Answer</label>
                            <div class="form-line">
                            <input type ="text" placeholder="Question answer here" class="form-control"
                            name="answer" id="answer">
                            </div>
                          </div>
                        </div>
                      </div>
                      <textarea class="form-control summernote-simple bio" id="note" name="question" placeholder="Lesson note here..." 
                           required="required">
                      </textarea>
                      <div class="m-l-25 m-b-20">
                        <button type="submit" name="addlessonnow" class="btn btn-info btn-border-radius waves-effect">Send
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
  <script src="<?php echo base_url(); ?>assets/pages/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
 <script type="text/javascript">
    $(document).ready(function() {
        $("#gradesec").change(function() {
          var gradesec=$("#gradesec").val();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url(); ?>Filtereach_user_gradesec/",
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