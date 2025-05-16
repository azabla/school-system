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
              <div class="col-12 col-md-12">
                <div class="alert alert-light alert-dismissible show fade">
                  <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                    Note:This page will fetch data after report card table has been loaded.
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#feedMergedSubject" role="tab" aria-selected="true">Internal Use</a>
                      </li>
                       <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#forEducationbeauro" role="tab" aria-selected="false">ለት/ት ቢሮ የሚላክ (In All Branch)</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="feedMergedSubject" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                        <div class="card">
                          <div class="card-header">
                            <div id="plist" class="people-list">
                              <div class="m-b-20">
                                <form id="comment_formGrade">
                                  <div class="row">
                                    <div class="col-lg-7 col-6">
                                      <div class="form-group">
                                       <select class="form-control selectric" required="required" name="branch_statisticsGrade" id="branchitGrade">
                                       <option>---Branch --- </option>
                                        <?php foreach($branch as $branchs){ ?>
                                          <option value="<?php echo $branchs->name;?>">
                                          <?php echo $branchs->name;?>
                                          </option>
                                        <?php }?>
                                       </select>
                                      </div>
                                    </div>
                                    <div class="col-lg-5 col-6">
                                      <div class="form-group">
                                        <select class="form-control" required="required" name="grade_statisticsGrade" id="grade_statisticsGrade">
                                          <option>-Grade- </option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-lg-12 col-6 table-responsive" id="subject_statisticsHereGrade" style="height:35vh">
                                    </div>
                                    
                                    <div class="col-lg-12 col-6">
                                      <hr>
                                      <div class="row">
                                        <?php foreach($fetch_termGrade as $fetch_terms){ ?>
                                        <div class="col-md-6 col-12">
                                          <div class="form-group">
                                            <div class="pretty p-icon p-jelly p-round p-bigger">
                                              <input type="checkbox" name="quarter_statisticsGrade[ ]" value="<?php echo $fetch_terms->term; ?>" id="customCheck1 quarter_statisticsGrade">
                                              <div class="state p-info">
                                                <i class="icon material-icons"></i>
                                                <label></label>
                                              </div>
                                            </div><?php echo $fetch_terms->term; ?>
                                          </div>
                                        </div>
                                        <?php }?>
                                      </div>
                                    </div>
                                    <div class="col-lg-5 col-5">
                                      <div class="form-group">
                                       <select class="form-control" required="required" name="less_thanGrade" id="less_thanGrade">
                                        <?php for($i=100;$i>=1;$i--){ ?>
                                          <option value="<?php echo $i ?>"><small><= </small> <?php echo $i;?></option>
                                        <?php } ?>
                                       </select>
                                      </div>
                                    </div>
                                    <div class="col-lg-5 col-5">
                                      <div class="form-group">
                                       <select class="form-control" required="required" name="greater_thanGrade" id="greater_thanGrade">
                                        <?php for($i=1;$i<=100;$i++){ ?>
                                          <option value="<?php echo $i ?>"><small>>= </small> <?php echo $i;?></option>
                                        <?php } ?>
                                       </select>
                                      </div>
                                    </div>
                                    <div class="col-lg-2 col-2">
                                      <button class="btn btn-default text-info" id="saveCriteria"><i class="fas fa-plus-circle"></i></button>
                                    </div>
                                    <div class="col-lg-12 col-12">
                                      <div class="listHereGs"></div>
                                    </div>
                                    <div class="col-lg-12 col-12">
                                      <input type="checkbox" name="includeName" id="includeName"> Include Name
                                      <button class="btn btn-primary btn-block" type="submit" name="getrank"> View </button>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <div class="chat">
                          <div class="chat-about">
                            <div class="row"> 
                              <div class="col-md-6 col-6"> </div>
                              <div class="col-md-6 col-6">
                                <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyGrade()">
                                  <span class="text-black">
                                  <i data-feather="printer"></i>
                                  </span>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="listReportGrade" id="helloReportGrade">  </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="forEducationbeauro" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                        <div class="card">
                          <div class="card-header">
                            <form id="comment_form" class="">
                               <div class="row">
                                  <div class="col-lg-12 col-12 table-responsive" style="height:20vh">
                                    <div class="row">
                                      <?php foreach($grade as $grades){ ?>
                                        <div class="col-lg-4 col-3">
                                        <div class="pretty p-icon p-jelly p-round p-bigger">
                                         <input class="grade_statistics" id="grade_statistics" type="checkbox" name="grade_statistics" value="<?php echo $grades->grade; ?>">
                                         <div class="state p-info">
                                            <i class="icon material-icons"></i>
                                            <label></label>
                                         </div>
                                         </div>
                                          <?php echo $grades->grade; ?>
                                        </div>
                                      <?php } ?>
                                    </div>

                                  </div>
                                  <div class="col-lg-12 col-12 table-responsive" id="subject_statisticsHere" style="height:25vh">
                                    
                                  </div>
                                  <div class="col-lg-12 col-12">
                                    <hr>
                                    <div class="form-group">
                                      <select class="custom-select" id="quarter_statistics">
                                        <option selected>Select Season:</option>
                                        <option value="semester1">1<sup>st</sup> Semester</option>
                                        <option value="semester2">2<sup>nd</sup> Semester</option>
                                        <option value="yearlyAverage">Yearly Average</option>
                                      </select>
                                    </div>
                                  </div> 
                                  <div class="col-lg-12 col-12">
                                    <button class="btn btn-primary btn-block" type="submit" name="getrank"> View </button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                          <div class="chat">
                            <div class="chat-about">
                              <div class="row"> 
                                <div class="col-md-6 col-6"> </div>
                                <div class="col-md-6 col-6">
                                  <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                                    <span class="text-black">
                                    <i data-feather="printer"></i>
                                    </span>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        <div class="listReport" id="helloReport">  </div>
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
  $(document).on('click', '.grade_statistics', function() {
    gradesec=[];
    $("input[name='grade_statistics']:checked").each(function(i){
      gradesec[i]=$(this).val();
    });
    if($(".grade_statistics").val()!=''){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Markstatistics/fetch_subject_from_gradeFilter/",
         data: ({
          gradesec: gradesec
        }),
        beforeSend: function() {
          $('#subject_statisticsHere').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $("#subject_statisticsHere").html(data);
        }
      });
    }
  });
