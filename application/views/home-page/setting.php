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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/daterangepicker.css">
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
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                <div class="card-body StudentViewTextInfo">
                  <ul class="nav nav-tabs" id="myTab2" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#about" role="tab" aria-selected="true">School Information</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab2" data-toggle="tab" href="#ayear" role="tab" aria-selected="false">Academic Year</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab4" data-toggle="tab" href="#branchTab" role="tab" aria-selected="false">Manage Branch</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab5" data-toggle="tab" href="#quarter" role="tab" aria-selected="false">Manage Season</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab6" data-toggle="tab" href="#promotionPolicy" role="tab" aria-selected="false">Promotion Policy</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab7" data-toggle="tab" href="#rankPolicy" role="tab" aria-selected="false">Display Policy</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab8" data-toggle="tab" href="#letterGrades" role="tab" aria-selected="false">Letter Grades</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab9" data-toggle="tab" href="#schoolDivision" role="tab" aria-selected="false">School Division</a>
                    </li>
                    
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab12" data-toggle="tab" href="#reportcardComments" role="tab" aria-selected="false">Report Card Comments</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab10" data-toggle="tab" href="#generalSetting" role="tab" aria-selected="false">General Setting</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab13" data-toggle="tab" href="#ScheduleTasks" role="tab" aria-selected="false">Schedule Tasks</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab11" data-toggle="tab" href="#socialPages" role="tab" aria-selected="false">Social Pages</a>
                    </li>
                  </ul>
                  <div class="tab-content tab-bordered" id="myTab3Content">
                    <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2">
                      <div class="row">
                        <div class="col-lg-12 col-12">
                          <a href="#" class="editsubject" value="" data-toggle="modal" data-target="#add_school_information"><span class="text-black">
                            <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Edit School Information</button>
                         </span>
                         </a>
                        </div>
                      </div>
                      <form action="<?php echo base_url()?>setting/" method="POST">
                         <div class="table-responsive">
                          <table class="table table-borderedr table-md">
                            <tr>
                              <th>Name</th>
                              <th>Logo</th>
                              <th>Phone</th>
                              <th>Email</th>
                              <th>Slogan</th>
                              <th>Website</th>
                              <th>Address</th>
                              <th>Created At</th>
                            </tr>
                            <?php foreach($schools as $school) {?>
                            <tr>
                              <td><?php echo $school->name;?> - <?php echo $school->name_2;?></td>
                               <td>
                                <img src="<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>" style="height:50px;width:50px;border-radius: 3em;">
                               </td>
                               <td><?php echo $school->phone;?></td>
                               <td><?php echo $school->email;?></td>
                                <td><?php echo $school->slogan;?></td>
                                <td><?php echo $school->website;?></td>
                                <td><?php echo $school->address;?></td>
                              <td><?php echo $school->date_created;?></td>
                            </tr>
                            <?php } ?>
                          </table>
                        </div>
                      </form>
                      <!-- <div id="qrcode"></div> -->
                    </div>
                  <div class="tab-pane fade show" id="ayear" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="addAcademicYear" value="" data-toggle="modal" data-target="#add_academic_year"><span class="text-black">
                          <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add Academic Year</button>
                       </span>
                       </a>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-striped table-md">
                        <tr>
                          <th>Ethiopian Year</th>
                          <th>Gregorian Year</th>
                          <th>Created At</th>
                          <th>Action</th>
                        </tr>
                        <?php foreach($fetch_year as $fetch_years) { 
                          $id=$fetch_years->id;?>
                        <tr class="delete_year<?php echo $id ?>">
                          <td><?php echo $fetch_years->year_name;?></td>
                          <td><?php echo $fetch_years->gyear;?></td>
                          <td><?php echo $fetch_years->date_created;?>
                          </td>
                          <td>
                           <button type="submit" name="deleteyear" value="<?php echo $fetch_years->id; ?>" 
                            class="btn btn-default deleteyear"><span class="text-danger">Delete</span>
                           </button>
                          </td>
                        </tr>
                        <?php } ?>
                      </table>
                    </div>
                    <div class="checkNewYear"></div>
                    <button class="btn btn-primary" type="submit" id="moveKgsubjectObjective">Move KG Subject Objective</button>
                  </div>
                  <div class="tab-pane fade show" id="branchTab" role="tabpanel" aria-labelledby="home-tab4">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="addSchoolBranch" value="" data-toggle="modal" data-target="#add_school_branch"><span class="text-black">
                          <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add School Branch</button>
                       </span>
                       </a>
                      </div>
                    </div>
                    <form method="POST" action="<?php echo base_url() ?>setting/">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <tr>
                            <th>Branch Name</th>
                            <th>Academic Year</th>
                            <th>Created At</th>
                            <th>Action</th>
                          </tr>
                          <?php foreach($branch as $branchs) { 
                            $id=$branchs->bid;?>
                          <tr class="delete_mem<?php echo $id ?>">
                            <td><?php echo $branchs->name;?></td>
                            <td><?php echo $branchs->academicyear;?></td>
                            <td><?php echo $branchs->datecreated;?>
                            </td>
                            <td>
                             <!--  <button type="submit" name="editbranch" value="<?php echo $branchs->bid; ?>" class="btn btn-success editbranch"><i class="fas fa-pen"></i>Edit
                              </button> -->
                              <button type="submit" name="deletebranch" onclick="return confirm('Are you sure you want to delete this Branch Name')" value="<?php echo $branchs->bid; ?>" class="btn btn-danger deletebranch"> <i class="fas fa-trash"></i>Delete
                              </button>
                            </td>
                          </tr>
                          <?php } ?>
                        </table>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane fade show" id="quarter" role="tabpanel" aria-labelledby="home-tab5">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="addSchoolSeason" value="" data-toggle="modal" data-target="#add_school_Season"><span class="text-black">
                          <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add School Season</button>
                       </span>
                       </a>
                      </div>
                    </div>
                    <div class="placeOfQuarter"></div>
                  </div>
                  <div class="tab-pane fade show" id="promotionPolicy" role="tabpanel" aria-labelledby="home-tab6">
                    <div class="row">
                      <div class="col-lg-6 col-12 form-group">
                        <a href="#" class="add_school_promotion_grade_level" value="" data-toggle="modal" data-target="#add_school_promotion_grade_level">
                            <button class="btn btn-primary btn-block"><i data-feather="plus-circle"> </i>School Grade Level</button>
                        </a>
                      </div>
                      <div class="col-lg-6 col-12 form-group">
                        <a href="#" class="add_school_promotion_policy" value="" data-toggle="modal" data-target="#add_school_promotion_policy">
                            <button class="btn btn-info btn-block"><i data-feather="plus-circle"> </i>School Promotion Policy</button>
                        </a>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane fade show" id="rankPolicy" role="tabpanel" aria-labelledby="home-tab7">
                    <div class="row">
                      <div class="col-lg-12 col-12" id="totalGradeName">
                        <div class="form-group">
                          <label for="Mobile">Dispaly Total on Report Card for Grades </label><br>
                          <?php foreach($grade as $grades){ 
                          $grade=$grades->grade;
                          $queryCheck=$this->db->query("select * from rank_allowed_grades where grade='$grade' and rowname='totalname' ");
                          if($queryCheck->num_rows()>0){ ?>
                          <div class="pretty p-switch p-fill">
                            <?php echo $grades->grade; ?>
                            <input type="checkbox" name="totalGradeName" class="totalGradeName" checked="checked" id="totalname" value="<?php echo $grades->grade; ?>" >
                            <div class="state p-success">
                              <label></label>
                            </div>
                          </div>
                          <?php } else { ?>
                          <div class="pretty p-switch p-fill">
                            <?php echo $grades->grade; ?>
                            <input type="checkbox" name="totalGradeName" class="totalGradeName" id="totalname" value="<?php echo $grades->grade; ?>" >
                            <div class="state p-success">
                              <label></label>
                            </div>
                          </div>
                          <?php } } ?>
                        </div>
                        <div class="dropdown-divider"></div>
                      </div>
                      
                      <div class="col-lg-12 col-12" id="averageGradeName">
                        <div class="form-group">
                          <label for="Mobile">Dispaly Average on Report Card for Grades </label><br>
                          <?php foreach($gradee as $grades){
                          $grade=$grades->grade;
                          $queryCheck=$this->db->query("select * from rank_allowed_grades where grade='$grade' and rowname='averagename' ");
                          if($queryCheck->num_rows()>0){ ?>
                          <div class="pretty p-switch p-fill">
                            <?php echo $grades->grade; ?>
                            <input type="checkbox" name="averageGradeName" class="averageGradeName" checked="checked" id="averagename" value="<?php echo $grades->grade; ?>" >
                            <div class="state p-success">
                              <label></label>
                            </div>
                          </div>
                          <?php } else { ?>
                          <div class="pretty p-switch p-fill">
                            <?php echo $grades->grade; ?>
                            <input type="checkbox" name="averageGradeName" class="averageGradeName" id="averagename" value="<?php echo $grades->grade; ?>" >
                            <div class="state p-success">
                              <label></label>
                            </div>
                          </div>
                          <?php } } ?>
                        </div>
                      </div>
                      
                      <div class="col-lg-12 col-12" id="rankGradeName">
                        <div class="dropdown-divider"></div>
                        <div class="form-group">
                          <label for="Mobile">Dispaly Rank on Report Card for Grades </label><br>
                          <?php foreach($gradee as $grades){
                          $grade=$grades->grade;
                          $queryCheck=$this->db->query("select * from rank_allowed_grades where grade='$grade' and rowname='rankname' ");
                          if($queryCheck->num_rows()>0){ ?>
                          <div class="pretty p-switch p-fill">
                            <?php echo $grades->grade; ?>
                            <input type="checkbox" name="rankGradeName" class="rankGradeName" checked="checked" id="rankname" value="<?php echo $grades->grade; ?>" >
                            <div class="state p-success">
                              <label></label>
                            </div>
                          </div>
                          <?php } else { ?>
                          <div class="pretty p-switch p-fill">
                            <?php echo $grades->grade; ?>
                            <input type="checkbox" name="rankGradeName" class="averageGradeName" id="rankname" value="<?php echo $grades->grade; ?>" >
                            <div class="state p-success">
                              <label></label>
                            </div>
                          </div>
                          <?php } } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="letterGrades" role="tabpanel" aria-labelledby="home-tab8">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="addSchoolLetterGrade" value="" data-toggle="modal" data-target="#add_school_letterRange"><span class="text-black">
                          <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add Letter Grade Range</button>
                       </span>
                       </a>
                      </div>
                    </div>
                    <div id="fetch_letter_policy"> </div>
                  </div>
                  <div class="tab-pane fade show" id="schoolDivision" role="tabpanel" aria-labelledby="home-tab9">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="addSchoolDivision" value="" data-toggle="modal" data-target="#add_school_division"><span class="text-black">
                          <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add School Staff Division</button>
                       </span>
                       </a>
                      </div>
                    </div>
                    <div class="fetchDivision"></div>
                  </div>
                  
                  <div class="tab-pane fade show" id="reportcardComments" role="tabpanel" aria-labelledby="home-tab12">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="addSchoolComments" value="" data-toggle="modal" data-target="#add_school_comments"><span class="text-black">
                          <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add Reportcard Comments</button>
                       </span>
                       </a>
                      </div>
                    </div>
                    <div class="fetchReportCardComment"></div>
                  </div>
                  <div class="tab-pane fade show" id="generalSetting" role="tabpanel" aria-labelledby="home-tab10">
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-12">
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-6">
                            <button type="submit" name="addnewWeek" data-toggle="modal" data-target="#newWeek" class="btn btn-info btn-md btn-block"> Add School Week
                            </button>
                          </div>
                          <div class="col-lg-6 col-md-6 col-6">
                            <button type="submit" name="addnewNonWorkingDates" data-toggle="modal" data-target="#newNonWorkingDays" class="btn btn-primary btn-md btn-block"> Add Non-Working Days
                            </button>
                          </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="studentsCanSeeQuarterResult"></div>
                        <div class="dropdown-divider"></div>
                        <div class="ageCalculationMethod"> </div>
                        <div class="dropdown-divider"></div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-12">
                        <div class="enableApproveMark"> </div>
                        <div class="dropdown-divider"></div>
                        <div class="enableApproveCommunicationBook"> </div>
                        <div class="dropdown-divider"></div>
                        <div class="lockMarkAutomatically"> </div>
                        <div class="dropdown-divider"></div>
                        <div class="on_off_registration_page"> </div>
                        <div class="dropdown-divider"></div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="ScheduleTasks" role="tabpanel" aria-labelledby="home-tab13">
                    <div class="scheduleDoneTasks"></div>
                    <div class="scheduleMarkResult"></div>
                  </div>
                  <div class="tab-pane fade show" id="socialPages" role="tabpanel" aria-labelledby="home-tab11">
                    <form method="POST" action="<?php echo base_url() ?>setting/">
                    <div class="row">
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <label for="Mobile">Facebook</label>
                            <input class="form-control" id="term" 
                            required="required" name="facebooklink" type="text" placeholder="Facebook Link">
                       </div>
                     </div>
                     <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <label for="Mobile">Twitter Link</label>
                            <input class="form-control" id="term" 
                            required="required" name="twitterlink" type="text" placeholder="Twitter Link">
                       </div>
                     </div>
                     <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <label for="Mobile">Instagram</label>
                            <input class="form-control" id="term" 
                            required="required" name="instagramlink" type="text" placeholder="Instagram Link">
                       </div>
                     </div>
                     <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <label for="Mobile">Telegram Link</label>
                            <input class="form-control" id="term" 
                            required="required" name="telegramlink" type="text" placeholder="Telegram Link">
                       </div>
                     </div>
                     <div class="col-lg-12 col-12">
                        <button type="submit" id="postsocial" name="postsocial" class="btn btn-primary btn-block">Save Social Pages
                       </button>
                    </div>
                  </div>
                  </form>
                   <div class="table-responsive">
                      <table class="table table-striped table-md">
                        <tr>
                          <th>Facebook</th>
                          <th>Twitter</th>
                          <th>Telegram</th>
                          <th>Instagram</th>
                          <th>Date Created</th>
                        </tr>
                        <?php foreach($social_pages as $social_page) {?>
                        <tr>
                          <td><?php echo $social_page->facebook;?></td>
                          <td><?php echo $social_page->twitter;?></td>
                          <td><?php echo $social_page->telegram;?></td>
                          <td><?php echo $social_page->instagram;?></td>
                          <td><?php echo $social_page->date_created;?></td>
                        </tr>
                        <?php } ?>
                      </table>
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
  <div class="modal fade" id="add_school_comments" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add School Comments</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card">
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="form-group">
                <label for="Mobile">Min Value/Average</label>
                <input class="form-control" id="minValue" 
                  name="minValue" type="number" placeholder="Minimum value" required>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="form-group">
                <label for="Mobile">Max Value/Average</label>
                <input class="form-control" id="maxValue" 
                  name="maxValue" type="number" placeholder="Maximum value" required>
              </div>
            </div>
            <div class="col-lg-6 col-12 table-responsive" style="height: 15vh;">
              <div class="form-group">
                <label for="Mobile">Grade</label>
                <div class="row">
                  <?php foreach($gradeeeee as $grades){ ?>
                  <div class="col-lg-3 col-6">
                  <div class="pretty p-bigger">
                   <input id="commentGrade" type="checkbox" name="commentGrade" value="<?php echo $grades->grade; ?>">
                   <div class="state p-info">
                      <i class="icon material-icons"></i>
                      <label></label><?php echo $grades->grade; ?>
                   </div>
                   </div>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="col-lg-12 col-12">
              <div class="dropdown-divider2"></div>
              <div class="form-group">
                <input type="text" class="form-control" id="reportcardComment" name="reportcardComment" placeholder="Comment here..."  required="required"> 
              </div>
            </div>
            <div class="col-lg-12 col-12">
              <div class="dropdown-divider2"></div>
              <button type="submit" id="postRcComment" name="postRcComment" class="btn btn-primary pull-right">Save Changes
              </button>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <a id="saveskygrade"></a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add_school_assesment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add School Assesment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card">
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="form-group">
                <label>Evaluation</label>
                <select class="form-control selectric" required="required" name="schoolAssesmentEva" id="schoolAssesmentEva">
                  <option></option>
                 
                </select>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="form-group">
                <label>Assesment Name</label>
                <input class="form-control" id="schoolAssesmentName" 
                  name="schoolAssesmentName" type="text" placeholder="School assesment...." required>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="form-group">
                <label>Percent(Optional)</label>
                <input class="form-control" id="assesmentPercent" 
                  name="assesmentPercent" type="number" placeholder="Percentage....">
              </div>
            </div>
            <div class="col-lg-3 col-5">
              <div class="form-group">
                <label>End Date</label>
                <input class="form-control" id="assesmentEndDate" 
                  name="assesmentEndDate" type="date" placeholder="Enter here school assesment...." required>
              </div>
            </div>
            <div class="col-lg-4 col-12 table-responsive" style="height: 20vh;">
              <div class="form-group">
                <label>Select Grade</label>
                <div class="row">
                  <?php foreach($gradeeee as $grades){ ?>
                  <div class="col-lg-3 col-4">
                  <div class="pretty p-bigger">
                   <input id="assesementGrade" type="checkbox" name="assesementGrade" value="<?php echo $grades->grade; ?>">
                   <div class="state p-success">
                      <i class="icon material-icons"></i>
                      <label></label><?php echo $grades->grade; ?>
                   </div>
                   </div>
                    
                    <div class="dropdown-divider2"></div>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-6">
              <div class="form-group">
                <label>Order(Optional)</label>
                <input type="number" name="assorder" id="assorder" class="form-control" placeholder="Order">
              </div>
            </div>
            <div class="col-lg-4 col-6">
              <input type="checkbox" name="ismandatory" id="ismandatory"> Is Mandatory
              <button type="submit" id="postAssesment" name="postAssesment" class="btn btn-primary btn-block">Save Changes
              </button>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <a id="saveskygrade"></a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add_school_division" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add School Staff Division</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card">
          <div class="row">
            <div class="col-lg-6 col-6">
              <div class="form-group">
                <input class="form-control" id="divisionName" 
                  required="required" name="divisionName" type="text" placeholder="Division Name(5-8 Division)etc...">
             </div>
           </div>
           <div class="col-lg-6 col-6">
              <button type="submit" id="postDivision" name="postDivision" class="btn btn-primary btn-block">Save Changes
             </button>
          </div>
        </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <a id="saveskygrade"></a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add_school_letterRange" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add Letter Range</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card">
          <form method="POST" id="save_letter_policy">
            <div class="row">
             
             <div class="col-md-4 col-6 form-group" id="letter_grajosstad">
              <input type="text" class="form-control" name="startRange" id="startRange" placeholder="Minimum value">
             </div>
             <div class="col-md-4 col-6 form-group" id="letter_grajosstad">
              <input type="text" class="form-control" name="endRange" id="endRange" placeholder="Maximum Value">
             </div>
             <div class="col-md-4 col-6 form-group" id="letter_grajosstad">
               <input type="text" class="form-control" name="valtext" id="valtext" placeholder="Range Value">
             </div>
             <div class="col-lg-12 col-12" id="letter_grajosstad">
                <div class="form-group">
                  <div class="row">
                    <?php foreach($gradeee as $grades){ ?>
                      <div class="col-lg-1 col-4">
                      <div class="pretty p-bigger">
                      <input type="checkbox" name="letter_grade" value="<?php echo $grades->grade; ?>" class="letter_grade" id="customCheck1">
                      <div class="state p-info">
                        <i class="icon material-icons"></i>
                        <label></label><?php echo $grades->grade; ?>
                      </div>
                     </div>
                   </div>
                  <?php } ?>
                  </div>
               </div>
             </div>
             <div class="col-lg-12 col-12 form-group">
                <button type="submit" id="post_letter_policy" name="postpolicy" class="btn btn-primary pull-right">Save Letter Policy
               </button>
            </div>
          </div>
          </form>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <a id="saveskygrade"></a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add_school_Season" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add School Season</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card">
          <div class="fetchSchoolCurriclumHere"></div>
          <div class="placeOfQuarterGS">
            <form id="comment_form" action="<?php echo base_url() ?>setting/">
            <div class="row">
              <div class="col-lg-2 col-6">
                <div class="form-group">
                 <label for="ac">Academic year</label>
                  <select class="form-control selectric"
                    required="required" name="ac" 
                    id="ac">
                    <?php foreach($academicyear as $academicyears){ ?>
                      <option>
                        <?php echo $academicyears->year_name ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-2 col-6">
                <div class="form-group">
                 <label for="ac">School Curriclum</label>
                  <select class="form-control selectric" required="required" name="termGroup" id="termGroup">

                  </select>
                </div>
             </div>
             <div class="col-lg-2 col-6">
                <div class="form-group">
                  <label for="Mobile">Season Name</label>
                    <input class="form-control" id="term" 
                    required="required" name="term" type="text" placeholder="Season name">
               </div>
             </div>
              <div class="col-lg-3 col-6">
                <div class="form-group">
                  <label for="Mobile">Start Date</label>
                    <input class="form-control" id="startdate" 
                    required="required" name="startdate" type="date">
                </div>
             </div>
             <div class="col-lg-3 col-12">
                <div class="form-group">
                  <label for="Mobile">End Date</label>
                    <input class="form-control" id="endate" 
                    required="required" name="endate" type="date">
                </div>
             </div>
             <div class="col-lg-12 col-12" id="grajosstad">
              <div class="form-group">
                <label for="Mobile">Select Grade: </label>
                <input type="checkbox" class="" id="selectAll_gradeGS" onClick="selectAll_gs()">Select All
                <div class="row">
                  <?php foreach($gradeee as $grades){ ?>
                    <div class="col-lg-1 col-3">
                      <div class="pretty p-bigger">
                      <input type="checkbox" name="quarter_grade[]" value="<?php echo $grades->grade; ?>" class="quarter_grade" id="customCheck1">
                      <div class="state p-info">
                        <i class="icon material-icons"></i>
                        <label></label><?php echo $grades->grade; ?>
                      </div>
                     </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="col-lg-12 col-12">
              <button type="submit" id="posterm" name="posterm" class="btn btn-info pull-right">Save Season
             </button>
            </div>
            </div>
          </form>
        </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <a id="saveskygrade"></a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add_school_branch" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add School Branch</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="<?php echo base_url() ?>setting/">
            <div class="row">
              <div class="col-lg-4 col-6">
                <div class="form-group">
                  <input class="form-control" id="branch" name="branch" required="required" type="text" placeholder="Branch name here...">
               </div>
             </div>
             <div class="col-lg-4 col-6">
               <select class="form-control selectric" required="required" name="bac" id="ac">
                  <?php foreach($academicyear as $academicyears){ ?>
                    <option>
                      <?php echo $academicyears->year_name ?>
                    </option>
                  <?php } ?>
                </select>
             </div>
             <div class="col-lg-4 form-group">
                <button type="submit" id="postyear"
                 name="postbranch" class="btn btn-info btn-block">Save Branch
               </button>
            </div>
          </div>
          </form>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <a id="saveskygrade"></a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add_academic_year" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add Academic Year</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="<?php echo base_url() ?>setting/">
            <div class="row">
              <div class="col-lg-5 col-6">
                <div class="form-group">
                    <input class="form-control" id="academicyear" name="academicyear" required="required" type="text" placeholder="Ethiopian academic year here...">
               </div>
              </div>
              <div class="col-lg-5 col-6">
                <div class="form-group">
                    <input class="form-control" id="gacademicyear" name="gacademicyear" required="required" type="text" placeholder="Gregorian academic year here...">
               </div>
              </div>
             <div class="col-lg-2 col-12">
                <button type="submit" id="postyear" name="postyear" class="btn btn-primary btn-block">Save Year
               </button>
            </div>
          </div>
          </form>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <a id="saveskygrade"></a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add_school_information" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add School Information</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <?php foreach($schools as $school){ ?>
           <?php echo form_open_multipart('Setting/schoolsetting');?>
            <div class="row">
              <div class="col-lg-4 col-12">
                <div class="form-group">
                <input class="form-control" name="sname" required="required" type="text" value="<?php echo $school->name; ?>" placeholder="School Name">
               </div>
             </div>
             <div class="col-lg-4 col-12">
                <div class="form-group">
                <input class="form-control" name="s2name" type="text" value="<?php echo $school->name_2; ?>" placeholder="School Name second language...">
               </div>
             </div>
             <div class="col-lg-4 col-12">
              <div class="form-group">
               <div class="custom-file">
                <input type="file" name="logo" class="custom-file-input" id="customFile">
                <label class="custom-file-label" for="customFile">Choose Logo</label>
              </div>
              </div>
             </div>
             <div class="col-lg-3 col-12">
                <div class="form-group">
                <input class="form-control" name="email" required="required" type="email" value="<?php echo $school->email; ?>" placeholder="School email">
               </div>
             </div>
             <div class="col-lg-3 col-12">
                <div class="form-group">
                <input class="form-control" name="address" required="required" type="text" value="<?php echo $school->address; ?>" placeholder="School Address">
               </div>
             </div>
             <div class="col-lg-3 col-12">
                <div class="form-group">
                <input class="form-control" name="slogan" required="required" type="text" value="<?php echo $school->slogan; ?>" placeholder="School slogan here">
               </div>
             </div>
             <div class="col-lg-3 col-12">
                <div class="form-group">
                <input class="form-control" name="schoolwebsite" required="required" type="text" value="<?php echo $school->website; ?>" placeholder="School Website">
               </div>
             </div>
             <div class="col-lg-6 col-12">
                <div class="form-group">
                <textarea name="mobile" class="form-control summernote-simple bio">
                <?php echo $school->phone; ?>
              </textarea>
               </div>
             </div>
             <div class="col-lg-6 col-12">
              <textarea name="about" class="form-control summernote-simple bio">
                <?php echo $school->about; ?>
              </textarea>
              </div>
            <div class="col-lg-12 col-12">
              <button type="submit" value="upload" name="postschool" class="btn btn-primary pull-right ">Save Information </button>
            </div>
          </div>
        </form>      
        <?php } ?>    
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <a id="saveskygrade"></a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="newNonWorkingDays" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Add Non-Working Dates</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <ul class="nav nav-tabs" id="myTab2" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab112" data-toggle="tab" href="#add_new_days" role="tab" aria-selected="true"> Add Non-Working Dates</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="home-tab22" data-toggle="tab" href="#view_days" role="tab" aria-selected="false">View Dates</a>
            </li>
          </ul>
          <div class="tab-content tab-bordered" id="myTab3Content">
            <div class="tab-pane fade show active" id="add_new_days" role="tabpanel" aria-labelledby="home-tab112">
              <form method="POST" id="saveNewSchool_days" class="saveNewSchool_days" name="saveNewSchool_days">
                <div class="form-group">
                  <div class="search-element">
                    <div class="row">
                      <div class="form-group col-lg-4 col-6">
                        <label>Non-Working Date</label>
                        <input id="non_working_dats" type="date" class="form-control" required="required" name="non_working_dats" placeholder="Non Working Date...">
                      </div>
                      <div class="form-group col-lg-8 col-6">
                        <label>Reason</label>
                        <input id="non_working_reason" type="text" class="form-control" required="required" name="non_working_reason" placeholder="Reason...">
                      </div>
                      <div class="form-group col-lg-10"></div>
                      <div class="form-group col-lg-2 col-12 pull-right">
                        <button class="btn btn-primary pull-right" name="save_days" id="save_days"> Save Days
                        </button>
                      </div>
                    </div>
                    <h4 class="msg_non" id="msg_non"></h4>
                  </div>
                </div>
              </form>
            </div>
            <div class="tab-pane fade show" id="view_days" role="tabpanel" aria-labelledby="home-tab22">
              <div class="fetch_school_non_working_days"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="newWeek" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Manage School Week</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <ul class="nav nav-tabs" id="myTab2" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab11" data-toggle="tab" href="#add_new_Week" role="tab" aria-selected="true"> Add Week</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="home-tab22" data-toggle="tab" href="#view_week" role="tab" aria-selected="false">View Week</a>
            </li>
          </ul>
          <div class="tab-content tab-bordered" id="myTab3Content">
            <div class="tab-pane fade show active" id="add_new_Week" role="tabpanel" aria-labelledby="home-tab11">
              <form method="POST" id="saveNewSchool_week" class="saveNewSchool_week" name="saveNewSchool_week">
                <div class="form-group">
                  <div class="search-element">
                    <div class="row">
                      <div class="form-group col-lg-6 col-6">
                        <label>Week Name</label>
                        <input id="week_Name" type="text" class="form-control" required="required" name="week_Name" placeholder="Week name here...">
                      </div>
                      <div class="form-group col-lg-3 col-6">
                        <label>Week Start Date</label>
                        <input id="week_date" type="date" class="form-control" required="required" name="start_date" placeholder="Week name here...">
                      </div>
                      <div class="form-group col-lg-3 col-6">
                        <label>Week End Date</label>
                        <input id="week_date" type="date" class="form-control" required="required" name="end_date" placeholder="Week name here...">
                      </div>
                      <div class="form-group col-lg-12 col-6">
                        <button class="btn btn-primary pull-right" name="save_vaccination" id="save_vaccination"> Save Week
                        </button>
                      </div>
                    </div>
                    <h4 class="msg" id="msg"></h4>
                  </div>
                </div>
              </form>
            </div>
            <div class="tab-pane fade show" id="view_week" role="tabpanel" aria-labelledby="home-tab2">
              <div class="fetch_school_week"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="edit_school_season" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit school season</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card">
          <div class="form-group" id="placeOfQuarterGS"></div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="add_school_promotion_grade_level" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">School Grade Level</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card">
          <div class="StudentViewTextInfo">
            <div class="gradeLevel"></div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add_school_promotion_policy" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">School Promotion Policy</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card">
          <div class="StudentViewTextInfo">
            <form method="POST" id="save_policy">
              <div class="row">
                <div class="col-lg-6 col-6">
                  <div class="form-group">
                    <label>Number of subjects failed</label>
                      <input class="form-control" id="noOsubjectsFailed"  required="required" name="noOsubjectsFailed" type="number" placeholder="Number of subjects failed">
                 </div>
               </div>
                <div class="col-lg-6 col-6">
                  <div class="form-group">
                    <label>Percentile (Optional)</label>
                      <input class="form-control" id="policy_average" name="policy_average" type="number" placeholder="Yearly Average">
                 </div>
               </div>
               <div class="col-lg-12 col-12" id="grajosstad">
                  <div class="form-group">
                    <label for="Mobile">Grade: </label>
                      <?php foreach($gradeeeee as $grades){ ?>
                        <div class="pretty p-icon p-bigger">
                        <input type="checkbox" name="policy_grade" value="<?php echo $grades->grade; ?>" class="policy_grade" id="customCheck1">
                        <div class="state p-info">
                          <i class="icon material-icons"></i>
                          <label></label><?php echo $grades->grade; ?>
                        </div>
                       </div>
                    <?php } ?>
                 </div>
               </div>
               <div class="col-lg-12 col-12">
                  <button type="submit" id="post_policy" name="postpolicy" class="btn btn-primary pull-right">Save Policy
                 </button>
              </div>
            </div>
            </form>
            <div class="card-body" id="fetch_promotion_policy"> </div>
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
  <script src="<?php echo base_url(); ?>assets/summernote/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/css/daterangepicker.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
    load_promotion_policy();
    function load_promotion_policy() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/fetchPromotionPolicy/',
        cache: false,
        beforeSend: function() {
          $('#fetch_promotion_policy').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
          );
        },
        success: function(html){
         $('#fetch_promotion_policy').html(html);
        }
      })
    }
    $(document).on('click', '#delete_promotion_policy', function() {
      var textId = $(this).attr("value");
      swal({
        title: 'Are you sure you want to delete this record ?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
          if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>setting/deletePromotionPolicy/",
            data: ({
              textId: textId
            }),
            cache: false,
            success: function(html) {
              load_promotion_policy();
            }
          });
        }
      });
    });
    $("#save_policy").on("submit",function(event){
      event.preventDefault();
      if($("#noOsubjectsFailed").val()!=''){
        var policy_average=$("#policy_average").val();
        var failedSubjects=$("#noOsubjectsFailed").val();
        policy_grade=[];
        $("input[name='policy_grade']:checked").each(function(i){
          policy_grade[i]=$(this).val();
        });
        $.ajax({
          url:"<?php echo base_url() ?>setting/savePromotionPolicy/",
          method:"POST",
          data:({
            policy_average:policy_average,
            policy_grade: policy_grade,
            failedSubjects:failedSubjects
          }),
          beforeSend:function(){
            $("#post_policy").attr("disabled","disabled");
          },
          success: function(){
            load_promotion_policy();
            $("#save_policy")[0].reset();
            $("#post_policy").removeAttr("disabled");
          }
        });
      }else{
        alert('Please enter necessary fields.');
      }
    });
  });
