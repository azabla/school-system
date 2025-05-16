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
  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
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
            <div class="row">
              <div class="col-12">
                <form action="<?php echo base_url()?>category/" method="POST">
                      <h4>
                      <?php if(isset($_SESSION['success'])){ ?>
                          <span class="text-success">
                              <?php echo $_SESSION['success']; ?>
                          </span>
                          <?php  }
                          elseif(isset($_SESSION['error'])) { ?>
                          <span class="text-danger">
                              <?php echo $_SESSION['error']; ?>  
                          </span>
                      <?php } ?>
                      </h4>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                        <label for="evname">Payment Category Name
                        </label>
                        <input class="form-control" name="pcname"
                         required="required" type="text" placeholder="Payment Category Name here...">
                       </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="form-group">
                         <label for="amount">Amount(Br.)</label>
                          <input type="number"
                          class="form-control"
                            required="required" name="amount" 
                            id="amount">
                            <span class="text-danger"> 
                            <?php echo form_error('amount'); ?>
                            </span>
                        </div>
                     </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                         <label for="ac">Academic year</label>
                          <select class="form-control selectric"
                            required="required" name="acy" 
                            id="acy">
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option>
                                <?php echo $academicyears->year_name ?>
                              </option>
                            <?php } ?>
                          </select>
                            <span class="text-danger"> 
                            <?php echo form_error('acg'); ?>
                            </span>
                        </div>
                     </div>
                  </div>
                   <div class="row">
                    <div class="col-lg-6">
                       <div class="form-group">
                        <label for="Mobile">
                        </label>
                          <?php foreach($grade as $grades){ ?>
                            <?php echo $grades->grade; ?>
                           <input type="checkbox" name="grade[ ]" 
                           value="<?php echo $grades->grade; ?>" id="">&nbsp;&nbsp;&nbsp;&nbsp;
                          <?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                       <div class="form-group">
                        <label for="Mobile">
                        </label>
                          <?php foreach($month as $months){ ?>
                            <?php echo $months->name; ?>
                           <input type="checkbox" name="month[ ]" 
                           value="<?php echo $months->name; ?>" id="">&nbsp;&nbsp;&nbsp;&nbsp;
                          <?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                     <button type="submit" name="postpc" class="btn btn-info ">Save Category
                      </button>
                    </div>
                  </div>
                  </div>
                  </form>
                <div class="card">
                  <div class="card-header">
                    <h4>Payment Category List</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-2">
                        <div class="list-group" id="list-tab" role="tablist">
                        <?php foreach($grade as $grades) { ?>  
                        <a class="list-group-item list-group-item-action gradeshere" id="<?php echo $grades->grade; ?>" data-toggle="list" href="#" role="tab">
                        <?php echo $grades->grade;?>
                        </a>
                        <?php } ?>
                        </div>
                      </div> 
                      <div class="col-10" id="list-tabb">
                        <small>Select grade you want</small>
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
<script type="text/javascript">
  $(document).on('click', '.gradeshere', function() {
    var postgrade = $(this).attr("id");
      $.ajax({
        url: "<?php echo base_url(); ?>fetch_thisgrade_paymentcategory/",
        method: "POST",
        data: ({
          postgrade: postgrade
        }),
        cache: false,
        beforeSend: function() {
          $('#list-tabb').html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(html) {
          $('#list-tabb').html(html);
        }
      });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '.deleteecategory', function() {
    var postgrade = $(this).attr("id");
      if (confirm("Are you sure you want to delete this Category ?")) {
            $.ajax({
                method: "POST",
                url: "<?php echo base_url(); ?>category",
                data: ({
                    postgrade: postgrade
                }),
                cache: false,
                success: function(html) {
                  $(".delete_mem" + postgrade).fadeOut('slow');
                }
            });
        } else {
            return false;
        }
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
</body>

</html>