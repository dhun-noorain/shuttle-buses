<?php
require_once '../session/sessions.php';
require_once '../login/functions.php';
$error=$success="";
if(isset($_POST['message']))
{
  if(!empty($_POST['message']))
  {
    $message = clean_str(get_post($db_con, 'message'));

    $sel = "SELECT email  FROM users WHERE reg_no='$regno'";
    $result = $db_con->query($sel);
    $result->data_seek(0);
    $data = $result->fetch_array(MYSQLI_ASSOC);
    

    $email = $data['email'];

    $to = "harunafaruk64@gmail.com";
    $subject = "Complain";
    $body = "$message";
    $header = "From $email";

      if(mail($to, $subject, $body, $header))
        $success = "Your complain has been successfully delivered, thank you!";
      else{
        $error = "There was an error sending your mail.";
      }
    $result->close();
  }else{
    $error = "Please enter a valid complain, thank you";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Report a problem</title>
  <!--bootstrap 4 css-->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<?php require_once '../session/navigation.php'; ?>

<div class="container">
  <div class="row">
    <div class="col-md-8 mx-auto mt-5">
      <div class="card card-body">
        <form action="" method="post">

        <div class="font-weight-bold text-center">
          <span class="text-danger"><?php echo $error; ?></span>
          <span class="text-success"><?php echo $success; ?></span>
        </div>

      <div class="form-group">
        <textarea name="message" cols="30" rows="10" placeholder="Message Here..." class="form-control"></textarea>
      </div>
      <div class="form-group d-flex justify-content-end">
        <button type="submit" class="btn btn-success btn-lg font-weight-bold">
          Send <i class="fas fa-paper-plane"></i>
        </button>
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