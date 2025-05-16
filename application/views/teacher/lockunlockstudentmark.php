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
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#LockStudentMark" role="tab" aria-selected="true">Lock Student Mark</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#LockSectionMark" role="tab" aria-selected="false">Lock Section Mark</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="LockStudentMark" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row">
                          <div class="col-md-12 col-12">
                            <input type="text" name="searchStudent" id="searchStudent" class="form-control typeahead" placeholder="Search student to lock mark...">
                          </div>
                        </div>
                        <div class="StudentList" id="StudentList"></div>
                      </div>

                      <div class="tab-pane fade show" id="LockSectionMark" role="tabpanel" aria-labelledby="home-tab2">
                        <?php $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='lockstudentmark' order by id ASC ");
                        $uperStuDEu=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='unlockstudentmark' order by id ASC "); 
                        if($uperStuDE->num_rows() >0 || $uperStuDEu->num_rows() >0){?>
                        <div class="row">
                          <div class="col-lg-6 col-6">
                            <div class="form-group">
                              <div class="row">
                                <?php 
                                if($_SESSION['usertype']===trim('Director')){
                                foreach($gradesec as $gradesecs){ ?>
                                  <div class="col-lg-4 col-6">
                                    <input type="checkbox" class="gradesescListForLockMarkList" name="gradesescListForLockMarkList[ ]" value="<?php echo $gradesecs->grade ?>"/><?php echo $gradesecs->grade ?>
                                    <small id="locksectionInfo<?php echo $gradesecs->grade ?>"></small>
                                    </div>
                                <?php } }else{ 
                                  foreach($gradesecTeacher as $gradesecs){ ?>
                                  <div class="col-lg-4 col-6">
                                    <input type="checkbox" class="gradesescListForLockMarkList" name="gradesescListForLockMarkList[ ]" value="<?php echo $gradesecs->grade ?>"/><?php echo $gradesecs->grade ?>
                                    <small id="locksectionInfo<?php echo $gradesecs->grade ?>"></small>
                                  </div>
                                <?php } }?>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-6 col-6">
                            <?php if($uperStuDE->num_rows() >0){ ?>
                            <button class="btn btn-primary btn-block" type="submit" id="LockThisGradesecMark" name="viewmark">Lock</button>
                            <?php } ?>

                            <span class="lockGradesecInfo"></span>
                            <div class="dropdown-divider"></div>
                            <?php  
                            if($uperStuDEu->num_rows() >0) { ?>
                            <button class="btn btn-warning btn-block" type="submit" id="UnlockThisGradesecMark" name="viewmark">UnLock</button>
                            <?php } ?>
                            <span class="UnlockGradesecInfo"></span>
                          </div>
                        </div>
                      <?php }else{ ?>
                        <span class="text-danger"> No Permission to lock/unlock mark.</span>
                      <?php }?>
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
</body>
<script type="text/javascript">
  $(document).on('click', '#LockThisGradesecMark', function(){
    gradesec=[];
    $("input[name='gradesescListForLockMarkList[ ]']:checked").each(function(i){
      gradesec[i]=$(this).val();
    });
    swal({
      title: 'Are you sure you want to lock this section mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })

    .then((willDelete) => {
      if (willDelete) {
        if(gradesec.length!=0){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>lockunlockmystudentmark/lockThisSectionMark/",
            data: ({
              gradesec:gradesec
            }),
            cache: false,
            beforeSend: function() {
              $('.lockGradesecInfo').html( 'Locking.<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
                );
            },
            success: function(html){
              $(".lockGradesecInfo").html('Locked successfully <i class="fas fa-lock"></i>');
              swal('Locked successfully!', {
                icon: 'success',
              });
            },
            error: function(html){
              swal('Oooops Please try later.', {
                icon: 'error',
              });
            }
          });
        }else{
          swal('Oooops Please select all necessary fields.', {
            icon: 'error',
          });
        }
      }
    });
  });
  $(document).on('click', '#UnlockThisGradesecMark', function(){
    gradesec=[];
    $("input[name='gradesescListForLockMarkList[ ]']:checked").each(function(i){
      gradesec[i]=$(this).val();
    });
    swal({
      title: 'Are you sure you want to unlock this section mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })

    .then((willDelete) => {
      if (willDelete) {
        if(gradesec.length!=0){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>lockunlockmystudentmark/UnlockThisSectionMark/",
            data: ({
              gradesec:gradesec
            }),
            cache: false,
            beforeSend: function() {
              $('.UnlockGradesecInfo').html( 'Locking.<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
                );
            },
            success: function(html){
              $(".UnlockGradesecInfo").html('Unlocked successfully <i class="fas fa-unlock"></i>');
              swal('Unlocked successfully!', {
                icon: 'success',
              });
            },
            error: function(html){
              swal('Oooops Please try later.', {
                icon: 'error',
              });
            }
          });
        }else{
          swal('Oooops Please select all necessary fields.', {
            icon: 'error',
          });
        }
      }
    });
  });
  $(document).ready(function() { 
    $('#searchStudent').on("keyup",function() {
      $searchItem=$('#searchStudent').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>lockunlockmystudentmark/searchStudent/",
        data: "searchItem=" + $("#searchStudent").val(),
        beforeSend: function() {
          $('.StudentList').html( 'Searching...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(data) {
          $(".StudentList").html(data);
        }
      });
    });
  });
  $(document).on('click', '.lockThisStudentMark', function(){
    var stuID=$(this).attr("value");
    swal({
      title: 'Are you sure you want to lock this student mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>lockunlockmystudentmark/lockThisStudentMark/",
          data: ({
            stuID: stuID
          }),
          cache: false,
          beforeSend: function() {
            $('#lockThisStudentMark' + stuID).html( 'Locking.<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
              );
          },
          success: function(html){
            $(".lockStudent" + stuID).fadeOut('slow');
            swal('Locked successfully!', {
              icon: 'success',
            });
          }
        });
      }
    });
  });
  $(document).on('click', '.unlockThisStudentMark', function(){
    var stuID=$(this).attr("value");
    swal({
      title: 'Are you sure you want to unlock this student mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>lockunlockmystudentmark/unlockThisStudentMark/",
          data: ({
            stuID: stuID
          }),
          cache: false,
          beforeSend: function() {
            $('#unlockThisStudentMark' + stuID).html( 'Unlocking.<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
              );
          },
          success: function(html){
            $(".lockStudent" + stuID).fadeOut('slow');
            swal('Unlocked successfully!', {
              icon: 'success',
            });
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