<?php require_once 'config.php'; 

// redirect the user to the website home page if they are not logged in
if (!is_logged_in()) {
  redirect("/index.php");
}

// remove the user's email and name from their session data
unset($_SESSION['email']);
unset($_SESSION['name']);

// redirect the user to the website home page
redirect("/index.php");
?>