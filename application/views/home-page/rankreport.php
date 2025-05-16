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
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#sectionRank" role="tab" aria-selected="true"> Section Rank</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#gradeRank" role="tab" aria-selected="false">Grade Rank</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#branchRankReport" role="tab" aria-selected="false">Branch Rank</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="sectionRank" role="tabpanel" aria-labelledby="home-tab1">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()"> <span class="text-black"> <i data-feather="printer"></i> </span>
                        </button>
                        <form id="comment_form">
                          <div class="row">
                           <div class="col-md-2 col-6">
                             <div class="form-group">
                               <select class="form-control selectric" required="required" name="quarter" id="rank_quarter">
                                <option>---Quarter---</option>
                               <option value="All">All</option>
                                <?php foreach($fetch_term as $fetch_term){ ?>
                                  <option value="<?php echo $fetch_term->term;?>">
                                  <?php echo $fetch_term->term;?>
                                  </option>
                                <?php }?>
                               </select>
                              </div>
                             </div>
                            <div class="col-md-2 col-6">
                              <div class="form-group">
                               <select class="form-control selectric" required="required" name="branch" id="rank_branch">
                               <option>--- Branch --- </option>
                                <?php foreach($branch as $branchs){ ?>
                                  <option value="<?php echo $branchs->name;?>">
                                  <?php echo $branchs->name;?>
                                  </option>
                                <?php }?>
                               </select>
                              </div>
                             </div>
                            <div class="col-md-4 col-6 table-responsive" id="rank_gradesec" style="height:15vh;"> 
                            </div>
                            <div class="col-md-2 col-6">
                              <div class="form-group">
                               <select class="form-control" required="required" name="rank_top" id="rank_top">
                               <option> --- Top --- </option>
                               <option> All </option>
                               <?php for($i=1;$i<=20;$i++){?>
                                <option><?php echo $i ?></option>
                               <?php } ?>
                               </select>
                              </div>
                            </div>
                            <div class="col-md-2 col-12">
                              <button class="btn btn-primary btn-block" type="submit" name="getrank">
                              Get Rank
                              </button>
                            </div>
                          </div>
                        </form>
                        <div class="rankList" id="helloRank"> </div>
                      </div>
                      <div class="tab-pane fade show" id="gradeRank" role="tabpanel" aria-labelledby="home-tab2">
                         <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyGrade()"> <span class="text-black"> <i data-feather="printer"></i> </span>
                        </button>
                        <form id="comment_formGrade">
                          <div class="row">
                            <div class="col-md-3 col-6">
                              <div class="form-group">

                                <div class="row">
                                  <?php foreach($fetch_terms as $fetch_term){ ?>
                                  <div class="col-md-12 col-12">
                                      <div class="pretty p-icon p-jelly p-bigger">
                                        <input type="checkbox" name="rank_quarterGrade" value="<?php echo $fetch_term->term; ?>" id="rank_quarterGrade">
                                        <div class="state p-info">
                                          <i class="icon material-icons"></i>
                                          <label></label><?php echo $fetch_term->term; ?>
                                        </div>
                                      </div>
                                  </div>
                                  <?php } ?>
                                </div>

                                <!-- <select class="form-control selectric" required="required" name="quarter" id="rank_quarterGrade">
                                 <option>---Quarter---</option>
                                 <option value="All">All</option>
                                  <?php foreach($fetch_terms as $fetch_term){ ?>
                                    <option value="<?php echo $fetch_term->term;?>">
                                    <?php echo $fetch_term->term;?>
                                    </option>
                                  <?php }?>
                                </select> -->
                              </div>
                            </div>
                            <div class="col-md-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="branch" id="rank_branchGrade">
                                 <option>--- Branch --- </option>
                                  <?php foreach($branch as $branchs){ ?>
                                    <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                    </option>
                                  <?php }?>
                                 </select>
                              </div>
                            </div>
                            <div class="col-md-2 col-6">
                              <div class="form-group">
                                <select class="form-control" required="required" name="gradesec" id="rank_gradesecGrade">
                                 <option> --- Grade --- </option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-2 col-6">
                              <div class="form-group">
                                <select class="form-control" required="required" name="rank_top" id="rank_topGrade">
                                 <option> --- Top --- </option>
                                 <option> All </option>
                                 <?php for($i=1;$i<=20;$i++){?>
                                  <option><?php echo $i ?></option>
                                 <?php } ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-2 col-12">
                              <button class="btn btn-primary btn-block" type="submit" name="getrank">
                                Get Rank
                              </button>
                            </div>
                          </div>
                         </form>
                        <div class="listGrade" id="helloGrade"> </div>
                      </div>
                      <div class="tab-pane fade show" id="branchRankReport" role="tabpanel" aria-labelledby="home-tab3">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyBranch()"> <span class="text-black"> <i data-feather="printer"></i> </span>
                        </button>
                        <form id="comment_formBranch">
                          <div class="row">
                            <div class="col-md-3 col-6">
                              <div class="form-group">
                                <select class="form-control selectric" required="required" name="quarter" id="rank_quarterBranch">
                                 <option>---Quarter---</option>
                                  <?php foreach($fetch_termss as $fetch_termg){ ?>
                                    <option value="<?php echo $fetch_termg->term;?>">
                                    <?php echo $fetch_termg->term;?>
                                    </option>
                                  <?php }?>
                                 </select>
                              </div>
                            </div>
                            <div class="col-md-3 col-6">
                              <div class="form-group">
                                <select class="form-control" required="required" name="gradesec" id="rank_gradesecBranch">
                                 <option> --- Grade --- </option>
                                  <?php foreach($gradesec as $gradesecs){ ?>
                                    <option value="<?php echo $gradesecs->gradesec;?>">
                                    <?php echo $gradesecs->gradesec;?>
                                    </option>
                                  <?php }?>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-3 col-6">
                              <div class="form-group">
                                <select class="form-control" required="required" name="rank_top" id="rank_topBranch">
                                  <option> --- Top --- </option>
                                  <option> All </option>
                                  <?php for($i=1;$i<=20;$i++){?>
                                    <option><?php echo $i ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-3 col-6">
                              <button class="btn btn-primary btn-block" type="submit" name="getrank">
                                Get Rank
                              </button>
                            </div>
                          </div>
                        </form>
                        <div class="listBranch" id="helloBranch"> </div>
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
</body>
<script type="text/javascript">
   $('#comment_formBranch').on('submit', function(event) {
    event.preventDefault();
      var gradesec=$("#rank_gradesecBranch").val();
      var quarter=$("#rank_quarterBranch").val();
      var top=$("#rank_topBranch").val()
      if($("#gradesec").val()!='' && $("#branch").val()!='' && $("#quarter").val()!=''){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Rankreport/fetchTopBranchRank/",
        data: ({
          gradesec:gradesec,
          quarter:quarter,
          top:top
        }),
        beforeSend: function() {
          $('.listBranch').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".listBranch").html(data);
        }
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#rank_branchGrade").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filterbranchgrade/",
        data: "branchit=" + $("#rank_branchGrade").val(),
        beforeSend: function() {
          $('#rank_gradesecGrade').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#rank_gradesecGrade").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
   $('#comment_formGrade').on('submit', function(event) {
    event.preventDefault();
      var gradesec=$("#rank_gradesecGrade").val();
      var branch=$("#rank_branchGrade").val();
      quarter=[];
      $("input[name='rank_quarterGrade']:checked").each(function(i){
        quarter[i]=$(this).val();
      });
      var top=$("#rank_topGrade").val();
      if(quarter.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Rankreport/fetchTopRankGrade/",
        data: ({
          gradesec:gradesec,
          branch:branch,
          quarter:quarter,
          top:top
        }),
        beforeSend: function() {
          $('.listGrade').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".listGrade").html(data);
        }
      });
    }else{
      swal({
        title: 'Please select all necessary fields.',
        text: '',
        icon: 'error',
        buttons: true,
        dangerMode: true,
      })
    }
  });
</script>
<script type="text/javascript">
  function codespeedyGrade(){
    var print_div = document.getElementById("helloGrade");
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
  function codespeedyBranch(){
    var print_div = document.getElementById("helloBranch");
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
  $(document).ready(function() {  
    $("#rank_branch").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Rankreport/filterGradeFromBranch4Rank/",
        data: "branchit=" + $("#rank_branch").val(),
        beforeSend: function() {
          $('#rank_gradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#rank_gradesec").html(data);
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
  function codespeedy(){
    var print_div = document.getElementById("helloRank");
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
   $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    gradesec=[];
    $("input[name='sectionRankGrandstande']:checked").each(function(i){
      gradesec[i]=$(this).val();
    });
    var branch=$("#rank_branch").val();
    var quarter=$("#rank_quarter").val();
    var top=$("#rank_top").val()
    if(gradesec.length!=0 && $("#branch").val()!='' && $("#quarter").val()!=''){
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Rankreport/fetchTopRank/",
        data: ({
          gradesec:gradesec,
          branch:branch,
          quarter:quarter,
          top:top
        }),
        beforeSend: function() {
          $('.rankList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".rankList").html(data);
        }
      });
    }else{
      swal({
        title: 'Please select all necessary fields.',
        text: '',
        icon: 'error',
      })
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
</html>