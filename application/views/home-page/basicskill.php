<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title> 
    <?php foreach($schools as $school) {
      echo $school->name;}
      ?>
  </title>
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/pretty-checkbox.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
</head>

<body>
  <div class="loader"><div class="loaderIcon"></div></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
       <?php include('header.php'); ?>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <?php include('left_menu.php'); ?>
        </aside>
      </div>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <?php if($_SESSION['usertype']===trim('superAdmin')){ ?>
              <div class="enable_sub_bs_categories"></div>
            <?php } ?>
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link show active" id="home-tab0" data-toggle="tab" href="#bsCateShow" role="tab" aria-selected="true">BS Categories</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" id="home-tab1" data-toggle="tab" href="#bsnameShow" role="tab" aria-selected="false">BS Names</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#bstypeShow" role="tab" aria-selected="false">BS Type</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#contypeShow" role="tab" aria-selected="false">Conduct Type</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="bsCateShow" role="tabpanel" aria-labelledby="home-tab0">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <?php if($enable_sub_category){ ?>
                        <a href="#" class="add_New_sub_Category" id="add_New_sub_Category" value="" data-toggle="modal" data-target="#add-new-sub-category">
                        <button class="btn btn-info pull-right"><i data-feather="plus-circle"> </i>Add Sub Category</button> 
                        </a>
                        <?php } ?>
                        <a href="#" class="add_New_Category" value="" data-toggle="modal" data-target="#add-new-category">
                        <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add New Category</button> 
                        </a>
                        
                      </div>
                      <div class="col-lg-12 col-12">
                        <div id="bskillCategory"> </div>
                      </div>
                    </div>                  
                  </div>
                  <div class="tab-pane fade show" id="bsnameShow" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="row">
                      <div class="col-lg-8 col-7">
                        <input type="text" name="searchBSNAMES" id="searchBSNAMES" class="form-control typeahead" placeholder="Search BS Name. . .  ">
                        <div class="dropdown-divider"></div>
                      </div>
                      <div class="col-lg-4 col-5">
                        <a href="#" class="add_New_Bsname" value="" data-toggle="modal" data-target="#add-new-bs-name"><span class="text-success">
                        <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add BS Name</button>
                       </span>
                       </a>
                      </div>
                      <div class="col-lg-12 col-12">
                        <div id="bskillDataShow"> </div>
                      </div>
                    </div> 
                  </div>
                  <div class="tab-pane fade show" id="bstypeShow" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="add_New_BsTpe" value="" data-toggle="modal" data-target="#add-new-bs-type"><span class="text-success">
                        <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add BS Type</button>
                       </span>
                       </a>
                      </div>
                      <div class="col-lg-12 col-12">
                        <div id="bskilltype">  </div>
                      </div>
                    </div>                    
                  </div>
                  <!--  -->
                  <div class="tab-pane fade show" id="contypeShow" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="add_New_conductType" value="" data-toggle="modal" data-target="#add-new-conduct-type"><span class="text-success">
                        <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add Conduct Type</button>
                       </span>
                       </a>
                      </div>
                      <div class="col-lg-12 col-12">
                         <div id="contype">  </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="add-new-conduct-type" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add Conduct Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="card-body StudentViewTextInfo">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6 col-6">
                  <div class="form-group">
                    <label for="Mobile">Conduct Type</label>
                   <input class="form-control" id="cotype" name="cotype" type="text" placeholder="Conduct type here">
                  </div>
                </div>
                <div class="col-lg-6 col-6">
                  <div class="form-group">
                    <label for="Mobile">Type Description</label>
                   <input class="form-control" id="contypedecs" name="contypedecs" type="text" placeholder="Conduct description here">
                  </div>
                </div>
                <div class="col-lg-10 col-12">
                  <div class="form-group">
                    <input type="checkbox" class="" id="selectAllConduct_Type" onClick="selectAllConductTYpe()">Select All
                    <div class="row"> 
                    <?php foreach($grade as $grades){ ?>
                      <div class="col-lg-2 col-4">
                        <div class="pretty p-bigger">
                         <input id="congrade" type="checkbox" name="congrade" value="<?php echo $grades->grade; ?>">
                         <div class="state p-info">
                            <i class="icon material-icons"></i>
                            <label></label><?php echo $grades->grade; ?>
                         </div>
                       </div>
                     </div>
                    <?php } ?>
                  </div>
                  </div>
                </div>
                <div class="col-lg-2 col-12">
                  <a id="infocontype"></a>
                  <button type="button" class="btn btn-primary btn-block" id="savecontype">Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add-new-bs-type" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add BS Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="card-body StudentViewTextInfo">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6 col-6">
                  <div class="form-group">
                    <label for="Mobile">Basic Skill Type</label>
                   <input class="form-control" id="bstype" name="bstype" type="text" placeholder="Basic skill type here">
                  </div>
                </div>
                <div class="col-lg-6 col-6">
                  <div class="form-group">
                    <label for="Mobile">Type Description</label>
                   <input class="form-control" id="bstypedecs" name="bstypedecs" type="text" placeholder="Basic skill description here">
                  </div>
                </div>
                <div class="col-lg-10 col-12">
                  <div class="form-group">
                     <input type="checkbox" class="" id="selectAllBS_Type" onClick="selectAllBSTYpe()">Select All
                    <div class="row"> 
                    <?php foreach($grade as $grades){ ?>
                      <div class="col-lg-2 col-4">
                        <div class="pretty p-bigger">
                         <input id="bsgrade" type="checkbox" name="bsgrade" value="<?php echo $grades->grade; ?>">
                         <div class="state p-info">
                            <i class="icon material-icons"></i>
                            <label></label><?php echo $grades->grade; ?>
                         </div>
                       </div>
                     </div>
                    <?php } ?>
                  </div>
                  </div>
                </div>
                <div class="col-lg-2 col-12">
                  <a id="infobstype"></a>
                  <button type="button" class="btn btn-primary btn-block" id="savebstype">Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add-new-bs-name" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add New BS Name</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="card-body StudentViewTextInfo">
            <div class="modal-body">
              <form method="POST" id="save_baskill">
                <div class="row">
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <select class="form-control" required="required" name="name_quarter" id="name_quarter">
                      <option>Select Season</option>
                      <?php foreach($fetch_term as $fetch_terms){ ?>
                        <option value="<?php echo $fetch_terms->term; ?>"><?php echo $fetch_terms->term; ?></option>
                      <?php } ?>
                    </select>
                    </div>
                  </div>
                  
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <select class="form-control" required="required" name="linkcategory" id="linkcategory">
                      <option>Select Category</option>
                      <?php foreach($bscategory as $bscategorys){ ?>
                        <option value="<?php echo $bscategorys->bscategory; ?>"><?php echo $bscategorys->bscategory; ?></option>
                      <?php } ?>
                    </select>
                    </div>
                  </div>
                  <?php if($enable_sub_category){ ?>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <select class="form-control" required="required" name="linksubcategory" id="linksubcategory">
                      <option>Select Sub-category</option>
                      
                    </select>
                    </div>
                  </div>
                  <?php } ?>
                  <div class="col-lg-12 col-12 table-responsive" style="height:15vh">
                    <input type="checkbox" class="" id="selectAllSubjectGS" onClick="selectAllSubject()">Select All
                    <div id="grade_4BsName"> </div>
                  </div>
                  <div class="col-lg-12 col-12">
                   <div class="form-group">
                     <input type="text" class="form-control" id="bsname" name="bsname" placeholder="Basic skill name ...">
                    </div>
                  </div>
                  <div class="col-lg-12 col-12">
                    <button type="submit" name="postevaluation" class="btn btn-primary pull-right">Save Basic Skill
                      </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add-new-category" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add New Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
          <div class="card-body StudentViewTextInfo">
            <div class="modal-body">
              <form method="POST" id="save_baskillCate">
                <div class="row">
                  <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <select class="form-control" required="required" name="bsname_quarter" id="bsname_quarter">
                      <option>Select Season</option>
                      <?php foreach($fetch_term as $fetch_terms){ ?>
                        <option value="<?php echo $fetch_terms->term; ?>"><?php echo $fetch_terms->term; ?></option>
                      <?php } ?>
                    </select>
                    </div>
                  </div>
                  <div class="col-lg-6 col-12">
                   <div class="form-group">
                     <input type="text" class="form-control" id="bsnamecate" name="bsnamecate" placeholder="Basic skill category ...">
                    </div>
                  </div>
                  <div class="col-lg-12 col-12 table-responsive" style="height:20vh">
                    <input type="checkbox" class="" id="selectAllBSCAte" onClick="selectAllCate()">Select All
                      <div class="row"> 
                      <?php foreach($grade as $grades){ ?>
                        <div class="col-lg-2 col-6">
                          <div class="pretty p-bigger">
                           <input id="categrade" type="checkbox" name="categrade" value="<?php echo $grades->grade; ?>">
                           <div class="state p-info">
                              <i class="icon material-icons"></i>
                              <label></label><?php echo $grades->grade; ?>
                           </div>
                         </div>
                       </div>
                      <?php } ?>
                      </div>
                  </div>
                  <div class="col-lg-12 col-12">
                    <button type="submit" name="postCategory" class="btn btn-primary pull-right">Save Category
                      </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add-new-sub-category" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add sub category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div> 
        <div class="modal-body">
          <ul class="nav nav-tabs" id="myTab2" role="tablist">
            <li class="nav-item">
              <a class="nav-link show active" id="home-tab0" data-toggle="tab" href="#addsub_category" role="tab" aria-selected="true">Add sub-category</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="home-tab1" data-toggle="tab" href="#viewSub_Category" role="tab" aria-selected="false">View Sub-category</a>
            </li>
          </ul>
          <div class="tab-content tab-bordered" id="myTab3Content">
            <div class="tab-pane fade show active" id="addsub_category" role="tabpanel" aria-labelledby="home-tab0">
              <form method="POST" id="save_baskill_subCate">
                <div class="row">
                  <div class="col-lg-3 col-12">
                    <div class="form-group">
                      <select class="form-control" required="required" name="bsnamesub_quarter" id="bsnamesub_quarter">
                      <option>Select Season</option>
                      <?php foreach($fetch_term as $fetch_terms){ ?>
                        <option value="<?php echo $fetch_terms->term; ?>"><?php echo $fetch_terms->term; ?></option>
                      <?php } ?>
                    </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-12 table-responsive" style="height:20vh">
                    <div class="row"> 
                    <?php foreach($grade as $grades){ ?>
                      <div class="col-lg-4 col-6">
                        <div class="pretty p-bigger">
                         <input id="categrade4Sub" type="checkbox" name="categrade4Sub[ ]" value="<?php echo $grades->grade; ?>">
                         <div class="state p-info">
                            <i class="icon material-icons"></i>
                            <label></label><?php echo $grades->grade; ?>
                         </div>
                       </div>
                     </div>
                    <?php } ?>
                    </div>
                  </div>
                  <div class="col-lg-5 col-12 form-group">
                    <select class="form-control selectric" required="required" name="basicSkillCategoriesPage" id="basicSkillCategoriesPage">
                    <option>--- Select Category ---</option>
                      
                     </select>
                  </div>
                  <div class="col-lg-12 col-12">
                   <div class="form-group">
                     <input type="text" class="form-control" id="bsnamesubcate" name="bsnamesubcate" placeholder="Basic skill sub category name ...">
                    </div>
                  </div>
                  <div class="col-lg-12 col-12">
                    <button type="submit" name="postsubCategory" id="postsubCategory" class="btn btn-primary pull-right">Save Sub-category
                    </button>
                  </div>
                </div>
              </form>
            </div>
            <div class="tab-pane fade show" id="viewSub_Category" role="tabpanel" aria-labelledby="home-tab0">
              <div class="load_sub_category"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  $(document).on('change', '#linkcategory', function() {
    var quarter=$("#name_quarter").val();
    var category=$("#linkcategory").val();
    $.ajax({
      url:"<?php echo base_url(); ?>Basicskill/fetch_bs_categoreis_forGrade/",
      method:"POST",
      data: ({
        category: category,
        quarter:quarter
      }),
      beforeSend: function() {
        $('#grade_4BsName').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
      },
      success:function(data){
        $('#grade_4BsName').html(data);
      }
    })
  });
  $(document).on('change', '#linkcategory', function() {
    var quarter=$("#name_quarter").val();
    var category=$("#linkcategory").val();
    $.ajax({
      url:"<?php echo base_url(); ?>Basicskill/fetch_bs_sub_categoreis_forGrade/",
      method:"POST",
      data: ({
        category: category,
        quarter:quarter
      }),
      beforeSend: function() {
        $('#linksubcategory').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
      },
      success:function(data){
        $('#linksubcategory').html(data);
      }
    })
  });
  $(document).on('click', "input[name='bs_sub_category_enable']", function() {
    var lockmarkk=$(this).attr("value");
    var academicyear=$(this).attr("id");
    if($(this).is(':checked')){
      $.ajax({
        url:"<?php echo base_url() ?>Basicskill/enable_sub_category/",
        method:"POST",
        data:({
          academicyear:academicyear,
          lockmark:lockmarkk
        }),
        success: function(data){
          if(data=='1'){
            iziToast.success({
              title: 'Changes updated successfully.',
              message: '',
              position: 'topRight'
            });
            window.location.reload();
          }else if(data=='0'){
            iziToast.error({
              title: 'Changes not updated. Please try again',
              message: '',
              position: 'topRight'
            });
          }else if(data=='3'){
            iziToast.success({
              title: 'Changes inserted successfully.',
              message: '',
              position: 'topRight'
            });
            window.location.reload();
          }else{
            iziToast.error({
              title: 'Changes not inserted. Please try again',
              message: '',
              position: 'topRight'
            });
          } 
        }
      });
    }else{
      var lockmarkk=$(this).attr("value");
      var academicyear=$(this).attr("id");
      $.ajax({
        url:"<?php echo base_url() ?>Basicskill/disable_sub_category/",
        method:"POST",
        data:({
          academicyear:academicyear,
           lockmark:lockmarkk
        }),
        success: function(data){
          if(data=='1'){
            iziToast.success({
              title: 'Changes deleted successfully.',
              message: '',
              position: 'topRight'
            });
            window.location.reload();
          }else{
            iziToast.error({
              title: 'Changes not deleted. Please try again',
              message: '',
              position: 'topRight'
            });
          }
          
        }
      });
    }
  });
  enable_bs_sub_categories();
  function enable_bs_sub_categories() {
    $.ajax({
      method:'POST',
      url:'<?php echo base_url() ?>Basicskill/enable_bs_sub_categories/',
      cache: false,
      beforeSend: function() {
        $('.enable_sub_bs_categories').html( 'Checking...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
        );
      },
      success: function(html){
       $('.enable_sub_bs_categories').html(html);
      }
    })
  }
  function load_sub_category_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>Basicskill/load_sub_category_data/",
      method:"POST",
      beforeSend: function() {
        $('.load_sub_category').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success:function(data){
        $('.load_sub_category').html(data);
      }
    })
  }
  $(document).on('click', '#remove_bs_sub_category_type', function(e) {
    e.preventDefault();
    var userid=$(this).attr("value");
    $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Basicskill/remove_bs_sub_category_type/",
      data: ({
        userid: userid
      }),
      beforeSend: function() {
        $('.remove_bs_sub_category_type' + userid).html( '<span class="text-info">Removing...</span>');
        $('#remove_bs_sub_category_type').attr( 'disabled','disabled');
        
      },
      success: function(html){
        if(html=='1'){
          iziToast.success({
            title: 'Removed successfully',
            message: '',
            position: 'topRight'
          });
          load_sub_category_data();
        }else{
          iziToast.error({
            title: 'Please try later',
            message: '',
            position: 'topRight'
          });
        }
        $('.remove_bs_sub_category_type' + userid).html( 'Remove');
        $('#remove_bs_sub_category_type').removeAttr( 'disabled');
      }
    });
  });
  $(document).on('click', '#add_New_sub_Category', function(e) {
    e.preventDefault();
    load_sub_category_data();
  });
  $(document).on('click', '#postsubCategory', function(e) {
    e.preventDefault();
    gradeName=[];
    $("input[name='categrade4Sub[ ]']:checked").each(function(i){
      gradeName[i]=$(this).val();
    });
    var quarter=$("#bsnamesub_quarter").val();
    var category=$("#basicSkillCategoriesPage").val();
    var subCategory=$("#bsnamesubcate").val();
    if ($('#bsnamesub_quarter').val() != '' & gradeName.length!=0) {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Basicskill/save_sub_category/",
        data: ({
          gradeName: gradeName,
          quarter:quarter,
          category:category,
          subCategory:subCategory
        }),
        beforeSend: function() {
          $('#postsubCategory').html( 'Saving...');
          $('#postsubCategory').attr( 'disabled','disabled');
        },
        success: function(html){
          if(html=='1'){
            iziToast.success({
              title: 'Saved successfully',
              message: '',
              position: 'topRight'
            });
            $('#save_baskill_subCate')[0].reset();
            load_sub_category_data();
          }else{
            iziToast.error({
              title: 'Sub-category name found.',
              message: '',
              position: 'topRight'
            });
          }
          $('#postsubCategory').html( 'Save Sub-category');
          $('#postsubCategory').removeAttr( 'disabled');
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
  $(document).on('click', '#categrade4Sub', function() {
    gradeName=[];
    $("input[name='categrade4Sub[ ]']:checked").each(function(i){
      gradeName[i]=$(this).val();
    });
    var quarter=$("#bsnamesub_quarter").val();
    $.ajax({
      url:"<?php echo base_url(); ?>Basicskill/fetch_bs_categoreis/",
      method:"POST",
      data: ({
        gradeName: gradeName,
        quarter:quarter
      }),
      beforeSend: function() {
        $('#basicSkillCategoriesPage').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
      },
      success:function(data){
        $('#basicSkillCategoriesPage').html(data);
      }
    })
  });
  loadcatedata();
    function loadcatedata()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Basicskill/fetchBsCategory/",
        method:"POST",
        beforeSend: function() {
          $('#bskillCategory').html( 'Loading Basic Skill Category...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#bskillCategory').html(data);
        }
      })
    }
    $(document).on('click', '#moveBSDetailsLastYear', function() { 
      swal({
        title: 'Are you sure you want to move last year Basic Skill?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>Basicskill/movingBasicSkill/",
            cache: false,
            beforeSend: function() {
              $('#moveBSDetailsLastYear').html( 'Please wait...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
              );
            },
            success: function(html) {
              $('#moveBSDetailsLastYear').html(html);
              loadcatedata();
              load_data_bs_Names();
            }
          });
        } 
      });
    });
  load_bstypedata();
    function load_bstypedata()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Basicskill/fetchBasicSkillsType/",
        method:"POST",
        beforeSend: function() {
          $('#bskilltype').html( 'Loading Basic Skill Type...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#bskilltype').html(data);
        }
      })
    }
  load_contypedata();
    function load_contypedata()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Basicskill/fetchConductType/",
        method:"POST",
        beforeSend: function() {
          $('#contype').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#contype').html(data);
        }
      })
    }
  load_data_bs_Names();
    function load_data_bs_Names()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Basicskill/fetchBasicSkills/",
        method:"POST",
        beforeSend: function() {
          $('#bskillDataShow').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#bskillDataShow').html(data);
        }
      })
    }
  $(document).ready(function() { 
    $('#searchBSNAMES').on("keyup",function() {
      if($('#searchBSNAMES').val()!==''){
        $searchItem=$('#searchBSNAMES').val();
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>Basicskill/searchBS_Names/",
          data: "searchItem=" + $("#searchBSNAMES").val(),
          beforeSend: function() {
            $('#bskillDataShow').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(data) {
            $("#bskillDataShow").html(data);
          }
        });
      }else{
        load_data_bs_Names();
      }
    });
  });
  function selectAllConductTYpe(){
      var itemsall=document.getElementById('selectAllConduct_Type');
      if(itemsall.checked==true){
      var items=document.getElementsByName('congrade');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('congrade');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
  }
  function selectAllBSTYpe(){
      var itemsall=document.getElementById('selectAllBS_Type');
      if(itemsall.checked==true){
      var items=document.getElementsByName('bsgrade');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('bsgrade');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
  }
  function selectAllCate(){
      var itemsall=document.getElementById('selectAllBSCAte');
      if(itemsall.checked==true){
      var items=document.getElementsByName('categrade');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('categrade');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
  }
  function selectAllSubject(){
      var itemsall=document.getElementById('selectAllSubjectGS');
      if(itemsall.checked==true){
      var items=document.getElementsByName('grade');
      for(var i=0;i < items.length;i++){
        items[i].checked=true;
      }
    }
      else{
      var items=document.getElementsByName('grade');
      for(var i=0;i < items.length;i++){
        items[i].checked=false;
      }
    }
  }
  $(document).ready(function(){
    $(document).on('click', "input[name='putbsCatLeftRow']", function() {
      var catName = $(this).attr("value");
      if($(this).is(':checked')){
        var catStatus = 1;
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Basicskill/updateCatLeftRow/",
          data: ({
            catName: catName,
            catStatus:catStatus
          }),
          cache: false,
          success: function(html) {
            iziToast.success({
              title: 'Category status changed successfully',
              message: '',
              position: 'topRight'
            });
          }
        });
      }else{
        var catStatus = 0;
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Basicskill/updateCatRightRow/",
          data: ({
            catName: catName,
            catStatus:catStatus
          }),
          cache: false,
          success: function(html) {
            iziToast.success({
              title: 'Category status changed successfully',
              message: '',
              position: 'topRight'
            });
          }
        });
      }
    });
  });
  $(document).on('change', '.bssubOrderJo', function() {
    var suborder=$(this).find('option:selected').attr('value');
    var subject=$(this).find('option:selected').attr('id');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Basicskill/updateCatOrder/",
        data: ({
          suborder:suborder,
          subject:subject
        }),
        success: function(data) {
          iziToast.success({
            title: 'Order updated successfully',
            message: '',
            position: 'topRight'
          });
        }
    });
  });
  $(document).ready(function(){
    
    $(document).on('click', '.editCAT', function() {
      var category=$(this).attr('value');
      var quarter=$(this).attr('name');
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Basicskill/editBsCategory/",
        data: ({
          category:category,
          quarter:quarter
        }),
        cache: false,
        beforeSend: function() {
          $('#bskillCategory').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success: function(data){
          $('#bskillCategory').html(data);
        }
      });
    });
    $(document).on('click', '#saveBsCategory', function() {
      grade=[];
      $("input[name='changeBSCateForGrade']:checked").each(function(i){
        grade[i]=$(this).val();
      });
      var categoryOld=$(this).attr('value');
      var categoryNew=$('#bsCategoryInfo').val();
      var quarterNew=$('#bstCategoryQuarterInfo').val();
      var quarterOld=$(this).attr('name');
      if(grade.length!=0 && $('#bsCategoryInfo').val()!='' && $('#bstCategoryQuarterInfo').val()!=''){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Basicskill/updateBsCategory/",
          data: ({
            categoryOld:categoryOld,
            categoryNew:categoryNew,
            quarterNew:quarterNew,
            quarterOld:quarterOld,
            grade:grade
          }),
          cache: false,
          beforeSend: function() {
            $('#bskillCategory').html( 'Saving...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success: function(data){
            loadcatedata();
          }
        });
      }else{
         swal('Oooops, Please select at least one grade or fill necessary fields to save changes!', {
          icon: 'error',
        });
      }
    });
    $(document).on('click', '#moveBSDetails', function() {
      $.ajax({
        url:"<?php echo base_url(); ?>Basicskill/movingBs/",
        method:"POST",
        beforeSend: function() {
          $('#bskillCategory').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#bskillCategory').html(data);
          loadcatedata();
          load_data_bs_Names();
        }
      })
    });
    $('#save_baskillCate').on('submit', function(event) {
      event.preventDefault();
      var bsnamecate=$('#bsnamecate').val();
      var bsname_quarter=$('#bsname_quarter').val();
      id=[];
      $("input[name='categrade']:checked").each(function(i){
        id[i]=$(this).val();
      });
      if($('#bsnamecate').val() =='' || id.length == 0 || $('#bsname_quarter').val()=='Select Season' )
      {
        swal('Oooops, Please type category.!', {
          icon: 'error',
        });
      }else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Basicskill/insertBsCategory/",
          data: ({
            bsnamecate:bsnamecate,
            grade:id,
            bsname_quarter:bsname_quarter
          }),
          cache: false,
          success: function(html){
            $('#bsnamecate').val('');
            $('#categrade').prop('checked',false);
            loadcatedata();
            iziToast.success({
              title: 'Saved successfully',
              message: '',
              position: 'topRight'
            });
          }
        });
      }
    });
  });
  $(document).on('click', '#deleteThisGradeCategory', function() {
    var grade=$(this).attr("value");
    var category=$(this).attr("title");
    var quarter=$(this).attr("name");
    swal({
      title: 'Are you sure you want to delete this Basic skill category?',
      text: 'Every data will be erased permanently regarding this Basic skill category for grade '+grade+'! ',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Basicskill/deleteSpecificBsCategory/",
          data: ({
            grade: grade,
            category:category,
            quarter:quarter
          }),
          cache: false,
          success: function(html){
            loadcatedata();
          }
        });
      }
    });
  });
  $(document).on('click', '.deleteCAT', function() {
    var delte_id=$(this).attr("value");
    var quarter=$(this).attr("name");
    swal({
      title: 'Are you sure you want to delete this Basic skill category?',
      text: 'Every data will be erased permanently regarding this Basic skill category! ',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Basicskill/deleteBsCategory/",
          data: ({
            delte_id: delte_id,
            quarter:quarter
          }),
          cache: false,
          success: function(html){
            $('.deleteCAT' + delte_id).fadeOut('slow');
            loadcatedata();
          }
        });
      }
    });
  });
  $(document).ready(function(){
    $(document).on('click', '#savecontype', function() {
      var cotype=$('#cotype').val();
      var contypedecs=$('#contypedecs').val();
      id=[];
      $("input[name='congrade']:checked").each(function(i){
        id[i]=$(this).val();
      });
      if($('#cotype').val() =='' || id.length == 0)
      {
        swal('Oooops, Please type conduct value!', {
          icon: 'error',
        });
      }else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Basicskill/insertConType/",
          data: ({
            cotype:cotype,
            contypedecs:contypedecs,
            grade:id
          }),
          cache: false,
          success: function(html){
            $('#cotype').val('');
            $('#contypedecs').val('');
            $('#congrade').prop('checked',true);
            load_contypedata();
          }
        });
      }
    });
  });
   $(document).on('click', '.deletecontype', function() {
    var delte_id=$(this).attr("value");
    var delte_desc=$(this).attr("id");
    swal({
      title: 'Are you sure you want to delete this Conduct Type?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Basicskill/deleteConType/",
          data: ({
            delte_id: delte_id,
            delte_desc:delte_desc
          }),
          cache: false,
          success: function(html){
            load_contypedata();
          }
        });
      }
    });
  });
  $(document).ready(function(){
    $(document).on('click', '#savebstype', function() {
      var bstype=$('#bstype').val();
      var bstypedecs=$('#bstypedecs').val();
      id=[];
      $("input[name='bsgrade']:checked").each(function(i){
        id[i]=$(this).val();
      });
      if($('#bstype').val() =='' || id.length == 0)
      {
        swal('Oooops, Please type Basic skill types!', {
          icon: 'error',
        });
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Basicskill/insertBsType/",
        data: ({
          bstype:bstype,
          bstypedecs:bstypedecs,
          grade:id
        }),
        cache: false,
        success: function(html){
          $('#bstype').val('');
          $('#bstypedecs').val('');
          load_bstypedata();
        }
      });
    }
  });
  $(document).on('click', '.editbaskilltype', function() {
    var bstype=$(this).attr('value');
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Basicskill/editBsType/",
      data: ({
        bstype:bstype
      }),
      cache: false,
      beforeSend: function() {
        $('#bskilltype').html( 'Loading Basic Skill Type...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data){
        $('#bskilltype').html(data);
      }
    });
  });
  $(document).on('click', '#saveBsType', function() {
    var bstypeId=$(this).attr('value');
    var bstypeName=$('.bstypeInfo').val();
    var bstypeDesc=$('.bstdescInfo').val();
    var oldbstypeName=$('.oldbstypeInfo').val();
    var oldbstypeDesc=$('.oldbstdescInfo').val();
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Basicskill/updateBsType/",
      data: ({
        bstypeId:bstypeId,
        bstypeName:bstypeName,
        bstypeDesc:bstypeDesc,
        oldbstypeName:oldbstypeName,
        oldbstypeDesc:oldbstypeDesc
      }),
      cache: false,
      success: function(data){
        load_bstypedata();
         iziToast.success({
          title: data,
          message: '',
          position: 'topRight'
        });
      }
    });
  });
});
   $(document).on('click', '.deletebaskilltype', function() {
    var delte_id=$(this).attr("value");
    swal({
      title: 'Are you sure you want to delete this Basic skill Type?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Basicskill/deleteBsType/",
          data: ({
            delte_id: delte_id
          }),
          cache: false,
          success: function(html){
            load_bstypedata();
          }
        });
      }
    });
  });
  $(document).ready(function(){
    $('#save_baskill').on('submit', function(event) {
      event.preventDefault();
      var bsname=$('#bsname').val();
      var bssubname=$('#linksubcategory').val();
      var linkcategory=$('#linkcategory').val();
      var name_quarter=$('#name_quarter').val();
      id=[];
      $("input[name='grade']:checked").each(function(i){
        id[i]=$(this).val();
      });
      if( id.length == 0 || $('#bsname').val() =='' || $('#name_quarter').val()=='')
      {
        swal('Oooops, Please select necessary fields!', {
          icon: 'error',
        });
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Basicskill/record_bs_Names/",
        data: ({
          id: id,
          bsname:bsname,
          linkcategory:linkcategory,
          name_quarter:name_quarter,
          bssubname:bssubname
        }),
        success: function(html){
          if(html=='1'){
            $('#save_baskill')[0].reset();
            
            iziToast.success({
              title: 'Saved successfully',
              message: '',
              position: 'topRight'
            });
            load_data_bs_Names();
          }else{
            iziToast.error({
              title: 'Basic skill name found',
              message: '',
              position: 'topRight'
            });
          }
        }
      });
    }
  });
  /*$(document).on('click', "input[name='addOnSubRowGs']", function() {
    var bsGrade=$(this).attr('id');
    var bsName=$(this).attr('class');
    var bsValue=$(this).attr('value');
    if($(this).is(':checked')){
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Basicskill/putOnSubjectRow/",
      data: ({
        bsGrade:bsGrade,
        bsName:bsName,
        bsValue:bsValue
      }),
      cache: false,
      success: function(html){
        iziToast.success({
          title: 'Basicskill',
          message: 'Updated successfully',
          position: 'topRight'
        });
      }
    });
    }else{
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Basicskill/deleteputOnSubjectRow/",
      data: ({
        bsGrade:bsGrade,
        bsName:bsName,
        bsValue:bsValue
      }),
      cache: false,
      success: function(html){
        iziToast.success({
          title: 'Basicskill',
          message: 'Updated successfully',
          position: 'topRight'
        });
      }
    });
    }
  });*/
  $(document).on('change', '.bsNamessubOrderJo', function() {
    var suborder=$(this).find('option:selected').attr('value');
    var bsName=$(this).find('option:selected').attr('id');
    var quarter=$(this).find('option:selected').attr('name');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Basicskill/updateBSOrderOrder/",
        data: ({
          suborder:suborder,
          bsName:bsName,
          quarter:quarter
        }),
        success: function(data) {
          iziToast.success({
            title: 'BS order updated successfully',
            message: '',
            position: 'topRight'
          });
        }
    });
  });
  $(document).on('change', '.specificbsNamessubOrderJo', function() {
    var suborder=$(this).find('option:selected').attr('value');
    var bsName=$(this).find('option:selected').attr('id');
    var quarter=$(this).find('option:selected').attr('name');
    var grade=$(this).find('option:selected').attr('title');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Basicskill/updateSpecificBSOrderOrder/",
        data: ({
          suborder:suborder,
          bsName:bsName,
          quarter:quarter,
          grade:grade
        }),
        success: function(data) {
          iziToast.success({
            title: 'BS order updated successfully',
            message: '',
            position: 'topRight'
          });
        }
    });
  });
  $(document).on('change', '.changeBSCategory', function() {
    var category=$(this).find('option:selected').attr('value');
    var bsname=$(this).find('option:selected').attr('title');
    var grade=$(this).find('option:selected').attr('id');
    var season=$(this).find('option:selected').attr('name');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>Basicskill/updateBsNameCategory/",
        data: ({
          category:category,
          bsname:bsname,
          grade:grade,
          season:season
        }),
        success: function(data) {
          iziToast.success({
            title: 'Basic Skill value updated successfully',
            message: '',
            position: 'topRight'
          });
        }
    });
  });
  $(document).on('click', '.editbaskill', function() {
    var bs=$(this).attr('value');
    var quarter=$(this).attr('name');
    var category=$(this).attr('title');
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Basicskill/editbaskill/",
      data: ({
        bs:bs,
        quarter:quarter,
        category:category
      }),
      cache: false,
      beforeSend: function() {
        $('#bskillDataShow').html( 'Saving changes...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
      },
      success: function(data){
        $('#bskillDataShow').html(data);
      }
    });
  });
  $(document).on('click', '#saveBsInfo', function() {
    grade=[];
    $("input[name='changeBSNameForGrade']:checked").each(function(i){
      grade[i]=$(this).val();
    });
    var bsInfo=$('.bsInfo').val();
    var bsnameInfo=$('#bsnameInfo').val();
    var bsquarterInfo=$('#bsquarterInfo').val();
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Basicskill/updateBs/",
      data: ({
        bsInfo:bsInfo,
        bsnameInfo:bsnameInfo,
        bsquarterInfo:bsquarterInfo,
        grade:grade
      }),
      cache: false,
      success: function(data){
        load_data_bs_Names();
        iziToast.success({
          title: data,
          message: '',
          position: 'topRight'
        });
      }
    });
  });
});
  $(document).on('click', '#deleteThisGradeBSNAme', function() {
    var grade=$(this).attr("value");
    var category=$(this).attr("title");
    var quarter=$(this).attr("name");
    swal({
      title: 'Are you sure you want to delete this Basic skill Name?',
      text: 'Every data will be erased permanently regarding this Basic skill Name for grade '+grade+'! ',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Basicskill/deleteSpecificBsName/",
          data: ({
            grade: grade,
            category:category,
            quarter:quarter
          }),
          cache: false,
          success: function(html){
             load_data_bs_Names();
          }
        });
      }
    });
  });
  $(document).on('click', "input[name='specificaddOnSubRowGs']", function() {
    var bsGrade=$(this).attr('id');
    var bsName=$(this).attr('class');
    var season=$(this).attr('value');
    if($(this).is(':checked')){
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Basicskill/putOnSubjectRow/",
      data: ({
        bsGrade:bsGrade,
        bsName:bsName,
        season:season
      }),
      cache: false,
      success: function(html){
        iziToast.success({
          title: 'Basicskill updated successfully',
          message: '',
          position: 'topRight'
        });
      }
    });
    }else{
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Basicskill/deleteputOnSubjectRow/",
      data: ({
        bsGrade:bsGrade,
        bsName:bsName,
        season:season
      }),
      cache: false,
      success: function(html){
        iziToast.success({
          title: 'Basicskill updated successfully',
          message: '',
          position: 'topRight'
        });
      }
    });
    }
  });
  $(document).on('click', '.deletebaskill', function() {
    var delte_id=$(this).attr("value");
    var quarter=$(this).attr("name");
    swal({
      title: 'Are you sure you want to delete this Basic skill?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Basicskill/",
          data: ({
            delte_id: delte_id,
            quarter:quarter
          }),
          cache: false,
          success: function(html){
            load_data_bs_Names();
          }
        });
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).on('click', '#changecolor', function() {
    var bgcolor=$(this).attr("value");
    $.ajax({
      url: "<?php echo base_url(); ?>Change_bgcolor/",
      method: "POST",
      data: ({
        bgcolor: bgcolor
      }),
    });
    if (bgcolor == "1") {
      $("body").removeClass();
      $("body").addClass("light");
      $("body").addClass("light-sidebar");
      $("body").addClass("theme-white");
      $(".choose-theme li").removeClass("active");
      $(".choose-theme li[title|='white']").addClass("active");
      $(".selectgroup-input[value|='1']").prop("checked", true);
    } else {
      $("body").removeClass();
      $("body").addClass("dark");
      $("body").addClass("dark-sidebar");
      $("body").addClass("theme-black");
      $(".choose-theme li").removeClass("active");
      $(".choose-theme li[title|='black']").addClass("active");
      $(".selectgroup-input[value|='2']").prop("checked", true);
    }
  });
</script>
<script type="text/javascript" language="javascript"> 
  var bgcolor_now=document.getElementById("bgcolor_now").value;
  if (bgcolor_now == "1") {
    $("body").removeClass();
    $("body").addClass("light");
    $("body").addClass("light-sidebar");
    $("body").addClass("theme-white");
    $(".choose-theme li").removeClass("active");
    $(".choose-theme li[title|='white']").addClass("active");
    $(".selectgroup-input[value|='1']").prop("checked", true);
  }else {
    $("body").removeClass();
    $("body").addClass("dark");
    $("body").addClass("dark-sidebar");
    $("body").addClass("theme-black");
    $(".choose-theme li").removeClass("active");
    $(".choose-theme li[title|='black']").addClass("active");
    $(".selectgroup-input[value|='2']").prop("checked", true);
  } 
</script> 

</html>