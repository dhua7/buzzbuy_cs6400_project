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
$report_name = "Report: Store Revenue by Year by State";
$timestamp = date("Y-m-d H:i:s");

// Escape variables for safety
$escaped_employeeid = mysqli_real_escape_string($db, $_SESSION['employeeid']);
$escaped_timestamp = mysqli_real_escape_string($db, $timestamp);
$escaped_report_name = mysqli_real_escape_string($db, $report_name);

$audit_query = "INSERT INTO AuditLogEntry (employeeid, timestamp, reportName) VALUES ('$escaped_employeeid', '$escaped_timestamp', '$escaped_report_name')";
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
					 </div> 
				</div> 
                
                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 
			</div>    

               <?php include("lib/footer.php"); ?>
		 
		</div>
	</body>
</html>