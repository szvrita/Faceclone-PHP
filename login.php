<?php

session_start();
include 'reso/frames/connection.php';

if(isset($_POST['email_address']) && isset($_POST['password'])){

  // Connect to the Database


  // Sanitise Email Address
  $email = mysqli_real_escape_string($con, $_POST['email_address']);

  // Query to search for user by email
  $query = 'SELECT * FROM users WHERE email_address = "'.$email.'" LIMIT 1';
  // Perform query and save in $user variable
  $user = mysqli_query($con, $query) or die(mysqli_connect_errorno());

  // Check if there are any results and do something with it
  if (mysqli_num_rows($user) > 0){
      // Parse results into a Hashmap
      $user = mysqli_fetch_all($user, MYSQLI_ASSOC);
      // Encrypt password from input
      $input_password = mysqli_real_escape_string($con, $_POST['password']);
      $input_password = sha1($email.$input_password);
      // Check passwords match
      if ($input_password === $user[0]['password']) {
        // Set logged in token
        $_SESSION['logged_in'] = true;
        // Save user details in session
        $_SESSION['user'] = $user[0];
        // Redirect to secure
        header('location: secure.php');
      } else {
        echo "Bitch, check your password";
      }

  } else {
      echo 'No account found :o';
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
		<p class="navbar-brand">Login</p>

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
							<a href='registration.php' class='navbar-brand' >Register</a>
							</div>";
			}
			?>

	</nav>

<div id="login">
<form action='login.php' method='POST'>
	<input type='text' name='email_address' placeholder='Enter your email'/>
	<input type='password' name='password' placeholder='Enter your password'/>
	<input type='submit' value='Log In'/>
</form>
</div>

</body>
</html>
