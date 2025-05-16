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
    <section id="ccr-left-section" class="col-md-8">
      <section id="ccr-contact-form">
        <div class="ccr-gallery-ttile">
          <span></span> 
          <p><strong>Login Page</strong></p>
        </div><br>
        <?php if(isset($_SESSION['error'])) { ?>
          <div class="alert alert-danger">
          <?php echo $_SESSION['error']; ?></div>
          <?php unset($_SESSION['error']); } ?>
          <?php if(isset($_SESSION['success'])) { ?>
          <div class="alert alert-success">
          <?php echo $_SESSION['success']; ?></div>
          <?php unset($_SESSION['success']); } ?>
          <?php 
          $query_name = $this->db->query("select * from school");
          $row_name = $query_name->row();
          $school_name=$row_name->name;
          $schoolID=$row_name->id;
          $queryCheck=$this->db->query("select * from subscription_detail where user_id ='$schoolID'");
          if($queryCheck->num_rows()>0){
            date_default_timezone_set("Africa/Addis_Ababa");
            $dtz = new DateTimeZone('UTC');
            $dt = new DateTime(date("Y-m-d h:i A"), $dtz); 
            $date = gmdate("Y-m-d h:i A", $dt->format('U'));
            $rowData=$queryCheck->row();
            $endDate=$rowData->sub_until;
            $date = strtotime($endDate);
            $remaining = $date - strtotime(date('Y-m-d h:i A'));
            $days_remaining = floor($remaining / 86400);
            $hours_remaining = floor(($remaining % 86400) / 3600);
            if($days_remaining <='0' && $hours_remaining<='0'){ ?>
              <h1 class="text-danger">Subscription expired. Please contact the school admin.</h1>
            <?php }
            else{ ?>
            <form action="<?php echo base_url() ?>loginpage/" method="POST" role="form" id="">
              <div class="col-md-6 form-group">
                <input type="text" name="username" class="form-control" id="name3" placeholder="UserName / ID" required>
              </div>
              <div class="col-md-6 form-group">
                <input type="password" class="form-control" name="password" id="email3" placeholder="Password" required>
                <input type="checkbox" onclick="myFunction()">Show Password 
              </div>
              <div class="col-md-6 form-group">
                <input type="checkbox" class="" name="remember" /> <small>Remember Me</small>
                <a href="<?php echo base_url(); ?>forgotpassword/" class="pull-right">Forgot Password? </a>
              </div>
              <div class="col-md-12 form-group">
                <button type="submit" class="btn btn-block" name="login">Login</button>
              </div>
            </form> 
          <?php }
          }else{ ?>
            <form action="<?php echo base_url() ?>loginpage/" method="POST" role="form" id="">
              <div class="col-md-6 form-group">
                <input type="text" name="username" class="form-control" id="name3" placeholder="UserName / ID" required>
              </div>
              <div class="col-md-6 form-group">
                <input type="password" class="form-control" name="password" id="email3" placeholder="Password" required>
                <input type="checkbox" onclick="myFunction()">Show Password 
              </div>
              <div class="col-md-6 form-group">
                <input type="checkbox" class="" name="remember" /> <small>Remember Me</small>
                <a href="<?php echo base_url(); ?>forgotpassword/" class="pull-right">Forgot Password? </a>
              </div>
              <div class="col-md-12 form-group">
                <button type="submit" class="btn btn-block" name="login">Login</button>
              </div>
            </form> 
          <?php } ?>
      </section>
    </section>
    <aside id="ccr-right-section" class="col-md-4 ccr-home">
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
<script type="text/javascript">
  function myFunction() {
  var x = document.getElementById("email3");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
} 
</script>