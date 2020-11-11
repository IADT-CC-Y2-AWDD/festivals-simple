<?php
function get_post_params($allowed_params=[]) {
	$allowed_array = [];
	foreach($allowed_params as $param) {
		if(isset($_POST[$param])) {
			$allowed_array[$param] = $_POST[$param];
		}
    else {
			$allowed_array[$param] = NULL;
		}
	}
	return $allowed_array;
}
function is_present($value) {
	if (is_array($value)) {
    return TRUE;
  }
  else {
    $trimmed_value = trim($value);
    return isset($trimmed_value) && $trimmed_value !== "";
  }
}
function has_length($value, $options=[]) {
	if(isset($options['max']) && (strlen($value) > (int)$options['max'])) {
		return false;
	}
	if(isset($options['min']) && (strlen($value) < (int)$options['min'])) {
		return false;
	}
	if(isset($options['exact']) && (strlen($value) != (int)$options['exact'])) {
		return false;
	}
	return true;
}
function is_safe_email($email) {
  $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);
  return strcmp($email, $sanitized_email) === 0;
}
function is_valid_email($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL) !== FALSE;
}
function has_no_html_tags($value) {
  return strcmp($value, strip_tags($value)) === 0;
}
?>