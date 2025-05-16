<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="" >
  <meta name="author" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php foreach($schools as $school) {
        echo $school->name;} ?></title>
  <link href="<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>" rel="icon">
  <link href="<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>" rel="apple-touch-icon">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assetslogin/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assetslogin/css/bootstrap-theme.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assetslogin/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assetslogin/css/style.css">

</head>

<body>
<div id="fb-root"></div>

<header> 
  <section id="ccr-site-title" class="container">
    <div class="col-md-6 col-8">
      <div class="site-logo">
          <img src="<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>" alt="Logo" style="height: 50px;" />
          <a href="#" class="navbar-brand">
            <strong><?php echo $school->name; ?></strong>
          </a>
      </div> 
    </div>
    <div class="col-md-3 col-2">
      <a href="<?php echo base_url(); ?>register/" class =""><button class="btn" id=""> Create Account </button></a>
        <?php $Appname='Diamond Academy.apk'; ?>
        <a href="<?php echo base_url().'Login/download/'.$Appname; ?>" class="get-started-btn">
        <button class="btn" id="">Download App</button></a>
    </div>
    <div class="col-md-3 col-2">
       <a href="<?php echo base_url(); ?>loginpage/" class =""><button class="btn pull-right btn-block" id="">Login</button></a>
    </div>
  </section>
  <?php $querySelect=$this->db->query("select * from studentregistrationstatus where registration_status='1' ");?>
  <section id="ccr-nav-main" class="container">
    <nav class="main-menu">
      <div class="">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".ccr-nav-main">
            <h4></h4>Menu <i class="fa fa-bars"></i></h4>
          </button>
        </div>
        <!--  -->
        <div class="collapse navbar-collapse ccr-nav-main">
          <ul class="nav navbar-nav">
            <li class="active"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li><a href="<?php echo base_url(); ?>about/">About</a></li>
            <li><a href="<?php echo base_url(); ?>schoolsgallery/">Gallery</a></li>
            <li><a href="<?php echo base_url(); ?>teacher/">Our Teachers</a></li>
            <li><a href="<?php echo base_url(); ?>vacancy/">Vacancy</a></li>
            <li><a href="<?php echo base_url(); ?>schoolblogs/">Blogs</a></li>
            <li><a href="#">Events</a></li>
            <li><a href="<?php echo base_url(); ?>contact/">Contact</a></li>
          </ul> 
        </div>
      </div>  
    </nav>
  </section>
</header>

