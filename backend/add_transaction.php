<?php
var_dump($_POST);
session_start();
if (!isset($_SESSION['user_id'])) {
    $response = array("status" => "error", "message" => "User not logged in");
    echo json_encode($response);
    die();
}

// Read the raw input
$inputJSON = file_get_contents('php://input');

// Decode the JSON data into a PHP associative array
$inputData = json_decode($inputJSON, true);

// Get transaction data from the decoded JSON
$name = $inputData['name'];
$amount = $inputData['amount'];
$isCredit = $inputData['isCredit'];

$user_id = $_SESSION['user_id'];
$servername = "localhost";
$dbusername = "lakshya";
$dbpassword = "lakshya";
$dbname = "float";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get transaction data from the POST request
//

// Create a new transaction in the transactions table
$stmt = $conn->prepare("INSERT INTO transactions (user_id, name, amount, isCredit) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isdi", $user_id, $name, $amount, $isCredit);
$result = $stmt->execute();
$stmt->close();

if (!$result) {
    $response = array("status" => "error", "message" => "Failed to add transaction to database");
    echo json_encode($response);
    die();
}

// Update the user's balance in the users table based on the transaction type
if ($isCredit) {
    // Credit transaction, add the amount to the user's balance
    $stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE user_id = ?");
    $stmt->bind_param("di", $amount, $user_id);
} else {
    // Debit transaction, subtract the amount from the user's balance
    $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE user_id = ?");
    $stmt->bind_param("di", $amount, $user_id);
}

$result = $stmt->execute();
$stmt->close();

if (!$result) {
    $response = array("status" => "error", "message" => "Failed to update user balance");
    echo json_encode($response);
    die();
}

$conn->close();

// Send a JSON response indicating success
$response = array("status" => "success");
echo json_encode($response);

exit();
?>
