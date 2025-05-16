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
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'> 
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
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <div class="table-responsive">
                     <table class="table table-striped table-hover"
                       id="tableExport" style="width:100%;">
                        <thead>
                          <tr>
                            <th>Post by</th>
                            <th>Subject</th>
                            <th>Title</th>
                            <th>Grade</th>
                            <th>View</th>
                            <th>Date Posted</th>
                            <th>Delete</th> 
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach($lesson as $lessons){  
                            $id=$lessons->lid;
                            $note=$lessons->note; ?>
                          <tr class="delete_mem<?php echo $id ?>">
                          <td>
                            <img alt="image" style="width: 30px;height:30px;border-radius: 3em" src="<?php echo base_url(); ?>/profile/<?php echo $lessons->profile;?>">
                            <a href="#">
                            <?php echo $lessons->fname;
                          echo' '; echo $lessons->mname;?></a></td>
                          <td><a href="#"><?php echo $lessons->subject; ?></a></td>
                          <td><?php echo  substr($lessons->title,0,40); ?>...</td>
                          <td><?php echo $lessons->grade; ?></td>
                          <td>
                            <?php if($note != ''){?>
                            <form action="<?php echo base_url()?>readlesson/" method="POST">
                            <div class="article-cta">
                              <button name="readmore" value="<?php echo $lessons->lid; ?>" class="btn btn-info" type="submit">Read
                              </button>
                            </div>
                          </form>
                          <?php }else{ ?>
                            <a href="<?php echo base_url().'lessonworksheet/'.$lessons->pdfdoc; ?>" class="dwn" download>Download</a>
                          <?php } ?>
                          </td>
                          <td>
                            <?php echo $lessons->lesson_date; ?> 
                          </td>
                          <form action="<?php echo base_url()?>viewlesson/" method="POST">
                          <td>
                            <?php if($lessons->teacher == $_SESSION['username']){ ?> 
                              <a href="#">
                                <button onclick="return confirm ('Are you sure you want to delete this lesson')" class="btn btn-default  deletelesson"  
                                name="deletelesson" type="submit" value="<?php echo $lessons->lid; ?>">
                                  <i class="fas fa-trash"></i>
                                </button>
                              </a>
                            <?php } ?>
                          </td>
                        </form>
                        </tr>
                      <?php } ?>
                    </tbody>
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

  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

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
</body>

</html>