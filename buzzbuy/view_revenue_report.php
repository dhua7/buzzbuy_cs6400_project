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

?>

<?php include("lib/header.php"); ?>
		<title>Buzzbuy View Actual vs. Predicted Revenue for GPS Units</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"><?php print $user_name; ?></div>          
					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">View Actual vs. Predicted Revenue for GPS Units</div>   
							<table>
								<tr>
									<td class="heading">Product ID</td>
									<td class="heading">Product Name</td>
									<td class="heading">Retail Price</td>
									<td class="heading">Units Sold</td>
									<td class="heading">Units Sold at Discount</td>
									<td class="heading">Units Sold at Retail Price</td>
									<td class="heading">Actual Revenue</td>
									<td class="heading">Predicted Revenue</td>
									<td class="heading">Revenue Difference</td>
								</tr>
																
								<?php								
                                    $query = "SELECT
                                              TS.PID,
                                              TS.ProductName,
                                              TS.RetailPrice,
                                              TS.TotalUnitsSold,
                                              CASE WHEN DS.DiscountUnitsSold IS NULL THEN 0 ELSE DS.DiscountUnitsSold END AS DiscountedUnitsSold,
                                              TS.TotalUnitsSold - CASE WHEN DS.DiscountUnitsSold IS NULL THEN 0 ELSE DS.DiscountUnitsSold END AS NonDiscountedUnitsSold,
                                              TS.TotalUnitsSold * TS.RetailPrice - CASE WHEN DS.DiscountedRevenue IS NULL THEN 0 ELSE DS.DiscountedRevenue END AS ActualRevenue,
                                              TS.TotalUnitsSold * TS.RetailPrice * 0.75 AS PredictedRevenue,
                                              (TS.TotalUnitsSold * TS.RetailPrice - CASE WHEN DS.DiscountedRevenue IS NULL THEN 0 ELSE DS.DiscountedRevenue END) - (TS.TotalUnitsSold * TS.RetailPrice * 0.75) AS RevenueDifference
                                              FROM (
                                              SELECT
                                              Product.PID,
                                              Product.ProductName,
                                              Product.RetailPrice,
                                              SUM(Sells.QuantitySold) AS TotalUnitsSold
                                              FROM
                                              Product
                                              JOIN Sells ON Product.PID = Sells.PID
                                              JOIN Assignto ON Product.PID = Assignto.PID
                                              WHERE
                                              Assignto.CategoryName = 'GPS'
                                              GROUP BY
                                              Product.PID, Product.ProductName, Product.RetailPrice
                                              ) TS
                                              LEFT JOIN (
                                              SELECT
                                              Discount.PID,
                                              SUM(Sells.QuantitySold) AS DiscountUnitsSold,
                                              SUM(Sells.QuantitySold * Discount.DiscountPrice) AS DiscountedRevenue
											  FROM
                                              Discount
                                              JOIN Sells ON Discount.PID = Sells.PID AND Discount.BusinessDate = Sells.DateSold
                                              GROUP BY
                                              Discount.PID
                                              ) DS ON TS.PID = DS.PID
                                              WHERE
                                              ABS((TS.TotalUnitsSold * TS.RetailPrice - CASE WHEN DS.DiscountedRevenue IS NULL THEN 0 ELSE DS.DiscountedRevenue END) - (TS.TotalUnitsSold * TS.RetailPrice * 0.75)) > 200
                                              ORDER BY
                                              RevenueDifference DESC";
                                             
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: find Friendship <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['PID']}</td>";
                                        print "<td>{$row['ProductName']}</td>";
                                        print "<td>{$row['RetailPrice']}</td>";
										print "<td>{$row['TotalUnitsSold']}</td>";
										print "<td>{$row['DiscountedUnitsSold']}</td>";
										print "<td>{$row['NonDiscountedUnitsSold']}</td>";
										print "<td>{$row['ActualRevenue']}</td>";
										print "<td>{$row['PredictedPrice']}</td>";
										print "<td>{$row['RevenueDifference']}</td>";
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
			$report_name = "Actual vs Predicted Revenue";
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