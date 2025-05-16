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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/glightbox.min.css" >
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <style>
        @-webkit-keyframes placeHolderShimmer {
          0% {
            background-position: -468px 0;
          }
          100% {
            background-position: 468px 0;
          }
        }

        @keyframes placeHolderShimmer {
          0% {
            background-position: -468px 0;
          }
          100% {
            background-position: 468px 0;
          }
        }

        .content-placeholder {
          display: inline-block;
          -webkit-animation-duration: 1s;
          animation-duration: 1s;
          -webkit-animation-fill-mode: forwards;
          animation-fill-mode: forwards;
          -webkit-animation-iteration-count: infinite;
          animation-iteration-count: infinite;
          -webkit-animation-name: placeHolderShimmer;
          animation-name: placeHolderShimmer;
          -webkit-animation-timing-function: linear;
          animation-timing-function: linear;
          background: #f6f7f8;
          background: -webkit-gradient(linear, left top, right top, color-stop(8%, #eeeeee), color-stop(18%, #dddddd), color-stop(33%, #eeeeee));
          background: -webkit-linear-gradient(left, #eeeeee 8%, #dddddd 18%, #eeeeee 33%);
          background: linear-gradient(to right, #eeeeee 8%, #dddddd 18%, #eeeeee 33%);
          -webkit-background-size: 800px 104px;
          background-size: 800px 104px;
          height: inherit;
          position: relative;
        }
    </style>
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
              <div class="col-12 col-md-6 col-lg-6 order-md-2">
                <div class="row"> 
                  <div class="col-12 col-md-12 col-lg-12">
                    <?php $usergroupP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='createPolls' and tableName='HomepagePost' order by id ASC "); 
                          if($usergroupP->num_rows()>0){ ?>
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          
                          <div class="col-lg-12 col-md-12 col-12">
                            <button  class="btn btn-primary btn-md btn-block" data-toggle="modal" data-target=".postPoll"> <h4 class="card-header">Create Poll <i class="fas fa-check-square"></i></h4> </button>
                          </div>
                          
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                    <div id="load_poll_data"></div>
                    <div id="load_poll_data_message"></div>
                    <!-- <div class="birthdate"> </div> -->
                    <!-- <div class="card">
                      <div class="card-header">Time Table</div>
                      <div class="body">
                        <div class="timeTable"></div>
                      </div>
                    </div> -->
                  </div>
                  <div class="col-12 col-md-12 col-lg-12">
                    <div class="title-header">Quick Links</div>
                    <div class="row">
                      <?php $this->db->where('usergroup',$_SESSION['usertype']);
                      $this->db->where('tableName','StudentMark');
                      $this->db->where('allowed','addstudentmark');
                      $this->db->order_by('id','ASC');
                      $uaddMark=$this->db->get('usergrouppermission');
                        if($uaddMark->num_rows()>0){ ?>
                      <div class="col-6 col-md-6 col-lg-6">
                        <a href="<?php echo base_url(); ?>addstudentresult/">
                          <button class="card card-body bg-info btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> Add Result
                          </button>
                        </a>
                      </div>

                      <div class="col-6 col-md-6 col-lg-6">
                        <a href="<?php echo base_url(); ?>editstudentmark/">
                          <button class="card card-body bg-warning btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> Edit Result
                          </button>
                        </a>
                      </div>
                      <?php } ?>
                      <?php $this->db->where('usergroup',$_SESSION['usertype']);
                      $this->db->where('tableName','StudentMark');
                      $this->db->where('allowed','viewstudentmark');
                      $this->db->order_by('id','ASC');
                      $uaddMark=$this->db->get('usergrouppermission'); 
                       if($uaddMark->num_rows()>0){ ?>
                      <div class="col-6 col-md-6 col-lg-6">
                        <a href="<?php echo base_url(); ?>studentresult/">
                          <button class="card card-body bg-primary btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> View Result
                          </button>
                        </a>
                      </div>
                      <div class="col-6 col-md-6 col-lg-6">
                        <a href="<?php echo base_url(); ?>studentmarkanalysis/">
                          <button class="card card-body bg-success btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> Mark Analysis
                          </button>
                        </a>
                      </div>
                      <?php } ?>
                      <?php  $this->db->where('usergroup',$_SESSION['usertype']);
                        $this->db->where('tableName','Attendance');
                        $this->db->where('allowed','studentAttendance');
                        $this->db->order_by('id','ASC');
                        $userPerStuAtt=$this->db->get('usergrouppermission');
                      if($userPerStuAtt->num_rows()>0) { ?>
                      <div class="col-6 col-md-6 col-lg-6">
                        <a href="<?php echo base_url(); ?>mystudentattendance/">
                          <button class="card card-body bg-secondary btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> Student Attendance
                          </button>
                        </a>
                      </div>
                      <?php } ?>
                      <?php $this->db->where('usergroup',$_SESSION['usertype']);
                        $this->db->where('allowed','summerclass');
                        $this->db->order_by('id','ASC');
                        $usergroupPermission=$this->db->get('usergrouppermission');
                      if($usergroupPermission->num_rows()>0){ ?>
                       <div class="col-6 col-md-6 col-lg-6">
                        <a href="<?php echo base_url(); ?>mysummerclass/">
                          <button class="card card-body bg-danger btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> Summer Class
                          </button>
                        </a>
                      </div>
                      <?php } ?>
                      <?php $this->db->where('usergroup',$_SESSION['usertype']);
                      $this->db->where('tableName','CommunicationBook');
                      $this->db->where('allowed','sendcommunicationbook');
                      $this->db->order_by('id','ASC');
                      $usergroupPermission=$this->db->get('usergrouppermission');
                      if($usergroupPermission->num_rows()>0){ ?>
                       <div class="col-6 col-md-6 col-lg-6">
                        <a href="<?php echo base_url(); ?>Communicationbookteacher/">
                          <button class="card card-body bg-info btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> Communication Book
                          </button>
                        </a>
                      </div>
                      <?php } ?>
                      <?php $this->db->where('usergroup',$_SESSION['usertype']);
                      $this->db->where('allowed','Chat');
                      $this->db->order_by('id','ASC');
                      $usergroupPermission=$this->db->get('usergrouppermission');
                      if($usergroupPermission->num_rows()>0){ ?>
                       <div class="col-6 col-md-6 col-lg-6">
                        <a href="<?php echo base_url(); ?>messagecompose/">
                          <button class="card card-body bg-primary btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> Compose Message
                          </button>
                        </a>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>

              </div>
              <div class="col-12 col-md-6 col-lg-6 order-md-1">
                <?php $this->db->where('usergroup',$_SESSION['usertype']);
                    $this->db->where('tableName','HomepagePost');
                    $this->db->where('allowed','postInfoTPhoto');
                    $this->db->order_by('id','ASC');
                    $usergroupPermission=$this->db->get('usergrouppermission');
                if($usergroupPermission->num_rows()>0){ ?>
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <div class="row">
                      
                      <div class="col-lg-12 col-md-12 col-12">
                        <button  class="btn btn-info btn-md btn-block" data-toggle="modal" data-target=".postPictureOrImage"> <h4 class="card-header">Post Feed <i class="fas fa-plus-circle"></i></h4> </button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php } ?>
                <div id="load_data"></div>
                <div id="load_data_message"></div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade postPoll" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myLargeModalLabel">Create Polls</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form name="add_name" id="add_name" class="StudentViewTextInfo"> 
            <div class="table-responscive">  
              <table class="table table-striped" id="dynamic_field">  
                <tr>  
                  <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                      <input type="text" id="name_question" name="name_question" placeholder="Enter Question" class="form-control name_question" required />
                    </div>
                  </div>
                </tr>
                <tr> 
                  <td><input type="text" id="name_option1" name="name_option[]" placeholder="Enter choice 1" class="form-control name_list" required /></td> 
                </tr>
                <tr>
                  <td><input type="text" id="name_option2" name="name_option[]" placeholder="Enter choice 2" class="form-control name_list" required /></td> 
                  <td><button type="button" name="add" id="add" class="btn btn-outline-success">Add Choice</button></td>  
                </tr>  
                <tr>
                  
                </tr>
              </table> 
              <div class="row">
                <div class="col-lg-6 col-md-6 col-6 form-group">
                  <label>Poll Length</label>
                    <input type="date" id="poll_length" name="poll_length" class="form-control" required>
                </div>
                <div class="col-lg-6 col-md-6 col-6 form-group">
                  <label>Who can vote?</label>
                    <select class="custom-select" required="required" name="who_can_vote" id="who_can_vote">
                      <option>Public</option>
                      <?php foreach($usergroup->result() as $usergroups){ ?>
                      <option> <?php echo $usergroups->uname; ?></option>
                      <?php } ?>
                    </select>
                </div>
              </div> 
              <input type="button" name="submit" id="submit" class="btn btn-info pull-right" value="Submit Poll" />  
            </div>  
          </form> 
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade postPictureOrImage" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myLargeModalLabel">Post Information,Questions,News...</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="postForm" method="POST" class="StudentViewTextInfo">
            <div class="chat-box StudentViewTextInfo card">
              <div class="row">
                <div class="col-12 col-md-6 col-lg-6 form-group">
                  <input type="text" class="form-control" id="postTitle" name="postTitle" placeholder="Post title...">
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <select class="custom-select" required="required" name="postAudience" id="postAudience">
                    <option>Public</option>
                    <?php foreach($usergroup->result() as $usergroups){ ?>
                    <option> <?php echo $usergroups->uname; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-12 col-md-12 col-lg-12">
                  <input type="file" name="postPicture" id="postPicture"/>
                    <textarea class="" rows="4" cols="100" wrap="physical" name="post_text" id="post_text" placeholder="Post news,meeting announcement or IQ questions..." style="width:100%; height:100px;"></textarea>  
                  </div>
                  <div class="col-12 col-md-12 col-lg-12">                 
                    <button class="btn btn-info pull-right sendPostFeed" value=""> Post Feed</button>
                  </div>
                </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade editPictureOrImageTeacher" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myLargeModalLabel">Edit Post</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="fetch_post_to_edit"></div>          
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/glightbox.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/swiper-bundle.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
  <script src="<?php echo base_url(); ?>assets/summernote/summernote-bs4.js"></script>
  <script>
    $('.teacher-notice-board').addClass('active');
    $(document).ready(function(){  
      $(document).on('click', '#btn_more', function(){  
       var last_video_id = $(this).attr("value");  
       var category_id = $(this).attr("name"); 
        $('.btn_more' + category_id).hide();
        $('.loding' + category_id).show();
       $.ajax({  
        url: "<?php echo base_url(); ?>replaycomment/fetch_feeds_comments/",
        method:"POST",  
         data: ({
          last_video_id: last_video_id,
          category_id:category_id
        }), 
        dataType:"text",  
        beforeSend: function() {
          $('.btn_more' + category_id).html("Loading...");
        },
        success:function(data)  
        {  
         if(data != '')  
         {
          $('.newcommentload_here' + category_id).append(data); 
         }  
         else  
         {  
          $('.loding' + category_id).hide();
          $('.btn_more' +category_id).html("No Data");  
         }  
        }  
       });  
      });  
    });   
    $(document).on('click', '.edit_this_post', function() { 
      var post_id = $(this).attr("id");
      $.ajax({
        method: "GET",
        url: "<?php echo base_url(); ?>home/fetch_postdata_to_edit/",
        data: ({
          post_id: post_id
        }),
        cache: false,
        beforeSend: function() {
          $('.fetch_post_to_edit').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html) {
          $(".fetch_post_to_edit" ).html(html);
        }
      });
    });
    $(document).on('click', '.updatesendPostFeed', function() { 
      var post_title = $("#updatepostTitle").val();
      var updatepostAudience = $("#updatepostAudience").val();
      var updatepost_text = $("#updatepost_text").val();
      var updated_pid=$("#updated_pid").val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>home/update_postdata_to_edit/",
        data: ({
          post_title: post_title,
          updatepostAudience:updatepostAudience,
          updatepost_text:updatepost_text,
          updated_pid:updated_pid
        }),
        cache: false,
        beforeSend: function() {
          $('.fetch_post_to_edit').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html) {
          $('.fetchUpdatedData' +updated_pid ).html(html);
            iziToast.success({
              title: 'Post updated successfully',
              message: '',
              position: 'topRight'
            });
          $('.editPictureOrImageTeacher'). modal('hide');
        }
      });
    });
  </script>
  <script>  
    $(document).on('click', '.delete_poll_post', function() { 
      var post_id = $(this).attr("id");
       swal({
          title: 'Are you sure you want to delete this Poll Post ?',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "GET",
            url: "<?php echo base_url(); ?>post_polls/delete_poll_post/",
            data: ({
              post_id: post_id
            }),
            cache: false,
            success: function(html) {
              $("#groupName" + post_id).fadeOut('slow');
            }
          });
        }
      });
    });
    $(document).on('click', '#voteThisPoll', function() {
      var pid = $(this).attr("value");
      var poll_group=$(this).attr("name");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>post_polls/submit_poll/",
        data: ({
          pid: pid,
          poll_group:poll_group
        }),
        cache: false,
        success: function(html) {
          $("#load_poll_data").html(html);
        }
      });
    });
    $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){  
        i++;  
        $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" id="name_option" name="name_option[]" placeholder="Enter choice" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
        var button_id = $(this).attr("id");   
        $('#row'+button_id+'').remove();  
      });  
      $(document).on('click', '#submit', function(event) {  
        if($("#name_option1").val()!='' && $("#name_option2").val()!='' && $("#name_question").val()!=''){          
          $.ajax({  
            url:"<?php echo base_url(); ?>post_polls",  
            method:"POST",  
            data:$('#add_name').serialize(),  
            success:function(data)  
            { 
              $('.postPoll'). modal('hide');
              $('#load_poll_data').html(data);
              $('#add_name')[0].reset();  
            }  
          });
        }else{
          swal({
            title: 'Oooops Please enter at least two Choices.',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
        }  
      });  
    });  
 </script>
  <script>
  $(document).ready(function(){
    var limit = 3;
    var start = 0;
    var limitPoll = 3;
    var startPOll = 0;
    var action = 'inactive';
    var action1 = 'inactive';
    function lazzy_loader(limit)
    {
      var output = '';
      for(var count=0; count<limit; count++)
      {
        output += '<div class="post_data">';
        output += '<p><span class="content-placeholder" style="width:100%; height: 30px;">&nbsp;</span></p>';
        output += '<p><span class="content-placeholder" style="width:100%; height: 100px;">&nbsp;</span></p>';
        output += '</div>';
      }
      $('#load_data_message').html(output);
    }
     function lazzy_loader_poll(limitPoll)
    {
      var output = '';
      for(var count=0; count<limitPoll; count++)
      {
        output += '<div class="post_data">';
        output += '<p><span class="content-placeholder" style="width:100%; height: 30px;">&nbsp;</span></p>';
        output += '<p><span class="content-placeholder" style="width:100%; height: 100px;">&nbsp;</span></p>';
        output += '</div>';
      }
      $('#load_poll_data_message').html(output);
    }
    lazzy_loader_poll(limitPoll);
    lazzy_loader(limit);
    function load_poll_data(limitPoll, startPOll)
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Post_polls/fetch_poll_data",
        method:"POST",
        data:{limit:limitPoll, start:startPOll},
        cache: false,
        success:function(data)
        {
          if(data == '')
          {
            $('#load_poll_data_message').html('<small></small>');
            action1 = 'active';
          }
          else
          {
            $('#load_poll_data').append(data);
            $('#load_poll_data_message').html("");
            action1 = 'inactive';
          }
        }
      })
    }
    function load_data(limit, start)
    {
      $.ajax({
        url:"<?php echo base_url(); ?>home/fetch_feeds",
        method:"POST",
        data:{limit:limit, start:start},
        cache: false,
        success:function(data)
        {
          $('#load_data').append(data);
          if(data == '')
          {
            $('#load_data_message').html('<small>No more post found</small>');
            action = 'active';
          }
          else
          {
            $('#load_data_message').html("Please wait...");
            action = 'inactive';
          }
        }
      })
    }

    if(action == 'inactive')
    {
      action = 'active';
      load_data(limit, start);
    }
     if(action1 == 'inactive')
    {
      action1 = 'active';
      load_poll_data(limitPoll, startPOll);
    }

    $(window).scroll(function(){
      if($(window).scrollTop() + $(window).height() > $("#load_data").height() && action == 'inactive')
      {
        lazzy_loader(limit);
        action = 'active';
        start = start + limit;
        setTimeout(function(){
          load_data(limit, start);
        }, 1000);
      }
    });
    $(window).scroll(function(){
      if($(window).scrollTop() + $(window).height() > $("#load_poll_data").height() && action1 == 'inactive')
      {
        lazzy_loader_poll(limitPoll);
        action1 = 'active';
        startPOll = startPOll + limitPoll;
        setTimeout(function(){
          load_poll_data(limitPoll, startPOll);
        }, 1000);
      }
    });
  });
