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

$sess_check = "SELECT time FROM trip WHERE trip_id='$trip_id'";
$sql = $db_con->query($sess_check);
if($sql == true)
{
  $time = $sql->fetch_array();
  if((strtotime($time['time']) + 600) < time())
  {
    $sql->close();
    session_destroy();
    unset($trip_id);
    header("location: reserve.php");
  }
}

if(isset($_POST['seat_bt']))
{
  $seat_no = clean_str(get_post($db_con, 'seat_bt'));
  
  $check = "SELECT seat_no FROM reservations WHERE reg_no = '$regno' AND trip_id='$trip_id'";
  $query = $db_con->query($check);
  if($query->num_rows > 0 && !empty($seat_no)) //checks if user already made a reservation
  {
    $query->data_seek(0);
    $data   = $query->fetch_array(MYSQLI_NUM);
    $r_seat = $data[0];
    $error = "You have already reserved seat number $r_seat. No multiple reservations";
  }else{
    $seat_check = "SELECT seat_no FROM reservations WHERE seat_no = '$seat_no' AND trip_id='$trip_id'";
    $run_check  = $db_con->query($seat_check);
    if($run_check->num_rows > 0) //checks if the seat user attempts to reserve has been reserved
    {
      $error = "Sorry, seat $seat_no has already been reserved!";
    }else{
      if(!empty($seat_no)) //reserves a seat.
      {
        $reserve = "INSERT INTO reservations VALUES(null, '$regno', '$trip_id', '$seat_no')";
        if($db_con->query($reserve) == true)
        {
          $success = "Successfully reserve seat number $seat_no";
        }else{
          $error = "Failed to reserve";
        }//end of else
      }
    }
    $query->close();
    $run_check->close();
  }
}//end of isset
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Reserve Here</title>
  <!--bootstrap 4 css-->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<nav class="navbar bg-dark">
  <a href="reserve.php" class="navbar-brand btn btn-dark"> <i class="fas fa-arrow-left"></i> </a>
</nav>

<div class="container">
  <div class="row">
    <div class="col-12 mt-5">
      <div class="font-weight-bold text-center">
        <span class="text-danger"> <?php echo $error; ?> </span>
        <span class="text-success"> <?php echo $success; ?></span>
      </div>
    </div>
    <div class="col-md-6 mx-auto mt-2 d-flex justify-content-center">
    <form action="" method="post">
      <table id="seats">
        <?php
        for($i = 1; $i <= 18; $i++)
        {
        echo "<tr> 
              <td> 
                <button type='submit' class='font-weight-bold text-success' name='seat_bt' value='$i'> $i <br> 
                  <i class='fas fa-couch fa-2x'></i> </button> 
              </td>
              <td> 
                <button type='submit' class='font-weight-bold text-success' name='seat_bt' value='".++$i."'>$i<br> 
                  <i class='fas fa-couch fa-2x'></i> </button>
              </td> 
              <td> 
                <button type='submit' class='font-weight-bold text-success' name='seat_bt' value='".++$i."'>$i<br>
                  <i class='fas fa-couch fa-2x'></i> </button>
              </td> 
              </tr>";
        }
        ?>
      </table>
    </form>
    </div>
  </div>
</div>

  <!--jquery-->
  <script src="../js/jquery.min.js"></script>
  <!--bootstrap 4 js-->
  <script src="../js/bootstrap.bundle.min.js"></script>
  <!--ajax for seats-->
  <script src="reserve-seat.js"></script>
  <!--fontawesome-->
  <script src="https://kit.fontawesome.com/14e0c06b3c.js" crossorigin="anonymous"></script>
</body>
</html
<?php $db_con->close(); ?>