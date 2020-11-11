<?php 
require_once 'config.php'; 
require_once 'lib/validation-register.php';

// redirect the user to their home page if they are logged in
if (is_logged_in()) {
  redirect("/home.php");
}
try {
  // $data stores the expected form data
  $data = get_post_params(['email', 'password', 'name']);   
  // $errors stores the error messages if there are any
  // initially we have no validation errors
  $errors = [];  
  
  // validate the email address, password and name
  validate_email($data['email']);
  validate_password($data['password']);
  validate_name($data['name']);

  // if we have no validation errors check 
  // then check if the email address is already registered
  // if the email address is not already registered then register the user
  if (empty($errors)) {
    // get the form data
    $email = $data['email'];
    $password = $data['password'];
    $name = $data['name'];

    // connect to the database
    $dsn = "mysql:host=".DB_SERVER.";dbname=".DB_DATABASE;
    $conn = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    // execute a query to see if there is a user with this email address already registered
    $select_sql = "SELECT * FROM users WHERE email = :email";
    $select_params = [
      ":email" => $email
    ];
    $select_stmt = $conn->prepare($select_sql);
    $select_status = $select_stmt->execute($select_params);

    // if there was an error executing the query then throw an exception
    if (!$select_status) {
      $error_info = $select_stmt->errorInfo();
      $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
      throw new Exception("Database error executing database query: " . $message);
    }

    // if the query returned at least one row 
    // then the email address is already registered so add an error to the errors array
    if ($select_stmt->rowCount() !== 0) {
      $errors['email'] = "Email/password invalid";
    }  
    // otherwise the email address is not already registered so this new users details can be
    // added to the database
    else {
      // execute a query to add the users details to the database
      $params = [
        ":email" => $email,
        ":password" => $password,
        ":name" => $name
      ];
      $sql = "INSERT INTO users (email, password, name) VALUES (:email, :password, :name)";
      $stmt = $conn->prepare($sql);
      $status = $stmt->execute($params);

      // if there was an error executing the query then throw an exception
      if (!$status) {
        $error_info = $stmt->errorInfo();
        $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      // if the query did not insert exactly one row then throw an exception
      if ($stmt->rowCount() !== 1) {
        throw new Exception("Failed to save user.");
      }
    }
  }
}
// if an exception was thrown then halt the script and display an error message
catch (PDOException $ex) {
  die($ex->getMessage());
}
catch (Exception $ex) {
  die($ex->getMessage());
}

// if there were no validation errors and the email address was not already registered
// then log the user in
if (empty($errors)) {
  // to log the user in, store their email address and name in the session array and redirect
  // them to the user home page
  $_SESSION['email'] = $email;
  $_SESSION['name'] = $name;
  redirect("/home.php");
}
// otherwise display the login form again
else {
  require 'register-form.php';
}
?>