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

        $sql = "SELECT tableNum, type FROM `venuehastable` WHERE branchID = '$branchID'";
        $sql2 = "SELECT date, start_time FROM `hostedevent` WHERE branchID = '$branchID' AND evid= '$evid'";

        $table_option = "";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $tableNum = $row["tableNum"];
                $type = $row["type"];
                $table_option.='<option value="'.$tableNum.'">'.$type.'</option>'; 
            }
        } else {
            echo "Error: ". $sql ."<br>". $conn->error."";
        }
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $date = $row2["date"];
        $start_time = $row2["start_time"];

        $conn->close();

        ?>
        <h1 style="padding-left: 20px;">Reserve a Table</h1>
        
        <!-- dropdown of next 14 days, cannot reserve further in the future -->
        <?php


        //dropdown of working hours
        

        $number_of_guests = '';
        for($z = 1; $z < 11; $z++){
            $number_of_guests.='<option value="'.$z.'">'.$z.' guest(s)</option>';
        }

        $table_picker='<select style="padding: 4px; margin-left: 20px;border-radius: 4px;font-size: 18px;" name="table">'.$table_option.'</select>';
        $guestsNum_picker='<select style="padding: 4px; margin-left: 20px;border-radius: 4px;font-size: 18px; margin-right: 10px;" name="guests">'.$number_of_guests.'</select>';

        //append all the dropdowns
        echo '<form action="#" method="post">'.$table_picker.$guestsNum_picker.'
        <input class="mini_button" type="submit" name="submit" value="Reserve" />
        </form>';

        if(isset($_POST['submit'])){
        $selected_date = $date;  // Storing Selected Value In Variable
        $selected_time = $start_time;
        $selected_table = $_POST['table'];
        $selected_guests = $_POST['guests'];
        $selected_branchID = $branchID;

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


        $sql = "SELECT *  FROM `tablereservation` WHERE tableNum = '$selected_table' AND date = '$selected_date' AND time = '$selected_time' AND branchID = '$selected_branchID'";

        $sql2 = "SELECT size, cost, numOfTableType FROM `venuehastable` WHERE tableNum = '$selected_table' AND branchID = '$selected_branchID'";
        $result2 = $conn->query($sql2);
        if($result2->num_rows > 0) {
            while($row2 = $result2->fetch_assoc()) {
                $size=$row2["size"];
                $cost=$row2["cost"];
                $numOfTableType=$row2["numOfTableType"];
            }
        }
        
        $totalNumOfGuests = 0;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $numOfGuests=$row["numOfGuests"];
                $totalNumOfGuests +=  $numOfGuests;
             }

            if(($totalNumOfGuests + $selected_guests) > ($numOfTableType*$size)){
                echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Sorry, we do not have any more space available in this section at this time. Please pick another time. <div><br>";
            } 
            else {
                //if time/date conflict: make a reservation if there are free tables in the section on that date/time
                //selectedTable has value tableNum, but in the sropdown the user picks table type
                echo "<br><div style='border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;'>Reservation made for ".$date." at ".$start_time."! The cost of the table is $".$cost.". It will be added to your final bill.</div>";
                //add INSERT INTO query

                $sqlInsertReservation = "INSERT INTO `tablereservation` VALUES ('', '$selected_date', '$selected_time', '$selected_guests', '$cid', '$selected_table', '$selected_branchID')"; //confirmationNum is auto-incremented
                $conn->query($sqlInsertReservation);
                
            }


        }else {
            //no time/date conflict so just make a reservation without checking for the number of spaces taken up
            echo "<br><div style='border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;'>Reservation made for ".$date." at ".$start_time."! The cost of the table is $".$cost.". It will be added to your final bill.</div>";
            //add INSERT INTO query
            $sqlInsertReservation = "INSERT INTO `tablereservation` VALUES ('', '$selected_date', '$selected_time', '$selected_guests', '$cid', '$selected_table', '$selected_branchID')"; //confirmationNum is auto-incremented
            $conn->query($sqlInsertReservation);
   
        }
            
        $conn->close();


    }
        ?>

    </body>
</html>
<?php
}
?>