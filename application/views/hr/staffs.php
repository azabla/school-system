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
            <div class="row">
              <div class="col-12">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="card">
                  <div class="card-header">
                  <div class="row">
                    <div class="col-4">
                      <a href="#" class="backPage"><i class="fas fa-backward"></i></a>
                    </div>
                    <div class="col-4">
                      <form class="" action="<?php echo base_url(); ?>Mystaffs/downloadStuData/">
                        <button type="submit" id="downloadStuData" name="downloadStuData" class="btn btn-secondary"> Download Staff Data <i data-feather="download"></i>
                        </button>
                      </form>
                    </div>
                    <div class="col-4">
                      <button class="btn btn-info" name="gethisreport" onclick="codespeedy()">
                        <span class="text-black">
                        <i data-feather="printer"></i>
                        </span>
                      </button>
                    </div>
                  </div>
                  <div class="resetPasswordInfo"></div>
                  <div class="card-body table-responsive mystaffs" id="mystaffs" style="height:70vh;"> </div>
                </div>
              </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <footer class="main-footer">
        <div class="footer-left">
         Call:+251967829025 &nbsp;&nbsp;
          Copyright &copy <?php echo date('Y');?>
          <a href="https://www.grandstande.com" target="_blanck">Grandstand IT Solution Plc</a>
          All rights are Reserved.
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
</body>
<script type="text/javascript">
  $(document).on('click', '#downloadStuData', function() {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Mystaffs/downloadStuData/",
        cache: false,
        beforeSend: function() {
          $('#downloadStuData').html( 'Downloading...');
        },
        success: function(html) {
          $("#downloadStuData").html('Download Finished.');
          window.open('<?php echo base_url(); ?>Mystaffs/downloadStuData/','_blanck');
        }
      });
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("mystaffs");
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
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Mystaffs/fetchStaffs/",
        method:"POST",
        beforeSend: function() {
          $('.mystaffs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success:function(data){
          $('.mystaffs').html(data);
        }
      })
    }
  });
  $(document).on('click', '.backPage', function()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>Mystaffs/fetchStaffs/",
      method:"POST",
      beforeSend: function() {
        $('.mystaffs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
      },
      success:function(data){
        $('.mystaffs').html(data);
      }
    })
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#edit_staff', function()
  {
    var staff_id=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Mystaffs/editStaff/",
      data: ({
        staff_id: staff_id
      }),
      cache: false,
      beforeSend: function() {
        $('.mystaffs').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
      },
      success: function(html){
       $('.mystaffs').html(html);
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('submit', '#updateStaForm', function(e) {
    e.preventDefault();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Mystaffs/updateStaff/",
      data:new FormData(this),
      processData:false,
      contentType:false,
      cache: false,
      async:false,
      beforeSend: function() {
        $('.resetPasswordInfo').html( '<span class="text-info">Updating...</span>');
      },
      success: function(html){
         $(".resetPasswordInfo").html(html);
      }
    });
  });
</script>
<script type="text/javascript">
  function load_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>Mystaffs/fetchStaffs/",
      method:"POST",
      beforeSend: function() {
        $('.mystaffs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
      },
      success:function(data){
        $('.mystaffs').html(data);
      }
    })
  }
  $(document).on('click', '.inactive', function()
  {
    var staff_id=$(this).attr("value");
    if(confirm('Are you susre you want to Inactive this Staff')){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Mystaffs/inactiveStaff/",
        data: ({
          staff_id: staff_id
        }),
        cache: false,
        success: function(html){
          $('.delete_staff' + staff_id).fadeOut('slow');
          load_data();
        }
      });
    }
  });
  $(document).on('click', '.active', function()
  {
    var staff_id=$(this).attr("value");
    if(confirm('Are you susre you want to Active this Staff')){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Mystaffs/activeStaff/",
        data: ({
          staff_id: staff_id
        }),
        cache: false,
        success: function(html){
          $('.delete_staff' + staff_id).fadeOut('slow');
          load_data();
        }
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#delete_staff', function()
  {
    var staff_id=$(this).attr("value");
    if(confirm('Are you susre you want to delete this Staff')){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Mystaffs/deleteStaff/",
        data: ({
          staff_id: staff_id
        }),
        cache: false,
        beforeSend: function() {
          $('.delete_staff' + staff_id).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
          );
        },
        success: function(html){
         $('.delete_staff' + staff_id).fadeOut('slow');
        }
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).on('click', '.resetStaffPassword', function() {
    var editedId = $(this).attr("id");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Mystaffs/resetStaffPassword/",
      data: ({
        editedId: editedId
      }),
      cache: false,
      beforeSend: function() {
        $('.resetPasswordInfo').html( 'Reseting...');
      },
      success: function(html) {
        $(".resetPasswordInfo").html(html);
      }
    });
  });
</script>
<script type="text/javascript">
  function calculateTotal() {
    var Net_Salary; 
    var gross_sallary;
    var taxableincome;
    var taxable_income=document.formemp.ti.value;
    var quality_allowance=document.formemp.ql.value;
    var transport_allowance=document.formemp.tl.value;
    var home_allowance=document.formemp.hl.value;
    var position_allowance=document.formemp.pl.value;
    var basic_sallary=document.formemp.gs.value;
    var T1=eval(basic_sallary) + eval(position_allowance);
    var T2=document.formemp.tl.value;
    gross_sallary=eval(quality_allowance) + eval(transport_allowance) + eval(home_allowance) + eval(position_allowance) + eval(basic_sallary);
    taxableincome=eval(basic_sallary) + eval(position_allowance);
    document.getElementById('gross_sallary').value = gross_sallary;
    document.getElementById('ti').value = taxableincome;
    var P;
    var IT;
    if(T1==0)
    {
      alert('Please Enter Basic Salary');
    }
    else if(T1<=600){
      Net_Salary= eval(T2) ;}
    else if(T1 <=1650 && T1 >=601){
      IT=(T1*(10/100))-60;
      P=T1*(7/100);
      P2=T1*(11/100);
      Net_Salary=eval(T1-IT-P);}
    else if(T1 <=3200 && T1 >=1651){
      IT=(T1*(15/100))-142.5;
      P=T1*(7/100);
      P2=T1*(11/100);
      Net_Salary=eval(T1-IT-P);}
    else if(T1 <=5250 && T1 >=3201){
      IT=(T1*(20/100))-302.5;
      P=T1*(7/100);
      P2=T1*(11/100);
      Net_Salary=eval(T1-IT-P);}
    else if(T1 <=7800 && T1 >=5251){
      IT=(T1*(25/100))-565;
      P=T1*(7/100);
      P2=T1*(11/100);
      Net_Salary=eval(T1-IT-P);}
    else if(T1 <=10900 && T1 >=7801){
      IT=(T1*(30/100))-955;
      P=T1*(7/100);
      P2=T1*(11/100);
      Net_Salary=eval(T1-IT-P);}
    else if(T1 >=10901){
      IT=(T1*(35/100))-1500;
      P=T1*(7/100);
      P2=T1*(11/100);
      Net_Salary=(T1-IT-P);
    }
    var gs_net_sallary=eval(Net_Salary) + eval(quality_allowance) + eval(transport_allowance) + eval(home_allowance);
      document.getElementById('tl').innerHTML = Net_Salary;
      document.getElementById('ns').value = gs_net_sallary;
      document.getElementById('income_tax').value = IT;
      document.getElementById('pension_7').value = P;
      document.getElementById('pension_11').value = P2;
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
</html>