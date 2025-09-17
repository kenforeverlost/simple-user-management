<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');

  function createUser($form){
    global $conn;
    $returnError = "";

    $sql = "INSERT INTO users (first_name, last_name, email, phone) VALUES (?,?,?,?)";
    $query = $conn->prepare($sql);
    $query->execute([
      $form['first_name'],
      $form['last_name'],
      $form['email'],
      $form['phone'],
    ]);

    if ($query->errorCode() != '00000'){
      $returnError = 'There was an error while saving the user.';
    }

    return $returnError;
  }

  function selectUserByEmail($email){
    global $conn;
    $returnError = "";
    $returnData = [];

    $sql = "SELECT * FROM users WHERE email = ?";
    $query = $conn->prepare($sql);
    $query->execute([$email]);
    $returnData = $query->fetch();

    if ($query->errorCode() != '00000'){
      $returnError = 'There was an error while fetching the user.';
    }

    return [
      'error' => $returnError,
      'data' => $returnData,
    ];
  }

  function selectUserById($id){
    global $conn;
    $returnError = "";
    $returnData = [];

    $sql = "SELECT * FROM users WHERE id = ?";
    $query = $conn->prepare($sql);
    $query->execute([$id]);
    $returnData = $query->fetch();

    if ($query->errorCode() != '00000'){
      $returnError = 'There was an error while fetching the user.';
    } else if (empty($returnData)) {
      $returnError = 'User not found.';
    }

    return [
      'error' => $returnError,
      'data' => $returnData,
    ];
  }

  function updateUser($form){
    global $conn;
    $returnError = "";

    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE id = ?";
    $query = $conn->prepare($sql);
    $query->execute([
      $form['first_name'],
      $form['last_name'],
      $form['email'],
      $form['phone'],
      $form['id'],
    ]);

    if ($query->errorCode() != '00000'){
      $returnError = 'There was an error while updating the user.';
    }

    return $returnError;
  }
?>