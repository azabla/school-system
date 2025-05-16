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
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#editStudentResult" role="tab" aria-selected="true">Edit Student Result </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#notFilledStudent" role="tab" aria-selected="false">NG/NA Student</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#resultReport" role="tab" aria-selected="false">Result Report</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab5" data-toggle="tab" href="#reportStatistics" role="tab" aria-selected="false">Report Statistics</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab6" data-toggle="tab" href="#rosterSummary" role="tab" aria-selected="false">Roster Summary</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab8" data-toggle="tab" href="#subject_list_name_sheet" role="tab" aria-selected="false">የማርክ ወረቀት</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="editStudentResult" role="tabpanel" aria-labelledby="home-tab1">
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
                        <?php if($_SESSION['usertype']===trim('Director')){?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesec" required="required"
                             name="bsgradesec" id="bsgradesec">
                              <option>--- Grade ---</option>
                              <?php foreach($gradesecD as $gradesecsD){ ?>
                              <option value="<?php echo $gradesecsD->grade;?>">
                                <?php echo $gradesecsD->grade;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                         <?php } else{?>
                          <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesec" required="required"
                             name="bsgradesec" id="bsgradesec">
                              <option>--- Grade ---</option>
                              <?php foreach($gradesec as $gradesecs){ ?>
                              <option value="<?php echo $gradesecs->roomgrade;?>">
                                <?php echo $gradesecs->roomgrade;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                      <?php } ?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric bsquarter" required="required" 
                            name="bsquarter" id="bsquarter">
                              <option>--- Category ---</option>
                              
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <?php $this->db->where('academicyear',$max_year);
                            $this->db->where('enable_status','1');
                            $query=$this->db->get('kg_chibt_week_category');
                            if($query->num_rows()>0){ ?>
                              <select class="form-control" required="required" name="period_status" id="period_status">
                                <option> </option>
                                <?php foreach (range(1, 10) as $week) { ?>
                                  <option value="Week <?= $week ?>">Week <?= $week ?></option>
                                <?php } ?>
                              </select>
                            <?php } else { ?>
                            <select class="form-control selectric period_status" required="required" name="period_status" id="period_status">
                              <option> </option>
                              <option>በመጀመሪያ</option>
                              <option>በመጨረሻ</option>                      
                            </select>
                          <?php }?>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="viewmark">View Student
                          </button>
                        </div>
                      </div>
                    </form> 
                    <div class="listbs" id="listbs"></div>
                  </div>
                  <div class="tab-pane fade show" id="notFilledStudent" role="tabpanel" aria-labelledby="home-tab2">
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
                        <?php if($_SESSION['usertype']===trim('Director')){?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecNonFilled" required="required"
                             name="bsgradesecNonFilled" id="bsgradesecNonFilled">
                              <option>--- Grade ---</option>
                              <?php foreach($gradesecD as $gradesecsD){ ?>
                              <option value="<?php echo $gradesecsD->grade;?>">
                                <?php echo $gradesecsD->grade;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                      <?php } else{?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecNonFilled" required="required"
                             name="bsgradesecNonFilled" id="bsgradesecNonFilled">
                              <option>--- Grade ---</option>
                              <?php foreach($gradesec as $gradesecs){ ?>
                              <option value="<?php echo $gradesecs->roomgrade;?>">
                                <?php echo $gradesecs->roomgrade;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                      <?php } ?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric bsquarterNonFilled" required="required" 
                            name="bsquarterNonFilled" id="bsquarterNonFilled">
                              <option>--- Category ---</option>
                              
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <?php $this->db->where('academicyear',$max_year);
                            $this->db->where('enable_status','1');
                            $query=$this->db->get('kg_chibt_week_category');
                            if($query->num_rows()>0){ ?>
                              <select class="form-control" required="required" name="period_statusNonFilled" id="period_statusNonFilled">
                                <option> </option>
                                <?php foreach (range(1, 10) as $week) { ?>
                                  <option value="Week <?= $week ?>">Week <?= $week ?></option>
                                <?php } ?>
                              </select>
                            <?php } else { ?>
                            <select class="form-control selectric period_statusNonFilled" required="required" name="period_statusNonFilled" id="period_statusNonFilled">
                              <option> </option>
                              <option>በመጀመሪያ</option>
                              <option>በመጨረሻ</option>                      
                            </select>
                          <?php }?>
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
                  <div class="tab-pane fade show" id="resultReport" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                        <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="fetch_result_report">
                      <div class="row">
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="year_result_report"  id="year_result_report">
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                                <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                        <?php if($_SESSION['usertype']===trim('Director')){?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control grade_result_report" required="required"
                             name="grade_result_report" id="grade_result_report">
                              <option>--- Grade ---</option>
                              <?php foreach($gradesecD as $gradesecsD){ ?>
                              <option value="<?php echo $gradesecsD->grade;?>">
                                <?php echo $gradesecsD->grade;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                      <?php } else{?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control grade_result_report" required="required"
                             name="grade_result_report" id="grade_result_report">
                              <option>--- Grade ---</option>
                               <?php foreach($gradesec as $gradesecs){ ?>
                              <option value="<?php echo $gradesecs->roomgrade;?>">
                                <?php echo $gradesecs->roomgrade;?>
                              </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                      <?php } ?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" 
                            name="term_result_report" id="term_result_report">
                              <option> </option>
                              
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2 col-6">
                          <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="show_result_report">Show Result
                          </button>
                        </div>
                      </div>
                    </form>
                    <div class="list_result_report" id="list_result_report"></div>
                  </div>
                  <div class="tab-pane fade show" id="reportStatistics" role="tabpanel" aria-labelledby="home-tab5">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyRS()">
                        <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>
                        </button>
                        <button type="submit" id="dataExportExcel" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel
                        </button>
                      </div>
                    </div>
                    <form method="POST" id="fetchReportStatisticsKG">
                      <div class="row">
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <?php $this->db->where('academicyear',$max_year);
                            $this->db->where('enable_status','1');
                            $query=$this->db->get('kg_chibt_week_category');
                            if($query->num_rows()>0){ ?>
                              <select class="form-control" required="required" name="period_statusRS" id="period_statusRS">
                                <option> </option>
                                <?php foreach (range(1, 10) as $week) { ?>
                                  <option value="Week <?= $week ?>">Week <?= $week ?></option>
                                <?php } ?>
                              </select>
                            <?php } else { ?>
                            <select class="form-control selectric period_statusRS" required="required" name="period_statusRS" id="period_statusRS">
                              <option> </option>
                              <option>በመጀመሪያ</option>
                              <option>በመጨረሻ</option>                      
                            </select>
                          <?php }?>
                          </div>
                        </div>
                       <?php if($_SESSION['usertype']===trim('Director')){?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecRS" required="required"
                             name="bsgradesecRS" id="bsgradesecRS">
                              <option>--- Grade ---</option>
                              <?php foreach($gradesecD as $gradesecsD){ ?>
                              <option value="<?php echo $gradesecsD->grade;?>">
                                <?php echo $gradesecsD->grade;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                      <?php } else{?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecRS" required="required"
                             name="bsgradesecRS" id="bsgradesecRS">
                              <option>--- Grade ---</option>
                               <?php foreach($gradesec as $gradesecs){ ?>
                              <option value="<?php echo $gradesecs->roomgrade;?>">
                                <?php echo $gradesecs->roomgrade;?>
                              </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                      <?php } ?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric bsquarterRS" required="required" 
                            name="bsquarterRS" id="bsquarterRS">
                              <option>--- Category ---</option>
                              
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-5 col-6">
                          <div class="table-responsive bsheaderRS" name="bsheaderRS" id="bsheaderRS" style="height: 20hv;">
                          </div>
                        </div>
                         <div class="col-lg-3 col-6">
                          <div class="table-responsive bsValueRS" id="bsValueRS" name="bsValueRS" style="height: 20hv;">
                          </div>
                        </div>
                        <div class="col-lg-2 col-6">
                          <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="viewmark">View
                          </button>
                        </div>
                      </div>
                    </form> 
                    <div class="listRS" id="listRS"></div>
                  </div>
                  <div class="tab-pane fade show" id="rosterSummary" role="tabpanel" aria-labelledby="home-tab6">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyRSummary()">
                        <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>
                        </button>
                        <button type="submit" id="dataExportExcelSummary" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel
                        </button>
                      </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <form method="POST" id="fetchRosterSummaryKG">
                      <div class="row">
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="bsacademicyearRSummary"  id="bsacademicyearRSummary">
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                                <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                        <?php if($_SESSION['usertype']===trim('Director')){?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecRSummary" required="required"
                             name="bsgradesecRSummary" id="bsgradesecRSummary">
                              <option>--- Grade ---</option>
                              <?php foreach($gradesecD as $gradesecsD){ ?>
                              <option value="<?php echo $gradesecsD->grade;?>">
                                <?php echo $gradesecsD->grade;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                      <?php } else{?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecRSummary" required="required"
                             name="bsgradesecRSummary" id="bsgradesecRSummary">
                              <option>--- Grade ---</option>
                               <?php foreach($gradesec as $gradesecs){ ?>
                              <option value="<?php echo $gradesecs->roomgrade;?>">
                                <?php echo $gradesecs->roomgrade;?>
                              </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                      <?php } ?>
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <?php $this->db->where('academicyear',$max_year);
                            $this->db->where('enable_status','1');
                            $query=$this->db->get('kg_chibt_week_category');
                            if($query->num_rows()>0){ ?>
                              <select class="form-control" required="required" name="period_statusRSummary" id="period_statusRSummary">
                                <option> </option>
                                <?php foreach (range(1, 10) as $week) { ?>
                                  <option value="Week <?= $week ?>">Week <?= $week ?></option>
                                <?php } ?>
                              </select>
                            <?php } else { ?>
                            <select class="form-control selectric period_statusRSummary" required="required" name="period_statusRSummary" id="period_statusRSummary">
                              <option> </option>
                              <option>በመጀመሪያ</option>
                              <option>በመጨረሻ</option>                      
                            </select>
                          <?php }?>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="viewmark">View Roster
                          </button>
                        </div>
                      </div>
                    </form> 
                    <div class="listRSummary" id="listRSummary"></div>
                  </div>
                  <div class="tab-pane fade show" id="subject_list_name_sheet" role="tabpanel" aria-labelledby="home-tab8">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyRMarking()">
                        <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>
                        </button>
                        <button type="submit" id="dataExportExcelSummary" name="dataExport" value="Export to excel" class="btn btn-info pull-right">Export To Excel
                        </button>
                      </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <form method="POST" id="fetchRosterMarkings">
                      <div class="row">
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="bsacademicyearRMarkings"  id="bsacademicyearRMarkings">
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                                <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                        <?php if($_SESSION['usertype']===trim('Director')){?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecMarkings" required="required"
                             name="bsgradesecMarkings" id="bsgradesecMarkings">
                              <option>--- Grade ---</option>
                              <?php foreach($gradesecD as $gradesecsD){ ?>
                              <option value="<?php echo $gradesecsD->grade;?>">
                                <?php echo $gradesecsD->grade;?>
                              </option>
                            <?php }?>
                            </select>
                          </div>
                        </div>
                      <?php } else{?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control bsgradesecMarkings" required="required"
                             name="bsgradesecMarkings" id="bsgradesecMarkings">
                              <option>--- Grade ---</option>
                               <?php foreach($gradesec as $gradesecs){ ?>
                              <option value="<?php echo $gradesecs->roomgrade;?>">
                                <?php echo $gradesecs->roomgrade;?>
                              </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                      <?php } ?>
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric bsquarterRSMarkings" required="required" name="bsquarterRSMarkings" id="bsquarterRSMarkings">
                              <option>--- Category ---</option>
                              
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <button class="btn btn-primary btn-block btn-lg" 
                            type="submit" name="viewmark">View Sheet
                          </button>
                        </div>
                      </div>
                    </form> 
                    <div class="listRMarkings" id="listRMarkings"></div>
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
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  $(document).ready(function() {  
    $(".bsgradesecMarkings").bind("change", function() {
      var gradesec=$('.bsgradesecMarkings').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/load_kg_subject_header_grade/",
        data: ({
          gradesec:gradesec
        }),
        beforeSend: function() {
          $('.bsquarterRSMarkings').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
        },
        success: function(data) {
          $(".bsquarterRSMarkings").html(data);
        }
      });
    });
  });
  $('#fetchRosterMarkings').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('.bsgradesecMarkings').val();
    var bsquarterRS=$('#bsquarterRSMarkings').val();
    var academicyear=$('#bsacademicyearRMarkings').val();
    if ($('#bsquarterRSMarkings').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/fetch_sheet/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          bsquarterRS:bsquarterRS,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('.listRMarkings').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $(".listRMarkings").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'error',
      });
    }
  });
  $("#dataExportExcelSummary").click(function(e) {
  let file = new Blob([$('.listRSummary').html()], {type:"application/vnd.ms-excel"});
  let url = URL.createObjectURL(file);
  let a = $("<a />", {
    href: url,
    download: "Roster Summary.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
  });
  function codespeedyRSummary(){
    var print_div = document.getElementById("listRSummary");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  $('#fetchRosterSummaryKG').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('.bsgradesecRSummary').val();
    var bsquarterRS=$('#period_statusRSummary').val();
    var academicyear=$('#bsacademicyearRSummary').val();
    if ($('#period_statusRSummary').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/fecthRosterSummary/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          bsquarterRS:bsquarterRS,
          academicyear:academicyear
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listRSummary').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $(".listRSummary").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'error',
      });
    }
  });
  function codespeedyRS(){
    var print_div = document.getElementById("listRS");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  $("#dataExportExcel").click(function(e) {
  let file = new Blob([$('.listRS').html()], {type:"application/vnd.ms-excel"});
  let url = URL.createObjectURL(file);
  let a = $("<a />", {
    href: url,
    download: "Result Statistics.xls"}).appendTo("body").get(0).click();
    e.preventDefault();
  });
  $('#fetchReportStatisticsKG').on('submit', function(event) {
    event.preventDefault();
    value_name=[];header_name=[];
    $("input[name='kg_subject_valueGS_admin']:checked").each(function(i){
      value_name[i]=$(this).val();
    });
    $("input[name='kg_subject_category_nameGS_admin']:checked").each(function(i){
      header_name[i]=$(this).val();
    });
    var gradesec=$('.bsgradesecRS').val();
    var bsquarterRS=$('#bsquarterRS').val();
    var period_statusRS=$('#period_statusRS').val();
    if (value_name.length != 0 && header_name.length != 0) {
      $.ajax({
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/fecthReportStatistics/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          value_name:value_name,
          period_statusRS:period_statusRS,
          bsquarterRS:bsquarterRS,
          header_name:header_name
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listRS').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $(".listRS").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'error',
      });
    }
  });
  $(document).ready(function() {  
    $(".bsquarterRS").bind("change", function() {
      var gradesec=$("#bsgradesecRS").val();
      var quarter=$('.bsquarterRS').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/load_kg_category_header/",
        data: ({
          gradesec: gradesec,
          quarter:quarter
        }),
        beforeSend: function() {
          $('.bsheaderRS').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
        },
        success: function(data) {
          $(".bsheaderRS").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $(".bsgradesecRS").bind("change", function() {
      var gradesec=$('.bsgradesecRS').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/load_kg_subject_header_grade/",
        data: ({
          gradesec:gradesec
        }),
        beforeSend: function() {
          $('.bsquarterRS').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
        },
        success: function(data) {
          $(".bsquarterRS").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $(".bsgradesecRS").bind("change", function() {
      var gradesec=$('.bsgradesecRS').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/load_kg_subject_value/",
        data: ({
          gradesec:gradesec
        }),
        beforeSend: function() {
          $('.bsValueRS').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
        },
        success: function(data) {
          $(".bsValueRS").html(data);
        }
      });
    });
  });
  $('#fetch_result_report').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('.grade_result_report').val();
    var quarter=$('#term_result_report').val();
    var year=$('#year_result_report').val();
    if ($('.grade_result_report').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/fetch_result_report/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          quarter:quarter,
          year:year
        }),
        dataType:"json",
        beforeSend: function() {
          $('.list_result_report').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $(".list_result_report").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'error',
      });
    }
  });
  $('#fetchNonFilledBs').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('.bsgradesecNonFilled').val();
    var quarter=$('#bsquarterNonFilled').val();
    var period_status=$('#period_statusNonFilled').val();
    if ($('.bsgradesecNonFilled').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/fecthNonFilledStudentBs/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          quarter:quarter,
          period_status:period_status
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listNonFilledBs').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $(".listNonFilledBs").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'error',
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).on('change', '.insert_stu_result_value_gs', function() {
    var value=$(this).find('option:selected').attr('value');
    var stuid=$(this).find('option:selected').attr('name');
    var bsname=$(this).find('option:selected').attr('class');
    var quarter=$('#bsQuarter_kg_value').val();
    var bsGradesec=$('#bsGradesec_kg_value').val();
    var branch=$('#bsBranch_kg_value').val();
    var bsPeriod=$('#bsPeriod_kg_value').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/updateStudentBs/",
        data: ({
          value:value,
          stuid:stuid,
          bsname:bsname,
          quarter:quarter,
          bsGradesec:bsGradesec,
          branch:branch,
          bsPeriod:bsPeriod
        }),
        success: function(data) {
          iziToast.success({
            title: 'Data updated successfully',
            message: '',
            position: 'topRight'
          });
        }
    });
  });
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("list_result_report");
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
    $("#branch_result_report").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Filter_grade_from_branch/",
        data: "branchit=" + $("#branch_result_report").val(),
        beforeSend: function() {
          $('.grade_result_report').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".grade_result_report").html(data);
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
  $(document).ready(function() {  
    $(".bsgradesec").bind("change", function() {
      var gradesec=$('.bsgradesec').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/load_kg_subject_header/",
        data: ({
          gradesec:gradesec
        }),
        beforeSend: function() {
          $('.bsquarter').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
        },
        success: function(data) {
          $(".bsquarter").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $(".bsgradesecNonFilled").bind("change", function() {

      var gradesec=$('.bsgradesecNonFilled').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/load_kg_subject_header/",
        data: ({
          gradesec:gradesec
        }),
        beforeSend: function() {
          $('.bsquarterNonFilled').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
        },
        success: function(data) {
          $(".bsquarterNonFilled").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#grade_result_report").bind("change", function() {
      var gradesec=$('#grade_result_report').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/load_kg_subject_header/",
        data: ({
          gradesec:gradesec
        }),
        beforeSend: function() {
          $('#term_result_report').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
        },
        success: function(data) {
          $("#term_result_report").html(data);
        }
      });
    });
  });
</script>
<!-- Grade change script ends -->
<!-- Fecth mark script starts -->
<script type="text/javascript">
  $(document).on('submit', '#fetchBs', function(event) {
    event.preventDefault();
    var gradesec=$('.bsgradesec').val();
    var quarter=$('#bsquarter').val();
    var period_status=$('#period_status').val();
    if ($('.bsgradesec').val() != '' || $('#period_status').val()!='--- Result Period ---') {
      $.ajax({
        url: "<?php echo base_url(); ?>mykgsubjectlistreport/fecthStudentResult/",
        method: "POST",
        data: ({
          gradesec:gradesec,
          quarter:quarter,
          period_status:period_status
        }),
        dataType:"json",
        beforeSend: function() {
          $('.listbs').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
        },
        success: function(data) {
          $(".listbs").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'error',
      });
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