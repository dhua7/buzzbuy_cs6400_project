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
    
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$h_name = mysqli_real_escape_string($db, $_POST['h_name']);
	$h_date = mysqli_real_escape_string($db, $_POST['h_date']);  
	if (empty($h_name)) {
			array_push($error_msg,  "Please enter a holiday name.");
	} 

	if (!is_date($h_date)) {
		array_push($error_msg,  "Error: Invalid holiday date ");
	}
	

	 if ( !empty($h_date) && !empty($h_name) )   { 
		$query = "INSERT INTO holidays " .
				 "(holidayname, businessdate) " .
				 "VALUES('$h_name','$h_date') ";

		$queryID = mysqli_query($db, $query);
		include('lib/show_queries.php');
		
		 if ($queryID == False) {
			 array_push($error_msg,  "INSERT ERROR: Holiday... <br>".  __FILE__ ." line:". __LINE__ );
			 //array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
		 } else {
			echo '<script>alert("A new holiday record was added successfully!")</script>'; 
			header('Location: view_holiday.php');
		 }

	 }

}  //end of if($_POST)


function is_date( $str ) { 
	$stamp = strtotime( $str ); 
	if (!is_numeric($stamp)) { 
		return false; 
	} 
	$month = date( 'm', $stamp ); 
	$day   = date( 'd', $stamp ); 
	$year  = date( 'Y', $stamp ); 
  
	if (checkdate($month, $day, $year)) { 
		return true; 
	} 
	return false; 
} 

?>


<?php include("lib/header.php"); ?>
		<title>BuzzBuy Add Holiday</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name"></div>          
					<div class="features">   
						
                        <div class="profile_section">
							<div class="subtitle">Add Holiday</div>   
                            
							<form name="profileform" action="add_holiday.php" method="post">
								<table>
									<tr>
										<td class="item_label">Holiday Name</td>
										<td>
											<input type="text" name="h_name" value="<?php if ($row['h_name']) { print $row['h_name']; } ?>" />	
										</td>
									</tr>
									<tr>
										<td class="item_label">Date</td>
										<td>
											<input type="text" name="h_date" value="<?php if ($row['h_date']) { print $row['h_date']; } ?>" />										
										</td>
									</tr>
									
								</table>
								
								<a href="javascript:profileform.submit();" class="fancy_button">Save</a> 
							
							</form>
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