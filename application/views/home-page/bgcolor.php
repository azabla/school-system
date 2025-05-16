<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$query_bgcolor = $this->db->query("select * from bgcolor where sid='".$_SESSION['id']."'");
    if($query_bgcolor->num_rows()>0){
        $row_bgcolor = $query_bgcolor->row();
        $sid=$row_bgcolor->bgcolor;
    }else{
        $sid=1;
    }
/*$query_bgImage = $this->db->query("select * from bgimage where bguser='".$_SESSION['username']."'");
    if($query_bgImage->num_rows()>0){
        $rowImage = $query_bgImage->row();
        $bgid=$rowImage->bgname;
    }else{
        $bgid='';
    }*/
?>