</script>
  <script type="text/javascript">
  $(document).ready(function(){
     $(document).on('submit', '#postForm', function(event) {  
      event.preventDefault();  
      if (!$.trim($("#post_text").val()) && $("#postPicture").val()=='' ) {
        swal('Oooops, Please write something on the provided field!', {
          icon: 'warning',
        });
      }else{
        if($("#postPicture").val()!=''){
          var file = document.getElementById("postPicture");
          var size = parseFloat(file.files[0].size);
          var maxSizeKB = 3072; //Size in KB.
          var maxSize = maxSizeKB * 1024;
          if (size > maxSize) {
            swal('Oooops, Maximum file size should be 3MB!', {
              icon: 'warning',
            });
          }else{
            var form_data = $(this).serialize();
            $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>home/postFeed/",
              data:new FormData(this),
              processData:false,
              contentType:false,
              cache: false,
              async:false,
              success: function(data) {
                $('#load_data').html(data);
                iziToast.success({
                  title: 'Post submitted successfully',
                  message: '',
                  position: 'topRight'
                });
                $('#postForm')[0].reset();
                $('#post_text').summernote('reset');
                $('.postPictureOrImage'). modal('hide');
                $("#post_text").val('');
                $("#postTitle").val('');
                $("#postPicture").val('');
              }
            });
          }
        }else{
          var form_data = $(this).serialize();
            $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>home/postFeed/",
              data:new FormData(this),
              processData:false,
              contentType:false,
              cache: false,
              async:false,
              success: function(data) {
                $('#load_data').html(data);
                iziToast.success({
                  title: 'Post submitted successfully',
                  message: '',
                  position: 'topRight'
                });
                $('#postForm')[0].reset();
                $('#post_text').summernote('reset');
                $('.postPictureOrImage'). modal('hide');
                $("#post_text").val('');
                $("#postTitle").val('');
                $("#postPicture").val('');
              }
            });
        }
      }
    });
  });
    $(document).on('click', '.deletepost', function() { 
      var post_id = $(this).attr("id");
       swal({
          title: 'Are you sure you want to delete this Post ?',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "GET",
            url: "<?php echo base_url(); ?>home",
            data: ({
              post_id: post_id
            }),
            cache: false,
            success: function(html) {
              $(".delete_postFeed" + post_id).fadeOut('slow');
            }
          });
        }
      });
    });
  </script>
  <script type="text/javascript">
    $(document).on('click', '.sendMyReplyComment', function() {
      var comID = $(this).attr("value");
      var replyText=$("#replayCommentText"+comID).val();
      if($("#replayCommentText"+comID).val() !='' ){
        swal({
          title: 'Are you sure?',
          text: 'Once You send,you can not edit or delete the reply text.',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $.ajax({
              method: "POST",
              url: "<?php echo base_url(); ?>Replaycomment/",
              data: ({
                comID: comID,
                replyText:replyText
              }),
              cache: false,
              success: function(html) {
                $(".list_comments"+comID).html(html);
                $("#replayCommentText"+comID).val('');
              }
            });
          }
        });
      }
    });
    $(document).ready(function(){
      checkQuarterEnd();
      function checkQuarterEnd()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>checkQuarterEndDate/",
          method:"POST",
          dataType:'json',
          success:function(data){
            if(data.data1<='30' && data.data1 >='0'){
              if(data.data2 >='0'){
                iziToast.show({
                  title: data.data1 +' Days and '+ data.data2 +' Hours has left for this season.',
                  message: '',
                  position: 'topRight'
                });
              }else{
                iziToast.show({
                  title: data.data1 +' Days and '+ data.data2 +' Hours passed to end season.',
                  message: '',
                  position: 'topRight'
                });
              }  
            }
          }
        })
      }
    });
  </script>
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
  $(document).on('click','.like',function(e){
    e.preventDefault();
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
        var typeLikes = data['likesTypes'];
        $(".count-likes_" + like_id).text(likes); 
        if(typeLikes == 1){
          $("#like_"+like_id).css("color","red");
        }if(typeLikes == 0){
          $("#like_"+like_id).css("color","black");
        }
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
    function Fetch_my_personal_unseen_notification(view = '') { 
      $.ajax({
        url: "<?php echo base_url() ?>Fetch_my_personal_unseen_notification/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType: "json",
        success: function(data) {
          $('.my-notification-show').html(data.notification);
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
    Fetch_my_personal_unseen_notification();
    inbox_unseen_notification();
    $(document).on('click', '.my-notification-show', function() {
        $('.count-new-notification').html('');
        Fetch_my_personal_unseen_notification('yes');
    });
    $(document).on('click', '.seen', function() {
        $('.count-new-inbox').html('');
        inbox_unseen_notification('yes');
    });
    setInterval(function() {
      Fetch_my_personal_unseen_notification();
      inbox_unseen_notification();
    }, 10000);

  });
</script>
</body>

</html>