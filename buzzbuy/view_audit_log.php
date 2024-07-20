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

// SQL query to check access
$employeeid = $_SESSION['employeeid'];

$auditAccessQuery = "SELECT AccessToAuditLog 
From User 
WHERE EmployeeID = '$employeeid'";

// Execute query
$auditAccessResult = mysqli_query($db, $auditAccessQuery);

// Check if user has access
$hasAccess = false;
if ($auditAccessResult->num_rows > 0) {
	while($row = $auditAccessResult->fetch_assoc()) {
		if ($row['AccessToAuditLog'] == true) {
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
	
?>

<?php include("lib/header.php"); ?>
<style>
    .highlight {
        background-color: yellow;
		color: black
    }
	table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }
</style>

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
                                    $query = "SELECT 
                                        AuditLogEntry.EmployeeID, 
                                        AuditLogEntry.TimeStamp, 
                                        AuditLogEntry.ReportName,
                                        CASE 
                                            WHEN (
                                                SELECT COUNT(CanAccess.DistrictNumber)
                                                FROM CanAccess
                                                WHERE CanAccess.EmployeeID = AuditLogEntry.EmployeeID
                                                GROUP BY CanAccess.EmployeeID
                                                HAVING COUNT(CanAccess.DistrictNumber) = (SELECT COUNT(DistrictNumber) FROM District)
                                            ) IS NOT NULL THEN 'highlight'
                                            ELSE ''
                                        END AS Highlight
                                    FROM 
                                        AuditLogEntry
                                    ORDER BY 
                                        AuditLogEntry.timestamp DESC, 
                                        AuditLogEntry.EmployeeID ASC
									LIMIT 100";
                                             
                                    $result = mysqli_query($db, $query);
                                    include('lib/show_queries.php');
                                    
                                    if (!empty($result) && (mysqli_num_rows($result) == 0)) {
                                        array_push($error_msg, "SELECT ERROR: find AuditLogEntry <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                        $highlightClass = $row['Highlight'];
                                        echo "<tr class='$highlightClass'>";
                                        echo "<td>{$row['EmployeeID']}</td>";
                                        echo "<td>{$row['TimeStamp']}</td>";
                                        echo "<td>{$row['ReportName']}</td>";
                                        echo "</tr>";                            
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