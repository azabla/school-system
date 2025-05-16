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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
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
                    <label>Academic Year</label>
                    <select class="form-controdl selectric" required="required" name="academicyear"  id="grands_academicyear">
                      <?php foreach($allYear as $academicyears){ ?>
                        <option value="<?php echo $academicyears->year_name;?>">
                          <?php echo $academicyears->year_name;?>
                        </option>
                      <?php }?>
                    </select>
                    <a href="#" class="addStudenttoSpecificSubject" value="" data-toggle="modal" data-target="#addStudentSpecificSubject"><span class="text-success">
                    <button class="btn btn-outline-primary pull-right"><i data-feather="plus-circle"> </i>Allocate Subject to Students</button> </span> </a>

                    <a href="#" id="evaluation_status"></a>
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#feedSubject" role="tab" aria-selected="true"> Manage Subject</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#feedMergedSubject" role="tab" aria-selected="false">Merged Subject</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab4" data-toggle="tab" href="#customSubjectPercentage" role="tab" aria-selected="false">Custom Subject Percentage</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#feedSubSubject" role="tab" aria-selected="false">Sub Subject</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="feedSubject" role="tabpanel" aria-labelledby="home-tab1">
                          <div class="row">
                            <div class="col-lg-9 col-12">
                              <div class="support-ticket media">
                                <div class="media-body">
                                  <span class="font-weight-bold">Key:-&nbsp; &nbsp; </span>
                                  <small class="text-muted"><span class="font-13">A - For letter subjects &nbsp; &nbsp;
                                  # - For number subjects &nbsp; &nbsp;
                                  Status - ON/OFF Subjects from student side. &nbsp; &nbsp;
                                  RC - Whether subject displays on report card, roster and transcript or not.</span> 
                                </small>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3 col-12">
                              <a href="#" class="AddNewSubject" value="" data-toggle="modal" data-target="#StarttAddingNewSubject"><span class="text-success">
                              <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add Subject</button>
                             </span>
                             </a>
                            </div>
                          </div>
                          <!-- <div class="StudentViewTextInfo">
                          <div class="row">
                            <div class="col-lg-6 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="academicyear"  id="grands_academicyear">
                                  <option>--Academic Year--</option>
                                <?php foreach($allYear as $academicyears){ ?>
                                  <option value="<?php echo $academicyears->year_name;?>">
                                    <?php echo $academicyears->year_name;?>
                                  </option>
                                <?php }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-6 col-6"><button class="btn btn-secondary fetchYearSubject" type="submit">View Subject</button>
                            </div>
                          </div>
                        </div> -->
                        <div class="subjectList" id="subjecttshere"></div>
                      </div>
                      <div class="tab-pane fade show" id="feedMergedSubject" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row">
                          <div class="col-lg-12 col-12">
                            <a href="#" class="editsubject" value="" data-toggle="modal" data-target="#exampleModalCenterj"><span class="text-black">
                              <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add Merged Subject</button>
                           </span>
                           </a>
                          </div>
                        </div>
                        <div class="fetch_merged_subject" id="fetch_merged_subject"> </div>
                      </div>
                      <div class="tab-pane fade show" id="customSubjectPercentage" role="tabpanel" aria-labelledby="home-tab4">
                        <div class="row">
                          <div class="col-lg-12 col-12">
                            <div class="row">
                              <?php foreach($grade as $grades){ ?>
                                <div class="col-lg-2 col-3">
                                  <div class="pretty p-icon p-bigger">
                                  <input type="radio" name="percentageSubjectGrade" value="<?php echo $grades->grade; ?>" class="percentageSubjectGrade" id="percentageSubjectGrade">
                                  <div class="state p-info">
                                    <i class="icon fa fa-check"></i>
                                    <label></label><?php echo $grades->grade; ?>
                                  </div>
                                 </div>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="col-md-12 col-12"> 
                            <div class="dropdown-divider"></div>
                            <div class="subjectPercentageNameHere"></div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade show" id="feedSubSubject" role="tabpanel" aria-labelledby="home-tab3">
                        <div class="row">
                          <div class="col-lg-4 col-6">
                            <label for="Mobile"><h6>Grade</h6></label>
                            <div class="row">
                              <?php foreach($grade as $grades){ ?>
                              <div class="col-lg-4 col-6">
                                  <?php echo $grades->grade; ?>
                                  <div class="pretty p-icon p-jelly p-round p-bigger">
                                  <input type="checkbox" name="subSubjectGrade" value="<?php echo $grades->grade; ?>" class="subSubjectGrade" id="customCheck1 subSubjectGrade">
                                  <div class="state p-info">
                                    <i class="icon fa fa-check"></i>
                                    <label></label>
                                  </div>
                                 </div>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="col-md-5 col-6 subjectNameHere">
                          </div>
                          <div class="col-lg-3 col-6">
                            <div class="form-group">
                              <input class="form-control subSubjectName" id="subSubjectName" required="required" type="text" placeholder="Group/List name here">
                            </div>
                          </div>
                          <div class="col-lg-12 col-6">
                            <div class="form-group">
                              <button type="submit" id="saveSubSubject" name="post" class="btn btn-primary btn-sm btn-block"> Save
                              </button>
                            </div>
                          </div>
                        </div>
                        <div class="fetch_sub_subject" id="fetch_sub_subject"> </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <div class="modal fade" id="StarttAddingNewSubject" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add New Subject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="card">  
                  <div class="card-body StudentViewTextInfo">
                    <div class="modal-body">
                      <form method="POST" id="saveNewSubject">
                        <div class="row">
                          <div class="col-lg-6 col-6">
                            <div class="form-group">
                              <label for="Mobile">Academic Year</label>
                              <select class="form-control selectric" required="required" name="academicYearNewSubject"  id="academicYearNewSubject">
                                <?php foreach($allYear as $academicyears){ ?>
                                  <option value="<?php echo $academicyears->year_name;?>">
                                    <?php echo $academicyears->year_name;?>
                                  </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-6 col-6">
                            <div class="form-group">
                              <label for="Mobile">Subject Name</label>
                              <input class="form-control subjectName" id="subjectName" required="required" type="text" placeholder="Subject name here">
                            </div>
                          </div>
                          <div class="col-lg-12 col-12 table-responsive" id="grajosstad" style="height: 20vh;">
                            <label for="Mobile"><h6>Grade</h6></label>
                            <div class="row">
                              <?php foreach($grade as $grades){ ?>
                              <div class="col-lg-3 col-12">
                                <div class="form-group">
                                  <div class="pretty p-icon p-smooth">
                                  <input type="checkbox" name="subjectGrade" value="<?php echo $grades->grade; ?>" id="customCheck1 subjectGrade">
                                  <div class="state p-info">
                                    <i class="icon fa fa-check"></i>
                                    <label></label><?php echo $grades->grade; ?>
                                  </div>
                                 </div>                                 
                                 <div class="pretty p-icon p-jelly p-round p-bigger">
                                  <input type="radio" name="subjectLetter<?php echo $grades->grade; ?>" value="#" id="customCheck1 subjectLetter">
                                  <div class="state p-info">
                                    <i class="icon material-icons"></i>
                                    <label></label>#
                                  </div>
                                 </div>
                                 <div class="pretty p-icon p-jelly p-round p-bigger">
                                  <input type="radio" name="subjectLetter<?php echo $grades->grade; ?>" value="A" id="customCheck1 subjectLetter">
                                  <div class="state p-info">
                                    <i class="icon material-icons"></i>
                                    <label></label>A
                                  </div>
                                 </div>
                                </div>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="col-lg-12 col-12">
                            <div class="form-group pull-right">
                              <button type="submit" name="post" class="btn btn-primary"> Save Subject
                              </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <div class="modal-footer bg-whitesmoke">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="exampleModalCenterj" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Merged Subject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-lg-6 col-6">
                    <div class="form-group">
                      <label for="Mobile">Merged Name</label>
                     <input class="form-control" id="mname" name="mnameiy" type="text" placeholder="Merged name here">
                    </div>
                  </div>
                  <div class="col-lg-6 col-6">
                    <div class="form-group">
                      <label for="Mobile">Subject Name 
                      </label>
                      <select class="form-control selectric" required="required" name="grasege" id="subid">
                        <option></option>
                        <?php foreach($subj4merged as $grades){ ?>
                          <option value="<?php echo $grades->Subj_name; ?>"><?php echo $grades->Subj_name; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                 <div class="row">
                   <?php foreach($grade as $grades){ ?>
                    <div class="col-lg-3 col-6">
                      <select name="lettery" class="lettery" id="inputGroupSelect04">
                        <option></option>
                        <?php for($i=100;$i>=1;$i--) { ?>
                        <option id="<?php echo $grades->grade; ?>" value="<?php echo $i; ?>">
                          <?php echo $i; ?>
                        </option>
                        <?php } ?>
                      </select>
                      <span class="badge badge-info"><?php echo $grades->grade; ?></span>
                      <div class="dropdown-divider"></div>
                    </div>
                   <?php } ?>
                 </div>
                
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <a id="saveskygrade"></a>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="addStudentSpecificSubject" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Allocate Subject to Students</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Note: The page is designed to enable the assignment of specific subjects to individual students. It likely provides a user interface where administrators or educators can select a student from a list and then assign one or more subjects to them.
          <ul class="nav nav-tabs" id="myTab2" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#allocateSubjectSpecificStudent" role="tab" aria-selected="true"> Allocate subject specific student</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="home-tab2" data-toggle="tab" href="#viewSubjectSpecificStudent" role="tab" aria-selected="false">View Allocate</a>
            </li>
          </ul>
          <div class="tab-content tab-bordered" id="myTab3Content">
            <div class="tab-pane fade show active" id="allocateSubjectSpecificStudent" role="tabpanel" aria-labelledby="home-tab1">
              <form method="POST" id="submitSubjectSpecificStudets">
                <div class="row">
                  <div class="col-lg-3 col-6">
                    <select class="form-control selectStudentGrade" id="selectStudentGrade" name="selectStudentGrade" required>
                      <option>---Select Grade---</option>
                      <?php foreach($grade as $grades){ ?>
                        <option value="<?php echo $grades->grade; ?>"><?php echo $grades->grade; ?></option>
                      
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-lg-5 col-12 form-group subjectSpecificsSudentList table-responsive StudentViewTextInfo" style="height: 25vh;">
                    <label>Select Student</label>

                  </div>
                  <div class="col-lg-4 col-12 form-group subjectSpecificSubjectList table-responsive StudentViewTextInfo" style="height: 25vh;">
                    <label>Select Subject</label>
                  </div>
                  <div class="col-lg-12 col-12">
                    <button class="btn btn-primary pull-right" type="submit" id="saveSubjectSpecificStudents">Save Changes</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="tab-pane fade show" id="viewSubjectSpecificStudent" role="tabpanel" aria-labelledby="home-tab2">
              <div class="getStudentSpecificSubject"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  $('.gs-sms-manage-subject-page').addClass('active');
  getSubjectSpecificStudent();
  function getSubjectSpecificStudent()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>subject/fetchSubjectSpecificStudents/",
      method:"POST",
      beforeSend: function() {
        $('.getStudentSpecificSubject').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.getStudentSpecificSubject').html(data);
      }
    })
  }
  $(document).on('click', '#removeStudentSubject', function()
    {
      var userName=$(this).data("id");
      var subject=$(this).data("subject");
      var year=$(this).data("year");
      swal({
        title: 'Are you sure you want to remove this student subject?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
      if (willDelete) {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>subject/removeSUbjectSpecificStudent/",
        data: ({
          userName: userName,
          subject:subject,
          year:year
        }),
        cache: false,
        success: function(html){
          getSubjectSpecificStudent();
        }
      });
     }
    });
  });
  $('#submitSubjectSpecificStudets').on('submit', function(event) {
      event.preventDefault();
      var subjectGrade=$('#selectStudentGrade').val();
      subject=[];student=[];
      $("input[name='subject4SpecificStudents']:checked").each(function(i){
        subject[i]=$(this).val();
      });
      $("input[name='student4SpecificStudents']:checked").each(function(i){
        student[i]=$(this).val();
      });
      if( subject.length == 0 || student.length == 0 || $('.selectStudentGrade').val() =='')
      {
        alert("Oooops, Please select necessary fields.");
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>subject/saveSubjectSpecificStudents/",
        data: ({
          subject: subject,
          student:student,
          subjectGrade:subjectGrade
        }),
        cache: false,
        success: function(html){
          $('#submitSubjectSpecificStudets')[0].reset();
          iziToast.success({
            title: 'Data saved successfully.',
            message: '',
            position: 'topRight'
          });
          getSubjectSpecificStudent();
        }
      });
    }
  });
  $(document).on('change', '.selectStudentGrade', function() {
    /*grade2analysis=[];
    $("input[name='selectStudentGrade']:checked").each(function(i){
      grade2analysis[i]=$(this).val();
    });*/
    var grade2analysis=$('.selectStudentGrade').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>subject/filterStudentSpecificSTudent/",
      data:({
        grade2analysis:grade2analysis
      }),
      beforeSend: function() {
        $('.subjectSpecificsSudentList').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      success: function(data) {
        $(".subjectSpecificsSudentList").html(data);
      }
    });
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>subject/filterSubjectSpecificSTudent/",
      data:({
        grade2analysis:grade2analysis
      }),
      beforeSend: function() {
        $('.subjectSpecificSubjectList').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      success: function(data) {
        $(".subjectSpecificSubjectList").html(data);
      }
    });
  });
  $(document).on('change', '#change_subject_specific', function() {
    var grade=$(this).find('option:selected').attr('id');
    var specific=$(this).find('option:selected').attr('value');
    var subject=$(this).find('option:selected').attr('name');
    var academicYear=$('#renamedAcademicYear').val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>subject/update_subject_specific",
      data: ({
        subject:subject,
        grade: grade,
        specific:specific,
        academicYear:academicYear
      }),
      cache: false,
      beforeSend: function() {
        $('.gr' + grade).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
        );
      },
      success: function(html){
        if(html=='1'){
          $('.gr' + grade).html('<span class="text-success">Saved</span>');
          iziToast.success({
            title: 'Subject status changed successfully',
            message: '',
            position: 'topRight'
          });
        }else{
          $('.gr' + grade).html('<span class="text-danger">Not Saved</span>');
          iziToast.error({
            title: 'Please try again.',
            message: '',
            position: 'topRight'
          });
        }
      }
    });
  });
  /*----------------------------------------------------------------------------------------*/
  $(document).on('change', '#change_subject_branch', function() {
    var grade=$(this).find('option:selected').attr('id');
    var branch=$(this).find('option:selected').attr('value');
    var subject=$(this).find('option:selected').attr('name');
    var academicYear=$('#renamedAcademicYear').val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>subject/update_subject_branch",
      data: ({
        subject:subject,
        grade: grade,
        branch:branch,
        academicYear:academicYear
      }),
      cache: false,
      beforeSend: function() {
        $('.gr' + grade).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
        );
      },
      success: function(html){
        if(html=='1'){
          $('.gr' + grade).html('<span class="text-success">Saved</span>');
          iziToast.success({
            title: 'Subject status changed successfully',
            message: '',
            position: 'topRight'
          });
        }else{
          $('.gr' + grade).html('<span class="text-danger">Not Saved</span>');
          iziToast.error({
            title: 'Please try again.',
            message: '',
            position: 'topRight'
          });
        }
      }
    });
  });
  $(document).on('click', "input[name='subject_branch_enable']", function() {
    var lockmarkk=$(this).attr("value");
    var academicyear=$(this).attr("id");
    if($(this).is(':checked')){
      $.ajax({
        url:"<?php echo base_url() ?>subject/enable_subject_branch/",
        method:"POST",
        data:({
          academicyear:academicyear,
          lockmark:lockmarkk
        }),
        success: function(data){
          if(data=='1'){
            iziToast.success({
              title: 'Changes updated successfully.',
              message: '',
              position: 'topRight'
            });
            window.location.reload();
          }else if(data=='0'){
            iziToast.error({
              title: 'Changes not updated. Please try again',
              message: '',
              position: 'topRight'
            });
          }else if(data=='3'){
            iziToast.success({
              title: 'Changes inserted successfully.',
              message: '',
              position: 'topRight'
            });
            window.location.reload();
          }else{
            iziToast.error({
              title: 'Changes not inserted. Please try again',
              message: '',
              position: 'topRight'
            });
          } 
        }
      });
    }else{
      var lockmarkk=$(this).attr("value");
      var academicyear=$(this).attr("id");
      $.ajax({
        url:"<?php echo base_url() ?>subject/disable_subject_branch/",
        method:"POST",
        data:({
          academicyear:academicyear,
           lockmark:lockmarkk
        }),
        success: function(data){
          if(data=='1'){
            iziToast.success({
              title: 'Changes deleted successfully.',
              message: '',
              position: 'topRight'
            });
            window.location.reload();
          }else{
            iziToast.error({
              title: 'Changes not deleted. Please try again',
              message: '',
              position: 'topRight'
            });
          }
        }
      });
    }
  });
  $(document).on('change', '#grands_academicyear', function()
  {
    var academicYear=$('#grands_academicyear').val();
    $.ajax({
      url:"<?php echo base_url(); ?>subject/fetchYearSubject/",
      method:"POST",
      data: ({
        academicYear:academicYear
      }),
      beforeSend: function() {
        $('.subjectList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.subjectList').html(data);
      }
    })
  });
  $(document).on('change', '.customPercentageSubjectAnalysis', function() {
    var value=$(this).find('option:selected').attr('value');
    var subject=$(this).find('option:selected').attr('name');
    var grade=$(this).find('option:selected').attr('id');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Subject/updateSubjectPercentage/",
        data: ({
          subject:subject,
          value:value,
          grade:grade
        }),
        success: function(data) {
          $(".subjectPercentageNameHere").html(data);
        }
    });
  });
  $(document).on('click', '.percentageSubjectGrade', function() {
    grade2analysis=[];
    $("input[name='percentageSubjectGrade']:checked").each(function(i){
      grade2analysis[i]=$(this).val();
    });
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Subject/fetchSubject4CustomPercentage/",
      data:({
        grade2analysis:grade2analysis
      }),
      beforeSend: function() {
        $('.subjectPercentageNameHere').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
        );
      },
      success: function(data) {
        $(".subjectPercentageNameHere").html(data);
      }
    });
  });
  $(document).on('click', '.Remove_customized_subjectPercentage', function() {
    var quarter = $(this).attr("value");
    var grade = $(this).attr("id");
    var academicYear = $(this).attr("name");
    var subject = $(this).attr("title");
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Subject/deleteSubject4CustomPercentage/",
      data:({
        quarter:quarter,
        grade:grade,
        academicYear:academicYear,
        subject:subject
      }),
      beforeSend: function() {
        $('.subjectPercentageNameHere').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
        );
      },
      success: function(data) {
        $(".subjectPercentageNameHere").html(data);
      }
    });
  });
  /*----------------------------------------------------*/
  $(document).on('click', "input[name='change_subject_status']", function() {
    var grade = $(this).attr("value");
    var subject = $(this).attr("id");
    var academicYear = $(this).attr("class");
    if($(this).is(':checked')){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>subject/on_subject_status/",
          data: ({
            grade:grade,
            subject:subject,
            academicYear:academicYear
          }),
          cache: false,
          success: function(html) {
            iziToast.success({
              title: 'Subject status saved successfully',
              message: '',
              position: 'topRight'
            });
          }
        });
    }else{
      var grade = $(this).attr("value");
      var subject = $(this).attr("id");
      var academicYear = $(this).attr("class");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>subject/off_subject_status/",
        data: ({
          grade:grade,
          subject:subject,
          academicYear:academicYear
        }),
        cache: false,
        success: function(html) {
          iziToast.success({
            title: 'Subject status saved successfully',
            message: '',
            position: 'topRight'
          });
        }
      });
    }
  });
  $(document).on('change', '#subListOrder', function() {
    var value=$(this).find('option:selected').attr('value');
    var sasgrade=$(this).find('option:selected').attr('name');
    var sasname=$(this).find('option:selected').attr('class');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>subject/updategroupOrder/",
        data: ({
          value:value,
          sasgrade:sasgrade,
          sasname:sasname
        }),
        success: function(data) {
          iziToast.success({
            title: 'Subject status saved successfully.',
            message: '',
            position: 'topRight'
          });
        }
    });
  });
  $(document).on('click', '.deleteSubSubject', function()
    {
      var removesub=$(this).attr("value");
      swal({
        title: 'Are you sure you want to delete this subject list?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
      if (willDelete) {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>subject/removeSubSubject/",
        data: ({
          removesub: removesub
        }),
        cache: false,
        success: function(html){
          loadSubSubjectData();
          $(".deletesubSubject" + removesub ).fadeOut('slow');
        }
      });
     }
    });
  });
    loadSubSubjectData();
    function loadSubSubjectData()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>subject/fetchSubSubject/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_sub_subject').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.fetch_sub_subject').html(data);
        }
      })
    }
  $(document).on('click', '.subSubjectGrade', function() {
    grade2analysis=[];
    $("input[name='subSubjectGrade']:checked").each(function(i){
      grade2analysis[i]=$(this).val();
    });
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Subject/fetchSubject4SubSubject/",
      data:({
        grade2analysis:grade2analysis
      }),
      beforeSend: function() {
        $('.subjectNameHere').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
        );
      },
      success: function(data) {
        $(".subjectNameHere").html(data);
      }
    });
  });
  $('#saveSubSubject').on('click', function(event) {
    grade=[];
    $("input[name='subSubjectGrade']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    subject=[];
    $("input[name='subSubjectAnalysis']:checked").each(function(i){
      subject[i]=$(this).val();
    });
    var subSubjectName=$('#subSubjectName').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Subject/saveSubSubjectName/",
      data:({
        subject:subject,
        subSubjectName:subSubjectName,
        grade:grade
      }),
      beforeSend: function() {
        $('.fetch_sub_subject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
        );
      },
      success: function(data) {
        $(".fetch_sub_subject").html(data);
        loadSubSubjectData();
        $('input[name="subSubjectAnalysis"]').each(function() {
          this.checked = false;
        });
        $('input[name="subSubjectGrade"]').each(function() {
          this.checked = false;
        });
        $('#subSubjectName').val('');
      }
    });
  });
   
