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
              <?php foreach($posts as $post){ $id=$post->pid; ?>
              <div class="col-12 col-md-3 col-lg-3 delete_mem<?php echo $id ?>">
                <article class="article article-style-c">
                  <div class="article-details">
                    <div class="article-category">
                      <div class="bullet"></div>
                       <a href="#"><?php echo $post->date_post; ?></a>
                       <?php if($post->postby ==$_SESSION['username']){ ?>
                       <a href="#">
                        <button class="btn btn-default deletepost"
                         name="deletepost" type="submit"
                         id="<?php echo $post->pid; ?>">
                          <i class="fas fa-trash"></i>
                        </button>
                       </a>
                     <?php } ?>
                    </div>
                    <div class="article-title">
                    <h2><a href="#"><?php echo $post->title; ?></a></h2>
                    </div>
                    <p><?php echo  substr($post->post,0,40); ?>...</p>
                    <form action="<?php echo base_url()?>read/" method="POST">
                    <div class="article-cta">
                      <a href="<?php echo base_url(); ?>read/">
                        <button name="readmore" value="<?php echo $post->pid; ?>" class="btn btn-info" type="submit">Read More
                        </button>
                      </a>
                    </div>
                   </form>
                    <div class="article-user">
                      <img alt="image" src="<?php echo base_url(); ?>/profile/<?php echo $post->profile;?>">
                      <div class="article-user-details">
                        <div class="user-detail-name">
                          <a href="#"><?php echo $post->postby; ?></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </article>
              </div>
             <?php } ?>
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
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
<script>
    $(document).ready(function() {
    $('.deletepost').click(function() { // To delete post   
        var post_id = $(this).attr("id");
        if (confirm("Are you sure you want to delete this Post ?")) {
            $.ajax({
                method: "GET",
                url: "<?php echo base_url(); ?>home",
                data: ({
                    post_id: post_id
                }),
                cache: false,
                success: function(html) {
                    $(".delete_mem" + post_id).fadeOut('slow');
                }
            });
        } else {
            return false;
        }
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