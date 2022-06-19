<?php
session_start();
require_once '/home/mir/lib/db.php';
?>

<!doctype html>
<html>
  <head>
    <title>Create a post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
      a {
        color: black;
      }
      body {
        background-color: linen
      }

    </style>
  </head>
  <body>
    <?php
    if (isset($_SESSION['username'])) {
      $welcome_msg = "welcome %s %s";
    }
    else {
      $welcome_msg = "No user is logged in";
    }?>
    <!-- welcome msg on top of site-->
    <div class="container-fluid p-5 bg-primary text-white text-center">
          <h1>Fjæsbog</h1>
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
        <?php
          # for each uid in database output the user as a link to their profile page
          $uid = get_uids();
          foreach ($uid as $u) {
            echo "<ul class='list-group'>";
            echo "<li class='list-group-item'>";
            echo "<a href='spec_bruger.php?uid=", $u, "'>", get_user($u)['firstname'], " ", get_user($u)['lastname'] ,"</a>";
            echo "</li>";
            echo "</ul>";
          }
         ?>
      </div>
      <div class="col-sm-2 col-md-8">
        <!-- form for making a title and content to a post-->
        <form action="upload.php" method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Title</label>
            <input type="text" class="form-control" name="titel" id="exampleFormControlInput1" placeholder="Title">
          </div>
          <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Post</label>
            <textarea class="form-control" name="content" placeholder="your post here..." id="exampleFormControlTextarea1" rows="3"></textarea>
          </div>
          <!-- form for adding a picture-->
          <div class="mb-3">
            <label for="formFile" class="form-label">Add a picture</label>
            <input class="form-control" type="file" id="formFile" name="picture">
          </div>
          <input class='btn btn-primary' type='submit' name="submit" value='Post!'>
        </form>
        <?php
        # Save values from forms into varuables
        $u = $_POST["bruger"];
        $title = $_POST["titel"];
        $content = $_POST["content"];
        $file_mime_type = $_FILES['picture']['type'];
        $filepath = $_FILES['picture']['tmp_name'];

        # Convert from mime to filetype
        if ($file_mime_type == "image/jpeg") {
          $filetype =".jpeg";
        }elseif ($file_mime_type == "image/png") {
          $filetype = ".PNG";
        }elseif ($file_mime_type == "image/gif") {
          $filetype = ".gif";
        }elseif ($file_mime_type == "image/jpg") {
          $filetype = ".jpg";
        }

        add_post($_SESSION['username'], htmlentities($title), htmlentities($content));
        # if picture has been uploaded, make attachment to picture and upload it
        if (isset($filepath) and isset($filetype)) {
          add_image($filepath, $filetype);
          add_attachment(end(get_pids()),end(get_iids()));
        }
        # for de-bugging
        if (false){
          echo "<pre>";
          echo "name: ", $_FILES['picture']['name'];

          echo " mime type: ", $_FILES['picture']['type'];
          echo " type: ", gettype($_FILES['picture']['type']);
          echo " File type: ", $filetype;
          echo " size: ", $_FILES['picture']['size'];

          echo " tmp_name: ", $_FILES['picture']['tmp_name'];
          echo " tmp_name: ", gettype($_FILES['picture']['tmp_name']);
          echo " error: ", $_FILES['picture']['error'];
          echo "</pre>";
          echo "<img src='", $filename, "'>";
        }
        ?>
      </div>
      <div class="col-sm-2 col-md-2">
      </div>
    </div>
  </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>
