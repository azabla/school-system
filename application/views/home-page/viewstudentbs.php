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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
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
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#showBasicSkill" role="tab" aria-selected="true">Feed BS(Option1) </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab4" data-toggle="tab" href="#feedBasicSkill" role="tab" aria-selected="true">Feed BS(Option2) </a>
                  </li>
                  <?php if($enable_sub_category){ ?>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab6" data-toggle="tab" href="#feedBasicSkill2" role="tab" aria-selected="true">Feed BS(Option3) </a>
                  </li>
                  <?php } ?>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#notFilledStudent" role="tab" aria-selected="false">Incomplete Student</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#printReport" role="tab" aria-selected="false">Print Report</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab5" data-toggle="tab" href="#overallComment" role="tab" aria-selected="false">Overall Comment</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="showBasicSkill" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                        <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="fetchBs">
                      <div class="row">
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="bsacademicyear"  id="bsacademicyear">
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                                <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                              <select class="form-control" required="required" name="bsbranch"
                              id="bsbranch">
                                <option>--- Branch ---</option>
                                  <?php foreach($branch as $branchs) { ?>
                                  <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                  </option>
                                  <?php } ?>
                              </select>
                            </div>
                          </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesec" required="required"
                             name="bsgradesec" id="bsgradesec">
                              <option>--- Grade ---</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" 
                            name="bsquarter" id="bsquarter">
                              <option> </option>
                              <?php foreach($fetch_term as $fetch_terms){ ?>
                                <option value="<?php echo $fetch_terms->term;?>">
                                <?php echo $fetch_terms->term;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="viewmark">View
                          </button>
                        </div>
                      </div>
                    </form> 
                    <div class="listbs" id="listbs"></div>
                  </div>
                  <div class="tab-pane fade show" id="feedBasicSkill" role="tabpanel" aria-labelledby="home-tab4">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyFeed()">
                        <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="fetchBsFeed">
                      <div class="row">
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="bsacademicyearFeed"  id="bsacademicyearFeed">
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                                <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                              <select class="form-control" required="required" name="bsbranchFeed" id="bsbranchFeed">
                                <option>--- Branch ---</option>
                                  <?php foreach($branch as $branchs) { ?>
                                  <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                  </option>
                                  <?php } ?>
                              </select>
                            </div>
                          </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecFeed" required="required"
                             name="bsgradesecFeed" id="bsgradesecFeed">
                              <option>--- Grade ---</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" 
                            name="bsquarterFeed" id="bsquarterFeed">
                              <option> </option>
                              <?php foreach($fetch_term as $fetch_terms){ ?>
                                <option value="<?php echo $fetch_terms->term;?>">
                                <?php echo $fetch_terms->term;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="viewmark">Start
                          </button>
                        </div>
                      </div>
                    </form> 
                    <div class="listbsFeed" id="listbsFeed"></div>
                  </div>
                  <?php if($enable_sub_category){ ?>
                  <div class="tab-pane fade show" id="feedBasicSkill2" role="tabpanel" aria-labelledby="home-tab6">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyFeed2()">
                        <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="fetchBsFeed2">
                      <div class="row">
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="bsacademicyearFeed2"  id="bsacademicyearFeed2">
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                                <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                              <select class="form-control" required="required" name="bsbranchFeed2" id="bsbranchFeed2">
                                <option>--- Branch ---</option>
                                  <?php foreach($branch as $branchs) { ?>
                                  <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                  </option>
                                  <?php } ?>
                              </select>
                            </div>
                          </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecFeed2" required="required"
                             name="bsgradesecFeed2" id="bsgradesecFeed2">
                              <option>--- Grade ---</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" 
                            name="bsquarterFeed2" id="bsquarterFeed2">
                              <option> </option>
                              <?php foreach($fetch_term as $fetch_terms){ ?>
                                <option value="<?php echo $fetch_terms->term;?>">
                                <?php echo $fetch_terms->term;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="viewmark2">Start
                          </button>
                        </div>
                      </div>
                    </form> 
                    <div class="listbsFeed2" id="listbsFeed2"></div>
                  </div>
                  <?php } ?>
                  <div class="tab-pane fade show" id="notFilledStudent" role="tabpanel" aria-labelledby="home-tab2">
                    <span class="text-time text-danger">NB: If you do not find anything after selecting all the required fields on this tab, it means that all the basic skill fields are filled.</span>
                    <form method="POST" id="fetchNonFilledBs">
                      <div class="row">
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="bsacademicyearNonFilled"  id="bsacademicyearNonFilled">
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                                <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                              <select class="form-control" required="required" name="bsbranchNonFilled"
                              id="bsbranchNonFilled">
                                <option>--- Branch ---</option>
                                  <?php foreach($branch as $branchs) { ?>
                                  <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                  </option>
                                  <?php } ?>
                              </select>
                            </div>
                          </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecNonFilled" required="required"
                             name="bsgradesecNonFilled" id="bsgradesecNonFilled">
                              <option>--- Grade ---</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" 
                            name="bsquarterNonFilled" id="bsquarterNonFilled">
                              <option> </option>
                              <?php foreach($fetch_term as $fetch_terms){ ?>
                                <option value="<?php echo $fetch_terms->term;?>">
                                <?php echo $fetch_terms->term;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="showNonFilledStudent">Show All
                          </button>
                        </div>
                      </div>
                    </form>
                    <div class="listNonFilledBs" id="listNonFilledBs"></div>
                  </div>
                  <div class="tab-pane fade show" id="printReport" role="tabpanel" aria-labelledby="home-tab3">
                    <form method="POST" id="printReportPage">
                      <div class="row">
                        <div class="col-lg-12 col-12">
                          <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyPrint()">
                          <span class="text-black">
                            <i data-feather="printer"></i>
                          </span>
                          </button>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="bsacademicyearNonFilledReport"  id="bsacademicyearNonFilledReport">
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                                <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                              <select class="form-control" required="required" name="bsbranchNonFilledReport"
                              id="bsbranchNonFilledReport">
                                <option>--- Branch ---</option>
                                  <?php foreach($branch as $branchs) { ?>
                                  <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                  </option>
                                  <?php } ?>
                              </select>
                            </div>
                          </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecNonFilledReport" required="required"
                             name="bsgradesecNonFilledReport" id="bsgradesecNonFilledReport">
                              <option>--- Grade ---</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" 
                            name="bsquarterNonFilledReport" id="bsquarterNonFilledReport">
                              <option> </option>
                              <?php foreach($fetch_term as $fetch_terms){ ?>
                                <option value="<?php echo $fetch_terms->term;?>">
                                <?php echo $fetch_terms->term;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="showNonFilledStudent">Show All
                          </button>
                        </div>
                      </div>
                    </form>
                    <div class="listNonFilledBsReport" id="listNonFilledBsReport"></div>
                  </div>
                  <div class="tab-pane fade show" id="overallComment" role="tabpanel" aria-labelledby="home-tab5">
                    <?php if($_SESSION['usertype']===trim('superAdmin')){ ?>
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="add_New_Category" value="" data-toggle="modal" data-target="#add-new-category"><span class="text-success">
                        <button class="btn btn-info pull-right"><i data-feather="plus-circle"> </i>Total Comment Words</button>
                       </span>
                       </a>
                      </div>
                    </div>
                    <?php } ?>
                    <form method="POST" id="feedOverallComment">
                      <div class="row">
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="bsacademicyearOverallComment"  id="bsacademicyearOverallComment">
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                                <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                          <div class="col-lg-4 col-6">
                            <div class="form-group">
                              <select class="form-control" required="required" name="bsbranchOverallComment"
                              id="bsbranchOverallComment">
                                <option>--- Branch ---</option>
                                  <?php foreach($branch as $branchs) { ?>
                                  <option value="<?php echo $branchs->name;?>">
                                    <?php echo $branchs->name;?>
                                  </option>
                                  <?php } ?>
                              </select>
                            </div>
                          </div>
                        <div class="col-lg-5 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecOverallComment" required="required"
                             name="bsgradesecOverallComment" id="bsgradesecOverallComment">
                              <option>--- Grade ---</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" 
                            name="bsquarterOverallComment" id="bsquarterOverallComment">
                              <option> </option>
                              <?php foreach($fetch_term as $fetch_terms){ ?>
                                <option value="<?php echo $fetch_terms->term;?>">
                                <?php echo $fetch_terms->term;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="showOverallComment">Show All
                          </button>
                        </div>
                      </div>
                    </form>
                    <div class="listOverallComment" id="listOverallComment"></div>
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
  <div class="modal fade" id="add-new-category" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Total comment words</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="card-body StudentViewTextInfo">
            <div class="modal-body">
              <div class="Total_NumbersValue" id="Total_NumbersValue">
                
              </div>
              
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script> 
  $('#fetchBsFeed2').on('submit', function(event) {
    event.preventDefault();
    var branches=$('#bsbranchFeed2').val();
    var gradesec=$('.bsgradesecFeed2').val();
    var quarter=$('#bsquarterFeed2').val();
    if ($('.bsgradesecFeed2').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Viewstudentbs/fecthStudentBsFeed2/",
        method: "POST",
        data: ({
          branches: branches,
          gradesec:gradesec,
          quarter:quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listbsFeed2').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listbsFeed2").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  total_comment_words();
  function total_comment_words()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>Viewstudentbs/total_comment_words/",
      method:"POST",
      beforeSend: function() {
        $('#Total_NumbersValue').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('#Total_NumbersValue').html(data);
      }
    })
  }
  $(document).on('submit', '#save_total_comment_words', function(event) {
      event.preventDefault();
      var TotalWords=$('#save_total_comment_words_here').val();
      if($('#save_total_comment_words_here').val() =='' )
      {
        swal('Oooops, Please type number.!', {
          icon: 'error',
        });
      }else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Viewstudentbs/saveTotalCommentWords/",
          data: ({
            TotalWords:TotalWords
          }),
          cache: false,
          success: function(html){
            total_comment_words();
            iziToast.success({
              title: 'Saved successfully',
              message: '',
              position: 'topRight'
            });
          }
        });
      }
    });
  $(document).ready(function () { 
    $(document).on('keyup', 'textarea:input[name=teacher_ocomment_gs]', function() {
      var stuid=$(this).attr('title');
      var max_length = $("#totalWordsFORComments").val(); 
      var words = 0;
        if ((this.value.match(/\S+/g)) != null) {
          words = this.value.match(/\S+/g).length;
        }
        if (words > max_length) {
          var trimmed = $(this).val().split(/\s+/, max_length).join(" ");
          $(this).val(trimmed + " ");
        }
        else {
          $('#totalWordsLeft' + stuid).text(words + " Words left");
          $('#totalWordsLeft' + stuid).text(max_length-words + " Words left");
        }

      /*let str = $.trim($(this).val()).split(" ");
      var len = max_length - str.length; 
      if(len==0){
        str.pop(); 
        $('.count_words' + stuid).attr("disabled","disabled");
      }*/
      $('#totalWordsLeft' + stuid).text(len + " Words left"); 
    }); 
  }); 
</script> 
<script type="text/javascript">
  $(document).on('click', '#submitTeacheroComment', function() {
    var academicyear=$("#academicyearTocomment").val();
    var quarter=$("#quarterTocomment").val();
    var markGradeSec=$("#markGradeSecTocomment").val();
    var markGradeSecBranch=$("#markGradeSecBranchTocomment").val();
    stuid=[];commentvalue=[];
    $("input[name='markGradeStuidTocomment']").each(function(i){
      stuid[i]=$(this).val();
    });
    $("textarea:input[name=teacher_ocomment_gs]").each(function(i){
      commentvalue[i]=$.trim($(this).val());
    });
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Viewstudentbs/save_teacher_comment/",
      data: ({
        stuid:stuid,
        commentvalue:commentvalue,
        academicyear: academicyear,
        quarter:quarter,
        markGradeSec:markGradeSec,
        markGradeSecBranch:markGradeSecBranch
      }),
      cache: false,
      beforeSend: function() {
        $('.infoTeacheroComment').html( 'Saving<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.infoTeacheroComment').html(html);
      }
    });
  });
  $('#feedOverallComment').on('submit', function(event) {
    event.preventDefault();
    var branches=$('#bsbranchOverallComment').val();
    var gradesec=$('#bsgradesecOverallComment').val();
    var quarter=$('#bsquarterOverallComment').val();
    if ($('#bsgradesecOverallComment').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Viewstudentbs/fecthOverallComment/",
        method: "POST",
        data: ({
          branches: branches,
          gradesec:gradesec,
          quarter:quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listOverallComment').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listOverallComment").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $(document).ready(function() {  
    $("#bsbranchOverallComment").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#bsbranchOverallComment").val(),
        beforeSend: function() {
          $('#bsgradesecOverallComment').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#bsgradesecOverallComment").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#bsgradesecOverallComment").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#bsgradesecOverallComment").val(),
         beforeSend: function() {
          $('#bsquarterOverallComment').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#bsquarterOverallComment").html(data);
        }
      });
    });
  });
  
  $(document).on('click', '.insertbsTypeGS_feed', function() {
    var value=$(this).attr('value');
    var stuid=$(this).attr('id');
    var bsname=$(this).attr('title');
    var quarter=$('#bsQuarter_feed').val();
    var bsGradesec=$('#bsGradesec_feed').val();
    var branch=$('#bsBranch_feed').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Viewstudentbs/updateStudentBs/",
        data: ({
          value:value,
          stuid:stuid,
          bsname:bsname,
          quarter:quarter,
          bsGradesec:bsGradesec,
          branch:branch
        }),
        success: function(data) {
          iziToast.success({
            title: data,
            message: '',
            position: 'topRight'
          });
        }
    });
  });
  $('#fetchBsFeed').on('submit', function(event) {
    event.preventDefault();
    var branches=$('#bsbranchFeed').val();
    var gradesec=$('.bsgradesecFeed').val();
    var quarter=$('#bsquarterFeed').val();
    if ($('.bsgradesecFeed').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Viewstudentbs/fecthStudentBsFeed/",
        method: "POST",
        data: ({
          branches: branches,
          gradesec:gradesec,
          quarter:quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listbsFeed').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listbsFeed").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $(document).ready(function() {  
    $("#bsgradesecNonFilled").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#bsgradesecNonFilled").val(),
         beforeSend: function() {
          $('#bsquarterNonFilled').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#bsquarterNonFilled").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#bsgradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#bsgradesec").val(),
         beforeSend: function() {
          $('#bsquarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#bsquarter").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#bsgradesecFeed").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#bsgradesecFeed").val(),
         beforeSend: function() {
          $('#bsquarterFeed').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#bsquarterFeed").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#bsgradesecFeed2").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_quarter/",
        data: "gradesec=" + $("#bsgradesecFeed2").val(),
         beforeSend: function() {
          $('#bsquarterFeed2').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(data) {
          $("#bsquarterFeed2").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#bsbranchFeed").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#bsbranchFeed").val(),
        beforeSend: function() {
          $('.bsgradesecFeed').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".bsgradesecFeed").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#bsbranchFeed2").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#bsbranchFeed2").val(),
        beforeSend: function() {
          $('.bsgradesecFeed2').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".bsgradesecFeed2").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  function codespeedyPrint(){
    var print_div = document.getElementById("listNonFilledBsReport");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  function codespeedyFeed(){
    var print_div = document.getElementById("listbsFeed");
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
  $('#printReportPage').on('submit', function(event) {
    event.preventDefault();
    var branches=$('#bsbranchNonFilledReport').val();
    var gradesec=$('.bsgradesecNonFilledReport').val();
    var quarter=$('#bsquarterNonFilledReport').val();
    if ($('.bsgradesecNonFilledReport').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Viewstudentbs/printReportPage/",
        method: "POST",
        data: ({
          branches: branches,
          gradesec:gradesec,
          quarter:quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listNonFilledBsReport').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="30" height="30" id="loa">' );
        },
        success: function(data) {
          $(".listNonFilledBsReport").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $('#fetchNonFilledBs').on('submit', function(event) {
    event.preventDefault();
    var branches=$('#bsbranchNonFilled').val();
    var gradesec=$('.bsgradesecNonFilled').val();
    var quarter=$('#bsquarterNonFilled').val();
    if ($('.bsgradesecNonFilled').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Viewstudentbs/fecthNonFilledStudentBs/",
        method: "POST",
        data: ({
          branches: branches,
          gradesec:gradesec,
          quarter:quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listNonFilledBs').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listNonFilledBs").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<script type="text/javascript">
  $(document).on('change', '.insertbsTypeo', function() {
    var value=$(this).find('option:selected').attr('value');
    var stuid=$(this).find('option:selected').attr('name');
    var bsname=$(this).find('option:selected').attr('class');
    var quarter=$('#bsQuarter').val();
    var bsGradesec=$('#bsGradesec').val();
    var branch=$('#bsBranch').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Viewstudentbs/updateStudentBs/",
        data: ({
          value:value,
          stuid:stuid,
          bsname:bsname,
          quarter:quarter,
          bsGradesec:bsGradesec,
          branch:branch
        }),
        success: function(data) {
          iziToast.success({
            title: data,
            message: '',
            position: 'topRight'
          });
        }
    });
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("listbs");
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
<!-- Grade change script starts-->
<script type="text/javascript">
  $(document).ready(function() {  
    $("#bsbranchNonFilled").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#bsbranchNonFilled").val(),
        beforeSend: function() {
          $('.bsgradesecNonFilled').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".bsgradesecNonFilled").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#bsbranchNonFilledReport").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#bsbranchNonFilledReport").val(),
        beforeSend: function() {
          $('.bsgradesecNonFilledReport').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".bsgradesecNonFilledReport").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#bsbranch").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#bsbranch").val(),
        beforeSend: function() {
          $('.bsgradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".bsgradesec").html(data);
        }
      });
    });
  });
</script>
<!-- Grade change script ends -->
<!-- Fecth mark script starts -->
<script type="text/javascript">
  $('#fetchBs').on('submit', function(event) {
    event.preventDefault();
    var branches=$('#bsbranch').val();
    var gradesec=$('.bsgradesec').val();
    var quarter=$('#bsquarter').val();
    if ($('.bsgradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Viewstudentbs/fecthStudentBs/",
        method: "POST",
        data: ({
          branches: branches,
          gradesec:gradesec,
          quarter:quarter
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listbs').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".listbs").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<!-- Fetch mark ends -->
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