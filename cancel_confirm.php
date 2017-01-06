
<?php

if ( isset($_GET['confirmationNum'])=="" ) {
    header("Location: index.php");
    exit;
  }
  else
  {
  $confirmationNum = $_GET['confirmationNum'];
// code that's going to remove the reservation from the DB, not sure where to put it yet, cuz it needs to be called onclick, but if I put it in the script tags it overwrites the session
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

	// only if they click the button delete
	$cancelReservation = "DELETE FROM `tablereservation` WHERE confirmationNum = '$confirmationNum'";
	//commented out for now, don't want my reservations cancelled as soon as I open the page - because that's what happens
	//WHY DO YOU DELETE RIGHT AWAY INSTEAD OF ON CLICK?!
	$conn->query($cancelReservation);
	$conn->close();
	header("Location: customer_reservations.php");
    exit;
	}

?>