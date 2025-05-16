<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Loggeduser extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('main_model');
    ob_start();
    $this->load->helper('cookie');
    $userLevel = userLevel();
    if($this->session->userdata('username') == '' || $userLevel!='1'){
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
	public function index($page='loggeduser')
  {
    if(!file_exists(APPPATH.'views/home-page/'.$page.'.php')){
      show_404();
    }
    $this->load->model('main_model');
    $user=$this->session->userdata('username');
    $usertype=$this->session->userdata('usertype');
    $query_branch = $this->db->query("select * from users where username='$user'");
    $row_branch = $query_branch->row();
    $logged_id=$row_branch->id;
    $date_now= date('y-m-d');
    $data['loggeduser']=$this->main_model->fetch_logged_user($date_now);
    $data['sessionuser']=$this->main_model->fetch_session_user($user);
    $data['academicyear']=$this->main_model->academic_year_filter();
    $data['schools']=$this->main_model->fetch_school();
    $data['posts']=$this->main_model->fetch_post();
    $this->load->view('home-page/'.$page,$data); 
  }
  function fetchUrgentAlert(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->main_model->fetchUrgentAlert($max_year);
  }
  function fetchUserActions(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    echo $this->main_model->fetchUserAction($max_year);
  }
  function customActions(){
    $accessbranch = sessionUseraccessbranch();
    $branchName = sessionUserDetailNonStudent();
    $branch=$branchName['branch'];
    $YearName = sessionAcademicYear();
    $max_year=$YearName['year'];
    if($this->input->post('searchItem')){
      $searchItem=$this->input->post('searchItem');
      if($_SESSION['usertype']===trim('superAdmin') || $accessbranch === '1'){
          echo $this->main_model->customActions($searchItem,$max_year);
      }else{
          echo $this->main_model->customActionsAdmin($searchItem,$branch,$max_year);
      }
    }
  }
  function deleteRestriction(){
    if($this->input->post('userName')){
      $userName=$this->input->post('userName');
      $this->db->where('tried_username',$userName);
      $query=$this->db->delete('login_attempt');
      if($query){
          echo '<span class="text-success">Deleted successfully</span>';
      }else{
          echo '<span class="text-danger">Please try later</span>';
      }
    } 
  }
  function fetchSystemBlockedUsres(){
    echo $this->main_model->fetch_blocked_users();
  }
  function restoreDeletedMark(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('subject')){
      $grade=$this->input->post('grade');
      $subject=$this->input->post('subject');
      $quarter=$this->input->post('quarter');
      $branch=$this->input->post('branch');
      $old_data=$this->input->post('oldata');
      echo $this->main_model->restoreDeletedMark($grade,$subject,$quarter,$branch,$old_data,$max_year);
    } 
  }
  function alertIgnore(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('id')){
      $grade=$this->input->post('grade');
      $id=$this->input->post('id');
      $quarter=$this->input->post('quarter');
      $branch=$this->input->post('branch');
      $academicyear=$this->input->post('academicyear');
      $this->db->where('id',$id);
      $this->db->set('status','1');
      $queryUpdate=$this->db->update('useralertactions');
      if($queryUpdate){
        echo '1';
      }else{
        echo '0';
      }
    } 
  }
  function alertUpdate(){
    $query = $this->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    if($this->input->post('id')){
      $grade=$this->input->post('grade');
      $id=$this->input->post('id');
      $quarter=$this->input->post('quarter');
      $branch=$this->input->post('branch');
      $academicyear=$this->input->post('academicyear');
      $queryUpdate=$this->main_model->update_reportcardResult_alert($academicyear,$grade,$branch,$quarter);
      if($queryUpdate){
        $this->db->where('id',$id);
        $this->db->set('status','1');
        $querySet=$this->db->update('useralertactions');
        if($querySet){
          echo '1';
        }else{
          echo '0';
        }
      }else{
        echo '0';
      }
    } 
  }

  function backUpFiles() {
    // Configure environment
    @ini_set('memory_limit', '-1');
    @set_time_limit(0);
    
    // Configure paths (verify these match your system)
    $backup_dir = 'C:\\Users\\Public\\Downloads\\';
    $mysqldump_path = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

    try {
        // Verify and create backup directory
        if (!is_dir($backup_dir) && !mkdir($backup_dir, 0755, true)) {
            throw new Exception("Directory creation failed: " . $backup_dir);
        }

        // Get database credentials
        $db = $this->db->database;
        $host = $this->db->hostname;
        $user = $this->db->username;
        $pass = $this->db->password;

        // Generate filename with timestamp
        $filename = 'backup-' . date('Y-m-d-His') . '.sql';
        $filepath = $backup_dir . $filename;

        // Verify mysqldump exists
        if (!file_exists($mysqldump_path)) {
            throw new Exception("mysqldump not found at: " . $mysqldump_path);
        }

        // Build safe command with escaped arguments
        $command = sprintf(
            '"%s" --host=%s --user=%s --password=%s %s --result-file=%s 2>&1',
            escapeshellarg($mysqldump_path),
            escapeshellarg($host),
            escapeshellarg($user),
            escapeshellarg($pass),
            escapeshellarg($db),
            escapeshellarg($filepath)
        );

        // Execute command
        exec($command, $output, $status);

        // Check results
        if ($status !== 0 || !file_exists($filepath)) {
            $errorDetails = "Command: " . str_replace($pass, '*****', $command) . "\n"
                          . "Status: $status\n"
                          . "Output: " . implode("\n", $output);
            throw new Exception("Backup failed:\n" . $errorDetails);
        }

        // Stream file to browser
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . basename($filepath) . "\"");
        header("Content-Length: " . filesize($filepath));
        readfile($filepath);
        exit;

    } catch (Exception $e) {
        // Return JSON error with details
        return json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
  }


  function backUpFIless(){
    /*$this->load->helper('download');
    $this->load->helper('url');
    $this->load->helper('file');
    $this->load->library('zip');
    $this->load->dbutil();
    $prefs = array('format' => 'zip', 'filename' => 'Database-backup_' . date('Y-m-d_H-i'));
    $backup =$this->dbutil->backup($prefs);
    $dbname='GS-backup_' . date('Y-m-d_H-i') . '.zip';
    $save='C:\Users\Public\Downloads/'.$dbname;
    write_file($save,$backup);
    $query=force_download($dbname,$backup);
    if ($query) {
      echo "<span class='text-success'>Database backup has been created successfully.File saved in C:\Users\Public\Downloads/</span>";
      
    }
    else {
      echo "<span class='text-danger'>Error while creating auto database backup!</span>";
    }*/
    // Database configuration
    /*$host = "localhost";
    $username = "root";
    $password = "chuchajossy21";
    $database_name = "sms";
    $conn = mysqli_connect($host, $username, $password, $database_name);
    $conn->set_charset("utf8");

    $tables = array();
    $sql = "SHOW TABLES";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }
    $tables = $this->db->list_tables();
    $sqlScript = "";
    foreach ($tables as $table) {    
        $query = "SHOW CREATE TABLE $table";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_row($result);
        
        $sqlScript .= "\n\n" . $row[1] . ";\n\n";
          
        $query = "SELECT * FROM $table";
        $result = mysqli_query($conn, $query);
        
        $columnCount = mysqli_num_fields($result);    
        for ($i = 0; $i < $columnCount; $i ++) {
            while ($row = mysqli_fetch_row($result)) {
                $sqlScript .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $columnCount; $j ++) {
                    $row[$j] = $row[$j];
                    
                    if (isset($row[$j])) {
                        $sqlScript .= '"' . $row[$j] . '"';
                    } else {
                        $sqlScript .= '""';
                    }
                    if ($j < ($columnCount - 1)) {
                        $sqlScript .= ',';
                    }
                }
                $sqlScript .= ");\n";
            }
        }
        $sqlScript .= "\n"; 
    }
    if(!empty($sqlScript))
    {
        $backup_file_name = 'GS' . '_backup_' . date('Y-m-dH-i') . '.sql';
        $fileHandler = fopen($backup_file_name, 'w+');
        $number_of_lines = fwrite($fileHandler, $sqlScript);
        fclose($fileHandler); 

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backup_file_name));
        ob_clean();
        flush();
        readfile($backup_file_name);
        exec('rm ' . $backup_file_name); 
    }*/
    echo 'Under construction!';
  }
}