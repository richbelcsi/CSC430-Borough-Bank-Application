<?php
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
    $ssn = $_POST['ssn'];
    $pin = $_POST['pin'];

    $name = trim($name);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $phonenumber = trim($phonenumber);

    if ($email === false) {
        echo "Invalid email format";
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO customer (Name, Email, Phone_Number, Address, Password, DOB, SSN, PIN) VALUES(?,?,?,?,?,?,?,?)";
    $stmtinsert = $db->prepare($sql);
    $result = $stmtinsert->execute([$name, $email, $phonenumber, $address, $passwordHash, $dob, $ssn, $pin]);

    if ($result) {
        echo 'Successfully saved.';
    } else {
        echo 'There were errors while saving the data.';
    }
} else {
    echo 'No data received';
}

function makeTransaction($user_id, $amount, $type) {
    global $db;
    $query = "INSERT INTO transactions (user_id, amount, type) VALUES (:user_id, :amount, :type)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':type', $type);
    return $stmt->execute();
}

function transferFunds($from_acc_no, $to_acc_no, $amount) {
    global $db;
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
        return true;
    } catch (PDOException $e) {
        $db->rollBack();
        return false;
    }
}
?>
