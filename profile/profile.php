<?php
require_once '../session/sessions.php';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>View Profile</title>
  <!--bootstrap 4 css-->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <style>
  @media(max-width: 40em)
  {
    .card{
      font-size: 0.7rem;
    }
  }
  </style>
</head>
<body>
<?php require_once '../session/navigation.php'; ?>
<div class="container">
  <div class="row">
    <div class="col-md-8 mx-auto mt-5">
      <div class="card card-body text-capitalize">
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <tbody>
              <tr>
                <th> <i class="fas fa-user"></i> first name</th>
                <td><?php echo $fname; ?></td>
              </tr>
              <tr>
                <th> <i class="fas fa-user"></i> last name</th>
                <td><?php echo $lname; ?></td>
              </tr>
              <tr>
                <th> <i class="fas fa-user"></i> reg number</th>
                <td><?php echo $regno; ?></td>
              </tr>
              <tr>
                <th> <i class="fas fa-map-marker"></i> department</th>
                <td><?php echo $dept; ?></td>
              </tr>
              <tr>
                <th> <i class="fas fa-envelope"></i> email</th>
                <td><?php echo $email; ?></td>
              </tr>
              <tr>
                <th> <i class="fas fa-mobile"></i> phone</th>
                <td><?php echo $phone; ?></td>
              </tr>
            </tbody>
          </table>
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