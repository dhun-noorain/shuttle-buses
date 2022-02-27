<?php
require_once './login/login-det.php';
require_once './login/functions.php';

$error="";
if(isset($_POST['regno'], $_POST['password']))
{
  $regno    = clean_str(get_post($db_con, 'regno'));
  $password = clean_str(get_post($db_con, 'password'));
  
  if(!empty($regno) && !empty($password))
  {
  $sel = "SELECT first_name, last_name, reg_no, password FROM users WHERE reg_no='$regno' AND password='$password'";
    $result = $db_con->query($sel);

    if($result->num_rows > 0)
    {
      for($i = 0; $i < $result->num_rows; $i++)
      {
        $result->data_seek($i);
        $data = $result->fetch_array(MYSQLI_NUM);

        $fname    = $data[0];
        $lname    = $data[1];
        $user_id  = $data[2];
        $user_pw = $data[3];
      }
      $result->close();
      if($user_id == $regno && $user_pw == $password)
      {
        session_start();
        $_SESSION['fname']   = ucfirst($fname);
        $_SESSION['lname']   = ucfirst($lname);
        $_SESSION['regno']   = $user_id;
        header("location: ./home/index.php");
      }else{
        $error = "Inavlid Username/Password combination";
      }
    }else{
      $error = "Inavlid Username/Password combination";
    }
  }else{
    $error = "*Fields can't be empty";
  }
}

$date = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Log in here</title>
  <!--bootstrap 4 css-->
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/main.css">
  <style>
    #info-section{
      background: url(bus-1.jpg);
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
    }
  </style>
</head>
<body>
<nav class="navbar py-3 bg-success">
  <div class="navbar-text mx-auto text-light text-capitalize font-weight-bold">
    welcome to abu aluta bus services
  </div>
</nav>
<!--log in section-->
<section id="login-section" class="py-3">
<div class="container">
  <div class="row">
    <!--carousel-->
    <div class="col-md-6 my-3">
      <div class="carousel slide" data-ride="carousel" id="carousel">
        <ul class="carousel-indicators">
          <li data-target="#carousel" data-slide-to="0" class="active"></li>
          <li data-target="#carousel" data-slide-to="1"></li>
        </ul>

        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="./login/bus-1.jpg" height="450" class="d-block w-100" alt="">
          </div>
          <div class="carousel-item">
            <img src="./login/bus-2.jpg" height="450" class="d-block w-100" alt="">
          </div>
        </div>

        <a href="#carousel" class="carousel-control-next" data-slide="next">
          <span class="carousel-control-next-icon"></span>
        </a>
        <a href="#carousel" class="carousel-control-prev" data-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </a>
      </div>
    </div>
    <!--log in form-->
    <div class="col-md-6 my-3" id="login-col">
      <div class="card">
        <div class="card-header bg-secondary">
          <h4 class="font-weight-bold text-light text-center text-capitalize">log in</h4>
        </div>
        <div class="card-body">
        <!--error message-->
        <span class="text-center font-weight-bold text-danger"><?php echo $error; ?></span>
          <form action="" method="post">
            <div class="form-group">
              <label for="regno" class="font-weight-bold">
                <i class="fas fa-user"></i> Registration Number
              </label>
              <input type="text" name="regno" id="regno" class="form-control" placeholder="Username">
            </div>
            <div class="form-group">
              <label for="password" class="font-weight-bold">
                <i class="fas fa-lock"></i> Password
              </label>
              <input type="password" name="password" id="password" class="form-control" placeholder="Password">
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-outline-success text-capitalize">log in</button>
            </div>
          </form>

          <p class="lead">
            <a href="./login/forgot-password.php" class="text-capitalize">forgot your password?</a>
          </p>
        </div>

        <div class="card-footer">
          <a href="./login/register.php" class="btn btn-outline-info btn-block font-weight-bold my-1">Open an account</a>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
<!--end of log in section-->

