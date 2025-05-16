<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Importbs extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('main_model');
        ob_start();
        $this->load->helper('cookie');
        $userLevel = userLevel();
        $usergroupPermission=$this->db->query("SELECT * from usergrouppermission where usergroup='".$_SESSION['usertype']."' and tableName='studentbasicskill' and allowed='importBSFormat' order by id ASC ");
        if($this->session->userdata('username') == '' || $usergroupPermission->num_rows()<1 || $userLevel!='1'){
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
	public function index($page='importbs')
	{
        if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
            show_404();
        }
        $this->load->model('main_model');
        $user=$this->session->userdata('username');

        $query = $this->db->query("select max(year_name) as year from academicyear");
        $row = $query->row();
        $max_year=$row->year;

        $query2 = $this->db->query("select max(quarter) as quarter from mark");
        $row2 = $query2->row();
        $max_quarter=$row2->quarter;
        $today=date('y-m-d');

        if(isset($_POST['insertbs']))
        {
            if(!empty($_FILES['importbs']["tmp_name"]))
            {
                $this->load->library('excel');
                $path = $_FILES["importbs"]["tmp_name"];
                $object = PHPExcel_IOFactory::load($path);
                
                foreach($object->getWorksheetIterator() as $worksheet)
                {
                    $gradesec = $worksheet->getCellByColumnAndRow(3,2)->getValue();
                $branch = $worksheet->getCellByColumnAndRow(4,2)->getValue();
                $quarter = $worksheet->getCellByColumnAndRow(5,2)->getValue();
                $query=$this->main_model->import_bs($gradesec,$quarter,$max_year,$branch);
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                    for($col=6;$col < $highestColumnIndex;$col++)
                    {
                        for($row=2; $row <= $highestRow; $row++)
                        {
                            $stuid = $worksheet->getCellByColumnAndRow(0,$row)->getValue();
                            $value = $worksheet->getCellByColumnAndRow($col,$row)->getValue();
                            $bsname = $worksheet->getCellByColumnAndRow($col,1)->getValue();
                            
                            
                            if($value!=''){
                                if($query){
                                    $data[] = array(
                                        'stuid'  => $stuid,
                                        'quarter'=>$quarter,
                                        'value'=>$value,
                                        'academicyear'=>$max_year,
                                        'bsname'=>$bsname,
                                        'datecreated'=>date('M-d-Y'),
                                        'byuser'=>$user,
                                        'bsgrade'=>$gradesec,
                                        'bsbranch'=>$branch
                                    );
                                }
                            }
                        }
                    }         
                }
                if(!empty($data)){
                    $query=$this->db->insert_batch('basicskillvalue'.$gradesec.$max_year,$data);
                    if($query){
                    $this->session->set_flashdata('success','
                            <div class="alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close"  data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                <i class="fas fa-check-circle"> </i> Basic Skill inserted successfully.
                        </div></div>');
                    }else{
                        $this->session->set_flashdata('error','<div class="alert alert-warning alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close"  data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                <i class="fas fa-check-circle"> </i> Please check your basic skill value again.
                        </div></div>');
                    }
                }
            }
        }
        $data['sessionuser']=$this->main_model->fetch_session_user($user);
        $data['academicyear']=$this->main_model->academic_year_filter();
        $data['schools']=$this->main_model->fetch_school();
        $this->load->view('home-page/'.$page,$data);
	} 
}