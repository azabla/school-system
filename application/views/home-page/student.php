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
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
</head>

<body>
  <div class="loader">
    <div class="loaderIcon"></div>
  </div>
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
              <div class="col-lg-12">
                <div class="row">
                  <div class="col-lg-3 col-6">
                    <button type="submit" name="addnew" class="card bg-info btn-block btn-sm" data-toggle="modal"
                      data-target="#newstudent"> Enroll Student<i class="fas fa-user-plus"></i>
                    </button>
                  </div>
                  <div class="col-lg-3 col-6">
                    <button type="submit" id="bulkyEditStudent" name="bulkyEditStudent"
                      class="card bg-warning btn-block btn-sm" data-toggle="modal" data-target="#editGroupStudent">
                      Group Edit<i class="fas fa-user-edit"></i> </button>
                  </div>
                  <div class="col-lg-3 col-6 text-center">
                    <a href="<?php echo base_url(); ?>dropoutstudents/" type="submit" name="" id=""
                      class="card bg-light btn-block btn-sm"> Dropout Students <i class="fas fa-user-minus"></i> </a>
                  </div>
                  <div class="col-lg-3 col-6">
                    <form method="POST" action="<?php echo base_url(); ?>student/downloadStuData/">
                      <button type="submit" id="downloadStuData" name="downloadStuData"
                        class="card bg-success btn-block btn-sm"> Download Student Data <i class="fas fa-download"></i>
                      </button>
                    </form>
                  </div>
                </div>
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <div class="card">
                  <div class="resetPasswordInfo"></div>
                  <div class="card-header">
                    <div class="row">
                      <div class="col-lg-4 col-6">
                        <p class="lastID"></p>
                      </div>
                      <div class="col-lg-4 col-6">
                        <div class="nofstudents" id="nofstudents"></div>
                      </div>
                      <div class="col-lg-4 col-12">
                        <input type="text" name="searchStudent" id="searchStudent" class="form-control typeahead"
                          placeholder="Search Student (Name, Id , Grade . . . ) ">
                      </div>
                    </div>
                  </div>
                  <div class="StudentViewTextInfo">
                    <div class="row">
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="academicyear"
                            id="grands_academicyear">
                            <option>--Year--</option>
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
                          <select class="form-control" required="required" name="branch" id="grands_branchit">
                            <option>--- Branch ---</option>

                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control grands_gradesec" name="gradesec" id="grands_gradesec">
                            <option>--- Section ---</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-6">
                        <div class="form-group">
                          <select class="form-control grands_grade" name="grands_grade" id="grands_grade">
                            <option>--- Grade ---</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-12">
                        <button class="btn btn-primary btn-block" type="submit" id="fetchStudent"
                          name="viewmark">View</button>
                      </div>
                    </div>
                    <div class="listStudentShow" id="student_view"></div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </section>
        <div class="modal fade" id="editGroupStudent" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="infoChangeServicePlace"></div>
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="card">
                <div class="card-body ">
                  <ul class="nav nav-tabs" id="myTab2" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#feedSubject" role="tab"
                        aria-selected="true">Custom Group Edit</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab2" data-toggle="tab" href="#feedMergedSubject" role="tab"
                        aria-selected="false">Default Group Edit</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab3" data-toggle="tab" href="#deleteAllStudent" role="tab"
                        aria-selected="false">Delete All Student Data</a>
                    </li>
                  </ul>
                  <div class="tab-content tab-bordered" id="myTab3Content">
                    <div class="tab-pane fade show active" id="feedSubject" role="tabpanel" aria-labelledby="home-tab1">
                      <div class="modal-body">
                        <div class="card">
                          <div class="card-body">
                            <div class="row">
                              <div class="col-lg-6 col-12">
                                <input type="text" class="form-control typeahead" id="searchStudentForTransportPlace"
                                  name="searchStudentForTransportPlace" placeholder="Search Student Id,Name">
                                <div class="table-responsive" style="height:15vh;">
                                  <div class="searchPlaceHere"></div>
                                </div>
                              </div>
                              <div class="col-lg-6 col-12">
                                <textarea class="form-control" id="selectStudentForTransportPlace"
                                  name="selectStudentForTransportPlace" col="12">  </textarea>
                                <button class="btn btn-default RemoveAll" id="removeAll" type="submit"><i
                                    class="fas fa-angle-double-left"></i></button>
                              </div>
                              <div class="col-lg-6 col-6">
                                <select class="form-control" required="required" name="takeActionOption"
                                  id="takeActionOption">
                                  <option>Select Action</option>
                                  <option value="sectionGroup">Change Section(With Mark)</option>
                                  <option value="gradeGroup">Change Grade</option>
                                  <option value="branchGroup">Change Branch(With Mark)</option>
                                  <option value="branchGroupNoMark">Change Branch(Without Mark)</option>
                                  <option value="deleteGroup">Delete Selected</option>
                                  <option value="dropGroup">Drop(Archive) Selected</option>
                                  <option value="adjustTransPlace">Adjust Transport Place</option>
                                  <option value="ASPON">Add on ASP</option>
                                  <option value="ASPOFF">Remove from ASP</option>
                                </select>
                              </div>

                              <div class="col-md-6 col-6 form-group">
                                <input type="text" class="form-control" id="newSection" disabled="disabled"
                                  name="newSection" placeholder="New section(A,B etc)...">
                              </div>
                              <div class="col-md-6 col-6 form-group">
                                <input type="text" class="form-control" id="newGrade" disabled="disabled"
                                  name="newGrade" placeholder="New Grade(4,5 etc)...">
                              </div>
                              <div class="col-md-6 col-6 form-group">
                                <input type="text" class="form-control" id="newBranch" disabled="disabled"
                                  name="newBranch" placeholder="New Branch with mark...">
                              </div>
                              <div class="col-md-4 col-6 form-group">
                                <input type="text" class="form-control" id="newBranchNoMark" disabled="disabled"
                                  name="newBranchNoMark" placeholder="New Branch with out mark...">
                              </div>
                              <div class="col-md-4 col-6 form-group">
                                <input type="text" class="form-control" id="newServicePlace" disabled="disabled"
                                  name="newServicePlace" placeholder="New Service Place...">
                              </div>
                              <div class="col-lg-4 col-12">
                                <button type="submit" class="btn btn-primary btn-block" id="saveNewTransportPlace"
                                  name="saveNewTransportPlace">Save Changes</button>
                              </div>
                            </div>
                            <div class="editGroupStudenthere" id="editGroupStudenthere"> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade show" id="feedMergedSubject" role="tabpanel" aria-labelledby="home-tab2">
                      <div class="infoForChangeDefaultGroupEdit"></div>
                      <div class="row">
                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control selectric" required="required" name="groupbranch"
                              id="groupbranch">
                              <option></option>
                              <?php foreach ($branch as $branchs) { ?>
                              <option value="<?php echo $branchs->name;  ?>"><?php echo $branchs->name; ?></option>
                              <?php  } ?>
                            </select>
                          </div>
                        </div>

                        <div class="col-lg-4 col-6">
                          <div class="form-group">
                            <select class="form-control groupgrade" name="grands_grade" id="groupgrade">
                              <option>--- Grade ---</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4 col-6">
                          <div class="form-group listGradesec_GS">
                            <!-- <select class="form-control groupgradesec" name="gradesec" id="groupgradesec">
                           <option>--- Section ---</option>
                           </select> -->
                          </div>
                        </div>
                        <div class="col-lg-6 col-6">
                          <select class="form-control" required="required" name="takeDefaultActionOption"
                            id="takeDefaultActionOption">
                            <option>Select Action</option>
                            <option value="changeBranchGroup" id="changeBranchGroup">Change Branch</option>
                            <option value="aspBranchGroupON">Add on ASP</option>
                            <option value="aspBranchGroupOFF">Remove from ASP</option>
                            <option value="dropBranchGroup">Drop(Archive) Selected</option>
                            <option value="undropBranchGroup">Re-register(Unarchive) Selected</option>
                            <option value="deleteBranchGroup">Delete Selected</option>
                            <!-- <option value="adjustBranchTransPlace">Adjust Transport Place</option> -->
                          </select>
                        </div>
                        <div class="col-lg-6 col-12">
                          <p class="fecthBranchHere"></p>
                          <button class="btn btn-primary btn-block" type="submit" id="changeDefaultGroup">Save
                            Changes</button>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade show" id="deleteAllStudent" role="tabpanel" aria-labelledby="home-tab3">
                      <button class="btn btn-outline-danger" type="submit" id="eraseAllStudentData">Delete All
                        Student</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="printStudentViewModal" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">
                  <button class="btn btn-default" onclick="codespeedyStudentView()" name="printlessonplan" type="submit"
                    id="">
                    <span class="text-warning">Print <i class="fas fa-print"></i></span>
                  </button>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="StudentViewPrintHere" id="StudentViewPrintHere"> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="newstudent" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4>New student registration</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <?php $nationals = array(
          'Afghan',
          'Albanian',
          'Algerian',
          'American',
          'Andorran',
          'Angolan',
          'Antiguans',
          'Argentinean',
          'Armenian',
          'Australian',
          'Austrian',
          'Azerbaijani',
          'Bahamian',
          'Bahraini',
          'Bangladeshi',
          'Barbadian',
          'Barbudans',
          'Batswana',
          'Belarusian',
          'Belgian',
          'Belizean',
          'Beninese',
          'Bhutanese',
          'Bolivian',
          'Bosnian',
          'Brazilian',
          'British',
          'Bruneian',
          'Bulgarian',
          'Burkinabe',
          'Burmese',
          'Burundian',
          'Cambodian',
          'Cameroonian',
          'Canadian',
          'Cape Verdean',
          'Central African',
          'Chadian',
          'Chilean',
          'Chinese',
          'Colombian',
          'Comoran',
          'Congolese',
          'Costa Rican',
          'Croatian',
          'Cuban',
          'Cypriot',
          'Czech',
          'Danish',
          'Djibouti',
          'Dominican',
          'Dutch',
          'East Timorese',
          'Ecuadorean',
          'Egyptian',
          'Emirian',
          'Equatorial Guinean',
          'Eritrean',
          'Estonian',
          'Ethiopian',
          'Fijian',
          'Filipino',
          'Finnish',
          'French',
          'Gabonese',
          'Gambian',
          'Georgian',
          'German',
          'Ghanaian',
          'Greek',
          'Grenadian',
          'Guatemalan',
          'Guinea-Bissauan',
          'Guinean',
          'Guyanese',
          'Haitian',
          'Herzegovinian',
          'Honduran',
          'Hungarian',
          'I-Kiribati',
          'Icelander',
          'Indian',
          'Indonesian',
          'Iranian',
          'Iraqi',
          'Irish',
          'Israeli',
          'Italian',
          'Ivorian',
          'Jamaican',
          'Japanese',
          'Jordanian',
          'Kazakhstani',
          'Kenyan',
          'Kittian and Nevisian',
          'Kuwaiti',
          'Kyrgyz',
          'Laotian',
          'Latvian',
          'Lebanese',
          'Liberian',
          'Libyan',
          'Liechtensteiner',
          'Lithuanian',
          'Luxembourger',
          'Macedonian',
          'Malagasy',
          'Malawian',
          'Malaysian',
          'Maldivan',
          'Malian',
          'Maltese',
          'Marshallese',
          'Mauritanian',
          'Mauritian',
          'Mexican',
          'Micronesian',
          'Moldovan',
          'Monacan',
          'Mongolian',
          'Moroccan',
          'Mosotho',
          'Motswana',
          'Mozambican',
          'Namibian',
          'Nauruan',
          'Nepali',
          'New Zealander',
          'Nicaraguan',
          'Nigerian',
          'Nigerien',
          'North Korean',
          'Northern Irish',
          'Norwegian',
          'Omani',
          'Pakistani',
          'Palauan',
          'Panamanian',
          'Papua New Guinean',
          'Paraguayan',
          'Peruvian',
          'Polish',
          'Portuguese',
          'Qatari',
          'Romanian',
          'Russian',
          'Rwandan',
          'Saint Lucian',
          'Salvadoran',
          'Samoan',
          'San Marinese',
          'Sao Tomean',
          'Saudi',
          'Scottish',
          'Senegalese',
          'Serbian',
          'Seychellois',
          'Sierra Leonean',
          'Singaporean',
          'Slovakian',
          'Slovenian',
          'Solomon Islander',
          'Somali',
          'South African',
          'South Korean',
          'Spanish',
          'Sri Lankan',
          'Sudanese',
          'Surinamer',
          'Swazi',
          'Swedish',
          'Swiss',
          'Syrian',
          'Taiwanese',
          'Tajik',
          'Tanzanian',
          'Thai',
          'Togolese',
          'Tongan',
          'Trinidadian/Tobagonian',
          'Tunisian',
          'Turkish',
          'Tuvaluan',
          'Ugandan',
          'Ukrainian',
          'Uruguayan',
          'Uzbekistani',
          'Venezuelan',
          'Vietnamese',
          'Welsh',
          'Yemenite',
          'Zambian',
          'Zimbabwean'
        ); ?>
          <form method="POST" id="saveNewFormStudent" class="saveNewFormStudent" name="saveNewFormStudent">
            <div class="form-group">
              <div class="search-element">
                <div class="row">
                  <div class="form-group col-lg-3 col-6">
                    <label for="password2" class="d-block">Student ID(<span class="text-danger"><i
                          class="fas fa-asterisk"></i></span>)</label>
                    <input id="stuid" required="required" type="text" class="form-control" name="stuid">
                    <span class="text-danger">
                      <?php echo form_error('stuid'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <label for="fname">First Name(<span class="text-danger"><i
                          class="fas fa-asterisk"></i></span>)</label>
                    <input id="fname" type="text" class="form-control" required="required" name="fname" autofocus>
                    <span class="text-danger">
                      <?php echo form_error('frist_name'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <label for="lname">Father Name(<span class="text-danger"><i
                          class="fas fa-asterisk"></i></span>)</label>
                    <input id="lname" type="text" class="form-control" required="required" name="lname">
                    <span class="text-danger">
                      <?php echo form_error('last_name'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <label for="gf_name">Last Name(<span class="text-danger"><i
                          class="fas fa-asterisk"></i></span>)</label>
                    <input id="gfname" type="text" class="form-control" required="required" name="gfname">
                    <span class="text-danger">
                      <?php echo form_error('gf_name'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <label for="gf_name">Last of Last Name</label>
                    <input id="llfname" type="text" class="form-control" name="llfname">
                    <span class="text-danger">
                      <?php echo form_error('gf_name'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <label for="dob" class="d-block">Date of Birth</label>
                    <input id="dob" type="date" class="form-control" data-indicator="pwindicator" name="dob">
                    <span class="dropdown-item has-icon text-danger">
                      <?php echo form_error('dob'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <label for="gf_name">Student Age</label>
                    <input id="studentAge" type="number" class="form-control" name="studentAge">
                    <span class="text-danger">
                      <?php echo form_error('gf_name'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <label for="gender">Gender(<span class="text-danger"><i
                          class="fas fa-asterisk"></i></span>)</label><br>
                    <input type="radio" id="gender" name="gender" value="Male">
                    <label>Male</label>&nbsp &nbsp
                    <input type="radio" id="gender" name="gender" value="Female">
                    <label>Female</label>
                    <span class="text-danger">
                      <?php echo form_error('gender'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <div class="form-group">
                      <label for="Profile">Profile Photo</label>
                      <input id="profile" type="file" class="form-control" name="profile">
                      <span class="text-danger">
                        <?php echo form_error('profile'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <div class="form-group">
                      <label for="grade">Grade(<span class="text-danger"><i class="fas fa-asterisk"></i></span>)
                      </label>

                      <?php if($gradeGroups->num_rows()>0){ ?>
                      <select class="form-control selectric" name="grade" id="grade" required>
                        <option> </option>
                        <?php foreach($gradeGroups->result() as $usergroup){ ?>
                        <option> <?php echo $usergroup->grade; ?></option>
                        <?php } ?>
                      </select>
                      <?php } else{ ?>
                      <input id="grade" type="text" class="form-control" name="grade" required>
                      <?php } ?>
                      <span class="text-danger">
                        <?php echo form_error('grade'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <label for="password" class="d-block">Section(<span class="text-danger"><i
                          class="fas fa-asterisk"></i></span>)</label>
                    <select class="form-control selectric" required="required" name="sec" id="sec">
                      <option> </option>
                      <option> A</option>
                      <option> B</option>
                      <option> C</option>
                      <option> D</option>
                      <option> E</option>
                      <option> F</option>
                      <option> G</option>
                      <option> H</option>
                      <option> I</option>
                      <option> J</option>
                      <option> K</option>
                      <option> L</option>
                      <option> N</option>
                      <option> O</option>
                      <option> P</option>
                      <option> Q</option>
                      <option> R</option>
                      <option> S</option>
                      <option> T</option>
                      <option> V</option>
                      <option> W</option>
                      <option> X</option>
                      <option> Y</option>
                      <option> Z</option>
                    </select>
                    <span class="dropdown-item has-icon text-danger">
                      <?php echo form_error('moname'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <label for="gf_name">Year Joined</label>
                    <input id="yearJoined" type="date" class="form-control" name="yearJoined">
                    <span class="text-danger">
                      <?php echo form_error('gf_name'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-4 col-12">
                    <label for="gf_name">Tell us if student has Special Needs</label>
                    <input id="specialNeeds" type="text" class="form-control" name="specialNeeds">
                    <span class="text-danger">
                      <?php echo form_error('gf_name'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-2 col-6">
                    <label for="gf_name">Previous School</label>
                    <input id="previousSchool" type="text" class="form-control" name="previousSchool">
                    <span class="text-danger">
                      <?php echo form_error('gf_name'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-2 col-6">
                    <div class="form-group">
                      <label for="Mobile">Father Mobile</label>
                      <input id="fmobile" type="text" class="form-control" name="fmobile">
                      <span class="text-danger">
                        <?php echo form_error('mobile'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-2 col-6">
                    <div class="form-group">
                      <label for="Mobile">Marital Status</label>
                      <select class="form-control selectric" name="maritalStatus" id="maritalStatus">
                        <option> </option>
                        <option> Single</option>
                        <option> Married</option>
                        <option> Separated</option>
                        <option> Divorced</option>
                        <option> Widowed</option>
                      </select>
                      <span class="text-danger">
                        <?php echo form_error('mobile'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-2 col-6">
                    <label for="dob" class="d-block">Father`s Date of Birth</label>
                    <input id="fdob" type="date" class="form-control" data-indicator="pwindicator" name="fdob">
                    <span class="dropdown-item has-icon text-danger">
                      <?php echo form_error('dob'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-2 col-6">
                    <label for="gf_name">Father`s Age</label>
                    <input id="fatherAge" type="number" class="form-control" name="fatherAge">
                    <span class="text-danger">
                      <?php echo form_error('gf_name'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-2 col-6">
                    <div class="form-group">
                      <label for="Mobile">Work type</label>
                      <input id="workType" type="text" class="form-control" name="workType">
                      <span class="text-danger">
                        <?php echo form_error('mobile'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <div class="form-group">
                      <label for="Mobile">Work Place</label>
                      <input id="workPlace" type="text" class="form-control" name="workPlace">
                      <span class="text-danger">
                        <?php echo form_error('mobile'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-2 col-6">
                    <div class="form-group">
                      <label for="Mobile">Nationality</label>
                      <select class="form-control selectric" name="nationality" id="nationality">
                        <option> </option>
                        <?php foreach($nationals as $national){ ?>
                        <option> <?php echo $national ?></option>
                        <?php } ?>
                      </select>
                      <span class="text-danger">
                        <?php echo form_error('mobile'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input id="email" type="email" class="form-control" name="email">
                      <span class="text-danger">
                        <?php echo form_error('email'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <div class="form-group">
                      <label for="city">City</label>
                      <select class="form-control selectric" name="city" id="city">
                        <option> </option>
                        <option> Addis Ababa</option>
                        <option> Sheger City</option>
                        <option> Adama/Nazreth</option>
                        <option> Mekelle</option>
                        <option> Bahir Dar</option>
                        <option> Hawassa</option>
                        <option> Jimma</option>
                        <option> Gonder</option>
                        <option> Harrer</option>
                        <option> Dilla</option>
                        <option> Axum</option>
                        <option> Dire Dawa</option>
                        <option> Dukem</option>
                        <option> D/Zeit(Bishoftu)</option>
                        <option> Other</option>
                      </select>
                      <span class="text-danger">
                        <?php echo form_error('city'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <div class="form-group">
                      <label for="Sub_city">Sub City</label>
                      <select class="form-control selectric" name="subcity" id="subcity">
                        <option> </option>
                        <option> Arada</option>
                        <option> Bole</option>
                        <option> Akaki Kality</option>
                        <option> Ns.Lafto</option>
                        <option> Gullele</option>
                        <option> Yeka</option>
                        <option> Kirkos</option>
                        <option> Kolfe</option>
                        <option> Addis Ketema</option>
                        <option> Lemi Kura</option>
                      </select>
                      <span class="text-danger">
                        <?php echo form_error('subcity'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <div class="form-group">
                      <label for="woreda">Woreda</label>
                      <select class="form-control selectric" name="woreda" id="woreda">
                        <option> </option>
                        <option> 01</option>
                        <option> 02</option>
                        <option> 03</option>
                        <option> 04</option>
                        <option> 05</option>
                        <option> 06</option>
                        <option> 07</option>
                        <option> 08</option>
                        <option> 09</option>
                        <option> 10</option>
                        <option> 11</option>
                        <option> 12</option>
                        <option> 13</option>
                        <option> 14</option>
                        <option> 15</option>
                      </select>
                      <span class="text-danger">
                        <?php echo form_error('woreda'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <div class="form-group">
                      <label for="woreda">Home Place/ House Number</label>
                      <input type="text" id="homePlace" name="homePlace" class="form-control">
                      <span class="text-danger">
                        <?php echo form_error('home_place'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <div class="form-group">
                      <label for="woreda">Mother Full Name</label>
                      <input type="text" id="motherfullname" name="motherfullname" class="form-control">
                      <span class="text-danger">
                        <?php echo form_error('home_place'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <div class="form-group">
                      <label for="Username">Mother Mobile</label>
                      <input id="mmobile" type="text" class="form-control" name="mmobile">
                      <span class="text-danger">
                        <?php echo form_error('username'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <div class="form-group">
                      <label for="usertype">User Type(<span class="text-danger"><i
                            class="fas fa-asterisk"></i></span>)</label>
                      <select class="form-control selectric" name="usertype" id="usertype" required="required">
                        <option> Student</option>
                      </select>
                      <span class="text-danger">
                        <?php echo form_error('usertype'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <label for="password" class="d-block">Password(<span class="text-danger"><i
                          class="fas fa-asterisk"></i></span>)</label>
                    <input id="password" required="required" type="password" class="form-control pwstrength"
                      data-indicator="pwindicator" name="password">
                    <span class="text-danger">
                      <?php echo form_error('password'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <label for="password2" class="d-block">Conf. Password(<span class="text-danger"><i
                          class="fas fa-asterisk"></i></span>)</label>
                    <input id="password2" required="required" type="password" class="form-control" name="password2">
                    <span class="text-danger">
                      <?php echo form_error('password-confirm'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <label for="password2" class="d-block">School Branch(<span class="text-danger"><i
                          class="fas fa-asterisk"></i></span>)</label>
                    <select class="form-control selectric" required="required" name="branch" id="branch">
                      <?php foreach($branch as $branchs){ ?>
                      <option><?php echo $branchs->name ?></option>
                      <?php } ?>
                    </select>
                    <span class="text-danger">
                      <?php echo form_error('password-confirm'); ?>
                    </span>
                  </div>
                  <div class="form-group col-lg-3 col-6">
                    <div class="form-group">
                      <label for="ac">Academic year(<span class="text-danger"><i
                            class="fas fa-asterisk"></i></span>)</label>
                      <select class=" form-control selectric" required="required" name="academicyear" id="academicyear">
                        <?php foreach($academicyear as $academicyears){ ?>
                        <option><?php echo $academicyears->year_name ?>
                        </option>
                        <?php } ?>
                      </select>
                      <span class="text-danger">
                        <?php echo form_error('ac'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-12">
                    <button class="btn btn-primary pull-right" name="savenewstudent" id="savenewstudent" type="submit">
                      <i class="fas fa-save"></i> Save student record
                    </button>
                  </div>
                </div>
                <h4 class="msg" id="msg"></h4>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="editStudentInfoPage" tabindex="-1" role="dialog" aria-labelledby="formModal"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="edit_student" id="edit_student">Edit Student </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body card-header">
          <div class="edit_student_page_place"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- General JS Scripts -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"> </script> -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
</body>
<script type="text/javascript">
$(document).on('click', '#eraseAllStudentData', function() {
  event.preventDefault();
  swal({
      title: 'Are you sure you want to delete all student data?',
      text: 'Once deleted you can not recover this data!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal({
            title: 'Again are you sure you want to delete all student data?',
            text: 'Note that Once deleted you can not recover this data!',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                url: "<?php echo base_url(); ?>student/deleteStudentData/",
                method: "POST",
                beforeSend: function() {
                  $('#eraseAllStudentData').html(
                    'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
                    );
                },
                success: function(html) {
                  $("#eraseAllStudentData").html(html);
                  swal('Data deleted Permanetly!', {
                    icon: 'success',
                  });
                }
              });
            }
          });
      }
    });
});
$(document).on('click', '#dropoutStudents', function() {
  $.ajax({
    url: "<?php echo base_url(); ?>student/fetch_dropout_students/",
    method: "POST",
    beforeSend: function() {
      $('.listStudentShow').html(
        'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">'
        );
    },
    success: function(data) {
      $('.listStudentShow').html(data);
    }
  })
});

function selectAll_gradesec_GS_Now() {
  var itemsall = document.getElementById('selectAll_gradesec_GS');
  if (itemsall.checked == true) {
    var items = document.getElementsByName('gradesec_list_gs');
    for (var i = 0; i < items.length; i++) {
      items[i].checked = true;
    }
  } else {
    var items = document.getElementsByName('gradesec_list_gs');
    for (var i = 0; i < items.length; i++) {
      items[i].checked = false;
    }
  }
}
</script>
<script type="text/javascript">
$(document).on('submit', '#saveNewFormStudent', function(e) {
  e.preventDefault();
  if ($('#fname').val() != '' && $('#lname').val() != '' && $('#gfname').val() != '' && $('#grade').val() != '' &&
    $('#sec').val() != '' && $('#password').val() != '' && $('#password2').val() != '' && $("#gender:checked").val()
    ) {
    if ($('#password').val() == $('#password2').val()) {
      var submitButton = $(this, "input[type='submit']");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>register_new_student/",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function() {
          $('#savenewstudent').html('<span class="text-info">Saving...</span>');
          submitButton.attr("disabled", "true");
        },
        success: function(html) {
          $('#savenewstudent').html(
            '<span class="text-info"> <i class="fas fa-save"></i> Save student record</span>');
          submitButton.attr("disabled", "false");
          if (html === '1') {
            $(".saveNewFormStudent")[0].reset();
            iziToast.success({
              title: 'Student record saved successfully.',
              message: '',
              position: 'topRight'
            });
          } else {
            iziToast.error({
              title: 'Student ID already exists. Please try with different ID.',
              message: '',
              position: 'topRight'
            });
          }
        }
      });
    } else {
      swal('Password does not match!', {
        icon: 'error',
      });
    }
  } else {
    swal('Please fill all fields!', {
      icon: 'error',
    });
  }
});
</script>
<script type="text/javascript">
$(document).on('click', '#changeDefaultGroup', function() {
  event.preventDefault();
  groupSection = [];
  $("input[name='gradesec_list_gs']:checked").each(function(i) {
    groupSection[i] = $(this).val();
  });
  var groupBranch = $('#groupbranch').val();
  var actionType = $('#takeDefaultActionOption').val();
  if (groupSection.length != 0) {
    swal({
        title: 'Are you sure you want to change this student status?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          if ($('#takeDefaultActionOption').val() != 'changeBranchGroup') {
            $.ajax({
              url: "<?php echo base_url(); ?>student/changeDefaultGroupEdit/",
              method: "POST",
              data: ({
                groupBranch: groupBranch,
                groupSection: groupSection,
                actionType: actionType
              }),
              beforeSend: function() {
                $('.infoForChangeDefaultGroupEdit').html(
                  'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
                  );
              },
              success: function(html) {
                $(".infoForChangeDefaultGroupEdit").html(html);
              }
            });
          } else if ($('#takeDefaultActionOption').val() == 'changeBranchGroup') {
            var newBranchName = $('#newGsBranchName').val();
            $.ajax({
              url: "<?php echo base_url(); ?>student/changeDefaultGroupEditBranch/",
              method: "POST",
              data: ({
                groupBranch: groupBranch,
                groupSection: groupSection,
                actionType: actionType,
                newBranchName: newBranchName
              }),
              beforeSend: function() {
                $('.infoForChangeDefaultGroupEdit').html(
                  'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
                  );
              },
              success: function(html) {
                $(".infoForChangeDefaultGroupEdit").html(html);
              }
            });
          }
        }
      });
  } else {
    swal('Please select all necessary fields!', {
      icon: 'error',
    });
  }
});
$(document).ready(function() {
  $("#takeDefaultActionOption").bind("change", function() {
    if ($('#takeDefaultActionOption').val() === 'changeBranchGroup') {
      var groupBranch = $('#groupbranch').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>student/fetch_brachto_defaultChange/",
        data: ({
          groupBranch: groupBranch
        }),
        beforeSend: function() {
          $('.fecthBranchHere').html(
            '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".fecthBranchHere").html(data);
        }
      });
    } else {
      $(".fecthBranchHere").html('');
    }
  });
});
$(document).ready(function() {
  $("#groupbranch").bind("change", function() {
    var groupBranch = $('#groupbranch').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Student/fetch_brachto_defaultChange/",
      data: ({
        groupBranch: groupBranch
      }),
      beforeSend: function() {
        $('.fecthBranchHere').html(
          '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data) {
        $(".fecthBranchHere").html(data);
      }
    });
  });
});
</script>
<script type="text/javascript">
$(document).ready(function() {
  $("#groupgrade").bind("change", function() {
    var grades = $('#groupgrade').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>student/filter_gradesec_ongrade_change/",
      data: ({
        grades: grades
      }),
      beforeSend: function() {
        $('.listGradesec_GS').html(
          '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data) {
        $(".listGradesec_GS").html(data);
      }
    });
  });
});
$(document).ready(function() {
  $("#groupbranch").bind("change", function() {
    var branchit = $('#groupbranch').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Student/filterOnlyGradeFromBranchForGroup/",
      data: ({
        branchit: branchit
      }),
      beforeSend: function() {
        $('.groupgrade').html(
          '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data) {
        $(".groupgrade").html(data);
      }
    });
  });
});
</script>

<script type="text/javascript">
$(document).ready(function() {
  $('#searchStudentForTransportPlace').on("keyup", function() {
    $searchItem = $('#searchStudentForTransportPlace').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>student/searchStudentsToTransportService/",
      data: "searchItem=" + $("#searchStudentForTransportPlace").val(),
      beforeSend: function() {
        $('.searchPlaceHere').html(
          'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      success: function(data) {
        $(".searchPlaceHere").html(data);
      }
    });
  });
});
$(document).on('click', '.saveThisStudentToGroupEdit', function() {
  event.preventDefault();
  var oldText = $('#selectStudentForTransportPlace').val();
  var stuID = $(this).attr("value");
  var newText = oldText + stuID + "\n";
  $("#selectStudentForTransportPlace").val(newText);
});
$(document).on('click', '#removeAll', function() {
  event.preventDefault();
  $("#selectStudentForTransportPlace").val('');
});
</script>
<script type="text/javascript">
$(document).on('change', '#takeActionOption', function() {
  var abtype = $(this).val();
  if ($(this).val() == 'adjustTransPlace') {
    $("input[name='newServicePlace']:disabled").each(function(i) {
      $(this).removeAttr("disabled", "disabled");
    });
  } else {
    $("#newServicePlace").attr("disabled", "disabled");
  }
  if ($(this).val() == 'sectionGroup') {
    $("input[name='newSection']:disabled").each(function(i) {
      $(this).removeAttr("disabled", "disabled");
    });
  } else {
    $("#newSection").attr("disabled", "disabled");
  }
  if ($(this).val() == 'gradeGroup') {
    $("input[name='newGrade']:disabled").each(function(i) {
      $(this).removeAttr("disabled", "disabled");
    });
  } else {
    $("#newGrade").attr("disabled", "disabled");
  }
  if ($(this).val() == 'branchGroup') {
    $("input[name='newBranch']:disabled").each(function(i) {
      $(this).removeAttr("disabled", "disabled");
    });
  } else {
    $("#newBranch").attr("disabled", "disabled");
  }
  if ($(this).val() == 'branchGroupNoMark') {
    $("input[name='newBranchNoMark']:disabled").each(function(i) {
      $(this).removeAttr("disabled", "disabled");
    });
  } else {
    $("#newBranchNoMark").attr("disabled", "disabled");
  }
});
$(document).on('click', '#saveNewTransportPlace', function() {
  event.preventDefault();
  var takeAction = $('#takeActionOption').val();
  var newServiceTransPlace = $('#newServicePlace').val();
  var newSection = $('#newSection').val();
  var newGrade = $('#newGrade').val();
  var branchGroupNoMark = $('#newBranchNoMark').val();
  var branchGroup = $('#newBranch').val();
  var newServicePlace = $('#selectStudentForTransportPlace').val();
  var stuIdArray = newServicePlace.split(/(\s+)/);
  swal({
      title: 'Are you sure?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: "<?php echo base_url(); ?>student/saveNewTransportPlace/",
          method: "POST",
          data: ({
            stuIdArray: stuIdArray,
            newServiceTransPlace: newServiceTransPlace,
            takeAction: takeAction,
            newSection: newSection,
            newGrade: newGrade,
            branchGroup: branchGroup,
            branchGroupNoMark: branchGroupNoMark
          }),
          beforeSend: function() {
            $('.infoChangeServicePlace').html(
              'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
              );
          },
          success: function(data) {
            $(".infoChangeServicePlace").html(data);
          }
        });
      }
    });
});
</script>
<script type="text/javascript">
function codespeedyAttendancePrint() {
  var print_div = document.getElementById("prinThiStudentAttendance");
  var print_area = window.open();
  print_area.document.write(print_div.innerHTML);
  print_area.document.write(
    '<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
  print_area.document.write(
    '<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
  print_area.document.write(
    '<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
  print_area.document.close();
  print_area.focus();
  print_area.print();
}
</script>
<script type="text/javascript">
$(document).on('click', '#viewStuAttendance', function() {
  event.preventDefault();
  var stuID = $(this).attr("value");
  var yearattende = $(this).attr("name");
  $.ajax({
    url: "<?php echo base_url(); ?>student/fecthThiStudentAttendance/",
    method: "POST",
    data: ({
      stuID: stuID,
      yearattende: yearattende
    }),
    beforeSend: function() {
      $('.listStudentShow').html(
        'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
        );
    },
    success: function(data) {
      $(".listStudentShow").html(data);
    }
  })
});
</script>
<!-- Grade change script starts-->
<script type="text/javascript">
$(document).ready(function() {
  $("#grands_academicyear").bind("change", function() {
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>student/filterGradesecfromBranch/",
      data: "academicyear=" + $("#grands_academicyear").val(),
      beforeSend: function() {
        $('#grands_branchit').html(
          '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data) {
        $("#grands_branchit").html(data);
      }
    });
  });
});
$(document).ready(function() {
  $('#searchStudent').on("keyup", function() {
    $searchItem = $('#searchStudent').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>student/searchStudent/",
      data: "searchItem=" + $("#searchStudent").val(),
      beforeSend: function() {
        $('.listStudentShow').html(
          'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      success: function(data) {
        $(".listStudentShow").html(data);
      }
    });
  });
});
</script>
<script type="text/javascript">
$(document).ready(function() {
  $("#grands_branchit").bind("change", function() {
    var branchit = $('#grands_branchit').val();
    var grands_academicyear = $('#grands_academicyear').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>student/Filter_grade_from_branch/",
      data: ({
        branchit: branchit,
        grands_academicyear: grands_academicyear
      }),
      beforeSend: function() {
        $('.grands_gradesec').html(
          '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data) {
        $(".grands_gradesec").html(data);
      }
    });
  });
});
$(document).ready(function() {
  $("#grands_branchit").bind("change", function() {
    var branchit = $('#grands_branchit').val();
    var grands_academicyear = $('#grands_academicyear').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Student/filterOnlyGradeFromBranch/",
      data: ({
        branchit: branchit,
        grands_academicyear: grands_academicyear
      }),
      beforeSend: function() {
        $('.grands_grade').html(
          '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data) {
        $(".grands_grade").html(data);
      }
    });
  });
});
</script>
<!-- Grade change script ends -->
<script type="text/javascript">
$(document).on('click', '#fetchStudent', function() {
  event.preventDefault();
  var gs_branches = $('#grands_branchit').val();
  var gs_gradesec = $('.grands_gradesec').val();
  var onlyGrade = $('.grands_grade').val();
  var grands_academicyear = $('#grands_academicyear').val();
  if ($('.grands_gradesec').val() != '' || $('.grands_grade').val() != '') {
    $.ajax({
      url: "<?php echo base_url(); ?>student/Fecth_thistudent/",
      method: "POST",
      data: ({
        gs_branches: gs_branches,
        gs_gradesec: gs_gradesec,
        onlyGrade: onlyGrade,
        grands_academicyear: grands_academicyear
      }),
      beforeSend: function() {
        $('.listStudentShow').html(
          'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
          );
      },
      success: function(data) {
        $(".listStudentShow").html(data);
      }
    })
  } else {
    swal('All fields are required.', {
      icon: 'error',
    });
  }
});
</script>
<script type="text/javascript">
$(document).on('click', '#changecolor', function() {
  var bgcolor = $(this).attr("value");
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
var bgcolor_now = document.getElementById("bgcolor_now").value;
if (bgcolor_now == "1") {
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
</script>
<script type="text/javascript">
$(document).on('click', '#downloadStuData', function() {
  $.ajax({
    method: "POST",
    url: "<?php echo base_url(); ?>student/downloadStuData/",
    cache: false,
    success: function(html) {
      $("#downloadStuData").html('Download Finished.');
      window.open('<?php echo base_url(); ?>student/downloadStuData/', '_blanck');
    }
  });
});
</script>
<script type="text/javascript">
$(document).on('click', '.deletestudent', function() {
  var post_id = $(this).attr("id");
  swal({
      title: 'Are you sure?',
      text: 'Once deleted, you will not be able to recover this student file!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal('Student Deleted Successfully!', {
          icon: 'success',
        });
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>student/delete_student",
          data: ({
            post_id: post_id
          }),
          cache: false,
          success: function(html) {
            $(".delete_mem" + post_id).fadeOut('slow');
          }
        });
      }
    });
});
</script>
<script type="text/javascript">
$(document).on('click', '.dropstudent', function() {
  var drop_id = $(this).attr("id");
  swal({
      title: 'Are you sure?',
      text: 'Once Droped, you will not be able to see this student file!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })

    .then((willDelete) => {
      if (willDelete) {
        swal('Student Droped Successfully!', {
          icon: 'success',
        });
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>student/",
          data: ({
            drop_id: drop_id
          }),
          cache: false,
          success: function(html) {
            $(".delete_mem" + drop_id).fadeOut('slow');
          }
        });
      }
    });
});
</script>
<script type="text/javascript">
$(document).on('click', '.editstudent', function() {
  var editedId = $(this).attr("id");
  var newAcademicYear = $(this).attr("value");
  $.ajax({
    method: "POST",
    url: "<?php echo base_url(); ?>student/editstudent/",
    data: ({
      editedId: editedId,
      newAcademicYear: newAcademicYear
    }),
    cache: false,
    beforeSend: function() {
      $('.edit_student_page_place').html(
        '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="34" height="34" id="loa">');
    },
    success: function(html) {
      $(".edit_student_page_place").html(html);
    }
  });
});
$(document).on('click', '.leavingRequest', function() {
  var editedId = $(this).attr("id");
  var newAcademicYear = $(this).attr("value");
  $.ajax({
    method: "POST",
    url: "<?php echo base_url(); ?>student/leavingRequest/",
    data: ({
      editedId: editedId,
      newAcademicYear: newAcademicYear
    }),
    cache: false,
    beforeSend: function() {
      $('.listStudentShow').html(
        '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="34" height="34" id="loa">');
    },
    success: function(html) {
      $(".listStudentShow").html(html);
    }
  });
});
</script>
<script type="text/javascript">
$(document).on('click', '.viewStudentPrint', function() {
  var editedId = $(this).attr("id");
  $.ajax({
    method: "POST",
    url: "<?php echo base_url(); ?>student/viewStudentPrint/",
    data: ({
      editedId: editedId
    }),
    cache: false,
    beforeSend: function() {
      $('.StudentViewPrintHere').html(
        '<img src="<?php echo base_url() ?>img/loader.gif" alt="" width="34" height="34" id="loa">');
    },
    success: function(html) {
      $(".StudentViewPrintHere").html(html);
    }
  });
});
</script>
<script type="text/javascript">
function codespeedyStudentView() {
  var print_div = document.getElementById("StudentViewPrintHere");
  var print_area = window.open();
  print_area.document.write(print_div.innerHTML);
  print_area.document.write(
    '<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
  print_area.document.write(
    '<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
  print_area.document.write(
    '<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
  print_area.document.close();
  print_area.focus();
  print_area.print();
}

function codespeedyStudentLeaving() {
  var print_div = document.getElementById("PrintStudentRequestPaper");
  var print_area = window.open();
  print_area.document.write(print_div.innerHTML);
  print_area.document.write(
    '<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
  print_area.document.write(
    '<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
  print_area.document.write(
    '<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
  print_area.document.close();
  print_area.focus();
  print_area.print();
}
</script>
<script type="text/javascript">
$(document).on('click', '.resetPassword', function() {
  var editedId = $(this).attr("id");
  $.ajax({
    method: "POST",
    url: "<?php echo base_url(); ?>student/resetPassword/",
    data: ({
      editedId: editedId
    }),
    cache: false,
    beforeSend: function() {
      $('.resetPasswordInfo').html('Reseting...');
    },
    success: function(html) {
      $(".resetPasswordInfo").html(html);
    }
  });
});
</script>
<script type="text/javascript">
$(document).on('submit', '#updateStuForm', function(e) {
  e.preventDefault();
  $.ajax({
    method: "POST",
    url: "<?php echo base_url(); ?>student/updateStudents/",
    data: new FormData(this),
    processData: false,
    contentType: false,
    cache: false,
    beforeSend: function() {
      $('#save_student_profile_changes').html('<span class="text-info">Saving...</span>');
      $('#save_student_profile_changes').attr("disabled", "disabled");
    },
    success: function(html) {
      $('#save_student_profile_changes').html('<span class="text-info"> Save Changes</span>');
      $('#save_student_profile_changes').removeAttr("disabled");
      if (html === '1') {
        iziToast.success({
          title: 'Changes updated successfully.',
          message: '',
          position: 'topRight'
        });
        $('#editStudentInfoPage').modal('hide');
      } else {
        iziToast.error({
          title: 'Please try again.',
          message: '',
          position: 'topRight'
        });
      }
    }
  });
});
</script>
<script>
$(document).ready(function() {
  function nofstudents(view = '') {
    $.ajax({
      url: "<?php echo base_url() ?>nOfStudents/",
      method: "POST",
      data: ({
        view: view
      }),
      success: function(html) {
        $('.nofstudents').html(html);
      }
    });
  }

  function last_student_ID(view = '') {
    $.ajax({
      url: "<?php echo base_url() ?>last_student_ID/",
      method: "POST",
      data: ({
        view: view
      }),
      success: function(html) {
        $('.lastID').html(html);
      }
    });
  }

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
  nofstudents();
  unseen_notification();
  inbox_unseen_notification();
  last_student_ID();
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
    last_student_ID();
    nofstudents();
  }, 5000);
});
</script>

</html>