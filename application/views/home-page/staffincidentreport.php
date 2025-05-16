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
                        <div class="table-responsive staffsList"> 
                          <table class="display dataTable" id='empTable' style="width:100%;">
                            <thead>
                             <tr>
                               <th>Full Name</th>
                               <th>Usertype</th>
                               <th>Mobile</th>
                              </tr>
                            </thead>
                          </table>  
                        </div>
                      </div>
                      <div class="tab-pane fade gs-tab-status show" id="fetchIncidentReport" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="table-responsive">
                          <table class="display dataTable" id='empTableGS' style="width:100%;">
                            <thead>
                              <tr>
                                <th>Staff Name</th>                         
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
  <div class="modal fade" id="report_thisstaff_incident" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>New Incident Report</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="report_thisstaff_incident"></div>
          
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="viewSinglestaffidentReport" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Incident Report</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="viewSinglestaffidentReport"></div>
          
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
  <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/gs_all.js"></script>
</body>
 <script type='text/javascript'>
    var baseURL= "<?php echo base_url();?>";
  </script>
  <script type="text/javascript">
    $(document).on('click', '.view_this_staff_incidentreport', function() {
      var reportID = $(this).attr("value");
      var reportUsername = $(this).attr("name");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>staffincidentreport/viewSinglestaffidentReport/",
        data: ({
          reportID:reportID,
          reportUsername:reportUsername
        }),
        cache: false,
        beforeSend: function() {
          $('.viewSinglestaffidentReport').html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success: function(html) {
          $(".viewSinglestaffidentReport").html(html);
        }
      });
    });
    $(document).on('click', '#report_staff_incident', function() {
      var editedId = $(this).attr("value");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>staffincidentreport/reportIncident_staff/",
        data: ({
          editedId: editedId
        }),
        cache: false,
        beforeSend: function() {
          $('.report_thisstaff_incident').html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success: function(html) {
          $(".report_thisstaff_incident").html(html);
        }
      });
    });
    $(document).on('change', '#incidentTypeCategoryChooseStaff', function() { 
      var incidentCategory=$("#incidentTypeCategoryChooseStaff").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>staffincidentreport/fetch_this_incidentform_type/",
        data: ({
          incidentCategory:incidentCategory
        }),
        beforeSend: function() {
          $('.page_for_incident_type_staff').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(data) {
          $(".page_for_incident_type_staff").html(data);
        }
      });
    });
  </script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#empTable').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>staffincidentreport/fetch_user_forreport/'
      },
      'columns': [
         { data: 'fname' },
         { data: 'usertype' },
         { data: 'mobile' },
      ]
    });
  });
  $(document).on('click', '.delete_this_staff_incidentreport', function() {
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
          url: "<?php echo base_url(); ?>staffincidentreport/deleteSingleidentReport/",
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
  $(document).ready(function(){
    $('#empTableGS').DataTable({
      'processing': true,
      'serverSide': true,
      "dataType": "json",
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>staffincidentreport/fetch_staff_incident_report/'
      },
      'columns': [
        { data: 'fname' },
        { data: 'incident_type' },
        { data: 'date_report' },
      ]
    });
  });
</script>
<!-- Grade change script ends -->
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
  $(document).on('submit', '.save_new_staff_incident', function(event) {
    event.preventDefault();
    incident_type=[];
    $("input[name='setAsIncident_Info']:checked").each(function(i){
      incident_type[i]=$(this).val();
    });
    var incident_staff=$('#incident_staff').val();
    var incident_date=$('#incident_date_staff').val();
    var incidentTypeCategoryChoose=$('#incidentTypeCategoryChooseStaff').val();
    var admin_action=$('#administrator_action_staff').val();
    var incident_location=$('#incident_location_staff').val();
    var incident_description=$('#incident_description_staff').val();
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
            url: "<?php echo base_url(); ?>staffincidentreport/save_staff_incident/",
            method: "POST",
            data: ({
              incident_staff:incident_staff,
              incident_date:incident_date,
              incidentTypeCategoryChoose:incidentTypeCategoryChoose,
              admin_action:admin_action,
              incident_description:incident_description,
              incident_location:incident_location,
              incident_type:incident_type
            }),
            beforeSend: function() {
              $('#save_staff_incident').html( 'Saving...');
              $('#save_staff_incident').attr( 'disabled','disabled');
            },
            success: function(data) {
              if(data=='1'){
                iziToast.success({
                  title: 'Incident recorded successfully',
                  message: '',
                  position: 'topRight'
                });
                $('.save_new_staff_incident')[0].reset();
                $('#report_thisstaff_incident').modal('hide');
                $('#empTableGS').DataTable().ajax.reload();
              }else{
                iziToast.error({
                  title: 'Oooops Please try again.',
                  message: '',
                  position: 'topRight'
                });
              }
              $('#save_staff_incident').html( 'Submit Incident');
              $('#save_staff_incident').removeAttr( 'disabled');
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