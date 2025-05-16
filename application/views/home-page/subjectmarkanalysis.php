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
            <div class="row">
              <div class="col-12">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="row">
              <div class="col-12 col-sm-12 col-lg-6">
              <div class="card">
                  <div class="card-header">
                    <div class="row">
                      <div class="col-md-10 col-8">
                        <h4>Subject mark analysis through table</h4>
                      </div>
                       <div class="col-md-2 col-4">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                        <span class="text-black">
                        <i data-feather="printer"></i>
                        </span>
                       </button>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                  <div class="row">
                     <div class="col-lg-6 col-6">
                      <div class="form-group">
                       <select class="form-control selectric"
                       required="required" name="branch"
                       id="admin_branch">
                       <option>--- Select branch ---</option>
                        <?php foreach($branch as $branchs){ ?>
                          <option value="<?php echo $branchs->name;?>">
                          <?php echo $branchs->name;?>
                          </option>
                        <?php }?>
                       </select>
                      </div>
                     </div>
                    <div class="col-md-6 col-6">
                      <div class="form-group">
                        <select class="form-control selectric" required="required" name="quarter"  id="analysis_quarter">
                          <option>--- Select Quarter ---</option>
                          <?php foreach($fetch_term as $fetch_terms){ ?>
                            <option value="<?php echo $fetch_terms->term;?>">
                            <?php echo $fetch_terms->term;?>
                            </option>
                          <?php }?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6 col-6">
                      <div class="form-group">
                        <select class="form-control"
                         required="required" name="grade2analysis" id="grade2analysis">
                         <option>--Select Grade--</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6 col-6">
                      <div class="form-group evaluation_here"></div>
                    </div>
                    <div class="col-md-6 col-12">
                      <button class="btn btn-primary btn-block btn-lg viewanalysiss"> View Analysis 
                      </button>
                    </div>
                  </div>
                  <div class="listanalysis table-responsive" id="helloAnalysis" style="height:45vh;">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-12 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Subject mark analysis through graph</h4>
                  </div>
                  <div class="card-body">
                    <div class="chart-container">
                      <div class="bar-chart-container">
                        <canvas id="bar-chart"></canvas>
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
  <script src="<?php echo base_url(); ?>assets/chart/chart.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
</body>
<!-- filter grade from branch starts -->
<script type="text/javascript">
  $(document).ready(function() {  
    $("#admin_branch").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#admin_branch").val(),
        beforeSend: function() {
          $('#grade2analysis').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grade2analysis").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#grade2analysis").bind("change", function() {
      var branch2analysis=$("#admin_branch").val();
      var grade2analysis=$("#grade2analysis").val();
       var analysis_quarter=$("#analysis_quarter").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Subjectmarkanalysis/fetchGradeSubject/",
        data:({
          branch2analysis:branch2analysis,
          grade2analysis:grade2analysis,
          analysis_quarter:analysis_quarter
        }),
        beforeSend: function() {
          $('.evaluation_here').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".evaluation_here").html(data);
        }
      });
    });
  });
</script>
<!-- fetch analysis starts -->
<script type="text/javascript">
  $(document).on('click', '.viewanalysiss', function() {
    var branch=$('#admin_branch').val();
    var gradesec=$('#grade2analysis').val();
    var evaluation=$('#subevaluationanalysis').val();
    var quarter=$('#analysis_quarter').val();
    $.ajax({
      url: "<?php echo base_url(); ?>subjectmarkanalysis/fetch_analysis/",
      method: "POST",
      data: ({
        branch: branch,
        gradesec:gradesec,
        evaluation:evaluation,
        quarter:quarter
      }),
      dataType:"JSON",
      beforeSend: function() {
        $('.listanalysis').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="54" height="54" id="loa">' );
      },
      success: function(data) {
        getData(data.data2);
        $(".listanalysis").html(data.data1);
      }
    });
  })
  function getData(data){
    var language = [];
    var total = [];
    var color = [];
    for(var count = 0; count < data.length; count++)
    {
      language.push(data[count].language);
      total.push(data[count].total);
      color.push(data[count].color);
    }
    var chart_data = {
      labels:language,
      datasets:[
        {
          label:'Total',
          backgroundColor:color,
          color:'#fff',
          data:total
        }
      ]
    };
    var options = {
      responsive:true,
      scales:{
        yAxes:[{
          ticks:{
            min:0
          }
        }]
      }
    };
    var group_chart1 = $('#bar-chart');
    var graph1 = new Chart(group_chart1, {
      type:"bar",
      data:chart_data
    });
  }
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("helloAnalysis");
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
<script>

  

</script>
</html>