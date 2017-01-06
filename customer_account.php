<?php
session_start();
if ( isset($_SESSION['user'])=="" ) {
    header("Location: index.php");
    exit;
  }
else
{
$cid=$_SESSION['user'];
$user=$_SESSION['username'];

?>
<?php


$servername = "localhost";
$usernameroot = "root";
$password = NULL;
$databasename = 'Venue';

//connect
$conn = new mysqli($servername, $usernameroot, $password, $databasename);

//check connecting
if($conn->connect_error) {
	die("Connection falied: " . $conn->connect_error);
}

$sql = "SELECT name, branchID FROM `venue`";
$result = $conn->query($sql);

$names = array();
$ids = array();
$venue_dropdowns = "";
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$venue_dropdowns.= '<a href = "http://localhost/304_project/venue_page.php?branchID='.$row["branchID"].'"> '.$row["name"].' </a>';
    }
} else {
    echo "0 results";
}

$conn->close();
 ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="customer.css">

   <script>
   		function infoUpdated() {
   			alert("Your account information has been updated!");
   		}

   </script>
</head>

<body background="party.jpg">
<ul>
  <li><a class="active" href="http://localhost/304_project/home.php">Home</a></li>
  <li><a href="http://localhost/304_project/events.php">Events</a></li>
  <li class="dropdown">
    <a href="#" class="dropbtn">Venues</a>
    <div class="dropdown-content">
	  <?php echo $venue_dropdowns; ?>
    </div>
  </li>
  <li class="dropdown">
    <a href="#" class="dropbtn">Account</a>
    <div class="dropdown-content">
	  <a href="customer_reservations.php">My Reservations</a>
	  <a href="customer_tickets.php">My Tickets</a>
	  <a href="customer_account.php">Account Settings</a>
    </div>
  </li>
  <li style="float:right">
  Logged in as <?php echo $user; ?>  <a href="http://localhost/304_project/logout.php">Logout</a>
  </li>
</ul>




<?php

// code used from http://www.codingcage.com/2015/01/user-registration-and-login-script-using-php-mysql.html as reference
	ob_start();
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

	$error = false;
	
	if( isset($_POST['updateInfo']) ) {	
		
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
		
		// if there's no error, continue to update
		if (!$error) {
			
			$query = " UPDATE `customer` SET f_name='$firstname', l_name='$lastname', email='$email', password='$pass' WHERE cid='$cid'";

			$conn->query($query);
		}


//TODO
// maybe later if we really have time left over, we could populate their name and last name and email so they only change what they want to, we won't have their passowrd in there however - STARTED BELOW

		//current customer info
	// 	$getCustomerInfo = "SELECT f_name, l_name, email FROM `customer`";
	// 	$result = $conn->query($getCustomerInfo);
	// 	if ($result->num_rows > 0) {
 //    	// output data of each row
 //    		while($row = $result->fetch_assoc()) {
	// 			$f_name = $row["f_name"];
	// 			$l_name = $row["l_name"];
	// 			$customerEmail = $row["email"];
	// 		}
	// 	}
	// 	else{
	// 		echo "No record found.";
	// 	}

	// }


	$conn->close(); 	
	}
//TODO
// maybe later if we really have time left over, we could populate their name and last name and email so they only change what they want to, we won't have their passowrd in there however

?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    	
            <h1 style="margin-left: 20px;" class=""><?php echo $user ?>'s Account Information.</h1>
			<div style="padding: 20px; background:rgba(20, 20, 20, 0.75);">
            <p> Below you can change your name, email address (your username) and your password.<p>

            <p>For the fields you wish to remain unchanged enter your current info.</p>
			</div>
            <br>
			<?php echo $errMSG; ?>
			
			<input type="text" name="firstname" placeholder="Your first name" maxlength="40" />
            <?php echo $firstnameError; ?>
            <br><br>
            <input type="text" name="lastname" placeholder="Your last name" maxlength="40" />
            <?php echo $lastnameError; ?>
            <br><br>
            <input type="email" name="email" placeholder="Your email" maxlength="40" />

            <?php echo $emailError; ?>
            <br><br>
           <input type="password" name="pass" placeholder="Your Password" maxlength="15" />
           <?php echo $passError; ?>
			<br><br>
           <button class="second_button" style="margin-left: 30px;" type="submit" name="updateInfo" onclick="infoUpdated()"><span>Save</span></button>
           <br><br>
          
    </form>


</body>
</html>

<?php
}
ob_end_flush();

?>
