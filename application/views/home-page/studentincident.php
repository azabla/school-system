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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
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
            <div class="row">
              <div class="col-lg-12 col-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <button type="submit" name="addnewConsequences" id="addnewConsequences" class="btn btn-primary pull-right" data-toggle="modal" data-target="#newConsequences" > <i class="fas fa-plus"></i> Consequences 
                    </button> 
                    <button type="submit" name="addnewIncidentType" id="addnewIncidentType" class="btn btn-info pull-right" data-toggle="modal" data-target="#newIncidentType" > <i class="fas fa-plus"></i> Incident Type
                    </button>  
                    <button type="submit" name="addnewForm" id="addnewFormCategory" class="btn btn-secondary pull-right" data-toggle="modal" data-target="#newIncidentForm" > <i class="fas fa-plus"></i> Incident Form
                    </button>
                  </div>
                </div>
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active gs-tab-status-2" id="home-tab1" data-toggle="tab" href="#addNewIncident" role="tab" aria-selected="true">Add Report</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link gs-tab-status-2" id="home-tab2" data-toggle="tab" href="#fetchIncidentReport" role="tab" aria-selected="false">Incident Report <span class="badge badge-danger count-new-incident-report"></span></a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show gs-tab-status active" id="addNewIncident" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row">
                          <div class="col-lg-3 col-6">
                             <div class="form-group">
                               <select class="form-control selectric" required="required" name="academicyear" id="grands_academicyear">
                                <option>--Year--</option>
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
                               <select class="form-control" required="required" name="branch" id="grands_branchit">
                               <option>--- Branch ---</option>
                               
                               </select>
                              </div>
                             </div>
                             <div class="col-lg-3 col-6">
                              <div class="form-group">
                               <select class="form-control grands_grade" name="grands_grade" id="grands_grade">
                               <option>--- Grade ---</option>
                              </select>
                              </div>
                             </div>
                           <div class="col-lg-3 col-6">
                            <button class="btn btn-primary btn-block" 
                            type="submit" id="fetchStudent" name="viewmark">View Student</button>
                          </div>
                        </div>
                        <hr>
                        <div class="listStudentShow" id="student_view"></div>
                      </div>
                      <div class="tab-pane fade gs-tab-status show" id="fetchIncidentReport" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="table-responsive">
                          <table class="display dataTable" id='empTableGS' style="width:100%;">
                            <thead>
                              <tr>
                                <th>Student ID</th> 
                                <th>Student Name</th>                           
                                <th>Grade</th>                         
                                <th>Incident Type</th>
                                <th>Incident Date</th>
                              </tr>
                            </thead>
                          </table> 
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
  <div class="modal fade" id="newIncidentType" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>New Incident Type</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="incident_type_page"></div>
          
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="newIncidentForm" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Add Incident Form/Category</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <form method="POST" id="saveNewFormIncidentCategory" class="saveNewFormIncidentCategory" name="saveNewFormIncidentCategory">
            <div class="form-group">
              <div class="search-element">
                <div class="row">
                  <div class="form-group col-lg-12 col-12">
                    <label>Incident form/category( <span class="text-time"> Example. White Incident Form,Red Incident Form...</span>)<span class="text-danger"> Please create the incident type/form from lower to higher according to their level. </span></label>
                    <input id="incidentCategory" type="text" class="form-control" required="required" name="incidentCategory" placeholder="Incident form here...">
                  </div>
                  <div class="form-group col-lg-12 col-12">
                    <button class="btn btn-primary pull-right" name="save_incident_Category" id="save_incident_Category"> Save Form
                    </button>
                  </div>
                </div>
                <hr>
                <div class="msg_Incident_category" id="msg_Incident_category"></div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="submitNewIncidentReport" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>New Incident Report</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="submitNewIncidentReport"></div>
          
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="viewSingleidentReport" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Incident Report</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="viewSingleidentReport"></div>
          
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="newConsequences" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Add Consequences</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="consequences_type_page"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/gs_all.js"></script>
</body>
 <script type='text/javascript'>
    var baseURL= "<?php echo base_url();?>";
  </script>
