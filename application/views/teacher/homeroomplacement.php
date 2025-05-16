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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
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
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#feedSubject" role="tab" aria-selected="true">New H.Room Placement</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#feedMergedSubject" role="tab" aria-selected="false">View Placement</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="feedSubject" role="tabpanel" aria-labelledby="home-tab1">
                        <form id="save_placement" method="POST">
                          <div class="row">
                            <div class="form-group col-lg-2 col-6">
                            <div class="form-group">
                              <label for="Mobile">Academic Year</label>
                              <select class="form-control selectric" required="required" name="academicyear" id="placaeacademicyear">
                              <?php foreach($academicyear as $academicyears){ ?>
                                <option><?php echo $academicyears->year_name ?></option>
                              <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-5 col-6">
                             <div class="form-group">
                              <label for="subject">Select Teacher </label>
                             <select class="form-control selectric" required="required" name="subject" id="hoomroomteacher">
                             <option></option>
                             <?php foreach($teachers as $teacher){ ?>
                              <option value="<?php echo $teacher->username ?>">
                              <?php echo $teacher->username ?> &nbsp;( <?php echo $teacher->fname ?>&nbsp;<?php echo $teacher->mname ?>)</option>
                             <?php } ?>
                            </select>
                           </div>
                          </div>
                           <div class="col-lg-5 col-6 table-responsive" style="height: 25vh;">
                             <div class="form-group">
                              <label for="subject">Select grade to assign </label><br>
                                <div class="row">
                                  <?php foreach($gradesec as $gradesecs){ ?>
                                    <div class="col-lg-4 col-6">
                                      <div class="pretty p-bigger">
                                        <input type="checkbox" name="grade" value="<?php echo $gradesecs->gradesec;?>" class="gradeplacement" id="customCheck1">
                                        <div class="state p-info">
                                          <i class="icon material-icons"></i>
                                          <label></label><?php echo $gradesecs->gradesec; ?>
                                        </div>
                                      </div>
                                       
                                      <div class="dropdown-divider2"></div>
                                    </div>
                                  <?php } ?>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-12 col-12">
                              <div class="form-group">
                                <button type="submit" name="postplacement" class="btn btn-primary pull-right ">Save changes
                                </button>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="tab-pane fade show" id="feedMergedSubject" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="fetch_placement"></div>
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
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Myhomeroomplacement/fetchHomeroomPlacement/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_placement').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.fetch_placement').html(data);
        }
      })
    }
    $('#save_placement').on('submit', function(event) {
      event.preventDefault();
      var academicyear=$('#placaeacademicyear').val();
      var teacher=$('#hoomroomteacher').val();
      id=[];
      $("input[name='grade']:checked").each(function(i){
        id[i]=$(this).val();
      });
      if(id.length!='0'){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Myhomeroomplacement/postHomeroomPlacement/",
          data: ({
            id: id,
            academicyear:academicyear,
            teacher:teacher
          }),
          cache: false,
          success: function(html){
            $('#save_placement')[0].reset();
            load_data();
            swal({
              title: 'Staff assigned successfully.',
              text: '',
              icon: 'success',
              buttons: true,
              dangerMode: true,
            })
          }
        });
      }else{
        swal({
          title: 'Please select all necessary fields.',
          text: '',
          icon: 'error',
          buttons: true,
          dangerMode: true,
        })
      }
    });
    $(document).on('click', '#delete_hoomroomplacemet', function()
    {
      var staff_placement=$(this).attr("value");
      swal({
        title: 'Are you susre you want to delete this Staff Placement?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Myhomeroomplacement/deleteHomeroomPlacement/",
            data: ({
              staff_placement: staff_placement
            }),
            cache: false,
            beforeSend: function() {
              $('.fetch_placement').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
            },
            success: function(html){
              load_data();
            }
          });
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

</html>