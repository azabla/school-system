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
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
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
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#staffRequest" role="tab" aria-selected="true">New Leaving Request</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#approvedStaffRequest" role="tab" aria-selected="true">Approved Request</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab5" data-toggle="tab" href="#studentLeavingReport" role="tab" aria-selected="true">Staff Leaving Report</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="staffRequest" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="staffRequestReport" id="staffRequestReport"> </div>
                  </div>
                  <div class="tab-pane fade show" id="approvedStaffRequest" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="table-responsive">
                      <table class="display dataTable" id='empTableGS' style="width:100%;">
                        <thead>
                          <tr>
                            <th>Full Name</th>                           
                            <th>Leave Type</th>                         
                            <th>Requested Date</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                      </table> 
                    </div>
                    <div class="approved_StaffRequest" id="approved_StaffRequest"> </div>
                  </div>
                  
                  <div class="tab-pane fade show" id="studentLeavingReport" role="tabpanel" aria-labelledby="home-tab5">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyLeaving()">
                        <span class="text-black"> <i data-feather="printer"></i> </span>
                        </button>
                        <button type="submit" id="dataExportExcelSLeaving" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="comment_form_leaving">
                      <div class="row">
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required"
                              name="grands_academicyear_leaving" id="grands_academicyear_leaving">
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
                            <select class="form-control" required="required" name="branch_leaving"
                             id="branch_leaving">
                              <option>--- Branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-12">
                          <button class="btn btn-primary btn-block" type="submit" name="viewmark_leaving">View</button>
                        </div>
                      </div>
                    </form>
                    <div class="SummaryRecord_leaving" id="SummaryRecord_leaving"> </div>
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
  <div class="modal fade" id="print_approved_staff_request" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="addSubject_new_exam">Request Report </h4> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <h3 class="badge badge-danger pull-right" id="count_no_question"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-header">
          <div class="modal-bodyd" id="fetch_approved_staff_request"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  $(document).on('click', "#printThisApprovedRequest", function() {
    var request_id=$(this).attr('value');
    $.ajax({
      url:"<?php echo base_url() ?>studentrequest/fetch_approved_staffrequest_form/",
      method:"POST",
      data:({
        request_id:request_id
      }),
      cache: false,
      beforeSend:function(){
        $('#fetch_approved_staff_request').html( 'Loading...' );
      },
      success: function(data){
        $("#fetch_approved_staff_request").html(data); 
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
        'url':'<?=base_url()?>studentrequest/fetch_approved_request/'
      },
      'columns': [
        { data: 'fname' },
        { data: 'requestype' },
        { data: 'requestdate' },
        { data: 'requestresponse' },
      ]
    });
  });
  $('#comment_form_leaving').on('submit', function(event) {
    event.preventDefault();
    var grands_academicyear=$('#grands_academicyear_leaving').val();
    var gs_branches=$('#branch_leaving').val();
    if ($('#branch_leaving').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>staffrequest/fetch_staff_leaving_report/",
        method: "POST",
        data: ({
          gs_branches: gs_branches,
          grands_academicyear:grands_academicyear
        }),
        beforeSend: function() {
          $('.SummaryRecord_leaving').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".SummaryRecord_leaving").html(data);
        }
      }) 
    }else {
      alert("All fields are required");
    }
  });
</script>
<script type="text/javascript">
  fetch_requested_form();
  function fetch_requested_form()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>studentrequest/fetch_new_student_request/",
      method:"POST",
      beforeSend: function() {
        $('.staffRequestReport').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.staffRequestReport').html(data);
      }
    })
  } 
  /*fetch_approved_staffrequest_form();
  function fetch_approved_staffrequest_form()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>studentrequest/fetch_approved_staffrequest_form/",
      method:"POST",
      beforeSend: function() {
        $('.approved_StaffRequest').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.approved_StaffRequest').html(data);
      }
    })
  }*/
  $(document).on('click', '#respond_student_request', function(event) {
    event.preventDefault();
    var id_request=$(this).attr("name");
    var req_response=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>studentrequest/submit_response/",
      data: ({
        id_request: id_request,
        req_response:req_response
      }),
      cache: false,
      success: function(data){
        iziToast.success({
          title: data,
          message: '',
          position: 'topRight'
        });
        fetch_requested_form();
        $('#empTableGS').DataTable().ajax.reload();
      }
    }); 
  });
  $("#dataExportExcelSLeaving").click(function(e) {
  let file = new Blob([$('.SummaryRecord_leaving').html()], {type:"application/vnd.ms-excel"});
  let url = URL.createObjectURL(file);
  let a = $("<a />", {
    href: url,
    download: "Staff Leaving Report.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
  });
  function codespeedyLeaving(){
    var print_div = document.getElementById("SummaryRecord_leaving");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  function codespeedyRequestReport(){
    var print_div = document.getElementById("codespeedyRequestReport");
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
      $("body").removeClass("dark");
      $("body").removeClass("dark-sidebar");
      $("body").removeClass("theme-black");
      $("body").addClass("light");
      $("body").addClass("light-sidebar");
      $("body").addClass("theme-white");
    } else {
      $("body").removeClass("light");
      $("body").removeClass("light-sidebar");
      $("body").removeClass("theme-white");
      $("body").addClass("dark");
      $("body").addClass("dark-sidebar");
      $("body").addClass("theme-black");
    }
  });
</script>
<script type="text/javascript" language="javascript"> 
  var bgcolor_now=document.getElementById("bgcolor_now").value;
  if (bgcolor_now == "1") {
    $("body").removeClass("dark");
    $("body").removeClass("dark-sidebar");
    $("body").removeClass("theme-black");
    $("body").addClass("light");
    $("body").addClass("light-sidebar");
    $("body").addClass("theme-white");
  }else {
    $("body").removeClass("light");
    $("body").removeClass("light-sidebar");
    $("body").removeClass("theme-white");
    $("body").addClass("dark");
    $("body").addClass("dark-sidebar");
    $("body").addClass("theme-black"); 
  } 
</script>  
</html>