<?php

$email = $_POST['email'];
$password = $_POST['password'];

$servername = "localhost";
$dbusername = "lakshya";
$dbpassword = "lakshya";
$dbname = "float";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT user_id, name, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $response = array("status" => "User not found");
    echo json_encode($response);
    die();
}

$user = $result->fetch_assoc();

if ($password != $user['password']) {
    $response = array("status" => "Incorrect password");
    echo json_encode($response);
    die();
}

$stmt->close();
$conn->close();

session_start();
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['password'] = $user['password'];

// Send a JSON response indicating success
$response = array("status" => "success");
echo json_encode($response);

exit();
?>
