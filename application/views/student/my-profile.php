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
              <div class="col-12 col-md-12 col-lg-12">
                <div class="row">
                <div class="card card-body">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <!-- <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                          aria-selected="true">About</a>
                      </li> -->
                      <li class="nav-item">
                        <a class="nav-link active" id="profile-tab2" data-toggle="tab" href="#settings" role="tab"
                          aria-selected="false">Profile</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="password-tab2" data-toggle="tab" href="#password" role="tab"
                          aria-selected="false">Password</a>
                      </li>
                    </ul>
                    <?php foreach($sessionuser as $sessionusers){?>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <!-- <div class="tab-pane fade " id="about" role="tabpanel" aria-labelledby="home-tab2">
                        <p class="m-t-30">
                          <?php echo $sessionusers->biography; ?>
                        </p>
                      </div> -->
                      
                      <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="profile-tab2">
                      <?php echo form_open_multipart('viewmyprofile/');?>
                          <div class="card-body">
                            <div class="support-ticket media">
                              <img alt="image" src="<?php echo base_url(); ?>/profile/<?php echo $sessionusers->profile;?>" class="user-img" width="100">
                            </div>
                            <div class="row">
                              <div class="form-group col-lg-4">
                                <label>First Name</label>
                                <input type="text" class="form-control fname" disabled="disabled"
                                value="<?php echo $sessionusers->fname ; ?>">
                                <div class="invalid-feedback">
                                  Please fill in the first name
                                </div>
                              </div>
                              <div class="form-group col-lg-4">
                                <label>Father Name</label>
                                <input type="text" class="form-control lname" disabled="disabled"
                                value="<?php  echo $sessionusers->mname  ; ?>">
                                <div class="invalid-feedback">
                                  Please fill in the last name
                                </div>
                              </div>
                              <div class="form-group col-lg-4">
                                <label>G.Father Name</label>
                                <input type="text" class="form-control lname" disabled="disabled"
                                value="<?php  echo $sessionusers->lname  ; ?>">
                                <div class="invalid-feedback">
                                  Please fill in the last name
                                </div>
                              </div>
                              <div class="form-group col-lg-4">
                                <label>Profile Photo</label>
                                 <input type="file" class="form-control"
                                name="profilephoto" disabled>
                                <div class="invalid-feedback">
                                  Please fill in the profile photo
                                </div>
                              </div>
                              <div class="form-group col-lg-4">
                                <label>Email</label>
                                <input type="email" class="form-control email" name="email"
                                 value="<?php  echo $sessionusers->email  ; ?>" disabled>
                                <div class="invalid-feedback">
                                  Please fill in the email
                                </div>
                              </div>
                              <div class="form-group col-lg-4">
                                <label>Phone</label>
                                <input type="tel" class="form-control mobile" name="mobile"
                                value="<?php  echo $sessionusers->mobile  ; ?>" disabled>
                              </div>
                              <div class="form-group col-lg-4">
                                <label>Gender</label>
                                <input type="tel" class="form-control mobile" name="mobile"
                                value="<?php  echo $sessionusers->gender  ; ?>" disabled>
                              </div>
                              <div class="form-group col-lg-4">
                                <label>Birth Date</label>
                                <input type="tel" class="form-control mobile" name="mobile"
                                value="<?php  echo $sessionusers->dob  ; ?>" disabled>
                              </div>
                              <div class="form-group col-lg-4">
                                <label>Status</label>
                                <input type="tel" class="form-control mobile" name="mobile"
                                value="<?php  echo $sessionusers->status  ; ?>" disabled>
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-lg-12">
                                <!-- <label>Biography</label>
                                <textarea name="biography" 
                                  class="form-control summernote-simple bio" disabled="disabled">
                                  <?php  echo $sessionusers->biography ; ?>
                                </textarea> -->
                              </div>
                            </div>    
                          </div>
                          <div class="card-footer text-right">
                            <!-- <button class="btn btn-primary" name="changeprofile">Save Changes</button> -->
                          </div>
                        </form>
                      </div>
                    
                      <div class="tab-pane fade show" id="password" role="tabpanel" aria-labelledby="password-tab2">
                          <small class="text-danger">Please type minimum 6 password digits including chars,numbers and different letter cases.</small>
                          <form method="POST" id="changePassword">
                          <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-12">
                                <label>Old Password</label>                                
                                <div class="chat-box">
                                  <div class="card-footer chat-forsm">
                                    <input type="password" class="form-control" required="required" id="oldPassword" name="password1">
                                    <a href="#" class="btn btn-default btn-sm text-center" id="eyeplaceOld" onclick="myFunction()"><i class="fas fa-eye"></i> Show Password</a> 
                                  </div>
                                </div>
                              </div>
                              <div class="form-group col-lg-6 col-md-6 col-12">
                                <label>New Password</label>
                                <div class="chat-box">
                                  <div class="card-footer chat-fosrm">
                                    <input type="password" required="required" class="form-control" id="newPassword" name="password2" onkeyup="checkPasswordStrength();">
                                    <a href="#" class="btn btn-default btn-sm text-center" id="eyeplaceNew" onclick="myFunction2()"><i class="fas fa-eye"></i> Show Password </a> 
                                  </div>
                                  <div id="password-strength-status"></div>
                                </div>
                              </div>
                            </div>
                            <div class="card-footer text-right">
                            <button class="btn btn-primary btn-block" id="save_my_password" type="submit" disabled="disabled">
                             Save Changes
                            </button>
                          </div>
                          <input type="hidden" class="txt_csrfname_student_profile" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>"><br>   
                        </form>
                      </div>
                    </div>
                    <?php } ?>
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
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  function checkPasswordStrength() {
    var number = /([0-9])/;
    var alphabets = /([a-zA-Z])/;
    var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
    var password = $('#newPassword').val().trim();
    if (password.length < 6) {
      $('#password-strength-status').removeClass();
      $('#password-strength-status').addClass('weak-password');
      $('#password-strength-status').html("<small class='text-danger'>Weak (should be atleast 6 characters.)</small>");
      $('#save_my_password').attr( 'disabled','disabled');
    } else {
      if (password.match(number) && password.match(alphabets) && password.match(special_characters)) {
        $('#password-strength-status').removeClass();
        $('#password-strength-status').addClass('strong-password');
        $('#password-strength-status').html("<small class='text-success'>Strong</small>");
        $('#save_my_password').removeAttr( 'disabled');
      }
      else {
        $('#password-strength-status').removeClass();
        $('#password-strength-status').addClass('medium-password');
        $('#password-strength-status').html("<small class='text-warning'>Medium (should include alphabets, numbers and special characters.)</small>");
        $('#save_my_password').attr( 'disabled','disabled');
      }
    }
  }
  function myFunction() {
    var x = document.getElementById("oldPassword");
    if (x.type === "password") {
      x.type = "text";
      $('#eyeplaceOld').html('<i class="fas fa-eye-slash"></i>  Hide Password');
    } else {
      x.type = "password";
      $('#eyeplaceOld').html('<i class="fas fa-eye"></i> Show Password');
    }
  } 
  function myFunction2() {
    var x = document.getElementById("newPassword");
    if (x.type === "password") {
      x.type = "text";
      $('#eyeplaceNew').html('<i class="fas fa-eye-slash"></i>  Hide Password');
    } else {
      x.type = "password";
      $('#eyeplaceNew').html('<i class="fas fa-eye"></i> Show Password');
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
    $('#changePassword').on('submit', function(event) {
    event.preventDefault();
    var csrfName = $('.txt_csrfname_student_profile').attr('name'); // Value specified in $config['csrf_token_name']
    var csrfHash = $('.txt_csrfname_student_profile').val(); // CSRF hash
    var oldPassword=escape($('#oldPassword').val());
    var newPassword=escape($('#newPassword').val());
      if( $('#newPassword').val() =='' || $('#oldPassword').val() =='')
      {
        alert("Oooops, Please fill all fields.");
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>viewmyprofile/changePassword/",
        data: ({
          oldPassword: oldPassword,
          newPassword:newPassword,
          [csrfName]:csrfHash
        }),
        cache: false,
        dataType:'json',
        beforeSend: function() {
          $('#save_my_password').attr( 'disabled','disabled');
          $('#save_my_password').html( 'Updating....');
        },
        success: function(html){
          if(html.response=='1'){
            $('#changePassword')[0].reset();
            $('#password-strength-status').html("");
            $('#save_my_password').attr( 'disabled','disabled');
            iziToast.success({
              title: 'Password changed successfully.',
              message: '',
              position: 'topRight'
            });
          }else{
            iziToast.error({
              title: 'Ooops old password not correct.',
              message: '',
              position: 'topRight'
            });
             $('#save_my_password').removeAttr( 'disabled'); 
          }
          $('.txt_csrfname_student_profile').val(html.token);
          $('#save_my_password').html( 'Save Changes');
        }
      });
    }
  });
  
  </script>
</html>