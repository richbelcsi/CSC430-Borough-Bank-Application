<?php
session_start();
require_once('config.php');

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM customer WHERE Email = ?";
$stmtselect = $db->prepare($sql);
$stmtselect->execute([$username]);
$user = $stmtselect->fetch();

if ($user) {
    $hashedPassword = $user['Password'];

    if (password_verify($password, $hashedPassword)) {
    	$_SESSION['Borough Bank'] = $user;
        echo 'Login successful';
    } else {
        echo 'Invalid password';
    }
} else {
    echo 'User not found';
}
?>
