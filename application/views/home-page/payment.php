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
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#addPayment" role="tab" aria-selected="true">Add payment</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#paymentReport" role="tab" aria-selected="false">Payment Report</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="addPayment" role="tabpanel" aria-labelledby="home-tab1">
                    <p class="infoPageSave"></p>
                    
                    <div class="row">
                      <div class="col-lg-2 col-4">
                        <div class="form-group">
                         <label for="ac">Academic year</label>
                          <select class="form-control selectric" required="required" name="acy"  id="acy">
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
                      <div class="col-lg-2 col-4">
                        <div class="form-group">
                          <label for="Mobile">Select Grade</label>
                          <select class="form-control selectric" required="required" name="gradesec"  id="gradesecPay">
                          <option></option>
                          <?php foreach($gradesec as $gradesecs){ ?>
                            <option value="<?php echo $gradesecs->gradesec;?>">
                            <?php echo $gradesecs->gradesec;?>
                            </option>
                          <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-4">
                        <div class="form-group">
                          <label for="Mobile">Receipt</label>
                          <input type="text" id="receiptPay" name="receipt" class="form-control">
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                        <label for="Mobile">Select Payment Type</label>
                          <select class="form-control selectric" required="required" name="ptype" id="ptype">
                          <option></option>
                          <?php foreach($payment_category as $payment_categorys){ ?>
                            <option value="<?php echo $payment_categorys->name;?>">
                            <?php echo $payment_categorys->name;?>
                            </option>
                          <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                        <label for="Mobile">Select Month</label>
                         <select class="form-control selectric" required="required" name="month" id="monthPaid">
                         <option></option>
                          <?php foreach($month as $months){ ?>
                            <option value="<?php echo $months->name;?>">
                            <?php echo $months->name;?>
                            </option>
                          <?php }?>
                         </select>
                        </div>
                     </div>
                  </div>
                  <div class="listHere"></div>
              </div>
              <div class="tab-pane fade show" id="paymentReport" role="tabpanel" aria-labelledby="home-tab2">
                <div class="card">
                  <div class="card-header">
                    <div class="table-responsive">
                      <form action="<?php echo base_url()?>payment/" method="POST">
                      <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                        <thead>
                          <tr>
                            <th>Student Name</th>
                            <th>Grade</th>
                            <th>Payment Type</th>
                            <th>Paid Month</th>
                            <th>Receipt</th>
                            <th>Status</th>
                            <th>Date Inserted</th>
                            <th>User</th>
                            <th>Method</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                           <?php foreach($payment as $payments){ ?>
                          <tr>
                            <td><img src="<?php echo base_url(); ?>/profile/<?php echo $payments->profile; ?>"style="width: 23px;height: 23px;border-radius: 3em;"> <?php echo $payments->fname;echo ' ';echo $payments->mname ?></td>
                            <td><?php echo $payments->gradesecc; ?></td>
                            <td><?php echo $payments->paymentype; ?></td>
                            <td><?php echo $payments->month; ?></td>
                            <td><?php echo $payments->payment_receipt; ?></td>
                            <td><span class="text-success">
                              <i data-feather="check"></i>Paid</span>
                           </td>
                            <td><?php echo $payments->date_created; ?></td>
                            <td><?php echo $payments->byuser; ?></td>
                            <td><?php echo $payments->method; ?></td>
                            <td>
                              <button type="submit" 
                              onclick="return confirm('Are you sure you want to delete this Payment ?')"
                              value="<?php  echo $payments->pid; ?>" name="deletepayment" class="btn btn-danger">Delete</button>
                            </td>
                          </tr>
                          <?php }?>
                        </tbody>
                      </table>
                    </form>
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
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
<script type="text/javascript">
    $(document).on('click', '.payPayment', function() {
      event.preventDefault();
      id=[];
      $("input[name='paidid']:checked").each(function(i){
        id[i]=$(this).val();
      });
      var academicyear=$('#acy').val();
      var gradesec=$('#gradesecPay').val();
      var receipt=$('#receiptPay').val();
      var paymentType=$('#ptype').val();
      var month=$('#monthPaid').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>payment/save_payment/",
        data: ({
          academicyear: academicyear,
          gradesec:gradesec,
          receipt:receipt,
          paymentType:paymentType,
          month:month,
          id:id
        }),
        beforeSend: function() {
          $('.infoPageSave').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
            );
        },
        success: function(data) {
          $(".infoPageSave").html(data);
        }
      });
    });
   $(document).ready(function() {  
    $("#gradesecPay").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_gradesec_for_payment/",
        data: "gradesec=" + $("#gradesecPay").val(),
         beforeSend: function() {
            $('.listHere').html(
                '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
            $(".listHere").html(data);
        }
      });
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
</body>

</html>