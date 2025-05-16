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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
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
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
            <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab1" data-toggle="tab" href="#startSummerClassPage" role="tab" aria-selected="true"> Start Sum. class</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#SummerClassStudent" role="tab" aria-selected="true"> Student</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab9" data-toggle="tab" href="#SummerClassStudentPlacement" role="tab" aria-selected="true">Student Placement</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab8" data-toggle="tab" href="#SummerStudentID" role="tab" aria-selected="true"> ID Card</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#SummerClassSubject" role="tab" aria-selected="true"> Subject</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab4" data-toggle="tab" href="#SummerClassEvaluation" role="tab" aria-selected="true">Evaluation</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab5" data-toggle="tab" href="#SummerPlacement" role="tab" aria-selected="true"> Teacher Placement</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab8" data-toggle="tab" href="#SummerAttendance" role="tab" aria-selected="true"> Student Attendance</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab6" data-toggle="tab" href="#SummerClassMark" role="tab" aria-selected="true"> Mark Result</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab7" data-toggle="tab" href="#summerReportCard" role="tab" aria-selected="true">Report Card</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show" id="startSummerClassPage" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="row">
                      <div class="col-lg-4 col-6">
                        <input type="text" class="form-control summerAcademicYearName" name="summerAcademicYearName" id="summerAcademicYearName" placeholder="Local academic year...">
                      </div>
                      <div class="col-lg-4 col-6">
                         <input type="text" class="form-control summerAcademicYearNameG" name="summerAcademicYearNameG" id="summerAcademicYearNameG" placeholder="Gregorian academic year...">
                      </div>
                      <div class="col-lg-4 col-12">
                        <button class="btn btn-primary btn-block" type="submit" id="saveNewSummerAcademicYear">Save Year</button>
                      </div>
                    </div>
                    <div class="summerAcademicyearPlace"></div>
                    <div class="dropdown-divider"></div>
                    <div id="listSummerClassStatus"></div>
                  </div>
                  <div class="tab-pane fade show active" id="SummerClassStudent" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="row">
                      <div class="col-2">
                        <div class="list-group" id="list-tab" role="tablist">
                          <a class="list-group-item list-group-item-action active" id="list-view-student" data-toggle="list" href="#list-viewstu" role="tab">
                          View Student
                          </a>
                          <a class="list-group-item list-group-item-action" id="list-import-student" data-toggle="list" href="#list-importstudent" role="tab">
                          Import Student
                          </a>
                          <a class="list-group-item list-group-item-action" id="list-delete-student" data-toggle="list" href="#list-deleteSummerStudent" role="tab">
                          Delete Student
                          </a>
                        </div>
                      </div>  
                      <div class="col-10">
                        <div class="tab-content" id="nav-tabContent">
                          <!-- view student starts -->
                          <div class="tab-pane fade show active" id="list-viewstu" role="tabpanel" aria-labelledby="list-view-student">
                            <div class="row">
                              <div class="col-lg-8 col-6"> 
                                <input type="text" name="searchStudent" id="searchStudent" class="form-control typeahead" placeholder="Search Student (Name, Id , Grade . . . ) ">
                              </div> 
                              <div class="col-lg-4 col-6">
                                <form method="POST" action="<?php echo base_url(); ?>summerclass/downloadStuData/">
                                  <button type="submit" id="downloadStuData" name="downloadStuData" class="btn btn-outline-success btn-sm pull-right"> Download Student Data <i data-feather="download"></i>
                                  </button>
                                </form>
                              </div>
                            </div>
                            <form method="POST" id="comment_formSummer">
                              <div class="row">
                                <div class="col-lg-2 col-6">
                                   <div class="form-group">
                                     <select class="form-control selectric" required="required" name="academicyear" id="grands_academicyearSummer">
                                      <option></option>
                                      <?php foreach($academicyear as $academicyears){ ?>
                                        <option value="<?php echo $academicyears->year_name;?>">
                                        <?php echo $academicyears->year_name;?>
                                        </option>
                                      <?php }?>
                                     </select>
                                    </div>
                                   </div>
                                   <div class="col-lg-3 col-6">
                                    <div class="form-group">
                                     <select class="form-control"
                                     required="required" name="branch"
                                     id="grands_branchitSummer">
                                     <option>--- Branch ---</option>
                                      <?php foreach($branch as $branchs){ ?>
                                        <option value="<?php echo $branchs->name;?>">
                                        <?php echo $branchs->name;?>
                                        </option>
                                      <?php }?>
                                     </select>
                                    </div>
                                   </div>
                                   <div class="col-lg-3 col-6">
                                    <div class="form-group">
                                     <select class="form-control grands_gradesecSummer" required="required" name="gradesec" id="grands_gradesecSummer">
                                     <option>--- Grade ---</option>
                                     </select>
                                    </div>
                                   </div>
                                 <div class="col-lg-2 col-6">
                                  <button class="btn btn-primary btn-lg btn-block" 
                                  type="submit" name="viewmark">View</button>
                                </div>
                              </div>
                            </form>
                            <div class="listSummerStudentShow" id="student_view"></div>
                          </div>
                          <div class="tab-pane fade show" id="list-importstudent" role="tabpanel" aria-labelledby="list-import-student">
                            <form id="uploadSummerStudent" method="post" enctype="multipart/form-data">
                              <div class="row">
                                <div class="form-group">
                                  <div class="col-lg-4">
                                    <input type="file" required="required" name="importSummerClassStudent" id="importSummerClassStudent"/>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <button type="submit" name="insertSummerStudent" id="insertSummerStudent" class="btn btn-outline-primary"> Save student </button>
                                </div>
                                <div class="col-lg-4" id="importStudentInfo"> </div>
                              </div>
                            </form>
                          </div>
                          <div class="tab-pane fade show" id="list-deleteSummerStudent" role="tabpanel" aria-labelledby="list-delete-student">
                            <button class="btn btn-outline-warning" id="deleteSummerStudentData" type="submit">Delete All Summer Student</button>
                            <div id="deleteSummerStudentDataInfo"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="dropdown-divider"></div>
                  </div>
                   <div class="tab-pane fade show" id="SummerClassStudentPlacement" role="tabpanel" aria-labelledby="home-tab9">
                    <div class="row">
                      <div class="col-2">
                        <div class="list-group" id="list-tab" role="tablist">
                          <a class="list-group-item list-group-item-action active" id="list-view-manual-placement" data-toggle="list" href="#list-manual-placement" role="tab">
                          Manual Placement
                          </a>
                          <a class="list-group-item list-group-item-action" id="list-view-automatic-placement" data-toggle="list" href="#list-automatic-placement" role="tab">
                          Automatic Placement
                          </a>
                        </div>
                      </div>
                      <div class="col-10">
                        <div class="tab-content" id="nav-tabContent">
                          <div class="tab-pane fade show active" id="list-manual-placement" role="tabpanel" aria-labelledby="list-view-manual-placement">
                            <div class="row">
                             <div class="col-lg-12 col-12">
                              <button class="btn btn-outline-default pull-right" name="gethisreport" onclick="codespeedyManual()">
                                <span class="text-black">
                                <i data-feather="printer"></i>
                                </span>
                              </button>
                             </div>
                            </div>
                            <form method="POST" id="comment_formManual">
                              <div class="row">
                                <div class="col-lg-2 col-6">
                                  <label for="Mobile">Academic Year </label>
                                  <select class="form-control selectric" disabled="disabled" required="required" name="academicyearManual" id="academicyearManual">
                                    <option></option>
                                    <?php foreach ($academicyear as $kevalue) { ?>
                                    <option><?php echo $kevalue->year_name; ?></option>
                                    <?php  } ?>
                                  </select>
                                </div>
                                <div class="col-lg-3 col-6">
                                  <label for="Mobile">Select Branch</label>
                                  <select class="form-control selectric" required="required" name="branchManual" id="branchManual">
                                    <option></option>
                                    <?php foreach ($branch as $branchs) { ?>
                                     <option value="<?php echo $branchs->name;  ?>"><?php echo $branchs->name; ?></option>
                                    <?php  } ?>
                                  </select>
                                </div>
                                <div class="col-lg-3 col-6">
                                  <label for="Mobile">Select Grade</label>
                                  <select class="form-control selectric" required="required" name="grade2placeManual" id="grade2placeManual">
                                    <option></option>
                                  </select>
                                </div>
                                <div class="col-lg-2 col-6">
                                  <label for="Mobile">No Of Section</label>
                                  <select class="form-control selectric" required="required" name="intoManual" id="intoManual">
                                    <option></option>
                                    <?php for($i=1;$i<=20;$i++) { ?>
                                     <option value="<?php echo $i;?>">
                                      <?php echo $i; ?>
                                     </option>
                                    <?php  } ?>
                                  </select>
                                </div>
                                <div class="col-lg-2 col-12">
                                  <label for="Mobile"></label>
                                    <button type="submit" class="btn btn-primary btn-block btn-lg" name="goplace">Show</button>
                                </div>
                              </div>
                            </form>
                            <div class="listManualPlacement" id="helloManualPlacement"> </div>
                          </div>
                          <div class="tab-pane fade show" id="list-automatic-placement" role="tabpanel" aria-labelledby="list-view-automatic-placement">
                            <div class="row">
                              <div class="col-lg-12 col-12">
                                <button class="btn btn-outline-default pull-right" name="gethisreport" onclick="codespeedyAuto()">
                                  <i data-feather="printer"></i>
                                </button>
                              </div>
                            </div>
                            <a class="infofound"></a>
                            <form method="POST" id="comment_form">
                              <div class="row">
                                <div class="col-lg-3 col-6">
                                  <label for="Mobile">Select Branch</label>
                                  <div class="form-group">
                                    <select class="form-control" required="required" 
                                    name="grands_branchit" id="grands_branchit">
                                      <option>--- Branch ---</option>
                                      <?php foreach($branch as $branchs){ ?>
                                        <option value="<?php echo $branchs->name;?>">
                                        <?php echo $branchs->name;?>
                                        </option>
                                      <?php }?>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                  <label for="Mobile">Select Grade</label>
                                  <div class="form-group">
                                    <select class="form-control grade2place_auto" required="required" name="grade2place_auto" id="grade2place_auto">
                                     <option>--- Grade ---</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                  <div class="row">
                                    <div class="col-lg-10">
                                      <label for="Mobile">No. of Section</label>
                                      <div class="form-group">
                                        <select class="form-control selectric"
                                         required="required" name="into" id="into">
                                         <option></option>
                                        <?php for($i=1;$i<=20;$i++) { ?>
                                         <option value="<?php echo $i;?>">
                                          <?php echo $i; ?>
                                         </option>
                                        <?php  } ?>
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                  <label for="Mobile"></label>
                                  <button type="submit" class="btn btn-primary btn-block btn-lg" name="goplace">Place</button>
                                </div>
                              </div>
                            </form>
                            <div class="listAuto" id="helloAuto"> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="SummerStudentID" role="tabpanel" aria-labelledby="home-tab8">
                    <div class="row">
                      <div class="col-2">
                        <div class="list-group" id="list-tab" role="tablist">
                          <a class="list-group-item list-group-item-action active" id="list-view-default-card" data-toggle="list" href="#list-default-ID-card" role="tab">
                          Default ID Card
                          </a>
                          <a class="list-group-item list-group-item-action" id="list-custom-ID-card" data-toggle="list" href="#list-view-custom-Card" role="tab">
                          Custom ID Card
                          </a>
                        </div>
                      </div>
                      <div class="col-10">
                        <div class="tab-content" id="nav-tabContent">
                          <div class="tab-pane fade show active" id="list-default-ID-card" role="tabpanel" aria-labelledby="list-view-default-card">
                            <div class="card-header">
                              <div class="row">
                                <div class="col-lg-6 col-6">
                                </div>
                                <div class="col-lg-6 col-6">
                                  <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                                    <i data-feather="printer"></i>
                                  </button>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-lg-2 col-6">
                                  <div class="form-group">
                                    <select class="form-control" required="required"  name="reportacaID" id="reportacaID">
                                      <option></option>
                                      <?php foreach($academicyear as $academicyears){ ?>
                                        <option value="<?php echo $academicyears->year_name;?>">
                                        <?php echo $academicyears->year_name;?>
                                        </option>
                                      <?php }?>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                  <div class="form-group">
                                    <select class="form-control" required="required" name="branchID" id="branchID">
                                      <option> --- Branch --- </option>
                                      <?php foreach($branch as $branchs){ ?>
                                      <option value="<?php echo $branchs->name;?>">
                                        <?php echo $branchs->name;?>
                                      </option>
                                      <?php }?>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-3 col-6 table-responsive" style="height:15vh;">
                                  <div class="form-group" id="gradesecID">
                                  </div>
                                </div>
                                <div class="col-lg-4 col-6 table-responsive" style="height:15vh;">
                                  <div class="form-group" id="studentID">
                                  </div>
                                </div>
                                <div class="col-lg-6 col-6 table-responsive" style="height:15vh;">
                                  <div class="form-group" id="placeID">
                                  </div>
                                </div>
                                <div class="col-lg-6 col-6">
                                  <button class="btn btn-primary btn-block" id="generateQRCodeNow" type="submit" name="gethisroster"> View
                                  </button>
                                </div>
                              </div>
                              <div class="idStuCardList" id="helloStuIDCard"> </div>
                              <div id="qrcode" style="padding: 10px;height:auto;width:65px;"></div>
                            </div>
                          </div>
                          <div class="tab-pane fade show" id="list-view-custom-Card" role="tabpanel" aria-labelledby="list-custom-ID-card">
                            <div class="row">
                              <div class="col-lg-6 col-6">
                              </div>
                              <div class="col-lg-6 col-6">
                                <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyCustom()">
                                  <i data-feather="printer"></i>
                                </button>
                              </div>
                            </div>
                            <div class="row"> 
                              <div class="col-lg-6 col-12">
                                <div class="card-header">
                                  <input type="text" class="form-control typeahead" id="searchStudentForTransportPlace" name="searchStudentForTransportPlace" placeholder="Search Student Id,Name">
                                  <div class="table-responsive" style="height:15vh;">
                                    <div class="searchPlaceHere"></div> 
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-6 col-12">
                                <textarea class="form-control" id="selectStudentForTransportPlace" name="selectStudentForTransportPlace" col="12">  </textarea>
                                <button class="btn btn-default RemoveAll" id="removeAll" type="submit"><i class="fas fa-angle-double-left"></i></button>
                                <input type="text" id="saveNewPlacehere" class="form-control saveNewPlaceherePlace" placeholder="New service place"><button class="btn btn-default btn-block saveNewplace" type="button" id="saveNewPlace">Save Changes</button><p 
                                class="infoPageSave"></p>
                              </div> 
                              <div class="col-lg-6 col-12">
                                <div class="card-header">
                                  <button type ="submit" class="btn btn-primary btn-block" id="fetchCustomIDCard" name="fetchCustomIDCard" >View ID Card</button>
                                </div>
                              </div>
                            </div>
                            <div class="fetchCustomIDCardHere" id="helloStuIDCardCustom"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="SummerClassSubject" role="tabpanel" aria-labelledby="home-tab3">
                    <form method="POST" id="saveNewSummerSubject">
                      <div class="row">
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <label for="Mobile">Subject Name</label>
                            <input class="form-control summerSubjectName" id="summerSubjectName" required="required" type="text" placeholder="Subject name here">
                          </div>
                        </div>
                        <div class="col-lg-6 col-6 table-responsive" id="summerGrade" style="height: 20vh;">
                          <label for="Mobile"><h6>Grade</h6></label>
                          <div class="row">
                            <?php foreach($grade as $grades){ ?>
                            <div class="col-lg-6">
                              <div class="form-group">
                                <?php echo $grades->grade; ?>
                                <div class="pretty p-icon p-jelly p-round p-bigger">
                                  <input type="checkbox" name="summerSubjectGrade" value="<?php echo $grades->grade; ?>" id="customCheck1 summerSubjectGrade">
                                  <div class="state p-info">
                                    <i class="icon material-icons"></i>
                                    <label></label>
                                  </div>
                                </div>
                                #
                                <div class="pretty p-icon p-jelly p-round p-bigger">
                                  <input type="checkbox" name="summerSubjectLetter" value="#" id="customCheck1 summerSubjectLetter">
                                  <div class="state p-info">
                                    <i class="icon material-icons"></i>
                                    <label></label>
                                  </div>
                                </div>
                                A
                                <div class="pretty p-icon p-jelly p-round p-bigger">
                                  <input type="checkbox" name="summerSubjectLetter" value="A" id="customCheck1 summerSubjectLetter">
                                  <div class="state p-info">
                                    <i class="icon material-icons"></i>
                                    <label></label>
                                  </div>
                                </div>
                              </div>
                              <hr>
                            </div>
                            <?php } ?>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <div class="form-group">
                            <button type="submit" name="postSummerSubject" class="btn btn-primary btn-block"> Save subject
                            </button>
                          </div>
                        </div>
                      </div>
                    </form>
                    <div class="summerSubjectList" id="summerSubjecttshere"></div>
                  </div>
                  <div class="tab-pane fade show" id="SummerClassEvaluation" role="tabpanel" aria-labelledby="home-tab4">
                    <form id="saveSummerevaluation" method="POST">
                      <div class="row">
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <label for="evname">Evaluation Name</label>
                            <input class="form-control summerevaName" name="summerevaName" type="text" placeholder="Evaluation name (Test,Final)...">
                          </div>
                         </div>
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <label for="Mobile">Percentage
                            </label>
                            <input class="form-control summerevaPercent" name="summerevaPercent" type="text" placeholder="Value here(In Number)...">
                          </div>
                        </div>
                        <div class="col-lg-4 col-12 table-responsive" style="height: 100px;">
                          <div class="form-group">
                            <label for="Mobile">Select grade</label><br>
                            <div class="row">
                            <?php foreach($grade as $grades){ ?>
                              <div class="col-lg-6">
                              <div class="pretty p-icon p-jelly p-round p-bigger">
                               <input id="summerevaGrade" type="checkbox" name="summerevaGrade" value="<?php echo $grades->grade; ?>">
                               <div class="state p-info">
                                  <i class="icon material-icons"></i>
                                  <label></label>
                               </div>
                               </div>
                                <?php echo $grades->grade; ?>
                                <div class="dropdown-divider2"></div>
                              </div>
                            <?php } ?>
                          </div>
                          </div>
                        </div>
                        <div class="col-lg-2 col-12 pull-right">
                          <button type="submit" name="postSummerEvaluation" class="btn btn-primary btn-block">Save Evaluation
                          </button>
                            <a href="#" class="saveSummerInfo"></a>
                        </div>
                      </div>
                    </form>
                    <div  id="summerEvaluationData"></div>
                  </div>
                  <div class="tab-pane fade show" id="SummerPlacement" role="tabpanel" aria-labelledby="home-tab5">
                    <form id="saveSummerPlacement" method="POST">
                      <div class="row">
                        <div class="form-group col-lg-2 col-6">
                          <div class="form-group">
                            <label for="Mobile">Academic Year</label>
                            <select class="form-control selectric" required="required" name="summerAcademicyear" id="summerAcademicyear">
                              <option></option>
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option><?php echo $academicyears->year_name ?></option>
                            <?php } ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                         <div class="form-group">
                            <label for="Staff">
                            Select staff to assign </label>
                           <select class="form-control selectric" required="required" name="summerStaff" id="summerStaff">
                           <option></option>
                            <?php foreach($staffs as $staff) { ?>
                            <option value="<?php echo $staff->username;?>"><?php echo $staff->username;echo '(';
                            echo $staff->fname.' '.$staff->mname;echo ')';
                            ?></option>
                          <?php }?>
                           </select>
                         </div>
                        </div>
                        <div class="col-lg-3 col-12 table-responsive" style="height: 20vh;">
                          <div class="form-group">
                            <label for="Grade"> Select grade to assign</label><br>
                            <div class="row">
                              <?php foreach($gradesec as $gradesecs){ ?>
                              <div class="col-lg-6 col-6">
                                <div class="pretty p-icon p-jelly p-round p-bigger">
                                  <input type="checkbox" name="summerGradePlacement" value="<?php echo $gradesecs->gradesec;?>" class="summerGradePlacement" id="customCheck1">
                                  <div class="state p-info">
                                    <i class="icon material-icons"></i>
                                    <label></label>
                                  </div>
                                </div>
                                <?php echo $gradesecs->gradesec; ?>
                                <div class="dropdown-divider2"></div>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-3 col-12 table-responsive" style="height: 20vh;">
                          <div class="form-group">
                            <label for="subject">Select subject to assign </label><br>
                            <?php foreach($subjects as $subject){ ?>
                              <div class="pretty p-icon p-jelly p-round p-bigger">
                                  <input type="checkbox" name="summerSubject" value="<?php echo $subject->Subj_name;?>" class="summerSubject" id="customCheck1">
                                  <div class="state p-info">
                                    <i class="icon material-icons"></i>
                                    <label></label>
                                  </div>
                              </div><?php echo $subject->Subj_name ;?>
                              <div class="dropdown-divider2"></div>
                            <?php } ?>
                          </div>
                        </div>
                        <div class="col-lg-12 col-12 pull-right">
                          <div class="form-group">
                            <button type="submit" name="postSummerPlacement" class="btn btn-primary btn-block ">Save Placement </button>
                          </div>
                        </div>
                      </div>
                    </form>
                    <div class="fetchSummerPacement"></div>
                  </div>
                  <div class="tab-pane fade show" id="SummerAttendance" role="tabpanel" aria-labelledby="home-tab8">
                    <div class="row">
                      <div class="col-2">
                        <div class="list-group" id="list-tab" role="tablist">
                          <a class="list-group-item list-group-item-action active" id="list-report-attendance" data-toggle="list" href="#list-attendanceReport" role="tab">
                           Attendance Report
                          </a>
                          <a class="list-group-item list-group-item-action" id="list-feed-attendance" data-toggle="list" href="#list-feedAttendance" role="tab">
                          Feed Attendance
                          </a>
                        </div>
                      </div>  
                      <div class="col-10">
                        <div class="tab-content" id="nav-tabContent">
                          <div class="tab-pane fade show active" id="list-attendanceReport" role="tabpanel" aria-labelledby="list-report-attendance">
                             <div class="row">
                                <div class="col-lg-6 col-6">
                                </div>
                                <div class="col-lg-6 col-6">
                                  <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyAttendanceReport()">
                                    <i data-feather="printer"></i>
                                  </button>
                                </div>
                              </div>
                            <div class="fetch_attendance_summer" id="printAttendanceReport"></div>
                          </div>
                          <div class="tab-pane fade show" id="list-feedAttendance" role="tabpanel" aria-labelledby="list-feed-attendance">
                            <form method="POST" id="comment_formAttendance">
                              <div class="row">
                                   <div class="col-lg-4 col-6">
                                    <div class="form-group">
                                     <select class="form-control" required="required" name="branch" id="grands_branchitAttendance">
                                     <option>--- Branch ---</option>
                                      <?php foreach($branch as $branchs){ ?>
                                        <option value="<?php echo $branchs->name;?>">
                                        <?php echo $branchs->name;?>
                                        </option>
                                      <?php }?>
                                     </select>
                                    </div>
                                   </div>
                                   <div class="col-lg-4 col-6">
                                    <div class="form-group">
                                     <select class="form-control grands_gradesecAttendance" required="required" name="gradesec" id="grands_gradesecAttendance">
                                     <option>--- Grade ---</option>
                                     </select>
                                    </div>
                                   </div>
                                 <div class="col-lg-4 col-12">
                                  <button class="btn btn-primary btn-lg btn-block" 
                                  type="submit" name="viewmark">View</button>
                                </div>
                              </div>
                            </form>
                            <div class="studentListSummer"> </div>
                          </div>
                          <!-- <div class="tab-pane fade show" id="list-deleteMark" role="tabpanel" aria-labelledby="list-delete-mark">
                            <form method="GET" id="deleteSummerMarkForm">
                              <div class="row">
                                <div class="col-lg-3 col-6">
                                  <div class="form-group">
                                   <select class="form-control selectric" required="required" name="deleteSummerMarkBranch" id="deleteSummerMarkBranch">
                                   <option>--- Branch ---</option>
                                    <?php foreach($branch as $branchs){ ?>
                                      <option value="<?php echo $branchs->name;?>">
                                      <?php echo $branchs->name;?>
                                      </option>
                                    <?php }?>
                                   </select>
                                  </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                  <div class="form-group">
                                     <select class="form-control selectric deleteSummerMarkGradesec" required="required" name="deleteSummerMarkGradesec" id="deleteSummerMarkGradesec">
                                     <option>--- Grade ---</option>
                                     </select>
                                  </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                  <div class="form-group">
                                    <select class="form-control deleteSummerMarkSubject" name="deleteSummerMarkSubject" required="required">
                                      <option>--- Select Subject ---</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-2 col-6">
                                  <button class="btn btn-primary btn-block" 
                                  type="submit" name="viewmark">View</button>
                                </div>
                              </div>
                            </form>
                            <div class="deleteSummerMark" id="deleteSummerMark"></div>
                          </div> -->
                          <div class="tab-pane fade show" id="list-lockMark" role="tabpanel" aria-labelledby="list-lock-mark">
                            <div class="row">
                              <div class="col-lg-12 col-12">
                                <button class="btn btn-primary" type="submit" id="lockThisSummerSubjectMark"> Lock Mark</button>
                                <button class="btn btn-primary" type="submit" id="unlockThisSummerSubjectMark"> Unock Mark</button>
                              </div>
                            </div>
                            <div class="fetchLockedMark"></div>
                          </div>
                          <!-- <div class="tab-pane fade show" id="list-exportMark" role="tabpanel" aria-labelledby="list-export-mark">                            
                            <form method="POST" action="<?php echo base_url(); ?>summerclass/exportSummerMarkFormat/">
                              <div class="row">
                                <div class="col-lg-4 col-6">
                                  <div class="form-group">
                                    <select class="form-control selectric" required="required" name="SummerBranchFormat"  id="SummerBranchFormat">
                                     <option>--- Select Branch ---</option>
                                      <?php foreach($branch as $branchs){ ?>
                                        <option value="<?php echo $branchs->name;?>">
                                        <?php echo $branchs->name;?>
                                        </option>
                                      <?php }?>
                                     </select>
                                  </div>
                                </div>
                                <div class="col-lg-4 col-6">
                                  <div class="form-group">
                                    <select class="form-control" required="required" name="summerGradesecformat"  id="summerGradesecformat">
                                      <option>--- Select Grade ---</option>
                                    
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                  <button class="btn btn-primary btn-block" type="submit" name="gethisgradeSummerFormate">Get</button>
                                </div>
                              </div>
                            </form>
                          </div> -->
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="SummerClassMark" role="tabpanel" aria-labelledby="home-tab6">
                    <div class="row">
                      <div class="col-2">
                        <div class="list-group" id="list-tab" role="tablist">
                          <a class="list-group-item list-group-item-action active" id="list-view-mark" data-toggle="list" href="#list-viewMark" role="tab">
                          View Mark
                          </a>
                          <a class="list-group-item list-group-item-action" id="list-add-mark" data-toggle="list" href="#list-addMark" role="tab">
                          Add Mark
                          </a>
                          <a class="list-group-item list-group-item-action" id="list-delete-mark" data-toggle="list" href="#list-deleteSummerMark" role="tab">
                          Edit Mark
                          </a>
                          <a class="list-group-item list-group-item-action" id="list-ng-mark" data-toggle="list" href="#list-NGSummerMark" role="tab">
                          NG Mark
                          </a>
                          <a class="list-group-item list-group-item-action" id="list-lock-mark" data-toggle="list" href="#list-lockSummerMark" role="tab">
                          Lock Mark
                          </a>
                          <a class="list-group-item list-group-item-action" id="list-export-mark" data-toggle="list" href="#list-exportSummerMark" role="tab">
                          Export Format
                          </a>
                        </div>
                      </div>  
                      <div class="col-10">
                        <div class="tab-content" id="nav-tabContent">
                          <div class="tab-pane fade show active" id="list-viewMark" role="tabpanel" aria-labelledby="list-view-mark">
                             <div class="row">
                                <div class="col-lg-6 col-6">
                                </div>
                                <div class="col-lg-6 col-6">
                                  <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedyViewMark()">
                                    <i data-feather="printer"></i>
                                  </button>
                                </div>
                              </div>
                            <form method="POST" id="summerMarkViewForm">
                              <div class="row">
                                  <div class="col-lg-3 col-6">
                                    <div class="form-group">
                                      <select class="form-control" required="required" name="SummerMarkBranch" id="SummerMarkBranch">
                                        <option>--- Branch ---</option>
                                          <?php foreach($branch as $branchs){ ?>
                                          <option value="<?php echo $branchs->name;?>">
                                            <?php echo $branchs->name;?>
                                          </option>
                                          <?php }?>
                                      </select>
                                    </div>
                                  </div>
                                <div class="col-lg-3 col-6">
                                  <div class="form-group">
                                    <select class="form-control summerMarkGradesec" required="required" name="summerMarkGradesec" id="summerMarkGradesec">
                                      <option>--- Grade ---</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-4 col-6">
                                  <div class="form-group">
                                    <select class="form-control summerMarkSubject" name="summerMarkSubject" required="required">
                                      <option>--- Select Subject ---</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-2 col-6">
                                  <button class="btn btn-primary btn-block" type="submit" name="viewSummerMark">View
                                  </button>
                                </div>
                              </div>
                            </form>
                            <div class="viewSummerMark" id="viewSummerMark"></div>
                          </div>
                          <div class="tab-pane fade show" id="list-addMark" role="tabpanel" aria-labelledby="list-add-mark">
                            <form id="uploadSummerMark" method="post" enctype="multipart/form-data">
                              <div class="row">
                                <div class="form-group">
                                  <div class="col-lg-4">
                                    <div id="image-preview" class="image-preview">
                                      <label for="uploadSummerStudentMark" id="image-label">Choose File
                                        <i data-feather="paperclip"></i>
                                      </label>
                                      <input type="file" required="required" name="uploadSummerMark" id="uploadSummerStudentMark"/>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <button type="submit" name="insertSummerMark" id="insertSummerMark" class="btn btn-primary"> Save mark </button>
                                </div>
                                <div class="col-lg-4" id="uploadMarkInfo"> </div>
                              </div>
                            </form>
                          </div>
                          <div class="tab-pane fade show" id="list-deleteSummerMark" role="tabpanel" aria-labelledby="list-delete-mark">
                            <form method="GET" id="deleteSummerMarkForm">
                              <div class="row">
                                <div class="col-lg-3 col-6">
                                  <div class="form-group">
                                   <select class="form-control selectric" required="required" name="deleteSummerMarkBranch" id="deleteSummerMarkBranch">
                                   <option>--- Branch ---</option>
                                    <?php foreach($branch as $branchs){ ?>
                                      <option value="<?php echo $branchs->name;?>">
                                      <?php echo $branchs->name;?>
                                      </option>
                                    <?php }?>
                                   </select>
                                  </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                  <div class="form-group">
                                     <select class="form-control selectric deleteSummerMarkGradesec" required="required" name="deleteSummerMarkGradesec" id="deleteSummerMarkGradesec">
                                     <option>--- Grade ---</option>
                                     </select>
                                  </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                  <div class="form-group">
                                    <select class="form-control deleteSummerMarkSubject" name="deleteSummerMarkSubject" required="required">
                                      <option>--- Select Subject ---</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-2 col-6">
                                  <button class="btn btn-primary btn-block" 
                                  type="submit" name="viewmark">View</button>
                                </div>
                              </div>
                            </form>
                            <div class="deleteSummerMark" id="deleteSummerMark"></div>
                          </div>
                          <div class="tab-pane fade show" id="list-NGSummerMark" role="tabpanel" aria-labelledby="list-ng-mark">
                            <form method="GET" id="NGSummerMarkForm">
                              <div class="row">
                                <div class="col-lg-3 col-6">
                                  <div class="form-group">
                                   <select class="form-control selectric" required="required" name="deleteSummerMarkBranchNG" id="deleteSummerMarkBranchNG">
                                   <option>--- Branch ---</option>
                                    <?php foreach($branch as $branchs){ ?>
                                      <option value="<?php echo $branchs->name;?>">
                                      <?php echo $branchs->name;?>
                                      </option>
                                    <?php }?>
                                   </select>
                                  </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                  <div class="form-group">
                                     <select class="form-control selectric deleteSummerMarkGradesecNG" required="required" name="deleteSummerMarkGradesecNG" id="deleteSummerMarkGradesecNG">
                                     <option>--- Grade ---</option>
                                     </select>
                                  </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                  <div class="form-group">
                                    <select class="form-control deleteSummerMarkSubjectNG" name="deleteSummerMarkSubjectNG" required="required">
                                      <option>--- Select Subject ---</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-2 col-6">
                                  <button class="btn btn-primary btn-block" 
                                  type="submit" name="viewmark">View NG</button>
                                </div>
                              </div>
                            </form>
                            <div class="deleteSummerMarkNG" id="deleteSummerMarkNG"></div>
                          </div>
                          <div class="tab-pane fade show" id="list-lockSummerMark" role="tabpanel" aria-labelledby="list-lock-mark">
                            <div class="row">
                              <div class="col-lg-12 col-12">
                                <button class="btn btn-primary" type="submit" id="lockThisSummerSubjectMark"> Lock Mark</button>
                                <button class="btn btn-primary" type="submit" id="unlockThisSummerSubjectMark"> Unock Mark</button>
                              </div>
                            </div>
                            <div class="fetchLockedMark"></div>
                          </div>
                          <div class="tab-pane fade show" id="list-exportSummerMark" role="tabpanel" aria-labelledby="list-export-mark">                            
                            <form method="POST" action="<?php echo base_url(); ?>summerclass/exportSummerMarkFormat/">
                              <div class="row">
                                <div class="col-lg-4 col-6">
                                  <div class="form-group">
                                    <select class="form-control selectric" required="required" name="SummerBranchFormat"  id="SummerBranchFormat">
                                     <option>--- Select Branch ---</option>
                                      <?php foreach($branch as $branchs){ ?>
                                        <option value="<?php echo $branchs->name;?>">
                                        <?php echo $branchs->name;?>
                                        </option>
                                      <?php }?>
                                     </select>
                                  </div>
                                </div>
                                <div class="col-lg-4 col-6">
                                  <div class="form-group">
                                    <select class="form-control" required="required" name="summerGradesecformat"  id="summerGradesecformat">
                                      <option>--- Select Grade ---</option>
                                    
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                  <button class="btn btn-primary btn-block" type="submit" name="gethisgradeSummerFormate">Get Format</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade show" id="summerReportCard" role="tabpanel" aria-labelledby="home-tab7">
                    <form method="POST" id="reportCardformSummer">
                      <div class="row">
                        <div class="col-lg-2 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="summerRCAC" id="summerRCAC">
                              <option></option>
                              <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                                <?php echo $academicyears->year_name;?>
                              </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <select class="form-control" required="required" name="summerRCBR" id="summerRCBR">
                              <option>--- Branch ---</option>
                              <?php foreach($branch as $branchs){ ?>
                                <option value="<?php echo $branchs->name;?>">
                                <?php echo $branchs->name;?>
                                </option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <div class="form-group">
                            <select class="form-control summerRCGR" required="required" name="gradesec" id="summerRCGR">
                              <option>--- Grade ---</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2 col-6">
                          <button class="btn btn-primary btn-block btn-lg" type="submit" name="viewreport">View</button>
                        </div>
                        <div class="col-lg-2">
                          <button class="btn btn-default pull-right" name="gethisSummerreport" onclick="codespeedyReportcard()">
                          <span class="text-black">
                            <i data-feather="printer"></i>
                          </span>
                          </button>
                        </div>
                      </div>
                    </form>
                    <div class="listSummerReportCard" id="reportCardView"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <form method="POST" id="comment_form_updateSummer">
        <div class="modal fade" id="editmarkSummer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Mark Value</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="editmarkhere_gsSummer">
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="updatesubject" class="btn btn-primary savegrandsubject">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <form method="POST" id="comment_form_NGupdate">
        <div class="modal fade" id="editngmarkSummer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit NG Mark</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="editngmarkhere_gs">
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="updatesubject" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <form method="POST" id="form_editOutofSummer">
        <div class="modal fade" id="editOutOfSummer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Mark Percentage</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="editoutof_gs">
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="updateoutof" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </form>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  $('#form_editOutofSummer').on('submit', function(event) {
    event.preventDefault();
    var oldOutOf=$('.oldOutOf').val();
    var updateOutOf=$('.updateOutOf').val();
    var markname=$('.outofmarkname').val();
    var gradesec=$('.markgradesec').val();
    var subject=$('.marksubject').val();
    var branch=$('.markbranch').val();
    var year=$('.markyear').val();
    $.ajax({
      url: "<?php echo base_url(); ?>Summerclass/updateOutOf/",
      method: "POST",
      data: ({
        oldOutOf:oldOutOf,
        updateOutOf:updateOutOf,
        markname: markname,
        gradesec:gradesec,
        subject:subject,
        branch:branch,
        year:year
      }),
      beforeSend: function() {
        $('.coreOutOFSummer'+oldOutOf+markname).html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">' );
      },
      success: function(data) {
         $(".changeOutInfo").html(data);
        $(".coreOutOFSummer"+oldOutOf+markname).html(data);
      }
    })
  });
</script>
<script>
  $(document).on('click', '.gs_edit_outofSummer', function() {
      var subject=$(".jo_subjectSummer").val();
      var gradesec=$(".jo_gradesecSummer").val();
      var branch=$(".jo_branchSummer").val();
      var year=$(".jo_yearSummer").val();
      var markname=$(this).attr("value");
      var outof=$(this).attr("id");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Summerclass/editOutOf/",
        data: ({
          subject: subject,
          gradesec:gradesec,
          branch:branch,
          year:year,
          markname:markname,
          outof: outof
        }),
        cache: false,
        beforeSend: function() {
          $('#editoutof_gs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data){
          $('#editoutof_gs').html(data);
        }
      }); 
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Summerclass/fetchAttendanceReport/",
        method:"POST",
        beforeSend: function() {
          $('.fetch_attendance_summer').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success:function(data){
          $('.fetch_attendance_summer').html(data);
        }
      })
    }
    $(document).on('change', '#attendanceDateSummer', function() {
      id=[];
      $("input[name='attendanceStuidSummer']:disabled").each(function(i){
        id[i]= $(this).removeAttr("disabled","disabled");
      });
    }); 
    $(document).on('change', '#attendanceStuidSummer', function() {
      $("#attendanceTypeSummer").removeAttr("disabled","disabled");
    }); 
    $(document).on('change', '#attendanceTypeSummer', function() {
      var abtype=$(this).val();
      if(abtype==='Late'){
        $("#attendanceMinuteSummer").removeAttr("disabled","disabled");
      }else{
        $("#attendanceMinuteSummer").attr("disabled","disabled");
      }
    });
    $(document).on('click', '#saveAttendanceSummer', function() {
      var attendanceDate =$('#attendanceDateSummer').val();
      var attendanceType=$('#attendanceTypeSummer').val();
      var attendanceMinute=$('#attendanceMinuteSummer').val();
      stuid=[];
      $("input[name='attendanceStuidSummer']:checked").each(function(i){
        stuid[i]=$(this).val();
      });
      if($('#attendanceDateSummer').val()!='' && $('#attendanceTypeSummer').val()!='' && stuid.length!=0 ){
        if($('#attendanceTypeSummer').val()==='Late'){
          if($('#attendanceMinuteSummer').val()!==''){
            $.ajax({
              method: "POST",
              url: "<?php echo base_url(); ?>Summerclass/saveAttendance/",
              data: ({
                stuid: stuid,
                attendanceDate:attendanceDate,
                attendanceType:attendanceType,
                attendanceMinute:attendanceMinute
              }),
              cache: false,
              success: function(html){
                load_data();
                iziToast.success({
                  title: 'Attendance',
                  message: 'Updated successfully',
                  position: 'bottomCenter'
                });
              }
            });
          }else{
            alert('Please insert minute to late attendance.');
          }
        }else{
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Summerclass/saveAttendance/",
            data: ({
              stuid: stuid,
              attendanceDate:attendanceDate,
              attendanceType:attendanceType,
              attendanceMinute:attendanceMinute
            }),
            cache: false,
            success: function(html){
              load_data();
              iziToast.success({
                title: 'Attendance',
                message: 'Updated successfully',
                position: 'bottomCenter'
              });
            }
          });
        }
      }else{
        alert('Please select necessary fields.')
      }
    }); 
  });
  $(document).on('click', '.deleteThisAttendaneSummer', function() {
    var attendanceId = $(this).attr("id");
     swal({
        title: 'Are you sure you want to delete this Attendance ?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
    .then((willDelete) => {
      if (willDelete) {
        swal('Attendance deleted successfully!', {
          icon: 'success',
        });
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Summerclass/deleteAttendanceSummer/",
          data: ({
            attendanceId: attendanceId
          }),
          cache: false,
          success: function(html) {
            $(".deleteSummerAttendane" + attendanceId).fadeOut('slow');
          }
        });
      }else {
        return false;
      }
    });
  });
