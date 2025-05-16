         <div class="sidebar-brand">
          <a href="#"> <img alt="image" src="<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>" style="height:35px;width:35px;border-radius: 3em;" class="header-logo" /> 
              <!-- <span class="logo-name"> 
                <?php foreach($schools as $school) {
                  echo $school->name;} ?>
              </span> -->
            </a>
          </div>
          <ul class="sidebar-menu">
            <!-- <li class="dropdown">
              <a href="dashboard/?dashboard-page/" class="nav-link">
                <i data-feather="monitor"></i><span>Dashboard</span></a>
            </li> -->
            <!-- <li class="dropdown">
              <a href="home/?admin-home-page/" class="nav-link"><i data-feather="home"></i><span>Home</span></a>
            </li> -->
            <?php if($_SESSION['usertype']==='superAdmin'){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="user-plus"></i><span>Manage User Group</span>
              </a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?php echo base_url(); ?>usergroup/">Add User Group</a>
                </li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>userpermission/">User Permission</a>
                </li>
              </ul>
            </li>
            <?php } ?> 
            <?php $uperStupro=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentPr' order by id ASC ");  
            if($uperStupro->num_rows()>0){ ?>
              <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="user-plus"></i><span>Registration Mgmt</span>
              </a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?php echo base_url() ?>newstaffs/?student-registration-request-page/">Registration Request</a>
                </li>
                <?php $uperStuAppp=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentApproval' order by id ASC ");  
                if($uperStuAppp->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url() ?>newstaffsfinanceapproval/?student-registration-approval-page/">Registration Approval</a>
                  </li>
                <?php } ?>
              </ul>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' order by id ASC ");  
            if($usergroupPermission->num_rows()>0){  ?>
            <li class="dropdown">
               <a href="" class="menu-toggle nav-link has-dropdown"><i data-feather="users"></i><span>Student(s) Mgmt</span>
                </a>
              <ul class="dropdown-menu">
                <?php $uperStuDE=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentDE' order by id ASC ");  if($uperStuDE->num_rows()>0){  ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>student/">Manage Student</a>
                  </li>
                <?php } $uperStupro=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentPr' order by id ASC ");  if($uperStupro->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url() ?>registration/">Student Registration</a>
                  </li>
                  <!-- <li><a class="nav-link" href="<?php echo base_url() ?>newstaffs/?student-registration-request-page/">Registration Request</a>
                  </li> -->
                <?php } $uperStuAppp=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentApproval' order by id ASC ");  
                  if($uperStuAppp->num_rows()>0){ ?>
                  <!-- <li><a class="nav-link" href="<?php echo base_url() ?>newstaffsfinanceapproval/?student-registration-approval-page/">Registration Approval</a>
                  </li> -->
                <?php } $uperStuPl=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentPl' order by id ASC ");  if($uperStuPl->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url() ?>automaticplacement/?student-section-placement-page/">Student Placement</a>
                  </li>
                <?php } $uperStuPl=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='Studentbp' order by id ASC ");  if($uperStuPl->num_rows()>0){ ?>
                  <li> <a class="nav-link" href="<?php echo base_url(); ?>branchplacement/">Branch Placement</a> </li>
                <?php } $uperStuPl=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentVaccination' order by id ASC ");  if($uperStuPl->num_rows()>0){ ?>
                  <li> <a class="nav-link" href="<?php echo base_url(); ?>studentvaccination/?student-vaccination-page/">Student Vaccination</a> </li>
                <?php } $uperStuIR=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentIncident' order by id ASC ");  if($uperStuIR->num_rows()>0){ ?>
                  <li> <a class="nav-link" href="<?php echo base_url(); ?>studentincident/?student-incident-report-page/">Incident Report</a> </li>
                <?php }  $uperStuView=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentVE' order by id ASC "); if($uperStuView->num_rows()>0){ ?>
                <li class="dropdown">
                  <a href="" class="menu-toggle nav-link has-dropdown"><span>Record Report</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li>
                      <a class="nav-link" href="<?php echo base_url(); ?>divisinrecordreport/"><span>By Division</span>
                      </a>
                    </li>
                    <li>
                      <a class="nav-link" href="<?php echo base_url(); ?>graderecordreport/"><span>By Grade</span>
                      </a>
                    </li>
                    <li>
                      <a class="nav-link" href="<?php echo base_url(); ?>sectionrecordreport/"><span>By Section</span>
                      </a>
                    </li>
                    <li>
                      <a class="nav-link" href="<?php echo base_url(); ?>summaryrecordreport/"><span>Summary</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="dropdown">
                  <a href="" class="menu-toggle nav-link has-dropdown"><span>Transport Report</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li>
                      <a class="nav-link" href="<?php echo base_url(); ?>gradetransportreport/"><span>By Grade</span>
                      </a>
                    </li>
                    <li>
                      <a class="nav-link" href="<?php echo base_url(); ?>sectiontransportreport/"><span>By Section</span>
                      </a>
                    </li>
                  </ul>
                </li>
                 <li> <a class="nav-link" href="<?php echo base_url(); ?>nosection/">No.of Section</a>
                </li>
                <li class="dropdown">
                  <a href="" class="menu-toggle nav-link has-dropdown"><span>Gender Report</span>
                  </a>
                  <ul class="dropdown-menu">                    
                    <li>
                      <a class="nav-link" href="<?php echo base_url(); ?>gradereport/"><span>By Grade</span>
                      </a>
                    </li>
                    <li>
                      <a class="nav-link" href="<?php echo base_url(); ?>report/"><span>By Section</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="dropdown">
                  <a href="" class="menu-toggle nav-link has-dropdown"><span>Phone Book</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li> <a href="<?php echo base_url(); ?>gradephonebook/" class="nav-link">By Grade
                    </a>
                    </li>
                    <li> <a href="<?php echo base_url(); ?>phonebook/" class="nav-link">By Section
                    </a>
                    </li>
                  </ul>
                </li>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>similaruserid/"><span>Similar UserId</span>
                  </a>
                </li>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>lastgradeinfo/?student-last-year-grade-information/"><span>Last Grade Info</span>
                  </a>
                </li>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>schoolparents/"><span>School Parents</span>
                  </a>
                </li>
              <?php } $gradeGroup=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='gradeGroup' order by id ASC "); if($gradeGroup->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>gradegroup/"><span>Grade Group</span>
                  </a>
                </li>
              <?php } $StudentPassword=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentPassword' order by id ASC "); if($StudentPassword->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>usernameandpassword/"><span>Username & Password</span>
                  </a>
                </li>
              <?php } $uperStuDrop=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentDrop' order by id ASC "); if($uperStuDrop->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>dropoutstudents/"><span>Dropout Students</span>
                  </a>
                </li>
              <?php } ?>
              <?php $uperStuRequest=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentRequest' order by id ASC "); if($uperStuRequest->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>studentrequest/?student-request-page/"><span>Students Request</span>
                  </a>
                </li>
              <?php } ?>
              </ul>
            </li>
          <?php } ?>
          <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' order by id ASC ");  
          if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown gs-sms-hr-page">
               <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="users"></i><span>HR Management</span>
                </a>
              <ul class="dropdown-menu">
                <?php $userpStaffDe=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffDE' order by id ASC ");  
                if($userpStaffDe->num_rows()>0){ ?>
                  <li><a class="nav-link gs-sms-staff-list-page" href="<?php echo base_url(); ?>staffs/">Staffs List</a> </li>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>payroll/">Staff Payroll</a></li>
                <?php } ?>  
                 <?php $userpStaffGrouping=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffGrouping' order by id ASC "); 
                 if($userpStaffGrouping->num_rows()>0){ ?>
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>staffgrouping/?staff-grouping-page/">Staff Grouping/Dept.</a> 
                  </li>  
                <?php } $userpStaffPhone=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffPhone' order by id ASC "); if($userpStaffPhone->num_rows()>0) { ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>staffphone/">Staffs Phone</a> </li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>staffreport/">Staffs Record Report</a> </li>                
                <?php } ?>
                <?php $userpStaffIncident=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffIncident' order by id ASC ");if($userpStaffIncident->num_rows()>0) {   ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>staffincidentreport/">Staffs Incident Report</a> </li> 
                <li><a class="nav-link" href="<?php echo base_url(); ?>staffselfevaluation/?staffs-self-evaluation-form">Staffs Self-evaluation</a> </li> 
                <?php } ?>
              <li>
              <a class="nav-link" href="<?php echo base_url() ?>experience/">
                <span>Employee Experience</span>
              </a>
             </li>
            </ul>
            </li>
            <?php } ?>
          
          <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' order by id ASC ");  
          if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
               <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="users"></i><span>Staffs Placement</span>
                </a>
              <ul class="dropdown-menu">
                <?php $userpStaffDP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='directorPl' order by id ASC "); if($userpStaffDP->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>directorplacement/">Director Placement</a> </li>
                <?php } $userpStaffTP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffPl' order by id ASC "); if($userpStaffTP->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>placement/">Teacher Placement</a> </li>
                <?php } $userpStaffHrP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='hoomeroomPl' order by id ASC "); if($userpStaffHrP->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>homeroomplacement/">Homeroom Placement</a> </li>
                 <?php } ?> 
              </ul>
            </li>
            <?php } ?>
            <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Subject' order by id ASC "); 
            if($usergroupPermission->num_rows()>0){ ?>
              <li class="dropdown gs-sms-manage-subject-page">
               <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="book"></i><span>Subject(s) Mgmt</span>
                </a>
                <ul class="dropdown-menu">
                  <?php $usergroupGradeSubject=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Subject' and allowed='gradeSubject' order by id ASC "); 
                  if($usergroupGradeSubject->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>subject/">Grade Subject(s) List</a>
                  </li>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>ordersubject/">Sub Order & Placement</a>
                  </li>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>kgsubject/">KG Subject(s) List</a>
                  </li>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>subjectobjectives/">KG Subject(s) Objectives</a>
                  </li>
                  <?php } $usergroupKGSUbjectL=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Subject' and allowed='KgSubjectList' order by id ASC "); 
                  if($usergroupKGSUbjectL->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>kgsubjectlist/?kg-subject-list-page/">የምልከታ መከታታያ ቅፅ</a>
                  </li>
                  <?php } $usergroupKGReport=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Subject' and allowed='KgSubjectReport' order by id ASC "); 
                  if($usergroupKGReport->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>kgsubjectlistreport/?kg-subject-form-page/">የምልከታ መከታታያ ሪፖርት</a>
                  </li>
                  <?php } ?>
                </ul>
              </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='IDCard' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
               <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="list"></i><span>ID Card Mgmt</span>
                </a>
              <ul class="dropdown-menu">
                 <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='IDCard' and allowed='StaffIDCard' order by id ASC ");  
                 if($usergroupPermission->num_rows()>0){ ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>staffidcard/">Staff ID</a>
                </li>
              <?php } ?>
              <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='IDCard' and allowed='StudentIDCard' order by id ASC ");if($usergroupPermission->num_rows()>0){ ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>idcard/">Student ID</a>
                </li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>parentidcard/?parent-id-card-page/">Parent ID</a>
                </li>
              <?php } ?>
              <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='IDCard' and allowed='StudentLibraryID' order by id ASC ");if($usergroupPermission->num_rows()>0){ ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>libraryidcard/?student-library-ID-Card/">Student Library ID</a>
                </li>
              <?php } ?>
              </ul>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='Evaluation' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="briefcase"></i>
                <span>Evaluation Mgmt</span>
              </a>
                <ul class="dropdown-menu">
                  <?php $userPerStuEvaluation=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Evaluation' and allowed='Mgmtevaluation' order by id ASC ");  
                  if($userPerStuEvaluation->num_rows()>0){ ?>
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>evaluation/?school-evaluation-admin-page"><span>Evaluation Weight</span>
                    </a>
                  </li>
               <?php } ?>
               <?php $userPerStuEvaluation=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Evaluation' and allowed='Mgmtassesment' order by id ASC ");  
               if($userPerStuEvaluation->num_rows()>0){ ?>
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>schoolassesment/?school-assesment-admin-page"><span>School Assesment</span>
                    </a>
                  </li>
               <?php } ?>
               </ul>
             </li>
            <?php }  $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown gs-sms-manage-attendance-page">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="user-check"></i>
                <span>Attendance Mgmt</span>
              </a>
            <ul class="dropdown-menu">
              <?php $userPerStuAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='studentAttendance' order by id ASC ");  if($userPerStuAtt->num_rows()>0){ ?>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>attendance/">
                <span>Student Attendance</span>
              </a>
             </li>
             <?php } $aspAttendance=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='studentASP' order by id ASC ");  if($aspAttendance->num_rows()>0) { ?>
              <li>
                <a class="nav-link" href="<?php echo base_url(); ?>aspattendance/?admin-student-asp-attendance-page">
                  <span>Student ASP Attendance</span>
                </a>
              </li>
             <?php } $userPerStaAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='staffAttendance' order by id ASC ");  if($userPerStaAtt->num_rows()>0) { ?>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>staffattendance/">
                <span>Staff Attendance</span>
              </a>
             </li>
            <?php } $userPerStaEvaAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='staffEvaluationAttendance' order by id ASC ");  if($userPerStaEvaAtt->num_rows()>0) { ?>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>staffevaluationattendance/?staff-evaluation-attendance/">
                <span>Supervision Attendance</span>
              </a>
             </li>
            <?php }  ?>
             </ul>
            </li>
            <?php } ?>
            <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='CommunicationBook'  order by id ASC ");
              if($usergroupPermission->num_rows()>0){ ?>
                <li class="dropdown">
               <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="message-square"></i><span>Communication Book</span>
                </a>
                <ul class="dropdown-menu">
                  <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='CommunicationBook' and allowed='sendcommunicationbook' order by id ASC ");
                  if($usergroupPermission->num_rows()>0){ ?>
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>communicationbook/?admin-communication-book/"><span>View Comm. Book</span> </a> 
                  </li>           
                  <?php } ?>
                </ul>
              </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='lessonplan' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="book-open"></i>
                <span>Manage Lesson Plan</span>
              </a>
            <ul class="dropdown-menu">
              <?php $upAddLplan=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='lessonplan' and allowed='addlessonplan' order by id ASC ");  if($upAddLplan->num_rows()>0){ ?>
              <li>
                <a class="nav-link" href="<?php echo base_url(); ?>dailylessonplan/?daily-lesson-plan-page/">
                  <span>Daily Lesson Plan</span>
                </a>
              </li>
              <li>
                <a class="nav-link" href="<?php echo base_url(); ?>addlessonplan/?lesson-plan-page/">
                  <span>Add Annual Lesson Plan</span>
                </a>
              </li>
            <?php } $upViewLplan=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='lessonplan' and allowed='viewlessonplan' order by id ASC ");  if($upViewLplan->num_rows()>0){?>
              <li>
                <a class="nav-link" href="<?php echo base_url(); ?>viewlessonplan/">
                  <span>View Annual Lesson Plan</span>
                </a>
              </li>
            <?php } ?>
            </ul>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='homeworkworksheet' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="book-open"></i>
                <span>Homework/Worksheet</span>
              </a>
            <ul class="dropdown-menu">
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>addlesson/">
                <span>Add HW/Worksheet</span>
              </a>
             </li>
            <li>
              <a class="nav-link" href="<?php echo base_url(); ?>viewlesson/">
                <span>View HW/Worksheet</span>
              </a>
             </li>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>viewansweredworksheet/">
                <span>Answered Worksheet</span>
              </a>
             </li>
             </ul>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='studentexam' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
             <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="file-text"></i>
                <span>Exam Mgmt</span>
              </a>
            <ul class="dropdown-menu">
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>exam/"> 
                <span>Add/Edit Exam</span>
              </a>
             </li>
            <li>
              <a class="nav-link" href="<?php echo base_url(); ?>viewexam/"> 
                <span>View Exam Result</span>
              </a>
             </li>
             </ul>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown gs-sms-manage-mark-page">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="layout"></i><span>Mark Mgmt</span>
              </a>
            <ul class="dropdown-menu">
              <?php $markformat=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='studentmarkformat' order by id ASC ");  if($markformat->num_rows()>0){ ?>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>exportmarkformat/">
                <span>Prepare Mark Format</span>
              </a>
             </li>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>exportmanualmarkformat/?admin-manual-mark-format-page/">
                <span>Manual Mark Format</span>
              </a>
             </li>
              <?php } $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='addstudentmark' order by id ASC ");  if($uaddMark->num_rows()>0){ ?>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>adjusttable/">
                <span>Adjust Table For Mark</span>
              </a>
             </li>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>addexam/">
                <span>Add New result(Online)</span>
              </a>
             </li>
            <li>
              <a class="nav-link" href="<?php echo base_url(); ?>addmark/">
                <span>Edit/Delete Mark result</span>
              </a>
            </li>
          <?php } $approveMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='approvemark' order by id ASC ");  
          if($approveMark->num_rows()>0){ ?>
            <li>
              <a class="nav-link" href="<?php echo base_url(); ?>approvemark/">
                <span>Approve Mark</span>
              </a>
            </li>
           <?php } $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='viewstudentmark' order by id ASC ");  if($uaddMark->num_rows()>0){ ?>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>ngmarkresult/">
                <span>View NG/Zero Result</span>
              </a>
              </li>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>copymovemark/?copy-move-page">
                <span>Copy Mark</span>
              </a>
              </li>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>markresult/">
                <span>View Mark Result</span>
              </a>
              </li>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>markanalysis/">
                <span>Mark Result Analysis</span>
              </a>
              </li>
              <!-- <li class="dropdown">
                <a href="" class="menu-toggle nav-link has-dropdown"><span>Mark Result Analysis</span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a class="nav-link" href="(); ?>subjectmarkanalysis/"><span>By Subject</span>
                    </a>
                  </li>
                  <li>
                    <a class="nav-link" href="(); ?>markanalysis/"><span>By Assesment</span>
                    </a>
                  </li>
                </ul>
              </li> -->
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>assesmentrank/?student-assesment-rank">
                <span>Assesment Rank</span>
              </a>
             </li>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>examstudents/?shuffle-students-page">
                <span>Special Students</span>
              </a>
             </li>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>markprogress/">
                <span>Mark Progress</span>
              </a>
              </li>
              <?php } $userpStaffAI=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='activeInactiveDiv' order by id ASC "); if($userpStaffAI->num_rows()>0){ ?>
              <li>
                <a class="nav-link" href="<?php echo base_url(); ?>markstatus/">
                  <span>Lock Division</span>
                </a>
              </li>
              <?php } $userpStaffAI=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' order by id ASC "); 
              if($userpStaffAI->num_rows()>0){ ?>
                <li>
                <a class="nav-link" href="<?php echo base_url(); ?>lockunlockstudentmark/?lock-unlock-page">
                  <span>Lock/Unlock Mark</span>
                </a>
              </li>
              <?php } ?>
             </ul>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>

              <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
              <i data-feather="clipboard"></i><span>Basic Skill & Conduct</span>
              </a>
            <ul class="dropdown-menu">
              <?php $usergroupADDREmove=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='addRemoveBS' order by id ASC ");  
              if($usergroupADDREmove->num_rows()>0){  ?>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>basicskill/"><span>Add BS Name & Type</span>
              </a>
             </li>
           <?php } $usergroupExportBS=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='exportBSFormat' order by id ASC ");  
              if($usergroupExportBS->num_rows()>0){?>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>exportbsformate/"><span>Export BS format</span>
              </a>
             </li>
           <?php } $usergroupExportBS=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='importBSFormat' order by id ASC ");  
              if($usergroupExportBS->num_rows()>0){?>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>importbs/"><span>Import BS Data</span>
              </a>
             </li>
           <?php } $usergroupCopyBS=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='copyStudentBSDATA' order by id ASC ");  
              if($usergroupCopyBS->num_rows()>0){?>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>movecopybasicskill/?move-copy basicskill-page"><span>Copy Student BS</span>
              </a>
             </li>
             <?php } $usergroupViewBS=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='editStudentBSDATA' order by id ASC ");  
              if($usergroupViewBS->num_rows()>0){?>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>viewstudentbs/"><span>View Student BS</span>
              </a>
             </li>
             <?php } ?>
             </ul>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='ImportExport' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="arrow-down-circle"></i><span>Import & Export</span></a>
              <ul class="dropdown-menu">
                <?php $exportFile=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='ImportExport' and allowed='exportFile' order by id ASC ");  if($exportFile->num_rows()>0){ ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>export/">Export Form</a></li>
              <?php } $importFile=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='ImportExport' and allowed='importFile' order by id ASC ");  if($importFile->num_rows()>0){?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>import/">Import Data</a></li>
              <?php } ?>
              </ul>
            </li>
             <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="grid"></i><span>Card & Roster</span></a>
              <ul class="dropdown-menu">
                 <?php $rpPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='reportcard' order by id ASC "); 
                  if($rpPermission->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>reportcard/">Grade Report Card</a></li>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>kgreportcard/">KG Report Card</a></li>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>semesterreportcard/?semester-report-card-page/">Semester Report Card</a></li>

                  <li><a class="nav-link" href="<?php echo base_url(); ?>sendemailresult/?send-result-email/">Email student result</a></li>

                <?php } $raPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='rankReport' order by id ASC "); 
                  if($raPermission->num_rows()>0){ ?>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>rankreport/?rank-report-page">Rank Report</a></li>
               <!--  <li class="dropdown">
                  <a href="" class="menu-toggle nav-link has-dropdown"><span>Rank Report</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li>
                      <a class="nav-link" href="<?php echo base_url(); ?>branchrankreport/"><span>By Branch</span>
                      </a>
                    </li>
                    <li>
                      <a class="nav-link" href="<?php echo base_url(); ?>graderankreport/"><span>By Grade</span>
                      </a>
                    </li>
                    <li>
                      <a class="nav-link" href="<?php echo base_url(); ?>rankreport/"><span>By Section</span>
                      </a>
                    </li>
                  </ul>
                </li> -->
                <?php } $roPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='roster' order by id ASC "); 
                  if($roPermission->num_rows()>0){ ?>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>roster/?student-roster-book-page">Student Roster</a></li>
                  <?php } $trPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='transcript' order by id ASC "); 
                  if($trPermission->num_rows()>0){ ?>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>transcript/">Student Transcript</a></li>
                  <?php } ?>
              </ul>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='feemanagment' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="dollar-sign"></i><span>Fee Management</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?php echo base_url(); ?>category/">Fee Category</a></li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>payment/">Add Fee</a></li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>paymentreport/">Fee Report</a></li>
              </ul>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='elibrary' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="activity"></i><span>Other Management</span></a>
              <ul class="dropdown-menu">
                <li>
                  <a class="nav-link" href="#">
                    <span>Transportation</span>
                  </a>
                </li>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>library/">
                    <span>E-Library</span>
                  </a>
                </li>
              </ul>
            </li>
            <?php } ?>
            <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='libraryManagement' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="book-open"></i><span>Library Mgmt</span></a>
              <ul class="dropdown-menu">
                <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='libraryManagement' and allowed='libraryBooks' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>librarybooks/?library-management-system/">
                    <span>Library Books</span>
                  </a>
                </li>
                <?php } ?>
                <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='libraryManagement' and allowed='borrowBooks' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>borrowbooks/?library-management-system/">
                    <span>Borrow Books</span>
                  </a>
                </li>
                <?php } ?>
                <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='libraryManagement' and allowed='borrowRequests' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>borrowrequests/?library-management-system/">
                    <span>Borrow Requests</span>
                  </a>
                </li>
                <?php } ?>
                 <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='libraryManagement' and allowed='borrowReport' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>borrowbooksreport/?library-management-system/">
                    <span>Generate Report</span>
                  </a>
                </li>
                <?php } ?>
              </ul>
            </li>
            <?php } ?>
            <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='inventoryManagement' order by id ASC "); 
            if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="shopping-bag"></i><span>Inventory Mgmt</span></a>
              <ul class="dropdown-menu">
                <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='inventoryManagement' and allowed='purchaseRequest' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>requestpurchase/?inventory-management-system/">
                    <span>Request Purchase</span>
                  </a>
                </li>
                <?php } ?>
                <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='inventoryManagement' and allowed='purchaseApprove' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>approvepurchase/?inventory-management-system/">
                    <span>Approve Purchase</span>
                  </a>
                </li>
                <?php } ?>
                <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='inventoryManagement' and allowed='stockCategory' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>stockcategory/?inventory-management-system/">
                    <span>Stock Categroy</span>
                  </a>
                </li>
                <?php } ?>
                
                 <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='inventoryManagement' and allowed='stockItem' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>inventory/?-management-system/">
                    <span>Stock Item</span>
                  </a>
                </li>
                <?php } ?>
                <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='inventoryManagement' and allowed='requestItem' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>requestitem/?inventory-management-system/">
                    <span>Send new request</span>
                  </a>
                </li>
                <?php } ?>
                <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='inventoryManagement' and allowed='approverequest' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>approverequest/?inventory-management-system/">
                    <span>Approve Request</span>
                  </a>
                </li>
                <?php } ?>
                <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='inventoryManagement' and allowed='generateReport' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>inventoryreport/?inventory-management-system/">
                    <span> Generate Report</span>
                  </a>
                </li>
                <?php } ?>
              </ul>
            </li>
            <?php } ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Messages</span></a>
              <ul class="dropdown-menu">
                <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='Chat' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>chat/">
                    Live Chat
                  </a>
                </li>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>videochat/">
                    Video Chat
                  </a>
                </li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>compose/">Compose</a></li>
                <?php }else{?>
                <?php } ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>inbox/">Inbox</a></li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>sent/">Sent</a></li>
              </ul>
            </li>
            <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='schoolfiles' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="file"></i><span>School Files</span></a>
              <ul class="dropdown-menu">
                 <li>
                  <a class="nav-link" href="#"><i data-feather="gift"></i><span>School Award</span>
                  </a>
                </li>
              </ul>
            </li>
             <?php } ?>
             <li class="dropdown gs-sms-self-request-report-page">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="file"></i><span>My Request & Report</span></a>
              <ul class="dropdown-menu">
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>myannualrequest/?admin-annual-leave-request/"><span>My Leaving Request</span> </a> 
                </li>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>documents/?my-report-task-document/"><span>My Tasks & Report </span>
                  </a>
                </li>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>myselfevaluationpage/?self-evaluation-page/"><span>My self-evaluation question</span>
                  </a>
                </li>
                <?php  $userpStaffActivity=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffActivity' order by id ASC "); 
                if($userpStaffActivity->num_rows()>0) { ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>staffrequest/">Staffs Request</a> </li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>staffdailyactivity/?staffs-daily-activity/">Staff Tasks & Report</a> </li>
                <?php } ?>
              </ul>
            </li>
             <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='websitemanagment' order by id ASC "); if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="link"></i><span>Website Managment</span></a>
              <ul class="dropdown-menu">
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>photogallery/"><span>Gallery</span>
                  </a>
                </li>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>employment/"><span>Vacancy</span>
                  </a>
                </li>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>blog/">
                    <span>News</span>
                  </a>
                </li>
              </ul>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='taskspage' order by id ASC "); 
            if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="activity"></i>
                <span>General Tasks</span>
              </a>
            <ul class="dropdown-menu">
              <li>
              <a class="nav-link" href="#">
                <span>Competition</span>
              </a>
              </li>
              <li>
                <a class="nav-link" href="<?php echo base_url() ?>lineupschedule/">
                  <span>LineUp Schedule </span>
                </a>
              </li>
              <li>
                <a class="nav-link" href="<?php echo base_url() ?>timetable/">
                  <span>Generate TimeTable </span>
                </a>
              </li>
              <li>
              <a class="nav-link" href="#">
                <span>Certificate of the student </span>
              </a>
             </li>
             <li>
              <a class="nav-link" href="#">
                <span>Appointment Letter</span>
              </a>
             </li>
             
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <span>Exam Schedule</span></a>
              <ul class="dropdown-menu">
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>examscheduler/">
                    <span>Generate Schedule</span>
                  </a>
                </li>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>viewexamscheduler/">
                    <span>View Exam Schedule</span>
                  </a>
                </li>
              </ul>
            </li>
             
             <li>
              <a class="nav-link" href="#">
                <span>Sporting events </span>
              </a>
             </li>
             <li>
              <a class="nav-link" href="#">
                <span>Classroom schedules </span>
              </a>
             </li>
             <li>
              <a class="nav-link" href="#">
                <span>Field trip schedules</span>
              </a>
             </li>
             <li>
              <a class="nav-link" href="#">
                <span>Themes</span>
              </a>
             </li>
             
             </ul>
            </li>
            <?php } ?>
              
              <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='summerclass' order by id ASC "); if($usergroupPermission->num_rows()>0){ ?>
              <li>
                <a class="nav-link" href="<?php echo base_url(); ?>summerclass/?admin-summer-class-page/"><i data-feather="user-plus"></i><span>Summer class</span>
                </a>
              </li>
              <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='staffperformance' order by id ASC "); 
              if($usergroupPermission->num_rows()>0){ ?>
              <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="activity"></i><span>Staff Performance</span></a>
                <ul class="dropdown-menu">
                  <?php $usergroupP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='staffperformance' and allowed='manageperformance' order by id ASC "); 
                  if($usergroupP->num_rows()>0){ ?>
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>teachersperformance/?admin-teacher-performance-page/"><span>Staff Performance</span>
                    </a>
                  </li>
                  <?php } $usergroupPVS=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='staffperformance' and allowed='performancestatus' order by id ASC "); 
                  if($usergroupPVS->num_rows()>0){ ?>
                   <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>performancestatus/?performance-status-page/"><span>Performance Status</span>
                    </a>
                  </li>
                  <?php } $usergroupPV=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='staffperformance' and allowed='viewmyperformance' order by id ASC "); 
                  if($usergroupPV->num_rows()>0){ ?>
                   <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>myperformanceresult/?my-performance-result-page/"><span>My Performance</span>
                    </a>
                  </li>
                  <?php } ?>
                </ul>
              </li>
              <?php } if($_SESSION['usertype']==='superAdmin'){ ?>
              <li>
                <a class="nav-link" href="<?php echo base_url(); ?>setting/"><i data-feather="settings"></i><span>School Setting</span>
                </a>
              </li>
              <li>
                <a class="nav-link" href="<?php echo base_url(); ?>subscription/"><i data-feather="dollar-sign"></i><span>Subscription</span>
                </a>
              </li>
              <li>
                <a class="nav-link" href="<?php echo base_url(); ?>loggeduser/"><i data-feather="log-in"></i><span>Logs & Actions</span>
                </a>
              </li>
            <?php } ?>
            <?php $usergroupP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='createPolls' and tableName='HomepagePost' order by id ASC "); 
              if($usergroupP->num_rows()>0){ ?>
              <li>
                <a class="nav-link" href="<?php echo base_url(); ?>pollresult/?poll-result-summary/"><i data-feather="check-square"></i><span>Poll Summary</span>
                </a>
              </li>
            <?php } ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="moon"></i><span>Dark Mode</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="#">
                  <label class="selectgroup-item">
                    <input type="radio" id="changecolor" name="value" value="1" class="selectgroup-input-radio select-layout" checked>
                    <i data-feather="sun"></i>Light
                  </label></a>
                </li>
                <li><a class="nav-link" href="#">
                  <label class="selectgroup-item">
                    <input type="radio" id="changecolor" name="value" value="2" class="selectgroup-input-radio select-layout">
                   <i data-feather="moon"></i> Dark
                  </label></a>
                </li>
              </ul>
            </li>
          </ul>
          <hr>