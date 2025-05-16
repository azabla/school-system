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

  <section id="ccr-nav-main" class="container">
    <nav class="main-menu">
      <div class="">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".ccr-nav-main">
            <h4></h4>Menu <i class="fa fa-bars"></i></h4>
          </button>
        </div>

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
          <p><strong>Application Form</strong></p>
        </div><br>
        <?php if(isset($_SESSION['error'])) { ?>
          <div class="alert alert-danger">
          <?php echo $_SESSION['error']; ?></div>
          <?php unset($_SESSION['error']); } ?>
          <?php if(isset($_SESSION['success'])) { ?>
          <div class="alert alert-success">
          <?php echo $_SESSION['success']; ?></div>
          <?php unset($_SESSION['success']); } ?>
          <?php foreach($applyjobs as $applyjob){ ?>
        <?php echo form_open_multipart('Vacancy/applicantsHere');?>
          <div class="row">
              <div class="col-lg-6 col-6 form-group">
                  <label>Position:</label>
                  <input type="text" name="applyposition"  class="form-control" value="<?php echo $applyjob->vposition ?>">
              </div>
              <div class="col-lg-6 col-6 form-group">
                  <label>Full Name:</label>
                  <input type="text" name="applyfullname"  class="form-control" placeholder="Please enter your full name..." required="required">
              </div>
              <div class="col-lg-6 col-6 form-group">
                  <label>Select Qualification:</label>
                  <select class="form-control" name="applyqualification" required>
                      <option></option>
                      <option>Diploma</option>
                      <option>Degree</option>
                      <option>Masters</option>
                      <option>PHD</option>
                  </select>
              </div>
              <div class="col-lg-6 col-6 form-group">
                  <label>Private School Experience:</label>
                  <select class="form-control" name="applyexperience" required>
                      <option></option>
                      <option>0 years</option>
                      <option>1-2 years</option>
                      <option>3-5 years</option>
                      <option>More than 5 years</option>
                  </select>
              </div>
              <div class="col-lg-6 col-6 form-group">
                  <label>Attach CV(Pdf|Doc):</label>
                  <input type="file" name="applycv" required="required">
              </div>
              <div class="col-lg-6 col-6 form-group">
                  <label>Attach Mobile:</label>
                  <input type="text" name="applymobile" required="required" class="form-control" placeholder="Enter your mobile">
              </div>
              <button class="btn btn-block" type="submit" name="submitapply">Submit</button>
          </div>
      </form> 
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
          <?php foreach($blogss as $blog){ $pageTitle=$blog->ntitle; ?>
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
          <?php foreach($blogss as $blog){ $pageTitle=$blog->ntitle; ?>
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