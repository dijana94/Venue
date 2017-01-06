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
<html lang="en">
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
            echo "0 results";
        }


        $conn->close();

        ?>
        <h1 style="margin-left: 20px;">Reserve a Table</h1>
        
        <!-- dropdown of next 14 days, cannot reserve further in the future -->
        <?php
        $day_option='';
        for ($x = 0; $x <= 14; $x++) {
            $timer='+'.$x.' day';
            $dayafter=strtotime($timer);
            $day_database_format= date("Y-m-d",$dayafter);
            $day_user_format= date("F j, Y",$dayafter); 
            $day_option.='<option value="'.$day_database_format.'">'.$day_user_format.'</option>';    
        } 

        //dropdown of working hours
        $time_option='';
        $starttime= date("H:i:s", mktime(0, 0, 0));
        for ($y = 9; $y < 24; $y++) {
            $timer='+'.$y.' hour';
            $timeafter=strtotime($timer, strtotime($starttime));
            $time_database_format= date("H:i:s",$timeafter);
            $time_user_format= date("h:i A",$timeafter); 
            $time_option.='<option value="'.$time_database_format.'">'.$time_user_format.'</option>';
        }

        $number_of_guests = '';
        for($z = 1; $z < 11; $z++){
            $number_of_guests.='<option value="'.$z.'">'.$z.' guest(s)</option>';
        }

        $date_picker='<select style="padding: 4px; margin-left: 20px;border-radius: 4px;font-size: 18px;" name="date">'.$day_option.'</select>';
        $time_picker = '<select style="padding: 4px; margin-left: 10px;border-radius: 4px;font-size: 18px;" name="time">'.$time_option.'</select>';
        $table_picker='<select style="padding: 4px; margin-left: 10px;border-radius: 4px;font-size: 18px;" name="table">'.$table_option.'</select>';
        $guestsNum_picker='<select style="padding: 4px; margin-left: 10px;border-radius: 4px;font-size: 18px;" name="guests">'.$number_of_guests.'</select>';

        //append all the dropdowns
        echo '<form action="#" method="post">'.$date_picker.$time_picker.$table_picker.$guestsNum_picker.'
        <input class="mini_button" type="submit" name="submit" value="Reserve" />
        </form>';

        if(isset($_POST['submit'])){
        $selected_date = $_POST['date'];  // Storing Selected Value In Variable
        $selected_time = $_POST['time'];
        $selected_table = $_POST['table'];
        $selected_guests = $_POST['guests'];
        $selected_branchID = $branchID;

        // echo "You have selected :" .$selected_date.'*'.$selected_time.'*'.$selected_table.'*'.$selected_guests.'*'.$selected_branchID;  // Displaying Selected Value

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


        $sql0 = "SELECT *  FROM `tablereservation` WHERE tableNum = '$selected_table' AND date = '$selected_date' AND time = '$selected_time' AND branchID = '$selected_branchID'";

        $sql2 = "SELECT size, cost, numOfTableType FROM `venuehastable` WHERE tableNum = '$selected_table' AND branchID = '$selected_branchID'";

        $sql3 = "SELECT evid FROM `hostedevent` WHERE branchID = '$branchID' AND date = '$selected_date' AND start_time = '$selected_time' ";
        $result3 = $conn->query($sql3);

        if($result3->num_rows > 0) {
            $row3 = $result3->fetch_assoc();
            $evid=$row3["evid"];
            echo '<br><div style="border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;">Sorry, there is an event goin on at this time, you must buy a ticket if you wish to reserve a table. Alternatively, pick another time. <br><br><p><a href = "http://localhost/304_project/buy_ticket_confirmation.php?evid='.$evid.'&branchID='.$branchID.'"><button>See event info</button></a></p></div>';
            $eventCheck = 0;
        } else {
            $eventCheck = 1;
        }

        $result2 = $conn->query($sql2);
        if($result2->num_rows > 0) {
            while($row2 = $result2->fetch_assoc()) {
                $size=$row2["size"];
                $cost=$row2["cost"];
                $numOfTableType=$row2["numOfTableType"];
            }
        }
   
        $totalNumOfGuests = 0;
        $result = $conn->query($sql0);
        if($eventCheck == 1) {
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
                echo "<br><div style='border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;'>Reservation made! The cost of the table is $".$cost.". It will be added to your final bill.</div>";
                //add INSERT INTO query
                $sqlInsertReservation = "INSERT INTO `tablereservation` VALUES ('', '$selected_date', '$selected_time', '$selected_guests', '$cid', '$selected_table', '$selected_branchID')"; //confirmationNum is auto-incremented
                $conn->query($sqlInsertReservation);
                
            }

        }else {
            //no time/date conflict so just make a reservation without checking for the number of spaces taken up
            echo "<br><div style='border-style: solid; color: #222222; border-color: green; background-color:#daf7a6; padding:10px;'>Reservation made! The cost of the table is $".$cost.". It will be added to your final bill.</div>";
            //add INSERT INTO query
            $sqlInsertReservation = "INSERT INTO `tablereservation` VALUES ('', '$selected_date', '$selected_time', '$selected_guests', '$cid', '$selected_table', '$selected_branchID')"; //confirmationNum is auto-incremented
            $result = $conn->query($sqlInsertReservation);
   
        } 
        }
         
        $conn->close();


    }
        ?>

    </body>
</html>
<?php
}
?>