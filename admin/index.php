<?php
require_once '../login/login-det.php';
require_once '../login/functions.php';

if(isset($_POST['admin_id'], $_POST['password']))
{
  $id       = clean_str(get_post($db_con, 'admin_id'));
  $password = clean_str(get_post($db_con, 'password'));

  if(!empty($id) && !empty($password))
  {
    $sel    = "SELECT * FROM admin WHERE admin_id='$id' AND password='$password'";
    $result = $db_con->query($sel);

    if($result->num_rows > 0)
    {
      for($i = 0; $i < $result->num_rows; $i++)
      {
        $result->data_seek($i);
        $data = $result->fetch_array(MYSQLI_ASSOC);

        $admin_id       = $data['admin_id'];
        $admin_password = $data['password'];
        $admin_name     = $data['name'];
      }

      if($admin_id == $id && $admin_password == $password)
      {
        session_start();
        $_SESSION['admin_id']   = $admin_id;
        $_SESSION['admin_name'] = $admin_name;
        header("location: admin_home.php");
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin Login</title>
  <!--bootstrap 4 css-->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="navbar-text text-capitalize font-weight-bold">admin login</div>
</nav>

<div class="conatainer">
  <div class="row">
    <div class="col-md-4 mx-auto mt-5">
      <div class="card card-body font-weight-bold">
        <form action="" method="post">
          <div class="form-group">
            <label for="admin_id" class="text-capitalize">
              <i class="fas fa-user"></i> admin iD
            </label>
            <input type="text" name="admin_id" id="admin_id" class="form-control" placeholder="Admin ID">
          </div>
          <div class="form-group">
            <label for="password" class="text-capitalize">
              <i class="fas fa-lock"></i> password
            </label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
          </div>
          <div class="form-group d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg font-weight-bold">Log in</button>
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