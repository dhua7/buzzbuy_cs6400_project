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

// Check if the user has access to audit logs
$sql = "SELECT AccessToAuditLog FROM User WHERE EmployeeID = '{$_SESSION['employeeid']}'";
$result2 = $conn->query($sql);
if ($result2 && $row = $result2->fetch_assoc()) {
    $accessToAuditLog = $row['AccessToAuditLog'];

    // Check if the user is granted access
    if ($accessToAuditLog) {
        // User has access, retrieve the top 100 most recent audit logs
        $sql = "SELECT TOP 100 AuditLogEntry.ReportName, User.EmployeeID,User.FirstName, User.LastName, AuditLogEntry.TimeStamp " . 
			   "FROM AuditLogEntry JOIN User ON AuditLogEntry.EmployeeID = User.EmployeeID " .
			   "ORDER BY AuditLogEntry.TimeStamp DESC ";
        $result = $conn->query($sql);

	}
}


?>

<?php include("lib/header.php"); ?>
		<title>View Audit Log</title>
	</head>
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"><?php print $user_name; ?></div>          
					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">View Audit Log</div>   
							<table>
								<tr>
									<td class="heading">Year</td>
									<td class="heading">Total AC Units Sold</td>
									<td class="heading">Average AC units sold per day</td>
									<td class="heading">Total AC Units Sold on GroundHog Day</td>
								</tr>
																
								<?php								
                                    if (isset($result2)) {
										while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)){
											print "<tr>";
											print "<td>{$row['ReportName']}</td>";
											print "<td>{$row['EmployeeID']}</td>";
											print "<td>{$row['FirstName']}</td>";
											print "<td>{$row['LastName']}</td>";	
											print "<td>{$row['TimeStamp']}</td>";											
											print "</tr>";
										}			
									} ?>
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


