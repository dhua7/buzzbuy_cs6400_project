<?php

include('lib/common.php');
// written by GTusername3

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

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
                                    $query = "SELECT strftime('%Y', BusinessDay.BusinessDate) AS Year," .  
											 "SUM(CASE WHEN LOWER(Assignto.CategoryName) = 'air conditioning' THEN Sells.QuantitySold ELSE 0 END) AS TotalACUnitsSold, " .
											 "SUM(CASE WHEN LOWER(Assignto.CategoryName) = 'air conditioning' THEN Sells.QuantitySold ELSE 0 END) / 365 AS AVGUnitsSoldPerDay, " .
											 "SUM(CASE WHEN strftime('%m', BusinessDay.BusinessDate) = '02' AND strftime('%d', BusinessDay.BusinessDate) = '02' THEN Sells.QuantitySold ELSE 0 END) AS GroundhogDaySales " .
											 "FROM BusinessDay JOIN Discount ON BusinessDay.BusinessDate = Discount.BusinessDate JOIN Product ON Discount.PID = Product.PID  " .
											 "JOIN Assignto ON Product .PID = Assignto.PID JOIN Sells ON Assignto.PID = Sells.PID " .  
											 "GROUP BY strftime('%Y', BusinessDay.BusinessDate) " .
											 "ORDER BY Year ASC" ; 
                                             
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: find Friendship <br>" . __FILE__ ." line:". __LINE__ );
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