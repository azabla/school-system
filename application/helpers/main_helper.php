<?php
defined('BASEPATH') OR exit('No direct script access allowed');
function sessionUserDetailNonStudent($array= array()){
    $CI = get_instance();
    $user=$CI->session->userdata('username');
    $query_branch = $CI->db->query("select * from users where username='$user'");
    return $query_branch->row_array();
    /*return $row_branch['branch'];*/
}
function sessionUserDetailStudent($array= array()){
    $CI = get_instance();
    $user=$CI->session->userdata('username');
    $query =$CI->db->query("select max(year_name) as year from academicyear");
    $row = $query->row();
    $max_year=$row->year;
    $query_branch = $CI->db->query("select * from users where username='$user' and academicyear='$max_year' and status='Active' and usertype='Student' and isapproved='1' ");
    $row_branch = $query_branch->row_array();
    return $row_branch['branch'];
}
function sessionUseraccessbranch($array= array()){
    $CI = get_instance();
    $userType=$CI->session->userdata('usertype');
    $queryAccessBranch=$CI->db->query("select accessbranch from usegroup where uname='$userType' ");
    $rowaccessbranch = $queryAccessBranch->row_array();
    return $rowaccessbranch['accessbranch'];
}
function sessionQuarterDetail($array= array()){
    $CI = get_instance();
    $query =$CI->db->query("select max(year_name) as year from academicyear");
    $row = $query->row_array();
    $max_year=$row['year'];
    $query2 = $CI->db->query("select *,max(term) as quarter from quarter where Academic_Year='$max_year' ");
    return $query2->row_array();
}
function sessionAcademicYear($array= array()){
    $CI = get_instance();
    $query =$CI->db->query("select *, max(year_name) as year from academicyear");
    return $query->row_array();
}
function userLevel($array= array()){
    $CI = get_instance();
    $userType=$CI->session->userdata('usertype');
    $queryAccessBranch=$CI->db->query("select userlevel from usegroup where uname='$userType' ");
    $userLevel = $queryAccessBranch->row_array();
    return $userLevel['userlevel'];
}