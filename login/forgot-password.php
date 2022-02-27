<?php
require_once 'login-det.php';
require_once 'functions.php';

$error=$success="";
if(isset($_POST['phone'], $_POST['email'], $_POST['regno']))
{
  $phone = clean_str(get_post($db_con, 'phone'));
  $email = clean_str(get_post($db_con, 'email'));
  $regno = clean_str(get_post($db_con, 'regno'));

  if(!empty($phone) && !empty($email) && !empty($regno))
  {
    if(val_regno($regno) == true && filter_var($email, FILTER_VALIDATE_EMAIL))
    {
      if(val_phone($phone) == true)
      {
        $sel = "SELECT password, reg_no, email, phone FROM users 
                      WHERE reg_no='$regno' AND phone='$phone' AND email='$email'";
        $result = $db_con->query($sel);

        if(!$result->num_rows < 0) $error = "Couldn't retrieve your information.";
        else{

            $result->data_seek(0);
            $data = $result->fetch_array(MYSQLI_ASSOC);

            $password = $data['password'];
            $u_regno  = $data['reg_no'];
            $u_email  = $data['email'];
            $u_phone  = $data['phone'];

          if($email == $u_email && $u_phone == $phone && $u_regno == $regno)
          {
            	$to = "$email";
            	$subject = "Password Reset";
            	$message = "Your password is $password. Please click ";
            	$message .= "http://localhost/web-projects/shuttle-bus/index.php";
            	
            	if(mail($to, $subject, $message))
            	{
            		$success = "<br>*Congratulations, your password has been sent to your email.";
            	}else{
            		$error = "<br>Can't reset password at the moment, please try again. <br />";
              }
          }else{
            $error = "<br>Couldn't retrieve account with the details provided.";
          }
        }//end of else
        $result->close();
      }else{
        $error = "<br>Invalid phone number";
      }
    }else{
      $error = "<br>Sorry, couldn't find <strong>$regno</strong>";
    }
  }else{
    $error = "<br>Please fill all fields";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Forgot Password</title>
  <!--bootstrap 4 css-->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<nav class="navbar bg-dark py-3">
  <a href="../index.php" class="btn btn-dark"> <i class="fas fa-arrow-left text-light"></i> </a>
</nav>

<!--password recovery form-->
<div class="container">
  <div class="row">
    <div class="col-md-8 mx-auto py-5">
      <div class="card">
        <div class="card-header text-justify">
          <p class="lead">
            Provide all neccessary information in order to recover your forgotten password!
          </p>

          <p>
            <strong>Note:</strong> 
            <span class="text-danger font-weight-bold"> 
              all information provided must correspond to the information on your profile 
            </span>
          </p>
        </div>

        <div class="card-body">
          <form action="" method="post" class="text-capitalize">
            <div class="text-center mb-3">
              <span class="text-danger"> <?php echo $error; ?> </span>
              <span class="text-success"> <?php echo $success; ?> </span>
            </div>

            <div class="form-group">
              <label for="phone">
                <i class="fas fa-mobile fa-1x"></i> phone number
              </label>
              <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone Number">
            </div>
            <div class="form-group">
              <label for="email">
                <i class="fas fa-envelope fa-1x"></i> email
              </label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Email">
            </div>
            <div class="form-group">
              <label for="regno">
                <i class="fas fa-user fa-1x"></i> reigstration number
              </label>
              <input type="text" name="regno" id="regno" class="form-control" placeholder="Resgistration Number">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary font-weight-bold">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end of password recovery form-->

  <!--jquery-->
  <script src="../js/jquery.min.js"></script>
  <!--bootstrap 4 js-->
  <script src="../js/bootstrap.bundle.min.js"></script>
  <!--fontawesome-->
  <script src="https://kit.fontawesome.com/14e0c06b3c.js" crossorigin="anonymous"></script>
</body>
</html>
<?php $db_con->close(); ?>