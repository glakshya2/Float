<?php
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$balance = $_POST['balance'];
$profilePhoto = $_FILES['profile_photo'];

$servername = "localhost";
$username = "lakshya";
$dbpassword = "lakshya";
$dbname = "float";

$conn = new mysqli($servername, $username, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$existingUserQuery = "SELECT COUNT(*) as count FROM users WHERE email = ?";
$stmt = $conn->prepare($existingUserQuery);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$userExists = $row['count'] > 0;

if ($userExists) {
    die("User already exists in the database!");
}

$insertQuery = "INSERT INTO users (name, email, password, balance) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($insertQuery);

$stmt->bind_param("ssss", $name, $email, $password, $balance);
$stmt->execute();

if ($stmt->error) {
    die("Error during execution: " . $stmt->error);
}

$userID = $stmt->insert_id; // Retrieve the auto-generated user ID


if (!empty($profilePhoto)) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . $userID . "." . pathinfo(basename($profilePhoto['name']), PATHINFO_EXTENSION);
    move_uploaded_file($profilePhoto['tmp_name'], $targetFile);

    $updateQuery = "UPDATE users SET profile_photo = ? WHERE user_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $targetFile, $userID);
    $stmt->execute();

    if ($stmt->error) {
        die("Error during execution: " . $stmt->error);
    }

}

$stmt->close();
$conn->close();

session_start();
$_SESSION['user_id'] = $name;
$_SESSION['password'] = $password;

$response = array('status' => 'success');
echo json_encode($response);
