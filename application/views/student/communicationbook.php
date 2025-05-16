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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php  echo base_url(); ?>assets/css/pretty-checkbox.min.css">
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
            <div class="card">
              <div class="card-body StudentViewTextInfo">
                <div class="row">
                  <div class="col-lg-12 col-12">
                
                  <?php $usergroupPermission1="SELECT * from usergrouppermission where usergroup=? and tableName=? and allowed=? order by id ASC ";
                  $usergroupPermission=$this->db->query($usergroupPermission1,array($_SESSION['usertype'],'CommunicationBook','sendcommunicationbook'));
                  if($usergroupPermission->num_rows()>0){ ?>
                    <a href="#" class="new_communicationBook" value="" data-toggle="modal" data-target="#new_communicationBook"><span class="text-black">
                      <button class="btn btn-primary pull-right"><i data-feather="plus-circle"> </i>Create New</button> </span>
                    </a>
                  <?php }  ?>
                </div>
                 <div class="col-lg-12 col-12">
                  <div id="mycomSubjectList"></div>
                  <input type="hidden" class="txt_csrfname_gs" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>"><br>
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
  <div class="modal fade" id="new_communicationBook" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Communication Book</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="createNew_CommunicationBook"> </div> 
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <a id="saveskygrade"></a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script type="text/javascript">
    $('.student-communication-board').addClass('active');
    $(document).ready(function(){
      var csrfName = $('.txt_csrfname_gs').attr('name'); // Value specified in $config['csrf_token_name']
      var csrfHash = $('.txt_csrfname_gs').val(); // CSRF hash
      loadSubjectData();
      function loadSubjectData()
      {
        $.ajax({
          url:"<?php echo base_url(); ?>mycommunicationbook/fetchcomMySubject/",
          method:"POST",
          data: ({
            [csrfName]:csrfHash
          }),
          dataType:'json',
          beforeSend: function() {
            $('#mycomSubjectList').html( 'Loading subject<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success:function(data){
            $('#mycomSubjectList').html(data.subject);
            $('.txt_csrfname_gs').val(data.token);
          }
        });
      }
      $(document).on('click', '.backTo_myMainPage', function()
      {
        var csrfName = $('.txt_csrfname_gs').attr('name'); // Value specified in $config['csrf_token_name']
        var csrfHash = $('.txt_csrfname_gs').val(); // CSRF hash
        $.ajax({
          url:"<?php echo base_url(); ?>mycommunicationbook/fetchcomMySubject/",
          method:"POST",
          data: ({
            [csrfName]:csrfHash
          }),
          dataType:'json',
          beforeSend: function() {
            $('#mycomSubjectList').html( 'Loading subject<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">');
          },
          success:function(data){
            $('#mycomSubjectList').html(data.subject);
            $('.txt_csrfname_gs').val(data.token);
          }
        })
      });
    });
    $(document).on('click', '#viewThisSubjectComBook', function() {
      var csrfName = $('.txt_csrfname_gs').attr('name'); // Value specified in $config['csrf_token_name']
      var csrfHash = $('.txt_csrfname_gs').val(); // CSRF hash
      var subject=$(this).attr('value');
      if($(this).attr('value')!=''){
        $.ajax({
          method: "POST",
          url: "<?php echo base_url(); ?>Mycommunicationbook/fetchMyCommBook/",
          data: ({
            subject:subject,
            [csrfName]:csrfHash
          }),
          cache: false,
          dataType:'json',
          beforeSend: function() {
            $('#mycomSubjectList').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
          },
          success: function(html){
            $('#mycomSubjectList').html(html.result);
            $('.txt_csrfname_gs').val(html.token);
          }
        });
      }
    });
    $(document).on('click', '.sendMyNewComBookReply', function() {
      var csrfName = $('.txt_csrfname_gs').attr('name'); // Value specified in $config['csrf_token_name']
      var csrfHash = $('.txt_csrfname_gs').val(); // CSRF hash
      teacherID=[];
      $("input[name='sendindNewComBookStatus']:checked").each(function(i){
        teacherID[i]=$(this).val();
      });
      var text=$('#sendMyNewComBookReplyText').val();
      if($("#sendMyNewComBookReplyText").val() !=='' && teacherID.length!=0 ){
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
              url: "<?php echo base_url(); ?>Mycommunicationbook/saveCommunicationBook/",
              data: ({
                teacherID: teacherID,
                text:text,
                [csrfName]:csrfHash
              }),
              cache: false,
              dataType:'json',
              beforeSend: function() {
                $('#sendindNewComBookStatus').html( 'Sending<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
              },
              success: function(html) {
                $('.txt_csrfname_gs').val(html.token);
                $("#sendindNewComBookStatus").html(html.response);
              }
            });
          }
        });
      }else{
        swal({
          title: 'Please select all necessary fields',
          text: '',
          icon: 'warning',
          dangerMode: true,
        })
      }
    });
    $(document).ready(function() { 
      var csrfName = $('.txt_csrfname_gs').attr('name'); // Value specified in $config['csrf_token_name']
      var csrfHash = $('.txt_csrfname_gs').val(); // CSRF hash
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>Mycommunicationbook/createNew_CommunicationBook/",
        data: ({
          [csrfName]:csrfHash
        }),
        dataType:'json',
        beforeSend: function() {
          $('.createNew_CommunicationBook').html( 'Loading<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">'
            );
        },
        success: function(data) {
          $(".createNew_CommunicationBook").html(data.response);
          $('.txt_csrfname_gs').val(data.token);
        }
      });
    });
    $(document).on('click', '.sendMyReply', function() {
      var csrfName = $('.txt_csrfname_gs').attr('name'); // Value specified in $config['csrf_token_name']
      var csrfHash = $('.txt_csrfname_gs').val(); // CSRF hash
      var comID = $(this).attr("value");
      var replyText=$("#replayComText"+comID).val();
      var subject=$(this).attr("data-subject");
      var branch=$(this).attr("data-branch");
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
              url: "<?php echo base_url(); ?>Mycommunicationbook/replyComBook/",
              data: ({
                comID: comID,
                replyText:replyText,
                [csrfName]:csrfHash,
                subject:subject
              }),
              dataType:'json',
              beforeSend: function() {
                $('#replyedTextHere'+comID ).html( 'Sending<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="24" height="24" id="loa">' );
              },
              success: function(html) {
                $("#replyedTextHere"+comID).html(html.response);
                $("#replayComText"+comID).val('');
                $('.txt_csrfname_gs').val(html.token);
              }
            });
          }
        });
      }
    });
  </script>
</body>

</html>