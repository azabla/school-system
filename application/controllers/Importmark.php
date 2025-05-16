<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Importmark extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    $this->load->library('excel');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    $uaddMark=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='StudentMark' and allowed='addstudentmark' order by id ASC ");
    if($this->session->userdata('username') == '' || $uaddMark->num_rows() <1 || $userLevel!='2'){
      $this->session->set_flashdata("error","Please Login first");
      $this->load->driver('cache');
      delete_cookie('username');
      unset($_SESSION);
      session_destroy();
      $this->cache->clean();
      ob_clean();
      redirect('login/');
    } 
  }
	public function index($page='importmark')
	{
    if(!file_exists(APPPATH.'views/teacher/'.$page.'.php')){
     show_404();
    }
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select branch,id from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $approvedID=$row_branch->id;
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' ");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    $today=date('y-m-d');
    if(isset($_POST['insertmark']))
    {
      if(!empty($_FILES['addmark']["tmp_name"]))
      {
        $path = $_FILES["addmark"]["tmp_name"];
        $object = PHPExcel_IOFactory::load($path);
        foreach($object->getWorksheetIterator() as $worksheet)
        {
          $data=array();
          $data1=array();
          $highestRow = $worksheet->getHighestRow();
          $highestColumn = $worksheet->getHighestColumn();
          $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
          $subname=$worksheet->getCellByColumnAndRow(2,2)->getValue();
          $quarter =$worksheet->getCellByColumnAndRow(1,2)->getValue();
          $gradesec = $worksheet->getCellByColumnAndRow(1,1)->getValue();
          $mybranch = $worksheet->getCellByColumnAndRow(2,1)->getValue();
          if($quarter!='' && $gradesec!='' && $mybranch!=''){
            for($col=3;$col <= $highestColumnIndex;$col++)
            {
              $evaid = $worksheet->getCellByColumnAndRow($col,2)->getValue();
              $outof = $worksheet->getCellByColumnAndRow($col,3)->getValue();
              $markname = $worksheet->getCellByColumnAndRow($col,1)->getValue();
              $query_check=$this->main_model->check_import_markm2($markname,$subname,$quarter,$max_year,$gradesec,$mybranch);
              if($query_check && $outof!='' && $markname!=''){
                for($row=4; $row <= $highestRow; $row++)
                {
                  $stuid = $worksheet->getCellByColumnAndRow(0,$row)->getValue();
                  $zeromarkinfo= $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                  if($worksheet->getCellByColumnAndRow($col,$row)!='')
                  {
                    $value1=$worksheet->getCellByColumnAndRow($col,$row)->getValue();
                    $value2=$worksheet->getCellByColumnAndRow($col,3)->getValue();
                    if($value1 > $value2 )
                    {
                      $value=0;
                    }
                    else
                    {
                      $value = $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                    }
                    $data[] = array(
                      'stuid'  => $stuid,
                      'subname'=>$subname,
                      'mgrade'=>$gradesec,
                      'evaid'=>$evaid,
                      'quarter'=>$quarter,
                      'value'=>$value,
                      'outof'=>$outof,
                      'academicyear'=>$max_year,
                      'markname'=>$markname,
                      'zeromarkinfo'=>$zeromarkinfo,
                      'approved'=>'1',
                      'approvedby'=>$approvedID,
                      'mbranch'=>$mybranch
                    );
                    $data1=array(
                      'userinfo'=>$user,
                      'useraction'=>'Excel Mark Inserted',
                      'infograde'=>$gradesec,
                      'subject'=>$subname,
                      'quarter'=>$quarter,
                      'academicyear'=>$max_year,
                      'oldata'=>'-',
                      'newdata'=>'-',
                      'updateduser'=>'-',
                      'userbranch'=>$mybranch,
                      'actiondate'=> date('Y-m-d H:i:s', time())
                    );
                  }
                }
              }
            }
          }else{
            $this->session->set_flashdata('success','
              <div class="alert alert-warning alert-dismissible show fade">
                <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                  <i class="fas fa-check-circle"> </i> Please adjust your excel format properly.
                </div>
              </div> ');
          }
        }
        if(!empty($data)){
          $query=$this->db->insert_batch('mark'.$mybranch.$gradesec.$quarter.$max_year,$data);
          if($query) {
            $queryInsert=$this->db->insert('useractions',$data1);
            if($quarter!==$max_quarter){
              $queryAlert=$this->db->insert('useralertactions',$data1);
            }
            $this->session->set_flashdata('success','
            <div class="alert alert-success alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i> Data inserted successfully.
              </div>
            </div> ');
          }else{
            $this->session->set_flashdata('error','
            <div class="alert alert-wa alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i> Please try Again.
              </div>
            </div> ');
          }
        }else{
          $this->session->set_flashdata('error','
            <div class="alert alert-warning alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i> Please try again, file already exists.
              </div>
            </div> ');
        }
      }
      else{
        $this->session->set_flashdata('error','
        <div class="alert alert-warning alert-dismissible show fade">
          <div class="alert-body">
            <button class="close"  data-dismiss="alert">
              <span>&times;</span>
            </button>
            <i class="fas fa-check-circle"> </i> Please select a file to import.
          </div>
        </div> ');
      }
    }
    $data['fetch_maxTerm']=$this->main_model->fetch_term_4teacheer($max_year);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['gradesec']=$this->main_model->fetch_gradesec($max_year);
    $data['schools']=$this->main_model->fetch_school();
    $data['branch']=$this->main_model->fetch_branch($max_year);
    $data['fetch_term']=$this->main_model->fetch_term($max_year);
    $this->load->view('teacher/'.$page,$data);
	}
  function importDefaultStudentMark(){
    $user=$this->session->userdata('username');
    $query_branch = $this->db->query("select branch,id,status2 from users where username='$user'");
    $row_branch = $query_branch->row();
    $branch=$row_branch->branch;
    $approvedID=$row_branch->id;
    $status2=$row_branch->status2;
    $query =$this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' ");
    $row2 = $query2->row();
    $max_quarter=$row2->quarter;
    $today=date('y-m-d');
    echo '<div class="row">';
    if($_FILES['addmark']['name'] != ''){
    /*if(isset($_FILES["addmark"]["name"]))
    {*/
      $fileName = $_FILES['addmark']['name'];
      
      $path = $_FILES["addmark"]["tmp_name"];
      $object = PHPExcel_IOFactory::load($path);
      $info = pathinfo($fileName);
      $allow_file = array("xls");
      if(in_array($info['extension'],$allow_file)){
        foreach($object->getWorksheetIterator() as $worksheet)
        {
          $data=array();
          $data1=array();
          $highestRow = $worksheet->getHighestRow();
          $highestColumn = $worksheet->getHighestColumn();
          $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
          $subname=trim($worksheet->getCellByColumnAndRow(2,2)->getValue() ?? '');
          $quarter=trim($worksheet->getCellByColumnAndRow(1,2)->getValue() ?? '');
          $gradesec=trim($worksheet->getCellByColumnAndRow(1,1)->getValue() ?? '');
          $mybranch=trim($worksheet->getCellByColumnAndRow(2,1)->getValue() ?? '');
          for($col=3;$col <= $highestColumnIndex;$col++)
          {
            $evaid = $worksheet->getCellByColumnAndRow($col,2)->getValue();
            $outof = $worksheet->getCellByColumnAndRow($col,3)->getValue();
            $markname = $worksheet->getCellByColumnAndRow($col,1)->getValue();
            $query_check=$this->main_model->check_import_markm2($markname,$subname,$quarter,$max_year,$gradesec,$mybranch);
            if($query_check && $outof!='' && $markname!=''){
              for($row=4; $row <= $highestRow; $row++)
              {
                $stuid = $worksheet->getCellByColumnAndRow(0,$row)->getValue();
                $zeromarkinfo= $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                if($worksheet->getCellByColumnAndRow($col,$row)!='')
                {
                  $value1=$worksheet->getCellByColumnAndRow($col,$row)->getValue();
                  $value2=$worksheet->getCellByColumnAndRow($col,3)->getValue();
                  if($value1 > $value2 )
                  {
                    $value=0;
                  }
                  else
                  {
                    $value = $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                  }
                  $data[] = array(
                    'stuid'  => $stuid,
                    'subname'=>$subname,
                    'mgrade'=>$gradesec,
                    'evaid'=>$evaid,
                    'quarter'=>$quarter,
                    'value'=>$value,
                    'outof'=>$outof,
                    'academicyear'=>$max_year,
                    'markname'=>$markname,
                    'zeromarkinfo'=>$zeromarkinfo,
                    'approved'=>'1',
                    'approvedby'=>$approvedID,
                    'mbranch'=>$mybranch
                  );
                  $data1=array(
                    'userinfo'=>$user,
                    'useraction'=>'Excel Mark Inserted',
                    'infograde'=>$gradesec,
                    'subject'=>$subname,
                    'quarter'=>$quarter,
                    'academicyear'=>$max_year,
                    'oldata'=>'-',
                    'newdata'=>'-',
                    'updateduser'=>'-',
                    'userbranch'=>$mybranch,
                    'actiondate'=> date('Y-m-d H:i:s', time())
                  );
                }
              }
            }
          }
          if(!empty($data)){
            $queryCheckM=$this->db->query("SHOW TABLES LIKE 'mark".$mybranch.$gradesec.$quarter.$max_year."' ");
            if ($queryCheckM->num_rows()>0)
            {
              $query=$this->db->insert_batch('mark'.$mybranch.$gradesec.$quarter.$max_year,$data);
              if($query) {
                $queryInsert=$this->db->insert('useractions',$data1);
                if($quarter!==$max_quarter){
                  $queryAlert=$this->db->insert('useralertactions',$data1);
                }
                echo '<div class="col-md-6 col-6">
                <div class="alert alert-success alert-dismissible show fade">
                  <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                    <i class="fas fa-check-circle"> </i> Data inserted successfully for subject '.$subname.'.
                  </div>
                </div></div>';
              }else{
                echo '
                <div class="alert alert-wa alert-dismissible show fade">
                  <div class="alert-body">
                    <button class="close"  data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                    <i class="fas fa-times"> </i> Please try Again.
                  </div>
                </div> ';
              }
            }
          }else{
           echo' <div class="col-md-6 col-6">
              <div class="alert alert-light alert-dismissible show fade">
                <div class="alert-body">
                  <button class="close"  data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                  <i class="fas fa-times"> </i> Please try again,Either file exists or something wrong with your excel subject '.$subname.'.
                </div>
              </div></div>';
          }
        }
      }else{
         echo'
            <div class="alert alert-light alert-dismissible show fade">
              <div class="alert-body">
                <button class="close"  data-dismiss="alert">
                  <span>&times;</span>
                </button>
                <i class="fas fa-check-circle"> </i> Please select only xls file.
              </div>
            </div> ';
      }
    }
    echo '</div>';
  }
}