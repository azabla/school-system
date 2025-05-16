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
  <link href='<?php echo base_url(); ?>assets/datatables/gs-dataTables.css' rel='stylesheet' type='text/css'>           
  
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
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
              <div class="col-12">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="row">
                  <div class="col-lg-3 col-6 form-group">
                    <button type="submit" name="dropoutStaffs" id="dropoutStaffs" class="card bg-light btn-block btn-sm" data-toggle="modal" data-target="#active_InactiveStaffProfile">  Dropout Staffs<i class="fas fa-user-minus"></i>
                    </button>
                  </div>
                  <div class="col-lg-3 col-6 form-group">
                    <form method="POST" action="<?php echo base_url(); ?>staffs/downloadStuData/">
                      <button type="submit" id="downloadStuData" name="downloadStuData" class="card bg-primary btn-block btn-sm"> Download Staff Data<i class="fas fa-download"></i>
                      </button>
                    </form>
                  </div>
                  <div class="col-lg-3 col-6 form-group">
                    <button type="submit" name="addnew" data-toggle="modal" data-target="#newStaffRegistration" class="card bg-info btn-block btn-sm">  New Staff<i class="fas fa-user-plus"></i>
                    </button>
                  </div>
                  <div class="col-lg-3 col-6 form-group">
                     <form method="POST" id="myForm" action="<?php echo base_url(); ?>staffs/resetUserPassword/">
                       <button type="submit" id="generatePassword" name="generatePassword" class="card bg-warning btn-block btn-sm">  Generate Staff Password<i class="fas fa-user-lock"></i>
                      </button> 
                    </form>
                  </div>
                </div>
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <div class="resetPasswordInfo"></div>
                    <div class="table-responsive staffsList"> 
                      <table class="display dataTable" id='empTable' style="width:100%;">
                        <thead>
                         <tr>
                           <th>Full Name</th>
                           <th>Usertype</th>
                           <th>Mobile</th>
                           <th>Branch</th>
                           <th>Status</th>
                          </tr>
                        </thead>
                      </table>  
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
  <div class="modal fade" id="newStaffRegistration" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4>New staff registration</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body card-header">
        <form method="POST" id="saveNewForm" class="saveNewForm" name="saveNewForm">
        <div class="form-group">
          <div class="search-element">
            <div class="row">
                <div class="form-group col-lg-4 col-6">
                  <label for="fname">First Name(<span class="text-danger"><i class="fas fa-asterisk"></i></span>)</label>
                  <input id="fnameRegistration" type="text" class="form-control" required="required" name="fnameRegistration">
                  <span class="text-danger"> 
                    <?php echo form_error('frist_name'); ?>
                  </span>
                </div>
                <div class="form-group col-lg-4 col-6">
                  <label for="lname">Father Name(<span class="text-danger"><i class="fas fa-asterisk"></i></span>)</label>
                  <input id="lnameRegistration" type="text" class="form-control" required="required" name="lnameRegistration">
                  <span class="text-danger">
                    <?php echo form_error('last_name'); ?>
                  </span>
                </div>
                <div class="form-group col-lg-4 col-6">
                  <label for="gf_name">GrandFather Name(<span class="text-danger"><i class="fas fa-asterisk"></i></span>)</label>
                  <input id="gfnameRegistration" type="text" class="form-control" required="required" name="gfnameRegistration">
                  <span class="text-danger">
                    <?php echo form_error('gf_name'); ?>
                  </span>
                </div>
              
                <div class="form-group col-lg-4 col-6">
                  <label for="gender">Gender(<span class="text-danger"><i class="fas fa-asterisk"></i></span>)</label><br>
                  <input type="radio" id="genderRegistration" name="genderRegistration" value="Male">
                  <label>Male</label>&nbsp &nbsp
                  <input type="radio" id="genderRegistration" name="genderRegistration" value="Female">
                  <label>Female</label>
                  <span class="text-danger">
                    <?php echo form_error('gender'); ?>
                  </span>
                </div>

                <div class="form-group col-lg-4 col-6">
                  <div class="form-group">
                    <label for="usertype">User Type(<span class="text-danger"><i class="fas fa-asterisk"></i></span>)</label>
                    <select class="form-control selectric" name="usertypeRegistration" id="usertypeRegistration" required="required">
                      <option> </option>
                      <?php foreach($usergroup->result() as $usergroups){ 
                        if($usergroups->uname!='Student') {?>
                        <option> <?php echo $usergroups->uname; ?></option>
                      <?php } } ?>
                    </select>
                    <span class="text-danger"> 
                      <?php echo form_error('usertype'); ?>
                    </span>
                  </div>
                </div>
                <div class="form-group col-lg-4 col-6">
                  <div class="form-group">
                    <label for="Username">Mobile</label>
                    <input id="mobileRegistration" type="text" class="form-control" name="mobileRegistration">
                    <span class="text-danger">
                      <?php echo form_error('mobile'); ?>
                    </span>
                  </div>
                </div>
              
                <div class="form-group col-lg-4 col-6">
                  <div class="form-group">
                    <label for="Mobile">Optional Mobile</label>
                    <input id="fathermobileRegistration" type="text" class="form-control" name="fathermobileRegistration">
                    <span class="text-danger"> 
                      <?php echo form_error('fathermobile'); ?>
                    </span>
                  </div>
                </div>
                <div class="form-group col-lg-4 col-6">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="emailRegistration" type="email" class="form-control" name="emailRegistration">
                    <span class="text-danger">
                      <?php echo form_error('email'); ?>
                    </span>
                  </div>
                </div>
                <div class="form-group col-lg-4 col-6">
                  <div class="form-group">
                    <label for="Profile">Profile Photo</label>
                    <input id="profileRegistration" type="file" class="form-control" name="profileRegistration">
                    <span class="text-danger">
                      <?php echo form_error('profile'); ?>
                    </span>
                  </div>
                </div>
                <div class="form-group col-lg-4 col-6">
                  <label for="dob" class="d-block">Date of Birth</label>
                  <input id="dobRegistration" type="date" class="form-control" data-indicator="pwindicator" name="dobRegistration" >
                  <span  class="dropdown-item has-icon text-danger"> 
                    <?php echo form_error('dob'); ?>
                  </span>
                </div>
                <div class="form-group col-lg-4 col-4">
                  <div class="form-group">
                    <label for="city">City</label>
                    <select class="form-control selectric" name="cityRegistration" id="cityRegistration">
                      <option> </option>
                      <option> Addis Ababa</option>
                      <option> Sheger City</option>
                      <option> Adama</option>
                      <option> Mekelle</option>
                      <option> Bahir Dar</option>
                      <option> Hawassa</option>
                      <option> Jimma</option>
                      <option> Gonder</option>
                      <option> Harrer</option>
                      <option> Dilla</option>
                      <option> Axum</option>
                      <option> Dire Dawa</option>
                      <option> Dukem</option>
                      <option> D/Zeit</option>
                      </select>
                      <span class="text-danger">
                        <?php echo form_error('city'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-4 col-4">
                    <div class="form-group">
                      <label for="Sub_city">Sub City</label>
                      <select class="form-control selectric" name="subcityRegistration" id="subcityRegistration">
                        <option> </option>
                        <option> Arada</option>
                        <option> Bole</option>
                        <option> Akaki Kality</option>
                        <option> Ns.Lafto</option>
                        <option> Gullele</option>
                        <option> Yeka</option>
                        <option> Kirkos</option>
                        <option> Lemi Kura</option>
                        </select>
                        <span class="text-danger"> 
                        <?php echo form_error('subcity'); ?>
                        </span>
                      </div>
                    </div>
                    <div class="form-group col-lg-4 col-4">
                      <div class="form-group">
                        <label for="woreda">Woreda</label>
                        <select class="form-control selectric" name="woredaRegistration" id="woredaRegistration">
                          <option> </option>
                          <option> 01</option>
                          <option> 02</option>
                          <option> 03</option>
                          <option> 04</option>
                          <option> 05</option>
                          <option> 06</option>
                          <option> 07</option>
                          <option> 08</option>
                          <option> 09</option>
                          <option> 10</option>
                          <option> 11</option>
                          <option> 12</option>
                          <option> 13</option>
                          <option> 14</option>
                        </select>
                        <span class="text-danger"> 
                        <?php echo form_error('woreda'); ?>
                        </span>
                      </div>
                    </div>                      
                    <div class="form-group col-lg-4 col-6">
                      <label for="password" class="d-block">Password(<span class="text-danger"><i class="fas fa-asterisk"></i></span>)</label>
                      <input id="passwordRegistration" required="required" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="passwordRegistration">
                      <span  class="text-danger">
                        <?php echo form_error('password'); ?>
                      </span>
                    </div>
                    <div class="form-group col-lg-4 col-6">
                      <label for="password2" class="d-block">Confirm Password(<span class="text-danger"><i class="fas fa-asterisk"></i></span>)</label>
                      <input id="password2Registration" required="required" type="password" class="form-control" name="password2Registration">
                      <span class="text-danger">
                        <?php echo form_error('password-confirm'); ?>
                      </span>
                    </div>
                    <div class="form-group col-lg-4 col-6">
                      <label for="password2" class="d-block">Staff Username(<span class="text-danger"><i class="fas fa-asterisk"></i></span>)</label>
                      <input id="stuidRegistration" required="required" type="text" class="form-control" name="stuidRegistration">
                      <span class="text-danger"> 
                        <?php echo form_error('stuid'); ?>
                      </span>
                    </div>
                    <div class="form-group col-lg-4 col-6">
                      <label for="password2" class="d-block">School Branch(<span class="text-danger"><i class="fas fa-asterisk"></i></span>)</label>
                      <select class="form-control selectric" required="required" name="branchRegistration"  id="branchRegistration">
                        <?php foreach($branch as $branchs){ ?>
                          <option><?php echo $branchs->name ?></option>
                        <?php } ?>
                      </select>
                      <span class="text-danger"> 
                        <?php echo form_error('password-confirm'); ?>
                      </span>
                    </div>
                    <div class="form-group col-lg-4 col-6">
                      <div class="form-group">
                        <label for="ac">Academic year(<span class="text-danger"><i class="fas fa-asterisk"></i></span>)</label>
                        <select class=" form-control selectric"
                        required="required" name="academicyearRegistration" id="academicyearRegistration">
                        <?php foreach($academicyear as $academicyears){ ?>
                          <option><?php echo $academicyears->year_name ?>
                          </option>
                        <?php } ?>
                      </select>
                      <span class="text-danger"> 
                        <?php echo form_error('ac'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-6">
                    <button class="btn btn-primary pull-right" name="savenewstaffLocal" id="savenewstaffLocal">
                      <i class="fas fa-save"></i> Save staffs
                    </button>
                  </div>
                </div>
                <h4 class="msgStaff" id="msgStaff"></h4>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="editStaffProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit Staff Profile</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
            <div class="card-body" id="edit_staff_profile_here">
              
            </div>
        </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="active_InactiveStaffProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Archived Staff List</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
            <div class="card-body" id="makeactive_staff_profile_here">
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
  <script src="<?php echo base_url(); ?>assets/datatables/gs-dataTables.js"></script>
</body>
<script type="text/javascript">
  $('.gs-sms-hr-page').addClass('active');
  function selectAllStaffs_GS(){
      var itemsall=document.getElementById('selectallstaffs_GS');
      if(itemsall.checked==true){
      var items=document.getElementsByName('activeInactiveStaffsList_GS[ ]');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('activeInactiveStaffsList_GS[ ]');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
  }
  $(document).ready(function(){
    $('#empTable').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
        'url':'<?=base_url()?>staffs/fetch_user/'
      },
      'columns': [
         { data: 'fname' },
         { data: 'usertype' },
         { data: 'mobile' },
         { data: 'branch' },
         { data: 'status' },
      ]
    });
  });
  $(document).on('click', '#generatePassword', function(e) {
    e.preventDefault();
    swal({
      title: 'Are you sure you want to generate new password for all staffs?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $('#myForm').submit();
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', "input[name='setAsFinalApproval']", function() {
      var staffUser = $(this).attr("id");
      if($(this).is(':checked')){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>staffs/feedApprovalStatus/",
            data: ({
              staffUser:staffUser
            }),
            cache: false,
            success: function(html) {
              iziToast.success({
                title: 'User saved as final approval on report card and roster.',
                message: '',
                position: 'bottomCenter'
              });
            }
          });
      }else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>staffs/deleteApprovalStatus/",
          data: ({
            staffUser:staffUser
          }),
          cache: false,
          success: function(html) {
            iziToast.success({
              title: 'User removed from final approval on report card and roster.',
              message: '',
              position: 'bottomCenter'
            });
          }
        });
      }
    });
  $(document).on('submit', '#saveNewForm', function(e) {
    e.preventDefault();
    if ($('#fnameRegistration').val() != '' && $('#gfnameRegistration').val() != '' && $('#lnameRegistration').val() != '' && $('#usertypeRegistration').val() != '' 
      && $("#genderRegistration:checked").val() && $('#passwordRegistration').val() != '' && $('#password2Registration').val() != '' && $('#stuidRegistration').val() != '') {
      if($('#passwordRegistration').val()==$('#password2Registration').val()){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Staffs/saveNewStaff/",
          data:new FormData(this),
          processData:false,
          contentType:false,
          cache: false,
          async:false,
          beforeSend: function() {
            $('#msgStaff').html( '<span class="text-info">Saving...</span>');
          },
          success: function(html){
             $("#msgStaff").html(html);
          }
        });
      }else{
        swal('Password does not match!', {
          icon: 'error',
        });
      }
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
</script>
<script type="text/javascript">
  
  function selectAllStaffsToActive(){
      var itemsall=document.getElementById('selectAllStaffsToActive');
      if(itemsall.checked==true){
      var items=document.getElementsByName('activeThisStaffsList[ ]');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('activeThisStaffsList[ ]');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
  }
</script>
<script type="text/javascript">
  $(document).on('click', '#downloadStuData', function() {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>staffs/downloadStuData/",
        cache: false,
        success: function(html) {
          $("#downloadStuData").html('Download Finished.');
          window.open('<?php echo base_url(); ?>staffs/downloadStuData/','_blanck');
        }
      });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#dropoutStaffs', function()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>staffs/fetch_dropout_staffs/",
      method:"POST",
      beforeSend: function() {
        $('#makeactive_staff_profile_here').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('#makeactive_staff_profile_here').html(data);
      }
    })
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#edit_staff', function()
  {
    var staff_id=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>staffs/editStaff/",
      data: ({
        staff_id: staff_id
      }),
      cache: false,
      beforeSend: function() {
        $('#edit_staff_profile_here').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
       $('#edit_staff_profile_here').html(html);
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('submit', '#updateStaForm', function(e) {
    e.preventDefault();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>staffs/updateStaff/",
      data:new FormData(this),
      processData:false,
      contentType:false,
      cache: false,
      beforeSend: function() {
        $('#savechanges_staff_update').attr('disabled', 'disabled');
        $('#savechanges_staff_update').html( 'Saving');
      },
      success: function(html){
        $('#editStaffProfile').modal('hide');
        $('#empTable').DataTable().ajax.reload();
        if(html=='1'){
          iziToast.success({
            title: 'Profile updated successfully',
            message: '',
            position: 'topRight'
          });
        }else{
          iziToast.error({
            title: 'Something wrong, please try again.',
            message: '',
            position: 'topRight'
          });
        }
        $('#savechanges_staff_update').removeAttr( 'disabled');
        $('#savechanges_staff_update').html( 'Save Changes');
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '.InactiveCustomStaffs', function()
  {
    staff_id=$(this).attr("value");
    swal({
        title: 'Are you sure?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
      if (willDelete) {
        swal('Staff Inactive successfully!', {
          icon: 'success',
        });
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>staffs/inactiveStaff/",
          data: ({
            staff_id: staff_id
          }),
          cache: false,
          success: function(html){
            $('.delete_staff' + staff_id).fadeOut('slow');
            $('#empTable').DataTable().ajax.reload();
          }
        });
      }
    });
  });
  $(document).on('click', '.ActiveCustomStaffs', function()
  {
    staff_id=[];
    $("input[name='activeThisStaffsList[ ]']:checked").each(function(i){
      staff_id[i]=$(this).val();
    });
    if(staff_id.length!=0){ 
      swal({
          title: 'Are you sure?',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
          swal('Staff Activated successfully!', {
            icon: 'success',
          });
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>staffs/activeStaff/",
            data: ({
              staff_id: staff_id
            }),
            cache: false,
            success: function(html){
              $('#active_InactiveStaffProfile').modal('hide');
              $('#empTable').DataTable().ajax.reload();
            }
          });
        }
      });
    }else{
      swal('No selected staff found!', {
        icon: 'error',
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#deleteCustomStaffs', function()
  {
    staff_id=$(this).attr("value"); 
    swal({
      title: 'Are you sure?',
      text: 'Once deleted, you will not be able to recover this staff file!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })

    .then((willDelete) => {
      if (willDelete) {
        swal('Staff Deleted Successfully!', {
          icon: 'success',
        });
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>staffs/deleteStaff/",
          data: ({
            staff_id: staff_id
          }),
          cache: false,
          beforeSend: function() {
            $('.delete_staff' + staff_id).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
            );
          },
          success: function(html){
           $('.delete_staff' + staff_id).fadeOut('slow');
           $('#empTable').DataTable().ajax.reload();
          }
        });
      }
    });
    
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#resetPasswordCustomStaffs', function()
  {
    staff_id=$(this).attr("value");
    swal({
      title: 'Are you sure you want to generate new password for this staff?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })

    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>staffs/resetStaffPassword/",
          data: ({
            editedId: staff_id
          }),
          cache: false,
          beforeSend: function() {
            $('.resetPasswordInfo').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
            );
          },
          success: function(html){
           $('.resetPasswordInfo').html(html);
           $('#empTable').DataTable().ajax.reload();
          }
        });
      }
    });
    
  });