</script>
  <script type="text/javascript">
    $(document).on('click', '.editerm', function() { 
      var term_id = $(this).attr("id");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>setting/fetchTermToEdit/",
          data: ({
              term_id: term_id
          }),
          cache: false,
          beforeSend: function() {
            $('#placeOfQuarterGS').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
          },
          success: function(html) {
            $('#placeOfQuarterGS').html(html);
          }
        });
    });
  </script>
  <script type="text/javascript">
    load_school_non_working_dats();
    function load_school_non_working_dats()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>setting/load_school_non_working_dates/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_school_non_working_days').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success:function(data){
          $('.fetch_school_non_working_days').html(data);
        }
      })
    }
    $(document).on('submit', '#saveNewSchool_days', function(e) {
    e.preventDefault();
    if ($('#non_working_reason').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>setting/save_new_non_working_dates/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#msg_non').html( '<span class="text-info">Saving...</span>');
        },
        success: function(html){
          $("#msg_non").html(html);
          $('#non_working_reason').val('');
          load_school_non_working_dats();
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '.deleteSchoolNon_working_date', function() {
    var textId = $(this).attr("value");
    swal({
      title: 'Are you sure you want to delete this Date ?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>setting/delete_school_non_working_dates/",
          data: ({
            textId: textId
          }),
          cache: false,
          success: function(html) {
            load_school_non_working_dats();
          }
        });
      }
    });
  });
  </script>
  <script type="text/javascript">
    load_school_week();
    function load_school_week()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>setting/load_school_week/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_school_week').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success:function(data){
          $('.fetch_school_week').html(data);
        }
      })
    }
    $(document).on('submit', '#saveNewSchool_week', function(e) {
    e.preventDefault();
    if ($('#week_Name').val() != '') {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>setting/save_new_week/",
        data:new FormData(this),
        processData:false,
        contentType:false,
        cache: false,
        async:false,
        beforeSend: function() {
          $('#msg').html( '<span class="text-info">Saving...</span>');
        },
        success: function(html){
          $("#msg").html(html);
          $('#week_Name').val('');
          load_school_week();
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '.deleteSchoolWeek', function() {
      var textId = $(this).attr("value");
      swal({
        title: 'Are you sure you want to delete this Week ?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>setting/delete_school_week/",
          data: ({
            textId: textId
          }),
          cache: false,
          success: function(html) {
            load_school_week();
          }
        });
      }
    });
  });
  </script>
  <script type="text/javascript">
  $(document).ready(function(){
    load_grade_level();
    function load_grade_level()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>setting/load_grade_level/",
        method:"POST",
        beforeSend: function() {
          $('.gradeLevel').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">');
        },
        success:function(data){
          $('.gradeLevel').html(data);
        }
      })
    }
    $(document).on('click', '.saveGradeLevel', function()
    {
      event.preventDefault();
      next_grade=[];
      $("input[name='next_grade']:checked").each(function(i){
        next_grade[i]=$(this).val();
      });
      var pre_grade=$('#pre_grade').val();
      if($('#pre_grade').val()!='--- Select grade ---'){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>setting/save_grade_level/",
          data: ({
            pre_grade:pre_grade,
            next_grade:next_grade
          }),
          cache: false,
          success: function(html){
            load_grade_level();
          }
        });
      }else{
        swal('Oooops, Please select grade fields!', {
          icon: 'warning',
        });
      }
    });
  $(document).on('click', '#removeGradeLevel', function()
  {
    var preGrade=$(this).attr("name");
    var nextGrade=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>setting/remove_grade_level/",
      data: ({
        preGrade: preGrade,
        nextGrade:nextGrade
      }),
      cache: false,
      beforeSend: function() {
        $('.removeGradeLevel' + preGrade + nextGrade).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="10" height="10" id="loa">'
        );
      },
      success: function(html){
        $(".removeGradeLevel" + preGrade + nextGrade).fadeOut('slow');
      }
    });  
  }); 
});
</script>
  <script type="text/javascript">
  function selectAll_gs(){
      var itemsall=document.getElementById('selectAll_gradeGS');
      if(itemsall.checked==true){
      var items=document.getElementsByName('quarter_grade[]');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('quarter_grade[]');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
  }
</script>
  <script type="text/javascript">
    $(document).on('click', "input[name='onoffQuarter']", function() {
      var term = $(this).attr("id");
      if($(this).is(':checked')){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Setting/onQuarter/",
            data: ({
              term:term
            }),
            cache: false,
            success: function(html) {
              iziToast.success({
                title: 'Quarter status saved successfully',
                message: '',
                position: 'bottomCenter'
              });
            }
          });
      }else{
        var term = $(this).attr("id");
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Setting/offQuarter/",
          data: ({
            term:term
          }),
          cache: false,
          success: function(html) {
            iziToast.success({
              title: 'Quarter status saved successfully',
              message: '',
              position: 'bottomCenter'
            });
          }
        });
      }
    });
  $(document).ready(function(){
    enableHomeRoomAccess();
    function enableHomeRoomAccess() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/enableHomeRoomAccess/',
        cache: false,
        beforeSend: function() {
          $('.isHomeroomAccesMarkHere').html( 'Submiting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
         $('.isHomeroomAccesMarkHere').html(html);
        }
      })
    }
  
  $(document).on('click', "input[name='isHomeroomAccesMark']", function() {
    var markon=$(this).attr("value");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>setting/saveEnableHomeRoomAcces/",
          method:"POST",
          data:({
            markon:markon
          }),
          success: function(){
            iziToast.success({
              title: 'Hoom Room can access assigned mark.',
              message: '',
              position: 'bottomCenter'
            });
          }
        });
      }else{
        var markon=$(this).attr("value");
        $.ajax({
          url:"<?php echo base_url() ?>setting/deleteHomeRomAccess/",
          method:"POST",
          data:({
             markon:markon
          }),
          success: function(){
            iziToast.success({
              title: 'Hoom Room can not access assigned mark.',
              message: '',
              position: 'bottomCenter'
            });
          }
        });
      }
    });
  });
