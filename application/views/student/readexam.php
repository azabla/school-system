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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/selectric.css">
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
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-body StudentViewTextInfo">
                    <?php foreach($readlesson as $read_mores){
                      $inti =60*($read_mores->examinute);
                    ?>
                      <script type="text/javascript">
                      var initialTime =<?php echo $inti;?>//Place here the total of seconds you receive on your PHP code. ie: var initialTime = <? echo $remaining; ?>;
                      var seconds = initialTime;
                      function timer() {
                        var days = Math.floor(seconds/24/60/60);
                        var hoursLeft   = Math.floor((seconds) - (days*86400));
                        var hours       = Math.floor(hoursLeft/3600);
                        var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
                        var minutes     = Math.floor(minutesLeft/60);
                        var remainingSeconds = seconds % 60;
                        if (remainingSeconds < 10) {
                          remainingSeconds = "0" + remainingSeconds;
                        }
                        document.getElementById('countdown').innerHTML = minutes + ": Minutes & " + remainingSeconds+ " :Seconds left";
                        if (seconds == 0) {
                          clearInterval(countdownTimer);
                          document.getElementById('countdown').innerHTML = "Your allowed time has been finished.";
                          document.getElementById('sendanswer').disabled = "disabled";
                        } else {
                          seconds--;
                        }
                      }
                      var countdownTimer = setInterval('timer()', 1000);
                      </script>
                    <?php } ?>
                    
                    <div class="inbox-body no-pad">
                      <h4>
                      <span id="countdown" class="timer text-danger">
                      </span>
                    </h4>
                    <form method="POST" action="<?php echo base_url() ?>myexam/">
                      <?php $no =1;foreach($readlesson as $read_mores){ ?>
                      <section class="mail-list">
                        <div class="view-mail">
                          <small class="text-muted">Q<?php echo $no;?>.</small>
                          <?php echo $read_mores->question; ?>
                        </div>
                        <div class="row">
                        <div class="col-lg-3 col-6">
                        <p>A.
                          <?php echo $read_mores->a; ?>
                        </p>
                       </div>
                       <div class="col-lg-3 col-6">
                        <p>B.
                          <?php echo $read_mores->b; ?>
                        </p>
                       </div>
                       <?php if($read_mores->c!=''){ ?>
                       <div class="col-lg-3 col-6">
                        <p>C.
                          <?php echo $read_mores->c; ?>
                        </p>
                       </div>
                      <?php } ?>
                      <?php if($read_mores->d!=''){ ?>
                       <div class="col-lg-3 col-6">
                        <p>D.
                          <?php echo $read_mores->d; ?>
                        </p>
                       </div>
                       <?php } ?>
                       <input type="hidden" name="eid[ ]" value="<?php echo $read_mores->eid; ?>">
                       <input type="hidden" name="subject" value="<?php echo $read_mores->subject; ?>">
                       <input type="hidden" name="examname" value="<?php echo $read_mores->examname; ?>">
                       <div class="col-lg-12">
                       <div class="form-group">
                        <small class="text-muted">
                          <label for="usertype">Select the correct answer below</label>
                          <select class="form-control selectric" name="myanswer[ ]" id="myanswer" required="required">
                          <option> </option>
                          <option> <?php echo $read_mores->a; ?></option>
                          <option> <?php echo $read_mores->b; ?></option>
                          <option> <?php echo $read_mores->c; ?></option>
                          <option> <?php echo $read_mores->d; ?></option>
                          </select>
                          </small>
                         </div>
                        </div>
                       </div>
                      </section>
                     <?php $no++; }?>
                     <button type="submit" name="sendanswer" id="sendanswer" class="btn btn-info pull-right">Submit Answer</button>
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
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
  <script src="<?php echo base_url(); ?>assets/pages/jquery.selectric.min.js"></script>
</body>
</html>