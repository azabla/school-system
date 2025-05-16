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
                <div class="row">
                  <div class="col-lg-12 col-12">
                    <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                    <span class="text-black">
                      <i data-feather="printer"></i>
                    </span>
                    </button>
                  </div>
                </div>
                <div class="card-body StudentViewTextInfo">
                <form method="POST" id="NgMarkForm">
                  <div class="row">
                    <div class="col-lg-6 col-4 table-responsive" style="height:15vh">
                      <div class="row">
                      <?php foreach($gre as $gradesecs){ ?>
                        <div class="col-lg-3 col-12">
                          <input type="checkbox" name="gradesec2ShowNgmark" value="<?php echo $gradesecs->grade;?>" class="gradesec2ShowNgmark" id="customCheck1"><?php echo $gradesecs->grade;?>
                        </div>
                      <?php }?>
                      </div>
                    </div>
                    <div class="col-lg-6 col-8 table-responsive" style="height:15vh">
                      <div class="grands_subject " id="grands_subject" ></div>
                    </div>
                    <div class="col-lg-6 col-6">
                      <div class="form-group">
                        <select class="form-control selectric" required="required" name="quarter" 
                        id="grands_quarter">
                          <option>--- Select Quarter ---</option>
                          <?php foreach($fetch_term as $fetch_terms){ ?>
                            <option value="<?php echo $fetch_terms->term;?>">
                            <?php echo $fetch_terms->term;?>
                            </option>
                          <?php }?>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-6 col-6">
                      <button class="btn btn-primary btn-block btn-lg" 
                        type="submit" name="viewmark">View
                      </button>
                    </div>
                  </div>
                </form>
                 <div class="nullmarkHere" id="nullmarkHere"></div>
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
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script type="text/javascript">
    function selectAllSubjectList(){
        var itemsall=document.getElementById('subjectToShowNgMarkHere');
        if(itemsall.checked==true){
        var items=document.getElementsByName('subjectToShowNgMark');
        for(var i=0;i < items.length;i++){
          items[i].checked=true;
        }
      }
        else{
        var items=document.getElementsByName('subjectToShowNgMark');
        for(var i=0;i < items.length;i++){
          items[i].checked=false;
        }
      }
    }
  </script>
</body>
<script type="text/javascript">
  $(document).on('click', '.gradesec2ShowNgmark', function() {
    grade2analysis=[];
    $("input[name='gradesec2ShowNgmark']:checked").each(function(i){
      grade2analysis[i]=$(this).val();
    });
    var branch2analysis=$("#grands_branchit").val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Myngmark/filterSubjectToNGMarkShow/",
      data:({
        branch2analysis:branch2analysis,
        grade2analysis:grade2analysis
      }),
      beforeSend: function() {
        $('.grands_subject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      success: function(data) {
        $(".grands_subject").html(data);
      }
    });
  });
  $(document).on('click', '.gradesec2ShowNgmark', function() {
    $("input[name='gradesec2ShowNgmark[ ]']:checked").each(function(i){
      grade2analysis[i]=$(this).val();
    });
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Myngmark/filterQuarterToNGMarkShow/",
      data:({
        grade2analysis:grade2analysis
      }),
      beforeSend: function() {
        $('#grands_quarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      success: function(data) {
        $("#grands_quarter").html(data);
      }
    });
  });
</script>
<script type="text/javascript">
  $('#NgMarkForm').on('submit', function(event) {
    event.preventDefault();
    gs_gradesec=[];gs_subject=[];
    $("input[name='gradesec2ShowNgmark']:checked").each(function(i){
      gs_gradesec[i]=$(this).val();
    });
    $("input[name='subjectToShowNgMark']:checked").each(function(i){
      gs_subject[i]=$(this).val();
    });
    var gs_quarter=$('#grands_quarter').val();
    if (gs_gradesec.length==0 || gs_subject.length==0) {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }else{
      $.ajax({
        url: "<?php echo base_url(); ?>Myngmark/fetchNullMark/",
        method: "POST",
        data: ({
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject,
          gs_quarter:gs_quarter
        }),
        beforeSend: function() {
          $('.nullmarkHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".nullmarkHere").html(data);
        }
      })
    }
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("nullmarkHere");
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