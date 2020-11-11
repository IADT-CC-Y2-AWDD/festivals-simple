<?php 
require_once 'config.php'; 
require_once 'lib/validation-register.php';

if (is_logged_in()) {
  redirect("/home.php");
}
try {
  $data = get_post_params(['email', 'password', 'name']);
  $errors = [];

  validate_email($data['email']);
  validate_password($data['password']);
  validate_name($data['name']);

  if (empty($errors)) {
    $email = $data['email'];
    $password = $data['password'];
    $name = $data['name'];

    $dsn = "mysql:host=".DB_SERVER.";dbname=".DB_DATABASE;
    $conn = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    $select_sql = "SELECT * FROM users WHERE email = :email";
    $select_params = [
      ":email" => $email
    ];
    $select_stmt = $conn->prepare($select_sql);
    $select_status = $select_stmt->execute($select_params);

    if (!$select_status) {
      $error_info = $select_stmt->errorInfo();
      $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
      throw new Exception("Database error executing database query: " . $message);
    }

    if ($select_stmt->rowCount() !== 0) {
      $errors['email'] = "Email/password invalid";
    }  
    else {
      $params = [
        ":email" => $email,
        ":password" => $password,
        ":name" => $name
      ];
      $sql = "INSERT INTO users (email, password, name) VALUES (:email, :password, :name)";
      $stmt = $conn->prepare($sql);
      $status = $stmt->execute($params);

      if (!$status) {
        $error_info = $stmt->errorInfo();
        $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      if ($stmt->rowCount() !== 1) {
        throw new Exception("Failed to save user.");
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

if (empty($errors)) {
  $_SESSION['email'] = $email;
  $_SESSION['name'] = $name;
  redirect("/home.php");
}
else {
  require 'register-form.php';
}
?>