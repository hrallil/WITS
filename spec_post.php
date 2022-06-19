<?php
session_start();
require_once '/home/mir/lib/db.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
    a {
      color: black;
    }
    body{
      background-color: linen;
    }
    .title{
      font-size: 200%;
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

          #for each uid in database output the user as a link to their profile page
          $uid = get_uids();
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
      <div class="col-sm-2 col-md-8">
    <?php

      $p = $_GET['pid'];
      $post_array = get_post($p);
      $uid = $post_array['uid'];

      #output the headline of post
      echo "<ul class='list-group'>";
      echo "<li class='list-group-item'>";
      echo "<div class='title'>";
      echo $post_array['title'], " ";
      echo "</div>";
      echo "</li>";

      # If the user made the post, display modify post as link leading to site that allows for editing the post
      if ($_SESSION['username'] == $post_array['uid']) {
        echo "<li class='list-group-item'>";
        echo "<a href='modify_post.php?pid=", $p, "'> Modify post </a> ";
        echo "</li>";
      }
      # Output auther of post as link to autors page
      echo "<li class='list-group-item'>";
      echo "<a href='spec_bruger.php?uid=", $uid, "'>", get_user($uid)['firstname'], " ", get_user($uid)['lastname'] ,"</a>";
      echo "</li>";

      # Output post content
      echo "<li class='list-group-item'>";
      echo $post_array['content'], " ";
      echo "</li>";

      # if any output images that may be related to the post
      $iid = get_iids_by_pid($p);
      if (isset($iid)) {
        echo "<li class='list-group-item'>";
        foreach ($iid as $i) {
          echo "<img class='img-fluid' src=", get_image($i)['path'], ">";
          # If you are the author of the post, make delete button for the picture leading to delete_image.php
          if ($_SESSION['username'] == $post_array['uid']) {
            echo "<br>";
            echo "<a class='btn btn-primary' role='button' href='delete_image.php?pid=", $p,"&iid=", $i,"'>Delete image</a>";
            echo "<br>";
          }
        }
      echo '</li>';
      }
      echo "</ul>";

      # Make a comment section and output all comments related to the post
      echo "<h4>Comments:</h4>";
      # logged in as any user, show a from to make a comment
      if (isset($_SESSION['username'])) {
        ?>
        <form action='spec_post.php?pid=<?php echo $p; ?>' method='post' enctype='multipart/form-data'>
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Your comment</label>
            <input type="text" class="form-control" name='comment' id="exampleFormControlInput1" placeholder="your comment here...">
          </div>
          <input class='btn btn-primary' type='submit' value='comment'>
        </form>
        <?php
        echo $_GET['comment'];
        # Add comment from the form
        add_comment($_SESSION['username'], $p, $_POST['comment']);
      }
      # Get all comments related to post,
      $cids = get_cids_by_pid($p);
      $re_cids = array_reverse($cids);
      foreach ($re_cids as $c) {
        $comment_uid = get_comment($c)['uid'];
        echo get_comment($c)['content'], " - <a href='spec_bruger.php?uid=", $comment_uid, "'>", get_user($comment_uid)['firstname'], " ", get_user($comment_uid)['lastname'] ,"</a>";
        echo ", at: ",get_comment($c)['date'], "  ";
        # if you made the comment or are the author of the post, make delete button to delete post
        if ($_SESSION['username'] == $comment_uid or $_SESSION['username'] == $post_array['uid'] ) {
          echo "<a class='btn btn-primary' role='button' href='delete_comment.php?pid=", $p,"&cid=", $c,"'>Delete</a>";
        }
        echo "<p>";
      }
      ?>
      </div>
      <div class='col-sm-2 col-md-8'>
      </div>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>
