<?php
session_start();

  require_once '../login/login-det.php';
    if(!isset($_SESSION['fname'], $_SESSION['lname'], $_SESSION['regno']))
    {
      header("location: ../index.php");
    }else if(isset($_SESSION['fname'], $_SESSION['lname'], $_SESSION['regno']))
          $regno = $db_con->real_escape_string($_SESSION['regno']);
          $fname  = $db_con->real_escape_string($_SESSION['fname']);
          $lname  = $db_con->real_escape_string($_SESSION['lname']);

          if(empty($regno) && empty($fname) && empty($lname))
          {
            header("location: ../index.php");
          }

    if(isset($_GET['logout'])){
      session_destroy();
      unset($_SESSION['regno'], $_SESSION['fname'], $_SESSION['lname']);
      $db_con->close();
      header("location: ../index.php");
    }
?>