</script>
  <script type="text/javascript">
  $(document).on('change', '#isassmandatory', function() {
    var value=$(this).find('option:selected').attr('value');
    var sasgrade=$(this).find('option:selected').attr('name');
    var sasname=$(this).find('option:selected').attr('class');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>setting/updateAssesmentMandatory/",
        data: ({
          value:value,
          sasgrade:sasgrade,
          sasname:sasname
        }),
        success: function(data) {
          iziToast.success({
            title: 'Assesment',
            message: 'Updated successfully',
            position: 'topRight'
          });
        }
    });
  });
  $(document).on('change', '#isassOrder', function() {
    var value=$(this).find('option:selected').attr('value');
    var sasgrade=$(this).find('option:selected').attr('name');
    var sasname=$(this).find('option:selected').attr('class');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>setting/updateAssesmentOrder/",
        data: ({
          value:value,
          sasgrade:sasgrade,
          sasname:sasname
        }),
        success: function(data) {
          iziToast.success({
            title: 'Assesment',
            message: 'Updated successfully',
            position: 'topRight'
          });
        }
    });
  });
  $(document).on('change', '#isassPercentage', function() {
    var value=$(this).find('option:selected').attr('value');
    var sasgrade=$(this).find('option:selected').attr('name');
    var sasname=$(this).find('option:selected').attr('class');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>setting/updateAssesmentPercentage/",
        data: ({
          value:value,
          sasgrade:sasgrade,
          sasname:sasname
        }),
        success: function(data) {
          iziToast.success({
            title: 'Assesment',
            message: 'Updated successfully',
            position: 'topRight'
          });
        }
    });
  });
