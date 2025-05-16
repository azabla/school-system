<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vacancy extends CI_Controller {
	public function index()
	{
        $this->load->model('main_model');
        $data['teachers']=$this->main_model->fetch_teachers();
        $data['blogs']=$this->main_model->fetch_vacancy();
        $data['blogss']=$this->main_model->fetch_blogs();
        $data['fetch_gallery']=$this->main_model->fetch_gallery();
        $data['social_pages']=$this->main_model->fetch_social_pages();
        $data['schools']=$this->main_model->fetch_school();//school
        $this->load->view('vacancy',$data);
	} 
    function applicantsHere(){
        $this->load->model('main_model');
        if(isset($_POST['submitapply'])){
            $config['upload_path']    = './vacancyfile/';
            $config['allowed_types']  = 'doc|pdf|docx';
            $this->load->library('upload', $config);
            $applyposition=$this->input->post('applyposition');
            $applyfullname=$this->input->post('applyfullname');
            $applyqualification=$this->input->post('applyqualification');
            $applyexperience=$this->input->post('applyexperience');
            $applymobile=$this->input->post('applymobile');
            if ($this->upload->do_upload('applycv')){
                $dataa =  $this->upload->data('file_name');
                $data=array(
                        'applyposition'=>$applyposition,
                        'applyfullname'=>$applyfullname,
                        'applyqualification'=>$applyqualification,
                        'applyexperience'=>$applyexperience,
                        'applymobile'=>$applymobile,
                        'applycv'=>$dataa,
                        'dateapplied'=>date('M-d-Y')
                );
                $query=$this->db->insert('jobapplicants',$data);
                if($query){
                    $this->session->set_flashdata("success",'Your application sent successfully.');
                    redirect('vacancy/');
                }else{
                    $this->session->set_flashdata("error",'Oooops Please try again.');
                    redirect('vacancy/');
                } 
            }else{
                $this->session->set_flashdata("error",'Oooops Please try with appropriate file(doc|pdf|docx).');
                redirect('vacancy/');
            }
        }else{
            redirect('vacancy/');
        }
    }
}