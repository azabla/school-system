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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
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
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button type="submit" id="add_new_self_evaluation_form" name="add_new_self_evaluation_form" class="btn btn-primary pull-right" data-toggle="modal" data-target="#new-self-evaluation-from"> Add self-evaluation questions <i class="fas fa-plus"></i> </button>
                      </div>
                    </div>
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#self-evaluation-questions" role="tab" aria-selected="false">Self-evaluation questions</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#self-evaluation-questions-report" role="tab" aria-selected="false">Self-evaluation Report</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="self-evaluation-questions" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="self-evaluation-question-list"></div>
                      </div>
                      <div class="tab-pane fade show" id="self-evaluation-questions-report" role="tabpanel" aria-labelledby="home-tab3">
                        <div class="table-responsive" id="reportContent_GS">                 
                          <table class="display dataTable" id='empTable_Report' style="width:100%;">
                            <thead>
                             <tr>
                              <th>Staff Name</th>         
                               <th>Question</th>
                               <th>Date Submitted</th>
                              </tr>
                            </thead>
                          </table> 
                        </div>
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
  <div class="modal fade" id="new-self-evaluation-from" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="msg_item" id="msg_item">Add new self-evaluation questions</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <small class="text-danger">Please type the question and select department for it.
          Note:The same question title will be concatenated and listed together and you should not write question numbers.</small>
          <form class="submit_new_self_evaluation_question" method="POST">
            <div class="row">
              <div class="col-lg-12 col-12">
                <div class="form-group">
                  <input type="text" id="evaluation_question_title" name="evaluation_question_title" class="form-control" placeholder="Question title..." required>
                </div>
              </div>
              <div class="col-lg-12 col-12">
                <div class="form-group">
                  <textarea rows="4" cols="50" wrap="physical" name="evaluation_question_name" id="evaluation_question_name" placeholder="Write question here..." style="width:100%; height:100px;" required></textarea>
                </div>
              </div>
              <?php foreach($user_group as $user_groups){ ?>
              <div class="col-lg-4 col-12">
                <div class="form-group">
                  <div class="pretty p-icon p-smooth">
                    <input type="checkbox" name="self_evaluation_question_for" class="self_evaluation_question_for" id="self_evaluation_question_for" value="<?php echo $user_groups->uname; ?>" >
                    <div class="state p-success">
                    <i class="icon fa fa-check"></i>
                        <label></label><?php echo $user_groups->uname; ?>
                    </div>
                  </div>
                </div>
              </div>
              <?php } ?>
              <div class="col-lg-12 col-12">
                <div class="form-group">
                 <button class="btn btn-primary pull-right" type="submit" id="submit_self_evaluation_question">Save Question</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="view-detail-this-staffe-question-answer" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="msg_item" id="msg_item">Self-evaluation answer</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <button class="btn btn-default pull-right" name="gethisreport" onclick="printApprovedansweredEvaluation()"> <i class="fas fa-print"></i> </button>
        </div>
        <div class="modal-body card-header">
          <div class="fetch_detail_self_evaluation_answer" id="fetch_detail_self_evaluation_answer"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
</body>
<script type="text/javascript">
  $('.gs-sms-hr-page').addClass('active');
  function printApprovedansweredEvaluation(){
    var print_div = document.getElementById("fetch_detail_self_evaluation_answer");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  $(document).on('click', '#view_detail_this_staff_self_evaluation_answer', function() {
    var requestid=$(this).attr("value");
    var staffName=$(this).attr("class");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>staffselfevaluation/view_detail_this_staff_self_evaluation_answer/",
      data: ({
        requestid: requestid,
        staffName:staffName
      }),
      cache: false,
      beforeSend: function() {
        $('.fetch_detail_self_evaluation_answer').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.fetch_detail_self_evaluation_answer').html(html);
      }
    }); 
  });
  $(document).ready(function(){
    $('#empTable_Report').DataTable({
      'processing': true,
      'serverSide': true,
      "dataType": "json",
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>staffselfevaluation/fetch_self_evaluation_report/'
      },
      'columns': [
        { data: 'fname' },
        { data: 'question_title' },
        { data: 'date_posted' },
      ]
    });
  }); 
  load_data();
  function load_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>staffselfevaluation/fetch_self_evaluation_questions_title/",
      method:"POST",
      beforeSend: function() {
        $('.self-evaluation-question-list').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.self-evaluation-question-list').html(data);
      }
    })
  }
  $(document).on('click', '.backToquestionTitlePage', function()
  {
    load_data();
  }); 
  $(document).on('click', '#view_this_self_evaluation_titlt_question', function(e) {
    e.preventDefault();
    var question_title=$(this).attr('value');
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>staffselfevaluation/fetch_self_evaluation_questions/",
      data: {
        question_title:question_title
      },
      beforeSend: function() {
        $('.self-evaluation-question-list').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data){
        $('.self-evaluation-question-list').html( data);
      }
    });
  });
  $(document).on('submit', '.submit_new_self_evaluation_question', function(e) {
    e.preventDefault();
    department_name=[];
    $("input[name='self_evaluation_question_for']:checked").each(function(i){
      department_name[i]=$(this).val();
    });
    var self_question = $('#evaluation_question_name').val();
    var question_title= $('#evaluation_question_title').val();
    if ($("#evaluation_question_name").val()!='' && department_name.length!=0 ) {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>staffselfevaluation/submit_request/",
        data: {
          self_question:self_question,
          department_name: department_name,
          question_title:question_title
        },
        beforeSend: function() {
          $('#submit_self_evaluation_question').attr('disabled','disabled');
          $('#submit_self_evaluation_question').html( 'Submiting...' );
        },
        success: function(data){
          if(data=='1'){
            iziToast.success({
              title: 'Request submitted successfully.',
              message: '',
              position: 'topRight'
            });
            $('#evaluation_question_name').val('');
            load_data();
          }else if(data=='2'){
            iziToast.error({
              title: 'Request found.',
              message: '',
              position: 'topRight'
            });
          }else{
            iziToast.error({
              title: 'Ooops please try later.',
              message: '',
              position: 'topRight'
            });
          }
          $('#submit_self_evaluation_question').removeAttr('disabled');
          $('#submit_self_evaluation_question').html( 'Save Question' );
        }
      });
    }else{
      swal('Please select department!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '#delete_this_self_evaluation_question', function() {
    swal({
      title: 'Are you sure?',
      text: 'Once deleted you can not recover everthing regarding this question! ',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        var requestid=$(this).attr("value");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>staffselfevaluation/delete_self_evaluation_question/",
          data: ({
            requestid: requestid
          }),
          cache: false,
          beforeSend: function() {
            $('#deletingSelfEvaluationQuestion' + requestid).html( 'Deleting<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(html){
            $('#deletingSelfEvaluationQuestion' + requestid).fadeOut('slow');
          }
        }); 
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