</script>
  <script type="text/javascript">
    
  $(document).ready(function(){
    loadQuareter();
    loadSchoolCurriclum();
    loadSchoolCurriclum4Use();
    loadSchoolAssesmentFilter();
    function loadSchoolAssesmentFilter() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/fetchEval4AssesmentFilter/',
        cache: false,
        beforeSend: function() {
          $('#schoolAssesmentEva').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
          $('#schoolAssesmentEva').html(html);
        }
      })
    }
    function loadQuareter() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/fetchQuarter/',
        cache: false,
        beforeSend: function() {
          $('.placeOfQuarter').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
          $('.placeOfQuarter').html(html);
        }
      })
    }
    function loadSchoolCurriclum() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/fetchschoolcurriclum/',
        cache: false,
        beforeSend: function() {
          $('.fetchSchoolCurriclumHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
          $('.fetchSchoolCurriclumHere').html(html);
        }
      })
    }
    function loadSchoolCurriclum4Use() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/loadSchoolCurriclum4Use/',
        cache: false,
        beforeSend: function() {
          $('#termGroup').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
          $('#termGroup').html(html);
        }
      })
    }
    $(document).on('click', "input[name='schoolAnnualCurriclum']", function() {
      var dname = $(this).attr("value");
      if($(this).is(':checked')){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>setting/feedschoolcurriclum/",
            data: ({
              dname:dname
            }),
            cache: false,
            success: function(html) {
              loadQuareter();
              loadSchoolCurriclum();
              loadSchoolCurriclum4Use();
              iziToast.success({
                title: 'Your Setting has been saved successfully',
                message: '',
                position: 'bottomCenter'
              });
            }
          });
      }else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>setting/deleteschoolcurriclum/",
          data: ({
            dname:dname
          }),
          cache: false,
          success: function(html) {
            loadQuareter();
            loadSchoolCurriclum();
            loadSchoolCurriclum4Use();
            iziToast.success({
              title: 'Your Setting has been deleted successfully',
              message: '',
              position: 'bottomCenter'
            });
          }
        });
      }
    });
});
</script>
  <!--  -->
  <script type="text/javascript">
  $(document).ready(function(){
    enableCommunicationBookApproval();
    function enableCommunicationBookApproval() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/enableCommunicationBookAuto/',
        cache: false,
        beforeSend: function() {
          $('.enableApproveCommunicationBook').html( 'Checking...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
         $('.enableApproveCommunicationBook').html(html);
        }
      })
    }
  
  $(document).on('click', "input[name='enableapproveCommunication']", function() {
    var markon=$(this).attr("value");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>setting/saveEnableCoomBookApprove/",
          method:"POST",
          data:({
            markon:markon
          }),
          success: function(){
            iziToast.success({
              title: 'Communication book approval ON.',
              message: '',
              position: 'topRight'
            });
          }
        });
      }else{
        var markon=$(this).attr("value");
        $.ajax({
          url:"<?php echo base_url() ?>setting/deleteEnableCoomBookApprove/",
          method:"POST",
          data:({
             markon:markon
          }),
          success: function(){
            iziToast.success({
              title: 'Communication book approval OFF.',
              message: '',
              position: 'topRight'
            });
          }
        });
      }
    });
  });
