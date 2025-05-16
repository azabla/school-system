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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
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
                      <div class="col-md-6 col-6">
                        <h4>Student rank report</h4>
                     </div>
                     <div class="col-md-6 col-6">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                        <span class="text-black">
                        <i data-feather="printer"></i>
                        </span>
                       </button>
                      </div>
                    </div>
                  </div>
                  <div class="card-header">
                    <form id="comment_form">
                     <div class="row">
                        <div class="col-md-4 col-6">
                          <div class="form-group">
                            <div class="row">
                            <?php if($_SESSION['usertype']===trim('Director')){
                            foreach($gradesec as $gradesecs){ ?>
                              <div class="col-md-4 col-6">
                                <div class="pretty p-icon p-jelly p-bigger">
                                  <input type="checkbox" name="rank_gradesec" value="<?php echo $gradesecs->grade; ?>" id="rank_gradesec">
                                  <div class="state p-success">
                                    <i class="icon material-icons"></i>
                                    <label></label><?php echo $gradesecs->grade; ?>
                                  </div>
                                </div>
                              </div>
                             <?php } } else { 
                              foreach($gradesecTeacher as $gradesecs){ ?> 
                                <div class="col-md-4 col-6">
                                  <div class="pretty p-icon p-jelly p-bigger">
                                    <input type="checkbox" name="rank_gradesec" value="<?php echo $gradesecs->grade; ?>" id="rank_gradesec">
                                    <div class="state p-success">
                                      <i class="icon material-icons"></i>
                                      <label></label><?php echo $gradesecs->grade; ?>
                                    </div>
                                  </div>
                                </div>
                             <?php }  } ?>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4 col-6">
                         <div class="form-group">
                          <div class="row">
                            <?php foreach($fetch_term as $fetch_term){ ?>
                              <div class="col-md-6 col-6">
                                <div class="pretty p-icon p-jelly p-bigger">
                                  <input type="checkbox" name="quarter" value="<?php echo $fetch_term->term; ?>" id="rank_quarter">
                                  <div class="state p-info">
                                    <i class="icon material-icons"></i>
                                    <label></label><?php echo $fetch_term->term; ?>
                                  </div>
                                </div>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                         </div>
                        <div class="col-md-2 col-6">
                          <div class="form-group">
                           <select class="form-control" required="required" name="rank_top" id="rank_top">
                           <option> --- Top --- </option>
                           <option> All </option>
                           <?php for($i=1;$i<=40;$i++){?>
                            <option><?php echo $i ?></option>
                           <?php } ?>
                           </select>
                          </div>
                        </div>
                       <div class="col-md-2 col-6">
                        <button class="btn btn-primary btn-block" type="submit" name="getrank">
                          Get Rank
                        </button>
                      </div>
                    </div>
                   </form>
                  <div class="ranklist" id="helloo"> </div>
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

</body>
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
    var print_div = document.getElementById("helloo");
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
   $('#comment_form').on('submit', function(event) {
      event.preventDefault();
      var top=$("#rank_top").val()
      quarter=[];gradesec=[];
      $("input[name='quarter']:checked").each(function(i){
        quarter[i]=$(this).val();
      });
      $("input[name='rank_gradesec']:checked").each(function(i){
        gradesec[i]=$(this).val();
      });
      if(gradesec.length!=0 && quarter.length!=0 && $("#rank_top").val()!='--- Top ---'){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Studentrankreport/fetchTopRank/",
        data: ({
          gradesec:gradesec,
          quarter:quarter,
          top:top
        }),
        beforeSend: function() {
          $('.ranklist').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".ranklist").html(data);
        }
      });
    }
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