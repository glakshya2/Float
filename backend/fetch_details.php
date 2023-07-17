<?php
session_start();

if (isset($_SESSION['user_id'])) {
  $servername = "localhost";
  $username = "lakshya";
  $password = "lakshya";
  $database = "float";

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $user_id = $_SESSION['user_id'];
  $sql = "SELECT * FROM Users WHERE user_id = $user_id";
  $result = $conn->query($sql);

  $response = array();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userDetails['user_id'] = $row['user_id'];
    $userDetails['name'] = $row['name'];
    $userDetails['email'] = $row['email'];
    $userDetails['balance'] = $row['balance'];
    $userDetails['profile_photo'] = $row['profile_photo'];
    $response['user'] = $userDetails;
  }

  $conn->close();

  header('Content-Type: application/json');
  echo json_encode($response);
}
?>
