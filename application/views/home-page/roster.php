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
              <div class="col-lg-12 col-12">
                <a href="#" class="editsubject" value="" data-toggle="modal" data-target="#updateRosterGS"><span class="text-black">
                  <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Update Roster</button>
               </span>
               </a>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#quarterRoster" role="tab" aria-selected="true">Season Roster</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#annualRoster" role="tab" aria-selected="false">Annual Roster</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#semesterRoster" role="tab" aria-selected="false">Semester Roster</a>
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
                            <div class="col-lg-2 col-6">
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
                                <select class="form-control selectric"
                                 required="required" name="branchQuarter" id="branch_rosterQuarter">
                                 <option> --- Select Branch --- </option>
                                  <!-- <?php foreach($branch as $branchs){ ?>
                                    <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                    </option>
                                  <?php }?> -->
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-3 col-6">
                              <div class="form-group">
                                <select class="form-control" required="required" name="gradesecQuarter" id="gradesec_rosterQuarter">
                                 <option> --- Select Grade --- </option>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-2 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="roQuarterQuarter" id="roQuarterQuarter">
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
                          <div class="listRosterQuarter" id="helloRosterQuarter"></div>
                        </form>
                      </div>
                      <div class="tab-pane fade show" id="annualRoster" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row"> 
                          <div class="col-md-12 col-12"> 
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                                <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>Print
                            </button> 
                            <button type="submit" onclick="exportTableToExcel('helloRoster', 'Student Roster')" id="dataExport" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel</button>
                          </div>
                        </div>
                        <form id="comment_form" class="StudentViewTextInfo">
                          <div class="row">
                            <div class="col-lg-2 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required"  name="reportaca" id="reportaca">
                                  <option>--Year--</option>
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
                                <select class="form-control selectric"
                                 required="required" name="branch" id="branch_roster">
                                 <option> --- Branch --- </option>
                                  <!-- <?php foreach($branch as $branchs){ ?>
                                    <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                    </option>
                                  <?php }?> -->
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-2 col-6">
                              <div class="form-group">
                                <select class="form-control"
                                 required="required" name="gradesec" id="gradesec_roster">
                                 <option> --- Grade --- </option>
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
                            <div class="col-lg-2 col-6">
                              <select class="custom-select" required="required" name="includeLetterTranscript" id="includeLetterTranscript">
                                <option value="Both">Number & Letter</option>
                                <option value="Number">Number</option>
                                <option value="Letter">Letter</option>
                              </select>
                            </div>
                            <div class="col-lg-2 col-6">
                              <button class="btn btn-primary btn-block" type="submit" name="gethisroster"> View Roster </button>
                            </div>
                          </div>
                        </form>
                        <div class="listRoster" id="helloRoster"></div>
                      </div>
                      <div class="tab-pane fade show" id="semesterRoster" role="tabpanel" aria-labelledby="home-tab3">
                        <div class="row"> 
                          <div class="col-md-12 col-12"> 
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedySemester()">
                                <span class="text-black">
                                <i data-feather="printer"></i>
                              </span>Print
                            </button> 
                             <button type="submit" id="dataExportExcelSemester" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel
                                </button>
                          </div>
                        </div>
                        <form id="comment_formSemester" class="StudentViewTextInfo">
                          <div class="row">
                            <div class="col-lg-2 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required"  name="reportacaSemester" id="reportacaSemester">
                                  <option>--Year--</option>
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
                                <select class="form-control selectric"
                                 required="required" name="branchSemester" id="branch_rosterSemester">
                                 <option> --- Branch --- </option>
                                  <!-- <?php foreach($branch as $branchs){ ?>
                                    <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                    </option>
                                  <?php }?> -->
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-2 col-6">
                              <div class="form-group">
                                <select class="form-control"
                                 required="required" name="gradesecSemester" id="gradesec_rosterSemester">
                                 <option> --- Grade --- </option>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-2 col-6">
                              <div class="form-group">
                                <select class="form-control" required="required" name="pageBreakSemester" id="pageBreakSemester">
                                  <option> --- No.Page --- </option>
                                  <?php for($i=1;$i<6;$i++){?>
                                    <option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
                                  <?php } ?>
                                 
                                </select>
                              </div>
                            </div>
                            <!-- <div class="col-lg-2 col-6">
                              <select class="custom-select" required="required" name="includeLetterTranscript" id="includeLetterTranscript">
                                <option value="Both">Number & Letter</option>
                                <option value="Number">Number</option>
                                <option value="Letter">Letter</option>
                              </select>
                            </div> -->
                            <div class="col-lg-2 col-12">
                              <button class="btn btn-primary btn-block" type="submit" name="gethisrosterSemester"> View Roster </button>
                            </div>
                          </div>
                        </form>
                        <div class="listRosterSemester" id="helloRosterSemester"></div>
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
  <div class="modal fade" id="updateRosterGS" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Update Roster</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-6 col-6">
              <div class="form-group">
                <select class="form-control selectric" required="required"  name="reportacaUpdate" id="reportacaUpdate">
                  <option>--Year--</option>
                  <?php foreach($academicyear as $academicyears){ ?>
                    <option value="<?php echo $academicyears->year_name;?>">
                    <?php echo $academicyears->year_name;?>
                    </option>
                  <?php }?>
                  </select>
              </div>
            </div>
            <div class="col-lg-6 col-6">
              <div class="form-group">
                <select class="form-control selectric" required="required" name="branch_rosterUpdate" id="branch_rosterUpdate">
                 <option> --- Branch --- </option>
                </select>
              </div>
            </div>
            <div class="col-lg-12 col-12">
              <div class="form-group" name="gradesec_rosterUpdate" id="gradesec_rosterUpdate">
              </div>
            </div>
            <div class="col-lg-6 col-6">
              <div class="form-group">
                <select class="form-control selectric" required="required" name="quarter_rosterUpdate" id="quarter_rosterUpdate">
                 <option> --- Season --- </option>
                </select>
              </div>
            </div>
            <div class="col-lg-6 col-6">
             <button class="btn btn-primary btn-block" type="submit" name="updateRoster_gs" id="updateRoster_gs"> Update Roster </button>
           </div>
          </div>
          <div class="checking_updateRoster_gs"></div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <a id="saveskygrade"></a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#reportacaUpdate").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Roster/filterQuarterfromAcademicYear/",
        data: "academicyear=" + $("#reportacaUpdate").val(),
        beforeSend: function() {
          $('#quarter_rosterUpdate').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#quarter_rosterUpdate").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#branch_rosterUpdate").bind("change", function() {
      var branchit=$("#branch_rosterUpdate").val();
      var academicyear=$("#reportacaUpdate").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>roster/filterGradefromBranchUpdate/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#gradesec_rosterUpdate').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradesec_rosterUpdate").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportacaUpdate").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Roster/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportacaUpdate").val(),
        beforeSend: function() {
          $('#branch_rosterUpdate').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#branch_rosterUpdate").html(data);
        }
      });
    });
  });
  $(document).on('click', '#updateRoster_gs', function() {
    grade2analysis=[];
    $("input[name='updateSubjectGrade']:checked").each(function(i){
      grade2analysis[i]=$(this).val();
    });
    var branchit=$("#branch_rosterUpdate").val();
    var academicyear=$("#reportacaUpdate").val();
    var season=$("#quarter_rosterUpdate").val();
    if(grade2analysis.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>roster/update_roster_summary/",
        data:({
          branchit:branchit,
          academicyear:academicyear,
          grade2analysis:grade2analysis,
          season:season
        }),
        beforeSend: function() {
          $('.checking_updateRoster_gs').html( 'Updating...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(data) {
          $(".checking_updateRoster_gs").html(data);
        }
      });
    }else{
      swal('Please select a grade!', {
        icon: 'error',
      });
    }
  });
  function codespeedySemester(){
    var print_div = document.getElementById("helloRosterSemester");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  $("#dataExportExcelSemester").click(function(e) {
  let file = new Blob([$('.listRosterSemester').html()], {type:"application/vnd.ms-excel"});
  let url = URL.createObjectURL(file);
  let a = $("<a />", {
    href: url,
    download: "Semester Roster.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
  });
  $('#comment_formSemester').on('submit', function(event) {
    event.preventDefault();
      var gradesec=$("#gradesecSemester").val();
      var branch=$("#branchSemester").val();
      if($("#gradesecSemester").val()!='' && $("#branchSemester").val()!=''){
      var form_data = $(this).serialize();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>roster/fetchSemester_roster/",
        data: form_data,
        beforeSend: function() {
          $('.listRosterSemester').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".listRosterSemester").html(data);
        }
      });
    }
  });
  $(document).ready(function() {  
    $("#branch_rosterSemester").bind("change", function() {
      var branchit=$("#branch_rosterSemester").val();
      var academicyear=$("#reportacaSemester").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>roster/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#gradesec_rosterSemester').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradesec_rosterSemester").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportacaSemester").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Roster/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportacaSemester").val(),
        beforeSend: function() {
          $('#branch_rosterSemester').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#branch_rosterSemester").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportacaQuarter").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Roster/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportacaQuarter").val(),
        beforeSend: function() {
          $('#branch_rosterQuarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#branch_rosterQuarter").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportaca").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Roster/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportaca").val(),
        beforeSend: function() {
          $('#branch_roster').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#branch_roster").html(data);
        }
      });
    });
  });
   $('#comment_formQuarter').on('submit', function(event) {
    event.preventDefault();
      if($("#gradesecQuarter").val()!='' && $("#branchQuarter").val()!=''){
      var form_data = $(this).serialize();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Roster/Fetch_quarter_roster/",
        data: form_data,
        beforeSend: function() {
          $('.listRosterQuarter').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".listRosterQuarter").html(data);
        }
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branch_rosterQuarter").bind("change", function() {
      var branchit=$("#branch_rosterQuarter").val();
      var academicyear=$("#reportacaQuarter").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Roster/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#gradesec_rosterQuarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradesec_rosterQuarter").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportacaQuarter").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Roster/filterQuarterfromAcademicYear/",
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
</script>
<script>  
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
  $(document).ready(function() {  
    $("#branch_roster").bind("change", function() {
      var branchit=$("#branch_roster").val();
      var academicyear=$("#reportaca").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>roster/filterGradefromBranch/",
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
        url: "<?php echo base_url(); ?>roster/fetchroster/",
        data: form_data,
        beforeSend: function() {
          $('.listRoster').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
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