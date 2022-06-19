<?php
session_start();
require_once '/home/mir/lib/db.php';
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Modify post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style media="screen">
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
      </div>
      <div class="col-sm-2 col-md-8">
    <?php
    $p = $_GET["pid"];
    $post_array = get_post($p);
    # Form for modifying your post
    echo "<form action='modify_post.php?pid=$p' method='post' enctype='multipart/form-data'>";
    ?>
      <div class="mb-3">
        <!-- Title of the post already containing the prior title so you dont have to rewrite the title-->
        <label for="exampleFormControlInput1" class="form-label">Title</label>
        <input type="text" class="form-control" name="title" id="exampleFormControlInput1" value="<?php echo $post_array['title']; ?>">
      </div>
      <div class="mb-3">
        <!-- Content of the post already containing the prior content so you dont have to rewrite the title-->
        <label for="exampleFormControlTextarea1" class="form-label">Post</label>
        <textarea class="form-control" name="content" id="exampleFormControlTextarea1" rows="3"><?php echo $post_array['content']; ?></textarea>
      </div>
      <div class="mb-3">
        <!-- if desired, you can add a picture to the post-->
        <label for="formFile" class="form-label">Add a picture</label>
        <input class="form-control" type="file" id="formFile" name="picture">
      </div>
      <div class="mb-3">
        <?php
        #show all images for this post, so user can see what pictures are on the post already
        $iid = get_iids_by_pid($p);
        foreach ($iid as $i) {
            echo "<img class='img-fluid' src=", get_image($i)['path']," width='700' alt='Computer Hope'>";
          }
         ?>
      </div>
      <input class='btn btn-primary' type='submit' name="submit" value='Modify'>
    </form>
    <?php
    # Saved values from the File form, containing the mime type and the temp_path
    $file_mime_type = $_FILES['picture']['type'];
    $filepath = $_FILES['picture']['tmp_name'];

    # Convert from mime to filetype. contains jpeg, PNG, gif, and jpg. can be expanded
    if ($file_mime_type == "image/jpeg") {
      $filetype =".jpeg";
    }elseif ($file_mime_type == "image/png") {
      $filetype = ".PNG";
    }elseif ($file_mime_type == "image/gif") {
      $filetype = ".gif";
    }elseif ($file_mime_type == "image/jpg") {
      $filetype = ".jpg";
    }

    # if there has been uploaded any picture in the File form, add it to database and attach it to the post
    if (isset($filepath) and isset($filetype)) {
      add_image($filepath, $filetype);
      add_attachment(end(get_pids()),end(get_iids()));
    }
    # If the submit form is filled correctly, make a new post containing the Title and content from the form
    $title = $_POST["title"];
    $content = $_POST["content"];
    if (isset($p) and isset($content) and isset($title)) {
      modify_post($p, $title, $content);
      echo "We are in the if statemnt";
      header("location: main.php");
    }else {
      echo "post made incorrectly. Please check that your content and title are filled out";
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
