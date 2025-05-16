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
                <div class="card">
                  <div class="card-header">
                    <div class="row">
                      <div class="col-md-6">
                        <h4>Students Grade History</h4>
                      </div>
                      <div class="col-md-6">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                        <span class="text-black">
                        <i data-feather="printer"></i>
                        </span>
                       </button>
                       <button type="submit" onclick="exportTableToExcel('helloUsers', 'Student History')" id="dataExport" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel</button>
                      </div>
                    </div>
                  <div class="row">
                    <div class="col-lg-4 col-6">
                      <div class="form-group">
                       <select class="form-control selectric"
                       required="required" name="branch" id="branch2progress">
                       <option>--- Select branch ---</option>
                        <?php foreach($branch as $branchs){ ?>
                          <option value="<?php echo $branchs->name;?>">
                          <?php echo $branchs->name;?>
                          </option>
                        <?php }?>
                       </select>
                      </div>
                    </div>
                    <div class="col-md-4 col-6">
                      <div class="form-group">
                        <select class="form-control"
                         required="required" name="grade2progress" id="grade2progress">
                         <option>--Select Grade--</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4 col-12">
                      <button class="btn btn-primary btn-block viewprogress"> View History 
                      </button>
                    </div>
                  </div>
                  
                </div>
                <div class="listprogressUsers" id="helloUsers"> </div>
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
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script>  
 function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById('helloUsers');
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
    $("#branch2progress").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>lastgradeinfo/Filter_grade_from_branch/",
        data: "branchit=" + $("#branch2progress").val(),
        beforeSend: function() {
          $('#grade2progress').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grade2progress").html(data);
        }
      });
    });
  });
</script>
<script>
  $(document).on('click', '.viewprogress', function() {
    var branch=$('#branch2progress').val();
    var grade=$('#grade2progress').val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>lastgradeinfo/fetch_history/",
      data: ({
        branch:branch,
        grade: grade
      }),
      cache: false,
      beforeSend: function() {
        $('.listprogressUsers').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="54" height="54" id="loa">' );
      },
      success: function(html){
        $('.listprogressUsers').html(html);
      }
    });   
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("helloUsers");
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
</html>