</script>
<script type="text/javascript">
  $('#comment_formAttendance').on('submit', function(event) {
    event.preventDefault();
    var attBranches=$('#grands_branchitAttendance').val();
    var attGradesec=$('.grands_gradesecAttendance').val();
    if ($('.grands_gradesecAttendance').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Summerclass/fetchStudents4Attendance/",
        method: "POST",
        data: ({
          attBranches: attBranches,
          attGradesec:attGradesec
        }),
        beforeSend: function() {
          $('.studentListSummer').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(data) {
          $(".studentListSummer").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_branchitAttendance").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filterGradeFromBranch/",
        data: "branchit=" + $("#grands_branchitAttendance").val(),
        beforeSend: function() {
          $('#grands_gradesecAttendance').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_gradesecAttendance").html(data);
        }
      });
    });
  });
  $(document).ready(function() { 
    $('#searchStudent').on("keyup",function() {
      $searchItem=$('#searchStudent').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/searchStudent/",
        data: "searchItem=" + $("#searchStudent").val(),
        beforeSend: function() {
          $('.listSummerStudentShow').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(data) {
          $(".listSummerStudentShow").html(data);
        }
      });
    });
  });
  $(document).ready(function(){
    load_data_summer_check();
    function load_data_summer_check()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Summerclass/fetchSummerClassStatus/",
        method:"POST",
        beforeSend: function() {
          $('#listSummerClassStatus').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">');
        },
        success:function(data){
          $('#listSummerClassStatus').html(data);
        }
      })
    }
    load_year();
    function load_year()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Summerclass/fetch_summer_academicyear/",
        method:"POST",
        beforeSend: function() {
          $('.summerAcademicyearPlace').html( 'Loading placement...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success:function(data){
          $('.summerAcademicyearPlace').html(data);
        }
      })
    }
    $('#saveNewSummerAcademicYear').on('click', function(event) {
      event.preventDefault();
      var academicyearName=$('#summerAcademicYearName').val();
      var academicyearNameG=$('#summerAcademicYearNameG').val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Summerclass/postSummerAcademicYear/",
        data: ({
          academicyearName:academicyearName,
          academicyearNameG:academicyearNameG
        }),
        cache: false,
        success: function(html){
          $('#summerAcademicYearName').val('');
          $('#summerAcademicYearNameG').val('');
          load_year();
          load_data_summer_check();
        }
      });
    });
  $(document).on('click', '.deleteSummerAcademicYearNow', function()
  {
    var yearName=$(this).attr("value");
    swal({
        title: 'Are you sure you want to delete this year?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Summerclass/deleteSummerAcademicYear/",
          data: ({
            yearName: yearName
          }),
          cache: false,
          beforeSend: function() {
            $('.deleteSummerAcademicYear' + yearName).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="10" height="10" id="loa">'
            );
          },
          success: function(html){
           load_year();
           load_data_summer_check();
          }
        });
      }
    }); 
  }); 
});
</script>