</script>
<script type="text/javascript">
  $(document).ready(function(){
    loadSubjectData();
    function loadSubjectData()
    {
      var academicYear=$('#grands_academicyear').val();
      $.ajax({
        url:"<?php echo base_url(); ?>subject/fetchSubject/",
        method:"POST",
        data:({
          academicYear:academicYear
        }),
        beforeSend: function() {
          $('.subjectList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.subjectList').html(data);
        }
      })
    }
    $('#saveNewSubject').on('submit', function(event) {
      event.preventDefault();
      var subjectName=$('#subjectName').val();
      var academicYear=$('#academicYearNewSubject').val();
      subjectGrade=[];subjectLetter=[];
      $("input[name='subjectGrade']:checked").each(function(i){
        subjectGrade[i]=$(this).val();
        subjectLetter[i]=$("input[name='subjectLetter" + subjectGrade[i]+  "']:checked").val();
      });
      /*$("input[name='subjectLetter']:checked").each(function(i){
        subjectLetter[i]=$(this).val();
      });*/
      if( subjectGrade.length == 0 || $('#subjectName').val() =='')
      {
        alert("Oooops, Please select necessary fields.");
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>subject/saveNewSubject/",
        data: ({
          subjectName: subjectName,
          subjectGrade:subjectGrade,
          subjectLetter:subjectLetter,
          academicYear:academicYear
        }),
        cache: false,
        success: function(html){
          $('#saveNewSubject')[0].reset();
          loadSubjectData();
          if(html=='1'){
            iziToast.success({
              title: 'Subject saved successfully.',
              message: '',
              position: 'topRight'
            });
          }else{
            iziToast.error({
              title: 'Ooops, Subject won`t saved.<br> May be subject found',
              message: '',
              position: 'topRight'
            });
          }
        }
      });
    }
  });
  $(document).on('click', '.backToSubject', function()
  {
    var academicYear=$('#grands_academicyear').val();
    $.ajax({
      url:"<?php echo base_url(); ?>subject/fetchSubject/",
      data: ({
        academicYear: academicYear
      }),
      method:"POST",
      beforeSend: function() {
        $('.subjectList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
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
<!-- Edit merged subject value -->
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data(){
      $.ajax({
        method:"POST",
        url:"<?php echo base_url() ?>subject/FetchMergedSubject/",
        cache: false,
        beforeSend: function() {
          $('#fetch_merged_subject').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success:function(html){
          $('#fetch_merged_subject').html(html);
        }
      });
    }
    $(document).on('change', '.lettery', function() {
      var valuee=$(this).find('option:selected').attr('value');
      var grade=$(this).find('option:selected').attr('id');
      var subjdd=$('#subid').val();
      var mname=$('#mname').val();
      if($('#subid').val()!='' && $('#mname').val()!=''){
        if($('#subid').val()!=$('#mname').val()){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>subject/UpdateMergedSubjectvalue",
            data: ({
              mname:mname,
              grade: grade,
              valuee:valuee,
              subjdd:subjdd
            }),
            cache: false,
            beforeSend: function() {
              $('#saveskygrade').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
              );
            },
            success: function(html){
              $('#saveskygrade').html(html);
              $('.lettery').val('');
              load_data();
            }
          });
        }else{
          swal('Merged subject name should not be the same with subject lists!', {
            icon: 'error',
          });
        }
      }else{
        swal('Please Select all necessary fields!', {
          icon: 'error',
        });
      }
    });
    $(document).on('change', '#percentageGrade', function() {
      var grade=$(this).find('option:selected').attr('id');
      var valuee=$(this).find('option:selected').attr('value');
      var mname=$(this).find('option:selected').attr('name');
      var academicYear=$('#renamedAcademicYear').val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>subject/updateEachSubjectPercentage",
        data: ({
          mname:mname,
          grade: grade,
          valuee:valuee,
          academicYear:academicYear
        }),
        cache: false,
        beforeSend: function() {
          $('.gr' + grade).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
          );
        },
        success: function(html){
          if(html=='1'){
            $('.gr' + grade).html('Saved');
            iziToast.success({
              title: 'Subject status saved successfully',
              message: '',
              position: 'topRight'
            });
          }else{
            $('.gr' + grade).html('Not Saved');
            iziToast.error({
              title: 'Please try again.',
              message: '',
              position: 'topRight'
            });
          }
        }
      });
    });
    $(document).on('click', '.removemerged', function()
    {
      var removesub=$(this).attr("value");
      var removesub2=$(this).attr("name");
      swal({
        title: 'Are you sure you want to delete this merged subject?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
      if (willDelete) {
        swal('Merged subject deleted successfully!', {
          icon: 'success',
        });
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>subject/removeMergedSubject/",
        data: ({
          removesub: removesub,
          removesub2: removesub2
        }),
        cache: false,
        success: function(html){
          load_data();
        }
      });
     }
    });
  });
  });
