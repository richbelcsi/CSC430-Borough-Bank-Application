<?php
require 'config.php';

if (isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $stmt = $db->prepare("SELECT * FROM customer WHERE Account_Number = :customer_id");
    $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Banking Application</title>
    <style>
        body {
            background-image: url("images/banking_img_2.jpg");
            background-size: cover;
            margin: 0;
            padding: 0;
            font-family: 'Copperplate', sans-serif;
        }

        .topnav {
            background-color: #1844aa;
            overflow: hidden;
            height: 80px;
        }

        .topnav a {
            float: left;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .topnav a:hover {
            background-color: #2b67f8;
            color: white;
        }

        .container {
            margin: auto;
            width: 60%;
            background-color: rgba(129, 120, 120, 0.50);
            padding: 20px;
        }

        .user_details {
            font-size: 20px;
            color: black;
            font-weight: bold;
            width: 100%;
            text-align: left;
            padding-left: 1rem;
        }

        .textbox {
            height: 20px;
            background-color: white;
            color: black;
            font-size: 15px;
            font-family: 'Copperplate', sans-serif;
            font-weight: bold;
        }

        button {
            border: 2px solid #2b67f8;
            background-color: white;
            color: #2b67f8;
            padding: 0.35em 1.2em;
            margin: 0.4em auto;
            font-size: 20px;
            font-weight: 300;
            cursor: pointer;
            display: block;
            text-align: center;
            min-width: 150px;
            border-radius: 5px;
            font-family: 'Copperplate', sans-serif;
        }

        button:hover {
            color: peachpuff;
            background-color: #2b67f8;
        }
    </style>
</head>
<body>
    <header>
        <div class="topnav">
            <a href="index.php">Home</a>
            <a class="active" href="view.php">Customers</a>
            <a href="transaction.php">Transactions</a>
        </div>
    </header>

    <div class="container">
        <div class="user_details">
            <?php
            session_start();
            require_once('config.php');

            if (!isset($_SESSION['user'])) {
                header("Location: login.php");
                exit();
            }

            if (isset($_POST['transfer'])) {
                $sender = $_SESSION['user'];
                $receiver = $_POST["receiver"];
                $amount = $_POST["amount"];

                $stmt = $db->prepare("SELECT Balance FROM customer WHERE Name = :sender");
                $stmt->bindParam(':sender', $sender);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row && $row['Balance'] >= $amount && $row['Balance'] - $amount >= 100) {
                    $stmt = $db->prepare("UPDATE customer SET Balance = Balance - :amount WHERE Name = :sender");
                    $stmt->bindParam(':amount', $amount);
                    $stmt->bindParam(':sender', $sender);
                    $stmt->execute();

                    $stmt = $db->prepare("UPDATE customer SET Balance = Balance + :amount WHERE Name = :receiver");
                    $stmt->bindParam(':amount', $amount);
                    $stmt->bindParam(':receiver', $receiver);
                    $stmt->execute();

                    $stmt = $db->prepare("INSERT INTO transfer (a_name, b_name, amount) VALUES (:sender, :receiver, :amount)");
                    $stmt->bindParam(':sender', $sender);
                    $stmt->bindParam(':receiver', $receiver);
                    $stmt->bindParam(':amount', $amount);
                    $stmt->execute();

                    echo "<div class='success-message'>Money sent successfully!</div>";
                } else {
                    echo "<div class='error-message'>Transaction Denied: Insufficient Balance or Minimum Balance Constraint!</div>";
                }
            }
            ?>
            </select>
            <br><br>
            <label for="myinput"><b>From: </b></label>
            <input id="myinput" name="sender" class="textbox" disabled type="text" value='<?php echo "$user"; ?>'></input>
            <br><br>
            <label for="amount"><b>Amount: </b></label>
            <input name="amount" type="number" min="100" class="textbox" required>
            <br><br>
            <button id="transfer" name="transfer">Transfer</button>
        </form>
    </div>
</body>
</html>
