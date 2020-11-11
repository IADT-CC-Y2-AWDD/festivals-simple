<?php if (isset($errors) && is_array($errors) && array_key_exists(KEY_EXCEPTION, $errors)) { ?>
<div class="alert alert-warning" role="alert"><?= $errors[KEY_EXCEPTION] ?></div>
<?php } ?>
