<?php
session_start();
require_once '/home/mir/lib/db.php';
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
      a {
        color: black;
      }
      .user_page{
        color: #0d6efd;
        font-size: 250%;
      }
      .title{
        font-size: 180%;
      }
      body{
        background-color: linen;
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
            $uid = get_uids();
            #for each uid in database output the user as a link to their profile page
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
          <?php

          # Make title on the site containing the name of the user whoms side you're on
          $u = $_GET['uid'];
          echo "<div class='user_page'>";
          echo "Welcome to ", get_user($u)['firstname'], " ", get_user($u)['lastname'], "'s page";
          echo "</div>";

          #get array of post id's (pid) for all post related to the user, for each pid do as follows
          $post_array = get_pids_by_uid($u);
          $re_post_array = array_reverse($post_array);
          foreach ($re_post_array as $p_a) {
            echo "<br>";
            $post_by_user = get_post($p_a);

            # Post the title of the post as a link refering to the post page itself.
            echo "<ul class='list-group'>";
            echo "<li class='list-group-item'>";
            echo "<div class='title'>";
            echo "<a href='spec_post.php?pid=", $p_a, "'>", $post_by_user['title'], "</a> ";
            echo " - ";
            echo $post_by_user['date'];
            echo "</div>";
            echo "</li>";

            # Output content of post
            echo "<li class='list-group-item'>";
            echo $post_by_user['content'];
            echo "</li>";

            # Make comment section
            $cids = get_cids_by_pid($p_a);
            echo "<li class='list-group-item'>";
            echo "<h4> Comments: </h4>";
            
            # if there are no comments, display "no comments"
            if (sizeof($cids) == 0) {
                echo " No comments";
            }
            # if there are more than 0 comments, show comments
            else {
              foreach ($cids as $c) {
                $comment_uid = get_comment($c)['uid'];
                echo "<ul class='list-group'>";
                echo "<li class='list-group-item'>";
                echo get_comment($c)['content']," - ",  get_user($comment_uid)['firstname'], " ", get_user($comment_uid)['lastname'];
                echo ", at: ",get_comment($c)['date'];
                echo "<br>";
                echo '</li>';
                echo "</ul>";
              }
            }
            echo "</li>";
            echo "</ul>";
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