<script type="text/javascript">
  $(document).on('click', '.delete_this_student_incidentreport', function() {
    swal({
      title: 'Are you sure?',
      text: 'Once deleted,you can not recover this report!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        var reportID = $(this).attr("value");
        var reportUsername = $(this).attr("name");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>studentincident/deleteSingleidentReport/",
          data: ({
            reportID:reportID,
            reportUsername:reportUsername
          }),
          cache: false,
          success: function(html) {
            $('#empTableGS').DataTable().ajax.reload();
          }
        });
      }
    });
  });
  $(document).on('submit', '.submit_final_decision', function(e) {
    e.preventDefault();
    if ($('#add_final_decision').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>studentincident/save_final_decision/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#updateFinalDesicion').html( 'Saving...');
          $('#updateFinalDesicion').attr( 'disabled','disabled');
        },
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Decision updated successfully.',
              message: '',
              position: 'topRight'
            });
            $('#viewSingleidentReport').modal('hide');
          }else if(html=='2'){
            iziToast.success({
              title: 'Decision saved successfully.',
              message: '',
              position: 'topRight'
            });
            $('#viewSingleidentReport').modal('hide');
          }else if(html=='3'){
            iziToast.error({
              title: 'Oooops unable to insert decision.',
              message: '',
              position: 'topRight'
            });
          }else{
            iziToast.error({
              title: 'unable to update decision.',
              message: '',
              position: 'topRight'
            });
          }
          $('#updateFinalDesicion').html('Submit Changes');
          $('#updateFinalDesicion').removeAttr( 'disabled');
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  function load_incidentconsequence_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>studentincident/fetch_consequence_type/",
      method:"POST",
      beforeSend: function() {
        $('.consequences_type_page').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.consequences_type_page').html(data);
      }
    })
  }
  $(document).on('click', '#addnewConsequences', function(e) {
    e.preventDefault();
    $.ajax({
      url:"<?php echo base_url(); ?>studentincident/fetch_consequence_type/",
      method:"POST",
      beforeSend: function() {
        $('.consequences_type_page').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.consequences_type_page').html(data);
      }
    })
  });
  $(document).on('submit', '#saveNewincident_consequence', function(e) {
    e.preventDefault();
    if ($('#consequenceName').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>studentincident/save_new_consequence/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#save_consequence_type').html( 'Saving...');
          $('#save_consequence_type').attr( 'disabled','disabled');
        },
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Saved successfully',
              message: '',
              position: 'topRight'
            });
            $('#consequenceName').val('');
            load_incidentconsequence_data();
          }else{
            iziToast.error({
              title: 'Consequence name found.',
              message: '',
              position: 'topRight'
            });
          }
          $('#save_consequence_type').html( 'Save Consequence');
          $('#save_consequence_type').removeAttr( 'disabled');
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '#remove_consequence_name', function(e) {
    e.preventDefault();
    var userid=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>studentincident/removeBook_this_consequence_name/",
      data: ({
        userid: userid
      }),
      beforeSend: function() {
        $('.remove_consequence_name' + userid).html( '<span class="text-info">Removing...</span>');
        $('#remove_consequence_name').attr( 'disabled','disabled');
        
      },
      success: function(html){
        if(html=='1'){
          iziToast.success({
            title: 'Removed successfully',
            message: '',
            position: 'topRight'
          });
          load_incidentconsequence_data();
        }else{
          iziToast.error({
            title: 'Please try later',
            message: '',
            position: 'topRight'
          });
        }
        $('.remove_consequence_name' + userid).html( 'Remove');
        $('#remove_consequence_name').removeAttr( 'disabled');
      }
    });
  });
  $(document).on('click', '.view_this_student_incidentreport', function() {
    var reportID = $(this).attr("value");
    var reportUsername = $(this).attr("name");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>studentincident/viewSingleidentReport/",
      data: ({
        reportID:reportID,
        reportUsername:reportUsername
      }),
      cache: false,
      beforeSend: function() {
        $('.viewSingleidentReport').html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="24" height="24" id="loa">');
      },
      success: function(html) {
        $(".viewSingleidentReport").html(html);
      }
    });
  });
  $(document).ready(function(){
    $('#empTableGS').DataTable({
      'processing': true,
      'serverSide': true,
      "dataType": "json",
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>studentincident/fetch_student_incident_report/'
      },
      'columns': [
        { data: 'username' },
        { data: 'fname' },
        { data: 'gradesec' },
        { data: 'incident_type' },
        { data: 'date_report' },
      ]
    });
  });
  function load_category_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>studentincident/fetch_incident_form/",
      method:"POST",
      beforeSend: function() {
        $('.msg_Incident_category').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.msg_Incident_category').html(data);
      }
    })
  }
  function load_incidenttype_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>studentincident/fetch_incident_type/",
      method:"POST",
      beforeSend: function() {
        $('.incident_type_page').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.incident_type_page').html(data);
      }
    })
  }
  $(document).on('click', '#addnewFormCategory', function(e) {
    e.preventDefault();
    load_category_data();
  });
  $(document).on('click', '#addnewIncidentType', function(e) {
    e.preventDefault();
    $.ajax({
      url:"<?php echo base_url(); ?>studentincident/fetch_incident_type/",
      method:"POST",
      beforeSend: function() {
        $('.incident_type_page').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.incident_type_page').html(data);
      }
    })
  });
  $(document).on('click', '#remove_incident_type', function(e) {
    e.preventDefault();
    var userid=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>studentincident/removeBook_this_incident_type/",
      data: ({
        userid: userid
      }),
      beforeSend: function() {
        $('.remove_incident_type' + userid).html( '<span class="text-info">Removing...</span>');
        $('#remove_incident_type').attr( 'disabled','disabled');
        
      },
      success: function(html){
        if(html=='1'){
          iziToast.success({
            title: 'Removed successfully',
            message: '',
            position: 'topRight'
          });
          load_incidenttype_data();
        }else{
          iziToast.error({
            title: 'Please try later',
            message: '',
            position: 'topRight'
          });
        }
        $('.remove_incident_type' + userid).html( 'Remove');
        $('#remove_incident_type').removeAttr( 'disabled');
      }
    });
  });
  $(document).on('click', '#remove_incident_category', function(e) {
    e.preventDefault();
    var userid=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>studentincident/removeBook_this_incident_category/",
      data: ({
        userid: userid
      }),
      beforeSend: function() {
        $('.remove_incident_category' + userid).html( '<span class="text-info">Removing...</span>');
        $('#remove_incident_category').attr( 'disabled','disabled');
        
      },
      success: function(html){
        if(html=='1'){
          iziToast.success({
            title: 'Removed successfully',
            message: '',
            position: 'topRight'
          });
          load_category_data();
        }else{
          iziToast.error({
            title: 'Please try later',
            message: '',
            position: 'topRight'
          });
        }
        $('.remove_incident_category' + userid).html( 'Remove');
        $('#remove_incident_category').removeAttr( 'disabled');
      }
    });
  });
  $(document).on('submit', '#saveNewFormIncidentCategory', function(e) {
    e.preventDefault();
    if ($('#incidentCategory').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>studentincident/save_new_incident_form/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#save_incident_Category').html( 'Saving...');
          $('#save_incident_Category').attr( 'disabled','disabled');
        },
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Saved successfully',
              message: '',
              position: 'topRight'
            });
            $('#incidentCategory').val('');
            load_category_data();
          }else{
            iziToast.error({
              title: 'Incident form found.',
              message: '',
              position: 'topRight'
            });
          }
          $('#save_incident_Category').html( 'Save Form');
          $('#save_incident_Category').removeAttr( 'disabled');
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('submit', '#saveNewFormIncidentType', function(e) {
    e.preventDefault();
    if ($('#incidentName').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>studentincident/save_new_incident/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#save_incident_type').html( 'Saving...');
          $('#save_incident_type').attr( 'disabled','disabled');
        },
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Saved successfully',
              message: '',
              position: 'topRight'
            });
            $('#incidentName').val('');
            load_incidenttype_data();
          }else{
            iziToast.error({
              title: 'Incident type found.',
              message: '',
              position: 'topRight'
            });
          }
          $('#save_incident_type').html( 'Save Incident Type');
          $('#save_incident_type').removeAttr( 'disabled');
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
</script>
 <script type="text/javascript">
  function codespeedyAttendancePrint(){
    var print_div = document.getElementById("prinThiStudentAttendance");
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

<!-- Grade change script starts-->
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_academicyear").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>studentincident/filterGradesecfromBranch/",
        data: "academicyear=" + $("#grands_academicyear").val(),
        beforeSend: function() {
          $('#grands_branchit').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $("#grands_branchit").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_branchit").bind("change", function() {
      var branchit=$('#grands_branchit').val();
      var grands_academicyear=$('#grands_academicyear').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>studentincident/filterOnlyGradeFromBranch/",
        data: ({
            branchit: branchit,
            grands_academicyear:grands_academicyear
        }),
        beforeSend: function() {
          $('.grands_grade').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $(".grands_grade").html(data);
        }
      });
    });
  });
