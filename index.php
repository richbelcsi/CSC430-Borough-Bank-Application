<?php
	
	session_start();

	if(!isset($_SESSION['Borough Bank'])){
		header("Location: login.php");
		exit();
	}

	if(isset($_GET['logout'])){
		session_destroy();
		unset($_SESSION);
		header("Location: login.php");
		exit();
	}
	
?>

<a href="index.php?logout=true">Logout</a>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking Application</title>
    <style>
        body {
            background-image: url("images/banking_img_3.jpg");
            background-size: cover;
            margin: 0;
            padding: 0;
            font-family: Copperplate, serif;
            font-style: italic;
            text-align: center;
            font-size: 25px;
        }

        .topnav {
            background-color: #1844aa;
            overflow: hidden;
            height: 80px;
        }

        .topnav a {
            float: left;
            color: rgb(211, 217, 233);
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 25px;
        }

        .topnav a:hover {
            background-color: #2b67f8;
            color: white;
        }

        .header {
            margin: 0;
            padding: 14px;
            color: #f9fafc;
            font-size: 50px;
            font-variant: small-caps;
        }

        .content {
            margin-top: 50px;
            text-align: center;
        }

        .quote {
            background-color: rgba(129, 120, 120, 0.322);
            width: 800px;
            margin: auto;
            padding: 20px;
            color: #FFFFFF;
            font-family: Lucida Handwriting, sans-serif;
            font-weight: bold;
        }

        .button {
            background-color: #2b67f8;
            border-radius: 29px;
            color: white;
            padding: 10px 25px;
            text-decoration: none;
            font-size: 20px;
            display: inline-block;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #1844aa;
        }

        .button:active {
            background-color: #1844aa;
            box-shadow: 0 5px #666;
            transform: translateY(4px);
        }
    </style>
</head>

<body>
    <div class="topnav">
        <a href="index.php">Home</a>
        <a href="customer.php">Customers</a>
        <a href="transaction.php">Transactions</a>
    </div>

    <div class="header">
        <h5>Borough Bank</h5>
    </div>

    <div class="content">
        <div class="quote">
            "Banking is not only about credit and savings, but also about creating a better life for people and building stronger communities."
        </div>
        <a href="#" class="button">Explore</a>
    </div>
</body>
</html>
