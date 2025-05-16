<?php
class schedule_model extends CI_Model{
  function check_today_schedule(){
    $dataToday=date('Y-m-d');
    $this->db->select('*');
    $this->db->from('schedule_gs');
    $this->db->where('scheduled_for',$dataToday);
    $query = $this->db->get();
    $output='';
    if($query->num_rows()>0){
      date_default_timezone_set('Africa/Addis_Ababa');
      $timeNow=date('h:i:s A');
      $timetoStart='05:00:00 PM';
      $timetoEnd='12:30:00 AM';
      foreach($query->result() as $row){
        $taskToday=$row->scheduled_task;
        if($taskToday=='MarkResult'){
          if($timetoStart <= $timeNow && $timetoEnd >= $timeNow){
            $output.=$this->calculateMarkResult();
          }else{
            $output.='<span class="text-info">Waiting time to start...</span>';
          }
        }else if($taskToday=='ReportCard'){

        }elseif($taskToday=='Roster'){

        }elseif($taskToday=='Transcript'){

        }
        $output.='<div class="alert alert-light alert-dismissible show fade">
          <div class="alert-body"><i class="fas fa-exclamation-triangle "></i> There is <span class="text-bold">'.$row->scheduled_task.'</span> task for today</div>
        </div>';
      }
    }else{
      $output.='<div class="alert alert-light alert-dismissible show fade">
        <div class="alert-body"><i class="fas fa-exclamation-triangle "></i> No task for today.</div>
      </div>';
    }
    return $output;
  }
  function calculateMarkResult(){
    $output='Good';
    return $output;
  }
}