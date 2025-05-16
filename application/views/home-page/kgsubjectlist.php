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
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <?php include('bgcolor.php'); ?>
                <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">
                <?php if($_SESSION['usertype']===trim('superAdmin')){ ?>
                  <div class="enable_week_categories"></div>
                <?php } ?>
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link show active" id="home-tab01" data-toggle="tab" href="#subject_main_category" role="tab" aria-selected="true">የጭብጥ ስም</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link show" id="home-tab0" data-toggle="tab" href="#subjectCategory" role="tab" aria-selected="true">የመማርያ ዓዉድ</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab1" data-toggle="tab" href="#kgsubject_list" role="tab" aria-selected="false">የመማርያ ዓዉድ ዝርዝር </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#subject_list_name_symbol" role="tab" aria-selected="false">የምልከታ ስም</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="home-tab3" data-toggle="tab" href="#subject_list_name_sheet" role="tab" aria-selected="false">የማርክ ወረቀት</a>
                  </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                  <div class="tab-pane fade show active" id="subject_main_category" role="tabpanel" aria-labelledby="home-tab01">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="addNewCategory" value="" data-toggle="modal" data-target="#start_add_new_category"><span class="text-success">
                        <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add New</button>
                       </span>
                       </a>
                      </div>
                    </div>
                    <div class="fect_subject_main_category" id="fect_subject_main_category"></div>
                  </div>
                  <div class="tab-pane fade show" id="subjectCategory" role="tabpanel" aria-labelledby="home-tab0">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="addNewSubCategory" value="" data-toggle="modal" data-target="#start_add_new_sub_category"><span class="text-success">
                        <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add New</button>
                       </span>
                       </a>
                      </div>
                    </div>
                    <div id="bskillCategory"> </div>
                  </div>
                  <div class="tab-pane fade show" id="kgsubject_list" role="tabpanel" aria-labelledby="home-tab1">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="addNewSubListCategory" value="" data-toggle="modal" data-target="#start_add_new_sub_list_category"><span class="text-success">
                        <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add New</button>
                       </span>
                       </a>
                      </div>
                    </div>
                    <div id="bskillDataShow"> </div>
                  </div>
                  <div class="tab-pane fade show" id="subject_list_name_symbol" role="tabpanel" aria-labelledby="home-tab2">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <a href="#" class="addNewSubListCategoryValue" value="" data-toggle="modal" data-target="#start_add_new_sub_list_value"><span class="text-success">
                        <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Add New</button>
                       </span>
                       </a>
                      </div>
                    </div>
                    <div id="bskilltype">  </div>
                  </div>
                  <div class="tab-pane fade show" id="subject_list_name_sheet" role="tabpanel" aria-labelledby="home-tab3">
                    <div class="row">
                      <div class="col-lg-12 col-12">
                        <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedySheet()">
                        <span class="text-black">
                          <i data-feather="printer"></i>
                        </span>
                        </button>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                          <select class="form-control selectric" required="required" name="reportaca2" id="reportaca2">
                            <option>--Year--</option>
                            <?php foreach($academicyear as $academicyears){ ?>
                              <option value="<?php echo $academicyears->year_name;?>">
                             <?php echo $academicyears->year_name;?>
                              </option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                         <select class="form-control selectric" required="required" name="branch2" id="grands_branchit2">
                         <option> --- Select Branch --- </option>
                         </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="form-group">
                         <select class="form-control"
                         required="required" name="gradesec2" id="grands_gradesec2">
                         <option> --- Grade --- </option>
                         </select>
                        </div>
                      </div>
                      <div class="col-lg-3 col-6">
                        <select class="form-control" required="required" name="category_forSheet" id="category_forSheet">
                          <option>Select Category</option>
                          <?php foreach($fetch_season as $fetch_seasons){ ?>
                            <option value="<?php echo $fetch_seasons->sub_name; ?>"><?php echo $fetch_seasons->sub_name; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-lg-12 col-12">
                        <button type="button" class="btn btn-primary pull-right" id="fetch_sheet">View Sheet</button>
                      </div>
                    </div>
                    <div class="sheet_view_page" id="sheet_view_page"></div>
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
  <div class="modal fade" id="start_add_new_category" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add New Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
            <div class="card-body">
              <div class="modal-body">
                <form method="POST" id="save_new_season_name" class="save_new_season_name" name="save_new_season_name">
                  <div class="form-group">
                    <div class="search-element">
                      <div class="row">
                        <div class="form-group col-lg-4 col-6">
                          <input id="kg_season_name" type="text" class="form-control" required="required" name="kg_season_name" placeholder="የጭብጥ ስም...">
                        </div>
                        <div class="col-lg-5 col-6 table-responsive" style="height:20vh">
                          <div class="form-group">
                            <label for="Mobile"></label>
                            <div class="row"> 
                              <?php foreach($grade as $grades){ ?>
                                <div class="col-lg-4 col-6">
                                  <div class="pretty p-icon p-bigger">
                                   <input id="header_grade" type="checkbox" name="header_grade" value="<?php echo $grades->grade; ?>">
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
                        <div class="form-group col-lg-3 col-12">
                          <button class="btn btn-primary btn-block" name="save_season_name" id="save_season_name">
                            <i class="fas fa-save"></i> Save
                          </button>
                        </div>
                      </div>
                      <h4 class="msg" id="msg"></h4>
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
  <div class="modal fade" id="start_add_new_sub_category" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add New</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
            <div class="card-body">
              <div class="modal-body">
                <form method="POST" id="save_baskillCate" class="StudentViewTextInfo">
                  <div class="row">
                    <div class="col-lg-3 col-6">
                      <select class="form-control" required="required" name="category_term" id="category_term">
                        <option>Select Category</option>
                        <?php foreach($fetch_season as $fetch_seasons){ ?>
                          <option value="<?php echo $fetch_seasons->sub_name; ?>"><?php echo $fetch_seasons->sub_name; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="col-lg-4 col-6 table-responsive" style="height:20vh">
                      <div class="grade_list_names" id="grade_list_names"></div>
                    </div>
                    <div class="col-lg-5 col-12">
                     <div class="form-group">
                       <input type="text" class="form-control" id="name_category" name="name_category" placeholder="የመማርያ ዓዉድ ...">
                      </div>
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
  <div class="modal fade" id="start_add_new_sub_list_category" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add New</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
            <div class="card-body">
              <div class="modal-body">
                <form method="POST" id="save_subject_list">
                  <div class="row">
                    <div class="col-lg-3 col-6">
                      <select class="form-control" required="required" name="category_term_sub" id="category_term_sub">
                        <option>Select Category</option>
                        <?php foreach($fetch_season as $fetch_seasons){ ?>
                          <option value="<?php echo $fetch_seasons->sub_name; ?>"><?php echo $fetch_seasons->sub_name; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="col-lg-3 col-6">
                      <div class="form-group">
                        <select class="form-control" required="required" name="link_subject_category" id="link_subject_category">
                        <option>Category</option>
                        <?php foreach($scategory as $bscategorys){ ?>
                          <option value="<?php echo $bscategorys->category_name; ?>"><?php echo $bscategorys->category_name; ?></option>
                        <?php } ?>
                      </select>
                      </div>
                    </div>
                    <div class="col-lg-4 col-12 table-responsive" style="height:20vh">
                      <div class="grade_subject_list_name" id="grade_subject_list_name"></div>
                    </div>
                    <div class="col-lg-8 col-8">
                     <div class="form-group">
                       <input type="text" class="form-control" id="subject_list_name" name="subject_list_name" placeholder="የመማርያ ዓዉድ ዝርዝር ስም ...">
                      </div> 
                    </div>
                    
                      <?php $this->db->where('academicyear',$max_year);
                      $this->db->where('enable_status','1');
                      $query=$this->db->get('kg_chibt_week_category');
                      if($query->num_rows()>0){ ?>
                        <div class="col-lg-4 col-4">
                          <select class="form-control" required="required" name="link_week_category" id="link_week_category">
                            <option> </option>
                            <option>Week 1</option>
                            <option>Week 2</option>
                            <option>Week 3</option>
                            <option>Week 4</option>
                            <option>Week 5</option>
                            <option>Week 6</option>
                            <option>Week 7</option>
                            <option>Week 8</option>
                            <option>Week 9</option>
                            <option>Week 10</option>
                          </select>
                        </div>
                        <div class="col-lg-12 col-12">
                          <button type="submit" name="postevaluation" class="btn btn-primary pull-right">Save List </button>
                        </div>
                      <?php } else{?>
                      <div class="col-lg-4 col-4">
                        <button type="submit" name="postevaluation" class="btn btn-primary btn-block">Save List </button>
                      </div>
                    <?php }?>
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
  <div class="modal fade" id="start_add_new_sub_list_value" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add New</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card">  
            <div class="card-body">
              <div class="modal-body">
                <div class="row">
                  <div class="col-lg-4 col-6">
                    <div class="form-group">
                     <input class="form-control" id="subject_value_name" name="subject_value_name" type="text" placeholder="የምልከታ ስም...">
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="form-group">
                     <input class="form-control" id="subject_value_percentage" name="subject_value_percentage" type="number" placeholder="የሚይዘው ውጤት...">
                    </div>
                  </div>
                  <div class="col-lg-5 col-12">
                    <div class="form-group">
                     <input class="form-control" id="subject_value_list" name="subject_value_list" type="text" placeholder="የምልከታ ዝርዝር...">
                    </div>
                  </div>
                  
                  <div class="col-lg-8 col-12 table-responsive" style="height:15vh">
                    <div class="form-group">
                      <label for="Mobile"></label>
                      <div class="row">
                        <?php foreach($grade as $grades){ ?>
                          <div class="col-lg-2 col-4">
                            <div class="pretty p-icon p-bigger">
                             <input id="subject_value_grade" type="checkbox" name="subject_value_grade" value="<?php echo $grades->grade; ?>">
                             <div class="state p-success">
                                <i class="icon material-icons"></i>
                                <label></label><?php echo $grades->grade; ?>
                             </div>
                           </div>
                         </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-12">
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
  <!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
<script type="text/javascript">
  function codespeedySheet(){
    var print_div = document.getElementById("sheet_view_page");
    var print_area = window.open();
    print_area.document.write(print_div.innerHTML);
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
    print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
    print_area.document.close();
    print_area.focus();
    print_area.print();
  }
  $('#fetch_sheet').on('click', function(event) {
    event.preventDefault();
    var bsnamecate=$('#category_forSheet').val();
    var branch=$('#grands_branchit2').val();
    var grade=$('#grands_gradesec2').val();
    var year=$('#reportaca2').val();
    if($('#category_forSheet').val() =='' || $('#reportaca2').val() =='--Year--')
    {
      swal('Oooops, Please select all necessary fields.!', {
        icon: 'error',
      });
    }else{
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>kgsubjectlist/fetch_sheet/",
        data: ({
          bsnamecate:bsnamecate,
          branch:branch,
          grade:grade,
          year:year
        }),
        cache: false,
        beforeSend: function() {
          $('.sheet_view_page').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('.sheet_view_page').html(html);
        }
      });
    }
  });
  $(document).ready(function() {  
    $("#grands_branchit2").bind("change", function() {
      var branchit=$("#grands_branchit2").val();
      var academicyear=$("#reportaca2").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>kgsubjectlist/filterGradefromBranch/",
        data: ({
          branchit: branchit,
          academicyear:academicyear
        }),
        beforeSend: function() {
          $('#grands_gradesec2').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_gradesec2").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#reportaca2").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>kgsubjectlist/filterGradesecfromBranch/",
        data: "academicyear=" + $("#reportaca2").val(),
        beforeSend: function() {
          $('#grands_branchit2').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grands_branchit2").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    enable_week_categories();
    function enable_week_categories() {
      $.ajax({
        method:'POST',
        url:'<?php echo base_url() ?>kgsubjectlist/enable_week_categories/',
        cache: false,
        beforeSend: function() {
          $('.enable_week_categories').html( 'Checking<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">'
          );
        },
        success: function(html){
         $('.enable_week_categories').html(html);
        }
      })
    }
  });
  $(document).on('click', "input[name='kg_chibt_week_category']", function() {
    var lockmarkk=$(this).attr("value");
    var academicyear=$(this).attr("id");
    if($(this).is(':checked')){
      $.ajax({
        url:"<?php echo base_url() ?>kgsubjectlist/enable_week_category/",
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
              title: 'Changes updated successfully.',
              message: '',
              position: 'topRight'
            });
            window.location.reload();
          }else{
            iziToast.error({
              title: 'Changes not updated. Please try again',
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
        url:"<?php echo base_url() ?>kgsubjectlist/disable_week_category/",
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
          }else{
            iziToast.error({
              title: 'Changes not updated. Please try again',
              message: '',
              position: 'topRight'
            });
          }
          
        }
      });
    }
  });

  /**/
  $(document).ready(function() {  
    $("#category_term_sub").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>kgsubjectlist/fetch_category_list_Names_grands/",
        data: "grade_list=" + $("#category_term_sub").val(),
        beforeSend: function() {
          $('#link_subject_category').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#link_subject_category").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#link_subject_category").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>kgsubjectlist/fetch_grade_list_Names_grands/",
        data: "grade_list=" + $("#link_subject_category").val(),
        beforeSend: function() {
          $('#grade_subject_list_name').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grade_subject_list_name").html(data);
        }
      });
    });
  });
  $(document).ready(function() {  
    $("#category_term").bind("change", function() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>kgsubjectlist/fetch_grade_list_Names/",
        data: "grade_list=" + $("#category_term").val(),
        beforeSend: function() {
          $('#grade_list_names').html( '<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(data) {
          $("#grade_list_names").html(data);
        }
      });
    });
  });
  load_subject_main_category();
  function load_subject_main_category()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>kgsubjectlist/load_subject_main_category/",
      method:"POST",
      beforeSend: function() {
        $('#fect_subject_main_category').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
      },
      success:function(data){
        $('#fect_subject_main_category').html(data);
      }
    })
  }
  $(document).on('click', '.delete_sub_header', function() {
    var delte_id=$(this).attr("value");
    swal({
      title: 'Are you sure you want to delete this header?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>kgsubjectlist/delete_subject_Header/",
          data: ({
            delte_id: delte_id
          }),
          cache: false,
          success: function(html){
            $('.delete_sub_header' + delte_id).fadeOut('slow');
            load_subject_main_category();
          }
        });
      }
    });
  });
  $(document).on('submit', '#save_new_season_name', function(e) {
    e.preventDefault();
    if ($('#kg_season_name').val() != '') {
      var header_name=$('#kg_season_name').val();
      id=[];
      $("input[name='header_grade']:checked").each(function(i){
        id[i]=$(this).val();
      });
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>kgsubjectlist/save_new_season_name/",
        data: ({
          header_name:header_name,
          grade:id
        }),
        success: function(html){
          iziToast.success({
            title: html,
            message: '',
            position: 'topRight'
          });
          $('#kg_season_name').val('');
          load_subject_main_category();
        }
      });
    }else{
      swal('Please fill all fields!', {
        icon: 'error',
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).on('change', '.kg_subject_category_list', function() {
    var suborder=$(this).find('option:selected').attr('value');
    var subject=$(this).find('option:selected').attr('id');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>kgsubjectlist/updateCatOrder/",
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
</script>
<script type="text/javascript">
  $(document).ready(function(){
    loadcatedata();
    function loadcatedata()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>kgsubjectlist/fetchBsCategory/",
        method:"POST",
        beforeSend: function() {
          $('#bskillCategory').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success:function(data){
          $('#bskillCategory').html(data);
        }
      })
    }
    $('#save_baskillCate').on('submit', function(event) {
      event.preventDefault();
      var bsnamecate=$('#name_category').val();
      var category_term=$('#category_term').val();
      id=[];
      $("input[name='category_grade']:checked").each(function(i){
        id[i]=$(this).val();
      });
      if($('#name_category').val() =='' || id.length == 0 || $('#category_term').val() =='Select Term')
      {
        swal('Oooops, Please type category.!', {
          icon: 'error',
        });
      }else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>kgsubjectlist/insertBsCategory/",
          data: ({
            bsnamecate:bsnamecate,
            grade:id,
            category_term:category_term
          }),
          cache: false,
          success: function(html){
            $('#name_category').val('');
            $('#category_grade').prop('checked',false);
            loadcatedata();
          }
        });
      }
    });
  });
