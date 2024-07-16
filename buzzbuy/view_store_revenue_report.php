<?php

include('lib/common.php');
<<<<<<< HEAD
// written by Team 34

if (!isset($_SESSION['employeeid'])) {
=======
// written by GTusername3

if (!isset($_SESSION['email'])) {
>>>>>>> main
	header('Location: login.php');
	exit();
}

<<<<<<< HEAD
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

/* if form was submitted, then execute query to search for friends */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
	$state = mysqli_real_escape_string($db, $_POST['state']);

		
	$query = "SELECT store.StoreNumber, city.CityName, YEAR(sells.DateSold) AS Year, " .
    		 "SUM(CASE WHEN discount.BusinessDate IS NOT NULL AND sells.DateSold = discount.BusinessDate " .
             "THEN sells.QuantitySold * discount.DiscountPrice ELSE sells.QuantitySold * product.RetailPrice END) AS TotalRevenue " .
			 "FROM Sells JOIN Store ON sells.StoreNumber = store.StoreNumber JOIN city ON store.CityName = city.CityName " . 
			 "JOIN Product ON sells.PID = product.PID LEFT JOIN Discount ON sells.PID = discount.PID AND sells.DateSold = discount.BusinessDate " .
			 " WHERE City.State='$state' " .
			 "GROUP BY store.StoreNumber, city.CityName, Year " .
			 "ORDER BY Year ASC, totalRevenue DESC ";
    
	$result2 = mysqli_query($db, $query);
    if (!$result2) {
		die("Query failed: " . $mysqli->error);
	}
    include('lib/show_queries.php');

    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg,  "SELECT ERROR:Failed to complete search ... <br>" . __FILE__ ." line:". __LINE__ );
    }
		
}

// Create an entry in the audit log
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$report_name = "Report: Air Conditioners on Groundhog Day?";
	$timestamp = date("Y-m-d H:i:s");
	
	// Escape variables for safety
	$escaped_employeeid = mysqli_real_escape_string($db, $_SESSION['employeeid']);
	$escaped_timestamp = mysqli_real_escape_string($db, $timestamp);
	$escaped_report_name = mysqli_real_escape_string($db, $report_name);
	
	
	$audit_query = "INSERT INTO AuditLogEntry (employeeid, timestamp, reportName) VALUES ('$escaped_employeeid', '$escaped_timestamp', '$escaped_report_name')";
	
	// Execute the query
	$result3 = mysqli_query($db, $audit_query);
	
	include('lib/show_queries.php');
	
	if ($result3 === false) {
		array_push($error_msg, "Error: Failed to add Audit Log Entry: " . mysqli_error($db));
	} 
	
	}
?>

<?php include("lib/header.php"); ?>
<title>Report: Revenue by Year by State</title>
	</head>
	
	<body>
    	<div id="main_container">
            <?php include("lib/menu.php"); ?>
			
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"><?php print $user_name; ?></div>          			
					<div class="features">   
						
						<div class="profile_section">						
							<div class="subtitle">Search for State</div> 
							
							<form name="searchform" action="view_store_revenue_report.php" method="POST">
								<table>								
									<tr>
										<td class="item_label">State</td>
										<td><input type="text" name="state" /></td>
									</tr>
									
								</table>
									<a href="javascript:searchform.submit();" class="fancy_button">Search</a> 					
							</form>							
						</div>
						
						<div class='profile_section'>
						<div class='subtitle'>Search Results</div>
						<table>
							<tr>
								<td class='heading'>Store Number</td>
								<td class='heading'>City</td>
								<td class='heading'>Year</td>
								<td class='heading'>Total Revenue by Year</td>
							</tr>
								<?php
									if (isset($result2)) {
										while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)){
											print "<tr>";
											print "<td>{$row['StoreNumber']}</td>";
											print "<td>{$row['CityName']}</td>";
											print "<td>{$row['Year']}</td>";	
											print "<td>{$row['TotalRevenue']}</td>";											
											print "</tr>";
										}
									}	?>
							</table>
							</div>
=======
$query = "SELECT first_name, last_name " .
		 "FROM User " .
		 "INNER JOIN RegularUser ON User.email = RegularUser.email " .
		 "WHERE User.email = '{$_SESSION['email']}'";
         
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
    
if (!empty($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
    $user_name = $row['first_name'] . " " . $row['last_name'];
} else {
        array_push($error_msg,  "SELECT ERROR: User profile <br>" . __FILE__ ." line:". __LINE__ );
}

?>

<?php include("lib/header.php"); ?>
		<title>GTOnline View Friends</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"><?php print $user_name; ?></div>          
					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">View Friends</div>   
							<table>
								<tr>
									<td class="heading">Name</td>
									<td class="heading">Relationship</td>
									<td class="heading">Connected Since</td>
								</tr>
																
								<?php								
                                    $query = "SELECT first_name, last_name, relationship, date_connected " .
                                             "FROM Friendship " .
                                             "INNER JOIN RegularUser ON RegularUser.email = Friendship.friend_email " .
                                             "INNER JOIN User ON User.email = RegularUser.email " .
                                             "WHERE Friendship.email='{$_SESSION['email']}'" .
                                             "AND date_connected IS NOT NULL " .
                                             "ORDER BY date_connected DESC";
                                             
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: find Friendship <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['first_name']} {$row['last_name']}</td>";
                                        print "<td>{$row['relationship']}</td>";
                                        print "<td>{$row['date_connected']}</td>";
                                        print "</tr>";							
                                    }									
                                ?>
							</table>						
						</div>	
>>>>>>> main
					 </div> 
				</div> 
                
                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 
			</div>    
<<<<<<< HEAD
            
               <?php include("lib/footer.php"); ?>
		 
		</div>
	</body>	
=======

               <?php include("lib/footer.php"); ?>
		 
		</div>
	</body>
>>>>>>> main
</html>