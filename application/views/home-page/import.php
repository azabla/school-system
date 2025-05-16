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
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
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
                  <div class="card-body StudentViewTextInfo">
                    <h4 class="card-header">Import From Excel</h4>
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#importFiles" role="tab" aria-selected="true"> Import Files</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#importZipedFiles" role="tab" aria-selected="false">Import Student Zip Photo</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="importFiles" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="gs_infoPage"></div>
                        <?php if(isset($_SESSION['success'])){ ?>
                        <span class="text-success">
                          <?php echo $_SESSION['success']; 
                          $this->session->unset_userdata ( 'success' );?>
                        </span>
                        <?php  }
                        else if(isset($_SESSION['error'])) { ?>
                        <span class="text-danger">
                            <?php echo $_SESSION['error']; 
                            $this->session->unset_userdata ( 'error' );?>  
                        </span>
                        <?php } ?>
                        <form method="POST" action="<?php echo base_url();?>import/" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-lg-4 col-12">
                              <div class="form-group">
                                <select class="form-control custom-select importCustomStudentData"
                                 required="required" name="imsubject" id="inputGroupSelect04">>
                                  <option></option>
                                  <option value="student">Import Regular Student </option>
                                  <option value="evaluation">Import Evaluation </option>
                                  <option value="subject">Import Subject </option>
                                  <option value="staffs">Import Staff </option>
                                  <option value="remoteStudent">Import Non-regular Student </option>
                                  <option value="updateStaffsDivision">Update Staff Division</option>
                                  <option value="attendance">Import Attendance </option>
                                  <option value="updateStudentInformation">Update Student Information </option>
                                  <option value="updateStudentMobile">Update Student Mobile </option>
                                  <option value="updateStudentEmail">Update Student Email </option>
                                  <option value="updateTransportService">Update Student Transport Service </option>
                                  <option value="updateStudentBranch">Update Student Branch </option>
                                  <option value="updateStudentGender">Update Student Gender </option>
                                  <option value="updateStudentDOB">Update Student DOB </option>
                                  <option value="updateStudentAge">Update Student Age </option>
                                  <option value="updateStaffPayroll">Update Staff Payroll </option>
                                  <option value="importBookRegistration">Import Library Book Data </option>
                                  <option value="importInventoryRegistration">Import Inventory Data </option>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-4 col-7">
                            <div class="form-group">
                              <input type="file" name="importwhat" id="addmark"/>
                              </div>
                            </div>
                            <div class="col-lg-4 col-5">
                              <button type="submit" name="importmater" id="importmater" class="btn btn-primary btn-block"> Import & Save
                              </button>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="tab-pane fade show" id="importZipedFiles" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="dropdown-divider"></div>
                        <div class="row">
                          <div class="col-lg-3 col-6">
                            <div class="form-group">
                                <input type="file" name="importwhat" id="fileUpload"/>              
                            </div>
                          </div>
                          <div class="col-lg-3 col-12">
                            <button type="submit" name="importmater" id="uploadData" class="btn btn-primary btn-block" onclick="uploadFile()"> 
                                Upload & Save </button>
                          </div>
                        </div>
                        <div class="progress">
                          <div class="progress-bar" id="progressBar"></div>
                        </div>
                        <div id="uploadStatus"></div>
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
</body>
<script type="text/javascript">
  $(document).ready(function() {  
    $(".importCustomStudentData").bind("change", function() {
      var dataFile=$(".importCustomStudentData").val();
      if(dataFile=='updateStudentDOB'){
        $(".gs_infoPage").html('<span class="text-danger">Please import only Student ID under "A" column and DOB under "B" column with format dd/mm/yyyy or yyyy/mm/dd .<span>');
      }else if(dataFile=='updateStudentAge'){
        $(".gs_infoPage").html('<span class="text-danger">Please import only Student ID under "A" column and Age under "B" column.<span>');
      }else if(dataFile=='updateStudentGender'){
        $(".gs_infoPage").html('<span class="text-danger">Please import only Student ID under "A" column and Gender under "B" column.<span>');
      }else if(dataFile=='updateStudentBranch'){
        $(".gs_infoPage").html('<span class="text-danger">Please import only Student ID under "A" column and Branch under "B" column.<span>');
      }else if(dataFile=='updateTransportService'){
        $(".gs_infoPage").html('<span class="text-danger">Please import only Student ID under "A" column and Transport place under "B" column.<span>');
      }else if(dataFile=='updateStudentMobile'){
        $(".gs_infoPage").html('<span class="text-danger">Please import only Student ID under "A" column, Mother Mobile under "B" column and Father Mobile under "C" column.<span>');
      }else if(dataFile=='updateStudentEmail'){
        $(".gs_infoPage").html('<span class="text-danger">Please import only Student ID under "A" column, Email under "B" column and Optional email under "C" column.<span>');
      }else if(dataFile=='updateStudentInformation'){
        $(".gs_infoPage").html('<span class="text-danger">Please import all student information according to regular student excel form.<span>');
      }else if(dataFile=='updateStaffPayroll'){
        $(".gs_infoPage").html('<span class="text-danger">Please import all necessary staff payroll information according to staff payroll form.<span>');
      }else if(dataFile=='updateStaffsDivision'){
        $(".gs_infoPage").html('<span class="text-danger">Please import only Staff username under "A" column and Division under "B" column.<span>');
      }else{
        $(".gs_infoPage").html('<span class="text-danger">Please import all necessary information according to excel form.</span>');
      }
    });
  });
  function uploadFile() {
    var fileInput = document.getElementById('fileUpload');
    var file = fileInput.files[0];
    if (file) {
      var formData = new FormData();
      formData.append('file', file);
      var xhr = new XMLHttpRequest();
      xhr.upload.addEventListener('progress', function (event) {
          if (event.lengthComputable) {
              var percent = Math.round((event.loaded / event.total) * 100);
              var progressBar = document.getElementById('progressBar');
              progressBar.style.width = percent + '%';
              progressBar.innerHTML = percent + '%';
          }
      });
      document.getElementById("uploadData").disabled = true; 
      uploadStatus.innerHTML =  'Uploading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">';
      xhr.addEventListener('load', function (event) {
          var uploadStatus = document.getElementById('uploadStatus');
          uploadStatus.innerHTML = event.target.responseText;
          document.getElementById("uploadData").disabled = false; 
      });
      xhr.open('POST', '<?php echo base_url();?>import/upload_data', true);
      xhr.send(formData);
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