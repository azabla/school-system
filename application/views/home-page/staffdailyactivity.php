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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
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
            <div class="row">
              <div class="col-12">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#tasksReport" role="tab" aria-selected="false">Tasks Report</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab1" data-toggle="tab" href="#newTaskForm" role="tab" aria-selected="true">Tasks</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="tasksReport" role="tabpanel" aria-labelledby="home-tab2">
                    <!-- <?php if($taskslist->num_rows()>0){ $no=1;
                    foreach($taskslist->result() as $row){  ?>
                      <button class="btn btn-light fetch_thistaskUsers" type="button" data-toggle="collapse" data-target="#no<?php echo $no; ?>" aria-expanded="false" aria-controls="collapseExample" name="<?php echo $no ?>" value="<?php echo $row->task_name ?>">
                        <?php echo $row->task_name ?>
                      </button>
                    <div class="collapse" id="no<?php echo $no ?>"> </div>
                    <?php $no++; } } else{ ?>
                      <div class="alert alert-light alert-dismissible show fade">
                        <div class="alert-body">
                        <i class="fas fa-check-circle"> </i> No tasks found.
                      </div></div>
                    <?php } ?> -->
                    <div class="fetch_all_tasks_page"></div>
                  </div>
                  <div class="tab-pane fade show" id="newTaskForm" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button type="submit" name="addnew" class="btn btn-outline-primary pull-right" data-toggle="modal" data-target="#newTaskFormModal" > <i data-feather="plus-circle"></i> Add New Task
                        </button>
                        <div class="dropdown-divider"></div>
                      </div>
                      <div class="col-lg-12 col-12">
                        <input type="text" name="searchTask" id="searchTask" class="form-control typeahead" placeholder="Search Task (Name,Users)...">
                        <div id="fetchTasks"> </div>
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
  <div class="modal fade" id="newTaskFormModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="taskMsg">New Task</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <form method="POST" id="newTask">
            <div class="row">
              <div class="form-group col-lg-3 col-6">
                <label>Task Name</label>
                <input type="text" class="form-control" name="taskName" placeholder="Type task name..." required>
              </div>
              <div class="form-group col-lg-3 col-6">
                <label>Task For</label>
                <select class="form-control selectric" name="whoseTask" id="whoseTask" required="required">
                <option> Select User </option>
                <?php foreach($usertype->result() as $usergroups){ ?>
                <option> <?php echo $usergroups->uname; ?></option>
                <?php } ?>
                </select>
              </div>
              <div class="form-group col-lg-3 col-6">
                <label>Task Type</label>
                <select class="form-control selectric" name="taskType" id="taskType" required="required">
                  <option> Task Type </option>                  
                  <option>Daily</option>
                  <option>Weekly</option>
                  <option>Monthly</option>
                </select>
              </div>
              <div class="form-group col-lg-3 col-6">
                 <label>Due Date</label>
                <input type="date" class="form-control" name="dueDate" placeholder="Type task name...">
              </div>
            </div>
            <button class="btn btn-primary pull-right" type="submit" id="saveTasks"> Save Tasks </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="editTaskFormModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="taskMsgEdit">Edit Task</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="modal-body" id="fetch_edit_Task"></div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click', '#delete_this_my_report_sent', function() {
      swal({
        title: 'Are you sure?',
        text: 'Once deleted,you can not recover this report!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          var reportID = $(this).attr("value");
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>staffdailyactivity/deleteSingleidentReport/",
            data: ({
              reportID:reportID
            }),
            cache: false,
            success: function(html) {
              load_mysent_report();
            }
          });
        }
      });
    });
  
    load_mysent_report();
    function load_mysent_report()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>staffdailyactivity/fetch_my_userstasks_report/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_all_tasks_page').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.fetch_all_tasks_page').html(data);
        }
      })
    }
    $(document).on('click', '.fetch_thistaskUsers', function() {
      var taskName=$(this).attr("value");
      var id=$(this).attr("name");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>staffdailyactivity/fetch_usersTask_progress/",
        data: ({
          taskName: taskName,
          id:id
        }),
        cache: false,
        beforeSend: function() {
          $('#no' + id).html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('#no' + id).html(html);
        }
      }); 
    });

    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>staffdailyactivity/fetchTasks/",
        method:"POST",
        beforeSend: function() {
          $('#fetchTasks').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#fetchTasks').html(data);
        }
      })
    }
    $(document).on('click', '#editThisTask', function() {
      var taskName=$(this).attr("value");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>staffdailyactivity/editThisTaskName/",
        data: ({
          taskName: taskName
        }),
        cache: false,
        beforeSend: function() {
          $('#fetch_edit_Task').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('#fetch_edit_Task').html(html);
        }
      }); 
    });
    $(document).on('click', '#deleteThisTask', function() {
      swal({
        title: 'Are you sure?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
      if (willDelete) {
        var taskName=$(this).attr("value");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>staffdailyactivity/deleteTaskName/",
          data: ({
            taskName: taskName
          }),
          cache: false,
          beforeSend: function() {
            $('.deletedTask' + taskName).html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(html){
            $('.deletedTask' + taskName).fadeOut('slow');
          }
        }); 
      }
      });
    });
    $(document).on('submit', '#submiteditedTask', function(e) {
      e.preventDefault();
      if ($('#editedtaskName').val() != '' && $('#editedwhoseTask').val() != 'Select User') {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>staffdailyactivity/saveEditedTasks/",
          data:new FormData(this),
          processData:false,
          contentType:false,
          cache: false,
          async:false,
          beforeSend: function() {
            $('#taskMsgEdit').html( '<span class="text-info">Updating...</span>');
          },
          success: function(html){
            $("#taskMsgEdit").html(html);
            load_data();
          }
        });
      }else{
        swal('Please fill all fields!', {
          icon: 'error',
        });
      }
    });
    $(document).on('submit', '#newTask', function(e) {
      e.preventDefault();
      if ($('#taskName').val() != '' && $('#whoseTask').val() != 'Select User' && $('#taskType').val() != 'Task Type') {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>staffdailyactivity/saveNewTasks/",
          data:new FormData(this),
          processData:false,
          contentType:false,
          cache: false,
          async:false,
          beforeSend: function() {
            $('#taskMsg').html( '<span class="text-info">Saving...</span>');
          },
          success: function(html){
             $("#taskMsg").html(html);
             load_data();
          }
        });
      }else{
        swal('Please fill all fields!', {
          icon: 'error',
        });
      }
    });
    $(document).on('keyup', '#searchTask', function(e) {
      $searchItem=$('#searchTask').val();
      if($('#searchTask').val()!=''){
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>staffdailyactivity/searchTasks/",
          data: "searchItem=" + $("#searchTask").val(),
          beforeSend: function() {
            $('#fetchTasks').html( 'Searching...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $("#fetchTasks").html(data);
          }
        });
      }else{
        load_data();
      }
    });
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("fetchTasks");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
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
</html>