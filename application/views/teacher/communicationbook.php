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
  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/izitoast/css/iziToast.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/gs-custom.css">
</head>

<body>
  <div class="loader"></div>
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
           <?php include('bgcolor.php'); ?>
            <input type="hidden" id="bgcolor_now" value="<?php echo $sid ?>">          
            <div class="row">
              <div class="col-12">
                <div class="grade_list_div"> </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>
    </div>
  </div>
  <div class="modal fade" id="AddNewCommunicationBook" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Send new communication book</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="dropdown-divider"></div>
        <div class="fetch_commbook_form"></div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="editNewCommunicationBook" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit communication book</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="dropdown-divider"></div>
        <div class="fetch_commbook_form_toedit"></div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/summernote/summernote-bs4.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/izitoast/js/iziToast.min.js"></script>
  <script type="text/javascript">
    $(document).on('click', '.AddNewCommunicationBook', function() {
      var academicyear=$(this).attr('data-year');
      var subject=$(this).attr('value');
      var grade=$(this).attr('id');
      var branch=$(this).attr('data-branch');
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>communicationbookteacher/fetch_communication_book_form/",
        data: ({
          academicyear:academicyear,
          grade:grade,
          subject:subject,
          branch: branch
        }),
        cache: false,
        beforeSend: function() {
          $('.fetch_commbook_form').html( '<span class="text-success">Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa"></span>' );
        },
        success: function(html){
          $('.fetch_commbook_form').html(html);
        }
      });
    });
    $(document).ready(function() {
      load_grade_to_commbook();
      function load_grade_to_commbook()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>communicationbookteacher/load_grade_to_commbook/",
          method:"POST",
          beforeSend: function() {
            $('.grade_list_div').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success:function(data){
            $('.grade_list_div').html(data);
          }
        })
      }
      $(document).on('click', '.backTo_MainPage', function()
      {
        load_grade_to_commbook();
      });
    }); 
    $(document).on('click', '.startfetchingCommBook_subject', function() {
      var academicyear=$(this).attr('id');
      var grade=$(this).attr('value');
      var branch=$(this).attr('name');
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>communicationbookteacher/fetch_subject_of_thisGrade/",
        data: ({
          academicyear:academicyear,
          grade:grade,
          branch: branch
        }),
        cache: false,
        beforeSend: function() {
          $('.grade_list_div').html( '<h3><span class="text-success">Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa"></span></h3>' );
        },
        success: function(html){
          $('.grade_list_div').html(html);
        }
      });
    });
    $(document).on('click', '.starttypingCommBook_student', function() {
      var subject=$(this).attr('id');
      var grade=$(this).attr('value');
      var branch=$(this).attr('name');
      var year=$(this).attr('data-year');
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>communicationbookteacher/fetch_comBookhistory_of_thisGrade/",
        data: ({
          subject:subject,
          grade:grade,
          branch: branch,
          year:year
        }),
        cache: false,
        beforeSend: function() {
          $('.grade_list_div').html( '<h3><span class="text-success">Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa"></span></h3>' );
        },
        success: function(html){
          $('.grade_list_div').html(html);
        }
      });
    });
    $(document).on('click', '.sendMyReply', function() {
      var comID = $(this).attr("value");
      var replyText=$("#replayComText"+comID).val();
      if($("#replayComText"+comID).val() !='' ){
        swal({
          title: 'Are you sure?',
          text: 'Once You send,you can not edit or delete the reply text.',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $.ajax({
              method: "POST",
              url: "<?php echo base_url(); ?>communicationbookteacher/replyComBook/",
              data: ({
                comID: comID,
                replyText:replyText
              }),
              cache: false,
              beforeSend: function() {
                $('#replyedTextHere'+comID ).html( 'Sending...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
              },
              success: function(html) {
                $("#replyedTextHere"+comID).html(html);
                $("#replayComText"+comID).val('');
              }
            });
          }
        });
      }
    });
    $(document).on('click', '.deleteThisComBook', function() {
      var comID = $(this).attr("value");
      swal({
        title: 'Are you sure?',
        text: 'Once You send,you want to delete this text.',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>communicationbookteacher/delete_commuication_bookText/",
            data: ({
              comID: comID
            }),
            cache: false,
            beforeSend: function() {
              $('#deleteThisComBook'+comID ).html( 'Deleting...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
            },
            success: function(html) {
              if(html=='1'){
                iziToast.success({
                  title: 'Communication book deleted successfully.',
                  message: '',
                  position: 'topRight'
                });
                $("#deleteThisComBook"+comID).fadeOut('slow');
              }else{
                iziToast.error({
                  title: 'Oooops Please try again later.',
                  message: '',
                  position: 'topRight'
                });
              }
            }
          });
        }
      });
    });
    $(document).on('click', '.editThisComBook', function() {
      var comID=$(this).attr('value');
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>communicationbookteacher/fetch_commbook_form_toedit/",
        data: ({
          comID:comID
        }),
        cache: false,
        beforeSend: function() {
          $('.fetch_commbook_form_toedit').html( '<span class="text-success">Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa"></span>' );
        },
        success: function(html){
          $('.fetch_commbook_form_toedit').html(html);
        }
      });
    });
    $(document).on('click', '.useThisText', function() {
      event.preventDefault();
      var oldText=$('#comNote').val();
      var stuID=$(this).attr("value");
      var newText=oldText+stuID+"\n";
      $("#comNote").val(newText);   
    });
  </script>
  <script type="text/javascript">
  $(document).on('submit', '#updateCommunicationForm', function(event) {
      event.preventDefault();
      var comGradesec=$('#updatedcomGradesec').val();
      var comSubject=$('#updatedcomSubject').val();
      var comBranch=$('#updatedcomBranch').val();
      var comYear=$('#updatedcomAcademicYear').val();
      var comNote=$('.updatedcomNote').val();
      var comID=$('#updatedcomID').val();
      if($.trim($('.updatedcomNote').val()).length < 1)
      {
        swal('Please select all necessary fields. ', {
          icon: 'error',
        });
      }else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>communicationbookteacher/updateCommunicationBook/",
          data: ({
            comID:comID,
            comGradesec: comGradesec,
            comSubject:comSubject,
            comNote:comNote
          }),
          cache: false,
          success: function(html){
            if(html=='1'){
              iziToast.success({
                title: 'Communication book updated successfully.',
                message: '',
                position: 'topRight'
              });
              load_subject_coomBook_history(comGradesec,comSubject,comBranch,comYear);
              $('#editNewCommunicationBook'). modal('hide');
            }else{
              iziToast.error({
                title: 'Something wrong, please try again.',
                message: '',
                position: 'topRight'
              });
            }
          }
        });
      }
    });    
    $(document).on('submit', '#saveCommunicationForm', function(event) {
      event.preventDefault();
      var comGradesec=$('#comGradesec').val();
      var comSubject=$('#comSubject').val();
      var comBranch=$('#comBranch').val();
      var comYear=$('#comAcademicYear').val();
      var comNote=$('.comNote').val();
      stuName=[];
      $("input[name='stuNameComBook']:checked").each(function(i){
        stuName[i]=$(this).val();
      });
      if( stuName.length==0 || $.trim($('.comNote').val()).length < 1)
      {
        swal('Please select all necessary fields. ', {
          icon: 'error',
        });
      }else{
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>communicationbookteacher/saveCommunicationBook/",
          data: ({
            comGradesec: comGradesec,
            comSubject:comSubject,
            stuName:stuName,
            comNote:comNote
          }),
          cache: false,
          success: function(html){
            if(html=='1'){
              iziToast.success({
                title: 'Communication book sent successfully.',
                message: '',
                position: 'topRight'
              });
              load_subject_coomBook_history(comGradesec,comSubject,comBranch,comYear);
              $('#AddNewCommunicationBook'). modal('hide');
            }else{
              iziToast.error({
                title: 'Something wrong, please try again.',
                message: '',
                position: 'topRight'
              });
            }
          }
        });
      }
    }); 
    function load_subject_coomBook_history(comGradesec,comSubject,comBranch,comYear)
    {
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>communicationbookteacher/fetch_comBookhistory_of_thisGrade/",
        data: ({
          subject:comSubject,
          grade:comGradesec,
          branch: comBranch,
          year:comYear
        }),
        cache: false,
        beforeSend: function() {
          $('.grade_list_div').html( '<h3><span class="text-success">Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa"></span></h3>' );
        },
        success: function(html){
          $('.grade_list_div').html(html);
        }
      });
    }
  </script>
  <script type="text/javascript">
    function selectAllCom(){
      var itemsall=document.getElementById('selectall');
      if(itemsall.checked==true){
        var items=document.getElementsByName('stuNameComBook');
        for(var i=0;i < items.length;i++){
          items[i].checked=true;
        }
      }
      else{
        var items=document.getElementsByName('stuNameComBook');
        for(var i=0;i < items.length;i++){
          items[i].checked=false;
        }
      }
    }
</script>
</body>

</html>