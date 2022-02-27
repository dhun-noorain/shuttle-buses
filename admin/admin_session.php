<?php
session_start();

require_once '../login/login-det.php';

if(!isset($_SESSION['admin_id'], $_SESSION['admin_name']))
{
  header("location: index.php");
}else if(isset($_SESSION['admin_id'], $_SESSION['admin_name'])) {
  $admin_id   = $db_con->real_escape_string($_SESSION['admin_id']);
  $admin_name = $db_con->real_escape_string($_SESSION['admin_name']);

  if(empty($admin_id) && empty($admin_name))
  {
    header("location: index.php");
  }
}

if(isset($_GET['logout']))
{
  unset($admin_id);
  unset($admin_name);
  session_destroy();

  header("location: index.php");
}

?>