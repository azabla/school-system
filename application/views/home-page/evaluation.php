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
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
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
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-12">
                <a href="#" id="evaluation_status"></a>
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#feedSubject" role="tab" aria-selected="true">Default Evaluation</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#feedMergedSubject" role="tab" aria-selected="false">Custom Evaluation</a>
                      </li>
                      <?php if($_SESSION['usertype']===trim('superAdmin')){ ?>
                      <div class="enable_teachers_change_evaluation"></div>
                      <?php } ?>
                      <!-- <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#groupEvaluation" role="tab" aria-selected="false">Group Evaluation</a>
                      </li> -->
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="feedSubject" role="tabpanel" aria-labelledby="home-tab1">
                         <a href="#" class="AddNewEvaluation" value="" data-toggle="modal" data-target="#newEvaluation">
                          <button class="btn btn-info pull-right"><i data-feather="plus-circle"> </i> Add Evaluation</button>
                        </a>
                        <div class="table-responsive">
                          <div id="evaluationData"> </div>
                        </div>
                      </div>
                      <div class="tab-pane fade show" id="feedMergedSubject" role="tabpanel" aria-labelledby="home-tab2">
                        <a href="#" class="AddNewCustomEvaluation" value="" data-toggle="modal" data-target="#newCustomEvaluation">
                          <button class="btn btn-info pull-right"><i data-feather="plus-circle"> </i> Add Custom Evaluation</button>
                        </a>
                        <div class="table-responsive">
                          <table class="display dataTable" id='empTableGS' style="width:100%;">
                            <thead>
                              <tr>
                                <th>Evaluation Name</th>                           
                                <th>Grade</th>
                                <th>Subject</th>
                                <th>Percentage</th>
                                <th>Season</th>                         
                                <th>Year</th>
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
      <form id="save_newevaluation" method="POST">
        <div class="modal fade" id="save_evaluations" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Evaluation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body evaluation_here">
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <a class="evl_remove"></a>
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
  
  <!--  -->
  <div class="modal fade" id="newCustomEvaluation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add New Custom Evaluation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="dropdown-divider"></div>
        <div class="card">
          <div class="card-body">
            <form id="saveCustomEvaluation" method="POST">
              <div class="row">
                <div class="col-lg-4 col-12 table-responsive">
                  <div class="form-group">
                    <div class="row">
                      <?php foreach($grade as $grades){ ?>
                        <div class="col-lg-3 col-6">
                        <div class="pretty p-bigger">
                         <input id="eva_grade_custom" type="checkbox" class="grade_custom" name="grade_custom" value="<?php echo $grades->grade; ?>">
                         <div class="state p-primary">
                            <i class="icon material-icons"></i>
                            <label></label><?php echo $grades->grade; ?>
                         </div>
                         </div>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-12 table-responsive evaluation_here_custom" style="height:20vh"> </div>
                <div class="col-md-5 col-12 table-responsive defaultSubjectHere_custom" style="height:20vh"> </div>
                <div class="col-lg-6 col-6">
                  <div class="form-group">
                    <input class="form-control eva_percent_custom" name="customPercent" type="number" placeholder="Value (In Number)...">
                  </div>
                </div>
                <div class="col-lg-6 col-6  pull-right">
                  <div class="form-group">
                    <button type="submit" name="postCustomEvaluation" class="btn btn-info btn-block">Save Evaluation </button>
                  </div>
                    <a href="#" class="save_info_custom"></a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--  -->
   <div class="modal fade" id="newEvaluation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add New Evaluation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="dropdown-divider"></div>
        <form id="save_evaluation" method="POST">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-3 col-6">
                  <div class="form-group">
                    <label for="evname">Evaluation Name</label>
                    <input class="form-control eva_name" name="evname" type="text" placeholder="Evaluation name (Test,Final)...">
                  </div>
                 </div>
                <div class="col-lg-3 col-6">
                  <div class="form-group">
                    <label for="Mobile">Evaluation Percentage
                    </label>
                    <input class="form-control eva_percent" name="percent" type="text" placeholder="Value here(In Number)...">
                  </div>
                </div>
                <div class="col-lg-6 col-12 table-responsive datapageheight">
                  <div class="form-group">
                    <label for="Mobile">Select grade</label><br>
                    <div class="row">
                      <?php foreach($grade as $grades){ ?>
                        <div class="col-lg-3 col-6">
                        <div class="pretty p-bigger">
                         <input id="eva_grade" type="checkbox" name="grade" value="<?php echo $grades->grade; ?>">
                         <div class="state p-info">
                            <i class="icon material-icons"></i>
                            <label></label><?php echo $grades->grade; ?>
                         </div>
                         </div>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12 col-12 pull-right">
                  <div class="form-group">
                    <label for="Mobile"></label>
                    <button type="submit" name="postevaluation" class="btn btn-primary pull-right">Save Evaluation </button>
                  </div>
                    <a href="#" class="save_info"></a>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--  -->
  <div class="modal fade" id="newGroupEvaluation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Group Evaluation</h5> &nbsp;&nbsp;&nbsp;
          <span class="pull-right grouped_evaluatin_error_info"></span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="dropdown-divider"></div>
        <div class="card">
          <div class="card-header">
            <form id="saveGroupEvaluation" method="POST">
              <div class="row">
                <div class="col-lg-4 col-12 table-responsive">
                  <div class="form-group">
                    <div class="row">
                      <?php foreach($grade as $grades){ ?>
                        <div class="col-lg-4 col-4">
                        <div class="pretty p-bigger">
                         <input id="eva_grade_group_gs" type="checkbox" name="grade_group_gs" value="<?php echo $grades->grade; ?>">
                         <div class="state p-primary">
                            <i class="icon material-icons"></i>
                            <label></label><?php echo $grades->grade; ?>
                         </div>
                         </div>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-6 table-responsive evaluation_here_group" style="height:30vh"> </div>
                <div class="col-lg-5 col-6">
                  <textarea class="selectAssesmentForGroup" rows="4" cols="50" wrap="physical" name="selectAssesmentForGroup" id="selectAssesmentForGroup" style="width:100%; height:100px;" required> </textarea><br>
                  <button class="btn btn-default RemoveAll" id="removeAll" type="submit"><i class="fas fa-angle-double-left"></i></button>
                </div>
                <div class="col-lg-4 col-6">
                  <div class="form-group">
                    <input class="form-control eva_percent_group" name="eva_percent_group" type="text" placeholder="Group name...">
                  </div>
                </div>
                <div class="col-lg-6 col-6  pull-right">
                  <div class="form-group">
                    <button type="submit" name="postgroupEvaluation" class="card card-body bg-primary btn-block">Save Group </button>
                  </div>
                    <a href="#" class="save_info_group"></a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/gs_all.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type='text/javascript'>
    var baseURL= "<?php echo base_url();?>";
  </script>
  <script type="text/javascript">
    $(document).on('click', "input[name='enable_teachers_change_evaluation']", function() {
      var lockmarkk=$(this).attr("value");
      var academicyear=$(this).attr("id");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>evaluation/onn_teacher_enable_evaluation_change/",
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
              /*window.location.reload();*/
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
              /*window.location.reload();*/
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
          url:"<?php echo base_url() ?>evaluation/off_teacher_enable_evaluation_change/",
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
              /*window.location.reload();*/
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
    $('#empTableGS').DataTable({
      'processing': true,
      'serverSide': true,
      "dataType": "json",
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>evaluation/fetchCustomEvaluations/'
      },
      'columns': [
        { data: 'customasses' },
        { data: 'customgrade' },
        { data: 'customsubject' },
        { data: 'custompercent' },
        { data: 'customquarter' },
        { data: 'academicyear' },
      ]
    });
    $(document).ready(function() {
      load_group_data();
      enable_teachers_change_evaluation();
      function enable_teachers_change_evaluation() {
        $.ajax({
          method:'POST',
          url:'<?php echo base_url() ?>evaluation/enable_teachers_change_evaluation/',
          cache: false,
          beforeSend: function() {
            $('.enable_teachers_change_evaluation').html( 'Checking...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
            );
          },
          success: function(html){
           $('.enable_teachers_change_evaluation').html(html);
          }
        })
      }
      function load_group_data()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>evaluation/fetchGroupEvaluations/",
          method:"POST",
          beforeSend: function() {
            $('#groupEvaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success:function(data){
            $('#groupEvaluationData').html(data);
          }
        })
      }
    $(document).on('click', '.deleteGroupevaluationHeader', function() {
      var year = $(this).attr("id");
      var quarter = $(this).attr("value");
      var group_name = $(this).attr("name");
      swal({
        title: 'Are you sure?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>evaluation/deleteGroupEvaluation/",
          data: ({
            year: year,
            quarter:quarter,
            group_name :group_name
          }),
          cache: false,
          success: function(html) {
            load_group_data();
          }
        });
      }
      });
    });
    $('#saveGroupEvaluation').on('submit', function(event) {
        event.preventDefault();
        var groupName=$('.eva_percent_group').val();
        id=[];
        $("input[name='grade_group_gs']:checked").each(function(i){
          id[i]=$(this).val();
        });
        var newServicePlace=$('#selectAssesmentForGroup').val();
        var stuIdArray=newServicePlace.split(/(\s+)/);
        if( id.length == 0 || $('.eva_percent_group').val() =='')
        {
          swal('Oooops, Please select necessary fields!', {
            icon: 'warning',
          });
        }else{
          $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>evaluation/postGroupEvaluation/",
          data: ({
            id:id,
            groupName:groupName,
            stuIdArray:stuIdArray
          }),
          cache: false,
          success: function(html){
            $(".grouped_evaluatin_error_info").html(html);
            $('#saveGroupEvaluation')[0].reset();
            load_group_data();
          }
        });
      }
    });
      $(document).on('click', '#moveGroupEvaluation', function() {
        $.ajax({
          url:"<?php echo base_url(); ?>evaluation/movingGroupEvaluations/",
          method:"POST",
          beforeSend: function() {
            $('#groupEvaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success:function(data){
            $('#groupEvaluationData').html(data);
            load_group_data();
          }
        })
      });
    });
    $(document).on('click', '.assesment4GroupEvaluation', function(event) {
      event.preventDefault();
      var oldText=$('#selectAssesmentForGroup').val();
      var stuID=$(this).attr("value");
      var newText=oldText+stuID+"\n";
      $("#selectAssesmentForGroup").val(newText);   
    });
    $(document).on('click', '#removeAll', function(event) {
      event.preventDefault();
      $("#selectAssesmentForGroup").val('');   
    });
    $(document).on('click', '#eva_grade_group_gs', function() {
      grade2analysis=[];
      $("input[name='grade_group_gs']:checked").each(function(i){
        grade2analysis[i]=$(this).val();
      });
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>evaluation/filterAssesmentGroupEvaluation/",
        data:({
          grade2analysis:grade2analysis
        }),
        beforeSend: function() {
          $('.evaluation_here_group').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".evaluation_here_group").html(data);
        }
      });
    });
  $(document).ready(function() {
     $(document).on('click', '.deleteCustomvaluation', function() {
      var post_id = $(this).attr("id");
      var quarter = $(this).attr("value");
      var evname = $(this).attr("name");
      swal({
        title: 'Are you sure?',
        text: 'Once deleted you can not recover this evaluation mark data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>evaluation/deleteCustomEvaluation/",
          data: ({
            post_id: post_id,
            quarter:quarter,
            evname :evname
          }),
          cache: false,
          success: function(html) {
            $('#empTableGS').DataTable().ajax.reload();
          }
        });
      }
      });
    });
  });
    $(document).ready(function(){
      $('#saveCustomEvaluation').on('submit', function(event) {
        event.preventDefault();
        var grade=$('#eva_grade_custom').val();
        var percent=$('.eva_percent_custom').val();
        id=[];subject=[];evalname=[];
        $("input[name='grade_custom']:checked").each(function(i){
          id[i]=$(this).val();
        });
        $("input[name='subject4CustomEvaluation']:checked").each(function(i){
          subject[i]=$(this).val();
        });
        $("input[name='assesment4CustomEvaluation']:checked").each(function(i){
          evalname[i]=$(this).val();
        });
        if( id.length == 0 || subject.length == 0 || $('.eva_percent_custom').val() =='' || evalname.length == 0)
        {
          swal('Oooops, Please select necessary fields!', {
            icon: 'warning',
          });
        }else{
          $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>evaluation/postCustomEvaluation/",
          data: ({
            id: id,
            evalname:evalname,
            percent:percent,
            subject:subject
          }),
          cache: false,
          success: function(html){
            $('#saveCustomEvaluation')[0].reset();
            $('#empTableGS').DataTable().ajax.reload();
          }
        });
      }
    });
    $(document).on('click', '#moveCustomEvaluation', function() {
      $.ajax({
        url:"<?php echo base_url(); ?>evaluation/movingCustomEvaluations/",
        method:"POST",
        success:function(data){
          $('#empTableGS').DataTable().ajax.reload();
        }
      })
    });
  });
    $(document).on('click', '.grade_custom', function() {
      grade2analysis=[];
      $("input[name='grade_custom']:checked").each(function(i){
        grade2analysis[i]=$(this).val();
      });
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>evaluation/filterSubject4CustomEvaluation/",
        data:({
          grade2analysis:grade2analysis
        }),
        beforeSend: function() {
          $('.defaultSubjectHere_custom').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".defaultSubjectHere_custom").html(data);
        }
      });
    });
    $(document).on('click', '.grade_custom', function() {
      grade2analysis=[];
      $("input[name='grade_custom']:checked").each(function(i){
        grade2analysis[i]=$(this).val();
      });
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>evaluation/filterAssesmentCustomEvaluation/",
        data:({
          grade2analysis:grade2analysis
        }),
        beforeSend: function() {
          $('.evaluation_here_custom').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".evaluation_here_custom").html(data);
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
        url:"<?php echo base_url(); ?>evaluation/fetchEvaluations/",
        method:"POST",
        beforeSend: function() {
          $('#evaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#evaluationData').html(data);
        }
      })
    }
    $('#save_evaluation').on('submit', function(event) {
      event.preventDefault();
      var grade=$('#eva_grade').val();
      var evname=$('.eva_name').val();
      var percent=$('.eva_percent').val();
     /* var quarter=$('#eva_quarter').val();*/
      id=[];
      $("input[name='grade']:checked").each(function(i){
        id[i]=$(this).val();
      });
      if( id.length == 0 || $('#eva_quarter').val() =='' || $('.eva_percent').val() =='' || $('.eva_name').val() =='')
      {
        swal('Oooops, Please select necessary fields!', {
          icon: 'warning',
        });
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>evaluation/postEvaluation/",
        data: ({
          id: id,
          evname:evname,
          percent:percent
        }),
        cache: false,
        success: function(html){
          $('#save_evaluation')[0].reset();
          load_data();
        }
      });
    }
  });
  $(document).on('click', '#movevaluation', function() {
    $.ajax({
      url:"<?php echo base_url(); ?>evaluation/movingEvaluations/",
      method:"POST",
      beforeSend: function() {
        $('#evaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('#evaluationData').html(data);
        load_data();
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
<script>
  $(document).ready(function() {
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>evaluation/fetchEvaluations/",
        method:"POST",
        beforeSend: function() {
          $('#evaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#evaluationData').html(data);
        }
      })
    }
     $(document).on('click', '.deletevaluation', function() {
      var post_id = $(this).attr("id");
      var quarter = $(this).attr("value");
      var evname = $(this).attr("name");
      swal({
        title: 'Are you sure?',
        text: 'Once deleted you can not recover this evaluation mark data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
      if (willDelete) {
        swal('Assesment evaluation deleted successfully!', {
          icon: 'success',
        });
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>evaluation/deleteEvaluation/",
          data: ({
            post_id: post_id,
            quarter:quarter,
            evname :evname
          }),
          cache: false,
          success: function(html) {
            load_data();
          }
        });
      }
      });
    });
  });
</script>
<script>
  $(document).on('click', '.remove_evalGS', function() {
    swal({
        title: 'Are you sure?',
        text: 'Once deleted you can not recover this evaluation mark data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
    .then((willDelete) => {
      if (willDelete) {
        var grade = $(this).attr("value");
        var quarter = $(this).attr("name");
        var evname = $(this).attr("id");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Delete_specific_gradevaluation",
          data: ({
            grade: grade,
            quarter:quarter,
            evname :evname
          }),
          cache: false,
          success: function(html) {
            $("#deleteEva" + grade + evname).fadeOut('slow');
            load_data();
          }
        });
      }
    });
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>evaluation/fetchEvaluations/",
        method:"POST",
        beforeSend: function() {
          $('#evaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#evaluationData').html(data);
        }
      })
    }
  });
