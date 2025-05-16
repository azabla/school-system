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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/daterangepicker.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
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
              <div class="col-lg-12">
                <div class="card">
                <div class="card-body StudentViewTextInfo">
                  <ul class="nav nav-tabs" id="myTab2" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab11" data-toggle="tab" href="#schoolAssesment" role="tab" aria-selected="false">School Assesment</a>
                    </li>
                    <?php if($_SESSION['usertype']===trim('superAdmin')){ ?>
                    <div class="filterassesmentby_branch_subject"></div>
                    <?php } ?>
                  </ul>
                  <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="schoolAssesment" role="tabpanel" aria-labelledby="home-tab11">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="addSchoolAssesment" value="" data-toggle="modal" data-target="#add_school_assesment"><span class="text-black">
                          <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add School Assesment</button>
                       </span>
                       </a>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="display dataTable" id='empTableGS' style="width:100%;">
                        <thead>
                          <tr>
                            <th>Evaluation Name</th>                           
                            <th>Grade</th>   
                            <th>Subject</th>                      
                            <th>Assesment Name</th>
                            <th>Percentage</th>                         
                            <th>Is Mandatory</th>                         
                            <th>End Date</th>
                            <th>Branch</th>
                            
                          </tr>
                        </thead>
                      </table> 
                    </div>
                    <div class="fetchSchoolAssesment"></div>
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
  
  <div class="modal fade" id="add_school_assesment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <h5 class="modal-title" id="exampleModalCenterTitle">Add School Assesment</h5> -->
          <span class="text-time">Note:- You can select branch and subject if school`s assesment are different based on branch</span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card">
          <form method="POST" id="submit_assesment">
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="form-group">
                <label>Branch</label>
                <?php if($assesment_status->num_rows()>0){ ?>
                <select class="form-control" required="required" name="schoolAssesmentBranch" id="schoolAssesmentBranch">
                  <option> </option>
                    <?php foreach($branch as $branchs){ ?>
                    <option value="<?php echo $branchs->name;?>">
                      <?php echo $branchs->name;?>
                    </option>
                    <?php }?>
                </select>
                <?php }else{ ?>
                  <select class="form-control" required="required" name="schoolAssesmentBranch" id="schoolAssesmentBranch" disabled="disabled">
                  <option> </option>
                    <?php foreach($branch as $branchs){ ?>
                    <option value="<?php echo $branchs->name;?>">
                      <?php echo $branchs->name;?>
                    </option>
                    <?php }?>
                  </select>
                <?php } ?>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="form-group">
                <label>Evaluation</label>
                <select class="form-control selectric" required="required" name="schoolAssesmentEva" id="schoolAssesmentEva">
                  <option></option>
                </select>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="form-group">
                <label>Assesment Name</label>
                <input class="form-control" id="schoolAssesmentName" 
                  name="schoolAssesmentName" type="text" placeholder="School assesment...." required>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="form-group">
                <label>Percent(Optional)</label>
                <input class="form-control" id="assesmentPercent" 
                  name="assesmentPercent" type="number" placeholder="Percentage....">
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="form-group">
                <label>End Date(Optional)</label>
                <input class="form-control" id="assesmentEndDate" 
                  name="assesmentEndDate" type="date" placeholder="Enter here school assesment....">
              </div>
            </div>
            <div class="col-lg-4 col-6 table-responsive" style="height: 20vh;">
              <div class="form-group">
                <label>Select Grade</label>
                <div class="row">
                  <?php foreach($grade as $grades){ ?>
                  <div class="col-lg-3 col-6">
                  <div class="pretty p-bigger">
                   <input id="assesementGrade" type="checkbox" name="assesementGrade" value="<?php echo $grades->grade; ?>">
                   <div class="state p-success">
                      <i class="icon material-icons"></i>
                      <label></label><?php echo $grades->grade; ?>
                   </div>
                   </div>
                    
                    <div class="dropdown-divider2"></div>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-12 table-responsive" style="height: 20vh;">
              <div class="form-group">
                <label>Select subject</label>
                <div class="subject_4_assesment"></div>
              </div>
            </div>
            <div class="col-lg-4 col-6">
              <div class="form-group">
                <label>Order(Optional)</label>
                <input type="number" name="assorder" id="assorder" class="form-control" placeholder="Order">
              </div>
            </div>
            <div class="col-lg-4 col-6">
              <input type="checkbox" name="ismandatory" id="ismandatory"> Is this Mandatory?
              <button type="submit" id="postAssesment" name="postAssesment" class="btn btn-primary btn-block">Save Changes
              </button>
            </div>
          </div>
        </form>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <a id="saveskygrade"></a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/css/daterangepicker.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
  $(document).on('click', '#assesementGrade', function() {
    grade2analysis=[];
    $("input[name='assesementGrade']:checked").each(function(i){
      grade2analysis[i]=$(this).val();
    });
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>schoolassesment/filter_Subject_4_assesment/",
      data:({
        grade2analysis:grade2analysis
      }),
      beforeSend: function() {
        $('.subject_4_assesment').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
          );
      },
      success: function(data) {
        $(".subject_4_assesment").html(data);
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
        'url':'<?=base_url()?>schoolassesment/fetch_School_Assesment/'
      },
      'columns': [
        { data: 'saseval' },
        { data: 'sasgrade' },
        { data: 'assesment_subject' },
        { data: 'sasname' },
        { data: 'saspercent' },
        { data: 'ismandatory' },
        { data: 'dateend' },
        { data: 'assesment_branch' },
        
      ]
    });
  });
  $(document).ready(function(){
    loadSchoolAssesmentFilter();
    filterassesmentby_branch_subject();
    function filterassesmentby_branch_subject() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>schoolassesment/filterassesmentby_branch_subject/',
        cache: false,
        beforeSend: function() {
          $('.filterassesmentby_branch_subject').html( 'Checking...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
         $('.filterassesmentby_branch_subject').html(html);
        }
      })
    }
    $(document).on('click', "input[name='filter_assesment_by_branch_subject']", function() {
      var lockmarkk=$(this).attr("value");
      var academicyear=$(this).attr("id");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>schoolassesment/onn_filter_assesment/",
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
          url:"<?php echo base_url() ?>schoolassesment/off_filter_assesment/",
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
    function loadSchoolAssesmentFilter() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>schoolassesment/fetchEval4AssesmentFilter/',
        cache: false,
        beforeSend: function() {
          $('#schoolAssesmentEva').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
          $('#schoolAssesmentEva').html(html);
        }
      })
    }
    
    $("#submit_assesment").on("submit",function(event){
      event.preventDefault();
      var assesmentEval=$("#schoolAssesmentEva").val();
      var assesmentName=$("#schoolAssesmentName").val();
      var assesmentEndDate=$("#assesmentEndDate").val();
      var assesmentPercent=$("#assesmentPercent").val();
      var assesmentBranch=$("#schoolAssesmentBranch").val();
      var assorder=$("#assorder").val();
      if($('#ismandatory').is(':checked')){
        var ismandatory='1';
      }else{
        var ismandatory='0';
      }
      assesmentGrade=[];assesmentSubject=[];
      $("input[name='assesementGrade']:checked").each(function(i){
        assesmentGrade[i]=$(this).val();
      });
      $("input[name='subjectfilter_4_assesment']:checked").each(function(i){
        assesmentSubject[i]=$(this).val();
      });
      if($("#schoolAssesmentName").val()!=='' && assesmentGrade.length!=0){
        if(assesmentSubject.length==0){
          $.ajax({
            url:"<?php echo base_url() ?>schoolassesment/saveSchoolAssesment_noSubject/",
            method:"POST",
            data:({
              assesmentEval:assesmentEval,
              assesmentName:assesmentName,
              assesmentGrade:assesmentGrade,
              assesmentEndDate:assesmentEndDate,
              assorder:assorder,
              ismandatory:ismandatory,
              assesmentPercent:assesmentPercent
            }),
            beforeSend:function(){
              $("#postAssesment").html("Saving");
              $("#postAssesment").attr("disabled","disabled");
            },
            success: function(){
              $('#empTableGS').DataTable().ajax.reload();
              $("#schoolAssesmentName").val('');
              $("#postAssesment").html("Save Changes");
              $("#postAssesment").removeAttr("disabled");
            }
          });
        }else{
          $.ajax({
            url:"<?php echo base_url() ?>schoolassesment/saveSchoolAssesment/",
            method:"POST",
            data:({
              assesmentEval:assesmentEval,
              assesmentName:assesmentName,
              assesmentGrade:assesmentGrade,
              assesmentSubject:assesmentSubject,
              assesmentBranch:assesmentBranch,
              assesmentEndDate:assesmentEndDate,
              assorder:assorder,
              ismandatory:ismandatory,
              assesmentPercent:assesmentPercent
            }),
            beforeSend:function(){
              $("#postAssesment").html("Saving");
              $("#postAssesment").attr("disabled","disabled");
            },
            success: function(){
              $('#empTableGS').DataTable().ajax.reload();
              $("#schoolAssesmentName").val('');
              $("#postAssesment").html("Save Changes");
              $("#postAssesment").removeAttr("disabled");
            }
          });
        }
      }
    });
    $(document).on('click', '.deleteAssesment', function() { 
      var sasname = $(this).attr("id");
      swal({
        title: 'Are you sure you want to delete this assesment name?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>schoolassesment/deleteAssesment/",
            data: ({
                sasname: sasname
            }),
            cache: false,
            success: function(html) {
             $('#empTableGS').DataTable().ajax.reload();
            }
          });
        } else {
          return false;
        }
      });
    });
    $(document).on('click', '.deleteAssesmentSasName', function() { 
      var sasname = $(this).attr("id");
      var sasgrade = $(this).attr("value");
      var sasID = $(this).attr("name");
      swal({
        title: 'Are you sure you want to delete this assesment name?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>schoolassesment/deleteAssesmentName/",
            data: ({
                sasname: sasname,
                sasgrade:sasgrade,
                sasID:sasID
            }),
            cache: false,
            success: function(html) {
              $('#empTableGS').DataTable().ajax.reload();
            }
          });
        } else {
          return false;
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).on('change', '.asses_endDate', function() {
    var sasgrade=$(this).attr("name");
    var sasname=$(this).attr("data_sasName");
    var sasID=$(this).attr("data-asses-id");
    var value=$("#asses_endDate"+sasID).val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>schoolassesment/updateAssesmentEnddate/",
        data: ({
          sasname:sasname,
          value:value,
          sasgrade:sasgrade,
          sasID:sasID
          
        }),
        success: function(data) {
          if(data=='1'){
            iziToast.success({
              title: 'Changes updated successfully',
              message: '',
              position: 'topRight'
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
  });
  $(document).on('change', '#isassmandatory', function() {
    var value=$(this).find('option:selected').attr('value');
    var sasgrade=$(this).find('option:selected').attr('name');
    var sasname=$(this).find('option:selected').attr('class');
    var sasID=$(this).find('option:selected').attr('data-mandatory');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>schoolassesment/updateAssesmentMandatory/",
        data: ({
          value:value,
          sasgrade:sasgrade,
          sasname:sasname,
          sasID:sasID
        }),
        success: function(data) {
          iziToast.success({
            title: 'Changes Updated successfully',
            message: '',
            position: 'topRight'
          });
        }
    });
  });
  $(document).on('change', '#isassOrder', function() {
    var value=$(this).find('option:selected').attr('value');
    var sasgrade=$(this).find('option:selected').attr('name');
    var sasname=$(this).find('option:selected').attr('class');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>schoolassesment/updateAssesmentOrder/",
        data: ({
          value:value,
          sasgrade:sasgrade,
          sasname:sasname
        }),
        success: function(data) {
          iziToast.success({
            title: 'Changes Updated successfully',
            message: '',
            position: 'topRight'
          });
        }
    });
  });
  $(document).on('change', '#isassPercentage', function() {
    var value=$(this).find('option:selected').attr('value');
    var sasgrade=$(this).find('option:selected').attr('name');
    var sasname=$(this).find('option:selected').attr('class');
    var sasID=$(this).find('option:selected').attr('data-percentage');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>schoolassesment/updateAssesmentPercentage/",
        data: ({
          value:value,
          sasgrade:sasgrade,
          sasname:sasname,
          sasID:sasID
        }),
        success: function(data) {
          iziToast.success({
            title: 'Changes Updated successfully',
            message: '',
            position: 'topRight'
          });
        }
    });
  });
</script>
</body>
</html>