</script>
  
  <script type="text/javascript">
  $(document).ready(function(){
    enableMarkAuto();
    function enableMarkAuto() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/enableMarkAuto/',
        cache: false,
        beforeSend: function() {
          $('.enableApproveMark').html( 'Checking...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
         $('.enableApproveMark').html(html);
        }
      })
    }
  
  $(document).on('click', "input[name='enableapprovemark']", function() {
    var markon=$(this).attr("value");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>setting/saveEnableMarkApprove/",
          method:"POST",
          data:({
            markon:markon
          }),
          success: function(){
            iziToast.success({
              title: 'Mark approve ON.',
              message: '',
              position: 'topRight'
            });
          }
        });
      }else{
        var markon=$(this).attr("value");
        $.ajax({
          url:"<?php echo base_url() ?>setting/deleteEnableMarkApprove/",
          method:"POST",
          data:({
             markon:markon
          }),
          success: function(){
            iziToast.success({
              title: 'Mark approve Off.',
              message: '',
              position: 'topRight'
            });
          }
        });
      }
    });
  });
</script>
  <script type="text/javascript">
  $(document).ready(function(){
    checkMarkAutoLock();
    on_off_registration_page();
    ageCalculationMethod();
    checkScheduleMarkResult();
    studentsCanSeeQuarterResult();
    function checkScheduleMarkResult() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/check_markresult_schedule/',
        cache: false,
        beforeSend: function() {
          $('.scheduleMarkResult').html( 'Checking...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
         $('.scheduleMarkResult').html(html);
        }
      })
    }
    $(document).on('click', '#saveSchedule', function() { 
      var scheduleType=$('#scheduledType').val();
      var scheduleDate=$('#scheduleMarkResult').val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>setting/schedule_task/",
        data: ({
          scheduleType:scheduleType,
          scheduleDate:scheduleDate
        }),
        cache: false,
        beforeSend: function() {
          $('.scheduleInfo').html( 'Please wait...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html) {
          checkScheduleMarkResult();
          $('.scheduleInfo').html(html);
        }
      });
    });
    $(document).on('click', '#deleteSchedule', function() { 
      var scheduleID=$(this).attr('value');
      swal({
        title: 'Are you sure you want to delete this schedule?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>setting/delete_schedule_task/",
            data: ({
              scheduleID:scheduleID
            }),
            cache: false,
            beforeSend: function() {
              $('.scheduleInfo').html( 'Please wait...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
              );
            },
            success: function(html) {
              checkScheduleMarkResult();
              $('.scheduleInfo').html(html);
            }
          });
        }
      });
    });
    function studentsCanSeeQuarterResult() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/studentsCanSeeQuarterResult/',
        cache: false,
        beforeSend: function() {
          $('.studentsCanSeeQuarterResult').html( 'Checking...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
         $('.studentsCanSeeQuarterResult').html(html);
        }
      })
    }
    function checkMarkAutoLock() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/lockMarkAutomatically/',
        cache: false,
        beforeSend: function() {
          $('.lockMarkAutomatically').html( 'Checking...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(html){
         $('.lockMarkAutomatically').html(html);
        }
      })
    }
    function on_off_registration_page() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/onoff_registration_page/',
        cache: false,
        beforeSend: function() {
          $('.on_off_registration_page').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
        },
        success: function(html){
         $('.on_off_registration_page').html(html);
        }
      })
    }
    function ageCalculationMethod() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/ageCalculationMethod/',
        cache: false,
        beforeSend: function() {
          $('.ageCalculationMethod').html( 'Checking...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
         $('.ageCalculationMethod').html(html);
        }
      })
    }
  $(document).on('change', '#ageMethod', function() {
    var valueAge=$(this).find('option:selected').attr('value');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>setting/updateAgeMethod/",
        data: ({
          valueAge:valueAge
        }),
        beforeSend: function() {
          $('.ageCalculationMethod').html( 'Updating...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(data) {
          ageCalculationMethod();
          iziToast.success({
            title: 'Age calculation method updated successfully.',
            message: '',
            position: 'topRight'
          });
        }
    });
  });
  $(document).on('click', "input[name='registration_of_off']", function() {
    var lockmark=$(this).attr("value");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>setting/save_registration_page_status/",
          method:"POST",
          data:({
            lockmark:lockmark
          }),
          success: function(){
            iziToast.success({
              title: 'Registration page is open',
              message: '',
              position: 'topRight'
            });
          }
        });
      }else{
        var lockmark=$(this).attr("value");
        $.ajax({
          url:"<?php echo base_url() ?>setting/delete_registration_page_status/",
          method:"POST",
          data:({
             lockmark:lockmark
          }),
          success: function(){
            iziToast.success({
              title: 'Registration page is closed',
              message: '',
              position: 'topRight'
            });
          }
        });
      }
    });
  $(document).on('click', "input[name='lockmarkautoOn']", function() {
    var lockmark=$(this).attr("value");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>setting/saveLockMarkAuto/",
          method:"POST",
          data:({
            lockmark:lockmark
          }),
          success: function(){
            iziToast.success({
              title: 'Mark',
              message: 'Will locked automatically',
              position: 'bottomCenter'
            });
          }
        });
      }else{
        var lockmark=$(this).attr("value");
        $.ajax({
          url:"<?php echo base_url() ?>setting/deleteLockMarkAuto/",
          method:"POST",
          data:({
             lockmark:lockmark
          }),
          success: function(){
            iziToast.success({
              title: 'Mark',
              message: 'lock deleted successfully',
              position: 'bottomCenter'
            });
          }
        });
      }
    });
    $(document).on('click', "input[name='can_see_card']", function() {
      var lockmarkk=$(this).attr("value");
      var quarter=$(this).attr("id");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>setting/save_can_see_report_card/",
          method:"POST",
          data:({
            lockmark:lockmarkk,
            quarter:quarter
          }),
          success: function(){
            iziToast.success({
              title: 'Student will see thier report card.',
              message: '',
              position: 'topRight'
            });
          }
        });
      }else{
        var lockmarkk=$(this).attr("value");
        var quarterr=$(this).attr("id");
        $.ajax({
          url:"<?php echo base_url() ?>setting/delete_can_see_report_card/",
          method:"POST",
          data:({
             lockmark:lockmarkk,
             quarter:quarterr
          }),
          success: function(){
            iziToast.success({
              title: 'Student will no longer see thier report card.',
              message: '',
              position: 'topRight'
            });
          }
        });
      }
    });
  });
