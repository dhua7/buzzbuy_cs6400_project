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
		<title>GTOnline View Revenue by Population</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"><?php print $user_name; ?></div>          
					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Revenue by Population</div>   
							<table>
								<tr>
									<td class="heading">Year</td>
									<td class="heading">City Size</td>
									<td class="heading">Total Revenue</td>
								</tr>
																
								<?php								
                                    $query = "SELECT YEAR(BusinessDay.BusinessDate) AS Year,
                                             CASE 
                                             WHEN City.Population < 3700000 THEN 'Small'
                                             WHEN City.Population >= 3700000 AND City.Population < 6700000 THEN 'Medium'
                                             WHEN City.Population >= 6700000 AND City.Population < 9000000 THEN 'Large'
                                             ELSE 'Extra Large'
                                             END AS CitySize,
                                             SUM(Sells.QuantitySold * Product.RetailPrice) AS TotalRevenue
                                             FROM Sells
                                             JOIN Store ON Sells.StoreNumber = Store.StoreNumber
                                             JOIN City ON Store.CityName = City.CityName 
                                             JOIN Product ON Sells.PID = Product.PID
                                             JOIN BusinessDay ON Sells.DateSold = BusinessDay.BusinessDate
                                             GROUP BY Year, CitySize
                                             ORDER BY Year ASC, CitySize ASC";
                                             
                                    $result = mysqli_query($db, $query);
                                    if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                        array_push($error_msg,  "SELECT ERROR: find Revenue by Population <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['Year']}</td>";
                                        print "<td>{$row['CitySize']}</td>";
                                        print "<td>\${$row['TotalRevenue']}</td>";
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
            $report_name = "Holidays";
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
