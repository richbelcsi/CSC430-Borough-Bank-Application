<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    print_r($_POST);

    $a_acc_no = $_POST['a_acc_no'] ?? null;
    $b_acc_no = $_POST['b_acc_no'] ?? null;
    $amount = $_POST['amount'] ?? null;

    if (!empty($a_acc_no) && !empty($b_acc_no) && !empty($amount) && $amount > 0) {
        try {
            $db->beginTransaction();

            $stmt = $db->prepare("SELECT Name, Balance FROM customer WHERE Account_Number = :a_acc_no");
            $stmt->bindParam(':a_acc_no', $a_acc_no);
            $stmt->execute();
            $sender = $stmt->fetch(PDO::FETCH_ASSOC);
            $a_name = $sender['Name'];

            $stmt = $db->prepare("SELECT Name FROM customer WHERE Account_Number = :b_acc_no");
            $stmt->bindParam(':b_acc_no', $b_acc_no);
            $stmt->execute();
            $recipient = $stmt->fetch(PDO::FETCH_ASSOC);
            $b_name = $recipient['Name'];

            if ($sender && $sender['Balance'] >= $amount) {
                
                $stmt = $db->prepare("UPDATE customer SET Balance = Balance - :amount WHERE Account_Number = :a_acc_no");
                $stmt->bindParam(':amount', $amount);
                $stmt->bindParam(':a_acc_no', $a_acc_no);
                $stmt->execute();

                
                $stmt = $db->prepare("UPDATE customer SET Balance = Balance + :amount WHERE Account_Number = :b_acc_no");
                $stmt->bindParam(':amount', $amount);
                $stmt->bindParam(':b_acc_no', $b_acc_no);
                $stmt->execute();

                
                $stmt = $db->prepare("INSERT INTO transfer (a_acc_no, b_acc_no, amount, date_time, a_name, b_name) VALUES (:a_acc_no, :b_acc_no, :amount, NOW(), :a_name, :b_name)");
                $stmt->bindParam(':a_acc_no', $a_acc_no);
                $stmt->bindParam(':b_acc_no', $b_acc_no);
                $stmt->bindParam(':amount', $amount);
                $stmt->bindParam(':a_name', $a_name);
                $stmt->bindParam(':b_name', $b_name);
                $stmt->execute();

                $db->commit();
                echo "Transfer successful";
            } else {
                echo "Insufficient balance";
            }
        } catch (Exception $e) {
            $db->rollBack();
            echo "Failed: " . $e->getMessage();
        }
    } else {
        echo "Invalid input";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borough Bank</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <style>
        body {
            background-color: #2b67f8;
            color: white;
        }
        .user-details {
            font-size: 20px;
            font-weight: bold;
            width: 50%;
            margin: auto;
            text-align: left;
            margin-top: 50px;
        }
        .center {
            margin-left: auto;
            margin-right: auto;
        }
        .success-message {
            text-align: center;
            font-size: 24px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="user-details center">
        <p>Receiver: <?php echo $b_acc_no; ?></p>
    </div>
    <div class="center">
        <input type="button" onclick="location.href='transaction.php';" value="View Transactions" />
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transfer</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Transfer</h1>
        <form method="POST">
            <label for="a_acc_no">Sender Account Number:</label>
            <input type="text" id="a_acc_no" name="a_acc_no" required>
            <label for="b_acc_no">Recipient Account Number:</label>
            <input type="text" id="b_acc_no" name="b_acc_no" required>
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" required>
            <button type="submit">Transfer</button>
        </form>
    </div>
</body>
</html>