</script>
  <script type="text/javascript">
  $(document).ready(function(){
    checkNewYear();
    function checkNewYear() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/checkNewAcademicYear',
        cache: false,
        beforeSend: function() {
          $('.checkNewYear').html( 'Checking...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
         $('.checkNewYear').html(html);
        }
      })
    }
  });
  $(document).on('click', '#prepareEveryThing', function() { 
    if (confirm("Are you sure you want to move last year setting?")) {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>setting/moveSetting/",
        cache: false,
        beforeSend: function() {
          $('.checkNewYear').html( 'Starting form branch, Please wait...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html) {
          $('.checkNewYear').html(html);
        }
      });
    } else {
      return false;
    }
  });
  $(document).on('click', '#moveKgsubjectObjective', function() { 
    swal({
      title: 'Are you sure you want to move KG Subject Objective?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>setting/movingSubjectObjective/",
          cache: false,
          beforeSend: function() {
            $('.checkNewYear').html( 'Please wait...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
            );
          },
          success: function(html) {
            $('.checkNewYear').html(html);
          }
        });
      } else {
        return false;
      }
    });
  });
</script>
<script type="text/javascript">
  /*report card comment starts*/
  $(document).ready(function(){
    loadreportcardComment();
    function loadreportcardComment() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/fetchrcComment/',
        cache: false,
        beforeSend: function() {
          $('.fetchReportCardComment').html( 'Loading comments...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
         $('.fetchReportCardComment').html(html);
        }
      })
    }
    $(document).on('click', '#moveLastYearRComments', function() { 
      swal({
        title: 'Are you sure you want to move last year comments?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>setting/movingLastyearComments/",
            cache: false,
            beforeSend: function() {
              $('.fetchReportCardComment').html( 'Please wait...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
              );
            },
            success: function(html) {
              $('.fetchReportCardComment').html(html);
              loadreportcardComment();
            }
          });
        } 
      });
    });
    $("#postRcComment").on("click",function(event){
      var minValue=$("#minValue").val();
      var maxValue=$("#maxValue").val();
      var reportcardComment=$("#reportcardComment").val();
      commentGrade=[];
      $("input[name='commentGrade']:checked").each(function(i){
        commentGrade[i]=$(this).val();
      });
      if($("#reportcardComment").val()!==''){
        $.ajax({
          url:"<?php echo base_url() ?>setting/saveReportcardComment/",
          method:"POST",
          data:({
            minValue:minValue,
            maxValue:maxValue,
            reportcardComment:reportcardComment,
            commentGrade:commentGrade
          }),
          beforeSend:function(){
            $("#postRcComment").attr("disabled","disabled");
          },
          success: function(){
            loadreportcardComment();
            $("#reportcardComment").val('');
            $("#postRcComment").removeAttr("disabled");
          }
        });
      }
    });
    $(document).on('click', '.deleteCommentValue', function() { 
      var commentValue = $(this).attr("id");
      var mingradevalue = $(this).attr("value");
      var maxgradevalue = $(this).attr("name");
      if (confirm("Are you sure you want to delete this Comment?")) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>setting/deleteRCcomment/",
          data: ({
              mingradevalue: mingradevalue,
              maxgradevalue: maxgradevalue,
              commentValue:commentValue
          }),
          cache: false,
          success: function(html) {
            loadreportcardComment();
          }
        });
      } else {
        return false;
      }
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
<!-- save division starts -->
<script type="text/javascript">
  $(document).ready(function(){
    loadDivision();
    function loadDivision() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/fetchDivision/',
        cache: false,
        beforeSend: function() {
          $('.fetchDivision').html( 'Loading Division...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
         $('.fetchDivision').html(html);
        }
      })
    }
    $("#postDivision").on("click",function(event){
      if($("#divisionName").val()!=''){
        var divisionName=$("#divisionName").val();
        $.ajax({
          url:"<?php echo base_url() ?>setting/saveSchoolDivision/",
          method:"POST",
          data:({
            divisionName:divisionName
          }),
          beforeSend:function(){
            $("#divisionName").attr("disabled","disabled");
          },
          success: function(){
            loadDivision();
            $("#divisionName").val('');
            $("#divisionName").removeAttr("disabled");
          }
        });
      }else{
        alert('Please enter necessary fields.');
      }
    });
    $(document).on('click', '.deleteDivision', function() { 
      var r_id = $(this).attr("id");
      if (confirm("Are you sure you want to delete this division ?")) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>setting/deleteDivision/",
          data: ({
              r_id: r_id
          }),
          cache: false,
          success: function(html) {
            $(".drange" + r_id).fadeOut('slow');
            loadDivision();
          }
        });
      } else {
        return false;
      }
    });
    /*delete academic year*/
    $(document).on('click', '.deleteyear', function() { 
      var yid = $(this).attr("value");
      if (confirm("Are you sure you want to delete this Academic Year ?")) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>setting/deleteyear/",
          data: ({
              yid: yid
          }),
          cache: false,
          success: function(html) {
            $(".delete_year" + yid).fadeOut('slow');
          }
        });
      } else {
        return false;
      }
    });
  });