<script type="text/javascript">
   $(document).on('click', '.saveNewplace', function() {
      event.preventDefault();
      var newServicePlaceName=$('.saveNewPlaceherePlace').val();
      var newServicePlace=$('#selectStudentForTransportPlace').val();
      var stuIdArray=newServicePlace.split(/(\s+)/);
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/save_new_summer_serviceplace/",
        data: ({
          stuIdArray: stuIdArray,
          newServicePlaceName:newServicePlaceName
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
    $("#grands_branchit").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/Filter_thisgrade_from_branch/",
        data: "branchit=" + $("#grands_branchit").val(),
        beforeSend: function() {
          $('#grade2place_auto').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grade2place_auto").html(data);
        }
      });
    });
  });
  $('#comment_form').on('submit', function(event) {
    event.preventDefault();
    var grade2place=$('#grade2place_auto').val();
    var branch2place=$('#grands_branchit').val();
    var into=$('#into').val();
    swal({
      title:'Are you sure you want to place automatically for grade ' +grade2place+' ?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        if ($('#grade2place_auto').val() != '') {
          var form_data = $(this).serialize();
          $.ajax({
            url: "<?php echo base_url(); ?>Summerclass/filterGrade4AutoPlacement/",
            method: "POST",
            data: form_data,
            beforeSend: function() {
              $('.listAuto').html( 'Placing...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
            },
            success: function(data) {
              /*$("#comment_form")[0].reset();*/
              $(".listAuto").html(data);
            }
          })
        }else {
          swal('Oooops, Please select necessary fields!', {
            icon: 'warning',
          });
        }
      }
    });
  });
  $('#grade2place_auto').on('change', function(event) {
    var grade2place=$('#grade2place_auto').val();
    var branch2place=$('#grands_branchit').val();
    $.ajax({
      url: "<?php echo base_url(); ?>Summerclass/checkPlacementFound/",
      method: "POST",
      data: ({
        grade2place: grade2place,
        branch2place:branch2place
      }),
      beforeSend: function() {
        $('.infofound').html( 'Checking placement...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
        success: function(data) {
        $(".infofound").html(data);
      }
    })
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#lockThisSummerSubjectMark', function() {
      event.preventDefault();
      /*var branch=$('#lockSummerMarkBranch').val();
      var gradesec=$('#lockSummerMarkGradesec').val();
      var subject=$('#lockSummerMarkSubject').val();*/
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/lockThisSummerMark/",
        beforeSend: function() {
          $('.fetchLockedMark').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".fetchLockedMark").html(data);
        }
      });
    });
  $(document).on('click', '#unlockThisSummerSubjectMark', function() {
      event.preventDefault();
      /*var branch=$('#lockSummerMarkBranch').val();
      var gradesec=$('#lockSummerMarkGradesec').val();
      var subject=$('#lockSummerMarkSubject').val();*/
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/unlockThisSummerMark/",
        beforeSend: function() {
          $('.fetchLockedMark').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".fetchLockedMark").html(data);
        }
      });
    });
    $(document).on('click', '#fetchCustomIDCard', function() {
      event.preventDefault();
      var newServicePlace=$('#selectStudentForTransportPlace').val();
      var stuIdArray=newServicePlace.split(/(\s+)/);
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/fetchCustomIDCard/",
        data: ({
          stuIdArray: stuIdArray
        }),
        beforeSend: function() {
          $('.fetchCustomIDCardHere').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".fetchCustomIDCardHere").html(data);
        }
      });
    });
  </script>
