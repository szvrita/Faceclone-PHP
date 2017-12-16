<?php

session_start();
include 'reso/frames/connection.php';
// Database Connection

if (isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_conf'])) {
	if ($_POST['password_conf'] != $_POST['password']) {
		echo 'Passwords do not match.';
	} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			echo 'Email address is not valid.';
	} else {
		$name = explode(' ', $_POST['fullname']);
		$data = array(
  	// Escape variables for security - mysqli_real_escape_string for the sake of sanitising the input
			'firstname' => mysqli_real_escape_string($con, $name[0]),
			'lastname' => mysqli_real_escape_string($con, $name[1]),
			'email_address' => mysqli_real_escape_string($con, $_POST['email']),
			'password' => mysqli_real_escape_string($con, $_POST['password'])
		);
    // Encrypting the password and assigning it a salt (the email address in that case)
		$data['password'] = sha1($data['email_address'].$data['password']);

		// var_dump($data);

    // Query the DB and store the results in a var
    $q = 'INSERT INTO users (
			firstname,
			lastname,
			email_address,
			password
		) VALUES (
			"'.$data['firstname'].'",
			"'.$data['lastname'].'",
			"'.$data['email_address'].'",
			"'.$data['password'].'"
		)';

    $query = mysqli_query($con, $q) or die(mysqli_error($con));
    // $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
    // var_dump($results);
		header('location: login.php');

	}
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link rel="stylesheet" href='reso/static/css/secure.css'/>
</head>
<body>
	<nav class="navbar navbar-dark bg-primary">
		<p class="navbar-brand">FaceClone</p>
		<p class="navbar-brand">Registration</p>

		<?php
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
				$actUser1 = $_SESSION['user']['firstname'];
				$actUser2 = $_SESSION['user']['lastname'];
				echo "<h6>$actUser1 $actUser2</h6>";
				?>
					<div id="logout">
				<?php
				if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
					echo '<a href="logout.php">Logout</a><br/>';
				} else {
					echo '<h3>Go away</h3></div>';
				}
			}else{
				echo "<div id='loggedout'>
							<a href='login.php' class='navbar-brand' >Login</a>
							</div>";

			}
			?>

	</nav>
	<div id="register">
	<form action='registration.php' method='POST'>
		<input type='text' name='fullname' placeholder='Full name'/>
		<input type='text' name='email' placeholder='Email address'/>
		<input type='password' name='password' placeholder='Password'/>
		<input type='password' name='password_conf' placeholder='Confirm password'/>
		<input type='submit' value='register'/>
	</form>
</div>
</body>
</html>
