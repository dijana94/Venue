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

<h2><?php echo $user?>'s Reservations</h2>

<?php 
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

//maybe later join with venue has table to get the section type too
$sql = "SELECT name, confirmationNum, numOfGuests, date, time FROM `tablereservation` r, `venue` v WHERE r.branchID = v.branchID AND cid = '$cid'";

$result = $conn->query($sql);

$reservationList = "";
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$venueName = $row["name"];
		$confirmationNum = $row["confirmationNum"];
		$numOfGuests = $row["numOfGuests"];
		$date = $row["date"];
		$time = $row["time"];

		$reservationList .=  '<div style="padding: 20px; height: 100px; width:50%;background:rgba(20, 20, 20, 0.7); border: 1px solid black; ">'.$venueName.'<br> On '.$date.' at '.$time.'<br>Confirmation number: '.$confirmationNum.'<br>For '.$numOfGuests.' Guest(s)<br><a href="cancel_confirm.php?confirmationNum='.$confirmationNum.'" onclick="return confirm(\'Are you sure?\')"><button class = "mini_button" style= "font-size: 12px;padding: 2px;width: 70px;"value ="$confirmationNum">Cancel</button></a></div><br>';

		
		// <a href="http://stackoverflow.com" 
  //   onclick="return confirm('Are you sure?');">My Link</a>
    }
} else {
    echo "You don't have any reservations yet! Go make some ;)";
}

$conn->close(); 
echo $reservationList;
?>




</body>
</html>
<?php
}
?>