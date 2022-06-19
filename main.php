<?php
session_start();
require_once '/home/mir/lib/db.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <!-- Title on the the site-->
    <title> Timeline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- extra styling-->
    <style>
    a {
      color: black;
    }
    .title{
      color: #0d6efd;
      font-size: 180%;
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

    <!-- container that contains all of the site-->
    <div class="container pt-2">
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
                      if (isset($_SESSION['username'])) {
                      echo "<li><a class='dropdown-item' href='spec_bruger.php?uid=", $_SESSION['username'], "'>Your profile</a></li>";
                      echo "<li><hr class='dropdown-divider'></li>";

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
      <div class="row">

        <!-- First column of site, contains all users  -->
        <div class="col-sm-2 col-md-2">
          <?php
            $uid = get_uids();

            #for each uid in database output the user as a link to their profile page
            foreach ($uid as $u) {
              ?>
              <ul class='list-group'>
              <li class='list-group-item'>
              <?php echo "<a href='spec_bruger.php?uid=", $u, "'>", get_user($u)['firstname'], " ", get_user($u)['lastname'] ,"</a>";?>
              </li>
              </ul>
              <?php
            }
           ?>
        </div>

        <!-- Second column of site, contains the timeline. the main contents of the site-->
        <div class="col-sm-2 col-md-8">
          <?php

            #get all post ids
            $pid = get_pids();
            $re_pid = array_reverse($pid);

            # show post in reverse order
            foreach ($re_pid as $p){
              echo "<br>";
              # save related data to this specific post
              $post_array = get_post($p);
              $uid = $post_array['uid'];
              $iid = get_iids_by_pid($p);
              echo "<ul class='list-group'>";

              # Make a title on the post
              echo "<li class='list-group-item'>";
              echo "<div class='title'>";
              echo "<a href='spec_post.php?pid=", $p, "'>", $post_array['title'], "</a> ";
              echo "</div>";
              echo "</li>";

              # Show name of user who uploaded the post
              echo "<li class='list-group-item'>";
              echo "posted by: ", "<a href='spec_bruger.php?uid=", $uid, "'>", get_user($uid)['firstname'], " ", get_user($uid)['lastname'] ,"</a>";
              echo "</li>";

              #show content of post
              echo "<li class='list-group-item'>";
              echo $post_array['content'], " ";
              echo "</li>";

              #if any images are uploaded with the post, show images
              if (!empty($iid)) {
                echo "<li class='list-group-item'>";
                foreach ($iid as $i) {
                  echo "<img class='img-fluid' src=", get_image($i)['path'], ">";
                }
                echo "</li>";
              }
              echo "</ul>";
            }
            ?>
          </div>
        <!-- Third column, contains nothing-->
        <div class="col-sm-2 col-md-2">
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>
