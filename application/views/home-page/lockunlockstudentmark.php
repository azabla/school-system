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
                  <div class="card-header"> 
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#LockStudentMark" role="tab" aria-selected="true">Lock Student Mark</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab6" data-toggle="tab" href="#LockAssesmentMark" role="tab" aria-selected="false">Lock Assesment Mark</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#LockSectionMark" role="tab" aria-selected="false">Lock Section Mark</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#LockGradeMark" role="tab" aria-selected="true">Lock Grade Mark</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab4" data-toggle="tab" href="#LockBranchMark" role="tab" aria-selected="false">Lock Branch Mark</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab5" data-toggle="tab" href="#LockAllMark" role="tab" aria-selected="false">Lock All Mark</a>
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
                      <div class="tab-pane fade show" id="LockAssesmentMark" role="tabpanel" aria-labelledby="home-tab6">
                        <div class="row">
                          <div class="col-lg-2 col-6">
                              <?php foreach ($branch as $row) { ?>
                                <div class="col-lg-12 col-12">
                                <input type="radio" class="customAssesmentBranch" name="customAssesmentBranch" value="<?php echo $row->name ?>"/><?php echo $row->name ?>
                                </div>
                              <?php } ?>
                            
                          </div>
                          <div class="col-lg-3 col-6 table-responsive" style="height:30vh">
                            <div id="listGradeSecAssesment"> </div>
                          </div>
                          <div class="col-lg-2 col-6">
                            <select class="form-control" id="selectQuarterHere" name="selectQuarterHere" required></select>
                          </div>
                          <div class="col-lg-5 col-12 table-responsive" style="height:30vh">
                            <div id="listEvaluationAssesment"> </div>
                            <button class="btn btn-outline-info btn-block" id="viewGradeAssesment">View Assement</button>
                          </div>
                        </div>
                        <div class="fetchAssesmentHere"></div>
                      </div>
                      <div class="tab-pane fade show" id="LockSectionMark" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row">
                          <div class="col-lg-2 col-6">
                            <div class="form-group">
                              <?php foreach ($branch as $row) { ?>
                                <div class="col-lg-12 col-12">
                                <input type="radio" class="studentBranchName" name="studentBranchName" value="<?php echo $row->name ?>"/><?php echo $row->name ?>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="col-lg-7 col-6 table-responsive" style="height:30vh">
                            <div class="form-group" id="listBranchSection"> </div>
                          </div>
                          <div class="col-lg-3 col-12">
                          <?php $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='lockstudentmark' order by id ASC "); 
                            if($uperStuDE->num_rows() >0){ ?>
                            <button class="btn btn-primary btn-block" type="submit" id="LockThisGradesecMark" name="viewmark">Lock</button>
                            <?php } ?>
                            <span class="lockGradesecInfo"></span>
                            <div class="dropdown-divider"></div>
                            <?php $uperStuDEu=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='unlockstudentmark' order by id ASC "); 
                            if($uperStuDEu->num_rows() >0){ ?>
                              <button class="btn btn-warning btn-block" type="submit" id="UnlockThisGradesecMark" name="viewmark">UnLock</button>
                            <?php }?>
                            <span class="UnlockGradesecInfo"></span>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade show" id="LockGradeMark" role="tabpanel" aria-labelledby="home-tab3">
                        <div class="row">
                          <div class="col-lg-2 col-6">
                            <div class="form-group">
                              <?php foreach ($branch as $row) { ?>
                                <div class="col-lg-12 col-12">
                                <input type="radio" class="studentGradeBranchName" name="studentGradeBranchName" value="<?php echo $row->name ?>"/><?php echo $row->name ?>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="col-lg-7 col-6 table-responsive" style="height:30vh">
                            <div class="form-group" id="listBranchGrade"> </div>
                          </div>
                          <div class="col-lg-3 col-12">
                            <?php $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='lockstudentmark' order by id ASC "); 
                            if($uperStuDE->num_rows() >0){ ?>
                              <button class="btn btn-primary btn-block" type="submit" id="LockThisGradeMark" name="viewmark">Lock</button>
                            <?php } ?>
                            <span class="lockGradeInfo"></span>
                            <div class="dropdown-divider"></div>
                            <?php $uperStuDEu=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='unlockstudentmark' order by id ASC "); 
                            if($uperStuDEu->num_rows() >0) { ?>
                              <button class="btn btn-warning btn-block" type="submit" id="UnlockThisGradeMark" name="viewmark">UnLock</button>
                            <?php } ?>
                            <span class="UnlockGradeInfo"></span>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade show" id="LockBranchMark" role="tabpanel" aria-labelledby="home-tab4">
                        <div class="row">
                          <div class="col-lg-6 col-6">
                            <div class="form-group">
                              <?php foreach ($branch as $row) { ?>
                                <div class="col-lg-6 col-12">
                                <input type="radio" class="lockBranch" name="lockBranch" value="<?php echo $row->name ?>"/> <?php echo $row->name ?>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="col-lg-6 col-12">
                            <?php $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='lockstudentmark' order by id ASC "); 
                            if($uperStuDE->num_rows() >0){ ?>
                            <button class="btn btn-primary btn-block" type="submit" id="LockThisBranchMark" name="viewmark">Lock Branch</button>
                            <?php } ?>
                            <span class="lockBranchInfo"></span>
                            <div class="dropdown-divider"></div>
                            <?php $uperStuDEu=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='unlockstudentmark' order by id ASC "); 
                            if($uperStuDEu->num_rows() >0) { ?>
                            <button class="btn btn-warning btn-block" type="submit" id="UnlockThisBranchMark" name="viewmark">UnLock Branch</button>
                            <?php } ?>
                            <span class="UnlockBranchInfo"></span>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade show" id="LockAllMark" role="tabpanel" aria-labelledby="home-tab5">
                        <div class="row">
                          <div class="col-lg-6 col-6">
                            <?php $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='lockstudentmark' order by id ASC "); 
                            if($uperStuDE->num_rows() >0){ ?>
                              <button class="btn btn-info btn-block btn-sm lock_any_mark" type="submit" name="viewmark"><i class='fas fa-lock'></i> Lock All Mark</button>
                            <?php } ?>
                            <span class="text-success lockInfo"></span>
                          </div>
                          <div class="col-lg-6 col-6">
                            <?php $uperStuDEu=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='unlockstudentmark' order by id ASC "); 
                            if($uperStuDEu->num_rows() >0) { ?>
                              <button class="btn btn-warning btn-block btn-sm unlock_any_mark" type="submit" name="viewmark"><i class="fas fa-unlock-alt"></i> Unlock All Mark</button>
                            <?php } ?>
                            <span class="text-success unlockInfo"></span>
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
      <?php include('footer.php'); ?>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#selectQuarterHere").bind("change", function() {
      gradeName=[];
      $("input[name='gradeListForLockAssesmentMarkList[ ]']:checked").each(function(i){
        gradeName[i]=$(this).val();
      });
      var quarter=$("#selectQuarterHere").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>lockunlockstudentmark/fetchThisGradeEvaluation/",
        data: ({
          quarter: quarter,
          gradeName:gradeName
        }),
        beforeSend: function() {
          $('#listEvaluationAssesment').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#listEvaluationAssesment").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#viewGradeAssesment', function() {
    branchName=[];gradeName=[];evaluationName=[];
    $("input[name='customAssesmentBranch']:checked").each(function(i){
      branchName[i]=$(this).val();
    });
    $("input[name='evaluationListForLockAssesment[ ]']:checked").each(function(i){
      evaluationName[i]=$(this).val();
    });
    $("input[name='gradeListForLockAssesmentMarkList[ ]']:checked").each(function(i){
      gradeName[i]=$(this).val();
    });
    var quarter=$("#selectQuarterHere").val();
    if(branchName.length!=0 && evaluationName.length!=0 && gradeName.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>lockunlockstudentmark/fetchAssesmentStatusToLockUnlock/",
        data: ({
          branchName: branchName,
          evaluationName:evaluationName,
          gradeName:gradeName,
          quarter:quarter
        }),
        beforeSend: function() {
          $('.fetchAssesmentHere').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".fetchAssesmentHere").html(data);
        }
      });
    }else{
      swal('Oooops Please select all necessary fields.', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '.customAssesmentBranch', function() {
    branchName=[];
    $("input[name='customAssesmentBranch']:checked").each(function(i){
      branchName[i]=$(this).val();
    });
    if($(".customAssesmentBranch").val()!=''){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>lockunlockstudentmark/fetchThisBranchGrade4Assesment/",
         data: ({
          branchName: branchName
        }),
        beforeSend: function() {
          $('#listGradeSecAssesment').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $("#listGradeSecAssesment").html(data);
        }
      });
    }
  });
  $(document).on('click', '.gradeListForLockAssesmentMarkList', function() {
    evaluationName=[];
    $("input[name='gradeListForLockAssesmentMarkList[ ]']:checked").each(function(i){
      evaluationName[i]=$(this).val();
    });
    if(evaluationName.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>lockunlockstudentmark/fetchGradeQuarter/",
         data: ({
          evaluationName: evaluationName
        }),
        beforeSend: function() {
          $('#selectQuarterHere').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $("#selectQuarterHere").html(data);
        }
      });
    }
  });
  $(document).on('click', "input[name='lockThisAssesmentMarkGS']", function() {
      var assesgrade=$(this).attr("value");
      var assesname=$(this).attr("id");
      var quarter=$(this).attr("title");
      var subject=$(this).attr("class");
      if($(this).is(':checked')){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>lockunlockstudentmark/lockThisAssesmentMark/",
            data: ({
              assesgrade: assesgrade,
              assesname:assesname,
              quarter:quarter,
              subject:subject
            }),
            cache: false,
            success: function(html) {
              iziToast.success({
                title: 'Assesment mark locked successfully',
                message: '',
                position: 'topRight'
              });
            }
          });
      }else{
        var assesgrade=$(this).attr("value");
        var assesname=$(this).attr("id");
        var quarter=$(this).attr("title");
        var subject=$(this).attr("class");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>lockunlockstudentmark/unlockThisAssesmentMark/",
          data: ({
            assesgrade: assesgrade,
            assesname:assesname,
            quarter:quarter,
            subject:subject
          }),
          cache: false,
          success: function(html) {
            iziToast.success({
              title: 'Assesment mark Unlocked successfully',
              message: '',
              position: 'topRight'
            });
          }
        });
      }
    });