</script>
<!-- Grade change script ends -->
<script type="text/javascript">
  $(document).on('click', '#fetchStudent', function() {
    event.preventDefault();
    var gs_branches=$('#grands_branchit').val();
    var gs_gradesec=$('.grands_gradesec').val();
    var onlyGrade=$('.grands_grade').val();
    var grands_academicyear=$('#grands_academicyear').val();
    if ($('.grands_gradesec').val() != '' || $('.grands_grade').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>studentincident/fecth_this_tudent/",
        method: "POST",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          onlyGrade:onlyGrade,
          grands_academicyear:grands_academicyear
        }),
        beforeSend: function() {
          $('.listStudentShow').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $(".listStudentShow").html(data);
        }
      })
    }else {
      swal('All fields are required.', {
        icon: 'error',
      });
    }
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
<script type="text/javascript">
  $(document).on('click', '.editstudent_incident', function() {
    var editedId = $(this).attr("value");
    var newAcademicYear=$(this).attr("name");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>studentincident/reportIncident_student/",
      data: ({
        editedId: editedId,
        newAcademicYear:newAcademicYear
      }),
      cache: false,
      beforeSend: function() {
        $('.submitNewIncidentReport').html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="24" height="24" id="loa">');
      },
      success: function(html) {
        $(".submitNewIncidentReport").html(html);
      }
    });
  });
