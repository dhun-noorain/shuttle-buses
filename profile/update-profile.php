<?php
require_once '../session/sessions.php';
require_once '../login/functions.php';

$error=$success="";

$sel = "SELECT * FROM users WHERE reg_no = '$regno'";
$result = $db_con->query($sel);
  if($result->num_rows > 0)
  {
    for($i = 0; $i < $result->num_rows; $i++)
    {
      $result->data_seek($i);
      $data = $result->fetch_array(MYSQLI_ASSOC);

      $fname = $data['first_name'];
      $lname = $data['last_name'];
      $dept  = $data['department'];
      $phone = $data['phone'];
      $email = $data['email'];
    }
  }

$result->close();

  if(isset($_POST['fname'], $_POST['lname'], $_POST['phone'], $_POST['email']))
  {
    $fname = clean_str(get_post($db_con, 'fname'));
    $lname = clean_str(get_post($db_con, 'lname'));
    $phone = clean_str(get_post($db_con, 'phone'));
    $email = clean_str(get_post($db_con, 'email'));

    if(!empty($fname) && !empty($lname) && !empty($phone) && !empty($email))
    {
      $update = "UPDATE users 
                 SET first_name='$fname', 
                 last_name='$lname', 
                 phone='$phone', 
                 email='$email' WHERE reg_no = '$regno'";
      
      if($db_con->query($update) === true)
      {
        $success = "Successfully updated";
      }else{
        $error = "Failed to update";
      }
    }else{
      $error = "<span class='glyphicon glyphicon-warning-sign'></span> Fields can't be empty";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Update Your Profile</title>
  <!--bootstrap 4 css-->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<?php require_once '../session/navigation.php'; ?>

<div class="container">
  <div class="row">
    <div class="col-md-8 mx-auto mt-5">
      <div class="card">
      <div class="card-body">
        <form action="" method="post">
        <div class="text-center">
          <span class="text-danger"> <?php echo $error; ?></span>
          <span class="text-success"> <?php echo $success; ?> </span>
        </div>
          <div class="form-group">
            <label for="fname" class="text-capitalize font-weight-bold">
              <i class="fas fa-user"></i> first name
            </label>
            <input type="text" name="fname" id="fname" class="form-control" value="<?php echo $fname; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="lname" class="text-capitalize font-weight-bold">
              <i class="fas fa-user"></i> last name
            </label>
            <input type="text" name="lname" id="lname" class="form-control" value="<?php echo $lname; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="regno" class="text-capitalize font-weight-bold">
              <i class="fas fa-user"></i> registration number
             </label>
            <input type="text" name="regno" id="regno" class="form-control" value="<?php echo $regno; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="phone" class="text-capitalize font-weight-bold">
              <i class="fas fa-mobile"></i> phone
            </label>
            <input type="text" name="phone" id="phone" class="form-control" value="<?php echo $phone; ?>">
          </div>
          <div class="form-group">
            <label for="dept" class="text-capitalize font-weight-bold">
              <i class="fas fa-map-marker"></i> department
            </label>
            <input type="text" name="dept" id="dept" class="form-control" value="<?php echo $dept; ?>" readonly>
          </div>
          <div class="form-group">
            <label for="email" class="text-capitalize font-weight-bold">
              <i class="fas fa-envelope"></i> email
            </label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo $email; ?>">
          </div>
          <div class="form-group d-flex justify-content-center">
            <button type="submit" class="btn btn-success btn-lg font-weight-bold">
              Update <i class="fas fa-check-circle"></i>
            </button>
          </div>
        </form>
      </div>
      <div class="card-footer">
        <a href="change-password.php" class="btn btn-outline-danger btn-block font-weight-bold">
          Change Password <i class="fas fa-lock"></i>
        </a>
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