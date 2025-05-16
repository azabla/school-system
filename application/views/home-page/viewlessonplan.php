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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/summernote/summernote-bs4.css">
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
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-10 col-lg-12">
                <div class="card">
                  <div class="inbox-center">
                    <div class="card-header">
                      <h5 class="header-title">View Lesson Plan</h5>
                    </div>   
                  </div>
                </div>
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="row">
                  <?php foreach($lessonplan as $lessonplans){ $id=$lessonplans->id; ?>
                  <div class="col-12 col-md-3 col-lg-3 delete_lessonplan<?php echo $id ?>">
                    <article class="article article-style-c">
                      <div class="article-details">
                        <div class="article-category">
                          <h5 class="header-title">Grade: <?php echo $lessonplans->grade; ?> </h5>
                          <h5 class="header-title">Subject: <?php echo $lessonplans->subject; ?>
                          <div class="dropdown-divider"></div>
                            <small class="text-muted">
                              <?php if($lessonplans->postby == $_SESSION['username'] || $_SESSION['usertype']=='superAdmin' ){ ?>
                                <button class="btn btn-default deletelessonplan" name="deletelessonplan" type="submit" id="<?php echo $lessonplans->id; ?>">
                                  <span class="text-danger"><i class="fas fa-trash"></i></span>
                                </button>
                                
                                <button class="btn btn-default editlessonplan" name="editlessonplan" type="submit" id="<?php echo $lessonplans->id; ?>">
                                  <a href="#" class="editsubject" value="" data-toggle="modal" data-target="#viewLesonPlanModal">
                                  <span class="text-info"><i class="fas fa-pen"></i></span></a>
                                </button> 
                                 <button class="btn btn-default viewlessonplan" name="viewlessonplan" type="submit" id="<?php echo $lessonplans->id; ?>">
                                  <a href="#" class="editsubject" value="" data-toggle="modal" data-target="#printLesonPlanModal">
                                  <span class="text-warning"><i class="fas fa-eye"></i></span></a>
                                </button>
                              <?php } ?>
                            </small>
                          </h5>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="article-user">
                          <img alt="image" class="border-circle" src="<?php echo base_url(); ?>/profile/<?php echo $lessonplans->profile;?>">
                          <div class="article-user-details">
                            <a href="#"><?php echo $lessonplans->fname;echo ' '; echo $lessonplans->mname;?></a>
                            <small class="text-muted pull-right"> <?php echo $lessonplans->dateposted; ?></small>
                          </div>
                        </div>
                      </div>
                    </article>
                  </div>
                 <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </section>

        <div class="modal fade" id="viewLesonPlanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Lesson Plan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="lessonplanEditHere"> </div>
              </div>
              <a id="updateLessonPlaninfo"></a>
              <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="printLesonPlanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">
                  <button class="btn btn-default printlessonplan" onclick="codespeedy()" name="printlessonplan" type="submit" id="">
                      <span class="text-warning">Print <i class="fas fa-print"></i></span>
                  </button></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="lessonplanPrintHere"> </div>
              </div>
            </div>
          </div>
        </div>

      </div>
       <footer class="main-footer">
        <div class="footer-left">
         Call:+251967829025 &nbsp;&nbsp;
          Copyright &copy <?php echo date('Y');?>
          <a href="https://www.grandstande.com" target="_blanck">Grandstand IT Solution Plc</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/summernote/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script type="text/javascript">
    function codespeedy(){
      var print_div = document.getElementById("printLessonPlanGs");
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
    $('#lesson_plan').on('submit', function(event) {
      event.preventDefault();
      var form_data = $(this).serialize();
      $.ajax({
        url: "<?php echo base_url(); ?>addlessonplan/savelessonplan/",
        method: "POST",
        data: form_data,
        beforeSend: function() {
          $('.savelessonplaninfo').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $('#lesson_plan')[0].reset();
          $(".savelessonplaninfo").html(data);
        }
      })
    });
  </script>
  <script type="text/javascript">
    $(document).on('click', '.deletelessonplan', function() {
      var deletelessonplanid = $(this).attr("id");
      if (confirm("Are you sure you want to delete this Lesson plan ?")) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>viewlessonplan/deleteViewLessonPlan/",
          data: ({
            deletelessonplanid: deletelessonplanid
          }),
          cache: false,
          success: function(html) {
            $(".delete_lessonplan" + deletelessonplanid).fadeOut('slow');
          }
        });
      }else {
        return false;
      }
    });
    $(document).on('click', '.editlessonplan', function() {
      var editlessonplan = $(this).attr("id");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>viewlessonplan/EditLessonPlan/",
        data: ({
          editlessonplan: editlessonplan
        }),
        cache: false,
        success: function(html) {
          $(".lessonplanEditHere").html(html);
        }
      });
    });
    $(document).on('click', '#saveChanges', function() {
      var lesson_objective = $('#lesson_objective_update').val();
      var teachers_guide = $('#teachers_guide_update').val();
      var students_guide = $('#students_guide_update').val();
      var materials_needed = $('#materials_needed_update').val();
      var lessonPlanId=$('#lessonPlanId').val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>viewlessonplan/updateLessonPlan/",
        data: ({
          lessonPlanId:lessonPlanId,
          lesson_objective: lesson_objective,
          teachers_guide:teachers_guide,
          students_guide:students_guide,
          materials_needed:materials_needed
        }),
        cache: false,
        beforeSend: function() {
          $('#updateLessonPlaninfo').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(html) {
          $("#updateLessonPlaninfo").html(html);
        }
      });
    });
    $(document).on('click', '.viewlessonplan', function() {
      var viewlessonplan = $(this).attr("id");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>viewlessonplan/viewLessonPlanHere/",
        data: ({
          viewlessonplan: viewlessonplan
        }),
        cache: false,
        success: function(html) {
          $(".lessonplanPrintHere").html(html);
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
  <script type="text/javascript">
    $(document).ready(function() {
      $("#lesson_grade").change(function() {
        var gradesec=$("#lesson_grade").val();
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Filter_grade_subject/",
          data: {gradesec:gradesec} ,
          success: function(data) {
            $("#lesson_subject").html(data);
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
</body>

</html>