</script>
<!-- edit subject script -->
<script type="text/javascript">
  $(document).on('click', '.changeme', function()
  {
    var gradejoss=$(this).attr("name");
    var letterjoss=$(this).attr("value");
    var subjjoss=$(this).attr("id");
    var academicYear=$('#renamedAcademicYear').val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>subject/updateSubjectForLetter/",
      data: ({
        gradejoss: gradejoss,
        letterjoss: letterjoss,
        subjjoss:subjjoss,
        academicYear:academicYear
      }),
      cache: false,
      beforeSend: function() {
        $('.gr' + gradejoss).html( 'Saving<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
        );
      },
      success: function(html){
        $('.gr' + gradejoss).html(html);
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '.deleleSubjectGS', function()
  {
    var gradename=$(this).attr("name");
    var subjname=$(this).attr("value");
    var academicYear=$('#renamedAcademicYear').val();
    swal({
      title: 'Are you sure?',
      text: 'Once deleted you can not recover this subject mark and other files!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>subject/deleteOneSubject/",
          data: ({
            gradename: gradename,
            subjname: subjname,
            academicYear:academicYear
          }),
          cache: false,
          beforeSend: function() {
            $('#deletee' + subjname + gradename).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
            );
          },
          success: function(html){
            $('#deletee' + subjname + gradename).fadeOut('slow');
            swal('Subject deleted successfully!', {
              icon: 'success',
            });
          }
        });
      }
    });
  });
