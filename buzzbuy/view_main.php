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
<title>BuzzBuy Main</title>
</head>

<body>
	<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name">
                <?php print $row['firstname'] . ' ' . $row['lastname']; ?>
				<br/><br/>
				<h>Welcome to BuzzBuy!</h>
				
				<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">View Total Number of</div>   
							<table>
								<tr>
									<td class="heading">Stores</td>
									<td class="heading">Cities</td>
									<td class="heading">Districts</td>
									<td class="heading">Manufacturers</td>
									<td class="heading">Products</td>
									<td class="heading">Categories</td>
									<td class="heading">Holidays</td>
								</tr>
																
								<?php								
                                    $query = "SELECT	
												(SELECT COUNT(DISTINCT StoreNumber) FROM store) AS Store_count,
												(SELECT COUNT(DISTINCT CityName) FROM city) AS City_count,
												(SELECT COUNT(DISTINCT DistrictNumber) FROM district) AS District_count,
												(SELECT COUNT(DISTINCT ManufacturerName) FROM manufacturer) AS Manufacturer_count,
												(SELECT COUNT(DISTINCT PID) FROM product) AS Product_count,
												(SELECT COUNT(DISTINCT CategoryName) FROM category) AS Category_count,
												(SELECT COUNT(*) FROM holidays) AS Holiday_count";
                                             
                                    $result = mysqli_query($db, $query);
									include('lib/show_queries.php');
                                    if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: find Holidays <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['Store_count']}</td>";
                                        print "<td>{$row['City_count']}</td>";
										print "<td>{$row['District_count']}</td>";
										print "<td>{$row['Manufacturer_count']}</td>";
										print "<td>{$row['Product_count']}</td>";
										print "<td>{$row['Category_count']}</td>";
										print "<td>{$row['Holiday_count']}</td>";
                                        print "</tr>";							
                                    }									
                                ?>
							</table>						
						</div>
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