<script type="text/javascript">
    $(document).ready(function() { 
      $('#searchStudentForTransportPlace').on("keyup",function() {
        $searchItem=$('#searchStudentForTransportPlace').val();
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>Summerclass/searchSummerStudentsToTransportService/",
          data: "searchItem=" + $("#searchStudentForTransportPlace").val(),
          beforeSend: function() {
            $('.searchPlaceHere').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
          },
          success: function(data) {
            $(".searchPlaceHere").html(data);
          }
        });
      });
    });
    $(document).on('click', '.saveThisSummerStudentToGroupEdit', function() {
      event.preventDefault();
      var oldText=$('#selectStudentForTransportPlace').val();
      var stuID=$(this).attr("value");
      var newText=oldText+stuID+"\n";
      $("#selectStudentForTransportPlace").val(newText);   
    });
    $(document).on('click', '#removeAll', function() {
      event.preventDefault();
      $("#selectStudentForTransportPlace").val('');   
    });
  </script>
  <script type="text/javascript">
  function codespeedyAuto(){
    var print_div = document.getElementById("helloAuto");
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
  function codespeedyCustom(){
    var print_div = document.getElementById("helloStuIDCardCustom");
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
  $(document).on('click', '.placesiecsummerstudent', function() {
    var stu_id=$(this).attr("id");
    var section_id=$(this).attr("value");
    var grade=$('.grades_summer').val();
    $.ajax({
      url: "<?php echo base_url(); ?>Summerclass/insertsection/",
      method: "POST",
      data: ({
        stu_id: stu_id,
        section_id: section_id,
        grade: grade
      }),
      beforeSend: function() {
        $('.saved').html( '<img src="<?php echo base_url() ?>loader/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      dataType:"json",
      success: function(data) {
        $('.saved' + stu_id + section_id).html(data.notification);
      }
    });
  });
  $(document).ready(function() {  
    $("#branchManual").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/Filter_thisgrade_from_branch_Summer/",
        data: "branchit=" + $("#branchManual").val(),
        beforeSend: function() {
          $('#grade2placeManual').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grade2placeManual").html(data);
        }
      });
    });
  });
  $('#comment_formManual').on('submit', function(event) {
    event.preventDefault();
    var grade2place=$('#grade2placeManual').val();
    var into=$('#intoManual').val();
    var branch=$('#branchManual').val();
    if ($('#grade2place').val() != '') {
      var form_data = $(this).serialize();
      $.ajax({
        url: "<?php echo base_url(); ?>Summerclass/filter_grade4placement/",
        method: "POST",
        data: form_data,
        beforeSend: function() {
          $('.listManualPlacement').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          /*$('#comment_form')[0].reset();*/
          $(".listManualPlacement").html(data);
        }
      })
    }else {
      swal('Oooops, Please select necessary fields!', {
        icon: 'warning',
      });
    }
  });
