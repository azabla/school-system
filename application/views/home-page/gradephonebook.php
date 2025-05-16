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
                  <div class="card-header">
                    <div class="row"> 
                      <div class="col-lg-4 col-4">
                        <h4>Grade Phone List</h4>
                     </div>
                      <div class="col-lg-8 col-8">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                          <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>
                        </button>
                        <form method="POST" action="<?php echo base_url(); ?>gradephonebook/downloadStuData/">
                          <button type="submit" id="downloadStuData" name="downloadStuData" class="btn btn-success btn-sm pull-right"> Excel Data <i data-feather="download"></i>
                          </button>
                        </form>
                      </div>
                    </div>
                    <div class="card-body">
                      <form id="fetch_phone" method="POST">
                       <div class="row">
                        <div class="col-md-3 col-6">
                         <div class="form-group">
                           <select class="form-control selectric" required="required" name="academicyear" 
                           id="grands_academicyear">
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
                             <select class="form-control selectric"
                             required="required" name="branch" id="gs_branch">
                             <option> --- Select Branch --- </option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                           </div>
                           <div class="col-lg-3 col-6">
                            <div class="form-group">
                             <select class="form-control selectric"
                             required="required" name="gradesec" id="gs_gradesec">
                             <option> --- Select Grade --- </option>
                              <?php foreach($gradesec as $gradesecs){ ?>
                                <option value="<?php echo $gradesecs->grade;?>">
                                <?php echo $gradesecs->grade;?>
                                </option>
                              <?php }?>
                             </select>
                            </div>
                           </div>
                           <div class="col-lg-2 col-6">
                            <button class="btn btn-primary btn-block btn-lg" name="gethisreport"> View
                           </button>
                         </div>
                      </div>
                    </form>
                    <div class="listHere table-responsive" id="helloHere" style="height:45vh"></div>
                   </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <footer class="main-footer">
        <div class="footer-left">
         Call:+251967829025 &nbsp;&nbsp;
          Copyright &copy<?php echo date('Y');?>
          <a href="https://www.grandstande.com" target="_blanck">Grandstand IT Solution Plc</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
</body>
<script type="text/javascript">
  $("#downloadStuData").click(function(e) {
    let file = new Blob([$('.listHere').html()], {type:"application/vnd.ms-excel"});
    let url = URL.createObjectURL(file);
    let a = $("<a />", {
    href: url,
    download: "Grade-Phonebook.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
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
  function codespeedy(){
    var print_div = document.getElementById("helloHere");
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
  $('#fetch_phone').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$("#gs_gradesec").val();
    var branch=$("#gs_branch").val();
    var grands_academicyear=$("#grands_academicyear").val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Gradephonebook/Fetch_gradephone/",
      data:({
        gradesec: gradesec,
        branch: branch,
        grands_academicyear:grands_academicyear
      }),
      beforeSend: function() {
        $('.listHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
      },
      success: function(data) {
        $(".listHere").html(data);
      }
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