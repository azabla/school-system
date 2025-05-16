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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
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
          <?php 
          if($summerClassMark->num_rows()>0){ ?>
             <div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
              <button class="close"  data-dismiss="alert">
                <span>&times;</span>
              </button>
              <i class="fas fa-check-circle"> </i> Summer class has been started. Please contact your system Admin.
            </div>
            </div> 
          <?php } else { if($markstatus->num_rows()>0 || $checkAutoLock) { ?>
            <div class="alert alert-warning alert-dismissible show fade">
            <div class="alert-body">
              <button class="close"  data-dismiss="alert">
                <span>&times;</span>
              </button>
              <i class="fas fa-check-circle"> </i> Access denied.
            </div>
            </div> 
          <?php } else{ ?>
          <div class="section-body">
            <div class="row">
              <div class="col-12">
              <?php include('bgcolor.php'); ?>
          <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
          <div class="card">
            <div class="card-body StudentViewTextInfo">
              <ul class="nav nav-tabs" id="myTab2" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#defaultMark" role="tab" aria-selected="true"> Edit/Delete Mark</a>
                </li>
                <!-- <li class="nav-item">
                  <a class="nav-link" id="home-tab2" data-toggle="tab" href="#customMark" role="tab" aria-selected="false">Import Custom Student</a>
                </li> -->
                <li class="nav-item">
                  <a class="nav-link" id="home-tab3" data-toggle="tab" href="#deleteCustomSubject" role="tab" aria-selected="false">Delete Custom Subject</a>
                </li>
              </ul>
              <div class="tab-content tab-bordered" id="myTab3Content">
                <div class="tab-pane fade show active StudentViewTextInfo" id="defaultMark" role="tabpanel" aria-labelledby="home-tab1">
                  <div class="row">
                    <div class="col-md-12 col-12">
                      <!-- <form method="POST" action="addmark/" enctype="multipart/form-data">
                        <div class="row">
                        <div class="form-group">
                          <div class="col-lg-6">
                            <div id="image-preview" class="image-preview">
                              <label for="addmark" id="image-label">Choose File
                                <i data-feather="paperclip"></i>
                              </label>
                              <input type="file" name="addmark" id="addmark"/>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <button type="submit" name="insertmark" id="insertmark" class="btn btn-outline-info btn-lg">  Import Mark </button>
                        </div>
                      </div>
                      </form> -->
                      <form id="importDefaultMark" method="POST" enctype="multipart/form-data" >
                        <div class="row">
                          <div class="col-lg-3 col-6">
                            <input type="file" name="addmark" id="addmark" required ="required"/>
                          </div>
                          <div class="col-lg-9 col-6">
                            <button type="submit" name="insertmark" id="insertmark" class="btn btn-info"> Submit & Save Mark </button>
                          </div>
                        </div>
                      </form>
                      <p class="importingDefaultMark"></p>
                    </div>
                  </div>
                  <hr>
                  <form method="GET" id="comment_form">
                    <div class="row">
                      <div class="col-lg-2 col-6">
                         <div class="form-group">
                           <select class="form-control selectric academicyearFilterJoss" required="required" name="academicyear"  
                           id="academicyear">
                           <option>- Year-</option>
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                              <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                           </select>
                          </div>
                         </div>
                         <div class="col-lg-4 col-6">
                          <div class="form-group">
                           <select class="form-control selectric"
                           required="required" name="branch"
                           id="branchit">
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
                          <div class="form-group">
                           <select class="form-control selectric gradesec" required="required" name="gradesec" id="gradesec">
                           <option>--- Grade ---</option>
                           </select>
                          </div>
                         </div>
                        <div class="col-lg-4 col-6">
                           <div class="form-group">
                          <select class="form-control subject"
                            name="subject">
                            <option>--- Select Subject ---</option>
                          </select>
                        </div>
                         </div>
                        <div class="col-lg-4 col-6">
                         <div class="form-group">
                          <select class="form-control selectric" required="required" name="quarter" id="quarter">
                          <option>--- Select Quarter ---</option>
                            
                           </select>
                          </div>
                         </div>
                        <div class="col-lg-2 col-6">
                        <button class="btn btn-primary btn-block btn-lg" 
                        type="submit" name="viewmark">View</button>
                      </div>
                    </div>
                  </form> 
                  <div class="dropdown-divider"></div>
                  <div id="listmark" class="listmark"></div>
                </div>
                <!-- <div class="tab-pane fade show" id="customMark" role="tabpanel" aria-labelledby="home-tab2">
                  <div class="alert alert-light alert-dismissible show fade">
                    <div class="alert-body">
                      <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                      </button>
                      Note:Remove unnecessary or empty columns and rows from imported excel.
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12 col-12">
                      <form method="POST" id="importCustomMark" enctype="multipart/form-data">
                        <input type="file" name="addcustommark" id="addcustommark" required accept=".xls, .xlsx"/>
                        <button type="submit" name="insertcustommark" id="insertcustommark" class="btn btn-primary btn-sm">  Import Mark </button>
                        <div class="dropdown-divider"></div>
                        <p class="importingCustomMark"></p>
                      </form>
                    </div>
                  </div>
                </div> -->
                <div class="tab-pane fade show" id="deleteCustomSubject" role="tabpanel" aria-labelledby="home-tab3">
                  <div class="alert alert-light alert-dismissible show fade">
                    <div class="alert-body">
                      <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                      </button>
                      Note:This will delete the selected subject mark permanently.
                    </div>
                  </div>
                  <form method="GET" id="custom_comment_form">
                    <div class="row">
                      <div class="col-lg-2 col-6">
                         <div class="form-group">
                           <select class="form-control selectric customAcademicYear" required="required" name="customAcademicYear" id="customAcademicYear">
                           <option>- Year-</option>
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                              <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                           </select>
                          </div>
                         </div>
                         <div class="col-lg-4 col-6">
                          <div class="form-group">
                           <select class="form-control selectric" required="required" name="customBranch" id="customBranch">
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
                          <div class="form-group">
                           <select class="form-control selectric customGradesec" required="required" name="customGradesec" id="customGradesec">
                           <option>--- Grade ---</option>
                           </select>
                          </div>
                         </div>
                        <div class="col-lg-4 col-6">
                         <div class="form-group">
                          <select class="form-control selectric" required="required" name="customQuarter" id="customQuarter">
                          <option>--- Select Quarter ---</option>
                            
                           </select>
                          </div>
                         </div>
                        <div class="col-lg-2 col-12">
                        <button class="btn btn-primary btn-block btn-lg" 
                        type="submit" name="viewSubjectMark">View Subject</button>
                      </div>
                    </div>
                  </form> 
                  <div class="dropdown-divider"></div>
                  <div id="listSubjectMark" class="listSubjectMark"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php } } ?>
  </section>
    <form method="POST" id="comment_form_NGupdate">
      <div class="modal fade" id="editngmark" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalCenterTitle">Edit NG Mark</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="editngmarkhere_gs"></div>
            <div class="modal-footer bg-whitesmoke br">
              <button type="submit" name="updatesubject" class="btn btn-primary">Save</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </form>
      <form method="POST" id="form_editOutof">
        <div class="modal fade" id="editOutOf" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Mark Percentage</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="editoutof_gs">
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="updateoutof" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <form method="POST" id="form_editMarkname">
        <div class="modal fade" id="editmarkName" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Mark Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="editmarkName_gs">
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="updatesubject" class="btn btn-primary savegrandsubject">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <form method="POST" id="comment_form_update">
        <div class="modal fade" id="editmark" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Mark Value</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="editmarkhere_gs">
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="updatesubject" class="btn btn-primary savegrandsubject">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <!-- modal for out of edit -->
      <form method="POST" id="comment_form_outofupdate">
        <div class="modal fade" id="editoutof" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Mark</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="editoutofhere_gs">
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="updatesubject" class="btn btn-primary savegrandsubject">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>  
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  $('#importDefaultMark').on('submit', function(event){
  event.preventDefault();
  $.ajax({
    url:"<?php echo base_url(); ?>addmark/importDefaultStudentMark",
    method:"POST",
    data:new FormData(this),
    contentType:false,
    cache:false,
    processData:false,
    beforeSend: function() {
      $('.importingDefaultMark').html( 'importing...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
    },
    success:function(data){
      $('#addmark').val('');
      $(".importingDefaultMark").html(data);
    }
  })
 });
</script>

<script type="text/javascript">
  $('#importCustomMark').on('submit', function(event){
  event.preventDefault();
  $.ajax({
   url:"<?php echo base_url(); ?>addmark/importCustomStudentMark",
   method:"POST",
   data:new FormData(this),
    beforeSend: function() {
      $('.importingCustomMark').html( 'importing...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
    },
   contentType:false,
   cache:false,
   processData:false,
   success:function(data){
    $('#addcustommark').val('');
     $(".importingCustomMark").html(data);
   }
  })
 });
</script>

<script type="text/javascript">
  $(document).on('change', '#changeEvaluation', function() {
    var subject=$(".jo_subject").val();
    var gradesec=$(".jo_gradesec").val();
    var branch=$(".jo_branch").val();
    var year=$(".jo_year").val();
    var quarter=$(".jo_quarter").val();
    var evalu=$("#changeEvaluation").val();
    var markname=$('.hidenMarkName').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>addmark/changeEvaluation/",
        data: ({
          evalu:evalu,
          markname: markname,
          gradesec:gradesec,
          subject:subject,
          quarter:quarter,
          branch:branch,
          year:year
        }),
        beforeSend: function() {
          $('.changeEvalInfo').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
        },
        success: function(data) {
          $(".changeEvalInfo").html(data);
        }
      });
  });