</script>
<script type="text/javascript">
  function codespeedyManual(){
    var print_div = document.getElementById("helloManualPlacement");
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
  $('#deleteSummerStudentData').on('click', function(event) {
    event.preventDefault();
    var gradesec=$('#summerRCGR').val();
    var branch=$('#summerRCBR').val();
    var reportaca=$('#summerRCAC').val();
    swal({
        title: 'Are you sure you want to delete this summer student data?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
        $.ajax({
          url: "<?php echo base_url(); ?>Summerclass/deleteSummerStudentData/",
          method: "POST",
          beforeSend: function() {
            $('#deleteSummerStudentDataInfo').html('Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="54" height="54" id="loa">');
          },
          success: function(data) {
            $("#deleteSummerStudentDataInfo").html(data);
          }
        })
      }else {
        return false;
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#branchID").bind("change", function() {
      var branchit=$('#branchID').val();
      var grands_academicyear=$('#reportacaID').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/Filter_grade_from_branch/",
        data: ({
            branchit: branchit,
            grands_academicyear:grands_academicyear
        }),
        beforeSend: function() {
          $('#gradesecID').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#gradesecID").html(data);
        }
      });
    });
    $(document).on('click', '.summer_studentServiceGrade', function() {
        grade=[];
        $("input[name='summer_studentServiceGrade']:checked").each(function(i){
          grade[i]=$(this).val();
        });
        var academicyear=$("#summer_studentListPLace").val();
        if($(".summer_studentServiceGrade").val()!=''){
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>Summerclass/fetchThisGradeStudentIdcard/",
           data: ({
            grade: grade,
            academicyear:academicyear
          }),
          beforeSend: function() {
            $('#studentID').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
              );
          },
          success: function(data) {
            $("#studentID").html(data);
          }
        });
      }
    });
  });
  $("#branchID").bind("change", function() {
      var branchit=$('#branchID').val();
      var grands_academicyear=$('#reportacaID').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filtersummer_ServicePlace/",
        data: ({
            branchit: branchit,
            grands_academicyear:grands_academicyear
        }),
        beforeSend: function() {
          $('#placeID').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#placeID").html(data);
        }
      });
    });
  $(document).on('click', '#generateQRCodeNow', function() {
      servicePlace=[];
      $("input[name='summer_studentServicePlace[ ]']:checked").each(function(i){
        servicePlace[i]=$(this).val();
      });
      studentList=[];
      $("input[name='summer_studentListTransportService[ ]']:checked").each(function(i){
        studentList[i]=$(this).val();
      });
      var gradesec=$("#gradesecID").val();
      var branch=$("#branchID").val();
      var reportacaID=$("#reportacaID").val();
      if(servicePlace.length!=0 && studentList.length!=0){
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/fetchsummer_StudentIdcard/",
         data: ({
          servicePlace: servicePlace,
          studentList:studentList,
          gradesec:gradesec,
          branch:branch,
          reportacaID:reportacaID
        }),
        beforeSend: function() {
          $('.idStuCardList').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".idStuCardList").html(data);
        }
      });
    }else{
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/fetchsummer_StudentIdcardWithoutPlace/",
         data: ({
          studentList:studentList,
          gradesec:gradesec,
          branch:branch,
          reportacaID:reportacaID
        }),
        beforeSend: function() {
          $('.idStuCardList').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">'
            );
        },
        success: function(data) {
          $(".idStuCardList").html(data);
        }
      });
    }
  });
  </script>
  <script type="text/javascript">
    function selectAllsummer_Student(){
        var itemsall=document.getElementById('summer_selectallStudentList');
        if(itemsall.checked==true){
        var items=document.getElementsByName('summer_studentListTransportService[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=true;
        }
      }
        else{
        var items=document.getElementsByName('summer_studentListTransportService[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=false;
        }
      }
    }
    function selectAllSummerPlaceList(){
        var itemsall=document.getElementById('selectallSummerServicePlaceList');
        if(itemsall.checked==true){
        var items=document.getElementsByName('summer_studentServicePlace[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=true;
        }
      }
        else{
        var items=document.getElementsByName('summer_studentServicePlace[ ]');
        for(var i=0;i < items.length;i++){
          items[i].checked=false;
        }
      }
    }
