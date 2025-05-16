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
  <script src="https://cdn.ckeditor.com/ckeditor5/35.2.1/classic/ckeditor.js"></script>
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
            <div class="card">
              <div class="card-header">
                <h5 class="header-title">Create Daily Lesson Plan</h5>
                <div class="row">
                  <div class="col-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#lpstep1" role="tab" aria-selected="true"> Create Lesson Plan(Step 1)</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab12" data-toggle="tab" href="#lpstep2" role="tab" aria-selected="true"> Create Lesson Plan(Step 2)</a>
                      </li>
                      <?php $upViewLplan=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='lessonplan' and allowed='viewlessonplan' order by id ASC ");  if($upViewLplan->num_rows()>0){?>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#feedMergedSubject" role="tab" aria-selected="false">View Lesson Plan</a>
                      </li>
                    <?php }?>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="lpstep1" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row">
                          <div class="col-md-4 col-6 form-group">
                            <label>Select Grade</label>
                            <select class="form-control" name="dailyGrade" id="dailyGrade" required>
                              <option></option>
                              <?php foreach($grade as $grades){ ?>
                                <option><?php echo $grades->grade ?></option>
                              <?php } ?>
                              
                            </select>
                          </div>
                          <div class="col-md-4 col-6 form-group">
                            <label>Select Subject</label>
                            <select class="form-control" id="dailySubject" required>
                              
                            </select>
                          </div>
                          <div class="col-md-4 col-12 form-group">
                            <label>Select Date</label>
                            <input type="date" class="form-control" name="dailyDate" id="dailyDate"> 
                          </div>
                          <div class="col-md-6 col-12 form-group">
                            <label>Fill Unit & Topic</label>
                            <input type="text" name="dailyUnitTopic" class="form-control" id="dailyUnitTopic" placeholder="Unit & Topic" required>
                          </div>
                          <div class="col-md-6 col-12 form-group">
                            <label>Lesson Topic</label>
                            <input type="text" name="lessonTopic" class="form-control" id="lessonTopic" placeholder="lesson Topic" required>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade show" id="lpstep2" role="tabpanel" aria-labelledby="home-tab12">
                        <div class="row">
                          <div class="col-md-4 col-12 form-group">
                            <label>Rationale of the topic: The lesson helps learners</label>
                            <textarea class="form-control" id="editor1" name="teachers_guide" placeholder="Rationale of the topic: The lesson helps learners..."  required="required"> </textarea>
                          </div>
                          <div class="col-md-4 col-12 form-group">
                            <label>Pre-requisite knowledge:</label>
                            <textarea class="form-control" id="editor2" name="teachers_guide" placeholder="Pre-requisite knowledge..."  required="required"> </textarea>
                          </div>
                          <div class="col-md-4 col-12 form-group">
                            <label>Competencies(Learning objectives): At the end of the lesson,the students will be able to</label>
                            <textarea class="form-control bio" id="editor3" name="teachers_guide" placeholder="At the end of the lesson,the students will be able to..."  required="required"> </textarea>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade show" id="feedMergedSubject" role="tabpanel" aria-labelledby="home-tab2">

                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="btn-toolbar pull-right">
                      <div class="btn-group">
                        <button class="btn btn-primary prevtab" data-direction="previous" data-target="#myTab"><span class="fas fa-arrow-left" aria-hidden="true"></span> Previous</button>
                        <button class="btn btn-success nexttab" data-direction="next" data-target="#myTab">Next <span class="fas fa-arrow-right" aria-hidden="true"></span>
                        </button>
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
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script>
    ClassicEditor
      .create( document.querySelector( '#editor1' ) )
      .then( editor => {
              console.log( editor );
      } )
      .catch( error => {
              console.error( error );
      } );
       ClassicEditor
      .create( document.querySelector( '#editor2' ) )
      .then( editor => {
              console.log( editor );
      } )
      .catch( error => {
              console.error( error );
      } );
       ClassicEditor
      .create( document.querySelector( '#editor3' ) )
      .then( editor => {
              console.log( editor );
      } )
      .catch( error => {
              console.error( error );
      } );
  </script>
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
    $(document).ready(function() {
      $("#dailyGrade").change(function() {
        var gradesec=$("#dailyGrade").val();
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Filter_grade_subject/",
          data: {gradesec:gradesec} ,
          success: function(data) {
            $("#dailySubject").html(data);
          }
        });
      });
    });
  </script>
  <script type="text/javascript">
    $('#lesson_plan').on('submit', function(event) {
      event.preventDefault();
      var form_data = $(this).serialize();
      $.ajax({
        url: "<?php echo base_url(); ?>addlessonplan/savelessonplan/",
        method: "POST",
        data: form_data,
        beforeSend: function() {
          $('.savelessonplaninfo').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $('#lesson_plan')[0].reset();
          $(".savelessonplaninfo").html(data);
        }
      })
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
  <script type="text/javascript">
    $(document).ready(function() {
      $("#lesson_grade").change(function() {
        var gradesec=$("#lesson_grade").val();
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Filter_grade_subject/",
          data: {gradesec:gradesec} ,
          success: function(data) {
            $("#lesson_subject").html(data);
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