</script>

<script type="text/javascript">
  $('#form_editMarkname').on('submit', function(event) {
    event.preventDefault();
    var oldMarkName=$('.hidenMarkName').val();
    var markname=$('.updateMarkName').val();
    var gradesec=$('.markgradesec').val();
    var subject=$('.marksubject').val();
    var quarter=$('.markquarter').val();
    var branch=$('.markbranch').val();
    var year=$('.markyear').val();
    $.ajax({
      url: "<?php echo base_url(); ?>addmark/updateMarkName/",
      method: "POST",
      data: ({
        oldMarkName:oldMarkName,
        markname: markname,
        gradesec:gradesec,
        subject:subject,
        quarter:quarter,
        branch:branch,
        year:year
      }),
      beforeSend: function() {
        $('.coreMarkName'+oldMarkName).html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
      },
      success: function(data) {
        $(".coreMarkName"+oldMarkName).html(data);
      }
    })
  });
</script>
<script type="text/javascript">
  $('#form_editOutof').on('submit', function(event) {
    event.preventDefault();
    var oldOutOf=$('.oldOutOf').val();
    var updateOutOf=$('.updateOutOf').val();
    var markname=$('.outofmarkname').val();
    var gradesec=$('.markgradesec').val();
    var subject=$('.marksubject').val();
    var quarter=$('.markquarter').val();
    var branch=$('.markbranch').val();
    var year=$('.markyear').val();
    $.ajax({
      url: "<?php echo base_url(); ?>addmark/updateOutOf/",
      method: "POST",
      data: ({
        oldOutOf:oldOutOf,
        updateOutOf:updateOutOf,
        markname: markname,
        gradesec:gradesec,
        subject:subject,
        quarter:quarter,
        branch:branch,
        year:year
      }),
      beforeSend: function() {
        $('.coreOutOF'+oldOutOf+markname).html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
      },
      success: function(data) {
         $(".changeOutInfo").html(data);
        $(".coreOutOF"+oldOutOf+markname).html(data);
      }
    })
  });