<!--info section-->
<section id="info-sections" class="py-3">
<div class="container">
  <div class="row">
    <div class="col-md-6 my-2">
      <div class="card card-body">
        <h4 class="font-weight-bold text-center text-capitalize text-info">
          available bus <i class="fas fa-bus"></i>
        </h4>
        <!--available buses table-->
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>BUS</th>
                <th>FROM</th>
                <th>TO</th>
                <th>TIME</th>
              </tr>
            </thead>
            <tbody>
            <?php
              $sel_trip = "SELECT * FROM trip WHERE date='$date'";
              $query    = $db_con->query($sel_trip);
              $count = 0;

              if($query->num_rows > 0)
              {
                for($i = 0; $i < $query->num_rows; $i++)
                {
                  $query->data_seek($i);
                  $data = $query->fetch_array(MYSQLI_ASSOC);

                  if(strtotime($data['time']) < time())
                    continue;
                  else{
                    echo "<tr> <td>" . $data['bus_id'] . "</td> <td>". ucfirst($data['source']) ."</td> <td>"
                          . ucfirst($data['destination']) . "</td> <td>". date('g:ia', strtotime($data['time'])) . 
                          "</td> </tr>";
                          $count++;
                  }
                }
                if($count == 0)
                  echo "<tr> <td>N/A</td> <td>N/A</td> <td>N/A</td> <td>N/A</td> </tr>";
              }else{
                echo "<tr> <td>N/A</td> <td>N/A</td> <td>N/A</td> <td>N/A</td> </tr>";
              }
              $query->close();
              $db_con->close();
            ?>
            </tbody>
          </table>
        </div>
        <!--end of available buses table-->
      </div>
    </div>
    <div class="col-md-6 my-2">
      <div class="card card-body">
        <h4 class="text-danger font-weight-bold text-center text-capitalize">
          notice <i class="fas fa-bell"></i>
        </h4>

        <p class="lead">
          Do ensure to always be present at most, <i class="fas fa-clock"></i> 5 minutes before the time of departure. Thank you!
        </p>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col my-2">
      <table class="table">
        <thead class="text-capitalize">
          <tr>
            <th colspan="3" class="text-center">working days <i class="fas fa-calendar-check"></i></th>
          </tr>
          <tr>
            <th class="text-info">day</th>
            <th class="text-success">from <i class="fas fa-clock"></i></th>
            <th class="text-danger">to <i class="fas fa-clock"></i></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="font-weight-bold">Monday</td>
            <td>7am</td>
            <td>6pm</td>
          </tr>
          <tr>
            <td class="font-weight-bold">Tuesday</td>
            <td>7am</td>
            <td>6pm</td>
          </tr>
          <tr>
            <td class="font-weight-bold">Wednesday</td>
            <td>7am</td>
            <td>6pm</td>
          </tr>
          <tr>
            <td class="font-weight-bold">Thursday</td>
            <td>7am</td>
            <td>6pm</td>
          </tr>
          <tr>
            <td class="font-weight-bold">Friday</td>
            <td>7am</td>
            <td>6pm</td>
          </tr>
          <tr>
            <td class="font-weight-bold">Saturday</td>
            <td>8am</td>
            <td>6pm</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</section>
<!--end of info section-->

<!--footer-->
<footer class="bg-dark text-light py-3">
<div class="container">
  <div class="row">
    <div class="col-md-4 text-info my-2">
      <h6 class="font-weight-bold text-light">Contact</h6>
      <abbr title="phone">+(234) 7034 3002 54</abbr> <i class="fas fa-phone"></i> <br>
      <abbr title="phone">+(234) 7035 3012 56</abbr> <i class="fas fa-phone"></i> <br>
      <abbr title="phone">+(234) 7038 3022 57</abbr> <i class="fas fa-phone"></i> <br>
    </div>
    <div class="col-md-4 text-muted my-2">
      <h6 class="font-weight-bold text-light">Bus stations</h6>
      <address class="text-capitalize">
        <strong class="font-italic">Kongo:</strong> <br>
        prof. brahim garba garden, opp. public admin. <br>
        <strong class="font-italic">Samaru:</strong> <br>
        prof. abdullahi mustapha car park, beside amina hostel.
      </address>
    </div>
    <div class="col-md-4 my-2">
      <h6 class="font-italic font-weight-bold">Follow our social media handles for more info:</h6>
      <div class="social d-flex justify-content-around mt-3">
        <a href="#" class="">
          <i class="fab fa-facebook fa-3x"></i>
        </a>
        <a href="#" class="">
          <i class="fab fa-twitter fa-3x"></i>
        </a>
        <a href="#" class="">
          <i class="fab fa-instagram fa-3x text-danger"></i>
        </a>
      </div>
    </div>
    <div class="col mt-3">
      <a href="#login-section" class="btn btn-outline-light text-capitalize font-weight-bold btn-block">
        back to top
      </a>
    </div>
  </div>
</div>
</footer>
<!--end of footer-->



  <!--jquery-->
  <script src="./js/jquery.min.js"></script>
  <!--bootstrap 4 js-->
  <script src="./js/bootstrap.bundle.min.js"></script>
  <!--fontawesome-->
  <script src="https://kit.fontawesome.com/14e0c06b3c.js" crossorigin="anonymous"></script>
</body>
</html>