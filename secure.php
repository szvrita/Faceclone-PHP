<?php
session_start();
include 'reso/frames/connection.php';
// Database Connection

if (isset($_POST['post_text']) && isset($_POST['img_url'])){
		$data = array(
  	// Escape variables for security - mysqli_real_escape_string for the sake of sanitising the input
      'userId' => $_SESSION['user']['id'],
      'post_text' => mysqli_real_escape_string($con, $_POST['post_text']),
			'img_url' => mysqli_real_escape_string($con, $_POST['img_url'])
		);
    // Query the DB and store the results in a var
    $q = 'INSERT INTO posts (
      userId,
      post_text,
			img_url
		) VALUES (
			"'.$data['userId'].'",
      "'.$data['post_text'].'",
			"'.$data['img_url'].'"
		)';
    $query = mysqli_query($con, $q) or die(mysqli_error($con));
	}

$postQuery = "SELECT p.*, u.firstname, u.lastname, u.email_address FROM posts AS p INNER JOIN users AS u ON u.id = p.userId ORDER BY p.timestamp DESC";
$result = mysqli_query($con, $postQuery);
if (mysqli_num_rows($result) > 0) {
  $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
  echo "No results";
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href='reso/static/css/secure.css'/>
    <title></title>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-primary">
      <p class="navbar-brand">FaceClone</p>

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
            echo '<h3>Go away</h3>';
          }

        ?>
      </div>

    </nav>
    <div class="container">
      <div class="row">
          <div class="col-md-3 col-sm-9">
            <form id="form-style" action="secure.php" method="POST" >
              <input id='post-text' type="text" name="post_text" placeholder="What's on your mind?"/>
              <input type="text" name="img_url" placeholder="Wanna share a pic?" />
              <input type="submit" value="Post"/>
            </form>
          </div>
          <div class="col-md-9 col-sm-9">
<?php
foreach ($results as $res) {
  echo '<div class="card" style="width: 90%;">
        <h6>'.$res['firstname']." ".$res['lastname'].'</h6>
        <p class="card-subtitle mb-2 text-muted">'.$res['timestamp'].'</p>
        <p class="card-text">'.$res['post_text'].'</p>
        <img class="card-img-top" src="'.$res['img_url'].'" alt="Card image cap">
        <div class="card-body">
        </div>
        </div>';
      }
		}else {
				echo "<div id='loggedout'>
							<a href='login.php' class='navbar-brand' >Login</a>
							<a href='registration.php' id='' class='navbar-brand' >Register</a>
							</div>";
			}

?>
          </div>
      </div>
    </div>
  </body>
</html>
