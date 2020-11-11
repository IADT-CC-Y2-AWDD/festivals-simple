<?php require_once 'config.php'; ?>

<?php
if (!is_logged_in()) {
  redirect("/index.php");
}

unset($_SESSION['email']);
unset($_SESSION['name']);

redirect("/index.php");
?>