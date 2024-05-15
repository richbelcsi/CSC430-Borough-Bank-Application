<?php
session_start();
require 'config.php';

if (isset($_POST['transfer'])) {
    $to_acc_no = $_POST['to_acc_no'];
    $amount = $_POST['amount'];
    $from_acc_no = $_SESSION['user_id'];

    try {
        $db->beginTransaction();

        // Deduct from sender
        $query = "UPDATE customer SET Balance = Balance - :amount WHERE Account_Number = :account_number";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':account_number', $from_acc_no);
        $stmt->execute();

        // Add to receiver
        $query = "UPDATE customer SET Balance = Balance + :amount WHERE Account_Number = :account_number";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':account_number', $to_acc_no);
        $stmt->execute();

        // Record the transfer
        $query = "INSERT INTO transfer (a_name, a_acc_no, b_name, b_acc_no, amount) 
                  SELECT sender.Name, sender.Account_Number, receiver.Name, receiver.Account_Number, :amount 
                  FROM customer sender, customer receiver 
                  WHERE sender.Account_Number = :from_acc_no AND receiver.Account_Number = :to_acc_no";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':from_acc_no', $from_acc_no);
        $stmt->bindParam(':to_acc_no', $to_acc_no);
        $stmt->execute();

        $db->commit();
        $success = "Transfer successful!";
    } catch (PDOException $e) {
        $db->rollBack();
        $error = "Transfer failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Banking Application</title>
<style>
  body{
    background-image: url("images/banking_img_3.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    margin: 0;
    padding: 0;
  }

  .topnav {
    overflow: hidden;
    background-color: #1844aa;
    height: 80px;
    margin-top: 0;
  }

  .topnav a {
    float: Right;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 20px;
  }

  .topnav a:hover {
    background-color: #2b67f8;
    color: white;
  }

  .header {
    margin-left: 14px;
    color: white;
    font-size: 50px;
    font-variant: small-caps;
    margin-top: -140px;
    padding-left: 16px;
  }

  .tablediv {
    width: 85%;
    margin: 0 auto;
    overflow-x: auto;
    margin-top: 20px;
  }

  table {
    width: 100%;
    font-family: 'Tahoma', sans-serif;
    font-weight: bold;
    background: #f9fafcc0;
    border: 5px solid black;
    border-collapse: collapse;
  }

  th, td {
    padding: 15px;
    text-align: center;
    vertical-align: middle;
    font-weight: bold;
    border: 2px solid black;
    font-size: 18px;
    color: black;
    border-collapse: collapse;
  }

  th {
    background-color: #f2f2f2;
    color: rgb(1, 29, 104);
    text-transform: uppercase;
  }
</style>
</head>
<body>
<header>
  <div class="topnav">
    <div class="topnav-right">
      <a href="index.php">Home</a>
      <a href="view.php">View Customers</a>
      <a class="active" href="transaction.php">View Transactions</a>
    </div>
  </div>

  <div class="header">
    <h5 style="padding-right: 1rem;margin-top: 75px;font-family: Garamond, sans-serif;">Borough Bank</h5>
  </div>
</header>

<div class="tablediv">
  <table>
    <tr>
      <th>Sender Name</th>
      <th>Sender Account Number</th>
      <th>Recipient Name</th>
      <th>Recipient Account Number</th>
      <th>Amount</th>
      <th>Date & Time</th>
    </tr>

    <?php
    $sql = "SELECT * FROM transfer";
    $stmt = $db->query($sql);
    if ($stmt->rowCount() > 0) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row["a_name"]. "</td>";
        echo "<td>" . $row["a_acc_no"] . "</td>";
        echo "<td>" . $row["b_name"]. "</td>";
        echo "<td>" . $row["b_acc_no"] . "</td>";
        echo "<td>" . $row["amount"] . "</td>";
        echo "<td>" . $row["date_time"] . "</td>";
        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='6'>No results</td></tr>";
    }
    ?>

  </table>
</div>
</body>
</html>
