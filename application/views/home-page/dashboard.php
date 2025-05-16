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
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
</head>

<body>
  <div class="loader"><div class="loaderIcon"></div></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <?php include('headerdashboard.php'); ?>
      <div class="main-contentDashboard">
        <section class="section">
          <?php include('bgcolor.php'); ?>
          <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
          <div class="section-body">
            <div class="row clearfix">
              <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Student Gender Numbers(By Grade)</h4>
                  </div>
                  <div class="card-body">
                    <div class="recent-report__chart">
                      <canvas id="chart1"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Student Numbers(By Grade)</h4>
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
              <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Staff Gender</h4>
                  </div>
                  <div class="card-body">
                    <div class="chart-container">
                      <div class="bar-chart-container">
                        <canvas id="bar-chartStaff"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php include('footer.php'); ?>
        </section>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/chart/chart.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/gs_all.js"></script>
  <script type='text/javascript'>
    var baseURL= "<?php echo base_url();?>";
  </script>
  <script type="text/javascript">
  $(document).ready(function() {
   
    $.ajax({
      url: "<?php echo base_url(); ?>dashboard/fetchStaffGenderReportGraph/",
      method: "POST",
      dataType:"JSON",
      beforeSend: function() {
        $('#bar-chartStaff').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="54" height="54" id="loa">' );
      },
      success: function(data) {
        getData(data.data2);
      }
    });
  
  function getData(data){
    var usertype = [];
    var total = [];
    var male = [];
    var female = [];
    var color = [];
    for(var count = 0; count < data.length; count++)
    {
      usertype.push(data[count].usertype);
      total.push(data[count].total);
      male.push(data[count].male);
      female.push(data[count].female);
      color.push(data[count].color);
    }
    const randomColor1 = Math.floor(Math.random()*16777215).toString(16);
    const randomColor2 = Math.floor(Math.random()*16777215).toString(16);
    const randomColor3 = Math.floor(Math.random()*16777215).toString(16);
    var chart_data = {
      labels:usertype,
      datasets:[
        {
          label:'Male',
          backgroundColor:'#' + randomColor1,
          color:'#fff',
          data:male
        },
        {
          label:'Female',
          backgroundColor:'#' + randomColor2,
          color:'#fff',
          data:female
        }
        ,
        {
          label:'Total',
          backgroundColor:'#' + randomColor3,
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
    var group_chart1 = $('#bar-chartStaff');
    var graph1 = new Chart(group_chart1, {
      type:"bar",
      data:chart_data
    });
  }
  })
</script>
  <script type="text/javascript">
  $(document).ready(function() {
   
    $.ajax({
      url: "<?php echo base_url(); ?>dashboard/fetchGradeReportGraph/",
      method: "POST",
      dataType:"JSON",
      beforeSend: function() {
        $('#bar-chart').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="54" height="54" id="loa">' );
      },
      success: function(data) {
        getData(data.data2);
      }
    });
  
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
      type:"pie",
      data:chart_data
    });
  }
  })
</script>
<script type="text/javascript">
  $(document).ready(function() {
   
    $.ajax({
      url: "<?php echo base_url(); ?>dashboard/fetchGradeGenderReportGraph/",
      method: "POST",
      dataType:"JSON",
      beforeSend: function() {
        $('#chart1').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="54" height="54" id="loa">' );
      },
      success: function(data) {
        getData(data.data2);
      }
    });
  
  function getData(data){
    var grade = [];
    var total = [];
    var male = [];
    var female = [];
    var color = [];
    for(var count = 0; count < data.length; count++)
    {
      grade.push(data[count].grade);
      total.push(data[count].total);
      male.push(data[count].male);
      female.push(data[count].female);
      color.push(data[count].color);
    }
    const randomColor1 = Math.floor(Math.random()*16777215).toString(16);
    const randomColor2 = Math.floor(Math.random()*16777215).toString(16);
    const randomColor3 = Math.floor(Math.random()*16777215).toString(16);
    var chart_data = {
      labels:grade,
      datasets:[
        {
          label:'Male',
          backgroundColor:'#' + randomColor1,
          color:'#fff',
          data:male
        },
        {
          label:'Female',
          backgroundColor:'#' + randomColor2,
          color:'#fff',
          data:female
        }
        ,
        {
          label:'Total',
          backgroundColor:'#' + randomColor3,
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
    var group_chart1 = $('#chart1');
    var graph1 = new Chart(group_chart1, {
      type:"bar",
      data:chart_data
    });
  }
  })
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
</body>

</html>