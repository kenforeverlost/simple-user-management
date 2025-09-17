<?php
  session_start();

  $flashSuccess = !empty($_SESSION['flash_success']) ? $_SESSION['flash_success'] : '';
  if (!empty($_SESSION['flash_success'])) {
    unset($_SESSION['flash_success']);
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - View</title>
    <meta name="description" content="This page allows the user to view all users from the database.">
    <link href="/css/styles.css" rel="stylesheet">
    <script src="/js/users.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const flashSuccess = '<?php echo $flashSuccess ?>';
        if (flashSuccess) {
          alert(flashSuccess);
        }
      });
    </script>
  </head>
  <body>
    <div class="mb-1">
      <label for="text-search">Search</label>
      <input type="text" id="text-search" name="text-search" value="">
    </div>
    <div>
      <a href="/users/create">
        <button>Add User</button>
      </a>
    </div>
    <div>
      <table>
        <tr>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Created At</th>
          <th>Action</th>
        </tr>
        <tbody id="table-contents">
          <tr>
            <td colspan="6" class="info">Loading...</td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>
