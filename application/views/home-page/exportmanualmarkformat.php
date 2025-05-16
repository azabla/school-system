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
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6"> </div>
                <div class="col-md-6">
                  <button type="submit" id="downloadStuData" name="downloadStuData" class="btn btn-success btn-sm pull-right"> Excel Data <i data-feather="download"></i> </button>
                  <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                  <span class="text-black">
                    <i data-feather="printer"></i>
                  </span>
                  </button>
                </div>
              </div>
              <form method="POST" id="comment_form">
                <div class="row">
                    <div class="col-lg-3 col-6">
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
                  <div class="col-lg-3 col-6">
                    <div class="form-group">
                      <select class="form-control grands_gradesec" required="required" name="gradesec" id="grands_gradesec">
                        <option>--- Grade ---</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="form-group">
                      <select class="form-control selectric" required="required" name="quarter" 
                      id="grands_quarter">
                      <option>--- Select Quarter ---</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-2 col-6">
                    <button class="btn btn-primary btn-lg btn-block" 
                      type="submit" name="viewmark">View
                    </button>
                  </div>
                </div>
              </form> 
              <div class="table-responsive listmark" id="mark_view" style="height:40vh"></div>
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
  <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js" integrity="sha512-w3u9q/DeneCSwUDjhiMNibTRh/1i/gScBVp2imNVAMCt6cUHIw6xzhzcPFIaL3Q1EbI2l+nu17q2aLJJLo4ZYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> 
</body>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_gradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filtertermfromgradegs/",
        data: "gradeit=" + $("#grands_gradesec").val(),
        beforeSend: function() {
          $('#grands_quarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_quarter").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function () {
      $("#downloadStuData").click(function(){
        TableToExcel.convert(document.getElementById("mark_view"), {
            name: "Mark-format.xlsx",
            sheet: {
            name: "Sheet1"
            }
          });
        });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_branchit").bind("change", function() {
      var branchit=$('#grands_branchit').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Exportmanualmarkformat/filterOnlyGradeFromBranch/",
        data: ({
            branchit: branchit
        }),
        beforeSend: function() {
          $('.grands_gradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".grands_gradesec").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("mark_view");
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
<!-- Grade change script starts-->
<!-- Grade change script ends -->

<!-- Fecth mark script starts -->
<script type="text/javascript">
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var gs_branches=$('#grands_branchit').val();
    var gs_gradesec=$('.grands_gradesec').val();
    var gs_quarter=$('#grands_quarter').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Exportmanualmarkformat/fecthMarkresult/",
        method: "POST",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          gs_quarter:gs_quarter
        }),
        beforeSend: function() {
          $('.listmark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(data) {
          $(".listmark").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<!-- Fetch mark ends -->
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