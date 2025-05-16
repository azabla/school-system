         <div class="sidebar-brand">
          <a href="#"> <img alt="image" src="<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>" style="height:35px;width:35px;border-radius: 3em;" class="header-logo" /> 
              <span class="logo-name"> 
                <?php foreach($schools as $school) {
                  echo $school->name;}
                  ?>
              </span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="dropdown activee">
              <a href="<?php echo base_url(); ?>home/" class="nav-link"><i data-feather="home"></i><span>Home</span></a>
            </li>
            <li><a class="nav-link" href="<?php echo base_url(); ?>mystaffs/">
              <i data-feather="users"></i>Staffs List</a>
            </li>
            <li><a class="nav-link" href="#">
              <i data-feather="users"></i>Student List</a>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="user-check"></i>
                <span>Attendance</span>
              </a>
            <ul class="dropdown-menu">
              <li>
              <a class="nav-link" href="#"><i data-feather="user-check"></i>
                <span>Attendance</span>
              </a>
             </li>
             </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="dollar-sign"></i><span>Payroll</span></a>
              <ul class="dropdown-menu">              
                <li><a class="nav-link" href="<?php echo base_url(); ?>mystaffpayroll/">Staff Payroll</a></li>
              </ul>
            </li>
            
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Messages</span></a>
              <ul class="dropdown-menu">
                <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='Chat' order by id ASC "); 
                if($usergroupPermission->num_rows()>0){ ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>newstaffcompose/">Compose</a></li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>staffchat/">Live Chat</a></li>
                <?php } else{ ?>
                <?php } ?>
                <li><a class="nav-link" href="<?php echo base_url(); ?>mystaffinbox/">Inbox</a></li>
                <li><a class="nav-link" href="<?php echo base_url(); ?>mystaffsent/">Sent</a></li>
                
              </ul>
            </li>
          
            <li>
              <a class="nav-link" href="<?php echo base_url(); ?>mystaffgallery/"><i data-feather="image"></i><span>Gallery</span>
              </a>
            </li>
            <li>
              <a class="nav-link" href="<?php echo base_url(); ?>staffdocuments/"><i data-feather="file"></i><span>My Documents</span>
              </a>
            </li>
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