</script>
<script type="text/javascript">
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var gs_branches=$('#branchit').val();
    var gs_gradesec=$('.gradesec').val();
    var gs_subject=$('.subject').val();
    var gs_quarter=$('#quarter').val();
    var academicyear=$('.academicyearFilterJoss').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>addmark/fetchGradeMark/",
        method: "GET",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject,
          gs_quarter:gs_quarter,
          academicyear:academicyear
        }),
        dataType:'json',
        beforeSend: function() {
          $('.listmark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listmark").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $('#custom_comment_form').on('submit', function(event) {
    event.preventDefault();
    var gs_branches=$('#customBranch').val();
    var gs_gradesec=$('.customGradesec').val();
    var gs_quarter=$('#customQuarter').val();
    var academicyear=$('.customAcademicYear').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>addmark/fetchCustomSubjectMark/",
        method: "GET",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          gs_quarter:gs_quarter,
          academicyear:academicyear
        }),
        dataType:'json',
        beforeSend: function() {
          $('.listSubjectMark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listSubjectMark").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<!-- Lock selected student mark starts -->
<script>
  $(document).on('click', '.lock_me', function() {
    swal({
      title: 'Are you sure?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
    if (willDelete) {
      swal('Mark Locked successfully!', {
        icon: 'success',
      });
      var subject=$(".jo_subject").val();
      var stuid=$(this).attr("value");
      var gradesec=$(".jo_gradesec").val();
      var branch=$(".jo_branch").val();
      var year=$(".jo_year").val();
      var quarter=$(".jo_quarter").val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>addmark/lockThisStudentMark/",
        data: ({
          stuid: stuid,
          subject: subject,
          quarter:quarter,
          gradesec:gradesec,
          branch:branch,
          year:year
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
<!-- lock selected student mark ends -->
<!-- Lock selected subject starts -->
<script>
  $(document).on('click', '.lock_selected', function() {
    swal({
      title: 'Are you sure?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
    if (willDelete) {
      swal('Mark Locked successfully!', {
        icon: 'success',
      });
      var subject=$(".jo_subject").val();
      var gradesec=$(".jo_gradesec").val();
      var branch=$(".jo_branch").val();
      var year=$(".jo_year").val();
      var quarter=$(".jo_quarter").val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>addmark/lockThisMark/",
        data: ({
          subject: subject,
          gradesec:gradesec,
          branch:branch,
          quarter:quarter,
          year:year
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
<!-- lock selected ends -->
<!-- Lock selected Grade starts -->
<script>
  $(document).on('click', '.lock_selected_grade', function() {
    swal({
      title: 'Are you sure?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal('Mark Locked successfully!', {
          icon: 'success',
        });
        var subject=$(".jo_subject").val();
        var gradesec=$(".jo_gradesec").val();
        var branch=$(".jo_branch").val();
        var year=$(".jo_year").val();
        var quarter=$(".jo_quarter").val();
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>addmark/lockThisSubject/",
          data: ({
            subject: subject,
            gradesec:gradesec,
            branch:branch,
            quarter:quarter,
            year:year
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
<!-- lock selected Grade ends -->

<!-- edit markname starts -->
<script>
  $(document).on('click', '.gs_edit_markname', function() {
      var subject=$(".jo_subject").val();
      var gradesec=$(".jo_gradesec").val();
      var branch=$(".jo_branch").val();
      var year=$(".jo_year").val();
      var quarter=$(".jo_quarter").val();
      var markname=$(this).attr("value");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>addmark/editMarkName/",
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
          $('#editmarkName_gs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data){
          $('#editmarkName_gs').html(data);
        }
      }); 
  });
</script>
<!-- edit markname ends -->
<script>
  $(document).on('click', '.gs_edit_outof', function() {
      var subject=$(".jo_subject").val();
      var gradesec=$(".jo_gradesec").val();
      var branch=$(".jo_branch").val();
      var year=$(".jo_year").val();
      var quarter=$(".jo_quarter").val();
      var markname=$(this).attr("value");
      var outof=$(this).attr("id");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>addmark/editOutOf/",
        data: ({
          subject: subject,
          gradesec:gradesec,
          branch:branch,
          quarter:quarter,
          year:year,
          markname:markname,
          outof: outof
        }),
        cache: false,
        beforeSend: function() {
          $('#editoutof_gs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data){
          $('#editoutof_gs').html(data);
        }
      }); 
  });
</script>
<!-- delete specific mark column starts -->
<script>
  $(document).on('click', '.gs_delete_markname', function() {
    swal({
      title: 'Are you sure?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
    if (willDelete) {
      swal('Mark deleted successfully!', {
        icon: 'success',
      });
      var subject=$(".jo_subject").val();
      var gradesec=$(".jo_gradesec").val();
      var branch=$(".jo_branch").val();
      var year=$(".jo_year").val();
      var quarter=$(".jo_quarter").val();
      var markname=$(this).attr("value");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>addmark/deleteMarkName/",
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
<!-- delete selected subject marks starts -->
<script>
  $(document).on('click', '.delete_selected', function() {
    swal({
      title: 'Are you sure?',
      text: 'Once deleted this mark you can not recover forever!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
    if (willDelete) {
      swal('Subject Mark deleted successfully!', {
        icon: 'success',
      });
      var subject=$(".jo_subject").val();
      var gradesec=$(".jo_gradesec").val();
      var branch=$(".jo_branch").val();
      var year=$(".jo_year").val();
      var quarter=$(".jo_quarter").val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>addmark/deleteThismark/",
        data: ({
          subject: subject,
          gradesec:gradesec,
          branch:branch,
          quarter:quarter,
          year:year
        }),
        cache: false,
        beforeSend: function() {
          $('.listmark').html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.listmark').html(html);
          iziToast.success({
            title: 'This Subject Mark',
            message: 'Deleted successfully',
            position: 'topRight'
          });
        }
      }); 
    }
  });
  });
  $(document).on('click', '#deleteCustomSubjectMark', function() {
    swal({
      title: 'Are you sure?',
      text: 'Once deleted this mark you can not recover forever!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
    if (willDelete) {
      swal('Subject Mark deleted successfully!', {
        icon: 'success',
      });
      /*var subject=$(".jo_subjectCustom").val();*/
      var subject=$(this).attr("value");
      var gradesec=$(".jo_gradesecCustom").val();
      var branch=$(".jo_branchCustom").val();
      var year=$(".jo_yearCustom").val();
      var quarter=$(".jo_quarterCustom").val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>addmark/deleteThisCustomSubjectMark/",
        data: ({
          subject: subject,
          gradesec:gradesec,
          branch:branch,
          quarter:quarter,
          year:year
        }),
        cache: false,
        beforeSend: function() {
          $('.listSubjectMark').html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.listSubjectMark').html(html);
          iziToast.success({
            title: 'This Subject Mark',
            message: 'Deleted successfully',
            position: 'topRight'
          });
        }
      }); 
    }
  });
  });
</script>
<!-- delete selected subject marks ends -->
<!-- delete selected grade marks starts -->
<script>
  $(document).on('click', '.delete_selected_grade', function() {
    swal({
      title: 'Are you sure?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal('Subject Mark deleted successfully!', {
          icon: 'success',
        });
        var subject=$(".jo_subject").val();
        var gradesec=$(".jo_gradesec").val();
        var branch=$(".jo_branch").val();
        var year=$(".jo_year").val();
        var quarter=$(".jo_quarter").val();
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>addmark/deleteThisGradeMark/",
          data: ({
            subject: subject,
            gradesec:gradesec,
            branch:branch,
            quarter:quarter,
            year:year
          }),
          cache: false,
          beforeSend: function() {
            $('.listmark').html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(html){
            $('.listmark').html(html);
            iziToast.success({
              title: 'This Grade Mark',
              message: 'Deleted successfully',
              position: 'topRight'
            });
          }
        }); 
      }
    });
  });
</script>
<!-- delete selected grade marks ends -->
<!-- edit mark starts -->
<script>
  $(document).on('click', '.edit_mark_gs', function() {
      var edtim=$(this).attr("value");
      var quarter=$('.jo_quarter').val();
      var gradesec=$('.jo_gradesec').val();
      var academicyear=$('.jo_year').val();
      var branch=$('.jo_branch').val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>addmark/fetchMarkToEdit/",
        data: ({
          edtim: edtim,
          quarter:quarter,
          gradesec:gradesec,
          academicyear:academicyear,
          branch:branch
        }),
        cache: false,
        beforeSend: function() {
          $('#editmarkhere_gs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('#editmarkhere_gs').html(html);
        }
    });
  });
</script>
<!-- edit mark ends -->
<!-- edit NG mark starts -->
<script>
  $(document).on('click', '.edit_NGmark_gs', function() {
    var stuid=$(this).attr("title");
    var evaid=$(this).attr("value");
    var subject=$(".jo_subject").val();
    var quarter=$(".jo_quarter").val();
    var markname=$(this).attr("name");
    var outof=$(this).attr("id");
    var branch=$('.jo_branch').val();
    var gradesec=$(".jo_gradesec").val();
    var academicyear=$(".jo_year").val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>addmark/fecthNgMarkToEdit/",
      data: ({
        stuid: stuid,
        subject:subject,
        quarter:quarter,
        gradesec:gradesec,
        academicyear:academicyear,
        markname:markname,
        outof:outof,
        evaid:evaid,
        branch:branch
      }),
      cache: false,
      beforeSend: function() {
        $('#editngmarkhere_gs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('#editngmarkhere_gs').html(html);
      }
    });
  });
</script>
<!-- edit NG mark ends -->
<!-- edit outof starts -->
<script>
  $(document).on('click', '.edit_markname_gs', function() {
      var markanme=$(this).attr("value");
      var subject=$(".jo_subject").val();
      var quarter=$(".jo_quarter").val();
      var gradesec=$(".jo_gradesec").val();
      var year=$(".jo_year").val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>addmark/fetchOutOffToEdit/",
        data: ({
          markanme: markanme,
          subject: subject,
          quarter:quarter,
          year:year,
          gradesec: gradesec
        }),
        cache: false,
        beforeSend: function() {
          $('#editoutofhere_gs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('#editoutofhere_gs').html(html);
        }
    });
  });
</script>
<!-- edit outof endes -->
<script>
  $('#comment_form_NGupdate').on('submit', function(event) {
    event.preventDefault();
    var my_eva=$(".my_eva").val();
    var stuid=$(".my_student").val();
    var subject=$(".my_subject").val();
    var quarter=$(".my_quarter").val();
    var year=$(".my_year").val();
    var gradesec=$(".my_gradeSec").val();
    var val =$(".correct_ngmark_gs").val();
    var markname =$(".my_markNameH").val();
    var outof=$(".my_outOf").val();
    var my_studentBranch=$(".my_studentBranch").val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>addmark/updateNgMarkNow/",
      data: ({
        my_eva: my_eva,
        stuid:stuid,
        subject:subject,
        quarter:quarter,
        year:year,
        val:val,
        gradesec:gradesec,
        markname:markname,
        outof:outof,
        my_studentBranch:my_studentBranch
      }),
      cache: false,
      beforeSend: function() {
        $('.info-ngmark').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.info-ngmark').html(html);
        $('.JoMark'+stuid+markname).html(val);
      }
    });
  });
</script>
<!-- edit NG mark ends -->
<script>
  $('#comment_form_update').on('submit', function(event) {
    event.preventDefault();
    load_mark();
    var outof=$(".outof").val();
    var mid=$(".mid").val();
    var value=$(".correct_mark_gs").val();
    var quarter=$(".mQuarter").val();
    var gradesec=$(".gSec").val();
    var year=$(".aYear").val();
    var branch=$(".gsBranch").val();
    function load_mark(){
      $.ajax({
        method:"POST",
        url:"<?php echo base_url() ?>addmark/FetchUpdatedMark/",
        data: ({
          mid: mid,
          quarter:quarter,
          gradesec:gradesec,
          year:year,
          branch:branch
        }),
        cache: false,
        beforeSend: function() {
          $('.jossMark'+mid).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
          );
        },
        success:function(html){
          $('.jossMark' + mid).html(html);
          //$('.fade').fadeOut('slow');
        }
      });
    }
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>addmark/updateMarkNow/",
        data: ({
          mid: mid,
          outof:outof,
          value:value,
          quarter:quarter,
          gradesec:gradesec,
          year:year,
          branch:branch
        }),
        cache: false,
        beforeSend: function() {
          $('.info-mark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.info-mark').html(html);
          load_mark();
        }
    });
  });
</script>
<!-- edit mark ends -->
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branchit").on("change", function() {
    var branch = $("#branchit").val();
    var academicyear = $(".academicyearFilterJoss").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>addmark/Filter_grade_from_branch",
        data: ({
          branch: branch,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('.gradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".gradesec").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#customBranch").on("change", function() {
    var branch = $("#customBranch").val();
    var academicyear = $(".customAcademicYear").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>addmark/Filter_grade_from_branch",
        data: ({
          branch: branch,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('.customGradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".customGradesec").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#gradesec").bind("change", function() {
      var gradesec=$("#gradesec").val();
      var academicyear = $(".academicyearFilterJoss").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>addmark/Fetch_subject_from_subject4MardEdit/",
        data: ({
          gradesec: gradesec,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('.subject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".subject").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $(".academicyearFilterJoss").bind("change", function() {
      var academicyear = $(".academicyearFilterJoss").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>addmark/fetchQuarterFromAcademicYear/",
        data: ({
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#quarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#quarter").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $(".customAcademicYear").bind("change", function() {
      var academicyear = $(".customAcademicYear").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>addmark/fetchQuarterFromAcademicYear/",
        data: ({
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#customQuarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#customQuarter").html(data);
        }
      });
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