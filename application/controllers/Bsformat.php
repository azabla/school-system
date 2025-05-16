<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bsformat extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        $this->load->library('excel');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='exportBSFormat' order by id ASC ");
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='2'){
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
	public function index($page='bsformat')
	{
        if(!file_exists(APPPATH.'views/teacher/'.$page.'.php'))
        {
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $query2 = $this->db->query("select max(term) as quarter from quarter where Academic_Year='$max_year' ");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $data['gradesec']=$this->main_model->fetch_mygradesec2($user,$max_year,$branch);
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['bsname']=$this->main_model->fetch_bsname($max_year,$max_quarter);
        $data['schools']=$this->main_model->fetch_school();
        $data['branch']=$this->main_model->fetch_branch($max_year);
        $this->load->view('teacher/'.$page,$data);
	}
    function export(){
        $user=$this->session->userdata('username');
        $query_branch = $this->db->query("select * from users where username='$user'");
        $row_branch = $query_branch->row();
        $branch=$row_branch->branch;
        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;
        $today=date('y-m-d');
        if(isset($_POST['exportbs'])){
            $gradesec=$this->input->post('gradesecbs');
            $filename =$gradesec.'.csv';
            $listInfo = $this->main_model->export_mystudent_bs_formate($gradesec,$max_year,$branch);
            $evnameinfo = $this->main_model->export_mythis_grade_bsname($gradesec,$max_year,$branch);
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->SetCellValue('A1','Id');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1','Student Name');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1','Student ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1','Grade');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1','Branch');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1','Quarter');
           /* $objPHPExcel->getActiveSheet()->SetCellValue('E1','Conduct'); */
            $column = 6;
            foreach($evnameinfo  as $field)
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field->bsname);
                $column++;
            }
            $rowCount = 2;
            foreach ($listInfo as $list) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $list->id);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $list->fname 
                    .' '. $list->mname.' '.$list->lname );
                
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $list->username);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $gradesec);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $branch);
                $rowCount++;
            }
            header('Content-Type:application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="BasicSkill '.$filename.'"');
            header('Cache-Control: max-age=0'); 
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');  
            $objWriter->save('php://output');  
        }
    } 
}