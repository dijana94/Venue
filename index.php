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
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		$email = htmlspecialchars($email);
		
		$pass = trim($_POST['pass']);
		$pass = strip_tags($pass);
		$pass = htmlspecialchars($pass);

		if( intval($email)!= 0 ){
			$branch=1;
		}
		else{
			$branch=0;
		}
	
		if(empty($email)){
			$error = true;
			$emailError = "Please enter your email address.";
		} else if ( (!filter_var($email,FILTER_VALIDATE_EMAIL)) && ($branch==0) ) {
			$error = true;
			$emailError = "Please enter valid email address.";
		}
		
		if(empty($pass)){
			$error = true;
			$passError = "Please enter your password.";
		}
		
		// if there's no error, continue to login
		if (!$error) {
			
			$sql="SELECT cid, f_name, password FROM `customer` WHERE email='$email'";

			$result = $conn->query($sql);
			$row=$result->fetch_assoc();
			$count = $result->num_rows; // if uname/pass correct it returns must be 1 row
			
			if( $count == 1 && $row['password']==$pass ) {
				$_SESSION['user'] = $row['cid'];
				$_SESSION['username'] = $row['f_name'];
				header("Location: home.php");
				exit();
			} 
			else {
				//code for manager authentication
				$sql2="SELECT sid, f_name, branchID FROM `staffemployed` WHERE sid='$email' AND manager = 1";

				$result2 = $conn->query($sql2);
				$row2=$result2->fetch_assoc();
				$count2 = $result2->num_rows; // if uname/pass correct it returns must be 1 row
			
				if( $count2 == 1 && $row2['branchID']==$pass ) {
					$_SESSION['user'] = $row2['sid'];
					$_SESSION['username'] = $row2['f_name'];
					header("Location: manager_home.php");
					exit();
				}
				else {
					$errMSG = "Incorrect Credentials, Try again...<br>";
				}				

			}
				
		}
		
	}

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="cover.css">
</head>
<body  background="bar.jpg">
<br>
<br>
<br>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">

        <h1 style = "font-size: 48px;">Log In to Venue</h1>
        
		<?php echo $errMSG; ?>

        <input type="text" name="email" placeholder="Your Email" maxlength="40" />
        <?php echo $emailError; ?>
        <br>

       <input type="password" name="pass" placeholder="Your Password" maxlength="15" />
       <?php echo $passError; ?>
		<br><br>

       <button type="submit" name="login" class="button" style="vertical-align:middle"><span>Sign In </span></button>
	   
       <br><br>

       <p>If you don't have an account set up, please sign up below.<br><br><a href="register.php">Sign Up</a></p>
          
    </form>

</body>
</html>

<?php ob_end_flush(); ?>