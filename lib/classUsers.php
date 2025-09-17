<?php
  session_start();
  require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');

  class Users {
    function getAllUsers(){
      global $conn;
      $returnError = "";
      $returnData = [];

      try {
        $sql = "SELECT * FROM users";
        $query = $conn->prepare($sql);
        $query->execute();
        $returnData = $query->fetchAll();
      } catch (Exception $e) {
        error_log($e->getMessage());
        $returnError = 'User data unavailable at this time.';
      }
        
      return [
        'error' => $returnError,
        'data' => $returnData,
      ];
    }

    function deleteUserById($id, $csrf_token){
      global $conn;
      $returnError = "";
      $id = !empty($id) || $id === 0 ? $id : null;



      if ($id === null) {
        $returnError = 'Invalid user ID.';
      } else if (empty($csrf_token) || !hash_equals($csrf_token, $_SESSION['csrf_token'])) {
        $returnError = 'CSRF token validation failed.';
      } else {
        try {
          $sql = "DELETE FROM users WHERE id = ?";
          $query = $conn->prepare($sql);
          $query->execute([$id]);
          $_SESSION['flash_success'] = 'User deleted successfully.';
        } catch (Exception $e) {
          error_log($e->getMessage());
          $returnError = 'There was an error while deleting the user.';
        }
      }
      
      return [
        'error' => $returnError
      ];
    }
  }

  if (!empty($_POST['class']) && !empty($_POST['function'])) {
    try {
      $class = $_POST['class'];
      $function = $_POST['function'];

      $object = new $class();

      if (!empty($_POST['arguments'])){
        $arguments = json_decode($_POST['arguments'], true);
        $reflectionMethod = new ReflectionMethod($class, $function);
        echo json_encode($reflectionMethod->invokeArgs($object, $arguments));
      } else {
        echo json_encode($object->$function());
      }
    } catch (Exception $e) {
      error_log($e->getMessage());
      echo json_encode(['error' => 'Unexpected error. Please try again later.']);
    }
  } else {
    echo json_encode([
      'error' => 'Invalid call'
    ]);
  }
?>
