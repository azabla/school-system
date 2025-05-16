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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
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
              <div class="col-12 col-md-12 col-lg-8">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#about" role="tab" aria-selected="true">About</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="profile-tab2" data-toggle="tab" href="#settings" role="tab" aria-selected="false">Profile</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="signature-tab" data-toggle="tab" href="#signatureTab" role="tab" aria-selected="false">Signature</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="password-tab2" data-toggle="tab" href="#passwordd" role="tab" aria-selected="false">Password</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="2factor-tab2" data-toggle="tab" href="#Two-factor" role="tab" aria-selected="false">2-F Authentication</a>
                      </li>
                    </ul>
                    <?php foreach($sessionuser as $sessionusers){?>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2">
                        <p class="m-t-30">
                          <?php echo $sessionusers->biography; ?>
                        </p>
                      </div>
                      
                      <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="profile-tab2">
                      <form id="myProfile" method="POST">
                          <div class="card-header">
                            <h4>Edit Profile</h4>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="form-group col-lg-4 col-6">
                                <label>First Name</label>
                                <input type="text" name="profileFname" class="form-control fname"
                                value="<?php echo $sessionusers->fname ; ?>">
                                <div class="invalid-feedback">
                                  Please fill in the first name
                                </div>
                              </div>
                              <div class="form-group col-lg-4 col-6">
                                <label>Father Name</label>
                                <input type="text" name="profileMname" class="form-control mname" 
                                value="<?php  echo $sessionusers->mname  ; ?>">
                                <div class="invalid-feedback">
                                  Please fill in the last name
                                </div>
                              </div>
                              <div class="form-group col-lg-4 col-6">
                                <label>G.Father Name</label>
                                <input type="text" name="profileLname" class="form-control lname"
                                value="<?php  echo $sessionusers->lname  ; ?>">
                                <div class="invalid-feedback">
                                  Please fill in the last name
                                </div>
                              </div>
                              <div class="form-group col-lg-4 col-6">
                                <label>Profile Photo</label>
                                 <input type="file"  class="form-control"
                                name="profilePhoto">
                                <div class="invalid-feedback">
                                  Please fill in the profile photo
                                </div>
                              </div>
                              <div class="form-group col-lg-4 col-6">
                                <label>Email</label>
                                <input type="email" class="form-control email" name="profileEmail"
                                 value="<?php  echo $sessionusers->email  ; ?>">
                                <div class="invalid-feedback">
                                  Please fill in the email
                                </div>
                              </div>
                              <div class="form-group col-lg-4 col-6">
                                <label>Phone</label>
                                <input type="tel" class="form-control mobile" name="profileMobile"
                                value="<?php  echo $sessionusers->mobile  ; ?>">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12 col-12">
                                <label>Biography</label>
                                <textarea name="profileBio"
                                  class="form-control summernote-simple bio">
                                  <?php  echo $sessionusers->biography ; ?>
                                </textarea>
                              </div>
                            </div>    
                          </div>
                          <div class="card-footer text-right">
                            <button class="btn btn-primary btn-block" type="submit" name="changeprofile" id="update_my_profile_gs">Save Changes</button>
                          </div>
                        </form>
                      </div>
                      <div class="tab-pane fade show" id="signatureTab" role="tabpanel" aria-labelledby="signature-tab">
                        <div class="signatureTabInfo">
                          <?php echo '<img alt="image" src="'.base_url().'/'.$sessionusers->mysign.'" class="rounded-circle">' ?>
                        </div>
                          <div id="signature-pad">
                            <div style="border:solid 1px teal; width:360px;height:110px;padding:3px;position:relative;">
                              <div id="note" onmouseover="my_function();">The signature should be inside box</div>
                              <canvas id="the_canvas" width="350px" height="100px"></canvas>
                            </div>
                            <div style="margin:10px;">
                              <input type="hidden" id="signature" name="signature">
                              <button type="button" id="clear_btn" class="btn btn-danger" data-action="clear"><span class="glyphicon glyphicon-remove">Clear</span> </button>
                              <button type="submit" id="save_btn" class="btn btn-primary" data-action="save-png"><span class="glyphicon glyphicon-ok">Save as Signature</span> </button>
                            </div>
                          </div>
                        </div>
                      <div class="tab-pane fade show" id="passwordd" role="tabpanel" aria-labelledby="password-tab2">
                          <small class="text-danger">Please type minimum 8 password digits including chars,numbers and different letter cases.</small>
                          <form method="POST" id="changePassword">
                          <div class="row">
                              <div class="form-group col-lg-6 col-md-6 col-12">
                                <label>Old Password</label>                                
                                <div class="chat-box">
                                  <div class="card-footer chat-fhorm">
                                    <input type="password" class="form-control" required="required" id="oldPassword" name="password1">
                                    <a href="#" class="btn btn-default btn-sm text-center" id="eyeplaceOld" onclick="myFunction()"><i class="fas fa-eye"></i> Show Password </a> 
                                  </div>
                                </div>
                              </div>
                              <div class="form-group col-lg-6 col-md-6 col-12">
                                <label>New Password</label>
                                <div class="chat-box">
                                  <div class="card-footer chat-fhorm">
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
                        </form>
                      </div>
                      <div class="tab-pane fade show" id="Two-factor" role="tabpanel" aria-labelledby="2factor-tab2">
                        <div class="card-header">
                          <h4>Two-Factor Authentication</h4>
                        </div>
                        <div class="two_factor_authentication_page"> </div>
                      </div>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php foreach($sessionuser as $sessionusers){?>
              <div class="col-12 col-md-12 col-lg-4">
                <div class="card author-box">
                  <div class="card-body StudentViewTextInfo">
                    <div class="author-box-center">
                      <img alt="image" src="<?php echo base_url(); ?>/profile/<?php echo $sessionusers->profile;?>" class="border-circle-4profile">
                      <div class="clearfix"></div>
                      <div class="author-box-name">
                        <a href="#">
                          <?php echo $sessionusers->fname;
                           echo '&nbsp';
                          echo $sessionusers->mname ; ?>
                        </a>
                      </div>
                    </div>
                    <div class="text-center">
                      <div class="author-box-description">
                        <p><?php $sessionusers->usertype ;?>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <h4>Personal Details</h4>
                  </div>
                  <div class="card-body StudentViewTextInfo">
                    <div class="py-4">
                      <p class="clearfix">
                        <span class="float-left">
                          Gender
                        </span>
                        <span class="float-right text-muted">
                         <?php  echo $sessionusers->gender ; ?>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Birthday
                        </span>
                        <span class="float-right text-muted">
                         <?php  echo $sessionusers->dob ; ?>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Phone
                        </span>
                        <span class="float-right text-muted">
                          <?php  echo $sessionusers->mobile ; ?>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Mail
                        </span>
                        <span class="float-right text-muted">
                           <?php  echo $sessionusers->email; ?>
                        </span>
                      </p>
                      <p class="clearfix">
                        <span class="float-left">
                          Status
                        </span>
                        <span class="float-right text-muted">
                           <?php  echo $sessionusers->status ; ?>
                        </span>
                      </p>
                     <p class="clearfix">
                        <span class="float-left">
                          City
                        </span>
                        <span class="float-right text-muted">
                          <?php  echo $sessionusers->city; ?>
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            <?php }?>
            </div>
          </div>
        </section>
      </div>
     <?php include('footer.php'); ?>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/summernote/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/signature.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  function checkPasswordStrength() {
    var number = /([0-9])/;
    var alphabets = /([a-zA-Z])/;
    var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
    var password = $('#newPassword').val().trim();
    if (password.length < 8) {
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
  $(document).on('click', "input[name='twoFactorAuthenticatio']", function() {
    var twofactor=$(this).attr("value");
    if($(this).is(':checked')){
      $.ajax({
        url:"<?php echo base_url() ?>myprofile/enable_two_factor_authentication/",
        method:"POST",
        data:({
          twofactor:twofactor
        }),
        success: function(data){
          if(data==='0'){
            iziToast.error({
              title: 'Please try again.',
              message: '',
              position: 'topRight'
            });
          }else{
            iziToast.success({
              title: 'Two-Factor Authentication enabled.',
              message: '',
              position: 'topRight'
            });
          }
        }
      });
    }else{
      var twofactor=$(this).attr("value");
      $.ajax({
        url:"<?php echo base_url() ?>myprofile/disable_two_factor_authentication/",
        method:"POST",
        data:({
           twofactor:twofactor
        }),
        success: function(){
          iziToast.success({
            title: 'Two-Factor Authentication disabled',
            message: '',
            position: 'topRight'
          });
        }
      });
    }
  });
  $(document).ready(function(){
    on_off_registration_page();
    function on_off_registration_page() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>myprofile/two_factor_authentication/',
        cache: false,
        beforeSend: function() {
          $('.two_factor_authentication_page').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(html){
         $('.two_factor_authentication_page').html(html);
        }
      })
    }
  });
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
<script>
var wrapper = document.getElementById("signature-pad");
var clearButton = wrapper.querySelector("[data-action=clear]");
var savePNGButton = wrapper.querySelector("[data-action=save-png]");
var canvas = wrapper.querySelector("canvas");
var el_note = document.getElementById("note");
var signaturePad;
signaturePad = new SignaturePad(canvas);

clearButton.addEventListener("click", function (event) {
  document.getElementById("note").innerHTML="The signature should be inside box";
  signaturePad.clear();
});
savePNGButton.addEventListener("click", function (event){
  event.preventDefault();
  if (signaturePad.isEmpty()){
    swal('Please provide signature first.', {
      icon: 'error',
    });
  }else{
    var canvas  = document.getElementById("the_canvas");
    var dataUrl = canvas.toDataURL();
    document.getElementById("signature").value = dataUrl;
     $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>myprofile/updateSignature/",
      data: ({
        dataUrl:dataUrl
      }),
      beforeSend: function() {
        $('.signatureTabInfo').html( '<span class="text-info">Updating...</span>');
        $('#save_btn').attr( 'disabled','disabled');
        $('#save_btn').html( 'Updating....');
      },
      success: function(html){
        $(".signatureTabInfo").html(html);
        $('#save_btn').removeAttr('disabled');
        $('#save_btn').html('Save as Signature');
      }
    });
  }
});
function my_function(){
  document.getElementById("note").innerHTML="";
}
</script>
<script type="text/javascript">
  $(document).on('submit', '#myProfile', function(e) {
    e.preventDefault();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>myprofile/updateMyProfile/",
      data:new FormData(this),
      processData:false,
      contentType:false,
      cache: false,
      async:false,
      beforeSend: function() {
        $('#update_my_profile_gs').attr('disabled','disabled');
        $('#update_my_profile_gs').html('Saving...');
      },
      success: function(html){
        $('#update_my_profile_gs').removeAttr('disabled');
        $('#update_my_profile_gs').html('Save Changes');
        iziToast.success({
          title: html,
          message: '',
          position: 'topRight'
        });
      }
    });
  });
  $('#changePassword').on('submit', function(event) {
    event.preventDefault();
    var oldPassword=$('#oldPassword').val();
    var newPassword=$('#newPassword').val();
    if( $('#newPassword').val() !=='' || $('#oldPassword').val() !=='')
    {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>myprofile/changePassword/",
        data: ({
          oldPassword: oldPassword,
          newPassword:newPassword
        }),
        cache: false,
        beforeSend: function() {
          $('#save_my_password').attr( 'disabled','disabled');
          $('#save_my_password').html( 'Updating....');
        },
        success: function(html){
          if(html=='1'){
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
          $('#save_my_password').html( 'Save Changes');
        }
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