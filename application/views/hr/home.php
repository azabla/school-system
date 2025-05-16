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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/summernote/summernote-bs4.css">
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
              <div class="col-12 col-md-5 col-lg-5">
              <?php foreach($posts as $post){ $id=$post->pid; ?>
              <div class="delete_mem<?php echo $id ?>">
                <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ustify-content-around">
                      <div class="col-10">
                        <div class="article-user-details">
                          <div class="user-detail-name">
                            <h5 class="card-title"><img alt="image" src="<?php echo base_url(); ?>/profile/<?php echo $post->profile;?>" class="border-circle">
                            <?php echo $post->fname;echo' '; echo $post->mname;?></h5>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                      <?php if($post->postby ==$_SESSION['username'] || $_SESSION['usertype']===trim('superAdmin')){ ?>
                        <div class="btn-group dropleft">
                          <a href="#" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
                            <i class="fas fa-ellipsis-h fa-sm fa-fw text-gray-400"></i>
                          </a>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon deletepost" id="<?php echo $post->pid; ?>">
                              <i class="fas fa-trash col-orange"></i> Remove
                            </a>
                            <div class="dropdown-divider"></div>
                          </div>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                 </div>
                 <div class="dropdown-divider"></div>
                  <div class="support-ticket media pb-1 mb-3">
                    <div class="media-body ml-3">
                      <span class="font-weight-bold">
                        <?php echo $post->title; ?>
                      </span>
                      <small class="text-muted"> 
                        ( <?php echo $post->date_post; ?> )
                      </small>
                      <?php if($post->post!=''){?>
                      <div class="shown<?php echo $post->pid;?>" id="grand_show">
                      <small class="text-muted font-15">
                        <?php  $strlen=$post->post;
                        echo  strlen($post->post) >400 ? substr($strlen,0,400).' <a class="readmore" value="'.$post->pid.'">
                          <span class="badge badge-pill badge-info"> Read More
                          </span>
                        </a>' : $post->post; ?>
                      </small>
                      </div>
                      <?php } else{ ?>
                      <div id="aniimated-thumbnials" class="list-unstyled row clearfix">
                        <img alt="image" src="<?php echo base_url(); ?>/public_post/<?php echo $post->photo;?>" class="img-responsive thumbnail">
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="dropdown-divider"></div>
                   <!-- count this post likes -->
                  <?php 
                  $like_query =$this->db->query("SELECT * FROM post_like WHERE pid='$id' ");
                    $tot_likes=$like_query->num_rows();
                    $session=$_SESSION['id'];
                  $user_like_query=$this->db->query("SELECT * FROM post_like WHERE pid='$id' and bid='$session'");
                  $user_likes=$user_like_query->num_rows();
                  ?>
                    <div class="row">
                      <div class="col-lg-1"> </div>
                      <div class="col-lg-6">
                      <p class="mb-0">
                        <div class="thumbs-up-button">
                        <a  class="like"  id="like_<?php echo $id; ?>">
                        <?php echo $user_likes > 0 ? 
                        '<span class="text-danger">
                        <i data-feather="heart"></i></span>'
                        : '<i data-feather="heart"></i>';
                        ?>
                        </a> 
                        <small>
                        <span class="col-green count-likes_<?php echo $id; ?>"> <?php echo $tot_likes; ?> </span>
                         likes</small>
                         </div>
                      </p>
                    </div>
                    <div class="col-lg-5"> </div>
                    </div>
                </div>
               </div>
               <div class="dropdown-divider"></div>
              </div>
             <?php } ?>
          </div>
         <div class="col-12 col-md-7 col-lg-7">
        <div class="main-sidebar-right">
         <div class="row">

          <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='Chat' order by id ASC ");  
         if($usergroupPermission->num_rows()>0){ ?>
           <div class="col-12 col-md-5 col-lg-5">
            <div class="card">
              <div class="card-header"><small class="text-muted">BirthDate Celebration </small></div>
                <div class="body">
                  <div id="plist" class="people-list">
                    <div class="m-b-20">
                      <div id="chat-scroll" class="birthdate">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
           </div>
           <div class="col-12 col-md-7 col-lg-7">
             <div class="card table-responsive" style="height: 170px;">
              <div class="card-header"><small class="text-muted">Users Online</small></div>
                <div class="body">
                  <div id="plist" class="people-list">
                    <div class="m-b-20">
                      <div id="chat-scroll" class="chatnamees">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
           </div>
         <?php } else{ ?>
          <div class="col-12 col-md-7 col-lg-7"></div>
          <div class="col-12 col-md-5 col-lg-5">
            <div class="card">
              <div class="card-header"><small class="text-muted">BirthDate Celebration </small></div>
                <div class="body">
                  <div id="plist" class="people-list">
                    <div class="m-b-20">
                      <div id="chat-scroll" class="birthdate">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
           </div>
          <?php } ?>

           <div class="col-12 col-md-12 col-lg-12">
             <div class="cardChat woreArea"> </div>
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
          <a href="https://www.grandstande.com" target="_blanck">Grandstand IT Solutions Plc</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/summernote/summernote-bs4.js"></script>
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
        $('.woreArea').html(html);
        $('.chat-box').scrollTop($('.chat-box')[0].scrollHeight);
      }
    });
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
          $('.woreArea').html(html);
          $('.input-field').val('');
          $('.chat-box').scrollTop($('.chat-box')[0].scrollHeight);
        }
      })
    }
  });
</script>
 <script type="text/javascript">
  $(document).on('click', '.readmore', function() {
    var id=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Fetch_showmore/",
      data: ({
        id: id
      }),
      cache: false,
      beforeSend: function() {
        $('.shown' + id).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="4" height="4" id="loa">' );
      },
      success: function(html){
        $('.shown' + id).html(html);
      }
    }); 
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
  $('.like').click(function() { 
    var id = this.id;
    var split_id = id.split("_");
    var text = split_id[0];
    var like_id = split_id[1];
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>fetch_post_likes/",
      data: ({
        like_id: like_id
      }),
      cache: false,
      dataType: 'json',
      success: function(data) {
       var likes = data['countlikes'];
       $(".count-likes_" + like_id).text(likes); 
       $("#like_" + like_id).css("color", "red");
      }
    });
  });
});
</script> 
<script>
  $(document).ready(function() {
    $('.deletepost').click(function() { 
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
      }else {
        return false;
      }
    });
  });
</script>
  
<script>
  $(document).ready(function() { 
    
    function birth_date(view = '') {
      $.ajax({
        url: "<?php echo base_url(); ?>birthdate/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType:"json",
        success: function(data) {
          $('.birthdate').html(data.notification);
        }
      });
    }
    function users_online(view = '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Users_online/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType:"json",
        success: function(data) {
          $('.chatnamees').html(data.notification);
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
    birth_date();
    unseen_notification();
    users_online();
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
      birth_date();
      unseen_notification();
      users_online();
      inbox_unseen_notification();
    }, 5000);

  });
</script>
</body>

</html>