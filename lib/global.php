<?php
function redirect($url) {
  header("Location: ".APP_URL.$url);
  exit();
}

function is_logged_in() {
  return (isset($_SESSION) && is_array($_SESSION) && array_key_exists("email", $_SESSION));
}

function input($key) {
  global $data;
  if (isset($data) && is_array($data) && array_key_exists($key, $data)) {
    return $data[$key];
  }
  else {
    return null;
  }
}

function error($key) {
  global $errors;
  if (isset($errors) && is_array($errors) && array_key_exists($key, $errors)) {
    return $errors[$key];
  }
  else {
    return null;
  }
}

function get_words($text, $count = 10) {
  return implode(' ', array_slice(explode(' ', $text), 0, $count));
}
?>
