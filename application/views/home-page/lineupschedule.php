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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
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
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="header-title">LineUp Schedule Page</h5>
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#feedSubject" role="tab" aria-selected="true"> Generate LineUp Schedule</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#feedMergedSubject" role="tab" aria-selected="false">Print Schedule</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="feedSubject" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row StudentViewTextInfo"> 
                          <div class="col-lg-3 col-12">
                            <div class="form-group">
                             <select class="form-control selectric" required="required" name="scheduleBranch" id="scheduleBranch">
                             <option>--- Branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                          </div>
                          <div class="col-lg-6 col-12">
                            <button class="btn btn-default"> Monday 
                              <input type="checkbox" name="customCheck1" value="Monday" class="timetableDay" id="customCheck1">
                            </button>
                            <button class="btn btn-default"> Tuesday 
                              <input type="checkbox" name="customCheck1" value="Tuesday" class="timetableDay" id="customCheck1">
                            </button>
                            <button class="btn btn-default"> Wednesday 
                              <input type="checkbox" name="customCheck1" value="Wednesday" class="timetableDay" id="customCheck1">
                            </button>
                            <button class="btn btn-default"> Thursday 
                              <input type="checkbox" name="customCheck1" value="Thursday" class="timetableDay" id="customCheck1">
                            </button>
                            <button class="btn btn-default"> Friday 
                              <input type="checkbox" name="customCheck1" value="Friday" class="timetableDay" id="customCheck1">
                            </button>
                            <button class="btn btn-default"> Saturday 
                              <input type="checkbox" name="customCheck1" value="Saturday" class="timetableDay" id="customCheck1">
                            </button>
                            <button class="btn btn-default"> Sunday 
                              <input type="checkbox" name="customCheck1" value="Sunday" class="timetableDay" id="customCheck1">
                            </button>
                          </div>
                          <div class="col-lg-3 col-4">
                            Include Director's ? <input type="checkbox" id="includeDirectors" name="includeDirectors">
                          </div>
                          <div class="col-lg-12 col-8">
                            <button type="submit" name="GenerateLineUpSchedule" id="GenerateLineUpSchedule" class="btn btn-primary btn-block">Generate LineUp</button>
                          </div>
                        </div>
                        <div class="table-responsive" style="height: 45vh;">
                          <div id="ViewGenerateLineUpSchedule" > </div>
                        </div>
                      </div>
                      <div class="tab-pane fade show" id="feedMergedSubject" role="tabpanel" aria-labelledby="home-tab2">
                        <button class="btn btn-primary btn-sm pull-right" name="gethisreport" onclick="codespeedy()">  <i class="fas fa-print"></i>  print   
                        </button>
                        <div class="row">
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                             <select class="form-control selectric" required="required" name="branch" id="branchit">
                             <option>--- Branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                          </div>
                          <div class="col-lg-4 col-6">
                            <select class="form-control" name="selectPrintType" id="selectPrintType" required>
                              <option> </option>
                              <option value="printTeacher"> Schedule for each Teacher</option>
                              <option value="printDate"> Schedule for each Day/Date</option>
                            </select>
                          </div>
                          <div class="col-lg-4 col-12">
                            <button class="btn btn-primary btn-block" type="submit" id="printScheduleItem"> View</button>
                          </div>
                        </div>
                        <div class="table-responsive" style="height: 45vh;">
                          <div id="printGeneratedLineUpSchedule" > </div>
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
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("printGeneratedLineUpSchedule");
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
  $(document).ready(function(){
    $('#printScheduleItem').on('click', function(event) {
      event.preventDefault();
      var printType=$('#selectPrintType').val();
      var branchit =$('#branchit').val();
      if( $('#selectPrintType').val() == '' && $('#branchit').val())
      {
        swal('Oooops, Please select necessary fields!', {
          icon: 'warning',
        });
      }
      else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>lineupschedule/printScheduleItem/",
          data: ({
            printType: printType,
            branchit:branchit
          }),
          cache: false,
          beforeSend: function() {
            $('#printGeneratedLineUpSchedule').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
          },
          success: function(html){
            $('#printGeneratedLineUpSchedule').html(html);
          }
        }); 
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>lineupschedule/fetchLineupschedule/",
        method:"POST",
        beforeSend: function() {
          $('#ViewGenerateLineUpSchedule').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success:function(data){
          $('#ViewGenerateLineUpSchedule').html(data);
        }
      })
    }
    $('#GenerateLineUpSchedule').on('click', function(event) {
      event.preventDefault();
      var scheduleDays=$('#customCheck1').val();
      var includeDirectors=$('#includeDirectors').val();
      var scheduleBranch=$('#scheduleBranch').val();
      scheduleDays=[];
      $("input[name='customCheck1']:checked").each(function(i){
        scheduleDays[i]=$(this).val();
      });
      if( scheduleDays.length == 0)
      {
        swal('Oooops, Please select necessary fields!', {
          icon: 'warning',
        });
      }
      else{
        swal('LineUp schedule generated successfully!', {
          icon: 'success',
        });
        if($('#includeDirectors').is(':checked')){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>lineupschedule/postLineupscheduleAll/",
            data: ({
              scheduleDays: scheduleDays,
              includeDirectors:includeDirectors,
              scheduleBranch:scheduleBranch
            }),
            cache: false,
            success: function(html){
              load_data();
            }
          });
        }else{
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>lineupschedule/postLineupscheduleTeacher/",
            data: ({
              scheduleDays: scheduleDays,
              includeDirectors:includeDirectors,
              scheduleBranch:scheduleBranch
            }),
            cache: false,
            success: function(html){
              load_data();
            }
          });
        }
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