</script>
<script type="text/javascript">
  function codespeedy(){
    var print_div = document.getElementById("helloStuIDCard");
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
  function codespeedyReportcard(){
    var print_div = document.getElementById("reportCardView");
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
  function codespeedyAttendanceReport(){
    var print_div = document.getElementById("printAttendanceReport");
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
  function codespeedyViewMark(){
    var print_div = document.getElementById("viewSummerMark");
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
  $('#reportCardformSummer').on('submit', function(event) {
    event.preventDefault();
    var gradesec=$('#summerRCGR').val();
    var branch=$('#summerRCBR').val();
    var reportaca=$('#summerRCAC').val();
      if ($('#summerRCGR').val() != '' && $('#summerRCBR').val() != '' ) {
        $.ajax({
          url: "<?php echo base_url(); ?>Summerclass/fetchSummerReportcard/",
          method: "POST",
          data: ({
            gradesec:gradesec,
            branch:branch,
            reportaca:reportaca
          }),
          async: false,
          cache: false,
          dataType: 'json',
          beforeSend: function() {
            $('.listSummerReportCard').html('Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="54" height="54" id="loa">');
          },
          success: function(data) {
            $(".listSummerReportCard").html(data);
          }
        })
      }else {
        alert("All fields are required");
      }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#summerRCBR").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filterGradeFromBranch/",
        data: "branchit=" + $("#summerRCBR").val(),
        beforeSend: function() {
          $('#summerRCGR').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="64" height="64" id="loa">' );
        },
        success: function(data) {
          $("#summerRCGR").html(data);
        }
      });
    });
  });
</script>
<script>
  $('#comment_form_updateSummer').on('submit', function(event) {
    event.preventDefault();
    load_mark();
    var outof=$(".outofSummer").val();
    var mid=$(".midSummer").val();
    var value=$(".correct_mark_gsSummer").val();
    var gradesec=$(".gSecSummer").val();
    var year=$(".aYearSummer").val();
    var branch=$(".gsBranchSummer").val();
    function load_mark(){
      $.ajax({
        method:"POST",
        url:"<?php echo base_url() ?>Summerclass/FetchUpdatedMark/",
        data: ({
          mid: mid,
          gradesec:gradesec,
          year:year,
          branch:branch
        }),
        cache: false,
        beforeSend: function() {
          $('.jossMarkSummer'+mid).html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
          );
        },
        success:function(html){
          $('.jossMarkSummer' + mid).html(html);
          //$('.fade').fadeOut('slow');
        }
      });
    }
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Summerclass/updateMarkNow/",
        data: ({
          mid: mid,
          outof:outof,
          value:value,
          gradesec:gradesec,
          year:year,
          branch:branch
        }),
        cache: false,
        beforeSend: function() {
          $('.info-markSummer').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.info-markSummer').html(html);
          load_mark();
        }
      });
  });
