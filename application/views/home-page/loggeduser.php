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
            <div class="card">  
              <div class="card-body StudentViewTextInfo">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab5" data-toggle="tab" href="#urgentAlert" role="tab" aria-selected="false"><h5 class="card-title">Urgent Alert</h5></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#userActions" role="tab" aria-selected="false"><h5 class="card-title">User Actions</h5>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab1" data-toggle="tab" href="#loggedUser" role="tab" aria-selected="true"> <h5 class="card-title">Logged User</h5></a>
                  </li>
                  
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#systemBlocked" role="tab" aria-selected="false"><h5 class="card-title">Auto Blocked Users</h5></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab4" data-toggle="tab" href="#backupFIles" role="tab" aria-selected="false"><h5 class="card-title">Backup Files</h5></a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="urgentAlert" role="tabpanel" aria-labelledby="home-tab5">
                    <div class="dropdown-divider"></div>
                    <p class="alert alert-light text-danger"><i class="fas fa-exclamation-triangle"> </i>This alert will inform you the manipulated results in previous seasons!</p>
                    <div id="fetchUrgentAlert"></div>
                  </div>
                  <div class="tab-pane fade show" id="userActions" role="tabpanel" aria-labelledby="home-tab2">
                    <button class="btn btn-primary pull-right" name="gethisreport" onclick="codespeedy()">  <i class="fas fa-print"></i>  print </button>
                    <button type="submit" onclick="exportTableToExcel('fetchUserActions', 'Users Action')" id="dataExport" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel</button>
                    <input type="text" name="searchStudent" id="searchStudent" class="form-control typeahead" placeholder="Search actions (by Subject, Grade , Date . . . ) ">
                    <div class="dropdown-divider"></div>
                    <div id="fetchUserActions"></div>
                  </div>
                  <div class="tab-pane fade show" id="loggedUser" role="tabpanel" aria-labelledby="home-tab1">
                    <?php if($loggeduser->num_rows()>0){ ?>
                    <div class="table-responsive">
                      <table class="table table-striped table-hover" style="width:100%;">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>User Name</th>
                            <th>User Type</th>
                            <th>Browser</th>
                            <th>IP Address</th>
                            <th>Platform</th>
                            <th>Logged At</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no =1; 
                          foreach($loggeduser->result() as $post){  ?>
                          <tr>
                          <td><?php echo $no;?>.</td>
                          <td><?php echo $post->fname; echo ' ';echo $post->mname;?></td>
                          <td><?php echo $post->usertype; ?></td>

                          <td><?php echo $post->browser . ' - ' . $post->bversion; ?></td>
                          <td><?php echo $post->platform; ?></td>
                          <td><?php echo $post->ipaddress;?></td>
                          <td><?php echo $post->dateime; ?></td>
                          </tr>
                           <?php $no++; } ?>
                        </tbody>
                      </table>
                    </div>
                  <?php  } else{ ?>
                    <div class="alert alert-warning alert-dismissible show fade">
                      <div class="alert-body">
                          <button class="close"  data-dismiss="alert">
                              <span>&times;</span>
                          </button>
                         <i class="fas fa-check-circle"> </i> No record found.
                      </div>
                    </div>
                  <?php } ?>
                  </div>
                  
                  <div class="tab-pane fade show" id="systemBlocked" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="fetchSystemBlockedUsers"></div>
                  </div>
                  <div class="tab-pane fade show" id="backupFIles" role="tabpanel" aria-labelledby="home-tab4">
                    <div class="row">
                      <div class="col-lg-6 col-6">
                        <!-- <p class="text-danger">Under construction!</p> -->
                        <form method="POST" action="<?php echo base_url(); ?>Loggeduser/backUpFIles/">
                          <button class="btn btn-primary btn-block backUpFIles pull-right " type="submit" id="backupFIles">Download Backup</button>
                        </form> 
                      </div>
                      <div class="col-lg-6 col-6">
                        <span class="backupFIlesInformation"></span>
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
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script> 
  $(document).ready(function(){
    loadUserAction();
    loadUrgentAlert();
    function loadUrgentAlert()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Loggeduser/fetchUrgentAlert/",
        method:"POST",
        beforeSend: function() {
          $('#fetchUrgentAlert').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#fetchUrgentAlert').html(data);
        }
      })
    }
    function loadUserAction()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Loggeduser/fetchUserActions/",
        method:"POST",
        beforeSend: function() {
          $('#fetchUserActions').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#fetchUserActions').html(data);
        }
      })
    }
    $(document).on('click', '.updateAlteredResult', function() {
      swal({
        title: 'Are you sure?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          var grade=$(this).attr("id");
          var id=$(this).attr("title");
          var quarter=$(this).attr("name");
          var branch=$(this).attr("value");
          var academicyear=$(this).attr("alt");
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>loggeduser/alertUpdate/",
            data: ({
              id:id,
              grade: grade,
              quarter:quarter,
              branch:branch,
              academicyear:academicyear
            }),
            cache: false,
            beforeSend: function() {
              $('.updatingAlteredResult' + id).attr('disabled','disabled');
              $('.updatingAlteredResult' + id).html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">');
            },
            success: function(html){
              if(html=='1'){
                loadUrgentAlert();
                iziToast.success({
                  title: '',
                  message: 'Result updated successfully.',
                  position: 'topRight'
                });
              }else{
                iziToast.error({
                  title: '',
                  message: 'Something wrong please try again..',
                  position: 'topRight'
                });
              }
            }
          }); 
        }
      });
    }); 
    $(document).on('click', '.ignoreAlteredResult', function() {
      swal({
        title: 'Are you sure?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          var grade=$(this).attr("id");
          var id=$(this).attr("title");
          var quarter=$(this).attr("name");
          var branch=$(this).attr("value");
          var academicyear=$(this).attr("alt");
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>loggeduser/alertIgnore/",
            data: ({
              id:id,
              grade: grade,
              quarter:quarter,
              branch:branch,
              academicyear:academicyear
            }),
            cache: false,
            beforeSend: function() {
              $('.ignoringAlteredResult' + id).attr('disabled','disabled');
              $('.ignoringAlteredResult' + id).html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">');
            },
            success: function(html){
              if(html=='1'){
                loadUrgentAlert();
                iziToast.success({
                  title: '',
                  message: 'Alert ignored successfully.',
                  position: 'topRight'
                });
              }else{
                iziToast.error({
                  title: '',
                  message: 'Something wrong please try again..',
                  position: 'topRight'
                });
              }
            }
          }); 
        }
      });
    });  
    $(document).on('click', '.restoreDeletedMark', function() {
      swal({
        title: 'Are you sure?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          var id=$(this).attr("id");
          var subject=$(this).attr("title");
          var quarter=$(this).attr("name");
          var branch=$(this).attr("value");
          var oldata=$(this).attr("alt");
          var split_id = id.split("_");
          var grade = split_id[0];
          var idn = split_id[1];
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>loggeduser/restoreDeletedMark/",
            data: ({
              subject:subject,
              grade: grade,
              quarter:quarter,
              branch:branch,
              oldata:oldata,
              idn:idn
            }),
            cache: false,
            beforeSend: function() {
              $('.savingrestoreDeletedMark' + idn).attr('disabled','disabled');
              $('.savingrestoreDeletedMark' + idn).html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">');
            },
            success: function(html){
              loadUserAction();
              iziToast.success({
                title: '',
                message: html,
                position: 'topRight'
              });
            }
          }); 
        }
      });
    }); 
    loadBlockedUsers();
    function loadBlockedUsers()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>loggeduser/fetchSystemBlockedUsres/",
        method:"POST",
        beforeSend: function() {
          $('.fetchSystemBlockedUsers').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.fetchSystemBlockedUsers').html(data);
        }
      })
    }
    $(document).on('click', '.btnRemoveRestriction', function() {
      swal({
        title: 'Are you sure?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
      if (willDelete) {
        var userName=$(this).attr("value");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>loggeduser/deleteRestriction/",
          data: ({
            userName: userName
          }),
          cache: false,
          success: function(html){
            loadBlockedUsers();
          }
        }); 
      }
      });
    }); 
  }); 
 function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById('fetchUserActions');
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
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
    $('#searchStudent').on("keyup",function() {
      $searchItem=$('#searchStudent').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Loggeduser/customActions/",
        data: "searchItem=" + $("#searchStudent").val(),
        beforeSend: function() {
          $('#fetchUserActions').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#fetchUserActions").html(data);
        }
      });
    });
  });
  function codespeedy(){
    var print_div = document.getElementById("fetchUserActions");
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