<section id="ccr-main-section">
  <div class="container">
    <section id="ccr-left-section" class="col-md-6">
      <section id="ccr-contact-form">
         
        <div class="ccr-gallery-ttile">
          <span></span> 
          <p><strong>Registration Page</strong></p>
        </div><br>
        <?php if(isset($_SESSION['success'])){ ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; ?>
        </div>
        <?php  }
        if(isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; ?>  
        </div>
        <?php } ?>
        <?php
        if($querySelect->num_rows()>0){ ?>
            <div class="alert alert-success">
              Note that:-This form is only for <b>new students</b>. Please fill in the form below in order to submit your personal information to the school registrar office so that you will be registered as legal guardians/Parents of the student(s). But for those who are <b>returning students</b>, you should login to the system and TAB <u>"Register now"</u> button other wise you will be Listed under <b>"Leaved from our school"</b>.
            </div>
            <?php echo form_open_multipart('register');?>
          <div class="row">
            <div class="form-group col-md-4">
                <label for="frist_name">First Name 
                    <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small> </label>
                
                <input id="frist_name" type="text" class="form-control" required="required" name="frist_name"autofocus>
                <span class="text-danger"> 
                 <?php echo form_error('frist_name'); ?>
                </span>
            </div>
            <div class="form-group col-md-4">
                <label for="last_name">Father Name
                     <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small>
                </label>
                <input id="last_name" type="text" class="form-control" required="required" name="last_name">
                <span class="text-danger"> 
                 <?php echo form_error('last_name'); ?>
                </span>
            </div>
            <div class="form-group col-md-4">
                <label for="gf_name">GrandFather Name
                     <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small>
                </label>
                <input id="gf_name" type="text" class="form-control" required="required" name="gf_name">
                <span class="text-danger"> 
                 <?php echo form_error('gf_name'); ?>
                </span>
            </div>
            <div class="form-group col-md-4">
                <label for="Username">Username
                     <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small>
                </label>
                <input id="username" required="required" type="text" class="form-control" name="username">
                <span class="text-danger"> 
                <?php echo form_error('username'); ?>
                </span>
            </div>
            <div class="form-group col-md-4">
                <label for="usertype">User Type
                     <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small>
                </label>
                <select class="form-control selectric" name="usertype" id="usertype" required="required">
                <option> </option>
                <?php foreach($usergroup->result() as $usergroups){ ?>
                <option> <?php echo $usergroups->uname; ?></option>
                <?php } ?>
                </select>
                <span class="text-danger"> 
                <?php echo form_error('usertype'); ?>
                </span>
            </div>
            <div class="form-group col-md-4">
               <label for="gender">Gender
                 <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small>
               </label><br>
                <input type="radio" name="gender" value="Male">
                    <label>Male</label>
                <input type="radio" name="gender" value="Female">
                    <label>Female</label>
                <span class="text-danger"> 
                    <?php echo form_error('gender'); ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label for="Mobile">Mobile
                     <!-- <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small> -->
                </label>
                <input id="mobile" type="text" class="form-control" name="mobile">
                <span class="text-danger"> 
                <?php echo form_error('mobile'); ?>
                </span>
            </div>
            <div class="form-group col-md-4">
                <label for="email">Email
                     <!-- <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small> -->
                </label>
                <input id="emaile" type="email" class="form-control" name="email">
                <span class="text-danger"> 
                <?php echo form_error('email'); ?>
                </span>
            </div>
            <div class="form-group col-md-4">
                <label for="Profile">Profile Photo</label>
                <input id="profile" type="file" class="form-control" name="profile">
                <span class="text-danger"> 
                <?php echo form_error('profile'); ?>
                </span>
            </div>
            <div class="form-group col-lg-4">
                <div class="form-group">
                    <label for="grade">Grade
                        <span class="text-danger">(Only for students)</span></label>
                        <?php if($gradeGroup->num_rows()>0){ ?>
                    <select class="form-control selectric" name="grade" id="grade">
                    <option> </option>
                    <?php foreach($gradeGroup->result() as $usergroup){ ?>
                        <option> <?php echo $usergroup->grade; ?></option>
                    <?php } ?>
                    </select>
                   <?php } else{ ?>
                    <input id="grade" type="text" class="form-control" name="grade">
                   <?php }?>                  
                    <span class="text-danger"> 
                    <?php echo form_error('grade'); ?>
                    </span>
                </div>
            </div>
            <div class="form-group col-lg-4">
                <label for="dob" class="d-block">Date of Birth
                     <!-- <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small> -->
                </label>
                <input id="dob" type="date" class="form-control" data-indicator="pwindicator" name="dob">
                <span  class="dropdown-item has-icon text-danger"> 
                <?php echo form_error('dob'); ?>
                </span>
            </div>
            <div class="form-group col-lg-4">
                <label for="password" class="d-block">Mother Name
                    <!--  <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small> -->
                </label>
                <input id="moname" type="text" class="form-control" name="moname">
                <span  class="dropdown-item has-icon text-danger"> 
                <?php echo form_error('moname'); ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-4">
               <div class="form-group">
                    <label for="city">City
                         <!-- <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small> -->
                    </label>
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
                    <option> Dukem</option>
                    <option> D/Zeit</option>
                    </select>
                    <span class="text-danger"> 
                    <?php echo form_error('city'); ?>
                    </span>
                </div>
            </div>
            <div class="form-group col-lg-4">
                <div class="form-group">
                    <label for="Sub_city">Sub City
                         <!-- <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small> -->
                    </label>
                    <select class="form-control selectric" name="subcity" id="subcity">
                    <option> </option>
                    <option> Addis Ketema</option>
                    <option> Arada</option>
                    <option> Bole</option>
                    <option> Akaki Kality</option>
                    <option> Ns.Lafto</option>
                    <option> Gullele</option>
                    <option> Yeka</option>
                    <option> Kirkos</option>
                    <option> Kolfe</option>
                    <option> Lemi Kura</option>
                    </select>
                    <span class="text-danger"> 
                    <?php echo form_error('subcity'); ?>
                    </span>
                </div>
            </div>
            <div class="form-group col-lg-4">
                <div class="form-group">
                    <label for="woreda">Woreda
                         <!-- <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small> -->
                    </label>
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
            <div class="form-group col-lg-6">
                <label for="password" class="d-block">Password
                     <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small>
                </label>
                <input id="password" required="required" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password">
                <span  class="text-danger"> 
                <?php echo form_error('password'); ?>
                </span>
            </div>
            <div class="form-group col-lg-6">
                <label for="password2" class="d-block">Password Confirmation
                     <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small>
                </label>
                <input id="password2" required="required" type="password" class="form-control" name="password-confirm">
                <span class="text-danger"> 
                <?php echo form_error('password-confirm'); ?>
                </span>
            </div>
            <div class="form-group col-lg-6">
                <label for="password2" class="d-block">Select School Branch
                     <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small>
                </label>
                <select class="form-control selectric"
                    required="required" name="branch" 
                    id="branch">
                    <?php foreach($branch as $branchs){ ?>
                        <option><?php echo $branchs->name ?></option>
                    <?php } ?>
                  </select>
                <span class="text-danger"> 
                </span>
            </div>
            <div class="form-group col-lg-6">
               <div class="form-group">
                 <label for="ac">Academic year
                     <small><span class="text-danger"> <i class="fa fa-asterisk"></i></span></small>
                 </label>
                  <select class="form-control selectric"
                    required="required" name="academicyear" 
                    id="academicyear">
                    <?php foreach($academicyear as $academicyears){ ?>
                        <option><?php echo $academicyears->year_name ?></option>
                    <?php } ?>
                  </select>
                    <span class="text-danger"> 
                    <?php echo form_error('ac'); ?>
                    </span>
                </div>
            </div>
          </div>
          <div class="text-center"><button class="btn btn-block" type="submit" name="register">Register</button></div>
          <br>
          <div class="text-center"> <a href="<?php echo base_url(); ?>loginpage/">Login
            </a>|| <a href="<?php echo base_url(); ?>forgotpassword/" class="">Forgot Password? </a>
          </div>
        </form>
        <?php } else{ ?>
            <div class="alert alert-light text-danger">
                <h3 class="text-danger">Registration has been closed!</h3>
            </div>
        <?php } ?>
      </section>
    </section>
    <aside id="ccr-right-section" class="col-md-6 ccr-home">
      <section id="sidebar-popular-post">
        <div class="ccr-gallery-ttile">
          <span></span> 
          <p><strong>Recent Post</strong></p>
        </div> 
        <ul>
          <?php foreach($blogs as $blog){ $pageTitle=$blog->ntitle; ?>
          <li>
            <a href="<?php echo base_url()?>singlepage?blog=<?php echo $pageTitle; ?>" class="media-left"><?php echo $blog->ntitle;?><img src="<?php echo base_url(); ?>/news/<?php echo $blog->photo;?>" style="height: 50px;" alt="..."></a>
            <div class="date-like-comment">
              <span class="date"><time datetime="2014-02-17"><?php echo $blog->datepost;?></time></span>
            </div>
          </li>
        <?php }?>
        </ul>
      </section>
    </aside>
  </div>
