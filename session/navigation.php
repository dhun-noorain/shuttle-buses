<?php 
  if(!isset($_SESSION['fname'], $_SESSION['lname'], $_SESSION['regno']))
  {
    header("location: ../index.php");
  }else if(isset($_SESSION['fname'], $_SESSION['lname'], $_SESSION['regno']))
        $regno = $db_con->real_escape_string($_SESSION['regno']);
        $fname  = $db_con->real_escape_string($_SESSION['fname']);
        $lname  = $db_con->real_escape_string($_SESSION['lname']);

        if(empty($regno) && empty($fname) && empty($lname))
        {
          header("location: ../index.php");
        }

  if(isset($_GET['logout'])){
    session_destroy();
    unset($_SESSION['regno'], $_SESSION['fname'], $_SESSION['lname']);
    $db_con->close();
    header("location: ../index.php");
  }
?>

<nav class="navbar navbar-light bg-light navbar-expand-md shadow-sm">

  <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav-links">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="nav-links">
    <ul class="navbar-nav font-weight-bold ml-auto">
      <li class="nav-item active"><a href="../home/index.php" class="nav-link">
        <i class="fas fa-home"></i> Home
      </a></li>
      <div class="dropdown">
        <li class="nav-item"><a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <i class="fas fa-user"></i> Profile
          <div class="dropdown-menu bg-light">
            <a href="../profile/profile.php" class="dropdown-item font-weight-bold"> <i class="fas fa-eye"></i>       View Profile 
            </a>
            
        <a href="../profile/update-profile.php" class="dropdown-item font-weight-bold"><i class="fas fa-sync"></i> 
              Update Profile 
            </a>
          </div>
          </a>
        </li>
      </div>
      <li class="nav-item"><a href="../reserve/reserve.php" class="nav-link">
        <i class="fas fa-couch"></i> Reserve a seat
      </a></li>
      <li class="nav-item"><a href="../report/report.php" class="nav-link">
        <i class="fas fa-cog"></i> Report a problem
      </a></li>
      <li class="nav-item">
        <a href="../session/sessions.php?logout=1" class="nav-link">
        <i class="fas fa-power-off"></i> Logout </a>
      </li>
    </ul>
  </div>
</nav>