</script>
<script>
  $('#save_newevaluation').on('submit', function(event) {
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>evaluation/fetchEvaluations/",
        method:"POST",
        beforeSend: function() {
          $('#evaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">');
        },
        success:function(data){
          $('#evaluationData').html(data);
        }
      })
    }
    event.preventDefault();
    var evname = $('#my_evname').val();
    var new_evname = $('#new_evname').val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>evaluation/Edit_thisgradevaluation",
      data: ({
        evname :evname,
        new_evname:new_evname
      }),
      beforeSend: function() {
        $('.evl_remove').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
      },
      cache: false,
      success: function(html) {
        $(".evl_remove").html(html);
        load_data();
      }
    });
  });
  load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>evaluation/fetchEvaluations/",
        method:"POST",
        beforeSend: function() {
          $('#evaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">');
        },
        success:function(data){
          $('#evaluationData').html(data);
        }
      })
    }
    $(document).on('change', '#percentageGradeEvaluation', function() {
      var grade=$(this).find('option:selected').attr('id');
      var value=$(this).find('option:selected').attr('value');
      var evname=$(this).find('option:selected').attr('name');
      var academicyear=$("#my_ac").val();
      var quarter=$("#my_quarterEval").val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>evaluation/updateEachEvaluationPercentage",
        data: ({
          evname:evname,
          grade: grade,
          value:value,
          academicyear:academicyear,
          quarter:quarter
        }),
        cache: false,
        beforeSend: function() {
          $('.greval' + grade + evname).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
          );
        },
        success: function(html){
          if(html=='1'){
            $('.greval' + grade + evname).html('Saved');
            load_data();
            iziToast.success({
              title: 'Changes saved successfully',
              message: '',
              position: 'topRight'
            });
          }else{
            $('.greval' + grade + evname).html('Not Saved');
            iziToast.error({
              title: 'Please try again.',
              message: '',
              position: 'topRight'
            });
          }
          
        }
      });
    });
</script>
<script>
  $(document).ready(function() {
     $(document).on('click', '.editevaluation', function() {
      var post_id = $(this).attr("id");
      var quarter = $(this).attr("value");
      var evname = $(this).attr("name");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>evaluation/fetchEvaluationsToEdit",
        data: ({
          post_id: post_id,
          quarter:quarter,
          evname :evname
        }),
        cache: false,
        beforeSend: function() {
          $('.evaluation_here').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">');
        },
        success: function(html) {
          $(".evaluation_here").html(html);
        }
      });
    });
  });
</script>
<script>
  $(document).ready(function() { 
    function load_evaluation_status()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>fetch_evaluation_status",
        method:"POST",
        success:function(data){
          $('#evaluation_status').html(data);
        }
      })
    } 
    load_evaluation_status();
    setInterval(function() {
      load_evaluation_status();
    }, 5000);
  });
</script>
</body>

</html>