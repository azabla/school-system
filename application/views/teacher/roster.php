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
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#quarterRoster" role="tab" aria-selected="true">Quarter Roster</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#annualRoster" role="tab" aria-selected="false">Annual Roster</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="quarterRoster" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row"> 
                          <div class="col-md-12 col-12"> 
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyQuarter()">
                                <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>Print
                            </button> 
                            <button type="submit" onclick="exportTableToExcelQuarter('helloRoster', 'Student Roster')" id="dataExport" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel</button>
                          </div>
                        </div>
                        <form id="comment_formQuarter">
                          <div class="row">
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="reportacaQuarter" id="reportacaQuarter">
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
                                <select class="form-control" required="required" name="gradesecQuarter" id="gradesec_rosterQuarter">
                                  <option> --- Select Grade --- </option>
                                 <?php if($_SESSION['usertype']===trim('Director')){
                                      foreach($gradesec as $gradesecs){ ?>
                                        <option value="<?php echo $gradesecs->grade;?>">
                                         <?php echo $gradesecs->grade;?>
                                        </option>
                                      <?php } }else{ 
                                      foreach($gradesecTeacher as $gradesecs){ ?>
                                        <option value="<?php echo $gradesecs->grade;?>">
                                         <?php echo $gradesecs->grade;?>
                                        </option>
                                      <?php } }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="roQuarterQuarter" id="roQuarterQuarter">
                                    <option>--- Quarter ---</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-2 col-6">
                              <button class="btn btn-primary btn-block" type="submit" name="gethisroster">
                                View
                              </button>
                            </div>
                          </div>
                          <div class="listRosterQuarter" id="helloRosterQuarter"></div>
                        </form>
                      </div>
                      <div class="tab-pane fade show" id="annualRoster" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row"> 
                          <div class="col-md-12 col-12"> 
                            <button class="btn btn-warning pull-right" name="gethisreport" onclick="codespeedy()">
                              <span class="text-black">
                              <i data-feather="printer"></i>
                            </span>Print
                            </button> 
                            <button type="submit" onclick="exportTableToExcelAnnual('helloAnnualRoster', 'Student Roster')" id="dataExport" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel</button>
                          </div>
                        </div>
                        <form id="comment_form">
                          <div class="row">
                            <div class="col-lg-2 col-6">
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
                            <div class="col-lg-2 col-6">
                              <div class="form-group">
                               <select class="form-control"
                               required="required" name="gradesec" id="gradesec_roster">
                               <option> --- Grade --- </option>
                               <?php if($_SESSION['usertype']===trim('Director')){
                                    foreach($gradesec as $gradesecs){ ?>
                                      <option value="<?php echo $gradesecs->grade;?>">
                                       <?php echo $gradesecs->grade;?>
                                      </option>
                                    <?php } }else{ 
                                    foreach($gradesecTeacher as $gradesecs){ ?>
                                      <option value="<?php echo $gradesecs->grade;?>">
                                       <?php echo $gradesecs->grade;?>
                                      </option>
                                    <?php } }?>
                               </select>
                              </div>
                             </div>
                             <div class="col-lg-2 col-6">
                              <div class="form-group">
                               <select class="form-control" required="required" name="pageBreak" id="pageBreak">
                                <option> --- No.Page --- </option>
                                <?php for($i=1;$i<6;$i++){?>
                                  <option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
                                <?php } ?>
                               
                               </select>
                              </div>
                             </div>
                             <div class="col-lg-3 col-6">
                              <select class="custom-select" required="required" name="includeLetterTranscript" id="includeLetterTranscript">
                                <option value="Both">Number & Letter</option>
                                <option value="Number">Number</option>
                                <option value="Letter">Letter</option>
                              </select>
                            </div>
                             <div class="col-lg-3 col-6">
                              <button class="btn btn-primary btn-block" type="submit" name="gethisroster">
                                View Roster
                              </button>
                            </div>
                          </div>
                        <div class="listRoster" id="helloRoster"></div>
                      </form>
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
</body>
<script type="text/javascript">
  function codespeedyQuarter(){
    var print_div = document.getElementById("helloRosterQuarter");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  function exportTableToExcelAnnual(tableID, filename = ''){
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
  function exportTableToExcelQuarter(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById('helloRosterQuarter');
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
<script type="text/javascript">
  $('#comment_formQuarter').on('submit', function(event) {
    event.preventDefault();
      if($("#gradesecQuarter").val()!='' && $("#branchQuarter").val()!=''){
      var form_data = $(this).serialize();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Studentroster/Fetch_quarter_roster/",
        data: form_data,
        beforeSend: function() {
          $('.listRosterQuarter').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="64" height="64" id="loa">'
            );
        },
        success: function(data) {
          $(".listRosterQuarter").html(data);
        }
      });
    }
  });
  $(document).ready(function() {  
    $("#reportacaQuarter").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Studentroster/filterQuarterfromAcademicYear/",
        data: "academicyear=" + $("#reportacaQuarter").val(),
        beforeSend: function() {
          $('#roQuarterQuarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#roQuarterQuarter").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branch_roster").bind("change", function() {
      var branchit=$("#branch_roster").val();
      var academicyear=$("#reportaca").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Studentroster/filterGradefromBranch/",
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
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
</script>
<script type="text/javascript">
   $('#comment_form').on('submit', function(event) {
    event.preventDefault();
      var gradesec=$("#gradesec").val();
      var branch=$("#branch").val();
      if($("#gradesec").val()!='' && $("#branch").val()!=''){
      var form_data = $(this).serialize();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Studentroster/fetchroster/",
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