</section>

<aside id="ccr-footer-sidebar" class="container">
  <div class="container">
    <ul>
      <li class="col-md-3">
        <h5>About Us</h5>
        <div class="about-us">
          <?php echo substr($school->about,0,700); ?>...<a href="<?php echo base_url(); ?>about/"><button class="btn">Read More </button></a>
        </div>
      </li>
      <li class="col-md-3">
        <h5>Contact Us</h5>
        <ul>
          <li>
            <p><a>Address: <?php echo $school->address;?></a></p>
          </li>
          <li>
            <p><a>Phone: <?php echo $school->phone;?></a></p>
          </li>
          <li>
           <p><a>Email: <?php echo $school->email;?></a></p>
          </li>
        </ul>
      </li>
      <li class="col-md-3">
        <h5>Useful Links</h5>
        <ul>
          <li><i class="fa fa-chevron-right"></i> <a href="<?php echo base_url(); ?>">Home</a></li>
          <li><i class="fa fa-chevron-right"></i> <a href="<?php echo base_url(); ?>about/">About us</a></li>
          <li><i class="fa fa-chevron-right"></i> <a href="<?php echo base_url(); ?>schoolblogs/">News</a></li>
          <li><i class="fa fa-chevron-right"></i> <a href="<?php echo base_url(); ?>schoolsgallery/">Gallery</a></li>
        </ul>
      </li>
      <li class="col-md-3">
        <h5>Popular Blogs</h5>
        <ul>
          <?php foreach($blogs as $blog){ $pageTitle=$blog->ntitle; ?>
          <li><a href="<?php echo base_url()?>singlepage?blog=<?php echo $pageTitle; ?>" class="media-left"><img src="<?php echo base_url(); ?>/news/<?php echo $blog->photo;?>" style="height: 50px;" alt="..."> <?php echo $blog->ntitle;?> </a></li>
          <?php } ?>
        </ul>
      </li>
    </ul>
  </div>
</aside> 


<footer id="ccr-footer" class="container">
  <div class="container">
    <div class="copyright">
       All Rights Reserved &copy; <?php echo date('Y') ?> Copyrights <a href="http://grandstande.com">Grandstande IT Solution Plc</a>
    </div>
  </div> 
</footer>
 <script src="<?php echo base_url(); ?>assetslogin/js/jquery-1.9.1.min.js"></script>
  <script src="<?php echo base_url(); ?>assetslogin/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>assetslogin/js/custom.js"></script>
</body>
</html>