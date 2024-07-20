<?php

include('lib/common.php');

//----------------------------------------------------------------
// written by Team 34
//
// this is to get a currently signed-in user's employee id from a client side session 
// also, this displays the SQL command that was used to get a user's information too.

if (!isset($_SESSION['employeeid'])) {
	header('Location: login.php');
	exit();
}

//--
// SQL query to check access
$corpQuery = "SELECT User.EmployeeID, COUNT(CanAccess.DistrictNumber) as CountDistrictAccess 
        FROM User 
        JOIN CanAccess ON User.EmployeeID = CanAccess.EmployeeID 
        GROUP BY User.EmployeeID 
        HAVING COUNT(CanAccess.DistrictNumber) = (SELECT COUNT(DistrictNumber) FROM District)";

// Execute query
$corpResult = mysqli_query($db, $corpQuery);

// Check if user has access
$hasAccess = false;
if ($corpResult->num_rows > 0) {
    while($row = $corpResult->fetch_assoc()) {
        if ($row['EmployeeID'] == $_SESSION['employeeid']) {
            $hasAccess = true;
            break;
        }
    }
}

if (!$hasAccess) {
    echo "<script>
            alert('Access Denied');
            window.location.href = '" . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'view_main.php') . "';
          </script>";
    exit();
}
//--

// just to display a signed-in user's information 
$query = "SELECT firstname, lastname " .
		 "FROM User " .
		 "WHERE User.employeeid='{$_SESSION['employeeid']}'";

$result = mysqli_query($db, $query);
include('lib/show_queries.php');
 
if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
    array_push($error_msg,  "Query ERROR: Failed to get User information...<br>" . __FILE__ ." line:". __LINE__ );
}
    
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$h_name = mysqli_real_escape_string($db, $_POST['h_name']);
	$h_date = mysqli_real_escape_string($db, $_POST['h_date']);  
	if (empty($h_name)) {
			array_push($error_msg,  "Please enter a holiday name.");
	} 

	if (!is_date($h_date)) {
		array_push($error_msg,  "Error: Invalid holiday date ");
	}
	
	$date_found = false;
	
	if ( !empty($h_date) && !empty($h_name) )   { 
		$q_find = "SELECT COUNT(*) AS H_FOUND FROM holidays WHERE businessdate = '$h_date'"; 
		$q_result = mysqli_query($db, $q_find);
		include('lib/show_queries.php');
		if ( !is_bool($q_result) && (mysqli_num_rows($q_result) > 0) ) {
			while ($row = mysqli_fetch_array($q_result, MYSQLI_ASSOC)) {
				$r_found = $row["H_FOUND"];
				if ($r_found > 0) {
					$date_found = true;
				}
				break;
			}
		}
		if ($date_found) { 
			// the same business date is found in the holidays table 
			echo "<script>alert('The date is already in Holidays');</script>";
		} 
		else {
	
			// add a new holiday record 
			$query = "INSERT INTO holidays " .
					"(holidayname, businessdate) " .
					"VALUES('$h_name','$h_date') ";

			$queryID = mysqli_query($db, $query);
			include('lib/show_queries.php');
		
			if ($queryID == False) {
				array_push($error_msg,  "INSERT ERROR: Holiday... <br>".  __FILE__ ." line:". __LINE__ );
				//array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
			} else {
				// add a new holiday record 
				$query = "INSERT INTO created " .
						 "(businessdate, employeeid) " .
						 "VALUES('$h_date', '{$_SESSION['employeeid']}') ";

				$queryID = mysqli_query($db, $query);
				
				$message = "A new holiday record was added successfully!";
				echo "<script>alert('$message');</script>"; 
			}
		}
	}

}  //end of if($_POST)


function is_date( $str ) { 
	$stamp = strtotime( $str ); 
	if (!is_numeric($stamp)) { 
		return false; 
	} 
	$month = date( 'm', $stamp ); 
	$day   = date( 'd', $stamp ); 
	$year  = date( 'Y', $stamp ); 
  
	if (checkdate($month, $day, $year)) { 
		return true; 
	} 
	return false; 
} 

?>


<?php include("lib/header.php"); ?>
		<title>BuzzBuy Add Holiday</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name"></div>          
					<div class="features">   
						
                        <div class="profile_section">
							<div class="subtitle">Add Holiday</div>   
                            
							<form name="profileform" action="add_holiday.php" method="post">
								<table>
									<tr>
										<td class="item_label">Holiday Name</td>
										<td>
											<input type="text" name="h_name" value="<?php if ($row['h_name']) { print $row['h_name']; } ?>" />	
										</td>
									</tr>
									<tr>
										<td class="item_label">Date</td>
										<td>
											<input type="text" name="h_date" value="<?php if ($row['h_date']) { print $row['h_date']; } ?>" />										
										</td>
									</tr>
									
								</table>
								
								<a href="javascript:profileform.submit();" class="fancy_button">Save</a> 
							
							</form>
						</div>
                        
					 </div> 	
				</div> 
                
                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
			</div>    

               <?php include("lib/footer.php"); ?>
				 
		</div>
	</body>
</html>