
<?php
session_start();
if ( isset($_SESSION['user'])=="" ) {
    header("Location: index.php");
    exit;
  }
else
{
$sid=$_SESSION['user'];
$username=$_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cerulean/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>

<body>
<div style="text-align:right;">Logged in as <?php echo $username; ?> | <a href="http://localhost/304_project/logout.php">Logout</a></div>
<h1 style="margin-left:20px;">Welcome to VENUE</h1>
<h3 style="margin-left:20px;">You are logged in as a Manager</h3>
<br>
<a href="http://localhost/304_project/statistics.php"><button class="button" style="width:200px;height:80px; margin-left:20px; font-size: 18px; border-radius: 4px; color: #336a99;" id="StatsBtn" >See Company Statistics</button></a>
<br>
<br>

<div class="well well-lg">
<h3>Add Event:</h3>

<?php 
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

	//wrtie and sql that willl select all branchID and names of venues, and then we put the name as venue and value="$branchID" where that's a variable $branchID = $row["branchID"];

	$sql = "SELECT name, branchID FROM `venue`";

	$venue_options = '';
	$venues = $conn->query($sql);
        if($venues->num_rows > 0) {
            while($row = $venues->fetch_assoc()) {
                $venueName = $row["name"];
                $branchID = $row["branchID"];
                $venue_options .='<option value="'.$branchID.'">'.$venueName.'</option>';
            }
        } else {
        	echo "0 results";
        }

	$time_picker = "  <label for='stime'>Start Time:</label> <select name='stime' class='form-control'>'.$time_option.'</select>";
	$name = '  <label for="name">Title:</label> <input type="text" class="form-control" name="name">';
	$date = ' <label for="date">Date:</label> <input type="date" name="date" class="form-control">';
	$venue = ' <label for="branchID">Venue:</label> <select name="branchID" class="form-control">'.$venue_options.'</select>';
	$price = ' <label for="price">Price:</label> <input type="number" name="price" step="0.01" min="0" class="form-control">';

	echo '<form action="#" class="form-inline" method="post">'.$venue.$name.$date.$time_picker.$price.'
	        <input type="submit" name="submit1" value="Submit" />
	        </form>';

	$conn->close();
			
	if(isset($_POST['submit1'])){

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

	    $selected_date = $_POST['date'];  // Storing Selected Value In Variable
        $selected_time = $_POST['stime'];
        $input_eventName = $_POST['name'];
        $selected_venue = $_POST['branchID']; //gets branchID of selected venue name
        $input_price = $_POST['price'];
		
		//must enter a date greater than today
		if ($selected_date < date("Y-m-d")) {
			echo '<br><div style="border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;">Please enter a valid date in the future.</div>';
		} else {
			$sql = "INSERT INTO `hostedevent` VALUES ('','$input_eventName','$selected_date','$selected_time','$selected_venue','$input_price')"; // evid is auto-incremented
			$result = $conn->query($sql);
			$result;
			//add echo saying it was successful if it inserted and error if it didn't
			if ($result === TRUE) {
	    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">New record created successfully.</div>';
			} else {
	    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql ."<br>". $conn->error."</div>";
			}
		}
		
		

		$conn->close();
	}
	?>


	<h3>Add Entertainmant:</h3>
	<?php 

	$enname = ' <label>Entertainment name:</label> <input type="text" class="form-control" name="enname">';
	$genre = ' <label>Genre:</label> <input type="text" name="genre" class="form-control">';
	$cost = ' <label>Cost:</label> <input type="number" name="cost" step="0.01" min="0" class="form-control">';

	echo '<form action="#" class="form-inline" method="post">'.$enname.$genre.$cost.'
	        <input type="submit" name="submit2" value="Submit" />
	        </form>';
		
	if(isset($_POST['submit2'])){

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

		$input_enName = $_POST['enname'];
        $input_genre = $_POST['genre'];
        $input_cost = $_POST['cost'];
		
		$sql2 = "INSERT INTO `entertainment` VALUES ('','$input_enName','$input_genre','$input_cost')"; //enid is auto-incremented
		//add echo saying it was successful if it inserted and error if it didn't
		$result2 = $conn->query($sql2);
		$result2;
		if ($result2 === TRUE) {
    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">New record created successfully.</div>';
		} else {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql2 ."<br>". $conn->error."</div>"; //I hope this is right, how to check?
		}

		$conn->close();
	}
	?>
	
	<h3>Add Entertainment to Event:</h3>
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
	
	$sql = "SELECT name, branchID FROM `venue`";

	$venuelist = array();
	$venues = $conn->query($sql);
        if($venues->num_rows > 0) {
            while($row = $venues->fetch_assoc()) {
                $venueName = $row["name"];
                $branchID = $row["branchID"];
                $venuelist[$branchID] = $venueName;
            }
        } else {
        	echo "0 results";
        }

	$sql_ent = "SELECT name, enid FROM `entertainment`";

	$en_options = '';
	$entertainments = $conn->query($sql_ent);
        if($entertainments->num_rows > 0) {
            while($row = $entertainments->fetch_assoc()) {
                $entertainmentName = $row["name"];
                $entertainmentID = $row["enid"];
                $en_options .='<option value="'.$entertainmentID.'">'.$entertainmentName.'</option>';
            }
        } else {
        	echo "0 results";
        }
		
	$sql_ev = "SELECT name, evid, branchID FROM `hostedevent`";

	$ev_options = '';
	$evs = $conn->query($sql_ev);
        if($evs->num_rows > 0) {
            while($row = $evs->fetch_assoc()) {
                $evName = $row["name"];
                $evID = $row["evid"];
				$evBranchID = $row["branchID"];
                $ev_options .='<option value="'.$evID.','.$evBranchID.'">'.$evName.' at '.$venuelist[$evBranchID].'</option>';
            }
        } else {
        	echo "0 results";
        }
		
	$plays_ev = '   <label>Event:</label> <select name="plays_evid" class="form-control">'.$ev_options.'</select>';
	$plays_en= '  <label>Select Entertainment to play at Event:</label> <select name="plays_enid" class="form-control">'.$en_options.'</select>';
	
	echo '<form action="#" class="form-inline" method="post">'.$plays_ev.$plays_en.'
	        <input type="submit" name="submit11" value="Submit" />
	        </form>';

	$conn->close();
	
	if(isset($_POST['submit11'])){

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

		$input_plays_en = $_POST['plays_enid'];
        $selected_plays_ev = $_POST['plays_evid'];
		$split_string = (explode(",",$selected_plays_ev));
        $input_evid = $split_string[0];
		$input_brID = $split_string[1];
		
		$sql11 = "INSERT INTO `playsat` VALUES ('$input_evid','$input_plays_en','$input_brID')";
		$conn->query($sql11);
		//add echo saying it was successful if it inserted and error if it didn't
		$result11 = $conn->query($sql11);
		$result11;
		if ($result11 === TRUE) {
    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">New record created successfully.</div>';
		} else {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql11 ."<br>". $conn->error."</div>"; //I hope this is right, how to check?
		}

		$conn->close();
	}
	?>
	
	<h3>Add Venue:</h3>
	<?php 
	$vname = ' <label>Venue Name:</label> <input type="text" name="vname" class="form-control">';
	$address = ' <label>Address:</label> <input type="text" name="address" class="form-control">';
	$capacity = ' <label>Max. Capacity:</label> <input type="number" name="capacity" step="1" min="0" class="form-control"><br><br>';
	$cover_charge = ' <label>Cover charge:</label> <input type="number" name="cover" step="0.01" min="0" class="form-control">';

	echo '<form action="#" class="form-inline" method="post">'.$vname.$address.$capacity.$cover_charge.'
	        <input type="submit" name="submit3" value="Submit" />
	        </form>';
	


	if(isset($_POST['submit3'])){

		//add trigger to add tables for this new venue, assuming that every new venue has capacity for the 3 basic types of tables i.e. bar, intimate and regular
		// INSERT INTO `venuehastable` VALUES ('', 1, 30, 'bar', 3.95, NEW.branchID);
		// INSERT INTO `venuehastable` VALUES ('', 6, 15, 'regular', 7.99, NEW.branchID);
		//$addTablesToVenue = "CREATE TRIGGER `addtablestovenue` AFTER INSERT ON `venue`.`venue`
								// FOR EACH ROW BEGIN 
								// INSERT INTO `venuehastable` VALUES ('', 2, 10, 'intimate', 12.12, :new.branchID);
								// END;"; //tableNum is auto-incremented

		// CREATE TRIGGER `addtablestovenue` AFTER INSERT ON `venue` FOR EACH ROW INSERT INTO `venuehastable` (size, numOfTableType, type, cost, branchID) VALUES ( 2, 30, 'intimate', 12.12, new.branchID);

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
	    //$conn->query($addTablesToVenue); //FOR THE TRIGGER

	    
		$input_vName = $_POST['vname'];
        $input_address = $_POST['address'];
        $input_capacity = $_POST['capacity'];
		$input_cover = $_POST['cover'];
		
		$sql3 = "INSERT INTO `venue` VALUES ('','$input_vName','$input_address','$input_capacity','$input_cover')"; //branchID is auto-incremented
		//add echo saying it was successful if it inserted and error if it didn't
		$result3 = $conn->query($sql3);
		$result3;
		if ($result3 === TRUE) {
    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">New record created successfully.</div>';
   
		} else {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql3 ."<br>". $conn->error."</div>";
		}
		$conn->close();
	}
	?>
	
	<h3>Add Staff:</h3>
	<?php 
	$fname = ' <label> First name: </label> <input type="text" name="fname" class="form-control">';
	$lname = ' <label> Last name:</label> <input type="text" name="lname" class="form-control">';
	$venue = ' <label>Venue name:</label> <select name="branchID" class="form-control">'.$venue_options.'</select>';
	$manager_yn = ' <label> Manager? </label><select name="manager_yn" class="form-control"><option value=1>yes</option><option value=0>no</option></select>';

	echo '<form action="#"  class="form-inline"  method="post">'.$fname.$lname.$venue.$manager_yn.'
	        <input type="submit" name="submit4" value="Submit" />
	        </form>';
			
	if(isset($_POST['submit4'])){

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
	    
        $input_fName = $_POST['fname'];
		$input_lName = $_POST['lname'];
        $selected_venue = $_POST['branchID']; //gets branchID of selected venue name
		$selected_yn = $_POST["manager_yn"];
		
		
		$sql4 = "INSERT INTO `staffemployed` VALUES ('','$input_fName','$input_lName','$selected_venue','$selected_yn')"; //sid is auto-incremented
		$result4 = $conn->query($sql4);
		$result4;
		//add echo saying it was successful if it inserted and error if it didn't
		if ($result4 === TRUE) {
    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">New record created successfully.</div>';
		} else {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql4 ."<br>". $conn->error."</div>";
		}

		$conn->close();
	}
	?>
	
	<h3>Add Table:</h3>
	<?php 
	$tableSize = ' <label>Table Size: </label> <input type="number" name="tsize" step="1" min="0" class="form-control" >';
	$tType = ' <label> Table Type: </label><input type="text" name="ttype" class="form-control" >';
	$numOfTType = ' <label> Num of Table Type: </label> <input type="number" name="numttype" step="1" min="0" class="form-control" ><br><br>';
	$tableCost = ' <label>Table Cost: </label> <input type="number" name="tCost" step="0.01" min="0" class="form-control" >';
	$tvenue = ' <label> Venue: </label> <select name="tbranchID" class="form-control" >'.$venue_options.'</select>';

	echo '<form action="#" class="form-inline"  method="post">'.$tableSize.$tType.$numOfTType.$tableCost.$tvenue.'
	        <input type="submit" name="submit5" value="Submit" />
	        </form>';
			
	if(isset($_POST['submit5'])){

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

		$input_tableSize = $_POST['tsize'];
        $input_tType = $_POST['ttype'];
		$input_numOfTType = $_POST['numttype'];
		$input_tCost = $_POST['tCost'];
        $selected_tvenue = $_POST['tbranchID']; //gets branchID of selected venue name
		
		//tableNum autoincremented
		$sql5 = "INSERT INTO `venuehastable` VALUES ('','$input_tableSize','$input_numOfTType', '$input_tType','$input_tCost','$selected_tvenue')";
		$result5 = $conn->query($sql5);
		$result5;
		//add echo saying it was successful if it inserted and error if it didn't
		if ($result5 === TRUE) {
    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">New record created successfully.</div>';
		} else {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql5 ."<br>". $conn->error."</div>"; 
		}

		$conn->close();
	}
	//=========== REMOVE STARTS ========================================================
	?>

	</div>
	<div class="well well-lg">
	<h2>Remove </h2>
	<h3>Remove Event:</h3>
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
		
	$sql_ev = "SELECT name, evid, branchID FROM `hostedevent`";

	$ev_options2 = '';
	$evs = $conn->query($sql_ev);
        if($evs->num_rows > 0) {
            while($row = $evs->fetch_assoc()) {
                $evName = $row["name"];
                $evID = $row["evid"];
				$evBranchID = $row["branchID"];
                $ev_options2 .='<option value="'.$evID.','.$evBranchID.'">'.$evName.' at '.$venuelist[$evBranchID].'</option>';
            }
        } else {
        	echo "0 results";
        }
		
	$remove_ev = '  <label> Remove Event: </label> <select name="remove_evid" class="form-control" >'.$ev_options2.'</select>';

	echo '<form action="#" class="form-inline" method="post">'.$remove_ev.'
	        <input type="submit" name="submit6" value="Submit" />
	        </form>';

	$conn->close();
	
	if(isset($_POST['submit6'])){

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

        $selected_remove_ev = $_POST['remove_evid'];
		$split_string = (explode(",",$selected_remove_ev));
        $remove_evid = $split_string[0];
		$remove_brID = $split_string[1];
		
		$sql6 = "DELETE FROM `hostedevent` WHERE evid = '$remove_evid' AND branchID = '$remove_brID'";
		//add echo saying it was successful if it inserted and error if it didn't
		$result6 = $conn->query($sql6);
		$result6;
		//add echo saying it was successful if it inserted and error if it didn't
		if ($result6 === TRUE) {
    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">Record removed successfully.</div>';
		} else {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql6 ."<br>". $conn->error."</div>"; //I hope this is right, how to check?
		}

		$conn->close();
	}
	?>
	
	<h3>Remove Entertainmant:</h3>
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

	$sql_en = "SELECT name, enid FROM `entertainment`";

	$entertainment_options = '';
	$entertainments = $conn->query($sql_en);
        if($entertainments->num_rows > 0) {
            while($row = $entertainments->fetch_assoc()) {
                $entertainmentName = $row["name"];
                $entertainmentID = $row["enid"];
                $entertainment_options .='<option value="'.$entertainmentID.'">'.$entertainmentName.'</option>';
            }
        } else {
        	echo "0 results";
        }

	$del_entertainment= ' <label> Select Entertainment to delete: </label> <select name="del_enid" class="form-control" >'.$entertainment_options.'</select>';

	echo '<form action="#" class="form-inline" method="post">'.$del_entertainment.'
	        <input type="submit" name="submit7" value="Submit" />
	        </form>';

	$conn->close();
			
	if(isset($_POST['submit7'])){

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

        $selected_entertainment_to_delete = $_POST['del_enid']; //gets branchID of selected venue name
		
		$sql7 = "DELETE FROM `entertainment` WHERE enid = '$selected_entertainment_to_delete'";
		//add echo saying it was successful if it inserted and error if it didn't
		$result7 = $conn->query($sql7);
		$result7;
		
		if ($result7 === TRUE) {
    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">Record removed successfully.</div>';
		} else {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql7 ."<br>". $conn->error."</div>"; 
		}

		$conn->close();
	}
	?>
	
	<h3>Remove Entertainment from Event</h3>
	
	<?php
	$evremoveen = ' <label> Select event from which to remove entertainment: </label> <select name="evidremoveen" class="form-control">'.$ev_options.'</select>';
	echo '<form action="#"  class="form-inline" method="post">'.$evremoveen.'
	        <input type="submit" name="submit13" value="Submit" />
	        </form><br>';
			
	if(isset($_POST['submit13'])){

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
		
		$chosen_event = $_POST['evidremoveen'];
		$split_string2 = (explode(",",$chosen_event));
		$chosen_evid = $split_string2[0];
		$chosen_brid = $split_string2[1];
		
		$sql_ents = "SELECT e.enid AS en, name, p.branchID AS ebr, p.evid as eev FROM `playsat` p, `entertainment` e WHERE p.branchID = '$chosen_brid' AND p.evid = '$chosen_evid' AND e.enid = p.enid";
		
		$ent_options = '';
		$ents = $conn->query($sql_ents);
        if($ents->num_rows > 0) {
            while($row = $ents->fetch_assoc()) {
                $ent_en = $row["en"];
				$ebr = $row["ebr"];
				$eev = $row["eev"];
                $ent_name = $row["name"];
                $ent_options .='<option value="'.$ent_en.','.$ebr.','.$eev.'">'.$ent_name.'</option>';
            }


            $del_ent= ' <label> Select entertainment to remove: </label> <select name="del_ent" class="form-control" >'.$ent_options.'</select>';
		
		echo '<form action="#" class="form-inline" method="post">'.$del_ent.'
	        <input type="submit" name="submit14" value="Submit" />
	        </form>';

        } else {
        	echo "<br><div style='border-style: solid; border-color: blue; background-color:#89C2F2; padding:10px;'>This event does not have entertainment.</div>";
        }

		
			
		$conn->close();
	}
		
		if(isset($_POST['submit14'])){

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

        $remove_ent = $_POST['del_ent']; 
		$split_string3 = (explode(",",$remove_ent));
		$chosen_ent = $split_string3[0];
		$chosen_ebr = $split_string3[1];
		$chosen_eev = $split_string3[2];
		
		$sql14 = "DELETE FROM `playsat` WHERE enid = '$chosen_ent' AND evid = '$chosen_eev' AND branchID = '$chosen_ebr'";
		
		$result14 = $conn->query($sql14);
		$result14;
		//add echo saying it was successful if it inserted and error if it didn't
		if ($result14 === TRUE) {
    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">Record removed successfully.</div>';
		} else {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql12 ."<br>". $conn->error."</div>"; //I hope this is right, how to check?
		}

		$conn->close();
	}
	
	?>
	
	<h3>Remove Venue:</h3>
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

	$sql = "SELECT name, branchID FROM `venue`";

	$venue_options = '';
	$venues = $conn->query($sql);
        if($venues->num_rows > 0) {
            while($row = $venues->fetch_assoc()) {
                $venueName = $row["name"];
                $branchID = $row["branchID"];
                $venue_options .='<option value="'.$branchID.'">'.$venueName.'</option>';
            }
        } else {
        	echo "0 results";
        }

	$venue = ' <label> Select Venue to delete: </label> <select name="del_branchID" class="form-control">'.$venue_options.'</select>';

	echo '<form action="#" class="form-inline" method="post">'.$venue.'
	        <input type="submit" name="submit8" value="Submit" />
	        </form>';

	$conn->close();
			
	if(isset($_POST['submit8'])){

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

        $selected_venue_to_delete = $_POST['del_branchID']; //gets branchID of selected venue name
		
		$sql8 = "DELETE FROM `venue` WHERE branchID = '$selected_venue_to_delete'";
		
		$result8 = $conn->query($sql8);
		$result8;
		//add echo saying it was successful if it inserted and error if it didn't
		if ($result8 === TRUE) {
    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">Record removed successfully.</div>';
		} else {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql8 ."<br>". $conn->error."</div>"; //I hope this is right, how to check?
		}

		$conn->close();
	}
	?>
	
	<h3>Remove Staff:</h3>
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

	//wrtie and sql that willl select all branchID and names of venues, and then we put the name as venue and value="$branchID" where that's a variable $branchID = $row["branchID"];

	$sql_staff = "SELECT sid, f_name, l_name FROM `staffemployed`";

	$staff_options = '';
	$staff = $conn->query($sql_staff);
        if($staff->num_rows > 0) {
            while($row = $staff->fetch_assoc()) {
                $sid = $row["sid"];
                $f_name = $row["f_name"];
				$l_name = $row["l_name"];
                $staff_options .='<option value="'.$sid.'">'.$f_name.' '.$l_name.'</option>';
            }
        } else {
        	echo "0 results";
        }

		$del_staff= ' <label> Select Staff to fire: </label> <select name="del_staff" class="form-control">'.$staff_options.'</select>';

		echo '<form action="#" class="form-inline" method="post">'.$del_staff.'
	        <input type="submit" name="submit9" value="Submit" />
	        </form>';
			
		$conn->close();
			
	if(isset($_POST['submit9'])){

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

        $remove_staff = $_POST['del_staff']; 
		
		$sql9 = "DELETE FROM `staffemployed` WHERE sid = '$remove_staff'";
		$conn->query($sql9);
		//add echo saying it was successful if it inserted and error if it didn't

		$conn->close();
	}
	?>
	
	<h3>Remove Table:</h3>
	<?php
	$tvenueremove = ' <label> Select venue from which to remove table: </label> <select name="tbranchIDremove" class="form-control">'.$venue_options.'</select>';
	echo '<form action="#" class="form-inline" method="post">'.$tvenueremove.'
	        <input type="submit" name="submit10" value="Submit" />
	        </form>';
			
	if(isset($_POST['submit10'])){

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
		
		$chosen_venue = $_POST['tbranchIDremove'];
		
		$sql_tables = "SELECT tableNum, type FROM `venuehastable` WHERE branchID = '$chosen_venue'";
		
		$table_options = '';
		$tables = $conn->query($sql_tables);
        if($tables->num_rows > 0) {
            while($row = $tables->fetch_assoc()) {
                $tableNum = $row["tableNum"];
                $tableType = $row["type"];
                $table_options .='<option value="'.$tableNum.'">'.$tableType.'</option>';
            }


            $del_table= ' Select table type to remove: <select name="del_table">'.$table_options.'</select>';
		
		echo '<form action="#" method="post">'.$del_table.'
	        <input type="submit" name="submit12" value="Submit" />
	        </form>';
        } else {
        	echo "<br><div style='border-style: solid; border-color: blue; background-color:#89C2F2; padding:10px;'>This venue has no tables to remove. Job well done!</div>";
        }

		
			
		$conn->close();
	}
		
		if(isset($_POST['submit12'])){

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

        $remove_table = $_POST['del_table']; 
		
		$sql12 = "DELETE FROM `venuehastable` WHERE tableNum = '$remove_table'";
		
		$result12 = $conn->query($sql12);
		$result12;
		//add echo saying it was successful if it inserted and error if it didn't
		if ($result12 === TRUE) {
    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">Record removed successfully.</div>';
		} else {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql12 ."<br>". $conn->error."</div>"; //I hope this is right, how to check?
		}

		$conn->close();
	}
	
	?>
	<br>
	<br>
	</div>
	<div class="well well-lg">
<h3>Update Venue:</h3>
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

	$sqlvid = "SELECT branchID FROM `venue`";

	$venueid_options = '';
	$venueids = $conn->query($sqlvid);
        if($venueids->num_rows > 0) {
            while($row = $venueids->fetch_assoc()) {
                $branchID = $row["branchID"];
                $venueid_options .='<option value="'.$branchID.'">'.$branchID.'</option>';
            }
        } else {
        	echo "0 results";
        }

	$vid = ' <label for="branchID">Venue ID:</label> <select name="vid" class="form-control">'.$venueid_options.'</select>';
	$vname = ' <label>Venue Name:</label> <input type="text" name="vname" class="form-control">';

	echo '<form action="#" class="form-inline" method="post">'.$vid.$vname.'
	        <input type="submit" name="submit15" value="Submit" />
	        </form>';
	

	$conn->close();
	if(isset($_POST['submit15'])){

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
	    //$conn->query($addTablesToVenue); //FOR THE TRIGGER

	    $input_vid = $_POST['vid'];
		$input_vName = $_POST['vname'];
      
		$sql15 = "UPDATE `venue` SET name = '$input_vName' WHERE branchID = '$input_vid'";
		//add echo saying it was successful if it inserted and error if it didn't
		$result15 = $conn->query($sql15);
		$result15;
		if ($result15 === TRUE) {
    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">Record modified successfully.</div>';
   
		} else {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql15 ."<br>". $conn->error."</div>";
		}
		$conn->close();
	}
	?>
	

	
	
	
	
	
	
	
	
	<h3>Update Customer hotness:</h3>
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

	$sqlcus = "SELECT cid, f_name, l_name FROM `customer`";

	$cus_options = '';
	$customers = $conn->query($sqlcus);
        if($customers->num_rows > 0) {
            while($row = $customers->fetch_assoc()) {
                $ccid = $row["cid"];
				$cf_name = $row["f_name"];
				$cl_name = $row["l_name"];
                $cus_options .='<option value="'.$ccid.'">'.$cf_name.' '.$cl_name.'</option>';
            }
        } else {
        	echo "0 results";
        }

	$hotness_opt = '';
        for($z = 1; $z < 11; $z++){
            $hotness_opt.='<option value="'.$z.'">'.$z.'</option>';
        }
		
	$cname = ' <label for="cid">Customer:</label> <select name="cid" class="form-control">'.$cus_options.'</select>';
	$hotness = ' <label>Hotness Level:</label> <select name="hotness" class="form-control">'.$hotness_opt.'</select>';

	echo '<form action="#" class="form-inline" method="post">'.$cname.$hotness.'
	        <input type="submit" name="submit16" value="Submit" />
	        </form>';
	

	$conn->close();
	if(isset($_POST['submit16'])){

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
	    //$conn->query($addTablesToVenue); //FOR THE TRIGGER

	    $input_cid = $_POST['cid'];
		$input_hotness = $_POST['hotness'];
      
		$sql16 = "UPDATE `customer` SET hotness = '$input_hotness' WHERE cid = '$input_cid'";
		//add echo saying it was successful if it inserted and error if it didn't
		$result16 = $conn->query($sql16);
		$result16;
		if ($result16 === TRUE) {
    		echo '<br><div style="border-style: solid; border-color: green; background-color:#daf7a6; padding:10px;">Record modified successfully.</div>';
   
		} else {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>Error: ". $sql16 ."<br>". $conn->error."</div>";
		}
		$conn->close();
	}
	?>
	
	
	
	
	



<br>
</div>
	<br>
	<br>
	<br>
</body>
</html>

 <?php
}
?>