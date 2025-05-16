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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
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
                <div class="col-lg-12 col-12">
                  <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                  <span class="text-black">
                    <i data-feather="printer"></i>
                  </span>
                  </button>
                </div>
              </div>
              <form method="POST" id="fetchBs">
                <div class="row">
                  <div class="col-lg-4 col-6">
                    <div class="form-group">
                      <select class="form-control bsgradesec" required="required"
                       name="bsgradesec" id="bsgradesec">
                        <option>--- Grade ---</option>
                        <?php if($_SESSION['usertype']===trim('Director')){
                          foreach($gradesec as $gradesecs){ ?>
                            <option value="<?php echo $gradesecs->gradesec;?>">
                            <?php echo $gradesecs->gradesec;?>
                            </option>
                          <?php } } else{
                            foreach($gradesecs as $gradesecss){ ?>
                            <option value="<?php echo $gradesecss->roomgrade;?>">
                            <?php echo $gradesecss->roomgrade;?>
                            </option> 
                          <?php } }?>

                        <!--  -->
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-6">
                    <div class="form-group">
                      <select class="form-control selectric" required="required" 
                      name="bsquarter" id="bsquarter">
                        <option>--- Select Quarter ---</option>
                        <?php foreach($fetch_term as $fetch_terms){ ?>
                          <option value="<?php echo $fetch_terms->term;?>">
                          <?php echo $fetch_terms->term;?>
                          </option>
                        <?php }?>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <button class="btn btn-primary btn-block btn-lg" 
                      type="submit" name="viewmark">View
                    </button>
                  </div>
                </div>
              </form> 
              <div class="listbs table-responsive" id="listbs" style="height:45vh"></div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <footer class="main-footer">
        <div class="footer-left">
         Call:+251967829025 &nbsp;&nbsp;
          Copyright &copy<?php echo date('Y');?>
          <a href="https://www.grandstande.com" target="_blanck">GrandStand IT Solution Plc</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
  $(document).on('change', '.insertResultCommentTypeo', function() {
    var value=$(this).find('option:selected').attr('value');
    var quarter=$('#resultCommentQuarter').val();
    var bsGradesec=$('#resultCommentGradesec').val();
    var stuid=$(this).find('option:selected').attr('name');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>studentreportcardcomments/updatestudentresultcomment/",
        data: ({
          value:value,
          stuid:stuid,
          quarter:quarter,
          bsGradesec:bsGradesec
        }),
        success: function(data) {
          iziToast.success({
            title: 'Comment Value',
            message: 'Updated successfully',
            position: 'topRight'
          });
        }
    });
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("listbs");
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
<!-- Fecth mark script starts -->
<script type="text/javascript">
  $('#fetchBs').on('submit', function(event) {
    event.preventDefault();
    var branches=$('#bsbranch').val();
    var gradesec=$('.bsgradesec').val();
    var quarter=$('#bsquarter').val();
    if ($('.bsgradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>studentreportcardcomments/fecthStudentBs/",
        method: "POST",
        data: ({
          branches: branches,
          gradesec:gradesec,
          quarter:quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listbs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(data) {
          $(".listbs").html(data);
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