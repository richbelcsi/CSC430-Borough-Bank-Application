<?php
session_start();
require 'config.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM customer WHERE Email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user_id'] = $user['Account_Number'];
        $_SESSION['email'] = $user['Email'];
        header('Location: index.php');
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/c4219223ca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="images/logo.png" class="brand_logo" alt="Borough Bank logo">
                    </div>
                </div>
                <div class="d-flex justify-content-center form_container">
                    <form id="loginForm">
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="username" id="username" class="form-control input_user" required>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="password" id="password" class="form-control input_pass" required>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="rememberme" class="custom-control-input" id="customControlInline">
                                <label class="custom-control-label" for="customControlInline">Remember me</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="d-flex justify-content-center mt-3 login_container">
                    <button type="button" name="button" id="login" class="btn login_btn">Login</button>
                </div>
                <div class="mt-4">
                    <div class="d-flex justify-content-center links">
                        <span>Don't have an account?</span>
                        <span>&nbsp;&nbsp;</span>
                        <a href="registration.php" class="ml-2">Sign Up</a>
                    </div>
                    <div class="d-flex justify-content-center">
                        <a href="#">Forgot your password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script>
        $(function(){
            $('#login').click(function(e){
                e.preventDefault();
                var valid = $('#loginForm')[0].checkValidity();
                if(valid){
                    var username = $('#username').val();
                    var password = $('#password').val();
                    $.ajax({
                        type: 'POST',
                        url: 'jslogin.php',
                        data: {username: username, password: password},
                        success: function(data){
                            alert(data);
                        },
                        error: function(data){
                            alert('There were errors while doing the operation.');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
