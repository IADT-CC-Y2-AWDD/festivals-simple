<?php 
require_once 'config.php'; 
require_once 'lib/validation-register.php';

// redirect the user to their home page if they are logged in
if (is_logged_in()) {
  redirect("/home.php");
}
try {
  // get the expected form data
  $data = get_post_params(['email', 'password']);
  // initially we have no validation errors
  $errors = [];

  // validate the email address and password
  validate_email($data['email']);
  validate_password($data['password']);

  // if we have no validation errors check if the email address and password are correct
  if (empty($errors)) {
    $email = $data['email'];
    $password = $data['password'];

    // connect to the database
    $dsn = "mysql:host=".DB_SERVER.";dbname=".DB_DATABASE;
    $conn = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    // retrieve the user details by email address
    $select_sql = "SELECT * FROM users WHERE email = :email";
    $select_params = [
      ":email" => $email
    ];
    $select_stmt = $conn->prepare($select_sql);
    $select_status = $select_stmt->execute($select_params);

    // if there was an error executing the query throw an exception
    if (!$select_status) {
      $error_info = $select_stmt->errorInfo();
      $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
      throw new Exception("Database error executing database query: " . $message);
    }

    $user = null;
    // if we retrieved at least one row retrieve it
    if ($select_stmt->rowCount() !== 0) {
      $user = $select_stmt->fetch(PDO::FETCH_ASSOC);
    }  
    // if there was no user with the specified email address put an error in the errors array
    if ($user === null) {
      $errors['email'] = "Email/password invalid";
    }
    // otherwise check the password submitted against the encrypted password
    else {
      // if the submitted password is incorrect put an error in the errors array
      if (!password_verify($password, $user['password'])) {
        $errors['email'] = "Email/password invalid";
      }
    }
  }
}
catch (PDOException $ex) {
  die($ex->getMessage());
}
catch (Exception $ex) {
  die($ex->getMessage());
}

// if there were no validation errors and the email address and password were correct,
// then log the user in
if (empty($errors)) {
  // to log the user in, store their email address and name in the session array and redirect
  // them to the user home page
  $_SESSION['email'] = $user['email'];
  $_SESSION['name'] = $user['name'];
  redirect("/home.php");
}
// otherwise display the login form again
else {
  require 'login-form.php';
}
?>