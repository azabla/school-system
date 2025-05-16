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
                  <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="card">

                        <div class="card-header">
                          <h4>Exam Scheduler</h4>
                        </div>
                        <div class="card-body">
                          <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#dayInfo" role="tab" aria-controls="dayInfo" aria-selected="true"> <h5 class="card-title">Day Info</h5></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="home-tab2" data-toggle="tab" href="#subInfo" role="tab" aria-controls="subInfo" aria-selected="false"> <h5 class="card-title">Subject Info</h5></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="home-tab3" data-toggle="tab" href="#noExam" role="tab" aria-controls="noExam" aria-selected="false"> <h5 class="card-title">No. of exam/day</h5></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="home-tab4" data-toggle="tab" href="#includeLunch" role="tab" aria-controls="includeLunch" aria-selected="false"> <h5 class="card-title">Include Lunch Time</h5></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="home-tab5" data-toggle="tab" href="#includeBreak" role="tab" aria-controls="includeBreak" aria-selected="false"> <h5 class="card-title">Include Break Time</h5></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="home-tab6" data-toggle="tab" href="#finishTab" role="tab" aria-controls="finish" aria-selected="false"> <h5 class="card-title">Finish</h5></a>
                            </li>
                          </ul>
                          <form id="wizard_with_validation" class="doExamSchedule" method="POST">
                          <div class="tab-content tab-bordered" id="myTab3Content">
                            <div class="tab-pane fade show active" id="dayInfo" role="tabpanel" aria-labelledby="home-tab1">
                            <fieldset>
                              <div class="form-group form-float table-responsive" style="height:30vh;">
                                <div class="form-line">
                                  <label class="form-label">Please select the days that exam may run on. *</label>
                                  <div class="row">
                                    <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6">
                                      <option id="All">All</option>
                                      <div class="pretty p-icon p-jelly p-round p-bigger">
                                        <input type="checkbox" name="examDays" onClick="selectDay()" value="" id="selectAllDays">
                                        <div class="state p-info">
                                          <i class="icon material-icons"></i>
                                          <label></label>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6">
                                      <option id="All">Monday</option>
                                      <div class="pretty p-icon p-jelly p-round p-bigger">
                                        <input type="checkbox" name="examDay" value="Monday" id="customCheck1">
                                        <div class="state p-info">
                                          <i class="icon material-icons"></i>
                                          <label></label>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6">
                                      <option id="All">Tuesday</option>
                                      <div class="pretty p-icon p-jelly p-round p-bigger">
                                        <input type="checkbox" name="examDay" value="Tuesday" id="customCheck1">
                                        <div class="state p-info">
                                          <i class="icon material-icons"></i>
                                          <label></label>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6">
                                      <option id="All">Wednesday</option>
                                      <div class="pretty p-icon p-jelly p-round p-bigger">
                                        <input type="checkbox" name="examDay" value="Wednesday" id="customCheck1">
                                        <div class="state p-info">
                                          <i class="icon material-icons"></i>
                                          <label></label>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6">
                                      <option id="All">Thursday</option>
                                      <div class="pretty p-icon p-jelly p-round p-bigger">
                                        <input type="checkbox" name="examDay" value="Thursday" id="customCheck1">
                                        <div class="state p-info">
                                          <i class="icon material-icons"></i>
                                          <label></label>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6">
                                      <option id="All">Friday</option>
                                      <div class="pretty p-icon p-jelly p-round p-bigger">
                                        <input type="checkbox" name="examDay" value="Friday" id="customCheck1">
                                        <div class="state p-info">
                                          <i class="icon material-icons"></i>
                                          <label></label>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6">
                                      <option id="All">Saturday</option>
                                      <div class="pretty p-icon p-jelly p-round p-bigger">
                                        <input type="checkbox" name="examDay" value="Saturday" id="customCheck1">
                                        <div class="state p-info">
                                          <i class="icon material-icons"></i>
                                          <label></label>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6">
                                      <option id="All">Sunday</option>
                                      <div class="pretty p-icon p-jelly p-round p-bigger">
                                        <input type="checkbox" name="examDay" value="Sunday" id="customCheck1">
                                        <div class="state p-info">
                                          <i class="icon material-icons"></i>
                                          <label></label>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </fieldset>
                            </div>
                            <div class="tab-pane fade" id="subInfo" role="tabpanel" aria-labelledby="home-tab2">
                            <fieldset>
                              <div class="form-group form-float table-responsive" style="height:30vh;">
                                <div class="form-line">
                                  <label class="form-label">Select subject to exam *</label>
                                  <div class="row">
                                    <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6">
                                      <option id="AllSub">All</option>
                                      <div class="pretty p-icon p-jelly p-round p-bigger">
                                        <input type="checkbox" name="examSubjects" onClick="selectSubject()" value="" id="selectAllSubjects">
                                        <div class="state p-info">
                                          <i class="icon material-icons"></i>
                                          <label></label>
                                        </div>
                                      </div>
                                    </div>
                                    <?php foreach($subject as $subjects){ ?>
                                    <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6">
                                      <option id="All"><?php echo $subjects->Subj_name ?></option>
                                      <div class="pretty p-icon p-jelly p-round p-bigger">
                                        <input type="checkbox" name="examSubject" value="<?php echo $subjects->Subj_name ?>" id="selectAllSubjects">
                                        <div class="state p-info">
                                          <i class="icon material-icons"></i>
                                          <label></label>
                                        </div>
                                      </div>
                                    </div>
                                    <?php } ?>
                                  </div>
                                </div>
                              </div>
                            </fieldset>
                            </div>
                            <div class="tab-pane fade" id="noExam" role="tabpanel" aria-labelledby="home-tab3">
                              <fieldset>
                                <label for="subject">Please input number of subject to exam in one day. *</label>
                                <input id="subjectPerDay" name="subjectPerDay" type="number" class="form-control" required>
                              </fieldset>
                            </div>
                            <div class="tab-pane fade" id="includeLunch" role="tabpanel" aria-labelledby="home-tab4">
                              <fieldset>
                                Yes <input id="doLunch" value="1" type="checkbox"> 
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                No <input id="doLunch" value="0" type="checkbox"> 
                              </fieldset>
                            </div>
                            <div class="tab-pane fade" id="includeBreak" role="tabpanel" aria-labelledby="home-tab5">
                              <div class="row">
                                <div class="col-md-12">
                                  <fieldset>
                                    Yes <input id="doBreak" value="1" type="checkbox">
                                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    No <input id="doBreak" value="0" type="checkbox"> 
                                  </fieldset>
                                </div>
                              </div>
                            </div>
                            <div class="tab-pane fade" id="finishTab" role="tabpanel" aria-labelledby="home-tab6">
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="btn-toolbar pull-center">
                                    <div class="btn-group">
                                      <button class="btn btn-primary saveExamSchedule" type="submit" name="saveExamSchedule"> Generate Exam Schedule</button>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="generateInfo"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                          </form>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="btn-toolbar pull-right">
                                <div class="btn-group">
                                  <button class="btn btn-default prevtab" data-direction="previous" data-target="#myTab"><span class="fas fa-arrow-left" aria-hidden="true"></span> Previous</button>
                                  <button class="btn btn-default nexttab" data-direction="next" data-target="#myTab">Next <span class="fas fa-arrow-right" aria-hidden="true"></span>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
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
      <footer class="main-footer">
        <div class="footer-left">
         Call:+251967829025 &nbsp;&nbsp;
          Copyright &copy<?php echo date('Y');?>
          <a href="https://www.grandstande.com" target="_blanck">GrandStand IT Solution Plc</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
