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
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#CustomPerformanceGroup" role="tab" aria-selected="true">Performance Group</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#CustomPerformanceActivities" role="tab" aria-selected="false">Performance Activities</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#EditTeacherPerformance" role="tab" aria-selected="false">Teacher's Performance</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab4" data-toggle="tab" href="#ViewTeacherPerformance" role="tab" aria-selected="false">Custom Report</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab5" data-toggle="tab" href="#ViewTeacherCustomReport" role="tab" aria-selected="false">Default Report</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="CustomPerformanceGroup" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="add_New_Group" value="" data-toggle="modal" data-target="#add-new-group"><span class="text-success">
                        <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add New Group</button>
                       </span>
                       </a>
                      </div>
                      <div class="col-lg-12 col-12">
                        <div class="dropdown-divider"></div>
                        <div class="fetchCustomGroup"></div>
                      </div>
                    </div> 
                  </div>
                  <div class="tab-pane fade show" id="CustomPerformanceActivities" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="add_New_Activity" value="" data-toggle="modal" data-target="#add-new-activity"><span class="text-success">
                        <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add New Activity</button>
                       </span>
                       </a>
                      </div>
                      <div class="col-lg-12 col-12">
                        <div class="dropdown-divider"></div>
                         <div class="fetchCustomActivity"></div>
                      </div>
                    </div> 
                  </div>
                  <div class="tab-pane fade show" id="EditTeacherPerformance" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="row">
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required"  name="activityYear" id="activityYear">
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
                          <select class="form-control selectric" required="required" name="activityBranch" id="activityBranch">
                            <option> --- Select Branch --- </option>
                            <?php foreach($branch as $branchs){ ?>
                            <option value="<?php echo $branchs->name;?>">
                              <?php echo $branchs->name;?>
                            </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="activity_staffs" id="activity_staffs">
                            <option> --- Select Staffs --- </option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="activity_week" id="activity_week">
                            <option> --- Select Week --- </option>
                            <?php foreach($week as $weeks){ ?>
                            <option value="<?php echo $weeks->week_name;?>">
                              <?php echo $weeks->week_name;?>
                            </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-12">
                        <button class="btn btn-primary btn-block" type="submit" id="fetchStaffToEditActivity" name="fetchStaffToEditActivity"> View Staff</button>
                      </div>
                    </div>
                    <div class="fetchTeacherActivity"></div>
                  </div>
                  <div class="tab-pane fade show" id="ViewTeacherPerformance" role="tabpanel" aria-labelledby="home-tab4">
                    <div class="row">
                      <div class="col-lg-6 col-6"> </div>
                      <div class="col-lg-6 col-6">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                          <i data-feather="printer"></i>
                        </button>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required"  name="activityViewYear" id="activityViewYear">
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
                          <select class="form-control selectric" required="required" name="activityViewBranch" id="activityViewBranch">
                            <option> --- Select Branch --- </option>
                            <?php foreach($branch as $branchs){ ?>
                            <option value="<?php echo $branchs->name;?>">
                              <?php echo $branchs->name;?>
                            </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="activityViewStaffs" id="activityViewStaffs">
                            <option> --- Select Staffs --- </option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="activityViewWeek" id="activityViewWeek">
                            <option> --- Select Week --- </option>
                            <?php foreach($week as $weeks){ ?>
                            <option value="<?php echo $weeks->week_name;?>">
                              <?php echo $weeks->week_name;?>
                            </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-12">
                        <button class="btn btn-primary btn-block" type="submit" id="fetchStaffToViewActivity" name="fetchStaffToViewActivity"> View Report</button>
                      </div>
                    </div>
                    <div class="viewTeacherActivity" id="viewTeacherActivity"></div>
                  </div>
                  <div class="tab-pane fade show" id="ViewTeacherCustomReport" role="tabpanel" aria-labelledby="home-tab5">
                    <div class="card">
                        <div class="row">
                          <div class="col-lg-6 col-6"> </div>
                          <div class="col-lg-6 col-6">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyCustom()">
                              <i data-feather="printer"></i>
                            </button>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-2 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required"  name="staff_activity_Year" id="staff_activity_Year">
                                <?php foreach($academicyear as $academicyears){ ?>
                                  <option value="<?php echo $academicyears->year_name;?>">
                                  <?php echo $academicyears->year_name;?>
                                  </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-2 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="staff_activity_Branch" id="staff_activity_Branch">
                                <option> --- Branch --- </option>
                                <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                  <?php echo $branchs->name;?>
                                </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-3 col-6">
                            <div class="form-group">
                              <select class="form-control staff_activity_Division" id="staff_activity_Division" name="staff_activity_Division">
                              <option>---Select Division---</option>
                              <?php foreach($user_division as $user_divisions){ ?>
                              <option value="<?php echo $user_divisions->dname; ?>"><?php echo $user_divisions->dname; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                          <div class="col-lg-3 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="staff_activity_Week" id="staff_activity_Week">
                                <option> --- Select Week --- </option>
                                <?php foreach($week as $weeks){ ?>
                                <option value="<?php echo $weeks->week_name;?>">
                                  <?php echo $weeks->week_name;?>
                                </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-2 col-12">
                            <button class="btn btn-primary btn-block" type="submit" id="fetch_staff_report" name="fetch_staff_report"> View Report</button>
                          </div>
                        </div>
                        <div class="viewTeacherReport" id="viewTeacherReport"></div>
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
  <div class="modal fade" id="add-new-activity" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add new performance activity</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="card-body StudentViewTextInfo">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-3 col-lg-3 col-6 form-group">
                   <select class="form-control selectperDivision" id="selectperDivision" name="selectperDivision">
                    <option>---Select Division---</option>
                    <?php foreach($user_division as $user_divisions){ ?>
                    <option value="<?php echo $user_divisions->dname; ?>"><?php echo $user_divisions->dname; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-2 col-lg-2 col-6 form-group">
                  <select class="form-control selectperGroup" id="selectperGroup" name="selectperGroup">
                    <option>---Select Group---</option>
                  </select>
                </div>
                <div class="col-md-7 col-lg-7 col-8 form-group">
                  <input type="text" name="customActivitiesName" id="customActivitiesName" class="form-control" placeholder="Custom performance activities here...">
                </div>
                <div class="col-md-4 col-lg-4 col-4 form-group">
                  <input type="number" name="customActivitiesPercent" id="customActivitiesPercent" class="form-control" placeholder="Weight...">
                </div>
                <div class="col-md-4 col-lg-4 col-6 form-group">
                   <select class="form-control activity_for" id="activity_for" name="activity_for">
                    <option>---Activity By---</option>
                    <?php foreach($usertype->result() as $usertypes){ ?>
                    <option value="<?php echo $usertypes->uname; ?>"><?php echo $usertypes->uname; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-4 col-lg-4 col-6 form-group">
                  <button class="btn btn-primary btn-block saveActivitiesText" id="saveActivitiesText">Save Activity</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add-new-group" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add new performance group</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="card-body StudentViewTextInfo">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-5 col-lg-5 col-7 form-group">
                  <input type="text" name="customGroupName" id="customGroupName" class="form-control" placeholder="Custom performance group...">
                </div>
                <div class="col-md-3 col-lg-3 col-5 form-group">
                   <select class="form-control customGroupDvision" id="customGroupDvision" name="customGroupDvision">
                    <option>---Select Division---</option>
                    <option>All Division</option>
                    <?php foreach($user_division as $user_divisions){ ?>
                    <option value="<?php echo $user_divisions->dname; ?>"><?php echo $user_divisions->dname; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-2 col-lg-2 col-6 form-group">
                  <input type="number" name="customGroupWeight" id="customGroupWeight" class="form-control" placeholder="Group Weight...">
                </div>
                <div class="col-md-2 col-lg-2 col-6 form-group">
                  <button class="btn btn-primary btn-block saveGroupText" id="saveGroupText">Save group</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="editPerformanceActivity" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit Performance Activity</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="fetchTeacherActivityToEdit"></div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="editPerformanceGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit Performance Group</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="fetchTeacherGroupToEdit"></div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {  
      $("#selectperDivision").bind("change", function() {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>teachersperformance/fetch_group_ondivision_change/",
          data: "select_group=" + $("#selectperDivision").val(),
          beforeSend: function() {
            $('#selectperGroup').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
          },
          success: function(data) {
            $("#selectperGroup").html(data);
          }
        });
      });
    });
    $(document).ready(function() {  
      $("#activityViewBranch").bind("change", function() {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>teachersperformance/fetch_staffs_onbranch_change/",
          data: "staffs_list=" + $("#activityViewBranch").val(),
          beforeSend: function() {
            $('#activityViewStaffs').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $("#activityViewStaffs").html(data);
          }
        });
      });
    });
    $(document).ready(function() {  
      $("#activityBranch").bind("change", function() {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>teachersperformance/fetch_staffs_onbranch_change/",
          data: "staffs_list=" + $("#activityBranch").val(),
          beforeSend: function() {
            $('#activity_staffs').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $("#activity_staffs").html(data);
          }
        });
      });
    });
    $(document).on('click', "input[name='onoff_their_result']", function() {
      var lockmark=$(this).attr("value");
      var per_week=$(this).attr("id");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>teachersperformance/saveLockMarkAuto/",
          method:"POST",
          data:({
            lockmark:lockmark,
            per_week:per_week
          }),
          success: function(){
            iziToast.success({
              title: 'Status enabled successfully.',
              message: '',
              position: 'topRight'
            });
          }
        });
      }else{
        var lockmark=$(this).attr("value");
      var per_week=$(this).attr("id");
        $.ajax({
          url:"<?php echo base_url() ?>teachersperformance/deleteLockMarkAuto/",
          method:"POST",
          data:({
             lockmark:lockmark,
             per_week:per_week
          }),
          success: function(){
            iziToast.success({
              title: 'Status disabled successfully.',
              message: '',
              position: 'topRight'
            });
          }
        });
      }
    });
  </script>
  <script type="text/javascript">
    loadCustomData();
      function loadCustomData()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>teachersperformance/fetchCustomGroup/",
          method:"POST",
          beforeSend: function() {
            $('.fetchCustomGroup').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
          },
          success:function(data){
            $('.fetchCustomGroup').html(data);
          }
        })
      }
    $(document).on('click', '.editPerformanceGroup', function() {
      var idName=$(this).attr("value");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>teachersperformance/edit_group_name/",
        data: ({
          idName: idName
        }),
        cache: false,
        beforeSend: function() {
          $('.fetchTeacherGroupToEdit').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data){
          $('.fetchTeacherGroupToEdit').html(data);
        }
      }); 
    });
    $(document).on('click', '#save_group_changes', function(event) {
      event.preventDefault();
      var groupNameNew=$('#groupNameNew').val();
      var groupWeightNew=$('#groupWeightNew').val();
      var groupIDNew=$('#groupIDNew').val();
      $.ajax({
        url:"<?php echo base_url(); ?>teachersperformance/update_group_name/",
        method:"POST",
        data: ({
          groupNameNew: groupNameNew,
          groupWeightNew:groupWeightNew,
          groupIDNew:groupIDNew
        }),
        beforeSend: function() {
          $('.save_group_info').html( 'Updating...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
        },
        cache:false,
        success:function(data){
          $(".save_group_info").html(data);
          $("#editPerformanceGroup").modal("hide");
          loadCustomData();
        }
      })
    });
    $(document).ready(function(){
      $('#fetch_staff_report').on('click', function(event) {
        event.preventDefault();
        var activityYear=$('#staff_activity_Year').val();
        var activityBranch=$('#staff_activity_Branch').val();
        var staff_activity_Week=$('#staff_activity_Week').val();
        var staff_activity_Division=$('#staff_activity_Division').val();
        if($('#staff_activity_Branch').val() =='' || $('#staff_activity_Division').val()=='---Select Division---')
        {
          swal({
            title: 'Oooops, Please select necessary fields.',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
        }else{
          $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>teachersperformance/fetch_Perform_Result/",
          data: ({
            activityYear: activityYear,
            activityBranch:activityBranch,
            staff_activity_Week:staff_activity_Week,
            staff_activity_Division:staff_activity_Division
          }),
          beforeSend: function() {
            $('.viewTeacherReport').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
          },
          cache: false,
          success: function(html){
            $('.viewTeacherReport').html(html);
          }
        });
      }
    });
  });
  </script>
  <script type="text/javascript">
    loadCustomGroup();
      function loadCustomGroup()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>teachersperformance/fetchCustomActivity/",
          method:"POST",
          beforeSend: function() {
            $('.fetchCustomActivity').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
          },
          success:function(data){
            $('.fetchCustomActivity').html(data);
          }
        })
      }
    $(document).on('click', '.editPerformanceActivity', function() {
      var idName=$(this).attr("value");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>teachersperformance/edit_activity_name/",
        data: ({
          idName: idName
        }),
        cache: false,
        beforeSend: function() {
          $('.fetchTeacherActivityToEdit').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data){
          $('.fetchTeacherActivityToEdit').html(data);
        }
      }); 
    });
    $(document).on('click', '#save_activity_changes', function(event) {
      event.preventDefault();
      var activityNameNew=$('#activityNameNew').val();
      var activityWeightNew=$('#activityWeightNew').val();
      var activityIDNew=$('#activityIDNew').val();
      $.ajax({
        url:"<?php echo base_url(); ?>teachersperformance/update_activity_name/",
        method:"POST",
        data: ({
          activityNameNew: activityNameNew,
          activityWeightNew:activityWeightNew,
          activityIDNew:activityIDNew
        }),
        beforeSend: function() {
          $('.save_activity_info').html( 'Updating...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
        },
        cache:false,
        success:function(data){
          $(".save_activity_info").html(data);
          $("#editPerformanceActivity").modal("hide");
          loadCustomGroup();
        }
      })
    });
  function codespeedy(){
    var print_div = document.getElementById("viewTeacherActivity");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  function codespeedyCustom(){
    var print_div = document.getElementById("viewTeacherReport");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
</script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#fetchStaffToViewActivity').on('click', function(event) {
        event.preventDefault();
        var activityYear=$('#activityViewYear').val();
        var activityBranch=$('#activityViewBranch').val();
        var activityViewWeek=$('#activityViewWeek').val();
        var activityViewStaffs=$('#activityViewStaffs').val();
        if($('#activityBranch').val() =='')
        {
          alert("Oooops, Please select necessary fields.");
        }else{
          $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>teachersperformance/fetchStaffPerformResult/",
          data: ({
            activityYear: activityYear,
            activityBranch:activityBranch,
            activityViewWeek:activityViewWeek,
            activityViewStaffs:activityViewStaffs
          }),
          beforeSend: function() {
            $('.viewTeacherActivity').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
          },
          cache: false,
          success: function(html){
            $('.viewTeacherActivity').html(html);
          }
        });
      }
    });
  });
  </script>
  <script type="text/javascript">
    $(document).on('change', '.insertTeacherPerformance', function() {
      var value=$(this).find('option:selected').attr('value');
      var teid=$(this).find('option:selected').attr('id');
      var acname=$(this).find('option:selected').attr('class');
      var GroupName=$(this).find('option:selected').attr('name');
      var activity_week=$(this).find('option:selected').attr('title');
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>teachersperformance/updateTeacherPerformance/",
        data: ({
          value:value,
          teid:teid,
          acname:acname,
          activity_week:activity_week,
          GroupName:GroupName
        }),
        success: function(data) {
          $('#TotalGivenResults'+GroupName + teid).html(data);
          iziToast.success({
            title: 'Teacher Performance Updated',
            message: '',
            position: 'topRight'
          });
        }
      });
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#fetchStaffToEditActivity').on('click', function(event) {
        event.preventDefault();
        var activityYear=$('#activityYear').val();
        var activityBranch=$('#activityBranch').val();
        var activity_week=$('#activity_week').val();
        var activity_staffs=$('#activity_staffs').val();
        if($('#activityBranch').val() =='' || $('#activity_week').val()=='--- Select Week ---')
        {
          swal({
            title: 'Oooops, Please select necessary fields.',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
        }else{
          $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>teachersperformance/fetchStaffToPerformActivity/",
          data: ({
            activityYear: activityYear,
            activityBranch:activityBranch,
            activity_week:activity_week,
            activity_staffs:activity_staffs
          }),
          beforeSend: function() {
            $('.fetchTeacherActivity').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
          },
          cache: false,
          success: function(html){
            $('.fetchTeacherActivity').html(html);
          }
        });
      }
    });
  });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      loadCustomGroup();
      function loadCustomGroup()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>teachersperformance/fetchCustomActivity/",
          method:"POST",
          beforeSend: function() {
            $('.fetchCustomActivity').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
          },
          success:function(data){
            $('.fetchCustomActivity').html(data);
          }
        })
      }
      $('#saveActivitiesText').on('click', function(event) {
        event.preventDefault();
        var selectperGroup=$('#selectperGroup').val();
        var customActivitiesName=$('#customActivitiesName').val();
        var customActivitiesPercent=$('#customActivitiesPercent').val();
        var activity_for=$('#activity_for').val();
        var selectperDivision=$('#selectperDivision').val();
        if($('#customActivitiesName').val() =='' || $('#selectperDivision').val()=='---Select Division---')
        {
          swal({
            title: 'Oooops, Please select necessary fields.',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
        }else{
          $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>teachersperformance/postCustomActivity/",
          data: ({
            selectperGroup: selectperGroup,
            customActivitiesName:customActivitiesName,
            customActivitiesPercent:customActivitiesPercent,
            activity_for:activity_for,
            selectperDivision:selectperDivision
          }),
          cache: false,
          success: function(html){
            $('#customActivitiesName').val('');
            loadCustomGroup();
          }
        });
      }
    });
    $(document).on('click', '.deletePerformanceActivity', function() {
      var textId = $(this).attr("id");
      swal({
        title: 'Are you sure you want to delete this text ?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>teachersperformance/deleteCustomActivity/",
            data: ({
              textId: textId
            }),
            cache: false,
            success: function(html) {
              loadCustomGroup();
            }
          });
        }
      });
    });
  });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      loadCustomData();
      function loadCustomData()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>teachersperformance/fetchCustomGroup/",
          method:"POST",
          beforeSend: function() {
            $('.fetchCustomGroup').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
          },
          success:function(data){
            $('.fetchCustomGroup').html(data);
          }
        })
      }
      $('#saveGroupText').on('click', function(event) {
        event.preventDefault();
        var customGroupName=$('#customGroupName').val();
        var customGroupWeight=$('#customGroupWeight').val();
        var customGroupDvision=$('#customGroupDvision').val();
        if($('#customGroupName').val() =='' || $('#customGroupDvision').val()=='---Select Division---')
        {
          swal({
            title: 'Oooops, Please select necessary fields.',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
        }else{
          $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>teachersperformance/postCustomGroup/",
          data: ({
            customGroupName: customGroupName,
            customGroupWeight:customGroupWeight,
            customGroupDvision:customGroupDvision
          }),
          cache: false,
          success: function(html){
            $('#customGroupName').val('');
            $('#customGroupWeight').val('');
            loadCustomData();
          }
        });
      }
    });
    $(document).on('click', '.deletePerformanceGroup', function() {
      var textId = $(this).attr("id");
      swal({
        title: 'Are you sure you want to delete this Group ?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>teachersperformance/deleteCustomGroup/",
          data: ({
            textId: textId
          }),
          cache: false,
          success: function(html) {
            loadCustomData();
          }
        });
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
</body>

</html>