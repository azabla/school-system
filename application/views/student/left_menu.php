         <div class="sidebar-brand">
          <a href="#"> <img alt="image" src="<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>" style="height:35px;width:auto;border-radius: 3em;" class="header-logo" /> 
              <span class="logo-name"> 
                
              </span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="student-notice-board">
              <a href="<?php echo base_url(); ?>noticeboard/?my-notice-board/" class="nav-link"><i data-feather="monitor"></i><span>Notice Board</span></a>
            </li>
            <li class="student-subject-board">
              <a class="nav-link" href="<?php echo base_url(); ?>myresult/"><i data-feather="book"></i><span>My Subject Result</span>
              </a>
            </li>
            <li class="student-attendance-board">
              <a href="<?php echo base_url(); ?>myattendance/" class="nav-link"><i data-feather="user-check"></i><span>My Attendance</span></a>
            </li>
            
            <li class="student-communication-board">
              <a class="nav-link" href="<?php echo base_url(); ?>mycommunicationbook/?student-communication-book/"><i data-feather="message-square"></i><span>Communication Book</span>
              </a>
            </li>
            <li class="dropdown student-request-page-board">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="shopping-bag"></i><span>My Request</span>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>myrequestbook/?student-request-book/"><span>Request Page</span> </a> 
                </li>
                <?php $usergroupPermis2="SELECT * from usergrouppermission where usergroup=?   and tableName=? and allowed=? order by id ASC "; 
                $usergroupPermission=$this->db->query($usergroupPermis2,array($_SESSION['usertype'],'libraryManagement','borrowBooks'));
                  if($usergroupPermission->num_rows()>0){ ?>
                <li>
                  <a class="nav-link" href="<?php echo base_url(); ?>Borrowedlibrarybooks/?library-management-system/"><span>Borrow Book</span> </a> 
                </li>
                <?php } ?>
              </ul>
            </li>
            <li class="student-lesson-board">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="book-open"></i>
                <span>Lesson & Worksheet</span>
              </a>
            <ul class="dropdown-menu"> 
            <!-- <li>
              <a class="nav-link" href="<?php echo base_url(); ?>mylesson/">
                <span>View Lesson/Worksheet</span>
              </a>
             </li>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>replayworksheet/">
                <span>Answer Worksheet</span>
              </a>
             </li>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>sentworksheet/">
                <span>Sent Worksheet</span>
              </a>
             </li> -->
             </ul>
            </li>
             <li class="student-onlin-exam-board">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="file-text"></i>
                <span>Online Exam</span>
              </a>
            <ul class="dropdown-menu">
            <li>
                <a class="nav-link" href="<?php echo base_url(); ?>myexam/">
                  <span>New Exam</span>
                </a> 
             </li>
             <li>
              <a class="nav-link" href="<?php echo base_url(); ?>myexamresult/">
                <span>Exam Result</span>
              </a>
             </li>
             </ul>
            </li>
                        
            <li class="student-payment-board">
              <a class="nav-link" href="<?php echo base_url(); ?>mypayment/"><i data-feather="dollar-sign"></i><span>Payment Report</span>
              </a>
            </li>
            <li class="student-elibrary-board">
              <a class="nav-link" href="<?php echo base_url(); ?>mylibrary/"><i data-feather="book-open"></i><span>E-Library</span>
              </a>
            </li>
            <!-- <li>
              <a class="nav-link" href="#"><i data-feather="award"></i><span>Competition</span>
              </a>
            </li> -->
            
            <li class="student-message-board">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Messages</span></a>
              <ul class="dropdown-menu">
              <?php $this->db->where('usergroup',$_SESSION['usertype']);
              $this->db->where('allowed','Chat');
              $usergroupPermission=$this->db->get('usergrouppermission');
              if($usergroupPermission->num_rows()>0){ ?>
                <!-- <li><a class="nav-link" href="">Live Chat</a></li> -->
                <li><a class="nav-link" href="<?php echo base_url(); ?>newcompose/">Compose</a></li>
              <?php } else{ ?>
              <?php } ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>myinbox/">Inbox</a></li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>isent/">Sent</a></li>
              
              </ul>
            </li>
            
            <!-- <li>
              <a class="nav-link" href="<?php echo base_url(); ?>myschoolgallery/"><i data-feather="image"></i><span>Gallery</span>
              </a>
            </li> -->
            <!-- <li>
              <a class="nav-link" href="#"><i data-feather="file"></i><span>My Documents</span>
              </a>
            </li> -->
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