</script>
<!-- Rank policy Starts -->
<script type="text/javascript">
  $(document).on('click', "input[name='totalGradeName']", function() {
    var grade=$(this).attr("value");
    var rowname=$(this).attr("id");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>setting/saveTotalPolicy/",
          method:"POST",
          data:({
            grade: grade,
            rowname:rowname
          }),
          beforeSend:function(){
            $("#post_rank_policy").attr("disabled","disabled");
          },
          success: function(){
            iziToast.success({
              title: 'Rank Policy',
              message: 'saved successfully',
              position: 'topRight'
            });
          }
        });
      }else{
        $.ajax({
          url:"<?php echo base_url() ?>setting/deleteTotalPolicy/",
          method:"POST",
          data:({
            grade: grade,
            rowname:rowname
          }),
          beforeSend:function(){
            $("#post_rank_policy").attr("disabled","disabled");
          },
          success: function(){
            iziToast.success({
              title: 'Rank Policy',
              message: 'deleted successfully',
              position: 'topRight'
            });
          }
        });
      }
  });
  $(document).on('click', "input[name='averageGradeName']", function() {
    var grade=$(this).attr("value");
    var rowname=$(this).attr("id");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>setting/saveAveragePolicy/",
          method:"POST",
          data:({
            grade: grade,
            rowname:rowname
          }),
          beforeSend:function(){
            $("#post_rank_policy").attr("disabled","disabled");
          },
          success: function(){
            iziToast.success({
              title: 'Average Policy',
              message: 'saved successfully',
              position: 'topRight'
            });
          }
        });
      }else{
        $.ajax({
          url:"<?php echo base_url() ?>setting/deleteAveragePolicy/",
          method:"POST",
          data:({
            grade: grade,
            rowname:rowname
          }),
          beforeSend:function(){
            $("#post_rank_policy").attr("disabled","disabled");
          },
          success: function(){
            iziToast.success({
              title: 'Average Policy',
              message: 'deleted successfully',
              position: 'topRight'
            });
          }
        });
      }
  });
  $(document).on('click', "input[name='rankGradeName']", function() {
    var grade=$(this).attr("value");
    var rowname=$(this).attr("id");
      if($(this).is(':checked')){
        $.ajax({
          url:"<?php echo base_url() ?>setting/saveRankPolicy/",
          method:"POST",
          data:({
            grade: grade,
            rowname:rowname
          }),
          beforeSend:function(){
            $("#post_rank_policy").attr("disabled","disabled");
          },
          success: function(){
            iziToast.success({
              title: 'Rank Policy',
              message: 'saved successfully',
              position: 'topRight'
            });
          }
        });
      }else{
        $.ajax({
          url:"<?php echo base_url() ?>setting/deleteRankPolicy/",
          method:"POST",
          data:({
            grade: grade,
            rowname:rowname
          }),
          beforeSend:function(){
            $("#post_rank_policy").attr("disabled","disabled");
          },
          success: function(){
            iziToast.success({
              title: 'Rank Policy',
              message: 'deleted successfully',
              position: 'topRight'
            });
          }
        });
      }
  });
  </script>
