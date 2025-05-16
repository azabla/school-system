<nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#"> <img alt="image" src="<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>" style="height:35px;width:auto;border-radius: 3em;" class="header-logo" />  
            </a>
            </li>
            <li>
              <a href="<?php echo base_url(); ?>home/?admin-home-page/" class="nav-link nav-link-lg">
                <i data-feather="home"></i>
              </a>
            </li>
            <li>
              <form class="form-inline mr-auto pull-right">
                <div class="search-element">
                  <?php if($_SESSION['usertype']===trim('superAdmin')){ 
                    $query_name = $this->db->query("select * from school");
                    $row_name = $query_name->row();
                    $school_name=$row_name->name;
                    $schoolID=$row_name->id;
                    $queryCheck=$this->db->query("select * from subscription_detail where user_id ='$schoolID'");
                    if($queryCheck->num_rows()>0){
                      date_default_timezone_set("Africa/Addis_Ababa");
                      $dtz = new DateTimeZone('etc/GMT-10');
                      $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
                      $date = gmdate("Y-m-d h:i A", $dt->format('U'));
                      $rowData=$queryCheck->row();
                      $endDate=$rowData->sub_until;
                      $date = strtotime($endDate);
                      $remaining = $date - strtotime(date('Y-m-d h:i A'));
                      $days_remaining = floor($remaining / 86400);
                      $hours_remaining = floor(($remaining % 86400) / 3600);
                      if($days_remaining <='30'){
                        if($days_remaining <='0' && $hours_remaining<='0'){
                          header("Location:".base_url()."login/");
                        }else{
                          echo "<small><span class='text-danger'><h5>
                          <i data-feather='alert-triangle'></i> 
                          $days_remaining Days and $hours_remaining Hours left <a href='".base_url()."subscription/'>Pay now</a></h5></span></small>";
                        }
                      }else{
                        echo '<small class="badge badge-info">Software Level: Premium</small>';
                      }
                    }else{
                      echo '<small class="badge badge-info">Software Level: Premium</small>';
                    }
                  }?> 
                </div>
              </form>
            </li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="seen nav-link nav-link-lg message-togglee">
              <i data-feather="mail"></i>
              <span class="badge headerBadge1 count-new-inbox"></span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
              <div class="dropdown-header">
                Messages
              </div>
              <div class="dropdown-list-content dropdown-list-message inbox-show">
              </div>
              <div class="dropdown-footer text-center">
                <a href="<?php echo base_url();?>inbox/">View All <i class="fas fa-chevron-right"></i></a>
              </div>
            </div>
          </li>
         
    <li class="dropdown dropdown-list-toggle">
      <a href="#" data-toggle="dropdown" class="seen_noti nav-link nav-link-lg message-togglee">
        <i data-feather="bell"></i>
        <span class="badge headerBadge1 count-new-notification"></span>
      </a>
      <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
              <div class="dropdown-header">
                Notifications
                <div class="float-right"> </div>
              </div>
          <div class="table-responsive" style="height:40vh;">
            <div class="dropdown-list-icons notification-show" >
              <a href="#" class="dropdown-item dropdown-item-unread">

              </a> 
            </div>
          </div>
      </div>
    </li>
     <?php foreach($sessionuser as $sessionusers){?>
          <li class="dropdown"><a href="#" data-toggle="dropdown"
              class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="<?php echo base_url(); ?>/profile/<?php echo $sessionusers->profile;?>"
                class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
              <div class="dropdown-title">Hello <?php echo $_SESSION['username']; ?>
              </div>
              <a href="<?php echo base_url(); ?>myprofile/" class="dropdown-item has-icon"> <i class="far fa-user"></i> Profile
              </a> 
              <div class="dropdown-divider"></div>
              <a href="<?php echo base_url() ?>mysessions/" class="dropdown-item has-icon text-alert"> <i class="fas fa-sign-in-alt"></i>
                My Session
              </a>
              <div class="dropdown-divider"></div>
              <a href="<?php echo base_url(); ?>logout/" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                Logout
              </a>
            </div>
          </li>
        <?php }?>
        </ul>
      </nav>


