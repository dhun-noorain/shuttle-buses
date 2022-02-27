<?php
require_once 'login-det.php';
require_once 'functions.php';
$error=$success="";
if(isset($_POST['fname'], $_POST['lname'], $_POST['phone'], $_POST['dept'], $_POST['email'], $_POST['regno'], $_POST['password'], $_POST['v_password']))
{
  $fname      = clean_str(get_post($db_con, 'fname'));
  $lname      = clean_str(get_post($db_con, 'lname'));
  $phone      = clean_str(get_post($db_con, 'phone'));
  $dept       = clean_str(get_post($db_con, 'dept'));
  $email      = clean_str(get_post($db_con, 'email'));
  $regno      = clean_str(get_post($db_con, 'regno'));
  $password   = clean_str(get_post($db_con, 'password'));
  $v_password = clean_str(get_post($db_con, 'v_password'));

  if(!empty($fname) && !empty($lname) && !empty($phone) && !empty($dept) && !empty($email) && !empty($regno) && !empty($password) && !empty($v_password)){
    if(filter_var($email, FILTER_VALIDATE_EMAIL) == true && val_regno($regno) === true && val_phone($phone)===true){
      if(check_pw($password, $v_password) === true){
        $insert = "INSERT INTO users 
                          VALUES('$fname', '$lname', '$phone', '$dept', '$email', '$regno', '$password')";
        if($db_con->query($insert) === true){
          $success = "<i class='fas fa-check fa-1x'></i> SUCCESSFULLY REGISTERED";
        }else{
          $error = "* Failed! Reg Number already exist .";
        }
      }else{
        $error = "* Passwords do not match!";
      }
    }else{
      $error = "* Please enter a valid email/registration/phone number";
    }
  }else{
    $error = "* Fields can't be left empty";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Register Here</title>
  <!--bootstrap 4 css-->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<nav class="navbar bg-dark py-3">
  <a href="index.php" class="btn btn-dark">
    <i class="fas fa-arrow-left text-light"></i>
  </a>
</nav>
<div class="conatiner">
  <div class="row">
    <div class="col-md-8 mx-auto my-3">
    <div class="card card-body">
      <form action="" method="post" class="mx-3">

          <div class="col-12 text-center font-weight-bold mb-2">
            <span class="text-danger"><?php echo $error; ?></span>
            <span class="text-success"><?php echo $success; ?></span>
          </div>

        <div class="form-group row">
          <label for="fname" class="col-md-4 text-capitalize font-weight-bold">
            <i class="fas fa-user fa-1x"></i> first name
          </label>
          <div class="col-md-8">
            <input type="text" name="fname" id="fname" class="form-control" placeholder="First Name">
          </div>
        </div>
        <div class="form-group row">
          <label for="lname" class="col-md-4 text-capitalize font-weight-bold">
            <i class="fas fa-user fa-1x"></i> last name
          </label>
          <div class="col-md-8">
            <input type="text" name="lname" id="lname" class="form-control" placeholder="Last Name">
          </div>
        </div>
        <div class="form-group row">
          <label for="phone" class="col-md-4 text-capitalize font-weight-bold">
            <i class="fas fa-phone fa-1x"></i> phone
          </label>
          <div class="col-md-8">
            <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone">
          </div>
        </div>
        <div class="form-group row">
          <label for="dept" class="col-md-4 text-capitalize font-weight-bold">
            <i class="fas fa-map-marker fa-1x"></i> department
          </label>
          <div class="col-md-8">
            <input type="text" name="dept" id="dept" class="form-control" placeholder="Department">
          </div>
        </div>
        <div class="form-group row">
          <label for="email" class="col-md-4 text-capitalize font-weight-bold">
            <i class="fas fa-envelope fa-1x"></i> email
          </label>
          <div class="col-md-8">
            <input type="email" name="email" id="email" class="form-control" placeholder="Email">
          </div>
        </div>
        <div class="form-group row">
          <label for="regno" class="col-md-4 text-capitalize font-weight-bold">
            <i class="fas fa-user fa-1x"></i> reg number
          </label>
          <div class="col-md-8">
            <input type="text" name="regno" id="regno" class="form-control" placeholder="Registration Number">
          </div>
        </div>
        <div class="form-group row">
          <label for="password" class="col-md-4 text-capitalize font-weight-bold">
            <i class="fas fa-lock fa-1x"></i> password
          </label>
          <div class="col-md-8">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
          </div>
        </div>
        <div class="form-group row">
          <label for="password2" class="col-md-4 text-capitalize font-weight-bold">
            <i class="fas fa-lock fa-1x"></i> verify password
          </label>
          <div class="col-md-8">
        <input type="password" name="v_password" id="password2" class="form-control" placeholder="Verify Password">
          </div>
        </div>

        <div class="form-group row col-md-8 offset-md-4">
          <button type="submit" class="btn btn-success">Register</button>
        </div>
      </form>

      <div class="card-footer">
        <p class="lead text-center">
          Already have an account? login <a href="../index.php">here!</a>
        </p>
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