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
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <style type="text/css">
    .tree ul {
    padding-top: 20px; position: relative;

    transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
    }
    .tree ul ul ul::before {
        content: '';
        position: absolute; left: 50%; bottom: -20px;
        border-left: 1px solid #ccc;
        height: 20px;
    }

    .tree li {
        float: left; text-align: center;
        list-style-type: none;
        position: relative;
        padding: 20px 5px 0 5px;

        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    /*We will use ::before and ::after to draw the connectors*/

    .tree li::before, .tree li::after{
        content: '';
        position: absolute; top: 0; right: 50%;
        border-top: 1px solid #ccc;
        width: 50%; height: 20px;
    }
    .tree li::after{
        right: auto; left: 50%;
        border-left: 1px solid #ccc;
    }

    /*We need to remove left-right connectors from elements without 
    any siblings*/
    .tree li:only-child::after, .tree li:only-child::before {
        display: none;
    }

    /*Remove space from the top of single children*/
    .tree li:only-child{ padding-top: 0;}

    /*Remove left connector from first child and 
    right connector from last child*/
    .tree li:first-child::before, .tree li:last-child::after{
        border: 0 none;
    }
    /*Adding back the vertical connector to the last nodes*/
    .tree li:last-child::before{
        border-right: 1px solid #ccc;
        border-radius: 0 5px 0 0;
        -webkit-border-radius: 0 5px 0 0;
        -moz-border-radius: 0 5px 0 0;
    }
    .tree li:first-child::after{
        border-radius: 5px 0 0 0;
        -webkit-border-radius: 5px 0 0 0;
        -moz-border-radius: 5px 0 0 0;
    }

    /*Time to add downward connectors from parents*/
    .tree ul ul::before{
        content: '';
        position: absolute; top: 0; left: 50%;
        border-left: 1px solid #ccc;
        width: 0; height: 20px;
    }

    .tree li a{
        border: 1px solid #ccc;
        padding: 15px 20px;
        text-decoration: none;
        color: #666;
        font-size: 18px;
        display: inline-block;

        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;

        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    /*Time for some hover effects*/
    /*We will apply the hover effect the the lineage of the element also*/
    .tree li a:hover, .tree li a:hover+ul li a {
        background: #c8e4f8; color: #3abaf4; border: 1px solid #94a0b4;
    }


    .tree ul ul ul ul li a {
        position: relative;
        left: 50%;
        transform: translateX(-50%);
        padding: 5px 10px;
        text-decoration: none;
        color: #666;
        font-size: 11px;
        border: 1px solid #ccc;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
        writing-mode: vertical-rl !important; /* Vertical orientation */
        text-orientation: mixed !important;
        white-space: nowrap; /* Prevent text from wrapping */
    }
  </style>
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
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <!-- <div class="card-header">
                    <div class="row">
                      <div class="col-md-6 col-8">
                        <h5 class="card-title">User Group</h5>
                      </div>
                      <div class="col-md-6 col-4">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                        <span class="text-black">
                        <i data-feather="printer"></i>
                        </span>
                       </button>
                      </div>
                    </div>
                  </div> -->
                  <div class="card-body StudentViewTextInfo">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#addUserGroup" role="tab" aria-selected="true"> Manage User Group</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#usergroupHierarchy" role="tab" aria-selected="false">User Group Hierarchy</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="addUserGroup" role="tabpanel" aria-labelledby="home-tab1">
                        <a href="#" class="addNewUserGroup" value="" data-toggle="modal" data-target="#addUserGroupPage"><span class="text-black">
                          <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add User Group</button>
                        </span>
                        </a>
                        <div class="lisGroup" id="helloGroup"> </div>
                      </div>
                      <div class="tab-pane fade show" id="usergroupHierarchy" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="row">
                          <div class="col-lg-12 col-md-12 col-12">
                            <a href="#" class="addNewUserGroupHierarchy" value="" data-toggle="modal" data-target="#addUserGroupHierarchy"><span class="text-black">
                              <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Adjust Group Hierarchy</button>
                            </span>
                            </a>
                          </div>
                          <div class="col-lg-12 col-md-12 col-12">
                            <div class="GroupHierarchy" id="helloGroupHierarchy"> </div>
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
  <div class="modal fade" id="addUserGroupHierarchy" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Adjust User Group Hierarchy</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="page_4_user_group_heirarchy"></div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="addUserGroupPage" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add User Group</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="form-group">
               <input type="text" name="usergroup" class="form-control" id="usergroup" placeholder="Group name(Admin, Teacher etc)...">
              </div>
            </div>
            <div class="col-md-4 col-12">
              <button class="btn btn-primary btn-block saveGroup">Save Group
              </button>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
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
    var print_div = document.getElementById("helloGroup");
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
    load_data_heirarchy();
    $(document).on('click', '.addNewUserGroupHierarchy', function() {
      $.ajax({
        url:"<?php echo base_url(); ?>usergroup/fetchUserGroup_heirarchy/",
        method:"POST",
        beforeSend: function() {
          $('.page_4_user_group_heirarchy').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.page_4_user_group_heirarchy').html(data);
        }
      });
    });
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>usergroup/fetchUserGroup/",
        method:"POST",
        beforeSend: function() {
          $('.lisGroup').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.lisGroup').html(data);
        }
      })
    }
    function load_data_heirarchy()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>usergroup/showUserGroup_heirarchy/",
        method:"POST",
        beforeSend: function() {
          $('.GroupHierarchy').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('.GroupHierarchy').html(data);
        }
      })
    }
    $(document).on('change', '.groupUserHeirarchy', function() {
      var usergroupLevel=$(this).find('option:selected').attr('value');
      var usergroup=$(this).find('option:selected').attr('class');
      $.ajax({
        url: "<?php echo base_url(); ?>usergroup/saveGroupHeirarchy/",
        method: "POST",
        data: ({
          usergroupLevel:usergroupLevel,
          usergroup: usergroup
        }),
        beforeSend: function() {
          $('.lisGroup').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          load_data_heirarchy();
          swal('Status Changed Successfully!', {
            icon: 'success',
          });
        }
      }) 
    });
    $('.saveGroup').on('click', function() {
      var usergroup=$('#usergroup').val();
      if ($('#usergroup').val() != '') {
        $.ajax({
          url: "<?php echo base_url(); ?>usergroup/saveGroup/",
          method: "POST",
          data: ({
            usergroup: usergroup
          }),
          beforeSend: function() {
            $('.lisGroup').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $("#usergroup").val('');
            load_data();
          }
        })
      }else {
        swal('All fields are required!', {
          icon: 'error',
        });
      }
    });
    $(document).on('change', '.groupUserLevel', function() {
      var usergroupLevel=$(this).find('option:selected').attr('value');
      var usergroup=$(this).find('option:selected').attr('class');
      $.ajax({
        url: "<?php echo base_url(); ?>usergroup/saveGroupLevel/",
        method: "POST",
        data: ({
          usergroupLevel:usergroupLevel,
          usergroup: usergroup
        }),
        beforeSend: function() {
          $('.lisGroup').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          load_data();
          swal('Status Changed Successfully!', {
            icon: 'success',
          });
        }
      }) 
    });
    $(document).on('click', '.deleteUserGroup', function() {
    var ugid=$(this).attr('id');
    swal({
      title: 'Are you sure you want to delete this Group Name?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
      $.ajax({
        url: "<?php echo base_url(); ?>usergroup/deleteGroup/",
        method: "POST",
        data: ({
          ugid: ugid
        }),
        beforeSend: function() {
          $('.lisGroup').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          load_data();
        }
      })
    }
    });
  });
  $(document).on('click', "input[name='accessOtherBranch']", function() {
    var groupName = $(this).attr("value");
    if($(this).is(':checked')){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>usergroup/feedAccessOtherBranch/",
          data: ({
            groupName: groupName
          }),
          cache: false,
          success: function(html) {
            swal('Status Changed Successfully!', {
                icon: 'success',
            });
          }
        });
    }else{
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>usergroup/deleteAccessOtherBranch/",
        data: ({
          groupName: groupName
        }),
        cache: false,
        success: function(html) {
          swal('Status Changed Successfully!', {
            icon: 'success',
          });
        }
      });
    }
  });
});
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