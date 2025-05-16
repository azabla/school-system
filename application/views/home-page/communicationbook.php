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
 <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
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
              <div class="col-lg-12 col-md-12 col-12">
                <a class="btn btn-info btn-sm btn-action pull-right" id="" value="" name="" type="submit" data-toggle="modal" data-target="#addCustomText"><i class="fas fa-plus"></i> Add custom text</a>
              </div>
            </div>
            <form method="GET" id="view_subject_form">
              <div class="row">
                <div class="col-lg-3 col-md-3 col-6">
                  <div class="form-group">
                    <select class="form-control selectric" required="required" name="academicyear"  id="grands_academicyear">
                      <option>--Year--</option>
                    <?php foreach($academicyear as $academicyears){ ?>
                      <option value="<?php echo $academicyears->year_name;?>">
                        <?php echo $academicyears->year_name;?>
                      </option>
                    <?php }?>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6">
                  <div class="form-group">
                    <select class="form-control" required="required" name="branch"
                    id="grands_branchit">
                      <option>--- Branch ---</option>
                        <?php foreach($branch as $branchs){ ?>
                        <option value="<?php echo $branchs->name;?>">
                          <?php echo $branchs->name;?>
                        </option>
                        <?php }?>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6">
                  <div class="form-group">
                    <select class="form-control grands_gradesec" required="required" name="gradesec" id="grands_gradesec">
                      <option>--- Grade ---</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-6">
                  <button class="btn btn-primary btn-lg btn-block" 
                    type="submit" name="viewmark">View Subject
                  </button>
                </div>
              </div>
              <div class="fetch_data_div"></div>        
            </form>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="addCustomText" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="copy_exam_page" id="copy_exam_page">Add Custom Text</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 col-lg-12 col-12">
              <input type="text" name="customTextName" id="customTextName" class="form-control" placeholder="Custom text here...">
            </div>
            <div class="col-md-12 col-lg-12 col-12">
              <button class="btn btn-primary pull-right saveCustomText" id="saveCustomText">Save Text</button>
            </div>
          </div>
          <div class="fetchCustomTextHere"></div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script type="text/javascript">
    $(document).on('click', '.startfetchingCommBook_student', function() {
      var subject=$(this).attr('id');
      var grade=$(this).attr('value');
      var branch=$(this).attr('name');
      var year=$(this).attr('data-year');
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>communicationbook/fetch_comBookhistory_of_thisGrade/",
        data: ({
          subject:subject,
          grade:grade,
          branch: branch,
          year:year
        }),
        cache: false,
        beforeSend: function() {
          $('.fetch_data_div').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.fetch_data_div').html(html);
        }
      });
    });
    $('#view_subject_form').on('submit', function(event) {
      event.preventDefault();
      var gs_branches=$('#grands_branchit').val();
      var gs_gradesec=$('.grands_gradesec').val();
      var academicyear=$('#grands_academicyear').val();
      if ($('.grands_gradesec').val() !='--- Grade ---') {
        $.ajax({
          url: "<?php echo base_url(); ?>communicationbook/fecth_subject/",
          method: "GET",
          data: ({
            gs_branches: gs_branches,
            gs_gradesec:gs_gradesec,
            academicyear:academicyear
          }),
          beforeSend: function() {
            $('.fetch_data_div').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $(".fetch_data_div").html(data);
          }
        })
      }else {
        swal('Oooops, Please select necessary fields!', {
          icon: 'warning',
        });
      }
    });
    $(document).ready(function() {  
      $("#grands_academicyear").bind("change", function() {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>communicationbook/filterGradesecfromBranch/",
          data: "academicyear=" + $("#grands_academicyear").val(),
          beforeSend: function() {
            $('#grands_branchit').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $("#grands_branchit").html(data);
          }
        });
      });
    });
    $("#grands_branchit").bind("change", function() {
      var branchit=$("#grands_branchit").val();
      var academicyear=$("#grands_academicyear").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>communicationbook/fetch_gradesec_frombranch_markresult/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('.grands_gradesec').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".grands_gradesec").html(data);
        }
      });
    });
    $(document).ready(function(){
      loadCustomData();
      function loadCustomData()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>communicationbook/fetchCustomText/",
          method:"POST",
          beforeSend: function() {
            $('.fetchCustomTextHere').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success:function(data){
            $('.fetchCustomTextHere').html(data);
          }
        })
      }
      $('#saveCustomText').on('click', function(event) {
        event.preventDefault();
        var customTextName=$('#customTextName').val();

        if($('#customTextName').val() =='')
        {
          swal({
            title: 'Oooops, Please select necessary fields.',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
        }else{
          $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>communicationbook/postCustomText/",
          data: ({
            customTextName: customTextName
          }),
          cache: false,
          success: function(html){
            $('#customTextName').val('');
            loadCustomData();
          }
        });
      }
    });
    $(document).on('click', '.deleteCustomText', function() {
      var textId = $(this).attr("id");
       swal({
          title: 'Are you sure you want to delete this text ?',
          text: '',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>communicationbook/deleteCustomText/",
            data: ({
              textId: textId
            }),
            cache: false,
            success: function(html) {
              loadCustomData();
            }
          });
        }
      });
    });
  });
  </script>
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
  
</body>

</html>