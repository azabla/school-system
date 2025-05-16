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
      ?>Roster
  </title>
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
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
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <div class="row"> 
                      <div class="col-md-4">
                        <h4><a href="<?php echo base_url() ?>roster/">Annual Roster</a></h4>
                     </div>
                     <div class="col-md-4">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                          <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>Print
                        </button>
                      </div>
                      <div class="col-md-4">  
                        <button type="submit" onclick="exportTableToExcel('helloRoster', 'Student Roster')" id="dataExport" name="dataExport" value="Export to excel" class="btn btn-info">Export To Excel</button>
                      </div>
                    </div>
                  </div>
                  <div class="card-header">
                    <form id="comment_form">
                     <div class="row">
                       <div class="col-lg-2 col-6">
                         <div class="form-group">
                           <select class="form-control selectric"
                           required="required" name="reportaca" id="reportaca">
                           <option>--Year--</option>
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                              <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                           </select>
                          </div>
                         </div>
                         <div class="col-lg-3 col-6">
                          <div class="form-group">
                           <select class="form-control selectric"
                           required="required" name="branch" id="branch_roster">
                           <option> --- Select Branch --- </option>
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
                           <select class="form-control"
                           required="required" name="gradesec" id="gradesec_roster">
                           <option> --- Select Grade --- </option>
                           </select>
                          </div>
                         </div>

                         <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="roQuarter" id="roQuarter">
                              <option>--- Quarter ---</option>
                            </select>
                          </div>
                        </div>

                         <div class="col-lg-2 col-12">
                        <button class="btn btn-primary btn-block" type="submit" name="gethisroster">
                          View
                        </button>
                      </div>
                    </div>
                  <div class="listRoster table-responsive" id="helloRoster" style="height:50vh;"></div>
                 </div>
                </form>
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
</body>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branch_roster").bind("change", function() {
      var branchit=$("#branch_roster").val();
      var academicyear=$("#reportaca").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>QuarterRoster/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#gradesec_roster').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradesec_roster").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportaca").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>QuarterRoster/filterQuarterfromAcademicYear/",
        data: "academicyear=" + $("#reportaca").val(),
        beforeSend: function() {
          $('#roQuarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#roQuarter").html(data);
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
    var print_div = document.getElementById("helloRoster");
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
      if($("#gradesec").val()!='' && $("#branch").val()!=''){
      var form_data = $(this).serialize();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>QuarterRoster/fetchroster/",
        data: form_data,
        beforeSend: function() {
          $('.listRoster').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="64" height="64" id="loa">'
            );
        },
        success: function(data) {
          $(".listRoster").html(data);
        }
      });
    }
  });
</script>
<script>  
 function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById('helloRoster');
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}  
 </script> 
</html>