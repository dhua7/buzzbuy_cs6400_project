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
            </div>          
			
        </div> 

                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
			</div>    

               <?php include("lib/footer.php"); ?>
				 
		</div>
	</body>
</html>