<!-- Save rank Ends -->
<!-- Letter policy Starts -->
<script type="text/javascript">
  $(document).ready(function(){
    load_letter_policy();
    function load_letter_policy() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/fetchLetterPolicy/',
        cache: false,
        beforeSend: function() {
          $('#fetch_letter_policy').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
         $('#fetch_letter_policy').html(html);
        }
      })
    }
    $(document).on('click', '#moveLastYearLetterRange', function() { 
      swal({
        title: 'Are you sure you want to move last year letter range?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>setting/movingLastyearLetterRange/",
            cache: false,
            beforeSend: function() {
              $('#fetch_letter_policy').html( 'Please wait...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
              );
            },
            success: function(html) {
              $('#fetch_letter_policy').html(html);
              load_letter_policy();
            }
          });
        } 
      });
    });
    $("#save_letter_policy").on("submit",function(event){
      event.preventDefault();
      if($(".letter_grade").val()!=''){
        startRange=$('#startRange').val();
        endRange=$('#endRange').val();
        valtext=$('#valtext').val();
        letter_grade=[];
        $("input[name='letter_grade']:checked").each(function(i){
          letter_grade[i]=$(this).val();
        });
        $.ajax({
          url:"<?php echo base_url() ?>setting/saveLetterPolicy/",
          method:"POST",
          data:({
            letter_grade: letter_grade,
            startRange:startRange,
            endRange:endRange,
            valtext:valtext
          }),
          beforeSend:function(){
            $("#post_letter_policy").attr("disabled","disabled");
          },
          success: function(){
            load_letter_policy();
            $("#save_letter_policy")[0].reset();
            $("#post_letter_policy").removeAttr("disabled");
          }
        });
      }else{
        alert('Please enter necessary fields.');
      }
    });
    $(document).on('click', '.deleteLetterPolicy', function() { 
      var r_id = $(this).attr("id");
      var lminvalue = $(this).attr("name");
      var lmaxvalue = $(this).attr("value");
      swal({
        title: 'Are you sure you want to delete this letter Range?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>setting/deleteLetterPolicy",
            data: ({
                r_id: r_id,
                lminvalue:lminvalue,
                lmaxvalue:lmaxvalue
            }),
            cache: false,
            success: function(html) {
              load_letter_policy();
            }
          });
        }else {
          return false;
        }
      });
    });
  });
</script>
<!-- Save rank Ends -->
<script>
  $(document).ready(function() {
    loadQuareter();
    function loadQuareter() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>setting/fetchQuarter/',
        cache: false,
        beforeSend: function() {
          $('.placeOfQuarter').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
          $('.placeOfQuarter').html(html);
        }
      })
    }
    $('#comment_form').on('submit', function(event) {
      event.preventDefault();
      var form_data = $(this).serialize();
      $.ajax({
        url: "<?php echo base_url(); ?>setting",
        method: "POST",
        data: form_data,
        beforeSend: function() {
          $('#posterm').attr("disabled", "disabled");
        },
        success: function(data) {
          loadQuareter();
          $('#comment_form')[0].reset();
          $("#posterm").removeAttr("disabled");
        }
      })
    });    
    $(document).on('click', '.deleteterm', function() { 
      var term_id = $(this).attr("id");
      swal({
      title: 'Are you sure you want to delete this term ?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
        $.ajax({
          method: "GET",
          url: "<?php echo base_url(); ?>setting",
          data: ({
              term_id: term_id
          }),
          cache: false,
          success: function(html) {
            $(".delete_mem" + term_id).fadeOut('slow');
          }
        });
      } else {
          return false;
      }
      });
    });
  });
</script>

<script type="text/javascript">
  $(document).on('click', '.saveEditedQuarter', function() { 
      function loadQuarter() {
        $.ajax({
          method:'POST',
          url:'<?php echo base_url() ?>setting/fetchQuarter/',
          cache: false,
          beforeSend: function() {
            $('.placeOfQuarter').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
            );
          },
          success: function(html){
            $('.placeOfQuarter').html(html);
          }
        })
      }
      var termName = $('.termName').val();
      var termStartDate = $('.termStartDate').val();
      var termEndDate = $('.termEndDate').val();
      var termID=$('.termID').val();
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>setting/updateQuarter/",
          data: ({
            termName: termName,
            termStartDate:termStartDate,
            termEndDate:termEndDate,
            termID:termID
          }),
          cache: false,
          success: function(html) {
            loadQuarter();
            $('#edit_school_season').modal('hide');
          }
        });
    });
</script>
<script>
    /*function check_schedule(view = '') { 
      $.ajax({
        url: "<?php echo base_url() ?>check_schedule/",
        method: "POST",
        data: ({
            view: view
        }),
        dataType: "json",
        success: function(data) {
          $('.scheduleDoneTasks').html(data.notification);
          if (data.unseen_notification > 0) {
            $('.count-new-notification').html(data.unseen_notification);
          }
        }
      });
    }*/
    function check_update_age(view = '') { 
      $.ajax({
        url: "<?php echo base_url() ?>check_update_age/",
        method: "POST",
        data: ({
            view: view
        }),
        dataType: "json",
        success: function(data) {
          if(data=='1'){
            iziToast.success({
              title: 'Age updated successfully.',
              message: '',
              position: 'topRight'
            });
          }else{
            iziToast.error({
              title: 'Oooops Please try again.',
              message: '',
              position: 'topRight'
            });
          }
        }
      });
    }  
    var dayInMilliseconds = 1000 * 60 * 60 * 24;
    setInterval(function() {
      check_update_age();
    }, dayInMilliseconds );
</script>
</body>
</html>