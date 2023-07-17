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
  $transactionSql = "SELECT * FROM transactions WHERE user_id = $user_id";
  $transactionResult = $conn->query($transactionSql);

  $response = array();

  if ($transactionResult->num_rows > 0) {
    while ($transactionRow = $transactionResult->fetch_assoc()) {
        $amount = $transactionRow['amount'];
        if ($transactionRow['isCredit'] == 1) {
            $amount = '+'.$amount;
        } else {
            $amount = '-'.$amount;
        }
      $transaction = array(
        'transaction_id' => $transactionRow['transaction_id'],
        'name' => $transactionRow['name'],
        'date' => $transactionRow['date'],
        'amount' => $amount,
      );

      $response['transactions'][] = $transaction;
    }
  }

  $conn->close();

  header('Content-Type: application/json');
  echo json_encode($response);
}
?>
