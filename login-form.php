<?php 
require_once 'config.php'; 

// redirect the user to their home page if they are logged in
if (is_logged_in()) {
  redirect("/home.php");
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Form validation example</title>

    <link href="<?= APP_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= APP_URL ?>/assets/css/template.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/form.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <?php require 'include/header.php'; ?>
      <?php require 'include/navbar.php'; ?>
      <main role="main">
        <?php require "include/exception.php"; ?>
        <h1>Login Form</h1>
        <form name='login' action="login.php" method="post">

          <div class="form-field">
            <label for="email">Email:</label>
            <!-- display an input text box with the previous value if any -->
            <input type="text" name="email" id="email" value="<?= input("email") ?>" />
            <!-- display span element with a validation error message if any -->
            <span class="error"><?= error("email") ?></span>
          </div>

          <div class="form-field">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" />
            <!-- display span element with a validation error message if any -->
            <span class="error"><?= error("password") ?></span>
          </div>

          <div class="form-field">
            <label></label>
            <input type="submit" name="submit" value="Submit" />
          </div>

        </form>
      </main>
      <?php require 'include/footer.php'; ?>
    </div>
    <script src="<?= APP_URL ?>/assets/js/jquery-3.5.1.min.js"></script>
    <script src="<?= APP_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
