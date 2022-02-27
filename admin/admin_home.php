<?php
require_once 'admin_session.php';
require_once '../login/functions.php';

$error=$success="";
$date = date("Y-m-d");

if(isset($_POST['bus'], $_POST['source'], $_POST['destination'], $_POST['time']))
{
  $bus      = $db_con->real_escape_string($_POST['bus']);
  $from     = $db_con->real_escape_string($_POST['source']);
  $to       = $db_con->real_escape_string($_POST['destination']);
  $time     = $db_con->real_escape_string($_POST['time']);

  if(!empty($bus) && !empty($from) && !empty($to) && !empty($time))
  {
    if($from != $to)
    {
      $sql = "INSERT INTO trip VALUES(null, '$from', '$to', '$time', '$bus', '$date')";
      if($db_con->query($sql) == true)
      {
        $success = "Successfully added";
      }else{
        $error = "Failed to add trip";
      }
    }else{
      $error = "Error, from $from to $to is not appropraite!";
    }
  }
}

if(isset($_POST['cancel']))
{
  $id = clean_str(get_post($db_con, 'cancel'));
  $del = "DELETE FROM trip WHERE trip_id='$id';";
  $del .= "DELETE FROM reservations WHERE trip_id='$id'";

    if($db_con->multi_query($del) == true)
    {
      header("location: admin_home.php");
    }else{
      $error = "Failed to cancel trip";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin Home</title>
  <!--bootstrap 4 css-->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="d-flex justify-content-end">
    <a href="#" class="navbar-text font-weight-bold dropdown-toggle" data-toggle="dropdown">
      <i class="fas fa-user"></i> <?php echo ucfirst($admin_name); ?>

      <div class="dropdown-menu">
        <a href="admin_home.php?logout=1" class="dropdown-item">
          <i class="fas fa-power-off"></i> Log out</a>
        </a>
      </div>
    </a>
  </div>
</nav>

<div class="container">
  <div class="row">
    <div class="col-md-8 mx-auto mt-5">
      <div class="card">
        <div class="card-header font-weight-bold text-center"> <?php echo date('d-m-Y'); ?> </div>
        <div class="card-body">
          <ul class="nav nav-pills justify-content-center text-capitalize font-weight-bold">
            <li class="nav-item"><a href="#trip" data-toggle="pill" class="nav-link active">add trip</a></li>
            <li class="nav-item"><a href="#viewTrip" data-toggle="pill" class="nav-link">view trip</a></li>
            <li class="nav-item"><a href="#passengers" data-toggle="pill" class="nav-link">view passengers</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane container active font-weight-bold text-capitalize" id="trip">
              <form action="" method="post">
                <div class="text-center mt-3">
                  <span class="text-danger"><?php echo $error; ?></span>
                  <span class="text-success"><?php echo $success; ?></span>
                </div>
                <div class="form-group">
                  <label for="bus">bus</label>
                  <select name="bus" id="bus" class="form-control" readonly>
                    <?php
                      $sel_bus = "SELECT bus_id FROM bus WHERE admin_id='$admin_id'";
                      $query   = $db_con->query($sel_bus);

                      $query->data_seek(0);
                      $data = $query->fetch_array(MYSQLI_NUM);

                      echo "<option value='$data[0]'> $data[0] </option>";
                      $query->close();
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="from">from</label>
                  <select name="source" id="from" class="form-control">
                    <option value="kongo">Kongo</option>
                    <option value="samaru">Samaru</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="to">to</label>
                  <select name="destination" id="to" class="form-control">
                    <option value="samaru">Samaru</option>
                    <option value="kongo">Kongo</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="time">time</label>
                  <select name="time" id="time" class="form-control">
                    <option value="07:00">07 AM</option>
                    <option value="08:00">08 AM</option>
                    <option value="09:00">09 AM</option>
                    <option value="10:00">10 AM</option>
                    <option value="11:00">11 AM</option>
                    <option value="12:00">12 PM</option>
                    <option value="13:00">01 PM</option>
                    <option value="14:00">02 PM</option>
                    <option value="15:00">03 PM</option>
                    <option value="16:00">04 PM</option>
                    <option value="17:00">05 PM</option>
                    <option value="18:00">06 PM</option>
                  </select>
                </div>
                <div class="form-group d-flex justify-content-center">
                  <button type="submit" class="btn btn-success font-weight-bold">Add Trip</button>
                </div>
              </form>
            </div>
            <div class="tab-pane container fade" id="viewTrip">
              <table class="table table-responsive mt-5">
                <thead>
                  <th>FROM</th>
                  <th>TO</th>
                  <th>TIME</th>
                </thead>
                <tbody>
                  <?php
                    $sel_trip = "SELECT source, destination, trip_id, time 
                                FROM trip WHERE bus_id='$data[0]' AND date='$date'";

                    $trip_sql = $db_con->query($sel_trip);

                    if($trip_sql->num_rows > 0)
                    {
                      $count = 0;
                      for($i=0; $i < $trip_sql->num_rows; $i++)
                      {
                        $trip_sql->data_seek($i);
                        $trips = $trip_sql->fetch_array(MYSQLI_ASSOC);
                        
                        //the time and trip arrays are used in the view passenger tab
                        $cur_trips[] = $trips['trip_id'];
                        $cur_time[]  = $trips['time'];

                        if(strtotime($trips['time']) > time())
                        {
                        echo "<tr> <td> " . ucfirst($trips['source']) . "</td><td>". ucfirst($trips['destination'])
                                    ."</td><td>". $trips['time'] ."</td> </tr>
                            <tr> <td colspan=3>
  <form method='POST'>
  <input type='hidden' value='".$trips['trip_id']."' name='cancel'> 
<button type='submit' class='btn btn-danger btn-block'>Cancel</button>
  </form>  </td> </tr>";
                          $count++;
                        }
                      }
                      if($count == 0)
                        echo "<tr><td>N/A</td><td>N/A</td><td>N/A</td></tr>";
                    }
                    $trip_sql->close();
                  ?>
                </tbody>
              </table>
            </div>
            <div class="tab-pane container fade" id="passengers">
              <?php
                if(!empty($cur_trips))
                {
                  for($i = 0; $i < count($cur_trips); $i++)
                  {
                    $sel_booked = "SELECT first_name, last_name, seat_no 
                                    FROM users, reservations
                                    WHERE users.reg_no=reservations.reg_no 
                                    AND trip_id=$cur_trips[$i]";
echo "<table class='table table-responsive mt-5'><thead> <tr> <th colspan=5> TIME- $cur_time[$i] <th></tr></thead>";
                
                    $run = $db_con->query($sel_booked);
                    if($run->num_rows > 0)
                    {
                      while($passengers = $run->fetch_array())
                      {
                        echo "<tbody> <tr> <td>FIRST NAME: </td> <td> " . $passengers['first_name'] . "</td>
                            <td>LAST NAME: </td> <td> " . $passengers['last_name'] . "</td>
                            <td>SEAT NO: </td> <td> " . $passengers['seat_no'] . "</td></tr>";
                      }
                      echo "</tbody></table><br>";
                    }
                  }
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <!--jquery-->
  <script src="../js/jquery.min.js"></script>
  <!--bootstrap 4 js-->
  <script src="../js/bootstrap.bundle.min.js"></script>
  <!--fontawesome-->
  <script src="https://kit.fontawesome.com/14e0c06b3c.js" crossorigin="anonymous"></script>
</body>
</html>
<?php $db_con->close(); ?>