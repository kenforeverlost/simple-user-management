<?php
  session_start();
  require_once($_SERVER['DOCUMENT_ROOT'].'/lib/actions.php');
  require_once($_SERVER['DOCUMENT_ROOT'].'/lib/helpers.php');

  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }

  $sessionToken = !empty($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';
  $form = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'phone' => '',
    'csrf_token' => '',
  ];
  $formError = [];

  if (!empty($_POST['submit'])){
    $form['first_name'] = trim($_POST['first_name']);
    $form['last_name'] = trim($_POST['last_name']);
    $form['email'] = trim($_POST['email']);
    $form['phone'] = trim($_POST['phone']);
    $form['csrf_token'] = $_POST['csrf_token'];

    $formError = validateUserForm($form,'create');

    if (empty($formError)) {
      $saveResponse = createUser($form);

      if (empty($saveResponse)) {
        $_SESSION['flash_success'] = 'User created successfully.';
        header('Location: /index.php');
      } else {
        $formError['fatal'] = 'There was an error while saving the user.';
      }
    
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Create</title>
    <meta name="description" content="This page allows the user to add a user to the database.">
    <link href="/css/styles.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <div>
        <h1>Add User</h1>
      </div>
      <div>
        <form action="" method="post" class="card" novalidate>
          <input type="hidden" name="csrf_token" value="<?php echo $sessionToken ?>">
          <div>
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $form['first_name'] ?>">
            <?php echo displayFormError('first_name',$formError) ?>
          </div>
          <div>
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $form['last_name'] ?>">
            <?php echo displayFormError('last_name',$formError) ?>
          </div>
          <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $form['email'] ?>">
            <?php echo displayFormError('email',$formError) ?>
          </div>
          <div>
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $form['phone'] ?>">
            <?php echo displayFormError('phone',$formError) ?>
          </div>
          <div class="form-btn-wrap">
              <button type="button" class="cancel" onclick="window.location.href='/users'">Back</button>
            <?php if (!empty($formError['fatal'])): ?>
              <span class="error mr-1"><?php echo $formError['fatal'] ?></span>
            <?php endif; ?>
            <input type="submit" name="submit">
          </div>
        </form>
      </div>
    </div>
  </body>
</html>