</script>
<script type="text/javascript">
  function loadcatedata()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>kgsubjectlist/fetchBsCategory/",
        method:"POST",
        beforeSend: function() {
          $('#bskillCategory').html( 'Loading Basic Skill Category...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success:function(data){
          $('#bskillCategory').html(data);
        }
      })
    }
  $(document).on('click', '.delete_sub_cat', function() {
    var delte_id=$(this).attr("value");
    swal({
      title: 'Are you sure you want to delete this category?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>kgsubjectlist/deleteBsCategory/",
          data: ({
            delte_id: delte_id
          }),
          cache: false,
          success: function(html){
            $('.delete_sub_cat' + delte_id).fadeOut('slow');
            loadcatedata();
          }
        });
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    load_bstypedata();
    function load_bstypedata()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>kgsubjectlist/subject_value_list/",
        method:"POST",
        beforeSend: function() {
          $('#bskilltype').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success:function(data){
          $('#bskilltype').html(data);
        }
      })
    }
    $(document).on('click', '#savebstype', function() {

      var bstype=$('#subject_value_name').val();
      var bstypedecs=$('#subject_value_list').val();
      var subject_percentage=$('#subject_value_percentage').val();
      id=[];
      $("input[name='subject_value_grade']:checked").each(function(i){
        id[i]=$(this).val();
      });
      if($('#bstype').val() =='' || id.length == 0)
      {
        swal('Oooops, Please type all necessary fields!', {
          icon: 'error',
        });
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>kgsubjectlist/insertBsType/",
        data: ({
          bstype:bstype,
          bstypedecs:bstypedecs,
          grade:id,
          subject_percentage:subject_percentage
        }),
        cache: false,
        success: function(html){
          $('#subject_value_name').val('');
          $('#subject_value_list').val('');
          load_bstypedata();
        }
      });
    }
  });
  $(document).on('click', '.editsubject_list_value', function() {
    var bstype=$(this).attr('value');
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>kgsubjectlist/editBsType/",
      data: ({
        bstype:bstype
      }),
      cache: false,
      beforeSend: function() {
        $('#bskilltype').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
      },
      success: function(data){
        $('#bskilltype').html(data);
      }
    });
  });
  $(document).on('click', '#savesub_Type', function() {
    var bstypeId=$(this).attr('value');
    var bstypeName=$('.sub_list_Info').val();
    var bstypeDesc=$('.sub_list_desc_Info').val();
    var bstypePercent=$('.sub_list_percentage').val();
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>kgsubjectlist/updateBsType/",
      data: ({
        bstypeId:bstypeId,
        bstypeName:bstypeName,
        bstypeDesc:bstypeDesc,
        bstypePercent:bstypePercent
      }),
      cache: false,
      success: function(data){
        load_bstypedata();
      }
    });
  });
});
   $(document).on('click', '.deletesubject_list_value', function() {
    var delte_id=$(this).attr("value");
    swal({
      title: 'Are you sure you want to delete this data value?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>kgsubjectlist/deleteBsType/",
          data: ({
            delte_id: delte_id
          }),
          cache: false,
          success: function(html){
            $('.delete_bs' + delte_id).fadeOut('slow');
            load_bstypedata();
          }
        });
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    load_data();
    function load_data()
    {
      $.ajax({
        url:"<?php echo base_url(); ?>kgsubjectlist/fetch_kg_subject_list_name/",
        method:"POST",
        beforeSend: function() {
          $('#bskillDataShow').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
        },
        success:function(data){
          $('#bskillDataShow').html(data);
        }
      })
    }
    $('#save_subject_list').on('submit', function(event) {
      event.preventDefault();
      var bsname=$('#subject_list_name').val();
      var linkcategory=$('#link_subject_category').val();
      var category_term_sub=$('#category_term_sub').val();
      var weekName=$('#link_week_category').val();
      id=[];
      $("input[name='grade_subject_list']:checked").each(function(i){
        id[i]=$(this).val();
      });
      if( id.length == 0 || $('#subject_list_name').val() =='')
      {
        swal('Oooops, Please select necessary fields!', {
          icon: 'error',
        });
      }else{
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>kgsubjectlist/save_subject_list/",
        data: ({
          id: id,
          bsname:bsname,
          linkcategory:linkcategory,
          category_term_sub:category_term_sub,
          weekName:weekName
        }),
        cache: false,
        success: function(html){
          $('#save_subject_list')[0].reset();
          load_data();
        }
      });
    }
  });
  $(document).on('click', '.edits_list_name', function() {
    var bs=$(this).attr('value');
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>kgsubjectlist/editlist_name/",
      data: ({
        bs:bs
      }),
      cache: false,
      beforeSend: function() {
        $('#bskillDataShow').html( 'Saving changes...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">');
      },
      success: function(data){
        $('#bskillDataShow').html(data);
      }
    });
  });
  $(document).on('click', '#save_list_nameInfo', function() {
    var bsInfo=$('.sub_listInfo').val();
    var bsnameInfo=$('#listnameInfo').val();
      $.ajax({
      method: "POST",
      url: "<?php echo base_url(); ?>kgsubjectlist/updateBs/",
      data: ({
        bsInfo:bsInfo,
        bsnameInfo:bsnameInfo
      }),
      cache: false,
      success: function(data){
        load_data();
      }
    });
  });
});
</script>
<script type="text/javascript">
  function load_data()
  {
    $.ajax({
      url:"<?php echo base_url(); ?>kgsubjectlist/fetch_kg_subject_list_name",
      method:"POST",
      beforeSend: function() {
        $('#bskillDataShow').html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="44" height="44" id="loa">');
      },
      success:function(data){
        $('#bskillDataShow').html(data);
      }
    })
  }
  $(document).on('click', '.deletes_list_name', function() {
    var delte_id = $(this).data('nid');   // Accessing the 'data-nid' attribute
    var delte_grade = $(this).data('sgrade');  // Accessing the 'data-sgrade' attribute

    swal({
      title: 'Are you sure you want to delete this List?',
      text: '',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>kgsubjectlist/delete_s_list_name",
          data: ({
            delte_id: delte_id,
            delte_grade:delte_grade
          }),
          cache: false,
          success: function(html){
            $('.delete_bs' + delte_id).fadeOut('slow');
            load_data();
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