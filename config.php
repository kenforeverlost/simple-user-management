<?php
  $host = "127.0.0.1";
  $db   = "userdb";
  $user = "root";
  $pass = "root";

  $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
