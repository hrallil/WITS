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
    # get the pid and cid for deleting the comment
    $c = $_GET['cid'];
    $p = $_GET['pid'];
    # delete the comment
    delete_comment($c);
    # send us back to the post we were at
    header("Location: spec_post.php?pid=$p");
    exit;
    ?>
  </body>
</html>
