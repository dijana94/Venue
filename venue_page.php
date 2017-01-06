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


<!--Venue Page-->
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="customer.css">
</head>
<body background="party.jpg" onload="initialize()">

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

<!-- DO WE NEED TO DO THIS EVERY TIME WE MAKE A QUERY? -->
<?php 


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
//get venue details
$sql = "SELECT name, branchID, address, capacity, cover_charge FROM `venue` WHERE branchID = '$branchID'";
//TODO: GET ALL EVENTS FOR VENUE
$eventsForVenue = "SELECT * FROM `hostedevent` WHERE branchID = '$branchID'";
$result = $conn->query($sql);
$allEvents = $conn->query($eventsForVenue);

$capacity = 0;
$cover_charge = 0;

//details query for side div
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$venueName=$row["name"];
		$capacity = $row["capacity"];
		$cover_charge = $row["cover_charge"];
		$venueAddress = $row["address"];
    }
} else {
    echo "0 results";
}

//events list query for events :P
$eventList = "";
if ($allEvents->num_rows > 0) {
    // output data of each row
    while($rowEvents = $allEvents->fetch_assoc()) {
    	$eventName=$rowEvents["name"];
		$date = $rowEvents["date"];
		$time = $rowEvents["start_time"];
		$evid = $rowEvents["evid"];
		$eventList .=  '<div class="event" style="padding: 20px; background:rgba(20, 20, 20, 0.75);">'.$eventName.'<br>'.$time.'<br>'.$date.'<br><br><a href = "http://localhost/304_project/event_page.php?evid='.$evid.'"><button class="mini_button" style="vertical-align:middle">Buy Tickets</button></a></div><br>';
    }
} else {
    echo "No events, yet! Sorry.";
}

$conn->close();

 ?>
<!--  want to make two divs on the side, one which will have the address, cover charge and capacity of the venue and another that will have the "make a reservation button" -->
<h1 style="padding-left: 20px;"><?php echo $venueName; ?></h1>
<!-- this div is for the list of events for each specific venue -->
<div style="overflow:hidden;">
<div style="float:left;width:60%;">  <?php echo $eventList; ?></div>

<div style="float:right;width:35%;background:rgba(20, 20, 20, 0.7); padding-left: 20px;padding-bottom: 20px;"><h4>Details</h4>
	<p>Address: <?php echo $venueAddress; ?></p>
	<p>Cover charge: <?php echo $cover_charge;?></p>
	<p>Capacity of venue: <?php echo $capacity;?></p>

<div><a href="http://localhost/304_project/table_reservation.php?branchID=<?php echo $branchID;?>"><button class="mini_button"style="width: 220px;">Make a Reservation</button></a></div>




</div>
</div>

    <h3 style="padding-left: 20px;">Location</h3>
    <div id="map" style="width: 480px; height: 320px; padding-left: 20px;"></div>
	<div id="address" style="display: none;">
		<?php echo $venueAddress; ?>
	</div>
    <script>
  var geocoder;
  var map;
  function initialize() {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(49.279123, -123.123257);
    var mapOptions = {
      zoom: 14,
      center: latlng
    }
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
	
	
	 var addressdiv = document.getElementById('address'); 
	 var address = addressdiv.textContent;	 
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == 'OK') {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
        });
      } else {
		  map.setCenter(latlng);
        alert('Geocode was not successful for the following reason: ' + status + address);
      }
    });

  }

    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD0t1gWCV_4zDtWvqLTRhd89N-v_44V2PQ&callback=initMap">
    </script>

 </body>
 <br>
 <br>
</html>

 <?php
}
?>