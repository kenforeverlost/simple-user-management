<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/lib/actions.php');

  function isValidName($name) {
    $regex = '/^[a-zA-Z -]+$/';

    return preg_match($regex, $name) === 1;
  }

  function isNameProperLength($name) {
    return strlen($name) <= 100;
  }

  function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
  }

  function isEmailProperLength($email) {
    return strlen($email) <= 255;
  }

  function isValidPhone($phone) {
    $regex = '/^[0-9\s\-()+]+$/';

    return preg_match($regex, $phone) === 1;
  }

  function isPhoneProperLength($phone) {
    return strlen($phone) <= 15 && strlen($phone) >= 7;
  }

  function validateUserForm($form,$type){
    $error = [];

    if ($type === 'manage' && empty($form['id'])){
      $error['id'] = 'ID is required';
    }

    if (empty($form['first_name'])){
      $error['first_name'] = 'First name is required';
    } else if (!isValidName($form['first_name'])) {
      $error['first_name'] = 'Name can only contain letters, spaces, and hyphens';
    } else if (!isNameProperLength($form['first_name'])) {
      $error['first_name'] = 'Name cannot exceed 100 characters';
    }

    if (empty($form['last_name'])){
      $error['last_name'] = 'Last name is required';
    } else if (!isValidName($form['last_name'])) {
      $error['last_name'] = 'Name can only contain letters, spaces, and hyphens';
    } else if (!isNameProperLength($form['last_name'])) {
      $error['last_name'] = 'Name cannot exceed 100 characters';
    }

    if (empty($form['email'])){
      $error['email'] = 'Email is required';
    } else if (!isValidEmail($form['email'])){
      $error['email'] = 'Email must be in proper format (example@domain.com)';
    } else if (!isEmailProperLength($form['email'])) {
      $error['email'] = 'Email cannot exceed 255 characters';
    } else {
      $selectResponse = selectUserByEmail($form['email']);

      if (
        empty($selectResponse['error']) &&
        !empty($selectResponse['data']) &&
        ($type === 'manage' && $selectResponse['data']['id'] != $form['id'])
      ) {
        $error['email'] = 'A user with this email already exists.';
      }
    }

    if (!empty($form['phone']) && !isValidPhone($form['phone'])){
      $error['phone'] = 'Phone number can only contain digits, spaces, -, (, ), and +';
    } else if (!empty($form['phone']) && !isPhoneProperLength($form['phone'])) {
      $error['phone'] = 'Phone number must be between 7 and 15 characters';
    }

    if (empty($form['csrf_token']) || !hash_equals($form['csrf_token'], $_SESSION['csrf_token'])) {
      $error['fatal'] = 'CSRF token validation failed.';
    }

    return $error;
  }

  function displayFormError($field, $errorArray) {
    $result = '';
    if (!empty($errorArray) && !empty($errorArray[$field])) {
      ob_start();
      ?>
        <span class="error"><?php echo $errorArray[$field]?></span>
      <?php
      $result = ob_get_clean();
    }
    return $result;
  }
?>