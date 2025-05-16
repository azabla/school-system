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
            <div class="row">
              <div class="col-12">
                <?php if(isset($_SESSION['success'])){ ?>
                <span class="text-success">
                    <?php echo $_SESSION['success']; ?>
                </span>
                <?php  }
                elseif(isset($_SESSION['error'])) { ?>
                <span class="text-danger">
                  <?php echo $_SESSION['error']; ?>  
                </span>
              <?php } ?>
              <?php include('bgcolor.php'); ?>
              <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card">
              <div class="card-header">
                <h4>Approve New Result</h4>
              </div>
              <div class="card-header">
                <form method="POST" id="comment_form">
                    <div class="row">
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="groupbranch" id="groupbranch">
                           <option></option>
                          <?php foreach ($branch as $branchs) { ?>
                           <option value="<?php echo $branchs->name;  ?>"><?php echo $branchs->name; ?></option>
                          <?php  } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="gradesec" id="gradesec">
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control subject"
                            name="subject">
                            <option>--- Select Subject ---</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="quarter"  id="quarter">
                          <option>--- Quarter ---</option>
                            <?php foreach($fetch_term as $fetch_terms){ ?>
                              <option value="<?php echo $fetch_terms->term;?>">
                              <?php echo $fetch_terms->term;?>
                              </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-12">
                        <button class="btn btn-primary btn-block btn-lg" 
                        type="submit" name="viewmark">View</button>
                      </div>
                    </div>
                  </form> 
                  <div id="listmark" class="listmark"></div>
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
</body>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#gradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#gradesec").val(),
         beforeSend: function() {
          $('#quarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#quarter").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#groupbranch").on("change", function() {
    var branch = $("#groupbranch").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>approvemark/Filter_grade_from_branch",
        data: ({
          branch: branch
        }),
        beforeSend: function() {
          $('#gradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradesec").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#gradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Approvemark/Filtersubjectfromstaff/",
        data: "gradesec=" + $("#gradesec").val(),
        beforeSend: function() {
            $('.subject').html( 'Loading Mark...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".subject").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var gs_gradesec=$('#gradesec').val();
    var gs_subject=$('.subject').val();
    var branch=$('#groupbranch').val();
    var gs_quarter=$('#quarter').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Approvemark/Fecth_grademark_4teacher/",
        method: "POST",
        data: ({
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject,
          gs_quarter:gs_quarter,
          branch:branch
        }),
        beforeSend: function() {
          $('.listmark').html( '<h3>Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="84" height="84" id="loa"></h3>' );
        },
        success: function(data) {
          $(".listmark").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<script>
  $(document).on('click', '.gs_Approve_markname', function() {
    swal({
      title: 'Are you sure you want to approve this mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        var subject=$(".jo_subject").val();
        var gradesec=$(".jo_gradesec").val();
        var branch=$(".jo_branch").val();
        var year=$(".jo_year").val();
        var quarter=$(".jo_quarter").val();
        var markname=$(this).attr("value");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Approvemark/approveMarkName/",
          data: ({
            subject: subject,
            gradesec:gradesec,
            branch:branch,
            quarter:quarter,
            year:year,
            markname: markname
          }),
          cache: false,
          beforeSend: function() {
            $('.listmark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(html){
            $('.listmark').html(html);
          }
        }); 
      }
    });
  });
</script>
<!-- edit mark ends -->
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
    /*function fetchNewMark(view = '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Approvemark/fetchNewMark4Approval/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType:"json",
        success: function(data) {
          $('.approve-mark-notification-show').html(data.notification);
          if (data.unseen_notification > 0) {
            $('.count-new-notification').html(data.unseen_notification);
          }
        }
      });
    }*/
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
          $('.namees').html(data.notification);
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
    /*fetchNewMark();*/
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
      /*fetchNewMark();*/
      unseen_notification();
      users_online();
      inbox_unseen_notification();
    }, 5000);

  });
</script>
</html>