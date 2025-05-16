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
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
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
              <div class="col-lg-12 col-12">
                <a href="#" class="AddNewSubject pull-right" value="" data-toggle="modal" data-target="#remotePlacement"><span class="text-success">
                <button class="btn btn-primary"><i data-feather="plus-circle"> </i>Remote Branch Placement</button>
               </span>
               </a>
              </div>
              <div class="col-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#feedSubject" role="tab" aria-selected="true"> New Placement</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#feedMergedSubject" role="tab" aria-selected="false">View Placement</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="feedSubject" role="tabpanel" aria-labelledby="home-tab1">
                      <form id="save_placement" method="POST"> 
                          <div class="row">
                           <div class="col-lg-3 col-12 form-group">
                              <label for="Staff"> Select staff to assign </label>
                             <select class="form-control selectric" required="required" name="staffplacement" id="staffplacement">
                             <option></option>
                              <?php foreach($staffs as $staff) { ?>
                              <option value="<?php echo $staff->username;?>"><?php echo $staff->fname.' '.$staff->mname;echo '('; echo $staff->username;echo ')';
                              ?></option>
                            <?php }?>
                             </select>
                          </div>
                          <div class="col-lg-4 col-12 form-group table-responsive" style="height: 25vh;">
                            <label for="Grade"> Select grade to assign</label><br>
                            <div class="row">
                              <?php foreach($gradesec as $gradesecs){ ?>
                              <div class="col-lg-4 col-6">
                                <div class="pretty p-icon p-bigger">
                                  <input type="checkbox" name="gradeplacement" value="<?php echo $gradesecs->gradesec;?>" class="gradeplacement" id="customCheck1">
                                  <div class="state p-info">
                                    <i class="icon material-icons"></i>
                                    <label></label><?php echo $gradesecs->gradesec; ?>
                                  </div>
                                </div>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="col-lg-5 col-12 form-group subjectplacement table-responsive" style="height: 25vh;">
                            <label for="subject">Select subject to assign </label><br>
                            
                          </div>
                          <div class="col-lg-12 col-12 pull-right">
                            <button type="submit" id="postplacement" class="btn btn-info pull-right">Save Placement </button>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="tab-pane fade show" id="feedMergedSubject" role="tabpanel" aria-labelledby="home-tab2">
                      <input type="text" name="searchStudent" id="searchStudent" class="form-control typeahead" placeholder="Search staff...">
                        <div class="dropdown-divider"></div>
                      <div class="fetch_placement"></div>
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
  <div class="modal fade" id="remotePlacement" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Manage Remote Teacher Placement</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="modal-body">
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab11" data-toggle="tab" href="#newRemotePlacement" role="tab" aria-selected="true"> New Remote Placement</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab22" data-toggle="tab" href="#viewRemotePlacement" role="tab" aria-selected="false">View Remote Placement</a>
              </li>
            </ul>
            <div class="tab-content tab-bordered" id="myTab3Content">
              <div class="tab-pane fade show active" id="newRemotePlacement" role="tabpanel" aria-labelledby="home-tab11">
                <form method="POST" class="saveRemotePlacement">
                  <div class="row">
                    <div class="col-lg-6 col-6">
                      <label for="Staff"> Staff To Assign </label>
                       <select class="form-control staffRemote" required="required" name="staffRemote" id="staffRemote">
                       <option></option>
                        <?php foreach($staffs as $staff) { ?>
                        <option value="<?php echo $staff->username;?>"><?php echo $staff->fname.' '.$staff->mname;echo '('; echo $staff->username;echo ')';
                        ?></option>
                      <?php }?>
                       </select>
                    </div>
                    <div class="col-lg-6 col-6">
                      <label for="Staff"> Remote Branch </label>
                      <div class="form-group">
                        <select class="form-control" required="required" name="branchRemote" id="branchRemote">
                          <option></option> 
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-5 col-12 table-responsive" style="height: 30vh;">
                      <label for="Grade"> Select grade to assign</label><br>
                      <div class="row">
                        <?php foreach($gradesec as $gradesecs){ ?>
                        <div class="col-lg-3 col-6">
                          <div class="pretty p-icon p-bigger">
                            <input type="checkbox" name="gradeRemote" value="<?php echo $gradesecs->gradesec;?>" class="gradeRemote" id="customCheck1">
                            <div class="state p-info">
                              <i class="icon material-icons"></i>
                              <label></label><?php echo $gradesecs->gradesec; ?>
                            </div>
                          </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-lg-7 col-12 table-responsive" style="height: 30vh;">
                      <label for="subject">Select subject to assign </label>
                      <div class="row">
                        <?php foreach($subjects as $subject){ ?>
                          <div class="col-lg-6 col-12">
                            <div class="pretty p-icon p-bigger">
                              <input type="checkbox" name="subjectRemote" value="<?php echo $subject->Subj_name;?>" class="subjectRemote" id="customCheck1">
                              <div class="state p-success">
                                <i class="icon material-icons"></i>
                                <label></label><?php echo $subject->Subj_name ;?>
                              </div>
                            </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-lg-12 col-12 pull-right">
                      <div class="dropdown-divider"></div>
                      <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                      <button type="submit" name="saveRemotePlacement" id="saveRemotePlacement" class="btn btn-primary pull-right"> Save Remote Placement </button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane fade show" id="viewRemotePlacement" role="tabpanel" aria-labelledby="home-tab2">
                <div class="fetch_remote_placement"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  
</body>
<script type="text/javascript">
  $(document).ready(function() { 
    $(document).on('click', '.gradeplacement', function() {
      grade2analysis=[];
      $("input[name='gradeplacement']:checked").each(function(i){
        grade2analysis[i]=$(this).val();
      });
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Placement/filterSubject4Evaluation/",
        data:({
          grade2analysis:grade2analysis
        }),
        beforeSend: function() {
          $('.subjectplacement').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".subjectplacement").html(data);
        }
      });
    }); 
    $("#staffRemote").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_staff_branch/",
        data: "staffRemote=" + $("#staffRemote").val(),
         beforeSend: function() {
          $('#branchRemote').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#branchRemote").html(data);
        }
      });
    });
    loadRemotedata();
    function loadRemotedata()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Placement/fetch_remote_placement/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_remote_placement').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.fetch_remote_placement').html(data);
        }
      })
    }
    $('.saveRemotePlacement').on('submit', function(event) {
      event.preventDefault();
      var staff=$('#staffRemote').val();
      var branchRemote=$('#branchRemote').val();
      grade=[];subject=[];
      $("input[name='gradeRemote']:checked").each(function(i){
        grade[i]=$(this).val();
      });
      $("input[name='subjectRemote']:checked").each(function(i){
        subject[i]=$(this).val();
      });
      if(grade.length!=0 && subject.length!=0){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Placement/postRemotePlacement/",
          data: ({
            grade: grade,
            branchRemote:branchRemote,
            staff:staff,
            subject:subject
          }),
          cache: false,
          success: function(html){
            $('.saveRemotePlacement')[0].reset();
            loadRemotedata();
          }
        });
      }else{
        swal('Please select all fields !', {
          icon: 'error',
        });
      }
    });
    $(document).on('click', '#delete_remotestaffplacemet', function()
    {
      var staffGrade=$(this).attr("name");
      var staffSubject=$(this).attr("value");
      var staffName=$(this).attr("class");
      var staff_branch=$(this).attr("title");
      swal({
        title: 'Are you susre you want to delete this Placement?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Placement/Delete_remote_staffplacement/",
            data: ({
              staffGrade:staffGrade,
              staffSubject:staffSubject,
              staffName:staffName,
              staff_branch:staff_branch
            }),
            cache: false,
            beforeSend: function() {
              $('.delete_remotestaffplacemet').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
              );
            },
            success: function(html){
             $('.delete_remotestaffplacemet' + staffGrade + staffSubject).fadeOut('slow');
             loadRemotedata();
            }
          });
        }
      });
    }); 
    $(document).on('click', '#delete_remotestaffAllplacemet', function()
    {
      var staffName=$(this).attr("value");
      swal({
        title: 'Are you susre you want to delete this Placement?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Placement/Delete_remote_staffAllplacement/",
            data: ({
              staffName:staffName
            }),
            cache: false,
            beforeSend: function() {
              $('.delete_remotedirectorplacementRow' + staffName).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
              );
            },
            success: function(html){
             loadRemotedata();
            }
          });
        }
      });
    }); 
  });
  $(document).ready(function() { 
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Placement/fetch_placement/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_placement').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.fetch_placement').html(data);
        }
      })
    }
    $('#searchStudent').on("keyup",function() {
      $searchItem=$('#searchStudent').val();
      if($('#searchStudent').val()!=''){
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>Placement/searchTeacherPlacementStaffs/",
          data: "searchItem=" + $("#searchStudent").val(),
          beforeSend: function() {
            $('.fetch_placement').html( 'Searching<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $(".fetch_placement").html(data);
          }
        });
      }else{
        load_data();
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Placement/fetch_placement/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_placement').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.fetch_placement').html(data);
        }
      })
    }
  $(document).on('click', '#postplacement', function(event) {
    event.preventDefault();
    var staff=$('#staffplacement').val();
    /*var subject=$('.subject4Evaluation').val();*/
    id=[];subject=[];
    $("input[name='gradeplacement']:checked").each(function(i){
      id[i]=$(this).val();
    });
    $("input[name='subject4Evaluation']:checked").each(function(i){
      subject[i]=$(this).val();
    });
    if(subject.length==0 || id.length==0){
      swal({
        title: 'Please select all necessary fields',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
    }else{
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Placement/post_placement/",
        data: ({
          id: id,
          staff:staff,
          subject:subject
        }),
        dataType: "json",
        success: function(data) {
          if(data.msg=='success'){
            iziToast.success({
              title: 'Saved successfully.',
              message: '',
              position: 'topRight'
            });
            load_data();
            $('#staffplacement').val('');
            $("input[name='gradeplacement']:checked").each(function(i){
              $(this).prop('checked', false);
            });
            $("input[name='subject4Evaluation']:checked").each(function(i){
              $(this).prop('checked', false);
            });
          }else if(data.msg=='error'){
            iziToast.error({
              title: 'Oooops Please try again.',
              message: '',
              position: 'topRight'
            });
          }else if(data.pfound!='' && data.not_Created!=''){
            iziToast.error({
              title: 'Placement found for ' + data.pfound + '' ,
              message:'' ,
              position: 'topRight'
            });
            load_data();
            $('#staffplacement').val('');
            $("input[name='gradeplacement']:checked").each(function(i){
              $(this).prop('checked', false);
            });
            $("input[name='subject4Evaluation']:checked").each(function(i){
              $(this).prop('checked', false);
            });
          }else if(data.not_Created!=''){
            iziToast.error({
              title: 'Subject not created' + data.not_Created + '',
              message:'' ,
              position: 'topRight'
            });
            load_data();
            $('#staffplacement').val('');
            $("input[name='gradeplacement']:checked").each(function(i){
              $(this).prop('checked', false);
            });
            $("input[name='subject4Evaluation']:checked").each(function(i){
              $(this).prop('checked', false);
            });
          }else if(data.pfound!=''){
            iziToast.error({
              title: 'Placement found for ' + data.pfound + '',
              message:'' ,
              position: 'topRight'
            });
            load_data();
            $('#staffplacement').val('');
            $("input[name='gradeplacement']:checked").each(function(i){
              $(this).prop('checked', false);
            });
            $("input[name='subject4Evaluation']:checked").each(function(i){
              $(this).prop('checked', false);
            });
          }else{
            iziToast.error({
              title: 'Oooops Please try again',
              message: '',
              position: 'topRight'
            });
          }
        }
      });
    }
  });
  $(document).on('click', '#delete_staffplacemet', function()
  {
    var staffGrade=$(this).attr("name");
    var staffSubject=$(this).attr("value");
    var staffName=$(this).attr("class");
    swal({
      title: 'Are you susre you want to delete this Staff Placement?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Placement/Delete_staffplacement/",
          data: ({
            staffGrade:staffGrade,
            staffSubject:staffSubject,
            staffName:staffName
          }),
          cache: false,
          beforeSend: function() {
            $('.delete_staffplacement').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
            );
          },
          success: function(html){
           $('.delete_staffplacement' + staffGrade + staffSubject).fadeOut('slow');
           load_data();
          }
        });
      }
    });
  }); 
  $(document).on('click', '#delete_staffAllplacemet', function()
  {
    var staffName=$(this).attr("value");
    swal({
      title: 'Are you susre you want to delete this Staff Placement?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Placement/Delete_staffAllplacement/",
          data: ({
            staffName:staffName
          }),
          cache: false,
          beforeSend: function() {
            $('.delete_staffplacement').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
            );
          },
          success: function(html){
           load_data();
          }
        });
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