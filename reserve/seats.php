<?php
require_once '../session/sessions.php';
require_once '../login/functions.php';

$error=$success="";

if(!isset($_SESSION['trip_id']))
{
  header("location: reserve.php");
}else{
  $trip_id = $_SESSION['trip_id'];
}

//marking reserved seats red
$sel = "SELECT seat_no FROM reservations WHERE trip_id='$trip_id'";
$result = $db_con->query($sel);
$reserved_seats = "";
if($result->num_rows != 0)
{
  for($j = 0; $j < 18; $j++)
  {
    $x_seats = $result->fetch_assoc();
	if(!empty($x_seats['seat_no']))
    echo $x_seats['seat_no'] . ",";
  }
}

$result->close();
$db_con->close();
?>