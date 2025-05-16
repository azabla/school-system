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
<link rel='shortcut icon' type='image/x-icon'
   href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
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
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link show active" id="home-tab0" data-toggle="tab" href="#bsCateShow" role="tab" aria-selected="true">Basic Skill Categories</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab1" data-toggle="tab" href="#bsnameShow" role="tab" aria-selected="false">Basic Skill Names</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#bstypeShow" role="tab" aria-selected="false">Basic Skill Type</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#contypeShow" role="tab" aria-selected="false">Conduct Type</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="bsCateShow" role="tabpanel" aria-labelledby="home-tab0">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="add_New_Category" value="" data-toggle="modal" data-target="#add-new-category"><span class="text-success">
                        <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add New Category</button>
                       </span>
                       </a>
                      </div>
                      <div class="col-lg-12 col-12">
                        <div id="bskillCategory"> </div>
                      </div>
                    </div>                  
                  </div>
                  <div class="tab-pane fade show" id="bsnameShow" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="row">
                      <div class="col-lg-12 col-12">
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
                  <div class="col-lg-4 col-6">
                    <div class="form-group">
                      <select class="form-control" required="required" name="name_quarter" id="name_quarter">
                      <option>Select Season</option>
                      <?php foreach($fetch_term as $fetch_terms){ ?>
                        <option value="<?php echo $fetch_terms->term; ?>"><?php echo $fetch_terms->term; ?></option>
                      <?php } ?>
                    </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-6">
                   <div class="form-group">
                     <input type="text" class="form-control" id="bsname" name="bsname" placeholder="Basic skill name ...">
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
                  <div class="col-lg-12 col-12 table-responsive" style="height:20vh">
                    <input type="checkbox" class="" id="selectAllSubjectGS" onClick="selectAllSubject()">Select All
                    <div class="row"> 

                    <?php foreach($grade as $grades){ ?>
                      <div class="col-lg-2 col-4">
                        <div class="pretty p-bigger">
                         <input id="eva_grade" type="checkbox" name="grade" value="<?php echo $grades->grade; ?>">
                         <div class="state p-info">
                            <i class="icon material-icons"></i>
                            <label></label><?php echo $grades->grade; ?>
                         </div>
                       </div>
                     </div>
                    <?php } ?>
                    </div>
                  </div>
                  <div class="col-lg-2 col-12">
                    <button type="submit" name="postevaluation" class="btn btn-primary btn-block pull-right">Save
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
                  <div class="col-lg-2 col-5">
                    <div class="form-group">
                      <select class="form-control" required="required" name="bsname_quarter" id="bsname_quarter">
                      <option>Select Season</option>
                      <?php foreach($fetch_term as $fetch_terms){ ?>
                        <option value="<?php echo $fetch_terms->term; ?>"><?php echo $fetch_terms->term; ?></option>
                      <?php } ?>
                    </select>
                    </div>
                  </div>
                  <div class="col-lg-4 col-7">
                   <div class="form-group">
                     <input type="text" class="form-control" id="bsnamecate" name="bsnamecate" placeholder="Basic skill category ...">
                    </div>
                  </div>
                  <div class="col-lg-4 col-12 table-responsive" style="height:20vh">
                    <input type="checkbox" class="" id="selectAllBSCAte" onClick="selectAllCate()">Select All
                      <div class="row"> 
                      <?php foreach($grade as $grades){ ?>
                        <div class="col-lg-3 col-4">
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
                  <div class="col-lg-2 col-12">
                    <button type="submit" name="postCategory" class="btn btn-primary btn-block">Save Category
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
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  loadcatedata();
    function loadcatedata()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Basicskillteacher/fetchBsCategory/",
        method:"POST",
        beforeSend: function() {
          $('#bskillCategory').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#bskillCategory').html(data);
        }
      })
    }
  load_bstypedata();
    function load_bstypedata()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>Basicskillteacher/fetchBasicSkillsType/",
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
        url:"<?php echo base_url(); ?>Basicskillteacher/fetchConductType/",
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
        url:"<?php echo base_url(); ?>Basicskillteacher/fetchBasicSkills/",
        method:"POST",
        beforeSend: function() {
          $('#bskillDataShow').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
        },
        success:function(data){
          $('#bskillDataShow').html(data);
        }
      })
    }
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
          url: "<?php echo base_url(); ?>Basicskillteacher/updateCatLeftRow/",
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
          url: "<?php echo base_url(); ?>Basicskillteacher/updateCatRightRow/",
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
        url: "<?php echo base_url(); ?>Basicskillteacher/updateCatOrder/",
        data: ({
          suborder:suborder,
          subject:subject
        }),
        success: function(data) {
          iziToast.success({
            title: 'Subject Order',
            message: 'Updated successfully',
            position: 'topRight'
          });
        }
    });
  });
  $(document).ready(function(){
    $(document).on('click', '.editCATTeacher', function() {
      var category=$(this).attr('value');
      var quarter=$(this).attr('name');
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Basicskillteacher/editBsCategoryTeacher/",
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
    $(document).on('click', '.editCATDirector', function() {
      var category=$(this).attr('value');
      var quarter=$(this).attr('name');
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Basicskillteacher/editBsCategory/",
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
          url: "<?php echo base_url(); ?>Basicskillteacher/updateBsCategory/",
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
        url:"<?php echo base_url(); ?>Basicskillteacher/movingBs/",
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
          url: "<?php echo base_url(); ?>Basicskillteacher/insertBsCategory/",
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
          url: "<?php echo base_url(); ?>Basicskillteacher/deleteSpecificBsCategory/",
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
          url: "<?php echo base_url(); ?>Basicskillteacher/insertConType/",
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
          url: "<?php echo base_url(); ?>Basicskillteacher/deleteConType/",
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
        url: "<?php echo base_url(); ?>Basicskillteacher/insertBsType/",
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
      url: "<?php echo base_url(); ?>Basicskillteacher/editBsType/",
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
      url: "<?php echo base_url(); ?>Basicskillteacher/updateBsType/",
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
          url: "<?php echo base_url(); ?>Basicskillteacher/deleteBsType/",
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
        url: "<?php echo base_url(); ?>Basicskillteacher/",
        data: ({
          id: id,
          bsname:bsname,
          linkcategory:linkcategory,
          name_quarter:name_quarter
        }),
        cache: false,
        success: function(html){
          $('#save_baskill')[0].reset();
          load_data_bs_Names();
          iziToast.success({
            title: 'Saved successfully',
            message: '',
            position: 'topRight'
          });
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
        url: "<?php echo base_url(); ?>Basicskillteacher/updateBSOrderOrder/",
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
        url: "<?php echo base_url(); ?>Basicskillteacher/updateSpecificBSOrderOrder/",
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
        url: "<?php echo base_url(); ?>Basicskillteacher/updateBsNameCategory/",
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
  $(document).on('click', '.editbaskillTeacher', function() {
    var bs=$(this).attr('value');
    var quarter=$(this).attr('name');
    var category=$(this).attr('title');
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Basicskillteacher/editbaskill/",
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
  $(document).on('click', '.editbaskillDirector', function() {
    var bs=$(this).attr('value');
    var quarter=$(this).attr('name');
    var category=$(this).attr('title');
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>Basicskillteacher/editbaskill/",
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
      url: "<?php echo base_url(); ?>Basicskillteacher/updateBs/",
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
          url: "<?php echo base_url(); ?>Basicskillteacher/deleteSpecificBsName/",
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
      url: "<?php echo base_url(); ?>Basicskillteacher/putOnSubjectRow/",
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
      url: "<?php echo base_url(); ?>Basicskillteacher/deleteputOnSubjectRow/",
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
          url: "<?php echo base_url(); ?>Basicskillteacher/",
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