</script>
<script type="text/javascript">
  function codespeedyStudentView(){
    var print_div = document.getElementById("StudentViewPrintHere");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  function codespeedyStudentLeaving(){
    var print_div = document.getElementById("PrintStudentRequestPaper");
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
  $(document).on('click', '#viewstudent_incident', function(event) {
    event.preventDefault();
    var username=$(this).attr('value');
    $.ajax({
      url: "<?php echo base_url(); ?>studentincident/previous_incident_report/",
      method: "POST",
      data: ({
        username: username
      }),
      beforeSend: function() {
        $('.listStudentShow').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(data) {
        $(".listStudentShow").html(data);
      }
    })
  });
  $(document).ready(function() { 
    $(document).on('change', '#incidentTypeCategoryChoose', function() { 
      var incidentCategory=$("#incidentTypeCategoryChoose").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>studentincident/fetch_this_incidentform_type/",
        data: ({
          incidentCategory:incidentCategory
        }),
        beforeSend: function() {
          $('.page_for_incident_type').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(data) {
          $(".page_for_incident_type").html(data);
        }
      });
    });
    $(document).on('change', '#incidentTypeCategoryChoose', function() { 
      var incidentCategory=$("#incidentTypeCategoryChoose").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>studentincident/fetch_this_incidentform_type_level/",
        data: ({
          incidentCategory:incidentCategory
        }),
        beforeSend: function() {
          $('.page_for_incident_type_level').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(data) {
          $(".page_for_incident_type_level").html(data);
        }
      });
    });
  });
  $(document).on('submit', '.save_new_incident', function(event) {
    event.preventDefault();
    incident_type=[];
    $("input[name='setAsIncident_Info']:checked").each(function(i){
      incident_type[i]=$(this).val();
    });
    var incident_teacher=$('#incident_teacher').val();
    var incident_student=$('#incident_student').val();
    var incident_date=$('#incident_date').val();
    var incidentTypeCategoryChoose=$('#incidentTypeCategoryChoose').val();
    var admin_action=$('#admin_action').val();
    var date_suspension_inschool=$('#date_suspension_inschool').val();
    var reentry_date_inschool=$('#reentry_date_inschool').val();
    var date_suspension_outschool=$('#date_suspension_outschool').val();
    var reentry_date_outschool=$('#reentry_date_outschool').val();
    var incident_location=$('#incident_location').val();
    var incident_description=$('#incident_description').val();
    var is_offense=$('#is_offense').val();
    var previous_conse=$('#previous_conse').val();
     $('input').attr('required', true);
    if(incident_type.length!=0) {
      swal({
        title: 'Are you sure?',
        text: 'Once send,you can not edit or delete this report!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            url: "<?php echo base_url(); ?>studentincident/save_incident/",
            method: "POST",
            data: ({
              incident_teacher:incident_teacher,
              incident_student:incident_student,
              incident_date:incident_date,
              incidentTypeCategoryChoose:incidentTypeCategoryChoose,
              admin_action:admin_action,
              date_suspension_inschool:date_suspension_inschool,
              reentry_date_inschool:reentry_date_inschool,
              date_suspension_outschool:date_suspension_outschool,
              reentry_date_outschool:reentry_date_outschool,
              incident_description:incident_description,
              incident_location:incident_location,
              is_offense:is_offense,
              incident_type:incident_type,
              previous_conse:previous_conse
            }),
            beforeSend: function() {
              $('#save_incident').html( 'Saving...');
              $('#save_incident').attr( 'disabled','disabled');
            },
            success: function(data) {
              if(data=='1'){
                iziToast.success({
                  title: 'Incident recorded successfully',
                  message: '',
                  position: 'topRight'
                });
                $('.save_new_incident')[0].reset();
                $('#submitNewIncidentReport').modal('hide');
                $('#empTableGS').DataTable().ajax.reload();
              }else{
                iziToast.error({
                  title: 'Oooops Please try again.',
                  message: '',
                  position: 'topRight'
                });
              }
              $('#save_incident').html( 'Submit Incident');
              $('#save_incident').removeAttr( 'disabled');
            }
          })
        }
      });
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
</script>
</html>