<?php
session_start();
require_once '/home/mir/lib/db.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Log in or make account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style media="screen">
      body{
        background-color: linen
      }
    </style>
  </head>
  <body>
    <?php
    # if the user is loggen in, set welcome msg
    if (isset($_SESSION['username'])) {
      $welcome_msg = "welcome %s %s";
    }
    # else display "no user logged in"
    else {
      $welcome_msg = "No user is logged in";
    }?>
    <!-- welcome msg on top of site-->
    <div class="container-fluid p-5 bg-primary text-white text-center">
          <h1>Fjæsbog</h1>
          <!-- display welcome msg -->
          <p><?php echo sprintf($welcome_msg, get_user($_SESSION['username'])['firstname'],get_user($_SESSION['username'])['lastname']); ?></p>
        </div>
    <!-- The navbar of the site-->
    <nav class="navbar fixed-top navbar-dark bg-dark">

        <div class="container-fluid">
            <a class="navbar-brand" href="main.php">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="main.php">Home</a>
                </li>
                <?php
                if (isset($_SESSION['username'])) {
                  echo "<li class='nav-item'>";
                  echo "<a class='nav-link' href='upload.php'> Create a post</a>";
                  echo "</li>";
                }
                 ?>

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Profile
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <?php
                    echo "<li><a class='dropdown-item' href='spec_bruger.php?uid=", $_SESSION['username'], "'>Your profile</a></li>";
                     ?>
                    <li><hr class="dropdown-divider"></li>
                    <?php
                    if (isset($_SESSION['username'])) {
                      echo "<li><a class='dropdown-item' href='log_out.php'> log out </a></li>";
                    }
                    else {
                      echo "<li><a class='dropdown-item' href='login.php'> Log in </a></li>";
                    }
                     ?>

                  </ul>
                </li>
              </ul>
            </div>
          </div>
    </nav>

    <div class="container pt-2">
      <div class="row">
      <div class="col-sm-2 col-md-2">
      </div>
      <div class="col-sm-2 col-md-8">
    <?php
    # if the log in form is filled send them to the main page
    if ( login($_POST['username'], $_POST['password'])) {
      $_SESSION['username'] = $_POST['username'];
      header("Location: main.php");
    }
    # if the new profile form is filled out, make a new user
    elseif (isset($_POST['new_username']) && isset($_POST['new_password']) && isset($_POST['new_firstname']) && isset($_POST['new_lastname'])) {
      add_user($_POST['new_username'], $_POST['new_firstname'], $_POST['new_lastname'], $_POST['new_password']);
    }
    ?>
    <p> Please log in below:  </p>
    <!-- log in form-->
    <form action='login.php' method='post'>
        <label for='floatingInputValue'>Username</label>
        <input type='text' name='username' class='form-control' id='floatingInputValue' placeholder='Username'>
        <label for='floatingInputValue'>Password</label>
        <input type='password' name='password' class='form-control' id='floatingInputValue' placeholder='password'>
        <br>
        <input class='btn btn-primary' type='submit' value='log in'>
    </form>

    <!-- Make a new user form-->
    <p>Don't have a login? NO WORRIES. Sign up now!</p>
    <form action='login.php' method='post'>
      <label for='floatingInputValue'>Username</label>
      <input type='text' name='new_username' class='form-control' id='floatingInputValue' placeholder='Username'>

      <label for='floatingInputValue'>Password</label>
      <input type='text' name='new_password' class='form-control' id='floatingInputValue' placeholder='Password'>

      <label for='floatingInputValue'>Firstname</label>
      <input type='text' name='new_firstname' class='form-control' id='floatingInputValue' placeholder='Firstname'>

      <label for='floatingInputValue'>Lastname</label>
      <input type='text' name='new_lastname' class='form-control' id='floatingInputValue' placeholder='Lastname'>
      <br>
        <input class='btn btn-primary' type='submit' value='sign up!'>
    </form>
    </div>
    <div class="col-sm-2 col-md-2">
    </div>
  </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  </body>
</html>
