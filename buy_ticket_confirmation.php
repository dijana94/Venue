
<?php
session_start();
if ( isset($_SESSION['user'])=="" ) {
    header("Location: index.php");
    exit;
  }
else
{
$cid=$_SESSION['user'];
$username=$_SESSION['username'];
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
  Logged in as <?php echo $username; ?>  <a href="http://localhost/304_project/logout.php">Logout</a>
  </li>
</ul>

<?php 


$evid = $_GET['evid'];
$branchID = $_GET['branchID']; 

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

//INSERT this new information into the database

$sql = "INSERT INTO `buysticketsfor` VALUES ('', '$branchID', '$evid', '$cid')"; // ticketID is autoincrement
$conn->query($sql);

$conn->close();

 ?>

<h2 style="margin-left: 20px;">See you there!</h2>

<p style="margin-left: 20px; font-size:18px;">Success! You have a ticket for your desired event. You will receieve your ticket via email. Please bring it with you to the event!</p>

<h3 style="margin-left: 20px;">Enjoy your event!</h3>
<p><a href="http://localhost/304_project/tablebooking.php?evid=<?php echo $evid; ?>&branchID=<?php echo $branchID; ?>"><button class="second_button" style="margin-left: 20px; width: 180px;"><span>Book table for this event</span></button></a></p>
 
 </body>
</html>
<?php
}
?>
 