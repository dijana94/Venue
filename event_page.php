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

//BROKEN PREVIOUS QUERY
// $sql = "SELECT ev.evid AS evid, en.enid AS enid, ev.name AS eventName, date, start_time, en.name AS enName, genre, v.name AS venueName, address, ev.price AS eventPrice, v.branchID AS branchID FROM `venue` v, `hostedevent` ev, `playsat` p, `entertainment` en, `buysticketsfor`t WHERE ev.branchID = p.branchID AND ev.evid = p.evid AND en.enid = p.enid AND v.branchID = p.branchID AND t.evid = ev.evid AND ev.evid = '$evid' AND p.evid = '$evid'  AND t.evid = '$evid'";

$sql = "SELECT ev.name AS eventName, date, start_time, price, en.name AS enName, genre, v.name AS venueName, address, v.branchID AS branchID FROM `entertainment` en, `hostedevent` ev, `playsat` p, `venue` v WHERE ev.branchID = p.branchID AND ev.evid = p.evid AND en.enid = p.enid AND v.branchID = ev.branchID AND p.evid ='$evid'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        $eventName = $row["eventName"];
        $eventPrice = $row["price"];
        $date = $row["date"];
        $time = $row["start_time"];

        $enName = $row["enName"];
        $enGenre = $row["genre"];

        $venueName = $row["venueName"];
        $venueAddress = $row["address"];
        $branchID = $row["branchID"];
        
    }
} else {
    echo "<br><div style='border-style: solid; border-color: purple; padding:10px;'>There is no entertianment at this event.</div>";
}

if($result->num_rows == 0) {
  echo "Sorry about that! Someone will fix it soon.";
} else {
  echo '<div  style="width:50%;background:rgba(20, 20, 20, 0.7); padding-left: 20px;padding-top: 20px;">
<h2>'.$eventName.'</h2>
<h3>Be there at '.$time.' on '.$date.'.</h3>
<p>'.$enName.' of '.$enGenre.' genre will be playing.</p>
<p>Individual tickets for this event are $'.$eventPrice.'</p>
<p>This event is at '.$venueName.' located at '.$venueAddress.'</p>
<p><a href = "http://localhost/304_project/buy_ticket_confirmation.php?evid='.$evid.'&branchID='.$branchID.'"><button class="second_button" style="width:180px;"><span>Buy my ticket!</span></button></a></p>
</div>';
}

//I don't want it to be a table, just tell them all the info in paragraphs below

$conn->close();
?>
</body>
</html>
<?php
}
?>