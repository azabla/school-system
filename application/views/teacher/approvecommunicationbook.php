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
              <div class="col-12">
                <div class="grade_list_divapprove"> </div>
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
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      load_grade_to_appprovecommbook();
      function load_grade_to_appprovecommbook()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>Approvecommunicationbook/load_grade_to_appprovecommbook/",
          method:"POST",
          beforeSend: function() {
            $('.grade_list_divapprove').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success:function(data){
            $('.grade_list_divapprove').html(data);
          }
        })
      }
      $(document).on('click', '.backTo_MainPageApprove', function()
      {
        load_grade_to_appprovecommbook();
      });
    }); 
    $(document).on('click', '.startfetchingapproveCommBook_subject', function() {
      var academicyear=$(this).attr('id');
      var grade=$(this).attr('value');
      var branch=$(this).attr('name');
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Approvecommunicationbook/fetch_subject_of_thisGrade_toapprove/",
        data: ({
          academicyear:academicyear,
          grade:grade,
          branch: branch
        }),
        cache: false,
        beforeSend: function() {
          $('.grade_list_divapprove').html( '<h3><span class="text-success">Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa"></span></h3>' );
        },
        success: function(html){
          $('.grade_list_divapprove').html(html);
        }
      });
    });
    $(document).on('click', '.startapprovingCommBook_student', function() {
      var subject=$(this).attr('id');
      var grade=$(this).attr('value');
      var branch=$(this).attr('name');
      var year=$(this).attr('data-year');
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Approvecommunicationbook/fetch_comBookhistory_of_thisGrade_toapprove/",
        data: ({
          subject:subject,
          grade:grade,
          branch: branch,
          year:year
        }),
        cache: false,
        beforeSend: function() {
          $('.grade_list_divapprove').html( '<h3><span class="text-success">Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa"></span></h3>' );
        },
        success: function(html){
          $('.grade_list_divapprove').html(html);
        }
      });
    });
  </script>

  <script type="text/javascript">
    $(document).on('click', '.approvComBook', function(event) {
      event.preventDefault();
      var stuID=$(this).attr('value');
      swal({
          title: 'Are you sure you want to approve this communication book?',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Approvecommunicationbook/approvethis_CommunicationBook/",
            data: ({
              stuID: stuID
            }),
            cache: false,
            success: function(html){
              $('#approveThisComBook' + stuID).fadeOut('slow');
            }
          });
        }
      });
    }); 
    $(document).on('click', '.rejectThisComBook', function(event) {
      event.preventDefault();
      var stuID=$(this).attr('value');
      swal({
          title: 'Are you sure you want to Reject this communication book?',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Approvecommunicationbook/rejectCommunicationBook/",
            data: ({
              stuID: stuID
            }),
            cache: false,
            success: function(html){
              $('#approveThisComBook' + stuID).fadeOut('slow');
            }
          });
        }
      });
    });
    $(document).on('click', '.approvReplayComBook', function(event) {
      event.preventDefault();
      var stuID=$(this).attr('value');
      swal({
          title: 'Are you sure you want to approve this communication book?',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Approvecommunicationbook/approvethis_replayCommunicationBook/",
            data: ({
              stuID: stuID
            }),
            cache: false,
            success: function(html){
              $('#approverejectReplay' + stuID).fadeOut('slow');
            }
          });
        }
      });
    }); 
    $(document).on('click', '.rejectthis_replayCommunicationBook', function(event) {
      event.preventDefault();
      var stuID=$(this).attr('value');
      swal({
          title: 'Are you sure you want to Reject this communication book?',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Approvecommunicationbook/rejectCommunicationBook/",
            data: ({
              stuID: stuID
            }),
            cache: false,
            success: function(html){
              $('#approverejectReplay' + stuID).fadeOut('slow');
            }
          });
        }
      });
    });
</script>
</body>

</html>