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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pages/summernote-bs4.css">
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
                      <?php $no =1;foreach($readlesson as $read_mores){ ?>
                        <div class="view-mail">
                          <small class="text-muted">Question: <?php echo $no;?> .</small> <?php echo $read_mores->question; ?>
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
                      </div>
                      <?php $no++; }?>
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
  <script src="<?php echo base_url(); ?>assets/pages/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</body>
</html>