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
<h1 style="margin-left:20px;">Statistics Page</h1>

<div class="well well-lg">
<h4>Customers who've been to every venue</h4> <!-- **THIS IS DIVISION** -->
<?php
$vips = ' <label for="vips">Show VIP table:</label>';

echo '<form action="#" class="form-inline" method="post">'.$vips.'
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


	$sql2 = "SELECT c.f_name, c.l_name FROM `customer` c WHERE NOT EXISTS (SELECT * from `venue` v WHERE NOT EXISTS (SELECT t.branchID FROM buysticketsfor t WHERE t.branchID = v.branchID AND c.cid = t.cid))";


	$result2 = $conn->query($sql2);
	echo $conn->error;

		//add echo saying it was successful if it inserted and error if it didn't
		
		$viplist = "";
		if ($result2->num_rows > 0) {
			// output data of each row
			while($row = $result2->fetch_assoc()) {
				$f_name=$row["f_name"];
				$l_name = $row["l_name"];
				$viplist .=  '<tr><td>'.$f_name.'</td><td>'.$l_name.'</td></tr>';
			}
		}

		if ($result2->num_rows == 0) {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>No Vip's to display.</div>"; 
		} else {
				echo '	<br><table style="width:25%">
						<tr>
					    <th>First Name</th>
					    <th>Last Name</th> 
						</tr>
						'.$viplist.'
						</table>';	
		}

		$conn->close();
		

	}
?>

<br>



<h4>Number of tickets sold for each event</h4> 
<?php

$ticketssold = ' <label for="ticketssold">Show Tickets Sold table:</label>';

echo '<form action="#" class="form-inline" method="post">'.$ticketssold.'
	        <input type="submit" name="submit6" value="Submit" />
	        </form>';


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
	
	
	$sqlv = "SELECT name, branchID FROM `venue`";

	$venuelist = array();
	$venues = $conn->query($sqlv);
        if($venues->num_rows > 0) {
            while($row = $venues->fetch_assoc()) {
                $venueName = $row["name"];
                $branchID = $row["branchID"];
                $venuelist[$branchID] = $venueName;
            }
        } else {
        	echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>There are no venues.</div>";
        }
		
	$sqle = "SELECT name, evid FROM `hostedevent`";
	
	$eventnamelist = array();
	$events = $conn->query($sqle);
        if($events->num_rows > 0) {
            while($row = $events->fetch_assoc()) {
                $eventName = $row["name"];
                $evid = $row["evid"];
                $eventnamelist[$evid] = $eventName;
            }
        } else {
        	echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>There are no events happening at any of the venues.</div>";
        }

		
		$sql8 = "SELECT COUNT(*) AS num,branchID,evid FROM customer c, buysticketsfor t WHERE c.cid = t.cid GROUP BY branchID, evid"; 
		$resulttickets = $conn->query($sql8);
		//add echo saying it was successful if it inserted and error if it didn't
		
		$ticketlist = "";
		if ($resulttickets->num_rows > 0) {
			// output data of each row
			while($row = $resulttickets->fetch_assoc()) {
				$sold=$row["num"];
				$branchID = $row["branchID"];
				$evid = $row["evid"];
				$ticketlist .=  '<tr><td>'.$eventnamelist[$evid].' at '.$venuelist[$branchID].'</td><td>'.$sold.'</td></tr>';
			}
		}

		if ($resulttickets->num_rows == 0) {
    		echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>No tickets to display.</div>"; 
		} else {
				echo '<br>	<table style="width:30%">
				<tr>
			    <th>Event</th> 
			    <th>tickets sold</th>
				</tr>
				'.$ticketlist.'
				</table>';
			}

		$conn->close();
		
		
	}
	?>

<br>









<h4>Average hotness of each event</h4> 
<?php

$hotness = ' <label for="hotness">Show Event hotness table:</label>';

echo '<form action="#" class="form-inline" method="post">'.$hotness.'
	        <input type="submit" name="submit1" value="Submit" />
	        </form>';


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
	
	
	$sqlv = "SELECT name, branchID FROM `venue`";

	$venuelist = array();
	$venues = $conn->query($sqlv);
        if($venues->num_rows > 0) {
            while($row = $venues->fetch_assoc()) {
                $venueName = $row["name"];
                $branchID = $row["branchID"];
                $venuelist[$branchID] = $venueName;
            }
        } else {
        	echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>No venues to display.</div>";
        }
		
	$sqle = "SELECT name, evid FROM `hostedevent`";
	
	$eventnamelist = array();
	$events = $conn->query($sqle);
        if($events->num_rows > 0) {
            while($row = $events->fetch_assoc()) {
                $eventName = $row["name"];
                $evid = $row["evid"];
                $eventnamelist[$evid] = $eventName;
            }
        } else {
        	echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>No events to display.</div>";
        }

		
		$sql1 = "SELECT AVG(hotness),branchID,evid FROM customer c, buysticketsfor t WHERE c.cid = t.cid GROUP BY branchID, evid"; // evid is auto-incremented
		$result = $conn->query($sql1);
		//add echo saying it was successful if it inserted and error if it didn't
		
		$tablelist = "";
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$avgHot=$row["AVG(hotness)"];
				$branchID = $row["branchID"];
				$evid = $row["evid"];
				$tablelist .=  '<tr><td>'.$eventnamelist[$evid].' at '.$venuelist[$branchID].'</td><td>'.$avgHot.'</td></tr>';
			}
		}

		if ($result->num_rows == 0) {
			echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>No result to display.</div>";
		} else {
				echo '<br>	<table style="width:30%">
					<tr>
				    <th>Event</th> 
				    <th>avg hotness</th>
					</tr>
					'.$tablelist.'
					</table>';
		}

		$conn->close();
		
		
	}
	?>

