<?php

include('lib/common.php');
// written by GTusername3

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$query = "SELECT first_name, last_name " .
		 "FROM User " .
		 "WHERE User.email = '{$_SESSION['email']}'";
		 
$result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    
if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        $user_name = $row['first_name'] . " " . $row['last_name'];
} else {
        array_push($error_msg,  "SELECT ERROR: User profile <br>" . __FILE__ ." line:". __LINE__ );
}

  //run the search query right away after loading search_friends.php
	$query = "SELECT RegularUser.email, first_name, last_name, home_town " .
			 "FROM User " .
			 "INNER JOIN RegularUser ON RegularUser.email = User.email " .
			 "WHERE RegularUser.email NOT IN " .
			 "	(SELECT friend_email FROM Friendship WHERE email = '{$_SESSION['email']}') " . 
			 "AND RegularUser.email <> '{$_SESSION['email']}' ORDER BY last_name, first_name";
			 
	$result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    
    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg,  "SELECT ERROR:Failed to find friends ... <br>" . __FILE__ ." line:". __LINE__ );
    }
                                  

/* if form was submitted, then execute query to search for friends */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$home_town = mysqli_real_escape_string($db, $_POST['home_town']);
		
	$query = "SELECT RegularUser.email, first_name, last_name, home_town " .
			 "FROM User " .
			 "INNER JOIN RegularUser ON RegularUser.email = User.email " .
			 "WHERE RegularUser.email NOT IN " .
			 "	(SELECT friend_email FROM Friendship WHERE email = '{$_SESSION['email']}') " . 
			 "AND RegularUser.email <> '{$_SESSION['email']}'";
			 
	if (!empty($name) or !empty($email) or !empty($home_town)) {
		$query = $query . " AND (1=0 ";
		
		if (!empty($name)) {
			$query = $query . " OR first_name LIKE '%$name%' OR last_name LIKE '%$name%' ";
		}
		if (!empty($email)) {
			$query = $query . " OR RegularUser.email LIKE '%$email%' ";
		}
		if (!empty($home_town)) {
			$query = $query . " OR home_town LIKE '%$home_town%' ";
		}
		$query = $query . ") ";
	}
	
	$query = $query . " ORDER BY last_name, first_name";
    
	$result = mysqli_query($db, $query);
    
    include('lib/show_queries.php');

    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg,  "SELECT ERROR:Failed to find friends ... <br>" . __FILE__ ." line:". __LINE__ );
    }
		
}
?>

<?php include("lib/header.php"); ?>
		<title>GTOnline Friend Search</title>
	</head>
	
	<body>
    	<div id="main_container">
            <?php include("lib/menu.php"); ?>
			
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"><?php print $user_name; ?></div>          			
					<div class="features">   
						
						<div class="profile_section">						
							<div class="subtitle">Search for Friends</div> 
							
							<form name="searchform" action="search_friends.php" method="POST">
								<table>								
									<tr>
										<td class="item_label">Name</td>
										<td><input type="text" name="name" /></td>
									</tr>
									<tr>
										<td class="item_label">Email</td>
										<td><input type="text" name="email" /></td>
									</tr>
									<tr>
										<td class="item_label">Hometown</td>
										<td><input type="text" name="home_town" /></td>
									</tr>
									
								</table>
									<a href="javascript:searchform.submit();" class="fancy_button">Search</a> 					
							</form>							
						</div>
						
						<div class='profile_section'>
						<div class='subtitle'>Search Results</div>
						<table>
							<tr>
								<td class='heading'>Name</td>
								<td class='heading'>Hometown</td>
							</tr>
								<?php
									if (isset($result)) {
										while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
											$friend_email = urlencode($row['email']);
											print "<tr>";
											print "<td><a href='request_friend.php?friend_email=$friend_email'>{$row['first_name']} {$row['last_name']}</a></td>";
											print "<td>{$row['home_town']}</td>";									
											print "</tr>";
										}
									}	?>
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