</body>
<script type="text/javascript">
  function bootstrapTabControl(){
  var i, items = $('.nav-link'), pane = $('.tab-pane');
  $('.nexttab').on('click', function(){
    for(i = 0; i < items.length; i++){
        if($(items[i]).hasClass('active') == true){
            break;
        }
    }
    if(i < items.length - 1){
        // for tab
        $(items[i]).removeClass('active');
        $(items[i+1]).tab('show');
        // for pane
        $(pane[i]).removeClass('show active');
        $(pane[i+1]).tab('show active');
    }
  });
  // Prev
  $('.prevtab').on('click', function(){
    for(i = 0; i < items.length; i++){
        if($(items[i]).hasClass('active') == true){
            break;
        }
    }
    if(i != 0){
        // for tab
        $(items[i]).removeClass('active');
        $(items[i-1]).tab('show');
        // for pane
        $(pane[i]).removeClass('show active');
        $(pane[i-1]).tab('show active');
    }
  });
}
bootstrapTabControl();

</script>
<script type="text/javascript">
  $('.saveExamSchedule').on('click', function(event) {
      event.preventDefault();
      var noExams=$('#subjectPerDay').val();
      var doLunch=$('#doLunch').val();
      var doBreak=$('#doBreak').val();
      subjectGrade=[];
      dayInfo=[];
      $("input[name='examSubject']:checked").each(function(i){
        subjectGrade[i]=$(this).val();
      });
      $("input[name='examDay']:checked").each(function(i){
        dayInfo[i]=$(this).val();
      });
      if(subjectGrade.length == 0 || dayInfo.length == 0)
      {
        alert("Oooops, Please select necessary fields.");
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Examscheduler/doExamSchedule/",
        data: ({
          dayInfo:dayInfo,
          subjectGrade: subjectGrade,
          noExams:noExams,
          doLunch:doLunch,
          doBreak:doBreak
        }),
        cache: false,
        beforeSend: function() {
          $('.generateInfo').html( 'Please wait...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.generateInfo').html(html);
        }
      });
    }
  });
</script>
<script type="text/javascript">
    function selectDay(){
      var itemsall=document.getElementById('selectAllDays');
      if(itemsall.checked==true){
        var items=document.getElementsByName('examDay');
        for(var i=0;i < items.length;i++){
          items[i].checked=true;
        }
      }
      else{
        var items=document.getElementsByName('examDay');
        for(var i=0;i < items.length;i++){
          items[i].checked=false;
        }
      }
    }
    function selectSubject(){
      var itemsall=document.getElementById('selectAllSubjects');
      if(itemsall.checked==true){
        var items=document.getElementsByName('examSubject');
        for(var i=0;i < items.length;i++){
          items[i].checked=true;
        }
      }
      else{
        var items=document.getElementsByName('examSubject');
        for(var i=0;i < items.length;i++){
          items[i].checked=false;
        }
      }
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
</html>