</script>
<script type="text/javascript">
  load_data();
  function load_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>Markstatistics/fetchRange/",
      method:"POST",
      beforeSend: function() {
        $('.listHereGs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="20" height="20" id="loa">');
      },
      success:function(data){
        $('.listHereGs').html(data);
      }
    })
  }
  $(document).on('click', '#saveCriteria', function() {
    event.preventDefault();
    var lessThan=$('#less_thanGrade').val();
    var greaterThan=$('#greater_thanGrade').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Markstatistics/saveRange/",
      data: ({
        lessThan:lessThan,
        greaterThan:greaterThan
      }),
      success: function(data) {
        $(".listHereGs").html(data);
      }
    });
  });
  $(document).on('click', '.btnRemove', function() {
    event.preventDefault();
    var id=$(this).attr("id");
    var minValue=$(this).attr("value");
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Markstatistics/removeRange/",
      data: ({
        lessThan:id,
        greaterThan:minValue
      }),
      success: function(data) {
        $(".listHereGs").html(data);
      }
    });
  });
</script>
<script type="text/javascript">
  $('#comment_formGrade').on('submit', function(event) {
    event.preventDefault();
    subStatistics=[]; quarterStatistics=[];
    $("input[name='subject_statisticsGrade']:checked").each(function(i){
      subStatistics[i]=$(this).val();
    });
    $("input[name='quarter_statisticsGrade[ ]']:checked").each(function(i){
      quarterStatistics[i]=$(this).val();
    });
    /*var quarterStatistics=$("#quarter_statisticsGrade").val();*/
    var grade_statistics=$("#grade_statisticsGrade").val();
    var branch_statistics=$("#branchitGrade").val();
    if($('#includeName').is(':checked')){
      var nameChecked=1;
    }else{
      var nameChecked=0;
    }
    if(quarterStatistics.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Markstatistics/thisGradeMarkStatistics/",
        data: ({
          quarterStatistics:quarterStatistics,
          grade_statistics:grade_statistics,
          branch_statistics:branch_statistics,
          subStatistics:subStatistics,
          nameChecked:nameChecked
        }),
        beforeSend: function() {
          $('.listReportGrade').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">'
            );
        },
        success: function(data) {
          $(".listReportGrade").html(data);
        }
      });
    }else{
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branchitGrade").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Markstatistics/filterOnlyGradeFromBranch/",
        data: "branchit=" + $("#branchitGrade").val(),
        beforeSend: function() {
          $('#grade_statisticsGrade').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grade_statisticsGrade").html(data);
        }
      });
    });
    $("#grade_statisticsGrade").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Markstatistics/fetch_subject_from_gradeSecFilter/",
        data: "gradesec=" + $("#grade_statisticsGrade").val(),
        beforeSend: function() {
          $('#subject_statisticshereGrade').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#subject_statisticsHereGrade").html(data);
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
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("helloReport");
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
  function codespeedyGrade(){
    var print_div = document.getElementById("helloReportGrade");
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
    subStatistics=[];gradeStatistics=[];
    $("input[name='subject_statistics']:checked").each(function(i){
      subStatistics[i]=$(this).val();
    });
    $("input[name='grade_statistics']:checked").each(function(i){
      gradeStatistics[i]=$(this).val();
    });
    var quarterStatistics=$("#quarter_statistics").val();
    if(subStatistics.length!==0 && $("#quarter_statistics").val()!='' ){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Markstatistics/thisMarkStatistics/",
        data: ({
          quarterStatistics:quarterStatistics,
          gradeStatistics:gradeStatistics,
          subStatistics:subStatistics
        }),
        beforeSend: function() {
          $('.listReport').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">'
            );
        },
        success: function(data) {
          $(".listReport").html(data);
        }
      });
    }else{
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
</script>

</html>