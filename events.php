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
	<link rel="stylesheet" type="text/css" href="dropdown_style.css">
	<script>
		//ALL THIS IS FOR FILTERING BY VENUE NAME
		function filterEvents() {
			hideEvents();
			var events = document.getElementsByName('events');
			var txt = "";
			var i;
			for (i = 0; i < events.length; i++) {
				if(events[i].checked) {
					txt += events[i].value + " ";
					showEvents(events[i].value);
				}
			}
		}

		function hideEvents() {
			var elements = document.getElementsByClassName("event");
			for(var i = 0; i < elements.length; i++) {
				elements[i].style.display = "none";
			}
		}

		function showEvents(eventName) {
			var elements = document.getElementsByClassName(eventName);
			console.log(elements);
			for(var i = 0; i < elements.length; i++) {
				elements[i].style.display = "block";
			}
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
  Logged in as <?php echo $username; ?>  <a href="http://localhost/304_project/logout.php">Logout</a>
  </li>
</ul>

<h1 style="margin-left:20px;">Upcoming Events</h1>

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

$sql = "SELECT evid, e.name AS eventName, date AS eventDate, start_time, v.name AS venueName FROM `hostedevent` e, `venue` v WHERE v.branchID = e.branchID";

$result = $conn->query($sql);

$capacity = 0;
$cover_charge = 0;
$eventList = "";
$checkboxList = "";
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$eventName=$row["eventName"];
		$venueName = $row["venueName"];
		$date = $row["eventDate"];
		$time = $row["start_time"];
		$evid = $row["evid"];
		$eventList .=  '<div  style="padding: 20px; background:rgba(20, 20, 20, 0.75); float:left; margin-bottom:10px; margin-left:10px; width:40%;" class="event '.$venueName.'"><a href = "http://localhost/304_project/event_page.php?evid='.$row["evid"].'"><span style = "font-size: 20px; color: #FFFFFF;">'.$eventName.'</span></a><br>'.$time.'<br>'.$date.'<br>'.$venueName.'</div>';


		
		if(strpos($checkboxList, $venueName) !== false){

		} else {
			$checkboxList .='<input  style="margin-left:20px;" type="checkbox" name="events" value="'.$venueName.'"><span style = "font-size: 20px;">'.$venueName.'</span> ';
		}
    }
} else {
    echo "0 results";
}

$conn->close();  

echo $checkboxList. '<button class = "mini_button" style="margin-left:20px;" onclick="filterEvents()"><span>Filter</span></button><hr>'.$eventList;

?>



</body>
</html>
<?php
}
?>