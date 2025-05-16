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
            <div class="title-header">Quick Links</div>
            <div class="row">
              <?php $this->db->where('usergroup',$_SESSION['usertype']);
                    $this->db->where('tableName','StudentMark');
                    $this->db->where('allowed','addstudentmark');
                    $this->db->order_by('id','ASC');
                    $uaddMark=$this->db->get('usergrouppermission');
                if($uaddMark->num_rows()>0){ ?>
              <div class="col-6 col-md-4 col-lg-4">
                <a href="<?php echo base_url(); ?>addstudentresult/">
                  <button class="card card-body bg-info btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> Add Result
                  </button>
                </a>
              </div>
              <div class="col-6 col-md-4 col-lg-4">
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
              <div class="col-6 col-md-4 col-lg-4">
                <a href="<?php echo base_url(); ?>studentresult/">
                  <button class="card card-body bg-primary btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> View Result
                  </button>
                </a>
              </div>
              <div class="col-6 col-md-4 col-lg-4">
                <a href="<?php echo base_url(); ?>studentmarkanalysis/">
                  <button class="card card-body bg-success btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> Mark Analysis
                  </button>
                </a>
              </div>
              <?php } ?>
              <?php $this->db->where('usergroup',$_SESSION['usertype']);
                    $this->db->where('tableName','Attendance');
                    $this->db->where('allowed','studentAttendance');
                    $this->db->order_by('id','ASC');
                    $userPerStuAtt=$this->db->get('usergrouppermission');

              if($userPerStuAtt->num_rows()>0) { ?>
              <div class="col-6 col-md-4 col-lg-4">
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
               <div class="col-6 col-md-4 col-lg-4">
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
               <div class="col-6 col-md-4 col-lg-4">
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
               <div class="col-6 col-md-4 col-lg-4">
                <a href="<?php echo base_url(); ?>messagecompose/">
                  <button class="card card-body bg-primary btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> Compose Message
                  </button>
                </a>
              </div>
              <?php } ?>
              <?php $this->db->where('usergroup',$_SESSION['usertype']);
                    $this->db->where('tableName','Student');
                    $this->db->where('allowed','StudentVE');
                    $this->db->order_by('id','ASC');
                    $usergroupPermission=$this->db->get('usergrouppermission');
              if($usergroupPermission->num_rows()>0){ ?>
               <div class="col-6 col-md-4 col-lg-4">
                <a href="<?php echo base_url(); ?>mystudent/">
                  <button class="card card-body bg-warning btn-block" name="markResultStudent" id="markResultStudentFilter" value="Summer"> Student Incident Report
                  </button>
                </a>
              </div>
              <?php } ?>
            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  
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