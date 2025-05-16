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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/summernote/summernote-bs4.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>
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
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#myReport" role="tab" aria-selected="true">My Report</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab3" data-toggle="tab" href="#myTasks" role="tab" aria-selected="true">My Tasks</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab2" data-toggle="tab" href="#savedDocuments" role="tab" aria-selected="false">Saved Documents</a>
              </li>
            </ul>
            <div class="tab-content tab-bordered" id="myTab3Content">
              <div class="tab-pane fade show active" id="myReport" role="tabpanel" aria-labelledby="home-tab1">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-12">
                    <button type="submit" name="addnewsend_new_my_task_report" id="addnewsend_new_my_task_report" class="btn btn-outline-primary pull-right" data-toggle="modal" data-target="#send_new_my_task_report" > <i data-feather="plus-circle"></i> New Report </button>
                  </div>
                  <div class="col-lg-12 col-md-12 col-12">
                    <div class="my_report_page" id="my_report_page"></div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade show" id="myTasks" role="tabpanel" aria-labelledby="home-tab3">
                <div class="my_Tasks" id="my_Tasks"></div>
              </div>
              <div class="tab-pane fade show" id="savedDocuments" role="tabpanel" aria-labelledby="home-tab2">
                <form id="mydocument" method="POST">
                  <div class="row">
                    <div class="col-lg-7 col-7">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="mydoc" name="mydoc">
                        <label class="custom-file-label" for="customFile">Select file to save</label>
                      </div>
                    </div>
                    <div class="col-lg-5 col-5">
                      <button type="submit"  name="post" class="btn btn-primary btn-block m-t-15 waves-effect">Save File</button>
                    </div>
                  </div>
                </form>
                <div class="mydocuments"></div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="send_new_my_task_report" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="taskMsg">New Task Report</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="new_submit_report_page"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="doTaskPage" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="taskMsgEdit">Do Task</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="modal-body" id="fetch_form_Todo_Task"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal_edit_this_my_report_sent" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="taskMsg">New Task Report</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="modal_new_submit_edit_page"></div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/summernote/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script type="text/javascript">
    $(document).on('click', '#edit_this_my_report_sent', function() {
      var rid=$(this).attr('value');
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>documents/fetch_this_report_toedt/",
        data: ({
          rid: rid
        }),
        cache: false,
        beforeSend: function() {
          $('.modal_new_submit_edit_page').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html) {
          $(".modal_new_submit_edit_page").html(html);
        }
      });
    });
    $(document).on('click', '#addnewsend_new_my_task_report', function() {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>documents/new_submit_report_page/",
        cache: false,
        beforeSend: function() {
          $('.new_submit_report_page').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html) {
          $(".new_submit_report_page").html(html);
        }
      });
    });
    $(document).on('click', '#doThisTask', function() {
      var taskName=$(this).attr("value");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>documents/fetchThisTaskName/",
        data: ({
          taskName: taskName
        }),
        cache: false,
        beforeSend: function() {
          $('#fetch_form_Todo_Task').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('#fetch_form_Todo_Task').html(html);
        }
      }); 
    });
    $(document).on('click', '#submitTask', function() {
      var taskText = $('#taskText').val();
      var TaskName=$(this).attr("value");
      if( $('#taskText').val()!='') 
      {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>documents/submitTask/",
          data: ({
            taskText: taskText,
            TaskName:TaskName
          }),
          cache: false,
          success: function(html) {
            $("#fetch_form_Todo_Task").html(html);
          }
        });
      }else {
       swal('Please type your response on the provided field!', {
          icon: 'error',
        });
      }
    });
    $(document).ready(function(){
        load_tasks();
        load_data();
        load_mysent_report();
        function load_mysent_report()
        {
          $.ajax({
            url:"<?php echo base_url(); ?>documents/fetch_my_tasks_report/",
            method:"POST",
            beforeSend: function() {
              $('.my_report_page').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
            },
            success:function(data){
              $('.my_report_page').html(data);
            }
          })
        }
        $(document).on('submit', '#submit_this_newReport', function(e) {
          e.preventDefault();
          var reportvalue = jQuery.trim($("#my_Report_detail").val());
          if (reportvalue.length  == 0  && $('#importReportFile_gs').val()=='') {
            swal('Please fill all fields!', {
              icon: 'error',
            });
          }else{
            $.ajax({
              method: "POST",
              url: "<?php echo base_url(); ?>documents/submit_this_newReport/",
              data:new FormData(this),
              processData:false,
              contentType:false,
              cache: false,
              async:false,
              beforeSend: function() {
                $('#savemy_Report_detail').html( '<span class="text-info">Submitting...</span>');
              },
              success: function(html){
                if(html=='1'){
                  toastr.success("", "Report submitted successfully.");
                  load_mysent_report();
                  $("#savemy_Report_detail").html('Submit Report');
                  $("#send_new_my_task_report").modal('hide');
                }else if(html=='3'){
                  $("#savemy_Report_detail").html('Submit Report');
                  toastr.error("", "Report name found. Please try with different report title.");
                }else if(html =='5'){
                  $("#savemy_Report_detail").html('Submit Report');
                  toastr.error("", "Oooops no immediate user found. Please contact your IT admin");
                }else{
                  $("#savemy_Report_detail").html('Submit Report');
                  toastr.error("", "Oooops unable to submit report. Please try later");
                }
              }
            });
          }
        });
        $(document).on('submit', '#submit_this_updateReport', function(e) {
          e.preventDefault();
          var reportvalue = jQuery.trim($("#my_Report_detail_update").val());
          if (reportvalue.length  == 0  && $('#my_Report_Name_update').val()=='') {
            swal('Please fill all fields!', {
              icon: 'error',
            });
          }else{
            $.ajax({
              method: "POST",
              url: "<?php echo base_url(); ?>documents/submit_updated_this_newReport/",
              data:new FormData(this),
              processData:false,
              contentType:false,
              cache: false,
              async:false,
              beforeSend: function() {
                $('#updatemy_Report_detail').html( '<span class="text-info">Updating...</span>');
              },
              success: function(html){
                if(html=='1'){
                  toastr.success("", "Report updated successfully.");
                  load_mysent_report();
                  $("#updatemy_Report_detail").html('Update Report');
                  $("#modal_edit_this_my_report_sent").modal('hide');
                }else if(html=='4'){
                  $("#updatemy_Report_detail").html('Update Report');
                  toastr.error("", "Report title found. Please try with different report title.");
                }else if(html =='5'){
                  $("#updatemy_Report_detail").html('Update Report');
                  toastr.error("", "No changes found.");
                }else{
                  $("#updatemy_Report_detail").html('Update Report');
                  toastr.error("", "Oooops unable to update report. Please try later");
                }
              }
            });
          }
        });
        $(document).on('click', '#remove_this_report_file', function(e) {
          e.preventDefault();
          swal({
            title: 'Are you sure you want to remove this file?',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              var removedId=$(this).attr('value');
              $.ajax({
                method: "POST",
                url: "<?php echo base_url(); ?>documents/remove_this_report_file/",
                data: ({
                  removedId: removedId
                }),
                cache: false,
                beforeSend: function() {
                  $('#remove_this_report_file' + removedId).html( '<span class="text-info">Removing...</span>');
                },
                success: function(html){
                  if(html=='1'){
                    toastr.success("", "Report updated successfully.");
                    $("#gs_remove_file_info" + removedId).fadeOut('slow');
                  }else if(html=='4'){
                    $("#remove_this_report_file").html('Remove');
                    toastr.error("", "Report title found. Please try with different report title.");
                  }else if(html =='5'){
                    $("#remove_this_report_file").html('Remove');
                    toastr.error("", "No changes found.");
                  }else{
                    $("#remove_this_report_file").html('Remove');
                    toastr.error("", "Oooops unable to update report. Please try later");
                  }
                }
              });
            }
          });
        });
        
        function load_tasks()
        {
          $.ajax({
            url:"<?php echo base_url(); ?>documents/fetch_my_tasks/",
            method:"POST",
            beforeSend: function() {
              $('.my_Tasks').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
            },
            success:function(data){
              $('.my_Tasks').html(data);
            }
          })
        }
        function load_data()
        {
          $.ajax({
            url:"<?php echo base_url(); ?>documents/fetchdocuments/",
            method:"POST",
            beforeSend: function() {
              $('.mydocuments').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
            },
            success:function(data){
              $('.mydocuments').html(data);
            }
          })
        }
        $('#mydocument').on('submit', function(e) {
          e.preventDefault();
          if($('#mydoc').val() =='')
          {
            alert("Oooops, Please select your file.");
          }else{
            $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>documents/postdocuments/",
            data:new FormData(this),
            processData:false,
            contentType:false,
            cache: false,
            async:false,
            success: function(html){
              $('#mydocument')[0].reset();
              load_data();
            }
          });
        }
      });
    });
  </script>
  <script>
  $(document).ready(function() {
     $(document).on('click', '.deletemydocument', function() {
      var id = $(this).attr("id");
      if (confirm("Are you sure you want to delete this file permanently ?")) 
      {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>documents/Deletedocuments",
          data: ({
            id: id
          }),
          cache: false,
          success: function(html) {
            $(".deletedocument" + id).fadeOut('slow');
          }
        });
      }else {
        return false;
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
</body>

</html>