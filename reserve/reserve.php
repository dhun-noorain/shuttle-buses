<?php
require_once '../session/sessions.php';
require_once '../login/functions.php';

$date = date("Y-m-d");

if(isset($_POST['reserve']))
{
  $_SESSION['trip_id'] = $db_con->real_escape_string($_POST['reserve']);
  header("location: reserve-seat.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Reserve a seat</title>
  <!--bootstrap 4 css-->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<?php require_once '../session/navigation.php'; ?>
<div class="container">
<div class="row">
  <div class="col-md-8 mx-auto mt-5">
    <?php
          $sel_trip = "SELECT * FROM trip WHERE date='$date' ORDER BY trip_id ASC";
          $query    = $db_con->query($sel_trip);
          $count = 0;
          $display = "";
          if($query->num_rows > 0)
          {
            $rows = $query->num_rows;
            
            for($i = 0; $i < $rows; $i++)
            {
              $query->data_seek($i);
              $data = $query->fetch_array(MYSQLI_ASSOC);

              if((strtotime($data['time'])+ 600) > time())
              {
                $trip_id = $data['trip_id'];

                $isReserved = "SELECT trip_id
                                FROM reservations
                                WHERE reg_no='$regno' AND trip_id=$trip_id";

                $check = $db_con->query($isReserved);

                if ($check->num_rows > 0) {

                  $display = "<div class='table-responsive'> <table class='table'>
                              <thead>
                              <th>BUS $trip_id</th>
                              <th>FROM</th>
                              <th>TO</th>
                              <th colspan=2>TIME</th>
                        </thead><tbody>
                    <tr> <td>" . $data['bus_id'] . "</td> <td>". ucfirst($data['source']) ."</td> <td>"
                    . ucfirst($data['destination']) ."</td> <td>". date('g:ia', strtotime($data['time'])) ."</td> </tr> 
                              <tr>
                              <td colspan=2> 
                                <form method='POST' id='reserve-btn'>
                                <button type='submit' class='btn btn-success btn-block'>Reserve</button> 
                                <input type='hidden' name='reserve' value='$trip_id'>
                                </form>
                              <td colspan=2> 
                                <form method='POST'>
                                  <button type='submit' class='btn btn-info btn-block' id='ticket-btn'>
                                    View Ticket
                                  </button> 
                                  <input type='hidden' name='ticket' value='$trip_id'>
                                </form>
                              </td>
                              </tr> 
                              <tr>
                                <td colspan=4> 
                                <form method='post'>
                                  <button type='button' class='btn btn-danger btn-block re_cancel' value='$trip_id' data-toggle='modal' data-target='#modal'>
                                    Cancel
                                  </button>
                                </form>
                                </td> 
                              </tr>
                              </tbody></table> </div> <br>";
                } else {
                  $display = "<div class='table-responsive'> <table class='table'>
                  <thead>
                  <th>BUS $trip_id</th>
                  <th>FROM</th>
                  <th>TO</th>
                  <th colspan=2>TIME</th>
            </thead><tbody>
        <tr> <td>" . $data['bus_id'] . "</td> <td>". ucfirst($data['source']) ."</td> <td>"
        . ucfirst($data['destination']) ."</td> <td>". date('g:ia', strtotime($data['time'])) ."</td> </tr> 
                  <tr>
                  <td colspan=4> 
                    <form method='POST' id='reserve-btn'>
                    <button type='submit' class='btn btn-success btn-block'>Reserve</button> 
                    <input type='hidden' name='reserve' value='$trip_id'>
                    </form></td></tr></table></div>";
                }
                echo $display;
                  $count++;
              }
            }
            if($count == 0)
              echo "<table class='table'>
              <thead><th>BUS</th><th>FROM</th><th>TO</th><th>TIME</th></thead><tbody><tr> 
              <td>N/A</td> <td>N/A</td> <td>N/A</td> <td>N/A</td> 
              </tr>";
          }else{
            echo "<table class='table'>
            <thead><th>BUS</th><th>FROM</th><th>TO</th><th>TIME</th></thead><tbody><tr> 
            <td>N/A</td> <td>N/A</td> <td>N/A</td> <td>N/A</td> 
            </tr>";
          }
    ?>
  </div>
</div>

<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card card-body">
    <?php
    $error=$success="";

      if(isset($_POST['cancel']))
      {
        $val = $db_con->real_escape_string($_POST['cancel']);
        if(!empty($val)){
          $cancel = "DELETE FROM reservations WHERE reg_no='$regno' AND trip_id='$val'";
          if($db_con->query($cancel) == true)
          {
            $success = "Successfuly Canceled";
          }else{
            $error = "Failed to cancel reservation";
          }
        }else{
          $error = "You haven't made a reservation";
        }
      }

      if(isset($_POST['ticket']))
      {
        $ticket_val = clean_str(get_post($db_con, 'ticket'));
        if(!empty($ticket_val))
        {
          $ticket = "SELECT first_name, last_name, time, seat_no, bus_id, source, destination 
                    FROM users, reservations, trip 
                    WHERE reservations.trip_id=trip.trip_id 
                    AND users.reg_no=reservations.reg_no 
                    AND users.reg_no='$regno' 
                    AND trip.trip_id='$ticket_val';";

          $result = $db_con->query($ticket);
          if($result)
          {
            $data = $result->fetch_assoc();
            if(!empty($data['first_name']) && !empty($data['last_name']))
            {
              echo "<table class='table table-responsive'> 
                    <tr> 
                    <td>FIRST NAME: " . $data['first_name'] ."</td> <td>LAST NAME: " . $data['last_name'] . "</td> 
                    </tr>" .
                    "<tr>
                      <td>BUS: ". $data['bus_id'] ."</td> <td>SEAT NUMBER: " . $data['seat_no'] . " </td> 
                    </tr>" .
                    "<tr>
                  <td>FROM: ". ucfirst($data['source']) ."</td> <td>TO: " . ucfirst($data['destination']) . " </td> 
                    </tr>" .
                    "<tr>
                      <td>TIME: ". $data['time'] ."</td> <td>DATE: " . date("d-m-Y") . " </td> 
                    </tr>";
            }else{
              $error = "Failed to generate ticket.";
            }
          }else{
            $error = "Failed to generate ticket";
          }
        }else{
          $error = "You haven't made a reservation";
        }
        $result->close();
      }
    ?>
      <div class="font-weight-bold text-center">
        <span class="text-danger"><?php echo $error; ?></span>
        <span class="text-success"><?php echo $success; ?></span>
      </div>
    </div>
  </div>
</div>
</div>


  <!--jquery-->
  <script src="../js/jquery.min.js"></script>
  <!--bootstrap 4 js-->
  <script src="../js/bootstrap.bundle.min.js"></script>
  <script src="cancel.js"></script>
  <!--fontawesome-->
  <script src="https://kit.fontawesome.com/14e0c06b3c.js" crossorigin="anonymous"></script>
</body>
</body>
</html>
<?php $db_con->close(); ?>

                        <div class='modal fade' id='modal'>
                          <div class='modal-dialog'>
                            <div class='modal-content'>
                              <div class='modal-header'>
                                <h5 class='font-weight-bold text-dark'>Confirmation!</h5>
                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                              </div>
                              <div class='modal-body'>
                                <p class='lead'>
                                  Are you sure you want to cancel reservation?
                                </p>
                              </div>
                              <div class='modal-footer'>
                                <form method='post'>
                                  <button type='submit' class='btn btn-success .conf_cancel'>
                                  Yes
                                  </button>
                                  <input type='hidden' value='' class="cancel_val" name='cancel'>
                                </form>
                                <button type='button' class='btn btn-danger' data-dismiss='modal'>
                                  No
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>