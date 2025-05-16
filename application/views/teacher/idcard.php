
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
  <div class="loader"><div class="loaderIcon"></div></div>
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
              <div class="card-header">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#defaultIDCard" role="tab" aria-selected="true"> Default ID Card</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#customIDCard" role="tab" aria-selected="false">Custom ID Card</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#backPage" role="tab" aria-selected="false">Back Page</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="defaultIDCard" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-lg-6 col-6">
                        </div>
                        <div class="col-lg-6 col-6">
                          <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                            <i data-feather="printer"></i>
                          </button>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-4 col-6 table-responsive" style="height: 25vh;">
                         <div class="form-group">
                          <label for="subject">Select grade </label><br>
                            <div class="row">
                              <?php foreach($gradesec as $gradesecs){ ?>
                                <div class="col-lg-4 col-6">
                                  <div class="pretty p-icon p-jelly p-round p-bigger">
                                    <input type="checkbox" name="studentServiceGrade" value="<?php echo $gradesecs->grade;?>" class="studentServiceGrade" id="customCheck1">
                                    <div class="state p-info">
                                      <i class="icon material-icons"></i>
                                      <label></label>
                                    </div>
                                  </div>
                                   <?php echo $gradesecs->grade; ?>
                                  <div class="dropdown-divider2"></div>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6 table-responsive" style="height:15vh;">
                          <div class="form-group" id="studentID">
                          </div>
                        </div>
                        <div class="col-lg-4 col-6 table-responsive" style="height:15vh;">
                          <div class="form-group" id="placeID">
                          </div>
                        </div>
                        <div class="col-lg-12 col-6">
                          <button class="btn btn-primary btn-block" id="generateQRCodeNow" type="submit" name="gethisroster"> View
                          </button>
                        </div>
                      </div>
                      <div class="idStuCardList table-responsive" id="helloStuIDCard" style="height:45vh;"> </div>
                      <div id="qrcode" style="padding: 10px;height:auto;width:65px;"></div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="customIDCard" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="row">
                      <div class="col-lg-6 col-6">
                      </div>
                      <div class="col-lg-6 col-6">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyCustom()">
                          <i data-feather="printer"></i>
                        </button>
                      </div>
                    </div>
                    <div class="row"> 
                      <div class="col-lg-6 col-12">
                        <div class="card-header">
                          <input type="text" class="form-control typeahead" id="searchStudentForTransportPlace" name="searchStudentForTransportPlace" placeholder="Search Student Id,Name">
                          <div class="table-responsive" style="height:15vh;">
                            <div class="searchPlaceHere"></div> 
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-12">
                        <textarea class="form-control" id="selectStudentForTransportPlace" name="selectStudentForTransportPlace" col="12">  </textarea>
                        <button class="btn btn-default RemoveAll" id="removeAll" type="submit"><i class="fas fa-angle-double-left"></i></button>
                      </div> 
                      <div class="col-lg-6 col-12">
                        <div class="card-header">
                          <button type ="submit" class="btn btn-primary btn-block" id="fetchCustomIDCard" name="fetchCustomIDCard" >View ID Card</button>
                        </div>
                      </div>
                    </div>
                    <div class="fetchCustomIDCardHere table-responsive" id="helloStuIDCardCustom" style="height:45vh;"></div>
                  </div>
                  <div class="tab-pane fade show" id="backPage" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="row">
                      <div class="col-lg-6 col-6">
                      </div>
                      <div class="col-lg-6 col-6">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="backPagePrint()">
                          <i data-feather="printer"></i>
                        </button>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6 col-6">
                        <input type="number" name="pageNumber" id="pageNumber" class="form-control">
                      </div>
                      <div class="col-lg-6 col-6">
                        <button type="button" class="btn btn-primary btn-block" id="submitBackPage">Submit</button>
                      </div>
                    </div>
                    <div class="fetchBackIDCardHere table-responsive" id="fetchBackIDCardHere" style="height:45vh;"></div>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script type="text/javascript">
    $(document).on('click', '#submitBackPage', function() {
      event.preventDefault();
      var pageNumber=$('#pageNumber').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Myidcard/fetchBackIdCard/",
        data: ({
          pageNumber: pageNumber
        }),
        beforeSend: function() {
          $('#fetchBackIDCardHere').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $("#fetchBackIDCardHere").html(data);
        }
      });
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function() { 
      $('#searchStudentForTransportPlace').on("keyup",function() {
        $searchItem=$('#searchStudentForTransportPlace').val();
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>Myidcard/searchStudentsToTransportService/",
          data: "searchItem=" + $("#searchStudentForTransportPlace").val(),
          beforeSend: function() {
            $('.searchPlaceHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
          },
          success: function(data) {
            $(".searchPlaceHere").html(data);
          }
        });
      });
    });
    $(document).on('click', '.saveThisStudentToGroupEdit', function() {
      event.preventDefault();
      var oldText=$('#selectStudentForTransportPlace').val();
      var stuID=$(this).attr("value");
      var newText=oldText+stuID+"\n";
      $("#selectStudentForTransportPlace").val(newText);   
    });
    $(document).on('click', '#removeAll', function() {
      event.preventDefault();
      $("#selectStudentForTransportPlace").val('');   
    });
  </script>
  <script type="text/javascript">
    $(document).on('click', '#fetchCustomIDCard', function() {
      event.preventDefault();
      var newServicePlace=$('#selectStudentForTransportPlace').val();
      var stuIdArray=newServicePlace.split(/(\s+)/);
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Myidcard/fetchCustomIDCard/",
        data: ({
          stuIdArray: stuIdArray
        }),
        beforeSend: function() {
          $('.fetchCustomIDCardHere').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".fetchCustomIDCardHere").html(data);
        }
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {  
    $(document).on('click', '.studentServiceGrade', function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Myidcard/filterServicePlace/",
        beforeSend: function() {
          $('#placeID').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#placeID").html(data);
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
      $(document).on('click', '.studentServiceGrade', function() {
        grade=[];
        $("input[name='studentServiceGrade']:checked").each(function(i){
          grade[i]=$(this).val();
        });
        if($(".studentServiceGrade").val()!=''){
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>Myidcard/fetchThisGradeStudentIdcard/",
           data: ({
            grade: grade
          }),
          beforeSend: function() {
            $('#studentID').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
              );
          },
          success: function(data) {
            $("#studentID").html(data);
          }
        });
      }
    });
  </script>
  <script type="text/javascript">
    function selectAllPlaceList(){
        var itemsall=document.getElementById('selectallServicePlaceList');
        if(itemsall.checked==true){
        var items=document.getElementsByName('studentServicePlace[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=true;
        }
      }
        else{
        var items=document.getElementsByName('studentServicePlace[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=false;
        }
      }
    }
</script>
  <script type="text/javascript">
    function selectAllStudent(){
        var itemsall=document.getElementById('selectallStudentList');
        if(itemsall.checked==true){
        var items=document.getElementsByName('studentListTransportService[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=true;
        }
      }
        else{
        var items=document.getElementsByName('studentListTransportService[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=false;
        }
      }
    }
</script>
  <script type="text/javascript">
    $(document).on('click', '#generateQRCodeNow', function() {
      servicePlace=[];
      $("input[name='studentServicePlace[ ]']:checked").each(function(i){
        servicePlace[i]=$(this).val();
      });
      studentList=[];
      $("input[name='studentListTransportService[ ]']:checked").each(function(i){
        studentList[i]=$(this).val();
      });
      var gradesec=$("#gradesecID").val();
      var branch=$("#branchID").val();
      var reportacaID=$("#reportacaID").val();
      if(servicePlace.length!=0 && studentList.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Myidcard/fetchStudentIdcard/",
         data: ({
          servicePlace: servicePlace,
          studentList:studentList,
          gradesec:gradesec
        }),
        beforeSend: function() {
          $('.idStuCardList').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".idStuCardList").html(data);
        }
      });
    }else{
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Myidcard/fetchStudentIdcardWithoutPlace/",
         data: ({
          studentList:studentList,
          gradesec:gradesec
        }),
        beforeSend: function() {
          $('.idStuCardList').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".idStuCardList").html(data);
        }
      });
    }
  });
