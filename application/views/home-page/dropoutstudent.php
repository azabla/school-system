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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
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
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#defaultUnarchive" role="tab" aria-selected="true">Default Unarchive</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#groupUnarchive" role="tab" aria-selected="false">Group Unarchive</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="defaultUnarchive" role="tabpanel" aria-labelledby="home-tab1">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                        <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>
                        </button>
                        <div class="table-responsive" id="Dropoutstudents">
                        <table class="display dataTable" id='empTableGS' style="width:100%;">
                          <thead>
                            <tr>
                              <th>Student Name</th>                           
                              <th>Student ID</th>                         
                              <th>Grade</th>
                              <th>Gender</th>
                              <th>Branch</th>                         
                              <th>Dropped Year</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                        </table> 
                      </div>
                      </div>
                      <div class="tab-pane fade show" id="groupUnarchive" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="alert alert-light alert-dismissible show fade">
                          <i class="fas fa-check-circle"> </i> Note: This section works for only <?php echo $maxYear ?> Academic Year.
                        </div>
                        <div class="card">
                            <div class="">
                              <div class="row"> 
                                <div class="col-lg-6 col-12">
                                  <div class="card-header">
                                    <input type="text" class="form-control typeahead" id="searchStudentForTransportPlace" name="searchStudentForTransportPlace" placeholder="Search Student Id,Name">
                                    <div class="table-responsive" style="height:15vh;">
                                      <div class="searchPlaceHere"></div> 
                                    </div>
                                  </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                  <textarea class="form-control" id="selectStudentForTransportPlace" name="selectStudentForTransportPlace" col="12">  </textarea>
                                  <button class="btn btn-default RemoveAll" id="removeAll" type="submit"><i class="fas fa-angle-double-left"></i></button>
                                </div> 
                                <div class="col-lg-6 col-6">
                                  <div class="card-header">
                                    <select class="form-control" required="required" name="takeActionOption" id="takeActionOption">
                                      <option>Select Action</option>
                                      <option value="UndropGroup">Register Selected</option>                                                    
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-6 col-6">
                                  <div class="card-header">
                                    <button type ="submit" class="btn btn-info" id="saveNewTransportPlace" name="saveNewTransportPlace" >Save Changes</button>
                                  </div>
                                </div>
                              </div>
                            </div>
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
  <div class="modal fade" id="register_dropout_student" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Re-register dropped student</h5>          
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="card-body StudentViewTextInfo">
            <div class="modal-body">
              <div class="fetch_dropoutstudents_gs"></div>
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
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function(){
    $('#empTableGS').DataTable({
      'processing': true,
      'serverSide': true,
      "dataType": "json",
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>Dropoutstudents/fetch_inactivestudents/'
      },
      'columns': [
        { data: 'fname' },
        { data: 'username' },
        { data: 'gradesec' },
        { data: 'gender' },
        { data: 'branch' },
        { data: 'academicyear' },
        { data: 'Action' },
      ]
    });
  });
  $(document).on('click', '#saveNewTransportPlace', function() {
      event.preventDefault();
      var takeAction=$('#takeActionOption').val(); 
      var newServicePlace=$('#selectStudentForTransportPlace').val();
      var stuIdArray=newServicePlace.split(/(\s+)/);
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
          url: "<?php echo base_url(); ?>Dropoutstudents/saveNewTransportPlace/",
          method: "POST",
          data: ({
            stuIdArray: stuIdArray,
            takeAction:takeAction
          }),
          success: function(data) {
            if(data=='1'){
              iziToast.success({
                title: 'Student has been registered successfully',
                message: '',
                position: 'topRight'
              });
            }else if(data=='2'){
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
            $('#empTableGS').DataTable().ajax.reload();
          }
        });
      }
    });
  });
    $(document).ready(function() { 
      $('#searchStudentForTransportPlace').on("keyup",function() {
        $searchItem=$('#searchStudentForTransportPlace').val();
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>Dropoutstudents/searchStudentsToTransportService/",
          data: "searchItem=" + $("#searchStudentForTransportPlace").val(),
          beforeSend: function() {
            $('.searchPlaceHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $(".searchPlaceHere").html(data);
          }
        });
      });
    });
    $(document).on('click', '.saveThisStudentToUnarchive', function() {
      event.preventDefault();
      var oldText=$('#selectStudentForTransportPlace').val();
      var stuID=$(this).attr("value");
      var newText=oldText+stuID+"\n";
      $("#selectStudentForTransportPlace").val(newText);   
    });
    $(document).on('click', '#removeAll', function() {
      event.preventDefault();
      $("#selectStudentForTransportPlace").val('');   
    });
  </script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("Dropoutstudents");
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
  $(document).on('click', '.registerstudent', function() {
    var register_id = $(this).attr("id");
    var yearDrooped = $(this).attr("value");
    var username = $(this).attr("name");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Dropoutstudents/fecth_student_toregister/",
      data: ({
        register_id: register_id,
        yearDrooped:yearDrooped,
        username:username
      }),
      cache: false,
      beforeSend: function() {
        $('.fetch_dropoutstudents_gs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(html) {
        $('.fetch_dropoutstudents_gs').html(html);
      }
    });
  });
  $(document).on('submit', '#registerDroppedStudent', function(event) {
    event.preventDefault();
    var register_id = $("#register_student_id").val();
    var yearDrooped = $("#register_student_year").val();
    var grade = $("#register_on_grade").val();
    var branch = $("#register_on_branch").val();
    var registerOnYear = $("#register_on_year").val();
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
          url: "<?php echo base_url(); ?>Dropoutstudents/register_student/",
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
            $('#register_dropout_student'). modal('hide');
            $('#empTableGS').DataTable().ajax.reload();
          }
        });
      }else {
        return false;
      }
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