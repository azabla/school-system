<nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> 
                  <i data-feather="menu"></i>
                </a>
            </li>
            <?php $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and allowed='postInfo' order by id ASC ");  if($usergroupPermission->num_rows()>0){ ?>
            <li>
              <a href="#" class="nav-link nav-link-lg"  data-toggle="modal" data-target="#exampleModal" >
                <i data-feather="plus-circle"></i>
              </a>
            </li>
            <?php }?>
            <li>
             <a href="#" class="nav-link nav-link-lg"  data-toggle="modal" data-target="#searchModal" >
                <i data-feather="search"></i>
              </a>
            </li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown"
              class="seen nav-link nav-link-lg message-togglee">
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
                <a href="<?php echo base_url();?>mystaffinbox/">View All <i class="fas fa-chevron-right"></i></a>
              </div>
            </div>
          </li>
         
    <li class="dropdown dropdown-list-toggle">
      <a href="#" data-toggle="dropdown"
              class="seen_noti nav-link nav-link-lg message-togglee">
              <i data-feather="bell" class="bell"></i>
              <span class="badge headerBadge1 count-new-notification"></span>
      </a>
      <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
              <div class="dropdown-header">
                Notifications
              </div>
          <div class="dropdown-list-content dropdown-list-icons notification-show">
          </div>
            <div class="dropdown-footer text-center">
                <a href="#">View All 
                  <i class="fas fa-chevron-right"></i>
                </a>
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
              <a href="<?php echo base_url(); ?>viewmystaffprofile/" class="dropdown-item has-icon"> <i class="far fa-user"></i> Profile
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
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                 <?php echo form_open_multipart('home/');?>
                  <label>Create Post</label>
                  <div class="form-group col-lg-12">
                      <div class="form-group">
                        <input class="form-control" name="title" required="required" type="text" placeholder="Title here">
                      </div>
                      <textarea class="summernote-simple" name="posthere"></textarea>
                     <div class="custom-file form-group">
                        <input type="file" class="custom-file-input" id="postphoto" name="postphoto">
                        <label class="custom-file-label" for="customFile">Post photo</label>
                      </div>
                  </div>
                  <button type="submit"  name="post" class="btn btn-primary m-t-15 waves-effect">Post</button>
                </form>
              </div>
              
            </div>
          </div>
        </div>

         <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
          aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <form action="#" method="POST">
                <div class="form-group">
                    <div class="search-element">
                      <input class="form-control" name="search" required="required" type="search" placeholder="Search posts" aria-label="Search">
                      <button class="btn btn-primary m-t-15 waves-effect" type="submit">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
               </div>
              </form>
              </div>
            </div>
          </div>
        </div>