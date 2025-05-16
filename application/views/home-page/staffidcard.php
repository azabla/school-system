
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
            <div class="card">
            <div class="card-body StudentViewTextInfo">
              <ul class="nav nav-tabs" id="myTab2" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#defaultIDCard" role="tab" aria-selected="true">Default ID Card</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="home-tab2" data-toggle="tab" href="#customIDCard" role="tab" aria-selected="false">Custom ID Card</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#backPage" role="tab" aria-selected="false">Back Page</a>
                  </li>
              </ul>
              <div class="tab-content tab-bordered" id="myTab3Content">
                <div class="tab-pane fade show active" id="defaultIDCard" role="tabpanel" aria-labelledby="home-tab1">
                  <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                    <span class="text-black">
                    <i data-feather="printer"></i>
                    </span>
                  </button>
                  <form id="comment_form">
                    <div class="row">
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required"  name="reportaca" id="reportaca">
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                              <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="branch" id="branch">
                            <option> --- Select Branch --- </option>
                            <?php foreach($branch as $branchs){ ?>
                            <option value="<?php echo $branchs->name;?>">
                              <?php echo $branchs->name;?>
                            </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-12">
                        <button class="btn btn-info btn-block" type="submit" name="gethisroster"> View ID</button>
                      </div>
                    </div>
                  </form>
                  <div class="listStaffID" id="helloStaffId"> </div>
                </div>
                
                <div class="tab-pane fade show" id="customIDCard" role="tabpanel" aria-labelledby="home-tab2">
                  <div class="row">
                    <div class="col-lg-6 col-6">
                    </div>
                    <div class="col-lg-6 col-6">
                      <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyCustom()">
                        <i data-feather="printer"></i>
                      </button>
                    </div>
                  </div>
                  <div class="row"> 
                    <div class="col-lg-6 col-12">
                      <div class="card-header">
                        <input type="text" class="form-control typeahead" id="searchStudentForTransportPlace" name="searchStudentForTransportPlace" placeholder="Search Student Id,Name">
                        <div class="table-responsive" style="height:15vh;">
                          <div class="searchPlaceHere"></div> 
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-12">
                      <textarea class="form-control" id="selectStudentForTransportPlace" name="selectStudentForTransportPlace" col="12">  </textarea>
                      <button class="btn btn-default RemoveAll" id="removeAll" type="submit"><i class="fas fa-angle-double-left"></i></button>
                    </div> 
                    <div class="col-lg-6 col-12">
                      <button type ="submit" class="btn btn-info btn-block" id="fetchCustomIDCard" name="fetchCustomIDCard" >View ID Card</button>

                    </div>
                  </div>
                  <div class="fetchCustomIDCardHere" id="helloStuIDCardCustom"></div>
                </div>
                <div class="tab-pane fade show" id="backPage" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="row">
                      <div class="col-lg-6 col-6">
                      </div>
                      <div class="col-lg-6 col-6">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="backPagePrint()">
                          <i data-feather="printer"></i>
                        </button>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-4 col-6">
                        <div class="form-group">
                          <select class="form-control" required="required" name="branchBack" id="branchIDBack">
                            <option> --- Branch --- </option>
                            <?php foreach($branch as $branchs){ ?>
                            <option value="<?php echo $branchs->name;?>">
                              <?php echo $branchs->name;?>
                            </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-6">
                        <input type="number" name="pageNumber" id="pageNumber" class="form-control">
                      </div>
                      <div class="col-lg-4 col-12">
                        <button type="button" class="btn btn-info btn-block" id="submitBackPage">View Card</button>
                      </div>
                    </div>
                    <div class="fetchBackIDCardHere" id="fetchBackIDCardHere"></div>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script type="text/javascript">
    $(document).on('click', '#submitBackPage', function() {
      event.preventDefault();
      var pageNumber=$('#pageNumber').val();
      var branchIDBack=$('#branchIDBack').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>staffidcard/fetchBackIdCard/",
        data: ({
          pageNumber: pageNumber,
          branchIDBack:branchIDBack
        }),
        beforeSend: function() {
          $('#fetchBackIDCardHere').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $("#fetchBackIDCardHere").html(data);
        }
      });
    });
  </script>
  <script type="text/javascript">
  function codespeedyCustom(){
    var print_div = document.getElementById("helloStuIDCardCustom");
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
    $(document).ready(function() { 
      $('#searchStudentForTransportPlace').on("keyup",function() {
        $searchItem=$('#searchStudentForTransportPlace').val();
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>staffidcard/searchStudentsToTransportService/",
          data: "searchItem=" + $("#searchStudentForTransportPlace").val(),
          beforeSend: function() {
            $('.searchPlaceHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $(".searchPlaceHere").html(data);
          }
        });
      });
    });
    $(document).on('click', '.saveThisStudentToGroupEdit_Staffs', function() {
      event.preventDefault();
      var oldText=$('#selectStudentForTransportPlace').val();
      var stuID=$(this).attr("value");
      var newText=oldText+stuID+"\n";
      $("#selectStudentForTransportPlace").val(newText);   
    });
    $(document).on('click', '#removeAll', function() {
      event.preventDefault();
      $("#selectStudentForTransportPlace").val('');   
    });
    $(document).on('click', '#fetchCustomIDCard', function() {
      event.preventDefault();
      var newServicePlace=$('#selectStudentForTransportPlace').val();
      var stuIdArray=newServicePlace.split(/(\s+)/);
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>staffidcard/fetchCustomIDCard/",
        data: ({
          stuIdArray: stuIdArray
        }),
        beforeSend: function() {
          $('.fetchCustomIDCardHere').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".fetchCustomIDCardHere").html(data);
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
      $("body").removeClass("dark");
      $("body").removeClass("dark-sidebar");
      $("body").removeClass("theme-black");
      $("body").addClass("light");
      $("body").addClass("light-sidebar");
      $("body").addClass("theme-white");
    } else {
      $("body").removeClass("light");
      $("body").removeClass("light-sidebar");
      $("body").removeClass("theme-white");
      $("body").addClass("dark");
      $("body").addClass("dark-sidebar");
      $("body").addClass("theme-black");
    }
  });
</script>
<script type="text/javascript" language="javascript"> 
  var bgcolor_now=document.getElementById("bgcolor_now").value;
  if (bgcolor_now == "1") {
    $("body").removeClass("dark");
    $("body").removeClass("dark-sidebar");
    $("body").removeClass("theme-black");
    $("body").addClass("light");
    $("body").addClass("light-sidebar");
    $("body").addClass("theme-white");
  }else {
    $("body").removeClass("light");
    $("body").removeClass("light-sidebar");
    $("body").removeClass("theme-white");
    $("body").addClass("dark");
    $("body").addClass("dark-sidebar");
    $("body").addClass("theme-black"); 
  } 
</script>  
  <script type="text/javascript">
   $('#comment_form').on('submit', function(event) {
    event.preventDefault();
      var branch=$("#branch").val();
      if($("#branch").val()!=''){
      var form_data = $(this).serialize();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>staffidcard/fetch_staff_idcard/",
        data: form_data,
        beforeSend: function() {
          $('.listStaffID').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
            );
        },
        success: function(data) {
          $(".listStaffID").html(data);
        }
      });
    }
  });
</script>
 <script type="text/javascript">
  function backPagePrint(){
    var print_div = document.getElementById("fetchBackIDCardHere");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  function codespeedy(){
    var print_div = document.getElementById("helloStaffId");
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