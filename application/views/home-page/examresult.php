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
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>/logo/<?php echo $school->logo;?>' />
  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'> 
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
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab1" data-toggle="tab" href="#examResult" role="tab" aria-selected="true"> Exam Result</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#notCompleted" role="tab" aria-selected="false"> Report</a>
                      </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                      <div class="tab-pane fade show active" id="examResult" role="tabpanel" aria-labelledby="home-tab1">
                        <div class="row">
                          <div class="col-lg-12 col-12">
                            <button class="btn btn-default pull-right" name="gethisreport" onclick="codespeedy()">
                              <span class="text-black">
                              <i data-feather="printer"></i>
                              </span>
                            </button>
                          </div>
                        </div>
                        <div class="table-responsive" id="helloHere">
                          <h5 class="text-center"><u><?php echo $school->name;?> Online exam result</u></h5>
                          <table class="tabler table-striped table-hover" id="tableExport" style="width:100%;">
                            <thead>
                              <tr>
                                <th>Student Name</th>
                                <th>Branch</th>
                                <th>Grade</th>
                                <th>Subject</th>
                                <th>Exam Name</th>
                                <th>Result</th>
                                <th>A.Year</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php 
                            foreach($examresult as $result){
                              $correctAnswer=0;$total_weight=0;
                              $ac=$result->academicyear;
                              $subject=$result->subject;
                              $examname=$result->examname;
                              $grade=$result->grade;
                              $ques=$result->eid;
                              $id=$result->id;?>
                              <tr>
                                <td><?php echo $result->fname; echo ' ' ;echo $result->mname; echo ' ' ;echo $result->lname;?>
                                </td>
                                <td><?php echo $result->branch; ?></td>
                                <td><?php echo $result->gradesec; ?></td>
                                <td><?php echo $result->subject; ?></td>
                                <td><?php echo $result->examname; ?></td>
                                <?php $queryGroup = $this->db->query("select * from exam where subject='$subject' and examname='$examname' and academicyear='$ac' and grade='$grade' group by examGroup ");
                                if($queryGroup->num_rows()>0){
                                  foreach ($queryGroup->result() as $kvalueW) {
                                    $weight=$kvalueW->question_weight;
                                    $total_weight=$total_weight + $weight;
                                    $orginalAnswer=$kvalueW->answer;
                                    $examGroup=$kvalueW->examGroup;
                                    $query = $this->db->query("select * from examanswer where subject='$subject' and examname='$examname' and ques=$examGroup and academicyear='$ac' and sid='$id' ");
                                    foreach ($query->result() as $kvalue) {
                                      $answ2=$kvalue->ans;
                                      if(strcasecmp($orginalAnswer, $answ2) === 0 && trim($orginalAnswer) === trim($answ2) && strcmp($orginalAnswer, $answ2) === 0){
                                        $correctAnswer=$correctAnswer + $weight;
                                      }?>
                                    <?php } 
                                  }?>
                                  <td class="text-center"><?php echo $correctAnswer;echo '/'; echo $total_weight; ?><div class="bullet"></div>
                                  <a href="#" data-toggle="modal" data-target="#view_ThisExamDetail" id="<?php echo $subject; ?>" value="<?php echo $ac; ?>" name="<?php echo $id; ?>" title="<?php echo $examname; ?>" class="text-info viewThisExamDetail" value="">View Detail</a></td>
                                  <td><?php echo $result->academicyear; ?></td>
                                <?php }else{ ?>
                                  <td>-</td>
                                  <td>-</td>
                                  <td><?php echo $result->academicyear; ?></td>
                                <?php } ?>
                              </tr>
                             <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="tab-pane fade show" id="notCompleted" role="tabpanel" aria-labelledby="home-tab2">
                        <div class="table-responsive card-body StudentViewTextInfo" id="notCompletedReport">                 
                          <table class="display dataTable" id='empTable_Report' style="width:100%;">
                            <thead>
                             <tr>
                              <th>Student Name</th>         
                               <th>Tried Subject</th>
                               <th>Exam Name</th>
                               <th>Tried Date</th>
                               <th>Action</th>
                              </tr>
                            </thead>
                          </table> 
                        </div>
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
  <div class="modal fade" id="view_ThisExamDetail" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="addSubject_new_exam">View Exam Detail </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-header">
          <div class="modal-bodyd" id="fetch_exam_detail"></div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
</body>
  <script type="text/javascript">
    $(document).on('click', '.viewThisExamDetail', function() {
      var subject=$(this).attr("id");
      var examName=$(this).attr("title");
      var academicyear=$(this).attr("value");
      var stuid=$(this).attr("name");
      $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>examresult/fetch_student_detail_result/",
        data: ({
          subject: subject,
          examName:examName,
          academicyear:academicyear,
          stuid:stuid
        }),
        cache: false,
        beforeSend: function() {
          $('#fetch_exam_detail').html( 'Loading...<img src="<?php echo base_url(); ?>/img/loader.gif" alt="" width="34" height="34" id="loa">' );
        },
        success: function(html){
          $('#fetch_exam_detail').html(html);
        }
      });       
    });
    $(document).ready(function(){
      $('#empTable_Report').DataTable({
        'processing': true,
        'serverSide': true,
        "dataType": "json",
        'serverMethod': 'post',
        'ajax': {
          'url':'<?=base_url()?>examresult/not_completedReport/'
        },
        'columns': [
          { data: 'fname' },
          { data: 'triedsubject' },
          { data: 'triedexam' },
          { data: 'datetried' },
          { data: 'Action' },
        ]
      });
    });
    $(document).on('click', '#allowtotrayagain', function() {
      swal({
        title: 'Are you sure?',
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          var requestid=$(this).attr("value");
          $.ajax({
            method: "POST",
            url: "<?php echo base_url(); ?>examresult/retryExam/",
            data: ({
              requestid: requestid
            }),
            cache: false,
            success: function(html){
              $('#empTable_Report').DataTable().ajax.reload();
            }
          }); 
        }
      });
    });
    function codespeedy(){
      var print_div = document.getElementById("helloHere");
      var print_area = window.open();
      print_area.document.write(print_div.innerHTML);
      print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" type="text/css" />');
      print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css" type="text/css" />');
      print_area.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" type="text/css" />');
      print_area.document.close();
      print_area.focus();
      print_area.print();
    }
  </script>
</html>