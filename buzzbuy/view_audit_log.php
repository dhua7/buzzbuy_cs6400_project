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
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	$report_name = "Report: View Holidays";
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
		<title>BuzzBuy View Audit Log</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"></div>          
					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">View Audit Log</div>   
							<table>
								<tr>
									<td class="heading">Employee ID</td>
									<td class="heading">Time Stamp</td>
									<td class="heading">Report Name</td>
								</tr>
																
								<?php								
                                    $query = "SELECT employeeid, timestamp, reportname " .
                                             "FROM auditlogentry " .
                                             "ORDER BY timestamp DESC";
                                             
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: find AuditLogEntry <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['employeeid']}</td>";
                                        print "<td>{$row['timestamp']}</td>";
										print "<td>{$row['reportname']}</td>";
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