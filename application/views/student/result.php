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
                    <?php $no =1;$total_weight=0;$correctAnswer=0;
                    foreach($exam as $read_mores){  
                      $examName=$read_mores->examname; 
                      $examGroup=$read_mores->examGroup;
                      $subject=$read_mores->subject;
                      $grade=$read_mores->grade;
                      $academicyear=$read_mores->academicyear;
                      $ans2=$read_mores->ans;
                      $weight=$read_mores->question_weight;
                      $total_weight=$total_weight + $weight;?>
                      <div class="support-ticket">
                        <div class="media-body ml-1">
                          <div class="badge badge-pill badge-light mb-1 float-right"><?php echo $weight;?> pts.</div>
                          <span class="font-weight-bold">Q<?php echo $no;?>.
                          <?php echo nl2br(html_entity_decode($read_mores->question)); ?></span>
                          <?php $this->db->where(array('subject'=>$subject));
                          $this->db->where(array('academicyear'=>$academicyear));
                          $this->db->where(array('grade'=>$grade));
                          $this->db->where(array('examname'=>$examName));
                          $this->db->where(array('examGroup'=>$examGroup));
                          $this->db->group_by('eid,examGroup');
                          $queryFetch=$this->db->get('exam');
                          foreach($queryFetch->result() as $row_choice) { 
                            $examGroupNow=$row_choice->examGroup;
                            $eidNow=$row_choice->eid;
                            $answer=$row_choice->answer;
                            $optionChoice=$row_choice->a;?>
                            <?php if($answer=='' && $optionChoice == ''){ ?>
                              <ul>
                                <li class="text-info"><?php echo htmlentities($optionChoice) ?></li>
                              </ul>
                            <?php } elseif($answer == '' && $optionChoice != ''){ ?>
                              <ul>
                                <li><?php echo htmlentities($optionChoice) ?></li>
                              </ul>
                            <?php } elseif($answer != '' && $optionChoice == ''){?>
                              <ul style="background-color: #DFF5E5;">
                                <li><?php echo htmlentities($answer) ?></li>
                              </ul>
                            <?php } else if(strcasecmp($answer, $optionChoice) === 0 && trim($answer) === trim($optionChoice) && strcmp($answer, $optionChoice) === 0){ ?>
                              <ul style="background-color: #DFF5E5;">
                                <li><?php echo htmlentities($optionChoice) ?></li>
                              </ul>
                              <?php } else{ ?>
                                <ul>
                                  <li><?php echo htmlentities($optionChoice) ?></li>
                                </ul>
                            <?php } } $no++; ?>
                            <?php if(strcasecmp($ans2, $answer) === 0 && trim($ans2) === trim($answer) && strcmp($ans2, $answer) === 0){ 
                              $correctAnswer=$correctAnswer + $weight; ?>
                            <?php } else{ ?>
                              <span class="text-danger">
                              Your answer was <i class="fas fa-chevron-right"></i> <?php echo $read_mores->ans; ?><i data-feather="x"></i>
                              </span>
                            <?php } ?>
                            <hr>
                          </div>
                        </div>
                      <?php }?>
                      <?php if($correctAnswer==$total_weight){ ?>
                        <span class="badge badge-success pull-right"><h4 class="pull-right">Total Results: <?php echo $correctAnswer; echo '/'; echo $total_weight; ?></h4></span>
                      <?php } else{?>
                        <span class="badge badge-primary pull-right"><h4 class="pull-right">Total Results: <?php echo $correctAnswer; echo '/'; echo $total_weight; ?></h4></span>
                      <?php }?>
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
  <script src="<?php echo base_url(); ?>assets/pages/jquery.selectric.min.js"></script>
</body>
</html>