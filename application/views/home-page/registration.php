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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
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
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#manualPromotion" role="tab" aria-selected="true">Manual Promotion</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#automaticPromotion" role="tab" aria-selected="false">Automatic Promotion</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#nonRegistered" role="tab" aria-selected="false">Not Registered Lists</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab4" data-toggle="tab" href="#nonPromoted" role="tab" aria-selected="false">Not Promoted Lists</a>
                      </li>
                    </ul>
                  </div>
                  <div class="tab-content tab-bordered" id="myTab3Content">
                    <div class="tab-pane fade show active" id="manualPromotion" role="tabpanel" aria-labelledby="home-tab1">
                      <div class="">
                        <form id="fetch_prmotion">
                         <div class="row">
                           <div class="col-lg-3 col-6">
                             <div class="form-group">
                               <select class="form-control selectric" required="required" name="reportaca" id="academicyearRegistration">
                               <option>---Select Academic Year---</option>
                                <?php foreach($academicyearlist as $academicyears){ ?>
                                  <option value="<?php echo $academicyears->id;?>">
                                  <?php echo $academicyears->year_name;?>
                                  </option>
                                <?php }?>
                               </select>
                              </div>
                             </div>
                             <div class="col-lg-3 col-6">
                                <div class="form-group">
                                 <select class="custom-select" required="required" name="branch" id="branchRegistration">
                                 <option> --- Select Branch --- </option>
                                  </select>
                                </div>
                              </div>
                             <div class="col-lg-3 col-6">
                              <div class="form-group">
                               <select class="custom-select" required="required" name="gradesec" id="gradesec_rg">
                                <option> --- Select Grade --- </option>
                               
                               </select>
                              </div>
                             </div>
                             <div class="col-lg-3 col-6">
                               <button class="btn btn-primary btn-block" type="submit">View</button>
                             </div>
                          </div>
                        </form>
                        <div class="check_detained"></div>
                        <div class="StudentViewTextInfo listPlacement"></div>
                      </div>
                    </div>
                    <div class="tab-pane fade show" id="automaticPromotion" role="tabpanel" aria-labelledby="home-tab2">
                      <div class="row">
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                           <select class="form-control selectric" required="required" name=""
                           id="fromAcademicYear">
                           <option>---From Academic Year---</option>
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                              <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                           </select>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                           <select class="form-control selectric" required="required" name=""
                           id="toAcademicYear">
                           <option>---To Academic Year---</option>
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                              <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                           </select>
                          </div>
                        </div>

                        <div class="col-md-3 col-12">
                          <button class="btn btn-primary btn-block" id="startAutoPromotion">Start Automatic Promotion</button>
                        </div>
                        <div class="col-md-12">
                          <div id="automaticPromotionInfo"></div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade show" id="nonRegistered" role="tabpanel" aria-labelledby="home-tab3">
                      <div class="">
                        <form id="fetch_prmotionNon">
                         <div class="row">
                           <div class="col-lg-3 col-6">
                             <div class="form-group">
                               <select class="form-control selectric" required="required" name="academicyearNon" id="academicyearNon">
                               <option>---Select Academic Year---</option>
                                <?php foreach($academicyearlist as $academicyears){ ?>
                                  <option value="<?php echo $academicyears->id;?>">
                                  <?php echo $academicyears->year_name;?>
                                  </option>
                                <?php }?>
                               </select>
                              </div>
                             </div>
                             <div class="col-lg-3 col-6">
                                <div class="form-group">
                                 <select class="custom-select" required="required" name="branchRegistrationNon" id="branchRegistrationNon">
                                 <option> --- Select Branch --- </option>
                                  </select>
                                </div>
                              </div>
                             <div class="col-lg-3 col-6">
                              <div class="form-group">
                               <select class="custom-select" required="required" name="gradesecNon" id="gradesecNon">
                                <option> --- Select Grade --- </option>
                               
                               </select>
                              </div>
                             </div>
                             <div class="col-lg-3 col-6">
                               <button class="btn btn-primary btn-block" type="submit">View</button>
                             </div>
                          </div>
                        </form>
                        <div class="listPlacementNon"></div>
                      </div>
                    </div>
                    <div class="tab-pane fade show" id="nonPromoted" role="tabpanel" aria-labelledby="home-tab4">
                      <div class="row">
                        <div class="col-lg-12 col-12">
                          <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedySheet()">
                          <span class="text-black">
                            <i data-feather="printer"></i>
                          </span>
                          </button>
                        </div>
                      </div>
                      <form id="fetch_nonPromotion">
                       <div class="row">
                         <div class="col-lg-3 col-6">
                           <div class="form-group">
                             <select class="form-control selectric" required="required" name="academicyearNonPromotion" id="academicyearNonPromotion">
                             <option>---Select Academic Year---</option>
                              <?php foreach($academicyearlist as $academicyears){ ?>
                                <option value="<?php echo $academicyears->id;?>">
                                <?php echo $academicyears->year_name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                          </div>
                          <div class="col-lg-3 col-6">
                            <div class="form-group">
                              <select class="custom-select" required="required" name="branchRegistrationNonPromotion" id="branchRegistrationNonPromotion">
                               <option> --- Select Branch --- </option>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-3 col-6">
                            <div class="form-group">
                             <select class="custom-select" required="required" name="gradesecNonPromotion" id="gradesecNonPromotion">
                              <option> --- Select Grade --- </option>
                             
                             </select>
                            </div>
                          </div>
                          <div class="col-lg-3 col-6">
                            <button class="btn btn-primary btn-block" type="submit">View</button>
                          </div>
                        </div>
                      </form>
                      <div class="listPlacementNonPromotion" id="listPlacementNonPromotion"></div>
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
  <div class="modal fade" id="register_thisyeare_student" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Re-register student</h5>          
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="card-body StudentViewTextInfo">
            <div class="modal-body">
              <div class="fetch_dropoutstudents_gs_page"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
  function codespeedySheet(){
    var print_div = document.getElementById("listPlacementNonPromotion");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  $('#fetch_nonPromotion').on('submit', function(event) { //done
    event.preventDefault();
    var academicyear=$('#academicyearNonPromotion').val();
    var gradesec_rg=$("#gradesecNonPromotion").val();
    var branch=$("#branchRegistrationNonPromotion").val();
    $.ajax({
      url: "<?php echo base_url(); ?>registration/fetchFailedStudents/",
      method: "POST",
      data: ({
        gradesec:gradesec_rg,
        branch:branch,
        academicyear:academicyear
      }),
      beforeSend: function() {
        $('.listPlacementNonPromotion').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="36" height="36" id="loa">' );
      },
      success: function(data) {
        $(".listPlacementNonPromotion").html(data);
      }
    })
  });
  $(document).ready(function() {  
    $("#academicyearNonPromotion").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>registration/Fetch_academicyear_branch_non_registration/",
        data: "academicyear=" + $("#academicyearNonPromotion").val(),
        beforeSend: function() {
          $('#branchRegistrationNonPromotion').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#branchRegistrationNonPromotion").html(data);
        }
      });
    });
  });
  $(document).ready(function() {
    $("#branchRegistrationNonPromotion").bind("change", function() {
      var branchRegistration=$("#branchRegistrationNonPromotion").val();
      var academicyear=$("#academicyearNonPromotion").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>registration/filtergrade_4_non_registeration/",
        data:({
          branchRegistration:branchRegistration,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#gradesecNonPromotion').html( 'Loading...');
        },
        success: function(data) {
          $("#gradesecNonPromotion").html(data);
        }
      });
    });
  });
  $(document).on('submit', '#registerLast_YearStudent', function(event) { //done
    event.preventDefault();
    var register_id = $("#register_laststudent_id").val();
    var yearDrooped = $("#register_laststudent_year").val();
    var grade = $("#register_on_grade_last").val();
    var branch = $("#register_on_branch_last").val();
    var registerOnYear = $("#register_on_year_last").val();
    swal({
      title: 'Are you sure you want to Register this student?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>registration/register_student/",
          data: ({
            register_id: register_id,
            yearDrooped:yearDrooped,
            grade:grade,
            branch:branch,
            registerOnYear:registerOnYear
          }),
          cache: false,
          success: function(html) {
            if(html=='1'){
              iziToast.success({
                title: 'Student has been registered successfully',
                message: '',
                position: 'topRight'
              });
            }else if(html=='2'){
              iziToast.error({
                title: 'Oooops, Student already registered.',
                message: '',
                position: 'topRight'
              });
            }else{
              iziToast.error({
                title: 'Oooops, Please try again.',
                message: '',
                position: 'topRight'
              });
            }
            $('#register_thisyeare_student'). modal('hide');
          }
        });
      }else {
        return false;
      }
    });
  });
  $(document).on('click', '.registerthis_student', function() {
    var register_id = $(this).attr("id");
    var yearDrooped = $(this).attr("value");
    var username = $(this).attr("name");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>registration/fecth_student_toregister/",
      data: ({
        register_id: register_id,
        yearDrooped:yearDrooped,
        username:username
      }),
      cache: false,
      beforeSend: function() {
        $('.fetch_dropoutstudents_gs_page').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(html) {
        $('.fetch_dropoutstudents_gs_page').html(html);
      }
    });
  });
  $('#fetch_prmotionNon').on('submit', function(event) {
    event.preventDefault();
    var academicyear=$('#academicyearNon').val();
    var gradesec_rg=$("#gradesecNon").val();
    var branch=$("#branchRegistrationNon").val();
    $.ajax({
      url: "<?php echo base_url(); ?>registration/fetch_grade_non_registration/",
      method: "POST",
      data: ({
        gradesec:gradesec_rg,
        branch:branch,
        academicyear:academicyear
      }),
      beforeSend: function() {
        $('.listPlacementNon').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="36" height="36" id="loa">' );
      },
      success: function(data) {
        $(".listPlacementNon").html(data);
      }
    })
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#startAutoPromotion").on("click", function() {
      var toAcademicYear=$("#toAcademicYear").val();
      var fromAcademicYear=$("#fromAcademicYear").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>registration/startAutoPromotion/",
        data:({
          toAcademicYear:toAcademicYear,
          fromAcademicYear:fromAcademicYear
        }),
        beforeSend: function() {
          $('#automaticPromotionInfo').html( 'Please wait...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#automaticPromotionInfo").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#academicyearNon").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>registration/Fetch_academicyear_branch_non_registration/",
        data: "academicyear=" + $("#academicyearNon").val(),
        beforeSend: function() {
          $('#branchRegistrationNon').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#branchRegistrationNon").html(data);
        }
      });
    });
  });
  $(document).ready(function() {
    $("#branchRegistrationNon").bind("change", function() {
      var branchRegistration=$("#branchRegistrationNon").val();
      var academicyear=$("#academicyearNon").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>registration/filtergrade_4_non_registeration/",
        data:({
          branchRegistration:branchRegistration,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#gradesecNon').html( 'Loading...');
        },
        success: function(data) {
          $("#gradesecNon").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#academicyearRegistration").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>registration/Fetch_academicyear_branch_non_registration/",
        data: "academicyear=" + $("#academicyearRegistration").val(),
        beforeSend: function() {
          $('#branchRegistration').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#branchRegistration").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#branchRegistration").bind("change", function() { 
      var branchRegistration=$("#branchRegistration").val();
      var academicyear=$("#academicyearRegistration").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>registration/filtergrade_4_non_registeration/",
        data:({
          branchRegistration:branchRegistration,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#gradesec_rg').html( 'Loading...');
        },
        success: function(data) {
          $("#gradesec_rg").html(data);
        }
      });
    });
  });
  $(document).on('click', '#promoteStudent', function() {  //done
    stuid=[];
    var nextGrade=$(this).attr("value");
    var academicyear=$('#academicyearRegistration').val();
    $("input[name='stuId[ ]']:checked").each(function(i){
      stuid[i]=$(this).val();
    });
    if(stuid.length == 0){
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }else{
      $.ajax({
        url: "<?php echo base_url(); ?>registration/studentPromotionPromoted/",
        method: "POST",
        data: ({
          stuid:stuid,
          nextGrade:nextGrade,
          academicyear:academicyear
        }),
        success: function(data) {
          iziToast.success({
            title: 'Student registration saved successfully',
            message: '',
            position: 'bottomCenter'
          });
        }
      })
    }
  });
  $(document).on('click', '#detainedStudent', function() { //done
    stuid=[];
    var academicyear=$('#academicyearRegistration').val();
    $("input[name='stuId[ ]']:checked").each(function(i){
      stuid[i]=$(this).val();
    });
    if(stuid.length == 0){
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }else{
      $.ajax({
        url: "<?php echo base_url(); ?>registration/studentPromotionDetained/",
        method: "POST",
        data: ({
          stuid:stuid,
          academicyear:academicyear
        }),
        success: function(data) {
          iziToast.success({
            title: 'Student Detained',
            message: 'successfully',
            position: 'topRight'
          });
        }
      })
    }
  });
  /*Promotion & Detained Query ends*/
  /*Clear Registration starts*/
  $(document).on('click', "input[name='unregister']", function() { //
    var stuid=$(this).attr('value');
    var academicyear=$(this).attr('class');
    swal({
      title: 'Are you sure you want to clear this student ' + academicyear + ' history ?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        if(!$(this).is(':checked')){
          $.ajax({
            url: "<?php echo base_url(); ?>registration/clearRegistration/",
            method: "POST",
            data: ({
              stuid:stuid,
              academicyear:academicyear
            }),
            beforeSend: function() {
              $('#checkstatus' + stuid).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="16" height="16" id="loa">' );
            },
            success: function(data) {
              $("#checkstatus" + stuid).fadeOut('slow');
              iziToast.success({
                title: 'Student registration cancelled successfully',
                message: '',
                position: 'bottomCenter'
              });
            }
          })
        }
      }
    });
  });
</script>
<script type="text/javascript">
  $('#fetch_prmotion').on('submit', function(event) {
    event.preventDefault();
    var academicyear=$('#academicyearRegistration').val();
    var gradesec_rg=$("#gradesec_rg").val();
    var branch=$("#branchRegistration").val();
    $.ajax({
      url: "<?php echo base_url(); ?>registration/fetch_grade_4registration/",
      method: "POST",
      data: ({
        gradesec_rg:gradesec_rg,
        branch:branch,
        academicyear:academicyear
      }),
      beforeSend: function() {
        $('.listPlacement').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(data) {
        $(".listPlacement").html(data);
      }
    })
  });
</script>
<script type="text/javascript">
    function selectAll(){
      var itemsall=document.getElementById('selectall');
      if(itemsall.checked==true){
        var items=document.getElementsByName('stuId[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=true;
        }
      }
        else{
        var items=document.getElementsByName('stuId[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=false;
        }
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
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branch_tr").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Fetch_transcript_grade/",
        data: "branch_tr=" + $("#branch_tr").val(),
        beforeSend: function() {
          $('#gradesec_tr').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradesec_tr").html(data);
        }
      });
    });
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