</script>
<script type="text/javascript">
  function backPagePrint(){
    var print_div = document.getElementById("fetchBackIDCardHere");
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
  function codespeedy(){
    var print_div = document.getElementById("helloStuIDCard");
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
  function codespeedyCustom(){
    var print_div = document.getElementById("helloStuIDCardCustom");
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
  $(document).ready(function() {  
    function unseen_notification(view = '') { 
      $.ajax({
        url: "<?php echo base_url() ?>fetch_unseen_notification/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType: "json",
        success: function(data) {
          $('.notification-show').html(data.notification);
          if (data.unseen_notification > 0) {
            $('.count-new-notification').html(data.unseen_notification);
          }
        }
      });
    }  
    function inbox_unseen_notification(view = '') { 
      $.ajax({
        url: "<?php echo base_url() ?>fetch_unseen_message_notification/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType: "json",
        success: function(data) {
        $('.inbox-show').html(data.notification);
          if (data.unseen_notification > 0) {
            $('.count-new-inbox').html(data.unseen_notification);
          }
        }
      });
    }
    unseen_notification();
    inbox_unseen_notification();
    $(document).on('click', '.seen_noti', function() {
      $('.count-new-notification').html('');
      inbox_unseen_notification('yes');
    });
    $(document).on('click', '.seen', function() {
      $('.count-new-inbox').html('');
      inbox_unseen_notification('yes');
    });
    setInterval(function() {
      unseen_notification();
      inbox_unseen_notification();
    }, 5000);
  });
</script>

</body>

</html>