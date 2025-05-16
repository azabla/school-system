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
    <div class="col-md-3">
      <a href="<?php echo base_url(); ?>register/" class =""><button class="btn" id=""> Create Account </button></a>
        <?php $Appname='Diamond Academy.apk'; ?>
        <a href="<?php echo base_url().'Login/download/'.$Appname; ?>" class="get-started-btn">
        <button class="btn" id="">Download App</button></a>
    </div>
    <div class="col-md-3">
       <a href="<?php echo base_url(); ?>loginpage/"><button class="btn pull-right btn-block" id="">Login</button></a>
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
        <?php $querySelect=$this->db->query("select * from studentregistrationstatus where registration_status='1' ");
        if($querySelect->num_rows()>0){ ?>
        <div class="row">
          <div class="col-lg-8 col-8">
            <div class="alert alert-info"><marquee> Exciting News: Admissions open for <?php echo $currentYear ?> Academic year.  Don't miss out – register your children today to secure their spot! </marquee>
            </div>
          </div>
          <div class="col-lg-4 col-4">
            <div class="alert alert-success">
              <a href="<?php echo base_url(); ?>register/" class =""><u>Click here</u> to process registration.</a>
            </div>
          </div>
        </div>
        <?php } ?>
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
      <section id="ccr-slide-main" class="carousel slide" data-ride="carousel">       
        <div class="carousel-inner">
          <?php $countt = 0;foreach($fetch_galleryToWebsite as $fetch_single_gallery){ ?>
            <?php if($countt == 0) { ?> <div class="active item">
            <?php } else{ ?> <div class="item"> <?php } ?>
              <div class="container slide-element">
                <img src="<?php echo base_url(); ?>/gallery/<?php echo $fetch_single_gallery->gname;?>" style="width: 100%;height: 400px;" alt="">
                <p><a href="#"><?php echo $fetch_single_gallery->gtitle;?></a></p>
              </div>
            </div>
           <?php $countt = $countt + 1;} ?>     
          </div> 
          <a class="carousel-control left" href="#ccr-slide-main" data-slide="prev"><i class="fa fa-arrow-left"></i></a>
          <a class="carousel-control right" href="#ccr-slide-main" data-slide="next"><i class="fa fa-arrow-right"></i></a>
          <ol class="carousel-indicators">
            <?php  $count2 = 0;
            foreach($fetch_galleryToWebsite as $fetch_single_gallery){ ?>
              <?php if($count2 == 0) {?>
               <li data-target="#ccr-slide-main" data-slide-to="<?php echo $count2; ?>" class="active"></li>
             <?php  } else{?>
                <li data-target="#ccr-slide-main" data-slide-to="<?php echo $count2; ?>"></li>
             <?php } ?>
            <?php  $count2 = $count2 + 1;} ?>
          </ol> 
      </section>

      <section id="" class="col-md-6">
        <div class="ccr-gallery-ttile">
            <span></span> 
            <p>Vision Statement of Diamond Academy</p>
        </div><br>
        <section class="">
          <div class="featured-sports-news-post">
          <i class="fa fa-chevron-right"></i> To be the best academic institution in Ethiopia capable of producing highly qualified professionals who can contribute to the indigenous capacity building of the nation. 
          </div>
        </section> 
      </section> 
        <section id="" class="col-md-6">
          <div class="ccr-gallery-ttile">
              <span></span> 
              <p>Mission Statement of Diamond Academy</p>
          </div><br>
          <section class="featured-sports-news">
            <div class="featured-sports-news-post">
            <p><i class="fa fa-chevron-right"></i> To provide conducive and nurturing habitat for our students and teachers</p>
            <p><i class="fa fa-chevron-right"></i> To help our students become competitive and successful</p>
            <p><i class="fa fa-chevron-right"></i> To broaden the minds of our students to their fullest potential</p>
            <p><i class="fa fa-chevron-right"></i> To create safe and friendly learning and working environment for our students and staff members</p>
            </div>
          </section> 
        </section> 

        <section id="" class="col-md-11">
          <div class="ccr-gallery-ttile">
              <span></span> 
              <p>Values of Diamond Academy</p>
          </div><br>
          <section class="featured-sports-news">
            <div class="featured-sports-news-post col-md-6">
            <p><i class="fa fa-chevron-right"></i> <strong>Achievement</strong></p>
            <p><i class="fa fa-chevron-right"></i> <strong>Teaching Excellence</strong></p>
            <p><i class="fa fa-chevron-right"></i> <strong>Innovation</strong></p>
            </div>
             <div class="featured-sports-news-post col-md-6">
            <p><i class="fa fa-chevron-right"></i> <strong>Respect</strong></p>
            <p><i class="fa fa-chevron-right"></i> <strong>Responsibility</strong></p>
            </div>
          </section> 
        </section>

      <section class="bottom-border"></section>
    </section>

    <aside id="ccr-right-section" class="col-md-4 ccr-home">
      <section id="ccr-calender">
        <div class="ccr-gallery-ttile">
          <span></span> 
          <p><strong>Who We Are?</strong></p>
        </div>
        <div class="featured-sports-news-post">
          <?php echo substr($school->about,0,700); ?>...<a href="<?php echo base_url(); ?>about/"><button class="btn">Read More </button></a>
        </div>
      </section>
      <section class="bottom-border"></section>

      <section id="ccr-sidebar-newslater">
        <div class="ccr-gallery-ttile">
          <span></span> 
          <p><label for="sb-newslater"><strong>Core Objectives of Diamond Academy</strong></label></p>
        </div>
        <div class="sidebar-newslater-form">
          <p><strong><i class="fa fa-chevron-right"></i> LEARNING TO LEARN:</strong> Implies learning how to learn by developing one's Concentration, Memory Skills and Ability to Think.</p>

           <p><strong><i class="fa fa-chevron-right"></i> LEARNING TO DO:</strong> represents the skillful, creative, and discerning application of knowledge.To perform a job or work, the learning to do must be fulfilled. This entails the acquisition of competence that enables people to deal with a variety of situations and to work in teams.</p>

            <p><strong><i class="fa fa-chevron-right"></i> LEARNING TO BE:</strong> it refers to the role of education in developing all the dimensions of the complete person: to achieve the physical, intellectual, emotional and ethical integration of individual into a complete man. </p>

             <p><strong><i class="fa fa-chevron-right"></i> LEARNING TO LIVE TOGETHER:</strong> can be achieved by developing an understanding of others and their history, traditions and spiritual values, and appreciation of interdependence. Teachers should help the students realize the value of being able to live together, in their gradually enlarging world: home, school, community, city, town, province, country, and the world as a global village. This is vital in building a genuine and lasting culture of peace in the world.</p>
        </div> 
      </section> 

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