</script>
<script type="text/javascript">
  function calculateTotal() {
    var Net_Salary; 
    var gross_sallary;
    var taxableincome;
    var taxable_income=document.formemp.ti.value;
    var quality_allowance=document.formemp.ql.value;
    var transport_allowance=document.formemp.tl.value;
    var home_allowance=document.formemp.hl.value;
    var position_allowance=document.formemp.pl.value;
    var basic_sallary=document.formemp.gs.value;
    var T1=eval(basic_sallary) + eval(position_allowance);
    var T2=document.formemp.tl.value;
    gross_sallary=eval(quality_allowance) + eval(transport_allowance) + eval(home_allowance) + eval(position_allowance) + eval(basic_sallary);
    taxableincome=eval(basic_sallary) + eval(position_allowance);
    document.getElementById('gross_sallary').value = gross_sallary;
    document.getElementById('ti').value = taxableincome;
    var P;
    var IT;
    if(T1==0)
    {
      alert('Please Enter Basic Salary');
    }
    else if(T1<=600){
      Net_Salary= eval(T2) ;}
    else if(T1 <=1650 && T1 >=601){
      IT=(T1*(10/100))-60;
      P=T1*(7/100);
      P2=T1*(11/100);
      Net_Salary=eval(T1-IT-P);}
    else if(T1 <=3200 && T1 >=1651){
      IT=(T1*(15/100))-142.5;
      P=T1*(7/100);
      P2=T1*(11/100);
      Net_Salary=eval(T1-IT-P);}
    else if(T1 <=5250 && T1 >=3201){
      IT=(T1*(20/100))-302.5;
      P=T1*(7/100);
      P2=T1*(11/100);
      Net_Salary=eval(T1-IT-P);}
    else if(T1 <=7800 && T1 >=5251){
      IT=(T1*(25/100))-565;
      P=T1*(7/100);
      P2=T1*(11/100);
      Net_Salary=eval(T1-IT-P);}
    else if(T1 <=10900 && T1 >=7801){
      IT=(T1*(30/100))-955;
      P=T1*(7/100);
      P2=T1*(11/100);
      Net_Salary=eval(T1-IT-P);}
    else if(T1 >=10901){
      IT=(T1*(35/100))-1500;
      P=T1*(7/100);
      P2=T1*(11/100);
      Net_Salary=(T1-IT-P);
    }
    var gs_net_sallary=eval(Net_Salary) + eval(quality_allowance) + eval(transport_allowance) + eval(home_allowance);
      document.getElementById('tl').innerHTML = Net_Salary;
      document.getElementById('ns').value = gs_net_sallary;
      document.getElementById('income_tax').value = IT;
      document.getElementById('pension_7').value = P;
      document.getElementById('pension_11').value = P2;
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