         <div class="sidebar-brand">
          <a href="#"> <img alt="image" src="<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>" style="height:35px;width:35px;border-radius: 3em;" class="header-logo" /> 
              <!-- <span class="logo-name"> 
                <?php foreach($schools as $school) {
                  echo $school->name;}
                  ?>
              </span> -->
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="dropdown teacher-notice-board">
              <a href="<?php echo base_url() ?>mynoticeboard/?notice-board-page/" class="nav-link"><i data-feather="monitor"></i><span>Notice Board</span></a>
            </li>
            <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' order by id ASC ");  
            if($usergroupPermission->num_rows()>0){  ?>
              <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                  <i data-feather="user-plus"></i>
                  <span>My Student(s)</span>
                </a>
                <ul class="dropdown-menu">
                  <?php $uperStuPl=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentPl' order by id ASC ");  if($uperStuPl->num_rows()>0){ ?>
                    <li><a class="nav-link" href="<?php echo base_url() ?>studentplacement/?student-section-placement-page-director/">Student Placement</a>
                  </li>
                  <?php } $uperStuView=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Student' and allowed='StudentVE' order by id ASC ");
                  if($uperStuView->num_rows()>0){  ?>
                      <li><a class="nav-link" href="<?php echo base_url(); ?>mystudent/?my-student-page">View Student</a> </li>
                    <li>
                      <a class="nav-link" href="<?php echo base_url(); ?>mystudentsummaryrecordreport/"><span>Summary Report</span>
                      </a>
                    </li>
                  <?php } ?>
                </ul>
              </li>
              <?php } ?>

              <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' order by id ASC ");  
            if($usergroupPermission->num_rows()>0){ ?>
              <li class="dropdown">
                 <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="users"></i><span>Staffs & Placement</span>
                  </a>
                <ul class="dropdown-menu">
                  <?php $userpStaffTP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffPl' order by id ASC "); if($userpStaffTP->num_rows()>0){ ?>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>mystaffplacement/">Teacher Placement</a> </li>
                  <?php } $userpStaffHrP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='hoomeroomPl' order by id ASC "); if($userpStaffHrP->num_rows()>0){ ?>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>myhomeroomplacement/">Homeroom Placement</a> </li>
                  <?php } $userpStaffPhone=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffPhone' order by id ASC "); if($userpStaffPhone->num_rows()>0) { ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>mystaffphone/">Staffs phone List</a> </li>
                  <?php } ?>
                </ul>
              </li>
              <?php } ?>
              <?php $usergroupPermissions=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Staff' and allowed='staffActivity' order by id ASC "); if($usergroupPermissions->num_rows()>0){ ?>
              <!-- <li>
              <a class="nav-link" href="<?php echo base_url(); ?>mystaffsreport/"><i data-feather="book-open"></i><span>Staffs Task</span>
              </a>
              </li> -->
              <?php } ?>
              <li class="dropdown">
               <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="shopping-bag"></i><span>My Request & Report</span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>annualrequest/?teacher-annual-leave-request/"><span>Annual Leaving</span> </a> 
                  </li>
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>myselfevaluationquestion/?self-evaluation-page/"><span>My self-evaluation question</span>
                    </a>
                  </li>
                  <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='inventoryManagement' and allowed='requestItem' order by id ASC "); 
                    if($usergroupPermission->num_rows()>0){ ?>
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>myitemrequest/?teacher-item-request/"><span>Item Request</span> </a> 
                  </li>
                  <?php } ?>
                  <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."'  and tableName='libraryManagement' and allowed='borrowBooks' order by id ASC "); 
                    if($usergroupPermission->num_rows()>0){ ?>
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>myborrowedbooks/?library-management-system/"><span>Borrow Book</span> </a> 
                  </li>
                  <?php } ?>
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>document/?my-task-report-page/"><span>My Tasks & Report</span>
                    </a>
                  </li>
                </ul>
              </li>
            <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Subject' order by id ASC "); 
            if($usergroupPermission->num_rows()>0){ ?>
              <li class="dropdown">
               <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="book"></i><span>Subject(s) Mgmt</span>
                </a>
                <ul class="dropdown-menu">                  
                  <?php $usergroupKGReport=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Subject' and allowed='KgSubjectReport' order by id ASC "); 
                  if($usergroupKGReport->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>mykgsubjectlistreport/?kg-subject-form-page/">የምልከታ መከታታያ ሪፖርት</a>
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
                <li><a class="nav-link" href="<?php echo base_url(); ?>mystaffidcard/">Staff ID</a>
                </li>
                 <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='IDCard' and allowed='StudentIDCard' order by id ASC ");if($usergroupPermission->num_rows()>0){ ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>myidcard/">Student ID</a>
                </li>
                <?php } ?>
              </ul>
            </li>
            <?php } ?>
            <?php $chkPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' order by id ASC "); 
            if($chkPermission->num_rows()>0){ ?>
              <li class="dropdown">
               <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="book"></i><span>Attendance Mgmt</span>
                </a>
                <ul class="dropdown-menu">
                  <?php  $userPerStuAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='studentAttendance' order by id ASC ");  
                  if($userPerStuAtt->num_rows()>0) { ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>mystudentattendance/"><span>Student Attendance</span></a> </li>
                  <?php } $aspAttendance=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='studentASP' order by id ASC ");  if($aspAttendance->num_rows()>0) { ?>
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>studentaspattendance/?admin-student-asp-attendance-page-director">
                      <span>ASP Attendance</span>
                    </a>
                  </li>
                  <?php } $userPerStaAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='staffAttendance' order by id ASC "); if($userPerStaAtt->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>mystaffattendance/">
                    <span>Staff Attendance</span></a> 
                  </li>
                  <?php } $userPerStaEvaAtt=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='Attendance' and allowed='staffEvaluationAttendance' order by id ASC ");  
                  if($userPerStaEvaAtt->num_rows()>0) { ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>mystaffevaluationattendance/?staff-evaluation-attendance/">
                    <span>Supervision Attendance</span></a> 
                  </li>
                  <?php } ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>mynotification/?my-attendance/">
                    <span>My Attendance</span></a> 
                  </li>
                </ul>
              </li>
              <?php } ?>
              
              <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='communicationbook' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
                <li class="dropdown">
               <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="message-square"></i><span>Communication Book</span>
                </a>
                <ul class="dropdown-menu">
                  <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='CommunicationBook' and allowed='sendcommunicationbook' order by id ASC ");
                  if($usergroupPermission->num_rows()>0){ ?>
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>communicationbookteacher/?teacher-communication-book/"><span>New Comm. Book</span> </a> 
                  </li>
                  <?php } $usergroupPermissionApprove=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='CommunicationBook' and allowed='approvecommunicationbook' order by id ASC ");
                    if($usergroupPermissionApprove->num_rows()>0){ ?>
                  <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>approvecommunicationbook/?teacher-item-request/"><span>Approve Comm. Book</span> </a> 
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
                <a class="nav-link" href="<?php echo base_url(); ?>mylessonplan/?add-lesson-plan-page/">
                  <span>Add Lesson Plan</span>
                </a>
              </li>
            <?php } $upViewLplan=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='lessonplan' and allowed='viewlessonplan' order by id ASC ");  
            if($upViewLplan->num_rows()>0){?>
              <li>
                <a class="nav-link" href="<?php echo base_url(); ?>viewmylessonplan/?view-lesson-plan-page/">
                  <span>View Lesson Plan</span>
                </a>
              </li>
            <?php }?>
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
              <a class="nav-link" href="<?php echo base_url(); ?>addstudentlesson/">
                <i data-feather="book-open"></i>
                <span>Add HW/Worksheet</span>
              </a>
             </li>
            <li>
              <a class="nav-link" href="<?php echo base_url(); ?>viewstudentlesson/"><i data-feather="book-open"></i>
                <span>View HW/Worksheet</span>
              </a>
             </li>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>viewmyansweredworksheet/"><i data-feather="book-open"></i>
                <span>Answered Worksheet</span>
              </a>
             </li>
             </ul>
            </li>
           <?php } ?>
           <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='Evaluation' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li>
              <a class="nav-link" href="<?php echo base_url(); ?>studentevaluationweight/?student-evaluation-weight/"><i data-feather="briefcase"></i><span>Student Evaluation</span>
              </a>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='studentexam' order by id ASC ");  if($usergroupPermission->num_rows()>0){?>
             <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="file-text"></i>
                <span>Exam Mgmt</span>
              </a>
            <ul class="dropdown-menu">
            <li>
              <a class="nav-link" href="#">
                <span>View Exam</span>
              </a>
             </li>
             </ul>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' order by id ASC ");  if($usergroupPermission->num_rows()>0){?>
            <li class="dropdown gs-sms-mark-management-teacher-page">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="layout"></i><span>Mark Mgmt</span>
              </a>
            <ul class="dropdown-menu">
              <?php $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='addstudentmark' order by id ASC ");  if($uaddMark->num_rows()>0){ ?>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>addstudentresult/">
                <span>Add New result</span>
              </a>
             </li>
             <?php } $markformat=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='studentmarkformat' order by id ASC ");  if($markformat->num_rows()>0){ ?>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>markformat/">
                <span>Prepare Mark Format</span>
              </a>
             </li>
            <?php } $markformat=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='approvemark' order by id ASC ");  if($markformat->num_rows()>0){ ?>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>approvestudentmark/">
                <span>Approve Mark</span>
              </a>
              </li>
            <?php } ?>
            <?php $markformat=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='addstudentmark' order by id ASC ");  if($markformat->num_rows()>0){ ?>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>importmark/">
                <span>Import excel mark </span>
              </a>
              </li>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>editstudentmark/">
                <span>Edit/Delete Mark</span>
              </a>
              </li>
              <?php } $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='viewstudentmark' order by id ASC ");  if($uaddMark->num_rows()>0){ ?>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>studentresult/">
                <span>View Result</span>
              </a>
              </li>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>mymarkprogress/">
                <span>Mark Progress</span>
              </a>
              </li>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>myngmark/">
                <span>NG Mark Result</span>
              </a>
              </li>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>studentmarkanalysis/">
                <span>Mark Analysis</span>
              </a>
              </li>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>mystudentassesmentrank/">
                <span>Assesment Rank</span>
              </a>
              </li>
              <?php } ?>
              <?php $userpStaffAI=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' order by id ASC "); 
              if($userpStaffAI->num_rows()>0){ ?>
                <li>
                <a class="nav-link" href="<?php echo base_url(); ?>lockunlockmystudentmark/?lock-unlock-page">
                  <span>Lock/Unlock Mark</span>
                </a>
              </li>
              <?php } ?>
             </ul>
            </li>
            <?php } ?>
            <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' order by id ASC "); 
            if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="grid"></i><span>Card & Roster</span></a>
              <ul class="dropdown-menu">
                <?php $rpPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='reportcard' order by id ASC "); 
                if($rpPermission->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>studentreportcard/">Grade Report Card</a></li>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>studentkgreportcard/">KG Report Card</a></li>
                <?php } $roPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='roster' order by id ASC ");
                if($roPermission->num_rows()>0){ ?>
                  <li><a class="nav-link" href="<?php echo base_url(); ?>studentroster/">Student Roster</a></li>
                <?php } $trPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='transcript' order by id ASC "); 
                  if($trPermission->num_rows()>0){ ?>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>studentranscript/">Student Transcript</a></li>
                  <?php }?>
              </ul>
            </li>
            <?php } ?>
            <?php $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='rankReport' order by id ASC ");
            if($uaddMark->num_rows()>0){ ?>
            <li>
              <a class="nav-link" href="<?php echo base_url(); ?>studentrankreport/"><i data-feather="award"></i><span>Rank Report</span>
              </a>
            </li>
           <?php } ?>
            <?php $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentCard' and allowed='Statistics' order by id ASC ");  if($uaddMark->num_rows()>0){ ?>
            <li>
              <a class="nav-link" href="<?php echo base_url(); ?>resultstatistics/"><i data-feather="activity"></i><span>Result Statistics</span>
              </a>
            </li>
          <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' order by id ASC ");  
          if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
              <i data-feather="clipboard"></i><span>Basic Skill & Conduct</span>
              </a>
            <ul class="dropdown-menu">
              <?php $usergroupADDREmove=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='addRemoveBS' order by id ASC ");  
              if($usergroupADDREmove->num_rows()>0){  ?>
              <li>
              <a class="nav-link" href="<?php echo base_url(); ?>basicskillteacher/?basic-skill-page"><span>Add BS Name & Type</span>
              </a>
             </li>
           <?php } $usergroupExportBS=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='exportBSFormat' order by id ASC ");  
              if($usergroupExportBS->num_rows()>0){ ?>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>bsformat/"><span>Export BS Excel format</span>
              </a>
             </li>
             <li>
             <?php } $usergroupExportBS=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='importBSFormat' order by id ASC ");  
              if($usergroupExportBS->num_rows()>0){ ?>
                <li>
              <a class="nav-link" href="<?php echo base_url(); ?>importbskill/"><span>Import Basic Skill Data</span>
              </a>
             </li>
            <?php } $usergroupViewBS=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='editStudentBSDATA' order by id ASC ");  
              if($usergroupViewBS->num_rows()>0){ ?>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>viewbs/"><span>View Student BS</span>
              </a>
             </li>
             <?php } ?>
             </ul>
            </li>
            <?php } ?>

            <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='ImportExport' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="arrow-down-circle"></i><span>Import & Export</span></a>
              <ul class="dropdown-menu">
                <?php $exportFile=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='ImportExport' and allowed='exportFile' order by id ASC ");  if($exportFile->num_rows()>0){ ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>exportformat/">Export</a></li>
              <?php } $importFile=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='ImportExport' and allowed='importFile' order by id ASC ");  if($importFile->num_rows()>0){?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>importfile/">Import</a></li>
              <?php } ?>
              </ul>
            </li>
            <?php } $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='staffperformance' order by id ASC "); 
              if($usergroupPermission->num_rows()>0){ ?>
              <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="activity"></i><span>Staff Performance</span></a>
                <ul class="dropdown-menu">
                <?php $usergroupP=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='staffperformance' and allowed='manageperformance' order by id ASC "); if($usergroupP->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>teacherperformance/?director-teacher-performance-page/"><span>Teachers Performance</span>
                  </a>
                </li>
                <?php } $usergroupPVS=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='staffperformance' and allowed='performancestatus' order by id ASC "); 
                  if($usergroupPVS->num_rows()>0){ ?>
                   <li>
                    <a class="nav-link" href="<?php echo base_url(); ?>myperformancestatus/?performance-status-page/"><span>Performance Status</span>
                    </a>
                  </li>
                <?php } $usergroupPV=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='staffperformance' and allowed='viewmyperformance' order by id ASC "); if($usergroupPV->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>myperformance/?my-performance-page/">
                    <span>My Performance</span>
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
                <li><a class="nav-link" href="<?php echo base_url(); ?>mchat/">Live Chat</a></li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>messagecompose/">Compose</a></li>
              <?php } else{?>
              <?php } ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>messageinbox/">Inbox</a></li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>messagesent/">Sent</a></li>
              </ul>
            </li>
            <!-- <li>
              <a class="nav-link" href="<?php echo base_url(); ?>schoolgallery/"><i data-feather="image"></i><span>Gallery</span>
              </a>
            </li> -->
            <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='summerclass' order by id ASC "); if($usergroupPermission->num_rows()>0){ ?>
            <li>
              <a class="nav-link" href="<?php echo base_url(); ?>mysummerclass/"><i data-feather="user-plus"></i><span>Summer Class</span>
              </a>
            </li>
          <?php }?>
           <!-- <li class="dropdown">
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
            </li> -->
          </ul>
          <hr>