</script>
<script>
  $(document).on('click', '.editSubject', function(){
    var edtisub=$(this).attr("value");
    var year=$(this).attr("name");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>subject/fetchSubjectToEdit/",
      data: ({
        edtisub: edtisub,
        year:year
      }),
      cache: false,
      beforeSend: function() {
        $('.subjectList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      success: function(html){
        $('.subjectList').html(html);
      }
    });
  });
  function loadSubjectData()
  {
    var academicYear=$('#grands_academicyear').val();
    $.ajax({
      url:"<?php echo base_url(); ?>subject/fetchSubject/",
      data: ({
        academicYear: academicYear
      }),
      method:"POST",
      beforeSend: function() {
        $('.subjectList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="84" height="84" id="loa">');
      },
      success:function(data){
        $('.subjectList').html(data);
      }
    })
  }
  $(document).on('click', '.saveSubjectChangesGS', function(){
    var newsubjName=$('#newSubjNameGrade').val();
    var oldsubjName=$('#oldSubjNameGrade').val();
    var academicYear=$('#renamedAcademicYear').val();
    if($('#newSubjNameGrade').val()!=''){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>subject/updateSubjectName/",
        data: ({
          newsubjName: newsubjName,
          oldsubjName:oldsubjName,
          academicYear:academicYear
        }),
        cache: false,
        beforeSend: function() {
          $('.subjectList').html( 'Saving<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(html){
          loadSubjectData();
        }
      });
    }else{
      swal('Please enter new subject name!', {
        icon: 'error',
      });
    }
  });
