<?php session_start();
require_once '/home/mir/lib/db.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
    # get iid and pid from URL
    $i = $_GET['iid'];
    $p = $_GET['pid'];
    # unattach the picture from the post
    delete_attachment($p, $i);
    # return to the post we were at 
    header("Location: spec_post.php?pid=$p");
    exit;
    ?>
  </body>
</html>
