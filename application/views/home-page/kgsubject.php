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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
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
            <div class="row">
              <div class="col-12">
             <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card card-header">
              <form method="POST" id="saveNewSubject">
                <div class="row">
                  <div class="col-lg-3 col-6">
                    <div class="form-group">
                      <label for="Mobile">Subject Name</label>
                      <input class="form-control subjectName" id="subjectName" required="required" type="text" placeholder="KG subject name here...">
                    </div>
                  </div>
                  <div class="col-lg-7 col-6">
                     <label for="Mobile"><h6>Grade</h6></label>
                     <div class="row">
                    <?php foreach($grade as $grades){ ?>
                    <div class="col-lg-5 col-6">
                      <div class="form-group">
                      <?php echo $grades->grade; ?>
                      <div class="pretty p-icon p-jelly p-round p-bigger">
                      <input type="checkbox" name="subjectGrade" value="<?php echo $grades->grade; ?>" id="customCheck1 subjectGrade">
                      <div class="state p-info">
                        <i class="icon material-icons"></i>
                        <label></label>
                      </div>
                     </div>
                     #
                     <div class="pretty p-icon p-jelly p-round p-bigger">
                      <input type="checkbox" name="subjectLetter" value="#" id="customCheck1 subjectLetter">
                      <div class="state p-info">
                        <i class="icon material-icons"></i>
                        <label></label>
                      </div>
                     </div>
                     A
                     <div class="pretty p-icon p-jelly p-round p-bigger">
                      <input type="checkbox" name="subjectLetter" value="A" id="customCheck1 subjectLetter">
                      <div class="state p-info">
                        <i class="icon material-icons"></i>
                        <label></label>
                      </div>
                     </div>
                    </div>
                    <hr>
                  </div>
                  <?php } ?>
                </div>
                </div>
                <div class="col-lg-2 col-12">
                  <div class="form-group">
                    <button type="submit" name="post" class="btn btn-primary btn-block btn-sm"> Save
                    </button>
                  </div>
                </div>
              </div>
            </form>
            <div class="subjectList" id="subjecttshere"></div>
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
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
   $(document).on('change', '.kgsubOrderJ', function() {
    var suborder=$(this).find('option:selected').attr('value');
    var subject=$(this).find('option:selected').attr('id');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>kgsubject/updateSubjectOrder/",
        data: ({
          suborder:suborder,
          subject:subject
        }),
        success: function(data) {
          iziToast.success({
            title: 'Subject Order',
            message: 'Updated successfully',
            position: 'topRight'
          });
        }
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    loadSubjectData();
    function loadSubjectData()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>kgsubject/fetchSubject/",
        method:"POST",
        beforeSend: function() {
          $('.subjectList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="84" height="84" id="loa">');
        },
        success:function(data){
          $('.subjectList').html(data);
        }
      })
    }
    $('#saveNewSubject').on('submit', function(event) {
      event.preventDefault();
      var subjectName=$('#subjectName').val();
      /*var onReportCard=$('#onReportCard').val();*/
      subjectGrade=[];subjectLetter=[];
      $("input[name='subjectGrade']:checked").each(function(i){
        subjectGrade[i]=$(this).val();
      });
      $("input[name='subjectLetter']:checked").each(function(i){
        subjectLetter[i]=$(this).val();
      });
      if( subjectGrade.length == 0 || $('#subjectName').val() =='')
      {
        alert("Oooops, Please select necessary fields.");
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>kgsubject/saveNewSubject/",
        data: ({
          subjectName: subjectName,
          subjectGrade:subjectGrade,
          subjectLetter:subjectLetter
        }),
        cache: false,
        success: function(html){
          $('#saveNewSubject')[0].reset();
          loadSubjectData();
        }
      });
    }
  });
  $(document).on('click', '.backToSubject', function()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>kgsubject/fetchSubject/",
      method:"POST",
      beforeSend: function() {
        $('.subjectList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="84" height="84" id="loa">');
      },
      success:function(data){
        $('.subjectList').html(data);
      }
    })
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

<!-- edit subject script -->
<script type="text/javascript">
  $(document).on('click', '.changeme', function()
  {
    var gradejoss=$(this).attr("value");
    var letterjoss=$(this).attr("name");
    var subjjoss=$(this).attr("id");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>kgsubject/updateSubjectForLetter/",
      data: ({
        gradejoss: gradejoss,
        letterjoss: letterjoss,
        subjjoss:subjjoss
      }),
      cache: false,
      beforeSend: function() {
        $('.gr' + gradejoss).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
        );
      },
      success: function(html){
        $('.gr' + gradejoss).html(html);
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '.dele', function()
  {
    var gradename=$(this).attr("name");
    var subjname=$(this).attr("value");
    if(confirm('Are you susre you want to delete this subject')){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>kgsubject/deleteOneSubject/",
        data: ({
          gradename: gradename,
          subjname: subjname
        }),
        cache: false,
        beforeSend: function() {
          $('#deletee' + subjname + gradename).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
          );
        },
        success: function(html){
         $('#deletee' + subjname + gradename).fadeOut('slow');
        }
      });
    }
  });
</script>
<script>
  $(document).on('click', '.editSubject', function(){
    var edtisub=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>kgsubject/fetchSubjectToEdit/",
      data: ({
        edtisub: edtisub
      }),
      cache: false,
      beforeSend: function() {
        $('.subjectList').html( '<h3>Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="84" height="84" id="loa"></h3>'
          );
      },
      success: function(html){
        $('.subjectList').html(html);
      }
    });
  });
  function loadSubjectData()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>kgsubject/fetchSubject/",
      method:"POST",
      beforeSend: function() {
        $('.subjectList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="84" height="84" id="loa">');
      },
      success:function(data){
        $('.subjectList').html(data);
      }
    })
  }
  $(document).on('submit', '.saveSubjectChanges', function(){
    var newsubjName=$('#newSubjName').val();
    var oldsubjName=$('#oldSubjName').val();
    var percent=$('#oldSubjPercent').val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>kgsubject/updateSubjectName/",
      data: ({
        newsubjName: newsubjName,
        oldsubjName:oldsubjName,
        percent:percent
      }),
      cache: false,
      beforeSend: function() {
        $('.subjectList').html( '<h3>Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="84" height="84" id="loa"></h3>'
          );
      },
      success: function(html){
        loadSubjectData();
      }
    });
  });
</script>
<!-- edit subject ends -->
<script>
  $(document).on('click', '.changeOnRp', function(){
    var subject=$(this).attr("id");
    var grade=$(this).attr("value");
    var onreportcard=$(this).attr("name");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>kgsubject/onreportcard/",
      data: ({
        onreportcard: onreportcard,
        subject: subject,
        grade:grade
      }),
      cache: false,
      beforeSend: function() {
        $('.gr' + onreportcard).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      success: function(html){
        $('.gr' + onreportcard).html(html);
      }
    });
  });
</script>
<script>
  function loadSubjectData()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>kgsubject/fetchSubject/",
      method:"POST",
      beforeSend: function() {
        $('.subjectList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="84" height="84" id="loa">');
      },
      success:function(data){
        $('.subjectList').html(data);
      }
    })
  }
  $(document).on('click', '.deletesubject', function(){
    var post_id = $(this).attr("id");
    if (confirm("Are you sure you want to delete this Subject ?")) {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>kgsubject/subjectDelete/",
        data: ({
          post_id: post_id
        }),
        cache: false,
        success: function(html) {
         loadSubjectData();
        }
      });
    }else {
      return false;
    }
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
</html>