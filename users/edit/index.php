<?php
  session_start();
  require_once($_SERVER['DOCUMENT_ROOT'].'/lib/actions.php');
  require_once($_SERVER['DOCUMENT_ROOT'].'/lib/helpers.php');

  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }

  $id = !empty($_GET['id']) || $_GET['id'] === '0' ? $_GET['id'] : null;
  $sessionToken = !empty($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';
  $form = [
    'id' => '',
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'phone' => '',
    'csrf_token' => '',
  ];
  $formError = [];
  $errorMessage = '';

  if ($id === null) {
    $errorMessage = 'The link you clicked does not work.';
  } else {
    $selectResponse = selectUserById($id);

    if (!empty($selectResponse['error'])) {
      $errorMessage = $selectResponse['error'];
    } else {
      $form = $selectResponse['data'];
    }

    if (!empty($_POST['submit'])){
      $form['id'] = $id;
      $form['first_name'] = trim($_POST['first_name']);
      $form['last_name'] = trim($_POST['last_name']);
      $form['email'] = trim($_POST['email']);
      $form['phone'] =  trim($_POST['phone']);
      $form['csrf_token'] =  $_POST['csrf_token'];

      $formError = validateUserForm($form,'manage');

      if (empty($formError)){
        $updateResponse = updateUser($form);

        if (empty($updateResponse)) {
          $_SESSION['flash_success'] = 'User updated successfully.';
          header('Location: /index.php');
        } else {
          $formError['fatal'] = 'There was an error while updating the user.';
        }
      }
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Manage</title>
    <meta name="description" content="This page allows the user to edit or delete a user from the database.">
    <link href="/css/styles.css" rel="stylesheet">
    <script src="/js/edit.js"></script>
  </head>
  <body>
    <div class="container">
      <div>
        <h1>Edit User</h1>
      </div>
      <div>
        <?php if (!empty($errorMessage)): ?>
          <div class="card">
            <div class="page-alert">
              <div><h2>Invalid Link</h2></div>
              <div class="error"><?php echo $errorMessage ?></div>
            </div>
          </div>
        <?php else: ?>
          <form action="" method="post" class="card" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo $sessionToken ?>">
            <input hidden type='number' id="id" name="id" value="<?php echo $id ?>">
            <div>
              <label for="first_name">First Name</label>
              <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($form['first_name']) ?>">
              <?php echo displayFormError('first_name',$formError) ?>
            </div>
            <div>
              <label for="last_name">Last Name</label>
              <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($form['last_name']) ?>">
              <?php echo displayFormError('last_name',$formError) ?>
            </div>
            <div>
              <label for="email">Email</label>
              <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($form['email']) ?>">
              <?php echo displayFormError('email',$formError) ?>
            </div>
            <div>
              <label for="phone">Phone</label>
              <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($form['phone']) ?>">
              <?php echo displayFormError('phone',$formError) ?>
            </div>
            <div class="form-btn-wrap">
              <button type="button" class="cancel" onclick="window.location.href='/users'">Back</button>
              <button type="button" class="alt" id="delete-btn" data-user-id="<?php echo $id ?>" data-csrf-token="<?php echo $sessionToken ?>">Delete</button>
              <input type="submit" name="submit">
            </div>
            <?php if (!empty($formError['fatal'])): ?>
              <div class="error text-right"><?php echo $formError['fatal'] ?></div>
            <?php endif; ?>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </body>
</html>
