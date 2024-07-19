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
	
?>

<?php include("lib/header.php"); ?>
		<title>BuzzBuy View Manufacturer's Product</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"><?php print $user_name; ?></div>          
					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">View Manufacturer's Product</div>   
							<table>
								<tr>
									<td class="heading">Manufacturer's Name</td>
									<td class="heading">Total Number of Products</td>
									<td class="heading">Average Retail Price</td>
									<td class="heading">Minimum Retail Price</td>
									<td class="heading">Maximum Retail Price</td>
								</tr>
																
								<?php								
                                    $query = "SELECT Manufacturer.ManufacturerName, COUNT(Product.PID) AS ProductCount, AVG(Product.RetailPrice) AS AveragePrice, MAX(Product.RetailPrice) AS MaxPrice, MIN(Product.RetailPrice) AS MinPrice
											 FROM Manufacturer JOIN Product ON Product.ManufacturerName = Manufacturer.ManufacturerName
											 GROUP BY Manufacturer.ManufacturerName
											 ORDER BY AveragePrice DESC
											 LIMIT 100" ;
                                             
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: find Friendship <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										$manufacturerName = urlencode($row['ManufacturerName']);
                                        print "<tr>";
                                        print "<td>{$row['ManufacturerName']}</td>";
                                        print "<td>{$row['ProductCount']}</td>";
                                        print "<td>{$row['AveragePrice']}</td>";
										print "<td>{$row['MaxPrice']}</td>";
										print "<td>{$row['MinPrice']}</td>";
										print "<td><a href='manufacturer_details.php?manufacturer=$manufacturerName'>View Details</a></td>";
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
		<!-- add a log entry -->
		<!-- JL: report_name must be one of names defined in our "report" table, otherwise a log entry won't be added to the table. --> 
		<?php 
			$report_name = "Manufacturer's Product Report";
			$timestamp = date("Y-m-d H:i:s");
	
			// Escape variables for safety
			$escaped_employeeid = mysqli_real_escape_string($db, $_SESSION['employeeid']);
			$escaped_timestamp = mysqli_real_escape_string($db, $timestamp);
			$escaped_report_name = mysqli_real_escape_string($db, $report_name);
			
			$audit_query = "INSERT INTO auditlogentry (employeeid, reportname, timestamp) VALUES ('{$escaped_employeeid}', '{$escaped_report_name}', '{$escaped_timestamp}');";

			// Execute the query
			$result = mysqli_query($db, $audit_query);
			
    		include('lib/show_queries.php');

			if ($result === False) {
				array_push($error_msg, "Error: Failed to add Audit Log Entry: " . mysqli_error($db));
			}
		?>
	</body>
</html>