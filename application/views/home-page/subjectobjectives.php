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
            <form method="POST" id="saveNewSubject">
              <div class="row">
                <div class="col-lg-6 col-6">
                  <div class="form-group">
                    <label for="Mobile">Objective Name</label>
                    <input class="form-control subjectName" id="subjectName" required="required" type="text" placeholder="Objective name here...">
                  </div>
                </div>
                <div class="col-lg-6 col-6 table-responsive" style="height:15vh">
                   <label for="Mobile"><h6>Grade</h6></label>
                   <div class="row">
                  <?php foreach($grade as $grades){ ?>
                  <div class="col-lg-4 col-6">
                    <div class="form-group">
                    <?php echo $grades->grade; ?>
                    <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input type="checkbox" name="subjectGrade" value="<?php echo $grades->grade; ?>" id="customCheck1 subjectGrade">
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
              
              <div class="col-lg-6 col-6 table-responsive" id="grajosstad" style="height:15vh">
                <label for="Mobile"><h6>New Subject</h6></label>
                <div class="row">
                  <?php foreach($subjects as $subject){ ?>
                  <div class="col-md-4 col-6">
                    <?php echo $subject->subname; ?>
                    <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input type="checkbox" name="osubject" value="<?php echo $subject->subname; ?>" 
                    id="customCheck1 osubject">
                    <div class="state p-info">
                      <i class="icon material-icons"></i>
                      <label></label>
                    </div>
                   </div>
                  <hr>
                </div>
                <?php } ?>
                 </div>
               </div>
                <div class="col-lg-6 col-6">
                  <label for="Mobile"><h6>Link to Old Subject</h6></label>
                  <select class="form-control" required="required" name="linksubject" id="linksubject">
                    <option></option>
                    <?php foreach($kgsubjects as $kgsubject){ ?>
                    <option value="<?php echo $kgsubject->Subj_name; ?>"><?php echo $kgsubject->Subj_name; ?></option>
                    <?php } ?>
                  </select>
                </div>
                 <div class="col-lg-12 col-12">
                  <button type="submit" name="post" class="btn btn-primary btn-block btn-sm"> Save
                  </button>
                </div>
            </div>
          </form>
          <div class="card subjectList" id="subjecttshere"></div>
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
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</body>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("subjecttshere");
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
    loadSubjectData();
    function loadSubjectData()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>subjectobjectives/fetchSubject/",
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
      var linksubject=$('#linksubject').val();
      subjectGrade=[];osubject=[];
      $("input[name='subjectGrade']:checked").each(function(i){
        subjectGrade[i]=$(this).val();
      });
      $("input[name='osubject']:checked").each(function(i){
        osubject[i]=$(this).val();
      });
      if( subjectGrade.length == 0 || $('#subjectName').val() =='')
      {
        alert("Oooops, Please select necessary fields.");
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>subjectobjectives/saveNewSubject/",
        data: ({
          subjectName: subjectName,
          subjectGrade:subjectGrade,
          osubject:osubject,
          linksubject:linksubject
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
      url:"<?php echo base_url(); ?>subjectobjectives/fetchSubject/",
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
<script type="text/javascript">
  $(document).on('click', '.dele', function()
  {
    var gradename=$(this).attr("name");
    var subjname=$(this).attr("value");
    if(confirm('Are you susre you want to delete this subject')){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>subjectobjectives/deleteOneSubject/",
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
      url: "<?php echo base_url(); ?>subjectobjectives/fetchSubjectToEdit/",
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
      url:"<?php echo base_url(); ?>subjectobjectives/fetchSubject/",
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
      url: "<?php echo base_url(); ?>subjectobjectives/updateSubjectName/",
      data: ({
        newsubjName: newsubjName,
        oldsubjName:oldsubjName
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
  function loadSubjectData()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>subjectobjectives/fetchSubject/",
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
        url: "<?php echo base_url(); ?>subjectobjectives/subjectDelete/",
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