<!-- New Student registration starts -->
<div class="modal fade" id="newstudent" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="msg" id="msg">New student registration</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <div class="search-element">
              <div class="row">
                <div class="form-group col-lg-4 col-6">
                  <label for="fname">First Name</label>
                  <input id="fname" type="text" class="form-control" required="required" name="fname"autofocus>
                  <span class="text-danger"> 
                    <?php echo form_error('frist_name'); ?>
                  </span>
                </div>
                <div class="form-group col-lg-4 col-6">
                  <label for="lname">Father Name</label>
                  <input id="lname" type="text" class="form-control" required="required" name="lname">
                  <span class="text-danger">
                    <?php echo form_error('last_name'); ?>
                  </span>
                </div>
                <div class="form-group col-lg-4 col-6">
                  <label for="gf_name">GrandFather Name</label>
                  <input id="gfname" type="text" class="form-control" required="required" name="gfname">
                  <span class="text-danger">
                    <?php echo form_error('gf_name'); ?>
                  </span>
                </div>
              
                <div class="form-group col-lg-4 col-6">
                  <label for="gender">Gender</label><br>
                  <input type="radio" id="gender" name="gender" value="Male">
                  <label>Male</label>&nbsp &nbsp
                  <input type="radio" id="gender" name="gender" value="Female">
                  <label>Female</label>
                  <span class="text-danger">
                    <?php echo form_error('gender'); ?>
                  </span>
                </div>

                <div class="form-group col-lg-4 col-6">
                  <div class="form-group">
                    <label for="usertype">User Type</label>
                    <select class="form-control selectric" name="usertype" id="usertype" required="required">
                      <option> Student</option>
                    </select>
                    <span class="text-danger"> 
                      <?php echo form_error('usertype'); ?>
                    </span>
                  </div>
                </div>
                <div class="form-group col-lg-4 col-6">
                  <div class="form-group">
                    <label for="Username">Mother Mobile</label>
                    <input id="mobile" required="required" type="text" class="form-control" name="username">
                    <span class="text-danger">
                      <?php echo form_error('username'); ?>
                    </span>
                  </div>
                </div>
              
                <div class="form-group col-lg-4 col-6">
                  <div class="form-group">
                    <label for="Mobile">Father Mobile</label>
                    <input id="fathermobile" required="required" type="text" class="form-control" name="mobile">
                    <span class="text-danger"> 
                      <?php echo form_error('mobile'); ?>
                    </span>
                  </div>
                </div>
                <div class="form-group col-lg-4 col-6">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" required="required" type="email" class="form-control" name="email">
                    <span class="text-danger">
                      <?php echo form_error('email'); ?>
                    </span>
                  </div>
                </div>
                <div class="form-group col-lg-4 col-6">
                  <div class="form-group">
                    <label for="Profile">Profile Photo(<span class="text-danger">opt</span>)</label>
                    <input id="profile" type="file" class="form-control" name="profile">
                    <span class="text-danger">
                      <?php echo form_error('profile'); ?>
                    </span>
                  </div>
                </div>
                <div class="form-group col-lg-4 col-6">
                  <div class="form-group">
                    <label for="grade">Grade </label>
                    <select class="form-control selectric" required="required"
                    name="grade" id="grade">
                      <option> </option>
                      <option> KG1</option>
                      <option> KG2</option>
                      <option> KG3</option>
                      <option> 1</option>
                      <option> 2</option>
                      <option> 3</option>
                      <option> 4</option>
                      <option> 5</option>
                      <option> 6</option>
                      <option> 7</option>
                      <option> 8</option>
                      <option> 9</option>
                      <option> 10</option>
                      <option> 11</option>
                      <option> 12</option>
                      </select>
                      <span class="text-danger">
                        <?php echo form_error('grade'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-lg-4 col-6">
                    <label for="password" class="d-block">Section</label>
                    <select class="form-control selectric" required="required"
                     name="sec" id="sec">
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
                      <span  class="dropdown-item has-icon text-danger">
                        <?php echo form_error('moname'); ?>
                      </span>
                    </div>
                    <div class="form-group col-lg-4 col-6">
                      <label for="dob" class="d-block">Date of Birth</label>
                      <input id="dob" required="required" type="date" class="form-control" data-indicator="pwindicator" name="dob" id="dob">
                      <span  class="dropdown-item has-icon text-danger"> 
                        <?php echo form_error('dob'); ?>
                      </span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-4 col-4">
                      <div class="form-group">
                        <label for="city">City</label>
                        <select class="form-control selectric" name="city" id="city">
                          <option> </option>
                          <option> Addis Ababa</option>
                          <option> Adama</option>
                          <option> Mekelle</option>
                          <option> Bahir Dar</option>
                          <option> Hawassa</option>
                          <option> Jimma</option>
                          <option> Gonder</option>
                          <option> Harrer</option>
                          <option> Dilla</option>
                          <option> Axum</option>
                          <option> Dire Dawa</option>
                          </select>
                          <span class="text-danger">
                            <?php echo form_error('city'); ?>
                          </span>
                        </div>
                      </div>
                      <div class="form-group col-lg-4 col-4">
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
                            </select>
                            <span class="text-danger"> 
                            <?php echo form_error('subcity'); ?>
                            </span>
                          </div>
                        </div>
                        <div class="form-group col-lg-4 col-4">
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
                            </select>
                            <span class="text-danger"> 
                            <?php echo form_error('woreda'); ?>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-6 col-6">
                          <label for="password" class="d-block">Password</label>
                          <input id="password" required="required" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password">
                          <span  class="text-danger">
                            <?php echo form_error('password'); ?>
                          </span>
                        </div>
                        <div class="form-group col-lg-6 col-6">
                          <label for="password2" class="d-block">Confirm Password</label>
                          <input id="password2" required="required" type="password" class="form-control" name="password-confirm">
                          <span class="text-danger">
                            <?php echo form_error('password-confirm'); ?>
                          </span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-4 col-6">
                          <label for="password2" class="d-block">Student ID</label>
                          <input id="stuid" required="required" type="text" class="form-control" name="stuid">
                          <span class="text-danger"> 
                            <?php echo form_error('stuid'); ?>
                          </span>
                        </div>
                        <div class="form-group col-lg-4 col-6">
                          <label for="password2" class="d-block">School Branch</label>
                          <select class="form-control selectric" required="required" name="branch"  id="branch">
                            <?php foreach($branch as $branchs){ ?>
                              <option><?php echo $branchs->name ?></option>
                            <?php } ?>
                          </select>
                          <span class="text-danger"> 
                            <?php echo form_error('password-confirm'); ?>
                          </span>
                        </div>
                        <div class="form-group col-lg-4 col-6">
                          <div class="form-group">
                            <label for="ac">Academic year</label>
                            <select class=" form-control selectric"
                            required="required" name="academicyear" id="academicyear">
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
                      <div class="form-group col-lg-12 col-6">
                        <button class="btn btn-primary btn-block" name="savenewstudent" id="savenewstudent">
                          <i class="fas fa-save"></i> Save
                        </button>
                      </div>
                    </div>
                    
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>