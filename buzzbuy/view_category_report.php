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
		<title>BuzzBuy View Holidays</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"><?php print $user_name; ?></div>          
					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">View Category</div>   
							<table>
								<tr>
									<td class="heading">Category Name</td>
									<td class="heading">Total Products</td>
									<td class="heading">Total Manufacturers</td>
									<td class="heading">Average Retail Prices</td>
								</tr>
																
								<?php								
                                    $query = "SELECT assignto.CategoryName, 
                                             COUNT(DISTINCT product.PID) AS TotalProducts, 
                                             COUNT(DISTINCT product.ManufacturerName) AS TotalManufacturers, 
                                             AVG(product.RetailPrice) AS AvgRetailPrice 
											 FROM assignto 
											 JOIN product ON assignto.PID = product.PID 
											 GROUP BY assignto.CategoryName 
											 ORDER BY assignto.CategoryName ASC";
                                             
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: find Category <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['CategoryName']}</td>";
										print "<td>{$row['TotalProducts']}</td>";
										print "<td>{$row['TotalManufacturers']}</td>";
										print "<td>{$row['AvgRetailPrice']}</td>";
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