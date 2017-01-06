
<?php

//if accessing this page directly go to login
if ( isset($_GET['ticketID'])=="" ) {
    header("Location: index.php");
    exit;
  }
  else
  {
  $ticketID = $_GET['ticketID'];
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
	$cancelTicket = "DELETE FROM `buysticketsfor` WHERE ticketID = '$ticketID'";
	//commented out for now, don't want my reservations cancelled as soon as I open the page - because that's what happens
	//WHY DO YOU DELETE RIGHT AWAY INSTEAD OF ON CLICK?!
	$conn->query($cancelTicket);
	$conn->close();
	header("Location: customer_tickets.php");
    exit;
	}
?>