<br>
<h4>Event with Max hotness</h4>

<?php
$maxhot2 = ' <label for="maxhot">Show hottest event:</label>';

echo '<form action="#" class="form-inline" method="post">'.$maxhot2.'
	        <input type="submit" name="submit3" value="Submit" />
	        </form>';


if(isset($_POST['submit3'])){

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
	
	$sqlv = "SELECT name, branchID FROM `venue`";

	$venuelist = array();
	$venues = $conn->query($sqlv);
        if($venues->num_rows > 0) {
            while($row = $venues->fetch_assoc()) {
                $venueName = $row["name"];
                $branchID = $row["branchID"];
                $venuelist[$branchID] = $venueName;
            }
        } else {
        	echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>No venues to display.</div>";
        }
		
	$sqle = "SELECT name, evid FROM `hostedevent`";
	
	$eventnamelist = array();
	$events = $conn->query($sqle);
        if($events->num_rows > 0) {
            while($row = $events->fetch_assoc()) {
                $eventName = $row["name"];
                $evid = $row["evid"];
                $eventnamelist[$evid] = $eventName;
            }








        } else {
        	echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>No events to display.</div>";
        }
		
		$sql4 = "SELECT MAX(avghot) AS maxhot,branchID,evid 
				FROM
				(SELECT AVG(hotness) AS avghot,branchID,evid 
				 FROM `customer` c, `buysticketsfor` t 
				 WHERE c.cid = t.cid 
				 GROUP BY branchID, evid) 
				 AS T
				 WHERE avghot = 
					(SELECT MAX(avghot)
					FROM (SELECT AVG(hotness) AS avghot,branchID,evid 
				 FROM `customer` c, `buysticketsfor` t 
				 WHERE c.cid = t.cid 
				 GROUP BY branchID, evid) 
				 AS T)";
		
		$result = $conn->query($sql4);
		//add echo saying it was successful if it inserted and error if it didn't
		
		$maxeventlist = "";
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$maxHot=$row["maxhot"];
				$branchID = $row["branchID"];
				$evid = $row["evid"];
				if (isset($eventnamelist[$evid]) && isset($venuelist[$branchID])){
					$maxeventlist .=  '<tr><td>'.$eventnamelist[$evid].' at '.$venuelist[$branchID].'</td><td>'.$maxHot.'</td></tr>';
				}
				
			}
		}

		if($result->num_rows == 0) {
			echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>No result to display.</div>";
		} else {
			if($maxeventlist != "") {

				echo '	<table style="width:30%">
					<tr>
				    <th>Event</th> 
				    <th>max hotness</th>
					</tr>
					'.$maxeventlist.'
					</table>';
			}
		}
	

		$conn->close();
		
	}
	?>


<br>

<h4>Event with Minimum hotness</h4>

<?php
$minhot = ' <label for="minhot">Show lamest event:</label>';

echo '<form action="#" class="form-inline" method="post">'.$minhot.'
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
	
	
	$sqlv = "SELECT name, branchID FROM `venue`";

	$venuelist = array();
	$venues = $conn->query($sqlv);
        if($venues->num_rows > 0) {
            while($row = $venues->fetch_assoc()) {
                $venueName = $row["name"];
                $branchID = $row["branchID"];
                $venuelist[$branchID] = $venueName;
            }
        } else {
        	echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>No venues to display.</div>";
        }
		
	$sqle = "SELECT name, evid FROM `hostedevent`";
	
	$eventnamelist = array();
	$events = $conn->query($sqle);
        if($events->num_rows > 0) {
            while($row = $events->fetch_assoc()) {
                $eventName = $row["name"];
                $evid = $row["evid"];
                $eventnamelist[$evid] = $eventName;
            }
        } else {
        	echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>No events to display.</div>";
        }
		
		$sql5 = "SELECT MIN(avghot) AS minhot,branchID,evid 
				FROM
				(SELECT AVG(hotness) AS avghot,branchID,evid 
				 FROM `customer` c, `buysticketsfor` t 
				 WHERE c.cid = t.cid 
				 GROUP BY branchID, evid) 
				 AS T
				 WHERE avghot = 
					(SELECT MIN(avghot)
					FROM (SELECT AVG(hotness) AS avghot,branchID,evid 
				 FROM `customer` c, `buysticketsfor` t 
				 WHERE c.cid = t.cid 
				 GROUP BY branchID, evid) 
				 AS T)";
						
		$minresult = $conn->query($sql5);
		//add echo saying it was successful if it inserted and error if it didn't
		
		$mineventlist = "";
		if ($minresult->num_rows > 0) {
			// output data of each row
			while($row = $minresult->fetch_assoc()) {
				$minHot=$row["minhot"];
				$branchID = $row["branchID"];
				$evid = $row["evid"];
				if(isset($eventnamelist[$evid]) && isset($venuelist[$branchID])) {
					$mineventlist .=  '<tr><td>'.$eventnamelist[$evid].' at '.$venuelist[$branchID].'</td><td>'.$minHot.'</td></tr>';
				}
				
			}
		}

		if($minresult->num_rows == 0) {
			echo "<br><div style='border-style: solid; border-color: red; background-color:#f2d7d5; padding:10px;'>No result to display.</div>";
		} else {
			if($mineventlist != "") {
				echo '	<table style="width:30%">
					<tr>
				    <th>Event</th> 
				    <th>min hotness</th>
					</tr>
					'.$mineventlist.'
					</table>';	
				
				
			}
		}

		$conn->close();
		
	}
	?>

<br>

</div>

</body>
</html>

 <?php
}
?>
