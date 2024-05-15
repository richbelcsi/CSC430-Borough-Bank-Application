<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Banking Application</title>
    <style>
        body {
            background-image: url("images/banking_img_2.jpg");
            margin: 0;
        }

        .topnav {
            overflow: hidden;
            background-color: #1844aa;
            height: 80px;
            margin-top: 0px;
        }

        .topnav a {
            float: Right;
            color: rgb(211, 217, 233);
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
            margin-left: -2px;
            color: white;
            font-size: 50px;
            font-variant: small-caps;
            margin-top: -140px;
            padding-left: 24px;
        }

        table {
            width: 85%;
            margin: auto;
            border-collapse: collapse;
            margin-top: 25px;
            background-color: aliceblue;
        }

        th,
        td {
            border: 3px solid black;
            padding: 20px;
            text-align: center;
            color: black;
            font-family: Tahoma, sans-serif;
        }

        th {
            background-color: steelblue;
            color: ghostwhite;
            text-transform: uppercase;
        }

        .button {
            background-color: steelblue;
            border: none;
            color: white;
            text-align: center;
            font-size: 15px;
            font-family: 'Copperplate';
            padding: 10px;
            width: 100%;
            cursor: pointer;
        }

        .button:hover {
            background-color: lightskyblue;
        }
    </style>
</head>
<body>
    <div class="topnav">
        <div class="topnav-right">
            <a href="index.php">Home</a>
            <a class="active" href="view.php">View Customers</a>
            <a href="transaction.php">View Transactions</a>
        </div>
    </div>

    <div class="header">
        <h5 style="padding-left: 0.50rem;margin-top: 75px;font-family: Garamond, sans-serif;">Borough Bank</h5>
    </div>

    <table>
        <tr>
            <th>Name</th>
            <th>Account Number</th>
            <th>Balance</th>
        </tr>

        <?php
        require_once('config.php');

        $stmt = $db->query("SELECT Name, Account_Number FROM customer");

        if ($stmt) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row["Name"] . "</td>";
                echo "<td>" . $row["Account_Number"] . "</td>";
                echo "<td><form method='post' action='transfer.php'>";
                echo "<input type='hidden' name='receiver' value='" . $row['Name'] . "'>";
                echo "<button class='button' type='submit' name='transfer'>Transfer</button>";
                echo "</form></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No results</td></tr>";
        }
        ?>

    </table>
</body>
</html>
