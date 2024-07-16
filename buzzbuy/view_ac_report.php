<?php

include('lib/common.php');
// written by Team 34

if (!isset($_SESSION['employeeid'])) {
	header('Location: login.php');
	exit();
}

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
$result = mysqli_query($db, $audit_query);

include('lib/show_queries.php');

if ($result === false) {
	array_push($error_msg, "Error: Failed to add Audit Log Entry: " . mysqli_error($db));
} 

}


?>

<?php include("lib/header.php"); ?>
		<title>Report: Air Conditioners on Groundhog Day?</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"><?php print $user_name; ?></div>          
					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Report: Air Conditioners on Groundhog Day?</div>   
							<table>
								<tr>
									<td class="heading">Year</td>
									<td class="heading">Total AC Units Sold</td>
									<td class="heading">Average AC units sold per day</td>
									<td class="heading">Total AC Units Sold on GroundHog Day</td>
								</tr>
																
								<?php								
                                    $query = "SELECT YEAR(BusinessDay.BusinessDate) as Year, " .
									"SUM(CASE WHEN LOWER(Assignto.CategoryName) = 'air conditioning' THEN Sells.QuantitySold ELSE 0 END) AS TotalACUnitsSold, " .
									"SUM(CASE WHEN LOWER(Assignto.CategoryName) = 'air conditioning' THEN Sells.QuantitySold ELSE 0 END) / 365 AS AVGUnitsSoldPerDay, " .
									"SUM(CASE WHEN MONTH(BusinessDay.BusinessDate) = '02' AND DAY(BusinessDay.BusinessDate) = '02' THEN Sells.QuantitySold ELSE 0 END) AS GroundhogDaySales ".
									"FROM  BusinessDay JOIN  Discount ON BusinessDay.BusinessDate = Discount.BusinessDate JOIN  Product ON Discount.PID = Product .PID ". 
									"JOIN  Assignto ON Product .PID = Assignto.PID JOIN  Sells ON Assignto.PID = Sells.PID " .
									"GROUP BY YEAR(BusinessDay.BusinessDate) " .
									"ORDER BY Year ASC"; 
                                             
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: find Report <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['Year']}</td>";
                                        print "<td>{$row['TotalACUnitsSold']}</td>";
                                        print "<td>{$row['AVGUnitsSoldPerDay']}</td>";
										print "<td>{$row['GroundhogDaySales']}</td>";
                                        print "</tr>";							
                                    }									
                                ?>
							</table>						
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


