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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel='shortcut icon' type='image/x-icon'
   href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
</head>

<body>
  <div class="loader"> <div class="loaderIcon"></div></div>
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
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#copyBS" role="tab" aria-selected="true"> Copy Basic Skill</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#moveBS" role="tab" aria-selected="false">Move Basic Skill</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="copyBS" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row">
                          <div class="col-lg-2 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="academicyear" id="academicyear">
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
                             <select class="form-control selectric" required="required" name="branchbs" id="mybranch">
                             <option>--- Select Branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                          </div>
                          <div class="col-lg-2 col-4">
                            <div class="form-group">
                             <select class="form-control" required="required" name="gradesecbs" id="gradesec">
                             </select>
                            </div>
                          </div>
                          <div class="col-lg-5 col-8 table-responsive" style="height:15vh;">
                            <div class="form-group" id="studentID">
                            </div>
                          </div>
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="fromquarter" id="fromquarter">
                                <option>--- From Quarter ---</option>
                                <?php foreach($fetch_term as $fetch_terms){ ?>
                                  <option value="<?php echo $fetch_terms->term;?>">
                                  <?php echo $fetch_terms->term;?>
                                  </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="toquarter" id="toquarter">
                                <option>--- To Quarter ---</option>
                                <?php foreach($fetch_term as $fetch_terms){ ?>
                                  <option value="<?php echo $fetch_terms->term;?>">
                                  <?php echo $fetch_terms->term;?>
                                  </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-4 col-12">
                            <button class="btn btn-primary btn-block" type="submit" id="copybsicSkillNow">Copy Bsic Skill</button>
                            <p class="copybsicSkillNowAlet"></p>
                          </div>
                        </div>
                        
                      </div>
                      <div class="tab-pane fade show" id="moveBS" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row">
                          <div class="col-lg-2 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="academicyear" id="moveacademicyear">
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
                             <select class="form-control selectric" required="required" name="branchbs" id="movemybranch">
                             <option>--- Select Branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                          </div>
                          <div class="col-lg-2 col-4">
                            <div class="form-group">
                             <select class="form-control" required="required" name="gradesecbs" id="movegradesec">
                             </select>
                            </div>
                          </div>
                          <div class="col-lg-5 col-8 table-responsive" style="height:15vh;">
                            <div class="form-group" id="movestudentID">
                            </div>
                          </div>
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="fromquarter" id="movefromquarter">
                                <option>--- From Quarter ---</option>
                                <?php foreach($fetch_term as $fetch_terms){ ?>
                                  <option value="<?php echo $fetch_terms->term;?>">
                                  <?php echo $fetch_terms->term;?>
                                  </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                              <select class="form-control selectric" required="required" name="toquarter" id="movetoquarter">
                                <option>--- To Quarter ---</option>
                                <?php foreach($fetch_term as $fetch_terms){ ?>
                                  <option value="<?php echo $fetch_terms->term;?>">
                                  <?php echo $fetch_terms->term;?>
                                  </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-4 col-12">
                            <button class="btn btn-primary btn-block" type="submit" id="movebsicSkillNow">Move Bsic Skill</button>
                            <p class="movebsicSkillNowAlet"></p>
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
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
  <script type="text/javascript">
    $(document).on('click', '#movebsicSkillNow', function() {
      swal({
        title: 'Are you sure you want to move selected student basic skill?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          studentList=[];
          $("input[name='selectAllStudentMoveCopyBSkill[ ]']:checked").each(function(i){
            studentList[i]=$(this).val();
          });
          var gradesec=$("#movegradesec").val();
          var branch=$("#movemybranch").val();
          var academicyear=$("#moveacademicyear").val();
          var toquarter=$("#movetoquarter").val();
          var fromquarter=$("#movefromquarter").val();
          if(studentList.length!=0 || $("#movegradesec").val()!=''){
            $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>movecopybasicskill/movebasicskill/",
               data: ({
                studentList:studentList,
                gradesec:gradesec,
                branch:branch,
                academicyear:academicyear,
                toquarter:toquarter,
                fromquarter:fromquarter
              }),
              beforeSend: function() {
                $('.movebsicSkillNowAlet').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
                  );
              },
              success: function(data) {
                $(".movebsicSkillNowAlet").html(data);
              }
            });
          }else{
            swal('All fields are required!', {
              icon: 'error',
            });
          }
        }
      });
    });
  </script>
  <script type="text/javascript">
    $(document).on('click', '#copybsicSkillNow', function() {
      swal({
        title: 'Are you sure you want to copy selected student basic skill?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          studentList=[];
          $("input[name='selectAllStudentMoveCopyBS[ ]']:checked").each(function(i){
            studentList[i]=$(this).val();
          });
          var gradesec=$("#gradesec").val();
          var branch=$("#mybranch").val();
          var academicyear=$("#academicyear").val();
          var toquarter=$("#toquarter").val();
          var fromquarter=$("#fromquarter").val();
          if(studentList.length!=0 || $("#gradesec").val()!=''){
            $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>movecopybasicskill/copybasicskill/",
               data: ({
                studentList:studentList,
                gradesec:gradesec,
                branch:branch,
                academicyear:academicyear,
                toquarter:toquarter,
                fromquarter:fromquarter
              }),
              beforeSend: function() {
                $('.copybsicSkillNowAlet').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
                  );
              },
              success: function(data) {
                $(".copybsicSkillNowAlet").html(data);
              }
            });
          }else{
            swal('All fields are required!', {
              icon: 'error',
            });
          }
        }
      });
    });
  </script>
  <script type="text/javascript">
    function selectAllStudentMoveCopyBS(){
        var itemsall=document.getElementById('selectAllStudentMoveCopyBSGS');
        if(itemsall.checked==true){
        var items=document.getElementsByName('selectAllStudentMoveCopyBS[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=true;
        }
      }
        else{
        var items=document.getElementsByName('selectAllStudentMoveCopyBS[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=false;
        }
      }
    }
  </script>
  <script type="text/javascript">
    function selectAllStudentMoveCopyBSkill(){
        var itemsall=document.getElementById('selectAllStudentMoveCopyBSGSJ');
        if(itemsall.checked==true){
        var items=document.getElementsByName('selectAllStudentMoveCopyBSkill[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=true;
        }
      }
        else{
        var items=document.getElementsByName('selectAllStudentMoveCopyBSkill[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=false;
        }
      }
    }
  </script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#gradesec").bind("change", function() {
      var gradesecs=$("#gradesec").val();
      var branch=$("#mybranch").val();
      var academicyear=$("#academicyear").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>movecopybasicskill/fetchThisGradeStudent/",
        data: ({
          gradesecs: gradesecs,
          academicyear:academicyear,
          branch:branch
        }),
        beforeSend: function() {
          $('#studentID').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#studentID").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#movegradesec").bind("change", function() {
      var gradesecs=$("#movegradesec").val();
      var branch=$("#movemybranch").val();
      var academicyear=$("#moveacademicyear").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>movecopybasicskill/fetchThisGradeStudentMove/",
        data: ({
          gradesecs: gradesecs,
          academicyear:academicyear,
          branch:branch
        }),
        beforeSend: function() {
          $('#movestudentID').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#movestudentID").html(data);
        }
      });
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
<script type="text/javascript">
  $(document).ready(function() {  
    $("#mybranch").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#mybranch").val(),
        beforeSend: function() {
          $('#gradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradesec").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#movemybranch").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#movemybranch").val(),
        beforeSend: function() {
          $('#movegradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#movegradesec").html(data);
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
</html>