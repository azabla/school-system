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
            <div class="row">
              <div class="col-12">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>"> 
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#manualPlacement" role="tab" aria-selected="true">Manual Placement</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#automaticPlacement" role="tab" aria-selected="false">Automatic Placement</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="manualPlacement" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row">
                         <div class="col-lg-12 col-12">
                          <button class="btn btn-outline-default pull-right" name="gethisreport" onclick="codespeedyManual()">
                            <span class="text-black">
                            <i data-feather="printer"></i>
                            </span>
                          </button>
                         </div>
                        </div>
                        <form method="POST" id="comment_formManual">
                          <div class="row">
                            <div class="col-lg-4 col-6">
                              <label for="Mobile">Select Grade</label>
                              <select class="form-control selectric" required="required" name="grade2placeManual" id="grade2placeManual">
                                <option> --- Select Grade --- </option>
                                <?php foreach($gradesec as $gradesecs){ ?>
                                  <option value="<?php echo $gradesecs->grade;?>">
                                   <?php echo $gradesecs->grade;?>
                                  </option>
                                <?php } ?>
                              </select>
                            </div>
                            <div class="col-lg-4 col-6">
                              <label for="Mobile">No Of Section</label>
                              <select class="form-control selectric" required="required" name="intoManual" id="intoManual">
                                <option></option>
                                <?php for($i=1;$i<=20;$i++) { ?>
                                 <option value="<?php echo $i;?>">
                                  <?php echo $i; ?>
                                 </option>
                                <?php  } ?>
                              </select>
                            </div>
                            <div class="col-lg-4 col-12">
                              <label for="Mobile"></label>
                                <button type="submit" class="btn btn-primary btn-block btn-lg" name="goplace">Show</button>
                            </div>
                          </div>
                        </form>
                        <div class="listManualPlacement" id="helloManualPlacement"> </div>
                      </div>
                      <div class="tab-pane fade show" id="automaticPlacement" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row">
                          <div class="col-lg-12 col-12">
                            <button class="btn btn-outline-default pull-right" name="gethisreport" onclick="codespeedy()">
                              <i data-feather="printer"></i>
                            </button>
                          </div>
                        </div>
                        <a class="infofound"></a>
                        <form method="POST" id="comment_form">
                          <div class="row">
                            <div class="col-lg-4 col-6">
                              <label for="Mobile">Select Grade</label>
                              <div class="form-group">
                                <select class="form-control grade2place" required="required" name="grade2place" id="grade2place">
                                 <option> --- Select Grade --- </option>
                                <?php 
                                  foreach($gradesec as $gradesecs){ ?>
                                    <option value="<?php echo $gradesecs->grade;?>">
                                     <?php echo $gradesecs->grade;?>
                                    </option>
                                  <?php  }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-4 col-6">
                              <div class="row">
                                <div class="col-lg-10">
                                  <label for="Mobile">No. of Section</label>
                                  <div class="form-group">
                                    <select class="form-control selectric"
                                     required="required" name="into" id="into">
                                     <option></option>
                                    <?php for($i=1;$i<=20;$i++) { ?>
                                     <option value="<?php echo $i;?>">
                                      <?php echo $i; ?>
                                     </option>
                                    <?php  } ?>
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4 col-12">
                              <label for="Mobile"></label>
                              <button type="submit" class="btn btn-primary btn-block btn-lg" name="goplace">Place</button>
                            </div>
                          </div>
                        </form>
                        <div class="listAuto" id="helloAuto"> </div>
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
  $(document).on('click', '.placesiec', function() {
    var stu_id=$(this).attr("id");
    var section_id=$(this).attr("value");
    var grade=$('.grades').val();
    $.ajax({
      url: "<?php echo base_url(); ?>studentplacement/insertsection/",
      method: "POST",
      data: ({
        stu_id: stu_id,
        section_id: section_id,
        grade: grade
      }),
      beforeSend: function() {
        $('.saved').html( '<img src="<?php echo base_url() ?>loader/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      dataType:"json",
      success: function(data) {
        $('.saved' + stu_id + section_id).html(data.notification);
      }
    });
  });
</script>
<script type="text/javascript">
  $('#comment_formManual').on('submit', function(event) {
    event.preventDefault();
    var grade2place=$('#grade2placeManual').val();
    var into=$('#intoManual').val();
    if ($('#grade2place').val() != '') {
      var form_data = $(this).serialize();
      $.ajax({
        url: "<?php echo base_url(); ?>studentplacement/filter_grade4placement/",
        method: "POST",
        data: form_data,
        beforeSend: function() {
          $('.listManualPlacement').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
            );
        },
        success: function(data) {
          /*$('#comment_form')[0].reset();*/
          $(".listManualPlacement").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
</script>
<script type="text/javascript">
  function codespeedyManual(){
    var print_div = document.getElementById("helloManualPlacement");
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
  $('#grade2place').on('change', function(event) {
    var grade2place=$('#grade2place').val();
    $.ajax({
      url: "<?php echo base_url(); ?>Studentplacement/checkPlacementFound/",
      method: "POST",
      data: ({
        grade2place: grade2place
      }),
      beforeSend: function() {
        $('.infofound').html( 'Checking placement...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
        success: function(data) {
        $(".infofound").html(data);
      }
    })
  });
</script>

<script type="text/javascript">
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var grade2place=$('#grade2place').val();
    var into=$('#into').val();
    swal({
      title:'Are you sure you want to place automatically for grade ' +grade2place+' ?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        if ($('#grade2place').val() != '') {
          var form_data = $(this).serialize();
          $.ajax({
            url: "<?php echo base_url(); ?>Studentplacement/filterGrade4AutoPlacement/",
            method: "POST",
            data: form_data,
            beforeSend: function() {
              $('.listAuto').html( 'Placing...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
            },
            success: function(data) {
              /*$("#comment_form")[0].reset();*/
              $(".listAuto").html(data);
            }
          })
        }else {
          swal('Oooops, Please select necessary fields!', {
            icon: 'warning',
          });
        }
      }
    });
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("helloAuto");
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