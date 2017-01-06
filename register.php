<?php

// code used from http://www.codingcage.com/2015/01/user-registration-and-login-script-using-php-mysql.html as reference
	ob_start();
	session_start();
 		$servername = "localhost";
        $username = "root";
        $password = NULL;
        $databasename = 'Venue';

        //connect
        $conn = new mysqli($servername, $username, $password, $databasename);

        //check connecting
        if($conn->connect_error) {
        die("Connection falied: " . $conn->connect_error);
        } 

	$firstnameError ='';
	$lastnameError = '';
	$emailError='';
	$passError='';
	$errMSG='';

	// it will never let you open index(login) page if session is set
	if ( isset($_SESSION['user'])!="" ) {
		header("Location: home.php");
		exit;
	}
	
	$error = false;
	
	if( isset($_POST['login']) ) {	
		
		// prevent sql injections/ clear user invalid inputs

		$firstname = trim($_POST['firstname']);
		$firstname = strip_tags($firstname);
		$firstname = htmlspecialchars($firstname);


		$lastname = trim($_POST['lastname']);
		$lastname = strip_tags($lastname);
		$lastname = htmlspecialchars($lastname);


		$email = trim($_POST['email']);
		$email = strip_tags($email);
		$email = htmlspecialchars($email);
		
		$pass = trim($_POST['pass']);
		$pass = strip_tags($pass);
		$pass = htmlspecialchars($pass);
		// prevent sql injections / clear user invalid inputs
		
		if(empty($email)){
			$error = true;
			$emailError = "Please enter your email address.";
		} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$error = true;
			$emailError = "Please enter valid email address.";
		}

		if(empty($firstname)){
			$error = true;
			$firstnameError = "Please enter your first name.";
		}

	    if(empty($lastname)){
			$error = true;
			$lastnameError = "Please enter your last name.";
		}
		
		if(empty($pass)){
			$error = true;
			$passError = "Please enter your password.";
		}
		
		// if there's no error, continue to login
		if (!$error) {
			
		
			$query = "INSERT INTO `customer` VALUES('', '$firstname', '$lastname', NULL, '$email','$pass')"; //cid is auto-incremented
			$conn->query($query);

			$_SESSION['user'] = $cid;
			$_SESSION['username'] = $firstname;
			header("Location: home.php");
			exit;

		}
		
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cerulean/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
<body>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
    	
            	<h2 style="margin-left: 20px;" class="">Sign In.</h2>
            
			<?php echo $errMSG; ?>
			<input style="margin-left: 20px;" type="text" name="firstname" placeholder="Your First Name" maxlength="40" />
            <?php echo $firstnameError; ?>
            <br><br>
            <input style="margin-left: 20px;" type="text" name="lastname" placeholder="Your Last Name" maxlength="40" />
            <?php echo $lastnameError; ?>
            <br><br>
            <input style="margin-left: 20px;" type="email" name="email" placeholder="Your Email" maxlength="40" />
            <?php echo $emailError; ?>
            <br><br>
           <input style="margin-left: 20px;" type="password" name="pass" placeholder="Your Password" maxlength="15" />
           <?php echo $passError; ?>
			<br><br>
           <button style="margin-left: 20px;" type="submit" name="login">Sign In</button>
           <br><br>
          
    </form>


</body>
</html>
<?php ob_end_flush(); ?>