</script>
<script>
  $(document).on('click', '.edit_mark_gsSummer', function() {
      var edtim=$(this).attr("value");
      var gradesec=$('.jo_gradesecSummer').val();
      var academicyear=$('.jo_yearSummer').val();
      var branch=$('.jo_branchSummer').val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Summerclass/fetchMarkToEdit/",
        data: ({
          edtim: edtim,
          gradesec:gradesec,
          academicyear:academicyear,
          branch:branch
        }),
        cache: false,
        beforeSend: function() {
          $('#editmarkhere_gsSummer').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('#editmarkhere_gsSummer').html(html);
        }
    });
  });
</script>
<script>
  $(document).on('click', '.delete_selected_gradeSummer', function() {
    if(confirm('Are you sure you want to delete this Grade mark?')){
      var subject=$(".jo_subjectSummer").val();
      var gradesec=$(".jo_gradesecSummer").val();
      var branch=$(".jo_branchSummer").val();
      var year=$(".jo_yearSummer").val();

      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Summerclass/deleteThisGradeMark/",
        data: ({
          subject: subject,
          gradesec:gradesec,
          branch:branch,
          year:year
        }),
        cache: false,
        beforeSend: function() {
          $('.deleteSummerMark').html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(html){
          $('.deleteSummerMark').html(html);
          iziToast.success({
            title: 'This Grade Mark',
            message: 'Deleted successfully',
            position: 'topRight'
          });
        }
      }); 
    }else{
      return false;
    }
  });
</script>
<script>
  $(document).on('click', '.delete_selectedSummer', function() {
    if(confirm('Are you sure you want to delete this subject mark?')){
      var subject=$(".jo_subjectSummer").val();
      var gradesec=$(".jo_gradesecSummer").val();
      var branch=$(".jo_branchSummer").val();
      var year=$(".jo_yearSummer").val();
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Summerclass/deleteThismark/",
        data: ({
          subject: subject,
          gradesec:gradesec,
          branch:branch,
          year:year
        }),
        cache: false,
        beforeSend: function() {
          $('.deleteSummerMark').html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(html){
          $('.deleteSummerMark').html(html);
          iziToast.success({
            title: 'This Subject Mark',
            message: 'Deleted successfully',
            position: 'topRight'
          });
        }
      }); 
    }else{
      return false;
    }
  });
</script>
<script>
  $(document).on('click', '.gs_delete_marknameSummer', function() {
    if(confirm('Are you sure you want to delete this mark?')){
      var subject=$(".jo_subjectSummer").val();
      var gradesec=$(".jo_gradesecSummer").val();
      var branch=$(".jo_branchSummer").val();
      var year=$(".jo_yearSummer").val();
      var markname=$(this).attr("value");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Summerclass/deleteMarkName/",
        data: ({
          subject: subject,
          gradesec:gradesec,
          branch:branch,
          year:year,
          markname: markname
        }),
        cache: false,
        beforeSend: function() {
          $('.deleteSummerMark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.deleteSummerMark').html(html);
        }
      }); 
    }else{
      return false;
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#SummerBranchFormat").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filterGradeFromBranch/",
        data: "branchit=" + $("#SummerBranchFormat").val(),
        beforeSend: function() {
          $('#summerGradesecformat').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="64" height="64" id="loa">' );
        },
        success: function(data) {
          $("#summerGradesecformat").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $('#NGSummerMarkForm').on('submit', function(event) {
    event.preventDefault();
    var gs_branches=$('#deleteSummerMarkBranchNG').val();
    var gs_gradesec=$('.deleteSummerMarkGradesecNG').val();
    var gs_subject=$('.deleteSummerMarkSubjectNG').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Summerclass/fetchNullMark/",
        method: "POST",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject
        }),
        beforeSend: function() {
          $('.deleteSummerMarkNG').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(data) {
          $(".deleteSummerMarkNG").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $('#deleteSummerMarkForm').on('submit', function(event) {
    event.preventDefault();
    var gs_branches=$('#deleteSummerMarkBranch').val();
    var gs_gradesec=$('.deleteSummerMarkGradesec').val();
    var gs_subject=$('.deleteSummerMarkSubject').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Summerclass/fetchSummerGradeMark/",
        method: "GET",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject
        }),
        dataType:'json',
        beforeSend: function() {
          $('.deleteSummerMark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(data) {
          $(".deleteSummerMark").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  $(document).on('click', '.edit_NGmark_gsSummer', function() {
    var stuid=$(this).attr("title");
    var evaid=$(this).attr("value");
    var subject=$(".jo_subjectSummer").val();
    var quarter=$(".jo_quarter").val();
    var markname=$(this).attr("name");
    var outof=$(this).attr("id");
    var gradesec=$(".jo_gradesecSummer").val();
    var academicyear=$(".jo_yearSummer").val();
    var branch=$('.jo_branchSummer').val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>summerclass/fecthNgMarkToEdit_summer/",
      data: ({
        stuid: stuid,
        subject:subject,
        quarter:quarter,
        gradesec:gradesec,
        academicyear:academicyear,
        markname:markname,
        outof:outof,
        evaid:evaid,
        branch:branch
      }),
      cache: false,
      beforeSend: function() {
        $('#editngmarkhere_gs').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('#editngmarkhere_gs').html(html);
      }
    });
  });
  $('#comment_form_NGupdate').on('submit', function(event) {
    event.preventDefault();
    var my_eva=$(".my_evaSummer").val();
    var stuid=$(".my_studentSummer").val();
    var subject=$(".my_subjectSummer").val();
    var quarter=$(".my_quarterSummer").val();
    var year=$(".my_yearSummer").val();
    var gradesec=$(".my_gradeSecSummer").val();
    var val =$(".correct_ngmark_gsSummer").val();
    var markname =$(".my_markNameHSummer").val();
    var outof=$(".my_outOfSummer").val();
    var my_studentBranch=$(".my_BranchSummer").val();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>summerclass/updateNgMarkNow/",
      data: ({
        my_eva: my_eva,
        stuid:stuid,
        subject:subject,
        quarter:quarter,
        year:year,
        val:val,
        gradesec:gradesec,
        markname:markname,
        outof:outof,
        my_studentBranch:my_studentBranch
      }),
      cache: false,
      beforeSend: function() {
        $('.info-ngmarkSummer').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
      },
      success: function(html){
        $('.info-ngmarkSummer').html(html);
        $('.JoMarkSummer'+stuid+markname).html(val);
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#deleteSummerMarkBranch").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filterGradeFromBranch/",
        data: "branchit=" + $("#deleteSummerMarkBranch").val(),
        beforeSend: function() {
          $('.deleteSummerMarkGradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="64" height="64" id="loa">' );
        },
        success: function(data) {
          $(".deleteSummerMarkGradesec").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#deleteSummerMarkBranchNG").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filterGradeFromBranch/",
        data: "branchit=" + $("#deleteSummerMarkBranchNG").val(),
        beforeSend: function() {
          $('.deleteSummerMarkGradesecNG').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="64" height="64" id="loa">' );
        },
        success: function(data) {
          $(".deleteSummerMarkGradesecNG").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#lockSummerMarkBranch").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filterGradeFromBranch/",
        data: "branchit=" + $("#lockSummerMarkBranch").val(),
        beforeSend: function() {
          $('.lockSummerMarkGradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="64" height="64" id="loa">' );
        },
        success: function(data) {
          $(".lockSummerMarkGradesec").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#deleteSummerMarkGradesecNG").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filterSubjectFromSummer/",
        data: "gradesec=" + $("#deleteSummerMarkGradesecNG").val(),
        beforeSend: function() {
          $('.deleteSummerMarkSubjectNG').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".deleteSummerMarkSubjectNG").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#deleteSummerMarkGradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filterSubjectFromSummer/",
        data: "gradesec=" + $("#deleteSummerMarkGradesec").val(),
        beforeSend: function() {
          $('.deleteSummerMarkSubject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".deleteSummerMarkSubject").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#lockSummerMarkGradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filterSubjectFromSummer/",
        data: "gradesec=" + $("#lockSummerMarkGradesec").val(),
        beforeSend: function() {
          $('.lockSummerMarkSubject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".lockSummerMarkSubject").html(data);
        }
      });
    });
  });
</script>
<!-- import mark starts -->
<script type="text/javascript">  
  $(document).ready(function(){  
    $('#uploadSummerMark').on("submit", function(e){  
      e.preventDefault(); 
      $.ajax({  
        url:"<?php echo base_url(); ?>Summerclass/importSummerMark/",  
        method:"POST",  
        data:new FormData(this),  
        contentType:false,         
        cache:false,           
        processData:false,          
        beforeSend: function() {
          $('#uploadMarkInfo').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success: function(data){  
          $('#uploadMarkInfo').html(data);
          $("#uploadSummerMark")[0].reset();   
        }  
      })  
    });  
  });  
 </script>
 <script type="text/javascript">
  $(document).ready(function() {  
    $("#SummerMarkBranch").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filterGradeFromBranch/",
        data: "branchit=" + $("#SummerMarkBranch").val(),
        beforeSend: function() {
          $('.summerMarkGradesec').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="64" height="64" id="loa">' );
        },
        success: function(data) {
          $(".summerMarkGradesec").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {  
    $("#summerMarkGradesec").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filterSubjectFromSummer/",
        data: "gradesec=" + $("#summerMarkGradesec").val(),
        beforeSend: function() {
          $('.summerMarkSubject').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $(".summerMarkSubject").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $('#summerMarkViewForm').on('submit', function(event) {
    event.preventDefault();
    var gs_branches=$('#SummerMarkBranch').val();
    var gs_gradesec=$('.summerMarkGradesec').val();
    var gs_subject=$('.summerMarkSubject').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Summerclass/fecthSummerMarkresult/",
        method: "POST",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          gs_subject:gs_subject
        }),
        beforeSend: function() {
          $('.viewSummerMark').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="64" height="64" id="loa">' );
        },
        success: function(data) {
          $(".viewSummerMark").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Summerclass/fetchSummerPlacement/",
        method:"POST",
        beforeSend: function() {
          $('.fetchSummerPacement').html( 'Loading placement...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="64" height="64" id="loa">');
        },
        success:function(data){
          $('.fetchSummerPacement').html(data);
        }
      })
    }
    $('#saveSummerPlacement').on('submit', function(event) {
      event.preventDefault();
      var academicyear=$('#summerAcademicyear').val();
      var staff=$('#summerStaff').val();
      id=[];subject=[];
      $("input[name='summerGradePlacement']:checked").each(function(i){
        id[i]=$(this).val();
      });
      $("input[name='summerSubject']:checked").each(function(i){
        subject[i]=$(this).val();
      });
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Summerclass/postSummerPlacement/",
        data: ({
          id: id,
          academicyear:academicyear,
          staff:staff,
          subject:subject
        }),
        cache: false,
        success: function(html){
          $('#saveSummerPlacement')[0].reset();
          load_data();
        }
      });
    
  });
  $(document).on('click', '#deleteSummerStaffplacement', function()
  {
    var staff_placement=$(this).attr("value");
    if(confirm('Are you susre you want to delete this Staff')){
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Summerclass/deleteSummerStaffPlacement/",
        data: ({
          staff_placement: staff_placement
        }),
        cache: false,
        beforeSend: function() {
          $('.deleteSummerStaffplacement').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="14" height="14" id="loa">'
          );
        },
        success: function(html){
         $('.deleteSummerStaffplacement').fadeOut('slow');
         load_data();
        }
      });
    }
  }); 
});
</script>
<!-- summer evaluation starts -->
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Summerclass/fetchSummerEvaluations/",
        method:"POST",
        beforeSend: function() {
          $('#summerEvaluationData').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success:function(data){
          $('#summerEvaluationData').html(data);
        }
      })
    }
    $('#saveSummerevaluation').on('submit', function(event) {
      event.preventDefault();
      var grade=$('#summerevaGrade').val();
      var evname=$('.summerevaName').val();
      var percent=$('.summerevaPercent').val();
      id=[];
      $("input[name='summerevaGrade']:checked").each(function(i){
        id[i]=$(this).val();
      });
      if( id.length == 0 || $('.summerevaPercent').val() =='' || $('.summerevaName').val() =='')
      {
        alert("Oooops, Please select necessary fields.");
      }else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Summerclass/postSummerEvaluation/",
          data: ({
            id: id,
            evname:evname,
            percent:percent
          }),
          cache: false,
          success: function(html){
            $('#saveSummerevaluation')[0].reset();
            load_data();
          }
        });
      }
    });
    $(document).on('click', '.deleteSummerEvaluation', function() {
      var post_id = $(this).attr("id");
      var evname = $(this).attr("name");
      if (confirm("Are you sure you want to delete this Evaluation ?")) 
      {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Summerclass/deleteSummerEvaluation/",
          data: ({
            post_id: post_id,
            evname :evname
          }),
          cache: false,
          success: function(html) {
            load_data();
          }
        });
      }else {
        return false;
      }
    });
  });
