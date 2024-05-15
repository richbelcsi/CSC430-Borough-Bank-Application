<?php
require_once('config.php');

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $phone_number = $_POST['phone_number'];
    $pin = $_POST['pin'];
    $ssn = $_POST['ssn'];

    $query = "INSERT INTO customer (Name, Email, Password, Address, DOB, Phone_Number, PIN, SSN) VALUES (:name, :email, :password, :address, :dob, :phone_number, :pin, :ssn)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':dob', $dob);
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->bindParam(':pin', $pin);
    $stmt->bindParam(':ssn', $ssn);

    if ($stmt->execute()) {
        header('Location: login.php');
        exit();
    } else {
        $error = "Failed to create account!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registration</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>

<div>
	<?php
	
	?>
</div>

<div>
	<form action="registration.php" method="post">
		<div class="container">

			<div class="row">
				<div class="col-sm-3">
					<h1>Registration</h1>
					<p>Please fill in the registration form with correct user data.</p>
					<hr class="mb-3">
					<label for="name"><b>Name</b></label>
					<input class="form-control" id="name" type="text" name="name" required>

					<label for="email"><b>Email</b></label>
					<input class="form-control" id="email" type="email" name="email" required>

					<label for="phonenumber"><b>Phone Number</b></label>
					<input class="form-control" id="phonenumber" type="text" name="phonenumber" required>

					<label for="address"><b>Address</b></label>
					<input class="form-control" id="address" type="text" name="address" required>

					<label for="password"><b>Password</b></label>
					<input class="form-control" id="password" type="password" name="password" required>

					<label for="dob"><b>DOB</b></label>
					<input class="form-control" id="dob" type="text" name="dob" required>

					<label for="ssn"><b>SSN</b></label>
					<input class="form-control" id="ssn" type="text" name="ssn" required>

					<label for="pin"><b>PIN</b></label>
					<input class="form-control" id="pin" type="text" name="pin" required>

					<label for="terms">
        				<input type="checkbox" id="terms" name="terms" required>
        				I agree to the <a href="terms.html" target="_blank">Terms</a> and <a href="privacy.html" target="_blank">Privacy Policy</a>
    				</label>
					<hr class="mb-3">
					<input class="btn btn-primary" type="submit" id="register" name="create" value="Sign Up">
				</div>
			</div>	
		</div>
	</form>
</div>	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
	$(function(){
		$('#register').click(function(e){

			var valid = this.form.checkValidity();

			if(valid){

				var name 		= $('#name').val();
				var email 		= $('#email').val();
				var phonenumber = $('#phonenumber').val();
				var address 	= $('#address').val();
				var password 	= $('#password').val();
				var dob 		= $('#dob').val();
				var ssn 		= $('#ssn').val();
				var pin 		= $('#pin').val();

				e.preventDefault();

				$.ajax({
					type: 'POST',
					url: 'process.php',
					data: {name: name, email: email, phonenumber: phonenumber, address: address, password: password, dob: dob, ssn: ssn, pin: pin},
					success: function(data){
						Swal.fire({
							'title': 'Successful',
							'text': data,
							'type': 'success'
						});
					},
					error: function(data){
						Swal.fire({
							'title': 'Error',
							'text': 'There were errors while saving the data.',
							'type': 'error'
						});
					}
				});

			}

		});
	});	
</script>
</body>
</html>
