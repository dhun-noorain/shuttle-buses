<?php
require_once '../session/sessions.php';
require_once '../login/functions.php';

$error=$success="";
if(isset($_POST['old_password'], $_POST['new_password'], $_POST['ver_password']))
{
  $old_pass = clean_str(get_post($db_con, 'old_password'));
  $new_pass = clean_str(get_post($db_con, 'new_password'));
  $ver_pass = clean_str(get_post($db_con, 'ver_password'));

  if(!empty($old_pass) && !empty($new_pass) && !empty($ver_pass))
  {
    if(check_pw($new_pass, $ver_pass) == true)
    {
      $sel = "SELECT password FROM users WHERE reg_no='$regno' AND password='$old_pass'";
      $result = $db_con->query($sel);
      if(!$result) $error = "Incorrect password";
      else{
        if($result->num_rows > 0)
        {
          $result->data_seek(0);
          $data = $result->fetch_array(MYSQLI_ASSOC);
          $user_pass = $data['password'];

          if($old_pass == $user_pass)
          {
            $update = "UPDATE users SET password='$new_pass' WHERE reg_no='$regno' AND password='$user_pass'";
            if($db_con->query($update)){
              $success = "Successfully changed";
            }else{
              $error = "Failed to change password";
            }
          }else{
            $error = "Incorrect password";
          }
        }else{
          $error = "Incorrect password";
        }
      }
    }else{
      $error = "Passwords do not match";
    }
  }else{
    $error = "All fields are required";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Change Password</title>
  <!--bootstrap 4 css-->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-light bg-dark">
  <a href="update-profile.php" class="navbar-brand btn btn-dark text-light">
    <i class="fas fa-arrow-left"></i>
  </a>
</nav>
<div class="container">
<div class="row">
  <div class="col-md-8 mx-auto mt-5">
    <div class="card card-body">
      <div class="font-weight-bold text-center">
        <span class="text-danger"><?php echo $error; ?></span>
        <span class="text-success"><?php echo $success; ?></span>
      </div>
      <form action="" method="post">
        <div class="form-group">
          <label for="old-pword" class="text-capitalize font-weight-bold">
            <i class="fas fa-lock"></i> old password
          </label>
          <input type="password" name="old_password" id="old-pword" class="form-control" placeholder="Old Password">
        </div>
        <div class="form-group">
          <label for="new-pword" class="text-capitalize font-weight-bold">
            <i class="fas fa-lock"></i> new password
          </label>
          <input type="password" name="new_password" id="new-pword" class="form-control" placeholder="New Password">
        </div>
        <div class="form-group">
          <label for="ver-pword" class="text-capitalize font-weight-bold">
            <i class="fas fa-lock"></i> verify password
          </label>
      <input type="password" name="ver_password" id="ver-pword" class="form-control" placeholder="Verify Password">
        </div>
        <div class="form-group d-flex justify-content-center mt-3">
          <button type="submit" class="text-capitalize btn btn-success btn-lg">change password</button>
        </div>
      </form>
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