</script>
<!-- edit subject ends -->
<script>
  $(document).on('click', "input[name='changeOnRpGS']", function() {
    var subject=$(this).attr("id");
    var grade=$(this).attr("value");
    var academicYear=$('#renamedAcademicYear').val();
    if($(this).is(':checked')){
      var onreportcard='1';
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Subject/onreportcard/",
        data: ({
          onreportcard: onreportcard,
          subject: subject,
          grade:grade,
          academicYear:academicYear
        }),
        cache: false,
        beforeSend: function() {
          $('.gr' + grade).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(html){
          $('.gr' + grade).html(html);
        }
      });
    }else{
      var onreportcard='0';
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Subject/onreportcard/",
        data: ({
          onreportcard: onreportcard,
          subject: subject,
          grade:grade,
          academicYear:academicYear
        }),
        cache: false,
        beforeSend: function() {
          $('.gr' + grade).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(html){
          $('.gr' + grade).html(html);
        }
      });
    }
  });
</script>
<script>
  function loadSubjectData()
  {
    var academicYear=$('#grands_academicyear').val();
    $.ajax({
      url:"<?php echo base_url(); ?>subject/fetchSubject/",
      data: ({
        academicYear: academicYear
      }),
      method:"POST",
      beforeSend: function() {
        $('.subjectList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.subjectList').html(data);
      }
    })
  }
  $(document).on('click', '.deletesubject', function(){
    var post_id = $(this).attr("id");
    var academicYear=$(this).attr("name");
    swal({
        title: 'Are you sure?',
        text: 'Once deleted this subject you can not recover this subject mark and other files!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Subject/subjectDelete/",
          data: ({
            post_id: post_id,
            academicYear:academicYear
          }),
          cache: false,
          success: function(html) {
            loadSubjectData();
            swal('Subject deleted successfully!', {
              icon: 'success',
            });
          }
        });
      }
    });
  });
</script>
<script>
  $(document).ready(function() {
    function fetch_mergedsubject_status()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>fetch_mergedsubject_status",
        method:"POST",
        success:function(data){
          $('#evaluation_status').html(data);
        }
      })
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
    unseen_notification();
    inbox_unseen_notification();
    fetch_mergedsubject_status();
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
      fetch_mergedsubject_status();
      inbox_unseen_notification();
    }, 5000);
  });
</script>
</html>