<?php
require_once 'config.php';

try {
  $festivals = array();

  // connect to the database
  $dsn = "mysql:host=".DB_SERVER.";dbname=".DB_DATABASE;
  $conn = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // execute a query to get the festivals from the database
  $select_sql = "SELECT * FROM festivals";
  $select_stmt = $conn->prepare($select_sql);
  $select_status = $select_stmt->execute();

  // if the query did not execute properly throw an exception
  if (!$select_status) {
    $error_info = $select_stmt->errorInfo();
    $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
    throw new Exception("Database error executing database query: " . $message);
  }

  // retrieve the festivals into an array
  if ($select_stmt->rowCount() !== 0) {
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
    while ($row !== FALSE) {
      $festivals[] = $row;
      $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
    }
  }
}
catch (PDOException $ex) {
  die($ex->getMessage());
}
catch (Exception $ex) {
  die($ex->getMessage());  
}
finally {
  $conn = null;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Music festivals</title>

    <link href="<?= APP_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= APP_URL ?>/assets/css/template.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <?php require 'include/header.php'; ?>
      <?php require 'include/navbar.php'; ?>
      <main role="main">
        <div>
          <h1>Our festivals</h1>
          <div class="row">
          <!-- for each festival, display a card with some of the details of the festival -->
          <?php foreach ($festivals as $festival) { ?>
            <div class="col mb-4">
              <div class="card" style="width:15rem;">
                <img src="<?= APP_URL ?>/assets/img/default.jpg" class="card-img-top" alt="...">
                <div class="card-body">
                  <h5 class="card-title"><?= $festival["title"] ?></h5>
                  <p class="card-text"><?= get_words($festival["description"], 20) ?></p>
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Location: <?= $festival["location"] ?></li>
                  <li class="list-group-item">Start date: <?= $festival["start_date"] ?></li>
                  <li class="list-group-item">End date: <?= $festival["end_date"] ?></li>
                </ul>
              </div>
            </div>
          <?php } ?>
          </div>
        </div>
      </main>
      <?php require 'include/footer.php'; ?>
    </div>
    <script src="<?= APP_URL ?>/assets/js/jquery-3.5.1.min.js"></script>
    <script src="<?= APP_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