</script>
<script type="text/javascript">
  $(document).on('click', '#LockThisBranchMark', function(){
    branchName=[];
    $("input[name='lockBranch']:checked").each(function(i){
      branchName[i]=$(this).val();
    });
    swal({
      title: 'Are you sure you want to lock this branch mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })

    .then((willDelete) => {
      if (willDelete) {
        if(branchName.length!=0){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>lockunlockstudentmark/lockThisBranchMark/",
            data: ({
              branchName: branchName
            }),
            cache: false,
            beforeSend: function() {
              $('.lockBranchInfo').html( 'Locking<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
                );
            },
            success: function(html){
              $(".lockBranchInfo").html('Locked successfully <i class="fas fa-lock"></i>');
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
  $(document).on('click', '#UnlockThisBranchMark', function(){
    branchName=[];
    $("input[name='lockBranch']:checked").each(function(i){
      branchName[i]=$(this).val();
    });
    swal({
      title: 'Are you sure you want to unlock this branch mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })

    .then((willDelete) => {
      if (willDelete) {
        if(branchName.length!=0){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>lockunlockstudentmark/unlockThisBranchMark/",
            data: ({
              branchName: branchName
            }),
            cache: false,
            beforeSend: function() {
              $('.UnlockBranchInfo').html( 'Locking<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
                );
            },
            success: function(html){
              $(".UnlockBranchInfo").html('Unlocked successfully <i class="fas fa-unlock"></i>');
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
  $(document).on('click', '.studentGradeBranchName', function() {
    branchName=[];
    $("input[name='studentGradeBranchName']:checked").each(function(i){
      branchName[i]=$(this).val();
    });
    if($(".studentGradeBranchName").val()!=''){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>lockunlockstudentmark/fetchThisBranchGrade/",
         data: ({
          branchName: branchName
        }),
        beforeSend: function() {
          $('#listBranchGrade').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $("#listBranchGrade").html(data);
        }
      });
    }
  });

  $(document).on('click', '#LockThisGradeMark', function(){
    branchName=[];
    $("input[name='studentGradeBranchName']:checked").each(function(i){
      branchName[i]=$(this).val();
    });
    grade=[];
    $("input[name='gradecListForLockMarkList[ ]']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    swal({
      title: 'Are you sure you want to lock this grade mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })

    .then((willDelete) => {
      if (willDelete) {
        if(grade.length!=0 && branchName.length!=0){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>lockunlockstudentmark/lockThisGradeMark/",
            data: ({
              branchName: branchName,
              grade:grade
            }),
            cache: false,
            beforeSend: function() {
              $('.lockGradeInfo').html( 'Locking<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
                );
            },
            success: function(html){
              $(".lockGradeInfo").html('Locked successfully <i class="fas fa-lock"></i>');
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
  $(document).on('click', '#UnlockThisGradeMark', function(){
    branchName=[];
    $("input[name='studentGradeBranchName']:checked").each(function(i){
      branchName[i]=$(this).val();
    });
    grade=[];
    $("input[name='gradecListForLockMarkList[ ]']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    swal({
      title: 'Are you sure you want to Unlock this grade mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })

    .then((willDelete) => {
      if (willDelete) {
        if(grade.length!=0 && branchName.length!=0){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>lockunlockstudentmark/UnlockThisGradeMark/",
            data: ({
              branchName: branchName,
              grade:grade
            }),
            cache: false,
            beforeSend: function() {
              $('.UnlockGradeInfo').html( 'Locking<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
                );
            },
            success: function(html){
              $(".UnlockGradeInfo").html('Unlocked successfully <i class="fas fa-unlock"></i>');
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
  $(document).on('click', '#LockThisGradesecMark', function(){
    branchName=[];
    $("input[name='studentBranchName']:checked").each(function(i){
      branchName[i]=$(this).val();
    });
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
        if(gradesec.length!=0 && branchName.length!=0){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>lockunlockstudentmark/lockThisSectionMark/",
            data: ({
              branchName: branchName,
              gradesec:gradesec
            }),
            cache: false,
            beforeSend: function() {
              $('.lockGradesecInfo').html( 'Locking<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
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
    branchName=[];
    $("input[name='studentBranchName']:checked").each(function(i){
      branchName[i]=$(this).val();
    });
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
        if(gradesec.length!=0 && branchName.length!=0){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>lockunlockstudentmark/UnlockThisSectionMark/",
            data: ({
              branchName: branchName,
              gradesec:gradesec
            }),
            cache: false,
            beforeSend: function() {
              $('.UnlockGradesecInfo').html( 'Locking<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
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
  $(document).on('click', '.studentBranchName', function() {
    branchName=[];
    $("input[name='studentBranchName']:checked").each(function(i){
      branchName[i]=$(this).val();
    });
    if($(".studentBranchName").val()!=''){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>lockunlockstudentmark/fetchThisBranchSection/",
         data: ({
          branchName: branchName
        }),
        beforeSend: function() {
          $('#listBranchSection').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $("#listBranchSection").html(data);
        }
      });
    }
  });
  $(document).ready(function() { 
    $('#searchStudent').on("keyup",function() {
      $searchItem=$('#searchStudent').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>lockunlockstudentmark/searchStudent/",
        data: "searchItem=" + $("#searchStudent").val(),
        beforeSend: function() {
          $('.StudentList').html( 'Searching<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
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
          url: "<?php echo base_url(); ?>lockunlockstudentmark/lockThisStudentMark/",
          data: ({
            stuID: stuID
          }),
          cache: false,
          beforeSend: function() {
            $('#lockThisStudentMark' + stuID).html( 'Locking<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
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
          url: "<?php echo base_url(); ?>lockunlockstudentmark/unlockThisStudentMark/",
          data: ({
            stuID: stuID
          }),
          cache: false,
          beforeSend: function() {
            $('#unlockThisStudentMark' + stuID).html( 'Unlocking<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
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
  $(document).on('click', '.lock_any_mark', function() {
    swal({
      title: 'Are you sure you want to lock all mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>lockunlockstudentmark/lockAllMark/",
          cache: false,
          beforeSend: function() {
            $('.lockInfo').html( 'Locking<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
          },
          success: function(html){
            $('.lockInfo').html(html);
            swal('Mark Locked successfully!', {
              icon: 'success',
            });
          }
        }); 
      }
    });
  });
  $(document).on('click', '.unlock_any_mark', function() {
    swal({
      title: 'Are you sure you want to unlock all mark?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>lockunlockstudentmark/unlockAllMark/",
          cache: false,
          beforeSend: function() {
            $('.unlockInfo').html( 'Locking<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
          },
          success: function(html){
            $('.unlockInfo').html(html);
            swal('Mark Unlocked successfully!', {
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
</html>