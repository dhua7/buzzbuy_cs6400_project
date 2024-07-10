<?php

include('lib/common.php');
// written by GTusername2

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

    $friend_email = mysqli_real_escape_string($db, $_REQUEST['friend_email']);

    $query = "SELECT first_name, last_name, home_town " . 
		 "FROM User " .
		 "INNER JOIN RegularUser on RegularUser.email = User.email " .
		 "WHERE RegularUser.email = '$friend_email'";

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');

if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
    
    $friend_name = $row['first_name'] . " " . $row['last_name'];
    $home_town = $row['home_town'] ;
} else {
    array_push($error_msg,  "SELECT ERROR: friend email: " . $friend_email ."<br>".  __FILE__ ." line:". __LINE__ );
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$relationship = mysqli_real_escape_string($db, $_REQUEST['relationship']);
	$friend_email = mysqli_real_escape_string($db, $_REQUEST['friend_email']);

	if (empty($relationship)) {
        array_push($error_msg,  "Error: You must provide a relationship ");
	}
	
	if ( !empty($friend_email) && !empty($relationship) )   { 
		$friend_name = mysqli_real_escape_string($db, $_POST['friend_name']);
		$home_town = mysqli_real_escape_string($db, $_POST['home_town']);

		$query = "INSERT INTO Friendship (email, friend_email, relationship, date_connected) " .
				 "VALUES ('{$_SESSION['email']}', '$friend_email', '$relationship', NULL)";

        $queryID = mysqli_query($db, $query);
            
        include('lib/show_queries.php');

        if ($queryID  == False) {
                 array_push($error_msg, "INSERT ERROR: friends email: " . $friend_email.  " relation: " . $relationship ."<br>". __FILE__ ." line:". __LINE__ );
          } 
            
        array_push($query_msg, "sending request ... ");
        header(REFRESH_TIME . 'url=view_requests.php');		
	}

}
?>

<?php include("lib/header.php"); ?>
		<title>GTOnline Friend Request</title>
	</head>

	<body>
		<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">
				<div class="center_left">
					<div class="title_name">Request Friend</div>          
					<div class="features">   
						
						<div class="profile_section">
							<div class="subtitle">Request Friend</div>   
							<form name="requestform" action="request_friend.php" method="POST">
							<table>								
								<tr>
									<td class="item_label">Name</td>
									<td><?php print $friend_name; ?></td>
								</tr>
								<tr>
									<td class="item_label">Hometown</td>
									<td><?php print $home_town; ?></td>
								</tr>
								<tr>
									<td class="item_label">Relationship</td>
									<td><input type="text" name="relationship" /></td>
								</tr>
							</table>
							
							<input type="hidden" name="friend_name" value="<?php print $friend_name; ?>" />
							<input type="hidden" name="home_town" value="<?php print $home_town; ?>" />
							<input type="hidden" name="friend_email" value="<?php print $friend_email; ?>" />
							
							<a href="javascript:requestform.submit();" class="fancy_button">Send</a> 
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