</script>
<!-- Subject starts -->
<script type="text/javascript">
  $(document).ready(function(){
    loadSubjectData();
    function loadSubjectData()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Summerclass/fetchSummerSubject/",
        method:"POST",
        beforeSend: function() {
          $('.summerSubjectList').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="84" height="84" id="loa">');
        },
        success:function(data){
          $('.summerSubjectList').html(data);
        }
      })
    }
    $('#saveNewSummerSubject').on('submit', function(event) {
      event.preventDefault();
      var subjectName=$('#summerSubjectName').val();
      subjectGrade=[];subjectLetter=[];
      $("input[name='summerSubjectGrade']:checked").each(function(i){
        subjectGrade[i]=$(this).val();
      });
      $("input[name='summerSubjectLetter']:checked").each(function(i){
        subjectLetter[i]=$(this).val();
      });
      if( subjectGrade.length == 0 || $('#summerSubjectName').val() =='')
      {
        alert("Oooops, Please select necessary fields.");
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Summerclass/saveNewSummerSubject/",
        data: ({
          subjectName: subjectName,
          subjectGrade:subjectGrade,
          subjectLetter:subjectLetter
        }),
        cache: false,
        success: function(html){
          $('#saveNewSummerSubject')[0].reset();
          loadSubjectData();
        }
      });
    }
  });
  $(document).on('click', '.deleteSummersubject', function(){
    var post_id = $(this).attr("id");
    if (confirm("Are you sure you want to delete this Subject ?")) {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Summerclass/summerSubjectDelete/",
        data: ({
          post_id: post_id
        }),
        cache: false,
        success: function(html) {
         loadSubjectData();
        }
      });
    }else {
      return false;
    }
  });
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {  
    $("#grands_branchitSummer").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Summerclass/filterGradeFromBranch/",
        data: "branchit=" + $("#grands_branchitSummer").val(),
        beforeSend: function() {
          $('.grands_gradesecSummer').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="64" height="64" id="loa">' );
        },
        success: function(data) {
          $(".grands_gradesecSummer").html(data);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $('#comment_formSummer').on('submit', function(event) {
    event.preventDefault();
    var gs_branches=$('#grands_branchitSummer').val();
    var gs_gradesec=$('.grands_gradesecSummer').val();
    var grands_academicyear=$('#grands_academicyearSummer').val();
    if ($('.gradesec').val() != '') {
      $.ajax({
        url: "<?php echo base_url(); ?>Summerclass/fecthThisStudent/",
        method: "POST",
        data: ({
          gs_branches: gs_branches,
          gs_gradesec:gs_gradesec,
          grands_academicyear:grands_academicyear
        }),
        beforeSend: function() {
          $('.listSummerStudentShow').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">' );
        },
        success: function(data) {
          $(".listSummerStudentShow").html(data);
        }
      })
    }else {
      alert("All fields are required");
    }
  });
  /*delete summer student*/
  $(document).on('click', '.deleteSummerStudent', function() {
    var post_id = $(this).attr("id");
    if (confirm("Are you sure you want to delete this student permantly ?")) {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Summerclass/deleteSummerStudent/",
        data: ({
          post_id: post_id
        }),
        cache: false,
        success: function(html) {
          $(".delete_mem" + post_id).fadeOut('slow');
        }
      });
    }else {
      return false;
    }
  });
  /*edit summer student*/
  $(document).on('click', '.editSummerStudent', function() {
    var editedId = $(this).attr("id");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Summerclass/editSummerStudent/",
      data: ({
        editedId: editedId
      }),
      cache: false,
      beforeSend: function() {
        $('.listSummerStudentShow').html( '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="74" height="74" id="loa">');
      },
      success: function(html) {
        $(".listSummerStudentShow").html(html);
      }
    });
  });
  /*update student*/
  $(document).on('submit', '#updateSummerStuForm', function(e) {
    e.preventDefault();
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Summerclass/updateSummerStudents/",
      data:new FormData(this),
      processData:false,
      contentType:false,
      cache: false,
      async:false,
      beforeSend: function() {
        $('.listSummerStudentShow').html( '<span class="text-info">Updating...</span>');
      },
      success: function(html){
         $(".listSummerStudentShow").html(html);
      }
    });
  });
</script>
<!-- Grade change script starts-->
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Summerclass/fetchSummerClassStatus/",
        method:"POST",
        beforeSend: function() {
          $('#listSummerClassStatus').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success:function(data){
          $('#listSummerClassStatus').html(data);
        }
      })
    }
    $(document).on('click', "input[name='startSummerClass']", function() {
      if($(this).is(':checked')){
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Summerclass/startSummerClass/",
            cache: false,
            success: function(html) {
              iziToast.success({
                title: 'Summer class',
                message: 'started successfully',
                position: 'topRight'
              });
            }
          });
      }else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Summerclass/deleteSummerClass/",
          cache: false,
          success: function(html) {
            iziToast.success({
              title: 'Summer class',
              message: 'deleted successfully',
              position: 'topRight'
            });
          }
        });
      } 
    });
  });
</script>
<!-- Import student -->
<script type="text/javascript">  
  $(document).ready(function(){  
    $('#uploadSummerStudent').on("submit", function(e){  
      e.preventDefault(); 
      $.ajax({  
        url:"<?php echo base_url(); ?>Summerclass/importStudent/",  
        method:"POST",  
        data:new FormData(this),  
        contentType:false,         
        cache:false,           
        processData:false,          
        beforeSend: function() {
          $('#importStudentInfo').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="74" height="74" id="loa">');
        },
        success: function(data){  
          $('#importStudentInfo').html(data);
          $("#uploadSummerStudent")[0].reset();   
        }  
      })  
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

</html>