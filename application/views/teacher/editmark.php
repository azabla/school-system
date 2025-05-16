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
          <?php } else { if($markstatus->num_rows()>0 || $checkAutoLock){?>
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
              <div class="card-header">
                <h4>Edit Student Result</h4>
              </div>
            <div class="card-body StudentViewTextInfo">
             <form method="POST" id="comment_form">
                    <div class="row">
                         <div class="col-lg-3 col-6">
                          <div class="form-group">
                          <select class="form-control selectric" required="required" name="gradesec" id="gradesec">
                           <option>--- Select Grade ---</option>
                            <?php foreach($fetch_grade_fromsp_toadd_neweaxm as $gradesecs){ ?>
                              <option value="<?php echo $gradesecs->grade;?>">
                              <?php echo $gradesecs->grade;?>
                              </option>
                            <?php }?>
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
                       <div class="col-lg-3 col-6">
                         <div class="form-group">
                           <select class="form-control selectric" required="required" name="quarter" 
                           id="quarter">
                          <option>--- Select Quarter ---</option>
                            <?php foreach($fetch_term as $fetch_terms){ ?>
                              <option value="<?php echo $fetch_terms->term;?>">
                              <?php echo $fetch_terms->term;?>
                              </option>
                            <?php }?>
                           </select>
                          </div>
                         </div>
                       <div class="col-lg-3 col-6">
                        <button class="btn btn-primary btn-block btn-lg" 
                        type="submit" name="viewmark">View Result</button>
                      </div>
                    </div>
                  </form> 
                  <div id="listmark" class="listmark"></div>
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
              <div class="modal-body" id="editngmarkhere_gs">
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="updatesubject" class="btn btn-primary">Save</button>
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
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Mark</h5>
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
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
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
      var subject=$(".jo_subject").val();
      var gradesec=$(".jo_gradesec").val();
      var branch=$(".jo_branch").val();
      var year=$(".jo_year").val();
      var quarter=$(".jo_quarter").val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Editstudentmark/deleteThismark/",
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
        url: "<?php echo base_url(); ?>Editstudentmark/editOutOf/",
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
      url: "<?php echo base_url(); ?>Editstudentmark/updateOutOf/",
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
        url: "<?php echo base_url(); ?>Editstudentmark/editMarkName/",
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
<script type="text/javascript">
  $(document).on('change', '#changeEvaluation', function() {
    var subject=$(".jo_subject").val();
    var gradesec=$(".jo_gradesec").val();
    var year=$(".jo_year").val();
    var quarter=$(".jo_quarter").val();
    var evalu=$("#changeEvaluation").val();
    var markname=$('.hidenMarkName').val();
    var branch=$('.jo_branch').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Editstudentmark/changeEvaluation/",
        data: ({
          evalu:evalu,
          markname: markname,
          gradesec:gradesec,
          subject:subject,
          quarter:quarter,
          year:year,
          branch:branch
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
    var year=$('.markyear').val();
    var branch=$('.markbranch').val();
    $.ajax({
      url: "<?php echo base_url(); ?>Editstudentmark/updateMarkName/",
      method: "POST",
      data: ({
        oldMarkName:oldMarkName,
        markname: markname,
        gradesec:gradesec,
        subject:subject,
        quarter:quarter,
        year:year,
        branch:branch
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
  $(document).ready(function() {  
    $("#gradesec").bind("change", function() {
      var gradesec=$("#gradesec").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Editstudentmark/Filtersubjectfromstaff/",
        data: ({
          gradesec:gradesec
        }),
         beforeSend: function() {
          $('.subject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $(".subject").html(data);
        }
      });
    });
  });
</script>
<script>
  $(document).on('click', '.lock_selected', function() {
    swal({
      title: 'Are you sure you want to Lock this subject mark?',
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
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Editstudentmark/lockThisSubject/",
          data: ({
            subject: subject,
            gradesec:gradesec,
            branch:branch,
            quarter:quarter,
            year:year
          }),
          cache: false,
          beforeSend: function() {
            $('.listmark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
          },
          success: function(html){
            $('.listmark').html(html);
          }
        }); 
      }else{
        return false;
      }
    });
  });
</script>
<script>
  $(document).on('click', '.unlock_selected', function() {
    swal({
      title: 'Are you sure you want to unlock this subject mark?',
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
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Editstudentmark/unlockThisSubject/",
          data: ({
            subject: subject,
            gradesec:gradesec,
            branch:branch,
            quarter:quarter,
            year:year
          }),
          cache: false,
          beforeSend: function() {
            $('.listmark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
          },
          success: function(html){
            $('.listmark').html(html);
          }
        }); 
      }else{
        return false;
      }
    });
  });
</script>
<script type="text/javascript">
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var gs_gradesec=$('#gradesec').val();
    var gs_subject=$('.subject').val();
    var gs_quarter=$('#quarter').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Editstudentmark/Fecth_grademark_4teacher/",
        method: "POST",
        data: ({
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject,
          gs_quarter:gs_quarter
        }),
        beforeSend: function() {
          $('.listmark').html( '<h3>Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa"></h3>' );
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
  $(document).on('click', '.gs_delete_markname', function() {
    swal({
        title: 'Are you sure you want to delete this mark?',
        text: 'Once deleted you can not recover this mark again!',
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
          url: "<?php echo base_url(); ?>Editstudentmark/deleteMarkName/",
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

      }else{
        return false;
      }
    });
  });
</script>

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
        url: "<?php echo base_url(); ?>Editstudentmark/fetchMarkToEdit/",
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
    var gradesec=$(".jo_gradesec").val();
    var academicyear=$(".jo_year").val();
    var branch=$('.jo_branch').val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Editstudentmark/fecthNgMarkToEdit/",
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
    var my_studentBranch=$(".my_Branch").val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Editstudentmark/updateNgMarkNow/",
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
        $('.info-ngmark').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
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
        url:"<?php echo base_url() ?>Editstudentmark/FetchUpdatedMark/",
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
        url: "<?php echo base_url(); ?>Editstudentmark/updateMarkNow/",
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